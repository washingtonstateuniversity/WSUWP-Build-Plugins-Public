<?php
/**
 * PhotoShelter Auth DataSource.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\API\Auth;

use PhotoShelter\Importer\API\RemoteSource;

/**
 * Class DataSource
 */
class DataSource extends RemoteSource {

	/**
	 * Authenticate with organization.
	 *
	 * @see https://engineering.photoshelter.com/psapi-v4-doc/#operation/orgAuthenticate
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	public function authenticate_organization( $args = array() ) {
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
				'photoshelter-importer-api-auth-org-missing-fields',
				sprintf(
					/* translators: %1$s: list of required fields */
					__( 'Auth\authenticate_organization() is missing the following required fields: %1$s', 'photoshelter-importer' ),
					implode( ', ', array_keys( $missing_required_fields ) )
				)
			);
		}

		$body = $args;

		return $this->api_request_post(
			'organization/authenticate',
			compact( 'body' )
		);
	}

	/**
	 * De-authenticate (log out of) organization.
	 *
	 * @see https://engineering.photoshelter.com/psapi-v4-doc/#operation/orgLogout
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	public function deauthenticate_organization( $args = array() ) {
		$default_args = array();

		$args = array_filter( wp_parse_args( $args, $default_args ) );

		$required_args = array();

		$missing_required_fields = array_diff_key( $required_args, $args );

		if ( count( $missing_required_fields ) ) {
			return new \WP_Error(
				'photoshelter-importer-api-auth-org-logout-missing-fields',
				sprintf(
					/* translators: %1$s: list of required fields */
					__( 'Auth\deauthenticate_organization() is missing the following required fields: %1$s', 'photoshelter-importer' ),
					implode( ', ', array_keys( $missing_required_fields ) )
				)
			);
		}

		$body = $args;

		return $this->api_request_post(
			'organization/logout',
			compact( 'body' )
		);
	}
}
