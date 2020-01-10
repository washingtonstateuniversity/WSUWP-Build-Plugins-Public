<?php
/**
 * Class PowerPressPostToSocial
 */

define('POWERPRESS_POSTED_STATUS_NOT_POSTED_YET', 0);
define('POWERPRESS_POSTED_STATUS_SUCCESS', 1);
define('POWERPRESS_POSTED_STATUS_FAILED', 2);
define('POWERPRESS_POSTED_STATUS_INVALID_CREDENTIALS', 3);
define('POWERPRESS_POSTED_STATUS_NO_CREDENTIALS', 4);
define('POWERPRESS_POSTED_STATUS_NOT_AUDIO', 5);
define('POWERPRESS_POSTED_STATUS_CONVERSION_FAILED', 6);
define('POWERPRESS_POSTED_STATUS_PROGRAM_ID_NOT_MATCHED', 7);
define('POWERPRESS_POSTED_STATUS_NOT_SCHEDULED', 8);

class PowerPressPostToSocial {
	// member variables


	// constructor
	/**
	 * PowerPressPostToSocial constructor.
	 */
	function __construct() {
		// WordPress hooks go here
		add_action( 'load-post.php', array( $this, 'my_add_metabox_setup' ) );
		add_action( 'load-post-new.php', array( $this, 'my_add_metabox_setup' ) );
		add_action( 'do_pings', array( $this , 'do_pings' ), 11, 0 );
	}

	// destructor
	/**
	 *
	 */
	function __destruct() {

	}

	// other functions //

	/**
	 *
	 */
	function my_add_metabox_setup() {
		add_action( 'add_meta_boxes', array($this, 'my_add_metabox') );
	}

	/**
	 *
	 */
	function my_add_metabox() {
		add_meta_box( 'pps_pts', __( 'PowerPress Post to Social', 'powerpress' ),  array($this, 'display_my_metabox'), 'post', 'side', 'default' );
	}

	/**
	 *
	 */
	function display_my_metabox() {
	
		
		$can_post = false;
		if ( get_post_status( get_the_ID() ) == 'publish' ) {
			$post_id = get_the_ID();
			$guid = urlencode( get_the_guid() );
			
			$EpisodeData = powerpress_get_enclosure_data($post_id);
			if( !empty($EpisodeData) && parse_url($EpisodeData['url'], PHP_URL_HOST) == 'media.blubrry.com' ) {
				add_thickbox();
				echo "<strong styleX='font-size: 115%; display: block; text-align: center;'><a class='thickbox button button-primary button-large' href='admin.php?action=powerpress-jquery-pts&width=600&height=550&post_id={$post_id}&guid={$guid}&TB_iframe=true' target='blank' title='Post to Social'>Post to Social</a></strong>";
				$can_post = true;
				echo "<br><br>\n";
			}
		}
		else {
			echo "Post to Social";
		}
		echo " ";
		echo "Status: "; // What is the status of the posting to social?
		if ( get_post_status( get_the_ID() ) == 'publish' ) {
			if ( get_post_meta( get_the_ID(), 'pts_scheduled', true ) ) {
				_e( 'Episode posted to social sites.', 'powerpress' );
			}
			else if( $can_post == false ) {
			
				_e( 'No podcast episode available in this post to send to social sites.', 'powerpress' );
			} else if (parse_url($EpisodeData['url'], PHP_URL_HOST) == 'media.blubrry.com') {
				_e( 'Nothing posted yet.', 'powerpress' );
				echo ' ';
				echo "<a class='thickbox' href='admin.php?action=powerpress-jquery-pts&width=600&height=550&post_id={$post_id}&guid={$guid}&TB_iframe=true' target='blank'>Post Now!</a>";
			}
			else {
			    _e('The media file must be hosted on Blubrry to post to social sites.', 'powerpress');
            }
		}
		else {
			echo "This post must be published before you can post to social sharing sites.";
		}
		
		echo "<p style=\"font-size: 85%; margin-top: 20px;\">";
		echo 'About: Post podcast episodes to Twitter, YouTube and Facebook using Blubrry\'s <a href="https://create.blubrry.com/resources/podcast-media-hosting/post-to-social/" target="_blank">Post to Social</a> service.';
		echo "</p>";
	}

