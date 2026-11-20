<?php
namespace DEX\UpdaterSDK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DEX\UpdaterSDK\Update_Checker', false ) ) :

class Update_Checker {

	const CACHE_TTL = 43200;

	private static array $registered_slugs = [];

	private array $config;

	private License_Storage $storage;

	private API_Client $client;

	public function __construct( array $config, License_Storage $storage, API_Client $client ) {
		$this->config  = $config;
		$this->storage = $storage;
		$this->client  = $client;
	}

	public function register(): void {
		$slug = sanitize_key( $this->config['slug'] );

		if ( isset( self::$registered_slugs[ $slug ] ) ) {
			return;
		}
		self::$registered_slugs[ $slug ] = true;

		if ( 'theme' === $this->config['type'] ) {
			add_filter( 'pre_set_site_transient_update_themes',      array( $this, 'check_for_update' ) );
			add_action( 'delete_site_transient_update_themes',       array( $this, 'clear_dex_cache' ) );
		} else {
			add_filter( 'pre_set_site_transient_update_plugins',     array( $this, 'check_for_update' ) );
			add_filter( 'plugins_api',                               array( $this, 'plugin_info' ), 20, 3 );
			add_action( 'delete_site_transient_update_plugins',      array( $this, 'clear_dex_cache' ) );
			add_action( 'in_plugin_update_message-' . $this->plugin_file(), array( $this, 'update_message' ), 10, 2 );
		}

		add_filter( 'upgrader_pre_install', array( $this, 'capture_rollback_version' ), 10, 2 );

		add_action( 'upgrader_process_complete', array( $this, 'maybe_clear_cache_after_upgrade' ), 10, 2 );
	}

	public function check_for_update( object $transient ): object {

		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$license_key = $this->storage->get_key();
		$update_info = $this->fetch_update_info( $license_key );

		if ( is_wp_error( $update_info ) || empty( $update_info ) || ! empty( $update_info['no_update'] ) ) {
			if ( 'theme' === $this->config['type'] ) {
				$slug = $this->config['slug'];
				if ( isset( $transient->response[ $slug ] ) ) {
					unset( $transient->response[ $slug ] );
				}

				$transient->no_update[ $slug ] = array(
					'theme'       => $slug,
					'new_version' => $this->config['version'],
					'url'         => '',
					'package'     => '',
				);
			} else {
				$plugin_file = $this->plugin_file();
				if ( isset( $transient->response[ $plugin_file ] ) ) {
					unset( $transient->response[ $plugin_file ] );
				}
				$transient->no_update[ $plugin_file ] = (object) array(
					'id'            => $plugin_file,
					'slug'          => $this->config['slug'],
					'plugin'        => $plugin_file,
					'new_version'   => $this->config['version'],
					'url'           => '',
					'package'       => '',
				);
			}
			return $transient;
		}

		if ( 'theme' === $this->config['type'] ) {
			$theme_update = Response_Parser::to_theme_update( $update_info );
			if ( $theme_update && version_compare( $theme_update['new_version'], $this->config['version'], '>' ) ) {
				$transient->response[ $this->config['slug'] ] = $theme_update;

				if ( isset( $transient->no_update[ $this->config['slug'] ] ) ) {
					unset( $transient->no_update[ $this->config['slug'] ] );
				}
			}
		} else {
			$plugin_file   = $this->plugin_file();
			$plugin_update = Response_Parser::to_plugin_update( $update_info, $plugin_file );
			if ( $plugin_update && version_compare( $plugin_update->new_version, $this->config['version'], '>' ) ) {
				$transient->response[ $plugin_file ] = $plugin_update;

				if ( isset( $transient->no_update[ $plugin_file ] ) ) {
					unset( $transient->no_update[ $plugin_file ] );
				}
			}
		}

		return $transient;
	}

	public function plugin_info( $result, string $action, object $args ) {
		if ( 'plugin_information' !== $action ) {
			return $result;
		}

		if ( empty( $args->slug ) || $args->slug !== $this->config['slug'] ) {
			return $result;
		}

		$license_key = $this->storage->get_key();
		$update_info = $this->fetch_update_info( $license_key );

		if ( is_wp_error( $update_info ) || empty( $update_info['new_version'] ) ) {
			return $result;
		}

		return Response_Parser::to_plugin_info( $update_info );
	}

