<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_title_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_title_theme_setup' );
	function pastore_church_sc_title_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_title_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('pastore_church_sc_title')) {	
	function pastore_church_sc_title($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pastore_church_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !pastore_church_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !pastore_church_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(pastore_church_strpos($image, 'http:')!==false ? $image : pastore_church_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !pastore_church_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	pastore_church_require_shortcode('trx_title', 'pastore_church_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_title_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_title_reg_shortcodes');
	function pastore_church_sc_title_reg_shortcodes() {
	
		pastore_church_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'pastore-church'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'pastore-church') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'pastore-church'),
					"desc" => wp_kses_data( __("Title content", 'pastore-church') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'pastore-church'),
					"desc" => wp_kses_data( __("Title type (header level)", 'pastore-church') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'pastore-church'),
						'2' => esc_html__('Header 2', 'pastore-church'),
						'3' => esc_html__('Header 3', 'pastore-church'),
						'4' => esc_html__('Header 4', 'pastore-church'),
						'5' => esc_html__('Header 5', 'pastore-church'),
						'6' => esc_html__('Header 6', 'pastore-church'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'pastore-church'),
					"desc" => wp_kses_data( __("Title style", 'pastore-church') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'pastore-church'),
						'underline' => esc_html__('Underline', 'pastore-church'),
						'divider' => esc_html__('Divider', 'pastore-church'),
						'iconed' => esc_html__('With icon (image)', 'pastore-church')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'pastore-church'),
					"desc" => wp_kses_data( __("Title text alignment", 'pastore-church') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pastore_church_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'pastore-church'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'pastore-church'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'pastore-church') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'pastore-church'),
						'100' => esc_html__('Thin (100)', 'pastore-church'),
						'300' => esc_html__('Light (300)', 'pastore-church'),
						'400' => esc_html__('Normal (400)', 'pastore-church'),
						'600' => esc_html__('Semibold (600)', 'pastore-church'),
						'700' => esc_html__('Bold (700)', 'pastore-church'),
						'900' => esc_html__('Black (900)', 'pastore-church')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'pastore-church'),
					"desc" => wp_kses_data( __("Select color for the title", 'pastore-church') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'pastore-church'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'pastore-church') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => pastore_church_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'pastore-church'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'pastore-church') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => pastore_church_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'pastore-church'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'pastore-church') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'pastore-church'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'pastore-church') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'pastore-church'),
						'medium' => esc_html__('Medium', 'pastore-church'),
						'large' => esc_html__('Large', 'pastore-church')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'pastore-church'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'pastore-church') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'pastore-church'),
						'left' => esc_html__('Left', 'pastore-church')
					)
				),
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
if ( !function_exists( 'pastore_church_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_title_reg_shortcodes_vc');
	function pastore_church_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'pastore-church'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'pastore-church'),
					"description" => wp_kses_data( __("Title content", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'pastore-church'),
					"description" => wp_kses_data( __("Title type (header level)", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'pastore-church') => '1',
						esc_html__('Header 2', 'pastore-church') => '2',
						esc_html__('Header 3', 'pastore-church') => '3',
						esc_html__('Header 4', 'pastore-church') => '4',
						esc_html__('Header 5', 'pastore-church') => '5',
						esc_html__('Header 6', 'pastore-church') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'pastore-church'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'pastore-church') => 'regular',
						esc_html__('Underline', 'pastore-church') => 'underline',
						esc_html__('Divider', 'pastore-church') => 'divider',
						esc_html__('With icon (image)', 'pastore-church') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'pastore-church'),
					"description" => wp_kses_data( __("Title text alignment", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'pastore-church'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'pastore-church'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'pastore-church') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'pastore-church') => 'inherit',
						esc_html__('Thin (100)', 'pastore-church') => '100',
						esc_html__('Light (300)', 'pastore-church') => '300',
						esc_html__('Normal (400)', 'pastore-church') => '400',
						esc_html__('Semibold (600)', 'pastore-church') => '600',
						esc_html__('Bold (700)', 'pastore-church') => '700',
						esc_html__('Black (900)', 'pastore-church') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'pastore-church'),
					"description" => wp_kses_data( __("Select color for the title", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'pastore-church'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => pastore_church_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'pastore-church'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => pastore_church_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'pastore-church'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'pastore-church') ),
					"group" => esc_html__('Icon &amp; Image', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'pastore-church'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'pastore-church') ),
					"group" => esc_html__('Icon &amp; Image', 'pastore-church'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'pastore-church') => 'small',
						esc_html__('Medium', 'pastore-church') => 'medium',
						esc_html__('Large', 'pastore-church') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'pastore-church'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'pastore-church') ),
					"group" => esc_html__('Icon &amp; Image', 'pastore-church'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'pastore-church') => 'top',
						esc_html__('Left', 'pastore-church') => 'left'
					),
					"type" => "dropdown"
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>