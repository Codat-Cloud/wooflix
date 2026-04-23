<div class="checkout-card address-section">

    <h6>Your Saved Delivery Address</h6>

    <!-- ADDRESS LIST -->
    <div class="address-list">

        @foreach($addresses as $address)
            <label class="address-card">

                <input type="radio" name="address">

                <div class="address-content">
                    <strong>{{ $address->address_line1 }}</strong>

                    <p>
                        {{ $address->name }}<br>
                        {{ $address->city }}, {{ $address->state }} - {{ $address->postal_code }}<br>
                        📞 {{ $address->phone }}
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a wire:click="edit({{ $address->id }})" class="edit-address">Edit</a>
                    <a wire:click="delete({{ $address->id }})" class="text-danger">Delete</a>
                </div>

            </label>
        @endforeach

    </div>

    <!-- ADD / EDIT BUTTON -->
    <button 
        class="btn btn-light w-100 mt-3"
        data-bs-toggle="collapse"
        data-bs-target="#addressForm"
    >
        {{ $editingId ? 'Edit Address' : '+ Add New Address' }}
    </button>

    <!-- FORM -->
    <div class="collapse mt-3 show" id="addressForm">

        <form wire:submit.prevent="save" class="row g-3">

            <div class="col-md-6">
                <input type="text" wire:model.defer="form.name" class="form-control" placeholder="Full Name">
            </div>

            <div class="col-md-6">
                <input type="text" wire:model.defer="form.phone" class="form-control" placeholder="Phone Number">
            </div>

            <div class="col-12">
                <textarea wire:model.defer="form.address_line1" class="form-control" rows="2" placeholder="Address"></textarea>
            </div>

            <div class="col-md-4">
                <input type="text" wire:model.defer="form.city" class="form-control" placeholder="City">
            </div>

            <div class="col-md-4">
                <input type="text" wire:model.defer="form.state" class="form-control" placeholder="State">
            </div>

            <div class="col-md-4">
                <input type="text" wire:model.defer="form.postal_code" class="form-control" placeholder="Pincode">
            </div>

            <div class="col-12">
                <button class="btn btn-orange w-100">
                    {{ $editingId ? 'Update Address' : 'Save Address' }}
                </button>
            </div>

        </form>

    </div>

</div>