<script type="text/javascript">
	$(document).ready(function () {
		var $actionId;
		var $data;

		$actionId = '|ACTION|';

		$('#animate_container_site').css({
			'position': 'absolute',
			'height': $(document).height(),
			'width': $(document).width()
		});
		$('#animate_container_image').css({
			'position': 'absolute',
			'height': $(document).height(),
			'width': $(document).width()
		});

		$.data(document.body, 'heat', true);

		window.setInterval(function () {
			var $response;
			var $data;
			$data = $.data(document.body, 'heat');
			if ($data === false)
			{
				console.log('animation in progress');
				return false;
			}
			$.ajax({
				type: 'GET',
				url: '|URL|',
				data: {
					statistic: '|STATISTIC|',
					action: $actionId
				},
				async: false,
				complete: function (jqXHR, textStatus)
				{
					try {
						$response = jqXHR.responseText;
						if ($response.length > 0)
						{
							console.log('ajax -> new data');
							$data = JSON.parse($response);
							$actionId = $data[$data.length - 1]['id'];
							heat($data);
						} else {
							console.log('ajax -> empty data');
						}
					} catch (e) {
						console.log(e);
					} finally {
					}
				}
			});
		}, 1000);

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

	});

</script>

<div id="content" class="o_h">
	|CONTENT|
	<br/>
	|CHAT_ID|
	<br/>
	|URL_CHAT|
	<br/>
	|CHAT_USER|
</div>



<div id="animate_container" style="position: fixed; top: 0px; left: 0px; height: 100%; width: 100%; border: solid 1px; background-color: #CCC;">
	<iframe id="animate_container_site" name="heatNoApi" src="|SITE_URL|" style="background: #FFFFFF;">
	iframe is not supported
	</iframe>
	<div id="animate_container_image"></div>
	<span id="mouse_cursor">&nbsp;</span>
	<span id="mouse_skip">&nbsp;</span>
</div>

<div id="chat_container" style="position: fixed; top: 0px; right: 0px; border: solid 1px; background-color: #CCC;">
	<div class=''>
		<div class=''>
			<div id='chat_history'>

			</div>
		</div>
		<hr/>
		<div class=''>
			<div id='chat_form'>

			</div>
		</div>
	</div>
</div>