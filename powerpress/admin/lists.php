<h1 class="pageTitle"><?php echo esc_html(__('Network:', 'powerpress-network'));?> <?php echo esc_html(get_option('powerpress_network_title') ); ?></h1>
<small><?php echo esc_html(__('Edit Or Create Lists', 'powerpress-network'));?></small><br><br>
<button onclick='manageList(-1);directStatus("Create List", "manageForm");' class="primaryButton"><?php echo esc_html(__('Create New List', 'powerpress-network'));?></button>
<a class="ppn-back-button" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=Select+Choice"); ?>">
<p><?php echo '&#8592; ' . esc_html(__('Back', 'powerpress-network'));?></p></a>
<table>
    <tr>
        <th style="text-align: left;"><?php echo esc_html(__('Current Lists', 'powerpress-network'));?></th>
    </tr>

    <?php
    $map = get_option ('powerpress_network_map');
	if( empty($props) )
		$props = array(); // Empty array so it will not loop
		
    for ($i = 0; $i < count($props); ++$i) {
        $key = 'l-'.$props[$i]['list_id'];
        if (isset($map[$key])){
            $link = get_permalink($map[$key]);
        } else{
            $link = null;
        }
        $props[$i]['link'] = $link;
        ?>
        <tr>
            <td>
                <span style="font-weight:bold;"><?php echo esc_html($props[$i]['list_title']); ?><i onclick="confirmDelete(<?php echo esc_js($props[$i]['list_id']);?>)" class="material-icons" title="Delete List"style="color:red; float: right; font-size: 20px">delete</i></span>
                <span style="font-weight:bold;"><i class="material-icons" title="Edit List"  style="float: right; font-size: 20px" onclick="manageList(<?php echo esc_js($props[$i]['list_id']);?>, '<?php echo esc_js($props[$i]['link'])?>');directStatus('Manage List', 'manageList');">edit</i></span>
                <ul>
                    <?php
                    if ($props[$i]['link'] == null){
                        ?>
                        <li style="color: red"><span>Link: (not set)<i class="material-icons" style="float:left; font-size: 14px;">warning</i></span></li>
                        <?php
                    } else {
                        ?>
                        <li style="color: green"><span>Link: <a href="<?php echo esc_url($props[$i]['link']);?>"><?php echo esc_html($props[$i]['link']);?></a><i class="material-icons" style="float:left; font-size: 14px;">done</i></span></li>
                        <?php
                    }
                    ?>
                </ul>
                <br>
                <span class="list-id">List ID: <?php echo esc_html($props[$i]['list_id']); ?></span>
                <br><br>
            </td>
        </tr>
        <?php
    }
    ?>
</table><br>
<button onclick='manageList(-1);directStatus("Create List", "manageForm");' class="primaryButton"><?php echo esc_html(__('Create New List', 'powerpress-network'));?></button>
<a class="ppn-back-button" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=Select+Choice"); ?>">
<p><?php echo esc_html(__('&#8592; Back', 'powerpress-network'));?></p></a>
<form id="manageForm" action="#/" method="POST" hidden> <!-- Make sure to keep back slash there for WordPress -->
</form>

<form id="manageList" action="#" method="POST" hidden> <!-- Make sure to keep back slash there for WordPress -->
    <input id="requestAction" name="requestAction">
    <input id="listId" name="listId" value="">
    <input id="linkPageList" name="linkPageList" value="">
</form>

<script>
    function manageList(listId, linkPage = false)
    {
        jQuery(function($){ $('#listId').attr('value', listId) });
        jQuery(function($){ $('#linkPageList').attr('value', linkPage) });
    }
    function confirmDelete(listId)
    {
        if (confirm('<?php echo esc_js(__('Are you sure you want to delete this list?', 'powerpress-network'));?>')) { //Confirm the delete network
            jQuery(function($){ $('#requestAction').attr('value', 'delete') });
            jQuery(function($){ $('#listId').attr('value', listId) });
            directStatus('List Lists', 'manageList', true);
        }
    }
</script>
