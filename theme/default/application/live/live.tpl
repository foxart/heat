
<script type="text/javascript">
	$(document).ready(function () {
		window.setInterval(function () {
			$.ajax({
				type: 'GET',
				url: '|URL|',
				async: false,
				beforeSend : function(){
					$('#background').fadeIn(500);
				},
				complete: function (jqXHR, textStatus)
				{
					$('#background').fadeOut(500);
					try {

					} catch (e) {
						console.log(e);
					} finally {
						$('#content').html(jqXHR.responseText);
					}
				}
			});
		}, 5000);

	});

</script>

<h2>monitor live</h2>

<div class="line"></div>

<div id="background" style="position: absolute; display: none; width: 100%; height: 100%; background: #B23F3F; opacity: 0.5; z-index: 9999;">&nbsp;</div>
<div id="content" class="o_h">
	|CONTENT|
</div>