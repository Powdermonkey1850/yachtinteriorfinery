<?php
namespace DEX\UpdaterSDK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DEX\UpdaterSDK\Settings_Widget', false ) ) :

class Settings_Widget {

	private static bool $page_registered = false;

	private static array $registry = [];

	private array $config;

	private License_Storage $storage;

	private API_Client $client;

	public function __construct( array $config, License_Storage $storage, API_Client $client ) {
		$this->config  = $config;
		$this->client  = $client;
		$this->storage = $storage;
	}

	public function register(): void {
		$slug = $this->slug();

		self::$registry[ $slug ] = array(
			'config'  => $this->config,
			'client'  => $this->client,
			'storage' => $this->storage,
		);

		if ( self::$page_registered ) {
			return;
		}
		self::$page_registered = true;

		add_action( 'admin_menu',            array( $this, 'add_menu_page' ), 5 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_head',            array( $this, 'add_admin_menu_styles' ) );
		add_action( 'wp_ajax_dex_activate_license',   array( static::class, 'ajax_activate' ) );
		add_action( 'wp_ajax_dex_deactivate_license', array( static::class, 'ajax_deactivate' ) );
		add_action( 'wp_ajax_dex_rollback_version',   array( static::class, 'ajax_rollback' ) );
	}

	public function add_admin_menu_styles(): void {
		?>
		<style>
			#toplevel_page_<?php echo esc_attr( DEX_ADMIN_MENU_SLUG ); ?> .wp-menu-image {
				background-size: 17px auto !important;
				filter: grayscale(1) brightness(1.5);
				opacity: 0.7;
				transition: filter 0.2s ease, opacity 0.2s ease;
			}
			#toplevel_page_<?php echo esc_attr( DEX_ADMIN_MENU_SLUG ); ?>:hover .wp-menu-image,
			#toplevel_page_<?php echo esc_attr( DEX_ADMIN_MENU_SLUG ); ?>.wp-has-current-submenu .wp-menu-image {
				filter: none;
				opacity: 1;
			}
		</style>
		<?php
	}

	public function add_menu_page(): void {
		add_menu_page(
			__( 'Divi Extended', 'dex-updater-sdk' ),
			__( 'Divi Extended', 'dex-updater-sdk' ),
			'manage_options',
			DEX_ADMIN_MENU_SLUG,
			array( $this, 'render_page' ),
			'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDUyLjIgNjAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUyLjIgNjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj48bGluZWFyR3JhZGllbnQgaWQ9IlNWR0lEXzFfIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjI2LjEiIHkxPSIwLjIiIHgyPSIyNi4xIiB5Mj0iNTkuOCI+PHN0b3Agb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojN0U0NjlBIi8+PHN0b3Agb2Zmc2V0PSIxIiBzdHlsZT0ic3RvcC1jb2xvcjojQzk0ODlBIi8+PC9saW5lYXJHcmFkaWVudD48cGF0aCBmaWxsPSJ1cmwoI1NWR0lEXzFfKSIgZD0iTTI1LjcsNy42TDYuNiwxOC42VjQxbDE5LjUsMTFsMTkuMy0xMC45TDQ1LjMsMTFsNi43LDMuOHYyOS45TDI2LjEsNTkuOEwwLjIsNDQuN1YxNC44TDI1LjYsMC4yTDI1LjcsNy42eiBNMzkuNyw3Ljd2MzBsLTEzLjYsNy43bC0xMy42LTcuOVYyMi4xbDEzLjYtOGw2LjUsMy45VjMuNkwzOS43LDcuN3ogTTMyLjcsMjUuOWwtNi42LTMuN0wxOS41LDI2djcuNWw2LjYsMy45bDYuNi0zLjhWMjUuOXoiLz48L3N2Zz4=',
			80
		);

		add_submenu_page(
			DEX_ADMIN_MENU_SLUG,
			__( 'Divi Extended License', 'dex-updater-sdk' ),
			__( 'License', 'dex-updater-sdk' ),
			'manage_options',
			DEX_ADMIN_MENU_SLUG,
			array( $this, 'render_page' )
		);
	}

	public function enqueue_assets( string $hook ): void {
		if ( 'toplevel_page_' . DEX_ADMIN_MENU_SLUG !== $hook ) {
			return;
		}

		$base_url = DEX_UPDATER_SDK_URL . 'assets/';
		$ver      = DEX_UPDATER_SDK_VERSION;

		wp_enqueue_style(
			'dex-settings-widget',
			$base_url . 'css/settings-widget.css',
			array(),
			$ver
		);

		wp_enqueue_script(
			'dex-settings-widget',
			$base_url . 'js/settings-widget.js',
			array( 'jquery' ),
			$ver,
			true
		);

		$rollback_data = array();
		foreach ( self::$registry as $slug => $entry ) {
			$rollback_version = get_option( 'dex_rollback_version_' . $slug, '' );
			$rollback_used    = (bool) get_option( 'dex_rollback_used_' . $slug, false );

			$rollback_data[ $slug ] = array(
				'version' => $rollback_version,
				'used'    => $rollback_used,
				'label'   => $entry['config']['slug'] ?? $slug,
			);
		}

		wp_localize_script( 'dex-settings-widget', 'dexLicense', array(
			'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
			'activateAction'   => 'dex_activate_license',
			'deactivateAction' => 'dex_deactivate_license',
			'rollbackAction'   => 'dex_rollback_version',
			'nonce'            => wp_create_nonce( 'dex_license' ),
			'rollbackData'     => $rollback_data,
			'i18n'             => array(
				'activating'       => __( 'Activating…',   'dex-updater-sdk' ),
				'deactivating'     => __( 'Deactivating…', 'dex-updater-sdk' ),
				'rollingBack'      => __( 'Rolling back…', 'dex-updater-sdk' ),
				'error'            => __( 'Request failed. Please try again.', 'dex-updater-sdk' ),
				'rollbackDone'     => __( 'Rollback complete. The page will reload.', 'dex-updater-sdk' ),
				'rollbackAlready'  => __( 'You have already rolled back to the previous version. Please update to the latest version before rolling back again.', 'dex-updater-sdk' ),
				'rollbackConfirm'  => __( 'Roll back to the previous version? The current version will be replaced.', 'dex-updater-sdk' ),
			),
		) );
	}

	public function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Divi Extended — License', 'dex-updater-sdk' ) . '</h1>';
		$this->render_section();
		echo '</div>';
	}

	public function render_section(): void {
		$storage    = $this->storage;
		$raw_key    = $storage->get_key();
		$status     = $storage->get_shared_status();
		$has_key    = ! empty( $raw_key );
		$nonce      = wp_create_nonce( 'dex_license' );

		$masked_key = $has_key
			? str_repeat( '*', max( 0, strlen( $raw_key ) - 4 ) ) . substr( $raw_key, -4 )
			: '';

		$products = array();
		foreach ( self::$registry as $slug => $entry ) {
			$rollback_version = get_option( 'dex_rollback_version_' . $slug, '' );
			$rollback_used    = (bool) get_option( 'dex_rollback_used_' . $slug, false );

			$products[ $slug ] = array(
				'name'             => $entry['config']['name'] ?? ucwords( str_replace( '-', ' ', $slug ) ),
				'version'          => $entry['config']['version'] ?? '',
				'rollback_version' => $rollback_version,
				'rollback_used'    => $rollback_used,
			);
		}

		include DEX_UPDATER_SDK_DIR . 'templates/settings-widget.php';
	}

	public static function ajax_activate(): void {
		check_ajax_referer( 'dex_license' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'dex-updater-sdk' ) ) );
		}

		$raw_key = sanitize_text_field( wp_unslash( $_POST['license_key'] ?? '' ) );

		if ( empty( $raw_key ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter a license key.', 'dex-updater-sdk' ) ) );
		}

		$first_response = null;
		$valid_status   = null;

		foreach ( self::$registry as $slug => $entry ) {
			$response = $entry['client']->activate( $raw_key );
			$status   = Response_Parser::to_license_status( $response );

			$entry['storage']->set_status( $status );

			delete_site_transient( 'dex_update_' . sanitize_key( $slug ) );
			delete_site_transient( 'dex_auto_act_tried_' . sanitize_key( $slug ) );

			if ( null === $first_response ) {
				$first_response = $status;
			}

			if ( $status['valid'] ) {
				if ( null === $valid_status ) {
					$valid_status = $status;
				} else {
					$current_priority = self::get_license_priority( $valid_status['license_type'] ?? null );
					$new_priority     = self::get_license_priority( $status['license_type'] ?? null );
					if ( $new_priority > $current_priority ) {
						$valid_status = $status;
					}
				}
			}
		}

		if ( null === $valid_status ) {
			$message = ( $first_response['message'] ?? '' ) ?: __( 'License key is not valid for any installed Divi Extended product.', 'dex-updater-sdk' );
			wp_send_json_error( array( 'message' => $message ) );
		}

		$first_entry = reset( self::$registry );
		$first_entry['storage']->set_key( $raw_key );
		$first_entry['storage']->set_shared_status( $valid_status );

		wp_send_json_success( $valid_status );
	}

	public static function ajax_deactivate(): void {
		check_ajax_referer( 'dex_license' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'dex-updater-sdk' ) ) );
		}

		foreach ( self::$registry as $slug => $entry ) {
			$raw_key = $entry['storage']->get_key();

			if ( ! empty( $raw_key ) ) {
				$entry['client']->deactivate( $raw_key );
			}

			$entry['storage']->delete_status();
			delete_site_transient( 'dex_update_' . sanitize_key( $slug ) );
		}

		$first_entry = reset( self::$registry );
		if ( $first_entry ) {
			$first_entry['storage']->delete_key();
		}

		wp_send_json_success( array( 'message' => __( 'License deactivated.', 'dex-updater-sdk' ) ) );
	}

	public static function ajax_rollback(): void {
		check_ajax_referer( 'dex_license' );

		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'dex-updater-sdk' ) ) );
		}

		$slug = sanitize_key( wp_unslash( $_POST['slug'] ?? '' ) );

		if ( empty( $slug ) || ! isset( self::$registry[ $slug ] ) ) {
			wp_send_json_error( array( 'message' => __( 'Product not found.', 'dex-updater-sdk' ) ) );
		}

		$rollback_version = get_option( 'dex_rollback_version_' . $slug, '' );

		if ( empty( $rollback_version ) ) {
			wp_send_json_error( array( 'message' => __( 'No previous version available for rollback.', 'dex-updater-sdk' ) ) );
		}

		if ( get_option( 'dex_rollback_used_' . $slug ) ) {
			wp_send_json_error( array( 'message' => __( 'You have already rolled back to the previous version. Please update to the latest version before rolling back again.', 'dex-updater-sdk' ) ) );
		}

		$entry       = self::$registry[ $slug ];
		$config      = $entry['config'];
		$storage     = $entry['storage'];
		$client      = $entry['client'];
		$license_key = $storage->get_key();

		if ( empty( $license_key ) ) {
			wp_send_json_error( array( 'message' => __( 'No license key found. Please activate your license before rolling back.', 'dex-updater-sdk' ) ) );
		}

		$site_url     = Environment::site_url();
		$rollback_url = $client->build_rollback_url( $license_key, $rollback_version, $site_url );
		$plugin_file  = ! empty( $config['file'] )
			? plugin_basename( $config['file'] )
			: $slug . '/' . $slug . '.php';

		set_transient( 'dex_rollback_initiating_' . $slug, 1, 60 );

		$update_transient = get_site_transient( 'update_plugins' );
		if ( ! is_object( $update_transient ) ) {
			$update_transient           = new \stdClass();
			$update_transient->response = array();
			$update_transient->checked  = array();
		}
		if ( ! isset( $update_transient->response ) ) {
			$update_transient->response = array();
		}

		$update_transient->response[ $plugin_file ] = (object) array(
			'id'          => 'dex/' . $slug,
			'slug'        => $slug,
			'plugin'      => $plugin_file,
			'new_version' => $rollback_version,
			'url'         => '',
			'package'     => $rollback_url,
		);
		set_site_transient( 'update_plugins', $update_transient );

		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		$is_active = false;
		if ( function_exists( 'is_plugin_active' ) ) {
			$is_active = is_plugin_active( $plugin_file );
		} else {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			$is_active = is_plugin_active( $plugin_file );
		}

		$upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );
		$result   = $upgrader->upgrade( $plugin_file );

		if ( $is_active && ! is_wp_error( $result ) && false !== $result && null !== $result ) {
			if ( ! function_exists( 'activate_plugin' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			activate_plugin( $plugin_file, '', false, true );
		}

		$update_transient = get_site_transient( 'update_plugins' );
		if ( is_object( $update_transient ) && isset( $update_transient->response[ $plugin_file ] ) ) {
			unset( $update_transient->response[ $plugin_file ] );
			set_site_transient( 'update_plugins', $update_transient );
		}

		delete_transient( 'dex_rollback_initiating_' . $slug );
		delete_site_transient( 'dex_update_' . $slug );
		delete_site_transient( 'update_plugins' );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		if ( false === $result || null === $result ) {
			wp_send_json_error( array( 'message' => __( 'Rollback failed. The package may not be available on the server or your license may have expired.', 'dex-updater-sdk' ) ) );
		}

		update_option( 'dex_rollback_used_' . $slug, true, false );

		wp_send_json_success( array(
			'message' => sprintf(

				__( 'Successfully rolled back to version %s. The page will reload to complete the process.', 'dex-updater-sdk' ),
				$rollback_version
			),
			'version' => $rollback_version,
		) );
	}

	private function slug(): string {
		return sanitize_key( $this->config['slug'] );
	}

	private static function get_license_priority( ?string $type ): int {
		switch ( $type ) {
			case 'lifetime':    return 5;
			case 'agency':      return 4;
			case 'extended':    return 3;
			case 'annual':      return 2;
			case 'single_site': return 1;
			default:            return 0;
		}
	}
}

endif;