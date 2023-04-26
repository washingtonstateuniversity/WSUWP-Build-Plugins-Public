
<div class="woocommerce">
    <div class="wrap wt-crp-container">
        <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
        <?php settings_errors(); ?>
        <?php do_action('wt_crp_before_settings_block'); ?>
        <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
            <a href="<?php echo admin_url('admin.php?page=wt-woocommerce-related-products') ?>" class="nav-tab <?php echo ($tab == 'related-product') ? 'nav-tab-active' : ''; ?>"><?php _e('Related Products', 'wt-woocommerce-related-products'); ?></a>

            <a href="<?php echo admin_url('admin.php?page=wt-woocommerce-related-products&tab=other-solutions'); ?>" class="nav-tab <?php echo ('other-solutions' == $tab) ? 'nav-tab-active' : ''; ?>"><?php _e('Other Solutions', 'wt-woocommerce-related-products'); ?></a>

        </h2>
        <?php
        switch ($tab) {
            case "related-product" :
                $this->admin_related_product_page();
                break;
            case "other-solutions" :
                $this->admin_other_solution_page();

                break;
            default :
                $this->admin_related_product_page();

                break;
        }
        ?>
    </div>
</div>