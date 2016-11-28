/**
 * Layers Customizer Previewer
 */
( function ( wp, $ ) {

	"use strict";

	// Check if customizer exists
	if ( ! wp || ! wp.customize ) return;

	// WordPress Stuff
	var	api = wp.customize,
		Preview;

	// New Customizer Previewer class
	api.LayersDevKitCustomizerPreview = {

		init: function () {
			var self = this; // cache previewer reference

			// layers-loaded event sent when the previewer is initialised
			this.preview.bind( 'active', function() {

				self.preview.send( 'layers-loaded', {dataKey :'dataValue'} );
			} );
		}
	};

	// Cache Preview
	Preview = api.Preview;
	api.Preview = Preview.extend( {
		initialize: function( params, options ) {

			// cache the Preview
			api.LayersDevKitCustomizerPreview.preview = this;

			// call the Preview's initialize function
			Preview.prototype.initialize.call( this, params, options );
		}
	} );

	// On document ready
	$( function () {

		// Initialize Layers Preview
		api.LayersDevKitCustomizerPreview.init();
	} );


	/**
	 * Layers Customizer Controls
	 */

	wp.customize( 'layers-custom-css', function( value ) {
		value.bind( function( newval ) {
			
			var $style_element;
			
			// Look for the native WP Devkit style element.
			if ( $( 'style#devkit-custom-styles-inline-css' ).length ) {
				$style_element = $( 'style#devkit-custom-styles-inline-css' );
			}
			// Look for the Layers Devkit style element.
			if ( $( 'style#layers-custom-styles-inline-css' ).length ) {
				$style_element = $( 'style#layers-custom-styles-inline-css' );
			}
			// If neither exsist then create one to be used for this customizer session.
			if ( ! $style_element ) {
				$style_element = $('<style id="devkit-custom-styles-inline-css" type="text/css" />');
				$('body').append( $style_element );
			}

			// Update the CSS block with the new CSS.
			$style_element.html( newval );
		} );
	} );

	wp.customize( 'layers-custom-js', function( value ) {
		value.bind( function( newval ) {

			try {

				// Insert placeholder where the original script block is, then remove the original.
				$('<div id="layers-custom-js-placeholder"></div>').insertAfter('#layers-custom-js');
				$('#layers-custom-js').remove();

				// Remove any of our previousy inserted temp <script> blocks.
				$('.layers-custom-js-temp').remove();

				// Build the new temp script block to be inserted.
				var $new_script_block = $('<script id="#layers-custom-js" class="layers-custom-js-temp">' + newval + '</script>');

				// Insert the new temp script block - at this point it will error if there's a bug in the JS.
				$new_script_block.insertAfter('#layers-custom-js-placeholder');
			}
			catch ( e ) {

				// Error in the script - let the user know.
				console.log( 'DevKit script error:' );
				console.log( e );
			}

		} );
	} );

} )( window.wp, jQuery );
