<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{



    protected $fillable = [
        'name', 'code', 'category_type', 'parent_id', 'description', 'image'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // روابط والد-فرزند برای استفاده‌های آینده
    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }
    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }


    // رابطه بازگشتی برای ساختار درختی
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
