<?php
/**
 * Carousel Widget
 *
 * This file is used to register and display the Layers - Carousel widget.
 *
 * @package Layers
 * @since Layers 1.0.0
 */

function layers_carousel_widget_init() {

	if( !class_exists( 'Layers_Widget' ) ) return;

	if( !class_exists( 'Layers_Carousel_Widget' ) ) {
		class Layers_Carousel_Widget extends Layers_Pro_Widget {

			/**
			*  Widget construction
			*/
			function __construct() {

				/**
				* Widget variables
				*
				* @param  	string    		$widget_title    	Widget title
				* @param  	string    		$widget_id    		Widget slug for use as an ID/classname
				* @param  	string    		$post_type    		(optional) Post type for use in widget options
				* @param  	string    		$taxonomy    		(optional) Taxonomy slug for use as an ID/classname
				* @param  	array 			$checkboxes    	(optional) Array of checkbox names to be saved in this widget. Don't forget these please!
				*/
				$this->widget_title = __( 'Posts Carousel' , 'layers-pro' );
				$this->widget_id = 'layers-pro-post-carousel';
				$this->post_type = 'post';
				$this->taxonomy = 'category';
				$this->checkboxes = array(
					'show_media',
					'show_titles',
					'show_excerpts',
					'show_dates',
					'show_author',
					'show_tags',
					'show_categories',
					'show_call_to_action',
					'navigation_arrows',
					'navigation_dots',
					'autoplay_slides',
				); // @TODO: Try make this more dynamic, or leave a different note reminding users to change this if they add/remove checkboxes

				/* Widget settings. */
				$widget_ops = array(
					'classname' => 'obox-layers-' . $this->widget_id .'-widget',
					'description' => __( 'This widget is used to display your ', 'layers-pro' ) . $this->widget_title . '.',
					'customize_selective_refresh' => TRUE,
				);

				/* Widget control settings. */
				$control_ops = array(
					'width' => LAYERS_WIDGET_WIDTH_SMALL,
					'height' => NULL,
					'id_base' => LAYERS_THEME_SLUG . '-widget-' . $this->widget_id,
				);

				/* Create the widget. */
				parent::__construct(
					LAYERS_THEME_SLUG . '-widget-' . $this->widget_id ,
					$this->widget_title,
					$widget_ops,
					$control_ops
				);

				/* Setup Widget Defaults */
				$this->defaults = array(
					'title' => __( 'Latest Posts', 'layers-pro' ),
					'excerpt' => __( 'Stay up to date with all our latest news and launches. Only the best quality makes it onto our blog!', 'layers-pro' ),
					'category' => 0,
					'posts_per_page' => 6,
					'design' => array(

						'slider_type' => 'set-by-set',
						'navigation_arrows' => 'on',
						'navigation_arrows_color' => '#696969',
						'navigation_dots' => 'on',
						'autoplay_slides' => 'on',
						'slide_interval' => '4',

						'show_media' => 'on',
						'show_titles' => 'on',
						'show_excerpts' => 'on',
						'show_dates' => 'on',
						'show_author' => 'on',
						'show_tags' => 'on',
						'show_categories' => 'on',
						'excerpt_length' => 200,
						'show_call_to_action' => 'on',
						'call_to_action' => __( 'Read More' , 'layers-pro' ),

						'text_style' => 'regular',

						'layout' => 'layout-boxed',
						'imageratios' => 'image-square',
						'textalign' => 'text-left',
						'columns' => '3',
						'gutter' => 'on',

						'background' => array(
							'position' => 'center',
							'repeat' => 'no-repeat'
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
			}

			/**
			* Enqueue Scripts
			*/
			function enqueue_scripts(){

				// Slider JS enqueue
				wp_enqueue_script(
					LAYERS_THEME_SLUG . '-slider-js' ,
					get_template_directory_uri() . '/core/widgets/js/swiper.js',
					array( 'jquery' ),
					LAYERS_VERSION
				);

				// Slider CSS enqueue
				wp_enqueue_style(
					LAYERS_THEME_SLUG . '-slider',
					get_template_directory_uri() . '/core/widgets/css/swiper.css',
					array(),
					LAYERS_VERSION
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

				// Enqueue Scipts when needed
				$this->enqueue_scripts();

				// Set the span class for each column
				if( isset( $instance['design'][ 'columns']  ) ) {
					$col_count = str_ireplace('columns-', '', $instance['design'][ 'columns']  );
					$span_class = 'span-' . ( 12/ $col_count );
				} else {
					$col_count = 3;
					$span_class = 'span-4';
				}

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

				// Columns Styling
				if( NULL !== $this->check_and_return( $instance, 'design', 'column-background-color' ) ) $this->inline_css .= layers_inline_styles( "#{$widget_id} .layers-masonry-column", array( 'css' => array( 'background-color' => $this->check_and_return( $instance, 'design', 'column-background-color' ) ) ) );
				if( NULL !== $this->check_and_return( $instance, 'design', 'column-background-color' ) ) $this->inline_css .= layers_inline_styles( "#{$widget_id} .thumbnail-body", array( 'css' => array( 'background-color' => 'transparent' ) ) );

				// Navigation Styling
				if( NULL !== $this->check_and_return( $instance, 'design', 'navigation_arrows_color' ) ) $this->inline_css .= layers_inline_styles( "#{$widget_id}", 'color', array( 'selectors' => array( '.arrows a' ), 'color' => $this->check_and_return( $instance, 'design', 'navigation_arrows_color' ) ) );
				if( NULL !== $this->check_and_return( $instance, 'design', 'navigation_arrows_color' ) ) $this->inline_css .= layers_inline_styles( "#{$widget_id}", 'border', array( 'selectors' => array( 'span.swiper-pagination-switch' ), 'border' => array( 'color' => $this->check_and_return( $instance, 'design', 'navigation_arrows_color' ) ) ) );
				if( NULL !== $this->check_and_return( $instance, 'design', 'navigation_arrows_color' ) ) $this->inline_css .= layers_inline_styles( "#{$widget_id}", 'background', array( 'selectors' => array( 'span.swiper-pagination-switch' ), 'background' => array( 'color' => $this->check_and_return( $instance, 'design', 'navigation_arrows_color' ) ) ) );
				if( NULL !== $this->check_and_return( $instance, 'design', 'navigation_arrows_color' ) ) $this->inline_css .= layers_inline_styles( "#{$widget_id}", 'background', array( 'selectors' => array( 'span.swiper-pagination-switch.swiper-active-switch' ), 'background' => array( 'color' => 'transparent !important' ) ) );

				// Button Styling
				$this->inline_css .= layers_pro_apply_widget_button_styling( $this, $instance, array( "#{$widget_id} .button" ) );

				// Button Styling
				$this->inline_css .= layers_pro_apply_widget_button_styling( $this, $instance, array( "#{$widget_id} .button" ) );

				// Set Image Sizes
				if( isset( $instance['design'][ 'imageratios' ] ) ){

					// Translate Image Ratio
					$image_ratio = layers_translate_image_ratios( $instance['design'][ 'imageratios' ] );

					if( 'layout-boxed' == $this->check_and_return( $instance , 'design', 'layout' ) && $col_count > 2 ){
						$use_image_ratio = $image_ratio . '-medium';
					} elseif( 'layout-boxed' != $this->check_and_return( $instance , 'design', 'layout' ) && $col_count > 3 ){
						$use_image_ratio = $image_ratio . '-large';
					} else {
						$use_image_ratio = $image_ratio . '-large';
					}
				} else {
					$use_image_ratio = 'large';
				}

				$query_args[ 'post_type' ] = $this->post_type;
				$query_args[ 'posts_per_page' ] = $instance['posts_per_page'];
				if( isset( $instance['order'] ) ) {

					$decode_order = json_decode( $instance['order'], true );

					if( is_array( $decode_order ) ) {
						foreach( $decode_order as $key => $value ){
							$query_args[ $key ] = $value;
						}
					}
				}

				// Do the special taxonomy array()
				if( isset( $instance['category'] ) && '' != $instance['category'] && 0 != $instance['category'] ){

					$query_args['tax_query'] = array(
						array(
							"taxonomy" => $this->taxonomy,
							"field" => "id",
							"terms" => $instance['category']
						)
					);
				}

				// Do the WP_Query
				$post_query = new WP_Query( $query_args );

				// Set the meta to display
				global $layers_post_meta_to_display;
				$layers_post_meta_to_display = array();
				if( isset( $instance['show_dates'] ) ) $layers_post_meta_to_display[] = 'date';
				if( isset( $instance['show_author'] ) ) $layers_post_meta_to_display[] = 'author';
				if( isset( $instance['show_categories'] ) ) $layers_post_meta_to_display[] = 'categories';
				if( isset( $instance['show_tags'] ) ) $layers_post_meta_to_display[] = 'tags';

				// Apply the advanced widget styling
				$this->apply_widget_advanced_styling( $widget_id, $instance );

				/**
				 * Generate Classes
				 */
				$widget_container_class = array();
				$widget_container_class[] = 'widget';
				$widget_container_class[] = 'widget-post-carousel';
				$widget_container_class[] = 'content-vertical-massive';
				$widget_container_class[] = ( 'on' == $this->check_and_return( $instance , 'design', 'background', 'darken' ) ? 'darken' : '' );
				$widget_container_class[] = $this->check_and_return( $instance , 'design', 'advanced', 'customclass' ); // Apply custom class from design-bar's advanced control.

				$widget_container_class = apply_filters( 'layers_post_carousel_widget_container_class', $widget_container_class, $this, $instance );
				$widget_container_class = implode( ' ', $widget_container_class );

				// Custom Anchor
				echo $this->custom_anchor( $instance ); ?>

				<div id="<?php echo $widget_id; ?>" class="<?php echo esc_attr( $widget_container_class ); ?>" <?php $this->selective_refresh_atts( $args ); ?>>

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

					<div class="post-carousel-row <?php echo $this->get_widget_layout_class( $instance ); ?>">
						<div class="grid">
							<?php
							if( $post_query->have_posts() ) :
								while( $post_query->have_posts() ) :

									$post_query->the_post();

									/**
									 * Generate Classes
									 */
									$classes = array();
									$classes[] = $span_class;
									$classes[] = 'column' . ( 'on' != $this->check_and_return( $instance, 'design', 'gutter' ) ? '-flush' : '' );
									$classes[] = ( 'overlay' == $this->check_and_return( $instance, 'design', 'text_style' ) && has_post_thumbnail() ) ? 'with-overlay' : 'regular' ;
									$classes[] = ( NULL !== $this->check_and_return( $instance, 'design', 'column-background-color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $instance, 'design', 'column-background-color' ) ) ) ? 'invert' : '' ;
									$classes = implode( ' ' , $classes );
									?>
									<article id="post-<?php the_ID(); ?>" class="layers-masonry-column thumbnail <?php echo esc_attr( $classes ); ?>" data-cols="<?php echo $col_count; ?>">

										<?php // Layers Featured Media
										if( isset( $instance['design']['show_media'] ) ) {
											echo layers_post_featured_media(
												array(
													'postid' => get_the_ID(),
													'wrap_class' => 'thumbnail-media' .  ( ( isset( $instance['design'][ 'imageratios' ] ) && 'image-round' == $instance['design'][ 'imageratios' ] ) ? ' image-rounded' : '' ),
													'size' => $use_image_ratio,
													'hide_href' => false
												)
											);
										} ?>
										<?php if( isset( $instance['design']['show_titles'] ) || isset( $instance['design']['show_excerpts'] ) ) { ?>
											<div class="thumbnail-body">
												<div class="overlay">
													<?php if( isset( $instance['design']['show_titles'] ) ) { ?>
														<header class="article-title">
															<h4 class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
														</header>
													<?php } ?>
													<?php if( isset( $instance['design']['show_excerpts'] ) ) {
														if( isset( $instance['design']['excerpt_length'] ) && '' == $instance['design']['excerpt_length'] ) {
															echo '<div class="excerpt">';
																the_content();
															echo '</div>';
														} else if( isset( $instance['design']['excerpt_length'] ) && 0 != $instance['design']['excerpt_length'] && strlen( get_the_excerpt() ) > $instance['design']['excerpt_length'] ){
															echo '<div class="excerpt">' . substr( get_the_excerpt() , 0 , $instance['design']['excerpt_length'] ) . '&#8230;</div>';
														} else if( '' != get_the_excerpt() ){
															echo '<div class="excerpt">' . get_the_excerpt() . '</div>';
														}
													}; ?>
													<?php if( 'overlay' != $this->check_and_return( $instance, 'design', 'text_style' ) ) { ?>
														<?php layers_post_meta( get_the_ID(), $layers_post_meta_to_display, 'footer' , 'meta-info ' . ( NULL !== $this->check_and_return( $instance, 'design', 'column-background-color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $instance, 'design', 'column-background-color' ) ) ? 'invert' : '' ) );?>
													<?php } // Don't show meta if we have chosen overlay ?>
													<?php if( isset( $instance['design']['show_call_to_action'] ) && $this->check_and_return( $instance, 'design', 'call_to_action' ) ) { ?>

														<?php
														/**
														 * Generate Classes
														 */
														$classes = array();

														// Button Size
														if ( $this->check_and_return( $instance, 'design', 'buttons-size' ) ) {
															$classes[] = 'btn-' . $this->check_and_return( $instance, 'design', 'buttons-size' );
														}

														// Button Width
														if ( $this->check_and_return( $instance, 'design', 'buttons-full-width' ) ) {
															$classes[] = 'btn-full';
														}

														$classes = implode( ' ', $classes );
														?>
														<a
															href="<?php the_permalink(); ?>"
															class="button <?php echo esc_attr( $classes ); ?>"
															<?php echo ( 'new' == $this->check_and_return( $instance, 'design', 'buttons-target' ) ) ? 'target="_blank"' : ''; ?>
															>
															<?php echo $instance['design']['call_to_action']; ?>
														</a>

													<?php } ?>
												</div>
											</div>
										<?php } ?>

									</article>
									<?php
								endwhile;
							endif;

							if( isset( $instance['design']['navigation_dots'] ) ) { ?>
								<div class="swiper-pagination"></div>
							<?php }
							if( isset( $instance['design']['navigation_arrows'] ) ) { ?>
								<div class="arrows">
									<a href="" class="l-left-arrow animate"></a>
									<a href="" class="l-right-arrow animate"></a>
								</div>
							<?php } ?>
						</div>
					</div>

					<?php // Print the Inline Styles for this Widget
					if( method_exists( $this, 'print_inline_css' ) )
						$this->print_inline_css();

					/**
					 * Slider javascript initialize
					 */
					$post_swiper_js_obj = str_replace( '-' , '_' , $this->get_layers_field_id( 'slider' ) ); ?>

					<script>
						jQuery(function($){

							<?php echo $post_swiper_js_obj; ?> = layers_pro_init_post_carousel( '#<?php echo $widget_id; ?>', {
								bulletClass: 'swiper-pagination-switch',
								bulletActiveClass: 'swiper-active-switch swiper-visible-switch',

								<?php if( isset( $instance['design']['navigation_dots'] ) ) { ?>
									pagination : '#<?php echo $widget_id; ?> .swiper-pagination',
								<?php } ?>

								<?php if( isset( $instance['design']['navigation_arrows'] ) ) { ?>
									prevButton: '#<?php echo $widget_id; ?> .l-left-arrow',
									nextButton: '#<?php echo $widget_id; ?> .l-right-arrow',
								<?php } ?>

								<?php if( isset( $instance['design']['autoplay_slides'] ) ) { ?>
									autoplay : <?php echo ( isset( $instance['design']['slide_interval'] ) ) ? ( 1000 * $instance['design']['slide_interval'] ) : 3000 ; ?>,
								<?php } ?>

								<?php if( isset( $instance['design']['slider_type'] ) && 'one-by-one' == $instance['design']['slider_type'] ) { ?>
									slidesPerGroup: 1,
								<?php } ?>
							});
						})
					</script>

				</div>
				<?php
				// Reset WP_Query
				wp_reset_postdata();

			}

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
			*  Widget form
			*
			* We use regulage HTML here, it makes reading the widget much easier than if we used just php to echo all the HTML out.
			*
			*/
			function form( $instance ){

				// Use defaults if $instance is empty.
				if( empty( $instance ) && ! empty( $this->defaults ) ) {
					$instance = wp_parse_args( $instance, $this->defaults );
				}

				// Mix in new/unset defaults on every instance load (NEW)
				$instance = $this->apply_defaults( $instance );

				$this->widget = $instance;

				$this->design_bar(
					'side', // CSS Class Name
					array(
						'name' => $this->get_layers_field_name( 'design' ),
						'id' => $this->get_layers_field_id( 'design' ),
						'widget_id' => $this->widget_id,
					), // Widget Object
					$instance, // Widget Values
					apply_filters( 'layers_post_carousel_widget_design_bar_components', array( // Components
						'layout',
						'columns',
						'carousel' => array(
							'icon-css' => 'icon-slider',
							'label' => __( 'Carousel', 'layers-pro' ),
							'elements' => array(
								/*
								'slider_type' => array(
									'type' => 'select',
									'name' => $this->get_layers_field_name( 'design', 'slider_type' ),
									'id' => $this->get_layers_field_id( 'design', 'slider_type' ),
									'value' => ( isset( $instance['design']['slider_type'] ) ) ? $instance['design']['slider_type'] : NULL,
									'label' => __( 'Slider Type' , 'layers-pro' ),
									'options' => array(
										'one-by-one' => __( 'Slide one by one' , 'layers-pro' ),
										'set-by-set' => __( 'Slide set by set' , 'layers-pro' ),
									),
								),
								*/
								'navigation_arrows' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'navigation_arrows' ),
									'id' => $this->get_layers_field_id( 'design', 'navigation_arrows' ),
									'value' => ( isset( $instance['design']['navigation_arrows'] ) ) ? $instance['design']['navigation_arrows'] : NULL,
									'label' => __( 'Show Slider Arrows' , 'layers-pro' ),
								),
								'navigation_arrows_color' => array(
									'type' => 'color',
									'name' => $this->get_layers_field_name( 'design', 'navigation_arrows_color' ),
									'id' => $this->get_layers_field_id( 'design', 'navigation_arrows_color' ),
									'value' => ( isset( $instance['design']['navigation_arrows_color'] ) ) ? $instance['design']['navigation_arrows_color'] : NULL,
									'label' => __( 'Slider Controls Color' , 'layers-pro' ),
									'data' => array( 'show-if-selector' => '#' . $this->get_layers_field_id( 'design', 'navigation_arrows' ), 'show-if-value' => 'true' ),
								),
								'navigation_dots' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'navigation_dots' ),
									'id' => $this->get_layers_field_id( 'design', 'navigation_dots' ),
									'value' => ( isset( $instance['design']['navigation_dots'] ) ) ? $instance['design']['navigation_dots'] : NULL,
									'label' => __( 'Show Slider Dots' , 'layers-pro' ),
								),
								'autoplay_slides' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'autoplay_slides' ),
									'id' => $this->get_layers_field_id( 'design', 'autoplay_slides' ),
									'value' => ( isset( $instance['design']['autoplay_slides'] ) ) ? $instance['design']['autoplay_slides'] : NULL,
									'label' => __( 'Autoplay Slides' , 'layers-pro' ),
								),
								'slide_interval' => array(
									'type' => 'number',
									'name' => $this->get_layers_field_name( 'design', 'slide_interval' ),
									'id' => $this->get_layers_field_id( 'design', 'slide_interval' ),
									'value' => ( isset( $instance['design']['slide_interval'] ) ) ? $instance['design']['slide_interval'] : NULL,
									'label' => __( 'Slide Interval (seconds)' , 'layers-pro' ),
									'data' => array( 'show-if-selector' => '#' . $this->get_layers_field_id( 'design', 'autoplay_slides' ), 'show-if-value' => 'true' ),
								),
							),
						),
						'display' => array(
							'icon-css' => 'icon-display',
							'label' => __( 'Display', 'layers-pro' ),
							'elements' => array(
								'text_style' => array(
									'type' => 'select',
									'name' => $this->get_layers_field_name( 'design', 'text_style' ),
									'description' => __( 'All posts must have featured-images', 'layers-pro' ),
									'id' => $this->get_layers_field_id( 'design', 'text_style' ),
									'value' => ( isset( $instance['design']['text_style'] ) ) ? $instance['design']['text_style'] : NULL,
									'label' => __( 'Title &amp; Excerpt Position' , 'layers-pro' ),
									'options' => array(
											'regular' => __( 'Regular' , 'layers-pro' ),
											'overlay' => __( 'Overlay' , 'layers-pro' )
									),
								),
								'show_media' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'show_media' ),
									'id' => $this->get_layers_field_id( 'design', 'show_media' ),
									'value' => ( isset( $instance['design']['show_media'] ) ) ? $instance['design']['show_media'] : NULL,
									'label' => __( 'Show Featured Images' , 'layers-pro' ),
								),
								'show_titles' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'show_titles' ),
									'id' => $this->get_layers_field_id( 'design', 'show_titles' ),
									'value' => ( isset( $instance['design']['show_titles'] ) ) ? $instance['design']['show_titles'] : NULL,
									'label' => __( 'Show  Post Titles' , 'layers-pro' ),
								),
								'show_excerpts' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'show_excerpts' ),
									'id' => $this->get_layers_field_id( 'design', 'show_excerpts' ),
									'value' => ( isset( $instance['design']['show_excerpts'] ) ) ? $instance['design']['show_excerpts'] : NULL,
									'label' => __( 'Show Post Excerpts' , 'layers-pro' ),
								),
								'excerpt_length' => array(
									'type' => 'number',
									'name' => $this->get_layers_field_name( 'design', 'excerpt_length' ),
									'id' => $this->get_layers_field_id( 'design', 'excerpt_length' ),
									'min' => 0,
									'max' => 10000,
									'value' => ( isset( $instance['design']['excerpt_length'] ) ) ? $instance['design']['excerpt_length'] : NULL,
									'label' => __( 'Excerpts Length' , 'layers-pro' ),
									'data' => array( 'show-if-selector' => '#' . $this->get_layers_field_id( 'design', 'show_excerpts' ), 'show-if-value' => 'true' ),
								),
								'show_dates' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'show_dates' ),
									'id' => $this->get_layers_field_id( 'design', 'show_dates' ),
									'value' => ( isset( $instance['design']['show_dates'] ) ) ? $instance['design']['show_dates'] : NULL,
									'label' => __( 'Show Post Dates' , 'layers-pro' ),
								),
								'show_author' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'show_author' ),
									'id' => $this->get_layers_field_id( 'design', 'show_author' ),
									'value' => ( isset( $instance['design']['show_author'] ) ) ? $instance['design']['show_author'] : NULL,
									'label' => __( 'Show Post Author' , 'layers-pro' ),
								),
								'show_tags' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'show_tags' ),
									'id' => $this->get_layers_field_id( 'design', 'show_tags' ),
									'value' => ( isset( $instance['design']['show_tags'] ) ) ? $instance['design']['show_tags'] : NULL,
									'label' => __( 'Show Tags' , 'layers-pro' ),
								),
								'show_categories' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'show_categories' ),
									'id' => $this->get_layers_field_id( 'design', 'show_categories' ),
									'value' => ( isset( $instance['design']['show_categories'] ) ) ? $instance['design']['show_categories'] : NULL,
									'label' => __( 'Show Categories' , 'layers-pro' ),
								),
								'show_call_to_action' => array(
									'type' => 'checkbox',
									'name' => $this->get_layers_field_name( 'design', 'show_call_to_action' ),
									'id' => $this->get_layers_field_id( 'design', 'show_call_to_action' ),
									'value' => ( isset( $instance['design']['show_call_to_action'] ) ) ? $instance['design']['show_call_to_action'] : NULL,
									'label' => __( 'Show "Read More" Buttons' , 'layers-pro' ),
								),
								'call_to_action' => array(
									'type' => 'text',
									'name' => $this->get_layers_field_name( 'design', 'call_to_action' ),
									'id' => $this->get_layers_field_id( 'design', 'call_to_action' ),
									'value' => ( isset( $instance['design']['call_to_action'] ) ) ? $instance['design']['call_to_action'] : NULL,
									'label' => __( '"Read More" Text' , 'layers-pro' ),
									'data' => array( 'show-if-selector' => '#' . $this->get_layers_field_id( 'design', 'show_call_to_action' ), 'show-if-value' => 'true' ),
								),
							),
						),
						'imageratios',
						'background',
						'advanced',
					), $this, $instance )
				); ?>
				<div class="layers-container-large">

					<?php $this->form_elements()->header( array(
						'title' =>  __( 'Post Carousel' , 'layers-pro' ),
						'icon_class' =>'post'
					) ); ?>

					<section class="layers-accordion-section layers-content">

						<div class="layers-row layers-push-bottom">
							<div class="layers-form-item">

								<?php echo $this->form_elements()->input(
									array(
										'type' => 'text',
										'name' => $this->get_layers_field_name( 'title' ),
										'id' => $this->get_layers_field_id( 'title' ),
										'placeholder' => __( 'Enter title here' , 'layers-pro' ),
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
									apply_filters( 'layers_post_carousel_widget_inline_design_bar_components', array( // Components
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
										'placeholder' => __( 'Short Excerpt' , 'layers-pro' ),
										'value' => ( isset( $instance['excerpt'] ) ) ? $instance['excerpt'] : NULL,
										'class' => 'layers-textarea layers-large',
									)
								); ?>
							</div>

							<?php // Grab the terms as an array and loop 'em to generate the $options for the input
							$terms = get_terms( $this->taxonomy , array( 'hide_empty' => false ) );
							if( !is_wp_error( $terms ) ) { ?>
								<div class="layers-form-item">
									<label for="<?php echo $this->get_layers_field_id( 'category' ); ?>"><?php echo __( 'Category to Display' , 'layers-pro' ); ?></label>
									<?php $category_options[ 0 ] = __( 'All' , 'layers-pro' );
									foreach ( $terms as $t ) $category_options[ $t->term_id ] = $t->name;
									echo $this->form_elements()->input(
										array(
											'type' => 'select',
											'name' => $this->get_layers_field_name( 'category' ),
											'id' => $this->get_layers_field_id( 'category' ),
											'placeholder' => __( 'Select a Category' , 'layers-pro' ),
											'value' => ( isset( $instance['category'] ) ) ? $instance['category'] : NULL,
											'options' => $category_options,
										)
									); ?>
								</div>
							<?php } // if !is_wp_error ?>

							<div class="layers-form-item">
								<label for="<?php echo $this->get_layers_field_id( 'posts_per_page' ); ?>"><?php echo __( 'Number of items to show' , 'layers-pro' ); ?></label>
								<?php $select_options[ '-1' ] = __( 'Show All' , 'layers-pro' );
								$select_options = $this->form_elements()->get_incremental_options( $select_options , 1 , 20 , 1);
								echo $this->form_elements()->input(
									array(
										'type' => 'number',
										'name' => $this->get_layers_field_name( 'posts_per_page' ),
										'id' => $this->get_layers_field_id( 'posts_per_page' ),
										'value' => ( isset( $instance['posts_per_page'] ) ) ? $instance['posts_per_page'] : NULL,
										'min' => '-1',
										'max' => '100',
									)
								); ?>
							</div>

							<div class="layers-form-item">
								<label for="<?php echo $this->get_layers_field_id( 'order' ); ?>"><?php echo __( 'Sort by' , 'layers-pro' ); ?></label>
								<?php echo $this->form_elements()->input(
									array(
										'type' => 'select',
										'name' => $this->get_layers_field_name( 'order' ),
										'id' => $this->get_layers_field_id( 'order' ),
										'value' => ( isset( $instance['order'] ) ) ? $instance['order'] : NULL,
										'options' => $this->form_elements()->get_sort_options(),
									)
								); ?>
							</div>
						</div>
					</section>

				</div>
			<?php } // Form
		} // Class

	}

	// Add our function to the widgets_init hook.
	register_widget( 'Layers_Carousel_Widget' );
}
add_action( 'widgets_init', 'layers_carousel_widget_init', 40 );
