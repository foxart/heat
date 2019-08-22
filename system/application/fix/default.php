<?php

	view('disabled');
	return;

	/* HOSTS */
	$sql_select = 
	"
		SELECT
			CAST(url AS CHAR(255)) AS url
		FROM
			statistic_url
		ORDER BY
			id ASC
	";
	$faSql->load($sql_select);
	$faSql->set(array(
		// 'IP' => $ip,
	));
	$faSql->run();
	$records = $faSql->to_assoc();
	$host_array = array();
	foreach ($records as $record)
	{
		$parse = parse_url($record['url']);
		if (isset($parse['host']) == true)
		{
			$host = $parse['host'];
			$host_array[] = $host;
		};
	};
	$hosts = array_unique($host_array);
	asort($hosts);
	/* HOSTS */

	/* TRUNCATE */
	$sql_truncate = "
		TRUNCATE statistic_host
	";
	$faSql->load($sql_truncate);
	$faSql->run();
	foreach ($hosts as $host)
	{
		$sql_insert = "
			INSERT INTO
				statistic_host (host)
			VALUES (
				'|HOST|'
			)
		";
		$faSql->load($sql_insert);
		$faSql->set(array(
			'HOST' => $host,
		));
		$faSql->run();
	};
	/* TRUNCATE */
	
	
	
	$sql_select = 
	"
		UPDATE
			statistic
		SET
			fk_statistic_host = null;
			
		SELECT
			s.id, CAST(su.url AS CHAR) AS url
		FROM
			statistic AS s
		LEFT JOIN
			statistic_url AS su ON su.id = s.fk_statistic_url
		ORDER BY
			id ASC
	";
	$faSql->load($sql_select);
	$faSql->run();
	$records = $faSql->to_assoc();
	
	foreach ($records as $record)
	{
		$parse = parse_url($record['url']);
		if (isset($parse['host']) == true)
		{
			$id = $record['id'];
			$host = $parse['host'];
			// $sql_update = 
			// "
				// SELECT
					// @statistic_host_id := id
				// FROM
					// statistic_host
				// WHERE
					// host = '|HOST|';
					
				// UPDATE
					// statistic
				// SET
					// fk_statistic_host = @statistic_host_id
				// WHERE
					// id = |ID|
			// ";
			
			$sql_update = 
			"
				UPDATE
					statistic
				SET
					fk_statistic_host = (
						SELECT
							id
						FROM
							statistic_host
						WHERE
							host = '|HOST|'
					)
				WHERE
					id = |ID|
			";
			
			
			$faSql->load($sql_update);
			$faSql->set(array(
				'ID' => $id,
				'HOST' => $host,
			));
			$faSql->run();
		};
	};
	
	view('fixed');
	
?>