@extends('layouts.front')

@section('content')

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
      <div class="container-xxl">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/">Home</a>
          </li>

          <li class="breadcrumb-item">
            <a href="/dogs">My Account</a>
          </li>
        </ol>
      </div>
    </nav>

    <!-- Profile -->
    <div class="container-xxl py-4" x-data="{ drawer: false }">
      <div class="row g-4">
        <!-- LEFT PROFILE -->

        <div class="col-lg-4">
          <div class="profile-card">
            <button class="edit-profile-btn"
                data-bs-toggle="offcanvas" 
                data-bs-target="#profileDrawer"
                onclick="setDrawer('profile')"
            >✎</button>

            <div class="profile-top">
              <div class="avatar">{{auth()->user()->name}}</div>
              <div>
                <h5>{{auth()->user()->name}}</h5>
                <p class="text-muted mb-0">{{auth()->user()->email}}</p>
              </div>
            </div>

            <div class="profile-actions-grid">
              <a
                href="#"
                class="profile-action"
                data-bs-toggle="offcanvas" 
                data-bs-target="#profileDrawer"
                onclick="setDrawer('address')"
              >
                <span>My Addresses</span>
                <span class="arrow">›</span>
              </a>

              <a href="#" class="profile-action"
                data-bs-toggle="offcanvas" 
                data-bs-target="#profileDrawer"
                onclick="setDrawer('refunds')"
              >
                <span>Payments & Refunds</span>
                <span class="arrow">›</span>
              </a>

              <a href="#" class="profile-action"
                data-bs-toggle="offcanvas" 
                data-bs-target="#profileDrawer"
                onclick="setDrawer('coupons')"
              >
                <span>Coupons</span>
                <span class="arrow">›</span>
              </a>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="profile-action logout w-100 text-start">
                    Logout
                </button>
            </form>
            </div>
          </div>
        </div>

        <!-- RIGHT CONTENT -->

        <div class="col-lg-8">
          <!-- ORDERS -->

          <div class="profile-section">
            <div class="section-header">
                <h6>Recent Orders</h6>
                <a href="#">View All</a>
            </div>

            <div class="order-list">

                @forelse($orders as $order)

                    @php
                        $item = $order->items->first(); // show first item
                        $product = $item?->product;
                    @endphp

                    @if($item && $product)

                        <div class="order-card">

                            <img 
                                src="{{ asset('storage/' . $product->main_image) }}"
                                alt="{{ $product->name }}"
                            />

                            <div class="order-info">
                                <h6>{{ $product->name }}</h6>

                                <p class="text-muted">
                                    {{ ucfirst($order->status) }} 
                                    on {{ $order->created_at->format('M d') }}
                                </p>
                            </div>

                            <a 
                                href="{{ route('front.singleProduct', $product->slug) }}"
                                class="btn btn-sm btn-orange"
                            >
                                Buy Again
                            </a>

                        </div>

                    @endif

                @empty

                    <div class="text-muted text-center py-4">
                        No orders yet
                    </div>

                @endforelse

            </div>
          </div>

          <!-- WISHLIST -->

          <div class="profile-section">
            <div class="section-header">
              <h6>Wishlist</h6>
              <a href="#">View All</a>
            </div>

            <div class="row g-3">
              <div class="col-md-4 col-6">
                <div class="product-card">
                  <img
                    src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?q=80&w=200"
                  />
                  <p>Dog Chew Toy</p>
                </div>
              </div>
            </div>
          </div>

          <!-- REVIEWS -->

          <div class="profile-section">
            <div class="section-header">
              <h6>Your Reviews</h6>
              <a href="#">View All</a>
            </div>

            <div class="review-card">
              <div class="d-flex justify-content-between">
                <strong>Dog Toy</strong>
                <span class="rating">⭐ 5.0</span>
              </div>

              <p class="text-muted small">
                Very good quality product. My dog loves it.
              </p>
            </div>
          </div>

          <!-- SUPPORT -->

          <div class="text-center mt-4">
            <a href="#" class="support-link">
              Need Help? Contact Customer Support
            </a>
          </div>
        </div>
      </div>
    </div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="profileDrawer" data-bs-backdrop="static" data-bs-keyboard="false">

    <!-- HEADER -->
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="drawerTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <!-- BODY -->
    <div class="offcanvas-body" id="drawerContent">
        <!-- content will be injected here -->
    </div>

</div>
    

@push('scripts')
    
<script>
function setDrawer(type) {

    const title = document.getElementById('drawerTitle');
    const content = document.getElementById('drawerContent');

    content.innerHTML = "<div class='text-center py-5'>Loading...</div>";

    let titles = {
        profile: "Edit Profile",
        address: "My Addresses",
        coupons: "Coupons"
    };

    document.getElementById('drawerTitle').innerText = titles[type] || '';

    fetch(`/profile/drawer/${type}`, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(res => res.text())
    .then(html => {
        content.innerHTML = html;
    })
    .catch(() => {
        content.innerHTML = "<div class='text-danger'>Failed to load</div>";
    });
}
</script>

@endpush

@endsection