	public function update_message( array $plugin_data, object $response ): void {
		if ( empty( $response->package ) && isset( $response->license_valid ) && ! $response->license_valid ) {
			$message = ! empty( $response->license_message )
				? $response->license_message
				: __( 'To enable automatic updates, please activate your license.', 'dex-updater-sdk' );

			echo ' <p style="color: #d63638;">' . esc_html( $message ) . '</ps>';
		}
	}

	public function capture_rollback_version( $result, array $hook_extra ) {
		if ( ! $this->is_our_upgrade( $hook_extra ) ) {
			return $result;
		}

		$update_info = $this->fetch_update_info( $this->storage->get_key() );
		if ( ! is_wp_error( $update_info ) && empty( $update_info['package'] ) && ! empty( $update_info['license_message'] ) ) {
			return new \WP_Error( 'dex_license_error', $update_info['license_message'] );
		}

		$slug = sanitize_key( $this->config['slug'] );

		$initiating_key = 'dex_rollback_initiating_' . $slug;

		if ( get_transient( $initiating_key ) ) {
			delete_transient( $initiating_key );
			return $result;
		}

		update_option( 'dex_rollback_version_' . $slug, $this->config['version'], false );
		delete_option( 'dex_rollback_used_' . $slug );

		return $result;
	}

	public function clear_dex_cache(): void {
		delete_site_transient( $this->cache_key() );
	}

	public function maybe_clear_cache_after_upgrade( $upgrader, array $hook_extra ): void {
		if ( $this->is_our_upgrade( $hook_extra ) ) {
			if ( ! is_wp_error( $upgrader->result ) && ! empty( $upgrader->result ) ) {
				set_site_transient(
					$this->cache_key(),
					array(
						'no_update'    => true,
						'_dex_version' => $this->config['version'],
					),
					60
				);
			} else {
				$this->clear_dex_cache();
			}
		}
	}

	private function fetch_update_info( string $license_key ) {
		$cache_key       = $this->cache_key();
		$current_version = (string) $this->config['version'];
		$cached          = get_site_transient( $cache_key );

		if ( false !== $cached ) {
			$cached_for = (string) ( $cached['_dex_version'] ?? '' );

			if ( $cached_for === $current_version ) {
				return $cached;
			}

			delete_site_transient( $cache_key );
		}

		$response = $this->client->get_update_info( $license_key, $current_version );

		if ( is_wp_error( $response ) ) {
			set_site_transient(
				$cache_key,
				array(
					'no_update'    => true,
					'_dex_version' => $current_version,
				),
				300
			);
			return $response;
		}

		if ( isset( $response['license_valid'] ) ) {
			if ( false === $response['license_valid'] ) {
				$this->storage->set_status( array(
					'valid'       => false,
					'message'     => $response['license_message'] ?? '',
					'expires_at'  => null,
					'environment' => null,
				) );
			} else {
				$current_status = $this->storage->get_status();
				if ( is_array( $current_status ) && ! $current_status['valid'] ) {
					$this->storage->delete_status();
					delete_site_transient( 'dex_auto_act_tried_' . sanitize_key( $this->config['slug'] ) );
				}
			}
		}

		$response['_dex_version'] = $current_version;
		set_site_transient( $cache_key, $response, self::CACHE_TTL );

		return $response;
	}

	private function is_our_upgrade( array $hook_extra ): bool {
		if ( 'plugin' === $this->config['type'] ) {
			return (
				( $hook_extra['type'] ?? '' ) === 'plugin' &&
				isset( $hook_extra['plugins'] ) &&
				in_array( $this->plugin_file(), (array) $hook_extra['plugins'], true )
			) || (

				isset( $hook_extra['plugin'] ) &&
				$hook_extra['plugin'] === $this->plugin_file()
			);
		}

		return (
			( $hook_extra['type'] ?? '' ) === 'theme' &&
			(
				( isset( $hook_extra['themes'] ) && in_array( $this->config['slug'], (array) $hook_extra['themes'], true ) ) ||
				( isset( $hook_extra['theme'] )  && $hook_extra['theme'] === $this->config['slug'] )
			)
		);
	}

	private function cache_key(): string {
		return 'dex_update_' . sanitize_key( $this->config['slug'] );
	}

	private function plugin_file(): string {
		if ( ! empty( $this->config['file'] ) ) {
			return plugin_basename( $this->config['file'] );
		}

		$slug = $this->config['slug'];
		return "{$slug}/{$slug}.php";
	}
}

endif;
