<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'pastore_church_shortcodes_is_used' ) ) {
	function pastore_church_shortcodes_is_used() {
		return pastore_church_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && pastore_church_strpos($_SERVER['REQUEST_URI'], 'vc-roles')!==false)			// VC Role Manager
			|| (function_exists('pastore_church_vc_is_frontend') && pastore_church_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'pastore_church_shortcodes_width' ) ) {
	function pastore_church_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'pastore-church'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'pastore_church_shortcodes_height' ) ) {
	function pastore_church_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'pastore-church'),
			"desc" => wp_kses_data( __("Width and height of the element", 'pastore-church') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'pastore_church_get_sc_param' ) ) {
	function pastore_church_get_sc_param($prm) {
		return pastore_church_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'pastore_church_set_sc_param' ) ) {
	function pastore_church_set_sc_param($prm, $val) {
		pastore_church_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'pastore_church_sc_map' ) ) {
	function pastore_church_sc_map($sc_name, $sc_settings) {
		pastore_church_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'pastore_church_sc_map_after' ) ) {
	function pastore_church_sc_map_after($after, $sc_name, $sc_settings='') {
		pastore_church_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'pastore_church_sc_map_before' ) ) {
	function pastore_church_sc_map_before($before, $sc_name, $sc_settings='') {
		pastore_church_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'pastore_church_compare_sc_title' ) ) {
	function pastore_church_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_shortcodes_settings_theme_setup' ) ) {
//	if ( pastore_church_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'pastore_church_action_before_init_theme', 'pastore_church_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'pastore_church_action_after_init_theme', 'pastore_church_shortcodes_settings_theme_setup' );
	function pastore_church_shortcodes_settings_theme_setup() {
		if (pastore_church_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = pastore_church_storage_get('registered_templates');
			ksort($tmp);
			pastore_church_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			pastore_church_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'pastore-church'),
					"desc" => wp_kses_data( __("ID for current element", 'pastore-church') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'pastore-church'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'pastore-church'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'pastore-church'),
					'ol'	=> esc_html__('Ordered', 'pastore-church'),
					'iconed'=> esc_html__('Iconed', 'pastore-church')
				),

				'yes_no'	=> pastore_church_get_list_yesno(),
				'on_off'	=> pastore_church_get_list_onoff(),
				'dir' 		=> pastore_church_get_list_directions(),
				'align'		=> pastore_church_get_list_alignments(),
				'float'		=> pastore_church_get_list_floats(),
				'hpos'		=> pastore_church_get_list_hpos(),
				'show_hide'	=> pastore_church_get_list_showhide(),
				'sorting' 	=> pastore_church_get_list_sortings(),
				'ordering' 	=> pastore_church_get_list_orderings(),
				'shapes'	=> pastore_church_get_list_shapes(),
				'sizes'		=> pastore_church_get_list_sizes(),
				'sliders'	=> pastore_church_get_list_sliders(),
				'controls'	=> pastore_church_get_list_controls(),
				'categories'=> pastore_church_get_list_categories(),
				'columns'	=> pastore_church_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), pastore_church_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), pastore_church_get_list_icons()),
				'locations'	=> pastore_church_get_list_dedicated_locations(),
				'filters'	=> pastore_church_get_list_portfolio_filters(),
				'formats'	=> pastore_church_get_list_post_formats_filters(),
				'hovers'	=> pastore_church_get_list_hovers(true),
				'hovers_dir'=> pastore_church_get_list_hovers_directions(true),
				'schemes'	=> pastore_church_get_list_color_schemes(true),
				'animations'		=> pastore_church_get_list_animations_in(),
				'margins' 			=> pastore_church_get_list_margins(true),
				'blogger_styles'	=> pastore_church_get_list_templates_blogger(),
				'forms'				=> pastore_church_get_list_templates_forms(),
				'posts_types'		=> pastore_church_get_list_posts_types(),
				'googlemap_styles'	=> pastore_church_get_list_googlemap_styles(),
				'field_types'		=> pastore_church_get_list_field_types(),
				'label_positions'	=> pastore_church_get_list_label_positions()
				)
			);

			// Common params
			pastore_church_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'pastore-church'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'pastore-church') ),
				"value" => "none",
				"type" => "select",
				"options" => pastore_church_get_sc_param('animations')
				)
			);
			pastore_church_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'pastore-church'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => pastore_church_get_sc_param('margins')
				)
			);
			pastore_church_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'pastore-church'),
				"value" => "inherit",
				"type" => "select",
				"options" => pastore_church_get_sc_param('margins')
				)
			);
			pastore_church_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'pastore-church'),
				"value" => "inherit",
				"type" => "select",
				"options" => pastore_church_get_sc_param('margins')
				)
			);
			pastore_church_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'pastore-church'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'pastore-church') ),
				"value" => "inherit",
				"type" => "select",
				"options" => pastore_church_get_sc_param('margins')
				)
			);

			pastore_church_storage_set('sc_params', apply_filters('pastore_church_filter_shortcodes_params', pastore_church_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			pastore_church_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('pastore_church_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = pastore_church_storage_get('shortcodes');
			uasort($tmp, 'pastore_church_compare_sc_title');
			pastore_church_storage_set('shortcodes', $tmp);
		}
	}
}
?>