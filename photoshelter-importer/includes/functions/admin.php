<?php
/**
 * Admin plugin functionality.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\Admin;

use PhotoShelter\Importer\API\OAuth\Client as OAuth_Client;
use PhotoShelter\Importer\API\OAuth\DataSource as OAuth_DataSource;
use PhotoShelter\Importer\API\Auth\Client as Auth_Client;
use PhotoShelter\Importer\API\Auth\DataSource as Auth_DataSource;
use PhotoShelter\Importer\APIv3\Organization\Client as Organization_Client;
use PhotoShelter\Importer\APIv3\Organization\DataSource as Organization_DataSource;
use function PhotoShelter\Importer\Helper\File\get_plugin_directory;
use function PhotoShelter\Importer\Helper\File\get_plugin_url;
use function PhotoShelter\Importer\REST_API\get_api_key;
use function PhotoShelter\Importer\REST_API\get_client_id;
use function PhotoShelter\Importer\REST_API\get_client_secret;
use function PhotoShelter\Importer\REST_API\get_importer_auth_rest_url;
use function PhotoShelter\Importer\REST_API\get_oauth_access_token;
use function PhotoShelter\Importer\REST_API\get_oauth_refresh_token;
use function PhotoShelter\Importer\REST_API\get_oauth_register_endpoint_url;
use function PhotoShelter\Importer\Core\get_plugin_data;
use function PhotoShelter\Importer\REST_API\get_org_id;
use function PhotoShelter\Importer\REST_API\get_org_name;

// Relevant constants.
define( 'PHOTOSHELTER_IMPORTER_SLUG_SETTINGS', 'photoshelter-importer-settings' );
define( 'PHOTOSHELTER_IMPORTER_SLUG_LIBRARY', 'photoshelter-importer-library' );

/**
 * Bootstrap.
 *
 * @since 1.0.0
 *
 * Things done on plugin initialization.
 */
function bootstrap() {
	add_action( 'admin_init', __NAMESPACE__ . '\\init' );
	add_action( 'admin_menu', __NAMESPACE__ . '\\admin_menu' );
}

/**
 * Init.
 *
 * @since 1.0.0
 *
 * Things done on admin_init.
 */
function init() {
	register_setting(
		'photoshelter-importer',
		'photoshelter_importer_options',
		array(
			'sanitize_callback' => __NAMESPACE__ . '\\validate_importer_options',
		)
	);

	add_settings_section(
		'photoshelter-importer_section_account',
		__( 'Account Details', 'photoshelter-importer' ),
		__NAMESPACE__ . '\\importer_settings_screen_section_account',
		'photoshelter-importer'
	);

	// Add API field.
	add_settings_field(
		'photoshelter-importer_account_api',
		__( 'PhotoShelter API Key', 'photoshelter-importer' ),
		__NAMESPACE__ . '\\importer_settings_screen_section_account_field_api',
		'photoshelter-importer',
		'photoshelter-importer_section_account',
		array(
			'name'      => 'api_key',
			'label_for' => 'photoshelter-importer_account_api',
			'class'     => 'photoshelter-importer-settings__row',
			'help_text' => __( 'Where can I find my API Key?', 'photoshelter-importer' ),
			'help_url'  => 'https://getlibris.zendesk.com/hc/en-us/articles/360051200374-WordPress-Plugin',
		)
	);

	register_blocks();
	register_block_assets();

	$api_key      = get_api_key();
	$client_id    = get_client_id();
	$access_token = get_oauth_access_token();

	// If we have our API key and our Client ID but not our Access Token,
	// the user has registered the application but needs to authorize the app.
	if ( ! empty( $api_key ) && ! empty( $client_id ) && empty( $access_token ) ) {
		add_settings_field(
			'photoshelter-importer_account_api_link',
			'',
			__NAMESPACE__ . '\\importer_settings_screen_section_account_field_api_link',
			'photoshelter-importer',
			'photoshelter-importer_section_account',
			array(
				'button_text' => __( 'Authorize through PhotoShelter', 'photoshelter-importer' ),
				'button_url'  => get_oauth_register_endpoint_url(),
			)
		);
	}

	// Only show Org ID field if API key and Access Token are present.
	if ( ! empty( $api_key ) && ! empty( $access_token ) ) {
		// Add Org ID field.
		add_settings_field(
			'photoshelter-importer_account_org_id',
			__( 'PhotoShelter Org ID', 'photoshelter-importer' ),
			__NAMESPACE__ . '\\importer_settings_screen_section_account_field_org',
			'photoshelter-importer',
			'photoshelter-importer_section_account',
			array(
				'name'      => 'org_id',
				'label_for' => 'photoshelter-importer_account_org_id',
				'class'     => 'photoshelter-importer-settings__row',
				'help_text' => __( 'Where can I find my Org ID?', 'photoshelter-importer' ),
				'help_url'  => 'https://getlibris.zendesk.com/hc/en-us/articles/360051200374-WordPress-Plugin',
			)
		);
	}

	// Add settings link to plugin page.
	add_filter(
		sprintf(
			'plugin_action_links_%1$s',
			PHOTOSHELTER_IMPORTER_BASE
		),
		__NAMESPACE__ . '\\plugin_settings_link'
	);

	// Enqueue admin scripts/styles.
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

	// Check transient.
	$importer_token_set = get_transient( 'photoshelter_importer_token_set' );

	if ( $importer_token_set && isset( $importer_token_set['type'], $importer_token_set['message'] ) ) {
		add_settings_error(
			'photoshelter_importer_token',
			sprintf( 'importer_token_%s', $importer_token_set['type'] ),
			$importer_token_set['message'],
			$importer_token_set['type']
		);

		delete_transient( 'photoshelter_importer_token_set' );
	}
}

