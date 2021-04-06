<?php
/**
 * Server-side rendering of the `core/term-description` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/term-description` block on the server.
 *
 * @param array $attributes Block attributes.
 *
 * @return string Returns the filtered post content of the current post.
 */
function gutenberg_render_block_core_term_description( $attributes ) {

	if ( ! is_category() && ! is_tag() && ! is_tax() ) {
		return '';
	}

	$extra_attributes   = ( isset( $attributes['textAlign'] ) )
		? array( 'class' => 'has-text-align-' . $attributes['textAlign'] )
		: array();
	$wrapper_attributes = get_block_wrapper_attributes( $extra_attributes );

	return '<div ' . $wrapper_attributes . '>' . term_description() . '</div>';
}

/**
 * Registers the `core/term-description` block on the server.
 */
function gutenberg_register_block_core_term_description() {
	register_block_type_from_metadata(
		__DIR__ . '/term-description',
		array(
			'render_callback' => 'gutenberg_render_block_core_term_description',
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_term_description', 20 );
