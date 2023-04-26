<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     4.9.0
 */
if (!defined('ABSPATH')) {
	exit;
}


if ( ! function_exists( 'crp_get_all_product_ids_from_cat_ids' ) ) {

	/**
	* Get all product ids from the given category ids
	* @since 1.3.7
	* @return array  
	*/
	function crp_get_all_product_ids_from_cat_ids( array $cat_ids ) {
            
            $all_ids = $total= array();
            
            if($cat_ids){
                $cat_ids = array_reverse($cat_ids);
                foreach ($cat_ids as $ckey => $cat_value) {
		$all_ids = get_posts(
			array(
				'post_type'		 => 'product',
				'numberposts'	 => -1,
				'post_status'	 => 'publish',
				'fields'		 => 'ids',
				'tax_query'		 => array(
					array(
						'taxonomy'	 => 'product_cat',
						'field'		 => 'term_id',
                                                    'terms'		 => array($cat_value),
						'operator'	 => 'IN',
					)
				),
			)
		);
                    $total = array_merge($total,$all_ids);
                    unset($all_ids);
                }
            }
            $all_ids = array_unique($total);

		return $all_ids;
	}
}

if ( ! function_exists( 'crp_get_all_product_ids_from_tag_ids' ) ) {

	/**
	* Get all product ids from the given tag ids
	* @since 1.3.7
	* @return array  
	*/
	function crp_get_all_product_ids_from_tag_ids( array $tag_ids ) {
		$all_ids = get_posts(
			array(
				'post_type'		 => 'product',
				'numberposts'	 => -1,
				'post_status'	 => 'publish',
				'fields'		 => 'ids',
				'tax_query'		 => array(
					array(
						'taxonomy'	 => 'product_tag',
						'field'		 => 'term_id',
						'terms'		 => $tag_ids,
						'operator'	 => 'IN',
					)
				),
			)
		);

		return $all_ids;
	}
}

if ( ! function_exists( 'crp_get_all_product_ids_from_attr_ids' ) ) {

	/**
	* Get all product ids from the given attributes
	* @since 1.4.0
	* @return array  
	*/
	function crp_get_all_product_ids_from_attr_ids( array $attr_data ) {
	
		$tax_query = array( 'relation'=> 'OR' );
		foreach ($attr_data as $attr_name => $attr_term_ids) {
			$tax_query[] = array(
				'taxonomy'        => "pa_$attr_name",
				'terms'           =>  $attr_term_ids,
				'operator'        => 'IN',
			);
		}
		$all_ids = new WP_Query(
			array(
				'post_type'		 => array('product', 'product_variation'),
				'posts_per_page'	 => -1,
				'post_status'	 => 'publish',
				'fields'		 => 'ids',
				'tax_query' => $tax_query
			)
		);

		if( $all_ids->have_posts() ) {
			return $all_ids->posts;
		}    

		return array();
	}
}

$global_related_by = (array) apply_filters( 'wt_crp_global_related_by', get_option('custom_related_products_crp_related_by', array('category')) );

if ( @$related_products || !empty($global_related_by) ) :

