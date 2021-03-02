<?php
/**
 * PhotoShelter OAuth Client.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\API\OAuth;

use PhotoShelter\Importer\API\ClientRemote;
use function PhotoShelter\Importer\REST_API\get_importer_auth_rest_url;

/**
 * Class Client
 */
class Client extends ClientRemote {
	/**
	 * Register application with OAuth.
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	public function register_application( $args = array() ) {
		$default_args = array(
			'application_name'   => '',
			'company_name'       => 'PhotoShelter',
			'description'        => '',
			'email'              => 'support@photoshelter.com',
			'homepage_uri'       => 'https://www.photoshelter.com',
			'logo_uri'           => '', // Leave empty for now.
			'name'               => '',
			'privacy_policy_uri' => 'https://www.photoshelter.com/support/privacy',
			'redirect_uri'       => get_importer_auth_rest_url(),
			'scopes'             => '', // Leave empty for now.
		);

		$args = array_filter( wp_parse_args( $args, $default_args ) );

		$response = $this->get_data_source()->register( $args );

		return $this->handle_response( $response );
	}

	/**
	 * Get OAuth token.
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	public function get_token( array $args ) {
		$default_args = array(
			'code'         => '',
			'redirect_uri' => '',
		);

		$args = array_filter( wp_parse_args( $args, $default_args ) );

		$response = $this->get_data_source()->token( $args );

		return $this->handle_response( $response );
	}
}
