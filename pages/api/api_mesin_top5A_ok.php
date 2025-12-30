<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Konfigurasi koneksi DB2
$hostname = "10.0.0.21";
$database = "NOWPRD";
$user = "db2admin";
$passworddb2 = "Sunkam@24809";
$port = "25000";

$conn_string = "DATABASE=$database; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2;";

// Koneksi ke DB2
$conn = db2_connect($conn_string, "", "");

if (!$conn) {
    echo json_encode(["error" => "Failed to connect to DB2: " . db2_conn_errormsg()], JSON_PRETTY_PRINT);
    exit;
}

// Mendapatkan tanggal hari ini dan 5 hari sebelumnya
$today = date('Y-m-d'); // Tanggal hari ini
$dates = [];

for ($i = 0; $i < 5; $i++) {
    $dates[] = date('Y-m-d', strtotime("-$i days", strtotime($today)));
}

// Buat string tanggal untuk digunakan dalam SQL dengan format yang benar
$date_conditions = implode(", ", array_map(fn($date) => "'$date'", $dates));
$date_columns = implode(", ", array_map(fn($date) => "COUNT(CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) = '$date' THEN 1 END) AS \"$date\"", $dates));

// Query SQL dengan nama kolom yang benar
$sql = "WITH RankedMachines AS (
    SELECT 
        ADSTORAGE.VALUESTRING AS NO_MESIN,
        $date_columns,
        SUM(
            CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) IN ($date_conditions) 
            THEN 1 
            ELSE 0 
            END
        ) AS TOTAL,
        ROW_NUMBER() OVER (ORDER BY SUM(
            CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) IN ($date_conditions) 
            THEN 1 
            ELSE 0 
            END
        ) DESC) AS Rank
    FROM 
        ELEMENTSINSPECTION
    LEFT OUTER JOIN DB2ADMIN.INITIALS ON INITIALS.CODE = ELEMENTSINSPECTION.OPERATORCODE
    LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
    LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME = 'MachineNo'
    WHERE 
        ELEMENTITEMTYPECODE = 'KGF' 
        AND QUALITYREASONCODE IN ('V00', 'V02', 'V03', 'H00', 'H02', 'H03', 'J00', 'J02') 
        AND INSPECTIONSTARTDATETIME BETWEEN '" . $dates[4] . "-07:00:00' AND '$today-07:00:00'
    GROUP BY ADSTORAGE.VALUESTRING
)
SELECT NO_MESIN, " . implode(", ", array_map(fn($date) => "\"$date\"", $dates)) . ", TOTAL
FROM RankedMachines
WHERE Rank <= 5
ORDER BY Rank;";

// Eksekusi Query
$result = db2_exec($conn, $sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . db2_stmt_errormsg()], JSON_PRETTY_PRINT);
    exit;
}

// Fetch Data
$data = [];
while ($row = db2_fetch_assoc($result)) {
    $data[] = $row;
}

// Output JSON
echo json_encode($data, JSON_PRETTY_PRINT);

// Tutup Koneksi
db2_close($conn);
?>
