<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
	require_once trailingslashit( get_template_directory() ) . PASTORE_CHURCH_FW_DIR . '/core/core.shortcodes/shortcodes_vc_classes.php';
}

// Width and height params
if ( !function_exists( 'pastore_church_vc_width' ) ) {
	function pastore_church_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", 'pastore-church'),
			"description" => wp_kses_data( __("Width of the element", 'pastore-church') ),
			"group" => esc_html__('Size &amp; Margins', 'pastore-church'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'pastore_church_vc_height' ) ) {
	function pastore_church_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", 'pastore-church'),
			"description" => wp_kses_data( __("Height of the element", 'pastore-church') ),
			"group" => esc_html__('Size &amp; Margins', 'pastore-church'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'pastore_church_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'pastore_church_shortcodes_vc_scripts_admin' );
	function pastore_church_shortcodes_vc_scripts_admin() {
		// Include CSS 
		pastore_church_enqueue_style ( 'shortcodes_vc_admin-style', pastore_church_get_file_url('shortcodes/theme.shortcodes_vc_admin.css'), array(), null );
		// Include JS
		pastore_church_enqueue_script( 'shortcodes_vc_admin-script', pastore_church_get_file_url('core/core.shortcodes/shortcodes_vc_admin.js'), array('jquery'), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'pastore_church_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'pastore_church_shortcodes_vc_scripts_front' );
	function pastore_church_shortcodes_vc_scripts_front() {
		if (pastore_church_vc_is_frontend()) {
			// Include CSS 
			pastore_church_enqueue_style ( 'shortcodes_vc_front-style', pastore_church_get_file_url('shortcodes/theme.shortcodes_vc_front.css'), array(), null );
			// Include JS
			pastore_church_enqueue_script( 'shortcodes_vc_front-script', pastore_church_get_file_url('core/core.shortcodes/shortcodes_vc_front.js'), array('jquery'), null, true );
			pastore_church_enqueue_script( 'shortcodes_vc_theme-script', pastore_church_get_file_url('shortcodes/theme.shortcodes_vc_front.js'), array('jquery'), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'pastore_church_shortcodes_vc_add_init_script' ) ) {
	//add_filter('pastore_church_shortcode_output', 'pastore_church_shortcodes_vc_add_init_script', 10, 4);
	function pastore_church_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (pastore_church_strpos($output, 'pastore_church_vc_init_shortcodes')===false) {
				$id = "pastore_church_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							pastore_church_init_post_formats();
							pastore_church_init_shortcodes(jQuery("body").eq(0));
							pastore_church_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}

// Return vc_param value
if ( !function_exists( 'pastore_church_get_vc_param' ) ) {
	function pastore_church_get_vc_param($prm) {
		return pastore_church_storage_get_array('vc_params', $prm);
	}
}

// Set vc_param value
if ( !function_exists( 'pastore_church_set_vc_param' ) ) {
	function pastore_church_set_vc_param($prm, $val) {
		pastore_church_storage_set_array('vc_params', $prm, $val);
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_shortcodes_vc_theme_setup' ) ) {
	//if ( pastore_church_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'pastore_church_action_before_init_theme', 'pastore_church_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'pastore_church_action_after_init_theme', 'pastore_church_shortcodes_vc_theme_setup' );
	function pastore_church_shortcodes_vc_theme_setup() {


		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( pastore_church_get_folder_dir('shortcodes/vc' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'pastore-church'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'pastore-church') ),
					"group" => esc_html__('Color scheme', 'pastore-church'),
					"class" => "",
					"value" => array_flip(pastore_church_get_list_color_schemes(true)),
					"type" => "dropdown"
		));
		vc_add_param("vc_row", array(
					"param_name" => "inverse",
					"heading" => esc_html__("Inverse colors", 'pastore-church'),
					"description" => wp_kses_data( __("Inverse all colors of this block", 'pastore-church') ),
					"group" => esc_html__('Color scheme', 'pastore-church'),
					"class" => "",
					"std" => "no",
					"value" => array(esc_html__('Inverse colors', 'pastore-church') => 'yes'),
					"type" => "checkbox"
		));

		if (pastore_church_shortcodes_is_used() && class_exists('PASTORE_CHURCH_VC_ShortCodeSingle')) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'pastore_church_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'pastore_church_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('pastore_church_shortcode_output', 'pastore_church_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			//vc_remove_element("vc_button");
			//vc_remove_element("vc_posts_slider");
			//vc_remove_element("vc_gmaps");
			//vc_remove_element("vc_teaser_grid");
			//vc_remove_element("vc_progress_bar");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_tweetmeme");
//			vc_remove_element("vc_googleplus");
//			vc_remove_element("vc_facebook");
//			vc_remove_element("vc_pinterest");
			//vc_remove_element("vc_message");
			//vc_remove_element("vc_posts_grid");
//			vc_remove_element("vc_carousel");
//			vc_remove_element("vc_flickr");
			//vc_remove_element("vc_tour");
//			vc_remove_element("vc_separator");
//			vc_remove_element("vc_single_image");
			//vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			//vc_remove_element("vc_toggle");
			//vc_remove_element("vc_tabs");
			//vc_remove_element("vc_tab");
//			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			
			pastore_church_storage_set('vc_params', array(
				
				// Common arrays and strings
				'category' => esc_html__("ThemeREX shortcodes", 'pastore-church'),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", 'pastore-church'),
					"description" => wp_kses_data( __("ID for the element", 'pastore-church') ),
					"group" => esc_html__('ID &amp; Class', 'pastore-church'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", 'pastore-church'),
					"description" => wp_kses_data( __("CSS class for the element", 'pastore-church') ),
					"group" => esc_html__('ID &amp; Class', 'pastore-church'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", 'pastore-church'),
					"description" => wp_kses_data( __("Select animation while object enter in the visible area of page", 'pastore-church') ),
					"group" => esc_html__('ID &amp; Class', 'pastore-church'),
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('animations')),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", 'pastore-church'),
					"description" => wp_kses_data( __("Any additional CSS rules (if need)", 'pastore-church') ),
					"group" => esc_html__('ID &amp; Class', 'pastore-church'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", 'pastore-church'),
					"description" => wp_kses_data( __("Margin above this shortcode", 'pastore-church') ),
					"group" => esc_html__('Size &amp; Margins', 'pastore-church'),
					"std" => "inherit",
					"value" => array_flip(pastore_church_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", 'pastore-church'),
					"description" => wp_kses_data( __("Margin below this shortcode", 'pastore-church') ),
					"group" => esc_html__('Size &amp; Margins', 'pastore-church'),
					"std" => "inherit",
					"value" => array_flip(pastore_church_get_sc_param('margins')),
					"type" => "dropdown"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", 'pastore-church'),
					"description" => wp_kses_data( __("Margin on the left side of this shortcode", 'pastore-church') ),
					"group" => esc_html__('Size &amp; Margins', 'pastore-church'),
					"std" => "inherit",
					"value" => array_flip(pastore_church_get_sc_param('margins')),
					"type" => "dropdown"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", 'pastore-church'),
					"description" => wp_kses_data( __("Margin on the right side of this shortcode", 'pastore-church') ),
					"group" => esc_html__('Size &amp; Margins', 'pastore-church'),
					"std" => "inherit",
					"value" => array_flip(pastore_church_get_sc_param('margins')),
					"type" => "dropdown"
				)
			) );
			
			// Add theme-specific shortcodes
			do_action('pastore_church_action_shortcodes_list_vc');

		}
	}
}
?>