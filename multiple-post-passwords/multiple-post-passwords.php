<?php
/**
 * Plugin Name: Multiple Post Passwords
 * Plugin URI: https://www.andreasmuench.de/wordpress/
 * Description: Easily setup multiple passwords for single protected posts
 * Version: 1.1.0
 * Author: Andreas Münch
 * Author URI: https://www.andreasmuench.de/wordpress/
 * Requires at least: 4.7.0
 * Tested up to: 5.5.3
 * Text Domain: multiple-post-passwords
 * Domain Path: /languages/
 * License: GPL2+
 *
 * @package multiple-post-passwords
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles core plugin hooks and action setup.
 *
 * @package multiple-post-passwords
 * @since 1.0.0
 */
class Multiple_Post_Passwords {
    /**
     * The single instance of the class.
     *
     * @var self
     * @since  1.0.0
     */
    private static $_instance = null;

    protected $basename;


    /**
     * Main Multiple_Post_Passwords Instance.
     *
     * Ensures only one instance of Multiple_Post_Passwords is loaded or can be loaded.
     *
     * @since  1.0.0
     * @static
     * @return self Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        // Define constants.
         define( 'MPP_VERSION', '1.1.0' );
         define( 'MPP_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

        $this->basename = plugin_basename(__FILE__);

        include_once MPP_PLUGIN_DIR . '/frontend/page-passwords.php';

        include_once MPP_PLUGIN_DIR . '/inc/cronjobs.php';

        \MultiplePostPasswords\Cronjobs::instance();

        include_once MPP_PLUGIN_DIR . '/admin/metabox.php';

        if ( is_admin() ) {

            MultiplePostPasswords\Admin\Metabox::instance();

            include_once MPP_PLUGIN_DIR . '/admin/settings-page.php';
            new \MultiplePostPasswords\Admin\Settings_Page(['plugin_basename'=>$this->basename]);


        } else {

            include_once MPP_PLUGIN_DIR . '/frontend/password-check.php';
            MultiplePostPasswords\Frontend\PasswordCheck::instance();

            include_once MPP_PLUGIN_DIR . '/frontend/alternative-password-check.php';
            MultiplePostPasswords\Frontend\AlternativePasswordCheck::instance();

        }

    }

}
/**
 * Main instance of Multiple_Post_Passwords
 *
 * Returns the main instance of Multiple_Post_Passwords to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Multiple_Post_Passwords
 */
function multiple_post_passwords_init() { 
    return Multiple_Post_Passwords::instance();
}

$GLOBALS['Multiple_Post_Passwords'] = multiple_post_passwords_init();
