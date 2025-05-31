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
        // تمام دسته‌های والد همراه با زیرشاخه و محصولات به صورت بازگشتی
        $categories = Category::with(['children', 'products'])->whereNull('parent_id')->get();
        return view('categories.index', compact('categories'));

    }

    /**
     * داده‌های درختی دسته‌بندی‌ها برای jsTree (خروجی JSON پیشرفته)
     */
    public function treeData()
    {
        $categories = Category::whereNull('parent_id')->with('childrenRecursive')->get();

        $makeTree = function ($categories) use (&$makeTree) {
            return $categories->map(function ($cat) use (&$makeTree) {
                return [
                    'id' => $cat->id,
                    'text' => e($cat->name),
                    'data' => [
                        'description' => $cat->description,
                        'image' => $cat->image
                    ],
                    'children' => $makeTree($cat->childrenRecursive),
                    // اگر می‌خواهی آیکون داشته باشی:
                    'icon' => $cat->category_type === 'product' ? 'fa fa-box text-primary' :
                              ($cat->category_type === 'service' ? 'fa fa-cogs text-success' : 'fa fa-user text-secondary'),
                ];
            });
        };

        return response()->json($makeTree($categories));
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
