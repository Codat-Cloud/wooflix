<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = ['name', 'slug', 'image', 'image_alt', 'description', 'is_active'];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class);
    }
}
