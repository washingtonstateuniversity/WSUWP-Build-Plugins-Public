<?php
namespace WatsonConv\Settings;

require_once(WATSON_CONV_PATH.'includes/settings/setup.php');
require_once(WATSON_CONV_PATH.'includes/settings/customize.php');
require_once(WATSON_CONV_PATH.'includes/settings/advanced.php');

add_action('admin_menu', array('WatsonConv\Settings\Main', 'init_page'));
add_action('admin_init', array('WatsonConv\Settings\Main', 'init_settings'));
add_action('admin_enqueue_scripts', array('WatsonConv\Settings\Main', 'init_scripts'));
add_action('after_plugin_row_'.WATSON_CONV_BASENAME, array('WatsonConv\Settings\Main', 'render_notice'), 10, 3);
add_filter('plugin_action_links_'.WATSON_CONV_BASENAME, array('WatsonConv\Settings\Main', 'add_links'));

add_action('plugins_loaded', array('WatsonConv\Settings\Setup', 'migrate_old_credentials'));
add_action('plugins_loaded', array('WatsonConv\Settings\Setup', 'change_credentials_to_basic'));
add_action('plugins_loaded', array('WatsonConv\Settings\Customize', 'migrate_old_show_on'));
add_action('plugins_loaded', array('WatsonConv\Settings\Customize', 'migrate_old_full_screen'));
add_action('upgrader_process_complete', array('WatsonConv\Settings\Customize', 'clear_css_cache'), 10, 2);

class Main {
    const SLUG = 'watson_asst';

    public static function init_page() {
        add_menu_page('Watson Assistant', 'Watson Assistant', 'manage_options', self::SLUG, 
            array(__CLASS__, 'render_page'), 'dashicons-format-chat');

        Setup::init_page();
        Customize::init_page();
        Advanced::init_page();

        remove_submenu_page(self::SLUG, self::SLUG);
    }

    public static function init_settings() {
        Setup::init_settings();
        Customize::init_settings();
        Advanced::init_settings();
    }

    public static function init_scripts($hook_suffix) {
        if (substr($hook_suffix, 0, 16) === 'watson-assistant') {
            wp_enqueue_style(
                'watsonconv-settings', 
                WATSON_CONV_URL.'css/settings.css', 
                array('wp-color-picker'),
                '0.7.4'
            );

            wp_enqueue_script(
                'watsonconv-settings', 
                WATSON_CONV_URL.'includes/settings/settings.js',
                array('wp-color-picker', 'jquery-ui-tooltip'),
                '0.7.4'
            );

            wp_localize_script('watsonconv-settings', 'page_data', array('hook_suffix' => $hook_suffix));

            \WatsonConv\Frontend::enqueue_styles(false);
        }
    }

    public static function render_page() {
    ?>
        <div class="wrap" style="max-width: 95em">
            <h2><?php esc_html_e('Watson Assistant', self::SLUG); ?></h2>
            <?php self::render_isv_banner(); ?>
        </div>
    <?php
    }

    public static function render_notice($plugin_file, $plugin_data, $status) {
        $credentials = get_option('watsonconv_credentials');

        if (empty($credentials)) {
        ?>
            <tr class="active icon-settings"><td colspan=3>
                <div class="update-message notice inline notice-warning notice-alt"
                     style="padding:0.5em; padding-left:1em; margin:0">
                    <span style='color:orange; margin-right:0.3em'
                          class='dashicons dashicons-admin-settings'></span>
                    <a href="admin.php?page=<?php echo Setup::SLUG ?>">
                        <?php esc_html_e('Please fill in your Watson Assistant credentials.', self::SLUG) ?>
                    </a>
                </div>
            </td></tr>
            
        <?php
        }
        $workspace_url = "";
        if(isset($credentials["workspace_url"])) {
            $workspace_url = $credentials["workspace_url"];
        }
        $api_version = \WatsonConv\API::detect_api_version($workspace_url);

        if (!empty($credentials) && ($api_version === 'v1')) {
        ?>
            <tr class="active icon-settings"><td colspan=3>
                <div class="update-message notice inline notice-error notice-alt"
                     style="padding:0.5em; padding-left:1em; margin:0">
                    <span style='color:orange; margin-right:0.3em'
                          class='dashicons dashicons-admin-network'></span>
                    <a href="admin.php?page=<?php echo Setup::SLUG ?>">
                        <?php esc_html_e('Please update your Watson Assistant Credentials to make plugin compatible with new functions.', self::SLUG) ?>
                    </a>
                </div>
            </td></tr>
        <?php
        }
    }

