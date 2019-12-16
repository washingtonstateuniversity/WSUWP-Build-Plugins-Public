<?php
/**
 * View: Top Bar Navigation Previous Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/top-bar/nav/prev.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @var string $link The URL to the previous page, if any, or an empty string.
 *
 * @version 4.9.10
 *
 */
?>
<li class="tribe-events-c-top-bar__nav-list-item">
	<a
		href="<?php echo esc_url( $link ); ?>"
		class="tribe-common-c-btn-icon tribe-common-c-btn-icon--caret-left tribe-events-c-top-bar__nav-link tribe-events-c-top-bar__nav-link--prev"
		aria-label="<?php esc_html_e( 'Previous', 'the-events-calendar' ); ?>"
		title="<?php esc_html_e( 'Previous', 'the-events-calendar' ); ?>"
		data-js="tribe-events-view-link"
	>
	</a>
</li>
