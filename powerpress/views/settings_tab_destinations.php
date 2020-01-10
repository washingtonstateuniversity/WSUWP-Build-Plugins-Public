<?php
	// settings_tab_destinations.php
	$cat_ID = '';
	if( !empty($FeedAttribs['category_id']) )
		$cat_ID = $FeedAttribs['category_id'];
	if( empty($FeedAttribs['type']) )
		$FeedAttribs['type'] = '';
	
	$feed_url = '';
	switch( $FeedAttribs['type'] )
	{
		case 'ttid': {
			$feed_url = get_term_feed_link($FeedAttribs['term_taxonomy_id'], $FeedAttribs['taxonomy_type'], 'rss2' );
		}; break;
		case 'category': {
			if( !empty($General['cat_casting_podcast_feeds']) )
				$feed_url = get_category_feed_link($cat_ID, 'podcast');
			else
				$feed_url = get_category_feed_link($cat_ID);
		}; break;
		case 'channel': {
			$feed_url = get_feed_link($FeedAttribs['feed_slug']);
		}; break;
		case 'post_type': {
			$feed_url = get_post_type_archive_feed_link($FeedAttribs['post_type'], $FeedAttribs['feed_slug']);
		}; break;
		case 'general':
		default: {
			$feed_url = get_feed_link('podcast');
		}
	}
	
	if( empty($FeedSettings['itunes_url']) )
		$FeedSettings['itunes_url'] = '';
	if( empty($FeedSettings['blubrry_url']) )
		$FeedSettings['blubrry_url'] = '';
	if( empty($FeedSettings['stitcher_url']) )
		$FeedSettings['stitcher_url'] = '';
	if( empty($FeedSettings['tunein_url']) )
		$FeedSettings['tunein_url'] = '';	
	if( empty($FeedSettings['spotify_url']) )
		$FeedSettings['spotify_url'] = '';
	if( empty($FeedSettings['google_url']) )
		$FeedSettings['google_url'] = '';
	if(empty($FeedSettings['iheart_url']) )
	    $FeedSettings['iheart_url'] = '';
	if(empty($FeedSettings['deezer_url']) )
	    $FeedSettings['deezer_url'] = '';
	if(empty($FeedSettings['pandora_url']) )
	    $FeedSettings['pandora_url'] = '';

		
	$googleUrl =  'https://www.google.com/podcasts?feed='.powerpress_base64_encode($feed_url);
?>



<h2><?php echo __('Destinations', 'powerpress'); ?></h2>
<p><?php echo __('Podcast directories and applications to syndicate your podcast.', 'powerpress'); ?></p>

<table class="form-table">
<tr valign="top">
<th scope="row">&nbsp;</th> 
<td>
<p><?php echo __('For your reference, your podcast feed URL is...', 'powerpress'); ?></p>
<input type="text" style="width: 80%;" name="NULL[feed_url]" value="<?php echo esc_attr($feed_url); ?>" maxlength="1024" onclick="javascript: this.select();" onfocus="javascript: this.select();" />
</td>
</tr>
</table>
<br />

<h3><?php echo __('Podcast Directories', 'powerpress'); ?></h3>
<p><?php echo __('Listing URLs are used by player subscribe links, subscribe sidebar widgets and subscribe to podcast page shortcodes.', 'powerpress'); ?></p>
<table class="form-table">
<tr valign="top">
<th scope="row"><?php echo __('Apple', 'powerpress'); ?></th> 
<td>
	<p><strong><a href="https://create.blubrry.com/manual/podcast-promotion/submit-podcast-to-itunes/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo __('Submit podcast to Apple', 'powerpress'); ?></a></strong></p>
	<label for="itunes_url" style="font-size: 120%; display: block; font-weight: bold;"><?php echo __('Apple Subscription URL', 'powerpress'); ?></label>
	<input type="text" style="width: 80%;" id="itunes_url" name="Feed[itunes_url]" value="<?php echo esc_attr($FeedSettings['itunes_url']); ?>" maxlength="255" />
	<p class="description"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'http://itunes.apple.com/podcast/title-of-podcast/id<strong>000000000</strong>'); ?></p>
	<p><?php echo __('Apple will email your Subscription URL to your <em>Apple Email</em> when your podcast is accepted into the Apple Podcasts Directory.', 'powerpress'); ?></p>
</td>
</tr>
</table>

