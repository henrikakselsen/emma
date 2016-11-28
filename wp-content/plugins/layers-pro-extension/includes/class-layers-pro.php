<?php
/**
 * Pro Class
 *
 * This file is used to modify any Pro related filtes, hooks & modifiers
 *
 * @package Layers
 * @since Layers 1.0
 */

class Layers_Pro {

	private static $instance;

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

		// Run the version checker
		$this->layers_version_check();

		// Check for Layers as well as WooCommerce
		add_action( 'admin_notices', array( $this, 'activate_admin_notice' ) );

		if( FALSE !== $this->update_required || 'layerswp' != get_template() ) return;

		// Includes
		require_once( LAYERS_PRO_DIR . 'includes/helpers.php' ); // Customizer Controls
		require_once( LAYERS_PRO_DIR . 'includes/controls.php' ); // Customizer Controls
		require_once( LAYERS_PRO_DIR . 'includes/widget-filters.php' ); // Customizer Controls
		require_once( LAYERS_PRO_DIR . 'widgets/base.php' ); // Widget Base
		require_once( LAYERS_PRO_DIR . 'widgets/post-carousel.php' ); // Post Carousel Widget
		require_once( LAYERS_PRO_DIR . 'widgets/call-to-action.php' ); // Call To Action Widget
		require_once( LAYERS_PRO_DIR . 'widgets/tabs.php' ); // Tabs Widget
		require_once( LAYERS_PRO_DIR . 'widgets/accordion.php' ); // Accordian Widget
		require_once( LAYERS_PRO_DIR . 'widgets/social-icons.php' ); // Social Icons Widget

		// Add Styles & Scripts
		add_filter( 'wp_enqueue_scripts', array( $this , 'enqueue_scripts' ), 30 );
		add_filter( 'admin_enqueue_scripts', array( $this , 'admin_enqueue_scripts' ) );

	}

	/**
	* Activate Pro admin notice
	*/
	public function activate_admin_notice(){
		global $blog_id;
		$themes = wp_get_themes( $blog_id );
		if( 'layerswp' !== get_template() ){ ?>
			<div class="updated is-dismissible notice">
				<p><?php _e( sprintf( "Layers is required to use Pro. <a href=\"%s\" target=\"_blank\">Click here</a> to get it.", ( isset( $themes['layerswp'] ) ? admin_url( 'themes.php?s=layerswp' ) : "http://www.layerswp.com" ) ), 'layers-storekit' ); ?></p>
			</div>
		<?php } elseif( FALSE !== $this->update_required ) { ?>
			<div class="updated is-dismissible notice">
				<p><?php _e( sprintf( "Pro requires Layers Version ". $this->update_required .". <a href=\"%s\" target=\"_blank\">Click here</a> to get the Layers Updater.", "http://www.layerswp.com/download/layers-updater" ), 'layers-storekit' ); ?></p>
			</div>
	<?php }
	}

	/**
	* Layers Min Version Checker
	*/
	public function layers_version_check(){

		$this->layers_meta = wp_get_theme( 'layerswp' );

		if( version_compare( $this->layers_meta->get( 'Version' ), LAYERS_PRO_REQUIRED_VERSION, '<' ) ){
			$this->update_required = LAYERS_PRO_REQUIRED_VERSION;
		} else {
			$this->update_required = FALSE;
		}
	}

	/**
	* Activation Hook
	*/
	public static function activate(){

		// Set Activation Transient
		set_transient( 'layers_pro_activated', 1, 30 );

		// if ( ( $mod = get_theme_mod( 'layers-header-background-color' ) ) && FALSE == get_theme_mod( 'layers-buttons-primary-background-color' ) ) {
		// 	_log( set_theme_mod( 'layers-buttons-primary-background-color', $mod ) );
		// }
	}

	/**
	*  Enqueue Admin Scripts & Styles
	*/

	public function admin_enqueue_scripts(){

		// Enqueue Script
		wp_enqueue_script(
			LAYERS_PRO_SLUG . '-admin',
			LAYERS_PRO_URI . 'assets/js/admin.js',
			array( 'jquery' ),
			LAYERS_PRO_VER,
			true
		);

		wp_localize_script(
			LAYERS_PRO_SLUG . '-admin' ,
			'layers_pro_params',
			array(
				'social_networks' => layers_pro_get_social_networks( 'native-with-values' ),
			)
		);

		// Enqueue CSS
		wp_enqueue_style(
			LAYERS_PRO_SLUG . '-admin',
			LAYERS_PRO_URI . 'assets/css/admin.css',
			array(),
			LAYERS_PRO_VER
		);
	}

	/**
	*  Enqueue Front End Scripts & Styles
	*/

	public function enqueue_scripts(){

		global $wp_customize;

		if ( $wp_customize ) {

			// Enqueue Swiper Slider
			wp_enqueue_script( LAYERS_THEME_SLUG . '-slider-js' );
			wp_enqueue_style( LAYERS_THEME_SLUG . '-slider' );
		}

		// Enqueue CSS
		wp_enqueue_style(
			LAYERS_PRO_SLUG . '-pro',
			LAYERS_PRO_URI . '/assets/css/layers-pro.css',
			array(),
			LAYERS_PRO_VER
		);

		// Enqueue Script
		wp_enqueue_script(
			LAYERS_PRO_SLUG . '-frontend',
			LAYERS_PRO_URI . 'assets/js/layers-pro.js',
			array( 'jquery', 'layers-framework',  ),
			LAYERS_PRO_VER
		);

		// Enqueue Parallax & Bakground Video Scripts
		wp_enqueue_script(
			LAYERS_PRO_SLUG . '-plugins',
			LAYERS_PRO_URI . 'assets/js/jquery.plugins.min.js',
			array( 'jquery' ),
			LAYERS_PRO_VER
		);

		if( function_exists( 'layers_get_theme_mod' ) ) {
			$enable_smooth_scroll = layers_get_theme_mod( 'enable-smooth-scroll' );

			if( FALSE != $enable_smooth_scroll ) {
				wp_enqueue_script(
					LAYERS_PRO_SLUG . '-smooth-scroll',
					LAYERS_PRO_URI . 'assets/js/jquery.smoothscroll.js',
					array( 'jquery' ),
					LAYERS_PRO_VER
				);
			}
		}

		// Enqueue Font-Awesome.
		wp_enqueue_style( 'layers-font-awesome' );
	}

}
