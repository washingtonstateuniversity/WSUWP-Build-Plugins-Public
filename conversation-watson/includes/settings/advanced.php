<?php
namespace WatsonConv\Settings;

use WatsonConv\Frontend;

class Advanced {
    const SLUG = 'watson_asst_advanced';

    public static function init_page() {
        add_submenu_page(Main::SLUG, 'Watson Assistant Plugin Customization', 
            'Advanced Features', 'manage_options', self::SLUG, array(__CLASS__, 'render_page'));
    }

    public static function init_settings() {
        self::init_rate_limit_settings();
        self::init_client_rate_limit_settings();
        self::init_voice_call_intro();
        self::init_twilio_cred_settings();
        self::init_call_ui_settings();
        self::init_context_var_settings();
        self::init_history_settings();
        // self::init_mail_settings();
        self::init_smtp_mail_settings();
        self::init_notification_settings();
    }

    public static function render_page() {
    ?>
        <div class="wrap" style="max-width: 95em">
            <h2><?php esc_html_e('Advanced Plugin Features', self::SLUG); ?></h2>
            
            <?php 
                Main::render_isv_banner(); 
                settings_errors(); 
            ?>

            <h2 class="nav-tab-wrapper">
                <a onClick="switch_tab('new_features')" class="nav-tab nav-tab-active new_features_tab">New Features</a>
                <a onClick="switch_tab('usage_management')" class="nav-tab usage_management_tab">Usage Management</a>
                <a onClick="switch_tab('voice_call')" class="nav-tab voice_call_tab">Voice Calling</a>
                <a onClick="switch_tab('context_var')" class="nav-tab context_var_tab">Context Variables</a>
                <a onClick="switch_tab('history')" class="nav-tab history_tab">Chat History</a>
                <a onClick="switch_tab('notification')" class="nav-tab notification_tab">Notification</a>
<!--                <a onClick="switch_tab('mail')" class="nav-tab mail_tab">Mail Action</a>-->
                <a onClick="switch_tab('smtp_mail')" class="nav-tab smtp_mail_tab">Mail Settings</a>
            </h2>

            <form action="options.php" method="POST">
                <?php settings_fields(self::SLUG); ?>
                <div class="tab-page new_features_page">
                    <?php self::render_new_features(); ?>
                </div>
                <div class="tab-page usage_management_page" style="display: none">
                    <?php do_settings_sections(self::SLUG.'_usage_management') ?>
                </div>
                <div class="tab-page voice_call_page" style="display: none">
                    <?php do_settings_sections(self::SLUG.'_voice_call') ?>
                </div>
                <div class="tab-page context_var_page" style="display: none">
                    <?php self::context_var_description() ?>
                    <hr>
                    <table width='100%'>
                        <tr>
                            <td class="responsive">
                                <h2>Enter Context Variable Labels Here</h2>
                                <p>
                                    Enter your desired labels in the text boxes. Next to the 
                                    text boxes, you can see the corresponding values of the 
                                    fields which you have set in your Wordpress profile, as
                                    an example of the information that will be provided to the 
                                    chatbot.
                                </p>
                                <table class='form-table'>
                                    <?php do_settings_fields(self::SLUG.'_context_var', 'watsonconv_context_var') ?>
                                </table>
                            </td>
                            <td id='context-var-image' class="responsive">
                                <img 
                                    class="drop-shadow" 
                                    style="max-width: 40em" 
                                    src="<?php echo WATSON_CONV_URL ?>/img/context_var.jpg"
                                >
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="tab-page history_page" style="display: none">
                    <?php do_settings_sections(self::SLUG.'_history') ?>
                </div>
                <div class="tab-page notification_page" style="display: none">
                    <?php do_settings_sections(self::SLUG.'_notification') ?>
                </div>
                <div class="tab-page mail_page" style="display: none">
                    <?php do_settings_sections(self::SLUG.'_email') ?>
                </div>

                <div class="tab-page smtp_mail_page" style="display: none">
                    <?php do_settings_sections(self::SLUG.'_smtp_mail') ?>
                </div>

                <?php submit_button(); ?>
                <p class="update-message notice inline notice-warning notice-alt"
                style="padding-top: 0.5em; padding-bottom: 0.5em">
                    <b>Note:</b> If you have a server-side caching plugin installed such as
                    WP Super Cache, you may need to clear your cache after changing settings or
                    deactivating the plugin. Otherwise, your action may not take effect.
                <p>
            </form>
        </div>
    <?php
    }

    public static function render_new_features() {
    ?>
        <p>
            The Watson Assistant service has recently been updated to support new types of responses called 
            <a href="https://console.bluemix.net/docs/services/conversation/dialog-overview.html#multimedia">Rich Responses</a>.
            This adds three new types of responses in additional to the default <strong>Text</strong> response:
        </p>
        <table class="rows">
            <tr>
                <td><h3>Image</h3></td>
                <td><p>Embeds an image into the response. The source image file must be 
                hosted somewhere and have a URL that you can use to reference it.</p></td>
            </tr>
            <tr>
                <td><h3>Option</h3></td>
                <td><p>Adds a list of one or more options. When a user clicks one of the 
                options, an associated user input value is sent to the service. How options are rendered can 
                differ depending on where you deploy the dialog. For example, in one integration channel the 
                options might be displayed as clickable buttons, but in another they might be displayed as a 
                dropdown list.</p></td>
            </tr>
            <tr>
                <td><h3>Pause</h3></td>
                <td><p>Forces the application to wait for a specified number of milliseconds 
                before continuing with processing. You can choose to show an indicator that the dialog is 
                working on typing a response. Use this response type if you need to perform an action that 
                might take some time. For example, a parent node makes a Cloud Function call and displays 
                the result in a child node. You could use this response type as the response for the parent 
                node to give the programmatic call time to complete, and then jump to the child node to show 
                the result. This response type does not render in the "Try it out" pane. You must access a 
                node that uses this response type from a test deployment to see how your users will experience 
                it.</p></td>
            </tr>
        </table>
        <br>

        Use Rich Responses to enrich your chatbot dialog and enhance user experience.
        </p>
    <?php
    }

    // ---------------- Rate Limiting -------------------

