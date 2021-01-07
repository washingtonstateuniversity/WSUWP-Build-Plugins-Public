<?php
/***
 * @package PowerPressNetwork
 */



if ( !function_exists('add_action') )
    die("access denied.");

require_once(dirname(__FILE__).'/api-data-transfer-bus.class.php');
require_once(dirname(__FILE__).'/powerpressadmin-auth.class.php');

class PowerPressNetwork
{
    private $display;
    private $apiBus;
    private $parent_slug;

    function __construct($parent_slug)
    {
        $pluginList = get_option( 'active_plugins' );
        if (in_array('powerpress/powerpress.php',$pluginList)) {
            $this->parent_slug = $parent_slug;
            $this->init();
            $this->apiBus = new PowerpressNetworkDataBus();
            add_action('admin_menu', array($this, 'checkUpdateProgram'));
        }
        else {
            if (in_array('powerpress-network/powerpress-network.php',$pluginList)) {
                function sample_admin_notice__success() {
                    ?>
                    <div class="notice notice-error">
                        <p><?php _e( 'WARNING: PowerPress Network will not work with PowerPress not active.' ); ?></p>
                    </div>
                    <?php
                }
                add_action( 'admin_notices', 'sample_admin_notice__success' );
            }
            else {
                die("PowerPress Network plugin not activated - must first activate PowerPress.");
            }
        }
    }

    function init()
    {

        if (!is_admin()) {
            require_once(dirname(__FILE__) . '/shortcodes/ShortCode.php');
            require_once(dirname(__FILE__) . '/shortcodes/Application.php');
            require_once(dirname(__FILE__) . '/shortcodes/ListPreview.php');
            require_once(dirname(__FILE__) . '/shortcodes/List.php');
            require_once(dirname(__FILE__) . '/shortcodes/Program.php');
            require_once(dirname(__FILE__) . '/shortcodes/Grid.php');

        }

        if (is_admin() && isset($_GET['page']) && $_GET['page'] == 'powerpress-network') {
            $key = $_GET['key'];

            switch ($key) {
                case 'programs':
                    include(dirname(__FILE__) . '/admin/programs.php');
                    break;
                case 'lists':
                    include(dirname(__FILE__) . '/admin/lists.php');
                    break;
                case 'link':
                    include(dirname(__FILE__) . '/admin/link.php');
                    break;
                case 'index':
                    include(dirname(__FILE__) . '/admin/index.php');
                    break;
                case 'base':
                    include(dirname(__FILE__) . '/admin/base.php');
                    break;
                case 'applications':
                    include(dirname(__FILE__) . '/admin/applications.php');
                    break;
                default:
                    include(dirname(__FILE__) . '/powerpress-network.php');

            }

        }

    }
	
	function getAccessToken()
	{
		// Look at the creds and use the latest access token, if its not the latest refresh it...
		$creds = get_option('powerpress_network_creds');
		if( !empty($creds['access_token']) && !empty($creds['access_expires']) && $creds['access_expires'] > time() ) { // If access token did not expire
			return $creds['access_token'];
		}
		
		if( !empty($creds['refresh_token']) && !empty($creds['client_id']) && !empty($creds['client_secret']) ) {
			
			// Create new access token with refresh token here...
			$auth = new PowerPressAuth();
			$resultTokens = $auth->getAccessTokenFromRefreshToken($creds['refresh_token'], $creds['client_id'], $creds['client_secret']);
			
			if( !empty($resultTokens['access_token']) && !empty($resultTokens['expires_in']) ) {
				powerpress_save_settings( array('access_token'=>$resultTokens['access_token'], 'access_expires'=>( time() + $resultTokens['expires_in'] - 10 ) ), 'powerpress_network_creds');
				
				return $resultTokens['access_token'];
			}
		}
		
		// If we failed to get credentials, return false
		return false;
	}

