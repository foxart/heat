<script type="text/javascript">
	window.onload = function () {
		var chartUrls = new CanvasJS.Chart("chartUrls",
				{
					title: {
						text: "Urls",
						fontSize: 20
					},
					animationEnabled: false,
					zoomEnabled: true,
					axisX: {
						labelFontSize: 10,
						valueFormatString: "DD/MMM/YY"
					},
					axisY: {},
					toolTip: {
						shared: true
					},
					theme: "theme1",
					legend: {
						verticalAlign: "center",
						horizontalAlign: "right"
					},
					data: [
						{
							type: "line",
							showInLegend: true,
							name: "urls",
							markerType: "square",
							color: "#F08080",
							lineThickness: 2,
							dataPoints: [ |URLS| ]
						}
					],
					legend:{
						cursor: "pointer",
						itemclick: function (e) {
							if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
								e.dataSeries.visible = false;
							} else {
								e.dataSeries.visible = true;
							}
							chartUsers.render();
						}
					}
				});



		chartUrls.render();
	}
</script>

<h2>
	User url visit activity
</h2>

<div class="line"></div>

<div class="d_t w_100">
	<div class="d_tr">
		<div class="d_tc">
			<div id="chartUrls" style="height: 500px; width: 100%;"></div>
		</div>
	</div>
</div>

