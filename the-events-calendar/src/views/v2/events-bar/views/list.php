<?php
/**
 * View: Events Bar Views List
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/views/v2/events-bar/views/list.php
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
	class="tribe-events-c-view-selector__content"
	id="tribe-events-view-selector-content"
	aria-hidden="true"
>
	<ul class="tribe-events-c-view-selector__list">
		<?php foreach ( $this->get( 'views' ) as $view => $view_class_name ) : ?>
			<?php $this->template( 'events-bar/views/list/item', [ 'view_class_name' => $view_class_name ] ); ?>
		<?php endforeach; ?>
	</ul>
</div>
