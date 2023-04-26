<?php

/**
 * Related Products - Functions
 *
 * Functions for import/export things.
 *
 * @package Custom_Related_Products
 * @since 1.4.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wt_list_to_serialized' ) ) {

	/**
     * Serialize data when importing using WP All Import.
     * @since 1.4.2
     */
	function wt_list_to_serialized( $value, $type = '' ) {

		// Split the list at the commas.
		$value = explode(',', $value);
		if( $type == 'attribute' ) {
			$value = ( method_exists('Custom_Related_Product_Import_Export', 'process_related_attr_for_import') ) ? 
			Custom_Related_Product_Import_Export::process_related_attr_for_import( $value ) : $value;
		}
		// Return the serialized list.
		return serialize( $value );

	}

}	

if ( ! function_exists( 'wt_crp_is_multi_array' ) ) {

	/**
     * Check whether an array is a multidimensional array.
     * @since 1.4.2
     */
	function wt_crp_is_multi_array( $arr ) {
		rsort( $arr );

    	return isset( $arr[0] ) && is_array( $arr[0] );
	}

}	

if ( ! function_exists( 'wt_data_deserialize' ) ) {

	/**
     * Deserialize data when exporting using WP All Export.
     * @since 1.4.2
     */
	function wt_data_deserialize( $value ) {

		$result = '';
		if( !empty($value) ) {
			$unserialised = maybe_unserialize( $value );
			if( is_array($unserialised) && !wt_crp_is_multi_array($unserialised) ) {
				return implode( ",", $unserialised );
			}
		}

		return $result;
	}

}	


