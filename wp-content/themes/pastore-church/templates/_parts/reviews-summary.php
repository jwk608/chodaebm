<?php
// Get template args
extract(pastore_church_template_get_args('reviews-summary'));

// Reviews summary stars
$reviews_summary = '';
if ( !in_array($post_options['layout'], array('accordion_1', 'accordion_2', 'list')) && $post_options['reviews'] && pastore_church_get_custom_option('show_reviews', null, $post_data['post_id'], $post_data['post_type'])=='yes' ) {	//!!!!! Check option in the specified post
	$avg_author = $post_data['post_reviews_'.(pastore_church_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
	if ($avg_author > 0)
		$reviews_summary = '<div class="post_rating reviews_summary blog_reviews">'
								. '<div class="criteria_summary criteria_row">'
									. pastore_church_reviews_get_summary_stars($avg_author, false, false, 5)
								. '</div>'
							. '</div>';
}
pastore_church_storage_set('reviews_summary', $reviews_summary);
?>