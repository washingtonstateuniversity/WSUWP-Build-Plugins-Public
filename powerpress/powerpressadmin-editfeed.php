<?php

if( !function_exists('add_action') )
	die("access denied.");
	
function powerpress_languages()
{
	// List copied from PodPress:
	$langs = array();
	$langs['af'] = __('Afrikaans', 'powerpress');
	$langs['sq'] = __('Albanian', 'powerpress');
	$langs['ar'] = __('Arabic', 'powerpress');
	$langs['ar-sa'] = __('Arabic (Saudi Arabia)', 'powerpress');
	$langs['ar-eg'] = __('Arabic (Egypt)', 'powerpress');
	$langs['ar-dz'] = __('Arabic (Algeria)', 'powerpress');
	$langs['ar-tn'] = __('Arabic (Tunisia)', 'powerpress');
	$langs['ar-ye'] = __('Arabic (Yemen)', 'powerpress');
	$langs['ar-jo'] = __('Arabic (Jordan)', 'powerpress');
	$langs['ar-kw'] = __('Arabic (Kuwait)', 'powerpress');
	$langs['ar-bh'] = __('Arabic (Bahrain)', 'powerpress');
	$langs['eu'] = __('Basque', 'powerpress');
	$langs['be'] = __('Belarusian', 'powerpress');
	$langs['bg'] = __('Bulgarian', 'powerpress');
	$langs['ca'] = __('Catalan', 'powerpress');
	$langs['zh-cn'] = __('Chinese (Simplified)', 'powerpress');
	$langs['zh-tw'] = __('Chinese (Traditional)', 'powerpress');
	$langs['hr'] = __('Croatian', 'powerpress');
	$langs['cs'] = __('Czech', 'powerpress');
	$langs['cr'] = __('Cree', 'powerpress');
	$langs['da'] = __('Danish', 'powerpress');
	$langs['nl'] = __('Dutch', 'powerpress');
	$langs['nl-be'] = __('Dutch (Belgium)', 'powerpress');
	$langs['nl-nl'] = __('Dutch (Netherlands)', 'powerpress');
	$langs['en'] = __('English', 'powerpress');
	$langs['en-au'] = __('English (Australia)', 'powerpress');
	$langs['en-bz'] = __('English (Belize)', 'powerpress');
	$langs['en-ca'] = __('English (Canada)', 'powerpress');
	$langs['en-ie'] = __('English (Ireland)', 'powerpress');
	$langs['en-jm'] = __('English (Jamaica)', 'powerpress');
	$langs['en-nz'] = __('English (New Zealand)', 'powerpress');
	$langs['en-ph'] = __('English (Phillipines)', 'powerpress');
	$langs['en-za'] = __('English (South Africa)', 'powerpress');
	$langs['en-tt'] = __('English (Trinidad)', 'powerpress');
	$langs['en-gb'] = __('English (United Kingdom)', 'powerpress');
	$langs['en-us'] = __('English (United States)', 'powerpress');
	$langs['en-zw'] = __('English (Zimbabwe)', 'powerpress');
	$langs['et'] = __('Estonian', 'powerpress');
	$langs['fo'] = __('Faeroese', 'powerpress');
	$langs['fi'] = __('Finnish', 'powerpress');
	$langs['fr'] = __('French', 'powerpress');
	$langs['fr-be'] = __('French (Belgium)', 'powerpress');
	$langs['fr-ca'] = __('French (Canada)', 'powerpress');
	$langs['fr-fr'] = __('French (France)', 'powerpress');
	$langs['fr-lu'] = __('French (Luxembourg)', 'powerpress');
	$langs['fr-mc'] = __('French (Monaco)', 'powerpress');
	$langs['fr-ch'] = __('French (Switzerland)', 'powerpress');
	$langs['gl'] = __('Galician', 'powerpress');
	$langs['gd'] = __('Gaelic', 'powerpress');
	$langs['de'] = __('German', 'powerpress');
	$langs['de-at'] = __('German (Austria)', 'powerpress');
	$langs['de-de'] = __('German (Germany)', 'powerpress');
	$langs['de-li'] = __('German (Liechtenstein)', 'powerpress');
	$langs['de-lu'] = __('German (Luxembourg)', 'powerpress');
	$langs['de-ch'] = __('German (Switzerland)', 'powerpress');
	$langs['el'] = __('Greek', 'powerpress');
	$langs['haw'] = __('Hawaiian', 'powerpress');
	$langs['he_IL'] = __('Hebrew', 'powerpress');
	$langs['hu'] = __('Hungarian', 'powerpress');
	$langs['is'] = __('Icelandic', 'powerpress');
	$langs['in'] = __('Indonesian', 'powerpress');
	$langs['ga'] = __('Irish', 'powerpress');
	$langs['it'] = __('Italian', 'powerpress');
	$langs['hi'] = __('Hindi', 'powerpress');
	$langs['it-it'] = __('Italian (Italy)', 'powerpress');
	$langs['it-ch'] = __('Italian (Switzerland)', 'powerpress');
	$langs['ja'] = __('Japanese', 'powerpress');
	$langs['ko'] = __('Korean', 'powerpress');
	$langs['mk'] = __('Macedonian', 'powerpress');
	$langs['no'] = __('Norwegian', 'powerpress');
	$langs['pa'] = __('Punjabi', 'powerpress');
	$langs['pl'] = __('Polish', 'powerpress');
	$langs['pt'] = __('Portuguese', 'powerpress');
	$langs['pt-br'] = __('Portuguese (Brazil)', 'powerpress');
	$langs['pt-pt'] = __('Portuguese (Portugal)', 'powerpress');
	$langs['ro'] = __('Romanian', 'powerpress');
	$langs['ro-mo'] = __('Romanian (Moldova)', 'powerpress');
	$langs['ro-ro'] = __('Romanian (Romania)', 'powerpress');
	$langs['ru'] = __('Russian', 'powerpress');
	$langs['ru-mo'] = __('Russian (Moldova)', 'powerpress');
	$langs['ru-ru'] = __('Russian (Russia)', 'powerpress');
	$langs['sr'] = __('Serbian', 'powerpress');
	$langs['sk'] = __('Slovak', 'powerpress');
	$langs['sl'] = __('Slovenian', 'powerpress');
	$langs['es'] = __('Spanish', 'powerpress');
	$langs['es-ar'] = __('Spanish (Argentina)', 'powerpress');
	$langs['es-bo'] = __('Spanish (Bolivia)', 'powerpress');
	$langs['es-cl'] = __('Spanish (Chile)', 'powerpress');
	$langs['es-co'] = __('Spanish (Colombia)', 'powerpress');
	$langs['es-cr'] = __('Spanish (Costa Rica)', 'powerpress');
	$langs['es-do'] = __('Spanish (Dominican Republic)', 'powerpress');
	$langs['es-ec'] = __('Spanish (Ecuador)', 'powerpress');
	$langs['es-sv'] = __('Spanish (El Salvador)', 'powerpress');
	$langs['es-gt'] = __('Spanish (Guatemala)', 'powerpress');
	$langs['es-hn'] = __('Spanish (Honduras)', 'powerpress');
	$langs['es-mx'] = __('Spanish (Mexico)', 'powerpress');
	$langs['es-ni'] = __('Spanish (Nicaragua)', 'powerpress');
	$langs['es-pa'] = __('Spanish (Panama)', 'powerpress');
	$langs['es-py'] = __('Spanish (Paraguay)', 'powerpress');
	$langs['es-pe'] = __('Spanish (Peru)', 'powerpress');
	$langs['es-pr'] = __('Spanish (Puerto Rico)', 'powerpress');
	$langs['es-es'] = __('Spanish (Spain)', 'powerpress');
	$langs['es-uy'] = __('Spanish (Uruguay)', 'powerpress');
	$langs['es-ve'] = __('Spanish (Venezuela)', 'powerpress');
	$langs['sv'] = __('Swedish', 'powerpress');
	$langs['sv-fi'] = __('Swedish (Finland)', 'powerpress');
	$langs['sv-se'] = __('Swedish (Sweden)', 'powerpress');
	$langs['ta'] = __('Tamil', 'powerpress');
	$langs['th'] = __('Thai', 'powerpress');
	$langs['bo'] = __('Tibetan', 'powerpress');
	$langs['tr'] = __('Turkish', 'powerpress');
	$langs['uk'] = __('Ukranian', 'powerpress');
	$langs['ve'] = __('Venda', 'powerpress');
	$langs['vi'] = __('Vietnamese', 'powerpress');
	$langs['zu'] = __('Zulu', 'powerpress');
    $langs['fa'] = __('Persian', 'powerpress');
    $langs['fa-af'] = __('Persian (Afghanistan)', 'powerpress');
	
	return $langs;
}

