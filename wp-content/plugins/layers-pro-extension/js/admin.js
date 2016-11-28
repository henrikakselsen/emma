/**
 * Pro Admin JS file
 *
 * This file contains admin JS functions
 *
 * Contents
 * 1 - Menu Social Icons interface
 */
jQuery(document).ready(function($){

	/**
	 * 1 - Menu Social Icons interface
	 */

	// Init the interface.
	if ( $('.nav-menus-php').length ) {

		// Init the interface to it's final location in Appearance > Menu.
		init_layers_social_menu_icons();
	}
	else{

		// Init the interface to it's final location in Customizer > Menus
		$( document ).on( 'layers-customizer-init', function(){
			init_layers_social_menu_icons();
		});
	}

	function init_layers_social_menu_icons() {

		// We're not in 'Customizer' or 'Appearance > Menu' so don't move, modify, or display the Social Icons. Just Bail.omizer' or 'Appearance > Menu' so don't move, modify, or display the Social Icons. Just Bail.
		if ( 0 == $( '#new-custom-menu-item .button-controls, #customlinkdiv .button-controls' ).length )
			return false;

		// Move Social-Icons interface.
		$('.layer-pro-menu-icons-interface').insertAfter('#new-custom-menu-item .button-controls'); // Customizer
		$('.layer-pro-menu-icons-interface').insertAfter('#customlinkdiv .button-controls'); // Appearance > Menu

		// Display the Social-Icons interface.
		$('.layer-pro-menu-icons-interface').css({ 'display':'block', 'visibility':'visible' });

		// Remove the layers-select-icons element, so the icons don't .activate when clicked.
		$('.layer-pro-menu-icons-interface .layers-icon-wrapper' ).unwrap();

		$(document).on( 'click', '.layer-pro-menu-icons-interface .layers-icon-wrapper', function(){

			var $item_label = $(this);
			var $item_input = $item_label.find('input');

			var $wp_url = $('#custom-menu-item-url');
			var $wp_link_text = $('#custom-menu-item-name');

			$chosen_network = $item_input.val();
			console.log( $chosen_network );
			$network_info = layers_pro_params.social_networks[ $chosen_network ];

			// Populate the Custom-Link inputs.
			if ( $network_info['value'] )
				$wp_url.val( $network_info['value'] );
			else
				$wp_url.val( 'http://' + $network_info['base_url'] );

			// Remove a class left by WP.
			$wp_link_text.removeClass('input-with-default-title').val( $network_info['name'] );
		})
	}


}(jQuery));
