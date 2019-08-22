<?php
	$authorizationData = $faAuthorize->get_login();
	
	$sql_select = 
	"
		SELECT
			@row := 0;
			
		SELECT
			@row := @row + 1 AS 'row', tmp.*
		FROM (
			SELECT
				-- s.fk_statistic_geo, sg.ip, MAX(s.time) AS time
				s.fk_statistic_url,
				s.fk_statistic_geo,
				gcountry.code,
				su.url,
				sg.ip,
				s.time
			FROM
				statistic AS s
			LEFT JOIN
				statistic_url AS su ON su.id = s.fk_statistic_url
			LEFT JOIN
				statistic_geo AS sg ON sg.id = s.fk_statistic_geo
			LEFT JOIN
				geo_country AS gcountry ON gcountry.id = sg.fk_geo_country
				
			WHERE
				IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
			GROUP BY
				s.fk_statistic_geo
			ORDER BY
				s.id DESC
		) AS tmp
			LIMIT
				|LIMIT|
	";
	$faSql->load($sql_select);
	$faSql->set(array(
		'HOST' => $authorizationData['host_id'],
		'LIMIT' => $monitor_limit,
	));
	$faSql->run();
	$records = $faSql->to_assoc();
	// view($faSql->query);

	if ($faSql->num_rows>0)
	{
		$tableRows = array();
		foreach($records as $key => $value)
		{
			$flag = strtolower($value['code']);
			
			$user_href = 'http://' . DOMAIN . US . 'users' . "?user={$value['fk_statistic_geo']}";
			$user_title = htmlspecialchars($value['ip']);
			
			$link_href = 'http://' . DOMAIN . US . 'links' . "?link={$value['fk_statistic_url']}";
			$link_title = htmlspecialchars(substr($value['url'], 0, 50));
			
			$tableRows[$key]['N'] = $value['row'];
			$tableRows[$key]['time'] = $value['time'];
			$tableRows[$key]['user'] = "<a class=\"_blank\" href=\"{$user_href}\">{$user_title}</a>";
			$tableRows[$key]['flag'] = "<span class=\"flag flag_{$flag}\">&nbsp;</span>";
			$tableRows[$key]['link'] = "<a class=\"_blank\" href=\"{$link_href}\">{$link_title}</a>";
		};
		$most_visited = fill_table($tableRows);
	} else {
		$most_visited = false;
	};

	return $most_visited;
?>