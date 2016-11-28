<?php
/**
 * Pro Widget Filters
 *
 * This file is used to modify the widgets in the Layers.
 *
 * @package Layers
 * @since Layers 1.0
 */

class Layers_Pro_Widget_Filters {

	private static $instance;

	/**
	 * Get Instance creates a singleton class that's cached to stop duplicate instances
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	 * Construct empty on purpose
	 */
	private function __construct() {}

	/**
	 * Init behaves like, and replaces, construct
	 */
	public function init(){

		$this->layers_meta = wp_get_theme( 'layerswp' );

		if ( !version_compare( $this->layers_meta->get( 'Version' ), '1.5.0', '<' ) ) {

			/**
			 * Add Featured-Image-Size to the Feature-Image elements.
			 */

			// Layers
			add_filter( 'layers_design_bar_featuredimage_column_item_elements' , array( $this, 'add_content_featured_image_size' ), 10, 2 );

			/**
			 * Add Heading-Type to the Fonts elements.
			 */

			// Layers
			add_filter( 'layers_design_bar_fonts_column_elements' , array( $this, 'add_heading_type' ), 10, 2 );
			add_filter( 'layers_design_bar_fonts_column_item_elements' , array( $this, 'add_heading_type' ), 10, 2 );
			add_filter( 'layers_design_bar_fonts_slide_item_elements' , array( $this, 'add_heading_type' ), 10, 2 );
			add_filter( 'layers_design_bar_fonts_post_elements', array( $this, 'add_heading_type' ), 10, 2 );
			add_filter( 'layers_design_bar_fonts_map_elements', array( $this, 'add_heading_type' ), 10, 2 );

			// Layers Pro
			add_filter( 'layers_design_bar_fonts_layers-pro-tabs_elements', array( $this, 'add_heading_type' ), 10, 2 );
			add_filter( 'layers_design_bar_fonts_layers-pro-social-icons_elements', array( $this, 'add_heading_type' ), 10, 2 );
			add_filter( 'layers_design_bar_fonts_layers-pro-post-carousel_elements', array( $this, 'add_heading_type' ), 10, 2 );
			add_filter( 'layers_design_bar_fonts_layers-pro-call-to-action_elements', array( $this, 'add_heading_type' ), 10, 2 );
			add_filter( 'layers_design_bar_fonts_layers-pro-accordion_elements', array( $this, 'add_heading_type' ), 10, 2 );

			// Showcase
			add_filter( 'layers_design_bar_fonts_project_elements', array( $this, 'add_heading_type' ), 10, 2 );

			/**
			 * Add Advanced-Button-Styling to the Design-Bar components.
			 */

			// Layers
			add_filter( 'layers_slide_widget_slide_design_bar_components' , array( $this, 'add_buttons_styling' ), 10, 3 );
			add_filter( 'layers_column_widget_column_design_bar_components' , array( $this, 'add_buttons_styling' ), 10, 3 );
			add_filter( 'layers_post_widget_design_bar_components' , array( $this, 'add_buttons_styling' ), 10, 3 );

			// Layers Pro
			add_filter( 'layers_cta_widget_cta_design_bar_components' , array( $this, 'add_buttons_styling' ), 10, 3 );
			add_filter( 'layers_accordion_widget_design_bar_components' , array( $this, 'add_buttons_styling' ), 10, 3 );
			add_filter( 'layers_post_carousel_widget_design_bar_components' , array( $this, 'add_buttons_styling' ), 10, 3 );
			add_filter( 'layers_social_widget_social_design_bar_components' , array( $this, 'add_buttons_styling' ), 10, 3 );

			// Showcase
			add_filter( 'layers_showcase_widget_design_bar_components' , array( $this, 'add_buttons_styling' ), 10, 3 );
		}

		if( !version_compare( $this->layers_meta->get( 'Version' ), '1.5.0', '<' ) ){

			add_filter( 'layers_design_bar_background_slide_item_elements' , array( $this, 'slider_elements' ), 10, 2 );
			add_action( 'layers_before_slider_widget_item_inner' , array( $this, 'slider_video_bg' ), 10, 3 );

			/**
			* Add Parallax to Each Standard Design Bar Background
			**/
			$widget_element_keys = array(
				'column',
				'project',
				'column_item',
				'map',
				'post',
				'slide_item',
				'layers-pro-accordion',
				'layers-pro-call-to-action',
				'layers-pro-post-carousel',
				'layers-pro-post-carousel',
				'layers-pro-social-icons',
				'layers-pro-tabs',
			);

			foreach( $widget_element_keys as $we_key ){
				add_filter( 'layers_design_bar_background_' . $we_key . '_elements' , array( $this, 'parallax_elements' ), 10, 2 );
			}

			$widget_container_keys = array(
				'slider_widget_item',
				'content_widget_container',
				'content_widget_item',
				'contact_widget_container',
				'post_widget_container',
				'post_carousel_widget_container',
				'tabs_widget_container',
				'cta_widget_container',
				'social_widget_container',
				'accordian_widget_container',
				'project_widget_container',
			);

			foreach( $widget_container_keys as $w_key ){
				add_action( 'layers_' . $w_key . '_class' , array( $this, 'widget_parallax_class' ), 10, 3 );
			}

			$widget_advanced_keys = array(
				'column',
				'column_item',
				'project',
				'map',
				'post',
				'slide',
				'layers-pro-accordion',
				'layers-pro-call-to-action',
				'layers-pro-post-carousel',
				'layers-pro-post-carousel',
				'layers-pro-social-icons',
				'layers-pro-tabs',
			);

			if( !version_compare( $this->layers_meta->get( 'Version' ), '1.6.1', '<' ) ) {

				foreach( $widget_advanced_keys as $we_key ){
					add_filter( 'layers_design_bar_advanced_' . $we_key . '_elements' , array( $this, 'mobile_display_elements' ), 10, 2 );
				}

				$widget_advanced_container_keys = array(
					'slider_widget_container',
					'content_widget_container',
					'content_widget_item',
					'contact_widget_container',
					'post_widget_container',
					'post_carousel_widget_container',
					'tabs_widget_container',
					'cta_widget_container',
					'social_widget_container',
					'accordian_widget_container',
					'project_widget_container',
				);

				foreach( $widget_advanced_container_keys as $w_key ){
					add_action( 'layers_' . $w_key . '_class' , array( $this, 'mobile_display_classes' ), 10, 3 );
				}

			}
		}
	}

