<?php
date_default_timezone_set('Asia/Jakarta');

$hostname="10.0.0.21";
$database = "NOWPRD";
$user = "db2admin";
$passworddb2 = "Sunkam@24809";
$port="25000";
$conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
$conn1 = db2_pconnect($conn_string,'', '');

if($conn1) {
	db2_close($conn1);
}
else{
    exit("DB2 Connection failed");
    }

?>