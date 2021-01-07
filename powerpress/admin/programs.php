<h1 class="pageTitle"><?php echo esc_html(__('Network:', 'powerpress-network'));?> <?php echo esc_html(get_option('powerpress_network_title') ); ?></h1>
<small>Manage programs and their pages</small><br><br><br>
<form method="POST" action="#/" id="manageForm"> <!-- Make sure to keep back slash there for WordPress -->
    <a class="ppn-back-button" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=Select+Choice"); ?>">
    <p><?php echo '&#8592; ' . esc_html(__('Back', 'powerpress-network'));?></p></a>
</form>
<table>
    <thead>
    <tr>
        <th style="text-align: left"><?php echo esc_html(__('Current Programs', 'powerpress-network'));?></th>
    </tr>
    </thead>

    <tbody>
    <?php
    $option = get_option('powerpress_network_map');
	
	if( empty($props) )
		$props = array(); // Empty array so it will not loop
	
    for ($i = 0; $i < count($props); ++$i){
        $key = 'p-'.$props[$i]['program_id'];
        if (isset($option[$key])){
            $link = get_permalink($option[$key]);
        } else{
            $link = null;
        }
        $props[$i]['link'] = $link;
		if( empty($props[$i]['program_title']) )
			$props[$i]['program_title'] = 'n/a';
		if( empty($props[$i]['program_id']) )
			$props[$i]['program_id'] = 0;
		
        ?>
        <tr>
            <td><span style="font-weight:bold; width:50%;"><?php echo esc_html($props[$i]['program_title']); ?></span>  <span><button class="backButton" href="#/" style="float:right" onclick ="manageProgram(<?php echo esc_js($props[$i]['program_id']);?>, '<?php echo esc_js($props[$i]['link']);?>' );directStatus('Manage Program', 'manageProgram')">Manage Program</button></span>
                <br>
                <br>
                <ul>
                    <?php
                    if ($props[$i]['link'] == null){
                     ?>
                        <li style="color: red"><span style="font-size:82%">Link: (not set)<i class="material-icons" style="float:left; font-size: 14px;">warning</i></span></li>
                     <?php
                    } else {
                        ?>
                        <li style="color: green"><span style="font-size:82%">Link: <a href="<?php echo esc_url($props[$i]['link']);?>"><?php echo esc_html($props[$i]['link']);?></a><i class="material-icons" style="float:left; font-size: 14px;">done</i></span></li>
                        <?php
                    }
                    ?>
                </ul>
                <br>
                <span>Program ID: <?php echo esc_html($props[$i]['program_id']); ?></span>
                <br>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table><br>
<a class="ppn-back-button" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=Select+Choice"); ?>">
<p><?php echo '&#8592; ' . esc_html(__('Back', 'powerpress-network'));?></p></a>

<form id="manageProgram" action="#/" method="POST" hidden> <!-- Make sure to keep back slash there for WordPress -->
    <input id="programId" name="programId" value="">
    <input id="linkPageProgram" name="linkPageProgram" value="">
    <input name="previousStatus" value="List Programs">
</form>
