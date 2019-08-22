(function () {
	var $container = $('#heat_container');

	$('#pdf').on('click', function () {

		$height = $('body').outerHeight();
		console.log($height);

		$data = $.data($container[0]);
		$width = $data.width;
		$height = $data.height;
		$height = 2000;
		$cache_width = $data.width;
		$format = [$width, $height];
		console.log($format);
//		$('body').scrollTop(0);
		createPDF();
		return false;
	});

	// create canvas object
	function getCanvas() {
		return html2canvas($container, {
			imageTimeout: 2000,
			// removeContainer: true
		});
	}

	//create pdf
	function createPDF() {
		getCanvas().then(function (canvas) {
			var img = canvas.toDataURL("image/png");
			var doc = new jsPDF({orientation: 'landscape', unit: 'px', format: 'a1'});
//			var doc = new jsPDF('portrait', 'px', $format);
			doc.addImage(img, 'JPEG', 0, 0);
			doc.save('export.pdf');
//			$container.width($cache_width);

		});
	}

}());

