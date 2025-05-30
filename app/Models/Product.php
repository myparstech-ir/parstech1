<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    // مقدار پیش فرض هشدار موجودی
    public const STOCK_ALERT_DEFAULT = 1;

    protected $fillable = [
        'name',
        'code',
        'category_id',
        'brand_id',
        'buy_price',
        'sell_price',
        'discount',
        'stock',
        'stock_alert',
        'min_order_qty',
        'expire_date',
        'added_at',
        'is_active',
        'unit',
        'weight',
        'barcode',
        'store_barcode',
        'image',
        'video',
        'gallery',
        'short_desc',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // گالری تصاویر (در صورت ذخیره به صورت JSON)
    public function getGalleryAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    // ارتباط محصولات و سهامداران از طریق جدول واسط product_shareholder
    public function shareholders()
    {
        return $this->belongsToMany(Person::class, 'product_shareholder', 'product_id', 'person_id')
            ->withPivot('percent');
    }
}
