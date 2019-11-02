<?php
/* Content Timeline support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('pastore_church_content_timeline_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_content_timeline_theme_setup', 1 );
	function pastore_church_content_timeline_theme_setup() {
		
		// One-click importer support
		if (pastore_church_exists_content_timeline()) {
			add_action( 'wp_enqueue_scripts', 								'pastore_church_content_timeline_frontend_scripts', 1001 );
			add_filter( 'pastore_church_filter_get_css',							'pastore_church_content_timeline_get_css', 10, 3 );
			if (is_admin()) {
				add_filter( 'pastore_church_filter_importer_options',				'pastore_church_content_timeline_importer_set_options' );
				add_action( 'pastore_church_action_importer_params',				'pastore_church_content_timeline_importer_show_params', 10, 1 );
				add_action( 'pastore_church_action_importer_import',				'pastore_church_content_timeline_importer_import', 10, 2 );
				add_action( 'pastore_church_action_importer_import_fields',		'pastore_church_content_timeline_importer_import_fields', 10, 1 );
				add_action( 'pastore_church_action_importer_export',				'pastore_church_content_timeline_importer_export', 10, 1 );
				add_action( 'pastore_church_action_importer_export_fields',		'pastore_church_content_timeline_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'pastore_church_filter_importer_required_plugins',		'pastore_church_content_timeline_importer_required_plugins', 10, 2 );
			add_filter( 'pastore_church_filter_required_plugins',			'pastore_church_content_timeline_tgmpa_required_plugins' );
		}

	}
}

// Check if plugin is installed and activated
if ( !function_exists( 'pastore_church_exists_content_timeline' ) ) {
	function pastore_church_exists_content_timeline() {
		return class_exists( 'ContentTimelineAdmin' );
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'pastore_church_content_timeline_frontend_scripts' ) ) {
	//add_action( 'wp_enqueue_scripts', 'pastore_church_content_timeline_frontend_scripts' );
	function pastore_church_content_timeline_frontend_scripts() {
		if (file_exists(pastore_church_get_file_dir('css/plugin.content_timeline.css')))
			pastore_church_enqueue_style( 'pj-plugin.content_timeline',  pastore_church_get_file_url('css/plugin.content_timeline.css'), array(), null );
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'pastore_church_content_timeline_tgmpa_required_plugins' ) ) {
	//add_filter('pastore_church_filter_tgmpa_required_plugins',	'pastore_church_content_timeline_tgmpa_required_plugins');
	function pastore_church_content_timeline_tgmpa_required_plugins($list=array()) {
		if (in_array('content_timeline', pastore_church_storage_get('required_plugins'))) {
			$path = pastore_church_get_file_dir('plugins/install/content_timeline.zip');
			if (file_exists($path)) {
				$list[] = array(
						'name' 		=> 'Content Timeline',
						'slug' 		=> 'content_timeline',
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

// Check plugin in the required plugins
if ( !function_exists( 'pastore_church_content_timeline_importer_required_plugins' ) ) {
	//add_filter( 'pastore_church_filter_importer_required_plugins',	'pastore_church_content_timeline_importer_required_plugins', 10, 2 );
	function pastore_church_content_timeline_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('content_timeline', pastore_church_storage_get('required_plugins')) && !pastore_church_exists_content_timeline() )
		//if (strpos($list, 'content_timeline')!==false && !pastore_church_exists_content_timeline() )
		if (pastore_church_strpos($list, 'content_timeline')!==false && !pastore_church_exists_content_timeline() )
			$not_installed .= '<br>Content Timeline';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'pastore_church_content_timeline_importer_set_options' ) ) {
	//add_filter( 'pastore_church_filter_importer_options',	'pastore_church_content_timeline_importer_set_options' );
	function pastore_church_content_timeline_importer_set_options($options=array()) {
		if ( in_array('content_timeline', pastore_church_storage_get('required_plugins')) && pastore_church_exists_content_timeline() ) {
			//$options['additional_options'][] = 'content_timeline_calendar_options';
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_content_timeline'] = str_replace('posts', 'content_timeline', $v['file_with_posts']);
				}
			}
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'pastore_church_content_timeline_importer_show_params' ) ) {
	//add_action( 'pastore_church_action_importer_params',	'pastore_church_content_timeline_importer_show_params', 10, 1 );
	function pastore_church_content_timeline_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('content_timeline', pastore_church_storage_get('required_plugins')) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_content_timeline" id="import_content_timeline" /> <label for="import_content_timeline"><?php esc_html_e('Import Content Timeline', 'pastore-church'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'pastore_church_content_timeline_importer_import' ) ) {
	//add_action( 'pastore_church_action_importer_import',	'pastore_church_content_timeline_importer_import', 10, 2 );
	function pastore_church_content_timeline_importer_import($importer, $action) {
		if ( $action == 'import_content_timeline' ) {
			$importer->response['result'] = $importer->import_dump('content_timeline', esc_html__('Content Timeline', 'pastore-church'));
		}
	}
}

// Display import progress
if ( !function_exists( 'pastore_church_content_timeline_importer_import_fields' ) ) {
	//add_action( 'pastore_church_action_importer_import_fields',	'pastore_church_content_timeline_importer_import_fields', 10, 1 );
	function pastore_church_content_timeline_importer_import_fields($importer) {
		?>
		<tr class="import_content_timeline">
			<td class="import_progress_item"><?php esc_html_e('Content Timeline', 'pastore-church'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'pastore_church_content_timeline_importer_export' ) ) {
	//add_action( 'pastore_church_action_importer_export',	'pastore_church_content_timeline_importer_export', 10, 1 );
	function pastore_church_content_timeline_importer_export($importer) {
		pastore_church_storage_set('export_content_timeline', serialize( array(
			'ctimelines' => $importer->export_dump('ctimelines')
			) )
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'pastore_church_content_timeline_importer_export_fields' ) ) {
	//add_action( 'pastore_church_action_importer_export_fields',	'pastore_church_content_timeline_importer_export_fields', 10, 1 );
	function pastore_church_content_timeline_importer_export_fields($importer) {
		?>
		<tr>
			<th align="left"><?php esc_html_e('Content Timeline', 'pastore-church'); ?></th>
			<td><?php pastore_church_fpc(pastore_church_get_file_dir('core/core.importer/export/content_timeline.txt'), pastore_church_storage_get('export_content_timeline')); ?>
				<a download="content_timeline.txt" href="<?php echo esc_url(pastore_church_get_file_url('core/core.importer/export/content_timeline.txt')); ?>"><?php esc_html_e('Download', 'pastore-church'); ?></a>
			</td>
		</tr>
		<?php
	}
}

?>