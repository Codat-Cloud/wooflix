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
        'pet_type_tag_id', 
        'description',
        'image',
        'desktop_banner', 
        'mobile_banner', 
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

    public function petType()
    {
        return $this->belongsTo(ProductFilterTag::class, 'pet_type_tag_id');
    }

    protected static function booted()
    {
        static::saving(function ($category) {

            if (empty($category->slug) && !empty($category->name)) {

                $baseSlug = Str::slug($category->name);

                // Append pet type slug if available
                if ($category->petType) {

                    $petTypeSlug = Str::slug($category->petType->slug);

                    $baseSlug = "{$petTypeSlug}-{$baseSlug}";
                }

                $slug = $baseSlug;

                $counter = 1;

                while (
                    static::where('slug', $slug)
                    ->when(
                        $category->exists,
                        fn($q) =>
                        $q->where('id', '!=', $category->id)
                    )
                    ->exists()
                ) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }

                $category->slug = $slug;
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
