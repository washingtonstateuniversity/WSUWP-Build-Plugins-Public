<?php
/**
 * PhotoShelter Remote Client abstract class.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\APIv3;

/**
 * Class Abstract RemoteSource
 */
abstract class ClientRemote {
	/**
	 * Data source instance.
	 *
	 * @var RemoteSource
	 */
	protected $data_source;

	/**
	 * Client constructor.
	 *
	 * @param RemoteSource $data_source RemoteSource instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct( RemoteSource $data_source ) {
		$this->data_source = $data_source;
	}

	/**
	 * Get the DataSource instance.
	 *
	 * @return RemoteSource
	 * @since 1.0.0
	 */
	public function get_data_source() {
		return $this->data_source;
	}

	/**
	 * Handle response from wp_remote_request().
	 *
	 * @param array   $response Response array.
	 * @param boolean $decode_json Whether to decode JSON (default = true).
	 *
	 * @return mixed|\WP_Error
	 * @since 1.0.0
	 */
	protected function handle_response( $response, $decode_json = true ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_body = wp_remote_retrieve_body( $response );

		if ( true === $decode_json ) {
			$response_body = json_decode( $response_body );
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		switch ( $response_code ) {
			case 200:
				return $response_body;
			case 400: // Bad request.
			case 404: // Not found.
			case 500: // Malformed request.
			default:
				if ( isset( $response_body->error ) ) {
					return new \WP_Error(
						'photoshelter-api-v3-bad-response',
						sprintf(
							/* translators: %1$d: Numeric HTTP status code (e.g. 400, 403, 500, etc.), %2$s Error message, if any. */
							__( 'Bad response from API (%1$d): %2$s', 'photoshelter-importer' ),
							$response_code,
							$response_body->error->message
						),
						$response_body
					);
				}

				return new \WP_Error(
					'photoshelter-api-v3-bad-response',
					sprintf(
						/* translators: %1$d: Numeric HTTP status code (e.g. 400, 403, 500, etc.), %2$s Error message, if any. */
						__( 'Bad response from API (%1$d): %2$s', 'photoshelter-importer' ),
						$response_code,
						$response_body->errors[0]->title
					),
					$response_body
				);
		}
	}
}
