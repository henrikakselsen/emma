<?php
/**
 * Carousel Widget
 *
 * This file is used to register and display the Layers - Carousel widget.
 *
 * @package Layers
 * @since Layers 1.0.0
 */

function layers_call_to_action_widget_init() {

	if( !class_exists( 'Layers_Widget' ) ) return;

	if( !class_exists( 'Layers_Call_To_Action_Widget' ) ) {
		class Layers_Call_To_Action_Widget extends Layers_Pro_Widget {

			/**
			*  Widget construction
			*/
			function __construct() {

				/**
				* Widget variables
				*
			 	* @param  	string    		$widget_title    	Widget title
			 	* @param  	string    		$widget_id    		Widget slug for use as an ID/classname
			 	* @param  	array 			$checkboxes    	(optional) Array of checkbox names to be saved in this widget. Don't forget these please!
			 	*/
				$this->widget_title = __( 'Call to Action', 'layers-pro' );
				$this->widget_id    = 'layers-pro-call-to-action';
				$this->checkboxes   = array(
					'buttons-full-width',
				);

				/* Widget settings. */
				$widget_ops = array(
					'classname' => 'obox-layers-' . $this->widget_id .'-widget',
					'description' => __( 'This widget is used to display your ', 'layers-pro' ) . $this->widget_title . '.',
					'customize_selective_refresh' => TRUE,
				);

				/* Widget control settings. */
				$control_ops = array(
					'width' => LAYERS_WIDGET_WIDTH_LARGE,
					'height' => NULL,
					'id_base' => LAYERS_THEME_SLUG . '-widget-' . $this->widget_id,
				);

				/* Create the widget. */
				parent::__construct(
					LAYERS_THEME_SLUG . '-widget-' . $this->widget_id,
					$this->widget_title,
					$widget_ops,
					$control_ops
				);

				/* Setup Widget Defaults */
				$this->defaults = array (
					'title' => __( 'Find Out More', 'layers-pro' ),
					'excerpt' => __( 'Follow the links below to find out more about our amazing services.', 'layers-pro' ),
					'design' => array(
						'layout' => 'layout-boxed',
						'background' => array(
							'position' => 'center',
							'repeat' => 'no-repeat',
							'color' => NULL,
						),
						'fonts' => array(
							'align' => 'text-left',
							'size' => 'medium',
							'color' => NULL,
							'shadow' => NULL,
							'heading-type' => 'h3',
						),
					),
				);

				/* Setup Widget Repeater Defaults */
				$this->register_repeater_defaults(
					'button',
					2,
					array(
						'button' => array(
							'link_text' => 'Buy Now',
							'link_type' => 'custom',
							'link_type_custom' => '#buy-more',
						)
					),
					array(
						'button' => array(
							'link_text' => 'More Info',
							'link_type' => 'custom',
							'link_type_custom' => '#more-info',
						)
					)
				);
			}

			/**
			*  Widget front end display
			*/
			function widget( $args, $instance ) {

				$this->backup_inline_css();

				// Turn $args array into variables.
				extract( $args );

				// Use defaults if $instance is empty.
				if( empty( $instance ) && ! empty( $this->defaults ) ) {
					$instance = wp_parse_args( $instance, $this->defaults );
				}

				// Mix in new/unset defaults on every instance load (NEW)
				$instance = $this->apply_defaults( $instance );

				/**
				 * Styling
				 */

				// Background Styling
				if ( NULL !== $this->check_and_return( $instance, 'design', 'background' ) ) {
					$this->inline_css .= layers_inline_styles( "#{$widget_id}", 'background', array( 'background' => $instance['design']['background'] ) );
				}

				// Text Styling
				if ( NULL !== $this->check_and_return( $instance, 'design', 'fonts', 'color' ) ) {
					$this->inline_css .= layers_inline_styles( "#{$widget_id} .section-title .heading, #{$widget_id} .section-title div.excerpt", array( 'css' => array( 'color' => $this->check_and_return( $instance, 'design', 'fonts', 'color' ) ) ) );
				}

				// Apply the advanced widget styling
				$this->apply_widget_advanced_styling( $widget_id, $instance );

				/**
				 * Generate Classes
				 */
				$widget_container_class = array();
				$widget_container_class[] = 'widget';
				$widget_container_class[] = 'layers-pro-call-to-action';
				$widget_container_class[] = 'content-vertical-massive';
				$widget_container_class[] = ( 'on' == $this->check_and_return( $instance , 'design', 'background', 'darken' ) ? 'darken' : '' );
				$widget_container_class[] = ( 'container' != $this->get_widget_layout_class( $instance ) ? 'content' : '' );
				$widget_container_class[] = $this->check_and_return( $instance , 'design', 'advanced', 'customclass' ); // Apply custom class from design-bar's advanced control.

				$widget_container_class = apply_filters( 'layers_cta_widget_container_class', $widget_container_class, $this, $instance );
				$widget_container_class = implode( ' ', $widget_container_class );

				// Custom Anchor
				echo $this->custom_anchor( $instance ); ?>

				<div id="<?php echo $widget_id; ?>" class="<?php echo esc_attr( $widget_container_class ); ?>" <?php $this->selective_refresh_atts( $args ); ?>>

					<?php if ( NULL !== $this->check_and_return( $instance , 'title' ) || NULL !== $this->check_and_return( $instance , 'excerpt' ) ) {

						$classes = array();
						$classes[] = $this->get_widget_layout_class( $instance );
						$classes[] = 'clearfix';
						$classes = implode( ' ', $classes ); ?>
						<div class="<?php echo esc_attr( $classes ); ?>">
							<?php
							/**
							 * Generate Classes
							 */
							$classes = array();
							$classes[] = 'section-title';
							$classes[] = 'clearfix';
							$classes[] = $this->check_and_return( $instance , 'design', 'fonts', 'size' );
							$classes[] = $this->check_and_return( $instance , 'design', 'fonts', 'align' );
							$classes[] = ( $this->check_and_return( $instance, 'design', 'background', 'color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $instance, 'design', 'background', 'color' ) ) ? 'invert' : '' );
							$classes = implode( ' ', $classes );
							?>
							<div class="<?php echo esc_attr( $classes ); ?>">
								<?php if( '' != $instance['title'] ) { ?>
									<<?php echo $this->check_and_return( $instance, 'design', 'fonts', 'heading-type' ); ?> class="heading">
										<?php echo esc_html( $instance['title'] ); ?>
									</<?php echo $this->check_and_return( $instance, 'design', 'fonts', 'heading-type' ); ?>>
								<?php } ?>
								<?php if( '' != $instance['excerpt'] ) { ?>
									<div class="excerpt"><?php layers_the_content( $instance['excerpt'] ); ?></div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>

					<?php if( ! empty( $instance['buttons'] ) ) { ?>

						<?php
						/**
						 * Generate Classes
						 */
						$classes = array();
						$classes[] = 'clearfix';
						$classes[] = 'button-collection';
						$classes[] = $this->get_widget_layout_class( $instance );
						$classes[] = $this->check_and_return( $instance , 'design', 'fonts', 'align' );
						$classes[] = $this->check_and_return( $instance , 'design', 'liststyle' );

						$classes = implode( ' ', $classes ); ?>
						<div class="<?php echo esc_attr( $classes ); ?>">
							<?php foreach ( explode( ',', $instance['button_ids'] ) as $item_key ) {

								// Setup Internal Vars.
								$item_instance = $instance['buttons'][ $item_key ];
								$item_id_attr  = "{$widget_id}-buttons-{$item_key}";

								// Mix in new/unset defaults on every instance load (NEW)
								$item_instance = $this->apply_defaults( $item_instance, 'button' );

								// Get the link array.
								if( method_exists( $this, 'check_and_return_link' ) ) {
									$link_array = $this->check_and_return_link( $item_instance, 'button' );
								} else {
									$link_array = $this->check_and_return( $item_instance, 'button' );
								}

								$link_href_attr   = ( isset( $link_array['link'] ) ) ? 'href="' . esc_url( $link_array['link'] ) . '"' : '';
								$link_target_attr = ( '_blank' == $link_array['target'] || ( !isset( $link_array[ 'target' ] ) && 'new' == $this->check_and_return( $item_instance, 'design', 'buttons-target' ) ) ) ? 'target="_blank"' : '';

								// Make sure we've got a tab going on here
								if( ! isset( $instance['buttons'][$item_key ]) ) continue;

								// Make sure we've got minimum stuff required for a button.
								if ( ! $link_array[ 'link' ] || ! $link_array[ 'text' ] ) continue;

								/**
								 * Button Styling
								 */
								$this->inline_css .= layers_pro_apply_widget_button_styling( $this, $item_instance, array( "#{$widget_id} #{$item_id_attr}.button" ) );

								/**
								 * Generate Classes
								 */
								$classes = array();

								// Button Size
								if ( $this->check_and_return( $item_instance, 'design', 'buttons-size' ) ) {
									$classes[] = 'btn-' . $this->check_and_return( $item_instance, 'design', 'buttons-size' );
								}

								// Button Width
								if ( $this->check_and_return( $item_instance, 'design', 'buttons-full-width' ) ) {
									$classes[] = 'btn-full';
								}

								$classes = implode( ' ', $classes ); ?>
								<a <?php echo $link_href_attr; ?> <?php echo $link_target_attr; ?> id="<?php echo esc_attr( $item_id_attr ); ?>" class="button <?php echo esc_attr( $classes ); ?>">
									<?php echo $link_array['text']; ?>
								</a>
							<?php } ?>
						</div>

					<?php }

					 // Print the Inline Styles for this Widget
					if( method_exists( $this, 'print_inline_css' ) )
						$this->print_inline_css(); ?>
				</div>
			<?php }

			/**
			*  Widget update
			*/
			function update( $new_instance, $old_instance ) {
				if ( isset( $this->checkboxes ) ) {
					foreach( $this->checkboxes as $cb ) {
						if( isset( $old_instance[ $cb ] ) ) {
							$old_instance[ $cb ] = strip_tags( $new_instance[ $cb ] );
						}
					} // foreach checkboxes
				} // if checkboxes
				return $new_instance;
			}

			/**
			* Widget form
			*
			* We use regular HTML here, it makes reading the widget much easier than if we used just php to echo all the HTML out.
			*/
			function form( $instance ){

				// Use defaults if $instance is empty.
				if( empty( $instance ) && ! empty( $this->defaults ) ) {
					$instance = wp_parse_args( $instance, $this->defaults );
				}

				// Mix in new/unset defaults on every instance load (NEW)
				$instance = $this->apply_defaults( $instance );

				$this->design_bar(
					'side', // CSS Class Name
					array( // Widget Object
						'name'      => $this->get_layers_field_name( 'design' ),
						'id'        => $this->get_layers_field_id( 'design' ),
						'widget_id' => $this->widget_id,
					),
					$instance, // Widget Values
					array( // Components
						'layout',
						'background',
						'advanced',
					)
				); ?>
				<div class="layers-container-large" id="layers-column-widget-<?php echo $this->number; ?>">

					<?php $this->form_elements()->header( array(
						'title' =>'Call to Action',
						'icon_class' =>'text'
					) ); ?>

					<section class="layers-accordion-section layers-content">
						<div class="layers-form-item">

							<?php echo $this->form_elements()->input(
								array(
									'type' => 'text',
									'name' => $this->get_layers_field_name( 'title' ),
									'id' => $this->get_layers_field_id( 'title' ),
									'placeholder' => __( 'Enter title here', 'layers-pro' ),
									'value' => ( isset( $instance['title'] ) ) ? $instance['title'] : NULL ,
									'class' => 'layers-text layers-large'
								)
							); ?>

							<?php $this->design_bar(
								'top', // CSS Class Name
								array( // Widget Object
									'name' => $this->get_layers_field_name( 'design' ),
									'id' => $this->get_layers_field_id( 'design' ),
									'widget_id' => $this->widget_id,
									'show_trash' => FALSE,
									'inline' => TRUE,
									'align' => 'right',
								),
								$instance, // Widget Values
								apply_filters( 'layers_cta_widget_inline_design_bar_components', array( // Components
									'fonts',
								), $this, $instance )
							); ?>

						</div>
						<div class="layers-form-item">

							<?php echo $this->form_elements()->input(
								array(
									'type' => 'rte',
									'name' => $this->get_layers_field_name( 'excerpt' ),
									'id' => $this->get_layers_field_id( 'excerpt' ),
									'placeholder' =>  __( 'Short Excerpt', 'layers-pro' ),
									'value' => ( isset( $instance['excerpt'] ) ) ? $instance['excerpt'] : NULL ,
									'class' => 'layers-textarea layers-large'
								)
							); ?>

						</div>
						<div class="layers-form-item">

							<?php $this->repeater( 'button', $instance ); ?>

						</div>
					</section>
				</div>

			<?php }

			/**
			*  Widget repeated item display.
			*/
			function button_item( $item_guid, $item_instance ) {

				// Required - Get the name of this type.
				$type = str_replace( '_item', '', __FUNCTION__ );

				// Mix in new/unset defaults on every instance load (NEW)
				$item_instance = $this->apply_defaults( $item_instance, 'button' );
				?>
				<li class="layers-accordion-item <?php echo $this->item_count; ?>" data-guid="<?php echo esc_attr( $item_guid ); ?>">

					<a class="layers-accordion-title">
						<span>
							<?php echo ucfirst( $type ); ?><span class="layers-detail"><?php if ( isset( $item_instance['button']['link_text'] ) ) echo $this->format_repeater_title( $item_instance['button']['link_text'] ); ?></span>
						</span>
					</a>

					<section class="layers-accordion-section layers-content">

						<?php $this->design_bar(
							'top', // CSS Class Name
							array( // Widget Object
								'name'       => $this->get_layers_field_name( 'design' ),
								'id'         => $this->get_layers_field_id( 'design' ),
								'widget_id'  => $this->widget_id,
								'number'     => $this->number,
								'show_trash' => TRUE,
							),
							$item_instance, // Widget Values
							apply_filters( 'layers_cta_widget_cta_design_bar_components', array(), $this, $item_instance ) // Components
						); ?>
						<div class="layers-row">
							<div class="layers-form-item">
								<label>
									<?php _e( 'Insert Link' , 'layerswp' ); ?>
								</label>
								<?php echo $this->form_elements()->input(
									array(
										'type' => 'link-group',
										'name' => $this->get_layers_field_name( 'button' ),
										'id' => $this->get_layers_field_id( 'button' ),
										'value' => ( isset( $item_instance['button'] ) ) ? $item_instance['button'] : NULL,
									)
								); ?>
							</div>
						</div>
					</section>

				</li>
				<?php
			}

		}

		// Add our function to the widgets_init hook.
		 register_widget("Layers_Call_To_Action_Widget");
	}
}
add_action( 'widgets_init', 'layers_call_to_action_widget_init', 40 );
