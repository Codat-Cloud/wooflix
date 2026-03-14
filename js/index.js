const cache = {};

// preload other categories
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".tab-pane[data-endpoint]").forEach(tab => {
    const url = tab.dataset.endpoint;

    fetch(url)
      .then(r => r.text())
      .then(html => {
        cache[url] = html;
      });
  });
});

// when tab is opened
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(btn=>{
  btn.addEventListener("shown.bs.tab", e=>{
    const target = document.querySelector(e.target.dataset.bsTarget);
    const url = target.dataset.endpoint;

    if(url && !target.dataset.loaded){
      target.innerHTML = cache[url] || "Loading...";
      target.dataset.loaded = true;
    }
  });
});

// arrow scroll
document.querySelectorAll(".deals-wrapper").forEach(wrapper=>{
  const scroll = wrapper.querySelector(".deals-scroll");
  const left = wrapper.querySelector(".deals-arrow.left");
  const right = wrapper.querySelector(".deals-arrow.right");

  if(left) left.onclick = () => scroll.scrollBy({left:-300,behavior:"smooth"});
  if(right) right.onclick = () => scroll.scrollBy({left:300,behavior:"smooth"});
});