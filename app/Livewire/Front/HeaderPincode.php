<?php

namespace App\Livewire\Front;

use Livewire\Component;

class HeaderPincode extends Component
{

    public $pincode = '';

    public function mount()
    {
        $saved = session('delivery_check');

        $this->pincode = $saved['pincode'] ?? '';
    }

    public function save()
    {
        $this->validate([
            'pincode' => 'required|digits:6',
        ]);

        $existing = session('delivery_check', []);

        session([
            'delivery_check' => [
                ...$existing,
                'pincode' => $this->pincode,
            ]
        ]);

        $this->dispatch('pincodeUpdated');
    }
    
    public function render()
    {
        return view('livewire.front.header-pincode');
    }
}
