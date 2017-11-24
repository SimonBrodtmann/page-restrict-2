<?php
/*
Plugin Name: Page Restrict 2
Description: Restrict certain pages to logged in users
Author: Simon Brodtmann
Text Domain: pagerestrict2
Version: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0
*/

// if we are in the admin load the admin functionality
if ( is_admin () )
	require_once( dirname ( __FILE__ ) . '/inc/admin.php' );

// get specific option
function pr2_get_opt ($option, $default = false) {
	$options = get_option('pr2_options');
	if (is_array($options)) {
		return $options[$option];
	}
    return $default;
}

function pr2_is_post_restricted($id = NULL) {
	switch (pr2_get_opt('method')) {
		case 'none':
			return false;
		case 'all':
			return true;
		case 'selected':
			global $post;
			if (!$id) $id = $post->ID;
			return get_metadata("post", $id, 'pagerestrict2_restricted', true) == true;
	}
	return false;
}

function pr2_page_restrict ( $content ) {
	if (pr2_is_post_restricted() && !is_user_logged_in()) {
		$content = '<p>';
		$content .= __('This content can only be viewed by logged in users.', 'pagerestrict2');
		$content .= '</p><a href="' . wp_login_url(get_permalink()) . '">';
		$content .= __('Login.', 'pagerestrict2');
		$content .= '</a>';
	}
	return $content;
}


/**
 * Replace the default shortcode handlers.
 *
 * @wp-hook after_setup_theme
 * @return  void
 */
function pr2_replace_gallery_shortcode() {
    // overwrite the native shortcode handler
    add_shortcode( 'gallery', 'pr2_gallery_shortcode' );
}
add_action( 'after_setup_theme', 'pr2_replace_gallery_shortcode' );


/**
 * Create a filtered gallery output.
 *
 * @wp-hook gallery
 * @param   array $attr
 * @return  string
 */
function pr2_gallery_shortcode( $attr ) {
    if (!is_user_logged_in() && isset($attr['ids'])) {
		$ids = explode(",", $attr['ids']);
		$idsFiltered = array();
		for ($i = 0; $i < count($ids); $i++) {
			$restricted = get_post_meta($ids[$i], 'pagerestrict2_restricted', true);
			if (!$restricted) {
				$idsFiltered[] = $ids[$i];
			}
		}
		$attr['ids'] = implode(",", $idsFiltered);
	}

    // Let WordPress create the regular gallery …
    $gallery = gallery_shortcode( $attr );

    return $gallery;
}


/**
 * Filter attachments
 * @wp-hook gallery
 **/
function pr2_filter_attachment($image, $attachment_id, $size, $icon) {
	if (pr2_is_post_restricted($attachment_id) && !is_user_logged_in())
		return NULL;
	return $image;
}


// Add Filters
add_filter ( 'the_content' , 'pr2_page_restrict' , 50 );
add_filter ( 'the_excerpt' , 'pr2_page_restrict' , 50 );
add_filter ( 'comments_array' , 'pr2_page_restrict' , 50 );
add_filter ( 'wp_get_attachment_image_src' , 'pr2_filter_attachment' , 50, 4 );
