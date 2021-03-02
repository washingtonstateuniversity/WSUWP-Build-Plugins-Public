<?php
/**
 * PhotoShelter Organization DataSource.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\APIv3\Organization;

use PhotoShelter\Importer\APIv3\RemoteSource;

/**
 * Class DataSource
 */
class DataSource extends RemoteSource {

	/**
	 * Get Organization.
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	public function get_organization( $args = array() ) {
		$default_args = array(
			'org_id' => '',
		);

		$args = array_filter( wp_parse_args( $args, $default_args ) );

		$required_args = array(
			'org_id' => 1,
		);

		$missing_required_fields = array_diff_key( $required_args, $args );

		if ( count( $missing_required_fields ) ) {
			return new \WP_Error(
				'photoshelter-importer-api-organization-register-missing-fields',
				sprintf(
					/* translators: %1$s: list of required fields */
					__( 'OAuth\get_organization() is missing the following required fields: %1$s', 'photoshelter-importer' ),
					implode( ', ', array_keys( $missing_required_fields ) )
				)
			);
		}

		$body = $args;

		$response = $this->api_request_get(
			sprintf( 'organization/%s', $args['org_id'] ),
			compact( 'body' )
		);

		return $response;
	}

	/**
	 * Gets OAuth token.
	 *
	 * @see https://engineering.photoshelter.com/psapi-v4-doc/#operation/oAuthToken
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	public function token( $args = array() ) {
		$default_args = array(
			'code'         => '',
			'grant_type'   => 'authorization_code',
			'redirect_uri' => '',
		);

		$args = array_filter( wp_parse_args( $args, $default_args ) );

		$required_args = array(
			'code'         => 1,
			'grant_type'   => 1,
			'redirect_uri' => 1,
		);

		$missing_required_fields = array_diff_key( $required_args, $args );

		if ( count( $missing_required_fields ) ) {
			return new \WP_Error(
				'photoshelter-importer-api-oauth-token-missing-fields',
				sprintf(
					/* translators: %1$s: list of required fields */
					__( 'OAuth\token() is missing the following required fields: %1$s', 'photoshelter-importer' ),
					implode( ', ', array_keys( $missing_required_fields ) )
				)
			);
		}

		$body = $args;

		return $this->api_request_post(
			'oauth/token',
			compact( 'body' )
		);
	}
}
