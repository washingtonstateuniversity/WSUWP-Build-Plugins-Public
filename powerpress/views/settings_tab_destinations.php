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
    if(empty($FeedSettings['amazon_url']) )
        $FeedSettings['amazon_url'] = '';
    if(empty($FeedSettings['pcindex_url']) )
        $FeedSettings['pcindex_url'] = '';
    if(empty($FeedSettings['jiosaavn_url']) )
        $FeedSettings['jiosaavn_url'] = '';
    if(empty($FeedSettings['podchaser_url']) )
        $FeedSettings['podchaser_url'] = '';
    if(empty($FeedSettings['gaana_url']) )
        $FeedSettings['gaana_url'] = '';

    $Settings['subscribe_feature_email'] = (isset($General['subscribe_feature_email']) ? $General['subscribe_feature_email'] : false );
    $Settings['subscribe_feature_apple'] = (isset($General['subscribe_feature_apple']) ? $General['subscribe_feature_apple'] : false );
    $Settings['subscribe_feature_gp'] = (isset($General['subscribe_feature_gp']) ? $General['subscribe_feature_gp'] : false );
    $Settings['subscribe_feature_stitcher'] = (isset($General['subscribe_feature_stitcher']) ? $General['subscribe_feature_stitcher'] : false );
    $Settings['subscribe_feature_tunein'] = (isset($General['subscribe_feature_tunein']) ? $General['subscribe_feature_tunein'] : false );
    $Settings['subscribe_feature_spotify'] = (isset($General['subscribe_feature_spotify']) ? $General['subscribe_feature_spotify'] : false );
    $Settings['subscribe_feature_iheart'] = (isset($General['subscribe_feature_iheart']) ? $General['subscribe_feature_iheart'] : false );
    $Settings['subscribe_feature_deezer'] = (isset($General['subscribe_feature_deezer']) ? $General['subscribe_feature_deezer'] : false );
    $Settings['subscribe_feature_pandora'] = (isset($General['subscribe_feature_pandora']) ? $General['subscribe_feature_pandora'] : false );
    $Settings['subscribe_feature_android'] = (isset($General['subscribe_feature_android']) ? $General['subscribe_feature_android'] : false );
    $Settings['subscribe_feature_blubrry'] = (isset($General['subscribe_feature_blubrry']) ? $General['subscribe_feature_blubrry'] : false );
    $Settings['subscribe_feature_amazon'] = (isset($General['subscribe_feature_amazon']) ? $General['subscribe_feature_amazon'] : false );
    $Settings['subscribe_feature_pcindex'] = (isset($General['subscribe_feature_pcindex']) ? $General['subscribe_feature_pcindex'] : false );
    $Settings['subscribe_feature_jiosaavn'] = (isset($General['subscribe_feature_jiosaavn']) ? $General['subscribe_feature_jiosaavn'] : false );
    $Settings['subscribe_feature_podchaser'] = (isset($General['subscribe_feature_podchaser']) ? $General['subscribe_feature_podchaser'] : false );
    $Settings['subscribe_feature_gaana'] = (isset($General['subscribe_feature_gaana']) ? $General['subscribe_feature_gaana'] : false );
    $Settings['subscribe_feature_email_shortcode'] = (isset($General['subscribe_feature_email_shortcode']) ? $General['subscribe_feature_email_shortcode'] : true );
    $Settings['subscribe_feature_apple_shortcode'] = (isset($General['subscribe_feature_apple_shortcode']) ? $General['subscribe_feature_apple_shortcode'] : true );
    $Settings['subscribe_feature_gp_shortcode'] = (isset($General['subscribe_feature_gp_shortcode']) ? $General['subscribe_feature_gp_shortcode'] : true );
    $Settings['subscribe_feature_stitcher_shortcode'] = (isset($General['subscribe_feature_stitcher_shortcode']) ? $General['subscribe_feature_stitcher_shortcode'] : true );
    $Settings['subscribe_feature_tunein_shortcode'] = (isset($General['subscribe_feature_tunein_shortcode']) ? $General['subscribe_feature_tunein_shortcode'] : true );
    $Settings['subscribe_feature_spotify_shortcode'] = (isset($General['subscribe_feature_spotify_shortcode']) ? $General['subscribe_feature_spotify_shortcode'] : true );
    $Settings['subscribe_feature_android_shortcode'] = (isset($General['subscribe_feature_android_shortcode']) ? $General['subscribe_feature_android_shortcode'] : true );
    $Settings['subscribe_feature_blubrry_shortcode'] = (isset($General['subscribe_feature_blubrry_shortcode']) ? $General['subscribe_feature_blubrry_shortcode'] : true );
    $Settings['subscribe_feature_iheart_shortcode'] = (isset($General['subscribe_feature_iheart_shortcode']) ? $General['subscribe_feature_iheart_shortcode'] : true );
    $Settings['subscribe_feature_deezer_shortcode'] = (isset($General['subscribe_feature_deezer_shortcode']) ? $General['subscribe_feature_deezer_shortcode'] : true );
    $Settings['subscribe_feature_pandora_shortcode'] = (isset($General['subscribe_feature_pandora_shortcode']) ? $General['subscribe_feature_pandora_shortcode'] : true );
    $Settings['subscribe_feature_amazon_shortcode'] = (isset($General['subscribe_feature_amazon_shortcode']) ? $General['subscribe_feature_amazon_shortcode'] : true );
    $Settings['subscribe_feature_pcindex_shortcode'] = (isset($General['subscribe_feature_pcindex_shortcode']) ? $General['subscribe_feature_pcindex_shortcode'] : true );
    $Settings['subscribe_feature_jiosaavn_shortcode'] = (isset($General['subscribe_feature_jiosaavn_shortcode']) ? $General['subscribe_feature_jiosaavn_shortcode'] : false );
    $Settings['subscribe_feature_podchaser_shortcode'] = (isset($General['subscribe_feature_podchaser_shortcode']) ? $General['subscribe_feature_podchaser_shortcode'] : false );
    $Settings['subscribe_feature_gaana_shortcode'] = (isset($General['subscribe_feature_gaana_shortcode']) ? $General['subscribe_feature_gaana_shortcode'] : false );
    $Settings['subscribe_feature_email_sidebar'] = (isset($General['subscribe_feature_email_sidebar']) ? $General['subscribe_feature_email_sidebar'] : true );
    $Settings['subscribe_feature_apple_sidebar'] = (isset($General['subscribe_feature_apple_sidebar']) ? $General['subscribe_feature_apple_sidebar'] : true );
    $Settings['subscribe_feature_gp_sidebar'] = (isset($General['subscribe_feature_gp_sidebar']) ? $General['subscribe_feature_gp_sidebar'] : true );
    $Settings['subscribe_feature_stitcher_sidebar'] = (isset($General['subscribe_feature_stitcher_sidebar']) ? $General['subscribe_feature_stitcher_sidebar'] : false );
    $Settings['subscribe_feature_tunein_sidebar'] = (isset($General['subscribe_feature_tunein_sidebar']) ? $General['subscribe_feature_tunein_sidebar'] : false );
    $Settings['subscribe_feature_spotify_sidebar'] = (isset($General['subscribe_feature_spotify_sidebar']) ? $General['subscribe_feature_spotify_sidebar'] : false );
    $Settings['subscribe_feature_iheart_sidebar'] = (isset($General['subscribe_feature_iheart_sidebar']) ? $General['subscribe_feature_iheart_sidebar'] : false );
    $Settings['subscribe_feature_deezer_sidebar'] = (isset($General['subscribe_feature_deezer_sidebar']) ? $General['subscribe_feature_deezer_sidebar'] : false );
    $Settings['subscribe_feature_pandora_sidebar'] = (isset($General['subscribe_feature_pandora_sidebar']) ? $General['subscribe_feature_pandora_sidebar'] : false );
    $Settings['subscribe_feature_android_sidebar'] = (isset($General['subscribe_feature_android_sidebar']) ? $General['subscribe_feature_android_sidebar'] : true );
    $Settings['subscribe_feature_blubrry_sidebar'] = (isset($General['subscribe_feature_blubrry_sidebar']) ? $General['subscribe_feature_blubrry_sidebar'] : false );
    $Settings['subscribe_feature_amazon_sidebar'] = (isset($General['subscribe_feature_amazon_sidebar']) ? $General['subscribe_feature_amazon_sidebar'] : false );
    $Settings['subscribe_feature_pcindex_sidebar'] = (isset($General['subscribe_feature_pcindex_sidebar']) ? $General['subscribe_feature_pcindex_sidebar'] : false );
    $Settings['subscribe_feature_jiosaavn_sidebar'] = (isset($General['subscribe_feature_jiosaavn_sidebar']) ? $General['subscribe_feature_jiosaavn_sidebar'] : false );
    $Settings['subscribe_feature_podchaser_sidebar'] = (isset($General['subscribe_feature_podchaser_sidebar']) ? $General['subscribe_feature_podchaser_sidebar'] : false );
    $Settings['subscribe_feature_gaana_sidebar'] = (isset($General['subscribe_feature_gaana_sidebar']) ? $General['subscribe_feature_gaana_sidebar'] : false );


