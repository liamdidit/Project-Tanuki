<?php
	
	include_once("templates/header.php");
	

	// Returns Agencies from database for dropdown
	$agency_query = mysql_query('SELECT DISTINCT agencies.agency_name FROM agencies
			ORDER BY agencies.agency_name');
	$anum = mysql_numrows($agency_query);
	
	
	// Returns Carriers from database for dropdown
	$carrier_query = mysql_query('SELECT carriers.carrier_name FROM carriers
			ORDER BY carriers.carrier_name');
	$cnum = mysql_numrows($carrier_query);
		
?>
<div id="major_wrapper">
	<div id="edit">
		<section>
			<h1>Add Agent</h1>
		</section>
		<aside id="arrow_border"></aside>
		<aside id="arrow"></aside>
	</div>
	<section id="content_wrapper">
		<form action="add_agent.php" method="post">
			<h2>Agent Information</h2>
			<section>
				<h4>First Name</h4>
				<input type="text" name="agent_first_name" required>
			</section>
			<section>
				<h4>Last Name</h4>
				<input type="text" name="agent_last_name" required>
			</section>
			<section>
				<h4>Writing Code</h4>
				<input type="text" name="agent_wc" class="writing_code" required><br />
			</section>
			
			<h2>Agency Information</h2>
			<section>
				<h4>Agency Name</h4>
				<select name="agency_name" required>
					<option value=\" \"></option>
		
<?php

			// Lists Agencies from database
			$ai = 0;
			while ($ai < $anum){
				
				$agency_name = mysql_result($agency_query, $ai);
				
				if(($agency_name != 'UNASSIGNED') AND ($agency_name != 'No Agency Assigned')) {
					echo "<option value='".$agency_name."'>".$agency_name."</option>";
				}
				
				$ai++;
			}
			
			echo "<option value ='UNASSIGNED'>I don't know ...</option>";

?>

				</select>
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
			<h2></h2>
			<section>
				<input class='add_button' type='submit' name='edit' value='Submit'>
			</section>
		</form>
	</section>
</div>
</div>

</body>
</html>