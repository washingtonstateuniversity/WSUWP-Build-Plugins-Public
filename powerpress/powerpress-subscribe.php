<?php

function powerpresssubscribe_get_subscribe_page($Settings)
{
	if( !empty($Settings['subscribe_page_link_id']) && is_numeric($Settings['subscribe_page_link_id']) )
		return get_page_link($Settings['subscribe_page_link_id']);
	if( !empty($Settings['subscribe_page_link_href']) )
		return $Settings['subscribe_page_link_href'];
	return '';
}

function powerpresssubscribe_get_itunes_url($Settings)
{
	if( !empty($Settings['itunes_url']) )
	{
		// Make URL https://, always add ?mt=2 to end of itunes.apple.com URLs, include l1 to load iTunes store if installed, and always remove uo=X if it's there
		return preg_replace("/^http:\/\//i", "https://", add_query_arg( array('uo' => false, 'mt' => '2', 'ls' => '1'), trim($Settings['itunes_url']) ) );
	}
	
	return '';
}

function powerpresssubscribe_add_guid_to_itunes_url($url, $guid)
{
	if( !empty($guid) && preg_match('/^https:\/\/itunes\.apple\.com.*/i', $url, $matches) ) {
		return $url . '#episodeGuid='. urlencode($guid);
	}
	
	return $url;
}

