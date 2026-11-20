<?php
namespace DEX\UpdaterSDK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DEX\UpdaterSDK\API_Client', false ) ) :

class API_Client {

	const TIMEOUT = 10;

	private string $api_url;

	private string $slug;

	public function __construct( string $api_url, string $slug ) {
		$this->api_url = rtrim( $api_url, '/' );
		$this->slug    = $slug;
	}

	public function activate( string $license_key ) {
		return $this->post(
			'/wp-json/dex-api/v1/license/activate',
			array(
				'license_key'  => $license_key,
				'site_url'     => Environment::site_url(),
				'product_slug' => $this->slug,
				'sdk_version'  => DEX_UPDATER_SDK_VERSION,
			)
		);
	}

	public function deactivate( string $license_key ) {
		return $this->post(
			'/wp-json/dex-api/v1/license/deactivate',
			array(
				'license_key'  => $license_key,
				'site_url'     => Environment::site_url(),
				'product_slug' => $this->slug,
			)
		);
	}

	public function check( string $license_key ) {
		return $this->post(
			'/wp-json/dex-api/v1/license/check',
			array(
				'license_key'  => $license_key,
				'site_url'     => Environment::site_url(),
				'product_slug' => $this->slug,
			)
		);
	}

	public function get_update_info( string $license_key, string $current_version ) {
		return $this->get(
			'/wp-json/dex-api/v1/update/info',
			array(
				'slug'        => $this->slug,
				'license_key' => $license_key,
				'site_url'    => Environment::site_url(),
				'version'     => $current_version,
			)
		);
	}

	public function build_rollback_url( string $license_key, string $rollback_version, string $site_url ): string {
		return add_query_arg(
			array(
				'slug'        => rawurlencode( $this->slug ),
				'license_key' => rawurlencode( $license_key ),
				'site_url'    => rawurlencode( $site_url ),
				'version'     => rawurlencode( $rollback_version ),
			),
			$this->api_url . '/wp-json/dex-api/v1/update/download'
		);
	}

	public function ping( string $site_url, string $event = 'ping' ): void {
		wp_remote_post(
			$this->api_url . '/wp-json/dex-api/v1/update/ping',
			array(
				'timeout'    => 2,
				'blocking'   => true,
				'user-agent' => $this->user_agent(),
				'headers'    => array( 'Content-Type' => 'application/json' ),
				'body'       => wp_json_encode( array(
					'slug'     => $this->slug,
					'site_url' => $site_url,
					'event'    => $event,
				) ),
			)
		);
	}

	private function post( string $path, array $body ) {
		$response = wp_remote_post(
			$this->api_url . $path,
			array(
				'timeout'    => self::TIMEOUT,
				'user-agent' => $this->user_agent(),
				'headers'    => array( 'Content-Type' => 'application/json' ),
				'body'       => wp_json_encode( $body ),
			)
		);

		return $this->parse_response( $response );
	}

	private function get( string $path, array $params = array() ) {
		$url = $this->api_url . $path;

		if ( ! empty( $params ) ) {
			$url = add_query_arg( array_map( 'urlencode', $params ), $url );
		}

		$response = wp_remote_get(
			$url,
			array(
				'timeout'    => self::TIMEOUT,
				'user-agent' => $this->user_agent(),
			)
		);

		return $this->parse_response( $response );
	}

	private function parse_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( ! is_array( $data ) ) {
			return new \WP_Error(
				'dex_api_invalid_response',
				sprintf( 'Unexpected API response (HTTP %d).', $code )
			);
		}

		return $data;
	}

	private function user_agent(): string {
		global $wp_version;
		return sprintf(
			'DEX-Updater-SDK/%s WordPress/%s PHP/%s slug/%s',
			DEX_UPDATER_SDK_VERSION,
			$wp_version ?? 'unknown',
			PHP_VERSION,
			$this->slug
		);
	}
}

endif;
