<?php

/* Theme setup section
-------------------------------------------------------------------- */

// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings

pastore_church_storage_set('settings', array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																// less  - slow, but support all features
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'no',								// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'add_image_size'	=> false,								// Add theme's thumb sizes into WP list sizes. 
																// If false - new image thumb will be generated on demand,
																// otherwise - all thumb sizes will be generated when image is loaded

	'use_list_cache'	=> true,								// Use cache for any lists (increase theme speed, but get 15-20K memory)
	'use_post_cache'	=> true,								// Use cache for post_data (increase theme speed, decrease queries number, but get more memory - up to 300K)

	'allow_profiler'	=> true,								// Allow to show theme profiler when 'debug mode' is on

	'admin_dummy_style' => 2									// 1 | 2 - Progress bar style when import dummy data
	)
);



// Default Theme Options
if ( !function_exists( 'pastore_church_options_settings_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_options_settings_theme_setup', 2 );	// Priority 1 for add pastore_church_filter handlers
	function pastore_church_options_settings_theme_setup() {
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'pastore_church_options_reset');

		// Settings 
		$socials_type = pastore_church_get_theme_setting('socials_type');
				
		// Prepare arrays 
		pastore_church_storage_set('options_params', apply_filters('pastore_church_filter_theme_options_params', array(
			'list_fonts'				=> array('$pastore_church_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$pastore_church_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$pastore_church_get_list_socials' => ''),
			'list_icons' 				=> array('$pastore_church_get_list_icons' => ''),
			'list_posts_types' 			=> array('$pastore_church_get_list_posts_types' => ''),
			'list_categories' 			=> array('$pastore_church_get_list_categories' => ''),
			'list_menus'				=> array('$pastore_church_get_list_menus' => ''),
			'list_sidebars'				=> array('$pastore_church_get_list_sidebars' => ''),
			'list_positions' 			=> array('$pastore_church_get_list_sidebars_positions' => ''),
			'list_skins'				=> array('$pastore_church_get_list_skins' => ''),
			'list_color_schemes'		=> array('$pastore_church_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$pastore_church_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$pastore_church_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$pastore_church_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$pastore_church_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$pastore_church_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$pastore_church_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$pastore_church_get_list_blog_counters' => ''),
			'list_animations_in' 		=> array('$pastore_church_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$pastore_church_get_list_animations_out' => ''),
			'list_filters'				=> array('$pastore_church_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$pastore_church_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$pastore_church_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$pastore_church_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$pastore_church_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$pastore_church_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$pastore_church_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$pastore_church_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$pastore_church_get_list_yesno' => ''),
			'list_on_off' 				=> array('$pastore_church_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$pastore_church_get_list_showhide' => ''),
			'list_sorting' 				=> array('$pastore_church_get_list_sortings' => ''),
			'list_ordering' 			=> array('$pastore_church_get_list_orderings' => ''),
			'list_locations' 			=> array('$pastore_church_get_list_dedicated_locations' => '')
			)
		));


		// Theme options array
		pastore_church_storage_set('options', array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'pastore-church'),
					"start" => "partitions",
					"override" => "category,services_group,post,page,custom",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'pastore-church'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'pastore-church'),
					"desc" => wp_kses_data( __('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'body_style' => array(
					"title" => esc_html__('Body style', 'pastore-church'),
					"desc" => wp_kses_data( __('Select body style:', 'pastore-church') )
								. ' <br>' 
								. wp_kses_data( __('<b>boxed</b> - if you want use background color and/or image', 'pastore-church') )
								. ',<br>'
								. wp_kses_data( __('<b>wide</b> - page fill whole window with centered content', 'pastore-church') )
								. (pastore_church_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses_data( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'pastore-church') )
									: '')
								. (pastore_church_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses_data( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'pastore-church') )
									: ''),
					"info" => true,
					"override" => "category,services_group,post,page,custom",
					"std" => "wide",
					"options" => pastore_church_get_options_param('list_body_styles'),
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'pastore-church'),
					"desc" => wp_kses_data( __('Add paddings above and below the page content', 'pastore-church') ),
					"override" => "post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'pastore-church'),
					"desc" => wp_kses_data( __('Select skin for the theme decoration', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no_less",
					"options" => pastore_church_get_options_param('list_skins'),
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the entire page', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'pastore-church'),
					"desc" => wp_kses_data( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'pastore-church'),
					"desc" => wp_kses_data( __('Color and image for the site background', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'pastore-church'),
					"desc" => wp_kses_data( __("Use custom color and/or image as the site background", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'pastore-church'),
					"desc" => wp_kses_data( __('Body background color',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'pastore-church'),
					"desc" => wp_kses_data( __('Select theme background pattern (first case - without pattern)',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => pastore_church_get_file_url('images/spacer.png'),
						1 => pastore_church_get_file_url('images/bg/pattern_1.jpg'),
						2 => pastore_church_get_file_url('images/bg/pattern_2.jpg'),
						3 => pastore_church_get_file_url('images/bg/pattern_3.jpg'),
						4 => pastore_church_get_file_url('images/bg/pattern_4.jpg'),
						5 => pastore_church_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'pastore-church'),
					"desc" => wp_kses_data( __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'pastore-church'),
					"desc" => wp_kses_data( __('Select theme background image (first case - without image)',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => pastore_church_get_file_url('images/spacer.png'),
						1 => pastore_church_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => pastore_church_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => pastore_church_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'pastore-church'),
					"desc" => wp_kses_data( __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'pastore-church'),
					"desc" => wp_kses_data( __('Select custom image position',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'pastore-church'),
					"desc" => wp_kses_data( __('Always load background images or only for boxed body style', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'pastore-church'),
						'always' => esc_html__('Always', 'pastore-church')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'pastore-church'),
					"desc" => wp_kses_data( __('Parameters of the video, used as site background', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'pastore-church'),
					"desc" => wp_kses_data( __("Show video as the site background", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'pastore-church'),
					"desc" => wp_kses_data( __("Youtube code of video", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'pastore-church'),
					"desc" => wp_kses_data( __("URL to video-file (uploaded on your site)", 'pastore-church') ),
					"readonly" =>false,
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'pastore-church'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'pastore-church'),
															'update' => esc_html__( 'Select Video', 'pastore-church')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'pastore-church'),
					"desc" => wp_kses_data( __('Use overlay texture for the video background', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'pastore-church'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'pastore-church'),
					"desc" => wp_kses_data( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'pastore-church'),
					"desc" => wp_kses_data( __('Select desired style of the page header', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "header_3",
					"options" => pastore_church_get_options_param('list_header_styles'),
					"style" => "list",
					"type" => "images"),

		"top_panel_position" => array(
					"title" => esc_html__('Top panel position', 'pastore-church'),
					"desc" => wp_kses_data( __('Select position for the top panel with logo and main menu', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'pastore-church'),
						'above' => esc_html__('Above slider', 'pastore-church'),
						'below' => esc_html__('Below slider', 'pastore-church'),
						'over'  => esc_html__('Over slider', 'pastore-church')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the top panel', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"pushy_panel_scheme" => array(
					"title" => esc_html__('Push panel color scheme', 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the push panel (with logo, menu and socials)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'top_panel_style' => array('header_8')
					),
					"std" => "dark",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),



		"show_top_panel_image" => array(
				"title" => esc_html__('Show top panel image', 'pastore-church'),
				"desc" => wp_kses_data( __('Show post/page/category panel image', 'pastore-church') ),
				"override" => "category,services_group,post,page,custom",
				"std" => "no",
				"options" => pastore_church_get_options_param('list_yes_no'),
				"type" => "switch"),

		"top_panel_image" => array(
				"title" => esc_html__('Top panel image', 'pastore-church'),
				"desc" => wp_kses_data( __('Select default background image of the page top panel', 'pastore-church') ),
				"override" => "category,services_group,post,page,custom",
				"dependency" => array(
					'show_top_panel_image' => array('yes')
				),
				"std" => "",
				"type" => "media"),
			
			
			
			
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'pastore-church'),
					"desc" => wp_kses_data( __('Show post/page/category title', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'pastore-church'),
					"desc" => wp_kses_data( __('Show path to current category (post, page)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'pastore-church'),
					"desc" => wp_kses_data( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'pastore-church') ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'pastore-church'),
					"desc" => wp_kses_data( __('Select the Main menu style and position', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'pastore-church'),
					"desc" => wp_kses_data( __('Select main menu for the current page',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"options" => pastore_church_get_options_param('list_menus'),
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'pastore-church'),
					"desc" => wp_kses_data( __('Attach main menu to top of window then page scroll down', 'pastore-church') ),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'pastore-church'), 
						"none"=>esc_html__("Don't fix menu position", 'pastore-church')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => esc_html__('Main menu slider', 'pastore-church'),
					"desc" => wp_kses_data( __('Use slider background for main menu items', 'pastore-church') ),
					"std" => "yes",
					"type" => "switch",
					"options" => pastore_church_get_options_param('list_yes_no')),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'pastore-church'),
					"desc" => wp_kses_data( __('Select animation to show submenu ', 'pastore-church') ),
					"std" => "fadeIn",
					"type" => "select",
					"options" => pastore_church_get_options_param('list_animations_in')),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'pastore-church'),
					"desc" => wp_kses_data( __('Select animation to hide submenu ', 'pastore-church') ),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => pastore_church_get_options_param('list_animations_out')),
		
		"menu_mobile" => array( 
					"title" => esc_html__('Main menu responsive', 'pastore-church'),
					"desc" => wp_kses_data( __('Allow responsive version for the main menu if window width less then this value', 'pastore-church') ),
					"std" => 1024,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'pastore-church'),
					"desc" => wp_kses_data( __('Width for dropdown menus in main menu', 'pastore-church') ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'pastore-church'),
					"desc" => wp_kses_data( __("Select parts for the user's menu area", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'pastore-church'),
					"desc" => wp_kses_data( __('Show user menu area on top of page', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'pastore-church'),
					"desc" => wp_kses_data( __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'pastore-church') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login/Logout buttons', 'pastore-church'),
					"desc" => wp_kses_data( __('Show Login and Logout buttons in the user menu area', 'pastore-church') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => esc_html__('Show bookmarks', 'pastore-church'),
					"desc" => wp_kses_data( __('Show bookmarks selector in the user menu', 'pastore-church') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),


		"show_extra_button" => array(
				"title" => esc_html__('Show additional button', 'pastore-church'),
				"desc" => wp_kses_data( __('Show additional button in the user menu area', 'pastore-church') ),
				"override" => "category,services_group,post,page,custom",
				"dependency" => array(
					'show_top_panel_top' => array('yes')
				),
				"std" => "no",
				"options" => pastore_church_get_options_param('list_yes_no'),
				"type" => "switch"),

		'extra_button_text' => array(
				"title" => esc_html__('Additional button text', 'pastore-church'),
				"desc" => wp_kses_data( __('Additional button text (display right)', 'pastore-church') ),
				"override" => "category,services_group,post,page,custom",
				"dependency" => array(
					'show_extra_button' => array('yes')
				),
				"std" => '',
				"type" => "text"
				),

		'extra_button_link' => array(
				"title" => esc_html__('Additional button link', 'pastore-church'),
				"desc" => wp_kses_data( __('Additional button link', 'pastore-church') ),
				"override" => "category,services_group,post,page,custom",
				"dependency" => array(
					'show_extra_button' => array('yes')
				),
				"std" => '',
				"type" => "text"
			),





		"info_header_4" => array( 
					"title" => esc_html__("Table of Contents (TOC)", 'pastore-church'),
					"desc" => wp_kses_data( __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => esc_html__('TOC position', 'pastore-church'),
					"desc" => wp_kses_data( __('Show TOC for the current page', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "float",
					"options" => array(
						'hide'  => esc_html__('Hide', 'pastore-church'),
						'fixed' => esc_html__('Fixed', 'pastore-church'),
						'float' => esc_html__('Float', 'pastore-church')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => esc_html__('Add "Home" into TOC', 'pastore-church'),
					"desc" => wp_kses_data( __('Automatically add "Home" item into table of contents - return to home page of the site', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => esc_html__('Add "To Top" into TOC', 'pastore-church'),
					"desc" => wp_kses_data( __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'pastore-church'),
					"desc" => wp_kses_data( __("Select or upload logos for the site's header and select it position", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'favicon' => array(
					"title" => esc_html__('Favicon', 'pastore-church'),
					"desc" => wp_kses_data( __("Upload a 16px x 16px image that will represent your website's favicon.<br /><em>To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (<a href='http://www.favicon.cc/'>www.favicon.cc</a>)</em>", 'pastore-church') ),
					"std" => "",
					"type" => "media"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'pastore-church'),
					"desc" => wp_kses_data( __('Main logo image', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_retina' => array(
					"title" => esc_html__('Logo image for Retina', 'pastore-church'),
					"desc" => wp_kses_data( __('Main logo image used on Retina display', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'pastore-church'),
					"desc" => wp_kses_data( __('Logo image for the header (if menu is fixed after the page is scrolled)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'pastore-church'),
					"desc" => wp_kses_data( __('Logo text - display it after logo image', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'pastore-church'),
					"desc" => wp_kses_data( __('Height for the logo in the header area', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'pastore-church'),
					"desc" => wp_kses_data( __('Top offset for the logo in the header area', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'pastore-church'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'pastore-church'),
					"desc" => wp_kses_data( __('Select parameters for main slider (you can override it in each category and page)', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'pastore-church'),
					"desc" => wp_kses_data( __('Do you want to show slider on each page (post)', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'pastore-church'),
					"desc" => wp_kses_data( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'pastore-church'),
						"fullwide"=>esc_html__("Fullwide", 'pastore-church'),
						"fullscreen"=>esc_html__("Fullscreen", 'pastore-church')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'pastore-church'),
					"desc" => wp_kses_data( __("Slider height (in pixels) - only if slider display with fixed height.", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'pastore-church'),
					"desc" => wp_kses_data( __('What engine use to show slider?', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "swiper",
					"options" => pastore_church_get_options_param('list_sliders'),
					"type" => "radio"),

		"slider_over_content" => array(
					"title" => esc_html__('Put content over slider',  'pastore-church'),
					"desc" => wp_kses_data( __('Put content below on fixed layer over this slider',  'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "editor"),

		"slider_over_scheme" => array(
					"title" => esc_html__('Color scheme for content above', 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the content over the slider', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "dark",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'pastore-church'),
					"desc" => wp_kses_data( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => pastore_church_array_merge(array(0 => esc_html__('- Select category -', 'pastore-church')), pastore_church_get_options_param('list_categories')),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'pastore-church'),
					"desc" => wp_kses_data( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'pastore-church'),
					"desc" => wp_kses_data( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => pastore_church_get_options_param('list_sorting'),
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'pastore-church'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => pastore_church_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'pastore-church'),
					"desc" => wp_kses_data( __("Interval (in ms) for slides change in slider", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'pastore-church'),
					"desc" => wp_kses_data( __("Choose pagination style for the slider", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'pastore-church'),
						'yes'  => esc_html__('Dots', 'pastore-church'), 
						'over' => esc_html__('Titles', 'pastore-church')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'pastore-church'),
					"desc" => wp_kses_data( __("Do you want to show post's title, reviews rating and description on slides in slider", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'pastore-church'),
						'slide' => esc_html__('Slide', 'pastore-church'), 
						'fixed' => esc_html__('Fixed', 'pastore-church')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'pastore-church'),
					"desc" => wp_kses_data( __("Do you want to show post's category on slides in slider", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'pastore-church'),
					"desc" => wp_kses_data( __("Do you want to show post's reviews rating on slides in slider", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'pastore-church'),
					"desc" => wp_kses_data( __("How many characters show in the post's description in slider. 0 - no descriptions", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'pastore-church'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'pastore-church'),
					"desc" => wp_kses_data( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'pastore-church') ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'pastore-church'),
					"desc" => wp_kses_data( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'pastore-church') ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'pastore-church'),
					"desc" => wp_kses_data( __('Show / Hide and select main sidebar', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'pastore-church'),
					"desc" => wp_kses_data( __('Select position for the main sidebar or hide it',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "right",
					"options" => pastore_church_get_options_param('list_positions'),
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the main sidebar', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'pastore-church'),
					"desc" => wp_kses_data( __('Select main sidebar content',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => pastore_church_get_options_param('list_sidebars'),
					"type" => "select"),
		

		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'pastore-church'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'pastore-church'),
					"desc" => wp_kses_data( __("Select components of the footer, set style and put the content for the user's footer area", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_footer_area" => array(
				"title" => esc_html__('Show footer area', 'pastore-church'),
				"desc" => wp_kses_data( __('Footer area', 'pastore-church') ),
				"override" => "category,services_group,post,page,custom",
				"std" => "no",
				"options" => pastore_church_get_options_param('list_yes_no'),
				"type" => "switch"),

		"footer_area" => array(
				"title" => esc_html__('Footer area',  'pastore-church'),
				"desc" => wp_kses_data( __("Content footer area", 'pastore-church') ),
				"override" => "category,services_group,post,page,custom",
				"dependency" => array(
					'show_footer_area' => array('yes')
				),
				"allow_html" => true,
				"std" => "",
				"type" => "editor"),

		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'pastore-church'),
					"desc" => wp_kses_data( __('Select style for the footer sidebar or hide it', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the footer', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'pastore-church'),
					"desc" => wp_kses_data( __('Select footer sidebar for the blog page',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => pastore_church_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'pastore-church'),
					"desc" => wp_kses_data( __('Select columns number for the footer sidebar',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_2" => array(
					"title" => esc_html__('Testimonials in Footer', 'pastore-church'),
					"desc" => wp_kses_data( __('Select parameters for Testimonials in the Footer', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'pastore-church'),
					"desc" => wp_kses_data( __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => esc_html__("Color scheme", 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the testimonials area', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'pastore-church'),
					"desc" => wp_kses_data( __('Number testimonials to show', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_3" => array(
					"title" => esc_html__('Twitter in Footer', 'pastore-church'),
					"desc" => wp_kses_data( __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'pastore-church'),
					"desc" => wp_kses_data( __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => esc_html__("Color scheme", 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the twitter area', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'pastore-church'),
					"desc" => wp_kses_data( __('Number twitter to show', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => esc_html__('Google map parameters', 'pastore-church'),
					"desc" => wp_kses_data( __('Select parameters for Google map (you can override it in each category and page)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'pastore-church'),
					"desc" => wp_kses_data( __('Do you want to show Google map on each page (post)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'pastore-church'),
					"desc" => wp_kses_data( __("Map height (default - in pixels, allows any CSS units of measure)", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'pastore-church'),
					"desc" => wp_kses_data( __("Enter address to show on map center", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longitude to show on map',  'pastore-church'),
					"desc" => wp_kses_data( __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'pastore-church'),
					"desc" => wp_kses_data( __("Enter title to show on map center", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'pastore-church'),
					"desc" => wp_kses_data( __("Enter description to show on map center", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"allow_html" => true,
					"std" => "",
					"type" => "textarea"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'pastore-church'),
					"desc" => wp_kses_data( __("Enter desired initial zoom for Google map", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'pastore-church'),
					"desc" => wp_kses_data( __("Select style to show Google map", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'dark',
					"options" => pastore_church_get_options_param('list_gmap_styles'),
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'pastore-church'),
					"desc" => wp_kses_data( __("Select or upload png-image with Google map marker", 'pastore-church') ),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'pastore-church'),
					"desc" => wp_kses_data( __("Show/Hide contacts area in the footer", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'pastore-church'),
					"desc" => wp_kses_data( __('Show contact information area in footer: site logo, contact info and large social icons', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the contacts area', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'pastore-church'),
					"desc" => wp_kses_data( __('Logo image in the footer (in the contacts area)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),

		'logo_footer_retina' => array(
					"title" => esc_html__('Logo image for footer for Retina', 'pastore-church'),
					"desc" => wp_kses_data( __('Logo image in the footer (in the contacts area) used on Retina display', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'pastore-church'),
					"desc" => wp_kses_data( __('Height for the logo in the footer area (in the contacts area)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'pastore-church'),
					"desc" => wp_kses_data( __("Show/Hide copyright area in the footer", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'pastore-church'),
					"desc" => wp_kses_data( __('Show area with copyright information, footer menu and small social icons in footer', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "socials",
					"options" => array(
						'none' => esc_html__('Hide', 'pastore-church'),
						'text' => esc_html__('Text', 'pastore-church'),
						'menu' => esc_html__('Text and menu', 'pastore-church'),
						'socials' => esc_html__('Text and Social icons', 'pastore-church')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'pastore-church'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the copyright area', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => pastore_church_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'pastore-church'),
					"desc" => wp_kses_data( __('Select footer menu for the current page',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => pastore_church_get_options_param('list_menus'),
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'pastore-church'),
					"desc" => wp_kses_data( __("Copyright text to show in footer area (bottom of site)", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"allow_html" => true,
					"std" => "Pastore Church &copy; 2014 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'pastore-church'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'pastore-church'),
					"desc" => wp_kses_data( __('Animation parameters and responsive layouts for the small screens', 'pastore-church') ),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'pastore-church'),
					"desc" => wp_kses_data( __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'pastore-church'),
					"desc" => wp_kses_data( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'pastore-church') ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'pastore-church'),
					"desc" => wp_kses_data( __('Do you want use extended animations effects on your site?', 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'animation_on_mobile' => array(
					"title" => esc_html__('Allow CSS animations on mobile', 'pastore-church'),
					"desc" => wp_kses_data( __('Do you allow extended animations effects on mobile devices?', 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'pastore-church'),
					"desc" => wp_kses_data( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'pastore-church'),
					"desc" => wp_kses_data( __('Do you want use responsive layouts on small screen or still use main layout?', 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'pastore-church'),
					"desc" => wp_kses_data( __('Specify additional parameters, used to load Google fonts', 'pastore-church') ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'pastore-church'),
					"desc" => wp_kses_data( __('Select subset, included into used Google fonts', 'pastore-church') ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'pastore-church'),
						'latin-ext' => esc_html__('Latin Extended', 'pastore-church'),
						'greek' => esc_html__('Greek', 'pastore-church'),
						'greek-ext' => esc_html__('Greek Extended', 'pastore-church'),
						'cyrillic' => esc_html__('Cyrillic', 'pastore-church'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'pastore-church'),
						'vietnamese' => esc_html__('Vietnamese', 'pastore-church')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),


		'info_other_3' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'pastore-church'),
					"desc" => wp_kses_data( __('Put here your custom CSS and JS code', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom CSS/HTML/JS', 'pastore-church'),
					"desc" => wp_kses_data( __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'pastore-church'),
					"desc" => wp_kses_data( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'pastore-church') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'pastore-church'),
					"desc" => wp_kses_data( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'pastore-church') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => esc_html__('Your custom HTML/JS code',  'pastore-church'),
					"desc" => wp_kses_data( __('Put here your invisible html/js code: Google analitics, counters, etc',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your custom CSS code',  'pastore-church'),
					"desc" => wp_kses_data( __('Put here your css code to correct main theme styles',  'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'pastore-church'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'pastore-church'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'pastore-church'),
					"desc" => wp_kses_data( __('Select desired blog streampage parameters (you can override it in each category)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'pastore-church'),
					"desc" => wp_kses_data( __('Select desired blog style', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "excerpt",
					"options" => pastore_church_get_options_param('list_blog_styles'),
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'pastore-church'),
					"desc" => wp_kses_data( __('Select desired hover style (only for Blog style = Portfolio)', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => pastore_church_get_options_param('list_hovers'),
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'pastore-church'),
					"desc" => wp_kses_data( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => pastore_church_get_options_param('list_hovers_dir'),
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'pastore-church'),
					"desc" => wp_kses_data( __('Select article display method: boxed or stretch', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "stretch",
					"options" => pastore_church_get_options_param('list_article_styles'),
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'pastore-church'),
					"desc" => wp_kses_data( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => pastore_church_get_options_param('list_locations'),
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'pastore-church'),
					"desc" => wp_kses_data( __('What taxonomy use for filter buttons', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => pastore_church_get_options_param('list_filters'),
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'pastore-church'),
					"desc" => wp_kses_data( __('Select the desired sorting method for posts', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "date",
					"options" => pastore_church_get_options_param('list_sorting'),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'pastore-church'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "desc",
					"options" => pastore_church_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'pastore-church'),
					"desc" => wp_kses_data( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'pastore-church'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'pastore-church'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'pastore-church'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'pastore-church'),
					"desc" => wp_kses_data( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"services_icon" => array(
				"title" => esc_html__('Select services icon', 'pastore-church'),
				"desc" => wp_kses_data( __('Select icon for output before services in some layouts', 'pastore-church') ),
				"override" => "services_group,post,custom",
				"std" => "icon-book-open",
				"options" => pastore_church_get_options_param('list_icons'),
				"style" => "select",
				"type" => "icons"
			),

		"single_style" => array(
					"title" => esc_html__('Single page style', 'pastore-church'),
					"desc" => wp_kses_data( __('Select desired style for single page', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "single-standard",
					"options" => pastore_church_get_options_param('list_single_styles'),
					"dir" => "horizontal",
					"type" => "radio"),

		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'pastore-church'),
					"override" => "page,post",
					"desc" => wp_kses_data( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'pastore-church') ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => pastore_church_get_options_param('list_alter_sizes')
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'pastore-church'),
					"desc" => wp_kses_data( __("Show featured image (if selected) before post content on single pages", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'pastore-church'),
					"desc" => wp_kses_data( __('Show area with post title on single pages', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'pastore-church'),
					"desc" => wp_kses_data( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'pastore-church'),
					"desc" => wp_kses_data( __('Show area with post info on single pages', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'pastore-church'),
					"desc" => wp_kses_data( __('Show text before "Read more" tag on single pages', 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'pastore-church'),
					"desc" => wp_kses_data( __("Show post author information block on single post page", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'pastore-church'),
					"desc" => wp_kses_data( __("Show tags block on single post page", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'pastore-church'),
					"desc" => wp_kses_data( __("Show related posts block on single post page", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'pastore-church'),
					"desc" => wp_kses_data( __("How many related posts showed on single post page", 'pastore-church') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page,custom",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'pastore-church'),
					"desc" => wp_kses_data( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'pastore-church'),
					"desc" => wp_kses_data( __('Select the desired sorting method for related posts', 'pastore-church') ),
		//			"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => pastore_church_get_options_param('list_sorting'),
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'pastore-church'),
					"desc" => wp_kses_data( __('Select the desired ordering method for related posts', 'pastore-church') ),
		//			"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => pastore_church_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'pastore-church'),
					"desc" => wp_kses_data( __("Show comments block on single post page", 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'pastore-church'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'pastore-church'),
					"desc" => wp_kses_data( __('Select excluded categories, substitute parameters, etc.', 'pastore-church') ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'pastore-church'),
					"desc" => wp_kses_data( __('Select categories, which posts are exclude from blog page', 'pastore-church') ),
					"std" => "",
					"options" => pastore_church_get_options_param('list_categories'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'pastore-church'),
					"desc" => wp_kses_data( __('Select type of the pagination on blog streampages', 'pastore-church') ),
					"std" => "pages",
					"override" => "category,services_group,page,custom",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'pastore-church'),
						//'slider'   => esc_html__('Slider with page numbers', 'pastore-church'),
						'viewmore' => esc_html__('"View more" button', 'pastore-church'),
						'infinite' => esc_html__('Infinite scroll', 'pastore-church')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'pastore-church'),
					"desc" => wp_kses_data( __('Select counters, displayed near the post title', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "views",
					"options" => pastore_church_get_options_param('list_blog_counters'),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'pastore-church'),
					"desc" => wp_kses_data( __('What category display in announce block (over posts thumb) - original or nearest parental', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'pastore-church'),
						'original' => esc_html__("Original post's category", 'pastore-church')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'pastore-church'),
					"desc" => wp_kses_data( __('Show post date after N days (before - show post age)', 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'pastore-church'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'pastore-church'),
					"desc" => wp_kses_data( __('Set up list of reviews criterias. You can override it in any category.', 'pastore-church') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'pastore-church'),
					"desc" => wp_kses_data( __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'pastore-church') ),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'pastore-church'),
					"desc" => wp_kses_data( __("Maximum level for reviews marks", 'pastore-church') ),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'pastore-church'), 
						'10'=>esc_html__('10 stars', 'pastore-church'), 
						'100'=>esc_html__('100%', 'pastore-church')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'pastore-church'),
					"desc" => wp_kses_data( __("Show rating marks as text or as stars/progress bars.", 'pastore-church') ),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'pastore-church'), 
						'stars' => esc_html__('As stars or bars', 'pastore-church')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'pastore-church'),
					"desc" => wp_kses_data( __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'pastore-church') ),
					"std" => esc_html__("bad,poor,normal,good,great", 'pastore-church'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'pastore-church'),
					"desc" => wp_kses_data( __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'pastore-church') ),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'pastore-church'),
						'users' => esc_html__('By visitors', 'pastore-church')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'pastore-church'),
					"desc" => wp_kses_data( __("Do you want hide second reviews tab in widgets and single posts?", 'pastore-church') ),
					"std" => "show",
					"options" => pastore_church_get_options_param('list_show_hide'),
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'pastore-church'),
					"desc" => wp_kses_data( __("What visitors can vote: all or only registered", 'pastore-church') ),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'pastore-church'), 
						'registered'=>esc_html__('Only registered', 'pastore-church')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'pastore-church'),
					"desc" => wp_kses_data( __('Add default reviews criterias.',  'pastore-church') ),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'pastore-church'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'pastore-church'),
					"desc" => wp_kses_data( __('Set up parameters to show images, galleries, audio and video posts', 'pastore-church') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'pastore-church'),
					"desc" => wp_kses_data( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'pastore-church') ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'pastore-church'), 
						"2" => esc_html__("Retina", 'pastore-church')
					),
					"type" => "switch"),
		
		"images_quality" => array(
					"title" => esc_html__('Quality for cropped images', 'pastore-church'),
					"desc" => wp_kses_data( __('Quality (1-100) to save cropped images', 'pastore-church') ),
					"std" => "70",
					"min" => 1,
					"max" => 100,
					"type" => "spinner"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'pastore-church'),
					"desc" => wp_kses_data( __('Substitute standard Wordpress gallery with our slider on the single pages', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'pastore-church'),
					"desc" => wp_kses_data( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'pastore-church'),
					"desc" => wp_kses_data( __('Maximum images number from gallery into slider', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'pastore-church'),
					"desc" => wp_kses_data( __('Select engine to show popup windows with images and galleries', 'pastore-church') ),
					"std" => "magnific",
					"options" => pastore_church_get_options_param('list_popups'),
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'pastore-church'),
					"desc" => wp_kses_data( __('Substitute audio tag with source from soundcloud to embed player', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'pastore-church'),
					"desc" => wp_kses_data( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'pastore-church') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'pastore-church'),
					"desc" => wp_kses_data( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'pastore-church'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page,post,custom",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'pastore-church'),
					"desc" => wp_kses_data( __("Social networks list for site footer and Social widget", 'pastore-church') ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'pastore-church'),
					"desc" => wp_kses_data( __('Select icon and write URL to your profile in desired social networks.',  'pastore-church') ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? pastore_church_get_options_param('list_socials') : pastore_church_get_options_param('list_icons'),
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'pastore-church'),
					"desc" => wp_kses_data( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'pastore-church'),
					"desc" => wp_kses_data( __("Show social share buttons block", 'pastore-church') ),
					"override" => "category,services_group,page,post,custom",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'pastore-church'),
						//'vertical'	=> esc_html__('Vertical', 'pastore-church'),
						'horizontal'=> esc_html__('Horizontal', 'pastore-church')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'pastore-church'),
					"desc" => wp_kses_data( __("Show share counters after social buttons", 'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'pastore-church'),
					"desc" => wp_kses_data( __('Caption for the block with social share buttons',  'pastore-church') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'pastore-church'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'pastore-church'),
					"desc" => wp_kses_data( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'pastore-church') ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? pastore_church_get_options_param('list_socials') : pastore_church_get_options_param('list_icons'),
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'pastore-church'),
					"desc" => wp_kses_data( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'pastore-church') ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'pastore-church'),
					"desc" => wp_kses_data( __('Your login (username) in Twitter',  'pastore-church') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'pastore-church'),
					"desc" => wp_kses_data( __('Twitter API Consumer key',  'pastore-church') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'pastore-church'),
					"desc" => wp_kses_data( __('Twitter API Consumer secret',  'pastore-church') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'pastore-church'),
					"desc" => wp_kses_data( __('Twitter API Token key',  'pastore-church') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'pastore-church'),
					"desc" => wp_kses_data( __('Twitter API Token secret',  'pastore-church') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),

		"info_socials_4" => array(
					"title" => esc_html__('Login via Social network', 'pastore-church'),
					"desc" => esc_html__("Settings for the Login via Social networks", 'pastore-church'),
					"type" => "info"),

		"social_login" => array(
					"title" => esc_html__('Social plugin shortcode',  'pastore-church'),
					"desc" => esc_html__('Social plugin shortcode like [plugin_shortcode]',  'pastore-church'),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'pastore-church'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'pastore-church'),
					"desc" => wp_kses_data( __('Company address, phones and e-mail', 'pastore-church') ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Address in the header', 'pastore-church'),
					"desc" => wp_kses_data( __('String with contact info in the left side of the site header', 'pastore-church') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header', 'pastore-church'),
					"desc" => wp_kses_data( __('String with open hours in form contact', 'pastore-church') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'pastore-church'),
					"desc" => wp_kses_data( __('E-mail for send contact form and user registration data', 'pastore-church') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'pastore-church'),
					"desc" => wp_kses_data( __('Company country, post code and city', 'pastore-church') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'pastore-church'),
					"desc" => wp_kses_data( __('Street and house number', 'pastore-church') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'pastore-church'),
					"desc" => wp_kses_data( __('Phone number', 'pastore-church') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'pastore-church'),
					"desc" => wp_kses_data( __('Fax number', 'pastore-church') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'pastore-church'),
					"desc" => wp_kses_data( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'pastore-church') ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'pastore-church'),
					"desc" => wp_kses_data( __("Message's maxlength in the contact form shortcode", 'pastore-church') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'pastore-church'),
					"desc" => wp_kses_data( __("Message's maxlength in the comments form", 'pastore-church') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'pastore-church'),
					"desc" => wp_kses_data( __('What function use to send mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'pastore-church') ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'pastore-church'),
					"desc" => wp_kses_data( __("What function use to send mail? Attention! Only wp_mail support attachment in the mail!", 'pastore-church') ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'pastore-church'),
						'mail' => esc_html__('PHP mail', 'pastore-church')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'pastore-church'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'pastore-church'),
					"desc" => wp_kses_data( __('Enable/disable AJAX search and output settings for it', 'pastore-church') ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'pastore-church'),
					"desc" => wp_kses_data( __('Show search field in the top area and side menus', 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'pastore-church'),
					"desc" => wp_kses_data( __('Use incremental AJAX search for the search field in top of page', 'pastore-church') ),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'pastore-church'),
					"desc" => wp_kses_data( __('The minimum length of the search string',  'pastore-church') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'pastore-church'),
					"desc" => wp_kses_data( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'pastore-church') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'pastore-church'),
					"desc" => wp_kses_data( __('Select post types, what will be include in search results. If not selected - use all types.', 'pastore-church') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => pastore_church_get_options_param('list_posts_types'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'pastore-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __('Number of the posts to show in search results',  'pastore-church') ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'pastore-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's thumbnail in the search results", 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'pastore-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's publish date in the search results", 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'pastore-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's author in the search results", 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'pastore-church'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's counters (views, comments, likes) in the search results", 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'pastore-church'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'pastore-church'),
					"desc" => wp_kses_data( __('Basic theme functionality settings', 'pastore-church') ),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => esc_html__('Notify about new registration', 'pastore-church'),
					"desc" => wp_kses_data( __('Send E-mail with new registration data to the contact email or to site admin e-mail (if contact email is empty)', 'pastore-church') ),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => esc_html__('No', 'pastore-church'),
						'both'  => esc_html__('Both', 'pastore-church'),
						'admin' => esc_html__('Admin', 'pastore-church'),
						'user'  => esc_html__('User', 'pastore-church')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'pastore-church'),
					"desc" => wp_kses_data( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'pastore-church'),
					"desc" => wp_kses_data( __("Allow authors to edit their posts in frontend area", 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'pastore-church'),
					"desc" => wp_kses_data( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'pastore-church'),
					"desc" => wp_kses_data( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'pastore-church'),
					"desc" => wp_kses_data( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'pastore-church'),
					"desc" => wp_kses_data( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'pastore-church') ),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'pastore-church'),
					"desc" => wp_kses_data( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'pastore-church') ),
					"std" => 120,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'pastore-church'),
					"desc" => wp_kses_data( __('Allow to use ThemeREX Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => esc_html__('Enable PO Composer in the admin panel', 'pastore-church'),
					"desc" => wp_kses_data( __('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'pastore-church'),
					"desc" => wp_kses_data( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utilities', 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		"info_service_2" => array(
					"title" => esc_html__('Wordpress cache', 'pastore-church'),
					"desc" => wp_kses_data( __('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'pastore-church') ),
					"type" => "info"),
		
		"use_menu_cache" => array(
					"title" => esc_html__('Use menu cache', 'pastore-church'),
					"desc" => wp_kses_data( __('Use cache for any menu (increase theme speed, decrease queries number). Attention! Please, clear cache after change permalink settings!', 'pastore-church') ),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"clear_cache" => array(
					"title" => esc_html__('Clear cache', 'pastore-church'),
					"desc" => wp_kses_data( __('Clear Wordpress cache data', 'pastore-church') ),
					"divider" => false,
					"icon" => "iconadmin-trash",
					"action" => "clear_cache",
					"type" => "button"),

		"info_service_3" => array(
					"title" => esc_html__('API Keys', 'pastore-church'),
					"desc" => wp_kses_data( __('API Keys for some Web services', 'pastore-church') ),
					"type" => "info"),

		"api_google" => array(
					"title" => esc_html__('Google API Key', 'pastore-church'),
					"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'pastore-church') ),
					"std" => "",
					"type" => "text"),
		));



		
		
		
		//###############################################
		//#### Hidden fields (for internal use only) #### 
		//###############################################
		/*
		pastore_church_storage_set_array('options', "custom_stylesheet_file", array(
			"title" => esc_html__('Custom stylesheet file', 'pastore-church'),
			"desc" => wp_kses_data( __('Path to the custom stylesheet (stored in the uploads folder)', 'pastore-church') ),
			"std" => "",
			"type" => "hidden"
			)
		);
		
		pastore_church_storage_set_array('options', "custom_stylesheet_url", array(
			"title" => esc_html__('Custom stylesheet url', 'pastore-church'),
			"desc" => wp_kses_data( __('URL to the custom stylesheet (stored in the uploads folder)', 'pastore-church') ),
			"std" => "",
			"type" => "hidden"
			)
		);
		*/

	}
}


// Update all temporary vars (start with $pastore_church_) in the Theme Options with actual lists
if ( !function_exists( 'pastore_church_options_settings_theme_setup2' ) ) {
	add_action( 'pastore_church_action_after_init_theme', 'pastore_church_options_settings_theme_setup2', 1 );
	function pastore_church_options_settings_theme_setup2() {
		if (pastore_church_options_is_used()) {
			// Replace arrays with actual parameters
			$lists = array();
			$tmp = pastore_church_storage_get('options');
			if (is_array($tmp) && count($tmp) > 0) {
				$prefix = '$pastore_church_';
				$prefix_len = pastore_church_strlen($prefix);
				foreach ($tmp as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (pastore_church_substr($k1, 0, $prefix_len) == $prefix || pastore_church_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = pastore_church_substr(pastore_church_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								unset($tmp[$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$tmp[$k]['options'] = pastore_church_array_merge($tmp[$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$tmp[$k]['options'] = $lists[$list_func] = pastore_church_array_merge($tmp[$k]['options'], $list_func == 'pastore_church_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'pastore-church'), $list_func));
								}
							}
						}
					}
				}
				pastore_church_storage_set('options', $tmp);
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'pastore_church_options_reset' ) ) {
	//add_action('after_switch_theme', 'pastore_church_options_reset');
	function pastore_church_options_reset($clear=true) {
		$theme_slug = str_replace(' ', '_', trim(pastore_church_strtolower(get_stylesheet())));
		$option_name = pastore_church_storage_get('options_prefix') . '_' . trim($theme_slug) . '_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "'.esc_sql(pastore_church_storage_get('options_prefix')).'_%"');
				// Add Templates Options
				if (file_exists(pastore_church_get_file_dir('demo/templates_options.txt'))) {
					$txt = pastore_church_fgc(pastore_church_get_file_dir('demo/templates_options.txt'));
					$data = pastore_church_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = pastore_church_replace_uploads_url(pastore_church_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>