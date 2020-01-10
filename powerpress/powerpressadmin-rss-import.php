<?php

// Load Importer API
require_once( ABSPATH . 'wp-admin/includes/import.php');

if ( !class_exists( 'WP_Importer' ) ) {
	if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-importer.php' ) )
		require_once( ABSPATH . 'wp-admin/includes/class-wp-importer.php' );
}

/**
 * PowerPress RSS Podcast Importer
 *
 * originally based on the rss importer, significantly modified specifically for podcasting
 */

/**
 * PowerPress RSS Podcast Importer
 *
 * Will process a Podcast RSS feed for importing posts into WordPress. 
 *
 */
if ( class_exists( 'WP_Importer' ) ) {
class PowerPress_RSS_Podcast_Import extends WP_Importer {

	var $m_content = '';
	var $m_item_pos = 0;
	var $m_item_inserted_count = 0;
	var $m_item_skipped_count = 0;
	var $m_item_migrate_count = 0;
	var $m_step = 0;
	var $m_errors = array();
	private $isHostedOnBlubrry = false; //used to show Blubrry signin during onboarding process


    function migrateCount() {
		return $this->m_item_migrate_count;
	}
	
	function importCount() {
		return $this->m_item_inserted_count;
	}
	
	function skippedCount() {
		return $this->m_item_skipped_count;
	}
	
	function errorsExist() {
		return ( count($this->m_errors) > 0 );
	}
	
	function getErrors() {
		return $this->m_errors;
	}
	
	function addError($msg) {
		$this->m_errors[] = $msg;
	}


	function header() {
        wp_enqueue_style('powerpress_onboarding_styles',plugin_dir_url( __FILE__ ) . 'css/onboarding.css' );
        echo '<div class="wrap" style="min-height: 100vh">';
		echo '<div class="pp_container" style="max-width: 100rem;">';
        echo '<h2 class="pp_align-center">'.__('PowerPress', 'powerpress').'</h2>';
    }


	function greet() {
		$General = powerpress_get_settings('powerpress_general');
		if (isset($_GET['from']) && ($_GET['from'] == 'gs' || $_GET['from'] == 'onboarding')) {
		    $from_onboarding = true;
        } else {
		    $from_onboarding = false;
        }
		if( !empty($_GET['import']) )
		{
			switch($_GET['import'] )
			{
				case 'powerpress-soundcloud-rss-podcast': echo '<h2 class="pp_align-center">'.__('Import Podcast from SoundCloud', 'powerpress').'</h2>'; break;
				case 'powerpress-libsyn-rss-podcast': echo '<h2 class="pp_align-center">'.__('Import Podcast from LibSyn', 'powerpress').'</h2>'; break;
				case 'powerpress-podbean-rss-podcast': echo '<h2 class="pp_align-center">'.__('Import Podcast from PodBean', 'powerpress').'</h2>'; break;
				case 'powerpress-squarespace-rss-podcast': echo '<h2 class="pp_align-center">'.__('Import Podcast from Squarespace', 'powerpress').'</h2>'; break;
				case 'powerpress-anchor-rss-podcast':  echo '<h2 class="pp_align-center">'.__('Import Podcast from Anchor.fm', 'powerpress').'</h2>'; break;
				case 'powerpress-rss-podcast':
				default: echo '<h2 class="pp_align-center">'.__('Import Podcast RSS Feed', 'powerpress').'</h2>'; break;
			}
		}
		else
		{
			echo '<h2 class="pp_align-center">'.__('Podcast RSS Import', 'powerpress').'</h2>';
		}
?>
<p class="pp_align-center"><?php echo __('The following tool will import your podcast episodes to this website.', 'powerpress'); ?></p>
<section id="one" class="pp_wrapper">
<div class="pp_inner">
<form enctype="multipart/form-data" action="" method="post" name="import-podcast-feed">
<?php wp_nonce_field('import-powerpress-rss') ?>
<input type="hidden" name="step" value="1" />
<input type="hidden" name="import" value="<?php echo( !empty($_REQUEST['import']) ? htmlspecialchars($_REQUEST['import']) : ''); ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo wp_max_upload_size(); ?>" />
<div class="pp_flex-grid" style="padding-top:35px;">
<div class="pp_col" style="flex-grow:6;">
<div class="pp_form-group">
    <label for="podcast_feed_url" ><?php _e('Podcast Feed URL:', 'powerpress'); ?></label>
    <?php
        $placeholder = 'https://example.com/feed.xml';
        switch($_GET['import']) {
            case 'powerpress-soundcloud-rss-podcast': $placeholder = 'http://feeds.soundcloud.com/users/soundcloud:users:00000000/sounds.rss'; break;
            case 'powerpress-libsyn-rss-podcast': $placeholder = 'http://yourshow.libsyn.com/rss'; break;
            case 'powerpress-podbean-rss-podcast': $placeholder = 'http://yourshow.podbean.com/feed/'; break;
            case 'powerpress-squarespace-rss-podcast': $placeholder = 'http://example.com/podcast/?format=rss'; break;
            case 'powerpress-anchor-rss-podcast': $placeholder = 'https://anchor.fm/s/xxxxxx/podcast/rss'; break;
        }
    ?>
    <input type="text" name="podcast_feed_url" id="podcast_feed_url" class="pp_outlined" placeholder="<?php echo esc_attr($placeholder); ?>" />
</div>
</div>
<div <?php if ($from_onboarding) { echo "style='display: none;'"; } ?>>
<h3><label for="podcast_feed_file"><?php _e('Or choose from your local disk:', 'powerpress'); ?></label></h3>
<input id="podcast_feed_file" name="podcast_feed_file" type="file" />
</div>

</div>
<div class="pp_flex-grid">
<!--
<p><?php echo sprintf(__('Importing your feed does not migrate your media files. Please use the %s tool to migrate your media once your feed is imported.', 'powerpress'), '<strong><a href="'.admin_url('admin.php?page=powerpress/powerpressadmin_migrate.php') .'">'. __('Migrate Media', 'powerpress') .'</a></strong>'); ?></p>
-->
<style>
.ppi-option {
	margin: 15px 0;
	font-size: 14px;
}
.ppi-option > p,
.ppi-option > label {
	font-size: 16px;
	
}

</style>
    <?php if ($from_onboarding) { ?>
<div class="import-to" id="import-to-default" style="display: none;">
				<div style="display: none;">
					<input type="checkbox" name="import_overwrite_program_info" value="1" checked>
				</div>
				<div style="display: none;">
					<input type="checkbox" name="import_itunes_image" value="1" checked>
				</div>
			</div>
			<?php if ($General['blubrry_hosting']) { ?>
			<div style="display: none;" class="ppi-option">
		        <input type="checkbox" name="migrate_to_blubrry" value="1" checked>
	        </div>
	        <?php }
     } else { ?>
<div class="pp_col">
	
	<div class="ppi-option">
		<h3><?php echo __('Import Podcast To', 'powerpress'); ?></h3>
	</div>
	<div>
		<div class="ppi-option">
			<label><input type="radio" name="import_to" id="import_to_default" value="default" checked /> <?php echo __('Default podcast feed', 'powerpress'); ?></label><br />
			<div class="import-to" id="import-to-default" style="display: none;">
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_overwrite_program_info" value="1" <?php echo isset($_GET['from']) ? 'checked': '' ?> > <?php echo __('Import program information', 'powerpress'); ?></label>
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_itunes_image" value="1" <?php echo isset($_GET['from']) ? 'checked': '' ?>> <?php echo __('Import iTunes artwork', 'powerpress'); ?></label>
				</div>
			</div>
		</div>
		<div class="ppi-option">
			<label><input type="radio" name="import_to" id="import_to_category" value="category" /> <?php echo __('Podcast Category feed', 'powerpress'); ?></label>
			<div class="import-to" id="import-to-category" style="display: none;">
				<div style="margin: 10px 0 10px 24px;">
					<label for="category"><?php echo __('Category', 'powerpress'); ?></label> &nbsp; <?php
					wp_dropdown_categories(array('show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '', 'hide_empty' => 0, 'id'=>'category', 'name' => 'category', 'orderby' => 'name', 'selected' => '', 'hierarchical' => true));
					?>
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_overwrite_program_info_category" value="1"> <?php echo __('Import program information', 'powerpress'); ?></label>
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_itunes_image_category" value="1"> <?php echo __('Import iTunes artwork', 'powerpress'); ?></label>
				</div>
			</div>
		</div>
<?php
	
	if( !empty($General['channels']) )
	{
		// List rall of teh podcast channel feeds
		$Feeds = array();
		if( isset($General['custom_feeds']) )
			$Feeds = $General['custom_feeds'];
		if( isset($General['custom_feeds']['podcast']) )
			unset($General['custom_feeds']['podcast']);
		if( !empty($Feeds) )
		{
?>
		<div class="ppi-option">
			<label><input type="radio" name="import_to" id="import_to_channel" value="channel" /> <?php echo __('Podcast Channel feed', 'powerpress'); ?></label><br />
			<div class="import-to" id="import-to-channel" style="display: none;">
				<div style="margin: 10px 0 10px 24px;">
<select id="feed_slug" name="feed_slug" class="large-input">
<option value=""><?php echo __('Select Channel feed', 'powerpress'); ?></option>
<?php

	asort($Feeds, SORT_STRING); // Sort feeds 
	foreach( $Feeds as $feed_slug => $feed_title ) {
	
	echo "\t<option value=\"$feed_slug\">$feed_title ($feed_slug)</option>\n";
}
?>
</select>
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_overwrite_program_info_channel" value="1"> <?php echo __('Import program information', 'powerpress'); ?></label>
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_itunes_image_channel" value="1"> <?php echo __('Import iTunes artwork', 'powerpress'); ?></label>
				</div>
			</div>
		</div>
<?php
		}
	} // end podcast channel
	
	if( !empty($General['posttype_podcasting']) )
	{
?>
		<div class="ppi-option">
			<label><input type="radio" name="import_to" id="import_to_post_type" value="post_type" /> <?php echo __('Podcast Post Type feed', 'powerpress'); ?></label>
			<div class="import-to" id="import-to-post_type" style="display: none;">
				<div style="margin: 10px 0 10px 24px;">
					<label for="post_type"><?php echo __('Post type', 'powerpress'); ?></label> &nbsp; 
					<input type="text" name="post_type" id="post_type" class="medium-text" value="" />
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label for="post_type_feed_slug"><?php echo __('Feed slug', 'powerpress'); ?></label> &nbsp; 
					<input type="text" name="post_type_feed_slug" id="post_type_feed_slug" class="medium-text" value="" />
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_overwrite_program_info_post_type" value="1"> <?php echo __('Import program information', 'powerpress'); ?></label>
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_itunes_image_post_type" value="1"> <?php echo __('Import iTunes artwork', 'powerpress'); ?></label>
				</div>
			</div>
		</div>
<?php
	} // end post type
	
	if( !empty($General['taxonomy_podcasting']) )
	{
		$PowerPressTaxonomies = get_option('powerpress_taxonomy_podcasting');
		if( empty($PowerPressTaxonomies) )
			$PowerPressTaxonomies = array();
		
?>
		<div class="ppi-option">
			<label><input type="radio" name="import_to" id="import_to_taxonomy" value="taxonomy" /> <?php echo __('Podcast Taxonomy feed', 'powerpress'); ?></label>
			<div class="import-to" id="import-to-taxonomy" style="display: none;">
				<div style="margin: 10px 0 10px 24px;">
<?php

if( !empty($PowerPressTaxonomies) ) { // If taxonomy podcasting feeds exist..

	global $wpdb;
	$tt_ids = '';
	
	$SelectOptions = array();
	foreach( $PowerPressTaxonomies as $tt_id => $null ) {
		if( !empty($tt_ids) )
			$tt_ids .= ',';
		$tt_ids .= $tt_id;
		
		$term = get_term_by('term_taxonomy_id', $tt_id);
		if( is_wp_error($term) )
			continue;
		$SelectOptions[ $tt_id ] = sprintf('%s (%s)', $term->name, $term->slug);
	}
	
?>
<select id="podcast_ttid" name="podcast_ttid" style="min-width: 240px;" class="postform">
<option value=""><?php echo __('Select Taxonomy Podcast', ''); ?></option>
<?php

	foreach( $SelectOptions as $tt_id => $label )
	{
		echo "\t<option value=\"$tt_id\">". htmlspecialchars($label). "</option>\n";
	}

?>
</select>
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_overwrite_program_info_taxonomy" value="1"> <?php echo __('Import program information', 'powerpress'); ?></label>
				</div>
				<div style="margin: 10px 0 10px 24px;">
					<label><input type="checkbox" name="import_itunes_image_taxonomy" value="1"> <?php echo __('Import iTunes artwork', 'powerpress'); ?></label>
				</div>
<?php } else { // else no taxonomy feeds have been created yet ?>
				<div style="margin: 10px 0 10px 24px;">
					<label><?php echo __('Please create a taxonomy podcast to continue.', 'powerpress'); ?></label>
				</div>
<?php } ?>
			</div>
<?php
	} // End if taxonomy podcasting enabled
?>
</div>
</div>
</div>
</div>
<div class="pp_col">
	<h3><?php echo __('Blubrry Podcast Media Hosting', 'powerpress'); ?></h3>
<?php
		if( empty($General['blubrry_hosting']) || $General['blubrry_hosting'] === 'false' ) {
?>
	<div class="ppi-option">
		<label><input type="checkbox" name="NULL" value="1" disabled> <?php echo __('Migrate media to your Blubrry hosting account', 'powerpress'); ?></label>
	</div>
	<p><a title="<?php echo esc_attr(__('Blubrry Podcast Hosting', 'powerpress')); ?>" href="<?php echo admin_url('admin.php'); ?>?action=powerpress-jquery-hosting&amp;KeepThis=true&amp;TB_iframe=true&amp;modal=false&amp;width=900&amp;height=600" target="_blank" class="thickbox"><?php echo __('Don\'t have a blubrry podcast hosting account?', 'powerpress'); ?></a></p>
<?php
		} else { ?>
	<div class="ppi-option">
		<label><input type="checkbox" name="migrate_to_blubrry" value="1" checked> <?php echo __('Migrate media to your Blubrry hosting account', 'powerpress'); ?></label>
	</div>
<?php
		}
?>
<link rel="stylesheet" href="<?php echo powerpress_get_root_url(); ?>css/admin.css" type="text/css" media="screen" />
<script language="javascript"><!--

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

//-->
</script>
<h3><a href="#" class="pp-expand-section"><?php echo __('Advanced Options', 'powerpress'); ?></a></h3>
<div style="margin-left: 24px; display: none;">
	<div class="ppi-option">
		<label><input type="checkbox" name="NULL" value="1" checked disabled> <?php echo __('Match episode by GUID (required)', 'powerpress'); ?></label>
	</div>
	<div class="ppi-option">
		<label><input type="checkbox" name="match_filename" value="1" checked> <?php echo __('Match episode by filename (recommended)', 'powerpress'); ?></label>
	</div>
	<div class="ppi-option">
		<label><input type="checkbox" name="match_title" value="1"> <?php echo __('Match episode by post title', 'powerpress'); ?></label>
	</div>
	<div class="ppi-option">
		<label><input type="checkbox" name="match_date" value="1"> <?php echo __('Match episode by exact post date and time', 'powerpress'); ?></label>
	</div>
	<div class="ppi-option">
		<label><input type="checkbox" name="import_blog_posts" value="1" > <?php echo __('Include blog posts', 'powerpress'); ?></label>
	</div>
	<div class="ppi-option">
	<input type="hidden" name="remove_query_string" value="0" />
		<label><input type="checkbox" name="remove_query_string" value="1" <?php if( !empty($_REQUEST['import']) && $_REQUEST['import'] == 'powerpress-libsyn-rss-podcast' ) {
			echo 'checked disabled'; } ?> > <?php echo __('Remove query strings from media URLs', 'powerpress'); ?></label>
	</div>
	<div class="ppi-option">
		<label for="import_post_status"><?php echo __('Post Status', 'powerpress'); ?></label> &nbsp;
		<select id="import_post_status" name="import_post_status" class="medium-text">
<?php
	$post_statuses = get_post_statuses();
	foreach( $post_statuses as $post_status_slug => $post_status_label ) {
	
	echo "\t<option value=\"$post_status_slug\"". ($post_status_slug=='publish'?' selected':'') .">".  htmlspecialchars("$post_status_label ($post_status_slug)") . "</option>\n";
}
?>
</select>
	</div>
	<div class="ppi-option">
		<label for="import_item_limit"><?php echo __('Item limit', 'powerpress'); ?></label> &nbsp;
		<input type="text" name="import_item_limit" id="import_item_limit" class="small-text" value="" /> (<?php echo __('leave blank for no limit', 'powerpress'); ?>)
	</div>
</div>

</div>
</div>
<?php } ?>
<div class="pp_col" style="padding: 20px 0px;">
                <hr class="pp_align-center">
                <div class="pp_button-container" style="float: right;">
                    <button name="submit" type="submit" class="pp_button" value="Import Podcast"><span><?php echo __('Import Podcast', 'powerpress'); ?></span></button>
                </div>
</form>
</div>
</div>
<script>
jQuery(document).ready( function() {
	
	var import_type = jQuery("input[name='import_to']:checked").val()
	jQuery('#import-to-'+import_type).show();
	jQuery("input[name='import_to']").change( function(e) {
		jQuery('.import-to').hide(400);
		jQuery('#import-to-'+jQuery(this).val() ).show(400);
	});
});
</script>
<?php
	return;
		echo '<div class="narrow">';
		
		echo '<h2>'.__('Import saved Feed', 'powerpress') .'</h2>';
		wp_import_upload_form("admin.php?import=rss-podcast&amp;step=1");
		echo '</div>';
	}

	function _normalize_tag( $matches ) {
		return '<' . strtolower( $matches[1] );
	}
	
	function import_program_info($channel, $overwrite=false, $download_itunes_image=false, $category_id = '', $feed_slug ='', $post_type = '', $ttid = '') {
		$Feed = get_option('powerpress_feed_podcast' );
		if( empty($Feed) )
			$Feed = get_option('powerpress_feed');
		
		if( !empty($category_id) ) {
			$Feed = get_option('powerpress_cat_feed_'.$category_id);
		} else if( !empty($post_type) ) {
			// TODO
		} else if( !empty($feed_slug) ) {
			$Feed = get_option('powerpress_feed_'.$feed_slug);
		}  else if( !empty($ttid) ) {
			$Feed = get_option('powerpress_taxonomy_'.$ttid);
		}
		
		if( empty($Feed) ) // Fix the situation where the empty value is not an array so we do not have notice messages
			$Feed = array();
		
		$NewSettings = array();
		
		$matches = array();
		$program_title = false;
		if( preg_match('|<title>(.*?)</title>|is', $channel, $matches) ) {
			$program_title = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['title']) )
				$NewSettings['title'] = $program_title;
		}
		
		// language
		$language = false;
		if( preg_match('|<language>(.*?)</language>|is', $channel, $matches) ) {
			$language = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['rss_language']) )
				$NewSettings['rss_language'] = $language;
		}
		
		// copyright
		$copyright = false;
		if( preg_match('|<copyright>(.*?)</copyright>|is', $channel, $matches) ) {
			$copyright = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['copyright']) )
				$NewSettings['copyright'] = $copyright;
		}
		
		// description
		$description = false;
		if( preg_match('|<description>(.*?)</description>|is', $channel, $matches) ) {
			$description = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['description']) )
				$NewSettings['description'] = $description;
		}
		
		// itunes:subtitle
		$itunes_subtitle = false;
		if( preg_match('|<itunes:subtitle>(.*?)</itunes:subtitle>|is', $channel, $matches) ) {
			$itunes_subtitle = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['itunes_subtitle']) )
				$NewSettings['itunes_subtitle'] = $itunes_subtitle;
		}
		
		// itunes:summary
		$itunes_summary = false;
		if( preg_match('|<itunes:summary>(.*?)</itunes:summary>|is', $channel, $matches) ) {
			$itunes_summary = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['itunes_summary']) )
				$NewSettings['itunes_summary'] = $itunes_summary;
		}
		
		// itunes:email
		$itunes_email = false;
		if( preg_match('|<itunes:email>(.*?)</itunes:email>|is', $channel, $matches) ) {
			$itunes_email = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['email']) )
				$NewSettings['email'] = $itunes_email;
		}
		
		// itunes:author
		$itunes_talent_name = false;
		if( preg_match('|<itunes:author>(.*?)</itunes:author>|is', $channel, $matches) ) {
			$itunes_talent_name = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['itunes_talent_name']) )
				$NewSettings['itunes_talent_name'] = $itunes_talent_name;
		}
		
		// itunes:explicit
		if( preg_match('|<itunes:explicit>(.*?)</itunes:explicit>|is', $channel, $explicit) )
		{
			$explicit_array = array('yes'=>1, 'clean'=>2); // No need to save 'no'
			$value = strtolower( trim( $explicit[1] ) );
			if( !empty($explicit_array[ $value ]) )
			{
				if( $overwrite || empty($Feed['itunes_explicit']) ) {
					$NewSettings['itunes_explicit'] = $explicit_array[ $value ];
				}
			}
		}

		// itunes:image
		$itunes_image = '';
		if( preg_match('/<itunes:image.*href="(.*?)".*(\/>|>.*<\/itunes:image>)/i', $channel, $image) )
		{
			$itunes_image = html_entity_decode( trim( $image[1] ) ); // Now we need to download and save the image locally...
			
			// download the image then save it locally...
			if( $download_itunes_image ) {
				
				echo '<hr />';
				echo '<p><strong>'. __('Program iTunes image', 'powerpress') .'</strong></p>';
				echo '<ul class="ul-disc">';
				
				$upload_path = false;
				$upload_url = false;
				$UploadArray = wp_upload_dir();
				if( false === $UploadArray['error'] )
				{
					$upload_path =  $UploadArray['basedir'].'/powerpress/';
					$upload_url =  $UploadArray['baseurl'].'/powerpress/';
					$filename = str_replace(" ", "_", basename($itunes_image) );
					
					if( file_exists($upload_path . $filename ) )
					{
						$filenameParts = pathinfo($filename);
						if( !empty($filenameParts['extension']) ) {
							do {
								$filename_no_ext = substr($filenameParts['basename'], 0, (strlen($filenameParts['extension'])+1) * -1 );
								$filename = sprintf('%s-%03d.%s', $filename_no_ext, rand(0, 999), $filenameParts['extension'] );
							} while( file_exists($upload_path . $filename ) );
						}
					}
					
					$options = array();
					$options['user-agent'] = 'Blubrry PowerPress/'.POWERPRESS_VERSION;
					if( !empty($_GET['import']) && $_GET['import'] == 'powerpress-squarespace-rss-podcast' )
						$options['user-agent'] = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36';
					$options['timeout'] = 10;
					
					$image_data = '';
					$response = wp_safe_remote_get($itunes_image, $options);
					if ( !is_wp_error( $response ) ) {
						$image_data = wp_remote_retrieve_body( $response );
					} else {
						$this->addError( __('Error downloading itunes image.', 'powerpress') );
					}
		
					if( !empty($image_data) ) {
						file_put_contents($upload_path.$filename, $image_data);
						$NewSettings['itunes_image'] = $upload_url . $filename;
						$NewSettings['rss2_image'] = $itunes_image;
						echo sprintf('iTunes image saved to %s.', ($upload_url . $filename) );
					} else {
						$this->addError( __('No iTunes image downloaded.', 'powerpress') );
						echo 'Error occurred downloading iTunes image.';
					}
				} else {
					echo 'Unable to save image to local folder.';
				}
				
				echo '</li>';
				echo '</ul>';
			} else if( $overwrite || empty($Feed['itunes_image']) ) {
				$NewSettings['itunes_image'] = $itunes_image;
				$NewSettings['rss2_image'] = $itunes_image;
			}
		}
		
			
		if( preg_match('|<itunes:author>(.*?)</itunes:author>|is', $channel, $matches) ) {
			$itunes_talent_name = $this->_sanatize_tag_value( $matches[1] );
			
			if( $overwrite || empty($Feed['itunes_talent_name']) )
				$NewSettings['itunes_talent_name'] = $itunes_talent_name;
		}
		
		// itunes:category (up to 3)
		$itunes_categories  = false;
		if( preg_match_all('|<itunes:category.*text="(.*?)"|is', $channel, $matches) ) {
			$pos = 1;
			$itunes_categories = $matches[1];
			$Categories = powerpress_apple_categories();
			$Categories = array_map('strtolower', $Categories);
			$cats_by_title = array_flip( $Categories );
			
			$FoundCategories = array();
			foreach( $itunes_categories as $index => $category )
			{
				$category = str_replace('&amp;', '&', $category);
				$category = strtolower($category);
				if( !empty($cats_by_title[ $category ] ) )
					$FoundCategories[] = $cats_by_title[ $category ];
			}
			
			// Now walk trouigh found categories and stack them correctly...
			// this logic rebuilds the categorires in the correct order no matter what method the service stacked them
			$FinalCats = array(1=>'', 2=>'', 3=>'');
			$last_category_index = 1;
			foreach( $FoundCategories as $index => $cat_id )
			{
				if( !empty($FinalCats[$last_category_index]) ) // Do we need to increment to the next category position
				{
					if( intval(substr($FinalCats[$last_category_index], 3)) > 0 )
					{
						$last_category_index++;
					}
					else if( intval(substr($FinalCats[$last_category_index],0, 2)) != intval(substr($cat_id,0, 2)) )
					{
						$last_category_index++;
					}
					// else we can overwrite this category with subcategory
				}
				
				if( $last_category_index > 3 )
					break; // We are at the max cats available...
				
				$FinalCats[ $last_category_index ] = $cat_id;
			}
			
			foreach( $FinalCats as $field_no => $cat_id ) {
				if( empty( $cat_id) )
					continue;
				$field = sprintf('apple_cat_%d', $field_no);
				
				if( $overwrite || empty($Feed[ $field  ]) ) {
					$NewSettings[ $field ] = $cat_id;
				}
			}
		}
		
		if( !empty($NewSettings) )
		{
			if( empty($category_id) && empty($feed_slug) && empty($post_type) && empty($ttid) ) {
				// Save here..
				if( get_option('powerpress_feed_podcast') ) { // If the settings were moved to the podcast channels feature...
					powerpress_save_settings($NewSettings, 'powerpress_feed_podcast' ); // save a copy here if that is the case.
				} else {
					powerpress_save_settings($NewSettings, 'powerpress_feed' );
				}
			} else if( !empty($category_id) ) {
				
				// First save the new settings into the specified options row...
				powerpress_save_settings($NewSettings, 'powerpress_cat_feed_'.$category_id ); // save a copy here if that is the case.
				
				// Then add the category id to the global array...
				$CurrentSettings = powerpress_get_settings('powerpress_general');
				if( !in_array($category_id, $CurrentSettings['custom_cat_feeds']) )
				{
					$NewSettings = array();
					if( !empty($CurrentSettings['custom_cat_feeds']) )
						$NewSettings['custom_cat_feeds'] = $CurrentSettings['custom_cat_feeds'];
					$NewSettings['custom_cat_feeds'][] = $category_id;
					if( empty($CurrentSettings['cat_casting']) ) {
						$NewSettings['cat_casting'] = 1; // Turn on category podcasting if not enabled
						$NewSettings['cat_casting_podcast_feeds'] = 1;
						$NewSettings['cat_casting_strict'] = 1;
					}
					
					powerpress_save_settings($NewSettings);
				}
			} else if ( !empty($post_type) ) {
				// TODO
			} else if ( !empty($feed_slug) ) {
				powerpress_save_settings($NewSettings, 'powerpress_feed_'.$feed_slug );
			} else if ( !empty($ttid) ) {
				powerpress_save_settings($NewSettings, 'powerpress_taxonomy_'. $ttid );
			}
			
			
			
			echo '<hr />';
			echo '<p><strong>'. __('Program information imported', 'powerpress') .'</strong></p>';
			echo '<ul class="ul-disc">';
			foreach( $NewSettings as $field => $value )
			{
				if( $field == 'rss2_image' )
					continue;
				
				echo '<li>';
				switch( $field )
				{
					case 'title': echo __('Feed Title (Show Title)', 'powerpress'); break;
					case 'rss_language': echo __(' Feed Language', 'powerpress'); break;
					case 'description': echo __('Feed Description', 'powerpress'); break;
					case 'copyright': echo __('Copyright', 'powerpress'); break;
					case 'itunes_talent_name': echo __('iTunes Author Name', 'powerpress'); break;
					case 'itunes_summary': echo __('iTunes Program Summary', 'powerpress'); break;
					case 'itunes_subtitle': echo __('iTunes Program Subtitle', 'powerpress'); break;
					case 'itunes_image': echo __('iTunes Image', 'powerpress'); break;
					case 'itunes_explicit': echo __('iTunes Explicit', 'powerpress'); break;
					case 'email': echo __('iTunes Email', 'powerpress'); break;
					case 'itunes_cat_1': echo __('iTunes Category', 'powerpress'); break;
					case 'itunes_cat_2': echo __('iTunes Category 2', 'powerpress'); break;
					case 'itunes_cat_3': echo __('iTunes Category 3', 'powerpress'); break;
					default: {
						if( defined('POWERPRESS_DEBUG') ) {
							if( is_string($value) )
								echo $field  . ': '.htmlspecialchars($value);
							else if( is_array($value) )
								echo $field .': {'. print_r($value, true) .'}';
						}
					}; break;
				}
				echo '</li>';
			}
			echo '</ul>';
		}
	}
	
	function import_item($post, $MatchFilter, $import_blog_posts=false, $category_strict='', $feed_slug='', $post_type = '', $taxonomy = '', $term = '', $remove_query_string = false, $post_status = 'publish') {	
		global $wpdb;
		$this->m_item_pos++;
		
		$matches = array();
		$post_title = false;
		if( !preg_match('|<title>(.*?)</title>|is', $post, $matches) ) {
			echo  sprintf(__('Empty episode title for item %d', 'powerpress'), $this->m_item_pos);
			$this->m_item_skipped_count++;
			return false;
		}
		$post_title = $this->_sanatize_tag_value($matches[1]);
			
		// Look for an enclosure, if not found skip it...
		$enclosure_data = false;
		if( !preg_match('#<enclosure(.*?)(/>|</enclosure>)#is', $post, $enclosure_data) ) {
			echo sprintf(__('No Media found for item %d', 'powerpress'), $this->m_item_pos);
			//echo '<pre>'.htmlspecialchars($post).'</pre>'; // Uncomment for debugging
			if( empty($import_blog_posts) ) {
				$this->m_item_skipped_count++;
				return false;
			}

			echo ' - ';
		}
		if( !empty($enclosure_data[1]) ) {
			$enclosure = $this->_parse_enclosure( '<enclosure '.$enclosure_data[1].' />', $post, $category_strict );
			if( empty($enclosure) ) {
				if( empty($import_blog_posts) ) {
					echo sprintf(__('No Media found for item %d', 'powerpress'), $this->m_item_pos);
					$this->m_item_skipped_count++;
					return false;
				}
			}
		}
		
		// GUID has to be last, as we will use the media URL as the guid as a last resort
		$guid = false;
		if( preg_match('|<guid.*?>(.*?)</guid>|is', $post, $matches) )
			$guid = $this->_sanatize_tag_value( $matches[1] );
		else if( !empty($enclosure['url']) )
			$guid = $enclosure['url'];
		
		$media_url = '';
		if( !empty($enclosure['url']) ) {
			if( !empty($remove_query_string) && !empty($enclosure['url']) && strstr($enclosure['url'], '?') ) {	
				$enclosure['url'] = strtok($enclosure['url'],'?');  //Tund3r: added for libsyn
			}
			$media_url = $enclosure['url'];
		}
		if(preg_match('/https?:\/\/(www\.)?media\.blubrry\.com\//m', $media_url)) {
            $this->isHostedOnBlubrry = true;
        }
		$post_date_gmt = false;
		if( preg_match('|<pubdate>(.*?)</pubdate>|is', $post, $matches) ) {
			$post_date_gmt = strtotime($matches[1]);
		} else {
			// if we don't already have something from pubDate
			if( preg_match('|<dc:date>(.*?)</dc:date>|is', $post, $matches) )
			{
				$post_date_gmt = preg_replace('|([-+])([0-9]+):([0-9]+)$|', '\1\2\3', $matches[1]);
				$post_date_gmt = str_replace('T', ' ', $post_date_gmt);
				$post_date_gmt = strtotime($post_date_gmt);
			}
		}

		$post_date_gmt = gmdate('Y-m-d H:i:s', $post_date_gmt);
		$post_date = get_date_from_gmt( $post_date_gmt );
		
		// Before we go any further, lets see if we have imported this one already...
		$exists = $this->_find_post(
			(empty($MatchFilter['match_guid'])?'':$guid),
			(empty($MatchFilter['match_title'])?'':$post_title),
			(empty($MatchFilter['match_date'])?'':$post_date),
			(empty($MatchFilter['match_filename'])?'':$media_url),
			$feed_slug
			);
		
		if( !empty($exists) )
		{
		    ?>
              <td><?php echo htmlspecialchars($post_title) ?></td>
              <td>Episode Already Imported</td>
              <td>&#x274c;</td>
		    <?php
			$this->m_item_skipped_count++;
			return false;
		}
		
		// Okay awesome, lets dig through the rest...
		$categories = array();
		if( preg_match_all('|<category>(.*?)</category>|is', $post, $matches) )
			$categories = $matches[1];

		if ( empty($categories) ) {
			if( preg_match_all('|<dc:subject>(.*?)</dc:subject>|is', $post, $matches) )
				$categories = $matches[1];
		}
		
		$cat_index = 0;
		foreach ($categories as $category) {
			$categories[$cat_index] = $this->_sanatize_tag_value( $category );
			$cat_index++;
		}
		
		$post_content = '';
		if( preg_match('|<content:encoded>(.*?)</content:encoded>|is', $post, $matches) )
			$post_content = $this->_sanatize_tag_value( $matches[1] );

		if ( empty($post_content) ) {
			// This is for feeds that put content in description
			if( preg_match('|<description>(.*?)</description>|is', $post, $matches) )
				$post_content = $this->_sanatize_tag_value( $matches[1] );
		}
		
		if ( empty($post_content) && !empty($enclosure['summary']) ) { // Last case situation lets used the itunes:summary if no description was available
			$post_content = $enclosure['summary'];
		}

		// Clean up content
		$post_content = preg_replace_callback('|<(/?[A-Z]+)|', array( &$this, '_normalize_tag' ), $post_content);
		$post_content = str_replace('<br>', '<br />', $post_content);
		$post_content = str_replace('<hr>', '<hr />', $post_content);

		$post_author = get_current_user_id();
		
		// Save this episode to the database...
		$post_to_save = compact('post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_status', 'guid', 'categories', 'enclosure');
		
		if( !empty($post_type) ) // If the post should go into a custom post type...
		{
			$post_to_save['post_type'] = $post_type;
		}
		$this->m_item_inserted_count++;
		
		$post_id = $this->_import_post_to_db($post_to_save, $feed_slug);
		if( empty($post_id) || is_wp_error($post_id) ) {
		    ?>
              <td><?php echo htmlspecialchars($post_title) ?></td>
              <td>Import Failed</td>
              <td>&#x274c;</td>
		    <?php
			return false;
		}
		$permalink = get_permalink($post_id);
		?>
          <td><?php echo "<a href='{$permalink} target='_blank'>" . htmlspecialchars($post_title) . "</a>" ?></td>
          <td>Episode Imported</td>
          <td>&#x2714;&#xFE0F;</td>
		<?php
		
		// Display a link to the blog post
		//echo ' <a href="'. get_permalink($post_id) .'" target="_blank"><i class="wp-menu-image dashicons-before dashicons-admin-links"></i></a>';

		// Category strict
		if( !empty($category_strict) )
		{
			wp_set_post_categories( $post_id, array($category_strict), true );
		}
		
		// Set specific taxonomy term to this post
		if( !empty($taxonomy) && !empty($term) )
		{
			wp_set_post_terms( $post_id, array($term), $taxonomy, true );
		}
		
		return ( $post_id > 0 );
	}
	
	function _sanatize_tag_value($value)
	{
		if( !is_string($value) )
			return '';
		
		$value = trim($value);
		if( preg_match('/^<!\[CDATA\[(.*)\]\]>$/is', $value, $matches) ) {
			$value = $matches[1];
		} else {
			$value = html_entity_decode($value);
		}
		
		return $value;
	}

	function import_episodes($MatchFilter, $import_blog_posts=false, $import_item_limit=0, $category='', $feed_slug='', $post_type = '', $ttid = '', $remove_query_string = false, $post_status='publish') {
		global $wpdb;
		@set_time_limit(60*15); // Give it 15 minutes
		$this->m_item_pos = 0;
		$taxonomy = '';
		$term = '';
		if( $ttid )
		{
			$TaxTermObj = get_term_by('term_taxonomy_id', $ttid);
			if( $TaxTermObj )
			{
				$term = $TaxTermObj->name;
				$taxonomy = $TaxTermObj->taxonomy;
				// Now get the post type if the taxonomy, which may not be "post"...
				$TaxonomyObj = get_taxonomy($taxonomy);
				// Set the post type to import into
				if( !empty($TaxonomyObj->object_type[0]) && $TaxonomyObj->object_type[0] != 'post' ) {
					$post_type = $TaxonomyObj->object_type[0];
				}
				// We should use the term's ID rather than it's name
				if( !empty($TaxonomyObj->hierarchical) ) {
					$term = intval($TaxTermObj->term_id);
				}
			}
			else
			{
				// Do not go any further, there is an error here!
				echo '<p><strong>';
				echo __('Error, unable to locate term taxonomy.', 'powerpress');
				echo '</strong></p>';
				return;
			}
		}
		
		$item_count = substr_count( $this->m_content, '<item>');
		$item_count += substr_count( $this->m_content, '<ITEM>');
		echo '<div class="pp_flex-grid">';
		echo '<div class="pp_col" style="order: 2;">';
		echo '<h3>Diagnostic information</h3>';
		echo '<ul>';
		if( !empty($ttid) )
			echo sprintf( '<li>Taxonomy Term ID: %s</li>', $ttid);
		if( !empty($taxonomy) )
			echo sprintf( '<li>Taxonomy: %s</li>', $taxonomy);
		if( !empty($term) )
			echo sprintf( '<li>Taxonomy Term: %s</li>', $term);
		if( !empty($post_type) )
			echo sprintf( '<li>Post Type: %s</li>', $post_type);
		if( !empty($feed_slug) )
			echo sprintf( '<li>Feed Slug: %s</li>', $feed_slug);
		if( !empty($category) )
			echo sprintf( '<li>Category: %s</li>', $category);
		
		echo sprintf( '<li>Remove query string: %s</li>', ( !empty($remove_query_string)?'true':'false') );
		echo '</ul></div>'
/*		echo '</ul>';

		echo '<hr />';
		echo '<p><strong>';
		echo __('Importing Episodes...', 'powerpress');
		echo '</strong></p>';

		echo '<p>';
		echo sprintf( __('Items Found: %d', 'powerpress'), $item_count);
		echo '</p>';*/
        ?>
        <style>
        #table_header {
          box-sizing: border-box;
          width: 100%;
          border-top: 1px solid rgba(144, 144, 144, 0.40);
          border-left: 1px solid rgba(144, 144, 144, 0.40);
          border-right: 1px solid rgba(144, 144, 144, 0.40);
          padding: 20px;
          padding-bottom: 40px;
          border-radius: 5px 5px 0 0;
        }
        table {
          width: 100%;
          table-layout: fixed;
          border-collapse: separate;
          border: 1px solid rgba(144, 144, 144, 0.40);
          border-bottom-left-radius: 10px;
          border-bottom-right-radius: 10px;
        }
        .left {
          float: left;
        }
        .right {
          float: right;
        }
        /*thead th {
          padding: 20px;
          border-bottom: 1px solid rgba(144, 144, 144, 0.40);
        }*/
        thead th:nth-child(1) {
          width: 3%
        }
        thead th:nth-child(2) {
          width: 58%;
        }
        thead th:nth-child(3) {
          width: 33%;
        }
        tbody td {
          padding: 15px 20px;
          border-bottom: 1px solid rgba(144, 144, 144, 0.40);
        }
        tbody td:nth-child(2) {
          font-weight: bold;
          text-align: left;
        }
        tbody td:nth-child(3) {
          text-align: right;
        }
        tbody td:nth-child(4) {
          text-align: right;
        }
        tbody tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }
        tbody tr:last-child td{
            border: unset;
        }
        tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }
        .green-text {
          color: green;
        }
        .warning-text {
          color: orange;
        }
        .subtle-text {

        }
        </style>
        <div class="pp_col" style="flex: 5">
        <div id="table_header">
            <strong class="left">Imported Episodes</strong>
            <span class="right"><?php echo  sprintf( __('%d Episodes Found', 'powerpress'), $item_count) ?></span>
        </div>
        <table>
          <thead>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
        <?php
		@flush();

		$item_start_pos = 0;
		$item_start_pos = (function_exists('mb_stripos')?mb_stripos($this->m_content, '<item>', $item_start_pos):stripos($this->m_content, '<item>', $item_start_pos) );
		$item_end_pos = $item_start_pos;
		$item_end_pos = (function_exists('mb_stripos')?mb_stripos($this->m_content, '</item>', $item_end_pos):stripos($this->m_content, '</item>', $item_end_pos) );
		
		$count = 0;
		while( $item_start_pos !== false && $item_end_pos !== false ) // If one were to return false, we stap!
		{
		    $count++;
			// check item limit at the beginning of each iteration
			if( $import_item_limit > 0 && $this->m_item_pos >= $import_item_limit ) {
				break; // Item limit reached, stop!
			}
			
			echo "<tr><td>{$count}</td>";
			$new_start = $item_start_pos + mb_strlen('<item>');
			$item_content = mb_substr($this->m_content, $new_start, $item_end_pos - $new_start);
			$this->import_item($item_content, $MatchFilter, $import_blog_posts, $category, $feed_slug, $post_type, $taxonomy, $term, $remove_query_string, $post_status);
			echo '</tr>';
			
			// Extra stop just in case...
			if( $count > 3000 )
				break;
				
			if( $count % 25 == 0 )
				@flush();
			
			$item_start_pos = (function_exists('mb_stripos')?mb_stripos($this->m_content, '<item>', $item_end_pos):stripos($this->m_content, '<item>', $item_end_pos) );
			$item_end_pos = (function_exists('mb_stripos')?mb_stripos($this->m_content, '</item>', $item_start_pos):stripos($this->m_content, '</item>', $item_start_pos) );
		}
	}

	function import() {
?>
<h5 class="pp_align-center"><?php _e('Importing Podcast', 'powerpress') ?>
<?php

		$result = false;
		if ( empty($_POST['podcast_feed_url']) ) {
			?><?php _e(' from uploaded file...', 'powerpress'); ?></h5><?php
			$result = $this->_import_handle_upload();
		}
		else
		{
			?><?php _e(' from URL: ', 'powerpress'); echo esc_html($_POST['podcast_feed_url']) ?></h5><?php
			$result = $this->_import_handle_url();
		}
		
		if( $result == false ) {
			$this->addError( __('Error occurred importing podcast.', 'powerpress') );
			return;
		}
		
		// Match posts by:
		$MatchFilter = array('match_guid'=>true);
		$MatchFilter['match_date'] = (!empty($_POST['match_date'])?true:false);
		$MatchFilter['match_title'] = (!empty($_POST['match_title'])?true:false);
		$MatchFilter['match_filename'] = (!empty($_POST['match_filename'])?true:false);
		
		$import_blog_posts = (!empty($_POST['import_blog_posts'])?true:false);
		$import_item_limit  = (!empty($_POST['import_item_limit'])?intval($_POST['import_item_limit']):0);
		$remove_query_string = (!empty($_POST['remove_query_string'])?true:false);
		$post_status = ( !empty($_POST['import_post_status']) ? $_POST['import_post_status']: 'publish' );
		$category  = (!empty($_POST['category'])?intval($_POST['category']):'');
		$feed_slug  = (!empty($_POST['feed_slug'])?($_POST['feed_slug']):'');
		$post_type = (!empty($_POST['post_type'])?($_POST['post_type']):'');
		$post_type_feed_slug = (!empty($_POST['post_type_feed_slug'])?($_POST['post_type_feed_slug']):'');
		$ttid = (!empty($_POST['podcast_ttid'])?intval($_POST['podcast_ttid']):'');
		//$import_  = (!empty($_POST['import_item_limit'])?intval($_POST['import_item_limit']):0);
		$import_to = 'default';
		if( !empty($_POST['import_to']) && $_POST['import_to'] != 'default' )
			$import_to = $_POST['import_to'];
		if( !empty($_REQUEST['import']) && $_REQUEST['import'] == 'powerpress-libsyn-rss-podcast' )
			$remove_query_string = true;
		
		// Set the correct parameters going in...
		switch( $import_to )
		{
			case 'category': {
				$feed_slug = '';
				$post_type = '';
				$ttid = '';
				if( empty($category) ) {
					echo '<p>No category selected.</p>';
					return;
				}
			}; break;
			case 'channel': {
				$category = '';
				$post_type = '';
				$ttid = '';
				if( empty($feed_slug) ) {
					echo '<p>No podcast channel selected.</p>';
					return;
				}
			}; break;
			case 'post_type': {
				$category = '';
				$feed_slug = $post_type_feed_slug;
				$ttid = '';
				
				if( empty($feed_slug) ) {
					echo '<p>No feed slug specified.</p>';
					return;
				}
				if( empty($post_type) ) {
					echo '<p>No post type specified.</p>';
					return;
				}
			}; break;
			case 'taxonomy': {
				$category = '';
				$feed_slug = '';
				$post_type = '';
				
				if( empty($ttid) ) {
					echo '<p>No taxonomy podcast selected.</p>';
					return;
				}
					
			}; break;
			case 'default':
			default: {
				$category = '';
				$feed_slug = '';
				$post_type = '';
				$ttid = '';
			}; break;
		}
		
		// First import program info...
		if( preg_match('/^(.*)<item>/is', $this->m_content, $matches) )
		{
			if( $import_to == 'default' ) {
				$overwrite_program_info = (!empty($_POST['import_overwrite_program_info'])?true:false);
				$import_itunes_image = (!empty($_POST['import_itunes_image'])?true:false);
				if( $overwrite_program_info || $import_itunes_image )
					$this->import_program_info($matches[1], $overwrite_program_info, $import_itunes_image);
			} else if( $import_to == 'category' ) {
				$overwrite_program_info = (!empty($_POST['import_overwrite_program_info_category'])?true:false);
				$import_itunes_image = (!empty($_POST['import_itunes_image_category'])?true:false);
				if( $overwrite_program_info || $import_itunes_image )
					$this->import_program_info($matches[1], $overwrite_program_info, $import_itunes_image, $category);
			} else if( $import_to == 'channel' ) {
				$overwrite_program_info = (!empty($_POST['import_overwrite_program_info_channel'])?true:false);
				$import_itunes_image = (!empty($_POST['import_itunes_image_channel'])?true:false);
				if( $overwrite_program_info || $import_itunes_image )
					$this->import_program_info($matches[1], $overwrite_program_info, $import_itunes_image, false, $feed_slug);
			} else if( $import_to == 'post_type' ) {
				$overwrite_program_info = (!empty($_POST['import_overwrite_program_info_post_type'])?true:false);
				$import_itunes_image = (!empty($_POST['import_itunes_image_post_type'])?true:false);
				if( $overwrite_program_info || $import_itunes_image )
					$this->import_program_info($matches[1], $overwrite_program_info, $import_itunes_image, false, $feed_slug, $post_type);
			} else if( $import_to == 'taxonomy' ) {
				$overwrite_program_info = (!empty($_POST['import_overwrite_program_info_taxonomy'])?true:false);
				$import_itunes_image = (!empty($_POST['import_itunes_image_taxonomy'])?true:false);
				if( $overwrite_program_info || $import_itunes_image )
					$this->import_program_info($matches[1], $overwrite_program_info, $import_itunes_image, false, false, false, $ttid);
			}
		}
		
		$this->import_episodes($MatchFilter, $import_blog_posts, $import_item_limit, $category, $feed_slug, $post_type, $ttid, $remove_query_string, $post_status);
		
		$migrated_to_blubrry = false;
		if( !empty($_POST['migrate_to_blubrry'])  && !empty($GLOBALS['pp_migrate_media_urls']) ) {
			require_once( POWERPRESS_ABSPATH .'/powerpressadmin-migrate.php');
			$migrated_to_blubrry = true;
			
			$update_option = true;
			$QueuedFiles = get_option('powerpress_migrate_queued');
			if( !is_array($QueuedFiles) ) {
				$QueuedFiles = array();
				$update_option = false;
			}
			
			$add_urls = '';
			foreach( $GLOBALS['pp_migrate_media_urls'] as $meta_id => $url )
			{
				if( empty($QueuedFiles[ $meta_id ]) ) { // Add to the array if not already added
					$QueuedFiles[ $meta_id ] = $url;
					if( !empty($add_urls) ) {
						$add_urls .= "\n";
					}
					$this->m_item_migrate_count++;
					$add_urls .= $url;
				}
			}
            if (!isset($_GET['from']) || ($_GET['from'] != 'gs' || $_GET['from'] != 'onboarding')) {
                echo '<h3>';
                echo __('Migration request...', 'powerpress');
                echo '</h3>';
                echo '<pre style="border: 1px solid #333; background-color: #FFFFFF; padding: 4px 8px;">';
                echo $add_urls;
                echo '</pre>';
            }
			$UpdateResults = powepress_admin_migrate_add_urls($add_urls);
			if( !empty($UpdateResults) )
			{
				echo '<p>Migration queued successfully.</p>';
				// Queued ok...
				if( $update_option )
					update_option('powerpress_migrate_queued', $QueuedFiles);
				else
					add_option('powerpress_migrate_queued', $QueuedFiles, '', 'no');
			}
			else
			{
				echo '<p>Failed to request migration.</p>';
			}
		}
		powerpress_page_message_print();
		?>
        <td colspan="4" style="text-align: right">
        <?php
        if ($this->m_item_inserted_count != 0) {
            echo $this->m_item_inserted_count . " Episodes Imported";
        }
        if ($this->m_item_skipped_count != 0) {
            if($this->m_item_inserted_count != 0) {
                echo ' / ';
            }
            echo $this->m_item_skipped_count . " Episodes Skipped";
        }
        ?>
        </td>
		<?php
		echo '</tbody></table></div></div>';
		if( !empty( $this->m_item_migrate_count ) )
			echo '<p>'. sprintf(__('Media files queued for migration: %d', 'powerpress'), $this->m_item_migrate_count).'</p>';
		
		echo '';
		if( $migrated_to_blubrry ) {
			echo '<p>'. sprintf(__('Visit %s to monitor the migration process.','powerpress'), '<strong><a href="'.admin_url('admin.php?page=powerpress/powerpressadmin_migrate.php') .'">'. __('Migrate Media', 'powerpress') .'</a></strong>' ). '</p>';
		} else {
			echo '<p>'. sprintf(__('You may now migrate your media manually or use the %s tool.','powerpress'), '<strong><a href="'.admin_url('admin.php?page=powerpress/powerpressadmin_migrate.php') .'">'. __('Migrate Media', 'powerpress') .'</a></strong>' ). '</p>';
		}
		$nextUrl = '';
		$GeneralSettings = powerpress_get_settings('powerpress_general');
		if(!empty($_GET['from']) && $_GET['from'] == 'onboarding') {
		    if (isset($GeneralSettings['blubrry_hosting']) && $GeneralSettings['blubrry_hosting'] != null) {
                $nextUrl = admin_url("admin.php?page=powerpressadmin_basic&step=createEpisode&import=true&migrate=true");
            } else {
                if ($this->isHostedOnBlubrry) {
                    $nextUrl = admin_url("admin.php?page=powerpressadmin_basic&step=blubrrySignin&import=true");
                } else {
                    $nextUrl = admin_url("admin.php?page=powerpressadmin_basic&step=nohost&import=true&from=import");
                }
            }
        }
		else if (!empty($_GET['from']) && $_GET['from'] == 'gs') {
            if (isset($GeneralSettings['blubrry_hosting']) && $GeneralSettings['blubrry_hosting'] != null) {
                $nextUrl = admin_url("admin.php?page=powerpressadmin_onboarding.php&step=createEpisode&import=true&migrate=true");
            } else {
                if ($this->isHostedOnBlubrry) {
                    $nextUrl = admin_url("admin.php?page=powerpressadmin_onboarding.php&step=blubrrySignin&import=true");
                } else {
                    $nextUrl = admin_url("admin.php?page=powerpressadmin_onboarding.php&step=nohost&import=true&from=import");
                }
            }
		}
		if(!empty($_GET['from'])) {
        ?>

            <div class="pp_col" style="padding: 20px 0px;">
                <hr class="pp_align-center">
                <div class="pp_button-container" style="float: right;">
                    <a href="<?php echo $nextUrl ?>"><button name="submit" type="button" class="pp_button" value="Import Podcast"><span>Continue</span></button></a>
                </div>

            </div>
        <?php
		}
	}

	function dispatch() {
		
		$this->m_step = 0;
		if( !empty($_POST['step']) )
			$this->m_step = intval($_POST['step']);
		else if( !empty($_GET['step']) )
			$this->m_step = intval($_GET['step']);
			
		// Drop back down a step if not setup for hosting...
		if( !empty($_POST['migrate_to_blubrry']) ) {
			$Settings = get_option('powerpress_general');
			if( empty($Settings['blubrry_auth']) ) {
				echo '<div class="notice is-dismissible updated"><p>'. sprintf(__('You must have a blubrry Podcast Hosting account to continue.', 'powerpress')) .' '. '<a href="http://create.blubrry.com/resources/podcast-media-hosting/" target="_blank">'. __('Learn More', 'powerpress') .'</a>'. '</p></div>';
				$this->m_step = 0; // Drop back a step
			}
		}
		
		$this->header();

		switch ($this->m_step) {
			case 0 :
				$this->greet();
				break;
			case 1 :
				check_admin_referer('import-powerpress-rss');
				$result = $this->import();
				if ( is_wp_error( $result ) )
					echo $result->get_error_message();
				break;
		}

	}

	function get_step() {

		return $this->m_step;
	}
	
	function _find_post_by_guid($guid)
	{
		global $wpdb;

		$post_guid = wp_unslash( sanitize_post_field( 'guid', $guid, 0, 'db' ) );

		$query = "SELECT ID FROM $wpdb->posts WHERE 1=1 ";
		$args = array();

		if ( !empty ( $post_guid ) ) {
			$query .= 'AND guid = %s';
			$args[] = $post_guid;
		}

		if ( !empty ( $args ) ) {
			$found = intval( $wpdb->get_var( $wpdb->prepare($query, $args) ) );
			if( $found > 0 )
				return $found;
		}
		
		return 0;
	}
	
	function _find_post_by_title($title)
	{
		global $wpdb;

		$post_guid = wp_unslash( sanitize_post_field( 'post_title', $title, 0, 'db' ) );

		$query = "SELECT ID FROM $wpdb->posts WHERE 1=1 ";
		$args = array();

		if ( !empty ( $post_guid ) ) {
			$query .= 'AND post_title = %s';
			$args[] = $title;
		}

		if ( !empty ( $args ) ) {
			$found = intval( $wpdb->get_var( $wpdb->prepare($query, $args) ) );
			if( $found > 0 )
				return $found;
		}
		
		return 0;
	}
	
	function _find_post_by_date($date)
	{
		global $wpdb;

		$post_guid = wp_unslash( sanitize_post_field( 'post_date', $date, 0, 'db' ) );

		$query = "SELECT ID FROM $wpdb->posts WHERE 1=1 ";
		$args = array();

		if ( !empty ( $post_guid ) ) {
			$query .= 'AND post_date = %s';
			$args[] = $date;
		}

		if ( !empty ( $args ) ) {
			$found = intval( $wpdb->get_var( $wpdb->prepare($query, $args) ) );
			if( $found > 0 )
				return $found;
		}
		
		return 0;
	}
	
	function _find_post_by_enclosure_filename($filename, $feed_slug = '')
	{
		global $wpdb;
		
		$meta_key = 'enclosure';
		if( !empty($feed_slug) && $feed_slug != 'podcast' )
			$meta_key = '_'. $feed_slug .':enclosure';
		
		$meta_value = $filename;
		
		$query = "SELECT p.ID ";
		$query .= "FROM {$wpdb->posts} AS p ";
		$query .= "INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id ";
		$query .= "WHERE pm.meta_key = %s ";
		$query .= "AND pm.meta_value LIKE '%%%s%%' ";
		$query .= "AND p.post_type != 'revision' ";
		$query .= "GROUP BY p.ID ";
		$query .= "ORDER BY p.post_date DESC LIMIT 1 "; // Make sure we use the oldest date
		$query = $wpdb->prepare($query, $meta_key, $meta_value );
		
		$results = $wpdb->get_results($query, ARRAY_A);
		if( !empty($results) )
		{
			foreach( $results as $null => $row ) {
				return $row['ID'];
			}
		}
		
		return 0;
	}
	
	function _find_post($guid = '', $title = '', $date = '', $media_url = '', $feed_slug='') {
		global $wpdb;
		
		if( !empty($guid) )
		{
			$found = $this->_find_post_by_guid($guid);
			if( $found )
				return $found;
		}
		
		if( !empty($media_url) )
		{
			$filename = basename($media_url);
			if( !empty($filename) ) {
				$found = $this->_find_post_by_enclosure_filename($filename, $feed_slug);
				if( $found )
					return $found;
			}
		}
		
		if( !empty($title) )
		{
			$found = $this->_find_post_by_title($title);
			if( $found )
				return $found;
		}
		
		if( !empty($date) )
		{
			$found = $this->_find_post_by_date($date);
				return $found;
		}

		return 0;
	}
	
	function _import_post_to_db($post, $feed_slug = '')
	{
		extract($post);
		$post_id = wp_insert_post($post);
		if ( is_wp_error( $post_id ) )
			return $post_id;
		if (!$post_id) {
			_e('Couldn&#8217;t get post ID', 'powerpress');
			return false;
		}
		
		if (0 != count($categories))
			wp_create_categories($categories, $post_id);
					
		if( !empty($enclosure['url']) )
		{
			$encstring = $enclosure['url'] . "\n" . $enclosure['length'] . "\n" . $enclosure['type'];
			$serialize = array();
			if( !empty($enclosure['duration']) && function_exists('powerpress_raw_duration') )
				$serialize['duration'] = powerpress_raw_duration($enclosure['duration']);
			if( !empty($enclosure['keywords']) )
				$serialize['keywords'] = $enclosure['keywords'];
			if( !empty($enclosure['summary']) )
				$serialize['summary'] = $enclosure['summary'];
			if( !empty($enclosure['subtitle']) )
				$serialize['subtitle'] = $enclosure['subtitle'];
			if( !empty($enclosure['author']) )
				$serialize['author'] = $enclosure['author'];
			if( !empty($enclosure['itunes_image']) )
				$serialize['itunes_image'] = $enclosure['itunes_image'];
			if( !empty($enclosure['block']) )
				$serialize['block'] = $enclosure['block'];
			if( !empty($enclosure['cc']) )
				$serialize['cc'] = $enclosure['cc'];
			if( !empty($enclosure['order']) )
				$serialize['order'] = $enclosure['order'];
			if( !empty($enclosure['explicit']) )
				$serialize['explicit'] = $enclosure['explicit'];
			if( !empty($enclosure['category']) )
				$serialize['category'] = $enclosure['category'];
				
			if( !empty($serialize) )
				$encstring .= "\n". serialize( $serialize );
				
			if( empty($feed_slug) || $feed_slug == 'podcast' ) // 'podcast' == $feed_slug || '' == $feed_slug
				$meta_id = add_post_meta($post_id, 'enclosure', $encstring, true);
			else
				$meta_id = add_post_meta($post_id, '_'. $feed_slug .':enclosure', $encstring, true);
		
			if( $meta_id ) {
				if( empty($GLOBALS['pp_migrate_media_urls']) )
					$GLOBALS['pp_migrate_media_urls'] = array();
				$GLOBALS['pp_migrate_media_urls'][ $meta_id ] = $enclosure['url'];
			}
		}
		return $post_id;
	}
	
	function _parse_enclosure($string, $post, $category_strict='')
	{
		global $wpdb;
		
		// Create an XML parser
		if ( ! function_exists( 'xml_parser_create' ) ) {
			// These are WordPress strings, no need to use our namespace for these messages.
			trigger_error( __( "PHP's XML extension is not available. Please contact your hosting provider to enable PHP's XML extension." ) );
			wp_die( __( "PHP's XML extension is not available. Please contact your hosting provider to enable PHP's XML extension." ) );
		}


		$p = xml_parser_create();
		xml_parse_into_struct($p, $string, $vals, $index);
		xml_parser_free($p);

		if( !empty($vals[0]['attributes']['URL']) )
		{
			$enclosure = array('url'=>trim($vals[0]['attributes']['URL']),'length'=>1, 'type'=>'');
			if(  !empty($vals[0]['attributes']['LENGTH']) )
				$enclosure['length'] = trim($vals[0]['attributes']['LENGTH']);
			if(  !empty($vals[0]['attributes']['TYPE']) )
				$enclosure['type'] = trim($vals[0]['attributes']['TYPE']);
			if( empty($enclosure['type']) )
				$enclosure['type'] = powerpress_get_contenttype($enclosure['url']);
			$matches = array();
			if( preg_match('|<itunes:duration>(.*?)</itunes:duration>|i', $post, $matches) )
			{
				$enclosure['duration'] = $this->_sanatize_tag_value( $matches[1] );
			}
			
			// keywords No longer supported by iTunes:
			if( preg_match('|<itunes:keywords>(.*?)</itunes:keywords>|i', $post, $matches) )
			{
				$enclosure['keywords'] = $this->_sanatize_tag_value( $matches[1] );
			}
			
			if( preg_match('|<itunes:summary>(.*?)</itunes:summary>|is', $post, $matches) )
			{
				$enclosure['summary'] = $this->_sanatize_tag_value( $matches[1] );
			}
			
			if( preg_match('|<itunes:subtitle>(.*?)</itunes:subtitle>|i', $post, $matches) )
			{
				$enclosure['subtitle'] = $this->_sanatize_tag_value( $matches[1] );
			}
			
			if( preg_match('|<itunes:author>(.*?)</itunes:author>|i', $post, $matches) )
			{
				$enclosure['author'] = $this->_sanatize_tag_value(  $matches[1] );
			}
			
			if( preg_match('|<itunes:block>(.*?)</itunes:block>|i', $post, $matches) )
			{
				$value = strtolower(trim( $matches[1] ));
				if( $value == 'yes' )
					$enclosure['block'] = 1;
			}
			
			if( preg_match('/<itunes:image[^h]*href="(.*?)".*(\/>|>.*<\/itunes:image>)/i', $post, $matches) )
			{
				$enclosure['itunes_image'] = html_entity_decode( trim( $matches[1] ) );
			}
			
			if( preg_match('|<itunes:isClosedCaptioned>(.*?)</itunes:isClosedCaptioned>|i', $post, $matches) )
			{
				$value = strtolower(trim( $matches[1] ));
				if( $value == 'yes' )
					$enclosure['cc'] = 1;
			}
			
			if( preg_match('|<itunes:order>(.*?)</itunes:order>|i', $post, $matches) )
			{
				$value = trim( $matches[1] );
				if( !empty($value) )
					$enclosure['order'] = intval($value);
			}
			
			if( preg_match('|<itunes:explicit>(.*?)</itunes:explicit>|i', $post, $matches) )
			{
				$explicit_array = array('yes'=>1, 'clean'=>2); // No need to save 'no'
				$value = strtolower( trim( $matches[1] ) );
				if( !empty($explicit_array[ $value ]) )
					$enclosure['explicit'] = $explicit_array[ $value ];
			}
			
			if( !empty($category_strict) )
			{
				$enclosure['category'] = $category_strict;
			}
				
			return $enclosure;
		}
		
		return '';
	}
	
	function _import_handle_url() {
		
		if( empty($_POST['podcast_feed_url']) ) {
			echo '<p>'.	__( 'URL is empty.', 'powerpress' ) .'<p>';
			return false;
		}
		
		$options = array();
		$options['user-agent'] = 'Blubrry PowerPress/'.POWERPRESS_VERSION;
		if( !empty($_GET['import']) && $_GET['import'] == 'powerpress-squarespace-rss-podcast' )
			$options['user-agent'] = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36';
		else if( !empty($_GET['import']) && $_GET['import'] == 'powerpress-podbean-rss-podcast' )
			$options['user-agent'] = 'iTunes/12.2.2 (Macintosh; OS X 10.10.5) AppleWebKit/600.8.9';  // Common user agent
		// 'gPodder/3.8.4 (+http://gpodder.org/)';
		$options['timeout'] = 10;
		
		$response = wp_safe_remote_get($_POST['podcast_feed_url'], $options);
		if ( is_wp_error( $response ) ) {
			echo '<p>'.	$response->get_error_message() .'<p>';
			return false;
		}
		
		$this->m_content = wp_remote_retrieve_body( $response );
		return true;
	}
	
	function _import_handle_upload() {
		if ( ! isset( $_FILES['podcast_feed_file'] )  || empty($_FILES['podcast_feed_file']['tmp_name']) ) {
			echo '<p>'.	__( 'Upload failed.', 'powerpress' ).'<p>';
			return false;
		}
		
		$this->m_content = file_get_contents($_FILES['podcast_feed_file']['tmp_name']);
		return true;
	}
} // end PowerPress_RSS_Podcast_Import class

	$powerpress_rss_podcast_import = new PowerPress_RSS_Podcast_Import();

	register_importer('powerpress-soundcloud-rss-podcast', __('Podcast from SoundCloud', 'powerpress'), __('Import episodes from a SoundCloud podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	register_importer('powerpress-libsyn-rss-podcast', __('Podcast from LibSyn', 'powerpress'), __('Import episodes from a LibSyn podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	register_importer('powerpress-podbean-rss-podcast', __('Podcast from PodBean ', 'powerpress'), __('Import episodes from a PodBean podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	register_importer('powerpress-squarespace-rss-podcast', __('Podcast from Squarespace', 'powerpress'), __('Import episodes from a Squarespace podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	register_importer('powerpress-anchor-rss-podcast', __('Podcast from Anchor.fm', 'powerpress'), __('Import episodes from an Anchor.fm podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	register_importer('powerpress-rss-podcast', __('Podcast RSS Feed', 'powerpress'), __('Import episodes from a RSS podcast feed.', 'powerpress'), array ($powerpress_rss_podcast_import, 'dispatch'));
	
}; // end if WP_Importer exists

// eof