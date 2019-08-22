
<script type="text/javascript">
$(document).ready(function(){


	|MAP|

	$('#map_open').click(function(){
		
		$('#map_container').slideDown(150, function()
		{
			
			//google.maps.event.trigger($mapCanvas, 'resize')
			google.maps.event.trigger($mapCanvas, 'resize');
			
			google_map($mapData);
		});
				
		return false;
	});
	
	$('#map_close').click(function(){
		$('#map_container').slideUp(150);
		return false;
	});

});
</script>


<div class="button">
	<a id="map_open" class="{data: '|URL_DATA|'}" href="#">map</a>
</div>

<div id="map_container" style="position: fixed; display: none; top: 0px; left: 0px; width: 100%; height: 100%; background-color: gray; overflow: auto; z-index: 1000;">
	
	<div id="map_canvas" style="position: fixed; width: 100%; height: 100%;">&nbsp;</div>
	
	<div style="position: fixed; top: 20px; right: 40px; z-index: 1002;">
		<span class="button"><a id="map_close" href="#">close</a></span>
	</div>
	
</div>