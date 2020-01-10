<?php
	// settings_tab_appearance.php
	
	if( $FeedSettings === false )
		$FeedSettings = powerpress_get_settings('powerpress_feed');
	if( empty($FeedSettings) )
		$FeedSettings = array();
	
	if( !isset($FeedSettings['subscribe_page_link_href']) )
		$FeedSettings['subscribe_page_link_href'] = '';
	if( !isset($FeedSettings['subscribe_page_link_id']) )
		$FeedSettings['subscribe_page_link_id'] = '';
	if( !isset($FeedSettings['subscribe_page_link_text']) )
		$FeedSettings['subscribe_page_link_text'] = '';
		
	$feed_slug = '';
	if( !empty($FeedAttribs['feed_slug']) )
		$feed_slug = $FeedAttribs['feed_slug'];
	
	if( !empty($feed_slug) ) {
?>
<h3><?php echo __('Website Settings', 'powerpress'); ?></h3>
<table class="form-table">
<tr valign="top">
<th scope="row">
<?php echo __('Disable Player', 'powerpress'); ?>
</th>
<td>
	<input type="hidden" name="DisablePlayerFor" value="" />
	<label><input name="DisablePlayerFor" type="checkbox" <?php if( isset($General['disable_player'][$feed_slug]) ) echo 'checked '; ?> value="1" /> <?php echo __('Do not display web player or links for this podcast.', 'powerpress'); ?></label>
	<input type="hidden" name="UpdateDisablePlayer" value="<?php echo $feed_slug; ?>" />
</td>
</tr>
</table>
<?php
	}// end $feed_slug
	
	
	// $GeneralSettings = powerpress_get_settings('powerpress_general');
	
		
	if( !empty($FeedAttribs['feed_slug']) && $FeedAttribs['type'] == 'ttid' )
	{
		return;
	}
	
	
