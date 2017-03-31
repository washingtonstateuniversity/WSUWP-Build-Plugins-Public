<?php
/*
Plugin Name: TablePress Extension: DataTables Buttons
Plugin URI: https://tablepress.org/extensions/datatables-buttons/
Description: Extension for TablePress to add the DataTables Buttons functionality
Version: 1.0
Author: Tobias Bäthge
Author URI: https://tobias.baethge.com/
*/

/*
 * See http://datatables.net/extensions/buttons/
 */

/* Shortcode:
 * [table id=1 datatables_buttons="copy,csv,excel,pdf,print,colvis" /]
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

// Init TablePress_DataTables_Buttons.
add_action( 'tablepress_run', array( 'TablePress_DataTables_Buttons', 'init' ) );
//TablePress_DataTables_Buttons::init_update_checker();

/**
 * TablePress Extension: DataTables Buttons
 * @author Tobias Bäthge
 * @since 1.0
 */
class TablePress_DataTables_Buttons {

	/**
	 * Plugin slug.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected static $slug = 'tablepress-datatables-buttons';

	/**
	 * Plugin version.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected static $version = '1.0';

	/**
	 * Instance of the Plugin Update Checker class.
	 *
	 * @var PluginUpdateChecker
	 * @since 1.0
	 */
	protected static $plugin_update_checker;

