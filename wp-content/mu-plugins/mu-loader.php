<?php


/**
 * Plugin Name: MU Plugin Loader
 * Description: Automatically loads all MU plugins located in subdirectories under /mu-plugins/.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

error_log('🔥 mu-loader.php is running'); // <— debug line

function mu_plugins_loader() {
	$mu_dir = WPMU_PLUGIN_DIR;
	error_log('Scanning MU plugins directory: ' . $mu_dir);

	foreach ( glob( $mu_dir . '/*', GLOB_ONLYDIR ) as $subdir ) {
		$main_file = $subdir . '/' . basename( $subdir ) . '.php';
		error_log('Checking: ' . $main_file);

		if ( file_exists( $main_file ) ) {
			error_log('✅ Loading MU plugin: ' . $main_file);
			require_once $main_file;
		} else {
			error_log('❌ No main file found in: ' . $subdir);
		}
	}
}
mu_plugins_loader();

