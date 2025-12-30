<?php
    header("content-type:application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=KNT_Schedule_ALL" . date('Y-m-d') . ".xls");
    header('Cache-Control: max-age=0');
?>
<?php
    require_once '../koneksi.php';

    function getSingleValue($conn1, $sql, $field) {
        $stmt = db2_exec($conn1, $sql);

        if ($stmt === false) {
            echo "<pre style='color:red'>DB2 ERROR in getSingleValue():\nSQL: $sql\nERROR: " . db2_stmt_errormsg() . "</pre>";
            return null;
        }

        $row = db2_fetch_assoc($stmt);
        return $row[$field] ?? null;
    }

    function getRow($conn1, $sql) {
        $stmt = db2_exec($conn1, $sql);

        if ($stmt === false) {
            echo "<pre style='color:red'>DB2 ERROR in getRow():\nSQL: $sql\nERROR: " . db2_stmt_errormsg() . "</pre>";
            return null;
        }

        return db2_fetch_assoc($stmt);
    }

    function getTerimaOrder($conn1, $project) {
        $sql = "SELECT 
                    LISTAGG(DISTINCT ORDERDATE, ', ') AS TERIMAORDER
                FROM 
                    PRODUCTIONDEMAND p
                WHERE 
                    ORIGDLVSALORDLINESALORDERCODE = '$project'
                    AND p.ITEMTYPEAFICODE = 'KGF'
                    AND p.PROGRESSSTATUS <> '6'
                    AND p.ORIGDLVSALORDLINESALORDERCODE IS NOT NULL";
        return getSingleValue($conn1, $sql, 'TERIMAORDER') ?? '';
    }

    function getTotalMachine($conn1, $project, $s1,$s2,$s3,$s4) {
        $sql = "SELECT
                    SUM(CASE WHEN INSPEK.KGPAKAI >0 THEN 1 ELSE 0 END) AS TOTMC
                FROM
                    ITXVIEWKNTORDER
                LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
                LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME = 'MachineNo'
                LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD6.FIELDNAME = 'StatusOper'
                LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD8 ON AD8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD8.FIELDNAME = 'StatusMesin'
                LEFT OUTER JOIN ( 
                            SELECT COUNT(WEIGHTREALNET ) AS KGPAKAI,DEMANDCODE FROM 
                            ELEMENTSINSPECTION WHERE ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100'
                            GROUP BY DEMANDCODE
                            ) INSPEK ON INSPEK.DEMANDCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE	
                WHERE
                    PRODUCTIONDEMAND.ITEMTYPEAFICODE = 'KGF'
                    AND (PRODUCTIONDEMAND.PROGRESSSTATUS = '0'
                        OR PRODUCTIONDEMAND.PROGRESSSTATUS = '1'
                        OR PRODUCTIONDEMAND.PROGRESSSTATUS = '2'
                        OR AD6.VALUESTRING = '1')
                    AND ITXVIEWKNTORDER.PROGRESSSTATUS IN('2', '6')
                    AND AD8.VALUESTRING IN('0', '1')
                    AND ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE = '$project'
                    AND ITXVIEWKNTORDER.SUBCODE01 = '$s1'
                    AND ITXVIEWKNTORDER.SUBCODE02 = '$s2'
                    AND ITXVIEWKNTORDER.SUBCODE03 = '$s3'
                    AND ITXVIEWKNTORDER.SUBCODE04 = '$s4'";
        return getSingleValue($conn1, $sql, 'TOTMC') ?? 0;
    }

    function getSeason($conn1, $project) {
        $sql = "SELECT STATISTICALGROUPCODE FROM SALESORDER WHERE CODE = '$project'";
        $val = getSingleValue($conn1, $sql, 'STATISTICALGROUPCODE');
        return $val ?? "<span style='font-size: 9px;background:#ffebee;color:#c62828;border-radius:3px;border-left:3px solid #c62828;'>⚠ Data not found. Check Sales Order</span>";
    }

    function getStockTransaction($conn1, $project, $s1, $s2, $s3, $s4) {
        $sql = "SELECT 
                SUM(USERPRIMARYQUANTITY) AS QTY
                FROM 
                    STOCKTRANSACTION
                WHERE 
                    ITEMTYPECODE='KGF' AND LOGICALWAREHOUSECODE='M021'
                    AND TEMPLATECODE='204'
                    AND PROJECTCODE='$project'
                    AND DECOSUBCODE01='$s1'
                    AND DECOSUBCODE02='$s2'
                    AND DECOSUBCODE03='$s3'
                    AND DECOSUBCODE04='$s4'";
        return getSingleValue($conn1, $sql, 'QTY') ?? 0;
    }

    function getBalance($conn1, $warehouse, $project, $s1, $s2, $s3, $s4) {
        $sql = "SELECT 
                    SUM(BASEPRIMARYQUANTITYUNIT) AS QTY
                FROM 
                    BALANCE
                WHERE 
                    ITEMTYPECODE='KGF'
                    AND LOGICALWAREHOUSECODE='$warehouse'
                    AND PROJECTCODE='$project'
                    AND DECOSUBCODE01='$s1'
                    AND DECOSUBCODE02='$s2'
                    AND DECOSUBCODE03='$s3'
                    AND DECOSUBCODE04='$s4'";
        return getSingleValue($conn1, $sql, 'QTY') ?? 0;
    }

    function calcEstimasi($rowMain, $totMc) {
        if ($totMc <= 0) {
            return [ "0", "0", "0", "0" ];
        }

        $capacity = $totMc * $rowMain['STD_QTY_PERMACHINE'];

        $persen = floor(round($rowMain['QTY_PRODUC_YESTERDAY'] / $capacity, 2) * 100);
        $need = ceil(($rowMain['TOTAL_QTY'] - $rowMain['PRODUCED']) / $capacity);
        $need = max(0, $need);
        $delivery = (new DateTime())->modify("+$need days")->format("Y-m-d");

        return [$capacity, $persen, $need, $delivery];
    }
