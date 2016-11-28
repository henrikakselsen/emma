<?php
/**
 * Layers DevKit Class
 *
 * This file is used to add DevKit / Portfolio functionality
 *
 * @package Layers
 * @since Layers 1.0
 */

class Layers_DevKit {

	public $settings;

	/**
	 * Instance is an internal singleton storage
	 */
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

		if( $this->is_layers_active() ) {

			/**
			 * DevKit with Layers.
			 */

			// Run the version checker
			$this->layers_version_check();

			// Check for Layers as well as DevKit
			add_action( 'admin_notices', array( $this, 'activate_devkit_admin_notice' ) );

			// If Layers is active but is not the required version then halt.
			if( FALSE !== $this->update_required ) return;

			// Send the user to onboarding upon activation
			register_activation_hook( LAYERS_DEVKIT_FILE , array( $this, 'activate' ) );
			add_action( 'init' , array( $this, 'onboard_redirect') );

			// Modify CSS Customizer Sections (only if Layers is active).
			add_filter( 'layers_customizer_sections', array( $this, 'modify_customizer_sections' ) );

			// Modify CSS Customizer Control (only if Layers is active).
			add_filter( 'layers_customizer_controls', array( $this, 'modify_customizer_controls' ) );

			// Modify Control Transport Method
			add_filter( 'customize_register', array( $this, 'modify_transport_methods' ), 100 );

			// Add Onboarding Page (only if Layers is active).
			add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 60 );

