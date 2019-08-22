<script type="text/javascript">
	$(document).ready(function ()
	{
		var $mapCenter = new google.maps.LatLng('|LATITUDE|', '|LONGITUDE|');
		var $mapOptions = {
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: $mapCenter,
			zoom: 1
		};
		var $mapCanvas = new google.maps.Map(document.getElementById("map_canvas"), $mapOptions);
		function google_map($data)
		{
			var $markerIconScale = 15;
			var $markerIcon = {
				path: google.maps.SymbolPath.CIRCLE,
				fillColor: '#B23F3F',
				fillOpacity: 0.5,
				strokeWeight: 1,
				scale: $markerIconScale
			};
			var $mapBounds = new google.maps.LatLngBounds();
			$.each($data, function ($key, $object)
			{
				var $coordinate = new google.maps.LatLng($object['latitude'], $object['longitude']);
				$markerIcon.scale = $object['size'];
				var $marker = new google.maps.Marker({
					position: $coordinate,
					icon: $markerIcon,
					title: $object['title'] + ' (' + $object['count'] + ')'
				});
				$marker.setMap($mapCanvas);
				// console.log($marker.getPosition());
				$mapBounds.extend($marker.getPosition());
			});
			$mapCanvas.fitBounds($mapBounds);
		}
		//google.maps.event.addDomListener(window, 'load', initialize);
		//google.maps.event.trigger($mapCanvas, 'resize');
		$response = JSON.parse('|LOCATION|');
		google_map($response);
	});
</script>

<div id="map_canvas" style="position: relative; width: 100%; height: 100%; min-height: 650px;">&nbsp;</div>