/**
 * Add admin menu and page.
 *
 * @since 1.0.0
 */
function admin_menu() {
	add_options_page(
		esc_html__( 'PhotoShelter Importer Settings', 'photoshelter-importer' ),
		esc_html__( 'PS Importer', 'photoshelter-importer' ),
		'manage_options',
		PHOTOSHELTER_IMPORTER_SLUG_SETTINGS,
		__NAMESPACE__ . '\\importer_settings_screen'
	);

	if ( get_api_key() && get_oauth_access_token() ) {
		add_menu_page(
			__( 'PS Importer', 'photoshelter-importer' ),
			__( 'PS Importer', 'photoshelter-importer' ),
			'manage_options',
			PHOTOSHELTER_IMPORTER_SLUG_LIBRARY,
			__NAMESPACE__ . '\\library_screen',
			plugins_url( 'assets/dist/svg/logo-icon-white.svg', PHOTOSHELTER_IMPORTER_PLUGIN ),
			null
		);
	}
}

/**
 * Register Blocks for Block Editor.
 *
 * @since 1.0.0
 */
function register_blocks() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	register_block_type(
		'photoshelter-importer/media',
		array(
			'editor_script' => 'photoshelter-importer-media-editor-js',
			'editor_style'  => 'photoshelter-importer-media-editor-css',
			'style'         => 'photoshelter-importer-media-css',
			'attributes'    => array(),
		)
	);
}

/**
 * Register Block Assets.
 *
 * @since 1.0.0
 */
function register_block_assets() {
	$editor_js_path    = '/assets/dist/js/blocks.editor.js';
	$editor_style_path = '/assets/dist/css/blocks.editor.css';

	$dependencies = array(
		'wp-plugins',
		'wp-element',
		'wp-edit-post',
		'wp-i18n',
		'wp-api-request',
		'wp-data',
		'wp-hooks',
		'wp-plugins',
		'wp-components',
		'wp-blocks',
		'wp-block-editor',
		'wp-editor',
		'wp-compose',
	);

	// Register the bundled block JS file.
	wp_register_script(
		'photoshelter-importer-media-editor-js',
		get_plugin_url() . $editor_js_path,
		$dependencies,
		filemtime( get_plugin_directory() . $editor_js_path ),
		true
	);

	wp_localize_script(
		'photoshelter-importer-media-editor-js',
		'PhotoShelterImporter',
		get_localized_objects_array()
	);

	// Register editor-only styles.
	wp_register_style(
		'photoshelter-importer-media-editor-css',
		get_plugin_url() . $editor_style_path,
		array(),
		filemtime( get_plugin_directory() . $editor_style_path )
	);
}

/**
 * Get array of objects for localizer.
 *
 * @return array Array of objects for localizer.
 * @since 1.0.0
 */
function get_localized_objects_array() {
	return array(
		'accessToken'  => get_oauth_access_token(),
		'apiKey'       => get_api_key(),
		'apiNonce'     => wp_create_nonce( 'wp_rest' ),
		'clientId'     => get_client_id(),
		'clientSecret' => get_client_secret(),
		'images'       => array(
			'audio' => get_plugin_url() . '/assets/dist/svg/audio-large.svg',
		),
		'orgId'        => get_org_id(),
		'orgName'      => get_org_name(),
		'refreshToken' => get_oauth_refresh_token(),
		'restUri'      => get_importer_auth_rest_url(),
		'uploadUri'    => admin_url( 'upload.php' ),
	);
}

