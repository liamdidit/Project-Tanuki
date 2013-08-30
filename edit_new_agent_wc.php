<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Agent variables
$agent_id = $_POST['agent_id'];
$agent_wc = $_POST['agent_wc'];
$contract_level = $_POST['contract_level'];


// Retrieves Agent data
$agent_result = mysql_query("SELECT agents.first_name, agents.last_name FROM agents
	WHERE (agents.id = $agent_id)") or die('Unable to execute your request ...');
$agent_num = mysql_numrows($agent_result);

$agent_first = mysql_result($agent_result,0,'agents.first_name');
$agent_last = mysql_result($agent_result,0,'agents.last_name');


// Carrier variables
$carrier_name = $_POST['carrier_name'];
$cidresult = mysql_query("SELECT carriers.id FROM carriers
	WHERE carriers.carrier_name = '$carrier_name'");
$cid = mysql_result($cidresult, 0);


// Agency variables
$agency_name = $_POST['agency_name'];
$awcresult = mysql_query("SELECT DISTINCT agencies.agency_wc FROM agencies
	INNER JOIN writing_codes
		ON agencies.agency_wc = writing_codes.agency_wc
	INNER JOIN carriers
		ON writing_codes.carrier_id = carriers.id
	WHERE carriers.carrier_name = '$carrier_name'
		AND agencies.agency_name = '$agency_name'");
$agency_wc = mysql_result($awcresult, 0);
		

// Add Writing Code		
$updatewc = "INSERT INTO writing_codes VALUES
	('$cid','$agency_wc','$agent_wc','$contract_level')";
mysql_query($updatewc);

$updateag = "INSERT INTO agents VALUES
	('','$agent_first','$agent_last','$agent_wc')";
mysql_query($updateag);


// Closes MySQL connection
mysql_close();


// Redirect browser 
header("Location: /edit_agent.php?id=$agent_id");
exit;

?>