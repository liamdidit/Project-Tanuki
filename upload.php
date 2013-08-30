<?php

/********************************/
/* Code at http://legend.ws/blog/tips-tricks/csv-php-mysql-import/
/* Edit the entries below to reflect the appropriate values
/********************************/
$databasehost = "localhost";
$databasename = "comp_database";
$databaseusername ="web";
$databasepassword = "Vague7even";
$fieldseparator = ",";
$lineseparator = "\r";
$csvfile = $_FILES['file']['tmp_name'];
$carrier_id = $_POST['carrier'];
/********************************/
/* Would you like to add an ampty field at the beginning of these records?
/* This is useful if you have a table with the first field being an auto_increment integer
/* and the csv file does not have such as empty field before the records.
/* Set 1 for yes and 0 for no. ATTENTION: don't set to 1 if you are not sure.
/* This can dump data in the wrong fields if this extra field does not exist in the table
/********************************/
$addauto = 0;
/********************************/
/* Would you like to save the mysql queries in a file? If yes set $save to 1.
/* Permission on the file should be set to 777. Either upload a sample file through ftp and
/* change the permissions, or execute at the prompt: touch output.sql && chmod 777 output.sql
/********************************/
$save = 0;
$outputfile = "output.sql";
/********************************/


if(!file_exists($csvfile)) {
	echo "File not found. Make sure you specified the correct path.\n";
	exit;
}

$file = fopen($csvfile,"r");

if(!$file) {
	echo "Error opening data file.\n";
	exit;
}

$size = filesize($csvfile);

if(!$size) {
	echo "File is empty.\n";
	exit;
}

$csvcontent = fread($file,$size);

fclose($file);

$con = @mysql_connect($databasehost,$databaseusername,$databasepassword) or die(mysql_error());
@mysql_select_db($databasename) or die(mysql_error());

$lines = 0;
$queries = "";
$linearray = array();

