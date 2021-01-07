<?php
    $Step = 1;
    require_once(POWERPRESS_ABSPATH .'/powerpressadmin-auth.class.php');
    $auth = new PowerPressAuth();
    add_thickbox();
    $General = powerpress_get_settings('powerpress_general');
    if (!isset($_REQUEST['_wpnonce'])) {
        powerpress_page_message_add_error(__('Invalid link', 'powerpress'));
        powerpress_page_message_print();
        exit;
    }
    if (wp_verify_nonce($_REQUEST['_wpnonce'], 'powerpress-link-blubrry')) {
        if (isset($_GET['blubrry_create'])) {
            $path = 'create';
            $actType = '&account_type=2';
        } else {
            $path = 'authorize';
            $actType = '';
        }

        if (!isset($_GET['code']) && !isset($_GET['error']) && !isset($_POST['Settings'])) {
            $result = $auth->getTemporaryCredentials();
            // Okay we got it!
            if ($result !== false && !empty($result['temp_client_id']) && !empty($result['temp_client_secret'])) {
                $state = md5(rand(0, 999999) . time());
                update_option('powerpress_temp_client', array('temp_client_id' => $result['temp_client_id'], 'temp_client_secret' => $result['temp_client_secret'], 'state' => $state));
                $from_string = '';
                if (isset($_GET['from'])) {
                    $from_string = "&from=" . $_GET['from'];
                    if ($_GET['from'] == 'powerpressadmin_basic') {
                        $tab_string = isset($_GET['tab']) ? "&tab={$_GET['tab']}" : "";
                        $sidenav_tab_string = isset($_GET['sidenav-tab']) ? "&sidenav-tab={$_GET['sidenav-tab']}" : "";
                        $from_string .= $tab_string;
                        $from_string .= $sidenav_tab_string;
                    }
                }
                $url_string = "admin.php?page={$_GET['page']}&step=blubrrySignin{$from_string}" . (isset($_GET['blubrry_create']) ? '&blubrry_create=true' : '');
                $redirect_uri = add_query_arg('_wpnonce', $_REQUEST['_wpnonce'], admin_url($url_string));
                update_option('powerpress_blubrry_api_redirect_uri', $redirect_uri);
                echo '<script>window.location.href = "' . $auth->getApiUrl() . 'oauth2/' . $path . '?response_type=code&client_id=' . $result['temp_client_id'] . '&state=' . $state . '&redirect_uri=' . urlencode($redirect_uri) . $actType . '";</script>';
                exit;
            }
        } else if (isset($_GET['code']) || isset($_GET['error'])) {
            if (isset($_GET['error']) && $_GET['error'] == 'consent_required') {
                if (isset($_GET['from']) && $_GET['from'] == 'powerpressadmin_basic') {
                    $tab_string = isset($_GET['tab']) ? "&tab={$_GET['tab']}" : "";
                    $sidenav_tab_string = isset($_GET['sidenav-tab']) ? "&sidenav-tab={$_GET['sidenav-tab']}" : "";
                    echo '<script>window.location.href = "' . admin_url("admin.php?page={$_GET['from']}{$tab_string}{$sidenav_tab_string}") . '";</script>';
                    exit;
                } elseif (isset($_GET['from']) && $_GET['from'] == 'new_post') {
                    echo '<script>window.location.href = "' . admin_url('post-new.php') . '";</script>';
                    exit;
                } elseif (isset($_GET['from']) && $_GET['from'] == 'hosting_plugin') {
                    echo '<script>window.location.href = "' . admin_url('admin.php?page=powerpress-site-setup') . '";</script>';
                    exit;
                } else {
                    echo '<script>window.location.href = "' . admin_url("admin.php?page={$_GET['page']}&step=nohost") . '";</script>';
                    exit;
                }
            } elseif (empty($_GET['state']) || empty($_GET['code'])) {
                powerpress_page_message_add_error(__('An error occurred linking your account. Missing parameters.', 'powerpress'));
            }
            //First, check if we're already logged in. If we are, we'll skip the client issuing and get access token
            $creds = get_option('powerpress_creds');
            if (!$creds) {
                $tempClient = get_option('powerpress_temp_client');
                if ($_GET['state'] != $tempClient['state']) {
                    powerpress_page_message_add_error(__('An error occurred linking your account. State does not match.', 'powerpress'));
                    return false;
                }
                $redirectUri = get_option('powerpress_blubrry_api_redirect_uri');

                // Get the client ID for this installation
                $resultClient = $auth->issueClient($_GET['code'], $tempClient['temp_client_id'], $tempClient['temp_client_secret'], $redirectUri);
                if ($resultClient === false || empty($resultClient['client_id']) || empty($resultClient['client_secret'])) {
                    if (!empty($resultTokens['error_description']))
                        powerpress_page_message_add_error($resultTokens['error_description']);
                    else if (!empty($resultTokens['error']))
                        powerpress_page_message_add_error($resultTokens['error']);
                    else
                        powerpress_page_message_add_error(__('Error issuing client:', 'powerpress-network') . ' ' . $auth->GetLastError() . $auth->getDebugInfo());
                    powerpress_page_message_print();
                    exit;
                }

                // Get the access and refresh token for this client
                $resultTokens = $auth->getAccessTokenFromCode($_GET['code'], $resultClient['client_id'], $resultClient['client_secret'], $redirectUri);

                if ($resultTokens === false || empty($resultTokens['access_token']) || empty($resultTokens['refresh_token'])) {
                    if (!empty($resultTokens['error_description']))
                        powerpress_page_message_add_error($resultTokens['error_description']);
                    else if (!empty($resultTokens['error']))
                        powerpress_page_message_add_error($resultTokens['error']);
                    else
                        powerpress_page_message_add_error(__('Error retrieving access token:', 'powerpress-network') . ' ' . $auth->GetLastError());
                    powerpress_page_message_print();
                    exit;
                }

                $props = array();
                $props['code'] = $_GET['code'];
                $props['client_id'] = $resultClient['client_id'];
                $props['client_secret'] = $resultClient['client_secret'];
                $props['access_token'] = $resultTokens['access_token'];
                $props['access_expires'] = (time() + $resultTokens['expires_in'] - 10);
                $props['refresh_token'] = $resultTokens['refresh_token'];
                powerpress_save_settings($props, 'powerpress_creds');

            } else {
                $props = $creds;
            }
            $result = $auth->checkAccountVerified();
            if (isset($result['account_enabled']) && isset($result['account_confirmed'])) {
                if (!$result['account_enabled'] || !$result['account_confirmed']) {
                    $props['account_verified'] = false;
                    powerpress_save_settings($props, 'powerpress_creds');
                    powerpress_check_account_verified_popup(true);
                } else {
                    $props['account_verified'] = true;
                    powerpress_save_settings($props, 'powerpress_creds');
                    $Save = false;
                    $Close = false;
                    $Programs = array();
                    $ProgramHosting = array();
                    $json_data = false;
                    $results_programs = array();
                    $api_url_array = powerpress_get_api_array();
                    $accessToken = powerpress_getAccessToken();

                    $req_url = '/2/service/index.json?cache=' . md5( rand(0, 999) . time() );
                    $req_url .= (defined('POWERPRESS_BLUBRRY_API_QSA') ? '?' . POWERPRESS_BLUBRRY_API_QSA : '');
                    $results_programs = $auth->api($accessToken, $req_url);

                    if (!$results_programs || isset($results_programs['error'])) {
                        powerpress_page_message_add_error(__('Error accessing account: ', 'powerpress') . isset($results_programs['error']) ? $results_programs['error'] : $auth->getLastError());
                    } else {
                        foreach ($results_programs as $null => $row) {
                            $Programs[$row['program_keyword']] = $row['program_title'];
                        }
                    }
                    $from_string = '';
                    if (isset($_GET['from'])) {
                        $from_string = "&from=" . $_GET['from'];
                        if ($_GET['from'] == 'powerpressadmin_basic') {
                            $tab_string = isset($_GET['tab']) ? "&tab={$_GET['tab']}" : "";
                            $sidenav_tab_string = isset($_GET['sidenav-tab']) ? "&sidenav-tab={$_GET['sidenav-tab']}" : "";
                            $from_string .= $tab_string;
                            $from_string .= $sidenav_tab_string;
                        }
                    }
                    wp_enqueue_style('powerpress_onboarding_styles', POWERPRESS_ABSPATH . '/css/onboarding.css'); ?>


                    <div class="pp_container">
                        <h2 class="pp_align-center"><?php echo __('You\'re ready to go!', 'powerpress'); ?></h2>
                        <hr class="pp_align-center"/>
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
                                    <form action="<?php echo add_query_arg('_wpnonce', $_REQUEST['_wpnonce'], admin_url("admin.php?page={$_GET['page']}&step=blubrrySignin{$from_string}")); ?>"
                                          method="post">
                                        <?php
                                        foreach ($Programs as $value => $desc)
                                            echo "<div><button type='submit' name='Settings[blubrry_program_keyword]' value='{$value}' class='pp_button show_button'><span>{$desc}</span></button></div>";

                                        if (isset($_GET['from']) && $_GET['from'] == 'import') { ?>
                                            <div class="pp_form-group" style="text-align: center">
                                                <div class="pp_input-field-login" style="display: inline-block">
                                                    <input type="checkbox" id="blubrry_migrate_option_signin"
                                                           name="Settings[blubrry_migrate]">
                                                    <label for="blubrry_migrate_option_signin"><?php echo __('Migrate media from imported feed (only possible with a hosting account)', 'powerpress'); ?></label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                $props['account_verified'] = false;
                powerpress_save_settings($props, 'powerpress_creds');
                powerpress_page_message_add_error(__('Error verifying account: ', 'powerpress') . isset($result['error']) ? $result['error'] : $auth->getLastError());
                powerpress_page_message_print();
            }
            //var_dump($result);
            //exit;

        } else if (isset($_POST['Settings'])) {
            $SaveSettings = $_POST['Settings'];
            $SaveSettings = powerpress_stripslashes($SaveSettings);
            $Save = false;
            $Close = false;
            $Programs = array();
            $ProgramHosting = array();
            $json_data = false;
            $results_programs = array();
            $api_url_array = powerpress_get_api_array();
            $accessToken = powerpress_getAccessToken();
            $req_url = '/2/service/index.json?cache=' . md5( rand(0, 999) . time() );
            $req_url .= (defined('POWERPRESS_BLUBRRY_API_QSA') ? '?' . POWERPRESS_BLUBRRY_API_QSA : '');
            $results = $auth->api($accessToken, $req_url);
            if (isset($results['error'])) {
                $Error = $results['error'];
                if (strstr($Error, __('currently not available', 'powerpress'))) {
                    $Error = __('Unable to find podcasts for this account.', 'powerpress');
                    $Error .= '<br /><span style="font-weight: normal; font-size: 12px;">';
                    $Error .= 'Verify that the email address you enter here matches the email address you used when you listed your podcast on blubrry.com.</span>';
                } else if (preg_match('/No programs found.*media hosting/i', $results['error'])) {
                    $Error .= '<br/><span style="font-weight: normal; font-size: 12px;">';
                    $Error .= 'Service may take a few minutes to activate.</span>';
                }
            } else if (!is_array($results)) {
                $Error = $json_data;
            } else {
                // Get all the programs for this user...
                foreach ($results as $null => $row) {
                    $Programs[$row['program_keyword']] = $row['program_title'];
                    if ($row['hosting'] === true || $row['hosting'] == 'true')
                        $ProgramHosting[$row['program_keyword']] = true;
                    else
                        $ProgramHosting[$row['program_keyword']] = false;
                }

                if (count($Programs) > 0) {
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


                    if (!empty($SaveSettings['blubrry_program_keyword'])) {
                        powerpress_add_blubrry_redirect($SaveSettings['blubrry_program_keyword']);
                        $SaveSettings['blubrry_hosting'] = $ProgramHosting[$SaveSettings['blubrry_program_keyword']];
                        if (!is_bool($SaveSettings['blubrry_hosting'])) {
                            if ($SaveSettings['blubrry_hosting'] === 'false' || empty($SaveSettings['blubrry_hosting']))
                                $SaveSettings['blubrry_hosting'] = false;
                        }

                        $Save = true;
                        $Close = true;
                    } else if (isset($SaveSettings['blubrry_program_keyword'])) // Present but empty
                    {
                        $Error = __('You must select a program to continue.', 'powerpress');
                    } else if (count($Programs) == 1) {
                        foreach ($Programs as $keyword => $title) {
                            break;
                        }

                        $SaveSettings['blubrry_program_keyword'] = $keyword;
                        $SaveSettings['blubrry_hosting'] = $ProgramHosting[$keyword];
                        if (!is_bool($SaveSettings['blubrry_hosting'])) {
                            if ($SaveSettings['blubrry_hosting'] === 'false' || empty($SaveSettings['blubrry_hosting']))
                                $SaveSettings['blubrry_hosting'] = false;
                        }
                        powerpress_add_blubrry_redirect($keyword);
                        $Close = true;
                        $Save = true;
                    } else {
                        $Step = 2;
                        $Settings['blubrry_username'] = $SaveSettings['blubrry_username'];
                    }
                } else {
                    $Error = __('No podcasts for this account are listed on blubrry.com.', 'powerpress');
                }
            }

            if (isset($Error)) {
                $Error .= '<p style="text-align: center;"><a href="http://create.blubrry.com/resources/powerpress/powerpress-settings/services-stats/" target="_blank">' . __('Click Here For Help', 'powerpress') . '</a></p>';
            }
            if ($Save) {
                powerpress_save_settings($SaveSettings);
                if (isset($_GET['from']) && $_GET['from'] == 'powerpressadmin_basic') {
                    $tab_string = isset($_GET['tab']) ? "&tab={$_GET['tab']}" : "";
                    $sidenav_tab_string = isset($_GET['sidenav-tab']) ? "&sidenav-tab={$_GET['sidenav-tab']}" : "";
                    echo '<script>window.location.href = "' . admin_url("admin.php?page={$_GET['from']}{$tab_string}{$sidenav_tab_string}") . '";</script>';
                } elseif (isset($_GET['from']) && $_GET['from'] == 'new_post') {
                    echo '<script>window.location.href = "' . admin_url('post-new.php') . '";</script>';
                } elseif (isset($_GET['from']) && $_GET['from'] == 'hosting_plugin') {
                    echo '<script>window.location.href = "' . admin_url('admin.php?page=powerpress-site-setup') . '";</script>';
                }
                echo '<script>window.location.href = "' . admin_url("admin.php?page={$_GET['page']}&step=createEpisode$migrate_string") . '";</script>';
            }
            // Clear cached statistics
            delete_option('powerpress_stats');

            if (isset($Error))
                powerpress_page_message_add_notice($Error, 'inline', false);


        }
    } else {
        powerpress_page_message_add_error(__('Invalid link', 'powerpress'));
        powerpress_page_message_print();
        exit;
    }
?>