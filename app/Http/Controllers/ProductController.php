<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Service;
use App\Models\Person;

class ProductController extends Controller
{
    // تابع کمکی برای تبدیل اعداد فارسی به انگلیسی
    private function faToEnNumber($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $input[$key] = $this->faToEnNumber($val);
            }
            return $input;
        }
        $faNums = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹','٫'];
        $enNums = ['0','1','2','3','4','5','6','7','8','9','.'];
        return str_replace($faNums, $enNums, $input);
    }

    private function normalizeNumbers(&$data, $fields)
    {
        foreach ($fields as $field) {
            if (isset($data[$field]) && !is_null($data[$field])) {
                $data[$field] = $this->faToEnNumber($data[$field]);
            }
        }
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('inventory')) {
            if ($request->inventory == 'low') {
                $query->where('stock', '<=', Product::STOCK_ALERT_DEFAULT);
            } elseif ($request->inventory == 'zero') {
                $query->where('stock', '<=', 0);
            } elseif ($request->inventory == 'ok') {
                $query->where('stock', '>', Product::STOCK_ALERT_DEFAULT);
            }
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $products = $query->paginate(20);

        $lowStockProducts = Product::where('stock', '<=', Product::STOCK_ALERT_DEFAULT)
            ->with(['category', 'brand'])->get();

        $categories = Category::all();
        $brands = Brand::all();
        $categories_count = $categories->count();
        $brands_count = $brands->count();

        return view('products.index', compact(
            'products', 'categories', 'brands', 'categories_count', 'brands_count', 'sort', 'direction', 'lowStockProducts'
        ));
    }

    public function create()
    {
        $lastAutoCodeProduct = Product::where('code', 'like', 'product-100%')
            ->orderByDesc(\DB::raw('CAST(SUBSTRING(code, 9) AS UNSIGNED)'))
            ->first();

        $default_code = 'product-1001';
        if ($lastAutoCodeProduct) {
            $lastNumber = intval(substr($lastAutoCodeProduct->code, 8));
            $default_code = 'product-' . ($lastNumber + 1);
        }

        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        $shareholders = Person::where('type', 'shareholder')->orderBy('full_name')->get();

        return view('products.create', compact(
            'categories', 'brands', 'units', 'shareholders', 'default_code'
        ));
    }

    public function store(Request $request)
    {
        // تبدیل اعداد فارسی به انگلیسی قبل از ولیدیشن
        $fieldsToConvert = [
            'stock', 'stock_alert', 'min_order_qty', 'weight', 'buy_price', 'sell_price', 'discount'
        ];
        $input = $request->all();
        $this->normalizeNumbers($input, $fieldsToConvert);
        // همچنین درصد سهامداران را نیز اصلاح کن اگر ارسالی باشد:
        if (isset($input['shareholder_percents'])) {
            $input['shareholder_percents'] = $this->faToEnNumber($input['shareholder_percents']);
        }
        $request->replace($input);

        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:255|unique:products,code',
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'unit'        => 'nullable|string|max:255',
            'stock'       => 'nullable|numeric',
            'stock_alert' => 'nullable|numeric',
            'min_order_qty' => 'nullable|numeric',
            'weight'      => 'nullable|numeric',
            'buy_price'   => 'nullable|numeric',
            'sell_price'  => 'nullable|numeric',
            'discount'    => 'nullable|numeric',
            'expire_date' => 'nullable|string',
            'added_at'    => 'nullable|string',
            'is_active'   => 'nullable|boolean',
            'barcode'     => 'nullable|string|max:255',
            'store_barcode' => 'nullable|string|max:255',
            'short_desc'  => 'nullable|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'video'       => 'nullable|file|mimes:mp4,avi,mov|max:10240',
        ]);

        $data = $request->only([
            'name', 'code', 'category_id', 'brand_id', 'unit', 'stock', 'stock_alert', 'min_order_qty', 'weight', 'buy_price',
            'sell_price', 'discount', 'expire_date', 'added_at', 'is_active', 'barcode', 'store_barcode', 'short_desc', 'description'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('products/videos', 'public');
        }

        $gallery = $request->file('gallery', []);
        if ($gallery && is_array($gallery)) {
            $gallery_paths = [];
            foreach ($gallery as $img) {
                if ($img) {
                    $gallery_paths[] = $img->store('products/gallery', 'public');
                }
            }
            if (count($gallery_paths)) {
                $data['gallery'] = json_encode($gallery_paths, JSON_UNESCAPED_UNICODE);
            }
        }

        $data['stock'] = $data['stock'] ?? 1;
        $data['stock_alert'] = $data['stock_alert'] ?? Product::STOCK_ALERT_DEFAULT;
        $data['min_order_qty'] = $data['min_order_qty'] ?? 1;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $product = Product::create($data);

        if ($request->has('shareholder_ids')) {
            $syncData = [];
            $percents = $request->input('shareholder_percents', []);
            $ids = $request->input('shareholder_ids', []);
            $totalPercent = 0;
            foreach ($ids as $id) {
                $percent = isset($percents[$id]) ? floatval($this->faToEnNumber($percents[$id])) : 0;
                $syncData[$id] = ['percent' => $percent];
                $totalPercent += $percent;
            }
            if (count($ids) === 1) {
                $syncData[$ids[0]] = ['percent' => 100];
            } elseif (count($ids) > 1 && $totalPercent < 100) {
                $remained = 100 - $totalPercent;
                $extra = $remained / count($ids);
                foreach ($ids as $id) {
                    $syncData[$id]['percent'] += $extra;
                }
            }
            $product->shareholders()->sync($syncData);
        } else {
            $allShareholders = Person::where('type', 'shareholder')->get();
            $count = $allShareholders->count();
            if ($count > 0) {
                $percent = 100 / $count;
                $syncData = [];
                foreach ($allShareholders as $sh) {
                    $syncData[$sh->id] = ['percent' => $percent];
                }
                $product->shareholders()->sync($syncData);
            }
        }

        return redirect()->route('products.index')->with('success', 'محصول با موفقیت ثبت شد.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('category_type', 'product')->get();
        $brands = Brand::all();
        $units = Unit::all();
        $shareholders = Person::where('type', 'shareholder')->orderBy('full_name')->get();
        return view('products.edit', compact('product', 'categories', 'brands', 'units', 'shareholders'));
    }

    public function update(Request $request, $id)
    {
        // تبدیل اعداد فارسی به انگلیسی قبل از ولیدیشن
        $fieldsToConvert = [
            'stock', 'stock_alert', 'min_order_qty', 'weight', 'buy_price', 'sell_price', 'discount'
        ];
        $input = $request->all();
        $this->normalizeNumbers($input, $fieldsToConvert);
        if (isset($input['shareholder_percents'])) {
            $input['shareholder_percents'] = $this->faToEnNumber($input['shareholder_percents']);
        }
        $request->replace($input);

        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:255|unique:products,code,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'unit'        => 'nullable|string|max:255',
            'stock'       => 'nullable|numeric',
            'stock_alert' => 'nullable|numeric',
            'min_order_qty' => 'nullable|numeric',
            'weight'      => 'nullable|numeric',
            'buy_price'   => 'nullable|numeric',
            'sell_price'  => 'nullable|numeric',
            'discount'    => 'nullable|numeric',
            'expire_date' => 'nullable|string',
            'added_at'    => 'nullable|string',
            'is_active'   => 'nullable|boolean',
            'barcode'     => 'nullable|string|max:255',
            'store_barcode' => 'nullable|string|max:255',
            'short_desc'  => 'nullable|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'video'       => 'nullable|file|mimes:mp4,avi,mov|max:10240',
        ]);

        $data = $request->only([
            'name', 'code', 'category_id', 'brand_id', 'unit', 'stock', 'stock_alert', 'min_order_qty', 'weight', 'buy_price',
            'sell_price', 'discount', 'expire_date', 'added_at', 'is_active', 'barcode', 'store_barcode', 'short_desc', 'description'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('products/videos', 'public');
        }

        $gallery = $request->file('gallery', []);
        if ($gallery && is_array($gallery)) {
            $gallery_paths = [];
            foreach ($gallery as $img) {
                if ($img) {
                    $gallery_paths[] = $img->store('products/gallery', 'public');
                }
            }
            if (count($gallery_paths)) {
                $data['gallery'] = json_encode($gallery_paths, JSON_UNESCAPED_UNICODE);
            }
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $product->update($data);

        if ($request->has('shareholder_ids')) {
            $syncData = [];
            $percents = $request->input('shareholder_percents', []);
            $ids = $request->input('shareholder_ids', []);
            $totalPercent = 0;
            foreach ($ids as $id) {
                $percent = isset($percents[$id]) ? floatval($this->faToEnNumber($percents[$id])) : 0;
                $syncData[$id] = ['percent' => $percent];
                $totalPercent += $percent;
            }
            if (count($ids) === 1) {
                $syncData[$ids[0]] = ['percent' => 100];
            } elseif (count($ids) > 1 && $totalPercent < 100) {
                $remained = 100 - $totalPercent;
                $extra = $remained / count($ids);
                foreach ($ids as $id) {
                    $syncData[$id]['percent'] += $extra;
                }
            }
            $product->shareholders()->sync($syncData);
        } else {
            $allShareholders = Person::where('type', 'shareholder')->get();
            $count = $allShareholders->count();
            if ($count > 0) {
                $percent = 100 / $count;
                $syncData = [];
                foreach ($allShareholders as $sh) {
                    $syncData[$sh->id] = ['percent' => $percent];
                }
                $product->shareholders()->sync($syncData);
            }
        }

        return redirect()->route('products.index')->with('success', 'محصول با موفقیت ویرایش شد.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'محصول با موفقیت حذف شد.');
    }
    public function show(Product $product)
    {
        // اگر جدول category یا brand وابسته است، eager load کن:
        $product->load(['category', 'brand']);

        return view('products.show', compact('product'));
    }
}