function powerpress_admin_capabilities()
{
	global $wp_roles;
	
	$capnames = array();
	// Get Role List
	foreach($wp_roles->role_objects as $key => $role) {
		foreach($role->capabilities as $cap => $grant) {
			$capnames[$cap] = ucwords( str_replace('_', ' ',  $cap) );
		}
	}

	$capnames = apply_filters( 'powerpress_admin_capabilities', array_unique($capnames) );
	
	$remove_keys = array('level_0', 'level_1', 'level_2', 'level_3', 'level_4', 'level_5', 'level_6', 'level_7', 'level_8', 'level_9', 'level_10');
	foreach( $remove_keys as $null=> $key )
		unset($capnames[ $key ]);
	asort($capnames);
	return $capnames;
}


// powerpressadmin_editfeed.php
function powerpress_admin_editfeed($type='', $type_value = '', $feed_slug = false)
{
	$SupportUploads = powerpressadmin_support_uploads();
	$General = powerpress_get_settings('powerpress_general');
	$FeedAttribs = array('type'=>$type, 'feed_slug'=>'', 'category_id'=>0, 'term_taxonomy_id'=>0, 'term_id'=>0, 'taxonomy_type'=>'', 'post_type'=>'');
	$cat_ID = false; $term_taxonomy_id = false;

    if (defined('WP_DEBUG')) {
        if (WP_DEBUG) {
            wp_register_style('powerpress_settings_style',  powerpress_get_root_url() . 'css/settings.css', array(), POWERPRESS_VERSION);
        } else {
            wp_register_style('powerpress_settings_style',  powerpress_get_root_url() . 'css/settings.min.css', array(), POWERPRESS_VERSION);
        }
    } else {
        wp_register_style('powerpress_settings_style',  powerpress_get_root_url() . 'css/settings.min.css', array(), POWERPRESS_VERSION);
    }
    wp_enqueue_style("powerpress_settings_style");

    $MultiSiteServiceSettings = false;
    if( is_multisite() )
    {
        $MultiSiteSettings = get_site_option('powerpress_multisite');
        if( !empty($MultiSiteSettings['services_multisite_only']) )
        {
            $MultiSiteServiceSettings = true;
        }
    }
	
	$FeedTitle = __('Feed Settings', 'powerpress');
	
	switch( $type )
	{
		case 'channel': {
			$feed_slug = $type_value;
			$FeedAttribs['feed_slug'] = $type_value;
			$FeedSettings = powerpress_get_settings('powerpress_feed_'.$feed_slug);
			if( !$FeedSettings && $type_value == 'podcast' ) // We are editing the default podcast channel
			{
				$FeedSettings = powerpress_get_settings('powerpress_feed');
			}
			
			if( !$FeedSettings )
			{
				$FeedSettings = array();
				$FeedSettings['title'] = '';
				if( !empty($General['custom_feeds'][$feed_slug]) )
					$FeedSettings['title'] = $General['custom_feeds'][$feed_slug];
			}
			$FeedSettings = powerpress_default_settings($FeedSettings, 'editfeed_custom');
			
			if( !isset($General['custom_feeds'][$feed_slug]) )
				$General['custom_feeds'][$feed_slug] = __('Podcast (default)', 'powerpress');
				
			$FeedTitle = sprintf( 'Podcast Settings for Channel: %s', htmlspecialchars($General['custom_feeds'][$feed_slug]) );
			echo sprintf('<input type="hidden" name="feed_slug" value="%s" />', $feed_slug);
			echo '<input type="hidden" name="action" value="powerpress-save-channel" />';
			
		}; break;
		case 'category': {
			$cat_ID = $type_value; 
			$FeedAttribs['category_id'] = $type_value;
			$FeedSettings = powerpress_get_settings('powerpress_cat_feed_'.$cat_ID);
			$FeedSettings = powerpress_default_settings($FeedSettings, 'editfeed_custom');
			
			$category = get_category_to_edit($cat_ID);
			$FeedTitle = sprintf( __('Podcast Settings for Category: %s', 'powerpress'), htmlspecialchars($category->name) );
			echo sprintf('<input type="hidden" name="cat" value="%s" />', $cat_ID);
			echo '<input type="hidden" name="action" value="powerpress-save-category" />';
			
		}; break;
		case 'ttid': {
			$term_taxonomy_id = $type_value;
			$FeedAttribs['term_taxonomy_id'] = $type_value;
			$FeedSettings = powerpress_get_settings('powerpress_taxonomy_'.$term_taxonomy_id);
			$FeedSettings = powerpress_default_settings($FeedSettings, 'editfeed_custom');
			
			global $wpdb;
			$term_info = $wpdb->get_results("SELECT term_id, taxonomy FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = $term_taxonomy_id",  ARRAY_A);
			if( !empty( $term_info[0]['term_id']) ) {
				$term_ID = $term_info[0]['term_id'];
				$taxonomy_type = $term_info[0]['taxonomy'];
				$FeedAttribs['term_id'] = $term_ID;
				$FeedAttribs['taxonomy_type'] = $taxonomy_type;

				$term_object = get_term_to_edit($term_ID, $taxonomy_type);
				$FeedTitle = sprintf( __('Podcast Settings for Taxonomy Term: %s', 'powerpress'), htmlspecialchars($term_object->name));
			}
			else
			{
				$FeedTitle = sprintf( __('Podcast Settings for Taxonomy Term: %s', 'powerpress'), 'Term ID '.htmlspecialchars($term_taxonomy_id));
			}
			echo sprintf('<input type="hidden" name="ttid" value="%s" />', $term_taxonomy_id);
			echo '<input type="hidden" name="action" value="powerpress-save-ttid" />';
			
		}; break;
		case 'post_type': {
			
			$FeedAttribs['post_type'] = $type_value;
			$FeedAttribs['feed_slug'] = $feed_slug;
			$FeedSettingsArray = powerpress_get_settings('powerpress_posttype_'.$FeedAttribs['post_type']);
			if( !is_array($FeedSettingsArray[ $feed_slug ]) )
				$FeedSettingsArray[ $feed_slug ] = array();
			$FeedSettings = powerpress_default_settings($FeedSettingsArray[ $feed_slug ], 'editfeed_custom');
			
			//$category = get_category_to_edit($cat_ID);
			$PostTypeTitle = $FeedAttribs['post_type']; // TODO: Get readable title of post type
			$FeedTitle = sprintf( __('Podcast Settings for Post Type %s with slug %s', 'powerpress'), htmlspecialchars($PostTypeTitle) , htmlspecialchars($feed_slug));
			echo sprintf('<input type="hidden" name="podcast_post_type" value="%s" />', $FeedAttribs['post_type']);
			echo sprintf('<input type="hidden" name="feed_slug" value="%s" />', $feed_slug);
			echo '<input type="hidden" name="action" value="powerpress-save-post_type" />';
			
		}; break;
		default: {
			$FeedSettings = powerpress_get_settings('powerpress_feed');
			$FeedSettings = powerpress_default_settings($FeedSettings, 'editfeed');
			echo '<input type="hidden" name="action" value="powerpress-save-settings" />';
		}; break;
	}
		
	
	echo '<h2>'. $FeedTitle .'</h2>';
	
	if( $cat_ID && (isset($_GET['from_categories']) || isset($_POST['from_categories'])) )
	{
		echo '<input type="hidden" name="from_categories" value="1" />';
	}

    wp_enqueue_script('powerpress-admin', powerpress_get_root_url() . 'js/admin.js', array(), POWERPRESS_VERSION );
	
?>
    <div id="powerpress_settings_page" class="powerpress_tabbed_content">
        <div class="pp-tab">
            <button id="welcome-tab" class="tablinks active" onclick="powerpress_openTab(event, 'settings-welcome')"><?php echo htmlspecialchars(__('Welcome', 'powerpress')); ?></button>
            <!-- #tab1 deprecated. was episodes tab -->
            <button id="feeds-tab" class="tablinks" onclick="powerpress_openTab(event, 'settings-feeds')"><?php echo htmlspecialchars(__('Feeds', 'powerpress')); ?></button>
            <?php if( in_array($FeedAttribs['type'], array('category', 'ttid', 'post_type', 'channel') ) ) { ?>
            <button id="website-tab" class="tablinks" onclick="powerpress_openTab(event, 'settings-website')"><?php echo htmlspecialchars(__('Website', 'powerpress')); ?></button>
            <?php } ?>
            <button id="destinations-tab" class="tablinks" onclick="powerpress_openTab(event, 'settings-destinations')"><?php echo htmlspecialchars(__('Destinations', 'powerpress')); ?></button>
            <!-- <button class="tablinks" onclick="openTab(event, 'settings-analytics')"><?php echo htmlspecialchars(__('Analytics', 'powerpress')); ?></button> -->
            <button id="other-tab" class="tablinks" onclick="powerpress_openTab(event, 'settings-other')"><?php echo htmlspecialchars(__('Other', 'powerpress')); ?></button>
        </div>

        <div id="settings-welcome" class="pp-tabcontent active">
            <div class="pp-sidenav">
                <?php
                powerpressadmin_edit_blubrry_services($General);
                ?>
                <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/support/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('POWERPRESS DOCUMENTATION', 'powerpress')); ?></a></div>
                <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/podcast-insider/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('PODCAST INSIDER BLOG', 'powerpress')); ?></a></div>
            </div>
            <button style="display: none;" id="welcome-default-open" class="pp-sidenav-tablinks active" onclick="sideNav(event, 'welcome-all')"><img class="pp-nav-icon" style="width: 22px;" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/rss-symbol.svg"><?php echo htmlspecialchars(__('Hidden button', 'powerpress')); ?></button>
            <div id="welcome-all" class="pp-sidenav-tab active">
                <?php powerpressadmin_welcome($General, $FeedSettings); ?>
            </div>
        </div>

        <div id="settings-feeds" class="pp-tabcontent has-sidenav">
            <div class="pp-sidenav">
                <button id="feeds-default-open" class="pp-sidenav-tablinks active" id="feeds-settings-tab" onclick="sideNav(event, 'feeds-settings')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/option_bar_settings_gray.svg"><?php echo htmlspecialchars(__('Feed Settings', 'powerpress')); ?></button>
                <button class="pp-sidenav-tablinks" id="feeds-artwork-tab" onclick="sideNav(event, 'feeds-artwork')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/camera_gray.svg"><?php echo htmlspecialchars(__('Podcast Artwork', 'powerpress')); ?></button>
                <button class="pp-sidenav-tablinks" id="feeds-basic-tab" onclick="sideNav(event, 'feeds-basic')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/edit_gray.svg"><?php echo htmlspecialchars(__('Basic Show Information', 'powerpress')); ?></button>
                <button class="pp-sidenav-tablinks" id="feeds-rating-tab" onclick="sideNav(event, 'feeds-rating')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/star_favorite_gray.svg"><?php echo htmlspecialchars(__('Rating Settings', 'powerpress')); ?></button>
                <button class="pp-sidenav-tablinks" id="feeds-apple-tab" onclick="sideNav(event, 'feeds-apple')"><span id="apple-icon-feed" class="destinations-side-icon" style="margin-left: 2px;"></span><span class="destination-side-text" style="margin-left: 6px;"><?php echo htmlspecialchars(__('Apple Settings', 'powerpress')); ?></span></button>
                <?php
                powerpressadmin_edit_blubrry_services($General);
                ?>
                <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/support/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('POWERPRESS DOCUMENTATION', 'powerpress')); ?></a></div>
                <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/podcast-insider/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('PODCAST INSIDER BLOG', 'powerpress')); ?></a></div>
            </div>
            <div id="feeds-settings" class="pp-sidenav-tab active">
                <?php powerpressadmin_edit_feed_settings($FeedSettings, $General, $FeedAttribs);
                powerpress_settings_tab_footer(); ?>
            </div>
            <div id="feeds-artwork" class="pp-sidenav-tab">
                <?php powerpressadmin_edit_artwork($FeedSettings, $General);
                powerpress_settings_tab_footer();  ?>
            </div>
            <div id="feeds-basic" class="pp-sidenav-tab">
                <?php powerpressadmin_edit_funding($FeedSettings, $feed_slug);
                powerpress_settings_tab_footer();  ?>
            </div>
            <div id="feeds-rating" class="pp-sidenav-tab">
                <?php powerpressadmin_edit_tv($FeedSettings, $feed_slug);
                powerpress_settings_tab_footer();  ?>
            </div>
            <div id="feeds-apple" class="pp-sidenav-tab">
                <?php powerpressadmin_edit_itunes_feed($FeedSettings, $General, $FeedAttribs);
                powerpress_settings_tab_footer();  ?>
            </div>
        </div>

        <div id="settings-website" class="pp-tabcontent">
            <div class="pp-sidenav">
                <button id="website-default-open" class="pp-sidenav-tablinks active" onclick="sideNav(event, 'website-settings')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/desktop_gray.svg"><?php echo htmlspecialchars(__('Website Settings', 'powerpress')); ?></button>
                <button class="pp-sidenav-tablinks" id="website-shortcodes-tab" onclick="sideNav(event, 'website-shortcodes')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/connection_pattern_gray.svg"><?php echo htmlspecialchars(__('PowerPress Shortcodes', 'powerpress')); ?></button>
                <?php
                powerpressadmin_edit_blubrry_services($General);
                ?>
                <div class="pp-sidenav-extra" style="margin-top: 10%;"><a href="https://www.blubrry.com/support/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('POWERPRESS DOCUMENTATION', 'powerpress')); ?></a></div>
                <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/podcast-insider/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('PODCAST INSIDER BLOG', 'powerpress')); ?></a></div>
            </div>

            <?php
            if( $General === false )
                $General = powerpress_get_settings('powerpress_general');
            $General = powerpress_default_settings($General, 'appearance');
            if( !isset($General['player_function']) )
                $General['player_function'] = 1;
            if( !isset($General['player_aggressive']) )
                $General['player_aggressive'] = 0;
            if( !isset($General['new_window_width']) )
                $General['new_window_width'] = '';
            if( !isset($General['new_window_height']) )
                $General['new_window_height'] = '';
            if( !isset($General['player_width']) )
                $General['player_width'] = '';
            if( !isset($General['player_height']) )
                $General['player_height'] = '';
            if( !isset($General['player_width_audio']) )
                $General['player_width_audio'] = '';
            if( !isset($General['disable_appearance']) )
                $General['disable_appearance'] = false;
            if( !isset($General['subscribe_links']) )
                $General['subscribe_links'] = true;
            if( !isset($General['subscribe_label']) )
                $General['subscribe_label'] = '';
            require_once( dirname(__FILE__).'/views/settings_tab_appearance.php' );

            ?>


            <div id="website-settings" class="pp-sidenav-tab active">
                <?php powerpressadmin_website_settings_custom_feed($General, $FeedSettings, $FeedAttribs);
                powerpress_settings_tab_footer();  ?>
            </div>
            <div id="website-shortcodes" class="pp-sidenav-tab">
                <?php powerpress_shortcode_settings($General, $FeedAttribs);
                powerpress_settings_tab_footer();  ?>
            </div>
        </div>

        <div id="settings-destinations" class="pp-tabcontent">
            <?php
            powerpressadmin_edit_destinations($FeedSettings, $General, $FeedAttribs);
            ?>
        </div>

        <div id="settings-analytics" class="pp-tabcontent">
            <div class="pp-sidenav">
                <?php
                powerpressadmin_edit_blubrry_services($General);
                ?>
            </div>
            <?php
            if( $MultiSiteServiceSettings && defined('POWERPRESS_MULTISITE_VERSION') )
            {
                PowerPressMultiSitePlugin::edit_blubrry_services($General);
            }
            else
            {
                powerpressadmin_edit_media_statistics($General);
            }
            ?>
        </div>

        <div id="settings-other" class="pp-tabcontent">        <div class="pp-sidenav">
            <?php
            powerpressadmin_edit_blubrry_services($General);
            ?>
            <div class="pp-sidenav-extra" style="margin-top: 10%;"><a href="https://www.blubrry.com/support/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('POWERPRESS DOCUMENTATION', 'powerpress')); ?></a></div>
            <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/podcast-insider/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('PODCAST INSIDER BLOG', 'powerpress')); ?></a></div>
        </div>
        <button style="display: none;" id="other-default-open" class="pp-sidenav-tablinks active" onclick="sideNav(event, 'other-all')"><img class="pp-nav-icon" style="width: 22px;" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/rss-symbol.svg"><?php echo htmlspecialchars(__('Hidden button', 'powerpress')); ?></button>
        <div id="other-all" class="pp-sidenav-tab active">
            <?php
            powerpressadmin_settings_tab_other($General, $FeedSettings, $feed_slug, $cat_ID, $FeedAttribs);
            powerpress_settings_tab_footer();
            ?>
        </div>
        </div>

    </div>
    <div class="clear"></div>

