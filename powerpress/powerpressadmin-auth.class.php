<?php
/*
PowerPressAuth library for WordPress (copied & pasted from Network plugin)

If curl is enabled, the WordPress functions are not used, allowing this class to work without WordPress
*/


class PowerPressAuth {

    // Error handling
    var $error = '';
    var $errorCode = 0;

    // API call URLs, can loop through for failures
    var $apiUrl = array('https://api.blubrry.com/');
    var $apiUrlIndex = 0;

    function __construct() {

        if( defined('POWERPRESS_BLUBRRY_API_URL') ) {
            if( strstr(POWERPRESS_BLUBRRY_API_URL, 'http://api.blubrry.com') == false ) // If not the default
            {
                $this->apiUrl = explode(';', POWERPRESS_BLUBRRY_API_URL);
            }
            else
            {
                $this->apiUrl[] = 'https://api.blubrry.com/'; // Use secure URL first when possible
                $this->apiUrl[] = 'http://api.blubrry.net/';
                $this->apiUrl[] = 'http://api.blubrry.com/';
            }
        }
    }

    function getApiUrl()
    {
        return $this->apiUrl[ $this->apiUrlIndex ];
    }

    function getDebugInfo() {
        $str = '';
        $str .= "API URLs:<br>\n";
        $str .= "<pre>". print_r($this->apiUrl, true) . "</pre>\n";
        return $str;
    }

    function getLastError() {
        return $this->error;
    }

    function getLastErrorCode() {
        return $this->errorCode;
    }

    function setApiUrl($url) {
        $this->apiUrlIndex = 0;
        $this->apiUrl = array($url);
    }


    function getTemporaryCredentials()
    {
        $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . "client/temporary?cache=" . md5( rand(0, 999) . time() );
        $return = $this->_makeApiCall($requestUrl);
        while( $return === false && $this->_retryApiUrl() ) {
            $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . "client/temporary?cache=" . md5( rand(0, 999) . time() );
            $return = $this->_makeApiCall($requestUrl);
        }
        return $return;
    }

    function issueClient($code, $clientId, $clientSecret, $redirectUri = '')
    {
        $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . 'client/issue?client_id=' . urlencode($clientId) . '&client_secret=' . urlencode($clientSecret) . '&code=' . urlencode($code) . '&redirect_uri='. urlencode($redirectUri);
        $return = $this->_makeApiCall($requestUrl);
        while( $return === false && $this->_retryApiUrl() ) {
            $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . 'client/issue?client_id=' . urlencode($clientId) . '&client_secret=' . urlencode($clientSecret) . '&code=' . urlencode($code) . '&redirect_uri='. urlencode($redirectUri);
            $return = $this->_makeApiCall($requestUrl);
        }
        return $return;
    }

    function getAccessTokenFromCode($code, $clientId, $clientSecret, $redirectUri ='')
    {
        $clientAuth = base64_encode("$clientId:$clientSecret");
        $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . 'oauth2/token';
        $post = array();
        $post['grant_type'] = 'authorization_code';
        $post['code'] = $code;
        $post['redirect_uri'] = urlencode($redirectUri);
        $return = $this->_makeApiCall($requestUrl, $post, $clientAuth);
        while( $return === false && $this->_retryApiUrl() ) {
            $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . 'oauth2/token';
            $return = $this->_makeApiCall($requestUrl, $post, $clientAuth);
        }
        return $return;
    }

    function getAccessTokenFromRefreshToken($refreshToken, $clientId, $clientSecret, $redirectUri ='')
    {
        $clientAuth = base64_encode("$clientId:$clientSecret");
        $post['grant_type'] = 'refresh_token';
        $post['refresh_token'] = $refreshToken;
        $post['redirect_uri'] = $redirectUri;
        $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . 'oauth2/token';
        $return = $this->_makeApiCall($requestUrl, $post, $clientAuth);
        while( $return === false && $this->_retryApiUrl() ) {
            $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . 'oauth2/token';
            $return = $this->_makeApiCall($requestUrl, $post, $clientAuth);
        }
        return $return;
    }

