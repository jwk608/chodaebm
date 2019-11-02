<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pastore_church_woocommerce_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_woocommerce_theme_setup', 1 );
	function pastore_church_woocommerce_theme_setup() {

		if (pastore_church_exists_woocommerce()) {
			add_action('pastore_church_action_add_styles', 				'pastore_church_woocommerce_frontend_scripts' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('pastore_church_filter_get_blog_type',				'pastore_church_woocommerce_get_blog_type', 9, 2);
			add_filter('pastore_church_filter_get_blog_title',			'pastore_church_woocommerce_get_blog_title', 9, 2);
			add_filter('pastore_church_filter_get_current_taxonomy',		'pastore_church_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('pastore_church_filter_is_taxonomy',				'pastore_church_woocommerce_is_taxonomy', 9, 2);
			add_filter('pastore_church_filter_get_stream_page_title',		'pastore_church_woocommerce_get_stream_page_title', 9, 2);
			add_filter('pastore_church_filter_get_stream_page_link',		'pastore_church_woocommerce_get_stream_page_link', 9, 2);
			add_filter('pastore_church_filter_get_stream_page_id',		'pastore_church_woocommerce_get_stream_page_id', 9, 2);
			add_filter('pastore_church_filter_detect_inheritance_key',	'pastore_church_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('pastore_church_filter_detect_template_page_id',	'pastore_church_woocommerce_detect_template_page_id', 9, 2);
			add_filter('pastore_church_filter_orderby_need',				'pastore_church_woocommerce_orderby_need', 9, 2);

			add_filter('pastore_church_filter_list_post_types', 			'pastore_church_woocommerce_list_post_types', 10, 1);

			add_action('pastore_church_action_shortcodes_list', 			'pastore_church_woocommerce_reg_shortcodes', 20);
			if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
				add_action('pastore_church_action_shortcodes_list_vc',	'pastore_church_woocommerce_reg_shortcodes_vc', 20);

			if (is_admin()) {
				add_filter( 'pastore_church_filter_importer_options',				'pastore_church_woocommerce_importer_set_options' );
				add_action( 'pastore_church_action_importer_after_import_posts',	'pastore_church_woocommerce_importer_after_import_posts', 10, 1 );
				add_action( 'pastore_church_action_importer_params',				'pastore_church_woocommerce_importer_show_params', 10, 1 );
				add_action( 'pastore_church_action_importer_import',				'pastore_church_woocommerce_importer_import', 10, 2 );
				add_action( 'pastore_church_action_importer_import_fields',		'pastore_church_woocommerce_importer_import_fields', 10, 1 );
				add_action( 'pastore_church_action_importer_export',				'pastore_church_woocommerce_importer_export', 10, 1 );
				add_action( 'pastore_church_action_importer_export_fields',		'pastore_church_woocommerce_importer_export_fields', 10, 1 );
			}
		}

		if (is_admin()) {
			add_filter( 'pastore_church_filter_importer_required_plugins',		'pastore_church_woocommerce_importer_required_plugins', 10, 2 );
			add_filter( 'pastore_church_filter_required_plugins',					'pastore_church_woocommerce_required_plugins' );
		}
	}
}

if ( !function_exists( 'pastore_church_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_woocommerce_settings_theme_setup2', 3 );
	function pastore_church_woocommerce_settings_theme_setup2() {
		if (pastore_church_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			pastore_church_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => 'blog-woocommerce',		// This params must be empty
				'single_template' => 'single-woocommerce',		// They are specified to enable separate settings for blog and single wooc
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'page'
				) )
			);

			// Add WooCommerce specific options in the Theme Options

			pastore_church_storage_set_array_before('options', 'partition_service', array(
				
				"partition_woocommerce" => array(
					"title" => esc_html__('WooCommerce', 'pastore-church'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => esc_html__('WooCommerce products list parameters', 'pastore-church'),
					"desc" => esc_html__("Select WooCommerce products list's style and crop parameters", 'pastore-church'),
					"type" => "info"),
		
				"shop_mode" => array(
					"title" => esc_html__('Shop list style',  'pastore-church'),
					"desc" => esc_html__("WooCommerce products list's style: thumbs or list with description", 'pastore-church'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => esc_html__('Thumbs', 'pastore-church'),
						'list' => esc_html__('List', 'pastore-church')
					),
					"type" => "checklist"),
		
				"show_mode_buttons" => array(
					"title" => esc_html__('Show style buttons',  'pastore-church'),
					"desc" => esc_html__("Show buttons to allow visitors change list style", 'pastore-church'),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),

				"shop_loop_columns" => array(
					"title" => esc_html__('Shop columns',  'pastore-church'),
					"desc" => esc_html__("How many columns used to show products on shop page", 'pastore-church'),
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

				"show_currency" => array(
					"title" => esc_html__('Show currency selector', 'pastore-church'),
					"desc" => esc_html__('Show currency selector in the user menu', 'pastore-church'),
					"std" => "yes",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch"),
		
				"show_cart" => array(
					"title" => esc_html__('Show cart button', 'pastore-church'),
					"desc" => esc_html__('Show cart button in the user menu', 'pastore-church'),
					"std" => "shop",
					"options" => array(
						'hide'   => esc_html__('Hide', 'pastore-church'),
						'always' => esc_html__('Always', 'pastore-church'),
						'shop'   => esc_html__('Only on shop pages', 'pastore-church')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => esc_html__("Crop product's thumbnail",  'pastore-church'),
					"desc" => esc_html__("Crop product's thumbnails on search results page or scale it", 'pastore-church'),
					"std" => "no",
					"options" => pastore_church_get_options_param('list_yes_no'),
					"type" => "switch")
				
				)
			);

		}
	}
}

// WooCommerce hooks
if (!function_exists('pastore_church_woocommerce_theme_setup3')) {
	add_action( 'pastore_church_action_after_init_theme', 'pastore_church_woocommerce_theme_setup3' );
	function pastore_church_woocommerce_theme_setup3() {

		if (pastore_church_exists_woocommerce()) {

			add_action(    'woocommerce_before_subcategory_title',		'pastore_church_woocommerce_open_thumb_wrapper', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'pastore_church_woocommerce_open_thumb_wrapper', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'pastore_church_woocommerce_open_item_wrapper', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'pastore_church_woocommerce_open_item_wrapper', 20 );

			add_action(    'woocommerce_after_subcategory',				'pastore_church_woocommerce_close_item_wrapper', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'pastore_church_woocommerce_close_item_wrapper', 20 );

			add_action(    'woocommerce_after_shop_loop_item_title',	'pastore_church_woocommerce_after_shop_loop_item_title', 7);

			add_action(    'woocommerce_after_subcategory_title',		'pastore_church_woocommerce_after_subcategory_title', 10 );
		}

		if (pastore_church_is_woocommerce_page()) {
			
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar
			
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'pastore_church_woocommerce_wrapper_start', 10);
			
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'pastore_church_woocommerce_wrapper_end', 10);

			add_action(    'woocommerce_show_page_title',				'pastore_church_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);		
			add_action(    'woocommerce_single_product_summary',		'pastore_church_woocommerce_show_product_title', 5 );

			add_action(    'woocommerce_before_shop_loop', 				'pastore_church_woocommerce_before_shop_loop', 10 );

			remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 10 );
			add_action(    'woocommerce_after_shop_loop',				'pastore_church_woocommerce_pagination', 10 );

			add_action(    'woocommerce_product_meta_end',				'pastore_church_woocommerce_show_product_id', 10);

			add_filter(    'woocommerce_output_related_products_args',	'pastore_church_woocommerce_output_related_products_args' );
			
			add_filter(    'woocommerce_product_thumbnails_columns',	'pastore_church_woocommerce_product_thumbnails_columns' );

			add_filter(    'loop_shop_columns',							'pastore_church_woocommerce_loop_shop_columns' );

			add_filter(    'get_product_search_form',					'pastore_church_woocommerce_get_product_search_form' );

			add_filter(    'post_class',								'pastore_church_woocommerce_loop_shop_columns_class' );
			add_action(    'the_title',									'pastore_church_woocommerce_the_title');
			
			pastore_church_enqueue_popup();
		}
	}
}



// Check if WooCommerce installed and activated
if ( !function_exists( 'pastore_church_exists_woocommerce' ) ) {
	function pastore_church_exists_woocommerce() {
		return class_exists('Woocommerce');
		//return function_exists('is_woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'pastore_church_is_woocommerce_page' ) ) {
	function pastore_church_is_woocommerce_page() {
		$rez = false;
		if (pastore_church_exists_woocommerce()) {
			if (!pastore_church_storage_empty('pre_query')) {
				$id = pastore_church_storage_get_obj_property('pre_query', 'queried_object_id', 0);
				$rez = pastore_church_storage_call_obj_method('pre_query', 'get', 'post_type')=='product' 
						|| $id==wc_get_page_id('shop')
						|| $id==wc_get_page_id('cart')
						|| $id==wc_get_page_id('checkout')
						|| $id==wc_get_page_id('myaccount')
						|| pastore_church_storage_call_obj_method('pre_query', 'is_tax', 'product_cat')
						|| pastore_church_storage_call_obj_method('pre_query', 'is_tax', 'product_tag')
						|| pastore_church_storage_call_obj_method('pre_query', 'is_tax', get_object_taxonomies('product'));
						
			} else
				$rez = is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		}
		return $rez;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'pastore_church_woocommerce_detect_inheritance_key' ) ) {
	//add_filter('pastore_church_filter_detect_inheritance_key',	'pastore_church_woocommerce_detect_inheritance_key', 9, 1);
	function pastore_church_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return pastore_church_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'pastore_church_woocommerce_detect_template_page_id' ) ) {
	//add_filter('pastore_church_filter_detect_template_page_id',	'pastore_church_woocommerce_detect_template_page_id', 9, 2);
	function pastore_church_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'pastore_church_woocommerce_get_blog_type' ) ) {
	//add_filter('pastore_church_filter_get_blog_type',	'pastore_church_woocommerce_get_blog_type', 9, 2);
	function pastore_church_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		
		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'pastore_church_woocommerce_get_blog_title' ) ) {
	//add_filter('pastore_church_filter_get_blog_title',	'pastore_church_woocommerce_get_blog_title', 9, 2);
	function pastore_church_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		
		if ( pastore_church_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = esc_html__('Tag:', 'pastore-church') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = esc_html__( 'Your cart', 'pastore-church' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = esc_html__( 'Checkout', 'pastore-church' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = esc_html__( 'Account', 'pastore-church' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = pastore_church_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = pastore_church_get_post_title($page_id);
			} else {
				$title = esc_html__( 'Shop', 'pastore-church' );
			}
		}
		
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'pastore_church_woocommerce_get_stream_page_title' ) ) {
	//add_filter('pastore_church_filter_get_stream_page_title',	'pastore_church_woocommerce_get_stream_page_title', 9, 2);
	function pastore_church_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (pastore_church_strpos($page, 'woocommerce')!==false) {
			if (($page_id = pastore_church_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = pastore_church_get_post_title($page_id);
			else
				$title = esc_html__('Shop', 'pastore-church');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'pastore_church_woocommerce_get_stream_page_id' ) ) {
	//add_filter('pastore_church_filter_get_stream_page_id',	'pastore_church_woocommerce_get_stream_page_id', 9, 2);
	function pastore_church_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (pastore_church_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'pastore_church_woocommerce_get_stream_page_link' ) ) {
	//add_filter('pastore_church_filter_get_stream_page_link',	'pastore_church_woocommerce_get_stream_page_link', 9, 2);
	function pastore_church_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (pastore_church_strpos($page, 'woocommerce')!==false) {
			$id = pastore_church_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'pastore_church_woocommerce_get_current_taxonomy' ) ) {
	//add_filter('pastore_church_filter_get_current_taxonomy',	'pastore_church_woocommerce_get_current_taxonomy', 9, 2);
	function pastore_church_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( pastore_church_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'pastore_church_woocommerce_is_taxonomy' ) ) {
	//add_filter('pastore_church_filter_is_taxonomy',	'pastore_church_woocommerce_is_taxonomy', 9, 2);
	function pastore_church_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query!==null && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'pastore_church_woocommerce_orderby_need' ) ) {
	//add_filter('pastore_church_filter_orderby_need',	'pastore_church_woocommerce_orderby_need', 9, 1);
	function pastore_church_woocommerce_orderby_need($need) {
		if ($need == false || pastore_church_storage_empty('pre_query'))
			return $need;
		else {
			return pastore_church_storage_call_obj_method('pre_query', 'get', 'post_type')!='product' 
					&& pastore_church_storage_call_obj_method('pre_query', 'get', 'product_cat')==''
					&& pastore_church_storage_call_obj_method('pre_query', 'get', 'product_tag')=='';
		}
	}
}

// Add custom post type into list
if ( !function_exists( 'pastore_church_woocommerce_list_post_types' ) ) {
	//add_filter('pastore_church_filter_list_post_types', 	'pastore_church_woocommerce_list_post_types', 10, 1);
	function pastore_church_woocommerce_list_post_types($list) {
		$list['product'] = esc_html__('Products', 'pastore-church');
		return $list;
	}
}


	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'pastore_church_woocommerce_frontend_scripts' ) ) {
	//add_action( 'pastore_church_action_add_styles', 'pastore_church_woocommerce_frontend_scripts' );
	function pastore_church_woocommerce_frontend_scripts() {
		if (pastore_church_is_woocommerce_page() || pastore_church_get_custom_option('show_cart')=='always')
			if (file_exists(pastore_church_get_file_dir('css/plugin.woocommerce.css')))
				pastore_church_enqueue_style( 'pastore_church-plugin.woocommerce-style',  pastore_church_get_file_url('css/plugin.woocommerce.css'), array(), null );
	}
}

