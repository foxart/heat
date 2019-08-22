var $faTrackApi = 'comodo.heat.api.php';
var $faChatInterval = 3000;

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



function fa_chat_create() {
	$chatHistoryContainer = $('<div>').attr('id', 'chat_history');
	$chatFormContainer = $('<div>').attr('id', 'chat_form');

	$('body').append($chatHistoryContainer);
	$('body').append($chatFormContainer);

	$chatHistory = $('<div>').attr('id', 'chatHistory');
	$chatHistoryContent = $('<div>').attr('id', 'chatHistoryContent');
	$chatHistoryContainer.append($chatHistory);
	$chatHistory.append($chatHistoryContent);

//	$chat = $('<div>').attr('id', 'chat');


	$chatForm = $('<div>').attr('id', 'chatForm');
	$chatFormTextarea = $('<textarea>').attr('id', 'chatFormTextarea');
	$chatFormButtonSend = $('<input type="submit">').attr('id', 'chatFormButtonSend').val('send');
	$chatFormButtonClose = $('<input type="button">').attr('id', 'chatFormButtonClose').val('close');


	$chatFormContainer.append($chatForm);
	$chatForm.append($chatFormTextarea);
	$chatForm.append($chatFormButtonSend);
	$chatForm.append($chatFormButtonClose);


	$chatHistoryContainer.css({
		position: 'fixed',
		display: 'none',
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
		display: 'none',
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
	$chatFormButtonClose.css({
		position: 'relative',
		float: 'right'
	});


	$chatFormButtonSend.click(function () {
		$message = $('#chatFormTextarea').val();
		$('#chatFormTextarea').val('');
		fa_chat_send($message, 'user');
		return false;
	});

	$chatFormButtonClose.click(function () {
		$chatHistoryContainer.hide();
		$chatFormContainer.hide();
		$message = 'user closed chat window';
		fa_chat_send($message, 'system');
		return false;
	});


	$.data(document.body, 'heatChat', true);
}


function fa_chat_send($message, $type)
{
	var $chatId;
	var $data;
	var $response;

	$.data(document.body, 'heatChat', true);
	$chatId = $.data(document.body, 'heatChatId');

	$data = {
		type: $type,
		user: $chatId,
		time: new Date().getTime(),
		message: $message
	};
	$.ajax({
		type: "POST",
		url: $faTrackApi,
		data: {
			method: 'chat',
			id: $chatId,
			action: 'send',
			data: JSON.stringify($data)
		},
		async: true,
		complete: function (jqXHR, textStatus)
		{
			try {
				$response = JSON.parse(jqXHR.responseText);
				$.data(document.body, 'heatChatLine', $response.line);
			} catch (e) {
				console.log('fa_chat -> api error');
				console.log(jqXHR.responseText);
				$(body).html(jqXHR.responseText);
			} finally {
				console.log('fa_chat_send');
			}

		}
	});
}

function fa_chat_refresh()
{
	var $chatLine;
	var $chatId;
	var $response;
	var $message;
	var $messages;


	$chatLine = $.data(document.body, 'heatChatLine');
	$chatId = $.data(document.body, 'heatChatId');

	if ($.type($chatLine) === 'undefined')
	{
		$chatLine = 0;
	}

	$.ajax({
		type: "POST",
		url: $faTrackApi,
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

				$lastMessage = JSON.parse($messages[$messages.length - 1]);
				if ($lastMessage.user !== $chatId) {
					$('#chat_history').show();
					$('#chat_form').show();
				}


				$.each($messages, function ($key, $value)
				{
					$message = JSON.parse($value);
					if ($message.user === $chatId) {
						$message = '<div style="margin: 0px 0px 10px 0px; padding: 10px; width: 70%; float: right; background-color: #FFF; color: #000; border-radius: 10px;">' + '<span style="float: left;"><b>Me</b></span><span style="float: right;"><i>' + timestamp_to_date($message.time) + '</i></span></br>' + '<pre class="message">' + $message.message + '</pre></div>';

					} else {

						$message = '<div style="margin: 0px 0px 10px 0px; padding: 10px; width: 70%; float: left; background-color: #CD1720; color: #FFF; border-radius: 10px;">' + '<span style="float: left;"><b>' + $message.user + '</b></span><span style="float: right;"><i>' + timestamp_to_date($message.time) + '</i></span></br>' + '<pre class="message">' + $message.message + '</pre></div>';
					}

					$('#chatHistoryContent').append($message);
				});
				$.data(document.body, 'heatChatLine', $response.line);
				$("#chatHistory").animate({scrollTop: $('#chatHistoryContent').height()}, 300);
			}
		}
	});

}


$(document).ready(function ()
{
	var $faTrackUrl = window.location.href; // Returns full URL

	var $faGeoId;
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
				if ($response.status === 'success')
				{
					$faGeoId = $response.statistic_geo_id;
					$.data(document.body, 'heatChatId', $faGeoId);
				} else {
					console.log('fa_track_check -> error');
					console.log(jqXHR.responseText);
				}
			} catch (e) {
				console.log('fa_track_check -> api error');
				console.log(jqXHR.responseText);
			} finally {
			}
		}
	});

	if (window.name !== 'heatNoApi')
	{
		fa_chat_create();

		window.setInterval(function () {
			var $heatChat = $.data(document.body, 'heatChat');
			if ($heatChat === true)
			{
				fa_chat_refresh();
			}
		}, $faChatInterval);
	}
});

