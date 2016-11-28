<?php
/**
 * Layers Pro Widget Base Class
 *
 * This file is used to register the base layers widget Class
 *
 * @package Layers
 * @since Layers 1.0.0
 */

function layers_pro_base_widget_init() {

	if( !class_exists( 'Layers_Widget' ) ) return;

	if( !class_exists( 'Layers_Pro_Widget' ) ) {
		
		class Layers_Pro_Widget extends Layers_Widget {
			
			/**
			 * TODO: This method should be removed and the dependancy of LP upped to Layers v1.5.4
			 * Helper - takes widget $args['before_widget'], strips out the needed data-attributes,
			 * and returns it as an isolated string. This is enables Partial Widget refresh by
			 * JavascScript in the Customizer preview (Thanks to Weston).
			 */
			function selective_refresh_atts( $args ) {
				
				$before_widget = isset( $args['before_widget'] ) ? $args['before_widget'] : '' ;
				
				preg_match_all(
					'/(data-customize-partial-id|data-customize-partial-type|data-customize-partial-placement-context|data-customize-widget-id)=("[^"]*")/i',
					$before_widget,
					$result
				);
				
				$atts = ( isset( $result[0] ) && is_array( $result[0] ) ) ? implode( $result[0], ' ' ) : '' ;
				
				echo $atts;
			}
			
			/**
			 * TODO: This method should be removed and the dependancy of LP upped to Layers v1.5.7
			 * Helper function to display the repeater title when the Widget first loads.
			 * There's also JS that will update this title as the title input is typed into.
			 *
			 * @param  string $text The existing value of the widget title.
			 * @return string       Formetted title eg ` : Pretty Title...`.
			 */
			public function format_repeater_title( $text ) {
				
				$text = substr( stripslashes( strip_tags( $text ) ), 0 , 50 ); // Shorten the text to a max character width.
				if ( strlen( $text ) > 50 ) $text .= '...'; // Add `...` if the text is over a certain length.
				if ( $text ) $text = ': ' . $text; // Add ` : ` to the beginning.
				
				return $text;
			}
		}
	}
}
add_action( 'widgets_init', 'layers_pro_base_widget_init', 40 );
