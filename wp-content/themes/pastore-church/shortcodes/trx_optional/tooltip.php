<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_tooltip_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_tooltip_theme_setup' );
	function pastore_church_sc_tooltip_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('pastore_church_sc_tooltip')) {	
	function pastore_church_sc_tooltip($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	pastore_church_require_shortcode('trx_tooltip', 'pastore_church_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_tooltip_reg_shortcodes');
	function pastore_church_sc_tooltip_reg_shortcodes() {
	
		pastore_church_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'pastore-church'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'pastore-church') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'pastore-church'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'pastore-church'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'pastore-church') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => pastore_church_get_sc_param('id'),
				"class" => pastore_church_get_sc_param('class'),
				"css" => pastore_church_get_sc_param('css')
			)
		));
	}
}
?>