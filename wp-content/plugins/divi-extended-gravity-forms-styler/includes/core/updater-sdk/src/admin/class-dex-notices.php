<?php
namespace DEX\UpdaterSDK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'DEX\UpdaterSDK\Notices', false ) ) :

class Notices {

	private array $config;

	private License_Storage $storage;

	public function __construct( array $config, License_Storage $storage ) {
		$this->config  = $config;
		$this->storage = $storage;
	}

	public function register(): void {
		add_action( 'admin_notices',          array( $this, 'maybe_show_notice' ) );
		add_action( 'wp_ajax_dex_dismiss_license_notice_' . $this->slug(), array( $this, 'ajax_dismiss' ) );
	}

	public function maybe_show_notice(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->is_dismissed() ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		$show_on = array( 'plugins', 'settings_page_dex-license' );

		if ( ! in_array( $screen->id, $show_on, true ) ) {
			return;
		}

		$key    = $this->storage->get_key();
		$status = $this->storage->get_status();

		if ( ! empty( $key ) && ! empty( $status['valid'] ) ) {
			return;
		}

		$plugin_name = esc_html( ucwords( str_replace( '-', ' ', $this->config['slug'] ) ) );

		if ( empty( $key ) ) {
			$message = sprintf(
				__( '<strong>%1$s</strong> — Please <a href="%2$s">activate your license key</a> to receive automatic updates.', 'dex-updater-sdk' ),
				$plugin_name,
				esc_url( $this->settings_url() )
			);
		} else {
			$deny_reason = $status['message'] ?? __( 'unknown error', 'dex-updater-sdk' );
			$message = sprintf(
				__( '<strong>%1$s</strong> — License key is invalid or expired (%2$s). <a href="%3$s">Update your license</a>.', 'dex-updater-sdk' ),
				$plugin_name,
				esc_html( $deny_reason ),
				esc_url( $this->settings_url() )
			);
		}

		printf(
			'<div class="notice notice-warning is-dismissible dex-license-notice" data-slug="%s" data-nonce="%s"><p>%s</p></div>',
			esc_attr( $this->slug() ),
			esc_attr( wp_create_nonce( 'dex_dismiss_' . $this->slug() ) ),
			wp_kses( $message, array( 'strong' => array(), 'a' => array( 'href' => array() ) ) )
		);

		?>
		<script>
		jQuery( document ).on( 'click', '.dex-license-notice[data-slug="<?php echo esc_js( $this->slug() ); ?>"] .notice-dismiss', function() {
			var $n = jQuery( this ).closest( '.dex-license-notice' );
			jQuery.post( '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
				action: 'dex_dismiss_license_notice_<?php echo esc_js( $this->slug() ); ?>',
				_ajax_nonce: $n.data( 'nonce' )
			} );
		} );
		</script>
		<?php
	}

	public function ajax_dismiss(): void {
		check_ajax_referer( 'dex_dismiss_' . $this->slug() );

		if ( current_user_can( 'manage_options' ) ) {
			update_user_meta( get_current_user_id(), $this->dismiss_key(), 1 );
		}

		wp_send_json_success();
	}

	private function is_dismissed(): bool {
		return (bool) get_user_meta( get_current_user_id(), $this->dismiss_key(), true );
	}

	private function dismiss_key(): string {
		return 'dex_dismiss_notice_' . $this->slug();
	}

	private function slug(): string {
		return sanitize_key( $this->config['slug'] );
	}

	private function settings_url(): string {
		return admin_url( 'admin.php?page=' . DEX_ADMIN_MENU_SLUG );
	}
}

endif;
