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

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
