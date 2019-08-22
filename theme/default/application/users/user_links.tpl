<script type="text/javascript">
$(document).ready(function()
{

	$('#heat_container_animate').click(function(){
		var hash = new Date().getTime();
		$data = $('#heat_container').data('heat');
		
		$imageTrack = $data.imageTrack + '&_=' + hash;
		$('#heat_container_image').attr('src', $imageTrack);
		$siteHeat = $data.getSite;
		$('#heat_container_site').attr('src', $siteHeat);
		
		$trackData = $data.getData;
		
		$.ajax({
			type: "GET",
			url: $data.getSize,
			async: true,
			cache: false,
			complete: function(jqXHR, textStatus)
			{
				try {
					$response = JSON.parse(jqXHR.responseText);
					console.log($response);
				} catch(e) {
					console.log(jqXHR.responseText);
					console.log(e);
				} finally {
					$('#heat_container_site').css({
						'width': $response.width,
						'height': $response.height
					});
					$('#heat_container_image').css({
						'width': $response.width,
						'height': $response.height
					});
				};
			}
		});
	
		$.ajax({
			type: "GET",
			url: $trackData,
			async: true,
			complete: function(jqXHR, textStatus)
			{
				try {
					$response = JSON.parse(jqXHR.responseText);
					animate($response);
				} catch(e) {
					console.log(jqXHR.responseText);
					console.log(e);
				} finally {
				};
			}
		});
		return false;
	});
	
	$('#heat_clicks').click(function(){
		var hash = new Date().getTime();
		$data = $('#heat_container').data('heat');
		$imageHeatClicks = $data.imageHeatClicks + '&_=' + hash;
		$('#heat_container_image').attr('src', $imageHeatClicks);
		$siteHeat = $data.getSite;
		$('#heat_container_site').attr('src', $siteHeat);
		$.ajax({
			type: "GET",
			url: $data.getSize,
			async: true,
			cache: false,
			complete: function(jqXHR, textStatus)
			{
				try {
					$response = JSON.parse(jqXHR.responseText);
				} catch(e) {
					console.log(jqXHR.responseText);
					console.log(e);
				} finally {
					$('#heat_container_site').css({
						'width': $response.width,
						'height': $response.height
					});
					$('#heat_container_image').css({
						'width': $response.width,
						'height': $response.height
					});
				};
			}
		});
		return false;
	});

	$('#heat_moves').click(function(){
		var hash = new Date().getTime();
		$data = $('#heat_container').data('heat');
		$imageHeatMoves = $data.imageHeatMoves + '&_=' + hash;
		$('#heat_container_image').attr('src', $imageHeatMoves);
		$siteHeat = $data.getSite;
		$('#heat_container_site').attr('src', $siteHeat);
		$.ajax({
			type: "GET",
			url: $data.getSize,
			async: true,
			cache: false,
			complete: function(jqXHR, textStatus)
			{
				try {
					$response = JSON.parse(jqXHR.responseText);
				} catch(e) {
					console.log(jqXHR.responseText);
					console.log(e);
				} finally {
					$('#heat_container_site').css({
						'width': $response.width,
						'height': $response.height
					});
					$('#heat_container_image').css({
						'width': $response.width,
						'height': $response.height
					});
				};
			}
		});
		return false;
	});
	
	$('.fa_heat').click(function(){
		$('#heat_container').slideDown(150);
		$metadata = $(this).metadata();
		$('#heat_container').data('heat', $metadata);
		$('#heat_container').data('heatStop', false);
		return false;
	});
	
	$('#heat_container_close').click(function(){
		//$('#heat_container').slideUp(150);
		//$('#heat_container').data('heatStop', true);
		//return false;
	});

});
</script>

<div id="heat_container" style="position: fixed; display: none; top: 0px; left: 0px; width: 100%; height: 100%; overflow: auto; background-color: #FFFFFF; z-index: 1000;">
	<iframe id="heat_container_site" name="heatNoApi" src="#" style="position: absolute;">
		iframe is not supported
	</iframe>
	<img id="heat_container_image" alt="image" src="#" style="position: absolute;">
	<span id="mouse_cursor">&nbsp;</span>
	<span id="mouse_skip">&nbsp;</span>
	<div style="position: fixed; top: 20px; right: 40px;">
		<span class="button"><a id="heat_container_animate" href="#">animate</a></span>
		<span class="button"><a id="heat_clicks" href="#">clicks heatmap</a></span>
		<span class="button"><a id="heat_moves" href="#">moves heatmap</a></span>
		<span class="button"><a id="heat_container_close" href="">close</a></span>
	</div>
</div>


<h2>List of links have been visited by selected user</h2>
<div class="line"></div>
|ITEMS|
|PAGINATOR|