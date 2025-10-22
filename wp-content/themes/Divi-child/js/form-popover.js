jQuery(function ($) {
  console.log("✅ Popup (reload version, centered) initialized");


$(".open-popup").on("click", function () {
  const product = $(this).data("product") || "your product";
  console.log("🧩 Popup requested for:", product);

  // ✅ Use .html() to allow <br/> rendering
  $(".popup-content h2").html("Enquire about<br/>our " + product);

  // Pass product to hidden field
  $("#input_2_4").val(product);

  // Show popup
  $(".custom-popup")
    .css({ display: "flex", opacity: 0 })
    .addClass("active")
    .animate({ opacity: 1 }, 200);

  $("body").addClass("no-scroll");
});



  // === CLOSE POPUP ===
  $(".close-popup, .custom-popup").on("click", function (e) {
    if ($(e.target).is(".custom-popup, .close-popup")) {
      $(".custom-popup")
        .animate({ opacity: 0 }, 200, function () {
          $(this).removeClass("active").css("display", "none");
        });
      $("body").removeClass("no-scroll");
      console.log("❎ Popup closed");
    }
  });

  // === ON FORM SUBMIT SUCCESS (using Gravity Forms AJAX) ===
  $(document).on("gform_confirmation_loaded", function (event, formId) {
    if (formId === 2) {
      console.log("✅ Form submitted successfully, reloading...");
      setTimeout(() => {
        location.reload();
      }, 1200); // small delay for confirmation visibility
    }
  });
});

