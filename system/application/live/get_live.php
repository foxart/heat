<?php

	$sql_select = "
		SELECT
			s.id,
			s.fk_statistic_url,
			s.fk_statistic_geo,
			s.time,
			s.timestamp,
			gcountry.code,
			gcountry.country,
			gcity.city,
			su.url,
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
			geo_location AS glocation ON glocation.id = sg.fk_geo_location
		LEFT JOIN
			geo_city AS gcity ON gcity.id = glocation.fk_geo_city
		WHERE
			IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
		ORDER BY
			s.timestamp DESC,
			s.fk_statistic_url DESC
		LIMIT
			|LIMIT|
	";
	$faSql->load($sql_select);
	$faSql->set(array(
		'HOST' => $authorizationData['host_id'],
		'LIMIT' => $liveLimit,
	));
	$faSql->run();
	$records = $faSql->to_assoc();
//	view($faSql->query);

	if ($faSql->num_rows > 0) {
		$tableRows = array();
		foreach ($records as $key => $value) {
			$flag = strtolower($value['code']);

			// $link_href = 'http://' . DOMAIN . US . 'links' . "?link={$value['fk_statistic_url']}";
			// $user_href = 'http://' . DOMAIN . US . 'users' . "?user={$value['fk_statistic_geo']}";

//			$watch_href = 'http://' . DOMAIN . US . 'live' . US . 'watch' . "?statistic={$value['id']}";
			$watch_href = 'http://' . DOMAIN . US . 'live' . US . 'watch' . "?statistic={$value['fk_statistic_geo']}";
			
			$chat_href = 'http://' . DOMAIN . US . 'users' . US . 'chat' . "?user={$value['fk_statistic_geo']}";
			
			$link_title = htmlspecialchars(substr($value['url'], 0, 50));
			$user_title = htmlspecialchars($value['ip']);


//			$tableRows[$key]['N'] = $value['row'];
			$tableRows[$key]['time'] = $value['timestamp'];
			// $tableRows[$key]['link'] = "<a class=\"_blank\" href=\"{$link_href}\">{$link_title}</a>";
			// $tableRows[$key]['user'] = "<a class=\"_blank\" href=\"{$user_href}\">{$user_title}</a>";
			$tableRows[$key]['link'] = $link_title;
			$tableRows[$key]['user'] = $user_title;
			$tableRows[$key]['flag'] = "<span class=\"flag flag_{$flag}\">&nbsp;</span>";
			$tableRows[$key]['code'] = $value['code'];
			$tableRows[$key]['country'] = $value['country'];
			$tableRows[$key]['city'] = $value['city'];
			
			$tableRows[$key]['chat'] = "<a class=\"_blank\" href=\"{$chat_href}\">chat</a>";
			
			$tableRows[$key]['watch'] = "<a class=\"_blank\" href=\"{$watch_href}\">watch</a>";
		};

		$live = fill_table($tableRows);
	} else {
		$live = false;
	};

	return $live;
?>