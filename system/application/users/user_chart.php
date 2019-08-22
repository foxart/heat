<?php

	$sql_select = 
	"
		SELECT
			CAST(s.time AS DATE) AS date,
			DATE_FORMAT(s.time, '%Y') AS year,
			DATE_FORMAT(s.time, '%c') AS month,
			DATE_FORMAT(s.time, '%e') AS day,
			COUNT(s.fk_statistic_url) AS urls
		FROM
			statistic AS s
		LEFT JOIN
			statistic_action AS sa ON sa.fk_statistic = s.id
		LEFT JOIN
			statistic_url AS su ON s.fk_statistic_url = su.id
		WHERE
			s.fk_statistic_geo = |USER|
		AND
			(sa.action = 'enter' OR	sa.action = 'exit')
		AND
			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
		AND
			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
		GROUP BY
			-- s.fk_statistic_url, 
			year, month, day
		ORDER BY
			s.id |ORDER|
		
	";
	$faSql->load($sql_select);
	$faSql->set(array(
		// 'HOST' => $authorizationData['host_id'],
		'DATE_START' => $get_date_start,
		'DATE_END' => $get_date_end,
		'USER' => $get_user,
		'ORDER' => 'ASC',
	));
	$faSql->run();
	
	
	$chartData['urls'] = array();
	if ($faSql->num_rows > 0)
	{
		$records = $faSql->to_assoc();
		$chartData = array();
		$count = 0;
		foreach($records as $key => $value)
		{
			$count++;
			
			$date = $value['date'];
			
			
			$urls = (int)$value['urls'];
			
			$chartData['urls'][] = "{x: new Date('{$date}'), y: {$urls}}";
		};
		
		// $list_record = fill_table($tableRows);
	} else {
		// $list_record = '<h1>no records found</h1>';
	};
	
	
	$urls = implode(',' . PHP_EOL, $chartData['urls']);
	// $urls = view($urls,false);
	
	
	$template = basename(__FILE__, EXT_PHP);
	$user_links_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
	$user_links_content = $faTemplate->set($user_links_template, array(
		'|USER_ID|' => $get_user,
		'|DATE_START|' => $get_date_start,
		'|DATE_END|' => $get_date_end,
		'|URLS|' => $urls,
	));

	return $user_links_content;
?>