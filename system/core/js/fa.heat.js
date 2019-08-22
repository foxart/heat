get_size = function ($object, $recursive) {
	var $size = 0;
	for (var $property in $object) {
		if ($object.hasOwnProperty($property)) {
			if ($recursive == true)
			{
				if ($.type($object[$property]) == 'object') {
					$size = $size + this.get_size($object[$property], true);
				} else {
					//found a property which is not an object, check for your conditions here
				}
			}
			$size++;
		}
	}
	return $size;
};
function heat($data)
{
//	var $data;
//	$data = $.data(document.body, 'heat');
	$.data(document.body, 'heat', false);
	console.log('animation');
	var $cursor;

	$cursor_radius = 60;
	$first = false;
	$last = false;
	$next = false;
	$delay = 0;

	// $delayAnimation = 0;

	$delayScroll = 10;
	$delayClick = 30;
	$delayMove = 1;
	$delaySkip = 30;
	

	$delayMax = 1000;
//	$delayInitialization = 600;
//	$dataLength = $data.length - 1;
	$dataLength = get_size($data) - 1;
	
//	console.log($dataLength);


	$scroll = new Object();
	$scroll.x = 0;
	$scroll.y = 0;



	$cursor = new Object();
	$cursor.domCursor = $('#mouse_cursor');
	$cursor.domSkip = $('#mouse_skip');
	$cursor.radius = $cursor_radius / 2;
	$cursor.radiusClick = $cursor_radius / 3;

	$delayAction = 0;

	// $('#heat_container_site').css({
	// 'width': $data[0].maxWidth,
	// 'height': $data[0].maxHeight
	// });
	// $('#heat_container_image').css({
	// 'width': $data[0].maxWidth,
	// 'height': $data[0].maxHeight
	// });

	$documentWidth = $(document).outerWidth();
	$documentHeight = $(document).outerHeight();

	$.each($data, function ($key, $object)
	{

		if ($key == 0)
		{
			$first = true;
			$cursor.domCursor.css({
				'top': $object.y - $cursor.radius,
				'left': $object.x - $cursor.radius
			});
		} else {
			$first = false;
		}

		if ($key == $dataLength)
		{
			$last = true;
//			console.log('last');
		} else {
//			console.log($key, $dataLength);
			$last = false;
		}

		if ($key >= 0 && $key < $dataLength)
		{
			$next = true;
			
			$objectCurr = $data[parseInt($key)];
			$objectNext = $data[parseInt($key) + 1];
			
			// if ($objectCurr.url !== $objectNext.url)
				
			console.log($objectCurr.url);
			
			if ($objectCurr.url.localeCompare($objectNext.url) !==0)
			{
				$('#animate_container_site').attr('src', $objectNext.url)
				
				
				
			};
			
			// console.log($objectNext);
			
			//$objectNext = $data[$key + 1];
		} else {
			$next = false;
		}

		if ($next == true)
		{
			$delayAction = parseInt($objectNext.time) - parseInt($object.time);
//			console.log($objectNext.time);
			if ($delayAction > $delayMax)
			{
				if ($object.action == 'move')
				{
					$object.action = 'skip';
				}
//				$delayAction = $delaySkip;
				$delayAction = 0;
			}
			
		}

		if ($first == true)
		{
			//$delayAction = parseInt($objectNext.time) - parseInt($object.time);
			$object.action = 'start';
		}

		if ($last == true)
		{
			//$delayAction = 1000;
			//$delayAction = parseInt($object.time);
			$object.action = 'end';
		}

		$delay = $delay + $delayAction;

		$object.delay = $delay;


		// if ($object.action == 'start' || $object.action == 'end')
		if ($first == true || $last == true)
		{
//			$delay = $delay + $delayInitialization;
		}

		$shiftX = $documentWidth - $object.x;
		$shiftY = $documentHeight - $object.y;

		if ($shiftY < 0)
		{
			$object.shiftY = $shiftY;
		} else {
			$object.shiftY = 0;
		}
		if ($shiftX < 0)
		{
			$object.shiftX = $shiftX;
		} else {
			$object.shiftX = 0;
		}

		if ($object.action == 'scroll')
		{
			$delay = $delay + $delayScroll;
		}

		if ($object.action == 'click')
		{
			$delay = $delay + $delayClick;
		}

		if ($object.action == 'skip')
		{
			$delay = $delay + $delaySkip;
		}

		if ($object.action == 'move')
		{
			// $object.x = $object.x - $scroll.x;
			// $object.y = $object.y - $scroll.y;
			$delay = $delay + $delayMove;
		}
		

		
		
	});

	$.each($data, function ($key, $object)
	{
		$object.x = $object.x - 0;
		$object.y = $object.y - 0;
		heat_animation_run($object, $cursor);
	});

}

