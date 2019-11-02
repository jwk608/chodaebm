<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

if (file_exists('/data/www/bm/chodaebm/www/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", '/data/www/bm/chodaebm/www/wp-content/wflogs/');
	include_once '/data/www/bm/chodaebm/www/wp-content/plugins/wordfence/waf/bootstrap.php';
}
?>