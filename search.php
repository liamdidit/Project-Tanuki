<head>
    <title></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery-ui-1.8.2.custom.css" /> 
    <link rel="stylesheet" href="css/jquery.ui.theme.css" />
    <link rel="stylesheet" href="css/jquery.ui.datepicker.css" />
    <link rel="stylesheet" href="css/datepicker.css" />

	<script src="js/jquery.ui.core.js"></script>
	<script src="js/jquery.ui.widget.js"></script>
	<script src="js/jquery.ui.datepicker.js"></script>
	<script src="js/autoNumeric.js"></script>
	
	<script src="js/jquery-1.8.2.js"></script> 
	<script src="js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script>
		jQuery(document).ready(function($){
			$('#name_search').autocomplete({source:'dynamic_search.php', minLength:2});
			$('.datepicker').datepicker();
     		$('#comp_base').autoNumeric();    
		});
	</script>
	
</head>
<body>
			
<?php
// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";


mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Reset timezone
date_default_timezone_set('UTC');

				
// Sets minimum query length
$min_length = 3;

if (!empty($_GET['query'])) {
	
	$query = $_GET['query'];
	
	// If query length is >= minimum length ...
	if(strlen($query) >= $min_length) {
			
		// Retrieve Agent information from database
		$result = mysql_query("SELECT DISTINCT (agents.id)as 'Agent ID',
				CONCAT(agents.first_name, ' ', agents.last_name)as 'Agent Name'
			FROM agents
			INNER JOIN writing_codes
				ON agents.agent_wc = writing_codes.agent_wc
			INNER JOIN agencies
				ON writing_codes.agency_wc = agencies.agency_wc
			WHERE writing_codes.agency_wc = ''
				OR agencies.agency_name = 'Unassigned'
			GROUP BY `Agent Name`
			ORDER BY agents.last_name");
		$num = mysql_numrows($result);
		
		
		// Retrieve Policy information from database
		$code_result = mysql_query("SELECT DISTINCT policies.agent_wc FROM policies
		WHERE NOT EXISTS (SELECT writing_codes.agent_wc FROM writing_codes
						WHERE writing_codes.agent_wc = policies.agent_wc)");
		$wc_num = mysql_numrows($code_result);
		
		if(($num > 0) OR ($wc_num > 0)) {
						
			echo "<header>";		
			echo "<h1><h1>&#9888;</h1>";
			echo "<section>";
			
		}
		
		
		// If one or more rows are returned ...
		if($num > 0) {
					
			echo "<h2>Unassigned</h2>";
			
			echo "<aside>";
				
			$i = 0;
			while ($i < $num) {
				
				$agent_id = mysql_result($result,$i,"Agent ID");
				$agent = mysql_result($result,$i,"Agent Name");
				
				echo "<a href='/edit_agent.php?id=".$agent_id."'>".$agent."</a>";
				
				$i++;
				
			}
			
			echo "</aside>";
			
		}
		
		
		// If one or more rows are returned ...
		if($wc_num > 0) {
					
			echo "<h2>Unidentified</h2>";
			
			echo "<aside>";
				
			$wc_i = 0;
			while ($wc_i < $wc_num) {
				
				$agent_wc = mysql_result($code_result,$wc_i,"policies.agent_wc");
				
				echo "<a href='/edit_agent.php?wc=".$agent_wc."'>".$agent_wc."</a>";
				
				$wc_i++;
				
			}
			
			echo "</aside>";
			
		}
		
		if(($num > 0) OR ($wc_num > 0)) {
						
			echo "</section>";
			echo "</header>";
			
		}
		
		// Changes HTML characters to respective ASCII alternatives
		$query = htmlspecialchars($query);
		
		// Prevents SQL injection
		$query = mysql_real_escape_string($query);
		
?>
		
	<div id="page_wrapper">
	
	<div id="left_section">
		<div id="get_started">
			<section>
				<h1>M3 Financial</h1>
				<h2>Commissions Module</h2>
			</section>
		</div>
		<aside id="start_arrow"></aside>
		
		<div id="admin">
			<ul>
				<a href="/">
					<li>
						&#8962;
					</li>
				</a>
				<a href="/new_agent.php">
					<li>
						&#128100;
					</li>
				</a>
				<a href="/new_agency.php">
					<li>
						&#128193;
					</li>
				</a>
				<a href="/new_policy.php">
					<li>
						&#59190;
					</li>
				</a>
				<a href="/import.php">
					<li>
						&#128229;
					</li>
				</a>
				<a href="/admin.php">
					<li>
						&#9881;
					</li>
				</a>
			</ul>
		</div>
	</div>
		
<?php
		
		echo "<div id='search_page'>";
		
		echo "<h1>Results for '".$query."'</h1>";
		echo "<aside id='arrow'></aside>";

//////////////////////////////////////////
////////////////// CARRIER SEARCH FUNCTION
//////////////////////////////////////////
				
		// Searches the database for a matching Carrier
		$carriers_search = mysql_query("SELECT DISTINCT carriers.carrier_name, carriers.id FROM carriers
			WHERE (carriers.carrier_name LIKE '%".$query."%')
			GROUP BY carriers.carrier_name");
		$carriers_num = mysql_numrows($carriers_search);
				
		// If one or more rows are returned ...
		if($carriers_num > 0) {
			
			echo "<h3>&#59392;</h3> <h2>Carriers</h2>";
					
			$i = 0;
			while ($i < $carriers_num) {
				$carrier_name = mysql_result($carriers_search, $i, "carriers.carrier_name");
				$carrier_id = mysql_result($carriers_search, $i, "carriers.id");
					
				echo "<h4><a href=/edit_carrier.php?id=".$carrier_id.">".$carrier_name."</a></h4>";
					
				$i++;
			}
		}

//////////////////////////////////////////
/////////////////// AGENCY SEARCH FUNCTION
//////////////////////////////////////////
				
		// Searches the database for a matching Agency
		$agency_search = mysql_query("SELECT DISTINCT agencies.agency_name, agencies.id FROM agencies
			WHERE (agency_name LIKE '%".$query."%')
			GROUP BY agencies.agency_name");
		$agency_num = mysql_numrows($agency_search);
				
		// If one or more rows are returned ...
		if($agency_num > 0) {
			
			echo "<h3>&#59254;</h3> <h2>Agencies</h2>";
					
			$i = 0;
			while ($i < $agency_num) {
				$agency_name = mysql_result($agency_search, $i, "agencies.agency_name");
				$agency_id = mysql_result($agency_search, $i, "agencies.id");
					
				echo "<h4><a href=/edit_agency.php?id=".$agency_id.">".$agency_name."</a></h4>";
					
				$i++;
			}
		}
		
/////////////////////////////////////////
/////////////////// AGENT SEARCH FUNCTION
/////////////////////////////////////////
		
		// Searches the database for a matching Agent
		$agent_search = mysql_query("SELECT DISTINCT agents.id, (CONCAT(agents.first_name, ' ', agents.last_name))as 'Agent Name' FROM agents
			WHERE (agents.first_name LIKE '%".$query."%')
			OR (agents.last_name LIKE '%".$query."%')
			GROUP BY CONCAT(agents.first_name, ' ', agents.last_name)");
		$agent_num = mysql_numrows($agent_search);
				
		// If one or more rows are returned ...
		if($agent_num > 0) {
			
			echo "<h3>&#128101;</h3> <h2>Agents</h2>";
					
			$i = 0;
			while ($i < $agent_num) {
				$agent_name = mysql_result($agent_search, $i, "Agent Name");
				$agent_id = mysql_result($agent_search, $i, "agents.id");
					
				echo "<h4><a href=/edit_agent.php?id=".$agent_id.">".$agent_name."</a></h4>";
					
				$i++;
			}
		}
		
////////////////////////////////////////////////
/////////////////// WRITING CODE SEARCH FUNCTION
////////////////////////////////////////////////
		
		// Searches the database for a matching Writing Code
		$code_search = mysql_query("SELECT writing_codes.agent_wc, (CONCAT(agents.first_name, ' ', agents.last_name))as 'Agent Name', agents.id FROM writing_codes
			INNER JOIN agents
			ON writing_codes.agent_wc = agents.agent_wc
			WHERE (writing_codes.agent_wc = '".$query."')
			GROUP BY writing_codes.agent_wc");
		$code_num = mysql_numrows($code_search);
				
		// If one or more rows are returned ...
		if($code_num > 0) {
			
			echo "<h3>&#128179;</h3> <h2>Writing Codes</h2>";
					
			$i = 0;
			while ($i < $code_num) {
				$agent_name = mysql_result($code_search, $i, "Agent Name");
				$agent_id = mysql_result($code_search, $i, "agents.id");
				$agent_wc = mysql_result($code_search, $i, "writing_codes.agent_wc");
					
				echo "<h4><a href=/edit_agent.php?id=".$agent_id.">".$agent_name." (".$agent_wc.")</a></h4>";
					
				$i++;
			}
		}
		
/////////////////////////////////////////////////
/////////////////// POLICY NUMBER SEARCH FUNCTION
/////////////////////////////////////////////////
		
		// Searches the database for a matching Policy Number
		$policy_search = mysql_query("SELECT policies.id, policies.policy_number, policies.trans_date, CONCAT(policies.client_first, ' ', policies.client_last)as 'Client Name' FROM policies
			WHERE (policies.policy_number = '".$query."')");
		$policy_num = mysql_numrows($policy_search);
				
		// If one or more rows are returned ...
		if($policy_num > 0) {
			
			echo "<h3>&#59190;</h3> <h2>Policies</h2>";
					
			$i = 0;
			while ($i < $policy_num) {
				$policy_number = mysql_result($policy_search, $i, "policies.policy_number");
				$policy_id = mysql_result($policy_search, $i, "policies.id");
				$policy_date = mysql_result($policy_search, $i, "policies.trans_date");
				$policy_client = mysql_result($policy_search, $i, "Client Name");
					
				echo "<h4><a href=/edit_policy.php?id=".$policy_id.">".$policy_number." - ".$policy_client." (".$policy_date.")</a></h4>";
					
				$i++;
			}
		}
		
		// Returns a message if nothing is found
		if(($agency_num <= 0) AND ($agent_num <= 0) AND ($code_num <= 0) AND ($policy_num <= 0) AND ($carriers_num <= 0)) {
			echo "We could not find what you were looking for.";
		}
			
	echo "</div>";
		
	}
	
	else {
		echo "Minimum query length is 3.";
	}
	
}


///////////////////////////////////
/////// COMMISSIONS SEARCH FUNCTION
///////////////////////////////////

elseif (!empty($_GET['start_date_input']) AND !empty($_GET['end_date_input'])) {
	
	$start_date = $_GET['start_date_input'];
	$end_date = $_GET['end_date_input'];
	$agency_query = $_GET['agency_input'];
	$agent_query = $_GET['agent_input'];
	$carrier_query = $_GET['carrier_input'];
	
	// Date formatter
	$date_format = 'F d, Y';

	$format_start_date = strtotime($start_date);
	$format_start_date = date($date_format, $format_start_date);
	
	$format_end_date = strtotime($end_date);
	$format_end_date = date($date_format, $format_end_date);
	
	if(!empty($_GET['agency_input'])) {
		
		// Searches the database for a matching Agency
		$agency_search = mysql_query("SELECT DISTINCT agencies.agency_name, agencies.id FROM agencies
			WHERE agencies.agency_name LIKE '%$agency_query%'
			LIMIT 1");
		$agency_num = mysql_numrows($agency_search);
		
		$agency_name = mysql_result($agency_search,0,"agencies.agency_name");
		$agency_id = mysql_result($agency_search,0,"agencies.id");
		
	} else {
		$agency_name = '';
	}
	
	// Searches the database for a matching Agent
	$agent_search = mysql_query("SELECT DISTINCT agents.first_name, agents.last_name, agents.id FROM agents
		WHERE agents.last_name = '$agent_query'
		LIMIT 1");
	$agent_num = mysql_numrows($agent_search);

	$agent_first = mysql_result($agent_search,0,"agents.first_name");
	$agent_last = mysql_result($agent_search,0,"agents.last_name");
	$agent_id = mysql_result($agent_search,0,"agents.id");
	
	if(($agency_name == 'Diversified Brokerage Services') OR $agency_name == 'Woodbury Financial Services') {
		$carriers_query = mysql_query("SELECT DISTINCT (carriers.carrier_name)as 'Carrier'
			FROM policies
		INNER JOIN carriers
			ON policies.carrier_id = carriers.id
		INNER JOIN writing_codes
			ON writing_codes.agent_wc = policies.agent_wc
		INNER JOIN agencies
			ON agencies.agency_wc = writing_codes.agency_wc
		INNER JOIN agents
			ON agents.agent_wc = writing_codes.agent_wc
		WHERE policies.trans_date >= '$start_date'
			AND policies.trans_date <= '$end_date'
			AND carriers.carrier_name LIKE '%$carrier_query%'
			AND ((agencies.agency_name = 'Diversified Brokerage Services') OR (agencies.agency_name = 'Woodbury Financial Services'))
			AND agents.first_name LIKE '%$agent_first%'
			AND agents.last_name LIKE '%$agent_last%'");
		$carriers_query_rows = mysql_numrows($carriers_query);
	} else {
		$carriers_query = mysql_query("SELECT DISTINCT (carriers.carrier_name)as 'Carrier'
			FROM policies
		INNER JOIN carriers
			ON policies.carrier_id = carriers.id
		INNER JOIN writing_codes
			ON writing_codes.agent_wc = policies.agent_wc
		INNER JOIN agencies
			ON agencies.agency_wc = writing_codes.agency_wc
		INNER JOIN agents
			ON agents.agent_wc = writing_codes.agent_wc
		WHERE policies.trans_date >= '$start_date'
			AND policies.trans_date <= '$end_date'
			AND carriers.carrier_name LIKE '%$carrier_query%'
			AND agencies.agency_name LIKE '%$agency_name%'
			AND agents.first_name LIKE '%$agent_first%'
			AND agents.last_name LIKE '%$agent_last%'");
		$carriers_query_rows = mysql_numrows($carriers_query);
	}
			
	// Loop through the returned Agent Writing Codes to present policy data
	if($carriers_query_rows > 0) {
		
		echo "<section class='report_wrapper'>";
			echo "<section class='report_header'>";
	
				if(!empty($_GET['agent_input']) AND !empty($_GET['agency_input'])) {
					echo "<h1>Earnings Report</h1>";
					echo "<h2>".$agent_first." ".$agent_last."</h2>";
					echo "<h2>".$agency_name."</h2>";
				} elseif(!empty($_GET['agent_input'])) {
					echo "<h1>Earnings Report</h1>";
					echo "<h2>".$agent_first." ".$agent_last."</h2>";
				} elseif(!empty($_GET['agency_input'])) {
					echo "<h1>Earnings Report</h1>";
					echo "<h2>".$agency_name."</h2>";
				} else {
					echo "<h1>Earnings Report</h1>";
				}
		
				echo "<h2>".$format_start_date." - ".$format_end_date."</h2>";
			echo "</section>";
			echo "<aside id='links'>";
					echo "<h3><a href='/'>&#8962;</a></h3>";
					
					if(!empty($_GET['agency_input'])) {
						echo "<h3><a href='/statement.php?da=".$start_date."&db=".$end_date."&agency=".$agency_name."'>&#128200;</a></h3>";
					} else {
						echo "<h3><a href='/statement.php?da=".$start_date."&db=".$end_date."'>&#128200;</a></h3>";
					}
			
			echo "</aside>";
		echo "</section>";
		
		
		$z = 0;
		while($z < $carriers_query_rows) {
			
			$carrier = mysql_result($carriers_query,$z,"Carrier");
			
			if(($agency_name == 'Diversified Brokerage Services') OR $agency_name == 'Woodbury Financial Services') {
				$agents_query = mysql_query("SELECT DISTINCT policies.agent_wc
					FROM policies
				INNER JOIN writing_codes
					ON writing_codes.agent_wc = policies.agent_wc
				INNER JOIN agencies
					ON agencies.agency_wc = writing_codes.agency_wc
				INNER JOIN carriers
					ON policies.carrier_id = carriers.id
				WHERE policies.trans_date >= '$start_date'
					AND policies.trans_date <= '$end_date'
					AND ((agencies.agency_name = 'Diversified Brokerage Services') OR (agencies.agency_name = 'Woodbury Financial Services'))
					AND carriers.carrier_name = '$carrier'");
				$agents_query_rows = mysql_numrows($agents_query);
			} else {
				$agents_query = mysql_query("SELECT DISTINCT policies.agent_wc
					FROM policies
				INNER JOIN writing_codes
					ON writing_codes.agent_wc = policies.agent_wc
				INNER JOIN agencies
					ON agencies.agency_wc = writing_codes.agency_wc
				INNER JOIN carriers
					ON policies.carrier_id = carriers.id
				WHERE policies.trans_date >= '$start_date'
					AND policies.trans_date <= '$end_date'
					AND agencies.agency_name LIKE '%$agency_name%'
					AND carriers.carrier_name = '$carrier'");
				$agents_query_rows = mysql_numrows($agents_query);
			}
			
			echo "<div id='statement_summary' style='float:left; width:100%;'>";
				echo "<table>";
					echo "<thead>";
						echo "<tr>";
							echo "<th colspan='3'>".$carrier."</th>";
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
						echo "<tr class='headers'>";
							echo "<td>Policy Number</td>";
							echo "<td>Carrier</td>";
							echo "<td>Product</td>";
							echo "<td>Annuitant</td>";
							echo "<td>Issue Age</td>";
							echo "<td>Premium</td>";
							echo "<td>Transaction Date</td>";
							echo "<td>Inforce Date</td>";
							echo "<td>M3 Earnings</td>";
						echo "</tr>";
					
			$i = 0;
			$grand_total = 0;
			while($i < $agents_query_rows) {
				
				$agent_wc = mysql_result($agents_query,$i);
				
				$result = mysql_query("SELECT (policies.id)as 'Policy ID',
					(agents.id)as 'Agent ID',
					(policies.policy_number)as 'Policy Number',
					CONCAT(agents.first_name, ' ', agents.last_name)as 'Agent',
					(policies.agent_wc)as 'Agent WC',
					(agencies.agency_name)as 'Agency',
					CONCAT(policies.client_first, ' ', policies.client_last)as 'Client Name',
					(policies.client_age)as 'Client Age',
					(carriers.carrier_name)as 'Carrier',
					(products.product_name)as 'Product',
					(policies.product_name)as 'Product ID',
					(policies.comp_base)as 'Value',
					(policies.trans_date)as 'Transaction Date',
					(writing_codes.contract_level)as 'Contract Level',
					(agencies.mv_group)as 'MV Group',
					(policies.reported_amt)as 'Reported Comp',
					(policies.effect_date)as 'Inforce Date',
					(commissions.effective_date)as 'Rate Date',
					(agencies.support_level)as 'Support Level',
					(policies.charge_back)as 'Chargeback',
					(policies.state)as 'State',
					(policies.option)as 'Option',
					(carriers.id)as 'Carrier ID'
					
					FROM policies
					
					INNER JOIN agents
						ON policies.agent_wc = agents.agent_wc
					INNER JOIN writing_codes
						ON agents.agent_wc = writing_codes.agent_wc
					INNER JOIN agencies
						ON writing_codes.agency_wc = agencies.agency_wc
					INNER JOIN gross_payout
						ON agencies.support_level = gross_payout.id
					INNER JOIN carriers
						ON policies.carrier_id = carriers.id
					INNER JOIN products
						ON policies.product_name = products.id
					INNER JOIN commissions
						ON policies.product_name = commissions.product_id
					
					WHERE agencies.support_level = commissions.support_id
					AND carriers.carrier_name = '$carrier'
					AND agents.agent_wc = '$agent_wc'
					AND policies.trans_date >= '$start_date'
					AND policies.trans_date <= '$end_date'
					AND policies.client_age >= commissions.min_age
					AND policies.client_age <= commissions.max_age
					AND commissions.carrier_id = policies.carrier_id
					AND commissions.effective_date <= policies.effect_date
					
					GROUP BY policies.id
					ORDER BY policies.trans_date ASC, policies.client_last ASC");
				$result_rows = mysql_numrows($result);
				
				$agent_id = mysql_result($result,0,'Agent ID');
				$agent_name = mysql_result($result,0,'Agent');
				$agency = mysql_result($result,0,"Agency");
			
				echo "<tr class='agent'>";
					echo "<td colspan='9'>";
						echo "<a href=/edit_agent.php?id=".$agent_id." target='_blank'>".$agent_name."</a> - ".$agent_wc;
						echo "<br />";
						echo "<i><a href='/statement.php?da=".$start_date."&db=".$end_date."&agency=".$agency."' target='_blank'>".$agency."</a></i>";
					echo "</td>";
				echo "</tr>";
				
				$p = 0;
				$m3_total = 0;
				
				while($p < $result_rows) {
					
					$carrier_id = mysql_result($result,$p,"Carrier ID");				// Unique Carrier ID
					$policy_id = mysql_result($result,$p,"Policy ID");					// Unique Policy ID
					$agent_id = mysql_result($result,$p,"Agent ID"); 			    	// Unique Agent ID
					$policy_number = mysql_result($result,$p,"Policy Number");			// Policy Number
					$client_name = mysql_result($result,$p,"Client Name");				// Client's full name
					$client_age = mysql_result($result,$p,"Client Age");				// Client's age (Issue age)
					$carrier = mysql_result($result,$p,"Carrier");						// Carrier name
					$product = mysql_result($result,$p,"Product");						// Product name
					$product_id = mysql_result($result,$p,"Product ID");				// Unique Product ID
					$value = mysql_result($result,$p,"Value");							// Total Premium paid
					$trans_date = mysql_result($result,$p,"Transaction Date");			// Transaction date
					$contract_level = mysql_result($result,$p,"Contract Level");		// Level contracted with Carrier
					$mv_group = mysql_result($result,$p,"MV Group");					// MV Group (True / False)
					$reported_comp = mysql_result($result,$p,"Reported Comp");			// Our earnings reported by Carrier
					$inforce_date = mysql_result($result,$p,"Inforce Date");			// Policy inforce date (Effective rate date)
					$rate_date = mysql_result($result,$p,"Rate Date");					// Effective date for commission rate
					$support_id = mysql_result($result,$p,"Support Level");				// Unique Support Level ID
					$chargeback = mysql_result($result,$p,"Chargeback");				// Charge back (True / False)
					$agent_wc = mysql_result($result,$p,"Agent WC");					// Agent's Writing Code
					$state = mysql_result($result,$p,"State");							// Writing state
					$option = mysql_result($result,$p,"Option");
					
					// Retrieve Contract Comp
					if((($carrier_id == '32') AND ($agency_name == 'Highland Capital Brokerage') AND ($inforce_date >= '2013-07-01'))) {
						$contract_comp_query = mysql_query("SELECT commissions.rate
																FROM commissions
															WHERE (commissions.state = '$state' OR commissions.state = '')
																AND commissions.effective_date <= '$inforce_date'
																AND commissions.max_age >= '$client_age'
																AND commissions.min_age <= '$client_age'
																AND commissions.product_id = '$product_id'
																AND commissions.contract_level = '90'
																AND commissions.carrier_id = '$carrier_id'
																AND (commissions.option = '$option' OR commissions.option = '')
														    ORDER BY commissions.effective_date DESC, commissions.state DESC, commissions.option DESC
															LIMIT 1");
						$contract_rate = mysql_result($contract_comp_query,0);
					} else {
						$contract_comp_query = mysql_query("SELECT commissions.rate
																FROM commissions
															WHERE (commissions.state = '$state' OR commissions.state = '')
																AND commissions.effective_date <= '$inforce_date'
																AND commissions.max_age >= '$client_age'
																AND commissions.min_age <= '$client_age'
																AND commissions.product_id = '$product_id'
																AND commissions.contract_level = '$contract_level'
																AND commissions.carrier_id = '$carrier_id'
																AND (commissions.option = '$option' OR commissions.option = '')
														    ORDER BY commissions.effective_date DESC, commissions.state DESC, commissions.option DESC
															LIMIT 1");
						$contract_rate = mysql_result($contract_comp_query,0);
					}
					
					// Retrieve Gross Comp
					if($carrier_id == '13') {
						$gross_comp_query = mysql_query("SELECT commissions.rate
															FROM commissions
														WHERE (commissions.state = '$state' OR commissions.state = '')
															AND commissions.effective_date <= '$inforce_date'
															AND commissions.max_age >= '$client_age'
															AND commissions.min_age <= '$client_age'
															AND commissions.product_id = '$product_id'
															AND commissions.contract_level = 'SMB'
															AND commissions.carrier_id = '$carrier_id'
															AND (commissions.option = '$option' OR commissions.option = '')
													    ORDER BY commissions.effective_date DESC, commissions.state DESC, commissions.option DESC
														LIMIT 1");
						$gross_comp = mysql_result($gross_comp_query,0);
					} else {
						$gross_comp_query = mysql_query("SELECT commissions.rate
															FROM commissions
														WHERE (commissions.state = '$state' OR commissions.state = '')
															AND commissions.effective_date <= '$inforce_date'
															AND commissions.max_age >= '$client_age'
															AND commissions.min_age <= '$client_age'
															AND commissions.product_id = '$product_id'
															AND commissions.support_id = '8'
															AND commissions.carrier_id = '$carrier_id'
															AND (commissions.option = '$option' OR commissions.option = '')
													    ORDER BY commissions.effective_date DESC, commissions.state DESC, commissions.option DESC
														LIMIT 1");
						$gross_comp = mysql_result($gross_comp_query,0);
					}
				
					// Retrieve Street Comp
					$street_comp_query = mysql_query("SELECT commissions.rate
														FROM commissions
													WHERE (commissions.state = '$state' OR commissions.state = '')
														AND commissions.effective_date <= '$inforce_date'
														AND commissions.max_age >= '$client_age'
														AND commissions.min_age <= '$client_age'
														AND commissions.product_id = '$product_id'
														AND commissions.support_id = '7'
														AND commissions.carrier_id = '$carrier_id'
														AND (commissions.option = '$option' OR commissions.option = '')
												    ORDER BY commissions.effective_date DESC, commissions.state DESC, commissions.option DESC
													LIMIT 1");
					$street_comp = mysql_result($street_comp_query,0);
					
					if(((strtotime($trans_date) - strtotime($inforce_date)) / 31536000) >= 1) {
						$gross_comp = ($gross_comp / 2);
						$contract_rate = ($contract_rate / 2);
						$street_comp = ($street_comp / 2);
					}

					$m3_comp = (($value * ($gross_comp - $contract_rate)) / 100);
					$m3_total += $m3_comp;
				
					// Format the dates for end user
					$format_trans_date = strtotime($trans_date);
					$format_trans_date = date($date_format, $format_trans_date);
			
					$format_inforce_date = strtotime($inforce_date);
					$format_inforce_date = date($date_format, $format_inforce_date);
		
						echo "<td><a href=/edit_policy.php?id=".$policy_id." target='_blank'>".$policy_number."</a></td>";
						echo "<td>".$carrier."</td>";
						echo "<td>".$product."</td>";
						echo "<td>".$client_name."</td>";
						echo "<td>".$client_age."</td>";
						echo "<td>$".number_format($value, 2, '.', ',')."</td>";
						echo "<td>".$format_trans_date."</td>";
						echo "<td>".$format_inforce_date."</td>";
						
						if((number_format($m3_comp, 2, '.', ',')) != (number_format($reported_comp, 2, '.', ',')) AND $chargeback == 0) {
							if((number_format($m3_comp, 2, '.', ',') == number_format(($reported_comp + 0.01), 2, '.', ',')) XOR (number_format($m3_comp, 2, '.', ',') == number_format(($reported_comp - 0.01), 2, '.', ','))) {
								echo "<td>$".number_format($m3_comp, 2, '.', ',')." (".number_format((($gross_comp - $contract_rate)), 2, '.', ',').")</td>";
							} else {
								echo "<td><font style='color:red; font-style:italic;'>$".number_format($m3_comp, 2, '.', ',')." (".number_format((($gross_comp - $contract_rate)), 3, '.', ',')."%)</font><br />$".number_format($reported_comp, 2, '.', ',')." (".number_format((($reported_comp * 100) / $value), 3, '.', ',')."%)</td>";
							}
						} elseif($chargeback == 1) {
							echo "<td>$".number_format($reported_comp, 2, '.', ',')." (".number_format((($reported_comp * 100) / $value), 3, '.', ',')."%)</td>";
						} else {
							echo "<td>$".number_format($m3_comp, 2, '.', ',')." (".number_format((($gross_comp - $contract_rate)), 3, '.', ',')."%)</td>";
						}
					
					echo "</tr>";
						
					$p++;
				
				}

				$grand_total += $m3_total;
				
				echo "<tr class='subtotal'>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td>$".number_format($m3_total, 2, '.', ',')."</td>";
				echo "</tr>";

				$i++;
				
			}
					
			$z++;
			
		}
		
	}
}
?>

				</tbody>
			</table>
		</center>
	</div>
</div>

<?php

// Closes MySQL connection
mysql_close();

?>

</body>