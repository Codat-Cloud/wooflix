<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="canonical" href="{{ url()->current() }}">

    @php
        // Logic: Use Page SEO if available, otherwise fallback to Global Settings
        $seoTitle = $page->seo_title ?? $page->title ?? $settings['site_title'] ?? $settings['site_name'] ?? 'Wooflix';
        $seoDesc = $page->seo_description ?? $settings['site_description'] ?? '';
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta name="keywords" content="{{ $settings['site_keywords'] ?? '' }}">

    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:image" content="{{ asset('storage/' . ($settings['og_image_default'] ?? '')) }}">

    @if(!empty($settings['favicon']))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings['favicon']) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $settings['favicon']) }}">
    @endif

    {!! $settings['header_scripts'] ?? '' !!}

    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

  </head>

    <body>
        
        {{-- HEADER --}}
        @include('front.partials.header')

        {{-- MAIN CONTENT --}}
        <main>
            @yield('content')
        </main>

        {{-- FOOTER --}}
        @include('front.partials.footer')

        
        <script>
            window.addEventListener('cart-updated', () => {
                const cart = new bootstrap.Offcanvas('#cartDrawer');
                cart.show();
            });

            document.addEventListener('livewire:init', () => {
                Livewire.on('page-title-updated', (event) => {
                    document.title = event.title;
                });
            });
        </script>
        
        @livewireScripts

        @stack('scripts')
    </body>
</html>
