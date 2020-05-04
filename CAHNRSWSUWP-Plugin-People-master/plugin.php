<?php
/*
Plugin Name: CAHNRSWSUWP People Profiles
Version: 0.0.3
Description: Allow local profiles that sync downstream from people.wsu.edu.
Author: washingtonstateuniversity, Danial Bleile
Author URI: https://web.wsu.edu/
Plugin URI: https://github.com/washingtonstateuniversity/cahnrswsuwp-plugin-people
Text Domain: cahnrswsuwp-plugin-people
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// This plugin uses namespaces and requires PHP 5.3 or greater.
if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	add_action( 'admin_notices', create_function( '', // phpcs:ignore WordPress.PHP.RestrictedPHPFunctions.create_function_create_function
	"echo '<div class=\"error\"><p>" . __( 'WSUWP Plugin Skeleton requires PHP 5.3 to function properly. Please upgrade PHP or deactivate the plugin.', 'wsuwp-plugin-skeleton' ) . "</p></div>';" ) );
	return;
} else {

	include_once __DIR__ . '/functions.php';

	include_once __DIR__ . '/includes/include-cahnrswsuwp-plugin-people.php';

	$cahnrswsuwp_plugin_people = WSUWP\CAHNRSWSUWP_Plugin_People\CAHNRSWSUWP_Plugin_People::get_instance();

}
