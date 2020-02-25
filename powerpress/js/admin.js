function powerpress_verifyMedia(el) {
    let feed_slug = el.id.replace("verify-button-", "");
    powerpress_get_media_info(feed_slug);
}

var interval = false;

function powerpress_openTab(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    let feed_slug = event.currentTarget.id.substring(1);
    evt.preventDefault();

    // Get all elements with class="pp-tabcontent" and hide them
    tabcontent = document.getElementsByClassName("pp-tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";

    //Set/unset the interval for updating artwork previews
    if (cityName == 'artwork-' + feed_slug && !interval) {
        let el = jQuery("#powerpress_itunes_image_" + feed_slug);
        interval = setInterval(function() { powerpress_insertArtIntoPreview(el[0]); }, 1000);
    }
    if (cityName != 'artwork-' + feed_slug && interval) {
        clearInterval(interval);
        interval = false;
    }
}

//Controls the three-way explicit setting switch
function powerpress_changeExplicitSwitch(el) {
    let id = el.id;
    let feed_slug = id.replace("not-set", "");
    feed_slug = feed_slug.replace("clean", "");
    feed_slug = feed_slug.replace("explicit", "");
    let not_set = jQuery('#not-set' + feed_slug);
    not_set.removeAttr("class");
    not_set.removeAttr("style");
    let clean = jQuery('#clean' + feed_slug);
    clean.removeAttr("class");
    clean.removeAttr("style");
    let explicit = jQuery('#explicit' + feed_slug);
    explicit.removeAttr("class");
    explicit.removeAttr("style");
    let select = jQuery('#pp-explicit-container' + feed_slug + ' > input');
    if (id.includes("not-set")) {
        not_set.attr("class", "explicit-selected");
        clean.attr("class", "pp-explicit-option");
        clean.attr("style", "border-left: 1px solid #b3b3b3;border-right: 1px solid #b3b3b3;");
        explicit.attr("class", "pp-explicit-option");
        select.val(0);
    } else if (id.includes("clean")) {
        not_set.attr("class", "pp-explicit-option");
        not_set.attr("style", "border-right: 1px solid #b3b3b3;");
        clean.attr("class", "explicit-selected");
        explicit.attr("class", "pp-explicit-option");
        explicit.attr("style", "border-left: 1px solid #b3b3b3");
        select.val(2);
    } else if (id.includes("explicit")) {
        not_set.attr("class", "pp-explicit-option");
        clean.attr("class", "pp-explicit-option");
        clean.attr("style", "border-left: 1px solid #b3b3b3;border-right: 1px solid #b3b3b3;");
        explicit.attr("class", "explicit-selected");
        select.val(1);
    }
}

function powerpress_toggleMetamarksSettings(el) {
    var feed_slug;
    let row_num_array = el.id.split("-");
    let row_num = row_num_array[3];
    let feed_slug_array = row_num_array.splice(4, 1);
    if (feed_slug_array.length > 1) {
        feed_slug = feed_slug_array.join("-");
    } else {
        feed_slug = feed_slug_array[0];
    }
    let button_id = "#" + el.id;
    let button = jQuery(button_id);
    let div_id = "#pp-hide-metamark-" + feed_slug + "-" + row_num;
    let type_select_id = "#pp-metamark-type-" + feed_slug + "-" + row_num;
    let type_preview_id = "#pp-metamark-preview-type--" + feed_slug + "-" + row_num;
    let pos_input_id = "#pp-metamark-pos-" + feed_slug + "-" + row_num;
    let pos_preview_id = "#pp-metamark-preview-pos-" + feed_slug + "-" + row_num;
    jQuery(div_id).toggleClass('pp-hidden-settings');
    console.log("tried to toggle class " + div_id);
    if (button.text().includes('Edit')) {
        button.html("Save");
    } else {
        button.html("Edit");
        let type = jQuery(type_select_id).find(":selected").text();
        let pos = jQuery(pos_input_id).val();
        jQuery(type_preview_id).html(type);
        jQuery(pos_preview_id).html(pos);
    }
}

function powerpress_showHideMediaDetails(el) {
    let feed_slug = el.id.replace("show-details-link-", "");
    //feed_slug = feed_slug.replace("hide-details-link-", "");
    let show_det = jQuery("#show-details-link-" + feed_slug);
    //let hide_det = jQuery("#hide-details-link-" + feed_slug);
    let div = jQuery("#hidden-media-details-" + feed_slug);
    show_det.toggleClass('pp-hidden-settings');
    //hide_det.toggleClass('pp-hidden-settings');
    show_det.toggleClass('media-details');
    //hide_det.toggleClass('media-details');
    div.toggleClass('pp-hidden-settings');
}

function powerpress_showHideAppleAdvanced(el) {
    let feed_slug;
    let show_det;
    let new_id;
    if (el.id.includes("show")) {
        feed_slug = el.id.replace("show-apple-link-", "");
        show_det = jQuery("#show-apple-link-" + feed_slug);
        show_det.attr("aria-pressed", "true");
        new_id = "hide-apple-link-" + feed_slug;
        show_det.html(hide_settings);
    } else {
        feed_slug = el.id.replace("hide-apple-link-", "");
        show_det = jQuery("#hide-apple-link-" + feed_slug);
        show_det.attr("aria-pressed", "false");
        new_id = "show-apple-link-" + feed_slug;
        show_det.html(show_settings);
    }
    el.id = new_id;
    let div = jQuery("#apple-advanced-settings-" + feed_slug);
    div.toggleClass('pp-hidden-settings');
}

function powerpress_changeMediaFile(el) {
    let feed_slug = el.id.replace("pp-edit-media-button-", "");
    let input = jQuery("#pp-url-input-container-" + feed_slug);
    input.removeAttr("style");
    input.attr("style", "display: inline-block");
    let show_input = jQuery("#powerpress_url_show_" + feed_slug);
    show_input.removeAttr("style");
    show_input.attr("style", "display: none");
    let edit_media = jQuery("#edit-media-file-" + feed_slug);
    edit_media.removeAttr("style");
    edit_media.attr("style", "display: none");
    let buttons = jQuery("#pp-change-media-file-" + feed_slug);
    buttons.removeAttr("style");
    buttons.attr("style", "display: inline-block");
    let above_label = jQuery("#pp-url-input-above-" + feed_slug);
    let below_label = jQuery("#pp-url-input-below-" + feed_slug);
    above_label.removeAttr("style");
    below_label.removeAttr("style");
}

//save button for edit media link
function powerpress_saveMediaFile(el) {
    let feed_slug = el.id.replace("save-media-", "");
    powerpress_get_media_info(feed_slug);
    let player_size = jQuery("#pp-player-size-" + feed_slug);
    let player_size_line = jQuery("#line-above-player-size-" + feed_slug);
    let link = jQuery("#pp-url-input-container-" + feed_slug + " > input").val();
    let display_filename = jQuery("#ep-box-filename-" + feed_slug);
    let link_parts = "";
    let input = jQuery("#pp-url-input-container-" + feed_slug);
    let show_input = jQuery("#powerpress_url_show_" + feed_slug);
    let edit_media = jQuery("#edit-media-file-" + feed_slug);
    let buttons = jQuery("#pp-change-media-file-" + feed_slug);
    let warning = jQuery("#file-change-warning-" + feed_slug);
    let above_label = jQuery("#pp-url-input-above-" + feed_slug);
    let below_label = jQuery("#pp-url-input-below-" + feed_slug);
    if (link !== '') {
        let video_types = [".mp4", ".m4v", ".webm", ".ogg", ".ogv"];
        let video = false;
        video_types.forEach(function(element) {
            if (link.endsWith(element)) {
                player_size.removeAttr("style");
                player_size.attr("style", "display: block");
                player_size_line.removeAttr("style");
                player_size_line.attr("style", "display: block");
                video = true;
            }
        });
        if (!video) {
            player_size.removeAttr("style");
            player_size.attr("style", "display: none");
            player_size_line.removeAttr("style");
            player_size_line.attr("style", "display: none");
        }
        if (link.includes("/")) {
            link_parts = link.split("/");
        } else {
            link_parts = link.split("\\");
        }
        let fname = link_parts.pop();
        display_filename.html(fname);
        warning.css('display', 'none');
        input.removeAttr("style");
        input.attr("style", "display: none");
        show_input.removeAttr("style");
        show_input.attr("style", "display: inline-block");
        edit_media.removeAttr("style");
        edit_media.attr("style", "display: inline-block");
        buttons.removeAttr("style");
        buttons.attr("style", "display: none");
        above_label.attr("style", "display: none");
        below_label.attr("style", "display: none");
    } else {
        warning.css('display', 'block');
        warning.addClass("error");
    }
}

//Continue button for adding media to a post
function powerpress_continueToEpisodeSettings(el) {
    let feed_slug = el.id.replace("continue-to-episode-settings-", "");
    powerpress_get_media_info(feed_slug);
    let player_size = jQuery("#pp-player-size-" + feed_slug);
    let player_size_line = jQuery("#line-above-player-size-" + feed_slug);
    let link = jQuery("#pp-url-input-container-" + feed_slug + " > input").val();
    let file_input = jQuery("#pp-url-input-container-" + feed_slug);
    let file_show = jQuery("#powerpress_url_show_" + feed_slug);
    let display_filename = jQuery("#ep-box-filename-" + feed_slug);
    let link_parts = [];
    let tab_container = jQuery("#tab-container-" + feed_slug);
    let warning = jQuery("#file-select-warning-" + feed_slug);
    let edit_file = jQuery("#edit-media-file-" + feed_slug);
    let select_file = jQuery("#select-media-file-" + feed_slug);
    let head = jQuery("#pp-pp-selected-media-head-" + feed_slug);
    let above_label = jQuery("#pp-url-input-above-" + feed_slug);
    let below_label = jQuery("#pp-url-input-below-" + feed_slug);
    let container = jQuery("#a-pp-selected-media-" + feed_slug);
    let details = jQuery("#media-file-details-" + feed_slug);
    if (link.length > 0) {
        let video_types = [".mp4", ".m4v", ".webm", ".ogg", ".ogv"];
        let video = false;
        video_types.forEach(function(element) {
            if (link.endsWith(element)) {
                player_size.removeAttr("style");
                player_size.attr("style", "display: block");
                player_size_line.removeAttr("style");
                player_size_line.attr("style", "display: block");
                video = true;
            }
        });
        if (!video) {
            player_size.removeAttr("style");
            player_size.attr("style", "display: none");
            player_size_line.removeAttr("style");
            player_size_line.attr("style", "display: none");
        }
        file_show.attr("title", link);
        if (link.includes("/")) {
            link_parts = link.split("/");
        } else {
            link_parts = link.split("\\");
        }
        let fname = link_parts.pop();
        display_filename.html(fname);
        tab_container.removeAttr("style");
        tab_container.attr("style", "display: block");
        select_file.removeAttr("style");
        select_file.attr("style", "display: none");
        edit_file.removeAttr("style");
        edit_file.attr("style", "display: inline-block");
        file_input.removeAttr("style");
        file_input.attr("style", "display: none");
        file_show.removeAttr("style");
        file_show.attr("style", "display: inline-block");
        warning.removeAttr("style");
        warning.attr("style", "display: none");
        head.removeAttr("style");
        head.attr("style", "display: none");
        above_label.attr("style", "display: none");
        below_label.attr("style", "display: none");
        details.removeAttr("style");
        details.attr("style", "display: inline-block");
        container.removeAttr("style");
    } else {
        warning.css('display', 'block');
        warning.addClass("error");
    }
}

//keeps art previews up to date
function powerpress_insertArtIntoPreview(el) {
    let feed_slug = el.id.replace("powerpress_itunes_image_", "");
    let art_input = "#powerpress_itunes_image_" + feed_slug;
    let poster_input = "#powerpress_image_" + feed_slug;
    let episode_artwork = jQuery(art_input);
    let img_tag = jQuery("#pp-image-preview-" + feed_slug);
    let caption_tag = jQuery("#pp-image-preview-caption-" + feed_slug);
    let poster_image = jQuery(poster_input);
    let poster_img_tag = jQuery("#poster-pp-image-preview-" + feed_slug);
    let poster_caption_tag = jQuery("#poster-pp-image-preview-caption-" + feed_slug);
    if (poster_img_tag.attr("src") != poster_image.val()) {
        poster_img_tag.attr("src", poster_image.val());
        let filename = "";
        if (poster_image.val().includes("/")) {
            let parts = poster_image.val().split("/");
            filename = parts.pop();
        } else {
            let parts = poster_image.val().split("\\");
            filename = parts.pop();
        }
        poster_caption_tag[0].innerHTML = filename;
    }
    if (img_tag.attr("src") != episode_artwork.val()) {
        img_tag.attr("src", episode_artwork.val());
        let filename = "";
        if (episode_artwork.val().includes("/")) {
            let parts = episode_artwork.val().split("/");
            filename = parts.pop();
        } else {
            let parts = episode_artwork.val().split("\\");
            filename = parts.pop();
        }
        caption_tag[0].innerHTML = filename;
    }
}