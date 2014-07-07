<?php
/*
Plugin Name: WSUWP Table of Contents Generator
Version: 0.3.0
Plugin URI: http://web.wsu.edu
Description: A shortcode to generate a DOM element containing the table of contents for a long page.
Author: washingtonstateuniversity, jeremyfelt
Author URI: http://web.wsu.edu
*/

class WSUWP_TOC_Generator {

	/**
	 * @var string Current version of this plugin.
	 */
	var $plugin_version = '0.3.0';

	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_shortcode( 'wsuwp_toc', array( $this, 'display_toc' ) );
	}

	/**
	 * Enqueue the scripts used to display the table of contents.
	 */
	public function display_toc( $attributes ) {
		$attributes = shortcode_atts( array( 'position' => 'content' ), $attributes );

		if ( ! in_array( $attributes['position'], array( 'content', 'bottom' ) ) ) {
			$attributes['position'] = 'content';
		}

		wp_enqueue_script( 'toc-jquery', plugins_url( 'js/toc.min.js', __FILE__ ), array( 'jquery' ), $this->plugin_version, true );
		wp_enqueue_script( 'wsuwp-toc-generator', plugins_url( 'js/wsuwp-toc-generator.js', __FILE__ ), array( 'toc-jquery', 'jquery' ), $this->plugin_version, true );

		if ( 'content' === $attributes['position'] ) {
			return '<div id="toc"></div>';
		}

		add_action( 'wp_footer', array( $this, 'footer_display_toc' ) );

		return '';
	}

	/**
	 * Display the TOC element at the end of the page for custom positioning.
	 */
	public function footer_display_toc() {
		echo '<div id="toc"></div>';
	}
}
new WSUWP_TOC_Generator();