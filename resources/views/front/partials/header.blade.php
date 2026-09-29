<header class="main-header d-none d-lg-block fixed-top">
  @if(!empty($settings['top_bar']))
  <div class="top-strip">
    <div class="container-xxl d-flex justify-content-center">
      <div>
        {{ $settings['top_bar' ?? ''] }}
      </div>
    </div>
  </div>
  @endif

  <div class="pt-3 pb-2">
    <div class="container-xxl">
      <div class="row align-items-center">
        <div class="col-lg-2">
          <div class="logo">
            <a href="/">
              <img src="{{ asset('storage/' . ($settings['logo_desktop'] ?? '')) }}" alt="" class="w-100" />
            </a>
          </div>
        </div>

        <div class="col-lg-5">
          <form action="{{ route('front.shop') }}" method="GET">
            <input
                type="text"
                name="q"
                class="form-control search-box"
                placeholder="Search products..."
                value="{{ request('q') }}"
                autocomplete="off"
            />
          </form>
        </div>

        <div class="col-lg-5 d-flex justify-content-end align-items-center gap-3 header-icons">
          @livewire('front.header-pincode')
          <a class="text-decoration-none text-dark me-1" href="{{route('dashboard')}}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
            </svg>
            Wishlist</a>
          <livewire:front.cart />

          @auth
              <div class="dropdown d-inline-block ms-2">
                  <a class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                          <circle cx="12" cy="7" r="4"></circle>
                          <path d="M5.5 21c1.5-4 11.5-4 13 0"></path>
                      </svg>
                      {{ Str::limit(auth()->user()->name, 5) }}
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                      <li><a class="dropdown-item" href="{{ route('dashboard') }}">My Account</a></li>
                      <li>
                          <form method="POST" action="{{ route('logout') }}">
                              @csrf
                              <button type="submit" class="dropdown-item">Logout</button>
                          </form>
                      </li>
                  </ul>
              </div>
          @else
              <a href="{{ route('login') }}" class="btn btn-orange ms-2">Login / Sign Up</a>
          @endauth
        </div>
      </div>
    </div>
  </div>
</header>

<nav class="main-nav d-none d-lg-block shadow">
  <div class="container-xxl">
    <ul class="nav-menu">
        
      <li class="has-mega">
        <a href="{{ route('front.shop', ['tags' => 'dog']) }}">Dogs</a>

        <div class="mega-menu shadow">
          <div class="mega-inner container-xxl">
            {{-- 🟢 CHANGED: Replaced 'row' with our masonry fluid layout class --}}
            <div class="mega-menu-masonry">
              
              @foreach($dogCategories as $parentCategory)
                {{-- 🟢 CHANGED: Replaced 'col-3' with the block keeper class --}}
                <div class="mega-masonry-item">
                  <div class="mega-column">
                    <p class="h6 mb-1 fw-bold">
                      <a href="{{ route('front.shop', ['tags' => 'dog', 'cat' => $parentCategory->slug]) }}">
                        {{ $parentCategory->name }}
                      </a>
                    </p>

                    @if($parentCategory->children->isNotEmpty())
                      @foreach($parentCategory->children as $subCategory)
                        <a href="{{ route('front.shop', ['tags' => 'dog', 'cat' => $subCategory->slug]) }}">
                          {{ $subCategory->name }}
                        </a>
                      @endforeach
                    @endif
                  </div>
                </div>
              @endforeach

            </div>
          </div>
        </div>
      </li>

      <li class="has-mega">
        <a href="{{ route('front.shop', ['tags' => 'cat']) }}">Cats</a>

        <div class="mega-menu shadow">
          <div class="mega-inner container-xxl">
            <div class="mega-menu-masonry">
              
              @foreach($catCategories as $parentCategory)
                <div class="mega-masonry-item">
                  <div class="mega-column">
                    <p class="h6 mb-1 fw-bold">
                      <a href="{{ route('front.shop', ['tags' => 'cat', 'cat' => $parentCategory->slug]) }}">
                        {{ $parentCategory->name }}
                      </a>
                    </p>

                    @if($parentCategory->children->isNotEmpty())
                      @foreach($parentCategory->children as $subCategory)
                        <a href="{{ route('front.shop', ['tags' => 'cat', 'cat' => $subCategory->slug]) }}">
                          {{ $subCategory->name }}
                        </a>
                      @endforeach
                    @endif
                  </div>
                </div>
              @endforeach

            </div>
          </div>
        </div>
      </li>

      <li class="has-mega">
        <a href="#">Brands</a>

        <div class="mega-menu shadow">
          <div class="mega-inner container-xxl">
            <div class="mega-column">
              <div class="row">
                <h6 class="mb-3">Popular Brands</h6>
                @foreach($brands as $brand)
                  <div class="col-2 mb-3">
                    <a href="{{ route('front.shop', ['brand' => $brand->slug]) }}">
                      <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" /> 
                      {{ $brand->name }}
                    </a>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </li>

      <li>
        <a href="{{ url('/wholesale') }}">Wholesale</a>
      </li>

    </ul>
  </div>
</nav>


<div class="mobile-header d-lg-none">
  <div class="mobile-header-bar">
    <button class="menu-btn" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">☰</button>
    <div class="delivery">@livewire('front.header-pincode')</div>
    <div class="wishlist"><a href="{{route('dashboard')}}" class="text-decoration-none text-dark">♡</a></div>
  </div>

  <div class="mobile-search">
    <div class="search-box-mobile">
      <a href="/"><span class="logo-icon"><img src="{{ asset('storage/' . ($settings['logo_mobile'] ?? ($settings['logo_desktop'] ?? ''))) }}" alt="" style="width: 20px" /></span></a>
      <form action="{{ route('front.shop') }}" method="GET" class="w-100 d-flex align-items-center">
        <input type="text" name="q" class="border-0 bg-transparent w-100" placeholder="Search products..." value="{{ request('q') }}" autocomplete="off" />
        <button type="submit" class="border-0 bg-transparent p-0"><span class="search-icon">🔍</span></button>
      </form>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
  <div class="offcanvas-header bg-primary text-white">
    <h5>Drool-worthy Treats!</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>

