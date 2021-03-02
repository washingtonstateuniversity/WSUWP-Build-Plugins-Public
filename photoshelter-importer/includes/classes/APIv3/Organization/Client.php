<?php
/**
 * PhotoShelter Organization Client.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\APIv3\Organization;

use PhotoShelter\Importer\APIv3\ClientRemote;

/**
 * Class Client
 */
class Client extends ClientRemote {
	/**
	 * Get organization object.
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	public function get_organization( $args = array() ) {
		$response = $this->get_data_source()->get_organization( $args );

		return $this->handle_response( $response );
	}

	/**
	 * Get organization name.
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 */
	public function get_organization_name( $args = array() ) {
		$response = $this->get_organization( $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return $response->data->Organization->name;
	}
}
