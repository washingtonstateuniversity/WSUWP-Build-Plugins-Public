<?php
namespace WatsonConv;

register_activation_hook(WATSON_CONV_FILE, array('WatsonConv\API', 'init_rate_limit'));
register_deactivation_hook(WATSON_CONV_FILE, array('WatsonConv\API', 'uninit_rate_limit'));

add_action('watson_get_iam_token', array('WatsonConv\API', 'get_iam_token'));
add_action('watson_save_to_disk', array('WatsonConv\API', 'record_api_usage'));
add_action('watson_reset_total_usage', array('WatsonConv\API', 'reset_total_usage'));
add_action('watson_reset_client_usage', array('WatsonConv\API', 'reset_client_usage'));
add_action('rest_api_init', array('WatsonConv\API', 'register_routes'));
add_action('update_option_watsonconv_interval', array('WatsonConv\API', 'init_rate_limit'));
add_action('update_option_watsonconv_client_interval', array('WatsonConv\API', 'init_rate_limit'));
add_filter('cron_schedules', array('WatsonConv\API', 'add_cron_schedules'));
add_action( 'phpmailer_init', array('WatsonConv\API', 'on_before_mail_send'));
add_action( 'wp_mail_failed', array('WatsonConv\API', 'on_mail_error'));

class API {
    const API_VERSION = '2018-07-10';  // Workspace/Skill API (version 1)
    const API_VERSION_2 = '2019-02-28';  // Assistant API (version 2)

    const API_V1_URL_RE = '/\/api\/v1\/workspaces\/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\/message/';
    const API_V2_URL_RE = '/\/api\/v2\/assistants\/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\/sessions/';

    const ACTION_TO_SEND_CONTEXT_VARS = 'mail_context_vars';

    public static function register_routes() {
        $credentials = get_option('watsonconv_credentials');
        $is_enabled = !empty($credentials) && (!isset($credentials['enabled']) || $credentials['enabled'] == 'true');

        if ($is_enabled) {
            register_rest_route('watsonconv/v1', '/message',
                array(
                    'methods' => 'post',
                    'callback' => array(__CLASS__, 'route_request')
                )
            );
        }

        $twilio_config = get_option('watsonconv_twilio');

        if (!empty($twilio_config['sid']) && 
            !empty($twilio_config['auth_token']) && 
            get_option('watsonconv_twiml_sid') &&
            get_option('watsonconv_call_id') &&
            get_option('watsonconv_call_recipient'))
        {
            register_rest_route('watsonconv/v1', '/twilio-token',
                array(
                    'methods' => 'get',
                    'callback' => array(__CLASS__, 'twilio_get_token')
                )
            );

            register_rest_route('watsonconv/v1', '/twilio-call',
                array(
                    'methods' => 'post',
                    'callback' => array(__CLASS__, 'twilio_call')
                )
            );
        }

        register_rest_route('watsonconv/v1', '/test-email',
            array(
                'methods' => 'post',
                'callback' => array('\WatsonConv\Settings\Advanced', 'send_test_email')
            )
        );

        register_rest_route('watsonconv/v1', '/test-notification',
            array(
                'methods' => 'post',
                'callback' => array('\WatsonConv\Settings\Advanced', 'send_test_notification')
            )
        );

        register_rest_route('watsonconv/v1', '/get-logo',
            array(
                'methods' => 'post',
                'callback' => array('\WatsonConv\Settings\Customize', 'get_watson_logo')
            )
        );

    }

    public static function twilio_get_token(\WP_REST_Request $request) {
        $twilio_config = get_option('watsonconv_twilio');
        
        $TWILIO_ACCOUNT_SID = $twilio_config['sid'];
        $TWILIO_AUTH_TOKEN = $twilio_config['auth_token'];
        $TWILIO_TWIML_APP_SID = get_option('watsonconv_twiml_sid');

        $capability = new \Twilio\Jwt\ClientToken($TWILIO_ACCOUNT_SID, $TWILIO_AUTH_TOKEN);
        $capability->allowClientOutgoing($TWILIO_TWIML_APP_SID);
        $token = $capability->generateToken();
        return array(
            'identity' => $identity,
            'token' => $token,
        );
    }