	/**
	* Add Content Widget - Featured Image Size.
	*/
	public function add_content_featured_image_size( $elements, $widget ){

		$new_elements = array();

		foreach( $elements as $key => $element ){

			$new_elements[ $key ] = $element;

			if( 'imageratios' == $key ){

				$new_elements['featuredimage-size'] = array(
					'type' => 'range',
					'label' => __( 'Image Size (px)', 'layerswp' ),
					'name' => $widget->get_layers_field_name( 'featuredimage-size' ),
					'id' => $widget->get_layers_field_id( 'featuredimage-size' ),
					'value' => ( isset( $widget->values['featuredimage-size'] ) ) ? $widget->values['featuredimage-size'] : 0,
					'min' => 0,
					'max' => 1040,
					'step' => 1,
					'placeholder' => 0,
				);
			}
		}

		return $new_elements;
	}

	/**
	* Add Content Widget, Slider Widget - Heading Type.
	*/
	public function add_heading_type( $elements, $widget ){

		$new_elements = array(

			'fonts-heading-type' => array(
				'type' => 'select-icons',
				'label' => __( 'Heading Type', 'layerswp' ),
				'name' => $widget->get_layers_field_name( 'fonts', 'heading-type' ),
				'id' => $widget->get_layers_field_id( 'fonts', 'heading-type' ),
				'value' => ( isset( $widget->values['fonts']['heading-type'] ) ) ? $widget->values['fonts']['heading-type'] : NULL,
				'options' => array(
					'h1' => array( 'name' => __( 'H1', 'layerswp' ), 'class' => 'icon-heading-1', 'data' => '' ),
					'h2' => array( 'name' => __( 'H2', 'layerswp' ), 'class' => 'icon-heading-2', 'data' => '' ),
					'h3' => array( 'name' => __( 'H3', 'layerswp' ), 'class' => 'icon-heading-3', 'data' => '' ),
					'h4' => array( 'name' => __( 'H4', 'layerswp' ), 'class' => 'icon-heading-4', 'data' => '' ),
					'h5' => array( 'name' => __( 'H5', 'layerswp' ), 'class' => 'icon-heading-5', 'data' => '' ),
					'h6' => array( 'name' => __( 'H6', 'layerswp' ), 'class' => 'icon-heading-6', 'data' => '' ),
				),
				'wrapper' => 'div',
				'wrapper-class' => 'layers-icon-group layers-icon-group-outline',
			),

		);

		return $new_elements + $elements;
	}


