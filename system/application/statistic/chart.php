<?php

	
	$sql_select = 
	"
		SELECT
			CAST(s.time AS DATE) AS date,
			DATE_FORMAT(s.time, '%Y') AS year,
			DATE_FORMAT(s.time, '%c') AS month,
			DATE_FORMAT(s.time, '%e') AS day,
			COUNT(s.fk_statistic_geo) AS users,
			SUM(s.enter) AS enters,
			SUM(s.exit) AS exits,
			SUM(s.click) AS clicks,
			SUM(s.move) AS moves,
			SUM(s.scroll) AS scrolls,
			SUM(s.move_time) AS move_time,
			SUM(s.scroll_time) AS scroll_time,
			SUM(s.spend_time) AS spend_time
		FROM
			statistic AS s
		WHERE
			IF(|HOST_ID| = 0, 1, s.fk_statistic_host = |HOST_ID|)
		AND
			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
		AND
			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
		GROUP BY
			year, month, day
		ORDER BY
			s.id |ORDER|
		
	";
	$faSql->load($sql_select);
	$faSql->set(array(
		'HOST_ID' => $authorizationData['host_id'],
		'DATE_START' => $get_date_start,
		'DATE_END' => $get_date_end,
		// 'USER' => $get_user,
		'ORDER' => 'ASC',
	));
	$faSql->run();
	
	
	$chartData['users'] = array();
	$chartData['enters'] = array();
	$chartData['exits'] = array();
	$chartData['clicks'] = array();
	$chartData['moves'] = array();
	$chartData['scrolls'] = array();
	$chartData['visits'] = array();
	if ($faSql->num_rows > 0)
	{
		$records = $faSql->to_assoc();
		$count = 0;
	
		$usersMin = 0;
		$usersMax = 0;
		$entersMin = 0;
		$entersMax = 0;
		$exitsMin = 0;
		$exitsMax = 0;
		$clicksMin = 0;
		$clicksMax = 0;
		$movesMin = 0;
		$movesMax = 0;
		$scrollsMin = 0;
		$scrollsMax = 0;
		
		foreach ($records as $key => $value)
		{
			$users = $records[$key]['users'];
			$enters = $records[$key]['enters'];
			$exits = $records[$key]['exits'];
			$clicks = $records[$key]['clicks'];
			$moves = $records[$key]['moves'];
			$scrolls = $records[$key]['scrolls'];
			
			
			if ($users > $usersMax)
			{
				$usersMax = $users;
			}
			if ($users < $usersMin or $usersMin == 0)
			{
				$usersMin = $users;
			}
			
			if ($enters > $entersMax)
			{
				$entersMax = $enters;
			}
			if ($enters < $entersMin or $entersMin == 0)
			{
				$entersMin = $enters;
			}
			
			if ($exits > $exitsMax)
			{
				$exitsMax = $exits;
			}
			if ($exits < $exitsMin or $exitsMin == 0)
			{
				$exitsMin = $exits;
			}
			
			if ($clicks > $clicksMax)
			{
				$clicksMax = $clicks;
			}
			if ($clicks < $clicksMin or $clicksMin == 0)
			{
				$clicksMin = $clicks;
			}
			
			if ($moves > $movesMax)
			{
				$movesMax = $moves;
			}
			if ($moves < $movesMin or $movesMin == 0)
			{
				$movesMin = $moves;
			}
			
			if ($scrolls > $scrollsMax)
			{
				$scrollsMax = $scrolls;
			}
			if ($scrolls < $scrollsMin or $scrollsMin == 0)
			{
				$scrollsMin = $scrolls;
			}
		};
	
		$normalizeStart = $usersMin;
		$normalizeEnd = $usersMax;
	
		$normalizeStart = min($usersMin, $entersMin, $exitsMin);
		$normalizeEnd = max($usersMax, $entersMax, $exitsMax);
	
		// $normalizeStart = 1;
		// $normalizeEnd = $usersMax;
	
		$chartData = array();
		foreach($records as $key => $value)
		{
			$count++;
			$date = $value['date'];
			
			$users = (int)$value['users'];
			$enters = (int)$value['enters'];
			$exits = (int)$value['exits'];
			$clicks = (int)$value['clicks'];
			$moves = (int)$value['moves'];
			$scrolls = (int)$value['scrolls'];
			
			// $enters = (int)normalize($enters, $entersMin, $entersMax, $normalizeStart, $normalizeEnd);
			// $exits = (int)normalize($exits, $exitsMin, $exitsMax, $normalizeStart, $normalizeEnd);
			$clicks = (int)normalize($clicks, $clicksMin, $clicksMax, $normalizeStart, $normalizeEnd);
			$moves = (int)normalize($moves, $movesMin, $movesMax, $normalizeStart, $normalizeEnd);
			$scrolls = (int)normalize($scrolls, $scrollsMin, $scrollsMax, $normalizeStart, $normalizeEnd);

			if ($enters - $exits > 0)
			{
				$open = $exits;
				$close = $enters;
			} else {
				$open = $enters;
				$close = $exits;
			}
			$high = $close + abs($close - $users);
			$low = $open - abs($open - $users);
			if ($low < 0)
			{
				$low = 0;
			}
				
			$visitsArray = array(
				$open,
				$high,
				$low,
				$close,
			);
			$visits = implode(', ', $visitsArray);
			
			
			$chartData['users'][] = "{x: new Date('{$date}'), y: {$users}}";
			// $chartData['enters'][] = "{x: new Date('{$date}'), y: [{$entersNormalized}, {$exitsNormalized}]}";
			$chartData['enters'][] = "{x: new Date('{$date}'), y: {$enters}}";
			$chartData['exits'][] = "{x: new Date('{$date}'), y: {$exits}}";
			$chartData['clicks'][] = "{x: new Date('{$date}'), y: {$clicks}}";
			$chartData['moves'][] = "{x: new Date('{$date}'), y: {$moves}}";
			$chartData['scrolls'][] = "{x: new Date('{$date}'), y: {$scrolls}}";
			
			$chartData['visits'][] = "{x: new Date('{$date}'), y: [{$visits}]}";
		};
		
		// $list_record = fill_table($tableRows);
	} else {
		// $list_record = '<h1>no records found</h1>';
	};
	
	
	
	
	$users = implode(',' . PHP_EOL, $chartData['users']);
	$enters = implode(',' . PHP_EOL, $chartData['enters']);
	$exits = implode(',' . PHP_EOL, $chartData['exits']);
	$clicks = implode(',' . PHP_EOL, $chartData['clicks']);
	$moves = implode(',' . PHP_EOL, $chartData['moves']);
	$scrolls = implode(',' . PHP_EOL, $chartData['scrolls']);
	$visits = implode(',' . PHP_EOL, $chartData['visits']);
	// $urls = view($urls,false);
	
	
	$template = basename(__FILE__, EXT_PHP);
	$user_links_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
	$user_links_content = $faTemplate->set($user_links_template, array(
		'|USER_ID|' => $get_user,
		'|DATE_START|' => $get_date_start,
		'|DATE_END|' => $get_date_end,
		'|USERS|' => $users,
		'|ENTERS|' => $enters,
		'|EXITS|' => $exits,
		'|CLICKS|' => $clicks,
		'|MOVES|' => $moves,
		'|SCROLLS|' => $scrolls,
		'|VISITS|' => $visits,
	));

	return $user_links_content;
?>