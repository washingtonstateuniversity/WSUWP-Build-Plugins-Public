<?php

namespace WSUWP\CAHNRSWSUWP_Plugin_People;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class CAHNRSWSUWP_Content_Syndicate {


	public function __construct() {

		add_filter( 'wsuwp_people_response', array( $this, 'add_local_people' ), 10, 2 );

		add_filter( 'wsuwp_people_item_html', array( $this, 'generate_item_html' ), 1, 4 );

	} // End __construct


	public function add_local_people( $people, $atts ) {

		$local_people = $this->get_local_people( $atts );

		if ( is_array( $people ) && ! empty( $people ) ) {

			foreach ( $people as $index => &$remote_person ) {

				if ( ! isset( $remote_person->profile_photo ) || empty( $remote_person->profile_photo ) ) {

					$remote_person->profile_photo = people_get_plugin_url() . '/images/person-placeholder.png';

				} // End if

				$nid = ( isset( $remote_person->nid ) && ! empty( $remote_person->nid ) ) ? $remote_person->nid : '';

				if ( ! empty( $nid ) ) {

					if ( array_key_exists( $nid, $local_people ) ) {

						$local_person = $local_people[ $nid ];

						$remote_person = $this->merge_people( $remote_person, $local_person );

						unset( $local_people[ $nid ] );

					} // End if
				} // End if
			} // End foreach
		} // End if

		if ( ! empty( $atts['nid'] ) ) {

			$nid = $atts['nid'];

			if ( ! empty( $local_people[ $nid ] ) ) {

				$people[] = $local_people[ $nid ];

			}
		} else {

			foreach ( $local_people as $id => $local_person ) {

				$people[] = $local_person;

			} // End $local_person
		} // End if

		return $people;

	} // End add_local_people


	public function merge_people( $remote_person, $local_person ) {

		if ( isset( $local_person->position_title ) && ! empty( $local_person->position_title ) ) {

			$remote_person->working_titles = array( $local_person->position_title );

		} // End if

		if ( isset( $local_person->display_name ) && ! empty( $local_person->display_name ) ) {

			$remote_person->title->rendered = $local_person->display_name;

		} // End if

		if ( isset( $local_person->bio ) && ! empty( $local_person->bio ) ) {

			$remote_person->content->rendered = $local_person->bio;

		} // End if

		return $remote_person;

	} // End merge_people


	public function get_local_people( $atts ) {

		$people = array();

		$args = array(
			'post_type'      => 'profile',
			'posts_per_page' => '-1',
			'post_status'    => 'publish',
		);

		$the_query = new \WP_Query( $args );

		if ( $the_query->have_posts() ) {

			include_once people_get_plugin_dir_path() . '/classes/class-person.php';

			include_once people_get_plugin_dir_path() . '/classes/class-rest-person.php';

			while ( $the_query->have_posts() ) {

				$the_query->the_post();

				$person = new REST_Person( $the_query->post );

				$nid = ( isset( $person->nid ) && ! empty( $person->nid ) ) ? $person->nid : $the_query->post->ID;

				$people[ $nid ] = $person;

			} // End while

			wp_reset_postdata();

		} // End if

		return $people;

	} // End get_local_people


