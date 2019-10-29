<?php
namespace WatsonConv\Settings;

class Setup {
    const SLUG = 'watson_asst_setup';

    public static function init_page() {
        add_submenu_page(Main::SLUG, 'Watson Assistant Setup', 'Set Up Chatbot',
            'manage_options', self::SLUG, array(__CLASS__, 'render_page'));
    }

    public static function init_settings() {
        self::init_credential_settings();
    }

    public static function render_page() {
        ?>
        <div class="wrap" style="max-width: 95em">
            <h2><?php esc_html_e('Set Up Your Chatbot', self::SLUG); ?></h2>

            <?php
            Main::render_isv_banner();
            settings_errors();
            ?>

            <h2 class="nav-tab-wrapper">
                <a onClick="switch_tab('build')" class="nav-tab nav-tab-active build_tab">1. Building a chatbot</a>
                <a onClick="switch_tab('workspace')" class="nav-tab workspace_tab">2. Plugin Setup</a>
                <a onClick="switch_tab('errors')" class="nav-tab errors_tab">Having Issues?</a>
            </h2>

            <form action="options.php" method="POST">
                <div class="tab-page build_page"><?php self::render_build(); ?></div>

                <?php settings_fields(self::SLUG); ?>

                <div class="tab-page workspace_page" style="display: none">
                    <?php self::main_setup_description(); ?>
                    <?php do_settings_sections(self::SLUG) ?>
                    <?php submit_button(); ?>

                    <p  class="update-message notice inline notice-warning notice-alt"
                        style="padding-top: 0.5em; padding-bottom: 0.5em">
                        <b>Note:</b> If you have a server-side caching plugin installed such as
                        WP Super Cache, you may need to clear your cache after changing settings or
                        deactivating the plugin. Otherwise, your action may not take effect.
                    <p>
                </div>
                <div class="tab-page errors_page" style="display: none">
                    <?php self::errors_page(); ?>
                </div>
            </form>
        </div>
        <?php
    }

    public static function render_intro() {
        ?>
        <p>
            Watson Assistant, formerly known as Watson Conversation, provides a clear and user-friendly
            interface to build virtual assistants to speak with your users. With the use of this plugin,
            you can add these virtual assistants, or <b>chatbots</b>, to your website with minimal
            technical knowledge or work.
        </p>
        <p>
            This diagram shows the overall architecture of a complete solution:
            <img
                    src="https://console.bluemix.net/docs/api/content/services/conversation/images/conversation_arch_overview.png?lang=en-US"
                    alt="Flow diagram of the service"
                    class="style-scope doc-content"
                    style="width:100%; border: 1px solid grey"
            >
        </p>
        <p>
            When you use this plugin, the <strong>Back-end system</strong> is Wordpress, while the
            <strong>Application</strong> and <strong>Interface</strong> are both included in this
            plugin. Therefore, all you need to worry about is bulding your chatbot in your Watson
            Assistant workspace and this plugin will take care of the rest.
        </p>
        <button type="button" class="button button-primary" onClick="switch_tab('build')">Next</button>
        <?php
    }

    public static function render_build() {
        ?>
        <p>
            Watson Assistant, formerly known as Watson Conversation, provides a clear and user-friendly
            interface to build virtual assistants to speak with your users. With the use of this plugin,
            you can add these virtual assistants, or <b>chatbots</b>, to your website with minimal
            technical knowledge or work.
        </p>
        <p>
            Before you can use Watson Assistant on your website, you'll have to build your chatbot using
            our user-friendly interface.
        </p>
        <p>
            <a href="https://cocl.us/bluemix-registration" rel="nofollow" target="_blank">
                Sign up here</a>
            for a free IBM Cloud Lite account to get started. If you have an account but have not started
            with Watson Assistant yet,
            <a href="https://console.bluemix.net/registration?target=/catalog/services/conversation">click here</a>
            to get started. Once you launch the Watson Assistant tool, you will be shown how to proceed
            to create your chatbot. You may find the following resources helpful.
        </p>
        <ul>
            <p><li>
                This video provides an overview of the Watson Assistant tool:
            </li></p>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/sSfTcxDrmSI" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            <p><li>
                You can also learn how to set up your Watson Assistant chatbot with
                <a href="https://cocl.us/build-a-chatbot" rel="nofollow" target="_blank">this quick free course</a>.
            </li></p>
            <p><li>
                See
                <a href="https://cocl.us/watson-conversation-help" rel="nofollow" target="_blank">
                    the Watson Assistant documentation</a>
                for more information.
            </li></p>
        </ul>
        <p>
            Once you've created your workspace and built your chatbot using the outlined resources,
            you're ready to connect it to your website!
        </p>

        <button type="button" class="button button-primary" onClick="switch_tab('workspace')">Next</button>
        <?php
    }

