<?php

class EL_GravityFormsStylerForDivi extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'divi-extended-gravity-forms-styler';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'divi-extended-gravity-forms-styler';

	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = ELICUS_GRAVITY_FORMS_STYLER_VERSION;

	/**
	 * EL_GravityFormsStylerForDivi constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'divi-extended-gravity-forms-styler', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

        $this->_builder_js_data     = array(
            'et_builder_accent_color' => esc_html( et_get_option( 'accent_color', '#7EBEC5' ) ),
            'site_url' => esc_url( site_url() ),
            'file' => et_is_builder_plugin_active() ? 'style-dbp' : 'style',
        );

		parent::__construct( $name, $args );
		
		require_once $this->plugin_dir . 'GravityFormsStylerForDiviHelper.php';

		if ( is_admin() ) {
            require_once $this->plugin_dir . 'src/class-update.php';
        }
	}
	
}

new EL_GravityFormsStylerForDivi;
