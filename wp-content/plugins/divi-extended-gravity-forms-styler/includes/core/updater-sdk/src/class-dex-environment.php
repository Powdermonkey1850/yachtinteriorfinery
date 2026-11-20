<?php
namespace DEX\UpdaterSDK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DEX\UpdaterSDK\Environment', false ) ) :

class Environment {

	public static function detect(): string {
		return self::classify( self::current_host() );
	}

	public static function site_url(): string {
		$url = site_url();
		return self::normalize( $url );
	}

	private static function classify( string $host ): string {
		if ( self::is_local( $host ) ) {
			return 'local';
		}

		if ( self::is_staging( $host ) ) {
			return 'staging';
		}

		return 'production';
	}

	private static function is_local( string $host ): bool {

		if ( in_array( $host, array( 'localhost', '127.0.0.1', '::1' ), true ) ) {
			return true;
		}

		foreach ( array( '.local', '.test', '.localhost', '.invalid' ) as $tld ) {
			if ( str_ends_with( $host, $tld ) ) {
				return true;
			}
		}

		if ( filter_var( $host, FILTER_VALIDATE_IP ) ) {
			foreach ( array( '10.', '192.168.', '172.' ) as $prefix ) {
				if ( str_starts_with( $host, $prefix ) ) {
					return true;
				}
			}
		}

		return false;
	}

	private static function is_staging( string $host ): bool {

		$staging_domains = array(
			'.wpengine.com',
			'.kinsta.cloud',
			'.pantheonsite.io',
			'.flywheelsites.com',
			'.wpsandbox.pro',
			'.instawp.xyz',
		);

		foreach ( $staging_domains as $domain ) {
			if ( str_ends_with( $host, $domain ) ) {
				return true;
			}
		}

		$staging_prefixes = array( 'staging.', 'dev.', 'test.', 'sandbox.', 'qa.', 'uat.', 'preview.' );

		foreach ( $staging_prefixes as $prefix ) {
			if ( str_starts_with( $host, $prefix ) ) {
				return true;
			}
		}

		return false;
	}

	private static function current_host(): string {
		$parts = parse_url( site_url() );
		return strtolower( $parts['host'] ?? '' );
	}

	private static function normalize( string $url ): string {
		$url   = strtolower( trim( $url ) );
		$parts = parse_url( $url );

		if ( empty( $parts['host'] ) ) {
			return $url;
		}

		$scheme = $parts['scheme'] ?? 'https';
		$host   = $parts['host'];

		if ( str_starts_with( $host, 'www.' ) ) {
			$host = substr( $host, 4 );
		}

		$path = rtrim( $parts['path'] ?? '', '/' );

		return $scheme . '://' . $host . $path;
	}
}

endif;
