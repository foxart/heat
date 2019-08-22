<script type="text/javascript">
	window.onload = function () {
		var chartUsers = new CanvasJS.Chart("chartUsers",
				{
					title: {
						text: "Users",
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
							type: "area",
							showInLegend: true,
							name: "Users",
							markerType: "circle",
							//markerSize: 12,
							color: "blue",
							lineThickness: 2,
							dataPoints: [|USERS|],
						},
						{
							type: "stepLine",
							showInLegend: true,
							name: "Enters",
							markerType: "square",
							color: "green",
							lineThickness: 2,
							dataPoints: [|ENTERS|]
						},
						{
							type: "stepLine",
							showInLegend: true,
							name: "Exits",
							markerType: "circle",
							color: "red",
							lineThickness: 2,
							dataPoints: [|EXITS|]
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
		
		var chartClicks = new CanvasJS.Chart("chartClicks",
				{
					title: {
						text: "Clicks",
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
							type: "area",
							showInLegend: true,
							name: "Clicks",
							markerType: "square",
							color: "green",
							lineThickness: 2,
							dataPoints: [|CLICKS|]
						},
						{
							type: "stepLine",
							showInLegend: true,
							name: "Moves",
							color: "orange",
							dataPoints: [|MOVES|]
						},
						{
							type: "stepLine",
							showInLegend: true,
							name: "Scrolls",
							markerType: "square",
							color: "red",
							lineThickness: 2,
							dataPoints: [|SCROLLS|]
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
		
		
		chartUsers.render();
		chartClicks.render();
	}
</script>

<div class="d_t w_100">
	<div class="d_tr">
		<div class="d_tc">
			<div id="chartUsers" style="height: 500px; width: 100%;"></div>
		</div>
		<div class="d_tc">
			<div id="chartClicks" style="height: 500px; width: 100%;"></div>
		</div>
	</div>
</div>

