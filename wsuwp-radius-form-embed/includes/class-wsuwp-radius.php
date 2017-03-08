<?php

class WSUWP_Radius {
	/**
	 * @var WSUWP_Radius
	 */
	private static $instance;

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.0.1
	 *
	 * @return \WSUWP_Radius
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSUWP_Radius();
			self::$instance->setup_hooks();
		}
		return self::$instance;
	}

	/**
	 * Setup hooks to include.
	 *
	 * @since 0.0.1
	*/
	public function setup_hooks() {
		add_shortcode( 'radius_form', array( $this, 'radius_form_shortcode' ) );
	}

	/* [radius_form title url height] */
	public function radius_form_shortcode( $atts ) {
		$a = shortcode_atts( array(
			'url' => '',
			'height' => '20',
			'title' => 'WSU Online contact form',
		), $atts );

		if ( preg_match( '/^https\:\/\/wsuonline\.hobsonsradius\.com\/ssc\/[a-z]{3,8}\/[A-Za-z_0-9]{9,25}\.ssc$/', $a['url'] ) ) {
			$url = esc_url( $a['url'] );
			$height = absint( $a['height'] );
			$title = esc_attr( $a['title'] );
			return "<iframe scrolling='no' title='{$title}'; src='{$url}' style='width:100%; height:{$height}vh;'></iframe>";
		} else {
			return '<p>Unable to import the form. Please use the correct format: [radius_form url="https://wsuonline..." title="form title" height="150"] </p>';
		}
	}
}
