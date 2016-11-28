/**
* Content Widget JS file
*
* This file contains functions relating to the Content Widget
 *
 * @package Layers
 * @since Layers 1.0.0
 * Contents
 * 1 - Sortable items
 * 2 - Column Removal & Additions
 * 3 - Column Title Update
 *
 * Author: Obox Themes
 * Author URI: http://www.oboxthemes.com/
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

jQuery(document).ready(function($){

	/**
	* 1 - Sortable items
	*/

	$( document ).on( 'layers-interface-init', '.widget, .layers-accordions', function( e ){
		// 'this' is the widget
		layers_set_events_sortable( $(this) );
	});

	function layers_set_events_sortable( $element_s ){

		$element_s.find( 'ul[id^="events_column_list_"]' ).each( function(){

			$that = $(this);

			$that.sortable({
				placeholder: "layers-sortable-drop",
				handle: ".layers-accordion-title",
				stop: function(e , li){
					// Module UL, looking up from our current target
					$eventsList = li.item.closest( 'ul' );

					// Modules <input>
					$eventsInput = $( '#events_column_ids_input_' + $eventsList.data( 'number' ) );

					// Apply new events order
					$events_guids = [];
					$eventsList.find( 'li.layers-accordion-item' ).each(function(){
						$events_guids.push( $(this).data( 'guid' ) );
					});

					// Trigger change for ajax save
					$eventsInput.val( $events_guids.join() ).layers_trigger_change();
				}
			});
		});
	}

	/**
	* 2 - Column Removal & Additions
	*/

	$(document).on( 'click' , 'ul[id^="events_column_list_"] .icon-trash' , function(e){
		e.preventDefault();

		// "Hi Mom"
		var $that = $(this);

		// Confirmation message @TODO: Make JS confirmation events

		var $remove_events = confirm( contentwidgeti18n.confirm_message );

		if( false === $remove_events ) return;

		// Module UL
		$eventsList = $( '#events_column_list_' + $that.data( 'number' ) );

		// Modules <input>
		$eventsInput = $( '#events_column_ids_input_' + $eventsList.data( 'number' ) );

		// Remove this banner
		$that.closest( '.layers-accordion-item' ).remove();

		// Curate events IDs
		$events_guids = [];

		$eventsList.find( 'li.layers-accordion-item' ).each(function(){
			$events_guids.push( $(this).data( 'guid' ) );
		});

		// Trigger change for ajax save
		$eventsInput.val( $events_guids.join() ).layers_trigger_change();

		// Reset Sortable Items
		layers_set_events_sortable( $that );
	});

	$(document).on( 'click' , '.layers-add-widget-events' , function(e){

		e.preventDefault();

		// "Hi Mom"
		var $that = $(this);

		// Add loading class
		$that.addClass('layers-loading-button');

		// Create the list selector
		$eventsListId = '#events_column_list_' + $that.data( 'number' );

		// Module UL
		$eventsList = $( $eventsListId );

		// Modules <input>
		$eventsInput = $( '#events_column_ids_input_' + $eventsList.data( 'number' ) );

		// Serialize input data
		$serialized_inputs = [];
		$.each(
			$eventsList.find( 'li.layers-accordion-item' ).last().find( 'textarea, input, select' ),
			function( i, input ){
				$serialized_inputs.push( $(input).serialize() );
		});

		$post_data = {
			action: 'layers_event_widget_actions',
			widget_action: 'add',
			id_base: $eventsList.data( 'id_base' ),
			instance: $serialized_inputs.join( '&' ),
			last_guid: ( 0 !== $eventsList.find( 'li.layers-accordion-item' ).length ) ? $eventsList.find( 'li.layers-accordion-item' ).last().data( 'guid' ) : false,
			number: $eventsList.data( 'number' ),
			nonce: layers_widget_params.nonce
		};

		$.post(
			ajaxurl,
			$post_data,
			function(data){

				// Set events
				$events = $(data);

				$events.find('.layers-accordion-section').hide();

				// Append events HTML
				$eventsList.append($events);

				// Append events IDs to the eventss input
				$events_guids = [];
				$eventsList.find( 'li.layers-accordion-item' ).each(function(){
					$events_guids.push( $(this).data( 'guid' ) );
				});

				// Trigger change for ajax save
				$eventsInput.val( $events_guids.join() ).layers_trigger_change();

				// Trigger interface init. will trigger init of elemnts eg colorpickers etc
				$events.trigger('layers-interface-init');

				// Remove loading class
				$that.removeClass('layers-loading-button');

				// Add Open Class to events
				setTimeout( function(){
					$events.find('.layers-accordion-title').trigger('click');
				}, 300 );
			}
		) // $.post

	});

	/**
	* 3 - Module Title Update
	*/

	$(document).on( 'keyup' , 'ul[id^="events_column_list_"] input[id*="-title"]' , function(e){

		// "Hi Mom"
		$that = $(this);

		// Set the string value
		$val = $that.val().toString().substr( 0 , 51 );

		// Set the Title
		$string = ': ' + ( $val.length > 50 ? $val + '...' : $val );

		// Update the accordian title
		$that.closest( '.layers-accordion-item' ).find( 'span.layers-detail' ).text( $string );

	});


}); //jQuery
