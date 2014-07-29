/**
 * Handle form submissions through the asset request form.
 */
(function( $, window, undefined ){

	/**
	 * Handle the click action on the form submission button.
	 */
	function handle_click( e ) {
		e.preventDefault();

		var first_name 	            = $( '#first-name' ).val(),
			last_name               = $( '#last-name' ).val(),
			email_address           = $( '#email-address' ).val(),
			area                    = $( '#area' ).val(),
			department              = $( '#department' ).val(),
			job_description         = $( '#job-description' ).val(),
			office_support_qty      = $( '#office-support-qty' ).val(),
			stone_sans_nocharge_qty = $( '#stone-sans-nocharge-qty' ).val(),
			stone_sans_charge_qty   = $( '#stone-sans-charge-qty' ).val(),
			full_stone_nocharge_qty = $( '#full-stone-nocharge-qty' ).val(),
			full_stone_charge_qty   = $( '#full-stone-charge-qty' ).val(),
			notes                   = $( '#request-notes' ).val(),
			post_id                 = $( '#request-form-post-id' ).val(),
			nonce                   = $( '#asset-request-nonce' ).val();

		// Build the data for our ajax call
		var data = {
			action:                  'submit_asset_request',
			first_name:              first_name,
			last_name:               last_name,
			email_address:           email_address,
			area:                    area,
			department:              department,
			job_description:         job_description,
			office_support_qty:      office_support_qty,
			stone_sans_nocharge_qty: stone_sans_nocharge_qty,
			stone_sans_charge_qty:   stone_sans_charge_qty,
			full_stone_nocharge_qty: full_stone_nocharge_qty,
			full_stone_charge_qty:   full_stone_charge_qty,
			notes:                   notes,
			post_id:                 post_id,
			_ajax_nonce:             nonce
		};

		// Make the ajax call
		$.post( window.ucomm_asset_data.ajax_url, data, function( response ) {
			response = $.parseJSON( response );

			if ( response.success ) {
				$( '#asset-request-form' ).remove();
				$( '#asset-request' ).append( '<p>Your asset request has been received. Please allow 24-48 hours for a response.</p>' );
			} else {
				$( '#asset-request' ).prepend( '<p>Something in the request failed. Please try again.</p><p>' + response.error + '</p>' );
			}
		});
	}

	$( '#submit-asset-request' ).on( 'click', handle_click );
}( jQuery, window ) );