    public static function twilio_call(\WP_REST_Request $request) {
        $response = new \Twilio\Twiml;
        
        $number = get_option('watsonconv_call_recipient');
        $dial = $response->dial(array('callerId' => get_option('watsonconv_call_id')));
        
        // wrap the phone number or client name in the appropriate TwiML verb
        // by checking if the number given has only digits and format symbols
        if (preg_match("/^[\d\+\-\(\) ]+$/", $number)) {
            $dial->number($number);
        } else {
            $dial->client($number);
        }

        echo header('Content-Type: text/xml');
        echo $response;
        die();
    }

    public static function route_request(\WP_REST_Request $request) {
        $usage_res = self::check_usage_allowed();
        if ($usage_res['allowed']) {
            $credentials = get_option('watsonconv_credentials');
            switch (self::detect_api_version($credentials['workspace_url'])) {
                case 'v1':
                    return self::route_request_v1($request);
                    break;
                case 'v2':
                    return self::route_request_v2($request);
                    break;
                default:
                    return new \WP_Error(
                        'config_error',
                        'Unable to determine Watson service endpoint version.',
                        503
                    );
            }
        } else {
            # reply with error message to client
            return self::reply_with_text($usage_res['message']);
        }
    }

    /**
     * Composes chat-bot reply text message
     *
     * @param string $message
     * @return array
     */
    private static function reply_with_text($message) {
        return array('output' => array(
            'generic' => array(
                array(
                    'response_type' => 'text',
                    'text' => $message
                )
            )
        ));
    }

    /**
     * Check if client have exceed the requests limit, if not update counters
     *
     * @return array|\WP_Error { (bool) allowed, (string) message }
     */
    private static function check_usage_allowed() {
        $is_allowed = false;
        $message = null;
        $ip_addr = self::get_client_ip();
        $total_requests = get_option('watsonconv_total_requests', 0) +
        get_transient('watsonconv_total_requests') ?: 0;
        $client_requests = get_option("watsonconv_requests_$ip_addr", 0) +
        get_transient("watsonconv_requests_$ip_addr") ?: 0;

        if (get_option('watsonconv_use_limit', 'no') == 'yes' &&
            $total_requests > get_option('watsonconv_limit', INF))
        {
//            return array('output' => array('text' =>
//                get_option('watsonconv_limit_message', "Sorry, I can't talk right now. Try again later.")
//            ));
            $message = get_option('watsonconv_limit_message');
            if (empty($message)) {
                $message = "Sorry, I can't talk right now. Try again later.";
            }
        } else if (get_option('watsonconv_use_client_limit', 'no') == 'yes' &&
            $client_requests > get_option('watsonconv_client_limit', INF))
        {
//            return array('output' => array('text' =>
//                get_option('watsonconv_client_limit_message', "Sorry, I can't talk right now. Try again later.")
//            ));
            $message = get_option('watsonconv_client_limit_message');
            if (empty($message)) {
                $message = "Sorry, I can't talk right now. Try again later.";
            }
        } else {
            set_transient(
                'watsonconv_total_requests',
                (get_transient('watsonconv_total_requests') ?: 0) + 1,
                MONTH_IN_SECONDS
            );
            set_transient(
                "watsonconv_requests_$ip_addr",
                (get_transient("watsonconv_requests_$ip_addr") ?: 0) + 1,
                MONTH_IN_SECONDS
            );

            $client_list = get_transient('watsonconv_client_list') ?: array();
            $client_list[$ip_addr] = true;
            set_transient('watsonconv_client_list', $client_list, DAY_IN_SECONDS);

            $credentials = get_option('watsonconv_credentials');
            $is_enabled = !empty($credentials) && (!isset($credentials['enabled']) || $credentials['enabled'] == 'true');

            if (!$is_enabled) {
                return new \WP_Error(
                    'config_error',
                    'Service not configured.',
                    503
                );
            }
            $is_allowed = true;
        }
        return array(
            'allowed' => $is_allowed,
            'message' => $message
        );
    }