    function reSendVerifyEmail() {
        $creds = get_option('powerpress_creds');
        $path = '/account/create-status?client_id=' . urlencode($creds['client_id']) . '&email=true';
        return $this->api('', $path);
    }

    function checkAccountVerified() {
        $creds = get_option('powerpress_creds');
        $path = '/account/create-status?cache=' . md5( rand(0, 999) . time() ) . '&client_id=' . urlencode($creds['client_id']);
        return $this->api('', $path);
    }

    function revokeClient($accessToken, $clientID, $clientSecret) {
        $path = '/client/revoke?client_id=' . urlencode($clientID) . '&client_secret=' . urlencode($clientSecret);
        return $this->api($accessToken, $path, array('client_id' => $clientID, 'client_secret' => $clientSecret));
    }

    function api($accessToken, $path, $post = false, $custom_request = false, $timeout = 15, $decode_json = true )
    {
        $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . ltrim($path, '/'); // Make sure prefix slash is removed
        $return = $this->_makeApiCall($requestUrl, $post, false, $accessToken, $custom_request, $timeout, $decode_json);
        while( $return === false && $this->_retryApiUrl() ) {
            $requestUrl = $this->apiUrl[ $this->apiUrlIndex ] . ltrim($path, '/'); // Make sure prefix slash is removed
            $return = $this->_makeApiCall($requestUrl, $post, false, $accessToken, $custom_request, $timeout, $decode_json);
        }
        return $return;
    }

