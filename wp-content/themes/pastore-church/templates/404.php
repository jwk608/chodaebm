<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_template_404_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_template_404_theme_setup', 1 );
	function pastore_church_template_404_theme_setup() {
		pastore_church_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			)
		));
	}
}

// Template output
if ( !function_exists( 'pastore_church_template_404_output' ) ) {
	function pastore_church_template_404_output() {
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<div class="page_icon_404 icon-computer"></div>
				<h1 class="page_title"><?php esc_html_e( 'We are sorry! ', 'pastore-church' ); ?><span><?php esc_html_e( 'Error 404!', 'pastore-church' ); ?></span></h1>
				<h2 class="page_subtitle"><?php esc_html_e('This page could not be found.', 'pastore-church'); ?></h2>
				<p class="page_description"><?php echo wp_kses_data( sprintf( __('Can\'t find what you need? Take a moment and do a search below or start from <a href="%s">our homepage</a>.', 'pastore-church'), esc_url(home_url('/')) ) ); ?></p>
				<div class="page_search"><?php echo trim(pastore_church_sc_search(array('state'=>'fixed', 'title'=>__('To search type and hit enter', 'pastore-church')))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>