<div class="clear"></div>
<?php

		
}

function powerpressadmin_edit_podcast_channel($FeedSettings, $General)
{
	// TODO
?>
<input type="hidden" name="action" value="powerpress-save-customfeed" />
<p style="margin-bottom: 0;">
	<?php echo __('Configure your custom podcast feed.', 'powerpress'); ?>
</p>
<?php
}

function powerpressadmin_edit_category_feed($FeedSettings, $General)
{
?>
<input type="hidden" name="action" value="powerpress-save-categoryfeedsettings" />
<p style="margin-bottom: 0;">
	<?php echo __('Configure your category feed to support podcasting.', 'powerpress'); ?>
</p>
<?php
}

function powerpressadmin_edit_feed_general($FeedSettings, $General, $FeedAttribs)
{
	$warning = '';
	$episode_count = powerpress_get_episode_count('podcast');
	if( $episode_count == 0 )
	{
		$warning = __('WARNING: You must create at least one podcast episode for your podcast feed to be valid.', 'powerpress');
	}

    $feed_link = '';
    switch( $FeedAttribs['type'])
    {
        case 'category': {
            if( !empty($General['cat_casting_podcast_feeds']) )
                $feed_link = get_category_feed_link($FeedAttribs['category_id'], 'podcast');
            else // Use the old link
                $feed_link = get_category_feed_link($FeedAttribs['category_id']);
        }; break;
        case 'ttid': {
            $feed_link = get_term_feed_link($FeedAttribs['term_taxonomy_id'], $FeedAttribs['taxonomy_type'], 'rss2');
        }; break;
        case 'post_type': {
            $feed_link = get_post_type_archive_feed_link($FeedAttribs['post_type'], $FeedAttribs['feed_slug']);
        }; break;
        case 'channel': {
            $feed_link = get_feed_link($FeedAttribs['feed_slug']);
        }; break;
        default: {
            $feed_link = get_feed_link('podcast');
        }; break;
    }
	
	if( !isset($FeedSettings['apply_to']) )
		$FeedSettings['apply_to'] = 1;
?>
<h1 class="pp-heading"><?php echo __('Podcast Feeds', 'powerpress'); ?></h1>
<p class="pp-settings-text"><?php echo __('Your podcast RSS feed: ', 'powerpress'); ?>
    <a href="<?php echo esc_attr($feed_link); ?>"> <?php echo esc_attr($feed_link); ?> </a>
</p>

<div class="pp-settings-section">
    <h2><?php echo __('Enhance Feeds', 'powerpress'); ?></h2>
    <ul>
        <li>
            <input class="pp-settings-radio" type="radio" name="Feed[apply_to]" value="1" <?php if( $FeedSettings['apply_to'] == 1 ) echo 'checked'; ?> />
            <div class="pp-settings-subsection">
                <p class="pp-main"><?php echo __('Enhance All Feeds', 'powerpress'); ?> (<?php echo __('Recommended', 'powerpress'); ?>)</p>
                <p class="pp-sub"><?php echo __('Adds podcasting support to all feeds', 'powerpress'); ?></p>
            </div>
        </li>
        <li>
            <input class="pp-settings-radio" type="radio" name="Feed[apply_to]" value="2" <?php if( $FeedSettings['apply_to'] == 2 ) echo 'checked'; ?> />
            <div class="pp-settings-subsection">
                <p class="pp-main"><?php echo __('Enhance Main Feed Only', 'powerpress'); ?></p>
                <p class="pp-sub"><?php echo __('Adds podcasting support to your main feed only', 'powerpress'); ?></p>
            </div>
        </li>
        <li>
            <input class="pp-settings-radio" type="radio" name="Feed[apply_to]" value="0" <?php if( $FeedSettings['apply_to'] == 0 ) echo 'checked'; ?> />
            <div class="pp-settings-subsection">
                <p class="pp-main"><?php echo __('Do Not Enhance Feeds', 'powerpress'); ?></p>
                <p class="pp-sub"><?php echo __('Feed Settings below will only apply to your podcast channel feeds', 'powerpress'); ?></p>
            </div>
        </li>
    </ul>
</div>
<div class="pp-settings-section">
    <h2><?php echo __('Podcast Feeds', 'powerpress'); ?></h2>
<?php if( $warning ) { ?>
<span class="powerpress-error" style="background-color: #FFEBE8; border-color: #CC0000; padding: 6px 10px;"><?php echo $warning; ?></span>
<?php } ?>
<?php
	
	//$General = get_option('powerpress_general');
	$Feeds = array('podcast'=> __('Special Podcast only Feed', 'powerpress') );
	if( isset($General['custom_feeds']['podcast']) )
		$Feeds = $General['custom_feeds'];
	else if( isset($General['custom_feeds'])&& is_array($General['custom_feeds']) )
		$Feeds += $General['custom_feeds'];
		
	foreach( $Feeds as $feed_slug=> $feed_title )
	{
		if( empty($feed_title) )
			$feed_title = $feed_slug;
		$edit_link = admin_url( 'admin.php?page=powerpress/powerpressadmin_customfeeds.php&amp;action=powerpress-editfeed&amp;feed_slug=') . $feed_slug;
?>
        <p class="pp-settings-text-with-label"><b><?php echo $feed_title; ?></b></p>
        <p class="pp-label-bottom"><a href="<?php echo get_feed_link($feed_slug); ?>" title="<?php echo $feed_title; ?>" target="_blank"><?php echo get_feed_link($feed_slug); ?></a>
	<?php if( defined('POWERPRESS_FEEDVALIDATOR_URL') ) { ?>
	- <a href="<?php echo POWERPRESS_FEEDVALIDATOR_URL. urlencode(get_feed_link($feed_slug)); ?>" target="_blank"><?php echo __('Validate Feed', 'powerpress'); ?></a>
	<?php } ?>
	<?php if( false && $feed_slug != 'podcast' ) { ?>
	- <a href="<?php echo $edit_link; ?>" title="<?php echo __('Edit Podcast Channel', 'powerpress'); ?>"><?php echo __('Edit', 'powerpress'); ?></a>
	<?php } ?>
        </p>
<?php } ?>
<p class="pp-settings-text"><?php echo __('These are podcast only feeds suitable for submission podcast directories such as Apple Podcasts.', 'powerpress'); ?></p>
<p style="margin-bottom: 3ch;" class="description pp-settings-text"><?php echo __('Note: We do not recommend submitting your main site feed to podcast directories such as iTunes. iTunes and many other podcast directories work best with feeds that do not have regular blog posts mixed in.', 'powerpress');  ?></p>

<div>
    <input type="hidden" name="General[feed_action_hook]" value="0" />
    <input class="pp-settings-checkbox" type="checkbox" name="General[feed_action_hook]" value="1" <?php if( !empty($General['feed_action_hook']) && $General['feed_action_hook'] == 1 ) echo 'checked '; ?>/>
    <div class="pp-settings-subsection">
        <p class="pp-settings-text"><?php echo __('Do not allow other plugins to modify podcast feeds.', 'powerpress'); ?></p>
    </div>
</div>
<div>
    <input type="hidden" name="General[feed_accel]" value="0" />
    <input class="pp-settings-checkbox" type="checkbox" name="General[feed_accel]" value="1" <?php if( !empty($General['feed_accel']) && $General['feed_accel'] == 1 ) echo 'checked '; ?>/>
    <div class="pp-settings-subsection">
        <p class="pp-settings-text"><?php echo __('Accelerate feed', 'powerpress'); ?></p>
    </div>
</div>

</div>

<div class="pp-settings-section">
    <h2><?php echo __('Feed Discovery', 'powerpress'); ?></h2>
    <input class="pp-settings-checkbox" type="checkbox" name="General[feed_links]" value="1" <?php if( !empty($General['feed_links']) && $General['feed_links'] == 1 ) echo 'checked '; ?>/>
    <div class="pp-settings-subsection">
        <p class="pp-main"><?php echo __('Include podcast feed links in HTML headers.', 'powerpress'); ?>
        <p class="pp-label-bottom"><?php echo __('Adds "feed discovery" links to your web site\'s headers allowing web browsers and feed readers to auto-detect your podcast feeds.', 'powerpress'); ?></p></p>
    </div>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('RSS2 Image', 'powerpress'); ?></h2>
    <input type="hidden" name="General[disable_rss_image]" value="1" />
    <input class="pp-settings-checkbox" type="checkbox" name="General[disable_rss_image]" value="0" <?php if( empty($General['disable_rss_image']) ) echo 'checked '; ?>/>
    <div class="pp-settings-subsection">
        <p class="pp-main"><?php echo __('Include RSS Image in feeds.', 'powerpress'); ?></label></p>
    </div>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('Emoji', 'powerpress'); ?></h2>
<?php
if( 'utf8mb4' !=  $GLOBALS['wpdb']->charset )
{
?>
<p class="pp-settings-text" style="font-weight: bold; color: #CC0000;">
	<?php 
		echo __('Emoji may not be supported with your WordPress installation. Please upgrade your database to support utf8mb4 available in WordPress 4.2 and newer.', 'powerpress');
		?>
	</p>
<?php }  ?>
<input type="hidden" name="General[rss_emoji]" value="0" />
<input class="pp-settings-checkbox" type="checkbox" name="General[rss_emoji]" value="1" <?php if( !empty($General['rss_emoji']) ) echo 'checked '; ?>/>
    <div class="pp-settings-subsection">
        <p class="pp-main"><?php echo __('Include Emoji in feeds.', 'powerpress'); ?></p>
        <p class="pp-label-bottom"><a href="https://create.blubrry.com/resources/powerpress/powerpress-settings/feeds/#emoji" target="_blank"><?php echo __('Learn more', 'powerpress'); ?></a></p>
    </div>
</div>
<?php
}

