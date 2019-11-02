<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_button_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_button_theme_setup' );
	function pastore_church_sc_button_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_button_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('pastore_church_sc_button')) {	
	function pastore_church_sc_button($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pastore_church_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (pastore_church_param_is_on($popup)) pastore_church_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? ' sc_button_iconed' : '')
					. (pastore_church_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. ($icon!='' ? '<span class="ico '. esc_attr($icon).'"></span>' : '')
			. do_shortcode($content)
			. '</a>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	pastore_church_require_shortcode('trx_button', 'pastore_church_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_button_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_button_reg_shortcodes');
	function pastore_church_sc_button_reg_shortcodes() {
	
		pastore_church_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'pastore-church'),
			"desc" => wp_kses_data( __("Button with link", 'pastore-church') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'pastore-church'),
					"desc" => wp_kses_data( __("Button caption", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'pastore-church'),
					"desc" => wp_kses_data( __("Select button's shape", 'pastore-church') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'pastore-church'),
						'round' => esc_html__('Round', 'pastore-church')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'pastore-church'),
					"desc" => wp_kses_data( __("Select button's style", 'pastore-church') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'pastore-church'),
						'color_1' => esc_html__('Color 1', 'pastore-church'),
						'color_2' => esc_html__('Color 2', 'pastore-church'),
						'icon' => esc_html__('Icon', 'pastore-church')
						//'border' => esc_html__('Border', 'pastore-church')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'pastore-church'),
					"desc" => wp_kses_data( __("Select button's size", 'pastore-church') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'pastore-church'),
						'medium' => esc_html__('Medium', 'pastore-church'),
						'large' => esc_html__('Large', 'pastore-church')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'pastore-church'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'pastore-church') ),
					"value" => "",
					"type" => "icons",
					"options" => pastore_church_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'pastore-church'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'pastore-church') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'pastore-church'),
					"desc" => wp_kses_data( __("Any color for button's background", 'pastore-church') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'pastore-church'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'pastore-church') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pastore_church_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'pastore-church'),
					"desc" => wp_kses_data( __("URL for link on button click", 'pastore-church') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'pastore-church'),
					"desc" => wp_kses_data( __("Target for link on button click", 'pastore-church') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'pastore-church'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'pastore-church') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'pastore-church'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'pastore-church') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => pastore_church_shortcodes_width(),
				"height" => pastore_church_shortcodes_height(),
				"top" => pastore_church_get_sc_param('top'),
				"bottom" => pastore_church_get_sc_param('bottom'),
				"left" => pastore_church_get_sc_param('left'),
				"right" => pastore_church_get_sc_param('right'),
				"id" => pastore_church_get_sc_param('id'),
				"class" => pastore_church_get_sc_param('class'),
				"animation" => pastore_church_get_sc_param('animation'),
				"css" => pastore_church_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_button_reg_shortcodes_vc');
	function pastore_church_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'pastore-church'),
			"description" => wp_kses_data( __("Button with link", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'pastore-church'),
					"description" => wp_kses_data( __("Button caption", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'pastore-church'),
					"description" => wp_kses_data( __("Select button's shape", 'pastore-church') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'pastore-church') => 'square',
						esc_html__('Round', 'pastore-church') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'pastore-church'),
					"description" => wp_kses_data( __("Select button's style", 'pastore-church') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'pastore-church') => 'filled',
						esc_html__('Color 1', 'pastore-church') => 'color_1',
						esc_html__('Color 2', 'pastore-church') => 'color_2',
						esc_html__('Icon', 'pastore-church') => 'icon'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'pastore-church'),
					"description" => wp_kses_data( __("Select button's size", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'pastore-church') => 'small',
						esc_html__('Medium', 'pastore-church') => 'medium',
						esc_html__('Large', 'pastore-church') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'pastore-church') ),
					"class" => "",
					"value" => pastore_church_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'pastore-church'),
					"description" => wp_kses_data( __("Any color for button's caption", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'pastore-church'),
					"description" => wp_kses_data( __("Any color for button's background", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'pastore-church'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'pastore-church') ),
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'pastore-church'),
					"description" => wp_kses_data( __("URL for the link on button click", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Link', 'pastore-church'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'pastore-church'),
					"description" => wp_kses_data( __("Target for the link on button click", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Link', 'pastore-church'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'pastore-church'),
					"description" => wp_kses_data( __("Open link target in popup window", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Link', 'pastore-church'),
					"value" => array(esc_html__('Open in popup', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'pastore-church'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Link', 'pastore-church'),
					"value" => "",
					"type" => "textfield"
				),
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('animation'),
				pastore_church_get_vc_param('css'),
				pastore_church_vc_width(),
				pastore_church_vc_height(),
				pastore_church_get_vc_param('margin_top'),
				pastore_church_get_vc_param('margin_bottom'),
				pastore_church_get_vc_param('margin_left'),
				pastore_church_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>