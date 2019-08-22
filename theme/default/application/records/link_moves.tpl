<script type="text/javascript">
	$(document).ready(function ()
	{
		var $container = $('#heat_container');
		$('#heat_container_open').click(function () {
			var $metadata = $container.metadata();
			var hash = new Date().getTime();
			$imageHeatMoves = $metadata.imageHeatMoves + '&_=' + hash;
			$siteHeat = $metadata.getSite;
			$siteImage = $metadata.imageSite;
			$.ajax({
				type: "GET",
				url: $metadata.getSize,
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
						$.data($container[0], $response);
						$('#heat_container_site').css({
							'width': $response.width,
							'height': $response.height
						});

						$('#heat_container_image').css({
							'width': $response.width,
							'height': $response.height
						});
						$container.slideDown(150);
						$('#heat_container_image').attr('src', $imageHeatMoves);
						$('#heat_container_site_image').attr('src', $siteImage);
						$('#heat_container_site').attr('src', $siteHeat);
					}
				}
			});
			return false;
		});
		$('#heat_container_close').click(function () {
			$('#heat_container').slideUp(150);
			return false;
		});
	});
</script>

{{ RECORDS }}

<div class="line"></div>

<span class="button"><a id="" class="_blank" href="{{ URL_PDF }}">PDF</a></span>

<span class="button"><a id="heat_container_open" href="#">show</a></span>

<div class="line"></div>

<img alt="image" src="{{ URL_IMAGE_MOVES }}" width="250" height="250">

<div id="heat_container" class="{{ METADATA }}" style="position: fixed; display: none; top: 0px; left: 0px; width: 100%; height: 100%; overflow: auto; background-color: #FFFFFF; z-index: 1000;">
	<iframe id="heat_container_site" name="heatNoApi" src="#" style="position: absolute;">iframe is not supported</iframe>
	<img id="heat_container_site_image" alt="image" src="#" style="position: absolute; margin: auto;">
	<img id="heat_container_image" alt="image" src="#" style="position: absolute; margin: auto; opacity: 0.5;">
	<div style="position: fixed; top: 20px; right: 40px;">
		<!--
		<span class="button"><a id="pdf" href="#">pdf</a></span>
		-->
		<span class="button"><a id="heat_container_close" href="#">close</a></span>
	</div>
</div>
