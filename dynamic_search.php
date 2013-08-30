<?php

if (!isset($_REQUEST['term']) )
    exit;

$dblink = mysql_connect('localhost', 'web', 'Vague7even') or die( mysql_error() );
mysql_select_db('comp_database');

$rs = mysql_query('SELECT DISTINCT first_name, last_name
				FROM agents
			WHERE first_name LIKE "%'. mysql_real_escape_string($_REQUEST['term']) .'%"
				OR last_name LIKE "%'. mysql_real_escape_string($_REQUEST['term']) .'%"
			ORDER BY last_name ASC LIMIT 0,10', $dblink);

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['first_name'] .' '. $row['last_name'] ,
            'value' => $row['first_name'] .' '. $row['last_name']
        );
    }
}

echo json_encode($data);
flush();

?>