function heat_animation_run($data, $cursor)
{
	setTimeout(function ()
	{
		$cursor.domCursor.css({
			'background-color': '#FFFFFF'
		});

		if ($('#heat_container').data('heatStop') == true)
		{

		}

		if ($data.action == 'click')
		{
			// console.log($data);

			$cursor.domCursor.css({
				'background-color': '#EFCFCF'
			});
			$cursor.domCursor.animate({
				'top': $data.y - $cursor.radiusClick + $data.shiftY,
				'left': $data.x - $cursor.radiusClick + $data.shiftX,
				'width': $cursor.radiusClick * 2,
				'height': $cursor.radiusClick * 2
			}, $delayClick / 2).animate({
				'top': $data.y - $cursor.radius + $data.shiftY,
				'left': $data.x - $cursor.radius + $data.shiftX,
				'width': $cursor.radius * 2,
				'height': $cursor.radius * 2
			}, $delayClick / 2);
		}

		if ($data.action == 'move')
		{
			$cursor.domCursor.css({
				'top': $data.y - $cursor.radius + $data.shiftY,
				'left': $data.x - $cursor.radius + $data.shiftX,
				'width': $cursor.radius * 2,
				'height': $cursor.radius * 2
			});
			$cursor.domSkip.hide();
			if ($data.shiftY < 0)
			{

				$('#heat_container_site').css({
					'top': $data.shiftY
				});
				$('#heat_container_image').css({
					'top': $data.shiftY
				});
			}
			if ($data.shiftX < 0)
			{
				$('#heat_container_site').css({
					'left': $data.shiftX
				});
				$('#heat_container_image').css({
					'left': $data.shiftX
				});
			}
		}

		if ($data.action == 'scroll')
		{
			// $('#heat_container_image').css({
			// 'top': -1 * $data.y,
			// 'left': -1 * $data.x
			// });

			$('#animate_container_site').css({
				'top': -1 * $data.y,
				'left': -1 * $data.x,
				'height': $('#animate_container_site').height() + $data.y,
				'width': $('#animate_container_site').width() + $data.x
			});

			// $cursor.domSkip.html('scroll');
			// $cursor.domSkip.css({
			// 'top': $(document).outerHeight()/2 - $cursor.domSkip.outerHeight()/2,
			// 'left': $(document).outerWidth()/2 - $cursor.domSkip.outerWidth()/2
			// });
			// $cursor.domSkip.fadeIn($delayScroll/2);//.fadeOut(300);
		}

		if ($data.action == 'skip')
		{
			$cursor.domSkip.html('skip idle');
			$cursor.domSkip.css({
				'top': $data.y - $cursor.domSkip.outerHeight() / 2,
				'left': $data.x - $cursor.domSkip.outerWidth() / 2
			});
			$cursor.domSkip.fadeIn($delaySkip / 2);//.fadeOut(300);
		}

		if ($data.action == 'start')
		{
//			$cursor.domSkip.html('start');
//			$cursor.domSkip.css({
//				'top': $data.y - $cursor.domSkip.outerHeight() / 2,
//				'left': $data.x - $cursor.domSkip.outerWidth() / 2
//			});
//			$cursor.domSkip.fadeIn($delayInitialization / 2);//.fadeOut(300);
			
			$cursor.domCursor.css({
				'top': $data.y - $cursor.radius + $data.shiftY,
				'left': $data.x - $cursor.radius + $data.shiftX,
				'width': $cursor.radius * 2,
				'height': $cursor.radius * 2
			});
			$cursor.domSkip.hide();
			
		}

		if ($data.action == 'end')
		{
//			$cursor.domSkip.html('end');
//			$cursor.domSkip.css({
//				'top': $data.y - $cursor.domSkip.outerHeight() / 2,
//				'left': $data.x - $cursor.domSkip.outerWidth() / 2
//			});
//			$cursor.domSkip.fadeIn($delayInitialization / 2);//.fadeOut(300);
			
			
			$cursor.domCursor.css({
				'top': $data.y - $cursor.radius + $data.shiftY,
				'left': $data.x - $cursor.radius + $data.shiftX,
				'width': $cursor.radius * 2,
				'height': $cursor.radius * 2
			});
			$cursor.domSkip.hide();
			
			/**
			 * animation ends
			 */
			$.data(document.body, 'heat', true);
			console.log('end');
		}

		
		
	}, $data.delay);
}
