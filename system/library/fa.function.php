<?php
	function get_trace()
	{
		$result = '';
		$dump = debug_backtrace();
		array_shift($dump);
		$dump_inverse = array_reverse($dump);
		reset($dump);
		$first = current($dump);
		$last = end($dump);
		$file = $first['file'];
		$line = $first['line'];
		$function = $first['function'];
		
		// print_r($first['args']);
		$arguments = implode_recursive($first['args'], ', ');
		foreach($dump_inverse as $item)
		{
			$chain_array[] = "file: <u>{$item['file']}</u> line: (<b>{$item['line']}</b>)";
		};
		$chain = implode('<br/>', $chain_array);
		ob_start();
		echo '<div class="trace">';
		echo 'file';
		echo '<span class="line">&nbsp;</span>';
		echo "<u>{$file}</u> line: (<b>{$line}</b>) method: <b>{$function}</b> ({$arguments})";
		echo '<span class="line">&nbsp;</span>';
		echo 'chain';
		echo '<span class="line">&nbsp;</span>';
		echo $chain;
		echo '<span class="line">&nbsp;</span>';
		echo '</div>';
		$result = ob_get_clean();
		return $result;
	}
	
	function raise_error($string)
	{
		echo '<div style="margin: 5px; padding: 5px; border: dotted 1px red;">';
		echo '<b>error:</b>';
		view($string);
		echo '</div>';
		exit;
	}
	
	
	function fa_view($variable='undefined', $output = true)
	{
		ob_start();
		echo '<div class="fa_view">';
		echo '<div class="content">';
		echo get_trace();
		echo 'parameter';
		echo '<span class="line">&nbsp;</span>';
		echo '<pre>';
		print_r($variable);
		echo '</pre>';
		echo '</div>';
		echo '</div>';
		$result = ob_get_clean();
		if ($output == true)
		{
			echo $result;
		} else {
			return $result;
		};
	};

	function get_constants($output = true)
	{
		$constants = get_defined_constants(true);
		$result = $constants['user'];
		// $result = $constants;
		return $result;
	}

	function get_ip()
	{
		if (empty($_SERVER['HTTP_CLIENT_IP'])==false)
		{
			$result = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (empty($_SERVER['HTTP_X_FORWARDED_FOR'])==false) {
			$result = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$result = $_SERVER['REMOTE_ADDR'];
		};
		return $result;
	}
	
	// function get_ip()
	// {
		// $client  = @$_SERVER['HTTP_CLIENT_IP'];
		// $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		// $remote  = $_SERVER['REMOTE_ADDR'];
		// if(filter_var($client, FILTER_VALIDATE_IP))
		// {
			// $ip = $client;
		// } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
			// $ip = $forward;
		// } else {
			// $ip = $remote;
		// }
		// return $ip;
	// }

	function to_pattern(&$value, $key) {
		$value = '|' . strtoupper($value) . '|';
	}
	
	
	function normalize($value, $fromLow, $fromHigh, $toLow, $toHigh)
	{
		$fromRange = $fromHigh - $fromLow;
		
		
		
		$toRange = $toHigh - $toLow;
		
		if ($fromRange !== 0)
		{
			$scaleFactor = $toRange / $fromRange;
		} else {
			$scaleFactor = 1;
		}
		
		
		$tmpValue = $value - $fromLow;
		$tmpValue *= $scaleFactor;
		return $tmpValue + $toLow;
	}
	
	
	function implode_recursive($array, $glue=', ')
	{
		$result = '';
		foreach ($array as $item)
		{
			if (is_array($item)) {
				$result .= implode_recursive($item, $glue);// . $glue;
			} else if (is_object($item)) {
				$result .= implode_recursive($item, $glue);// . $glue;
			} else {
				$result .= $item . $glue;
			};
		};
		$result = substr($result, 0, 0 - strlen($glue));
		return $result;
	}
	
	
	function get_unique()
	{
		return uniqid();
	}
	
	function check_multiplicity($a, $b)
	{
		if ($a % $b)
			$result = false;
		else
			$result = true;
		return $result;
	}
	
	function get_charts($data, $columns=2)
	{
		$result = '';

		ob_start();
		$colors = array(
			'135d13',
			'478900',
			'7eb234',
			'd0e17e',
			'edf4b7',
		);
		$count = 0;
		foreach ($data as $key => $value)
		{
			$chart = strtolower(str_replace(' ', '_', $key));
			$width = round(100/$columns);
			echo "<div style=\"position: relative; float: left; width: {$width}%;\">";
			echo "<div class=\"pie_chart\">";
			echo "<div class=\"pie_chart_content\">";
			echo "<div class=\"pie_chart_header\">{$key}</div>";
			if (count($value)>0)
			{
				echo '<div class="pie_chart_legend">';
				echo '<ul>';
				$color = 0;
				foreach ($value as $value_key => $value_value)
				{
				echo "<li class=\"{$chart}_legend  {'color': '#{$colors[$color]}', 'value': '{$value_value}'}\"><span class=\"pie_chart_point\" style=\"background-color: #{$colors[$color]}\">&nbsp;</span>{$value_key} - <b>{$value_value}</b></li>" . PHP_EOL;
					$color++;
					if ($color == count($colors))
					{
						$color = 0;
					};
				};
				echo '</ul>';
				echo '</div>';
				// echo "<div class=\"pie_chart_canvas dashboard_chart {canvas: '{$chart}', legend: '{$chart}_legend', 'size': '250', log: 'log_{$chart}'}\" style=\"width: 250px; height: 250px;\"></div>";
				echo "<div class=\"pie_chart_canvas dashboard_chart {canvas: '{$chart}', legend: '{$chart}_legend', 'size': '250', log: 'log_{$chart}'}\"></div>";
				// echo "<div id=\"log_{$chart}\" class=\"log\"></div>";
			} else {
				echo '<div class="text_content">no data</div>';
				echo '<div class="text_content">&nbsp;</div>';
			};
			echo '</div>';
			echo '</div>';
			echo '</div>';
			$count++;
			if (check_multiplicity($count, $columns) == true) echo '<div style="overflow: hidden; width: 100%;"></div>';
		};
		$result = ob_get_clean();
		return $result;
	}
	
	function fill_table($json)
	{
		// view($json);
		$result = '';
		$result_thead = '';
		$result_tbody = '';
		$tr = 1;
		foreach($json as $array_key => $array_value)
		{
			$td = 1;
			if ($tr == 1)
			{
				$row_class = 'table_header';
				$result_thead = $result_thead . "<div class=\"table_header table_row\">" . PHP_EOL;
				foreach($array_value as $key => $value)
				{
					$result_thead = $result_thead . '<span class="table_cell td' . $td . '">' . $key . '</span>' . PHP_EOL;
				};
				$result_thead = $result_thead . '</div>' . PHP_EOL;
			};
			
			$result_tbody = $result_tbody . "<div class=\"table_body table_row\">" . PHP_EOL;
			foreach($array_value as $key => $value)
			{
				if ($value == '')
				{
					$value = "&nbsp;";
				} else {
					// $value = htmlentities($value);
					$value = $value;
				};
				$result_tbody = $result_tbody . "<span class=\"table_cell td{$td}\">{$value}</span>" . PHP_EOL;
				$td++;
			};
			$result_tbody = $result_tbody . '</div>' . PHP_EOL;
			$tr++;
		};
		
		
		$result = $result . '<div class="table">' . PHP_EOL;
		$result = $result . '<div class="table_content">' . PHP_EOL;
		$result = $result . $result_thead;
		$result = $result . $result_tbody;
		$result = $result . '</div>' . PHP_EOL;
		$result = $result . '</div>' . PHP_EOL;
		return $result;
	};
	
	function format_milliseconds($milliseconds)
	{
		if ($milliseconds == 0)
		{
			$result = '0';
		} else {
			$seconds = floor($milliseconds / 1000);
			$minutes = floor($seconds / 60);
			$hours = floor($minutes / 60);
			$milliseconds = $milliseconds % 1000;
			$seconds = $seconds % 60;
			$minutes = $minutes % 60;
			// $format = '%u:%02u:%02u.%03u';
			// $time = sprintf($format, $hours, $minutes, $seconds, $milliseconds);
			$format = '%u:%02u:%02u';
			$time = sprintf($format, $hours, $minutes, $seconds);
			// $result = rtrim($time, '0');
			$result = $time;
		};
		
		return $result;
	}
	
	function get_distance_old($x1, $y1, $x2, $y2)
	{
		$result =  sqrt(pow($x2-$x1, 2) + pow($y2-$y1, 2));
		return $result;
	}
	
	function get_distance($x1, $y1, $x2, $y2)
	{
		$x_dist = $x2-$x1;
		$y_dist = $y2-$y1;
		if($x_dist<0)
		{
			$x_dist *=-1;
		};
		if($y_dist<0)
		{
			$y_dist *=-1;
		};
		$distance = $x_dist + $y_dist;
		return $distance;
	}
	
	
?>