<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move pastore_church_set_post_views to the javascript - counter will work under cache system
	if (pastore_church_get_custom_option('use_ajax_views_counter')=='no') {
		pastore_church_set_post_views(get_the_ID());
	}

	pastore_church_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !pastore_church_param_is_off(pastore_church_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>