    function requestAPI($requestUrl, $auth = false, $post = false)
    {
		$accessToken = '';	
		if( $auth ) {
			$accessToken = $this->getAccessToken();
			if( empty($accessToken) )
				return false;
		}
		
		// Equivelant command line argument to run command
		//mail('c', 'dd', "curl ". (is_array($post) ? '-d "'.implode("&", $post) .'" ' : '') ."-H \"Authorization: Bearer $accessToken\" \"$requestUrl\"");
		$auth = new PowerPressAuth();
		$response = $auth->api($accessToken, $requestUrl, $post);
		
		if( $response === false ) {
            powerpress_page_message_add_error( __('Error: ' . $auth->getLastError(), 'powerpress') );
        }
		return $response;
    }

    static function getHTML($filename, $props, $networkInfo, $accountInfo)
    {
        if (is_file(dirname(__FILE__) . '/shortcodes/views/' . $filename)) {
            ob_start();
            include(dirname(__FILE__) . '/shortcodes/views/' . $filename);
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        } else if (is_file(dirname(__FILE__) . '/admin/' . $filename)) {
            ob_start();
            include(dirname(__FILE__) . '/admin/' . $filename);
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        } else {
            return "<div><strong>View for $filename unavailable.</strong></div>";
        }
    }

    function action_wp_enqueue_scripts()
    {
        if (is_admin()) {
            wp_register_style('powerpress-network', $this::powerpress_network_plugin_url() . 'css/style.css');
            wp_enqueue_style('powerpress-network');
            wp_enqueue_script('powerpress-network', $this::powerpress_network_plugin_url() . 'js/admin.js', array('jquery'));
        }
    }

    static function include_script($handler, $src)
    {
        wp_register_script($handler, plugins_url($src, __FILE__));
        wp_enqueue_script($handler);
    }

    static function include_style($handler, $src)
    {
        wp_register_style($handler, plugins_url($src, __FILE__));
        wp_enqueue_style($handler);
    }

    function wpse_load_plugin_css()
    {
        $plugin_url = plugin_dir_url(__FILE__);
        wp_enqueue_style('style', $plugin_url . 'css/stylesheet.css');
        wp_enqueue_style('style', $plugin_url . 'css/blueprint.css');
    }

    static function powerpress_network_plugin_url()
    {
        $local_path = __FILE__;
        if (DIRECTORY_SEPARATOR == '\\') { // Win32 fix
            $local_path = basename(dirname(__FILE__)) . '/' . basename(__FILE__);

            if (strpos(__FILE__, 'C:\\') === 0 && strstr($local_path, 'mu-plugins')) {
                $local_path = __FILE__;
                $local_path = substr($local_path, 2);
                $local_path = str_replace('\\', '/', $local_path);
            }

            if (strstr(__FILE__, 'mu-plugins')) {
                // mu-plugins URL!
                return content_url() . '/mu-plugins/' . dirname($local_path) . '/';
            }
        }

        $plugin_url = plugins_url('', $local_path);
        return $plugin_url . '/';
    }

    public function network_plugin_setup_menu(){
        //$parent_slug, __('PowerPress Network', 'powerpress'), __('PowerPress Network', 'powerpress'), POWERPRESS_CAPABILITY_EDIT_PAGES, 'network-plugin', 'powerpress_admin_page_network_plugin');
        add_submenu_page( $this->parent_slug, 'Network', 'manage_options', 'network-plugin', array($this, 'display_plugin') );
    }

