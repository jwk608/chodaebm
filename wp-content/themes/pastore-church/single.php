<?php
/**
 * Single post
 */
get_header(); 

$single_style = pastore_church_storage_get('single_style');
if (empty($single_style)) $single_style = pastore_church_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	pastore_church_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !pastore_church_param_is_off(pastore_church_get_custom_option('show_sidebar_main')),
			'content' => pastore_church_get_template_property($single_style, 'need_content'),
			'terms_list' => pastore_church_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>