    public static function main_setup_description() {
        $credentials = get_option('watsonconv_credentials');
        $cred_type = empty($credentials['type']) ? 'basic' : $credentials['type'];
        ?>
        <p>
            This is where you  get to finally connect the Watson Assistant chatbot you built to your
            website. To do this, you need to get the URL and credentials of your Watson Assistant.
            To find these values, navigate to the Assistant you've built and activate <b>View API Details</b> link.
        </p>
        <table width="100%"><tr>
                <td class="responsive" style="padding: 10px; text-align: center;">
                    <div>
                        <img src="<?php echo WATSON_CONV_URL ?>/img/credentials_v2_1.png">
                    </div>
                </td>
                <td class="responsive" style="padding: 10px; text-align: center;">
                    <div>
                        <img src="<?php echo WATSON_CONV_URL ?>/img/credentials_v2_2.png">
                    </div>
                </td>
            </tr></table>
        <!--<p>
            Please select what kind of credentials do you have:<br>
            <label for="watsonconv_credentials_basic">
                <input
                        type="radio"
                        id="watsonconv_credentials_basic"
                        name="watsonconv_credentials[type]"
                        value="basic"
                    <?php checked($cred_type, 'basic'); ?>
                >
                <strong>Username/Password</strong>
            </label>
            <br>
            <label for="watsonconv_credentials_iam">
                <input
                        type="radio"
                        id="watsonconv_credentials_iam"
                        name="watsonconv_credentials[type]"
                        value="iam"
                    <?php checked($cred_type, 'iam'); ?>
                >
                <strong>API Key</strong>
            </label>
        </p>-->
        <!--<table width="100%"><tr>
            <td class="responsive" style="padding: 10px; text-align: center;">
                <label for="watsonconv_credentials_basic">
                    <input 
                        type="radio" 
                        id="watsonconv_credentials_basic" 
                        name="watsonconv_credentials[type]" 
                        value="basic"
                        <?php checked($cred_type, 'basic'); ?>
                    >
                    <strong>Username/Password</strong>
                    <div>
                        <img src="<?php echo WATSON_CONV_URL ?>/img/credentials.jpg">
                    </div>
                </label>
            </td>
            <td class="responsive" style="padding: 10px; text-align: center;">
                <label for="watsonconv_credentials_iam">
                    <input 
                        type="radio" 
                        id="watsonconv_credentials_iam" 
                        name="watsonconv_credentials[type]" 
                        value="iam"
                        <?php checked($cred_type, 'iam'); ?>
                    >
                    <strong>API Key</strong>
                    <div>
                        <img src="<?php echo WATSON_CONV_URL ?>/img/credentials_iam.jpg">
                    </div>
                </label>
            </td>
        </tr></table>-->
        <strong>How to get API v2 credentials?</strong>
        <ul>
            <li><a href="https://console.bluemix.net/dashboard/apps">Go to your IBM Cloud Dashboard</a>. If nothing shows up, be patient. Sometimes it takes a while to load.</li>
            <li>Click on your Watson Assistant service name in Services list.</li>
            <li>Click "Launch Watson Assistant".</li>
            <li>On the tool's page go to <strong>"Assistants"</strong> tab.</li>
            <li>On the Assistants tab, call the menu of your Assistant by clicking on the "threee dots" button on the right and select "Settings" in the menu. </li>
            <li>On the Assistant Settings tab, select "API Details" from the menu on the left.</li>
            <li>Copy "Assistant URL" and "Api Key" values.</li>
        </ul>
        <p>
            Enter these values in their corresponding fields below. <strong>Please fill Username field with "apikey" word.</strong> Once you click
            "Save Changes", the plugin will verify if the credentials are valid and notify
            you of whether or not the configuration was successful.
        </p>
        <?php
    }

