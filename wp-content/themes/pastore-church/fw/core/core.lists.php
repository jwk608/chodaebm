<?php
/**
 * Pastore Church Framework: return lists
 *
 * @package pastore_church
 * @since pastore_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'pastore_church_get_list_styles' ) ) {
	function pastore_church_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'pastore-church'), $i);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'pastore_church_get_list_margins' ) ) {
	function pastore_church_get_list_margins($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'pastore-church'),
				'tiny'		=> esc_html__('Tiny',		'pastore-church'),
				'small'		=> esc_html__('Small',		'pastore-church'),
				'medium'	=> esc_html__('Medium',		'pastore-church'),
				'large'		=> esc_html__('Large',		'pastore-church'),
				'huge'		=> esc_html__('Huge',		'pastore-church'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'pastore-church'),
				'small-'	=> esc_html__('Small (negative)',	'pastore-church'),
				'medium-'	=> esc_html__('Medium (negative)',	'pastore-church'),
				'large-'	=> esc_html__('Large (negative)',	'pastore-church'),
				'huge-'		=> esc_html__('Huge (negative)',	'pastore-church')
				);
			$list = apply_filters('pastore_church_filter_list_margins', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'pastore_church_get_list_animations' ) ) {
	function pastore_church_get_list_animations($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'pastore-church'),
				'bounced'		=> esc_html__('Bounced',		'pastore-church'),
				'flash'			=> esc_html__('Flash',		'pastore-church'),
				'flip'			=> esc_html__('Flip',		'pastore-church'),
				'pulse'			=> esc_html__('Pulse',		'pastore-church'),
				'rubberBand'	=> esc_html__('Rubber Band',	'pastore-church'),
				'shake'			=> esc_html__('Shake',		'pastore-church'),
				'swing'			=> esc_html__('Swing',		'pastore-church'),
				'tada'			=> esc_html__('Tada',		'pastore-church'),
				'wobble'		=> esc_html__('Wobble',		'pastore-church')
				);
			$list = apply_filters('pastore_church_filter_list_animations', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'pastore_church_get_list_line_styles' ) ) {
	function pastore_church_get_list_line_styles($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'pastore-church'),
				'dashed'=> esc_html__('Dashed', 'pastore-church'),
				'dotted'=> esc_html__('Dotted', 'pastore-church'),
				'double'=> esc_html__('Double', 'pastore-church'),
				'image'	=> esc_html__('Image', 'pastore-church')
				);
			$list = apply_filters('pastore_church_filter_list_line_styles', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'pastore_church_get_list_animations_in' ) ) {
	function pastore_church_get_list_animations_in($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'pastore-church'),
				'bounceIn'			=> esc_html__('Bounce In',			'pastore-church'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'pastore-church'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'pastore-church'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'pastore-church'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'pastore-church'),
				'fadeIn'			=> esc_html__('Fade In',			'pastore-church'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'pastore-church'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'pastore-church'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'pastore-church'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'pastore-church'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'pastore-church'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'pastore-church'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'pastore-church'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'pastore-church'),
				'flipInX'			=> esc_html__('Flip In X',			'pastore-church'),
				'flipInY'			=> esc_html__('Flip In Y',			'pastore-church'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'pastore-church'),
				'rotateIn'			=> esc_html__('Rotate In',			'pastore-church'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','pastore-church'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'pastore-church'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'pastore-church'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','pastore-church'),
				'rollIn'			=> esc_html__('Roll In',			'pastore-church'),
				'slideInUp'			=> esc_html__('Slide In Up',		'pastore-church'),
				'slideInDown'		=> esc_html__('Slide In Down',		'pastore-church'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'pastore-church'),
				'slideInRight'		=> esc_html__('Slide In Right',		'pastore-church'),
				'zoomIn'			=> esc_html__('Zoom In',			'pastore-church'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'pastore-church'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'pastore-church'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'pastore-church'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'pastore-church')
				);
			$list = apply_filters('pastore_church_filter_list_animations_in', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'pastore_church_get_list_animations_out' ) ) {
	function pastore_church_get_list_animations_out($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',	'pastore-church'),
				'bounceOut'			=> esc_html__('Bounce Out',			'pastore-church'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'pastore-church'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'pastore-church'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'pastore-church'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'pastore-church'),
				'fadeOut'			=> esc_html__('Fade Out',			'pastore-church'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'pastore-church'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'pastore-church'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'pastore-church'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'pastore-church'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'pastore-church'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'pastore-church'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'pastore-church'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'pastore-church'),
				'flipOutX'			=> esc_html__('Flip Out X',			'pastore-church'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'pastore-church'),
				'hinge'				=> esc_html__('Hinge Out',			'pastore-church'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'pastore-church'),
				'rotateOut'			=> esc_html__('Rotate Out',			'pastore-church'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'pastore-church'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'pastore-church'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'pastore-church'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'pastore-church'),
				'rollOut'			=> esc_html__('Roll Out',		'pastore-church'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'pastore-church'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'pastore-church'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'pastore-church'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'pastore-church'),
				'zoomOut'			=> esc_html__('Zoom Out',			'pastore-church'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'pastore-church'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'pastore-church'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'pastore-church'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'pastore-church')
				);
			$list = apply_filters('pastore_church_filter_list_animations_out', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('pastore_church_get_animation_classes')) {
	function pastore_church_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return pastore_church_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!pastore_church_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'pastore_church_get_list_categories' ) ) {
	function pastore_church_get_list_categories($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'pastore_church_get_list_terms' ) ) {
	function pastore_church_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = pastore_church_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = pastore_church_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'pastore_church_get_list_posts_types' ) ) {
	function pastore_church_get_list_posts_types($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_posts_types'))=='') {
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = esc_html__('Post', 'pastore-church');
			if (is_array($types) && count($types) > 0) {
				foreach ($types as $t) {
					if ($t == 'post') continue;
					$list[$t] = pastore_church_strtoproper($t);
				}
			}
			*/
			// Return only theme inheritance supported post types
			$list = apply_filters('pastore_church_filter_list_post_types', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'pastore_church_get_list_posts' ) ) {
	function pastore_church_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = pastore_church_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'pastore-church');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set($hash, $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'pastore_church_get_list_pages' ) ) {
	function pastore_church_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return pastore_church_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'pastore_church_get_list_users' ) ) {
	function pastore_church_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = pastore_church_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'pastore-church');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_users', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'pastore_church_get_list_sliders' ) ) {
	function pastore_church_get_list_sliders($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'pastore-church')
			);
			$list = apply_filters('pastore_church_filter_list_sliders', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'pastore_church_get_list_slider_controls' ) ) {
	function pastore_church_get_list_slider_controls($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'pastore-church'),
				'side'		=> esc_html__('Side', 'pastore-church'),
				'bottom'	=> esc_html__('Bottom', 'pastore-church'),
				'pagination'=> esc_html__('Pagination', 'pastore-church')
				);
			$list = apply_filters('pastore_church_filter_list_slider_controls', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'pastore_church_get_slider_controls_classes' ) ) {
	function pastore_church_get_slider_controls_classes($controls) {
		if (pastore_church_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'pastore_church_get_list_popup_engines' ) ) {
	function pastore_church_get_list_popup_engines($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'pastore-church'),
				"magnific"	=> esc_html__("Magnific popup", 'pastore-church')
				);
			$list = apply_filters('pastore_church_filter_list_popup_engines', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_menus' ) ) {
	function pastore_church_get_list_menus($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'pastore-church');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'pastore_church_get_list_sidebars' ) ) {
	function pastore_church_get_list_sidebars($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_sidebars'))=='') {
			if (($list = pastore_church_storage_get('registered_sidebars'))=='') $list = array();
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'pastore_church_get_list_sidebars_positions' ) ) {
	function pastore_church_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'pastore-church'),
				'left'  => esc_html__('Left',  'pastore-church'),
				'right' => esc_html__('Right', 'pastore-church')
				);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'pastore_church_get_sidebar_class' ) ) {
	function pastore_church_get_sidebar_class() {
		$sb_main = pastore_church_get_custom_option('show_sidebar_main');
		$sb_outer = false;
		return (pastore_church_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (pastore_church_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_body_styles' ) ) {
	function pastore_church_get_list_body_styles($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'pastore-church'),
				'wide'	=> esc_html__('Wide',		'pastore-church')
				);
			if (pastore_church_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'pastore-church');
				$list['fullscreen']	= esc_html__('Fullscreen',	'pastore-church');
			}
			$list = apply_filters('pastore_church_filter_list_body_styles', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_skins' ) ) {
	function pastore_church_get_list_skins($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_skins'))=='') {
			$list = pastore_church_get_list_folders("skins");
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_skins', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'pastore_church_get_list_themes' ) ) {
	function pastore_church_get_list_themes($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_themes'))=='') {
			$list = pastore_church_get_list_files("css/themes");
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_themes', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_templates' ) ) {
	function pastore_church_get_list_templates($mode='') {
		if (($list = pastore_church_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = pastore_church_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: pastore_church_strtoproper($v['layout'])
										);
				}
			}
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_templates_blog' ) ) {
	function pastore_church_get_list_templates_blog($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_templates_blog'))=='') {
			$list = pastore_church_get_list_templates('blog');
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_templates_blogger' ) ) {
	function pastore_church_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_templates_blogger'))=='') {
			$list = pastore_church_array_merge(pastore_church_get_list_templates('blogger'), pastore_church_get_list_templates('blog'));
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_templates_single' ) ) {
	function pastore_church_get_list_templates_single($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_templates_single'))=='') {
			$list = pastore_church_get_list_templates('single');
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_templates_header' ) ) {
	function pastore_church_get_list_templates_header($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_templates_header'))=='') {
			$list = pastore_church_get_list_templates('header');
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_templates_forms' ) ) {
	function pastore_church_get_list_templates_forms($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_templates_forms'))=='') {
			$list = pastore_church_get_list_templates('forms');
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_article_styles' ) ) {
	function pastore_church_get_list_article_styles($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'pastore-church'),
				"stretch" => esc_html__('Stretch', 'pastore-church')
				);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_post_formats_filters' ) ) {
	function pastore_church_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'pastore-church'),
				"thumbs"  => esc_html__('With thumbs', 'pastore-church'),
				"reviews" => esc_html__('With reviews', 'pastore-church'),
				"video"   => esc_html__('With videos', 'pastore-church'),
				"audio"   => esc_html__('With audios', 'pastore-church'),
				"gallery" => esc_html__('With galleries', 'pastore-church')
				);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_portfolio_filters' ) ) {
	function pastore_church_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'pastore-church'),
				"tags"		=> esc_html__('Tags', 'pastore-church'),
				"categories"=> esc_html__('Categories', 'pastore-church')
				);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_hovers' ) ) {
	function pastore_church_get_list_hovers($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'pastore-church');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'pastore-church');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'pastore-church');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'pastore-church');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'pastore-church');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'pastore-church');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'pastore-church');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'pastore-church');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'pastore-church');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'pastore-church');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'pastore-church');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'pastore-church');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'pastore-church');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'pastore-church');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'pastore-church');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'pastore-church');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'pastore-church');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'pastore-church');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'pastore-church');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'pastore-church');
			$list['square effect1']  = esc_html__('Square Effect 1',  'pastore-church');
			$list['square effect2']  = esc_html__('Square Effect 2',  'pastore-church');
			$list['square effect3']  = esc_html__('Square Effect 3',  'pastore-church');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'pastore-church');
			$list['square effect5']  = esc_html__('Square Effect 5',  'pastore-church');
			$list['square effect6']  = esc_html__('Square Effect 6',  'pastore-church');
			$list['square effect7']  = esc_html__('Square Effect 7',  'pastore-church');
			$list['square effect8']  = esc_html__('Square Effect 8',  'pastore-church');
			$list['square effect9']  = esc_html__('Square Effect 9',  'pastore-church');
			$list['square effect10'] = esc_html__('Square Effect 10',  'pastore-church');
			$list['square effect11'] = esc_html__('Square Effect 11',  'pastore-church');
			$list['square effect12'] = esc_html__('Square Effect 12',  'pastore-church');
			$list['square effect13'] = esc_html__('Square Effect 13',  'pastore-church');
			$list['square effect14'] = esc_html__('Square Effect 14',  'pastore-church');
			$list['square effect15'] = esc_html__('Square Effect 15',  'pastore-church');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'pastore-church');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'pastore-church');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'pastore-church');
			$list['square effect_more']  = esc_html__('Square Effect More',  'pastore-church');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'pastore-church');
			$list = apply_filters('pastore_church_filter_portfolio_hovers', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'pastore_church_get_list_blog_counters' ) ) {
	function pastore_church_get_list_blog_counters($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'pastore-church'),
				'likes'		=> esc_html__('Likes', 'pastore-church'),
				'rating'	=> esc_html__('Rating', 'pastore-church'),
				'comments'	=> esc_html__('Comments', 'pastore-church')
				);
			$list = apply_filters('pastore_church_filter_list_blog_counters', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'pastore_church_get_list_alter_sizes' ) ) {
	function pastore_church_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'pastore-church'),
					'1_2' => esc_html__('1x2', 'pastore-church'),
					'2_1' => esc_html__('2x1', 'pastore-church'),
					'2_2' => esc_html__('2x2', 'pastore-church'),
					'1_3' => esc_html__('1x3', 'pastore-church'),
					'2_3' => esc_html__('2x3', 'pastore-church'),
					'3_1' => esc_html__('3x1', 'pastore-church'),
					'3_2' => esc_html__('3x2', 'pastore-church'),
					'3_3' => esc_html__('3x3', 'pastore-church')
					);
			$list = apply_filters('pastore_church_filter_portfolio_alter_sizes', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_hovers_directions' ) ) {
	function pastore_church_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'pastore-church'),
				'right_to_left' => esc_html__('Right to Left',  'pastore-church'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'pastore-church'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'pastore-church'),
				'scale_up'      => esc_html__('Scale Up',  'pastore-church'),
				'scale_down'    => esc_html__('Scale Down',  'pastore-church'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'pastore-church'),
				'from_left_and_right' => esc_html__('From Left and Right',  'pastore-church'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'pastore-church')
			);
			$list = apply_filters('pastore_church_filter_portfolio_hovers_directions', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'pastore_church_get_list_label_positions' ) ) {
	function pastore_church_get_list_label_positions($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'pastore-church'),
				'bottom'	=> esc_html__('Bottom',		'pastore-church'),
				'left'		=> esc_html__('Left',		'pastore-church'),
				'over'		=> esc_html__('Over',		'pastore-church')
			);
			$list = apply_filters('pastore_church_filter_label_positions', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'pastore_church_get_list_bg_image_positions' ) ) {
	function pastore_church_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'pastore-church'),
				'center top'   => esc_html__("Center Top", 'pastore-church'),
				'right top'    => esc_html__("Right Top", 'pastore-church'),
				'left center'  => esc_html__("Left Center", 'pastore-church'),
				'center center'=> esc_html__("Center Center", 'pastore-church'),
				'right center' => esc_html__("Right Center", 'pastore-church'),
				'left bottom'  => esc_html__("Left Bottom", 'pastore-church'),
				'center bottom'=> esc_html__("Center Bottom", 'pastore-church'),
				'right bottom' => esc_html__("Right Bottom", 'pastore-church')
			);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'pastore_church_get_list_bg_image_repeats' ) ) {
	function pastore_church_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'pastore-church'),
				'repeat-x'	=> esc_html__('Repeat X', 'pastore-church'),
				'repeat-y'	=> esc_html__('Repeat Y', 'pastore-church'),
				'no-repeat'	=> esc_html__('No Repeat', 'pastore-church')
			);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'pastore_church_get_list_bg_image_attachments' ) ) {
	function pastore_church_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'pastore-church'),
				'fixed'		=> esc_html__('Fixed', 'pastore-church'),
				'local'		=> esc_html__('Local', 'pastore-church')
			);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'pastore_church_get_list_bg_tints' ) ) {
	function pastore_church_get_list_bg_tints($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'pastore-church'),
				'light'	=> esc_html__('Light', 'pastore-church'),
				'dark'	=> esc_html__('Dark', 'pastore-church')
			);
			$list = apply_filters('pastore_church_filter_bg_tints', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_field_types' ) ) {
	function pastore_church_get_list_field_types($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'pastore-church'),
				'textarea' => esc_html__('Text Area','pastore-church'),
				'password' => esc_html__('Password',  'pastore-church'),
				'radio'    => esc_html__('Radio',  'pastore-church'),
				'checkbox' => esc_html__('Checkbox',  'pastore-church'),
				'select'   => esc_html__('Select',  'pastore-church'),
				'date'     => esc_html__('Date','pastore-church'),
				'time'     => esc_html__('Time','pastore-church'),
				'button'   => esc_html__('Button','pastore-church')
			);
			$list = apply_filters('pastore_church_filter_field_types', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'pastore_church_get_list_googlemap_styles' ) ) {
	function pastore_church_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'pastore-church')
			);
			$list = apply_filters('pastore_church_filter_googlemap_styles', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'pastore_church_get_list_icons' ) ) {
	function pastore_church_get_list_icons($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_icons'))=='') {
			$list = pastore_church_parse_icons_classes(pastore_church_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'pastore_church_get_list_socials' ) ) {
	function pastore_church_get_list_socials($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_socials'))=='') {
			$list = pastore_church_get_list_files("images/socials", "png");
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'pastore_church_get_list_flags' ) ) {
	function pastore_church_get_list_flags($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_flags'))=='') {
			$list = pastore_church_get_list_files("images/flags", "png");
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_flags', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'pastore_church_get_list_yesno' ) ) {
	function pastore_church_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'pastore-church'),
			'no'  => esc_html__("No", 'pastore-church')
		);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'pastore_church_get_list_onoff' ) ) {
	function pastore_church_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'pastore-church'),
			"off" => esc_html__("Off", 'pastore-church')
		);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'pastore_church_get_list_showhide' ) ) {
	function pastore_church_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'pastore-church'),
			"hide" => esc_html__("Hide", 'pastore-church')
		);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'pastore_church_get_list_orderings' ) ) {
	function pastore_church_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'pastore-church'),
			"desc" => esc_html__("Descending", 'pastore-church')
		);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'pastore_church_get_list_directions' ) ) {
	function pastore_church_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'pastore-church'),
			"vertical" => esc_html__("Vertical", 'pastore-church')
		);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'pastore_church_get_list_shapes' ) ) {
	function pastore_church_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'pastore-church'),
			"square" => esc_html__("Square", 'pastore-church')
		);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'pastore_church_get_list_sizes' ) ) {
	function pastore_church_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'pastore-church'),
			"small"  => esc_html__("Small", 'pastore-church'),
			"medium" => esc_html__("Medium", 'pastore-church'),
			"large"  => esc_html__("Large", 'pastore-church')
		);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'pastore_church_get_list_controls' ) ) {
	function pastore_church_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'pastore-church'),
			"side" => esc_html__("Side", 'pastore-church'),
			"bottom" => esc_html__("Bottom", 'pastore-church')
		);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'pastore_church_get_list_floats' ) ) {
	function pastore_church_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'pastore-church'),
			"left" => esc_html__("Float Left", 'pastore-church'),
			"right" => esc_html__("Float Right", 'pastore-church')
		);
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'pastore_church_get_list_alignments' ) ) {
	function pastore_church_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'pastore-church'),
			"left" => esc_html__("Left", 'pastore-church'),
			"center" => esc_html__("Center", 'pastore-church'),
			"right" => esc_html__("Right", 'pastore-church')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'pastore-church');
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'pastore_church_get_list_hpos' ) ) {
	function pastore_church_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'pastore-church');
		if ($center) $list['center'] = esc_html__("Center", 'pastore-church');
		$list['right'] = esc_html__("Right", 'pastore-church');
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'pastore_church_get_list_vpos' ) ) {
	function pastore_church_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'pastore-church');
		if ($center) $list['center'] = esc_html__("Center", 'pastore-church');
		$list['bottom'] = esc_html__("Bottom", 'pastore-church');
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'pastore_church_get_list_sortings' ) ) {
	function pastore_church_get_list_sortings($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'pastore-church'),
				"title" => esc_html__("Alphabetically", 'pastore-church'),
				"views" => esc_html__("Popular (views count)", 'pastore-church'),
				"comments" => esc_html__("Most commented (comments count)", 'pastore-church'),
				"author_rating" => esc_html__("Author rating", 'pastore-church'),
				"users_rating" => esc_html__("Visitors (users) rating", 'pastore-church'),
				"random" => esc_html__("Random", 'pastore-church')
			);
			$list = apply_filters('pastore_church_filter_list_sortings', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'pastore_church_get_list_columns' ) ) {
	function pastore_church_get_list_columns($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'pastore-church'),
				"1_1" => esc_html__("100%", 'pastore-church'),
				"1_2" => esc_html__("1/2", 'pastore-church'),
				"1_3" => esc_html__("1/3", 'pastore-church'),
				"2_3" => esc_html__("2/3", 'pastore-church'),
				"1_4" => esc_html__("1/4", 'pastore-church'),
				"3_4" => esc_html__("3/4", 'pastore-church'),
				"1_5" => esc_html__("1/5", 'pastore-church'),
				"2_5" => esc_html__("2/5", 'pastore-church'),
				"3_5" => esc_html__("3/5", 'pastore-church'),
				"4_5" => esc_html__("4/5", 'pastore-church'),
				"1_6" => esc_html__("1/6", 'pastore-church'),
				"5_6" => esc_html__("5/6", 'pastore-church'),
				"1_7" => esc_html__("1/7", 'pastore-church'),
				"2_7" => esc_html__("2/7", 'pastore-church'),
				"3_7" => esc_html__("3/7", 'pastore-church'),
				"4_7" => esc_html__("4/7", 'pastore-church'),
				"5_7" => esc_html__("5/7", 'pastore-church'),
				"6_7" => esc_html__("6/7", 'pastore-church'),
				"1_8" => esc_html__("1/8", 'pastore-church'),
				"3_8" => esc_html__("3/8", 'pastore-church'),
				"5_8" => esc_html__("5/8", 'pastore-church'),
				"7_8" => esc_html__("7/8", 'pastore-church'),
				"1_9" => esc_html__("1/9", 'pastore-church'),
				"2_9" => esc_html__("2/9", 'pastore-church'),
				"4_9" => esc_html__("4/9", 'pastore-church'),
				"5_9" => esc_html__("5/9", 'pastore-church'),
				"7_9" => esc_html__("7/9", 'pastore-church'),
				"8_9" => esc_html__("8/9", 'pastore-church'),
				"1_10"=> esc_html__("1/10", 'pastore-church'),
				"3_10"=> esc_html__("3/10", 'pastore-church'),
				"7_10"=> esc_html__("7/10", 'pastore-church'),
				"9_10"=> esc_html__("9/10", 'pastore-church'),
				"1_11"=> esc_html__("1/11", 'pastore-church'),
				"2_11"=> esc_html__("2/11", 'pastore-church'),
				"3_11"=> esc_html__("3/11", 'pastore-church'),
				"4_11"=> esc_html__("4/11", 'pastore-church'),
				"5_11"=> esc_html__("5/11", 'pastore-church'),
				"6_11"=> esc_html__("6/11", 'pastore-church'),
				"7_11"=> esc_html__("7/11", 'pastore-church'),
				"8_11"=> esc_html__("8/11", 'pastore-church'),
				"9_11"=> esc_html__("9/11", 'pastore-church'),
				"10_11"=> esc_html__("10/11", 'pastore-church'),
				"1_12"=> esc_html__("1/12", 'pastore-church'),
				"5_12"=> esc_html__("5/12", 'pastore-church'),
				"7_12"=> esc_html__("7/12", 'pastore-church'),
				"10_12"=> esc_html__("10/12", 'pastore-church'),
				"11_12"=> esc_html__("11/12", 'pastore-church')
			);
			$list = apply_filters('pastore_church_filter_list_columns', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'pastore_church_get_list_dedicated_locations' ) ) {
	function pastore_church_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'pastore-church'),
				"center"  => esc_html__('Above the text of the post', 'pastore-church'),
				"left"    => esc_html__('To the left the text of the post', 'pastore-church'),
				"right"   => esc_html__('To the right the text of the post', 'pastore-church'),
				"alter"   => esc_html__('Alternates for each post', 'pastore-church')
			);
			$list = apply_filters('pastore_church_filter_list_dedicated_locations', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'pastore_church_get_post_format_name' ) ) {
	function pastore_church_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'pastore-church') : esc_html__('galleries', 'pastore-church');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'pastore-church') : esc_html__('videos', 'pastore-church');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'pastore-church') : esc_html__('audios', 'pastore-church');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'pastore-church') : esc_html__('images', 'pastore-church');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'pastore-church') : esc_html__('quotes', 'pastore-church');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'pastore-church') : esc_html__('links', 'pastore-church');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'pastore-church') : esc_html__('statuses', 'pastore-church');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'pastore-church') : esc_html__('asides', 'pastore-church');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'pastore-church') : esc_html__('chats', 'pastore-church');
		else						$name = $single ? esc_html__('standard', 'pastore-church') : esc_html__('standards', 'pastore-church');
		return apply_filters('pastore_church_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'pastore_church_get_post_format_icon' ) ) {
	function pastore_church_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('pastore_church_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'pastore_church_get_list_fonts_styles' ) ) {
	function pastore_church_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','pastore-church'),
				'u' => esc_html__('U', 'pastore-church')
			);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'pastore_church_get_list_fonts' ) ) {
	function pastore_church_get_list_fonts($prepend_inherit=false) {
		if (($list = pastore_church_storage_get('list_fonts'))=='') {
			$list = array();
			$list = pastore_church_array_merge($list, pastore_church_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>pastore_church_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = pastore_church_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('pastore_church_filter_list_fonts', $list);
			if (pastore_church_get_theme_setting('use_list_cache')) pastore_church_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? pastore_church_array_merge(array('inherit' => esc_html__("Inherit", 'pastore-church')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'pastore_church_get_list_font_faces' ) ) {
	function pastore_church_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = pastore_church_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? pastore_church_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? pastore_church_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.esc_html__('uploaded font', 'pastore-church').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>