    public function setDisplay()
    {
        $this->display = $this->action_admin_init();
    }
    static function createPage()
    {
        $originalMap = get_option('powerpress_network_map');
        $map = $originalMap;
        $pageCreated = false;
        $target = null;
        $postID = null;
        if ($_POST['target'] == "Program"){
            $target = "p-".$_POST['targetId'];
        } else if ($_POST['target'] == "List") {
            $target = "l-".$_POST['targetId'];
        }
        //Check if the page for desired program or list has been already created
        if (isset($map[$target])){
            $postID = $map[$target];
            if (get_post_status($postID) == 'publish'){
                $pageCreated = true;
            }
        }

        if (!$pageCreated){
            global $user_ID;
            $page['post_type'] = 'page';
            $page['post_content'] = $_POST['content'];
            $page['post_parent'] = 0;
            $page['post_author'] = $user_ID;
            $page['post_status'] = 'publish';
            $page['post_title'] = __($_POST['pageTitle'], 'powerpress');
            $postID = wp_insert_post($page);
            if ($postID != 0){
                $map[$target] = $postID;
                if($originalMap === null) {
                    add_option('powerpress_network_map', $map);
                } else{
                    update_option('powerpress_network_map', $map);
                }
            }
        }
        return $postID;
    }

    private function handlePageAction($createUrl)
    {
        $option = get_option('powerpress_network_map');
        $postID = 0;
        if (isset($_POST['pageAction']) && $_POST['pageAction'] == 'unlink'){
            if ($_POST['target'] == 'List') {
                unset($option['l-' . $_POST['targetId']]);
                $this->removeOption('link_page_list');
            } else if ($_POST['target'] == 'Program') {
                unset($option['p-' . $_POST['targetId']]);
                $this->removeOption('link_page_program');
            }
            update_option ('powerpress_network_map', $option);
        } else {
            if (empty($_POST['pageID'])) {
                $postID = $this->createPage();
            } else{
                $postID = $_POST['pageID'];
                if ($_POST['target'] == 'List') {
                    $option['l-' . $_POST['targetId']] = $postID;
                } else if ($_POST['target'] == 'Program') {
                    $option['p-' . $_POST['targetId']] = $postID;
                }
                $option[] = $postID;
                update_option('powerpress_network_map', $option);
            }
            if ($postID != 0) {
                if ($_POST['target'] == 'List') {
                    $option['l-' . $_POST['targetId']] = $postID;
                    $this->insertOption('link_page_list', get_permalink($postID));
                } else if ($_POST['target'] == 'Program') {
                    $option['p-' . $_POST['targetId']] = $postID;
                    $this->insertOption('link_page_program', get_permalink($postID));
                }
            }
        }
        if ($postID!= 0 && !(isset($_POST['redirectUrl']) && $_POST['redirectUrl'] == 'false')) {
            header('location: ' . $createUrl . 'wp-admin/post.php?post=' . $postID . '&action=edit');
            exit;
        }
    }

    private function handleCodeReturn($apiUrl, $createUrl, &$creds)
    {
		if( empty($_GET['state']) || empty($_GET['code']) ) {
			powerpress_page_message_add_error( __('An error occurred linking your account. Missing parameters.', 'powerpress-network') );
			return false;
		}
		
		$tempClient = get_option('powerpress_network_temp_client');
		if( $_GET['state'] != $tempClient['state'] ) {
			powerpress_page_message_add_error( __('An error occurred linking your account. State does not match.', 'powerpress-network') );
			return false;
		} 
		$redirectUri = admin_url('admin.php?page=network-plugin');
		$auth = new PowerPressAuth();
		
		// Get the client ID for this installation
		$resultClient = $auth->issueClient($_GET['code'], $tempClient['temp_client_id'], $tempClient['temp_client_secret'], $redirectUri);
		if( $resultClient === false || empty($resultClient['client_id']) || empty($resultClient['client_secret']) ) {
			if( !empty($resultTokens['error_description']) )
				powerpress_page_message_add_error( $resultTokens['error_description'] );
			else if( !empty($resultTokens['error']) )
					powerpress_page_message_add_error( $resultTokens['error'] );
			else
				powerpress_page_message_add_error( __('Error issuing client:','powerpress-network') .' '.$auth->GetLastError() . $auth->getDebugInfo() );
			return false;
		}
		
		// Get the access and refresh token for this client
		$resultTokens = $auth->getAccessTokenFromCode( $_GET['code'], $resultClient['client_id'], $resultClient['client_secret'], $redirectUri);
		if( $resultTokens === false || empty($resultTokens['access_token']) || empty($resultTokens['refresh_token']) ) {
			if( !empty($resultTokens['error_description']) )
				powerpress_page_message_add_error( $resultTokens['error_description'] );
			else if( !empty($resultTokens['error']) )
					powerpress_page_message_add_error( $resultTokens['error'] );
			else
				powerpress_page_message_add_error( __('Error retrieving access token:','powerpress-network') .' '.$auth->GetLastError() );
			return false;
		}
		
		$props = array();
		$props['code'] = $_GET['code'];
		$props['client_id'] = $resultClient['client_id'];
		$props['client_secret'] = $resultClient['client_secret'];
		$props['access_token'] = $resultTokens['access_token'];
		$props['access_expires'] = ( time() + $resultTokens['expires_in'] - 10 );
		$props['refresh_token'] = $resultTokens['refresh_token'];
		////update_option('network_general', $props);
		powerpress_save_settings( $props, 'powerpress_network_creds');
		
		powerpress_page_message_add_notice( __('Account linked successfully.', 'powerpress-network') );
		return;
    }

