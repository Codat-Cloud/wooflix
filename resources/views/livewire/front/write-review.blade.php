<div class="write-review-container">
    @if (session()->has('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="review-form">
        <div class="row g-3">
            
            {{-- FEATURE: Hide fields if user is logged in --}}
            @guest
                <div class="col-md-6">
                    <label class="small fw-bold">Name</label>
                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Your Name" />
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="small fw-bold">Email</label>
                    <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" />
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            @else
                <div class="col-12 mb-2">
                    <p class="small text-muted">Posting as: <span class="fw-bold text-dark">{{ auth()->user()->name }}</span></p>
                </div>
            @endguest

            <div class="col-md-12">
                <label class="small fw-bold">Your Review</label>
                <textarea wire:model="comment" class="form-control @error('comment') is-invalid @enderror" rows="4" placeholder="Share your experience..."></textarea>
                @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="small fw-bold mb-1 d-block">Rating</label>
                <div class="d-flex align-items-center gap-2" x-cloak x-data="{ hover: 0, rating: 0 }">
                {{-- <div class="d-flex align-items-center gap-2" x-data="{ hover: 0, rating: @entangle('rating') }"> --}}
                    @foreach(range(1, 5) as $i)
                        <span 
                            @mouseenter="hover = {{ $i }}" 
                            @mouseleave="hover = 0" 
                            @click="rating = {{ $i }}"
                            class="fs-2 transition"
                            :class="(hover || rating) >= {{ $i }} ? 'text-warning' : 'text-light'"
                            style="cursor: pointer; -webkit-text-stroke: 1px #ffc107;"
                        >
                            ★
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="col-md-6">
                <label class="small fw-bold mb-1 d-block">Photos (Max 5)</label>
                <input type="file" wire:model="images" class="form-control" multiple accept="image/*" id="review-upload" />
                
                {{-- FEATURE: Uploading Indicator --}}
                <div wire:loading wire:target="images" class="mt-2 text-warning small">
                    <div class="spinner-border spinner-border-sm" role="status"></div> Uploading...
                </div>

                {{-- FEATURE: Image Preview Grid with 'X' --}}
                @if($images)
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @foreach($images as $index => $image)
                            <div class="position-relative" style="width: 70px; height: 70px;">
                                <img src="{{ $image->temporaryUrl() }}" class="w-100 h-100 rounded border object-fit-cover">
                                <button type="button" 
                                        wire:click="removeImage({{ $index }})" 
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center shadow-sm"
                                        style="width: 18px; height: 18px; border-radius: 50%; transform: translate(30%, -30%);">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                @error('images.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-12 mt-4">
                <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-dark shadow-sm" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">Submit Review</span>
                    <span wire:loading wire:target="submit">Processing...</span>
                </button>
            </div>
        </div>
    </form>
</div>