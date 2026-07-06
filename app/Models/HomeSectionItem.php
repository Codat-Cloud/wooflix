<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    public $featured_products;

    public function section()
    {
        return $this->belongsTo(HomeSection::class);
    }

    protected static function booted()
    {
        static::saving(function ($item) {
            if (!$item->item_id || !$item->home_section_id) {
                return;
            }

            $section = HomeSection::find($item->home_section_id);
            if (!$section) {
                return;
            }

            // BRAND
            if ($section->type === 'brand') {
                $brand = Brand::find($item->item_id);
                if ($brand) {
                    $item->link = route('front.shop', ['brand' => $brand->slug]);
                }
            }

            // CATEGORY OR TABBED DEALS CATEGORIES
            // 🟢 FIXED: Generates frontend link arrays for tabbed products as well
            elseif ($section->type === 'category' || $section->type === 'tabbed_category_products') {
                $category = Category::find($item->item_id);
                if ($category) {
                    $item->link = route('front.shop', ['cat' => $category->slug]);
                }
            }
        });
    }
}