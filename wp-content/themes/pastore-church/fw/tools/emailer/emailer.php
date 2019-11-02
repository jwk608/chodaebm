<?php
/**
 * Send email to subscribers from selected group
 */
 
// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('pastore_church_emailer_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_emailer_theme_setup' );
	function pastore_church_emailer_theme_setup() {
		// AJAX: Save e-mail in subscribe list
		add_action('wp_ajax_emailer_submit',				'pastore_church_callback_emailer_submit');
		add_action('wp_ajax_nopriv_emailer_submit',			'pastore_church_callback_emailer_submit');
		// AJAX: Confirm e-mail in subscribe list
		add_action('wp_ajax_emailer_confirm',				'pastore_church_callback_emailer_confirm');
		add_action('wp_ajax_nopriv_emailer_confirm',		'pastore_church_callback_emailer_confirm');
		// AJAX: Get subscribers list if group changed
		add_action('wp_ajax_emailer_group_getlist',			'pastore_church_callback_emailer_group_getlist');
		add_action('wp_ajax_nopriv_emailer_group_getlist',	'pastore_church_callback_emailer_group_getlist');
	}
}

if (!function_exists('pastore_church_emailer_theme_setup2')) {
	add_action( 'pastore_church_action_after_init_theme', 'pastore_church_emailer_theme_setup2' );		// Fire this action after load theme options
	function pastore_church_emailer_theme_setup2() {
		if (is_admin() && current_user_can('manage_options') && pastore_church_get_theme_option('admin_emailer')=='yes') {
			new pastore_church_emailer();
		}
	}
}


class pastore_church_emailer {

	var $subscribers  = array();
	var $error    = '';
	var $success  = '';
	var $max_recipients_in_one_letter = 50;

	//-----------------------------------------------------------------------------------
	// Constuctor
	//-----------------------------------------------------------------------------------
	function __construct() {
		// Setup actions handlers
		add_action('admin_menu', array($this, 'admin_menu_item'));
		add_action("admin_enqueue_scripts", array($this, 'load_scripts'));
		add_action("admin_head", array($this, 'prepare_js'));

		// Init properties
		$this->subscribers = pastore_church_emailer_group_getlist();
	}

	//-----------------------------------------------------------------------------------
	// Admin Interface
	//-----------------------------------------------------------------------------------
	function admin_menu_item() {
		if ( current_user_can( 'manage_options' ) ) {
			// 'theme' - add in the 'Appearance'
			// 'management' - add in the 'Tools'
			pastore_church_admin_add_menu_item('theme', array(
				'page_title' => esc_html__('Emailer', 'pastore-church'),
				'menu_title' => esc_html__('Emailer', 'pastore-church'),
				'capability' => 'manage_options',
				'menu_slug'  => 'trx_emailer',
				'callback'   => array($this, 'build_page'),
				'icon'		 => ''
				)
			);
		}
	}


