<?php

function powerpressadmin_edit_entry_options($General)
{
    //powerpress_admin_ep_settings_enqueue_scripts();
    if( !isset($General['default_url']) )
        $General['default_url'] = '';
    if( !isset($General['episode_box_mode']) )
        $General['episode_box_mode'] = 0; // Default not set, 1 = no duration/file size, 2 = yes duration/file size (default if not set)
    if( !isset($General['episode_box_embed']) )
        $General['episode_box_embed'] = 0;
    if( !isset($General['set_duration']) )
        $General['set_duration'] = 0;
    if( !isset($General['set_size']) )
        $General['set_size'] = 0;
    if( !isset($General['auto_enclose']) )
        $General['auto_enclose'] = 0;
    if( !isset($General['episode_box_player_size']) )
        $General['episode_box_player_size'] = 0;
    if( !isset($General['episode_box_closed_captioned']) )
        $General['episode_box_closed_captioned'] = 0;
    if( !isset($General['episode_box_order']) )
        $General['episode_box_order'] = 0;
    if( !isset($General['episode_box_feature_in_itunes']) )
        $General['episode_box_feature_in_itunes'] = 0;

    require_once(dirname(__FILE__) . "/../powerpressadmin-epbox-options.php");
    ?>
    <script language="javascript">
        jQuery(document).ready(function() {
            jQuery("body").css("background-color", "white");
            jQuery("body").css("font-family", "Roboto, sans-serif");
        });
    </script>
    <div class="wrap" id="powerpress_settings">
    <form enctype="multipart/form-data" method="POST" action="<?php echo admin_url( 'admin.php'); ?>?action=powerpress-ep-box-options-save"&amp;KeepThis=true&amp;TB_iframe=true&amp;width=600&amp;height=400&amp;modal=false">
        <?php wp_nonce_field('powerpress-edit');
        echo "<div id=\"tab-container-epbox-settings\">";
            echo "<div class=\"pp-tab\" style='border-top: none;'>";
                $titles = array("main" => esc_attr(__("Episode Entry Options", "powerpress")), "permalinks" => esc_attr(__("Permalinks", "powerpress")), "advanced" => esc_attr(__("Advanced Options", "powerpress")));
                echo "<button style='font-size: 80%;width: 25%;' class=\"tablinks active\" id=\"1\" title='{$titles['main']}' onclick=\"powerpress_openTab(event, 'epbox-main')\" >" . esc_html(__($titles['main'], 'powerpress')) . "</button>";
                echo "<button style='font-size: 80%;' class=\"tablinks\" id=\"2\" title='{$titles['permalinks']}' onclick=\"powerpress_openTab(event, 'epbox-permalinks')\">" . esc_html(__($titles['permalinks'], 'powerpress')) . "</button>";
                echo "<button style='font-size: 80%;' class=\"tablinks\" id=\"3\" title='{$titles['advanced']}' onclick=\"powerpress_openTab(event, 'epbox-advanced')\">" . esc_html(__($titles['advanced'], 'powerpress')) . "</button>";
                echo "</div>";
        ?>
            <div id="epbox-main" class="pp-tabcontent active">
                <?php powerpress_epbox_main_tab($General); ?>
            </div>
            <div id="epbox-permalinks" class="pp-tabcontent">
                <?php powerpress_epbox_permalinks_tab($General); ?>
            </div>
            <div id="epbox-advanced" class="pp-tabcontent">
                <?php powerpress_epbox_advanced_tab($General); ?>
            </div>
        </div>
        <p class="submit">
            <input style="margin-left: 30px;" type="submit" name="Submit" id="powerpress_save_button" class="button-primary button-blubrry" value="<?php echo __('Save Changes', 'powerpress') ?>" />
        </p>
    </form>
    <?php
}

