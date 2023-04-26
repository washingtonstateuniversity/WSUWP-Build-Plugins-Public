<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Custom_Related_Products
 * @subpackage Custom_Related_Products/admin/partials
 */
?>

<div class="wrap wt-crp-container">
    <?php settings_errors(); ?>
    <div class="crp-main-container" style="width: 68%;display: inline-block;">
        <p style="font-size: 16px;">
            <b><?php _e('Settings', 'wt-woocommerce-related-products'); ?></b>
        </p>
        <p style="border-top: 1px dashed rgb(204, 204, 204); padding-top: 5px;"></p>
        <div>
            <form action="options.php" method="post">
                <?php
                settings_fields($this->plugin_name);
                do_settings_sections($this->plugin_name);
                submit_button();
                ?>
            </form>
        </div>
    </div>
    <div style="width: 27%;float: <?php echo is_rtl() ? 'left;' : 'right;' ?> margin-right: 10px;">
        <div style="background: #fff; height:auto; padding: 15px; box-shadow: 0px 0px 2px #ccc;">
            <h2 style="text-align: center;margin-top: 10px;"><?php _e('Watch setup video', 'wt-woocommerce-related-products'); ?></h2>
            <iframe src="//www.youtube.com/embed/KOMx3g-ZMQs" allowfullscreen="allowfullscreen" frameborder="0" align="middle" style="width:100%;margin-bottom: 1em;margin-top: 4px;"></iframe>
        </div>

        <div class="wt-crp-upsell-wrapper market-box table-box-main" style="margin-top: 14px;">
            <div class="crp-premium-upgrade wt-crppro-sidebar">

                <div class="wt-crppro-header">
                    <div class="wt-crppro-name">
                        <div style="float: left"><img src="<?php echo CRP_PLUGIN_URL; ?>admin/img/gopro/bestseller.svg" alt="featured img" width="36" height="36"></div>
                        <div style="float: right">
                            <h4 class="wt-crp-product-name"><?php _e('Best Sellers for WooCommerce'); ?></h4>
                        </div>
                    </div>


                    <div class="wt-crppro-mainfeatures">
                        <div class="wt-crppro-btn-wrapper">
                            <a href="<?php echo esc_url("https://www.webtoffee.com/product/woocommerce-best-sellers/?utm_source=free_plugin_sidebar&utm_medium=related_free_plugin&utm_campaign=WooCommerce_Best_Sellers"); ?>" class="wt-crppro-blue-btn-suite" target="_blank"><?php _e('GET THE PLUGIN'); ?> <span class="dashicons dashicons-arrow-right-alt"></span></a>
                        </div> 
                        <ul class="wt-crp-suite-moneyback-wrap">
                            <li class="money-back"><?php _e('30 Day Money Back Guarantee'); ?></li>
                            <li class="support"><?php _e('Fast and Superior Support'); ?></li>
                        </ul>               
                    </div>
                    <div class="wt-crp-product wt-crp-product_review wt-crp-product_tags wt-crp-product_categories wt-crp-gopro-cta wt-crppro-features">
                        <ul class="ticked-list wt-crppro-allfeat">						
                            <li><?php _e('Creates a Best Seller page'); ?></li>							
                            <li><?php _e('Adds bestseller label to thumbnails'); ?></li>
                            <li><?php _e('Rank products based on sales'); ?></li>            
                            <li><?php _e('Use custom sales count period'); ?></li>
                            <li><?php _e('Supports simple & variable products'); ?></li>
                            <li><?php _e('Feature best-selling products in a slider'); ?></li>
                            <li><?php _e('Displays a bestseller seal on product pages'); ?></li>
                            <li><?php _e('Customize the label and seal'); ?></li>
                            <li><?php _e('Set limit for bestsellers'); ?></li>
                        </ul>                        
                    </div>


                </div>		

            </div>
        </div>
        <div class="wt-crp-upsell-wrapper market-box table-box-main" style="margin-top: 14px;">
            <div class="crp-premium-upgrade wt-crppro-sidebar">

                <div class="wt-crppro-header">
                    <div class="wt-crppro-name">
                        <div style="float: left"><img src="<?php echo CRP_PLUGIN_URL; ?>admin/img/gopro/freeq.svg" alt="featured img" width="36" height="36"></div>
                        <div style="float: right">
                            <h4 class="wt-crp-product-name"><?php _e('Frequently Bought Together for WooCommerce'); ?></h4>
                        </div>
                    </div>


                    <div class="wt-crppro-mainfeatures">
                        <div class="wt-crppro-btn-wrapper">
                            <a href="<?php echo esc_url("https://www.webtoffee.com/product/woocommerce-frequently-bought-together/?utm_source=free_plugin_sidebar&utm_medium=related_free_plugin&utm_campaign=Frequently_Bought_Together"); ?>" class="wt-crppro-blue-btn-suite" target="_blank"><?php _e('GET THE PLUGIN'); ?> <span class="dashicons dashicons-arrow-right-alt"></span></a>
                        </div> 
                        <ul class="wt-crp-suite-moneyback-wrap">
                            <li class="money-back"><?php _e('30 Day Money Back Guarantee'); ?></li>
                            <li class="support"><?php _e('Fast and Superior Support'); ?></li>
                        </ul>               
                    </div>

                    <div class="wt-crp-product wt-crp-product_review wt-crp-product_tags wt-crp-product_categories wt-crp-gopro-cta wt-crppro-features">
                        <ul class="ticked-list wt-crppro-allfeat">						
                            <li><?php _e('Adds a frequently bought together section'); ?></li>							
                            <li><?php _e('Offer discounts on product bundle'); ?></li>
                            <li><?php _e('Add custom & related products'); ?></li>            
                            <li><?php _e('Add upsells, cross-sells'); ?></li>
                            <li><?php _e('Limit the number of products'); ?></li>
                            <li><?php _e('Personalize texts and labels'); ?></li>
                            <li><?php _e('Customize the display fields'); ?></li>
                        </ul>                        
                    </div>


                </div>		

            </div>
        </div>

        <div class="wt_go-review">
            <h3 style="text-align: center;"><?php echo __('Like this plugin?', 'wt-woocommerce-related-products'); ?></h3>
            <p><?php echo __('If you find this plugin useful please show your support and rate it', 'wt-woocommerce-related-products'); ?> <a href="https://wordpress.org/support/plugin/wt-woocommerce-related-products/reviews/#new-post" target="_blank" style="color: #ffc600; text-decoration: none;">★★★★★</a><?php echo __(' on', 'wt-woocommerce-related-products'); ?> <a href="https://wordpress.org/support/plugin/wt-woocommerce-related-products/reviews/#new-post" target="_blank">WordPress.org</a> -<?php echo __('  much appreciated!', 'wt-woocommerce-related-products'); ?> :)</p>

        </div>
                
        
                        <div class="wt_go-link">
                            <p style="font-size: 16px;text-align: center;">
                                <b><?php _e('Quick links', 'wt-woocommerce-related-products'); ?></b>
                            </p>
                            <p style="font-size: 14px;">
        <?php _e('Easily display related products for your products on your site based on category, tag or product. Learn how to:', 'wt-woocommerce-related-products'); ?>
                            </p>
                            <ul style="margin-top:0px; list-style:disc; margin-left:15px;font-size: 14px;line-height: 25px;">
                                        <li> <?php echo sprintf(__('%s Relate products by category, tag or both %s', 'wt-woocommerce-related-products'), '<a href="https://www.webtoffee.com/related-products-woocommerce-user-guide/#by-category" target="_blank">', '</a>'); ?> </li>
                                        <li> <?php echo sprintf(__('%s Relate products individually for each product %s', 'wt-woocommerce-related-products'), '<a href="https://www.webtoffee.com/related-products-woocommerce-user-guide/#individually" target="_blank">', '</a>'); ?></li>
                                        <li> <?php echo sprintf(__('%s Exclude products from displaying as related products %s', 'wt-woocommerce-related-products'), '<a href="https://www.webtoffee.com/related-products-woocommerce-user-guide/#exclude-category" target="_blank">', '</a>'); ?></li>
                                        <li> <?php echo sprintf(__('%s Display related products using shortcodes %s', 'wt-woocommerce-related-products'), '<a href="https://www.webtoffee.com/related-products-woocommerce-user-guide/#using-shortcode" target="_blank">', '</a>'); ?></li>
                            </ul>
                        </div>
    </div>