<div class="offcanvas-body">
  <div class="mobile-nav">
      
      <div class="mobile-nav-group mb-3">
          <button class="btn w-100 text-start fw-bold d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#mobileDogsGroup">
              Dogs <span>▼</span>
          </button>
          
          <div class="collapse" id="mobileDogsGroup">
              <div class="ps-3 mt-2">
                  {{-- 🟢 Updated loop to iterate through Dog specific rows only --}}
                  @foreach($dogCategories as $parentCategory)
                      <div class="mb-3">
                          <button class="btn w-100 text-start fw-semibold p-0 text-dark d-flex justify-content-between align-items-center mb-1" data-bs-toggle="collapse" data-bs-target="#dogParent{{ $parentCategory->id }}">
                              {{ $parentCategory->name }}
                              @if($parentCategory->children->isNotEmpty()) <small class="text-muted" style="font-size: 10px;">►</small> @endif
                          </button>

                          <div class="collapse" id="dogParent{{ $parentCategory->id }}">
                              <ul class="list-unstyled ps-3 my-2 border-start">
                                  <li class="mb-2">
                                      <a href="{{ route('front.shop', ['tags' => 'dog', 'cat' => $parentCategory->slug]) }}" class="fw-semibold text-primary text-decoration-none small">
                                          View All {{ $parentCategory->name }}
                                      </a>
                                  </li>
                                  @foreach($parentCategory->children as $subCategory)
                                      <li class="mb-2">
                                          <a href="{{ route('front.shop', ['tags' => 'dog', 'cat' => $subCategory->slug]) }}" class="text-muted text-decoration-none small">
                                              {{ $subCategory->name }}
                                          </a>
                                      </li>
                                  @endforeach
                              </ul>
                          </div>
                      </div>
                  @endforeach
              </div>
          </div>
      </div>

      <div class="mobile-nav-group mb-3">
          <button class="btn w-100 text-start fw-bold d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#mobileCatsGroup">
              Cats <span>▼</span>
          </button>
          
          <div class="collapse" id="mobileCatsGroup">
              <div class="ps-3 mt-2">
                  {{-- 🟢 Updated loop to iterate through Cat specific rows only --}}
                  @foreach($catCategories as $parentCategory)
                      <div class="mb-3">
                          <button class="btn w-100 text-start fw-semibold p-0 text-dark d-flex justify-content-between align-items-center mb-1" data-bs-toggle="collapse" data-bs-target="#catParent{{ $parentCategory->id }}">
                              {{ $parentCategory->name }}
                              @if($parentCategory->children->isNotEmpty()) <small class="text-muted" style="font-size: 10px;">►</small> @endif
                          </button>

                          <div class="collapse" id="catParent{{ $parentCategory->id }}">
                              <ul class="list-unstyled ps-3 my-2 border-start">
                                  <li class="mb-2">
                                      <a href="{{ route('front.shop', ['tags' => 'cat', 'cat' => $parentCategory->slug]) }}" class="fw-semibold text-primary text-decoration-none small">
                                          View All {{ $parentCategory->name }}
                                      </a>
                                  </li>
                                  @foreach($parentCategory->children as $subCategory)
                                      <li class="mb-2">
                                          <a href="{{ route('front.shop', ['tags' => 'cat', 'cat' => $subCategory->slug]) }}" class="text-muted text-decoration-none small">
                                              {{ $subCategory->name }}
                                          </a>
                                      </li>
                                  @endforeach
                              </ul>
                          </div>
                      </div>
                  @endforeach
              </div>
          </div>
      </div>

      <div class="mobile-nav-group mb-3">
          <button class="btn w-100 text-start fw-bold d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#mobileBrands">
              Brands <span>▼</span>
          </button>
          <div class="collapse" id="mobileBrands">
              <ul class="list-unstyled ps-3 mt-2">
                  @foreach($brands as $brand)
                      <li class="mb-2">
                          <a href="{{ route('front.shop', ['brand' => $brand->slug]) }}" class="text-dark text-decoration-none">
                              {{ $brand->name }}
                          </a>
                      </li>
                  @endforeach
              </ul>
          </div>
      </div>

      <div class="mt-4 ps-2">
          <a href="{{ url('/wholesale') }}" class="d-block fw-bold text-dark text-decoration-none">
              Wholesale
          </a>
      </div>
  </div>
</div>


</div>

<div class="mobile-bottom-nav d-lg-none">
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        <span>Home</span>
    </a>

    <a href="{{ route('front.shop') }}" class="{{ request()->routeIs('front.shop') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect width="7" height="7" x="3" y="3" rx="1"/>
            <rect width="7" height="7" x="14" y="3" rx="1"/>
            <rect width="7" height="7" x="14" y="14" rx="1"/>
            <rect width="7" height="7" x="3" y="14" rx="1"/>
        </svg>
        <span>Shop</span>
    </a>

    <a href="{{ route('dashboard') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
        </svg>
        <span>Wishlist</span>
    </a>

    <a href="{{ route('front.cart') }}" class="position-relative">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="8" cy="21" r="1"/>
            <circle cx="19" cy="21" r="1"/>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
        </svg>
        <span>Cart</span>
    </a>

    <a href="{{ route('dashboard') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="7" r="4"/>
            <path d="M5.5 21c1.5-4 11.5-4 13 0"/>
        </svg>
        <span>Account</span>
    </a>
</div>