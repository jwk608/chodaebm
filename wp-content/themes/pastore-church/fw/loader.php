<?php
/**
 * Pastore Church Framework
 *
 * @package pastore_church
 * @since pastore_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'PASTORE_CHURCH_FW_DIR' ) )			define( 'PASTORE_CHURCH_FW_DIR', 'fw' );

// Include theme variables storage
require_once trailingslashit( get_template_directory() ) . PASTORE_CHURCH_FW_DIR . '/core/core.storage.php';


// Theme variables storage
//$theme_slug = str_replace(' ', '_', trim(strtolower(get_stylesheet())));
//pastore_church_storage_set('options_prefix', 'pastore_church'.'_'.trim($theme_slug));	// Used as prefix to store theme's options in the post meta and wp options
pastore_church_storage_set('options_prefix', 'pastore_church');	// Used as prefix to store theme's options in the post meta and wp options
pastore_church_storage_set('page_template', '');			// Storage for current page template name (used in the inheritance system)
pastore_church_storage_set('widgets_args', array(			// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget_title">',
		'after_title'   => '</h5>',
	)
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'pastore_church_loader_theme_setup', 20 );
	function pastore_church_loader_theme_setup() {

		pastore_church_profiler_add_point(esc_html__('After load theme required files', 'pastore-church'));

		// Before init theme
		do_action('pastore_church_action_before_init_theme');

		// Load current values for main theme options
		pastore_church_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			pastore_church_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */
// Manual load important libraries before load all rest files
// core.strings must be first - we use pastore_church_str...() in the pastore_church_get_file_dir()
require_once trailingslashit( get_template_directory() ) . PASTORE_CHURCH_FW_DIR . '/core/core.strings.php';
// core.files must be first - we use pastore_church_get_file_dir() to include all rest parts
require_once trailingslashit( get_template_directory() ) . PASTORE_CHURCH_FW_DIR . '/core/core.files.php';

// Include debug and profiler
require_once trailingslashit( get_template_directory() ) . PASTORE_CHURCH_FW_DIR . '/core/core.debug.php';
// Include custom theme files
pastore_church_autoload_folder( 'includes' );

// Include core files
pastore_church_autoload_folder( 'core' );

// Include theme-specific plugins and post types
pastore_church_autoload_folder( 'plugins' );

// Include theme templates
pastore_church_autoload_folder( 'templates' );

// Include theme widgets
pastore_church_autoload_folder( 'widgets' );
?>