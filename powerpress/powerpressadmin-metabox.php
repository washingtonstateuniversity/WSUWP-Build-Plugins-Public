<?php

function episode_box_top($EnclosureURL, $FeedSlug, $ExtraData, $GeneralSettings, $EnclosureLength, $DurationHH, $DurationMM, $DurationSS) {

    if ($EnclosureURL) {
        $style1 = "display: none";
        $style2 = "display: block";
        $style3 = "display: inline-block";
        $style4 = "display: none";
        $filename = $EnclosureURL;
        $style_attr = "";
        $padding = "";
    } else {
        $style1 = "display: inline-block";
        $style2 = "display: none";
        $style3 = "display: none";
        $style4 = "display: block";
        $padding = "style=\"margin-bottom: 2em;\"";
        $filename = "";
        if($GeneralSettings['blubrry_hosting']) {
            $style_attr = "style=\"padding: 2em;\"";
        } else {
            $style_attr = "";
        }
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
    <div id="a-pp-selected-media-<?php echo $FeedSlug; ?>" <?php echo $padding; ?>>
        <h3 id="pp-pp-selected-media-head-<?php echo $FeedSlug; ?>">
            <?php echo esc_html(__('Media URL', 'powerpress')); ?>
            <a class="pp-ep-box-settings thickbox" title='Entry Box Settings' href="<?php echo admin_url('admin.php'); ?>?action=powerpress-ep-box-options&amp;KeepThis=true&amp;TB_iframe=true&amp;width=600&amp;height=400&amp;modal=false">
                <img class="ep-box-settings-icon" src="<?php echo powerpress_get_root_url(); ?>images/outline_settings_24px.svg" alt="" />
            </a>
        </h3>
        <div id="pp-media-blubrry-container-<?php echo $FeedSlug; ?>" <?php echo $style_attr; ?>>
            <div id="pp-selected-media-text-<?php echo $FeedSlug; ?>">
                <div id="media-input-<?php echo $FeedSlug; ?>">
                    <div id="pp-url-input-container-<?php echo $FeedSlug; ?>" style="<?php echo $style1 ?>">
                        <div id="pp-url-input-label-container-<?php echo $FeedSlug; ?>">
                            <input type="text" id="powerpress_url_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__('File Media or URL')); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][url]" placeholder="https://example.com/path/to/media.mp3"
                                   value="<?php echo esc_attr($EnclosureURL); ?>" />
                        </div>
                        <div id="pp-change-media-file-<?php echo $FeedSlug; ?>" style="display: none;">
                            <div id="save-media-<?php echo $FeedSlug; ?>" class="pp-blue-button"
                                 onclick="powerpress_saveMediaFile(this); return false;"><?php echo esc_html(__('VERIFY', 'powerpress')); ?></div>
                        </div>
                        <div id="select-media-file-<?php echo $FeedSlug; ?>" style="<?php echo $style1 ?>">
                            <div id="continue-to-episode-settings-<?php echo $FeedSlug; ?>" class="pp-blue-button"
                             onclick="powerpress_continueToEpisodeSettings(this); return false;"><?php echo esc_html(__('VERIFY', 'powerpress')); ?></div>
                        </div>
                    </div>
                    <div style="<?php echo $style3 ?>" title="<?php echo $EnclosureURL; ?>"
                         id="powerpress_url_show_<?php echo $FeedSlug; ?>">
                        <div id="ep-box-filename-container-<?php echo $FeedSlug; ?>">
                            <p id="ep-box-filename-<?php echo $FeedSlug; ?>"><?php echo $filename ?></p>
                        </div>
                        <img id="powerpress_success_<?php echo $FeedSlug; ?>"
                             src="/wp-content/plugins/powerpress/images/check.svg"
                             style="height: 24px; margin: 14px 1em 0 1em; vertical-align:top; display:none; float: right;"/>
                        <img id="powerpress_fail_<?php echo $FeedSlug; ?>"
                             src="/wp-content/plugins/powerpress/images/redx.svg"
                             style="height: 24px; margin: 14px 1em 0 1em; vertical-align:top; display:none; float: right;"/>
                        <img id="powerpress_check_<?php echo $FeedSlug; ?>"
                             src="<?php echo admin_url(); ?>images/loading.gif"
                             style="height: 24px; margin: 14px 1em 0 1em; vertical-align:top; display: none; float: right;"
                             alt="<?php echo esc_attr(__('Checking Media', 'powerpress')); ?>"/>

                    </div>
                </div>
            </div>
            <div id="ep-box-blubrry-service-<?php echo $FeedSlug; ?>" style="<?php echo $style4; ?>">
                    <?php if($GeneralSettings['blubrry_hosting']) { ?>
                    <div id="ep-box-blubrry-connected-<?php echo $FeedSlug; ?>">
                        <img class="ep-box-blubrry-icon" src="<?php echo powerpress_get_root_url(); ?>images/blubrry_icon.png" alt="" />
                        <div class="ep-box-blubrry-info-container">
                            <h4 class="blubrry-connect-info"><?php echo __('Your Blubrry account is connected', 'powerpress'); ?></h4>
                            <p class="blubrry-connect-info"><?php echo __('Select or upload your media to your Blubrry hosting account.', 'powerpress'); ?></p>
                        </div>
                        <a id="pp-change-media-link-<?php echo $FeedSlug; ?>"
                           href="<?php echo admin_url('admin.php'); ?>?action=powerpress-jquery-media&podcast-feed=<?php echo $FeedSlug; ?>&KeepThis=true&TB_iframe=true&modal=false"
                           class="thickbox">
                            <div id="change-media-button-<?php echo $FeedSlug; ?>"><?php echo esc_html(__('CHOOSE FILE', 'powerpress')); ?></div>
                        </a>
                    </div>
                    <?php } else {
                        $pp_nonce = powerpress_login_create_nonce();
                    ?>
                        <div id="ep-box-blubrry-connect-<?php echo $FeedSlug; ?>" style="<?php echo $style4; ?>">
                            <img class="ep-box-blubrry-icon" src="<?php echo powerpress_get_root_url(); ?>images/blubrry_icon.png" alt="" />
                            <div class="ep-box-blubrry-info-container">
                                <h4 class="blubrry-connect-info"><?php echo __('If you host with Blubrry', 'powerpress'); ?></h4>
                                <p class="blubrry-connect-info"><?php echo __('You can select a media file from your computer by connecting your hosting account.', 'powerpress'); ?></p>
                            </div>
                            <a class="button-blubrry" id="ep-box-connect-account-<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__('Blubrry Services Integration', 'powerpress')); ?>" href="<?php echo add_query_arg( '_wpnonce', $pp_nonce, admin_url("admin.php?page=powerpressadmin_onboarding.php&step=blubrrySignin&from=new_post")); ?>">
                                <div id="ep-box-connect-account-button-<?php echo $FeedSlug; ?>"><?php echo __('Connect to Blubrry', 'powerpress'); ?></div>
                            </a>
                        </div>
                        <div id="ep-box-min-blubrry-connect-<?php echo $FeedSlug; ?>" style="<?php echo $style2; ?>">
                            <div id="pp-connect-account-<?php echo $FeedSlug; ?>">
                                <a id="pp-connect-account-link-<?php echo $FeedSlug; ?>" class="pp-media-edit-details button-blubrry" title="<?php echo esc_attr(__("Blubrry Services Integration","powerpress")); ?>" href="<?php echo add_query_arg( '_wpnonce', $pp_nonce, admin_url("admin.php?page=powerpressadmin_onboarding.php&step=blubrrySignin&from=new_post")); ?>">
                                    <b><?php echo esc_html(__('Connect Blubrry Account', 'powerpress')); ?></b>
                                </a>
                            </div>
                            <div id="pp-cancel-container-<?php echo $FeedSlug; ?>">
                                <div id="pp-cancel-media-<?php echo $FeedSlug; ?>">
                                    <button id="cancel-media-edit-<?php echo $FeedSlug; ?>" class="pp-media-edit-details"
                                            onclick="powerpress_cancelMediaEdit(this); return false;"><b><?php echo esc_html(__('CANCEL', 'powerpress')); ?></b></button>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
            </div>
        </div>
        <div id="pp-warning-messages">
            <div id="file-select-warning-<?php echo $FeedSlug; ?>"
                 style="background-color: white; box-shadow: none; margin-left: 0; padding-left: 3px; display:none; color: #dc3232;"><?php echo esc_html(__('You must have a media file selected to continue to episode settings.', 'powerpress')); ?></div>
            <div id="file-change-warning-<?php echo $FeedSlug; ?>"
                 style="background-color: #f5f5f5; box-shadow: none; margin-left: 0; padding-left: 3px; display:none; color: #dc3232;"><?php echo esc_html(__('You must have a media file selected to save.', 'powerpress')); ?></div>
            <div id="powerpress_warning_<?php echo $FeedSlug; ?>"
                 style="background-color: #f5f5f5; box-shadow: none; margin-left: 0; padding-left: 3px; display:none; color: #dc3232;"><?php echo esc_html(__('Error verifying media file.', 'powerpress')); ?></div>
            <input type="hidden" id="powerpress_hosting_<?php echo $FeedSlug; ?>"
                   name="Powerpress[<?php echo $FeedSlug; ?>][hosting]"
                   value="<?php echo(!empty($ExtraData['hosting']) ? '1' : '0'); ?>"/>
            <div id="powerpress_hosting_note_<?php echo $FeedSlug; ?>"
                 style="margin-left: 2px; padding-bottom: 2px; padding-top: 2px; display: <?php echo(!empty($ExtraData['hosting']) ? 'block' : 'none'); ?>">
                <em><?php echo esc_html(__('Media file hosted by blubrry.com.', 'powerpress')); ?>
                    (<a href="#" title="<?php echo esc_attr(__('Remove Blubrry.com hosted media file', 'powerpress')); ?>"
                        onclick="powerpress_remove_hosting('<?php echo $FeedSlug; ?>');return false;"><?php echo esc_html(__('remove', 'powerpress')); ?></a>)
                </em></div>
            <input type="hidden" id="powerpress_program_keyword_<?php echo $FeedSlug; ?>"
                   name="Powerpress[<?php echo $FeedSlug; ?>][program_keyword]"
                   value="<?php echo !empty($ExtraData['program_keyword']) ? $ExtraData['program_keyword'] : ''; ?>"/>

        </div>
        <div id="media-file-details-<?php echo $FeedSlug; ?>" style="<?php echo $style3; ?>">
            <div>
                <div id="edit-media-file-<?php echo $FeedSlug; ?>" style="<?php echo $style3 ?>">
                    <button id="pp-edit-media-button-<?php echo $FeedSlug; ?>" class="media-details"
                         onclick="powerpress_changeMediaFile(event, this); return false;"><?php echo esc_html(__('Edit Media File', 'powerpress')); ?></button>
                </div>
                <div id="show-hide-media-details-<?php echo $FeedSlug; ?>">
                    <!--<div class="ep-box-line-bold"></div>-->
                    <div id="media-details-container-<?php echo $FeedSlug; ?>">
                        <button id="show-details-link-<?php echo $FeedSlug; ?>" class="media-details" title="<?php echo esc_attr(__("Show file size and duration","powerpress")); ?>"
                           onclick="powerpress_showHideMediaDetails(this); return false;"><?php echo esc_html(__('View File Size and Duration', 'powerpress')); ?>  &#709;</button>
                        <!--<a id="hide-details-link-<?php //echo $FeedSlug; ?>" class="pp-hidden-settings"
                           onclick="showHideMediaDetails(this)"><?php //echo __('Hide File Size and Duration', 'powerpress'); ?>  &#708;</a>-->
                    </div>
                </div>
            </div>
            <div id="hidden-media-details-<?php echo $FeedSlug; ?>" class="pp-hidden-settings">
                <div class="powerpress_row">
                    <p class="media-details-head"><?php echo esc_html(__('File Size', 'powerpress')); ?></p>
                    <div class="pp-detail-section">
                        <div class="details-auto-detect">
                            <input class="media-details-radio" id="powerpress_set_size_0_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Auto detect file size","powerpress")); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_size]" value="0"
                                   type="radio" <?php echo($GeneralSettings['set_size'] == 0 ? 'checked' : ''); ?> />
                            <?php echo esc_html(__('Auto detect file size', 'powerpress')); ?>
                        </div>
                        <div class="details-specify">
                            <input class="media-details-radio" id="powerpress_set_size_1_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Select specify file size","powerpress")); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_size]" value="1"
                                   type="radio" <?php echo($GeneralSettings['set_size'] == 1 ? 'checked' : ''); ?> />
                            <?php echo esc_html(__('Specify', 'powerpress')) . ': '; ?>
                            <input class="pp-ep-box-input" type="text" id="powerpress_size_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("File size in bytes","powerpress")); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][size]"
                                   value="<?php echo esc_attr($EnclosureLength); ?>" style="width: 110px;height: auto;"
                                   onchange="javascript:jQuery('#powerpress_set_size_1_<?php echo $FeedSlug; ?>').attr('checked', true);"/>
                            <?php echo esc_html(__('in bytes', 'powerpress')); ?>
                        </div>
                    </div>
                </div>
                <div class="powerpress_row">
                    <p class="media-details-head" style="margin-bottom: 1ch;"><?php echo esc_html(__('Duration', 'powerpress')); ?></p>
                    <div class="pp-detail-section">
                        <div class="details-auto-detect">
                            <input class="media-details-radio" id="powerpress_set_duration_0_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Auto detect duration","powerpress")); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_duration]" value="0"
                                   type="radio" <?php echo($GeneralSettings['set_duration'] == 0 ? 'checked' : ''); ?> />
                            <?php echo esc_html(__('Auto detect duration', 'powerpress')); ?>
                        </div>
                        <div class="details-specify">
                            <input class="media-details-radio" id="powerpress_set_duration_1_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Select specify duration","powerpress")); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_duration]" value="1"
                                   type="radio" <?php echo($GeneralSettings['set_duration'] == 1 ? 'checked' : ''); ?> />
                            <?php echo esc_html(__('Specify', 'powerpress')) . ': '; ?>
                            <input type="text" class="pp-ep-box-input" id="powerpress_duration_hh_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Duration hours","powerpress")); ?>"
                                   placeholder="HH" name="Powerpress[<?php echo $FeedSlug; ?>][duration_hh]"
                                   maxlength="2" value="<?php echo esc_attr($DurationHH); ?>"
                                   onchange="javascript:jQuery('#powerpress_set_duration_1_<?php echo $FeedSlug; ?>').attr('checked', true);"/><strong
                                    style="margin-left: 4px;">:</strong>
                            <input type="text" class="pp-ep-box-input" id="powerpress_duration_mm_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Duration minutes","powerpress")); ?>"
                                   placeholder="MM" name="Powerpress[<?php echo $FeedSlug; ?>][duration_mm]"
                                   maxlength="2" value="<?php echo esc_attr($DurationMM); ?>"
                                   onchange="javascript:jQuery('#powerpress_set_duration_1_<?php echo $FeedSlug; ?>').attr('checked', true);"/><strong
                                    style="margin-left: 4px;">:</strong>
                            <input type="text" class="pp-ep-box-input" id="powerpress_duration_ss_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Duration seconds","powerpress")); ?>"
                                   placeholder="SS" name="Powerpress[<?php echo $FeedSlug; ?>][duration_ss]"
                                   maxlength="10" value="<?php echo esc_attr($DurationSS); ?>"
                                   onchange="javascript:jQuery('#powerpress_set_duration_1_<?php echo $FeedSlug; ?>').attr('checked', true);"/>
                        </div>
                        <div class="details-not-specified">
                            <input class="media-details-radio" id="powerpress_set_duration_2_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Duration not specified","powerpress")); ?>"
                                   name="Powerpress[<?php echo $FeedSlug; ?>][set_duration]" value="-1"
                                   type="radio" <?php echo($GeneralSettings['set_duration'] == -1 ? 'checked' : ''); ?> />
                            <?php echo esc_html(__('Not specified', 'powerpress')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <label for="Powerpress[<?php echo $FeedSlug; ?>][category]"><?php echo esc_html(__('Category', 'powerpress')); ?></label>
                <div class="powerpress_row_content"><?php
                    echo '<select id="powerpress_category_'. $FeedSlug . '" name="Powerpress['. $FeedSlug .'][category]" class="pp-ep-box-input"> title="Category"';
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
        <?php if($EnclosureURL) { ?>
        <div class="ep-box-line"></div>
        <?php } ?>
    </div>
    <?php if($EnclosureURL) { ?>
    <div class="powerpress_remove_container">
        <div class="powerpress_row_content">
            <input type="checkbox" class='ep-box-checkbox' name="Powerpress[<?php echo $FeedSlug; ?>][remove_podcast]" id="powerpress_remove_<?php echo $FeedSlug; ?>" value="1"  onchange="javascript:document.getElementById('a-pp-selected-media-<?php echo $FeedSlug; ?>').style.display=(this.checked?'none':'block');javascript:document.getElementById('tab-container-<?php echo $FeedSlug; ?>').style.display=(this.checked?'none':'block');" />
            <b><?php echo esc_html(__('Remove Episode', 'powerpress')); ?></b><?php echo esc_html(__(' - Podcast episode will be removed from this post upon save', 'powerpress')); ?>
        </div>
    </div>
    <?php }
}


