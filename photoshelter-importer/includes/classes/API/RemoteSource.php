<?php
/**
 * PhotoShelter Remote Source abstract class.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\API;

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
	 * API Auth token.
	 *
	 * @var string
	 */
	protected $auth_token;

	/**
	 * RemoteSource constructor.
	 *
	 * @param string $api_key API Key string.
	 * @param string $auth_token API Auth Token string.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $api_key = '', $auth_token = '' ) {
		$this->api_base   = constant( 'PHOTOSHELTER_API_BASE' );
		$this->api_key    = $api_key;
		$this->auth_token = $auth_token;
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
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	protected function api_request_get( $path, $args = array() ) {
		$url = $this->api_base . $path;

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
	 * @param string $url The URL to request.
	 * @param array  $args Array of arguments. See wp_remote_get().
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	protected function remote_request( $url, $args = array() ) {
		$headers = array(
			'Accept'        => 'application/json',
			'Cache-Control' => 'no-store, private',
			'Content-Type'  => 'application/json; charset=UTF-8',
			'pragma'        => 'no-cache',
			'User-Agent'    => 'PS-WordPress-Plugin',
		);

		if ( ! empty( $this->api_key ) ) {
			$headers['X-PS-Api-Key'] = $this->api_key;
		}

		if ( ! empty( $this->auth_token ) ) {
			$headers['Authorization']   = sprintf( 'Bearer %s', $this->auth_token );
			$headers['X-PS-Auth-Token'] = $this->auth_token;
		}

		$default_args = array(
			'headers' => $headers,
			'method'  => 'GET',
			'timeout' => 30,
		);

		$args = wp_parse_args( $args, $default_args );

		return wp_remote_request( $url, $args );
	}
}
