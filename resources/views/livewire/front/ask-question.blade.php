<div>
    @if (session()->has('question_success'))
        <div class="alert alert-success border-0 shadow-sm my-2">{{ session('question_success') }}</div>
    @endif

    <form wire:submit.prevent="submit" class="review-form">
        <div class="row g-3">
            @guest
                <div class="col-md-6">
                    <input type="text" wire:model="name" class="form-control" placeholder="Your Name" />
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6">
                    <input type="email" wire:model="email" class="form-control" placeholder="Email" />
                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            @else
                <div class="col-md-12">
                    <p class="text-muted small">Posting as: <strong>{{ $name }}</strong></p>
                </div>
            @endguest

            <div class="col-md-12">
                <textarea wire:model="question" class="form-control" rows="4" placeholder="Ask your question"></textarea>
                @error('question') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-orange">Submit Question</button>
            </div>
        </div>
    </form>
</div>