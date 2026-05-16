<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductFilterTag extends Model
{
    protected $fillable = [
        'type',
        'name',
        'slug',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function categories()
    {
        return $this->hasMany(
            Category::class,
            'pet_type_tag_id'
        )->whereNull('parent_id');
    }

    protected static function booted()
    {
        static::saving(function ($filterTag) {

            // If user didn't provide slug → generate
            if (empty($filterTag->slug) && !empty($filterTag->name)) {
                $base = Str::slug($filterTag->name);
                $filterTag->slug = static::uniqueSlug($base, $filterTag->id);
                // If user provided slug → normalize + ensure unique
            } elseif (!empty($filterTag->slug)) {
                $base = Str::slug($filterTag->slug);
                $filterTag->slug = static::uniqueSlug($base, $filterTag->id);
            }
        });
    }


    protected static function uniqueSlug(string $base, $ignoreId = null): string
    {
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public static function grouped()
    {
        return static::active()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('type');
    }
}
