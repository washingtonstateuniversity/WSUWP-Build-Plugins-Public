<?php
/*
Plugin Name: WSU Analytics
Version: 0.0.1
Plugin URI: http://web.wsu.edu
Description: Manages analytics for sites on the WSUWP Platform
Author: washingtonstateuniversity, jeremyfelt
Author URI: http://web.wsu.edu
*/

class WSU_Analytics {

	/**
	 * @var string The current version of this plugin, or used to break script cache.
	 */
	var $version = '0.0.1';

	/**
	 * Add our hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'display_settings' ) );
	}

	/**
	 * Register the settings fields that will be output for this plugin.
	 */
	public function display_settings() {
		register_setting( 'general', 'wsuwp_ga_id', array( $this, 'sanitize_ga_id' ) );
		add_settings_field( 'wsuwp-ga-id', 'Google Analytics ID', array( $this, 'general_settings_ga_id'), 'general', 'default', array( 'label_for' => 'wsuwp_ga_id' ) );
	}

	/**
	 * Make sure what we're seeing looks like a Google Analytics tracking ID.
	 *
	 * @param string $ga_id The inputted Google Analytics ID.
	 *
	 * @return string Sanitized Google Analytics ID.
	 */
	public function sanitize_ga_id( $ga_id ) {
		// trim spaces, uppercase UA, explode to 3 piece array
		$ga_id = explode( '-', trim( strtoupper( $ga_id ) ) );

		if ( empty( $ga_id ) || 'UA' !== $ga_id[0] ) {
			return false;
		}

		if ( isset( $ga_id[1] ) ) {
			$ga_id[1] = preg_replace( '/[^0-9]/', '', $ga_id[1] );
		}

		if ( isset( $ga_id[2] ) ) {
			$ga_id[2] = preg_replace( '/[^0-9]/', '', $ga_id[2] );
		}

		$ga_id = implode( '-', $ga_id );

		return $ga_id;
	}

	/**
	 * Display a field to capture the site's Google Analytics ID.
	 */
	public function general_settings_ga_id() {
		$google_analytics_id = get_option( 'wsuwp_ga_id', false );

		?><input id="wsuwp_ga_id" name="wsuwp_ga_id" value="<?php echo esc_attr( $google_analytics_id ); ?>" type="text" class="regular-text" /><?php
	}

	/**
	 * Enqueue the scripts used for analytics on the platform.
	 */
	public function enqueue_scripts() {
		// Look for a site level Google Analytics ID
		$google_analytics_id = get_option( 'wsuwp_ga_id', false );

		// If a site level ID does not exist, look for a network level Google Analytics ID
		if ( ! $google_analytics_id ) {
			$google_analytics_id = get_site_option( 'wsuwp_network_ga_id', false );
		}

		// If no GA ID exists, we can't reliably track visitors.
		if ( ! $google_analytics_id ) {
			return;
		}

		$site_details = get_blog_details();

		wp_enqueue_script( 'jquery-jtrack', 'https://repo.wsu.edu/jtrack/jquery.jTrack.0.2.1.js', array( 'jquery' ), $this->script_version(), true );
		wp_register_script( 'wsu-analytics-main', plugins_url( 'js/analytics.min.js', __FILE__ ), array( 'jquery-jtrack', 'jquery' ), $this->script_version(), true );

		$tracker_data = array(
			'tracker_id' => $google_analytics_id,
			'domain' => $site_details->domain,
		);

		wp_localize_script( 'wsu-analytics-main', 'wsu_analytics', $tracker_data );
		wp_enqueue_script( 'wsu-analytics-main' );
	}

	/**
	 * Compile a script version and include WSUWP Platform if possible.
	 *
	 * @return string Version to be attached to scripts.
	 */
	private function script_version() {
		if ( function_exists( 'wsuwp_global_version' ) ) {
			return wsuwp_global_version() . '-' . $this->version;
		}

		return $this->version;
	}
}
new WSU_Analytics();