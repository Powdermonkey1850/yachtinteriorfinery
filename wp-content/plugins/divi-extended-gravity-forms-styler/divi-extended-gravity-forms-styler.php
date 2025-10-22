<?php
/*
Plugin Name: Gravity Forms Styler For Divi
Plugin URI:  https://diviextended.com/product/gravity-forms-styler-for-divi/
Description: Gravity Forms Styler for Divi is an all-in-one Divi plugin to add and style your Gravity Forms in Divi effortlessly.
Version:     1.0.3
Author:      Elicus
Author URI:  https://elicus.com/
Update URI:  https://diviextended.com/
License:     GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: divi-extended-gravity-forms-styler
Domain Path: /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'ELICUS_GRAVITY_FORMS_STYLER_VERSION', '1.0.3' );
define( 'ELICUS_GRAVITY_FORMS_STYLER_PATH', plugin_dir_url( __FILE__ ) );
define( 'ELICUS_GRAVITY_FORMS_STYLER_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ELICUS_GRAVITY_FORMS_STYLER_BASENAME', plugin_basename( __FILE__ ) );

require_once plugin_dir_path( __FILE__ ) . 'includes/src/class-installation.php';
register_activation_hook( __FILE__, array( 'El_Gravity_Forms_Styler_Installation', 'el_plugin_add_installs' ) );
register_deactivation_hook( __FILE__, array( 'El_Gravity_Forms_Styler_Installation', 'el_plugin_remove_installs' ) );


if ( ! function_exists( 'el_gfs_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function el_gfs_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/GravityFormsStylerForDivi.php';
}
add_action( 'divi_extensions_init', 'el_gfs_initialize_extension' );
endif;
