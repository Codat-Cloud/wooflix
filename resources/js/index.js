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

function initReviewSwipe() {

    const el = document.querySelector("#reviewGalleryImage");
    if (!el) return;

    el.addEventListener("touchstart", e => {
        reviewTouchStartX = e.changedTouches[0].screenX;
    });

    el.addEventListener("touchend", e => {
        reviewTouchEndX = e.changedTouches[0].screenX;
        handleReviewSwipe();
    });
}

initReviewSwipe();

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
