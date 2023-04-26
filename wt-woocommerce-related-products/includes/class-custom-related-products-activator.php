<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Custom_Related_Products
 * @subpackage Custom_Related_Products/includes
 * @author     markhf
 */
class Custom_Related_Products_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */



	public static function activate() {
		
		set_transient( '_crp_screen_activation_redirect', true, 30 );
                add_option( 'crp_version', Custom_Related_Products::VERSION);
         

	}

	

}
