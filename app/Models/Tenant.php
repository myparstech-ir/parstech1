<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    protected $casts = [
        'data' => 'array',
    ];

    // اگر بعداً خواستی ارتباط با User بزنی
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
