<head>
    <title></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <link rel="stylesheet" href="/css/style.css" type="text/css" />
    
    <link rel="stylesheet" href="css/jquery.ui.theme.css">
    <link rel="stylesheet" href="css/jquery.ui.datepicker.css">
    <link rel="stylesheet" href="css/datepicker.css">
    
    <script src="js/jquery-1.8.2.js"></script>
	<script src="js/jquery.ui.core.js"></script>
	<script src="js/jquery.ui.widget.js"></script>
	<script src="js/jquery.ui.datepicker.js"></script>
    
    <script>
	$(function() {
		$( ".datepicker" ).datepicker();
	});
	</script>
	
</head>
<body>

<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Carrier variables
$carrier_name = $_POST['carrier_name'];
$contract_level = $_POST['contract_level'];
$cidresult = mysql_query("SELECT carriers.id
		FROM carriers
	WHERE carriers.carrier_name = '$carrier_name'");
$carrierid = mysql_result($cidresult, 0);

// Agency variables
$agency_name = $_POST['agency_name'];
$agency_wc = $_POST['agency_wc'];
$support_level = $_POST['support_level'];
$mvgroup = $_POST['mv_group'];

$payresult = mysql_query("SELECT gross_payout.id
		FROM gross_payout
	WHERE gross_payout.level = '$support_level'");
$paylevel = mysql_result($payresult, 0);


// Inserts Agency data
$agencyquery = "INSERT INTO agencies VALUES
	('','$agency_wc','$agency_name','$paylevel','$mvgroup')";
mysql_query($agencyquery);


// Inserts Writing Code data
$wcquery = "INSERT INTO writing_codes VALUES
	('$carrierid','$agency_wc','','$contract_level')";
mysql_query($wcquery);


// Closes MySQL connection
mysql_close();


// Redirect browser 
header("Location: /");
exit;


?>

</body>