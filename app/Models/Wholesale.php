<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wholesale extends Model
{
    protected $fillable = [
        'full_name',
        'business_name',
        'email',
        'phone',
        'business_type',
        'gst_number',
        'address',
        'city',
        'state',
        'postal_code',
        'products_interested',
        'monthly_quantity',
        'sells_pet_products',
        'brands',
        'sales_channels',
        'message',
        'consent',
        'status',
        'source',
    ];

    protected $casts = [
        'products_interested' => 'array',
        'sales_channels' => 'array',
        'sells_pet_products' => 'boolean',
        'consent' => 'boolean',
    ];
}
