<?php
$authorizationData = $faAuthorize->get_login();
ob_start();
?>

<link href="/heat/theme/default/css/jquery.orgchart.css" charset="utf-8" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<script src="/heat/theme/default/js/html2canvas.js" type="text/javascript"></script>
<script src="/heat/theme/default/js/jquery.orgchart.js" type="text/javascript"></script>
<script src="/heat/theme/default/js/jquery.orgchart.data.js" type="text/javascript"></script>


<script type="text/javascript">
	'use strict';
	(function ($) {
		$(function () {
			$('#chart-container').orgchart({
				'data': datascource,
				'depth': 100,
				'nodeTitle': 'name',
				'nodeContent': 'title',
				'nodeID': 'id',
				'createNode': function ($node, data) {
					var nodePrompt = $('<i>', {
						'class': 'fa fa-info-circle second-menu-icon',
						click: function () {
							$(this).siblings('.second-menu').toggle();
						}
					});
					// var secondMenu = '<div class="second-menu"><img class="avatar" src="img/avatar/' + data.id + '.jpg"></div>';
					// $node.append(nodePrompt).append(secondMenu);
				}
			});
		});
	})(jQuery);
</script>

<h1>Product map</h1>


<div id="chart-container"></div>





<?php
$result = ob_get_clean();
return $result;
