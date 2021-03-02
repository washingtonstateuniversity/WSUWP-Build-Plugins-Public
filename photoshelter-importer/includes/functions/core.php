<?php
/**
 * Core plugin functionality.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\Core;

/**
 * Bootstrap.
 *
 * Things done on plugin initialization.
 *
 * @since 1.0.0
 */
function bootstrap() {
}

/**
 * Activate the plugin.
 *
 * Things to do when the plugin is activated.
 *
 * @since 1.0.0
 */
function activate() {
	flush_rewrite_rules();
}

/**
 * Deactivate the plugin.
 *
 * Things to do when the plugin is deactivated.
 *
 * @since 1.0.0
 */
function deactivate() {

}

/**
 * Get Plugin info array.
 *
 * @return array Array of plugin info.
 * @since 1.0.0
 */
function get_plugin_info() {
	$default_headers = array(
		'Name'        => 'Plugin Name',
		'PluginURI'   => 'Plugin URI',
		'Version'     => 'Version',
		'Description' => 'Description',
		'Author'      => 'Author',
		'AuthorURI'   => 'Author URI',
		'TextDomain'  => 'Text Domain',
		'DomainPath'  => 'Domain Path',
	);

	$plugin_data = \get_file_data( PHOTOSHELTER_IMPORTER_PLUGIN, $default_headers, 'plugin' );

	return array_change_key_case( $plugin_data, CASE_LOWER );
}

/**
 * Get Plugin data value by key string.
 *
 * @param string $key Optional data key string.
 *
 * @return string|array String if key is specified and exists, or Array if no key is specified.
 * @since 1.0.0
 */
function get_plugin_data( $key = '' ) {
	$plugin_info = get_plugin_info();

	if ( empty( $key ) ) {
		return $plugin_info;
	}

	if ( isset( $plugin_info[ $key ] ) ) {
		return $plugin_info[ $key ];
	}

	return '';
}
