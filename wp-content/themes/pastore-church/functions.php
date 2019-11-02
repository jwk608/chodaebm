<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'pastore_church_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_theme_setup', 1 );
	function pastore_church_theme_setup() {

		// Register theme menus
		add_filter( 'pastore_church_filter_add_theme_menus',		'pastore_church_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'pastore_church_filter_add_theme_sidebars',	'pastore_church_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'pastore_church_filter_importer_options',		'pastore_church_set_importer_options' );

		// Add theme required plugins
		add_filter( 'pastore_church_filter_required_plugins',		'pastore_church_add_required_plugins' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 'pastore_church_body_classes' );

		// Set list of the theme required plugins
		pastore_church_storage_set('required_plugins', array(
			'essgrids',
			'revslider',
			'tribe_events',
			'trx_donations',
			'trx_utils',
			'visual_composer',
			'html5_jquery_audio_player',
			'content_timeline'
			)
		);
		
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'pastore_church_add_theme_menus' ) ) {
	//add_filter( 'pastore_church_filter_add_theme_menus', 'pastore_church_add_theme_menus' );
	function pastore_church_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'pastore-church');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'pastore_church_add_theme_sidebars' ) ) {
	//add_filter( 'pastore_church_filter_add_theme_sidebars',	'pastore_church_add_theme_sidebars' );
	function pastore_church_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'pastore-church' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'pastore-church' )
			);
			if (function_exists('pastore_church_exists_woocommerce') && pastore_church_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'pastore-church' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'pastore_church_add_required_plugins' ) ) {
	//add_filter( 'pastore_church_filter_required_plugins',		'pastore_church_add_required_plugins' );
	function pastore_church_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('Pastore Church Utilities','pastore-church'),
			'version'	=> '2.7',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> pastore_church_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}


// Add theme specified classes into the body
if ( !function_exists('pastore_church_body_classes') ) {
	//add_filter( 'body_class', 'pastore_church_body_classes' );
	function pastore_church_body_classes( $classes ) {

		$classes[] = 'pastore_church_body';
		$classes[] = 'body_style_' . trim(pastore_church_get_custom_option('body_style'));
		$classes[] = 'body_' . (pastore_church_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(pastore_church_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(pastore_church_get_custom_option('article_style'));
		
		$blog_style = pastore_church_get_custom_option(is_singular() && !pastore_church_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(pastore_church_get_template_name($blog_style));
		
		$body_scheme = pastore_church_get_custom_option('body_scheme');
		if (empty($body_scheme)  || pastore_church_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = pastore_church_get_custom_option('top_panel_position');
		if (!pastore_church_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = pastore_church_get_sidebar_class();

		if (pastore_church_get_custom_option('show_video_bg')=='yes' && (pastore_church_get_custom_option('video_bg_youtube_code')!='' || pastore_church_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		return $classes;
	}
}


// Set theme specific importer options
if ( !function_exists( 'pastore_church_set_importer_options' ) ) {
	//add_filter( 'pastore_church_filter_importer_options',	'pastore_church_set_importer_options' );
	function pastore_church_set_importer_options($options=array()) {
		if (is_array($options)) {
			$options['debug'] = pastore_church_get_theme_option('debug_mode')=='yes';
			$options['menus'] = array(
				'menu-main'	  => esc_html__('Main menu', 'pastore-church')
			);
			// Prepare demo data
			$demo_data_url = 'http://pastorechurch.themerex.net/demo/';
			// Main demo
			$options['files']['default'] = array(
				'title'				=> esc_html__('Basekit demo', 'pastore-church'),
				'file_with_posts'	=> esc_url($demo_data_url) . 'default/posts.txt',
				'file_with_users'	=> esc_url($demo_data_url) . 'default/users.txt',
				'file_with_mods'	=> esc_url($demo_data_url) . 'default/theme_mods.txt',
				'file_with_options'	=> esc_url($demo_data_url) . 'default/theme_options.txt',
				'file_with_templates'=>esc_url($demo_data_url) . 'default/templates_options.txt',
				'file_with_widgets'	=> esc_url($demo_data_url) . 'default/widgets.txt',
				'file_with_revsliders' => array(
					esc_url($demo_data_url) . 'default/revsliders/home.zip',
					esc_url($demo_data_url) . 'default/revsliders/home-main.zip'
				),
				'file_with_attachments' => array(),
				'attachments_by_parts'	=> true,
				'domain_dev'	=> esc_url('http://pastorechurch.my'),	// Developers domain
				'domain_demo'	=> esc_url('http://pastorechurch.themerex.net')	// Demo-site domain
			);
			for ($i=1; $i<=12; $i++) {
				$options['files']['default']['file_with_attachments'][] = esc_url($demo_data_url) . 'default/uploads/uploads.' . sprintf('%03u', $i);
			}
		}
		return $options;
	}
}


/* Include framework core files
------------------------------------------------------------------- */
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>