<?php
/**
 * Product Reviews
 *
 * Functions for displaying product reviews data meta box.
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin/Meta Boxes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Meta_Box_Product_Reviews
 */
class WC_Meta_Box_Product_Reviews {

	/**
	 * Output the metabox
	 *
	 * @param object $comment
	 */
	public static function output( $comment ) {
		wp_nonce_field( 'woocommerce_save_data', 'woocommerce_meta_nonce' );

		$current = get_comment_meta( $comment->comment_ID, 'rating', true );
		?>
		<select name="rating" id="rating">
			<?php for ( $rating = 1; $rating <= 5; $rating ++ ) {
				printf( '<option value="%1$s"%2$s>%1$s</option>', $rating, selected( $current, $rating, false ) );
			} ?>
		</select>
		<?php
	}

	/**
	 * Save meta box data
	 *
	 * @param mixed $data
	 *
	 * @return mixed
	 */
	public static function save( $data ) {
		// Not allowed, return regular value without updating meta
		if ( ! wp_verify_nonce( $_POST['woocommerce_meta_nonce'], 'woocommerce_save_data' ) && ! isset( $_POST['rating'] ) ) {
			return $data;
		}

		if ( $_POST['rating'] > 5 || $_POST['rating'] < 0 ) {
			return $data;
		}

		$comment_id = $data['comment_ID'];

		// Update meta
		update_comment_meta(
			$comment_id,
			'rating',
			intval( $_POST['rating'] )
		);

		// Return regular value after updating
		return $data;
	}
}
