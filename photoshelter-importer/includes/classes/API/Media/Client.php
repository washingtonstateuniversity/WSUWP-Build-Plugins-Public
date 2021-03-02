<?php
/**
 * PhotoShelter Media Client.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\API\Media;

use PhotoShelter\Importer\API\ClientRemote;


/**
 * Class Client
 */
class Client extends ClientRemote {
	/**
	 * Download media.
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error Array on success, \WP_Error on failure.
	 * @since 1.0.0
	 */
	public function download_media( $args = array() ) {
		$default_args = array(
			'file_type'    => '',
			'file_quality' => 'original',
			'media_id'     => '',
		);

		$args = array_filter( wp_parse_args( $args, $default_args ) );

		$response = $this->get_data_source()->download_media( $args );

		return $this->handle_response( $response, false );
	}

	/**
	 * Get Media by media_id.
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|mixed|\WP_Error
	 * @since 1.0.0
	 */
	public function get_media( $args = array() ) {
		$default_args = array(
			'media_id' => '',
		);

		$args = array_filter( wp_parse_args( $args, $default_args ) );

		$response = $this->get_data_source()->get_media( $args );

		return $this->handle_response( $response );
	}
}
