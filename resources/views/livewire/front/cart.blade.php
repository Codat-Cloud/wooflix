<span>

    <!-- CART BUTTON -->
    <span
        class="cart-btn position-relative me-2"
        data-bs-toggle="offcanvas"
        data-bs-target="#cartDrawer"
    >
        🛒 Cart
        <span class="cart-count">{{ $count }}</span>
    </span>

    <!-- OFFCANVAS -->
    <div class="offcanvas offcanvas-end cart-drawer" tabindex="-1" id="cartDrawer" wire:ignore.self>

        <div class="offcanvas-header">
            <h5>Your Cart</h5>
            <button class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column">

            {{-- ================= CART ITEMS ================= --}}
            <div class="cart-items">

                @forelse($items as $item)

                    <div class="cart-item">

                        <!-- REMOVE -->
                        <button 
                            class="remove-icon"
                            wire:click="remove({{ $item->id }})"
                        >
                            ✕
                        </button>

                        <!-- IMAGE -->
                        <img 
                            src="{{ asset('storage/' . ($item->variant->image ?? $item->product->main_image)) }}"
                            class="cart-image"
                        />

                        <!-- DETAILS -->
                        <div class="cart-details">

                            <p class="cart-title" title="{{$item->display_name}}">

                                {{ \Illuminate\Support\Str::limit($item->display_name, 35) }}
                            </p>

                            <div class="cart-bottom">

                                <!-- QTY -->
                                <div class="qty-control">

                                    <button 
                                        wire:click="decrease({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="decrease({{ $item->id }})"
                                        wire:click.stop="decrease({{ $item->id }})"
                                    >
                                        −
                                    </button>

                                    <input 
                                        class="qty-input" 
                                        value="{{ $item->quantity }}" 
                                        readonly 
                                    />

                                    <button 
                                        wire:click="increase({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="increase({{ $item->id }})"
                                        wire:click.stop="increase({{ $item->id }})"
                                    >
                                        +
                                    </button>

                                </div>

                                <!-- PRICE -->
                                <div class="item-price">
                                    ₹{{ number_format($item->price, 2) }}
                                    @if($item->variant_name)
                                    <br>
                                        <small class="">
                                            {{ $item->variant_name }}
                                        </small>
                                    @endif
                                </div>

                            </div>

                        </div>
                    </div>

                @empty

                    <!-- EMPTY STATE -->
                    <div class="empty-cart text-center w-100">

                        <img src="{{ asset('images/empty-cart.png') }}" />

                        <h5>Your cart is empty</h5>

                        <p>Looks like you haven't added anything yet.</p>

                        <a href="/" class="start-shopping">Start Shopping</a>

                    </div>

                @endforelse

            </div>

            {{-- ================= SUMMARY ================= --}}
            @if(count($items) > 0)

                <div class="cart-summary mt-auto">

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₹₹{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span>
                        ₹{{ number_format($this->items->sum(fn($i) => $i->price * $i->quantity), 2) }}
                        </span>
                    </div>

                    <button class="checkout-btn">
                        Proceed to Checkout
                    </button>

                </div>

            @endif

        </div>
    </div>

</span>