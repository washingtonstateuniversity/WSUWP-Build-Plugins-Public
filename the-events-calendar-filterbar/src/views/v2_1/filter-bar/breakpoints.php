<?php
/**
 * Filter Bar: Breakpoints
 *
 * NOTE: This template is temporary and will be removed in a later release.
 * Do not hook into this template or modify, they will break in newer versions.
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 5.1.2
 *
 * @var bool   $is_initial_load    Boolean on whether view is being loaded for the first time.
 * @var string $breakpoint_pointer String we use as pointer to the current view we are setting up with breakpoints.
 */

if ( ! $is_initial_load ) {
	return;
}
?>
<script class="tribe-events-breakpoints">
	(function() {
		var completed = false;

		function initBreakpoints() {
			if ( completed ) {
				// This was fired already and completed no need to attach to the event listener.
				document.removeEventListener( 'DOMContentLoaded', initBreakpoints );
				return;
			}

			if ( 'undefined' === typeof window.tribe ) {
				return;
			}

			if ( 'undefined' === typeof window.tribe.filterBar ) {
				return;
			}

			if ( 'undefined' === typeof window.tribe.filterBar.filterBarState ) {
				return;
			}

			if ( 'function' !== typeof (window.tribe.filterBar.filterBarState.setup) ) {
				return;
			}

			var container = document.querySelectorAll( '[data-view-breakpoint-pointer="<?php echo esc_js( $breakpoint_pointer ); ?>"]' );

			if ( ! container ) {
				return;
			}

			window.tribe.filterBar.filterBarState.setup( container );
			completed = true;
			// This was fired already and completed no need to attach to the event listener.
			document.removeEventListener( 'DOMContentLoaded', initBreakpoints );
		}

		// Try to init the breakpoints right away.
		initBreakpoints();
		document.addEventListener( 'DOMContentLoaded', initBreakpoints );
	})();
</script>
