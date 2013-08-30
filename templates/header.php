<head>
    <title></title>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery-ui-1.8.2.custom.css" /> 
    <link rel="stylesheet" href="css/jquery.ui.theme.css" />
    <link rel="stylesheet" href="css/jquery.ui.datepicker.css" />
    <link rel="stylesheet" href="css/datepicker.css" />

	<script src="js/jquery.ui.core.js"></script>
	<script src="js/jquery.ui.widget.js"></script>
	<script src="js/jquery.ui.datepicker.js"></script>
	<script src="js/autoNumeric.js"></script>
	
	<script src="js/jquery-1.8.2.js"></script> 
	<script src="js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script>
		jQuery(document).ready(function($){
			$('#name_search').autocomplete({source:'dynamic_search.php', minLength:2});
			$('.datepicker').datepicker();
     		$('#comp_base').autoNumeric();    
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
			echo "<h1>&#9888;</h1>";
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
		
		
		?>
	
	<div id="page_wrapper">
	
	<div id="left_section">
		<div id="get_started">
			<section>
				<h1>M3 Financial</h1>
				<h2>Commissions Module</h2>
			</section>
		</div>
		<aside id="start_arrow"></aside>
		
		<div id="admin">
			<ul>
				<a href="/">
					<li>
						&#8962;
					</li>
				</a>
				<a href="/new_agent.php">
					<li>
						&#128100;
					</li>
				</a>
				<a href="/new_agency.php">
					<li>
						&#128193;
					</li>
				</a>
				<a href="/new_policy.php">
					<li>
						&#59190;
					</li>
				</a>
				<a href="/import.php">
					<li>
						&#128229;
					</li>
				</a>
				<a href="/admin.php">
					<li>
						&#9881;
					</li>
				</a>
			</ul>
		</div>
	</div>