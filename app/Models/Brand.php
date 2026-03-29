<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'is_visible',
        'meta_title',
        'meta_description',
    ];

    protected static function booted()
    {
        static::saving(function ($brand) {

            // If user didn't provide slug → generate
            if (empty($brand->slug) && !empty($brand->name)) {
                $base = Str::slug($brand->name);
                $brand->slug = static::uniqueSlug($base, $brand->id);
            }

            // If user provided slug → normalize + ensure unique
            if (!empty($brand->slug)) {
                $base = Str::slug($brand->slug);
                $brand->slug = static::uniqueSlug($base, $brand->id);
            }
        });
    }

    protected static function uniqueSlug(string $base, $ignoreId = null): string
    {
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
