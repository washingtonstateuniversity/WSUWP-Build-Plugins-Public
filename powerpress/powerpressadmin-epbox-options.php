<?php

function powerpress_epbox_main_tab($General) {
    ?>
    <script>
        function SelectEmbedField(checked)
        {
            if( checked )
                jQuery('#embed_replace_player').removeAttr("disabled");
            else
                jQuery('#embed_replace_player').attr("disabled","disabled");
        }
    </script>
    <h4 class="pp-section-title-block"><?php echo __('Episode Entry Options', 'powerpress'); ?></h4>
    <br />
    <div class="pp-section-container" style="margin: 2em 0 0 2em;">

        <input id="episode_box_flag" class="ep-box-checkbox" name="General[new_episode_box_flag]" type="hidden" value="1" />


        <p class="pp-ep-box-text"><b><?php echo __('Podcast Entry Box', 'powerpress'); ?></b></p>

        <p class="pp-ep-box-settings-text" style="margin-top: 1ch;">
            <?php echo __('Configure your podcast episode entry box with the options that fit your needs.', 'powerpress'); ?>
        </p>
        <div class="ep-box-line-margin-bold" style="width: 100%;"></div>
        <div id="episode_box_mode_adv">

            <p class="pp-ep-box-settings-text" style="margin-top: 0;"><input class="ep-box-checkbox" name="Null[ignore]" type="checkbox" value="1" checked onclick="return false" onkeydown="return false" disabled /> <?php echo __('Media URL', 'powerpress'); ?>
                (<?php echo __('Specify URL to episode\'s media file', 'powerpress'); ?>)</p>
            <!--
                        <p style="margin-top: 3ch;"><input id="episode_box_mode" class="ep-box-checkbox" name="General[new_episode_box_mode]" type="checkbox" value="2" <?php //if( empty($General['new_episode_box_mode']) || $General['new_episode_box_mode'] != 1 ) echo ' checked'; ?> /> <?php //echo __('Media File Size and Duration', 'powerpress'); ?>
                            (<?php //echo __('Specify episode\'s media file size and duration', 'powerpress'); ?>)</p>
                        -->
            <p class="pp-ep-box-settings-text" style="margin-top: 3ch; margin-bottom: 0;"><input id="episode_box_embed" class="ep-box-checkbox" name="General[new_episode_box_embed]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_embed']) || $General['new_episode_box_embed'] == 1 ) echo ' checked'; ?> onclick="SelectEmbedField(this.checked);"  /> <?php echo __('Embed Field', 'powerpress'); ?>
                (<?php echo __('Enter embed code from sites such as YouTube', 'powerpress'); ?>)</p>
            <p class="pp-ep-box-settings-text" style="margin-top: 1.5ch; margin-left: 6.5ch; font-size: 85%;"><input id="embed_replace_player" class="ep-box-checkbox" name="General[new_embed_replace_player]" type="checkbox" value="1"<?php if( !isset($General['embed_replace_player']) || $General['embed_replace_player'] == 1 ) echo ' checked'; ?> /> <?php echo __('Replace Player with Embed', 'powerpress'); ?>
                (<?php echo __('Do not display default player if embed present for episode.', 'powerpress'); ?>)</p>

            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><input id="episode_box_player_links_options" class="ep-box-checkbox" name="NULL[new_episode_box_player_links_options]" type="checkbox" value="1" checked disabled /> <?php echo __('Display Player and Links Options', 'powerpress'); ?>
            </p>
            <div id="episode_box_player_links_options_div" style="margin-left: 3em;">

                <p class="pp-ep-box-settings-text" style="margin-top: 10px;  margin-bottom: 5px;"><input id="episode_box_no_player" class="ep-box-checkbox episode_box_no_player_or_links" name="General[new_episode_box_no_player]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_no_player']) || $General['new_episode_box_no_player'] == 1) echo ' checked'; ?> /> <?php echo __('No Player Option', 'powerpress'); ?>
                    (<?php echo __('Disable media player on a per episode basis', 'powerpress'); ?>)</p>

                <p class="pp-ep-box-settings-text" style="margin-top: 10px;  margin-bottom: 20px;"><input id="episode_box_no_links" class="ep-box-checkbox episode_box_no_player_or_links" name="General[new_episode_box_no_links]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_no_links']) || $General['new_episode_box_no_links'] == 1 ) echo ' checked'; ?> /> <?php echo __('No Links Option', 'powerpress'); ?>
                    (<?php echo __('Disable media links on a per episode basis', 'powerpress'); ?>)</p>

            </div>

            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><input id="episode_box_cover_image" class="ep-box-checkbox" name="General[new_episode_box_cover_image]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_cover_image']) || $General['new_episode_box_cover_image'] == 1 ) echo ' checked'; ?> /> <?php echo __('Poster Image', 'powerpress'); ?>
                (<?php echo __('Specify URL to poster artwork specific to each episode', 'powerpress'); ?>)</p>

            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><input id="episode_box_player_size" class="ep-box-checkbox" name="General[new_episode_box_player_size]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_player_size']) || $General['new_episode_box_player_size'] == 1 ) echo ' checked'; ?> /> <?php echo __('Player Width and Height', 'powerpress'); ?>
                (<?php echo __('Customize player width and height on a per episode basis', 'powerpress'); ?>)</p>
            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><input id="episode_box_subtitle" class="ep-box-checkbox" name="General[new_episode_box_subtitle]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_subtitle']) || $General['new_episode_box_subtitle'] == 1 ) echo ' checked'; ?> /> <?php echo __('Apple Podcast Subtitle Field', 'powerpress'); ?>
                (<?php echo __('Leave unchecked to use the first 250 characters of your blog post', 'powerpress'); ?>)</p>
            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><input id="episode_box_summary" class="ep-box-checkbox" name="General[new_episode_box_summary]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_summary']) || $General['new_episode_box_summary'] == 1 ) echo ' checked'; ?> /> <?php echo __('Apple Podcast Summary Field', 'powerpress'); ?>
                (<?php echo __('Leave unchecked to use your blog post', 'powerpress'); ?>)</p>



            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><input id="episode_box_author" class="ep-box-checkbox" name="General[new_episode_box_author]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_author']) || $General['new_episode_box_author'] == 1 ) echo ' checked'; ?> /> <?php echo __('Apple Podcast Author Field', 'powerpress'); ?>
                (<?php echo __('Leave unchecked to the post author name', 'powerpress'); ?>)</p>

            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><input id="episode_box_explicit" class="ep-box-checkbox" name="General[new_episode_box_explicit]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_explicit']) || $General['new_episode_box_explicit'] == 1 ) echo ' checked'; ?> /> <?php echo __('Apple Podcast Explicit Field', 'powerpress'); ?>
                (<?php echo __('Leave unchecked to use your feed\'s explicit setting', 'powerpress'); ?>)</p>


            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><label><input id="episode_box_itunes_image" class="ep-box-checkbox" name="General[new_episode_box_itunes_image]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_itunes_image']) || $General['new_episode_box_itunes_image'] == 1 ) echo ' checked'; ?> /> <?php echo __('Apple Podcast Episode Image Field', 'powerpress'); ?></label>
                (<?php echo __('Leave unchecked to use the image embedded into your media files.', 'powerpress'); ?>)</p>

            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><label><input id="episode_box_order" class="ep-box-checkbox" name="General[new_episode_box_order]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_order']) || $General['new_episode_box_order'] == 1 ) echo ' checked'; ?> <?php if( !isset($General['new_episode_box_feature_in_itunes']) || $General['new_episode_box_feature_in_itunes'] == 1 ) echo ' checked disabled'; ?> /> <?php echo __('Apple Podcast Order', 'powerpress'); ?></label>
                (<?php echo __('Override the default ordering of episodes on the Apple and Google Podcast directories', 'powerpress'); ?>)</p>
            <p class="pp-ep-box-settings-text" style="margin: 0 0 0 3em;"><em><?php echo __('If conflicting values are present the directories will use the default ordering.', 'powerpress'); ?><br />
            <?php echo __('This feature only applies to the default podcast feed and Custom Podcast Channel feeds added by PowerPress.', 'powerpress'); ?></em></p>

            <p class="pp-ep-box-settings-text"><label><!--<select name="General[ebititle]" class="bpp_input_sm">
                        <?php
                        $linkoptions = array('false'=>__('Hide Field', 'powerpress'),
                            1=>__('Show Field', 'powerpress') );

                        foreach( $linkoptions as $value => $desc )
                            echo "\t<option value=\"$value\"". ( ( ( !empty($General['new_episode_box_itunes_title']) && $General['new_episode_box_itunes_title'] == $value ) ||  ( !isset($General['new_episode_box_itunes_title']) && $value == 1 ) ) ?' selected':''). ">$desc</option>\n";

                        ?>
                    </select>--> <input id="episode_box_itunes_title" class="ep-box-checkbox" name="General[new_episode_box_itunes_title]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_itunes_title']) || $General['new_episode_box_itunes_title'] == 1 ) echo ' checked'; ?> /> <?php echo __('Apple Podcast Episode Title Field', 'powerpress'); ?></label> </p>
            <p class="pp-ep-box-settings-text" style="margin: 0 0 0 3em;"><em><?php echo __('Specify Apple Podcast episode title separate from podcast feed title.', 'powerpress'); ?></em></p>


            <p class="pp-ep-box-settings-text"><label><!--<select name="General[ebinst]" class="bpp_input_sm">
                        <?php
                        $linkoptions = array('false'=>__('Hide Field', 'powerpress'),
                            1=>__('Show Field', 'powerpress') );

                        foreach( $linkoptions as $value => $desc )
                            echo "\t<option value=\"$value\"". ( ( ( !empty($General['new_episode_box_itunes_nst']) && $General['new_episode_box_itunes_nst'] == $value ) ||  ( !isset($General['new_episode_box_itunes_title']) && $value == 1 ) ) ?' selected':''). ">$desc</option>\n";

                        ?>
                    </select>--><input id="episode_box_itunes_nst" class="ep-box-checkbox" name="General[new_episode_box_itunes_nst]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_itunes_nst']) || $General['new_episode_box_itunes_nst'] == 1 ) echo ' checked'; ?> /> <?php echo __('Apple Podcast Episode Number, Season and Type Fields', 'powerpress'); ?></label> </p>
            <p class="pp-ep-box-settings-text" style="margin: 0 0 0 3em;"><em><?php echo __('Enter specifics about episode including episode number, season number and type (full, trailer, or bonus).', 'powerpress'); ?></em></p>

            <p class="pp-ep-box-settings-text" style="margin-top: 3ch;"><label><input id="episode_box_feature_in_itunes" class="ep-box-checkbox" name="General[new_episode_box_feature_in_itunes]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_feature_in_itunes']) || $General['new_episode_box_feature_in_itunes'] == 1 ) echo ' checked'; ?> /> <?php echo __('Feature Episode in Apple and Google Podcasts', 'powerpress'); ?></label>
                (<?php echo __('Display selected episode at top of your show\'s listings', 'powerpress'); ?>)</p>
            <p class="pp-ep-box-settings-text" style="margin: 0 0 0 3em;"><em><?php echo __('All other episodes will be listed following the featured episode.', 'powerpress'); ?></em><br />
                <em><?php echo __('This feature only applies to the default podcast feed and Custom Podcast Channel feeds added by PowerPress.', 'powerpress'); ?></em></p>

            <!--
                        <p style="margin-top: 3ch;"><input id="episode_box_gp_desc" class="ep-box-checkbox" name="General[new_episode_box_gp_desc]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_gp_desc']) || $General['new_episode_box_gp_desc'] == 1 ) echo ' checked'; ?> /> <?php echo __('Google Play Description Field', 'powerpress'); ?>
                            (<?php echo __('Leave unchecked to use your blog post', 'powerpress'); ?>)</p>
                        <p style="margin-top: 3ch;"><input id="episode_box_gp_explicit" class="ep-box-checkbox" name="General[new_episode_box_gp_explicit]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_gp_explicit']) || $General['new_episode_box_gp_explicit'] == 1 ) echo ' checked'; ?> /> <?php echo __('Google Play Explicit Field', 'powerpress'); ?>
                            (<?php echo __('Leave unchecked to use your feed\'s explicit setting', 'powerpress'); ?>)</p>
                        -->
        </div>
    </div>
    <script language="javascript"><!--
        SelectEmbedField(<?php echo $General['new_episode_box_embed']; ?>);
        //-->
    </script>


<?php }

function powerpress_epbox_permalinks_tab($General) {
    global $wp_rewrite;
    if( $wp_rewrite->permalink_structure ) // Only display if permalinks is enabled in WordPress
    {
        ?>
        <h4 class="pp-section-title-block"><?php echo __('Permalinks', 'powerpress'); ?></h4>
        <br />
        <div class="pp-section-container" style="margin: 2em 0 0 2em;">
            <p class="pp-ep-box-text"><b><?php echo __('Podcast Permalinks', 'powerpress'); ?></b></p>
            <div class="ep-box-line-margin-bold" style="width: 100%;"></div>

            <select name="General[permalink_feeds_only]" class="pp-ep-box-input" style="width: 60%; height: 42px;">
                <?php
                $options = array(0=>__('Default WordPress Behavior', 'powerpress'), 1=>__('Match Feed Name to Page/Category', 'powerpress') );
                $current_value = (!empty($General['permalink_feeds_only'])?$General['permalink_feeds_only']:0);

                foreach( $options as $value => $desc )
                    echo "\t<option value=\"$value\"". ($current_value==$value?' selected':''). ">$desc</option>\n";

                ?>
            </select>
            <p class="pp-ep-box-settings-text" style="margin-top: 1ch;"><?php echo sprintf(__('When configured, %s/podcast/ is matched to page/category named \'podcast\'.', 'powerpress'), get_bloginfo('url') ); ?></p>

        </div>
        <?php
    }
    ?>

<?php }

function powerpress_epbox_advanced_tab($General) {
    $DefaultMediaURL = false;
    if( !empty($General['default_url']) )
        $DefaultMediaURL = true;

    ?>
    <h4 class="pp-section-title-block"><?php echo __('Advanced Options', 'powerpress'); ?></h4>
    <br />

    <div class="pp-section-container" style="margin: 3em 0 0 2em;">
        <p class="pp-ep-box-text"><b><?php echo __('Episode Box Appearance', 'powerpress'); ?></b></p>
        <div class="ep-box-line-margin-bold" style="width: 100%;"></div>

        <p class="pp-ep-box-settings-text" style="margin: 0;"><label><input type="checkbox" class="ep-box-checkbox" name="General[skip_to_episode_settings]" value="2" <?php if( isset($General['skip_to_episode_settings']) && $General['skip_to_episode_settings'] ) echo 'checked '; ?>/> <?php echo __('Skip Media Verification', 'powerpress'); ?></label></p>
        <p class="pp-ep-box-settings-text" style="margin: 0 0 0 3em;"><em><?php echo __('Check this box to display all episode settings before the media is verified. If this setting is selected, you risk losing all your entered data if you try to publish an episode that has no media attached. This setting should only be enabled by very experienced PowerPress users.', 'powerpress'); ?></em></p>

    </div>
    <div class="pp-section-container" style="margin: 3em 0 0 2em;">
        <p class="pp-ep-box-text"><b><?php echo __('Block Feed', 'powerpress'); ?></b></p>
        <div class="ep-box-line-margin-bold" style="width: 100%;"></div>
        <div id="advanced_basic_options">
            <p class="pp-ep-box-settings-text" style="margin: 0;"><label><input id="episode_box_block" class="ep-box-checkbox" name="General[new_episode_box_block]" type="checkbox" value="1"<?php if( !isset($General['new_episode_box_block']) || $General['new_episode_box_block'] == 1 ) echo ' checked'; ?> /> <?php echo __('Apple Podcast Block', 'powerpress'); ?> (<?php echo htmlspecialchars('<itunes:block>yes</itunes:block>'); ?>)</label></p>
            <p class="pp-ep-box-settings-text" style="margin: 0 0 0 3em;"><em><?php echo __('Prevent episodes from appearing in Apple Podcast and other diretories that support the iTunes:block tag. Episodes may still appear in other directories and applications.', 'powerpress'); ?></em></p>
        </div>
    </div>

    <?php if( $DefaultMediaURL || defined('POWERPRESS_DEFAULT_MEDIA_URL') ) { ?>
        <div class="pp-section-container" style="margin: 3em 0 0 2em;">
            <p class="pp-ep-box-text"><b><?php echo __('Default Media URL', 'powerpress'); ?></b></p>
            <div class="ep-box-line-margin-bold" style="width: 100%;"></div>
            <input type="text" style="width: 60%; height: 42px;" name="General[default_url]" value="<?php echo esc_attr($General['default_url']); ?>" maxlength="255" />
            <p class="pp-ep-box-settings-text" style="margin-top: 1ch;"><?php echo __('e.g. http://example.com/mediafolder/', 'powerpress'); ?></p>
            <p class="pp-ep-box-settings-text" style="margin-top: 0;"><?php echo __('URL above will prefix entered file names that do not start with \'http://\'. URL above must end with a trailing slash. You may leave blank if you always enter the complete URL to your media when creating podcast episodes.', 'powerpress'); ?>
            </p>

        </div>
    <?php } ?>

    <div class="pp-section-container" style="margin: 3em 0 0 2em;">
        <p class="pp-ep-box-text"><b><?php echo __('File Size Default', 'powerpress'); ?></b></p>
        <div class="ep-box-line-margin-bold" style="width: 100%;"></div>
        <select name="General[set_size]" class="pp-ep-box-input" style="width: 60%; height: 42px;">
            <?php
            $options = array(0=>__('Auto detect file size', 'powerpress'), 1=>__('User specify', 'powerpress') );

            foreach( $options as $value => $desc )
                echo "\t<option value=\"$value\"". ($General['set_size']==$value?' selected':''). ">$desc</option>\n";

            ?>
        </select>
        <p class="pp-ep-box-settings-text" style="margin-top: 1ch;">(<?php echo __('specify default file size option when creating a new episode', 'powerpress'); ?>)</p>
    </div>

    <div class="pp-section-container" style="margin: 3em 0 0 2em;">
        <p class="pp-ep-box-text"><b><?php echo __('Duration Default', 'powerpress'); ?></b></p>
        <div class="ep-box-line-margin-bold" style="width: 100%;"></div>
        <select name="General[set_duration]" class="pp-ep-box-input" style="width: 60%; height: 42px;">
            <?php
            $options = array(0=>__('Auto detect duration', 'powerpress'), 1=>__('User specify', 'powerpress'), -1=>__('Not specified (not recommended)', 'powerpress') );

            foreach( $options as $value => $desc )
                echo "\t<option value=\"$value\"". ($General['set_duration']==$value?' selected':''). ">$desc</option>\n";

            ?>
        </select>
        <p class="pp-ep-box-settings-text" style="margin-top: 1ch;">(<?php echo __('specify default duration option when creating a new episode', 'powerpress'); ?>)</p>
    </div>

    <div class="pp-section-container" style="margin: 3em 0 0 2em;">
        <p class="pp-ep-box-text"><b><?php echo __('Auto Add Media', 'powerpress'); ?></b></p>
        <div class="ep-box-line-margin-bold" style="width: 100%;"></div>
        <select name="General[auto_enclose]" class="pp-ep-box-input" style="width: 60%; height: 42px;">
            <?php
            $options = array(0=>__('Disabled (default)', 'powerpress'), 1=>__('First media link found in post content', 'powerpress'), 2=>__('Last media link found in post content', 'powerpress') );

            foreach( $options as $value => $desc )
                echo "\t<option value=\"$value\"". ($General['auto_enclose']==$value?' selected':''). ">$desc</option>\n";

            ?>
        </select>
        <p class="pp-ep-box-settings-text" style="margin-top: 1ch;"><?php echo __('When enabled, the first or last media link found in the post content is automatically added as your podcast episode.', 'powerpress'); ?></p>
        <p class="pp-ep-box-settings-text" style="margin-top: 0;"><em><?php echo __('NOTE: Use this feature with caution. Links to media files could unintentionally become podcast episodes.', 'powerpress'); ?></em></p>
        <p class="pp-ep-box-settings-text" style="margin-top: 0;"><em><?php echo __('WARNING: Episodes created with this feature will <u>not</u> include Duration (total play time) information.', 'powerpress'); ?></em></p>
    </div>

    <div class="pp-section-container" style="margin: 3em 0 0 2em;">
        <p class="pp-ep-box-text"><b><?php echo __('Disable Warnings', 'powerpress'); ?></b></p>
        <div class="ep-box-line-margin-bold" style="width: 100%;"></div>
        <select name="General[hide_warnings]" class="pp-ep-box-input" style="width: 60%; height: 42px;">
            <?php
            $options = array(0=>__('No (default)', 'powerpress'), 1=>__('Yes', 'powerpress') );
            $current_value = (!empty($General['hide_warnings'])?$General['hide_warnings']:0);
            foreach( $options as $value => $desc )
                echo "\t<option value=\"$value\"". ($current_value==$value?' selected':''). ">$desc</option>\n";

            ?>
        </select>
        <p class="pp-ep-box-settings-text" style="margin-top: 1ch;"><?php echo __('Disable warning messages displayed in episode entry box. Errors are still displayed.', 'powerpress'); ?></p>
    </div>

<?php } ?>
