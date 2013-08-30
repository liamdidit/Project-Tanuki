<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Agent variables
$agent_wc = $_POST['agent_wc'];
$agent_id = $_POST['agent_id'];
$contract_level = $_POST['contract_level'];


// Carrier variables
$carrier_name = $_POST['carrier_name'];
$carrier_id_query = mysql_query("SELECT carriers.id FROM carriers
	WHERE carriers.carrier_name = '$carrier_name'") or die('TROUBLE RETRIEVING CARRIER ID');
$carrier_num = mysql_result($carrier_id_query, 0);
$carrier_id = mysql_result($carrier_id_query,0,'carriers.id');
	
	
// Agency variables
$agency_name = $_POST['agency_name'];
	
	
// Retrieves the Agency's Writing Code from the database
$agency_code_query = mysql_query("SELECT DISTINCT agencies.agency_wc FROM agencies
	INNER JOIN writing_codes
		ON agencies.agency_wc = writing_codes.agency_wc
	INNER JOIN carriers
		ON writing_codes.carrier_id = carriers.id
	WHERE carriers.id = '$carrier_id'
		AND agencies.agency_name = '$agency_name'") or die('TROUBLE RETRIEVING AGENCY WC');
$agency_wc = mysql_result($agency_code_query, 0);


// Retrieves old Agent data
$old_agent_result = mysql_query("SELECT agents.id, agents.first_name, agents.last_name, agents.agent_wc FROM agents
	INNER JOIN writing_codes
		ON agents.agent_wc = writing_codes.agent_wc
	WHERE agents.id = '$agent_id'") or die('Unable to execute your request ...');
$old_agent_first = mysql_result($old_agent_result,0,'agents.first_name');
$old_agent_last = mysql_result($old_agent_result,0,'agents.last_name');
$old_agent_num = mysql_result($old_agent_result,0,'agents.agent_wc');
$old_agent_id = mysql_result($old_agent_result,0,'agents.agent_id');


// Changes/Deletes the Agent's Writing Code
if(isset($_POST['edit'])) {
	
	$edit_query = "UPDATE agents
		SET agents.agent_wc = '$agent_wc'
		WHERE agents.agent_wc = '$old_agent_num'";
	mysql_query($edit_query);
	
	$edit_code_query = "UPDATE writing_codes
		SET writing_codes.agent_wc = '$agent_wc', writing_codes.agency_wc = '$agency_wc', writing_codes.contract_level = '$contract_level'
		WHERE writing_codes.agent_wc = '$old_agent_num'
		AND writing_codes.carrier_id = '$carrier_id'";
	mysql_query($edit_code_query);
	
} elseif(isset($_POST['delete_wc'])) {
	
	$delete_query = "DELETE FROM agents
		WHERE agents.agent_wc = '$agent_wc'";
	mysql_query($delete_query);
	
	$delete_code_query = "DELETE FROM writing_codes
		WHERE writing_codes.agent_wc = '$agent_wc'
		AND writing_codes.carrier_id = '$carrier_id'";
	mysql_query($delete_code_query);
	
}

// Changes the agent_id for redirection
$new_agent_id_result = mysql_query("SELECT agents.id FROM agents
			WHERE (agents.first_name = '$old_agent_first') AND (agents.last_name = '$old_agent_last')
			LIMIT 1") or die('No results were found ...');
$new_agent_id_num = mysql_numrows($new_agent_id_result);


if($new_agent_id_num > 0){
	$agent_id = mysql_result($new_agent_id_result,0,'agents.id');
};


// Closes MySQL connection
mysql_close();


// Redirect browser 
header("Location: /edit_agent.php?id=$agent_id");
exit;

?>