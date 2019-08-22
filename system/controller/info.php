<?php

$info = array();

$info[] = view($_SERVER, false);
// session_start();
$info[] = view($_SESSION, false);

$info[] = view(get_constants(), false);

ob_start();
phpinfo();
$info[] = ob_get_contents();
ob_get_clean();

$content = implode($info, '<hr/>');

view($content);
exit;