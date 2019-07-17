<?php
/**
 * Defines the locations the `Tribe__Context` class should look up.
 *
 * The location definitions are moved here to avoid burdening the `Tribe__Context` class with a long array definition
 * that would be loaded upfront every time the `Tribe__Context` class file is loaded. Since locations will be required
 * only when the Context is built moving them here is a small optimization.
 * This file is meant to be included by the `Tribe__Context::populate_locations` method.
 *
 * @since 4.9.11
 */

return [
	'posts_per_page' => [
		'read'  => [
			Tribe__Context::REQUEST_VAR  => 'posts_per_page',
			Tribe__Context::TRIBE_OPTION => [ 'posts_per_page', 'postsPerPage' ],
			Tribe__Context::OPTION       => 'posts_per_page',
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'posts_per_page',
		],
	],
	'is_main_query'  => [
		'read'  => [
			Tribe__Context::FUNC => static function () {
				global $wp_query;

				return $wp_query->is_main_query();
			},
		],
		'write' => [
			Tribe__Context::FUNC => static function () {
				global $wp_query, $wp_the_query;
				$wp_the_query = $wp_query;
			},
		],
	],
	'paged'          => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => [ 'paged', 'page' ],
			Tribe__Context::QUERY_VAR   => [ 'paged', 'page' ],
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'paged',
			Tribe__Context::QUERY_VAR   => 'paged',
		],
	],
	'page'           => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => [ 'page', 'paged' ],
			Tribe__Context::QUERY_VAR   => [ 'page', 'paged' ],
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => 'page',
			Tribe__Context::QUERY_VAR   => 'page',
		],
	],
	'name'           => [
		'read'  => [
			Tribe__Context::REQUEST_VAR => [ 'name', 'post_name' ],
			Tribe__Context::WP_PARSED   => [ 'name', 'post_name' ],
			Tribe__Context::QUERY_VAR   => [ 'name', 'post_name' ],
		],
		'write' => [
			Tribe__Context::REQUEST_VAR => [ 'name', 'post_name' ],
			Tribe__Context::QUERY_VAR   => [ 'name', 'post_name' ],
		],
	],
];
