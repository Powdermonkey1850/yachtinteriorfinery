document.addEventListener("DOMContentLoaded", function() {
  // Top-level toggle
  document.querySelectorAll(".top-toggle-title").forEach(function(title) {
    title.addEventListener("click", function() {
      let content = this.nextElementSibling;

      document.querySelectorAll(".top-toggle-content").forEach(function(other) {
        if (other !== content) {
          other.style.maxHeight = null;
          if (other.previousElementSibling) other.previousElementSibling.classList.remove("open");
          other.querySelectorAll(".sub-toggle-content").forEach(function(sub) {
            sub.style.maxHeight = null;
            sub.classList.remove("open");
          });
        }
      });

      if (content.style.maxHeight && content.style.maxHeight !== "0px") {
        content.style.maxHeight = null;
        this.classList.remove("open");
      } else {
        content.style.maxHeight = (content.scrollHeight + 10) + "px";
        this.classList.add("open");
      }
    });
  });

  // Sub-toggle
  document.querySelectorAll(".sub-toggle-title").forEach(function(title) {
    title.addEventListener("click", function() {
      let content = this.nextElementSibling;
      let parent = this.closest(".top-toggle-content");

      parent.querySelectorAll(".sub-toggle-content").forEach(function(other) {
        if (other !== content) {
          other.style.maxHeight = null;
          other.classList.remove("open");
        }
      });

      if (content.style.maxHeight && content.style.maxHeight !== "0px") {
        content.style.maxHeight = null;
        content.classList.remove("open");
      } else {
        content.style.maxHeight = (content.scrollHeight + 10) + "px";
        content.classList.add("open");
      }

      setTimeout(function() {
        parent.style.maxHeight = (parent.scrollHeight + 10) + "px";
      }, 300);
    });
  });
});
