<?php
/**
 * Handles hooking all the actions and filters used by the module.
 *
 * To remove a filter:
 * remove_filter( 'some_filter', [ tribe( Tribe\Events\Filterbar\Views\V2_1\Customizer\Hooks::class ), 'some_filtering_method' ] );
 * remove_filter( 'some_filter', [ tribe( 'views.v2.customizer.filters' ), 'some_filtering_method' ] );
 *
 * To remove an action:
 * remove_action( 'some_action', [ tribe( Tribe\Events\Filterbar\Views\V2_1\Customizer\Hooks::class ), 'some_method' ] );
 * remove_action( 'some_action', [ tribe( 'views.v2.customizer.hooks' ), 'some_method' ] );
 *
 * @since 5.1.4
 *
 * @package Tribe\Events\Filterbar\Views\V2_1\Customizer
 */

namespace Tribe\Events\Filterbar\Views\V2_1\Customizer;

use Tribe\Events\Filterbar\Views\V2_1\Customizer\Section\Global_Elements;

/**
 * Class Hooks
 *
 * @since 5.1.4
 *
 * @package Tribe\Events\Filterbar\Views\V2_1\Customizer
 */
class Hooks extends \tad_DI52_ServiceProvider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since 5.1.4
	 */
	public function register() {
		$this->add_filters();
	}

	/**
	 * Adds the filters required by each Filter bar Views v2_1 component.
	 *
	 * @since 5.1.4
	 */
	public function add_filters() {
		add_filter( 'tribe_customizer_section_global_elements_css_template', [ $this, 'filter_global_elements_css_template'], 10, 2 );
	}

	/**
	 * Filter the output CSS for the events bar customizer section.
	 *
	 * @since 5.1.4
	 *
	 * @param string $arguments The existing CSS string.
	 * @param mixed  $section   The section instance we are dealing with (Events_Bar).
	 */
	public function filter_global_elements_css_template( $arguments, $section ) {
		return $this->container->make( Global_Elements::class )->filter_global_elements_css_template( $arguments, $section );
	}

}
