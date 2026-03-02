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

// Telescope/debug monitor target in existing SQL Server DB (dbknitt),
// but using a dedicated schema (see observability/debug_config.php).
$debugMonitorDatabase = "dbknitt";
$sqlsrvDebugMonitor = array(
    "Database" => $debugMonitorDatabase,
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

if (!function_exists('qcf_get_debug_monitor_conn')) {
    function qcf_get_debug_monitor_conn()
    {
        static $conn = null;
        if (is_resource($conn) || is_object($conn)) {
            return $conn;
        }

        global $hostSVR19, $sqlsrvDebugMonitor, $con, $con_nowprd;

        // Prefer dedicated observability DB connection.
        if (!empty($hostSVR19) && isset($sqlsrvDebugMonitor) && is_array($sqlsrvDebugMonitor)) {
            $reconnect = @sqlsrv_connect($hostSVR19, $sqlsrvDebugMonitor);
            if ($reconnect !== false) {
                $conn = $reconnect;
                return $conn;
            }
        }

        // Fallback to existing dbknitt connection.
        if (isset($con) && (is_resource($con) || is_object($con))) {
            $conn = $con;
            return $conn;
        }

        // Last fallback to nowprd connection.
        if (isset($con_nowprd) && (is_resource($con_nowprd) || is_object($con_nowprd))) {
            $conn = $con_nowprd;
            return $conn;
        }

        return null;
    }
}

if (!function_exists('qcf_get_db_dying_conn')) {
    function qcf_get_db_dying_conn()
    {
        // Backward-compat alias for observability bootstrap.
        return qcf_get_debug_monitor_conn();
    }
}

$qcfDebugBootstrap = __DIR__ . DIRECTORY_SEPARATOR . 'observability' . DIRECTORY_SEPARATOR . 'debug_bootstrap.php';
if (is_file($qcfDebugBootstrap)) {
    include_once $qcfDebugBootstrap;
}

?>
