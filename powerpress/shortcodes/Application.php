<?php

class PowerPressNetworkApplication extends PowerPressNetworkShortCode
{
    function __construct()
    {
        parent::__construct('ppn-application');
    }

    function ppn_shortcode($attr, $contents)
    {
        require_once(WP_PLUGIN_DIR . '/powerpress/powerpressadmin.php');
        $props = array();

        if ( !empty($attr['auto-active']) ) {
            $props['auto-active'] = (bool)true;
        }

        if ( isset($attr['terms-url']) ) {
            $props['terms-url'] = (string)$attr['terms-url'];
        }

        if ( isset($attr['default-list']) ) {
            $props['default-list'] = (int)$attr['default-list'];
        }

        $props['network_general'] = get_option('network_general');
        $props['powerpress_network'] = get_option('powerpress_network');
        if (isset($props['powerpress_network']['list_id'])){
            $listID = $props['powerpress_network']['list_id'];
        }
        $networkID = get_option('powerpress_network_id');
        $networkTitle = get_option('powerpress_network_title');
        $props['powerpress_network']['network_id'] = $networkID;
        $props['powerpress_network']['network_title'] = $networkTitle;

        $post = false;
        $requestUrl = '/2/powerpress/network/'.$networkID.'/lists/';
        $results = $GLOBALS['ppn_object']->requestAPI($requestUrl, true, $post);

        $props['post'] = $post;
        $props['lists'] = $results;
        //$props['apiUrl'] = $apiUrl;
        if (!isset($null)){
            $null = null;
        }
        return PowerPressNetwork::getHTML('forms.php', $props, null, null);
    }
}

$GLOBALS['ppn_application'] = new PowerPressNetworkApplication();

