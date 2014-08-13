<?php
/*
Plugin Name: WSUWP Table of Contents Generator
Version: 0.4.1
Plugin URI: http://web.wsu.edu
Description: A shortcode to generate a DOM element containing the table of contents for a long page.
Author: washingtonstateuniversity, jeremyfelt
Author URI: http://web.wsu.edu
*/

class WSUWP_TOC_Generator {

	/**
	 * @var string Current version of this plugin.
	 */
	var $plugin_version = '0.4.1';

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
		$defaults = array(
			'position' => 'content',
			'headers' => 'h1,h2,h3,h4',
		);
		$attributes = shortcode_atts( $defaults, $attributes );

		if ( ! in_array( $attributes['position'], array( 'content', 'bottom' ) ) ) {
			$attributes['position'] = 'content';
		}

		$headers = explode( ',', $attributes['headers'] );
		$headers = array_filter( $headers, array( $this, 'clean_headers' ) );
		$headers = implode( ',', $headers );

		wp_enqueue_script( 'toc-jquery', plugins_url( 'js/toc.min.js', __FILE__ ), array( 'jquery' ), $this->plugin_version, true );
		wp_register_script( 'wsuwp-toc-generator', plugins_url( 'js/wsuwp-toc-generator.js', __FILE__ ), array( 'toc-jquery', 'jquery' ), $this->plugin_version, true );
		wp_localize_script( 'wsuwp-toc-generator', 'WSUWP_TOC', array( 'selectors' => $headers ) );
		wp_enqueue_script( 'wsuwp-toc-generator' );

		if ( 'content' === $attributes['position'] ) {
			return '<div id="toc" class="toc toc-generated"></div>';
		}

		add_action( 'wp_footer', array( $this, 'footer_display_toc' ) );

		return '';
	}

	/**
	 * Filter the list of provided header sizes to remove any that are invalid.
	 *
	 * @param array $header Array of header sizes.
	 *
	 * @return bool True if valid. False if not.
	 */
	public function clean_headers( $header ) {
		$header = trim( strtolower( $header ) );

		if ( in_array( $header, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Display the TOC element at the end of the page for custom positioning.
	 */
	public function footer_display_toc() {
		echo '<div id="toc" class="toc toc-generated"></div>';
	}
}
new WSUWP_TOC_Generator();
