<?php
$availablePages = get_pages();
?>

<h1 class="pageTitle"><?php echo esc_html(__('Manage List', 'powerpress-network'));?>: <?php echo esc_html($props['list_info']['list_title']); ?></h1>
<small><?php echo esc_html(__('Edit Lists And Its Page and Programs', 'powerpress-network'));?></small><br><br>
<form method="POST" action="#/" id="manageForm"> <!-- Make sure to keep back slash there for WordPress -->
    <a class="ppn-back-button" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=List+Lists"); ?>">
    <p><?php echo esc_html(__('&#8592; Back', 'powerpress-network'));?></p></a>
</form>
<!--List settings section-->
<div class="settingBox">
    <a href="#TB_inline?&width=500&height=300&inlineId=editListSettings" class="thickbox" title="Powerpress Network plugin"><button class="primaryButton" style="float:right">Edit</button></a>
    <h2 class="thickboxTitle">List Settings</h2>
    <small><?php echo esc_html(__('Edit the title and description of the list', 'powerpress-network'));?></small><br><br>
    <label for="oldListTitle"><b><?php echo esc_html(__('Current List Title', 'powerpress-network'));?></b></label>
    <input id="oldListTitle" name="oldListTitle" type="text" value="<?php echo esc_html($props['list_info']['list_title']); ?>" readonly><br><br>
    <label for="listDescription"><b><?php echo esc_html(__('Current List Description', 'powerpress-network'));?></b></label>
    <textarea id ="listDescription" class="description" name="listDescription" rows ="3" type="text" readonly><?php echo esc_html($props['list_info']['list_description']);?></textarea><br>
</div>

<div id="editListSettings" style="display:none">
    <h1 class="pageTitle"><?php echo esc_html(__('Edit List', 'powerpress-network'));?></h1>
    <form method="POST" action="#/" id="editForm"> <!-- Make sure to keep back slash there for WordPress -->
        <label for="editListTitle"><b><?php echo esc_html(__('New List Title', 'powerpress-network'));?></b></label>
        <input id="editListTitle" name="editListTitle" type="text" value="<?php echo esc_html($props['list_info']['list_title']); ?>"><br>
        <label for="editListDescription"><b><?php echo esc_html(__('New List Description', 'powerpress-network'));?></b></label>
        <textarea id ="editListDescription" class="description" name="editListDescription" rows ="3" type="text"><?php echo esc_html($props['list_info']['list_description']);?></textarea><br>

        <button type="submit" class="ppn-back-button" onclick="directStatus('Manage List', 'editForm', true)"><?php echo esc_html(__('Edit', 'powerpress-network'));?></button>
        <p class="ppn-back-button" onclick ="tb_remove()"><?php echo esc_html(__('Cancel', 'powerpress-network'));?></p>
    </form>
</div>

<!--List Page Section-->
<div class="settingBox">
    <h2 class="thickboxTitle">List Page</h2>
    <small><?php echo esc_html(__('Set, create, change or unlink the list page', 'powerpress-network'));?></small><br><br>
    <?php
    if (!empty($networkInfo['link_page_list'])){
        ?>
        <label for="currentLink"><b><?php echo esc_html(__('Current Link Page', 'powerpress-network'));?></b></label>
        <input class="linkInput" type="text" value="<?php echo esc_html($networkInfo['link_page_list']);?>" readonly><br><br>
        <a href="#TB_inline?&width=500&height=300&inlineId=selectPageBox" class="thickbox" title="Powerpress Network plugin"><button class="primaryButton"><?php echo esc_html(__('Change Page', 'powerpress-network'));?></button></a>
        <a href="#TB_inline?&width=600&height=200&inlineId=confirmUnlink" class="thickbox" title="Powerpress Network plugin"><button class="warningButton"><?php echo esc_html(__('Unlink page', 'powerpress-network'));?></button></a>
        <?php

    } else{
        ?>
        <label for="currentLink"><b><?php echo esc_html(__('Current Link Page', 'powerpress-network'));?></b></label>
        <input class="linkInput" type="text" value="(not set)" readonly><br><br>
        <a href="#TB_inline?&width=500&height=300&inlineId=selectPageBox" class="thickbox" title="Powerpress Network plugin"><button class="primaryButton"><?php echo esc_html(__('Select Existing Page', 'powerpress-network'));?></button></a>
        <button class="primaryButton" onclick="createPage('<?php echo esc_html($networkInfo['list_id']);?>', 'List', 'createForm', '<?php echo esc_html($props['list_info']['list_title']);?>');"><?php echo esc_html(__('Create Page', 'powerpress-network'));?></button>
        <form method="POST" id="createForm">
            <input name="target" value="List" hidden>
            <input name="targetId" value="<?php echo esc_html($networkInfo['list_id']) ?>" hidden>
            <input name="redirectUrl" value="true" hidden>
        </form>
        <?php
    }
    ?>
</div>

