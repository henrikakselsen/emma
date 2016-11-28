<?php

function layers_pro_apply_control_button_styling( $prefix, $selectors ) {

	$css = array();

	// Prep: Background.
	if ( 'transparent' == layers_get_theme_mod( "{$prefix}-background-style" ) ) {

		/**
		 * Transparent Background.
		 */

		$css['background']   = 'transparent';
	}
	else if ( 'gradient' == layers_get_theme_mod( "{$prefix}-background-style" ) ) {

		/**
		 * Gradient Background.
		 */

		if (
			'' != layers_get_theme_mod( "{$prefix}-background-gradient-start-color", FALSE ) &&
			'' != layers_get_theme_mod( "{$prefix}-background-gradient-end-color", FALSE )
			) {

			$gradient_start_color = layers_get_theme_mod( "{$prefix}-background-gradient-start-color", FALSE );
			$gradient_end_color   = layers_get_theme_mod( "{$prefix}-background-gradient-end-color", FALSE );

			$gradient_start_color_hover = layers_too_light_then_dark( $gradient_start_color, 20 );
			$gradient_end_color_hover   = layers_too_light_then_dark( $gradient_end_color, 20 );

			$gradient_degrees = ( '' != layers_get_theme_mod( "{$prefix}-background-gradient-direction", FALSE ) ) ? layers_get_theme_mod( "{$prefix}-background-gradient-direction", FALSE ) . 'deg, ' : '';
			$css['background'] = "linear-gradient( $gradient_degrees $gradient_start_color, $gradient_end_color )";
		}
	}
	else if ( 'solid' == layers_get_theme_mod( "{$prefix}-background-style" ) ) {

		/**
		 * Solid Background.
		 */

		if ( '' != layers_get_theme_mod( "{$prefix}-background-color", FALSE ) ) {

			$css['background'] = layers_get_theme_mod( "{$prefix}-background-color", FALSE );
		}
	}

	// Prep: Text Color.
	if ( layers_get_theme_mod( "{$prefix}-text-color", FALSE ) ) {
		$css['color'] = layers_get_theme_mod( "{$prefix}-text-color");
	}

	// Prep: Text Shadow.
	if ( layers_get_theme_mod( "{$prefix}-text-shadow", FALSE ) ) {
		if ( 'top' == layers_get_theme_mod( "{$prefix}-text-shadow") ) $css['text-shadow'] = '0 -1px rgba(0,0,0,0.3)';
		if ( 'bottom' == layers_get_theme_mod( "{$prefix}-text-shadow") ) $css['text-shadow'] = '0 1px rgba(0,0,0,0.3)';
	}

	// Prep: Text Transform.
	if ( layers_get_theme_mod( "{$prefix}-text-transform" ) ) {
		$css['text-transform'] = layers_get_theme_mod( "{$prefix}-text-transform" );
	}

	// Prep: Button Shadow.
	if ( layers_get_theme_mod( "{$prefix}-shadow", FALSE ) ) {
		if ( 'small' == layers_get_theme_mod( "{$prefix}-shadow") ) $css['box-shadow'] = '0 1px 0 rgba(0,0,0,0.15)';
		if ( 'medium' == layers_get_theme_mod( "{$prefix}-shadow") ) $css['box-shadow'] = '0 1px 5px rgba(0,0,0,0.2)';
		if ( 'large' == layers_get_theme_mod( "{$prefix}-shadow") ) $css['box-shadow'] = '0 3px 10px rgba(0,0,0,0.2)';
	}

	// Prep: Button Border Width.
	if ( '' != layers_get_theme_mod( "{$prefix}-border-width", FALSE ) ) {
		$css['border-width'] = layers_get_theme_mod( "{$prefix}-border-width") . 'px';
	}

	// Prep: Button Border Color.
	if ( '' !== layers_get_theme_mod( "{$prefix}-border-color") ) {
		$css['border-color'] = layers_get_theme_mod( "{$prefix}-border-color", FALSE );
	}

	// Prep: Border Radius.
	if ( '' !== layers_get_theme_mod( "{$prefix}-border-radius" ) && 0 !== layers_get_theme_mod( "{$prefix}-border-radius") ) {
		$css['border-radius'] = layers_get_theme_mod( "{$prefix}-border-radius") . 'px';
	}

	/**
	 * Apply Button Styling
	 */
	layers_pro_apply_button_styling( $selectors, $css );
}

