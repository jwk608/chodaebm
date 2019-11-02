<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_br_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_br_theme_setup' );
	function pastore_church_sc_br_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_br_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('pastore_church_sc_br')) {	
	function pastore_church_sc_br($atts, $content = null) {
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	pastore_church_require_shortcode("trx_br", "pastore_church_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_br_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_br_reg_shortcodes');
	function pastore_church_sc_br_reg_shortcodes() {
	
		pastore_church_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'pastore-church'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'pastore-church'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'pastore-church') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'pastore-church'),
						'left' => esc_html__('Left', 'pastore-church'),
						'right' => esc_html__('Right', 'pastore-church'),
						'both' => esc_html__('Both', 'pastore-church')
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_br_reg_shortcodes_vc');
	function pastore_church_sc_br_reg_shortcodes_vc() {
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", 'pastore-church'),
			"description" => wp_kses_data( __("Line break or Clear Floating", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", 'pastore-church'),
					"description" => wp_kses_data( __("Select clear side (if need)", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'pastore-church') => 'none',
						esc_html__('Left', 'pastore-church') => 'left',
						esc_html__('Right', 'pastore-church') => 'right',
						esc_html__('Both', 'pastore-church') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends PASTORE_CHURCH_VC_ShortCodeSingle {}
*/
	}
}
?>