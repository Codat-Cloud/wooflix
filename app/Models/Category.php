<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'image',
        'meta_title',
        'meta_description'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    protected static function booted()
    {
        static::saving(function ($category) {

            if (empty($category->slug) && !empty($category->name)) {

                $slug = Str::slug($category->name);
                $count = static::where('slug', 'LIKE', "{$slug}%")->count();

                $category->slug = $count ? "{$slug}-{$count}" : $slug;
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
