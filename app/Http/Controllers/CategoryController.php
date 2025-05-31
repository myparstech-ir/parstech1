<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * نمایش صفحه لیست دسته‌بندی‌ها (درختی)
     */
    public function index()
    {
        // توجه: برای jsTree نیازی به ارسال دسته‌ها نیست
        return view('categories.index');
    }

    /**
     * داده‌های درختی دسته‌بندی‌ها برای jsTree (خروجی JSON پیشرفته)
     */
    public function treeData()
    {
        // همه دسته‌ها را با محصولات و محصولات هر دسته را با فروش‌ها بیاوریم
        $categories = Category::with(['products.sales', 'products'])->get();

        // دسته‌های ریشه‌ای (parent_id = null)
        $rootCategories = $categories->whereNull('parent_id');

        // ساختار درختی بازگشتی
        $buildTree = function ($cats) use ($categories, &$buildTree) {
            return $cats->map(function ($cat) use ($categories, $buildTree) {
                // جمع محصولات خود دسته و زیرمجموعه‌ها
                $allProductIds = $this->getAllProductIds($cat, $categories);
                $productCount = count($allProductIds);

                // مجموع فروش (فرض: فیلد total_price در Sale)
                $totalSales = Product::whereIn('id', $allProductIds)
                    ->with('sales')
                    ->get()
                    ->flatMap->sales
                    ->sum('total_price'); // اگر فیلد فروش متفاوت است اصلاح کن

                // مجموع موجودی
                $totalStock = Product::whereIn('id', $allProductIds)->sum('stock'); // اگر فیلد stock متفاوت است اصلاح کن

                // نمایش آمار کنار نام دسته
                $text = sprintf(
                    '%s <span class="badge bg-primary ms-2">%d محصول</span> <span class="badge bg-success ms-2">%s فروش</span> <span class="badge bg-info">%d موجودی</span>',
                    e($cat->name),
                    $productCount,
                    number_format($totalSales),
                    $totalStock
                );

                // آرایه خروجی jsTree
                return [
                    'id' => $cat->id,
                    'parent' => $cat->parent_id ?: '#',
                    'text' => $text,
                    'icon' => $cat->category_type === 'product' ? 'fa fa-box text-primary' :
                              ($cat->category_type === 'service' ? 'fa fa-cogs text-success' : 'fa fa-user text-secondary'),
                    'state' => ['opened' => $cat->parent_id ? false : true],
                    'data' => [
                        'description' => $cat->description,
                        'image' => $cat->image,
                    ],
                    'children' => $buildTree($categories->where('parent_id', $cat->id)),
                ];
            })->values();
        };

        return response()->json($buildTree($rootCategories));
    }

    /**
     * گرفتن همه محصولات یک دسته و زیرشاخه‌هایش (بازگشتی)
     */
    private function getAllProductIds($category, $allCategories)
    {
        $ids = $category->products ? $category->products->pluck('id')->toArray() : [];
        $children = $allCategories->where('parent_id', $category->id);
        foreach ($children as $child) {
            $ids = array_merge($ids, $this->getAllProductIds($child, $allCategories));
        }
        return $ids;
    }

    /**
     * نمایش فرم ساخت دسته‌بندی جدید
     */
    public function create()
    {
        $personCategories = Category::where('category_type', 'person')->get();
        $productCategories = Category::where('category_type', 'product')->get();
        $serviceCategories = Category::where('category_type', 'service')->get();

        $nextPersonCode = 'per' . (Category::where('category_type', 'person')->count() + 1001);
        $nextProductCode = 'pro' . (Category::where('category_type', 'product')->count() + 1001);
        $nextServiceCode = 'ser' . (Category::where('category_type', 'service')->count() + 1001);

        return view('categories.create', compact(
            'personCategories', 'productCategories', 'serviceCategories',
            'nextPersonCode', 'nextProductCode', 'nextServiceCode'
        ));
    }

    /**
     * ثبت دسته‌بندی جدید
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'code' => 'nullable|string|max:100',
            'category_type' => 'required|in:person,product,service',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['name', 'code', 'category_type', 'parent_id', 'description']);
        if (empty($data['code'])) {
            $prefix = [
                'person' => 'per',
                'product' => 'pro',
                'service' => 'ser',
            ];
            $count = Category::where('category_type', $data['category_type'])->count() + 1001;
            $data['code'] = $prefix[$data['category_type']] . $count;
        }
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'دسته‌بندی با موفقیت ثبت شد.');
    }

    /**
     * نمایش فرم ویرایش دسته‌بندی
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $category->id)
            ->where('category_type', $category->category_type)
            ->get();
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * ثبت ویرایش دسته‌بندی
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191',
            'code' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['name', 'code', 'parent_id', 'description']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'دسته‌بندی با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف دسته‌بندی و زیرشاخه‌های آن
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        foreach ($category->children as $child) {
            $child->delete();
        }
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'دسته‌بندی با موفقیت حذف شد.');
    }
}
