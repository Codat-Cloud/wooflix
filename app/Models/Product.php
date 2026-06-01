<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'asin',
        'hsn',
        'filters',
        'main_image',
        'size_chart_image',
        'short_description',
        'description',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
        'custom_tracking_script',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'filters' => 'array',
    ];

    protected static function booted()
    {
        static::saving(function ($product) {

            // ❌ DO NOT FORCE is_active here

            if (empty($product->slug) && $product->name) {

                $base = \Illuminate\Support\Str::slug($product->name);

                $count = static::where('slug', 'LIKE', "{$base}%")
                    ->when($product->id, fn($q) => $q->where('id', '!=', $product->id))
                    ->count();

                $product->slug = $count ? "{$base}-{$count}" : $base;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)
            ->where('is_active', true);
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)
            ->where('is_default', true);
    }

    public function getDefaultVariantNameAttribute()
    {
        return $this->defaultVariant?->name
            ?? $this->variants()->value('name')
            ?? '-';
    }

    // public function images()
    // {
    //     return $this->hasMany(ProductImage::class);
    // }

    /**
     * Main Product Media Slider (Standard additional images)
     */
    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)->where('type', 'gallery');
    }

    /**
     * Optional Infographics / Explainer Graphics (Shown below description text)
     */
    public function infographicImages()
    {
        return $this->hasMany(ProductImage::class)->where('type', 'infographic');
    }

    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // Logic for the Summary Bars (5 stars, 4 stars, etc.)
    public function getRatingStatsAttribute()
    {
        $total = $this->reviews()->count();

        // Returns count for each star level [5 => 20, 4 => 3, etc.]
        $counts = $this->reviews()
            ->selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $stats = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $counts[$i] ?? 0;
            $percentage = $total > 0 ? ($count / $total) * 100 : 0;
            $stats[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return [
            'total' => $total,
            'average' => round($this->reviews()->avg('rating'), 1) ?: 0,
            'details' => $stats
        ];
    }

    public function questions()
    {
        return $this->hasMany(ProductQuestion::class)
            ->where('is_visible', true)
            ->latest();
    }

    public function getDisplayPriceAttribute()
    {
        $variant = $this->defaultVariant;

        if (!$variant) {
            return 0;
        }

        return $variant->sale_price > 0
            ? $variant->sale_price
            : $variant->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        $variant = $this->defaultVariant;

        if (
            !$variant ||
            $variant->price <= 0 ||
            $variant->sale_price <= 0 ||
            $variant->sale_price >= $variant->price
        ) {
            return 0;
        }

        return (int) round(
            (
                ($variant->price - $variant->sale_price)
                / $variant->price
            ) * 100
        );
    }

    public function frequentlyBought()
    {
        return $this->belongsToMany(
            Product::class,
            'product_frequently_bought',
            'product_id',
            'related_product_id'
        )->withTimestamps()->with(['variants', 'defaultVariant']); // Pre-fetch variant price blocks instantly
    }
}
