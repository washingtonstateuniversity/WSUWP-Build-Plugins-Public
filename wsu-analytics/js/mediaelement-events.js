var _wpmejsSettings={
	pluginPath: "/wp-includes/js/mediaelement/",
	success: function( mejs ) {
		//orginal default settings
		var autoplay, loop;

		if ( 'flash' === mejs.pluginType ) {
			autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;
			loop = mejs.attributes.loop && 'false' !== mejs.attributes.loop;

			autoplay && mejs.addEventListener( 'canplay', function () {
				mejs.play();
			}, false );

			loop && mejs.addEventListener( 'ended', function () {
				mejs.play();
			}, false );
		}

		if(typeof jQuery.jtrack !=="undefined"){
			// Event listener for when the video starts playing
			mejs.addEventListener( 'playing', function( e ) {
				jQuery.jtrack.trackEvent(pageTracker,"Audio", 'playing');
			}, false);

			// Event listener for when the video is paused
			mejs.addEventListener( 'pause', function( e ) {
				jQuery.jtrack.trackEvent(pageTracker,"Audio", 'pausing');
			}, false);

			// Event listener for when the video ends
			mejs.addEventListener( 'ended', function( e ) {
				jQuery.jtrack.trackEvent(pageTracker,"Audio", 'ending');
			}, false);
		}
	}
};