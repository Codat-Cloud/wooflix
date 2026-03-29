const cache = {};

// console.log('JS working');

// preload other categories
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".tab-pane[data-endpoint]").forEach((tab) => {
        const url = tab.dataset.endpoint;

        fetch(url)
            .then((r) => r.text())
            .then((html) => {
                cache[url] = html;
            });
    });
});

// when tab is opened
document.querySelectorAll('[data-bs-toggle="tab"]').forEach((btn) => {
    btn.addEventListener("shown.bs.tab", (e) => {
        const target = document.querySelector(e.target.dataset.bsTarget);
        const url = target.dataset.endpoint;

        if (url && !target.dataset.loaded) {
            target.innerHTML = cache[url] || "Loading...";
            target.dataset.loaded = true;
        }
    });
});

// arrow scroll
document.querySelectorAll(".deals-wrapper").forEach((wrapper) => {
    const scroll = wrapper.querySelector(".deals-scroll");
    const left = wrapper.querySelector(".deals-arrow.left");
    const right = wrapper.querySelector(".deals-arrow.right");

    if (left)
        left.onclick = () =>
            scroll.scrollBy({ left: -300, behavior: "smooth" });
    if (right)
        right.onclick = () =>
            scroll.scrollBy({ left: 300, behavior: "smooth" });
});

document.addEventListener("click", function (e) {
    if (e.target.classList.contains("qty-plus")) {
        let input = e.target.parentElement.querySelector("input");
        input.value = parseInt(input.value) + 1;
    }

    if (e.target.classList.contains("qty-minus")) {
        let input = e.target.parentElement.querySelector("input");
        if (input.value > 1) input.value = parseInt(input.value) - 1;
    }

    if (e.target.classList.contains("remove-item")) {
        e.target.closest(".cart-item").remove();
    }
});

document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-icon")) {
        e.target.closest(".cart-item").remove();

        checkEmptyCart();
    }
});

function checkEmptyCart() {
    const cart = document.querySelector(".cart-items");

    if (cart.children.length === 0) {
        cart.innerHTML = `
<div class="empty-cart">
<img src="assets/images/empty-cart.png">
<h5>Your cart is empty</h5>
<p>Looks like you haven't added anything yet.</p>
<a href="/" class="start-shopping">Start Shopping</a>
</div>
`;
    }
}

document.querySelectorAll(".filter-toggle").forEach((btn) => {
    btn.addEventListener("click", () => {
        const group = btn.parentElement;

        group.classList.toggle("collapsed");
    });
});

/* VARIANT SELECTION */

document.querySelectorAll(".variant-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
        document
            .querySelectorAll(".variant-btn")
            .forEach((b) => b.classList.remove("active"));

        this.classList.add("active");
    });
});

/* WISHLIST */

const wishlist = document.getElementById("wishlistBtn");

if (wishlist) {
    wishlist.addEventListener("click", function () {
        this.classList.toggle("active");

        this.innerHTML = this.classList.contains("active") ? "❤" : "♡";
    });
}

const images = [
    "https://images.unsplash.com/photo-1598137265627-2d2d37a35d58?w=900",
    "https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd?w=900",
    "https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=900",
    "https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=900",
];

let currentImage = 0;

function setImage(index) {
    const img = document.getElementById("mainImage");

    img.classList.add("fade");

    setTimeout(() => {
        img.src = images[index];

        img.classList.remove("fade");
    }, 200);

    currentImage = index;

    document
        .querySelectorAll(".thumb")
        .forEach((t) => t.classList.remove("active"));

    document.querySelectorAll(".thumb")[index].classList.add("active");
}

function changeImage(step) {
    currentImage += step;

    if (currentImage < 0) currentImage = images.length - 1;

    if (currentImage >= images.length) currentImage = 0;

    setImage(currentImage);
}

let touchStartX = 0;
let touchEndX = 0;

const gallery = document.querySelector(".gallery-main");

gallery.addEventListener("touchstart", function (e) {
    touchStartX = e.changedTouches[0].screenX;
});

