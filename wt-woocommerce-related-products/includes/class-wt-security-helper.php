<?php

/**
 * Webtoffee Security Library
 *
 * Includes Data sanitization, Access checking
 * @author WebToffee <info@webtoffee.com>
 */

if(!class_exists('Wt_Related_Product_Security_Helper'))
{

	class Wt_Related_Product_Security_Helper 
	{
		/**
		 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
		 * Non-scalar values are ignored.
		 * 
		 * @since 1.4.0
		 * @param string|array $var Data to sanitize.
		 * @return string|array
		 */
		public static function crp_sanitize_text( $var ) {
			if ( is_array( $var ) ) {
				return array_map( 'self::crp_sanitize_text', $var );
			} else {
				return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
			}
		}
		
	}
}