<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceEmailTemplate extends Model
{
    protected $fillable = [
        'checkout_service_id',
        'name',
        'subject',
        'body',
        'from_email',
        'from_name',
        'status',
    ];

    public function service()
    {
        return $this->belongsTo(CheckoutService::class, 'checkout_service_id');
    }
}