function subscribeSetting($directory, $feed_url, $listing_url) {
        $style = " style=\"height: 32px;\" ";
	    $link_at_top = true;
	    $id_tail = "-subsection";
	    $class = " class='pp-heading'";
	    $android_url = "";
	    $email_url = "";
        if( preg_match('/^(https?:\/\/)(.*)$/i', $feed_url, $matches ) ) {
            $android_url =  $matches[1] . 'subscribeonandroid.com/' . $matches[2];
            $email_url =  $matches[1] . 'subscribebyemail.com/' . $matches[2];
        }

	    switch ($directory) {
            case 'apple': ?>

                    <h2 class="pp-heading"><span id="apple-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Apple Podcast', 'powerpress'); ?></span></h2>
                    <?php if ($link_at_top) { ?>
                        <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-itunes/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo __('How to submit a podcast to Apple', 'powerpress'); ?></a></b></p>
                    <?php } ?>
                    <p class="pp-settings-text"><?php echo __('Follow the steps to submit your podcast to Apple then come back here and enter the Subscription URL. Apple will email your Subscription URL to your Apple Email when your podcast is accepted into the Apple Podcasts Directory.', 'powerpress'); ?></p>
                    <?php if (!$link_at_top) { ?>
                    <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-itunes/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo __('How to submit a podcast to Apple', 'powerpress'); ?></a></b></p>
                    <?php } ?>
                    <input class="pp-settings-text-input-less-wide" type="text" id="itunes_url<?php echo $id_tail; ?>" name="Feed[itunes_url]" placeholder="<?php echo __('Apple Subscription URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                    <label for="itunes_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'http://itunes.apple.com/podcast/title-of-podcast/id000000000'); ?></label>

                <?php
                break;
            case 'google':
                $googleUrl =  'https://www.google.com/podcasts?feed='.powerpress_base64_encode($feed_url);?>

                    <h2 class="pp-heading"><span id="google-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Google', 'powerpress'); ?></span></h2>
                    <?php if ($link_at_top) { ?>
                    <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-google-podcasts/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Google', 'powerpress'); ?></a></b></p>
                    <?php } ?>
                    <p class="pp-settings-text"><?php echo __('Google Podcasts directory is available through Google search, Google Home smart speakers, and the new Google Podcasts app for Android. As long as your podcast website is discoverable by Google search, your podcast will be included in this directory.', 'powerpress'); ?></p>
                    <?php if (!$link_at_top) { ?>
                    <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-google-podcasts/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Google', 'powerpress'); ?></a></b></p>
                    <?php } ?>
                    <input class="pp-settings-text-input-less-wide" type="text" placeholder="<?php echo __('Google Listing URL', 'powerpress'); ?>" id="google_url_override<?php echo $id_tail; ?>" name="Feed[google_url]" value="<?php echo esc_attr($listing_url); ?>" maxlength="255"  />
                    <label for="google_url_override" class="pp-settings-label-under">e.g. <?php echo esc_attr($googleUrl); ?></label>


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
                <?php
                break;
            case 'stitcher': ?>

                    <h2 class="pp-heading"><span id="stitcher-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Stitcher', 'powerpress'); ?></span></h2>
                    <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/publish-podcast-stitcher/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Stitcher', 'powerpress'); ?></a></b></p>
                    <input class="pp-settings-text-input-less-wide" type="text" id="stitcher_url<?php echo $id_tail; ?>" name="Feed[stitcher_url]" placeholder="<?php echo __('Stitcher Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                    <label for="stitcher_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'http://www.stitcher.com/podcast/your/listing-url/'); ?></label>

                <?php
                break;
            case 'tunein': ?>

                    <h2 class="pp-heading"><span id="tunein-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Tunein', 'powerpress'); ?></span></h2>
                    <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/publish-podcast-tunein/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to TuneIn', 'powerpress'); ?></a></b></p>
                    <input class="pp-settings-text-input-less-wide" type="text" id="tunein_url<?php echo $id_tail; ?>" name="Feed[tunein_url]" placeholder="<?php echo __('TuneIn Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                    <label for="tunein_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'http://tunein.com/radio/your-podcast-p000000/'); ?></label>


                <?php
                break;
            case 'spotify': ?>

                    <h2 class="pp-heading"><span id="spotify-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Spotify', 'powerpress'); ?></span></h2>
                    <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-spotify/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Spotify', 'powerpress'); ?></a></b></p>
                    <input class="pp-settings-text-input-less-wide" type="text" id="spotify_url<?php echo $id_tail; ?>" name="Feed[spotify_url]" placeholder="<?php echo __('Spotify Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                    <label for="spotify_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://open.spotify.com/show/abcdefghijklmnopqrstu'); ?></label>


                <?php
                break;
            case 'blubrry': ?>

                    <h2<?php echo $class; ?>><img class="pp-directory-icon" <?php echo $style; ?>alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/blubrry.svg"><?php echo __('Blubrry Podcast Directory', 'powerpress'); ?></h2>
                    <?php if ($link_at_top) { ?>
                    <p class="pp-settings-text">
                        <b><a href="https://blubrry.com/addpodcast.php?feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Blubrry Directory', 'powerpress'); ?></a></b>
                    </p>
                    <?php }?>
                    <p class="pp-settings-text">
                        <b><?php echo __('Get listed on the largest podcast directory in the world! ', 'powerpress'); ?></b><?php echo sprintf(__('Once listed, %s to expand your podcast distribution to Blubrry\'s SmartTV Apps (e.g. Roku) and apply to be on Spotify.', 'powerpress'), '<a href="https://create.blubrry.com/resources/blubrry-podcast-directory/" target="_blank">'. __('Get Featured', 'powerpress').'</a>' ); ?>
                    </p>
                    <?php if(!$link_at_top) { ?>
                    <p class="pp-settings-text">
                        <b><a href="https://blubrry.com/addpodcast.php?feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Blubrry Directory', 'powerpress'); ?></a></b>
                    </p>
                    <?php } ?>
                    <input class="pp-settings-text-input-less-wide" type="text" id="blubrry_url<?php echo $id_tail; ?>" name="Feed[blubrry_url]" placeholder="<?php echo __('Blubrry Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                    <label for="blubrry_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://blubrry.com/title_of_podcast/'); ?></label>

                <?php
                break;
            case 'android': ?>
                <h2 class="pp-heading"><span id="android-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Android', 'powerpress'); ?></span></h2>
                <input class="pp-settings-text-input-less-wide" type="text" id="android_url_<?php echo $id_tail; ?>" name="null[android_url]" placeholder="<?php echo __('Subscribe by Android not available', 'powerpress'); ?>" value="<?php echo esc_attr($android_url); ?>" maxlength="255" readonly />
                <?php
                break;
            case 'email': ?>
                <h2 class="pp-heading"><span id="email-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Email', 'powerpress'); ?></span></h2>
                <input class="pp-settings-text-input-less-wide" type="text" id="email_url_<?php echo $id_tail; ?>" name="null[iheart_url]" placeholder="<?php echo __('Susbcribe on Email not available', 'powerpress'); ?>" value="<?php echo esc_attr($email_url); ?>" maxlength="255" readonly />
                <?php
                break;
            case 'iheart': ?>

                <h2 class="pp-heading"><span id="iheart-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('iHeartRadio', 'powerpress'); ?></span></h2>
                <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-iheartradio/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to iHeartRadio', 'powerpress'); ?></a></b></p>
                <input class="pp-settings-text-input-less-wide" type="text" id="iheart_url<?php echo $id_tail; ?>" name="Feed[iheart_url]" placeholder="<?php echo __('iHeartRadio Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                <label for="iheart_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://www.iheart.com/podcast/abcdefghijklmopqrstu/'); ?></label>

                <?php
                break;
            case 'deezer': ?>

                <h2 class="pp-heading"><span id="deezer-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Deezer', 'powerpress'); ?></span></h2>
                <p class="pp-settings-text"><b><a href="https://blubrry.com/podcast-insider/2019/08/07/blubrry-podcasts-coming-deezer/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Deezer', 'powerpress'); ?></a></b></p>
                <input class="pp-settings-text-input-less-wide" type="text" id="deezer_url<?php echo $id_tail; ?>" name="Feed[deezer_url]" placeholder="<?php echo __('Deezer Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                <label for="deezer_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://www.deezer.com/us/show/1234'); ?></label>

                <?php
                break;
            case 'pandora': ?>

                <h2 class="pp-heading"><span id="pandora-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Pandora', 'powerpress'); ?></span></h2>
                <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-pandora/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Pandora', 'powerpress'); ?></a></b></p>
                <input class="pp-settings-text-input-less-wide" type="text" id="pandora_url<?php echo $id_tail; ?>" name="Feed[pandora_url]" placeholder="<?php echo __('Pandora Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                <label for="pandora_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://www.pandora.com/us/show/1234'); ?></label>

                <?php
                break;
            case 'amazon': ?>

                <h2 class="pp-heading"><span id="amazon-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Amazon Music', 'powerpress'); ?></span></h2>
                <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-amazon-music-podcasts/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Amazon Music', 'powerpress'); ?></a></b></p>
                <input class="pp-settings-text-input-less-wide" type="text" id="amazon_url<?php echo $id_tail; ?>" name="Feed[amazon_url]" placeholder="<?php echo __('Amazon Music Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                <label for="amazon_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://music.amazon.com/podcasts/x999xx99-9x99-9x99-x999-xx9xx999xxxx/Example-Podcast'); ?></label>

                <?php
                break;
            case 'pcindex': ?>

                <h2 class="pp-heading"><span id="pcindex-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Podcast Index', 'powerpress'); ?></span></h2>
                <p class="pp-settings-text"><?php echo __('Podcast Index is an independent podcast directory, free, available to anyone. Striving to protect podcasting as a platform for free speech.','powerpress'); ?></p>
                <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-podcast-index/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Podcast Index', 'powerpress'); ?></a></b></p>
                <input class="pp-settings-text-input-less-wide" type="text" id="pcindex_url<?php echo $id_tail; ?>" name="Feed[pcindex_url]" placeholder="<?php echo __('Podcast Index Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                <label for="pcindex_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://podcastindex.org/podcast/xxxxxxx'); ?></label>

                <?php
                break;
            case 'jiosaavn': ?>

                <h2 class="pp-heading"><span id="jiosaavn-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('JioSaavn', 'powerpress'); ?></span></h2>
                <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-jiosaavn/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to JioSaavn', 'powerpress'); ?></a></b></p>
                <input class="pp-settings-text-input-less-wide" type="text" id="jiosaavn_url<?php echo $id_tail; ?>" name="Feed[jiosaavn_url]" placeholder="<?php echo __('JioSaavn Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                <label for="jiosaavn_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://www.jiosaavn.com/shows/xxxxxxxx'); ?></label>

                <?php
                break;
            case 'podchaser': ?>

                <h2 class="pp-heading"><span id="podchaser-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Podchaser', 'powerpress'); ?></span></h2>
                <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-podchaser/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Podchaser', 'powerpress'); ?></a></b></p>
                <input class="pp-settings-text-input-less-wide" type="text" id="podchaser_url<?php echo $id_tail; ?>" name="Feed[podchaser_url]" placeholder="<?php echo __('Podchaser Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                <label for="podchaser_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://podchaser.com/podcasts/xxxxxxx-123456'); ?></label>

                <?php
                break;
            case 'gaana': ?>

                <h2 class="pp-heading"><span id="gaana-icon" class="destinations-side-icon"></span><span class="directory-summary-head"><?php echo __('Gaana', 'powerpress'); ?></span></h2>
                <p class="pp-settings-text"><b><a href="https://blubrry.com/manual/podcast-promotion/submit-podcast-to-gaana/?podcast-feed=<?php echo urlencode($feed_url); ?>" target="_blank"><?php echo  __('How to submit a podcast to Gaana', 'powerpress'); ?></a></b></p>
                <input class="pp-settings-text-input-less-wide" type="text" id="gaana_url<?php echo $id_tail; ?>" name="Feed[gaana_url]" placeholder="<?php echo __('Gaana Listing URL', 'powerpress'); ?>" value="<?php echo esc_attr($listing_url); ?>" maxlength="255" />
                <label for="gaana_url" class="pp-settings-label-under"><?php echo sprintf(__('e.g. %s', 'powerpress'), 'https://gaana.com/season/xxxxxxxx'); ?></label>

                <?php
                break;
            case 'default':
                break;
        }
    }