	function do_pings() {
		$Settings = get_option( 'powerpress_general' );

		$post_id = get_the_ID();
		$guid = get_the_guid();

		$enclosure_data = powerpress_get_enclosure_data( $post_id );
		if ( !empty( $enclosure_data ) ) {
			$results = callUpdateListing($post_id, $guid);
			$podcast_id = $results['podcast-id'];

			add_post_meta( $post_id, 'podcast-id', $podcast_id, true );
		}
	}
}

/**
 * @param int $post_id
 * @param string $program_keyword
 * @param string $guid
 * @return array|mixed|object|string
 */
function callUpdateListing( $post_id, $guid ) {
	$Settings = get_option('powerpress_general');
	$episodeData = powerpress_get_enclosure_data( $post_id );
	if(!empty($episodeData['program_keyword'])) {
	    //for multi account support, if empty then fallback to
	    $program_keyword = $episodeData['program_keyword'];
    }
	else {
        $program_keyword = $Settings['blubrry_program_keyword'];
    }
	if( empty($episodeData['duration']) )
		$episodeData['duration'] = '';
	
	$subtitle = '';
	if( !empty($episodeData['subtitle']) )
		$subtitle = $episodeData['subtitle'];

	if ( empty( $subtitle ) && !empty($episodeData['summary']) ) {
		$subtitle = substr( $episodeData['summary'], 0, 255 );
	}
	if ( empty( $subtitle ) ) {
		$subtitle = powerpress_get_the_exerpt( false, true, $post_id );
	}
	if ( empty( $subtitle ) ) {
		$subtitle = substr( get_the_content( $post_id ), 0, 255 );
	}

	$FeedSettings = get_option( 'powerpress_feed' );

	$post_params = array(
		'feed-url'  => '',                                           // required
		'title'     => get_the_title( $post_id ),                    // required
		'date'      => get_the_date( 'r', $post_id ),                // required
		'guid'      => $guid,
		'media-url' => $episodeData['url'],                          // required
		'subtitle'  => $subtitle,
		'duration'  => $episodeData['duration'], // hh:mm:ss format; we assume no podcast episode will exceed 24 hours
		'filesize'  => $episodeData['size'],                         // required
		'explicit'  => $FeedSettings['itunes_explicit'],
		'link'      => get_the_permalink( $post_id ),
		'image'     => $FeedSettings['itunes_image'],
	);

	$api_url_array = powerpress_get_api_array();

	foreach ( $api_url_array as $api_url ) {
		$response = powerpress_remote_fopen( "{$api_url}social/{$program_keyword}/update-listing.json", $Settings['blubrry_auth'], json_encode( $post_params ) );

		if ( $response ) {
			break;
		}
	}

	if ( $response ) {
		$result = json_decode( $response, true );
		if( !empty($result) )
			return $result;
			
		return $response;
	}
	else {
		return false;
	}
}

/**
 * @param string $program_keyword
 * @return array|mixed|object|string
 */
function callGetSocialOptions( $program_keyword, $podcast_id ) {
	$Settings = get_option( 'powerpress_general' );

	$api_url_array = powerpress_get_api_array();
	foreach ( $api_url_array as $api_url ) {
		$response = powerpress_remote_fopen("{$api_url}social/{$program_keyword}/get-social-options.json?podcast_id={$podcast_id}", $Settings['blubrry_auth'] );
		if ( $response ) {
			break;
		}
	}

	if ( $response ) {
		return json_decode( $response, true );
	}
}