function seo_tab($FeedSlug, $ExtraData, $iTunesExplicit, $seo_feed_title, $GeneralSettings, $iTunesSubtitle, $iTunesSummary, $iTunesAuthor, $iTunesOrder, $iTunesBlock, $object)
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
        $AppleOpt = true;
        $AppleExtra = true;
        if (isset($GeneralSettings['new_episode_box_subtitle']) && isset($GeneralSettings['new_episode_box_summary']) && isset($GeneralSettings['new_episode_box_author']) && isset($GeneralSettings['new_episode_box_explicit']) && isset($GeneralSettings['new_episode_box_order']) && isset($GeneralSettings['new_episode_box_itunes_title']) && isset($GeneralSettings['new_episode_box_itunes_nst']) && isset($GeneralSettings['new_episode_box_feature_in_itunes']) && isset($GeneralSettings['new_episode_box_block']) ) {
            if ($GeneralSettings['new_episode_box_subtitle'] == 2 && $GeneralSettings['new_episode_box_summary'] == 2 && $GeneralSettings['new_episode_box_author'] == 2 && $GeneralSettings['new_episode_box_explicit'] == 2 && $GeneralSettings['new_episode_box_order'] == 2 && $GeneralSettings['new_episode_box_itunes_title'] == 2 && $GeneralSettings['new_episode_box_itunes_nst'] == 2 && $GeneralSettings['new_episode_box_feature_in_itunes'] == 2 && $GeneralSettings['new_episode_box_block'] == 2) {
                $AppleOpt = false;
                $AppleExtra = false;
            } else {
                if ($GeneralSettings['new_episode_box_subtitle'] == 2 && $GeneralSettings['new_episode_box_summary'] == 2 && $GeneralSettings['new_episode_box_author'] == 2 && $GeneralSettings['new_episode_box_order'] == 2 && $GeneralSettings['new_episode_box_feature_in_itunes'] == 2 && $GeneralSettings['new_episode_box_block'] == 2) {
                    $AppleExtra = false;
                }
            }
        }

        // episode title
        ?>
        <div class="pp-section-container">

            <h4 class="pp-section-title"><?php echo esc_html(__("Episode Title", 'powerpress')); ?></h4>
            <div class="pp-tooltip-right" style="margin: 1ch 0 0 1ch;">i
                <span class="text-pp-tooltip" style="top: -50%; min-width: 200px;"><?php echo esc_html(__('Please enter your episode title in the space for post title, above the post description.', 'powerpress')); ?></span>
            </div>
            <?php if ($seo_feed_title) { ?>
            <div class="powerpress_row">
                <div class="powerpress_row_content">
                    <input type="text" id="powerpress_feed_title_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Episode title","powerpress")); ?>"
                                       class="pp-ep-box-input"
                                       name="Powerpress[<?php echo $FeedSlug; ?>][feed_title]"
                                       value="<?php echo esc_attr($ExtraData['feed_title']); ?>"
                                       placeholder="<?php echo esc_attr(__('Custom episode title', 'powerpress')); ?>"
                                       style="width: 96%; margin-top: 1em;"/>
                </div>
                <label class="pp-ep-box-label-under"><?php echo esc_html(__("Leave blank to use WordPress post title at the top of this page.", 'powerpress')); ?></label>
            </div>
            <?php } else { ?>
                <p class="pp-ep-box-text"><?php echo esc_html(__("The episode title is pulled from your WordPress post title at the top of this page.", 'powerpress')); ?></p>
            <?php } ?>
        </div>
        <div class="pp-section-container">
            <h4 class="pp-section-title"><?php echo esc_html(__("Episode Description", 'powerpress')); ?></h4>
            <div class="pp-tooltip-right" style="margin: 1ch 0 0 1ch;">i
                <span class="text-pp-tooltip"><?php echo esc_html(__('Please enter your description in the space above the episode box, underneath the post title.', 'powerpress')); ?></span>
            </div>
            <p class="pp-ep-box-text"><?php echo esc_html(__("The episode description is pulled from your WordPress post content, which can be edited above.", 'powerpress')); ?></p>
        </div>
        <?php if($AppleOpt) { ?>
        <div id="apple-podcast-opt-<?php echo $FeedSlug; ?>" class="pp-section-container">
            <div class="pp-section-container">
                <h4 class="pp-section-title"><?php echo esc_html(__("Apple Podcasts Optimization (optional)", 'powerpress')); ?></h4>
                <div class="pp-tooltip-right">i
                    <span class="text-pp-tooltip"><?php echo esc_html(__('Fill this section out thoroughly to optimize the exposure that your podcast gets on Apple.', 'powerpress')); ?></span>
                </div>
                <?php if( !isset($GeneralSettings['new_episode_box_explicit']) || $GeneralSettings['new_episode_box_explicit'] == 1 ) { ?>
                <div id="pp-explicit-container-<?php echo $FeedSlug; ?>">
                    <input type="number" style="display: none" id="powerpress_explicit_<?php echo $FeedSlug; ?>"
                            name="Powerpress[<?php echo $FeedSlug; ?>][explicit]" value="<?php echo $iTunesExplicit ? $iTunesExplicit : 0; ?>">
                    <label class="pp-ep-box-label" for="explicit-switch-base-<?php echo $FeedSlug; ?>"><?php echo esc_html(__("Explicit Setting", "powerpress")); ?></label>
                    <div id="explicit-switch-base-<?php echo $FeedSlug; ?>">
                        <div id="not-set-<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("No explicit selected","powerpress")); ?>"
                             onclick="powerpress_changeExplicitSwitch(this)"<?php echo $iTunesExplicit == 2 ? ' style="border-right: 1px solid #b3b3b3;"' : '' ?>
                        " class="<?php echo $iTunesExplicit == 0 ? 'explicit-selected' : 'pp-explicit-option' ?>
                        "><?php echo esc_html(__('NOT SET', 'powerpress')); ?></div>
                        <div id="clean-<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Clean content","powerpress")); ?>"
                             onclick="powerpress_changeExplicitSwitch(this)"<?php echo $iTunesExplicit == 2 ? '' : ' style="border-left: 1px solid #b3b3b3;border-right: 1px solid #b3b3b3;"' ?>
                        " class="<?php echo $iTunesExplicit == 2 ? 'explicit-selected' : 'pp-explicit-option' ?>
                        "><?php echo esc_html(__('CLEAN', 'powerpress')); ?></div>
                        <div id="explicit-<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Explicit content","powerpress")); ?>"
                             onclick="powerpress_changeExplicitSwitch(this)"<?php echo $iTunesExplicit == 2 ? ' style="border-left: 1px solid #b3b3b3;"' : '' ?>
                        " class="<?php echo $iTunesExplicit == 1 ? 'explicit-selected' : 'pp-explicit-option' ?>
                        "><?php echo esc_html(__('EXPLICIT', 'powerpress')); ?></div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="pp-section-container">
                <?php if(!isset($GeneralSettings['new_episode_box_itunes_title']) || $GeneralSettings['new_episode_box_itunes_title'] == 1) { ?>
                <div class="powerpress-label-container" id="apple-title-container-<?php echo $FeedSlug; ?>">
                    <label class="pp-ep-box-label-apple"
                           for="powerpress_episode_apple_title_<?php echo $FeedSlug; ?>"><?php echo esc_html(__('Title', 'powerpress')); ?></label>
                    <input class="pp-ep-box-input" type="text" title="<?php echo esc_attr(__("Apple Podcasts episode title","powerpress")); ?>"
                           id="powerpress_episode_apple_title_<?php echo $FeedSlug; ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][episode_title]"
                           value="<?php echo esc_attr($ExtraData['episode_title']); ?>" maxlength="255"/>
                </div>
                <?php }
                if (!isset($GeneralSettings['new_episode_box_itunes_nst']) || $GeneralSettings['new_episode_box_itunes_nst'] == 1) {
                ?>
                <div class="powerpress-label-container" id="episode-no-container-<?php echo $FeedSlug; ?>">
                    <label class="pp-ep-box-label-apple"
                           for="powerpress_episode_episode_no_<?php echo $FeedSlug; ?>"><?php echo esc_html(__('Episode #', 'powerpress')); ?></label>
                    <input class="pp-ep-box-input" type="number" title="<?php echo esc_attr(__("Apple Podcasts episode number","powerpress")); ?>"
                           id="powerpress_episode_episode_no_<?php echo $FeedSlug; ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][episode_no]"
                           value="<?php echo esc_attr($ExtraData['episode_no']); ?>"/>
                </div>
                <div class="powerpress-label-container" id="season-container-<?php echo $FeedSlug; ?>">
                    <label class="pp-ep-box-label-apple"
                           for="powerpress_episode_season_<?php echo $FeedSlug; ?>"><?php echo esc_html(__('Season #', 'powerpress')); ?></label>
                    <!--<div class="pp-tooltip-left" style="float: right;">i
                        <span class="text-pp-tooltip"
                              style="float: right;"><?php echo esc_html(__('If your feed type is set to serial, you may specify a season for each episode.', 'powerpress')); ?></span>
                    </div>-->
                    <input class="pp-ep-box-input" type="number" oninput="powerpress_setCurrentSeason(this)"
                           id="powerpress_episode_season_<?php echo $FeedSlug; ?>"
                           name="Powerpress[<?php echo $FeedSlug; ?>][season]" title="<?php echo esc_attr(__("Apple Podcasts season number","powerpress")); ?>"
                           style="width: 100%;"
                           <?php if (isset($ExtraData['season']) && $ExtraData['season']) {
                                   echo " value=\"" . esc_attr($ExtraData['season']) . "\"/>";
                               } elseif (isset($GeneralSettings['current_season'])) {
                                   //echo " value=\"" . esc_attr($GeneralSettings['current_season']) . "\"/>";
                                   echo " />";
                               } else {
                                   echo " />";
                               } ?>
                </div>
                <?php } ?>
            </div>
            <?php
            $iTunesFeatured = get_option('powerpress_itunes_featured');
            $FeaturedChecked = false;
            if (!empty($object->ID) && !empty($iTunesFeatured[$FeedSlug]) && $iTunesFeatured[$FeedSlug] == $object->ID) {
                $FeaturedChecked = true;
            }
            ?>
            <script>
                var show_settings = "<?php echo esc_js(__("See More Settings &#709;", "powerpress")); ?>";
                var hide_settings = "<?php echo esc_js(__("Hide Settings &#708;", "powerpress")); ?>";
            </script>

            <?php if($AppleExtra) { ?>
            <div id="show-hide-apple-<?php echo $FeedSlug; ?>">
                <div class="ep-box-line-margin" style="border-top: 2px solid #EFEFEF;"></div>
                <div id="apple-advanced-container-<?php echo $FeedSlug; ?>">
                    <button id="show-apple-link-<?php echo $FeedSlug; ?>" class="apple-advanced" aria-pressed="false" title="<?php echo esc_attr(__("More settings button","powerpress")); ?>"
                            onclick="powerpress_showHideAppleAdvanced(this); return false;"><?php echo esc_html(__('See More Settings &#709;', 'powerpress')); ?></button>
                </div>
            </div>
            <?php } ?>
            <div id="apple-advanced-settings-<?php echo $FeedSlug; ?>" class="pp-hidden-settings">
                <?php if( !isset($GeneralSettings['new_episode_box_summary']) || $GeneralSettings['new_episode_box_summary'] == 1 ) { ?>
                <div class="apple-opt-section-container">
                    <div id="apple-summary-<?php echo $FeedSlug; ?>" class="powerpress-label-container" style="width: 100%;">
                        <label class="pp-ep-box-label-apple"
                               for="Powerpress[<?php echo $FeedSlug; ?>][summary]"><?php echo esc_html(__('Summary', 'powerpress')); ?></label>
                        <textarea id="powerpress_summary_<?php echo $FeedSlug; ?>" class="pp-ep-box-input" title="<?php echo esc_attr(__("Apple Podcasts episode summary","powerpress")); ?>"
                                  name="Powerpress[<?php echo $FeedSlug; ?>][summary]"><?php echo esc_textarea($iTunesSummary); ?></textarea>
                        <label class="pp-ep-box-label-under"><?php echo esc_html(__('Leave blank to use post content.', 'powerpress')); ?></label>
                    </div>
                </div>
                <?php }
                if( !isset($GeneralSettings['new_episode_box_author']) || $GeneralSettings['new_episode_box_author'] == 1 ) { ?>
                <div class="apple-opt-section-container">
                    <div class="powerpress-label-container" style="width: 100%;" id="apple-author-<?php echo $FeedSlug; ?>">
                        <label class="pp-ep-box-label" for="Powerpress[<?php echo $FeedSlug; ?>][author]"><?php echo esc_html(__('Author', 'powerpress')); ?></label>
                        <input class="pp-ep-box-input" type="text" id="powerpress_author_<?php echo $FeedSlug; ?>"  title="<?php echo esc_attr(__("Apple Podcasts episode author","powerpress")); ?>" name="Powerpress[<?php echo $FeedSlug; ?>][author]" value="<?php echo esc_attr($iTunesAuthor); ?>" />
                        <label class="pp-ep-box-label-under"><?php echo esc_html(__('Leave blank to use default.', 'powerpress')); ?></label>
                    </div>
                </div>
                <?php }
                if( !isset($GeneralSettings['new_episode_box_subtitle']) || $GeneralSettings['new_episode_box_subtitle'] == 1 ) { ?>
                <div class="apple-opt-section-container">
                    <div class="powerpress-label-container" style="width: 100%;" id="apple-subtitle-<?php echo $FeedSlug; ?>">
                        <label class="pp-ep-box-label"
                               for="Powerpress[<?php echo $FeedSlug; ?>][subtitle]"><?php echo esc_html(__('Subtitle', 'powerpress')); ?></label>
                        <input class="pp-ep-box-input" type="text" id="powerpress_subtitle_<?php echo $FeedSlug; ?>"
                               name="Powerpress[<?php echo $FeedSlug; ?>][subtitle]" title="<?php echo esc_attr(__("Apple Podcasts episode subtitle","powerpress")); ?>"
                               value="<?php echo esc_attr($iTunesSubtitle); ?>" size="250" />
                        <label class="pp-ep-box-label-under"><?php echo esc_html(__('Leave blank to use post excerpt.', 'powerpress')); ?></label>
                    </div>
                </div>
                <?php } ?>
                <div class="apple-opt-section-container">
                    <?php if (!isset($GeneralSettings['new_episode_box_itunes_nst']) || $GeneralSettings['new_episode_box_itunes_nst'] == 1) {
                    ?>
                    <div class="powerpress-label-container">
                        <label class="pp-ep-box-label-apple"
                               for="powerpress_episode_type_<?php echo $FeedSlug; ?>"><?php echo esc_html(__('Type', 'powerpress')); ?></label>
                        <select style="font-size: 14px;" class="pp-ep-box-input" id="powerpress_episode_type_<?php echo $FeedSlug; ?>"
                                name="Powerpress[<?php echo $FeedSlug; ?>][episode_type]" title="<?php echo esc_attr(__("Apple Podcasts episode type","powerpress")); ?>">
                            <?php
                            $type_array = array('' => esc_html(__('Full (default)', 'powerpress')), 'full' => esc_html(__('Full Episode', 'powerpress')), 'trailer' => esc_html(__('Trailer', 'powerpress')), 'bonus' => esc_html(__('Bonus', 'powerpress')));

                            foreach ($type_array as $value => $desc)
                                echo "\t<option value=\"$value\"" . ($ExtraData['episode_type'] == $value ? ' selected' : '') . ">$desc</option>\n";
                            ?>
                        </select>
                    </div>
                    <?php
                        $float = "right";
                    } elseif($GeneralSettings['new_episode_box_feature_in_itunes'] == 2 && $GeneralSettings['new_episode_box_order'] == 2) {
                        $float = "none";
                    } else {
                        $float = "right";
                    }
                    if (!isset($GeneralSettings['new_episode_box_block']) || $GeneralSettings['new_episode_box_block'] == 1) { ?>
                    <div class="powerpress-label-container" style="float: <?php echo $float; ?>">
                        <label class="pp-ep-box-label" for="Powerpress[<?php echo $FeedSlug; ?>][block]"><?php echo esc_html(__('Block', 'powerpress')); ?></label>
                        <select class="pp-ep-box-input" id="powerpress_block_<?php echo $FeedSlug; ?>" name="Powerpress[<?php echo $FeedSlug; ?>][block]" title="<?php echo esc_attr(__("Apple Podcasts block episode","powerpress")); ?>">
                            <?php
                            $block_array = array(''=>esc_html(__('No', 'powerpress')), 1=>esc_html(__('Yes, Block episode from Apple Podcasts', 'powerpress')) );

                            foreach( $block_array as $value => $desc )
                                echo "\t<option value=\"$value\"". ($iTunesBlock==$value?' selected':''). ">$desc</option>\n";
                            unset($block_array);
                            ?>
                        </select>
                    </div>
                    <?php } ?>
                </div>
                <div class="apple-opt-section-container">
                    <?php if( !isset($GeneralSettings['new_episode_box_feature_in_itunes']) || $GeneralSettings['new_episode_box_feature_in_itunes'] == 1 ) { ?>
                    <div class="powerpress-label-container" id="apple-feature-<?php echo $FeedSlug; ?>" style="width: 65%;">
                        <h4 class="pp-section-title-block" style="width: 100%;"><?php echo esc_html(__("Feature Episode", 'powerpress')); ?></h4>
                        <?php if ($FeaturedChecked) { ?>
                        <input type="hidden" name="PowerpressFeature[<?php echo $FeedSlug; ?>]" value="0" />
                        <?php } ?>
                        <input type="checkbox" id="powerpress_feature_<?php echo $FeedSlug; ?>"
                               name="PowerpressFeature[<?php echo $FeedSlug; ?>]" title="<?php echo esc_attr(__("Feature episode","powerpress")); ?>"
                               value="1" <?php echo($FeaturedChecked ? 'checked' : ''); ?> />
                        <span for="powerpress_feature_<?php echo $FeedSlug; ?>"
                              style="font-size: 14px;"> <?php echo esc_html(__('Episode will appear at the top of your episode list in the Apple Podcast directory.', 'powerpress')); ?></span>
                    </div>
                    <?php
                        $float = "right";
                    } else {
                        $float = "none";
                    }
                    if( !isset($GeneralSettings['new_episode_box_order']) || $GeneralSettings['new_episode_box_order'] == 1 ||  !isset($GeneralSettings['new_episode_box_feature_in_itunes']) || $GeneralSettings['new_episode_box_feature_in_itunes'] == 1 ) { ?>
                    <div class="powerpress-label-container" id="type-<?php echo $FeedSlug; ?>" style="float: <?php echo $float; ?>; width: 30%;">
                        <label class="pp-ep-box-label" for="Powerpress[<?php echo $FeedSlug; ?>][order]"><?php echo esc_html(__('Order', 'powerpress')); ?></label>
                        <input class="pp-ep-box-input" type="number" id="powerpress_order_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Apple Podcasts episode order","powerpress")); ?>" name="Powerpress[<?php echo $FeedSlug; ?>][order]" value="<?php echo esc_attr($iTunesOrder); ?>" />
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
    <?php
}

