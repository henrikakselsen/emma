<?php
/**
 * Layers Pro Widget Base Class
 *
 * This file is used to register the base layers widget Class
 *
 * @package Layers
 * @since Layers 1.0.0
 */

function layers_storekit_base_widget_init() {

	if( !class_exists( 'Layers_Widget' ) ) return;

	if( !class_exists( 'Layers_Storekit_Widget' ) ) {
		
		class Layers_Storekit_Widget extends Layers_Widget {
			
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
			
		}
	}
}
add_action( 'widgets_init', 'layers_storekit_base_widget_init', 40 );
