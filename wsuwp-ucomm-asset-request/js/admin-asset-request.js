(function($, window){
	// When publishing, this will flash briefly. It should say 'Approve'.
	window.postL10n.publish = 'Approve';

	// Rename the 'Publish' action to 'Approve' when applicable.
	$( '#publish' ).attr( 'value', window.postL10n.publish );
	$( '#original_publish' ).attr( 'value', window.postL10n.publish );
}(jQuery, window));