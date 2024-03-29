<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_columns_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_columns_theme_setup' );
	function pastore_church_sc_columns_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_columns_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_columns_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_columns id="unique_id" count="number"]
	[trx_column_item id="unique_id" span="2 - number_columns"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta, odio arcu vut natoque dolor ut, enim etiam vut augue. Ac augue amet quis integer ut dictumst? Elit, augue vut egestas! Tristique phasellus cursus egestas a nec a! Sociis et? Augue velit natoque, amet, augue. Vel eu diam, facilisis arcu.[/trx_column_item]
	[trx_column_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in. Magna eros hac montes, et velit. Odio aliquam phasellus enim platea amet. Turpis dictumst ultrices, rhoncus aenean pulvinar? Mus sed rhoncus et cras egestas, non etiam a? Montes? Ac aliquam in nec nisi amet eros! Facilisis! Scelerisque in.[/trx_column_item]
	[trx_column_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim. Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna.[/trx_column_item]
	[trx_column_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna. Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim.[/trx_column_item]
[/trx_columns]
*/

if (!function_exists('pastore_church_sc_columns')) {	
	function pastore_church_sc_columns($atts, $content=null){	
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"count" => "2",
			"fluid" => "no",
			"margins" => "yes",
			"autoheight" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= pastore_church_get_css_dimensions_from_values($width, $height);
		$count = max(1, min(12, (int) $count));
		if (empty($autoheight)) $autoheight = 'no';
		pastore_church_storage_set('sc_columns_data', array(
			'counter' => 1,
            'after_span2' => false,
            'after_span3' => false,
            'after_span4' => false,
            'count' => $count
            )
        );
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="columns_wrap sc_columns'
					. ' columns_' . (pastore_church_param_is_on($fluid) ? 'fluid' : 'nofluid') 
					. (!empty($margins) && pastore_church_param_is_off($margins) ? ' no_margins' : '')
					. (pastore_church_param_is_on($autoheight) ? ' autoheight' : '')
					. ' sc_columns_count_' . esc_attr($count)
					. (!empty($class) ? ' '.esc_attr($class) : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
				. '>'
					. do_shortcode($content)
				. '</div>';
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_columns', $atts, $content);
	}
	pastore_church_require_shortcode('trx_columns', 'pastore_church_sc_columns');
}


if (!function_exists('pastore_church_sc_column_item')) {	
	function pastore_church_sc_column_item($atts, $content=null) {
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts( array(
			// Individual params
			"span" => "1",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_tile" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => ""
		), $atts)));
		$css .= ($align !== '' ? 'text-align:' . esc_attr($align) . ';' : '') 
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '');
		$span = max(1, min(11, (int) $span));
		if (!empty($bg_image)) {
			if ($bg_image > 0) {
				$attach = wp_get_attachment_image_src( $bg_image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$bg_image = $attach[0];
			}
		}
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="column-'.($span > 1 ? esc_attr($span) : 1).'_'.esc_attr(pastore_church_storage_get_array('sc_columns_data', 'count')).' sc_column_item sc_column_item_'.esc_attr(pastore_church_storage_get_array('sc_columns_data', 'counter')) 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (pastore_church_storage_get_array('sc_columns_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
					. (pastore_church_storage_get_array('sc_columns_data', 'counter') == 1 ? ' first' : '') 
					. ($span > 1 ? ' span_'.esc_attr($span) : '') 
					. (pastore_church_storage_get_array('sc_columns_data', 'after_span2') ? ' after_span_2' : '') 
					. (pastore_church_storage_get_array('sc_columns_data', 'after_span3') ? ' after_span_3' : '') 
					. (pastore_church_storage_get_array('sc_columns_data', 'after_span4') ? ' after_span_4' : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '')
					. '>'
					. ($bg_color!=='' || $bg_image !== '' ? '<div class="sc_column_item_inner" style="'
							. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(pastore_church_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;background-position: center center;') : '')
							. '">' : '')
						. do_shortcode($content)
					. ($bg_color!=='' || $bg_image !== '' ? '</div>' : '')
					. '</div>';
		pastore_church_storage_inc_array('sc_columns_data', 'counter', $span);
		pastore_church_storage_set_array('sc_columns_data', 'after_span2', $span==2);
		pastore_church_storage_set_array('sc_columns_data', 'after_span3', $span==3);
		pastore_church_storage_set_array('sc_columns_data', 'after_span4', $span==4);
		return apply_filters('pastore_church_shortcode_output', $output, 'trx_column_item', $atts, $content);
	}
	pastore_church_require_shortcode('trx_column_item', 'pastore_church_sc_column_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_columns_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_columns_reg_shortcodes');
	function pastore_church_sc_columns_reg_shortcodes() {
	
		pastore_church_sc_map("trx_columns", array(
			"title" => esc_html__("Columns", 'pastore-church'),
			"desc" => wp_kses_data( __("Insert up to 5 columns in your page (post)", 'pastore-church') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"fluid" => array(
					"title" => esc_html__("Fluid columns", 'pastore-church'),
					"desc" => wp_kses_data( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", 'pastore-church') ),
					"value" => "no",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				), 
				"margins" => array(
					"title" => esc_html__("Margins between columns", 'pastore-church'),
					"desc" => wp_kses_data( __("Add margins between columns", 'pastore-church') ),
					"value" => "yes",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"autoheight" => array(
					"title" => esc_html__("Autoheight", 'pastore-church'),
					"desc" => wp_kses_data( __("Make equal columns height", 'pastore-church') ),
					"value" => "yes",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('yes_no')
				),
				"width" => pastore_church_shortcodes_width(),
				"height" => pastore_church_shortcodes_height(),
				"top" => pastore_church_get_sc_param('top'),
				"bottom" => pastore_church_get_sc_param('bottom'),
				"left" => pastore_church_get_sc_param('left'),
				"right" => pastore_church_get_sc_param('right'),
				"id" => pastore_church_get_sc_param('id'),
				"class" => pastore_church_get_sc_param('class'),
				"animation" => pastore_church_get_sc_param('animation'),
				"css" => pastore_church_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_column_item",
				"title" => esc_html__("Column", 'pastore-church'),
				"desc" => wp_kses_data( __("Column item", 'pastore-church') ),
				"container" => true,
				"params" => array(
					"span" => array(
						"title" => esc_html__("Merge columns", 'pastore-church'),
						"desc" => wp_kses_data( __("Count merged columns from current", 'pastore-church') ),
						"value" => "",
						"type" => "text"
					),
					"align" => array(
						"title" => esc_html__("Alignment", 'pastore-church'),
						"desc" => wp_kses_data( __("Alignment text in the column", 'pastore-church') ),
						"value" => "",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => pastore_church_get_sc_param('align')
					),
					"color" => array(
						"title" => esc_html__("Fore color", 'pastore-church'),
						"desc" => wp_kses_data( __("Any color for objects in this column", 'pastore-church') ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", 'pastore-church'),
						"desc" => wp_kses_data( __("Any background color for this column", 'pastore-church') ),
						"value" => "",
						"type" => "color"
					),
					"bg_image" => array(
						"title" => esc_html__("URL for background image file", 'pastore-church'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'pastore-church') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"bg_tile" => array(
						"title" => esc_html__("Tile background image", 'pastore-church'),
						"desc" => wp_kses_data( __("Do you want tile background image or image cover whole column?", 'pastore-church') ),
						"value" => "no",
						"dependency" => array(
							'bg_image' => array('not_empty')
						),
						"type" => "switch",
						"options" => pastore_church_get_sc_param('yes_no')
					),
					"_content_" => array(
						"title" => esc_html__("Column item content", 'pastore-church'),
						"desc" => wp_kses_data( __("Current column item content", 'pastore-church') ),
						"divider" => true,
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => pastore_church_get_sc_param('id'),
					"class" => pastore_church_get_sc_param('class'),
					"animation" => pastore_church_get_sc_param('animation'),
					"css" => pastore_church_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_columns_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_columns_reg_shortcodes_vc');
	function pastore_church_sc_columns_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_columns",
			"name" => esc_html__("Columns", 'pastore-church'),
			"description" => wp_kses_data( __("Insert columns with margins", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_columns',
			"class" => "trx_sc_columns",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_column_item'),
			"params" => array(
				array(
					"param_name" => "count",
					"heading" => esc_html__("Columns count", 'pastore-church'),
					"description" => wp_kses_data( __("Number of the columns in the container.", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "2",
					"type" => "textfield"
				),
				array(
					"param_name" => "fluid",
					"heading" => esc_html__("Fluid columns", 'pastore-church'),
					"description" => wp_kses_data( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", 'pastore-church') ),
					"class" => "",
					"value" => array(esc_html__('Fluid columns', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", 'pastore-church'),
					"description" => wp_kses_data( __("Make equal columns height)", "pastore-church") ),
					"class" => "",
					"value" => array(esc_html__('Autoheight', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "margins",
					"heading" => esc_html__("Margins between columns", 'pastore-church'),
					"description" => wp_kses_data( __("Add margins between columns", 'pastore-church') ),
					"class" => "",
					"std" => "yes",
					"value" => array(esc_html__('Disable margins between columns', 'pastore-church') => 'no'),
					"type" => "checkbox"
				),
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('animation'),
				pastore_church_get_vc_param('css'),
				pastore_church_vc_width(),
				pastore_church_vc_height(),
				pastore_church_get_vc_param('margin_top'),
				pastore_church_get_vc_param('margin_bottom'),
				pastore_church_get_vc_param('margin_left'),
				pastore_church_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_column_item][/trx_column_item]
				[trx_column_item][/trx_column_item]
			',
			'js_view' => 'VcTrxColumnsView'
		) );
		
		
		vc_map( array(
			"base" => "trx_column_item",
			"name" => esc_html__("Column", 'pastore-church'),
			"description" => wp_kses_data( __("Column item", 'pastore-church') ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_column_item",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_column_item',
			"as_child" => array('only' => 'trx_columns'),
			"as_parent" => array('except' => 'trx_columns'),
			"params" => array(
				array(
					"param_name" => "span",
					"heading" => esc_html__("Merge columns", 'pastore-church'),
					"description" => wp_kses_data( __("Count merged columns from current", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'pastore-church'),
					"description" => wp_kses_data( __("Alignment text in the column", 'pastore-church') ),
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'pastore-church'),
					"description" => wp_kses_data( __("Any color for objects in this column", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'pastore-church'),
					"description" => wp_kses_data( __("Any background color for this column", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("URL for background image file", 'pastore-church'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'pastore-church'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole column?", 'pastore-church') ),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'pastore-church') => 'yes'),
					"type" => "checkbox"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Column's content", 'pastore-church'),
					"description" => wp_kses_data( __("Content of the current column", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				pastore_church_get_vc_param('id'),
				pastore_church_get_vc_param('class'),
				pastore_church_get_vc_param('animation'),
				pastore_church_get_vc_param('css')
			),
			'js_view' => 'VcTrxColumnItemView'
		) );
		
		class WPBakeryShortCode_Trx_Columns extends PASTORE_CHURCH_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Column_Item extends PASTORE_CHURCH_VC_ShortCodeCollection {}
	}
}
?>