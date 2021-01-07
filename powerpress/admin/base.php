<h1 class="pageTitle"><?php echo esc_html(__('Network:', 'powerpress-network'));?> <?php echo esc_html(get_option('powerpress_network_title') ); ?></h1>
<small><?php echo esc_html(__('Customize your network with the following options below','powerpress-network'));?></small><br>

<ul class="mainChoiceList">
    <li class="material-list"><a href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=List+Programs"); ?>"><h3 class="baseChoice"><?php echo esc_html(__('Programs', 'powerpress-network'));?></h3></a> <p><?php echo esc_html(__('Manage the programs in your network by creating or editing their pages', 'powerpress-network'));?></p></li>
    <li class="material-list"><a href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=List+Lists"); ?>" ><h3 class="baseChoice"><?php echo esc_html(__('Lists', 'powerpress-network'));?></h3></a><p><?php echo esc_html(__('Manage the lists in your network by controlling which programs are in them', 'powerpress-network'));?></p></li>
    <li class="material-list"><a href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=List+Applicants"); ?>"><h3 class="baseChoice"><?php echo esc_html(__('Applicants', 'powerpress-network'));?></h3></a><p><?php echo esc_html(__('Moderate your network by reviewing programs who have applied to join', 'powerpress-network'));?></p></li>
</ul>

<div class="unlinkNetwork" id="unlinkNetwork" style="display: none;">
    <form method='POST' id="choiceForm" action="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) .""); ?>"> <!-- Make sure to keep back slash there for WordPress -->
		<input type="hidden" name="ppn-action" value="unset-network-id" />
        <h2 class="thickboxTitle"><?php echo esc_html(__('Confirm the unlinking of your Network', 'powerpress-network')); ?></h2>
        <button class="warningButton" type="submit"><?php echo esc_html(__('Unlink Network', 'powerpress-network'));?></button>
    </form>
</div>
<a href="#TB_inline?&width=600&height=200&inlineId=unlinkNetwork" class="thickbox" title="Powerpress Network plugin"><button class="warningButton"><?php echo esc_html(__('Unlink Network', 'powerpress-network'));?></button></a>

<button type="button" class="cacheButton" onclick="refreshAndCallDirectAPI('Select Choice', 'manageForm')"><?php echo esc_html(__('Clear site cache', 'powerpress-network'));?></button>