    public static function add_links($links) {
        $credentials = get_option('watsonconv_credentials');

        $settings_link = '<a href="admin.php?page=' . (empty($credentials) ? Setup::SLUG : Customize::SLUG) . '">'
            . esc_html__('Settings', self::SLUG) . '</a>';

        $learn_link = '<a href="https://cocl.us/build-a-chatbot" target="_blank">'
            . esc_html__('Learn', self::SLUG) . '</a>';

        return array($learn_link, $settings_link) + $links;
    }

    public static function render_v1_update_warning() {
        Logger::log_message("yes, it reached this method");
        $credentials = get_option('watsonconv_credentials');
        $workspace_url = "";
        if(isset($credentials["workspace_url"])) {
            $workspace_url = $credentials["workspace_url"];
        }
        $api_version = \WatsonConv\API::detect_api_version($workspace_url);
        Logger::log_message("workspace url", $workspace_url);
        if (!empty($credentials) && ($api_version === 'v1')) {
            ?> 
                <div id="v1_warning" class="notice notice-info is-dismissible">
                    <p>Please update your Watson Assistant API credentials</p>
                    <a
                        class='button button-primary' 
                        style='margin-bottom: 0.5em' 
                        href='https://cocl.us/CB0103EN_WATR_WPP'
                        target="_blank"
                    >
                        Become a Partner
                    </a>
                </div>
            <?php
        }
    }

    public static function render_isv_banner() {
    ?> 
        <div class="notice notice-info is-dismissible">
            <p><?php esc_html_e('
                Want to make money building chatbots for clients? Become an IBM Partner, registration is quick and free!
                Get one year of Watson Assistant and 100,000 API calls, 10 workspaces or chatbots, 200 intents and 200 entities as your free starting bonus.'
            , self::SLUG); ?></p>
            <a
                class='button button-primary' 
                style='margin-bottom: 0.5em' 
                href='https://cocl.us/CB0103EN_WATR_WPP'
                target="_blank"
            >
                Become a Partner
            </a>
        </div>
    <?php

        $credentials = get_option('watsonconv_credentials');
        $workspace_url = "";
        if(isset($credentials["workspace_url"])) {
            $workspace_url = $credentials["workspace_url"];
        }
        $api_version = \WatsonConv\API::detect_api_version($workspace_url);
        if (!empty($credentials) && ($api_version === 'v1')) {
            ?> 
                <div class="error notice-error is-dismissible">
                    <p>Please update your Watson Assistant API credentials to be able to use new functionality.</p>
                    <a
                        class='button button-primary' 
                        style='margin-bottom: 0.5em' 
                        href='admin.php?page=<?php echo Setup::SLUG ?>'
                    >
                        Update credentials
                    </a>
                </div>
            <?php
        }
    }

    public static function render_radio_buttons($option_name, $default_value, $options, $div_style = '') {
        foreach ($options as $option) {
        ?>
            <div style="<?php echo $div_style ?>" >
                <label for="<?php echo $option_name.'_'.$option['value'] ?>">
                    <input
                        name=<?php echo $option_name ?>
                        id="<?php echo $option_name.'_'.$option['value'] ?>"
                        type="radio"
                        value="<?php echo $option['value'] ?>"
                        <?php checked($option['value'], get_option($option_name, $default_value)) ?>
                    >
                    <?php echo $option['label'] ?>
                </label><br />
            </div>
        <?php
        }
    }
}
