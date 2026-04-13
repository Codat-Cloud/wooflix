<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'title', 'code', 'type', 'value', 'min_spend', 'max_discount', 
        'usage_limit', 'user_limit', 'starts_at', 'expires_at', 
        'is_best', 'is_visible', 'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_best' => 'boolean',
        'is_visible' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    // Future-proof: Easily check if a coupon is valid in one call
    public function scopeAvailable(Builder $query): void
    {
        $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    }

    public function getDisplayTitleAttribute(): string
    {
        if ($this->title) {
            return $this->title;
        }

        return match ($this->type) {
            'percentage' => "{$this->value}% OFF",
            'fixed' => "Flat ₹" . number_format($this->value, 0) . " OFF",
            'free_shipping' => "Free Shipping",
            default => "Special Offer",
        };
    }
}
