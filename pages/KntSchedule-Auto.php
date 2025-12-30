<?php
    header("content-type:application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=KNT_Schedule_" . date('Y-m-d') . ".xls");
    header('Cache-Control: max-age=0');
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
            require_once '../koneksi.php';
            $dataMain   = "SELECT * FROM ITXVIEWSCHEDULE_KNITING";
            $queryMain  = db2_exec($conn1, $dataMain);
        ?>
        <?php while ($rowMain = db2_fetch_assoc($queryMain)) : ?>
            <?php
                $rTerimaOrder = "SELECT
                                    LISTAGG(DISTINCT ORDERDATE, ', ') AS TERIMAORDER 
                                FROM
                                    PRODUCTIONDEMAND p
                                WHERE
                                    ORIGDLVSALORDLINESALORDERCODE = '$rowMain[PROJECT]'
                                    AND p.ITEMTYPEAFICODE = 'KGF'
                                    AND NOT p.PROGRESSSTATUS = '6'
                                    AND NOT p.ORIGDLVSALORDLINESALORDERCODE IS NULL";
                $queryTerimaOrder = db2_exec($conn1, $rTerimaOrder);
                $rowTerimaOrder = db2_fetch_assoc($queryTerimaOrder);
                $dataTerimaOrder = $rowTerimaOrder['TERIMAORDER'] ?? '';
        
                $rTotMC = "SELECT
                                SUM(CASE WHEN INSPEK.KGPAKAI >0 THEN 1 ELSE 0 END) AS TOTMC
                            FROM
                                ITXVIEWKNTORDER
                            LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON
                                PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
                            LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON
                                ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
                                AND ADSTORAGE.NAMENAME = 'MachineNo'
                            LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON
                                AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
                                AND AD6.FIELDNAME = 'StatusOper'
                            LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD8 ON
                                AD8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
                                AND AD8.FIELDNAME = 'StatusMesin'
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
                                AND ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE = '$rowMain[PROJECT]'
                                AND CONCAT(CONCAT(CONCAT(RTRIM(ITXVIEWKNTORDER.SUBCODE01), ' '), CONCAT(RTRIM(ITXVIEWKNTORDER.SUBCODE02), RTRIM(ITXVIEWKNTORDER.SUBCODE03))), CONCAT(' ', RTRIM(ITXVIEWKNTORDER.SUBCODE04))) = '$rowMain[ARTICLE_CODE]'";
                $queryTotMC = db2_exec($conn1, $rTotMC);
                $rowTotMC = db2_fetch_assoc($queryTotMC);
                $dataTotMC = $rowTotMC['TOTMC'] ?? '0';
                $dataCapacity = $dataTotMC * $rowMain['STD_QTY_PERMACHINE'];
                if($dataCapacity>0){
                    $dataPersentaseTarget = FLOOR(ROUND($rowMain['QTY_PRODUC_YESTERDAY'] / $dataCapacity, 2) * 100);
                    $dataEstimated_Completed = CEIL($rowMain['TOTAL_QTY'] / $dataCapacity );
                    // Hitung jumlah hari produksi yang dibutuhkan
                    $daysNeeded = round(($rowMain['TOTAL_QTY'] - $rowMain['PRODUCED']) / $dataCapacity, 2);
                    $daysNeeded =ceil($daysNeeded);
                    // Kurangi 1 hari sesuai logika awal (- 1 DAY)
                    $daysNeeded = max(0, $daysNeeded);
                    // Tambahkan ke tanggal sekarang
                    $dataEstimated_Delivery = (new DateTime())->modify("+$daysNeeded days")->format('Y-m-d');                                    
                }else{
                    $dataPersentaseTarget = "";
                    $dataEstimated_Completed = "";
                    $dataEstimated_Delivery = "";
                }

                $rSeason = "SELECT
                                STATISTICALGROUPCODE
                            FROM
                                SALESORDER
                            WHERE
                                CODE = '$rowMain[PROJECT]'";
                $querySeason = db2_exec($conn1, $rSeason);
                $rowSeason = db2_fetch_assoc($querySeason);
                $dataSeason = $rowSeason['STATISTICALGROUPCODE'] ?? '<span style="font-size: 9px; background-color: #ffebee; color: #c62828;  border-radius: 3px; font-weight: 500; border-left: 3px solid #c62828;">⚠ Data not found. Check Sales Order</span>';

                $rDigudang = "SELECT 
                                    SUM(USERPRIMARYQUANTITY) AS QTY
                                FROM
                                    STOCKTRANSACTION
                                WHERE
                                    ITEMTYPECODE = 'KGF'
                                    AND LOGICALWAREHOUSECODE = 'M021'
                                    AND TEMPLATECODE = '204'
                                    AND PROJECTCODE = '$rowMain[PROJECT]'
                                    AND DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                    AND DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                    AND DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                    AND DECOSUBCODE04 = '$rowMain[SUBCODE04]'";
                $queryDigudang = db2_exec($conn1, $rDigudang);
                $rowDigudang = db2_fetch_assoc($queryDigudang);
                $dataDigudang = $rowDigudang['QTY'] ?? 0;

                $rSisaStockDigudang = "SELECT
                                            SUM(BASEPRIMARYQUANTITYUNIT) AS QTY
                                        FROM
                                            BALANCE
                                        WHERE
                                            ITEMTYPECODE = 'KGF'
                                            AND LOGICALWAREHOUSECODE = 'M021'
                                            AND PROJECTCODE = '$rowMain[PROJECT]'
                                            AND DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                            AND DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                            AND DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                            AND DECOSUBCODE04 = '$rowMain[SUBCODE04]'";
                $querySisaStockDigudang = db2_exec($conn1, $rSisaStockDigudang);
                $rowSisaStockDigudang = db2_fetch_assoc($querySisaStockDigudang);
                $dataSisaStockDigudang = $rowSisaStockDigudang['QTY'] ?? 0;

                $rBelumKirimKnt = "SELECT
                                        SUM(BASEPRIMARYQUANTITYUNIT) AS QTY
                                    FROM
                                        BALANCE
                                    WHERE
                                        ITEMTYPECODE = 'KGF'
                                        AND LOGICALWAREHOUSECODE = 'M502'
                                        AND PROJECTCODE = 'OPN2500458'
                                        AND DECOSUBCODE01 = 'CVC'
                                        AND DECOSUBCODE02 = 'F3C'
                                        AND DECOSUBCODE03 = '25067'
                                        AND DECOSUBCODE04 = 'HH2'";
                $queryBelumKirimKnt = db2_exec($conn1, $rBelumKirimKnt);
                $rowBelumKirimKnt = db2_fetch_assoc($queryBelumKirimKnt);
                $dataBelumKirimKnt = $rowBelumKirimKnt['QTY'] ?? 0;
            ?>
            <tr>
                <td><?= $dataTerimaOrder; ?></td>
                <td><?= $rowMain['LANGGANAN']; ?></td>
                <td><?= $rowMain['KATEGORI']; ?></td>
                <td><?= $dataSeason; ?></td>
                <td><?= $rowMain['PROJECT']; ?></td>
                <td><?= $rowMain['UKURAN_MESIN']; ?></td>
                <td><?= $rowMain['ARTICLE_CODE']; ?></td>
                <td><?= $rowMain['MACHINE']; ?></td>
                <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? number_format($rowMain['TOTAL_QTY'],2 ) : 0; ?></td>
                <td><?= $dataTotMC; ?></td> <!--$rowMain['TOTAL_MACHINE'] -->
                <td><?= number_format($rowMain['STD_QTY_PERMACHINE'], 0) ?></td>
                <td><?= number_format($dataCapacity, 0) ?></td>
                <td><?= number_format($rowMain['QTY_PRODUC_YESTERDAY'], 2) ?></td>
                <td><?= !empty($dataPersentaseTarget) ? number_format($dataPersentaseTarget, 0) . " %" : ''; ?></td> <!-- $rowMain['PERSENTASE_TARGET'] -->
                <td><?= number_format($rowMain['PRODUCED'], 2) ?></td>
                <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? number_format($rowMain['BELUM_RAJUT'], 2) : 0; ?></td>
                <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? $rowMain['PERSENTASE_SETELAH_PROD'] : 0; ?></td>
                <td><?= number_format($dataDigudang, 2); ?></td>
                <td><?= number_format($dataSisaStockDigudang, 2); ?></td>
                <td><?= number_format($dataBelumKirimKnt, 2); ?></td>
                <td><?= (empty($rowMain['ORIGINAL_PDCODE']) and $dataTotMC > 0 ) ? $daysNeeded : ''; //$dataEstimated_Completed;?></td>
                <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? $dataEstimated_Delivery : 0; ?></td>
                <td><?= (empty($rowMain['ORIGINAL_PDCODE']) and $dataTotMC > 0 ) ? $rowMain['DELIVERY_ACTUAL'] : ''; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>