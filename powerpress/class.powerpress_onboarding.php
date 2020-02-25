<?php
class PowerpressOnboarding
{
    function __construct()
    {
        if (defined('WP_DEBUG')) {
            if (WP_DEBUG) {
                wp_enqueue_style('powerpress_onboarding_styles', plugin_dir_url(__FILE__) . 'css/onboarding.css');
            } else {
                wp_enqueue_style('powerpress_onboarding_styles', plugin_dir_url(__FILE__) . 'css/onboarding.min.css');
            }
        } else {
            wp_enqueue_style('powerpress_onboarding_styles', plugin_dir_url(__FILE__) . 'css/onboarding.min.css');
        }
    }

    public function router($GET) {
        if(empty($GET['step'])) {
            include 'views/onboarding/start.php';
        }
        else {
            switch ($GET['step']) {
                case 'nohost':
                    include 'views/onboarding/nohost.php';
                    break;
                case 'blubrrySignin':
                    include 'views/onboarding/blubrry_signin.php';
                    break;
                case 'showBasics':
                    include 'views/onboarding/show_basics.php';
                    break;
                case 'createEpisode':
                    include 'views/onboarding/createepisode.php';
                    break;
                case 'wantStats':
                    include 'views/onboarding/want_stats.php';
                    break;
                default:
                    include 'views/onboarding/start.php';
                    break;
            }
        }
    }
}
