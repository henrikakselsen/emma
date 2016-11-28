<?php
/**
 * Layers DevKit Class
 *
 * This file is used to add DevKit / Portfolio functionality
 *
 * @package Layers
 * @since Layers 1.0
 */

class Layers_DevKit_Settings {

	private static $instance;

	private $defaults;
	private $saved;
	private $settings;

	/**
	*  Get Instance creates a singleton class that's cached to stop duplicate instances
	*/
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	*  Construct empty on purpose
	*/

	private function __construct() {}

	/**
	*  Init behaves like, and replaces, construct
	*/

	public function init(){

		// Save settings.
		add_action ( 'admin_init' , array( $this, 'save_settings' ) );

		// Add Onboarding Page (only if Layers is active).
		add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 60 );

		$this->register();
	}

	public function register(){

		// Settings
		global $layers_devkit;

		$layers_devkit->defaults = apply_filters( 'layers_devkit_defaults', array(

			// Active Panels
			'layers-devkit-css-active'     => 'yes',
			'layers-devkit-desktop-active' => 'yes',
			'layers-devkit-tablet-active'  => 'yes',
			'layers-devkit-mobile-active'  => 'yes',
			'layers-devkit-js-active'      => 'yes',

			// Responsive Sizes
			'layers-devkit-desktop-width' => 1025,
			'layers-devkit-desktop-height' => 1000,
			'layers-devkit-tablet-width'  => 768,
			'layers-devkit-tablet-height'  => 1024,
			'layers-devkit-mobile-width'  => 375,
			'layers-devkit-mobile-height'  => 667,

			'layers-devkit-code-linting' => '',

		) );

		$layers_devkit->settings = array(

			// Responsive Sizes
			'mobile_width'   => ( $layers_devkit->is_layers_active() ) ? $layers_devkit->defaults['layers-devkit-mobile-width'] : layers_devkit_get_option( 'layers-devkit-mobile-width', FALSE ),
			'mobile_height'  => $layers_devkit->defaults['layers-devkit-mobile-height'],

			'tablet_width'   => ( $layers_devkit->is_layers_active() ) ? $layers_devkit->defaults['layers-devkit-tablet-width'] : layers_devkit_get_option( 'layers-devkit-tablet-width', FALSE ),
			'tablet_height'  => $layers_devkit->defaults['layers-devkit-tablet-height'],

			'desktop_width'  => ( $layers_devkit->is_layers_active() ) ? $layers_devkit->defaults['layers-devkit-desktop-width'] : layers_devkit_get_option( 'layers-devkit-desktop-width', FALSE ),
			'desktop_height' => $layers_devkit->defaults['layers-devkit-desktop-height'],

			// Theme
			'theme' => 'mbo',

			// Linting
			'linting' => ( 'yes' == layers_devkit_get_option( 'layers-devkit-code-linting' ) ) ? TRUE : FALSE,
		);

	}

	public function save_settings() {
		global $layers_devkit;

		if( isset( $_POST[ 'layers-devkit-nonce' ] ) && wp_verify_nonce(  $_POST[ 'layers-devkit-nonce' ], 'layers-devkit-settings' ) ) {
			foreach( $layers_devkit->defaults as $input_key => $input_default ){
				update_option( $input_key, isset( $_POST[ $input_key ] ) ? $_POST[ $input_key ] : 0 );
			}
		}

		$this->saved = TRUE;
	}


	/**
	* Add Sub Menu Page to the Layers Menu Item
	*/
	public function add_submenu_page(){

		add_submenu_page(
			'options-general.php',
			__( 'DevKit Settings' , 'layerswp' ),
			__( 'DevKit Settings' , 'layerswp' ),
			'edit_theme_options',
			'layers-devkit-settings',
			array( $this, 'load_settings_ui' )
		);
	}

	public function load_settings_ui(){

		//Check the user capabilities
		// if ( ! current_user_can( 'manage_woocommerce' ) ) {
		// 	wp_die( __( 'You do not have sufficient permissions to access this page.', 'wsw' ) );
		// }

		// Include Partials, we're using require so that inside the partial we can use $this to access the header and footer
		require LAYERS_DEVKIT_DIR .'partials/settings.php';
	}

}
