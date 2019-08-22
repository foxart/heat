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