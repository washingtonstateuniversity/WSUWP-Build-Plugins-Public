<?php
/**
 * View: Events Bar
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/views/v2/events-bar.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.9.4
 *
 */
?>
<div
	class="tribe-events-header__events-bar tribe-events-c-events-bar"
	data-js="tribe-events-events-bar"
>

	<h2 class="tribe-common-a11y-visual-hide"><?php printf( esc_html__( '%s Search and Views Navigation', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?></h2>

	<?php $this->template( 'events-bar/search-button' ); ?>

	<div
		class="tribe-events-c-events-bar__search-filters-container"
		id="tribe-events-search-filters-container"
		data-js="tribe-events-search-filters-container"
	>
		<?php $this->template( 'events-bar/tabs' ); ?>

		<?php $this->template( 'events-bar/search' ); ?>

		<?php $this->template( 'events-bar/filters' ); ?>
	</div>

	<?php $this->template( 'events-bar/views' ); ?>

</div>
