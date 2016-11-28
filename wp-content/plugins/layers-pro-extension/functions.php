<?php
/*
 * Plugin Name: Layers Pro - Extended Layers customizability
 * Version: 1.6.8
 * Plugin URI: https://www.layerswp.com/layers-pro/
 * Description: Unlock customizability in the Layers framework.
 * Author: Obox
 * Author URI: https://www.layerswp.com/
 * Requires at least: 4.0
 * Tested up to: 4.1
 * Layers Plugin: True
 * Layers Required Version: 1.5.0
 *
 * Text Domain: layers-pro
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Obox
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define Constants
if ( defined( 'SCRIPT_DEBUG' ) && TRUE == SCRIPT_DEBUG ) {
    define( 'LAYERS_PRO_VER', rand( 0 , 100 ) );
} else {
    define( 'LAYERS_PRO_VER', '1.6.8' );
}
define( 'LAYERS_PRO_SLUG' , 'layers-pro' );
define( 'LAYERS_PRO_REQUIRED_VERSION' , '1.5.0' );
define( 'LAYERS_PRO_DIR' , plugin_dir_path( __FILE__ ) );
define( 'LAYERS_PRO_URI' , plugin_dir_url( __FILE__ ) );
define( 'LAYERS_PRO_FILE' , __FILE__ );

// Load plugin class files
require_once( 'includes/class-layers-pro.php' );

// Register Activation Hook
register_activation_hook( LAYERS_PRO_FILE , array( 'Layers_Pro', 'activate' ) );

if( ! function_exists( 'layers_pro_init' ) ) {
    function layers_pro_init() {

        // Instantiate Plugin
        global $layers_pro;
        $layers_pro = Layers_Pro::get_instance();

        // Localization
        load_plugin_textdomain( LAYERS_PRO_SLUG, FALSE, dirname( plugin_basename( __FILE__ ) ) . "/lang/" );
    }
    add_action( 'plugins_loaded', 'layers_pro_init' );
}
