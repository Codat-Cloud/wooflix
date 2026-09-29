@extends('layouts.front')

@section('title', $page->seo_title ?? $page->title)
@section('meta_description', $page->seo_description)

{{-- 🟢 1. Inject GrapesJS compiled styles if they exist --}}
@push('styles')
    @if($page->css)
        <style>
            {!! $page->css !!}
        </style>
    @endif
@endpush

@section('content')

    {{-- 🟢 2. VISUAL BUILDER (Full-width canvas output) --}}
    @if($page->isVisualBuilder())
        <main class="page-builder-content">
            {!! $page->content !!}
        </main>

    {{-- 🟢 3. STANDARD SIMPLE PAGE (Preserves original breadcrumb & container layout) --}}
    @else
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">{{ $page->title }}</li>
                        </ol>
                    </nav>

                    <h1 class="mb-4">{{ $page->title }}</h1>
                    
                    <div class="page-content">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.ajax-contact-form').forEach(form => {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                const submitBtn = form.querySelector('button[type="submit"]');
                const feedbackBox = form.querySelector('.form-feedback') || document.createElement('div');
                const originalText = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Sending...';

                // Grab CSRF token from page header
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                try {
                    const response = await fetch(form.getAttribute('action'), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(Object.fromEntries(new FormData(form)))
                    });

                    const data = await response.json();

                    feedbackBox.style.display = 'block';
                    if (response.ok && data.success) {
                        feedbackBox.className = 'alert alert-success p-2 small mt-3';
                        feedbackBox.innerText = data.message;
                        form.reset();
                    } else {
                        const err = data.message || Object.values(data.errors || {})[0]?.[0] || 'Unable to send message.';
                        feedbackBox.className = 'alert alert-danger p-2 small mt-3';
                        feedbackBox.innerText = err;
                    }
                } catch (error) {
                    feedbackBox.style.display = 'block';
                    feedbackBox.className = 'alert alert-danger p-2 small mt-3';
                    feedbackBox.innerText = 'Network error. Please try again.';
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });
    });
</script>
@endpush