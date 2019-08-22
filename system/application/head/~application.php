<?php

$template_ini = parse_ini_file(PATH_TEMPLATE . '.default' . DS . 'template.ini', true);
$template_css = array();
$template_js = array();
$template_plugin = array();

foreach ($template_ini as $template_ini_key => $template_ini_value) {
	foreach ($template_ini_value as $key => $value) {
		if ($template_ini_key == 'css') {
			$url_css = US . 'template' . US . '.default' . US . 'css' . US . $value . EXT_CSS;
			$template_css[] = "<link href=\"{$url_css}\" charset=\"utf-8\" rel=\"stylesheet\" type=\"text/css\"/>";
		};
		if ($template_ini_key == 'js') {
			$url_js = US . 'template' . US . '.default' . US . 'js' . US . $value . EXT_JS;
			$template_js[] = "<script src=\"{$url_js}\" charset=\"utf-8\" type=\"text/javascript\"></script>";
		};

		// plugin
		if ($template_ini_key == 'plugin') {

			foreach ($value as $plugin_key => $plugin_value) {

				if ($key == 'js') {
					$url_js = US . 'template' . US . '.default' . US . 'plugin' . US . $plugin_key . US . 'js' . US . $plugin_value . EXT_JS;
					$template_js[] = "<script src=\"{$url_js}\" charset=\"utf-8\" type=\"text/javascript\"></script>";
				};

				if ($key == 'css') {
					$url_css = US . 'template' . US . '.default' . US . 'plugin' . US . $plugin_key . US . 'css' . US . $plugin_value . EXT_CSS;
					$template_css[] = "<link href=\"{$url_css}\" charset=\"utf-8\" rel=\"stylesheet\" type=\"text/css\"/>";
				};
			};
		};
	};
};
$css = implode(PHP_EOL, $template_css);
$js = implode(PHP_EOL, $template_js);


$result = $css . PHP_EOL . $js;

return $result;
