<h1 class="pageTitle"><?php echo esc_html(__('Edit List', 'powerpress-network'));?></h1>
<form method="POST" action="#/" id="createForm"> <!-- Make sure to keep back slash there for WordPress -->
    <label for="oldListTitle"><b><?php echo esc_html(__('Old List Title', 'powerpress-network'));?></b></label>
    <input id="oldListTitle" name="oldListTitle" type="text" value="<?php echo esc_html($networkInfo['list_title']); ?>" disabled><br>
    <label for="editListTitle"><b><?php echo esc_html(__('New List Title', 'powerpress-network'));?></b></label>
    <input id="editListTitle" name="editListTitle" type="text" value="<?php echo esc_html($networkInfo['list_title']); ?>"><br>
    <label for="listDescription"><b><?php echo esc_html(__('Old List Description', 'powerpress-network'));?></b></label>
    <textarea id ="listDescription" class="description" name="listDescription" rows ="3" type="text" disabled><?php echo esc_html($networkInfo['list_description']);?></textarea><br>
    <label for="editListDescription"><b><?php echo esc_html(__('New List Description', 'powerpress-network'));?></b></label>
    <textarea id ="editListDescription" class="description" name="editListDescription" rows ="3" type="text"><?php echo esc_html($networkInfo['list_description']);?></textarea><br>
    <button type="submit" class="primaryButton" onclick="directStatus('List Lists', 'createForm', true)"><?php echo esc_html(__('Edit', 'powerpress-network'));?></button>
    <a class="ppn-back-button" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=List+Lists"); ?>">
    <p><?php echo '&#8592; ' . esc_html(__('Back', 'powerpress-network'));?></p></a>
</form>
