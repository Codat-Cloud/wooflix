<?php

namespace App\Livewire\Front;

use App\Models\Review;
use App\Models\ReviewImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class WriteReview extends Component
{
    use WithFileUploads;

    public $productId;
    public $name;
    public $email;
    public $comment;
    public $rating = 5; 
    public $images = [];

    public function mount($productId)
    {
        $this->productId = $productId;

        // FEATURE: If logged in, pre-fill and hide name/email fields
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }
    }

    public function removeImage($index)
    {
        // FEATURE: Allows user to deselect an image before uploading
        array_splice($this->images, $index, 1);
    }

    public function submit()
    {
        $this->validate([
            'name' => Auth::check() ? 'nullable' : 'required|string|max:255',
            'email' => Auth::check() ? 'nullable' : 'required|email|max:255',
            'comment' => 'required|string|min:10',
            'rating' => 'required|integer|between:1,5',
            'images.*' => 'nullable|image|max:2048', 
        ]);

        // 1. Create the Review
        $review = Review::create([
            'product_id' => $this->productId,
            'user_id' => Auth::id(), // Links to user if logged in
            'customer_name' => $this->name,
            'customer_email' => $this->email,
            'comment' => $this->comment,
            'rating' => $this->rating,
            'is_approved' => false, 
            'is_verified_buyer' => false, 
        ]);

        // 2. Handle Image Uploads
        if ($this->images) {
            foreach (collect($this->images)->take(5) as $image) {
                $path = $image->store('reviews', 'public');
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image_path' => $path,
                ]);
            }
        }

        session()->flash('success', 'Thank you! Your review has been submitted for approval.');

        // 3. Reset form (keeping name/email if they are logged in)
        $this->reset(['comment', 'rating', 'images']);
        if (!Auth::check()) {
            $this->reset(['name', 'email']);
        }
    }

    public function render()
    {
        return view('livewire.front.write-review');
    }
}