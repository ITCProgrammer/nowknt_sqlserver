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
$conn = db2_connect($conn_string, "", "");

if (!$conn) {
    echo json_encode(["error" => "Failed to connect to DB2: " . db2_conn_errormsg()], JSON_PRETTY_PRINT);
    exit;
}

// Query SQL
$sql = "WITH RankedMachines AS (
    SELECT 
        ADSTORAGE.VALUESTRING AS NO_MESIN,
        COUNT(CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) = '2025-03-03' THEN 1 END) AS \"2025-03-03\",
        COUNT(CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) = '2025-03-04' THEN 1 END) AS \"2025-03-04\",
        COUNT(CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) = '2025-03-05' THEN 1 END) AS \"2025-03-05\",
        COUNT(CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) = '2025-03-06' THEN 1 END) AS \"2025-03-06\",
        COUNT(CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) = '2025-03-07' THEN 1 END) AS \"2025-03-07\",
        SUM(
            CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) IN ('2025-03-03', '2025-03-04', '2025-03-05', '2025-03-06', '2025-03-07') 
            THEN 1 
            ELSE 0 
            END
        ) AS TOTAL,
        ROW_NUMBER() OVER (ORDER BY SUM(
            CASE WHEN SUBSTR(INSPECTIONSTARTDATETIME, 1, 10) IN ('2025-03-03', '2025-03-04', '2025-03-05', '2025-03-06', '2025-03-07') 
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
        AND INSPECTIONSTARTDATETIME BETWEEN '2025-03-03-07:00:00' AND '2025-03-07-07:00:00'
    GROUP BY ADSTORAGE.VALUESTRING
)
SELECT NO_MESIN, \"2025-03-03\", \"2025-03-04\", \"2025-03-05\", \"2025-03-06\", \"2025-03-07\", TOTAL
FROM RankedMachines
WHERE Rank <= 5
ORDER BY Rank;";

// Eksekusi Query
$result = db2_exec($conn, $sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . db2_stmt_errormsg()], JSON_PRETTY_PRINT);
    exit;
}

// Menyiapkan array untuk struktur Chart.js
$labels = ["2025-03-03", "2025-03-04", "2025-03-05", "2025-03-06", "2025-03-07"];
$datasets = [];

$colors = ["#1f497d", "#e67e22", "#3498db", "#2c7b4f", "#8e44ad", "#27ae60"];
$index = 0;

// Fetch data
while ($row = db2_fetch_assoc($result)) {
    $datasets[] = [
        "label" => $row["NO_MESIN"],
        "data" => [
            intval($row["2025-03-03"]),
            intval($row["2025-03-04"]),
            intval($row["2025-03-05"]),
            intval($row["2025-03-06"]),
            intval($row["2025-03-07"])
        ],
        "backgroundColor" => $colors[$index % count($colors)]
    ];
    $index++;
}

// Hasil JSON
$response = [
    "labels" => $labels,
    "datasets" => $datasets
];

// Output JSON
echo json_encode($response, JSON_PRETTY_PRINT);

// Tutup koneksi
db2_close($conn);
?>