?>
<table border="1">
    <thead>
        <tr>
            <th style="text-align: center">Terima Order</th>
            <th style="text-align: center">Langganan</th>
            <th style="text-align: center">Kategori</th>
            <th style="text-align: center">Season</th>
            <th style="text-align: center">Project</th>
            <th style="text-align: center">Ukuran Mesin</th>
            <th style="text-align: center">Article Code</th>
            <th style="text-align: center">Machine Type</th>
            <th style="text-align: center">Total Qty</th>
            <th style="text-align: center">Total Mesin</th>
            <th style="text-align: center">Std Qty per machine</th>
            <th style="text-align: center">Capacity per day</th>
            <th style="text-align: center">Qty Prod. Yesterday<br><?= date('Y-m-d', strtotime('-1 day')); ?></th>
            <th style="text-align: center">% Target</th>
            <th style="text-align: center">Produced</th>
            <th style="text-align: center">Belum Rajut</th>
            <th style="text-align: center">Persentase Setelah Produksi</th>
            <th style="text-align: center">Digudang</th>
            <th style="text-align: center">Sisa Stock digudang</th>
            <th style="text-align: center">Belum Kirim KNT</th>
            <th style="text-align: center">Estimate Complete</th>
            <th style="text-align: center">Estimated Delivery</th>
            <th style="text-align: center">Delivery Actual</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $dataMain = "SELECT 
                            DISTINCT 
                            LANGGANAN,
                            KATEGORI,
                            PROJECT,
                            ORIGINAL_PDCODE,
                            ARTICLE_CODE,
                            UKURAN_MESIN,
                            TOTAL_QTY,
                            MACHINE,
                            TOTAL_MACHINE,
                            STD_QTY_PERMACHINE,
                            CAPACITY_PERDAY,
                            QTY_PRODUC_YESTERDAY,
                            PERSENTASE_TARGET,
                            PRODUCED,
                            BELUM_RAJUT,
                            PERSENTASE_SETELAH_PROD,
                            ESTIMATED_COMPLETED,
                            ESTIMATED_DELIVERY,
                            DELIVERY_ACTUAL,
                            SUBCODE01,
                            SUBCODE02,
                            SUBCODE03,
                            SUBCODE04 
                        FROM 
                            ITXVIEWSCHEDULE_KNITING_ALL 
                        WHERE 
                            CREATIONDATETIME BETWEEN '$Awal' AND '$Akhir'";
            $queryMain = db2_exec($conn1, $dataMain);

            while ($rowMain = db2_fetch_assoc($queryMain)):
                $project = TRIM($rowMain['PROJECT']);
                $article = TRIM($rowMain['ARTICLE_CODE']);

                $s1 = TRIM($rowMain['SUBCODE01']);
                $s2 = TRIM($rowMain['SUBCODE02']);
                $s3 = TRIM($rowMain['SUBCODE03']);
                $s4 = TRIM($rowMain['SUBCODE04']);

                $terimaOrder = getTerimaOrder($conn1, $project);
                $totMc       = getTotalMachine($conn1, $project, $s1,$s2,$s3,$s4);
                $season      = getSeason($conn1, $project);

                $qtyDigudang       = getStockTransaction($conn1, $project, $s1,$s2,$s3,$s4);
                $qtySisaDigudang   = getBalance($conn1, 'M021', $project, $s1,$s2,$s3,$s4);
                $qtyBelumKirimKnt  = getBalance($conn1, 'M502', $project, $s1,$s2,$s3,$s4);

                list($capacity, $persenTarget, $daysNeeded, $dateDelivery) = calcEstimasi($rowMain, $totMc);
        ?>
                <tr>
                    <td><?= $terimaOrder ?></td>
                    <td><?= $rowMain['LANGGANAN'] ?></td>
                    <td><?= $rowMain['KATEGORI'] ?></td>
                    <td><?= $season ?></td>
                    <td><?= $project ?></td>
                    <td><?= $rowMain['UKURAN_MESIN'] ?></td>
                    <td><?= $rowMain['ARTICLE_CODE'] ?></td>
                    <td><?= $rowMain['MACHINE'] ?></td>

                    <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? number_format($rowMain['TOTAL_QTY'],2) : 0 ?></td>

                    <td><?= $totMc ?></td>
                    <td><?= number_format($rowMain['STD_QTY_PERMACHINE'],0) ?></td>
                    <td><?= number_format($capacity,0) ?></td>

                    <td><?= number_format($rowMain['QTY_PRODUC_YESTERDAY'],2) ?></td>

                    <td><?= $persenTarget !== "" ? $persenTarget . " %" : "" ?></td>

                    <td><?= number_format($rowMain['PRODUCED'],2) ?></td>
                    <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? number_format($rowMain['BELUM_RAJUT'],2) : 0 ?></td>
                    <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? $rowMain['PERSENTASE_SETELAH_PROD'] : 0 ?></td>

                    <td><?= number_format($qtyDigudang,2) ?></td>
                    <td><?= number_format($qtySisaDigudang,2) ?></td>
                    <td><?= number_format($qtyBelumKirimKnt,2) ?></td>

                    <td><?= ($totMc > 0 && empty($rowMain['ORIGINAL_PDCODE'])) ? $daysNeeded : '' ?></td>
                    <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? $dateDelivery : '' ?></td>
                    <td><?= ($totMc > 0 && empty($rowMain['ORIGINAL_PDCODE'])) ? $rowMain['DELIVERY_ACTUAL'] : '' ?></td>
                </tr>
            <?php endwhile; ?>
    </tbody>
</table>