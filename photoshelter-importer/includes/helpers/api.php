<?php
/**
 * API helpers.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\Helper\API;

/**
 * Error response formatter.
 *
 * @param \WP_Error $error WordPress error object.
 * @param integer   $status Error status code.
 *
 * @return \WP_REST_Response|\WP_Error
 * @since 1.0.0
 */
function error_response( $error, $status ) {
	if ( is_wp_error( $error ) ) {
		return new \WP_REST_Response(
			array(
				'code'    => $error->get_error_code(),
				'message' => sprintf(
					/* translators: %s is the error message */
					__( 'API Response error: %s', 'photoshelter-importer' ),
					$error->get_error_message()
				),
				'data'    => compact( 'status' ),
			)
		);
	}

	return $error;
}
