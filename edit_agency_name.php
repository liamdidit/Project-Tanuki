<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Agency variables
$agencyname = $_POST['agency_name'];
$aid = $_POST['agency_id'];
	

// Retrieves old Agency data
$old_agency_result = mysql_query("SELECT agencies.agencyname, agencies.agencywc FROM agencies
	WHERE (agencies.id = $aid)") or die('Unable to execute your request ...');
$old_agency_num = mysql_numrows($old_agency_result);
	
	
// Old Agency variables
$old_agency_name = mysql_result($old_agency_result,0,'agencies.agencyname');
$old_agency_wc = mysql_result($old_agency_result,0,'agencies.agencywc');


	if($_POST['edit'] = 'Edit'){		
		$updateagent = "UPDATE agencies
			SET agencies.agencyname = '$agencyname'
			WHERE (agencies.agencyname = '$old_agency_name')";
		mysql_query($updateagent);
	}

	elseif($_POST['edit'] = 'Delete'){
		$updateagent = "DELETE FROM agencies
			WHERE (agencies.agencyname = '$agencyname')";
		mysql_query($updateagent);
		
		$updatewc = "DELETE FROM writing_codes
			INNER JOIN agencies
				ON writing_codes.agencywc = agents.agencywc
			WHERE (agencies.agencyname = '$old_agency_name')";
		mysql_query($updatewc);
	}

// Closes MySQL connection
mysql_close();


// Redirect browser 
header("Location: /edit_agency.php?id=$aid");
exit;

?>