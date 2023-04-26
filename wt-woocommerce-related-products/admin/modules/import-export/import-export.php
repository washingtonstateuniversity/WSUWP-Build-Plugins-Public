<?php

/**
 * Related products - Import Export
 *
 * @link
 * @since 1.4.2
 *
 * @package  Custom_Related_Products
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Custom_Related_Product_Import_Export {

    public function __construct() {
        add_action('admin_init', array( $this, 'init' ));
        add_action('init', array( $this, 'load_dependents' ));
	}

    public function init() {
        $this->load_import_export_vendors();
        $this->remove_existing_filters();
    }

    /**
     * Loading supported vendors for import/export
     * @since 1.4.2
     */
    public function load_import_export_vendors() {
        $this->load_woocommerce_default_import_export();
    }

    /**
     * To remove the previously added filters for import/export through theme or external plugins.
     * @since 1.4.2
     */
    public function remove_existing_filters() {
        remove_filter('woocommerce_product_export_meta_value', 'webtoffee_related_products_export');
        remove_filter( 'woocommerce_product_importer_parsed_data', 'woocommerce_product_importer_parsed_data');
    }

    /**
     * Support for woocommerce's default import/export
     * @since 1.4.2
     */
    public function load_woocommerce_default_import_export() {
        
        add_filter( 'woocommerce_product_export_meta_value', array( $this, 'process_woocommerce_default_export' ), 11, 4 );
        add_filter( 'woocommerce_product_importer_parsed_data', array( $this, 'process_woocommerce_default_import' ), 10, 2 );
        add_filter('wt_batch_product_export_row_data', array( $this, 'process_webtoffee_export'), 10,2);
        add_filter('wt_woocommerce_product_import_process_item_data', array( $this,'process_webtoffee_import'),10,1);
    }

    /**
     * Process data of woocommerce's default export
     * @since 1.4.2
     */
    public function process_woocommerce_default_export($value, $meta, $product, $row) {
        $wt_crp_meta_keys = $this->get_crp_meta_keys();
        if ( in_array($meta->key, $wt_crp_meta_keys) ) {
            if( $meta->key == '_crp_related_product_attr' ) {
                $value = $this->process_related_attr_for_export($value);
            }
            if( $meta->key == '_crp_excluded_cats' || $meta->key == '_crp_related_product_cats' || $meta->key == '_crp_related_product_tags' ) {
                $value = $this->process_term_id_for_export($value);
            }
            return implode( ",", $value );     
        }else{
            return $value;
        }   
    }
    
     /**
     * Process data of webtoffee export
     * @since 1.4.2
     */
    public function process_webtoffee_export($row, $product) {
        $wt_crp_meta_keys = $this->get_crp_meta_keys();
        foreach ($wt_crp_meta_keys as $crp_key => $crp_value) {
            $crp_value = 'meta:'.$crp_value;
            if(array_key_exists($crp_value, $row) && !empty($row[$crp_value])){
                $value = json_decode($row[$crp_value]);

                if( $crp_value == 'meta:_crp_related_product_attr' ) {
                    $value = $this->process_related_attr_for_export($value);
                }
                if( $crp_value == 'meta:_crp_excluded_cats' || $crp_value == 'meta:_crp_related_product_cats' || $crp_value == 'meta:_crp_related_product_tags' ) {
                    $value = $this->process_term_id_for_export($value);
                }

                $row[$crp_value] = implode( ",", $value );  
            }
        }
        return $row;
    }
    
    
         /**
     * Process data of all_export export
     * @since 1.4.2
     */
    public function process_all_export_export($rows, $export_options,$export_id) {
        $wt_crp_meta_keys = $this->get_crp_meta_keys();
        foreach ($wt_crp_meta_keys as $crp_key => $crp_value) {
            foreach ($rows as $r_key => $row) {               
                if(array_key_exists($crp_value, $row) && !empty($row[$crp_value])){
                    $value = unserialize($row[$crp_value]);
                    if( $crp_value == '_crp_related_product_attr' ) {
                        $value = $this->process_related_attr_for_export($value);
                    }
                    if( $crp_value == '_crp_excluded_cats' || $crp_value == '_crp_related_product_cats' || $crp_value == '_crp_related_product_tags' ) {
                        $value = $this->process_term_id_for_export($value);
                    }
                    if(!empty($value)){
                     $rows[$r_key][$crp_value] = implode( ",", $value );  
                    }
                }
          }
        }
        return $rows;
    }

    /**
     * Process data of woocommerce's default import
     * @since 1.4.2
     */
    public function process_woocommerce_default_import($data, $object) {

        $wt_crp_meta_keys = $this->get_crp_meta_keys();  
        if( !empty( $data[ 'meta_data' ] ) ) {
            foreach ( $data[ 'meta_data' ] as $mkey => $mvalue ) {
                

                if ( in_array($mvalue['key'], $wt_crp_meta_keys) && is_string( $mvalue['key'] ) ) {
                    $custom_meta = explode( ",", $mvalue[ 'value' ] );
                    if( $mvalue['key'] == '_crp_related_product_attr' ) {
                        $custom_meta = $this->process_related_attr_for_import($custom_meta);
                    }
                    
                    if( $mvalue['key'] == '_crp_excluded_cats' || $mvalue['key'] == '_crp_related_product_cats' || $mvalue['key'] == '_crp_related_product_tags' ) {
                       $custom_meta = $this->process_term_id_for_import($custom_meta, $mvalue['key']);
                    }

                    if( $mvalue['key'] == '_crp_related_skus' ) {
                        $custom_meta = $this->get_product_id_from_sku($custom_meta);
                        $data[ 'meta_data' ][ $mkey][ 'key' ]		 = '_crp_related_ids';
                    }

                    $en_value = json_encode( $custom_meta, JSON_NUMERIC_CHECK );
                    $custom_meta_data = json_decode( $en_value, true );
                    $data[ 'meta_data' ][ $mkey ][ 'value' ] = $custom_meta_data;
                }
            }
        }

        return $data;
    }

    /**
     * Process data of webtoffee import
     * @since 1.4.2
     */
    public function process_webtoffee_import($meta) {

        $wt_crp_meta_keys = $this->get_crp_meta_keys();  
        foreach ($meta['meta_data'] as $key => $meta_data) {
            if (in_array($meta_data['key'],$wt_crp_meta_keys)){
                    $custom_meta = explode( ",", $meta_data['value'] );
                    if( $meta_data['key'] == '_crp_related_product_attr' ) {
                        $custom_meta = $this->process_related_attr_for_import($custom_meta);
                    }
                    if( $meta_data['key'] == '_crp_excluded_cats' || $meta_data['key'] == '_crp_related_product_cats' || $meta_data['key'] == '_crp_related_product_tags' ) {
                     $custom_meta = $this->process_term_id_for_import($custom_meta, $meta_data['key']);
                    }
                    if( $meta_data['key'] == '_crp_related_skus' ) {
                        $custom_meta = $this->get_product_id_from_sku($custom_meta);
                        $meta[ 'meta_data' ][$key]['key']		 = '_crp_related_ids';
                    }
                    $meta[ 'meta_data' ][ $key ][ 'value' ] = $custom_meta;

            } 
        }
         return $meta;
    }
    
        /**
     * Process data of all_import import
     * @since 1.4.2
     */
    
     public function process_all_import_import($pid, $meta_key, $meta_value) {

        $wt_crp_meta_keys = $this->get_crp_meta_keys();  
            if (in_array($meta_key ,$wt_crp_meta_keys)){                
                    $custom_meta = explode( ",", $meta_value );
                    if( $meta_key == '_crp_related_product_attr' ) {
                        $custom_meta = $this->process_related_attr_for_import($custom_meta);
                    }
                    
                     if( $meta_key == '_crp_excluded_cats' || $meta_key == '_crp_related_product_cats' || $meta_key == '_crp_related_product_tags' ) {
                       $custom_meta = $this->process_term_id_for_import($custom_meta, $meta_key);
                    }

                    if( $meta_key == '_crp_related_skus' ) {
                        $custom_meta = $this->get_product_id_from_sku($custom_meta);
                        $meta_key		 = '_crp_related_ids';
                    }
                    update_post_meta( $pid, $meta_key, $custom_meta );
            } 

    }
    /**
     * Get meta keys of the plugin
     * @since 1.4.2
     * @return array
     */
    public function get_crp_meta_keys() {
        return array(
            '_crp_related_ids',
            '_crp_related_product_tags',
            '_crp_related_product_cats',
            '_crp_related_product_attr',
            '_crp_excluded_cats',
            '_crp_related_skus'
        );
    }

    /**
     * Process related attributes values for export
     * @since 1.4.2
     * @return array
     */
    public function process_related_attr_for_export( $attr_data ) {

        $processed_attr = array();
        if( !empty($attr_data) ) {
            foreach ( $attr_data as $slug => $term_id_list ) {
                foreach ($term_id_list as $term_id) {
                    $term = get_term($term_id);
                    $term_id = $term->slug;
                    $processed_attr[] = "$slug:$term_id";
                }  
            }
        }

        return $processed_attr;
    }
    
        /**
     * Process related term values for export
     * @since 1.4.2
     * @return array
     */
    public function process_term_id_for_export( $term_data ) {

        $processed_term = array();
        if( !empty($term_data) ) {
                foreach ($term_data as $term_id) {
                    $term = get_term($term_id);
                    $term_id = $term->slug;
                    $processed_term[] = $term_id;
                }  
        }

        return $processed_term;
    }

    /**
     * Process related attributes values for import
     * @since 1.4.2
     * @return array
     */
    public static function process_related_attr_for_import( $attr_data ) {

        $processed_attr = array();
        if( !empty($attr_data) ) {
            foreach ($attr_data as $slug_termid) {
                $exploded = explode(':', $slug_termid);
                if( !empty($exploded[0]) && !empty($exploded[1]) ) {
                    $tax_type = 'pa_'.$exploded[0];
                    $term_data = get_term_by( 'slug', $exploded[1], $tax_type);
                    $processed_attr[$exploded[0]][] = $term_data->term_id;
                }
            }
        }

        return $processed_attr;
    }
    
    
       /**
     * Process related attributes values for import
     * @since 1.4.2
     * @return array
     */
    public static function process_term_id_for_import( $term_data,$type ) {

        $processed_term = array();
        if( !empty($term_data) ) {
            foreach ($term_data as $slug_termid) {
                if( !empty($slug_termid) ) {
                    if($type == '_crp_excluded_cats' || $type == '_crp_related_product_cats'){
                        $tax_type = 'product_cat';
                    }
                    
                     if($type == '_crp_related_product_tags'){
                        $tax_type = 'product_tag';
                    }
                    $term_data = get_term_by( 'slug', $slug_termid, $tax_type);
                    $processed_term[] = $term_data->term_id;
                }
            }
        }

        return $processed_term;
    }

    /**
     * Get product id from sku
     * @since 1.4.2
     * @return array
     */
    public function get_product_id_from_sku( $product_skus ) {

        $product_ids = array();
        foreach ($product_skus as $sku) {
            $product_ids[] = wc_get_product_id_by_sku( trim( $sku ) );
        }

        return $product_ids;
    }

    /**
     * Load dependent functions for import export
     * @since 1.4.2
     * @return void
     */
    public function load_dependents() {
         add_action('pmxi_update_post_meta', array( $this,'process_all_import_import'),1,3);
         add_filter('wp_all_export_csv_rows', array( $this, 'process_all_export_export'), 10,3);
        //require_once plugin_dir_path( __FILE__ ) . 'includes/wt-functions.php';
    }






}
new Custom_Related_Product_Import_Export();