    private function checkSignin($apiUrl, $createUrl, &$creds)
    {
        $option = get_option('powerpress_network_creds');
		$accessToken = $this->getAccessToken();
		
		if( !empty($accessToken) )
			return true;
			
		return false;
    }

    private function handleSearchFeed($apiUrl, $creds, $networkInfo)
    {
        return $this->apiBus->getFeedFromLink($apiUrl, $creds, $networkInfo, $_POST['feedUrl']);
    }



	public function action_admin_init()
    {
		// Only do anything if we are in the network page..
		if(empty($_GET['page']) || $_GET['page'] != 'network-plugin' )
			return;

        // Move wp-admin code here that processes things before any HTML is sent back by the server.
        $apiArray = powerpress_get_api_array();
        $apiUrl = $apiArray[0];
        $createUrl = get_home_url() . '/';
        $creds = array();
        if (isset ($_POST['target'])) {
            $this->handlePageAction($createUrl);
        }
        if (isset($_GET['code'])) {
            $this->handleCodeReturn($apiUrl, $createUrl, $creds);
        }
        if (isset($_POST['unlinkAccount'])){
            delete_option ('powerpress_network_creds');
        }
		
		if( !empty($_POST['ppn-action']) ) {
			switch( $_POST['ppn-action']) {
				case 'link-account': {
					// Link account action requested
					if (isset($_POST['signinRequest'])) {
					
						$auth = new PowerPressAuth();
						$result = $auth->getTemporaryCredentials();
					   
						// Okay we got it!
						if( $result !== false && !empty($result['temp_client_id']) && !empty($result['temp_client_secret']) ) {
							$state = md5( rand(0, 999999) . time() );
							update_option('powerpress_network_temp_client', array('temp_client_id' => $result['temp_client_id'], 'temp_client_secret' =>$result['temp_client_secret'], 'state'=>$state ));
							header('location:' . $auth->getApiUrl() . 'oauth2/authorize?response_type=code&client_id=' . $result['temp_client_id'] . '&redirect_uri=' . $createUrl . 'wp-admin/admin.php?page=network-plugin&state='.$state );
							exit;
						}
						
						// Handle error here
						if( !empty($result['error_description']) )
							powerpress_page_message_add_error( $result['error_description'] );
						else if( !empty($result['error']) )
							powerpress_page_message_add_error( $result['error'] );
						else
							powerpress_page_message_add_error( __('Error creating temporary client:','powerpress-network') .' '.$auth->GetLastError() );
					}
				}; break;
				case 'set-network-id': {
					$networkId = $_POST['networkId'];
					$requestUrl = '/2/powerpress/network/' . $networkId;
					$props = $this->requestAPI($requestUrl, true);
					
					 
					//$props = PowerpressNetworkDataBus::getCacheOrCallAPI($creds, $cacheName, $requestUrl, $needDirectAPI);
					if( !empty($props['network_id']) ) {
						update_option('powerpress_network_id', $networkId);
						if( !empty($props['network_title']) )
							update_option('powerpress_network_title', $props['network_title']);
					} else {
						//delete_option('powerpress_network_id');
						//delete_option('powerpress_network_title');
					}
					
				}; break;
				case 'unset-network-id': {
				
					delete_option('powerpress_network_id');
					delete_option('powerpress_network_title');
					$networkId = '';
				}; break;
			}
		}
        $passSignIn = $this->checkSignin($apiUrl, $createUrl, $creds);

        $danger = true;
        $alert = null;
        $status = null;
        $props = array();
        $needDirectAPI = false;


        $networkInfo = get_option('powerpress_network');
		$networkId  = get_option('powerpress_network_id');
        $networkTitle = get_option('powerpress_network_title');
        $networkInfo['network_id'] = $networkId;
        $networkInfo['network_title'] = $networkTitle;
		//echo "PowerPress Network ID: $networkId <br />";

        $accountInfo = $this->apiBus->getNetworkOwnerInformation($apiUrl, $creds, $networkInfo, $needDirectAPI);

        if ($passSignIn) { //If the user pass the signin section
            if ( empty($networkId) ){
                $status = 'List Networks';
            } else {
				if (isset($_GET['status']) && ($_GET['status'] != 'List Networks' ) )  {
					$status = $_GET['status'];
				} else {
					$status = 'Select Choice';
				}
            }
			//echo "-    - -  - - - - - - - - - - - - $status <br />";
            if (isset($_POST['listId'])) {
                $this->insertOption('list_id', $_POST['listId']);
                $networkInfo = get_option('powerpress_network');
				$networkInfo['network_id'] = $networkId;
                $networkInfo['network_title'] = $networkTitle;
            }
            if (isset($_POST['programId'])){
                $this->insertOption('program_id', $_POST['programId']);
                $networkInfo = get_option('powerpress_network');
				$networkInfo['network_id'] = $networkId;
                $networkInfo['network_title'] = $networkTitle;
            }
            if (isset($_POST['linkPageProgram'])){
                $this->insertOption('link_page_program', $_POST['linkPageProgram']);
                $networkInfo = get_option('powerpress_network');
				$networkInfo['network_id'] = $networkId;
                $networkInfo['network_title'] = $networkTitle;
            }
            if (isset($_POST['linkPageList'])){
                $this->insertOption('link_page_list', $_POST['linkPageList']);
                $networkInfo = get_option('powerpress_network');
				$networkInfo['network_id'] = $networkId;
                $networkInfo['network_title'] = $networkTitle;
            }
            if (isset($_POST['needDirectAPI']) && $_POST['needDirectAPI'] == true){
                //delete all option with ppn-cache at first
                $all_options = wp_load_alloptions();
                foreach (  $all_options as $key => $value ) {
                    if ( strpos( $key, 'ppn-cache ' ) === 0 ) {
                        delete_option( $key );
                    }
                }
            }

            if (isset ($_POST['changeOrCreate']) && $_POST['changeOrCreate'] == true) {
                if (isset($_POST['newListTitle'])) { //Create New List
                    $create = array(
                            'newListTitle' => $_POST['newListTitle'],
                            'newListDescription' => $_POST['newListDescription']
                    );
                    $props = $this->apiBus->createNewList($apiUrl, $creds, $networkInfo, $create);
                    $needDirectAPI = true;
                }

                if (isset($_POST['editListTitle'])) { //Edit List
                    $update = array(
                            'editListTitle'  => $_POST['editListTitle'],
                            'editListDescription'=> $_POST['editListDescription']
                    );
                    $props = $this->apiBus->updateList($apiUrl, $creds, $networkInfo, $update);
                    $needDirectAPI = true;
                }

                if (isset($_POST['requestAction'])) { //Change List
                    $needDirectAPI = true;
                    if ($_POST['requestAction'] == 'delete') {
                        if (isset($_POST['listId'])) {
                            $this->insertOption('list_id', $_POST['listId']);
                            $networkInfo = get_option('powerpress_network');
							$networkInfo['network_id'] = $networkId;
                            $networkInfo['network_title'] = $networkTitle;
                            $props = $this->apiBus->deleteSpecificList($apiUrl, $creds, $networkInfo);
                        }
                    } else if ($_POST['requestAction'] == 'save') {
                        $props = $this->apiBus->updateProgramsInSpecificList($apiUrl, $creds, $networkInfo, $_POST['program']);
                    }
                }


                if (isset($_POST['feedUrl'])) {
                    $props = $this->handleSearchFeed($apiUrl, $creds, $networkInfo);
                }

                if (isset($_POST['listIdForApp'])) {
                    $needDirectAPI = true;
                    $add = array(
                            'programIdForApp'=> $_POST['programIdForApp'],
                            'listIdForApp'   => $_POST['listIdForApp'],
                            'appLabel'       => $_POST['appLabel'],
                            'feedUrl'        => urlencode($_POST['feedUrl'])
                    );
                    $this->apiBus->submitApplication($apiUrl, $creds, $networkInfo, $add);
                }

                if (isset($_POST['appAction'])) {
                    $needDirectAPI = true;
                    $action = array(
                            'appAction' => $_POST['appAction'],
                            'applicantId' => $_POST['applicantId']
                    );
                    $props = $this->apiBus->changeApplicationStatus($apiUrl, $creds, $networkInfo, $action);
                }
            }

            if (isset($props['alert'])){
                $alert = $props['alert'];
            }
            if (isset($props['danger'])){
                $danger = $props['danger'];
            }

            $requestUrl = null;
//            $status = 'Manage Program';
//            $_POST['linkPage'] = 'Nothing here';
            switch ($status) {
                case 'List Networks':
                    $props = $this->apiBus->getNetworksInAccount($creds);
                    if (isset($_POST['linkNetwork']) && $_POST['linkNetwork'] == 'unlink') {
                        delete_option('powerpress_network');
                    }
                    break;

                case 'Select Choice':
                    $props = $this->apiBus->getSpecificNetworkInAccount($apiUrl, $creds, $networkInfo, $needDirectAPI );
                    $networkInfo = get_option('powerpress_network');
					$networkInfo['network_id'] = $networkId;
                    $networkInfo['network_title'] = $networkTitle;
                    break;

                case 'List Programs':
                    $props = $this->apiBus->getProgramsInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI );
                    break;

                case 'List Lists':
                    $props = $this->apiBus->getListsInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI );
                    $networkInfo = get_option('powerpress_network');
					$networkInfo['network_id'] = $networkId;
                    $networkInfo['network_title'] = $networkTitle;
                    break;

                case 'List Applicants':
                    $props = $this->apiBus->getApplicantsInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI );
                    break;