    public static function errors_page() {
        ?>
        <p>
            If you have successfully connected your chatbot to the website on the previous page,
            but your chatbot doesn't seem to be working on your website, then you can create a support
            post <a href="https://wordpress.org/support/plugin/conversation-watson" target="_blank">
                in this forum</a>.
        </p>
        
        <?php self::render_error_log(); ?>
        
        <?php
    }

    public static function render_error_log($offset = 0) {
        // Getting error log from database
        $log_entries_number = \WatsonConv\Storage::count_rows("debug_log");
        $log_limit = ($log_entries_number > 50) ? 50 : $log_entries_number;
        $log_select_options = array(
            "order" => array(
                \WatsonConv\Storage::order("debug_log", "id", "DESC")
            ),
            "limit" => $log_limit
        );
        $log = \WatsonConv\Storage::select("debug_log", $log_select_options);
        // Cutting array to 50 elements
        $log = array_slice($log, $offset, $log_limit);



        // $load_more_button = '<button type="button" id="watsonconv_load_more_log_messages" class="button-primary">
        //     Load 50 more
        // </button>';
        $formatted = $log_limit > 1 ? $log_limit . ' messages' : 'a message';
        $copy_logs_button = "<button type=\"button\" id=\"watsonconv_copy_log_messages\" class=\"button-primary\">
            Copy {$formatted} to clipboard
        </button>";

        // Link to download full JSON log
        // Unique id for log fetching event
        $get_log_event_id = uniqid();
        // Nonce for that action
        $fetch_nonce = wp_create_nonce( "log_fetch_{$get_log_event_id}" );
        // Link creation timestamp
        update_option("watsonconv_log_fetch_ts", time(), "no");
        // Writing new action id and erasing old one
        update_option("watsonconv_log_fetch_event", $get_log_event_id, "no");

        $rest_nonce = wp_create_nonce( "wp_rest" );
        $rest_url = get_rest_url();
        $first_param_separator = "?";
        if( !(strpos($rest_url, $first_param_separator) === false) ) {
            $first_param_separator = "&";
        }
        $full_rest_endpoint = "{$rest_url}watsonconv/v1/logs/{$first_param_separator}fetch_nonce={$fetch_nonce}&_wpnonce={$rest_nonce}";
        $full_log_link = "You can <a href=\"{$full_rest_endpoint}\" target='_blank' download>download full log file with complete information</a>.<br>";

        $download_failed_text = "<strong>This link is a single use and has a short lifespan to avoid exposure of your site's sensitive data.<br>If download failed, please refresh the page and try to download log again.</strong>";
        
        echo "<p>{$full_log_link} {$download_failed_text}</p>";

        ?>
        <p>Information included in file:</p>
        <ul>
            <li>Watson Assistant plugin log messages</li>
            <li>Watson Assistant plugin settings</li>
            <li>Versions of PHP, MySQL, and Wordpress</li>
            <li>List of installed WordPress plugins</li>
            <li>Site address</li>
            <li>List of database tables with information about database engine <strong>(without any data from tables)</strong>.</li>
        </ul>
        <p>
            <strong>Please don't post this file and your credentials (password/API keys) on the public forum. Instead, if required, use the following
            e-mail box for private communication: <a href="mailto:help@intela.io">help@intela.io</a></strong>
        </p>

        <?php

        // Log container start
        echo '<div class="watson-settings-box" id="watsonconv_log_container">';
        
        // Total messages number and number of shown ones
        $total_messages_text = "<strong>{$log_entries_number} log messages total.</strong>";
        $showing_messages_text = "<span id=\"watsonconv_log_messages_shown\">Showing last {$log_limit}.</span>";
        echo "<div>{$total_messages_text} {$showing_messages_text}</div> {$copy_logs_button}";

        // Current event code
        $current_event = 0;
        // Iterating through log messages and outputting them
        foreach($log as $log_message) {
            // Event id
            $event = $log_message["p_event"];
            if($event != $current_event) {
                $current_event = $event;
                $timestamp = $log_message["s_created"];
                $human_date = date("Y-m-d H:i:s", $timestamp);
                echo "<hr>";
                echo "<p class=\"watsonconv_log_event\" id=\"watsonconv_log_event_{$event}\"><i>Event {$event}, {$human_date}</i></p>";
            }
            // Message string
            $message_id = $log_message["id"];
            $message_text = $log_message["p_message"];
            $message_html_class = "watsonconv_log_message watsonconv_event_message_{$event}";
            $message_html_id = "watsonconv_log_message_{$message_id}";
            $message_content = "<strong>#{$message_id}:</strong> {$message_text}";
            echo "<p class=\"{$message_html_class}\" id=\"{$message_html_id}\">{$message_content}</p>";
            // Message details
            $details_html_class = "watson-settings-box watsonconv_log_details watsonconv_event_details_{$event}";
            $details_html_id = "watsonconv_log_details_{$message_id}";
            $message_details = "";
            if(isset($log_message["p_details"])) {
                $message_details = $log_message["p_details"];
            }
            echo "<pre class=\"{$details_html_class}\" id=\"{$details_html_id}\">{$message_details}</pre>";
        }

        echo '</div>';
    }

