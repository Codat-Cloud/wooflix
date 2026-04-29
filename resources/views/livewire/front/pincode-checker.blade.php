<div class="delivery-check">
    <input 
        type="text"
        class="pincode-input"
        placeholder="Enter pincode"
        wire:model.defer="pincode"
        maxlength="6"
    />

    <button 
        class="btn btn-orange delivery-btn"
        wire:click="check"
        wire:loading.attr="disabled"
    >
    {{-- LOADING --}}
    <span wire:loading wire:target="check">
        <span class="spinner-border spinner-border-sm"></span>
    </span>

    {{-- SUCCESS --}}
    @if($deliveryAvailable === true)
        <span class="text-success">✔</span>

    {{-- FAILURE --}}
    @elseif($deliveryAvailable === false)
        <span class="text-danger">✖</span>

    {{-- DEFAULT --}}
    @else
        <span wire:loading.remove wire:target="check">Check</span>
    @endif
    </button>
</div>

@if($deliveryText) {{-- show only after check --}}
<div class="delivery-items">

    {{-- EXPRESS --}}
    @if($isExpress)
        <div class="delivery-item">
            <span class="delivery-icon">⚡</span>
            <span>Get it <strong>{{ $deliveryText }}</strong></span>
        </div>
    @else
        <div class="delivery-item">
            <span class="delivery-icon">⚡</span>
            <span>Express Delivery <strong>Not Available</strong></span>
        </div>
    @endif

    {{-- DELIVERY DATE --}}
    <div class="delivery-item">
        <span class="delivery-icon">🚚</span>
        <span>
            Expected delivery date – 
            <strong>{{ $deliveryDate }}</strong>
        </span>
    </div>

</div>
@endif