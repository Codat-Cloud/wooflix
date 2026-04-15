<div x-data="{ 
    selectedPrice: {{ $product->sale_price }}, 
    selectedMRP: {{ $product->base_price }},
    selectedId: {{ $product->variants->first()->id ?? 'null' }},
    loading: false,
    success: false
}">
    <div class="product-price border-bottom">
        <div>
            <span class="price">₹<span x-text="selectedPrice.toFixed(2)"></span></span>
            <template x-if="selectedMRP > selectedPrice">
                <span class="old-price">MRP: ₹<span x-text="selectedMRP.toFixed(2)"></span></span>
            </template>
        </div>
    </div>

    @if($product->variants->count() > 0)
        <div class="product-variants py-2">
            <h6 class="fw-bold">Select Option</h6>
            <div class="variant-options">
                @foreach($product->variants as $variant)
                    <div class="variant-item">
                        <button type="button" class="variant-btn" 
                            :class="selectedId == {{ $variant->id }} ? 'active' : ''"
                            @click="selectedPrice = {{ $variant->sale_price }}; selectedMRP = {{ $variant->price }}; selectedId = {{ $variant->id }};">
                            {{ $variant->name }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-3">
        <div x-show="success" class="alert alert-success py-2 small mb-2 border-0 shadow-sm" x-cloak>
            ✓ Item added to cart!
        </div>

        <button 
            type="button"
            @click="
                loading = true;
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ product_id: {{ $product->id }}, variant_id: selectedId })
                })
                .then(res => res.json())
                .then(data => { 
                    loading = false; 
                    success = true; 
                    setTimeout(() => success = false, 3000);
                    window.dispatchEvent(new CustomEvent('cart-updated')); 
                })
            "
            :disabled="loading"
            class="btn btn-orange add-cart-btn w-100"
        >
            <span x-show="!loading">Add To Cart</span>
            <span x-show="loading">Adding...</span>
        </button>
    </div>
</div>