</div>

<style>
    .wt_go-review{
        background: #fff;
        float: left;
        /*border-radius: 4px;*/
        height:auto;
        padding: 15px;
        box-shadow: 0px 0px 2px #ccc;
        margin: 15px 0px;
    }
    .wt_go-link{
        background: #fff;
        float: left;
        /*border-radius: 4px;*/
        height:auto;
        padding: 15px;
        box-shadow: 0px 0px 2px #ccc;
    }
    .wt_go-review h3{
        text-align: center;
    }
    .wt-blue-info{
        color: #646970;
        background-color: #d9edf7;
        border-color: #bce8f1;
        padding: 2px 18px 18px 18px;
        border: 1px solid transparent;
        border-radius: 4px;
        margin-top: 32px;
    }
    .wt-crp-container .form-table th {
        width : 290px;
    }
    .crp-main-container {
        background-color: white;
        padding: 10px 10px 10px 20px;
    }

    .crp-paragraph {
        margin-top: 12px !important;
    }

    .crp-banner {
        width: 92%;
        margin-top: 5px;
        font-size: 12px;
    }
    .working-mode-field .description {
        margin: 0px 0px 15px 25px;
    }
    .crp-disallow {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .wt-crp-container .crp-disallow select, .wt-crp-container .crp-disallow label, .wt-crp-container .crp-disallow input, .wt-crp-container .crp-disallow span, .wt-crp-container .crp-disallow li {
        cursor: not-allowed !important;
    }
    .wt-crp-container fieldset span.select2 {
        width: 320px !important;
    }
    .wt-crp-select, .wt-crp-input {
        width: 320px;
    }
    .crp-info {
        font-size: 13px;
    }
    .crp-info-box {
        color: #646970;
        background-color: #d9edf7;
        border-color: #bce8f1;
        padding: 14px;
        margin-bottom: -14px;
        border: 1px solid transparent;
        border-radius: 4px;
        margin-top: 18px;
    }

    .crp-alert {
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }
    .crp-seconday-alert {
        color: #383d41;
        background-color: #e2e3e5;
        border-color: #d6d8db;
    }

    .crp-info-alert {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    .crp-warning-alert {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
    .wt-crp-note {
        font-size: 13px !important;
    }
    .crp-overide-theme .crp-alert {
        margin-top: 8px;
        width: 70%;
        display: none;
    }
    .wt_crp_branding {
        text-align: end;
        width: 100%;
        margin-bottom: 10px;
    }
    .wt_crp_brand_label {
        width: 100%;
        padding-bottom: 10px;
        font-size: 11px;
        font-weight: 600;
    }
    .wt_crp_brand_logo img {
        max-width: 100px;
    }

    .inner-addon {
        position: relative;
        margin: 10px;
    }

    /* style icon */
    .inner-addon .glyphicon {
        position: absolute;
        /*padding: 10px;*/
        pointer-events: none;
    }

    /* align icon */
    .left-addon .glyphicon  {
        left:  0px;
    }
    .right-addon .glyphicon {
        right: 0px;
    }

    /* add padding  */
    .left-addon input  {
        padding-left:  40px !important;
    }
    .right-addon input {
        padding-right: 30px;
    }
    .wt-preview-desktop:before{
        content: "\f472";
        display: inline-block;
        -webkit-font-smoothing: antialiased;
        font: normal 20px/21px dashicons;
        vertical-align: top;
        margin: 1px 2px;
        padding: 4px 4px;
        border-right: 1px solid #8c8f94;
    }
    .wt-preview-tablet:before{
        content: "\f471";
        display: inline-block;
        -webkit-font-smoothing: antialiased;
        font: normal 20px/21px dashicons;
        vertical-align: top;
        margin: 1px 2px;
        padding: 4px 4px;
        border-right: 1px solid #8c8f94;
    }
    .wt-preview-mobile:before{
        content: "\f470";
        display: inline-block;
        -webkit-font-smoothing: antialiased;
        font: normal 20px/21px dashicons;
        vertical-align: top;
        margin: 1px 2px;
        padding: 4px 4px;
        border-right: 1px solid #8c8f94;
    }
    #custom_related_products_crp_banner_product_width_mobile::-webkit-inner-spin-button,
    #custom_related_products_crp_banner_product_width_mobile::-webkit-outer-spin-button,
    #custom_related_products_crp_banner_product_width_tab::-webkit-inner-spin-button,
    #custom_related_products_crp_banner_product_width_tab::-webkit-outer-spin-button,
    #custom_related_products_crp_banner_product_width_desk::-webkit-inner-spin-button,
    #custom_related_products_crp_banner_product_width_desk::-webkit-outer-spin-button {

        opacity: 1;

    }



</style>

