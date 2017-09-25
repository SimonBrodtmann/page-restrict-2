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

function pr2_is_post_restricted() {
	switch (pr2_get_opt('method')) {
		case 'none':
			return false;
		case 'all':
			return true;
		case 'selected':
			global $post;
			return get_post_meta($post->ID, 'pagerestrict2_restricted', true) == true;
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

// Add Filters
add_filter ( 'the_content' , 'pr2_page_restrict' , 50 );
add_filter ( 'the_excerpt' , 'pr2_page_restrict' , 50 );
add_filter ( 'comments_array' , 'pr2_page_restrict' , 50 );
