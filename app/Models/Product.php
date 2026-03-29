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
        'main_image',
        'short_description',
        'description',
        'is_active',
        'is_featured',
        'base_price',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
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
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
