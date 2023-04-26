<?php

/**
 * Plugin Name:       Related Products for WooCommerce     
 * Plugin URI:        https://wordpress.org/plugins/wt-woocommerce-related-products/ 
 * Description:       Displays custom related products based on category, tag, attribute or product for your WooCommerce store.
 * Version:           1.4.7
 * Author:            WebToffee  
 * Author URI:        https://www.webtoffee.com/        
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wt-woocommerce-related-products
 * WC tested up to:   7.3.0
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

if ( !defined( 'CRP_PLUGIN_URL' ) )
	define( 'CRP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( !defined( 'CRP_PLUGIN_DIR' ) ) {
	define( 'CRP_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( !defined( 'CRP_PLUGIN_DIR_PATH' ) ) {
	define( 'CRP_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CRP_PLUGIN_TEMPLATE_PATH' ) ) {
    define( 'CRP_PLUGIN_TEMPLATE_PATH', CRP_PLUGIN_DIR_PATH . 'public/partials' );
}


if (!defined('WT_CRP_BASE_NAME')) {
    define('WT_CRP_BASE_NAME', plugin_basename(__FILE__));
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WT_RELATED_PRODUCTS_VERSION', '1.4.7' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-related-products-activator.php
 */
function activate_custom_related_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-related-products-activator.php';
	Custom_Related_Products_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-related-products-deactivator.php
 */
function deactivate_custom_related_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-related-products-deactivator.php';
	Custom_Related_Products_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_related_products' );
register_deactivation_hook( __FILE__, 'deactivate_custom_related_products' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-custom-related-products.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-wt-relatedproducts-uninstall-feedback.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-wt-security-helper.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_custom_related_products() {

	$plugin = new Custom_Related_Products();
	$plugin->run();
}

run_custom_related_products();
