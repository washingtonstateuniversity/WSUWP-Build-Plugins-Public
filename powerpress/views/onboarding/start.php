<?php
$Settings = get_option('powerpress_general');
if (!isset($Settings['pp_onboarding_incomplete'])) {
    powerpress_save_settings(array('pp_onboarding_incomplete' => 1, 'powerpress_general'));
}
?>
<div class="wrap">
    <div class="pp_container">
        <h2 class="pp_align-center"><?php echo __('Welcome to PowerPress','powerpress'); ?><img id="blubrry-logo-onboarding" src="<?php echo powerpress_get_root_url(); ?>images/blubrry_icon.png" alt="" /> </h2>
        <h5 class="pp_align-center"><?php echo __('Let\'s get started','powerpress'); ?></h5>
        <hr  class="pp_align-center" />
        <h1 class="pp_align-center"><?php echo __('Have you started your podcast?','powerpress'); ?></h1>
        <h5 class="pp_align-center"><?php echo __('Do you already have an RSS feed?','powerpress'); ?></h5>
        <br />
        <section id="one" class="pp_wrapper">
            <div class="pp_inner">

                <div class="pp_flex-grid">
                    <div class="pp_col">
                        <div class="pp_box">
                            <div class="pp_image pp_fit center">
                                <img src="<?php echo powerpress_get_root_url(); ?>images/onboarding/no_start.png" alt="" class="" />
                            </div>
                            <div class="pp_content">
                                <div class="btn-caption-container" style="margin-top:3em; height: 40%;">
                                    <p class="pp_align-center"><?php echo __('No worries. We\'ll get a feed set up for you now.','powerpress'); ?></p>
                                </div>
                                <!--<footer class="pp_align-center">-->
                                    <div class="pp_button-container">
                                        <a href="<?php echo admin_url("admin.php?page={$_GET['page']}&step=showBasics"); ?>">
                                            <button type="button" class="pp_button_alt"><span><?php echo __('No','powerpress'); ?></span></button>
                                        </a>
                                    </div>
                                <!--</footer>-->
                            </div>
                        </div>
                    </div>

                    <div class="pp_col">
                        <div class="pp_box">
                            <div class="pp_image pp_fit center">
                                <img src="<?php echo powerpress_get_root_url(); ?>images/onboarding/yes_start.png" alt="" />
                            </div>
                            <div class="pp_content">
                                <div class="btn-caption-container" style="margin-top:3em; height: 40%;">
                                    <p class="pp_align-center"><?php echo __('Great! Next step, we\'ll import your podcast feed.','powerpress'); ?></p>
                                </div>
                                    <!--<footer class="pp_align-center">-->
                                    <div class="pp_button-container">
                                        <a href="<?php echo $_GET['page'] == 'powerpressadmin_basic' ? admin_url("admin.php?import=powerpress-rss-podcast&from=onboarding") : admin_url("admin.php?import=powerpress-rss-podcast&from=gs"); ?>">
                                            <button type="button" class="pp_button"><span><?php echo __('Yes','powerpress'); ?></span></button>
                                        </a>
                                    </div>
                                <!--</footer>-->
                            </div>
                        </div>
                    </div>
            </div>
                    <div class="pp_button-container" style="float: right;margin-top: 1em;">
                        <a href="<?php echo admin_url("admin.php?page={$_GET['page']}&step=createEpisode"); ?>">
                            <?php echo __('Skip &rarr;','powerpress'); ?>
                        </a>
                    </div>
    </div>
</div>

