<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Agency variables
$agency_wc = $_POST['agency_wc'];
$old_agency_wc = $_POST['old_agency_wc'];
$agency_id = $_POST['agency_id'];


// Carrier variables
$carrier_name = $_POST['carrier_name'];
$c_id_result = mysql_query("SELECT carriers.id FROM carriers
	WHERE carriers.carrier_name = '$carrier_name'") or die('TROUBLE RETRIEVING CARRIER ID');
$carrier_id = mysql_result($c_id_result, 0);

	
// Update Writing Code data from database
$update_agent = "UPDATE agencies
	SET agencies.agency_wc = '$agency_wc'
	WHERE (agencies.agency_wc = '$old_agency_wc')";
mysql_query($update_agent);
		
$update_wc = "UPDATE writing_codes
	SET writing_codes.agency_wc = '$agency_wc'
	WHERE (writing_codes.agency_wc = '$old_agency_wc')";
mysql_query($update_wc);


// Closes MySQL connection
mysql_close();


// Redirect browser 
header("Location: /edit_agency.php?id=$agency_id");
exit;

?>