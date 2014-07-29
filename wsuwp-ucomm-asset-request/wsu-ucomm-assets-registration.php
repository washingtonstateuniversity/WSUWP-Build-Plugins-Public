<?php
/*
Plugin Name: University Communications Assets Registration
Plugin URI: http://ucomm.wsu.edu/assets/
Description: Allows users to register for assets.
Author: washingtonstateuniversity, jeremyfelt
Author URI: http://web.wsu.edu/
Version: 0.2.0
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

class WSU_UComm_Assets_Registration {

	/**
	 * @var string Script version used to break cache when needed.
	 */
	var $script_version = '0.2.0';

	/**
	 * @var string Post type slug for asset requests.
	 */
	var $post_type_slug = 'ucomm_asset_request';

	/**
	 * @var string User meta key used to assign asset access.
	 */
	var $user_meta_key = '_ucomm_asset_permissions';

	/**
	 * @var string Meta key used to store requested asset types for a user's asset request.
	 */
	var $requested_asset_types_meta_key = '_ucomm_asset_type_request';

	/**
	 * @var string Meta key for storing assets' asset type assignments.
	 */
	var $asset_assignments_meta_key = '_ucomm_asset_assignments';

	/**
	 * Maintain a list of assets that have been assigned asset types.
	 *
	 * @var array Asset type associations.
	 */
	var $assigned_asset_types = array();

	/**
	 * @var array The array of asset type slugs, quantities, and names.
	 */
	var $asset_types = array(
		'office_support_qty'      => array( 'qty' => 0, 'name' => 'Office Support Package' ),
		'full_stone_nocharge_qty' => array( 'qty' => 0, 'name' => 'Full Stone Font Family (no charge)' ),
		'full_stone_charge_qty'   => array( 'qty' => 0, 'name' => 'Full Stone Font Family ($60)' ),
	);

	/**
	 * Setup the hooks.
	 */
	public function __construct() {
		add_filter( 'wsuwp_sso_create_new_user', array( $this, 'wsuwp_sso_create_new_user' ), 10, 1 );
		add_filter( 'wsuwp_sso_new_user_role',   array( $this, 'wsuwp_sso_new_user_role'   ), 10, 1 );
		add_filter( 'user_has_cap',              array( $this, 'map_asset_request_cap'     ), 10, 4 );

		add_action( 'wsuwp_sso_user_created',       array( $this, 'remove_user_roles'    ), 10, 1 );
		add_action( 'init',                         array( $this, 'register_post_type'   ), 10, 1 );
		add_action( 'init',                         array( $this, 'temp_redirect'        ),  5, 1 );
		add_action( 'wp_ajax_submit_asset_request', array( $this, 'submit_asset_request' ), 10, 1 );
		add_action( 'transition_post_status',       array( $this, 'grant_asset_access'   ), 10, 3 );
		add_action( 'add_meta_boxes',               array( $this, 'add_meta_boxes'       ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_asset_file_types' ), 10, 3 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_shortcode( 'ucomm_asset_request',    array( $this, 'ucomm_asset_request_display' ) );
	}

	/**
	 * Add a temporary redirect to force all /assets/ traffic to /assets/font-request/ as that will
	 * be the only asset available at first.
	 */
	public function temp_redirect() {
		if ( '/assets/' === $_SERVER['REQUEST_URI'] ) {
			wp_safe_redirect( site_url( '/font-request/' ) );
			die();
		}
	}

	/**
	 * Enqueue a script for use in the admin view of the asset request.
	 */
	public function admin_enqueue_scripts() {
		if ( 'ucomm_asset_request' === get_current_screen()->id ) {
			wp_enqueue_script( 'admin-assets-request', plugins_url( '/js/admin-asset-request.js', __FILE__ ), array( 'jquery' ), $this->script_version, true );
		}
	}

	/**
	 * Register the post type used to handle asset registration requests.
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => 'Asset Request',
			'singular_name'      => 'Asset Request',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Asset Request',
			'edit_item'          => 'Edit Asset Request',
			'new_item'           => 'New Asset Request',
			'all_items'          => 'All Asset Requests',
			'view_item'          => 'View Asset Request',
			'search_items'       => 'Search Asset Requests',
			'not_found'          => 'No asset requests found',
			'not_found_in_trash' => 'No asset requests found in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Asset Requests',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_admin_bar'  => false,
			'query_var'          => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array(''),
		);

		register_post_type( $this->post_type_slug, $args );
	}

	/**
	 * Map capabilities for users that are requesting access to assets.
	 *
	 * @param array   $allcaps An array of all the role's capabilities.
	 * @param array   $cap     Actual capabilities for meta capability.
	 * @param array   $args    Optional parameters passed to has_cap(), typically object ID.
	 * @param WP_User $user    The user object.
	 *
	 * @return array Modified list of capabilities for a user.
	 */
	public function map_asset_request_cap( $allcaps, $cap, $args, $user ) {
		$user_asset_types = get_user_meta( $user->ID, $this->user_meta_key, true );

		// This user has access to at least one asset type.
		if ( $user_asset_types ) {
			$allcaps['access_asset_type'] = true;
		}

		// Loop through the user's allowed asset types and set the capabilities.
		foreach( (array) $user_asset_types as $asset_type ) {
			$allcaps[ 'access_' . $asset_type ] = true;
		}

		return $allcaps;
	}

	/**
	 * Determine if an asset type has been assigned to a given asset type.
	 *
	 * @param string $asset_name Name of an asset file.
	 *
	 * @return bool|string False if not assigned. String of the asset type if assigned.
	 */
	private function get_assigned_asset_type( $asset_name ) {
		if ( empty( $this->assigned_asset_types ) ) {
			$this->assigned_asset_types = get_post_meta( get_queried_object_id(), $this->asset_assignments_meta_key, true );
		}

		foreach( $this->assigned_asset_types as $asset_type => $name ) {
			if ( $asset_name === $name ) {
				return $asset_type;
			}
		}

		return false;
	}

	/**
	 * Handle the display of the ucomm_asset_request shortcode.
	 *
	 * @return string HTML output
	 */
	public function ucomm_asset_request_display() {
		// Build the output to return for use by the shortcode.
		ob_start();
		?>
		<div id="asset-request">
			<?php

			if ( is_user_member_of_blog() ) {
				if ( current_user_can( 'access_asset_type' ) ) {
					// Retrieve assets attached to this page and display them in a list for download.
					$available_assets = get_attached_media( 'application/zip', get_queried_object_id() );

					echo '<h3>Available Assets</h3><ul>';
					foreach( $available_assets as $asset ) {
						// Has this asset been assigned an asset type?
						$asset_type = $this->get_assigned_asset_type( $asset->post_name );
						if ( $asset_type ) {
							if ( current_user_can( 'access_' . $asset_type ) ) {
								$attached_file = explode( '/', get_attached_file( $asset->ID ) );
								$file_name = array_pop( $attached_file );
								echo '<li><a href="' . esc_url( wp_get_attachment_url( $asset->ID ) ) .'">' . esc_html( $file_name ) . '</a></li>';
							}
						}
					}
					echo '</ul>';
				} else {
					$user_requests = new WP_Query(
						array(
						'post_type'      => $this->post_type_slug,
						'author'         => get_current_user_id(),
						'post_status'    => array( 'publish', 'pending' ),
						'posts_per_page' => 1,
					));

					if ( $user_requests->have_posts() ) {
						echo 'We have received your request for access. You should receive verification and instructions shortly.';
					} else {
						$this->asset_form_output();
					}
				}
			} else {
				if ( is_user_logged_in() ) {
					// To ease the workflow, anybody authenticated user that visits this site should be made a subscriber.
					add_existing_user_to_blog( array( 'user_id' => get_current_user_id(), 'role' => 'subscriber' ) );
					$this->asset_form_output();
				} else {
					echo '<p>Please <a href="' . wp_login_url( network_site_url( $_SERVER['REQUEST_URI'] ), true ) . '">authenticate with your WSU Network ID</a> to request asset access.</p>';
				}
			}
			?>
		</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Display the HTML used to handle the asset request form.
	 *
	 */
	private function asset_form_output() {
		wp_enqueue_script( 'ucomm_asset_request', plugins_url( '/js/asset-request.js', __FILE__ ), array( 'jquery' ), $this->script_version, true );
		wp_localize_script( 'ucomm_asset_request', 'ucomm_asset_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		?>
		<form id="asset-request-form" class="asset-request">
			<input type="hidden" id="asset-request-nonce" value="<?php echo esc_attr( wp_create_nonce( 'asset-request' ) ); ?>" />
			<input type="hidden" id="request-form-post-id" value="<?php echo esc_attr( get_queried_object_id() ); ?>" />
			
			<label for="first_name">First Name:</label><br />
			<input type="text" name="first_name" id="first-name" value="" style="width:100%;" />

			<label for="last_name">Last Name:</label><br />
			<input type="text" name="last_name" id="last-name" value="" style="width:100%;" />

			<label for="email_address">Email Address:</label><br>
			<input type="text" name="email_address" id="email-address" value="" style="width:100%;" />

			<label for="deparatment">College/Department:</label><br>
			<input type="text" name="department" id="department" value="" style="width:100%;" />

			<label for="job_description">Job Title/Description:</label><br>
			<input type="text" name="job_description" id="job-description" value="" style="width:100%;" />			

			<ol>
				<li>
					<p><strong>Office Support Package.</strong> Includes the basic Stone Sans and Stone Serif font families, which are necessary for creating office communications/memorandum for both internal and external audiences in compliance with University brand standards.</p>
					<input type="text" name="office_support_qty" id="office-support-qty" size="2" value="0" />
					<label for="office_support_qty">Office Support Package (no charge)</label>
                    </li>
				<li>
					<p><strong>Stone Sans II and Stone Serif Families.</strong> Includes Stone Sans II (which includes Stone Sans Condensed) and Stone Serif families. This package is for new users who do not currently have the regular Stone Sans and Stone Serif fonts installed on their machines. This package is only used by graphic designers creating branding university communications.</p>
					<input type="text" name="full_stone_nocharge_qty" id="full-stone-nocharge-qty" size="2" value="0" />
					<label for="full_stone_nocharge_qty">Stone Sans II and Stone Serif Families (no charge to University design staff)**</label>
					<br />
					<input type="text" name="full_stone_charge_qty" id="full-stone-charge-qty" size="2" value="0" />
					<label for="full_stone_charge_qty">Stone Sans II and Stone Serif Families ($60 for non-design staff)**</label>
					<p>**If you are requesting this package at no charge and your current position description does not explicitly include visual design responsibilities, or are requesting the package on behalf of such an individual or individuals, please provide brief justification in support of your request in the field below.</p>
				</li>
			</ol>

			<label for="notes">Justification for font family:</label><br>
			<textarea name="notes" id="request-notes" rows="5" style="width:100%;"></textarea>

			<input type="submit" id="submit-asset-request" value="Request Assets" style="float:right">
			<div class="clear"></div>
		</form>
		<?php
	}

	/**
	 * Handle the submission of an asset request form through AJAX.
	 *
	 * Asset type and email address are added to the post title for quick identification
	 * in the admin. Notes submitted by the requesting user are added as post content.
	 *
	 * Additional fields should find their way to post meta so that they can be displayed
	 * as part of the request in the admin.
	 */
	public function submit_asset_request() {
		wp_verify_nonce( 'asset-request' );

		$post = array(
			'post_status' => 'pending',
			'post_type' => $this->post_type_slug,
			'post_author' => get_current_user_id(),
		);

		// We should have at least one font quantity specified for the request if it is valid.
		$font_check = false; // Aids in verification that a quantity has been requested.
		foreach ( $this->asset_types as $font_slug => $font_data ) {
			if ( ! empty( $_POST[ $font_slug ] ) ) {
				$this->asset_types[ $font_slug ][ 'qty' ] = absint( $_POST[ $font_slug ] );
				$font_check = true;
			} else {
				$this->asset_types[ $font_slug ][ 'qty' ] = 0;
			}
		}

		if ( false === $font_check ) {
			echo json_encode( array( 'error' => 'Please enter a quantity for at least one font.' ) );
			die();
		}

		if ( empty( $_POST['first_name'] ) ) {
			echo json_encode( array( 'error' => 'Please enter first name.' ) );
			die();
		} else {
			$first_name = sanitize_text_field( $_POST['first_name'] );
		}

		if ( empty( $_POST['last_name'] ) ) {
			echo json_encode( array( 'error' => 'Please enter last name.' ) );
			die();
		} else {
			$last_name = sanitize_text_field( $_POST['last_name'] );
		}

		if ( empty( $_POST[ 'email_address'] ) ) {
			echo json_encode( array( 'error' => 'Please enter email address.' ) );
			die();
		} else {
			$email = sanitize_email( $_POST['email_address'] );
			$post['post_title'] = sanitize_text_field( 'Request from ' . $first_name . ' ' . $last_name . ' (' . $email . ')' );
		}

		if ( empty( $_POST['department'] ) ) {
			echo json_encode( array( 'error' => 'Please enter department name.' ) );
			die();
		} else {
			$department = sanitize_text_field( $_POST['department'] );
		}

		if ( empty( $_POST['job_description'] ) ) {
			echo json_encode( array( 'error' => 'Please enter job description.' ) );
			die();
		} else {
			$job_description = sanitize_text_field( $_POST['job_description'] );
		}

		if ( empty( $_POST['notes'] ) ) {
			$post['post_content'] = 'No justification notes included in request.';
		} else {
			$post['post_content'] = wp_kses_post( $_POST['notes'] );
		}

		$post_id = wp_insert_post( $post );

		if ( is_wp_error( $post_id ) ) {
			echo json_encode( array( 'error' => 'There was an error creating the request.' ) );
			die();
		}

		//field meta data stuff
		update_post_meta( $post_id, '_ucomm_request_first_name', $first_name );
		update_post_meta( $post_id, '_ucomm_request_last_name',  $last_name );
		update_post_meta( $post_id, '_ucomm_request_email', $email );
		update_post_meta( $post_id, '_ucomm_request_department', $department );
		update_post_meta( $post_id, '_ucomm_request_job_description', $job_description );
		update_post_meta( $post_id, $this->requested_asset_types_meta_key, $this->asset_types );

		$form_post_id = empty( $_POST['post_id'] ) ? 0 : absint( $_POST['post_id'] );
		update_post_meta( $post_id, '_ucomm_request_form_id', $form_post_id );

		// Basic notification email text.
		$message =  "Thank you for completing the font request form.\r\n\r\n";
		$message .= "University Communications has been notified of your request and you should be hearing something shortly.\r\n\r\n";
		$message .= "Once a request has been approved, you will receive another email with a link to download the font files.\r\n\r\n";
		$message .= "Thank you,\r\nUniversity Communications\r\n";

		// Notify the requestor with an email that a request has been received.
		$this->prep_mail_filters();
		wp_mail( $email, 'Font Download Request Received', $message );
		wp_mail( 'assets.ucomm@wsu.edu', 'Font Download Request Received', $message );
		$this->unprep_mail_filters();

		echo json_encode( array( 'success' => 'Request received.' ) );
		die();
	}

	/**
	 * Add meta boxes where required.
	 *
	 * @param string  $post_type Post type slug.
	 * @param WP_Post $post      Current post object.
	 */
	public function add_meta_boxes( $post_type, $post ) {
		if ( 'ucomm_asset_request' === $post_type ) {
			add_meta_box( 'ucomm-asset-request-details', 'Asset Request Details:', array( $this, 'asset_request_details' ), null, 'normal', 'high' );
		}
		if ( 'page' === $post_type && isset( $post->post_content ) && has_shortcode( $post->post_content, 'ucomm_asset_request' ) ) {
			add_meta_box( 'ucomm-asset-files', 'Asset Files:', array( $this, 'asset_request_files' ), null, 'normal', 'default' );
		}
	}

	/**
	 * Display a meta box to show asset files that have been attached to this request
	 * form so that we can assign them to font request types.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function asset_request_files( $post ) {
		$attached_files = get_attached_media( 'application/zip', $post->ID );
		if ( empty( $attached_files ) ) {
			return;
		}
		$file_assigned = get_post_meta( $post->ID, $this->asset_assignments_meta_key, true );

		foreach ( $this->asset_types as $font_slug => $font ) {
			if ( isset( $file_assigned[ $font_slug ] ) ) {
				$this->asset_types[ $font_slug ]['file'] = $file_assigned[ $font_slug ];
			} else {
				$this->asset_types[ $font_slug ]['file'] = '0';
			}
		}

		?><p>Assign a file attached to this request form to each request type.</p>
		<table>
		<thead><tr><td>Request Type</td><td>File</td><td></td></tr></thead>
		<?php foreach( $this->asset_types as $font_slug => $font ) : ?>
		<tr>
			<td><?php echo esc_html( $font['name'] ); ?></td>
			<td><select name="font_assigned-<?php echo esc_attr( $font_slug ); ?>" id="font-assigned">
				<option value="0">---</option>
				<?php foreach( $attached_files as $file ) : ?>
				<option value="<?php echo esc_attr( $file->post_name ); ?>" <?php selected( $font['file'], $file->post_name, true ); ?>><?php echo esc_html( $file->post_title ); ?></option>
				<?php endforeach; ?>
			</td>
		</tr>
		<?php endforeach; ?></table><?php
	}

	/**
	 * Save an array of assigned files to the file permission types when an
	 * asset request form page is updated.
	 *
	 * @param int     $post_id ID of the current post being saved.
	 * @param WP_Post $post    Post object of the current post being saved.
	 * @param bool    $update  True if this is an update. False if not.
	 */
	public function save_asset_file_types( $post_id, $post, $update ) {
		if ( false === $update || 'page' !== $post->post_type || ! has_shortcode( $post->post_content, 'ucomm_asset_request' ) ) {
			return;
		}

		$file_assigned = array();
		foreach( $this->asset_types as $font_slug => $font ) {
			if ( ! empty( $_POST[ 'font_assigned-' . $font_slug ] ) ) {
				$file_assigned[ $font_slug ] = sanitize_key( $_POST[ 'font_assigned-' . $font_slug ] );
			} else {
				$file_assigned[ $font_slug ] = 0;
			}
		}

		update_post_meta( $post_id, $this->asset_assignments_meta_key, $file_assigned );
	}

	/**
	 * Display the details for the loaded asset request in a meta box.
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function asset_request_details( $post ) {
		$first_name  = get_post_meta( $post->ID, '_ucomm_request_first_name', true );
		$last_name   = get_post_meta( $post->ID, '_ucomm_request_last_name',  true );
		$email       = get_post_meta( $post->ID, '_ucomm_request_email',      true );
		$department  = get_post_meta( $post->ID, '_ucomm_request_department', true );
		$job_desc    = get_post_meta( $post->ID, '_ucomm_request_job_description', true );

		// Contains the asset types requested in this asset request.
		$this->asset_types = get_post_meta( $post->ID, $this->requested_asset_types_meta_key, true );

		if ( empty( $this->asset_types ) ) {
			$this->asset_types = array();
		}

		// Contains the asset types that the user has access to.
		$user_asset_types = (array) get_user_meta( $post->post_author, $this->user_meta_key,  true );
		?>
		<ul>
			<li>Name: <?php echo esc_html( $first_name ); ?> <?php echo esc_html( $last_name ); ?></li>
			<li>Email: <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
			<li>Department: <?php echo esc_html( $department ); ?></li>
			<li>Job Description: <?php echo esc_html( $job_desc ); ?></li>
		</ul>
		<h4>Requested Fonts:</h4>
		<table class="font-approval">
			<thead>
			<tr><th align="left">Asset Type</th><th align="right">Quantity</th><th>Approval Status</th></tr></thead>
		<?php foreach( $this->asset_types as $font_slug => $font ) : ?>
			<?php $selected = in_array( $font_slug, $user_asset_types ) ? 1 : 0; ?>
			<tr>
				<td><label for="font_approval_<?php echo esc_attr( $font_slug ); ?>"><?php echo esc_html( $font['name'] ); ?></label></td>
				<td align="right"><?php echo absint( $font['qty'] ); ?></td>
				<td><select name="font_approval_<?php echo esc_attr( $font_slug ); ?>">
						<option value="1" <?php selected( $selected, 1 ); ?>>Approved</option>
						<option value="0" <?php selected( $selected, 0 ); ?>>Not Approved</option>
				</select></td>
			</tr>
		<?php endforeach; ?>
		</table>

		<h4>Notes for use justification:</h4>
		<?php echo $post->post_content; ?>
		<?php
	}

	/**
	 * Grant a user access to asset downloads when their asset request is published. Remove
	 * user access to asset downloads when their asset request is unpublished.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       Current post object.
	 */
	public function grant_asset_access( $new_status, $old_status, $post ) {
		if ( $this->post_type_slug !== $post->post_type ) {
			return;
		}

		// Don't accidentally revoke your own access.
		if ( get_current_user_id() == $post->post_author ) {
			return;
		}

		$user_id = absint( $post->post_author );

		// Set current user access to asset types.
		if ( 'publish' === $new_status ) {
			$user_asset_access = array();
			foreach( $this->asset_types as $font_slug => $font ) {
				if ( isset( $_POST[ 'font_approval_' . $font_slug ] ) && 1 == $_POST[ 'font_approval_' . $font_slug ] ) {
					$user_asset_access[] = $font_slug;
				}
			}
			update_user_meta( $user_id, $this->user_meta_key, $user_asset_access );
			if ( ! empty( $user_asset_access ) ) {
				$this->prep_mail_filters();
				$email = get_post_meta( $post->ID, '_ucomm_request_email', true );
				$email_sent = get_post_meta( $post->ID, '_ucomm_notification_sent', true );

				if ( is_email( $email ) && ! $email_sent ) {
					// Attempt to find the original page on which the request occurred.
					$form_post_id = get_post_meta( $post->ID, '_ucomm_request_form_id', true );
					if ( empty( $form_post_id ) ) {
						$page_url = home_url();
					} else {
						$page_url = get_permalink( absint( $form_post_id ) );
					}

					// Basic approval notification text.
					$message =  "Your request for font access has been approved.\r\n\r\n";
					$message .= "Please visit " . esc_url( $page_url ) . " to download the font files.\r\n\r\n";
					$message .= "Thank you,\r\nUniversity Communications\r\n";
					wp_mail( $email, 'Font Download Request Approved', $message );

					// Log the email send so that this doesn't repeat.
					update_post_meta( $post->ID, '_ucomm_notification_sent', time() );
				}
				$this->unprep_mail_filters();
			}
		}

		// Unset current user access to asset types.
		if ( 'publish' !== $new_status ) {
			delete_user_meta( $user_id, $this->user_meta_key );
		}
	}

	/**
	 * Enable the automatic creation of a new user if authentication is handled
	 * via WSU Network ID and no user exists.
	 *
	 * @return bool
	 */
	public function wsuwp_sso_create_new_user() {
		return true;
	}

	/**
	 * Set an automatically created user's role as subscriber.
	 *
	 * @return string New role for the new user.
	 */
	public function wsuwp_sso_new_user_role() {
		return 'subscriber';
	}

	/**
	 * Remove all roles from a new user when they are automatically created.
	 *
	 * @param int $user_id A user's ID.
	 */
	public function remove_user_roles( $user_id ) {
		$user = get_userdata( $user_id );
		$user->set_role( '' );
	}

	/**
	 * Set filters used to send mail from this plugin.
	 */
	private function prep_mail_filters() {
		add_filter( 'wp_mail_from_name',    array( $this, 'set_mail_from_name'    ) );
	}

	/**
	 * Unset filters used to send mail from this plugin.
	 */
	private function unprep_mail_filters() {
		remove_filter( 'wp_mail_from_name',    array( $this, 'set_mail_from_name'    ) );
	}

	/**
	 * Modify the default email from name for email sent by WordPress.
	 *
	 * @return string The from name used in the email.
	 */
	public function set_mail_from_name() {
		return 'University Communications - Font Request';
	}
}
new WSU_UComm_Assets_Registration();
