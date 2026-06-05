<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCartView extends Model
{
    // Point explicitly to our Postgres View
    protected $table = 'abandoned_carts_view';

    // Disable writes since this is a read-only presentation aggregation mapping
    public $incrementing = false;
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}