<?php
/**
 * REST API Extension plugin functionality.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\REST_API;

use PhotoShelter\Importer\API\Media\Client as Media_Client;
use PhotoShelter\Importer\API\Media\DataSource as Media_DataSource;
use PhotoShelter\Importer\API\OAuth\Client as OAuth_Client;
use PhotoShelter\Importer\API\OAuth\DataSource as OAuth_DataSource;

use function PhotoShelter\Importer\Admin\admin_settings_url;
use function PhotoShelter\Importer\Helper\API\error_response;
use function PhotoShelter\Importer\Helper\File\get_mime_type;

// Constants.
define( 'PHOTOSHELTER_API_BASE', 'https://www.photoshelter.com/psapi/v4.0/' );
define( 'PHOTOSHELTER_API_NAMESPACE', 'photoshelter/v1' );
define( 'PHOTOSHELTER_API_V3_BASE', 'https://www.photoshelter.com/psapi/v3/' );
define( 'PHOTOSHELTER_API_V3_KEY', 'mob4GRwPkIc' );
define( 'PHOTOSHELTER_API_REGEX_KEY', '^[a-zA-Z0-9._]{11}$' );
define( 'PHOTOSHELTER_API_REGEX_COLLECTION_ID', 'C0000[a-zA-Z0-9._]{11}' );
define( 'PHOTOSHELTER_API_REGEX_GALLERY_ID', 'G0000[a-zA-Z0-9._]{11}' );
define( 'PHOTOSHELTER_API_REGEX_MEDIA_ID', '(AD|VD|DO|I0)000[a-zA-Z0-9._]{11}' );
define( 'PHOTOSHELTER_API_REGEX_ORG_ID', 'O0000[a-zA-Z0-9._]{11}' );

/**
 * Bootstrap.
 *
 * Things done on plugin initialization.
 *
 * @since 1.0.0
 */
function bootstrap() {
	add_action( 'rest_api_init', __NAMESPACE__ . '\\register_callback_routes' );
}

/**
 * Get Importer Auth REST Url string.
 *
 * @return string Rest URL.
 * @since 1.0.0
 */
function get_importer_auth_rest_url() {
	return get_rest_url( null, 'photoshelter/v1/importer/auth' );
}

/**
 * Get OAuth register endpoint URL.
 *
 * @return string
 * @since 1.0.0
 */
function get_oauth_register_endpoint_url() {
	return add_query_arg(
		array(
			'api_key'       => get_api_key(),
			'client_id'     => get_client_id(),
			'redirect_uri'  => get_importer_auth_rest_url(),
			'response_type' => 'code',
			'state'         => 'photoshelter',
		),
		'https://www.photoshelter.com/psapi/v4.0/oauth/authorize'
	);
}

/**
 * Get API key string.
 *
 * @return string
 * @since 1.0.0
 */
function get_api_key() {
	$options = get_option( 'photoshelter_importer_options', array() );

	return isset( $options['api_key'] ) ? $options['api_key'] : '';
}

/**
 * Get Org ID string.
 *
 * @return string
 * @since 1.0.0
 */
function get_org_id() {
	$options = get_option( 'photoshelter_importer_options', array() );

	return isset( $options['org_id'] ) ? $options['org_id'] : '';
}

/**
 * Get Org ID string.
 *
 * @return string
 * @since 1.0.0
 */
function get_org_name() {
	return get_option( 'photoshelter_importer_org_name', __( 'Library', 'photoshelter-importer' ) );
}

/**
 * Get Client ID.
 *
 * @return string
 * @since 1.0.0
 */
function get_client_id() {
	return get_option( 'photoshelter_importer_oauth_client_id', '' );
}

/**
 * Get Client ID.
 *
 * @return string
 * @since 1.0.0
 */
function get_client_secret() {
	return get_option( 'photoshelter_importer_oauth_client_secret', '' );
}

/**
 * Get OAuth access token.
 *
 * @return string
 * @since 1.0.0
 */
function get_oauth_access_token() {
	return get_option( 'photoshelter_importer_oauth_access_token', '' );
}

/**
 * Get OAuth access token expiration.
 *
 * @return string
 * @since 1.0.0
 */
function get_oauth_access_token_expiration() {
	return get_option( 'photoshelter_importer_oauth_access_token_expires', '' );
}

/**
 * Get OAuth refresh token.
 *
 * @return string
 * @since 1.0.0
 */
function get_oauth_refresh_token() {
	return get_option( 'photoshelter_importer_oauth_refresh_token', '' );
}

