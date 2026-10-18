<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AbandonedCheckout extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'name',
        'email',
        'phone',
        'address_line1',
        'city',
        'state',
        'postal_code',
        'total_qty',
        'total_value',
        'cart_snapshot',
        'recovery_token',
        'last_active_at',
    ];

    protected $casts = [
        'cart_snapshot'  => 'array',
        'total_value'    => 'decimal:2',
        'last_active_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->recovery_token)) {
                $model->recovery_token = Str::random(40);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
