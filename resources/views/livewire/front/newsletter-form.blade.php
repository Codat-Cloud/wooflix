<div>
    @if($subscribed)
        <div class="newsletter-success text-success fw-bold d-flex align-items-center gap-2 animate__animated animate__fadeIn">
            <span>🎉</span>
            <div>
                <p class="mb-0">You're on the list!</p>
                <small class="text-muted fw-normal">Check your inbox for a welcome treat soon.</small>
            </div>
        </div>
    @else

        <form class="subscribe-form" wire:submit.prevent="subscribe" class="newsletter-form">
            <div class="form-group">
            <input
                type="email"
                wire:model="email" 
                placeholder="Enter your email" 
                class="form-control @error('email') is-invalid @enderror"
            />
            </div>

            <button type="submit" wire:loading.attr="disabled" class="btn btn-orange w-100 mt-1">
                <span wire:loading.remove>Subscribe</span>
                <span wire:loading>...</span>
            </button>
        </form>
        
    @endif

    @error('email') 
        <small class="text-danger mt-1">{{ $message }}</small> 
    @enderror

</div>