<table class="form-table">
<tr valign="top">
<th scope="row"><?php echo __('Google', 'powerpress'); ?> <?php echo powerpressadmin_new(); ?></th>
<td>
	<p><strong><a href="https://create.blubrry.com/manual/podcast-promotion/submit-podcast-google-podcasts/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('Learn more about Google Podcasts', 'powerpress'); ?></a></strong></p>
	<label for="googleplay_url" style="font-size: 120%; display: block; font-weight: bold;"><?php echo __('Google Listing URL', 'powerpress'); ?></label>
	<input type="text" class="bpp-input-normal" style="<?php echo ( empty($FeedSettings['google_url'])?'':'display: none;'); ?>" id="google_url" name="Null[google_url]" value="<?php echo esc_attr($googleUrl); ?>" maxlength="255" readOnly onclick="javascript: this.select();" onfocus="javascript: this.select();" />
	<input type="text" class="bpp-input-normal" placeholder="<?php echo esc_attr($googleUrl); ?>" style="<?php echo ( empty($FeedSettings['google_url'])?'display: none;':''); ?>" id="google_url_override" name="Feed[google_url]" value="<?php echo esc_attr($FeedSettings['google_url']); ?>" maxlength="255"  />
	<label><input type="checkbox" name="NULL[google_url_toggle]" id="google_url_toggle"  value="1" <?php echo ( empty($FeedSettings['google_url'])?'':'checked'); ?> /> <?php echo __('Modify', 'powerpress'); ?></label>
	<p><?php echo __('Google Podcasts directory is available through Google search, Google Home smart speakers, and the new Google Podcasts app for Android. As long as your podcast website is discoverable by Google search, your podcast will be included in this directory.', 'powerpress'); ?></p>
</td>
</tr>
</table>

<script>
jQuery( document ).ready(function() {
  // Handler for .ready() called.
  jQuery('#google_url_toggle').click( function(e) {
	if( this.checked )  {
		 jQuery('#google_url').hide();
		 jQuery('#google_url_override').show();
	} else {
		if( confirm('<?php echo esc_js( __('Reset, are you sure?', 'powerpres') ); ?>') ) {
			jQuery('#google_url_override').val('');
			 jQuery('#google_url_override').hide();
			 jQuery('#google_url').show();
		 } else {
			e.preventDefault();
		 }
	}
  });
});
</script>
<table class="form-table">
<tr valign="top">
<th scope="row"><?php echo __('Blubrry Podcast Directory', 'powerpress'); ?></th>
<td>
	<p><strong><a href="https://blubrry.com/addpodcast.php?feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('Submit podcast to Blubrry Podcast Directory', 'powerpress'); ?></a></strong></p>
	<p>
		<?php echo __('The largest podcast directory in the World!', 'powerpress'); ?>
	</p><p>
		<?php echo sprintf(__('Once listed, %s to expand your podcast distribution to Blubrry\'s SmartTV Apps (e.g. Roku) and apply to be on Spotify.', 'powerpress'), '<a href="https://create.blubrry.com/resources/blubrry-podcast-directory/" target="_blank">'. __('Get Featured', 'powerpress').'</a>' ); ?>
	</p>
	<label for="blubrry_url" style="font-size: 120%; display: block; font-weight: bold;"><?php echo __('Blubrry Listing URL', 'powerpress'); ?></label>
	<input type="text" class="bpp-input-normal" id="blubrry_url" name="Feed[blubrry_url]" value="<?php echo esc_attr($FeedSettings['blubrry_url']); ?>" maxlength="255" />
	<p class="description"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://blubrry.com/title_of_podcast/'); ?></p>
</td>
</tr>
</table>

<table class="form-table">
<tr valign="top">
<th scope="row"><?php echo __('Stitcher Podcast Radio', 'powerpress'); ?></th>
<td>
	<p><strong><a href="https://create.blubrry.com/manual/podcast-promotion/publish-podcast-stitcher/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('Submit podcast to Stitcher', 'powerpress'); ?></a></strong></p>
	<label for="stitcher_url" style="font-size: 120%; display: block; font-weight: bold;"><?php echo __('Stitcher Listing URL', 'powerpress'); ?></label>
	<input type="text" class="bpp-input-normal" id="stitcher_url" name="Feed[stitcher_url]" value="<?php echo esc_attr($FeedSettings['stitcher_url']); ?>" maxlength="255" />
	<p class="description"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'http://www.stitcher.com/podcast/your/listing-url/'); ?></p>
</td>
</tr>
</table>

