<?php

namespace App\Livewire\Front;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Wishlist as WishlistModel;

class Wishlist extends Component
{
    public $productId;
    public $variantId = null;
    public $isWishlisted = false;

    public function mount($productId, $variantId = null)
    {
        $this->productId = $productId;
        $this->variantId = $variantId;

        if (Auth::check()) {
            $this->isWishlisted = WishlistModel::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->when($variantId, fn($q) => $q->where('variant_id', $variantId))
                ->exists();
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            $this->dispatch('open-login-modal');
        }

        $query = WishlistModel::where('user_id', Auth::id())
            ->where('product_id', $this->productId);

        if ($this->variantId) {
            $query->where('variant_id', $this->variantId);
        }

        if ($query->exists()) {
            $query->delete();
            $this->isWishlisted = false;
        } else {
            WishlistModel::create([
                'user_id' => Auth::id(),
                'product_id' => $this->productId,
                'variant_id' => $this->variantId,
            ]);

            $this->isWishlisted = true;
        }
    }

    public function render()
    {
        return view('livewire.front.wishlist');
    }
}
