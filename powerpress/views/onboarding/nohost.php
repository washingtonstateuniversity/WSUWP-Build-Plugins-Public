<?php
$GeneralSettings = powerpress_get_settings('powerpress_general');
if (isset($GeneralSettings['blubrry_auth']) && $GeneralSettings['blubrry_auth'] != null) {
    $next_page = 'createEpisode';
} else {
    $next_page = 'wantStats';
}
if (isset($_GET['from']) && $_GET['from'] == 'import') {
    $querystring_import = "&from=import";
} else {
    $querystring_import = "";
}
?>
<div class="wrap">
    <div class="pp_container">
        <h2 class="pp_align-center"><?php echo __('Would you like to host with Blubrry?', 'powerpress'); ?></h2>
        <h5 class="pp_align-center"><?php echo __('Don’t know what a podcast host is?', 'powerpress'); ?> <a style="color:blue" href="https://create.blubrry.com/manual/internet-media-hosting/"><?php echo __('Learn more', 'powerpress'); ?></a></h5>
        <hr  class="pp_align-center" />
        <p class="pp_align-center"><?php echo __('A podcast media host is essential for your show. If you host your files and RSS feed on WordPress, it can be very fragile and break.', 'powerpress'); ?> </p>

        <p class="pp_align-center"><?php echo __('Directories may reject your show if they find out you are not using a reliable hosting service.', 'powerpress'); ?></p>

        <section id="one" class="pp_wrapper" style="margin-top:25px;">
            <div class="pp_inner">

                <div class="pp_flex-grid">

                    <div class="pp_col">
                        <div class="pp_box">
                            <div class="pp_image pp_fit center">
                                <img src="<?php echo powerpress_get_root_url(); ?>images/onboarding/nohost.png" alt="" class="" />
                            </div>
                            <div class="pp_content">
                                <!--<div class="pp_align-center">-->
                                    <div class="btn-caption-container">
                                        <p class="pp_align-center"><?php echo __('You may self-host, but have limited options and security for your show.', 'powerpress'); ?></p>
                                    </div>
                                    <div class="pp_button-container">
                                        <a href="<?php echo admin_url("admin.php?page={$_GET['page']}&step=$next_page"); ?>">
                                            <button type="button" class="pp_button_alt"><span><?php echo __('No, thanks', 'powerpress'); ?></span></button>
                                        </a>
                                    </div>
                                <!--</div>-->
                            </div>
                        </div>
                    </div>

                    <div class="pp_col">
                        <div class="pp_box">
                            <div class="pp_image center">
                                <img src="<?php echo powerpress_get_root_url(); ?>images/onboarding/blubrry.png" alt="" />
                            </div>
                            <div class="pp_content">
                                <!--<div class="pp_align-center">-->
                                    <div class="btn-caption-container">
                                        <p class="pp_align-center"><?php echo __('Secure media storage, unlimited bandwidth, and pro stats included. Create an account or sign in.', 'powerpress'); ?></p>
                                    </div>
                                    <div class="pp_button-container">
                                        <a href="<?php echo admin_url("admin.php?page={$_GET['page']}&step=blubrrySignin$querystring_import"); ?>">
                                            <button type="button" class="pp_button"><span><?php echo __('Host with Blubrry', 'powerpress'); ?></span></button>
                                        </a>
                                    </div>
                                <!--</div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>