<?php

//ini_set('memory_limit', '8M');
global $php_start;
global $db_time;
global $memory_usage_start;
global $memory_peak_start;
$php_start = microtime(true);
$memory_usage_start = memory_get_usage(false);
$memory_peak_start = memory_get_peak_usage(false);