    // ------------ Workspace Credentials ---------------

    // If an installation of this plugin has a credentials format from the versions before 0.3.0,
    // migrate them to the new format.
    public static function migrate_old_credentials() {
        try {
            $credentials = get_option('watsonconv_credentials');

            if (!isset($credentials['workspace_url']) && isset($credentials['url']) && isset($credentials['id'])) {
                $credentials['workspace_url'] =
                    rtrim($credentials['url'], '/').'/workspaces/'.$credentials['id'].'/message/';

                unset($credentials['url']);
                update_option('watsonconv_credentials', $credentials);
            }

            if (!isset($credentials['auth_header']) && isset($credentials['username']) && isset($credentials['password'])) {
                $credentials['auth_header'] = 'Basic ' . base64_encode(
                        $credentials['username'].':'.
                        $credentials['password']
                    );

                update_option('watsonconv_credentials', $credentials);
            }

            if (isset($credentials['password']) && !isset($credentials['api_key'])) {
                $credentials['api_key'] = $credentials['password'];
                update_option('watsonconv_credentials', $credentials);
            }


            if (!isset($credentials['auth_header']) && isset($credentials['username']) && isset($credentials['api_key'])) {
                $credentials['auth_header'] = 'Basic ' . base64_encode(
                        $credentials['username'].':'.
                        $credentials['api_key']
                    );

                update_option('watsonconv_credentials', $credentials);
            }
        } catch (\Exception $e) {}
    }

    public static function init_credential_settings() {
        $settings_page = self::SLUG;

        add_settings_section('watsonconv_credentials', 'Assistant Details and Service Credentials',
            array(__CLASS__, 'workspace_description'), $settings_page);

        add_settings_field('watsonconv_enabled', '', array(__CLASS__, 'render_enabled'),
            $settings_page, 'watsonconv_credentials');
        add_settings_field('watsonconv_workspace_url', 'Assistant URL', array(__CLASS__, 'render_url'),
            $settings_page, 'watsonconv_credentials');
        add_settings_field('watsonconv_username', 'Username', array(__CLASS__, 'render_username'),
            $settings_page, 'watsonconv_credentials');
//        add_settings_field('watsonconv_password', 'Password', array(__CLASS__, 'render_password'),
//            $settings_page, 'watsonconv_credentials');
        add_settings_field('watsonconv_api_key', 'API Key', array(__CLASS__, 'render_api_key'),
            $settings_page, 'watsonconv_credentials');

        register_setting(self::SLUG, 'watsonconv_credentials', array(__CLASS__, 'validate_credentials'));
    }

    private static function get_debug_string($response) {
        $response_body = wp_remote_retrieve_body($response);

        $json_data = @json_decode($response_body);

        if (empty($response_body)) {
            $response_string = var_export($response, true);
        } else if (!is_null($json_data) && json_last_error() === JSON_ERROR_NONE) {
            $response_string = json_encode($json_data, defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 128);
        } else if (is_array($response_body)) {
            $response_string = json_encode($response_body, defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 128);
        } else if (is_string($response_body)) {
            $response_string = $response_body;
        } else {
            $response_string = var_export($response_body, true);
        }

        $response_string = str_replace('\\/', '/', $response_string);

        return '<a id="error_expand">Click here for debug information.</a>
            <pre id="error_response" style="display: none;">'.$response_string.'</pre>';
    }

