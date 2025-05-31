<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'service_code', 'service_category_id', 'unit_id', 'unit', 'price', 'tax', 'execution_cost',
        'short_description', 'description', 'image', 'is_active', 'is_vat_included', 'is_discountable',
        'service_info', 'info_link', 'full_description'
    ];

    public function shareholders()
    {
        // توجه به استفاده از persons
        return $this->belongsToMany(Person::class, 'service_shareholder', 'service_id', 'person_id')
            ->withPivot('percent');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'service_category_id');
    }

    public function createOrUpdateProduct()
    {
        $categoryId = $this->service_category_id;
        if (empty($categoryId)) {
            $defaultCategory = \App\Models\Category::where('category_type', 'service')->first();
            $categoryId = $defaultCategory ? $defaultCategory->id : 1;
        }

        $product = \App\Models\Product::updateOrCreate(
            ['code' => $this->service_code],
            [
                'name'        => $this->title,
                'code'        => $this->service_code,
                'category_id' => $categoryId,
                'image'       => $this->image,
                'short_desc'  => $this->short_description,
                'description' => $this->description,
                'unit'        => $this->unit,
                'sell_price'  => $this->price,
                'type'        => 'service',
                'is_active'   => $this->is_active,
            ]
        );

        $this->product_id = $product->id;
        $this->save();

        return $product;
    }
}
