<?php
$authorizationData = $faAuthorize->get_login();
ob_start();
?>

<link href="/heat/theme/default/css/tree.css" charset="utf-8" rel="stylesheet" type="text/css"/>


<script src="/heat/theme/default/js/tree.js" type="text/javascript"></script>
<script src="/heat/theme/default/js/tree.data.js" type="text/javascript"></script>
<script src="/heat/theme/default/js/tree.run.js" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function () {
		init();
	});
</script>

<div id="right-container">

	<h4>Tree Orientation</h4>
	<table>
		<tbody>
			<tr>
				<td>
					<label for="r-top">Top </label>
				</td>
				<td>
					<input id="r-top" name="orientation" value="top" type="radio">
				</td>
			</tr>
			<tr>
				<td>
					<label for="r-left">Left </label>
				</td>
				<td>
					<input id="r-left" name="orientation" checked="checked" value="left" type="radio">
				</td>
			</tr>
			<tr>
				<td>
					<label for="r-bottom">Bottom </label>
				</td>
				<td>
					<input id="r-bottom" name="orientation" value="bottom" type="radio">
				</td>
			</tr>
			<tr>
				<td>
					<label for="r-right">Right </label>
				</td>
				<td>
					<input id="r-right" name="orientation" value="right" type="radio">
				</td>
			</tr>
		</tbody></table>

	<h4>Selection Mode</h4>
	<table>
		<tbody><tr>
				<td>
					<label for="s-normal">Normal </label>
				</td>
				<td>
					<input id="s-normal" name="selection" checked="checked" value="normal" type="radio">
				</td>
			</tr>
			<tr>
				<td>
					<label for="s-root">Set as Root </label>
				</td>
				<td>
					<input id="s-root" name="selection" value="root" type="radio">
				</td>
			</tr>
		</tbody></table>

</div>

<div id="log">

</div>



<div id="infovis" style="position: relative; width: 800px; height: 800px; margin: auto; overflow: hidden;">



</div>




<?php
$result = ob_get_clean();
return $result;
