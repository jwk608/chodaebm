<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_template_news_announce_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_template_news_announce_theme_setup', 1 );
	function pastore_church_template_news_announce_theme_setup() {
		pastore_church_add_template(array(
			'layout' => 'news-announce',
			'template' => 'news-announce',
			'mode'   => 'news',
			'title'  => esc_html__('Recent News /Style Announce/', 'pastore-church'),
			'thumb_title'  => esc_html__('Medium image Announce (crop)', 'pastore-church'),
			'w'		 => 370,
			'h'		 => 334
		));
	}
}

// Template output
if ( !function_exists( 'pastore_church_template_news_announce_output' ) ) {
	function pastore_church_template_news_announce_output($post_options, $post_data) {
		$style = $post_options['layout'];
		$number = $post_options['number'];
		$count = $post_options['posts_on_page'];
		$post_format = $post_data['post_format'];
		$readmore = esc_html__('Read More','pastore-church');
		$grid = array(
			array('full'),
			array('full', 'full'),
			array('big', 'medium', 'big right'),
			array('big', 'medium', 'big right', 'full'),
			array('big', 'medium', 'big right', 'full', 'full'),
			array('big', 'medium', 'big right', 'big', 'medium', 'big right'),
			array('big', 'medium', 'big right', 'big', 'medium', 'big right', 'full'),
			array('big', 'medium', 'big right', 'big', 'medium', 'big right', 'full', 'full')
		);
		$thumb_slug = $grid[$count-$number >= 8 ? 8 : ($count-1)%8][($number-1)%8];
		?><article id="post-<?php echo esc_html($post_data['post_id']); ?>"
			<?php post_class( 'post_item post_layout_'.esc_attr($style)
							.' post_format_'.esc_attr($post_format)
							.' post_size_'.esc_attr($thumb_slug)
							); ?>
			>
			<?php if ($post_data['post_flags']['sticky']) {	?>
				<span class="sticky_label"></span>
			<?php } ?>
			<div class="post_featured">
				<?php
				if ($post_data['post_gallery']){
					echo $post_data['post_gallery'];
				}
				else if ($post_data['post_thumb']) {
					$post_data['post_video'] = $post_data['post_audio'] = $post_data['post_gallery'] = '';
					pastore_church_template_set_args('post-featured', array(
						'post_options' => $post_options,
						'post_data' => $post_data
					));
					get_template_part(pastore_church_get_file_slug('templates/_parts/post-featured.php'));
				}
				?>
			</div>
			<div class="post_des">
				<?php if(!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'))) { ?>
					<h5 class="post_title entry-title"><a href="<?php echo esc_url($post_data['post_link']); ?>" rel="bookmark"><?php echo trim($post_data['post_title']); ?></a></h5>
						<div class="post_info">
							<span class="post_meta_date"><?php echo esc_html($post_data['post_date']); ?></span>
							<span class="post_meta_author icon-user-light"><?php echo trim($post_data['post_author_link']); ?></span>
							<span class="post_meta_comments icon-comment-light"><?php echo trim($post_data['post_comments']); ?></span>
						</div>
				<?php }
				if ($post_data['post_excerpt']) {
					echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))
						? $post_data['post_excerpt']
						: wpautop(pastore_church_strshort($post_data['post_excerpt'], isset($post_options['descr'])
								? $post_options['descr']
								: 100
							)
						);
				}
				if(!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')) && $post_data['post_link']) { ?>
					<a class="sc_button sc_button_square sc_button_style_color_1 sc_button_size_small" href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($readmore); ?></a><?php
				}
				?>
			</div>

		</article>
		<?php
	}
}
?>