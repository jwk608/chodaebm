<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_image_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_image_theme_setup' );
	function pastore_church_sc_image_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_image_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_image_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('pastore_church_sc_image')) {	
	function pastore_church_sc_image($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"icon" => "",
			"link" => "",
			"extra" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pastore_church_get_css_dimensions_from_values($width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = pastore_church_get_resized_image_url($src, $w, $h);
		}
		if (trim($link)) pastore_church_enqueue_popup();


		if (pastore_church_param_is_on($extra)){
			$class .= ' extra';
			$output = empty($src) ? '' : ('<figure' . ($id ? ' id="' . esc_attr($id) . '"' : '')
				. ' class="sc_image ' . ($align && $align != 'none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_' . esc_attr($shape) : '') . (!empty($class) ? ' ' . esc_attr($class) : '') . '"'
				. (!pastore_church_param_is_off($animation) ? ' data-animation="' . esc_attr(pastore_church_get_animation_classes($animation)) . '"' : '')
				. ($css != '' ? ' style="' . esc_attr($css) . '"' : '')
				. '>'
				. (trim($link) ? '<a href="' . esc_url($link) . '">' : '')
				. '<img src="' . esc_url($src) . '" alt="" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span' . ($icon ? ' class="' . esc_attr($icon) . '"' : '') . '></span> ' . ($title) . '</figcaption>' : '')
				. '</figure>');
		}
		else {
			$output = empty($src) ? '' : ('<figure' . ($id ? ' id="' . esc_attr($id) . '"' : '')
				. ' class="sc_image ' . ($align && $align != 'none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_' . esc_attr($shape) : '') . (!empty($class) ? ' ' . esc_attr($class) : '') . '"'
				. (!pastore_church_param_is_off($animation) ? ' data-animation="' . esc_attr(pastore_church_get_animation_classes($animation)) . '"' : '')
				. ($css != '' ? ' style="' . esc_attr($css) . '"' : '')
				. '>'
				. (trim($link) ? '<a href="' . esc_url($link) . '">' : '')
				. '<img src="' . esc_url($src) . '" alt="" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span' . ($icon ? ' class="' . esc_attr($icon) . '"' : '') . '></span> ' . ($title) . '</figcaption>' : '')
				. '</figure>');
		}

		return apply_filters('pastore_church_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	pastore_church_require_shortcode('trx_image', 'pastore_church_sc_image');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_image_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_image_reg_shortcodes');
	function pastore_church_sc_image_reg_shortcodes() {
	
		pastore_church_sc_map("trx_image", array(
			"title" => esc_html__("Image", 'pastore-church'),
			"desc" => wp_kses_data( __("Insert image into your post (page)", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for image file", 'pastore-church'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'pastore-church') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"extra" => array(
					"title" => esc_html__("Use extra style", 'pastore-church'),
					"desc" => wp_kses_data( __("Use extra style (border)", 'pastore-church') ),
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"title" => array(
					"title" => esc_html__("Title", 'pastore-church'),
					"desc" => wp_kses_data( __("Image title (if need)", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon before title",  'pastore-church'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'pastore-church') ),
					"value" => "",
					"type" => "icons",
					"options" => pastore_church_get_sc_param('icons')
				),
				"align" => array(
					"title" => esc_html__("Float image", 'pastore-church'),
					"desc" => wp_kses_data( __("Float image to left or right side", 'pastore-church') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pastore_church_get_sc_param('float')
				), 
				"shape" => array(
					"title" => esc_html__("Image Shape", 'pastore-church'),
					"desc" => wp_kses_data( __("Shape of the image: square (rectangle) or round", 'pastore-church') ),
					"value" => "square",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"square" => esc_html__('Square', 'pastore-church'),
						"round" => esc_html__('Round', 'pastore-church')
					)
				), 
				"link" => array(
					"title" => esc_html__("Link", 'pastore-church'),
					"desc" => wp_kses_data( __("The link URL from the image", 'pastore-church') ),
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
if ( !function_exists( 'pastore_church_sc_image_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_image_reg_shortcodes_vc');
	function pastore_church_sc_image_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_image",
			"name" => esc_html__("Image", 'pastore-church'),
			"description" => wp_kses_data( __("Insert image", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_image',
			"class" => "trx_sc_single trx_sc_image",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("Select image", 'pastore-church'),
					"description" => wp_kses_data( __("Select image from library", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "extra",
					"heading" => esc_html__("Use extra style", 'pastore-church'),
					"description" => wp_kses_data( __("Use extra style (border)", 'pastore-church') ),
					"class" => "",
					"value" => array(esc_html__('Use extra style', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Image alignment", 'pastore-church'),
					"description" => wp_kses_data( __("Align image to left or right side", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Image shape", 'pastore-church'),
					"description" => wp_kses_data( __("Shape of the image: square or round", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Square', 'pastore-church') => 'square',
						esc_html__('Round', 'pastore-church') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'pastore-church'),
					"description" => wp_kses_data( __("Image's title", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title's icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'pastore-church') ),
					"class" => "",
					"value" => pastore_church_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'pastore-church'),
					"description" => wp_kses_data( __("The link URL from the image", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Image extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>