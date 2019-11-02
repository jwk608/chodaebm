<?php
/* Donations support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pastore_church_trx_donations_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_trx_donations_theme_setup', 1 );
	function pastore_church_trx_donations_theme_setup() {

		// Register shortcode in the shortcodes list
		if (pastore_church_exists_trx_donations()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('pastore_church_filter_get_blog_type',			'pastore_church_trx_donations_get_blog_type', 9, 2);
			add_filter('pastore_church_filter_get_blog_title',		'pastore_church_trx_donations_get_blog_title', 9, 2);
			add_filter('pastore_church_filter_get_current_taxonomy',	'pastore_church_trx_donations_get_current_taxonomy', 9, 2);
			add_filter('pastore_church_filter_is_taxonomy',			'pastore_church_trx_donations_is_taxonomy', 9, 2);
			add_filter('pastore_church_filter_get_stream_page_title',	'pastore_church_trx_donations_get_stream_page_title', 9, 2);
			add_filter('pastore_church_filter_get_stream_page_link',	'pastore_church_trx_donations_get_stream_page_link', 9, 2);
			add_filter('pastore_church_filter_get_stream_page_id',	'pastore_church_trx_donations_get_stream_page_id', 9, 2);
			add_filter('pastore_church_filter_query_add_filters',		'pastore_church_trx_donations_query_add_filters', 9, 2);
			add_filter('pastore_church_filter_detect_inheritance_key','pastore_church_trx_donations_detect_inheritance_key', 9, 1);
			add_filter('pastore_church_filter_list_post_types',		'pastore_church_trx_donations_list_post_types');
			// Register shortcodes in the list
			add_action('pastore_church_action_shortcodes_list',		'pastore_church_trx_donations_reg_shortcodes');
			if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
				add_action('pastore_church_action_shortcodes_list_vc','pastore_church_trx_donations_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'pastore_church_filter_importer_options',				'pastore_church_trx_donations_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'pastore_church_filter_importer_required_plugins',	'pastore_church_trx_donations_importer_required_plugins', 10, 2 );
			add_filter( 'pastore_church_filter_required_plugins',				'pastore_church_trx_donations_required_plugins' );
		}
	}
}

if ( !function_exists( 'pastore_church_trx_donations_settings_theme_setup2' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_trx_donations_settings_theme_setup2', 3 );
	function pastore_church_trx_donations_settings_theme_setup2() {
		// Add Donations post type and taxonomy into theme inheritance list
		if (pastore_church_exists_trx_donations()) {
			pastore_church_add_theme_inheritance( array('donations' => array(
				'stream_template' => 'blog-donations',
				'single_template' => 'single-donation',
				'taxonomy' => array(TRX_DONATIONS::TAXONOMY),
				'taxonomy_tags' => array(),
				'post_type' => array(TRX_DONATIONS::POST_TYPE),
				'override' => 'page'
				) )
			);
		}
	}
}

// Check if Donations installed and activated
if ( !function_exists( 'pastore_church_exists_trx_donations' ) ) {
	function pastore_church_exists_trx_donations() {
		return class_exists('TRX_DONATIONS');
	}
}


// Return true, if current page is donations page
if ( !function_exists( 'pastore_church_is_trx_donations_page' ) ) {
	function pastore_church_is_trx_donations_page() {
		$is = false;
		if (pastore_church_exists_trx_donations()) {
			$is = in_array(pastore_church_storage_get('page_template'), array('blog-donations', 'single-donation'));
			if (!$is) {
				if (!pastore_church_storage_empty('pre_query'))
					$is = (pastore_church_storage_call_obj_method('pre_query', 'is_single') && pastore_church_storage_call_obj_method('pre_query', 'get', 'post_type') == TRX_DONATIONS::POST_TYPE) 
							|| pastore_church_storage_call_obj_method('pre_query', 'is_post_type_archive', TRX_DONATIONS::POST_TYPE) 
							|| pastore_church_storage_call_obj_method('pre_query', 'is_tax', TRX_DONATIONS::TAXONOMY);
				else
					$is = (is_single() && get_query_var('post_type') == TRX_DONATIONS::POST_TYPE) 
							|| is_post_type_archive(TRX_DONATIONS::POST_TYPE) 
							|| is_tax(TRX_DONATIONS::TAXONOMY);
			}
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'pastore_church_trx_donations_detect_inheritance_key' ) ) {
	//add_filter('pastore_church_filter_detect_inheritance_key',	'pastore_church_trx_donations_detect_inheritance_key', 9, 1);
	function pastore_church_trx_donations_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return pastore_church_is_trx_donations_page() ? 'donations' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'pastore_church_trx_donations_get_blog_type' ) ) {
	//add_filter('pastore_church_filter_get_blog_type',	'pastore_church_trx_donations_get_blog_type', 9, 2);
	function pastore_church_trx_donations_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax(TRX_DONATIONS::TAXONOMY) || is_tax(TRX_DONATIONS::TAXONOMY))
			$page = 'donations_category';
		else if ($query && $query->get('post_type')==TRX_DONATIONS::POST_TYPE || get_query_var('post_type')==TRX_DONATIONS::POST_TYPE)
			$page = $query && $query->is_single() || is_single() ? 'donations_item' : 'donations';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'pastore_church_trx_donations_get_blog_title' ) ) {
	//add_filter('pastore_church_filter_get_blog_title',	'pastore_church_trx_donations_get_blog_title', 9, 2);
	function pastore_church_trx_donations_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( pastore_church_strpos($page, 'donations')!==false ) {
			if ( $page == 'donations_category' ) {
				$term = get_term_by( 'slug', get_query_var( TRX_DONATIONS::TAXONOMY ), TRX_DONATIONS::TAXONOMY, OBJECT);
				$title = $term->name;
			} else if ( $page == 'donations_item' ) {
				$title = pastore_church_get_post_title();
			} else {
				$title = esc_html__('All donations', 'pastore-church');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'pastore_church_trx_donations_get_stream_page_title' ) ) {
	//add_filter('pastore_church_filter_get_stream_page_title',	'pastore_church_trx_donations_get_stream_page_title', 9, 2);
	function pastore_church_trx_donations_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (pastore_church_strpos($page, 'donations')!==false) {
			if (($page_id = pastore_church_trx_donations_get_stream_page_id(0, $page=='donations' ? 'blog-donations' : $page)) > 0)
				$title = pastore_church_get_post_title($page_id);
			else
				$title = esc_html__('All donations', 'pastore-church');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'pastore_church_trx_donations_get_stream_page_id' ) ) {
	//add_filter('pastore_church_filter_get_stream_page_id',	'pastore_church_trx_donations_get_stream_page_id', 9, 2);
	function pastore_church_trx_donations_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (pastore_church_strpos($page, 'donations')!==false) $id = pastore_church_get_template_page_id('blog-donations');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'pastore_church_trx_donations_get_stream_page_link' ) ) {
	//add_filter('pastore_church_filter_get_stream_page_link',	'pastore_church_trx_donations_get_stream_page_link', 9, 2);
	function pastore_church_trx_donations_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (pastore_church_strpos($page, 'donations')!==false) {
			$id = pastore_church_get_template_page_id('blog-donations');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'pastore_church_trx_donations_get_current_taxonomy' ) ) {
	//add_filter('pastore_church_filter_get_current_taxonomy',	'pastore_church_trx_donations_get_current_taxonomy', 9, 2);
	function pastore_church_trx_donations_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( pastore_church_strpos($page, 'donations')!==false ) {
			$tax = TRX_DONATIONS::TAXONOMY;
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'pastore_church_trx_donations_is_taxonomy' ) ) {
	//add_filter('pastore_church_filter_is_taxonomy',	'pastore_church_trx_donations_is_taxonomy', 9, 2);
	function pastore_church_trx_donations_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get(TRX_DONATIONS::TAXONOMY)!='' || is_tax(TRX_DONATIONS::TAXONOMY) ? TRX_DONATIONS::TAXONOMY : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'pastore_church_trx_donations_query_add_filters' ) ) {
	//add_filter('pastore_church_filter_query_add_filters',	'pastore_church_trx_donations_query_add_filters', 9, 2);
	function pastore_church_trx_donations_query_add_filters($args, $filter) {
		if ($filter == 'donations') {
			$args['post_type'] = TRX_DONATIONS::POST_TYPE;
		}
		return $args;
	}
}

// Add custom post type to the list
if ( !function_exists( 'pastore_church_trx_donations_list_post_types' ) ) {
	//add_filter('pastore_church_filter_list_post_types',		'pastore_church_trx_donations_list_post_types');
	function pastore_church_trx_donations_list_post_types($list) {
		$list[TRX_DONATIONS::POST_TYPE] = esc_html__('Donations', 'pastore-church');
		return $list;
	}
}


// Register shortcode in the shortcodes list
if (!function_exists('pastore_church_trx_donations_reg_shortcodes')) {
	//add_filter('pastore_church_action_shortcodes_list',	'pastore_church_trx_donations_reg_shortcodes');
	function pastore_church_trx_donations_reg_shortcodes() {
		if (pastore_church_storage_isset('shortcodes')) {

			$plugin = TRX_DONATIONS::get_instance();
			$donations_groups = pastore_church_get_list_terms(false, TRX_DONATIONS::TAXONOMY);

			pastore_church_sc_map_before('trx_dropcaps', array(

				// Donations form
				"trx_donations_form" => array(
					"title" => esc_html__("Donations form", 'pastore-church'),
					"desc" => esc_html__("Insert Donations form", 'pastore-church'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'pastore-church'),
							"desc" => esc_html__("Title for the donations form", 'pastore-church'),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'pastore-church'),
							"desc" => esc_html__("Subtitle for the donations form", 'pastore-church'),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'pastore-church'),
							"desc" => esc_html__("Short description for the donations form", 'pastore-church'),
							"value" => "",
							"type" => "textarea"
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'pastore-church'),
							"desc" => esc_html__("Alignment of the donations form", 'pastore-church'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => pastore_church_get_sc_param('align')
						),
						"account" => array(
							"title" => esc_html__("PayPal account", 'pastore-church'),
							"desc" => esc_html__("PayPal account's e-mail. If empty - used from Donations settings", 'pastore-church'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"sandbox" => array(
							"title" => esc_html__("Sandbox mode", 'pastore-church'),
							"desc" => esc_html__("Use PayPal sandbox to test payments", 'pastore-church'),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => pastore_church_get_sc_param('yes_no')
						),
						"amount" => array(
							"title" => esc_html__("Default amount", 'pastore-church'),
							"desc" => esc_html__("Specify amount, initially selected in the form", 'pastore-church'),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => 5,
							"min" => 1,
							"step" => 5,
							"type" => "spinner"
						),
						"currency" => array(
							"title" => esc_html__("Currency", 'pastore-church'),
							"desc" => esc_html__("Select payment's currency", 'pastore-church'),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => pastore_church_array_merge(array(0 => esc_html__('- Select currency -', 'pastore-church')), $plugin->currency_codes)
						),
						"width" => pastore_church_shortcodes_width(),
						"top" => pastore_church_get_sc_param('top'),
						"bottom" => pastore_church_get_sc_param('bottom'),
						"left" => pastore_church_get_sc_param('left'),
						"right" => pastore_church_get_sc_param('right'),
						"id" => pastore_church_get_sc_param('id'),
						"class" => pastore_church_get_sc_param('class'),
						"css" => pastore_church_get_sc_param('css')
					)
				),
				
				
				// Donations form
				"trx_donations_list" => array(
					"title" => esc_html__("Donations list", 'pastore-church'),
					"desc" => esc_html__("Insert Doantions list", 'pastore-church'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'pastore-church'),
							"desc" => esc_html__("Title for the donations list", 'pastore-church'),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'pastore-church'),
							"desc" => esc_html__("Subtitle for the donations list", 'pastore-church'),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'pastore-church'),
							"desc" => esc_html__("Short description for the donations list", 'pastore-church'),
							"value" => "",
							"type" => "textarea"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'pastore-church'),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", 'pastore-church'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'pastore-church'),
							"desc" => esc_html__("Caption for the button at the bottom of the block", 'pastore-church'),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("List style", 'pastore-church'),
							"desc" => esc_html__("Select style to display donations", 'pastore-church'),
							"value" => "excerpt",
							"type" => "select",
							"options" => array(
								'excerpt' => esc_html__('Excerpt', 'pastore-church')
							)
						),
						"readmore" => array(
							"title" => esc_html__("Read more text", 'pastore-church'),
							"desc" => esc_html__("Text of the 'Read more' link", 'pastore-church'),
							"value" => esc_html__('Read more', 'pastore-church'),
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'pastore-church'),
							"desc" => esc_html__("Select categories (groups) to show donations. If empty - select donations from any category (group) or from IDs list", 'pastore-church'),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => pastore_church_array_merge(array(0 => esc_html__('- Select category -', 'pastore-church')), $donations_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of donations", 'pastore-church'),
							"desc" => esc_html__("How many donations will be displayed? If used IDs - this parameter ignored.", 'pastore-church'),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'pastore-church'),
							"desc" => esc_html__("How many columns use to show donations list", 'pastore-church'),
							"value" => 3,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'pastore-church'),
							"desc" => esc_html__("Skip posts before select next part.", 'pastore-church'),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Donadions order by", 'pastore-church'),
							"desc" => esc_html__("Select desired sorting method", 'pastore-church'),
							"value" => "date",
							"type" => "select",
							"options" => pastore_church_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Donations order", 'pastore-church'),
							"desc" => esc_html__("Select donations order", 'pastore-church'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => pastore_church_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Donations IDs list", 'pastore-church'),
							"desc" => esc_html__("Comma separated list of donations ID. If set - parameters above are ignored!", 'pastore-church'),
							"value" => "",
							"type" => "text"
						),
						"top" => pastore_church_get_sc_param('top'),
						"bottom" => pastore_church_get_sc_param('bottom'),
						"id" => pastore_church_get_sc_param('id'),
						"class" => pastore_church_get_sc_param('class'),
						"css" => pastore_church_get_sc_param('css')
					)
				)

			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('pastore_church_trx_donations_reg_shortcodes_vc')) {
	//add_filter('pastore_church_action_shortcodes_list_vc',	'pastore_church_trx_donations_reg_shortcodes_vc');
	function pastore_church_trx_donations_reg_shortcodes_vc() {

		$plugin = TRX_DONATIONS::get_instance();
		$donations_groups = pastore_church_get_list_terms(false, TRX_DONATIONS::TAXONOMY);

		// Donations form
		vc_map( array(
				"base" => "trx_donations_form",
				"name" => esc_html__("Donations form", 'pastore-church'),
				"description" => esc_html__("Insert Donations form", 'pastore-church'),
				"category" => esc_html__('Content', 'pastore-church'),
				'icon' => 'icon_trx_donations_form',
				"class" => "trx_sc_single trx_sc_donations_form",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'pastore-church'),
						"description" => esc_html__("Title for the donations form", 'pastore-church'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'pastore-church'),
						"description" => esc_html__("Subtitle for the donations form", 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'pastore-church'),
						"description" => esc_html__("Description for the donations form", 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'pastore-church'),
						"description" => esc_html__("Alignment of the donations form", 'pastore-church'),
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "account",
						"heading" => esc_html__("PayPal account", 'pastore-church'),
						"description" => esc_html__("PayPal account's e-mail. If empty - used from Donations settings", 'pastore-church'),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "sandbox",
						"heading" => esc_html__("Sandbox mode", 'pastore-church'),
						"description" => esc_html__("Use PayPal sandbox to test payments", 'pastore-church'),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'pastore-church'),
						'dependency' => array(
							'element' => 'account',
							'not_empty' => true
						),
						"class" => "",
						"value" => array("Sandbox mode" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "amount",
						"heading" => esc_html__("Default amount", 'pastore-church'),
						"description" => esc_html__("Specify amount, initially selected in the form", 'pastore-church'),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'pastore-church'),
						"class" => "",
						"value" => "5",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency", 'pastore-church'),
						"description" => esc_html__("Select payment's currency", 'pastore-church'),
						"class" => "",
						"value" => array_flip(pastore_church_array_merge(array(0 => esc_html__('- Select currency -', 'pastore-church')), $plugin->currency_codes)),
						"type" => "dropdown"
					),
					pastore_church_get_vc_param('id'),
					pastore_church_get_vc_param('class'),
					pastore_church_get_vc_param('css'),
					pastore_church_vc_width(),
					pastore_church_get_vc_param('margin_top'),
					pastore_church_get_vc_param('margin_bottom'),
					pastore_church_get_vc_param('margin_left'),
					pastore_church_get_vc_param('margin_right')
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_Form extends PASTORE_CHURCH_VC_ShortCodeSingle {}



		// Donations list
		vc_map( array(
				"base" => "trx_donations_list",
				"name" => esc_html__("Donations list", 'pastore-church'),
				"description" => esc_html__("Insert Donations list", 'pastore-church'),
				"category" => esc_html__('Content', 'pastore-church'),
				'icon' => 'icon_trx_donations_list',
				"class" => "trx_sc_single trx_sc_donations_list",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("List style", 'pastore-church'),
						"description" => esc_html__("Select style to display donations", 'pastore-church'),
						"class" => "",
						"value" => array(
							esc_html__('Excerpt', 'pastore-church') => 'excerpt'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'pastore-church'),
						"description" => esc_html__("Title for the donations form", 'pastore-church'),
						"group" => esc_html__('Captions', 'pastore-church'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'pastore-church'),
						"description" => esc_html__("Subtitle for the donations form", 'pastore-church'),
						"group" => esc_html__('Captions', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'pastore-church'),
						"description" => esc_html__("Description for the donations form", 'pastore-church'),
						"group" => esc_html__('Captions', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'pastore-church'),
						"description" => esc_html__("Link URL for the button at the bottom of the block", 'pastore-church'),
						"group" => esc_html__('Captions', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", 'pastore-church'),
						"description" => esc_html__("Caption for the button at the bottom of the block", 'pastore-church'),
						"group" => esc_html__('Captions', 'pastore-church'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more text", 'pastore-church'),
						"description" => esc_html__("Text of the 'Read more' link", 'pastore-church'),
						"group" => esc_html__('Captions', 'pastore-church'),
						"class" => "",
						"value" => esc_html__('Read more', 'pastore-church'),
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'pastore-church'),
						"description" => esc_html__("Select category to show donations. If empty - select donations from any category (group) or from IDs list", 'pastore-church'),
						"group" => esc_html__('Query', 'pastore-church'),
						"class" => "",
						"value" => array_flip(pastore_church_array_merge(array(0 => esc_html__('- Select category -', 'pastore-church')), $donations_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => esc_html__("How many columns use to show donations", 'pastore-church'),
						"group" => esc_html__('Query', 'pastore-church'),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'pastore-church'),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'pastore-church'),
						"group" => esc_html__('Query', 'pastore-church'),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'pastore-church'),
						"description" => esc_html__("Skip posts before select next part.", 'pastore-church'),
						"group" => esc_html__('Query', 'pastore-church'),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'pastore-church'),
						"description" => esc_html__("Select desired posts sorting method", 'pastore-church'),
						"group" => esc_html__('Query', 'pastore-church'),
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'pastore-church'),
						"description" => esc_html__("Select desired posts order", 'pastore-church'),
						"group" => esc_html__('Query', 'pastore-church'),
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("client's IDs list", 'pastore-church'),
						"description" => esc_html__("Comma separated list of donation's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'pastore-church'),
						"group" => esc_html__('Query', 'pastore-church'),
						'dependency' => array(
							'element' => 'cats',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),

					pastore_church_get_vc_param('id'),
					pastore_church_get_vc_param('class'),
					pastore_church_get_vc_param('css'),
					pastore_church_get_vc_param('margin_top'),
					pastore_church_get_vc_param('margin_bottom')
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_List extends PASTORE_CHURCH_VC_ShortCodeSingle {}

	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pastore_church_trx_donations_required_plugins' ) ) {
	//add_filter('pastore_church_filter_required_plugins',	'pastore_church_trx_donations_required_plugins');
	function pastore_church_trx_donations_required_plugins($list=array()) {
		if (in_array('trx_donations', pastore_church_storage_get('required_plugins'))) {
			$path = pastore_church_get_file_dir('plugins/install/trx_donations.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Donations',
					'slug' 		=> 'trx_donations',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'pastore_church_trx_donations_importer_required_plugins' ) ) {
	//add_filter( 'pastore_church_filter_importer_required_plugins',	'pastore_church_trx_donations_importer_required_plugins', 10, 2 );
	function pastore_church_trx_donations_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('trx_donations', pastore_church_storage_get('required_plugins')) && !pastore_church_exists_trx_donations() )
		if (pastore_church_strpos($list, 'trx_donations')!==false && !pastore_church_exists_trx_donations() )
			$not_installed .= '<br>Donations';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'pastore_church_trx_donations_importer_set_options' ) ) {
	//add_filter( 'pastore_church_filter_importer_options',	'pastore_church_trx_donations_importer_set_options' );
	function pastore_church_trx_donations_importer_set_options($options=array()) {
		if ( in_array('trx_donations', pastore_church_storage_get('required_plugins')) && pastore_church_exists_trx_donations() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'trx_donations_options';
		}
		return $options;
	}
}
?>