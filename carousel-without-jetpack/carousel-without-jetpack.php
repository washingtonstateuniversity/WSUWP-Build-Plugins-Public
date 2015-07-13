<?php
/* 
Plugin Name: Gallery Carousel Without JetPack
Plugin URI: http://www.wpbeginner.com/
Description: Transform your standard galleries into an immersive full-screen experience without requiring you to connect to WordPress.com
Version: 0.7.4
Author: Syed Balkhi
Author URI: http://www.wpbeginner.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


/**
* This is a fork of Carousel Module from JetPack. I just wanted the Carousel to work
* without logging into WordPress.com because I shouldn't be forced to (that's evil). So I'm releasing
* this little plugin which is exactly the copy of JetPack module. I will update this plugin everytime that JetPack updates.
*/

/**
* Boostrap 'carousel' module so it'll work standalone
*/
class CarouselWithoutJetpack {
	/**
	* Constructor
	*/
	function CarouselWithoutJetpack() {
		// Plugin Details
        $this->plugin = new stdClass;
        $this->plugin->name = 'carousel-without-jetpack'; // Plugin Folder
        $this->plugin->folder = WP_PLUGIN_DIR.'/'.$this->plugin->name; // Full Path to Plugin Folder
        $this->plugin->url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); // Full URL to Plugin Folder

		// Include class.jetpack-options.php
		// Ignore if Jetpack or another plugin has already done this
		if (!class_exists('No_Jetpack_Options')) {
			require_once($this->plugin->folder.'/carousel/class.jetpack-options.php');
		}
		
		// Include No_Jetpack_Carousel
		// Ignore if Jetpack or another plugin has already done this
		if (!class_exists('No_Jetpack_Carousel')) {
			require_once($this->plugin->folder.'/carousel/jetpack-carousel.php');
		}		
		
		add_action('wp_enqueue_scripts', array(&$this, 'frontendScriptsAndCSS'));
		add_action('plugins_loaded', array(&$this, 'loadLanguageFiles'));
	}
	
	/**
	* Enqueue jQuery Spin
	*/
	function frontendScriptsAndCSS() {
		wp_register_script( 'spin', plugins_url( 'carousel/spin.js', __FILE__ ), false );
		wp_register_script( 'jquery.spin', plugins_url( 'carousel/jquery.spin.js', __FILE__ ) , array( 'jquery', 'spin' ) );
	}

	/**
	* Load translations
	*/
	function loadLanguageFiles() {
		load_plugin_textdomain('carousel', false, basename( dirname( __FILE__ ) ) . '/languages' );		
	}
}

new CarouselWithoutJetpack;