    public static function validate_credentials($credentials) {
        $old_credentials = get_option('watsonconv_credentials');
        $credentials['type'] = 'basic';

        if (!isset($credentials['enabled'])) {
            $old_credentials['enabled'] = 'false';
            return $old_credentials;
        }

        if (empty($credentials['workspace_url'])) {
            add_settings_error('watsonconv_credentials', 'invalid-id', 'Please enter an Assistant URL.');
            $empty = true;
        }

        if ($credentials['type'] == 'iam') {
            if (empty($credentials['api_key'])) {
                add_settings_error('watsonconv_credentials', 'invalid-api-key', 'Please enter an API key.');
                $empty = true;
            }
        } else {
            if (empty($credentials['username'])) {
                add_settings_error('watsonconv_credentials', 'invalid-username', 'Please enter a username.');
                $empty = true;
            }
            if (empty($credentials['api_key'])) {
                add_settings_error('watsonconv_credentials', 'invalid-api-key', 'Please enter an API key.');
                $empty = true;
            }
        }

        if (isset($empty)) {
            return $old_credentials;
        }

        if ($credentials == $old_credentials) {
            return $credentials;
        }

        if ($credentials['type'] == 'iam') {
            $auth_header = 'Basic ' . base64_encode(
                    'apikey:'.
                    $credentials['api_key']
                );
        } else {
            $auth_header = 'Basic ' . base64_encode(
                    $credentials['username'].':'.
                    $credentials['api_key']
                );
        }

        // Detect endpoint URL API version and validate credentials
        $is_success = false;
        switch (\WatsonConv\API::detect_api_version($credentials['workspace_url'])) {
            case 'v1':
                $is_success = self::check_api_v1($credentials['workspace_url'], $auth_header);
                break;
            case 'v2':
                $is_success = self::check_api_v2($credentials['workspace_url'], $auth_header);
                break;
            default:
                add_settings_error('watsonconv_credentials', 'invalid-credentials',
                    'Unable to determine endpoint URL version. Please ensure you entered valid Assistant URL (v2) or Workspace URL (v1)');
        }
        if (!$is_success) {
            return get_option('watsonconv_credentials');
        }

//        $response = wp_remote_post(
//            $credentials['workspace_url'].'?version='.\WatsonConv\API::API_VERSION,
//            array(
//                'timeout' => 20,
//                'headers' => array(
//                    'Authorization' => $auth_header,
//                    'Content-Type' => 'application/json'
//                ), 'body' => json_encode(array(
//                    'input' => new \stdClass,
//                    'context' => new \stdClass()
//                ))
//            )
//        );
//
//        $response_code = wp_remote_retrieve_response_code($response);
//        $response_code_string = empty($response_code) ? '' : ' ('.$response_code.')';
//
//        $debug_info = self::get_debug_string($response);
//
//        if (is_wp_error($response)) {
//            add_settings_error('watsonconv_credentials', 'invalid-credentials',
//                'Unable to connect to Watson server'.$response_code_string.'. ' . $debug_info);
//            return get_option('watsonconv_credentials');
//        } else if ($response_code == 401) {
//            add_settings_error('watsonconv_credentials', 'invalid-credentials',
//                'Please ensure you entered valid credentials and workspace URL'.$response_code_string.'. ' . $debug_info);
//            return get_option('watsonconv_credentials');
//        } else if ($response_code == 404 || $response_code == 400) {
//            add_settings_error('watsonconv_credentials', 'invalid-id',
//                'Please ensure you entered a valid workspace URL'.$response_code_string.'. ' . $debug_info);
//            return get_option('watsonconv_credentials');
//        } else if ($response_code != 200) {
//            add_settings_error('watsonconv_credentials', 'invalid-url',
//                'Please ensure you entered a valid workspace URL'.$response_code_string.'. ' . $debug_info);
//            return get_option('watsonconv_credentials');
//        }

        $credentials['auth_header'] = $auth_header;

        add_settings_error(
            'watsonconv_credentials',
            'valid-credentials',
            'Your chatbot has been successfully connected to your Wordpress site. <a href="'
            .get_site_url().'">Browse your website</a> to see it in action.',
            'updated'
        );

        return $credentials;
    }

