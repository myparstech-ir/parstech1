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
        $query = Category::query();
        if ($type) {
            $query->where('category_type', $type);
        }
        $categories = $query->orderBy('name')->get([
            'id', 'name', 'code', 'category_type', 'parent_id', 'description', 'image'
        ]);
        return response()->json($categories);
    }

    // متد جدید ایجکس محصولات
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
