<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Agent variables
$agent_first = $_POST['agent_first_name'];
$agent_last = $_POST['agent_last_name'];
$agent_id = $_POST['agent_id'];
$agent_wc = $_POST['agent_wc'];


// Retrieves old Agent data
$old_agent_result = mysql_query("SELECT agents.first_name, agents.last_name, agents.agent_wc
									FROM agents
								WHERE (agents.id = '$agent_id')") or die('Unable to execute your request ...');
$old_agent_num = mysql_numrows($old_agent_result);
	
	
// Old Agent variables
$old_agent_first = mysql_result($old_agent_result,0,'agents.first_name');
$old_agent_last = mysql_result($old_agent_result,0,'agents.last_name');
$old_agent_wc = mysql_result($old_agent_result,0,'agents.agent_wc');


	if(isset($_POST['edit'])){
		$update_agent_query = "UPDATE agents
			SET agents.first_name = '$agent_first',
				agents.last_name = '$agent_last'
			WHERE (agents.first_name = '$old_agent_first')
				AND (agents.last_name = '$old_agent_last')";
		mysql_query($update_agent_query);

	} elseif(isset($_POST['delete'])){
		$update_agent_query = "DELETE FROM agents
			WHERE (agents.first_name = '$old_agent_first')
				AND (agents.last_name = '$old_agent_last')";
		mysql_query($update_agent_query);
		
		$update_code_query = "DELETE FROM writing_codes
			INNER JOIN agents
				ON writing_codes.agent_wc = agents.agent_wc
			WHERE (agents.first_name = '$old_agent_first')
				AND (agents.last_name = '$old_agent_last')";
		mysql_query($update_code_query);
		
	} elseif(isset($_POST['add'])){
		
		// Carrier variables
		$carrier_name = $_POST['carrier_name'];
		
		$carrier_query = mysql_query("SELECT carriers.id FROM carriers
				WHERE carriers.carrier_name = '$carrier_name'
				LIMIT 1");
		$carrier_id = mysql_result($carrier_query, 0, "carriers.id");
		
		// Agency variables
		$agency_name = $_POST['agency_name'];
		
		$agency_wc_query = mysql_query("SELECT agencies.agency_wc FROM agencies
				WHERE agencies.agency_name = '$agency_name'
				LIMIT 1");
		$agency_wc = mysql_result($agency_wc_query, 0);
		
		$agency_contract_query = mysql_query("SELECT writing_codes.contract_level FROM writing_codes
					INNER JOIN carriers
						ON writing_codes.carrier_id = '$carrier_id'
					WHERE writing_codes.agency_wc = '$agency_wc'
					AND carriers.id = '1'
					LIMIT 1");
		$contract_level = mysql_result($agency_contract_query, 0);
		
		// Updates the database
		$add_agent_query = "INSERT INTO agents
				(agents.id, agents.first_name, agents.last_name, agents.agent_wc)
			VALUES ('', '$agent_first', '$agent_last', '$agent_wc')";	
		mysql_query($add_agent_query);
		
		$add_agent_wc_query = "INSERT INTO writing_codes
				(writing_codes.carrier_id, writing_codes.agency_wc, writing_codes.agent_wc, writing_codes.contract_level)
			VALUES ('$carrier_id', '$agency_wc', '$agent_wc', '$contract_level')";
		mysql_query($add_agent_wc_query);
		
	}

// Locates the Agent in the database for redirection
$agent_id_query = "SELECT agents.id FROM agents
		WHERE agents.agent_wc = '$agent_wc'";	
mysql_query($agent_id_query);

$agent_id = mysql_result($agent_id_query, 0);

// Closes MySQL connection
mysql_close();

// Redirect browser 
header("Location: /");

?>