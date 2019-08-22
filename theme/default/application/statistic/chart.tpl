<script type="text/javascript">
window.onload = function () {
	var chart = new CanvasJS.Chart("chart",
	{
		title:{
			text: "Statistic",
			fontSize: 20
		},
		animationEnabled: false,
		zoomEnabled: true,
		axisX:{
			labelFontSize: 10
		},
		axisY: {
			includeZero: false
		},		
		toolTip:{
			shared: true
		},
		theme: "theme1",
		legend:{
			verticalAlign: "center",
			horizontalAlign: "right"
		},
		data: [
			{
				type: "area",
				showInLegend: true,
				name: "clicks",
				color: "orange",
				dataPoints: [|CLICKS|]
			},
			{
				type: "stepLine",
				showInLegend: true,
				name: "moves",
				color: "blue",
				lineThickness: 3,
				dataPoints: [|MOVES|]
			},
			{
				type: "spline",
				showInLegend: true,
				name: "scrolls",
				color: "violet",
				lineThickness: 3,
				dataPoints: [|SCROLLS|]
			},
			{
				type: "candlestick",
				showInLegend: true,
				name: "visits",
				color: "black",
				lineThickness: 1,
				dataPoints: [|VISITS|]
			},
			{
				type: "line",
				showInLegend: true,
				name: "users",
				markerType: "circle",
				markerSize: 12,
				color: "red",
				lineThickness: 6,
				dataPoints: [|USERS|]
			},
		],
		legend:{
			cursor:"pointer",
			itemclick:function(e){
				if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				} else {
					e.dataSeries.visible = true;
				}
				chartUsers.render();
			}
		}
	});

	
	
	chart.render();
}
</script>

<div class="d_t w_100">
	<div class="d_tr">
		<div class="d_tc">
			<div id="chart" style="height: 650px; width: 100%;"></div>
		</div>
	</div>
</div>

