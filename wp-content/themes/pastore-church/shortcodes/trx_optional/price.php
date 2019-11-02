<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_price_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_price_theme_setup' );
	function pastore_church_sc_price_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_price_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_price_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('pastore_church_sc_price')) {	
	function pastore_church_sc_price($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		if (!empty($money)) {
			$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
			$m = explode('.', str_replace(',', '.', $money));
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. '>'
				. '<span class="sc_price_currency">'.($currency).'</span>'
				. '<span class="sc_price_money">'.($m[0]).'</span>'
				. (!empty($m[1]) ? '<span class="sc_price_info">' : '')
				. (!empty($m[1]) ? '<span class="sc_price_penny">'.($m[1]).'</span>' : '')
				. (!empty($period) ? '<span class="sc_price_period">'.($period).'</span>' : (!empty($m[1]) ? '<span class="sc_price_period_empty"></span>' : ''))
				. (!empty($m[1]) ? '</span>' : '')
				. '</div>';
		}
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_price', $atts, $content);
	}
	pastore_church_require_shortcode('trx_price', 'pastore_church_sc_price');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_price_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_price_reg_shortcodes');
	function pastore_church_sc_price_reg_shortcodes() {
	
		pastore_church_sc_map("trx_price", array(
			"title" => esc_html__("Price", 'pastore-church'),
			"desc" => wp_kses_data( __("Insert price with decoration", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"money" => array(
					"title" => esc_html__("Money", 'pastore-church'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'pastore-church'),
					"desc" => wp_kses_data( __("Currency character", 'pastore-church') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'pastore-church'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'pastore-church'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'pastore-church') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pastore_church_get_sc_param('float')
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
if ( !function_exists( 'pastore_church_sc_price_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_price_reg_shortcodes_vc');
	function pastore_church_sc_price_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price",
			"name" => esc_html__("Price", 'pastore-church'),
			"description" => wp_kses_data( __("Insert price with decoration", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_price',
			"class" => "trx_sc_single trx_sc_price",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'pastore-church'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'pastore-church'),
					"description" => wp_kses_data( __("Currency character", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'pastore-church'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'pastore-church'),
					"description" => wp_kses_data( __("Align price to left or right side", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('float')),
					"type" => "dropdown"
				),
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('css'),
				pastore_church_get_vc_param('margin_top'),
				pastore_church_get_vc_param('margin_bottom'),
				pastore_church_get_vc_param('margin_left'),
				pastore_church_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Price extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>