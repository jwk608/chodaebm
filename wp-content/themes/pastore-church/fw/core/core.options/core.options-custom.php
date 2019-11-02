<?php
/**
 * Pastore Church Framework: Theme options custom fields
 *
 * @package	pastore_church
 * @since	pastore_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_options_custom_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_options_custom_theme_setup' );
	function pastore_church_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'pastore_church_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'pastore_church_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'pastore_church_options_custom_load_scripts');
	function pastore_church_options_custom_load_scripts() {
		pastore_church_enqueue_script( 'pastore_church-options-custom-script',	pastore_church_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'pastore_church_show_custom_field' ) ) {
	function pastore_church_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(pastore_church_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager pastore_church_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'pastore-church') : esc_html__( 'Choose Image', 'pastore-church')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'pastore-church') : esc_html__( 'Choose Image', 'pastore-church')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'pastore-church') : esc_html__( 'Choose Image', 'pastore-church')) . '</a>';
				break;
		}
		return apply_filters('pastore_church_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>