	//-----------------------------------------------------------------------------------
	// Load required styles and scripts
	//-----------------------------------------------------------------------------------
	function load_scripts() {
		if (isset($_REQUEST['page']) && $_REQUEST['page']=='trx_emailer') {
			pastore_church_enqueue_style('trx-emailer-style', pastore_church_get_file_url('tools/emailer/emailer.css'), array(), null);
			wp_deregister_style('jquery_ui');
			wp_deregister_style('date-picker-css');
		}
		if (isset($_REQUEST['page']) && $_REQUEST['page']=='trx_emailer') {
			pastore_church_enqueue_script('jquery-ui-core', false, array('jquery'), null, true);
			pastore_church_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
			pastore_church_enqueue_script('trx-emailer-script', pastore_church_get_file_url('tools/emailer/emailer.js'), array('jquery'), null, true);
		}
	}
	
	
	//-----------------------------------------------------------------------------------
	// Prepare javascripts global variables
	//-----------------------------------------------------------------------------------
	function prepare_js() { 
		?>
		<script type="text/javascript">
			var PASTORE_CHURCH_EMAILER_ajax_nonce = "<?php echo esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))); ?>";
			var PASTORE_CHURCH_EMAILER_ajax_url   = "<?php echo admin_url('admin-ajax.php'); ?>";
			var PASTORE_CHURCH_EMAILER_ajax_error = "<?php esc_html_e('Invalid server answer!', 'pastore-church'); ?>";
		</script>
		<?php 
	}
	
	
	//-----------------------------------------------------------------------------------
	// Build the Main Page
	//-----------------------------------------------------------------------------------
	function build_page() {
		
		$mail = pastore_church_get_theme_option('mail_function');

		$subject = $message = $attach = $group = $sender_name = $sender_email = '';
		$subscribers_update = $subscribers_delete = $subscribers_clear = false;
		$subscribers = array();
		
		if ( isset($_POST['emailer_subject']) ) {
			do {
				// Check nonce
				if ( !wp_verify_nonce( pastore_church_get_value_gp('nonce'), admin_url('admin-ajax.php') ) ) {
					$this->error = esc_html__('Incorrect WP-nonce data! Operation canceled!', 'pastore-church');
					break;
				}
				// Get post data
				$subject = pastore_church_get_value_gp('emailer_subject');
				if (empty($subject)) {
					$this->error = esc_html__('Subject can not be empty! Operation canceled!', 'pastore-church');
					break;
				}
				$message = pastore_church_get_value_gp('emailer_message');
				if (empty($message)) {
					$this->error = esc_html__('Message can not be empty! Operation canceled!', 'pastore-church');
					break;
				}
				$attach  = isset($_FILES['emailer_attachment']['tmp_name']) && file_exists($_FILES['emailer_attachment']['tmp_name']) ? $_FILES['emailer_attachment']['tmp_name'] : '';
				$group   = pastore_church_get_value_gp('emailer_group');
				$subscribers = pastore_church_get_value_gp('emailer_subscribers');
				if (!empty($subscribers))
					$subscribers = explode("\n", str_replace(array(';', ','), array("\n", "\n"), $subscribers));
				else
					$subscribers = array();
				if (count($subscribers)==0) {
					$this->error = esc_html__('Subscribers lists are empty! Operation canceled!', 'pastore-church');
					break;
				}
				$sender_name = pastore_church_get_value_gp('emailer_sender_name', get_bloginfo('name'));
				$sender_email = pastore_church_get_value_gp('emailer_sender_email');
				if (empty($sender_email)) $sender_email = pastore_church_get_theme_option('contact_email');
				if (empty($sender_email)) $sender_email = get_bloginfo('admin_email');
				if (empty($sender_email)) {
					$this->error = esc_html__('Sender email is empty! Operation canceled!', 'pastore-church');
					break;
				}
				$headers = 'From: ' . strip_tags($sender_name) . ' <' . trim($sender_email) . '>' . "\r\n"
							. 'Content-Type: text/html; charset=UTF-8' . "\r\n";

				$subscribers_update = isset($_POST['emailer_subscribers_update']);
				$subscribers_delete = isset($_POST['emailer_subscribers_delete']);
				$subscribers_clear  = isset($_POST['emailer_subscribers_clear']);

				// Send email
				$new_list = array();
				$list = array();
				$cnt = 0;
				if (is_array($subscribers) && count($subscribers) > 0) {
					foreach ($subscribers as $email) {
						$email = trim(chop($email));
						if (empty($email)) continue;
						if (!preg_match('/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}/', $email)) continue;
						$list[] = $email;
						$cnt++;
						if ($cnt >= $this->max_recipients_in_one_letter) {
							$rez = $mail == 'mail' 
								? @mail( join(',', $list), $subject, $message, $headers)
								: @wp_mail( $list, $subject, $message, $headers, $attach );
							if (!$rez) {
								$err_msg = esc_html__('Error occured when send message!', 'pastore-church');
								$cnt = 0;
								break;
							}
							if ($subscribers_update && $group!='none') $new_list = array_merge($new_list, $list);
							$list = array();
							$cnt = 0;
						}
					}
				}
				$add_msg = $err_msg = '';
				if ($cnt > 0) {
					$rez = $mail == 'mail' 
						? @mail( join(',', $list), $subject, $message, $headers)
						: @wp_mail( $list, $subject, $message, $headers, $attach );
					if (!$rez)
						$err_msg = esc_html__('Error occured when send message!', 'pastore-church');
					if ($subscribers_update && $group!='none') $new_list = array_merge($new_list, $list);
					$list = array();
					$cnt = 0;
				}
				if ($subscribers_update && $group!='none') {
					$rez = array();
					if (is_array($this->subscribers[$group]) && count($this->subscribers[$group]) > 0) {
						foreach ($this->subscribers[$group] as $k=>$v) {
							if (!$subscribers_clear && !empty($v))
								$rez[$k] = $v;
						}
					}
					if (is_array($new_list) && count($new_list) > 0) {
						foreach ($new_list as $v) {
							$rez[$v] = '';
						}
					}
					$this->subscribers[$group] = $rez;
					update_option('pastore_church_emailer_subscribers', $this->subscribers);
					$add_msg .= esc_html__('The subscriber list is updated', 'pastore-church');
				} else if ($subscribers_delete && $group!='none') {
					unset($this->subscribers[$group]);
					update_option('pastore_church_emailer_subscribers', $this->subscribers);
					$add_msg .= esc_html__('The subscriber list is cleared', 'pastore-church');
				}
				if ($err_msg)
					$this->error = $err_msg;
				else
					$this->success = esc_html__('E-Mail was send successfull!', 'pastore-church') . ($add_msg ? ' '.trim($add_msg) : '');
			} while (false);
		}

		?>
		<div class="trx_emailer">
			<h2 class="trx_emailer_title"><?php esc_html_e('ThemeREX Emailer', 'pastore-church'); ?></h2>
			<div class="trx_emailer_result">
				<?php if (!empty($this->error)) { ?>
				<div class="error">
					<p><?php echo trim($this->error); ?></p>
				</div>
				<?php } ?>
				<?php if (!empty($this->success)) { ?>
				<div class="updated">
					<p><?php echo trim($this->success); ?></p>
				</div>
				<?php } ?>
			</div>
	
			<form id="trx_emailer_form" action="#" method="post" enctype="multipart/form-data">

				<input type="hidden" value="<?php echo esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))); ?>" name="nonce" />

				<div class="trx_emailer_block">
					<fieldset class="trx_emailer_block_inner">
						<legend> <?php esc_html_e('Letter data', 'pastore-church'); ?> </legend>
						<div class="trx_emailer_fields">
							<div class="trx_emailer_field trx_emailer_subject">
								<label for="emailer_subject"><?php esc_html_e('Subject:', 'pastore-church'); ?></label>
								<input type="text" value="<?php echo esc_attr($subject); ?>" name="emailer_subject" id="emailer_subject" />
							</div>
							<?php if ($mail=='wp_mail') { ?>
								<div class="trx_emailer_field trx_emailer_attachment">
									<label for="emailer_attachment"><?php esc_html_e('Attachment:', 'pastore-church'); ?></label>
									<input type="file" name="emailer_attachment" id="emailer_attachment" />
								</div>
							<?php } ?>
							<div class="trx_emailer_field trx_emailer_message">
								<?php
								wp_editor( $message, 'emailer_message', array(
									'wpautop' => false,
									'textarea_rows' => 10
								));
								?>								
							</div>
						</div>
					</fieldset>
				</div>
	
				<div class="trx_emailer_block">
					<fieldset class="trx_emailer_block_inner">
						<legend> <?php esc_html_e('Subscribers', 'pastore-church'); ?> </legend>
						<div class="trx_emailer_fields">
							<div class="trx_emailer_field trx_emailer_group">
								<label for="emailer_group"><?php esc_html_e('Select group:', 'pastore-church'); ?></label>
								<select name="emailer_group" id="emailer_group">
									<option value="none"<?php echo ('none'==$group ? ' selected="selected"' : ''); ?>><?php esc_html_e('- Select group -', 'pastore-church'); ?></option>
									<?php
									if (is_array($this->subscribers) && count($this->subscribers) > 0) {
										foreach ($this->subscribers as $gr=>$list) {
											echo '<option value="'.esc_attr($gr).'"'.($group==$gr ? ' selected="selected"' : '').'>'.pastore_church_strtoproper($gr).'</option>';
										}
									}
									?>
								</select>
								<input type="checkbox" name="emailer_subscribers_update" id="emailer_subscribers_update" value="1"<?php echo !empty($subscribers_update) ? ' checked="checked"' : ''; ?> /><label for="emailer_subscribers_update" class="inline" title="<?php esc_attr_e('Update the subscribers list for selected group', 'pastore-church'); ?>"><?php esc_html_e('Update', 'pastore-church'); ?></label>
								<input type="checkbox" name="emailer_subscribers_clear" id="emailer_subscribers_clear" value="1"<?php echo !empty($subscribers_clear) ? ' checked="checked"' : ''; ?> /><label for="emailer_subscribers_clear" class="inline" title="<?php esc_attr_e('Clear this group from not confirmed emails after send', 'pastore-church'); ?>"><?php esc_html_e('Clear', 'pastore-church'); ?></label>
								<input type="checkbox" name="emailer_subscribers_delete" id="emailer_subscribers_delete" value="1"<?php echo !empty($subscribers_delete) ? ' checked="checked"' : ''; ?> /><label for="emailer_subscribers_delete" class="inline" title="<?php esc_attr_e('Delete this group after send', 'pastore-church'); ?>"><?php esc_html_e('Delete', 'pastore-church'); ?></label>
							</div>
							<div class="trx_emailer_field trx_emailer_subscribers2">
								<label for="emailer_subscribers" class="big"><?php esc_html_e('List of recipients:', 'pastore-church'); ?></label>
								<textarea name="emailer_subscribers" id="emailer_subscribers"><?php echo join("\n", $subscribers); ?></textarea>
							</div>
							<div class="trx_emailer_field trx_emailer_sender_name">
								<label for="emailer_sender_name"><?php esc_html_e('Sender name:', 'pastore-church'); ?></label>
								<input type="text" name="emailer_sender_name" id="emailer_sender_name" value="<?php echo esc_attr($sender_name); ?>" /><br />
							</div>
							<div class="trx_emailer_field trx_emailer_sender_email">
								<label for="emailer_sender_email"><?php esc_html_e('Sender email:', 'pastore-church'); ?></label>
								<input type="text" name="emailer_sender_email" id="emailer_sender_email" value="<?php echo esc_attr($sender_email); ?>" />
							</div>
						</div>
					</fieldset>
				</div>
	
				<div class="trx_emailer_buttons">
					<a href="#" id="trx_emailer_send"><?php echo esc_html_e('Send', 'pastore-church'); ?></a>
				</div>
	
			</form>
		</div>
		<?php
	}

}


