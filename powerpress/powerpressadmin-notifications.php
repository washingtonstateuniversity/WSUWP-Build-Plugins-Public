<?php
	// powerpressadmin-notifications.php
	
	// Notice message manager for PowerPress
	
	// Inspired by the notifications in Yoast's SEO
	// Also inspired from https://premium.wpmudev.org/blog/adding-admin-notices/
	// For the sake of keeping things simple, we will only include this class when we need to display notifications
	
class PowerPress_Notification {
	
	private $settings = array();
	
	public function __construct($content, $settings = array() )
	{
		$defaults = array();
		$defaults['content'] = '';
		$defaults['type'] = 'updated';
		$defaults['id'] = '';
		$this->settings = wp_parse_args( $settings, $defaults );
		if( !empty($content) )
			$this->settings['content'] = $content;
		if( empty($this->settings['content']) )
			$this->settings['id'] = crc32($this->settings['content']); // Not ideal, but with no ID we need some unique value
	}
	
	public function get_notification_as_string()
	{
		$html = '<div class="powerpress-notice notice is-dismissible ' . esc_attr( $this->settings['type'] ) . '" id="powerpress-notice-'. esc_attr( $this->settings['id'] ) .'">'; 
		$html .= wpautop( $this->settings['content'] );
			
		if( version_compare($GLOBALS['wp_version'], 4.2, '<' ) ) {
			$html .= '<p>&nbsp; <a style="float:right;" href="#" class="notice-dismiss-link">'. __('Dismiss', 'powerpress') .'</a></p>';
		}
		$html .= '</div>' . PHP_EOL;
		return $html;
	}
	
};

class PowerPress_Notification_Manager {

	private $notifications = array();
	private $dismissedNotifications = array();
	
	public function __construct()
	{
		$this->dismissedNotifications = get_option('powerpress_dismissed_notices');
		add_action( 'all_admin_notices', array( $this, 'all_admin_notices' ) );
		add_action('wp_ajax_powerpress_notice_dismiss', array($this, 'wp_ajax_powerpress_notice_dismiss') );
		add_action('admin_head', array($this, 'admin_head') );
	}
	
	public function all_admin_notices()
	{
		foreach( $this->notifications as $key => $notification )
		{
			echo $notification->get_notification_as_string();
		}
	}
	
	public function wp_ajax_powerpress_notice_dismiss()
	{
		$dismiss_notice_id = $_POST['dismiss_notice_id'];
		preg_match('/^powerpress-notice-(.*)$/i', $dismiss_notice_id, $match );
		if( empty($match[1]) )
			die('-1');
			
		$DismissedNotifications = get_option('powerpress_dismissed_notices');
		if( !is_array($DismissedNotifications) )
			$DismissedNotifications = array();
		$DismissedNotifications[ $match[1] ] = 1;
		update_option('powerpress_dismissed_notices',  $DismissedNotifications);
		die('1');
	}
	
	function admin_head()
	{
		if( count($this->notifications) > 0 ) // If there are notices to print, then lets also put in the ajax to clear them
		{
			if( version_compare($GLOBALS['wp_version'], 4.2, '>=' ) ) {
?>
<script type="text/javascript"><!--

jQuery(document).ready( function() {
	
	jQuery(document).on( 'click', '.powerpress-notice .notice-dismiss', function() {
	
		var dismissId = jQuery(this).closest('.powerpress-notice').attr('id');
		jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {action:"powerpress_notice_dismiss", dismiss_notice_id: dismissId });
			});
});

--></script>
<?php
			}
			else
			{
?>
<script type="text/javascript"><!--

jQuery(document).ready( function() {
	
	jQuery(document).on( 'click', '.powerpress-notice .notice-dismiss-link', function(e) {
		e.preventDefault();
		var dismissId = jQuery(this).closest('.powerpress-notice').attr('id');
		jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {action:"powerpress_notice_dismiss", dismiss_notice_id: dismissId });
		jQuery(this).closest('.powerpress-notice').hide(); // Hide the div now we dismissed it
	});
});

--></script>
<?php
			}
?>
<style>
.powerpress-notice a {
	text-decoration: underline;
}
</style>	
<?php
		}
	}
	
	public function dismissed_status($notification_id)
	{
		if( !empty($this->dismissedNotifications[ $notification_id ]) )
			return true;
		return false;
	}
	
	public function add($notification_id, $notification_content)
	{
		if( !$this->dismissed_status($notification_id) ) {
			$this->notifications[$notification_id] = new PowerPress_Notification($notification_content, array('id'=>$notification_id)  );
		}
	}

};

function powerpressadmin_notifications_appropriate()
{
	// Any powerpress page
	if( preg_match('/wp-admin\/.*powerpress/', $_SERVER['REQUEST_URI']) )
		return true;
		
	// Dashboard is acceptable:
	if( preg_match('/wp-admin\/(index\.php)?$/', $_SERVER['REQUEST_URI']) )
		return true;
	
	// edit posts (pages, post types, etc...)
	if( preg_match('/wp-admin\/edit\.php/', $_SERVER['REQUEST_URI']) )
		return true;
		
	// managing plugins
	if( preg_match('/wp-admin\/plugins\.php/', $_SERVER['REQUEST_URI']) )
		return true;
	
	// Required so we can dismiss notices
	if( preg_match('/wp-admin\/admin-ajax\.php/', $_SERVER['REQUEST_URI']) )
		return true;
		
	return false;
}



// eof