/**
 * Register Importer callback routes.
 *
 * @since 1.0.0
 */
function register_callback_routes() {
	register_rest_route(
		PHOTOSHELTER_API_NAMESPACE,
		'/importer/auth/',
		array(
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => __NAMESPACE__ . '\\handle_importer_auth_callback',
		)
	);

	register_rest_route(
		PHOTOSHELTER_API_NAMESPACE,
		'/importer/token/',
		array(
			'methods'             => \WP_REST_Server::EDITABLE,
			'callback'            => __NAMESPACE__ . '\\handle_importer_token_callback',
			'permission_callback' => __NAMESPACE__ . '\\permission_callback',
		)
	);

	register_rest_route(
		PHOTOSHELTER_API_NAMESPACE,
		'/importer/sideload/',
		array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => __NAMESPACE__ . '\\handle_importer_sideload',
			'permission_callback' => __NAMESPACE__ . '\\permission_callback',
		)
	);
}

/**
 * Permission callback.
 *
 * @param \WP_REST_Request $request Full details about the request.
 *
 * @return bool
 * @since 1.0.0
 */
function permission_callback( $request ) {
	return current_user_can( 'edit_others_posts' );
}

/**
 * Handle Importer Auth callback response.
 *
 * @param \WP_REST_Request $request REST API response.
 *
 * @since 1.0.0
 */
function handle_importer_auth_callback( \WP_REST_Request $request ) {
	$code = $request->get_param( 'code' );

	$oauth_client = new OAuth_Client( new OAuth_DataSource( get_api_key() ) );

	$token_response = $oauth_client->get_token(
		array(
			'client_id'     => get_client_id(),
			'client_secret' => get_client_secret(),
			'code'          => $code,
			'redirect_uri'  => get_importer_auth_rest_url(),
		)
	);

	if ( is_wp_error( $token_response ) ) {
		set_transient(
			'photoshelter_importer_token_set',
			array(
				'type'    => 'error',
				'message' => $token_response->get_error_message(),
			),
			HOUR_IN_SECONDS
		);
	} else {
		update_option( 'photoshelter_importer_oauth_access_token', $token_response->access_token );
		update_option( 'photoshelter_importer_oauth_access_token_expires', $token_response->expires_in );
		update_option( 'photoshelter_importer_oauth_refresh_token', $token_response->refresh_token );

		set_transient(
			'photoshelter_importer_token_set',
			array(
				'type'    => 'success',
				'message' => __( 'Access granted.', 'photoshelter-importer' ),
			),
			HOUR_IN_SECONDS
		);
	}

	// Redirect to our Settings page URL.
	wp_safe_redirect( admin_settings_url() );
	exit;
}

/**
 * Handle Importer Token callback response.
 *
 * @param \WP_REST_Request $request REST API request.
 *
 * @return \WP_REST_Response|\WP_Error
 * @since 1.0.0
 */
function handle_importer_token_callback( \WP_REST_Request $request ) {
	$token         = strval( $request->get_param( 'token' ) );
	$expires       = intval( $request->get_param( 'expires' ) );
	$refresh_token = strval( $request->get_param( 'refresh_token' ) );

	if ( ! $token || ! $expires || ! $refresh_token ) {
		return new \WP_Error(
			'bad-input',
			__( 'Unexpected input', 'photoshelter-importer' ),
			array(
				'status' => 500,
			)
		);
	}

	update_option( 'photoshelter_importer_oauth_access_token', $token );
	update_option( 'photoshelter_importer_oauth_access_token_expires', $expires );
	update_option( 'photoshelter_importer_oauth_refresh_token', $refresh_token );

	return new \WP_REST_Response(
		array(
			'code'    => 'success',
			'message' => __( 'OK', 'photoshelter-importer' ),
			'data'    => array(
				'status' => 200,
			),
		),
		200
	);
}

/**
 * Handle Importer Sideload callback response.
 *
 * @param \WP_REST_Request $request REST API request.
 *
 * @return \WP_Error|\WP_REST_Response
 * @since 1.0.0
 */
