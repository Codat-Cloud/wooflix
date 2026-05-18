<div class="dropdown location-dropdown d-inline-block">

    <button
        class="btn location-btn dropdown-toggle"
        data-bs-toggle="dropdown"
    >
        📍

        <span>
            {{ $pincode ?: 'Select Pincode' }}
        </span>

    </button>

    <div
        class="dropdown-menu dropdown-menu-end location-menu"
        style="width: 300px;"
    >

        <small class="text-center">

            Enter your pincode to check product availability

        </small>

        <form
            wire:submit.prevent="save"
            class="pincode-form"
        >

            <input
                type="text"
                class="form-control"
                wire:model.lazy="pincode"
                placeholder="Enter pincode"
                maxlength="6"
                height="20px"
            />

            <button
                type="submit"
                class="btn btn-orange"
            >

                Save

            </button>

        </form>

    </div>

</div>