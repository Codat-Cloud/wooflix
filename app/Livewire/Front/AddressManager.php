<?php

namespace App\Livewire\Front;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddressManager extends Component
{
    public $addresses = [];
    public $editingId = null;

    public $form = [
        'name' => '',
        'phone' => '',
        'address_line1' => '',
        'address_line2' => '',
        'city' => '',
        'state' => '',
        'postal_code' => '',
    ];

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $this->addresses = Auth::user()->addresses()->latest()->get();
    }

    // ================= ADD / UPDATE =================
    public function save()
    {
        $this->validate([
            'form.name' => 'required',
            'form.phone' => 'required',
            'form.address_line1' => 'required',
            'form.city' => 'required',
            'form.state' => 'required',
            'form.postal_code' => 'required',
        ]);

        if ($this->editingId) {
            Address::where('id', $this->editingId)
                ->where('user_id', Auth::id())
                ->update($this->form);
        } else {
            Address::create([
                ...$this->form,
                'user_id' => Auth::id(),
                'country' => 'India',
            ]);
        }

        $this->resetForm();
        $this->loadAddresses();
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->editingId = $id;
        $this->form = $address->only([
            'name',
            'phone',
            'address_line1',
            'address_line2',
            'city',
            'state',
            'postal_code',
        ]);
    }

    // ================= DELETE =================
    public function delete($id)
    {
        Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        $this->loadAddresses();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->form = [
            'name' => '',
            'phone' => '',
            'address_line1' => '',
            'address_line2' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
        ];
    }

    public function render()
    {
        return view('livewire.front.address-manager');
    }
}
