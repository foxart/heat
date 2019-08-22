$(document).ready(function(){
	$('._blank').livequery(function () {
		$(this).click(function(){
			$url = $(this).attr('href');
			window.open($url, '_blank');
			return false;
		});

	});
});
function animate($data)
{
	// console.log($data);
	$cursor_radius = 9;
	$first = false;
	$last = false;
	$next = false;
	$delay = 0;

	// $delayAnimation = 0;

	$delaySkip = 60;
	$delayScroll = 60;
	$delayClick = 300;
	$delayMove = 30;

	$delayMax = 1200;
	$delayInitialization = 0;
	$dataLength = $data.length - 1;


	$scroll = new Object();
	$scroll.x = 0;
	$scroll.y = 0;



	$cursor = new Object();
	$cursor.domCursor = $('#mouse_cursor');
	$cursor.domSkip = $('#mouse_skip');
	$cursor.radius = $cursor.domCursor.outerHeight() / 2;
	$cursor.radiusClick = $cursor.domCursor.outerHeight() / 3;

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
		} else {
			$last = false;
		}

		if ($key >= 0 && $key < $dataLength)
		{
			$next = true;
			$objectNext = $data[parseInt($key) + 1];
			//$objectNext = $data[$key + 1];
		} else {
			$next = false;
		}
		if ($next == true)
		{
			$delayAction = parseInt($objectNext.time) - parseInt($object.time);
			if ($delayAction > $delayMax)
			{
				if ($object.action == 'move')
				{
					$object.action = 'skip';
				}
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
			$delay = $delay + $delayInitialization;
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
			$delay = $delay + $delayMax;
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
		;

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
					'left': $data.shiftX,
				});
				$('#heat_container_image').css({
					'left': $data.shiftX,
				});
			}
		}

		if ($data.action == 'scroll')
		{
			// $('#heat_container_image').css({
			// 'top': -1 * $data.y,
			// 'left': -1 * $data.x
			// });

			// $('#heat_container_site').css({
			// 'top': -1 * $data.y,
			// 'left': -1 * $data.x,
			// 'height': $('#heat_container_site').height() + $data.y,
			// 'width': $('#heat_container_site').width() + $data.x
			// });

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
			$cursor.domSkip.fadeIn($delayMax / 2);//.fadeOut(300);
		}

		if ($data.action == 'start')
		{
			$cursor.domSkip.html('start');
			$cursor.domSkip.css({
				'top': $data.y - $cursor.domSkip.outerHeight() / 2,
				'left': $data.x - $cursor.domSkip.outerWidth() / 2
			});
			$cursor.domSkip.fadeIn($delayInitialization / 2);//.fadeOut(300);
		}
		;

		if ($data.action == 'end')
		{
			$cursor.domSkip.html('end');
			$cursor.domSkip.css({
				'top': $data.y - $cursor.domSkip.outerHeight() / 2,
				'left': $data.x - $cursor.domSkip.outerWidth() / 2
			});
			$cursor.domSkip.fadeIn($delayInitialization / 2);//.fadeOut(300);
		}

	}, $data.delay);
}
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

function timestamp_to_date(unix_timestamp) {
	var a = new Date(unix_timestamp);
	var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var year = a.getFullYear();
	var month = months[a.getMonth()];
	var date = a.getDate();
	var hour = a.getHours();
	var min = a.getMinutes();
	var sec = a.getSeconds();
	var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec;
	return time;
}