function powerpresssubscribe_get_settings($ExtraData, $detect_category=true)
{
	$GeneralSettings = get_option('powerpress_general');

	$feed_slug = (empty($ExtraData['feed'])?'podcast': $ExtraData['feed']);
	$post_type = (empty($ExtraData['post_type'])?false: $ExtraData['post_type']);
	$category_id = (empty($ExtraData['cat_id'])?false: $ExtraData['cat_id']);
	$taxonomy_term_id = (empty($ExtraData['taxonomy_term_id'])?false: $ExtraData['taxonomy_term_id']);
	// Special case, strict category specified...
	if( 'podcast' == $feed_slug && empty($post_type) && empty($taxonomy_term_id) && !empty($ExtraData['category']) ) {
		if( !empty($GeneralSettings['cat_casting_strict']) ) // Strict category podcasting, otherwise we let the logic below figure it out!
		{
			$category_id = $ExtraData['category'];
			$ExtraData['subscribe_type'] = 'category';
		}
	}
	
	if( empty($ExtraData['subscribe_type']) ) // Make sure this value is set
		$ExtraData['subscribe_type'] = '';
    if(!isset($GeneralSettings['subscribe_widget_shape'])) {
        $GeneralSettings['subscribe_widget_shape'] = 'squared';
    }
	switch( $ExtraData['subscribe_type'] )
	{
		case 'post_type': {
			$category_id = 0;
			$taxonomy_term_id = 0;
		}; break;
		case 'category': {
			$feed_slug = 'podcast';
			$taxonomy_term_id = 0;
			$post_type = '';
		}; break;
		case 'ttid': {
			$feed_slug = 'podcast';
			$category_id = 0;
			if( empty($post_type) )
				$post_type = get_post_type();
		}; break;
		case 'channel': 
		case 'general': 
		default: {
			$category_id = 0;
			$post_type = '';
			$taxonomy_term_id = 0;
		}; break;
	}
	
	// We need to know if category podcasting is enabled, if it is then we may need to dig deeper for this info....
	if( false != $detect_category && empty($category_id) && !empty($GeneralSettings['cat_casting']) && $feed_slug == 'podcast' && empty($ExtraData['subscribe_type']) )
	{
		if( !$category_id && is_category() )
		{
			$category_id = get_query_var('cat');
		}
		if( !$category_id && is_single() )
		{
			$categories = wp_get_post_categories( get_the_ID() );
			if( count($categories) == 1 ) {
				foreach( $categories as $null=> $category_id ) {
					break;
				}
			}
			if( !empty($category_id) )
			{
				$Settings = get_option('powerpress_cat_feed_'.$category_id );
				// Check if it is a podcast category...
				if( !empty($Settings) ) {
					$ExtraData['subscribe_type'] = 'category';
				} else {
					$category_id = false; // Unset it!
				}
			}
		}
	}
	
	// Category
	if( !empty($category_id) && $ExtraData['subscribe_type'] == 'category' )
	{
		$Settings = get_option('powerpress_cat_feed_'.$category_id );
		
		if( !empty($Settings) )
		{
			if( empty($Settings['title']) ) {
				$Settings['title'] = get_cat_name( $category_id ); // Get category title
				$Settings['title'] .= ' '. apply_filters( 'document_title_separator', '-' ) .' ';
				$Settings['title'] .= get_bloginfo_rss('name');
			}
			if( empty($Settings['title']) )	
				$Settings['title'] = get_bloginfo_rss('name'); // Get blog title, best we can do
			if( !empty($Settings['feed_redirect_url']) )
				$Settings['feed_url'] = $Settings['feed_redirect_url'];
			else if( !empty($General['cat_casting_podcast_feeds']) )
				$Settings['feed_url'] = get_category_feed_link($category_id, 'podcast');
			else
				$Settings['feed_url'] = get_category_feed_link( $category_id ); // Get category feed URL
			
			$Settings['subscribe_page_url'] = powerpresssubscribe_get_subscribe_page($Settings);
			$Settings['itunes_url'] = powerpresssubscribe_get_itunes_url($Settings);
			$Settings['image_url'] = $Settings['itunes_image'];
			$Settings['subscribe_feature_email'] = (isset($GeneralSettings['subscribe_feature_email']) ? $GeneralSettings['subscribe_feature_email'] : false );
            $Settings['subscribe_feature_apple'] = (isset($GeneralSettings['subscribe_feature_apple']) ? $GeneralSettings['subscribe_feature_apple'] : true );
			$Settings['subscribe_feature_gp'] = (isset($GeneralSettings['subscribe_feature_gp']) ? $GeneralSettings['subscribe_feature_gp'] : false );
			$Settings['subscribe_feature_stitcher'] = (isset($GeneralSettings['subscribe_feature_stitcher']) ? $GeneralSettings['subscribe_feature_stitcher'] : false );
			$Settings['subscribe_feature_tunein'] = (isset($GeneralSettings['subscribe_feature_tunein']) ? $GeneralSettings['subscribe_feature_tunein'] : false );
			$Settings['subscribe_feature_spotify'] = (isset($GeneralSettings['subscribe_feature_spotify']) ? $GeneralSettings['subscribe_feature_spotify'] : false );
            $Settings['subscribe_feature_iheart'] = (isset($GeneralSettings['subscribe_feature_iheart']) ? $GeneralSettings['subscribe_feature_iheart'] : false );
            $Settings['subscribe_feature_deezer'] = (isset($GeneralSettings['subscribe_feature_deezer']) ? $GeneralSettings['subscribe_feature_deezer'] : false );
            $Settings['subscribe_feature_pandora'] = (isset($GeneralSettings['subscribe_feature_pandora']) ? $GeneralSettings['subscribe_feature_pandora'] : false );
            $Settings['subscribe_feature_android'] = (isset($GeneralSettings['subscribe_feature_android']) ? $GeneralSettings['subscribe_feature_android'] : false );
            $Settings['subscribe_feature_blubrry'] = (isset($GeneralSettings['subscribe_feature_blubrry']) ? $GeneralSettings['subscribe_feature_blubrry'] : false );
            $Settings['subscribe_feature_amazon'] = (isset($GeneralSettings['subscribe_feature_amazon']) ? $GeneralSettings['subscribe_feature_amazon'] : false );
            $Settings['subscribe_feature_pcindex'] = (isset($GeneralSettings['subscribe_feature_pcindex']) ? $GeneralSettings['subscribe_feature_pcindex'] : false );
            $Settings['subscribe_feature_jiosaavn'] = (isset($GeneralSettings['subscribe_feature_jiosaavn']) ? $GeneralSettings['subscribe_feature_jiosaavn'] : false );
            $Settings['subscribe_feature_podchaser'] = (isset($GeneralSettings['subscribe_feature_podchaser']) ? $GeneralSettings['subscribe_feature_podchaser'] : false );
            $Settings['subscribe_feature_gaana'] = (isset($GeneralSettings['subscribe_feature_gaana']) ? $GeneralSettings['subscribe_feature_gaana'] : false );
            $Settings['subscribe_feature_email_shortcode'] = (isset($GeneralSettings['subscribe_feature_email_shortcode']) ? $GeneralSettings['subscribe_feature_email_shortcode'] : true );
            $Settings['subscribe_feature_apple_shortcode'] = (isset($GeneralSettings['subscribe_feature_apple_shortcode']) ? $GeneralSettings['subscribe_feature_apple_shortcode'] : true );
            $Settings['subscribe_feature_gp_shortcode'] = (isset($GeneralSettings['subscribe_feature_gp_shortcode']) ? $GeneralSettings['subscribe_feature_gp_shortcode'] : true );
            $Settings['subscribe_feature_stitcher_shortcode'] = (isset($GeneralSettings['subscribe_feature_stitcher_shortcode']) ? $GeneralSettings['subscribe_feature_stitcher_shortcode'] : true );
            $Settings['subscribe_feature_tunein_shortcode'] = (isset($GeneralSettings['subscribe_feature_tunein_shortcode']) ? $GeneralSettings['subscribe_feature_tunein_shortcode'] : true );
            $Settings['subscribe_feature_spotify_shortcode'] = (isset($GeneralSettings['subscribe_feature_spotify_shortcode']) ? $GeneralSettings['subscribe_feature_spotify_shortcode'] : true );
            $Settings['subscribe_feature_android_shortcode'] = (isset($GeneralSettings['subscribe_feature_android_shortcode']) ? $GeneralSettings['subscribe_feature_android_shortcode'] : true );
            $Settings['subscribe_feature_blubrry_shortcode'] = (isset($GeneralSettings['subscribe_feature_blubrry_shortcode']) ? $GeneralSettings['subscribe_feature_blubrry_shortcode'] : true );
            $Settings['subscribe_feature_iheart_shortcode'] = (isset($GeneralSettings['subscribe_feature_iheart_shortcode']) ? $GeneralSettings['subscribe_feature_iheart_shortcode'] : true );
            $Settings['subscribe_feature_deezer_shortcode'] = (isset($GeneralSettings['subscribe_feature_deezer_shortcode']) ? $GeneralSettings['subscribe_feature_deezer_shortcode'] : true );
            $Settings['subscribe_feature_pandora_shortcode'] = (isset($GeneralSettings['subscribe_feature_pandora_shortcode']) ? $GeneralSettings['subscribe_feature_pandora_shortcode'] : true );
            $Settings['subscribe_feature_amazon_shortcode'] = (isset($GeneralSettings['subscribe_feature_amazon_shortcode']) ? $GeneralSettings['subscribe_feature_amazon_shortcode'] : true );
            $Settings['subscribe_feature_pcindex_shortcode'] = (isset($GeneralSettings['subscribe_feature_pcindex_shortcode']) ? $GeneralSettings['subscribe_feature_pcindex_shortcode'] : true );
            $Settings['subscribe_feature_jiosaavn_shortcode'] = (isset($GeneralSettings['subscribe_feature_jiosaavn_shortcode']) ? $GeneralSettings['subscribe_feature_jiosaavn_shortcode'] : true );
            $Settings['subscribe_feature_podchaser_shortcode'] = (isset($GeneralSettings['subscribe_feature_podchaser_shortcode']) ? $GeneralSettings['subscribe_feature_podchaser_shortcode'] : true );
            $Settings['subscribe_feature_gaana_shortcode'] = (isset($GeneralSettings['subscribe_feature_gaana_shortcode']) ? $GeneralSettings['subscribe_feature_gaana_shortcode'] : true );
            $Settings['subscribe_feature_email_sidebar'] = (isset($GeneralSettings['subscribe_feature_email_sidebar']) ? $GeneralSettings['subscribe_feature_email_sidebar'] : true );
            $Settings['subscribe_feature_apple_sidebar'] = (isset($GeneralSettings['subscribe_feature_apple_sidebar']) ? $GeneralSettings['subscribe_feature_apple_sidebar'] : true );
            $Settings['subscribe_feature_gp_sidebar'] = (isset($GeneralSettings['subscribe_feature_gp_sidebar']) ? $GeneralSettings['subscribe_feature_gp_sidebar'] : true );
            $Settings['subscribe_feature_stitcher_sidebar'] = (isset($GeneralSettings['subscribe_feature_stitcher_sidebar']) ? $GeneralSettings['subscribe_feature_stitcher_sidebar'] : false );
            $Settings['subscribe_feature_tunein_sidebar'] = (isset($GeneralSettings['subscribe_feature_tunein_sidebar']) ? $GeneralSettings['subscribe_feature_tunein_sidebar'] : false );
            $Settings['subscribe_feature_spotify_sidebar'] = (isset($GeneralSettings['subscribe_feature_spotify_sidebar']) ? $GeneralSettings['subscribe_feature_spotify_sidebar'] : false );
            $Settings['subscribe_feature_iheart_sidebar'] = (isset($GeneralSettings['subscribe_feature_iheart_sidebar']) ? $GeneralSettings['subscribe_feature_iheart_sidebar'] : false );
            $Settings['subscribe_feature_deezer_sidebar'] = (isset($GeneralSettings['subscribe_feature_deezer_sidebar']) ? $GeneralSettings['subscribe_feature_deezer_sidebar'] : false );
            $Settings['subscribe_feature_pandora_sidebar'] = (isset($GeneralSettings['subscribe_feature_pandora_sidebar']) ? $GeneralSettings['subscribe_feature_pandora_sidebar'] : false );
            $Settings['subscribe_feature_android_sidebar'] = (isset($GeneralSettings['subscribe_feature_android_sidebar']) ? $GeneralSettings['subscribe_feature_android_sidebar'] : true );
            $Settings['subscribe_feature_blubrry_sidebar'] = (isset($GeneralSettings['subscribe_feature_blubrry_sidebar']) ? $GeneralSettings['subscribe_feature_blubrry_sidebar'] : false );
            $Settings['subscribe_feature_amazon_sidebar'] = (isset($GeneralSettings['subscribe_feature_amazon_sidebar']) ? $GeneralSettings['subscribe_feature_amazon_sidebar'] : false );
            $Settings['subscribe_feature_pcindex_sidebar'] = (isset($GeneralSettings['subscribe_feature_pcindex_sidebar']) ? $GeneralSettings['subscribe_feature_pcindex_sidebar'] : false );
            $Settings['subscribe_feature_jiosaavn_sidebar'] = (isset($GeneralSettings['subscribe_feature_jiosaavn_sidebar']) ? $GeneralSettings['subscribe_feature_jiosaavn_sidebar'] : false );
            $Settings['subscribe_feature_podchaser_sidebar'] = (isset($GeneralSettings['subscribe_feature_podchaser_sidebar']) ? $GeneralSettings['subscribe_feature_podchaser_sidebar'] : false );
            $Settings['subscribe_feature_gaana_sidebar'] = (isset($GeneralSettings['subscribe_feature_gaana_sidebar']) ? $GeneralSettings['subscribe_feature_gaana_sidebar'] : false );
            if (isset($GeneralSettings['subscribe_no_important_styling'])) {
                $Settings['subscribe_no_important_styling'] = $GeneralSettings['subscribe_no_important_styling'];
            } else {
                $Settings['subscribe_no_important_styling'] = 'include';
            }
            return $Settings;
		}
		
		return false; // If we specifically wanted a category, then we need to return false so we don't miss-represent
	}
	
	// Taxonomy
	if( $ExtraData['subscribe_type'] == 'ttid' )
	{
		if( !empty($GeneralSettings['taxonomy_podcasting']) && !empty($taxonomy_term_id) )
		{
			$term_ID = '';
			$taxonomy_type = '';
			$Settings = get_option('powerpress_taxonomy_'. intval($taxonomy_term_id) );
			if( !empty($Settings) ) {
				global $wpdb;
				$term_info = $wpdb->get_results("SELECT term_id, taxonomy FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id = {$taxonomy_term_id} LIMIT 1",  ARRAY_A);
				if( !empty( $term_info[0]['term_id']) ) {
					$term_ID = $term_info[0]['term_id'];
					$taxonomy_type = $term_info[0]['taxonomy'];
				}
			}
			
			if( empty($term_ID) || empty($taxonomy_type) )
				return false;
			
			if( !empty($Settings['feed_redirect_url']) )
				$Settings['feed_url'] = $Settings['feed_redirect_url'];
			if( empty($General['feed_url']) )
				$Settings['feed_url'] = get_term_feed_link($term_ID, $taxonomy_type, 'rss2');
			
			$Settings['subscribe_page_url'] = powerpresssubscribe_get_subscribe_page($Settings);
			$Settings['itunes_url'] = powerpresssubscribe_get_itunes_url($Settings);
			$Settings['image_url'] = $Settings['itunes_image'];
            $Settings['subscribe_feature_email'] = (isset($GeneralSettings['subscribe_feature_email']) ? $GeneralSettings['subscribe_feature_email'] : false );
            $Settings['subscribe_feature_apple'] = (isset($GeneralSettings['subscribe_feature_apple']) ? $GeneralSettings['subscribe_feature_apple'] : true );
            $Settings['subscribe_feature_gp'] = (isset($GeneralSettings['subscribe_feature_gp']) ? $GeneralSettings['subscribe_feature_gp'] : false );
            $Settings['subscribe_feature_stitcher'] = (isset($GeneralSettings['subscribe_feature_stitcher']) ? $GeneralSettings['subscribe_feature_stitcher'] : false );
            $Settings['subscribe_feature_tunein'] = (isset($GeneralSettings['subscribe_feature_tunein']) ? $GeneralSettings['subscribe_feature_tunein'] : false );
            $Settings['subscribe_feature_spotify'] = (isset($GeneralSettings['subscribe_feature_spotify']) ? $GeneralSettings['subscribe_feature_spotify'] : false );
            $Settings['subscribe_feature_iheart'] = (isset($GeneralSettings['subscribe_feature_iheart']) ? $GeneralSettings['subscribe_feature_iheart'] : false );
            $Settings['subscribe_feature_deezer'] = (isset($GeneralSettings['subscribe_feature_deezer']) ? $GeneralSettings['subscribe_feature_deezer'] : false );
            $Settings['subscribe_feature_pandora'] = (isset($GeneralSettings['subscribe_feature_pandora']) ? $GeneralSettings['subscribe_feature_pandora'] : false );
            $Settings['subscribe_feature_android'] = (isset($GeneralSettings['subscribe_feature_android']) ? $GeneralSettings['subscribe_feature_android'] : false );
            $Settings['subscribe_feature_blubrry'] = (isset($GeneralSettings['subscribe_feature_blubrry']) ? $GeneralSettings['subscribe_feature_blubrry'] : false );
            $Settings['subscribe_feature_amazon'] = (isset($GeneralSettings['subscribe_feature_amazon']) ? $GeneralSettings['subscribe_feature_amazon'] : false );
            $Settings['subscribe_feature_pcindex'] = (isset($GeneralSettings['subscribe_feature_pcindex']) ? $GeneralSettings['subscribe_feature_pcindex'] : false );
            $Settings['subscribe_feature_jiosaavn'] = (isset($GeneralSettings['subscribe_feature_jiosaavn']) ? $GeneralSettings['subscribe_feature_jiosaavn'] : false );
            $Settings['subscribe_feature_podchaser'] = (isset($GeneralSettings['subscribe_feature_podchaser']) ? $GeneralSettings['subscribe_feature_podchaser'] : false );
            $Settings['subscribe_feature_gaana'] = (isset($GeneralSettings['subscribe_feature_gaana']) ? $GeneralSettings['subscribe_feature_gaana'] : false );
            $Settings['subscribe_feature_email_shortcode'] = (isset($GeneralSettings['subscribe_feature_email_shortcode']) ? $GeneralSettings['subscribe_feature_email_shortcode'] : true );
            $Settings['subscribe_feature_apple_shortcode'] = (isset($GeneralSettings['subscribe_feature_apple_shortcode']) ? $GeneralSettings['subscribe_feature_apple_shortcode'] : true );
            $Settings['subscribe_feature_gp_shortcode'] = (isset($GeneralSettings['subscribe_feature_gp_shortcode']) ? $GeneralSettings['subscribe_feature_gp_shortcode'] : true );
            $Settings['subscribe_feature_stitcher_shortcode'] = (isset($GeneralSettings['subscribe_feature_stitcher_shortcode']) ? $GeneralSettings['subscribe_feature_stitcher_shortcode'] : true );
            $Settings['subscribe_feature_tunein_shortcode'] = (isset($GeneralSettings['subscribe_feature_tunein_shortcode']) ? $GeneralSettings['subscribe_feature_tunein_shortcode'] : true );
            $Settings['subscribe_feature_spotify_shortcode'] = (isset($GeneralSettings['subscribe_feature_spotify_shortcode']) ? $GeneralSettings['subscribe_feature_spotify_shortcode'] : true );
            $Settings['subscribe_feature_android_shortcode'] = (isset($GeneralSettings['subscribe_feature_android_shortcode']) ? $GeneralSettings['subscribe_feature_android_shortcode'] : true );
            $Settings['subscribe_feature_blubrry_shortcode'] = (isset($GeneralSettings['subscribe_feature_blubrry_shortcode']) ? $GeneralSettings['subscribe_feature_blubrry_shortcode'] : true );
            $Settings['subscribe_feature_iheart_shortcode'] = (isset($GeneralSettings['subscribe_feature_iheart_shortcode']) ? $GeneralSettings['subscribe_feature_iheart_shortcode'] : true );
            $Settings['subscribe_feature_deezer_shortcode'] = (isset($GeneralSettings['subscribe_feature_deezer_shortcode']) ? $GeneralSettings['subscribe_feature_deezer_shortcode'] : true );
            $Settings['subscribe_feature_pandora_shortcode'] = (isset($GeneralSettings['subscribe_feature_pandora_shortcode']) ? $GeneralSettings['subscribe_feature_pandora_shortcode'] : true );
            $Settings['subscribe_feature_amazon_shortcode'] = (isset($GeneralSettings['subscribe_feature_amazon_shortcode']) ? $GeneralSettings['subscribe_feature_amazon_shortcode'] : true );
            $Settings['subscribe_feature_pcindex_shortcode'] = (isset($GeneralSettings['subscribe_feature_pcindex_shortcode']) ? $GeneralSettings['subscribe_feature_pcindex_shortcode'] : true );
            $Settings['subscribe_feature_jiosaavn_shortcode'] = (isset($GeneralSettings['subscribe_feature_jiosaavn_shortcode']) ? $GeneralSettings['subscribe_feature_jiosaavn_shortcode'] : true );
            $Settings['subscribe_feature_podchaser_shortcode'] = (isset($GeneralSettings['subscribe_feature_podchaser_shortcode']) ? $GeneralSettings['subscribe_feature_podchaser_shortcode'] : true );
            $Settings['subscribe_feature_gaana_shortcode'] = (isset($GeneralSettings['subscribe_feature_gaana_shortcode']) ? $GeneralSettings['subscribe_feature_gaana_shortcode'] : true );
            $Settings['subscribe_feature_email_sidebar'] = (isset($GeneralSettings['subscribe_feature_email_sidebar']) ? $GeneralSettings['subscribe_feature_email_sidebar'] : true );
            $Settings['subscribe_feature_apple_sidebar'] = (isset($GeneralSettings['subscribe_feature_apple_sidebar']) ? $GeneralSettings['subscribe_feature_apple_sidebar'] : true );
            $Settings['subscribe_feature_gp_sidebar'] = (isset($GeneralSettings['subscribe_feature_gp_sidebar']) ? $GeneralSettings['subscribe_feature_gp_sidebar'] : true );
            $Settings['subscribe_feature_stitcher_sidebar'] = (isset($GeneralSettings['subscribe_feature_stitcher_sidebar']) ? $GeneralSettings['subscribe_feature_stitcher_sidebar'] : false );
            $Settings['subscribe_feature_tunein_sidebar'] = (isset($GeneralSettings['subscribe_feature_tunein_sidebar']) ? $GeneralSettings['subscribe_feature_tunein_sidebar'] : false );
            $Settings['subscribe_feature_spotify_sidebar'] = (isset($GeneralSettings['subscribe_feature_spotify_sidebar']) ? $GeneralSettings['subscribe_feature_spotify_sidebar'] : false );
            $Settings['subscribe_feature_iheart_sidebar'] = (isset($GeneralSettings['subscribe_feature_iheart_sidebar']) ? $GeneralSettings['subscribe_feature_iheart_sidebar'] : false );
            $Settings['subscribe_feature_deezer_sidebar'] = (isset($GeneralSettings['subscribe_feature_deezer_sidebar']) ? $GeneralSettings['subscribe_feature_deezer_sidebar'] : false );
            $Settings['subscribe_feature_pandora_sidebar'] = (isset($GeneralSettings['subscribe_feature_pandora_sidebar']) ? $GeneralSettings['subscribe_feature_pandora_sidebar'] : false );
            $Settings['subscribe_feature_android_sidebar'] = (isset($GeneralSettings['subscribe_feature_android_sidebar']) ? $GeneralSettings['subscribe_feature_android_sidebar'] : true );
            $Settings['subscribe_feature_blubrry_sidebar'] = (isset($GeneralSettings['subscribe_feature_blubrry_sidebar']) ? $GeneralSettings['subscribe_feature_blubrry_sidebar'] : false );
            $Settings['subscribe_feature_amazon_sidebar'] = (isset($GeneralSettings['subscribe_feature_amazon_sidebar']) ? $GeneralSettings['subscribe_feature_amazon_sidebar'] : false );
            $Settings['subscribe_feature_pcindex_sidebar'] = (isset($GeneralSettings['subscribe_feature_pcindex_sidebar']) ? $GeneralSettings['subscribe_feature_pcindex_sidebar'] : false );
            $Settings['subscribe_feature_jiosaavn_sidebar'] = (isset($GeneralSettings['subscribe_feature_jiosaavn_sidebar']) ? $GeneralSettings['subscribe_feature_jiosaavn_sidebar'] : false );
            $Settings['subscribe_feature_podchaser_sidebar'] = (isset($GeneralSettings['subscribe_feature_podchaser_sidebar']) ? $GeneralSettings['subscribe_feature_podchaser_sidebar'] : false );
            $Settings['subscribe_feature_gaana_sidebar'] = (isset($GeneralSettings['subscribe_feature_gaana_sidebar']) ? $GeneralSettings['subscribe_feature_gaana_sidebar'] : false );
            if (isset($GeneralSettings['subscribe_no_important_styling'])) {
                $Settings['subscribe_no_important_styling'] = $GeneralSettings['subscribe_no_important_styling'];
            } else {
                $Settings['subscribe_no_important_styling'] = 'include';
            }
			return $Settings;
		}
		return false;
	}
	
	// Post Type Podcasting
	if( $ExtraData['subscribe_type'] == 'post_type' )
	{
		if( !empty($GeneralSettings['posttype_podcasting']) )
		{
			if( empty($post_type) && !empty($ExtraData['id']) )
				$post_type = get_post_type( $ExtraData['id'] );
			
			switch( $post_type )
			{
				case 'page':
				case 'post':
				{
					// SWEET, CARRY ON!
				}; break;
				default: {
					$SettingsArray = get_option('powerpress_posttype_'.$post_type);
					$Settings = false;
					if( !empty($SettingsArray[ $feed_slug ]) )
						$Settings = $SettingsArray[ $feed_slug ];
					
					if( !empty($Settings) )
					{
						$Settings['title'] = $Settings['title'];
						if( empty($Settings['title']) ) {
							$obj = get_post_type_object($post_type);
							if( !empty($obj->labels->singular_name) )
								$Settings['title'] = $obj->labels->singular_name;
						}
						if( empty($Settings['title']) ) {
							$Settings['title'] = get_bloginfo_rss('name');
						}
						if( !empty($Settings['feed_redirect_url']) )
							$Settings['feed_url'] = $Settings['feed_redirect_url'];
						else
							$Settings['feed_url'] = get_post_type_archive_feed_link($post_type, $feed_slug); // Get post type feed URL
						$Settings['subscribe_page_url'] = powerpresssubscribe_get_subscribe_page($Settings);
						$Settings['itunes_url'] = powerpresssubscribe_get_itunes_url($Settings);
						$Settings['image_url'] = $Settings['itunes_image'];
                        $Settings['subscribe_feature_email'] = (isset($GeneralSettings['subscribe_feature_email']) ? $GeneralSettings['subscribe_feature_email'] : false );
                        $Settings['subscribe_feature_apple'] = (isset($GeneralSettings['subscribe_feature_apple']) ? $GeneralSettings['subscribe_feature_apple'] : true );
                        $Settings['subscribe_feature_gp'] = (isset($GeneralSettings['subscribe_feature_gp']) ? $GeneralSettings['subscribe_feature_gp'] : false );
                        $Settings['subscribe_feature_stitcher'] = (isset($GeneralSettings['subscribe_feature_stitcher']) ? $GeneralSettings['subscribe_feature_stitcher'] : false );
                        $Settings['subscribe_feature_tunein'] = (isset($GeneralSettings['subscribe_feature_tunein']) ? $GeneralSettings['subscribe_feature_tunein'] : false );
                        $Settings['subscribe_feature_spotify'] = (isset($GeneralSettings['subscribe_feature_spotify']) ? $GeneralSettings['subscribe_feature_spotify'] : false );
                        $Settings['subscribe_feature_iheart'] = (isset($GeneralSettings['subscribe_feature_iheart']) ? $GeneralSettings['subscribe_feature_iheart'] : false );
                        $Settings['subscribe_feature_deezer'] = (isset($GeneralSettings['subscribe_feature_deezer']) ? $GeneralSettings['subscribe_feature_deezer'] : false );
                        $Settings['subscribe_feature_pandora'] = (isset($GeneralSettings['subscribe_feature_pandora']) ? $GeneralSettings['subscribe_feature_pandora'] : false );
                        $Settings['subscribe_feature_android'] = (isset($GeneralSettings['subscribe_feature_android']) ? $GeneralSettings['subscribe_feature_android'] : false );
                        $Settings['subscribe_feature_blubrry'] = (isset($GeneralSettings['subscribe_feature_blubrry']) ? $GeneralSettings['subscribe_feature_blubrry'] : false );
                        $Settings['subscribe_feature_amazon'] = (isset($GeneralSettings['subscribe_feature_amazon']) ? $GeneralSettings['subscribe_feature_amazon'] : false );
                        $Settings['subscribe_feature_pcindex'] = (isset($GeneralSettings['subscribe_feature_pcindex']) ? $GeneralSettings['subscribe_feature_pcindex'] : false );
                        $Settings['subscribe_feature_jiosaavn'] = (isset($GeneralSettings['subscribe_feature_jiosaavn']) ? $GeneralSettings['subscribe_feature_jiosaavn'] : false );
                        $Settings['subscribe_feature_podchaser'] = (isset($GeneralSettings['subscribe_feature_podchaser']) ? $GeneralSettings['subscribe_feature_podchaser'] : false );
                        $Settings['subscribe_feature_gaana'] = (isset($GeneralSettings['subscribe_feature_gaana']) ? $GeneralSettings['subscribe_feature_gaana'] : false );
                        $Settings['subscribe_feature_email_shortcode'] = (isset($GeneralSettings['subscribe_feature_email_shortcode']) ? $GeneralSettings['subscribe_feature_email_shortcode'] : true );
                        $Settings['subscribe_feature_apple_shortcode'] = (isset($GeneralSettings['subscribe_feature_apple_shortcode']) ? $GeneralSettings['subscribe_feature_apple_shortcode'] : true );
                        $Settings['subscribe_feature_gp_shortcode'] = (isset($GeneralSettings['subscribe_feature_gp_shortcode']) ? $GeneralSettings['subscribe_feature_gp_shortcode'] : true );
                        $Settings['subscribe_feature_stitcher_shortcode'] = (isset($GeneralSettings['subscribe_feature_stitcher_shortcode']) ? $GeneralSettings['subscribe_feature_stitcher_shortcode'] : true );
                        $Settings['subscribe_feature_tunein_shortcode'] = (isset($GeneralSettings['subscribe_feature_tunein_shortcode']) ? $GeneralSettings['subscribe_feature_tunein_shortcode'] : true );
                        $Settings['subscribe_feature_spotify_shortcode'] = (isset($GeneralSettings['subscribe_feature_spotify_shortcode']) ? $GeneralSettings['subscribe_feature_spotify_shortcode'] : true );
                        $Settings['subscribe_feature_android_shortcode'] = (isset($GeneralSettings['subscribe_feature_android_shortcode']) ? $GeneralSettings['subscribe_feature_android_shortcode'] : true );
                        $Settings['subscribe_feature_blubrry_shortcode'] = (isset($GeneralSettings['subscribe_feature_blubrry_shortcode']) ? $GeneralSettings['subscribe_feature_blubrry_shortcode'] : true );
                        $Settings['subscribe_feature_iheart_shortcode'] = (isset($GeneralSettings['subscribe_feature_iheart_shortcode']) ? $GeneralSettings['subscribe_feature_iheart_shortcode'] : true );
                        $Settings['subscribe_feature_deezer_shortcode'] = (isset($GeneralSettings['subscribe_feature_deezer_shortcode']) ? $GeneralSettings['subscribe_feature_deezer_shortcode'] : true );
                        $Settings['subscribe_feature_pandora_shortcode'] = (isset($GeneralSettings['subscribe_feature_pandora_shortcode']) ? $GeneralSettings['subscribe_feature_pandora_shortcode'] : true );
                        $Settings['subscribe_feature_amazon_shortcode'] = (isset($GeneralSettings['subscribe_feature_amazon_shortcode']) ? $GeneralSettings['subscribe_feature_amazon_shortcode'] : true );
                        $Settings['subscribe_feature_pcindex_shortcode'] = (isset($GeneralSettings['subscribe_feature_pcindex_shortcode']) ? $GeneralSettings['subscribe_feature_pcindex_shortcode'] : true );
                        $Settings['subscribe_feature_jiosaavn_shortcode'] = (isset($GeneralSettings['subscribe_feature_jiosaavn_shortcode']) ? $GeneralSettings['subscribe_feature_jiosaavn_shortcode'] : true );
                        $Settings['subscribe_feature_podchaser_shortcode'] = (isset($GeneralSettings['subscribe_feature_podchaser_shortcode']) ? $GeneralSettings['subscribe_feature_podchaser_shortcode'] : true );
                        $Settings['subscribe_feature_gaana_shortcode'] = (isset($GeneralSettings['subscribe_feature_gaana_shortcode']) ? $GeneralSettings['subscribe_feature_gaana_shortcode'] : true );
                        $Settings['subscribe_feature_email_sidebar'] = (isset($GeneralSettings['subscribe_feature_email_sidebar']) ? $GeneralSettings['subscribe_feature_email_sidebar'] : true );
                        $Settings['subscribe_feature_apple_sidebar'] = (isset($GeneralSettings['subscribe_feature_apple_sidebar']) ? $GeneralSettings['subscribe_feature_apple_sidebar'] : true );
                        $Settings['subscribe_feature_gp_sidebar'] = (isset($GeneralSettings['subscribe_feature_gp_sidebar']) ? $GeneralSettings['subscribe_feature_gp_sidebar'] : true );
                        $Settings['subscribe_feature_stitcher_sidebar'] = (isset($GeneralSettings['subscribe_feature_stitcher_sidebar']) ? $GeneralSettings['subscribe_feature_stitcher_sidebar'] : false );
                        $Settings['subscribe_feature_tunein_sidebar'] = (isset($GeneralSettings['subscribe_feature_tunein_sidebar']) ? $GeneralSettings['subscribe_feature_tunein_sidebar'] : false );
                        $Settings['subscribe_feature_spotify_sidebar'] = (isset($GeneralSettings['subscribe_feature_spotify_sidebar']) ? $GeneralSettings['subscribe_feature_spotify_sidebar'] : false );
                        $Settings['subscribe_feature_iheart_sidebar'] = (isset($GeneralSettings['subscribe_feature_iheart_sidebar']) ? $GeneralSettings['subscribe_feature_iheart_sidebar'] : false );
                        $Settings['subscribe_feature_deezer_sidebar'] = (isset($GeneralSettings['subscribe_feature_deezer_sidebar']) ? $GeneralSettings['subscribe_feature_deezer_sidebar'] : false );
                        $Settings['subscribe_feature_pandora_sidebar'] = (isset($GeneralSettings['subscribe_feature_pandora_sidebar']) ? $GeneralSettings['subscribe_feature_pandora_sidebar'] : false );
                        $Settings['subscribe_feature_android_sidebar'] = (isset($GeneralSettings['subscribe_feature_android_sidebar']) ? $GeneralSettings['subscribe_feature_android_sidebar'] : true );
                        $Settings['subscribe_feature_blubrry_sidebar'] = (isset($GeneralSettings['subscribe_feature_blubrry_sidebar']) ? $GeneralSettings['subscribe_feature_blubrry_sidebar'] : false );
                        $Settings['subscribe_feature_amazon_sidebar'] = (isset($GeneralSettings['subscribe_feature_amazon_sidebar']) ? $GeneralSettings['subscribe_feature_amazon_sidebar'] : false );
                        $Settings['subscribe_feature_pcindex_sidebar'] = (isset($GeneralSettings['subscribe_feature_pcindex_sidebar']) ? $GeneralSettings['subscribe_feature_pcindex_sidebar'] : false );
                        $Settings['subscribe_feature_jiosaavn_sidebar'] = (isset($GeneralSettings['subscribe_feature_jiosaavn_sidebar']) ? $GeneralSettings['subscribe_feature_jiosaavn_sidebar'] : false );
                        $Settings['subscribe_feature_podchaser_sidebar'] = (isset($GeneralSettings['subscribe_feature_podchaser_sidebar']) ? $GeneralSettings['subscribe_feature_podchaser_sidebar'] : false );
                        $Settings['subscribe_feature_gaana_sidebar'] = (isset($GeneralSettings['subscribe_feature_gaana_sidebar']) ? $GeneralSettings['subscribe_feature_gaana_sidebar'] : false );
                        if (isset($GeneralSettings['subscribe_no_important_styling'])) {
                            $Settings['subscribe_no_important_styling'] = $GeneralSettings['subscribe_no_important_styling'];
                        } else {
                            $Settings['subscribe_no_important_styling'] = 'include';
                        }
                        return $Settings;
					}
				}; break;
			}
		}
		
		return false;
	}
	
	
	// Podcast default and channel feed settings
	$Settings = get_option('powerpress_feed_'. $feed_slug);
	
	if( empty($Settings) && $feed_slug == 'podcast' )
		$Settings = get_option('powerpress_feed'); // Get the main feed settings
	
	if( !empty($Settings) )
	{
		if( empty($Settings['title']) ) {
			$Settings['title'] = get_bloginfo_rss('name'); // Get blog title
		}
		if( !empty($Settings['feed_redirect_url']) )
			$Settings['feed_url'] = $Settings['feed_redirect_url'];
		else
			$Settings['feed_url'] =  get_feed_link($feed_slug); // Get Podcast RSS Feed
		$Settings['subscribe_page_url'] = powerpresssubscribe_get_subscribe_page($Settings);
		$Settings['itunes_url'] = powerpresssubscribe_get_itunes_url($Settings);
		$Settings['image_url'] = $Settings['itunes_image'];
        $Settings['subscribe_feature_email'] = (isset($GeneralSettings['subscribe_feature_email']) ? $GeneralSettings['subscribe_feature_email'] : false );
        $Settings['subscribe_feature_apple'] = (isset($GeneralSettings['subscribe_feature_apple']) ? $GeneralSettings['subscribe_feature_apple'] : true );
        $Settings['subscribe_feature_gp'] = (isset($GeneralSettings['subscribe_feature_gp']) ? $GeneralSettings['subscribe_feature_gp'] : false );
        $Settings['subscribe_feature_stitcher'] = (isset($GeneralSettings['subscribe_feature_stitcher']) ? $GeneralSettings['subscribe_feature_stitcher'] : false );
        $Settings['subscribe_feature_tunein'] = (isset($GeneralSettings['subscribe_feature_tunein']) ? $GeneralSettings['subscribe_feature_tunein'] : false );
        $Settings['subscribe_feature_spotify'] = (isset($GeneralSettings['subscribe_feature_spotify']) ? $GeneralSettings['subscribe_feature_spotify'] : false );
        $Settings['subscribe_feature_iheart'] = (isset($GeneralSettings['subscribe_feature_iheart']) ? $GeneralSettings['subscribe_feature_iheart'] : false );
        $Settings['subscribe_feature_deezer'] = (isset($GeneralSettings['subscribe_feature_deezer']) ? $GeneralSettings['subscribe_feature_deezer'] : false );
        $Settings['subscribe_feature_pandora'] = (isset($GeneralSettings['subscribe_feature_pandora']) ? $GeneralSettings['subscribe_feature_pandora'] : false );
        $Settings['subscribe_feature_android'] = (isset($GeneralSettings['subscribe_feature_android']) ? $GeneralSettings['subscribe_feature_android'] : false );
        $Settings['subscribe_feature_blubrry'] = (isset($GeneralSettings['subscribe_feature_blubrry']) ? $GeneralSettings['subscribe_feature_blubrry'] : false );
        $Settings['subscribe_feature_amazon'] = (isset($GeneralSettings['subscribe_feature_amazon']) ? $GeneralSettings['subscribe_feature_amazon'] : false );
        $Settings['subscribe_feature_pcindex'] = (isset($GeneralSettings['subscribe_feature_pcindex']) ? $GeneralSettings['subscribe_feature_pcindex'] : false );
        $Settings['subscribe_feature_jiosaavn'] = (isset($GeneralSettings['subscribe_feature_jiosaavn']) ? $GeneralSettings['subscribe_feature_jiosaavn'] : false );
        $Settings['subscribe_feature_podchaser'] = (isset($GeneralSettings['subscribe_feature_podchaser']) ? $GeneralSettings['subscribe_feature_podchaser'] : false );
        $Settings['subscribe_feature_gaana'] = (isset($GeneralSettings['subscribe_feature_gaana']) ? $GeneralSettings['subscribe_feature_gaana'] : false );
        $Settings['subscribe_feature_email_shortcode'] = (isset($GeneralSettings['subscribe_feature_email_shortcode']) ? $GeneralSettings['subscribe_feature_email_shortcode'] : true );
        $Settings['subscribe_feature_apple_shortcode'] = (isset($GeneralSettings['subscribe_feature_apple_shortcode']) ? $GeneralSettings['subscribe_feature_apple_shortcode'] : true );
        $Settings['subscribe_feature_gp_shortcode'] = (isset($GeneralSettings['subscribe_feature_gp_shortcode']) ? $GeneralSettings['subscribe_feature_gp_shortcode'] : true );
        $Settings['subscribe_feature_stitcher_shortcode'] = (isset($GeneralSettings['subscribe_feature_stitcher_shortcode']) ? $GeneralSettings['subscribe_feature_stitcher_shortcode'] : true );
        $Settings['subscribe_feature_tunein_shortcode'] = (isset($GeneralSettings['subscribe_feature_tunein_shortcode']) ? $GeneralSettings['subscribe_feature_tunein_shortcode'] : true );
        $Settings['subscribe_feature_spotify_shortcode'] = (isset($GeneralSettings['subscribe_feature_spotify_shortcode']) ? $GeneralSettings['subscribe_feature_spotify_shortcode'] : true );
        $Settings['subscribe_feature_android_shortcode'] = (isset($GeneralSettings['subscribe_feature_android_shortcode']) ? $GeneralSettings['subscribe_feature_android_shortcode'] : true );
        $Settings['subscribe_feature_blubrry_shortcode'] = (isset($GeneralSettings['subscribe_feature_blubrry_shortcode']) ? $GeneralSettings['subscribe_feature_blubrry_shortcode'] : true );
        $Settings['subscribe_feature_iheart_shortcode'] = (isset($GeneralSettings['subscribe_feature_iheart_shortcode']) ? $GeneralSettings['subscribe_feature_iheart_shortcode'] : true );
        $Settings['subscribe_feature_deezer_shortcode'] = (isset($GeneralSettings['subscribe_feature_deezer_shortcode']) ? $GeneralSettings['subscribe_feature_deezer_shortcode'] : true );
        $Settings['subscribe_feature_pandora_shortcode'] = (isset($GeneralSettings['subscribe_feature_pandora_shortcode']) ? $GeneralSettings['subscribe_feature_pandora_shortcode'] : true );
        $Settings['subscribe_feature_amazon_shortcode'] = (isset($GeneralSettings['subscribe_feature_amazon_shortcode']) ? $GeneralSettings['subscribe_feature_amazon_shortcode'] : true );
        $Settings['subscribe_feature_pcindex_shortcode'] = (isset($GeneralSettings['subscribe_feature_pcindex_shortcode']) ? $GeneralSettings['subscribe_feature_pcindex_shortcode'] : true );
        $Settings['subscribe_feature_jiosaavn_shortcode'] = (isset($GeneralSettings['subscribe_feature_jiosaavn_shortcode']) ? $GeneralSettings['subscribe_feature_jiosaavn_shortcode'] : true );
        $Settings['subscribe_feature_podchaser_shortcode'] = (isset($GeneralSettings['subscribe_feature_podchaser_shortcode']) ? $GeneralSettings['subscribe_feature_podchaser_shortcode'] : true );
        $Settings['subscribe_feature_gaana_shortcode'] = (isset($GeneralSettings['subscribe_feature_gaana_shortcode']) ? $GeneralSettings['subscribe_feature_gaana_shortcode'] : true );
        $Settings['subscribe_feature_email_sidebar'] = (isset($GeneralSettings['subscribe_feature_email_sidebar']) ? $GeneralSettings['subscribe_feature_email_sidebar'] : true );
        $Settings['subscribe_feature_apple_sidebar'] = (isset($GeneralSettings['subscribe_feature_apple_sidebar']) ? $GeneralSettings['subscribe_feature_apple_sidebar'] : true );
        $Settings['subscribe_feature_gp_sidebar'] = (isset($GeneralSettings['subscribe_feature_gp_sidebar']) ? $GeneralSettings['subscribe_feature_gp_sidebar'] : true );
        $Settings['subscribe_feature_stitcher_sidebar'] = (isset($GeneralSettings['subscribe_feature_stitcher_sidebar']) ? $GeneralSettings['subscribe_feature_stitcher_sidebar'] : false );
        $Settings['subscribe_feature_tunein_sidebar'] = (isset($GeneralSettings['subscribe_feature_tunein_sidebar']) ? $GeneralSettings['subscribe_feature_tunein_sidebar'] : false );
        $Settings['subscribe_feature_spotify_sidebar'] = (isset($GeneralSettings['subscribe_feature_spotify_sidebar']) ? $GeneralSettings['subscribe_feature_spotify_sidebar'] : false );
        $Settings['subscribe_feature_iheart_sidebar'] = (isset($GeneralSettings['subscribe_feature_iheart_sidebar']) ? $GeneralSettings['subscribe_feature_iheart_sidebar'] : false );
        $Settings['subscribe_feature_deezer_sidebar'] = (isset($GeneralSettings['subscribe_feature_deezer_sidebar']) ? $GeneralSettings['subscribe_feature_deezer_sidebar'] : false );
        $Settings['subscribe_feature_pandora_sidebar'] = (isset($GeneralSettings['subscribe_feature_pandora_sidebar']) ? $GeneralSettings['subscribe_feature_pandora_sidebar'] : false );
        $Settings['subscribe_feature_android_sidebar'] = (isset($GeneralSettings['subscribe_feature_android_sidebar']) ? $GeneralSettings['subscribe_feature_android_sidebar'] : true );
        $Settings['subscribe_feature_blubrry_sidebar'] = (isset($GeneralSettings['subscribe_feature_blubrry_sidebar']) ? $GeneralSettings['subscribe_feature_blubrry_sidebar'] : false );
        $Settings['subscribe_feature_amazon_sidebar'] = (isset($GeneralSettings['subscribe_feature_amazon_sidebar']) ? $GeneralSettings['subscribe_feature_amazon_sidebar'] : false );
        $Settings['subscribe_feature_pcindex_sidebar'] = (isset($GeneralSettings['subscribe_feature_pcindex_sidebar']) ? $GeneralSettings['subscribe_feature_pcindex_sidebar'] : false );
        $Settings['subscribe_feature_jiosaavn_sidebar'] = (isset($GeneralSettings['subscribe_feature_jiosaavn_sidebar']) ? $GeneralSettings['subscribe_feature_jiosaavn_sidebar'] : false );
        $Settings['subscribe_feature_podchaser_sidebar'] = (isset($GeneralSettings['subscribe_feature_podchaser_sidebar']) ? $GeneralSettings['subscribe_feature_podchaser_sidebar'] : false );
        $Settings['subscribe_feature_gaana_sidebar'] = (isset($GeneralSettings['subscribe_feature_gaana_sidebar']) ? $GeneralSettings['subscribe_feature_gaana_sidebar'] : false );
        if (isset($GeneralSettings['subscribe_no_important_styling'])) {
            $Settings['subscribe_no_important_styling'] = $GeneralSettings['subscribe_no_important_styling'];
        } else {
            $Settings['subscribe_no_important_styling'] = 'include';
        }

        if( !empty($FeedSettings['premium']) ) {
			$Settings['subscribe_feature_email'] = false;
			$Settings['subscribe_feature_gp'] = false;
            $Settings['subscribe_feature_apple'] = false;
			$Settings['subscribe_feature_stitcher'] = false;
			$Settings['subscribe_feature_tunein'] = false;
			$Settings['subscribe_feature_spotify'] = false;
			$Settings['subscribe_feature_iheart'] = false;
            $Settings['subscribe_feature_deezer'] = false;
            $Settings['subscribe_feature_pandora'] = false;
            $Settings['subscribe_feature_android'] = false;
            $Settings['subscribe_feature_blubrry'] = false;
            $Settings['subscribe_feature_amazon'] = false;
            $Settings['subscribe_feature_pcindex'] = false;
            $Settings['subscribe_feature_jiosaavn'] = false;
            $Settings['subscribe_feature_podchaser'] = false;
            $Settings['subscribe_feature_gaana'] = false;
            $Settings['subscribe_feature_email_shortcode'] = false;
            $Settings['subscribe_feature_apple_shortcode'] = false;
            $Settings['subscribe_feature_gp_shortcode'] = false;
            $Settings['subscribe_feature_stitcher_shortcode'] = false;
            $Settings['subscribe_feature_tunein_shortcode'] = false;
            $Settings['subscribe_feature_spotify_shortcode'] = false;
            $Settings['subscribe_feature_android_shortcode'] = false;
            $Settings['subscribe_feature_blubrry_shortcode'] = false;
            $Settings['subscribe_feature_iheart_shortcode'] = false;
            $Settings['subscribe_feature_deezer_shortcode'] = false;
            $Settings['subscribe_feature_pandora_shortcode'] = false;
            $Settings['subscribe_feature_amazon_shortcode'] = false;
            $Settings['subscribe_feature_pcindex_shortcode'] = false;
            $Settings['subscribe_feature_jiosaavn_shortcode'] = false;
            $Settings['subscribe_feature_podchaser_shortcode'] = false;
            $Settings['subscribe_feature_gaana_shortcode'] = false;
            $Settings['subscribe_feature_email_sidebar'] = false;
            $Settings['subscribe_feature_gp_sidebar'] = false;
            $Settings['subscribe_feature_apple_sidebar'] = false;
            $Settings['subscribe_feature_stitcher_sidebar'] = false;
            $Settings['subscribe_feature_tunein_sidebar'] = false;
            $Settings['subscribe_feature_spotify_sidebar'] = false;
            $Settings['subscribe_feature_iheart_sidebar'] = false;
            $Settings['subscribe_feature_deezer_sidebar'] = false;
            $Settings['subscribe_feature_pandora_sidebar'] = false;
            $Settings['subscribe_feature_android_sidebar'] = false;
            $Settings['subscribe_feature_blubrry_sidebar'] = false;
            $Settings['subscribe_feature_amazon_sidebar'] = false;
            $Settings['subscribe_feature_pcindex_sidebar'] = false;
            $Settings['subscribe_feature_jiosaavn_sidebar'] = false;
            $Settings['subscribe_feature_podchaser_sidebar'] = false;
            $Settings['subscribe_feature_gaana_sidebar'] = false;
        }
		
		return $Settings;
	}
	return false;
}

