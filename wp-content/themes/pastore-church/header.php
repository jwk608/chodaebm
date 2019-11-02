<?php
/**
 * The Header for our theme.
 */

// Theme init - don't remove next row! Load custom options
pastore_church_core_init_theme();

pastore_church_profiler_add_point(esc_html__('Before Theme HTML output', 'pastore-church'));

$theme_skin = pastore_church_esc(pastore_church_get_custom_option('theme_skin'));
$body_scheme = pastore_church_get_custom_option('body_scheme');
if (empty($body_scheme)  || pastore_church_is_inherit_option($body_scheme)) $body_scheme = 'original';
$body_style  = pastore_church_get_custom_option('body_style');
$top_panel_style = pastore_church_get_custom_option('top_panel_style');
$top_panel_position = pastore_church_get_custom_option('top_panel_position');
$top_panel_scheme = pastore_church_get_custom_option('top_panel_scheme');
$video_bg_show  = pastore_church_get_custom_option('show_video_bg')=='yes' && (pastore_church_get_custom_option('video_bg_youtube_code')!='' || pastore_church_get_custom_option('video_bg_url')!='');

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo 'scheme_' . esc_attr($body_scheme); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1<?php if (pastore_church_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
	<meta name="format-detection" content="telephone=no">

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php
	if ( !function_exists('has_site_icon') || !has_site_icon() ) {
		$favicon = pastore_church_get_custom_option('favicon');
		if (!$favicon) {
			if ( file_exists(pastore_church_get_file_dir('skins/'.($theme_skin).'/images/favicon.ico')) )
				$favicon = pastore_church_get_file_url('skins/'.($theme_skin).'/images/favicon.ico');
			if ( !$favicon && file_exists(pastore_church_get_file_dir('favicon.ico')) )
				$favicon = pastore_church_get_file_url('favicon.ico');
		}
		if ($favicon) {
			?><link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>" /><?php
		}
	}

	wp_head();
	?>
</head>

<body <?php body_class();?>>

	<?php 
	pastore_church_profiler_add_point(esc_html__('BODY start', 'pastore-church'));
	
	echo force_balance_tags(pastore_church_get_custom_option('gtm_code'));

	do_action( 'before' );

	// Add TOC items 'Home' and "To top"
	if (pastore_church_get_custom_option('menu_toc_home')=='yes')
		echo trim(pastore_church_sc_anchor(array(
			'id' => "toc_home",
			'title' => esc_html__('Home', 'pastore-church'),
			'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'pastore-church'),
			'icon' => "icon-home",
			'separator' => "yes",
			'url' => esc_url(home_url('/'))
			)
		)); 
	if (pastore_church_get_custom_option('menu_toc_top')=='yes')
		echo trim(pastore_church_sc_anchor(array(
			'id' => "toc_top",
			'title' => esc_html__('To Top', 'pastore-church'),
			'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'pastore-church'),
			'icon' => "icon-double-up",
			'separator' => "yes")
			)); 
	?>

	<?php
		$class = $style = '';
		if (pastore_church_get_custom_option('bg_custom')=='yes' && ($body_style=='boxed' || pastore_church_get_custom_option('bg_image_load')=='always')) {
			if (($img = pastore_church_get_custom_option('bg_image_custom')) != '')
				$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', pastore_church_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
			else if (($img = pastore_church_get_custom_option('bg_pattern_custom')) != '')
				$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
			else if (($img = pastore_church_get_custom_option('bg_image')) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = pastore_church_get_custom_option('bg_pattern')) > 0)
				$class = 'bg_pattern_'.($img);
			if (($img = pastore_church_get_custom_option('bg_color')) != '')
				$style .= 'background-color: '.($img).';';
		}
	?>

	<div class="body_wrap<?php echo !empty($class) ? ' '.esc_attr($class) : ''; ?>"<?php echo !empty($style) ? ' style="'.esc_attr($style).'"' : ''; ?>>

		<?php
		if ($video_bg_show) {
			$youtube = pastore_church_get_custom_option('video_bg_youtube_code');
			$video   = pastore_church_get_custom_option('video_bg_url');
			$overlay = pastore_church_get_custom_option('video_bg_overlay')=='yes';
			if (!empty($youtube)) {
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
				<?php
			} else if (!empty($video)) {
				$info = pathinfo($video);
				$ext = !empty($info['extension']) ? $info['extension'] : 'src';
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
				<?php
			}
		}
		?>

		<div class="page_wrap">

			<?php
			pastore_church_profiler_add_point(esc_html__('Before Page Header', 'pastore-church'));
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				pastore_church_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				// Mobile Menu
				get_template_part(pastore_church_get_file_slug('templates/headers/_parts/header-mobile.php'));

				pastore_church_profiler_add_point(esc_html__('After show menu', 'pastore-church'));
			}

			// Slider
			get_template_part(pastore_church_get_file_slug('templates/headers/_parts/slider.php'));
			
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				pastore_church_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				// Mobile Menu
				get_template_part(pastore_church_get_file_slug('templates/headers/_parts/header-mobile.php'));

				pastore_church_profiler_add_point(esc_html__('After show menu', 'pastore-church'));
			}

			
			
			// Top of page section: page title and breadcrumbs
			$show_title = pastore_church_get_custom_option('show_page_title')=='yes';
			$show_navi = $show_title && is_single() && pastore_church_is_woocommerce_page();
			$show_breadcrumbs = pastore_church_get_custom_option('show_breadcrumbs')=='yes';
			if ($show_title || $show_breadcrumbs) {

				// Get custom image (for blog) or featured image (for single)
				$header_css = '';
				if(pastore_church_get_custom_option('show_top_panel_image')=='yes') {
					$header_image ='';
					$header_image_attachment = false;
					if ($header_image_attachment && is_singular()) {
						$post_id = get_the_ID();
						$post_format = get_post_format();
						$header_image = wp_get_attachment_url(get_post_thumbnail_id($post_id));
					}
					if (empty($header_image))
						$header_image = pastore_church_get_custom_option('top_panel_image');
					if (empty($header_image))
						$header_image = get_header_image();
					if (!empty($header_image)) {
						$header_css = ' style="background-image: url(' . esc_url($header_image) . ')"';
					}
				}
				?>
				<div class="top_panel_title <?php echo (!empty($show_title) ? ' title_present'.  ($show_navi ? ' navi_present' : '') : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present' : ''); ?> scheme_<?php echo esc_attr($top_panel_scheme); ?>">
					<div class="top_panel_title_inner top_panel_inner_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present_inner' : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present_inner' : ''); ?>" <?php echo trim($header_css); ?>>
						<div class="content_wrap">
							<?php
							if ($show_title) {
								if ($show_navi) {
									?><div class="post_navi"><?php 
										previous_post_link( '<span class="post_navi_item post_navi_prev">%link</span>', '%title', true, '', 'product_cat' );
										next_post_link( '<span class="post_navi_item post_navi_next">%link</span>', '%title', true, '', 'product_cat' );
									?></div><?php
								} else {
									?><h1 class="page_title"><?php echo strip_tags(pastore_church_get_blog_title()); ?></h1><?php
								}
							}
							if ($show_breadcrumbs) {
								?><div class="breadcrumbs"><?php if (!is_404()) pastore_church_show_breadcrumbs(); ?></div><?php
							}
							?>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<div class="page_content_wrap page_paddings_<?php echo esc_attr(pastore_church_get_custom_option('body_paddings')); ?>">

				<?php
				pastore_church_profiler_add_point(esc_html__('Before Page content', 'pastore-church'));
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') pastore_church_open_wrapper('<div class="content_wrap">');
				
				// Main content wrapper
				pastore_church_open_wrapper('<div class="content">');

				?>