	/**
	* Parallax Background Element
	*/
	public function parallax_elements( $elements, $widget = array()  ){

		if( empty( $widget ) ) return $elements;

		$new_elements = array();

		foreach( $elements as $key => $element ){

			$new_elements[ $key ] = $element;

			if( 'background-position' == $key ){

				unset(  $new_elements[ 'background-parallax' ] );

				$new_elements[ 'background-parallax' ] = array(
					'type' => 'checkbox',
					'label' => __( 'Parallax', 'layers-pro' ),
					'name' => $widget->get_layers_field_name( 'background', 'parallax' ),
					'id' => $widget->get_layers_field_id( 'background', 'parallax' ),
					'value' => ( isset( $widget->values['background']['parallax'] ) ) ? $widget->values['background']['parallax'] : NULL,
					'data' => array(
						'show-if-selector' => '#' . $widget->get_layers_field_id( 'background', 'image' ),
						'show-if-value' => '',
						'show-if-operator' => '!==',
					)
				);
			}

		}

		return $new_elements;
	}


	/**
	* Mobile Display Elements
	*/
	public function mobile_display_elements( $elements, $widget = array()  ){

		if( empty( $widget ) ) return $elements;

		$new_elements = array();

		foreach( $elements as $key => $element ){

			$new_elements[ $key ] = $element;

			if( 'margin' == $key ){

				$new_elements[ 'hide' ] = array(
					'type' => 'select-icons',
					'label' => __( 'Hide on Certain Devices', 'layers-pro' ),
					'group' => array(
						'hide-desktop' => array(
							'type' => 'select-icons',
							'name' => $widget->get_layers_field_name( 'hide', 'desktop' ),
							'id' => $widget->get_layers_field_id( 'hide', 'desktop' ),
							'value' => ( isset( $widget->values['hide']['desktop'] ) ) ? $widget->values['hide']['desktop'] : NULL,
							'options' => array(
								'desktop' => array(
									'class' => 'icon-desktop',
									'data' => ''
								),
							),
						),
						'hide-tablet' => array(
							'type' => 'select-icons',
							'name' => $widget->get_layers_field_name( 'hide', 'tablet' ),
							'id' => $widget->get_layers_field_id( 'hide', 'tablet' ),
							'value' => ( isset( $widget->values['hide']['tablet'] ) ) ? $widget->values['hide']['tablet'] : NULL,
							'options' => array(
								'tablet' => array(
									'class' => 'icon-tablet',
									'data' => ''
								),
							),
						),
						'hide-phone' => array(
							'type' => 'select-icons',
							'name' => $widget->get_layers_field_name( 'hide', 'phone' ),
							'id' => $widget->get_layers_field_id( 'hide', 'phone' ),
							'value' => ( isset( $widget->values['hide']['phone'] ) ) ? $widget->values['hide']['phone'] : NULL,
							'options' => array(
								'phone' => array(
									'class' => 'icon-phone',
									'data' => ''
								),
							),
						),

					),
					'wrapper' => 'div',
					'wrapper-class' => 'layers-icon-group layers-icon-group-outline layers-span-12',
				);
			}

		}

		return $new_elements;
	}



