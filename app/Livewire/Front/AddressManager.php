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
        'is_default' => false,
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

        if (Address::where('user_id', Auth::id())->count() === 0) {
            $this->form['is_default'] = true;
        }

        // 🔥 If setting default → remove previous default
        if ($this->form['is_default']) {

            $query = Address::where('user_id', Auth::id());

            if ($this->editingId) {
                $query->where('id', '!=', $this->editingId);
            }

            $query->update(['is_default' => false]);
        }

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
        $this->form = [
            'name' => $address->name,
            'phone' => $address->phone,
            'address_line1' => $address->address_line1,
            'address_line2' => $address->address_line2,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'is_default' => (bool) $address->is_default,
        ];
    }

    // ================= DELETE =================
    public function delete($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$address) return;

        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault) {
            Address::where('user_id', Auth::id())
                ->latest()
                ->first()?->update(['is_default' => true]);
        }

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
            'is_default' => false,
        ];
    }

    public function render()
    {
        return view('livewire.front.address-manager');
    }
}
