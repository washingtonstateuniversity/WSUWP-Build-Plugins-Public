<?php
/**
 * PhotoShelter Media DataSource.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\API\Media;

use PhotoShelter\Importer\API\RemoteSource;
use function PhotoShelter\Importer\REST_API\get_api_key;
use function PhotoShelter\Importer\REST_API\get_oauth_access_token;

/**
 * Class DataSource
 */
class DataSource extends RemoteSource {
	/**
	 * Download media.
	 *
	 * @see https://engineering.photoshelter.com/psapi-v4-doc/#operation/mediaDownloadGetId
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

		$args = wp_parse_args( $args, $default_args );

		$required_args = array(
			'file_type' => 1,
			'media_id'  => 1,
		);

		$missing_required_fields = array_diff_key( $required_args, $args );

		if ( count( $missing_required_fields ) ) {
			return new \WP_Error(
				'photoshelter-importer-api-media-download-missing-fields',
				sprintf(
					/* translators: %1$s: list of required fields */
					__( 'Media\download_media() is missing the following required fields: %1$s', 'photoshelter-importer' ),
					implode( ', ', array_keys( $missing_required_fields ) )
				)
			);
		}

		return $this->api_request_get(
			add_query_arg(
				array(
					'download_filetype' => $args['file_type'],
					'download_quality'  => $args['file_quality'],
				),
				sprintf( 'media/%s/download', $args['media_id'] )
			)
		);
	}

	/**
	 * Get Media by media id.
	 *
	 * @param array $args Array of arguments.
	 *
	 * @return array|\WP_Error
	 * @since 1.0.0
	 */
	public function get_media( $args = array() ) {
		$default_args = array(
			'media_id' => '',
		);

		$args = array_filter( wp_parse_args( $args, $default_args ) );

		$required_args = array(
			'media_id' => 1,
		);

		$missing_required_fields = array_diff_key( $required_args, $args );

		if ( count( $missing_required_fields ) ) {
			return new \WP_Error(
				'photoshelter-importer-api-get-media-missing-fields',
				sprintf(
					/* translators: %1$s: list of required fields */
					__( 'Media\get_media() is missing the following required fields: %1$s', 'photoshelter-importer' ),
					implode( ', ', array_keys( $missing_required_fields ) )
				)
			);
		}

		return $this->api_request_get(
			sprintf( 'media/%s', $args['media_id'] ),
			array()
		);
	}
}
