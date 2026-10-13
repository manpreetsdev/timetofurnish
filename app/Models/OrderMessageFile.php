<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMessageFile extends Model
{
    protected $fillable = [
        'order_message_id',
        'order_id',
        'path',
        'original_name',
        'mime',
        'kind',
        'size',
    ];

    public function message()
    {
        return $this->belongsTo(OrderMessage::class, 'order_message_id');
    }

    public function getSizeLabelAttribute(): string
    {
        $size = (int) $this->size;

        return $size >= 1048576 ? round($size / 1048576, 1) . ' MB' : max(1, round($size / 1024)) . ' KB';
    }
}
