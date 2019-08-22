<script type="text/javascript">
	$(document).ready(function ()
	{
		$('.datepicker').pickmeup({
			//position		: 'right',
			//flat		: true,
			//trigger_event: 'touchstart',
			hide_on_select: true,
			format: 'Y-m-d',
			mode: 'single',
			//mode		: 'range',
			//calendars	: 2
		});
	});
</script>
<div class="o_h ">
	<h2>
		Detailed information about selected user
	</h2>
</div>

<div class="line"></div>

<div class="o_h">
	|USER_INFO|
</div>

<br/>

<div class="o_h ">
	<div class="d_t w_100">
		<div class="d_tr">
			<div class="d_tc w_50">
				|USER_MENU|
			</div>
			<div class="d_tc ta_l w_50">
				<h2>
					FILTER DATES
				</h2>
				&nbsp;
				<form action="" method="get">
					<input name="user" value="|USER_ID|" type="hidden"></input>
					<input class="datepicker" name="date_start" type="text" value="|DATE_START|"></input>
					<input class="datepicker" name="date_end" type="text" value="|DATE_END|"></input>
					<input type="submit" value="filter"></input>
				</form>
			</div>
		</div>
	</div>

	<div class="line"></div>
	|USER_LINKS|
</div>