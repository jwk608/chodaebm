<?php
/**
 * Pastore Church Framework: theme variables storage
 *
 * @package	pastore_church
 * @since	pastore_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('pastore_church_storage_get')) {
	function pastore_church_storage_get($var_name, $default='') {
		global $PASTORE_CHURCH_STORAGE;
		return isset($PASTORE_CHURCH_STORAGE[$var_name]) ? $PASTORE_CHURCH_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('pastore_church_storage_set')) {
	function pastore_church_storage_set($var_name, $value) {
		global $PASTORE_CHURCH_STORAGE;
		$PASTORE_CHURCH_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('pastore_church_storage_empty')) {
	function pastore_church_storage_empty($var_name, $key='', $key2='') {
		global $PASTORE_CHURCH_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($PASTORE_CHURCH_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($PASTORE_CHURCH_STORAGE[$var_name][$key]);
		else
			return empty($PASTORE_CHURCH_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('pastore_church_storage_isset')) {
	function pastore_church_storage_isset($var_name, $key='', $key2='') {
		global $PASTORE_CHURCH_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($PASTORE_CHURCH_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($PASTORE_CHURCH_STORAGE[$var_name][$key]);
		else
			return isset($PASTORE_CHURCH_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('pastore_church_storage_inc')) {
	function pastore_church_storage_inc($var_name, $value=1) {
		global $PASTORE_CHURCH_STORAGE;
		if (empty($PASTORE_CHURCH_STORAGE[$var_name])) $PASTORE_CHURCH_STORAGE[$var_name] = 0;
		$PASTORE_CHURCH_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('pastore_church_storage_concat')) {
	function pastore_church_storage_concat($var_name, $value) {
		global $PASTORE_CHURCH_STORAGE;
		if (empty($PASTORE_CHURCH_STORAGE[$var_name])) $PASTORE_CHURCH_STORAGE[$var_name] = '';
		$PASTORE_CHURCH_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('pastore_church_storage_get_array')) {
	function pastore_church_storage_get_array($var_name, $key, $key2='', $default='') {
		global $PASTORE_CHURCH_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($PASTORE_CHURCH_STORAGE[$var_name][$key]) ? $PASTORE_CHURCH_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($PASTORE_CHURCH_STORAGE[$var_name][$key][$key2]) ? $PASTORE_CHURCH_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('pastore_church_storage_set_array')) {
	function pastore_church_storage_set_array($var_name, $key, $value) {
		global $PASTORE_CHURCH_STORAGE;
		if (!isset($PASTORE_CHURCH_STORAGE[$var_name])) $PASTORE_CHURCH_STORAGE[$var_name] = array();
		if ($key==='')
			$PASTORE_CHURCH_STORAGE[$var_name][] = $value;
		else
			$PASTORE_CHURCH_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('pastore_church_storage_set_array2')) {
	function pastore_church_storage_set_array2($var_name, $key, $key2, $value) {
		global $PASTORE_CHURCH_STORAGE;
		if (!isset($PASTORE_CHURCH_STORAGE[$var_name])) $PASTORE_CHURCH_STORAGE[$var_name] = array();
		if (!isset($PASTORE_CHURCH_STORAGE[$var_name][$key])) $PASTORE_CHURCH_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$PASTORE_CHURCH_STORAGE[$var_name][$key][] = $value;
		else
			$PASTORE_CHURCH_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('pastore_church_storage_set_array_after')) {
	function pastore_church_storage_set_array_after($var_name, $after, $key, $value='') {
		global $PASTORE_CHURCH_STORAGE;
		if (!isset($PASTORE_CHURCH_STORAGE[$var_name])) $PASTORE_CHURCH_STORAGE[$var_name] = array();
		if (is_array($key))
			pastore_church_array_insert_after($PASTORE_CHURCH_STORAGE[$var_name], $after, $key);
		else
			pastore_church_array_insert_after($PASTORE_CHURCH_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('pastore_church_storage_set_array_before')) {
	function pastore_church_storage_set_array_before($var_name, $before, $key, $value='') {
		global $PASTORE_CHURCH_STORAGE;
		if (!isset($PASTORE_CHURCH_STORAGE[$var_name])) $PASTORE_CHURCH_STORAGE[$var_name] = array();
		if (is_array($key))
			pastore_church_array_insert_before($PASTORE_CHURCH_STORAGE[$var_name], $before, $key);
		else
			pastore_church_array_insert_before($PASTORE_CHURCH_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('pastore_church_storage_push_array')) {
	function pastore_church_storage_push_array($var_name, $key, $value) {
		global $PASTORE_CHURCH_STORAGE;
		if (!isset($PASTORE_CHURCH_STORAGE[$var_name])) $PASTORE_CHURCH_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($PASTORE_CHURCH_STORAGE[$var_name], $value);
		else {
			if (!isset($PASTORE_CHURCH_STORAGE[$var_name][$key])) $PASTORE_CHURCH_STORAGE[$var_name][$key] = array();
			array_push($PASTORE_CHURCH_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('pastore_church_storage_pop_array')) {
	function pastore_church_storage_pop_array($var_name, $key='', $defa='') {
		global $PASTORE_CHURCH_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($PASTORE_CHURCH_STORAGE[$var_name]) && is_array($PASTORE_CHURCH_STORAGE[$var_name]) && count($PASTORE_CHURCH_STORAGE[$var_name]) > 0) 
				$rez = array_pop($PASTORE_CHURCH_STORAGE[$var_name]);
		} else {
			if (isset($PASTORE_CHURCH_STORAGE[$var_name][$key]) && is_array($PASTORE_CHURCH_STORAGE[$var_name][$key]) && count($PASTORE_CHURCH_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($PASTORE_CHURCH_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('pastore_church_storage_inc_array')) {
	function pastore_church_storage_inc_array($var_name, $key, $value=1) {
		global $PASTORE_CHURCH_STORAGE;
		if (!isset($PASTORE_CHURCH_STORAGE[$var_name])) $PASTORE_CHURCH_STORAGE[$var_name] = array();
		if (empty($PASTORE_CHURCH_STORAGE[$var_name][$key])) $PASTORE_CHURCH_STORAGE[$var_name][$key] = 0;
		$PASTORE_CHURCH_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('pastore_church_storage_concat_array')) {
	function pastore_church_storage_concat_array($var_name, $key, $value) {
		global $PASTORE_CHURCH_STORAGE;
		if (!isset($PASTORE_CHURCH_STORAGE[$var_name])) $PASTORE_CHURCH_STORAGE[$var_name] = array();
		if (empty($PASTORE_CHURCH_STORAGE[$var_name][$key])) $PASTORE_CHURCH_STORAGE[$var_name][$key] = '';
		$PASTORE_CHURCH_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('pastore_church_storage_call_obj_method')) {
	function pastore_church_storage_call_obj_method($var_name, $method, $param=null) {
		global $PASTORE_CHURCH_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($PASTORE_CHURCH_STORAGE[$var_name]) ? $PASTORE_CHURCH_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($PASTORE_CHURCH_STORAGE[$var_name]) ? $PASTORE_CHURCH_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('pastore_church_storage_get_obj_property')) {
	function pastore_church_storage_get_obj_property($var_name, $prop, $default='') {
		global $PASTORE_CHURCH_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($PASTORE_CHURCH_STORAGE[$var_name]->$prop) ? $PASTORE_CHURCH_STORAGE[$var_name]->$prop : $default;
	}
}
?>