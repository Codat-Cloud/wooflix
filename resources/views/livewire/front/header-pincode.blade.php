<div class="dropdown location-dropdown d-inline-block">

    <button
        class="btn location-btn dropdown-toggle align-items-center"
        data-bs-toggle="dropdown"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
            <circle cx="12" cy="10" r="3"/>
        </svg>

        <span>
            {{ $pincode ?: 'Pincode' }}
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
                placeholder="Enter Pincode"
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