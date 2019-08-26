<?php
/**
 * View: Month View - Mobile Event Featured Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/views/v2/month/mobile-events/mobile-day/mobile-event/featured-image.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.9.4
 *
 */

 // @todo @fe: use template tags for images here
// @todo @fe use srcset here?

if ( ! $event->featured || empty( $event->thumbnail->full->url ) ) {
	return;
}

?>
<div class="tribe-events-calendar-month-mobile-events__mobile-event-featured-image-wrapper">
	<div class="tribe-events-calendar-month-mobile-events__mobile-event-featured-image tribe-common-c-image tribe-common-c-image--bg">
		<div
			class="tribe-common-c-image__bg"
			style="background-image: url('<?php echo esc_url( $event->thumbnail->full->url ); ?>');"
			role="img"
			aria-label="<?php echo esc_attr( get_the_title( $event->ID ) ); ?>"
		>
		</div>
	</div>
</div>
