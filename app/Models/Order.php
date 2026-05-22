<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'shipping_amount',
        'status',
        'payment_status',
        'payment_method',
        'coupon_id',
        'discount',
        'tracking_number',
        'tracking_url',
        'shipping_name',
        'shipping_phone',
        'shipping_address_line1',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',

        'shiprocket_order_id',
        'shipment_id',
        'awb_code',
        'courier_name',
        'label_url',
        'manifest_url',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->order_number = 'VKY-' . strtoupper(uniqid());
        });
    }
}
