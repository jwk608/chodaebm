<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pastore_church_vc_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_vc_theme_setup', 1 );
	function pastore_church_vc_theme_setup() {
		if (pastore_church_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'pastore_church_filter_importer_options',				'pastore_church_vc_importer_set_options' );
			}
			add_action('pastore_church_action_add_styles',		 				'pastore_church_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'pastore_church_filter_importer_required_plugins',		'pastore_church_vc_importer_required_plugins', 10, 2 );
			add_filter( 'pastore_church_filter_required_plugins',					'pastore_church_vc_required_plugins' );
		}
	}
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'pastore_church_exists_visual_composer' ) ) {
	function pastore_church_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'pastore_church_vc_is_frontend' ) ) {
	function pastore_church_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
		//return function_exists('vc_is_frontend_editor') && vc_is_frontend_editor();
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pastore_church_vc_required_plugins' ) ) {
	//add_filter('pastore_church_filter_required_plugins',	'pastore_church_vc_required_plugins');
	function pastore_church_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', pastore_church_storage_get('required_plugins'))) {
			$path = pastore_church_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Visual Composer',
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'pastore_church_vc_frontend_scripts' ) ) {
	//add_action( 'pastore_church_action_add_styles', 'pastore_church_vc_frontend_scripts' );
	function pastore_church_vc_frontend_scripts() {
		if (file_exists(pastore_church_get_file_dir('css/plugin.visual-composer.css')))
			pastore_church_enqueue_style( 'pastore_church-plugin.visual-composer-style',  pastore_church_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'pastore_church_vc_importer_required_plugins' ) ) {
	//add_filter( 'pastore_church_filter_importer_required_plugins',	'pastore_church_vc_importer_required_plugins', 10, 2 );
	function pastore_church_vc_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('visual_composer', pastore_church_storage_get('required_plugins')) && !pastore_church_exists_visual_composer() && pastore_church_get_value_gp('data_type')=='vc' )
		if (!pastore_church_exists_visual_composer() )		// && pastore_church_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>Visual Composer';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'pastore_church_vc_importer_set_options' ) ) {
	//add_filter( 'pastore_church_filter_importer_options',	'pastore_church_vc_importer_set_options' );
	function pastore_church_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', pastore_church_storage_get('required_plugins')) && pastore_church_exists_visual_composer() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'wpb_js_templates';
		}
		return $options;
	}
}
?>