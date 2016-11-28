<?php

/**
 * Retrieve theme modification value for the current theme.
 *
 * @param string $name Theme modification name.
 * @param string $allow_empty Whether the Theme modification should return empty, or the default, if no value is set.
 * @return string
 */
if( !function_exists( 'layers_devkit_get_option' ) ) {
	function layers_devkit_get_option( $name = '', $allow_empty = TRUE ) {

		global $layers_devkit;

		$layers_devkit_defaults = $layers_devkit->defaults;

		// Set option default
		$default = ( isset( $layers_devkit_defaults[ $name ] ) ? $layers_devkit_defaults[ $name ] : FALSE );

		// If color control always return a value
		/*
		@TODO: Bring this back in at a later date, if necessary
		if (
				isset( $layers_devkit_defaults[ $name ][ 'type' ] ) &&
				'layers-color' == $layers_devkit_defaults[ $name ][ 'type' ]
			){
			$default = '';
		}
 		*/

		// Get theme option
		$theme_mod = get_option( $name, $default );

		// Template can choose whether to allow empty
		if ( '' == $theme_mod && FALSE == $allow_empty && FALSE != $default ) {
			$theme_mod = $default;
		}

		// Return theme option
		return $theme_mod;
	}
}

?>