	/**
	 * Initialize the plugin by registering necessary plugin filters and actions.
	 *
	 * @since 1.0
	 */
	public static function init() {
		add_filter( 'tablepress_shortcode_table_default_shortcode_atts', array( __CLASS__, 'shortcode_table_default_shortcode_atts' ) );
		add_filter( 'tablepress_table_js_options', array( __CLASS__, 'table_js_options' ), 10, 3 );
		add_filter( 'tablepress_datatables_parameters', array( __CLASS__, 'datatables_parameters' ), 10, 4 );
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_css_files' ) );
		}
	}

	/**
	 * Load and initialize the plugin update checker.
	 *
	 * @since 1.0
	 */
	public static function init_update_checker() {
		require_once dirname( __FILE__ ) . '/libraries/plugin-update-checker.php';
		self::$plugin_update_checker = PucFactory::buildUpdateChecker(
			'https://tablepress.org/downloads/extensions/update-check/' . self::$slug . '.json',
			__FILE__
		);
	}

	/**
	 * Add "datatables_buttons" and related parameters to the [table /] Shortcode.
	 *
	 * @since 1.0
	 *
	 * @param array $default_atts Default attributes for the TablePress [table /] Shortcode.
	 * @return array Extended attributes for the Shortcode.
	 */
	public static function shortcode_table_default_shortcode_atts( $default_atts ) {
		$default_atts['datatables_buttons'] = '';
		$default_atts['datatables_buttons_technique'] = 'flash,html5';
		return $default_atts;
	}

	/**
	 * Enqueue CSS files with the CSS code for the DataTables Buttons feature.
	 *
	 * @since 1.0
	 */
	public static function enqueue_css_files() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$url = plugins_url( "css/buttons.dataTables{$suffix}.css", __FILE__ );
		wp_enqueue_style( self::$slug, $url, array(), self::$version );
	}

	/**
	 * Pass configuration from Shortcode parameters to JavaScript arguments.
	 *
	 * @since 1.0
	 *
	 * @param array  $js_options     Current JS options.
	 * @param string $table_id       Table ID.
	 * @param array  $render_options Render Options.
	 * @return array Modified JS options.
	 */
	public static function table_js_options( $js_options, $table_id, $render_options ) {
		$js_options['datatables_buttons'] = strtolower( $render_options['datatables_buttons'] );
		$js_options['datatables_buttons_technique'] = strtolower( $render_options['datatables_buttons_technique'] );

		// Remove invalid button names from the list.
		$js_options['datatables_buttons'] = explode( ',', $js_options['datatables_buttons'] );
		foreach ( $js_options['datatables_buttons'] as $idx => $button ) {
			if ( ! in_array( $button, array( 'copy', 'csv', 'excel', 'pdf', 'print', 'colvis' ), true ) ) {
				unset( $js_options['datatables_buttons'][ $idx ] );
			}
		}

		// Bail out early if no button is to be shown.
		if ( 0 === count( $js_options['datatables_buttons'] ) ) {
			return $js_options;
		}

		// DataTables and with that the Header row must be turned on for DataTables Responsive to be usable.
		$js_options['use_datatables'] = true;
		$js_options['table_head'] = true;

		// Register the JS files.
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$url = plugins_url( "js/dataTables.buttons{$suffix}.js", __FILE__ );
		wp_enqueue_script( self::$slug, $url, array( 'tablepress-datatables' ), self::$version, true );

		// If any of the export buttons is shown, we need the Flash and HTML5 JS files.
		foreach ( array( 'copy', 'csv', 'excel', 'pdf' ) as $button ) {
			if ( in_array( $button, $js_options['datatables_buttons'], true ) ) {
				if ( false !== strpos( $js_options['datatables_buttons_technique'], 'flash' ) ) {
					$url = plugins_url( "js/buttons.flash{$suffix}.js", __FILE__ );
					wp_enqueue_script( self::$slug . '-flash', $url, array( self::$slug ), self::$version, true );

					// Add the common filter that adds JS for all calls on the page.
					if ( ! has_filter( 'tablepress_all_datatables_commands', array( __CLASS__, 'all_datatables_commands' ) ) ) {
						add_filter( 'tablepress_all_datatables_commands', array( __CLASS__, 'all_datatables_commands' ) );
					}
				}
				if ( false !== strpos( $js_options['datatables_buttons_technique'], 'html5' ) ) {
					$url = plugins_url( "js/buttons.html5{$suffix}.js", __FILE__ );
					wp_enqueue_script( self::$slug . '-html5', $url, array( self::$slug ), self::$version, true );
				}
				break;
			}
		}

		// Add special JS files for special buttons.
		if ( in_array( 'print', $js_options['datatables_buttons'], true ) ) {
			$url = plugins_url( "js/buttons.print{$suffix}.js", __FILE__ );
			wp_enqueue_script( self::$slug . '-print', $url, array( self::$slug ), self::$version, true );
		}
		if ( in_array( 'excel', $js_options['datatables_buttons'], true ) ) {
			$url = plugins_url( 'js/jszip.min.js', __FILE__ );
			wp_enqueue_script( self::$slug . '-jsmin', $url, array( self::$slug ), self::$version, true );
		}
		if ( in_array( 'pdf', $js_options['datatables_buttons'], true ) ) {
			$url = plugins_url( 'js/pdfmake.min.js', __FILE__ );
			wp_enqueue_script( self::$slug . '-pdfmake', $url, array( self::$slug ), self::$version, true );
		}
		if ( in_array( 'colvis', $js_options['datatables_buttons'], true ) ) {
			$url = plugins_url( "js/buttons.colVis{$suffix}.js", __FILE__ );
			wp_enqueue_script( self::$slug . '-colvis', $url, array( self::$slug ), self::$version, true );
		}

		return $js_options;
	}

	/**
	 * Evaluate JS parameters and convert them to DataTables parameters.
	 *
	 * @since 1.0
	 *
	 * @param array  $parameters DataTables parameters.
	 * @param string $table_id   Table ID.
	 * @param string $html_id    HTML ID of the table.
	 * @param array  $js_options JS options for DataTables.
	 * @return array Extended DataTables parameters.
	 */
	public static function datatables_parameters( $parameters, $table_id, $html_id, $js_options ) {
		// Bail out early if no button is to be shown.
		if ( 0 === count( $js_options['datatables_buttons'] ) ) {
			return $parameters;
		}

		$parameters['dom'] = '"dom":"Blfrtip"';

		// Construct the DataTables Buttons config parameter.
		foreach ( $js_options['datatables_buttons'] as &$button ) {
			$button = "'{$button}'";
		}
		$parameters['buttons'] = '"buttons":[' . implode( ',', $js_options['datatables_buttons'] ) . ']';

		return $parameters;
	}

	/**
	 * If the Flash technique is loaded (because the export buttons are shown), change the default location of a SWF file to the local copy.
	 *
	 * @since 1.0
	 *
	 * @param array $commands The JS commands for the DataTables JS library.
	 * @return array Modified JS commands for the DataTables JS library.
	 */
	public static function all_datatables_commands( $commands ) {
		$url = plugins_url( 'swf/flashExport.swf', __FILE__ );
		$commands = "$.fn.dataTable.Buttons.swfPath='{$url}';\n" . $commands;
		return $commands;
	}

} // class TablePress_DataTables_Buttons
