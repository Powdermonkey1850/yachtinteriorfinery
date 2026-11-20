<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_dex_sdk_this_version = '1.0.3';

if (
	defined( 'DEX_UPDATER_SDK_VERSION' ) &&
	version_compare( DEX_UPDATER_SDK_VERSION, $_dex_sdk_this_version, '>=' )
) {
	return;
}

define( 'DEX_UPDATER_SDK_VERSION', $_dex_sdk_this_version );
define( 'DEX_UPDATER_SDK_DIR',     plugin_dir_path( __FILE__ ) );
define( 'DEX_UPDATER_SDK_URL',     plugin_dir_url( __FILE__ ) );

if ( ! defined( 'DEX_ADMIN_MENU_SLUG' ) ) {
	define( 'DEX_ADMIN_MENU_SLUG', 'divi-extended' );
}

unset( $_dex_sdk_this_version );

require_once DEX_UPDATER_SDK_DIR . 'src/class-dex-license-storage.php';
require_once DEX_UPDATER_SDK_DIR . 'src/class-dex-environment.php';
require_once DEX_UPDATER_SDK_DIR . 'src/class-dex-api-client.php';
require_once DEX_UPDATER_SDK_DIR . 'src/class-dex-response-parser.php';
require_once DEX_UPDATER_SDK_DIR . 'src/class-dex-update-checker.php';

require_once DEX_UPDATER_SDK_DIR . 'src/admin/class-dex-settings-widget.php';
require_once DEX_UPDATER_SDK_DIR . 'src/admin/class-dex-notices.php';

require_once DEX_UPDATER_SDK_DIR . 'src/class-dex-updater.php';