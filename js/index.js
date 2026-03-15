const cache = {};

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
    left.onclick = () => scroll.scrollBy({ left: -300, behavior: "smooth" });
  if (right)
    right.onclick = () => scroll.scrollBy({ left: 300, behavior: "smooth" });
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