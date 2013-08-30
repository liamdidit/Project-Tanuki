<?php
	
	include_once("templates/header.php");
	
	
	// Returns Carriers from database for dropdown
	$carriers_query = mysql_query("SELECT DISTINCT carriers.carrier_name FROM carriers ORDER BY carriers.carrier_name ASC");
	$cnum = mysql_numrows($carriers_query);
	
	
	// Returns Products from database for dropdown based on Carrier selection
	$product_query = mysql_query("SELECT DISTINCT (products.product_name)as 'Product Name', (products.id)as 'Product ID' FROM commissions INNER JOIN products ON commissions.product_id = products.id ORDER BY commissions.carrier_id, commissions.product_id");
	$pnum = mysql_numrows($product_query);
	
?>
<div id="major_wrapper">
	<div id="edit">
		<section>
			<h1>Add Policy</h1>
		</section>
		<aside id="arrow_border"></aside>
		<aside id="arrow"></aside>
	</div>
	<section id="content_wrapper">
		<form action="add_policy.php" method="post">
			<h2>Policy Information</h2>
			<section>
				<h4>Policy Number</h4>
				<input type="text" class="writing_code" name="policy_number" required>
			</section>
			<section>
				<h4>Effective Date</h4>
				<input type="text" name="receive_date" class="datepicker" alt="sql_date" required>
			</section>
			<section>
				<h4>Transaction Date</h4>
				<input type="text" name="trans_date" class="datepicker" alt="sql_date" required>
			</section>
			<section>
				<h4>Issue State</h4>
				<select name="state" required>
					<option value =\" \"></option>
					<option value ="AL">Alabama</option>
					<option value ="AK">Alaska</option>
					<option value ="AZ">Arizona</option>
					<option value ="AR">Arkansas</option>
					<option value ="CA">California</option>
					<option value ="CO">Colorado</option>
					<option value ="CT">Connecticut</option>
					<option value ="DE">Delaware</option>
					<option value ="FL">Florida</option>
					<option value ="GA">Georgia</option>
					<option value ="HI">Hawaii</option>
					<option value ="ID">Idaho</option>
					<option value ="IL">Illinois</option>
					<option value ="IN">Indiana</option>
					<option value ="IA">Iowa</option>
					<option value ="KS">Kansas</option>
					<option value ="KY">Kentucky</option>
					<option value ="LA">Louisiana</option>
					<option value ="ME">Maine</option>
					<option value ="MD">Maryland</option>
					<option value ="MA">Massachusetts</option>
					<option value ="MI">Michigan</option>
					<option value ="MN">Minnesota</option>
					<option value ="MS">Mississippi</option>
					<option value ="MO">Missouri</option>
					<option value ="MT">Montana</option>
					<option value ="NE">Nebraska</option>
					<option value ="NV">Nevada</option>
					<option value ="NH">New Hampshire</option>
					<option value ="NJ">New Jersey</option>
					<option value ="NM">New Mexico</option>
					<option value ="NY">New York</option>
					<option value ="NC">North Carolina</option>
					<option value ="ND">North Dakota</option>
					<option value ="OH">Ohio</option>
					<option value ="OK">Oklahoma</option>
					<option value ="OR">Oregon</option>
					<option value ="PA">Pennsylvania</option>
					<option value ="RI">Rhode Island</option>
					<option value ="SC">South Carolina</option>
					<option value ="SD">South Dakota</option>
					<option value ="TN">Tennessee</option>
					<option value ="TX">Texas</option>
					<option value ="UT">Utah</option>
					<option value ="VT">Vermont</option>
					<option value ="VA">Virginia</option>
					<option value ="WA">Washington</option>
					<option value ="WV">West Virginia</option>
					<option value ="WI">Wisconsin</option>
					<option value ="WY">Wyoming</option>
				</select>
			</section>
			<section>
				<h4>Premium</h4>
				<input type="text" class="premium_amount" id="comp_base" name="comp_base" class="contract_level" required>
			</section>
			<section>
				<h4>Surrender?</h4>
				<input name="charge_back" class="checkbox" type="checkbox" value="1">
			</section>
			
			<h2>Agent Information</h2>
			<section>
				<h4>Writing Code</h4>
				<input type="text" class="writing_code" name="agent_wc" required>
			</section>
			
			<h2>Client Information</h2>
			<section>
				<h4>First Name</h4>
				<input type="text" name="client_first" required>
			</section>
			<section>
				<h4>Last Name</h4>
				<input type="text" name="client_last" required>
			</section>
			<section>
				<h4>Issue Age</h4>
				<input type="text" class="contract_level" name="client_age" size="2" maxlength="2" required>
			</section>
			
			<h2>Carrier Information</h2>
			<section>
				<h4>Carrier Name</h4>
				<select name="carrier_name" required>
						<option value=\" \"></option>
<?php

// Lists Carriers from database
$ci = 0;
while ($ci < $cnum){
	
	$carrier_name = mysql_result($carriers_query, $ci);
	echo '<option value="'.$carrier_name.'">'.$carrier_name.'</option>';
	
	$ci++;
}

?>
				</select>
			</section>
			<section>
				<h4>Product/Plan</h4>
				<select name="product_name" required>
					<option value=\" \"></option>
<?php

// Lists Products from database
$pi = 0;
while ($pi < $pnum){
	
	$product_name = mysql_result($product_query,$pi,'Product Name');
	$product_id = mysql_result($product_query,$pi,'Product ID');
	echo '<option value="'.$product_id.'">'.$product_name.'</option>';
	
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
					<input type="text" class="premium_amount" name="reported_amt" required>
				</section>
				<section>
					<h4>Option</h4>
					<input type="text" class="contract_level" name="option">
				</section>
				<h2></h2>
				<section>
					<input class='add_button' type='submit' name='edit' value='Submit'>
				</section>
			</form>
		</section>
	</div>	
</div>

</body>
</html>