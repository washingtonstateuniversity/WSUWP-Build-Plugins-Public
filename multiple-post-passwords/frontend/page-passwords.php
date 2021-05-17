<?php

namespace MultiplePostPasswords\Frontend;

use MultiplePostPasswords\Admin\Metabox;

/**
 * Frontend logic for enabling multiple passwords on protected pages
 *
 * @package multiple-post-passwords
 * @since 1.0.0
 */
class PagePasswords {

    /**
     * default time after which used passwords expire if expiration is activated
     */
    const DEFAULT_PASSWORD_EXPIRE_HOURS = 24;

    /**
     * @param $post_id
     * @return array
     */
    static function get_passwords($post_id)
    {
        $passwords = get_post_meta($post_id, \MultiplePostPasswords\Admin\Metabox::$value_slug, true);
        // should be an array, test for backwards compatibility
        if (!is_array($passwords)) $passwords = explode("\n", $passwords);
        return $passwords;
    }

    /**
     * @param $post_id
     * @param $password
     */
    static function maybe_set_password_used($post_id, $password) {

        // only save if deletion is activated
        if(!get_option('mpp_delete_used_passwords') ) {
            return;
        }

        $password = trim($password);

        $used_passwords = get_option('mpp_used_passwords', array());

        // exit if entry already exists
        foreach ($used_passwords as $used_password){
            if($used_password['post_id'] == $post_id && $used_password['password'] == $password){
                return;
            }
        }

        $used_passwords[] = array(
            'post_id' => $post_id,
            'password' => $password,
            'time_used' => time()
        );

        update_option('mpp_used_passwords', $used_passwords);

    }

    /**
     * delete array of passwords from post
     *
     * @param $post_id
     * @param $passwords
     */
    static function delete_passwords($post_id, $passwords_to_delete){

        $passwords = self::get_passwords($post_id);
        $passwords = array_map('MultiplePostPasswords\Frontend\PagePasswords::specialchars_decode_password', $passwords);

        $cleaned_passwords = array_diff( $passwords, $passwords_to_delete);

        update_post_meta($post_id, Metabox::$value_slug, $cleaned_passwords);
    }

    /**
     * as the passwords are saved encoded, we need to decode them for comparison
     *
     * @param $password
     * @return string
     */
    static function specialchars_decode_password($password){
        return trim (wp_specialchars_decode($password, ENT_QUOTES));
    }

}

