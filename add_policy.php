<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Policy variables
$policy_number = $_POST['policy_number'];
$comp_base = $_POST['comp_base'];
$agent_wc = $_POST['agent_wc'];
$client_first = $_POST['client_first'];
$client_last = $_POST['client_last'];
$client_age = $_POST['client_age'];
$carrier_name = $_POST['carrier_name'];
$product_name = $_POST['product_name'];
$trans_date = $_POST['trans_date'];
$charge_back = $_POST['charge_back'];
$reported_amt = $_POST['reported_amt'];
$receive_date = $_POST['receive_date'];
$state = $_POST['state'];
$option = $_POST['option'];

// Carrier variables
$carrier_name = $_POST['carrier_name'];
$carrier_id_query = mysql_query("SELECT carriers.id
		FROM carriers
	WHERE carriers.carrier_name = '$carrier_name'");
$carrier_id = mysql_result($carrier_id_query,0);


// Returns next ID in sequence
$id_query = mysql_query("SELECT DISTINCT policies.id FROM policies ORDER BY policies.id DESC LIMIT 1");
$last_id = mysql_result($id_query,0);

$new_id = ($last_id + 1);


// Inserts Policy data
$agent_query = "INSERT INTO policies VALUES
	('$new_id','$agent_wc','$trans_date','$client_first','$client_last','$client_age','$carrier_id','$product_name','$policy_number','$comp_base','$reported_amt','$state','$receive_date','$charge_back','$option')";
mysql_query($agent_query);
	

// Closes MySQL connection
mysql_close();

// Redirect browser 
header("Location: /edit_policy.php?id=$new_id");

?>