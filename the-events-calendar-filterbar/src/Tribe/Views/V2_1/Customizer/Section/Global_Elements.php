<?php
/**
 * The Events Calendar Customizer Section Class
 * Global Elements
 *
 * @since 5.2.0
 */

namespace Tribe\Events\Filterbar\Views\V2_1\Customizer\Section;

/**
 * Month View
 *
 * @since 5.2.0
 */
class Global_Elements {

	public function filter_global_elements_css_template( $css_template, $section ) {
		if (
			$section->should_include_setting_css( $section->ID, 'background_color_choice' )
			&& $section->should_include_setting_css( $section->ID, 'background_color' )
		) {
			// Nav background overrides
			$css_template .= '
				.tribe-filter-bar .tribe-filter-bar__filters-slider-nav--overflow-start:before {
					background: linear-gradient(-90deg, transparent 15%, <%= global_elements.background_color %> 70%);
				}

				.tribe-filter-bar .tribe-filter-bar__filters-slider-nav--overflow-end:after {
					background: linear-gradient(90deg, transparent 15%, <%= global_elements.background_color %> 70%);
				}
			';
		}

		return $css_template;
	}
}
