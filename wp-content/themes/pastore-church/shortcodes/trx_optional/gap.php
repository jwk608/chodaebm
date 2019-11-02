<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_gap_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_gap_theme_setup' );
	function pastore_church_sc_gap_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_gap_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('pastore_church_sc_gap')) {	
	function pastore_church_sc_gap($atts, $content = null) {
		if (pastore_church_in_shortcode_blogger()) return '';
		$output = pastore_church_gap_start() . do_shortcode($content) . pastore_church_gap_end();
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	pastore_church_require_shortcode("trx_gap", "pastore_church_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_gap_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_gap_reg_shortcodes');
	function pastore_church_sc_gap_reg_shortcodes() {
	
		pastore_church_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", 'pastore-church'),
			"desc" => wp_kses_data( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'pastore-church') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", 'pastore-church'),
					"desc" => wp_kses_data( __("Gap inner content", 'pastore-church') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_gap_reg_shortcodes_vc');
	function pastore_church_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", 'pastore-church'),
			"description" => wp_kses_data( __("Insert gap (fullwidth area) in the post content", 'pastore-church') ),
			"category" => esc_html__('Structure', 'pastore-church'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Gap content", 'pastore-church'),
					"description" => wp_kses_data( __("Gap inner content", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				)
				*/
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends PASTORE_CHURCH_VC_ShortCodeCollection {}
	}
}
?>