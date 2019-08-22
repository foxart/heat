<?php

global $php_start;
global $db_time;
global $memory_usage_start;
global $memory_peak_start;

$memory_available = filter_var(ini_get("memory_limit"), FILTER_SANITIZE_NUMBER_INT) * 1024;

/*
 * memory fill
 */
$get = filter_input_array(INPUT_GET);
if (isset($get['memory']) == true) {
	if ((int) $get['memory'] < 64 or (int) $get['memory'] > 1024) {
		$kb = 64; //kbs
	} else {
		$kb = (int) $get['memory'];
	}

	$size = $kb * 1024;
	$stop = 85;
	$memory_cycle_start = round(memory_get_usage(false) / 1024);
	$data = '';
	$i = 0;
	while (true) {
		$i++;
		$data = $data . str_repeat(' ', $size);
		$memory_cycle_current = round(memory_get_usage(false) / 1024);
		$memory_cycle_current_peak = round(memory_get_peak_usage(false) / 1024);
		$memory_cycle_percent = round($memory_cycle_current * 100 / $memory_available);
		$memory_cycle_percent_peak = round($memory_cycle_current_peak * 100 / $memory_available, 1);
		$memory_cycle_percent_peak_next = round(($memory_cycle_current_peak + $kb) * 100 / $memory_available);
		$memory_test = "<br/>test: <b>{$i}</b> cycles of <b>{$kb}</b> kbs ({$memory_cycle_percent}% / {$memory_cycle_percent_peak}%)";
		if ($memory_cycle_percent_peak_next > $stop) {
			break;
		}
	}
} else {
	$memory_test = '';
}

$php_end = microtime(true);
$memory_usage_end = memory_get_usage(false);
$memory_peak_end = memory_get_peak_usage(false);

$php_time = $php_end - $php_start - $db_time;
$total_time = $php_end - $php_start;

$php_formatted = number_format($php_time * 1000);
$db_formatted = number_format($db_time * 1000);
$total_formatted = number_format($total_time * 1000);

$php_percentage = round($php_time * 100 / ($total_time), 1);
$db_percentage = round($db_time * 100 / ($total_time), 1);

$memory_usage = round((($memory_usage_end - $memory_usage_start) / 1024));
$memory_peak = round((($memory_peak_end - $memory_peak_start) / 1024));

$memory_usage_percent = round($memory_usage * 100 / $memory_available, 1);
$memory_peak_percent = round($memory_peak * 100 / $memory_available, 1);

$result = "time: <b>{$php_formatted}</b> + <b>{$db_formatted}</b> = <b>{$total_formatted}</b> ms ({$php_percentage}% / {$db_percentage}%)<br/>memory: <b>{$memory_usage}</b> / <b>{$memory_peak}</b> of <b>{$memory_available}</b> kbs ({$memory_usage_percent}% / {$memory_peak_percent}%){$memory_test}";
return $result;
