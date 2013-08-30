<head>
	
	<link rel="stylesheet" href="/css/style.css" type="text/css" />
	
</head>

<body>
	
<?php


// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";


mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die("Unable to select database");


// Retrieve report variables
$start_date = $_GET['da'];
$end_date = $_GET['db'];


// Date formatter
$date_format = 'F d, Y';


// Retrieve Agency query
$set_agency = $_GET['agency'];


?>

<div id="statement_wrapper">
	<div id='statement_title'>
		<h1>M3 Financial, Inc.</h1>
		<p>
			180 Glastonbury Blvd. Suite 105<br />
			Glastonbury, CT 06033<br />
			800.308.5555<br />
			<a href="www.m3fin.com">www.m3fin.com</a>
		</p>
	</div>
	
	<div id='statement_head'>
		<h2>Commissions Statement</h2>
		<p>Prepared for
		
<?php

			echo $set_agency;
			
?>	
			
		</p>
		<p> 
			
<?php

			$format_start_date = strtotime($start_date);
			$format_start_date = date($date_format, $format_start_date);
			
			$format_end_date = strtotime($end_date);
			$format_end_date = date($date_format, $format_end_date);
			
			echo $format_start_date." through ".$format_end_date;
			
?>

		</p>
	</div>
	<form id="print_button">
		<input type="button" value="Print Page" onClick="window.print()" />
	</form>
	<div id='statement_summary'>
		<center>

<?php


// Retrieve Policy information from database

