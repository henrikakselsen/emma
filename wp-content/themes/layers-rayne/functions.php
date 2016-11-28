<?php
/*
*
* Main Functions - Rayne Layers Child Theme
*
*
* # Enqueue Scripts/Styles
* # Add Post Formats
* # Register Plugins for this theme
* # Customizer and frontned Inline Styles
* # Preset Layouts, The following filter adds your Layers pages to the preset layouts under Layers > Add Page
* # Instagram Widget
* # TGM Activation Class
*/


/*
*
* Enqueue Scripts/Styles
*
*/
function rayne_scripts() {
	wp_enqueue_style( 'magnific-popup', get_stylesheet_directory_uri() . '/assets/css/magnific-popup.css' );
	wp_enqueue_style( 'jquery-bxslider', get_stylesheet_directory_uri() . '/assets/css/bxslider.css' );
	wp_enqueue_script( 'js-bxslider', get_stylesheet_directory_uri() . '/assets/js/jquery.bxslider.min.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script( 'js-magnific', get_stylesheet_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array('jquery'), '1.0.0', false);
	wp_enqueue_script( 'js-custom', get_stylesheet_directory_uri() . '/assets/js/child-custom.js', array('jquery'), '1.0.0', true);
}

add_action( 'wp_enqueue_scripts', 'rayne_scripts' );

/*
*
* Add Post Formats
*
*/
function rayne_layerswp_formats(){
	add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio' ) );
}
add_action( 'after_setup_theme', 'rayne_layerswp_formats');


/*
*
* Customizer Defaults
*
*/
function rayne_layerswp_customizer_defaults( $defaults ){

    $defaults = array(

        //Site Settings
        'header-logo-size' => 'custom',
        'header-logo-size-custom' => '150',
        'body-background-color' => '#FFFFFF',
        'site-accent-color' => '#161616',
        'body-fonts' => 'Merriweather',
        'menu-fonts' => 'Montserrat',
        'heading-fonts' => 'Montserrat',
        'form-fonts' => 'Montserrat',
        'buttons-primary-border-radius' => '100',

        //Header
        'header-width' => 'layout-boxed',
        'header-menu-layout' => 'header-logo-center-top',
        'header-sticky' => '1',
        'header-search-active' => '1',
        'header-background-color' => '#FFFFFF',

        //Blog
        'archive-right-sidebar' => '1',
        'archive-left-sidebar' => '0',
        'single-right-sidebar' => '0',
        'single-left-sidebar' => '0',

        //Footer
        'footer-width' => 'layout-fullwidth',
        'footer-sidebar-count' => '1',
        'footer-background-color' => '#2b2b2b'

    );

    return $defaults;
}
add_filter( 'layers_customizer_control_defaults', 'rayne_layerswp_customizer_defaults' );


/*
* Preset Layouts
*
* The following filter adds your Layers pages to the preset layouts under Layers > Add Page
*
*/
function rayne_layerswp_presets_layout( $layers_preset_layouts ){

    /* Lets include all presets */

    $rayne_layerswp_presets[ 'layout-one' ] = array(
        'title' => __( 'Home', 'rayne-layers' ),
        'screenshot' => get_stylesheet_directory_uri() . '/assets/preset-screenshots/home-page.jpg',
        'screenshot_type' => 'jpeg',
        'json' =>  '{"obox-layers-builder-78":{"layers-widget-post-9":{"design":{"layout":"layout-boxed","columns":"2","column-background-color":"#ffffff","gutter":"on","gutter-CHECKBOX":"on","buttons-size":"","buttons-background-style":"","buttons-background-color":"","buttons-background-gradient-start-color":"","buttons-background-gradient-end-color":"","buttons-background-gradient-direction":"","buttons-text-color":"","buttons-text-shadow":"","buttons-text-transform":"","buttons-border-width":"","buttons-border-color":"","buttons-border-radius":"","buttons-shadow":"","liststyle":"list-grid","imageratios":"image-square","background":{"color":"","image":"","repeat":"no-repeat","position":"center","parallax-CHECKBOX":"on","stretch-CHECKBOX":"on","darken-CHECKBOX":"on"},"advanced":{"padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""},"anchor":"","customclass":"","customcss":""},"fonts":{"heading-type":"h3","align":"text-left","size":"medium","color":""}},"text_style":"regular","show_pagination":"on","show_pagination-CHECKBOX":"on","show_media":"on","show_media-CHECKBOX":"on","show_titles":"on","show_titles-CHECKBOX":"on","show_excerpts":"on","show_excerpts-CHECKBOX":"on","excerpt_length":"200","show_dates-CHECKBOX":"on","show_author-CHECKBOX":"on","show_tags-CHECKBOX":"on","show_categories-CHECKBOX":"on","show_call_to_action":"on","show_call_to_action-CHECKBOX":"on","call_to_action":"Read More","title":"","excerpt":"","category":"0","posts_per_page":"6","order":"{\"orderby\":\"date\",\"order\":\"desc\"}"}}}'
    );

    $rayne_layerswp_presets[ 'rayne-services' ] = array(
        'title' => __( 'Services', 'layers-rayne' ),
        'screenshot' => get_stylesheet_directory_uri() . '/assets/preset-screenshots/services.jpg',
        'screenshot_type' => 'png',
        'json' =>  '{"obox-layers-builder-35":{"layers-widget-slide-3":{"design":{"layout":"layout-fullwidth","advanced":{"padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""},"anchor":"","customclass":"","customcss":""}},"autoheight_slides-CHECKBOX":"on","slide_height":"650","show_slider_arrows-CHECKBOX":"on","slider_arrow_color":"","show_slider_dots-CHECKBOX":"on","animation_type":"slide","autoplay_slides-CHECKBOX":"on","slide_time":"","focus_slide":"0","slide_ids":"725","slides":{"725":{"design":{"background":{"color":"#000000","image":"http:\/\/obox.beta\/rayne\/wp-content\/uploads\/sites\/35\/2016\/10\/ink-banner-01.jpg","video-type":"","video-mp4":"","video-youtube":"","video-vimeo":"","repeat":"no-repeat","position":"center","parallax":"on","parallax-CHECKBOX":"on","stretch":"on","stretch-CHECKBOX":"on","darken-CHECKBOX":"on"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"heading-type":"h3","align":"text-center","size":"large","color":""},"buttons-size":"","buttons-background-style":"","buttons-background-color":"","buttons-background-gradient-start-color":"","buttons-background-gradient-end-color":"","buttons-background-gradient-direction":"","buttons-text-color":"","buttons-text-shadow":"","buttons-text-transform":"","buttons-border-width":"","buttons-border-color":"","buttons-border-radius":"","buttons-shadow":"","advanced":{"customclass":""}},"title":"I Love to Write","excerpt":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eget eleifend nunc. Vivamus imperdiet lacus nec accumsan accumsan. Proin nec euismod lorem.<\/p>","button":{"link_type":"custom","link_type_custom":"","link_type_post":"","link_type_post_type_archive":"","link_type_taxonomy_archive":"","link_text":""}}}},"layers-widget-column-5":{"design":{"layout":"layout-boxed","liststyle":"list-grid","gutter":"on","gutter-CHECKBOX":"on","background":{"color":"#2b2b2b","image":"","repeat":"no-repeat","position":"center","parallax-CHECKBOX":"on","stretch-CHECKBOX":"on","darken-CHECKBOX":"on"},"advanced":{"padding":{"top":"40","right":"","bottom":"20","left":""},"margin":{"top":"","right":"","bottom":"","left":""},"anchor":"","customclass":"","customcss":""},"fonts":{"heading-type":"h3","align":"text-left","size":"small","color":""}},"title":"","excerpt":"","column_ids":"485,502,696","columns":{"485":{"design":{"background":{"color":"","image":"","repeat":"no-repeat","position":"center","parallax-CHECKBOX":"on","stretch-CHECKBOX":"on","darken-CHECKBOX":"on"},"featuredimage":"http:\/\/obox.beta\/rayne\/wp-content\/uploads\/sites\/35\/2016\/10\/apartments-streets.jpg","featuredvideo":"","imageratios":"image-round","featuredimage-size":"64","imagealign":"image-left","fonts":{"heading-type":"h5","align":"text-left","size":"small","color":""},"buttons-size":"","buttons-background-style":"","buttons-background-color":"","buttons-background-gradient-start-color":"","buttons-background-gradient-end-color":"","buttons-background-gradient-direction":"","buttons-text-color":"","buttons-text-shadow":"","buttons-text-transform":"","buttons-border-width":"","buttons-border-color":"","buttons-border-radius":"","buttons-shadow":"","advanced":{"padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","bottom":""},"customclass":""}},"width":"4","title":"Your service title","excerpt":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eget eleifend nunc.<\/p>","button":{"link_type":"custom","link_type_custom":"","link_type_post":"","link_type_post_type_archive":"","link_type_taxonomy_archive":"","link_text":""}},"502":{"design":{"background":{"color":"","image":"","repeat":"no-repeat","position":"center","parallax-CHECKBOX":"on","stretch-CHECKBOX":"on","darken-CHECKBOX":"on"},"featuredimage":"http:\/\/obox.beta\/rayne\/wp-content\/uploads\/sites\/35\/2016\/10\/gourmet-04.jpg","featuredvideo":"","imageratios":"image-round","featuredimage-size":"64","imagealign":"image-left","fonts":{"heading-type":"h4","align":"text-left","size":"small","color":""},"buttons-size":"","buttons-background-style":"","buttons-background-color":"","buttons-background-gradient-start-color":"","buttons-background-gradient-end-color":"","buttons-background-gradient-direction":"","buttons-text-color":"","buttons-text-shadow":"","buttons-text-transform":"","buttons-border-width":"","buttons-border-color":"","buttons-border-radius":"","buttons-shadow":"","advanced":{"padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","bottom":""},"customclass":""}},"width":"4","title":"Your service title","excerpt":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eget eleifend nunc.<\/p>","button":{"link_type":"custom","link_type_custom":"","link_type_post":"","link_type_post_type_archive":"","link_type_taxonomy_archive":"","link_text":""}},"696":{"design":{"background":{"color":"","image":"","repeat":"no-repeat","position":"center","parallax-CHECKBOX":"on","stretch-CHECKBOX":"on","darken-CHECKBOX":"on"},"featuredimage":"http:\/\/obox.beta\/rayne\/wp-content\/uploads\/sites\/35\/2016\/10\/apartments-streets.jpg","featuredvideo":"","imageratios":"image-round","featuredimage-size":"64","imagealign":"image-left","fonts":{"heading-type":"h4","align":"text-left","size":"small","color":""},"buttons-size":"","buttons-background-style":"","buttons-background-color":"","buttons-background-gradient-start-color":"","buttons-background-gradient-end-color":"","buttons-background-gradient-direction":"","buttons-text-color":"","buttons-text-shadow":"","buttons-text-transform":"","buttons-border-width":"","buttons-border-color":"","buttons-border-radius":"","buttons-shadow":"","advanced":{"padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","bottom":""},"customclass":""}},"width":"4","title":"Your service title","excerpt":"<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eget eleifend nunc.<\/p>","button":{"link_type":"custom","link_type_custom":"","link_type_post":"","link_type_post_type_archive":"","link_type_taxonomy_archive":"","link_text":""}}}},"layers-widget-post-7":{"design":{"layout":"layout-boxed","columns":"2","column-background-color":"#ffffff","gutter":"on","gutter-CHECKBOX":"on","buttons-size":"","buttons-background-style":"","buttons-background-color":"","buttons-background-gradient-start-color":"","buttons-background-gradient-end-color":"","buttons-background-gradient-direction":"","buttons-text-color":"","buttons-text-shadow":"","buttons-text-transform":"","buttons-border-width":"","buttons-border-color":"","buttons-border-radius":"","buttons-shadow":"","liststyle":"list-grid","imageratios":"image-square","background":{"color":"","image":"","repeat":"no-repeat","position":"center","parallax-CHECKBOX":"on","stretch-CHECKBOX":"on","darken-CHECKBOX":"on"},"advanced":{"padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""},"anchor":"","customclass":"","customcss":""},"fonts":{"heading-type":"h3","align":"text-center","size":"medium","color":""}},"text_style":"regular","show_pagination-CHECKBOX":"on","show_media":"on","show_media-CHECKBOX":"on","show_titles":"on","show_titles-CHECKBOX":"on","show_excerpts":"on","show_excerpts-CHECKBOX":"on","excerpt_length":"200","show_dates-CHECKBOX":"on","show_author-CHECKBOX":"on","show_tags-CHECKBOX":"on","show_categories-CHECKBOX":"on","show_call_to_action":"on","show_call_to_action-CHECKBOX":"on","call_to_action":"Read More","title":"Latest Posts","excerpt":"<p>Stay up to date with all our latest news and launches. Only the best quality makes it onto our blog!<\/p>","category":"0","posts_per_page":"6","order":"{\"orderby\":\"date\",\"order\":\"desc\"}"}}}'
    );

    /* Blank Default Template */
    $rayne_layerswp_presets['blank'] = array(
        'title' => __( 'Blank Page' , 'rayne-layers' ),
        'screenshot' => NULL,
        'json' => esc_attr( '{}' ),
        'container-css' => 'blank-product'
    );

    return $rayne_layerswp_presets;
}

add_filter( 'layers_preset_layouts', 'rayne_layerswp_presets_layout', 0 );

/*
*
* Preset Layouts, The following filter adds your Layers pages to the preset layouts under Layers > Add Page
*
*/
require_once get_stylesheet_directory() . '/inc/theme-hooks.php';


/*
*
* Instagram Widget
*
*/
require_once get_stylesheet_directory() . '/widgets/instagram.php';