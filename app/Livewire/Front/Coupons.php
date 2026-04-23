<?php

namespace App\Livewire\Front;

use App\Models\Coupon;
use Livewire\Component;

class Coupons extends Component
{
    public $coupons = [];

    public function mount()
    {
        $this->coupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.front.coupons');
    }
}
