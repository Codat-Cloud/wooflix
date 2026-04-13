<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ReviewImage extends Model
{
    protected $fillable = ['review_id', 'image_path'];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    // Auto-delete the file from storage when the record is deleted
    protected static function booted()
    {
        static::deleted(function ($reviewImage) {
            Storage::disk('public')->delete($reviewImage->image_path);
        });
    }
}
