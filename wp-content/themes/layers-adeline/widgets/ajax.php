<?php  /**
 * Widget Ajax
 *
 * This file is used to fetch, using Ajax, and display different parts of the layers widgets
 *
 * @package Layers
 * @since Layers 1.0.0
 */

add_action( 'wp_ajax_layers_event_widget_actions', 'layers_event_widget_actions' );
function layers_event_widget_actions(){

    if( !check_ajax_referer( 'layers-widget-actions', 'nonce', false ) ) die( 'You threw a Nonce exception' ); // Nonce

    $widget = new Layers_Content_Events_Widget();

    if( 'add' == $_POST[ 'widget_action'] ) {

        // Get the previous element's column data
        parse_str(
            urldecode( stripslashes( $_POST[ 'instance' ] ) ),
            $data
        );

        // Get the previous element's column data
        if( isset( $data[ 'widget-' . $_POST[ 'id_base' ] ] ) && isset( $_POST[ 'last_guid' ] ) && is_numeric( $_POST[ 'last_guid' ] ) ) {
            $instance = $data[ 'widget-' . $_POST[ 'id_base' ] ][ $_POST[ 'number' ] ][ 'columns' ][ $_POST[ 'last_guid' ] ];
        } else {
            $instance = NULL;
        }

        $widget->column_item( array( 'id_base' => $_POST[ 'id_base' ] , 'number' => $_POST[ 'number' ] ), NULL, $instance );
    }
    die();
}

add_action( 'admin_enqueue_scripts', 'layers_school_widget_scripts' , 50 );
function layers_school_widget_scripts(){

    // Content Widget
    wp_enqueue_script(
        LAYERS_THEME_SLUG . '-admin-events-widget' ,
        get_stylesheet_directory_uri() . '/widgets/js/events.js' ,
        array(),
        LAYERS_VERSION,
        true
    );
}