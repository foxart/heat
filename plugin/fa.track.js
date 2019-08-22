var $faTrackData = new Array();
var $faTrackApi = 'comodo.heat.api.php';
var $faTrackId = 0;
var $faTrackUrl = window.location.href; // Returns full URL
var $faTrackInterval = 5000;
// var $faTrackUrl = window.location.pathname; // Returns only PATH



function fa_track_check()
{
	console.log('fa_track_check');
	$.ajax({
		type: "POST",
		url: $faTrackApi,
		data: {
			method: 'check',
			url: $faTrackUrl,
			time: Date.now(),
			width: $(document).width(),
			height: $(document).height()
		},
		async: false,
		complete: function (jqXHR, textStatus)
		{
			try {
				$response = JSON.parse(jqXHR.responseText);
				console.log($response);
				if ($response.status == 'success')
				{
					$faTrackId = $response.statistic_id;

					if ($response.statistic_url_image == 1)
					{
						fa_track_image($response.statistic_url_id);
					}
				} else {
					console.log('fa_track_check -> error');
					console.log(jqXHR.responseText);
				}
			} catch (e) {
				console.log('fa_track_check -> api error');
				console.log(jqXHR.responseText);
//				$(body).html(jqXHR.responseText);
			} finally {
			}
		}
	});
}

function fa_track_log($data, $async)
{
	console.log('fa_track_log');
//	console.log($data);
	$.ajax({
		type: "POST",
		url: $faTrackApi,
		data: {
			method: 'log',
			id: $faTrackId,
			data: $data
		},
		async: $async,
		complete: function (jqXHR, textStatus)
		{
			if ($response.status !== 'success')
			{
				console.log('fa_track_log -> error');
				console.log(jqXHR.responseText);
			}
		}
//		$(body).html(jqXHR.responseText);
	});
}

function fa_track_image($faTrackId)
{
	console.log('fa_track_image');
	var $image;
	html2canvas(document.body, {
		onrendered: function (canvas) {
			console.log('html2canvas -> before send');
			$image = canvas.toDataURL().replace(/^data[:]image\/(png|jpg|jpeg)[;]base64,/i, '');
			// $image = canvas.toDataURL('image/jpeg');
			$.ajax({
				type: "POST",
				url: $faTrackApi,
				data: {
					method: 'image',
					id: $faTrackId,
					image: $image,
					width: $(document).width(),
					height: $(document).height()
				},
				// async: false,
				async: true,
				complete: function (jqXHR, textStatus)
				{
					console.log('html2canvas -> complete');
					console.log(jqXHR.responseText);
					if ($response.status != 'success')
					{
						console.log('fa_track_image -> error');
						console.log(jqXHR.responseText);
					}
				}
			});
		}
	});
}

function fa_track_data($position, action)
{
	$track = new Object;
	var $time = Date.now();
	$track.action = action;
	$track.time = $time;
	$track.x = $position.x;
	$track.y = $position.y;
	$track.width = $(document).width();
	$track.height = $(document).height();
	return $track;
}

$.fn.extend({
	create_track_data: function ($position, action)
	{
		$track = new Object;
		var $time = Date.now();
		$track.action = action;
		$track.time = $time;
		$track.x = $position.x;
		$track.y = $position.y;
		$track.width = $(document).width();
		$track.height = $(document).height();
		return $track;
	},
	faTrack: function (options)
	{
		$moveStep = 25;
		$scrollStep = 10;
		$dataLimit = 100;

		$mouse = new Object;
		$mouse.x = 0;
		$mouse.y = 0;
		$mousePrev = new Object;
		$mousePrev.x = 0;
		$mousePrev.y = 0;

		$position = new Object;
		$scroll = new Object;
		$scroll.x = 0;
		$scroll.y = 0;
		$scrollPrev = new Object;
		$scrollPrev.x = 0;
		$scrollPrev.y = 0;

		$(this).each(function ()
		{
			$this = $(this);
			var $count = 0;
			$this.bind({
				mousemove: function (event)
				{
					$mouse.x = event.pageX;
					$mouse.y = event.pageY;
					if (Math.abs($mouse.x - $mousePrev.x) > $moveStep || Math.abs($mouse.y - $mousePrev.y) > $moveStep)
					{

						$mousePrev.x = $mouse.x;
						$mousePrev.y = $mouse.y;
						// $faTrackData[$count] = fa_track_data($mouse, 'move');
						$faTrackData.push(fa_track_data($mouse, 'move'));
						$count++;
						if ($count == $dataLimit)
						{
							$count = 0;
							fa_track_log($faTrackData, true);
							$faTrackData = new Array();
						}
						$.data(document.body, 'heatCountCurr', $count);
					}
				},
				click: function (event)
				{
					$mouse.x = event.pageX;
					$mouse.y = event.pageY;
					// $faTrackData[$count] = fa_track_data($mouse, 'click');
					$faTrackData.push(fa_track_data($mouse, 'click'));
					$count++;
					if ($count == $dataLimit)
					{
						$count = 0;
						fa_track_log($faTrackData, true);
						$faTrackData = new Array();
					}
					$.data(document.body, 'heatCountCurr', $count);
				},
				scroll: function ()
				{
					$scroll.x = $(window).scrollLeft();
					$scroll.y = $(window).scrollTop();
					if (Math.abs($scroll.x - $scrollPrev.x) > $scrollStep || Math.abs($scroll.y - $scrollPrev.y) > $scrollStep)
					{
						// $position.x = $scrollPrev.x - $scroll.x;
						// $position.y = $scrollPrev.y - $scroll.y;
						$scrollPrev.x = $scroll.x;
						$scrollPrev.y = $scroll.y;
						// $faTrackData[$count] = fa_track_data($position, 'scroll');
						$faTrackData.push(fa_track_data($scroll, 'scroll'));
						$count++;
						if ($count == $dataLimit)
						{
							$count = 0;
							fa_track_log($faTrackData, true);
							$faTrackData = new Array();
						}
						$.data(document.body, 'heatCountCurr', $count);
					}
				},
			});
		})
	}
});



$(document).ready(function ()
{
	if (window.name !== 'heatNoApi')
	{
		$.data(document.body, 'heatCountCurr', 0);
		$.data(document.body, 'heatCountPrev', null);
		window.setInterval(function () {
			var $currCount = $.data(document.body, 'heatCountCurr');
			var $prevCount = $.data(document.body, 'heatCountPrev');
			if ($currCount === $prevCount && $currCount !== 0)
			{
				fa_track_log($faTrackData, true);
				$faTrackData = new Array();
				$.data(document.body, 'heatCountCurr', 0);
			} else {
				$.data(document.body, 'heatCountPrev', $currCount);
			}
		}, $faTrackInterval);
		fa_track_check();
		$(document).faTrack();
	}
});

$(window).bind('beforeunload', function () {
	if (window.name !== 'heatNoApi')
	{
		$mouse = new Object;
		$mouse.x = 0;
		$mouse.y = 0;
		$faTrackData.push(fa_track_data($mouse, 'exit'));
		fa_track_log($faTrackData, false);
	}
});