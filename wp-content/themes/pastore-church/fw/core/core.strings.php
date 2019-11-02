<?php
/**
 * Pastore Church Framework: strings manipulations
 *
 * @package	pastore_church
 * @since	pastore_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'PASTORE_CHURCH_MULTIBYTE' ) ) define( 'PASTORE_CHURCH_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('pastore_church_strlen')) {
	function pastore_church_strlen($text) {
		return PASTORE_CHURCH_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('pastore_church_strpos')) {
	function pastore_church_strpos($text, $char, $from=0) {
		return PASTORE_CHURCH_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('pastore_church_strrpos')) {
	function pastore_church_strrpos($text, $char, $from=0) {
		return PASTORE_CHURCH_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('pastore_church_substr')) {
	function pastore_church_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = pastore_church_strlen($text)-$from;
		}
		return PASTORE_CHURCH_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('pastore_church_strtolower')) {
	function pastore_church_strtolower($text) {
		return PASTORE_CHURCH_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('pastore_church_strtoupper')) {
	function pastore_church_strtoupper($text) {
		return PASTORE_CHURCH_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('pastore_church_strtoproper')) {
	function pastore_church_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<pastore_church_strlen($text); $i++) {
			$ch = pastore_church_substr($text, $i, 1);
			$rez .= pastore_church_strpos(' .,:;?!()[]{}+=', $last)!==false ? pastore_church_strtoupper($ch) : pastore_church_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('pastore_church_strrepeat')) {
	function pastore_church_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('pastore_church_strshort')) {
	function pastore_church_strshort($str, $maxlength, $add='...') {
	//	if ($add && pastore_church_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= pastore_church_strlen($str)) 
			return strip_tags($str);
		$str = pastore_church_substr(strip_tags($str), 0, $maxlength - pastore_church_strlen($add));
		$ch = pastore_church_substr($str, $maxlength - pastore_church_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = pastore_church_strlen($str) - 1; $i > 0; $i--)
				if (pastore_church_substr($str, $i, 1) == ' ') break;
			$str = trim(pastore_church_substr($str, 0, $i));
		}
		if (!empty($str) && pastore_church_strpos(',.:;-', pastore_church_substr($str, -1))!==false) $str = pastore_church_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('pastore_church_strclear')) {
	function pastore_church_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (pastore_church_substr($text, 0, pastore_church_strlen($open))==$open) {
					$pos = pastore_church_strpos($text, '>');
					if ($pos!==false) $text = pastore_church_substr($text, $pos+1);
				}
				if (pastore_church_substr($text, -pastore_church_strlen($close))==$close) $text = pastore_church_substr($text, 0, pastore_church_strlen($text) - pastore_church_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('pastore_church_get_slug')) {
	function pastore_church_get_slug($title) {
		return pastore_church_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('pastore_church_strmacros')) {
	function pastore_church_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('pastore_church_unserialize')) {
	function pastore_church_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			//if ($data===false) $data = @unserialize(str_replace(array("\n", "\r"), array('\\n','\\r'), $str));
			return $data;
		} else
			return $str;
	}
}
?>