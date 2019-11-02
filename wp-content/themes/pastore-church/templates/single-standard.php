<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_template_single_standard_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_template_single_standard_theme_setup', 1 );
	function pastore_church_template_single_standard_theme_setup() {
		pastore_church_add_template(array(
			'layout' => 'single-standard',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single standard', 'pastore-church'),
			'thumb_title'  => esc_html__('Fullwidth image (crop)', 'pastore-church'),
			'w'		 => 1170,
			'h'		 => 659
		));
	}
}

// Template output
if ( !function_exists( 'pastore_church_template_single_standard_output' ) ) {
	function pastore_church_template_single_standard_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && pastore_church_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = pastore_church_get_custom_option('show_post_title')=='yes' && (pastore_church_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));
		$title_tag = pastore_church_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		pastore_church_open_wrapper('<article class="'
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		if ($show_title && pastore_church_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><?php echo trim($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
		<?php
		}

		if (!$post_data['post_protected'] && pastore_church_get_custom_option('show_post_info')=='yes') {
			$post_options['info_parts'] = array('snippets'=>true);
			pastore_church_template_set_args('post-info', array(
				'post_options' => $post_options,
				'post_data' => $post_data
			));
			get_template_part(pastore_church_get_file_slug('templates/_parts/post-info.php'));
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(pastore_church_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])	// && $post_data['post_format']!='gallery' && $post_data['post_format']!='image')
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				echo trim($post_options['dedicated']);
			} else {
				pastore_church_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php echo trim($post_data['post_thumb']); ?></a>
				</div>
				<?php
			}
			?>
			</section>
			<?php
		}

		pastore_church_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');

		pastore_church_template_set_args('reviews-block', array(
			'post_options' => $post_options,
			'post_data' => $post_data,
			'avg_author' => $avg_author,
			'avg_users' => $avg_users
		));
		get_template_part(pastore_church_get_file_slug('templates/_parts/reviews-block.php'));

		// Prepare args for all rest template parts
		// This parts not pop args from storage!
		pastore_church_template_set_args('single-footer', array(
			'post_options' => $post_options,
			'post_data' => $post_data
		));

		// Post content
		if ($post_data['post_protected']) {
			echo trim($post_data['post_excerpt']);
			echo get_the_password_form();
		} else {
			if (!pastore_church_storage_empty('reviews_markup') && pastore_church_strpos($post_data['post_content'], pastore_church_get_reviews_placeholder())===false)
				$post_data['post_content'] = pastore_church_sc_reviews(array()) . ($post_data['post_content']);
			echo trim(pastore_church_gap_wrapper(pastore_church_reviews_wrapper($post_data['post_content'])));
			wp_link_pages( array(
				'before' => '<nav class="pagination_single"><span class="pager_pages">' . esc_html__( 'Pages:', 'pastore-church' ) . '</span>',
				'after' => '</nav>',
				'link_before' => '<span class="pager_numbers">',
				'link_after' => '</span>'
				)
			);

			if ( (pastore_church_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) || (!pastore_church_param_is_off(pastore_church_get_custom_option("show_share"))) ) {
				?>
				<div class="post_info_bottom">
					<?php
					// Likes
					pastore_church_enqueue_messages();
					$likes = isset($_COOKIE['pastore_church_likes']) ? $_COOKIE['pastore_church_likes'] : '';
					$allow = pastore_church_strpos($likes, ','.($post_data['post_id']).',')===false;
					?>
					<div class="post_info_likes">
						<a class="post_counters_item post_counters_likes icon-heart-light <?php echo !empty($allow) ? 'enabled' : 'disabled'; ?>" title="<?php echo !empty($allow) ? esc_attr__('Like', 'pastore-church') : esc_attr__('Dislike', 'pastore-church'); ?>" href="#"
						   data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
						   data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
						   data-title-like="<?php esc_attr_e('Like', 'pastore-church'); ?>"
						   data-title-dislike="<?php esc_attr_e('Dislike', 'pastore-church'); ?>"><span class="post_counters_number"><?php echo trim($post_data['post_likes']); ?></span></a>
						<span class="text"><?php echo ' '.esc_html__('Like this post', 'pastore-church'); ?></span>
					</div>
					<?php

					get_template_part(pastore_church_get_file_slug('templates/_parts/share.php'));
					?>
					<?php
						if (pastore_church_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links) ) {
					?>
					<span class="post_info_item post_info_tags"><?php echo join(' ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
					<?php } ?>
				</div>
				<?php
			}

		}

		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			get_template_part(pastore_church_get_file_slug('templates/_parts/editor-area.php'));
		}

		pastore_church_close_wrapper();	// .post_content

		if (!$post_data['post_protected']) {
			get_template_part(pastore_church_get_file_slug('templates/_parts/author-info.php'));
		}

		$sidebar_present = !pastore_church_param_is_off(pastore_church_get_custom_option('show_sidebar_main'));
		if (!$sidebar_present) pastore_church_close_wrapper();	// .post_item
		get_template_part(pastore_church_get_file_slug('templates/_parts/related-posts.php'));
		if ($sidebar_present) pastore_church_close_wrapper();		// .post_item

		if (!$post_data['post_protected']) {
			get_template_part(pastore_church_get_file_slug('templates/_parts/comments.php'));
		}

		// Manually pop args from storage
		// after all single footer templates
		pastore_church_template_get_args('single-footer');
	}
}
?>