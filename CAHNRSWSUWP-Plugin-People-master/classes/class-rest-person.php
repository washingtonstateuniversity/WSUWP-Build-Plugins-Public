<?php

namespace WSUWP\CAHNRSWSUWP_Plugin_People;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class REST_Person extends Person {

	/**
	 * Properties for structured to match WP REST API response
	 */

	public $taxonomy_terms    = array();
	public $photos            = array();
	public $display_photo     = '';
	public $working_titles    = array();
	public $display_title     = '';
	public $office_alt        = '';
	public $address_alt       = '';
	public $email_alt         = '';
	public $phone_alt         = '';
	public $content           = '';
	public $title             = '';
	public $profile_photo     = '';


	public function set_by_post_id( $post_id ) {

		parent::set_by_post_id( $post_id );

		$this->content = new \stdClass();
		$this->title   = new \stdClass();

		$this->photos            = array();
		$this->display_photo     = $this->profile_image;
		$this->profile_photo     = $this->profile_image;
		$this->working_titles    = array( $this->position_title );
		$this->display_title     = '';
		$this->office_alt        = $this->office;
		$this->address_alt       = '';
		$this->email_alt         = $this->email;
		$this->phone_alt         = $this->phone;
		$this->content->rendered = $this->bio;
		$this->title->rendered   = $this->display_name;
		$this->display_bio       = true;

	} // End set_by_post_id


} // End Rest_Person
