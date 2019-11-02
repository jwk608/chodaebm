<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_list_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_list_theme_setup' );
	function pastore_church_sc_list_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_list_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('pastore_church_sc_list')) {	
	function pastore_church_sc_list($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		pastore_church_storage_set('sc_list_data', array(
			'counter' => 0,
            'icon' => empty($icon) || pastore_church_param_is_inherit($icon) ? "icon-right" : $icon,
            'icon_color' => $icon_color,
            'style' => $style
            )
        );
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	pastore_church_require_shortcode('trx_list', 'pastore_church_sc_list');
}


if (!function_exists('pastore_church_sc_list_item')) {	
	function pastore_church_sc_list_item($atts, $content=null) {
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		pastore_church_storage_inc_array('sc_list_data', 'counter');
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || pastore_church_param_is_inherit($icon)) $icon = pastore_church_storage_get_array('sc_list_data', 'icon');
		if (trim($color) == '' || pastore_church_param_is_inherit($icon_color)) $icon_color = pastore_church_storage_get_array('sc_list_data', 'icon_color');
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (pastore_church_storage_get_array('sc_list_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
			. (pastore_church_storage_get_array('sc_list_data', 'counter') == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. (pastore_church_storage_get_array('sc_list_data', 'style')=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. trim($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	pastore_church_require_shortcode('trx_list_item', 'pastore_church_sc_list_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_list_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_list_reg_shortcodes');
	function pastore_church_sc_list_reg_shortcodes() {
	
		pastore_church_sc_map("trx_list", array(
			"title" => esc_html__("List", 'pastore-church'),
			"desc" => wp_kses_data( __("List items with specific bullets", 'pastore-church') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", 'pastore-church'),
					"desc" => wp_kses_data( __("Bullet's style for each list item", 'pastore-church') ),
					"value" => "ul",
					"type" => "checklist",
					"options" => pastore_church_get_sc_param('list_styles')
				), 
				"color" => array(
					"title" => esc_html__("Color", 'pastore-church'),
					"desc" => wp_kses_data( __("List items color", 'pastore-church') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'pastore-church'),
					"desc" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)",  'pastore-church') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => pastore_church_get_sc_param('icons')
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", 'pastore-church'),
					"desc" => wp_kses_data( __("List icons color", 'pastore-church') ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => pastore_church_get_sc_param('top'),
				"bottom" => pastore_church_get_sc_param('bottom'),
				"left" => pastore_church_get_sc_param('left'),
				"right" => pastore_church_get_sc_param('right'),
				"id" => pastore_church_get_sc_param('id'),
				"class" => pastore_church_get_sc_param('class'),
				"animation" => pastore_church_get_sc_param('animation'),
				"css" => pastore_church_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", 'pastore-church'),
				"desc" => wp_kses_data( __("List item with specific bullet", 'pastore-church') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", 'pastore-church'),
						"desc" => wp_kses_data( __("Current list item content", 'pastore-church') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", 'pastore-church'),
						"desc" => wp_kses_data( __("Current list item title (show it as tooltip)", 'pastore-church') ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", 'pastore-church'),
						"desc" => wp_kses_data( __("Text color for this item", 'pastore-church') ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'pastore-church'),
						"desc" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'pastore-church') ),
						"value" => "",
						"type" => "icons",
						"options" => pastore_church_get_sc_param('icons')
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", 'pastore-church'),
						"desc" => wp_kses_data( __("Icon color for this item", 'pastore-church') ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", 'pastore-church'),
						"desc" => wp_kses_data( __("Link URL for the current list item", 'pastore-church') ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", 'pastore-church'),
						"desc" => wp_kses_data( __("Link target for the current list item", 'pastore-church') ),
						"value" => "",
						"type" => "text"
					),
					"id" => pastore_church_get_sc_param('id'),
					"class" => pastore_church_get_sc_param('class'),
					"css" => pastore_church_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_list_reg_shortcodes_vc');
	function pastore_church_sc_list_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", 'pastore-church'),
			"description" => wp_kses_data( __("List items with specific bullets", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", 'pastore-church'),
					"description" => wp_kses_data( __("Bullet's style for each list item", 'pastore-church') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(pastore_church_get_sc_param('list_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'pastore-church'),
					"description" => wp_kses_data( __("List items color", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => pastore_church_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'pastore-church'),
					"description" => wp_kses_data( __("List icons color", 'pastore-church') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "colorpicker"
				),
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('animation'),
				pastore_church_get_vc_param('css'),
				pastore_church_get_vc_param('margin_top'),
				pastore_church_get_vc_param('margin_bottom'),
				pastore_church_get_vc_param('margin_left'),
				pastore_church_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", 'pastore-church'),
			"description" => wp_kses_data( __("List item with specific bullet", 'pastore-church') ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", 'pastore-church'),
					"description" => wp_kses_data( __("Title for the current list item (show it as tooltip)", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'pastore-church'),
					"description" => wp_kses_data( __("Link URL for the current list item", 'pastore-church') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'pastore-church'),
					"description" => wp_kses_data( __("Link target for the current list item", 'pastore-church') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'pastore-church'),
					"description" => wp_kses_data( __("Text color for this item", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => pastore_church_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'pastore-church'),
					"description" => wp_kses_data( __("Icon color for this item", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("List item text", 'pastore-church'),
					"description" => wp_kses_data( __("Current list item content", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('css')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends PASTORE_CHURCH_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends PASTORE_CHURCH_VC_ShortCodeContainer {}
	}
}
?>