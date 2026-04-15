<?php

namespace App\Livewire\Front;

use App\Models\ProductQuestion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AskQuestion extends Component
{
    public $productId;
    public $name;
    public $email;
    public $question;

    public function mount($productId)
    {
        $this->productId = $productId;

        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'question' => 'required|min:10',
        ]);

        ProductQuestion::create([
            'product_id' => $this->productId,
            'user_id' => Auth::id(),
            'name' => $this->name,
            'email' => $this->email,
            'question' => $this->question,
            'is_visible' => false,
        ]);

        session()->flash('question_success', 'Question sent! We will reply soon.');
        $this->reset('question');
        if (!Auth::check()) {
            $this->reset(['name', 'email']);
        }
    }

    public function render()
    {
        return view('livewire.front.ask-question');
    }
}