<?php
date_default_timezone_set('Asia/Jakarta');

$hostname="10.0.0.21";
$database = "NOWPRD";
$user = "db2admin";
$passworddb2 = "Sunkam@24809";
$port="25000";
$conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
$conn1 = db2_connect($conn_string,'', '');

if($conn1) {
}
else{
    exit("DB2 Connection failed");
    }

// SQL Server connections for dbknitt and nowprd
$hostSVR19     = "10.0.0.221";
$usernameSVR19 = "sa";
$passwordSVR19 = "Ind@taichen2024";

$sqlsrvDbknitt = array(
    "Database" => "dbknitt",
    "UID" => $usernameSVR19,
    "PWD" => $passwordSVR19,
    "ReturnDatesAsStrings" => true
);

$sqlsrvNowprd = array(
    "Database" => "nowprd",
    "UID" => $usernameSVR19,
    "PWD" => $passwordSVR19,
    "ReturnDatesAsStrings" => true
);

$con = sqlsrv_connect($hostSVR19, $sqlsrvDbknitt);
$con_nowprd = sqlsrv_connect($hostSVR19, $sqlsrvNowprd);

if ($con === false) {
    exit("SQL Server connection to dbknitt failed: " . print_r(sqlsrv_errors(), true));
}

if ($con_nowprd === false) {
    exit("SQL Server connection to nowprd failed: " . print_r(sqlsrv_errors(), true));
}

?>