?>

<div class="pp-sidenav">
    <div class="pp-sidenav-extra"><p class="pp-sidenav-extra-text"><b><?php echo htmlspecialchars(__('DESTINATIONS SETTINGS', 'powerpress')); ?></b></p></div>
    <button id="destinations-default-open" class="pp-sidenav-tablinks active" onclick="sideNav(event, 'destinations-all')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/rss-symbol.svg" style="margin-left: 7px; margin-right: 20px;width: 22px;"><?php echo htmlspecialchars(__('Basic Info', 'powerpress')); ?></button>
    <button class="pp-sidenav-tablinks" id="destinations-apple-tab" onclick="sideNav(event, 'destinations-apple')"><span id="apple-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Apple', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-google-tab" onclick="sideNav(event, 'destinations-google')"><span id="google-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Google', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-spotify-tab" onclick="sideNav(event, 'destinations-spotify')"><span id="spotify-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Spotify', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-amazon-tab" onclick="sideNav(event, 'destinations-amazon')"><span id="amazon-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Amazon Music', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-android-tab" onclick="sideNav(event, 'destinations-android')"><span id="android-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Android', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-pandora-tab" onclick="sideNav(event, 'destinations-pandora')"><span id="pandora-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Pandora', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-iheart-tab" onclick="sideNav(event, 'destinations-iheart')"><span id="iheart-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('iHeartRadio', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-stitcher-tab" onclick="sideNav(event, 'destinations-stitcher')"><span id="stitcher-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Stitcher', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-blubrry-tab" onclick="sideNav(event, 'destinations-blubrry')"><img class="pp-nav-icon" alt="" src="<?php echo powerpress_get_root_url(); ?>images/settings_nav_icons/blubrry.svg" style="margin-left: 7px; margin-right: 20px;"><?php echo htmlspecialchars(__('Blubrry Directory', 'powerpress')); ?></button>
    <button class="pp-sidenav-tablinks" id="destinations-jiosaavn-tab" onclick="sideNav(event, 'destinations-jiosaavn')"><span id="jiosaavn-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('JioSaavn', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-podchaser-tab" onclick="sideNav(event, 'destinations-podchaser')"><span id="podchaser-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Podchaser', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-gaana-tab" onclick="sideNav(event, 'destinations-gaana')"><span id="gaana-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Gaana', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-pcindex-tab" onclick="sideNav(event, 'destinations-pcindex')"><span id="pcindex-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Podcast Index', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-email-tab" onclick="sideNav(event, 'destinations-email')"><span id="email-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Email', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-tunein-tab" onclick="sideNav(event, 'destinations-tunein')"><span id="tunein-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('TuneIn', 'powerpress')); ?></span></button>
    <button class="pp-sidenav-tablinks" id="destinations-deezer-tab" onclick="sideNav(event, 'destinations-deezer')"><span id="deezer-icon-side" class="destinations-side-icon"></span><span class="destination-side-text"><?php echo htmlspecialchars(__('Deezer', 'powerpress')); ?></span></button>
    <?php
    powerpressadmin_edit_blubrry_services($General);
    ?>
    <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/support/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('POWERPRESS DOCUMENTATION', 'powerpress')); ?></a></div>
    <div class="pp-sidenav-extra"><a href="https://www.blubrry.com/podcast-insider/" class="pp-sidenav-extra-text"><?php echo htmlspecialchars(__('PODCAST INSIDER BLOG', 'powerpress')); ?></a></div>
