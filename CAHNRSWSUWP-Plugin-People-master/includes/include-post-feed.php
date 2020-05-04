<?php

namespace WSUWP\CAHNRSWSUWP_Plugin_People;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Post_Feed {

	public function __construct() {

		$this->add_filters();

	} // End __construct


	private function add_filters() {

		add_filter( 'core_post_feed_local_item_array', array( $this, 'get_people_item' ), 10, 3 );

	}


	public function get_people_item( $item, $post_id, $settings ) {

		if ( 'profile' === $item['post_type'] ) {

			include_once people_get_plugin_dir_path() . '/classes/class-person.php';

			$person = new Person( $post_id );

			$item['nid']              = $person->nid;
			// Contact Card
			$item['affiliation']     = $person->affiliation;
			$item['display_name']     = $person->display_name;
			$item['last_name']        = $person->last_name;
			$item['profile_image']    = $person->profile_image;
			$item['position_title']   = $person->position_title;
			$item['office']          = $person->office;
			$item['phone']           = $person->phone;
			$item['website']         = $person->website;
			$item['email']            = $person->email;
			$item['physical_address'] = $person->physical_address;
			$item['mailing_address']  = $person->mailing_address;
			// Education
			$item['cv_link']          = $person->cv_link;
			$item['focus_area']       = $person->focus_area;
			$item['bio']              = $person->bio;

		} // End if

		return $item;

	} // End get_people_item


} // End

$include_post_feed = new Post_Feed();