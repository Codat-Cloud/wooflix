    <!-- USP Section -->
    <section class="usp-section">
      <div class="container-xxl">
        <div class="row text-center g-3">
          <div class="col-12 col-lg">
            <div class="usp-item">
              <!-- FREE SHIPPING -->
              <svg xmlns="http://www.w3.org/2000/svg"
                  width="32"
                  height="32"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">

                  <rect x="1" y="3" width="15" height="13"></rect>

                  <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>

                  <circle cx="5.5" cy="18.5" r="2.5"></circle>

                  <circle cx="18.5" cy="18.5" r="2.5"></circle>

              </svg>

              <h6>FREE SHIPPING</h6>

              <p>On Orders Above ₹699</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
              <!-- FREE RETURNS -->
              <svg xmlns="http://www.w3.org/2000/svg"
                  width="32"
                  height="32"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">

                  <polyline points="1 4 1 10 7 10"></polyline>

                  <path d="M3.51 15a9 9 0 1 0 .49-9"></path>

              </svg>

              <h6>FREE RETURNS</h6>

              <p>Within 7 days (T&C Apply)</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
            <!-- SECURE PAYMENT -->
            <svg xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">

                <rect
                    x="2"
                    y="5"
                    width="20"
                    height="14"
                    rx="2"
                    ry="2"
                ></rect>

                <path d="M2 10h20"></path>

                <path d="M7 15h2"></path>

                <path d="M11 15h2"></path>

                <path d="M18 7l2 2-2 2"></path>

            </svg>

              <h6>SECURE PAYMENT</h6>

              <p>Your Transaction is Secure</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
            <!-- BEST SUPPORT -->
            <svg xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">

                <path d="M18 8a6 6 0 0 0-12 0v5a2 2 0 0 0 2 2h1v-5H6"></path>

                <path d="M18 15h1a2 2 0 0 0 2-2V8a10 10 0 0 0-20 0v5a2 2 0 0 0 2 2h1"></path>

                <path d="M9 19a3 3 0 0 0 6 0"></path>

            </svg>

              <h6>BEST SUPPORT</h6>

              <p>Mon - Fri. 9 AM to 9 PM</p>
            </div>
          </div>

          <div class="col-12 col-lg">
            <div class="usp-item">
            <!-- FAST DELIVERY -->
            <svg xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">

                <path d="M5 17h14"></path>

                <path d="M5 12h10"></path>

                <path d="M5 7h6"></path>

                <path d="M19 7l-4 5h3l-1 5 4-6h-3z"></path>

            </svg>

              <h6>FAST DELIVERY</h6>

              <p>We Deliver on Time</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="container-xxl">
        <div class="row footer-top">
          <div class="col-6 col-md-3">
            <h6>SHOP FOR</h6>

            <ul>
              <li><a href="#">Dogs</a></li>
              <li><a href="#">Cats</a></li>
              <li><a href="#">Birds</a></li>
              <li><a href="#">Small Animal</a></li>
              <li><a href="#" class="highlight">Pharmacy</a></li>
              <li><a href="#">Online Vet Consult</a></li>
              <li><a href="#">Adoption</a></li>
            </ul>
          </div>

          <div class="col-6 col-md-3">
            <h6>QUICK LINKS</h6>

            <ul>
              <li><a href="#">About Us</a></li>
              <li><a href="#">Contact Us</a></li>
              <li><a href="{{route('order.track')}}">Track Your Order</a></li>
            <ul>
                @foreach($footerPages as $p)
                    <li>
                        <a href="{{ route('front.page', $p->slug) }}">
                            {{ $p->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
            </ul>
          </div>

          <div class="col-6 col-md-3">
            <h6>EXPLORE IT</h6>

            <ul>
              <li><a href="#">Careers</a></li>
              <li><a href="#">Birthday Club</a></li>
              <li><a href="#">Learn With Wooflix</a></li>
              <li><a href="#">Customers Love</a></li>
            </ul>
          </div>

          <div class="col-md-3">
            {{-- <h6>DOWNLOAD WOOFLIX APP</h6>

            <div class="app-buttons">
              <img src="assets/images/google-play.png" />

              <img src="assets/images/app-store.png" />
            </div> --}}

            <h6 class="subscribe-title mt-0">
              SUBSCRIBE FOR LATEST OFFERS AND DISCOUNTS
            </h6>

            @livewire('front.newsletter-form')
          </div>
        </div>
      </div>

      @if(!empty($settings['popular_searches']))
        <div class="popular-search">
          <div class="container-xxl">
            <strong>POPULAR SEARCHES:</strong>
              @foreach(explode(',', $settings['popular_searches']) as $keyword)
                  @php $trimmed = trim($keyword); @endphp
                  
                  @if($trimmed)
                      <a href="{{ url('/collections?q='.$trimmed) }}" class="popular-link text-decoration-none text-dark">
                          {{ $trimmed }}
                      </a>

                      {{-- Add the pipe separator only if it's NOT the last item --}}
                      @if (!$loop->last)
                          <span>|</span>
                      @endif
                  @endif
              @endforeach
          </div>
        </div>
      @endif

      <div class="footer-bottom">
        <div
          class="container-xxl d-flex justify-content-between align-items-center flex-wrap"
        >
          <p>© Copyright 2020 - 2026, VKY TECHNOLOGIES. ALL RIGHTS RESERVED.</p>

          {{-- {{ dd($socialLinks) }} --}}
            <div class="social-icons d-flex align-items-center gap-3">
              @if(!empty($socialLinks['facebook']))
                  <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener noreferrer" title="Facebook" aria-label="Facebook">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                      </svg>
                  </a>
              @endif

              @if(!empty($socialLinks['instagram']))
                  <a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener noreferrer" title="Instagram" aria-label="Instagram">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                      </svg>
                  </a>
              @endif

              @if(!empty($socialLinks['youtube']))
                  <a href="{{ $socialLinks['youtube'] }}" target="_blank" rel="noopener noreferrer" title="YouTube" aria-label="YouTube">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C21.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                      </svg>
                  </a>
              @endif

              @if(!empty($socialLinks['linkedin']))
                  <a href="{{ $socialLinks['linkedin'] }}" target="_blank" rel="noopener noreferrer" title="LinkedIn" aria-label="LinkedIn">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                      </svg>
                  </a>
              @endif

              @if(!empty($socialLinks['twitter']))
                  <a href="{{ $socialLinks['twitter'] }}" target="_blank" rel="noopener noreferrer" title="X (Twitter)" aria-label="X (Twitter)">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                      </svg>
                  </a>
              @endif

              @if(!empty($socialLinks['pinterest']))
                  <a href="{{ $socialLinks['pinterest'] }}" target="_blank" rel="noopener noreferrer" title="Pinterest" aria-label="Pinterest">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345-.09.375-.291 1.199-.332 1.365-.053.22-.175.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
                      </svg>
                  </a>
              @endif
          </div>

        </div>
      </div>

      <div class="footer-seo">
        <div class="container-xxl">
          {!! $settings['footer_about'] ?? '' !!}
        </div>
      </div>
    </footer>