<?php
/**
* Adeline Theme
* Layers Child Theme
* Theme Functions: Actions & Filters
*
*/

// Change the default excerpt more string
add_filter( 'excerpt_more', 'adeline_excerpt_more' );

function adeline_excerpt_more( $more ) {

		return '...';
}

// Ensure cart contents update when products are added to the cart via AJAX.
add_filter('add_to_cart_fragments', 'layers_woocommerce_cart_fragments');

//Inline Styles Frontend
add_action( 'wp_enqueue_scripts','adeline_inline_styles' , 60 );

function adeline_inline_styles() {

	$adeline_featured_image = wp_get_attachment_url( get_post_thumbnail_id() );
	$adeline_title_container_color = layers_get_theme_mod( 'title-container-color', FALSE );
	$adeline_footer_color = layers_get_theme_mod( 'color-footer-widgets', FALSE );

		if( ( '' != $adeline_featured_image and 'post' == get_post_type() )
			or ( '' != $adeline_featured_image and is_page_template('template-blank.php') ) ) {

			layers_inline_styles( array(
				'selectors' => array(
					'.featured-title-bg'
					),
				'css' => array(
					'background-image' => 'url(\'' . esc_url($adeline_featured_image) . '\')'
					)
				));
		}

		elseif( '' != layers_get_theme_mod( 'title-container-bg' )) {
			layers_inline_styles( array(
				'selectors' => array(
				'.title-container.featured-title-bg'
				),
				'css' => array(
					'background-image' => 'url(\'' . layers_get_theme_mod( 'title-container-bg' ) . '\')',
				),
			));
		}

		elseif( '' != layers_get_theme_mod( 'title-container-color' )) {
			layers_inline_styles( array(
				'selectors' => array(
				'.title-container.featured-title-bg'
				),
				'css' => array(
					'background-color' => layers_get_theme_mod('title-container-color'),
				),
			));
		}

		// Add Invert if the color is not light
		if ( 'dark' == layers_is_light_or_dark( $adeline_title_container_color ) ){
			add_filter( 'layers_featured_title_bg_class', 'layers_add_invert_class' );
		}

		if ('' != layers_get_theme_mod('color-footer-widgets')) {
			layers_inline_styles( array(
				'selectors' => array(
					'.bgl-footer-widgets'
					),
				'css' => array(
					'background-color' => layers_get_theme_mod('color-footer-widgets')
					),
			));
		}

		if ( 'dark' == layers_is_light_or_dark( $adeline_footer_color ) ){
			add_filter( 'layers_footer_widgets_class', 'layers_add_invert_class' );
		}

		if ('' != layers_get_theme_mod('form-fonts')) {
			layers_inline_styles( array(
				'selectors' => array(
					'h5.section-nav-title, .medium h5.heading, .small h5.heading'
					),
				'css' => array(
					'font-family' => '\'' . layers_get_theme_mod('form-fonts') . '\''
					),
			));
		}

		if ('' != layers_get_theme_mod('site-accent-color')) {
			layers_inline_styles( array(
				'selectors' => array(
					'a:hover, .nav-horizontal li a:hover, .sidebar .widget li a:hover, .header-site.invert .nav-horizontal > ul > li > a:hover'
					),
				'css' => array(
					'color' => layers_get_theme_mod('site-accent-color')
					),
			));

			layers_inline_styles( array(
				'selectors' => array(
					'textarea:focus, input:focus, select:focus, .pagination .current, .pagination a:hover'
					),
				'css' => array(
					'border-color' => layers_get_theme_mod('site-accent-color')
					),
			));

			layers_inline_styles( array(
				'selectors' => array(
					'.sub-menu li a:hover, .header-site.invert .sub-menu li a:hover, .pagination .current, .pagination a:hover, .tagcloud a:hover, .tagcloud a:focus'
					),
				'css' => array(
					'background-color' => layers_get_theme_mod('site-accent-color')
					),
			));
		}

		if ('' != layers_get_theme_mod('main-border-color')) {
			layers_inline_styles( array(
				'selectors' => array(
					'body'),
				'css' => array(
					'background-color' => layers_get_theme_mod('main-border-color')
					),
				));
		}
}


/*
*
* Page Title Background
*
*/
function adeline_bg_page_title() {

	$adeline_featured_image = wp_get_attachment_url( get_post_thumbnail_id() );

	$args_bg_cat = array (
		'posts_per_page'         => '1',
		'ignore_sticky_posts'    => true,
	);

	// The Query
	$query_bg_post = new WP_Query( $args_bg_cat );

	if ( $query_bg_post->have_posts() && '' != $adeline_featured_image) {

		echo '<div class="adeline-overlay-bg" style="background-image: url(\'' . esc_url( $adeline_featured_image ) . '\')"></div>';

	}
	// Restore original Post Data
	wp_reset_postdata();
	}

add_action('layers_after_header_page_title','adeline_bg_page_title');