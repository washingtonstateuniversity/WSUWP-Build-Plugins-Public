<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_Related_Products
 * @subpackage Custom_Related_Products/public
 * @author     markhf
 */
class Custom_Related_Products_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action('wp_footer', array($this, 'wt_process_slider_script'), 99999);
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
		$slider_state	 = get_option( 'custom_related_products_slider','enable' );
		if('enable' == $slider_state){
                     wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/custom-related-products-public.css', array(), $this->version, 'all');
                     wp_enqueue_style('carousel-css', plugin_dir_url(__FILE__) . 'css/owl.carousel.min.css', array(), $this->version, 'all');
                     wp_enqueue_style('carousel-theme-css', plugin_dir_url(__FILE__) . 'css/owl.theme.default.min.css', array(), $this->version, 'all');
                    //wp_enqueue_style('bxslider-css', plugin_dir_url(__FILE__) . 'css/jquery.bxslider.min.css', array(), $this->version, 'all');
                    //wp_enqueue_style('swiper-css', plugin_dir_url(__FILE__) . 'css/swiper.min.css', array(), $this->version, 'all');
		}
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        //wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/custom-related-products-public.js', array('jquery'), $this->version, false);
		$slider_state	 = get_option( 'custom_related_products_slider','enable' );
		if('enable' == $slider_state){
                    wp_enqueue_script('wt-fa-js', plugin_dir_url(__FILE__) . 'js/826a7e3dce.js', array('jquery'), $this->version, false);
                    wp_enqueue_script('wt-owl-js', plugin_dir_url(__FILE__) . 'js/wt_owl_carousel.js', array('jquery'), $this->version, false);	
                }
    }

    public function crp_filter_related_products($args) {
        
        global $post;
        $related = get_post_meta($post->ID, '_crp_related_ids', true);

        $related_categories_ids = get_post_meta($product_id, '_crp_related_product_cats', true);
		$related_tags_ids = get_post_meta($product_id, '_crp_related_product_tags', true);

        $related = $this->get_product_category_ids($related_categories_ids,$related);
		$related = $this->get_product_tag_ids($related_tags_ids,$related);

        $disable = get_option('custom_related_products_disable');
        if (isset($related) && !empty($related) && !empty($disable)) { 
            $args['post__in'] = $related;
        } elseif(empty($disable)) { 
            $args = $args;
        }

        return $args;
    }

    public function crp_woocommerce_locate_template($template, $template_name, $template_path) {
       
       
        global $woocommerce;
        $_template = $template;

        if (!$template_path) {
            $template_path = $woocommerce->template_url;
        }

        $plugin_path = CRP_PLUGIN_DIR_PATH . '/woocommerce/';
        
        $template = locate_template(
                array(
                    $template_path . $template_name,
                    $template_name
                )
        );
       
        $overide_theme_rp = ( get_option('custom_related_products_overide_theme_rp', 'enable') == 'enable') ? true : false;
        $override_theme_template = apply_filters( 'wt_crp_override_theme_template', $overide_theme_rp );
        if ( ( !$template ||  $override_theme_template ) && file_exists($plugin_path . $template_name) ) {
            $template = $plugin_path . $template_name;
        }
        // Use default template

        if (!$template) {
            $template = $_template;
        }
        // Return what we found
        return $template;
    }
    function wt_woocommerce_output_related_products() {
        global $product;

		if ( ! $product ) {
			return;
		}
        $related_products = wc_get_related_products( $product->get_id() );
        wc_get_template( 'single-product/related.php', array( 'related_products' => $related_products ), plugin_dir_path( __FILE__ ) . 'woocommerce/', '' );
    }
    
    function crp_display_ids( $result, $product_id ) {
        
        $related_ids = get_post_meta( $product_id, '_crp_related_ids', true );

        $related_categories_ids = get_post_meta($product_id, '_crp_related_product_cats', true);
		
		$related_tag_ids = get_post_meta($product_id, '_crp_related_product_tags', true);

        $related_ids = $this->get_product_category_ids($related_categories_ids,$related_ids);
		$related_ids = $this->get_product_tag_ids($related_tag_ids,$related_ids);

        return empty( $related_ids ) ? $result : true;
		
     }
        
        
	function crp_remove_taxonomy( $result, $product_id ) {
            
        $related = get_post_meta( $product_id, '_crp_related_ids', true );
        
        $related_categories_ids = get_post_meta($product_id, '_crp_related_product_cats', true);
		
		$related_tag_ids = get_post_meta($product_id, '_crp_related_product_tags', true);

        $related = $this->get_product_category_ids($related_categories_ids,$related);
		
		$related = $this->get_product_tag_ids($related_tag_ids,$related);

        if ( ! empty( $related ) ) {
            return false;
        } else {
            return $result;
        }
    }
    
    function crp_related_products_query( $query, $product_id ) {
        $modify_default_mode_query = apply_filters( 'wt_crp_modify_default_mode_query', false );
        $working_mode = Custom_Related_Products::get_current_working_mode();
        if( $working_mode == 'default' && !$modify_default_mode_query ) {
            return $query;
        }
        $related = get_post_meta( $product_id, '_crp_related_ids', true );

        $related_categories_ids = get_post_meta($product_id, '_crp_related_product_cats', true);

        $related = $this->get_product_category_ids($related_categories_ids,$related);
		
		$related_tag_ids = get_post_meta($product_id, '_crp_related_product_tags', true);

        $related = $this->get_product_tag_ids($related_tag_ids,$related);

        if ( ! empty( $related ) && is_array( $related ) ) {
            $related = implode( ',', array_map( 'absint', $related ) );
            $query['where'] .= " AND p.ID IN ( {$related} )";
        }
        return $query;
    }

    function get_product_category_ids($related_categories_ids, $related=array()){

        if(!empty($related_categories_ids)){
    
            foreach($related_categories_ids as $related_categories_id){
    
                $all_ids = get_posts( array(
                    'post_type'     => 'product',
                    'numberposts'   => -1,
                    'post_status'   => 'publish',
                    'fields'        => 'ids',
                    'tax_query'     => array(
                       array(
                          'taxonomy' => 'product_cat',
                          'field'    => 'term_id',
                          'terms'    => $related_categories_id,
                          'operator' => 'IN',
                       )
                    ),
                ) );
    
                if(!empty($related)){
                    $related = array_merge($all_ids,$related);
                }else{
                    $related = $all_ids;
                }
            }
        }
        return $related;
    
    }
	
	    function get_product_tag_ids($related_tag_ids, $related=array()){

        if(!empty($related_tag_ids) && is_array($related_tag_ids)){
    
            foreach($related_tag_ids as $related_tag_id){
    
                $all_ids = get_posts( array(
                    'post_type'     => 'product',
                    'numberposts'   => -1,
                    'post_status'   => 'publish',
                    'fields'        => 'ids',
                    'tax_query'     => array(
                       array(
                          'taxonomy' => 'product_tag',
                          'field'    => 'term_id',
                          'terms'    => $related_tag_id,
                          'operator' => 'IN',
                       )
                    ),
                ) );
    
                if(!empty($related)){
                    $related = array_merge($all_ids,$related);
                }else{
                    $related = $all_ids;
                }
            }
        }
        return $related;
    
    }
    
    public function wt_process_slider_script() {       
        $slider_state	 = get_option('custom_related_products_slider','enable');
        $slider_state_temp	 = get_option('custom_related_products_slider_temp');
        $disable_empty = apply_filters('wt_iew_importer_custom_related_products_disable_empty_slides', false);
        $working_mode = Custom_Related_Products::get_current_working_mode();
        if(strstr(wp_get_theme()->get('Name'),'Woodmart')){
            $disable_empty = true;
        }
        if ('enable' == $slider_state){
            $slide_width = get_option('custom_related_products_crp_banner_width') ? get_option('custom_related_products_crp_banner_width'): 100;
            $slide_width = $slide_width . '%';
            $crp_view_port = get_option('custom_related_products_crp_banner_product_width') ;
            $desktop_view = isset($crp_view_port[0]) && !empty($crp_view_port[0]) ?  $crp_view_port[0] : 3;
            $tab_view = isset($crp_view_port[1]) && !empty($crp_view_port[1]) ?  $crp_view_port[1] : 2;
            $mobile_view = isset($crp_view_port[2]) && !empty($crp_view_port[2]) ?  $crp_view_port[2] : 1;
            ?>
                <script>
                    jQuery(document).ready(function($) {
                            $('.owl-carousel').owlCarousel({
                              loop: false,
                              margin: 10,
                              nav: true,
                              navText: [
                                "<i class='fa fa-angle-left wt-left'></i>",
                                "<i class='fa fa-angle-right wt-right'></i>"
                              ],
//                                                          autoplay: true,
                              autoplayHoverPause: true,
                              responsive: {
                                0: {
                                  items: <?php echo $mobile_view; ?>
                                },
                                600: {
                                  items: <?php echo $tab_view; ?>
                                },
                                1000: {
                                  items: <?php echo $desktop_view; ?>
                                }
                              }
                            })
                    jQuery(".carousel-wrap>.owl-carousel>.owl-stage-outer>.owl-stage>.owl-item>div[class*='col-']").removeClass (function (index, css) {
                        return (css.match (/(^|\s)col-\S+/g) || []).join(' ');
                     });
                     
                    <?php if($disable_empty){ ?>
             
                    jQuery('.wt-related-products >.carousel-wrap>.owl-carousel>.owl-stage-outer>.owl-stage> .active').each(function(){
                         if(jQuery(this).find('div').length == 0){

                                jQuery(this).hide();
                         }
                    });
                    
                    jQuery(".owl-carousel").on('change.owl.carousel', function(e) { 
                      var total = e.item.count; 

                      itemsPerPage = e.page.size; 
                      itemGoOut = e.item.index; 
                       hidden = jQuery('.owl-item > :hidden').length;

                      itemRemain = total - (itemsPerPage + itemGoOut + 1);
                       if(hidden != 0){
                         itemRemain = itemRemain - hidden;
                            if(itemRemain === 0){
                                 jQuery('.owl-next').hide();
                            }else{
                                 jQuery('.owl-next').show();
                            }
                      }

                      });
                     <?php } ?>

                  });
                                                                                       
                </script>
                <style>
                    .wt-related-products{
                                    width: <?php echo $slide_width . ' !important'; ?>;
                                }
                                <?php if($slider_state_temp !== 'disable' && $working_mode !== 'default'){ ?>
                                
                                        .wt-related-products div.wt-crp-content-wrapper span.wt_price, .wt_cart_button {
                                            display          : block;
                                            text-align       : center;
                                        }

                                        .wt-related-products div.wt-crp-content-wrapper .wt-crp-product-title {
                                            text-align     : center;
                                            margin: 0px;
                                        }

                                        .wt-related-products .owl-theme .owl-nav [class*=owl-] {
                                            color: #969292 ;
                                            padding: 0px !important;
                                            margin: 20px !important;
                                            height: 40px !important;
                                            width: 40px !important;
                                            border-radius: 50% !important;
                                            z-index: 10000000;
                                        }
/*                                        .owl-theme .owl-nav [class*=owl-] {
                                            background: #ffffff !important;
                                        }*/

                                        /* fix blank or flashing items on carousel */
                                        .wt-related-products .owl-carousel .item {
                                            position: relative;
                                            z-index: 100;
                                            -webkit-backface-visibility: hidden;
                                        }

                                        /* end fix */
                                        .wt-related-products .owl-nav > div {
                                            margin-top: -26px;
                                            position: absolute;
                                            top: 30%;
                                            color: #cdcbcd;
                                        }

                                        .wt-related-products .owl-nav i {
                                            font-size: 32px !important;
                                            margin-top: 2px !important;
                                            line-height: initial !important;
                                        }

                                        .wt-related-products .owl-nav .owl-prev {
                                            left: -11px;
                                        }

                                        .wt-related-products .owl-nav .owl-next {
                                            right: -11px;
                                        }

                                        .wt-related-products .carousel-wrap {
                                            padding: 0 3%;
                                            position: relative;
                                        }
                                        .wt-related-products .carousel-wrap ul {
                                          overflow: hidden;
                                        }

                                        .wt-related-products .wt-crp-content-wrapper .quantity{
                                            display: none;
                                        }

                                        .wt-related-products .wt-crp-content-wrapper .add_to_cart_button {
                                            margin-bottom: 5px !important;
                                        }
                                        .wt-related-products .wt-crp-content-wrapper form.cart {
                                            padding: 0px 0 !important;
                                        }
                                        .wt-related-products .wt-left{
                                            margin-right: 5px;
                                        }
                                        .wt-related-products .wt-right{
                                            margin-left: 5px;
                                        }
                                        .wt-related-products .wt-crp-content-wrapper{
                                            line-height: 28px;
                                            margin-top: 5px;
                                        }

                                        .wt-related-products .owl-theme .owl-nav [class*=owl-]:hover {
                                            background: #1f2021 !important;
                                            text-decoration: none;
                                        }

                                        .wt-related-products ul.products li.product, .wt-related-products ul.products {
                                            margin: initial !important;
                                            width: initial !important;
                                            float: initial !important;
                                            grid-template-columns: initial !important;
                                            max-width:initial !important;
                                            min-width:initial !important;
                                        }
                                        .wt-related-products .woocommerce ul.products, .wt-related-products .woocommerce-page ul.products {
                                            grid-template-columns: initial !important;
                                        }
                                        .wt-crp-wrapper div{
                                            max-width: 100%;

                                        }
                                        .wt-related-products .owl-dots{
                                            display: none !important;
                                        }

                                <?php } ?>
                                
                </style>
                 <?php
        }else{
            ?>
                   <script>
                    jQuery(document).ready(function($) {
                        jQuery(".wt-related-products").removeClass('products');
                    });
                                                                                       
                </script>
            <?php
            
            if(is_cart()){
               ?>
              <script>
                    jQuery(document).ready(function($) {
                        jQuery(".wt-related-products-cart").find('.first').removeClass('first');
                        jQuery(".wt-related-products-cart").find('.last').removeClass('last');
                          jQuery(".wt-related-products-cart>ul[class*='columns']").removeClass (function (index, css) {
                        return (css.match (/(^|\s)columns\S+/g) || []).join(' ');
                     });
                        jQuery(".wt-related-products-cart>ul").addClass('columns-3');
                         var max = 0;
                         var w =0;
                        jQuery('.wt-related-products-cart ul li').each(function() {
                            var h = jQuery(this).height(); 
                            w = jQuery(this).width(); 
                            max = h > max ? h : max;
                            var arr = [];
                            arr.push(jQuery(this).height());
                        });
                       //sleepFor(500);
                       jQuery('.wt-related-products-cart ul li').each(function() {
                            if(max>0){
                                jQuery(this).css("height", max+10);
                                jQuery(this).css("width", w-5);
                            }
                        });
                        
                        function sleepFor(sleepDuration){
                            var now = new Date().getTime();
                            while(new Date().getTime() < now + sleepDuration){ /* Do nothing */ }
                        }

                    });
                                                                                       
                </script>
            <?php
            }
        }
         
        
    }
}