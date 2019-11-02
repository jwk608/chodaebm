<?php
/* WPML support functions
------------------------------------------------------------------------------- */

// Check if WPML installed and activated
if ( !function_exists( 'pastore_church_exists_wpml' ) ) {
	function pastore_church_exists_wpml() {
		return defined('ICL_SITEPRESS_VERSION') && class_exists('sitepress');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pastore_church_wpml_required_plugins' ) ) {
	//add_filter('pastore_church_filter_required_plugins',	'pastore_church_wpml_required_plugins');
	function pastore_church_wpml_required_plugins($list=array()) {
		if (in_array('wpml', pastore_church_storage_get('required_plugins'))) {
			$path = pastore_church_get_file_dir('plugins/install/wpml.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'WPML',
					'slug' 		=> 'wpml',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}
?>