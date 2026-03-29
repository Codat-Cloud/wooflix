<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'desktop_image',
        'mobile_image',
        'link',
        'sort_order',
        'is_active',
    ];
}
