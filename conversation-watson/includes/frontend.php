<?php
namespace WatsonConv;

add_action('wp_loaded', array('WatsonConv\Frontend', 'register_scripts'));
add_action('wp_enqueue_scripts', array('WatsonConv\Frontend', 'chatbox_popup'));
add_action('wp_footer', array('WatsonConv\Frontend', 'render_div'));
add_shortcode('watson-chat-box', array('WatsonConv\Frontend', 'chatbox_shortcode'));

class Frontend {
    /**
     * @return string
     */
    public static function get_version() {
        $plugin_file_data = get_file_data(WATSON_CONV_FILE, array("Version" => "Version"));
        return $plugin_file_data['Version'];
    }

    public static function enqueue_styles($force_full_screen = null) {
        wp_enqueue_style('watsonconv-chatbox');

        $font_size = get_option('watsonconv_font_size', 11);
        $font_size_fs = get_option('watsonconv_font_size_fs', 14);
        $fab_text_size = get_option('watsonconv_fab_text_size', 15);
        $fab_icon_size = get_option('watsonconv_fab_icon_size', 28);
        $color_rgb = sscanf(get_option('watsonconv_color', '#23282d'), "#%02x%02x%02x");
        $messages_height = get_option('watsonconv_size', 200);
        $position = explode('_', get_option('watsonconv_position', 'bottom_right'));
        $watsonconv_logo_option = get_option('watsonconv_logo', 'off');
        $default_logo_url = WATSON_CONV_URL . 'img/chatbot_logo.png';
        $watsonconv_logo_url = '';
        $watsonconv_logo_display = 'block';
        $title_padding = '35px';
        
        $is_dark = self::luminance($color_rgb) <= 0.5;
        $text_color = $is_dark ? 'white' : 'black';

        $main_color = vsprintf('rgb(%d, %d, %d)', $color_rgb);
        $main_color_light = vsprintf('rgba(%d, %d, %d, 0.7)', $color_rgb);

        foreach ($color_rgb as $index => $channel) {
            $color_rgb[$index] = $channel * 0.9;
        }

        $main_color_dark = vsprintf('rgb(%d, %d, %d)', $color_rgb);

        if (is_null($force_full_screen)) {
            $full_screen_settings = get_option('watsonconv_full_screen');
            $full_screen_query = isset($full_screen_settings['query']) ?
                $full_screen_settings['query'] : '@media screen and (max-width:640px) { %s }';
        } else {
            $full_screen_query = $force_full_screen ? '%s' : '';
        }

        $inline_style = get_option('watsonconv_css_cache');

        if ($watsonconv_logo_option == 'on') {
            $watsonconv_logo_url = $default_logo_url;
        }

        if ($watsonconv_logo_option == 'custom') {
            $watsonconv_logo_url = get_option('watsonconv_custom_logo') ? get_site_url() . get_option('watsonconv_custom_logo') : WATSON_CONV_URL . 'img/chatbot_logo.png';
        }

        if ($watsonconv_logo_option == 'off') {
            $watsonconv_logo_display = 'none';
            $title_padding = '0';
        }

        $exists_logo_url = true;
        if($inline_style && $watsonconv_logo_url) {
            $exists_logo_url = strpos($inline_style, $watsonconv_logo_url);
        }

        if (!$inline_style || $exists_logo_url === false) {
            $inline_style = '
                #message-container #messages .watson-message,
                    #watson-box #watson-header,
                    #watson-fab
                {
                    background-color: '.$main_color.';
                    color: '.$text_color.';
                }

                #message-container #messages .watson-message .typing-dot
                {
                    background-color: '.$text_color.';
                }

                #watson-box #message-send
                {
                    background-color: '. $main_color .';
                }

                #watson-box #message-send:hover
                {
                    background-color: '. ($is_dark ? $main_color_light : $main_color_dark) .';
                }
                
                #watson-box #message-send svg
                {
                fill: '. ($is_dark ? $text_color : 'rgba(0, 0, 0, 0.9)') .';
                }

                #message-container #messages .message-option
                {
                    border-color: '. ($is_dark ? $main_color : 'rgba(0, 0, 0, 0.9)') .';
                    color: '. ($is_dark ? $main_color : 'rgba(0, 0, 0, 0.9)') .';
                }

                #message-container #messages .message-option:hover
                {
                    border-color: '. ($is_dark ? $main_color_light : 'rgba(0, 0, 0, 0.6)') .';
                    color: '. ($is_dark ? $main_color_light : 'rgba(0, 0, 0, 0.6)') .';
                }

                #watson-box #messages > div:not(.message) > a
                {
                    color: '. ($is_dark ? $main_color : $text_color) .';
                }

                #watson-fab-float
                {
                    '.$position[0].': 5vmin;
                    '.$position[1].': 5vmin;
                }

                #watson-fab-icon
                {
                    font-size: '.$fab_icon_size.'pt
                }

                #watson-fab-text
                {
                    font-size: '.$fab_text_size.'pt
                }

                #watson-box .watson-font
                {
                    font-size: '.$font_size.'pt;
                }
                
                #watson-header .watson-font {
                    padding-left: ' . $title_padding . ';
                }
                
                #watson-box .chatbox-logo
                {
                    display: ' . $watsonconv_logo_display . ';
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    background-image: url("' . $watsonconv_logo_url . '");
                    background-color: white;
                    background-size: 100% 100%;
                    background-repeat: no-repeat;
                    border: solid 1px white;
                    position: absolute;
                    top: 50%;
                    left: 7%;
                    transform: translate(-50%,-50%);
                    -webkit-transform: translate(-50%,-50%);
                    -moz-transform: translate(-50%,-50%);
                }

                #watson-float
                {
                    '.$position[0].': 5vmin;
                    '.$position[1].': 5vmin;
                }
                #watson-box
                {
                    width: '.(0.825*$messages_height + 4.2*$font_size).'pt;
                    height: auto;
                }
                #message-container
                {
                    height: '.$messages_height.'pt
                }' . 
                sprintf(
                    $full_screen_query, 
                    '#watson-float #watson-box
                    {
                        width: 100%;
                        height: 100%;
                    }

                    #watson-box
                    {
                        max-width: 100%;
                    }

                    #watson-box .watson-font
                    {
                        font-size: '.$font_size_fs.'pt;
                    }
                
                    #watson-float
                    {
                        top: 0;
                        right: 0;
                        bottom: 0;
                        left: 0;
                        transform: translate(0, 0) !important;
                    }

                    #watson-float #message-container
                    {
                        height: auto;
                    }
                    #chatbox-body
                    {           
                        display: flex; 
                        flex-direction: column;
                    }'
                );
            
            if (is_null($force_full_screen)) {
                update_option('watsonconv_css_cache', $inline_style);
            }
        }