                case 'Manage List':
                    $props = $this->apiBus->getSpecificListInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI );
                    $networkInfo = get_option ('powerpress_network');
					$networkInfo['network_id'] = $networkId;
                    $networkInfo['network_title'] = $networkTitle;
                    break;
                case 'Manage Program':
                    $props = $this->apiBus->getSpecificProgramInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI);
                    $networkInfo = get_option ('powerpress_network');
					$networkInfo['network_id'] = $networkId;
                    $networkInfo['network_title'] = $networkTitle;
                    break;
            }

        }
        $return = array();
        $return['status'] = $status;
        $return['props'] = $props;
        $return['accountInfo'] = $accountInfo;
        $return['alert'] = $alert;
        $return['danger'] = $danger;
        $return['network_info'] = $networkInfo;
        return $return;

    }

    public function returnDisplay()
    {
        return $this->display;
    }


    static function removeOption ($key)
    {
        $result = get_option ('powerpress_network');
        unset ($result[$key]);
        update_option('powerpress_network', $result);
    }



    static function insertOption ($key, $value)
    {
        $result = get_option ('powerpress_network');
        $result[$key] = $value;
        update_option('powerpress_network', $result);
    }


    function display_plugin()
    {
		if( function_exists('powerpress_page_message_print') )
			powerpress_page_message_print();
        $status = $this->display['status'];
        $props = $this->display['props'];
        $accountInfo = $this->display['accountInfo'];
        $alert = $this->display['alert'];
        $danger = $this->display['danger'];
        $networkInfo = $this->display['network_info'];
        if ($alert) { //If there is alert, print it out
            ?>
            <div class="alert<?php if (!$danger) echo '-success'; ?>">
                <p class="alertMessage"><?php echo __($alert, 'powerpress-network'); ?></p>
                <p class="closebtn" onclick="this.parentElement.style.display='none';">&times</p>
            </div>

            <?php
        }
        ?>
        <link href="<?php echo powerpress_get_root_url() . "css/admin.css"; ?>" rel="stylesheet">
        <link href="<?php echo $this->powerpress_network_plugin_url() . "css/admin.css"; ?>" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script src="<?php echo $this->powerpress_network_plugin_url() . "js/admin.js"; ?>"></script>

        <?php ?>


        <div class="container">
        <?php
        //var_dump($status);
        if ($status == 'List Networks') {
            echo $this->getHTML('networks.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'Select Choice') {
            echo $this->getHTML('base.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'List Programs') {
            echo $this->getHTML('programs.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'List Lists') {
            echo $this->getHTML('lists.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'List Applicants') {
            echo $this->getHTML('applications.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'Submit App') {
            echo $this->getHTML('submitapplications.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'Create List') {
            echo $this->getHTML('createlist.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'Manage List') {
            echo $this->getHTML('managelist.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'Edit List') {
            echo $this->getHTML('editlist.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'Create List Page') {
            echo $this->getHTML('createlistpage.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'Create Program Page') {
            echo $this->getHTML('createprogrampage.php', $props, $networkInfo, $accountInfo);
        } else if ($status == 'Manage Program'){
            echo $this->getHTML('manageprogram.php', $props, $networkInfo, $accountInfo);
        } else {
            echo $this->getHTML('signin.php', $props, $networkInfo, $accountInfo);
        }
    ?>
    </div>
<?php
    }

    static function updateMeta ($meta_key)
    {
        global $wpdb;
        $update = array ('last_update'=>time(), 'need_update'=>true);
        $update = serialize($update);
        $query = "UPDATE {$wpdb->prefix}postmeta SET meta_value='".$update."' WHERE meta_key ='".$meta_key."'";
        $wpdb->get_results( $query, OBJECT );
    }


    function checkUpdateProgram()
    {
        $option = get_option ('powerpress_network_creds');
        if (empty($option)){
            return;
        }
		$accessToken = $this->getAccessToken();
		
        if (!wp_next_scheduled ( 'updateProgram' )) {
            wp_schedule_event(time(), 'hourly', 'updateProgram');
        }
        global $wpdb;

        $timeExecute = wp_next_scheduled('updateProgram');
        if (time() >= $timeExecute){
            $network = get_option ('powerpress_network');
			if( empty($networkId) )
				return;
            $apiArray = powerpress_get_api_array();
            $apiUrl = $apiArray[0];
            $post = false; // array('grant_type'=>'client_credentials', 'access_token'=>$accessToken );
            $requestUrl = $apiUrl.'2/powerpress/network/'.$networkId.'/update?since='.($timeExecute - 24*60*60);
            $programUpdate = $this->requestAPI($requestUrl);
            for ($i = 0 ; $i < count ($programUpdate); ++$i) {
                PowerPressNetwork::updateMeta($programUpdate[$i]);
            }
        }
    }
}