	/**
	* Mobile Display Classes
	*/
	public function mobile_display_classes( $container_class, $widget_this = array(), $instance = array() ){

		// Fallback incase there is no widget or item variables to work with
		if( empty( $widget_this ) || empty( $instance ) ) return $container_class;

		// Get the mobile fallback
		$hide = $widget_this->check_and_return( $instance, 'design', 'hide' );

		if( is_array( $hide ) ){
			foreach( $hide as $h ){
				$container_class[] = 'hide-' . $h;
			}
		}

		return $container_class;

	}

	/**
	* Slider Background Elements
	*/

	public function slider_elements( $elements, $widget ){

		$new_elements = array();

		foreach( $elements as $key => $element ){

			$new_elements[ $key ] = $element;

			if( 'background-image' == $key ){

				$new_elements[ 'background-video-type' ] = array(
					'type' => 'select',
					'label' => __( 'Background Video', 'layers-pro' ),
					'name' => $widget->get_layers_field_name( 'background', 'video-type' ),
					'id' => $widget->get_layers_field_id( 'background', 'video-type' ),
					'value' => ( isset( $widget->values['background']['video-type'] ) ) ? $widget->values['background']['video-type'] : NULL,
					'options' => array(
						'' => __( 'None', 'layers-pro' ),
						'self-hosted' => __( 'Self Hosted', 'layers-pro' ),
						'youtube' => __( 'YouTube', 'layers-pro' ),
						'vimeo' => __( 'Vimeo', 'layers-pro' ),
					)
				);
				$new_elements[ 'background-video-mp4' ] = array(
					'type' => 'upload',
					'label' => __( 'Background MP4', 'layers-pro' ),
					'button_label' => __( 'Choose MP4 File', 'layers-pro' ),
					'name' => $widget->get_layers_field_name( 'background', 'video-mp4' ),
					'id' => $widget->get_layers_field_id( 'background', 'video-mp4' ),
					'value' => ( isset( $widget->values['background']['video-mp4'] ) ) ? $widget->values['background']['video-mp4'] : NULL,
					'data' => array(
						'show-if-selector' => '#' . $widget->get_layers_field_id( 'background', 'video-type' ),
						'show-if-value' => 'self-hosted'
					)
				);
				$new_elements[ 'background-video-youtube' ] = array(
					'type' => 'text',
					'label' => __( 'YouTube URL', 'layers-pro' ),
					'name' => $widget->get_layers_field_name( 'background', 'video-youtube' ),
					'id' => $widget->get_layers_field_id( 'background', 'video-youtube' ),
					'value' => ( isset( $widget->values['background']['video-youtube'] ) ) ? $widget->values['background']['video-youtube'] : NULL,
					'data' => array(
						'show-if-selector' => '#' . $widget->get_layers_field_id( 'background', 'video-type' ),
						'show-if-value' => 'youtube'
					)
				);
				$new_elements[ 'background-video-vimeo' ] = array(
					'type' => 'text',
					'label' => __( 'Vimeo URL', 'layers-pro' ),
					'name' => $widget->get_layers_field_name( 'background', 'video-vimeo' ),
					'id' => $widget->get_layers_field_id( 'background', 'video-vimeo' ),
					'value' => ( isset( $widget->values['background']['video-vimeo'] ) ) ? $widget->values['background']['video-vimeo'] : NULL,
					'data' => array(
						'show-if-selector' => '#' . $widget->get_layers_field_id( 'background', 'video-type' ),
						'show-if-value' => 'vimeo'
					)
				);
			}
		}

		return $new_elements;
	}