function layers_pro_apply_widget_button_styling( $widget, $item, $selectors ) {

	// Make sure the 'buttons' values are at the root of the item instance, not nested deeper in 'design' - so it is one size fits all.
	if ( isset( $item['design'] ) ) {
		foreach ( $item['design'] as $key => $value ) {
			if ( -1 < strpos( $key, 'buttons-' ) && isset( $item['design'] ) ) {
				$item = $item['design'];
			}
		}
	}

	$css = array();

	// Prep: Background.
	if ( 'transparent' == $widget->check_and_return( $item, 'buttons-background-style' ) ) {

		/**
		 * Transparent Background.
		 */

		$css['background']       = 'transparent';
	}
	else if ( 'gradient' == $widget->check_and_return( $item, 'buttons-background-style' ) ) {

		if (
				NULL != $widget->check_and_return( $item, 'buttons-background-gradient-start-color' ) &&
				NULL != $widget->check_and_return( $item, 'buttons-background-gradient-end-color' )
			) {

			/**
			 * Gradient Background.
			 */

			$gradient_start_color = $widget->check_and_return( $item, 'buttons-background-gradient-start-color' );
			$gradient_end_color   = $widget->check_and_return( $item, 'buttons-background-gradient-end-color' );

			$gradient_start_color_hover = layers_too_light_then_dark( $gradient_start_color, 20 );
			$gradient_end_color_hover   = layers_too_light_then_dark( $gradient_end_color, 20 );

			$gradient_degrees = ( NULL != $widget->check_and_return( $item, 'buttons-background-gradient-direction' ) ) ? $widget->check_and_return( $item, 'buttons-background-gradient-direction' ) . 'deg, ' : '';
			$css['background'] = "linear-gradient( $gradient_degrees $gradient_start_color, $gradient_end_color )";
		}
	}
	else if ( 'solid' == $widget->check_and_return( $item, 'buttons-background-style' ) ) {

		/**
		 * Solid Background.
		 */

		if ( NULL != $widget->check_and_return( $item, 'buttons-background-color' ) ) {

			$css['background'] = $widget->check_and_return( $item, 'buttons-background-color' );
		}
	}

	// Prep: Text Color.
	if ( NULL != $widget->check_and_return( $item, 'buttons-text-color' ) ) {
		$css['color'] = $widget->check_and_return( $item, 'buttons-text-color');
	}

	// Prep: Text Shadow.
	if ( NULL != $widget->check_and_return( $item, 'buttons-text-shadow' ) ) {
		if ( 'top' == $widget->check_and_return( $item, 'buttons-text-shadow') ) $css['text-shadow'] = '0 -1px rgba(0,0,0,0.3)';
		if ( 'bottom' == $widget->check_and_return( $item, 'buttons-text-shadow') ) $css['text-shadow'] = '0 1px rgba(0,0,0,0.3)';
	}

	// Prep: Text Transform.
	if ( NULL != $widget->check_and_return( $item, 'buttons-text-transform' ) ) {
		$css['text-transform'] = $widget->check_and_return( $item, 'buttons-text-transform' );
	}

	// Prep: Button Shadow.
	if ( NULL != $widget->check_and_return( $item, 'buttons-shadow' ) ) {
		if ( 'small' == $widget->check_and_return( $item, 'buttons-shadow') ) $css['box-shadow'] = '0 1px 0 rgba(0,0,0,0.15)';
		if ( 'medium' == $widget->check_and_return( $item, 'buttons-shadow') ) $css['box-shadow'] = '0 1px 5px rgba(0,0,0,0.2)';
		if ( 'large' == $widget->check_and_return( $item, 'buttons-shadow') ) $css['box-shadow'] = '0 3px 10px rgba(0,0,0,0.2)';
	}

	// Prep: Button Border Width.
	if ( NULL != $widget->check_and_return( $item, 'buttons-border-width' ) ) {
		$css['border-width'] = $widget->check_and_return( $item, 'buttons-border-width') . 'px';
	}

	// Prep: Button Border Color.
	if ( NULL != $widget->check_and_return( $item, 'buttons-border-color') ) {
		$css['border-color'] = $widget->check_and_return( $item, 'buttons-border-color');
	}

	// Prep: Border Radius.
	if ( $widget->check_and_return( $item, 'buttons-border-radius' ) && 0 !== $widget->check_and_return( $item, 'buttons-border-radius') ) {
		$css['border-radius'] = $widget->check_and_return( $item, 'buttons-border-radius') . 'px';
	}

	/**
	 * Apply Button Styling
	 */
	return layers_pro_apply_button_styling( $selectors, $css );
}