    private function _makeApiCallCurl($url, $post = false, $clientCredsBase64 = false, $bearerValue = '', $custom_request = false, $timeout = 15, $decode_json = true ) {

        $curl = curl_init();
        if ( version_compare( PHP_VERSION, '5.5.0') > 0 )
            curl_reset($curl);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        if ( version_compare( PHP_VERSION, '5.3.0') < 0 && !ini_get('safe_mode') )
        {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Follow location redirection
            curl_setopt($curl, CURLOPT_MAXREDIRS, 12); // Location redirection limit
        }
        else if ( !ini_get('open_basedir') )
        {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Follow location redirection
            curl_setopt($curl, CURLOPT_MAXREDIRS, 12); // Location redirection limit
        }
        else // open_basedir is set, bummer
        {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 0 );
        }

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2 ); // Connect time out
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // The maximum number of seconds to execute.
        curl_setopt($curl, CURLOPT_USERAGENT, 'Blubrry PowerPress/'.POWERPRESS_VERSION);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        if( preg_match('/^https:\/\//i', $url) != 0 )
        {
            if( file_exists(ABSPATH . WPINC . '/certificates/ca-bundle.crt') ) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2 );
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true );
                curl_setopt($curl, CURLOPT_CAINFO, ABSPATH . WPINC . '/certificates/ca-bundle.crt');
            } else {
                // Trust the SSL certs, not ideal but we don't have the bundle
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            }
        }
        // HTTP Authentication
        if( !empty($clientCredsBase64) )
        {
            curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$clientCredsBase64) );
        } else if( !empty($bearerValue) ) {
            curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$bearerValue) );
        }

        // Handle post data
        if( is_array($post) && count($post) > 0 )
        {
            $post_query = '';
            foreach( $post as $name => $value )
            {
                if( $post_query != '' )
                    $post_query .= '&';
                $post_query .= $name;
                $post_query .= '=';
                $post_query .= urlencode($value);
            }
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_query);
        }
        else if( $custom_request )
        {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $custom_request);
        }

        $returnedBody = curl_exec($curl);
        $error = curl_errno($curl);
        $error_msg = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if( $error ) // Curl level error, lets deal with it...
        {
            $this->error = $error_msg;
            $this->errorCode = $error;
            return false;
        }
        else if( $http_code > 399 ) // HTTP level error, lets record it and see if the response is what we want to use...
        {
            $this->error = "HTTP $http_code";
            $this->errorCode = $http_code;
            switch( $http_code )
            {
                case 400: $this->error .= ' '. __("Bad Request", 'powerpress'); break;
                case 401: $this->error .= ' '. __("Unauthorized (Check that your username and password are correct)", 'powerpress'); break;
                case 402: $this->error .= ' '. __("Payment Required", 'powerpress'); break;
                case 403: $this->error .= ' '. __("Forbidden", 'powerpress'); break;
                case 404: $this->error .= ' '. __("Not Found", 'powerpress'); break;
            }
        }

        if( !empty($returnedBody) ) {
            //mail('cio@rawvoice,com', '_makeApiCallCurl body', "$returnedBody");
            //var_dump($returnedBody);
            if ($decode_json) {
                $decoded = @json_decode($returnedBody, true);
                if (!empty($decoded))
                    return $decoded;
            } else {
                return $returnedBody;
            }

            if( $this->errorCode != 0 ) {
                $this->error = 'Unable to decode response.';
                $this->errorCode = -1;
            }
            return false;
        }

        if( !empty($returnedBody) )
            $this->error = $returnedBody;
        else
            $this->error = 'Unknown error occurred.';
        $this->errorCode = -1;
        return false;
    }

    private function _makeApiCall($url, $post = false, $clientCredsBase64 = false, $bearerValue = '', $custom_request = false, $timeout = 15, $decode_json = true) {

        // Reset the errors
        $this->error = '';
        $this->errorCode = 0;
        if( function_exists('curl_init') ) // If using CURL, better handling of errors
            return $this->_makeApiCallCurl($url, $post, $clientCredsBase64, $bearerValue, $custom_request, $timeout, $decode_json);

        if( !function_exists('wp_remote_post') ) {
            $this->error = 'WordPress or curl library required.';
            $this->errorCode = -1;
            return false;
        }

        $options = array();
        $options['timeout'] = $timeout;
        $options['user-agent'] = 'Blubrry PowerPress/'.POWERPRESS_VERSION;
        if( !empty($clientCredsBase64) )
            $options['headers']['Authorization'] = 'Basic '.$clientCredsBase64;
        else if( !empty($bearerValue) )
            $options['headers']['Authorization'] = 'Bearer '.$bearerValue;

        if( !empty($post) ) {
            $options['body'] = $post;
            $response = wp_remote_post( $url, $options );
        } else if($custom_request) {
            $options['method'] = $custom_request;
            $response = wp_remote_request($url,$options);
        } else
        {
            $response = wp_remote_get( $url, $options );
        }

        if ( is_wp_error( $response ) )
        {
            $this->errorCode = $response->get_error_code();
            $this->error = $response->get_error_message();
            return false;
        }

        if( !empty($response['body']) )
            $returnedBody = $response['body'];
        else
            $returnedBody = '';

        if( isset($response['response']['code']) && $response['response']['code'] > 399 )
        {
            $this->error = "HTTP ".$response['response']['code'];
            $this->errorCode = $response['response']['code'];
            switch( $response['response']['code'] )
            {
                case 400: $this->error .= ' '. __("Bad Request", 'powerpress'); break;
                case 401: $this->error .= ' '. __("Unauthorized (Check that your username and password are correct)", 'powerpress'); break;
                case 402: $this->error .= ' '. __("Payment Required", 'powerpress'); break;
                case 403: $this->error .= ' '. __("Forbidden", 'powerpress'); break;
                case 404: $this->error .= ' '. __("Not Found", 'powerpress'); break;
                default: $this->error .= ' '.$response['response']['message'];
            }
        }

        if( !empty($returnedBody) ) {
            if ($decode_json) {
                $decoded = @json_decode($returnedBody, true);
                if ($decoded !== false) {
                    return $decoded;
                }
            } else {
                return $returnedBody;
            }

            if( $this->errorCode != 0 ) {
                $this->error = 'Unable to decode response.';
                $this->errorCode = -1;
            }
            return false;
        }

        if( !empty($returnedBody) )
            $this->error = $returnedBody;
        else
            $this->error = 'Unknown error occurred.';
        $this->errorCode = -1;
        return false;
    }

    private function _retryApiUrl() {
        if( ($this->apiUrlIndex+1) < count($this->apiUrl) ) {
            // Retry using the next indexed API url
            $this->apiUrlIndex++;
            return true;
        }
        return false;
    }
} // end of class

// eof