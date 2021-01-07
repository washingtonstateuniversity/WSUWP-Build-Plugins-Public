<h1 class="pageTitle"><?php echo esc_html(__('Your Networks', 'powerpress-network'));?></h1>
<small><?php echo esc_html(__('Choose a network you want to edit', 'powerpress_network'));?></small><br><br><br>
<?php if (!empty($props)) {
    ?>
    <div style="overflow-x:auto;">
        <table class ="networkTable">
            <thead>
            <tr>
                <th class="networkTable"><?php echo esc_html(__('Network Title', 'powerpress-network'));?></th>
            </tr>
            </thead>
            <tbody>
            <?php
                for ($i = 0; $i < count($props); ++$i) {
                    ?>
                    <tr>
                        <td class="networkTable">
                            <form action="<?php echo admin_url("admin.php?page=" . urlencode(powerpress_admin_get_page()) . ""); ?>"
                                  method="post">
                                <input type="hidden" name="networkId" value="<?php echo esc_html($props[$i]['network_id']); ?>"/>
                                <input type="hidden" name="ppn-action" value="set-network-id"/>
                                <label><input type="radio" name="networkChoice"
                                              onclick="this.form.submit();"> <?php echo esc_html($props[$i]['network_title']); ?>
                                </label>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            ?>
            </tbody>
        </table>
    </div>
<?php } else {?>
    <h2>Sorry, we couldn't find a Network associated with your account. Learn more about the Network feature <a href="https://create.blubrry.com/professional-podcast-hosting/podcast-network-plugin/">here</a>, or contact our support team <a href="https://blubrry.com/contact/">here.</a></h2>
<?php } ?>
    <br>
	
	<form action="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) .""); ?>" method="post" style="display: inline;">
		<input type="hidden" name="ppn-action" value="unset-network-id" />
	</form>
	<form method='POST' action='#/' id='editForm'>
    <!--Hidden form to keep the information of this session-->
    <input id="networkId" name="networkId" value="" type="hidden">
	
    <button class="warningButton" type="submit" onclick ="unlinkAccount('editForm');directStatus('Signin, editForm')"><?php echo esc_html(__('Unlink Account', 'powerpress-network'));?></button>
    <button class="cacheButton" onclick="refreshAndCallDirectAPI('List Networks', 'editForm')"><?php echo esc_html(__('Clear Cache', 'powerpress-network'));?></button>
</form>
