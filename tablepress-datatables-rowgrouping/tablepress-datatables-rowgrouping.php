<?php
/*
Plugin Name: TablePress Extension: DataTables Row Grouping
Plugin URI: https://tablepress.org/extensions/datatables-rowgrouping/
Description: Custom Extension for TablePress to add the DataTables Row Grouping plugin
Version: 1.1
Author: Tobias Bäthge
Author URI: https://tobias.baethge.com/
*/

/*
 * See http://jquery-datatables-row-grouping.googlecode.com/svn/trunk/default.html
 */

/*
 * Register necessary Plugin Filters.
 */
add_filter( 'tablepress_shortcode_table_default_shortcode_atts', 'tablepress_add_shortcode_parameters_rowgrouping' );
add_filter( 'tablepress_table_js_options', 'tablepress_add_rowgrouping_js_options', 10, 3 );
add_filter( 'tablepress_datatables_command', 'tablepress_add_rowgrouping_js_command', 10, 5 );

/**
 * Add "datatables_rowgrouping" as a valid parameter to the [table /] Shortcode.
 *
 * @since 1.0
 *
 * @param array $default_atts Default attributes for the TablePress [table /] Shortcode.
 * @return array Extended attributes for the Shortcode.
 */
function tablepress_add_shortcode_parameters_rowgrouping( $default_atts ) {
	$default_atts['datatables_rowgrouping'] = '';
	return $default_atts;
}

/**
 * Pass "datatables_rowgrouping" from Shortcode parameters to JavaScript arguments.
 *
 * @since 1.0
 *
 * @param array  $js_options    Current JS options.
 * @param string $table_id      Table ID.
 * @param array $render_options Render Options.
 * @return array Modified JS options.
 */
function tablepress_add_rowgrouping_js_options( $js_options, $table_id, $render_options ) {
	$js_options['datatables_rowgrouping'] = $render_options['datatables_rowgrouping'];

	// Register the JS.
	if ( '' !== $js_options['datatables_rowgrouping'] ) {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$js_rowgrouping_url = plugins_url( "rowgrouping{$suffix}.js", __FILE__ );
		wp_enqueue_script( 'tablepress-rowgrouping', $js_rowgrouping_url, array( 'tablepress-datatables' ), '1.2.9', true );
	}

	return $js_options;
}

/**
 * Evaluate "datatables_rowgrouping" parameter and add corresponding JavaScript code, if needed.
 *
 * @since 1.0
 *
 * @param string $command    DataTables command.
 * @param string $html_id    HTML ID of the table.
 * @param array  $parameters DataTables parameters.
 * @param string $table_id   Table ID.
 * @param array  $js_options DataTables JS options.
 * @return string Modified DataTables command.
 */
function tablepress_add_rowgrouping_js_command( $command, $html_id, $parameters, $table_id, $js_options ) {
	if ( empty( $js_options['datatables_rowgrouping'] ) ) {
		return $command;
	}

	// Get rowgrouping parameters from Shortcode attribute, except if it's just set to "true".
	$rowgrouping_parameters = '';
	if ( true !== $js_options['datatables_rowgrouping'] ) {
		$rowgrouping_parameters = $js_options['datatables_rowgrouping'];
	}

	$command = "$('#{$html_id}').dataTable({$parameters}).rowGrouping({$rowgrouping_parameters});";
	return $command;
}
