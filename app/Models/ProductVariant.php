<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',

        // Variant identity
        'name',
        'slug',
        'sku',
        'barcode',

        // Pricing
        'price',
        'sale_price',

        // Inventory
        'stock',

        // Shipping
        'weight',
        'length',
        'width',
        'height',

        // Status
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'sale_price' => 'float',

        'stock' => 'integer',

        'weight' => 'float',
        'length' => 'float',
        'width' => 'float',
        'height' => 'float',

        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function ($variant) {

            /*
            |--------------------------------------------------------------------------
            | AUTO SLUG
            |--------------------------------------------------------------------------
            */
            if (empty($variant->slug)) {

                $variant->slug = Str::slug($variant->name);
            }

            /*
            |--------------------------------------------------------------------------
            | DEFAULT VARIANT LOGIC
            |--------------------------------------------------------------------------
            */
            // First variant automatically becomes default
            $hasDefault = static::where('product_id', $variant->product_id)
                ->where('is_default', true)
                ->when(
                    $variant->id,
                    fn($q) =>
                    $q->where('id', '!=', $variant->id)
                )
                ->exists();

            if (!$hasDefault) {
                $variant->is_default = true;
            }

        });

        static::saved(function ($variant) {
            if ($variant->is_default) {
                static::where('product_id', $variant->product_id)
                    ->where('id', '!=', $variant->id)
                    ->update(['is_default' => false]);
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function optionValues()
    {
        return $this->belongsToMany(
            ProductOptionValue::class,
            'variant_option_values',
            'product_variant_id',
            'product_option_value_id'
        );
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_variant_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?: $this->price;
    }

    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }
}
