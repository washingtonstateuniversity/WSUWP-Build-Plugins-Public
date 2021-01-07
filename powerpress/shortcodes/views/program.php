<?php

wp_register_style('namespace', '../css/style.css');
wp_enqueue_style('namespace');

$path = plugin_dir_path(dirname(__FILE__));
$id = get_query_var('episode_id');
$pagename = get_query_var('pagename');
?>

<head>
    <link href="https://assets2.blubrry.com/css/blubrryicons.css" rel="stylesheet" type="text/css"/>
    <link href="https://assets2.blubrry.com/tests/colorbox.css" rel="stylesheet" type="text/css"/>
    <link href="https://assets2.blubrry.com/css/ekko-lightbox.css" rel="stylesheet" type="text/css"/>
    <link href="https://assets2.blubrry.com/css/ekko-lightbox.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://assets2.blubrry.com/css/blubrryicons.css" rel="stylesheet" type="text/css"/>
    <link href="<?php if (isset($_SERVER['HTTPS'])) {
        echo 'https://' . $_SERVER['SERVER_NAME'] . '/wp-content/plugins/powerpress-directory/js/mediaelement/build/mediaelementplayer.min.css';
    } else {
        echo 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content/plugins/powerpress-directory/js/mediaelement/build/mediaelementplayer.min.css';
    } ?>" rel="stylesheet" type="text/css"/>

    <script src="https://assets2.blubrry.com/js/ekko-lightbox.js"></script>
    <script src="https://assets2.blubrry.com/js/ekko-lightbox.min.js"></script>
    <script src="https://assets2.blubrry.com/js/jquery.colorbox.js"></script>
    <script src="https://assets2.blubrry.com/js/jquery.colorbox-min.js"></script>
    <script src="<?php if (isset($_SERVER['HTTPS'])) {
        echo 'https://' . $_SERVER['SERVER_NAME'] . '/wp-content/plugins/powerpress-directory/js/mediaelement/build/mediaelement-and-player.js';
    } else {
        echo 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content/plugins/powerpress-directory/js/mediaelement/build/mediaelement-and-player.js';
    } ?>"></script>
    <meta charset="utf-8">
</head>
<div>
    <h3><?php echo esc_html($props[0]->program_title); ?> </h3>
</div>
<div class="program-container">
    <div class="jumbotron">
        <div class="grid-container">
            <div class="item1"></div>
            <div class="item3"></div>
            <div class="coverart">
                <img class="coverart"
                     src="<?php if (isset($props[0]->program_header_image)) {
                         echo("https://assets.blubrry.com/coverart/300/{$props[0]->program_id}.jpg");
                     } else {
                         echo("https://www.blubrry.com/themes/blubrry3/images/cover.png");
                     } ?>"
                >
            </div>
            <div class="share-icons display-inline-block">
                <a id="subscribe" href="" class="text-decoration-none" data-toggle="lightbox" data-title="Subscribe" data-gallery="remove-load" data-width="1200">
                    <i id="subscribe" class="blubrry-icon bicon-rss ppn-subscribe-style" ></i>
                    <p class="subshare">Subscribe</p>
                </a>
                <a id="share" href="<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'] ?>" class="text-decoration-none" data-toggle="lightbox" data-title="Share" data-gallery="remove-load" data-width="1200">
                    <i class="blubrry-icon bicon-share ppn-share-style" onclick="SetShareOptions(<?php echo $props[0]->program_rssurl; ?>,<?php echo $props[0]->program_title; ?>)"></i>
                    <p class="subshare">Share</p>
                </a>
            </div>
        </div>
    </div>

    <div class="description bold">
        <p class="ppn-margin-bottom"><?php echo esc_html($props[0]->program_desc); ?></p>
        <a class="ppn-claim-style" id="claim" href="https://www.blubrry.com" data-toggle="lightbox" data-title="Claim" data-gallery="remove-load" data-width="1200">Claim This Show</a>
    </div>

    <?php if (!get_query_var('episode_id', false)) { ?>
        <div class="podcast-filename">
            <audio id="audio" controls preload="none" class="audio-tag-filename">
                <source src="<?php echo esc_html($props[0]->podcast_filename); ?>">
            </audio>
        </div>

        <ul id="playlist">
            <?php for ($i = 10; $i > 0; $i--) {
                ?>
                <li class="list-group-item entry well z-depth-1">
                    <div class="playbuttoncontainer">
                        <a href="<?php echo($props[$i]->podcast_filename); ?>">
                            <img class="playbutton" src="<?php if (isset($_SERVER['HTTPS'])) {
                                echo 'https://' . $_SERVER['SERVER_NAME'] . '/wp-content/plugins/powerpress-directory/images/ShowPageRedesignPlayButton.png';
                            } else {
                                echo 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content/plugins/powerpress-directory/images/ShowPageRedesignPlayButton.png';
                            } ?>"
                        </a>
                    </div>
                    <a href="/<?php echo $props[0]->keyword; ?>/episode<?php echo $props[$i]->podcast_no; ?>"
                       title="<?php echo esc_html($props[$i]->podcast_title); ?>">
                        <?php echo(esc_html($props[$i]->podcast_title)); ?>
                    </a>
                    <p class="subtitle"><?php echo esc_html($props[$i]->podcast_subtitle); ?></p>
                </li>
            <?php } ?>
        </ul>
    <?php } else {
        foreach ($props as $ep => $x) {
            if ($x->podcast_no == $id) { ?>
                <div class="podcast-filename">
                    <audio id="audio" controls preload="none" class="audio-tag-filename">
                        <source src="<?php echo esc_html($x->podcast_filename); ?>">
                    </audio>
                </div>
                <div>
                    <h4><?php echo esc_html($x->podcast_title); ?></h4>
                    <audio controls class="display-inline">
                        <source src="<?php echo esc_html($x->podcast_filename); ?>">
                    </audio>
                    <p class="podcast-summary-paragraph"><?php echo nl2br($x->podcast_summary); ?></p>
                </div>
                <?php
            }
        }
    } ?>
