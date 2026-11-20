<?php
namespace DEX\UpdaterSDK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DEX\UpdaterSDK\License_Storage', false ) ) :

class License_Storage {

	const OPTION_KEY = 'divi_extended_license_key';

	const SHARED_STATUS_KEY = 'divi_extended_license_status';

	private string $slug;

	private string $enc_key;

	public function __construct( string $slug ) {
		$this->slug    = sanitize_key( $slug );
		$this->enc_key = $this->derive_key();
	}

	public function get_key(): string {
		$stored = get_option( self::OPTION_KEY, '' );

		if ( empty( $stored ) ) {
			return '';
		}

		return $this->decrypt( (string) $stored );
	}

	public function set_key( string $raw_key ): void {
		if ( '' === trim( $raw_key ) ) {
			delete_option( self::OPTION_KEY );
			return;
		}

		update_option( self::OPTION_KEY, $this->encrypt( $raw_key ), false );
	}

	public function delete_key(): void {
		delete_option( self::OPTION_KEY );
		$this->delete_shared_status();
		$this->delete_status();
	}

	public function set_shared_status( array $status ): void {
		update_option( self::SHARED_STATUS_KEY, $status, false );
	}

	public function get_shared_status(): ?array {
		$val = get_option( self::SHARED_STATUS_KEY, null );
		return is_array( $val ) ? $val : null;
	}

	public function delete_shared_status(): void {
		delete_option( self::SHARED_STATUS_KEY );
	}

	public function set_status( array $status ): void {
		update_option( $this->status_option_name(), $status, false );
	}

	public function get_status(): ?array {
		$val = get_option( $this->status_option_name(), null );
		return is_array( $val ) ? $val : null;
	}

	public function delete_status(): void {
		delete_option( $this->status_option_name() );
	}

	private function status_option_name(): string {
		return self::SHARED_STATUS_KEY . '_' . $this->slug;
	}

	private function derive_key(): string {
		$salt  = defined( 'AUTH_KEY' )  ? AUTH_KEY  : '';
		$salt .= defined( 'AUTH_SALT' ) ? AUTH_SALT : '';

		return substr( hash( 'sha256', $salt, true ), 0, 32 );
	}

	private function encrypt( string $plaintext ): string {
		$iv         = random_bytes( 16 );
		$ciphertext = openssl_encrypt( $plaintext, 'AES-256-CBC', $this->enc_key, OPENSSL_RAW_DATA, $iv );

		if ( false === $ciphertext ) {

			return base64_encode( $plaintext );
		}

		return base64_encode( $iv . $ciphertext );
	}

	private function decrypt( string $stored ): string {
		$raw = base64_decode( $stored, true );

		if ( false === $raw || strlen( $raw ) < 17 ) {

			$plain = base64_decode( $stored, true );
			return ( false !== $plain ) ? $plain : '';
		}

		$iv         = substr( $raw, 0, 16 );
		$ciphertext = substr( $raw, 16 );
		$plaintext  = openssl_decrypt( $ciphertext, 'AES-256-CBC', $this->enc_key, OPENSSL_RAW_DATA, $iv );

		return ( false !== $plaintext ) ? $plaintext : '';
	}
}

endif;
