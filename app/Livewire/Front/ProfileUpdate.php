<?php

namespace App\Livewire\Front;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ProfileUpdate extends Component
{

    public $name;
    public $email;

    public $current_password;
    public $password;
    public $password_confirmation;

    public $successMessage = '';

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    // ================= PROFILE UPDATE =================
    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = Auth::user();

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->successMessage = "Profile updated successfully";
    }

    // ================= PASSWORD UPDATE =================
    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect');
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        $this->successMessage = "Password updated successfully";
    }
    
    public function render()
    {
        return view('livewire.front.profile-update');
    }
}
