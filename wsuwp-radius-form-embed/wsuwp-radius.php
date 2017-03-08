<?php
/*
Plugin Name: WSUWP Radius
Version: 0.0.1
Description: A WordPress plugin to create a shortcode to embed a Hobsons Radius contact form using the form's URL.
Author: washingtonstateuniversity, ssheilah
Author URI: https://web.wsu.edu/
Plugin URI: https://github.com/ssheilah/wsuwp-radius
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// The core plugin class.
require dirname( __FILE__ ) . '/includes/class-wsuwp-radius.php';

add_action( 'after_setup_theme', 'WSUWP_Radius' );
/**
 * Start things up.
 *
 * @return \WSUWP_Radius
 */
function WSUWP_Radius() {
	return WSUWP_Radius::get_instance();
}
