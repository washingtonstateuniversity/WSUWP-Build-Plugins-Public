<?php

function episode_box_top($EnclosureURL, $FeedSlug, $ExtraData, $GeneralSettings, $EnclosureLength, $DurationHH, $DurationMM, $DurationSS) {

    if ($EnclosureURL) {
        $style1 = "display: none";
        $style2 = "display: block";
        $style3 = "display: inline-block";
        $style4 = "display: none";
        $style5 = "background-color: #f5f5f5;";
        $filename = basename(parse_url($EnclosureURL, PHP_URL_PATH));
    } else {
        $style1 = "display: inline-block";
        $style2 = "display: none";
        $style3 = "display: none";
        $style4 = "display: block";
        $style5 = "background-color: white;";
        $filename = "";
    }
    if (!$DurationHH) {
        $DurationHH = '00';
    }
    if (!$DurationMM) {
        $DurationMM = '00';
    }
    if (!$DurationSS) {
        $DurationSS = '00';
    }
    ?>
    <div id="a-pp-selected-media-<?php echo $FeedSlug; ?>" style="<?php echo $style5 ?>">
        <h3 id="pp-pp-selected-media-head-<?php echo $FeedSlug; ?>"
            style="<?php echo $style4; ?>"><?php echo __('Attach podcast media or enter URL', 'powerpress'); ?></h3>
        <div id="pp-selected-media-text-<?php echo $FeedSlug; ?>">
            <div id="media-input-<?php echo $FeedSlug; ?>" class="label-container">
                <div id="pp-url-input-container-<?php echo $FeedSlug; ?>" style="<?php echo $style1 ?>">
                    <label id="pp-url-input-above-<?php echo $FeedSlug; ?>" class="pp-url-input-label"
                           style="display: none;"
                           for="powerpress_url_<?php echo $FeedSlug; ?>"><?php echo __('File Media or URL'); ?></label>
                    <input type="text" id="powerpress_url_<?php echo $FeedSlug; ?>" title="<?php echo __('File Media or URL'); ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][url]"
                           value="<?php echo esc_attr($EnclosureURL); ?>" <?php echo(!empty($ExtraData['hosting']) ? 'readOnly' : ''); ?> />
                    <label id="pp-url-input-below-<?php echo $FeedSlug; ?>" class="pp-url-input-label"
                           style="display: none;"
                           for="powerpress_url_<?php echo $FeedSlug; ?>"><?php echo __('Update URL or attach new media file'); ?></label>
                </div>
                <div style="<?php echo $style3 ?>" class="ep-box-input"
                     id="powerpress_url_show_<?php echo $FeedSlug; ?>">
                    <p id="ep-box-filename-<?php echo $FeedSlug; ?>"><?php echo $filename ?></p>
                    <img id="powerpress_success_<?php echo $FeedSlug; ?>"
                         src="/wp-content/plugins/powerpress/images/check.svg"
                         style="height: 24px; margin-top: 14px; margin-right: 1em; vertical-align:text-top; float: right; display:none;"/>
                    <img id="powerpress_fail_<?php echo $FeedSlug; ?>"
                         src="/wp-content/plugins/powerpress/images/redx.svg"
                         style="height: 24px; margin-top: 14px; margin-right: 1em; vertical-align:text-top; float: right; display:none;"/>
                    <img id="powerpress_check_<?php echo $FeedSlug; ?>"
                         src="<?php echo admin_url(); ?>images/loading.gif"
                         style="height: 24px; margin-top: 14px; margin-right: 1em; vertical-align:text-top; float: right; display: none;"
                         alt="<?php echo __('Checking Media', 'powerpress'); ?>"/>
                </div>
            </div>
        </div>

        <div id="select-media-file-<?php echo $FeedSlug; ?>" style="<?php echo $style1 ?>">
            <a id="select-media-link-<?php echo $FeedSlug; ?>"
               href="<?php echo admin_url('admin.php'); ?>?action=powerpress-jquery-media&podcast-feed=<?php echo $FeedSlug; ?>&KeepThis=true&TB_iframe=true&modal=false"
               class="thickbox">
                <div class="pp-gray-button"
                     id="select-media-button-<?php echo $FeedSlug; ?>"><?php echo __('SELECT FILE', 'powerpress'); ?></div>
            </a>
            <div id="continue-to-episode-settings-<?php echo $FeedSlug; ?>" class="pp-blue-button"
                 onclick="continueToEpisodeSettings(this)"><?php echo __('CONTINUE', 'powerpress'); ?></div>
        </div>

        <div id="edit-media-file-<?php echo $FeedSlug; ?>" style="<?php echo $style3 ?>">
            <div id="pp-edit-media-button-<?php echo $FeedSlug; ?>" class="pp-gray-button"
                 onclick="changeMediaFile(this)"><?php echo __('CHANGE MEDIA', 'powerpress'); ?></div>
            <div id="verify-button-<?php echo $FeedSlug; ?>" class="pp-blue-button"
                 onclick="verifyMedia(this)"><?php echo __('VERIFY LINK', 'powerpress'); ?></div>
        </div>

        <div id="pp-change-media-file-<?php echo $FeedSlug; ?>" style="display: none;">
            <a id="pp-change-media-link-<?php echo $FeedSlug; ?>"
               href="<?php echo admin_url('admin.php'); ?>?action=powerpress-jquery-media&podcast-feed=<?php echo $FeedSlug; ?>&KeepThis=true&TB_iframe=true&modal=false"
               class="thickbox">
                <div class="pp-gray-button"
                     id="change-media-button-<?php echo $FeedSlug; ?>"><?php echo __('CHOOSE FILE', 'powerpress'); ?></div>
            </a>
            <div id="save-media-<?php echo $FeedSlug; ?>" class="pp-blue-button"
                 onclick="saveMediaFile(this)"><?php echo __('SAVE', 'powerpress'); ?></div>
        </div>
        <div id="pp-warning-messages">
            <div id="file-select-warning-<?php echo $FeedSlug; ?>"
                 style="background-color: white; box-shadow: none; margin-left: 0; padding-left: 3px; display:none; color: #dc3232;"><?php echo __('You must have a media file selected to continue to episode settings.', 'powerpress'); ?></div>
            <div id="file-change-warning-<?php echo $FeedSlug; ?>"
                 style="background-color: #f5f5f5; box-shadow: none; margin-left: 0; padding-left: 3px; display:none; color: #dc3232;"><?php echo __('You must have a media file selected to save.', 'powerpress'); ?></div>
            <div id="powerpress_warning_<?php echo $FeedSlug; ?>"
                 style="background-color: #f5f5f5; box-shadow: none; margin-left: 0; padding-left: 3px; display:none; color: #dc3232;"></div>
            <input type="hidden" id="powerpress_hosting_<?php echo $FeedSlug; ?>"
                   name="Powerpress[<?php echo $FeedSlug; ?>][hosting]"
                   value="<?php echo(!empty($ExtraData['hosting']) ? '1' : '0'); ?>"/>
            <div id="powerpress_hosting_note_<?php echo $FeedSlug; ?>"
                 style="margin-left: 2px; padding-bottom: 2px; padding-top: 2px; display: <?php echo(!empty($ExtraData['hosting']) ? 'block' : 'none'); ?>">
                <em><?php echo __('Media file hosted by blubrry.com.', 'powerpress'); ?>
                    (<a href="#" title="<?php echo __('Remove Blubrry.com hosted media file', 'powerpress'); ?>"
                        onclick="powerpress_remove_hosting('<?php echo $FeedSlug; ?>');return false;"><?php echo __('remove', 'powerpress'); ?></a>)
                </em></div>
            <input type="hidden" id="powerpress_program_keyword_<?php echo $FeedSlug; ?>"
                   name="Powerpress[<?php echo $FeedSlug; ?>][program_keyword]"
                   value="<?php echo !empty($ExtraData['program_keyword']) ? $ExtraData['program_keyword'] : ''; ?>"/>

        </div>
        <div id="media-file-details-<?php echo $FeedSlug; ?>" style="<?php echo $style3; ?>">
            <?php
            if( !empty($GeneralSettings['cat_casting_strict']) && !empty($GeneralSettings['custom_cat_feeds']) )
            {
                // Get Podcast Categories...
                $cur_cat_id = intval(!empty($ExtraData['category'])?$ExtraData['category']:0);
                if( count($GeneralSettings['custom_cat_feeds']) == 1 ) // Lets auto select the category
                {
                    foreach( $GeneralSettings['custom_cat_feeds'] as $null => $cur_cat_id ) {
                        break;
                    }
                    reset($GeneralSettings['custom_cat_feeds']);
                }

                ?>
                <div id="pp-category-dropdown-<?php echo $FeedSlug; ?>">
                    <label for="Powerpress[<?php echo $FeedSlug; ?>][category]"><?php echo __('Category', 'powerpress'); ?></label>
                    <div class="powerpress_row_content"><?php
                        echo '<select id="powerpress_category_'. $FeedSlug . '" name="Powerpress['. $FeedSlug .'][category]" class="ep-box-input"> title="Category"';
                        echo '<option value="0"';
                        echo '>' . esc_html( __('Select category', 'powerpress') ) . '</option>' . "\n";

                        foreach( $GeneralSettings['custom_cat_feeds'] as $null => $cat_id ) {
                            $catObj = get_category( $cat_id );
                            if( empty($catObj->name ) )
                                continue; // Do not allow empty categories forward

                            $label = $catObj->name; // TODO: Get the category title
                            echo '<option value="' . esc_attr( $cat_id ) . '"';
                            if ( $cat_id == $cur_cat_id )
                                echo ' selected="selected"';
                            echo '>' . esc_html( $label ) . '</option>' . "\n";
                        }
                        echo '</select>';
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <div id="show-hide-media-details-<?php echo $FeedSlug; ?>">
                <!--<div class="ep-box-line-bold"></div>-->
                <div id="media-details-container-<?php echo $FeedSlug; ?>">
                    <a id="show-details-link-<?php echo $FeedSlug; ?>" class="media-details" title="<?php echo __("Show file size and duration","powerpress"); ?>"
                       onclick="showHideMediaDetails(this)"><?php echo __('View File Size and Duration', 'powerpress'); ?>  &#709;</a>
                    <!--<a id="hide-details-link-<?php //echo $FeedSlug; ?>" class="pp-hidden-settings"
                       onclick="showHideMediaDetails(this)"><?php //echo __('Hide File Size and Duration', 'powerpress'); ?>  &#708;</a>-->
                </div>
            </div>
            <div id="hidden-media-details-<?php echo $FeedSlug; ?>" class="pp-hidden-settings">
                <div class="powerpress_row">
                    <p class="media-details"><?php echo __('FILE SIZE', 'powerpress'); ?></p>
                    <div class="ep-box-line-bold"></div>
                    <div class="pp-detail-section">
                        <div class="details-auto-detect">
                            <input class="media-details-radio" id="powerpress_set_size_0_<?php echo $FeedSlug; ?>" title="<?php echo __("Auto detect file size","powerpress"); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_size]" value="0"
                                   type="radio" <?php echo($GeneralSettings['set_size'] == 0 ? 'checked' : ''); ?> />
                            <?php echo __('Auto detect file size', 'powerpress'); ?>
                        </div>
                        <div class="details-specify">
                            <input class="media-details-radio" id="powerpress_set_size_1_<?php echo $FeedSlug; ?>" title="<?php echo __("Select specify file size","powerpress"); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_size]" value="1"
                                   type="radio" <?php echo($GeneralSettings['set_size'] == 1 ? 'checked' : ''); ?> />
                            <?php echo __('Specify', 'powerpress') . ': '; ?>
                            <input class="ep-box-input" type="text" id="powerpress_size_<?php echo $FeedSlug; ?>" title="<?php echo __("File size in bytes","powerpress"); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][size]"
                                   value="<?php echo esc_attr($EnclosureLength); ?>" style="width: 110px;"
                                   onchange="javascript:jQuery('#powerpress_set_size_1_<?php echo $FeedSlug; ?>').attr('checked', true);"/>
                            <?php echo __('in bytes', 'powerpress'); ?>
                        </div>
                    </div>
                </div>
                <div class="powerpress_row">
                    <p class="media-details"><?php echo __('DURATION', 'powerpress'); ?></p>
                    <div class="ep-box-line-bold"></div>
                    <div class="pp-detail-section">
                        <div class="details-auto-detect">
                            <input class="media-details-radio" id="powerpress_set_duration_0_<?php echo $FeedSlug; ?>" title="<?php echo __("Auto detect duration","powerpress"); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_duration]" value="0"
                                   type="radio" <?php echo($GeneralSettings['set_duration'] == 0 ? 'checked' : ''); ?> />
                            <?php echo __('Auto detect duration', 'powerpress'); ?>
                        </div>
                        <div class="details-specify">
                            <input class="media-details-radio" id="powerpress_set_duration_1_<?php echo $FeedSlug; ?>" title="<?php echo __("Select specify duration","powerpress"); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_duration]" value="1"
                                   type="radio" <?php echo($GeneralSettings['set_duration'] == 1 ? 'checked' : ''); ?> />
                            <?php echo __('Specify', 'powerpress') . ': '; ?>
                            <input type="text" class="ep-box-input" id="powerpress_duration_hh_<?php echo $FeedSlug; ?>" title="<?php echo __("Duration hours","powerpress"); ?>"
                                   placeholder="HH" name="Powerpress[<?php echo $FeedSlug; ?>][duration_hh]"
                                   maxlength="2" value="<?php echo esc_attr($DurationHH); ?>"
                                   onchange="javascript:jQuery('#powerpress_set_duration_1_<?php echo $FeedSlug; ?>').attr('checked', true);"/><strong
                                    style="margin-left: 4px;">:</strong>
                            <input type="text" class="ep-box-input" id="powerpress_duration_mm_<?php echo $FeedSlug; ?>" title="<?php echo __("Duration minutes","powerpress"); ?>"
                                   placeholder="MM" name="Powerpress[<?php echo $FeedSlug; ?>][duration_mm]"
                                   maxlength="2" value="<?php echo esc_attr($DurationMM); ?>"
                                   onchange="javascript:jQuery('#powerpress_set_duration_1_<?php echo $FeedSlug; ?>').attr('checked', true);"/><strong
                                    style="margin-left: 4px;">:</strong>
                            <input type="text" class="ep-box-input" id="powerpress_duration_ss_<?php echo $FeedSlug; ?>" title="<?php echo __("Duration seconds","powerpress"); ?>"
                                   placeholder="SS" name="Powerpress[<?php echo $FeedSlug; ?>][duration_ss]"
                                   maxlength="10" value="<?php echo esc_attr($DurationSS); ?>"
                                   onchange="javascript:jQuery('#powerpress_set_duration_1_<?php echo $FeedSlug; ?>').attr('checked', true);"/>
                        </div>
                        <div class="details-not-specified">
                            <input class="media-details-radio" id="powerpress_set_duration_2_<?php echo $FeedSlug; ?>" title="<?php echo __("Duration not specified","powerpress"); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_duration]" value="-1"
                                   type="radio" <?php echo($GeneralSettings['set_duration'] == -1 ? 'checked' : ''); ?> />
                            <?php echo __('Not specified', 'powerpress'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}


function seo_tab($FeedSlug, $ExtraData, $iTunesExplicit, $canSetSeason, $iTunesSubtitle, $iTunesSummary, $iTunesAuthor, $iTunesOrder, $iTunesBlock, $object)
{
    ?>
    <!-- Tab content -->
    <div id="seo-<?php echo $FeedSlug; ?>" class="pp-tabcontent active">
        <?php //Apple Podcasts Settings
        // //Apple Podcasts Title
        if (empty($ExtraData['episode_title'])) {
            $ExtraData['episode_title'] = '';
        }
        if (empty($ExtraData['episode_no'])) {
            $ExtraData['episode_no'] = '';
        }
        if (empty($ExtraData['season'])) {
            $ExtraData['season'] = '';
        }
        if (empty($ExtraData['episode_type'])) {
            $ExtraData['episode_type'] = '';
        }
        if (empty($ExtraData['feed_title'])) {
            $ExtraData['feed_title'] = '';
        }

        // episode title
        ?>
        <div class="pp-section-container">

            <h4 class="pp-section-title"><?php echo __("Episode Title", 'powerpress') ?></h4>
            <div class="pp-tooltip-right" style="margin: 1ch 0 0 1ch;">i
                <span class="text-pp-tooltip" style="top: -50%; min-width: 200px;"><?php echo __('Please enter your episode title in the space for post title, above the post description.', 'powerpress'); ?></span>
            </div>
            <?php if (!empty($GeneralSettings['seo_feed_title'])) { ?>
            <div class="powerpress_row">
                <div class="powerpress_row_content">
                    <input type="text" id="powerpress_feed_title_<?php echo $FeedSlug; ?>" title="<?php echo __("Episode title","powerpress"); ?>"
                                       class="ep-box-input"
                                       name="Powerpress[<?php echo $FeedSlug; ?>][feed_title]"
                                       value="<?php echo esc_attr($ExtraData['feed_title']); ?>"
                                       placeholder="<?php echo __('Custom episode title', 'powerpress'); ?>"
                                       style="width: 100%; margin-top: 1em;"/>
                </div>
            </div>
            <?php } else { ?>
                <p class="ep-box-text"><?php echo __("The episode title is pulled from your WordPress post title at the top of this page.", 'powerpress'); ?></p>
            <?php } ?>
        </div>
        <div class="pp-section-container">
            <h4 class="pp-section-title"><?php echo __("Episode Description", 'powerpress') ?></h4>
            <div class="pp-tooltip-right" style="margin: 1ch 0 0 1ch;">i
                <span class="text-pp-tooltip"><?php echo __('Please enter your description in the space above the episode box, underneath the post title.', 'powerpress'); ?></span>
            </div>
            <p class="ep-box-text"><?php echo __("The episode description is pulled from your WordPress post content, which can be edited above.", 'powerpress'); ?></p>
        </div>
        <div id="apple-podcast-opt-<?php echo $FeedSlug; ?>" class="pp-section-container">
            <div class="pp-section-container">
                <h4 class="pp-section-title"><?php echo __("Apple Podcasts Optimization (optional)", 'powerpress') ?></h4>
                <div class="pp-tooltip-right">i
                    <span class="text-pp-tooltip"><?php echo __('Fill this section out thoroughly to optimize the exposure that your podcast gets on Apple.', 'powerpress'); ?></span>
                </div>
                <div id="explicit-container-<?php echo $FeedSlug; ?>">
                    <select style="display: none" id="powerpress_explicit_<?php echo $FeedSlug; ?>"
                            name="Powerpress[<?php echo $FeedSlug; ?>][explicit]" style="height:20px;">
                        <?php
                        $explicit_array = array('' => __('Use feed\'s explicit setting', 'powerpress'), 0 => __('no - display nothing', 'powerpress'), 1 => __('yes - explicit content', 'powerpress'), 2 => __('clean - no explicit content', 'powerpress'));

                        foreach ($explicit_array as $value => $desc)
                            echo "\t<option value=\"$value\"" . ($iTunesExplicit == $value ? ' selected' : '') . ">$desc</option>\n";

                        ?>
                    </select>
                    <div id="explicit-switch-base-<?php echo $FeedSlug; ?>">
                        <div id="not-set-<?php echo $FeedSlug; ?>" title="<?php echo __("No explicit selected","powerpress"); ?>"
                             onclick="changeExplicitSwitch(this)"<?php echo $iTunesExplicit == 2 ? ' style="border-right: 1px solid #b3b3b3;"' : '' ?>
                        " class="<?php echo $iTunesExplicit == 0 ? ' explicit-selected' : 'explicit-option' ?>
                        "><?php echo __('NOT SET', 'powerpress'); ?></div>
                        <div id="clean-<?php echo $FeedSlug; ?>" title="<?php echo __("Clean content","powerpress"); ?>"
                             onclick="changeExplicitSwitch(this)"<?php echo $iTunesExplicit == 2 ? '' : ' style="border-left: 1px solid #b3b3b3;border-right: 1px solid #b3b3b3;"' ?>
                        " class="<?php echo $iTunesExplicit == 2 ? ' explicit-selected' : 'explicit-option' ?>
                        "><?php echo __('CLEAN', 'powerpress'); ?></div>
                        <div id="explicit-<?php echo $FeedSlug; ?>" title="<?php echo __("Explicit content","powerpress"); ?>"
                             onclick="changeExplicitSwitch(this)"<?php echo $iTunesExplicit == 2 ? ' style="border-left: 1px solid #b3b3b3;"' : '' ?>
                        " class="<?php echo $iTunesExplicit == 1 ? ' explicit-selected' : 'explicit-option' ?>
                        "><?php echo __('EXPLICIT', 'powerpress'); ?></div>
                    </div>
                </div>
            </div>
            <div class="pp-section-container">
                <div class="label-container" id="apple-title-container-<?php echo $FeedSlug; ?>">
                    <label class="ep-box-label-apple"
                           for="powerpress_episode_apple_title_<?php echo $FeedSlug; ?>"><?php echo __('Title', 'powerpress'); ?></label>
                    <input class="apple-opt-input" type="text" title="<?php echo __("Apple Podcasts episode title","powerpress"); ?>"
                           id="powerpress_episode_apple_title_<?php echo $FeedSlug; ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][episode_title]"
                           value="<?php echo esc_attr($ExtraData['episode_title']); ?>" maxlength="255"/>
                </div>
                <div class="label-container" id="episode-no-container-<?php echo $FeedSlug; ?>">
                    <label class="ep-box-label-apple"
                           for="powerpress_episode_episode_no_<?php echo $FeedSlug; ?>"><?php echo __('Episode #', 'powerpress'); ?></label>
                    <input class="apple-opt-input" type="number" title="<?php echo __("Apple Podcasts episode number","powerpress"); ?>"
                           id="powerpress_episode_episode_no_<?php echo $FeedSlug; ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][episode_no]"
                           value="<?php echo esc_attr($ExtraData['episode_no']); ?>"/>
                </div>
                <div class="label-container" id="season-container-<?php echo $FeedSlug; ?>">
                    <label class="ep-box-label-apple"
                           for="powerpress_episode_season_<?php echo $FeedSlug; ?>"><?php echo __('Season #', 'powerpress'); ?></label>
                    <div class="pp-tooltip-left" style="float: right;">i
                        <span class="text-pp-tooltip"
                              style="float: right;"><?php echo __('If your feed type is set to serial, you may specify a season for each episode.', 'powerpress'); ?></span>
                    </div>
                    <input class="apple-opt-input" type="number" onclick="setCurrentSeason(this)"
                           id="powerpress_episode_season_<?php echo $FeedSlug; ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][season]" title="<?php echo __("Apple Podcasts season number","powerpress"); ?>"
                           value="<?php if ($canSetSeason) {
                               if (isset($ExtraData['season']) && $ExtraData['season']) {
                                   echo esc_attr($ExtraData['season']) . "\"/>";
                               } elseif (isset($GeneralSettings['current_season'])) {
                                   echo esc_attr($GeneralSettings['current_season']) . "\"/>";
                               } else {
                                   echo "1\"/>";
                               }
                           } else {
                               if (isset($ExtraData['season']) && $ExtraData['season']) {
                                   $season = esc_attr($ExtraData['season']);
                               } else {
                                   $season = '1';
                               }
                               echo "$season\" style=\"display: none\"/>";
                               echo "<input class='ep-box-input' type='number' id='powerpress_episode_season_disabled_$FeedSlug' value='$season' disabled \>";
                           } ?>
                    <input id="most-recent-season-<?php echo $FeedSlug; ?>" type="number"
                    name="General[current_season]" style="display: none;" value="1" />
                </div>
            </div>
            <?php
            $iTunesFeatured = get_option('powerpress_itunes_featured');
            $FeaturedChecked = false;
            if (!empty($object->ID) && !empty($iTunesFeatured[$FeedSlug]) && $iTunesFeatured[$FeedSlug] == $object->ID) {
                $FeaturedChecked = true;
            }
            ?>
            <script>
                var show_settings = "<?php echo __("See More Settings &#709;", "powerpress"); ?>";
                var hide_settings = "<?php echo __("Hide Settings &#708;", "powerpress"); ?>";
            </script>

            <div id="show-hide-apple-<?php echo $FeedSlug; ?>">
                <div class="ep-box-line-margin" style="border-top: 2px solid #EFEFEF;"></div>
                <div id="apple-advanced-container-<?php echo $FeedSlug; ?>">
                    <button id="show-apple-link-<?php echo $FeedSlug; ?>" class="apple-advanced" aria-pressed="false" title="<?php echo __("More settings button","powerpress"); ?>"
                            onclick="showHideAppleAdvanced(this)"><?php echo __('See More Settings &#709;', 'powerpress'); ?></button>
                </div>
            </div>
            <div id="apple-advanced-settings-<?php echo $FeedSlug; ?>" class="pp-hidden-settings">
                <div class="apple-opt-section-container">
                    <div id="apple-summary-<?php echo $FeedSlug; ?>" class="label-container" style="width: 100%;">
                        <label class="ep-box-label-apple"
                               for="Powerpress[<?php echo $FeedSlug; ?>][summary]"><?php echo __('Summary', 'powerpress'); ?></label>
                        <textarea id="powerpress_summary_<?php echo $FeedSlug; ?>" class="apple-opt-input" title="<?php echo __("Apple Podcasts episode summary","powerpress"); ?>"
                                  name="Powerpress[<?php echo $FeedSlug; ?>][summary]"><?php echo esc_textarea($iTunesSummary); ?></textarea>
                        <label class="ep-box-label-under"><?php echo __('Leave blank to use post content.', 'powerpress'); ?></label>
                    </div>
                </div>
                <div class="apple-opt-section-container">
                    <div class="label-container" style="width: 100%;" id="height-apple-author-<?php echo $FeedSlug; ?>">
                        <label class="ep-box-label" for="Powerpress[<?php echo $FeedSlug; ?>][author]"><?php echo __('Author', 'powerpress'); ?></label>
                        <input class="apple-opt-input" type="text" id="powerpress_author_<?php echo $FeedSlug; ?>"  title="<?php echo __("Apple Podcasts episode author","powerpress"); ?>" name="Powerpress[<?php echo $FeedSlug; ?>][author]" value="<?php echo esc_attr($iTunesAuthor); ?>" />
                        <label class="ep-box-label-under"><?php echo __('Leave blank to use post author name.', 'powerpress'); ?></label>
                    </div>
                </div>
                <div class="apple-opt-section-container">
                    <div class="label-container" style="width: 100%;" id="height-apple-subtitle-<?php echo $FeedSlug; ?>">
                        <label class="ep-box-label"
                               for="Powerpress[<?php echo $FeedSlug; ?>][subtitle]"><?php echo __('Subtitle', 'powerpress'); ?></label>
                        <input class="apple-opt-input" type="text" id="powerpress_subtitle_<?php echo $FeedSlug; ?>"
                               name="Powerpress[<?php echo $FeedSlug; ?>][subtitle]" title="<?php echo __("Apple Podcasts episode subtitle","powerpress"); ?>"
                               value="<?php echo esc_attr($iTunesSubtitle); ?>" size="250" />
                        <label class="ep-box-label-under"><?php echo __('Leave blank to use post excerpt.', 'powerpress'); ?></label>
                    </div>
                </div>
                <div class="apple-opt-section-container">
                    <div class="label-container">
                        <label class="ep-box-label-apple"
                               for="powerpress_episode_type_<?php echo $FeedSlug; ?>"><?php echo __('Type', 'powerpress'); ?></label>
                        <select style="font-size: 14px;" class="apple-opt-input" id="powerpress_episode_type_<?php echo $FeedSlug; ?>"
                                name="Powerpress[<?php echo $FeedSlug; ?>][episode_type]" title="<?php echo __("Apple Podcasts episode type","powerpress"); ?>">
                            <?php
                            $type_array = array('' => __('Full (default)', 'powerpress'), 'full' => __('Full Episode', 'powerpress'), 'trailer' => __('Trailer', 'powerpress'), 'bonus' => __('Bonus', 'powerpress'));

                            foreach ($type_array as $value => $desc)
                                echo "\t<option value=\"$value\"" . ($ExtraData['episode_type'] == $value ? ' selected' : '') . ">$desc</option>\n";
                            ?>
                        </select>
                    </div>
                    <div class="label-container" style="float: right;">
                        <label class="ep-box-label" for="Powerpress[<?php echo $FeedSlug; ?>][block]"><?php echo __('Block', 'powerpress'); ?></label>
                        <select class="apple-opt-input" id="powerpress_block_<?php echo $FeedSlug; ?>" name="Powerpress[<?php echo $FeedSlug; ?>][block]" title="<?php echo __("Apple Podcasts block episode","powerpress"); ?>">
                            <?php
                            $block_array = array(''=>__('No', 'powerpress'), 1=>__('Yes, Block episode from Apple Podcasts', 'powerpress') );

                            foreach( $block_array as $value => $desc )
                                echo "\t<option value=\"$value\"". ($iTunesBlock==$value?' selected':''). ">$desc</option>\n";
                            unset($block_array);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="apple-opt-section-container">
                    <div class="label-container" id="apple-feature-<?php echo $FeedSlug; ?>">
                        <h4 class="pp-section-title-block" style="width: 100%;"><?php echo __("Feature Episode", 'powerpress') ?></h4>
                        <?php if ($FeaturedChecked) { ?>
                        <input type="hidden" name="PowerpressFeature[<?php echo $FeedSlug; ?>]" value="0" />
                        <?php } ?>
                        <input type="checkbox" id="powerpress_feature_<?php echo $FeedSlug; ?>"
                               name="PowerpressFeature[<?php echo $FeedSlug; ?>]" title="<?php echo __("Feature episode","powerpress"); ?>"
                               value="1" <?php echo($FeaturedChecked ? 'checked' : ''); ?> />
                        <span for="powerpress_feature_<?php echo $FeedSlug; ?>"
                              style="font-size: 14px;"> <?php echo __('Episode will appear at the top of your episode list in the Apple Podcast directory.', 'powerpress'); ?></span>
                    </div>
                    <div class="label-container" id="height-type-<?php echo $FeedSlug; ?>" style="float: right;">
                        <label class="ep-box-label" for="Powerpress[<?php echo $FeedSlug; ?>][order]"><?php echo __('Order', 'powerpress'); ?></label>
                        <input class="apple-opt-input" type="number" id="powerpress_order_<?php echo $FeedSlug; ?>" title="<?php echo __("Apple Podcasts episode order","powerpress"); ?>" name="Powerpress[<?php echo $FeedSlug; ?>][order]" value="<?php echo esc_attr($iTunesOrder); ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function artwork_tab($FeedSlug, $ExtraData, $object, $IsVideo, $CoverImage)
{
    ?>

    <div id="artwork-<?php echo $FeedSlug; ?>" class="pp-tabcontent">
        <?php
        $form_action_url = admin_url("media-upload.php?type=powerpress_image&tab=type&post_id={$object->ID}&powerpress_feed={$FeedSlug}&TB_iframe=true&width=450&height=200");

        //Setting for itunes artwork
        if (!isset($ExtraData['itunes_image']))
            $ExtraData['itunes_image'] = '';
        ?>
        <div class="pp-section-container">
            <div class="powerpress-art-text">
                <h4 class="pp-section-title"
                    style="display: inline-block; margin-bottom: 1em;"><?php echo __('Apple Podcast Episode Artwork', 'powerpress'); ?></h4>
                <div class="pp-tooltip-right">i
                    <span class="text-pp-tooltip"
                          style="top: -150%;"><?php echo __('Episode artwork should be square and have dimensions between 1400 x 1400 pixels and 3000 x 3000 pixels.', 'powerpress'); ?></span>
                </div>
                <br/>
                <input type="text" class="ep-box-input" title="<?php echo __("Apple Image URL","powerpress"); ?>"
                       id="powerpress_itunes_image_<?php echo $FeedSlug; ?>"
                       placeholder="<?php echo htmlspecialchars(__('e.g. http://example.com/path/to/image.jpg', 'powerpress')); ?>"
                       name="Powerpress[<?php echo $FeedSlug; ?>][itunes_image]"
                       value="<?php echo esc_attr($ExtraData['itunes_image']); ?>"
                       style="font-size: 90%;" size="250" oninput="insertArtIntoPreview(this)"/>
                <br/>
                <br/>
                <a href="<?php echo $form_action_url; ?>" class="thickbox powerpress-itunes-image-browser"
                   id="powerpress_itunes_image_browser_<?php echo $FeedSlug; ?>"
                   title="<?php echo __('Select Apple Image', 'powerpress'); ?>">
                    <button class="pp-gray-button"><?php echo __('UPLOAD EPISODE ARTWORK', 'powerpress'); ?></button>
                </a>
            </div>
            <div class="powerpress-art-preview">
                <p class="pp-section-subtitle" style="font-weight: bold;"><?php echo __('PREVIEW', 'powerpress'); ?></p>
                <img id="pp-image-preview-<?php echo $FeedSlug; ?>"
                     src="<?php echo esc_attr($ExtraData['itunes_image']); ?>" alt="No artwork selected"/>
                <p id="pp-image-preview-caption-<?php echo $FeedSlug; ?>" class="pp-section-subtitle"
                   style="font-weight: bold;margin: 3px;"><?php echo get_filename_from_path(esc_attr($ExtraData['itunes_image'])); ?></p>
            </div>
        </div>
        <?php //Setting for poster image
        if ($IsVideo) { ?>
            <div class="ep-box-line-margin"></div>
            <div class="pp-section-container">
                <div class="powerpress-art-text">
                    <h4 class="pp-section-title"><?php echo __('Thumbnail Image', 'powerpress'); ?></h4>
                    <div class="pp-tooltip-right">i
                        <span class="text-pp-tooltip"><?php echo __('This setting is displayed because your podcast media is a video file.', 'powerpress'); ?></span>
                    </div>
                    <br/> <br/>
                    <input type="text" class="ep-box-input" id="powerpress_image_<?php echo $FeedSlug; ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][image]" title="<?php echo __("Poster image URL","powerpress"); ?>"
                           value="<?php echo esc_attr($CoverImage); ?>"
                           placeholder="<?php echo htmlspecialchars(__('e.g. http://example.com/path/to/image.jpg', 'powerpress')); ?>"
                           style="font-size: 90%;" size="250" oninput="insertArtIntoPreview(this)"/>
                    <br/>
                    <label class="ep-box-caption"
                           for="powerpress_image_<?php echo $FeedSlug; ?>"><?php echo __('Poster image for video (m4v, mp4, ogv, webm, etc..)', 'powerpress'); ?></label>
                    <br/>
                    <a href="<?php echo $form_action_url; ?>" class="thickbox powerpress-image-browser"
                       id="powerpress_image_browser_<?php echo $FeedSlug; ?>"
                       title="<?php echo __('Select Poster Image', 'powerpress'); ?>">
                        <button class="pp-gray-button"><?php echo __('UPLOAD THUMBNAIL', 'powerpress'); ?></button>
                    </a>
                </div>
                <div class="powerpress-art-preview">
                    <p class="pp-section-subtitle"
                       style="font-weight: bold;"><?php echo __('PREVIEW', 'powerpress'); ?></p>
                    <img id="poster-pp-image-preview-<?php echo $FeedSlug; ?>"
                         src="<?php echo esc_attr($CoverImage); ?>" alt="No thumbnail selected"/>
                    <p id="poster-pp-image-preview-caption-<?php echo $FeedSlug; ?>" class="pp-section-subtitle"
                       style="font-weight: bold;margin: 3px;"><?php echo get_filename_from_path(esc_attr($CoverImage)); ?></p>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <?php
}

function display_tab($FeedSlug, $IsVideo, $NoPlayer, $NoLinks, $Width, $Height, $Embed)
{
    ?>

    <div id="display-<?php echo $FeedSlug; ?>" class="pp-tabcontent">
        <div id="pp-display-player-<?php echo $FeedSlug; ?>" class="pp-section-container">
            <h4 class="pp-section-title-block"><?php echo __('Episode Player', 'powerpress') ?></h4>
            <span style="font-size: 14px;">
                <input id="powerpress_no_player_<?php echo $FeedSlug; ?>" title="<?php echo __("Do not display player","powerpress"); ?>"
                                                  class="ep-box-checkbox"
                                                  name="Powerpress[<?php echo $FeedSlug; ?>][no_player]" value="1"
                                                  type="checkbox" <?php echo($NoPlayer == 1 ? 'checked' : ''); ?> />
                <?php echo __('Do not display player', 'powerpress'); ?>
            </span>
            <br/>
            <span style="font-size: 14px;">
                <input id="powerpress_no_links_<?php echo $FeedSlug; ?>" title="<?php echo __("Do not display media links","powerpress"); ?>"
                                                  class="ep-box-checkbox"
                                                  name="Powerpress[<?php echo $FeedSlug; ?>][no_links]" value="1"
                                                  type="checkbox" <?php echo($NoLinks == 1 ? 'checked' : ''); ?> />
                <?php echo __('Do not display media links', 'powerpress'); ?>
            </span>
        </div>

        <?php //Setting for audio player size
        if ($IsVideo) { ?>
            <div id="pp-player-size-<?php echo $FeedSlug; ?>" class="pp-section-container">
                <h4 class="pp-section-title"><?php echo __('Video Player Size', 'powerpress') ?></h4>
                <div class="powerpress_row_content">
                    <input type="text" id="powerpress_episode_player_width_<?php echo $FeedSlug; ?>" title="<?php echo __("Player width","powerpress"); ?>"
                           class="ep-box-input" placeholder="<?php echo htmlspecialchars(__('Width', 'powerpress')); ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][width]" value="<?php echo esc_attr($Width); ?>"
                           style="width: 40%;font-size: 90%;" size="5"/>
                    x
                    <input type="text" id="powerpress_episode_player_height_<?php echo $FeedSlug; ?>"
                           class="ep-box-input"
                           placeholder="<?php echo htmlspecialchars(__('Height', 'powerpress')); ?>" title="<?php echo __("Player height","powerpress"); ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][height]" value="<?php echo esc_attr($Height); ?>"
                           style="width: 40%; font-size: 90%;" size="5"/>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="ep-box-line"></div>
        <div id="player-shortcode-<?php echo $FeedSlug; ?>" class="pp-section-container">
            <h4 class="pp-section-title-block"><?php echo __('Display Player Anywhere on Page', 'powerpress'); ?></h4>
            <div style="display:inline-block"><p class="pp-shortcode-example" style="font-weight: bold;">
                    [<?php echo __('powerpress_player'); ?>]</p></div>
            <p class="pp-section-subtitle"><?php echo __('Just copy and paste this shortcode anywhere in your page content. ', 'powerpress'); ?>
                <a href="https://support.wordpress.com/shortcodes/"
                   target="_blank"><?php echo __('Learn more about shortcodes here.', 'powerpress'); ?></a></p>
        </div>
        <div class="ep-box-line"></div>
        <?php //Setting for media embed ?>
        <div class="pp-section-container">
            <h4 class="pp-section-title"><?php echo __('Media Embed', 'powerpress') ?></h4>
            <div class="pp-tooltip-right">i
                <span class="text-pp-tooltip"
                      style="top: -50%;"><?php echo __('Here, you can enter a link to embed a media player.', 'powerpress'); ?></span>
            </div>
            <div class="powerpress_row_content">
                <textarea class="ep-box-input" id="powerpress_embed_<?php echo $FeedSlug; ?>"
                          name="Powerpress[<?php echo $FeedSlug; ?>][embed]" title="<?php echo __("Media Embed","powerpress"); ?>"
                          style="font-size: 14px; width: 90%; height: 80px;"
                          onfocus="this.select();"><?php echo esc_textarea($Embed); ?></textarea>
            </div>
        </div>
    </div>
    <?php
}

function notes_tab($FeedSlug, $object)
{
    ?>


    <div id="notes-<?php echo $FeedSlug; ?>" class="pp-tabcontent">
        <?php $MetaRecords = powerpress_metamarks_get($object->ID, $FeedSlug); ?>
        <script language="javascript"><!--

            function powerpress_metamarks_addrow(FeedSlug) {
                var NextRow = 0;
                if (jQuery('#powerpress_metamarks_counter_' + FeedSlug).length > 0) {
                    NextRow = jQuery('#powerpress_metamarks_counter_' + FeedSlug).val();
                } else {
                    alert('<?php echo __('An error occurred.', 'powerpress'); ?>');
                    return;
                }
                NextRow++;
                jQuery('#powerpress_metamarks_counter_' + FeedSlug).val(NextRow);

                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url(); ?>admin-ajax.php',
                    data: {
                        action: 'powerpress_metamarks_addrow',
                        next_row: NextRow,
                        feed_slug: encodeURIComponent(FeedSlug)
                    },
                    timeout: (10 * 1000),
                    success: function (response) {
                        <?php
                        if (defined('POWERPRESS_AJAX_DEBUG'))
                            echo "\t\t\t\talert(response);\n";
                        ?>
                        jQuery('#powerpress_metamarks_block_' + FeedSlug).append(response);
                    },
                    error: function (objAJAXRequest, strError) {

                        var errorMsg = "HTTP " + objAJAXRequest.statusText;
                        if (objAJAXRequest.responseText) {
                            errorMsg += ', ' + objAJAXRequest.responseText.replace(/<.[^<>]*?>/g, '');
                        }

                        jQuery('#powerpress_check_' + FeedSlug).css("display", 'none');
                        if (strError == 'timeout')
                            jQuery('#powerpress_warning_' + FeedSlug).text('<?php echo __('Operation timed out.', 'powerpress'); ?>');
                        else if (errorMsg)
                            jQuery('#powerpress_warning_' + FeedSlug).text('<?php echo __('AJAX Error', 'powerpress') . ': '; ?>' + errorMsg);
                        else if (strError != null)
                            jQuery('#powerpress_warning_' + FeedSlug).text('<?php echo __('AJAX Error', 'powerpress') . ': '; ?>' + strError);
                        else
                            jQuery('#powerpress_warning_' + FeedSlug).text('<?php echo __('AJAX Error', 'powerpress') . ': ' . __('Unknown', 'powerpress'); ?>');
                        jQuery('#powerpress_warning_' + FeedSlug).css('display', 'block');
                    }
                });
            }

            function powerpress_metamarks_deleterow(div) {
                if (confirm('<?php echo __('Delete row, are you sure?', 'powerpress'); ?>')) {
                    jQuery('#' + div).remove();
                }
                return false;
            }

            // -->
        </script>
        <div class="powerpress_row">
            <h4 class="pp-section-title-block"> <?php echo __('Meta Marks', 'powerpress') ?> </h4>
            <div class="powerpress_row_content">
                <input type="hidden" name="Null[powerpress_metamarks_counter_<?php echo $FeedSlug ?>]"
                       id="powerpress_metamarks_counter_<?php echo $FeedSlug ?>"
                       value="<?php echo count($MetaRecords) ?>"/>
                <input style="cursor:pointer;" type="button" id="powerpress_check_<?php echo $FeedSlug ?>_button"
                       name="powerpress_check_<?php echo $FeedSlug ?>_button" title="<?php echo __("Add Meta Mark","powerpress"); ?>"
                       value="<?php echo __('Add Meta Mark', 'powerpress') ?>"
                       onclick="powerpress_metamarks_addrow('<?php echo $FeedSlug ?>')" class="button"/>

                <div class="powerpress_metamarks_block" id="powerpress_metamarks_block_<?php echo $FeedSlug ?>">

                    <?php
                    $index = 0;
                    foreach ($MetaRecords as $key => $row) {
                        echo powerpress_metamarks_editrow_html($FeedSlug, $index, $row);
                        $index++;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
}


function get_filename_from_path($path) {
    if (strpos($path, '/') !== false) {
        $pieces = explode('/', $path);
    } else {
        $pieces = explode('\\', $path);
    }
    return end($pieces);
}

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @return unknown
 */
function media_upload_powerpress_image() {
	$errors = array();
	$id = 0;

	if ( isset($_POST['html-upload']) && !empty($_FILES) ) {
		// Upload File button was clicked
		$post_id = intval( $_REQUEST['post_id'] ); // precautionary, make sure we're always working with an integer
		$id = media_handle_upload('async-upload', $post_id);
		unset($_FILES);
		if ( is_wp_error($id) ) {
			$errors['upload_error'] = $id;
			$id = false;
		}
	}

	return wp_iframe( 'powerpress_media_upload_type_form', 'powerpress_image', $errors, $id );
}

add_action('media_upload_powerpress_image', 'media_upload_powerpress_image');

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $html
 */
function powerpress_send_to_episode_entry_box($url) {
?>
<script type="text/javascript">
/* <![CDATA[ */
var win = window.dialogArguments || opener || parent || top;
if( win.powerpress_send_to_poster_image )
	win.powerpress_send_to_poster_image('<?php echo addslashes($url); ?>');
/* ]]> */
</script>
<?php
	exit;
}


/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $tabs
 * @return unknown
 */
function powerpress_update_media_upload_tabs($tabs) {
	
	if( !empty($_GET['type'] ) )
	{
		if( $_GET['type'] == 'powerpress_image' ) // We only want to allow uploads
		{
			unset($tabs['type_url']);
			unset($tabs['gallery']);
			unset($tabs['library']);
		}
	}
	return $tabs;
}
add_filter('media_upload_tabs', 'powerpress_update_media_upload_tabs', 100);

/**
 * {@internal Missing Short Description}}
 *
 * @since unknown
 *
 * @param unknown_type $type
 * @param unknown_type $errors
 * @param unknown_type $id
 */
function powerpress_media_upload_type_form($type = 'file', $errors = null, $id = null)
{
	media_upload_header();

	$post_id = isset( $_REQUEST['post_id'] )? intval( $_REQUEST['post_id'] ) : 0;

	$form_action_url = admin_url("media-upload.php?type=$type&tab=type&post_id=$post_id");
	$form_action_url = apply_filters('media_upload_form_url', $form_action_url, $type);
	
	if ( $id && !is_wp_error($id) ) {
		$image_url = wp_get_attachment_url($id);
		powerpress_send_to_episode_entry_box( $image_url );
	}

?>

<form enctype="multipart/form-data" method="post" action="<?php echo esc_attr($form_action_url); ?>" class="media-upload-form type-form validate" id="<?php echo $type; ?>-form">
<input type="submit" class="hidden" name="save" value="" />
<input type="hidden" name="post_id" id="post_id" value="<?php echo (int) $post_id; ?>" />
<?php wp_nonce_field('media-form'); ?>

<h3 class="media-title"><?php echo __('Select poster image from your computer.', 'powerpress'); ?></h3>

<?php media_upload_form( $errors ); ?>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready( function() {
	jQuery('#sidemenu').css('display','none');
	jQuery('body').css('margin','0px 20px');
	jQuery('body').css('height','auto');
	jQuery('html').css('height','auto'); // Elimate the weird scroll bar
});
//]]>
</script>
<div id="media-items">
<?php
	if ( $id && is_wp_error($id) ) {
		echo '<div id="media-upload-error">'.esc_html($id->get_error_message()).'</div>';
	}
?>
</div>
</form>
<?php
}

function powerpress_media_upload_use_flash($flash) {
	if( !empty($_GET['type']) && $_GET['type'] == 'powerpress_image' )
	{
		return false;
	}
	return $flash;
}

add_filter('flash_uploader', 'powerpress_media_upload_use_flash');