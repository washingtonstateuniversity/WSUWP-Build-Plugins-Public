<?php

class PowerpressNetworkDataBus{
    function __construct()
    {
        //Nothing here
    }

    /**
     * Returns requested data after deciding whether to pull the data from a cache or the API
     * @param $creds array of oauth credentials for blubrry api
     * @param $cacheName string
     * @param $requestUrl string
     * @param $needDirectAPI bool--true if we do not want a cached result
     * @param $clientAuth bool
     * @return array of data
     */
    static function getCacheOrCallAPI($creds, $cacheName, $requestUrl, $needDirectAPI, $clientAuth = true)
    {
		// value of $requestUrl should not have 
		if( preg_match('/^https?:\/\/([^\/]*)(.*)$/', $requestUrl, $matches) ) {
			$requestUrl = $matches[2];
		}
		
		if( empty($creds['post']) )
			$creds['post'] = false;
		
        $cache = get_option($cacheName);
        //Need direct API: will not use cache but directly call an API
        if ($needDirectAPI === false && !empty($cache)) {
            if (isset($cache['data']) && isset($cache['insert_timestamp']) && $cache['insert_timestamp'] > (time() - 4*60*60)) {
                //mail ('use cache 1', 'use cache 1 ', print_r('use cache 1', true));
                //case 1: there is still cache in database and it is created from less than 1 day
                return $cache['data'];
            } else if (isset($cache['last_error_timestamp']) && $cache['last_error_timestamp'] > time() - 60*60 && isset($cache['data'])) {
                //mail ('use cache 2', 'use cache 2 ', print_r('use cache 2', true));
                //case 2: if the cache is created from more than 1 days, but the last error is less than 1 hour
                return $cache['data'];
            } else {
                $props = $GLOBALS['ppn_object']->requestAPI($requestUrl, $clientAuth, $creds['post']);
                if (empty($props['error'])) {
                    $cache['data'] = $props;
                    $cache['insert_timestamp'] = time();
                    update_option($cacheName, $cache);
                    //case 3: there is no useful cache in case 1 and 2, call an API. If successful, insert new cache to database
                    //mail ('use API', 'use API ', print_r('use API', true));
                    return $props;
                } else {
                    $cache['last_error_timestamp'] = time();
                    $cache['insert_timestamp'] = 0;
					$cache['returned_data'] = $props;
                    update_option($cacheName, $cache);
                    if (isset($cache['data'])) {
                        //mail ('use cache 3', 'use cache 3 ', print_r('use cache 3', true));
                        //case 4: if the new API call also produces error, update the timestamp error
                        return $cache['data'];
                    } else {
                        //mail ('empty', 'empty', print_r('empty', true));
                        //case 5: nothing is useful, return error
                        return array('error' => $cache['error']);
                    }
                }
            }
        } else{
            $props = $GLOBALS['ppn_object']->requestAPI($requestUrl, $clientAuth, $creds['post']);
            if (isset($cacheName)) {
                if (empty($props['error'])) {
                    $cache['data'] = $props;
                    $cache['insert_timestamp'] = time();
                    update_option($cacheName, $cache);
                    //mail ('use API', 'use API ', print_r('use API', true));
                    return $props;
                } else {
                    $cache['last_error_timestamp'] = time();
                    $cache['insert_timestamp'] = 0;
                    $cache['error'] = $props['error'];
					$cache['returned_data'] = $props;
                    update_option($cacheName, $cache);
                    if (isset($cache['data'])) {
                        //mail ('use cache 3', 'use cache 3 ', print_r('use cache 3', true));
                        return $cache['data'];
                    } else {
                        //mail ('empty', 'empty', print_r('empty', true));
                        return array('error' => $cache['error']);
                    }
                }
            }
            return $props;
        }
    }

