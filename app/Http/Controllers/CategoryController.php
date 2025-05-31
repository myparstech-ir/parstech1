<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // لیست دسته‌بندی‌ها (صفحه درختی)
    public function index()
    {
        return view('categories.index');
    }

    // داده‌های درختی برای jsTree
    public function treeData(Request $request)
    {
        $categories = Category::all();

        $tree = [];
        foreach ($categories as $cat) {
            $tree[] = [
                'id' => $cat->id,
                'parent' => $cat->parent_id ? $cat->parent_id : '#',
                'text' =>
                    e($cat->name) .
                    ($cat->code ? " <span class='cat-code'>(".e($cat->code).")</span>" : '') .
                    ($cat->category_type ? " <span class='cat-type'>".e($cat->category_type)."</span>" : ''),
                'icon' => $cat->category_type === 'product' ? 'fa fa-box' :
                          ($cat->category_type === 'service' ? 'fa fa-cogs' : 'fa fa-user'),
                'state' => ['opened' => $cat->parent_id ? false : true],
                'data' => [
                    'description' => $cat->description,
                    'image' => $cat->image,
                ]
            ];
        }

        return response()->json($tree);
    }

    // فرم ساخت دسته جدید
    public function create()
    {
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    // ذخیره دسته جدید
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

    // فرم ویرایش دسته
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $category->id)
            ->where('category_type', $category->category_type)
            ->get();
        return view('categories.edit', compact('category', 'categories'));
    }

    // ذخیره ویرایش دسته
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
            ->with('success', 'دسته‌بندی با موفقیت ویرایش شد.');
    }

    // حذف دسته
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'دسته‌بندی با موفقیت حذف شد.');
    }
}
