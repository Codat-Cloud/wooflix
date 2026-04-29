    <!-- DESKTOP HEADER -->

    <header class="main-header d-none d-lg-block fixed-top">
      <!-- TOP STRIP -->

      <div class="top-strip">
        <div class="container-xxl d-flex justify-content-between">
          <div class="top-links d-none d-lg-block">
            <a href="#">Track Order</a>
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
              <div class="dropdown location-dropdown d-inline-block">
                <button
                  class="btn location-btn dropdown-toggle"
                  data-bs-toggle="dropdown"
                >
                  📍 <span id="userPincode">560034</span>
                </button>

                <div class="dropdown-menu dropdown-menu-end location-menu" style="width: 300px;">
                  <h6 class="text-center">
                    Enter your pincode to Choose a delivery location to check
                    product availability
                  </h6>

                  <form id="pincodeForm" class="pincode-form">
                    <input
                      type="text"
                      class="form-control"
                      id="pincodeInput"
                      placeholder="Enter pincode"
                      maxlength="6"
                    />

                    <button type="submit" class="btn btn-orange">Save</button>
                  </form>
                </div>
              </div>

              <a class="text-decoration-none text-dark me-1" href="{{route('dashboard')}}" class="me-2">♡ Wishlist</a>
              <livewire:front.cart />
              {{-- <span
                class="cart-btn position-relative me-2"
                data-bs-toggle="offcanvas"
                data-bs-target="#cartDrawer"
              >
                🛒 Cart
                <span class="cart-count">12</span>
              </span> --}}

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
          <li class="has-mega">
            <a href="#">Dogs</a>

            <div class="mega-menu shadow">
              <div class="mega-inner container-xxl">
                <div class="mega-column">
                  <h6>Dog Food</h6>

                  {{-- <a href="#"
                    ><img src="assets/images/menu/dog-food.jpg" /> Dry Food</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/dog-food.jpg" /> Wet Food</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/dog-food.jpg" /> Puppy Food</a
                  > --}}
                </div>

                <div class="mega-column">
                  <h6>Dog Treats</h6>

                  {{-- <a href="#"
                    ><img src="assets/images/menu/treat.jpg" /> Soft Treats</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/treat.jpg" /> Dental Treats</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/treat.jpg" /> Training
                    Treats</a --}}
                  >
                </div>

                <div class="mega-column">
                  <h6>Dog Toys</h6>

                  {{-- <a href="#"
                    ><img src="assets/images/menu/toy.jpg" /> Chew Toys</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/toy.jpg" /> Plush Toys</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/toy.jpg" /> Interactive
                    Toys</a --}}
                  >
                </div>
              </div>
            </div>
          </li>

          <li class="has-mega">
            <a href="#">Cats</a>

            <div class="mega-menu shadow">
              <div class="mega-inner container-xxl">
                <div class="mega-column">
                  <h6>Cat Food</h6>

                  {{-- <a href="#"
                    ><img src="assets/images/menu/cat.jpg" /> Dry Food</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/cat.jpg" /> Wet Food</a
                  > --}}
                </div>

                <div class="mega-column">
                  <h6>Cat Toys</h6>

                  {{-- <a href="#"
                    ><img src="assets/images/menu/cat-toy.jpg" /> Interactive
                    Toys</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/cat-toy.jpg" /> Catnip Toys</a
                  > --}}
                </div>
              </div>
            </div>
          </li>

          <li class="has-mega">
            <a href="{{route('front.shop')}}">Brands</a>
            <div class="mega-menu shadow">
              <div class="mega-inner container-xxl">
                <div class="mega-column">
                    <h6>Popular Brands</h6>

                    @foreach($brands as $brand)
                        <a href="">
                            <img 
                                src="{{ asset('storage/' . $brand->logo) }}" 
                                alt="{{ $brand->name }}"
                            >
                            {{ $brand->name }}
                        </a>
                    @endforeach
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
          📍 Delivering to <strong>560034</strong>
          <span class="change">Change</span>
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

          <input type="text" placeholder="Search for Dog" />

          <span class="search-icon">🔍</span>
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
        <ul class="mobile-nav">
          <li>Shop for Dogs</li>
          <li>Shop for Cats</li>
          <li>Shop by Brands</li>
          <li>Winter'25 <span class="badge bg-warning text-dark">NEW</span></li>
          <li>Fresh Food for Dogs</li>
          <li>Complete Meals for Cats</li>
          <li>HUFT Outlet <span class="badge bg-danger">60% Off</span></li>
          <li>HUFT Spa</li>
          <li>HUFT Hub</li>
          <li>Store & Spa Locator</li>
          <li>Become a Franchisee</li>
          <li>Join our Birthday Club</li>
          <li>Adopt Joy</li>
        </ul>
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
        <span>Category</span>
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