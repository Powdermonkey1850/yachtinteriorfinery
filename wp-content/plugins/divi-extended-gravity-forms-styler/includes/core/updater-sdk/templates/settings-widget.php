<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_valid    = ! empty( $status['valid'] );
$expires_at  = $status['expires_at']  ?? null;
$environment = $status['environment'] ?? null;
?>

<div class="dex-license-widget" id="dex-license-widget">
	<div class="dex-license-widget__status" id="dex-status">
		<?php if ( $has_key ) : ?>
			<span class="dex-license-badge dex-license-badge--<?php echo $is_valid ? 'valid' : 'invalid'; ?>">
				<?php echo $is_valid ? esc_html__( 'Active', 'dex-updater-sdk' ) : esc_html__( 'Invalid / Expired', 'dex-updater-sdk' ); ?>
			</span>
			<?php if ( $is_valid && $expires_at ) : ?>
				<span class="dex-license-widget__expiry">
					<?php
					printf(
						esc_html__( 'Renews %s', 'dex-updater-sdk' ),
						esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expires_at ) ) )
					);
					?>
				</span>
			<?php elseif ( $is_valid ) : ?>
				<span class="dex-license-widget__expiry">
					<?php
					$label = 'Lifetime';
					$license_type = $status['license_type'] ?? null;
					if ( 'single_site' === $license_type ) {
						$label = 'Single Site';
					} elseif ( 'extended' === $license_type ) {
						$label = 'Extended';
					} elseif ( 'agency' === $license_type ) {
						$label = 'Agency';
					}
					echo esc_html( $label );
					?>
				</span>
			<?php endif; ?>
			<?php if ( $is_valid && $environment ) : ?>
				<span class="dex-license-widget__env">
					<?php echo esc_html( ucfirst( $environment ) ); ?>
				</span>
			<?php endif; ?>
		<?php else : ?>
			<span class="dex-license-badge dex-license-badge--inactive">
				<?php esc_html_e( 'Not activated', 'dex-updater-sdk' ); ?>
			</span>
		<?php endif; ?>
	</div>

	<div class="dex-license-widget__form">
		<input
			type="password"
			id="dex-license-key"
			class="regular-text dex-license-key-input"
			placeholder="<?php echo $has_key ? esc_attr( $masked_key ) : esc_attr__( 'Paste your license key here', 'dex-updater-sdk' ); ?>"
			autocomplete="off"
			<?php if ( $has_key ) : ?>readonly<?php endif; ?>
		/>

		<div class="dex-license-widget__actions">
			<?php if ( ! $has_key ) : ?>
				<button
					type="button"
					class="button button-primary"
					id="dex-activate-btn"
				>
					<?php esc_html_e( 'Activate License', 'dex-updater-sdk' ); ?>
				</button>
			<?php else : ?>
				<button
					type="button"
					class="button button-secondary"
					id="dex-deactivate-btn"
				>
					<?php esc_html_e( 'Deactivate', 'dex-updater-sdk' ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>

	<p class="description">
		<?php esc_html_e( 'This license key is shared across all Divi Extended plugins on this site. Enter it once to enable automatic updates for all installed products you have purchased.', 'dex-updater-sdk' ); ?>
	</p>

	<div class="dex-license-widget__message" id="dex-message"></div>

	<input type="hidden" id="dex-license-nonce" value="<?php echo esc_attr( $nonce ); ?>" />

	<?php if ( ! empty( $products ) ) :
		$has_any_rollback = false;
		foreach ( $products as $p ) {
			if (
				! empty( $p['rollback_version'] ) &&
				version_compare( $p['rollback_version'], $p['version'], '<' )
			) {
				$has_any_rollback = true;
				break;
			}
		}
	?>
	<?php if ( $has_any_rollback ) : ?>
	<hr style="margin:1.5em 0;">
	<h3><?php esc_html_e( 'Version Rollback', 'dex-updater-sdk' ); ?></h3>
	<p class="description"><?php esc_html_e( 'If a recent update caused issues you can roll back to the previous version. Rollback requires an active license.', 'dex-updater-sdk' ); ?></p>

	<table class="widefat striped dex-rollback-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Product', 'dex-updater-sdk' ); ?></th>
				<th><?php esc_html_e( 'Current Version', 'dex-updater-sdk' ); ?></th>
				<th><?php esc_html_e( 'Previous Version', 'dex-updater-sdk' ); ?></th>
				<th><?php esc_html_e( 'Action', 'dex-updater-sdk' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $products as $product_slug => $p ) :
				if ( empty( $p['rollback_version'] ) ) {
					continue;
				}
				if ( version_compare( $p['rollback_version'], $p['version'], '>=' ) ) {
					continue;
				}
				$rollback_used = $p['rollback_used'];
			?>
			<tr>
				<td><?php echo esc_html( $p['name'] ); ?></td>
				<td><?php echo esc_html( $p['version'] ?: '—' ); ?></td>
				<td><code><?php echo esc_html( $p['rollback_version'] ); ?></code></td>
				<td>
					<?php if ( $rollback_used ) : ?>
						<em class="dex-rollback-done">
							<?php
							printf(
								esc_html__( 'Rolled back to v%s', 'dex-updater-sdk' ),
								esc_html( $p['rollback_version'] )
							);
							?>
						</em>
						<p class="description" style="margin-top:4px;">
							<?php esc_html_e( 'Please update to the latest version before rolling back again.', 'dex-updater-sdk' ); ?>
						</p>
					<?php else : ?>
						<button
							type="button"
							class="button button-secondary dex-rollback-btn"
							data-slug="<?php echo esc_attr( $product_slug ); ?>"
							data-version="<?php echo esc_attr( $p['rollback_version'] ); ?>"
							data-used="false"
						>
							<?php
							printf(
								esc_html__( 'Rollback to v%s', 'dex-updater-sdk' ),
								esc_html( $p['rollback_version'] )
							);
							?>
						</button>
					<?php endif; ?>
					<div class="dex-rollback-message" id="dex-rollback-msg-<?php echo esc_attr( $product_slug ); ?>"></div>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
	<?php endif; ?>
</div>
