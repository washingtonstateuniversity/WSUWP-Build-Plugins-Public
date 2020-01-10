<?php
    $Step = 1;
    if(isset($_GET['import'])) {
        powerpress_page_message_add_notice(__('It looks like your podcast is already hosted by Blubrry, sign in to your Blubrry account to get see the full power of Powerpress.', 'powerpress'), 'inline', false);
    }
    if(isset($_POST['Settings'])) {
        $Password = $_POST['Password'];
        $SaveSettings = $_POST['Settings'];
        $Password = powerpress_stripslashes($Password);
        $SaveSettings = powerpress_stripslashes($SaveSettings);

        $Save = false;
        $Close = false;
        $Programs = array();
        $ProgramHosting = array();
        $Error = "";
        // Anytime we change the password we need to test it...
        $auth = base64_encode( $SaveSettings['blubrry_username'] . ':' . $Password );
        $json_data = false;
        $api_url_array = powerpress_get_api_array();
        foreach( $api_url_array as $index => $api_url )
        {
            $req_url = sprintf('%s/service/index.json', rtrim($api_url, '/') );
            $req_url .= (defined('POWERPRESS_BLUBRRY_API_QSA')?'?'. POWERPRESS_BLUBRRY_API_QSA:'');
            $json_data = powerpress_remote_fopen($req_url, $auth);
            if( !$json_data && $api_url == 'https://api.blubrry.com/' ) { // Lets force cURL and see if that helps...
                $json_data = powerpress_remote_fopen($req_url, $auth, array(), 15, false, true);
            }
            if( $json_data != false )
                break;
        }
        if( $json_data )
        {
            $results =  powerpress_json_decode($json_data);

            if( isset($results['error']) )
            {
                $Error = $results['error'];
                if( strstr($Error, __('currently not available', 'powerpress') ) )
                {
                    $Error = __('Unable to find podcasts for this account.', 'powerpress');
                    $Error .= '<br /><span style="font-weight: normal; font-size: 12px;">';
                    $Error .= 'Verify that the email address you enter here matches the email address you used when you listed your podcast on blubrry.com.</span>';
                }
                else if( preg_match('/No programs found.*media hosting/i', $results['error']) )
                {
                    $Error .= '<br/><span style="font-weight: normal; font-size: 12px;">';
                    $Error .= 'Service may take a few minutes to activate.</span>';
                }
            }
            else if( !is_array($results) )
            {
                $Error = $json_data;
            }
            else
            {
                // Get all the programs for this user...
                foreach( $results as $null => $row )
                {
                    $Programs[ $row['program_keyword'] ] = $row['program_title'];
                    if( $row['hosting'] === true || $row['hosting'] == 'true' )
                        $ProgramHosting[ $row['program_keyword'] ] = true;
                    else
                        $ProgramHosting[ $row['program_keyword'] ] = false;
                }

                if( count($Programs) > 0 )
                {
                    global $wpdb;
                    $migrate_string = "";

                    if (isset($SaveSettings['blubrry_migrate']) && $SaveSettings['blubrry_migrate']) {
                        $migrate_string = "&migrate=true";
                        $query = "SELECT meta_id, post_id, meta_key, meta_value FROM {$wpdb->postmeta} WHERE meta_key LIKE \"%enclosure\"";
                        $results_data = $wpdb->get_results($query, ARRAY_A);
                        if ($results_data) {
                            if (empty($GLOBALS['pp_migrate_media_urls']))
                                $GLOBALS['pp_migrate_media_urls'] = array();
                            foreach ($results_data as $index => $row) {
                                list($url) = @explode("\n", $row['meta_value'], 2);
                                $url = trim($url);
                                $post_id = $row['post_id'];
                                $GLOBALS['pp_migrate_media_urls'][$post_id] = $url;
                            }
                            require_once(POWERPRESS_ABSPATH . '/powerpressadmin-migrate.php');

                            $update_option = true;
                            $QueuedFiles = get_option('powerpress_migrate_queued');
                            if (!is_array($QueuedFiles)) {
                                $QueuedFiles = array();
                                $update_option = false;
                            }

                            $add_urls = '';
                            foreach ($GLOBALS['pp_migrate_media_urls'] as $meta_id => $url) {
                                if (empty($QueuedFiles[$meta_id])) { // Add to the array if not already added
                                    $QueuedFiles[$meta_id] = $url;
                                    if (!empty($add_urls)) {
                                        $add_urls .= "\n";
                                    }
                                    $add_urls .= $url;
                                }
                            }
                            powepress_admin_migrate_add_urls($add_urls);
                        }
                    }

                    $SaveSettings['blubrry_auth'] = $auth;

                    if( !empty($SaveSettings['blubrry_program_keyword']) )
                    {
                        powerpress_add_blubrry_redirect($SaveSettings['blubrry_program_keyword']);
                        $SaveSettings['blubrry_hosting'] = $ProgramHosting[ $SaveSettings['blubrry_program_keyword'] ];
                        if( !is_bool($SaveSettings['blubrry_hosting']) )
                        {
                            if( $SaveSettings['blubrry_hosting'] === 'false' || empty($SaveSettings['blubrry_hosting']) )
                                $SaveSettings['blubrry_hosting'] = false;
                        }

                        $Save = true;
                        $Close = true;
                    }
                    else if( isset($SaveSettings['blubrry_program_keyword']) ) // Present but empty
                    {
                        $Error = __('You must select a program to continue.', 'powerpress');
                    }
                    else if( count($Programs) == 1 )
                    {
                        foreach( $Programs as $keyword => $title ) {
                            break;
                        }

                        $SaveSettings['blubrry_program_keyword'] = $keyword;
                        $SaveSettings['blubrry_hosting'] = $ProgramHosting[ $keyword ];
                        if( !is_bool($SaveSettings['blubrry_hosting']) )
                        {
                            if( $SaveSettings['blubrry_hosting'] === 'false' || empty($SaveSettings['blubrry_hosting']) )
                                $SaveSettings['blubrry_hosting'] = false;
                        }
                        powerpress_add_blubrry_redirect($keyword);
                        $Close = true;
                        $Save = true;
                    }
                    else
                    {
                        $Step = 2;
                        $Settings['blubrry_username'] = $SaveSettings['blubrry_username'];
                    }
                }
                else
                {
                    $Error = __('No podcasts for this account are listed on blubrry.com.', 'powerpress');
                }
            }
        }
        else
        {
            global $g_powerpress_remote_error, $g_powerpress_remote_errorno;
            //$Error = '<h3>'. __('Error', 'powerpress') .'</h3>';
            if( !empty($g_powerpress_remote_errorno) && $g_powerpress_remote_errorno == 401 )
                $Error .= '<p>'. __('Incorrect sign-in email address or password.', 'powerpress') .'</p><p>'. __('Verify your account settings then try again.', 'powerpress') .'</p>';
            else if( !empty($g_powerpress_remote_error) )
                $Error .= '<p>'.$g_powerpress_remote_error .'</p>';
            else
                $Error .= '<p>'.__('Authentication failed.', 'powerpress') .'</p>';
        }

        if( $Error )
        {
            $Error .= '<p style="text-align: center;"><a href="http://create.blubrry.com/resources/powerpress/powerpress-settings/services-stats/" target="_blank">'. __('Click Here For Help','powerpress') .'</a></p>';
        }
        if( $Save ) {
            powerpress_save_settings($SaveSettings);
            echo '<script>window.location.href = "' . admin_url("admin.php?page={$_GET['page']}&step=createEpisode$migrate_string") . '";</script>';
        }
        // Clear cached statistics
        delete_option('powerpress_stats');

        if( $Error )
            powerpress_page_message_add_notice( $Error, 'inline', false );
    }