function handle_importer_sideload( \WP_REST_Request $request ) {
	/**
	 * WordPress filesystem.
	 *
	 * @var \WP_Filesystem_Direct
	 */
	global $wp_filesystem;

	require_once ABSPATH . '/wp-admin/includes/image.php';
	require_once ABSPATH . '/wp-admin/includes/media.php';

	// Initialize the WP filesystem.
	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		\WP_Filesystem();
	}

	$file_type        = strval( $request->get_param( 'file_type' ) );
	$file_type        = empty( $file_type ) ? 'original' : $file_type;
	$file_quality     = strval( $request->get_param( 'file_quality' ) );
	$media_id         = strval( $request->get_param( 'media_id' ) );
	$set_file_name    = strval( $request->get_param( 'name' ) );
	$set_file_alt     = strval( $request->get_param( 'alt' ) );
	$set_file_caption = strval( $request->get_param( 'caption' ) );

	$file_extension = pathinfo( $set_file_name, PATHINFO_EXTENSION );

	if ( 'original' !== $file_quality ) {
		if ( 'pdf' === $file_extension ) {
			$file_type = 'original';
		} elseif ( 'image' === $file_type ) {
			$file_type = 'jpeg';

			if ( 'original_size' === $file_quality ) {
				$file_quality = '';
			}
		} elseif ( 'video' === $file_type ) {
			$file_type = 'mp4';

			if ( 'original_size' === $file_quality ) {
				$file_quality = '';
			}
		} else {
			$file_type = 'original';
		}
	} else {
		$file_type = 'original';
	}

	if ( ! $media_id ) {
		return new \WP_Error(
			'bad-input',
			__( 'Unexpected input', 'photoshelter-importer' ),
			array(
				'status' => 500,
			)
		);
	}

	$media = new Media_Client( new Media_DataSource( get_api_key(), get_oauth_access_token() ) );

	$info = $media->get_media(
		array(
			'media_id'     => $media_id,
			'file_quality' => $file_quality,
		)
	);

	if ( is_wp_error( $info ) ) {
		return error_response( $info, 400 );
	}

	if ( ! property_exists( $info->data->attributes, 'file_name' ) ) {
		return new \WP_Error(
			'api-fetch-error',
			sprintf(
				/* translators: %s is the tmp file path */
				__( 'Response in unexpected format: %s', 'photoshelter-importer' ),
				wp_json_encode( $info )
			),
			array(
				'status' => 500,
			)
		);
	}

	$file_name = $info->data->attributes->file_name;

	$tmp_path = sprintf( '/tmp/%s', $file_name );
	$download = $media->download_media( compact( 'file_type', 'file_quality', 'media_id' ) );

	if ( is_wp_error( $download ) ) {
		return error_response( $download, 400 );
	}

	if ( ! $wp_filesystem->put_contents( $tmp_path, $download ) ) {
		return new \WP_Error(
			'file-error',
			sprintf(
				/* translators: %s is the tmp file path */
				__( 'Unable to write to file system: %s', 'photoshelter-importer' ),
				$tmp_path
			),
			array(
				'status' => 500,
			)
		);
	}

	$mime_type = get_mime_type( $tmp_path );

	try {
		$attachment_id = \media_handle_sideload(
			array(
				'name'           => $file_name,
				'tmp_name'       => $tmp_path,
				'post_mime_type' => $mime_type,
			),
			0
		);
	} catch ( \Exception $e ) {
		return new \WP_Error(
			'sideload-error',
			sprintf(
				/* translators: %s is the tmp file path */
				__( 'Unable to sideload attachment: %s', 'photoshelter-importer' ),
				$e->getMessage()
			),
			array(
				'status' => 500,
			)
		);
	}

	if ( is_wp_error( $attachment_id ) ) {
		return error_response( $attachment_id, 400 );
	}

	update_post_meta(
		$attachment_id,
		'photoshelter_importer_id',
		$media_id
	);

	if ( wp_attachment_is_image( $attachment_id ) ) {
		$image_meta = array(
			'ID' => $attachment_id,
		);

		if ( $set_file_name ) {
			$image_meta['post_title'] = $set_file_name;
		}

		if ( $set_file_caption ) {
			$image_meta['post_excerpt'] = $set_file_caption;
		}

		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $set_file_alt );

		wp_update_post( $image_meta );
	}

	return new \WP_REST_Response(
		array(
			'code'    => 'success',
			'message' => __( 'OK', 'photoshelter-importer' ),
			'data'    => array(
				'status' => 200,
				'asset'  => array(
					'id'        => $attachment_id,
					'caption'   => wp_get_attachment_caption( $attachment_id ),
					'mime_type' => get_post_mime_type( $attachment_id ),
					'url'       => wp_get_attachment_url( $attachment_id ),
				),
			),
		),
		200
	);
}
