<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('pastore_church_sc_audio_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_sc_audio_theme_setup' );
	function pastore_church_sc_audio_theme_setup() {
		add_action('pastore_church_action_shortcodes_list', 		'pastore_church_sc_audio_reg_shortcodes');
		if (function_exists('pastore_church_exists_visual_composer') && pastore_church_exists_visual_composer())
			add_action('pastore_church_action_shortcodes_list_vc','pastore_church_sc_audio_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_audio url="http://trex2.themerex.dnw/wp-content/uploads/2014/12/Dream-Music-Relax.mp3" image="http://trex2.themerex.dnw/wp-content/uploads/2014/10/post_audio.jpg" title="Insert Audio Title Here" author="Lily Hunter" controls="show" autoplay="off"]
*/

if (!function_exists('pastore_church_sc_audio')) {	
	function pastore_church_sc_audio($atts, $content = null) {
		if (pastore_church_in_shortcode_blogger()) return '';
		extract(pastore_church_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "hide",
			"title" => "",
			"author" => "",
			"image" => "",
			"mp3" => '',
			"wav" => '',
			"src" => '',
			"url" => '',
			"align" => '',
			"controls" => "show",
			"autoplay" => "",
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		if ($src=='') {
			if ($url) $src = $url;
			else if ($mp3) $src = $mp3;
			else if ($wav) $src = $wav;
		}
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$class .= ($class ? ' ' : '') . pastore_church_get_css_position_as_classes($top, $right, $bottom, $left);
		$data = ($title != ''  ? ' data-title="'.esc_attr($title).'"'   : '')
				. ($author != '' ? ' data-author="'.esc_attr($author).'"' : '')
				. ($image != ''  ? ' data-image="'.esc_url($image).'"'   : '')
				. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
				. (!pastore_church_param_is_off($animation) ? ' data-animation="'.esc_attr(pastore_church_get_animation_classes($animation)).'"' : '');


		$class .= (pastore_church_param_is_on($style) ? ' extra_style' : '');
		$class .=  (pastore_church_param_is_on($controls) ? ' no_controls' : '');

		$audio = '<audio'
			. ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_audio' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. ' src="'.esc_url($src).'"'
			. (pastore_church_param_is_on($controls) ? ' controls="controls"' : '')
			. (pastore_church_param_is_on($autoplay) && is_single() ? ' autoplay="autoplay"' : '')
			. ($width ? ' width="'.esc_attr($width).'"' : '').($height ? ' height="'.esc_attr($height).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($data)
			. '></audio>';
		if ( pastore_church_get_custom_option('substitute_audio')=='no') {
			if (pastore_church_param_is_on($frame)) {
				$audio = pastore_church_get_audio_frame($audio, $image, $s);
			}
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$audio = pastore_church_substitute_audio($audio, false);
			}
		}
		if (pastore_church_get_theme_option('use_mediaelement')=='yes')
			pastore_church_enqueue_script('wp-mediaelement');
		return apply_filters('pastore_church_shortcode_output', $audio, 'trx_audio', $atts, $content);
	}
	pastore_church_require_shortcode("trx_audio", "pastore_church_sc_audio");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_audio_reg_shortcodes' ) ) {
	//add_action('pastore_church_action_shortcodes_list', 'pastore_church_sc_audio_reg_shortcodes');
	function pastore_church_sc_audio_reg_shortcodes() {
	
		pastore_church_sc_map("trx_audio", array(
			"title" => esc_html__("Audio", 'pastore-church'),
			"desc" => wp_kses_data( __("Insert audio player", 'pastore-church') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Extra style", 'pastore-church'),
					"desc" => wp_kses_data( __("Show extra style", 'pastore-church') ),
					"divider" => true,
					"size" => "medium",
					"value" => "show",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('show_hide')
				),
				"url" => array(
					"title" => esc_html__("URL for audio file", 'pastore-church'),
					"desc" => wp_kses_data( __("URL for audio file", 'pastore-church') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose audio', 'pastore-church'),
						'action' => 'media_upload',
						'type' => 'audio',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose audio file', 'pastore-church'),
							'update' => esc_html__('Select audio file', 'pastore-church')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"image" => array(
					"title" => esc_html__("Cover image", 'pastore-church'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'pastore-church') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"title" => array(
					"title" => esc_html__("Title", 'pastore-church'),
					"desc" => wp_kses_data( __("Title of the audio file", 'pastore-church') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"author" => array(
					"title" => esc_html__("Author", 'pastore-church'),
					"desc" => wp_kses_data( __("Author of the audio file", 'pastore-church') ),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Show controls", 'pastore-church'),
					"desc" => wp_kses_data( __("Show controls in audio player", 'pastore-church') ),
					"divider" => true,
					"size" => "medium",
					"value" => "show",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('show_hide')
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay audio", 'pastore-church'),
					"desc" => wp_kses_data( __("Autoplay audio on page load", 'pastore-church') ),
					"value" => "off",
					"type" => "switch",
					"options" => pastore_church_get_sc_param('on_off')
				),
				"align" => array(
					"title" => esc_html__("Align", 'pastore-church'),
					"desc" => wp_kses_data( __("Select block alignment", 'pastore-church') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => pastore_church_get_sc_param('align')
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'pastore_church_sc_audio_reg_shortcodes_vc' ) ) {
	//add_action('pastore_church_action_shortcodes_list_vc', 'pastore_church_sc_audio_reg_shortcodes_vc');
	function pastore_church_sc_audio_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_audio",
			"name" => esc_html__("Audio", 'pastore-church'),
			"description" => wp_kses_data( __("Insert audio player", 'pastore-church') ),
			"category" => esc_html__('Content', 'pastore-church'),
			'icon' => 'icon_trx_audio',
			"class" => "trx_sc_single trx_sc_audio",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Extra style", 'pastore-church'),
					"description" => wp_kses_data( __("Show Extra style", 'pastore-church') ),
					"class" => "",
					"value" => array("Extra style" => "show" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for audio file", 'pastore-church'),
					"description" => wp_kses_data( __("Put here URL for audio file", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", 'pastore-church'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'pastore-church'),
					"description" => wp_kses_data( __("Title of the audio file", 'pastore-church') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "author",
					"heading" => esc_html__("Author", 'pastore-church'),
					"description" => wp_kses_data( __("Author of the audio file", 'pastore-church') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'pastore-church'),
					"description" => wp_kses_data( __("Show/hide controls", 'pastore-church') ),
					"class" => "",
					"value" => array("Hide controls" => "show" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay", 'pastore-church'),
					"description" => wp_kses_data( __("Autoplay audio on page load", 'pastore-church') ),
					"class" => "",
					"value" => array("Autoplay" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'pastore-church'),
					"description" => wp_kses_data( __("Select block alignment", 'pastore-church') ),
					"class" => "",
					"value" => array_flip(pastore_church_get_sc_param('align')),
					"type" => "dropdown"
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
		) );
		
		class WPBakeryShortCode_Trx_Audio extends PASTORE_CHURCH_VC_ShortCodeSingle {}
	}
}
?>