    /**
     * Routes client request to Workspace/Skill API endpoint (v1)
     *
     * @param \WP_REST_Request $request
     * @return mixed|\WP_Error -- reply
     */
    private static function route_request_v1(\WP_REST_Request $request) {
        $body = $request->get_json_params();
        $credentials = get_option('watsonconv_credentials');

        $send_body = apply_filters(
            'watsonconv_user_message',
            array(
                'input' => empty($body['input']) ? new \stdClass() : $body['input'],
                'context' => empty($body['context']) ? new \stdClass() : $body['context']
            )
        );

        do_action('watsonconv_message_pre_send', $send_body);

        $response = wp_remote_post(
            $credentials['workspace_url'].'?version='.self::API_VERSION,
            array(
                'timeout' => 20,
                'headers' => array(
                    'Authorization' => $credentials['auth_header'],
                    'Content-Type' => 'application/json'
                ),
                'body' => json_encode($send_body)
            )
        );

        do_action('watsonconv_message_received', $response);

        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        $response_code = wp_remote_retrieve_response_code($response);

        $response_body = apply_filters('watsonconv_bot_message', $response_body);

        if ($response_code !== 200) {
            return self::reply_with_response_error($response);
        } else {
            do_action('watsonconv_message_parsed', $response_body);
            return $response_body;
        }
    }

    /**
     * Routes client request to Assistant API endpoint (v2)
     *
     * @param \WP_REST_Request $request
     * @return mixed|\WP_Error -- reply
     */
    private static function route_request_v2(\WP_REST_Request $request) {
        $body = $request->get_json_params();
        $session_id = array_key_exists('session_id', $body) ? $body['session_id'] : null;

        if (empty($session_id)) {
            # create new session
            $session_id = self::create_session();
            if (!is_string($session_id)) {
                return $session_id;  // reply with response error
            }
        }

        // Array with input and output representation for further
        // database insertion
        $watson_request_array = array();
        // History collection options
        $history_options = array(
            // Enables chat history collection functionality
            "enabled" => get_option("watsonconv_history_enabled") == "yes",
            // Enables getting extended information and debug data
            "debug" => get_option("watsonconv_history_debug_enabled") == "yes"
        );

        $credentials = get_option('watsonconv_credentials');
        $endpoint_url = $credentials['workspace_url'];
        $send_body = apply_filters(
            'watsonconv_user_message',
            array(
                'input' => empty($body['input']) ? new \stdClass() : $body['input'],
                'context' => empty($body['context']) ? new \stdClass() : $body['context']
            )
        );
        // If enabled, request extended data and debut output
        if($history_options["debug"]) {
            $send_body["input"]["options"]["debug"] = true;
            $send_body["input"]["options"]["alternate_intents"] = true;
            $send_body["input"]["options"]["return_context"] = true;
        }
        // Adding request data to array
        $watson_request_array['user_request'] = $send_body;

        do_action('watsonconv_message_pre_send', $send_body);

        $url_tpl = $endpoint_url.'/%s/message?version='.self::API_VERSION_2;
        $post_args = array(
            'timeout' => 20,
            'headers' => array(
                'Authorization' => $credentials['auth_header'],
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($send_body,  JSON_FORCE_OBJECT)
        );

        $response = wp_remote_post(sprintf($url_tpl, $session_id), $post_args);
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code === 404) {
            // session is expired, recreate
            $session_id = self::create_session();
            // try again
            $response = wp_remote_post(sprintf($url_tpl, $session_id), $post_args);
            $response_code = wp_remote_retrieve_response_code($response);
        }

        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        $watson_request_array['watson_response'] = $response_body;
        $watson_request_array['session_id'] = $session_id;
        do_action('watsonconv_message_received', $response);
        $response_body = apply_filters('watsonconv_bot_message', $response_body);

        

        if(isset($response_body['output']["actions"])
            && !empty($response_body['output']["actions"])
            && get_option('watsonconv_mail_vars_enabled')){

            $response_actions  = $response_body['output']["actions"];

            for($i = 0; $i < count($response_actions); $i++){
                if($response_actions[$i]['name'] != self::ACTION_TO_SEND_CONTEXT_VARS){
                    continue;
                }else{
                    self::mail_context_vars($response_actions[$i]);
                    unset($response_body['output']["actions"][$i]);
                }
            }
        }

        // Writing to database
        if($history_options["enabled"]) {
            Storage::insert("requests", $watson_request_array);
        }

        if ($response_code !== 200) {
            return self::reply_with_response_error($response, $session_id);
        } else {
            $response_body['session_id'] = $session_id;  # inject session_id
            do_action('watsonconv_message_parsed', $response_body);
            return $response_body;
        }
    }

