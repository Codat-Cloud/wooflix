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

    // This tells Laravel NOT to try and save this to the home_section_items table
    public $featured_products;

    public function section()
    {
        return $this->belongsTo(HomeSection::class);
    }


protected static function booted()
{
    static::saving(function ($item) {
        if (!$item->item_id || !$item->home_section_id) {

            Log::warning('Missing item_id or home_section_id');

            return;
        }

        $section = HomeSection::find($item->home_section_id);

        if (!$section) {

            Log::warning('Section not found');

            return;
        }

        // BRAND
        if ($section->type === 'brand') {

            $brand = Brand::find($item->item_id);

            Log::info('Brand Loaded', [
                'brand' => $brand?->name,
                'slug' => $brand?->slug,
            ]);

            if ($brand) {

                $url = route('front.shop', [
                    'brand' => $brand->slug
                ]);

                Log::info('Generated Brand URL', [
                    'url' => $url
                ]);

                $item->link = $url;
            }
        }

        // CATEGORY
        elseif ($section->type === 'category') {

            $category = Category::find($item->item_id);

            if ($category) {

                $url = route('front.shop', [
                    'cat' => $category->slug
                ]);

                Log::info('Generated Category URL', [
                    'url' => $url
                ]);

                $item->link = $url;
            }
        }

    });
}
}
