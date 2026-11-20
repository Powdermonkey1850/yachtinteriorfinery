<?php
namespace DEX\UpdaterSDK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DEX\UpdaterSDK\Response_Parser', false ) ) :

class Response_Parser {

	public static function to_plugin_update( array $api_response, string $plugin_file ): ?object {
		if ( ! empty( $api_response['no_update'] ) || empty( $api_response['new_version'] ) ) {
			return null;
		}

		$package = $api_response['package'] ?? false;
		if ( '' === $package ) {
			$package = false;
		}

		return (object) array(
			'id'              => $plugin_file,
			'slug'            => $api_response['slug']         ?? '',
			'plugin'          => $plugin_file,
			'new_version'     => $api_response['new_version']  ?? '',
			'url'             => $api_response['url']          ?? '',
			'package'         => $package,
			'tested'          => $api_response['tested']       ?? '',
			'requires'        => $api_response['requires']     ?? '',
			'requires_php'    => $api_response['requires_php'] ?? '',
			'upgrade_notice'  => $api_response['upgrade_notice'] ?? '',
			'license_valid'   => $api_response['license_valid'] ?? true,
			'license_message' => $api_response['license_message'] ?? '',
		);
	}

	public static function to_plugin_info( array $api_response ): object {
		$sections     = $api_response['sections']     ?? array();
		$banners      = $api_response['banners']      ?? array();
		$contributors = $api_response['contributors'] ?? array();

		return (object) array(
			'name'          => $api_response['name']         ?? $api_response['slug'] ?? '',
			'slug'          => $api_response['slug']         ?? '',
			'version'       => $api_response['new_version']  ?? '',
			'author'        => $api_response['author']       ?? '',
			'homepage'      => $api_response['homepage']     ?? $api_response['url'] ?? '',
			'requires'      => $api_response['requires']     ?? '',
			'tested'        => $api_response['tested']       ?? '',
			'requires_php'  => $api_response['requires_php'] ?? '',
			'last_updated'  => $api_response['last_updated'] ?? '',
			'download_link' => $api_response['package']      ?? '',
			'banners'       => array(
				'low'  => $banners['low']  ?? '',
				'high' => $banners['high'] ?? '',
			),
			'contributors'  => $contributors,
			'sections'      => array(
				'description'  => $sections['description']  ?? '',
				'installation' => $sections['installation'] ?? '',
				'changelog'    => $sections['changelog']    ?? '',
				'faq'          => $sections['faq']          ?? '',
			),
		);
	}

	public static function to_theme_update( array $api_response ): ?array {
		if ( ! empty( $api_response['no_update'] ) || empty( $api_response['new_version'] ) ) {
			return null;
		}

		$package = $api_response['package'] ?? false;
		if ( '' === $package ) {
			$package = false;
		}

		return array(
			'theme'           => $api_response['slug']         ?? '',
			'new_version'     => $api_response['new_version']  ?? '',
			'url'             => $api_response['url']          ?? '',
			'package'         => $package,
			'requires'        => $api_response['requires']     ?? '',
			'requires_php'    => $api_response['requires_php'] ?? '',
			'license_valid'   => $api_response['license_valid'] ?? true,
			'license_message' => $api_response['license_message'] ?? '',
		);
	}

	public static function to_license_status( $response ): array {
		if ( is_wp_error( $response ) ) {
			return array(
				'valid'       => false,
				'message'     => $response->get_error_message(),
				'expires_at'  => null,
				'environment' => null,
			);
		}

		return array(
			'valid'       => ! empty( $response['valid'] ),
			'message'     => $response['message']     ?? '',
			'expires_at'  => $response['expires_at']  ?? null,
			'environment' => $response['environment'] ?? null,
			'license_type'=> $response['license_type']?? null,
		);
	}
}

endif;
