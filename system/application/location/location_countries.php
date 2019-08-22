<?php
	$sql = "
		SELECT
			COUNT(sg.id) AS count,
			gcountry.id AS country_id,
			gcountry.country,
			gcountry.code,
			gcity.city,
			gcity.latitude,
			gcity.longitude
		FROM
			statistic_geo AS sg
		LEFT JOIN
			geo_city AS gcity ON gcity.id = sg.fk_geo_city
		LEFT JOIN
			geo_country AS gcountry ON gcountry.id = sg.fk_geo_country
		LEFT JOIN
			statistic AS s ON s.fk_statistic_geo = sg.id
		WHERE
			IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
		AND
			IF('|COUNTRY|' = '0', 1, gcountry.code = '|COUNTRY|')
		GROUP BY
			IF('|COUNTRY|' = '0', gcountry.country, gcity.city)
	";
	$faSql->load($sql);
	$faSql->set(array(
		'HOST' => $authorizationData['host_id'],
		'COUNTRY' => $get_country,
	));
	$faSql->run();
	
	if ($faSql->num_rows>0)
	{
		$geoData = $faSql->to_assoc();
		$countries_list = array();
		$countries_list[] = "<ul>";
		
		if ($get_country == '0')
		{
		} else {
			
			$flag = strtolower($geoData[0]['code']);
			$countries_list[] = "<br/>";
			$countries_list[] = "<h3><span class=\"fl_l flag flag_{$flag}\">&nbsp;</span>&nbsp;{$geoData[0]['country']} ({$geoData[0]['count']})</h3>";
			$countries_list[] = "<div class=\"line\"></div>";
		};
		
		foreach ($geoData as $key => $value)
		{
			if (isset($value['latitude']) == true and isset($value['longitude']) == true)
			{
				$flag = strtolower($value['code']);
				$country_code = $value['code'];
				$userCount = $value['count'];
				
				if ($get_country == '0')
				{
					$title = $value['country'];
					$linkCountry = "http://" . DOMAIN . US . 'location' . "?country={$country_code}";
					$countries_list[] = "<li class=\"o_h\"><span class=\"fl_l flag flag_{$flag}\">&nbsp;</span>&nbsp;<b>{$country_code}</b> <a href=\"$linkCountry\">{$title}</a> (<b>{$userCount}</b>)</li>";
				} else {
					$title = $value['city'];
					if ($title != '')
					{
						$countries_list[] = "<li>{$title} (<b>{$userCount}</b>)</li>";
					};
				};
			};
		};
		$countries_list[] = "</ul>";
		$countries_list = implode(PHP_EOL, $countries_list);
	} else {
		$countries_list = false;
	};

	
	return $countries_list;
?>