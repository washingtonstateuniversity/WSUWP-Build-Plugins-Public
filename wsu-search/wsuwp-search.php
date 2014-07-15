<?php
/*
Plugin Name: WSU Search
Version: 0.0.1
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
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}

	/**
	 * When a post is saved, ensure that the most recent version is updated in the index. If this
	 * does not yet exist in the index, then create the document and log the generated UUID.
	 *
	 * @param int     $post_id The ID of the post being saved.
	 * @param WP_Post $post    The entire post object.
	 *
	 * @return null
	 */
	public function save_post( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return NULL;
		}

		if ( in_array( $post->post_status, array( 'inherit', 'auto-draft' ) ) ) {
			return NULL;
		}

		// HTTP request arguments.
		$args = array();
		// Data to be sent as JSON to Elasticsearch.
		$data = array();

		$search_id = get_post_meta( $post_id, '_wsusearch_doc_id', true );

		// If this document already has an ID, we'll PUT to update it. If not, we'll POST a new document.
		if ( $search_id ) {
			$args['method'] = 'PUT';
			$this->index_api_url .= sanitize_key( $search_id );
		} else {
			$args['method'] = 'POST';
		}

		$data['title'] = $post->post_title;
		$data['date'] = $post->post_date;
		$data['content'] = $post->post_content;
		$data['url'] = get_permalink( $post_id );

		// Information about the site and network this came from.
		$data['site_id'] = get_current_blog_id();

		if ( function_exists( 'wsuwp_get_current_network' ) ) {
			$data['network_id'] = wsuwp_get_current_network()->id;
		}

		$args['body'] = json_encode( $data );

		$response = wp_remote_post( $this->index_api_url, $args );
		$response = wp_remote_retrieve_body( $response );

		if ( ! empty( $response ) ) {
			$response_data = json_decode( $response );
			update_post_meta( $post_id, '_wsusearch_doc_id', sanitize_key( $response_data->_id ) );
		}
	}
}
new WSU_Search();