<?php
/**
 * Plugin Name: Divi Child Preloader
 * Description: Adds a lightweight preloader and FOUC protection for Divi-based sites.
 * Author: Site Admin
 * Version: 1.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Output preloader markup right after <body>.
 */
function preloader_html() {
	if ( is_admin() || ( defined('ET_BUILDER') && ET_BUILDER ) ) return;

	echo '<div id="page-preloader"><div class="spinner"></div></div>';
}
add_action( 'wp_body_open', 'preloader_html', 1 );

/**
 * Enqueue preloader CSS and JS from /css and /js folders.
 */
function preloader_enqueue_assets() {
	if ( is_admin() || ( defined('ET_BUILDER') && ET_BUILDER ) ) return;

	$plugin_dir = plugin_dir_path( __FILE__ );
	$plugin_url = plugin_dir_url( __FILE__ );

	$js_file  = $plugin_dir . 'js/preloader.js';
	$css_file = $plugin_dir . 'css/preloader.css';

	$version = max(
		file_exists($js_file)  ? filemtime($js_file)  : 0,
		file_exists($css_file) ? filemtime($css_file) : 0
	);

	// Enqueue CSS
	if ( file_exists( $css_file ) ) {
		wp_enqueue_style(
			'divi-preloader',
			$plugin_url . 'css/preloader.css',
			[],
			$version
		);
	}

	// Enqueue JS
	if ( file_exists( $js_file ) ) {
		wp_enqueue_script(
			'divi-preloader',
			$plugin_url . 'js/preloader.js',
			[],
			$version,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'preloader_enqueue_assets', 5 );

/**
 * Preload Divi stylesheet early to reduce FOUC.
 */
function preloader_preload_divi_css() {
	echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/style.css' ) . '" as="style" onload="this.rel=\'stylesheet\'">';
	echo '<noscript><link rel="stylesheet" href="' . esc_url( get_template_directory_uri() . '/style.css' ) . '"></noscript>';
}
add_action( 'wp_head', 'preloader_preload_divi_css', 5 );

/**
 * Fallback for wp_body_open (older themes).
 */
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

