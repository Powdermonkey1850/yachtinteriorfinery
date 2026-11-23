jQuery(function ($) {
   const { __ } = wp.i18n;

   const disableOptionsInit = function () {
      const container = $(this);
      // Disable some options
      const disableOptions = function () {
         container.find('.gform-settings-generic-map__row select option[value="***disabled***"]').attr('disabled', 'disabled');
      };
      const addFieldLimit = 2;
      const disableAddFieldButton = function () {
         // Add +1 to the limit for the field header
         container.find('.gform-settings-generic-map__table tbody').find('.add_field_choice').prop('disabled',
             container.find('.gform-settings-generic-map__table tbody').find('.gform-settings-generic-map__row').length >= addFieldLimit + 1
         );
      };
      const refresh = function () {
         disableOptions();
         disableAddFieldButton();
      };
      refresh();
      // The mapping table is rendered by Gravity Forms after DOM ready, so watch the whole
      // section: re-apply on any node insertion/removal (initial render, added/removed rows).
      // Only childList mutations are observed, so setting attributes above cannot re-trigger it.
      const observer = new MutationObserver(refresh);
      observer.observe(container.get(0), { childList: true, subtree: true });
   };

   $('#gform-settings-section-wpco-sib-integration-settings').each(disableOptionsInit);

   // Append Pro button
   $('#gform-settings-section-wpco-sib-integration-settings .gform-settings-panel__title, #gform-settings-section-sendinblue-add-on-settings .gform-settings-panel__title').append(
       $('<a href="https://wpconnect.co/gravity-forms-sendinblue-add-on" target="_blank" class="button primary dkgfsib-free-btn-pro"></a>').text(__('Upgrade to Pro version', 'addon-gravityforms-sendinblue-free'))
   );
});
