/**
 * Layers Customizer
 */
( function( exports, $ ) {

	"use strict";

	// Check if customizer exists
	if ( ! wp || ! wp.customize ) return;

	// WordPress Stuff
	var	api = wp.customize,
		Previewer;

	// Global Elements
	var	$save_button = $( '#customize-header-actions input#save' ),
		$save_spinner = $( '#customize-header-actions .spinner' );

	// Global Vars
	var	$global_width = layers_devkit_settings.desktop_width,
		$global_height = layers_devkit_settings.desktop_height,
		$global_className = 'layers-devkit-tab-css-desktop',
		$global_grow_if_smaller = true,
		$global_scroll_if_bigger = false,
		$global_state = 'closed';

	// New Customizer Previewer class
	api.LayersDevKitCustomizerPreviewer = {

		init: function () {

			var self = this; // cache previewer reference.
			
			/**
			 * Init CodeMirror Panels.
			 */
			var codeMirrorJs = new CodeMirror( document.getElementById('layers-devkit-tab-js-main-tab'), {
				value: $( '#layers-custom-js' ).val(),
				mode:  "javascript",
				lineNumbers: true,
				theme: layers_devkit_settings.theme,
				indentUnit: 4,
				indentWithTabs: true,
				autoCloseBrackets: true,
				gutters: ( Boolean( layers_devkit_settings.linting ) ) ? ["CodeMirror-lint-markers"] : [] ,
				lint: Boolean( layers_devkit_settings.linting ),
			});
			$( '#layers-devkit-run' ).click(function(){
				$( '#layers-custom-js' ).val( codeMirrorJs.getValue() ).change();
			});

			var codeMirrorCssMain = new CodeMirror( document.getElementById('layers-devkit-tab-css-main-tab'), {
				value: $( '#layers-custom-css-main' ).val(),
				mode:  "css",
				lineNumbers: true,
				theme: layers_devkit_settings.theme,
				indentUnit: 4,
				indentWithTabs: true,
				autoCloseBrackets: true,
				gutters: ( Boolean( layers_devkit_settings.linting ) ) ? ["CodeMirror-lint-markers"] : [] ,
				lint: Boolean( layers_devkit_settings.linting ),
			});
			codeMirrorCssMain.on( "change", update_css_control );
			if ( typeof Inlet == 'function' ) Inlet(codeMirrorCssMain);

			var codeMirrorCssMobile = new CodeMirror( document.getElementById('layers-devkit-tab-css-mobile-tab'), {
				value: $( '#layers-custom-css-mobile' ).val(),
				mode:  "css",
				lineNumbers: true,
				theme: layers_devkit_settings.theme,
				indentUnit: 4,
				indentWithTabs: true,
				autoCloseBrackets: true,
				gutters: ( Boolean( layers_devkit_settings.linting ) ) ? ["CodeMirror-lint-markers"] : [] ,
				lint: Boolean( layers_devkit_settings.linting ),
			});
			codeMirrorCssMobile.on( "change", update_css_control );
			if ( typeof Inlet == 'function' ) Inlet(codeMirrorCssMobile);

			var codeMirrorCssTablet = new CodeMirror( document.getElementById('layers-devkit-tab-css-tablet-tab'), {
				value: $( '#layers-custom-css-tablet' ).val(),
				mode:  "css",
				lineNumbers: true,
				theme: layers_devkit_settings.theme,
				indentUnit: 4,
				indentWithTabs: true,
				autoCloseBrackets: true,
				gutters: ( Boolean( layers_devkit_settings.linting ) ) ? ["CodeMirror-lint-markers"] : [] ,
				lint: Boolean( layers_devkit_settings.linting ),
			});
			codeMirrorCssTablet.on( "change", update_css_control );
			if ( typeof Inlet == 'function' ) Inlet(codeMirrorCssTablet);

			var codeMirrorCssDesktop = new CodeMirror( document.getElementById('layers-devkit-tab-css-desktop-tab'), {
				value: $( '#layers-custom-css-desktop' ).val(),
				mode:  "css",
				lineNumbers: true,
				theme: layers_devkit_settings.theme,
				indentUnit: 4,
				indentWithTabs: true,
				autoCloseBrackets: true,
				gutters: ( Boolean( layers_devkit_settings.linting ) ) ? ["CodeMirror-lint-markers"] : [] ,
				lint: Boolean( layers_devkit_settings.linting ),
			});
			codeMirrorCssDesktop.on( "change", update_css_control );
			if ( typeof Inlet == 'function' ) Inlet(codeMirrorCssDesktop);
			
			// Collect the CSS code.
			var $collect_css = '';
			
			// Set empty start value's
			var	$mainCSS = '',
				$desktopCSS = '',
				$tabletCSS = '',
				$mobileCSS = '';
			
			// Cache elements, so we avoid needing to re-slect them each time the `change` event
			// is triggered - which is regularaly, hence very bad for performance.
			
			var $wp_controls_panel = jQuery('#widgets-right');
			var $wp_widgets_panel = jQuery('#accordion-panel-widgets');
			
			var $css_main_input    = $('#layers-custom-css-main');
			var $css_desktop_input = $('#layers-custom-css-desktop');
			var $css_tablet_input  = $('#layers-custom-css-tablet');
			var $css_mobile_input  = $('#layers-custom-css-mobile');
			var $css_custom_input  = $('#layers-custom-css');
			
			var $css_desktop_is_active = ( 0 !== $( '#layers-devkit-tab-css-desktop' ).length );
			var $css_tablet_is_active  = ( 0 !== $( '#layers-devkit-tab-css-tablet' ).length );
			var $css_mobile_is_active  = ( 0 !== $( '#layers-devkit-tab-css-mobile' ).length );
			
			
			// Update Controls on code changes
			function update_css_control() {

				$collect_css = '';
				
				// Main CSS is always on.
				$mainCSS = codeMirrorCssMain.getValue();
				if ( '' !== $mainCSS ) {
					$collect_css += '\n';
					$collect_css += '\n';
					$collect_css += '\/**\n';
					$collect_css += '* Layers DevKit Custom Main CSS\n';
					$collect_css += '*\/\n';
					$collect_css += $mainCSS + '\n';
				}
				
				// Check if desktop CSS is active, then collect it. and add it to the main CSS.
				$desktopCSS = codeMirrorCssDesktop.getValue();
				if ( $css_desktop_is_active ) {
					if ( '' !== $desktopCSS ) {
						$collect_css += '\n';
						$collect_css += '\/**\n';
						$collect_css += '* Layers DevKit Custom Desktop CSS\n';
						$collect_css += '*\/\n';
						$collect_css += '@media only screen and (min-width: ' + ( parseInt( layers_devkit_settings.tablet_width ) + 1 ) + 'px){\n';
						$collect_css += '\t' + $desktopCSS + '\n';
						$collect_css += '}\n';
					}
				}
				
				// Check if tablet CSS is active, then collect it. and add it to the main CSS.
				$tabletCSS = codeMirrorCssTablet.getValue();
				if ( $css_tablet_is_active ) {
					if ( '' !== $tabletCSS ) {
						$collect_css += '\n';
						$collect_css += '\/**\n';
						$collect_css += '* Layers DevKit Custom Tablet CSS\n';
						$collect_css += '*\/\n';
						$collect_css += '@media only screen and (min-width: ' + ( parseInt( layers_devkit_settings.mobile_width ) + 1 ) + 'px) and (max-width: ' + layers_devkit_settings.tablet_width + 'px){\n';
						$collect_css += '\t' + $tabletCSS + '\n';
						$collect_css += '}\n';
					}
				}
				
				// Check if mobile CSS is active, then collect it. and add it to the main CSS.
				$mobileCSS = codeMirrorCssMobile.getValue();
				if ( $css_mobile_is_active ) {
					if ( '' !== $mobileCSS ) {
						$collect_css += '\n';
						$collect_css += '\/**\n';
						$collect_css += '* Layers DevKit Custom Mobile CSS\n';
						$collect_css += '*\/\n';
						$collect_css += '@media only screen and (max-width: ' + layers_devkit_settings.mobile_width + 'px){\n';
						$collect_css += '\t' + $mobileCSS + '\n';
						$collect_css += '}\n';
					}
				}

				// Dispence change event so WordPress knows the fields have changed when it comes to saving
				$css_main_input.val( $mainCSS ).change();
				$css_desktop_input.val( $desktopCSS ).change();
				$css_tablet_input.val( $tabletCSS ).change();
				$css_mobile_input.val( $mobileCSS ).change();

				// Ping a change on the main custom-css field so the preview is postMessage updated
				$css_custom_input.val( $collect_css ).change();
			}
			update_css_control();


			// Move Developer Panel to it's new location in the customizer controls
			// This must be done after init of CodeMirror to avoid display:none issues
			$( 'body' ).prepend( $( '.layers-devkit-panel' ).css({ opacity: '', zIndex: '' }) );
			
			$('#customize-header-actions').append( $('.layers-devkit-button') );
			$( '.layers-devkit-button' ).css({ opacity: '', zIndex: '' });
			//$('.layers-devkit-button').css({ 'display':'block', 'visibility':'visible' });

			// Add DevKit Link to Layers Top Action Buttons
			$( '.layers-customizer-nav > li > ul' ).append( $('.top-nav-button').css({ opacity: '', zIndex: '' }) );

			// Edit Code button
			$(document).on( 'click', '#layers-devkit-button, .devkit-button, .customize-controls-layers-button-css, .customize-controls-layers-button-devkit, .layers-devkit-button', function( event ){

				// Close any open widget forms, especially our wide Layers forms
				$( '.customize-control-widget_form.expanded .widget-top' ).click();

				// Open Devit
				toggle_devkit_panel();

				return false;
			});

			// Main Back button
			$(document).on( 'click', '#devkit-close-button', function(){
				
				toggle_devkit_panel();
			});

			// Initialise Tabs
			$( '.layers-devkit-tab' ).click(function(){

				// Get elements
				var $tab_button = $(this);
				var $tab_content = $( '#' + $tab_button.attr('id') + '-tab' );

				// Handle Buttons toggle
				//$tab_button.parent().find( '.layers-devkit-tab' ).removeClass('active');
				$( '.layers-devkit-tab' ).removeClass('active');
				$tab_button.addClass( 'active' );

				// Handle Content toggle
				$( '.layers-devkit-code-panel-holder' ).removeClass('active');
				$tab_content.parent().append( $tab_content.addClass('active') );

				// Handle Responsive displays
				var $tab_text = $tab_button.attr('id');
				if ( 'layers-devkit-tab-css-mobile' == $tab_text ) {

					// 480, 568
					set_iframe_size( layers_devkit_settings.mobile_width, layers_devkit_settings.mobile_height, $tab_text, false , true ); // grow_if_smaller, scroll_if_bigger
				}
				else if ( 'layers-devkit-tab-css-tablet' == $tab_text ) {

					// 767, 1024
					set_iframe_size( layers_devkit_settings.tablet_width, layers_devkit_settings.tablet_height, $tab_text, false, true ); // grow_if_smaller, scroll_if_bigger
				}
				else if ( 'layers-devkit-tab-css-desktop' == $tab_text ) {

					// 1200, 1000
					set_iframe_size( layers_devkit_settings.desktop_width, layers_devkit_settings.desktop_height, $tab_text, true, true ); // grow_if_smaller, scroll_if_bigger
				}
				else {

					// GlobalCss & Javascript buttons clicked
					set_iframe_size( layers_devkit_settings.desktop_width, layers_devkit_settings.desktop_height, $tab_text, true, false ); // grow_if_smaller, scroll_if_bigger
				}
			});
			$( '.layers-devkit-tab' ).eq(0).click();


			// Testing - auto open devkit panel
			//open_devkit_panel();

			var reset_position;

			function toggle_devkit_panel(){

				if( ! $( 'body' ).hasClass('layers-devkit-open') ){
					open_devkit_panel();
				} else {
					close_devkit_panel();
				}
			}

			function open_devkit_panel() {

				$( 'body' ).addClass( 'layers-devkit-open layers-devkit-animations' );

				// Move Actions
				$( '.layers-devkit-action-bar .layers-devkit-wp-actions' ).append( $save_spinner );
				$( '.layers-devkit-action-bar .layers-devkit-wp-actions' ).append( $save_button );

				setTimeout( function() {

					set_iframe_size( $global_width, $global_height, $global_className, $global_grow_if_smaller, $global_scroll_if_bigger );
				}, 300 );
			}

			function close_devkit_panel() {

				$( 'body' ).removeClass('layers-devkit-open');

				reset_position = setTimeout(function(){

					$( 'body' ).removeClass('layers-devkit-animations');

				}, 400);

				// Move Actions
				if ( $( '#customize-header-actions .primary-actions' ).length ) { // Newer WP versions moved them here.
					$( '#customize-header-actions .primary-actions' ).prepend( $save_spinner );
					$( '#customize-header-actions .primary-actions' ).prepend( $save_button );
				}
				else{
					$( '#customize-header-actions' ).append( $save_spinner );
					$( '#customize-header-actions' ).append( $save_button );
				}

				reset_responsive_viewport( true );
			}

			function set_iframe_size( $width, $height, $className, $grow_if_smaller, $scroll_if_bigger ) {

				// console.log( 'width: ' + $width + ', height: ' + $height + ', className: ' + $className + ', grow_if_smaller: ' + $grow_if_smaller );

				if( null == $width && null == $height ){
					$width = $global_width;
					$height = $global_height;
					$className = $global_className;
					$grow_if_smaller = $global_grow_if_smaller;
					$scroll_if_bigger = $global_scroll_if_bigger;
				}

				$global_width = $width;
				$global_height = $height;
				$global_className = $className;
				$global_grow_if_smaller = $grow_if_smaller;
				$global_scroll_if_bigger = $scroll_if_bigger;

				if ( null == $width || null == $width ) {
					return false;
				}

				var $customizer_holder = jQuery( '#customize-preview' );
				var $customizer_iframe = jQuery( '#customize-preview iframe' );
				var $customizer_content = jQuery( '#customize-preview iframe' ).contents();

				if ( $width < $customizer_holder.width() ) {

					/*
					 * Smaller (than preview area)
					 */

					reset_responsive_viewport( true ); //remove holders too

					$customizer_holder.addClass( 'layers-responsive-preview' );
					$customizer_holder.addClass( $className );

					if ( true != $grow_if_smaller ) {

						$customizer_holder.addClass( 'layers-responsive-preview-smaller' );

						// Reduce height if too high for viewer
						if ( $height > $customizer_holder.height() - 32 ) $height = $customizer_holder.height() - 32;

						// Make sure width and height are even - to avoid blurring
						if ( $width % 2 ) $width = $width - 1;
						if ( $height % 2 ) $height = $height - 1;

						$customizer_iframe.css({
							width: $width,
							height: $height
						});
					}
					else {

						$customizer_holder.addClass( 'layers-responsive-preview-bigger' );
					}
				}
				else {

					/*
					 * Bigger (than preview area)
					 */

					reset_responsive_viewport();

					$customizer_holder.addClass( 'layers-responsive-preview' );
					$customizer_holder.addClass( 'layers-responsive-preview-bigger' );
					$customizer_holder.addClass( $className );

					if ( true == $scroll_if_bigger ) {

						// Not Auto

						if ( 0 === $customizer_content.find('.inner_holder').length ) {
							var $elements = $customizer_content.find('body').children().not('script, link, style');
							$customizer_content.find('body').prepend( jQuery("<div>").addClass("outer_holder") );
							$customizer_content.find('.outer_holder').prepend( jQuery("<div>").addClass("inner_holder") );
							$customizer_content.find('.inner_holder').prepend( $elements );
						}

						$customizer_content.find('body').css({
							height: $customizer_content.find('.outer_holder').height() * 1.3
						});

						$customizer_content.find('.outer_holder').scroll(function() {
							$customizer_content.find('html, body').scrollTop( $customizer_content.find('.outer_holder').scrollTop() );
						});

						$customizer_content.find('.outer_holder').css({
							width: $customizer_holder.width(),
							height: $customizer_holder.height(),
							overflow: 'auto',
							position: 'fixed',
							transition: '.3s ease-in-out'
						});
						$customizer_content.find('.inner_holder').css({
							width: $width,
							transition: '.3s ease-in-out'
						});
						$customizer_content.find('html').css({
							overflow: 'hidden'
						});
						$customizer_iframe.css({
							width: $width,
							height: '',
							overflow: ''
						});
					}
				}
			}

			function reset_responsive_viewport ( $remove_holder ) {

				var $customizer_holder = jQuery( '#customize-preview' );
				var $customizer_iframe = jQuery( '#customize-preview iframe' );
				var $customizer_content = jQuery( '#customize-preview iframe' ).contents();

				$customizer_holder.removeClass( 'layers-responsive-preview' );
				$customizer_holder.removeClass( 'layers-responsive-preview-bigger' );
				$customizer_holder.removeClass( 'layers-responsive-preview-smaller' );
				$customizer_holder.removeClass( 'layers-devkit-tab-css-main' );
				$customizer_holder.removeClass( 'layers-devkit-tab-css-mobile' );
				$customizer_holder.removeClass( 'layers-devkit-tab-css-tablet' );
				$customizer_holder.removeClass( 'layers-devkit-tab-css-desktop' );

				// Remove the inner divs
				if ( $customizer_content.find('.inner_holder').length && null !== $remove_holder ) {

					var $elements = $customizer_content.find('.inner_holder').children();
					$customizer_content.find('body').prepend( $elements );
					$customizer_content.find('.outer_holder').remove();
					$customizer_content.find('html').css({ overflow: '' });
				}

				// Reset Customizer Holder
				$customizer_holder.css({
					width: '',
					height: ''
				});

				// Reset Customizer Iframe
				$customizer_iframe.css({
					width: '',
					height: ''
				});

				// Reset Customizer Iframe Content
				$customizer_content.find('body').css({
					height: ''
				});

			}

			// Window Resize Event
			$( window ).resize( function( event ) {

				if( jQuery( '#customize-preview' ).hasClass( 'layers-responsive-preview' ) ){
					set_iframe_size( $global_width, $global_height, $global_className, $global_grow_if_smaller );
				}
			} );

			// layers-loaded event received when the previewer is initialiseds
			this.preview.bind( 'layers-loaded', function( data ) {

				// Only if devkit panel open
				if( $( 'body' ).hasClass('layers-devkit-open') ){
					set_iframe_size( $global_width, $global_height, $global_className, $global_grow_if_smaller );
				}
			} );
		}
	};

	// Cache Preview
	Previewer = api.Previewer;
	api.Previewer = Previewer.extend({
		initialize: function( params, options ) {

			// cache the Preview
			api.LayersDevKitCustomizerPreviewer.preview = this;

			// call the Previewer's initialize function
			Previewer.prototype.initialize.call( this, params, options );
		}
	} );

	// On document ready
	$( function() {

		// Initialize Layers Previewer
		api.LayersDevKitCustomizerPreviewer.init();
	} );

} )( wp, jQuery );
