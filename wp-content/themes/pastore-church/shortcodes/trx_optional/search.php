<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_search_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_search_theme_setup' );
	function pastore_church_sc_search_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_search_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('pastore_church_sc_search')) {	
	function pastore_church_sc_search($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'pastore-church'),
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
		if (empty($ajax)) $ajax = pastore_church_get_theme_option('use_ajax_search');
		// Load core messages
		pastore_church_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (pastore_church_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search-light" title="' . ($state=='closed' ? esc_attr__('Open search', 'pastore-church') : esc_attr__('Start search', 'pastore-church')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !pastore_church_param_is_off($scheme) && !pastore_church_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	pastore_church_require_shortcode('trx_search', 'pastore_church_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_search_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_search_reg_shortcodes');
	function pastore_church_sc_search_reg_shortcodes() {
	
		pastore_church_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'pastore-church'),
			"desc" => wp_kses_data( __("Show search form", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'pastore-church'),
					"desc" => wp_kses_data( __("Select style to display search field", 'pastore-church') ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'pastore-church'),
						"rounded" => esc_html__('Rounded', 'pastore-church')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'pastore-church'),
					"desc" => wp_kses_data( __("Select search field initial state", 'pastore-church') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'pastore-church'),
						"opened" => esc_html__('Opened', 'pastore-church'),
						"closed" => esc_html__('Closed', 'pastore-church')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'pastore-church'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'pastore-church') ),
					"value" => esc_html__("Search &hellip;", 'pastore-church'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'pastore-church'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'pastore-church') ),
					"value" => "yes",
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_search_reg_shortcodes_vc');
	function pastore_church_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'pastore-church'),
			"description" => wp_kses_data( __("Insert search form", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'pastore-church'),
					"description" => wp_kses_data( __("Select style to display search field", 'pastore-church') ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'pastore-church') => "regular",
						esc_html__('Flat', 'pastore-church') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'pastore-church'),
					"description" => wp_kses_data( __("Select search field initial state", 'pastore-church') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'pastore-church')  => "fixed",
						esc_html__('Opened', 'pastore-church') => "opened",
						esc_html__('Closed', 'pastore-church') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'pastore-church'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'pastore-church'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'pastore-church'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'pastore-church') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'pastore-church') => 'yes'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>