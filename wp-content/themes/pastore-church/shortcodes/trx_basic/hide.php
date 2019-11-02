<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_hide_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_hide_theme_setup' );
	function pastore_church_sc_hide_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('pastore_church_sc_hide')) {	
	function pastore_church_sc_hide($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	pastore_church_require_shortcode('trx_hide', 'pastore_church_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_hide_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_hide_reg_shortcodes');
	function pastore_church_sc_hide_reg_shortcodes() {
	
		pastore_church_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'pastore-church'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'pastore-church'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'pastore-church'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'pastore-church') ),
					"value" => "yes",
					"size" => "small",
					"options" => pastore_church_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>