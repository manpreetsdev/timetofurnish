<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderEmailLog extends Model
{
    protected $fillable = [
        'order_id',
        'checkout_service_id',
        'service_email_template_id',
        'type',
        'to_email',
        'cc',
        'from_email',
        'from_name',
        'subject',
        'body',
        'status',
        'error',
        'sent_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function service()
    {
        return $this->belongsTo(CheckoutService::class, 'checkout_service_id');
    }

    public function template()
    {
        return $this->belongsTo(ServiceEmailTemplate::class, 'service_email_template_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
