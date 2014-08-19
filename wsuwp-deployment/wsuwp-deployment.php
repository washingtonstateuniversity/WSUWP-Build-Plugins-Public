<?php
/*
Plugin Name: WSUWP Deployment
Plugin URI: http://web.wsu.edu
Description: Receive deploy requests in WordPress and act accordingly.
Author: washingtonstateuniversity, jeremyfelt
Version: 1.0.0
*/

class WSU_Deployment {

	/**
	 * @var string Slug to track the deployment post type.
	 */
	var $post_type_slug = 'wsuwp_deployment';

	/**
	 * @var string Slug to track deployment instances in a post type.
	 */
	var $deploy_instance_slug = 'wsuwp_depinstance';

	/**
	 * @var array List of deploy types allowed by default.
	 */
	var $allowed_deploy_types = array(
		'theme-individual',
		'plugin-individual',
		'build-plugins-public',
		'build-plugins-private',
		'build-themes-public',
		'build-themes-private',
		'platform'
	);

	/**
	 * Add hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'save_post', array( $this, 'save_repository_url' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_deploy_type' ), 10, 2 );
	}

	/**
	 * Register the deployment and deployment instance post types to track
	 * the deployments that have been created and then initiated.
	 */
	public function register_post_type() {
		global $blog_id, $site_id;

		// Only enable this on the network's primary site.
		if ( 1 != $blog_id || 1 != $site_id ) {
			return;
		}

		$labels = array(
			'name' => 'Deployments',
			'singular_name' => 'Deployment',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Deployment',
			'edit_item' => 'Edit Deployment',
			'new_item' => 'New Deployment',
			'all_items' => 'All Deployments',
			'view_item' => 'View Deployments',
			'search_items' => 'Search Deployments',
			'not_found' => 'No deployments found',
			'not_found_in_trash' => 'No deployments found in Trash',
			'menu_name' => 'Deployments',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'deployment' ),
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title', ),
		);
		register_post_type( $this->post_type_slug, $args );

		$instance_labels = array(
			'name' => 'Deployment Instances',
			'singular_name' => 'Deployment Instance',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Deployment Instance',
			'edit_item' => 'Edit Deployment Instance',
			'new_item' => 'New Deployment Instance',
			'all_items' => 'All Deployment Instances',
			'view_item' => 'View Deployment Instances',
			'search_items' => 'Search Deployment Instances',
			'not_found' => 'No deployment instances found',
			'not_found_in_trash' => 'No deployment instances found in Trash',
			'menu_name' => 'Deployment Instances',
		);

