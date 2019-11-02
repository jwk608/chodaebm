<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_anchor_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_anchor_theme_setup' );
	function pastore_church_sc_anchor_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_anchor_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('pastore_church_sc_anchor')) {	
	function pastore_church_sc_anchor($atts, $content = null) {
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(pastore_church_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (pastore_church_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	pastore_church_require_shortcode("trx_anchor", "pastore_church_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_anchor_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_anchor_reg_shortcodes');
	function pastore_church_sc_anchor_reg_shortcodes() {
	
		pastore_church_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'pastore-church'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'pastore-church'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'pastore-church') ),
					"value" => "",
					"type" => "icons",
					"options" => pastore_church_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'pastore-church'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'pastore-church'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'pastore-church'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'pastore-church'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'pastore-church') ),
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"id" => pastore_church_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_anchor_reg_shortcodes_vc');
	function pastore_church_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'pastore-church'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'pastore-church') ),
					"class" => "",
					"value" => pastore_church_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'pastore-church'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'pastore-church'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'pastore-church'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'pastore-church'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'pastore-church') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				pastore_church_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>