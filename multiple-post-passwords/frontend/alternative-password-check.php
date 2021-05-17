<?php

namespace MultiplePostPasswords\Frontend;


/**
 * Alternative Password Check which is much quicker when using alot of passwords for one post
 *
 * @package multiple-post-passwords
 * @since 1.1.0
 */
class AlternativePasswordCheck
{

    /**
     * The single instance of the class.
     *
     * @var self
     * @since  1.1.0
     */
    private static $_instance = null;


    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
        if(!get_option('mpp_use_alternative_password_check') ) {
            return;
        }

        // we use this hook to fetch the sent password
        add_filter('post_password_expires', [$this, 'fetch_sent_password']);

        // we need to start a session to make this work
        add_action('init', [$this, 'start_session']);

    }

    function start_session(){

        if (!session_id()) {
            session_start();
        }
    }


    /**
     * we save the sent password in the session for later use
     *
     * @param $expire
     * @return mixed
     */
    function fetch_sent_password($expire){

        if ( !empty($_POST['post_password']) ) {
            if (!session_id()) {
                session_start();
            }
            $_SESSION['mpp_alternative_password'] = $_POST['post_password'];
        }

        // we don´t want to change the cookie expire, we use this hook for something different.
        // But we need to return it to avoid breaking something
        return $expire;
    }


    /**
     * we compare the passwords
     * post_id is ignored currently, which is the standard WP behaviour
     *
     * @param $post_password string password of the post to check
     * @param $post_id  int currently not used
     * @return bool     if password fits
     */
    static function check_password($post_password, $post_id){

        $user_password =  wp_unslash($_SESSION['mpp_alternative_password']);

        return ($post_password == $user_password);

    }

}

