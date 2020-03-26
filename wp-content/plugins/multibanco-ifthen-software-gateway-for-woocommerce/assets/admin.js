(function( $ ) {

	if ( ifthenpay.gateway != '' ) {

		var hide_extra_fields = true;

		switch( ifthenpay.gateway ) {
			case 'multibanco':
				if (
					$( '#woocommerce_multibanco_ifthen_for_woocommerce_ent' ).val().trim().length == 5
					&&
					$( '#woocommerce_multibanco_ifthen_for_woocommerce_subent' ).val().trim().length <= 3
					&&
					parseInt( $( '#woocommerce_multibanco_ifthen_for_woocommerce_ent' ).val() ) > 0
					&&
					parseInt( $( '#woocommerce_multibanco_ifthen_for_woocommerce_subent' ).val() ) > 0
					&&
					$( '#woocommerce_multibanco_ifthen_for_woocommerce_secret_key' ).val().trim() != ''
				) {
					hide_extra_fields = false;
				}
				break;
			case 'mbway':
				if (
					$( '#woocommerce_mbway_ifthen_for_woocommerce_mbwaykey' ).val().trim().length == 10
					&&
					$( '#woocommerce_mbway_ifthen_for_woocommerce_secret_key' ).val().trim() != ''
				) {
					hide_extra_fields = false;
				}
				break;
			case 'payshop':
				if (
					$( '#woocommerce_payshop_ifthen_for_woocommerce_payshopkey' ).val().trim().length == 10
					&&
					$( '#woocommerce_payshop_ifthen_for_woocommerce_secret_key' ).val().trim() != ''
				) {
					hide_extra_fields = false;
				}
				break;
			default:
				// code block
				break;
		}

		//Hide extra fields if there are errors on required fields
		if ( hide_extra_fields ) {
			switch( ifthenpay.gateway ) {
				case 'multibanco':
					var number_fields = 4;
					if ( $( '#wc_ifthen_mb_mode' ).length ) {
						number_fields++;
					}
					$( '#wc_ifthen_settings table.form-table tr:nth-child(n+'+number_fields+')' ).hide();
					$( '#wc_ifthen_settings .mb_hide_extra_fields' ).hide();
					break;
				case 'mbway':
					$( '#wc_ifthen_settings table.form-table tr:nth-child(n+3)' ).hide();
					$( '#wc_ifthen_settings .mb_hide_extra_fields' ).hide();
					break;
				case 'payshop':
					$( '#wc_ifthen_settings table.form-table tr:nth-child(n+3)' ).hide();
					$( '#wc_ifthen_settings .mb_hide_extra_fields' ).hide();
					break;
				default:
					// code block
					break;
			}
		}

		//Settings saved (??)
		$( '#woocommerce_'+ifthenpay.gateway+'_ifthen_for_woocommerce_settings_saved' ).val( '1' );

		//Callback activation
		$( '#wc_ifthen_callback_open' ).click( function() {
			ifthen_callback_open();
			return false;
		});
		$( '#wc_ifthen_callback_cancel' ).click( function() {
			$( '#wc_ifthen_callback_div' ).toggle();
			$( '#wc_ifthen_callback_open_p' ).toggle();
			return false;
		});
		//Callback send
		$( '#wc_ifthen_callback_submit' ).click( function() {
			if ( confirm( ifthenpay.callback_confirm ) ) {
				$( '#wc_ifthen_callback_send' ).val( 1 );
				$( '#mainform' ).submit()
				return true;
			} else {
				return false;
			}
		});
		setTimeout( function() {
			if ( ifthenpay.callback_email_sent == 'no' ) {
				$( '#wc_ifthen_callback_open' ).addClass('button-link-delete');
				ifthen_callback_open();
				if ( ifthenpay.callback_auto_open == '1' ) { 
					setTimeout( function() {
						$( '#wc_ifthen_callback_div' ).addClass('focus' );
					}, 250 );
					setTimeout( function() {
						$( '#wc_ifthen_callback_div' ).removeClass('focus' );
					}, 1500 );
				}
			}
		}, 500 );

	}

	function ifthen_callback_open() {
		$( '#wc_ifthen_callback_div' ).toggle();
		$( '#wc_ifthen_callback_open_p' ).toggle();
	}

})( jQuery );
