jQuery(document).ready(function($) {
  // Open popup
  $('.open-popup').on('click', function() {
    var productName = $(this).data('product'); // get attribute value

    // Set hidden field value (adjust for your form field ID)
    var $field = $('#input_1_3');
    if ($field.length) {
      $field.val(productName);
    }

    // Optional: update heading dynamically
    $('.popup-content h2').text('Enquire about our ' + productName);

    // Add 'active' class to show popup (centered)
    $('.custom-popup').addClass('active').hide().fadeIn(200);

    // Disable body scroll while popup is open
    $('body').css('overflow', 'hidden');
  });

  // Close popup when clicking × or background
  $('.close-popup, .custom-popup').on('click', function(e) {
    if ($(e.target).is('.custom-popup, .close-popup')) {
      $('.custom-popup').fadeOut(200, function() {
        $(this).removeClass('active');
      });
      $('body').css('overflow', 'auto');
    }
  });
});