// 1: Subscribe widget added to the links...
function powerpressplayer_link_subscribe_pre($content, $media_url, $ExtraData = array() )
{
	$detect_category = true;
	if( 'post' != get_post_type() && empty($ExtraData['subscribe_type']) )
	{
		$post_type = get_post_type();
		$ExtraData['subscribe_type'] = 'post_type';
	}
	else if( 'post' == get_post_type() && !empty($ExtraData['category']) ) { // If strict category selected
		$ExtraData['cat_id'] = $ExtraData['category'];
		//$ExtraData['subscribe_type'] = 'category'; // Let the get settings function below figure this out
		$detect_category = false;
	}
	
	$SubscribeSettings = powerpresssubscribe_get_settings( $ExtraData, $detect_category );
	if( empty($SubscribeSettings) )
		return $content;
	
	if( !isset($SubscribeSettings['subscribe_links']) )
		$SubscribeSettings['subscribe_links'] = 1; // Default make this the first link option
		
	if( $SubscribeSettings['subscribe_links'] != 1 ) // beginning of links
		return $content;
		
	$feed_url = $SubscribeSettings['feed_url'];
	$itunes_url = trim($SubscribeSettings['itunes_url']);
	
	$links_array = array();
	if( !empty($itunes_url) && !empty($SubscribeSettings['subscribe_feature_apple']) ) {
		$guid = get_the_guid();
		$links_array[] = "<a href=\"".  htmlspecialchars( powerpresssubscribe_add_guid_to_itunes_url($itunes_url, $guid) ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_itunes\" title=\"". __('Subscribe on Apple Podcasts', 'powerpress') ."\" rel=\"nofollow\">". __('Apple Podcasts','powerpress') ."</a>".PHP_EOL_WEB;
	}

    if( !empty($SubscribeSettings['subscribe_feature_gp']) )
    {
        if( !empty($SubscribeSettings['google_url']) )
            $SubscribeSettings['googleplay_url'] =$SubscribeSettings['google_url'];
        else
            $SubscribeSettings['googleplay_url'] = 'https://www.google.com/podcasts?feed='. powerpress_base64_encode($feed_url);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['googleplay_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_googleplay\" title=\"". __('Subscribe on Google Podcasts', 'powerpress') ."\" rel=\"nofollow\">". __('Google Podcasts','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( !empty($SubscribeSettings['subscribe_feature_spotify']) && !empty($SubscribeSettings['spotify_url']) )
    {
        $SubscribeSettings['spotify_url'] = trim($SubscribeSettings['spotify_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['spotify_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_spotify\" title=\"". __('Subscribe on Spotify', 'powerpress') ."\" rel=\"nofollow\">". __('Spotify','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( !empty($SubscribeSettings['subscribe_feature_amazon']) && !empty($SubscribeSettings['amazon_url']) )
    {
        $SubscribeSettings['amazon_url'] = trim($SubscribeSettings['amazon_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['amazon_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_amazon\" title=\"". __('Subscribe on Amazon Music', 'powerpress') ."\" rel=\"nofollow\">". __('Amazon Music','powerpress') ."</a>".PHP_EOL_WEB;
    }
	
	if( preg_match('/^(https?:\/\/)(.*)$/i', $feed_url, $matches ) && !empty($SubscribeSettings['subscribe_feature_android']) ) {
            $android_url = $matches[1] . 'subscribeonandroid.com/' . $matches[2];
		    $links_array[] = "<a href=\"".  htmlspecialchars($android_url) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_android\" title=\"". __('Subscribe on Android', 'powerpress') ."\" rel=\"nofollow\">". __('Android','powerpress') ."</a>".PHP_EOL_WEB;
	}

    if( !empty($SubscribeSettings['subscribe_feature_pandora']) && !empty($SubscribeSettings['pandora_url']) )
    {
        $SubscribeSettings['pandora_url'] = trim($SubscribeSettings['pandora_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['pandora_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_pandora\" title=\"". __('Subscribe on Pandora', 'powerpress') ."\" rel=\"nofollow\">". __('Pandora','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( !empty($SubscribeSettings['subscribe_feature_iheart']) && !empty($SubscribeSettings['iheart_url']) )
    {
        $SubscribeSettings['iheart_url'] = trim($SubscribeSettings['iheart_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['iheart_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_iheart\" title=\"". __('Subscribe on iHeartRadio', 'powerpress') ."\" rel=\"nofollow\">". __('iHeartRadio','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( !empty($SubscribeSettings['subscribe_feature_stitcher']) && !empty($SubscribeSettings['stitcher_url']) )
    {
        $SubscribeSettings['stitcher_url'] = trim($SubscribeSettings['stitcher_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['stitcher_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_stitcher\" title=\"". __('Subscribe on Stitcher', 'powerpress') ."\" rel=\"nofollow\">". __('Stitcher','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( !empty($SubscribeSettings['subscribe_feature_blubrry']) && !empty($SubscribeSettings['blubrry_url']) )
    {
        $SubscribeSettings['blubrry_url'] = trim($SubscribeSettings['blubrry_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['blubrry_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_blubrry\" title=\"". __('Subscribe on Blubrry', 'powerpress') ."\" rel=\"nofollow\">". __('Blubrry','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( !empty($SubscribeSettings['subscribe_feature_jiosaavn']) && !empty($SubscribeSettings['jiosaavn_url']) )
    {
        $SubscribeSettings['jiosaavn_url'] = trim($SubscribeSettings['jiosaavn_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['jiosaavn_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_jiosaavn\" title=\"". __('Subscribe on JioSaavn', 'powerpress') ."\" rel=\"nofollow\">". __('JioSaavn','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( !empty($SubscribeSettings['subscribe_feature_podchaser']) && !empty($SubscribeSettings['podchaser_url']) )
    {
        $SubscribeSettings['podchaser_url'] = trim($SubscribeSettings['podchaser_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['podchaser_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_podchaser\" title=\"". __('Subscribe on Podchaser', 'powerpress') ."\" rel=\"nofollow\">". __('Podchaser','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( !empty($SubscribeSettings['subscribe_feature_gaana']) && !empty($SubscribeSettings['gaana_url']) )
    {
        $SubscribeSettings['gaana_url'] = trim($SubscribeSettings['gaana_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['gaana_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_gaana\" title=\"". __('Subscribe on Gaana', 'powerpress') ."\" rel=\"nofollow\">". __('Gaana','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( !empty($SubscribeSettings['subscribe_feature_pcindex']) && !empty($SubscribeSettings['pcindex_url']) )
    {
        $SubscribeSettings['pcindex_url'] = trim($SubscribeSettings['pcindex_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['pcindex_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_pcindex\" title=\"". __('Subscribe on Podcast Index', 'powerpress') ."\" rel=\"nofollow\">". __('Podcast Index','powerpress') ."</a>".PHP_EOL_WEB;
    }

    if( preg_match('/^(https?:\/\/)(.*)$/i', $feed_url, $matches ) && !empty($SubscribeSettings['subscribe_feature_email']) ) {
        $email_url = $matches[1] . 'subscribebyemail.com/' . $matches[2];
        $links_array[] = "<a href=\"" . htmlspecialchars($email_url) . "\" class=\"powerpress_link_subscribe powerpress_link_subscribe_email\" title=\"" . __('Subscribe by Email', 'powerpress') . "\" rel=\"nofollow\">" . __('Email', 'powerpress') . "</a>" . PHP_EOL_WEB;
    }
	
	if( !empty($SubscribeSettings['subscribe_feature_tunein']) && !empty($SubscribeSettings['tunein_url']) )
	{
		$SubscribeSettings['tunein_url'] = trim($SubscribeSettings['tunein_url']);
		$links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['tunein_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_tunein\" title=\"". __('Subscribe on TuneIn', 'powerpress') ."\" rel=\"nofollow\">". __('TuneIn','powerpress') ."</a>".PHP_EOL_WEB;
	}

    if( !empty($SubscribeSettings['subscribe_feature_deezer']) && !empty($SubscribeSettings['deezer_url']) )
    {
        $SubscribeSettings['deezer_url'] = trim($SubscribeSettings['deezer_url']);
        $links_array[] = "<a href=\"".  esc_attr($SubscribeSettings['deezer_url'] ) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_deezer\" title=\"". __('Subscribe on Deezer', 'powerpress') ."\" rel=\"nofollow\">". __('Deezer','powerpress') ."</a>".PHP_EOL_WEB;
    }

	$links_array[] = "<a href=\"". htmlspecialchars($feed_url) ."\" class=\"powerpress_link_subscribe powerpress_link_subscribe_rss\" title=\"". __('Subscribe via RSS', 'powerpress') ."\" rel=\"nofollow\">". __('RSS','powerpress') ."</a>".PHP_EOL_WEB;
	
	if( !empty($SubscribeSettings['subscribe_page_url']) )
	{
		$label = (empty($SubscribeSettings['subscribe_page_link_text'])?__('More', 'powerpress'):$SubscribeSettings['subscribe_page_link_text']);
		$links_array[] = "<a href=\"{$SubscribeSettings['subscribe_page_url']}\" class=\"powerpress_link_subscribe powerpress_link_subscribe_more\" title=\"". htmlspecialchars($label) ."\" rel=\"nofollow\">". htmlspecialchars($label) ."</a>".PHP_EOL_WEB;
	}
	
	$content .= implode(' '.POWERPRESS_LINK_SEPARATOR .' ', $links_array);
	return $content;
}

function powerpressplayer_link_subscribe_post($content, $media_url, $ExtraData = array() )
{
	if( !empty($content) )
	{
		$GeneralSettings = get_option('powerpress_general');
		
		$label = __('Subscribe:', 'powerpress');
		if( !empty($GeneralSettings['subscribe_label']) )
			$label = $GeneralSettings['subscribe_label'];
		// Get label setting from $GeneralSettings
		$prefix = htmlspecialchars($label) . ' ';
		
		$return = '<p class="powerpress_links powerpress_subscribe_links">'. $prefix . $content . '</p>';
		return $return;
	}
	return $content;
}

function powerpress_subscribe_shortcode( $attr ) {


	if ( is_feed() ) {
		return '';
	}
	
	// Only works on pages...
	if ( !is_singular() ) {
		if( empty($attr['archive']) )
			return '';
	}

	/*
	extract( shortcode_atts( array(
		'channel'=>'', // Used for PowerPress Podcast Channels
		'slug' => '', // Used for PowerPress (alt for 'channel')
		'feed' => '', // Used for PowerPress (alt for 'channel')
		'post_type' => 'post', // Used for PowerPress 
		'category'=>'', // Used for PowerPress (specify category ID, name or slug)
		'term_taxonomy_id'=>'', // Used for PowerPress (specify term taxonomy ID)
		//'term_id'=>'', // Used for PowerPress (specify term ID, name or slug)
		//'taxonomy'=>'', // Used for PowerPress (specify taxonomy name)
		
		'title'	=> '', // Display custom title of show/program
		'subtitle'=>'', // Subtitle for podcast (optional)
		'feed_url'=>'', // provide subscribe widget for specific RSS feed
		'itunes_url'=>'', // provide subscribe widget for specific iTunes subscribe URL
		'image_url'=>'', // provide subscribe widget for specific iTunes subscribe URL
		'heading'=>'', // heading label for podcast
		
		'itunes_subtitle'=>'', // Set to 'true' to include the iTunes subtitle in subscribe widget
		
		// Appearance attributes
		'itunes_button'=>'', // Set to 'true' to use only the iTunes button
		'itunes_banner'=>'', // Set to 'true' to use only the iTunes banner
		'style'=>'' // Set to 'true' to use only the iTunes banner
	), $attr, 'powerpresssubscribe' ) );
	//return print_r($attr, true);
	*/
	
	/**/
	if( !is_array($attr) ) // Convert to an array to avoid php notice messages
	{
		$attr = array();
	}
	
	if( empty($attr['slug']) && !empty($attr['feed']) )
		$attr['slug'] = $attr['feed'];
	else if( empty($attr['slug']) && !empty($attr['channel']) )
		$attr['slug'] = $attr['channel'];
	else if( empty($attr['slug']) )
		$attr['slug'] = 'podcast';
	
	// Set empty args to prevent warnings
	if( !isset($attr['term_taxonomy_id']) )
		$attr['term_taxonomy_id'] = '';
	if( !isset($attr['category_id']) )
		$attr['category_id'] = '';
	if( !isset($attr['post_type']) )
		$attr['post_type'] = '';

	$subscribe_type = '';
	$category_id = '';
		
	if(!empty($attr['category']) )
	{
		$CategoryObj = false;
		if( preg_match('/^[0-9]*$/', $attr['category']) ) // If it is a numeric ID, lets try finding it by ID first...
			$CategoryObj = get_term_by('id', $attr['category'], 'category');
		if( empty($CategoryObj) )
			$CategoryObj = get_term_by('name', $attr['category'], 'category');
		if( empty($CategoryObj) )
			$CategoryObj = get_term_by('slug', $attr['category'], 'category');
		if( !empty($CategoryObj) )
		{
			$category_id = $CategoryObj->term_id;
		}
	}
	

	
	if( !empty($attr['category']) )
		$subscribe_type = 'category';
	else if( !empty($attr['term_taxonomy_id']) )
		$subscribe_type = 'ttid';
	else if( !empty($attr['post_type']) )
		$subscribe_type = 'post_type';
	else if( empty($attr['post_type']) && !empty($attr['slug']) && $attr['slug'] != 'podcast' )
		$subscribe_type = 'channel';
	
	$Settings = array();
	if( !empty($attr['feed_url']) )
	{
		$Settings['feed_url'] = $attr['feed_url'];
	}
	else
	{
		$Settings = powerpresssubscribe_get_settings(  array('feed'=>$attr['slug'], 'taxonomy_term_id'=>$attr['term_taxonomy_id'], 'cat_id'=>$category_id, 'post_type'=>$attr['post_type'], 'subscribe_type'=>$subscribe_type), false );
	}
	
	// Podcast title handling
	if( isset($attr['title']) && empty($attr['title']) && isset($Settings['title']) )
		unset( $Settings['title'] ); // Special case, if the title is unset, then it should not be displayed
	else if( !empty($attr['title']) )
		$Settings['title'] = $attr['title'];
	else if( !isset($Settings['title']) )
		$Settings['title'] = ''; // This way the title can be detected
		
	unset($Settings['subtitle']); // Make sure no subtitle passes this point
	if( !empty($attr['itunes_subtitle']) && !empty($Settings['itunes_subtitle']) ) {
		$Settings['subtitle'] = $Settings['itunes_subtitle'];
	} else if( !empty($attr['subtitle']) ) {
		$Settings['subtitle'] = $attr['subtitle'];
	}
		
	
	if( !empty($attr['itunes_url']) )
		$Settings['itunes_url'] = $attr['itunes_url'];
	if( !empty($attr['style']) )
		$Settings['subscribe_widget_style'] = $attr['style'];
    if( !empty($attr['subscribe_no_important_styling']) )
        $Settings['subscribe_no_important_styling'] = $attr['subscribe_no_important_styling'];
	if( !empty($attr['image_url']) )
		$Settings['image_url'] = $attr['image_url'];	
	if( isset($attr['heading']) ) // If a custom heading is set
		$Settings['heading'] = $attr['heading'];
		
	if( empty($Settings) )
		return '';
	$Settings['itunes_url'] = powerpresssubscribe_get_itunes_url($Settings);
    if (defined('WP_DEBUG')) {
        if (WP_DEBUG) {
            wp_enqueue_style('powerpress-subscribe-style-modern', plugin_dir_url(__FILE__) . 'css/subscribe.css', array(), POWERPRESS_VERSION);
        } else {
            wp_enqueue_style('powerpress-subscribe-style-modern', plugin_dir_url(__FILE__) . 'css/subscribe.min.css', array(), POWERPRESS_VERSION);
        }
    } else {
        wp_enqueue_style('powerpress-subscribe-style-modern', plugin_dir_url(__FILE__) . 'css/subscribe.min.css', array(), POWERPRESS_VERSION);
    }
	if( !empty($attr['itunes_button']) && !empty($Settings['itunes_url']) )
	{
		$html = '<div>';
		$html .= '';
		$html .='<a href="';
		$html .= esc_url($Settings['itunes_url']);
		$html .= '" style="display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/images/badges/en-us/badge_itunes-lrg.svg) no-repeat;width:165px;height:40px;"></a>';
		$html .= '</div>';
		return $html;
	}
	
	if( !empty($attr['itunes_badge']) && !empty($Settings['itunes_url']) )
	{
		$html = '<div>';
		$html .= '';
		$html .='<a href="';
		$html .= esc_url($Settings['itunes_url']);
		$html .= '" style="display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/images/badges/en-us/badge_itunes-sm.svg) no-repeat;width:80px;height:15px;"></a>';
		$html .= '</div>';
		return $html;
	}
	
	if( !empty($attr['itunes_banner']) && !empty($Settings['itunes_url']) )
	{
		$apple_id = powerpress_get_apple_id($Settings['itunes_url'], true);
		if( !empty($apple_id) && $apple_id > 0 )
		{
			$html = '';
			$html .= '<div id="ibb-widget-root-'.$apple_id.'"></div>';
			$html .= "<script>(function(t,e,i,d){var o=t.getElementById(i),n=t.createElement(e);o.style.height=250;o.style.width=300;o.style.display='inline-block';n.id='ibb-widget',n.setAttribute('src',('https:'===t.location.protocol?'https://':'http://')+d),n.setAttribute('width','300'),n.setAttribute('height','250'),n.setAttribute('frameborder','0'),n.setAttribute('scrolling','no'),o.appendChild(n)})(document,'iframe','ibb-widget-root-".$apple_id."'";
			$html .= ',"banners.itunes.apple.com/banner.html?partnerId=&aId=&bt=catalog&t=catalog_blur&id='.$apple_id.'&c=us&l=en-US&w=300&h=250");</script>';
			return $html;
		}
		return '';
	}
	
	// This is the only spot that gets the General settings for the subscribe buttons...
	$PowerPressSettings = get_option('powerpress_general');
	if (empty($Settings['subscribe_widget_style'])) {
        $Settings['subscribe_widget_style'] = (!empty($PowerPressSettings['subscribe_widget_style']) ? $PowerPressSettings['subscribe_widget_style'] : '');
    }
	$Settings['subscribe_widget_shape'] = ( empty($PowerPressSettings['subscribe_widget_shape']) || $PowerPressSettings['subscribe_widget_shape'] == 'squared' ? '-sq': '');
	$Settings['subscribe_no_important_styling'] = (!empty($PowerPressSettings['subscribe_no_important_styling']) ? $PowerPressSettings['subscribe_no_important_styling'] : '' );
	return powerpress_do_subscribe_widget($Settings, $PowerPressSettings);
}

add_shortcode( 'powerpresssubscribe', 'powerpress_subscribe_shortcode' );
add_shortcode( 'powerpress_subscribe', 'powerpress_subscribe_shortcode' );
	
require_once( POWERPRESS_ABSPATH . '/class.powerpress-subscribe-widget.php' );

function powerpress_do_subscribe_widget($settings, $PowerPressSettings)
{
    //ob_start();
    //echo implode("<br />", array_keys($settings));
    //$output = ob_get_clean();
    //return $output;
	if( empty($settings['feed_url']) )
	{
		return '';
	}
	
	if( isset($settings['title']) && empty($settings['title']) )
	{
		$settings['title'] = get_bloginfo_rss('name');
	}

	if( empty($settings['itunes_url']) )
	{
		$settings['itunes_url'] = powerpresssubscribe_get_itunes_url( $settings );
	}
	
	if( empty($settings['subscribe_widget_style']) )
	{
        $settings['subscribe_widget_style'] = 'classic';
		
		if( empty($PowerPressSettings['timestamp']) || $PowerPressSettings['timestamp'] > 1570366800 ) {
				$settings['subscribe_widget_style'] = 'modern';
		}
	}
	
	if( empty($settings['image_url']) )
	{
		$settings['image_url'] = powerpress_get_root_url() . 'itunes_default.jpg'; // Default PowerPress image used in this case.
	}

	if (isset($settings['subscribe_no_important_styling']) && $settings['subscribe_no_important_styling'] == "exclude") {
	    $important_tags_class = '';
    } else {
	    $important_tags_class = ' pp-sub-widget-include';
    }
	
	
	$html = '';
	$html .= '<div class="pp-sub-widget pp-sub-widget-'. esc_attr($settings['subscribe_widget_style']) . $important_tags_class .'">';
	if( !empty($settings['title']) )
	{
		if( !isset($settings['heading']) ) { // If not specified in the shortcode
				$settings['heading'] = __('Subscribe to', 'powerpress');
		}

		if( !empty($settings['heading']) ) { // If there is a value set for the heading, lets use it
			$html .= '<div class="pp-sub-h">'.  esc_html($settings['heading']) .'</div>';
		}

		$html .= '<div class="pp-sub-t">'.  esc_html( $settings['title'] ) .'</div>';
	}
	else
	{
		$settings['title'] = ''; // Make sure it's an empty string
	}
	
	if( !empty($settings['subtitle']) )
	{
		$html .= '<p class="pp-sub-st">'.  esc_html( $settings['subtitle'] ) .'</p>';
	}
		
		// Lets build the subscribe box...
			$html .= '<div class="pp-sub-bx">';
				$html .= '<img class="pp-sub-l" src="'. esc_url( $settings['image_url'] ) .'" '. (!empty($settings['title'])?' title="'.  esc_attr($settings['title']).'" ':'') .'/>';
				$html .= '<div class="pp-sub-btns">';

			if( !empty($settings['itunes_url']) &&  !empty($settings['subscribe_feature_apple_shortcode']) ) {
				$html .= '<a href="'.  esc_url( $settings['itunes_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-itunes" title="'.  esc_attr( __('Subscribe on Apple Podcasts', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Apple Podcasts', 'powerpress') ) .'</a>';
			}
            // Google Podcasts
            $googleUrl = '';
            if( !empty($settings['google_url']) )
                $googleUrl = $settings['google_url'];
            else
                $googleUrl = 'https://www.google.com/podcasts?feed='. powerpress_base64_encode($settings['feed_url']);
            if (!empty($settings['subscribe_feature_gp_shortcode'])) {
                $html .= '<a href="' . esc_url($googleUrl) . '" class="pp-sub-btn' . $settings['subscribe_widget_shape'] . ' pp-sub-gp" title="' . esc_attr(__('Subscribe on Google Podcasts', 'powerpress')) . '"><span class="pp-sub-ic"></span>' . esc_html(__('Google Podcasts', 'powerpress')) . '</a>';
            }

            if(  !empty($settings['subscribe_feature_spotify_shortcode']) && !empty($settings['spotify_url']) )
            {
                $html .= '<a href="'.  esc_url( $settings['spotify_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-spotify" title="'.  esc_attr( __('Subscribe on Spotify', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Spotify', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_amazon_shortcode']) &&  !empty($settings['amazon_url']) ) {

                $html .= '<a href="'.  esc_url( $settings['amazon_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-amazon" title="'.  esc_attr( __('Subscribe on Amazon Music', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Amazon Music', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_android_shortcode']) &&  preg_match('/^(https?:\/\/)(.*)$/i', $settings['feed_url'], $matches ) ) {
                $android_url =  $matches[1] . 'subscribeonandroid.com/' . $matches[2];
                $html .= '<a href="'.  esc_url( $android_url ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-android" title="'.  esc_attr( __('Subscribe on Android', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Android', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_pandora_shortcode']) &&  !empty($settings['pandora_url']) ) {
                $html .= '<a href="'.  esc_url( $settings['pandora_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-pandora" title="'.  esc_attr( __('Subscribe on Pandora', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Pandora', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_iheart_shortcode']) &&  !empty($settings['iheart_url']) ) {
                $html .= '<a href="'.  esc_url( $settings['iheart_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-iheartradio" title="'.  esc_attr( __('Subscribe on iHeartRadio', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('iHeartRadio', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_stitcher_shortcode']) &&  !empty($settings['stitcher_url']) )
            {
                $html .= '<a href="'.  esc_url( $settings['stitcher_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-stitcher" title="'.  esc_attr( __('Subscribe on Stitcher', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Stitcher', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_blubrry_shortcode']) &&  !empty($settings['blubrry_url']) ) {

                $html .= '<a href="'.  esc_url( $settings['blubrry_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-blubrry" title="'.  esc_attr( __('Subscribe on Blubrry', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Blubrry', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_jiosaavn_shortcode']) &&  !empty($settings['jiosaavn_url']) ) {

                $html .= '<a href="'.  esc_url( $settings['jiosaavn_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-jiosaavn" title="'.  esc_attr( __('Subscribe on JioSaavn', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('JioSaavn', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_podchaser_shortcode']) &&  !empty($settings['podchaser_url']) ) {

                $html .= '<a href="'.  esc_url( $settings['podchaser_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-podchaser" title="'.  esc_attr( __('Subscribe on Podchaser', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Podchaser', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_gaana_shortcode']) &&  !empty($settings['gaana_url']) ) {

                $html .= '<a href="'.  esc_url( $settings['gaana_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-gaana" title="'.  esc_attr( __('Subscribe on Gaana', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Gaana', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_pcindex_shortcode']) &&  !empty($settings['pcindex_url']) ) {

                $html .= '<a href="'.  esc_url( $settings['pcindex_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-pcindex" title="'.  esc_attr( __('Subscribe on Podcast Index', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Podcast Index', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_email_shortcode']) &&  preg_match('/^(https?:\/\/)(.*)$/i', $settings['feed_url'], $matches ) ) {

                $email_url =  $matches[1] . 'subscribebyemail.com/' . $matches[2];
                $html .= '<a href="'.  esc_url( $email_url ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-email" title="'.  esc_attr( __('Subscribe by Email', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('by Email', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_tunein_shortcode']) &&  !empty($settings['tunein_url']) )
            {
                $html .= '<a href="'.  esc_url( $settings['tunein_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-tunein" title="'.  esc_attr( __('Subscribe on TuneIn', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('TuneIn', 'powerpress') ) .'</a>';
            }

            if( !empty($settings['subscribe_feature_deezer_shortcode']) &&  !empty($settings['deezer_url']) ) {

                $html .= '<a href="'.  esc_url( $settings['deezer_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-deezer" title="'.  esc_attr( __('Subscribe on Deezer', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('Deezer', 'powerpress') ) .'</a>';
            }

            //$html .= var_dump($settings, true);


            $html .= '<a href="'.  esc_url( $settings['feed_url'] ) .'" class="pp-sub-btn'.$settings['subscribe_widget_shape'].' pp-sub-rss" title="'.  esc_attr( __('Subscribe via RSS', 'powerpress') ) .'"><span class="pp-sub-ic"></span>'.  esc_html( __('RSS', 'powerpress') ) .'</a>';


			$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="pp-sub-m">';
			$html .= '<p class="pp-sub-m-p">'.  esc_html( __('Or subscribe with your favorite app by using the address below', 'powerpress') ) .'</p>';
			$html .= '<input class="pp-sub-m-i" type="text" name="NULL'. rand(0,9999) .'" value="'.  esc_attr( $settings['feed_url'] ) .'" onclick="this.focus();this.select();" />';
		$html .= '</div>';
	$html .= '</div>';

	return $html;
}

function powerpress_do_subscribe_sidebar_widget($settings)
{
	if( empty($settings['feed_url']) )
	{
		return '';
	}
	
	if( empty($settings['itunes_url']) )
	{
		$settings['itunes_url'] = powerpresssubscribe_get_itunes_url( $settings );
	}

	if( empty($settings['style']) )
	{
		$settings['style'] = 'classic';
		$PowerPressSettings = get_option('powerpress_general');
		if( empty($PowerPressSettings['timestmap']) || $PowerPressSettings['timestamp'] > 1570366800 ) // If after Oct 7, 2019
			$settings['style'] = 'modern';
	}
    if( empty($settings['modern_direction']) )
    {
        $settings['modern_direction'] = 'vertical';
    }
	
	if( !isset($settings['modern_style']) ) { // In case it gets this far and it's not provided...
		$settings['modern_style'] = '-sq';
	}
	
/*  Prints settings array to webpage
    ob_start();
    var_dump($settings);
    $s = ob_get_clean();
    $html = "<pre>$s</pre>";
*/
    if (isset($settings['subscribe_no_important_styling']) && $settings['subscribe_no_important_styling'] == "exclude") {
        $important_tags_class = '';
    } else {
        $important_tags_class = ' pp-ssb-widget-include';
    }
    $html = '';
	$html .= '<div class="pp-ssb-widget pp-ssb-widget-'. esc_attr($settings['style']) . $important_tags_class .'">';
		if( !empty($settings['itunes_url']) && !empty($settings['subscribe_feature_apple_sidebar']) ) {
			$html .= '<a href="'.  esc_url( $settings['itunes_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-itunes" title="'.  esc_attr( __('Subscribe on Apple Podcasts', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Apple Podcasts', 'powerpress') ) .'</span></a>';
		}

        if(  !empty($settings['subscribe_feature_gp_sidebar']) ) {
            $googleUrl = '';
            if( !empty($settings['google_url']) )
                $gp_url = $settings['google_url'];
            else
                $gp_url = 'https://www.google.com/podcasts?feed='.powerpress_base64_encode($settings['feed_url']);

            $html .= '<a href="'.  esc_url( $gp_url ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].'  pp-ssb-gp" title="'.  esc_attr( __('Subscribe on Google Podcasts', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Google Podcasts', 'powerpress') ) .'</span></a>';
        }

        if(  !empty($settings['subscribe_feature_spotify_sidebar']) && !empty($settings['spotify_url']) ) {
            $settings['spotify_url'] = trim($settings['spotify_url']);
            $html .= '<a href="'.  esc_url( $settings['spotify_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-spotify" title="'.  esc_attr( __('Subscribe on Spotify', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Spotify', 'powerpress') ) .'</span></a>';
        }

        if(  !empty($settings['subscribe_feature_amazon_sidebar']) && !empty($settings['amazon_url']) ) {
            $settings['amazon_url'] = trim($settings['amazon_url']);
            $html .= '<a href="'.  esc_url( $settings['amazon_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-amazon" title="'.  esc_attr( __('Subscribe on Amazon Music', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Amazon Music', 'powerpress') ) .'</span></a>';
        }

        if( preg_match('/^(https?:\/\/)(.*)$/i', $settings['feed_url'], $matches ) && !empty($settings['subscribe_feature_android_sidebar']) ) {
            $android_url =  $matches[1] . 'subscribeonandroid.com/' . $matches[2];
            $html .= '<a href="'.  esc_url( $android_url ) .'" class="pp-ssb-btn'.$settings['modern_style'].'  '.$settings['modern_direction'].' pp-ssb-android" title="'.  esc_attr( __('Subscribe on Android', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Android', 'powerpress') ) .'</span></a>';
        }

        if(  !empty($settings['subscribe_feature_pandora_sidebar']) && !empty($settings['pandora_url']) ) {
            $settings['pandora_url'] = trim($settings['pandora_url']);
            $html .= '<a href="'.  esc_url( $settings['pandora_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-pandora" title="'.  esc_attr( __('Subscribe on Pandora', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Pandora', 'powerpress') ) .'</span></a>';
        }

        if(  !empty($settings['subscribe_feature_iheart_sidebar']) && !empty($settings['iheart_url']) ) {
            $settings['iheart_url'] = trim($settings['iheart_url']);
            $html .= '<a href="'.  esc_url( $settings['iheart_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-iheartradio" title="'.  esc_attr( __('Subscribe on iHeartRadio', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('iHeartRadio', 'powerpress') ) .'</span></a>';
        }

        if( !empty($settings['subscribe_feature_stitcher_sidebar']) && !empty($settings['stitcher_url']) ) {
            $settings['stitcher_url'] = trim($settings['stitcher_url']);
            $html .= '<a href="'.  esc_url( $settings['stitcher_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-stitcher" title="'.  esc_attr( __('Subscribe on Stitcher', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Stitcher', 'powerpress') ) .'</span></a>';
        }

        if( !empty($settings['subscribe_feature_blubrry_sidebar']) && !empty($settings['blubrry_url']) ) {
            $settings['blubrry_url'] = trim($settings['blubrry_url']);
            $html .= '<a href="'.  esc_url( $settings['blubrry_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-blubrry" title="'.  esc_attr( __('Subscribe on Blubrry', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Blubrry', 'powerpress') ) .'</span></a>';
        }

        if(  !empty($settings['subscribe_feature_jiosaavn_sidebar']) && !empty($settings['jiosaavn_url']) ) {
            $settings['jiosaavn_url'] = trim($settings['jiosaavn_url']);
            $html .= '<a href="'.  esc_url( $settings['jiosaavn_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-jiosaavn" title="'.  esc_attr( __('Subscribe on JioSaavn', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('JioSaavn', 'powerpress') ) .'</span></a>';
        }

        if(  !empty($settings['subscribe_feature_podchaser_sidebar']) && !empty($settings['podchaser_url']) ) {
            $settings['podchaser_url'] = trim($settings['podchaser_url']);
            $html .= '<a href="'.  esc_url( $settings['podchaser_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-podchaser" title="'.  esc_attr( __('Subscribe on Podchaser', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Podchaser', 'powerpress') ) .'</span></a>';
        }

        if(  !empty($settings['subscribe_feature_gaana_sidebar']) && !empty($settings['gaana_url']) ) {
            $settings['gaana_url'] = trim($settings['gaana_url']);
            $html .= '<a href="'.  esc_url( $settings['gaana_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-gaana" title="'.  esc_attr( __('Subscribe on Gaana', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Gaana', 'powerpress') ) .'</span></a>';
        }

        if(  !empty($settings['subscribe_feature_pcindex_sidebar']) && !empty($settings['pcindex_url']) ) {
            $settings['pcindex_url'] = trim($settings['pcindex_url']);
            $html .= '<a href="'.  esc_url( $settings['pcindex_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-pcindex" title="'.  esc_attr( __('Subscribe on Podcast Index', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Podcast Index', 'powerpress') ) .'</span></a>';
        }

        if( preg_match('/^(https?:\/\/)(.*)$/i', $settings['feed_url'], $matches )  ) {
            if( !empty($settings['subscribe_feature_email_sidebar']) ) {
                $email_url =  $matches[1] . 'subscribebyemail.com/' . $matches[2];
                $html .= '<a href="'.  esc_url( $email_url ) .'" class="pp-ssb-btn'.$settings['modern_style'].'  '.$settings['modern_direction'].'  pp-ssb-email" title="'.  esc_attr( __('Subscribe by Email', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('by Email', 'powerpress') ) .'</span></a>';
            }
        }

        if( !empty($settings['subscribe_feature_tunein_sidebar']) && !empty($settings['tunein_url']) ) {
            $settings['tunein_url'] = trim($settings['tunein_url']);
            $html .= '<a href="'.  esc_url( $settings['tunein_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-tunein" title="'.  esc_attr( __('Subscribe on TuneIn', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('TuneIn', 'powerpress') ) .'</span></a>';
        }

        if(  !empty($settings['subscribe_feature_deezer_sidebar']) && !empty($settings['deezer_url']) ) {
            $settings['deezer_url'] = trim($settings['deezer_url']);
            $html .= '<a href="'.  esc_url( $settings['deezer_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-deezer" title="'.  esc_attr( __('Subscribe on Deezer', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('Deezer', 'powerpress') ) .'</span></a>';
        }

		$html .= '<a href="'.  esc_url( $settings['feed_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-rss" title="'.  esc_attr( __('Subscribe via RSS', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('RSS', 'powerpress') ) .'</span></a>';

		
		if( !empty($settings['subscribe_page_url']) )
			$html .= '<a href="'.  esc_url( $settings['subscribe_page_url'] ) .'" class="pp-ssb-btn'.$settings['modern_style'].' '.$settings['modern_direction'].' pp-ssb-more" title="'.  esc_attr( __('More Subscribe Options', 'powerpress') ) .'"><span class="pp-ssb-ic"></span><span class="pp-ssb-text">'.  esc_html( __('More Subscribe Options', 'powerpress') ) .'</span></a>';
	$html .= '</div>';

	return $html;
}