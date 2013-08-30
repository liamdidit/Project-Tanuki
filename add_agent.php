<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Agent variables
$agent_first_name = $_POST['agent_first_name'];
$agent_last_name = $_POST['agent_last_name'];
$agent_wc = $_POST['agent_wc'];


// Carrier variables
$carrier_name = $_POST['carrier_name'];
$cidresult = mysql_query("SELECT carriers.id
	FROM carriers
	WHERE carriers.carrier_name = '$carrier_name'");
$carrier_id = mysql_result($cidresult, 0);


// Agency variables
$agency_name = $_POST['agency_name'];
$awcresult = mysql_query("SELECT DISTINCT agencies.agency_wc
	FROM agencies
	INNER JOIN writing_codes
		ON agencies.agency_wc = writing_codes.agency_wc
	INNER JOIN carriers
		ON writing_codes.carrier_id = carriers.id
	WHERE carriers.id = '$carrier_id'
		AND agencies.agency_name = '$agency_name'");
$agency_wc = mysql_result($awcresult, 0);

// Searches database for Agency's Contract Level
$contract_level_query = mysql_query("SELECT writing_codes.contract_level
	FROM writing_codes
	WHERE writing_codes.carrier_id = '$carrier_id'
		AND writing_codes.agency_wc = '$agency_wc'
	LIMIT 1");
$contract_level = mysql_result($contract_level_query, 0);

// Inserts Agent data
$agentquery = mysql_query("INSERT INTO agents VALUES
	('','$agent_first_name','$agent_last_name','$agent_wc')");
mysql_query($agentquery);

// Inserts Writing Code data
$wcquery = mysql_query("INSERT INTO writing_codes VALUES
	('$carrier_id','$agency_wc','$agent_wc','$contract_level')");
mysql_query($wcquery);

// Returns Agent data
$redirect_agent = mysql_query("SELECT DISTINCT agents.id FROM agents
	WHERE agents.first_name = '$agent_first_name'
	AND agents.last_name = '$agent_last_name'
	GROUP BY agents.last_name");
$aid = mysql_result($redirect_agent, 0);	

// Closes MySQL connection
mysql_close();

// Redirect browser 
header("Location: /edit_agent.php?id=$aid");

?>