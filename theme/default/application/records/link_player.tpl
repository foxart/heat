<script type="text/javascript">
	$(document).ready(function ()
	{
		var $first_record;
		var $last_record;

		$data = $("#heat_container").metadata();
		$siteHeat = $data.getSite;
		$('#preview').attr('src', $siteHeat);

		$('#heat_container_open').click(function () {
			var hash = new Date().getTime();
			var $response;
			$data = $("#heat_container").metadata();
			$imageTrack = $data.imageTrack + '&_=' + hash;
			$siteHeat = $data.getSite;
			$trackDataUrl = $data.getData;
			$.ajax({
				type: "GET",
				url: $data.getSize,
				async: true,
				cache: false,
				complete: function (jqXHR, textStatus)
				{
					try {
						$response = JSON.parse(jqXHR.responseText);
					} catch (e) {
						console.log(jqXHR.responseText);
						console.log(e);
					} finally {
						$('#heat_container_site').css({
//							'width': $response.width,
//							'height': $response.height
							'width': 800,
							'height': 600
						});
						$('#heat_container_image').css({
							'width': $response.width,
							'height': $response.height
						});
						$('#heat_container').slideDown(150);
						$('#heat_container_image').attr('src', $imageTrack);
						$('#heat_container_site').attr('src', $siteHeat);
					}
				}
			});

			$.ajax({
				type: "GET",
				url: $trackDataUrl,
				async: true,
				complete: function (jqXHR, textStatus)
				{
					try {
						$response = JSON.parse(jqXHR.responseText);
						$first_record = $response[0];
						$last_record = $response[$response.length - 1];
						console.log($last_record);
						console.log($trackDataUrl);
						animate($response);
					} catch (e) {
						console.log(jqXHR.responseText);
						console.log(e);
					} finally {
					}
				}
			});
			return false;
		});

		$('#heat_container_close').click(function () {
			$('#heat_container').slideUp(150);
			return false;
		});

		$('#heat_container_player_prev').click(function () {
			$.ajax({
				type: "GET",
				url: $trackDataUrl + '&id=' + $first_record.id,
				async: true,
				complete: function (jqXHR, textStatus)
				{
					try {
						$response = JSON.parse(jqXHR.responseText);
						$first_record = $response[0];
						$last_record = $response[$response.length - 1];
						console.log($trackDataUrl);
						animate($response);
					} catch (e) {
						console.log(jqXHR.responseText);
						console.log(e);
					} finally {
					}
				}
			});
			return false;
		});

		$('#heat_container_player_next').click(function () {
			$.ajax({
				type: "GET",
				url: $trackDataUrl + '&id=' + $last_record.id,
				async: true,
				complete: function (jqXHR, textStatus)
				{
					try {
						$response = JSON.parse(jqXHR.responseText);
						$first_record = $response[0];
						$last_record = $response[$response.length - 1];
						console.log($trackDataUrl);
						animate($response);
					} catch (e) {
						console.log(jqXHR.responseText);
						console.log(e);
					} finally {
					}
				}
			});
			return false;
		});

	});
</script>

{{ RECORDS }}

<div class="line"></div>

<span class="button"><a id="heat_container_open" href="#">show</a></span>
{{ METADATA }}



<div id="heat_container" class="{{ METADATA }}" style="position: fixed; display: none; top: 0px; left: 0px; width: 100%; height: 100%; overflow: auto; background-color: #FFFFFF; z-index: 1000;">
	<iframe id="heat_container_site" name="heatNoApi" src="#" style="position: absolute;">iframe is not supported</iframe>

	<img id="heat_container_image" alt="image" src="#" style="position: absolute;">

	<span id="mouse_cursor">&nbsp;</span>
	<span id="mouse_skip">&nbsp;</span>

	<div style="position: fixed; top: 20px; left: 40px;">
		<span class="button" >
			<a id="heat_container_player_prev"  href="#">prev</a>
		</span>
	</div>

	<div style="position: fixed; top: 60px; left: 40px;">
		<span class="button" >
			<a id="heat_container_player_next"  href="#">next</a>
		</span>
	</div>

	<div style="position: fixed; top: 20px; right: 40px;">
		<span class="button"><a id="heat_container_close" href="#">close</a></span>
	</div>

</div>
