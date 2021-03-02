<?php
/**
 * PhotoShelter Auth Client.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\API\Auth;

use PhotoShelter\Importer\API\ClientRemote;

/**
 * Class Client
 */
class Client extends ClientRemote {

	/**
	 * Authenticate organization
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

		$response = $this->get_data_source()->authenticate_organization( $args );

		return $this->handle_response( $response );
	}

	/**
	 * Log out of organization
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	public function deauthenticate_organization( $args = array() ) {
		$default_args = array();

		$args = array_filter( wp_parse_args( $args, $default_args ) );

		$response = $this->get_data_source()->deauthenticate_organization( $args );

		return $this->handle_response( $response );
	}
}
