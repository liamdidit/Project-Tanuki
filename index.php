<?php
	
	include_once("templates/header.php");
	
?>
	
	<div id="middle_section">
		<div id="search">
			<section>
				<h1>Search</h1>
			</section>
			<aside id="arrow_border"></aside>
			<aside id="arrow"></aside>
		</div>
		<div id="search_form">
			<form action="search.php" method="get">
				<input class="text_area" type="text" name="query">
				<input class="submit_button" type="submit" value="&#128269;" class="search_button">
			</form>
		</div>
	</div>
	
	<div id="last_section">
		<div id="report">
			<section>
				<h1>Report</h1>
			</section>
			<aside id="arrow_border"></aside>
			<aside id="arrow"></aside>
		</div>
		<div id="report_form">
			<form action="search.php" method="GET">
				<input type="text" name="agency_input" placeholder="agency name">
		    	<input type="text" name="agent_input" placeholder="agent last">
		    	<input type="text" name="carrier_input" placeholder="carrier">
		    	<input type="text" name="start_date_input" class="datepicker" id="start_date" placeholder="starting on ...">
		    	<input type="text" name="end_date_input" class="datepicker" id="end_date" placeholder="ending on ...">
		    	<input class="submit_button" type="submit" value="&#128269;" class="search_button">
			</form>
		</div>
	</div>
	
	</div>
	
</body>
</html>