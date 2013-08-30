<?php

$uname = "web";
$pword = "Vague7even";
$db = "comp_database";

mysql_connect('localhost',$uname,$pword);

@mysql_select_db($db) or die("Unable to select database");

$agencyname = 'John, Prete & Bartolotta';
$carriername = 'Lincoln Financial';
$awcresult = 
mysql_query("SELECT agencies.agencywc
FROM agencies
INNER JOIN writing_codes ON agencies.agencywc = writing_codes.agencywc
INNER JOIN carriers ON writing_codes.carrierid = carriers.id
WHERE carriers.carriername = '$carriername'
AND agencies.agencyname = '$agencyname'");
$agencywc = mysql_result($awcresult, 0);

mysql_close();

echo "$agencywc";

?>