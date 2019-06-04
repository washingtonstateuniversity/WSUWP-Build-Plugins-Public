<?php
namespace WatsonConv\Settings;

class Customize {
    const SLUG = 'watson_asst_customize';

    public static function init_page() {
        add_submenu_page(Main::SLUG, 'Watson Assistant Plugin Customization', 
            'Customize Plugin', 'manage_options', self::SLUG, array(__CLASS__, 'render_page'));
    }

    public static function init_settings() {
        self::init_behaviour_settings();
        self::init_chat_box_settings();
        self::init_fab_settings();
    }

    public static function render_page() {
    ?>
        <div class="wrap" style="max-width: 95em">
            <h2><?php esc_html_e('Customize Your Plugin', self::SLUG); ?></h2>
            
            <?php 
                Main::render_isv_banner(); 
                settings_errors(); 
            ?>

            <h2 class="nav-tab-wrapper">
                <a onClick="switch_tab('behaviour')" class="nav-tab nav-tab-active behaviour_tab">Behaviour</a>
                <a onClick="switch_tab('chat_box')" class="nav-tab chat_box_tab">Chat Box</a>
                <a onClick="switch_tab('fab')" class="nav-tab fab_tab">Chat Button</a>
            </h2>

            <form action="options.php" method="POST">
                <?php
                    settings_fields(self::SLUG); 

                    ?>
                        <div class="tab-page behaviour_page">
                            <?php do_settings_sections(self::SLUG.'_behaviour') ?>
                        </div>
                        <div class="tab-page chat_box_page" style="display: none">
                            <?php do_settings_sections(self::SLUG.'_chat_box') ?>
                        </div>
                        <div class="tab-page fab_page" style="display: none">
                            <?php do_settings_sections(self::SLUG.'_fab') ?>
                        </div>
                    <?php
                ?>

                <input type="hidden" value="" name="watsonconv_css_cache" />

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

    // ------------- Behaviour Settings ----------------

    public static function init_behaviour_settings() {
        $settings_page = self::SLUG . '_behaviour';

        add_settings_section('watsonconv_behaviour', '',
            array(__CLASS__, 'behaviour_description'), $settings_page);

        $delay_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'When you use this setting, the chat box will wait for the chosen number of seconds
                before being displayed to the user.'
                , self::SLUG
            ),
            esc_html__('Delay Before Pop-Up', self::SLUG)
        );

