<?php

namespace App\Livewire\Front;

use App\Models\Subscriber;
use Livewire\Attributes\Rule;
use Livewire\Component;

class NewsletterForm extends Component
{
    #[Rule('required|email|unique:subscribers,email', message: [
        'unique' => 'This email is already part of our pack!',
        'email' => 'Please enter a valid paw-mail address.',
    ])]

    public $email = '';

    public $subscribed = false;

    public function subscribe()
    {
        $this->validate();

        Subscriber::create([
            'email' => $this->email,
        ]);

        $this->reset('email');

        $this->subscribed = true;

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Thanks for joining the pack!'
        ]);
    }

    public function render()
    {
        return view('livewire.front.newsletter-form');
    }
}
