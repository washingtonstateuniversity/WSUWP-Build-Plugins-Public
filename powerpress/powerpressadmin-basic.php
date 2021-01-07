<?php

function powerpress_admin_basic()
{
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

    $FeedAttribs = array('type'=>'general', 'feed_slug'=>'', 'category_id'=>0, 'term_taxonomy_id'=>0, 'term_id'=>0, 'taxonomy_type'=>'', 'post_type'=>'');
	// feed_slug = channel
	
	$General = powerpress_get_settings('powerpress_general');
	$General = powerpress_default_settings($General, 'basic');
	
	$FeedSettings = powerpress_get_settings('powerpress_feed');
	$FeedSettings = powerpress_default_settings($FeedSettings, 'editfeed');
	
	$CustomFeed = get_option('powerpress_feed_'.'podcast'); // Get the custom podcast feed settings saved in the database
	if( $CustomFeed ) // If they enabled custom podast channels...
	{
		$FeedSettings = powerpress_merge_empty_feed_settings($CustomFeed, $FeedSettings);
		$FeedAttribs['channel_podcast'] = true;
	}
	
	$MultiSiteServiceSettings = false;
	if( is_multisite() )
	{
		$MultiSiteSettings = get_site_option('powerpress_multisite');
		if( !empty($MultiSiteSettings['services_multisite_only']) )
		{
			$MultiSiteServiceSettings = true;
		}
	}

    wp_enqueue_script('powerpress-admin', powerpress_get_root_url() . 'js/admin.js', array(), POWERPRESS_VERSION );

?>
<script type="text/javascript"><!--
function CheckRedirect(obj)
{
	if( obj.value )
	{
		if( obj.value.indexOf('rawvoice') == -1 && obj.value.indexOf('techpodcasts') == -1 && 
			obj.value.indexOf('blubrry') == -1 && obj.value.indexOf('podtrac') == -1 )
		{
			if( !confirm('<?php echo __('The redirect entered is not recongized as a supported statistics redirect service.', 'powerpress'); ?>\n\n<?php echo __('Are you sure you wish to continue with this redirect url?', 'powerpress'); ?>') )
			{
				obj.value = '';
				return false;
			}
		}
	}
	return true;
}

jQuery(document).ready(function($) {

	
	jQuery('#episode_box_player_links_options').change(function () {
		
		var objectChecked = jQuery('#episode_box_player_links_options').attr('checked');
		if(typeof jQuery.prop === 'function') {
			objectChecked = jQuery('#episode_box_player_links_options').prop('checked');
		}
		
		if( objectChecked == true ) {
			jQuery('#episode_box_player_links_options_div').css("display", 'block' );
		}
		else {
			jQuery('#episode_box_player_links_options_div').css("display", 'none' );
			jQuery('.episode_box_no_player_or_links').attr("checked", false );
			jQuery('#episode_box_no_player_and_links').attr("checked", false );
			if(typeof jQuery.prop === 'function') {
				jQuery('.episode_box_no_player_or_links').prop("checked", false );
				jQuery('#episode_box_no_player_and_links').prop("checked", false );
			}
		}
	} );
	
	jQuery('#episode_box_no_player_and_links').change(function () {
		
		var objectChecked = jQuery(this).attr("checked");
		if(typeof jQuery.prop === 'function') {
			objectChecked = jQuery(this).prop("checked");
		}
		
		if( objectChecked == true ) {
			jQuery('.episode_box_no_player_or_links').attr("checked", false );
			if(typeof jQuery.prop === 'function') {
				jQuery('.episode_box_no_player_or_links').prop("checked", false );
			}
		}
	} );

	jQuery('.episode_box_no_player_or_links').change(function () {
		var objectChecked = jQuery(this).attr("checked");
		if(typeof jQuery.prop === 'function') {
			objectChecked = jQuery(this).prop("checked");
		}
		
		if( objectChecked == true) {
			jQuery('#episode_box_no_player_and_links').attr("checked", false );
			if(typeof jQuery.prop === 'function') {
				jQuery('#episode_box_no_player_and_links').prop("checked", false );
			}
		}
	} );
	
	jQuery('#episode_box_feature_in_itunes').change( function() {
		var objectChecked = jQuery('#episode_box_feature_in_itunes').attr('checked');
		if(typeof jQuery.prop === 'function') {
			objectChecked = jQuery('#episode_box_feature_in_itunes').prop('checked');
		}
		if( objectChecked ) {
			jQuery("#episode_box_order").attr("disabled", true);
		} else {
			jQuery("#episode_box_order").removeAttr("disabled");
		}
	});

} );
//-->
</script>
<input type="hidden" name="action" value="powerpress-save-settings" />
<input type="hidden" name="General[pp-gen-settings-tabs]" value="1" />
<input type="hidden" name="PlayerSettings[pp-gen-settings-tabs]" value="1" />

<input type="hidden" id="save_tab_pos" name="tab" value="<?php echo (empty($_POST['tab']) ? "settings-welcome" : $_POST['tab']); ?>" />
<input type="hidden" id="save_sidenav_pos" name="sidenav-tab" value="<?php echo (empty($_POST['sidenav-tab']) ? "" : $_POST['sidenav-tab']); ?>" />

<div id="powerpress_admin_header">
<h2><?php echo __('Blubrry PowerPress Settings', 'powerpress'); ?></h2> 

</div>

<div id="powerpress_settings_page" class="powerpress_tabbed_content">
    <div class="pp-tab">
        <button id="welcome-tab" class="tablinks active" onclick="powerpress_openTab(event, 'settings-welcome')"><?php echo htmlspecialchars(__('Welcome', 'powerpress')); ?></button>
        <!-- #tab1 deprecated. was episodes tab -->
        <button id="feeds-tab" class="tablinks" onclick="powerpress_openTab(event, 'settings-feeds')"><?php echo htmlspecialchars(__('Feeds', 'powerpress')); ?></button>
        <button id="website-tab" class="tablinks" onclick="powerpress_openTab(event, 'settings-website')"><?php echo htmlspecialchars(__('Website', 'powerpress')); ?></button>
        <button id="destinations-tab" class="tablinks" onclick="powerpress_openTab(event, 'settings-destinations')"><?php echo htmlspecialchars(__('Destinations', 'powerpress')); ?></button>
        <!-- <button id="analytics-tab" class="tablinks" onclick="openTab(event, 'settings-analytics')"><?php echo htmlspecialchars(__('Analytics', 'powerpress')); ?></button> -->
        <button id="advanced-tab" class="tablinks" onclick="powerpress_openTab(event, 'settings-advanced')"><?php echo htmlspecialchars(__('Advanced', 'powerpress')); ?></button>
    </div>
	
	<div id="settings-welcome" class="pp-tabcontent active">
        <div class="pp-sidenav">
            <?php
            powerpressadmin_edit_blubrry_services($General);
            ?>
            <div class="pp-sidenav-extra" style="margin-top: 10%;"><a href="https://www.blubrry.com/support/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('POWERPRESS DOCUMENTATION', 'powerpress')); ?></a></div>
            <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/podcast-insider/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('PODCAST INSIDER BLOG', 'powerpress')); ?></a></div>
        </div>
        <button style="display: none;" id="welcome-default-open" class="pp-sidenav-tablinks active" onclick="sideNav(event, 'welcome-all')"><img class="pp-nav-icon" style="width: 22px;" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/rss-symbol.svg"><?php echo htmlspecialchars(__('Hidden button', 'powerpress')); ?></button>
        <div id="welcome-all" class="pp-sidenav-tab active">
	        <?php powerpressadmin_welcome($General, $FeedSettings); ?>
        </div>
	</div>

    <div id="settings-feeds" class="pp-tabcontent has-sidenav">
        <div class="pp-sidenav">
            <div class="pp-sidenav-extra"><p class="pp-sidenav-extra-text"><b><?php echo htmlspecialchars(__('FEED SETTINGS', 'powerpress')); ?></b></p></div>
            <button id="feeds-default-open" class="pp-sidenav-tablinks active" onclick="sideNav(event, 'feeds-feeds')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/megaphone_gray.svg"><?php echo htmlspecialchars(__('Podcast Feeds', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="feeds-settings-tab" onclick="sideNav(event, 'feeds-settings')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/option_bar_settings_gray.svg"><?php echo htmlspecialchars(__('Feed Settings', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="feeds-artwork-tab" onclick="sideNav(event, 'feeds-artwork')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/camera_gray.svg"><?php echo htmlspecialchars(__('Podcast Artwork', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="feeds-seo-tab" onclick="sideNav(event, 'feeds-seo')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/fileboard_checklist_gray.svg"><?php echo htmlspecialchars(__('Podcast SEO', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="feeds-basic-tab" onclick="sideNav(event, 'feeds-basic')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/edit_gray.svg"><?php echo htmlspecialchars(__('Basic Show Information', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="feeds-rating-tab" onclick="sideNav(event, 'feeds-rating')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/star_favorite_gray.svg"><?php echo htmlspecialchars(__('Rating Settings', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="feeds-apple-tab" onclick="sideNav(event, 'feeds-apple')"><span id="apple-icon-feed" class="destinations-side-icon" style="margin-left: 2px;"></span><span class="destination-side-text" style="margin-left: 6px;"><?php echo htmlspecialchars(__('Apple Settings', 'powerpress')); ?></span></button>
            <?php
            powerpressadmin_edit_blubrry_services($General);
            ?>
            <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/support/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('POWERPRESS DOCUMENTATION', 'powerpress')); ?></a></div>
            <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/podcast-insider/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('PODCAST INSIDER BLOG', 'powerpress')); ?></a></div>
        </div>
        <div id="feeds-feeds" class="pp-sidenav-tab active">
            <?php
            powerpressadmin_edit_feed_general($FeedSettings, $General, $FeedAttribs);
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="feeds-settings" class="pp-sidenav-tab">
            <?php
            powerpressadmin_edit_feed_settings($FeedSettings, $General, $FeedAttribs);
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="feeds-artwork" class="pp-sidenav-tab">
            <?php
            powerpressadmin_edit_artwork($FeedSettings, $General);
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="feeds-seo" class="pp-sidenav-tab">
            <?php
            require_once(POWERPRESS_ABSPATH . "/powerpressadmin-search.php");
            powerpress_admin_search();
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="feeds-basic" class="pp-sidenav-tab">
            <?php
            powerpressadmin_edit_funding($FeedSettings);
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="feeds-rating" class="pp-sidenav-tab">
            <?php
            powerpressadmin_edit_tv($FeedSettings);
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="feeds-apple" class="pp-sidenav-tab">
            <?php
            powerpressadmin_edit_itunes_feed($FeedSettings, $General, $FeedAttribs);
            powerpress_settings_tab_footer();
            ?>
        </div>
    </div>

    <div id="settings-website" class="pp-tabcontent">
        <div class="pp-sidenav">
            <div class="pp-sidenav-extra"><p class="pp-sidenav-extra-text"><b><?php echo htmlspecialchars(__('WEBSITE SETTINGS', 'powerpress')); ?></b></p></div>
            <button id="website-default-open" class="pp-sidenav-tablinks active" onclick="sideNav(event, 'website-settings')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/desktop_gray.svg"><?php echo htmlspecialchars(__('Website Settings', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="website-blog-tab" onclick="sideNav(event, 'website-blog')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/file_gray.svg"><?php echo htmlspecialchars(__('Blog Posts and Pages', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="website-subscribe-tab" onclick="sideNav(event, 'website-subscribe')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/profile_plus_round_gray.svg"><?php echo htmlspecialchars(__('Subscribe Page', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="website-shortcodes-tab" onclick="sideNav(event, 'website-shortcodes')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/connection_pattern_gray.svg"><?php echo htmlspecialchars(__('PowerPress Shortcodes', 'powerpress')); ?></button>
            <button class="pp-sidenav-tablinks" id="website-new-window-tab" onclick="sideNav(event, 'website-new-window')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/play_gray.svg"><?php echo htmlspecialchars(__('Play in New Window', 'powerpress')); ?></button>
            <?php
            powerpressadmin_edit_blubrry_services($General);
            ?>
            <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/support/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('POWERPRESS DOCUMENTATION', 'powerpress')); ?></a></div>
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
            $General['subscribe_links'] = false;
        if( !isset($General['subscribe_label']) )
            $General['subscribe_label'] = '';
        require_once( dirname(__FILE__).'/views/settings_tab_appearance.php' );

        ?>


        <div id="website-settings" class="pp-sidenav-tab active">
            <?php
            powerpressadmin_website_settings($General, $FeedSettings);
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="website-blog" class="pp-sidenav-tab">
            <?php
            powerpressadmin_blog_settings($General, $FeedSettings);
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="website-subscribe" class="pp-sidenav-tab">
            <?php
            powerpress_subscribe_settings($General, $FeedSettings);
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="website-shortcodes" class="pp-sidenav-tab">
            <?php
            powerpress_shortcode_settings($General, $FeedAttribs);
            powerpress_settings_tab_footer();
            ?>
        </div>
        <div id="website-new-window" class="pp-sidenav-tab">
            <?php
            powerpressadmin_new_window_settings($General, $FeedSettings);
            powerpress_settings_tab_footer();
            ?>
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
		//powerpressadmin_edit_media_statistics($General);
	}
		?>
	</div>

	<div id="settings-advanced" class="pp-tabcontent">
        <div class="pp-sidenav">
            <?php
            powerpressadmin_edit_blubrry_services($General);
            ?>
            <div class="pp-sidenav-extra" style="margin-top: 10%;"><a href="https://www.blubrry.com/support/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('POWERPRESS DOCUMENTATION', 'powerpress')); ?></a></div>
            <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/podcast-insider/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('PODCAST INSIDER BLOG', 'powerpress')); ?></a></div>
        </div>
	<?php
    powerpressadmin_advanced_options($General, false);
    ?>
    </div>

</div>
<div class="clear"></div>

<?php
}

function powerpressadmin_advanced_options($General, $link_account = false)
{
	// Break the bottom section here out into it's own function
	$ChannelsCheckbox = '';
	if( !empty($General['custom_feeds']) )
		$ChannelsCheckbox = ' onclick="alert(\''.  __('You must delete all of the Podcast Channels to disable this option.', 'powerpress')  .'\');return false;"';
	$CategoryCheckbox = '';
	//if( !empty($General['custom_cat_feeds']) ) // Decided ont to include this warning because it may imply that you have to delete the actual category, which is not true.
	//	$CategoryCheckbox = ' onclick="alert(\'You must remove podcasting from the categories to disable this option.\');return false;"';
?>
<script language="javascript">

jQuery(document).ready( function() {
	
	jQuery('.pp-expand-section').click( function(e) {
		e.preventDefault();
		
		if( jQuery(this).hasClass('pp-expand-section-expanded') ) {
			jQuery(this).removeClass('pp-expand-section-expanded');
			jQuery(this).parent().next('div').hide(400);
			jQuery(this).blur();
		} else {
			jQuery(this).addClass('pp-expand-section-expanded');
			jQuery(this).parent().next('div').show(400);
			jQuery(this).blur();
		}
	});
});

function goToPodcastSEO() {
    jQuery("#feeds-tab").click();
    jQuery("#feeds-seo-tab").click();
    return false;
}
</script>
<div style="margin-left: 10px;">

    <button style="display: none;" id="advanced-default-open" class="pp-sidenav-tablinks active" onclick="sideNav(event, 'advanced-all')"><img class="pp-nav-icon" style="width: 22px;" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/rss-symbol.svg"><?php echo htmlspecialchars(__('Hidden button', 'powerpress')); ?></button>
	<div id="advanced-all" class="pp-sidenav-tab active">
        <h1 class="pp-heading"><?php echo __('Advanced Settings', 'powerpress'); ?></h1>
		<div>
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="NULL[import_podcast]" value="1" checked disabled />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><a href="<?php echo admin_url('admin.php?page=powerpress/powerpressadmin_import_feed.php'); ?>"><?php echo __('Import Podcast', 'powerpress'); ?></a></p>
                <p class="pp-sub"><?php echo __('Import podcast feed from SoundCloud, LibSyn, PodBean or other podcast service.', 'powerpress'); ?></p>
            </div>
		</div>
		<div>
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="NULL[migrate_media]" value="1" checked disabled />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><a href="<?php echo admin_url('admin.php?page=powerpress/powerpressadmin_migrate.php'); ?>"><?php echo __('Migrate Media', 'powerpress'); ?></a></p>
                <p class="pp-sub"><?php echo __('Migrate media files to Blubrry Podcast Media Hosting with only a few clicks.', 'powerpress'); ?></p>
            </div>
		</div>
		<div>
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="NULL[podcasting_seo]" value="1" checked disabled />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><a id="advanced-tab-seo-link" onclick="goToPodcastSEO();return false;"><?php echo __('Podcasting SEO', 'powerpress'); ?></a></p>
                <p class="pp-sub"><?php echo __('Optimize how your podcast appears in Internet search results.', 'powerpress'); ?></p>
            </div>
		</div>
		
		<div>
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="NULL[player_options]" value="1" checked disabled />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><?php echo __('Audio Player Options', 'powerpress'); ?></p>
                <p class="pp-sub"><?php echo __('Select from 3 different web based audio players.', 'powerpress'); ?>
                    <b><a href="<?php echo admin_url('admin.php?page=powerpress/powerpressadmin_player.php&sp=1'); ?>">(<?php echo __('configure audio player', 'powerpress'); ?>)</a></b></p>
            </div>
		</div>
		<div>
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="NULL[video_player_options]" value="1" checked disabled />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><?php echo __('Video Player Options', 'powerpress'); ?></p>
                <p class="pp-sub"><?php echo __('Select from 3 different web based video players.', 'powerpress'); ?>
                <b><a href="<?php echo admin_url('admin.php?page=powerpress/powerpressadmin_videoplayer.php&sp=1'); ?>">(<?php echo __('configure video player', 'powerpress'); ?>)</a></b></p>
            </div>
		</div>
		<div>
			<input type="hidden" name="General[channels]" value="0" />
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="General[channels]" value="1" <?php echo ( !empty($General['channels']) ?' checked':''); echo $ChannelsCheckbox; ?> />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><?php echo __('Custom Podcast Channels', 'powerpress'); ?></p>
                <p class="pp-sub"><?php echo __('Manage multiple media files and/or formats to one blog post.', 'powerpress'); ?>
                <?php if( empty($General['channels']) ) { ?>
                (<?php echo __('feature will appear in left menu when enabled', 'powerpress'); ?>)
                <?php } else { ?>
                <b><a href="<?php echo admin_url('admin.php?page=powerpress/powerpressadmin_customfeeds.php'); ?>">(<?php echo __('configure podcast channels', 'powerpress'); ?>)</a></b>
                <?php } ?>
                </p>
            </div>
		</div>
		<div>
			<input type="hidden" name="General[cat_casting]" value="0" />
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="General[cat_casting]" value="1" <?php echo ( !empty($General['cat_casting']) ?' checked':'');  echo $CategoryCheckbox;  ?> />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><?php echo __('Category Podcasting', 'powerpress'); ?></p>
                <p class="pp-sub"><?php echo __('Manage podcasting for specific categories.', 'powerpress'); ?>
                <?php if( empty($General['cat_casting']) ) { ?>
                (<?php echo __('feature will appear in left menu when enabled', 'powerpress'); ?>)
                <?php } else { ?>
                <b><a href="<?php echo admin_url('admin.php?page=powerpress/powerpressadmin_categoryfeeds.php'); ?>">(<?php echo __('configure podcast categories', 'powerpress'); ?>)</a></b>
                <?php } ?>
                </p>
            </div>
		</div>
		
		
		<div>
			<input type="hidden" name="General[taxonomy_podcasting]" value="0" />
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="General[taxonomy_podcasting]" value="1" <?php echo ( !empty($General['taxonomy_podcasting']) ?' checked':''); ?> />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><?php echo __('Taxonomy Podcasting', 'powerpress'); ?>
                <p class="pp-sub"><?php echo __('Manage podcasting for specific taxonomies.', 'powerpress'); ?>
                <?php if( empty($General['taxonomy_podcasting']) ) { ?>
                (<?php echo __('feature will appear in left menu when enabled', 'powerpress'); ?>)
                <?php } else { ?>
                <b><a href="<?php echo admin_url('admin.php?page=powerpress/powerpressadmin_taxonomyfeeds.php'); ?>">(<?php echo __('configure taxonomy podcasting', 'powerpress'); ?>)</a></b>
                <?php } ?>
                </p>
            </div>
		</div>
		<div>
			<input type="hidden" name="General[posttype_podcasting]" value="0" />
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="General[posttype_podcasting]" value="1" <?php echo ( !empty($General['posttype_podcasting']) ?' checked':''); ?> />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><?php echo __('Post Type Podcasting', 'powerpress'); ?></p>
                <p class="pp-sub"><?php echo __('Manage multiple media files and/or formats to specific post types.', 'powerpress'); ?>
                <?php if( empty($General['posttype_podcasting']) ) { ?>
                (<?php echo __('feature will appear in left menu when enabled', 'powerpress'); ?>)
                <?php } else { ?>
                <b><a href="<?php echo admin_url('admin.php?page=powerpress/powerpressadmin_posttypefeeds.php'); ?>">(<?php echo __('configure post type podcasting', 'powerpress'); ?>)</a></b>
                <?php } ?>
                </p>
            </div>
		</div>
		<div>
			<input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="General[playlist_player]" value="1" <?php echo ( !empty($General['playlist_player']) ?' checked':''); ?> />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><?php echo __('PowerPress Playlist Player', 'powerpress'); ?></p>
                <p class="pp-sub"><?php echo __('Create playlists for your podcasts.', 'powerpress'); ?>
                <b><a href="http://create.blubrry.com/resources/powerpress/advanced-tools-and-options/powerpress-playlist-shortcode/" target="_blank">(<?php echo __('learn more', 'powerpress'); ?>)</a></b>
                </p>
            </div>
		</div>
        <div>
            <input class="pp-settings-checkbox" style="margin-top: 3em;" type="checkbox" name="General[powerpress_network]" value="1" <?php echo ( !empty($General['powerpress_network']) ?' checked':''); ?> />
            <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 2em;">
                <p class="pp-main"><?php echo __('PowerPress Network', 'powerpress'); ?></p>
                <p class="pp-sub"><?php echo __('Create a network of podcasts.', 'powerpress'); ?>
                    <b><a href="http://create.blubrry.com/professional-podcast-hosting/podcast-network-plugin/network-plugin-documentation/" target="_blank">(<?php echo __('learn more', 'powerpress'); ?>)</a></b>
                </p>
            </div>
        </div>
        <?php
        powerpressadmin_edit_media_statistics($General);
        powerpress_settings_tab_footer(); ?>
	</div>
</div>

<?php
}


function powerpressadmin_edit_podpress_options($General)
{
	if( !empty($General['process_podpress']) || powerpress_podpress_episodes_exist() )
	{
		if( !isset($General['process_podpress']) )
			$General['process_podpress'] = 0;
		if( !isset($General['podpress_stats']) )	
			$General['podpress_stats'] = 0;
?>

<h3><?php echo __('PodPress Options', 'powerpress'); ?></h3>
<table class="form-table">
<tr valign="top">
<th scope="row">

<?php echo __('PodPress Episodes', 'powerpress'); ?></th> 
<td>
<select name="General[process_podpress]" class="bpp_input_med">
<?php
$options = array(0=>__('Ignore', 'powerpress'), 1=>__('Include in Posts and Feeds', 'powerpress') );

foreach( $options as $value => $desc )
	echo "\t<option value=\"$value\"". ($General['process_podpress']==$value?' selected':''). ">$desc</option>\n";
	
?>
</select>  (<?php echo __('includes podcast episodes previously created in PodPress', 'powerpress'); ?>)
</td>
</tr>
	<?php if( !empty($General['podpress_stats']) || powerpress_podpress_stats_exist() ) { ?>
	<tr valign="top">
	<th scope="row">

	<?php echo __('PodPress Stats Archive', 'powerpress'); ?></th> 
	<td>
	<select name="General[podpress_stats]" class="bpp_input_sm">
	<?php
	$options = array(0=>__('Hide', 'powerpress'), 1=>__('Display', 'powerpress') );

	foreach( $options as $value => $desc )
		echo "\t<option value=\"$value\"". ($General['podpress_stats']==$value?' selected':''). ">$desc</option>\n";
		
	?>
	</select>  (<?php echo __('display archive of old PodPress statistics', 'powerpress'); ?>)
	</td>
	</tr>
	<?php } ?>
	</table>
<?php
	}
}

function powerpressadmin_edit_itunes_general($FeedSettings, $General, $FeedAttribs = array() )
{
	// Set default settings (if not set)
	if( !empty($FeedSettings) )
	{
		if( !isset($FeedSettings['itunes_url']) )
			$FeedSettings['itunes_url'] = '';
	}
	if( !isset($General['itunes_url']) )
		$General['itunes_url'] = '';
	else if( !isset($FeedSettings['itunes_url']) ) // Should almost never happen
		$FeedSettings['itunes_url'] = $General['itunes_url'];
	
	$feed_slug = $FeedAttribs['feed_slug'];
	$cat_ID = $FeedAttribs['category_id'];
	
	if( $feed_slug == 'podcast' && $FeedAttribs['type'] == 'general' )
	{
		if( empty($FeedSettings['itunes_url']) && !empty($General['itunes_url']) )
			$FeedSettings['itunes_url'] = $General['itunes_url'];
	}
	
	$itunes_feed_url = '';

	switch( $FeedAttribs['type'] )
	{
		case 'ttid': {
			$itunes_feed_url = get_term_feed_link($FeedAttribs['term_taxonomy_id'], $FeedAttribs['taxonomy_type'], 'rss2');
		}; break;
		case 'category': {
			if( !empty($General['cat_casting_podcast_feeds']) )
				$itunes_feed_url = get_category_feed_link($cat_ID, 'podcast');
			else
				$itunes_feed_url = get_category_feed_link($cat_ID);
		}; break;
		case 'channel': {
			$itunes_feed_url = get_feed_link($feed_slug);
		}; break;
		case 'post_type': {
			$itunes_feed_url = get_post_type_archive_feed_link($FeedAttribs['post_type'], $feed_slug);
		}; break;
		case 'general':
		default: {
			$itunes_feed_url = get_feed_link('podcast');
		}
	}
	
?>
<h3><?php echo __('iTunes Listing Information', 'powerpress'); ?></h3>

<?php
} // end itunes general

function powerpressadmin_edit_blubrry_services($General, $action_url = false, $action = false)
{
	$DisableStatsInDashboard = false;
	if( !empty($General['disable_dashboard_stats']) )
		$DisableStatsInDashboard = true;

?>
<div id="connect-blubrry-services">
    <?php
    if( !empty($General['blubrry_program_keyword']) ) { ?>
        <div id="blubrry-services-connected-settings">
            <div style="margin-bottom: 1em;">
                <span><img src="<?php echo powerpress_get_root_url(); ?>images/done_24px.svg" style="margin: 0 0 0 8%;vertical-align: text-bottom;"  alt="<?php echo __('Enabled!', 'powerpress'); ?>" /></span>
                <p id="connected-blubrry-blurb"><?php echo __("Connected to <b>Blubrry</b>", 'powerpress'); ?></p>
            </div>
            <a style="display: block;" class="thickbox" title="<?php echo esc_attr(__('Blubrry Services Integration', 'powerpress')); ?>" href="<?php echo admin_url(); echo wp_nonce_url( "admin.php?action=powerpress-jquery-account-edit", 'powerpress-jquery-account-edit'); ?>&amp;KeepThis=true&amp;TB_iframe=true&amp;width=600&amp;height=400&amp;modal=true" target="_blank"><?php echo __('Go to Blubrry Account', 'powerpress'); ?></a>
        </div>
    <?php
    }
	else // Not signed up for hosting?
	{
?>
        <div id="connect-see-options">
            <img id="blubrry-logo-connect" alt="" src="<?php echo powerpress_get_root_url(); ?>images/blubrry_icon.png">
            <h4><?php echo sprintf(__('<b>PowerPress</b> works best with <b>Blubrry</b>', 'powerpress')); ?></h4>
            <p id="connect-blubrry-blurb"><?php echo sprintf(__('Get access to detailed analytics and more by <b>connecting to your Blubrry Hosting Account.</b>', 'powerpress')); ?></p>
            <p style="font-size: 125%; margin: 1ch 0 0 1ch">
                <strong><a class="button-primary  button-blubrry" id="connect-blubrry-button-options"
                           title="<?php echo esc_attr(__('Blubrry Services Info', 'powerpress')); ?>"
                           href="http://create.blubrry.com/resources/podcast-media-hosting/"
                           target="_blank"><?php echo __('SEE MY OPTIONS', 'powerpress'); ?></a></strong>
            </p>
        </div>
        <div id="connect-blubrry-button-container">
            <p style="margin-top: 1ch;" class="pp-settings-text-no-margin"><?php echo __('Already have a Blubrry account?', 'powerpress'); ?></p>
            <p style="font-size: 125%; margin-top: 5px;">
                <strong><button class="button-primary  button-blubrry" id="connect-blubrry-button-options"
                           type="submit" name="blubrry-login" value="1"
                           title="<?php echo esc_attr(__('Blubrry Services Integration', 'powerpress')); ?>">
                        <?php echo __('LET\'S CONNECT', 'powerpress'); ?></button></strong>

            </p>
        </div>
<?php
	} // end not signed up for hosting
	
?>

</div>
<?php
}

function powerpressadmin_edit_media_statistics($General)
{
	if( !isset($General['redirect1']) )
		$General['redirect1'] = '';
	if( !isset($General['redirect2']) )
		$General['redirect2'] = '';
	if( !isset($General['redirect3']) )
		$General['redirect3'] = '';

    $DisableStatsInDashboard = false;
    if( !empty($General['disable_dashboard_stats']) )
        $DisableStatsInDashboard = true;

    $StatsIntegrationURL = '';
	if( !empty($General['blubrry_program_keyword']) )
		$StatsIntegrationURL = 'http://media.blubrry.com/'.$General['blubrry_program_keyword'].'/';
?>
    <script>
        function showSecondRedirectInput(event) {
            event.preventDefault();
            document.getElementById('powerpress_redirect2_table').style.display = 'block';
            document.getElementById('powerpress_redirect2_showlink').style.display='none';

        }
        function showThirdRedirectInput(event) {
            event.preventDefault();
            document.getElementById('powerpress_redirect3_table').style.display='block';
            document.getElementById('powerpress_redirect3_showlink').style.display='none';
        }
    </script>
<div id="blubrry_stats_settings">
<h2><?php echo __('Media Statistics', 'powerpress'); ?></h2>
    <div>
        <input name="DisableStatsInDashboard" class="pp-settings-checkbox" style="margin-top: 1em;" type="checkbox" value="1"<?php if( $DisableStatsInDashboard == true ) echo ' checked'; ?> />
        <div class="pp-settings-subsection" style="border-bottom: none; margin-top: 0;">
            <p class="pp-main"><?php echo __('Remove Statistics from WordPress Dashboard', 'powerpress'); ?></p>
        </div>
    </div>
	<div>
        <h4><?php echo __('REDIRECT URL', 'powerpress'); ?></h4>
        <p class="pp-settings-text-no-margin">
		<?php echo __('Enter your Redirect URL issued by your media statistics service provider below.', 'powerpress'); ?>
		</p>

		<div style="position: relative; padding-bottom: 10px;">
			<table class="form-table">
			<tr valign="top">
			<th scope="row">
			<?php echo __('Redirect URL 1', 'powerpress'); ?> 
			</th>
			<td>
			<input type="text" class="pp-settings-text-input" name="<?php if( stripos($General['redirect1'], $StatsIntegrationURL) !== false ) echo 'NULL[redirect1]'; else echo 'General[redirect1]'; ?>" value="<?php echo esc_attr($General['redirect1']); ?>" onChange="return CheckRedirect(this);" maxlength="255" <?php if( stripos($General['redirect1'], $StatsIntegrationURL) !== false ) { echo ' readOnly="readOnly"';  $StatsIntegrationURL = false; } ?> />
			</td>
			</tr>
			</table>
			<?php if( empty($General['redirect2']) && empty($General['redirect3']) ) { ?>
			<div style="position: absolute;bottom: -2px;left: -40px;" id="powerpress_redirect2_showlink">
				<a href="#" style="margin-left: 40px;" onclick="showSecondRedirectInput(event)"><?php echo __('Add Another Redirect', 'powerpress'); ?></a href="#">
			</div>
			<?php } ?>
		</div>
	
		
		<div id="powerpress_redirect2_table" style="position: relative; <?php if( empty($General['redirect2']) && empty($General['redirect3']) ) echo 'display:none;'; ?> padding-bottom: 10px;">
			<table class="form-table">
			<tr valign="top">
			<th scope="row">
			<?php echo __('Redirect URL 2', 'powerpress'); ?> 
			</th>
			<td>
			<input type="text" class="pp-settings-text-input" name="<?php if( stripos($General['redirect2'], $StatsIntegrationURL) !== false ) echo 'NULL[redirect2]'; else echo 'General[redirect2]'; ?>" value="<?php echo esc_attr($General['redirect2']); ?>" onblur="return CheckRedirect(this);" maxlength="255" <?php if( stripos($General['redirect2'], $StatsIntegrationURL) !== false ) { echo ' readOnly="readOnly"';  $StatsIntegrationURL = false; } ?> />
			</td>
			</tr>
			</table>
			<?php if( $General['redirect3'] == '' ) { ?>
			<div style="position: absolute;bottom: -2px;left: -40px;" id="powerpress_redirect3_showlink">
				<a href="#" style="margin-left: 40px;" onclick="showThirdRedirectInput(event)"><?php echo __('Add Another Redirect', 'powerpress'); ?></a>
			</div>
			<?php } ?>
		</div>

		<div id="powerpress_redirect3_table" style="<?php if( empty($General['redirect3']) ) echo 'display:none;'; ?>">
			<table class="form-table">
			<tr valign="top">
			<th scope="row">
			<?php echo __('Redirect URL 3', 'powerpress'); ?> 
			</th>
			<td>
			<input type="text" class="pp-settings-text-input" name="<?php if( stripos($General['redirect3'], $StatsIntegrationURL) !== false ) echo 'NULL[redirect3]'; else echo 'General[redirect3]'; ?>" value="<?php echo esc_attr($General['redirect3']); ?>" onblur="return CheckRedirect(this);" maxlength="255" <?php if( stripos($General['redirect3'], $StatsIntegrationURL) !== false ) echo ' readOnly="readOnly"'; ?> />
			</td>
			</tr>
			</table>
		</div>
	<style type="text/css">
	#TB_window {
		border: solid 1px #3D517E;
	}
	</style>
	</div>
</div><!-- end blubrry_stats_settings -->
<?php
}

	
function powerpressadmin_appearance($General=false, $Feed = false)
{
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
		
		
	/*
	$Players = array('podcast'=>__('Default Podcast (podcast)', 'powerpress') );
	if( isset($General['custom_feeds']) )
	{
		foreach( $General['custom_feeds'] as $podcast_slug => $podcast_title )
		{
			if( $podcast_slug == 'podcast' )
				continue;
			$Players[$podcast_slug] = sprintf('%s (%s)', $podcast_title, $podcast_slug);
		}
	}
	*/
    require_once( dirname(__FILE__).'/views/settings_tab_appearance.php' );
    powerpressadmin_website_settings($General, $Feed);
    powerpressadmin_blog_settings($General, $Feed);
    powerpress_subscribe_settings($General, $Feed);
    powerpress_shortcode_settings($General, $Feed);
    powerpressadmin_new_window_settings($General, $Feed);
?>

<?php  
} // End powerpress_admin_appearance()


// Admin page, footer
function powerpress_settings_tab_footer()
{ ?>
    <div class="pp-settings-footer">
        <?php powerpress_settings_save_button(); ?>
    </div>
    <?php
}
function powerpressadmin_welcome($GeneralSettings, $FeedSettings)
{
    if (isset($_GET['feed_slug'])) {
        $feed_slug = $_GET['feed_slug'];
    } else {
        $feed_slug = 'podcast';
    }
    if (isset($FeedSettings['itunes_image']) && !empty($FeedSettings['itunes_image'])) {
        $image = $FeedSettings['itunes_image'];
    } else {
        $image = powerpress_get_root_url() . 'images/pts_cover.jpg';
    }
    if (isset($FeedSettings['itunes_summary'])) {
        $description = $FeedSettings['itunes_summary'];
    } elseif (isset($FeedSettings['itunes_subtitle'])) {
        $description = $FeedSettings['itunes_subtitle'];
    } elseif (isset($FeedSettings['description'])) {
        $description = $FeedSettings['description'];
    } else {
        $description = '';
    }
    $numEp = powerpress_admin_episodes_per_feed($feed_slug);
?>
    <script>
        function goToArtworkSettings() {
            jQuery("#feeds-tab").click();
            jQuery("#feeds-artwork-tab").click();
            return false;
        }

        function goToDestinationSettings() {
            jQuery("#destinations-tab").click();
            jQuery("#destinations-apple-tab").click();
            return false;
        }
    </script>
<div>
    <div class="pp-settings-program-summary">
        <div class="prog-sum-head">
            <h2 class="pp-heading" id="welcome-title"><?php echo isset($FeedSettings['title']) ? $FeedSettings['title'] : ''; ?></h2>
            <div class="pp-settings-recent-post">
                <img id="welcome-preview-image" src="<?php echo $image; ?>" alt="Feed Image" />
                <div class="pp-settings-welcome-text">
                    <p class="pp-settings-text-no-margin" style="margin-bottom: 2ch;"><?php echo __('By', 'powerpress'); ?> <?php echo isset($FeedSettings['itunes_talent_name']) ? $FeedSettings['itunes_talent_name'] : ''; ?></p>
                    <p class="pp-settings-text-no-margin"><?php echo $description; ?></p>
                </div>
            </div>
            <div class="pp-settings-num-episodes">
                <p class="pp-settings-text-no-margin"><?php echo __('Number of Episodes', 'powerpress'); ?></p>
                <h2 class="pp-heading" style="margin-top: 5px;"><?php echo $numEp; ?></h2>
            </div>
        </div>
        <div class="prog-sum-contents">
            <a id="welcome-tab-new-post" href="<?php echo admin_url('post-new.php') ?>">
                <div class="pp_button-container">
                    <?php echo __('CREATE NEW EPISODE', 'powerpress'); ?>
                </div>
            </a>
            <div class="pp-settings-podcast-status">
                <p class="pp-settings-text-no-margin" style="margin-bottom: 2ch;"><?php echo __('Podcast Status', 'powerpress'); ?></p>
                <?php if (!$GeneralSettings || (isset($GeneralSettings['pp_onboarding_incomplete']) && $GeneralSettings['pp_onboarding_incomplete'] == 1) && (isset($GeneralSettings['timestamp']) && $GeneralSettings['timestamp'] > 1576972800)) { ?>
                    <p class="pp-settings-status-text"><a class="program-status-link" href="<?php echo admin_url("admin.php?page=powerpressadmin_onboarding.php"); ?>"><img src="<?php echo powerpress_get_root_url(); ?>images/status_incomplete.svg" class="pp-settings-icon-small"  alt="<?php echo __('Not done', 'powerpress'); ?>" />Finish Show Prep</a></p>
                <?php } else { ?>
                    <p class="pp-settings-status-text"><img src="<?php echo powerpress_get_root_url(); ?>images/status_complete.svg" class="pp-settings-icon-small"  alt="<?php echo __('Done!', 'powerpress'); ?>" />Finished Show Prep</p>
                <?php }
                if (empty($FeedSettings['itunes_image']) && empty($FeedSettings['rss2_image'])) { ?>
                    <p id="pp-welcome-artwork-link" class="program-status-link" onclick="goToArtworkSettings();return false;"><img src="<?php echo powerpress_get_root_url(); ?>images/status_incomplete.svg" class="pp-settings-icon-small"  alt="<?php echo __('Not done', 'powerpress'); ?>" />Add Artwork to Show</p>
                <?php } else { ?>
                    <p class="pp-settings-status-text"><img src="<?php echo powerpress_get_root_url(); ?>images/status_complete.svg" class="pp-settings-icon-small"  alt="<?php echo __('Done!', 'powerpress'); ?>" />Added Artwork to Show</p>
                <?php }
                if (!isset($FeedSettings['itunes_url']) || empty($FeedSettings['itunes_url'])) { ?>
                    <p id="pp-welcome-applesubmit-link" class="program-status-link" onclick="goToDestinationSettings();return false;"><img src="<?php echo powerpress_get_root_url(); ?>images/status_incomplete.svg" class="pp-settings-icon-small"  alt="<?php echo __('Not done', 'powerpress'); ?>" />Submit to Apple Podcasts</p>
                <?php } else { ?>
                    <p class="pp-settings-status-text"><img src="<?php echo powerpress_get_root_url(); ?>images/status_complete.svg" class="pp-settings-icon-small"  alt="<?php echo __('Done!', 'powerpress'); ?>" />Submitted to Apple Podcasts</p>
                <?php } ?>
            </div>
        </div>
    </div>
	<div class="powerpress-welcome-news">
		<h2><?php echo __('<em>PODCAST INSIDER</em> NEWS &amp; UPDATES', 'powerpress'); ?></h2>
		<?php powerpressadmin_community_news(4, true); ?>
	</div>

	<div class="clear"></div>
</div>
<?php
} // End powerpressadmin_welcome()

function powerpressadmin_edit_funding($FeedSettings = false, $feed_slug='podcast', $cat_ID=false)
{
	if( !isset($FeedSettings['donate_link']) )
		$FeedSettings['donate_link'] = 0;
	if( !isset($FeedSettings['donate_url']) )
		$FeedSettings['donate_url'] = '';
	if( !isset($FeedSettings['donate_label']) )
		$FeedSettings['donate_label'] = '';

    if( !isset($FeedSettings['location']) )
        $FeedSettings['location'] = '';
    if( !isset($FeedSettings['frequency']) )
        $FeedSettings['frequency'] = '';
    ?>

    <h1 class="pp-heading"><?php echo __('Basic Show Information', 'powerpress'); ?></h1>
    <div class="pp-settings-section">
        <h2><?php echo __('Location', 'powerpress'); ?></h2>
        <label for="Feed[location]" class="pp-settings-label"><?php echo __('Optional', 'powerpress'); ?></label>
        <input class="pp-settings-text-input" type="text" name="Feed[location]" value="<?php echo esc_attr($FeedSettings['location']); ?>" maxlength="50" />
        <label for="Feed[location]" class="pp-settings-label-under"><?php echo __('e.g. Cleveland, Ohio', 'powerpress'); ?></label>
    </div>
    <div class="pp-settings-section">
        <h2><?php echo __('Episode Frequency', 'powerpress'); ?></h2>
        <label for="Feed[frequency]" class="pp-settings-label"><?php echo __('Optional', 'powerpress'); ?></label>
        <input class="pp-settings-text-input" type="text" name="Feed[frequency]" value="<?php echo esc_attr($FeedSettings['frequency']); ?>" maxlength="50" />
        <label for="Feed[frequency]" class="pp-settings-label-under"><?php echo __('e.g. Weekly', 'powerpress'); ?></label>
    </div>
<!--  Donate link and label -->
    <div class="pp-settings-section">
        <h2><?php echo __('Donate Link', 'powerpress'); ?> </h2>
        <label for="donate_link"></label>
        <input class="pp-settings-checkbox" style="margin-top: 2.5ch;" type="checkbox" id="donate_link" name="Feed[donate_link]" value="1" <?php if( $FeedSettings['donate_link'] == 1 ) echo 'checked '; ?>/>
        <div class="pp-settings-subsection">
	        <p class="pp-main"><?php echo __('Syndicate a donate link with your podcast. Create your own crowdfunding page with PayPal donate buttons, or link to a service such as Patreon.', 'powerpress'); ?></p>
        </div>
	    <label for="donate_url" class="pp-settings-label"><?php echo __('Donate URL', 'powerpress'); ?></label>
        <input class="pp-settings-text-input" type="text" id="donate_url" value="<?php echo esc_attr($FeedSettings['donate_url']); ?>" name="Feed[donate_url]" />
	    <label for="donate_label" class="pp-settings-label"><?php echo __('Donate Label', 'powerpress'); ?></label>
        <input class="pp-settings-text-input" type="text" id="donate_label" value="<?php echo esc_attr($FeedSettings['donate_label']); ?>" name="Feed[donate_label]" />
        <label for="donate_label" class="pp-settings-label-under"><?php echo __('optional', 'powerpress'); ?></label>
	    <p class="pp-settings-text" style="margin-top: 1em;"><a href="http://create.blubrry.com/resources/powerpress/advanced-tools-and-options/syndicating-a-donate-link-in-your-podcast/" target="_blank"><?php echo __('Learn more about syndicating donate links for podcasting', 'powerpress'); ?></a></p>
    </div>
<?php
}

function powerpressadmin_edit_tv($FeedSettings = false, $feed_slug='podcast', $cat_ID=false)
{
	if( !isset($FeedSettings['parental_rating']) )
		$FeedSettings['parental_rating'] = '';

?>
<h1 class="pp-heading"><?php echo __('Rating Settings', 'powerpress'); ?></h1>
<p class="pp-settings-text"><?php echo sprintf(__('A parental rating is used to display your content on %s applications available on Internet connected TV\'s. The TV Parental Rating applies to both audio and video media.', 'powerpress'), '<strong><a href="http://www.blubrry.com/roku_blubrry/" target="_blank">Blubrry</a></strong>'); ?></p>
<div class="pp-settings-section" style="border-left: none;">
    <h2><?php echo __('Parental Rating', 'powerpress'); ?>  </h2>
	<?php
	$Ratings = array(''=>__('No rating specified', 'powerpress'),
			'TV-Y'=>__('Children of all ages', 'powerpress'),
			'TV-Y7'=>__('Children 7 years and older', 'powerpress'),
			'TV-Y7-FV'=>__('Children 7 years and older [fantasy violence]', 'powerpress'),
			'TV-G'=>__('General audience', 'powerpress'),
			'TV-PG'=>__('Parental guidance suggested', 'powerpress'),
			'TV-14'=>__('May be unsuitable for children under 14 years of age', 'powerpress'),
			'TV-MA'=>__('Mature audience - may be unsuitable for children under 17', 'powerpress')
		);
	$RatingsTips = array(''=>'',
				'TV-Y'=>__('Whether animated or live-action, the themes and elements in this program are specifically designed for a very young audience, including children from ages 2-6. These programs are not expected to frighten younger children.  Examples of programs issued this rating include Sesame Street, Barney & Friends, Dora the Explorer, Go, Diego, Go! and The Backyardigans.', 'powerpress'),
				'TV-Y7'=>__('These shows may or may not be appropriate for some children under the age of 7. This rating may include crude, suggestive humor, mild fantasy violence, or content considered too scary or controversial to be shown to children under seven. Examples include Foster\'s Home for Imaginary Friends, Johnny Test, and SpongeBob SquarePants.', 'powerpress'),
				'TV-Y7-FV'=>__('When a show has noticeably more fantasy violence, it is assigned the TV-Y7-FV rating. Action-adventure shows such Pokemon series and the Power Rangers series are assigned a TV-Y7-FV rating.', 'powerpress'),
				'TV-G'=>__('Although this rating does not signify a program designed specifically for children, most parents may let younger children watch this program unattended. It contains little or no violence, no strong language and little or no sexual dialogue or situation. Networks that air informational, how-to content, or generally inoffensive content.', 'powerpress'),
				'TV-PG'=>__('This rating signifies that the program may be unsuitable for younger children without the guidance of a parent. Many parents may want to watch it with their younger children. Various game shows and most reality shows are rated TV-PG for their suggestive dialog, suggestive humor, and/or coarse language. Some prime-time sitcoms such as Everybody Loves Raymond, Fresh Prince of Bel-Air, The Simpsons, Futurama, and Seinfeld  usually air with a TV-PG rating.', 'powerpress'),
				'TV-14'=>__('Parents are strongly urged to exercise greater care in monitoring this program and are cautioned against letting children of any age watch unattended. This rating may be accompanied by any of the following sub-ratings:', 'powerpress'),
				'TV-MA'=>__('A TV-MA rating means the program may be unsuitable for those below 17. The program may contain extreme graphic violence, strong profanity, overtly sexual dialogue, very coarse language, nudity and/or strong sexual content. The Sopranos is a popular example.', 'powerpress')
		);
			
	
	foreach( $Ratings as $rating => $title )
	{
		$tip = $RatingsTips[ $rating ];
		if (!$rating) {
		    $style = "style=\"margin-bottom:\"";
        }
?>
    <div>
        <input class="pp-settings-radio" type="radio" name="Feed[parental_rating]" value="<?php echo $rating; ?>" <?php if( $FeedSettings['parental_rating'] == $rating) echo 'checked'; ?> />
        <div class="pp-settings-subsection">
            <p class="pp-main">
                <?php if( $rating ) { ?>
                    <strong><?php echo $rating; ?></strong>
                <?php } else { ?>
                    <strong><?php echo htmlspecialchars($title); ?></strong>
                <?php } ?>
            </p>
            <?php if( $rating ) { ?>
                <p class="pp-sub">
                    <?php echo htmlspecialchars($title); ?>
                </p>
            <?php } else { ?>
                <br />
            <?php  } ?>
        </div>
    </div>
	<?php
	}
?>
</div>

<?php
}

function powerpressadmin_edit_artwork($FeedSettings, $General)
{
	$SupportUploads = powerpressadmin_support_uploads();
?>

<h1 class="pp-heading"><?php echo __('Podcast Artwork', 'powerpress'); ?></h1>


<div class="pp-settings-section">
    <h2><?php echo __('Apple Podcast Artwork', 'powerpress'); ?></h2>
    <label for="Feed[itunes_image]" class="pp-settings-label"><?php echo __('Artwork URL', 'powerpress'); ?></label>
    <input class="pp-settings-text-input" type="text" id="itunes_image" name="Feed[itunes_image]" value="<?php echo esc_attr( !empty($FeedSettings['itunes_image'])? $FeedSettings['itunes_image']:''); ?>" maxlength="255" />
    <label for="Feed[itunes_image]" class="pp-settings-label-under"><?php echo __('Apple Podcast image must be at least 1400 x 1400 pixels in .jpg or .png format. Apple Podcast image must not exceed 3000 x 3000 pixels and must use RGB color space. The filesize should not exceed 0.5MB.', 'powerpress'); ?></label>

    <?php if( $SupportUploads ) { ?>
    <input name="itunes_image_checkbox" id="itunes_image_checkbox" type="hidden" value="0" />
    <div id="itunes_image_upload">
        <div>
            <div class="pp-settings-button">
                <label class="pp-settings-button-label" for="itunes_image_file">
                    <img class="pp-settings-icon" src="<?php echo powerpress_get_root_url(); ?>images/cloud_up.svg" alt="">
                    <?php echo __('Upload Image', 'powerpress'); ?>
                </label>
                <input type="file" id="itunes_image_file" name="itunes_image_file" accept="image/*" class="pp_file_upload" style="display: none" />
            </div>
        </div>
        <input class="pp-settings-checkbox" style="margin-top: 0;" name="itunes_image_checkbox_as_rss" type="checkbox" value="1" onchange="powerpress_show_field('rss_image_upload_container', !this.checked)" />
        <label class="pp-checkbox-label" for="itunes_image_checkbox_as_rss"><?php echo __('Also use as RSS image', 'powerpress'); ?></label>
    </div>
        <!--<a href="#" onclick="javascript: window.open( document.getElementById('itunes_image').value ); return false;"><?php echo __('preview', 'powerpress'); ?></a>-->
    <?php } ?>
</div>



<div class="pp-settings-section">
    <h2><?php echo __('Apple Episode Image', 'powerpress'); ?></h2>
    <input class="pp-settings-checkbox" type="checkbox" name="Feed[episode_itunes_image]" value="1" <?php if( !empty($FeedSettings['episode_itunes_image']) ) echo 'checked '; ?>/>
    <div class="pp-settings-subsection">
        <p class="pp-main"><?php echo __('Use the program Apple podcast image above as your Apple episode image.', 'powerpress'); ?></p>
        <p class="pp-sub"><?php echo __('NOTE: You must still save artwork into your media files to guarantee your artwork is displayed during playback.', 'powerpress'); ?></p>
    </div>
</div>





<div class="pp-settings-section">
    <h2><?php echo __('RSS2 Image', 'powerpress'); ?> </h2>
    <label for="Feed[rss2_image]" class="pp-settings-label"><?php echo __('Recommendation: Use Apple Podacst image', 'powerpress'); ?></label>
    <input class="pp-settings-text-input" type="text" id="rss2_image" name="Feed[rss2_image]" value="<?php echo esc_attr( !empty($FeedSettings['rss2_image'])? $FeedSettings['rss2_image']:''); ?>" maxlength="255" />
    <label for="Feed[rss2_image]" class="pp-settings-label-under"><?php echo __('Place the URL to the RSS image above.', 'powerpress'); ?> <?php echo __('Example', 'powerpress'); ?> http://mysite.com/images/rss.jpg</label>

    <!--
    <a href="#" onclick="javascript: window.open( document.getElementById('rss2_image').value ); return false;"><?php echo __('preview', 'powerpress'); ?></a>
    <p><?php echo __('RSS image should be at least 88 pixels wide and at least 31 pixels high in either .gif, .jpg and .png format.', 'powerpress'); ?></p>
    <p><strong><?php echo __('A square image that is 300 x 300 pixel or larger in .jpg format is recommended.', 'powerpress'); ?></strong></p>
    -->

    <?php if( $SupportUploads ) { ?>
        <input name="rss2_image_checkbox" id="rss2_image_checkbox" type="hidden" value="0" />
        <div id="rss_image_upload">
            <div>
                <div class="pp-settings-button">
                    <label class="pp-settings-button-label" for="rss2_image_file">
                        <img class="pp-settings-icon" src="<?php echo powerpress_get_root_url(); ?>images/cloud_up.svg" alt="">
                        <?php echo __('Upload Image', 'powerpress'); ?>
                    </label>
                    <input type="file" id="rss2_image_file" name="rss2_image_file"  style="display: none" />
                </div>
            </div>
        </div>
    <?php } ?>
</div>
    <script>
        document.getElementById('itunes_image_file').onchange = function (event) {
            document.getElementById('itunes_image').value = this.value.replace("C:\\fakepath\\", "");
            let checkbox_id = "itunes_image_checkbox";
            console.log(checkbox_id);
            if (event.currentTarget.value.length > 0) {
                document.getElementById(checkbox_id).value = 1;
            }
            console.log(document.getElementById(checkbox_id).value);
        };
        document.getElementById('rss2_image_file').onchange = function (event) {
            document.getElementById('rss2_image').value = this.value.replace("C:\\fakepath\\", "");
            let checkbox_id = "rss2_image_checkbox";
            console.log(checkbox_id);
            if (event.currentTarget.value.length > 0) {
                document.getElementById(checkbox_id).value = 1;
            }
            console.log(document.getElementById(checkbox_id).value);
        };
        document.getElementById('itunes_image').onchange = function(event) {
            let checkbox_id = "itunes_image_checkbox";
            console.log(checkbox_id);
            if (event.currentTarget.value.length > 0) {
                document.getElementById(checkbox_id).value = 1;
            } else {
                document.getElementById(checkbox_id).value = 0;
            }
            console.log(document.getElementById(checkbox_id).value);
        };
        document.getElementById('rss2_image').onchange = function(event) {
            let checkbox_id = "rss2_image_checkbox";
            console.log(checkbox_id);
            if (event.currentTarget.value.length > 0) {
                document.getElementById(checkbox_id).value = 1;
            } else {
                document.getElementById(checkbox_id).value = 0;
            }
            console.log(document.getElementById(checkbox_id).value);
        };
    </script>
<?php

}


function powerpressadmin_edit_destinations($FeedSettings, $General, $FeedAttribs)
{
	require_once( dirname(__FILE__).'/views/settings_tab_destinations.php' );
}

