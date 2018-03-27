<?php

// Initialize the default options during plugin activation
function pr2_init () {
	$options = get_option('pr2_options', array());
	update_option('pr2_options', $options);
}

/**
 * The meta box
 */
function page_restriction_status_meta_box ( $post ) {
	$post_ID = $post->ID;
?>
	<p>
		<label for="pr2_restriction" class="selectit">
			<input type="checkbox" name="pr2_restriction" id="pr2_restriction"<?php if (get_post_meta($post_ID, 'pagerestrict2_public', true)) echo ' checked="checked"'; ?>/>
			Inhalt öffentlich zugänglich machen.
		</label>
	</p>
<?php
}

/**
 * Add meta box to create/edit page pages
 */
function pr2_meta_box () {
	add_meta_box ( 'pagerestrictionstatusdiv' , 'Sichtbarkeit' , 'page_restriction_status_meta_box' , array('page', 'post', 'veranstaltung') , 'normal' , 'default' );
	if (post_type_supports(get_current_screen()->post_type, 'pagerestrict')) {
		add_meta_box ( 'pagerestrictionstatusdiv' , 'Sichtbarkeit' , 'page_restriction_status_meta_box' , get_current_screen() , 'normal' , 'default' );
	}
}

/**
 * Get custom POST vars on edit/create page pages and update options accordingly
 */
function pr2_meta_save ($post_id, $value) {
	if ($value) {
		update_post_meta($post_id, 'pagerestrict2_public', true);
	} else if (get_post_meta($post_id, 'pagerestrict2_public', true)) {
		delete_post_meta($post_id, 'pagerestrict2_public');
	}
}

/**
 * Activation hook
 */
register_activation_hook ( dirname ( dirname ( __FILE__ ) ) . '/pagerestrict2.php' , 'pr2_init' );

/**
 * Tell WordPress what to do.  Action hooks.
 */
add_action ( 'add_meta_boxes' , 'pr2_meta_box' );
add_action ( 'save_post' , function($post_id) {pr2_meta_save($post_id, isset($_POST['pr2_restriction']));}, 10 , 1 );
add_filter ( 'attachment_fields_to_save', function($post, $attachment) {pr2_meta_save($post['ID'], isset($attachment['pr2_restriction']));}, 10, 2 );


/**
 * Add restriction to attachments
 *
 * @param $form_fields array, fields to include in attachment form
 * @param $post object, attachment record in database
 * @return $form_fields, modified form fields
 */

function pr2_attachment_field( $form_fields, $post ) {
    $form_fields['pr2_restriction'] = array(
        'label' => 'Öffentlich sichtbar',
        'input' => 'html',
		'html' => '<input type="checkbox" name="attachments[' . $post->ID . '][pr2_restriction]" id="attachments-' . $post->ID . '-pr2_restriction" value="pr2_restriction"' . (get_post_meta($post->ID, 'pagerestrict2_public', true) ? ' checked="checked"' : '') . '/>'
    );
    return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'pr2_attachment_field', 10, 2 );

?>