    /**
     * Assuming Workspace/Skill URL (v1) is provided, try to sign in
     *
     * @param string $endpoint_url - Workspace/Skill URL
     * @param string $auth_header - HTTP Auth header
     * @return bool - true if API v1 service accepts credentials
     */
    private static function check_api_v1($endpoint_url, $auth_header) {
        $response = wp_remote_post(
            $endpoint_url.'?version='.\WatsonConv\API::API_VERSION,
            array(
                'timeout' => 20,
                'headers' => array(
                    'Authorization' => $auth_header,
                    'Content-Type' => 'application/json'
                ), 'body' => json_encode(array(
                'input' => new \stdClass,
                'context' => new \stdClass()
            ))
            )
        );

        $response_code = wp_remote_retrieve_response_code($response);
        $response_code_string = empty($response_code) ? '' : ' ('.$response_code.')';

        $debug_info = self::get_debug_string($response);
        if (is_wp_error($response)) {
            add_settings_error('watsonconv_credentials', 'invalid-credentials',
                'Unable to connect to Watson server'.$response_code_string.'. ' . $debug_info);
            return false;
        } else if ($response_code == 401) {
            add_settings_error('watsonconv_credentials', 'invalid-credentials',
                'Please ensure you entered valid credentials and workspace URL'.$response_code_string.'. ' . $debug_info);
            return false;
        } else if ($response_code == 404 || $response_code == 400) {
            add_settings_error('watsonconv_credentials', 'invalid-id',
                'Please ensure you entered a valid workspace URL'.$response_code_string.'. ' . $debug_info);
            return false;
        } else if ($response_code != 200) {
            add_settings_error('watsonconv_credentials', 'invalid-url',
                'Please ensure you entered a valid workspace URL'.$response_code_string.'. ' . $debug_info);
            return false;
        } else {
            return true; // success
        }
    }

    /**
     * Assuming Assistant URL (v2) is provided, try to sign in
     *
     * @param string $endpoint_url - Workspace/Skill URL
     * @param string $auth_header - HTTP Auth header
     * @return bool - true if API v2 service accepts credentials
     */
    private static function check_api_v2($endpoint_url, $auth_header) {
        $response = wp_remote_post(
            $endpoint_url.'?version='.\WatsonConv\API::API_VERSION_2,
            array(
                'timeout' => 20,
                'headers' => array(
                    'Authorization' => $auth_header,
                    'Content-Type' => 'application/json'
                ), 'body' => json_encode(array(
                'input' => new \stdClass,
                'context' => new \stdClass()
            ))
            )
        );

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code == 201) {
            # Note, that according to Watson Assistant documentation successful response code here should be 200
            $response_body = json_decode(wp_remote_retrieve_body($response), true);
            if (isset($response_body) && isset($response_body['session_id']) &&
                preg_match('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $response_body['session_id'])) {
                return true;  // success
            }
        }

        // something went wrong, get more info
        $debug_info = self::get_debug_string($response);
        $response_code_string = empty($response_code) ? '' : ' ('.$response_code.')';
        if (is_wp_error($response)) {
            add_settings_error('watsonconv_credentials', 'invalid-credentials',
                'Unable to connect to Watson Assistant service'.$response_code_string.'. ' . $debug_info);
        } else if ($response_code == 401) {
            add_settings_error('watsonconv_credentials', 'invalid-credentials',
                'Please ensure you entered valid Assistant URL and credentials'.$response_code_string.'. ' . $debug_info);
        } else if ($response_code == 404 || $response_code == 400) {
            add_settings_error('watsonconv_credentials', 'invalid-id',
                'Please ensure you entered a valid Assistant URL'.$response_code_string.'. ' . $debug_info);
        } else {
            add_settings_error('watsonconv_credentials', 'invalid-url',
                'Please ensure you entered a valid Assistant URL'.$response_code_string.'. ' . $debug_info);
        }
        return false;

//        var_dump($response_body);
//        die();
//        return false;
    }