    /**
     * Returns networks associated to the account
     * @param $creds array of oauth credentials for blubrry api
     * @return array of networks
     */
    static function getNetworksInAccount($creds)
    {
        $cacheName = 'ppn-cache n';
        $requestUrl = '/2/powerpress/network';
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, $cacheName, $requestUrl,  true);
    }

    /**
     * Returns data about a specific network
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array
     * @param $needDirectAPI bool--true if we do not want a cached result
     * @return array|bool--either the desired data or false
     */
    static function getSpecificNetworkInAccount($apiUrl, $creds, $networkInfo, $needDirectAPI)
    {
        if (isset($networkInfo['network_id'])) {
            $cacheName = 'ppn-cache n-' . $networkInfo['network_id'];
            $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'];
            $props = PowerpressNetworkDataBus::getCacheOrCallAPI($creds, $cacheName, $requestUrl, $needDirectAPI);
            return $props;
        }
        return false;
    }

    /**
     * Returns all programs in a network
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array
     * @param $needDirectAPI bool--true if we do not want a cached result
     * @return array of programs
     */
    static function getProgramsInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI)
    {
        $cacheName = 'ppn-cache n-'.$networkInfo['network_id'].'-p';
        $requestUrl = '/2/powerpress/network/' . $networkInfo['network_id'] . '/programs';
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, $cacheName, $requestUrl, $needDirectAPI);
    }

    /**
     * Changes the status of an applicant to the network
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array
     * @param $appAction array with action and applicant id
     * @return array with return data or error
     */
    static function changeApplicationStatus($apiUrl, $creds, $networkInfo, $appAction)
    {
        $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/applicant/changestatus?action=' . $appAction['appAction'] . '&applicantId=' . $appAction['applicantId'];
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl,  true);
    }

    /**
     * Returns all lists in a network
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array
     * @param $needDirectAPI bool--true if we do not want a cached result
     * @return array of lists
     */
    static function getListsInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI)
    {
        $cacheName = 'ppn-cache n-'.$networkInfo['network_id'].'-l';
        $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/lists/';
        $props = PowerpressNetworkDataBus::getCacheOrCallAPI($creds, $cacheName, $requestUrl, $needDirectAPI);
        return $props;
    }

    /**
     * Creates a new list in a network
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array
     * @param $create array of data about the new list
     * @return array with return data or error
     */
    static function createNewList($apiUrl, $creds, $networkInfo, $create)
    {
        $create['newListTitle'] = urlencode($create['newListTitle']);
        $create['newListDescription'] = urlencode($create['newListDescription']);
        $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/lists/new?title=' . $create['newListTitle'] . '&description=' . $create['newListDescription'];
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl, true);
    }

    /**
     * Adds programs to a list
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array
     * @param $addedPrograms array of programs
     * @return array with return data or error
     */
    static function updateProgramsInSpecificList($apiUrl, $creds, $networkInfo, $addedPrograms)
    {
        $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/lists/' . $networkInfo['list_id'] . '/update-programs?';
        if (isset($addedPrograms)) {
            foreach ($addedPrograms as $programId) {
                $requestUrl .= 'programId[]=' . $programId . '&';
            }
        }
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl, true);
    }

    /**
     * Deletes a list
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array which includes the id of the list to be deleted
     * @return array with return data or error
     */
    static function deleteSpecificList($apiUrl, $creds, $networkInfo)
    {
        $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/lists/' . $networkInfo['list_id'] . '/delete';
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl, true);
    }

    /**
     * Returns all programs that have applied to a network
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array
     * @param $needDirectAPI bool--true if we do not want a cached result
     * @return array of applicants
     */
    static function getApplicantsInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI)
    {
        $cacheName = "ppn-cache n-".$networkInfo['network_id']."-a";
        $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/applicant';
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, $cacheName, $requestUrl, $needDirectAPI);
    }

    /**
     * Returns data relating to one specific list
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array which includes the id of the desired list
     * @return array|bool--array of list data or false
     */
    static function getSpecificListInNetwork ($apiUrl, $creds, $networkInfo, $needDirectAPI)
    {
        if (!empty($networkInfo['network_id']) && !empty ($networkInfo['list_id'])) {
            $cacheName = 'ppn-cache n-' . $networkInfo['network_id'] . '-l-' . $networkInfo['list_id'];
            $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/lists/' . $networkInfo['list_id'] . '/programs';
            $props = PowerpressNetworkDataBus::getCacheOrCallAPI($creds, $cacheName, $requestUrl, $needDirectAPI = true);
            if (!empty($props['list_info']['list_title']) && !empty($props['list_info']['list_description'])) {
                PowerPressNetwork::insertOption('list_title', $props['list_info']['list_title']);
                PowerPressNetwork::insertOption('list_description', $props['list_info']['list_description']);
            }
            return $props;
        }
        return false;
    }

    /**
     * Returns data relating to one specific list
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array which includes the id of the desired program
     * @param $needDirectAPI bool--true if we do not want a cached result
     * @return array|bool--array of program data or false
     */
    static function getSpecificProgramInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI)
    {
        $cacheName = 'ppn-cache n-'.$networkInfo['network_id'].'-p-'.$networkInfo['program_id'];
        if (!empty($networkInfo['network_id']) && !empty($networkInfo['program_id']) ) {
            $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/programs/' . $networkInfo['program_id'];
            $props = PowerpressNetworkDataBus::getCacheOrCallAPI(false, $cacheName, $requestUrl, $needDirectAPI);
            if (!empty($props['program_info']['program_id']) && !empty($props['program_info']['program_title'])) {
                PowerPressNetwork::insertOption('program_id', $props['program_info']['program_id']);
                PowerPressNetwork::insertOption('program_title', $props['program_info']['program_title']);
            }
            return $props;
        }
        return false;
    }

    /**
     * Removes a program from a network
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array which includes the id of the program to be removed
     * @param $needDirectAPI bool--true if we do not want a cached result
     * @return array|bool--array of return data or false
     */
    static function removeSpecificProgramInNetwork($apiUrl, $creds, $networkInfo, $needDirectAPI)
    {
        $cacheName = 'ppn-cache n-'.$networkInfo['network_id'].'-p-'.$networkInfo['program_id'];
        if (!empty($networkInfo['network_id']) && !empty($networkInfo['program_id']) ) {
            $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/programs/' . $networkInfo['program_id'] . '/delete';
            $props = PowerpressNetworkDataBus::getCacheOrCallAPI($creds, $cacheName, $requestUrl, $needDirectAPI);
            if (!empty($props['program_info']['program_id']) && !empty($props['program_info']['program_title'])) {
                PowerPressNetwork::removeOption('program_id', $props['program_info']['program_id']);
                PowerPressNetwork::removeOption('program_title', $props['program_info']['program_title']);
            }
            return $props;
        }
        return false;
    }

    /**
     * Returns data about the user account associated to the network
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array which includes the id of the desired program
     * @param $needDirectAPI bool--true if we do not want a cached result
     * @return array|bool--array of account data or false
     */
    static function getNetworkOwnerInformation($apiUrl, $creds, $networkInfo, $needDirectAPI)
    {
        if(!empty($networkInfo['network_id']))
        {
            $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/account/information/';
            $props = self::getCacheOrCallAPI($creds, null, $requestUrl, $needDirectAPI);
            return $props;
        } else {
            return false;
        }
    }

    /**
     * Given a feed URL, returns information about the applicant
     * @param $apiUrl string url to Blubrry api
     * @param $createUrl string url to wordpress site
     * @param $tokenInfo array which includes the id of the desired program
     * @return array of credentials
     */
    static function refreshTokenToGetCredentials($apiUrl, $createUrl, $tokenInfo)
    {
        $clientAuth = $tokenInfo['client_auth'];
        $refreshToken = $tokenInfo['refresh_token'];
        $oldAccessToken = $tokenInfo['access_token'];
        $requestUrl = $apiUrl . 'oauth2/token?grant_type=refresh_token&refresh_token=' . $refreshToken;
        $post = array('grant_type' => 'refresh_token', 'refresh_token' => $refreshToken, 'redirect_uri' => $createUrl . 'wp-admin/admin.php?page=network-plugin');
        $creds['post'] = $post;
        $props = PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl, true, $clientAuth);
        $props['client_auth'] = $clientAuth;
        $props['refresh_token'] = $refreshToken;
        return $props;
    }

    /**
     * Given a feed URL, returns information about the applicant
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array which includes the id of the desired program
     * @param $feedUrl string
     * @return array of applicant data
     */
    static function getFeedFromLink($apiUrl, $creds, $networkInfo, $feedUrl)
    {
        $feedUrl = trim($feedUrl);
        $feedUrl = preg_replace('#^(https?://|ftps?://)?(www.)?#', '', $feedUrl);
        $feedUrl = urlencode($feedUrl);
        $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/applicant/findshow?feedUrl=' . $feedUrl;
        $props = PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl, true);
        $requestUrl = $apiUrl.'2/powerpress/network/'.$networkInfo['network_id'].'/lists/';
        $props['list'] = PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl, true);
        return $props;
    }

    /**
     * Returns data relating to one specific list
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array which includes the id of the desired program
     * @param $add array of data about the applicant
     * @return array with return data or error
     */
    static function submitApplication($apiUrl, $creds, $networkInfo, $add)
    {
        $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/applicant/submit?listId=' . $add['listIdForApp'] . '&programId=' . $add['programIdForApp'] . '&feedUrl=' . $add['feedUrl'] . '&webName=' . $add['appLabel'];
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl, true);
    }

    /**
     * Returns data relating to one specific list
     * @param $apiUrl string
     * @param $creds array of oauth credentials for blubrry api
     * @param $networkInfo array which includes the id of the desired program
     * @param $update array of new data to overwrite the existing list data
     * @return array with return data or error
     */
    static function updateList($apiUrl, $creds, $networkInfo, $update)
    {
        $update['editListTitle'] = urlencode($update['editListTitle']);
        $update['editListDescription'] = urlencode($update['editListDescription']);
        $requestUrl = $apiUrl . '2/powerpress/network/' . $networkInfo['network_id'] . '/lists/'.$networkInfo['list_id'].'/edit?title=' . $update['editListTitle'] . '&description=' . $update['editListDescription'];
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl, true);;

    }

    /**
     * Given a feed URL, returns information about the applicant
     * @param $apiUrl string
     * @param $post array
     * @return array of token data
     */
    static function checkTokenValidity($apiUrl, $post)
    {
        $creds['post'] = $post;
        $requestUrl = $apiUrl . '2/powerpress/network/token';
        return PowerpressNetworkDataBus::getCacheOrCallAPI($creds, null, $requestUrl, true);
    }
}
