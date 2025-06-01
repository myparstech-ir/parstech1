<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'service_id',
        'quantity',
        'unit_price',
        'discount',
        'tax',
        'total',
        'description',
        'unit',
    ];

    // آیتم فروش ممکن است محصول باشد
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // آیتم فروش ممکن است خدمت باشد
    public function service()
    {
        // اگر خدمت‌ها مدل جدا دارند، این خط را تغییر بده.
        return $this->belongsTo(Product::class, 'service_id');
        // یا اگر مدل شما Service است:
        // return $this->belongsTo(Service::class, 'service_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