<table class="form-table">
<tr valign="top">
<th scope="row"><?php echo __('TuneIn', 'powerpress'); ?></th>
<td>
	<p><strong><a href="https://create.blubrry.com/manual/podcast-promotion/publish-podcast-tunein/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('Submit podcast to TuneIn', 'powerpress'); ?></a></strong></p>
	<label for="tunein_url" style="font-size: 120%; display: block; font-weight: bold;"><?php echo __('TuneIn Listing URL', 'powerpress'); ?></label>
	<input type="text" class="bpp-input-normal" id="tunein_url" name="Feed[tunein_url]" value="<?php echo esc_attr($FeedSettings['tunein_url']); ?>" maxlength="255" />
	<p class="description"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'http://tunein.com/radio/your-podcast-p000000/'); ?></p>
	
</td>
</tr>
</table>

<table class="form-table">
<tr valign="top">
<th scope="row"><?php echo __('Spotify', 'powerpress'); ?> <?php echo powerpressadmin_new(); ?></th>
<td>
	<p><strong><a href="https://create.blubrry.com/manual/podcast-promotion/submit-podcast-to-spotify/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('Submit podcast to Spotify', 'powerpress'); ?></a></strong></p>
	<label for="spotify_url" style="font-size: 120%; display: block; font-weight: bold;"><?php echo __('Spotify Listing URL', 'powerpress'); ?></label>
	<input type="text" class="bpp-input-normal" id="spotify_url" name="Feed[spotify_url]" value="<?php echo esc_attr($FeedSettings['spotify_url']); ?>" maxlength="255" />
	<p class="description"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://open.spotify.com/show/abcdefghijklmnopqrstu'); ?></p>
	
</td>
</tr>
<tr valign="top">
    <th scope="row"><?php echo __('iHeartRadio', 'powerpress'); ?> <?php echo powerpressadmin_new(); ?></th>
    <td>
        <p><strong><a href="https://create.blubrry.com/manual/podcast-promotion/submit-podcast-to-iheartradio/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('Submit podcast to iHeartRadio', 'powerpress'); ?></a></strong></p>
        <label for="iheart_url" style="font-size: 120%; display: block; font-weight: bold;"><?php echo __('iHeartRadio Listing URL', 'powerpress'); ?></label>
        <input type="text" class="bpp-input-normal" id="iheart_url" name="Feed[iheart_url]" value="<?php echo esc_attr($FeedSettings['iheart_url']); ?>" maxlength="255" />
        <p class="description"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://www.iheart.com/podcast/abcdefghijklmopqrstu/'); ?></p>

    </td>
</tr>
<tr valign="top">
    <th scope="row"><?php echo __('Deezer', 'powerpress'); ?> <?php echo powerpressadmin_new(); ?></th>
    <td>
        <p><strong><a href="https://powerpresspodcast.com/2019/08/07/blubrry-podcasts-coming-deezer/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('Submit podcast to Deezer', 'powerpress'); ?></a></strong></p>
        <label for="deezer_url" style="font-size: 120%; display: block; font-weight: bold;"><?php echo __('Deezer Listing URL', 'powerpress'); ?></label>
        <input type="text" class="bpp-input-normal" id="deezer_url" name="Feed[deezer_url]" value="<?php echo esc_attr($FeedSettings['deezer_url']); ?>" maxlength="255" />
        <p class="description"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://www.deezer.com/us/show/1234'); ?></p>

    </td>
</tr>
<tr valign="top">
    <th scope="row"><?php echo __('Pandora', 'powerpress'); ?> <?php echo powerpressadmin_new(); ?></th>
    <td>
        <p><strong><a href="https://create.blubrry.com/manual/podcast-promotion/submit-podcast-to-pandora/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('Submit podcast to Pandora', 'powerpress'); ?></a></strong></p>
        <label for="pandora_url" style="font-size: 120%; display: block; font-weight: bold;"><?php echo __('Pandora Listing URL', 'powerpress'); ?></label>
        <input type="text" class="bpp-input-normal" id="pandora_url" name="Feed[pandora_url]" value="<?php echo esc_attr($FeedSettings['pandora_url']); ?>" maxlength="255" />
<!--        Unknown: <p class="description">--><?php //echo sprintf(__('e.g. %s', 'powerpress'), 'https://www.iheart.com/podcast/abcdefghijklmopqrstu/'); ?><!--</p>-->

    </td>
</tr>
</table>

<br />