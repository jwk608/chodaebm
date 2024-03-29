<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_template_attachment_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_template_attachment_theme_setup', 1 );
	function pastore_church_template_attachment_theme_setup() {
		pastore_church_add_template(array(
			'layout' => 'attachment',
			'mode'   => 'internal',
			'title'  => esc_html__('Attachment page layout', 'pastore-church'),
			'thumb_title'  => esc_html__('Fullwidth image', 'pastore-church'),
			'w'		 => 1170,
			'h'		 => null,
			'h_crop' => 659
		));
	}
}

// Template output
if ( !function_exists( 'pastore_church_template_attachment_output' ) ) {
	function pastore_church_template_attachment_output($post_options, $post_data) {
		$post_data['post_views']++;
		$title_tag = pastore_church_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';
		?>
		<article <?php post_class('post_item post_item_attachment template_attachment'); ?>>
		
			<<?php echo esc_html($title_tag); ?> class="post_title"><?php echo !empty($post_data['post_excerpt']) ? strip_tags($post_data['post_excerpt']) : $post_data['post_title']; ?></<?php echo esc_html($title_tag); ?>>

			<div class="post_featured">
				<div class="post_thumb post_nav" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<?php
					echo trim($post_data['post_thumb']);
					$post = get_post();
					$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
					if (is_array($attachments) && count($attachments) > 0) {
						foreach ($attachments as $k => $attachment) {
							if ( $attachment->ID == $post->ID )
								break;
						}
					}
					if ( isset( $attachments[ $k-1 ] ) ) {
						$link = get_permalink( $attachments[ $k-1 ]->ID ).'#top_of_page';
						$desc = pastore_church_strshort(!empty($attachments[ $k-1 ]->post_excerpt) ? $attachments[ $k-1 ]->post_excerpt : $attachments[ $k-1 ]->post_title, 30);
						?>
						<a class="post_nav_item post_nav_prev" href="<?php echo esc_url($link); ?>">
							<span class="post_nav_info">
								<span class="post_nav_info_title"><?php esc_html_e('Previous item', 'pastore-church'); ?></span>
								<span class="post_nav_info_description"><?php echo trim($desc); ?></span>
							</span>
						</a>
						<?php
					}
					if ( isset( $attachments[ $k+1 ] ) ) {
						$link = get_permalink( $attachments[ $k+1 ]->ID ).'#top_of_page';
						$desc = pastore_church_strshort(!empty($attachments[ $k+1 ]->post_excerpt) ? $attachments[ $k+1 ]->post_excerpt : $attachments[ $k+1 ]->post_title, 30);
						?>
						<a class="post_nav_item post_nav_next" href="<?php echo esc_url($link); ?>">
							<span class="post_nav_info">
								<span class="post_nav_info_title"><?php esc_html_e('Next item', 'pastore-church'); ?></span>
								<span class="post_nav_info_description"><?php echo trim($desc); ?></span>
							</span>
						</a>
						<?php
					}
					?>
				</div>
			</div>
		
			<?php
			if (!$post_data['post_protected'] && pastore_church_get_custom_option('show_post_info')=='yes') {
				pastore_church_template_set_args('post-info', array(
					'post_options' => $post_options,
					'post_data' => $post_data
				));
				get_template_part(pastore_church_get_file_slug('templates/_parts/post-info.php')); 
			}
			?>
		
			<div class="post_content">
				<?php
				// Post content
				if ($post_data['post_protected']) { 
					echo trim($post_data['post_excerpt']);
				} else {
					echo !empty($post_data['post_content']) ? trim($post_data['post_content']) : esc_html__('No image description ...', 'pastore-church'); 
					wp_link_pages( array( 
						'before' => '<div class="nav_pages_parts"><span class="pages">' . esc_html__( 'Pages:', 'pastore-church' ) . '</span>', 
						'after' => '</div>',
						'link_before' => '<span class="page_num">',
						'link_after' => '</span>'
					) ); 
					
					if ( pastore_church_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
						?>
						<div class="post_info_bottom">
							<span class="post_info_item post_info_tags"><?php echo join(' ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
						</div>
						<?php 
					}
				}
				?>
			
			</div>	<!-- /.post_content -->
		
		</article>

		<section class="related_wrap related_wrap_empty"></section>

		<?php	
		if (!$post_data['post_protected']) {
			get_template_part(pastore_church_get_file_slug('templates/_parts/comments.php'));
		}
	}
}
?>