	/**
	* Add Slider Widget - Slides - Button Styling.
	*/
	public function add_buttons_styling( $components, $widget = FALSE, $instance = FALSE ){

		// If this filter is called before we added the 2 new args - $widget, $instance - then bail.
		if ( FALSE === $widget || FALSE === $instance ) return $components;

		// Remove the existing watered down buttons control it exists.
		unset( $components['buttons'] ); // Search for array key
		if ( FALSE !== array_search ( 'buttons', $components ) )
			unset( $components[ array_search ( 'buttons', $components ) ] ); // Search for array value

		// Custom settings based on where the filter is action-ed.
		switch ( current_filter() ) {

			case 'layers_slide_widget_slide_design_bar_components' :
			case 'layers_column_widget_column_design_bar_components' :

				$after = 'fonts';

			break;
			case 'layers_post_widget_design_bar_components' :
			case 'layers_showcase_widget_design_bar_components' :

				$after = 'columns';

			break;
			case 'layers_post_carousel_widget_design_bar_components':

				$after = 'display';
			case 'layers_portfolio_widget_design_bar_components':

				$after = 'display';

			break;
			case 'layers_cta_widget_cta_design_bar_components' :
			case 'layers_social_widget_social_design_bar_components' :

				// If 'fonts' is not in the existing components then add a placeholder that
				// we can also look for below. So the new component will still be added.
				if ( empty( $components ) ) $components[] = '-placeholder-';

				$after = '-placeholder-';

			break;
			case 'layers_accordion_widget_design_bar_components' :
			case 'layers_social_widget_design_bar_components' :

				$after = 'background';

			break;
			default:

				$after = FALSE;
		}

		// If there's nothing to put this after then bail.
		if ( ! $after ) return $components;

		// A place to collect the new components.
		$new_components = array();

		// Loop through existing components looking for one we want ours to go after.
		foreach( $components as $key => $element ) {

			// Add the current existing component.
			$new_components[ $key ] = $element;

			// Add ours after the specified component(s).
			if ( $after === $key || $after === $element  ) {

				// Add the new component.
				$new_components['buttons-advanced-new'] = array(
					'icon-css' => 'icon-call-to-action',
					'label' => __( 'Buttons', 'layers-pro' ),
					'elements' => array(

						'buttons-size' => array(
							'type' => 'select',
							'label' => __( 'Size', 'layers-pro' ),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-size' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-size' ),
							'value' => ( isset( $instance['design']['buttons-size'] ) ) ? $instance['design']['buttons-size'] : NULL,
							'options' => array(
								'' => '-- Choose --',
								'small' => __( 'Small', 'layers-pro' ),
								'medium' => __( 'Medium', 'layers-pro' ),
								'large' => __( 'Large', 'layers-pro' ),
								'massive' => __( 'Massive', 'layers-pro' ),
							),
						),

						/**
						 * Background
						 */
						'buttons-background-style' => array(
							'type'  => 'select',
							'label' => __( 'Background Style', 'layers-pro' ),
							'choices' => array(
								'' => '-- Choose --',
								'solid' => __( 'Solid', 'layers-pro' ),
								'transparent' => __( 'Transparent', 'layers-pro' ),
								'gradient' => __( 'Gradient', 'layers-pro' ),
							),
							'default'  => 'solid',

							'name' => $widget->get_layers_field_name( 'design', 'buttons-background-style' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-background-style' ),
							'value' => ( isset( $instance['design']['buttons-background-style'] ) ) ? $instance['design']['buttons-background-style'] : NULL,
						),
						'buttons-background-color' => array(
							'type' => 'color',
							'label' => __( 'Background Color', 'layers-pro' ),
							'data' => array(
								'show-if-selector' => '#' . $widget->get_layers_field_id( 'design', 'buttons-background-style' ),
								'show-if-value' => 'solid',
							),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-background-color' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-background-color' ),
							'value' => ( isset( $instance['design']['buttons-background-color'] ) ) ? $instance['design']['buttons-background-color'] : NULL,
						),
						'buttons-background-gradient-start-color' => array(
							'type' => 'color',
							'label' => __( 'Gradient Start Color', 'layers-pro' ),
							'data' => array(
								'show-if-selector' => '#' . $widget->get_layers_field_id( 'design', 'buttons-background-style' ),
								'show-if-value' => 'gradient',
							),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-background-gradient-start-color' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-background-gradient-start-color' ),
							'value' => ( isset( $instance['design']['buttons-background-gradient-start-color'] ) ) ? $instance['design']['buttons-background-gradient-start-color'] : NULL,
						),
						'buttons-background-gradient-end-color' => array(
							'type' => 'color',
							'label' => __( 'Gradient End Color', 'layers-pro' ),
							'data' => array(
								'show-if-selector' => '#' . $widget->get_layers_field_id( 'design', 'buttons-background-style' ),
								'show-if-value' => 'gradient',
							),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-background-gradient-end-color' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-background-gradient-end-color' ),
							'value' => ( isset( $instance['design']['buttons-background-gradient-end-color'] ) ) ? $instance['design']['buttons-background-gradient-end-color'] : NULL,
						),
						'buttons-background-gradient-direction' => array(
							'type' => 'range',
							'label' => __( 'Gradient Angle', 'layers-pro' ),
							'min' => '0',
							'max' => '360',
							'step' => '1',
							'data' => array(
								'show-if-selector' => '#' . $widget->get_layers_field_id( 'design', 'buttons-background-style' ),
								'show-if-value' => 'gradient',
							),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-background-gradient-direction' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-background-gradient-direction' ),
							'value' => ( isset( $instance['design']['buttons-background-gradient-direction'] ) ) ? $instance['design']['buttons-background-gradient-direction'] : NULL,
						),
						/**
						 * Text
						 */
						'buttons-text-color' => array(
							'type' => 'color',
							'label' => __( 'Text Color', 'layers-pro' ),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-text-color' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-text-color' ),
							'value' => ( isset( $instance['design']['buttons-text-color'] ) ) ? $instance['design']['buttons-text-color'] : NULL,
						),
						'buttons-text-shadow' => array(
							'type' => 'select',
							'label' => __( 'Text Shadow', 'layers-pro' ),
							'choices' => array(
								'' => '-- Choose --',
								'none' => __( 'None', 'layers-pro' ),
								'bottom' => __( 'Bottom Shadow', 'layers-pro' ),
								'top' => __( 'Top Shadow', 'layers-pro' ),
							),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-text-shadow' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-text-shadow' ),
							'value' => ( isset( $instance['design']['buttons-text-shadow'] ) ) ? $instance['design']['buttons-text-shadow'] : NULL,
						),
						'buttons-text-transform' => array(
							'type' => 'select',
							'label' => __( 'Text Transform', 'layers-pro' ),
							'choices' => array(
								// '' => __( 'Normal', 'layers-pro' ),
								'' => '-- Choose --',
								'uppercase' => __( 'Uppercase', 'layers-pro' ),
								'capitalize' => __( 'Capitalize', 'layers-pro' ),
								'lowercase' => __( 'Lowercase', 'layers-pro' ),
							),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-text-transform' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-text-transform' ),
							'value' => ( isset( $instance['design']['buttons-text-transform'] ) ) ? $instance['design']['buttons-text-transform'] : NULL,
						),
						/**
						 * Border
						 */
						'buttons-border-width' => array(
							'type' => 'range',
							'label' => __( 'Border Width', 'layers-pro' ),
							'min' => '0',
							'max' => '10',
							'step' => '1',
							'default' => '0',
							'name' => $widget->get_layers_field_name( 'design', 'buttons-border-width' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-border-width' ),
							'value' => ( isset( $instance['design']['buttons-border-width'] ) ) ? $instance['design']['buttons-border-width'] : NULL,
						),
						'buttons-border-color' => array(
							'type' => 'color',
							'label' => __( 'Border Color', 'layers-pro' ),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-border-color' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-border-color' ),
							'value' => ( isset( $instance['design']['buttons-border-color'] ) ) ? $instance['design']['buttons-border-color'] : NULL,
						),
						/**
						 * Styling
						 */
						'buttons-border-radius' => array(
							'type' => 'range',
							'label' => __( 'Rounded Corner Size', 'layers-pro' ),
							'min' => '0',
							'max' => '100',
							'step' => '1',
							'default' => '4',
							'name' => $widget->get_layers_field_name( 'design', 'buttons-border-radius' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-border-radius' ),
							'value' => ( isset( $instance['design']['buttons-border-radius'] ) ) ? $instance['design']['buttons-border-radius'] : NULL,
						),
						'buttons-shadow' => array(
							'type' => 'select',
							'label' => __( 'Button Shadow', 'layers-pro' ),
							'choices' => array(
								'' => '-- Choose --',
								'none' => __( 'None', 'layers-pro' ),
								'small' => __( 'Small', 'layers-pro' ),
								'medium' => __( 'Medium', 'layers-pro' ),
								'large' => __( 'Large', 'layers-pro' ),
							),
							'name' => $widget->get_layers_field_name( 'design', 'buttons-shadow' ),
							'id' => $widget->get_layers_field_id( 'design', 'buttons-shadow' ),
							'value' => ( isset( $instance['design']['buttons-shadow'] ) ) ? $instance['design']['buttons-shadow'] : NULL,
						),

					),
				);
			}
		}

		return $new_components;
	}

	/**
	* Add Parallax Class to Widgets
	*/

	public function widget_parallax_class( $item_class, $widget_this = array(), $item_instance = array() ){

		// Fallback incase there is no widget or item variables to work with
		if( empty( $widget_this ) || empty( $item_instance ) ) return $item_class;

		// Get the mobile fallback
		$parallax = $widget_this->check_and_return( $item_instance, 'design', 'background', 'parallax');

		if( 'on' == $parallax ) {
			$item_class[] = 'layers-parallax';
		}

		return $item_class;
	}

	public function slider_video_bg( $widget_this, $item_instance = array(), $widget_instance = array()){
		global $wp_customize;

		if( empty( $item_instance ) || empty( $widget_instance ) ) return $widget_this;

		// Get the mobile fallback
		$bg_img = $widget_this->check_and_return( $item_instance, 'design', 'background', 'image');

		// Get the video type
		$bg_video_type = $widget_this->check_and_return( $item_instance, 'design', 'background', 'video-type');

		// Spool up the video URLs
		$src = FALSE;
		$mp4 = $widget_this->check_and_return( $item_instance, 'design', 'background', 'video-mp4');
		$youtube = $widget_this->check_and_return( $item_instance, 'design', 'background', 'video-youtube');
		$vimeo = $widget_this->check_and_return( $item_instance, 'design', 'background', 'video-vimeo');

		if( $mp4 && 'self-hosted' ){
			$src = $mp4;
		} else if( $vimeo && 'vimeo' == $bg_video_type ){
			$src = $vimeo;
			$id = layers_get_vimeo_id( $src );
		} else if( $youtube && 'youtube' == $bg_video_type ){
			$src = $youtube;
			$id = layers_get_youtube_id( $src );
		}

		if( !$src ) return;

		if( 'self-hosted' == $bg_video_type ) {
			if( !$wp_customize && 1 == count( $widget_this->check_and_return( $widget_instance, 'slides' ) ) ) {
				$autoplay = 'autoplay';
			} else {
				$autoplay = '';
			}
			?>
			<video <?php if( $wp_customize ) echo 'customizer'; ?> <?php echo $autoplay; ?> loop <?php if( $bg_img ) { ?>poster="<?php echo wp_get_attachment_url( $bg_img ); ?>"<?php } ?>>
				<?php if( $src ) { ?>
					<source src="<?php echo wp_get_attachment_url( $src ); ?>" type="video/mp4" />
  				<?php } ?>
			</video>
		<?php }
		elseif( 'youtube' == $bg_video_type || 'vimeo' == $bg_video_type ) {
			if( !$wp_customize && 1 == count( $widget_this->check_and_return( $widget_instance, 'slides' ) ) ) {
				$autoplay = '&autoplay=1';
			} else {
				$autoplay = '';
			}
			?>
			<div class="layerspro-slider-video fitvidsignore">
				<?php if( 'youtube' == $bg_video_type ) {
					 ?>
					<iframe frameborder="0" src="//www.youtube.com/embed/<?php echo $id; ?>?enablejsapi=1&controls=0&loop=1&playlist=<?php echo $id; ?>&rel=0&showinfo=0&autohide=1&wmode=transparent&hd=1<?php echo $autoplay; ?>"></iframe>
				<?php } elseif( 'vimeo' == $bg_video_type ) {
					 ?>
					<iframe frameborder="0" src="//player.vimeo.com/video/<?php echo $id; ?>?title=0&controls=0&byline=0&portrait=0&loop=1&background=1<?php echo $autoplay; ?>" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<?php }  ?>
			</div>
		<?php }
	}

}

// Initialize
Layers_Pro_Widget_Filters::get_instance();
