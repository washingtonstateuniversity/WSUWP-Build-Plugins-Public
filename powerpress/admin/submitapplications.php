<?php
?>
<h1 class="pageTitlte">Network: <?php echo esc_html($networkInfo['network_title']); ?></h1><br>
<small>Submit your application</small><br><br><br>
<form id ='createForm' method="POST">
    <label for="feedUrl"><b>Feed URL</b></label><br>
    <input type="url" id ="feedUrl" name="feedUrl" placeholder="Type in the URL feed you want to add" <?php if (isset($props['program_rssurl'])) echo 'readonly value=\''.esc_html($props['program_rssurl']).'\' ';?>><br>
    <button id="findProgram" onclick="directStatus('Submit App', 'createForm', true)">Find</button>
    <a class="warningButton" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=List+Applicants"); ?>">
    <p><?php echo '&#8592 ' . esc_html(__('Cancel', 'powerpress-network'));?></p></a>
    <div id="addInfo">
        <label for="listIdForApp"><b>List</b></label><br>
        <select name="listIdForApp">
            <?php

            for ($i = 0; $i < count($props['list']); ++$i){
            ?>
            <option value = <?php echo esc_html($props['list'][$i]['list_id']); ?>> <?php echo esc_html($props['list'][$i]['list_title']); ?>
                <?php
                }
                ?>
        </select><br>
        <label for="appLabel"><b>App Label</b></label>
        <input id="appLabel" name="appLabel" type="text" value=""><br>
        <input name="programIdForApp" value="<?php echo esc_html($props['program_id']);?>" hidden disable>
        <button type="submit" id="saveButton" onclick ="directStatus('List Applicants', 'createForm', true)">Apply</button>
        <a class="warningButton" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=List+Applicants"); ?>">
        <p><?php echo __('&#8592; Cancel', 'powerpress-network');?></p></a>
    </div>
</form>

<script>
    if (!jQuery(function($){ $('#feedUrl').is('[readonly]')}))
        jQuery(function($){ $('#addInfo').remove()});
    else {
        jQuery(function($){ $('#findProgram').remove()});
        jQuery(function($){ $('#backProgram').remove()});
    }
</script>


