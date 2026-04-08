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

    // This tells Laravel NOT to try and save this to the home_section_items table
    public $featured_products;

    public function section()
    {
        return $this->belongsTo(HomeSection::class);
    }
}
