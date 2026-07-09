<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageReview extends Model
{
    protected $fillable = [
        'type',
        'image',
        'name',
        'rating',
        'review_text',
        'review_date',
        'status',
        'category_tag',
    ];

    protected $casts = [
        'review_date' => 'date',
    ];
}
