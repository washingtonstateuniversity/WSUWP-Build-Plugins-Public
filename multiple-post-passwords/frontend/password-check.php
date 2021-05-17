<?php

namespace MultiplePostPasswords\Frontend;

/**
 * Frontend logic for enabling multiple passwords on protected pages
 *
 * @package multiple-post-passwords
 * @since 1.0.0
 */
class PasswordCheck {

    /**
     * The single instance of the class.
     *
     * @var self
     * @since  1.0.0
     */
    private static $_instance = null;

    private $cache = [];


    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    private  function __construct() {

        add_filter( 'post_password_required', [$this, 'post_password_required_filter'], 20, 2 );

    }

    /**
     * Set password required to false if any password fits
     *
     * @since 1.0.0
     * @see adapted from original wp function post_password_required()
     *
     * @param  \WP_Post $post Post to check for
     * @return  bool false if a password is not required or the correct password cookie is present, true otherwise.
     */
    function post_password_required_filter ( $required, $post ) {

        // do noting if visible anyway
        if ( !$required ) return $required;

        // no user pw set -> no sense to check anything
        if ( !isset( $_COOKIE[ 'wp-postpass_' . COOKIEHASH ] ) ) return $required;

        // as this function gets called multiple (four) times in one page request
        // and the $hasher->CheckPassword function is quite performance hungry
        // we check the cache to speed things up
        if (!empty($this->cache[$post->ID])) return $this->cache[$post->ID];

        $passwords = PagePasswords::get_passwords($post->ID);

        // do nothing if no additional passwords defined
        if ( empty($passwords) ) return $required;

        require_once ABSPATH . WPINC . '/class-phpass.php';
        $hasher = new \PasswordHash( 8, true );

        $hash = wp_unslash( $_COOKIE[ 'wp-postpass_' . COOKIEHASH ] );
        if ( 0 !== strpos( $hash, '$P$B' ) ) {

            $required = true;

        } else {

            $required = true;

            foreach ($passwords as $password) {

                $password = trim (wp_specialchars_decode($password, ENT_QUOTES));

                if ( !empty(trim($password))) {

                    if(get_option('mpp_use_alternative_password_check') ) {

                        // faster way of checking
                        $password_fits = AlternativePasswordCheck::check_password($password, $post->ID);

                    } else {
                        // WordPress way
                        $password_fits =  $hasher->CheckPassword( trim($password), $hash );
                    }

                    if($password_fits){

                        $required = false;

                        $this->cache[$post->ID] = $required;

                        PagePasswords::maybe_set_password_used($post->ID, $password);

                        return $required;
                    }
                }
            }
        }

        $this->cache[$post->ID] = $required;

        return $required;

    }
}

