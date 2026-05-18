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

    public function mount()
    {
        $saved = session('delivery_check');

        if ($saved) {

            $this->pincode = $saved['pincode'];

            $this->deliveryAvailable =
                $saved['deliveryAvailable'] ?? null;

            $this->deliveryText =
                $saved['deliveryText'] ?? null;

            $this->deliveryDate =
                $saved['deliveryDate'] ?? null;

            $this->isExpress =
                $saved['isExpress'] ?? false;
        }
    }

    // 🔄 Reset when user types
    public function updatedPincode()
    {
        $saved = session('delivery_check');

        // No saved state
        if (!$saved) {
            return;
        }

        // Same pincode → keep results
        if ($saved['pincode'] == $this->pincode) {
            return;
        }

        // Different pincode → reset old result
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

        session([
            'delivery_check' => [
                'pincode' => $this->pincode,
                'deliveryAvailable' => $this->deliveryAvailable,
                'deliveryText' => $this->deliveryText,
                'deliveryDate' => $this->deliveryDate,
                'isExpress' => $this->isExpress,
            ]
        ]);

        $this->dispatch('pincodeUpdated');
    }

    public function render()
    {
        return view('livewire.front.pincode-checker');
    }
}
