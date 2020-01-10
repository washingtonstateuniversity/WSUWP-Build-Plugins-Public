<?php
$AppleCategories = powerpress_apple_categories(true);
$FeedSettings = powerpress_get_settings('powerpress_feed_podcast');
$GeneralSettings = powerpress_get_settings('powerpress_general');
$title = $FeedSettings['title'] ? $FeedSettings['title'] : get_bloginfo_rss('name');
if (isset($_FILES['itunes_image_file'])) {
    $feed_info = explode(" ", $_POST['basic_details']);
    foreach ($feed_info as $i => $word) {
        switch($word) {
            case 'TITLE:':
                if ($feed_info[$i + 1] != 'CATEGORY:') {
                    $title = str_replace("_", " ", $feed_info[$i + 1]);
                }
                break;
            case 'CATEGORY:':
                if ($feed_info[$i + 1] != 'EXPLICIT:') {
                    $FeedSettings['apple_cat_1'] = $feed_info[$i + 1];
                }
                break;
            case 'EXPLICIT:':
                if ($feed_info[$i + 1] != 'undefined') {
                    $FeedSettings['itunes_explicit'] = intval($feed_info[$i + 1]);
                }
                break;
            default:
                break;
        }
    }
    $upload_path = false;
    $upload_url = false;
    $error = false;
    $UploadArray = wp_upload_dir();
    if( false === $UploadArray['error'] )
    {
        $upload_path =  $UploadArray['basedir'].'/powerpress/';
        $upload_url =  $UploadArray['baseurl'].'/powerpress/';
    }
    $filename = str_replace(" ", "_", basename($_FILES['itunes_image_file']['name']) );
    $temp = $_FILES['itunes_image_file']['tmp_name'];

    if( file_exists($upload_path . $filename ) )
    {
        $filenameParts = pathinfo($filename);
        if( !empty($filenameParts['extension']) ) {
            do {
                $filename_no_ext = substr($filenameParts['basename'], 0, (strlen($filenameParts['extension'])+1) * -1 );
                $filename = sprintf('%s-%03d.%s', $filename_no_ext, rand(0, 999), $filenameParts['extension'] );
            } while( file_exists($upload_path . $filename ) );
        }
    }

    // Check the image...
    if( file_exists($temp) )
    {
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        if (!move_uploaded_file($temp, $upload_path . $filename)) {
            powerpress_page_message_add_error(__('Error saving Apple Podcasts image', 'powerpress') . ':	' . htmlspecialchars($_FILES['itunes_image_file']['name']) . ' - ' . __('An error occurred saving the iTunes image on the server.', 'powerpress') . ' ' . sprintf(__('Local folder: %s; File name: %s', 'powerpress'), $upload_path, $filename));
            $error = true;
        } else {
            $previewImageURL = $upload_url . $filename;
        }
    }
}
if (isset($_POST['pp_start']['title'])) {
    $SaveSettings = powerpress_stripslashes($_POST['pp_start']);
    if (isset($previewImageURL)) {
        unset($SaveSettings['itunes_image']);
        $SaveSettings['itunes_image'] = $previewImageURL;
    }
    //var_dump($SaveSettings);
    powerpress_save_settings($SaveSettings, 'powerpress_feed_podcast');
    if (isset($GeneralSettings['blubrry_hosting']) && $GeneralSettings['blubrry_hosting'] != null) {
        echo '<script>window.location.href = "' . admin_url("admin.php?page={$_GET['page']}&step=createEpisode") . '";</script>';
    } else {
        echo '<script>window.location.href = "' . admin_url("admin.php?page={$_GET['page']}&step=nohost") . '";</script>';
    }
}

?>

