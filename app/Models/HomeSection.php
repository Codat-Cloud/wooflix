<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'type',
        'layout',
        'sort_order',
        'is_active',
    ];

    public function items()
    {
        return $this->hasMany(HomeSectionItem::class)
            ->orderBy('sort_order');
    }
}