</div>
<script>
    var audio;
    var playlist;
    var tracks;
    var current;

    init();

    function init() {
        current = 0;
        audio = $('#audio');
        playlist = $('#playlist');
        tracks = playlist.find('li div a');
        len = tracks.length - 1;
        audio[0].volume = 0.5;
        playlist.find('div a').click(function (e) {
            e.preventDefault();
            link = $(this);
            current = link.parent().index();
            run(link, audio[0]);
        });
        audio[0].addEventListener('ended', function (e) {
            current++;
            if (current == len) {
                current = 0;
                link = playlist.find('a')[0];
            } else {
                link = playlist.find('a')[current];
            }
            run($(link), audio[0]);
        });

    }

    function run(link, player) {
        playlist = $('#playlist');
        player.src = link.attr('href');
        par = link.parent();
        console.log(par);
        console.log(par.parent());

        active = document.getElementsByClassName('active');
        var elems = document.querySelectorAll(".active");

        [].forEach.call(elems, function (el) {
            el.classList.remove("active");
        });

        par.addClass('active');
        par.parent().addClass('active');
        audio[0].load();
        audio[0].play();
    }

    function SetShareOptions(url, title) {
        var msg = encodeURIComponent(title + ' ' + url);
        var siteTitle = document.title.substring(0, document.title.indexOf(' -'));
        var html = '<h4 style="margin-left: 10px;">Share This Podcast</h4>';
        html += '<div class="share-popup">';
        html += '<ul style="list-style: none;">';
        html += '<li style="font-weight: normal;\n' +
            '\tmargin: 0 10px;\n' +
            '\theight: 28px;\n' +
            '\twidth: 130px;\n' +
            '\tfont-size: 16px;"><a style="" href="http://twitter.com/home?status=' + msg + '" class="twitter" title="Twitter" target="_blank">Twitter</a></li>';

        html += '<li style="font-weight: normal;\n' +
            '\tmargin: 0 10px;\n' +
            '\theight: 28px;\n' +
            '\twidth: 130px;\n' +
            '\tfont-size: 16px;"><a style="" href="http://www.facebook.com/share.php?u=' + encodeURIComponent(url) + '&amp;t=' + encodeURIComponent(title) + '" class="facebook" title="Facebook" target="_blank">Facebook</a></li>';

        html += '<li style="font-weight: normal;\n' +
            '\tmargin: 0 10px;\n' +
            '\theight: 28px;\n' +
            '\twidth: 130px;\n' +
            '\tfont-size: 16px;"><a style="" href="http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(url) + '&amp;title=' + encodeURIComponent(title) + '&amp;source=' + encodeURIComponent(siteTitle) + '" class="linkedin" title="LinkedIn" target="_blank">LinkedIn</a></li>';

        html += '<li style="font-weight: normal;\n' +
            '\tmargin: 0 10px;\n' +
            '\theight: 28px;\n' +
            '\twidth: 130px;\n' +
            '\tfont-size: 16px;"><a style="" href="mailto:?subject=' + encodeURIComponent(title) + '&amp;body=' + encodeURIComponent('Link: ' + url) + '" class="email" title="Email" target="_blank">Email</a></li>';

        html += '</ul>';
        html += '</div>';
        return html;
    }

    function SetSubscribeOptions(url) {
        var html = "<h4 style='text-align: center'> Subscribe To This Podcast </h4>";
        html += "<div class='pp-sub-buttons'>";
        html += "<a href= <?php echo("'" . $props[0]->program_itunesurl . "'"); ?>  class=\"pp-sub-button pp-sub-itunes\" title=\"Subscribe on iTunes\"><span class=\"pp-sub-img\"></span>Subscribe on iTunes</a>";
        html += "<br/>";
        html += "<a href=\"" + "https://subscribeonandroid.com/" + url + "\" class=\"pp-sub-button pp-sub-android\" title=\"Subscribe on Android\"><span class=\"pp-sub-img\"></span>Subscribe on Android</a>";
        html += "<br/>";
        html += "<a href=\"" + "https://subscribebyemail.com/" + url + "\" class=\"pp-sub-button pp-sub-email\" title=\"Subscribe by Email\"><span class=\"pp-sub-img\"></span>Subscribe by Email</a>";
        html += "<br/>";
        html += "<a href=\"" + url + "\" class=\"pp-sub-button pp-sub-mycast\" title=\"Subscribe by Email\"><span class=\"pp-sub-img\"></span>Add To MyCast</a>";
        html += "</div>";

        return html;
    }

    jQuery('a#share').colorbox({
        html: SetShareOptions(<?php echo "'" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'] . $props[0]->program_title . "'"; ?>,<?php echo "'" . $props[0]->program_title . "'";?>),
        width: "50%",
        height: "50%"
    });
    jQuery('a#subscribe').colorbox({
        html: SetSubscribeOptions(<?php echo "'" . $props['program_rssurl'] . "'"?>),
        width: 500,
        height: 500
    });
    jQuery('a#claim').colorbox({
        html: '<p><?php echo($html);?></p>', //TODO: See if this variable is correst
        width: 500,
        height: 500
    });

    $('video, audio').mediaelementplayer({
        // Do not forget to put a final slash (/)
        pluginPath: '<?php echo dirname(dirname(__FILE__));?>/js/mediaelement/',
        // this will allow the CDN to use Flash without restrictions
        // (by default, this is set as `sameDomain`)
        shimScriptAccess: 'always'
        // more configuration
    });
</script>
