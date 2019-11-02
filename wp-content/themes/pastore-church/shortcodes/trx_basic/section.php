<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_section_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_section_theme_setup' );
	function pastore_church_sc_section_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_section_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_section id="unique_id" class="class_name" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

pastore_church_storage_set('sc_section_dedicated', '');

if (!function_exists('pastore_church_sc_section')) {	
	function pastore_church_sc_section($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"align_title" => "",
			"white" => "no",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'pastore-church'),
			"link" => '',
			"top_border" => "hide",
			"bottom_border" => "hide",
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
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = pastore_church_get_scheme_color('bg');
			$rgb = pastore_church_hex2rgb($bg_color);
		}

		$class .= ($bottom_border && pastore_church_param_is_on($bottom_border) ? ' bottom_border' : '');
		$class .= ($top_border && pastore_church_param_is_on($top_border) ? ' top_border' : '');

		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$class .= ($white && pastore_church_param_is_on($white) ? ' scheme_light' : '');
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(pastore_church_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;background-position: center center;') : '')
			.(!pastore_church_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(pastore_church_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !pastore_church_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = pastore_church_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && pastore_church_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = pastore_church_prepare_css_value($width);
		$height = pastore_church_prepare_css_value($height);
	
		if ((!pastore_church_param_is_off($scroll) || !pastore_church_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!pastore_church_param_is_off($scroll)) pastore_church_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '') 
					. ($scheme && !pastore_church_param_is_off($scheme) && !pastore_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (pastore_church_param_is_on($scroll) && !pastore_church_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || pastore_church_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (pastore_church_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (pastore_church_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (pastore_church_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (pastore_church_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle'. ($align_title && $align_title!='none' ? ' title_align'.esc_attr($align_title) : '') .'">' . trim(pastore_church_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_section_title sc_item_title'. ($align_title && $align_title!='none' ? ' title_align'.esc_attr($align_title) : '') .'">' . trim(pastore_church_strmacros($title)) . '</h2>' : '')
							. (!empty($description) ? '<div class="sc_section_descr sc_item_descr'. ($align_title && $align_title!='none' ? ' title_align'.esc_attr($align_title) : '') .'">' . trim(pastore_church_strmacros($description)) . '</div>' : '')
							. '<div class="sc_section_content_wrap">' . do_shortcode($content) . '</div>'
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.pastore_church_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (pastore_church_param_is_on($pan) ? '</div>' : '')
					. (pastore_church_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!pastore_church_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || pastore_church_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (pastore_church_param_is_on($dedicated)) {
			if (pastore_church_storage_get('sc_section_dedicated')=='') {
				pastore_church_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	pastore_church_require_shortcode('trx_section', 'pastore_church_sc_section');
}

if (!function_exists('pastore_church_sc_block')) {	
	function pastore_church_sc_block($atts, $content=null) {
		$atts['class'] = (!empty($atts['class']) ? ' ' : '') . 'sc_section_block';
		return apply_filters('pastore_church_shortcode_output', pastore_church_sc_section($atts, $content), 'trx_block', $atts, $content);
	}
	pastore_church_require_shortcode('trx_block', 'pastore_church_sc_block');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_section_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_section_reg_shortcodes');
	function pastore_church_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", 'pastore-church'),
			"desc" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'pastore-church') ),
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
				"align_title" => array(
					"title" => esc_html__("Align title", 'pastore-church'),
					"desc" => wp_kses_data( __("Select title alignment", 'pastore-church') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pastore_church_get_sc_param('align')
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'pastore-church'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'pastore-church'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"bottom_border" => array(
					"title" => esc_html__("Bottom border", 'pastore-church'),
					"desc" => wp_kses_data( __("Show Bottom border", 'pastore-church') ),
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"top_border" => array(
					"title" => esc_html__("Top border", 'pastore-church'),
					"desc" => wp_kses_data( __("Show Top border", 'pastore-church') ),
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"dedicated" => array(
					"title" => esc_html__("Dedicated", 'pastore-church'),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'pastore-church') ),
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Align", 'pastore-church'),
					"desc" => wp_kses_data( __("Select block alignment", 'pastore-church') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pastore_church_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", 'pastore-church'),
					"desc" => wp_kses_data( __("Select width for columns emulation", 'pastore-church') ),
					"value" => "none",
					"type" => "checklist",
					"options" => pastore_church_get_sc_param('columns')
				), 
				"pan" => array(
					"title" => esc_html__("Use pan effect", 'pastore-church'),
					"desc" => wp_kses_data( __("Use pan effect to show section content", 'pastore-church') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'pastore-church'),
					"desc" => wp_kses_data( __("Use scroller to show section content", 'pastore-church') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"scroll_dir" => array(
					"title" => esc_html__("Scroll and Pan direction", 'pastore-church'),
					"desc" => wp_kses_data( __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'pastore-church') ),
					"dependency" => array(
						'pan' => array('yes'),
						'scroll' => array('yes')
					),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => pastore_church_get_sc_param('dir')
				),
				"scroll_controls" => array(
					"title" => esc_html__("Scroll controls", 'pastore-church'),
					"desc" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'pastore-church') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "hide",
					"type" => "checklist",
					"options" => pastore_church_get_sc_param('controls')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'pastore-church'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'pastore-church') ),
					"value" => "",
					"type" => "checklist",
					"options" => pastore_church_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", 'pastore-church'),
					"desc" => wp_kses_data( __("Any color for objects in this section", 'pastore-church') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"white" => array(
					"title" => esc_html__("White elements", 'pastore-church'),
					"desc" => wp_kses_data( __("White elements in section content", 'pastore-church') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'pastore-church'),
					"desc" => wp_kses_data( __("Any background color for this section", 'pastore-church') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'pastore-church'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'pastore-church') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", 'pastore-church'),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'pastore-church') ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'pastore-church'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'pastore-church') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'pastore-church'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'pastore-church') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", 'pastore-church'),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'pastore-church') ),
					"value" => "yes",
					"dependency" => array(
						'compare' => 'or',
						'bg_color' => array('not_empty'),
						'bg_texture' => array('not_empty'),
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'pastore-church'),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'pastore-church'),
					"desc" => wp_kses_data( __("Font weight of the text", 'pastore-church') ),
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
				"_content_" => array(
					"title" => esc_html__("Container content", 'pastore-church'),
					"desc" => wp_kses_data( __("Content for section container", 'pastore-church') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
		);
		pastore_church_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", 'pastore-church');
		$sc["desc"] = esc_html__("Container for any section ([trx_block] analog - to enable nesting)", 'pastore-church');
		pastore_church_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_section_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_section_reg_shortcodes_vc');
	function pastore_church_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", 'pastore-church'),
			"description" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", 'pastore-church'),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'pastore-church') => 'yes'),
					"type" => "checkbox"
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
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", 'pastore-church'),
					"description" => wp_kses_data( __("Select width for columns emulation", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('columns')),
					"type" => "dropdown"
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
					"param_name" => "align_title",
					"heading" => esc_html__("Title Alignment", 'pastore-church'),
					"description" => wp_kses_data( __("Select title alignment", 'pastore-church') ),
					"group" => esc_html__('Captions', 'pastore-church'),
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('align')),
					"type" => "dropdown"
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
					"param_name" => "pan",
					"heading" => esc_html__("Use pan effect", 'pastore-church'),
					"description" => wp_kses_data( __("Use pan effect to show section content", 'pastore-church') ),
					"group" => esc_html__('Scroll', 'pastore-church'),
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", 'pastore-church'),
					"description" => wp_kses_data( __("Use scroller to show section content", 'pastore-church') ),
					"group" => esc_html__('Scroll', 'pastore-church'),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll_dir",
					"heading" => esc_html__("Scroll direction", 'pastore-church'),
					"description" => wp_kses_data( __("Scroll direction (if Use scroller = yes)", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"group" => esc_html__('Scroll', 'pastore-church'),
					"value" => array_flip(pastore_church_get_sc_param('dir')),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll_controls",
					"heading" => esc_html__("Scroll controls", 'pastore-church'),
					"description" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Scroll', 'pastore-church'),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"value" => array_flip(pastore_church_get_sc_param('controls')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'pastore-church'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'pastore-church') ),
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'pastore-church'),
					"description" => wp_kses_data( __("Any color for objects in this section", 'pastore-church') ),
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "white",
					"heading" => esc_html__("White elements", 'pastore-church'),
					"description" => wp_kses_data( __("White elements in section content", 'pastore-church') ),
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"class" => "",
					"value" => array(esc_html__('White elements', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "top_border",
					"heading" => esc_html__("Top border", 'pastore-church'),
					"description" => wp_kses_data( __("Top border", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"std" => "no",
					"value" => array(esc_html__('Show Top border', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bottom_border",
					"heading" => esc_html__("Bottom border", 'pastore-church'),
					"description" => wp_kses_data( __("Show Bottom border", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"std" => "no",
					"value" => array(esc_html__('Show Bottom border', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'pastore-church'),
					"description" => wp_kses_data( __("Any background color for this section", 'pastore-church') ),
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'pastore-church'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'pastore-church') ),
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'pastore-church'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'pastore-church') ),
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"class" => "",
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'pastore-church'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'pastore-church') ),
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'pastore-church'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'pastore-church') ),
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", 'pastore-church'),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'pastore-church') ),
					"group" => esc_html__('Colors and Images', 'pastore-church'),
					"class" => "",
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'pastore-church') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'pastore-church'),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'pastore-church'),
					"description" => wp_kses_data( __("Font weight of the text", 'pastore-church') ),
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
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Container content", 'pastore-church'),
					"description" => wp_kses_data( __("Content for section container", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
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
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", 'pastore-church');
		$sc["description"] = wp_kses_data( __("Container for any section ([trx_block] analog - to enable nesting)", 'pastore-church') );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends PASTORE_CHURCH_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends PASTORE_CHURCH_VC_ShortCodeCollection {}
	}
}
?>