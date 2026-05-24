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
    public $subtotal = 0;

    protected $listeners = [
        'add-to-cart'  => 'add',
        'cart-updated' => 'loadCart', // Tells the drawer to refresh its content automatically
    ];


    public function updateTotals()
    {
        $this->count = collect($this->items)->sum('quantity');

        $this->subtotal = collect($this->items)
            ->sum(fn($i) => $i->price * $i->quantity);
    }

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $sessionId = session()->getId();

        $query = CartItem::with('product', 'variant');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', $sessionId);
        }

        $this->items = $query->get()->values();

        $this->updateTotals();
    }

    public function add($variant_id = null, $product_id = null)
    {
        // Support both named object and positional arguments
        if (is_array($variant_id)) {
            $data = $variant_id;
            $variant_id = $data['variant_id'] ?? null;
            $product_id = $data['product_id'] ?? null;
        }


        $sessionId = session()->getId();
        $userId = auth()->id();

        // 1. Create/Update Cart Item
        $variant = ProductVariant::find($variant_id);

        if (!$variant || $variant->stock <= 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Sorry, this item just went out of stock!'
            ]);
            return;
        }

        if (!$variant) return;

        CartItem::updateOrCreate(
            [
                'variant_id' => $variant_id,
                'user_id'    => $userId ?? null,
                'session_id' => $userId ? null : $sessionId,
            ],
            [
                'product_id' => $product_id,
                'price'      => $variant->display_price,
                'quantity'   => 1, // Or increment logic
            ]
        );

        $this->loadCart();

        // 2. CRITICAL: Get all variant IDs currently in the cart to sync with Frontend
        $currentCartIds = CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })
            ->pluck('variant_id')
            ->filter()
            ->map(fn($id) => (int)$id) // Cast to integers for JS
            ->values()
            ->toArray();

        // 3. Dispatch the event that Alpine.js is listening for (@cart-updated.window)
        $this->dispatch('cart-updated', [
            'variant_ids' => $currentCartIds,
            'count' => count($currentCartIds)
        ]);
    }

    public function remove($id)
    {
        CartItem::find($id)?->delete();
        $this->loadCart();

        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.front.cart');
    }

    public function increase($id)
    {
        foreach ($this->items as $key => $item) {

            if ($item->id == $id) {

                // Reload fresh from DB (avoid stale data)
                $dbItem = CartItem::with('variant', 'product')->find($id);

                if (!$dbItem) return;

                // Determine stock source
                $stock = $dbItem->variant?->stock ?? 0;

                // ✅ Stock check
                if ($dbItem->quantity >= $stock) {

                    $this->dispatch('notify', [
                        'type' => 'error',
                        'message' => 'Maximum available stock reached'
                    ]);

                    return;
                }

                // ✅ Optimistic UI update
                $this->items[$key]->quantity++;

                // ✅ DB update
                $dbItem->increment('quantity');

                break;
            }
        }

        $this->updateTotals();

        $this->dispatch('cart-updated');
    }

    public function decrease($id)
    {
        foreach ($this->items as $key => $item) {

            if ($item->id == $id) {

                if ($item->quantity <= 1) return;

                $this->items[$key]->quantity--;

                CartItem::where('id', $id)->decrement('quantity');

                break;
            }
        }

        $this->updateTotals();

        $this->dispatch('cart-updated');
    }

    public function isInCart($variantId)
    {
        return CartItem::where('session_id', session()->getId())
            ->where('variant_id', $variantId)
            ->exists();
    }
}
