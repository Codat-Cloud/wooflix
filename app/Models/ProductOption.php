<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'position',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }

    protected static function booted()
    {
        static::saved(function ($option) {

            if (request()->has('values_input')) {

                $values = explode(',', request('values_input'));

                foreach ($values as $value) {
                    $option->values()->create([
                        'value' => trim($value),
                    ]);
                }
            }
        });
    }
}