?>

	<section class="related products wt-related-products wt-related-products-cart">

                <?php
		global $post;

		// when rendering through shortcode
		if (isset($shortcode_post)) {

			$post = $shortcode_post;
		}
		
		$working_mode = class_exists('Custom_Related_Products') ? Custom_Related_Products::get_current_working_mode() : '';

		if ( $working_mode == 'custom' ) {

                $crp_post_id_arr= array();$cart_post_id_arr = array();
                foreach( WC()->cart->get_cart() as $cart_item ){
                        $post = get_post($cart_item['product_id']);
                        $cart_post_id_arr[] = $post->ID;

			$current_post_id = $post->ID;
			global $sitepress;
			$use_primary_id_wpml = apply_filters( 'wt_crp_use_primary_id_wpml', get_option('custom_related_products_use_primary_id_wpml') );
			if( $use_primary_id_wpml == 'enable' && isset( $sitepress ) && defined('ICL_LANGUAGE_CODE') ) {
				$default_lang = $sitepress->get_default_language();
				if( $default_lang != ICL_LANGUAGE_CODE && function_exists('icl_object_id') ) {
					$default_id = icl_object_id ($post->ID, "product", false, $default_lang);
					$default_post = get_post( $default_id );
					$post = $default_post;
				}
			}

			$reselected = get_post_meta($post->ID, 'selected_ids', true);

			if (!empty($reselected)) {
				add_post_meta($post->ID, '_crp_related_ids', $reselected);
			}

			$related = apply_filters( 'wt_crp_related_product_ids', array_filter(array_map('absint', (array) get_post_meta($post->ID, '_crp_related_ids', true))));

			


			//gets selected related categories
			$related_categories_ids = apply_filters( 'wt_crp_related_category_ids',array_filter(array_map('absint', (array) get_post_meta($post->ID, '_crp_related_product_cats', true))));
				
			//gets selected related tags
			$related_tags_ids = apply_filters( 'wt_crp_related_tag_ids', get_post_meta($post->ID, '_crp_related_product_tags', true) );
			
			//gets selected related attributes
			$related_attr_ids = apply_filters( 'wt_crp_related_attribute_ids', get_post_meta($post->ID, '_crp_related_product_attr', true) );
		
			if(!empty($related) || !empty($related_categories_ids) || !empty($related_tags_ids) || !empty($related_attr_ids)) {

				if (!empty($related_categories_ids)) {
					$all_ids = crp_get_all_product_ids_from_cat_ids( $related_categories_ids );

					if (!empty($related)) {
						$related = array_merge($all_ids, $related);
					} else {
						$related = $all_ids;
					}
				}
	
				if (!empty($related_tags_ids) && is_array($related_tags_ids)) {
					$all_ids = crp_get_all_product_ids_from_tag_ids( $related_tags_ids );

					if (!empty($related)) {
						$related = array_merge($all_ids, $related);
					} else {
						$related = $all_ids;
					}
				}

				if (!empty($related_attr_ids)) {

					$all_ids = crp_get_all_product_ids_from_attr_ids( $related_attr_ids );

					if (!empty($related)) {
						$related = array_merge($all_ids, $related);
					} else {
						$related = $all_ids;
					}
				}
			} else if(!empty($global_related_by)) {
				
				if( in_array( 'category', $global_related_by ) ) {
					$product_cat_ids = array();
					$prod_terms = wp_get_post_terms($post->ID, 'product_cat', array("orderby" => "parent"));//get_the_terms( $post->ID, 'product_cat' );
					if ( ! empty( $prod_terms ) && ! is_wp_error( $prod_terms ) ) {
						$subcategory_only = apply_filters('wt_crp_subcategory_only', false);
						$category_count = count($prod_terms);
                        $term_ids = array_column($prod_terms, 'term_id');

						foreach ($prod_terms as $prod_term) {
							if( $subcategory_only && $category_count > 1 ) {
                                $has_term_id = false;
                                $children = function_exists('get_categories') ? get_categories( array ('taxonomy' => 'product_cat', 'child_of' => $prod_term->term_id )) : array();
                                foreach ($children as $term) {
                                    if( in_array($term->term_id, $term_ids) ) {
                                        $has_term_id = true;
                                        break;
                                    }
                                }
                                
                                if ( count($children) == 0 || !$has_term_id ) {
									// if no children, then it may be the deepest sub category.
									$product_cat_ids[] = $prod_term->term_id;
								}
							}else {
								// gets product cat id
								$product_cat_ids[] = $prod_term->term_id;
							}	
						}
						if(!empty($product_cat_ids)) {
							$related = crp_get_all_product_ids_from_cat_ids( $product_cat_ids );
						}
					}
					
				}

				if( in_array( 'tag', $global_related_by ) ) {
					$product_tag_ids = $related_ids = array();
					$prod_terms = get_the_terms( $post->ID, 'product_tag' );
					if ( ! empty( $prod_terms ) && ! is_wp_error( $prod_terms ) ) {
						foreach ($prod_terms as $prod_term) {
							// gets product tag id
							$product_tag_ids[] = $prod_term->term_id;
						}
						if(!empty($product_tag_ids)) {
							$related_ids = crp_get_all_product_ids_from_tag_ids( $product_tag_ids );
							$related = ( !empty($related) && is_array($related) ) ? array_merge($related, $related_ids) : $related_ids;
						}
					}
				}
			}
                        if(!empty($related)){
                              $crp_post_id_arr =    array_merge($crp_post_id_arr,$related);                    
                        }
                }
                $crp_post_id_arr = (array_unique($crp_post_id_arr));
                $post_ids = array_diff($crp_post_id_arr,$cart_post_id_arr);
                $related = array_values($post_ids);

			//gets excluded categories
			$excluded_categories_ids = apply_filters( 'wt_crp_excluded_category_ids',get_post_meta($post->ID, '_crp_excluded_cats', true) );

			if (!empty($excluded_categories_ids) && !empty($related)) {
				$all_ids = crp_get_all_product_ids_from_cat_ids( $excluded_categories_ids );

				if (!empty($all_ids)) {
					$related = array_diff($related, $all_ids);
				}
			}

			delete_post_meta($post->ID, 'selected_ids');
			$related	= is_array($related) ? array_diff($related, array($post->ID, $current_post_id)) : array();
			if (!empty($related)) {

                                $number_of_products	 = get_option('custom_related_products_crp_number', 3);
                                $slider_state	 = get_option('custom_related_products_slider','enable');
                                if('enable' != $slider_state){
                                    $number_of_products = class_exists('Custom_Related_Products') ? Custom_Related_Products::wt_get_device_type() : '3';
                                }
				$number_of_products	 = apply_filters('wt_related_products_number', $number_of_products);
                                $related = array_slice($related, 0, $number_of_products);

				$related_products	 = array();
				$copy				 = array();
				
				$related_products	 = $related;
				while (count($related_products)) {
					// takes a rand array elements by its key
					$element			 = array_rand($related_products);
					// assign the array and its value to an another array
					$copy[$element]	 = $related_products[$element];
					//delete the element from source array
					unset($related_products[$element]);
				}

				$orderby 			 = get_option('custom_related_products_crp_order_by', 'popularity');
				$orderby			 = apply_filters('wt_related_products_orderby', $orderby);
				$order 				 = get_option('custom_related_products_crp_order', 'DESC');	
				$order				 = apply_filters('wt_related_products_order', $order);

				$i = 1;

				// Setup your custom query
				$args = array(
					'post_type' => 'product', 
					'posts_per_page' => $number_of_products, 
					'orderby' => $orderby, 
					'order' => $order, 
					'post__in' => $copy
				);
				$custom_orderby = class_exists('Custom_Related_Products') ? Custom_Related_Products::get_custom_order_by_values() : array();
				if( array_key_exists( $orderby, $custom_orderby ) ) {
					$args['orderby'] =  $custom_orderby[$orderby]['orderby'];
					$args['meta_key'] = $custom_orderby[$orderby]['meta_key'];
				}
				
				// To exclude out of stock products
				$exclude_os	 = get_option('custom_related_products_exclude_os');
				if (!empty($exclude_os)) {
					$args['meta_query'] = array(
						array(
							'key'       => '_stock_status',
							'value'     => 'outofstock',
							'compare'   => 'NOT IN'
						)
					);
				}
                                $copy = apply_filters("woocommerce_crp_set_product_visibility", $copy); 
                                $min_slides = class_exists('Custom_Related_Products') ? Custom_Related_Products::wt_get_device_type() : '3';
                                $slider_status = 'enable';

                                if(count($copy) <= $min_slides){
                                    update_option('custom_related_products_slider_temp','disable');
                                    $slider_status = 'disable';
                                }else{
                                    update_option('custom_related_products_slider_temp','enabled');
                                }
                                $bxslider		 = 'slider';
                                $slider_state	 = get_option('custom_related_products_slider','enable');
                                $crp_title		 = get_option('custom_related_products_crp_title', esc_html__('Related Products', 'wt-woocommerce-related-products'));
                                $crp_heading 	 = apply_filters('wt_related_products_heading', "<h2 class='wt-crp-heading'>" . esc_html( $crp_title ) . " </h2>", $crp_title);

                                $few_slider		 = '';
                                $slider_type = get_option('custom_related_products_slider_type') ? get_option('custom_related_products_slider_type'):'swiper';
                                 if(in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
                                    $slider_type = 'bx';
                                }
                                
                                if(strstr(wp_get_theme()->get('Name'),'Woodmart') || strstr(wp_get_theme()->get('Name'),'Flatsome')){
                                    $slider_type = 'bx';
                                }
                                
                                if(strstr(wp_get_theme()->get('Name'),'Divi') || strstr(wp_get_theme()->get('Name'),'Avada') || strstr(wp_get_theme()->get('Name'),'BeOnePage')){
                                    $slider_type = 'swiper';
                                }
                                if($slider_status == 'disable'){
                                    $slider_state = '';
                                    $bxslider = '';
                                }
                                 if ('enable' == $slider_state && $slider_type== 'bx') {
                                        $bxslider = 'bxslider';
                                 }
                                 if (('enable' != $slider_state && $slider_type== 'swiper') || ('enable' != $slider_state && $slider_type== 'bx')) {
                                        $bxslider = '';
                                 }
                                if(strstr(wp_get_theme()->get('Name'),'Twenty Twenty-One')){
                                    $slider_type = 'swiper';
                                }
                                
				$loop	 = new WP_Query($args);
                               
                                
                                @ini_set('display_errors','Off');
                                @ini_set('error_reporting', E_ALL );
                                @define('WP_DEBUG', false);
                                @define('WP_DEBUG_DISPLAY', false);
                                if($loop->have_posts()) {
					echo $crp_heading;

                                        if($bxslider && apply_filters( 'wt_crp_custom_related_product_template', false )){
                                            ?>
                                                    <div class="carousel-wrap">
                                                        <div class="owl-carousel owl-theme products">
                                                <?php 
                                                 $rel_products = $loop->posts;
                                              foreach ($rel_products as $products) {
                                                        $bs_id = absint($products->ID);
                                                        $bs_qty = 3;
                                                        $args = array(
                                                            'id' => $bs_id,
                                                            'qty' => $bs_qty,
                                                            'loop' => '',
                                                        );
                                                      
                                                        wc_get_template('/wt-custom-related.php', $args, CRP_PLUGIN_TEMPLATE_PATH, CRP_PLUGIN_TEMPLATE_PATH);
                                                    }
                                                          ?>
                                                </div>
                                        </div>
                                                <?php 
                                            
                                        }else{
                                             if ($bxslider) {
                                                ?>
                                                <div class="carousel-wrap">
                                                <ul class="owl-carousel owl-theme products">
                                                <?php 
				
                                    
                                            } else {
                                                 woocommerce_product_loop_start();
                                                  ?>
                                            <?php 
                                                
                                            }

                                            while ($loop->have_posts()) : $loop->the_post();
						wc_get_template_part('content', 'product'); 

                                            endwhile; // end of the loop. 
                                            woocommerce_product_loop_end();
                                            if ($bxslider) {
                                                    ?></ul>
                                                    </div>
                                            <?php 
                                            } 
                                        }
                                     }                               
			} else {
				?>
				<section class="related_products" style="display: none;"></section>
			<?php
			}
		} else if( $working_mode == 'default' && !empty( $related_products )) {
                      $crp_title         = get_option('custom_related_products_crp_title', esc_html__('Related Products', 'wt-woocommerce-related-products'));
                      $crp_heading 	 = apply_filters('wt_related_products_heading', "<h2 class='wt-crp-heading'>" . esc_html( $crp_title ) . " </h2>", $crp_title);
                      $bxslider = false;
			?>
			<?php echo $crp_heading; ?>
			<?php
			$crelated = get_post_meta($post->ID, '_crp_related_ids', true);

			if (!empty($crelated))
				update_post_meta($post->ID, 'selected_ids', $crelated);
			?>
			<?php if ($bxslider) { ?>
				<ul class="<?php echo esc_attr( $bxslider ); ?> crp-slider products columns-<?php echo esc_attr(wc_get_loop_prop('columns')); ?>">
				<?php } else {

				woocommerce_product_loop_start();
			} ?>
				<?php
				foreach ($related_products as $related_product) :
					if (!is_object($related_product)) {
						$related_product = wc_get_product($related_product);
					}

					$post_object		 = get_post($related_product->get_id());
					setup_postdata($GLOBALS['post']	 = &$post_object);
					wc_get_template_part('content', 'product');
				?>
			<?php
				endforeach;
				woocommerce_product_loop_end();
		}
		?>

	</section>

<?php
endif;
wp_reset_postdata();


