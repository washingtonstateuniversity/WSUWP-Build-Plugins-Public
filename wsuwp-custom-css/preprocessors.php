<?php

/**
 * CSS preprocessor registration.
 *
 * To add a new preprocessor (or replace an existing one), hook into the
 * wsu_custom_css_preprocessors filter and add an entry to the array
 * that is passed in.
 *
 * Format is:
 * $preprocessors[ UNIQUE_KEY ] => array( 'name' => 'Processor name', 'callback' => [processing function] );
 *
 * The callback function accepts a single string argument (non-CSS markup) and returns a string (CSS).
 *
 * @param array $preprocessors The list of preprocessors added thus far.
 * @return array
 */

function wsu_register_css_preprocessors( $preprocessors ) {
	$preprocessors['less'] = array(
		'name' => 'LESS',
		'callback' => 'wsu_less_css_preprocess'
	);

	$preprocessors['sass'] = array(
		'name' => 'Sass (SCSS Syntax)',
		'callback' => 'wsu_sass_css_preprocess'
	);

	return $preprocessors;
}

add_filter( 'wsu_custom_css_preprocessors', 'wsu_register_css_preprocessors' );

function wsu_less_css_preprocess( $less ) {
	require_once( dirname( __FILE__ ) . '/preprocessors/lessc.inc.php' );

	$compiler = new lessc();

	try {
		return $compiler->compile( $less );
	} catch ( Exception $e ) {
		return $less;
	}
}

function wsu_sass_css_preprocess( $sass ) {
	require_once( dirname( __FILE__ ) . '/preprocessors/scss.inc.php' );

	$compiler = new scssc();

	try {
		return $compiler->compile( $sass );
	} catch ( Exception $e ) {
		return $sass;
	}
}