<?php
/**
 * Template of Best Seller
 *
 * @version 1.0.0
 */
$bestseller_product = wc_get_product($id);
$product = $bestseller_product;

if ($bestseller_product) :
    $defaults = array(
    'quantity' => 1,
    'class' => implode(
            ' ',
            array_filter(
                    array(
                        'button',
                        '', //wc_wp_theme_get_element_class_name( 'button' ), // escaped in the template.
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                    )
            )
    ),
    'attributes' => array(
        'data-product_id' => $product->get_id(),
        'data-product_sku' => $product->get_sku(),
        'aria-label' => $product->add_to_cart_description(),
        'rel' => 'nofollow',
    ),
);

$args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args($args, $defaults), $product);

if (isset($args['attributes']['aria-label'])) {
    $args['attributes']['aria-label'] = wp_strip_all_tags($args['attributes']['aria-label']);
}
    $price = $bestseller_product->get_price_html();
    $del = array('<span class="amount">', '</span>', '<del>', '<ins>');
    $price = str_replace($del, '', $price);
    $price = str_replace('</del>', ' -', $price);
    $price = str_replace('</ins>', '', $price);
    $get_price_html = '<span class="amount wt-amount" style="text-decoration: none !important;">' . $price . '</span>';
    ?>
    <div <?php wc_product_class( '', $product ); ?> class="wt-crp-wrapper">
        <a href="<?php echo get_permalink($id); ?>" style="color: unset">
            <div class="wt-crp-container">
                <!--<span class="wt-crp-position"><?php echo $loop ?></span>-->
                <?php
                $thumb_id = get_post_thumbnail_id($id);
                $thumb_id = !empty($thumb_id) ? $thumb_id : get_option('woocommerce_placeholder_image');
                if ($thumb_id) :
                    ?>

                    <div class="wt-crp-thumb-wrapper">
                        <?php
                        $image_title = esc_attr(get_the_title($thumb_id));
                        $image_caption = get_post($thumb_id)->post_excerpt;
                        $image_link = wp_get_attachment_url($thumb_id);
                        $image = get_the_post_thumbnail($id, 'shop_catalog');
                        $resized_link = wp_get_attachment_image_src($thumb_id, 'shop_catalog');

                        $image_link = !empty($resized_link) ? $resized_link[0] : $image_link;

                        echo "<img src='{$image_link}' title='{$image_title}' alt='{$image_title}' />";
                        ?>
                    </div>
                <?php endif; ?>
                <div class="wt-crp-content-wrapper">
                    <p class=" woocommerce-loop-product__title wt-crp-product-title"><?php echo $bestseller_product->get_title(); ?></p>
                    <span class="wt_price"> <?php echo $get_price_html; ?></span>
                    <div class="wt_cart_button">

                        <?php
                        echo apply_filters(
                                'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                                sprintf(
                                        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                        esc_url($product->add_to_cart_url()),
                                        esc_attr(isset($args['quantity']) ? $args['quantity'] : 1 ),
                                        esc_attr(isset($args['class']) ? $args['class'] : 'button' ),
                                        isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
                                        esc_html($product->add_to_cart_text())
                                ),
                                $product,
                        );
                        ?>
                    </div>

                    </td>
                    </tr>
                    </table>       
                </div>
            </div>
        </a>
    </div>
<?php endif; ?>

