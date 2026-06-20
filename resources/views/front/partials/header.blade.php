<header class="main-header d-none d-lg-block fixed-top">
  <div class="top-strip">
    <div class="container-xxl d-flex justify-content-between">
      <div class="top-links d-none d-lg-block">
        <a href="{{route('order.track')}}">Track Order</a>
      </div>
      <div>Sara’s Wholesome - Balanced Nutrition for Dogs</div>
    </div>
  </div>

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
          <a class="text-decoration-none text-dark me-1" href="{{route('dashboard')}}">♡ Wishlist</a>
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
              <div class="row">
                @foreach($megaMenuCategories as $parentCategory)
                <div class="col-3 mb-3">
                    <div class="mega-column">
                      <p class="h6 mb-1 fw-bold">
                        <a href="{{ route('front.shop', ['tags' => 'dog', 'cat' => $parentCategory->slug]) }}">
                          {{ $parentCategory->name }}
                        </a>
                      </p>

                      @if($parentCategory->children->isNotEmpty())
                        @foreach($parentCategory->children as $subCategory)
                          <a href="{{ route('front.shop', ['tags' => 'dog', 'cat' => $subCategory->slug]) }}">
                            {{-- <img src="{{ asset('assets/images/menu/dog-food.jpg') }}" alt="{{ $subCategory->name }}" />  --}}
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
                <div class="row">
                @foreach($megaMenuCategories as $parentCategory)
                <div class="col-3 mb-3">
                    <div class="mega-column">
                      <p class="h6 mb-1 fw-bold">
                        <a href="{{ route('front.shop', ['tags' => 'cat', 'cat' => $parentCategory->slug]) }}">
                          {{ $parentCategory->name }}
                        </a>
                      </p>

                      @if($parentCategory->children->isNotEmpty())
                        @foreach($parentCategory->children as $subCategory)
                          <a href="{{ route('front.shop', ['tags' => 'cat', 'cat' => $subCategory->slug]) }}">
                            {{-- <img src="{{ asset('assets/images/menu/dog-food.jpg') }}" alt="{{ $subCategory->name }}" />  --}}
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
          <a href="{{ url('/brands') }}">Brands</a>

          <div class="mega-menu shadow">
            <div class="mega-inner container-xxl">
              <div class="mega-column">
                <div class="row">
                  <h6>Popular Brands</h6>
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
                  @foreach($megaMenuCategories as $parentCategory)
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
                  @foreach($megaMenuCategories as $parentCategory)
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
  <a href="/" class="active"><div>🏠</div><span>Home</span></a>
  <a href="{{route('front.shop')}}"><div>⬜</div><span>Collections</span></a>
  <a href="#"><div>🐶</div><span>HUFT Hub</span></a>
  <a href="{{route('front.cart')}}"><div>🛒</div><span>Cart</span></a>
  <a href="{{route('dashboard')}}"><div>👤</div><span>Account</span></a>
</div>