<div id="selectPageBox" style="display: none">
    <form method="POST" id="pageForm">
        <p style="color: black; font-weight: bold"><?php echo esc_html(__('Select an existing page to link to current program', 'powerpress-network'));?></p>
        <br>
        <select class="dropdownChoice" name="pageID">
            <?php
            for ($i = 0; $i < count($availablePages); ++$i) {
                ?>
                <option
                        value="<?php echo esc_html($availablePages[$i]->ID); ?>"><?php echo esc_html($availablePages[$i]->post_title); ?></option>
                <?php
            }
            ?>

        </select>
        <br>
        <p style="color: black; font-weight: bold"><?php echo esc_html(__('Remember to put this short code on your new page', 'powerpress-network'));?></p>
        <br>
        <input readonly value='<?php echo esc_html($props['list_info']['shortcode']);?>'>
        <input name="target" value="List" hidden>
        <input name="targetId" value="<?php echo esc_html($networkInfo['list_id']); ?>" hidden>
        <input name="redirectUrl" value="false" hidden>
    </form>
    <button type="submit" class="ppn-back-button" onclick="directStatus('Manage List', 'pageForm', true)"><?php echo esc_html(__('Save', 'powerpress-network'));?></button>
    <p class="ppn-back-button" onclick="tb_remove();"><?php echo esc_html(__('Cancel', 'powerpress-network'));?></p>
</div>

<div class="confirmUnlink" id="confirmUnlink" style="display: none">
    <h2 class="thickboxTitle"><?php echo esc_html(__('Confirm Unlink', 'powerpress-network'));?></h2>
    <form method="POST" id="unlinkForm">
        <input name="target" value="List" hidden>
        <input name="targetId" value="<?php echo esc_html($props['list_info']['list_id']); ?>" hidden>
        <input name="redirectUrl" value="false" hidden>
    </form>

    <p style="color: black; font-weight: bold"><?php echo esc_html(__('Are you sure you want to unlink the current page off the program?', 'powerpress-network'));?></p><br>
    <button type="submit" class="warningButton" onclick="confirmUnlink('unlinkForm');directStatus('Manage List', 'unlinkForm')"><?php echo esc_html(__('Unlink page', 'powerpress-network'));?></button>
    <p class="ppn-back-button" onclick="tb_remove();"><?php echo esc_html(__('Cancel', 'powerpress-network'));?></p>
</div>

<!--Program Section-->

<div class="settingBox">
    <a href="#TB_inline?&width=500&height=300&inlineId=programBox" class="thickbox" title="Powerpress Network plugin"><button  style="float:right" class="primaryButton">Edit</button></a>
    <h2 class="thickboxTitle"><?php echo esc_html(__('Program Management', 'powerpress-network'));?></h2>
    <small><?php echo esc_html(__('Manage programs inside the list', 'powerpress-network'));?></small><br><br>
    <label><b><?php echo esc_html(__('Current programs in list', 'powerpress-network'));?></b></label><br>
    <ul style="color: green">
    <?php
    $option = get_option('powerpress_network_map');
    for ($i = 0; $i < count($props['programs']); ++$i) {
        $key = 'p-'.$props['programs'][$i]['program_id'];
        if (isset($option[$key])){
            $link = get_permalink($option[$key]);
        } else{
            $link = null;
        }
        $props['programs'][$i]['link'] = $link;
        if ($props['programs'][$i]['checked'] == true) {
            ?>
            <li><span style="font-size:90%"><?php echo esc_html($props['programs'][$i]['program_title']);?><i class="material-icons" style="float:left">done</i> <a style="float: right" onclick="manageProgram(<?php echo esc_html($props['programs'][$i]['program_id']);?>, '<?php echo esc_html($props['programs'][$i]['link']);?>'); directStatus('Manage Program', 'specificProgramForm', false)"><?php echo esc_html(__('Save', 'powerpress-network'));?></a></span></li>
            <?php
        }
    }
    ?>
    </ul>
    <form id="specificProgramForm" method ="POST" hidden>
        <input id="programId" name="programId" value="">
        <input id="linkPageProgram" name ="linkPageProgram" value="">
        <input name="previousStatus" value = "Manage List">
    </form>
</div>

<div id ="programBox" style="display: none">
    <form id="programForm" action="#/" method="POST"> <!-- Make sure to keep back slash there for WordPress -->
        <table>
            <tr>
                <th><?php echo esc_html(__('In List', 'powerpress-network'));?></th>
                <th><?php echo esc_html(__('Program Title', 'powerpress-network'));?></th>
            </tr>
            <?php
            for ($i = 0; $i < count($props['programs']); ++$i) {
                ?>
                <tr>
                    <td><input name="program[<?php echo $i; ?>]" class="program" type="checkbox"
                               value="<?php echo esc_html($props['programs'][$i]['program_id']); ?>"
                               <?php if ($props['programs'][$i]['checked']) echo ' checked'; ?>>
                    </td>
                    <td><?php echo esc_html($props['programs'][$i]['program_title']); ?></td>
                </tr>

                <?php
            }
            ?>
        </table>
        <br>
        <input id="requestAction" name="requestAction" hidden>
        <button id="submit" type="submit" class="ppn-back-button" onclick="jQuery(function($){ $('#requestAction').attr('value', 'save')});directStatus('Manage List', 'programForm', true)"><?php echo esc_html(__('Save', 'powerpress-network'));?></button>
        <p class="ppn-back-button" onclick="tb_remove();"><?php echo esc_html(__('Cancel', 'powerpress-network'));?></p>
    </form>
</div>

<!--End of all section-->
<form method="POST" action="#/" id="manageForm"> <!-- Make sure to keep back slash there for WordPress -->
    <a class="ppn-back-button" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=List+Lists"); ?>">
    <p><?php echo '&#8592; ' . esc_html(__('Back', 'powerpress-network'));?></p></a>
</form>
