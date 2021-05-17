<?php

/*
Plugin Name: Export All URLs
Plugin URI: https://AtlasGondal.com/
Description: This plugin allows you to extract Title, URL and Categories of builtin post types (e.g post, page) or any other custom post type available on your site. You can write output in the dashboard or export as CSV file. It can be very useful during migration, seo analysis and security audit.
Version: 4.1
Author: Atlas Gondal
Author URI: https://AtlasGondal.com/
License: GPL v2 or higher
License URI: License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


function eau_extract_all_urls_nav(){

    add_management_page( 'Export All URLs', 'Export All URLs', 'manage_options', 'extract-all-urls-settings', 'eau_include_settings_page' );

}

add_action( 'admin_menu', 'eau_extract_all_urls_nav' );

function eau_include_settings_page(){

    include(plugin_dir_path(__FILE__) . 'extract-all-urls-settings.php');

}

function eau_export_all_urls_on_activate() {
    if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        $plugin_data = get_plugin_data( __FILE__ );
        $plugin_version = $plugin_data['Version'];
        $plugin_name = $plugin_data['Name'];
        wp_die( '<h1>' . __('Could not activate plugin: PHP version error') . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('You are using PHP version') . ' ' . PHP_VERSION . '</strong>. ' . __( 'This plugin has been tested with PHP versions 5.4 and greater.') . '</p><p>' . __('WordPress itself recommends using PHP version 7.3 or greater') . ': <a href="https://wordpress.org/about/requirements/" target="_blank">' . __('Official WordPress requirements') . '</a>' . '. ' . __('Please upgrade your PHP version or contact your Server administrator.') . '</p>', __('Could not activate plugin: PHP version error'), array( 'back_link' => true ) );

    }
    set_transient( 'eau_export_all_urls_activation_redirect', true, 30 );
}

register_activation_hook( __FILE__, 'eau_export_all_urls_on_activate' );

function eau_redirect_on_export_all_urls_activation() {

    if ( ! get_transient( 'eau_export_all_urls_activation_redirect' ) ) {
        return;
    }

    delete_transient( 'eau_export_all_urls_activation_redirect' );

    wp_safe_redirect( add_query_arg( array( 'page' => 'extract-all-urls-settings' ), admin_url( 'tools.php' ) ) );

}
add_action( 'admin_init', 'eau_redirect_on_export_all_urls_activation' );

add_filter( 'admin_footer_text', 'eau_admin_footer_text' );
function eau_admin_footer_text( $footer_text ) {

    $current_screen = get_current_screen();

    $is_export_all_urls_screen = ( $current_screen && false !== strpos( $current_screen->id, 'extract-all-urls-settings' ) );

    if ( $is_export_all_urls_screen ) {
        $footer_text = 'Enjoyed <strong>Export All URLs</strong>? Please leave us a <a href="https://wordpress.org/support/plugin/export-all-urls/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support! ';
    }

    return $footer_text;
}