</div>

<div id="destinations-all" class="pp-sidenav-tab active">

    <h1 class="pp-heading"><?php echo __('Destinations', 'powerpress'); ?></h1>


    <div>
        <p style="line-height: 36px;" class="pp-settings-text"><?php echo __('Your podcast RSS feed: ', 'powerpress'); ?>
            <a href="<?php echo esc_attr($feed_url); ?>"> <?php echo esc_attr($feed_url); ?> </a>
            <br />
            <?php echo __('Use this URL to submit your podcast to various directories.', 'powerpress'); ?>
            <br />
            <?php echo __('Directory listing URLs are used by player subscribe links, subscribe sidebar widgets, and subscribe to podcast page shortcodes.', 'powerpress'); ?>
        </p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-apple" class="pp-sidenav-tab">
    <?php subscribeSetting('apple', $feed_url, $FeedSettings['itunes_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_apple_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_apple_sidebar" name="General[subscribe_feature_apple_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_apple_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_apple_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_apple_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_apple_shortcode" name="General[subscribe_feature_apple_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_apple_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_apple_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_apple]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_apple" name="General[subscribe_feature_apple]" value="1" <?php if( !empty($Settings['subscribe_feature_apple']) ) echo 'checked '; ?>/> <label for="subscribe_feature_apple"><?php echo __('Show link under player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-google" class="pp-sidenav-tab">
    <?php subscribeSetting('google', $feed_url, $FeedSettings['google_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_gp_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_gp_sidebar" name="General[subscribe_feature_gp_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_gp_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_gp_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_gp_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_gp_shortcode" name="General[subscribe_feature_gp_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_gp_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_gp_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_gp]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_gp" name="General[subscribe_feature_gp]" value="1" <?php if( !empty($Settings['subscribe_feature_gp']) ) echo 'checked '; ?>/> <label for="subscribe_feature_gp"><?php echo __('Show link under player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-spotify" class="pp-sidenav-tab">
    <?php subscribeSetting('spotify', $feed_url, $FeedSettings['spotify_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_spotify_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_spotify_sidebar" name="General[subscribe_feature_spotify_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_spotify_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_spotify_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_spotify_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_spotify_shortcode" name="General[subscribe_feature_spotify_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_spotify_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_spotify_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_spotify]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_spotify" name="General[subscribe_feature_spotify]" value="1" <?php if( !empty($Settings['subscribe_feature_spotify']) ) echo 'checked '; ?>/> <label for="subscribe_feature_spotify"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-amazon" class="pp-sidenav-tab">
    <?php subscribeSetting('amazon', $feed_url, $FeedSettings['amazon_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_amazon_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_amazon_sidebar" name="General[subscribe_feature_amazon_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_amazon_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_amazon_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_amazon_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_amazon_shortcode" name="General[subscribe_feature_amazon_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_amazon_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_amazon_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_amazon]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_amazon" name="General[subscribe_feature_amazon]" value="1" <?php if( !empty($Settings['subscribe_feature_amazon']) ) echo 'checked '; ?>/> <label for="subscribe_feature_amazon"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-android" class="pp-sidenav-tab">
    <?php subscribeSetting('android', $feed_url, ''); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_android_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_android_sidebar" name="General[subscribe_feature_android_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_android_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_android_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_android_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_android_shortcode" name="General[subscribe_feature_android_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_android_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_android_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_android]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_android" name="General[subscribe_feature_android]" value="1" <?php if( !empty($Settings['subscribe_feature_android']) ) echo 'checked '; ?>/> <label for="subscribe_feature_android"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-pandora" class="pp-sidenav-tab">
    <?php subscribeSetting('pandora', $feed_url, $FeedSettings['pandora_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_pandora_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_pandora_sidebar" name="General[subscribe_feature_pandora_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_pandora_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_pandora_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_pandora_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_pandora_shortcode" name="General[subscribe_feature_pandora_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_pandora_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_pandora_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_pandora]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_pandora" name="General[subscribe_feature_pandora]" value="1" <?php if( !empty($Settings['subscribe_feature_pandora']) ) echo 'checked '; ?>/> <label for="subscribe_feature_pandora"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-iheart" class="pp-sidenav-tab">
    <?php subscribeSetting('iheart', $feed_url, $FeedSettings['iheart_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_iheart_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_iheart_sidebar" name="General[subscribe_feature_iheart_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_iheart_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_iheart_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_iheart_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_iheart_shortcode" name="General[subscribe_feature_iheart_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_iheart_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_iheart_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_iheart]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_iheart" name="General[subscribe_feature_iheart]" value="1" <?php if( !empty($Settings['subscribe_feature_iheart']) ) echo 'checked '; ?>/> <label for="subscribe_feature_iheart"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-stitcher" class="pp-sidenav-tab">
    <?php subscribeSetting('stitcher', $feed_url, $FeedSettings['stitcher_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_stitcher_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_stitcher_sidebar" name="General[subscribe_feature_stitcher_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_stitcher_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_stitcher_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_stitcher_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_stitcher_shortcode" name="General[subscribe_feature_stitcher_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_stitcher_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_stitcher_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_stitcher]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_stitcher" name="General[subscribe_feature_stitcher]" value="1" <?php if( !empty($Settings['subscribe_feature_stitcher']) ) echo 'checked '; ?>/> <label for="subscribe_feature_stitcher"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-blubrry" class="pp-sidenav-tab">
    <?php subscribeSetting('blubrry', $feed_url, $FeedSettings['blubrry_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_blubrry_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_blubrry_sidebar" name="General[subscribe_feature_blubrry_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_blubrry_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_blubrry_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_blubrry_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_blubrry_shortcode" name="General[subscribe_feature_blubrry_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_blubrry_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_blubrry_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_blubrry]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_blubrry" name="General[subscribe_feature_blubrry]" value="1" <?php if( !empty($Settings['subscribe_feature_blubrry']) ) echo 'checked '; ?>/> <label for="subscribe_feature_blubrry"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-jiosaavn" class="pp-sidenav-tab">
    <?php subscribeSetting('jiosaavn', $feed_url, $FeedSettings['jiosaavn_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_jiosaavn_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_jiosaavn_sidebar" name="General[subscribe_feature_jiosaavn_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_jiosaavn_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_jiosaavn_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_jiosaavn_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_jiosaavn_shortcode" name="General[subscribe_feature_jiosaavn_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_jiosaavn_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_jiosaavn_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_jiosaavn]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_jiosaavn" name="General[subscribe_feature_jiosaavn]" value="1" <?php if( !empty($Settings['subscribe_feature_jiosaavn']) ) echo 'checked '; ?>/> <label for="subscribe_feature_jiosaavn"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-podchaser" class="pp-sidenav-tab">
    <?php subscribeSetting('podchaser', $feed_url, $FeedSettings['podchaser_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_podchaser_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_podchaser_sidebar" name="General[subscribe_feature_podchaser_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_podchaser_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_podchaser_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_podchaser_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_podchaser_shortcode" name="General[subscribe_feature_podchaser_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_podchaser_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_podchaser_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_podchaser]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_podchaser" name="General[subscribe_feature_podchaser]" value="1" <?php if( !empty($Settings['subscribe_feature_podchaser']) ) echo 'checked '; ?>/> <label for="subscribe_feature_podchaser"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-gaana" class="pp-sidenav-tab">
    <?php subscribeSetting('gaana', $feed_url, $FeedSettings['gaana_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_gaana_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_gaana_sidebar" name="General[subscribe_feature_gaana_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_gaana_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_gaana_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_gaana_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_gaana_shortcode" name="General[subscribe_feature_gaana_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_gaana_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_gaana_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_gaana]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_gaana" name="General[subscribe_feature_gaana]" value="1" <?php if( !empty($Settings['subscribe_feature_gaana']) ) echo 'checked '; ?>/> <label for="subscribe_feature_gaana"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-pcindex" class="pp-sidenav-tab">
    <?php subscribeSetting('pcindex', $feed_url, $FeedSettings['pcindex_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_pcindex_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_pcindex_sidebar" name="General[subscribe_feature_pcindex_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_pcindex_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_pcindex_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_pcindex_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_pcindex_shortcode" name="General[subscribe_feature_pcindex_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_pcindex_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_pcindex_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_pcindex]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_pcindex" name="General[subscribe_feature_pcindex]" value="1" <?php if( !empty($Settings['subscribe_feature_pcindex']) ) echo 'checked '; ?>/> <label for="subscribe_feature_pcindex"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>


<div id="destinations-email" class="pp-sidenav-tab">
    <?php subscribeSetting('email', $feed_url, ''); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_email_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_email_sidebar" name="General[subscribe_feature_email_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_email_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_email_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_email_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_email_shortcode" name="General[subscribe_feature_email_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_email_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_email_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_email]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_email" name="General[subscribe_feature_email]" value="1" <?php if( !empty($Settings['subscribe_feature_email']) ) echo 'checked '; ?>/> <label for="subscribe_feature_email"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-tunein" class="pp-sidenav-tab">
    <?php subscribeSetting('tunein', $feed_url, $FeedSettings['tunein_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_tunein_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_tunein_sidebar" name="General[subscribe_feature_tunein_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_tunein_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_tunein_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_tunein_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_tunein_shortcode" name="General[subscribe_feature_tunein_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_tunein_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_tunein_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_tunein]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_tunein" name="General[subscribe_feature_tunein]" value="1" <?php if( !empty($Settings['subscribe_feature_tunein']) ) echo 'checked '; ?>/> <label for="subscribe_feature_tunein"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>

<div id="destinations-deezer" class="pp-sidenav-tab">
    <?php subscribeSetting('deezer', $feed_url, $FeedSettings['deezer_url']); ?>
    <div class="pp-show-subscribe">
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_deezer_sidebar]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_deezer_sidebar" name="General[subscribe_feature_deezer_sidebar]" value="1" <?php if( !empty($Settings['subscribe_feature_deezer_sidebar']) ) echo 'checked '; ?>/> <label for="subscribe_feature_deezer_sidebar"><?php echo __('Show link in subscribe sidebar', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_deezer_shortcode]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_deezer_shortcode" name="General[subscribe_feature_deezer_shortcode]" value="1" <?php if( !empty($Settings['subscribe_feature_deezer_shortcode']) ) echo 'checked '; ?>/> <label for="subscribe_feature_deezer_shortcode"><?php echo __('Show link on subscribe page', 'powerpress'); ?></label></p>
        <p class="pp-settings-text-smaller-margin"><input type="hidden" name="General[subscribe_feature_deezer]" value="0" /><input class="pp-settings-checkbox" type="checkbox" id="subscribe_feature_deezer" name="General[subscribe_feature_deezer]" value="1" <?php if( !empty($Settings['subscribe_feature_deezer']) ) echo 'checked '; ?>/> <label for="subscribe_feature_deezer"><?php echo __('Show link under media player', 'powerpress'); ?></label></p>
    </div>
    <?php powerpress_settings_tab_footer(); ?>
</div>
<br />