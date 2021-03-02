<?php
/**
 * File helpers.
 *
 * @package photoshelter-importer
 */

namespace PhotoShelter\Importer\Helper\File;

/**
 * Get Mime Type for file.
 *
 * @param string $file File with path included.
 *
 * @return string Mime type.
 * @since 1.0.0
 */
function get_mime_type( $file ) {
	$finfo = finfo_open( FILEINFO_MIME_TYPE );
	$mime  = finfo_file( $finfo, $file );
	finfo_close( $finfo );

	return $mime;
}

/**
 * Gets this plugin's absolute directory path.
 *
 * @return string
 * @ignore
 * @access private
 *
 * @since 1.0.0
 */
function get_plugin_directory() {
	return dirname( PHOTOSHELTER_IMPORTER_PLUGIN );
}

/**
 * Get plugin URL.
 *
 * @return string
 * @since 1.0.0
 */
function get_plugin_url() {
	return untrailingslashit( PHOTOSHELTER_IMPORTER_URL );
}