function layers_pro_apply_button_styling( $selectors, $css ) {

	$styles = '';

	/**
	 * Apply Main Styles
	 */

	$styles .= layers_inline_styles( implode( ', ', $selectors ), array( 'css' => $css ) );

	/**
	 * Apply Main Styles :before & :after.
	 */

	$before_and_after_css = array();
	if ( isset( $css['color'] ) ) $before_and_after_css['color'] = $css['color'];
	if ( isset( $css['text-shadow'] ) ) $before_and_after_css['text-shadow'] = $css['text-shadow'];
	$styles .= layers_inline_styles( implode( ':before, ', $selectors ) . ':before ' . implode( ':after, ', $selectors ) . ':after', array( 'css' => $before_and_after_css ) );

	/**
	 * Apply Hover Styles
	 */

	$hover_css = array();

	// Background Color.
	if ( isset( $css['background'] ) ) {

		if ( 0 === strpos( $css['background'], '#' ) ) {

			// Background is a #hex color - so set background to a lighter shade of that color.
			// $hover_css['background'] = layers_too_light_then_dark( $css['background'] );
			$hover_css['background'] = layers_adjust_brightness( $css['background'], 35, true );
		}
		/*
		elseif (
				'transparent' == $css['background'] &&
				isset( $css['border-width'] ) && 0 !== ( (int) $css['border-width'] ) &&
				isset( $css['border-color'] )
			) {

			// Background is transparent, it has a font color is set - so use the font color for the hover background color.
			$hover_css['background'] = $css['border-color'];
			$hover_css['color'] = layers_light_or_dark( $css['border-color'], '#000000', '#FFFFFF' );
		}
		elseif (
				'transparent' == $css['background'] &&
				isset( $css['color'] )
			) {

			// Background is transparent, it has a font color is set - so use the font color for the hover background color.
			$hover_css['background'] = $css['color'];
			$hover_css['color'] = layers_light_or_dark( $css['color'], '#000000', '#FFFFFF' );
		}
		elseif ( 'transparent' == $css['background'] ) {

			// Background is set to transparent - so set the hover to transparent too.
			$hover_css['background'] = 'transparent';
		}
		*/
	}

	// Text Color.
	if ( isset( $css['border-color'] ) ) {

		if (
				0 === strpos( $css['border-color'], '#' ) &&
				isset( $css['border-width'] ) && 0 !== ( (int) $css['border-width'] )
			) {
			// $hover_css['border-color'] = layers_too_light_then_dark( $css['border-color'] );
			$hover_css['border-color'] = layers_adjust_brightness( $css['border-color'], -55, true );
		}
	}

	// Text Color.
	if ( isset( $css['color'] ) && ! isset( $hover_css['color'] ) ) {
		/*
		// $hover_css['color'] = layers_too_light_then_dark( $css['color'] );
		$hover_css['color'] = layers_adjust_brightness( $css['color'], 35, true );
		*/
	}

	// Apply hover styles.
	$styles .= layers_inline_styles( implode( ':hover, ', $selectors ) . ':hover', array( 'css' => $hover_css ) );

	/**
	 * Apply Hover Styles :before & :after.
	 */

	$before_and_after_css = array();
	if ( isset( $hover_css['color'] ) ) $before_and_after_css['color'] = $hover_css['color'];
	if ( isset( $hover_css['text-shadow'] ) ) $before_and_after_css['text-shadow'] = $hover_css['text-shadow'];

	$styles .= layers_inline_styles( implode( ':before, ', $selectors ) . ':before ' . implode( ':after, ', $selectors ) . ':after', array( 'css' => $before_and_after_css ) );

	// Debugging:
	global $wp_customize;
	if ( $wp_customize && ( ( bool ) layers_get_theme_mod( 'dev-switch-button-css-testing' ) ) ) {

		echo '<pre style="font-size:11px;">';

		if ( 0 === strpos( $selectors[0], '#' ) )
			print_r( $selectors );
		else
			echo "GLOBAL\n";

		echo "button -----------------------\n";
		if ( empty( $css ) )
			echo '';
		else
			foreach ( $css as $key => $value )
				echo "$key: $value\n";

		echo "button:hover -----------------\n";
		if ( empty( $hover_css ) )
			echo '';
		else
			foreach ( $hover_css as $key => $value )
				echo "$key: $value\n";

		echo '</pre>';
	}

	return $styles;
}

