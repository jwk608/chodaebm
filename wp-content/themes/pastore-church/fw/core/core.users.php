<?php
/**
 * Pastore Church Framework: Registered Users
 *
 * @package	pastore_church
 * @since	pastore_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('pastore_church_users_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_users_theme_setup' );
	function pastore_church_users_theme_setup() {

		if ( is_admin() ) {
			// Add extra fields in the user profile
			add_action( 'show_user_profile',		'pastore_church_add_fields_in_user_profile' );
			add_action( 'edit_user_profile',		'pastore_church_add_fields_in_user_profile' );
	
			// Save / update additional fields from profile
			add_action( 'personal_options_update',	'pastore_church_save_fields_in_user_profile' );
			add_action( 'edit_user_profile_update',	'pastore_church_save_fields_in_user_profile' );
		}

	}
}


// Return (and show) user profiles links
if (!function_exists('pastore_church_show_user_socials')) {
	function pastore_church_show_user_socials($args) {
		$args = array_merge(array(
			'author_id' => 0,										// author's ID
			'allowed' => array(),									// list of allowed social
			'size' => 'small',										// icons size: tiny|small|big
			'shape' => 'square',									// icons shape: square|round
			'style' => pastore_church_get_theme_setting('socials_type')=='images' ? 'bg' : 'icons',	// style for show icons: icons|images|bg
			'echo' => true											// if true - show on page, else - only return as string
			), is_array($args) ? $args 
				: array('author_id' => $args));						// If send one number parameter - use it as author's ID
		$output = '';
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		$social_list = pastore_church_get_theme_option('social_icons');
		$list = array();
		if (is_array($social_list) && count($social_list) > 0) {
			foreach ($social_list as $soc) {
				if ($args['style'] == 'icons') {
					$parts = explode('-', $soc['icon'], 2);
					$sn = isset($parts[1]) ? $parts[1] : $soc['icon'];
				} else {
					$sn = basename($soc['icon']);
					$sn = pastore_church_substr($sn, 0, pastore_church_strrpos($sn, '.'));
					if (($pos=pastore_church_strrpos($sn, '_'))!==false)
						$sn = pastore_church_substr($sn, 0, $pos);
				}
				if (count($args['allowed'])==0 || in_array($sn, $args['allowed'])) {
					$link = get_the_author_meta('user_' . ($sn), $args['author_id']);
					if ($link) {
						$icon = $args['style']=='icons' || pastore_church_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : pastore_church_get_socials_url(basename($soc['icon']));
						$list[] = array(
							'icon'	=> $icon,
							'url'	=> $link
						);
					}
				}
			}
		}
		if (count($list) > 0) {
			$output = '<div class="sc_socials sc_socials_size_'.esc_attr($args['size']).' sc_socials_shape_'.esc_attr($args['shape']).' sc_socials_type_' . esc_attr($args['style']) . '">' 
							. trim(pastore_church_prepare_socials($list, $args['style'])) 
						. '</div>';
			if ($args['echo']) echo trim($output);
		}
		return $output;
	}
}

// Show additional fields in the user profile
if (!function_exists('pastore_church_add_fields_in_user_profile')) {
	function pastore_church_add_fields_in_user_profile( $user ) { 
	?>
		<h3><?php esc_html_e('User Position', 'pastore-church'); ?></h3>
		<table class="form-table">
			<tr>
				<th><label for="user_position"><?php esc_html_e('User position', 'pastore-church'); ?>:</label></th>
				<td><input type="text" name="user_position" id="user_position" size="55" value="<?php echo esc_attr(get_the_author_meta('user_position', $user->ID)); ?>" />
					<span class="description"><?php esc_html_e('Please, enter your position in the company', 'pastore-church'); ?></span>
				</td>
			</tr>
		</table>
	
		<h3><?php esc_html_e('Social links', 'pastore-church'); ?></h3>
		<table class="form-table">
		<?php
		$socials_type = pastore_church_get_theme_setting('socials_type');
		$social_list = pastore_church_get_theme_option('social_icons');
		if (is_array($social_list) && count($social_list) > 0) {
			foreach ($social_list as $soc) {
				if ($socials_type == 'icons') {
					$parts = explode('-', $soc['icon'], 2);
					$sn = isset($parts[1]) ? $parts[1] : $soc['icon'];
				} else {
					$sn = basename($soc['icon']);
					$sn = pastore_church_substr($sn, 0, pastore_church_strrpos($sn, '.'));
					if (($pos=pastore_church_strrpos($sn, '_'))!==false)
						$sn = pastore_church_substr($sn, 0, $pos);
				}
				if (!empty($sn)) {
					?>
					<tr>
						<th><label for="user_<?php echo esc_attr($sn); ?>"><?php echo trim(pastore_church_strtoproper($sn)); ?>:</label></th>
						<td><input type="text" name="user_<?php echo esc_attr($sn); ?>" id="user_<?php echo esc_attr($sn); ?>" size="55" value="<?php echo esc_attr(get_the_author_meta('user_'.($sn), $user->ID)); ?>" />
							<span class="description"><?php echo sprintf(esc_html__('Please, enter your %s link', 'pastore-church'), pastore_church_strtoproper($sn)); ?></span>
						</td>
					</tr>
					<?php
				}
			}
		}
		?>
		</table>
	<?php
	}
}

// Save / update additional fields
if (!function_exists('pastore_church_save_fields_in_user_profile')) {
	function pastore_church_save_fields_in_user_profile( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;

		if (isset($_POST['user_position'])) 
			update_user_meta( $user_id, 'user_position', $_POST['user_position'] );
		
		$socials_type = pastore_church_get_theme_setting('socials_type');
		$social_list = pastore_church_get_theme_option('social_icons');
		if (is_array($social_list) && count($social_list) > 0) {
			foreach ($social_list as $soc) {
				if ($socials_type == 'icons') {
					$parts = explode('-', $soc['icon'], 2);
					$sn = isset($parts[1]) ? $parts[1] : $soc['icon'];
				} else {
					$sn = basename($soc['icon']);
					$sn = pastore_church_substr($sn, 0, pastore_church_strrpos($sn, '.'));
					if (($pos=pastore_church_strrpos($sn, '_'))!==false)
						$sn = pastore_church_substr($sn, 0, $pos);
				}
				if (isset($_POST['user_'.($sn)]))
					update_user_meta( $user_id, 'user_'.($sn), $_POST['user_'.($sn)] );
			}
		}
	}
}
?>