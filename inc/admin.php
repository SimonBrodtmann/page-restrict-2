<?php

// Initialize the default options during plugin activation
function pr2_init () {
	$options = get_option('pr2_options', array());
	if (!$options['method']) $options['method'] = 'selected';
	update_option('pr2_options', $options);
}

// Add the options page
function pr2_options_page () {
	if ( current_user_can ( 'edit_others_pages' ) && function_exists ( 'add_options_page' ) ) :
		add_options_page ( 'Page Restrict 2' , 'Page Restrict 2' , 'publish_pages' , 'pagerestrict2' , 'pr2_admin_page' );
		add_filter("plugin_action_links_pagerestrict2", 'pr2_filter_plugin_actions' );
	endif;

}

// Add the setting link to the plugin actions
function pr2_filter_plugin_actions ( $links ) {
        $settings_link = '<a href="options-general.php?page=pagerestrict2">' . __( 'Settings', 'pagerestrict2' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
}

// The options page
function pr2_admin_page () {
	if ( $_POST && $_POST['action'] == 'update' ) :
		$page_ids = false;
		$pr_options = array();
		$pr_options['method'] = $_POST['method'];
		update_option('pr2_options', $pr_options);
		echo '<div id="message" class="updated fade"><p><strong>' . __('Settings saved', 'pagerestrict2') . '.</strong></p></div>';
	endif;
	$pr_method = pr2_get_opt ( 'method' );
?>
	<div class="wrap">
		<h2>Page Restrict Options</h2>
		<form action="" method="post">
                        <input type="hidden" name="action" value="update" />
			<h3><?php _e( 'General Options', 'pagerestrict2' ); ?></h3>
			<p><?php _e( 'These options pertain to the general operation of the plugin', 'pagerestrict2' ); ?></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<?php _e( 'Restriction Method', 'pagerestrict2' ); ?>
					</th>
					<td>
						<select name="method">
							<option value="all"<?php selected ( 'all' , pr2_get_opt ( 'method' ) ); ?>><?php _e( 'All', 'pagerestrict2' ); ?></option>
							<option value="none"<?php selected ( 'none' , pr2_get_opt ( 'method' ) ); ?>><?php _e( 'None', 'pagerestrict2' ); ?></option>
							<option value="selected"<?php selected ( 'selected' , pr2_get_opt ( 'method' ) ); ?>><?php _e( 'Selected', 'pagerestrict2' ); ?></option>
						</select>
					</td>
				</tr>
			</table>
			<br />
			<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="Save Changes" />
			</p>
		</form>
	</div>
<?php
}

/**
 * The meta box
 */
function page_restriction_status_meta_box ( $post ) {
	$post_ID = $post->ID;
	if ( $post->post_type == 'page' ) {
		$the_IDs = pr2_get_opt ('pages');
	}
	else {
		$the_IDs = pr2_get_opt ('posts');
	}
?>
	<p>
		<label for="pr2_restriction" class="selectit">
			<input type="checkbox" name="pr2_restriction" id="pr2_restriction"<?php if (get_post_meta($post_ID, 'pagerestrict2_restricted', true)) echo ' checked="checked"'; ?>/>
			<?php _e('Restrict content to logged in users.', 'pagerestrict2'); ?>
		</label>
	</p>
<?php
}

/**
 * Add meta box to create/edit page pages
 */
function pr2_meta_box () {
	add_meta_box ( 'pagerestrictionstatusdiv' , __('Restriction', 'pagerestrict2') , 'page_restriction_status_meta_box' , array('page', 'post') , 'normal' , 'high' );
}

/**
 * Get custom POST vars on edit/create page pages and update options accordingly
 */
function pr2_meta_save () {
	global $post;
	if (isset($_POST['pr2_restriction'])) {
		update_post_meta($post->ID, 'pagerestrict2_restricted', true);
	} else if (get_post_meta($post->ID, 'pagerestrict2_restricted', true)) {
		delete_post_meta($post->ID, 'pagerestrict2_restricted');
	}
}

/**
 * Activation hook
 */
register_activation_hook ( dirname ( dirname ( __FILE__ ) ) . '/pagerestrict2.php' , 'pr2_init' );

/**
 * Tell WordPress what to do.  Action hooks.
 */
add_action ( 'admin_menu' , 'pr2_meta_box' );
add_action ( 'save_post' , 'pr2_meta_save' );
add_action ( 'admin_menu' , 'pr2_options_page' ) ;
?>