function artwork_tab($FeedSlug, $ExtraData, $object, $CoverImage, $GeneralSettings)
{
    ?>

    <div id="artwork-<?php echo $FeedSlug; ?>" class="pp-tabcontent">
        <?php
        $form_action_url = admin_url("media-upload.php?type=powerpress_image&tab=type&post_id={$object->ID}&powerpress_feed={$FeedSlug}&TB_iframe=true&width=450&height=200");

        //Setting for itunes artwork
        if (!isset($ExtraData['itunes_image']) || !$ExtraData['itunes_image']) {
            $itunes_image = '';
            $itunes_image_preview = powerpress_get_root_url() . 'images/pts_cover.jpg';
        } else {
            $itunes_image = $ExtraData['itunes_image'];
            $itunes_image_preview = $itunes_image;
        }
        if (!$CoverImage) {
            $CoverImage_preview = powerpress_get_root_url() . 'images/pts_cover.jpg';
        } else {
            $CoverImage_preview = $CoverImage;
        }
        if (isset($GeneralSettings['new_episode_box_itunes_image']) && $GeneralSettings['new_episode_box_itunes_image'] == 2 && isset($GeneralSettings['new_episode_box_cover_image']) && $GeneralSettings['new_episode_box_cover_image']) {
            echo "<p class='pp-ep-box-text'>" . __('No artwork settings enabled', 'powerpress') . "</p></div>";
            return;
        }
        if (!isset($GeneralSettings['new_episode_box_itunes_image']) || $GeneralSettings['new_episode_box_itunes_image'] == 1) { ?>
        <div class="pp-section-container">
            <div class="powerpress-art-text">
                <h4 class="pp-section-title"
                    style="display: inline-block; margin-bottom: 1em;"><?php echo esc_html(__('Apple Podcast Episode Artwork', 'powerpress')); ?></h4>
                <div class="pp-tooltip-right">i
                    <span class="text-pp-tooltip"
                          style="top: -150%;"><?php echo esc_html(__('Episode artwork should be square and have dimensions between 1400 x 1400 pixels and 3000 x 3000 pixels.', 'powerpress')); ?></span>
                </div>
                <br/>
                <input type="text" class="pp-ep-box-input" title="<?php echo esc_attr(__("Apple Image URL","powerpress")); ?>"
                       id="powerpress_itunes_image_<?php echo $FeedSlug; ?>"
                       placeholder="<?php echo htmlspecialchars(__('e.g. http://example.com/path/to/image.jpg', 'powerpress')); ?>"
                       name="Powerpress[<?php echo $FeedSlug; ?>][itunes_image]"
                       value="<?php echo esc_attr($itunes_image); ?>"
                       style="font-size: 90%;" size="250" oninput="powerpress_insertArtIntoPreview(this)"/>
                <br/>
                <br/>
                <a href="<?php echo $form_action_url; ?>" class="thickbox powerpress-itunes-image-browser"
                   id="powerpress_itunes_image_browser_<?php echo $FeedSlug; ?>"
                   title="<?php echo esc_attr(__('Select Apple Image', 'powerpress')); ?>">
                    <button class="pp-gray-button"><?php echo esc_html(__('UPLOAD EPISODE ARTWORK', 'powerpress')); ?></button>
                </a>
            </div>
            <div class="powerpress-art-preview">
                <p class="pp-section-subtitle" style="font-weight: bold;"><?php echo esc_html(__('PREVIEW', 'powerpress')); ?></p>
                <img id="pp-image-preview-<?php echo $FeedSlug; ?>"
                     src="<?php echo esc_attr($itunes_image_preview); ?>" alt="No artwork selected"/>
                <p id="pp-image-preview-caption-<?php echo $FeedSlug; ?>" class="pp-section-subtitle"
                   style="font-weight: bold;margin: 3px;"><?php if ($itunes_image) { echo get_filename_from_path(esc_attr($itunes_image)); } ?></p>
            </div>
        </div>
        <div class="ep-box-line-margin"></div>
        <?php }
        if( isset($GeneralSettings['new_episode_box_cover_image']) && $GeneralSettings['new_episode_box_cover_image'] == 1 ) { ?>
        <div id="powerpress_thumbnail_container_<?php echo $FeedSlug; ?>" class="pp-section-container">
            <div class="powerpress-art-text">
                <h4 class="pp-section-title"><?php echo esc_html(__('Thumbnail Image', 'powerpress')); ?></h4>
                <div class="pp-tooltip-right">i
                    <span class="text-pp-tooltip"><?php echo esc_html(__('This artwork only shows up if your podcast media is a video file.', 'powerpress')); ?></span>
                </div>
                <br/> <br/>
                <input type="text" class="pp-ep-box-input" id="powerpress_image_<?php echo $FeedSlug; ?>"
                       name="Powerpress[<?php echo $FeedSlug; ?>][image]" title="<?php echo esc_attr(__("Poster image URL","powerpress")); ?>"
                       value="<?php echo esc_attr($CoverImage); ?>"
                       placeholder="<?php echo htmlspecialchars(__('e.g. http://example.com/path/to/image.jpg', 'powerpress')); ?>"
                       style="font-size: 90%;" size="250" oninput="powerpress_insertArtIntoPreview(this)"/>
                <br/>
                <label class="ep-box-caption"
                       for="powerpress_image_<?php echo $FeedSlug; ?>"><?php echo esc_html(__('Poster image for video (m4v, mp4, ogv, webm, etc..)', 'powerpress')); ?></label>
                <br/>
                <a href="<?php echo $form_action_url; ?>" class="thickbox powerpress-image-browser"
                   id="powerpress_image_browser_<?php echo $FeedSlug; ?>"
                   title="<?php echo esc_attr(__('Select Poster Image', 'powerpress')); ?>">
                    <button class="pp-gray-button"><?php echo esc_html(__('UPLOAD THUMBNAIL', 'powerpress')); ?></button>
                </a>
            </div>
            <div class="powerpress-art-preview">
                <p class="pp-section-subtitle"
                   style="font-weight: bold;"><?php echo esc_html(__('PREVIEW', 'powerpress')); ?></p>
                <img id="poster-pp-image-preview-<?php echo $FeedSlug; ?>"
                     src="<?php echo esc_attr($CoverImage_preview); ?>" alt="No thumbnail selected"/>
                <p id="poster-pp-image-preview-caption-<?php echo $FeedSlug; ?>" class="pp-section-subtitle"
                   style="font-weight: bold;margin: 3px;"><?php if ($CoverImage) { echo get_filename_from_path(esc_attr($CoverImage)); } ?></p>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php
}

function display_tab($FeedSlug, $IsVideo, $NoPlayer, $NoLinks, $Width, $Height, $Embed, $GeneralSettings)
{
    ?>

    <div id="display-<?php echo $FeedSlug; ?>" class="pp-tabcontent">
        <?php if( ( !isset($GeneralSettings['new_episode_box_no_player']) || $GeneralSettings['new_episode_box_no_player'] == 1) || (!isset($GeneralSettings['new_episode_box_no_links']) || $GeneralSettings['new_episode_box_no_links'] == 1) ) { ?>
        <div id="pp-display-player-<?php echo $FeedSlug; ?>" class="pp-section-container">
            <h4 class="pp-section-title-block"><?php echo esc_html(__('Episode Player', 'powerpress')); ?></h4>
            <?php if( !isset($GeneralSettings['new_episode_box_no_player']) || $GeneralSettings['new_episode_box_no_player'] == 1) { ?>
            <p style="font-size: 14px;" class="pp-ep-box-text">
                <input id="powerpress_no_player_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Do not display player","powerpress")); ?>"
                                                  class="ep-box-checkbox"
                                                  name="Powerpress[<?php echo $FeedSlug; ?>][no_player]" value="1"
                                                  type="checkbox" <?php echo($NoPlayer == 1 ? 'checked' : ''); ?> />
                <?php echo esc_html(__('Do not display player', 'powerpress')); ?>
            </p>
            <br/><?php }
            if (!isset($GeneralSettings['new_episode_box_no_links']) || $GeneralSettings['new_episode_box_no_links'] == 1) { ?>
            <p style="font-size: 14px; margin-top: 1ch;" class="pp-ep-box-text">
                <input id="powerpress_no_links_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Do not display media links","powerpress")); ?>"
                                                  class="ep-box-checkbox"
                                                  name="Powerpress[<?php echo $FeedSlug; ?>][no_links]" value="1"
                                                  type="checkbox" <?php echo($NoLinks == 1 ? 'checked' : ''); ?> />
                <?php echo esc_html(__('Do not display media links', 'powerpress')); ?>
            </p>
            <?php } ?>
        </div>

        <div id="line-above-player-size-<?php echo $FeedSlug; ?>" class="ep-box-line"></div>
        <?php } //Setting for audio player size

        if (!isset($GeneralSettings['new_episode_box_player_size']) || $GeneralSettings['new_episode_box_player_size'] == 1) { ?>
        <div id="pp-player-size-<?php echo $FeedSlug; ?>" class="pp-section-container">
            <h4 class="pp-section-title" style="width: 100%;"><?php echo esc_html(__('Video Player Size', 'powerpress')); ?></h4>
            <div class="powerpress-label-container">
                <input type="text" id="powerpress_episode_player_width_<?php echo $FeedSlug; ?>" title="<?php echo esc_attr(__("Player width","powerpress")); ?>"
                       class="pp-ep-box-input" placeholder="<?php echo htmlspecialchars(__('Width', 'powerpress')); ?>"
                       name="Powerpress[<?php echo $FeedSlug; ?>][width]" value="<?php echo esc_attr($Width); ?>"
                       style="width: 40%;font-size: 90%;" size="5"/>
                x
                <input type="text" id="powerpress_episode_player_height_<?php echo $FeedSlug; ?>"
                       class="pp-ep-box-input"
                       placeholder="<?php echo htmlspecialchars(__('Height', 'powerpress')); ?>" title="<?php echo esc_attr(__("Player height","powerpress")); ?>"
                       name="Powerpress[<?php echo $FeedSlug; ?>][height]" value="<?php echo esc_attr($Height); ?>"
                       style="width: 40%; font-size: 90%;" size="5"/>
            </div>
        </div>
        <div class="ep-box-line"></div>
        <?php } ?>
        <div id="player-shortcode-<?php echo $FeedSlug; ?>" class="pp-section-container">
            <h4 class="pp-section-title-block"><?php echo esc_html(__('Display Player Anywhere on Page', 'powerpress')); ?></h4>
            <div style="display:inline-block"><p class="pp-shortcode-example" style="font-weight: bold;">
                    [<?php echo __('powerpress'); ?>]</p></div>
            <p class="pp-section-subtitle"><?php echo esc_html(__('Just copy and paste this shortcode anywhere in your page content. ', 'powerpress')); ?>
                <a href="https://support.wordpress.com/shortcodes/"
                   target="_blank"><?php echo esc_html(__('Learn more about shortcodes here.', 'powerpress')); ?></a></p>
        </div>
        <div class="ep-box-line"></div>
        <?php //Setting for media embed
         if( !isset($GeneralSettings['new_episode_box_embed']) || $GeneralSettings['new_episode_box_embed'] == 1 ) {
        ?>
        <div class="pp-section-container">
            <h4 class="pp-section-title"><?php echo esc_html(__('Media Embed', 'powerpress')); ?></h4>
            <div class="pp-tooltip-right">i
                <span class="text-pp-tooltip"
                      style="top: -50%;"><?php echo esc_html(__('Here, you can enter a link to embed a media player.', 'powerpress')); ?></span>
            </div>
            <div class="powerpress_row_content">
                <textarea class="pp-ep-box-input" id="powerpress_embed_<?php echo $FeedSlug; ?>"
                          name="Powerpress[<?php echo $FeedSlug; ?>][embed]" title="<?php echo esc_attr(__("Media Embed","powerpress")); ?>"
                          style="font-size: 14px; width: 95%; height: 80px;"
                          onfocus="this.select();"><?php echo esc_textarea($Embed); ?></textarea>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php
}