gallery.addEventListener("touchend", function (e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    let swipeDistance = touchEndX - touchStartX;

    /* swipe threshold */
    if (Math.abs(swipeDistance) < 50) return;

    /* swipe left → next image */
    if (swipeDistance < 0) {
        changeImage(1);
    }

    /* swipe right → previous image */
    if (swipeDistance > 0) {
        changeImage(-1);
    }
}

document.querySelectorAll(".coupon-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        const code = btn.dataset.code;

        navigator.clipboard.writeText(code);

        btn.classList.add("copied");

        btn.querySelector(".btn-text").textContent = "Copied";

        setTimeout(() => {
            btn.classList.remove("copied");
            btn.querySelector(".btn-text").textContent = "Copy";
        }, 2000);
    });
});

const reviewImages = [
    "https://images.unsplash.com/photo-1601758124510-52d02ddb7cbd",
    "https://images.unsplash.com/photo-1598137265627-2d2d37a35d58",
    "https://images.unsplash.com/photo-1601758228041-f3b2795255f1",
];

let reviewImageIndex = 0;

function openReviewGallery(index) {
    reviewImageIndex = index;

    document.getElementById("reviewGalleryImage").src = reviewImages[index];

    new bootstrap.Modal(document.getElementById("reviewGalleryModal")).show();
}

function changeReviewImage(step) {
    reviewImageIndex += step;

    if (reviewImageIndex < 0) reviewImageIndex = reviewImages.length - 1;
    if (reviewImageIndex >= reviewImages.length) reviewImageIndex = 0;

    document.getElementById("reviewGalleryImage").src =
        reviewImages[reviewImageIndex];
}

/* limit images */

document.getElementById("reviewImages").addEventListener("change", function () {
    if (this.files.length > 5) {
        alert("You can upload maximum 5 images");

        this.value = "";
    }
});

/* SAVE TAB STATE */

document.querySelectorAll(".review-tabs .nav-link").forEach((tab) => {
    tab.addEventListener("click", function () {
        localStorage.setItem("activeReviewTab", this.dataset.bsTarget);
    });
});

/* RESTORE TAB */

document.addEventListener("DOMContentLoaded", function () {
    const activeTab = localStorage.getItem("activeReviewTab");

    if (activeTab) {
        const tabTrigger = document.querySelector(
            '[data-bs-target="' + activeTab + '"]',
        );

        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
        }
    }
});

let reviewTouchStartX = 0;
let reviewTouchEndX = 0;

const reviewModal = document.querySelector("#reviewGalleryImage");

reviewModal.addEventListener("touchstart", function (e) {
    reviewTouchStartX = e.changedTouches[0].screenX;
});

reviewModal.addEventListener("touchend", function (e) {
    reviewTouchEndX = e.changedTouches[0].screenX;
    handleReviewSwipe();
});

function handleReviewSwipe() {
    let distance = reviewTouchEndX - reviewTouchStartX;

    if (Math.abs(distance) < 50) return;

    /* swipe left → next */
    if (distance < 0) {
        changeReviewImage(1);
    }

    /* swipe right → previous */
    if (distance > 0) {
        changeReviewImage(-1);
    }
}

document.querySelectorAll(".address-card").forEach((card) => {
    card.addEventListener("click", function () {
        document.querySelectorAll(".address-card").forEach((el) => {
            el.classList.remove("active");
        });

        this.classList.add("active");
    });
});

// Offcanvas for Profile
window.openDrawer = function (type) {
    console.log(type);

    const drawer = new bootstrap.Offcanvas(
        document.getElementById("profileDrawer"),
    );
    const title = document.getElementById("drawerTitle");
    const content = document.getElementById("drawerContent");

    /* LOADING STATE */
    content.innerHTML = "<div class='text-center py-5'>Loading...</div>";

    let url = "";

    if (type === "address") {
        title.innerText = "My Addresses";
        url = "/address.html";
    }

    if (type === "refunds") {
        title.innerText = "Payments & Refunds";
        url = "/refunds.html";
    }

    if (type === "coupons") {
        title.innerText = "Coupons";
        url = "/coupons.html";
    }

    fetch(url)
        .then((res) => res.text())
        .then((html) => {
            content.innerHTML = html;

            /* RE-INIT COLLAPSE (IMPORTANT) */
            content.querySelectorAll(".collapse").forEach((el) => {
                new bootstrap.Collapse(el, { toggle: false });
            });
        });

    drawer.show();
};