function powerpressadmin_edit_feed_settings($FeedSettings, $General, $FeedAttribs = array() )
{
	$SupportUploads = powerpressadmin_support_uploads();
	if( !isset($FeedSettings['posts_per_rss']) )
		$FeedSettings['posts_per_rss'] = '';
	if( !isset($FeedSettings['rss2_image']) )
		$FeedSettings['rss2_image'] = '';
	if( !isset($FeedSettings['copyright']) )
		$FeedSettings['copyright'] = '';
	if( !isset($FeedSettings['title']) )
		$FeedSettings['title'] = '';
	if( !isset($FeedSettings['rss_language']) )
		$FeedSettings['rss_language'] = '';
		
	$feed_link = '';
	switch( $FeedAttribs['type'])
	{
		case 'category': {
			if( !empty($General['cat_casting_podcast_feeds']) )
				$feed_link = get_category_feed_link($FeedAttribs['category_id'], 'podcast');
			else // Use the old link
				$feed_link = get_category_feed_link($FeedAttribs['category_id']);
		}; break;
		case 'ttid': {
			$feed_link = get_term_feed_link($FeedAttribs['term_taxonomy_id'], $FeedAttribs['taxonomy_type'], 'rss2');
		}; break;
		case 'post_type': {
			$feed_link = get_post_type_archive_feed_link($FeedAttribs['post_type'], $FeedAttribs['feed_slug']);
		}; break;
		case 'channel': {
			$feed_link = get_feed_link($FeedAttribs['feed_slug']);
		}; break;
		default: {
			$feed_link = get_feed_link('podcast');
		}; break;
	}
	
	$cat_ID = $FeedAttribs['category_id'];

?>
<h1 class="pp-heading"><?php echo __('Feed Settings', 'powerpress'); ?></h1>
<p class="pp-settings-text"><?php echo __('Your podcast RSS feed: ', 'powerpress'); ?>
    <a href="<?php echo esc_attr($feed_link); ?>"> <?php echo esc_attr($feed_link); ?> </a>
</p>

<div class="pp-settings-section">
    <h2><?php echo __('Feed Title', 'powerpress'); ?></h2>
    <label for="Feed[title]" class="pp-settings-label"><?php echo __('Show Title', 'powerpress'); ?></label>
    <input class="pp-settings-text-input" type="text" name="Feed[title]" value="<?php echo esc_attr($FeedSettings['title']); ?>" maxlength="255" />
    <label for="Feed[title]" class="pp-settings-label-under">
<?php if( $cat_ID ) { ?>
<?php echo __('Leave blank to use default category title', 'powerpress'); ?>
<?php } else { ?>
<?php echo __('Leave blank to use blog title for the show', 'powerpress'); ?>
<?php } ?>
    </label>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('Feed Description', 'powerpress'); ?></h2>
    <label for="Feed[description]" class="pp-settings-label"><?php echo __('Show Description', 'powerpress'); ?></label>
    <input class="pp-settings-text-input" type="text" name="Feed[description]"  value="<?php echo esc_attr( !empty($FeedSettings['description'])? $FeedSettings['description']:''); ?>" maxlength="1000" />
    <label for="Feed[description]" class="pp-settings-label-under">
<?php if( $cat_ID ) { ?>
<?php echo __('Leave blank to use category description', 'powerpress'); ?>
<?php } else { ?>
<?php echo __('Leave blank to use blog description', 'powerpress'); ?>
<?php } ?>
    </label>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('Feed Landing Page URL', 'powerpress'); ?></h2>
    <input class="pp-settings-text-input" type="text" name="Feed[url]"  value="<?php echo esc_attr( !empty($FeedSettings['url'])? $FeedSettings['url']:''); ?>" maxlength="255" />
    <label for="Feed[url]" class="pp-settings-label-under">
<?php if( $cat_ID ) { ?>
<?php echo __('Leave blank to use category page', 'powerpress'); ?>
<?php } else { ?>
<?php echo __('Leave blank to use home page', 'powerpress'); ?>
<?php } ?>
<?php if( $cat_ID ) { ?>
    <p class="description"><?php echo __('Category page URL', 'powerpress'); ?>: <?php echo get_category_link($cat_ID); ?></p>
<?php } else { ?>
    <p class="description">e.g. <?php echo get_bloginfo('url'); ?>/custom-page/</p>
<?php } ?>
    </label>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('PodcastMirror Feed URL', 'powerpress'); ?><br /></h2>
    <label for="Feed[feed_redirect_url]" class="pp-settings-label"><?php echo __('URL', 'powerpress'); ?></label>
    <input class="pp-settings-text-input" type="text" name="Feed[feed_redirect_url]" value="<?php echo esc_attr(!empty($FeedSettings['feed_redirect_url'])? $FeedSettings['feed_redirect_url']:''); ?>" maxlength="100" />
    <label for="Feed[feed_redirect_url]" class="pp-settings-label-under">
        <?php echo __('Leave blank to use built-in feed', 'powerpress'); ?>
    </label>
    <span class="pp-right-label" style="float: right;"><a href="https://podcastmirror.com" target="_blank"><?php echo __('Learn more', 'powerpress'); ?></a></span>
    <p style="margin-top: 1em;" class="pp-settings-text"><?php echo __('Use this option to mirror your podcast feed to provide fast, scalable subscriptions to your show. This option is not required. Service is FeedBurner compatible.', 'powerpress'); ?></p>
<?php
$link = $feed_link;
if( strstr($link, '?') )
	$link .= "&redirect=no";
else
	$link .= "?redirect=no";
?>
    <p class="pp-settings-text"><?php echo __('Bypass Redirect URL', 'powerpress'); ?>: <a href="<?php echo $link; ?>" target="_blank"><?php echo $link; ?></a></p>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('Show the most recent', 'powerpress'); ?></h2>
    <p class="pp-settings-text"><?php echo __('Please enable the Feed Episode Maximizer option to optimize your feed for more than 10 episodes.', 'powerpress'); ?></p>
    <label for="Feed[posts_per_rss]" class="pp-settings-label"><?php echo __('Show the most recent', 'powerpress'); ?></label>
    <input class="pp-settings-text-input" type="text" name="Feed[posts_per_rss]" value="<?php echo ( !empty($FeedSettings['posts_per_rss'])? $FeedSettings['posts_per_rss']:''); ?>" maxlength="5" />
    <label for="Feed[posts_per_rss]" class="pp-settings-label-under">
        <?php echo sprintf(__('episodes / posts per feed (site default: %d, maximum: %d)', 'powerpress'), get_option('posts_per_rss'), 300); ?>
    </label>
    </div>

<?php
	if( in_array($FeedAttribs['type'], array('channel', 'category', 'post_types', 'general')) )
	{
?>
<div class="pp-settings-section">
    <h2><?php echo __('Feed Episode Maximizer', 'powerpress'); ?></h2>
    <input class="pp-settings-checkbox" type="checkbox" name="Feed[maximize_feed]" value="1" <?php if( !empty($FeedSettings['maximize_feed']) ) echo 'checked'; ?> />
    <div class="pp-settings-subsection">
        <p class="pp-main"><?php echo __('Maximize the number of episodes while maintaining an optimal feed size.', 'powerpress'); ?></p>
        <p class="pp-settings-text"><a href="https://create.blubrry.com/resources/powerpress/powerpress-settings/feeds/#maximizer" target="_blank"><?php echo __('Learn more', 'powerpress'); ?></a></p>
    </div>
</div>

<?php
	}
?>

<div class="pp-settings-section">
    <h2><?php echo __('Feed Language', 'powerpress'); ?></h2>
    <select class="pp-settings-select" name="Feed[rss_language]">
<?php
$Languages = powerpress_languages();

echo '<option value="">'. __('Blog Default Language', 'powerpress') .'</option>';
foreach( $Languages as $value=> $desc )
	echo "\t<option value=\"$value\"". ($FeedSettings['rss_language']==$value?' selected':''). ">". esc_attr($desc)."</option>\n";
?>
    </select>
    <label class="pp-settings-label-under" for="Feed[rss_language]">
<?php
	$rss_language = get_bloginfo_rss('language');
	$rss_language = strtolower($rss_language);
if( isset($Languages[ $rss_language ]) )
{
?>
 <?php echo __('Blog Default', 'powerpress'); ?>: <?php echo $Languages[ $rss_language ]; ?>
 <?php } else {  ?>
<?php echo __('Blog Default', 'powerpress'); ?>: <?php echo $rss_language; ?>
 <?php } ?>
    </label>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('Copyright', 'powerpress'); ?></h2>
    <label for="Feed[copyright]" class="pp-settings-label"><?php echo __('Copyright text', 'powerpress'); ?></label>
    <input class="pp-settings-text-input" type="text" name="Feed[copyright]" value="<?php echo esc_attr($FeedSettings['copyright']); ?>" maxlength="255" />
</div>

<div class="pp-settings-section">
    <h2><?php echo __('Caching Debug Comments', 'powerpress'); ?></h2>
    <input class="pp-settings-checkbox" type="checkbox" name="General[allow_feed_comments]" value="1" <?php if( !empty($General['allow_feed_comments']) ) echo 'checked'; ?> />
    <div class="pp-settings-subsection">
        <p class="pp-main">
	    <?php echo __('Allow WP Super Cache or W3 Total Cache to add HTML Comments to the bottom of your feeds', 'powerpress'); ?>
	    (<?php echo __('Recommended unchecked', 'powerpress'); ?>)
        </p>
    </div>
    <p class="pp-label-bottom" style="margin-top: 2ch;"><?php echo __('iTunes is known to have issues with feeds that have HTML comments at the bottom.', 'powerpress'); ?>
    <?php echo __('NOTE: This setting should only be enabled for debugging purposes.', 'powerpress'); ?></p>

</div>

<?php

}


