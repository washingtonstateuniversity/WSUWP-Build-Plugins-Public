<?php
/**
 * The Events Calendar Customizer Section Class
 * Events Bar
 *
 * @since 5.1.4
 */

namespace Tribe\Events\Filterbar\Views\V2_1\Customizer\Section;

/**
 * Class Events_Bar
 *
 * @since 5.1.4
 */
class Events_Bar {
	/**
	 * Add CSS based for the new Events Bar section.
	 *
	 * @since 5.1.4
	 *
	 * @param string $css_template The existing CSS.
	 * @param mixed $section       The section instance we are dealing with (Events_Bar).
	 *
	 * @return string $css_template The amended CSS.
	 */
	public function filter_events_bar_css_template ( $css_template, $section ) {

		if ( $section->should_include_setting_css( 'events_bar_border_color_choice' ) ) {
			$css_template .= "
				.tribe-common--breakpoint-medium.tribe-events--filter-bar-horizontal.tribe-events .tribe-events-header--has-event-search .tribe-events-c-events-bar__filter-button-container {
					border-color: <%= tec_events_bar.events_bar_border_color %>;
				}
			";
		}

		if ( $section->should_include_setting_css( 'events_bar_text_color' ) ) {
			$css_template .= "
				.tribe-events .tribe-events-header__events-bar .tribe-events-c-events-bar__filter-button-text {
					color: <%= tec_events_bar.events_bar_text_color %>;
				}
			";
		}

		if ( $section->should_include_setting_css( 'events_bar_icon_color_choice' ) ) {
			if ( 'custom' === $section->get_option( 'events_bar_icon_color_choice' ) ) {
				$icon_color = "tec_events_bar.events_bar_icon_color";
			} elseif (
				'accent' === $section->get_option( 'events_bar_icon_color_choice' )
				&& $section->should_include_setting_css( 'accent_color', 'global_elements' )
			) {
				$icon_color = "global_elements.accent_color";
			}

			if ( ! empty( $icon_color ) ) {
				$css_template .= "
					.tribe-events .tribe-events-c-events-bar__filter-button-icon path {
						fill: <%= {$icon_color} %>;
					}
				";
			}
		}

		return $css_template;
	}
}
