<div class="checkout-card address-section">

    <h6 class="mb-3 fw-bold">Your Saved Delivery Address</h6>

    <!-- ADDRESS LIST -->
    <div class="address-list">

        @foreach($addresses as $address)
            <label class="address-card">

                <input type="radio" name="address" value="{{ $address->id }}">

                <!-- Radio Indicator Checkmark -->
                <span class="custom-radio"></span>

                <!-- Address Body -->
                <div class="address-content">
                    <div class="address-header">
                        <strong class="address-line">{{ $address->address_line1 }}</strong>
                        @if($address->is_default)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Primary</span>
                        @endif
                    </div>

                    <p class="address-details">
                        <span class="fw-medium text-dark">{{ $address->name }}</span><br>
                        {{ $address->city }}, {{ $address->state }} - {{ $address->postal_code }}<br>
                        <span class="text-muted">📞 {{ $address->phone }}</span>
                    </p>
                </div>

                <!-- Action Buttons (Pinned Top-Right) -->
                <div class="address-actions">
                    <button 
                        type="button"
                        wire:click.stop="edit({{ $address->id }})" 
                        class="btn"
                        title="Edit Address"
                        data-bs-toggle="collapse"
                        data-bs-target="#addressForm"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                    </button>

                    <button 
                        type="button"
                        wire:click.stop="delete({{ $address->id }})" 
                        wire:confirm="Are you sure you want to delete this address?"
                        class="btn"
                        title="Delete Address"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>

            </label>
        @endforeach

    </div>

    <!-- ADD / EDIT TOGGLE BUTTON -->
    <button 
        type="button"
        class="btn btn-outline-secondary w-100 my-3 py-2 fw-medium add-address-btn"
        data-bs-toggle="collapse"
        data-bs-target="#addressForm"
    >
        {{ $editingId ? 'Edit Address' : '+ Add New Address' }}
    </button>

    <!-- FORM -->
    <div class="collapse p-2 show" id="addressForm">

        <form wire:submit.prevent="save" class="row g-2 g-sm-3 p-3 border rounded-3 bg-light-subtle">

            <div class="col-12 col-sm-6">
                <input type="text" wire:model.defer="form.name" class="form-control" placeholder="Full Name">
            </div>

            <div class="col-12 col-sm-6">
                <input type="text" wire:model.defer="form.phone" class="form-control" placeholder="Phone Number">
            </div>

            <div class="col-12">
                <textarea wire:model.defer="form.address_line1" class="form-control" rows="2" placeholder="Street Address / House No."></textarea>
            </div>

            <div class="col-6 col-sm-4">
                <input type="text" wire:model.defer="form.city" class="form-control" placeholder="City">
            </div>

            <div class="col-6 col-sm-4">
                <input type="text" wire:model.defer="form.state" class="form-control" placeholder="State">
            </div>

            <div class="col-12 col-sm-4">
                <input type="text" wire:model.defer="form.postal_code" class="form-control" placeholder="Pincode" maxlength="6">
            </div>

            <div class="col-12">
                <div class="form-check my-1">
                    <input 
                        type="checkbox"
                        class="form-check-input"
                        wire:model.defer="form.is_default"
                        id="isDefault"
                    >
                    <label class="form-check-label small user-select-none" for="isDefault">
                        Set as default address
                    </label>
                </div>
            </div>

            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-orange w-100 py-2 fw-semibold">
                    {{ $editingId ? 'Update Address' : 'Save Address' }}
                </button>
            </div>

        </form>

    </div>

</div>