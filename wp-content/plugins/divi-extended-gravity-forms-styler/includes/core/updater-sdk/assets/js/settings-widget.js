( function ( $ ) {
	'use strict';

	$( function () {

		if ( typeof dexLicense === 'undefined' ) {
			return;
		}

		var data           = dexLicense;
		var $widget        = $( '#dex-license-widget' );
		var $keyInput      = $( '#dex-license-key' );
		var $messageEl     = $( '#dex-message' );
		var nonce          = $( '#dex-license-nonce' ).val() || data.nonce;

		if ( ! $widget.length ) {
			return;
		}

		$widget.on( 'click', '#dex-activate-btn', function () {
			var $btn = $( this );
			var key  = $keyInput.val().trim();

			if ( ! key ) {
				showMessage( $messageEl, data.i18n.error, 'error' );
				return;
			}

			$btn.prop( 'disabled', true ).text( data.i18n.activating );
			clearMessage( $messageEl );

			$.post( data.ajaxUrl, {
				action:      data.activateAction,
				_ajax_nonce: nonce,
				license_key: key,
			} )
			.done( function ( response ) {
				if ( response.success ) {

					setTimeout( function () { window.location.reload(); }, 600 );
				} else {
					showMessage( $messageEl, ( response.data && response.data.message ) || data.i18n.error, 'error' );
					$btn.prop( 'disabled', false ).text( 'Activate License' );
				}
			} )
			.fail( function () {
				showMessage( $messageEl, data.i18n.error, 'error' );
				$btn.prop( 'disabled', false ).text( 'Activate License' );
			} );
		} );

		$widget.on( 'click', '#dex-deactivate-btn', function () {
			var $btn = $( this );

			$btn.prop( 'disabled', true ).text( data.i18n.deactivating );
			clearMessage( $messageEl );

			$.post( data.ajaxUrl, {
				action:      data.deactivateAction,
				_ajax_nonce: nonce,
			} )
			.done( function ( response ) {
				if ( response.success ) {
					setTimeout( function () { window.location.reload(); }, 500 );
				} else {
					showMessage( $messageEl, ( response.data && response.data.message ) || data.i18n.error, 'error' );
					$btn.prop( 'disabled', false );
				}
			} )
			.fail( function () {
				showMessage( $messageEl, data.i18n.error, 'error' );
				$btn.prop( 'disabled', false );
			} );
		} );

		$widget.on( 'click', '.dex-rollback-btn', function ( e ) {
			e.preventDefault();

			var $btn     = $( this );
			var slug     = $btn.data( 'slug' );
			var version  = $btn.data( 'version' );
			var used     = $btn.data( 'used' ) === true || $btn.data( 'used' ) === 'true';
			var $msgEl   = $( '#dex-rollback-msg-' + slug );

			if ( used ) {
				showMessage( $msgEl, data.i18n.rollbackAlready, 'error' );
				return;
			}

			if ( ! window.confirm( data.i18n.rollbackConfirm ) ) {
				return;
			}

			$btn.prop( 'disabled', true ).text( data.i18n.rollingBack );
			clearMessage( $msgEl );

			$.post( data.ajaxUrl, {
				action:      data.rollbackAction,
				_ajax_nonce: nonce,
				slug:        slug,
			} )
			.done( function ( response ) {
				if ( response.success ) {
					showMessage( $msgEl, response.data.message || data.i18n.rollbackDone, 'success' );

					$btn.data( 'used', 'true' ).addClass( 'dex-rollback-btn--used' );

					setTimeout( function () { window.location.reload(); }, 2000 );
				} else {
					showMessage( $msgEl, ( response.data && response.data.message ) || data.i18n.error, 'error' );
					$btn.prop( 'disabled', false ).text( 'Rollback to v' + version );
				}
			} )
			.fail( function () {
				showMessage( $msgEl, data.i18n.error, 'error' );
				$btn.prop( 'disabled', false ).text( 'Rollback to v' + version );
			} );
		} );

		function showMessage( $el, message, type ) {
			$el
				.removeClass( 'dex-message--success dex-message--error' )
				.addClass( 'dex-message--' + type )
				.text( message )
				.css( 'display', 'block' );
		}

		function clearMessage( $el ) {
			$el.text( '' ).css( 'display', 'none' );
		}

	} );

}( jQuery ) );
