document.addEventListener("DOMContentLoaded", function () {
  const preloader = document.getElementById("page-preloader");
  if (!preloader) return;

  window.addEventListener("load", function () {
    setTimeout(() => {
      document.body.classList.add("loaded");
    }, 300);
  });
});