function powerpressadmin_settings_tab_other($General, $FeedSettings, $feed_slug, $cat_ID = false,  $FeedAttribs = array() ) {

	require_once( dirname(__FILE__).'/views/settings_tab_other.php' );
}

function powerpressadmin_settings_tab_appearance($General, $FeedSettings, $FeedAttribs = array()) {
	require_once( dirname(__FILE__).'/views/settings_tab_appearance.php' );
}

function powerpressadmin_edit_appearance_feed($General,  $FeedSettings, $feed_slug)
{
	// Appearance Settings
?>
<h3><?php echo __('Website Settings', 'powerpress'); ?></h3>
<table class="form-table">
<tr valign="top">
<th scope="row">
<?php echo __('Disable Player', 'powerpress'); ?>
</th>
<td>
	<input name="DisablePlayerFor" type="checkbox" <?php if( isset($General['disable_player'][$feed_slug]) ) echo 'checked '; ?> value="1" /> <?php echo __('Do not display web player or links for this podcast.', 'powerpress'); ?>
	<input type="hidden" name="UpdateDisablePlayer" value="<?php echo $feed_slug; ?>" />
</td>
</tr>
</table>
<?php

}

function powerpressadmin_edit_itunes_feed($FeedSettings, $General, $FeedAttribs = array() )
{
	$feed_slug = $FeedAttribs['feed_slug'];
	$cat_ID = $FeedAttribs['category_id'];
	
	$SupportUploads = powerpressadmin_support_uploads();
	if( !isset($FeedSettings['itunes_subtitle']) )
		$FeedSettings['itunes_subtitle'] = '';
	if( !isset($FeedSettings['itunes_summary']) )
		$FeedSettings['itunes_summary'] = '';
	if( !isset($FeedSettings['itunes_keywords']) )
		$FeedSettings['itunes_keywords'] = '';	
	if( !isset($FeedSettings['itunes_cat_1']) )
		$FeedSettings['itunes_cat_1'] = '';
	if( !isset($FeedSettings['itunes_cat_2']) )
		$FeedSettings['itunes_cat_2'] = '';
	if( !isset($FeedSettings['itunes_cat_3']) )
		$FeedSettings['itunes_cat_3'] = '';
    if( !isset($FeedSettings['apple_cat_1']) )
        $FeedSettings['apple_cat_1'] = '';
	if( !isset($FeedSettings['apple_cat_2']) )
        $FeedSettings['apple_cat_2'] = '';
	if( !isset($FeedSettings['apple_cat_3']) )
        $FeedSettings['apple_cat_3'] = '';
	if( !isset($FeedSettings['itunes_explicit']) )
		$FeedSettings['itunes_explicit'] = 0;
	if( !isset($FeedSettings['itunes_talent_name']) )
		$FeedSettings['itunes_talent_name'] = '';
	if( !isset($FeedSettings['email']) )
		$FeedSettings['email'] = '';
	if( !isset($FeedSettings['itunes_new_feed_url_podcast']) )
		$FeedSettings['itunes_new_feed_url_podcast'] = '';
	if( !isset($FeedSettings['itunes_new_feed_url']) )
		$FeedSettings['itunes_new_feed_url'] = '';
	if( !isset($FeedSettings['itunes_type']) )
		$FeedSettings['itunes_type'] = '';
		
	$AdvancediTunesSettings = !empty($FeedSettings['itunes_summary']);
	if( !empty($FeedSettings['itunes_subtitle']) )
		$AdvancediTunesSettings = true;
    //Logic to convert old iTunes categories to 2019 ones.
    $CatArray = array('cat_1' => $FeedSettings['itunes_cat_1'], 'cat_2' => $FeedSettings['itunes_cat_2'], 'cat_3' => $FeedSettings['itunes_cat_3']);
    $mappings = array('01-00' => '01-00', '01-01' => '01-02', '01-02' => '01-03', '01-03' => '01-04', '01-04' => '01-01',
                      '01-05' => '01-05', '01-06' => '01-06', '02-00' => '02-00', '02-02' => '02-01', '02-03' => '02-03',
                      '03-00' => '03-00', '04-00' => '04-00', '04-04' => '04-03', '05-04' => '10-05', '07-00' => '07-00', '07-01' => '07-01', '07-04' => '07-06',
                      '08-00' => '09-00', '09-00' => '11-00', '11-00' => '13-00', '11-01' => '13-01', '11-02' => '13-02',
                      '11-03' => '13-03', '11-04' => '13-04', '11-05' => '13-05', '11-07' => '13-07', '12-02' => '14-06',
                      '12-03' => '14-09', '13-00' => '15-00', '13-02' => '15-02', '13-03' => '15-03', '13-04' => '15-04','14-03' => '16-15',
                      '15-00' => '17-00', '16-00' => '19-00');
    foreach($CatArray as $key => $value) {
        if(empty($FeedSettings['apple_'.$key])) {
            if ($value === '02-01') {
                // Business > Business News (02-01) to News > Business News (11-01)
                $FeedSettings['apple_' . $key] = '12-01';
            } else if ($value === '15-02') {
                // Technology > Tech News (15-02) to News > Tech News (11-07)
                $FeedSettings['apple_' . $key] = '12-07';
            } else if ($value === '13-01') {
                // Society & Culture > History (13-01) to History (08-00)
                $FeedSettings['apple_' . $key] = '08-00';
            } else if ($value === '12-01') {
                // Science & Medicine > Medicine (12-01) to Health > Medicine (07-03)
                $FeedSettings['apple_' . $key] = '07-03';
            } else if($value === '05-01') {
                // Games & Hobbies > Automotive (05-01) to Leisure > Automotive (10-02)
                $FeedSettings['apple_'. $key] = '10-02';
            } else if($value === '05-02') {
                // Games & Hobbies > Aviation (05-02) to Leisure > Aviation (10-03)
                $FeedSettings['apple_'. $key] = '10-03';
            } else if($value == '05-03') {
                // Games & Hobbies > Hobbies (05-03) to Leisure > Hobbies (10-06)
                $FeedSettings['apple_'. $key] = '10-06';
            } else if($value == '05-05') {
                // Games & Hobbies > Video Games (05-05) to Leisure > Video Games (10-08)
                $FeedSettings['apple_'. $key] = '10-08';
            } else if (array_key_exists($value, $mappings)) {
                //Category has a 1:1 mapping
                $FeedSettings['apple_'. $key] = $mappings[$value];
            }
        }
    }
?>

<h1 class="pp-heading"><?php echo __('Apple Settings', 'powerpress'); ?></h1>
<p class="pp-settings-text">
    <?php echo __('The following settings will affect the display of your podcast\'s listing on Apple Podcasts, or when/how your podcast appears in Apple Search results.','powerpress'); ?>
</p>
<div class="pp-settings-section">
    <h2><?php echo __('Program Subtitle', 'powerpress'); ?> <br /></h2>
    <input type="text" class="pp-settings-text-input" name="Feed[itunes_subtitle]"  value="<?php echo esc_attr($FeedSettings['itunes_subtitle']); ?>" maxlength="255" />
</div>

<div class="pp-settings-section">
    <h2><?php echo __('Program Summary', 'powerpress'); ?></h2>
    <p class="pp-main"><?php echo __('Your summary cannot exceed 4,000 characters in length and should not include HTML, except for hyperlinks', 'powerpress'); ?></p>
    <textarea name="Feed[itunes_summary]" class="pp-settings-text-input" rows="5" ><?php echo esc_textarea($FeedSettings['itunes_summary']); ?></textarea>
    <input type="hidden" name="General[itunes_cdata]" value="0" />
    <input type="checkbox" class="pp-settings-checkbox" name="General[itunes_cdata]" value="1" <?php echo ( !empty($General['itunes_cdata'])?'checked ':''); ?>/>
    <div class="pp-settings-subsection" style="border: none;">
        <p class="pp-sub"><?php echo __('Wrap summary values with &lt;![CDATA[ ... ]]&gt; tags', 'powerpress'); ?></p>
    </div>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('Episode Summary', 'powerpress'); ?></h2>
    <input type="checkbox" class="pp-settings-checkbox" name="Feed[enhance_itunes_summary]" value="1" <?php echo ( !empty($FeedSettings['enhance_itunes_summary'])?'checked ':''); ?>/>
    <div class="pp-settings-subsection">
        <p class="pp-main"><?php echo __('Optimize iTunes Summary from Blog Posts', 'powerpress'); ?></p>
        <p class="pp-sub"><?php echo __('Creates a friendlier view of your post/episode content.', 'powerpress'); ?></p>
    </div>
</div>


<?php
		if( !empty($FeedSettings['itunes_keywords']) )
		{
?>
<div class="pp-settings-section">
    <h2><?php echo __('Program Keywords', 'powerpress'); ?></h2>
    <input type="text" class="pp-settings-text-input" name="Feed[itunes_keywords]" style="width: 60%;"  value="<?php echo esc_attr($FeedSettings['itunes_keywords']); ?>" maxlength="255" />
    <p><?php echo __('Feature Deprecated by Apple. Keywords above are for your reference only.', 'powerpress'); ?></p>
</div>
<?php
		} // End iTunes keywords

$MoreCategories = false;
if( !empty($FeedSettings['itunes_cat_2']) )
	$MoreCategories = true;
else if( !empty($FeedSettings['itunes_cat_3']) )
	$MoreCategories = true;

$Categories = powerpress_itunes_categories(true);
$AppleCategories = powerpress_apple_categories(true);

?>
    <div class="pp-settings-section" style="display: inline-block;width: 28%;">
        <h2><?php echo __('Category', 'powerpress'); ?><span class="powerpress-required"><?php echo __('Required', 'powerpress'); ?></span></h2>

        <?php
        $errorClass = '';
        if(empty($FeedSettings['itunes_cat_1'])) {
            //don't show an error if there isn't a corresponding itunes category
        }
        else if(empty($FeedSettings['apple_cat_1'])) {
            $errorClass = "class='pp-form-error'";
        }
        ?>
        <select <?php echo $errorClass ?>  name="Feed[apple_cat_1]" class="pp-settings-select" style="width: 80%;">
            <?php

            $AppleCategories = powerpress_apple_categories(true);

            echo '<option value="">'. __('Select Category', 'powerpress') .'</option>';

            foreach( $AppleCategories as $value=> $desc ) {
                echo "\t<option value=\"$value\"" . ($FeedSettings['apple_cat_1'] == $value ? ' selected' : '') . ">" . htmlspecialchars($desc) . "</option>\n";
            }
            ?>
        </select>
            <?php
                if($errorClass == "class='pp-form-error'") {
                    echo '<p style="width: 250px;">';
                    echo __('Please enter an Apple Podcasts category to prepare yourself for the new 2019 categories', 'powerpress');
                    echo '</p>';                }
                else {
                    echo '';
                }
            ?>
    </div>



<!-- start advanced features -->
    <!--<div id="more_itunes_cats" style="display: inline-block;">-->

    <div class="pp-settings-section" style="display: inline-block;width: 28%;">
        <?php
            $errorClass = '';
            if(empty($FeedSettings['itunes_cat_2'])) {
                //don't show an error if there isn't a corresponding itunes caregory
            }
            else if(empty($FeedSettings['apple_cat_2'])) {
                $errorClass = "class='pp-form-error'";
            }
        ?>
        <h2><?php echo __('Category 2', 'powerpress'); ?> </h2>
        <select <?php echo $errorClass ?> name="Feed[apple_cat_2]" class="pp-settings-select" style="width: 80%;">
            <?php

            echo '<option value="">'. __('Select Category', 'powerpress') .'</option>';

            foreach( $AppleCategories as $value=> $desc ) {

                echo "\t<option value=\"$value\"" . ($FeedSettings['apple_cat_2'] == $value ? ' selected' : '') . ">" . htmlspecialchars($desc) . "</option>\n";
            }

            reset($Categories);
            ?>
        </select>

        <?php
        if($errorClass == "class='pp-form-error'") {
            echo '<p class="pp-settings-text" style="width: 250px;">';
            echo __('Please enter an Apple Podcasts category to prepare yourself for the new 2019 categories', 'powerpress');
            echo '</p>';    }
        else {
            echo '';
        }
        ?>

    </div>

    <div class="pp-settings-section" style="display: inline-block;width: 28%;">
        <?php
            $errorClass = '';
            if(empty($FeedSettings['itunes_cat_3'])) {
                //don't show an error if there isn't a corresponding itunes caregory
            }
            else if(empty($FeedSettings['apple_cat_3'])) {
                $errorClass = "class='pp-form-error'";
            }
        ?>
        <h2><?php echo __('Category 3', 'powerpress'); ?></h2>
        <select <?php echo $errorClass ?> name="Feed[apple_cat_3]" class="pp-settings-select" style="width: 80%;">
            <?php
            echo '<option value="">'. __('Select Category', 'powerpress') .'</option>';
            foreach( $AppleCategories as $value=> $desc ) {
                echo "\t<option value=\"$value\"" . ($FeedSettings['apple_cat_3'] == $value ? ' selected' : '') . ">" . htmlspecialchars($desc) . "</option>\n";
            }

            reset($Categories);
            ?>
        </select>

        <?php
        if($errorClass == "class='pp-form-error'") {
            echo '<p style="width: 250px;">';
            echo __('Please enter an Apple Podcasts category to prepare yourself for the new 2019 categories', 'powerpress');
            echo '</p>';
        }
        else {
            echo '';
        }

        ?>
    </div>

    <!--</div>-->
    <!-- end advanced features -->

    <div class="pp-settings-section">
        <h2><?php echo __('Author Name', 'powerpress'); ?></h2>
        <input type="text" class="pp-settings-text-input" name="Feed[itunes_talent_name]" value="<?php echo esc_attr($FeedSettings['itunes_talent_name']); ?>" maxlength="255" /><br />
        <input type="checkbox" class="pp-settings-checkbox" name="Feed[itunes_author_post]" value="1" <?php echo ( !empty($FeedSettings['itunes_author_post'])?'checked ':''); ?>/>
        <div class="pp-settings-subsection" style="border: none;">
            <p class="pp-sub"><?php echo __('Use blog post author\'s name for individual episodes.', 'powerpress'); ?></p>
        </div>
    </div>

    <div class="pp-settings-section">
        <h2><?php echo __('Author Email', 'powerpress'); ?><span class="powerpress-required"><?php echo __('Required', 'powerpress'); ?></span></h2>
        <input type="text" class="pp-settings-text-input" name="Feed[email]" value="<?php echo esc_attr($FeedSettings['email']); ?>" maxlength="255" />
        <label for="Feed[email]" class="pp-settings-label-under"><?php echo __('Apple will email this address when your podcast is accepted into the Apple Podcast Directory.', 'powerpress'); ?></label>
    </div>


<div class="pp-settings-section" style="display: inline-block;width: 45%;">
    <h2><?php echo __('Explicit', 'powerpress'); ?><span class="powerpress-required"><?php echo __('Required', 'powerpress'); ?></span></h2>
    <select name="Feed[itunes_explicit]" class="pp-settings-select" style="width: 80%;">
    <?php
    $explicit = array(0=> __('No option selected', 'powerpress'), 1=>__('Yes - explicit content', 'powerpress'), 2=>__('Clean - no explicit content', 'powerpress'));

    foreach( $explicit as $value=> $desc )
        echo "\t<option value=\"$value\"". ($FeedSettings['itunes_explicit']==$value?' selected':''). (($FeedSettings['itunes_explicit']!=0&&$value==0)?'disabled':''). ">$desc</option>\n";

    ?>
    </select>
	<p class="description pp-settings-text"><?php echo __('Note: As of February, 2016, you must select either Yes or Clean.', 'powerpress'); ?><br /><br /></p>
</div>
<div class="pp-settings-section" style="display: inline-block;width: 45%;">
    <h2><?php echo __('Feed Type', 'powerpress'); ?></h2>
    <select name="Feed[itunes_type]" class="pp-settings-select" style="width: 80%;">
        <?php
        $types = array(''=> __('No option selected', 'powerpress'), 'episodic'=>__('Episodic (default)', 'powerpress'), 'serial'=>__('Serial', 'powerpress'));

        foreach( $types as $value=> $desc )
            echo "\t<option value=\"$value\"". ($FeedSettings['itunes_type']==$value?' selected':''). ">$desc</option>\n";

            ?>
    </select>
    <p class="description pp-settings-text">
        <?php echo __('Episodic: displays latest episode first.', 'powerpress'); ?> <br />
        <?php echo __('Serial: displays latest episode last.', 'powerpress'); ?>
    </p>
</div>


    <div class="pp-settings-section">
	    <h2><?php echo __('New Feed URL', 'powerpress'); ?></h2>
		<div id="new_feed_url_step_1" style="display: <?php echo ( !empty($FeedSettings['itunes_new_feed_url']) || !empty($FeedSettings['itunes_new_feed_url_podcast'])  ?'none':'block'); ?>;">
			 <p class="pp-settings-text" style="margin-top: 5px;"><strong><a href="#" onclick="return powerpress_new_feed_url_prompt();"><?php echo __('Set iTunes New Feed URL', 'powerpress'); ?></a></strong></p>
			 <p class="pp-settings-text"><strong>
			 <?php echo __('The Apple New Feed URL option works primarily for Apple\'s Podcast directory only, and should only be used if you are unable to implement a HTTP 301 redirect.', 'powerpress'); ?>
			 <?php echo __('A 301 redirect will route <u>all podcast clients including iTunes</u> to your new feed address.', 'powerpress'); ?>
			 </strong> 
			 </p>
			 <p class="pp-settings-text">
			 <?php echo __('Learn more:', 'powerpress'); ?> <a href="https://create.blubrry.com/manual/syndicating-your-podcast-rss-feeds/changing-your-podcast-rss-feed-address-url/" target="_blank"><?php echo __('Changing Your Podcast RSS Feed Address (URL)', 'powerpress'); ?></a>
			</p>
		</div>
		<div id="new_feed_url_step_2" style="display: <?php echo ( !empty($FeedSettings['itunes_new_feed_url']) || !empty($FeedSettings['itunes_new_feed_url_podcast'])  ?'block':'none'); ?>;">
			<p class="pp-settings-text" style="margin-top: 5px;"><strong><?php echo __('WARNING: Changes made here are permanent. If the New Feed URL entered is incorrect, you will lose subscribers and will no longer be able to update your listing in the iTunes Store.', 'powerpress'); ?></strong></p>
			<p class="pp-settings-text"><strong><?php echo __('DO NOT MODIFY THIS SETTING UNLESS YOU ABSOLUTELY KNOW WHAT YOU ARE DOING.', 'powerpress'); ?></strong></p>
			<p class="pp-settings-text">
				<?php echo htmlspecialchars( sprintf(__('Apple recommends you maintain the %s tag in your feed for at least two weeks to ensure that most subscribers will receive the new New Feed URL.', 'powerpress'), '<itunes:new-feed-url>' ) ); ?>
			</p>
			<p class="pp-settings-text">
			<?php 
			$FeedName = __('Main RSS2 feed', 'powerpress');
			$FeedURL = get_feed_link('rss2');
			if( $cat_ID )
			{
				$category = get_category_to_edit($cat_ID);
				$FeedName = sprintf( __('%s category feed', 'powerpress'), htmlspecialchars($category->name) );
				if( !empty($General['cat_casting_podcast_feeds']) )
					$FeedURL = get_category_feed_link($cat_ID, 'podcast');
				else
					$FeedURL = get_category_feed_link($cat_ID);
			}
			else if( $feed_slug )
			{
				if( !empty($General['custom_feeds'][ $feed_slug ]) )
					$FeedName = $General['custom_feeds'][ $feed_slug ];
				else
					$FeedName = __('Podcast', 'powerpress');
				$FeedName = trim($FeedName).' '.__('feed', 'powerpress');
				$FeedURL = get_feed_link($feed_slug);
			}
			else if( $FeedAttribs['type'] == 'ttid' )
			{
				$term_object = get_term_to_edit($FeedAttribs['term_id'],$FeedAttribs['taxonomy_type']);
				$FeedName = sprintf( __('%s taxonomy term feed', 'powerpress'), htmlspecialchars($term_object->name) );
				$FeedURL = get_term_feed_link($FeedAttribs['term_id'],$FeedAttribs['taxonomy_type'], 'rss2');
			}
			
			echo sprintf(__('The New Feed URL value below will be applied to the %s (%s).', 'powerpress'), $FeedName, $FeedURL);
?>
			</p>
			<p style="margin-bottom: 0;">
				<label class="pp-settings-label"><?php echo __('New Feed URL', 'powerpress'); ?></label>
				<input type="text" class="pp-settings-text-input" name="Feed[itunes_new_feed_url]"  value="<?php echo esc_attr($FeedSettings['itunes_new_feed_url']); ?>" maxlength="255" />
			</p>
			<label class="pp-settings-label-under"><?php echo __('Leave blank for no New Feed URL', 'powerpress'); ?></label>
			
			<p class="pp-settings-text" style="margin-top: 2em;"><a href="https://create.blubrry.com/manual/syndicating-your-podcast-rss-feeds/changing-your-podcast-rss-feed-address-url/" target="_blank"><?php echo __('More information regarding the iTunes New Feed URL is available here.', 'powerpress'); ?></a></p>
			<p class="pp-settings-text">
<?php
			if( !$cat_ID && !$feed_slug )
			{
				if( empty($General['channels']) )
					echo sprintf(__('Please activate the \'Custom Podcast Channels\' Advanced Option to set the new-feed-url for your podcast only feed (%s)', 'powerpress'), get_feed_link('podcast') );
				else
					echo sprintf(__('Please navigate to the \'Custom Podcast Channels\' section to set the new-feed-url for your podcast only feed (%s)', 'powerpress'), get_feed_link('podcast') );
			}
?>
			</p>
		</div>
	</div>


<h1 class="pp-heading" style="margin-bottom: 1em;"><?php echo __('Advanced Options', 'powerpress');  ?></h1>
<div id="permanent_itunes_settings" class="pp-settings-section">

	<div>
		<p class="pp-settings-text" style="margin-bottom: 1em;">
			<strong style="color: #CC0000; font-weight: bold;"><?php echo __('SETTINGS BELOW HAVE PERMANENT CONSEQUENCES.', 'powerpress'); ?></strong>
		</p>
		<p style="margin-bottom: 0;" class="pp-settings-text">
			<?php echo __('Feeds affected', 'powerpress'); ?>: 
		</p>
		<div style="margin-left: 20px;" class="pp-settings-text">
			<?php
			// $General, $feed_slug=false, $cat_ID=false
			
			if( $feed_slug )
			{
				echo '<a href="';
				echo esc_attr( get_feed_link($feed_slug) );
				echo '" target="_blank">';
				echo esc_html( get_feed_link($feed_slug) );
				echo '</a>';
			}
			else if( $cat_ID )
			{
				if( !empty($General['cat_casting_podcast_feeds']) )
					$feed_url = get_category_feed_link($cat_ID, 'podcast');
				else
					$feed_url = get_category_feed_link($cat_ID);
				echo '<a href="';
				echo esc_attr( $feed_url );
				echo '" target="_blank">';
				echo esc_html( $feed_url );
				echo '</a>';
			}
			else
			{
				echo '<a href="';
				echo esc_attr( get_feed_link('') );
				echo '" target="_blank">';
				echo esc_html( get_feed_link('') );
				echo '</a>';
				
				if( empty($General['custom_feeds']['podcast']) )
				{
					echo '<br /><a href="';
					echo esc_attr( get_feed_link('podcast') );
					echo '" target="_blank">';
					echo esc_html( get_feed_link('podcast') );
					echo '</a>';
				}
			}
			
			?>
		</div>
		
	</div>

    <h2><?php echo __('Block', 'powerpress'); ?></h2>
    <input type="checkbox" class="pp-settings-checkbox" name="Feed[itunes_block]" value="1" <?php if( !empty($FeedSettings['itunes_block']) ) echo 'checked'; ?> />
    <div class="pp-settings-subsection">
        <p class="pp-sub"><?php echo __('Prevent the entire podcast from appearing in the Apple Podcast directory.', 'powerpress'); ?></p>
	</div>

    <h2><?php echo __('Complete', 'powerpress'); ?></h2>
    <input type="checkbox" class="pp-settings-checkbox" name="Feed[itunes_complete]" value="1" <?php if( !empty($FeedSettings['itunes_complete']) ) echo 'checked'; ?> />
    <div class="pp-settings-subsection">
        <p class="pp-sub"><?php echo __('Indicate the completion of a podcast. Apple will no longer update your listing in the Apple Podcast directory.', 'powerpress'); ?></p>
	</div>

</div>

<?php
}
	
?>