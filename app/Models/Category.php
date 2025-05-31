<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'code', 'category_type', 'parent_id', 'description', 'image'
    ];

    /**
     * محصولات این دسته
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * دسته والد
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * زیرشاخه‌ها
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
