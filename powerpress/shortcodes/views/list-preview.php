<h1>Preview of programs in <?php echo esc_html($props['list_title']);  ?></h1>
<h4><?php echo esc_html($props['list_desc']); ?></h4>
<br>
<?php if(count($props['results']) > $props['limit']) {
    $props['results_sliced'] = array_slice($props['results'],0, $props['limit']);
} ?>
<?php foreach ($props['results_sliced'] as $i => $res) { ?>
    <?php if($res['checked'] == true) { ?>
        <div>
            <h3><?php echo esc_html($res['program_title']); ?></h3>
        </div>
    <?php } ?>
<?php } ?>
<?php ?>
<a href="<?php echo esc_url($props['link']); ?>">Link to rest of list</a>



