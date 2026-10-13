<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'sender_id',
        'sender_type',
        'is_alert',
        'body',
        'read_by_customer_at',
        'read_by_admin_at',
    ];

    protected $casts = [
        'is_alert' => 'boolean',
        'read_by_customer_at' => 'datetime',
        'read_by_admin_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function files()
    {
        return $this->hasMany(OrderMessageFile::class);
    }
}
