<?php
if (!function_exists('pastore_church_theme_shortcodes_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_theme_shortcodes_setup', 1 );
	function pastore_church_theme_shortcodes_setup() {
		add_filter('pastore_church_filter_googlemap_styles', 'pastore_church_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'pastore_church_theme_shortcodes_googlemap_styles' ) ) {
	function pastore_church_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'pastore-church');
		$list['greyscale']	= esc_html__('Greyscale', 'pastore-church');
		$list['inverse']	= esc_html__('Inverse', 'pastore-church');
		$list['dark']	= esc_html__('Dark', 'pastore-church');
		return $list;
	}
}
?>