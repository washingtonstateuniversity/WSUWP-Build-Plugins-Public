<?php

namespace MultiplePostPasswords\Admin;

use MultiplePostPasswords\Frontend\PagePasswords;

class Settings_Page
{

    protected $args;

    public function __construct($args = null)
    {
        $this->args = wp_parse_args(

            $args,
            [
                'slug' => 'multiple-post-passwords-settings',
                'settings_prefix' => 'mpp_',
                'page_title' => __('Multiple Post Passwords','multiple-post-passwords'),
                'settings' => array()
            ]
        );

        $this->settings = $this->args['settings'];

        // Initialise settings
        add_action('admin_menu', array($this, 'init'));

        // Add settings page to menu
        add_action('admin_menu', array($this, 'add_menu_item'));

        // Register plugin settings
        add_action('admin_init', array($this, 'register_settings'));

        // Add settings link to plugins page
        add_filter('plugin_action_links_'.$args['plugin_basename'], array($this, 'add_settings_link'));
    }

    /**
     * Initialise settings
     * @return void
     */
    public function init()
    {
        if (empty($this->settings)){
            $this->settings = $this->get_settings_fields();
        }
    }

    /**
     * Add settings page to admin menu
     * @return void
     */
    public function add_menu_item()
    {
        // add to WP settings as subpage
        add_submenu_page(
            'options-general.php',
            $this->args['page_title'],
            $this->args['page_title'],
            'manage_options',
             $this->args['slug'],
            array($this, 'render_page')
        );
    }


    /**
     * Add settings link to plugin list table
     * @param array $links Existing links
     * @return array        Modified links
     */
    public function add_settings_link($links) {
        $settings_link = '<a href="admin.php?page='.$this->args['slug'].'">'.__('Settings').'</a>';
        array_push($links, $settings_link);
        return $links;
    }

    /**
     * Build settings fields
     * @return array Fields to be displayed on settings page
     */
    private function get_settings_fields()
    {

        $settings = array(
            'used_passwords_deletion' => array(
                'title' =>  __('Used Passwords Deletion','multiple-post-passwords'),
                'fields' => array(
                    array(
                        'name' => 'delete_used_passwords',
                        'label' => __('Delete used passwords','multiple-post-passwords'),
                        'type' => 'checkbox',
                        'description' => __('Activate to delete used passwords after a defined period of time','multiple-post-passwords'),
                    ),
                    array(
                        'name' => 'password_expire_hours',
                        'label' => __('Expire passwords after x hours','multiple-post-passwords'),
                        'type' => 'number',
                        'default' => PagePasswords::DEFAULT_PASSWORD_EXPIRE_HOURS,
                        'min' => 0,
                        'step' => '0.1',
                        'description' => __('<br>After being used the password will expire after the specified amount of hours.<br>0.5 -> the password will expire after half an hour<br>24 -> the password will expire after one day<br>168 -> the password will expire after one week ', 'multiple-post-passwords'),
                    ),
                    array(
                        'name' => 'used_pw_deletion_notification_email',
                        'label' => __('Email to send notification on password deletion','multiple-post-passwords'),
                        'type' => 'email',
                        'placeholder' => '',
                        'description' => __('Email to send notification on email deletion. Mainly for testing if all works as wanted. Leave empty if you don´t want to get a notification.', 'multiple-post-passwords'),
                    ),
                )
            ),
            'alternative_password_check' => array(
                'title' =>  __('Alternative Password Check','multiple-post-passwords'),
                'fields' => array(
                    array(
                        'name' => 'use_alternative_password_check',
                        'label' => __('Use alternative password check','multiple-post-passwords'),
                        'type' => 'checkbox',
                        'description' => __('You can use this type of password check if you are using lots of passwords in one page/post. This should speed up things dramatically.','multiple-post-passwords'),
                    ),
                ),
            )
        );

        return $settings;
    }

    /**
     * Register plugin settings
     * @return void
     */
    public function register_settings()
    {
        if (is_array($this->settings)) {
            foreach ($this->settings as $section => $data) {

                // Add section to page
                add_settings_section($section, $data['title'], array($this, 'settings_section'), $this->args['slug']);

                foreach ($data['fields'] as $field) {

                    // Sanitize callback for field
                    $sanitize_callback = '';
                    if (isset($field['sanitize_callback'])) {
                        $sanitize_callback = $field['sanitize_callback'];
                    }

                    // Register field
                    $option_name = $this->args['settings_prefix'] . $field['name'];

                    register_setting($this->args['slug'], $option_name, $sanitize_callback);

                    // Add field to page
                    add_settings_field($field['name'], $field['label'], array($this, 'display_field'), $this->args['slug'], $section, array('field' => $field));
                }
            }
        }
    }

    public function settings_section($section)
    {
        $html = '<p> ' . $this->settings[$section['id']]['description'] . '</p>' . PHP_EOL;
        echo $html;
    }


    /**
     * Generate HTML for displaying fields
     * @param array $args Field data
     * @return void
     */
    public function display_field($args)
    {

        $field = $args['field'];

        if(empty($field['id'])){
            $field['id'] =  $field['name'];
        }

        $option_name = $this->args['settings_prefix'] . $field['id'];

        $option = get_option($option_name);

        $data = '';
        if (isset($field['default'])) {
            $data = $field['default'];
        }
        if ($option) {
            $data = $option;
        }

        $html = '';

        switch ($field['type']) {

            case 'text':
            case 'password':
            case 'time':
            case 'email':
            case 'number':
                $params = '';
                if(strlen($field['min']) > 0){
                    $min = intval($field['min']);
                    $params .= ' min="'.$min.'" ';
                    $data = max($min, floatval($data));
                }
                if(!empty($field['step'])){
                    $params .= ' step="'.floatval($field['step']).'" ';
                }
                $html .= '<input id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '" value="' . $data . '" '.$params.'/>' . PHP_EOL;
                break;

            case 'checkbox':
                $checked = '';
                if ($option && 'on' == $option) {
                    $checked = 'checked="checked"';
                }
                $html .= '<input id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" ' . $checked . '/>' . PHP_EOL;
                break;

        }

        switch ($field['type']) {

            case 'checkbox_multi':
            case 'radio':
            case 'select_multi':
                $html .= '<br/><span class="description">' . $field['description'] . '</span>';
                break;

            default:
                $html .= '<label for="' . esc_attr($field['id']) . '"><span class="description">' . $field['description'] . '</span></label>' . PHP_EOL;
                break;
        }

        echo $html;
    }


    /**
     * Load settings page content
     * @return void
     */
    public function render_page()
    {

        // Build page HTML
        $html = '';
        $html .= '<div class="wrap" id="'.$this->args['slug'].'">' . PHP_EOL;
        $html .= '<h1>'.$this->args['page_title'].'</h1>';
        $html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . PHP_EOL;

        ob_start();

        settings_fields($this->args['slug']);

        foreach ($this->settings as $section => $settings_tab) {
            ?>

                <h2 class="title"><?= $settings_tab['title'] ?></h2>

                <table class="form-table">
                    <?php do_settings_fields($this->args['slug'], $section); ?>
                </table>

            <?php
        }

        submit_button();

        $html .= ob_get_clean();

        $html .= '</form>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;

        ob_start();

        $html .= ob_get_clean();

        echo $html;
    }



}
