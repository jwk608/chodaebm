<?php
/**
 * Pastore Church Framework: messages subsystem
 *
 * @package	pastore_church
 * @since	pastore_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('pastore_church_messages_theme_setup')) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_messages_theme_setup' );
	function pastore_church_messages_theme_setup() {
		// Core messages strings
		add_action('pastore_church_action_add_scripts_inline', 'pastore_church_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('pastore_church_get_error_msg')) {
	function pastore_church_get_error_msg() {
		return pastore_church_storage_get('error_msg');
	}
}

if (!function_exists('pastore_church_set_error_msg')) {
	function pastore_church_set_error_msg($msg) {
		$msg2 = pastore_church_get_error_msg();
		pastore_church_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('pastore_church_get_success_msg')) {
	function pastore_church_get_success_msg() {
		return pastore_church_storage_get('success_msg');
	}
}

if (!function_exists('pastore_church_set_success_msg')) {
	function pastore_church_set_success_msg($msg) {
		$msg2 = pastore_church_get_success_msg();
		pastore_church_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('pastore_church_get_notice_msg')) {
	function pastore_church_get_notice_msg() {
		return pastore_church_storage_get('notice_msg');
	}
}

if (!function_exists('pastore_church_set_notice_msg')) {
	function pastore_church_set_notice_msg($msg) {
		$msg2 = pastore_church_get_notice_msg();
		pastore_church_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('pastore_church_set_system_message')) {
	function pastore_church_set_system_message($msg, $status='info', $hdr='') {
		update_option('pastore_church_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('pastore_church_get_system_message')) {
	function pastore_church_get_system_message($del=false) {
		$msg = get_option('pastore_church_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			pastore_church_del_system_message();
		return $msg;
	}
}

if (!function_exists('pastore_church_del_system_message')) {
	function pastore_church_del_system_message() {
		delete_option('pastore_church_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('pastore_church_messages_add_scripts_inline')) {
	function pastore_church_messages_add_scripts_inline() {
		echo '<script type="text/javascript">'
			
			. "if (typeof PASTORE_CHURCH_STORAGE == 'undefined') var PASTORE_CHURCH_STORAGE = {};"
			
			// Strings for translation
			. 'PASTORE_CHURCH_STORAGE["strings"] = {'
				. 'ajax_error: 			"' . addslashes(esc_html__('Invalid server answer', 'pastore-church')) . '",'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'pastore-church')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'pastore-church')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'pastore-church')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'pastore-church')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'pastore-church')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'pastore-church')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'pastore-church')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'pastore-church')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'pastore-church')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'pastore-church')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'pastore-church')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'pastore-church')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'pastore-church')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'pastore-church')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'pastore-church')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'pastore-church')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'pastore-church')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'pastore-church')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'pastore-church')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'pastore-church')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'pastore-church')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'pastore-church')) . '",'
				. 'login_empty:			"' . addslashes(esc_html__('The Login field can\'t be empty', 'pastore-church')) . '",'
				. 'login_long:			"' . addslashes(esc_html__('Too long login field', 'pastore-church')) . '",'
				. 'login_success:		"' . addslashes(esc_html__('Login success! The page will be reloaded in 3 sec.', 'pastore-church')) . '",'
				. 'login_failed:		"' . addslashes(esc_html__('Login failed!', 'pastore-church')) . '",'
				. 'password_empty:		"' . addslashes(esc_html__('The password can\'t be empty and shorter then 4 characters', 'pastore-church')) . '",'
				. 'password_long:		"' . addslashes(esc_html__('Too long password', 'pastore-church')) . '",'
				. 'password_not_equal:	"' . addslashes(esc_html__('The passwords in both fields are not equal', 'pastore-church')) . '",'
				. 'registration_success:"' . addslashes(esc_html__('Registration success! Please log in!', 'pastore-church')) . '",'
				. 'registration_failed:	"' . addslashes(esc_html__('Registration failed!', 'pastore-church')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'pastore-church')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'pastore-church')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'pastore-church')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'pastore-church')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'pastore-church')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'pastore-church')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'pastore-church')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'pastore-church')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'pastore-church')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'pastore-church')) . '"'
				. '};'
			
			. '</script>';
	}
}
?>