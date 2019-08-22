<script type="text/javascript">
$(document).ready(function()
{
	$('#animate_container_site').css({
		'position': 'absolute',
		'height': $(document).height(),
		'width': $(document).width()
	});
	$('#animate_container_image').css({
		'position': 'absolute',
		'height': $(document).height(),
		'width': $(document).width()
	});
	$('#heat_container_site').css({
		'position': 'absolute',
		'height': $(document).height(),
		'width': $(document).width()
	});
	$('#heat_container_image').css({
		'position': 'absolute',
		'height': $(document).height(),
		'width': $(document).width()
	});
		
	$('.fa_animate').click(function(){
		$('#animate_container').slideDown(150);
		$metadata = $(this).metadata();
		$url_data = $metadata.data;
		$url_image = $metadata.image;
		$url_site = $metadata.site;
		$('#animate_container_image').attr('src', $url_image);
		$('#animate_container_site').attr('src', $url_site);
		
		$.ajax({
			type: "GET",
			url: $url_data,
			async: true,
			complete: function(jqXHR, textStatus)
			{
				try {
					$response = JSON.parse(jqXHR.responseText);
					$('#animate_container').data().track = $response;
				} catch(e) {
					console.log(jqXHR.responseText);
					console.log(e);
				} finally {
				};
			}
		});
		return false;
	});
	
	$('#animate_container_animate').click(function(){
		$data = $('#animate_container').data();
		$track = $data.track;
		animate($track);
		return false;
	});
	
	$('#animate_container_close').click(function(){
		$('#animate_container').slideUp(150);
		return false;
	});
	
	$('.fa_heat').click(function(){
		$('#heat_container').slideDown(150);
		$metadata = $(this).metadata();
		$url_data = $metadata.data;
		$url_image = $metadata.image;
		$url_site = $metadata.site;
		$('#heat_container_image').attr('src', $url_image);
		$('#heat_container_site').attr('src', $url_site);
		
		return false;
	});
	
	$('#heat_container_close').click(function(){
		$('#heat_container').slideUp(150);
		return false;
	});
	
});
</script>

<div class="o_h ">
	<h3>
		header
	</h3>
	|HEAT_CONTENT|
</div>

<div id="animate_container" style="height: 200px;">
	<iframe id="animate_container_site" src="#" style="background: #FFFFFF;">
		iframe is not supported
	</iframe>
	<img id="animate_container_image" alt="image" src="#">
	<span id="mouse_cursor">&nbsp;</span>
	<span id="mouse_skip">&nbsp;</span>
	
	<!-- <div style="position: fixed; top: 20px; right: 40px;">
		<span class="button"><a id="animate_container_animate" href="#">animate</a></span>
		<span class="button"><a id="animate_container_close" href="#">close</a></span>
	</div> -->
</div>

<div id="heat_container" style="height: 200px;">
	<iframe id="heat_container_site" src="#">
		iframe is not supported
	</iframe>
	<img id="heat_container_image" alt="image" src="#">

	<!-- <div style="position: fixed; top: 20px; right: 40px;">
		<span class="button"><a id="heat_container_close" href="#">close</a></span>
	</div> -->
</div>

<div class="o_h">
	|ITEMS|
	|PAGINATOR|
</div>