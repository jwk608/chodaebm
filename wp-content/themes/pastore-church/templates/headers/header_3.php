<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_template_header_3_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_template_header_3_theme_setup', 1 );
	function pastore_church_template_header_3_theme_setup() {
		pastore_church_add_template(array(
			'layout' => 'header_3',
			'mode'   => 'header',
			'title'  => esc_html__('Header 3', 'pastore-church'),
			'icon'   => pastore_church_get_file_url('templates/headers/images/3.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'pastore_church_template_header_3_output' ) ) {
	function pastore_church_template_header_3_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_3 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_3 top_panel_position_<?php echo esc_attr(pastore_church_get_custom_option('top_panel_position')); ?>">
			
			<?php if (pastore_church_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						pastore_church_template_set_args('top-panel-top', array(
							'top_panel_top_components' => array('contact_info', 'login', 'bookmarks')
						));
						get_template_part(pastore_church_get_file_slug('templates/headers/_parts/top-panel-top.php'));
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php echo trim($header_css); ?>>
				<div class="content_wrap">
					<div class="contact_logo">
						<?php pastore_church_show_logo(true, true, false, false, true, false); ?>
					</div>
					<div class="menu_main_wrap">
						<nav class="menu_main_nav_area">
							<?php
							$menu_main = pastore_church_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = pastore_church_get_nav_menu();
							echo trim($menu_main);
							?>
						</nav>
						<?php
						if (pastore_church_get_custom_option('show_search')=='yes')
							echo trim(pastore_church_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed", 'title'=>"Type your search and hit enter")));
						?>
					</div>
				</div>
			</div>

			</div>
		</header>

		<?php
		pastore_church_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => true,
				 'socials' => true,
				 'bookmarks' => false,
				 'contact_address' => true,
				 'contact_phone_email' => false,
				 'woo_cart' => false,
				 'search' => true
			)
		);
	}
}
?>