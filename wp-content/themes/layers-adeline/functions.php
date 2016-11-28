<?php
/**
* Adeline Theme
* Layers Child Theme
* Customizer Defaults
*
*/

 // Enqueue Styles
add_action( 'wp_enqueue_scripts', 'adeline_styles' );

if( ! function_exists( 'adeline_styles' ) ) {

	function adeline_styles() {

		// Dequeue Layers Components and Colors CSS to allow proper overrides by requeuing it in the order we want
		wp_dequeue_style('layers-components');
		wp_enqueue_style(
			'layers-components',
			get_template_directory_uri() . '/assets/css/components.css',
			array('layers-framework')
		); // Components

		wp_enqueue_style(
			'layers-parent-style',
			get_template_directory_uri() . '/style.css',
			array()
		); // Layers Parent Style

		wp_enqueue_style(
			'adeline-custom-presets',
			get_stylesheet_directory_uri() . '/assets/css/custom-presets.css',
			array()
		); // Layers Parent Style

        wp_enqueue_style(
        	'font-awesome',
			get_stylesheet_directory_uri() . '/assets/css/font-awesome.min.css',
			array()
        ); // Font Awesome

        wp_enqueue_style(
        	'animate-css',
			get_stylesheet_directory_uri() . '/assets/css/animate.min.css',
			array()
        ); // Animate CSS

	}

}

// Enqueue Scripts
add_action('wp_enqueue_scripts', 'adeline_scripts');

if( ! function_exists( 'adeline_scripts' ) ) {

	function adeline_scripts() {

		wp_enqueue_script(
			'wow-js' . '-custom',
			get_stylesheet_directory_uri() . '/assets/js/wow.min.js',
			array(
				'jquery', // make sure this only loads if jQuery has loaded
			)
		); // This outputs at header

		wp_enqueue_script(
			'child-components' . '-custom',
			get_stylesheet_directory_uri() . '/assets/js/theme.js',
			array(
				'jquery', // make sure this only loads if jQuery has loaded
			), '1.0.0', true // Outputs this at footer
		); // Custom Child Theme jQuery
	}

}

// Customizer Options
require_once get_stylesheet_directory() . '/inc/customizer.php';

// Theme Hooks & Functions
require_once get_stylesheet_directory() . '/inc/theme_functions.php';

// Theme Presets
require_once get_stylesheet_directory() . '/inc/presets.php';

// Instagram Widget
require_once get_stylesheet_directory() . '/widgets/instagram.php';