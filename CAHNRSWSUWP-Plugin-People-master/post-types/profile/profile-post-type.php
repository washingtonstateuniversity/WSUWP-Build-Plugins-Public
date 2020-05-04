<?php

namespace WSUWP\CAHNRSWSUWP_Plugin_People;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Profile_Post_Type {

	// @var $post_settings Settings for the Save_Post_API to use.
	protected $post_settings = array(
		'_wsuwp_profile_nid' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_external_profile' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_last_name' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_position_title' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_affiliation' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_office' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_phone' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_website' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_email' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_physical_address' => array(
			'sanitize_type' => 'array',
		),
		'_wsuwp_profile_mailing_address' => array(
			'sanitize_type' => 'array',
		),
		'_wsuwp_profile_cv_link' => array(
			'sanitize_type' => 'text',
		),
		'_wsuwp_profile_focus_area' => array(
			'sanitize_type' => 'html',
		),
	);


	public function __construct() {

		register_activation_hook( people_get_plugin_dir_path( '/plugin.php' ), array( $this, 'activate_plugin' ) );

		add_action( 'init', array( $this, 'register_post_type' ) );

		if ( is_admin() ) {

			require_once people_get_plugin_dir_path() . '/vendor/save-post/save-post.php';

			$save_post = new Save_Post_Data( $this->post_settings, array( 'profile' ), 'wsuwp_profile', 'wsuwp_profile_save_post' );

			add_action( 'edit_form_after_title', array( $this, 'render_editor' ) );

		} // End if

	} // End __construct


	public function render_editor( $post ) {

		if ( 'profile' === $post->post_type ) {

			include_once people_get_plugin_dir_path() . '/classes/class-person.php';

			$person = new Person( $post );

			$nid                          = $person->nid;
			$external_profile             = $person->external_profile;
			// Contact Card
			$last_name                    = $person->last_name;
			$last_name_ph                 = $person->last_name_remote;
			$position_title               = $person->position_title;
			$position_title_ph            = $person->position_title_remote;
			$affiliation                  = $person->affiliation;
			$affiliation_ph               = $person->affiliation_remote;
			$office                       = $person->office;
			$office_ph                    = $person->office_remote;
			$phone                        = $person->phone;
			$phone_ph                     = $person->phone_remote;
			$website                      = $person->website;
			$website_ph                   = $person->website_remote;
			$email                        = $person->email;
			$email_ph                     = $person->email_remote;
			$physical_address             = ( is_array( $person->physical_address ) ) ? $person->physical_address : array();
			$physical_address_ph          = ( is_array( $person->physical_address_remote ) ) ? $person->physical_address_remote : array();
			$physical_address_1           = ( ! empty( $physical_address['line_1'] ) ) ? $physical_address['line_1'] : '';
			$physical_address_1_ph        = ( ! empty( $physical_address_ph['line_1'] ) ) ? $physical_address_ph['line_1'] : '';
			$physical_address_2           = ( ! empty( $physical_address['line_2'] ) ) ? $physical_address['line_2'] : '';
			$physical_address_2_ph        = ( ! empty( $physical_address_ph['line_2'] ) ) ? $physical_address_ph['line_2'] : '';
			$physical_address_city        = ( ! empty( $physical_address['city'] ) ) ? $physical_address['city'] : '';
			$physical_address_city_ph     = ( ! empty( $physical_address_ph['city'] ) ) ? $physical_address_ph['city'] : '';
			$physical_address_state       = ( ! empty( $physical_address['state'] ) ) ? $physical_address['state'] : '';
			$physical_address_state_ph    = ( ! empty( $physical_address_ph['state'] ) ) ? $physical_address_ph['state'] : '';
			$physical_address_zip         = ( ! empty( $physical_address['zip'] ) ) ? $physical_address['zip'] : '';
			$physical_address_zip_ph      = ( ! empty( $physical_address_ph['zip'] ) ) ? $physical_address_ph['zip'] : '';
			$mailing_address              = ( is_array( $person->mailing_address ) ) ? $person->mailing_address : array();
			$mailing_address_ph           = ( is_array( $person->mailing_address_remote ) ) ? $person->mailing_address_remote : array();
			$mailing_address_1            = ( ! empty( $mailing_address['line_1'] ) ) ? $mailing_address['line_1'] : '';
			$mailing_address_1_ph         = ( ! empty( $mailing_address_ph['line_1'] ) ) ? $mailing_address_ph['line_1'] : '';
			$mailing_address_2            = ( ! empty( $mailing_address['line_2'] ) ) ? $mailing_address['line_2'] : '';
			$mailing_address_2_ph         = ( ! empty( $mailing_address_ph['line_2'] ) ) ? $mailing_address_ph['line_2'] : '';
			$mailing_address_city         = ( ! empty( $mailing_address['city'] ) ) ? $mailing_address['city'] : '';
			$mailing_address_city_ph      = ( ! empty( $mailing_address_ph['city'] ) ) ? $mailing_address_ph['city'] : '';
			$mailing_address_state        = ( ! empty( $mailing_address['state'] ) ) ? $mailing_address['state'] : '';
			$mailing_address_state_ph     = ( ! empty( $mailing_address_ph['state'] ) ) ? $mailing_address_ph['state'] : '';
			$mailing_address_zip          = ( ! empty( $mailing_address['zip'] ) ) ? $mailing_address['zip'] : '';
			$mailing_address_zip_ph       = ( ! empty( $mailing_address_ph['zip'] ) ) ? $mailing_address_ph['zip'] : '';
			// Education
			$cv_link                      = $person->cv_link;
			$cv_link_ph                   = $person->cv_link_remote;
			$focus_area                   = $person->focus_area;
			$focus_area_remote            = $person->focus_area_remote;

			// Add nonce field to metabox.
			wp_nonce_field( 'wsuwp_profile_save_post', 'wsuwp_profile' );

			include __DIR__ . '/displays/profile-editor.php';

		} // End if

	} // End render_editor



	public function activate_plugin() {

		$this->register_post_type();

		flush_rewrite_rules();

	} // End activate_plugin


	public function register_post_type() {

		$labels = array(
			'name'               => _x( 'profiles', 'post type general name', 'cahnrswsuwp-plugin-people' ),
			'singular_name'      => _x( 'profile', 'post type singular name', 'cahnrswsuwp-plugin-people' ),
			'menu_name'          => _x( 'profiles', 'admin menu', 'cahnrswsuwp-plugin-people' ),
			'name_admin_bar'     => _x( 'profile', 'add new on admin bar', 'cahnrswsuwp-plugin-people' ),
			'add_new'            => _x( 'Add New', 'profile', 'cahnrswsuwp-plugin-people' ),
			'add_new_item'       => __( 'Add New profile', 'cahnrswsuwp-plugin-people' ),
			'new_item'           => __( 'New profile', 'cahnrswsuwp-plugin-people' ),
			'edit_item'          => __( 'Edit profile', 'cahnrswsuwp-plugin-people' ),
			'view_item'          => __( 'View profile', 'cahnrswsuwp-plugin-people' ),
			'all_items'          => __( 'All profiles', 'cahnrswsuwp-plugin-people' ),
			'search_items'       => __( 'Search profiles', 'cahnrswsuwp-plugin-people' ),
			'parent_item_colon'  => __( 'Parent profiles:', 'cahnrswsuwp-plugin-people' ),
			'not_found'          => __( 'No profiles found.', 'cahnrswsuwp-plugin-people' ),
			'not_found_in_trash' => __( 'No profiles found in Trash.', 'cahnrswsuwp-plugin-people' ),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => __( 'Description.', 'cahnrswsuwp-plugin-people' ),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'has_archive'         => true,
			'exclude_from_search' => true,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
			'show_in_rest'        => true,
			'taxonomies'          => array( 'category', 'post_tag' ),
		);

		register_post_type( 'profile', $args );

	} // End register_post_type


} // End Profile_Post_Type

$cahnrswsuwp_profile_post_type = new Profile_Post_Type();
