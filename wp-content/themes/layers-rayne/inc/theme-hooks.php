<?php
//The Hooks

/**
*
* Remove Featured Image
*/
add_filter( 'layers_post_featured_media', 'rayne_featured_media' );
function rayne_featured_media( $media ){
		if( is_single() && has_post_thumbnail() ) return '';

	return $media;
}

/*
*
* Before copyright hook
*
*/
function rayne_bottom_footer_before() {?>
</div>
<div class="rayne-bottom clearfix">
	<div class="<?php if( 'layout-fullwidth' != layers_get_theme_mod( 'footer-width' ) ) echo 'container'; ?>span-12">

<?php  }
add_action('layers_before_footer_copyright', 'rayne_bottom_footer_before');

/*
*
* After copyright hook
*
*/
function rayne_bottom_footer_after() {?>
</div>

<?php  }
add_action('layers_after_footer_copyright', 'rayne_bottom_footer_after');

/*
*
* Share on Social Media Buttons
*
*/
function rayne_social_sharing_buttons() {

	if(is_single()) {
		global $post;
		// Get current page URL
		$rayneURL = get_permalink();

		// Get current page title
		$rayneTitle = str_replace( ' ', '%20', get_the_title());

		// Get Post Thumbnail for pinterest
		$rayneThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

		// Construct sharing URL without using any script
		$twitterURL = 'https://twitter.com/intent/tweet?text='.$rayneTitle.'&amp;url='.$rayneURL;
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$rayneURL;
		$googleURL = 'https://plus.google.com/share?url='.$rayneURL;
		$bufferURL = 'https://bufferapp.com/add?url='.$rayneURL.'&amp;text='.$rayneTitle;

		// Based on popular demand added Pinterest too
		$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$rayneURL.'&amp;media='.$rayneThumbnail[0].'&amp;description='.$rayneTitle;

		// Add sharing button at the end of page/page content
		$content  = '<div class="social-share">';
		$content .= '<a class="social-link" href="' . esc_url($facebookURL) . '" target="_blank"><i class="fa fa-facebook"></i></a>';
		$content .= '<a class="social-link" href="' . esc_url($twitterURL) . '" target="_blank"><i class="fa fa-twitter"></i></a>';
		$content .= '<a class="social-link" href="' . esc_url($googleURL) . '" target="_blank"><i class="fa fa-google"></i></a>';
		$content .= '<a class="social-link" href="' . esc_url($pinterestURL) . '" target="_blank"><i class="fa fa-pinterest"></i></a>';
		$content .= '</div>';

		echo $content;
	}
}

add_action( 'layers_after_single_content', 'rayne_social_sharing_buttons');

/*
*
* Page Title Background
*
*/
function rayne_bg_page_title() {

	$rayne_featured_image = wp_get_attachment_url( get_post_thumbnail_id() );

	$args_bg_cat = array (
		'posts_per_page'         => '1',
		'ignore_sticky_posts'    => true,
	);

	// The Query
	$query_bg_post = new WP_Query( $args_bg_cat );

	if ( $query_bg_post->have_posts() && '' != $rayne_featured_image) {

		echo '<div class="rayne-overlay-bg" style="background-image: url(\'' . esc_url($rayne_featured_image) . '\')"></div>';

	}
	// Restore original Post Data
	wp_reset_postdata();
	}

add_action('layers_after_header_page_title','rayne_bg_page_title');

/*
*
* Media Gallery
*
*/
function rayne_post_gallery($output, $attr) {
    global $post;

    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby'])
            unset($attr['orderby']);
    }

    extract(shortcode_atts(array(
        'order' => 'ASC',
        'orderby' => 'menu_order ID',
        'id' => $post->ID,
        'itemtag' => 'dl',
        'icontag' => 'dt',
        'captiontag' => 'dd',
        'columns' => 3,
        'size' => 'thumbnail',
        'include' => '',
        'exclude' => ''
    ), $attr));

    $id = intval($id);
    if ('RAND' == $order) $orderby = 'none';

    if (!empty($include)) {
        $include = preg_replace('/[^0-9,]+/', '', $include);
        $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    }

    if (empty($attachments)) return '';

    // Here's your actual output, you may customize it to your need
    $output = "<ul class=\"bxslider\">\n";

    // Now you loop through each attachment
    foreach ($attachments as $id => $attachment) {
        $img = wp_get_attachment_image_src($id, 'full');

        $output .= "<li>\n";
        $output .= "<a href=\"" . esc_url($img[0]) . "\"><img src=\"" . esc_url($img[0]) ."\" width=\"" . esc_attr($img[1]) ."\" height=\"" . esc_attr($img[2]) ."\" alt=\"\" /></a>\n";
        $output .= "</li>\n";
    }

    $output .= "</ul class=>\n";

    return $output;
}
add_filter('post_gallery', 'rayne_post_gallery', 10, 2);


/*
*
* Header Container for Page Titles
*
*/
function rayne_header_page_title() {
	if(is_single()) {
		get_template_part( 'partials/header' , 'page-title' );
	}
}
add_action('layers_after_header','rayne_header_page_title');