    /**
     * Creates Assistant Session
     *
     * @return string|\WP_Error -- session id
     */
    private static function create_session() {
        $credentials = get_option('watsonconv_credentials');
        $endpoint_url = $credentials['workspace_url'];
        $response = wp_remote_post(
            $endpoint_url.'?version='.self::API_VERSION_2,
            array(
                'timeout' => 20,
                'headers' => array(
                    'Authorization' => $credentials['auth_header'],
                    'Content-Type' => 'application/json'
                ),
                'body' => '{}'
            )
        );
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 201) {
            return self::reply_with_response_error($response);
        }
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($response_body) || empty($response_body['session_id'])) {
            return self::reply_with_response_error($response);
        }

        // Writing new session to database if history collection is enabled
        $history_enabled = get_option("watsonconv_history_enabled") == "yes";
        if($history_enabled) {
            // Filling data array for further saving to database
            $data_array = array(
                "id" => $response_body["session_id"]
            );
            // Writing to database
            Storage::insert("sessions", $data_array);
            // Checking database storage limits
            Background_Task_Runner::new_task("session_storage_check");
        }

        return $response_body['session_id'];
    }

    /**
     * Save error message into DB and reply with WP_Error object
     *
     * @param $response
     * @param $session_id
     * @return array
     */
    private static function reply_with_response_error($response, $session_id = NULL) {
        // Logging response to debug log
        Logger::log_message("Watson Assistant response error", self::get_debug_info($response));
        // Retrieving response code
        $response_code = wp_remote_retrieve_response_code($response);
        // Returning WP_Error object
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        // Error object, not sure if we need it
        $error_object = new \WP_Error(
            'watson_error',
            $response_body,
            empty($response_code) ? array() : array('status' => $response_code)
        );
        // Constructing response to deliver to user
        $fallback_response = array(
            "output" => array(
                "generic" => array(
                    array(
                        "response_type" => "text",
                        "text" => "Something went wrong."
                    ),
                    array(
                        "response_type" => "text",
                        "text" => "Cannot process your message. Try again later."
                    )
                )
            ),
            "session_id" => $session_id
        );

        return $fallback_response;

    }

    public static function reset_total_usage() {
        delete_option('watsonconv_total_requests');
    }

    public static function reset_client_usage() {
        if (get_transient('watsonconv_client_list')) {
            foreach (get_transient('watsonconv_client_list') as $client_id => $val) {
                delete_option("watsonconv_requests_$client_id");
            };

            delete_option('watsonconv_client_list');
        }
    }

    public static function record_api_usage() {
        update_option(
            'watsonconv_total_requests',
            get_option('watsonconv_total_requests', 0) +
                get_transient('watsonconv_total_requests') ?: 0
        );

        delete_transient('watsonconv_total_requests');

        if (get_transient('watsonconv_client_list')) {
            foreach (get_transient('watsonconv_client_list') as $client_id => $val) {
                update_option(
                    "watsonconv_requests_$client_id",
                    get_option("watsonconv_requests_$client_id", 0) +
                        get_transient("watsonconv_requests_$client_id") ?: 0
                );

                delete_transient("watsonconv_requests_$client_id");
            };

            update_option(
                'watsonconv_client_list',
                get_option('watsonconv_client_list', array()) +
                    get_transient('watsonconv_client_list')
            );

            delete_transient('watsonconv_client_list');
        }
    }

    public static function init_rate_limit() {
        self::uninit_rate_limit();
        wp_schedule_event(time(), 'minutely', 'watson_save_to_disk');
        wp_schedule_event(time(), get_option('watsonconv_interval', 'monthly'), 'watson_reset_total_usage');
        wp_schedule_event(time(), get_option('watsonconv_client_interval', 'monthly'), 'watson_reset_client_usage');
    }

    public static function uninit_rate_limit() {
        wp_clear_scheduled_hook('watson_save_to_disk');
        wp_clear_scheduled_hook('watson_reset_total_usage');
        wp_clear_scheduled_hook('watson_reset_client_usage');
    }

    public static function add_cron_schedules($schedules) {
        $schedules['monthly'] = array('interval' => MONTH_IN_SECONDS, 'display' => 'Once every month');
        $schedules['weekly'] = array('interval' => WEEK_IN_SECONDS, 'display' => 'Once every week');
        $schedules['minutely'] = array('interval' => MINUTE_IN_SECONDS, 'display' => 'Once every minute');
        
        return $schedules;
    }

    public static function get_client_ip() {
        $ip_addr = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ip_addr = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ip_addr = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ip_addr = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ip_addr = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ip_addr = $_SERVER['REMOTE_ADDR'];

        return $ip_addr;
    }

    // Getting debug info from server response
    public static function get_debug_info($response) {
        $response_body = wp_remote_retrieve_body($response);

        $json_data = @json_decode($response_body);

        if (empty($response_body)) {
            $response = var_export($response, true);
        } else if (!is_null($json_data) && json_last_error() === JSON_ERROR_NONE) {
            $response = $json_data;
        } else if (is_array($response_body) || is_string($response_body)) {
            $response = $response_body;
        } else {
            $response = var_export($response_body, true);
        }

        if (is_string($response)) {
            $response = str_replace('\\/', '/', $response);
        }

        return $response;
    }

    /**
     * Detect provided endpoint URL service version
     * @param string $endpoint_url
     * @return string | false - 'v1' | 'v2' | false
     */
    public static function detect_api_version($endpoint_url) {
        if (preg_match(self::API_V1_URL_RE, $endpoint_url)) {
            return 'v1';
        } else /*if (preg_match(self::API_V2_URL_RE, $endpoint_url))*/ {
            return 'v2';
        } /*else {
            return false;
        } */
    }

    public static function mail_context_vars($request)
    {
        if (isset($request) && !empty($request)){

            $data = array();

            foreach ($request['parameters'] as $key => $val){
                $parameters = $key . ': ' . $val;
                array_push($data, $parameters);
            }
            $data = implode("\n", $data);

            $emailTo = get_option('watsonconv_mail_vars_email_address_to');
            $subject = "Watson Assistant plug-in for WordPress: Mail Action";
            $message = "Following is data collected by Watson Assistant.\n" . $data;

            try{
                wp_mail($emailTo, $subject, $message);
            }catch (\Exception $e){}
        }
    }


    public static function on_before_mail_send( $phpmailer )
    {

        $enabledUserSmtpSettings = get_option('watsonconv_smtp_setting_enabled');
        if($enabledUserSmtpSettings){
            $phpmailer->isSMTP();
            $phpmailer->Host       = get_option('watsonconv_mail_vars_smtp_host');
            $phpmailer->SMTPAuth   = get_option('watsonconv_mail_vars_smtp_authentication');
            $phpmailer->Port       = get_option('watsonconv_mail_vars_smtp_port');
            $phpmailer->SMTPSecure = get_option('watsonconv_mail_vars_smtp_secure');
            $phpmailer->Username   = get_option('watsonconv_mail_vars_smtp_username');
            $phpmailer->Password   = get_option('watsonconv_mail_vars_smtp_password');
            $phpmailer->From       = '';
            /*$phpmailer->FromName   = '';*/
        }
    }

    //works if errors occur while sending messages
    public static function on_mail_error( $wp_error )
    {
        Logger::log_wp_error($wp_error);
    }
}
