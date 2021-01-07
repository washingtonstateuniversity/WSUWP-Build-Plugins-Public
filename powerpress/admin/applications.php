<?php
$table = '<table>
                        <tr>
                        <th class="pendingApply">'.esc_html(__('Pending', 'powerpress-network')).'</th>
                        <th class="approvedApply">'.esc_html(__('Approved', 'powerpress-network')).'</th>
                        <th class="disapprovedApply">'.esc_html(__('Disapproved', 'powerpress-network')).'</th>
                        </tr>';
$pendingColumn = '<tr>';
$processedColumn = '<tr>';
$approvedColumn = '<tr>';
$disapprovedColumn = '<tr>';
if( empty($props) )
	$props = array(); // Empty array so it will not loop

for ($i = 0; $i < count($props); ++$i){
    switch ($props[$i]['app_status']) {
        case 0:
            $pendingColumn .= '<td class="pendingApply">'.esc_html($props[$i]['program_title']).'<span title="Disapprove" class="disapproveIcon" style="float:right" onclick="approveProgram('.esc_js($props[$i]['applicant_id']).', false)">&#x2716</span><span title="Approve" class="approveIcon" style="float:right" onclick="approveProgram('.esc_js($props[$i]['applicant_id']).', true)">&#x2714</span></td>
                                           <td></td>
                                           <td></td>
                                           ';
            break;

        case 1:
            $approvedColumn .= '<td></td>
                                             <td class="approvedApply">'.esc_html($props[$i]['program_title']).'</td>
                                             <td></td>
                                             ';
            break;

        case -1:
            $disapprovedColumn .='<td></td>
                                              <td></td>
                                              <td class="disapprovedApply" style="color: red">'.esc_html($props[$i]['program_title']).'</td>
                                              ';
            break;
    }
    $pendingColumn.='</tr>';
    $processedColumn .='</tr>';
    $approvedColumn.='</tr>';
    $disapprovedColumn.='</tr>';
}
$table.=$pendingColumn.$approvedColumn.$disapprovedColumn.'</table>';


//Modified content
$pendingTab = '<div class="tabContent" style="display:block" id="pending">';
$approvedTab = '<div class="tabContent" style="display:none" id="approved">';
$rejectedTab = '<div class="tabContent" style="display:none" id="rejected">';
for ($i = 0; $i < count($props); ++$i){
    switch ($props[$i]['app_status']) {
        case 0:
            $pendingTab.='<div class="programRow">
                                <div class="programInfo" style="display: inline">
                                    <h2>'. esc_html($props[$i]['program_title']).' </h2>
                                    <h5>List: '.esc_html($props[$i]['list_title']).'</h5>
                                    <button class="applicantButton" style="background-color: #007800; display: inline-block; " onclick="approveProgram('.esc_js($props[$i]['applicant_id']).', true)">Approve</button>
                                    <button class="applicantButton" style="background-color: #B00000; display: inline-block;" onclick="approveProgram('.esc_js($props[$i]['applicant_id']).', false)">Reject</button>
                                </div>
                          </div>';
            break;
        case 1:
            $approvedTab .='<div class="programRow">
                            <div class="programInfo" style="display: inline">
                                <h2 style="color:green">'. esc_html($props[$i]['program_title']).' </h2>
                                <h5 style="color:green">List: '.esc_html($props[$i]['list_title']).'</h5>
                            </div>
                          </div>';
            break;
        case -1:
            $rejectedTab .='<div class="programRow">
                            <div class="programInfo" style="display: inline">
                                <h2 style="color:red">'. esc_html($props[$i]['program_title']).' </h2>
                                <h5 style="color:red">List: '.esc_html($props[$i]['list_title']).'</h5>
                            </div>
                          </div>';
            break;
    }
}
$pendingTab.='</div>';
$approvedTab.='</div>';
$rejectedTab.='</div>';

//End modified content


?>
<h1 class="pageTitle"><?php echo esc_html(__('Network:', 'powerpress-network'));?> <?php echo esc_html(get_option('powerpress_network_title') ); ?></h1><br>
<small><?php echo esc_html(__('Your Applicants', 'powerpress-network'));?></small><br><br><br>
<form method="post" id="applicationForm" hiddent>
    <button class="primaryButton" onclick="createApplicationPage('Application','applicationForm', 'Application Page')">
        <?php echo esc_html(__('Create Application Page', 'powerpress-network'));?>
    </button>
</form>
<a class="ppn-back-button" href="<?php echo admin_url("admin.php?page=". urlencode(powerpress_admin_get_page()) ."&status=Select+Choice"); ?>">
<p><?php echo esc_html(__('&#8592; Back', 'powerpress-network'));?></p></a>

<div class="tab">
    <button class="tabActive" id="pendingTab"    onclick="showApplication('pending')">Pending</button>
    <button class="tabInactive" id="approvedTab"  onclick="showApplication('approved')">Approved</button>
    <button class="tabInactive" id="rejectedTab"  onclick="showApplication('rejected')">Rejected</button>
</div>

<?php echo $pendingTab.$approvedTab.$rejectedTab;?>
<form id='createForm' method="POST">
</form>

