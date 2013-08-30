<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Agency variables
$agency_id = $_POST['agency_id'];
$agency_wc = $_POST['agency_wc'];


// Retrieves Agency data
$agency_result = mysql_query("SELECT agencies.agency_name, agencies.payout_level, agencies.mv_group FROM agencies
	WHERE (agencies.id = '$agency_id')") or die('Unable to execute your request ...');
$agency_num = mysql_numrows($agency_result);

$agency_name = mysql_result($agency_result,0,'agencies.agency_name');
$agency_level = mysql_result($agency_result,0,'agencies.payout_level');
$mv_group = mysql_result($agency_result,0,'agencies.payout_level');


// Carrier variables
$carrier_name = $_POST['carrier_name'];
$c_id_result = mysql_query("SELECT carriers.id FROM carriers
	WHERE carriers.carrier_name = '$carrier_name'");
$carrier_id = mysql_result($c_id_result, 0);
		

// Add Writing Code		
$update_wc = "INSERT INTO writing_codes VALUES
	('$carrier_id','$agency_wc','')";
mysql_query($update_wc);

$update_ag = "INSERT INTO agencies VALUES
	('','$agency_wc','$agency_name','$agency_level','$mv_group')";
mysql_query($update_ag);


// Closes MySQL connection
mysql_close();


// Redirect browser 
header("Location: /edit_agency.php?id=$agency_id");

?>