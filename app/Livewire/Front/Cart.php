<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;

class Cart extends Component
{
    public $items = [];
    public $count = 0;

    protected $listeners = ['add-to-cart' => 'add'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $sessionId = session()->getId();

        $this->items = CartItem::with('product', 'variant')
            ->where('session_id', $sessionId)
            ->get()
            ->values();

        $this->count = collect($this->items)->sum('quantity');
    }

    public function add($variant_id = null, $product_id = null)
    {
        $sessionId = session()->getId();

        if ($variant_id) {

            $variant = \App\Models\ProductVariant::findOrFail($variant_id);

            $exists = \App\Models\CartItem::where('session_id', $sessionId)
                ->where('variant_id', $variant_id)
                ->exists();

            if ($exists) return;

            CartItem::create([
                'user_id' => auth()->id() ?? null,
                'session_id' => $sessionId,
                'product_id' => $variant->product_id,
                'variant_id' => $variant_id,
                'quantity' => 1,
                'price' => $variant->sale_price,
            ]);
        } elseif ($product_id) {

            $product = Product::findOrFail($product_id);

            $exists = CartItem::where('session_id', $sessionId)
                ->where('product_id', $product_id)
                ->whereNull('variant_id')
                ->exists();

            if ($exists) return;

            CartItem::create([
                'user_id' => auth()->id() ?? null,
                'session_id' => $sessionId,
                'product_id' => $product_id,
                'variant_id' => null,
                'quantity' => 1,
                'price' => $product->sale_price ?? $product->base_price,
            ]);
        }

        $this->loadCart();

        $this->dispatch('cart-updated', [
            'count' => $this->count,
            'variant_ids' => \App\Models\CartItem::where('session_id', session()->getId())
                ->pluck('variant_id')
                ->filter()
                ->values()
                ->toArray(),

            'product_ids' => \App\Models\CartItem::where('session_id', session()->getId())
                ->pluck('product_id')
                ->values()
                ->toArray(),
        ]);
    }

    public function remove($id)
    {
        CartItem::find($id)?->delete();
        $this->loadCart();
    }

    public function render()
    {
        return view('livewire.front.cart');
    }

    public function increase($id)
    {
        $item = CartItem::find($id);

        if ($item) {
            $item->increment('quantity');
        }

        $this->loadCart();
    }

    public function decrease($id)
    {
        $item = CartItem::find($id);

        if ($item && $item->quantity > 1) {
            $item->decrement('quantity');
        }

        $this->loadCart();
    }

    public function isInCart($variantId)
    {
        return CartItem::where('session_id', session()->getId())
            ->where('variant_id', $variantId)
            ->exists();
    }
}
