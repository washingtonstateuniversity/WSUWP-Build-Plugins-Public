<?php
/*
Plugin Name: WSU Admin
Plugin URI: http://web.wsu.edu
Description: Customized portions of the admin area of WordPress for Washington State University
Author: washingtonstateuniversity, jeremyfelt
Version: 0.0.1
*/

class WSU_Admin {
	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_filter( 'manage_pages_columns', array( $this, 'add_last_updated_column' ) );
		add_filter( 'manage_posts_columns', array( $this, 'add_last_updated_column' ) );
		add_action( 'manage_pages_custom_column', array( $this, 'last_updated_column_data' ), 10, 2 );
		add_action( 'manage_posts_custom_column', array( $this, 'last_updated_column_data' ), 10, 2 );
	}

	/**
	 * Add a column to posts and pages for Last Updated.
	 *
	 * @param array $columns List of columns.
	 *
	 * @return array Modified list of columns.
	 */
	public function add_last_updated_column( $columns ) {
		$columns = array_merge( $columns, array( 'wsu_last_updated' => 'Last Updated' ) );

		return $columns;
	}

	/**
	 * Display last updated data in our custom posts and page table column.
	 *
	 * @param string $column  Column being output.
	 * @param int    $post_id ID of the post row being output.
	 */
	public function last_updated_column_data( $column, $post_id ) {
		if ( 'wsu_last_updated' !== $column ) {
			return;
		}

		// Retrieve the last revision for this post, which should also be the last updated record.
		$revisions = wp_get_post_revisions( $post_id, array( 'numberposts' => 1 ) );

		foreach ( $revisions as $revision ) {
			echo get_the_author_meta('display_name', $revision->post_author );
			echo '<br>';

			// If within 24 hours, show a human readable version instead
			if ( ( time() - strtotime( $revision->post_date ) ) < DAY_IN_SECONDS ) {
				echo human_time_diff( time(), strtotime( $revision->post_date ) ) . ' ago';
			} else {
				echo date( 'Y/m/d', strtotime( $revision->post_date ) );
			}
			break;
		}
	}
}
new WSU_Admin();