/**
 * Generates an HTML text input
 *
 * @param $label
 * @param string $name
 * @param string $value
 *
 * @param string $placeholder
 * @param string $help_text
 * @param int $rows
 * @param int $maxlength
 *
 * @return string
 */
function generate_text_field( $label, $name, $value='', $placeholder='', $help_text='', $rows=1, $maxlength=4000 ) {
	$text_field = '<div class="form-group">' ."\n";
	$text_field .= "<label for='{$name}'>{$label}</label>\n";

	if ( $rows === 1 ) {
		$text_field .= '<input type="text" ';
	}
	else {
		$text_field .= "<textarea rows='{$rows}'";
	}

	$text_field .= "name='{$name}' ";
	$text_field .= "id='{$name}' ";
	$text_field .= "placeholder='{$placeholder}' ";
	$text_field .= "maxlength='{$maxlength}' ";

	if ( $rows === 1 ) {
		$text_field .= "value='{$value}' ";
	}

	$text_field .= "class='form-control' aria-describedby='{$name}-help'>";
	if ( $rows > 1 ) {
		$text_field .= $value;
		$text_field .= '</textarea>';
	}

	$text_field .= "\n<span id='{$name}-help' class='help-block'>{$help_text}</span>";
	$text_field .= "\n</div>";
	return $text_field;
}

/**
 * Generates an HTML checkbox input
 *
 * @param string $label
 * @param string $name
 * @param string $value
 * @param string $checked 'checked' to have the box checked, '' otherwise
 *
 * @return string
 */
function generate_checkbox( $label, $name, $value, $checked='' ) {
	$checkbox = '<label>' ."\n";
	$checkbox .= "<input type='checkbox' name='{$name}' value='{$value}' {$checked}> {$label}\n";
	$checkbox .= '</label>' ."\n";

	return $checkbox;
}

/**
 * Generates an HTML radio input
 *
 * @param string $label
 * @param string $name
 * @param string $value
 * @param string $checked 'checked' to have the radio selected, '' otherwise
 *
 * @return string
 */
function generate_radio( $label, $name, $value, $checked='' ) {
	$checkbox = '<label>' ."\n";
	$checkbox .= "<input type='radio' name='{$name}' value='{$value}' {$checked}> {$label}\n";
	$checkbox .= '</label>' ."\n";

	return $checkbox;
}

function displayStatus($isPostedArray)
{
    $tempStatusArray = $isPostedArray;

    if (sizeof($tempStatusArray) > 1){
        if (end($tempStatusArray) == 0){
            $tempStatusArray = array($tempStatusArray[0], end($tempStatusArray));
        }
        else {
            $tempStatusArray = array($tempStatusArray[0]);
        }
    }
    foreach($tempStatusArray as $isPosted){

        if ($isPosted == POWERPRESS_POSTED_STATUS_SUCCESS) { ?>
            <span class="label label-success">Posted!</span>
            <?php return;

        } else if ($isPosted == POWERPRESS_POSTED_STATUS_NOT_POSTED_YET) { ?>
            <script>post_scheduled_notification.style.display = "block";</script>

            <?php if (sizeof($isPostedArray) > 1) {?>
                <span class="label label-primary"> Post Rescheduled</span>

            <?php } else { ?>
                <span class="label label-primary"> Post Scheduled</span>
            <?php } return;

        } else if ($isPosted == POWERPRESS_POSTED_STATUS_NOT_SCHEDULED) { ?>

        <?php } else if ($isPosted == POWERPRESS_POSTED_STATUS_CONVERSION_FAILED) { ?>
            <span class="label label-danger">Error occurred: Video creation failed</span>

        <?php } else if($isPosted == POWERPRESS_POSTED_STATUS_NOT_AUDIO) { ?>
            <span class="label label-danger">Error occurred: Mp3 file required for posting to Youtube</span>

        <?php } else if ($isPosted == POWERPRESS_POSTED_STATUS_INVALID_CREDENTIALS || $isPosted == POWERPRESS_POSTED_STATUS_NO_CREDENTIALS) { ?>
            <span class="label label-danger">Error occurred: Please re-link your account</span>

        <?php } else if ($isPosted > POWERPRESS_POSTED_STATUS_SUCCESS) { ?>
            <span class="label label-danger">Error occurred</span>

            <?php
        }
    }

}

