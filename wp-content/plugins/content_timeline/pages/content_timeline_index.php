<div class="wrap">
	<h2>Timelines
			<a href="<?php echo admin_url( "admin.php?page=contenttimeline_edit" ); ?>" class="add-new-h2">Add New</a>
	</h2>
<?php

?>


<table class="wp-list-table widefat fixed">
	<thead>
		<tr>
			<th width="5%">ID</th>
			<th width="30%">Name</th>
			<th width="60%">Shortcode</th>
			<th width="20%">Actions</th>					
		</tr>
	</thead>
	
	<tfoot>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Shortcode</th>
			<th>Actions</th>					
		</tr>
	</tfoot>
	
	<tbody>
		<?php 
			global $wpdb;
			$prefix = $wpdb->prefix;

			if(isset($_GET['action']) && $_GET['action'] == 'delete') {
				$wpdb->query('DELETE FROM '. $prefix . 'ctimelines WHERE id = '.$_GET['id']);
			}
			$timelines = $wpdb->get_results("SELECT * FROM " . $prefix . "ctimelines ORDER BY id");
			if (count($timelines) == 0) {
				echo '<tr>'.
						 '<td colspan="100%">No timelines found.</td>'.
					 '</tr>';
			} else {
				$tname;
				foreach ($timelines as $timeline) {
					$tname = $timeline->name;
					if(!$tname) {
						$tname = 'Timeline #' . $timeline->id . ' (untitled)';
					}
					echo '<tr>'.
							'<td>' . $timeline->id . '</td>'.						
							'<td>' . '<a href="' . admin_url('admin.php?page=contenttimeline_edit&id=' . $timeline->id) . '" title="Edit">'.$tname.'</a>' . '</td>'.
							'<td> [content_timeline id="' . $timeline->id . '"]</td>' .		
							'<td>' . '<a href="' . admin_url('admin.php?page=contenttimeline_edit&id=' . $timeline->id) . '" title="Edit this item">Edit</a> | '.									  
								  '<a href="' . admin_url('admin.php?page=contenttimeline&action=delete&id='  . $timeline->id) . '" title="Delete this item" >Delete</a>'.
							'</td>'.														
						'</tr>';
				}
			}
		?>
		
	</tbody>		 
</table>
<div style="margin-top:20px;">

<h2>Step by step:</h2>
<ul>
	<li><h3>1. Click on "Add New button"</h3></li>
	<li><h3>2. Setup your timeline, and click Save</h3></li>
	<li><h3>3. Copy "shortcode" from table and use it in your post or page. (for adding timeline into .php parts of template use it like this "&lt;?php do_shortcode('[content_timeline id="X"]') ?&gt;" where X is id of your timeline)</h3></li>

</ul>
</div>
</div>
<?php

?>