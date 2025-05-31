<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Person; // این خط را اضافه کن

class ServiceController extends Controller
{
    /**
     * نمایش فرم افزودن خدمت جدید
     */
    public function create()
    {
        $serviceCategories = Category::where('category_type', 'service')->get();
        $units = Unit::orderBy('title')->get();
        $shareholders = Person::where('type', 'shareholder')->get();

        $last = Service::where('service_code', 'like', 'services-%')
            ->orderByRaw('CAST(SUBSTRING(service_code, 10) AS UNSIGNED) DESC')
            ->first();
        if ($last && preg_match('/^services-(\d+)$/', $last->service_code, $m)) {
            $nextCode = 'services-' . (intval($m[1]) + 1);
        } else {
            $nextCode = 'services-1001';
        }

        return view('services.create', compact('serviceCategories', 'units', 'shareholders', 'nextCode'));
    }

    /**
     * لیست خدمات
     */
    public function index(Request $request)
    {
        $serviceCategories = Category::where('category_type', 'service')->get();
        $units = Unit::orderBy('title')->get();

        $query = Service::query();

        if ($request->filled('name')) {
            $query->where('title', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('service_category_id', $request->input('category_id'));
        }
        if ($request->filled('unit')) {
            $query->where('unit', $request->input('unit'));
        }

        // مقداردهی پیش‌فرض برای مرتب‌سازی
        $sort = $request->get('sort', 'id');
        $dir = $request->get('dir', 'desc');
        if (!in_array($sort, ['title', 'service_code', 'price', 'sells_count', 'profit_sum', 'id'])) $sort = 'id';
        if (!in_array($dir, ['asc', 'desc'])) $dir = 'desc';

        // آمار فروش و سود هر خدمت (اگر جدول SaleItem داری)
        $services = $query
            ->withCount(['saleItems as sells_count' => function($q) {
                $q->select(\DB::raw('count(*)'));
            }])
            ->withSum(['saleItems as profit_sum' => function($q) {
                $q->select(\DB::raw('sum(unit_price * quantity - discount)'));
            }])
            ->orderBy($sort === 'profit_sum' ? \DB::raw('profit_sum') : $sort, $dir)
            ->paginate(20);

        // کارت‌های آماری
        $totalServices = Service::count();
        $totalSells = \App\Models\SaleItem::whereHas('service')->count();
        $totalProfit = \App\Models\SaleItem::select(\DB::raw('sum(unit_price * quantity - discount) as profit'))->first()->profit ?? 0;

        // خدمات پرفروش (برای کاروسل)
        $topServices = Service::withCount(['saleItems as sells_count'])
            ->orderByDesc('sells_count')
            ->take(5)->get();

        // چارت فروش ماهانه
        $chartData = \App\Models\SaleItem::select(
                \DB::raw("DATE_FORMAT(created_at, '%Y-%m') as yyyymm"),
                \DB::raw("sum(unit_price * quantity - discount) as profit_sum"),
                \DB::raw('sum(quantity) as sells_count')
            )
            ->whereHas('service')
            ->groupBy('yyyymm')
            ->orderBy('yyyymm')
            ->get();

        return view('services.index', compact(
            'services', 'serviceCategories', 'units',
            'totalServices', 'totalSells', 'totalProfit', 'topServices', 'chartData', 'sort', 'dir'
        ));
    }

    public function ajaxList(Request $request)
    {
        $query = Service::with('category')->where('is_active', 1);

        if ($request->has('q') && $request->q) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%$q%")
                    ->orWhere('service_code', 'like', "%$q%");
            });
        }

        $limit = intval($request->get('limit', 10));
        $services = $query->limit($limit)->get();

        $results = $services->map(function ($service) {
            $product = \App\Models\Product::where('code', $service->service_code)->first();
            return [
                'id'         => $product ? $product->id : null,
                'code'       => $service->service_code,
                'name'       => $service->title,
                'category'   => $service->category ? $service->category->name : '-',
                'unit'       => $service->unit,
                'sell_price' => $service->price,
                'description'=> $service->short_description ?? $service->description,
                'stock'      => 1,
            ];
        });

        return response()->json($results);
    }

    /**
     * تولید کد خدمت جدید (برای ajax)
     */
    public function nextCode()
    {
        $last = Service::where('service_code', 'like', 'services-%')
            ->orderByRaw('CAST(SUBSTRING(service_code, 10) AS UNSIGNED) DESC')
            ->first();
        if ($last && preg_match('/^services-(\d+)$/', $last->service_code, $m)) {
            $next = intval($m[1]) + 1;
        } else {
            $next = 1001;
        }
        return response()->json(['code' => 'services-' . $next]);
    }

    /**
     * ثبت خدمت جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_code' => 'required|string|max:255|unique:services,service_code',
            'service_category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'execution_cost' => 'nullable|numeric',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
            'is_vat_included' => 'nullable|boolean',
            'is_discountable' => 'nullable|boolean',
            'service_info' => 'nullable|string|max:255',
            'info_link' => 'nullable|string',
            'full_description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_vat_included'] = $request->has('is_vat_included');
        $validated['is_discountable'] = $request->has('is_discountable');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $unit = Unit::find($validated['unit_id']);
        $validated['unit'] = $unit ? $unit->title : null;

        $service = Service::create($validated);

        // ذخیره سهامداران و درصد سهم
        $inputShares = $request->input('shareholders', []);
        $shareholders = Person::where('type', 'shareholder')->get();
        $totalShare = 0;
        $emptyIds = [];

        foreach ($shareholders as $person) {
            $percent = isset($inputShares[$person->id]) && $inputShares[$person->id] !== null && $inputShares[$person->id] !== '' ? floatval($inputShares[$person->id]) : null;
            if ($percent === null) {
                $emptyIds[] = $person->id;
            } else {
                $totalShare += $percent;
            }
        }
        // تقسیم باقی‌مانده سهم بین افراد بدون سهم
        $remain = 100 - $totalShare;
        $autoPercent = (count($emptyIds) > 0 && $remain > 0) ? round($remain / count($emptyIds), 2) : 0;

        // فرض: Service و Person رابطه many-to-many دارند (و جدول pivot مثلاً service_shareholder دارد)
        foreach ($shareholders as $person) {
            $percent = isset($inputShares[$person->id]) && $inputShares[$person->id] !== null && $inputShares[$person->id] !== '' ? floatval($inputShares[$person->id]) : null;
            if ($percent === null && count($emptyIds) > 0) {
                $percent = $autoPercent;
            }
            if ($percent > 0) {
                $service->shareholders()->attach($person->id, ['percent' => $percent]);
            }
        }

        // محصول معادل بساز
        if(method_exists($service, 'createOrUpdateProduct')) {
            $service->createOrUpdateProduct();
        }

        return redirect()->route('services.index')->with('success', 'خدمات با موفقیت ثبت شد.');
    }

    /**
     * نمایش فرم ویرایش خدمت
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $serviceCategories = Category::where('category_type', 'service')->get();
        $units = Unit::orderBy('title')->get();
        $shareholders = Person::where('type', 'shareholder')->get(); // اینجا هم باید مدل Person باشد

        return view('services.edit', compact('service', 'serviceCategories', 'units', 'shareholders'));
    }

    /**
     * ویرایش خدمت
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:255',
            'service_code' => 'required|string|max:255|unique:services,service_code,' . $service->id,
            'service_category_id' => 'nullable|exists:categories,id',
            'unit_id'        => 'required|exists:units,id',
            'price'       => 'nullable|numeric',
            'is_active'   => 'nullable|boolean',
            'service_info' => 'nullable|string|max:255',
            'info_link' => 'nullable|string',
            'full_description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'title', 'service_code', 'service_category_id', 'unit_id', 'price', 'is_active',
            'service_info', 'info_link', 'full_description', 'short_description', 'description'
        ]);
        if (!isset($data['is_active'])) $data['is_active'] = true;

        $unit = Unit::find($data['unit_id']);
        $data['unit'] = $unit ? $unit->title : null;

        if ($request->hasFile('image')) {
            if ($service->image) {
                \Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        $service->createOrUpdateProduct();

        return redirect()->route('services.index')->with('success', 'خدمت با موفقیت ویرایش شد.');
    }

    /**
     * حذف خدمت
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('services.index')->with('success', 'خدمت با موفقیت حذف شد.');
    }
    public function addUnit(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:units,title'
        ]);
        $unit = new Unit();
        $unit->title = $request->title;
        $unit->save();

        return response()->json([
            'id' => $unit->id,
            'title' => $unit->title,
        ]);
    }
}
