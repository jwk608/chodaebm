<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_template_related_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_template_related_theme_setup', 1 );
	function pastore_church_template_related_theme_setup() {
		pastore_church_add_template(array(
			'layout' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => esc_html__('Related posts /no columns/', 'pastore-church'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'pastore-church'),
			'w'		 => 370,
			'h'		 => 370
		));
		pastore_church_add_template(array(
			'layout' => 'related_2',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => esc_html__('Related posts /2 columns/', 'pastore-church'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'pastore-church'),
			'w'		 => 370,
			'h'		 => 370
		));
		pastore_church_add_template(array(
			'layout' => 'related_3',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => esc_html__('Related posts /3 columns/', 'pastore-church'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'pastore-church'),
			'w'		 => 370,
			'h'		 => 370
		));
		pastore_church_add_template(array(
			'layout' => 'related_4',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => esc_html__('Related posts /4 columns/', 'pastore-church'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'pastore-church'),
			'w'		 => 370,
			'h'		 => 370
		));
	}
}

// Template output
if ( !function_exists( 'pastore_church_template_related_output' ) ) {
	function pastore_church_template_related_output($post_options, $post_data) {
		$show_title = true;	//!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = pastore_church_in_shortcode_blogger(true) ? 'div' : 'article';
		/*
		pastore_church_template_set_args('reviews-summary', array(
			'post_options' => $post_options,
			'post_data' => $post_data
		));
		get_template_part(pastore_church_get_file_slug('templates/_parts/reviews-summary.php'));
		$reviews_summary = pastore_church_storage_get('reviews_summary');
		*/
		if ($columns > 1) {
			?><div class="<?php echo 'column-1_'.esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
		<<?php echo trim($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['number']); ?>">

			<div class="post_content">
				<?php if ($post_data['post_video'] || $post_data['post_thumb'] || $post_data['post_gallery']) { ?>
				<div class="post_featured">
					<?php
					pastore_church_template_set_args('post-featured', array(
						'post_options' => $post_options,
						'post_data' => $post_data
					));
					get_template_part(pastore_church_get_file_slug('templates/_parts/post-featured.php'));
					?>
				</div>
				<?php } ?>

				<?php if ($show_title) { ?>
					<div class="post_content_wrap">
						<?php
						if (!isset($post_options['links']) || $post_options['links']) { 
							?><h5 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h5><?php
						} else {
							?><h5 class="post_title"><?php echo trim($post_data['post_title']); ?></h5><?php
						}
						//echo trim($reviews_summary);
						if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
							?><div class="post_info_tags"><?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></div><?php
						}
						?>
					</div>
				<?php } ?>
			</div>	<!-- /.post_content -->
		</<?php echo trim($tag); ?>>	<!-- /.post_item -->
		<?php
		if ($columns > 1) {
			?></div><?php
		}
	}
}
?>