function powerpress_ajax_pts($Settings)
{
	powerpress_admin_jquery_header( __( 'Post to Social', 'powerpress' ) );

	if ( !current_user_can('publish_posts' ) ) {
		powerpress_page_message_add_notice( __( 'You do not have sufficient permission to do this.', 'powerpress' ) );
		powerpress_page_message_print();
		?>
		<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
		<?php
		powerpress_admin_jquery_footer();
		exit;
	}

	if ( empty( $Settings['blubrry_program_keyword'] ) ) {
		powerpress_page_message_add_notice( __( 'You must connect your Blubrry account and set up a program.', 'powerpress' ) );
		powerpress_page_message_print();
		?>
		<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
		<?php
		powerpress_admin_jquery_footer();
		exit;
	}

	// Make API calls here //

	$post_id = (int) $_GET['post_id'];
	$guid    = urldecode( $_GET['guid'] );

	// make sure the podcast episode is in the Blubrry directory using the `update-listing` api call
	if ( get_post_meta( $post_id, 'podcast-id', true ) ) {
		$response = array( 'podcast-id' => get_post_meta( $post_id, 'podcast-id', true ) );
	}
	else {
		$response = callUpdateListing( $post_id, $guid );
	}
	if ( !is_array( $response ) ) { // an error occurred\	
		echo "<br /><br />";
		echo $response;

		exit;
	}
	
	//die('ok2');
	
	if ( isset( $response['error'] ) ) {
		powerpress_page_message_add_notice( $response['error'] );
		powerpress_page_message_print();
		?>
		<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
		<?php
		powerpress_admin_jquery_footer();
		exit;
	}

	if ( isset( $response['warnings'] ) ) {
		powerpress_page_message_add_notice( $response['warnings'] );
		powerpress_page_message_print();
	}

	$podcast_id = $response['podcast-id'];
    $EpisodeData = powerpress_get_enclosure_data($post_id);
    if(!empty($EpisodeData['program_keyword'])) {
        $program_keyword = $EpisodeData['program_keyword'];
    }
    else {
        $program_keyword = $Settings['blubrry_program_keyword'];
    }
    add_post_meta( $post_id, 'podcast-id', $podcast_id, true );
	// get the info necessary to create the post to social form using the `get-social-options` api call
	$response = callGetSocialOptions( $program_keyword, $podcast_id );

    if ( !is_array( $response ) ) { // a cURL error occurred
		echo $response;
		exit;
	}

	if ( isset( $response['error'] ) ) {
		powerpress_page_message_add_notice( __( 'There was a problem fetching your post to social settings. ', 'powerpress' ) .$response['error'] );
		powerpress_page_message_print();
		?>
		<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
		<?php
		powerpress_admin_jquery_footer();
		exit;
	}

	// build the post to social form
	if( !empty($response['success']) )
		echo "<h3>{$response['success']}</h3>";
	//else
		//var_dump($response);
	?>

    <script language=JavaScript>

        function check_length(pts_form)
        {
            maxLen = 280 - (pts_form.twitter_link.value.length+1); // max number of characters allowed
            if (pts_form.twitter_content.value.length > maxLen) {
                var msg = "You have reached your maximum limit of characters allowed";
                alert(msg);
                pts_form.twitter_content.value = pts_form.twitter_content.value.substring(0, maxLen);

            } else {

                document.getElementById('text_length').innerHTML = maxLen - pts_form.twitter_content.value.length;
            }
        }

    </script>

	<script>var linkel = document.createElement('link'); linkel.rel = 'stylesheet'; linkel.href = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'; document.head.appendChild(linkel);</script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

	<form action="admin.php?action=powerpress-jquery-pts-post" method="POST" id="pts_form" name="pts_form">
		<input type="hidden" name="podcast-id" value="<?php echo $podcast_id; ?>">
						<input type="hidden" name="post-id" value="<?php echo $post_id; ?>">
	<?php
        if (empty($response['accounts'])) {
            echo '<h2>No social accounts linked</h2>';
            echo '<p>Visit the <a href="https://publish.blubrry.com/social/" target="_blank">Podcaster Dashboard</a> to link your social accounts</p>';
        }
        if (!empty($response['accounts']['Twitter'])){
            echo '<h2>' ."<img src='{$response['settings']['twitter_image']}'>" ." Twitter" .'</h2>'; ?>

            <label>Check the accounts where you want this episode to be posted</label> <br> <?php
            foreach ($response['accounts']['Twitter'] as $meta_id => $account){
                $isPostedToTwitter = $account['status']; ?>
                <label  class="checkbox-inline"><input type="checkbox" name="twitter_meta[]"
                        value="<?php echo $meta_id ?>" <?php echo ($isPostedToTwitter[0] < POWERPRESS_POSTED_STATUS_FAILED || $isPostedToTwitter[0] == POWERPRESS_POSTED_STATUS_NOT_SCHEDULED) ? 'checked' : '';?> <?php echo ($isPostedToTwitter[0] == POWERPRESS_POSTED_STATUS_SUCCESS) ? 'disabled' : '';?>> <?php echo "@" . $account['twitter_handle']?></label>
                <?php displayStatus($isPostedToTwitter)?><br>

            <?php } ?>
            <br>
            <input type="hidden" id="twitter_link" name="twitter_link" value="<?php echo $response['settings']['link'] ?>" />
            <textarea onKeyUp=check_length(this.form); class="form-control" rows="2" name="twitter_content" id="twitter_content"
                      size="<?php echo 280 - (strlen($response['settings']['link'])); ?>" placeholder="What's happening?" <?php echo ($isPostedToTwitter[0] == POWERPRESS_POSTED_STATUS_SUCCESS || $isPostedToTwitter[0] == POWERPRESS_POSTED_STATUS_NOT_POSTED_YET) ? 'readonly' : '';?>
            ><?php echo (isset($response['accounts']['twitter_posted_array']['content']) ? $response['accounts']['twitter_posted_array']['content'] : '')?></textarea>
            <b>Characters Left: </b><label id="text_length"> <?php echo 280 - (strlen($response['settings']['link'])); ?></label>
            Player URL: <?php echo $response['settings']['link']; ?>
        <?php }

        if (!empty($response['accounts']['Youtube'])){
            echo '<h2>' ."<img src='{$response['settings']['youtube_image']}'>" ." Youtube" .'</h2>'; ?>

            <label>Check the accounts where you want this episode to be posted</label> <br> <?php
            foreach ($response['accounts']['Youtube'] as $meta_id => $account){ ?>
                <?php $isPostedToYoutube = $account['status']; ?>
                <label class="checkbox-inline"><input type="checkbox" name="youtube_meta[]"
                        value="<?php echo $meta_id ?>" <?php echo ($isPostedToYoutube[0] < POWERPRESS_POSTED_STATUS_FAILED || $isPostedToYoutube[0] == POWERPRESS_POSTED_STATUS_NOT_SCHEDULED) ? 'checked' : '';?> <?php echo ($isPostedToYoutube[0] == POWERPRESS_POSTED_STATUS_SUCCESS) ? 'disabled' : '';?>> <?php echo "as " . $account['youtube_name']?></label>
                <?php displayStatus($isPostedToYoutube); ?><br>
            <?php } ?>
            <br>
            <label for="youtube_title">Video Title</label>
            <textarea class="form-control" rows="1" id="youtube_title" name="youtube_title"
                      placeholder="Video title" <?php echo ($isPostedToYoutube[0] == POWERPRESS_POSTED_STATUS_SUCCESS || $isPostedToYoutube[0] == POWERPRESS_POSTED_STATUS_NOT_POSTED_YET) ? 'readonly' : '';?>><?php echo (isset($response['accounts']['youtube_posted_array']['youtube_title']) ? $response['accounts']['youtube_posted_array']['youtube_title'] : '')?></textarea>
            <label for="youtube_description">Youtube description</label>
            <textarea class="form-control" rows="3" id="youtube_description" name="youtube_description"
                      placeholder="Youtube description" <?php echo ($isPostedToYoutube[0] == POWERPRESS_POSTED_STATUS_SUCCESS || $isPostedToYoutube[0] == POWERPRESS_POSTED_STATUS_NOT_POSTED_YET) ? 'readonly' : '';?>><?php echo (isset($response['accounts']['youtube_posted_array']['youtube_description']) ? $response['accounts']['youtube_posted_array']['youtube_description'] : '')?></textarea>
        <?php }

        if (!empty($response['accounts']['Facebook'])){
            echo '<h2>' ."<img src='{$response['settings']['facebook_image']}'>" ." Facebook" .'</h2>'; ?>

            <label>Check the pages where you want this episode to be posted</label> <br> <?php
            foreach ($response['accounts']['Facebook'] as $meta_id => $account){
                foreach ($account['pages'] as $page){ ?>
                    <?php $isPostedToFacebook = $page['status']; ?>
                    <label  class="checkbox-inline"><input type="checkbox" name="facebook_meta[<?php echo $meta_id ?>][]"
                           value="<?php echo $page['name'] ?>" <?php echo ($isPostedToFacebook[0] < POWERPRESS_POSTED_STATUS_FAILED || $isPostedToFacebook[0] == POWERPRESS_POSTED_STATUS_NOT_SCHEDULED) ? 'checked' : '';?> <?php echo ($isPostedToFacebook[0] == POWERPRESS_POSTED_STATUS_SUCCESS) ? 'disabled' : '';?>> <?php echo $page['name'] . " (as " . $account['social_name'] . ")"?></label>
                    <?php displayStatus($isPostedToFacebook); ?><br>
            <?php }
            } ?>
            <br>
            <label for="facebook_link">Link to Podcast</label>
            <textarea class="form-control" rows="1" id="facebook_link" name="facebook_link"
                      placeholder="Link your podcast here" <?php echo ($isPostedToFacebook[0] == POWERPRESS_POSTED_STATUS_SUCCESS || $isPostedToFacebook[0] == POWERPRESS_POSTED_STATUS_NOT_POSTED_YET) ? 'readonly' : '';?>><?php echo (isset($response['accounts']['facebook_posted_array']['link_to_podcast']) ? $response['accounts']['facebook_posted_array']['link_to_podcast'] : $response['settings']['link']) ?></textarea>
            <br>
            <label for="facebook_description">Post to Facebook</label>
            <textarea class="form-control" rows="3" id="facebook_description" name="facebook_description"
                      placeholder="What's on your mind?" <?php echo ($isPostedToFacebook[0] == POWERPRESS_POSTED_STATUS_SUCCESS || $isPostedToFacebook[0] == POWERPRESS_POSTED_STATUS_NOT_POSTED_YET) ? 'readonly' : '';?>><?php echo (isset($response['accounts']['facebook_posted_array']['facebook_description']) ? $response['accounts']['facebook_posted_array']['facebook_description'] : '')?></textarea>

        <?php }
	?>
		<hr>
        <small>Disclaimer: By hitting "Post to Selected Media Accounts" you are agreeing to allow Blubrry to post the
            above content on the selected Social Media Accounts.
        </small><br><br>
        <input class="btn btn-sm btn-primary" name="do_update" type="submit"
               value="Post To Selected social media accounts"
               id="post_button" />
    <a href="#" class="btn btn-sm btn-default"
       onclick="self.parent.tb_remove();"><?php echo __( 'Cancel', 'powerpress' ); ?></a>
    </form>
	<?php
	powerpress_admin_jquery_footer();
}

