<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_call_to_action_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_call_to_action_theme_setup' );
	function pastore_church_sc_call_to_action_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_call_to_action_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_call_to_action_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_call_to_action id="unique_id" align="left|center|right"]
	[inner shortcodes]
[/trx_call_to_action]
*/

if (!function_exists('pastore_church_sc_call_to_action')) {	
	function pastore_church_sc_call_to_action($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"align" => "center",
			"custom" => "no",
			"accent" => "no",
			"image" => "",
			"video" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Learn more', 'pastore-church'),
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_call_to_action_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if (!empty($image)) {
			$thumb_sizes = pastore_church_get_thumb_sizes(array('layout' => 'excerpt'));
			$image = !empty($video) 
				? pastore_church_get_resized_image_url($image, $thumb_sizes['w'], $thumb_sizes['h']) 
				: pastore_church_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		if (!empty($video)) {
			$video = '<video' . ($id ? ' id="' . esc_attr($id.'_video') . '"' : '') 
				. ' class="sc_video"'
				. ' src="' . esc_url(pastore_church_get_video_player_url($video)) . '"'
				. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
				. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
				. ' data-ratio="16:9"'
				. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
				. ' controls="controls" loop="loop"'
				. '>'
				. '</video>';
			if (pastore_church_get_custom_option('substitute_video')=='no') {
				$video = pastore_church_get_video_frame($video, $image, '', '');
			} else {
				if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
					$video = pastore_church_substitute_video($video, $width, $height, false);
				}
			}
			if (pastore_church_get_theme_option('use_mediaelement')=='yes')
				pastore_church_enqueue_script('wp-mediaelement');
		}
		
		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pastore_church_get_css_dimensions_from_values($width, $height);
		
		$content = do_shortcode($content);
		
		$featured = ($style==1 && (!empty($content) || !empty($image) || !empty($video))
					? '<div class="sc_call_to_action_featured column-1_2">'
						. (!empty($content) 
							? $content 
							: (!empty($video) 
								? $video 
								: $image)
							)
						. '</div>'
					: '');
	
		$need_columns = ($featured || $style==2) && !in_array($align, array('center', 'none'))
							? ($style==2 ? 4 : 2)
							: 0;
		
		$buttons = (!empty($link)
						? '<div class="sc_call_to_action_buttons sc_item_buttons'.($need_columns && $style==2 ? ' right_col' : '').'">'
							. (!empty($link) 
								? '<div class="sc_call_to_action_button sc_item_button">'
									.do_shortcode('[trx_button size="large" link="'.esc_url($link).'"'. ($icon!='' ? ' icon="'.esc_attr($icon).'"' : '').']'.esc_html($link_caption).'[/trx_button]').'</div>'
								: '')
							. '</div>'
						: '');
	
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_call_to_action'
					. (pastore_church_param_is_on($accent) ? ' sc_call_to_action_accented' : '')
					. ' sc_call_to_action_style_' . esc_attr($style) 
					. ' sc_call_to_action_align_'.esc_attr($align)
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. '"'
				. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				//. ($need_columns ? '<div class="columns_wrap">' : '')
				. ($align!='right' ? $featured : '')
				. ($style==2 && $align=='right' ? $buttons : '')
				. '<div class="sc_call_to_action_info'.($need_columns ? ' left_col' : '').'">'
					. (!empty($title) ? '<h3 class="sc_call_to_action_title sc_item_title">' . trim(pastore_church_strmacros($title)) . '</h3>' : '')
					. (!empty($subtitle) ? '<h6 class="sc_call_to_action_subtitle sc_item_subtitle">' . trim(pastore_church_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($description) ? '<div class="sc_call_to_action_descr sc_item_descr">' . trim(pastore_church_strmacros($description)) . '</div>' : '')
					. ($style==1 ? $buttons : '')
				. '</div>'
				. ($style==2 && $align!='right' ? $buttons : '')
				. ($align=='right' ? $featured : '')
				//. ($need_columns ? '</div>' : '')
			. '</div>';
	
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_call_to_action', $atts, $content);
	}
	pastore_church_require_shortcode('trx_call_to_action', 'pastore_church_sc_call_to_action');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_call_to_action_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_call_to_action_reg_shortcodes');
	function pastore_church_sc_call_to_action_reg_shortcodes() {
	
		pastore_church_sc_map("trx_call_to_action", array(
			"title" => esc_html__("Call to action", 'pastore-church'),
			"desc" => wp_kses_data( __("Insert call to action block in your page (post)", 'pastore-church') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'pastore-church'),
					"desc" => wp_kses_data( __("Title for the block", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'pastore-church'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'pastore-church'),
					"desc" => wp_kses_data( __("Short description for the block", 'pastore-church') ),
					"value" => "",
					"type" => "textarea"
				),
				"style" => array(
					"title" => esc_html__("Style", 'pastore-church'),
					"desc" => wp_kses_data( __("Select style to display block", 'pastore-church') ),
					"value" => "1",
					"type" => "checklist",
					"options" => pastore_church_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'pastore-church'),
					"desc" => wp_kses_data( __("Alignment elements in the block", 'pastore-church') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pastore_church_get_sc_param('align')
				),
				"accent" => array(
					"title" => esc_html__("White color", 'pastore-church'),
					"desc" => wp_kses_data( __("Fill entire block with White color", 'pastore-church') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'pastore-church'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'pastore-church') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'pastore-church'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Button's icon",  'pastore-church'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'pastore-church') ),
					"value" => "",
					"type" => "icons",
					"options" => pastore_church_get_sc_param('icons')
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
if ( !function_exists( 'pastore_church_sc_call_to_action_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_call_to_action_reg_shortcodes_vc');
	function pastore_church_sc_call_to_action_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_call_to_action",
			"name" => esc_html__("Call to Action", 'pastore-church'),
			"description" => wp_kses_data( __("Insert call to action block in your page (post)", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_call_to_action',
			"class" => "trx_sc_single trx_sc_call_to_action",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block's style", 'pastore-church'),
					"description" => wp_kses_data( __("Select style to display this block", 'pastore-church') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(pastore_church_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'pastore-church'),
					"description" => wp_kses_data( __("Select block alignment", 'pastore-church') ),
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "accent",
					"heading" => esc_html__("White color", 'pastore-church'),
					"description" => wp_kses_data( __("Fill entire block with White color", 'pastore-church') ),
					"class" => "",
					"value" => array("Fill with White color" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'pastore-church'),
					"description" => wp_kses_data( __("Title for the block", 'pastore-church') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'pastore-church'),
					"description" => wp_kses_data( __("Subtitle for the block", 'pastore-church') ),
					"group" => esc_html__('Captions', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'pastore-church'),
					"description" => wp_kses_data( __("Description for the block", 'pastore-church') ),
					"group" => esc_html__('Captions', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'pastore-church'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'pastore-church') ),
					"group" => esc_html__('Captions', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'pastore-church'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'pastore-church') ),
					"group" => esc_html__('Captions', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'pastore-church') ),
					"class" => "",
					"value" => pastore_church_get_sc_param('icons'),
					"type" => "dropdown"
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
		
		class WPBakeryShortCode_Trx_Call_To_Action extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>