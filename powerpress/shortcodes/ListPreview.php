<?php
//This shortcode is not used
class PowerPressNetworkListPreview extends PowerPressNetworkShortCode
{
    function __construct()
    {
        parent::__construct('ppn-listpreview');
    }

    function ppn_shortcode($attr, $contents)
    {
        plugins_url('/powerpress/powerpress.php','__FILE__');
        plugins_url('/powerpress/powerpressadmin-jquery.php');
        require_once(WP_PLUGIN_DIR . '/powerpress/powerpressadmin.php');

        $list_id = $attr['id'];
        $limit = $attr['limit'];
        $url = $attr['url'];

        //$root = get_root_url();
        $root = "something";

        $props = [];
        $list_link = $root.$url;
        $props['link'] = $list_link;

        $page = get_query_var('paged');
        if($page > 1) {
            $props['paged'] = $page;
        } else {
            $props['start'] = 1;
            $props['paged'] = 1;
        }

        $start = ($props['paged'] - 1) * $limit;

        $props['limit'] = $limit;
        $props['show-paging'] = true;

        $pathParts = explode('/', $_SERVER['REQUEST_URI']);
        $props['link-to'] = "featured/" . $pathParts[2];

        $props['url'] = $url;

        $networkInfo = get_option('powerpress_network');
        $props['network_general'] = get_option('network_general');
        $networkId = $networkInfo['network_id'];
        $networkTitle = $networkInfo['network_title'];
        unset($networkInfo['list_title']);
        unset($networkInfo['list_description']);
        unset($networkInfo['list_id']);

        $post = false;
        $requestUrl = '/2/powerpress/network/'.$networkId.'/lists/'.$list_id.'/programs';
        $results = $GLOBALS['ppn_object']->requestAPI($requestUrl, true, $post);
        $props['network_title'] = $networkTitle;
        $props['network_id'] = $networkId;
        $props['post'] = $post;
        $props['results'] = $results;
        $props['list_title'] = $results['list_title'];
        $props['list_desc'] = $results['list_description'];
        unset($props['results']['list_title']);
        unset($props['results']['list_description']);
        $props['apiUrl'] = $apiUrl;
        $temp = null;
        foreach ($props['results'] as $programs){
            if ($programs['checked']){
                $temp[] = $programs;
            }
        }
        $props['results'] = $temp;
        if (empty($props['list_title'])){
            return PowerPressNetwork::getHTML('no-list-results.php', $props, $null);
        } else if (empty($props['results'])) {
            return PowerPressNetwork::getHTML('list-no-programs.php', $props, $null);
        } else {
            return PowerPressNetwork::getHTML('list-preview.php', $props, $null);
        }
    }
}
$GLOBALS['ppn-listpreview'] = new PowerPressNetworkListPreview();