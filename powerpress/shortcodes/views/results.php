<?php
if (empty($props['results'])) {
    echo $props['no-results'];
} else { ?>
<div class="results-container">
    <?php if ($props['show-paging']) { ?>
        <div class="results-header">
        </div>
    <?php } ?>
    <?php foreach ((array)$props['results'] as $i => $res) { ?>
        <div class="result <?php if ($i === 0) echo 'first' ?>">
            <div class="artwork-container col-xs-3 ppn-padding">
                <img id="results-artwork" src="<?php echo $res->image_url ?>"/>
            </div>
            <div class="result-text">
                <a href="<?php if (isset($_SERVER['HTTPS'])) {
                    echo 'https://' . $_SERVER['SERVER_NAME'] . '/' . $res->click_url;
                } else {
                    echo 'http://' . $_SERVER['SERVER_NAME'] . '/' . $res->click_url;
                } ?>">
                    <h4><?php if (strlen($res->title) > 44) echo substr($res->title, 0, 41) . "..."; else echo $res->title ?></h4>
                </a>
                <p><?php if (strlen($res->body) > 144) echo substr($res->body, 0, 140) . "..."; else echo $res->body ?></p>
            </div>
        </div>
    <?php } ?>
    <?php
    $pathParts = [];
    preg_match('/\/(.*[^page\/\d\/])\//i', $_SERVER['REQUEST_URI'], $pathParts);
    // [1] is now the match of this preg_match, which pulls everything before /page/number/ (if page number is present)
    ?>
    <div class="page-nav">
        <a class="page-prev <?php if ($props['paged'] === 1) echo 'disabled' ?>"
            <?php if ($props['paged'] > 1) { ?>
                href="/<?php echo $pathParts[1] ?>/page/<?php echo $props['paged'] - 1; ?>/<?php if (!empty($_SERVER['QUERY_STRING'])) echo "?" . $_SERVER['QUERY_STRING']; ?>"
            <?php } ?>>
            <p><span class="glyphicon glyphicon-arrow-left"></span> Previous </p>
        </a>
        <a class="page-next <?php if ($props['total'] <= ($props['paged'] * $props['limit'])) echo 'disabled' ?>"
            <?php if ($props['total'] > ($props['paged'] * $props['limit'])) { ?>
                href="/<?php echo $pathParts[1] ?>/page/<?php echo $props['paged'] + 1; ?>/<?php if (!empty($_SERVER['QUERY_STRING'])) echo "?" . $_SERVER['QUERY_STRING']; ?>"
            <?php } ?>>
            <p> Next <span class="glyphicon glyphicon-arrow-right"></span></p>
        </a>
    </div>
    <?php } ?>
</div>