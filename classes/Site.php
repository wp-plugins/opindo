<?php

	class Site {
		public static function current() {
			$string = '?';
			foreach($_GET as $key => $get) $string .= $key .'=' . $get . '&';
			$string = rtrim($string, '&');

			$current = get_site_url();
			if(strpos($current, '?' && $string != '?') == false) $current .= $string;
			return $current;
		}

		public static function root() {
			$root = plugin_dir_url(__FILE__);
			$root = str_replace('/classes', '', $root);
			return $root;
		}
	}

?>