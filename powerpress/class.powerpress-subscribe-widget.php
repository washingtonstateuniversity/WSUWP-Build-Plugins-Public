<?php
/**
 * @package PowerPressSubscribe_Widget
 */
class PowerPressSubscribe_Widget extends WP_Widget {

	function __construct() {
		load_plugin_textdomain( 'powerpress' );
		
		parent::__construct(
			'powerpress_subscribe',
			__( 'Subscribe to Podcast' , 'powerpress'),
			array( 'description' => __( 'Display subscribe to podcast links.' , 'powerpress') )
		);
		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_head', array( $this, 'css' ) );
		}
		
		add_action('admin_enqueue_scripts', array( $this, 'load_scripts' ));
	}
	
	function load_scripts($hook) {
		
		// taken from: https://pippinsplugins.com/loading-scripts-correctly-in-the-wordpress-admin/
		if( $hook == 'widgets.php' )
		{
			//echo "<!-- $hook -->";
			wp_enqueue_script( 'powerpress-subscribe-widget', plugins_url( 'js/powerpress-subscribe-widget.js' , __FILE__ ) );
		}
	}

	function css() {
?>

<style type="text/css" xmlns="http://www.w3.org/1999/html">

/*
PowerPress subscribe sidebar widget
*/
<?php if( !defined('POWERPRESS_SUBSCRIBE_SIDEBAR_NO_H_STYLING') ) { ?>
.widget-area .widget_powerpress_subscribe h2,
.widget-area .widget_powerpress_subscribe h3,
.widget-area .widget_powerpress_subscribe h4,
.widget_powerpress_subscribe h2,
.widget_powerpress_subscribe h3,
.widget_powerpress_subscribe h4 {
	margin-bottom: 0;
	padding-bottom: 0;
}
<?php } ?>
</style>
<?php
	}

	function form( $instance ) {
        $General = powerpress_get_settings('powerpress_general');
		if ( empty($instance['title']) ) {
			$instance['title'] = __( 'Subscribe to Podcast' , 'powerpress');
		}
		if(empty($instance['style'])) {
		    if (empty($General['timestamp']) || $General['timestamp'] > 1570366800) {
                $instance['style'] = 'modern';
            } else {
                $instance['style'] = 'classic';
            }
        }
        if(empty($instance['modern_style'])) {
            $instance['modern_style'] = 'squared';
        }
        if(empty($instance['modern_direction'])) {
            $instance['modern_direction'] = 'vertical';
        }
		if ( empty($instance['subscribe_type']) ) {
			$instance['subscribe_type'] = '';
		}
		if ( empty($instance['subscribe_post_type']) ) {
			$instance['subscribe_post_type'] = '';
		}
		if ( empty($instance['subscribe_feed_slug']) ) {
			$instance['subscribe_feed_slug'] = '';
		}
		if ( empty($instance['subscribe_category_id']) ) {
			$instance['subscribe_category_id'] = '';
		}

		$GeneralSettings = get_option('powerpress_general');
?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:' , 'powerpress'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
        <p>
            <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php esc_html_e( 'Style:' , 'powerpress'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' );  ?> ">
                <option value="modern" <?php echo selected($instance['style'], 'modern')?> >Modern</option>
                <option value="classic" <?php echo selected($instance['style'], 'classic') ?> >Classic</option>
            </select>
        </p>
        <p class="pp-sub-widget-p-modern-styles">
            <input type="radio" id="<?php echo $this->get_field_id('modern_style_square') ?>"
                   name="<?php echo $this->get_field_name('modern_style') ?>" value="squared" <?php echo $instance['modern_style'] === 'squared' ? 'checked' : '' ?> />
            <label for="<?php echo $this->get_field_id('modern_style_square') ?>"> <?php esc_html_e('Squared', 'powerpress'); ?></label>
            <input type="radio" id="<?php echo $this->get_field_id('modern_style_rounded') ?>"
                   name="<?php echo $this->get_field_name('modern_style') ?>" value="rounded" <?php echo $instance['modern_style'] === 'rounded' ? 'checked' : '' ?> />
            <label for="<?php echo $this->get_field_id('modern_style_rounded') ?>"> <?php esc_html_e('Rounded', 'powerpress'); ?></label>
        </p>
        <p class="pp-sub-widget-p-modern-styles">
            <input type="radio" id="<?php echo $this->get_field_id('modern_direction_vertical') ?>"
                   name="<?php echo $this->get_field_name('modern_direction') ?>" value="vertical" <?php echo $instance['modern_direction'] === 'vertical' ? 'checked' : '' ?> />
            <label for="<?php echo $this->get_field_id('modern_direction_vertical') ?>"> <?php esc_html_e('Vertical', 'powerpress'); ?></label>
            <input type="radio" id="<?php echo $this->get_field_id('modern_direction_horizontal') ?>"
                   name="<?php echo $this->get_field_name('modern_direction') ?>" value="horizontal" <?php echo $instance['modern_direction'] === 'horizontal' ? 'checked' : '' ?> />
            <label for="<?php echo $this->get_field_id('modern_direction_horizontal') ?>"> <?php esc_html_e('Horizontal', 'powerpress'); ?></label>
        </p>
		<p class="pp-sub-widget-p-subscribe_type">
		<label for="<?php echo $this->get_field_id('subscribe_type'); ?>"><?php _e( 'Select Podcast Type:', 'powerpress' ); ?></label>
		<select class="widefat powerpress-subscribe-type" onchange="javascript: powerpress_subscribe_widget_change(this)" id="<?php echo $this->get_field_id('subscribe_type'); ?>" name="<?php echo $this->get_field_name('subscribe_type'); ?>">
		<?php
		$types = array(''=>__('Default Podcast','powerpress'), 'channel'=>__('Podcast Channel','powerpress')); //, 'ttid'=>__('Taxonomy Podcasting','powerpress'));
		
		if( !empty($GeneralSettings['cat_casting']) || $instance['subscribe_type'] == 'category' ) // If category podcasting enabled
			$types['category'] = __('Category Podcasting','powerpress');
		
		if( !empty($GeneralSettings['posttype_podcasting']) || $instance['subscribe_type'] == 'post_type'  ) // If post type podcasting enabled
			$types['post_type'] = __('Post Type Podcasting','powerpress');
		
		foreach( $types as $type => $label )	{
			echo '<option value="' . $type . '"'
				. selected( $instance['subscribe_type'], $type, false )
				. '>' . $label . "</option>\n";
		}
		?>
		</select>
		</p>
<?php
		// If Post type podcasting enabled...
		if( !empty($GeneralSettings['posttype_podcasting']) || $instance['subscribe_type'] == 'post_type' )
		{
?>
		<p id="<?php echo $this->get_field_id('subscribe_post_type_section'); ?>" class="pp-sub-widget-p-post_type"<?php if( $instance['subscribe_type'] != 'post_type' ) echo " style=\"display: none;\""; ?>>
		<label for="<?php echo $this->get_field_id('subscribe_post_type'); ?>"><?php _e( 'Select Post Type:', 'powerpress' ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('subscribe_post_type'); ?>" name="<?php echo $this->get_field_name('subscribe_post_type'); ?>">
		<option value=""><?php echo __('Select Post Type', 'powerpress'); ?></option>
<?php
		$post_types = powerpress_admin_get_post_types(false);
		foreach( $post_types as $index => $label ) {
			echo '<option value="' . $label . '"'
				. selected( $instance['subscribe_post_type'], $label, false )
				. '>' . $label . "</option>\n";
		}
?>
		</select>
		</p>
<?php } ?>
		
		<p id="<?php echo $this->get_field_id('subscribe_feed_slug_section'); ?>" class="pp-sub-widget-p-channel"<?php if( $instance['subscribe_type'] != 'post_type' && $instance['subscribe_type'] != 'channel' ) echo " style=\"display: none;\""; ?>>
		<label for="<?php echo $this->get_field_id( 'subscribe_feed_slug' ); ?>"><?php esc_html_e( 'Feed Slug:' , 'powerpress'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'subscribe_feed_slug' ); ?>" name="<?php echo $this->get_field_name( 'subscribe_feed_slug' ); ?>" type="text" value="<?php echo esc_attr( $instance['subscribe_feed_slug'] ); ?>" />
		</p>
<?php // If category podcasting...
		if( !empty($GeneralSettings['cat_casting']) || $instance['subscribe_type'] == 'category' ) { ?>
		<p id="<?php echo $this->get_field_id('subscribe_category_id_section'); ?>" class="pp-sub-widget-p-category"<?php if( $instance['subscribe_type'] != 'category' ) echo " style=\"display: none;\""; ?>>
		<label for="<?php echo $this->get_field_id( 'subscribe_category_id' ); ?>"><?php esc_html_e( 'Category ID:' , 'powerpress'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'subscribe_category_id' ); ?>" name="<?php echo $this->get_field_name( 'subscribe_category_id' ); ?>" type="text" value="<?php echo esc_attr( $instance['subscribe_category_id'] ); ?>" />
		</p>
		<?php } ?>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['style'] = strip_tags( $new_instance['style']);
		$instance['modern_style'] = strip_tags( $new_instance['modern_style']);
        $instance['modern_direction'] = strip_tags( $new_instance['modern_direction']);
        $instance['subscribe_type'] = strip_tags( $new_instance['subscribe_type'] ); // general, channel, category, post_type, ttid
		$instance['subscribe_post_type'] = strip_tags( $new_instance['subscribe_post_type'] );; // eg sermons
		$instance['subscribe_feed_slug'] = strip_tags( $new_instance['subscribe_feed_slug'] );; // e.g. podcast
		$instance['subscribe_category_id'] = strip_tags( $new_instance['subscribe_category_id'] );; // e.g. 456
		//$instance['subscribe_term_taxonomy_id'] = strip_tags( $new_instance['subscribe_term_taxonomy_id'] );; // e.g. 345
		return $instance;
	}

	function widget( $args, $instance ) {

		$ExtraData = array('subscribe_type'=>'general', 'feed'=>'', 'taxonomy_term_id'=>'', 'cat_id'=>'', 'post_type'=>'');
		if( !empty($instance['subscribe_type']) )
			$ExtraData['subscribe_type'] = $instance['subscribe_type'];
		else
			$ExtraData['subscribe_type'] =  '';
		switch( $ExtraData['subscribe_type'] )
		{
			case 'post_type': {
				
				if( empty($instance['subscribe_post_type']) || empty($instance['subscribe_feed_slug']) )
					return;
				$ExtraData['post_type'] = $instance['subscribe_post_type'];
				$ExtraData['feed'] = $instance['subscribe_feed_slug'];
			}; 
			case 'channel': {
				if( empty($instance['subscribe_feed_slug']) )
					return;
				$ExtraData['feed'] = $instance['subscribe_feed_slug'];
			}; break;
			case 'ttid': {
				if( empty($instance['subscribe_term_taxonomy_id']) || !is_numeric($instance['subscribe_term_taxonomy_id']) )
					return;
				$ExtraData['taxonomy_term_id'] = $instance['subscribe_term_taxonomy_id'];
			}; break;
			case 'category': {
			 
				if( empty($instance['subscribe_category_id']) )
					return;
				
				if( is_numeric($instance['subscribe_category_id']) )
				{
					$ExtraData['cat_id'] = $instance['subscribe_category_id'];
				}
				else
				{
					$catObj = get_category_by_slug($instance['subscribe_category_id']);
					if( empty($catObj->term_id) )
						return;
					$ExtraData['cat_id'] = $catObj->term_id;
				}
			}; break;
			default: {
				// Doesn't matter, we're using the default podcast channel 

			};
		}
		$Settings = powerpresssubscribe_get_settings( $ExtraData, false );
        $Settings['modern_style'] = '-sq'; // Default to squared corners
		$Settings['style'] = '';
		if( isset($instance['style']) )
			$Settings['style'] = $instance['style'];
        if (defined('WP_DEBUG')) {
            wp_enqueue_style('powerpress_subscribe_widget_modern', plugin_dir_url(__FILE__) . 'css/subscribe-widget.css');
        } else {
            wp_enqueue_style('powerpress_subscribe_widget_modern', plugin_dir_url(__FILE__) . 'css/subscribe-widget.min.css');
        }
        if( !empty($instance['modern_style']) && $instance['modern_style'] == 'squared') {
            $Settings['modern_style'] = '-sq';
        }
		if( !empty($instance['modern_style']) && $instance['modern_style'] == 'rounded') {
            $Settings['modern_style'] = ''; // Use new rounded corners
        }

        if( !empty($instance['modern_direction']) && $instance['modern_direction'] == 'horizontal') {
            $Settings['modern_direction'] = 'horizontal';
        }
		if( empty($Settings) )
			return;
		
		if( empty($instance['title']) )
			$instance['title'] = __( 'Subscribe to Podcast' , 'powerpress');
		$instance['title'] = trim($instance['title']);

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo esc_html( $instance['title'] );
			echo $args['after_title'];
		}
		echo  powerpress_do_subscribe_sidebar_widget( $Settings );
		echo $args['after_widget'];
		return;
	}
}

function powerpress_subscribe_register_widget() {
	register_widget( 'PowerPressSubscribe_Widget' );
}

add_action( 'widgets_init', 'powerpress_subscribe_register_widget' );
