<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_tabs_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_tabs_theme_setup' );
	function pastore_church_sc_tabs_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_tabs_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_tabs_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tabs id="unique_id" tab_names="Planning|Development|Support" initial="1 - num_tabs"]
	[trx_tab]Randomised words which don't look even slightly believable. If you are going to use a passage. You need to be sure there isn't anything embarrassing hidden in the middle of text established fact that a reader will be istracted by the readable content of a page when looking at its layout.[/trx_tab]
	[trx_tab]Fact reader will be distracted by the <a href="#" class="main_link">readable content</a> of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have evolved over. There are many variations of passages of Lorem Ipsum available, but the majority.[/trx_tab]
	[trx_tab]Distracted by the  readable content  of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have  evolved over.  There are many variations of passages of Lorem Ipsum available.[/trx_tab]
[/trx_tabs]
*/

if (!function_exists('pastore_church_sc_tabs')) {	
	function pastore_church_sc_tabs($atts, $content = null) {
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"scroll" => "no",
			"style" => "1",
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
	
		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pastore_church_get_css_dimensions_from_values($width);
	
		if (!pastore_church_param_is_off($scroll)) pastore_church_enqueue_slider();
		if (empty($id)) $id = 'sc_tabs_'.str_replace('.', '', mt_rand());
	
		pastore_church_storage_set('sc_tab_data', array(
			'counter'=> 0,
            'scroll' => $scroll,
            'height' => pastore_church_prepare_css_value($height),
            'id'     => $id,
            'titles' => array()
            )
        );
	
		$content = do_shortcode($content);
	
		$sc_tab_titles = pastore_church_storage_get_array('sc_tab_data', 'titles');
	
		$initial = max(1, min(count($sc_tab_titles), (int) $initial));
	
		$tabs_output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
							. ' class="sc_tabs sc_tabs_style_'.esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
							. ' data-active="' . ($initial-1) . '"'
							. '>'
						.'<ul class="sc_tabs_titles">';
		$titles_output = '';
		$icon = '';
		for ($i = 0; $i < count($sc_tab_titles); $i++) {
			$classes = array('sc_tabs_title');
			if ($i == 0) $classes[] = 'first';
			else if ($i == count($sc_tab_titles) - 1) $classes[] = 'last';
			if($sc_tab_titles[$i]['icon']){
				$icon =  '<span class="sc_tab_icon '.($sc_tab_titles[$i]['icon']!='' && $sc_tab_titles[$i]['icon']!='none' ? ' '.esc_attr($sc_tab_titles[$i]['icon']) : '').'"'.'></span>';
			}
			if ($icon != '' && $style == 3){
				$titles_output .= '<li class="' . join(' ', $classes) . '">'
					. '<a href="#' . esc_attr($sc_tab_titles[$i]['id']) . '" class="theme_button" id="' . esc_attr($sc_tab_titles[$i]['id']) . '_tab">' . $icon . '</a>'
					. '</li>';
			}
			else {
				$titles_output .= '<li class="' . join(' ', $classes) . '">'
					. '<a href="#' . esc_attr($sc_tab_titles[$i]['id']) . '" class="theme_button" id="' . esc_attr($sc_tab_titles[$i]['id']) . '_tab">' . ($sc_tab_titles[$i]['title']) . '</a>'
					. '</li>';
			}
		}
	
		pastore_church_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
		pastore_church_enqueue_script('jquery-effects-fade', false, array('jquery','jquery-effects-core'), null, true);
	
		$tabs_output .= $titles_output
			. '</ul>' 
			. ($content)
			.'</div>';
		return apply_filters('pastore_church_shortcode_output', $tabs_output, 'trx_tabs', $atts, $content);
	}
	pastore_church_require_shortcode("trx_tabs", "pastore_church_sc_tabs");
}