			// Add customizer menu link in Layers menu (only if Layers is active).
			add_filter( 'layers_customizer_menu', array( $this, 'add_customizer_menu_link' ) );

		}
		else {

			/**
			 * DevKit as native WP plugin.
			 */

			// Render DevKit WP Button
			add_action( 'customize_controls_print_footer_scripts' , array( $this, 'render_devkit_wp_button' ) );

			// Setup the Theme Customizer settings and controls...
			add_action( 'customize_register' , 'layers_devkit_register_type' );
			add_action( 'customize_register' , array( $this, 'register_backup_controls' ) );

			// Apply Custom CSS
			add_action( 'wp_enqueue_scripts' , array( $this, 'apply_custom_styles' ), 90 );

			if( !isset( $wp_customize ) ) {
				add_filter( 'query_vars' , array( $this, 'custom_styles_add_query_vars' ) );
				add_action( 'template_redirect' , array( $this, 'custom_styles_template_redirect' ) );
			}
		}

		// Enqueue Customizer Scripts
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_enqueue_scripts' ) );

		// Enqueue Customizer Preview Scripts
		add_action( 'customize_preview_init', array( $this, 'preview_enqueue_scripts' ) );

		// Enqueue Admin Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Render DevKit Panel
		add_action( 'customize_controls_print_footer_scripts' , array( $this, 'render_devkit_panel' ) );

		// Apply Custom Js
		add_action( 'get_footer' , array( $this, 'apply_custom_js' ), 100 );
	}

	/**
	* Activate DevKit admin notice
	*/
	public function activate_devkit_admin_notice(){
		global $blog_id;
		$themes = wp_get_themes( $blog_id );

		if( !$this->is_layers_active() ){ ?>
			<div class="updated is-dismissible notice">
				<p><?php _e( sprintf( "Layers is required to use DevKit. <a href=\"%s\" target=\"_blank\">Click here</a> to get it.", ( isset( $themes['layerswp'] ) ? admin_url( 'themes.php?s=layerswp' ) : "http://www.layerswp.com" ) ), 'layers-devkit' ); ?></p>
			</div>
		<?php } elseif( FALSE !== $this->update_required ) { ?>
			<div class="updated is-dismissible notice">
				<p><?php _e( sprintf( "DevKit requires Layers Version ". $this->update_required .". <a href=\"%s\" target=\"_blank\">Click here</a> to get the Layers Updater.", "http://www.layerswp.com/download/layers-updater" ), 'layers-devkit' ); ?></p>
			</div>
		<?php }
	}

	/**
	* Helper to check if Layers theme is active.
	*/
	public static function is_layers_active(){

		if ( isset( $_GET['theme'] ) ) {
			// We're in Customizer and 'Live Previewing' another theme.
			$theme = $_GET['theme'];
		}
		else {
			// We're in Customizer as normal.
			$theme = get_template();
		}

		return ( bool )( 'layerswp' == $theme );
	}

	/**
	* Layers Min Version Checker
	*/
	public function layers_version_check(){

		if( !$this->is_layers_active() ) return FALSE;

		$layers_meta = wp_get_theme( 'layerswp' );

		if( version_compare( $layers_meta->get( 'Version' ), LAYERS_DEVKIT_REQUIRED_VERSION, '<' ) ){
			$this->update_required = LAYERS_DEVKIT_REQUIRED_VERSION;
		} else {
			$this->update_required = FALSE;
		}
	}

	/**
	* Set Activation Transient
	*/
	public function activate(){

		if( !get_theme_mod( 'layers-custom-css-main' ) && get_theme_mod( 'layers-custom-css' ) ) {
			set_theme_mod( 'layers-custom-css-main' , get_theme_mod( 'layers-custom-css' ) );
		}

		set_transient( 'layers_devkit_activated', 1, 30 );
	}

	/**
	* Redirect Users to onboarding upon activation
	*/
	public function onboard_redirect(){

		 // Only do this if the user can activate plugins
		if ( ! current_user_can( 'manage_options' ) )
			return;

		// Don't do anything if the transient isn't set
		if ( ! get_transient( 'layers_devkit_activated' ) )
			return;

		wp_redirect( admin_url( 'admin.php?page=layers-devkit-get-started' ) );
	}

	/**
	* Enqueue Customizer Scripts
	*/
	function customize_enqueue_scripts() {

		if( ! $this->is_layers_active() ) {

			// Icons - incase they are not available by layers.
			wp_enqueue_style(
				'layers-icon-fonts',
				LAYERS_DEVKIT_URI . 'assets/css/layers-icons.css',
				array(),
				LAYERS_DEVKIT_VER
			);
		}

		// Code Mirror scripts and styles
		wp_enqueue_script(
			'layers-codemirror-js',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/lib/codemirror.js',
			array(),
			LAYERS_DEVKIT_VER
		);
		wp_enqueue_style(
			'layers-codemirror-css',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/lib/codemirror.css',
			array(),
			LAYERS_DEVKIT_VER
		);

		wp_enqueue_style(
			'layers-codemirror-theme-1',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/theme/' . $this->settings['theme'] . '.css',
			array(),
			LAYERS_DEVKIT_VER
		);

		wp_enqueue_script(
			'layers-codemirror-mode-javascript',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/mode/javascript/javascript.js',
			array(),
			LAYERS_DEVKIT_VER
		);

		wp_enqueue_script(
			'layers-codemirror-mode-css',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/mode/css/css.js',
			array(),
			LAYERS_DEVKIT_VER
		);

		// Code Mirror Plugin - Close Brackets
		wp_enqueue_script(
			'layers-codemirror-plugin-closebrackets',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/plugins/closebrackets/closebrackets.js',
			array(),
			LAYERS_DEVKIT_VER
		);

		// Code Mirror Plugin - Lint
		wp_enqueue_script(
			'layers-codemirror-plugin-lint-lint-js',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/plugins/lint/lint.js',
			array(),
			LAYERS_DEVKIT_VER
		);
		wp_enqueue_style(
			'layers-codemirror-plugin-lint-lint-css',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/plugins/lint/lint.css',
			array(),
			LAYERS_DEVKIT_VER
		);
		wp_enqueue_script(
			'layers-codemirror-plugin-lint-css-lint',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/plugins/lint/css-lint.js',
			array(),
			LAYERS_DEVKIT_VER
		);
		wp_enqueue_script(
			'layers-codemirror-plugin-lint-javascript-lint',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/plugins/lint/javascript-lint.js',
			array(),
			LAYERS_DEVKIT_VER
		);
		wp_enqueue_script(
			'layers-codemirror-plugin-lint-jshint-js',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/plugins/lint/jshint.js',
			array(),
			LAYERS_DEVKIT_VER
		);
		wp_enqueue_script(
			'layers-codemirror-plugin-lint-stubbornella-css-lint-js',
			LAYERS_DEVKIT_URI . 'assets/js/codemirror/plugins/lint/stubbornella-css-lint.js',
			array(),
			LAYERS_DEVKIT_VER
		);

		// Enqueue Scripts
		wp_enqueue_script(
			'layers-devkit-js',
			LAYERS_DEVKIT_URI . 'assets/js/layers-devkit-customizer.js',
			array( 'customize-controls' ),
			LAYERS_DEVKIT_VER,
			true
		);

		// Enqueue Styles
		wp_enqueue_style(
			'layers-devkit-css',
			LAYERS_DEVKIT_URI . 'assets/css/layers-devkit.css',
			array(),
			LAYERS_DEVKIT_VER
		);

		// Localize Script
		wp_localize_script(
			'layers-devkit-js',
			'layers_devkit_settings',
			$this->settings
		);
	}

	/**
	* Enqueue Preview Scripts
	*/
	function preview_enqueue_scripts () {

		// Enqueue Scripts
		wp_enqueue_script(
			'layers-devkit-preview-js',
			LAYERS_DEVKIT_URI . 'assets/js/layers-devkit-preview.js',
			array( 'customize-preview-widgets' ),
			LAYERS_DEVKIT_VER,
			true
		);

		// Enqueue Styles
		wp_enqueue_style(
			'layers-devkit-css',
			LAYERS_DEVKIT_URI . 'assets/css/layers-devkit.css',
			array(),
			LAYERS_DEVKIT_VER
		);
	}

	/**
	* Enqueue Admin Scripts
	*/
	function admin_enqueue_scripts() {
		
		// Only load this on the Devkit Settings page.
		if ( isset( $_GET['page'] ) && 'layers-devkit-settings' == $_GET['page'] ) {

			if( ! $this->is_layers_active() ) {

				// Icons - incase they are not available by layers.
				wp_enqueue_style(
					'layers-icon-fonts',
					LAYERS_DEVKIT_URI . 'assets/css/layers-icons.css',
					array(),
					LAYERS_DEVKIT_VER
				);
			}

			// Enqueue Styles
			wp_enqueue_style(
				'layers-devkit-settings-css',
				LAYERS_DEVKIT_URI . 'assets/css/layers-devkit-settings.css',
				array(),
				LAYERS_DEVKIT_VER
			);

			// Localize Script
			wp_localize_script(
				'layers-devkit-js',
				'layers_devkit_settings',
				$this->settings
			);
		}
	}

	/**
	* Add Sub Menu Page to the Layers Menu Item
	*/
	public function add_submenu_page(){
		add_submenu_page(
			'layers-dashboard',
			__( 'DevKit Help' , 'layers-devkit' ),
			__( 'DevKit Help' , 'layers-devkit' ),
			'edit_theme_options',
			'layers-devkit-get-started',
			array( $this, 'load_onboarding' )
		);
	}

	public function load_onboarding(){
		// Include Partials, we're using require so that inside the partial we can use $this to access the header and footer
		require LAYERS_DEVKIT_DIR .'includes/onboarding.php';
	}

	/**
	 * Modify CSS Customizer Section
	 */

	public function modify_customizer_sections( $sections ){

		// @TODO: write function to get a control by id, so we don't have to rely on parent id.
		$sections['css'] = array(
			'title' =>__( 'DevKit', 'layers-devkit' ),
			'priority' => 40,
		);

		return $sections;
	}

	/**
	 * Modify CSS Customizer Control
	 */

	public function modify_customizer_controls( $controls ){

		// Add DevKit controls, still using the in custom-css control as the main CSS holder
		$controls['css'] = array(
			'devkit-button' => array(
				'type'  => 'layers-button',
				'text'  => 'DevKit',
			),
			'custom-css' => array(
				'type'   => 'layers-code',
				'label'  => 'Combined CSS',
			),
			'custom-css-main' => array(
				'type'   => 'layers-code',
				'label'  => 'Global CSS',
			),
			'custom-css-desktop' => array(
				'type'   => 'layers-code',
				'label'  => 'Desktop CSS',
			),
			'custom-css-tablet' => array(
				'type'   => 'layers-code',
				'label'  => 'Tablet CSS',
			),
			'custom-css-mobile' => array(
				'type'   => 'layers-code',
				'label'  => 'Mobile CSS',
			),
			'custom-js' => array(
				'type'   => 'layers-code',
				'label'  => 'Javascript',
			),
		);

		return $controls;
	}

	/**
	 * Modify CSS Customizer Control Transport Method
	 */

	public function modify_transport_methods( $wp_customize ){

		// Change Control settings to postMessage.
		// only the 'custom-css' will have a handler, ther others will be saved, but not post to the preview
		$wp_customize->get_setting( 'layers-custom-css' )->transport = 'postMessage';
		$wp_customize->get_setting( 'layers-custom-css-main' )->transport = 'postMessage';
		$wp_customize->get_setting( 'layers-custom-css-desktop' )->transport = 'postMessage';
		$wp_customize->get_setting( 'layers-custom-css-tablet' )->transport = 'postMessage';
		$wp_customize->get_setting( 'layers-custom-css-mobile' )->transport = 'postMessage';
		$wp_customize->get_setting( 'layers-custom-js' )->transport = 'postMessage';

		// Hide the CSS Section
		$wp_customize->get_section( 'layers-css' )->active_callback = '__return_false';
	}

	function render_devkit_panel() { ?>

		<div class="layers-devkit-panel" style="opacity:0; z-index: 0;">
			<div class="layers-devkit-action-bar">

				<a id="devkit-close-button"></a>

				<span class="layers-devkit-tabs layers-devkit-css-tabs">
					<a id="layers-devkit-tab-css-main" class="layers-devkit-tab">
						<?php echo esc_html( __( 'CSS', 'layerswp') ) ?>
						<!-- <span class="dashicons dashicons-admin-site"></span> -->
					</a>
					<?php if ( 'yes' == layers_devkit_get_option( 'layers-devkit-desktop-active' ) ) { ?>
						<a id="layers-devkit-tab-css-desktop" class="layers-devkit-tab responsive" title="<?php echo _e( 'Up to ', 'layers-devkit' ) . layers_devkit_get_option( 'layers-devkit-desktop-width', FALSE ) . 'px'; ?>" >
							<i class="icon-desktop layers-small"></i>
						</a>
					<?php } ?>
					<?php if ( 'yes' == layers_devkit_get_option( 'layers-devkit-tablet-active' ) ) { ?>
						<a id="layers-devkit-tab-css-tablet" class="layers-devkit-tab responsive"title="<?php echo _e( 'Up to ', 'layers-devkit' ) . layers_devkit_get_option( 'layers-devkit-tablet-width', FALSE ) . 'px'; ?>" >
							<i class="icon-tablet layers-small"></i>
						</a>
					<?php } ?>
					<?php if ( 'yes' == layers_devkit_get_option( 'layers-devkit-mobile-active' ) ) { ?>
						<a id="layers-devkit-tab-css-mobile" class="layers-devkit-tab responsive" title="<?php echo _e( 'Up to ', 'layers-devkit' ) . layers_devkit_get_option( 'layers-devkit-mobile-width', FALSE ) . 'px'; ?>" >
							<i class="icon-phone layers-small"></i>
						</a>
					<?php } ?>
				</span>

				<?php if ( 'yes' == layers_devkit_get_option( 'layers-devkit-js-active' ) ) { ?>
					<span class="layers-devkit-tabs layers-devkit-javascript-tabs">
						<a id="layers-devkit-tab-js-main" class="layers-devkit-tab">
							<?php echo esc_html( __( 'Javascript', 'layerswp') ) ?>
						</a>
					</span>
				<?php } ?>

				<span class="layers-devkit-wp-actions"></span>

			</div>

			<span class="layers-devkit-tabs-content <?php if ( 'yes' == layers_devkit_get_option( 'layers-devkit-code-linting' ) ) echo 'layers-devkit-linting-enabled' ; ?>">
				<div id="layers-devkit-tab-css-main-tab" class="layers-devkit-code-panel-holder active"></div>
				<div id="layers-devkit-tab-js-main-tab" class="layers-devkit-code-panel-holder active">
					<a id="layers-devkit-run"><?php _e( 'Run', 'layers-devkit' ) ?> <span class="dashicons dashicons-controls-play"></span></a>
				</div>
				<div id="layers-devkit-tab-css-mobile-tab" class="layers-devkit-code-panel-holder active"></div>
				<div id="layers-devkit-tab-css-tablet-tab" class="layers-devkit-code-panel-holder active"></div>
				<div id="layers-devkit-tab-css-desktop-tab" class="layers-devkit-code-panel-holder active"></div>
			</span>
		</div>

		<?php if ( false ) { ?>
		<li class="top-nav-button" style="opacity:0; z-index: 0;">
			<a class="customize-controls-layers-button customize-controls-layers-button-css" href="#" target="_blank">
				<i class="dashicons dashicons-editor-code"></i><?php _e( 'DevKit' , 'layers-devkit' ) ?>
			</a>
		</li>
		<?php } ?>

		<?php
	}

	public function add_customizer_menu_link( $menu ) {

		$insert_after_index = array_search( 'dashboard', array_keys( $menu ) ) + 1;

		$new_menu_item = array(
			'devkit' => array(
				'text'			=> __( 'DevKit' , 'layers-devkit' ),
				'link'			=> '#',
				'icon_class'	=> 'dashicons dashicons-editor-code',
			)
		);

		$menu_before_index = array_slice( $menu, 0, $insert_after_index, true );
		$menu_after_index = array_slice( $menu, $insert_after_index, count($menu) - 1, true );

		$menu = $menu_before_index + $new_menu_item + $menu_after_index;

		return $menu;
	}

	public function apply_custom_js(){

		if ( function_exists( 'layers_get_theme_mod' ) ) {
			echo '<script id="layers-custom-js">' . layers_get_theme_mod( 'custom-js' ) . '</script>';
		}
		else{
			echo '<script id="layers-custom-js">' . get_theme_mod( 'layers-custom-js' ) . '</script>';
		}
	}

	/**
	* Add DevKit Button in the Customizer - WordPress Only.
	*/
	function render_devkit_wp_button() {

		?>
		<a class="layers-devkit-button" style="opacity:0; z-index: 0;">
			<i class="dashicons dashicons-editor-code"></i>
		</a>
		<?php
	}

	/**
	* Register backup control when Layers isn't active - WordPress Only.
	*/
	public static function register_backup_controls( $wp_customize ) {

		/**
		 * Section
		 */
		$wp_customize->add_section(
			'layers-css',
			array(
				'title' => __( 'DevKit', 'layers-devkit' ),
				'priority' => 35,
				'capability' => 'edit_theme_options',
				'description' => __('', 'layers-devkit'),
				'active_callback' => '__return_false', // Hide the this section - only show it to Debug.
			)
		);

		/**
		 * Settings / Controls
		 */

		$fields = array(
			'layers-custom-css' => array(
				'label' => __( 'Combined CSS', 'layers-devkit' ),
				'description' => '',
				'default' => '',
			),
			'layers-custom-css-main' => array(
				'label' => __( 'Global CSS', 'layers-devkit' ),
				'description' => '',
				'default' => '',
			),
			'layers-custom-css-desktop' => array(
				'label' => __( 'Desktop CSS', 'layers-devkit' ),
				'description' => '',
				'default' => '',
			),
			'layers-custom-css-tablet' => array(
				'label' => __( 'Tablet CSS', 'layers-devkit' ),
				'description' => '',
				'default' => '',
			),
			'layers-custom-css-mobile' => array(
				'label' => __( 'Mobile CSS', 'layers-devkit' ),
				'description' => '',
				'default' => '',
			),
			'layers-custom-js' => array(
				'label' => __( 'Javascript', 'layers-devkit' ),
				'description' => '',
				'default' => '',
			),
		);

		foreach ( $fields as $field_id => $field_value ) {

			$wp_customize->add_setting(
				$field_id,
				array(
					'default' => $field_value['default'],
					'type' => 'theme_mod',
					'capability' => 'edit_theme_options',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_control( new Layers_DevKit_Textarea_Control(
				$wp_customize,
				$field_id,
				array(
					'label'   => $field_value['label'],
					'section' => 'layers-css',
					'settings'   => $field_id,
					'priority' => 10
				)
			) );
		}
	}

	public function apply_custom_styles(){
		global $wp_customize;

		if( isset( $wp_customize ) ){

			// Enqueue the DevKit Stylesheet URL
			wp_enqueue_style( 'devkit-custom-styles', LAYERS_DEVKIT_URI . 'assets/css/custom.css' );

			// Add the DevKit inline Styles (customizer only, this obeys the theme previewing)
			wp_add_inline_style( 'devkit-custom-styles', trim( get_theme_mod( 'layers-custom-css' ) ) );
		} else {

			// Enqueue the DevKit Stylesheet URL
			wp_enqueue_style( 'devkit-custom-styles', add_query_arg( array( 'stylesheet' => 'devkit-custom' ), home_url() ) );
		}
	}

	public function custom_styles_add_query_vars($query_vars) {
		$query_vars[] = 'stylesheet';
		return $query_vars;
	}

	public function custom_styles_template_redirect(){

		// Check if CSS file is required
		$style = get_query_var( 'stylesheet' );

		// Load the new stylesheet provided our query_var is devkit-custom
		if ( 'devkit-custom' === $style ) {

			header('Content-type: text/css');
			wp_reset_postdata();
			echo trim( get_theme_mod( 'layers-custom-css' ) );
			exit;
		}
	}

}

/**
* Register backup TextArea control type when Layers isn't active.
*/
function layers_devkit_register_type( $wp_customize ) {

	if ( class_exists( 'WP_Customize_Control' ) ) {
		class Layers_DevKit_Textarea_Control extends WP_Customize_Control {

			public $label;

			public function render_content() {
				?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<textarea id="<?php echo $this->id ?>" data-customize-setting-link="<?php echo $this->id ?>"  class="large-text" cols="20" rows="5" ><?php echo esc_textarea( $this->value() ); ?></textarea>
				</label>
				<?php
			}
		}
	}
}
