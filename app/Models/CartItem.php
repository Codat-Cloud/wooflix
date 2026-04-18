<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
    ];

    // ================= RELATIONS =================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // ================= HELPERS =================

    public function getDisplayNameAttribute()
    {
        return $this->variant?->name ?? $this->product?->name;
    }

    // DO NOT override price column
    public function getDisplayPriceAttribute()
    {
        return $this->variant?->price ?? $this->product?->base_price;
    }

    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

}