if(($set_agency == 'Diversified Brokerage Services') OR $set_agency == 'Woodbury Financial Services') {
	$carriers_query = mysql_query("SELECT DISTINCT (carriers.carrier_name)as 'Carrier'
		FROM policies
	INNER JOIN carriers
		ON policies.carrier_id = carriers.id
	INNER JOIN writing_codes
		ON writing_codes.agent_wc = policies.agent_wc
	INNER JOIN agencies
		ON agencies.agency_wc = writing_codes.agency_wc
	WHERE policies.trans_date >= '$start_date'
		AND policies.trans_date <= '$end_date'
		AND ((agencies.agency_name = 'Diversified Brokerage Services') OR (agencies.agency_name = 'Woodbury Financial Services'))");
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
	WHERE policies.trans_date >= '$start_date'
		AND policies.trans_date <= '$end_date'
		AND agencies.agency_name = '$set_agency'");
	$carriers_query_rows = mysql_numrows($carriers_query);
}


// Loop through the returned Agent Writing Codes to present policy data
if($carriers_query_rows > 0) {
	
	$z = 0;
	while($z < $carriers_query_rows) {
			
	$carrier = mysql_result($carriers_query,$z,"Carrier");
	
	if(($set_agency == 'Diversified Brokerage Services') OR $set_agency == 'Woodbury Financial Services') {
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
			AND agencies.agency_name = '$set_agency'
			AND carriers.carrier_name = '$carrier'");
		$agents_query_rows = mysql_numrows($agents_query);
	}
		
	echo "<table>";
		echo "<thead>";
			echo "<tr>";
				echo "<th colspan='3'>".$carrier."</th>";
			echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
			echo "<tr class='headers'>";
				echo "<td>Policy Number</td>";
				echo "<td>Client</td>";
				echo "<td>Product</td>";
				echo "<td>Transaction Date</td>";
				echo "<td>Premium</td>";
				echo "<td>Contract Payout</td>";
				echo "<td>M3 Override</td>";
				echo "<td>Total Earnings</td>";
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
		
		$agent_name = mysql_result($result,0,'Agent');
		$agency_name = mysql_result($result,0,"Agency");
		
		echo "<tr class='agent'>";
			echo "<td colspan='8'>";
				echo $agent_name." - ".$agent_wc;
			echo "</td>";
		echo "</tr>";
		
		$p = 0;
		$agent_total = 0;
		$override_sub = 0;
		$carrier_sub = 0;
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
			
			// Retrieve Support Rate
			if($agency_name == 'Tennessee Brokerage' AND $inforce_date >= '2013-05-01') {
				$support_comp_query = mysql_query("SELECT commissions.rate
													FROM commissions
												WHERE (commissions.state = '$state' OR commissions.state = '')
													AND commissions.effective_date <= '$inforce_date'
													AND commissions.max_age >= '$client_age'
													AND commissions.min_age <= '$client_age'
													AND commissions.product_id = '$product_id'
													AND commissions.support_id = '2'
													AND commissions.carrier_id = '$carrier_id'
													AND (commissions.option = '$option' OR commissions.option = '')
											    ORDER BY commissions.effective_date DESC, commissions.state DESC, commissions.option DESC
												LIMIT 1");
				$support_rate = mysql_result($support_comp_query,0);
			} else {
				$support_comp_query = mysql_query("SELECT commissions.rate
													FROM commissions
												WHERE (commissions.state = '$state' OR commissions.state = '')
													AND commissions.effective_date <= '$inforce_date'
													AND commissions.max_age >= '$client_age'
													AND commissions.min_age <= '$client_age'
													AND commissions.product_id = '$product_id'
													AND commissions.support_id = '$support_id'
													AND commissions.carrier_id = '$carrier_id'
													AND (commissions.option = '$option' OR commissions.option = '')
											    ORDER BY commissions.effective_date DESC, commissions.state DESC, commissions.option DESC
												LIMIT 1");
				$support_rate = mysql_result($support_comp_query,0);
			}
			
			if($mv_group == True){
				$support_rate = $gross_comp;
			}
			
			// Calculate earnings
			$value = $value;
			$reported_comp = $reported_comp;
			
			if(((strtotime($trans_date) - strtotime($inforce_date)) / 31536000) >= 1) {
				$gross_comp = ($gross_comp / 2);
				$contract_rate = ($contract_rate / 2);
				$street_comp = ($street_comp / 2);
				$support_rate = ($support_rate / 2);
				
				if(($set_agency == 'Diversified Brokerage Services') OR ($set_agency == 'Woodbury Financial Services')) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * 0.05) / 100);
				} elseif(($set_agency == 'LifePro Financial Services') AND ($carrier == 'American Equity')) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * (($gross_comp - $contract_rate) - 0.10)) / 100);
					$support_rate = $gross_comp - 0.10;
				} elseif(($set_agency == 'Accurate Advisors') AND ($carrier == 'American Equity')) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * (($gross_comp - $contract_rate) - 0.25)) / 100);
					$support_rate = $gross_comp - 0.25;
				} elseif(($set_agency == 'Milner Group') AND (($carrier == 'American Equity') XOR ($carrier == 'Symetra') XOR ($carrier == 'Forethought') XOR ($carrier == 'Athene'))) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * (($gross_comp - $contract_rate) - 0.05)) / 100);
					$support_rate = $gross_comp - 0.05;
				} else {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * ($support_rate - $contract_rate)) / 100);
				}
			} elseif($chargeback == 1) {
				if(($set_agency == 'Diversified Brokerage Services') OR ($set_agency == 'Woodbury Financial Services')) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($reported_comp * 0.05) / 100);
				} elseif(($set_agency == 'LifePro Financial Services') AND ($carrier == 'American Equity')) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * (($gross_comp - $contract_rate) - 0.10)) / 100);
					$support_rate = $gross_comp - 0.10;
				} elseif(($set_agency == 'Accurate Advisors') AND ($carrier == 'American Equity')) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * (($gross_comp - $contract_rate) - 0.25)) / 100);
					$support_rate = $gross_comp - 0.25;
				} elseif(($set_agency == 'Milner Group') AND (($carrier == 'American Equity') XOR ($carrier == 'Forethought') XOR ($carrier == 'Symetra') XOR ($carrier == 'Athene'))) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * (($gross_comp - $contract_rate) - 0.05)) / 100);
					$support_rate = $gross_comp - 0.05;
				} else {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * ($support_rate - $contract_rate)) / 100);
				}
			} else {
				if(($set_agency == 'Diversified Brokerage Services') OR ($set_agency == 'Woodbury Financial Services')) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * 0.05) / 100);
				} elseif(($set_agency == 'LifePro Financial Services') AND ($carrier == 'American Equity')) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * (($gross_comp - $contract_rate) - 0.10)) / 100);
					$support_rate = $gross_comp - 0.10;
				} elseif(($set_agency == 'Accurate Advisors') AND ($carrier == 'American Equity')) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * (($gross_comp - $contract_rate) - 0.25)) / 100);
					$support_rate = $gross_comp - 0.25;
				} elseif(($set_agency == 'Milner Group') AND (($carrier == 'American Equity') XOR ($carrier == 'Forethought') XOR ($carrier == 'Symetra') XOR ($carrier == 'Athene'))) {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * (($gross_comp - $contract_rate) - 0.05)) / 100);
					$support_rate = $gross_comp - 0.05;
				} else {
					$contract_payout = (($value * $contract_rate) / 100);
					$support_payout = (($value * ($support_rate - $contract_rate)) / 100);
				}
			}
			
			if(($carrier == 'Allianz Preferred') OR ($agency_name == 'M3 Financial')) {
				$support_payout = '0';
			}
			
			$agent_earnings = ($contract_payout + $support_payout);
			$agent_total += $agent_earnings;
			$override_sub += $support_payout;
			$carrier_sub += $contract_payout;
			
			// Format the date for end user
			$format_trans_date = strtotime($trans_date);
			$format_trans_date = date($date_format, $format_trans_date);
		
			
			echo "<td>".$policy_number."</td>";
			echo "<td>".$client_name."</td>";
			echo "<td>".$product."</td>";
			echo "<td>".$format_trans_date."</td>";
				
			if($value < 0) {
				echo "<td><font color=red>$".number_format($value, 2, '.', ',')."</font></td>";
			} else {
				echo "<td>$".number_format($value, 2, '.', ',')."</td>";
			}
				
			if($contract_payout < 0) {
				echo "<td><font color=red>$".number_format($contract_payout, 2, '.', ',')." (".$contract_rate."%)</font></td>";
			} else {
				echo "<td>$".number_format($contract_payout, 2, '.', ',')." (".$contract_rate."%)</td>";
			}
				
			if((($set_agency == 'Diversified Brokerage Services') XOR ($set_agency == 'Woodbury Financial Services')) AND ($carrier != 'Allianz Preferred')) {
				if($support_payout < 0) {
					$support_rate = '0.05';
					echo "<td><font color=red>$".number_format($support_payout, 2, '.', ',')." (".number_format($support_rate, 3, '.', ',')."%)</font></td>";
				} else {
					$support_rate = '0.05';
					echo "<td>$".number_format($support_payout, 2, '.', ',')." (".number_format($support_rate, 3, '.', ',')."%)</td>";
				}
			} elseif($carrier == 'Allianz Preferred') {
				if($support_payout < 0) {
					$support_rate = '0.05';
					echo "<td>-</td>";
				} else {
					$support_rate = '0.05';
					echo "<td>-</td>";
				}
			} else {
				if($support_payout < 0) {
					echo "<td><font color=red>$".number_format($support_payout, 2, '.', ',')." (".number_format($support_rate - $contract_rate, 3, '.', ',')."%)</font></td>";
				} else {
					echo "<td>$".number_format($support_payout, 2, '.', ',')." (".number_format($support_rate - $contract_rate, 3, '.', ',')."%)</td>";
				}
			}
				
			if($agent_earnings < 0) {
				echo "<td><font color=red>$".number_format($agent_earnings, 2, '.', ',')."</font></td>";
			} else {
				echo "<td>$".number_format($agent_earnings, 2, '.', ',')."</td>";
			}
			
		echo "</tr>";
		
		$p++;
			
	}

		$grand_total += $agent_total;
		$total_override += $override_sub;
		$total_contract += $carrier_sub;

		echo "<tr class='subtotal'>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td>$".number_format($carrier_sub, 2, '.', ',')."</td>";
			echo "<td><i>$".number_format($override_sub, 2, '.', ',')."</i></td>";
			echo "<td>$".number_format($agent_total, 2, '.', ',')."</td>";
		echo "</tr>";
	
	$i++;
		
	}

	echo "<tr class='earnings'>";
		echo "<td>Total Earnings</td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td>$".number_format($grand_total, 2, '.', ',')."</td>";
	echo "</tr><br /><br /><br /><br />";

	$z++;
		
	}

}

?>

				</tbody>
			</table>
		</center>
	</div>
	
<?php

	echo "<aside id='total_override'>Total Paid Override &nbsp;&nbsp;&nbsp;$".number_format($total_override, 2, '.', ',')."</aside>";

?>

</div>


<?php


// Closes MySQL connection
mysql_close();


?>

</body>