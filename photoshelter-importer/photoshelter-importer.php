<?php
/**
 * Plugin bootstrap.
 *
 * @package     PhotoShelter\Importer
 * @author      Scott Weaver (@tdlm)
 * @license     GPL2+
 *
 * Plugin Name: PhotoShelter Importer
 * Plugin URI:  https://www.photoshelter.com
 * Description: PhotoShelter Digital Asset Manager integration with WordPress.
 * Version:     1.0.0
 * Author:      PhotoShelter
 * Author URI:  https://www.photoshelter.com
 * License:     GPLv2 or later
 * Text Domain: photoshelter-importer
 * Domain Path: /languages
 */

namespace PhotoShelter\Importer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Disable direct file access.
}

// Global constants.
define( 'PHOTOSHELTER_IMPORTER_VERSION', '1.0.0' );
define( 'PHOTOSHELTER_IMPORTER_PLUGIN', __FILE__ );
define( 'PHOTOSHELTER_IMPORTER_BASE', plugin_basename( PHOTOSHELTER_IMPORTER_PLUGIN ) );
define( 'PHOTOSHELTER_IMPORTER_URL', plugin_dir_url( PHOTOSHELTER_IMPORTER_PLUGIN ) );
define( 'PHOTOSHELTER_IMPORTER_PATH', plugin_dir_path( PHOTOSHELTER_IMPORTER_PLUGIN ) );
define( 'PHOTOSHELTER_IMPORTER_INC', PHOTOSHELTER_IMPORTER_PATH . 'includes/' );

// Include files.
require_once PHOTOSHELTER_IMPORTER_INC . 'helpers/api.php';
require_once PHOTOSHELTER_IMPORTER_INC . 'helpers/file.php';
require_once PHOTOSHELTER_IMPORTER_INC . 'functions/core.php';
require_once PHOTOSHELTER_IMPORTER_INC . 'functions/rest-api.php';
require_once PHOTOSHELTER_IMPORTER_INC . 'functions/admin.php';

// Register autoloader.
spl_autoload_register(
	function ( $class ) {
		$prefix   = 'PhotoShelter\\Importer\\';
		$base_dir = __DIR__ . '/includes/classes/';
		$len      = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}
		$relative_class = substr( $class, $len );
		$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

// Activation/Deactivation.
register_activation_hook( __FILE__, '\PhotoShelter\Importer\Core\activate' );
register_deactivation_hook( __FILE__, '\PhotoShelter\Importer\Core\deactivate' );

// Bootstrap.
Core\bootstrap();
REST_API\bootstrap();

if ( is_admin() ) {
	Admin\bootstrap();
}