//==========================================================================================
// Utilities
//==========================================================================================

// Save e-mail in subscribe list
if ( !function_exists( 'pastore_church_callback_emailer_submit' ) ) {
	function pastore_church_callback_emailer_submit() {

		if ( !wp_verify_nonce( pastore_church_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'');

		$group = $_REQUEST['group'];
		$email = $_REQUEST['email'];

		if (preg_match('/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}/', $email)) {
			$subscribers = pastore_church_emailer_group_getlist($group);
			if (isset($subscribers[$group][$email]))
				$response['error'] = esc_html__('E-mail address already in the subscribers list!', 'pastore-church');
			else {
				$subscribers[$group][$email] = md5(mt_rand());
				update_option('pastore_church_emailer_subscribers', $subscribers);
				$subj = sprintf(esc_html__('Site %s - Subscribe confirmation', 'pastore-church'), get_bloginfo('site_name'));
				$url = admin_url('admin-ajax.php');
				$link = $url . (pastore_church_strpos($url, '?')===false ? '?' : '') . 'action=emailer_confirm&nonce='.urlencode($subscribers[$group][$email]).'&email='.urlencode($email).'&group='.urlencode($group);
				$msg = sprintf(__("You or someone else added this e-mail address into our subcribtion list.\nPlease, confirm your wish to receive newsletters from our website by clicking on the link below:\n\n<a href=\"%s\">%s</a>\n\nIf you do not wiish to subscribe to our newsletters, simply ignore this message.", 'pastore-church'), $link, $link);
				$sender_name = get_bloginfo('name');
				$sender_email = pastore_church_get_theme_option('contact_email');
				if (empty($sender_email)) $sender_email = get_bloginfo('admin_email');
				$headers = 'From: ' . strip_tags($sender_name).' <' . trim($sender_email) . '>' . "\r\n"
							. 'Content-Type: text/html' . "\r\n";
				$mail = pastore_church_get_theme_option('mail_function');
				$rez = $mail == 'mail' 
					? @mail( $email, $subj, nl2br($msg), $headers)
					: @wp_mail( $email, $subj, nl2br($msg), $headers );
				if (!$rez) {
					$response['error'] = esc_html__('Error send message!', 'pastore-church');
				}
			}
		} else
			$response['error'] = esc_html__('E-mail address is not valid!', 'pastore-church');
		echo json_encode($response);
		die();
	}
}

// Confirm e-mail in subscribe list
if ( !function_exists( 'pastore_church_callback_emailer_confirm' ) ) {
	function pastore_church_callback_emailer_confirm() {
		
		$group = $_REQUEST['group'];
		$email = $_REQUEST['email'];
		$nonce = $_REQUEST['nonce'];
		if (preg_match('/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}/', $email)) {
			$subscribers = pastore_church_emailer_group_getlist($group);
			if (isset($subscribers[$group][$email])) {
				if ($subscribers[$group][$email] == $nonce) {
					$subscribers[$group][$email] = '';
					update_option('pastore_church_emailer_subscribers', $subscribers);
					pastore_church_set_system_message(esc_html__('Confirmation complete! E-mail address succefully added in the subscribers list!', 'pastore-church'), 'success');
					//header('Location: '.home_url('/'));
					wp_safe_redirect( home_url('/') );
				} else if ($subscribers[$group][$email] != '') {
					pastore_church_set_system_message(esc_html__('Bad confirmation code!', 'pastore-church'), 'error');
					//header('Location: '.home_url('/'));
					wp_safe_redirect( home_url('/') );
				} else {
					pastore_church_set_system_message(esc_html__('E-mail address already exists in the subscribers list!', 'pastore-church'), 'error');
					//header('Location: '.home_url('/'));
					wp_safe_redirect( home_url('/') );
				}
			}
		}
		die();
	}
}


// Get subscribers list if group changed
if ( !function_exists( 'pastore_church_callback_emailer_group_getlist' ) ) {
	function pastore_church_callback_emailer_group_getlist() {
		
		if ( !wp_verify_nonce( pastore_church_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'', 'subscribers' => '');
		
		$group = $_REQUEST['group'];
		$subscribers = pastore_church_emailer_group_getlist($group);
		$list = array();
		if (isset($subscribers[$group]) && is_array($subscribers[$group]) && count($subscribers[$group]) > 0) {
			foreach ($subscribers[$group] as $k=>$v) {
				if (empty($v))
					$list[] = $k;
			}
		}
		$response['subscribers'] = join("\n", $list);

		echo json_encode($response);
		die();
	}
}

// Get Subscribers list
if ( !function_exists( 'pastore_church_emailer_group_getlist' ) ) {
	function pastore_church_emailer_group_getlist($group='') {
		$subscribers = get_option('pastore_church_emailer_subscribers', array());
		if (!is_array($subscribers))
			$subscribers = array();
		if (!empty($group) && (!isset($subscribers[$group]) || !is_array($subscribers[$group])))
			$subscribers[$group] = array();
		if (is_array($subscribers) && count($subscribers) > 0) {
			$need_save = false;
			foreach ($subscribers as $grp=>$list) {
				if (isset($list[0])) {	// Plain array - old format - convert it
					$rez = array();
					foreach ($list as $v) {
						$rez[$v] = '';
					}
					$subscribers[$grp] = $rez;
					$need_save = true;
				}
			}
			if ($need_save)
				update_option('pastore_church_emailer_subscribers', $subscribers);
		}
		return $subscribers;
	}
}
?>