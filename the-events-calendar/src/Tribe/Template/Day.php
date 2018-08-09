<?php
/**
 * @for     Day Template
 * This file contains hooks and functions required to set up the day view.
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__Events__Template__Day' ) ) {
	class Tribe__Events__Template__Day extends Tribe__Events__Template_Factory {

		protected $body_class = 'tribe-events-day';
		protected $asset_packages = array();

		const AJAX_HOOK = 'tribe_event_day';

		/**
		 * The path to the template file used for the view.
		 * This value is used in Shortcodes/Tribe_Events.php to
		 * locate the correct template file for each shortcode
		 * view.
		 *
		 * @var string
		 */
		public $view_path = 'day/content';


		/**
		 * Set up hooks for this template
		 *
		 **/
		public function hooks() {

			parent::hooks();

			tribe_asset_enqueue( 'tribe-events-ajax-day' );

			add_filter( 'tribe_get_ical_link', array( $this, 'ical_link' ), 20, 1 );
			add_filter( 'tribe_events_header_attributes', array( $this, 'header_attributes' ) );
		}

		/**
		 * Add header attributes for day view
		 *
		 * @return string
		 **/
		public function header_attributes( $attrs ) {

			if ( ! $wp_query = tribe_get_global_query_object() ) {
				return;
			}

			$current_day = $wp_query->get( 'start_date' );

			$attrs['data-view']    = 'day';
			$attrs['data-baseurl'] = tribe_get_day_link( $current_day );
			$attrs['data-date']    = date( 'Y-m-d', strtotime( $current_day ) );
			$attrs['data-header']  = date( tribe_get_date_format( true ), strtotime( $current_day ) );

			return $attrs;
		}

		/**
		 * Get the title for day view
		 * @param      $title
		 * @param null $sep
		 *
		 * @return string
		 */
		protected function get_title( $original_title, $sep = null ) {
			$new_title = parent::get_title( $original_title, $sep );
			if ( has_filter( 'tribe_events_day_view_title' ) ) {
				_deprecated_function( "The 'tribe_events_day_view_title' filter", '3.8', " the 'tribe_get_events_title' filter" );
				$title_date = date_i18n( tribe_get_date_format( true ), strtotime( get_query_var( 'eventDate' ) ) );
				$new_title  = apply_filters( 'tribe_events_day_view_title', $new_title, $sep, $title_date );
			}
			return $new_title;
		}


		/**
		 * Get the link to download the ical version of day view
		 * @param $link
		 *
		 * @return string
		 */
		public function ical_link( $link ) {
			if ( ! $wp_query = tribe_get_global_query_object() ) {
				return;
			}

			$day = $wp_query->get( 'start_date' );

			return trailingslashit( esc_url( trailingslashit( tribe_get_day_link( $day ) ) . '?ical=1' ) );
		}

		/**
		 * Organize and reorder the events posts according to time slot
		 *
		 **/
		public function setup_view() {
			$wp_query = tribe_get_global_query_object();

			$time_format = apply_filters( 'tribe_events_day_timeslot_format', get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT ) );

			if ( $wp_query->have_posts() ) {
				$unsorted_posts = $wp_query->posts;
				foreach ( $unsorted_posts as &$post ) {
					if ( tribe_event_is_all_day( $post->ID ) ) {
						$post->timeslot = esc_html__( 'All Day', 'the-events-calendar' );
					} else {
						if ( strtotime( tribe_get_start_date( $post->ID, true, Tribe__Date_Utils::DBDATETIMEFORMAT ) ) < strtotime( $wp_query->get( 'start_date' ) ) ) {
							$post->timeslot = esc_html__( 'Ongoing', 'the-events-calendar' );
						} else {
							$post->timeslot = tribe_get_start_date( $post, false, $time_format );
						}
					}
				}
				unset( $post );

				// Make sure All Day events come first
				$all_day = array();
				$ongoing = array();
				$hourly  = array();
				foreach ( $unsorted_posts as $i => $post ) {
					if ( $post->timeslot == esc_html__( 'All Day', 'the-events-calendar' ) ) {
						$all_day[ $i ] = $post;
					} else {
						if ( $post->timeslot == esc_html__( 'Ongoing', 'the-events-calendar' ) ) {
							$ongoing[ $i ] = $post;
						} else {
							$hourly[ $i ] = $post;
						}
					}
				}

				$wp_query->posts = array_values( $all_day + $ongoing + $hourly );
				$wp_query->rewind_posts();
			}
		}

		protected function nothing_found_notice() {
			$events_label_plural_lowercase = tribe_get_event_label_plural_lowercase();
			list( $search_term, $tax_term, $geographic_term ) = $this->get_search_terms();

			if ( empty( $search_term ) && empty( $geographic_term ) && ! empty( $tax_term ) ) {
				Tribe__Notices::set_notice( 'events-not-found', sprintf( esc_html__( 'No matching %1$s listed under %2$s scheduled for %3$s. Please try another day.', 'the-events-calendar' ), $events_label_plural_lowercase, $tax_term, '<strong>' . date_i18n( tribe_get_date_format( true ), strtotime( get_query_var( 'eventDate' ) ) ) . '</strong>' ) );
			} elseif ( empty( $search_term ) && empty( $geographic_term ) ) {
				Tribe__Notices::set_notice( 'events-not-found', sprintf( esc_html__( 'No %1$s scheduled for %2$s. Please try another day.', 'the-events-calendar' ), $events_label_plural_lowercase, '<strong>' . date_i18n( tribe_get_date_format( true ), strtotime( get_query_var( 'eventDate' ) ) ) . '</strong>' ) );
			} else {
				parent::nothing_found_notice();
			}
		}

		/**
		 * AJAX handler for tribe_event_day (dayview navigation)
		 * This loads up the day view shard with all the appropriate events for the day
		 *
		 */
		public function ajax_response() {
			if ( isset( $_POST['eventDate'] ) && $_POST['eventDate'] ) {

				Tribe__Events__Query::init();

				$post_status = array( 'publish' );
				if ( is_user_logged_in() ) {
					$post_status[] = 'private';
				}

				$args = array(
					'post_status'  => $post_status,
					'eventDate'    => $_POST['eventDate'],
					'eventDisplay' => 'day',
					'featured'     => tribe( 'tec.featured_events' )->featured_events_requested(),
				);

				Tribe__Events__Main::instance()->displaying = 'day';

				if ( isset( $_POST['tribe_event_category'] ) ) {
					$args[ Tribe__Events__Main::TAXONOMY ] = $_POST['tribe_event_category'];
				}

				$query = tribe_get_events( $args, true );

				global $post;
				global $wp_query;

				$wp_query = $query;

				add_filter( 'tribe_is_day', '__return_true' ); // simplest way to declare that this is a day view

				ob_start();
				tribe_get_view( 'day/content' );

				$response = array(
					'html'        => ob_get_clean(),
					'success'     => true,
					'total_count' => $query->found_posts,
					'view'        => 'day',
				);
				apply_filters( 'tribe_events_ajax_response', $response );

				header( 'Content-type: application/json' );
				echo json_encode( $response );
				die();
			}

		}
	}
}