function fa_chat_create($chatId, $chatUrl, $chatUser) {
	$.data(document.body, 'heatChatId', $chatId);
	$.data(document.body, 'heatChatUrl', $chatUrl);
	$.data(document.body, 'heatChatUser', $chatUser);

	$chatHistoryContainer = $('#chat_history');
	$chatFormContainer = $('#chat_form');

	$chatHistory = $('<div>').attr('id', 'chatHistory');
	$chatHistoryContent = $('<div>').attr('id', 'chatHistoryContent');
	$chatHistoryContainer.append($chatHistory);
	$chatHistory.append($chatHistoryContent);

	$chatForm = $('<div>').attr('id', 'chatForm');
	$chatFormTextarea = $('<textarea>').attr('id', 'chatFormTextarea');
	$chatFormButtonSend = $('<input type="submit">').attr('id', 'chatFormButton').val('send');
	$chatFormContainer.append($chatForm);
	$chatForm.append($chatFormTextarea);
	$chatForm.append($chatFormButtonSend);

	$chatHistoryContainer.css({
		position: 'fixed',
//		display: 'none',
		top: '0px',
		right: '0px',
		width: '500px',
		height: '240px',
		padding: '10px 0px 10px 0px',
		backgroundColor: '#26343F',
		zIndex: 9999
	});
	$chatHistory.css({
		position: 'relative',
		width: '100%',
		height: '100%',
		overflow: 'auto'
	});

	$chatHistoryContent.css({
		position: 'relative',
		margin: '0px 10px',
		width: 'auto',
		height: 'auto',
		fontSize: '13px',
		lineHeight: '19px',
		fontFamily: 'Arial',
		overflow: 'hidden'

	});


	$chatFormContainer.css({
		position: 'fixed',
//		display: 'none',
		top: '250px',
		right: '0px',
		width: '500px',
		height: '125px',
		padding: '10px 0px 10px 0px',
		backgroundColor: '#26343F',
		zIndex: 9999
	});
	$chatForm.css({
		position: 'relative',
		margin: '0px 10px 0px 10px',
		width: 'auto',
		height: 'auto'
	});
	$chatFormTextarea.css({
		position: 'relative',
		marginBottom: '10px',
		overflow: 'hidden',
		width: '100%',
		height: '85px',
		fontSize: '13px',
		lineHeight: '19px',
		fontFamily: 'Arial',
		border: 'none',
		backgroundColor: '#FFF'
	});

	$chatFormButtonSend.css({
		position: 'relative',
		float: 'left'
	});
//	$chatFormButtonClose.css({
//		position: 'relative',
//		float: 'right'
//	});
	$chatFormButtonSend.click(function () {
		console.log('click');
		fa_chat_send();
		return false;
	});

	$.data(document.body, 'heatChat', true);
//	$.data(document.body, 'heatChat', false);
}


function fa_chat_send()
{
	var $message;
	var $chatId;
	var $chatUrl;
	var $chatUser;
	var $response;
	var $data;

	$.data(document.body, 'heatChat', true);

	$chatId = $.data(document.body, 'heatChatId');
	$chatUrl = $.data(document.body, 'heatChatUrl');
	$chatUser = $.data(document.body, 'heatChatUser');

	$message = $('#chatFormTextarea').val();

	$data = {
		type: 'server',
		user: $chatUser,
		time: new Date().getTime(),
		message: $('#chatFormTextarea').val()
	};

	$.ajax({
		type: "POST",
		url: $chatUrl,
		data: {
			method: 'chat',
			id: $chatId,
			action: 'send',
			data: JSON.stringify($data)
		},
		async: true,
		complete: function (jqXHR, textStatus)
		{
			$response = JSON.parse(jqXHR.responseText);
			$.data(document.body, 'heatChatLine', $response.line);
			$('#chatFormTextarea').val('');
		}
	});
}

function fa_chat_refresh()
{
	var $chatId;
	var $chatUrl;
	var $chatLine;
	var $response;

	$chatId = $.data(document.body, 'heatChatId');
	$chatUrl = $.data(document.body, 'heatChatUrl');
	$chatLine = $.data(document.body, 'heatChatLine');

	if ($.type($chatLine) === 'undefined')
	{
		$chatLine = 0;
	}

	$.ajax({
		type: "POST",
		url: $chatUrl,
		data: {
			method: 'chat',
			id: $chatId,
			action: 'refresh',
			data: $chatLine
		},
		async: true,
		complete: function (jqXHR, textStatus)
		{
			$response = JSON.parse(jqXHR.responseText);
			if ($chatLine !== $response.line)
			{

				$messages = JSON.parse($response.message);
				$.each($messages, function ($key, $value)
				{
					$message = JSON.parse($value);

					if ($message.user === $chatId) {

						$message = '<div style="margin: 0px 0px 10px 0px; padding: 10px; width: 70%; float: left; background-color: #CD1720; color: #FFF; border-radius: 10px;">' + '<span style="float: left;"><b>user-' + $message.user + '</b></span><span style="float: right;"><i>' + timestamp_to_date($message.time) + '</i></span></br>' + '<pre class="message">' + $message.message + '</pre></div>';
					} else {

						$message = '<div style="margin: 0px 0px 10px 0px; padding: 10px; width: 70%; float: right; background-color: #FFF; color: #000; border-radius: 10px;">' + '<span style="float: left;"><b>Me</b></span><span style="float: right;"><i>' + timestamp_to_date($message.time) + '</i></span></br>' + '<pre class="message">' + $message.message + '</pre></div>';
					}

					$('#chatHistoryContent').append($message);
				});
				$.data(document.body, 'heatChatLine', $response.line);
				$("#chatHistory").animate({scrollTop: $('#chatHistoryContent').height()}, 300);

//				$.data(document.body, 'heatChatLine', $response.line);
//				$('#chatHistoryContent').append($response.message);
//				$('#chatHistoryContent').append('<br/>');
//				$("#chatHistory").animate({scrollTop: $('#chatHistoryContent').height()}, "slow");
			}
		}
	});
}