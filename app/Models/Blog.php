<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'image_alt',
        'seo_title',
        'seo_description',
        'is_published',
        'related_posts',
        'published_at'
    ];

    protected $casts = [
        'related_posts' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($blog) {
            // Force the slug to be URL-friendly even if manually entered
            $blog->slug = Str::slug($blog->slug);
        });
    }

    public function getWebpThumbAttribute()
    {
        if (!$this->featured_image) return null;

        return preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $this->featured_image);
    }

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_post');
    }

    // Auto-fetch the Previous Post
    public function getPreviousPostAttribute()
    {
        return self::where('id', '<', $this->id)->where('is_published', true)->orderBy('id', 'desc')->first();
    }

    // Auto-fetch the Next Post
    public function getNextPostAttribute()
    {
        return self::where('id', '>', $this->id)->where('is_published', true)->orderBy('id', 'asc')->first();
    }
}
