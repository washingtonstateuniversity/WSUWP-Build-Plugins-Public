<?php
/*
Plugin Name: WSUWP Video Backgrounds
Version: 0.1.0
Plugin URI: http://web.wsu.edu
Description: A WordPress plugin to display HTML5 video backgrounds.
Author: washingtonstateuniversity, jeremyfelt
Author URI: http://web.wsu.edu
*/

class WSU_Video_Background {
	/**
	 * @var string Current version of the Javscript for cache breaking.
	 */
	var $script_version = '0.1.0';

	/**
	 * Setup plugin hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_shortcode( 'wsu_video_background', array( $this, 'video_background' ) );
	}

	/**
	 * Register the scripts used by the shortcode.
	 */
	public function enqueue_scripts() {
		wp_register_script( 'wsu-videobg-jquery', plugins_url( 'js/jquery.videobg.js', __FILE__ ), array( 'jquery' ), $this->script_version, true );
		wp_register_script( 'wsu-videobg', plugins_url( 'js/wsuwp-videobg.js', __FILE__ ), array( 'wsu-videobg-jquery' ), $this->script_version, true );
	}

	/**
	 * Display a video background container.
	 *
	 * @param array  $atts    List of attributes to use in the shortcode.
	 * @param string $content Content provided between shortcode tags.
	 *
	 * @return string
	 */
	public function video_background( $atts, $content = null ) {
		$defaults = array(
			'id'          => 'wsu-video-background',
			'mp4'         => '',
			'ogv'         => '',
			'webm'        => '',
			'poster'      => '',
			'autoplay'    => true,
			'loop'        => true,
			'scale'       => true,
			'zIndex'      => 0,
			'opacity'     => 1,
			'script_only' => false,
		);
		$atts = shortcode_atts( $defaults, $atts );

		ob_start();

		// By default, create the container for the video background.
		if ( false === $atts['script_only'] ) {
			?><div id="<?php echo esc_attr( $atts['id'] ); ?>"><?php if ( null !== $content ) { echo apply_filters( 'the_content', $content ); } ?></div><?php
		}

		$content = ob_get_contents();
		ob_end_clean();

		$wsu_video_background = array(
			'id' => esc_attr( $atts['id'] ),
			'mp4' => esc_url( $atts['mp4'] ),
			'ogv' => esc_url( $atts['ogv'] ),
			'webm' => esc_url( $atts['webm'] ),
			'poster' => esc_url( $atts['poster'] ),
			'scale' => (bool) $atts['scale'],
			'zIndex' => intval( $atts['zIndex'] )
		);
		wp_localize_script( 'wsu-videobg', 'wsu_video_background', $wsu_video_background );
		wp_enqueue_script( 'wsu-videobg' );

		return $content;
	}
}
new WSU_Video_Background();