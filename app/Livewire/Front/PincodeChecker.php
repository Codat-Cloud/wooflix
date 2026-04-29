<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Services\ShiprocketService;
use Carbon\Carbon;

class PincodeChecker extends Component
{
    public $pincode = '';

    public $deliveryAvailable = null; // true | false | null
    public $deliveryText = null;
    public $deliveryDate = null;
    public $isExpress = false;

    // 🔄 Reset when user types
    public function updatedPincode()
    {
        $this->deliveryAvailable = null;
        $this->deliveryText = null;
        $this->deliveryDate = null;
        $this->isExpress = false;
    }

    // ✅ This must match Blade
    public function check()
    {
        $this->validate([
            'pincode' => 'required|digits:6',
        ]);

        $service = app(ShiprocketService::class);
        $result = $service->checkServiceability($this->pincode);

        // ❌ Not serviceable
        if (!$result['available']) {
            $this->deliveryAvailable = false;
            $this->deliveryText = "Delivery not available";
            return;
        }

        // ✅ Serviceable
        $this->deliveryAvailable = true;

        $days = $result['min_days'];
        $this->isExpress = $days <= 2;

        $date = Carbon::now()->addDays($days);

        if ($days === 0) {
            $this->deliveryText = "Today";
        } elseif ($days === 1) {
            $this->deliveryText = "Tomorrow";
        } else {
            $this->deliveryText = $date->format('D, d M');
        }

        $this->deliveryDate = $this->deliveryText;
    }

    public function render()
    {
        return view('livewire.front.pincode-checker');
    }
}