		$instance_args = array(
			'labels'             => $instance_labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'rewrite'            => array( 'slug' => 'deployment-instance' ),
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title', ),
		);
		register_post_type( $this->deploy_instance_slug, $instance_args );

		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
	}

	/**
	 * Capture the actual deployment information when notified from
	 * version control. This avoids the complete load of the template.
	 */
	public function template_redirect() {
		if ( ! is_singular( $this->post_type_slug ) ) {
			return;
		}

		if ( isset( $_SERVER[ 'HTTP_X_GITHUB_EVENT' ] ) && 'create' === $_SERVER[ 'HTTP_X_GITHUB_EVENT' ] && ! empty( $_POST['payload'] ) ) {
			$this->_handle_create_webhook();
		} elseif ( ! isset( $_SERVER['HTTP_X_GITHUB_EVENT'] ) ) {
			wp_safe_redirect( home_url() );
		}

		die();
	}

	/**
	 * Handle the 'create' event passed via webhook from GitHub.
	 */
	private function _handle_create_webhook() {
		// This seems overkill, but it is working.
		$payload = wp_unslash( $_POST['payload'] );
		$payload = maybe_serialize( $payload );
		$payload = maybe_unserialize( $payload );
		$payload = json_decode( $payload );

		$deployment_data = array(
			'tag' => false,
			'ref_type' => false,
			'sender' => false,
			'avatar_url' => false,
		);

		// Check for a tag reference and store it.
		if ( isset( $payload->ref ) ) {
			$deployment_data['tag'] = $payload->ref;
		} else {
			die();
		}

		// Check to make sure a tag is being created and not a branch.
		if ( isset( $payload->ref_type ) && 'tag' === $payload->ref_type ) {
			$deployment_data['ref_type'] = $payload->ref_type;
		} else {
			die();
		}

		if ( isset( $payload->sender ) ) {
			$deployment_data['sender'] = $payload->sender->login;
			$deployment_data['avatar_url'] = $payload->sender->avatar_url;
		}

		$deployment = get_post( get_the_ID() );
		$time = time();

		// Build the deployment instance.
		$title = date( 'Y-m-d H:i:s', $time ) . ' | ' . esc_html( $deployment->post_title ) . ' | ' . esc_html( $deployment_data[ 'tag'] ) . ' | ' . esc_html( $deployment_data['sender'] );
		$args = array(
			'post_type' => $this->deploy_instance_slug,
			'post_title' => $title,
			'post_status' => 'publish',
		);
		$instance_id = wp_insert_post( $args );

		add_post_meta( $instance_id, '_deploy_data', $deployment_data, true );

		$deployments = get_post_meta( get_the_ID(), '_deploy_instances', true );
		if ( ! is_array( $deployments ) ) {
			$deployments = array();
		}
		$deployments[ $time ] = absint( $instance_id );
		update_post_meta( get_the_ID(), '_deploy_instances', $deployments );

		$this->_handle_deploy( $deployment_data['tag'], $deployment );

		die();
	}

	/**
	 * Hand deployment details to the relevant script on the production machine. Script
	 * is called as:
	 *
	 * deploy-build.sh 0.0.1 directory-of-theme https://github.com/washingtonstateuniversity/repository.git theme-individual
	 * SCRIPT ^        TAG ^ DIRECTORY ^        REPOSITORY URL ^                                            TYPE ^
	 *
	 * @param string  $tag  Tagged version being deployed.
	 * @param WP_Post $post Object containing the project being deployed.
	 */
	private function _handle_deploy( $tag, $post ) {
		// Tags can only be alphanumeric with dashes and dots
		if ( 0 === preg_match( '|^([a-zA-Z0-9-.])+$|', $tag ) ) {
			die( 'Invalid tag format' );
		}

		$repository_directory = sanitize_key( $post->post_name );

		$deploy_type = get_post_meta( $post->ID, '_deploy_type', true );
		if ( ! in_array( $deploy_type, $this->allowed_deploy_types ) ) {
			$deploy_type = 'theme-individual';
		}

		$repository_url = get_post_meta( $post->ID, '_repository_url', true );
		if ( false === $repository_url || empty( $repository_url ) ) {
			return;
		} else {
			$repository_url = esc_url( $repository_url );
		}

		shell_exec( 'sh /var/repos/wsuwp-deployment/deploy-build.sh ' . $tag . ' ' . $repository_directory . ' ' . $repository_url . ' ' . $deploy_type );
	}

	/**
	 * Add the meta boxes used by our deployment post types.
	 *
	 * @param $post_type
	 * @param $post
	 */
	public function add_meta_boxes( $post_type, $post ) {
		if ( $this->deploy_instance_slug !== $post_type && $this->post_type_slug !== $post_type ) {
			return;
		}

		add_meta_box( 'wsuwp_deploy_repository', 'Repository URL', array( $this, 'display_repository_url' ), $this->post_type_slug, 'normal' );
		add_meta_box( 'wsuwp_deploy_type', 'Deploy Type', array( $this, 'display_deploy_type' ), $this->post_type_slug, 'normal' );
		add_meta_box( 'wsuwp_deploy_instances', 'Deploy Instances', array( $this, 'display_deploy_instances' ), $this->post_type_slug, 'normal' );
		add_meta_box( 'wsuwp_deploy_instance_data', 'Deploy Payload', array( $this, 'display_instance_payload' ), $this->deploy_instance_slug, 'normal' );
	}

	/**
	 * Display a meta box for storing the repository's URL.
	 *
	 * @param WP_Post $post Current post data.
	 */
	public function display_repository_url( $post ) {
		if ( $this->post_type_slug !== $post->post_type ) {
			return;
		}

		$repository_url = get_post_meta( $post->ID, '_repository_url', true );

		if ( $repository_url ) {
			$repository_url = esc_url( $repository_url );
		} else {
			$repository_url = '';
		}

		wp_nonce_field( 'wsuwp-save-repository', '_wsuwp_repository_nonce' );
		?>
		<label for="wsuwp_deploy_repository">Repository URL:</label>
		<input name="wsuwp_deploy_repository" id="wsu_deploy_repository" type="text" value="<?php echo $repository_url; ?>" />
		<?php
	}

	/**
	 * Save a repository URL with a post for use with deployment.
	 *
	 * @param int     $post_id ID of the post being saved.
	 * @param WP_Post $post    Full post object of post being saved.
	 */
	public function save_repository_url( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( $this->post_type_slug !== $post->post_type ) {
			return;
		}

		if ( ! isset( $_POST['_wsuwp_repository_nonce'] ) || ! wp_verify_nonce( $_POST['_wsuwp_repository_nonce'], 'wsuwp-save-repository' ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( isset( $_POST['wsuwp_deploy_repository'] ) && ! empty( trim( $_POST['wsuwp_deploy_repository'] ) ) ) {
			update_post_meta( $post_id, '_repository_url', esc_url_raw( $_POST['wsuwp_deploy_repository'] ) );
		}
	}

	/**
	 * Store the deploy type for a deployment.
	 *
	 * @param WP_Post $post Current post being edited.
	 */
	public function display_deploy_type( $post ) {
		if ( $this->post_type_slug !== $post->post_type ) {
			return;
		}

		$deployment_type = get_post_meta( $post->ID, '_deploy_type', true );

		// Force a deployment type from those we expect.
		if ( ! in_array( $deployment_type, $this->allowed_deploy_types ) ) {
			$deployment_type = 'theme-individual';
		}

		wp_nonce_field( 'wsuwp-save-deploy-type', '_wsuwp_deploy_type_nonce' );
		?>
		<label for="wsuwp_deploy_type">Deployment Type:</label>
		<select name="wsuwp_deploy_type" id="wsuwp_deploy_type">
			<option value="theme-individual" <?php selected( 'theme-individual', $deployment_type, true ); ?>>Individual Theme</option>
			<option value="plugin-individual" <?php selected( 'plugin-individual', $deployment_type, true ); ?>>Individual Plugin</option>
			<option value="build-plugins-public" <?php selected( 'build-plugins-public', $deployment_type, true ); ?>>Build Plugins Public</option>
			<option value="build-plugins-private" <?php selected( 'build-plugins-private', $deployment_type, true ); ?>>Build Plugins Private</option>
			<option value="build-themes-public" <?php selected( 'build-themes-public', $deployment_type, true ); ?>>Build Themes Public</option>
			<option value="build-themes-private" <?php selected( 'build-themes-private', $deployment_type, true ); ?>>Build Themes Private</option>
			<option value="platform" <?php selected( 'platform', $deployment_type, true ); ?>>Platform</option>
		</select>
		<?php
	}

	/**
	 * Save the deployment type to meta for the deployment instance. By default, we'll assume "theme-individual".
	 *
	 * @param int     $post_id ID of the post being saved.
	 * @param WP_Post $post    Post being saved.
	 */
	public function save_deploy_type( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( $this->post_type_slug !== $post->post_type ) {
			return;
		}

		if ( ! isset( $_POST['_wsuwp_deploy_type_nonce'] ) || ! wp_verify_nonce( $_POST['_wsuwp_deploy_type_nonce'], 'wsuwp-save-deploy-type' ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( ! isset( $_POST['wsuwp_deploy_type'] ) || ! in_array( $_POST['wsuwp_deploy_type'], $this->allowed_deploy_types ) ) {
			$deploy_type = 'theme-individual';
		} else {
			$deploy_type = $_POST['wsuwp_deploy_type'];
		}

		update_post_meta( $post_id, '_deploy_type', $deploy_type );

		return;
	}

	/**
	 * Display the deployment instances that have occurred on this
	 * deployment configuration.
	 *
	 * @param $post
	 */
	public function display_deploy_instances( $post ) {
		if ( $this->post_type_slug !== $post->post_type ) {
			return;
		}

		$deployments = get_post_meta( get_the_ID(), '_deploy_instances', true );
		if ( ! empty( $deployments ) ) {
			$deployments = array_reverse( $deployments, true );
			echo '<ul>';
			foreach ( $deployments as $time => $instance_id ) {
				$deploy_data = get_post_meta( $instance_id, '_deploy_data', true );
				if ( ! $deploy_data ) {
					$deploy_tag = 'View';
				} else {
					$deploy_tag = $deploy_data['tag'];
				}

				echo '<li>' . date( 'Y-m-d H:i:s', $time ) . ' | <a href="' . esc_html( admin_url( 'post.php?post=' . absint( $instance_id ) . '&action=edit') ) . '">' . esc_html( $deploy_tag ) . '</a></li>';
			}
			echo '<ul>';
		}
	}

	/**
	 * Display the payload data from a deployment in the instance meta box.
	 * @param $post
	 */
	public function display_instance_payload( $post ) {
		$deploy_data = get_post_meta( $post->ID, '_deploy_data', true );

		if ( isset( $deploy_data['tag'] ) ) {
			echo 'Tag: ' . esc_html( $deploy_data['tag'] ) . '<br />';
		}

		if ( isset( $deploy_data['ref_type'] ) ) {
			echo 'Ref Type: ' . esc_html( $deploy_data['ref_type'] ) . '<br />';
		}

		if ( isset( $deploy_data['sender'] ) ) {
			echo 'Author: ' . esc_html( $deploy_data['sender'] ) . '<br />';
			echo '<img src="' . esc_url( $deploy_data['avatar_url'] ) . '" style="height:50px; width: auto;">';
		}
	}
}
new WSU_Deployment();