        $show_on_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'By default, the chat box pop-up will display on every page of your website.
                If you choose "Only Certain Pages", you can control which pages you want users
                to see your chat box on.', 
                self::SLUG
            ),
            esc_html__('Show Chat Box On:', self::SLUG)
        );

        $front_page_title = sprintf(
            '<span href="#" title="%s">%s</span>',
            esc_html__(
                'This is usually the first page users see when they visit your website.
                By default, this is a list of the latest posts on your website. However, this 
                can also be set to a static page in the Reading section of your Settings.', 
                self::SLUG
            ),
            esc_html__('Front Page', self::SLUG)
        );

        $pages_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'Simply check the boxes next to the pages you want the floating chat box to display on.
                If you want the chat box to display on every page in this list, you can click the
                check box at the top next to "Select all Pages".', 
                self::SLUG
            ),
            esc_html__('Pages', self::SLUG)
        );

        $posts_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'Simply check the boxes next to the posts you want the floating chat box to display on.
                If you want the chat box to display on every post in this list, you can click the
                check box at the top next to "Select all Posts".', 
                self::SLUG
            ),
            esc_html__('Posts', self::SLUG)
        );

        $cats_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'Here, you can select which categories of posts you want to display the chat box on.
                The chat box will display on every post in the selected categories.', 
                self::SLUG
            ),
            esc_html__('Categories', self::SLUG)
        );

        add_settings_field('watsonconv_delay', $delay_title,
            array(__CLASS__, 'render_delay'), $settings_page, 'watsonconv_behaviour');

        add_settings_field('watsonconv_show_on', $show_on_title,
            array(__CLASS__, 'render_show_on'), $settings_page, 'watsonconv_behaviour');
        add_settings_field('watsonconv_home_page', $front_page_title,
            array(__CLASS__, 'render_home_page'), $settings_page, 'watsonconv_behaviour');
        add_settings_field('watsonconv_pages', $pages_title,
            array(__CLASS__, 'render_pages'), $settings_page, 'watsonconv_behaviour');
        add_settings_field('watsonconv_posts', $posts_title,
            array(__CLASS__, 'render_posts'), $settings_page, 'watsonconv_behaviour');
        add_settings_field('watsonconv_categories', $cats_title,
            array(__CLASS__, 'render_categories'), $settings_page, 'watsonconv_behaviour');

        register_setting(self::SLUG, 'watsonconv_delay');

        register_setting(self::SLUG, 'watsonconv_show_on');
        register_setting(self::SLUG, 'watsonconv_home_page');
        register_setting(self::SLUG, 'watsonconv_pages', array(__CLASS__, 'sanitize_array'));
        register_setting(self::SLUG, 'watsonconv_posts', array(__CLASS__, 'sanitize_array'));
        register_setting(self::SLUG, 'watsonconv_categories', array(__CLASS__, 'sanitize_array'));
    }
    
    public static function sanitize_array($val) {
        return empty($val) ? array(-1) : $val;
    }

    public static function behaviour_description($args) {
    ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>">
            <?php esc_html_e('This section allows you to customize
                how you want the chat box to behave. These settings display the chatbox as a
                floating box on the specified pages. If you want to show the chat box inline within 
                posts and pages, you can use the shortcode', self::SLUG) ?> <b>[watson-chat-box]</b>.
        </p>
    <?php
    }

    public static function render_delay() {
        $delay = get_option('watsonconv_delay');
    ?>
        <input name="watsonconv_delay" id="watsonconv_delay" type="number"
            value="<?php echo empty($delay) ? 0 : $delay?>"
            style="width: 4em" />
        seconds
    <?php
    }

    public static function render_show_on() {
        Main::render_radio_buttons(
            'watsonconv_show_on',
            'all',
            array(
                array(
                    'label' => esc_html__('All Pages', self::SLUG),
                    'value' => 'all'
                ), array(
                    'label' => esc_html__('Only Certain Pages', self::SLUG),
                    'value' => 'only'
                )
            )
        );

    ?>
        <span class="show_on_only">
            <br>
            Please select which pages you want to display the chat box on from the options below:
        </span>
    <?php
    }

    public static function migrate_old_show_on() {
        try {
            $show_on = get_option('watsonconv_show_on');
            $home_page = get_option('watsonconv_home_page', 'false') == true;
            $pages = get_option('watsonconv_pages', array(-1));
            $posts = get_option('watsonconv_posts', array(-1));
            $cats = get_option('watsonconv_categories', array(-1));

            if ($show_on == 'all_except') {
                if (!$home_page && $pages == array(-1) && $posts == array(-1) && $cats == array(-1)) {
                    update_option('watsonconv_show_on', 'all');
                } else {
                    update_option('watsonconv_show_on', 'only');
                    update_option('watsonconv_home_page', $home_page ? 'false' : 'true');

                    update_option('watsonconv_pages', array_diff(
                            array_map(function($page) {return $page->ID;}, get_pages()),
                            $pages
                    ));

                    update_option('watsonconv_posts', array_diff(
                            array_map(function($post) {return $post->ID;}, get_posts()),
                            $posts
                    ));

                    update_option('watsonconv_categories', array_diff(
                            array_map(function($cat) {return $cat->cat_ID;}, get_categories(array('hide_empty' => 0))),
                            $cats
                    ));
                }
            }
        } catch (\Exception $e) {}
    }

    public static function render_home_page() {
    ?>
        <fieldset class="show_on_only">
            <input
                type="checkbox" id="watsonconv_home_page"
                name="watsonconv_home_page" value="true"
                <?php checked('true', get_option('watsonconv_home_page', 'false')) ?>
            />
            <label for="watsonconv_home_page">
                Front Page
            </label>
        </fieldset>
    <?php
    }

    public static function render_pages() {
    ?>
        <fieldset class="show_on_only" style="border: 1px solid black; padding: 1em">
            <legend>
                <input id="select_all_pages" type="checkbox"/>
                <label for="select_all_pages">Select All Pages</label>
            </legend>
            <?php
                $pages = get_pages(array(
                    'sort_column' => 'post_date',
                    'sort_order' => 'desc'
                ));
                $checked_pages = get_option('watsonconv_pages');

                foreach ($pages as $page) {
                ?>
                    <input
                        type="checkbox" id="pages_<?php echo $page->ID ?>"
                        name="watsonconv_pages[]" value="<?php echo $page->ID ?>"
                        <?php if (in_array($page->ID, (array)$checked_pages)): ?>
                            checked
                        <?php endif; ?>
                    />
                    <label for="pages_<?php echo $page->ID; ?>">
                        <?php echo $page->post_title ?>
                    </label>
                    <span style="float: right">
                        <?php echo $page->post_date ?>
                    </span>
                    <br>
                <?php
                }
            ?>
        </fieldset
    <?php
    }

    public static function render_posts() {
    ?>
        <fieldset class="show_on_only" style="border: 1px solid black; padding: 1em">
            <legend>
                <input id="select_all_posts" type="checkbox"/>
                <label for="select_all_posts">Select All Posts</label>
            </legend>
            <?php
                $posts = get_posts(array('order_by' => 'date'));
                $checked_posts = get_option('watsonconv_posts');

                foreach ($posts as $post) {
                ?>
                    <input
                        type="checkbox" id="posts_<?php echo $post->ID ?>"
                        name="watsonconv_posts[]" value="<?php echo $post->ID ?>"
                        <?php if (in_array($post->ID, (array)$checked_posts)): ?>
                            checked
                        <?php endif; ?>
                    />
                    <label for="posts_<?php echo $post->ID; ?>">
                        <?php echo $post->post_title ?>
                    </label>
                    <span style="float: right">
                        <?php echo $post->post_date ?>
                    </span>
                    <br>
                <?php
                }
            ?>
        </fieldset
    <?php
    }

    public static function render_categories() {
    ?>
        <fieldset class="show_on_only" style="border: 1px solid black; padding: 1em">
            <legend>
                <input id="select_all_cats" type="checkbox"/>
                <label for="select_all_cats">Select All Categories</label>
            </legend>
            <?php
                $cats = get_categories(array('hide_empty' => 0));
                $checked_cats = get_option('watsonconv_categories');

                foreach ($cats as $cat) {
                ?>
                    <input
                        type="checkbox" id="cats_<?php echo $cat->cat_ID ?>"
                        name="watsonconv_categories[]" value="<?php echo $cat->cat_ID ?>"
                        <?php if (in_array($cat->cat_ID, (array)$checked_cats)): ?>
                            checked
                        <?php endif; ?>
                    />
                    <label for="cats_<?php echo $cat->cat_ID ?>">
                        <?php echo $cat->cat_name ?>
                    </label>
                    <span style="float: right; margin-left: 4em">
                        <?php echo $cat->category_description ?>
                    </span>
                    <br>
                <?php
                }
            ?>
        </fieldset
    <?php
    }

    // --------- Chat Box Appearance Settings -----------

    public static function init_chat_box_settings() {
        $settings_page = self::SLUG . '_chat_box';

        add_settings_section('watsonconv_appearance_chatbox', 'Chat Box',
            array(__CLASS__, 'chatbox_description'), $settings_page);

        $full_screen_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'Choosing "Always" causes the chat box to always display in full-screen mode. 
                On small devices, it can get hard to use the default draggable floating chat box.
                By checking the "Only Small Devices" option, you can keep the floating chat box
                for laptops and desktop computers while showing it in full screen mode for mobile
                devices with a smaller width. The "Never" option causes the floating chat box to
                always be used, though this can be difficult to use on small devices. Advanced users
                can also write their own custom CSS media query by choosing the last option.'
                , self::SLUG
            ),
            esc_html__('Full Screen', self::SLUG)
        );

        $minimized_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This setting only affects how the chat box appears to the user the first time they
                see it in a single browser session. On every page after the first one, the minimized
                state will be controlled by the user. If you want to force the chat box to be minimized
                on a specific page, you can add "?chat_min=yes" to the end of the URL (without the quotes).'
                , self::SLUG
            ),
            esc_html__('Chat Box Minimized by Default', self::SLUG)
        );

        $position_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This setting determines which corner of the screen the floating chat box will appear
                in when the user first sees it. If the chat box isn\'t in full screen mode, the user
                can then drag it to a different position if they please.'
                , self::SLUG
            ),
            esc_html__('Position', self::SLUG)
        );

        $send_btn_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'Users can send messages from the text box by pressing the "Enter" key on desktop, 
                or "Submit"/"Go" on mobile device keyboards. If you set this setting to "Yes", then 
                there will also be a button next to the text box to give the user another option for
                sending messages.'
                , self::SLUG
            ),
            esc_html__('Show Send Message Button', self::SLUG)
        );

        $typing_delay_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'When this is enabled, the plugin will display a "typing" animation for a short time
                before displaying chatbot responses, to make it appear as if the chatbot is thinking
                and typing like a real person.'
                , self::SLUG
            ),
            esc_html__('Chatbot Typing Animation', self::SLUG)
        );

        // Weird, I know
        $title_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This title appears at the top of the chat box, above the messages.'
                , self::SLUG
            ),
            esc_html__('Chat Box Title', self::SLUG)
        );

        $clear_text_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This is the tooltip for the button the user can click to clear the conversation 
                history and start over.'
                , self::SLUG
            ),
            esc_html__('"Clear Messages" Tooltip', self::SLUG)
        );

        $message_prompt_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This is the text that appears in the message text box to prompt the user to type a message.'
                , self::SLUG
            ),
            esc_html__('"Type Message" Prompt', self::SLUG)
        );

        $font_size_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This changes the font size of the title and messages in the chat box.'
                , self::SLUG
            ),
            esc_html__('Font Size', self::SLUG)
        );

        $font_size_fs_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This changes the font size when the chat box is displaying in full screen mode.'
                , self::SLUG
            ),
            esc_html__('Font Size in Full Screen', self::SLUG)
        );

        $color_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This changes the color of the chatbox header, and the background color of messages
                received by the user from the chatbot. If your version of Wordpress does not support
                the color picker, you will have to manually enter the color in hexadecimal format 
                prefixed with #. For example, white would be written as #ffffff or #FFFFFF, and black
                would be written as #000000.'
                , self::SLUG
            ),
            esc_html__('Color', self::SLUG)
        );

        $size_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This changes the size of the floating chat box window, allowing more space for the
                messages.'
                , self::SLUG
            ),
            esc_html__('Window Size', self::SLUG)
        );
        

        add_settings_field('watsonconv_full_screen', $full_screen_title,
            array(__CLASS__, 'render_full_screen'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_minimized', $minimized_title,
            array(__CLASS__, 'render_minimized'), $settings_page, "watsonconv_appearance_chatbox");
        add_settings_field('watsonconv_position', $position_title,
            array(__CLASS__, 'render_position'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_send_btn', $send_btn_title,
                array(__CLASS__, 'render_send_btn'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_typing_delay', $typing_delay_title,
                array(__CLASS__, 'render_typing_delay'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_title', $title_title,
            array(__CLASS__, 'render_title'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_clear_text', $clear_text_title,
            array(__CLASS__, 'render_clear_text'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_message_prompt', $message_prompt_title,
            array(__CLASS__, 'render_message_prompt'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_font_size', $font_size_title,
            array(__CLASS__, 'render_font_size'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_font_size_fs', $font_size_fs_title,
            array(__CLASS__, 'render_font_size_fs'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_color', $color_title,
            array(__CLASS__, 'render_color'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_size', $size_title,
            array(__CLASS__, 'render_size'), $settings_page, 'watsonconv_appearance_chatbox');
        add_settings_field('watsonconv_chatbox_preview', esc_html__('Preview'),
            array(__CLASS__, 'render_chatbox_preview'), $settings_page, 'watsonconv_appearance_chatbox');

        register_setting(self::SLUG, 'watsonconv_minimized');
        register_setting(self::SLUG, 'watsonconv_full_screen', array(__CLASS__, 'parse_full_screen_settings'));
        register_setting(self::SLUG, 'watsonconv_position');
        register_setting(self::SLUG, 'watsonconv_send_btn');
        register_setting(self::SLUG, 'watsonconv_typing_delay');
        register_setting(self::SLUG, 'watsonconv_title');
        register_setting(self::SLUG, 'watsonconv_clear_text');
        register_setting(self::SLUG, 'watsonconv_message_prompt');
        register_setting(self::SLUG, 'watsonconv_font_size');
        register_setting(self::SLUG, 'watsonconv_font_size_fs');
        register_setting(self::SLUG, 'watsonconv_color', array(__CLASS__,  'validate_color'));
        register_setting(self::SLUG, 'watsonconv_size');

        register_setting(self::SLUG, 'watsonconv_css_cache');
    }

    public static function chatbox_description($args) {
    ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>">
            <?php esc_html_e('This section allows you to specify how you want
                the chat box to appear to your site visitor.', self::SLUG) ?>
        </p>
    <?php
    }

    public static function clear_css_cache($upgrader_object, $options) {
        try {
            $current_plugin_path_name = plugin_basename( __FILE__ );
            
            // Array to store results of checks
            $correct = array();
            // Checking if $options is passed to this function
            $correct["options"] = isset($options);
            // Checking if action is set
            $correct["action"] = isset($options["action"]);
            // Checking if option type is set
            $correct["type"] = isset($options["type"]);
            // Checking if we have plugins list
            $correct["plugins_exist"] = isset($options["plugins"]);
            // Checking if plugins list is array
            if($correct["plugins_exist"]) {
                $correct["plugins_array"] = is_array($options["plugins"]);
            }
            else {
                $correct["plugins_array"] = false;
            }
            // Checking if everything is alright
            foreach ($correct as $check_name => $check_result) {
                // If one of the checks failed, exiting
                if(!$check_result) {
                    return;
                }
            }

            if ($options['action'] == 'update' && $options['type'] == 'plugin' ) {
                foreach($options['plugins'] as $each_plugin){
                    if ($each_plugin == $current_plugin_path_name) {
                        delete_option('watsonconv_css_cache');
                    }
                }
            }
        } catch (\Exception $e) {}
    }

    public static function render_minimized() {
        Main::render_radio_buttons(
            'watsonconv_minimized',
            'no',
            array(
                array(
                    'label' => esc_html__('Always', self::SLUG),
                    'value' => 'yes'
                ), array(
                    'label' => esc_html__('When Chat Box is Windowed', self::SLUG),
                    'value' => 'window'
                ), array(
                    'label' => esc_html__('When Chat Box is Full Screen', self::SLUG),
                    'value' => 'fullscreen'
                ), array(
                    'label' => esc_html__('Never', self::SLUG),
                    'value' => 'no'
                )
            )
        );
    }

    public static function migrate_old_full_screen() {
        try {
            if (get_option('watsonconv_full_screen') == 'yes') {
                update_option(
                    'watsonconv_full_screen', 
                    array(
                        'mode' => 'all',
                        'max_width' => '640px',
                        'query' => '%s'
                    )
                );
            } else if (get_option('watsonconv_full_screen') == 'no') {
                update_option(
                    'watsonconv_full_screen', 
                    array(
                        'mode' => 'mobile',
                        'max_width' => '640px',
                        'query' => '@media screen and (max-width:640px) { %s }'
                    )
                );
            }
        } catch (\Exception $e) {}
    }

    public static function parse_full_screen_settings($settings) {
        if ($settings['mode'] == 'all') {
            $settings['query'] = '%s';
        } else if ($settings['mode'] == 'mobile') {
            $settings['query'] = '@media screen and (max-width:'.$settings['max_width'].') { %s }';
        } else if ($settings['mode'] == 'custom') {
            $settings['query'] = $settings['query'] . ' { %s }';
        } else {
            $settings['query'] = '';
        }

        return $settings;
    }
    
    public static function render_full_screen() {
        $settings = get_option('watsonconv_full_screen');

        $mode = isset($settings['mode']) ? $settings['mode'] : 'mobile';
        $max_width = isset($settings['max_width']) ? $settings['max_width'] : '640px';
        $query = isset($settings['query']) ? 
            substr($settings['query'], 0, -7) : 
            '@media screen and (max-width:640px)';

        ?>
            <label for="watsonconv_full_screen_all">
                <input
                    name="watsonconv_full_screen[mode]"
                    id="watsonconv_full_screen_all"
                    type="radio"
                    value="all"
                    <?php checked('all', $mode) ?>
                >
                Always
            </label><br />

            <label for="watsonconv_full_screen_mobile">
                <input
                    name="watsonconv_full_screen[mode]"
                    id="watsonconv_full_screen_mobile"
                    type="radio"
                    value="mobile"
                    <?php checked('mobile', $mode) ?>
                >
                Only Small Devices
            </label><br />
            <div id="watsonconv_full_screen_max_width">
                Maximum Width: 
                <input
                    name="watsonconv_full_screen[max_width]"
                    type="text"
                    value="<?php echo $max_width ?>"
                    style="width: 6em"
                >
            </div>

            <label for="watsonconv_full_screen_never">
                <input
                    name="watsonconv_full_screen[mode]"
                    id="watsonconv_full_screen_never"
                    type="radio"
                    value="never"
                    <?php checked('never', $mode) ?>
                >
                Never (Not recommended)
            </label><br />
            
            <label for="watsonconv_full_screen_custom">
                <input
                    name="watsonconv_full_screen[mode]"
                    id="watsonconv_full_screen_custom"
                    type="radio"
                    value="custom"
                    <?php checked('custom', $mode) ?>
                >
                Custom CSS query (Advanced)
            </label><br />
            <div id="watsonconv_full_screen_query">
                Query: 
                <input
                    name="watsonconv_full_screen[query]"
                    type="text"
                    value="<?php echo $query ?>"
                    style="width: 40em"
                >
            </div>
        <?php
    }

    public static function render_position() {
        $top_left_box =
            "<div class='preview-window'>
                <div class='preview-box' style='top: 1em; left: 1em'></div>
            </div>";

        $top_right_box =
            "<div class='preview-window'>
                <div class='preview-box' style='top: 1em; right: 1em'></div>
            </div>";

        $bottom_left_box =
            "<div class='preview-window'>
                <div class='preview-box' style='bottom: 1em; left: 1em'></div>
            </div>";

        $bottom_right_box =
            "<div class='preview-window'>
                <div class='preview-box' style='bottom: 1em; right: 1em'></div>
            </div>";

        Main::render_radio_buttons(
            'watsonconv_position',
            'bottom_right',
            array(
                array(
                    'label' => esc_html__('Top-Left', self::SLUG) . $top_left_box,
                    'value' => 'top_left'
                ), array(
                    'label' => esc_html__('Top-Right', self::SLUG) . $top_right_box,
                    'value' => 'top_right'
                ), array(
                    'label' => esc_html__('Bottom-Left', self::SLUG) . $bottom_left_box,
                    'value' => 'bottom_left'
                ), array(
                    'label' => esc_html__('Bottom-Right', self::SLUG) . $bottom_right_box,
                    'value' => 'bottom_right'
                )
            ),
            'display: inline-block'
        );
    }

    public static function render_send_btn() {
        Main::render_radio_buttons(
            'watsonconv_send_btn',
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

    public static function render_typing_delay() {
        Main::render_radio_buttons(
            'watsonconv_typing_delay',
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

    public static function render_title() {
    ?>
        <input name="watsonconv_title" id="watsonconv_title"
            type="text" style="width: 16em"
            value="<?php echo get_option('watsonconv_title', '') ?>" />
    <?php
    }

    public static function render_clear_text() {
    ?>
        <input name="watsonconv_clear_text" id="watsonconv_clear_text"
            type="text" style="width: 16em"
            value="<?php echo get_option('watsonconv_clear_text', 'Clear Messages') ?>" />
    <?php
    }

    public static function render_message_prompt() {
    ?>
        <input name="watsonconv_message_prompt" id="watsonconv_message_prompt"
            type="text" style="width: 16em"
            value="<?php echo get_option('watsonconv_message_prompt', 'Type a Message') ?>" />
    <?php
    }

    public static function render_font_size() {
    ?>
        <input name="watsonconv_font_size" id="watsonconv_font_size"
            type="number" min=4 step=0.5 style="width: 4em"
            value="<?php echo get_option('watsonconv_font_size', 11) ?>" />
        pt
    <?php
    }

    public static function render_font_size_fs() {
    ?>
        <input name="watsonconv_font_size_fs" id="watsonconv_font_size_fs"
            type="number" min=4 step=0.5 style="width: 4em"
            value="<?php echo get_option('watsonconv_font_size_fs', 14) ?>" />
        pt
    <?php
    }

    public static function validate_color($val) {
        if (strlen($val) < 7 || count(sscanf($val, "#%02x%02x%02x")) !== 3) {
            add_settings_error('watsonconv_color', 'invalid-format', 
                'The color entered must be in 6-digit hexadecimal format prefixed with #. For example, 
                white would be written as #ffffff or #FFFFFF, and black would be written as #000000.');

            return get_option('watsonconv_color', '#23282d');
        }

        return $val;
    }

    public static function render_color() {
    ?>
        <input name="watsonconv_color" id="watsonconv_color"
            type="text" style="width: 6em"
            value="<?php echo get_option('watsonconv_color', '#23282d')?>" />
    <?php
    }

    public static function render_size() {
        Main::render_radio_buttons(
            'watsonconv_size',
            200,
            array(
                array(
                    'label' => esc_html__('Small', self::SLUG),
                    'value' => 160
                ), array(
                    'label' => esc_html__('Medium', self::SLUG),
                    'value' => 200
                ), array(
                    'label' => esc_html__('Large', self::SLUG),
                    'value' => 240
                )
            )
        );
    }

    public static function render_chatbox_preview() {
    ?>
        <div id='watson-box' class='drop-shadow animated' style='display: block;'>
            <div id='watson-header' class='watson-font' style='cursor: default;'>
                <span class='dashicons dashicons-arrow-down-alt2 header-button'></span>
                <span class='dashicons dashicons-trash header-button'></span>
                <span class='dashicons dashicons-phone header-button'></span>
                <div id='watson-title' class='overflow-hidden' ><?php echo get_option('watsonconv_title', '') ?></div>
            </div>
            <div id='message-container'>
                <div id='messages' class='watson-font'>
                    <div>
                        <div class='message watson-message'>
                            This is a message from the chatbot.
                        </div>
                    </div>
                    <div>
                        <div class='message user-message'>
                            This is a message from the user.
                        </div>
                    </div>
                    <div>
                        <div class='message watson-message'>
                            This message is a slightly longer message than the previous one from the chatbot.
                        </div>
                    </div>
                    <div>
                        <div class='message user-message'>
                            This message is a slightly longer message than the previous one from the user.
                        </div>
                    </div>
                    <div>
                        <div class='message watson-message'>
                            Below is an example of the Chatbot Typing Animation.
                        </div>
                        <div class='message watson-message'>
                            <div class='typing-dot'>
                            </div><div class='typing-dot'>
                            </div><div class='typing-dot'>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='message-form watson-font'>
                <input
                    id='watson-message-input'
                    class='message-input watson-font'
                    type='text'
                    placeholder='<?php echo get_option('watsonconv_message_prompt', 'Type a Message') ?>'
                    disabled='true'
                />
                <div id='message-send'>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 48 48" 
                            fill="white"
                        >
                            <path d="M4.02 42L46 24 4.02 6 4 20l30 4-30 4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    
    // ----------- FAB Appearance Settings --------------

    public static function init_fab_settings() {
        $settings_page = self::SLUG . '_fab';

        add_settings_section('watsonconv_appearance_button', 'Chat Button',
            array(__CLASS__, 'fab_description'), $settings_page);

        $fab_icon_pos_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'If you want the chat button to have an icon and a text label, then you can specify whether
                you want the icon to be on the left of the text or the right. If there is no text,
                the position doesn\'t matter. Alternatively, you can hide the icon and just use text.'
                , self::SLUG
            ),
            esc_html__('Icon Position', self::SLUG)
        );

        $fab_text_title = sprintf(
            '<span href="#" title="%s">%s</span>', 
            esc_html__(
                'This is the label for the chat button that users click to open the chat box. This
                can be left blank if you like.'
                , self::SLUG
            ),
            esc_html__('Text Label', self::SLUG)
        );
        
        add_settings_field('watsonconv_fab_icon_pos', $fab_icon_pos_title,
            array(__CLASS__, 'render_fab_icon_pos'), $settings_page, 'watsonconv_appearance_button');
        add_settings_field('watsonconv_fab_text', $fab_text_title,
            array(__CLASS__, 'render_fab_text'), $settings_page, 'watsonconv_appearance_button');
        add_settings_field('watsonconv_fab_icon_size', esc_html__('Icon Size'),
            array(__CLASS__, 'render_fab_icon_size'), $settings_page, 'watsonconv_appearance_button');
        add_settings_field('watsonconv_fab_text_size', esc_html__('Text Size'),
            array(__CLASS__, 'render_fab_text_size'), $settings_page, 'watsonconv_appearance_button');
        add_settings_field('watsonconv_fab_preview', esc_html__('Preview'),
            array(__CLASS__, 'render_fab_preview'), $settings_page, 'watsonconv_appearance_button');

        register_setting(self::SLUG, 'watsonconv_fab_icon_pos');
        register_setting(self::SLUG, 'watsonconv_fab_text');
        register_setting(self::SLUG, 'watsonconv_fab_icon_size');
        register_setting(self::SLUG, 'watsonconv_fab_text_size');
    }

    public static function fab_description($args) {
    ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>">
            <?php esc_html_e('This section allows you to customize the appearance of the button
                the user clicks to access the chat box.', self::SLUG) ?>
        </p>
    <?php
    }

    public static function render_fab_icon_pos() {
        Main::render_radio_buttons(
            'watsonconv_fab_icon_pos',
            'left',
            array(
                array(
                    'label' => esc_html__('Left of Text', self::SLUG),
                    'value' => 'left'
                ), array(
                    'label' => esc_html__('Right of Text', self::SLUG),
                    'value' => 'right'
                ), array(
                    'label' => esc_html__('Hide Icon', self::SLUG),
                    'value' => 'hide'
                )
            )
        );
    }

    public static function render_fab_text() {
    ?>
        <input name="watsonconv_fab_text" id="watsonconv_fab_text"
            type="text" style="width: 16em"
            value="<?php echo get_option('watsonconv_fab_text', '') ?>" />
    <?php
    }

    public static function render_fab_icon_size() {
    ?>
        <input name="watsonconv_fab_icon_size" id="watsonconv_fab_icon_size"
            type="number" min=4 step=0.5 style="width: 4em"
            value="<?php echo get_option('watsonconv_fab_icon_size', 28) ?>" />
        pt
    <?php
    }

    public static function render_fab_text_size() {
    ?>
        <input name="watsonconv_fab_text_size" id="watsonconv_fab_text_size"
            type="number" min=4 step=0.5 style="width: 4em"
            value="<?php echo get_option('watsonconv_fab_text_size', 15) ?>" />
        pt
    <?php
    }

    public static function render_fab_preview() {
    ?>
        <div id='watson-fab' class='drop-shadow animated-shadow' style='cursor: default;'>
            <span id='watson-fab-icon' class='fab-icon-left dashicons dashicons-format-chat' style='padding: 0;'></span>
            <span id='watson-fab-text' style='display: none; padding: 0;'>
                <?php echo get_option('watsonconv_fab_text') ?>
            </span>
            <span id='watson-fab-icon' class='fab-icon-right dashicons dashicons-format-chat' style='display: none; padding: 0;'></span>
        </div>
    <?php
    }
}
