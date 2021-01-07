<h1 class="pageTitle"><?php echo esc_html(__('Create New List', 'powerpress-network'));?></h1>
<form method="POST" id="createForm" action="#/"> <!-- Make sure to keep back slash there for WordPress -->
    <label for="newListTitle"><b><?php echo esc_html(__('New List Title', 'powerpress-network'));?></b></label>
    <input id ="newListTitle" name="newListTitle" type="text"><br>
    <label for="newListDescription"><b><?php echo esc_html(__('New List Description', 'powerpress-network'));?></b></label>
    <textarea id ="newListDescription" name="newListDescription" class="description" type="text" rows="3"></textarea><br>
    <button class="ppn-back-button" onclick="directStatus('List Lists', 'createForm', true)" type="submit"><?php echo esc_html(__('Create', 'powerpress-network'));?></button>
    <a class="ppn-back-button" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=List+Lists"); ?>">
    <p><?php echo esc_html(__('&#8592; Back', 'powerpress-network'));?></p></a>
</form>