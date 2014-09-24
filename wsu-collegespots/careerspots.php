<?php
/*
Plugin Name: WSU CareerSpots Shortcode
Plugin URI: http://web.wsu.edu
Description: Display a CareerSpots iframe using a shortcode.
Author: washingtonstateuniversity, jeremyfelt
Version: 0.0.1
*/

class WSU_CareerSpots {
	/**
	 * Setup hooks and shortcodes.
	 */
	public function __construct() {
		add_shortcode( 'careerspots', array( $this, 'display_careerspots_shortcode' ) );
	}

	/**
	 * Process the requested attributes and display a CollegeSpots iframe.
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function display_careerspots_shortcode( $atts ) {
		$defaults = array(
			'key' => '',
			'title' => 'CareerSpots.com Video',
		    'width' => 210,
		    'height' => 170,
		);
		$atts = shortcode_atts( $defaults, $atts );

		$atts['key'] = preg_replace( '/[^a-zA-Z0-9_\-]/', '', $atts['key'] );
		$iframe_src = 'https://www.careerspots.com/secure/SpotlightVideo.aspx?key=' . $atts['key'];

		$atts['height'] = str_replace( 'px', '', $atts['height'] );
		$atts['width'] = str_replace( 'px', '', $atts['width'] );

		$output = '<iframe src="' . esc_url( $iframe_src ) . '" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"
						style="width: ' . absint( $atts['width'] ) . 'px; height: ' . absint( $atts['height'] ) . 'px;"
						title="' . esc_attr( $atts['title'] ) . '"></iframe>';

		return $output;
	}
}
new WSU_CareerSpots();