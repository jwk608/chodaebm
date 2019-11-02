<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_icon_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_icon_theme_setup' );
	function pastore_church_sc_icon_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_icon_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('pastore_church_sc_icon')) {	
	function pastore_church_sc_icon($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !pastore_church_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(pastore_church_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || pastore_church_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !pastore_church_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	pastore_church_require_shortcode('trx_icon', 'pastore_church_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_icon_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_icon_reg_shortcodes');
	function pastore_church_sc_icon_reg_shortcodes() {
	
		pastore_church_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'pastore-church'),
			"desc" => wp_kses_data( __("Insert icon", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'pastore-church'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'pastore-church') ),
					"value" => "",
					"type" => "icons",
					"options" => pastore_church_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'pastore-church'),
					"desc" => wp_kses_data( __("Icon's color", 'pastore-church') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'pastore-church'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'pastore-church') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'pastore-church'),
						'round' => esc_html__('Round', 'pastore-church'),
						'square' => esc_html__('Square', 'pastore-church')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'pastore-church'),
					"desc" => wp_kses_data( __("Icon's background color", 'pastore-church') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'pastore-church'),
					"desc" => wp_kses_data( __("Icon's font size", 'pastore-church') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'pastore-church'),
					"desc" => wp_kses_data( __("Icon font weight", 'pastore-church') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'pastore-church'),
						'300' => esc_html__('Light (300)', 'pastore-church'),
						'400' => esc_html__('Normal (400)', 'pastore-church'),
						'700' => esc_html__('Bold (700)', 'pastore-church')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'pastore-church'),
					"desc" => wp_kses_data( __("Icon text alignment", 'pastore-church') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pastore_church_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'pastore-church'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"top" => pastore_church_get_sc_param('top'),
				"bottom" => pastore_church_get_sc_param('bottom'),
				"left" => pastore_church_get_sc_param('left'),
				"right" => pastore_church_get_sc_param('right'),
				"id" => pastore_church_get_sc_param('id'),
				"class" => pastore_church_get_sc_param('class'),
				"css" => pastore_church_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_icon_reg_shortcodes_vc');
	function pastore_church_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'pastore-church'),
			"description" => wp_kses_data( __("Insert the icon", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => pastore_church_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'pastore-church'),
					"description" => wp_kses_data( __("Icon's color", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'pastore-church'),
					"description" => wp_kses_data( __("Background color for the icon", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'pastore-church'),
					"description" => wp_kses_data( __("Shape of the icon background", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'pastore-church') => 'none',
						esc_html__('Round', 'pastore-church') => 'round',
						esc_html__('Square', 'pastore-church') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'pastore-church'),
					"description" => wp_kses_data( __("Icon's font size", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'pastore-church'),
					"description" => wp_kses_data( __("Icon's font weight", 'pastore-church') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'pastore-church') => 'inherit',
						esc_html__('Thin (100)', 'pastore-church') => '100',
						esc_html__('Light (300)', 'pastore-church') => '300',
						esc_html__('Normal (400)', 'pastore-church') => '400',
						esc_html__('Bold (700)', 'pastore-church') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'pastore-church'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'pastore-church'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('css'),
				pastore_church_get_vc_param('margin_top'),
				pastore_church_get_vc_param('margin_bottom'),
				pastore_church_get_vc_param('margin_left'),
				pastore_church_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>