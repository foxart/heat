$(document).ready(function(){
	$('._blank').livequery(function () {
		$(this).click(function(){
			$url = $(this).attr('href');
			window.open($url, '_blank');
			return false;
		});

	});
});