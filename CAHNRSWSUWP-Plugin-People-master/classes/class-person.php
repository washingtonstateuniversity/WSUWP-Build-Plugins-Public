<?php

namespace WSUWP\CAHNRSWSUWP_Plugin_People;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Person {

	public $nid                             = '';
	public $nid_remote                      = '';
	public $external_profile                = '';
	// Contact Card
	public $affiliation                     = '';
	public $affiliation_remote              = '';
	public $display_name                    = '';
	public $last_name                       = '';
	public $last_name_remote                = '';
	public $profile_image                   = '';
	public $position_title                  = '';
	public $position_title_remote           = '';
	public $office                          = '';
	public $office_remote                   = '';
	public $degree                          = array();
	public $phone                           = '';
	public $phone_remote                    = '';
	public $website                         = '';
	public $website_remote                  = '';
	public $email                           = '';
	public $email_remote                    = '';
	public $physical_address                = '';
	public $physical_address_remote         = '';
	public $mailing_address                 = '';
	public $mailing_address_remote          = '';
	// Education
	public $cv_link                         = '';
	public $cv_link_remote                  = '';
	public $focus_area                      = '';
	public $focus_area_remote               = '';

	public $bio                             = '';


	public function __construct( $post = false ) {

		if ( $post ) {

			if ( is_numeric( $post ) ) {

				$this->set_by_post_id( $post );

			} elseif ( isset( $post->ID ) ) {

				$this->set_by_post_id( $post->ID );

			} // End if
		} // End if

	} // End __construct


	public function set_by_post_id( $post_id ) {

		$this->nid                             = get_post_meta( $post_id, '_wsuwp_profile_nid', true );
		$this->external_profile                = get_post_meta( $post_id, '_wsuwp_profile_external_profile', true );
		// Contact Card
		$this->affiliation                     = get_post_meta( $post_id, '_wsuwp_profile_affiliation', true );
		$this->display_name                    = get_the_title( $post_id );
		$this->last_name                       = get_post_meta( $post_id, '_wsuwp_profile_last_name', true );
		$this->profile_image                   = $this->get_local_profile_image( $post_id );
		$this->position_title                  = get_post_meta( $post_id, '_wsuwp_profile_position_title', true );
		$this->office                          = get_post_meta( $post_id, '_wsuwp_profile_office', true );
		$this->phone                           = get_post_meta( $post_id, '_wsuwp_profile_phone', true );
		$this->website                         = get_post_meta( $post_id, '_wsuwp_profile_website', true );
		$this->email                           = get_post_meta( $post_id, '_wsuwp_profile_email', true );
		$this->physical_address                = $this->get_local_physical_address( $post_id );
		$this->mailing_address                 = $this->get_local_mailing_address( $post_id );
		// Education
		$this->cv_link                         = get_post_meta( $post_id, '_wsuwp_profile_cv_link', true );
		$this->focus_area                      = get_post_meta( $post_id, '_wsuwp_profile_focus_area', true );
		$this->bio                             = apply_filters( 'the_content', get_the_content( $post_id ) );

	} // End set_by_post_id


	protected function get_local_profile_image( $post_id ) {

		$profile_image = '';

		if ( has_post_thumbnail( $post_id ) ) {

			$profile_image_url = get_the_post_thumbnail_url( $post_id, 'medium' );

			if ( $profile_image_url ) {

				$profile_image = $profile_image_url;

			} // End if
		} else {

			$profile_image = people_get_plugin_url() . '/images/person-placeholder.png';

		}// End if

		return $profile_image;

	} // End get_local_profile_image


	protected function get_local_physical_address( $post_id ) {

		$physical_address = array();

		$address = get_post_meta( $post_id, '_wsuwp_profile_physical_address', true );

		if ( is_array( $address ) ) {

			$physical_address['line_1']   = ( ! empty( $address['line_1'] ) ) ? $address['line_1'] : '';
			$physical_address['line_2']   = ( ! empty( $address['line_2'] ) ) ? $address['line_2'] : '';
			$physical_address['city']     = ( ! empty( $address['city'] ) ) ? $address['city'] : '';
			$physical_address['state']    = ( ! empty( $address['state'] ) ) ? $address['state'] : '';
			$physical_address['zip']      = ( ! empty( $address['zip'] ) ) ? $address['zip'] : '';

		} // End if

		return $physical_address;

	} // End get_local_physical_address


	protected function get_local_mailing_address( $post_id ) {

		$mailing_address = array();

		$address = get_post_meta( $post_id, '_wsuwp_profile_mailing_address', true );

		if ( is_array( $address ) ) {

			$mailing_address['line_1']   = ( ! empty( $address['line_1'] ) ) ? $address['line_1'] : '';
			$mailing_address['line_2']   = ( ! empty( $address['line_2'] ) ) ? $address['line_2'] : '';
			$mailing_address['city']     = ( ! empty( $address['city'] ) ) ? $address['city'] : '';
			$mailing_address['state']    = ( ! empty( $address['state'] ) ) ? $address['state'] : '';
			$mailing_address['zip']      = ( ! empty( $address['zip'] ) ) ? $address['zip'] : '';

		} // End if

		return $mailing_address;

	} // End get_local_mailing_address


	public function set_remote( $rest_response ) {

	} // End set_remote

	public function get_nid() {
		return $this->nid;
	}

	public function get_position_title() {
		return ( ! empty( $this->position_title ) ) ? $this->position_title : $this->position_title_remote;
	}

	public function get_office() {
		return ( ! empty( $this->office ) ) ? $this->office : $this->office_remote;
	}

}
