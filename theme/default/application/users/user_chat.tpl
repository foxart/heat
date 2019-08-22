<!--
<script type='text/javascript'>
	$(document).ready(function ()
	{
		var $chatId;
		var $chatUrl;
		var $chatUser;
		$chatId = '|CHAT_ID|';
		$chatUrl = '|URL_CHAT|';
		$chatUser = '|CHAT_USER|';
		fa_chat_create($chatId, $chatUrl, $chatUser);
		window.setInterval(function () {
			var $heatChat = $.data(document.body, 'heatChat');
			if ($heatChat === true)
			{
				fa_chat_refresh();
			}
		}, 1000);
	});
</script>
-->
<h2>
	Chat with the selected user
</h2>

<div class="line"></div>
{{ CHAT_HISTORY }}

<!--
<div class='d_t w_100 h_100'>
	<div class='d_tr h_100'>
		<div class='d_tc w_50 h_100 '>
			|URL_CHAT|
		</div>
		<div class='d_tc va_t h_100'>
			<div id='chat_form'></div>
		</div>
	</div>
</div>

<div class='d_t w_100 h_100'>
	<div class='d_tr h_100'>
		<div class='d_tc w_50 h_100 '>
			<div id='chat_history'>
			</div>
		</div>
		<div class='d_tc va_t h_100'>
			<div id='chat_form'></div>
		</div>
	</div>
</div>
-->