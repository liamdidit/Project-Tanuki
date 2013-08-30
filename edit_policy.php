<?php

	include_once("templates/header.php");
	
	// Dynamically retrieve entry for editing
	$pid = $_GET['id'];
	
	// Returns Carriers from database for dropdown
	$carriers_query = mysql_query("SELECT DISTINCT carriers.carrier_name, carriers.id FROM carriers ORDER BY carriers.carrier_name ASC");
	$cnum = mysql_numrows($carriers_query);
	
	// Returns Products from database for dropdown based on Carrier selection
	$product_query = mysql_query("SELECT DISTINCT (products.product_name)as 'Product Name', (products.id)as 'Product ID' FROM commissions INNER JOIN products ON commissions.product_id = products.id ORDER BY commissions.carrier_id, commissions.product_id");
	$pnum = mysql_numrows($product_query);
	
	// Retrieves all Policy transactions from the database
	$trans_results = mysql_query("SELECT policies.id, policies.agent_wc, policies.trans_date, policies.client_first,
			policies.client_last, policies.client_age, policies.carrier_id, policies.product_name,
			policies.policy_number, policies.comp_base, policies.reported_amt, policies.state, policies.effect_date,
			policies.charge_back, policies.option
		FROM policies
		WHERE (policies.id = '$pid')
		ORDER BY policies.trans_date ASC") or die('Init premium query not working ...');
	$tnum = mysql_numrows($trans_results);
	
	$policy_id = mysql_result($trans_results,0,"policies.id");
	$policy_number = mysql_result($trans_results,0,"policies.policy_number");
	$agent_wc = mysql_result($trans_results,0,"policies.agent_wc");
	$client_first = mysql_result($trans_results,0,"policies.client_first");
	$client_last = mysql_result($trans_results,0,"policies.client_last");
	$client_age = mysql_result($trans_results,0,"policies.client_age");
	$carrier_id = mysql_result($trans_results,0,"policies.carrier_id");
	$product_name = mysql_result($trans_results,0,"policies.product_name");
	$comp_base = mysql_result($trans_results,0,"policies.comp_base");
	$trans_date = mysql_result($trans_results,0,"policies.trans_date");
	$reported_amt = mysql_result($trans_results,0,"policies.reported_amt");
	$state = mysql_result($trans_results,0,"policies.state");
	$effect_date = mysql_result($trans_results,0,"policies.effect_date");
	$charge_back = mysql_result($trans_results,0,"policies.charge_back");
	$option = mysql_result($trans_results,0,"policies.option");
	
?>
<div id="major_wrapper">
	<div id="edit">
		<section>
			<h1>Edit Policy</h1>
		</section>
		<aside id="arrow_border"></aside>
		<aside id="arrow"></aside>
	</div>
	<section id="content_wrapper">
		<form action="edit_policy_info.php" method="post">
			<h2>Policy Information</h2>
			<section>
				<h4>Policy Number</h4>
				<input type="hidden" name="pid" value="<?php echo $policy_id; ?>" required READONLY>
				<input type="text" name="policy_number" class="writing_code" value="<?php echo $policy_number; ?>" required READONLY>
			</section>
			<section>
				<h4>Effective Date</h4>
				<input type="text" name="receive_date" class="datepicker" alt="sql_date" value="<?php echo $effect_date; ?>" required>
			</section>
			<section>
				<h4>Transaction Date</h4>
				<input type="text" name="trans_date" class="datepicker" alt="sql_date" value="<?php echo $trans_date; ?>" required>
			</section>
			<section>
				<h4>Issue State</h4>
				<select name="state" required>
					<option value =\" \">-- State --</option>
					<option value ="AL" <?php if($state == 'AL'){ echo "selected"; } ?>>Alabama</option>
					<option value ="AK" <?php if($state == 'AK'){ echo "selected"; } ?>>Alaska</option>
					<option value ="AZ" <?php if($state == 'AZ'){ echo "selected"; } ?>>Arizona</option>
					<option value ="AR" <?php if($state == 'AR'){ echo "selected"; } ?>>Arkansas</option>
					<option value ="CA" <?php if($state == 'CA'){ echo "selected"; } ?>>California</option>
					<option value ="CO" <?php if($state == 'CO'){ echo "selected"; } ?>>Colorado</option>
					<option value ="CT" <?php if($state == 'CT'){ echo "selected"; } ?>>Connecticut</option>
					<option value ="DE" <?php if($state == 'DE'){ echo "selected"; } ?>>Delaware</option>
					<option value ="FL" <?php if($state == 'FL'){ echo "selected"; } ?>>Florida</option>
					<option value ="GA" <?php if($state == 'GA'){ echo "selected"; } ?>>Georgia</option>
					<option value ="HI" <?php if($state == 'HI'){ echo "selected"; } ?>>Hawaii</option>
					<option value ="ID" <?php if($state == 'ID'){ echo "selected"; } ?>>Idaho</option>
					<option value ="IL" <?php if($state == 'IL'){ echo "selected"; } ?>>Illinois</option>
					<option value ="IN" <?php if($state == 'IN'){ echo "selected"; } ?>>Indiana</option>
					<option value ="IA" <?php if($state == 'IA'){ echo "selected"; } ?>>Iowa</option>
					<option value ="KS" <?php if($state == 'KS'){ echo "selected"; } ?>>Kansas</option>
					<option value ="KY" <?php if($state == 'KY'){ echo "selected"; } ?>>Kentucky</option>
					<option value ="LA" <?php if($state == 'LA'){ echo "selected"; } ?>>Louisiana</option>
					<option value ="ME" <?php if($state == 'ME'){ echo "selected"; } ?>>Maine</option>
					<option value ="MD" <?php if($state == 'MD'){ echo "selected"; } ?>>Maryland</option>
					<option value ="MA" <?php if($state == 'MA'){ echo "selected"; } ?>>Massachusetts</option>
					<option value ="MI" <?php if($state == 'MI'){ echo "selected"; } ?>>Michigan</option>
					<option value ="MN" <?php if($state == 'MN'){ echo "selected"; } ?>>Minnesota</option>
					<option value ="MS" <?php if($state == 'MS'){ echo "selected"; } ?>>Mississippi</option>
					<option value ="MO" <?php if($state == 'MO'){ echo "selected"; } ?>>Missouri</option>
					<option value ="MT" <?php if($state == 'MT'){ echo "selected"; } ?>>Montana</option>
					<option value ="NE" <?php if($state == 'NE'){ echo "selected"; } ?>>Nebraska</option>
					<option value ="NV" <?php if($state == 'NV'){ echo "selected"; } ?>>Nevada</option>
					<option value ="NH" <?php if($state == 'NH'){ echo "selected"; } ?>>New Hampshire</option>
					<option value ="NJ" <?php if($state == 'NJ'){ echo "selected"; } ?>>New Jersey</option>
					<option value ="NM" <?php if($state == 'NM'){ echo "selected"; } ?>>New Mexico</option>
					<option value ="NY" <?php if($state == 'NY'){ echo "selected"; } ?>>New York</option>
					<option value ="NC" <?php if($state == 'NC'){ echo "selected"; } ?>>North Carolina</option>
					<option value ="ND" <?php if($state == 'ND'){ echo "selected"; } ?>>North Dakota</option>
					<option value ="OH" <?php if($state == 'OH'){ echo "selected"; } ?>>Ohio</option>
					<option value ="OK" <?php if($state == 'OK'){ echo "selected"; } ?>>Oklahoma</option>
					<option value ="OR" <?php if($state == 'OR'){ echo "selected"; } ?>>Oregon</option>
					<option value ="PA" <?php if($state == 'PA'){ echo "selected"; } ?>>Pennsylvania</option>
					<option value ="RI" <?php if($state == 'RI'){ echo "selected"; } ?>>Rhode Island</option>
					<option value ="SC" <?php if($state == 'SC'){ echo "selected"; } ?>>South Carolina</option>
					<option value ="SD" <?php if($state == 'SD'){ echo "selected"; } ?>>South Dakota</option>
					<option value ="TN" <?php if($state == 'TN'){ echo "selected"; } ?>>Tennessee</option>
					<option value ="TX" <?php if($state == 'TX'){ echo "selected"; } ?>>Texas</option>
					<option value ="UT" <?php if($state == 'UT'){ echo "selected"; } ?>>Utah</option>
					<option value ="VT" <?php if($state == 'VT'){ echo "selected"; } ?>>Vermont</option>
					<option value ="VA" <?php if($state == 'VA'){ echo "selected"; } ?>>Virginia</option>
					<option value ="WA" <?php if($state == 'WA'){ echo "selected"; } ?>>Washington</option>
					<option value ="WV" <?php if($state == 'WV'){ echo "selected"; } ?>>West Virginia</option>
					<option value ="WI" <?php if($state == 'WI'){ echo "selected"; } ?>>Wisconsin</option>
					<option value ="WY" <?php if($state == 'WY'){ echo "selected"; } ?>>Wyoming</option>
				</select>
			</section>
			<section>
				<h4>Premium Amount</h4>
				<input type="text" id="comp_base" name="comp_base" class="premium_amount" value="<?php echo $comp_base; ?>" required>
			</section>
			<section>
				<h4>Surrender?</h4>
				<input class="checkbox" name="charge_back" <?php if($charge_back == TRUE){ echo "checked"; } ?> type="checkbox" value="1">
			</section>
			
			<h2>Agent Information</h2>
			<section>
				<h4>Writing Code</h4>
				<input type="text" class="writing_code" name="agent_wc" value="<?php echo $agent_wc; ?>" required>
			</section>
			
			<h2>Client Information</h2>
			<section>
				<h4>First Name</h4>
				<input type="text" name="client_first" value="<?php echo $client_first; ?>" required>
			</section>
			<section>
				<h4>Last Name</h4>
				<input type="text" name="client_last" value="<?php echo $client_last; ?>" required>
			</section>
			<section>
				<h4>Age at Issue</h4>
				<input type="text" class="contract_level" name="client_age" size="2" maxlength="2" value="<?php echo $client_age; ?>" required>
			</section>
			
			<h2>Carrier Information</h2>
			<section>
				<h4>Insurer</h4>
				<select name="carrier_name" required>
						<option value=\" \">-- Select a Carrier --</option>
<?php

// Lists Carriers from database
$ci = 0;
while ($ci < $cnum){
	
	$c_query_id = mysql_result($carriers_query, $ci, 'carriers.id');
	$carrier_name = mysql_result($carriers_query, $ci, 'carriers.carrier_name');
	
	echo "<option value='".$carrier_id."'";
	
	if($carrier_id == $c_query_id){
		echo ' selected';
	}
	
	echo ">".$carrier_name."</option>";
	
	$ci++;
}

?>
				</select>
			</section>
			<section>
				<h4>Product/Plan</h4>
				<select name="product_name" required>
					<option value=\" \">-- Select a Product --</option>
<?php

// Lists Products from database
$pi = 0;
while ($pi < $pnum){
	
	$p_query_name = mysql_result($product_query,$pi,'Product Name');
	$product_id = mysql_result($product_query,$pi,'Product ID');
	
	echo "<option value='".$product_id."'";
	
	if($product_name == $product_id){
		echo ' selected';
	}
	
	echo ">".$p_query_name."</option>";
	
	$pi++;
}

// Closes MySQL connection
mysql_close();

?>
					</select>
				</section>
				<h2>Commission Information</h2>
				<section>
					<h4>Earnings</h4>
					<input type="text" name="reported_amt" class="premium_amount" value="<?php echo $reported_amt; ?>" required>
				</section>
				<section>
					<h4>Option</h4>
					<input type="text" class="contract_level" value="<?php echo $option; ?>" name="option">
				</section>
				<h2></h2>
				<section>
					<input class='add_button' type='submit' name='edit' value='Submit'>
				</section>
			</form>
		</section>
	</div>

</body>
</html>