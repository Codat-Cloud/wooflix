    <!-- DESKTOP HEADER -->

    <header class="main-header d-none d-lg-block fixed-top">
      <!-- TOP STRIP -->

      <div class="top-strip">
        <div class="container-xxl d-flex justify-content-between">
          <div class="top-links d-none d-lg-block">
            <a href="https://www.shiprocket.in/shipment-tracking">Track Order</a>
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

              {{-- Header Pincode --}}
              @livewire('front.header-pincode')

              <a class="text-decoration-none text-dark me-1" href="{{route('dashboard')}}" class="me-2">♡ Wishlist</a>
              <livewire:front.cart />

              @auth
                  <div class="dropdown d-inline-block ms-2">
                      <a class="btn btn-light dropdown-toggle d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown">

                          <!-- SVG ICON (better than emoji) -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                              viewBox="0 0 24 24" fill="none" stroke="currentColor"
                              stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                              <circle cx="12" cy="7" r="4"></circle>
                              <path d="M5.5 21c1.5-4 11.5-4 13 0"></path>
                          </svg>

                          {{ Str::limit(auth()->user()->name, 10) }}
                      </a>

                      <ul class="dropdown-menu dropdown-menu-end">
                          <li>
                              <a class="dropdown-item" href="{{ route('dashboard') }}">
                                  My Account
                              </a>
                          </li>

                          <li>
                              <form method="POST" action="{{ route('logout') }}">
                                  @csrf
                                  <button type="submit" class="dropdown-item">
                                      Logout
                                  </button>
                              </form>
                          </li>
                      </ul>
                  </div>
              @else
                  <a href="{{ route('login') }}" class="btn btn-orange ms-2">
                      Login / Sign Up
                  </a>
              @endauth

            </div>
          </div>
        </div>
      </div>
    </header>



    <!-- DESKTOP NAV -->

    <nav class="main-nav d-none d-lg-block shadow">
      <div class="container-xxl">
        <ul class="nav-menu">
        @foreach($petTypes as $petType)

        <li class="has-mega">

            {{-- Main Navigation Link --}}
            <a href="{{ route('front.shop', ['tags' => $petType->slug]) }}">
                {{ $petType->name }}
            </a>

            <div class="mega-menu shadow">

                <div class="mega-inner container-xxl">

                    @foreach($petType->categories as $parentCategory)

                        <div class="mega-column">

                            {{-- Parent Category --}}
                            <h6>

                                <a href="{{ route('front.shop', ['cat' => $parentCategory->slug]) }}">
                                    {{ $parentCategory->name }}
                                </a>

                            </h6>

                            {{-- Child Categories --}}
                            @foreach($parentCategory->children as $child)

                                <a href="{{ route('front.shop', ['cat' => $child->slug]) }}">

                                    @if($child->image)

                                        <img
                                            src="{{ asset('storage/' . $child->image) }}"
                                            alt="{{ $child->name }}"
                                        >

                                    @endif

                                    {{ $child->name }}

                                </a>

                            @endforeach

                        </div>

                    @endforeach

                </div>

            </div>

        </li>

        @endforeach

          <li class="has-mega">
            <a href="#">Brands</a>
            <div class="mega-menu shadow">
              <div class="mega-inner container-xxl">
                <div class="mega-column">
                    <h6>Available Brands</h6>

                    <div class="row">
                      @foreach($brands as $brand)
                      <div class="col-3">
                            <a href="{{ route('front.shop', ['brand' => $brand->slug]) }}">
                                <img 
                                    src="{{ asset('storage/' . $brand->logo) }}" 
                                    alt="{{ $brand->name }}"
                                >
                                {{ $brand->name }}
                            </a>
                            
                          </div>
                          @endforeach
                    </div>

                </div>
              </div>
            </div>
          </li>

          <li><a href="{{route('front.wholesale')}}">Wholesale</a></li>
        </ul>
      </div>
    </nav>

    <!-- MOBILE HEADER -->

    <div class="mobile-header d-lg-none">
      <div class="mobile-header-bar">
        <button
          class="menu-btn"
          data-bs-toggle="offcanvas"
          data-bs-target="#mobileMenu"
        >
          ☰
        </button>

        <div class="delivery">
          @livewire('front.header-pincode')
          {{-- 📍 Delivering to <strong> {{ session('delivery_check.pincode') ?: 'Location' }}</strong>
          <span class="change">Change</span> --}}
        </div>

        <div class="wishlist">
          <a href="{{route('dashboard')}}" class="text-decoration-none text-dark">
            ♡
          </a>
          </div>
      </div>

      <div class="mobile-search">
        <div class="search-box-mobile">
          <a href="/">
            <span class="logo-icon">
              <img src="{{ asset('storage/' . ($settings['logo_mobile'] ?? ($settings['logo_desktop'] ?? ''))) }}" alt="" style="width: 20px" />
            </span>
          </a>

          <form
              action="{{ route('front.shop') }}"
              method="GET"
              class="w-100 d-flex align-items-center"
          >

              <input
                  type="text"
                  name="q"
                  class="border-0 bg-transparent w-100"
                  placeholder="Search products..."
                  value="{{ request('q') }}"
                  autocomplete="off"
              />

              <button
                  type="submit"
                  class="border-0 bg-transparent p-0"
              >
                  <span class="search-icon">🔍</span>
              </button>

          </form>
        </div>
      </div>
    </div>

    <!-- MOBILE OFFCANVAS MENU -->

    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
      <div class="offcanvas-header bg-primary text-white">
        <h5>Drool-worthy Treats!</h5>
        <button
          type="button"
          class="btn-close btn-close-white"
          data-bs-dismiss="offcanvas"
        ></button>
      </div>

      <div class="offcanvas-body">
        <div class="mobile-nav">

            {{-- PET TYPES --}}
            @foreach($petTypes as $petType)

                <div class="mobile-nav-group mb-3">

                    {{-- PET TYPE --}}
                    <button
                        class="btn w-100 text-start fw-bold"
                        data-bs-toggle="collapse"
                        data-bs-target="#petType{{ $petType->id }}"
                    >

                        {{ $petType->name }}

                    </button>

                    {{-- CATEGORY DROPDOWN --}}
                    <div
                        class="collapse"
                        id="petType{{ $petType->id }}"
                    >

                        <ul class="list-unstyled ps-3">

                            @foreach($petType->categories as $parentCategory)

                                <li class="mb-2">

                                    {{-- Parent Category --}}
                                    <a
                                        href="{{ route('front.shop', ['cat' => $parentCategory->slug]) }}"
                                        class="fw-semibold text-dark text-decoration-none"
                                    >

                                        {{ $parentCategory->name }}

                                    </a>

                                    {{-- Child Categories --}}
                                    @if($parentCategory->children->count())

                                        <ul class="list-unstyled ps-3 mt-1">

                                            @foreach($parentCategory->children as $child)

                                                <li class="mb-1">

                                                    <a
                                                        href="{{ route('front.shop', ['cat' => $child->slug]) }}"
                                                        class="text-muted text-decoration-none"
                                                    >

                                                        {{ $child->name }}

                                                    </a>

                                                </li>

                                            @endforeach

                                        </ul>

                                    @endif

                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            @endforeach

            {{-- BRANDS --}}
            <div class="mobile-nav-group">

                <button
                    class="btn w-100 text-start fw-bold"
                    data-bs-toggle="collapse"
                    data-bs-target="#mobileBrands"
                >

                    Brands

                </button>

                <div
                    class="collapse"
                    id="mobileBrands"
                >

                    <ul class="list-unstyled ps-3">

                        @foreach($brands as $brand)

                            <li class="mb-2">

                                <a
                                    href="{{ route('front.shop', ['brand' => $brand->slug]) }}"
                                    class="text-dark text-decoration-none"
                                >

                                    {{ $brand->name }}

                                </a>

                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

            {{-- STATIC LINKS --}}
            <div class="mt-4">

                <a
                    href="{{ route('front.wholesale') }}"
                    class="d-block mb-2 text-dark text-decoration-none"
                >
                    Wholesale
                </a>

            </div>

        </div>
      </div>
    </div>

    <!-- MOBILE BOTTOM NAV -->

    <div class="mobile-bottom-nav d-lg-none">
      <a href="/" class="active">
        <div>🏠</div>
        <span>Home</span>
      </a>

      <a href="{{route('front.shop')}}">
        <div>⬜</div>
        <span>Collections</span>
      </a>

      <a href="#">
        <div>🐶</div>
        <span>HUFT Hub</span>
      </a>

      <a href="{{route('front.cart')}}">
        <div>🛒</div>
        <span>Cart</span>
      </a>

      <a href="{{route('dashboard')}}">
        <div>👤</div>
        <span>Account</span>
      </a>
    </div>