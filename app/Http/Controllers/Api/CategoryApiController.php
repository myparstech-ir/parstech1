<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $id = $request->query('id');
        $query = Category::query();

        if ($id) {
            $category = $query->find($id);
            if ($category) {
                return response()->json([[
                    'id' => $category->id,
                    'name' => $category->name,
                ]]);
            }
            return response()->json([]);
        }

        if ($type) {
            $query->where('category_type', $type);
        }
        $categories = $query->orderBy('name')->get([
            'id', 'name', 'code', 'category_type', 'parent_id', 'description', 'image'
        ]);
        return response()->json($categories);
    }

    // ایجکس محصولات با جستجو
    public function productList(Request $request)
    {
        $q = $request->input('q', '');
        $limit = $request->input('limit', 5);

        $categories = Category::where('category_type', 'product')
            ->when($q, function($query) use ($q) {
                $query->where('name', 'like', '%'.$q.'%');
            })
            ->orderByDesc('id')
            ->limit($limit)
            ->get(['id', 'name']);

        return response()->json([
            'items' => $categories->map(function($cat){
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ];
            }),
        ]);
    }
}
