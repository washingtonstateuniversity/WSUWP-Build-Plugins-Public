<?php
/*
Plugin Name: WSUWP Maps
Version: 0.2.0
Plugin URI: http://web.wsu.edu
Description: A shortcode to display an embedded map from maps.wsu.edu.
Author: washingtonstateuniversity, jeremyfelt
Author URI: http://web.wsu.edu
*/

class WSUWP_Maps {

	/**
	 * @var string Current version of this plugin.
	 */
	var $plugin_version = '0.2.0';

	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_shortcode( 'wsuwp_map', array( $this, 'display_map' ) );
	}

	/**
	 * Handle the supplied shortcode to display a WSU map.
	 */
	public function display_map( $attributes ) {
		$defaults = array(
			'size' => 'medium',
			'id' => '',
			'alias' => '',
			'width' => '',
			'height' => '',
		);
		$att = shortcode_atts( $defaults, $attributes );

		if ( '' !== $att['id'] ) {
			$map_url = 'http://map.wsu.edu/t/' . sanitize_key( $att['id'] );
		} elseif ( '' !== $att['alias'] ) {
			$map_url = 'http://map.wsu.edu/rt/' . sanitize_key( $att['alias'] ) . '?mode=standalone';
		} else {
			$map_url = 'http://map.wsu.edu/t/942CFE9C'; // Default to the WSU label.
		}

		if ( 'small' === $att['size'] ) {
			$x = 214;
			$y = 161;
		} elseif ( 'medium' === $att['size'] ) {
			$x = 354;
			$y = 266;
		} elseif ( 'large' === $att['size'] ) {
			$x = 495;
			$y = 372;
		} elseif ( 'largest' === $att['size'] ) {
			$x = 731;
			$y = 549;
		} else {
			$x = 354;
			$y = 266;
		}

		if ( '' !== $att['width'] ) {
			$x = absint( $att['width'] );
		}

		if ( '' !== $att['height'] )  {
			$y = absint( $att['height'] );
		}

		$html = '<iframe width="' . $x . '" height="' . $y . '" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' . esc_url( $map_url ) . '" ></iframe>';

		return $html;
	}
}
new WSUWP_Maps();