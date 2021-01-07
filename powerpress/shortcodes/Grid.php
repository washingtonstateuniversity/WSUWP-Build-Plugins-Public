<?php


class PowerPressNetworkGrid extends PowerPressNetworkShortCode
{
    function __construct()
    {
        parent::__construct('ppn-gridview');
    }

    function ppn_shortcode($attr, $contents)
    {
        plugins_url('/powerpress/powerpress.php', '__FILE__');
        plugins_url('/powerpress/powerpressadmin-jquery.php');
        require_once(WP_PLUGIN_DIR . '/powerpress/powerpressadmin.php');
        $rows = 1;
        if (isset($attr['rows'])) {
            $rows = $attr['rows'];
        }
        if (!isset($attr['cols']) || !in_array($attr['cols'], array(1, 2, 3, 4, 6))) {
            $cols = 4;
        } else {
            $cols = $attr['cols'];
        }
        if (!isset($attr['display-title'])) {
            $disp = false;
        }
        else {
            $disp = ($attr['display-title'] == 'true');
        }
        $listId = $attr['id'];
        $props = [];
        if (isset($attr['hover']) && $attr['hover'] == 'true') {
            $props['hover'] = true;
        } else {
            $props['hover'] = false;
        }
        $apiArray = powerpress_get_api_array();
        $apiUrl = $apiArray[0];
        $networkInfo = get_option('powerpress_network');
        $networkInfo['list_id'] = $listId;
        $networkInfo['network_id'] = get_option('powerpress_network_id');
        $networkInfo['network_title'] = get_option('powerpress_network_title');
        $props['network_general'] = get_option('network_general');
        $props['network_map'] = get_option('powerpress_network_map');
        if (!isset($null)) {
            $null = null;
        }
        if (!empty($networkInfo['network_id'])) {
            $networkId = $networkInfo['network_id'];
            $networkTitle = $networkInfo['network_title'];
            $post = false;
            $checkNetwork = PowerpressNetworkDataBus::getSpecificNetworkInAccount($apiUrl, array('post' => $post), $networkInfo, false);
            if (empty ($checkNetwork['error'])) {
                if ($listId != 'all') {
                    $results = PowerpressNetworkDataBus::getSpecificListInNetwork($apiUrl, array('post' => $post), $networkInfo, false);
                    $props['results'] = $results['programs'];
                    $props['list_title'] = $results['list_info']['list_title'];
                    $props['list_desc'] = $results['list_info']['list_description'];
                } else {
                    $results = PowerpressNetworkDataBus::getProgramsInNetwork($apiUrl, array('post' => $post), $networkInfo, false);
                    $props['results'] = $results;
                    $props['list_title'] = 'Something just to pass the condition'; //TODO: Come up with something better than this?
                }

                $props['network_title'] = $networkTitle;
                $props['network_id'] = $networkId;
                $props['post'] = $post;
                $props['cols'] = $cols;
                $props['rows'] = $rows;
                $props['display-title'] = $disp;
                $props['apiUrl'] = $apiUrl;

                $temp = null;
                if (!empty($props['results'])) {
                    foreach ($props['results'] as $program) {
                        if ($listId == 'all' || isset($program['checked']) && $program['checked'] == true) {
                            if (isset($props['network_map']['p-' . $program['program_id']])) {
                                $postId = $props['network_map']['p-' . $program['program_id']];
                                if (get_post_status($postId) == 'publish') {
                                    $program['link'] = get_permalink($postId);
                                } else {
                                    $program['link'] = '#';
                                }
                            } else {
                                $program['link'] = '#';
                            }
                            $temp[] = $program;
                        }
                    }
                }
                $props['results'] = $temp;
                if (empty($props['list_title'])) {
                    return PowerPressNetwork::getHTML('no-list-results.php', $props, null, null);
                } else if (empty($props['results'])) {
                    return PowerPressNetwork::getHTML('list-no-programs.php', $props, null, null);
                } else {
                    return PowerPressNetwork::getHTML('grid-results.php', $props, null, null);
                }
            } else {
                return PowerPressNetwork::getHTML('invalid-network.php', $props, null, null);
            }
        } else {
            return PowerPressNetwork::getHTML('invalid-network.php', $props, null, null);
        }
    }
}

$GLOBALS['ppn-grid'] = new PowerPressNetworkGrid();