/**
 * Enqueue admin assets.
 *
 * @param string $hook Hook slug string.
 *
 * @since 1.0.0
 */
function enqueue_assets( $hook ) {
	$admin_css = '/assets/dist/css/admin-style.css';

	// Enqueue admin styles.
	wp_enqueue_style(
		'photoshelter-importer-admin',
		plugins_url( $admin_css, PHOTOSHELTER_IMPORTER_PLUGIN ),
		array( 'admin-menu' ),
		filemtime( PHOTOSHELTER_IMPORTER_PATH . $admin_css )
	);

	switch ( $hook ) {
		case 'toplevel_page_photoshelter-importer-library':
			$javascript = '/assets/dist/js/library.js';
			$stylesheet = '/assets/dist/css/admin-library-style.css';

			wp_enqueue_script(
				'photoshelter-importer-admin-library-js',
				plugins_url( $javascript, PHOTOSHELTER_IMPORTER_PLUGIN ),
				array(
					'wp-element',
					'wp-i18n',
					'wp-api-fetch',
					'wp-data',
					'wp-date',
					'wp-compose',
					'wp-components',
					'wp-data-controls',
					'wp-block-library',
					'lodash',
					'wp-notices',
				),
				filemtime( PHOTOSHELTER_IMPORTER_PATH . $javascript ),
				true
			);

			wp_enqueue_style(
				'photoshelter-importer-admin-library',
				plugins_url( $stylesheet, PHOTOSHELTER_IMPORTER_PLUGIN ),
				array( 'wp-components' ),
				filemtime( PHOTOSHELTER_IMPORTER_PATH . $stylesheet )
			);

			wp_localize_script(
				'photoshelter-importer-admin-library-js',
				'PhotoShelterImporter',
				get_localized_objects_array()
			);
			break;
		case 'settings_page_photoshelter-importer-settings':
			$stylesheet = '/assets/dist/css/admin-settings-style.css';

			wp_enqueue_style(
				'photoshelter-importer-admin-settings-css',
				plugins_url( $stylesheet, PHOTOSHELTER_IMPORTER_PLUGIN ),
				array(),
				filemtime( PHOTOSHELTER_IMPORTER_PATH . $stylesheet )
			);
			break;
		default:
			break;
	}
}

/**
 * Importer Settings screen.
 *
 * @since 1.0.0
 */
