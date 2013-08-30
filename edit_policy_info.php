<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Policy variables
$policy_id = $_POST['pid'];
$policy_number = $_POST['policy_number'];
$effect_date = $_POST['receive_date'];
$trans_date = $_POST['trans_date'];
$state = $_POST['state'];
$comp_base = $_POST['comp_base'];
$charge_back = $_POST['charge_back'];
$client_first = $_POST['client_first'];
$client_last = $_POST['client_last'];
$client_age = $_POST['client_age'];
$reported_amt = $_POST['reported_amt'];
$option = $_POST['option'];


// Agent variables
$agent_wc = $_POST['agent_wc'];


// Carrier variables
$carrier_id = $_POST['carrier_name'];
$product_id = $_POST['product_name'];
		

// Edit Policy information in database
$update_policy = "UPDATE policies
			SET policies.agent_wc = '$agent_wc',
				policies.trans_date = '$trans_date',
				policies.client_first = '$client_first',
				policies.client_last = '$client_last',
				policies.client_age = '$client_age',
				policies.carrier_id = '$carrier_id',
				policies.product_name = '$product_id',
				policies.policy_number = '$policy_number',
				policies.comp_base = '$comp_base',
				policies.reported_amt = '$reported_amt',
				policies.state = '$state',
				policies.effect_date = '$effect_date',
				policies.charge_back = '$charge_back',
				policies.option = '$option'
			WHERE (policies.id = '$policy_id')";
mysql_query($update_policy);


// Closes MySQL connection
mysql_close();


// Redirect browser 
header("Location: /edit_policy.php?id=$policy_id");
exit;


?>