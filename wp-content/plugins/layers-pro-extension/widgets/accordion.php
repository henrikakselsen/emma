<?php
/**
 * Carousel Widget
 *
 * This file is used to register and display the Layers - Carousel widget.
 *
 * @package Layers
 * @since Layers 1.0.0
 */

function layers_accordion_widget_init() {

	if( !class_exists( 'Layers_Widget' ) ) return;

	if( !class_exists( 'Layers_Accordion_Widget' ) ) {
		class Layers_Accordion_Widget extends Layers_Pro_Widget {

			/**
			*  Widget construction
			*/
			function __construct() {

				/**
				* Widget variables
				*
				* @param   string   $widget_title   Widget title
				* @param   string   $widget_id      Widget slug for use as an ID/classname
				* @param   array    $checkboxes     (optional) Array of checkbox names to be saved in this widget. Don't forget these please!
				*/
				$this->widget_title = __( 'Accordions', 'layers-pro' );
				$this->widget_id    = 'layers-pro-accordion';
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
					'title' => __( 'Accordions', 'layers-pro' ),
					'excerpt' => __( 'Lorem ipsum dolor sit amet enim ad minim veniam, quis nostrud exercitation.', 'layers-pro' ),
					'design' => array(
						'layout' => 'layout-boxed',
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
					'accordion',
					3,
					array(
						'accordion-title' => 'Accordion 1',
						'content' => __( '<p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit, ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in.</p>', 'layers-pro'),
					),
					array(
						'accordion-title' => 'Accordion 2',
						'content' => __( '<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', 'layers-pro'),
					),
					array(
						'accordion-title' => 'Accordion 3',
						'content' => __( '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in.</p>', 'layers-pro'),
					)
				);
			}

			/**
			*  Widget front end display
			*/
			function widget( $args, $instance ) {

				global $wp_customize;

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
					$this->inline_css .= layers_inline_styles( "#{$widget_id}", 'background', array( 'background' => $this->check_and_return( $instance, 'design', 'background' ) ) );
				}

				// Text Styling
				if ( NULL !== $this->check_and_return( $instance, 'design', 'fonts', 'color' ) ) {
					$this->inline_css .= layers_inline_styles( "#{$widget_id} .section-title .heading, #{$widget_id} .section-title div.excerpt", array( 'css' => array( 'color' => $this->check_and_return( $instance, 'design', 'fonts', 'color' ) ) ) );
					$this->inline_css .= layers_inline_styles( "#{$widget_id} .accordion-list > li a.accordion-button, #{$widget_id} .accordion-list > li a.accordion-button:before, #{$widget_id} .accordion-list > li .accordion-content", array( 'css' => array( 'color' => $this->check_and_return( $instance, 'design', 'fonts', 'color' ) ) ) );

				}

				// Button Styling
				$this->inline_css .= layers_pro_apply_widget_button_styling( $this, $instance, array( "#{$widget_id} .accordion-list > li > a.accordion-button" ) );

				// Apply the advanced widget styling
				$this->apply_widget_advanced_styling( $widget_id, $instance );

				/**
				 * Generate Classes
				 */
				$widget_container_class = array();
				$widget_container_class[] = 'widget';
				$widget_container_class[] = 'content-vertical-massive';
				$widget_container_class[] = 'layers-pro-accordion'; // Important - Widget Class
				$widget_container_class[] = ( 'on' == $this->check_and_return( $instance , 'design', 'background', 'darken' ) ? 'darken' : '' );
				if ( empty( $instance['design']['buttons-background-color'] ) || '#ffffff' == $instance['design']['buttons-background-color'] ) {
					$widget_container_class[] = 'accordion-item-styling';
				}
				$widget_container_class[] = $this->check_and_return( $instance , 'design', 'advanced', 'customclass' ); // Apply custom class from design-bar's advanced control.

				$widget_container_class = apply_filters( 'layers_accordian_widget_container_class', $widget_container_class, $this, $instance );
				$widget_container_class = implode( ' ', $widget_container_class );

				// Custom Anchor
				echo $this->custom_anchor( $instance ); ?>

				<div id="<?php echo esc_html( $widget_id ); ?>" class="<?php echo esc_attr( $widget_container_class ); ?>" <?php $this->selective_refresh_atts( $args ); ?>>

					<?php if ( NULL !== $this->check_and_return( $instance , 'title' ) || NULL !== $this->check_and_return( $instance , 'excerpt' ) ) { ?>
						<div class="container clearfix">
							<?php
							/**
							 * Generate Classes
							 */
							$classes = array();
							$classes[] = $this->check_and_return( $instance , 'design', 'fonts', 'size' );
							$classes[] = $this->check_and_return( $instance , 'design', 'fonts', 'align' );
							$classes[] = ( $this->check_and_return( $instance, 'design', 'background' , 'color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $instance, 'design', 'background' , 'color' ) ) ? 'invert' : '' );
							$classes = implode( ' ', $classes ); ?>
							<div class="section-title clearfix <?php echo esc_attr( $classes ); ?>">
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

					<?php if( ! empty( $instance['accordions'] ) ) { ?>

						<?php
						/**
						 * Generate Classes
						 */
						$classes = array();
						$classes[] = 'clearfix';
						$classes[] = 'accordion-row';
						$classes[] = 'nav-accordion';
						$classes[] = $this->get_widget_layout_class( $instance );
						$classes[] = ( $this->check_and_return( $instance, 'design', 'buttons-background-color' ) ) ? 'no-background' : '';
						$classes[] = ( $this->check_and_return( $instance, 'design', 'background' , 'color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $instance, 'design', 'background' , 'color' ) ) ? 'invert' : '' );
						$classes = implode( ' ', $classes );
						?>
						<div class="<?php echo esc_attr( $classes ); ?>">
							<ul class="accordion-list">
								<?php
								/**
								 * Tab Items - Loop
								 */
								$i = 0;
								foreach ( explode( ',', $instance['accordion_ids'] ) as $item_key ) {

									// Internal Vars.
									$item_instance         = $instance['accordions'][ $item_key ];
									$item_id_attr = "{$widget_id}-accordions-{$item_key}";

									// Mix in new/unset defaults on every instance load (NEW)
									$item_instance = $this->apply_defaults( $item_instance, 'accordion' );

									// Make sure we've got a accordion going on here
									if( ! isset( $instance['accordions'][$item_key ]) ) continue;
									?>
									<li id="<?php echo esc_attr( $item_id_attr ); ?>" class="accordion">
										<?php
										/**
										 * Generate Classes
										 */
										$classes = array();

										// Button Size
										if ( $this->check_and_return( $instance, 'design' , 'buttons-size' ) ) {
											$classes[] = 'accordion-' . $this->check_and_return( $instance, 'design', 'buttons-size' );
										}

										$classes = implode( ' ', $classes );
										?>
										<a href="#" class="accordion-button <?php echo esc_attr( $classes ); ?>">
											<?php echo esc_attr( $item_instance['accordion-title'] ); ?>
										</a>
										<?php
										/**
										 * Generate Classes
										 */
										$classes = array();
										$classes[] = 'content';
										$classes[] = 'copy';
										$classes[] = 'accordion-content';
										$classes = implode( ' ', $classes );
										?>
										<section class=" <?php echo esc_attr( $classes ); ?>">
											<?php echo layers_the_content( $item_instance['content'] ); ?>
										</section>
									</li>
									<?php
									$i++;
								}
								?>
							</ul>
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
						'name' => $this->get_layers_field_name( 'design' ),
						'id' => $this->get_layers_field_id( 'design' ),
						'widget_id' => $this->widget_id,
					),
					$instance, // Widget Values
					apply_filters( 'layers_accordion_widget_design_bar_components', array( // Components
						'layout',
						'background',
						'advanced',
					), $this, $instance )
				); ?>
				<div class="layers-container-large" id="layers-column-widget-<?php echo $this->number; ?>">

					<?php
					$this->form_elements()->header( array(
						'title' =>'Accordions',
						'icon_class' =>'text'
					) );
					?>

					<section class="layers-accordion-section layers-content">
						<div class="layers-form-item">

							<?php echo $this->form_elements()->input(
								array(
									'type' => 'text',
									'name' => $this->get_layers_field_name( 'title' ),
									'id' => $this->get_layers_field_id( 'title' ),
									'placeholder' => __( 'Enter title here', 'layers-pro' ),
									'value' => ( isset( $instance['title'] ) ) ? $instance['title'] : NULL,
									'class' => 'layers-text layers-large',
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
								apply_filters( 'layers_accordion_widget_inline_design_bar_components', array( // Components
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
									'value' => ( isset( $instance['excerpt'] ) ) ? $instance['excerpt'] : NULL,
									'class' => 'layers-textarea layers-large',
								)
							); ?>

						</div>
						<div class="layers-form-item">

							<?php $this->repeater( 'accordion', $instance ); ?>

						</div>
					</section>

				</div>

			<?php }

			/**
			*  Widget repeated item display.
			*/
			function accordion_item( $item_guid, $item_instance ) {

				// Required - Get the name of this type.
				$type = str_replace( '_item', '', __FUNCTION__ );

				// Mix in new/unset defaults on every instance load (NEW)
				$item_instance = $this->apply_defaults( $item_instance, 'accordion' );
				?>
				<li class="layers-accordion-item <?php echo $this->item_count; ?>" data-guid="<?php echo esc_attr( $item_guid ); ?>">
					
					<a class="layers-accordion-title">
						<span>
							<?php echo ucfirst( $type ); ?><span class="layers-detail"><?php if ( isset( $item_instance['accordion-title'] ) ) echo $this->format_repeater_title( $item_instance['accordion-title'] ); ?></span>
						</span>
					</a>
					
					<section class="layers-accordion-section layers-content">

						<?php
						$this->design_bar(
							'top', // CSS Class Name
							array( // Widget Object
								'name'       => $this->get_layers_field_name( 'design' ),
								'id'         => $this->get_layers_field_id( 'design' ),
								'widget_id'  => $this->widget_id,
								'number'     => $this->number,
								'show_trash' => TRUE,
							),
							$item_instance, // Widget Values
							array( // Components
							)
						);
						?>

						<div class="layers-row">
							<div class="layers-column layers-span-12">
								<p class="layers-form-item">
									<label for="<?php echo $this->get_layers_field_id( 'accordion-title' ); ?>"><?php _e( 'Title' , 'layers-pro' ); ?></label>
									<?php echo $this->form_elements()->input(
										array(
											'type' => 'text',
											'name' => $this->get_layers_field_name( 'accordion-title' ),
											'id' => $this->get_layers_field_id( 'accordion-title' ),
											'placeholder' => __( 'Tab title', 'layers-pro' ),
											'value' => ( isset( $item_instance['accordion-title'] ) ) ? $item_instance['accordion-title'] : NULL ,
											'class' => 'layers-text'
										)
									); ?>
								</p>
							</div>
						</div>

						<div class="layers-row">
							<div class="layers-column layers-span-12">
								<p class="layers-form-item">
									<label for="<?php echo $this->get_layers_field_id( 'content' ); ?>"><?php _e( 'Excerpt' , 'layers-pro' ); ?></label>
									<?php echo $this->form_elements()->input(
										array(
											'type' => 'rte',
											'name' => $this->get_layers_field_name( 'content' ),
											'id' => $this->get_layers_field_id( 'content' ),
											'placeholder' =>  __( 'Short Excerpt', 'layers-pro' ),
											'value' => ( isset( $item_instance['content'] ) ) ? $item_instance['content'] : NULL ,
											'class' => 'layers-textarea'
										)
									); ?>
								</p>
							</div>
						</div>

					</section>

				</li>
				<?php
			}

		}

		// Add our function to the widgets_init hook.
		register_widget("Layers_Accordion_Widget");
	}
}
add_action( 'widgets_init', 'layers_accordion_widget_init', 40 );
