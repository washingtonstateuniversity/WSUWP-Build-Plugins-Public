<?php

namespace MultiplePostPasswords\Admin;

/**
 * Show Metabox with multiple passwords in edit post/page admin view.
 *
 * @since 1.0.0
 */
class Metabox
{
    public static $value_slug = '_mpp_additional_passwords';

    protected $nonce_slug = '_mpp_nonce';

    /**
     * The single instance of the class.
     *
     * @var self
     *
     * @since  1.0.0
     */
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes'], 10, 2);

        add_action('save_post', [$this, 'save_meta_box_data']);
    }

    public function add_meta_boxes($post_type, $post)
    {
        // only show if post protection is enabled
        if (empty($post->post_password)) {
            return;
        }

        // add for all public post types
        $post_types = get_post_types(['public' => true]);

        add_meta_box('mpp-passwords-metabox', __('Additional Passwords', 'multiple-post-passwords'), [$this, 'display_meta_box'], $post_types, 'side', 'high');
    }

    /**
     * Display the meta box.
     *
     * @param \WP_Post $post
     */
    public function display_meta_box($post)
    {
        // Add a nonce field so we can check for it later.
        wp_nonce_field($this->nonce_slug, $this->nonce_slug);

        $value = get_post_meta($post->ID, self::$value_slug, true);

        // should be an array, test for backwards compatibility
        $value_string = (is_array($value)) ? implode("\n", $value) : $value;

        echo '<textarea rows="6" style="width:100%" id="'.self::$value_slug.'" name="'.self::$value_slug.'">'.$value_string.'</textarea>';
        echo '<p>'.__('Add one password per line.', 'multiple-post-passwords').'</p>';
    }

    /**
     * When the post is saved, saves our custom data.
     *
     * @since 1.0.0
     *
     * @param int $post_id
     */
    public function save_meta_box_data($post_id)
    {
        // Check if our nonce is set.
        if (!isset($_POST[$this->nonce_slug])) {
            return;
        }

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($_POST[$this->nonce_slug], $this->nonce_slug)) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        if (!isset($_POST[self::$value_slug])) {
            return;
        }

        $passwords = explode("\n", esc_textarea($_POST[self::$value_slug]));
        $passwords = array_map('trim', $passwords);
        $passwords = array_unique($passwords);

        // Update the meta field in the database.
        update_post_meta($post_id, self::$value_slug, $passwords);
    }
}
