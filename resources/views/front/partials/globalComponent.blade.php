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

    if (type === 'address') {
        content.innerHTML = `@livewire('front.address-manager')`;
    }

    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-orange" role="status"></div>
            <p class="mt-2 small text-muted">Loading...</p>
        </div>
    `;

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