function importer_settings_screen() {
	?>
	<div class="wrap">
		<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
		<form action="options.php" method="POST">
			<?php settings_fields( 'photoshelter-importer' ); ?>
			<?php do_settings_sections( 'photoshelter-importer' ); ?>
			<?php submit_button( __( 'Save Settings', 'photoshelter-importer' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Library screen.
 *
 * @since 1.0.0
 */
function library_screen() {
	?>
	<div id="app"></div>
	<?php
}

/**
 * Importer Settings screen : Account section.
 *
 * @param array $args Array of arguments.
 *
 * @since 1.0.0
 */
function importer_settings_screen_section_account( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>">
		<?php
		echo esc_html__(
			'Please enter your PhotoShelter API Key in order to access your Library.',
			'photoshelter-importer'
		);
		?>
	</p>
	<?php
}

/**
 * Importer Settings Screen : Account API field.
 *
 * @param array $args Array of arguments.
 *
 * @since 1.0.0
 */
function importer_settings_screen_section_account_field_api( $args ) {
	$options = get_option( 'photoshelter_importer_options' );
	?>
	<input
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			name="photoshelter_importer_options[<?php echo esc_attr( $args['name'] ); ?>]"
			type="text"
			placeholder="<?php echo esc_attr__( 'Enter API Key here.', 'photoshelter-importer' ); ?>"
			value="<?php echo esc_attr( $options[ $args['name'] ] ); ?>"
	/>
	<p class="description">
		<?php
		echo wp_kses(
			sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>',
				$args['help_url'],
				$args['help_text']
			),
			array(
				'a' => array(
					'href'   => array(),
					'target' => array(),
				),
			)
		);
		?>
	</p>
	<?php
}

/**
 * Importer Settings Screen : Account API auth link.
 *
 * @param array $args Array of arguments.
 *
 * @since 1.0.0
 */
function importer_settings_screen_section_account_field_api_link( $args ) {
	?>
	<button
			type="button"
			name="token"
			id="token"
			class="button button-primary photoshelter-button-external"
			onclick="window.location.href = '<?php echo esc_attr( $args['button_url'] ); ?>';"
	>
		<?php echo esc_html( $args['button_text'] ); ?>
		<span class="dashicons dashicons-external"></span>
	</button>
	<?php
}

/**
 * Importer Settings Screen : Account Org ID field.
 *
 * @param array $args Array of arguments.
 *
 * @since 1.0.0
 */
function importer_settings_screen_section_account_field_org( $args ) {
	$options = get_option( 'photoshelter_importer_options' );

	$org_logged_in = (bool) get_option( 'photoshelter_importer_org_logged_in' );

	$value = isset( $args['name'], $options[ $args['name'] ] ) ? $options[ $args['name'] ] : '';

	if ( false === $org_logged_in || '' === $value ) :
		?>
		<input
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="photoshelter_importer_options[<?php echo esc_attr( $args['name'] ); ?>]"
				type="text"
				placeholder="<?php echo esc_attr__( 'Enter Org ID here, if you have one.', 'photoshelter-importer' ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
		/>
		<p class="description">
			<?php
			echo wp_kses(
				sprintf(
					'<a href="%1$s" target="_blank">%2$s</a>',
					$args['help_url'],
					$args['help_text']
				),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			);
			?>
		</p>
	<?php else : ?>
		<input
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="photoshelter_importer_options[<?php echo esc_attr( $args['name'] ); ?>]"
				type="hidden"
				value="<?php echo esc_attr( $value ); ?>"
		/>
		<input type="submit" class="button button-primary"
			onClick="document.querySelector('#<?php echo esc_attr( $args['label_for'] ); ?>').value=''"
			value="<?php esc_attr_e( 'Disconnect', 'photoshelter-importer' ); ?>"
		>
		<?php
	endif;
}

/**
 * Validate importer settings options.
 *
 * @param array $input Options input array.
 *
 * @return array
 * @since 1.0.0
 */
function validate_importer_options( $input ) {
	$errors = array();
	$output = array();

	$previous_options = get_option( 'photoshelter_importer_options' );

	// Loop through incoming inputs and validate them.
	foreach ( $input as $key => $value ) {
		if ( isset( $input[ $key ] ) ) {
			$output[ $key ] = wp_strip_all_tags( stripslashes( $input[ $key ] ) );
		}

		// Validate API key field.
		if ( isset( $output['api_key'] ) ) {

			// Only send out an error if user entered something and it doesn't match the pattern.
			if (
				! empty( $output['api_key'] ) &&
				! preg_match( '#' . PHOTOSHELTER_API_REGEX_KEY . '#', $output['api_key'] )
			) {
				$errors['api_key'] = __( 'Invalid API key specified.', 'photoshelter-importer' );
				$output['api_key'] = '';
			}
		}

		// Validate Org ID field, if present.
		if ( isset( $output['org_id'] ) ) {

			// Only send out an error if user entered something and it doesn't match the pattern.
			if (
				! empty( $output['org_id'] ) &&
				! preg_match( '#' . PHOTOSHELTER_API_REGEX_ORG_ID . '#', $output['org_id'] )
			) {
				$errors['org_id'] = __( 'Invalid Org ID specified.', 'photoshelter-importer' );
				$output['org_id'] = '';
			}
		}
	} // end foreach

	// Add errors.
	foreach ( $errors as $error_key => $error ) {
		if ( 'api_key' === $error_key ) {
			update_option( 'photoshelter_importer_oauth_access_token', '' );
			update_option( 'photoshelter_importer_oauth_access_token_expires', '' );
			update_option( 'photoshelter_importer_oauth_refresh_token', '' );
			update_option( 'photoshelter_importer_org_logged_in', false );
		}

		add_settings_error(
			'photoshelter_importer_options',
			"invalid_${error_key}",
			$error,
			'error'
		);
	}

	// If things look good, register the application (if necessary) and/or process organization login (if necessary).
	if ( ! empty( $output['api_key'] ) && ! isset( $errors['api_key'] ) ) {

		// If the API key changed, register the application.
		if ( $output['api_key'] !== $previous_options['api_key'] ) {
			register_application( $output['api_key'] );
		}

		// If the ORG ID changed, process it.
		if (
			isset( $output['org_id'] ) &&
			$output['org_id'] !== $previous_options['org_id'] &&
			! isset( $errors['org_id'] )
		) {
			if ( false === process_organization( $output['org_id'] ) ) {
				$output['org_id'] = '';
			}
		}
	}

	return apply_filters( 'photoshelter_importer_validate_importer_options', $output, $input );
}

/**
 * Register application.
 *
 * @param string $api_key API key string.
 *
 * @return void
 * @since 1.0.0
 */
function register_application( $api_key ) {
	// Get blog and plugin info.
	$blog_name   = get_bloginfo( 'name' );
	$plugin_name = get_plugin_data( 'name' );

	// Create OAuth client instance.
	$oauth_client = new OAuth_Client( new OAuth_DataSource( $api_key ) );

	// Create application variables.
	$application_name = sprintf( '%1$s on %2$s', $plugin_name, $blog_name );

	$response = $oauth_client->register_application(
		array(
			'application_name' => $application_name,
			'description'      => $application_name,
			'name'             => $blog_name,
		)
	);

	// Add errors, if applicable.
	if ( is_wp_error( $response ) ) {
		add_settings_error(
			'photoshelter_importer_options',
			'api_key_bad_response',
			$response->get_error_message(),
			'error'
		);
		$output['api_key'] = '';
	}

	// Do stuff with response.
	$attributes    = $response->data->attributes;
	$client_id     = $attributes->client_id;
	$client_secret = $attributes->client_secret;

	update_option( 'photoshelter_importer_oauth_client_id', $client_id );
	update_option( 'photoshelter_importer_oauth_client_secret', $client_secret );
	update_option( 'photoshelter_importer_oauth_access_token', '' );
	update_option( 'photoshelter_importer_oauth_access_token_expires', '' );
	update_option( 'photoshelter_importer_oauth_refresh_token', '' );
}

/**
 * Process organization for ORG ID.
 *
 * @param string      $org_id Org ID string.
 * @param null|string $api_key Optional api key. If null, will do a lookup.
 *
 * @return bool
 * @since 1.0.0
 */
function process_organization( $org_id = '', $api_key = null ) {
	$api_key = is_null( $api_key ) ? get_api_key() : $api_key;

	if ( empty( $api_key ) ) {
		// Do nothing if we have no API key.
		return false;
	}

	$success = true;

	// Create Auth client instance.
	$auth_client   = new Auth_Client( new Auth_DataSource( $api_key, get_oauth_access_token() ) );
	$authenticated = false;

	// V3.
	$org_auth_client = new Organization_Client( new Organization_DataSource( PHOTOSHELTER_API_V3_KEY ) );
	$org_name        = __( 'organization', 'photoshelter-importer' );

	if ( empty( $org_id ) ) {
		$auth_response = $auth_client->deauthenticate_organization();

		update_option( 'photoshelter_importer_org_name', __( 'Library', 'photoshelter-importer' ) );
	} else {
		$auth_response = $auth_client->authenticate_organization(
			compact( 'org_id' )
		);

		$org_name = $org_auth_client->get_organization_name(
			compact( 'org_id' )
		);

		if ( is_wp_error( $org_name ) ) {
			add_settings_error(
				'photoshelter_importer_options',
				'org_name_bad_response',
				$org_name->get_error_message(),
				'error'
			);

			$success = false;

			update_option( 'photoshelter_importer_org_name', $org_name );
		} else {
			update_option( 'photoshelter_importer_org_name', $org_name );
		}

		$authenticated = true;
	}

	// Add errors, if applicable.
	if ( is_wp_error( $auth_response ) ) {
		add_settings_error(
			'photoshelter_importer_options',
			'org_id_bad_response',
			$auth_response->get_error_message(),
			'error'
		);

		$success = false;
	} else {
		add_settings_error(
			'photoshelter_importer_options',
			'org_id_authenticated',
			$authenticated ?
				/* translators: %s is the organition name */
				sprintf( __( 'Logged into %s.', 'photoshelter-importer' ), $org_name ) :
				/* translators: %s is the organition name */
				sprintf( __( 'Logged out of %s.', 'photoshelter-importer' ), $org_name ),
			'success'
		);

		update_option( 'photoshelter_importer_org_logged_in', $authenticated );
	}

	return $success;
}

/**
 * Get Admin settings URL with query params.
 *
 * @param string $path PhotoShelter Admin Settings page path fragment (default = settings).
 *
 * @return string|void
 * @since 1.0.0
 */
function admin_settings_url( $path = 'settings' ) {
	return admin_url( sprintf( 'options-general.php?page=photoshelter-importer-%1$s', $path ) );
}

/**
 * Plugin settings link.
 *
 * @param array $links Array of link elements.
 *
 * @return array Modified array of link elements.
 * @since 1.0.0
 */
function plugin_settings_link( $links ) {
	$settings_link = sprintf(
		'<a href="options-general.php?page=%s">%s</a>',
		PHOTOSHELTER_IMPORTER_SLUG_SETTINGS,
		__( 'Settings', 'photoshelter-importer' )
	);

	array_unshift( $links, $settings_link );

	return $links;
}
