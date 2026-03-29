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
                <img src="{{ asset('/images/logo.png')}}" alt="" class="w-100" />
              </div>
            </div>

            <div class="col-lg-5">
              <input
                type="text"
                class="form-control search-box"
                placeholder="Search"
              />
            </div>

            <div class="col-lg-5 text-end header-icons">
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

              <span class="me-2">♡ Wishlist</span>
              <span
                class="cart-btn position-relative me-2"
                data-bs-toggle="offcanvas"
                data-bs-target="#cartDrawer"
              >
                🛒 Cart
                <span class="cart-count">12</span>
              </span>

              <button class="btn btn-orange">Login/Sign Up</button>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Offcanvas Cart -->
    <div
      class="offcanvas offcanvas-end cart-drawer"
      tabindex="-1"
      id="cartDrawer"
    >
      <div class="offcanvas-header">
        <h5>Your Cart</h5>

        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
        ></button>
      </div>

      <div class="offcanvas-body d-flex flex-column">
        <div class="cart-items" id="cartItems">
          <!-- CART ITEM -->

          <div class="cart-item" data-price="174.30">
            <button class="remove-icon">✕</button>

            <img src="{{ asset('/images/toy1.jpg')}}" class="cart-image" />

            <div class="cart-details">
              <h6 class="cart-title">HUFT Lady Buggs Plush Toy</h6>

              <div class="cart-bottom">
                <div class="qty-control">
                  <button class="qty-minus">−</button>

                  <input class="qty-input" value="1" readonly />

                  <button class="qty-plus">+</button>
                </div>

                <div class="item-price">₹174.30</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty Cart State -->
        <div class="empty-cart">
          <img src="{{ asset('/images/empty-cart.png')}}" />

          <h5>Your cart is empty</h5>

          <p>Looks like you haven't added anything yet.</p>

          <a href="/" class="start-shopping"> Start Shopping </a>
        </div>

        <!-- ORDER SUMMARY -->

        <div class="cart-summary">
          <div class="summary-row">
            <span>Subtotal</span>
            <span id="cartSubtotal">₹174.30</span>
          </div>

          <div class="summary-row">
            <span>Shipping</span>
            <span id="cartShipping">Free</span>
          </div>

          <div class="summary-row total">
            <span>Total</span>
            <span id="cartTotal">₹174.30</span>
          </div>

          <button class="checkout-btn">Proceed to Checkout</button>
        </div>
      </div>
    </div>

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

                  <a href="#"
                    ><img src="assets/images/menu/dog-food.jpg" /> Dry Food</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/dog-food.jpg" /> Wet Food</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/dog-food.jpg" /> Puppy Food</a
                  >
                </div>

                <div class="mega-column">
                  <h6>Dog Treats</h6>

                  <a href="#"
                    ><img src="assets/images/menu/treat.jpg" /> Soft Treats</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/treat.jpg" /> Dental Treats</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/treat.jpg" /> Training
                    Treats</a
                  >
                </div>

                <div class="mega-column">
                  <h6>Dog Toys</h6>

                  <a href="#"
                    ><img src="assets/images/menu/toy.jpg" /> Chew Toys</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/toy.jpg" /> Plush Toys</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/toy.jpg" /> Interactive
                    Toys</a
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

                  <a href="#"
                    ><img src="assets/images/menu/cat.jpg" /> Dry Food</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/cat.jpg" /> Wet Food</a
                  >
                </div>

                <div class="mega-column">
                  <h6>Cat Toys</h6>

                  <a href="#"
                    ><img src="assets/images/menu/cat-toy.jpg" /> Interactive
                    Toys</a
                  >
                  <a href="#"
                    ><img src="assets/images/menu/cat-toy.jpg" /> Catnip Toys</a
                  >
                </div>
              </div>
            </div>
          </li>

          <li class="has-mega">
            <a href="#">Brands</a>

            <div class="mega-menu shadow">
              <div class="mega-inner container-xxl">
                <div class="mega-column">
                  <h6>Popular Brands</h6>

                  <a href="#"
                    ><img src="assets/images/brands/royal.jpg" /> Royal Canin</a
                  >
                  <a href="#"
                    ><img src="assets/images/brands/pedigree.jpg" /> Pedigree</a
                  >
                  <a href="#"
                    ><img src="assets/images/brands/meowsi.jpg" /> Meowsi</a
                  >
                </div>
              </div>
            </div>
          </li>

          <li><a href="#">Wholesale</a></li>
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

        <div class="wishlist">♡</div>
      </div>

      <div class="mobile-search">
        <div class="search-box-mobile">
          <a href="/">
            <span class="logo-icon">
              <img src="{{ asset('/images/icon.png')}}" alt="" style="width: 20px" />
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
      <a href="#" class="active">
        <div>🏠</div>
        <span>Home</span>
      </a>

      <a href="#">
        <div>⬜</div>
        <span>Category</span>
      </a>

      <a href="#">
        <div>🐶</div>
        <span>HUFT Hub</span>
      </a>

      <a href="#">
        <div>🛒</div>
        <span>Cart</span>
      </a>

      <a href="#">
        <div>👤</div>
        <span>Account</span>
      </a>
    </div>