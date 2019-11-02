<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_socials_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_socials_theme_setup' );
	function pastore_church_sc_socials_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_socials_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('pastore_church_sc_socials')) {	
	function pastore_church_sc_socials($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => pastore_church_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		pastore_church_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? pastore_church_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) pastore_church_storage_set_array('sc_social_data', 'icons', $list);
		} else if (pastore_church_param_is_off($custom))
			$content = do_shortcode($content);
		if (pastore_church_storage_get_array('sc_social_data', 'icons')===false) pastore_church_storage_set_array('sc_social_data', 'icons', pastore_church_get_custom_option('social_icons'));
		$output = pastore_church_prepare_socials(pastore_church_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	pastore_church_require_shortcode('trx_socials', 'pastore_church_sc_socials');
}


if (!function_exists('pastore_church_sc_social_item')) {	
	function pastore_church_sc_social_item($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = pastore_church_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(pastore_church_get_socials_dir($name.'.png')))
					$icon = pastore_church_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (pastore_church_storage_get_array('sc_social_data', 'icons')===false) pastore_church_storage_set_array('sc_social_data', 'icons', array());
			pastore_church_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	pastore_church_require_shortcode('trx_social_item', 'pastore_church_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_socials_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_socials_reg_shortcodes');
	function pastore_church_sc_socials_reg_shortcodes() {
	
		pastore_church_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'pastore-church'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'pastore-church') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'pastore-church'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'pastore-church') ),
					"value" => pastore_church_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'pastore-church'),
						'images' => esc_html__('Images', 'pastore-church')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'pastore-church'),
					"desc" => wp_kses_data( __("Size of the icons", 'pastore-church') ),
					"value" => "small",
					"options" => pastore_church_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'pastore-church'),
					"desc" => wp_kses_data( __("Shape of the icons", 'pastore-church') ),
					"value" => "square",
					"options" => pastore_church_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'pastore-church'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'pastore-church') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'pastore-church'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'pastore-church') ),
					"divider" => true,
					"value" => "no",
					"options" => pastore_church_get_sc_param('yes_no'),
					"type" => "switch"
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'pastore-church'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'pastore-church') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'pastore-church'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'pastore-church') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'pastore-church'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'pastore-church') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'pastore-church'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'pastore-church') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_socials_reg_shortcodes_vc');
	function pastore_church_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'pastore-church'),
			"description" => wp_kses_data( __("Custom social icons", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'pastore-church'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'pastore-church') ),
					"class" => "",
					"std" => pastore_church_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'pastore-church') => 'icons',
						esc_html__('Images', 'pastore-church') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'pastore-church'),
					"description" => wp_kses_data( __("Size of the icons", 'pastore-church') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(pastore_church_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'pastore-church'),
					"description" => wp_kses_data( __("Shape of the icons", 'pastore-church') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(pastore_church_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'pastore-church'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'pastore-church'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'pastore-church') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('animation'),
				pastore_church_get_vc_param('css'),
				pastore_church_get_vc_param('margin_top'),
				pastore_church_get_vc_param('margin_bottom'),
				pastore_church_get_vc_param('margin_left'),
				pastore_church_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'pastore-church'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'pastore-church') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'pastore-church'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'pastore-church'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'pastore-church'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends PASTORE_CHURCH_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>