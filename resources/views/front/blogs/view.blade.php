@extends('layouts.front')

@section('content')
<article class="blog-single py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                {{-- Breadcrumbs / Category --}}
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb justify-content-center">
                        @foreach($blog->categories as $category)
                            <li class="breadcrumb-item">
                                <a href="#" class="text-orange text-decoration-none fw-bold">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ol>
                </nav>

                {{-- Title Section --}}
                <header class="text-center mb-5">
                    <h1 class="display-4 fw-bold mb-3">{{ $blog->title }}</h1>
                    <div class="text-muted small">
                        <i class="bi bi-calendar3 me-1"></i> {{ $blog->published_at?->format('M d, Y') ?? $blog->created_at->format('M d, Y') }}
                        <span class="mx-2">|</span>
                        <i class="bi bi-clock me-1"></i> {{ ceil(str_word_count(strip_tags($blog->content)) / 200) }} min read
                    </div>
                </header>

                {{-- Featured Image --}}
                @if($blog->featured_image)
                    <div class="mb-5 shadow-sm rounded-4 overflow-hidden">
                        <img src="{{ asset('storage/' . $blog->featured_image) }}" 
                             alt="{{ $blog->image_alt ?? $blog->title }}" 
                             class="img-fluid w-100">
                    </div>
                @endif

                {{-- Content Section --}}
                <div class="blog-content fs-5 lh-lg text-dark mb-5">
                    {!! $blog->content !!}
                </div>

                <hr class="my-5">

                {{-- Prev/Next Navigation --}}
                <div class="row g-4 mb-5">
                    <div class="col-6">
                        @if($blog->previous_post)
                            <a href="{{ route('front.blogs.view', $blog->previous_post->slug) }}" class="text-decoration-none group">
                                <small class="text-muted d-block text-uppercase">Previous Post</small>
                                <span class="fw-bold text-dark h5">← {{ Str::limit($blog->previous_post->title, 40) }}</span>
                            </a>
                        @endif
                    </div>
                    <div class="col-6 text-end">
                        @if($blog->next_post)
                            <a href="{{ route('front.blogs.view', $blog->next_post->slug) }}" class="text-decoration-none">
                                <small class="text-muted d-block text-uppercase">Next Post</small>
                                <span class="fw-bold text-dark h5">{{ Str::limit($blog->next_post->title, 40) }} →</span>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Related Posts Section --}}
    @if($relatedPosts->count() > 0)
    <div class="bg-light py-5">
        <div class="container">
            <h3 class="text-center fw-bold mb-4">You Might Also Like</h3>
            <div class="row g-4">
                @foreach($relatedPosts as $post)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">
                                    <a href="{{ route('front.blogs.view', $post->slug) }}" class="text-dark text-decoration-none">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</article>
@endsection

@push('styles')
<style>
    .blog-content p { margin-bottom: 1.5rem; }
    .blog-content img { max-width: 100%; border-radius: 1rem; height: auto; }
    .text-orange { color: #ff6b35; } /* Your Wooflix theme color */
    .breadcrumb-item + .breadcrumb-item::before { content: "•"; }
</style>
@endpush