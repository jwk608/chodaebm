<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_highlight_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_highlight_theme_setup' );
	function pastore_church_sc_highlight_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_highlight_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('pastore_church_sc_highlight')) {	
	function pastore_church_sc_highlight($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(pastore_church_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	pastore_church_require_shortcode('trx_highlight', 'pastore_church_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_highlight_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_highlight_reg_shortcodes');
	function pastore_church_sc_highlight_reg_shortcodes() {
	
		pastore_church_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'pastore-church'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'pastore-church') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'pastore-church'),
					"desc" => wp_kses_data( __("Highlight type", 'pastore-church') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'pastore-church'),
						1 => esc_html__('Type 1', 'pastore-church'),
						2 => esc_html__('Type 2', 'pastore-church'),
						3 => esc_html__('Type 3', 'pastore-church')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'pastore-church'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'pastore-church') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'pastore-church'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'pastore-church') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'pastore-church'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'pastore-church'),
					"desc" => wp_kses_data( __("Content for highlight", 'pastore-church') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => pastore_church_get_sc_param('id'),
				"class" => pastore_church_get_sc_param('class'),
				"css" => pastore_church_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_highlight_reg_shortcodes_vc');
	function pastore_church_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'pastore-church'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'pastore-church'),
					"description" => wp_kses_data( __("Highlight type", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'pastore-church') => 0,
							esc_html__('Type 1', 'pastore-church') => 1,
							esc_html__('Type 2', 'pastore-church') => 2,
							esc_html__('Type 3', 'pastore-church') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'pastore-church'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'pastore-church'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'pastore-church'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'pastore-church'),
					"description" => wp_kses_data( __("Content for highlight", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>