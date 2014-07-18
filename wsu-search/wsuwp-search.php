<?php
/*
Plugin Name: WSU Search
Version: 0.2.0
Plugin URI: http://web.wsu.edu
Description: Connects to Search
Author: washingtonstateuniversity, jeremyfelt
Author URI: http://web.wsu.edu
*/

/**
 * Class WSU_Search
 */
class WSU_Search {

	/**
	 * @var string The base URL used to add pages from WordPress to our index.
	 */
	var $index_api_url = 'http://134.121.140.161:9200/wsu-web/page/';

	/**
	 * Setup hooks.
	 */
	public function __construct() {
		// Use a different index for pages saved during local development.
		if ( defined( 'WSU_LOCAL_CONFIG' ) && true === WSU_LOCAL_CONFIG ) {
			$this->index_api_url = 'http://134.121.140.161:9200/wsu-local-dev/page/';
		}

		add_action( 'transition_post_status', array( $this, 'save_post' ), 10, 3 );
		add_action( 'transition_post_status', array( $this, 'delete_post' ), 10, 3 );
	}

	/**
	 * When a post is saved, ensure that the most recent version is updated in the index. If this
	 * does not yet exist in the index, then create the document and log the generated UUID.
	 *
	 * @param string  $new_status Post status being saved.
	 * @param string  $old_status Previous post status.
	 * @param WP_Post $post       The entire post object.
	 *
	 * @return null
	 */
	public function save_post( $new_status, $old_status, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return NULL;
		}

		if ( 'publish' !== $new_status ) {
			return NULL;
		}

		// HTTP request arguments.
		$args = array();
		// Data to be sent as JSON to Elasticsearch.
		$data = array();

		$search_id = get_post_meta( $post->ID, '_wsusearch_doc_id', true );

		// If this document already has an ID, we'll PUT to update it. If not, we'll POST a new document.
		if ( $search_id ) {
			$args['method'] = 'PUT';
			$this->index_api_url .= $this->_sanitize_es_id( $search_id );
		} else {
			$args['method'] = 'POST';
		}

		$data['title'] = $post->post_title;
		$data['date'] = $post->post_date;
		$data['modified'] = $post->post_modified;
		$data['content'] = $post->post_content;
		$data['url'] = get_permalink( $post->ID );
		$data['post_type'] = $post->post_type;

		// Information about the site and network this came from.
		$data['site_id'] = get_current_blog_id();

		// Store the hostname - e.g. home.wsu.edu - as a field.
		$home_url = parse_url( trailingslashit( get_home_url() ) );
		$data['hostname'] = $home_url['host'];
		$data['site_url'] = $home_url['host'];

		// Only attach path if it isn't empty.
		if ( '/' !== $home_url['path'] ) {
			$data['site_url'] .= $home_url['path'];
		}

		if ( function_exists( 'wsuwp_get_current_network' ) ) {
			$data['network_id'] = intval( wsuwp_get_current_network()->id );
		}

		// Map each registered public taxonomy to the Elasticsearch document.
		$taxonomies = get_taxonomies( array( 'public' => true ) );

		foreach ( $taxonomies as $taxonomy ) {
			$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'slugs' ) );
			if ( ! is_wp_error( $post_terms ) ) {
				if ( 'post_tag' === $taxonomy ) {
					$data['university_tag'] = $post_terms;
				} elseif( 'wsuwp_university_category' === $taxonomy ) {
					$data['university_category'] = $post_terms;
				} elseif( 'wsuwp_university_location' === $taxonomy ) {
					$data['university_location'] = $post_terms;
				} elseif( 'category' === $taxonomy ) {
					$data['site_category'] = $post_terms;
				} else {
					$data[ $taxonomy ] = $post_terms;
				}
			}
		}

		$args['body'] = json_encode( $data );

		$response = wp_remote_post( $this->index_api_url, $args );
		$response = wp_remote_retrieve_body( $response );

		if ( ! empty( $response ) ) {
			$response_data = json_decode( $response );
			update_post_meta( $post->ID, '_wsusearch_doc_id', $this->_sanitize_es_id( $response_data->_id ) );
		}
	}

	/**
	 * When a post is saved, delete it from the index if the end post status is something other
	 * than 'publish'.
	 *
	 * @param string  $new_status Post status being saved.
	 * @param string  $old_status Previous post status.
	 * @param WP_Post $post       The entire post object.
	 *
	 * @return null
	 */
	public function delete_post( $new_status, $old_status, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return NULL;
		}

		// This document should be saving to a post status other than publish from a post status of publish.
		if ( 'publish' === $new_status || 'publish' !== $old_status ) {
			return NULL;
		}

		$search_id = get_post_meta( $post->ID, '_wsusearch_doc_id', true );

		// This document has not yet been saved, no need to delete.
		if ( false === $search_id ) {
			return NULL;
		}

		$this->index_api_url .= $this->_sanitize_es_id( $search_id );

		// Make a request to delete the existing document from Elasticsearch.
		$response = wp_remote_request( $this->index_api_url, array( 'method' => 'DELETE' ) );

		if ( ! is_wp_error( $response ) ) {
			delete_post_meta( $post->ID, '_wsusearch_doc_id' );
		}
	}

	/**
	 * Sanitize the key returned from Elasticsearch. It should be a-z, A-Z, -, and _ only.
	 *
	 * @param string $id UUID returned from ES, or currently in use for a page.
	 *
	 * @return string sanitized string.
	 */
	private function _sanitize_es_id( $id ) {
		return preg_replace( '/[^a-zA-Z0-9_\-]/', '', $id );
	}
}
new WSU_Search();