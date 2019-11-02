<?php
/**
 * ThemeREX Framework: debug utilities (for internal use only!)
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $TRX_DONATIONS_GLOBALS;
if (!isset($TRX_DONATIONS_GLOBALS)) $TRX_DONATIONS_GLOBALS = array();
$TRX_DONATIONS_GLOBALS['debug_file_name'] = 'debug.log';
$TRX_DONATIONS_GLOBALS['max_dump_level'] = -1;

if (!function_exists('trx_donations_debug_set_max_dump_level')) {
	function trx_donations_debug_set_max_dump_level($lvl) {
		global $TRX_DONATIONS_GLOBALS;
		$TRX_DONATIONS_GLOBALS['max_dump_level'] = $lvl;
	}
}

if (!function_exists('trx_donations_debug_die_message')) {
	function trx_donations_debug_die_message($msg) {
		trx_donations_debug_trace_message($msg);
		die($msg);
	}
}

if (!function_exists('trx_donations_debug_trace_message')) {
	function trx_donations_debug_trace_message($msg) {
		global $TRX_DONATIONS_GLOBALS;
		file_put_contents(substr(plugin_dir_path(__FILE__), 0, -9).($TRX_DONATIONS_GLOBALS['debug_file_name']), date('d.m.Y H:i:s')." $msg\n", FILE_APPEND);
	}
}

if (!function_exists('trx_donations_debug_calls_stack_screen')) {
	function trx_donations_debug_calls_stack_screen($level=-1) {
		$s = debug_backtrace();
		array_shift($s);
		trx_donations_debug_dump_screen($s, $level);
	}
}

if (!function_exists('trx_donations_debug_calls_stack_file')) {
	function trx_donations_debug_calls_stack_file($level=-1) {
		$s = debug_backtrace();
		array_shift($s);
		trx_donations_debug_dump_file($s, $level);
	}
}

if (!function_exists('trx_donations_debug_dump_screen')) {
	function trx_donations_debug_dump_screen(&$var, $level=-1) {
		if ((is_array($var) || is_object($var)) && count($var))
			echo "<pre>\n".nl2br(esc_html(trx_donations_debug_dump_var($var, 0, $level)))."</pre>\n";
		else
			echo "<tt>".nl2br(esc_html(trx_donations_debug_dump_var($var, 0, $level)))."</tt>\n";
	}
}

if (!function_exists('trx_donations_debug_dump_file')) {
	function trx_donations_debug_dump_file(&$var, $level=-1) {
		trx_donations_debug_trace_message("\n\n".trx_donations_debug_dump_var($var, 0, $level));
	}
}

if (!function_exists('trx_donations_debug_dump_var')) {
	function trx_donations_debug_dump_var(&$var, $level=0, $max_level=-1)  {
		global $TRX_DONATIONS_GLOBALS;
		if ($max_level < 0) $max_level = $TRX_DONATIONS_GLOBALS['max_dump_level'];
		if (is_array($var)) $type="Array[".count($var)."]";
		else if (is_object($var)) $type="Object";
		else $type="";
		if ($type) {
			$rez = "$type\n";
			if ($max_level<0 || $level < $max_level) {
				for (Reset($var), $level++; list($k, $v)=each($var); ) {
					if (is_array($v) && $k==="GLOBALS") continue;
					for ($i=0; $i<$level*3; $i++) $rez .= " ";
					$rez .= $k.' => '. trx_donations_debug_dump_var($v, $level, $max_level);
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

if (!function_exists('trx_donations_debug_dump_wp')) {
	function trx_donations_debug_dump_wp($query=null) {
		global $wp_query;
		if (!$query) $query = $wp_query;
		echo "<tt>"
			."<br>admin=".is_admin()
			."<br>mobile=".wp_is_mobile()
			."<br>custom preview=".is_custom_preview()
			."<br>main_query=".is_main_query()."  query=".esc_html($query->is_main_query())
			."<br>home=".is_home()."  query=".esc_html($query->is_home())
			."<br>fp=".is_front_page()."  query=".esc_html($query->is_front_page())
			."<br>query->is_posts_page=".esc_html($query->is_posts_page)
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
			."<br><br />"
			."</tt>";
	}
}
?>