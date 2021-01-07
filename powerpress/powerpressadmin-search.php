<?php
// powerpressadmin-search.php

function powerpress_admin_search()
{
	$General = powerpress_get_settings('powerpress_general');
	if( empty($General['seo_feed_title']) )
		$General['seo_feed_title'] = '';
	
?>
<script language="javascript"><!--

jQuery(document).ready(function() {
	
<?php  
	
		if( !empty($General['seo_feed_title'])  && $General['seo_feed_title'] == 1 )
		echo "	jQuery('#powerpress_example_post_title').hide();\n";
?>
	jQuery('#seo_feed_title').change( function() {
		if( this.checked )
			jQuery('#powerpress_seo_feed_title_1').prop('checked', true);
		else
			jQuery('.powerpress_seo_feed_title').prop('checked', false);
	});
	jQuery('.powerpress_seo_feed_title').change( function() {
		
		jQuery('#seo_feed_title').prop('checked', true);
		switch( this.value )
		{
			case '1':
			case 1: {
				jQuery('#powerpress_example_post_title').hide();
			}; break;
			case '2':
			case 2: {
				jQuery('#powerpress_example_post_title').show();
				
				var p_title_html = jQuery('#powerpress_example_post_title')[0].outerHTML;
				var e_title_html = jQuery('#powerpress_example_episode_title')[0].outerHTML;
				jQuery('#powerpress_example_post_episode_title').html( e_title_html + p_title_html);
			}; break;
			case '3':
			case 3: {
				jQuery('#powerpress_example_post_title').show();
				
				var p_title_html = jQuery('#powerpress_example_post_title')[0].outerHTML;
				var e_title_html = jQuery('#powerpress_example_episode_title')[0].outerHTML;
				jQuery('#powerpress_example_post_episode_title').html( p_title_html + e_title_html);
			}; break;
			default: {
				
			}
		}
	});
});
//-->
</script>
<h1 class="pp-heading"><?php echo __('Podcast SEO', 'powerpress'); ?></h1>
<p class="pp-settings-text">
    <?php echo __('Enable features to help with podcasting search engine optimization (SEO). The following options can assist your web and podcasting SEO strategies.', 'powerpress'); ?>
	<a href="http://create.blubrry.com/resources/powerpress/advanced-tools-and-options/podcasting-seo-settings/"  target="_blank"><?php echo __('Learn More', 'powerpress'); ?></a>
</p>


<div class="pp-settings-section">
    <h2><?php echo __('Episode Titles', 'powerpress'); ?></h2>
	<input name="PowerPressSearchToggle[seo_feed_title]" type="hidden" value="0" />
	<input class="pp-settings-checkbox" id="seo_feed_title" name="PowerPressSearchToggle[seo_feed_title]" type="checkbox" value="1" <?php if( !empty($General['seo_feed_title']) ) echo 'checked '; ?> />
    <div class="pp-settings-subsection-no-border" style="padding-left: 1ch;">
        <p class="pp-main">
        <?php echo __('Specify custom episode titles for podcast feeds.', 'powerpress'); ?>
	    </p>
        <div>
            <input class="pp-settings-radio-small" type="radio" style="margin: 2ch 8px 0 1em;vertical-align: top;" class="powerpress_seo_feed_title" id="powerpress_seo_feed_title_1" name="General[seo_feed_title]" value="1" <?php if( $General['seo_feed_title'] == 1 ) echo 'checked'; ?> />
            <div class="pp-settings-subsection-no-border" style="padding-bottom: 0;">
                <p class="pp-settings-text" style="margin: 0;"><?php echo __('Feed episode title replaces post title', 'powerpress'); ?></p>
                <p class="pp-sub" style="font-size: 14px"><?php echo __('Default', 'powerpress'); ?></p>
            </div>
        </div>
        <div>
            <input class="pp-settings-radio-small" type="radio" style="margin-left: 1em;" class="powerpress_seo_feed_title" id="powerpress_seo_feed_title_2" name="General[seo_feed_title]" value="2" <?php if( $General['seo_feed_title'] == 2 ) echo 'checked'; ?> />
            <div class="pp-settings-subsection-no-border">
                <p class="pp-settings-text" style="margin: 0;"><?php echo __('Feed episode title prefixes post title', 'powerpress'); ?></p>
            </div>
        </div>
        <div>
            <input class="pp-settings-radio-small" type="radio" style="margin-left: 1em;" class="powerpress_seo_feed_title" id="powerpress_seo_feed_title_3" name="General[seo_feed_title]" value="3" <?php if( $General['seo_feed_title'] == 3 ) echo 'checked'; ?> />
            <div class="pp-settings-subsection-no-border">
                <p class="pp-settings-text" style="margin: 0;"><?php echo __('Feed episode title appended to post title', 'powerpress'); ?></p>
            </div>
        </div>
	</div>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('AudioObjects', 'powerpress'); ?></h2>
	<input name="General[seo_audio_objects]" type="hidden" value="0" />
	<input class="pp-settings-checkbox" name="General[seo_audio_objects]" type="checkbox" value="1" <?php if( !empty($General['seo_audio_objects']) ) echo 'checked '; ?> />
    <div class="pp-settings-subsection-no-border" style="padding-left: 1ch;">
        <p class="pp-main"><?php echo __('Schema.org audio objects in microdata format.', 'powerpress'); ?></p>
        <p class="pp-sub"><?php echo __('What this means and why it\'s important', 'powerpress'); ?></p>
    </div>
</div>

<div class="pp-settings-section">
    <h2><?php echo __('VideoObjects', 'powerpress'); ?></h2>
	<input name="General[seo_video_objects]" type="hidden" value="0" />
	<input class="pp-settings-checkbox" name="General[seo_video_objects]" type="checkbox" value="1" <?php if( !empty($General['seo_video_objects']) ) echo 'checked '; ?> />
    <div class="pp-settings-subsection-no-border" style="padding-left: 1ch;">
        <p class="pp-main"><?php echo __('Schema.org video objects in microdata format.', 'powerpress'); ?></p>
        <p class="pp-sub"><?php echo __('What this means and why it\'s important', 'powerpress'); ?></p>
    </div>
</div>

<?php

?>

<?php
} // End powerpress_admin_search()

