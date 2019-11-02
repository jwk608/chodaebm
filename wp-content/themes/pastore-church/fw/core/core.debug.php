<?php
/**
 * Pastore Church Framework: debug utilities (for internal use only!)
 *
 * @package	pastore_church
 * @since	pastore_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'pastore_church_debug_theme_setup' ) ) {
	add_action( 'pastore_church_action_before_init_theme', 'pastore_church_debug_theme_setup', 11 );
	function pastore_church_debug_theme_setup() {
		if (pastore_church_get_theme_option('debug_mode')=='yes' && pastore_church_get_theme_setting('allow_profiler')) {
			if (is_admin())
				add_action('admin_print_footer_scripts',	'pastore_church_profiler_show', 100);
			else
				add_action('wp_footer',		'pastore_church_profiler_show', 100);
		}
	}
}

// Short analogs for debug functions
if (!function_exists('dcl')) {	function dcl($msg)	{ 	if (is_user_logged_in()) echo '<br>"' . esc_html($msg) . '"<br>'; } }			// Console log - output any message on the screen
if (!function_exists('dco')) {	function dco(&$var, $lvl=-1)	{ 	if (is_user_logged_in()) pastore_church_debug_dump_screen($var, $lvl); } }	// Console obj - output object structure on the screen
if (!function_exists('dcs')) {	function dcs($lvl=-1){ 	if (is_user_logged_in()) pastore_church_debug_calls_stack_screen($lvl); } }			// Console stack - output calls stack on the screen
if (!function_exists('dcw')) {	function dcw($q=null) {	if (is_user_logged_in()) pastore_church_debug_dump_wp($q); } }						// Console WP - output WP is_... states on the screen
if (!function_exists('ddo')) {	function ddo(&$var, $lvl=-1)	{ 	if (is_user_logged_in()) pastore_church_debug_dump_var($var, $lvl); } }	// Return obj - return object structure
if (!function_exists('dfl')) {	function dfl($var)	{	if (is_user_logged_in()) pastore_church_debug_trace_message($var); } }				// File log - output any message into file debug.log
if (!function_exists('dfo')) {	function dfo(&$var, $lvl=-1)	{ 	if (is_user_logged_in()) pastore_church_debug_dump_file($var, $lvl); } }	// File obj - output object structure into file debug.log
if (!function_exists('dfs')) {	function dfs($lvl=-1){ 	if (is_user_logged_in()) pastore_church_debug_calls_stack_file($lvl); } }				// File stack - output calls stack into file debug.log


if (!function_exists('pastore_church_debug_die_message')) {
	function pastore_church_debug_die_message($msg) {
		pastore_church_debug_trace_message($msg);
		die($msg);
	}
}

if (!function_exists('pastore_church_debug_trace_message')) {
	function pastore_church_debug_trace_message($msg) {
		pastore_church_fpc(get_stylesheet_directory().'/debug.log', date('d.m.Y H:i:s')." $msg\n", FILE_APPEND);
	}
}

if (!function_exists('pastore_church_debug_calls_stack_screen')) {
	function pastore_church_debug_calls_stack_screen($level=-1) {
		$s = debug_backtrace();
		array_shift($s);
		pastore_church_debug_dump_screen($s, $level);
	}
}

if (!function_exists('pastore_church_debug_calls_stack_file')) {
	function pastore_church_debug_calls_stack_file($level=-1) {
		$s = debug_backtrace();
		array_shift($s);
		pastore_church_debug_dump_file($s, $level);
	}
}

if (!function_exists('pastore_church_debug_dump_screen')) {
	function pastore_church_debug_dump_screen(&$var, $level=-1) {
		if ((is_array($var) || is_object($var)) && count($var))
			echo "<pre>\n".nl2br(esc_html(pastore_church_debug_dump_var($var, 0, $level)))."</pre>\n";
		else
			echo "<tt>".nl2br(esc_html(pastore_church_debug_dump_var($var, 0, $level)))."</tt>\n";
	}
}

if (!function_exists('pastore_church_debug_dump_file')) {
	function pastore_church_debug_dump_file(&$var, $level=-1) {
		pastore_church_debug_trace_message("\n\n".pastore_church_debug_dump_var($var, 0, $level));
	}
}

if (!function_exists('pastore_church_debug_dump_var')) {
	function pastore_church_debug_dump_var(&$var, $level=0, $max_level=-1)  {
		if (is_array($var)) $type="Array[".count($var)."]";
		else if (is_object($var)) $type="Object";
		else $type="";
		if ($type) {
			$rez = "$type\n";
			if ($max_level<0 || $level < $max_level) {
				for (Reset($var), $level++; list($k, $v)=each($var); ) {
					if (is_array($v) && $k==="GLOBALS") continue;
					for ($i=0; $i<$level*3; $i++) $rez .= " ";
					$rez .= $k.' => '. pastore_church_debug_dump_var($v, $level, $max_level);
				}
			}
		} else if (is_bool($var))
			$rez = ($var ? 'true' : 'false')."\n";
		else if (is_long($var) || is_float($var) || intval($var) != 0)
			$rez = $var."\n";
		else
			$rez = '"'.($var).'"'."\n";
		return $rez;
	}
}

if (!function_exists('pastore_church_debug_dump_wp')) {
	function pastore_church_debug_dump_wp($query=null) {
		global $wp_query;
		if (!$query) $query = $wp_query;
		echo "<tt>"
			."<br>admin=".is_admin()
			."<br>mobile=".wp_is_mobile()
			."<br>main_query=".is_main_query()."  query=".esc_html($query->is_main_query())
			."<br>query->is_posts_page=".esc_html($query->is_posts_page)
			."<br>home=".is_home()."  query=".esc_html($query->is_home())
			."<br>fp=".is_front_page()."  query=".esc_html($query->is_front_page())
			."<br>search=".is_search()."  query=".esc_html($query->is_search())
			."<br>category=".is_category()."  query=".esc_html($query->is_category())
			."<br>tag=".is_tag()."  query=".esc_html($query->is_tag())
			."<br>archive=".is_archive()."  query=".esc_html($query->is_archive())
			."<br>day=".is_day()."  query=".esc_html($query->is_day())
			."<br>month=".is_month()."  query=".esc_html($query->is_month())
			."<br>year=".is_year()."  query=".esc_html($query->is_year())
			."<br>author=".is_author()."  query=".esc_html($query->is_author())
			."<br>page=".is_page()."  query=".esc_html($query->is_page())
			."<br>single=".is_single()."  query=".esc_html($query->is_single())
			."<br>singular=".is_singular()."  query=".esc_html($query->is_singular())
			."<br>attachment=".is_attachment()."  query=".esc_html($query->is_attachment())
			."<br>WooCommerce=".esc_html(function_exists('pastore_church_is_woocommerce_page') && pastore_church_is_woocommerce_page())
			."<br><br />"
			."</tt>";
	}
}


/* Profiler functions
---------------------------------------------------------- */
// Add profiler point
if (!function_exists('pastore_church_profiler_add_point')) {
	function pastore_church_profiler_add_point($name, $theme_mode=true, $data=false) {
		global $timestart;
		if (pastore_church_get_theme_option('debug_mode')=='yes' && pastore_church_get_theme_setting('allow_profiler')) {
			if ($data===false) {
				$data = array(
					'mode' => $theme_mode,
					'time' => microtime(true)-max(0, $timestart),
					'memory' => memory_get_usage(),
					'queries' => get_num_queries()
					);
			}
			pastore_church_storage_set_array('profiler_points', microtime(true).'|'.$name, $data);
		}
	}
}

// Show time and memory statistic
if (!function_exists('pastore_church_profiler_show')) {
	function pastore_church_profiler_show() {

	}
}
?>