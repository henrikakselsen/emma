<?php
/**
 * Pro Controls
 *
 * This file is used to configure the Controls in the Customizer.
 *
 * @package Layers
 * @since Layers 1.0
 */

class Layers_Pro_Controls {

	private static $instance;

	/**
	 * Get Instance creates a singleton class that's cached to stop duplicate instances
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	 * Construct empty on purpose
	 */
	private function __construct() {}

	/**
	 * Init behaves like, and replaces, construct
	 */
	public function init(){

		// Add Customizer Panel
		add_filter( 'layers_customizer_panels', array( $this, 'modify_customizer_panels' ), 60 );

		// Add Customizer Section
		add_filter( 'layers_customizer_sections', array( $this, 'modify_customizer_sections' ), 60 );

		// Add Customizer Control
		add_filter( 'layers_customizer_controls', array( $this, 'modify_customizer_controls' ), 60 );

		// Apply Customizations
		add_action( 'wp_enqueue_scripts', array( $this, 'apply_customizer_customizations' ), 60 );

		// Apply Featured Image Settings
		add_filter( 'layers_post_featured_media', array( $this, 'apply_featured_image_settings' ) );

		// Apply Meta Display Settings
		add_filter( 'layers_post_meta_display', array( $this, 'apply_meta_display_settings' ) );

		// Modify Read More Buttons Text.
		add_action( 'layers_read_more_text', array( $this, 'layers_read_more_text' ) );

		// Modify Header Class according to the Logo size
		add_filter( 'layers_header_class', array( $this, 'header_class' ) );
		add_filter( 'layers_title_container_class', array( $this, 'title_container_class' ) );

		// Modify Comment Display
		add_filter( 'comments_template', array( $this, 'comments' ) );

		// Apply Localizations to Scripts.
		add_action( 'layers_sticky_header_breakpoint', array( $this, 'apply_sticky_header_breakpoint' ) );

		// Add 'layers-pro-active' to the body class when active.
		add_filter( 'layers_body_class', array( $this, 'body_class' ) );

		// Add Search to Header.
		add_action( 'layers_after_header_nav' , array( $this, 'add_search_button' ), 25 );
		add_filter( 'layers_after_footer', array( $this, 'render_search_interface' ) );
		// add_filter( 'wp_nav_menu_items', array( $this, 'add_search_menu_item' ), 90, 2 ); // OLD

		// ---------- Disable other conflicting color applications -----------

		// Remove application of colors by Layers.
		add_action( 'init', array( $this, 'remove_layers_colors' ) );

		// Disable ColorKit if it is also activated.
		add_action( 'init', array( $this, 'disable_colorkit' ) );
	}

	/**
	* Remove application of colors by Layers.
	*/
	public function remove_layers_colors() {
		remove_action( 'wp_enqueue_scripts', 'layers_apply_customizer_styles', 50 );
		remove_action( 'wp_enqueue_scripts', 'layers_apply_customizer_styles', 100 ); // Legacy - Layers used to hook this to 100
	}

	/**
	 * Disable ColorKit if it is also activated.
	 */
	public function disable_colorkit() {

		// Bail if ColorKit is not active.
		if ( ! class_exists( 'Layers_Controls_ColorKit' ) ) return;

		// Get the instance of the ColorKit class.
		$layers_colorkit = Layers_Controls_ColorKit::get_instance();

		// Remove all Colorkit functionality by removing all it's actions & filters.
		remove_filter( 'layers_customizer_panels', array( $layers_colorkit, 'modify_customizer_panels' ) );
		remove_filter( 'layers_customizer_sections', array( $layers_colorkit, 'modify_customizer_sections' ) );
		remove_filter( 'layers_customizer_controls', array( $layers_colorkit, 'modify_customizer_controls' ) );
		remove_action( 'wp_enqueue_scripts', array( $layers_colorkit, 'apply_customizer_customizations' ), 90 );

		// Display notice if ColorKit is running
		add_action( 'admin_notices', array( $this, 'colorkit_disabled_admin_notice' ) );
	}