if (!function_exists('pastore_church_sc_tab')) {	
	function pastore_church_sc_tab($atts, $content = null) {
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"tab_id" => "",		// get it from VC
			"title" => "",		// get it from VC
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		pastore_church_storage_inc_array('sc_tab_data', 'counter');
		$counter = pastore_church_storage_get_array('sc_tab_data', 'counter');
		if (empty($id))
			$id = !empty($tab_id) ? $tab_id : pastore_church_storage_get_array('sc_tab_data', 'id').'_'.intval($counter);
		$sc_tab_titles = pastore_church_storage_get_array('sc_tab_data', 'titles');
		if (isset($sc_tab_titles[$counter-1])) {
			$sc_tab_titles[$counter-1]['id'] = $id;
			if (!empty($title))
				$sc_tab_titles[$counter-1]['title'] = $title;
			if (!empty($icon))
				$sc_tab_titles[$counter-1]['icon'] = $icon;
		} else {
			$sc_tab_titles[] = array(
				'id' => $id,
				'title' => $title,
				'icon' => $icon
			);
		}
		pastore_church_storage_set_array('sc_tab_data', 'titles', $sc_tab_titles);




		$output = '<div id="'.esc_attr($id).'"'
					.' class="sc_tabs_content' 
						. ($counter % 2 == 1 ? ' odd' : ' even') 
						. ($counter == 1 ? ' first' : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. '>' 
				. (pastore_church_param_is_on(pastore_church_storage_get_array('sc_tab_data', 'scroll')) 
					? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical" style="height:'.(pastore_church_storage_get_array('sc_tab_data', 'height') != '' ? pastore_church_storage_get_array('sc_tab_data', 'height') : '200px').';"><div class="sc_scroll_wrapper swiper-wrapper"><div class="sc_scroll_slide swiper-slide">' 
					: '')
				. do_shortcode($content) 
				. (pastore_church_param_is_on(pastore_church_storage_get_array('sc_tab_data', 'scroll')) 
					? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical '.esc_attr($id).'_scroll_bar"></div></div>' 
					: '')
			. '</div>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_tab', $atts, $content);
	}
	pastore_church_require_shortcode("trx_tab", "pastore_church_sc_tab");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_tabs_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_tabs_reg_shortcodes');
	function pastore_church_sc_tabs_reg_shortcodes() {
	
		pastore_church_sc_map("trx_tabs", array(
			"title" => esc_html__("Tabs", 'pastore-church'),
			"desc" => wp_kses_data( __("Insert tabs in your page (post)", 'pastore-church') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Tabs style", 'pastore-church'),
					"desc" => wp_kses_data( __("Select style for tabs items", 'pastore-church') ),
					"value" => 1,
					"options" => pastore_church_get_list_styles(1, 3),
					"type" => "radio"
				),
				"initial" => array(
					"title" => esc_html__("Initially opened tab", 'pastore-church'),
					"desc" => wp_kses_data( __("Number of initially opened tab", 'pastore-church') ),
					"divider" => true,
					"value" => 1,
					"min" => 0,
					"type" => "spinner"
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'pastore-church'),
					"desc" => wp_kses_data( __("Use scroller to show tab content (height parameter required)", 'pastore-church') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
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
			),
			"children" => array(
				"name" => "trx_tab",
				"title" => esc_html__("Tab", 'pastore-church'),
				"desc" => wp_kses_data( __("Tab item", 'pastore-church') ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Tab title", 'pastore-church'),
						"desc" => wp_kses_data( __("Current tab title", 'pastore-church') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__('Tab font icon',  'pastore-church'),
						"desc" => wp_kses_data( __("Select font icon for the Tab item from Fontello icons set (if style=Style 3)",  'pastore-church') ),
						"value" => "",
						"type" => "icons",
						"options" => pastore_church_get_sc_param('icons')
					),
					"_content_" => array(
						"title" => esc_html__("Tab content", 'pastore-church'),
						"desc" => wp_kses_data( __("Current tab content", 'pastore-church') ),
						"divider" => true,
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
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
if ( !function_exists( 'pastore_church_sc_tabs_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_tabs_reg_shortcodes_vc');
	function pastore_church_sc_tabs_reg_shortcodes_vc() {
	
		$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
		$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
		vc_map( array(
			"base" => "trx_tabs",
			"name" => esc_html__("Tabs", 'pastore-church'),
			"desc" => wp_kses_data( __("Tabs", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_tabs',
			"class" => "trx_sc_collection trx_sc_tabs",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_tab'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Tabs style", 'pastore-church'),
					"desc" => wp_kses_data( __("Select style of tabs items", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(pastore_church_get_list_styles(1, 3)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "initial",
					"heading" => esc_html__("Initially opened tab", 'pastore-church'),
					"desc" => wp_kses_data( __("Number of initially opened tab", 'pastore-church') ),
					"class" => "",
					"value" => 1,
					"type" => "textfield"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Scroller", 'pastore-church'),
					"desc" => wp_kses_data( __("Use scroller to show tab content (height parameter required)", 'pastore-church') ),
					"class" => "",
					"value" => array("Use scroller" => "yes" ),
					"type" => "checkbox"
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
			'default_content' => '
				[trx_tab title="' . esc_html__( 'Tab 1', 'pastore-church' ) . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
				[trx_tab title="' . esc_html__( 'Tab 2', 'pastore-church' ) . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
			',
			"custom_markup" => '
				<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
					<ul class="tabs_controls">
					</ul>
					%content%
				</div>
			',
			'js_view' => 'VcTrxTabsView'
		) );
		
		
		vc_map( array(
			"base" => "trx_tab",
			"name" => esc_html__("Tab item", 'pastore-church'),
			"desc" => wp_kses_data( __("Single tab item", 'pastore-church') ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_tab",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_tab',
			"as_child" => array('only' => 'trx_tabs'),
			"as_parent" => array('except' => 'trx_tabs'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Tab title", 'pastore-church'),
					"desc" => wp_kses_data( __("Title for current tab", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Tab font icon", 'pastore-church'),
					"description" => wp_kses_data( __("Select font icon for the Tab item from Fontello icons set (if style=Style 3)", 'pastore-church') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'pastore-church'),
					"value" => pastore_church_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "tab_id",
					"heading" => esc_html__("Tab ID", 'pastore-church'),
					"desc" => wp_kses_data( __("ID for current tab (required). Please, start it from letter.", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('css')
			),
		  'js_view' => 'VcTrxTabView'
		) );
		class WPBakeryShortCode_Trx_Tabs extends PASTORE_CHURCH_VC_ShortCodeTabs {}
		class WPBakeryShortCode_Trx_Tab extends PASTORE_CHURCH_VC_ShortCodeTab {}
	}
}
?>