// Replace standard WooCommerce function
/*
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
	function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $post;
		if ( has_post_thumbnail() ) {
			$s = wc_get_image_size( $size );
			return pastore_church_get_resized_image_tag($post->ID, $s['width'], pastore_church_get_theme_option('crop_product_thumb')=='no' ? null :  $s['height']);
			//return get_the_post_thumbnail( $post->ID, array($s['width'], $s['height']) );
		} else if ( wc_placeholder_img_src() )
			return wc_placeholder_img( $size );
	}
}
*/

// Before main content
if ( !function_exists( 'pastore_church_woocommerce_wrapper_start' ) ) {
	//remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	//add_action('woocommerce_before_main_content', 'pastore_church_woocommerce_wrapper_start', 10);
	function pastore_church_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !pastore_church_storage_empty('shop_mode') ? pastore_church_storage_get('shop_mode') : 'thumbs'; ?>">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'pastore_church_woocommerce_wrapper_end' ) ) {
	//remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);		
	//add_action('woocommerce_after_main_content', 'pastore_church_woocommerce_wrapper_end', 10);
	function pastore_church_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'pastore_church_woocommerce_show_page_title' ) ) {
	//add_action('woocommerce_show_page_title', 'pastore_church_woocommerce_show_page_title', 10);
	function pastore_church_woocommerce_show_page_title($defa=true) {
		return pastore_church_get_custom_option('show_page_title')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'pastore_church_woocommerce_show_product_title' ) ) {
	//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);		
	//add_action( 'woocommerce_single_product_summary', 'pastore_church_woocommerce_show_product_title', 5 );
	function pastore_church_woocommerce_show_product_title() {
		if (pastore_church_get_custom_option('show_post_title')=='yes' || pastore_church_get_custom_option('show_page_title')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// Add list mode buttons
if ( !function_exists( 'pastore_church_woocommerce_before_shop_loop' ) ) {
	//add_action( 'woocommerce_before_shop_loop', 'pastore_church_woocommerce_before_shop_loop', 10 );
	function pastore_church_woocommerce_before_shop_loop() {
		if (pastore_church_get_custom_option('show_mode_buttons')=='yes') {
			echo '<div class="mode_buttons"><form action="' . esc_url(pastore_church_get_protocol().'://' . ($_SERVER["HTTP_HOST"]) . ($_SERVER["REQUEST_URI"])).'" method="post">'
				. '<input type="hidden" name="pastore_church_shop_mode" value="'.esc_attr(pastore_church_storage_get('shop_mode')).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr__('Show products as thumbs', 'pastore-church').'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr__('Show products as list', 'pastore-church').'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'pastore_church_woocommerce_open_thumb_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'pastore_church_woocommerce_open_thumb_wrapper', 9 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'pastore_church_woocommerce_open_thumb_wrapper', 9 );
	function pastore_church_woocommerce_open_thumb_wrapper($cat='') {
		pastore_church_storage_set('in_product_item', true);
		?>
		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<a class="hover_icon hover_icon_link" href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'pastore_church_woocommerce_open_item_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'pastore_church_woocommerce_open_item_wrapper', 20 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'pastore_church_woocommerce_open_item_wrapper', 20 );
	function pastore_church_woocommerce_open_item_wrapper($cat='') {
		?>
				</a>
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'pastore_church_woocommerce_close_item_wrapper' ) ) {
	//add_action( 'woocommerce_after_subcategory', 'pastore_church_woocommerce_close_item_wrapper', 20 );
	//add_action( 'woocommerce_after_shop_loop_item', 'pastore_church_woocommerce_close_item_wrapper', 20 );
	function pastore_church_woocommerce_close_item_wrapper($cat='') {
		?>
			</div>
		</div>
		<?php
		pastore_church_storage_set('in_product_item', false);
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'pastore_church_woocommerce_after_shop_loop_item_title' ) ) {
	//add_action( 'woocommerce_after_shop_loop_item_title', 'pastore_church_woocommerce_after_shop_loop_item_title', 7);
	function pastore_church_woocommerce_after_shop_loop_item_title() {
		if (pastore_church_storage_get('shop_mode') == 'list') {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'pastore_church_woocommerce_after_subcategory_title' ) ) {
	//add_action( 'woocommerce_after_subcategory_title', 'pastore_church_woocommerce_after_subcategory_title', 10 );
	function pastore_church_woocommerce_after_subcategory_title($category) {
		if (pastore_church_storage_get('shop_mode') == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'pastore_church_woocommerce_show_product_id' ) ) {
	//add_action( 'woocommerce_product_meta_end', 'pastore_church_woocommerce_show_product_id', 10);
	function pastore_church_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'pastore-church') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'pastore_church_woocommerce_output_related_products_args' ) ) {
	//add_filter( 'woocommerce_output_related_products_args', 'pastore_church_woocommerce_output_related_products_args' );
	function pastore_church_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (pastore_church_param_is_on(pastore_church_get_custom_option('show_post_related'))) {
			$ccc_add = in_array(pastore_church_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  pastore_church_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (pastore_church_param_is_off(pastore_church_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$ppp = pastore_church_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'pastore_church_woocommerce_product_thumbnails_columns' ) ) {
	//add_filter( 'woocommerce_product_thumbnails_columns', 'pastore_church_woocommerce_product_thumbnails_columns' );
	function pastore_church_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'pastore_church_woocommerce_loop_shop_columns_class' ) ) {
	//add_filter( 'post_class', 'pastore_church_woocommerce_loop_shop_columns_class' );
	function pastore_church_woocommerce_loop_shop_columns_class($class) {
		global $woocommerce_loop;
		if (is_product()) {
			if (!empty($woocommerce_loop['columns']))
			$class[] = ' column-1_'.esc_attr($woocommerce_loop['columns']);
		} else if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
			$ccc_add = in_array(pastore_church_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  pastore_church_get_custom_option('shop_loop_columns');
			$ccc = $ccc > 0 ? $ccc : (pastore_church_param_is_off(pastore_church_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$class[] = ' column-1_'.esc_attr($ccc);
		}
		return $class;
	}
}

// Number columns for shop streampage
if ( !function_exists( 'pastore_church_woocommerce_loop_shop_columns' ) ) {
	//add_filter( 'loop_shop_columns', 'pastore_church_woocommerce_loop_shop_columns' );
	function pastore_church_woocommerce_loop_shop_columns($cols) {
		$ccc_add = in_array(pastore_church_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
		$ccc =  pastore_church_get_custom_option('shop_loop_columns');
		$ccc = $ccc > 0 ? $ccc : (pastore_church_param_is_off(pastore_church_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
		return $ccc;
	}
}

// Search form
if ( !function_exists( 'pastore_church_woocommerce_get_product_search_form' ) ) {
	//add_filter( 'get_product_search_form', 'pastore_church_woocommerce_get_product_search_form' );
	function pastore_church_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'pastore-church') . '" value="' . get_search_query() . '" name="s" title="' . esc_attr__('Search for products:', 'pastore-church') . '" /><button class="search_button icon-search" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'pastore_church_woocommerce_the_title' ) ) {
	//add_filter( 'the_title', 'pastore_church_woocommerce_the_title' );
	function pastore_church_woocommerce_the_title($title) {
		if (pastore_church_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.get_permalink().'">'.($title).'</a>';
		}
		return $title;
	}
}

// Show pagination links
if ( !function_exists( 'pastore_church_woocommerce_pagination' ) ) {
	//add_filter( 'woocommerce_after_shop_loop', 'pastore_church_woocommerce_pagination', 10 );
	function pastore_church_woocommerce_pagination() {
		$style = pastore_church_get_custom_option('blog_pagination');
		pastore_church_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr($style),
			'style' => $style,
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => $style=='pages' ? 10 : 20
			)
		);
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pastore_church_woocommerce_required_plugins' ) ) {
	//add_filter('pastore_church_filter_required_plugins',	'pastore_church_woocommerce_required_plugins');
	function pastore_church_woocommerce_required_plugins($list=array()) {
		if (in_array('woocommerce', pastore_church_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check WooC in the required plugins
if ( !function_exists( 'pastore_church_woocommerce_importer_required_plugins' ) ) {
	//add_filter( 'pastore_church_filter_importer_required_plugins',	'pastore_church_woocommerce_importer_required_plugins', 10, 2 );
	function pastore_church_woocommerce_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('woocommerce', pastore_church_storage_get('required_plugins')) && !pastore_church_exists_woocommerce() )
		if (pastore_church_strpos($list, 'woocommerce')!==false && !pastore_church_exists_woocommerce() )
			$not_installed .= '<br>WooCommerce';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'pastore_church_woocommerce_importer_set_options' ) ) {
	//add_filter( 'pastore_church_filter_importer_options',	'pastore_church_woocommerce_importer_set_options' );
	function pastore_church_woocommerce_importer_set_options($options=array()) {
		if ( in_array('woocommerce', pastore_church_storage_get('required_plugins')) && pastore_church_exists_woocommerce() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_woocommerce'] = str_replace('posts', 'woocommerce', $v['file_with_posts']);
				}
			}
			// Add slugs to export options for this plugin
			$options['additional_options'][]	= 'shop_%';
			$options['additional_options'][]	= 'woocommerce_%';
		}
		return $options;
	}
}

// Setup WooC pages after import posts complete
if ( !function_exists( 'pastore_church_woocommerce_importer_after_import_posts' ) ) {
	//add_action( 'pastore_church_action_importer_after_import_posts',	'pastore_church_woocommerce_importer_after_import_posts', 10, 1 );
	function pastore_church_woocommerce_importer_after_import_posts($importer) {
		$wooc_pages = array(						// Options slugs and pages titles for WooCommerce pages
			'woocommerce_shop_page_id' 				=> 'Shop',
			'woocommerce_cart_page_id' 				=> 'Cart',
			'woocommerce_checkout_page_id' 			=> 'Checkout',
			'woocommerce_pay_page_id' 				=> 'Checkout &#8594; Pay',
			'woocommerce_thanks_page_id' 			=> 'Order Received',
			'woocommerce_myaccount_page_id' 		=> 'My Account',
			'woocommerce_edit_address_page_id'		=> 'Edit My Address',
			'woocommerce_view_order_page_id'		=> 'View Order',
			'woocommerce_change_password_page_id'	=> 'Change Password',
			'woocommerce_logout_page_id'			=> 'Logout',
			'woocommerce_lost_password_page_id'		=> 'Lost Password'
		);
		foreach ($wooc_pages as $woo_page_name => $woo_page_title) {
			$woopage = get_page_by_title( $woo_page_title );
			if ($woopage->ID) {
				update_option($woo_page_name, $woopage->ID);
			}
		}
		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'pastore_church_woocommerce_importer_show_params' ) ) {
	//add_action( 'pastore_church_action_importer_params',	'pastore_church_woocommerce_importer_show_params', 10, 1 );
	function pastore_church_woocommerce_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('woocommerce', pastore_church_storage_get('required_plugins')) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_woocommerce" id="import_woocommerce" /> <label for="import_woocommerce"><?php esc_html_e('Import WooCommerce', 'pastore-church'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'pastore_church_woocommerce_importer_import' ) ) {
	//add_action( 'pastore_church_action_importer_import',	'pastore_church_woocommerce_importer_import', 10, 2 );
	function pastore_church_woocommerce_importer_import($importer, $action) {
		if ( $action == 'import_woocommerce' ) {
			$importer->response['result'] = $importer->import_dump('woocommerce', esc_html__('WooCommerce meta', 'pastore-church'));
		}
	}
}

// Display import progress
if ( !function_exists( 'pastore_church_woocommerce_importer_import_fields' ) ) {
	//add_action( 'pastore_church_action_importer_import_fields',	'pastore_church_woocommerce_importer_import_fields', 10, 1 );
	function pastore_church_woocommerce_importer_import_fields($importer) {
		?>
		<tr class="import_woocommerce">
			<td class="import_progress_item"><?php esc_html_e('WooCommerce meta', 'pastore-church'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'pastore_church_woocommerce_importer_export' ) ) {
	//add_action( 'pastore_church_action_importer_export',	'pastore_church_woocommerce_importer_export', 10, 1 );
	function pastore_church_woocommerce_importer_export($importer) {
		pastore_church_storage_set('export_woocommerce', serialize( array(
			"woocommerce_attribute_taxomonies"				=> $importer->export_dump("woocommerce_attribute_taxomonies"),
			"woocommerce_downloadable_product_permissions"	=> $importer->export_dump("woocommerce_downloadable_product_permissions"),
            "woocommerce_order_itemmeta"					=> $importer->export_dump("woocommerce_order_itemmeta"),
            "woocommerce_order_items"						=> $importer->export_dump("woocommerce_order_items"),
            "woocommerce_termmeta"							=> $importer->export_dump("woocommerce_termmeta")
            ) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'pastore_church_woocommerce_importer_export_fields' ) ) {
	//add_action( 'pastore_church_action_importer_export_fields',	'pastore_church_woocommerce_importer_export_fields', 10, 1 );
	function pastore_church_woocommerce_importer_export_fields($importer) {
		?>
		<tr>
			<th align="left"><?php esc_html_e('WooCommerce', 'pastore-church'); ?></th>
			<td><?php pastore_church_fpc(pastore_church_get_file_dir('core/core.importer/export/woocommerce.txt'), pastore_church_storage_get('export_woocommerce')); ?>
				<a download="woocommerce.txt" href="<?php echo esc_url(pastore_church_get_file_url('core/core.importer/export/woocommerce.txt')); ?>"><?php esc_html_e('Download', 'pastore-church'); ?></a>
			</td>
		</tr>
		<?php
	}
}



// Register shortcodes to the internal builder
//------------------------------------------------------------------------
if ( !function_exists( 'pastore_church_woocommerce_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_woocommerce_reg_shortcodes', 20);
	function pastore_church_woocommerce_reg_shortcodes() {

		// WooCommerce - Cart
		pastore_church_sc_map("woocommerce_cart", array(
			"title" => esc_html__("Woocommerce: Cart", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Cart page", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Checkout
		pastore_church_sc_map("woocommerce_checkout", array(
			"title" => esc_html__("Woocommerce: Checkout", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Checkout page", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - My Account
		pastore_church_sc_map("woocommerce_my_account", array(
			"title" => esc_html__("Woocommerce: My Account", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show My Account page", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Order Tracking
		pastore_church_sc_map("woocommerce_order_tracking", array(
			"title" => esc_html__("Woocommerce: Order Tracking", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Order Tracking page", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Shop Messages
		pastore_church_sc_map("shop_messages", array(
			"title" => esc_html__("Woocommerce: Shop Messages", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show shop messages", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Product Page
		pastore_church_sc_map("product_page", array(
			"title" => esc_html__("Woocommerce: Product Page", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display single product page", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'pastore-church'),
					"desc" => wp_kses_data( __("SKU code of displayed product", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'pastore-church'),
					"desc" => wp_kses_data( __("ID of displayed product", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many products showed", 'pastore-church') ),
					"value" => "1",
					"min" => 1,
					"type" => "spinner"
				),
				"post_type" => array(
					"title" => esc_html__("Post type", 'pastore-church'),
					"desc" => wp_kses_data( __("Post type for the WP query (leave 'product')", 'pastore-church') ),
					"value" => "product",
					"type" => "text"
				),
				"post_status" => array(
					"title" => esc_html__("Post status", 'pastore-church'),
					"desc" => wp_kses_data( __("Display posts only with this status", 'pastore-church') ),
					"value" => "publish",
					"type" => "select",
					"options" => array(
						"publish" => esc_html__('Publish', 'pastore-church'),
						"protected" => esc_html__('Protected', 'pastore-church'),
						"private" => esc_html__('Private', 'pastore-church'),
						"pending" => esc_html__('Pending', 'pastore-church'),
						"draft" => esc_html__('Draft', 'pastore-church')
						)
					)
				)
			)
		);
		
		// WooCommerce - Product
		pastore_church_sc_map("product", array(
			"title" => esc_html__("Woocommerce: Product", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display one product", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'pastore-church'),
					"desc" => wp_kses_data( __("SKU code of displayed product", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'pastore-church'),
					"desc" => wp_kses_data( __("ID of displayed product", 'pastore-church') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Best Selling Products
		pastore_church_sc_map("best_selling_products", array(
			"title" => esc_html__("Woocommerce: Best Selling Products", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show best selling products", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many products showed", 'pastore-church') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
					)
				)
			)
		);
		
		// WooCommerce - Recent Products
		pastore_church_sc_map("recent_products", array(
			"title" => esc_html__("Woocommerce: Recent Products", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many products showed", 'pastore-church') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pastore-church'),
						"title" => esc_html__('Title', 'pastore-church')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pastore_church_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Related Products
		pastore_church_sc_map("related_products", array(
			"title" => esc_html__("Woocommerce: Related Products", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show related products", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many products showed", 'pastore-church') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pastore-church'),
						"title" => esc_html__('Title', 'pastore-church')
						)
					)
				)
			)
		);
		
		// WooCommerce - Featured Products
		pastore_church_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Featured Products", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many products showed", 'pastore-church') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pastore-church'),
						"title" => esc_html__('Title', 'pastore-church')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pastore_church_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Top Rated Products
		pastore_church_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Top Rated Products", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many products showed", 'pastore-church') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pastore-church'),
						"title" => esc_html__('Title', 'pastore-church')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pastore_church_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Sale Products
		pastore_church_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Sale Products", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products on sale", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many products showed", 'pastore-church') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pastore-church'),
						"title" => esc_html__('Title', 'pastore-church')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pastore_church_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product Category
		pastore_church_sc_map("product_category", array(
			"title" => esc_html__("Woocommerce: Products from category", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many products showed", 'pastore-church') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pastore-church'),
						"title" => esc_html__('Title', 'pastore-church')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pastore_church_get_sc_param('ordering')
				),
				"category" => array(
					"title" => esc_html__("Categories", 'pastore-church'),
					"desc" => wp_kses_data( __("Comma separated category slugs", 'pastore-church') ),
					"value" => '',
					"type" => "text"
				),
				"operator" => array(
					"title" => esc_html__("Operator", 'pastore-church'),
					"desc" => wp_kses_data( __("Categories operator", 'pastore-church') ),
					"value" => "IN",
					"type" => "checklist",
					"size" => "medium",
					"options" => array(
						"IN" => esc_html__('IN', 'pastore-church'),
						"NOT IN" => esc_html__('NOT IN', 'pastore-church'),
						"AND" => esc_html__('AND', 'pastore-church')
						)
					)
				)
			)
		);
		
		// WooCommerce - Products
		pastore_church_sc_map("products", array(
			"title" => esc_html__("Woocommerce: Products", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list all products", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"skus" => array(
					"title" => esc_html__("SKUs", 'pastore-church'),
					"desc" => wp_kses_data( __("Comma separated SKU codes of products", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'pastore-church'),
					"desc" => wp_kses_data( __("Comma separated ID of products", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pastore-church'),
						"title" => esc_html__('Title', 'pastore-church')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pastore_church_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product attribute
		pastore_church_sc_map("product_attribute", array(
			"title" => esc_html__("Woocommerce: Products by Attribute", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many products showed", 'pastore-church') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pastore-church'),
						"title" => esc_html__('Title', 'pastore-church')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pastore_church_get_sc_param('ordering')
				),
				"attribute" => array(
					"title" => esc_html__("Attribute", 'pastore-church'),
					"desc" => wp_kses_data( __("Attribute name", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"filter" => array(
					"title" => esc_html__("Filter", 'pastore-church'),
					"desc" => wp_kses_data( __("Attribute value", 'pastore-church') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Products Categories
		pastore_church_sc_map("product_categories", array(
			"title" => esc_html__("Woocommerce: Product Categories", 'pastore-church'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"number" => array(
					"title" => esc_html__("Number", 'pastore-church'),
					"desc" => wp_kses_data( __("How many categories showed", 'pastore-church') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'pastore-church'),
					"desc" => wp_kses_data( __("How many columns per row use for categories output", 'pastore-church') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'pastore-church'),
						"title" => esc_html__('Title', 'pastore-church')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'pastore-church'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => pastore_church_get_sc_param('ordering')
				),
				"parent" => array(
					"title" => esc_html__("Parent", 'pastore-church'),
					"desc" => wp_kses_data( __("Parent category slug", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'pastore-church'),
					"desc" => wp_kses_data( __("Comma separated ID of products", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"hide_empty" => array(
					"title" => esc_html__("Hide empty", 'pastore-church'),
					"desc" => wp_kses_data( __("Hide empty categories", 'pastore-church') ),
					"value" => "yes",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
					)
				)
			)
		);
	}
}



// Register shortcodes to the VC builder
//------------------------------------------------------------------------
if ( !function_exists( 'pastore_church_woocommerce_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_woocommerce_reg_shortcodes_vc');
	function pastore_church_woocommerce_reg_shortcodes_vc() {
	
		if (false && function_exists('pastore_church_exists_woocommerce') && pastore_church_exists_woocommerce()) {
		
			// WooCommerce - Cart
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_cart",
				"name" => esc_html__("Cart", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show cart page", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_wooc_cart',
				"class" => "trx_sc_alone trx_sc_woocommerce_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'pastore-church'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'pastore-church') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Cart extends PASTORE_CHURCH_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Checkout
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_checkout",
				"name" => esc_html__("Checkout", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show checkout page", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_wooc_checkout',
				"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'pastore-church'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'pastore-church') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Checkout extends PASTORE_CHURCH_VC_ShortCodeAlone {}
		
		
			// WooCommerce - My Account
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_my_account",
				"name" => esc_html__("My Account", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show my account page", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_wooc_my_account',
				"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'pastore-church'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'pastore-church') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_My_Account extends PASTORE_CHURCH_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Order Tracking
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_order_tracking",
				"name" => esc_html__("Order Tracking", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show order tracking page", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_wooc_order_tracking',
				"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'pastore-church'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'pastore-church') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Order_Tracking extends PASTORE_CHURCH_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Shop Messages
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "shop_messages",
				"name" => esc_html__("Shop Messages", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show shop messages", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_wooc_shop_messages',
				"class" => "trx_sc_alone trx_sc_shop_messages",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'pastore-church'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'pastore-church') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Shop_Messages extends PASTORE_CHURCH_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Product Page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_page",
				"name" => esc_html__("Product Page", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display single product page", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_product_page',
				"class" => "trx_sc_single trx_sc_product_page",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'pastore-church'),
						"description" => wp_kses_data( __("SKU code of displayed product", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'pastore-church'),
						"description" => wp_kses_data( __("ID of displayed product", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many products showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", 'pastore-church'),
						"description" => wp_kses_data( __("Post type for the WP query (leave 'product')", 'pastore-church') ),
						"class" => "",
						"value" => "product",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_status",
						"heading" => esc_html__("Post status", 'pastore-church'),
						"description" => wp_kses_data( __("Display posts only with this status", 'pastore-church') ),
						"class" => "",
						"value" => array(
							esc_html__('Publish', 'pastore-church') => 'publish',
							esc_html__('Protected', 'pastore-church') => 'protected',
							esc_html__('Private', 'pastore-church') => 'private',
							esc_html__('Pending', 'pastore-church') => 'pending',
							esc_html__('Draft', 'pastore-church') => 'draft'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Page extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product",
				"name" => esc_html__("Product", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display one product", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_product',
				"class" => "trx_sc_single trx_sc_product",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'pastore-church'),
						"description" => wp_kses_data( __("Product's SKU code", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'pastore-church'),
						"description" => wp_kses_data( __("Product's ID", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
			// WooCommerce - Best Selling Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "best_selling_products",
				"name" => esc_html__("Best Selling Products", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show best selling products", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_best_selling_products',
				"class" => "trx_sc_single trx_sc_best_selling_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many products showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Best_Selling_Products extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Recent Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "recent_products",
				"name" => esc_html__("Recent Products", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_recent_products',
				"class" => "trx_sc_single trx_sc_recent_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many products showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"

					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pastore-church') => 'date',
							esc_html__('Title', 'pastore-church') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Recent_Products extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Related Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "related_products",
				"name" => esc_html__("Related Products", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show related products", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_related_products',
				"class" => "trx_sc_single trx_sc_related_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many products showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pastore-church') => 'date',
							esc_html__('Title', 'pastore-church') => 'title'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Related_Products extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Featured Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "featured_products",
				"name" => esc_html__("Featured Products", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_featured_products',
				"class" => "trx_sc_single trx_sc_featured_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many products showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pastore-church') => 'date',
							esc_html__('Title', 'pastore-church') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Featured_Products extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Top Rated Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "top_rated_products",
				"name" => esc_html__("Top Rated Products", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_top_rated_products',
				"class" => "trx_sc_single trx_sc_top_rated_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many products showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pastore-church') => 'date',
							esc_html__('Title', 'pastore-church') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Top_Rated_Products extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Sale Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "sale_products",
				"name" => esc_html__("Sale Products", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products on sale", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_sale_products',
				"class" => "trx_sc_single trx_sc_sale_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many products showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pastore-church') => 'date',
							esc_html__('Title', 'pastore-church') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Sale_Products extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product Category
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_category",
				"name" => esc_html__("Products from category", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_product_category',
				"class" => "trx_sc_single trx_sc_product_category",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many products showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pastore-church') => 'date',
							esc_html__('Title', 'pastore-church') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "category",
						"heading" => esc_html__("Categories", 'pastore-church'),
						"description" => wp_kses_data( __("Comma separated category slugs", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "operator",
						"heading" => esc_html__("Operator", 'pastore-church'),
						"description" => wp_kses_data( __("Categories operator", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('IN', 'pastore-church') => 'IN',
							esc_html__('NOT IN', 'pastore-church') => 'NOT IN',
							esc_html__('AND', 'pastore-church') => 'AND'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Category extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "products",
				"name" => esc_html__("Products", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list all products", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_products',
				"class" => "trx_sc_single trx_sc_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "skus",
						"heading" => esc_html__("SKUs", 'pastore-church'),
						"description" => wp_kses_data( __("Comma separated SKU codes of products", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'pastore-church'),
						"description" => wp_kses_data( __("Comma separated ID of products", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pastore-church') => 'date',
							esc_html__('Title', 'pastore-church') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Products extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
		
			// WooCommerce - Product Attribute
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_attribute",
				"name" => esc_html__("Products by Attribute", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_product_attribute',
				"class" => "trx_sc_single trx_sc_product_attribute",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many products showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pastore-church') => 'date',
							esc_html__('Title', 'pastore-church') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "attribute",
						"heading" => esc_html__("Attribute", 'pastore-church'),
						"description" => wp_kses_data( __("Attribute name", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "filter",
						"heading" => esc_html__("Filter", 'pastore-church'),
						"description" => wp_kses_data( __("Attribute value", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Attribute extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products Categories
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_categories",
				"name" => esc_html__("Product Categories", 'pastore-church'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'pastore-church') ),
				"category" => esc_html__('WooCommerce', 'pastore-church'),
				'icon' => 'icon_trx_product_categories',
				"class" => "trx_sc_single trx_sc_product_categories",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number", 'pastore-church'),
						"description" => wp_kses_data( __("How many categories showed", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'pastore-church'),
						"description" => wp_kses_data( __("How many columns per row use for categories output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'pastore-church') => 'date',
							esc_html__('Title', 'pastore-church') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'pastore-church'),
						"description" => wp_kses_data( __("Sorting order for products output", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(pastore_church_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "parent",
						"heading" => esc_html__("Parent", 'pastore-church'),
						"description" => wp_kses_data( __("Parent category slug", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "date",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'pastore-church'),
						"description" => wp_kses_data( __("Comma separated ID of products", 'pastore-church') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "hide_empty",
						"heading" => esc_html__("Hide empty", 'pastore-church'),
						"description" => wp_kses_data( __("Hide empty categories", 'pastore-church') ),
						"class" => "",
						"value" => array("Hide empty" => "1" ),
						"type" => "checkbox"
					)
				)
			) );
			
			class WPBakeryShortCode_Products_Categories extends PASTORE_CHURCH_VC_ShortCodeSingle {}
		
		}
	}
}
?>