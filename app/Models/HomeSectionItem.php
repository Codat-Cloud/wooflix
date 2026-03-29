<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSectionItem extends Model
{
    protected $fillable = [
        'home_section_id',
        'item_id',
        'title',
        'image',
        'link',
        'sort_order',
    ];

    public function section()
    {
        return $this->belongsTo(HomeSection::class);
    }
}