    public static function workspace_description($args) {
        ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>" class="basic_cred">
            <?php esc_html_e('Specify the Assistant URL, username and API Key for your Watson
                Assistant below.', self::SLUG) ?> <br />
        </p>
        <!--
        <p id="<?php echo esc_attr( $args['id'] ); ?>" class="iam_cred">
            <?php esc_html_e('Specify the Assistant URL and API key for your Watson
                Assistant below.', self::SLUG) ?> <br />
        </p>
        -->
<!--        <a href="" id="--><?php //echo esc_attr( $args['id'] ); ?><!--_link">-->
<!--            --><?php //esc_html_e("I don't see a username and password in my credentials") ?>
<!--        </a>-->
<!--        <div id="--><?php //echo esc_attr( $args['id'] ); ?><!--_description">-->
<!--            <p>-->
<!--                If you have API Key only, please fill Username field with <code>apikey</code> word, and put API Key value into the Password field.-->
<!--                <br>-->
<!--                Like following:-->
<!--            </p>-->
<!--            <p>-->
<!--                <strong>Username</strong>: apikey-->
<!--                <br>-->
<!--                <strong>Password</strong>: XxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx-->
<!--            </p>-->
<!--        </div>-->
        <?php
    }

    public static function render_enabled() {
        $credentials = get_option('watsonconv_credentials');
        $enabled = (isset($credentials['enabled']) ? $credentials['enabled'] : 'true') == 'true';
        ?>
        <fieldset>
            <input
                    type="checkbox" id="watsonconv_enabled"
                    name="watsonconv_credentials[enabled]"
                    value="true"
                <?php echo $enabled ? 'checked' : '' ?>
            />
            <label for="watsonconv_enabled">
                Enable Chatbot
            </label>
        </fieldset>
        <?php
    }

    public static function render_username() {
        $credentials = get_option('watsonconv_credentials');
        ?>
        <input name="watsonconv_credentials[username]" class="watsonconv_credentials basic_cred"
               id="watsonconv_username" type="text"
               value="<?php echo empty($credentials['username']) ? 'apikey' : $credentials['username'] ?>"
               placeholder="e.g. xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
               style="max-width: 24em; width: 100%;"/>
        <?php
    }

    public static function render_password() {
        $credentials = get_option('watsonconv_credentials');
        ?>
        <input name="watsonconv_credentials[password]" class="watsonconv_credentials basic_cred"
               id="watsonconv_password" type="password"
               value="<?php echo empty($credentials['password']) ? '' : $credentials['password'] ?>"
               style="max-width: 24em; width: 100%;" />
        <?php
    }

    public static function render_url() {
        $credentials = get_option('watsonconv_credentials');
        ?>
        <input name="watsonconv_credentials[workspace_url]" class="watsonconv_credentials"
               id="watsonconv_workspace_url" type="text"
               value="<?php echo empty($credentials['workspace_url']) ? '' : $credentials['workspace_url']; ?>"
               placeholder='e.g. https://gateway.watsonplatform.net/assistant/api/v2/assistants/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/sessions'
               style="max-width: 60em; width: 100%;" />
        <p  class="update-message notice inline notice-warning notice-alt"
            id="v1_url_notice"
            style="padding-top: 0.5em; padding-bottom: 0.5em; display: none;">
            <b>Warning:</b> You entered the credentials for old version of Watson Assistant (v1) that uses Skills (previously Workspaces). Please retrieve new credentials (v2) that uses Assistants as written above.
        <p>
        <?php
    }

    public static function render_api_key() {
        $credentials = get_option('watsonconv_credentials');
        ?>
        <input name="watsonconv_credentials[api_key]" class="watsonconv_credentials iam_cred"
               id="watsonconv_api_key" type="text"
               value="<?php echo empty($credentials['api_key']) ? '' : $credentials['api_key']; ?>"
               placeholder="e.g. XxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx"
               style="max-width: 30em; width: 100%;"/>
        <?php
    }

    public static function change_credentials_to_basic(){
        try{
            $credentials = get_option('watsonconv_credentials');
            if (isset($credentials['type']) && $credentials['type'] == 'iam'){
                $credentials['type'] = 'basic';
                $credentials['username'] = 'apikey';
                $credentials['password'] = $credentials['api_key'];
                update_option('watsonconv_credentials', $credentials);
            }
        } catch (\Exception $e) {}
    }
}
