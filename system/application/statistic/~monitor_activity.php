<?php
	// return;
	$sql_select = 
	"
		SELECT
			@row := 0;
			
		SELECT
			@row := @row + 1 AS 'row', tmp.*
		FROM (
			SELECT
				s.fk_statistic_geo,
				s.fk_statistic_url,
				
				gcountry.code,
				
				su.url, DATE_FORMAT(s.time, '%Y-%m-%d') AS date,
				sa.time,
				sa.action,
				sg.ip
			FROM
				statistic AS s
			LEFT JOIN
				statistic_url AS su ON su.id = s.fk_statistic_url
			LEFT JOIN
				statistic_geo AS sg ON sg.id = s.fk_statistic_geo
			LEFT JOIN
				geo_country AS gcountry ON gcountry.id = sg.fk_geo_country
			LEFT JOIN
				statistic_action AS sa ON sa.id = (
					SELECT id
					FROM statistic_action
					WHERE fk_statistic = s.id
					ORDER BY id DESC
					LIMIT 1
				)
			WHERE
				IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
			ORDER BY
				sa.id DESC
			LIMIT
				|LIMIT|
		) AS tmp
	";
	$faSql->load($sql_select);
	$faSql->set(array(
		'HOST' => $authorizationData['host_id'],
		'LIMIT' => $monitor_limit,
	));
	$faSql->run();
	// view($faSql->query);

	if ($faSql->num_rows>0)
	{
		$records = $faSql->to_assoc();
		$tableRows = array();
		foreach($records as $key => $value)
		{
			$flag = strtolower($value['code']);
			$user_href = 'http://' . DOMAIN . US . 'users' . "?user={$value['fk_statistic_geo']}";
			$user_title = htmlspecialchars($value['ip']);
			$link_href = 'http://' . DOMAIN . US . 'links' . "?link={$value['fk_statistic_url']}";
			// $link_title = htmlspecialchars($value['url']);
			$link_title = htmlspecialchars(substr($value['url'], 0, 50));
			$tableRows[$key]['N'] = $value['row'];
			$tableRows[$key]['time'] = $value['date'] . ' ' . date('H:i:s', $value['time']/1000);
			$tableRows[$key]['link'] = "<a class=\"_blank\" href=\"{$link_href}\">{$link_title}</a>";
			$tableRows[$key]['user'] = "<a class=\"_blank\" href=\"{$user_href}\">{$user_title}</a>";
			$tableRows[$key]['flag'] = "<span class=\"flag flag_{$flag}\">&nbsp;</span>";
			$tableRows[$key]['last action'] = $value['action'];
		};
		$most_visited = fill_table($tableRows);
	} else {
		$most_visited = false;
	};

	return $most_visited;
?>