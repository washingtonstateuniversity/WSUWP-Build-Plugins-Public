<?php

class PowerPressNetworkProgram extends PowerPressNetworkShortCode
{
    function __construct()
    {
        parent::__construct('ppn-program');
    }

    function ppn_shortcode($attr, $contents)
    {
        plugins_url('/powerpress/powerpress.php', '__FILE__');
        plugins_url('/powerpress/powerpressadmin-jquery.php');
        require_once(WP_PLUGIN_DIR . '/powerpress/powerpressadmin.php');

        $program_id = $attr['id'];
        $style = 'full';
        $ssbstyle = "modern";
        $ssbshape = "circle";
        $limit = '20';
        if (isset($attr['style'])) {
            $style = $attr['style'];
        }
        if (isset($attr['ssb-style'])) {
            $ssbstyle = $attr['ssb-style'];
        }
        if (isset($attr['ssb-shape'])) {
            $ssbshape = $attr['ssb-shape'];
        }
        if (isset($attr['limit'])) {
            $limit = $attr['limit'];
        }
        $props = [];

        $props['program_id'] = $program_id;
        $props['style'] = $style;
        $props['ssb-style'] = $ssbstyle;
        $props['ssb-shape'] = $ssbshape;
        $props['limit'] = $limit;

        $apiArray = powerpress_get_api_array();
        $apiUrl = $apiArray[0];
        if (!isset($null)) {
            $null = null;
        }
        $networkInfo = get_option('powerpress_network');
        $networkId = get_option('powerpress_network_id');
        $networkTitle = get_option('powerpress_network_title');
        $networkInfo['network_id'] = $networkId;
        $networkInfo['network_title'] = $networkTitle;

        $props['network_general'] = get_option('network_general');
        if (!empty($networkInfo['network_id'])) {
            $networkInfo['program_id'] = $program_id;

            $post = false;
            $results = PowerpressNetworkDataBus::getSpecificProgramInNetwork($apiUrl, $post, $networkInfo, false);

            if (isset($results['error'])) {
                if ($results['error'] == 'Fail To Find Your Program In This Network') {
                    return PowerPressNetwork::getHTML('no-program-results.php', $props, null, null);
                } else if ($results['error'] == 'Your account does not have the network with specified id') {
                    return PowerPressNetwork::getHTML('invalid-network.php', $props, null, null);
                }
                return $results['error'];
            }

            $props['episodes'] = $results['episodes'];
            $props['artwork_url'] = $results['program_info']['artwork_url'];
            $props['program_title'] = $results['program_info']['program_title'];
            $props['program_desc'] = $results['program_info']['program_desc'];
            $props['talent_name'] = $results['program_info']['talent_name'];
            $props['program_url'] = $results['program_info']['program_htmlurl'];
            $props['program_rssurl'] = $results['program_info']['program_rssurl'];
            $props['program_itunesurl']  = isset($results['program_info']['program_itunesurl']) ? $results['program_info']['program_itunesurl'] : false;

            $props['subscribe_googleplay']  = isset($results['program_info']['subscribe_googleplay']) ? $results['program_info']['subscribe_googleplay'] : false;
            $props['subscribe_html']  = isset($results['program_info']['subscribe_html']) ? $results['program_info']['subscribe_html'] : false;
            $props['subscribe_stitcher']  = isset($results['program_info']['subscribe_stitcher']) ? $results['program_info']['subscribe_stitcher'] : false;
            $props['subscribe_tunein']  = isset($results['program_info']['subscribe_tunein']) ? $results['program_info']['subscribe_tunein'] : false;
            $props['subscribe_itunes']  = isset($results['program_info']['subscribe_itunes']) ? $results['program_info']['subscribe_itunes'] : false;
            $props['subscribe_deezer']  = isset($results['program_info']['subscribe_deezer']) ? $results['program_info']['subscribe_deezer'] : false;
            $props['subscribe_iheart']  = isset($results['program_info']['subscribe_iheart']) ? $results['program_info']['subscribe_iheart'] : false;
            $props['subscribe_pandora']  = isset($results['program_info']['subscribe_pandora']) ? $results['program_info']['subscribe_pandora'] : false;
            $props['subscribe_radio_com']  = isset($results['program_info']['subscribe_radio_com']) ? $results['program_info']['subscribe_radio_com'] : false;
            $props['subscribe_spotify'] = isset($results['program_info']['subscribe_spotify']) ? $results['program_info']['subscribe_spotify'] : false;
            return PowerPressNetwork::getHTML('program-result.php', $props, null, null);
        } else {
            return PowerPressNetwork::getHTML('invalid-network.php', $props, null, null);
        }
    }
}
$GLOBALS['ppn_program'] = new PowerPressNetworkProgram();