<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantOptionValue extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_variant_id',
        'product_option_value_id',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function value()
    {
        return $this->belongsTo(ProductOptionValue::class, 'product_option_value_id');
    }
}
