<?php
	return 'menu dates';
	return false;
	$sql_select = 
	"
		SELECT tmp.id, tmp.time AS timestamp, DATE_FORMAT(tmp.time, '%m-%d-%Y') AS time, tmp.url
		FROM
		(
			SELECT
				s.id, s.time, su.url
			FROM
				statistic AS s
			LEFT JOIN
				statistic_url AS su ON su.id = s.fk_statistic_url
			LEFT JOIN
				statistic_action AS sa ON sa.fk_statistic = s.id
		) as tmp
		GROUP BY
			tmp.id
		ORDER BY
			tmp.time
	";
	$faSql->load($sql_select);
	$faSql->set(array());
	$faSql->run();
	$faSql->seek();
	$result = array();
	
	while ($record = $faSql->fetch_assoc())
	{
		$timestamp = $record['timestamp'];
		$month = date("m", strtotime($timestamp));
		$year = date("Y", strtotime($timestamp));
		// $day = date("d", strtotime($timestamp));
		// $result[$year][$month][$day] = 'link';
		$result[$year][$month] = 'link';
	};
	
	
	$menu_left = array();
	foreach ($result as $result_key => $result_value)
	{
		$menu_left_url = 'http://' . DOMAIN . US . APPLICATION . US . $result_key;
		$menu_title = $result_key;
		$menu_left[] = "<div class=\"o_h\" style=\"margin-bottom: 20px;\">";
		if ($result_key == $get_year)
		{
			$menu_left[] = "<div class=\"button active\"><a class=\"fs_7\" href=\"{$menu_left_url}\">{$menu_title}</a></div>";
		} else {
			$menu_left[] = "<div class=\"button\"><a class=\"fs_7\" href=\"{$menu_left_url}\">{$menu_title}</a></div>";
		};
		$menu_left[] = "<ul class=\"list\" style=\"margin-top: 5px; padding-left: 10px;\">";
		if (is_array($result_value) == true)
		{
			foreach ($result_value as $key => $value)
			{
				$menu_left_url = 'http://' . DOMAIN . US . APPLICATION . US . $result_key . US . $key;
				$menu_left_full_url = 'http://' . DOMAIN . US . APPLICATION . US . $result_key . US . $key . US . 'full';
				$menu_title = $result_key . '/' . $key;
				if ($result_key == $get_year and $key == $get_month)
				{
					$menu_left[] = "<li><a class=\"fw_b fs_5\" href=\"{$menu_left_url}\">{$menu_title}</a>&nbsp;&nbsp;&nbsp;<a class=\"fs_2\" href=\"{$menu_left_full_url}\">by day</a></li>";
				} else {
					$menu_left[] = "<li><a class=\"fs_5\" href=\"{$menu_left_url}\">{$menu_title}</a>&nbsp;&nbsp;&nbsp;<a class=\"fs_2\" href=\"{$menu_left_full_url}\">by day</a></li>";
				};
			}
		};
		
		$menu_left[] = "</ul>";
		$menu_left[] = "</div>";
	};
	$menu_left = implode(PHP_EOL, $menu_left);

	$template = basename(__FILE__, EXT_PHP);
	$list_date_template = $faTemplate->get('application' . DS . 'menu' . DS . $template);
	$list_date_template = $faTemplate->set($list_date_template, array(
		'|MENU|' => $menu_left,
	));

	return $list_date_template;
	
?>