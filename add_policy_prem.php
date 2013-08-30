<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Policy variables
$pid = $_POST['policy_id'];


// Additional Premium variables
$value = $_POST['add_premium'];
$issuedate = $_POST['issue_date'];
		

// Retrieve Policy information from database
$result = mysql_query("SELECT policies.agentwc,
				    policies.insuredfirstname,
				    policies.insuredlastname,
				    policies.clientage,
				    policies.carrierid,
				    policies.product,
				    policies.policynumber,
				    policies.policystatus,
				    policies.splitticket,
				    policies.splitticket2
			FROM policies
			WHERE (policies.id = '$pid')");


$agentwc = mysql_result($result,0,"policies.agentwc");
$insuredfirstname = mysql_result($result,0,"policies.insuredfirstname");
$insuredlastname = mysql_result($result,0,"policies.insuredlastname");
$clientage = mysql_result($result,0,"policies.clientage");
$carrierid = mysql_result($result,0,"policies.carrierid");
$product = mysql_result($result,0,"policies.product");
$policynumber = mysql_result($result,0,"policies.policynumber");
$policystatus = mysql_result($result,0,"policies.policystatus");
$splitticket = mysql_result($result,0,"policies.splitticket");
$splitticket2 = mysql_result($result,0,"policies.splitticket2");


// Inserts new Policy into database with Additional Premium value
$policy_query = "INSERT INTO policies VALUES
	('','$agentwc','$issuedate','$insuredfirstname','$insuredlastname','$clientage','$carrierid','$product','$policynumber','$policystatus','$value','$splitticket','$splitticket2')";
mysql_query($policy_query);


// Closes MySQL connection
mysql_close();


// Redirect browser 
header("Location: /edit_policy.php?id=$pid");
exit;


?>