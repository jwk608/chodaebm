<?php
// Get template args
extract(pastore_church_template_last_args('single-footer'));

$show_share = pastore_church_get_custom_option("show_share");
if (!pastore_church_param_is_off($show_share)) {
	$rez = pastore_church_show_share_links(array(
		'post_id'    => $post_data['post_id'],
		'post_link'  => $post_data['post_link'],
		'post_title' => $post_data['post_title'],
		'post_descr' => strip_tags($post_data['post_excerpt']),
		'post_thumb' => $post_data['post_attachment'],
		'type'		 => 'block',
		'echo'		 => false
	));
	if ($rez) {
		?>
		<div class="post_info_share post_info_share_<?php echo esc_attr($show_share); ?>"><?php echo trim($rez); ?></div>
		<?php
	}
}
?>