	/**
	 * Generate the HTML used for individual people when called with the shortcode.
	 *
	 * @since 1.0.0 Pulled from WSUWP Content Syndicate
	 *
	 * @param stdClass $person Data returned from the WP REST API.
	 * @param string   $type   The type of output expected.
	 * @param array    $atts   The shortcode attributes.
	 *
	 * @return string The generated HTML for an individual person.
	 */
	public function generate_item_html( $item_html, $person, $type, $atts ) {

		if ( isset( $_GET['debug'] ) ) { return 'test'; }

		if ( 'large-gallery' === $type ) {

			// Determine which fields to display.
			if ( ! empty( $atts['display_fields'] ) ) {
				$display_fields = array_map( 'trim', explode( ',', $atts['display_fields'] ) );
			} else {
				$display_fields = explode( ',', $this->local_extended_atts['display_fields'] );
			}

			// Build out the profile container classes.
			$classes = 'wsuwp-person-container';
			$classes .= ' ' . $person->slug;

			if ( ! empty( $atts['filters'] ) && empty( $atts['nid'] ) && ! empty( $person->taxonomy_terms ) ) {
				foreach ( $person->taxonomy_terms as $taxonomy => $terms ) {
					$prefix = ( 'wsuwp_university_org' === $taxonomy ) ? 'organization' : array_pop( explode( '_', $taxonomy ) );
					foreach ( $terms as $term ) {
						$classes .= ' ' . $prefix . '-' . $term->slug;
					}
				}
			}

			// Cast the collection as an array to account for scenarios
			// where it can sometimes come through as an object.
			$photo_collection = (array) $person->photos;
			$photo = false;

			// Determine the photo size to display.
			// A note about the photo collection:
			// if the uploaded image wasn't big enough to have generated a large or medium size,
			// the full size image is assigned as the value for those keys.
			$photo_size = 'medium';

			// Get the URL of the display photo.
			if ( in_array( 'photo', $display_fields, true ) && ! empty( $photo_collection ) ) {
				if ( ! empty( $person->display_photo ) && isset( $photo_collection[ $person->display_photo ] ) ) {
					$photo = $photo_collection[ $person->display_photo ]->$photo_size;
				} elseif ( isset( $photo_collection[0] ) ) {
					$photo = $photo_collection[0]->$photo_size;
				}
			}

			// Get the legacy profile photo URL if the person's collection is empty.
			if ( ! $photo && isset( $person->profile_photo ) ) {
				$photo = $person->profile_photo;
			}

			// Get the display title(s).
			if ( ! empty( $person->working_titles ) ) {
				if ( ! empty( $person->display_title ) ) {
					$display_titles = explode( ',', $person->display_title );
					foreach ( $display_titles as $display_title ) {
						if ( isset( $person->working_titles[ $display_title ] ) ) {
							$titles[] = $person->working_titles[ $display_title ];
						}
					}
				} else {
					$titles = $person->working_titles;
				}
			} else {
				$titles = array( $person->position_title );
			}

			$office = ( ! empty( $person->office_alt ) ) ? $person->office_alt : $person->office;
			$address = ( ! empty( $person->address_alt ) ) ? $person->address_alt : $person->address;
			$email = ( ! empty( $person->email_alt ) ) ? $person->email_alt : $person->email;
			$phone = ( ! empty( $person->phone_alt ) ) ? $person->phone_alt : $person->phone;

			$website = $person->website;

			// Set the bio if needed.
			// TODO The var $bio is uses and set below for the link. It would be worth while to consolidate these to only set the bio once.

			$bio_fields = array_intersect( array( 'bio', 'bio-unit', 'bio-university' ), $display_fields );

			$about = '';

			// Check which bio to display if has fields or is profile. Default is $person->content->rendered
			if ( ! empty( $bio_fields ) || 'profile' === $atts['output'] ) {

				if ( in_array( 'bio-unit', $display_fields, true ) ) {

					$about = isset( $person->bio_unit ) ? $person->bio_unit : '';

				} elseif ( in_array( 'bio-university', $display_fields, true ) ) {

					$about = isset( $person->bio_university ) ? $person->bio_university : '';

				} else {

					$about = $person->content->rendered;

				} // End if
			} // End if

			$name = $person->title->rendered;

			// Set up profile URL.
			$link = false;

			if ( ! empty( $atts['link'] ) && 'people.wsu.edu' !== $atts['host'] ) {
				if ( 'has-bio' === $atts['link'] ) {

					switch ( $person->display_bio ) {
						case 'university':
							$bio = $person->bio_university;
							break;
						case 'unit':
							$bio = $person->bio_unit;
							break;
						default:
							$bio = $person->content->rendered;
					}
					if ( ! empty( $bio ) ) {
						$link = $person->link;
					}
				} elseif ( 'yes' === $atts['link'] ) {
					$link = $person->link;
				}
			} elseif ( ! empty( $atts['link'] ) && ! empty( $atts['profile_page_url'] ) ) {

				// If has link attr and has profile_page_url -> link to dynamic profile page

				$profile_link = $atts['profile_page_url'] . '?nid=' . $person->nid;

				if ( 'has-bio' === $atts['link'] ) {

					$bio = $person->content->rendered;

					if ( ! empty( $bio ) ) {

						$link = $profile_link;

					} // End if
				} elseif ( 'yes' === $atts['link'] ) {

					$link = $profile_link;

				} // End if
			} // End if

			ob_start();
			?>
			<div class="<?php echo esc_attr( $classes ); ?>">

				<?php if ( $photo && in_array( 'photo', $display_fields, true ) ) { ?>
					<figure class="wsuwp-person-photo" aria-hidden="true" style="background-image:url(<?php echo esc_url( $photo ); ?>);">
						<?php if ( $link ) { ?><a href="<?php echo esc_url( $link ); ?>"><?php } ?>
						<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $person->title->rendered ); ?>" />
						<?php if ( $link ) { ?></a><?php } ?>
					</figure>
				<?php } ?>

				<?php if ( in_array( 'name', $display_fields, true ) ) { ?>
				<div class="wsuwp-person-name">
					<?php if ( $link ) { ?><a href="<?php echo esc_url( $link ); ?>"><?php } ?>
					<?php echo esc_html( $person->title->rendered ); ?>
					<?php if ( $link ) { ?></a><?php } ?>
					</div>
				<?php } ?>

				<?php if ( in_array( 'degree', $display_fields, true ) ) { ?>
					<?php foreach ( $person->degree as $degree ) { ?>
					<div class="wsuwp-person-degree"><?php echo esc_html( $degree ); ?></div>
					<?php } ?>
				<?php } ?>

				<?php if ( in_array( 'title', $display_fields, true ) ) { ?>
					<?php foreach ( $titles as $title ) { ?>
					<div class="wsuwp-person-position"><?php echo esc_html( $title ); ?></div>
					<?php } ?>
				<?php } ?>

				<?php if ( in_array( 'office', $display_fields, true ) ) { ?>
				<div class="wsuwp-person-office"><?php echo esc_html( $office ); ?></div>
				<?php } ?>

				<?php if ( in_array( 'address', $display_fields, true ) ) { ?>
				<div class="wsuwp-person-address"><?php echo esc_html( $address ); ?></div>
				<?php } ?>

				<?php if ( in_array( 'email', $display_fields, true ) ) { ?>
				<div class="wsuwp-person-email">
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				</div>
				<?php } ?>

				<?php if ( in_array( 'phone', $display_fields, true ) ) { ?>
				<div class="wsuwp-person-phone"><?php echo esc_html( $phone ); ?></div>
				<?php } ?>

				<?php if ( in_array( 'website', $display_fields, true ) && ! empty( $person->website ) ) { ?>
				<div class="wsuwp-person-website">
					<a href="<?php echo esc_url( $person->website ); ?>"><?php echo esc_html( $atts['website_link_text'] ); ?></a>
				</div>
				<?php } ?>

				<?php if ( ! empty( $about ) ) { ?>
				<div class="wsuwp-person-bio">
					<?php echo wp_kses_post( $about ); ?>
				</div>
				<?php } ?>

			</div>
			<?php
			$item_html = ob_get_contents();
			ob_end_clean();

		}

		return $item_html;
	}



} // End CAHNRSWSUWP_Content_Syndicate

$cahrnswsuwp_content_syndicate = new CAHNRSWSUWP_Content_Syndicate();