        wp_add_inline_style('watsonconv-chatbox', $inline_style);
    }

    private static function luminance($srgb) {
        $lin_rgb = array_map(function($val) {
            $val /= 255;

            if ($val <= 0.03928) {
                return $val / 12.92;
            } else {
                return pow(($val + 0.055) / 1.055, 2.4);
            }
        }, $srgb);

        return 0.2126 * $lin_rgb[0] + 0.7152 * $lin_rgb[1] + 0.0722 * $lin_rgb[2];
    }

    private static function get_context_var() {
        $context = new \stdClass();
        $current_user = wp_get_current_user();
        if ($current_user->ID != 0) {
            $fname_label = get_option('watsonconv_fname_var');
            $lname_label = get_option('watsonconv_lname_var');
            $nname_label = get_option('watsonconv_nname_var');
            $email_label = get_option('watsonconv_email_var');
            $login_label = get_option('watsonconv_login_var');

            $first_name = get_user_meta($current_user->ID, 'first_name', true);
            $last_name = get_user_meta($current_user->ID, 'last_name', true);
            $nickname = get_user_meta($current_user->ID, 'nickname', true);
            
            if ($fname_label && !empty($first_name)) {
                $context->$fname_label = $first_name;
            }
            if ($lname_label && !empty($last_name)) {
                $context->$lname_label = $last_name;
            }
            if ($nname_label && !empty($nickname)) {
                $context->$nname_label = $nickname;
            }

            if ($email_label && $current_user->has_prop('user_email')) {
                $context->$email_label = $current_user->get('user_email');
            }
            if ($login_label && $current_user->has_prop('user_login')) {
                $context->$login_label = $current_user->get('user_login');
            }
        }

        $plugin_label = get_option('watsonconv_plugin_version_var');
        $plugin_version = self::get_version();

        if ($plugin_label && !empty($plugin_version)) {
            $context->$plugin_label = $plugin_version;
        }


        // Ensure user_defined is not passed as an empty object
        if (count((array)$context) > 0) {
            return array(
                'skills' => array(
                    'main skill' => array(
                        'user_defined' => $context
                    )
                )
            );
        } else {
            return new \stdClass();
        }
    }

    private static function get_settings() {
        $twilio_config = get_option('watsonconv_twilio');

        $call_configured = (bool)(
            !empty($twilio_config['sid']) && 
            !empty($twilio_config['auth_token']) && 
            get_option('watsonconv_twiml_sid') &&
            get_option('watsonconv_call_id') &&
            get_option('watsonconv_call_recipient')
        );

        $full_screen_settings = get_option('watsonconv_full_screen');
        $full_screen_query = isset($full_screen_settings['query']) ?
            $full_screen_settings['query'] : '@media screen and (max-width:640px) { %s }';

        $url = trailingslashit( get_home_url( NULL, '', 'rest' ) );
        if ( 'index.php' !== substr( $url, 9 ) ) {
            $url .= 'index.php';
        }
        $url = add_query_arg( 'rest_route', '/watsonconv/v1/message', $url );

        return array(
            'delay' => (int) get_option('watsonconv_delay', 0),
            'minimized' => get_option('watsonconv_minimized', 'no'),
            'position' => explode('_', get_option('watsonconv_position', 'bottom_right')),
            'title' => get_option('watsonconv_title', ''),
            'clearText' => get_option('watsonconv_clear_text', 'Clear Messages'),
            'messagePrompt' => get_option('watsonconv_message_prompt', 'Type a Message'),
            'fullScreenQuery' => substr($full_screen_query, 7, -7),
            'showSendBtn' => get_option('watsonconv_send_btn', 'no'),
            'typingDelay' => get_option('watsonconv_typing_delay', 'no'),
            'fabConfig' => array(
                'iconPos' => get_option('watsonconv_fab_icon_pos', 'left'),
                'text' => get_option('watsonconv_fab_text', '')
            ),
            'callConfig' => array(
                'useTwilio' => get_option('watsonconv_use_twilio', 'no'),
                'configured' => $call_configured,
                'recipient' => get_option('watsonconv_call_recipient'),
                'callTooltip' => get_option('watsonconv_call_tooltip'),
                'callButton' => get_option('watsonconv_call_button'),
                'callingText' => get_option('watsonconv_calling_text')
            ),
            'context' => self::get_context_var(),
            'nonce' => wp_create_nonce('wp_rest'),
            'apiUrl' => $url,
        );
    }

    public static function chatbox_popup() {
        $ip_addr = API::get_client_ip();

        $page_selected =
            get_option('watsonconv_show_on', 'all') == 'all' ||
            (is_front_page() && get_option('watsonconv_home_page', 'false') == 'true') ||
            is_page(get_option('watsonconv_pages', array(-1))) ||
            is_single(get_option('watsonconv_posts', array(-1))) ||
            in_category(get_option('watsonconv_categories', array(-1)));

        $total_requests = get_option('watsonconv_total_requests', 0) +
            get_transient('watsonconv_total_requests') ?: 0;
        $client_requests = get_option("watsonconv_requests_$ip_addr", 0) +
            get_transient("watsonconv_requests_$ip_addr") ?: 0;

        $credentials = get_option('watsonconv_credentials');
        $is_enabled = !empty($credentials) && (!isset($credentials['enabled']) || $credentials['enabled'] == 'true');

        if ($page_selected &&
            (get_option('watsonconv_use_limit', 'no') == 'no' ||
                $total_requests < get_option('watsonconv_limit', 10000)) &&
            (get_option('watsonconv_use_client_limit', 'no') == 'no' ||
                $client_requests < get_option('watsonconv_client_limit', 100)) &&
            $is_enabled) {

            self::enqueue_styles();
            $settings = self::get_settings();
            
            if ($settings['callConfig']['useTwilio'] == 'yes' && $settings['callConfig']['configured']) {
                wp_enqueue_script('twilio-js', 'https://media.twiliocdn.com/sdk/js/client/v1.4/twilio.min.js');
            }

            wp_enqueue_script('watsonconv-chat-app');
            wp_localize_script('watsonconv-chat-app', 'watsonconvSettings', $settings);
        }
    }

    public static function chatbox_shortcode() {
        $ip_addr = API::get_client_ip();

        $total_requests = get_option('watsonconv_total_requests', 0) +
            get_transient('watsonconv_total_requests') ?: 0;
        $client_requests = get_option("watsonconv_requests_$ip_addr", 0) +
            get_transient("watsonconv_requests_$ip_addr") ?: 0;

        $credentials = get_option('watsonconv_credentials');
        $is_enabled = !empty($credentials) && (!isset($credentials['enabled']) || $credentials['enabled'] == 'true');

        if ((get_option('watsonconv_use_limit', 'no') == 'no' ||
                $total_requests < get_option('watsonconv_limit', 10000)) &&
            (get_option('watsonconv_use_client_limit', 'no') == 'no' ||
                $client_requests < get_option('watsonconv_client_limit', 100)) &&
            $is_enabled) 
        {
            if (!wp_script_is('watsonconv-chat-app', 'enqueued')) {
                self::enqueue_styles();
                $settings = self::get_settings();
                
                if ($settings['callConfig']['useTwilio'] == 'yes' && $settings['callConfig']['configured']) {
                    wp_enqueue_script('twilio-js', 'https://media.twiliocdn.com/sdk/js/client/v1.4/twilio.min.js');
                }
    
                wp_enqueue_script('watsonconv-chat-app');
                wp_localize_script('watsonconv-chat-app', 'watsonconvSettings', $settings);
            }

            return '<div id="watsonconv-inline-box"></div>';
        }

        return '';
    }

    public static function render_div() {
        ?>
            <div id="watsonconv-floating-box"></div>
        <?php
    }

    public static function register_scripts() {
        wp_register_script('watsonconv-chat-app', WATSON_CONV_URL.'app.js', array('jquery'), self::get_version(), true);
        wp_register_style('watsonconv-chatbox', WATSON_CONV_URL.'css/chatbox.css', array('dashicons'), self::get_version());
        wp_enqueue_script('wp-api');
        wp_localize_script( 'wp-api', 'wpApiSettings', array( 'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ) ) );
    }
}