function layers_pro_get_social_networks( $type = NULL ) {

	$return_collection = array();

	$networks = array(
		'apple'         => array( 'name' => 'Apple',            'icon_class' => 'fa fa-apple',           'base_url' => 'itunes.apple.com' ), // New
		'behance'       => array( 'name' => 'Behance',          'icon_class' => 'fa fa-behance',         'base_url' => 'behance.net' ),
		'bitbucket'     => array( 'name' => 'Bitbucket',        'icon_class' => 'fa fa-bitbucket',       'base_url' => 'bitbucket.org' ),
		'dribbble'      => array( 'name' => 'Dribbble',         'icon_class' => 'fa fa-dribbble',        'base_url' => 'dribbble.com' ),
		'dropbox'       => array( 'name' => 'Dropbox',          'icon_class' => 'fa fa-dropbox',         'base_url' => 'dropbox.com' ),
		'facebook'      => array( 'name' => 'Facebook',         'icon_class' => 'fa fa-facebook',        'base_url' => 'facebook.com' ),
		'flickr'        => array( 'name' => 'Flickr',           'icon_class' => 'fa fa-flickr',          'base_url' => 'flickr.com' ),
		'foursquare'    => array( 'name' => 'Foursquare',       'icon_class' => 'fa fa-foursquare',      'base_url' => 'foursquare.com' ),
		'github'        => array( 'name' => 'Github',           'icon_class' => 'fa fa-github',          'base_url' => 'github.com' ),
		'gittip'        => array( 'name' => 'GitTip',           'icon_class' => 'fa fa-gittip',          'base_url' => 'gittip.com' ),
		'instagram'     => array( 'name' => 'Instagram',        'icon_class' => 'fa fa-instagram',       'base_url' => 'instagr.am' ),
		'instagram'     => array( 'name' => 'Instagram',        'icon_class' => 'fa fa-instagram',       'base_url' => 'instagram.com' ),
		'linkedin'      => array( 'name' => 'LinkedIn',         'icon_class' => 'fa fa-linkedin',        'base_url' => 'linkedin.com' ),
		'lastfm'        => array( 'name' => 'Last.fm',          'icon_class' => 'fa fa-lastfm',          'base_url' => 'last.fm' ), // New
		'medium'        => array( 'name' => 'Medium',           'icon_class' => 'fa fa-medium',          'base_url' => 'medium.com' ),
		'envelope'      => array( 'name' => 'Email',            'icon_class' => 'fa fa-envelope',        'base_url' => 'mailto:' ),
		'pinterest'     => array( 'name' => 'Pinterest',        'icon_class' => 'fa fa-pinterest',       'base_url' => 'pinterest.com' ),
		'google-plus'   => array( 'name' => 'Google+',          'icon_class' => 'fa fa-google-plus',     'base_url' => 'plus.google.com' ),
		'renren'        => array( 'name' => 'RenRen',           'icon_class' => 'fa fa-renren',          'base_url' => 'renren.com' ),
		'slack'         => array( 'name' => 'Slack',            'icon_class' => 'fa fa-slack',           'base_url' => 'slack.com' ),
		'spotify'       => array( 'name' => 'Spotify',          'icon_class' => 'fa fa-spotify',         'base_url' => 'spotify.com' ), // New
		'soundcloud'    => array( 'name' => 'Soundcloud',       'icon_class' => 'fa fa-soundcloud',      'base_url' => 'soundcloud.com' ),
		'trello'        => array( 'name' => 'Trello',           'icon_class' => 'fa fa-trello',          'base_url' => 'trello.com' ),
		'tumblr'        => array( 'name' => 'Tumblr',           'icon_class' => 'fa fa-tumblr',          'base_url' => 'tumblr.com' ),
		'twitter'       => array( 'name' => 'Twitter',          'icon_class' => 'fa fa-twitter',         'base_url' => 'twitter.com' ),
		'vk'            => array( 'name' => 'VK',               'icon_class' => 'fa fa-vk',              'base_url' => 'vk.com' ),
		'vine'          => array( 'name' => 'Vine',             'icon_class' => 'fa fa-vine',            'base_url' => 'vine.co' ),
		'vimeo'         => array( 'name' => 'Vimeo',            'icon_class' => 'fa fa-vimeo',           'base_url' => 'vimeo.com' ),
		'weibo'         => array( 'name' => 'Weibo',            'icon_class' => 'fa fa-weibo',           'base_url' => 'weibo.com' ),
		'xing'          => array( 'name' => 'Xing',             'icon_class' => 'fa fa-xing',            'base_url' => 'xing.com' ),
		'youtube'       => array( 'name' => 'YouTube',          'icon_class' => 'fa fa-youtube',         'base_url' => 'youtube.com' ),
	);


	// Reformat the array for the various usage types.

	if ( 'config' == $type ) {

		/**
		 * Format array for use with Controls Config.
		 */

		$return_collection_first = array();
		$return_collection_last = array();

		foreach ( $networks as $key => $value ) {

			$return_item = array(
				'type'   => 'layers-text',
				'label'  => '<i class="' . $value['icon_class'] . '"></i>&nbsp; ' . __( $value['name'], 'layers-pro' ),
				'default' => '',
				'placeholder' => 'http://' . $value['base_url'],
			);

			if ( get_theme_mod( "layers-social-network-{$key}" ) )
				$return_collection_first[ "social-network-{$key}" ] = $return_item;
			else
				$return_collection_last[ "social-network-{$key}" ] = $return_item;
		}

		$return_collection = $return_collection_first + $return_collection_last;
	}
	else if ( 'select-icons' == $type ) {

		/**
		 * Format array for use with forms Select-Icons.
		 */

		$return_collection_first = array();
		$return_collection_last = array();

		foreach ( $networks as $key => $value ) {

			$return_item = array(
				'name'   => $value['name'],
				'class' => 'fa-2x ' . $value['icon_class'],
				'data' => array(
					'tip' => $value['name'],
				),
			);

			if ( get_theme_mod( "layers-social-network-{$key}" ) )
				$return_collection_first[$key] = $return_item;
			else
				$return_collection_last[$key] = $return_item;
		}

		$return_collection = $return_collection_first + $return_collection_last;
	}
	elseif( 'select' == $type ) {

		/**
		 * Format array for use with forms Select.
		 */

		foreach ( $networks as $key => $value ) {
			$return_collection[ $key ] = $value['name'];
		}
	}
	elseif( 'native-with-values' == $type ) {

		/**
		 * Format array for use with forms Select.
		 */

		foreach ( $networks as $key => $value ) {
			$value['value'] = get_theme_mod( "layers-social-network-{$key}" );
			$return_collection[ $key ] = $value;
		}
	}
	else {

		/**
		 * Format array natively.
		 */

		$return_collection = $networks;
	}

	return $return_collection;
}

