<?php

namespace WSUWP\CAHNRSWSUWP_Plugin_People;

/**
 * Get the plugin dir path
 * @since 0.0.1
 * 
 * @param string $path Optional path to append.
 * 
 * @return string Full plugin path
 */
function people_get_plugin_dir_path( $path = '' ) {

	$plugin_dir = plugin_dir_path( __FILE__ );

	if ( ! empty( $path ) ) {

		$plugin_dir .= $path;

	} // End if

	return $plugin_dir;

} // End people_get_plugin_dir_path


/**
 * Get the plugin url
 * @since 0.0.1
 * 
 * @param string $path Optional path to append.
 * 
 * @return string Full plugin path
 */
function people_get_plugin_url( $path = '' ) {

	$plugin_url = plugin_dir_url( __FILE__ );

	if ( ! empty( $path ) ) {

		$plugin_url .= $path;

	} // End if

	return $plugin_url;

} // End people_get_plugin_url
