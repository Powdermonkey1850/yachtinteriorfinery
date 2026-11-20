<?php
namespace DEX\UpdaterSDK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DEX\UpdaterSDK\Updater', false ) ) :

class Updater {

	private static array $init_queue = [];

	private static array $initialized_slugs = [];

	private static bool $queue_flushed = false;

	private array $config;

	private License_Storage $storage;

	private API_Client $client;

	private Update_Checker $checker;

	private Settings_Widget $widget;

	private Notices $notices;

	public function __construct( array $config ) {
		$this->config = wp_parse_args( $config, array(
			'slug'    => '',
			'version' => '1.0.0',
			'file'    => '',
			'type'    => 'plugin',
			'api_url' => '',
		) );

		$this->storage = new License_Storage( $this->config['slug'] );
		$this->client  = new API_Client( $this->config['api_url'], $this->config['slug'] );
		$this->checker = new Update_Checker( $this->config, $this->storage, $this->client );
		$this->widget  = new Settings_Widget( $this->config, $this->storage, $this->client );
		$this->notices = new Notices( $this->config, $this->storage );
	}

	public function init(): void {
		if ( empty( $this->config['slug'] ) || empty( $this->config['api_url'] ) ) {
			return;
		}

		if ( self::$queue_flushed || did_action( 'plugins_loaded' ) ) {
			$this->do_register();
		} else {
			self::$init_queue[] = $this;
			if ( 1 === count( self::$init_queue ) ) {
				add_action( 'plugins_loaded', array( static::class, 'flush_init_queue' ), 1 );
			}
		}
	}

	public static function flush_init_queue(): void {
		self::$queue_flushed = true;

		foreach ( self::$init_queue as $updater ) {
			$updater->do_register();
		}

		self::$init_queue = [];
	}

	public function storage(): License_Storage {
		return $this->storage;
	}

	public function client(): API_Client {
		return $this->client;
	}

	public function config(): array {
		return $this->config;
	}

	private function do_register(): void {
		$slug = sanitize_key( $this->config['slug'] );

		if ( isset( self::$initialized_slugs[ $slug ] ) ) {
			return;
		}
		self::$initialized_slugs[ $slug ] = true;

		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			$this->checker->register();
		}

		if ( is_admin() ) {
			$this->widget->register();
			$this->notices->register();

			add_action( 'admin_init', array( $this, 'maybe_auto_activate' ) );
			add_action( 'admin_init', array( $this, 'maybe_ping' ) );
		}

		if ( 'plugin' === $this->config['type'] && ! empty( $this->config['file'] ) ) {
			register_deactivation_hook( $this->config['file'], array( $this, 'on_deactivation' ) );
		}
	}

	public function maybe_auto_activate(): void {
		$raw_key = $this->storage->get_key();
		if ( empty( $raw_key ) ) {
			return;
		}

		$status = $this->storage->get_status();
		if ( null !== $status ) {
			if ( ! empty( $status['valid'] ) ) {
				return;
			}

			if ( str_contains( $status['message'] ?? '', 'disabled for this site' ) ) {
				return;
			}

			if ( str_contains( $status['message'] ?? '', 'Single-site license limit reached' ) ) {
				return;
			}
		}

		$transient_key = 'dex_auto_act_tried_' . sanitize_key( $this->config['slug'] );
		if ( get_site_transient( $transient_key ) ) {
			return;
		}

		$response = $this->client->activate( $raw_key );
		$status   = Response_Parser::to_license_status( $response );

		$this->storage->set_status( $status );

		if ( $status['valid'] ) {
			delete_site_transient( $transient_key );
			$this->storage->set_shared_status( $status );
		} else {
			set_site_transient( $transient_key, 1, DAY_IN_SECONDS );
		}
	}

	public function maybe_ping(): void {
		$transient_key = 'dex_ping_' . sanitize_key( $this->config['slug'] );

		if ( get_site_transient( $transient_key ) ) {
			return;
		}

		set_site_transient( $transient_key, 1, WEEK_IN_SECONDS );

		$this->client->ping( Environment::site_url() );
	}

	public function on_deactivation(): void {
		$transient_key = 'dex_ping_' . sanitize_key( $this->config['slug'] );
		delete_site_transient( $transient_key );
		$this->client->ping( Environment::site_url(), 'deactivate' );
	}

}

endif;
