<?php
/**
 * Pastore Church Framework: ini-files manipulations
 *
 * @package	pastore_church
 * @since	pastore_church 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


//  Get value by name from .ini-file
if (!function_exists('pastore_church_ini_get_value')) {
	function pastore_church_ini_get_value($file, $name, $defa='') {
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = pastore_church_fga($file);
			} else
				return $defa;
		}
		$name = pastore_church_strtolower($name);
		$rez = $defa;
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim($file[$i]);
			if (($pos = pastore_church_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(pastore_church_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			if (pastore_church_strtolower(trim(chop($parts[0])))==$name) {
				$rez = trim(chop($parts[1]));
				if (pastore_church_substr($rez, 0, 1)=='"')
					$rez = pastore_church_substr($rez, 1, pastore_church_strlen($rez)-2);
				else
					$rez *= 1;
				break;
			}
		}
		return $rez;
	}
}

//  Retrieve all values from .ini-file as assoc array
if (!function_exists('pastore_church_ini_get_values')) {
	function pastore_church_ini_get_values($file) {
		$rez = array();
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = pastore_church_fga($file);
			} else
				return $rez;
		}
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim(chop($file[$i]));
			if (($pos = pastore_church_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(pastore_church_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			$key = trim(chop($parts[0]));
			$rez[$key] = trim($parts[1]);
			if (pastore_church_substr($rez[$key], 0, 1)=='"')
				$rez[$key] = pastore_church_substr($rez[$key], 1, pastore_church_strlen($rez[$key])-2);
			else
				$rez[$key] *= 1;
		}
		return $rez;
	}
}
?>