<?php
$authorizationData = $faAuthorize->get_login();
ob_start();
?>

<h1>version history</h1>
<pre style="font-size: 12px; line-height: 12px;">
	<h2>v.1.0</h2>
	<div class="line"></div>
	1.0.0 - 2015.02.03
	1.0.1 - 2015.02.04
	1.0.2 - 2015.02.27
	<br/>
	<h2>v.1.1</h2>
	<div class="line"></div>
	1.1.0 - 2015.05.05
		heat map implementation
	1.1.1 - 2015.05.06
	1.1.2 - 2015.05.12
	1.1.3 - 2015.05.13
	1.1.4 - 2015.05.14
	1.1.5 - 2015.05.15
	<br/>
	<h2>v.1.2</h2>
	<div class="line"></div>
	1.2.0 - 2015.06.18
		track implementation
	1.2.1 - 2015.06.19
	1.2.2 - 2015.06.20
	1.2.3 - 2015.06.22
	1.2.4 - 2015.06.23
		20x speed optimization
		live track implementation
	1.2.5 - 2015.08.21
		statistic chart implementation
		live track optimization
		live track follow link improvements
	<br/>
	<h2>v.1.3</h2>
	<div class="line"></div>
	1.3.0 - 2015.09.09
		live chat implementation
	1.3.1 - 2015.09.28
		live chat links
		google map speed optimization
	1.3.2 - 2015.10.19
		live chat improvement
		moves map fix
		minor fixes and changes
	1.3.3 - 2015.11.04
		menu improvement
	1.3.4 - 2015.11.05
		global records display rebuild
	1.3.5 - 2015.11.06
		impossible feature added
</pre>

<?php
$result = ob_get_clean();
return $result;
