<?php
    require_once POWERPRESS_ABSPATH.'/powerpressadmin.php';
    powerpress_save_settings(array('pp_onboarding_incomplete' => 0, 'powerpress_general'));
    if (isset($_GET['migrate']) && $_GET['migrate']) {
        $message_style = 'display: block;';
    } else {
        $message_style = 'display: none;';
    }
?>
<div class="pp_container">
    <h2 class="pp_align-center"><?php echo __('Time to work on a new episode', 'powerpress'); ?></h2>
    <hr  class="pp_align-center" />
    <p class="pp_align-center"><?php echo __('Get started by creating your first post and adding an episode.', 'powerpress'); ?></p>
    <p class="pp_align-center" style="<?php echo $message_style; ?>"><?php echo __('Looks like you have some media migrating to your Blubrry hosting account.', 'powerpress'); ?>&nbsp;<a href="<?php echo admin_url("admin.php?page=powerpress/powerpressadmin_migrate.php"); ?>"><?php echo __('Click here to view the progress.','powerpress'); ?></a></p>
    <section id="one" class="pp_wrapper" style="margin-top:25px;">
        <div class="pp_inner">

            <div class="pp_flex-grid">

                <div class="pp_col">
                    <div class="pp_box">
                        <div class="pp_image center">
                            <img src="<?php echo powerpress_get_root_url(); ?>images/onboarding/mic.png" alt="" />
                        </div>
                        <div class="pp_content">
                            <!--<footer class="pp_align-center" style="height: 100%">-->
                                <div class="btn-caption-container">
                                    <p class="pp_align-center" style="margin-bottom: 1ch;"><?php echo __('Release a new episode or blog post.', 'powerpress'); ?></p>
                                    <p style="text-align: center; font-size: 14px; margin-top: 0;"><?php echo __('FYI: Before submitting to podcast directories, you are required to have at least one published episode.', 'powerpress'); ?></p>
                                </div>
                                <div class="pp_button-container">
                                    <a href="<?php echo admin_url('post-new.php') ?>"><button type="button" class="pp_button"><span><?php echo __('Create a new episode', 'powerpress'); ?></span></button></a>
                                </div>
                            <!--</footer>-->
                        </div>
                    </div>
                </div>

                <div class="pp_col">
                    <div class="pp_box">
                        <div class="pp_image pp_fit center">
                            <img src="<?php echo powerpress_get_root_url(); ?>images/onboarding/planning.png" alt="" class="" />
                        </div>
                        <div class="pp_content">
                            <!--<footer class="pp_align-center" style="height: 100%">-->
                                <div class="btn-caption-container">
                                    <p class="pp_align-center"><?php echo __('Continue with PowerPress options for your feed and website.', 'powerpress'); ?></p>
                                </div>
                                <div class="pp_button-container">
                                    <a href="<?php echo admin_url('admin.php?page=powerpressadmin_basic') ?>">
                                        <button type="button" class="pp_button_alt"><span><?php echo __('Skip and go to settings', 'powerpress'); ?></span></button>
                                    </a>
                                </div>
                            <!--</footer>-->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