    public static function init_rate_limit_settings() {
        $settings_page = self::SLUG . '_usage_management';

        add_settings_section('watsonconv_rate_limit', 'Total Usage Management',
            array(__CLASS__, 'rate_limit_description'), $settings_page);

        $overage_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This is the message that will be given to users who are talking with your chatbot
                when the Maximum Number of Total Requests is exceeded. The chat box will disappear
                when the user navigates to a different page.'
                , self::SLUG
            ),
            esc_html__('Overage Message', self::SLUG)
        );

        add_settings_field('watsonconv_use_limit', 'Limit Total API Requests',
            array(__CLASS__, 'render_use_limit'), $settings_page, 'watsonconv_rate_limit');
        add_settings_field('watsonconv_limit', 'Maximum Number of Total Requests',
            array(__CLASS__, 'render_limit'), $settings_page, 'watsonconv_rate_limit');
        add_settings_field('watsonconv_limit_message', $overage_title,
            array(__CLASS__, 'render_limit_message'), $settings_page, 'watsonconv_rate_limit');

        register_setting(self::SLUG, 'watsonconv_use_limit');
        register_setting(self::SLUG, 'watsonconv_interval');
        register_setting(self::SLUG, 'watsonconv_limit');
        register_setting(self::SLUG, 'watsonconv_limit_message');
    }

    public static function rate_limit_description($args) {
    ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>">
            <p>
                <?php esc_html_e('
                    This section allows you to prevent overusage of your credentials by
                    limiting use of the chat bot.
                ', self::SLUG) ?>
            </p>
            <p>
                <?php esc_html_e("
                    If you have a paid plan for Watson
                    Assistant, then the amount you have to pay is directly related to the
                    number of API requests made. The number of API requests is equal to the
                    number of messages sent by users of your chat bot, in addition to the chatbot's initial greeting.
                ", self::SLUG) ?>
            </p>
            <p>
                <?php 
                    esc_html_e("
                        For example, the Standard plan charges $0.0025 per API call (one API call includes
                        one message sent by a user and its response from the chatbot). That means if 
                        visitors to your site send a total of 1000 messages in a month, you will be 
                        charged ($0.0025 per API call) x (1000 calls) = $2.50. If you want to limit the 
                        costs incurred by this chatbot, you can put a limit on the total number of API 
                        requests for a specific period of time here. However, it is recommended to regularly
                        check your API usage for Watson Assistant in your
                    ", self::SLUG);
                    printf(
                        ' <a href="https://console.bluemix.net/dashboard/apps" target="_blank">%s</a> ', 
                        esc_html__('IBM Cloud Console', self::SLUG)
                    );
                    esc_html_e("
                        as that is the most accurate measure.
                    ", self::SLUG);
                ?>
            </p>
        </p>
    <?php
    }

    public static function render_use_limit() {
        Main::render_radio_buttons(
            'watsonconv_use_limit',
            'no',
            array(
                array(
                    'label' => esc_html__('Yes', self::SLUG),
                    'value' => 'yes'
                ), array(
                    'label' => esc_html__('No', self::SLUG),
                    'value' => 'no'
                )
            )
        );
    }

    public static function render_limit() {
        $limit = get_option('watsonconv_limit');
    ?>
        <input name="watsonconv_limit" id="watsonconv_limit" type="number"
            value="<?php echo empty($limit) ? 0 : $limit?>"
            style="width: 8em" />
        <select name="watsonconv_interval" id="watsonconv_interval">
            <option value="monthly" <?php selected(get_option('watsonconv_interval', 'monthly'), 'monthly')?>>
                Per Month
            </option>
            <option value="weekly" <?php selected(get_option('watsonconv_interval', 'monthly'), 'weekly')?>>
                Per Week
            </option>
            <option value="daily" <?php selected(get_option('watsonconv_interval', 'monthly'), 'daily')?>>
                Per Day
            </option>
            <option value="hourly" <?php selected(get_option('watsonconv_interval', 'monthly'), 'hourly')?>>
                Per Hour
            </option>
        </select>
    <?php
    }
    
    public static function render_limit_message() {
    ?>
        <input name="watsonconv_limit_message" id="watsonconv_limit_message" type="text"
            value="<?php echo get_option('watsonconv_limit_message', "Sorry, I can't talk right now. Try again later.") ?>"
            style="width: 40em" />
    <?php
    }

    // ---------- Rate Limiting Per Client --------------

    public static function init_client_rate_limit_settings() {
        $settings_page = self::SLUG . '_usage_management';

        add_settings_section('watsonconv_client_rate_limit', 'Usage Per Client',
            array(__CLASS__, 'client_rate_limit_description'), $settings_page);

        $overage_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This is the message that will be given to users who exceed the Maximum Number of
                Requests Per Client. The chat box will disappear when the user navigates to a 
                different page.'
                , self::SLUG
            ),
            esc_html__('Overage Message', self::SLUG)
        );

        add_settings_field('watsonconv_use_client_limit', 'Limit API Requests Per Client',
            array(__CLASS__, 'render_use_client_limit'), $settings_page, 'watsonconv_client_rate_limit');
        add_settings_field('watsonconv_client_limit', 'Maximum Number of Requests Per Client',
            array(__CLASS__, 'render_client_limit'), $settings_page, 'watsonconv_client_rate_limit');
        add_settings_field('watsonconv_client_limit_message', $overage_title,
            array(__CLASS__, 'render_client_limit_message'), $settings_page, 'watsonconv_client_rate_limit');

        register_setting(self::SLUG, 'watsonconv_use_client_limit');
        register_setting(self::SLUG, 'watsonconv_client_interval');
        register_setting(self::SLUG, 'watsonconv_client_limit');
        register_setting(self::SLUG, 'watsonconv_client_limit_message');
    }

    public static function render_use_client_limit() {
        Main::render_radio_buttons(
            'watsonconv_use_client_limit',
            'no',
            array(
                array(
                    'label' => esc_html__('Yes', self::SLUG),
                    'value' => 'yes'
                ), array(
                    'label' => esc_html__('No', self::SLUG),
                    'value' => 'no'
                )
            )
        );
    }

    public static function client_rate_limit_description($args) {
    ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>">
            <?php esc_html_e('
                These settings allow you to control how many messages can be sent by each
                visitor to your site, rather than in total. This can help protect against
                a few visitors from using up too many messages and, therefore, preventing
                the rest of the visitors from having access to the chatbot.
            ', self::SLUG) ?>
            </a>
        </p>
    <?php
    }

    public static function render_client_limit() {
        $client_limit = get_option('watsonconv_client_limit');
    ?>
        <input name="watsonconv_client_limit" id="watsonconv_client_limit" type="number"
            value="<?php echo empty($client_limit) ? 0 : $client_limit ?>"
            style="width: 8em" />
        <select name="watsonconv_client_interval" id="watsonconv_client_interval">
            <option value="monthly" <?php selected(get_option('watsonconv_client_interval', 'monthly'), 'monthly')?>>
                Per Month
            </option>
            <option value="weekly" <?php selected(get_option('watsonconv_client_interval', 'monthly'), 'weekly')?>>
                Per Week
            </option>
            <option value="daily" <?php selected(get_option('watsonconv_client_interval', 'monthly'), 'daily')?>>
                Per Day
            </option>
            <option value="hourly" <?php selected(get_option('watsonconv_client_interval', 'monthly'), 'hourly')?>>
                Per Hour
            </option>
        </select>
    <?php
    }
    
    public static function render_client_limit_message() {
    ?>
        <input name="watsonconv_client_limit_message" id="watsonconv_client_limit_message" type="text"
            value="<?php echo get_option('watsonconv_client_limit_message', "Sorry, I can't talk right now. Try again later.") ?>"
            style="width: 40em" />
    <?php
    }

    // ------------- Voice Calling -------------------

    public static function init_voice_call_intro() {
        $settings_page = self::SLUG . '_voice_call';

        add_settings_section('watsonconv_voice_call_intro', 'What is Voice Calling?',
            array(__CLASS__, 'voice_call_description'), $settings_page);
        
        add_settings_field('watsonconv_call_recipient', 'Phone Number to Receive Calls from Users',
            array(__CLASS__, 'render_call_recipient'), $settings_page, 'watsonconv_voice_call_intro');
        add_settings_field('watsonconv_use_twilio', 'Use Voice Calling?',
            array(__CLASS__, 'render_use_twilio'), $settings_page, 'watsonconv_voice_call_intro');

        register_setting(self::SLUG, 'watsonconv_call_recipient', array(__CLASS__, 'validate_phone'));
        register_setting(self::SLUG, 'watsonconv_use_twilio');

    }

    public static function voice_call_description($args) {
    ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>">
            <?php esc_html_e('The Voice Calling feature essentially allows users to get in 
                touch with a real person on your team if they get tired of speaking with a chatbot.') ?> <br><br>
            <?php esc_html_e('If you input your phone number below, the user will have the option to call you.
                They can either do this by simply dialing
                your number on their phone, or you can enable the VOIP feature which allows the user to call
                you directly from their browser through their internet connection, with no toll. This is powered
                by a service called ') ?>
            <a href="http://cocl.us/what-is-twilio" target="_blank">Twilio</a>.
        </p>
    <?php
    }
    
    public static function render_call_recipient() {
    ?>
        <input name="watsonconv_call_recipient" id="watsonconv_call_recipient" type="text"
            value="<?php echo get_option('watsonconv_call_recipient') ?>"
            placeholder="+15555555555"
            style="width: 24em" />
    <?php
    }

    public static function render_use_twilio() {
        Main::render_radio_buttons(
            'watsonconv_use_twilio',
            'no',
            array(
                array(
                    'label' => esc_html__('Yes', self::SLUG),
                    'value' => 'yes'
                ), array(
                    'label' => esc_html__('No', self::SLUG),
                    'value' => 'no'
                )
            )
        );
    }
    
    // ------------ Twilio Credentials ---------------

    public static function init_twilio_cred_settings() {
        $settings_page = self::SLUG . '_voice_call';

        add_settings_section('watsonconv_twilio_cred', '<span class="twilio_settings">Twilio Credentials</span>',
            array(__CLASS__, 'twilio_cred_description'), $settings_page);

        add_settings_field('watsonconv_twilo_sid', 'Account SID', array(__CLASS__, 'render_twilio_sid'),
            $settings_page, 'watsonconv_twilio_cred');
        add_settings_field('watsonconv_twilio_auth', 'Auth Token', array(__CLASS__, 'render_twilio_auth'),
            $settings_page, 'watsonconv_twilio_cred');
        add_settings_field('watsonconv_call_id', 'Caller ID (Verified Number with Twilio)',
            array(__CLASS__, 'render_call_id'), $settings_page, 'watsonconv_twilio_cred');
        add_settings_field('watsonconv_twilio_domain', 'Domain Name of this Website (Probably doesn\'t need changing)',
            array(__CLASS__, 'render_domain_name'), $settings_page, 'watsonconv_twilio_cred');

        register_setting(self::SLUG, 'watsonconv_twilio', array(__CLASS__, 'validate_twilio'));
        register_setting(self::SLUG, 'watsonconv_call_id', array(__CLASS__, 'validate_phone'));
    }

    public static function validate_twilio($new_config) {
        if (!empty($new_config['sid']) || !empty($new_config['auth_token'])) {
            $old_config = get_option('watsonconv_twilio');

            try {
                $client = new \Twilio\Rest\Client($new_config['sid'], $new_config['auth_token']);
                
                try {
                    $app = $client
                        ->applications(get_option('watsonconv_twiml_sid'))
                        ->fetch();
                } catch (\Twilio\Exceptions\RestException $e) {
                    $app = false;
                    $params = array('FriendlyName' => 'Chatbot for ' . $new_config['domain_name']);

                    foreach($client->account->applications->read($params) as $_app) {
                        $app = $_app;
                    }

                    if (!$app) {
                        $params = array('FriendlyName' => 'Chatbot for ' . $old_config['domain_name']);
        
                        foreach($client->account->applications->read($params) as $_app) {
                            $app = $_app;
                        }

                        if (!$app) {
                            $app = $client->applications->create('Chatbot for ' . $new_config['domain_name']);
                        }
                    }
                }

                $app->update(
                    array(
                        'voiceUrl' => $new_config['domain_name'] . '?rest_route=/watsonconv/v1/twilio-call',
                        'FriendlyName' => 'Chatbot for ' . $new_config['domain_name']
                    )
                );

                update_option('watsonconv_twiml_sid', $app->sid);
            } catch (\Exception $e) {
                add_settings_error(
                    'watsonconv_twilio', 
                    'twilio-invalid', 
                    $e->getMessage() . ' (' . $e->getCode() . ')'
                );
                
                return array(
                    'sid' => '',
                    'auth_token' => '',
                    'domain_name' => $old_config['domain_name']
                );
            }
        }

        return $new_config;
    }

    public static function validate_phone($number) {
        if (!empty($number) && !preg_match('/^\+?[1-9]\d{1,14}$/', $number)) {
            add_settings_error(
                'watsonconv_twilio', 
                'invalid-phone-number', 
                'Please use valid E.164 format for phone numbers (e.g. +15555555555).'
            );

            return '';
        }

        return $number;
    }

    public static function twilio_cred_description($args) {
    ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>" class="twilio_settings">
            <a href="http://cocl.us/try-twilio" target="_blank">
                <?php esc_html_e('Start by creating your free trial Twilio account here.')?>
            </a><br>
            <?php esc_html_e(' You can get your Account SID and Auth Token from your Twilio Dashboard.') ?> <br>
            <?php esc_html_e('For the caller ID, you can use a number that you\'ve either obtained from or') ?>
            <a href="https://www.twilio.com/console/phone-numbers/verified" target="_blank">
                <?php esc_html_e('verified with') ?>
            </a>
            <?php esc_html_e('Twilio.') ?> <br>
            <?php esc_html_e('Then just specify the phone number you want to answer the user\'s calls on 
                and you\'re good to go.') ?> <br>
            <?php esc_html_e('The Domain Name below is simply the domain name that Twilio will use 
                to reach your website. For most websites the default will work fine.', self::SLUG) ?> <br><br>
            <?php esc_html_e('Note: Phone numbers must be entered in E.164 format (e.g. +15555555555).') ?>
        </p>
    <?php
    }

    public static function render_twilio_sid() {
        $config = get_option('watsonconv_twilio');
        $sid = (empty($config) || empty($config['sid'])) ? '' : $config['sid'];
    ?>
        <input name="watsonconv_twilio[sid]" id="watsonconv_twilio_sid" type="text"
            value="<?php echo $sid ?>"
            placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
            style="width: 24em" />
    <?php
    }

    public static function render_twilio_auth() {
        $config = get_option('watsonconv_twilio');
        $token = (empty($config) || empty($config['auth_token'])) ? '' : $config['auth_token'];
    ?>
        <input name="watsonconv_twilio[auth_token]" id="watsonconv_twilio_auth" type="password"
            value="<?php echo $token ?>"
            style="width: 24em"/>
    <?php
    }
    
    public static function render_call_id() {
    ?>
        <input name="watsonconv_call_id" id="watsonconv_call_id" type="text"
            value="<?php echo get_option('watsonconv_call_id') ?>"
            placeholder="+15555555555"
            style="width: 24em" />
    <?php
    }
    
    public static function render_domain_name() {
        $config = get_option('watsonconv_twilio');
        $domain_name = (empty($config) || empty($config['domain_name']))
            ? get_site_url() : $config['domain_name'];
    ?>
        <input name="watsonconv_twilio[domain_name]" id="watsonconv_twilio_domain" type="text"
            value="<?php echo $domain_name ?>"
            placeholder="<?php echo get_site_url() ?>"
            style="width: 24em" />
    <?php
    }
    
    // ------------ Voice Call UI Text ---------------

    public static function init_call_ui_settings() {
        $settings_page = self::SLUG . '_voice_call';

        add_settings_section('watsonconv_call_ui', '<span class="twilio_settings">Voice Call UI Text</span>',
            array(__CLASS__, 'twilio_call_ui_description'), $settings_page);

        add_settings_field('watsonconv_call_tooltip', 'This message will display when the user hovers over the phone button.', 
            array(__CLASS__, 'render_call_tooltip'), $settings_page, 'watsonconv_call_ui');
        add_settings_field('watsonconv_call_button', 'This is the text for the button to call using Twilio.',
            array(__CLASS__, 'render_call_button'), $settings_page, 'watsonconv_call_ui');
        add_settings_field('watsonconv_calling_text', 'This text is displayed when calling.',
            array(__CLASS__, 'render_calling_text'), $settings_page, 'watsonconv_call_ui');

        register_setting(self::SLUG, 'watsonconv_call_tooltip');
        register_setting(self::SLUG, 'watsonconv_call_button');
        register_setting(self::SLUG, 'watsonconv_calling_text');
    }

    public static function twilio_call_ui_description($args) {
    ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>" class="twilio_settings">
            <?php esc_html_e('Here, you can customize the text to be used in the voice calling 
                user interface.', self::SLUG) ?>
        </p>
    <?php
    }

    public static function render_call_tooltip() {
    ?>
        <input name="watsonconv_call_tooltip" id="watsonconv_call_tooltip" type="text"
            value="<?php echo get_option('watsonconv_call_tooltip') ?: 'Talk to a Live Agent' ?>"
            style="width: 24em" />
    <?php
    }

    public static function render_call_button() {
    ?>
        <input name="watsonconv_call_button" id="watsonconv_call_button" type="text"
            value="<?php echo get_option('watsonconv_call_button') ?: 'Start Toll-Free Call Here' ?>"
            style="width: 24em"/>
    <?php
    }
    
    public static function render_calling_text() {
    ?>
        <input name="watsonconv_calling_text" id="watsonconv_calling_text" type="text"
            value="<?php echo get_option('watsonconv_calling_text') ?: 'Calling Agent...' ?>"
            style="width: 24em"/>
    <?php
    }
    
    // ---------- Context Variable Settings -------------
    
    private static function init_context_var_settings() {
        $settings_page = self::SLUG . '_context_var';

        add_settings_section('watsonconv_context_var', '',
            array(__CLASS__, 'context_var_description'), $settings_page);

        $first_name_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'The first name of the user.'
                , self::SLUG
            ),
            esc_html__('First Name', self::SLUG)
        );

        $last_name_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'The last name of the user.'
                , self::SLUG
            ),
            esc_html__('Last Name', self::SLUG)
        );

        $nickname_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                "The user's nickname."
                , self::SLUG
            ),
            esc_html__('Nickname', self::SLUG)
        );

        $email_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                "The user's email address."
                , self::SLUG
            ),
            esc_html__('Email Address', self::SLUG)
        );

        $login_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                "The user's login username."
                , self::SLUG
            ),
            esc_html__('Username', self::SLUG)
        );

        $plugin_version_title = sprintf(
            '<span href="#" title="%s">%s</span>',
            esc_html__(
                "The plugin version."
                , self::SLUG
            ),
            esc_html__('Plug-in Version', self::SLUG)
        );
        
        add_settings_field('watsonconv_fname_var', $first_name_title,
            array(__CLASS__, 'render_fname_var'), $settings_page, 'watsonconv_context_var');
        add_settings_field('watsonconv_lname_var', $last_name_title,
            array(__CLASS__, 'render_lname_var'), $settings_page, 'watsonconv_context_var');
        add_settings_field('watsonconv_nname_var', $nickname_title,
            array(__CLASS__, 'render_nname_var'), $settings_page, 'watsonconv_context_var');
        add_settings_field('watsonconv_email_var', $email_title,
            array(__CLASS__, 'render_email_var'), $settings_page, 'watsonconv_context_var');
        add_settings_field('watsonconv_login_var', $login_title,
            array(__CLASS__, 'render_login_var'), $settings_page, 'watsonconv_context_var');
        add_settings_field('watsonconv_plugin_version_var', $plugin_version_title,
            array(__CLASS__, 'render_plugin_version_var'), $settings_page, 'watsonconv_context_var');

        register_setting(self::SLUG, 'watsonconv_fname_var', array(__CLASS__, 'validate_context_var'));
        register_setting(self::SLUG, 'watsonconv_lname_var', array(__CLASS__, 'validate_context_var'));
        register_setting(self::SLUG, 'watsonconv_nname_var', array(__CLASS__, 'validate_context_var'));
        register_setting(self::SLUG, 'watsonconv_email_var', array(__CLASS__, 'validate_context_var'));
        register_setting(self::SLUG, 'watsonconv_login_var', array(__CLASS__, 'validate_context_var'));
        register_setting(self::SLUG, 'watsonconv_plugin_version_var', array(__CLASS__, 'validate_context_var'));
    }

    public static function context_var_description() {
    ?>
        <p>
            Would you like to use a user's name or email in your chatbot's dialog? 
            This page allows you to send user account information (such as first name, last name) to your
            Watson Assistant chatbot as a "context variable". You can use this to customize
            your dialog to say different things depending on the value of the context variable. 
            To do this, follow these instructions:
        </p>
        <ol>
            <li>Give labels to the values you want to use by filling out the fields below 
                (e.g. 'fname' for First Name).</li>
            <li>Navigate to you Watson Assistant workspace (the place where you create your chatbot's dialog).</li>
            <li>Now you can type <strong>$fname</strong> in your chatbot dialog and this 
                will be replaced with the user's first name.</li> 
            <li>Sometimes a user may not specify their first name and so this context 
                variable won't be sent. Because of this, you should check if the
                chatbot recognizes the context variable first like in the example below.</li>
        </ol>
    <?php
    } 

    public static function render_fname_var() {
    ?>
        <input name="watsonconv_fname_var" id="watsonconv_fname_var"
            type="text" style="width: 16em"
            placeholder="e.g. fname"
            value="<?php echo get_option('watsonconv_fname_var', '') ?>" 
        />
        <span class='dashicons dashicons-arrow-right-alt'></span>
        "<?php echo get_user_meta(get_current_user_id(), 'first_name', true); ?>"
    <?php
    }

    public static function render_lname_var() {
        ?>
            <input name="watsonconv_lname_var" id="watsonconv_lname_var"
                type="text" style="width: 16em"
                placeholder="e.g. lname"
                value="<?php echo get_option('watsonconv_lname_var', '') ?>" 
            />
            <span class='dashicons dashicons-arrow-right-alt'></span>
            "<?php echo get_user_meta(get_current_user_id(), 'last_name', true); ?>"
        <?php
    }

    public static function render_nname_var() {
        ?>
            <input name="watsonconv_nname_var" id="watsonconv_nname_var"
                type="text" style="width: 16em"
                placeholder="e.g. nickname"
                value="<?php echo get_option('watsonconv_nname_var', '') ?>" 
            />
            <span class='dashicons dashicons-arrow-right-alt'></span>
            "<?php echo get_user_meta(get_current_user_id(), 'nickname', true); ?>"
        <?php
    }

    public static function render_email_var() {
        ?>
            <input name="watsonconv_email_var" id="watsonconv_email_var"
                type="text" style="width: 16em"
                placeholder="e.g. email"
                value="<?php echo get_option('watsonconv_email_var', '') ?>" 
            />
            <span class='dashicons dashicons-arrow-right-alt'></span>
            "<?php echo wp_get_current_user()->get('user_email'); ?>"
        <?php
    }

    public static function render_login_var() {
        ?>
            <input name="watsonconv_login_var" id="watsonconv_login_var"
                type="text" style="width: 16em"
                placeholder="e.g. username"
                value="<?php echo get_option('watsonconv_login_var', '') ?>" 
            />
            <span class='dashicons dashicons-arrow-right-alt'></span>
            "<?php echo wp_get_current_user()->get('user_login'); ?>"
        <?php
    }

    public static function render_plugin_version_var() {
        ?>
        <input name="watsonconv_plugin_version_var" id="watsonconv_plugin_version_var"
               type="text" style="width: 16em"
               placeholder="e.g. plug-in version"
               value="<?php echo get_option('watsonconv_plugin_version_var', '') ?>"
        />
        <span class='dashicons dashicons-arrow-right-alt'></span>
        "<?php echo Frontend::get_version(); ?>"
        <?php
    }

    public static function validate_context_var($str) 
    {
        if (preg_match('/^[a-zA-Z0-9_]*$/',$str)) {
            return $str;
        } else {
            add_settings_error('watsonconv', 'invalid-var-name', 
                'A context variable name can only contain upper and lowercase alphabetic characters,
                numeric characters (0-9), and underscores.');
            return '';
        }
    }

    // ---------- Mail Settings -------------

    public static function init_mail_settings(){
        $settings_page = self::SLUG . '_email';


        add_settings_section('watsonconv_email', '<span class="email_settings">About Mail Action</span>',
            array(__CLASS__, 'email_description'), $settings_page);
        add_settings_field('watsonconv_mail_vars_enabled', 'Enable Mail Action',
            array(__CLASS__, 'render_mail_vars_enabled'), $settings_page, 'watsonconv_email');

        register_setting(self::SLUG, 'watsonconv_mail_vars_enabled');
    }

    public static function email_description(){
        ?>
        <p>
            This section allows you to configure Mail Action feature.
        </p>
        <p>
            This feature allows you to send data collected with Watson Assistant to the predefined e-mail box.
        </p>
        <p>
            To trigger Mail Action you need to issue Client request from the desired Dialog Node. Below is an example request:
        <pre>
{
  "output": {
    < skipped >
  },
  "actions": [
    {
      "name": "<?php echo \WatsonConv\Api::ACTION_TO_SEND_CONTEXT_VARS;?>",
      "type": "client",
      "parameters": {
        "var1": "$variable1",
        "var2": "$variable2",
        ...
      },
      "result_variable": "result"
    }
  ]
}            </pre>
        Please refer Watson Assistant documentation for details on:
        <a href="https://console.bluemix.net/docs/services/assistant/dialog-actions.html" target="_blank">
            making programmatic calls from a dialog node
        </a>.<br>
        Create an action with the following parameters:
        </pre>
        <p>
            <strong>name</strong>: <?php echo \WatsonConv\Api::ACTION_TO_SEND_CONTEXT_VARS;?>
            <br>
            <strong>type</strong>: client
            <br>
            <strong>parameters</strong>: {your context variables}
            <br>
            <strong>result_variable</strong>: result
        </p>
        <h2>Settings</h2>
        <?php
    }

    public static function render_mail_vars_enabled() {
        Main::render_radio_buttons(
            'watsonconv_mail_vars_enabled',
            0,
            array(
                array(
                    'label' => esc_html__('Yes', self::SLUG),
                    'value' => 1
                ), array(
                'label' => esc_html__('No', self::SLUG),
                'value' => 0
            )
            )
        );
    }

    // ---------- SMTP Mail Settings -------------

    public static function init_smtp_mail_settings(){
        $settings_page = self::SLUG . '_smtp_mail';

        add_settings_section('watsonconv_mail_settings', '<span class="email_settings">Mail Settings</span>',
            array(__CLASS__, 'smtp_mail_description'), $settings_page);
        add_settings_field('watsonconv_mail_vars_email_address_to', 'Recipient email address',
            array(__CLASS__, 'render_mail_vars_email_address_to'), $settings_page, 'watsonconv_mail_settings');
        add_settings_field('watsonconv_button_check_email_sending', '',
            array(__CLASS__, 'render_button_check_email_sending'), $settings_page, 'watsonconv_mail_settings');

        add_settings_field('watsonconv_smtp_setting_enabled', 'Configure Advanced Email Settings',
            array(__CLASS__, 'render_smtp_setting_enabled'), $settings_page, 'watsonconv_mail_settings');


        add_settings_section('watsonconv_smtp_settings', '',
            '', $settings_page);
        add_settings_field('watsonconv_mail_vars_smtp_host', 'Outbound SMTP host',
            array(__CLASS__, 'render_mail_vars_smtp_host'), $settings_page, 'watsonconv_smtp_settings');
        add_settings_field('watsonconv_mail_vars_smtp_authentication', 'Authentication',
            array(__CLASS__, 'render_mail_vars_smtp_authentication'), $settings_page, 'watsonconv_smtp_settings');
        add_settings_field('watsonconv_mail_vars_smtp_username', 'SMTP Username',
            array(__CLASS__, 'render_mail_vars_smtp_username'), $settings_page, 'watsonconv_smtp_settings');
        add_settings_field('watsonconv_mail_vars_smtp_password', 'SMTP Password',
            array(__CLASS__, 'render_mail_vars_smtp_password'), $settings_page, 'watsonconv_smtp_settings');
        add_settings_field('watsonconv_mail_vars_smtp_port', 'SMTP Port',
            array(__CLASS__, 'render_mail_vars_smtp_port'), $settings_page, 'watsonconv_smtp_settings');
        add_settings_field('watsonconv_mail_vars_smtp_secure', 'SMTP Encryption',
            array(__CLASS__, 'render_mail_vars_smtp_secure'), $settings_page, 'watsonconv_smtp_settings');


        register_setting(self::SLUG, 'watsonconv_smtp_setting_enabled');
        register_setting(self::SLUG, 'watsonconv_mail_vars_smtp_host');
        register_setting(self::SLUG, 'watsonconv_mail_vars_smtp_authentication');
        register_setting(self::SLUG, 'watsonconv_mail_vars_smtp_port');
        register_setting(self::SLUG, 'watsonconv_mail_vars_smtp_secure');
        register_setting(self::SLUG, 'watsonconv_mail_vars_smtp_username');
        register_setting(self::SLUG, 'watsonconv_mail_vars_smtp_password');
        register_setting(self::SLUG, 'watsonconv_mail_vars_email_address_to', array(__CLASS__, 'validate_email'));
    }

    public static function smtp_mail_description(){
        ?>
        <p>
            This section allows you to configure E-mail Settings.
        </p>
        <p>
            Please use "Send a Test Email" button to make sure plug-in is able to deliver messages to your mailbox.
        </p>
        <?php
    }

    public static function validate_email($email) {
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            add_settings_error(
                'watsonconv_email',
                'invalid-email',
                'Please use valid format for email (e.g. email@example.com).'
            );

            return get_option('watsonconv_mail_vars_email_address_to');
        }

        return $email;
    }

    public static function render_mail_vars_email_address_to(){
        ?>
        <input name="watsonconv_mail_vars_email_address_to" id="watsonconv_mail_vars_email_address_to"
               type="text" style="width: 16em"
               placeholder="e.g. email@example.com"
               autocomplete="off"
               value="<?php echo get_option('watsonconv_mail_vars_email_address_to', '') ?>"
        />
        <?php
    }

    public static function render_mail_vars_smtp_host(){
        ?>
        <input name="watsonconv_mail_vars_smtp_host" id="watsonconv_mail_vars_smtp_host"
               type="text" style="width: 16em"
               placeholder="e.g. smtp.gmail.com"
               value="<?php echo get_option('watsonconv_mail_vars_smtp_host', '') ?>"
        />
        <?php
    }

    public static function render_mail_vars_smtp_authentication(){
        Main::render_radio_buttons(
            'watsonconv_mail_vars_smtp_authentication',
            '1',
            array(
                array(
                    'label' => esc_html__('On', self::SLUG),
                    'value' => 1
                ), array(
                'label' => esc_html__('Off', self::SLUG),
                'value' => 0
            )
            )
        );
    }

    public static function render_mail_vars_smtp_port(){
        ?>
        <input name="watsonconv_mail_vars_smtp_port" id="watsonconv_mail_vars_smtp_port"
               type="text" style="width: 16em"
               placeholder="e.g. 465"
               value="<?php echo get_option('watsonconv_mail_vars_smtp_port', '') ?>"
        />
        <?php
    }

    public static function render_mail_vars_smtp_secure(){
        Main::render_radio_buttons(
            'watsonconv_mail_vars_smtp_secure',
            'none',
            array(
                array(
                    'label' => esc_html__('None', self::SLUG),
                    'value' => 'none'
                ), array(
                'label' => esc_html__('SSL', self::SLUG),
                'value' => 'ssl'
            ),array(
                'label' => esc_html__('TLS', self::SLUG),
                'value' => 'tls'
            )
            )
        );
    }

    public static function render_mail_vars_smtp_username(){
        ?>
        <input name="watsonconv_mail_vars_smtp_username" id="watsonconv_mail_vars_smtp_username"
               type="text" style="width: 16em"
               placeholder="e.g. email@example.com"
               autocomplete="off"
               value="<?php echo get_option('watsonconv_mail_vars_smtp_username', '') ?>"
        />
        <?php
    }

    public static function render_mail_vars_smtp_password(){
        ?>
        <input name="watsonconv_mail_vars_smtp_password" id="watsonconv_mail_vars_smtp_password"
               type="password" style="width: 16em"
               placeholder="e.g. secret"
               autocomplete="new-password"
               value="<?php echo get_option('watsonconv_mail_vars_smtp_password', '') ?>"
        />
        <?php
    }

    public static function render_button_check_email_sending(){
        ?>
        <button type="button" id="watsonconv_button_check_email_sending"
                class="button-primary">
            Send a Test Email
        </button>
        <?php
    }

    public static function render_smtp_setting_enabled() {
        Main::render_radio_buttons(
            'watsonconv_smtp_setting_enabled',
            0,
            array(
                array(
                    'label' => esc_html__('Yes', self::SLUG),
                    'value' => 1
                ), array(
                'label' => esc_html__('No', self::SLUG),
                'value' => 0
            )
            )
        );
    }

    public static function render_test_email_success_message(){
        return  '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">'
                    .'<p>'
                        .'<strong>Test Email has been sent. Please check your mailbox</strong>'
                    .'</p>'
                    .'<button type="button" class="notice-dismiss">'
                        .'<span class="screen-reader-text">Hide this notice.</span>'
                    .'</button>'
                .'</div>';
    }

    public static function render_test_email_error_message(){
        return  '<div id="setting-error-settings_error" class="error settings-error notice is-dismissible">'
            .'<p>'
            .'<strong>Unable to send a Test Email: ' . $GLOBALS['phpmailer']->ErrorInfo .'</strong>'
            .'</p>'
            . '<p>Please consider enabling Advanced Mail Settings</p>'
            .'<button type="button" class="notice-dismiss">'
            .'<span class="screen-reader-text">Hide this notice.</span>'
            .'</button>'
            .'</div>';
    }

    public static function set_recipient_email_address($request)
    {
        if(get_option('watsonconv_mail_vars_email_address_to') == $request){
            return true;
        }elseif(get_option('watsonconv_mail_vars_email_address_to') || get_option('watsonconv_mail_vars_email_address_to') != $request){
            update_option('watsonconv_mail_vars_email_address_to', $request);
        }else{
            add_option('watsonconv_mail_vars_email_address_to', $request);
        }
    }

    public static function send_test_email()
    {
        // Check if current user is permitted to control plugins
        if(!current_user_can('administrator')) {
            Logger::log_message("Unauthorized REST API access", "Unauthorized access while sending test email");
            return new \WP_REST_Response('Not Authorized', 403);
        }

        self::set_recipient_email_address($_POST['email']);
        $user = wp_get_current_user()->get('user_login');
        $emailTo = get_option('watsonconv_mail_vars_email_address_to');
        $subject = "Watson Assistant plug-in for WordPress: test e-mail";
        $message = "Hello " . $user . ", this is a test email!";

        try{
            if (wp_mail($emailTo, $subject, $message)){
                return self::render_test_email_success_message();
            }else{
                return self::render_test_email_error_message();
            }
        }catch (\Exception $e){}
    }

    // ---------- Chat History Settings ----------

    // Initializing chat history collection settings
    public static function init_history_settings(){
        // Wordpress settings page name
        $settings_page = self::SLUG . '_history';

        // GENERAL DESCRIPTION
        // Section id
        $general_id = 'watsonconv_history_description';
        // Section title
        $general_title = '<span class="history_settings">Chat History</span>';
        // Callback for description rendering
        $general_callback = array(__CLASS__, 'render_history_description');
        // Adding section to page
        add_settings_section($general_id, $general_title, $general_callback, $settings_page);

        // ENABLING/DISABLING CHAT HISTORY COLLECTION
        // Setting id
        $history_id = 'watsonconv_history_enabled';
        // Setting title
        $history_title = 'Enable chat history collection';
        // Setting rendering callback
        $history_callback = array(__CLASS__, 'render_history_enabled');
        // Adding setting to page
        add_settings_field($history_id, $history_title, $history_callback, $settings_page, $general_id);
        // Registering setting in Wordpress
        register_setting(self::SLUG, $history_id);

        // ENABLING/DISABLING ADDITIONAL (DEBUG) INFORMATION COLLECTION
        // Setting id
        $debug_id = 'watsonconv_history_debug_enabled';
        // Setting title
        $debug_title = 'Enable extended information collection';
        // Setting rendering callback
        $debug_callback = array(__CLASS__, 'render_history_debug_enabled');
        // Adding setting to page
        add_settings_field($debug_id, $debug_title, $debug_callback, $settings_page, $general_id);
        // Registering setting in Wordpress
        register_setting(self::SLUG, $debug_id);

        // ENABLING/DISABLING MAXIMUM AMOUNT OF SAVED SESSIONS
        // Setting id
        $limit_id = 'watsonconv_history_limit_enabled';
        // Setting title
        $limit_title = 'Set maximum amount of stored chat sessions';
        // Setting rendering callback
        $limit_callback = array(__CLASS__, 'render_history_limit_enabled');
        // Adding setting to page
        add_settings_field($limit_id, $limit_title, $limit_callback, $settings_page, $general_id);
        // Registering setting in Wordpress
        register_setting(self::SLUG, $limit_id);

        // SETTING A MAXIMUM AMOUNT OF SAVED SESSIONS
        $amount_id = 'watsonconv_history_limit';
        // Setting title
        $amount_title = 'Maximum number of saved sessions';
        // Setting rendering callback
        $amount_callback = array(__CLASS__, 'render_history_limit');
        // Adding setting to page
        add_settings_field($amount_id, $amount_title, $amount_callback, $settings_page, $general_id);
        // Registering setting in Wordpress
        register_setting(self::SLUG, $amount_id);
    }

    // Description of chat history features
    public static function render_history_description() {
        ?>
        <p>This section allows you to configure chat history collection feature.</p>
        <p>You can store chat messages from both Watson Assistant and your site's users for further review.<br>
        Also this feature provides data for e-mail notificator (please refer to Notification tab)</p>

        <?php
    }

    // Radiobuttons for enabling/disabling chat history collection
    public static function render_history_enabled() {
        Main::render_radio_buttons(
            'watsonconv_history_enabled',
            'no',
            array(
                array(
                    'label' => esc_html__('Yes', self::SLUG),
                    'value' => 'yes'
                ), array(
                'label' => esc_html__('No', self::SLUG),
                'value' => 'no'
                )
            )
        );
    }

    // Radiobuttons for enabling/disabling debug information collection
    public static function render_history_debug_enabled() {
        Main::render_radio_buttons(
            'watsonconv_history_debug_enabled',
            'no',
            array(
                array(
                    'label' => esc_html__('Yes', self::SLUG),
                    'value' => 'yes'
                ), array(
                'label' => esc_html__('No', self::SLUG),
                'value' => 'no'
                )
            )
        );
        ?>
        <p>Extended information includes additional diagnostic information for fine-tuning your bot such as additional recognized intents, Watson Assistant log messages and visited nodes.</p>
        <?php
    }

    // Radiobuttons for enabling/disabling session storage limit
    public static function render_history_limit_enabled() {
        Main::render_radio_buttons(
            'watsonconv_history_limit_enabled',
            'no',
            array(
                array(
                    'label' => esc_html__('Yes', self::SLUG),
                    'value' => 'yes'
                ), array(
                    'label' => esc_html__('No', self::SLUG),
                    'value' => 'no'
                )
            )
        );
    }

    // History storage limit
    public static function render_history_limit() {
        $history_limit = get_option('watsonconv_history_limit');
        
        ?>
        <input name="watsonconv_history_limit" id="watsonconv_history_limit" type="number"
            value="<?php echo empty($history_limit) ? 100 : $history_limit ?>"
            style="width: 8em" />
        <?php


    }

    // ---------- Notification Settings ----------

    // Initializing notification settings
    public static function init_notification_settings(){
        // Wordpress settings page name
        $settings_page = self::SLUG . '_notification';

        // GENERAL DESCRIPTION
        // Section id
        $section_id = 'watsonconv_notification_settings';
        // Section title
        $section_title = '<span class="notification_settings">Notification</span>';
        // Callback for description rendering
        $section_callback = array(__CLASS__, 'render_notification_description');
        // Adding section to page
        add_settings_section($section_id, $section_title, $section_callback, $settings_page);

        // ENABLING/DISABLING NOTIFICATION
        // Setting id
        $notification_id = 'watsonconv_notification_enabled';
        // Setting title
        $notification_title = 'Enable notification';
        // Setting rendering callback
        $notification_callback = array(__CLASS__, 'render_notification_enabled');
        // Adding setting to page
        add_settings_field($notification_id, $notification_title, $notification_callback, $settings_page, $section_id);
        // Registering setting in Wordpress
        register_setting(self::SLUG, $notification_id);

        // Recipient email address
        // ENABLING/DISABLING NOTIFICATION
        // Setting id
        $notification_email_to_id = 'watsonconv_notification_email_to';
        // Setting title
        $notification_email_to_title = 'E-mail addresses to be notified';
        // Setting rendering callback
        $notification_email_to_callback = array(__CLASS__, 'render_notification_email_to');
        // Adding setting to page
        add_settings_field($notification_email_to_id, $notification_email_to_title, $notification_email_to_callback, $settings_page, $section_id);
        // Registering setting in Wordpress
        register_setting(self::SLUG, $notification_email_to_id, array(__CLASS__, 'validate_notification_email_to'));

        // ENABLING/DISABLING USAGE SUMMARY NOTIFICATION INTERVAL
        // Setting id
        $notification_summary_interval_id = 'watsonconv_notification_summary_interval';
        // Setting title
        $notification_summary_interval_title = 'How often do you want to be notified';
        // Setting rendering callback
        $notification_summary_interval_callback = array(__CLASS__, 'render_notification_summary_interval');
        // Adding setting to page
        add_settings_field($notification_summary_interval_id, $notification_summary_interval_title, $notification_summary_interval_callback, $settings_page, $section_id);
        // Registering setting in Wordpress
        register_setting(self::SLUG, $notification_summary_interval_id);

        // Send test notification
        $notification_send_test_id = 'watsonconv_notification_send_test';
        add_settings_field($notification_send_test_id, '', array(__CLASS__, 'render_send_test_notification_email'),
            $settings_page, $section_id);
        register_setting(self::SLUG, $notification_send_test_id);


        register_setting(self::SLUG, $section_id, array(__CLASS__, 'validate_notification_settings'));
    }

    // Description of chat history features
    public static function render_notification_description() {
        ?>
        <p>Would you like to be notified when customers have conversations with your chatbot? You can configure this plugin to send you and your colleagues an email when these conversations take place. You can choose to be notofied every hour, every day or week.</p>
        <? if (get_option('watsonconv_history_enabled', '') !== 'yes') { ?>
            <p class="update-message notice inline notice-warning notice-alt" style="padding-top: 0.5em; padding-bottom: 0.5em">
                <b>Note:</b> In order to get correct notifications please <strong>Enable</strong> Chat History collection.<br>
                Chat history collection settings are available at <strong>Chat History</strong> tab.
            </p>
        <? } ?>
        <?php
    }

    // Radio buttons for enabling/disabling notification
    public static function render_notification_enabled() {
        Main::render_radio_buttons(
            'watsonconv_notification_enabled',
            'no',
            array(
                array(
                    'label' => esc_html__('Yes', self::SLUG),
                    'value' => 'yes'
                ), array(
                'label' => esc_html__('No', self::SLUG),
                'value' => 'no'
            )
            )
        );
    }

    public static function render_notification_email_to(){
        ?>
        <input name="watsonconv_notification_email_to" id="watsonconv_notification_email_to"
               type="text" style="width: 32em"
               placeholder="e.g. alice@example.com, bob@example.com, charlie@example.com"
               autocomplete="off"
               value="<?php echo get_option('watsonconv_notification_email_to', '') ?>"
        />
        <p><i>You can specify multiple e-mails separated by commas.</i></p>
<!--        <p><strong>Example:</strong> <tt style="border: 1px solid darkgrey; padding: 3px">alice@example.com, bob@example.com, charlie@example.com</tt></p>-->
        <?php
    }


    // Radio buttons for enabling/disabling notification
    public static function render_notification_summary_interval() {
        Main::render_radio_buttons(
            'watsonconv_notification_summary_interval',
            'no',
            array(
                /*
                array(
                    'label' => esc_html__('Never', self::SLUG),
                    'value' => '0'
                ), array(
                    'label' => esc_html__('Minutely', self::SLUG),
                    'value' => '60'
                ),*/ array(
                    'label' => esc_html__('Hourly', self::SLUG),
                    'value' => '3600'
                ), array(
                    'label' => esc_html__('Daily', self::SLUG),
                    'value' => '86400'
                ), array(
                    'label' => esc_html__('Weekly', self::SLUG),
                    'value' => '604800'
                )
            )
        );
    }

    public static function render_send_test_notification_email() {
        $credentials = get_option('watsonconv_notification_send_test');
        ?>
        <button type="button" id="watsonconv_notification_send_test" class="button-primary">
            Send test notification e-mail
        </button>
        <?php
    }

    public static function validate_notification_email_to($emails_string) {
        if($emails_string){
            $validation_result = self::validate_emails_string($emails_string);
            $emails = $validation_result["all"];
            $malformed_emails = $validation_result["malformed"];

            // Processing malformed addresses
            $malformed_emails_count = count($malformed_emails);
            if(count($malformed_emails) > 0) {
                $address_wording = ($malformed_emails_count == 1) ? "This address is" : "These addresses are";
                $incorrect_addresses = implode(", ", $malformed_emails);
                $error_message = "{$address_wording} not correct: {$incorrect_addresses}. Please use valid format for email (e.g. email@example.com).";
                add_settings_error(
                    'watsonconv_notification_email_to',
                    'invalid-email',
                    $error_message
                );
                return get_option('watsonconv_notification_email_to');
            } else {
                return implode(", ", $emails);
            }
        }
    }

    public static function validate_emails_string($emails_string) {
        // Empty array to store emails
        $emails = array();
        // Filling array
        $emails = explode(",", $emails_string);
        // Amount of emails
        $emails_count = count($emails);
        // Array for malformed emails
        $malformed_emails = array();
        // Trimming spaces and validating addresses
        for($i = 0; $i < $emails_count; $i++) {
            // Trimming spaces
            $emails[$i] = trim($emails[$i]);
            // Adding malformed emails to the array 
            if( !filter_var($emails[$i], FILTER_VALIDATE_EMAIL) ) {
                // Pushing address to array, adding quotes
                array_push($malformed_emails, "\"{$emails[$i]}\"");
            }
        }
        // Is string valid?
        $valid_string = false;
        if( (count($malformed_emails) == 0) && (count($emails) > 0) ) {
            $valid_string = true;
        }
        // Result of validation
        $result = array(
            "string" => $emails_string,
            "all" => $emails,
            "malformed" => $malformed_emails,
            "valid" => $valid_string
        );
        return $result;
    }

    public static function validate_notification_settings($settings) {
        $is_valid = true;
        $enabled = get_option('watsonconv_notification_enabled', '') === 'yes';
        if ($enabled) {
            $email = get_option('watsonconv_notification_email_to', '');
            if (empty($email)) {
                add_settings_error(
                    'watsonconv_notification_email_to',
                    'invalid-email',
                    'Please specify recipient e-mail address'
                );
                $is_valid = false;
            }


            \WatsonConv\Email_Notificator::reset_summary_prev_ts();
        }
        return $settings;
    }

    public static function send_test_notification() {
        // Check if current user is permitted to control plugins
        if(!current_user_can('administrator')) {
            Logger::log_message("Unauthorized REST API access", "Unauthorized access while sending test notification");
            return new \WP_REST_Response('Not Authorized', 403);
        }

        $emails_list = $_POST['emails'];
        $emails_validation = self::validate_emails_string($emails_list);
        $status_message = "";
        $error = false;

        $emails_plural_affix = (count($emails_validation["all"]) > 1) ? "s" : "";


        if($emails_validation["valid"]) {
            $sending_status = \WatsonConv\Email_Notificator::send_summary_notification(true, $emails_list);

            if($sending_status === true) {
                $status_message = "Email{$emails_plural_affix} successfully sent";
            }
            else if(is_array($sending_status)) {
                $error = true;
                $status_message = "Errors while sending email{$emails_plural_affix}: " . implode(", ", $sending_status) . ". Please check your settings on \"Email Settings\" tab.";
            }
        }
        else if(!$emails_validation["valid"]) {
            $error = true;
            if( count($emails_validation["all"]) == 0 ) {
                $status_message = "No email addresses";
            }
            else {
                $malformed_emails = $emails_validation["malformed"];
                $address_wording = (count($malformed_emails) == 1) ? "This address is" : "These addresses are";
                $incorrect_addresses = implode(", ", $malformed_emails);
                $status_message = "{$address_wording} not correct: {$incorrect_addresses}. Please use valid format for email (e.g. email@example.com).";
            }
        }

        return self::render_test_notification_status($status_message, $error);
    }

    public static function render_test_notification_status($message, $is_error){
        $error_class = $is_error ? "error" : "updated";
        $message_tag_id = "setting-error-settings_error";
        $message_tag_class = "{$error_class} settings-error notice is-dismissible";
        $message_tag_start = "<div id=\"{$message_tag_id}\" class=\"{$message_tag_class}\">";
        $message_paragraph = "<p>{$message}</p>";
        $dismiss_button = '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notice.</span></button>';
        $message_tag_end = "</div>";
        $full_html = "{$message_tag_start}{$message_paragraph}{$dismiss_button}{$message_tag_end}";
        return $full_html;
    }
}
