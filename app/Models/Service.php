<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'service_code',
        'service_category_id',
        'unit_id',
        'unit',
        'price',
        'tax',
        'execution_cost',
        'short_description',
        'description',
        'image',
        'is_active',
        'is_vat_included',
        'is_discountable',
        'service_info',
        'info_link',
        'full_description',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class, 'service_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'service_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function shareholders()
    {
        return $this->belongsToMany(Person::class, 'service_shareholder', 'service_id', 'person_id')
            ->withPivot('percent');
    }
}