function powerpress_ajax_pts_post($Settings)
{
	powerpress_admin_jquery_header( __( 'Post to Social', 'powerpress' ) );

	//$Settings = get_option('powerpress_general');

	$api_url_array = powerpress_get_api_array();

	$podcast_id = $_POST['podcast-id'];
	$post_id = $_POST['post-id'];
	$EpisodeData = powerpress_get_enclosure_data($post_id);
	if(!empty($EpisodeData['program_keyword'])) {
        $program_keyword = $EpisodeData['program_keyword'];
    }
	else {
        $program_keyword = $Settings['blubrry_program_keyword'];
    }

	unset( $_POST['podcast-id'] );
	unset( $_POST['post-id'] );

	$post_data = array();

    /*foreach ( $_POST as $key => $value ) {
        if ( $value ) { // we don't allow empty messages to be posted to social media

            preg_match("/-(\d+)-?/", $key, $matches);
            $social_id = $matches[1];

            preg_match("/^(\w+)-/i", $key, $matches);
            $social_type = strtolower($matches[1]);

            if ( !isset( $post_data[ $social_id ] ) ) {
                $post_data[ $social_id ] = array(
                    'social-id' => $social_id,
                    'social-type' => $social_type,
                );
            }

            if ( !isset( $post_data[ $social_id ]['social-data'] ) ) {
                $post_data[ $social_id ]['social-data'] = array();
            }

            $field_name = preg_replace( "/^\w+-/i", "", $key );

            $post_data[ $social_id ]['social-data'][ $field_name ] = $value;
        }

    }*/

	if (!empty($_POST['twitter_content'])){
	    $post_data['twitter']['accounts'] = $_POST['twitter_meta'];
	    $post_data['twitter']['content'] = $_POST['twitter_content'];
	    $post_data['twitter']['link'] = $_POST['twitter_link'];
    }

	if (!empty($_POST['facebook_description'])){
	    $post_data['facebook']['accounts'] = $_POST['facebook_meta'];
	    $post_data['facebook']['description'] = $_POST['facebook_description'];

	    if (!empty($_POST['facebook_link'])){
	        $post_data['facebook']['link'] = $_POST['facebook_link'];
        }
	    else {
            $post_data['facebook']['link'] = $_POST['twitter_link'];
        }
    }

	if (!empty($_POST['youtube_description'])){
	    $post_data['youtube']['accounts'] = $_POST['youtube_meta'];
	    $post_data['youtube']['description'] = $_POST['youtube_description'];
	    $post_data['youtube']['title'] = $_POST['youtube_title'];
    }


	$post_params = array( 'podcast-id' => $podcast_id, 'post-data' => $post_data, '' );

	foreach ( $api_url_array as $api_url ) {
		$response = powerpress_remote_fopen( "{$api_url}social/{$program_keyword}/post.json", $Settings['blubrry_auth'], json_encode( $post_params ) );
		if ( $response ) {
			break;
		}
	}

	$response = json_decode( $response, true );

	if ( $response['status'] == 'success' ) {
		powerpress_page_message_add_notice( __( 'Posting to social been scheduled! Please allow up to an hour to post.', 'powerpress' ) );
		powerpress_page_message_print();

		add_post_meta( $post_id, 'pts_scheduled', 1, true );
	}
	else {
		powerpress_page_message_add_notice( $response['error'] );
		powerpress_page_message_print();
	}
	?>
	<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
	<?php
	powerpress_admin_jquery_footer();
}

$powerpress_PTS = new PowerPressPostToSocial();


