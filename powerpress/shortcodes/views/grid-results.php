<?php
wp_register_style('blueprint', '../css/blueprint.css');
wp_enqueue_style('blueprint');
if (!is_numeric($props['rows']))
    $props['rows'] = 1;
if (!is_numeric($props['cols']))
    $props['cols'] = 4;

$limit = $props['rows'] * $props['cols'];
$numCols = array();
$numCols['large'] = $props['cols'];
if ($numCols['large'] == 6){
    $numCols['medium'] = 4;
    $numCols['small'] = 2;
} else if ($numCols['large'] == 4 || $numCols['large'] == 3){
    $numCols['medium'] = 2;
    $numCols['small'] = 2;
} else{
    $numCols['medium'] = $numCols['large'];
    $numCols['small'] = $numCols['large'];
}
$size = array();
foreach ($numCols as $key=>$value){
    $size[$key] = 12 / $value;
}
$gridHTML = null;
if ($limit > count($props['results'])){
    $limit = count($props['results']);
}
$gridHTML.="<div bp='grid' class='ppn-grid-header'>";
$count = 0;
foreach ($props['results'] as $program) {

    if (!$program['link'] || $program['link'] == "#")
        continue;

    if($count >= $limit)
        break;
    $count++;

    $gridHTML .= "
        <div class='ppn-grid-rows' bp='".$size['large']."@lg ".$size['medium']." @md ".$size['small']." @sm'>
            <div class='ppn-grid-cell'>
                <div class='ppn-grid-img' id='".$program['program_id']."'>
                <div class='ppn-centered-content'>
                    <a class='square' href='" . esc_url(($program['link'])) . "'>
                        <img class='ppn-img' bp ='float-center' src='" . esc_url(($program['artwork_url']['300'])) . "' title='".esc_html(($program['program_title']))."'>
                    </a>";
    if ($props['display-title']) {
        $gridHTML .= "
                    <div class='ppn-grid-title'>
                        <h3 bp='text-center' class='ppn-title'>" . "<a href='" . esc_url(($program['link'])) . "'>" . esc_html(($program['program_title'])) . "</a></h3>
                    </div>";
    }

    $gridHTML .=
            "        </div>
                </div>
            </div>    
        </div>   
                ";

}
$gridHTML.="</div>";
?>

<html>
    <head>
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
        <link href="<?php echo PowerPressNetwork::powerpress_network_plugin_url(). "css/blueprint.css";?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo PowerPressNetwork::powerpress_network_plugin_url(); ?>css/style.css" type="text/css"/>
    </head>

    <body>
        <?php echo $gridHTML;?>
    </body>
</html>
<style>
    .ppn-grid-cell:hover, ppn-grid-cell:hover{
        transform: scale(<?php if ($props['hover']) echo'1.1'; else echo '1.0'?>);
    }
    .ppn-centered-content {
        <?php if($props['display-title']) echo 'background-color: #444444;'; ?>
        margin-left: auto;
        margin-right: auto;
        max-width: 300px;
        outline: 1px solid #444444;
    }
</style>