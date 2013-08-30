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
		
		
		// Retrieve Agent information from database
		$result = mysql_query("SELECT DISTINCT (agents.id)as 'Agent ID',
				CONCAT(agents.first_name, ' ', agents.last_name)as 'Agent Name'
			FROM agents
			INNER JOIN writing_codes
				ON agents.agent_wc = writing_codes.agent_wc
			INNER JOIN agencies
				ON writing_codes.agency_wc = agencies.agency_wc
			WHERE writing_codes.agency_wc = ''
				OR agencies.agency_name = 'Unassigned'
			GROUP BY `Agent Name`
			ORDER BY agents.last_name");
		$num = mysql_numrows($result);
		
		
		// Retrieve Policy information from database
		$code_result = mysql_query("SELECT DISTINCT policies.agent_wc FROM policies
		WHERE NOT EXISTS (SELECT writing_codes.agent_wc FROM writing_codes
						WHERE writing_codes.agent_wc = policies.agent_wc)");
		$wc_num = mysql_numrows($code_result);
		
		if(($num > 0) OR ($wc_num > 0)) {
						
			echo "<header>";		
			echo "<h1><h1>&#9888;</h1>";
			echo "<section>";
			
		}
		
		
		// If one or more rows are returned ...
		if($num > 0) {
					
			echo "<h2>Unassigned</h2>";
			
			echo "<aside>";
				
			$i = 0;
			while ($i < $num) {
				
				$agent_id = mysql_result($result,$i,"Agent ID");
				$agent = mysql_result($result,$i,"Agent Name");
				
				echo "<a href='/edit_agent.php?id=".$agent_id."'>".$agent."</a>";
				
				$i++;
				
			}
			
			echo "</aside>";
			
		}
		
		
		// If one or more rows are returned ...
		if($wc_num > 0) {
					
			echo "<h2>Unidentified</h2>";
			
			echo "<aside>";
				
			$wc_i = 0;
			while ($wc_i < $wc_num) {
				
				$agent_wc = mysql_result($code_result,$wc_i,"policies.agent_wc");
				
				echo "<a href='/edit_agent.php?wc=".$agent_wc."'>".$agent_wc."</a>";
				
				$wc_i++;
				
			}
			
			echo "</aside>";
			
		}
		
		if(($num > 0) OR ($wc_num > 0)) {
						
			echo "</section>";
			echo "</header>";
			
		}
		
		
		// Closes MySQL connection
		mysql_close();
		
		
		?>
		
	<div id="page_wrapper">
	
	<div id="left_section">
		<div id="get_started">
			<section>
				<h1>M3 Financial</h1>
				<h2>Commissions Module</h2>
			</section>
			<aside id="arrow"></aside>
		</div>
		
		<div id="admin">
			<ul>
				<a href="/new_agent.php">
					<li>
						&#128101;
					</li>
				</a>
				<a href="/new_agency.php">
					<li>
						&#128188;
					</li>
				</a>
				<a href="/new_policy.php">
					<li>
						&#59190;
					</li>
				</a>
			</ul>
		</div>
	</div>
		
	</div>

	<section id="content_wrapper">
		<div id="edit">
			<section>
				<h1>Edit Agency</h1>
			</section>
			<aside id="arrow_border"></aside>
			<aside id="arrow"></aside>
		</div>
	
<?php

// Connects to MySQL database
$username = "web";
$password = "Vague7even";
$database = "comp_database";

mysql_connect('localhost',$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


// Dynamically retrieve entry for editing
$id = $_GET['id'];


// Searches the table for the Agency based on the ID passed through the URL
$result = mysql_query("SELECT agencies.id, agencies.agency_name FROM agencies
	WHERE (agencies.id = '$id')") or die('Could not locate agency id.');
$anum = mysql_numrows($result);


// Agent variables
$agency_name = mysql_result($result,0,"agencies.agency_name");


// Return all of the Agency's Writing Codes
$wcresult = mysql_query("SELECT DISTINCT writing_codes.agency_wc, carriers.carrier_name FROM writing_codes
	INNER JOIN agencies
		ON writing_codes.agency_wc = agencies.agency_wc
	INNER JOIN carriers
		ON writing_codes.carrier_id = carriers.id
	WHERE (agencies.agency_name = '$agency_name')") or die('No results were found ...');
$wnum = mysql_numrows($wcresult);


// Returns Carriers from database for dropdown
$carrierresult = mysql_query('SELECT carriers.carrier_name FROM carriers ORDER BY carriers.carrier_name');
$cnum = mysql_numrows($carrierresult);


// Display Agency information
echo "<form action='edit_agency_name.php' method='post'>";
echo "<h2>Agency Information</h2>";
echo "<input type='hidden' value='".$id."' name='agency_id' size=2 READONLY />";
echo "<input type='text' value='".$agency_name."' name='agency_name' class='agency_name' required />";
echo "<input type='submit' name='edit' class='edit_button' value='&#9998;'>";
echo "<input type='submit' name='edit' class='delete_button' value='&#128683;'>";
echo "</form>";


// Add new Writing Code
echo "<form action='edit_new_agency_wc.php' method='post'>";
echo "<h2>Add Writing Code</h2>";
echo "<input type='hidden' value='".$id."' name='agency_id' size=2 READONLY />";
echo "<select name='carrier_name'>";
echo "<option value=\'\'>-- Select a carrier --</option>";


// Lists Carriers from database
if($cnum > 0){
	$ci = 0;
	while ($ci < $cnum){
		$carrier_name = mysql_result($carrierresult, $ci);
		
		echo "<option value='".$carrier_name."'>".$carrier_name."</option>";
	
		$ci++;
	}
};

echo "</select>";
echo "<input type='text' name='agency_wc' />";
echo "<input type='submit' name='edit' value='&oplus;' class='edit_button'>";
echo "</form>";


////////////////////////////////////////////////////////////////////////////////////


// Set Writing Code variables for the information returned from the query
if($wnum > 0){
	$i = 0;
	
	echo "<form>";
		echo "<h2>Writing Codes</h2>";
	echo "</form>";
	
	while ($i < $wnum){
	
		$agency_wc = mysql_result($wcresult, $i, "writing_codes.agency_wc");
		$carrier_name = mysql_result($wcresult, $i, "carriers.carrier_name");
		
		echo "<form action='edit_agency_wc.php' method='post'>";
			echo "<input type='hidden' value='".$id."' name='agency_id' size=2 READONLY />";
			echo "<input type='hidden' value='".$agency_wc."' name='old_agency_wc' READONLY />";
			echo "<input type='text' value='".$carrier_name."' name='carrier_name' READONLY />";
			echo "<input type='text' value='".$agency_wc."' name='agency_wc' />";
			echo "<input type='submit' name='edit' class='edit_button' value='&#9998;'>";
		echo "</form>";

		$i++;
	}
}

echo "<h3><a href='/'>&#10226;</a></h3>";

// Closes the MySQL connection
mysql_close();

?>

</body>
</html>