foreach(split($lineseparator,$csvcontent) as $line) {

	$lines++;
	$line = trim($line," \t");
	$line = str_replace("\r","",$line);
	$linearray = explode($fieldseparator,$line);
	$linemysql = implode("','",$linearray);
	
	if($carrier_id == '10') {
		$date_convert_trans = mysql_real_escape_string($linearray[10]);
		$date_convert_trans = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_trans)));
		
		$date_convert_eff = mysql_real_escape_string($linearray[11]);
		$date_convert_eff = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_eff)));
		
		if($linearray[9] == 'Cancelled') {
			$chargeback = '1';
		} else {
			$chargeback = '0';
		}
		
		$product_query = mysql_query("SELECT products.id FROM products WHERE products.carrier_id = '$carrier_id' AND products.codes LIKE '%".$linearray[5]."%' LIMIT 1");
		$product_id = mysql_result($product_query,0,'products.id');
		
		if($product_id == NULL) {
			$product_id = '0';
		}
		
		$premium_base = preg_replace('/[\$,]/', '', $linearray[12]);
		$premium_base = floatval($premium_base);
		$premium_base = number_format($premium_base, 2, '.', '');
		
		$paid_amt = preg_replace('/[\$,]/', '', $linearray[13]);
		$paid_amt = floatval($paid_amt);
		$paid_amt = number_format($paid_amt, 2, '.', '');
		
		$client_name_array = explode(' ',$linearray[6]);
		$client_first = $client_name_array[0];
		$client_last = end($client_name_array);
		
		$client_first = ucfirst(strtolower($client_first));
		$client_last = ucfirst(strtolower($client_last));
		
		$query = "INSERT INTO policies
			VALUES ('','$linearray[3]','$date_convert_trans','$client_first','$client_last','$linearray[7]','$carrier_id','$product_id','$linearray[4]','$premium_base',
					'$paid_amt','$linearray[8]','$date_convert_eff','$chargeback','')";
	} elseif($carrier_id == '3') {
		$date_convert_trans = mysql_real_escape_string($linearray[17]);
		$date_convert_trans = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_trans)));
		
		$date_convert_eff = mysql_real_escape_string($linearray[17]);
		$date_convert_eff = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_eff)));
		
		if($linearray[37] == 'Chbk Cancel  ') {
			$chargeback = '1';
		} else {
			$chargeback = '0';
		}
		
		$product_name = preg_replace('/\s+/', '', $linearray[22]);
		
		$product_query = mysql_query("SELECT products.id FROM products WHERE products.carrier_id = '$carrier_id' AND products.codes LIKE '%".$product_name."%' LIMIT 1");
		$product_id = mysql_result($product_query,0,'products.id');
		
		if($product_id == NULL) {
			$product_id = '0';
		}
		
		$premium_base = preg_replace('/[\$,]/', '', $linearray[18]);
		$premium_base = floatval($premium_base);
		$premium_base_dec = substr($premium_base, -2, 2);
		$premium_base = substr_replace($premium_base, '.', -2, 2).$premium_base_dec;
		
		$paid_amt = preg_replace('/[\$,]/', '', $linearray[27]);
		$paid_amt = floatval($paid_amt);
		$paid_amt_dec = substr($paid_amt, -2, 2);
		$paid_amt = substr_replace($paid_amt, '.', -2, 2).$paid_amt_dec;
		
		$client_first = $linearray[3];
		$client_last = $linearray[2];
		
		$client_first = ucfirst(strtolower($client_first));
		$client_first = preg_replace('/\s+/', '', $client_first);
		
		$client_last = ucfirst(strtolower($client_last));
		$client_last = preg_replace('/\s+/', '', $client_last);
		
		$agent_wc = preg_replace('/\s+/', '', $linearray[15]);
		
		$query = "INSERT INTO policies
			VALUES ('','$agent_wc','$date_convert_trans','$client_first','$client_last','$linearray[21]','$carrier_id','$product_id','$linearray[1]','$premium_base',
					'$paid_amt','$linearray[9]','$date_convert_eff','$chargeback','')";
	} elseif($carrier_id == '13') {
		$date_convert_trans = mysql_real_escape_string($linearray[0]);
		$date_convert_trans = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_trans)));
		
		$date_convert_eff = mysql_real_escape_string($linearray[23]);
		$date_convert_eff = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_eff)));
		
		if($linearray[1] == 'Commission Chargeback') {
			$chargeback = '1';
		} else {
			$chargeback = '0';
		}
		
		$product_name = $linearray[8];
		
		$product_query = mysql_query("SELECT products.id FROM products WHERE products.carrier_id = '$carrier_id' AND products.codes LIKE '%".$product_name."%' LIMIT 1");
		$product_id = mysql_result($product_query,0,'products.id');
		
		if($product_id == NULL) {
			$product_id = '0';
		}
		
		$premium_base = preg_replace('/[\$,]/', '', $linearray[19]);
		$premium_base = floatval($premium_base);
		$premium_base = number_format($premium_base, 2, '.', '');
		
		$paid_amt = preg_replace('/[\$,]/', '', $linearray[21]);
		$paid_amt = floatval($paid_amt);
		$paid_amt = number_format($paid_amt, 2, '.', '');
		
		$client_first = $linearray[7];
		$client_last = $linearray[6];
		
		$client_first = ucfirst(strtolower($client_first));
		$client_first = preg_replace('/\s+/', '', $client_first);
		
		$client_last = ucfirst(strtolower($client_last));
		$client_last = preg_replace('/\s+/', '', $client_last);
		
		$agent_wc = preg_replace('/\s+/', '', $linearray[9]);
		
		$query = "INSERT INTO policies
			VALUES ('','$agent_wc','$date_convert_trans','$client_first','$client_last','60','$carrier_id','$product_id','$linearray[3]','$premium_base',
					'$paid_amt','$linearray[27]','$date_convert_eff','$chargeback','$linearray[31]')";
	} elseif($carrier_id == '1') {
		$date_convert_trans = mysql_real_escape_string($linearray[18]);
		$date_convert_trans = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_trans)));
		
		$date_convert_eff = mysql_real_escape_string($linearray[19]);
		$date_convert_eff = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_eff)));
		
		if($linearray[5] == 'Chbk Earned Tar') {
			$chargeback = '1';
		} else {
			$chargeback = '0';
		}
		
		$product_name = $linearray[3];
		
		$product_query = mysql_query("SELECT (products.carrier_id)as 'True Carrier ID', (products.id)as 'Product ID' FROM products WHERE (products.carrier_id = '$carrier_id' OR products.carrier_id = '32') AND products.codes LIKE '%".$product_name."%' LIMIT 1");
		$product_id = mysql_result($product_query,0,'Product ID');
		$carrier_id_true = mysql_result($product_query,0,'True Carrier ID');
		
		if($product_id == NULL) {
			$product_id = '0';
		}
		
		$premium_base = preg_replace('/[\$,]/', '', $linearray[8]);
		$premium_base = floatval($premium_base);
		$premium_base = number_format($premium_base, 2, '.', '');
		
		$paid_amt = preg_replace('/[\$,]/', '', $linearray[12]);
		$paid_amt = floatval($paid_amt);
		$paid_amt = number_format($paid_amt, 2, '.', '');
		
		$client_first = $linearray[2];
		$client_first = preg_replace('/\"/', '', $client_first);
		
		$client_last = $linearray[1];
		$client_last = preg_replace('/\"/', '', $client_last);
		
		$query = "INSERT INTO policies
			VALUES ('','$linearray[14]','$date_convert_trans','$client_first','$client_last','$linearray[20]','$carrier_id_true','$product_id','$linearray[0]','$premium_base',
					'$paid_amt','$linearray[17]','$date_convert_eff','$chargeback','')";
	} elseif($carrier_id == '16') {
		if($linearray[23] > 1){
			continue;
		} else {
			$date_convert_trans = mysql_real_escape_string($linearray[0]);
			$date_convert_trans = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_trans)));
			
			$date_convert_eff = mysql_real_escape_string($linearray[12]);
			$date_convert_eff = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_eff)));
			
			$product_code = $linearray[15];
			
			$product_query = mysql_query("SELECT (products.id)as 'Product ID' FROM products WHERE (products.carrier_id = '$carrier_id') AND products.codes LIKE '%".$product_code."%' LIMIT 1");
			$product_id = mysql_result($product_query,0,'Product ID');
			
			if($product_id == NULL) {
				$product_id = '0';
			}
			
			$premium_base = preg_replace('/\"/', '', $linearray[9]);
			$premium_base = floatval($premium_base);
			$premium_base = number_format($premium_base, 2, '.', '');
			
			$paid_amt = preg_replace('/\"/', '', $linearray[11]);
			$paid_amt = floatval($paid_amt);
			$paid_amt = number_format($paid_amt, 2, '.', '');
			
			$client_first_array = explode(' ',$linearray[4]);
			$client_first = preg_replace('/\"/', '', $client_first_array[1]);
			$client_first = ucfirst(strtolower($client_first));
			
			$client_last = $linearray[3];
			$client_last = preg_replace('/\"/', '', $client_last);
			$client_last = ucfirst(strtolower($client_last));
			
			$query = "INSERT INTO policies
				VALUES ('','$linearray[6]','$date_convert_trans','$client_first','$client_last','$linearray[21]','$carrier_id','$product_id','$linearray[2]','$premium_base',
						'$paid_amt','$linearray[27]','$date_convert_eff','','')";
		}
	} elseif($carrier_id == '6') {
		$date_convert_trans = mysql_real_escape_string($linearray[9]);
		$date_convert_trans = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_trans)));
		
		$date_convert_eff = mysql_real_escape_string($linearray[4]);
		$date_convert_eff = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_eff)));
		
		$product_code = $linearray[5].' '.$linearray[6];
		$product_query = mysql_query("SELECT (products.id)as 'Product ID' FROM products WHERE (products.carrier_id = '$carrier_id') AND products.codes LIKE '%".$product_code."%' LIMIT 1");
		$product_id = mysql_result($product_query,0,'Product ID');
		
		if($product_id == NULL) {
			$product_id = '0';
		}
		
		$premium_base = preg_replace('/[\$,]/', '', $linearray[13]);
		$premium_base = floatval($premium_base);
		$premium_base = number_format($premium_base, 2, '.', '');
		
		$paid_amt = preg_replace('/[\$,]/', '', $linearray[18]);
		$paid_amt = floatval($paid_amt);
		$paid_amt = number_format($paid_amt, 2, '.', '');
		
		$client_first = $linearray[1];
		$client_first = preg_replace('/\"/', '', $client_first);
		
		$client_last = $linearray[2];
		$client_last = preg_replace('/\"/', '', $client_last);
		
		$agent_wc = $linearray[23];
		
		if($agent_wc == '-') {
			$agent_wc = $linearray[24];
		}
		
		$query = "INSERT INTO policies
			VALUES ('','$agent_wc','$date_convert_trans','$client_first','$client_last','$linearray[3]','$carrier_id','$product_id','$linearray[0]',
				'$premium_base','$paid_amt','','$date_convert_eff','','')";
	} elseif($carrier_id == '28') {
		$date_convert_trans = mysql_real_escape_string($linearray[9]);
		$date_convert_trans = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_trans)));
		
		$date_convert_eff = mysql_real_escape_string($linearray[4]);
		$date_convert_eff = date('Y-m-d', strtotime(str_replace('-', '/', $date_convert_eff)));
		
		$product_code = $linearray[5].' '.$linearray[6];
		$product_query = mysql_query("SELECT (products.id)as 'Product ID' FROM products WHERE (products.carrier_id = '$carrier_id') AND products.codes LIKE '%".$product_code."%' LIMIT 1");
		$product_id = mysql_result($product_query,0,'Product ID');
		
		if($product_id == NULL) {
			$product_id = '0';
		}
		
		$premium_base = preg_replace('/[\$,]/', '', $linearray[13]);
		$premium_base = floatval($premium_base);
		$premium_base = number_format($premium_base, 2, '.', '');
		
		$paid_amt = preg_replace('/[\$,]/', '', $linearray[18]);
		$paid_amt = floatval($paid_amt);
		$paid_amt = number_format($paid_amt, 2, '.', '');
		
		$client_first = $linearray[1];
		$client_first = preg_replace('/\"/', '', $client_first);
		
		$client_last = $linearray[2];
		$client_last = preg_replace('/\"/', '', $client_last);
		
		$agent_wc = $linearray[23];
		
		if($agent_wc == '-') {
			$agent_wc = $linearray[24];
		}
		
		$query = "INSERT INTO policies
			VALUES ('','$agent_wc','$date_convert_trans','$client_first','$client_last','$linearray[3]','$carrier_id','$product_id','$linearray[0]',
				'$premium_base','$paid_amt','','$date_convert_eff','','')";
	} else {
		echo "Please select a carrier.";
	}
	
	$queries .= $query . "\n";

	@mysql_query($query);
}

@mysql_close($con);

if($save) {
	
	if(!is_writable($outputfile)) {
		echo "File is not writable, check permissions.\n";
	}
	
	else {
		$file2 = fopen($outputfile,"w");
		
		if(!$file2) {
			echo "Error writing to the output file.\n";
		}
		else {
			fwrite($file2,$queries);
			fclose($file2);
		}
	}
	
}

echo "Found a total of $lines records in this csv file.<br />";

echo $product_name;

?>