<div class="wrap">
    <div class="pp_container">
        <h2 class="pp_align-center"><?php echo __('Enter your podcast title and upload artwork', 'powerpress'); ?></h2>
        <h5 class="pp_align-center" style="margin-bottom: 2rem;"><?php echo __('Before you can upload your podcast, you need to name it and add podcast artwork.', 'powerpress'); ?></h5>
        <hr style="margin-top: 0;" class="pp_align-center" />

            <section id="one" class="pp_wrapper" style="margin-top:25px;">

                <div class="pp_flex-grid">
                    <div class="pp_col">
                        <form id="basic-feed" enctype="multipart/form-data" action="" method="post">
                            <div class="pp_leftline">
                                <h4><?php echo __('Podcast Title', 'powerpress'); ?></h4>
                                <div class="pp_form-group">
                                    <div class="pp_input-field-thirds">
                                        <input id="input-title" type="text" name="pp_start[title]" class="pp_outlined" value="<?php echo $title; ?>" placeholder="<?php echo __('Enter the title of your podcast', 'powerpress'); ?>">
                                        <label id="title-label" style="display:none" oninput=""><?php echo __('Enter the title of your podcast', 'powerpress'); ?></label>
                                        <script>
                                            jQuery("#input-title").on("input", function(el) {
                                                jQuery("#title-label").css("display", "inline-block");
                                                jQuery("#input-title").attr("placeholder", "");
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="pp_leftline">
                                <h4><?php echo __('Category', 'powerpress'); ?></h4>
                                <div class="pp_form-group">
                                    <div class="pp_input-field-thirds">
                                        <select id="apple_cat" name="pp_start[apple_cat_1]" class="bpp_input_med">
                                            <?php

                                            echo '<option value="">'. __('Select Category', 'powerpress') .'</option>';

                                            foreach( $AppleCategories as $value=> $desc ) {
                                                echo "\t<option value=\"$value\"" . ($FeedSettings['apple_cat_1'] == $value ? ' selected' : '') . ">" . htmlspecialchars($desc) . "</option>\n";
                                            }
                                            reset($AppleCategories);
                                            ?>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="pp_leftline">
                                <h4><?php echo __('Does your podcast contain explicit content?', 'powerpress'); ?></h4>
                                <div class="pp_form-group">
                                    <label><input type="radio" name="pp_start[itunes_explicit]" value="1" <?php echo $FeedSettings['itunes_explicit'] == 1 ? 'checked': '' ?> /> <?php echo __('Yes', 'powerpress'); ?></label>
                                    <label><input type="radio" name="pp_start[itunes_explicit]" value="2" <?php echo $FeedSettings['itunes_explicit'] == 2 ? 'checked': '' ?> /> <?php echo __('No', 'powerpress'); ?></label>
                                </div>
                            </div>
                            <button type="submit" name="basic-feed-submit" class="pp_button" style="visibility: hidden;"><span><?php echo __('Continue', 'powerpress'); ?></span></button>
                        </form>
                    </div>

                    <div class="pp_col">
                        <form id="artwork" enctype="multipart/form-data" action="" method="post">
                            <div class="pp_leftline">
                                <h4><?php echo __('Podcast Artwork', 'powerpress'); ?></h4>
                                <div id="error-container" style="display: none;"><h5 style="font-weight: bold;color: red;"><img style="vertical-align: middle;margin: 0 5px 3px 0;" src="<?php echo powerpress_get_root_url(); ?>images/onboarding/cancel.svg"><?php echo __('Your image is not valid.', 'powerpress'); ?></h5></div>
                                <div class="pp_flex-grid" id="showbasics_artwork">
                                    <div class="pp_col" id="showbasics_artwork_upload" style="margin-left: 0;">
                                        <input type="text" id="filePath" readonly class="pp_outlined" style="margin: 1rem 0 1ch 0;" placeholder="Upload your show artwork" <?php echo empty($FeedSettings['itunes_image']) ? '' : "value='{$FeedSettings['itunes_image']}'"  ?>>
                                        <input id="itunes_image" type="hidden" name="pp_start[itunes_image]" <?php echo !empty($FeedSettings['itunes_image']) ? "value='{$FeedSettings['itunes_image']}'" : ""  ?>>
                                        <?php
                                        if (!isset($previewImageURL)) {
                                            $previewImageURL = !empty($FeedSettings['itunes_image']) ? $FeedSettings['itunes_image'] : 'https://via.placeholder.com/275?text=Artwork+Preview';
                                        } ?>
                                        <img id="preview_image" class="image_wrapper" src="<?php echo $previewImageURL ?>" alt="Podcast Artwork Preview">
                                        <input type="hidden" name="basic_details" id="basic-details">
                                    </div>
                                    <div class="pp_col" style="margin: 0;">
                                        <div id="upload-artwork-button" class="pp_button_alt" onclick="document.getElementById('FileAttachment').click();">
                                            <span><?php echo __('Upload', 'powerpress'); ?></span>
                                            <input type="file" id="FileAttachment" name="itunes_image_file" accept="image/*" class="pp_file_upload" style="display: none;" />
                                        </div>
                                        <div id="artwork-spec">
                                            <strong><?php echo __('Make sure your artwork meets the criteria for the best experience!', 'powerpress'); ?></strong>
                                            <hr style="margin: 1em 0 0 0;">
                                            <p class="pp-smaller-text"><?php echo __('Minimum size: 1400px x 1400px', 'powerpress'); ?></p>
                                            <p class="pp-smaller-text"><?php echo __('Maximum size: 3000px x 3000px', 'powerpress'); ?></p>
                                            <img style="display: none;" src="<?php echo powerpress_get_root_url(); ?>images/onboarding/checkmark.svg" id="size-icon" class="success-fail-icon">
                                            <hr style="margin: 0;">
                                            <p class="pp-smaller-text"><?php echo __('.jpg or .png', 'powerpress'); ?></p>
                                            <img style="display: none;" src="<?php echo powerpress_get_root_url(); ?>images/onboarding/checkmark.svg" id="type-icon" class="success-fail-icon">
                                            <hr style="margin: 0;">
                                            <p class="pp-smaller-text"><?php echo __('RGB color space', 'powerpress'); ?></p>
                                            <img style="display: none;" src="<?php echo powerpress_get_root_url(); ?>images/onboarding/checkmark.svg" id="colorspace-icon" class="success-fail-icon">
                                            <hr style="margin: 0 0 1em 0;">
                                            <a href="https://create.blubrry.com/resources/powerpress/powerpress-settings/artwork-2/"><?php echo __('Learn more about Podcast Artwork', 'powerpress'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="artwork-submit" class="pp_button" style="visibility: hidden;"><span><?php echo __('Continue', 'powerpress'); ?></span></button>
                        </form>
                    </div>
            </section>
            <div class="pp_col" style="padding: 20px 0;">
                <hr class="pp_align-center" />
                <div class="pp_button-container" style="float: right;">
                    <button id="continue-button" type="button" name="submit" class="pp_button"><span><?php echo __('Continue', 'powerpress'); ?></span></button>
                </div>
            </div>
</div>
<script>
    function verifyImage() {
        var img = new Image();
        img.onload = function() {
            let url = jQuery('#preview_image').attr("src");
            jQuery("#itunes_image").val(url);

            let width = this.naturalWidth;
            let height = this.naturalHeight;
            if (width != height || width > 3000 || width < 1400) {
                jQuery("#size-icon").removeAttr('src');
                jQuery("#size-icon").attr('src', '<?php echo powerpress_get_root_url(); ?>images/onboarding/cancel.svg');
            }
            jQuery("#size-icon").removeAttr('style');
            jQuery("#size-icon").attr('style', 'display: inline-block');
            if (!url.toLowerCase().includes('.jpg') && !url.toLowerCase().includes('.png')) {
                jQuery("#type-icon").removeAttr('src');
                jQuery("#type-icon").attr('src', '<?php echo powerpress_get_root_url(); ?>images/onboarding/cancel.svg')
            }
            jQuery("#type-icon").removeAttr('style');
            jQuery("#type-icon").attr('style', 'display: inline-block');

            let validate_url = 'https://castfeedvalidator.com/validate_colorspace?artwork-url=' + encodeURIComponent(url);
            jQuery("#colorspace-icon").removeAttr('src');
            jQuery("#colorspace-icon").attr('src', validate_url);
            jQuery("#colorspace-icon").removeAttr('style');
            jQuery("#colorspace-icon").attr('style', 'display: inline-block');

        };
        let url = jQuery('#preview_image').attr("src");
        img.src = url;
    }
    jQuery(document).ready(function() {
        jQuery("#filePath").val(jQuery("#preview_image").attr('src').replace(/https?:\/\/.*\/uploads\/powerpress\//i, ''));
        let title = jQuery("#input-title").val().replace(" ", "_");
        let category = jQuery("#apple_cat").val();
        let explicit = jQuery("input[type=radio]:checked").val();
        jQuery("#basic-details").val("TITLE: " + title + " CATEGORY: " + category + " EXPLICIT: " + explicit);
        jQuery("#input-title").on("input", function() {
            refreshDetails();
        });
        jQuery("#apple_cat").on("change", function() {
            refreshDetails();
        });
        jQuery("input[type=radio]").on("change", function() {
            refreshDetails();
        });
        jQuery("#continue-button").on("click", function () {
            let valid_image = true;
            jQuery(".success-fail-icon").each(function(index) {
                if (jQuery(this).attr("src").includes("cancel.svg")) {
                    valid_image = false;
                }
            });
            if (valid_image) {
                jQuery('#artwork :input').not(':submit').clone().hide().appendTo('#basic-feed');
                jQuery("#basic-feed").submit();
            } else {
                jQuery("#error-container").removeAttr('style');
            }
        });
        verifyImage();
    });

    function refreshDetails() {
        let title = jQuery("#input-title").val().replace(" ", "_");
        let category = jQuery("#apple_cat").val();
        let explicit = jQuery("input[type=radio]:checked").val();
        jQuery("#basic-details").val("TITLE: " + title + " CATEGORY: " + category + " EXPLICIT: " + explicit);
    }

    document.getElementById("FileAttachment").onchange = function () {
        jQuery("#artwork").submit();
    };
</script>
