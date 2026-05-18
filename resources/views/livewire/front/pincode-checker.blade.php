<!-- DELIVERY CHECK -->
<div class="delivery-info">

    <h5 class="delivery-title">
        Delivery & Service Information
    </h5>

    <div class="delivery-checker mb-3">

    <div class="d-flex gap-2">

        <input
            type="text"
            wire:model.lazy="pincode"
            class="form-control"
            placeholder="Enter Pincode"
            maxlength="6"
        >

        <button
            wire:click="check"
            wire:loading.attr="disabled"
            wire:target="check"
            class="btn btn-dark d-flex align-items-center justify-content-center"
            style="min-width: 90px;"
            class="btn btn-warning"
        >
            {{-- NORMAL TEXT --}}
            <span wire:loading.remove wire:target="check">
                Check
            </span>

            {{-- LOADER --}}
            <span wire:loading wire:target="check">

            <span
                class="spinner-border spinner-border-sm"
                role="status"
                aria-hidden="true"
            ></span>

            </span>
        </button>

    </div>

    @error('pincode')

        <small class="text-danger">
            {{ $message }}
        </small>

    @enderror

</div>

    <div class="delivery-items">

        {{-- EXPRESS DELIVERY --}}
        <div class="delivery-item">

            <span class="delivery-icon">⚡</span>

            <span>

                @if($deliveryAvailable === false)

                    Express delivery unavailable

                @elseif($deliveryDate)

                    Get it
                    <strong class="text-success">{{ $deliveryText }}</strong>

                @else

                    Check delivery availability

                @endif

            </span>

        </div>

        {{-- DELIVERY DATE --}}
        <div class="delivery-item">

            <span class="delivery-icon">🚚</span>

            <span>

                @if($deliveryAvailable === false)

                    Delivery not available

                @elseif($deliveryDate)

                    Expected delivery date –
                    <strong class="text-success">{{ $deliveryDate }}</strong>

                @else

                    Enter pincode for delivery date

                @endif

            </span>

        </div>

        {{-- RETURN POLICY --}}
        <div class="delivery-item">

            <span class="delivery-icon">📦</span>

            <span>No Exchange & Returns</span>

        </div>

        {{-- FREE DELIVERY --}}
        <div class="delivery-item">

            <span class="delivery-icon free">FREE</span>

            <span>

                Enjoy Free Delivery above
                <strong>₹699</strong>

            </span>

        </div>

    </div>

</div>