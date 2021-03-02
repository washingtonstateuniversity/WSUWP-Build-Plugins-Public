<?php
/**
 * PhotoShelter Remote Source abstract class.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\APIv3;

/**
 * Class Abstract RemoteSource
 */
abstract class RemoteSource {
	/**
	 * Base URL for OAuth endpoint.
	 *
	 * @var string
	 */
	protected $api_base;

	/**
	 * API Key.
	 *
	 * @var string
	 */
	protected $api_key;

	/**
	 * RemoteSource constructor.
	 *
	 * @param string $api_key API Key string.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $api_key = '' ) {
		$this->api_base = constant( 'PHOTOSHELTER_API_V3_BASE' );
		$this->api_key  = $api_key;
	}

	/**
	 * Do a remote POST request for PATH and request arguments.
	 *
	 * @param string $path The PATH to request.
	 * @param array  $args Array of arguments. See wp_remote_get().
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	protected function api_request_post( $path, $args = array() ) {
		$url = $this->api_base . $path;

		// Force method to POST.
		$args['method'] = 'POST';

		// Ensure we encode the body.
		$args['body'] = wp_json_encode( $args['body'] );

		return $this->remote_request(
			$url,
			$args
		);
	}

	/**
	 * Do a remote GET request for PATH and request arguments.
	 *
	 * @param string $path The PATH to request.
	 * @param array  $args Array of arguments. See wp_remote_get().
	 * @param array  $params Params array.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	protected function api_request_get( $path, $args = array(), $params = array() ) {
		$query_args = array_replace(
			array(
				'api_key' => PHOTOSHELTER_API_V3_KEY,
			),
			$params
		);

		$url = add_query_arg( $query_args, $this->api_base . $path );

		return $this->remote_request(
			$url,
			$args
		);
	}

	/**
	 * Do a remote request for URL and request arguments.
	 *
	 * NOTE: This is an override of wp_remote_request so it is mockable/testable.
	 *
	 * @param string $url  The URL to request.
	 * @param array  $args Array of arguments. See wp_remote_get().
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	protected function remote_request( $url, $args = array() ) {
		$headers = array(
			'Cache-Control' => 'no-store, private',
			'Content-Type'  => 'application/json; charset=UTF-8',
			'pragma'        => 'no-cache',
			'User-Agent'    => 'PS-WordPress-Plugin',
		);

		$default_args = array(
			'headers' => $headers,
			'method'  => 'GET',
			'timeout' => 30,
		);

		$args = wp_parse_args( $args, $default_args );

		return wp_remote_request( $url, $args );
	}
}