/**
 * Find social links in top-level menu items, add icon HTML
 */
add_filter( 'wp_nav_menu_objects', 'layers_pro_convert_social_nav_menu_items', 20, 2 );

function layers_pro_convert_social_nav_menu_items( $sorted_menu_items, $args ){

	$networks = layers_pro_get_social_networks();

	foreach( $sorted_menu_items as &$item ) {

		// Skip submenu items.
		if ( 0 != $item->menu_item_parent ) {
			continue;
		}

		// Dynamically apply the iconclass.
		foreach( $networks as $network_key => $network_values ) {
			if ( false !== strpos( strtolower( $item->url ), strtolower( $network_values['base_url'] ) ) ) {
				$icon_class = $network_values['icon_class'];
				$item->title = "<i class='{$icon_class}'></i>";
				// $item->title = "<span class='{$icon_class}'></span><span class='fa-hidden'>{$item->title}</span>";
			}
		}
	}

	return $sorted_menu_items;
}

// Render layers customizer menu
// add_action( 'customize_controls_print_footer_scripts' , 'layers_pro_render_menu_icons_interface' ); // Customizer
add_action( 'print_media_templates' , 'layers_pro_render_menu_icons_interface' ); // Admin

function layers_pro_render_menu_icons_interface() {

	$form_elements = new Layers_Form_Elements(); ?>
	<div class="layer-pro-menu-icons-interface">

		<label>
			<?php _e( 'Social Icons', 'layers-pro' ); ?>
		</label>

		<div class="layers-visuals-item layers-icon-group">
			<?php echo $form_elements->input(
				array(
					'type' => 'select-icons',
					'name' => '',
					'id' => '',
					'placeholder' => __( 'e.g. http://facebook.com/oboxthemes', 'layers-pro' ),
					'value' => '',
					'options' => layers_pro_get_social_networks( 'select-icons' ),
				)
			); ?>
		</div>

		<span class="layers-form-item-description">
			<?php _e( 'Your Social Network links are set in Customizer > Site Settings > Social Networks.', 'layers-pro' ); ?>
		</span>

	</div>
	<?php
}