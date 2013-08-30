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
			<h1>Edit Agency</h1>
		</section>
		<aside id="arrow_border"></aside>
		<aside id="arrow"></aside>
	</div>
	<section id="content_wrapper">
	
<?php
			
// Dynamically retrieve entry for editing
$id = $_GET['id'];
	
	// Searches the table for the Agency based on the ID passed through the URL
	$id_result = mysql_query("SELECT agencies.id, agencies.agency_name FROM agencies
		WHERE (agencies.id = '$id')") or die('No results were found ...');
	$idnum = mysql_numrows($id_result);
	
	// Agency variables
	$agency_id = mysql_result($id_result,0,"agencies.id");
	$agency_name = mysql_result($id_result,0,"agencies.agency_name");
	
	// Returns all of the Agency's Writing Codes
	$writing_code_query = mysql_query("SELECT DISTINCT agencies.id, writing_codes.agency_wc, writing_codes.contract_level, writing_codes.carrier_id, agencies.agency_name, carriers.carrier_name FROM writing_codes
		INNER JOIN agencies
			ON writing_codes.agency_wc = agencies.agency_wc
		INNER JOIN carriers
			ON writing_codes.carrier_id = carriers.id
		WHERE (agencies.agency_name = '$agency_name')") or die('No results were found ...');
	$wnum = mysql_numrows($writing_code_query);

	echo "<form action='edit_agency_name.php' method='post'>";
		echo "<h2>Agency Information</h2>";
		echo "<input type='hidden' value='".$id."' name='agency_id' size='2' READONLY>";
		echo "<section>";
			echo "<h4>Name</h4>";
			echo "<input type='text' value='".$agency_name."' name='agency_name' required>";
		echo "</section>";
		echo "<input type='submit' name='edit' class='edit_button' value='&#9998;'>";
		echo "<input type='submit' name='delete_agent' class='delete_button' value='&#59177;'>";
	echo "</form>";
	
	// Set Writing Code variables for the information returned from the query
	if($wnum > 0){
		
			echo "<form>";
				echo "<h2>Writing Codes</h2>";
			echo "</form>";
		
		$wi = 0;
		while ($wi < $wnum){
	
			$agency_wc = mysql_result($writing_code_query, $wi, "writing_codes.agency_wc");
			$agency = mysql_result($writing_code_query, $wi, "agencies.agency_name");
			$carrier_name = mysql_result($writing_code_query, $wi, "carriers.carrier_name");
			$contract_level = mysql_result($writing_code_query, $wi, "writing_codes.contract_level");
			$wc_id = mysql_result($writing_code_query, $wi, "agencies.id");
		
				echo "<form action='edit_agency_wc.php' method='post'>";
				echo "<input type='hidden' value='".$wc_id."' name='agency_id' size='2' READONLY>";
					echo "<section>";
						echo "<input type='text' class='carrier_name' value='".$carrier_name."' name='carrier_name' READONLY />";
					echo "</section>";
					echo "<section>";
						echo "<input type='text' class='writing_code' name='agency_wc' value='".$agency_wc."'>";
					echo "</section>";
					echo "<section>";
						echo "<input type='text' class='contract_level' name='contract_level' value='".$contract_level."' required>";
					echo "</section>";
					echo "<input type='submit' name='edit' class='edit_button' value='&#9998;'>";
					echo "<input type='submit' class='delete_button' name='delete_wc' value='&#59177;'>";
				echo "</form>";
		
			$wi++;
			
		}
		
	}
	
	// Add new Writing Code
	echo "<form action='edit_new_agency_wc.php' method='post'>";
		echo "<h2>Add Writing Code</h2>";
		echo "<input type='hidden' value='".$id."' name='agency_id' size=2 READONLY />";
		echo "<section>";
			echo "<h4>Carrier</h4>";
			echo "<select name='carrier_name'>";
				echo "<option value=\'\'>-- Select a carrier --</option>";
	
	
		// Lists Carriers from database
		if($cnum > 0) {
			$ci = 0;
			while ($ci < $cnum) {
				
				$carrier_name = mysql_result($carrier_query, $ci);
						
				echo "<option value='".$carrier_name."'>".$carrier_name."</option>";
			
				$ci++;
				
			}
		}
	
			echo "</select>";
		echo "</section>";
		echo "<section>";
			echo "<h4>Writing Code</h4>";
			echo "<input type='text' class='writing_code' name='agency_wc' />";
		echo "</section>";
		echo "<section>";
			echo "<h4>Contract Level</h4>";
			echo "<input type='text' class='contract_level' name='contract_level' size='2' required>";
		echo "</section>";
		echo "<input type='submit' name='edit' class='edit_button' value='&oplus;'>";
	echo "</form>";

// Closes the MySQL connection
mysql_close();

?>
</div>

</body>
</html>