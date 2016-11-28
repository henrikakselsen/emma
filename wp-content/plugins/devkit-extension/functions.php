<?php
/*
 * Plugin Name: DevKit - Developer Tools for WordPress
 * Version: 1.4
 * Plugin URI: http://www.oboxthemes.com/
 * Description: Seamlessly add and edit your custom CSS and Javascript directly in the WordPress customizer interface
 * Author: Obox
 * Author URI: http://www.oboxthemes.com/
 * Requires at least: 4.0
 * Tested up to: 4.2
 * Text Domain: layers-devkit
 * Domain Path: /lang/
 * Layers Plugin: True
 * Layers Required Version: 1.1.0
 *
 * @package WordPress
 * @author Obox
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( defined( 'SCRIPT_DEBUG' ) && TRUE == SCRIPT_DEBUG ) {
    define( 'LAYERS_DEVKIT_VER', rand( 0 , 100 ) );
} else {
    define( 'LAYERS_DEVKIT_VER', '1.4' );
}

define( 'LAYERS_DEVKIT_SLUG' , 'layers-devkit' );
define( 'LAYERS_DEVKIT_REQUIRED_VERSION' , '1.1.0' );
define( 'LAYERS_DEVKIT_DIR' , plugin_dir_path( __FILE__ ) );
define( 'LAYERS_DEVKIT_URI' , plugin_dir_url( __FILE__ ) );
define( 'LAYERS_DEVKIT_FILE' , __FILE__ );

// Load plugin class files
require_once( 'includes/functions.php' );
require_once( 'includes/class-devkit.php' );
require_once( 'includes/class-settings.php' );

if( !function_exists( 'layers_devkit_init' ) ) {
    // Instantiate Plugin
    function layers_devkit_init() {

        global $layers_devkit, $layers_devkit_settings;

        $layers_devkit = Layers_DevKit::get_instance();

        $layers_devkit_settings = Layers_DevKit_Settings::get_instance();

        // Localization
        load_plugin_textdomain( LAYERS_DEVKIT_SLUG, FALSE, dirname( plugin_basename( __FILE__ ) ) . "/lang/" );
    } // layers_devkit_init
}
add_action( "plugins_loaded", "layers_devkit_init" );