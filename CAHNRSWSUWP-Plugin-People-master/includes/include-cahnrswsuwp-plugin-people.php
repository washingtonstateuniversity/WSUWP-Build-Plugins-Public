<?php

namespace WSUWP\CAHNRSWSUWP_Plugin_People;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class CAHNRSWSUWP_Plugin_People {

	private static $instance;

	/**
	 * Maintain and return the one instance. Initiate hooks when called the first time.
	 *
	 * @since 0.0.1
	 *
	 * @return \CAHNRSWSUWP_Plugin_People
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			
			self::$instance = new CAHNRSWSUWP_Plugin_People();

			self::$instance->init_plugin();

		} // End if

		return self::$instance;

	} // End get_instance


	protected function init_plugin() {

		$this->add_post_types();

		include_once people_get_plugin_dir_path() . '/includes/include-cahnrswsuwp-content-syndicate.php';

		include __DIR__ . '/include-post-feed.php';

	} // End init_plugin


	protected function add_post_types() {

		$plugin_dir_path = people_get_plugin_dir_path();

		include_once $plugin_dir_path . '/post-types/profile/profile-post-type.php';

	} // End add_post_types

} // End CAHNRSWSUWP_Plugin_People