function notes_tab($FeedSlug, $object, $GeneralSettings)
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
                            jQuery('#powerpress_warning_' + FeedSlug).text('<?php echo esc_js(__('Operation timed out.', 'powerpress')); ?>');
                        else if (errorMsg)
                            jQuery('#powerpress_warning_' + FeedSlug).text('<?php echo esc_js(__('AJAX Error', 'powerpress')) . ': '; ?>' + errorMsg);
                        else if (strError != null)
                            jQuery('#powerpress_warning_' + FeedSlug).text('<?php echo esc_js(__('AJAX Error', 'powerpress')) . ': '; ?>' + strError);
                        else
                            jQuery('#powerpress_warning_' + FeedSlug).text('<?php echo esc_js(__('AJAX Error', 'powerpress')) . ': ' . esc_html(__('Unknown', 'powerpress')); ?>');
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
            <h4 class="pp-section-title-block"> <?php echo esc_html(__('Meta Marks', 'powerpress')); ?> </h4>
            <div class="powerpress_row_content">
                <input type="hidden" name="Null[powerpress_metamarks_counter_<?php echo $FeedSlug ?>]"
                       id="powerpress_metamarks_counter_<?php echo $FeedSlug ?>"
                       value="<?php echo count($MetaRecords) ?>"/>
                <input style="cursor:pointer;" type="button" id="powerpress_check_<?php echo $FeedSlug ?>_button"
                       name="powerpress_check_<?php echo $FeedSlug ?>_button" title="<?php echo esc_attr(__("Add Meta Mark","powerpress")); ?>"
                       value="<?php echo esc_attr(__('Add Meta Mark', 'powerpress')) ?>"
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

<h3 class="media-title"><?php echo esc_html(__('Select poster image from your computer.', 'powerpress')); ?></h3>

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