?>
<?php if($Step == 1) { ?>
<div class="wrap">
    <div class="pp_container">
        <h2 class="pp_align-center"><?php echo __('Connect to your Blubrry Account', 'powerpress'); ?></h2>
        <img class="image_center" src="<?php echo powerpress_get_root_url(); ?>images/onboarding/blubrrysignin.png">
        <div class="pp_container" style="padding-top: 0;margin-top:0;">
            <?php powerpress_page_message_print() ?>
            <div class="pp_flex-grid" style="align-items: center;justify-content: center;">
                <div class="pp_col" style="text-align: center;">
                    <form action="" method="post">
                            <div class="pp_form-group" style="text-align: center">
                                <div class="pp_input-field-login"style="display: inline-block">
                                    <input type="text" id="blubrry_username_signin" name="Settings[blubrry_username]" class="pp_outlined" required>
                                    <label for="blubrry_username_signin"><?php echo __('Username', 'powerpress'); ?></label>
                                </div>
                            </div>
                            <div class="pp_form-group" style="text-align: center">
                                <div class="pp_input-field-login" style="display: inline-block">
                                    <input type="password" id="blubrry_password_signin" name="Password" class="pp_outlined" required>
                                    <label for="blubrry_password_signin"><?php echo __('Password', 'powerpress'); ?></label>
                                </div>
                            </div>
                        <?php if(isset($_GET['from']) && $_GET['from'] == 'import') { ?>
                            <div class="pp_form-group" style="text-align: center">
                                <div class="pp_input-field-login" style="display: inline-block">
                                    <input type="checkbox" id="blubrry_migrate_option_signin" name="Settings[blubrry_migrate]">
                                    <label for="blubrry_migrate_option_signin"><?php echo __('Migrate media from imported feed (only possible with a hosting account)', 'powerpress'); ?></label>
                                </div>
                            </div>
                        <?php } ?>
                            <button type="submit" name="signin" class="pp_button"><span><?php echo __('Sign in', 'powerpress'); ?></span></button>
                            <div style="margin-top: 15px">
                                <a href="https://www.blubrry.com/resetpassword.php"><?php echo __('Forgot your password?', 'powerpress');?></a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        <p style="text-align: center"><?php echo __('Don\'t have an account?', 'powerpress'); ?> <a href="https://www.blubrry.com/createaccount.php" target="_blank"><?php echo __('Create one now', 'powerpress') ?></a></p>
    </div>

    <?php } else if($Step == 2) {    ?>
    <div class="pp_container">
        <h2 class="pp_align-center"><?php echo __('You\'re ready to go!', 'powerpress'); ?></h2>
        <p class="pp_align-center"><?php echo __('Signed in as ', 'powerpress'); ?><a href="https://publish.blubrry.com/account/" target="_blank"><?php echo $Settings['blubrry_username'] ?></a></p>
        <hr  class="pp_align-center" />
        <p class="pp_align-center"><?php echo __('You can now now able to upload episodes from within WordPress to blubrry, view basic stats from the wordpress dashboard.', 'powerpress'); ?></p>
        <p class="pp_align-center"><?php echo __('If this is the wrong Blubrry account,  visit settings to unlink this account.', 'powerpress'); ?></p>
    </div>

    <div class="pp_container">
        <?php powerpress_page_message_print() ?>
        <h2 class="pp_align-center"><?php echo __('Select your default show', 'powerpress'); ?></h2>
        <p class="pp_align-center"><?php echo __('You have multiple shows in your account. Please select which one you want to be your default show for this website.', 'powerpress'); ?></p>
        <div class="pp_flex-grid">
            <div class="pp_col">
                <div class="pp_button-container">
                    <form action="" method="post">
                        <input type="hidden" name="Settings[blubrry_username]" value="<?php echo esc_attr($Settings['blubrry_username']); ?>" />
                        <input type="hidden" name="Password" value="<?php echo esc_attr($Password); ?>" />
                        <?php
                        foreach( $Programs as $value => $desc )
                            echo "<div><button type='submit' name='Settings[blubrry_program_keyword]' value='{$value}' class='pp_button show_button'><span>{$desc}</span></button></div>";
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>