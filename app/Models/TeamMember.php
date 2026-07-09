<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'department',
        'designation',
        'email',
        'bio',
        'photo',
        'department_sort_order',
        'sort_order',
        'is_active',
    ];
}
