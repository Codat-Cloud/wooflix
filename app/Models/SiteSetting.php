<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    /**
     * Get a setting value by key with caching for speed.
     */
    public static function get($key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            return self::where('key', $key)->value('value') ?? $default;
        });
    }

    protected static function booted()
    {
        static::saved(function ($setting) {
            // 🟢 Invalidate both the individual key and the global bulk cache
            Cache::forget("setting.{$setting->key}");
            Cache::forget('site_settings_all');
        });

        static::deleted(function ($setting) {
            Cache::forget("setting.{$setting->key}");
            Cache::forget('site_settings_all');
        });
    }
}
