( function($) {

	$( document ).ready( function() {
		$( '.chosen-select' ).chosen();
	} );

	if ( typeof inlineEditPost !== 'undefined' ) {

		var $wp_inline_edit = inlineEditPost.edit;

		inlineEditPost.edit = function( id ) {

			$wp_inline_edit.apply( this, arguments );

			var $post_id = 0;

			if ( typeof( id ) == 'object' ) {
				$post_id = parseInt( this.getId( id ) );
			}

			if ( $post_id > 0 ) {
				var $edit_row = $( '#edit-' + $post_id );
				var $release_date = $( '#spnote-' + $post_id ).text();
				$edit_row.find( 'textarea[name="spnote"]' ).val( $release_date );
			}

		}

	}

	$( '#bulk_edit' ).on( 'click', function() {

		var $bulk_row = $( '#bulk-edit' );

		var $post_ids = new Array();

		$bulk_row.find( '#bulk-titles' ).children().each( function() {
			$post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
		});

		var spnote = $bulk_row.find( 'textarea[name="spnote"]' ).val();

		$.ajax( {
			url   : ajaxurl,
			type  : 'POST',
			async : false,
			cache : false,
			data  : {
				action   : 'spnote_save_bulk_edit',
				post_ids : $post_ids,
				spnote   : spnote
			}
		} );

	});

} )( jQuery );
