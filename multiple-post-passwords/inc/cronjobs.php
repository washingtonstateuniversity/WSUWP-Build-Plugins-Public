<?php

namespace MultiplePostPasswords;

use MultiplePostPasswords\Frontend\PagePasswords;

/**
 * setup cronjobs
 *
 * @since 1.1.0
 */
class Cronjobs
{

    /**
     * The single instance of the class.
     *
     * @var self
     */
    private static $_instance = null;

    private static $schedule_name = 'mpp_30_minutes';
    private static $hook_name = 'mpp_check_for_used_passwords_deletion_cron_hook';

    /**
     * @return Cronjobs
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Cronjobs constructor.
     */
    private function __construct()
    {

        add_filter('cron_schedules', array($this, 'add_cron_interval'));

        add_action(self::$hook_name, array($this, 'check_for_used_passwords_deletion'));

        if ( ! wp_next_scheduled( self::$hook_name ) ) {
            wp_schedule_event( time(), self::$schedule_name, self::$hook_name );
        }

    }

    /**
     * @param $schedules
     * @return mixed
     */
    function add_cron_interval($schedules)
    {

        $schedules[self::$schedule_name] = array(
            'interval' => 30 * 60,
            'display' => esc_html__('Every 30 minutes'),
        );

        return $schedules;
    }

    /**
     * delete passwords if expired
     */
    function check_for_used_passwords_deletion(){

        $used_passwords = get_option('mpp_used_passwords', array());

        $password_expiry_hours = get_option('mpp_password_expire_hours', PagePasswords::DEFAULT_PASSWORD_EXPIRE_HOURS);

        $passwords_to_delete = array();
        $update_required = false;
        foreach ($used_passwords as $key => $used_password){

            if(time() > $used_password['time_used'] + $password_expiry_hours*60*60){
                // save passwords to delete by post_id in array to be deleted later in the function
                if(!isset($passwords_to_delete[$used_password['post_id']] )){
                    $passwords_to_delete[$used_password['post_id']]  = array();
                }
                $passwords_to_delete[$used_password['post_id']][] = $used_password['password'];
                unset($used_passwords[$key]);
                $update_required = true;
            }
        }


        // walk through array to delete passwords from post meta
        foreach($passwords_to_delete as $post_id => $passwords){
            PagePasswords::delete_passwords($post_id, $passwords);
        }

        if($update_required){
            // delete passwords from used_passwords
            update_option('mpp_used_passwords', $used_passwords);

            $this->maybe_send_email_notification($passwords_to_delete);
        }
    }

    /**
     * Send Email notification if activated in settings
     *
     * @param $passwords_to_delete array
     */
    function maybe_send_email_notification($passwords_to_delete){
        // send email notification
        if(!empty(get_option('mpp_used_pw_deletion_notification_email'))){
            $message = __('Passwords deleted!', 'multiple-post-passwords').PHP_EOL.PHP_EOL;
            foreach($passwords_to_delete as $post_id => $passwords){
                $message.= 'Post '.$post_id.': '.implode(', ',$passwords).PHP_EOL;
            }
            wp_mail(
                get_option('mpp_used_pw_deletion_notification_email'),
                __('Multiple Password Deletion Notification', 'multiple-post-passwords'),
                $message
            );
        }
    }
}