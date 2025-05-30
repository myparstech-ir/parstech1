<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    use HasFactory;

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

    // ارتباط با دسته‌بندی
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ارتباط با برند
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // گالری تصاویر (در صورت ذخیره به صورت JSON)
    public function getGalleryAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
    public function shareholders()
    {
        // اگر در جدول product_shareholder ستون person_id داری:
        return $this->belongsToMany(Person::class, 'product_shareholder', 'product_id', 'person_id')
            ->withPivot('percent');

        // اگر مدل Shareholder داری و فیلدش shareholder_id است:
        // return $this->belongsToMany(Shareholder::class, 'product_shareholder', 'product_id', 'shareholder_id')
        //     ->withPivot('percent');
    }

    // اگر نیاز به متدهای بیشتری بود اینجا اضافه کن
}
