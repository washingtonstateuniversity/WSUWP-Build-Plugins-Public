<?php
/*
Plugin Name: Watson Assistant
Description: This plugin allows you to easily add chatbots powered by IBM Watson Assistant (formerly Watson Conversation) to your website.
Author: IBM Cognitive Class
Author URI: https://cognitiveclass.ai
Version: 0.8.27
Text Domain: watsonconv
*/

define('WATSON_CONV_FILE', __FILE__);
define('WATSON_CONV_PATH', plugin_dir_path(__FILE__));
define('WATSON_CONV_URL', plugin_dir_url(__FILE__));
define('WATSON_CONV_BASENAME', plugin_basename(__FILE__));

function watsonconv_check_php_compatibility() {
    $required = '5.3';

    if (version_compare( PHP_VERSION, $required, '<' )) {
        deactivate_plugins( basename( __FILE__ ) );
        wp_die(
            "<p>The <strong>Watson Assistant</strong> plugin requires PHP version <b>$required</b> 
                or greater. You have PHP version <b>". PHP_VERSION . '</b>. See 
                <a href="https://wordpress.org/support/upgrade-php/" target="_blank">this page</a>
                for information on upgrading.</p>',
            'Plugin Activation Error',  
            array('response' => 200, 'back_link' => TRUE)
        );
    } else {
        return;
    }
}

register_activation_hook(WATSON_CONV_FILE, 'watsonconv_check_php_compatibility');

require_once(WATSON_CONV_PATH.'vendor/autoload.php');
// Database
// It should be included earlier than install and task runner files because they
// depend on database related functionality
require_once(WATSON_CONV_PATH.'includes/storage.php');
// Logger
require_once(WATSON_CONV_PATH.'includes/logger.php');
// Settings and frontend
require_once(WATSON_CONV_PATH.'includes/settings/main.php');
require_once(WATSON_CONV_PATH.'includes/frontend.php');
// Background task runner
require_once(WATSON_CONV_PATH.'includes/background-task-runner.php');
// Install and update
require_once(WATSON_CONV_PATH.'includes/install.php');
// Plugin functionality
require_once(WATSON_CONV_PATH.'includes/api.php');
// Plugin functionality
require_once(WATSON_CONV_PATH.'includes/email_notificator.php');

