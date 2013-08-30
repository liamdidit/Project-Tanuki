<?php
	
	include_once("templates/header.php");
	

	// Returns Payout Levels from database for dropdown
	$payout_query = mysql_query('SELECT DISTINCT gross_payout.level FROM gross_payout
			ORDER BY gross_payout.id');
	$pnum = mysql_numrows($payout_query);
	
	
	// Returns Carriers from database for dropdown
	$carrier_query = mysql_query('SELECT carriers.carrier_name FROM carriers
			ORDER BY carriers.carrier_name');
	$cnum = mysql_numrows($carrier_query);
		
?>
<div id="major_wrapper">
	<div id="edit">
		<section>
			<h1>Add Agency</h1>
		</section>
		<aside id="arrow_border"></aside>
		<aside id="arrow"></aside>
	</div>
	<section id="content_wrapper">
		<form action="add_agency.php" method="post">
			<h2>Agency Information</h2>
			<section>
				<h4>Agency Name</h4>
				<input type="text" name="agency_name" required>
			</section>
			<section>
				<h4>Writing Code</h4>
				<input type="text" class="writing_code" name="agency_wc" required>
			</section>
			<section>
				<h4>Support Level</h4>
				<select name="support_level" required>
					<option value=\" \"></option>
				
<?php

// Lists Support Model Levels from database
$pi = 0;
while ($pi < $pnum){
	
	$payout_level = mysql_result($payout_query, $pi);
	
	if($payout_level != 'Street' AND $payout_level != 'Gross' AND $payout_level != 'MV Group' AND $payout_level != 'DBS'){
		echo '<option value="'.$payout_level.'">'.$payout_level.'</option>';
	}
	
	$pi++;
}

?>
		
				</select>
			</section>
			<section>
				<h4>MV Group / NBCNM</h4>
				<input name="mv_group" value="1" type="checkbox" class="checkbox">
			</section>
			
			<h2>Carrier Information</h2>
			<section>
				<h4>Carrier Name</h4>
				<select name="carrier_name" required>
					<option value=\" \"></option>

<?php

// Lists Carriers from database
$ci = 0;
while ($ci < $cnum){
	
	$carrier_name = mysql_result($carrier_query, $ci);
	echo '<option value="'.$carrier_name.'">'.$carrier_name.'</option>';
	
	$ci++;
}

// Closes MySQL connection
mysql_close();

?>
		
				</select>
			</section>
			<section>
			<h4>Contract Level</h4>
				<input type="text" class="contract_level" name="contract_level" required>
			</section>
			<h2></h2>
			<section>
				<input class='add_button' type='submit' name='edit' value='Submit'>
			</section>
		</form>
	</section>	
</div>

</body>
</html>