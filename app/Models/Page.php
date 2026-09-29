<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'editor_type',
        'content',
        'css',
        'gjs_data',
        'seo_title',
        'seo_description',
        'is_active',
    ];

    protected $casts = [
        'gjs_data'  => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Check if the page uses GrapesJS visual builder
     */
    public function isVisualBuilder(): bool
    {
        return $this->editor_type === 'grapesjs';
    }

    /**
     * Check if the page uses standard rich-text editor
     */
    public function isSimple(): bool
    {
        return $this->editor_type === 'simple';
    }

    // Auto-generate slug from title if not provided
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($page) {
            if (!$page->slug) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
}
