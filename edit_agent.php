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
			<h1>Edit Agent</h1>
		</section>
		<aside id="arrow_border"></aside>
		<aside id="arrow"></aside>
	</div>
	<section id="content_wrapper">
	
<?php

// Dynamically retrieve entry for editing
if(isset($_GET['id'])) {
	
	$id = $_GET['id'];
	
	// Searches the table for the Agent based on the ID passed through the URL
	$id_result = mysql_query("SELECT agents.id, agents.first_name, agents.last_name FROM agents
		WHERE (agents.id = '$id')") or die('No results were found ...');
	$idnum = mysql_numrows($id_result);
	
	// Agent variables
	$agent_id = mysql_result($result,0,"agents.id");
	$agent_first = mysql_result($id_result,0,"agents.first_name");
	$agent_last = mysql_result($id_result,0,"agents.last_name");
	
	// Returns all of the Agent's Writing Codes
	$writing_code_query = mysql_query("SELECT agents.id, writing_codes.agent_wc, writing_codes.contract_level, writing_codes.agency_wc, writing_codes.carrier_id, agencies.agency_name, carriers.carrier_name FROM writing_codes
		INNER JOIN agents
			ON writing_codes.agent_wc = agents.agent_wc
		INNER JOIN agencies
			ON writing_codes.agency_wc = agencies.agency_wc
		INNER JOIN carriers
			ON writing_codes.carrier_id = carriers.id
		WHERE (agents.first_name = '$agent_first') AND (agents.last_name = '$agent_last')") or die('No results were found ...');
	$wnum = mysql_numrows($writing_code_query);

	echo "<form action='edit_agent_name.php' method='post'>";
		echo "<h2>Agent Information</h2>";
		echo "<input type='hidden' value='".$id."' name='agent_id' size='2' READONLY>";
		echo "<section>";
			echo "<h4>First Name</h4>";
			echo "<input type='text' value='".$agent_first."' name='agent_first_name' required>";
		echo "</section>";
		echo "<section>";
			echo "<h4>Last Name</h4>";
			echo "<input type='text' value='".$agent_last."' name='agent_last_name' required>";
		echo "</section>";
		echo "<input type='submit' name='edit' class='edit_button' value='&#9998;'>";
		echo "<input type='submit' name='delete_agent' class='delete_button' value='&#59177;'>";
	echo "</form>";
	
	// Set Writing Code variables for the information returned from the query
	if($wnum > 0){
		
		echo "<form>";
			echo "<h2>Writing Codes</h2>";
		echo "</form>";
		echo "<table id='agent_table'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Carrier</th>
							<th>Agency</th>
							<th>Writing Code</th>
							<th>Contract Level (Paid)</th>
							<th>Edit</th>
							<th>Delete</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
		
		$wi = 0;
		while ($wi < $wnum){
	
			$agent_wc = mysql_result($writing_code_query, $wi, "writing_codes.agent_wc");
			$agency = mysql_result($writing_code_query, $wi, "agencies.agency_name");
			$carrier_name = mysql_result($writing_code_query, $wi, "carriers.carrier_name");
			$contract_level = mysql_result($writing_code_query, $wi, "writing_codes.contract_level");
			$wc_id = mysql_result($writing_code_query, $wi, "agents.id");
			
			echo "<form action='edit_agent_wc.php' method='post'>";
				echo "<input type='hidden' value='".$wc_id."' name='agent_id' size='2' READONLY>";
				echo "<input type='hidden' value='".$carrier_name."' name='carrier_name' READONLY>";
				echo "<tr>";
					echo "<td>".$carrier_name."</td>";
					echo "<td>";
						echo "<select name='agency_name' class='agency_box'>";
							echo "<option value=\'\'></option>";
						
							// Lists Agencies from database
							if($anum > 0) {
								$a_i = 0;
								while ($a_i < $anum) {
									
									$agency_name = mysql_result($agency_query, $a_i);
									
									if(($agency_name != 'UNASSIGNED') AND ($agency_name != 'No Agency Assigned')) {
										if($agency_name == $agency){
											echo "<option value='".$agency_name."' selected='selected'>".$agency_name."</option>";
										} else {
											echo "<option value='".$agency_name."'>".$agency_name."</option>";
										}
									}
									
									$a_i++;
									
								}
							}
							echo "<option value ='UNASSIGNED'>I don't know ...</option>";
						echo "</select>";
					echo "</td>";
					echo "<td><input type='text' class='writing_code' name='agent_wc' value='".$agent_wc."'></td>";
					echo "<td><input type='text' class='contract_level' name='contract_level' value='".$contract_level."' required></td>";
					echo "<td><input type='submit' name='edit' class='edit_button' value='&#9998;'></td>";
					echo "<td><input type='submit' class='delete_button' name='delete_wc' value='&#59177;'></td>";
				echo "</tr>";
			echo "</form>";
		
			$wi++;
			
		}

			echo "</tbody>";
		echo "</table>";
		
	}
	
	// Add new Writing Code
	echo "<form action='edit_new_agent_wc.php' method='post'>";
		echo "<h2>Add Writing Code</h2>";
		echo "<input type='hidden' value='".$id."' name='agent_id' size=2 READONLY />";
		echo "<section>";
			echo "<h4>Carrier Name</h4>";
			echo "<select name='carrier_name'>";
				echo "<option value=\'\'></option>";
	
	
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
			echo "<h4>Agency Name</h4>";
			echo "<select name='agency_name'>";
				echo "<option value=\'\'></option>";
	
	
		// Lists Agencies from database
		if($anum > 0) {
			$a_i = 0;
			while ($a_i < $anum) {
				
				$agency_name = mysql_result($agency_query, $a_i);
				
				if(($agency_name != 'UNASSIGNED') AND ($agency_name != 'No Agency Assigned')) {
					echo "<option value='".$agency_name."'>".$agency_name."</option>";
				}
				
				$a_i++;
				
			}
		}
		
				echo "<option value ='UNASSIGNED'>I don't know ...</option>";
			echo "</select>";
		echo "</section>";
		echo "<section>";
			echo "<h4>Writing Code</h4>";
			echo "<input type='text' class='writing_code' name='agent_wc' />";
		echo "</section>";
		echo "<section>";
			echo "<h4>Contract Level</h4>";
			echo "<input type='text' class='contract_level' name='contract_level' size='2' required>";
		echo "</section>";
		echo "<input type='submit' name='edit' class='edit_button' value='&oplus;'>";
	echo "</form>";
	
}


if(isset($_GET['wc'])) {
	
	$agent_wc = $_GET['wc'];
	
	// Set up content for page
	echo "<form action='edit_agent_name.php' method='post'>";
		echo "<h2>Agent Information</h2>";
		echo "<section>";
			echo "<h4>First Name</h4>";
			echo "<input type='text' name='agent_first_name' required>";
		echo "</section>";
		echo "<section>";
			echo "<h4>Last Name</h4>";
			echo "<input type='text' name='agent_last_name' required>";
		echo "</section>";
		echo "<section>";
			echo "<h4>Writing Code</h4>";
			echo "<input type='text' class='writing_code' value='$agent_wc' name='agent_wc' READONLY>";
		echo "</section><br /><br /><br />";
	
	// Add new Writing Code
		echo "<section>";
			echo "<h4>Carrier Name</h4>";
			echo "<input type='hidden' value='".$id."' name='agent_id' size=2 READONLY />";
			echo "<select name='carrier_name'>";
				echo "<option value=\'\'></option>";
			
		
		// Lists Carriers from database
		if($cnum > 0){
			$ci = 0;
			while ($ci < $cnum){
				$carrier_name = mysql_result($carrier_query, $ci);
						
				echo "<option value='".$carrier_name."'>".$carrier_name."</option>";
			
				$ci++;
			}
		};
		
			echo "</select>";
		echo "</section>";
		echo "<section>";
			echo "<h4>Agency Name</h4>";
			echo "<select name='agency_name'>";
				echo "<option value=\'\'></option>";
		
		
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
			echo "</select>";
		echo "</section>";
		echo "<input type='submit' name='add' class='edit_button' value='&oplus;'>";
	echo "</form>";
	
}


	if(isset($_GET['id'])) {
		
	// Searches the database for all related Policies
			$result = mysql_query("SELECT (policies.id)as 'Policy ID',
					(agents.id)as 'Agent ID',
					(policies.policy_number)as 'Policy Number',
					CONCAT(agents.first_name,' ', agents.last_name)as 'Agent',
					CONCAT(policies.client_first, ' ', policies.client_last)as 'Client Name',
					(policies.client_age)as 'Client Age',
					(carriers.carrier_name)as 'Carrier',
					(products.product_name)as 'Product',
					(policies.comp_base)as 'Value',
					DATE_FORMAT(policies.trans_date, '%M %d, %Y')as 'Transaction Date'
				FROM policies
				INNER JOIN agents
		    		ON policies.agent_wc = agents.agent_wc
				INNER JOIN writing_codes
		    		ON agents.agent_wc = writing_codes.agent_wc
				INNER JOIN agencies
		    		ON writing_codes.agency_wc = agencies.agency_wc
				INNER JOIN carriers
				    ON policies.carrier_id = carriers.id
				INNER JOIN commissions
				    ON policies.product_name = commissions.product_id
				INNER JOIN products
					ON policies.product_name = products.id
				WHERE agents.last_name = '$agent_last'
					AND agents.first_name = '$agent_first'
		        	AND policies.client_age >= commissions.min_age
		        	AND policies.client_age <= commissions.max_age
				GROUP BY policies.id ASC
				ORDER BY policies.policy_number ASC, policies.trans_date ASC") or die("<h1>Sorry!</h1> There was an error creating the report. Please try again. If the issue persists, contact your IT administrator.");
			$num = mysql_numrows($result);
		
		
		if($num > 0) {
						
			echo "<form>";
				echo "<h2>Policies</h2>";
			echo "</form>";		
				
			echo "<table id='agent_table'>";
				echo "<thead>";
					echo "<tr>";
						echo "<th>Policy Number</th>
								<th>Transaction Date</th>
								<th>Client Name</th>
								<th>Issue Age</th>
								<th>Carrier</th>
								<th>Product</th>
								<th>Premium</th>";
					echo "</tr>";
				echo "</thead>";
			echo "<tbody>";
		
			$i = 0;
			while ($i < $num) {
				$policy_id = mysql_result($result,$i,"Policy ID");
				$agent_id = mysql_result($result,$i,"Agent ID");
				$policy_number = mysql_result($result,$i,"Policy Number");
				$agent_name = mysql_result($result,$i,"Agent");
				$client_name = mysql_result($result,$i,"Client Name");
				$client_age = mysql_result($result,$i,"Client Age");
				$carrier = mysql_result($result,$i,"Carrier");
				$product = mysql_result($result,$i,"Product");
				$premium = mysql_result($result,$i,"Value");
				$policy_date = mysql_result($result,$i,"Transaction Date");
				$add_contr = mysql_result($result,$i,"Additional Contribution");

				echo "<tr>";
					echo "<td><a href='/edit_policy.php?id=".$policy_id."'>".$policy_number."</a></td>";
					echo "<td>".$policy_date."</td>";
					echo "<td>".$client_name."</td>";
					echo "<td>".$client_age."</td>";
					echo "<td>".$carrier."</td>";
					echo "<td>".$product."</td>";
					echo "<td>$".(number_format($premium, 2, '.', ','))."</td>";
				echo "</tr>";
					
				$i++;
				
			}
	
			echo "</tbody>";
			echo "</table>";
	
		}

	}

	if(isset($_GET['wc'])) {
		
		// Searches the database for all related Policies
		$result = mysql_query("SELECT (policies.id)as 'Policy ID',
				(policies.policy_number)as 'Policy Number',
				CONCAT(policies.client_first, ' ', policies.client_last)as 'Client Name',
				(policies.client_age)as 'Client Age',
				(carriers.carrier_name)as 'Carrier',
				(products.product_name)as 'Product',
				(policies.comp_base)as 'Value',
				DATE_FORMAT(policies.trans_date, '%M %d, %Y')as 'Transaction Date'
			FROM policies
			INNER JOIN carriers
			    ON policies.carrier_id = carriers.id
			INNER JOIN products
				ON policies.product_name = products.id
			WHERE policies.agent_wc = '$agent_wc'
			GROUP BY policies.id ASC
			ORDER BY policies.trans_date") or die("<h1>Sorry!</h1> There was an error creating the report. Please try again. If the issue persists, contact your IT administrator.");
		$num = mysql_numrows($result);
	
	
		if($num > 0) {
			
			echo "<form>";
				echo "<h2>Policies</h2>";
			echo "</form>";	
		
			echo "<table id='agent_table'>";
				echo "<thead>";
					echo "<tr>";
						echo "<th>Policy Number</th>
								<th>Transaction Date</th>
								<th>Client Name</th>
								<th>Issue Age</th>
								<th>Carrier</th>
								<th>Product</th>
								<th>Premium</th>";
					echo "</tr>";
				echo "</thead>";
			echo "<tbody>";
		
			$i = 0;
			while ($i < $num) {
				$policy_id = mysql_result($result,$i,"Policy ID");
				$agent_id = mysql_result($result,$i,"Agent ID");
				$policy_number = mysql_result($result,$i,"Policy Number");
				$agent_name = mysql_result($result,$i,"Agent");
				$client_name = mysql_result($result,$i,"Client Name");
				$client_age = mysql_result($result,$i,"Client Age");
				$carrier = mysql_result($result,$i,"Carrier");
				$product = mysql_result($result,$i,"Product");
				$premium = mysql_result($result,$i,"Value");
				$policy_date = mysql_result($result,$i,"Transaction Date");
				$add_contr = mysql_result($result,$i,"Additional Contribution");

				echo "<tr>";
					echo "<td><a href='/edit_policy.php?id=".$policy_id."'>".$policy_number."</a></td>";
					echo "<td>".$policy_date."</td>";
					echo "<td>".$client_name."</td>";
					echo "<td>".$client_age."</td>";
					echo "<td>".$carrier."</td>";
					echo "<td>".$product."</td>";
					echo "<td>$".(number_format($premium, 2, '.', ','))."</td>";
				echo "</tr>";
					
				$i++;
				
			}
	
			echo "</tbody>";
			echo "</table>";
		
		}

	}

// Closes MySQL connection
mysql_close();
		
?>

		</section>
		</div>
	</div>

</body>
</html>