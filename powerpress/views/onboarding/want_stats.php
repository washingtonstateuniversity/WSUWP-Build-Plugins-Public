<?php
$pp_nonce = powerpress_login_create_nonce();
?>
<div class="wrap">
    <div class="pp_container">
        <h2 class="pp_align-center"><?php echo __('Gain access to free tools', 'powerpress'); ?></h2>
        <hr  class="pp_align-center" />
        <p class="pp_align-center"><?php echo __('Signing up for a free Blubrry account will allow you to use a number of helpful features, free of charge.', 'powerpress'); ?> </p>


        <section id="one" class="pp_wrapper" style="margin-top:25px;">
            <div class="pp_inner">

                <div class="pp_flex-grid">

                    <div class="pp_col">
                        <div class="pp_box">
                            <div class="pp_image pp_fit center">
                                <img src="<?php echo powerpress_get_root_url(); ?>images/onboarding/self_host.png" alt="" class="" />
                            </div>
                            <div class="pp_content">
                                <!--<div class="pp_align-center">-->
                                <div class="btn-caption-container">
                                    <p class="pp_align-center"><?php echo __('I\'ll continue uploading my episodes to my host and website.', 'powerpress'); ?></p>
                                </div>
                                <div class="pp_button-container">
                                    <a href="<?php echo admin_url("admin.php?page={$_GET['page']}&step=createEpisode"); ?>">
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
                                <img src="<?php echo powerpress_get_root_url(); ?>images/onboarding/free_tools.png" alt="" />
                            </div>
                            <div class="pp_content">
                                <!--<div class="pp_align-center">-->
                                <div class="btn-caption-container">
                                    <p class="pp_align-center"><?php echo __('Don\'t miss out on Blubrry\'s free podcast stats and directory listing.', 'powerpress'); ?></p>
                                </div>
                                <div class="pp_button-container">
                                    <a href="<?php echo add_query_arg( '_wpnonce', $pp_nonce, admin_url("admin.php?page={$_GET['page']}&step=blubrrySignin")); ?>">
                                        <button type="button" class="pp_button"><span><?php echo __('I\'d like free tools', 'powerpress'); ?></span></button>
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