?>
<table class="form-table">
<tr valign="top">
<th scope="row">
<?php echo __('Subscribe Page', 'powerpress'); ?></th> 
<td>
	<p><?php echo __('Add a link to a page to explain to your audience how to subscribe to your podcast.', 'powerpress'); ?></p>
	<p><?php echo __('The following link will be added to the Subscribe on iTunes and Subscribe via RSS links below the player.', 'powerpress'); ?></p>
	<ul>
	<li>
	<label for="subscribe_page_link_id"><?php echo __('Subscribe Page:', 'powerpress'); ?> 
	<?php wp_dropdown_pages( array( 'id'=>'subscribe_page_link_id', 'name' => 'Feed[subscribe_page_link_id]', 'echo' => 1, 'show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '', 'selected' => $FeedSettings['subscribe_page_link_id'] ) ); ?>
	<div id="subscribe_page_link_or" style="<?php echo ( !empty($FeedSettings['subscribe_page_link_id']) ?'display:none;':''); ?>">
		<div><?php echo __(' - or - ', 'powerpress'); ?></div>
		<label for="subscribe_page_link_href"><?php echo __('Subscribe URL:', 'powerpress'); ?> <input type="text" id="subscribe_page_link_href" value="<?php echo esc_attr($FeedSettings['subscribe_page_link_href']); ?>" name="Feed[subscribe_page_link_href]" placeholder="" style="width:60%;"<?php echo (empty($FeedSettings['subscribe_page_link_id'])?'':' disabled'); ?> /></label>
		<p><?php echo __('(If subscribe page is not hosted on this site)', 'powerpress'); ?></p> 
	</div><!-- end subscribe_page_link_or -->
	
<?php
		if( empty($FeedAttribs) && empty($FeedSettings['subscribe_page_link_href']) && empty($FeedSettings['subscribe_page_link_id']) )
		{
?>
	<h3><a href="#" id="powerpress_create_subscribe_page"><?php echo __('Create a subscribe page from Template', 'powerpress'); ?></a></h3> 
	<p><?php echo __('Creates a page from a template with the [powerpress_subscribe] shortcode. We encourage you to edit this page in your own words. Depending on your SEO strategy, you may want to configure the meta robots content to noindex.', 'powerpress'); ?>
	</p>
<?php
		}
?>
	<p><a href="https://create.blubrry.com/resources/powerpress/advanced-tools-and-options/subscribe-page/" target="_blank"><?php echo __('Learn more about the PowerPress Subscribe Page', 'powerpress'); ?></a></p>
	<?php
	// TODO: use the $FeedAttribs to create a recommended shortcode for this particular channel, may be simple [powerpress_subscribe] or it may specify the category, taxonomy, and/or feed_slug/post tpe podcasting
	?>
	</li>
	<li><label for="subscribe_page_link_text"><?php echo __('Subscribe Page Link Label:', 'powerpress'); ?><br /><input type="text" id="subscribe_page_link_text" value="<?php echo esc_attr($FeedSettings['subscribe_page_link_text']); ?>" name="Feed[subscribe_page_link_text]" placeholder="" style="width:60%;" /></label>
	<?php echo __('(leave blank for default)', 'powerpress'); ?>
	<p><?php echo __('Default: More Subscribe Options', 'powerpress'); ?></p>
	</li>
	</ul>
</td>
</tr>
<tr valign="top">
    <th scope="row">
        <?php echo __('Subscribe Widget', 'powerpress'); ?></th>
    <td>
        <p><?php echo __('Select the appearance of the subscribe buttons used on your subscribe page', 'powerpress'); ?></p>
        <ul>
			<?php
				// Set default based on when they installed PowerPress...
				if( empty($General['subscribe_widget_style']) ) { 
					
					if( empty($General['timnestamp']) || $General['timestamp'] > 1570366800 ) { // On or after Oct 8, 2019
						$General['subscribe_widget_style'] = 'modern'; // new
					} else {
						$General['subscribe_widget_style'] = 'classic'; // Before Oct 8, 2019
					}
				}
				if( empty($General['subscribe_widget_shape']) ) {
					$General['subscribe_widget_shape'] = 'squared'; // Default value
				}
			?>
            <li>
                <label for="subscribe_widget_style"><?php esc_html_e( 'Style:' , 'powerpress'); ?></label>
                <select id="subscribe_widget_style" name="General[subscribe_widget_style]">
                    <option value="modern" <?php echo selected($General['subscribe_widget_style'], 'modern'); ?> >Modern</option>
                    <option value="classic" <?php echo selected($General['subscribe_widget_style'], 'classic'); ?> >Classic</option>
                </select>
                <input type="radio" id="subscribe_widget_shape_squared"
                       name="General[subscribe_widget_shape]" value="squared" <?php echo $General['subscribe_widget_shape'] === 'squared' ? 'checked' : '' ?> />
                <label for="subscribe_widget_shape_squared"> <?php esc_html_e('Squared', 'powerpress'); ?></label>
                <input type="radio" id="subscribe_widget_shape_rounded"
                       name="General[subscribe_widget_shape]" value="rounded" <?php echo $General['subscribe_widget_shape'] === 'rounded' ? 'checked' : '' ?> />
                <label for="subscribe_widget_shape_rounded"> <?php esc_html_e('Rounded', 'powerpress'); ?></label>
             </li>
            <li>
                <iframe id="btnPreview" title="<?php echo esc_attr(__('Subscribe Button Preview', 'powerpress')); ?>" style="height: 127px;width: auto;" src="<?php echo admin_url('admin.php?action=powerpress-jquery-subscribe-preview&style='.$General['subscribe_widget_style'].'&shape='.$General['subscribe_widget_shape']) ?>"></iframe>
            </li>
			<?php
			if( empty($General['subscribe_no_important_styling']) )
				$General['subscribe_no_important_styling'] = 'include';
			
			?>
		<li>
                <select id="subscribe_no_important_styling" name="General[subscribe_no_important_styling]">
				<option value="include" <?php echo selected($General['subscribe_no_important_styling'], 'include') ?> ><?php esc_html_e( 'Include recommended styling (recommended)' , 'powerpress'); ?></option>
				<option value="exclude" <?php echo selected($General['subscribe_no_important_styling'], 'exclude')?> ><?php esc_html_e( 'Exclude recommended styling' , 'powerpress'); ?></option>
                </select>
                <label><?php esc_html_e( 'Select Exclude to remove !important and text align left CSS styling.' , 'powerpress'); ?></label>
            </li>
        </ul>
    </td>
</tr>
<?php
	// Display the shortcodes!
	$shortcode = array();
	$shortcode['powerpress'] = '[powerpress]';
	$shortcode['powerpress_playlist'] = '[powerpress_playlist]';
	$shortcode['powerpress_subscribe'] = '[powerpress_subscribe]';
	if( !empty($feed_slug) && $feed_slug != 'podcast' )
	{
		$shortcode['powerpress'] = '[powerpress channel="'.$feed_slug.'"]';
		$shortcode['powerpress_playlist'] = '[powerpress_playlist channel="'.$feed_slug.'"]';
		$shortcode['powerpress_subscribe'] = '[powerpress_subscribe channel="'.$feed_slug.'"]';
	}
	if( !empty($FeedAttribs['post_type']) )
	{
		$shortcode['powerpress'] = '[powerpress channel="'.$feed_slug.'" post_type="'.$FeedAttribs['post_type'].'"]';
		$shortcode['powerpress_playlist'] = '[powerpress_playlist channel="'.$feed_slug.'" post_type="'.$FeedAttribs['post_type'].'"]';
		$shortcode['powerpress_subscribe'] = '[powerpress_subscribe channel="'.$feed_slug.'" post_type="'.$FeedAttribs['post_type'].'"]';
	}
	if( !empty($FeedAttribs['category_id']) )
	{
		$shortcode['powerpress_playlist'] = '[powerpress_playlist category="'.$FeedAttribs['category_id'].'"]';
		$shortcode['powerpress_subscribe'] = '[powerpress_subscribe category="'.$FeedAttribs['category_id'].'"]';
	}
	if( !empty($FeedAttribs['term_taxonomy_id']) )
	{
		$shortcode['powerpress_playlist'] = '[powerpress_playlist term_taxonomy_id="'.$FeedAttribs['term_taxonomy_id'].'" post_type="post"]';
		$shortcode['powerpress_subscribe'] = '[powerpress_subscribe term_taxonomy_id="'.$FeedAttribs['term_taxonomy_id'].'"]';
	}
	
?>
<tr valign="top">
<th scope="row">
<?php echo __('PowerPress Shortcodes', 'powerpress'); ?></th>
<td>
<h3><?php echo __('PowerPress Player Shortcode', 'powerpress'); ?></h3>
<p>
<?php echo '<code>'.$shortcode['powerpress'].'</code>'; ?> 
</p>
<p>
<?php echo __('The Player shortcode is used to position your media presentation (player and download links) exactly where you want within your post or page content.', 'powerpress'); ?> 
</p>
<p>
<?php echo sprintf(__('Please visit the %s page for additional options.', 'powerpress'), '<a href="https://create.blubrry.com/resources/powerpress/advanced-tools-and-options/shortcode/" target="_blank">'. __('PowerPress Player Shortcode', 'powerpress') .'</a>' ); ?>
</p>
<p class="description">
<?php echo __('Note: When specifying a URL to media in the powerpress shortcode, only the player is included. The Media Links will <u>NOT</u> be included since there is not enough meta information to display them.', 'powerpress'); ?>
</p>
<h3><?php echo __('PowerPress Playlist Shortcode', 'powerpress'); ?></h3>
<?php if( empty($GeneralSettings['playlist_player']) ) { // Either not set or set on  
?>
<p style="margin-bottom: 20px; margin-left: 40px;">
	<input type="checkbox" name="General[playlist_player]" value="1" /> 
	<strong><?php echo __('Enable PowerPress Playlist Player', 'powerpress'); ?></strong>
</p>
<?php } ?>
<p>
<?php echo '<code>'.$shortcode['powerpress_playlist'].'</code>'; ?> 
</p>
<p>
<?php echo __('The Playlist shortcode is used to display a player with a playlist of your podcast episodes. It utilizes the default playlist built into WordPress.', 'powerpress'); ?> 
</p>
<p>
<?php echo sprintf(__('Please visit the %s page for additional options.', 'powerpress'), '<a href="https://create.blubrry.com/resources/powerpress/advanced-tools-and-options/powerpress-playlist-shortcode/" target="_blank">'. __('PowerPress Playlist Shortcode', 'powerpress') .'</a>' ); ?>
</p>

<h3><?php echo __('PowerPress Subscribe Shortcode', 'powerpress'); ?> </h3>
<p>
<?php echo '<code>'.$shortcode['powerpress_subscribe'].'</code>'; ?> 
</p>
<p>
<?php echo __('The Subscribe shortcode is used to display a subscribe to podcast widget for your podcast. It is intended for use on a custom subscribe page. See the Subscribe Page section below for more details.', 'powerpress'); ?> 
</p>
<p>
<?php echo sprintf(__('Please visit the %s page for additional options.', 'powerpress'), '<a href="https://create.blubrry.com/resources/powerpress/advanced-tools-and-options/powerpress-subscribe-shortcode/" target="_blank">'. __('PowerPress Subscribe Shortcode', 'powerpress') .'</a>' ); ?>
</p>



<h3><?php echo __('PowerPress Skip-to-Position in Player Shortcode', 'powerpress'); ?> <?php echo powerpressadmin_new(); ?></h3>
<p>
<?php echo '<code>[skipto time="TIME"]</code>'; ?> 
</p>
<p>
<?php echo __('The Skip-to-Position in Player shortcode creates a link in your page to skip to the specified position in the player. Time may be entered in seconds, minutes:seconds, or hours:minutes:seconds. A label may be specified by providing text followed by a closing shortcode tag. See examples below.', 'powerpress'); ?> 
</p>
<ul style="margin-left: 40px;">
<li>[skipto time="45"] &#8212;  <a href="#" onclick="return false;">0:45</a></li>
<li>[skipto time="45"]Skip intro[/skipto] &#8212; <a href="#" onclick="return false;">Skip intro</a></li>
<li>[skipto time="3:05"] <?php echo __('or', 'powerpress'); ?> [skipto time="185"] &#8212; <a href="#" onclick="return false;">3:05</a></li>
<li>[skipto time="1:23:45"] <?php echo __('or', 'powerpress'); ?> [skipto time="83:45"] <?php echo __('or', 'powerpress'); ?> [skipto time="5025"] &#8212; <a href="#" onclick="return false;">1:23:45</a></li>
</ul>
<p>
<?php echo sprintf(__('Please visit the %s page for additional options.', 'powerpress'), '<a href="https://create.blubrry.com/resources/powerpress/advanced-tools-and-options/skip-to-position-in-player/" target="_blank">'. __('PowerPress Skip-to-Position in Player Shortcode', 'powerpress') .'</a>' ); ?>
</p>

</td>
</tr>

</table>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', ()=> {
        let baserUrl = "<?php echo admin_url('admin.php?action=powerpress-jquery-subscribe-preview'); ?>";
        const previewIframe = document.querySelector('#btnPreview');
        const styleSelector = document.querySelector('#subscribe_widget_style');
        const squaredRadio = document.querySelector('#subscribe_widget_shape_squared');
        const roundedRadio = document.querySelector('#subscribe_widget_shape_rounded');
        let selectedShape = '';
        if (squaredRadio.checked) {
            selectedShape = 'squared';
        }
        function refreshIframe() {
            previewIframe.src = `${baserUrl}&style=${styleSelector.value}&shape=${selectedShape}`
        }

        styleSelector.addEventListener('change', refreshIframe);
        squaredRadio.addEventListener('change', () => {
            selectedShape = 'squared';
            refreshIframe();
        });
        roundedRadio.addEventListener('change', () => {
            selectedShape = '';
            refreshIframe();
        });
    });
</script>