<?php
/**
* Adeline Theme
* Layers Child Theme
* Customizer Defaults
*
*/

//Default Theme Options
add_filter( 'layers_customizer_control_defaults', 'adeline_customizer_defaults' );

function adeline_customizer_defaults( $defaults ){

	$defaults = array(

		'body-fonts' => 'Muli',
		'form-fonts' => 'Montserrat',
		'heading-fonts' => 'Playfair Display',

		'header-menu-layout' => 'header-logo-left',
		'header-logo-size' => 'custom',
		'header-logo-size-custom' => '80',
		'title-background-image' => '116',
		'buttons-primary-border-radius' => '0',
		'buttons-primary-background-color' => '#000000',

		// WooCommerce / StoreKit Defaults
		'woocommerce-show-menu-cart-mini-cart' => '1',
		'woocommerce-product-list-thumbnails-flip' => '1',
		'woocommerce-product-list-products-per-row' => '3',
		'single-left-woocommerce-sidebar' => FALSE,
		'single-right-woocommerce-sidebar' => FALSE,
		'archive-left-woocommerce-sidebar' => FALSE,
		'archive-right-woocommerce-sidebar' => TRUE,
	);

	return $defaults;
}

//Customizer Controls
add_filter( 'layers_customizer_controls', 'adeline_customizer_controls' );

function adeline_customizer_controls( $controls ){

		$adeline_header_layout = array(
			'title-container-bg' => array(
				'type'		=> 'upload',
				'label'		=> __( 'Title Container Background' , 'layerswp' ),
			),
		);

		$adeline_colors = array(
			'color-footer-widgets' => array(
				'type' => 'layers-color',
				'label' => __( 'Footer Widgets Color', 'layerswp' ),
			),
			'title-container-color' => array(
				'type'		=> 'layers-color',
				'label'		=> __( 'Title Container Color' , 'layerswp' ),
				'default'	=> '#2b2b2b'
			),
			'main-border-color' => array(
				'type'		=> 'layers-color',
				'label'		=> __( 'Main Border Color' , 'layerswp' ),
				'default'	=> '#161616'
			),
		);

		$controls['site-colors'] = array_merge(
			$controls['site-colors'],
			$adeline_colors
		);

		$controls['header-layout'] = array_merge(
			$controls['header-layout'],
			$adeline_header_layout
		);


		return $controls;
}