<div class="drawer-forms">

    @if($successMessage)
        <div class="alert alert-success">
            {{ $successMessage }}
        </div>
    @endif

    <!-- PROFILE -->
    <form wire:submit.prevent="updateProfile" class="profile-section">

        <div class="section-header">
            <h6>Profile Information</h6>
            <p>Update your name and email</p>
        </div>

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" wire:model.defer="name" class="form-control custom-input">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" wire:model.defer="email" class="form-control custom-input">
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-orange w-100 mt-2">
            Save Changes
        </button>
    </form>

    <!-- PASSWORD -->
    <form wire:submit.prevent="updatePassword" class="profile-section">

        <div class="section-header">
            <h6>Update Password</h6>
        </div>

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" wire:model.defer="current_password" class="form-control custom-input">
            @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" wire:model.defer="password" class="form-control custom-input">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" wire:model.defer="password_confirmation" class="form-control custom-input">
        </div>

        <button class="btn btn-outline-dark w-100 mt-2">
            Update Password
        </button>
    </form>

</div>