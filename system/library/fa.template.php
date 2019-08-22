<?php

class faTemplate {

	function get($filename) {
		$template = PATH_THEME . THEME . DS . $filename . EXT_TEMPLATE;
		if (file_exists($template)) {
			$result = file_get_contents($template);
			return $result;
		} else {
			raise_error("file: $template does not exist");
		}
	}

	function set($template, $data) {
		$search = array_keys($data);
		$replace = array_values($data);
		$subject = $template;
		$result = str_replace($search, $replace, $subject);
		return $result;
	}

}
