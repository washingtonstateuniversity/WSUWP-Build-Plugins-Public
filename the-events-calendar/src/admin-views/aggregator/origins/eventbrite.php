<?php
$field = new stdClass;
$field->label = __( 'Select an Event', 'the-events-calendar' );
$field->placeholder = __( 'Select from your existing Eventbrite events', 'the-events-calendar' );
?>
<tr class="tribe-dependent" data-depends="#tribe-ea-field-origin" data-condition="eventbrite">
	<th scope="row">
		<label for="tribe-ea-field-eventbrite_selected_id"><?php echo esc_html( $field->label ); ?></label>
	</th>
	<td>
		<?php wp_nonce_field( 'import_eventbrite', 'import_eventbrite' ); ?>
		<input
			name="eventbrite_selected_id"
			type="hidden"
			id="tribe-ea-field-eventbrite_selected_id"
			class="tribe-ea-field tribe-ea-size-medium tribe-ea-dropdown"
			placeholder="<?php echo esc_attr( $field->placeholder ); ?>"
			data-prevent-clear
		>
	</td>
</tr>
<?php
$field = new stdClass;
$field->label = __( 'Eventbrite Event ID', 'the-events-calendar' );
$field->placeholder = __( 'Eventbrite Event ID', 'the-events-calendar' );
$field->help = __( 'When filled out, this field will be used instead of the selected event above.', 'the-events-calendar' );
?>
<tr class="tribe-dependent" data-depends="#tribe-ea-field-origin" data-condition="eventbrite">
	<th scope="row">
		<label for="tribe-ea-field-eventbrite_id"><?php echo esc_html( $field->label ); ?></label>
	</th>
	<td>
		<input
			name="eventbrite_selected_id"
			type="hidden"
			id="tribe-ea-field-eventbrite_selected_id"
			class="tribe-ea-field tribe-ea-size-medium"
			placeholder="<?php echo esc_attr( $field->placeholder ); ?>"
		>
		<span class="tribe-bumpdown-trigger tribe-bumpdown-permanent tribe-ea-help dashicons dashicons-editor-help" data-bumpdown="<?php echo esc_attr( $field->help ); ?>"></span>
	</td>
</tr>