	/**
	 * Disable ColorKit admin Notice.
	 */
	public function colorkit_disabled_admin_notice() {
		global $pagenow;
		if ( 'plugins.php' == $pagenow ) {
			?>
			<div class="updated">
				<p><?php _e( '<strong>Layers Pro</strong> has replaced <strong>ColorKit</strong> - it has the same color controls, plus a whole lot more. <strong>Please disable and remove ColorKit</strong>.', 'layers-pro' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Add Customizer Panel
	 */
	public function modify_customizer_panels( $panels ) {

		return $panels;
	}

	/**
	 * Add Customizer Section
	 */
	public function modify_customizer_sections( $sections ) {

		$sections['buttons'] = array(
			'title' => __( 'Buttons', 'layers-pro' ),
			'panel' => 'site-settings',
		);

		$sections['social-networks'] = array(
			'title' => __( 'Social Networks', 'layers-pro' ),
			'panel' => 'site-settings',
		);

		$sections = layers_insert_config_elements_after(
			'header-layout',
			$sections,
			array( 'menu-styling' =>
				array(
					'title' => __( 'Menu Styling', 'layers-pro' ),
					'panel' => 'header',
				)
			)
		);

		$sections = layers_insert_config_elements_before(
			'blog-archive',
			$sections,
			array( 'blog-styling' =>
				array(
					'title' => __( 'Styling', 'layers-pro' ),
					'panel' => 'blog-archive-single',
				)
			)
		);

		// Remove the old color sections - it has no controls left in it.
		// unset( $sections['site-colors'] );

		return $sections;
	}

	/**
	 * Add Customizer Controls
	 */
	public function modify_customizer_controls( $controls ){

		$pro_badge = ' <span class="layers-control-title-marker" title="Control from Layers Pro">PRO</span>';

		$pro_controls['menu-styling'] = array(
			/**
			 * -- Menu Styling Goes Here ----------
			 */
			'menu-heading' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Header Menu Styling', 'layers-pro' ) . $pro_badge,
				'description' => __( 'This will affect the menus which display in the header', 'layers-pro' ), // @TODO
				// 'class' => 'layers-push-top',
			),
			/**
			 * Text
			 */
			'menu-text-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Text Color', 'layers-pro' ),
				'class' => 'group',
			),
			'menu-text-shadow' => array(
				'type' => 'layers-select',
				'label' => __( 'Text Shadow', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'none' => __( 'None', 'layers-pro' ),
					'bottom' => __( 'Bottom Shadow', 'layers-pro' ),
					'top' => __( 'Top Shadow', 'layers-pro' ),
				),
			),
			'menu-text-transform' => array(
				'type' => 'layers-select',
				'label' => __( 'Text Transform', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'' => __( 'Normal', 'layers-pro' ),
					'uppercase' => __( 'Uppercase', 'layers-pro' ),
					'capitalize' => __( 'Capitalize', 'layers-pro' ),
					'lowercase' => __( 'Lowercase', 'layers-pro' ),
				),
			),
			'menu-item-spacing' => array(
				'type'  => 'layers-range',
				'label' => __( 'Link Spacing', 'layers-pro' ),
				'class' => 'group',
				'colspan'  => 3,
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'placeholder' => 10,
			),
			'menu-hover-toggle' => array(
				'type'  => 'layers-checkbox',
				'label' => __( 'Enable Hover Styling', 'layers-pro' ),
				'class' => 'group',
			),
			'menu-hover-heading' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Header Menu Hover Styling', 'layers-pro' ) . $pro_badge,
				'description' => __( 'This will affect header menu items when hovered over or active', 'layers-pro' ), // @TODO
				'class' => 'layers-push-top',
				'linked'    => array(
						'show-if-selector' => "#layers-menu-hover-toggle",
						'show-if-value' => 'true',
				),
			),
			'menu-hover-text-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Text Color', 'layers-pro' ),
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-menu-hover-toggle",
						'show-if-value' => 'true',
				),
			),
			'menu-hover-text-shadow' => array(
				'type' => 'layers-select',
				'label' => __( 'Text Shadow', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'none' => __( 'None', 'layers-pro' ),
					'bottom' => __( 'Bottom Shadow', 'layers-pro' ),
					'top' => __( 'Top Shadow', 'layers-pro' ),
				),
				'linked'    => array(
						'show-if-selector' => "#layers-menu-hover-toggle",
						'show-if-value' => 'true',
				),
			),
			/**
			 * Background & Borders
			 */
			'menu-hover-background-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Background Color', 'layers-pro' ),
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-menu-hover-toggle",
						'show-if-value' => 'true',
				),
			),
			'menu-hover-border-radius' => array(
				'type' => 'layers-range',
				'label' => __( 'Rounded Corner Size', 'layers-pro' ),
				'class' => 'group',
				'default' => 4,
				'min' => '0',
				'max' => '100',
				'step' => '1',
				'linked'    => array(
						'show-if-selector' => "#layers-menu-hover-toggle",
						'show-if-value' => 'true',
				),
			),
			/**
			 * Border
			 */
			'menu-hover-border-width' => array(
				'type' => 'layers-range',
				'label' => __( 'Border Width', 'layers-pro' ),
				'class' => 'group layers-push-top-small',
				'min' => '0',
				'max' => '10',
				'step' => '1',
				'placeholder' => '0',
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-menu-hover-toggle",
						'show-if-value' => 'true',
				),
			),
			'menu-hover-border-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Border Color', 'layers-pro' ),
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-menu-hover-toggle",
						'show-if-value' => 'true',
				),
			),

			'submenu-primary-heading' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Sub Menu Styling', 'layers-pro' ) . $pro_badge,
				'description' => __( 'This will affect menu drop-downs', 'layers-pro' ), // @TODO
				'class' => 'layers-push-top',
			),
			/**
			 * Text
			 */
			'submenu-text-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Text Color', 'layers-pro' ),
				'class' => 'group',
			),
			'submenu-text-shadow' => array(
				'type' => 'layers-select',
				'label' => __( 'Text Shadow', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'none' => __( 'None', 'layers-pro' ),
					'bottom' => __( 'Bottom Shadow', 'layers-pro' ),
					'top' => __( 'Top Shadow', 'layers-pro' ),
				),
			),
			'submenu-text-transform' => array(
				'type' => 'layers-select',
				'label' => __( 'Text Transform', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'' => __( 'Normal', 'layers-pro' ),
					'uppercase' => __( 'Uppercase', 'layers-pro' ),
					'capitalize' => __( 'Capitalize', 'layers-pro' ),
					'lowercase' => __( 'Lowercase', 'layers-pro' ),
				),
			),
			/**
			 * Background & Borders
			 */
			'submenu-background-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Background Color', 'layers-pro' ),
				'class' => 'group',
			),
			/**
			 * Border
			 */
			'submenu-border-width' => array(
				'type' => 'layers-range',
				'label' => __( 'Border Width', 'layers-pro' ),
				'class' => 'group layers-push-top-small',
				'min' => '0',
				'max' => '10',
				'step' => '1',
				'placeholder' => '0',
				'class' => 'group',
			),
			'submenu-border-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Border Color', 'layers-pro' ),
				'class' => 'group',
			),
			'submenu-separate-border-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Separator Border Color', 'layers-pro' ),
				'class' => 'group',
			),
		);

		$pro_controls['buttons'] = array(
			/**
			 * -- Primary Buttons (White/Light Backgrounds) ----------
			 */
			'buttons-primary-heading-primary' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Primary Buttons', 'layers-pro' ) . $pro_badge,
				'description' => __( 'Buttons apearing on light backgrounds (eg white).', 'layers-pro' ), // @TODO
				'class' => '',
			),
			/**
			 * Background
			 */
			'buttons-primary-background-style' => array(
				'type'  => 'layers-select',
				'label' => __( 'Background Style', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'' => '-- Choose --',
					'solid' => __( 'Solid', 'layers-pro' ),
					'transparent' => __( 'Transparent', 'layers-pro' ),
					'gradient' => __( 'Gradient', 'layers-pro' ),
				),
				'default'  => 'solid',
			),
			'buttons-primary-background-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Background Color', 'layers-pro' ),
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-buttons-primary-background-style",
						'show-if-value' => 'solid',
				),
			),
			'buttons-primary-background-gradient-start-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Gradient Start Color', 'layers-pro' ),
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-buttons-primary-background-style",
						'show-if-value' => 'gradient',
				),
			),
			'buttons-primary-background-gradient-end-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Gradient End Color', 'layers-pro' ),
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-buttons-primary-background-style",
						'show-if-value' => 'gradient',
				),
			),
			'buttons-primary-background-gradient-direction' => array(
				'type' => 'layers-range',
				'label' => __( 'Gradient Angle', 'layers-pro' ),
				'class' => 'group',
				'default' => 0,
				'min' => '0',
				'max' => '360',
				'step' => '1',
				'linked'    => array(
						'show-if-selector' => "#layers-buttons-primary-background-style",
						'show-if-value' => 'gradient',
				),
			),
			/**
			 * Text
			 */
			'buttons-primary-text-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Text Color', 'layers-pro' ),
				'class' => 'group layers-push-top-small',
			),
			'buttons-primary-text-shadow' => array(
				'type' => 'layers-select',
				'label' => __( 'Text Shadow', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'' => '-- Choose --',
					'none' => __( 'None', 'layers-pro' ),
					'bottom' => __( 'Bottom Shadow', 'layers-pro' ),
					'top' => __( 'Top Shadow', 'layers-pro' ),
				),
			),
			'buttons-primary-text-transform' => array(
				'type' => 'layers-select',
				'label' => __( 'Text Transform', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					// '' => __( 'Normal', 'layers-pro' ),
					'' => '-- Choose --',
					'uppercase' => __( 'Uppercase', 'layers-pro' ),
					'capitalize' => __( 'Capitalize', 'layers-pro' ),
					'lowercase' => __( 'Lowercase', 'layers-pro' ),
				),
			),
			/**
			 * Border
			 */
			'buttons-primary-border-width' => array(
				'type' => 'layers-range',
				'label' => __( 'Border Width', 'layers-pro' ),
				'class' => 'group layers-push-top-small',
				'default' => 0,
				'min' => '0',
				'max' => '10',
				'step' => '1',
			),
			'buttons-primary-border-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Border Color', 'layers-pro' ),
				'class' => 'group',
			),
			/**
			 * Styling
			 */
			'buttons-primary-border-radius' => array(
				'type' => 'layers-range',
				'label' => __( 'Rounded Corner Size', 'layers-pro' ),
				'class' => 'group layers-push-top-small',
				'default' => 4,
				'min' => '0',
				'max' => '100',
				'step' => '1',
			),
			'buttons-primary-shadow' => array(
				'type' => 'layers-select',
				'label' => __( 'Button Shadow', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'' => '-- Choose --',
					'none' => __( 'None', 'layers-pro' ),
					'small' => __( 'Small', 'layers-pro' ),
					'medium' => __( 'Medium', 'layers-pro' ),
					'large' => __( 'Large', 'layers-pro' ),
				),
			),
			/**
			 * -- Secondary Secondry (White/Light Backgrounds) ----------
			 */
			'buttons-secondary-heading-primary' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Secondary Buttons', 'layers-pro' ) . $pro_badge,
				'description' => __( 'Buttons apearing on dark backgrounds (eg black).', 'layers-pro' ),
				'class' => 'layers-push-top',
			),
			/**
			 * Background
			 */
			'buttons-secondary-background-style' => array(
				'type'  => 'layers-select',
				'label' => __( 'Background Style', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'' => '-- Choose --',
					'solid' => __( 'Solid', 'layers-pro' ),
					'transparent' => __( 'Transparent', 'layers-pro' ),
					'gradient' => __( 'Gradient', 'layers-pro' ),
				),
				'default'  => 'solid',
			),
			'buttons-secondary-background-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Background Color', 'layers-pro' ),
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-buttons-secondary-background-style",
						'show-if-value' => 'solid',
				),
			),
			'buttons-secondary-background-gradient-start-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Gradient Start Color', 'layers-pro' ),
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-buttons-secondary-background-style",
						'show-if-value' => 'gradient',
				),
			),
			'buttons-secondary-background-gradient-end-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Gradient End Color', 'layers-pro' ),
				'class' => 'group',
				'linked'    => array(
						'show-if-selector' => "#layers-buttons-secondary-background-style",
						'show-if-value' => 'gradient',
				),
			),
			'buttons-secondary-background-gradient-direction' => array(
				'type' => 'layers-range',
				'label' => __( 'Gradient Angle', 'layers-pro' ),
				'class' => 'group',
				'default' => 0,
				'min' => '0',
				'max' => '360',
				'step' => '1',
				'linked'    => array(
						'show-if-selector' => "#layers-buttons-secondary-background-style",
						'show-if-value' => 'gradient',
				),
			),
			/**
			 * Text
			 */
			'buttons-secondary-text-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Text Color', 'layers-pro' ),
				'class' => 'group layers-push-top-small',
			),
			'buttons-secondary-text-shadow' => array(
				'type' => 'layers-select',
				'label' => __( 'Text Shadow', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'' => '-- Choose --',
					'none' => __( 'None', 'layers-pro' ),
					'bottom' => __( 'Bottom Shadow', 'layers-pro' ),
					'top' => __( 'Top Shadow', 'layers-pro' ),
				),
			),
			'buttons-secondary-text-transform' => array(
				'type' => 'layers-select',
				'label' => __( 'Text Transform', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					// '' => __( 'Normal', 'layers-pro' ),
					'' => '-- Choose --',
					'uppercase' => __( 'Uppercase', 'layers-pro' ),
					'capitalize' => __( 'Capitalize', 'layers-pro' ),
					'lowercase' => __( 'Lowercase', 'layers-pro' ),
				),
			),
			/**
			 * Border
			 */
			'buttons-secondary-border-width' => array(
				'type' => 'layers-range',
				'label' => __( 'Border Width', 'layers-pro' ),
				'class' => 'group layers-push-top-small',
				'default' => 0,
				'min' => '0',
				'max' => '10',
				'step' => '1',
			),
			'buttons-secondary-border-color' => array(
				'type' => 'layers-color',
				'label' => __( 'Border Color', 'layers-pro' ),
				'class' => 'group',
			),
			/**
			 * Styling
			 */
			'buttons-secondary-border-radius' => array(
				'type' => 'layers-range',
				'label' => __( 'Rounded Corner Size', 'layers-pro' ),
				'class' => 'group layers-push-top-small',
				'default' => 0,
				'min' => '0',
				'max' => '100',
				'step' => '1',
			),
			'buttons-secondary-shadow' => array(
				'type' => 'layers-select',
				'label' => __( 'Button Shadow', 'layers-pro' ),
				'class' => 'group',
				'choices' => array(
					'' => '-- Choose --',
					'none' => __( 'None', 'layers-pro' ),
					'small' => __( 'Small', 'layers-pro' ),
					'medium' => __( 'Medium', 'layers-pro' ),
					'large' => __( 'Large', 'layers-pro' ),
				),
			),
		);

		$controls = layers_insert_config_elements_after(
			'header-overlay',
			$controls,
			array(
				'header-search-heading' => array(
					'type' => 'layers-heading',
					'heading_divider' => __( 'Search', 'layers-pro' ) . $pro_badge,
					'class' => 'layers-push-top',
				),
				'header-search-active' => array(
					'label' => __( 'Show Search' , 'layers-pro' ),
					'type' => 'layers-checkbox',
				),
			)
		);

		$pro_controls['header-layout'] = array(

			'header-styling-heading' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Header Styling', 'layers-pro' ) . $pro_badge,
				'description' => __( 'Control the visual styling of your header elements. Go here to <a class="customizer-link" href="#accordion-section-title_tagline">change logo size</a>.', 'layers-pro' ),
				'class' => 'layers-push-top',
			),
			'header-height' => array(
				'type'   => 'layers-range',
				'label'  => __( 'Header Padding', 'layers-pro' ),
				'class' => 'group',
				'placeholder' => 0,
				'min' => 0,
				'max' => 100,
				'step' => 1,
			),
			'header-background-color' => array(
				'label' => '',
				'subtitle' => __( 'Background Color' , 'layers-pro' ),
				'type' => 'layers-color',
				'default' => '#F3F3F3',
				'class' => 'group',
			),
			'header-background-image' => array(
				'label' => __( 'Background Image', 'layers-pro' ),
				'type' => 'layers-select-images',
				'class' => 'group',
			),
			'header-background-repeat' => array(
				'label' => __( 'Repeat', 'layers-pro' ),
				'type' => 'layers-select',
				'class' => 'group',
				'default' => 'no-repeat',
				'choices' => array(
					'no-repeat' => __( 'No Repeat', 'layers-pro' ),
					'repeat' => __( 'Repeat', 'layers-pro' ),
					'repeat-x' => __( 'Repeat Horizontal', 'layers-pro' ),
					'repeat-y' => __( 'Repeat Vertical', 'layers-pro' )
				),
				'linked' => array(
					'show-if-selector' => '#layers-header-background-image',
					'show-if-value' => '',
					'show-if-operator' => '!==',
				)
			),
			'header-background-position' => array(
				'label' => __( 'Position', 'layers-pro' ),
				'type' => 'layers-select',
				'class' => 'group',
				'default' => 'center',
				'choices' => array(
					'center' => __( 'Center', 'layers-pro' ),
					'top' => __( 'Top', 'layers-pro' ),
					'bottom' => __( 'Bottom', 'layers-pro' ),
					'left' => __( 'Left', 'layers-pro' ),
					'right' => __( 'Right', 'layers-pro' )
				),
				'linked' => array(
					'show-if-selector' => '#layers-header-background-image',
					'show-if-value' => '',
					'show-if-operator' => '!==',
				)
			),
			'header-background-parallax' => array(
				'label' => __( 'Parallax', 'layers-pro' ),
				'type' => 'layers-checkbox',
				'default' => FALSE,
				'class' => 'group',
				'linked' => array(
					'show-if-selector' => '#layers-header-background-image',
					'show-if-value' => '',
					'show-if-operator' => '!==',
				)
			),
			'header-background-size' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Stretch', 'layers-pro' ),
				'default' => TRUE,
				'class' => 'group',
				'linked' => array(
					'show-if-selector' => '#layers-header-background-image',
					'show-if-value' => '',
					'show-if-operator' => '!==',
				)
			),
			'header-sticky-breakpoint' => array(
				'type'  => 'layers-range',
				'label' => __( 'Sticky Breakpoint (px)' , 'layers-pro' ),
				'description' => __( 'At what point in the scroll down does your header start sticking.' , 'layers-pro' ),
				'class' => 'group',
				'min' => 0,
				'max' => 500,
				'step' => 1,
				'placeholder' => 270,
				'linked' => array(
					'show-if-selector' => '#layers-header-sticky',
					'show-if-value' => 'true',
				),
			),
			'header-title-styling-heading' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Page Title Styling', 'layers-pro' ) . $pro_badge,
				'description' => __( 'Page titles appear on list pages and pages using the "Blank Page" template they also include breadcrumb navigation.', 'layers-pro' ),
				'class' => 'layers-push-top',
			),
			'title-background-color' => array(
				'label' => '',
				'subtitle' => __( 'Title Background', 'layers-pro' ),
				'type' => 'layers-color',
				'class' => 'group',
			),

			'title-background-image' => array(
				'label' => __( 'Background Image', 'layers-pro' ),
				'type' => 'layers-select-images',
				'class' => 'group',
			),
			'title-background-repeat' => array(
				'label' => __( 'Repeat', 'layers-pro' ),
				'type' => 'layers-select',
				'class' => 'group',
				'default' => 'no-repeat',
				'choices' => array(
					'no-repeat' => __( 'No Repeat', 'layers-pro' ),
					'repeat' => __( 'Repeat', 'layers-pro' ),
					'repeat-x' => __( 'Repeat Horizontal', 'layers-pro' ),
					'repeat-y' => __( 'Repeat Vertical', 'layers-pro' )
				),
				'linked' => array(
					'show-if-selector' => '#layers-title-background-image',
					'show-if-value' => '',
					'show-if-operator' => '!==',
				)
			),
			'title-background-position' => array(
				'label' => __( 'Position', 'layers-pro' ),
				'type' => 'layers-select',
				'class' => 'group',
				'default' => 'center',
				'choices' => array(
					'center' => __( 'Center', 'layers-pro' ),
					'top' => __( 'Top', 'layers-pro' ),
					'bottom' => __( 'Bottom', 'layers-pro' ),
					'left' => __( 'Left', 'layers-pro' ),
					'right' => __( 'Right', 'layers-pro' )
				),
				'linked' => array(
					'show-if-selector' => '#layers-title-background-image',
					'show-if-value' => '',
					'show-if-operator' => '!==',
				)
			),
			'title-background-parallax' => array(
				'label' => __( 'Parallax', 'layers-pro' ),
				'type' => 'layers-checkbox',
				'default' => FALSE,
				'class' => 'group',
				'linked' => array(
					'show-if-selector' => '#layers-title-background-image',
					'show-if-value' => '',
					'show-if-operator' => '!==',
				)
			),
			'title-background-size' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Stretch', 'layers-pro' ),
				'default' => TRUE,
				'class' => 'group',
				'linked' => array(
					'show-if-selector' => '#layers-title-background-image',
					'show-if-value' => '',
					'show-if-operator' => '!==',
				)
			),
			'header-title-sticky-breakpoint' => array(
				'type'  => 'layers-range',
				'label' => __( 'Sticky Breakpoint (px)' , 'layers-pro' ),
				'description' => __( 'At what point in the scroll down does your header start sticking.' , 'layers-pro' ),
				'class' => 'group',
				'min' => 0,
				'max' => 500,
				'step' => 1,
				'placeholder' => 270,
				'linked' => array(
					'show-if-selector' => '#layers-header-title-sticky',
					'show-if-value' => 'true',
				),
			),
			'section-title-heading-color' => array(
				'label' => '',
				'subtitle' => __( 'Title', 'layers-pro' ),
				'description' => __( 'Text color of your page title', 'layers-pro' ),
				'type' => 'layers-color', //#323232
				'class' => 'group',
			),
			'section-title-excerpt-color' => array(
				'label' => '',
				'subtitle' => __( 'Excerpt', 'layers-pro' ),
				'description' => __( 'Text color of your page description', 'layers-pro' ),
				'type' => 'layers-color', // #323232
				'class' => 'group',
			),
			'header-title-height' => array(
				'type'   => 'layers-range',
				'label'  => __( 'Title Height', 'layers-pro' ),
				'class' => 'group',
				'min' => 20,
				'max' => 200,
				'step' => 1,
				'placeholder' => 20,
			),
			'header-title-spacing-after' => array(
				'type'  => 'layers-range',
				'label' => __( 'Title Below Spacing (px)', 'layers-pro' ),
				'class' => 'group',
				'min' => 0,
				'max' => 300,
				'step' => 1,
				'placeholder' => 0,
			),

		);

		$pro_controls['title_tagline'] = array(

			/*'header-logo-styling-header' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Logo Styling', 'layers-pro' ) . $pro_badge,
				// 'description' => '',
				// 'class' => 'layers-push-top',
			),*/
			'header-logo-size' => array(
				'type'  => 'layers-select',
				'label' => __( 'Logo Size', 'layers-pro' ) . $pro_badge,
				'class' => 'group layers-push-top',
				'choices' => array(
					'' => __( 'Auto', 'layers-pro' ),
					'small' => __( 'Small', 'layers-pro' ),
					'medium' => __( 'Medium', 'layers-pro' ),
					'large' => __( 'Large', 'layers-pro' ),
					'massive' => __( 'Massive', 'layers-pro' ),
					'custom' => __( 'Custom', 'layers-pro' ),
				),
			),
			'header-logo-size-custom' => array(
				'type'  => 'layers-range',
				'label' => __( 'Logo Custom Size', 'layers-pro' ),
				'class' => 'group',
				'min' => 1,
				'max' => 200,
				'step' => 1,
				'linked' => array(
					'show-if-selector' => "#layers-header-logo-size",
					'show-if-value' => 'custom',
				),
			),

		);

		$pro_controls['site-general'] = array(
			'header-site-general' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'General', 'layers-pro' ) . $pro_badge,
			),
			'enable-smooth-scroll' => array(
				'label' => __( 'Enable Smooth Scroll', 'layers-pro' ),
				'type' => 'layers-checkbox',
				'default' => FALSE
			),
			'header-body-styling' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Styling', 'layers-pro' ) . $pro_badge,
				'class' => 'layers-push-top',
			),
			'body-background-color' => array(
				'label' => '',
				'subtitle' => __( 'Body Background', 'layers-pro' ),
				'description' => __( 'Body container background color', 'layers-pro' ),
				'type' => 'layers-color',
				'class' => 'group',
			),
			'site-accent-color' => array(
				'label' => '',
				'subtitle' => __( 'Site Accent Color', 'layerswp' ),
				'description' => __( 'Choose a color for your buttons and links.', 'layerswp' ),
				'type' => 'layers-color',
				'default' => FALSE,
				'class' => 'group',
			),
		);

		$controls = layers_insert_config_elements_before(
			'show-layers-badge',
			$controls,
			array(
				'header-position-styling' => array(
					'type' => 'layers-heading',
					'heading_divider' => __( 'Styling', 'layers-pro' ) . $pro_badge,
					'class' => 'layers-push-top',
				),
				'footer-background-color' => array(
					'label' => '',
					'subtitle' => __( 'Footer Background' , 'layers-pro' ),
					'type' => 'layers-color',
					'default' => '#F3F3F3',
					'class' => 'group',
				),
				'footer-height' => array(
					'type'   => 'layers-range',
					'label'  => __( 'Footer Padding', 'layers-pro' ),
					'class' => 'group layers-push-bottom-small',
					'placeholder' => 40,
					'min' => 0,
					'max' => 250,
					'step' => 1,
				),
			)
		);

		$pro_controls['blog-archive'] = array(

			'archive-heading-styling' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Layout &amp; Post Meta', 'layers-pro' ) . $pro_badge,
				'description' => __( 'These settings allow you to choose what meta to display on a single blog page.', 'layers-pro' ),
			),
			'archive-featured-image' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Featured Image', 'layers-pro' ),
				'default' => 'yes',
			),
			'archive-post-meta-date' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Date', 'layers-pro' ),
				'default' => 'yes',
			),
			'archive-post-meta-author' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Author', 'layers-pro' ),
				'default' => 'yes',
			),
			'archive-post-meta-categories' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Categories', 'layers-pro' ),
				'default' => 'yes',
			),
			'archive-post-meta-tags' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Tags', 'layers-pro' ),
				'default' => 'yes',
			),
			'archive-excerpt' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Excerpt', 'layers-pro' ),
				'default' => 'true',
			),
			'archive-excerpt-length' => array(
				'type'   => 'layers-number',
				'label'  => __( 'Excerpt Length (word count)', 'layers-pro' ),
				'default' => 50,
				'linked' => array(
					'show-if-selector' => "#layers-archive-excerpt",
					'show-if-value' => 'true',
				),
			),
			'archive-read-more-button' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( "'Read More' Button", 'layers-pro' ),
				'default' => 'yes',
			),
			'archive-read-more-button-text' => array(
				'type'   => 'layers-text',
				'label'  => __( "'Read More' Button Text", 'layers-pro' ),
				'default' => 'Read More',
				'linked' => array(
					'show-if-selector' => "#layers-archive-read-more-button",
					'show-if-value' => 'true',
				),
			),
		);

		$pro_controls['blog-single'] = array(

			'single-heading-styling' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Post Meta', 'layers-pro' ) . $pro_badge,
				'description' => __( 'These settings allow you to choose what meta to display on a single blog page.', 'layers-pro' ),
			),
			'single-featured-image' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Featured Image', 'layers-pro' ),
				'default' => 'yes',
			),
			'single-post-meta-date' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Date', 'layers-pro' ),
				'default' => 'yes',
			),
			'single-post-meta-author' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Author', 'layers-pro' ),
				'default' => 'yes',
			),
			'single-post-meta-categories' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Categories', 'layers-pro' ),
				'default' => 'yes',
			),
			'single-post-meta-tags' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Tags', 'layers-pro' ),
				'default' => 'yes',
			),

			'single-comment-heading-styling' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Comments', 'layers-pro' ) . $pro_badge,
				'description' => __( 'These settings allow you to control comment display on all posts and pages.', 'layers-pro' ),
			),
			'single-post-comments' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Comments on Posts', 'layers-pro' ),
				'default' => TRUE,
			),
			'single-page-comments' => array(
				'type'   => 'layers-checkbox',
				'label'  => __( 'Comments on Pages', 'layers-pro' ),
				'default' => TRUE,
			),

		);

		$pro_controls['blog-styling'] = array(

			'blog-colors-heading' => array(
				'type' => 'layers-heading',
				'heading_divider' => __( 'Blog Colors', 'layers-pro' ) . $pro_badge,
				'description' => __( 'Customize the various colors of your blog. Make sure you switch to the blog so you can see your changes live.', 'layers-pro' ),
				'class' => 'layers-push-bottom',
			),
			'story-title-color' => array(
				'label' => '',
				'subtitle' => __( 'Titles', 'layers-pro' ),
				'description' => __( 'Post Title Colors', 'layers-pro' ),
				'type' => 'layers-color',
				'class' => 'group',
			),
			'story-link-color' => array(
				'label' => '',
				'subtitle' => __( 'Story Link', 'layers-pro' ),
				'description' => __( 'Link colors in posts', 'layers-pro' ),
				'type' => 'layers-color',
				'class' => 'group',
			),
			'sidebar-well-color' => array(
				'label' => '',
				'subtitle' => __( 'Sidebar Background', 'layers-pro' ),
				'type' => 'layers-color',
				'default' => '#FFFFFF',
				'class' => 'group',
			),
			'story-meta-color' => array(
				'label' => '',
				'subtitle' => __( 'Post Meta', 'layers-pro' ),
				'type' => 'layers-color',
				'class' => 'group',
			),
			'comments-background-color' => array(
				'label' => '',
				'subtitle' => __( 'Comments' , 'layers-pro' ),
				'type' => 'layers-color',
				'class' => 'group',
			),
		);

		$pro_controls['social-networks'] = layers_pro_get_social_networks( 'config' );

		// Remove ColorKit & Layers Pro upsells.
		$controls = layers_remove_config_element( 'upsell-colorkit-heading', $controls );
		$controls = layers_remove_config_element( 'logo-upsell-layers-pro', $controls );
		$controls = layers_remove_config_element( 'header-upsell-layers-pro', $controls );
		$controls = layers_remove_config_element( 'blog-single-upsell-layers-pro', $controls );
		$controls = layers_remove_config_element( 'blog-archive-upsell-layers-pro', $controls );
		$controls = layers_remove_config_element( 'colors-upsell-layers-pro', $controls );
		$controls = layers_remove_config_element( 'footer-upsell-layers-pro', $controls );

		$controls = layers_remove_config_element( 'site-color-heading', $controls );
		$controls = layers_remove_config_element( 'site-accent-color', $controls );

		return array_merge_recursive( $controls,  $pro_controls );
	}

	/**
	 * Apply Customizations
	 */
	function apply_customizer_customizations() {
		$theme = wp_get_theme();

		if( 'layerswp' !== $theme->template ) return;

		$bg_color = layers_get_theme_mod( 'body-background-color', FALSE );

		if( '' != $bg_color ) {

			layers_inline_styles("
				.wrapper-content {
					background-color: $bg_color;
				}
			");
		}

		/**
		 * Menu Styling
		 */

		$primary_menu_a = '.header-site.invert .nav-horizontal > ul > li > a, .header-site .nav-horizontal > ul > li > a, .header-search a';
		$primary_menu_li = '.header-site.invert .nav-horizontal > ul > li, .header-site .nav-horizontal > ul > li';
		$primary_menu_a_hover = '.header-site.invert .nav-horizontal > ul > li:hover > a, .header-site .nav-horizontal > ul > li:hover > a';
		$primary_menu_li_hover = '.header-site.invert .nav-horizontal > ul > li:hover, .header-site .nav-horizontal > ul > li:hover';

		$primary_menu_style = array();
		$primary_menu_li_style = array();
		$primary_menu_hover_style = array();


		// Prep: Menu Text Colors
		$menu_text_color = layers_get_theme_mod( 'menu-text-color' );
		if( $menu_text_color ){
			$primary_menu_style[ 'color' ] = $menu_text_color;
			if( !layers_get_theme_mod( 'menu-hover-text-color' ) )
				$primary_menu_hover_style[ 'color' ] = layers_too_light_then_dark( $menu_text_color );
		}

		$menu_text_shadow = layers_get_theme_mod( 'menu-text-shadow' );
		if ( 'top' == $menu_text_shadow ) {
			$primary_menu_style[ 'text-shadow' ] = '0 -1px rgba(0,0,0,0.3)';
		} else if ( 'bottom' == $menu_text_shadow ) {
			$primary_menu_style[ 'text-shadow' ] = '0 1px rgba(0,0,0,0.3)';
		}

		$menu_text_transform = layers_get_theme_mod( 'menu-text-transform' );
		if( $menu_text_transform ) {
			$primary_menu_style[ 'text-transform' ] = $menu_text_transform;
		}

		/**
		 * Menu Hover Styling
		 */

		$menu_hover_text_color = layers_get_theme_mod( 'menu-hover-text-color' );
		if( $menu_hover_text_color ){
			$primary_menu_hover_style[ 'color' ] = $menu_hover_text_color;
		}

		$menu_hover_text_shadow = layers_get_theme_mod( 'menu-hover-text-shadow' );
		if ( 'top' == $menu_hover_text_shadow ) {
			$primary_menu_hover_style[ 'text-shadow' ] = '0 -1px rgba(0,0,0,0.3)';
		} else if ( 'bottom' == $menu_hover_text_shadow ) {
			$primary_menu_hover_style[ 'text-shadow' ] = '0 1px rgba(0,0,0,0.3)';
		}

		$menu_hover_border_radius = layers_get_theme_mod( 'menu-hover-border-radius' );
		if( $menu_hover_border_radius ) {
			$primary_menu_style[ 'border-radius' ] = $menu_hover_border_radius . 'px';
		}

		$menu_hover_background_color = layers_get_theme_mod( 'menu-hover-background-color' );
		if( $menu_hover_background_color ) {
			$primary_menu_hover_style[ 'background-color' ] = $menu_hover_background_color;
		}

		$menu_hover_border_width = layers_get_theme_mod( 'menu-hover-border-width' );
		if( $menu_hover_border_width ) {
			$primary_menu_style[ 'border-width' ] = $menu_hover_border_width .'px';
			$primary_menu_style[ 'border-style' ] = 'solid';
			$primary_menu_style[ 'border-color' ] = 'transparent';
		}

		$menu_hover_border_color = layers_get_theme_mod( 'menu-hover-border-color' );
		if( $menu_hover_border_color ) {
			$primary_menu_hover_style[ 'border-color' ] = $menu_hover_border_color;
		}


		/**
		 * Apply the Main Menu Styling
		 */
		if( !empty( $primary_menu_hover_style ) ) {

			if( version_compare( LAYERS_VERSION, '1.2.11', '<' ) && ( $menu_hover_background_color || 0 > $menu_hover_border_width ) ){

				$less_padding = array(
					'header-logo-center',
					'header-logo-left',
					'header-logo-right',
				);


				if( in_array( layers_get_theme_mod( 'header-menu-layout' ) , $less_padding ) ) {
					$primary_menu_style[ 'padding' ] = '0px 10px';
				} else {
					$primary_menu_style[ 'padding' ] = '4px 8px';
				}
				$primary_menu_li_style[ 'margin' ] = '0px 6px;';
				layers_inline_styles( $primary_menu_li, array( 'css' => $primary_menu_li_style ) );
			}

			layers_inline_styles( $primary_menu_a_hover, array( 'css' => $primary_menu_hover_style ) );
		}

		if( !empty( $primary_menu_style ) ) {
			layers_inline_styles( $primary_menu_a, array( 'css' => $primary_menu_style ) );
		}

		$sub_menu = '.header-site.invert .sub-menu, .header-site .sub-menu';
		$sub_menu_text = '.header-site.invert .sub-menu li a, .header-site .sub-menu li a';
		$sub_menu_text_hover = '.header-site.invert .sub-menu li:hover a, .header-site .sub-menu li:hover a';
		$sub_menu_li = '.header-site.invert .sub-menu li, .header-site .sub-menu li';
		$sub_menu_li_hover = '.header-site.invert .sub-menu li:hover, .header-site .sub-menu li:hover';

		$submenu_style = array();
		$submenu_text_style = array();
		$submenu_text_hover_style = array();
		$submenu_li_style = array();

		$submenu_text_color = layers_get_theme_mod( 'submenu-text-color' );
		if( $submenu_text_color ){
			$submenu_text_style[ 'color' ] = $submenu_text_color;
			$submenu_text_hover_style[ 'color' ] = layers_too_light_then_dark( $submenu_text_color );
		}
		$submenu_text_shadow = layers_get_theme_mod( 'submenu-text-shadow' );
		if ( 'top' == $submenu_text_shadow ) {
			$submenu_text_style[ 'text-shadow' ] = '0 -1px rgba(0,0,0,0.3)';
		} else if ( 'bottom' == $submenu_text_shadow ) {
			$submenu_text_style[ 'text-shadow' ] = '0 1px rgba(0,0,0,0.3)';
		}
		$submenu_text_transform = layers_get_theme_mod( 'submenu-text-transform' );
		if( $submenu_text_transform ){
			$submenu_text_style[ 'text-transform' ] = $submenu_text_transform;
		}
		$submenu_background_color = layers_get_theme_mod( 'submenu-background-color' );
		if( $submenu_background_color ) {
			$submenu_text_style[ 'background-color' ] = $submenu_background_color;
			$submenu_text_hover_style[ 'background-color' ] = layers_too_light_then_dark( $submenu_background_color );
		}

		$submenu_border_width = layers_get_theme_mod( 'submenu-border-width' );
		$submenu_border_color = layers_get_theme_mod( 'submenu-border-color' );
		if( $submenu_border_width ) {
			$submenu_style[ 'border-width' ] = $submenu_border_width . 'px';
			$submenu_style[ 'border-color' ] = $submenu_border_color;
		}

		$submenu_separator_border_color = layers_get_theme_mod( 'submenu-separate-border-color' );
		if( $submenu_separator_border_color ) {
			$submenu_li_style[ 'border-color' ] = $submenu_separator_border_color;
		}

		/**
		 * Apply the Sub Menu Styling
		 */
		if( !empty( $submenu_style ) ) {
			layers_inline_styles( $sub_menu, array( 'css' => $submenu_style ) );
		}
		if( !empty( $submenu_text_style ) ) {
			layers_inline_styles( $sub_menu_text, array( 'css' => $submenu_text_style ) );
			layers_inline_styles( $sub_menu_text_hover, array( 'css' => $submenu_text_hover_style ) );
		}
		if( !empty( $submenu_li_style ) ) {
			layers_inline_styles( $sub_menu_li, array( 'css' => $submenu_li_style ) );
		}

		/**
		 * Apply Button Styling
		 */

		// Primary Buttons
		layers_pro_apply_control_button_styling( 'buttons-primary', array(
			'input[type="button"]',
			'input[type="submit"]',
			'button',
			'.button',
			'.form-submit input[type="submit"]',
		) );

		// Secondary Buttons
		layers_pro_apply_control_button_styling( 'buttons-secondary', array(
			'.invert input[type="button"]',
			'.invert input[type="submit"]',
			'.invert button',
			'.invert .button',
			'.invert .form-submit input[type="submit"]',
		) );


		/**
		 * Header - Top & Bottom Padding
		 */
		// if ( layers_get_theme_mod( 'header-outer-padding-top') || layers_get_theme_mod( 'header-outer-padding-bottom') ) {
		if ( FALSE ) {

			// Set intial.
			$padding_top    = NULL;
			$padding_bottom = NULL;

			// Get Values.
			if ( layers_get_theme_mod( 'header-outer-padding-top' ) ) {
				$padding_top = layers_get_theme_mod( 'header-outer-padding-top');
			}
			if ( layers_get_theme_mod( 'header-outer-padding-bottom' ) ) {
				$padding_bottom = layers_get_theme_mod( 'header-outer-padding-bottom');
			}

			// Apply Styles.
			layers_inline_styles( '.header-site', array( 'css' => array(
				'padding-top'    => "{$padding_top}px",
				'padding-bottom' => "{$padding_bottom}px",
			) ) );
		}

		/**
		 * Header - Height
		 */
		if ( layers_get_theme_mod( 'header-height') ) {

			// Get Values.
			$padding = ( layers_get_theme_mod( 'header-height') );

			// Apply Styles.
			layers_inline_styles( "
				@media only screen and ( min-width: 769px ) {
					.header-site:not( .is_stuck ) {
						padding-top    : {$padding}px ;
						padding-bottom : {$padding}px ;
					}
				}
			" );
		}

		/**
		 * Header - Link Spacing
		 */
		if ( is_numeric( layers_get_theme_mod( 'menu-item-spacing') ) ) {

			// Get Values.
			$spacing = layers_get_theme_mod( 'menu-item-spacing');

			// Apply Styles.
			layers_inline_styles( '.header-site .nav-horizontal > ul > li', array( 'css' => array(
				'margin-left'    => "{$spacing}px",
				'margin-right' => "{$spacing}px",
			) ) );
		}

		/**
		 * Header - Logo Size
		 */
		$size = layers_get_theme_mod( 'header-logo-size');
		$max_height = layers_get_theme_mod( 'header-logo-size-custom');
		if ( 'custom' === $size && '' !== $max_height ) {

			// Apply Styles.
			layers_inline_styles( '.custom-logo-link img, .site-logo-link img, .mark img', array( 'css' => array(
				'width' => 'auto',
				'max-height'    => "{$max_height}px",
			) ) );
		}

		/**
		 * Footer - Top & Bottom Padding
		 */
		//if ( layers_get_theme_mod( 'footer-outer-padding-top') || layers_get_theme_mod( 'footer-outer-padding-bottom') ) {
		if ( FALSE ) {

			// Set intial.
			$padding_top    = NULL;
			$padding_bottom = NULL;

			// Get Values.
			if ( layers_get_theme_mod( 'footer-outer-padding-top' ) ) {
				$padding_top = layers_get_theme_mod( 'footer-outer-padding-top');
			}
			if ( layers_get_theme_mod( 'footer-outer-padding-bottom' ) ) {
				$padding_bottom = layers_get_theme_mod( 'footer-outer-padding-bottom');
			}

			// Apply Styles.
			layers_inline_styles( "
				.footer-site > .container > .row:first-child {
					padding-top : {$padding_top}px ;
					padding-bottom : {$padding_bottom}px ;
				}
			" );
		}

		/**
		 * Footer - Height
		 */
		if ( layers_get_theme_mod( 'footer-height') ) {

			// Get Values.
			$padding_top = layers_get_theme_mod( 'footer-height' );
			$padding_bottom = layers_get_theme_mod( 'footer-height' );

			// Apply Styles.
			layers_inline_styles( "
				@media only screen and ( min-width: 769px ) {
					.footer-site > .container > .row:first-child {
						padding-top : {$padding_top}px ;
						padding-bottom : {$padding_bottom}px ;
					}
				}
			" );
		}

		/**
		 * Header Title - Height
		 */
		if ( layers_get_theme_mod( 'header-title-height') ) {

			// Get Values.
			$padding_top = layers_get_theme_mod( 'header-title-height');
			$padding_bottom = ( layers_get_theme_mod( 'header-title-height') ) / 2;


			// Apply Styles.
			layers_inline_styles( "
				@media only screen and ( min-width: 769px ) {
					.title-container .title {
						padding-top : {$padding_top}px ;
						padding-bottom : {$padding_bottom}px ;
					}
				}
			" );
		}

		/**
		 * Header Title - Bottom Spacing
		 */
		if ( layers_get_theme_mod( 'header-title-spacing-after') ) {

			// Set intial.
			$spacing = NULL;

			// Get Values.
			if ( layers_get_theme_mod( 'header-title-spacing-after') ) {
				$spacing = layers_get_theme_mod( 'header-title-spacing-after');
			}

			// Apply Styles.
			layers_inline_styles( '.title-container', array( 'css' => array(
				'margin-bottom'    => "{$spacing}px",
			) ) );
		}

		/**
		 * Archive Excerpt
		 */
		if ( ! (BOOL) layers_get_theme_mod( 'archive-excerpt' ) ) {
			remove_action( 'layers_list_post_content', 'layers_excerpt_action' );
		}

		/**
		 * Archive Excerpt - Length
		 */
		if ( layers_get_theme_mod( 'archive-excerpt' ) && 0 < layers_get_theme_mod( 'archive-excerpt-length' ) ) {
			$this->apply_excerpt_length();
		}

		/**
		 * Archive Read More Buttons - On/Off
		 */
		if ( ! (BOOL) layers_get_theme_mod( 'archive-read-more-button') ) {
			remove_action( 'layers_list_read_more', 'layers_read_more_action' );
		}

		/**
		* Header Background
		*/
		$css = array();

		// Background Color
		$header_color = layers_get_theme_mod( 'header-background-color', FALSE );
		if ( '' != $header_color ) {
			$css['background-color'] = $header_color;
		}

		// Background Image
		$header_image = layers_get_theme_mod( 'header-background-image', FALSE );
		if ( '' != $header_image ) {
			$header_image = wp_get_attachment_image_src( $header_image, 'full' );
			$header_image = $header_image[0];
			$css['background-image'] = "url( '$header_image' )";
			$css['background-repeat'] = layers_get_theme_mod( 'header-background-repeat' );
			$css['background-position'] = layers_get_theme_mod( 'header-background-position' );
			if ( 1 == layers_get_theme_mod( 'header-background-size' ) ) $css['background-size'] = 'cover';
		}

		// Apply Background Styling
		layers_inline_styles( '.header-site, .header-site.header-sticky', array( 'css' => $css ) );

		// Apply Background class-name
		if ( isset( $css['background-color'] ) ) {
			layers_maybe_set_invert( $css['background-color'], 'layers_header_class' );
		}

		/**
		* Footer Colors
		*/
		$footer_color = layers_get_theme_mod( 'footer-background-color', FALSE );
		if( '' != $footer_color ) {
			layers_inline_styles("
				.footer-site {
					background-color: $footer_color;
				}
			");
			layers_maybe_set_invert( $footer_color, 'layers_footer_site_class' );
		}

		/**
		 * Title Container
		 */
		$title_background_color = layers_get_theme_mod( 'title-background-color', TRUE );
		if( '' != $title_background_color ) {
			layers_inline_styles("
				.title-container {
					background-color: $title_background_color;
				}
			");
			layers_maybe_set_invert( $title_background_color, 'layers_title_container_class' );
		}


		// Background Image
		$title_image = layers_get_theme_mod( 'title-background-image', FALSE );
		if ( '' != $title_image ) {
			$title_image = wp_get_attachment_image_src( $title_image, 'full' );
			$title_image = $title_image[0];
			$css['background-image'] = "url( '$title_image' )";
			$css['background-repeat'] = layers_get_theme_mod( 'title-background-repeat' );
			$css['background-position'] = layers_get_theme_mod( 'title-background-position' );
			if ( 1 == layers_get_theme_mod( 'title-background-size' ) ) $css['background-size'] = 'cover';

			layers_inline_styles( '.title-container', array( 'css' => $css ) );
		}

		/**
		 * Section Title - Headings
		 */
		$section_title_heading_color = layers_get_theme_mod( 'section-title-heading-color', TRUE );
		$section_title_heading_color_hover = layers_too_light_then_dark( $section_title_heading_color );
		if( '' != $section_title_heading_color ) {
			layers_inline_styles("
				.title-container .title .heading,
				nav.bread-crumbs a {
					color: $section_title_heading_color;
				}
			");
			layers_inline_styles("
				nav.bread-crumbs a:hover,
				nav.bread-crumbs li {
					color: $section_title_heading_color_hover;
				}
			");
		}

		/**
		 * Section Title - Excerpt
		 */
		$section_title_excerpt_color = layers_get_theme_mod( 'section-title-excerpt-color', TRUE );
		if( '' != $section_title_excerpt_color ) {
			layers_inline_styles("
				.title-container .title div.excerpt {
					color: $section_title_excerpt_color;
				}
			");
		}

		/**
		 * Content - Links
		 */
		$story_link_color = layers_get_theme_mod( 'story-link-color', TRUE );
		$story_link_color_hover = layers_too_light_then_dark( $story_link_color );
		if( '' != $story_link_color ) {
			layers_inline_styles("
				.copy a:not(.button),
				.story a:not(.button) {
					color: $story_link_color;
					border-bottom-color: $story_link_color;
				}
			");
			layers_inline_styles("
				.copy a:not(.button):hover,
				.story a:not(.button):hover {
					color: $story_link_color_hover;
					border-bottom-color: $story_link_color_hover;
				}
			");
		}

		/**
		 * Content Titles
		 */
		$story_title_color = layers_get_theme_mod( 'story-title-color', TRUE );
		$story_title_color_hover = layers_too_light_then_dark( $story_title_color );
		if( '' != $story_title_color ) {
			layers_inline_styles("
				.type-post header.section-title .heading,
				.type-post header.section-title .heading a,
				.type-page header.section-title .heading,
				.type-page header.section-title .heading a,
				.heading.comment-title,
				.heading.comment-title a,
				.comment-reply-title,
				.comment-reply-title a {
					color: $story_title_color;
				}
			");
			layers_inline_styles("
				.type-post header.section-title .heading a:hover,
				.type-page header.section-title .heading a:hover,
				.heading.comment-title a:hover,
				.comment-reply-title .heading a:hover {
					color: $story_title_color_hover;
				}
			");
		}

		/**
		 * Sidebar Well
		 */
		$sidebar_well_color = layers_get_theme_mod( 'sidebar-well-color', TRUE );
		if( '' != $sidebar_well_color ) {
			layers_inline_styles("
				.sidebar .well {
					background-color: $sidebar_well_color;
				}
			");
			layers_maybe_set_invert( $sidebar_well_color, 'layers_body_sidebar_class' );
			layers_maybe_set_invert( $sidebar_well_color, 'layers_left_sidebar_class' );
			layers_maybe_set_invert( $sidebar_well_color, 'layers_right_sidebar_class' );
			layers_maybe_set_invert( $sidebar_well_color, 'layers_left_woocommerce_sidebar_class' );
			layers_maybe_set_invert( $sidebar_well_color, 'layers_right_woocommerce_sidebar_class' );
		}

		/**
		 * Post Meta Color
		 */
		$meta_color = layers_get_theme_mod( 'story-meta-color', TRUE );
		if( '' != $meta_color ) {
			layers_inline_styles("
				.type-post .meta-info,
				.type-page .meta-info,
				.type-post .meta-info a,
				.type-page .meta-info a {
					color: $meta_color;
				}
			");
		}

		/**
		 * Comments
		 */

		$comment_background_color = layers_get_theme_mod( 'comments-background-color', TRUE );
		$comment_indent_background_color = 'rgba(' . implode( ', ' , layers_hex2rgb( $comment_background_color ) ) . ', .15 )';
		if( '' != $comment_background_color ) {
			layers_inline_styles( "
				.comment-list > .comment.well,
				.comment-list > .comment-respond {
					background-color: $comment_background_color;
				}
			");
			layers_maybe_set_invert( $comment_background_color, 'layers_comment_list_class' );
		}

	}

	public function apply_featured_image_settings( $string ){

		if ( is_singular() )
			$option_key = 'single';
		else
			$option_key = 'archive';

		if ( ! ( BOOL ) layers_get_theme_mod( $option_key . '-featured-image' ) ) {
			$string = '';
		}

		return $string;
	}

	public function apply_meta_display_settings( $display_array ){

		if ( is_singular() )
			$option_key = 'single';
		else
			$option_key = 'archive';

		$meta = array(
			'date',
			'author',
			'categories',
			'tags',
		);

		foreach( $meta as $meta_key ){
			if ( ! ( BOOL ) layers_get_theme_mod( $option_key . '-post-meta-' . $meta_key ) )
				$display_array = array_diff( $display_array, array( $meta_key ) );
		}

		return $display_array;
	}

	function apply_excerpt_length(){

		if( !function_exists( 'layers_get_theme_mod' ) ) return;

		if( is_archive() || layers_is_post_list_template() )
			add_action( 'excerpt_length', array( $this, 'layers_excerpt_length' ) );
	}

	/**
	 * Archive Excerpt - Length
	 */
	function layers_excerpt_length( $length ) {

		if( !function_exists( 'layers_get_theme_mod' ) ) return $length;

		// Only apply to the archive page
		return layers_get_theme_mod( 'archive-excerpt-length' );
	}

	/**
	* Read More Buttons - Text
	*/
	public function layers_read_more_text( $text ) {

		if( !function_exists( 'layers_get_theme_mod' ) ) return $text;

		return layers_get_theme_mod( 'archive-read-more-button-text' );
	}

	/**
	* Body Class
	*/
	public function body_class( $classes ){
		$classes[] = 'layers-pro-active';
		return $classes;
	}

	/**
	* Header Class
	*/
	public function header_class( $classes ){
		$size = layers_get_theme_mod( 'header-logo-size');
		$parallax = layers_get_theme_mod( 'header-background-parallax');

		if( FALSE !== $parallax ){
			$classes[] = 'layers-parallax';
		}

		if( FALSE !== $size ){
			$classes[] = 'layers-logo-' . $size;
		}

		return $classes;
	}

	/**
	* Title Container Class
	*/
	public function title_container_class( $classes ){
		$parallax = layers_get_theme_mod( 'title-background-parallax');

		if( FALSE !== $parallax ){
			$classes[] = 'layers-parallax';
		}

		return $classes;
	}

	/**
	* Comment Display
	*/
	function comments( $file ){

		if( is_page() && ! ( BOOL ) layers_get_theme_mod( 'single-page-comments' ) ) {
			$file = dirname( __FILE__ ) . '/../includes/empty.php';
		}

		if( is_single() && ! ( BOOL ) layers_get_theme_mod( 'single-post-comments' ) ) {
			$file = dirname( __FILE__ ) . '/../includes/empty.php';
		}

		return $file;
	}

	/**
	* Apply Header Sticky Breakpoint.
	*/
	function apply_sticky_header_breakpoint( $breakpoint ) {

		if ( layers_get_theme_mod( 'header-sticky-breakpoint') ) {
			return layers_get_theme_mod( 'header-sticky-breakpoint');
		}

		return $breakpoint;
	}

	/**
	* Search Button
	*/
	function add_search_button(){

		if ( layers_get_theme_mod( 'header-search-active' ) ) {
			?>
			<div class="header-search">
				<a href="#">
					<i class="l-search"></i>
				</a>
			</div>
			<?php
		}
	}

	/**
	* Search Button
	*/
	function add_search_menu_item( $items, $args ) {

		if ( layers_get_theme_mod( 'header-search-active' ) ) {
			ob_start();
			?>
			<li class="menu-item menu-item-type-search menui-item-layers-search">
				<a href="#">
					<i class="l-search"></i>
				</a>
			</li>
			<?php
			$items .= ob_get_clean();
		}

		return $items;
	}

	/**
	* Search Interface
	*/
	function render_search_interface() {
		?>
		<div class="search-interface-overlay">
			<form role="search" method="get" class="search-interface-holder" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="search-text">
					<?php _e( 'Search:', 'layers-pro' ); ?>
				</label>
				<input
					type="text"
					id="layers-modal-search-field"
					class="search-field"
					placeholder="<?php _e( 'Type Something', 'layers-pro' ); ?>"
					value="<?php if ( isset( $_GET['s'] ) ) echo esc_attr( $_GET['s'] ); ?>"
					name="s"
					title="<?php _e( 'Search for:', 'layers-pro' ); ?>"
					autocomplete="off"
					autocapitalize="off"
				>
			</form>
			<a href="#" class="search-close">
				<i class="l-close"></i>
			</a>
		</div>
		<?php
	}

}

// Initialize
Layers_Pro_Controls::get_instance();
