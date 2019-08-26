<?php
/**
 * The Day View.
 *
 * @package Tribe\Events\Views\V2\Views
 * @since 4.9.2
 */

namespace Tribe\Events\Views\V2\Views;

use Tribe\Events\Views\V2\View;
use Tribe__Events__Main as TEC;
use Tribe__Events__Rewrite as Rewrite;
use Tribe__Utils__Array as Arr;
use Tribe__Date_Utils as Dates;

class Day_View extends View {
	/**
	 * Slug for this view
	 *
	 * @since 4.9.4
	 *
	 * @var string
	 */
	protected $slug = 'day';

	/**
	 * Visibility for this view.
	 *
	 * @since 4.9.4
	 *
	 * @var bool
	 */
	protected $publicly_visible = true;

	/**
	 * {@inheritDoc}
	 */
	public function prev_url( $canonical = false, array $passthru_vars = [] ) {
		$default_date   = 'now';
		$date           = $this->context->get( 'event_date', $default_date );
		$event_date_var = $default_date === $date ? '' : $date;
		$previous_date = date( Dates::DBDATEFORMAT, strtotime( $date . ' -1day' ) );

		$past = tribe_events()->by_args( [
			'on_date'  => $previous_date,
			'posts_per_page' => 1,
			'order'          => 'DESC',
		] );
		$past_posts = $past->get_query()->get_posts();
		$previous_event = reset( $past_posts );

		if ( ! $previous_event ) {
			$past = tribe_events()->by_args( [
				'starts_before'  => $date,
				'posts_per_page' => 1,
				'order'          => 'DESC',
			] );
			$past_posts = $past->get_query()->get_posts();
			$previous_event = reset( $past_posts );

			if ( ! $previous_event ) {
				return '';
			}

			$previous_date = tribe_get_start_date( $previous_event, false, Dates::DBDATEFORMAT );
		}

		$query_args = [ 'eventDate' => $previous_date ];
		$url = remove_query_arg( [ 'tribe-bar-date' ], $this->get_url() );
		$url = add_query_arg( $query_args, $url );

		if ( ! empty( $url ) && $canonical ) {
			$input_url = $url;

			if ( ! empty( $passthru_vars ) ) {
				$input_url = remove_query_arg( array_keys( $passthru_vars ), $url );
			}

			// Make sure the view slug is always set to correctly match rewrites.
			$input_url = add_query_arg( [ 'eventDisplay' => $this->slug ], $input_url );

			$canonical_url = tribe( 'events.rewrite' )->get_clean_url( $input_url );

			if ( ! empty( $passthru_vars ) ) {
				$canonical_url = add_query_arg( $passthru_vars, $canonical_url );
			}

			$url = $canonical_url;
		}

		$url = $this->filter_prev_url( $canonical, $url );

		return $url;
	}

	/**
	 * {@inheritDoc}
	 */
	public function next_url( $canonical = false, array $passthru_vars = [] ) {
		$default_date   = 'today';
		$date           = $this->context->get( 'event_date', $default_date );
		$event_date_var = $default_date === $date ? '' : $date;
		$next_date = date( Dates::DBDATEFORMAT, strtotime( $date . ' 23:59 +1day' ) );

		$future = tribe_events()->by_args( [
			'on_date'  => $next_date,
			'posts_per_page' => 1,
		] );
		$future_posts = $future->get_query()->get_posts();
		$next_event = reset( $future_posts );

		if ( ! $next_event ) {
			$future = tribe_events()->by_args( [
				'starts_after'  => $date . ' 23:59',
				'posts_per_page' => 1,
			] );
			$future_posts = $future->get_query()->get_posts();
			$next_event = reset( $future_posts );

			if ( ! $next_event ) {
				return '';
			}

			$next_date = tribe_get_start_date( $next_event, false, Dates::DBDATEFORMAT );
		}

		$query_args = [ 'eventDate' => $next_date ];
		$url = remove_query_arg( [ 'tribe-bar-date' ], $this->get_url() );
		$url = add_query_arg( $query_args, $url );

		if ( ! empty( $url ) && $canonical ) {
			$input_url = $url;

			if ( ! empty( $passthru_vars ) ) {
				$input_url = remove_query_arg( array_keys( $passthru_vars ), $url );
			}

			// Make sure the view slug is always set to correctly match rewrites.
			$input_url = add_query_arg( [ 'eventDisplay' => $this->slug ], $input_url );

			$canonical_url = tribe( 'events.rewrite' )->get_clean_url( $input_url );

			if ( ! empty( $passthru_vars ) ) {
				$canonical_url = add_query_arg( $passthru_vars, $canonical_url );
			}

			$url = $canonical_url;
		}

		$url = $this->filter_next_url( $canonical, $url );

		return $url;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function setup_repository_args( \Tribe__Context $context = null ) {
		$context = null !== $context ? $context : $this->context;

		$args = parent::setup_repository_args( $context );

		$context_arr = $context->to_array();

		$date = Arr::get( $context_arr, 'event_date', 'now' );
		$event_display = Arr::get( $context_arr, 'event_display_mode', Arr::get( $context_arr, 'event_display' ), 'current' );

		$args['date_overlaps'] = [ tribe_beginning_of_day( $date ), tribe_end_of_day( $date ) ];

		return $args;
	}
}
