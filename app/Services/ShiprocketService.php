<?php

// app/Services/ShiprocketService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ShiprocketService
{
    protected function token(): ?string
    {
        return Cache::remember('shiprocket_token', 55 * 60, function () {
            $res = Http::post(
                'https://apiv2.shiprocket.in/v1/external/auth/login',
                [
                    'email' => config('services.shiprocket.email'),
                    'password' => config('services.shiprocket.password'),
                ]
            )->json();

            return $res['token'] ?? null;
        });
    }

    public function checkServiceability($pincode)
    {
        $response = Http::withToken($this->token())
            ->get('https://apiv2.shiprocket.in/v1/external/courier/serviceability/', [
                'pickup_postcode' => config('services.shiprocket.pickup_pin', '110001'),
                'delivery_postcode' => $pincode,
                'weight' => 0.5,
                'cod' => 1,
            ])->json();

        $couriers = $response['data']['available_courier_companies'] ?? [];

        if (empty($couriers)) {
            return [
                'available' => false,
            ];
        }

        // Extract min days
        $minDays = collect($couriers)
            ->pluck('etd')
            ->filter()
            ->map(function ($etd) {
                preg_match('/\d+/', $etd, $matches);
                return isset($matches[0]) ? (int)$matches[0] : 99;
            })
            ->min();

        return [
            'available' => true,
            'min_days' => $minDays,
        ];
    }
}
