<div class="container-fluid">
        <div class="card card-warning">
            <div class="card-header">                
                <h3 class="card-title">Knitting Schedule</h3>
            </div>
            <div class="card-body table-responsive">
                <table id="example1" width="100%" class="table table-sm table-striped table-bordered table-hover" style="font-size: 11px;">
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
                            <th style="text-align: center">Total Demand</th>
                            <th style="text-align: center">Total Plan MC</th>
                            <th style="text-align: center">Total MC Jalan</th>
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
                            require_once 'koneksi.php';
                            $dataMain   = "WITH QTY_PRODUC_YESTERDAY AS (
												SELECT
														PROJECTCODE,
														DECOSUBCODE01,
														DECOSUBCODE02,
														DECOSUBCODE03,
														DECOSUBCODE04,
														SUM(USERPRIMARYQUANTITY) AS QTY_PRODUC_YESTERDAY
												FROM
														STOCKTRANSACTION s
												WHERE
														LOGICALWAREHOUSECODE = 'M502'
													AND TEMPLATECODE = '110'
													AND (TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) >= TIMESTAMP(CAST(CURRENT_DATE AS DATE) - 1 DAY, TIME('07:00:00')))
													AND (TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) < TIMESTAMP(CAST(CURRENT_DATE AS DATE), TIME('07:00:00')))
												GROUP BY
														PROJECTCODE,
														DECOSUBCODE01,
														DECOSUBCODE02,
														DECOSUBCODE03,
														DECOSUBCODE04
												),
												QTY_PRODUC_ALL AS(
													SELECT
														PROJECTCODE,
														DECOSUBCODE01,
														DECOSUBCODE02,
														DECOSUBCODE03,
														DECOSUBCODE04,
														SUM(USERPRIMARYQUANTITY) AS QTY_PRODUC_ALL
													FROM
														STOCKTRANSACTION s
													WHERE
														LOGICALWAREHOUSECODE = 'M502'
														AND TEMPLATECODE = '110'
													GROUP BY
														PROJECTCODE,
														DECOSUBCODE01,
														DECOSUBCODE02,
														DECOSUBCODE03,
														DECOSUBCODE04
												),
												TOTAL_QTY_DEMAND AS (
												SELECT
													DISTINCT
													p2.SUBCODE01 AS tq_SUBCODE01,
													p2.SUBCODE02 AS tq_SUBCODE02,
													p2.SUBCODE03 AS tq_SUBCODE03,
													p2.SUBCODE04 AS tq_SUBCODE04,
													p2.ORIGDLVSALORDLINESALORDERCODE,
													COALESCE(SUM(p2.USERPRIMARYQUANTITY), 0)
													+ COALESCE(SUM(a2.VALUEDECIMAL), 0)
													- (
														COALESCE(SUM(a1.VALUEDECIMAL), 0)
													  + COALESCE(SUM(a3.VALUEDECIMAL), 0)
													  ) AS TOTAL_QTY_ALL,
													(COALESCE(SUM(p2.USERPRIMARYQUANTITY), 0) - COALESCE(SUM(a3.VALUEDECIMAL), 0)) AS TOTAL_QTY
												FROM
													PRODUCTIONDEMAND p2
												LEFT JOIN ADSTORAGE a1 ON
													a1.UNIQUEID = p2.ABSUNIQUEID
													AND
												a1.FIELDNAME = 'QtyOperIn'
												LEFT JOIN ADSTORAGE a2 ON
													a2.UNIQUEID = p2.ABSUNIQUEID
													AND
												a2.FIELDNAME = 'QtyOperOut'
												LEFT JOIN ADSTORAGE a3 ON
													a3.UNIQUEID = p2.ABSUNIQUEID
													AND
												a3.FIELDNAME = 'QtySalin'
												WHERE
													p2.ITEMTYPEAFICODE = 'KGF' 
												GROUP BY
													p2.SUBCODE01,
													p2.SUBCODE02,
													p2.SUBCODE03,
													p2.SUBCODE04,
													p2.ORIGDLVSALORDLINESALORDERCODE
												),
												TOTAL_PLAN AS (
												SELECT
													SUM(CASE WHEN INSPEK.KGPAKAI > 0 THEN 0 ELSE 1 END) AS TOTMC_PLAN,
													TRIM(ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE) AS tp_PROJECT,
													TRIM(ITXVIEWKNTORDER.SUBCODE01) AS tp_SUBCODE01,
													TRIM(ITXVIEWKNTORDER.SUBCODE02) AS tp_SUBCODE02,
													TRIM(ITXVIEWKNTORDER.SUBCODE03) AS tp_SUBCODE03,
													TRIM(ITXVIEWKNTORDER.SUBCODE04) AS tp_SUBCODE04
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
													SELECT
														COUNT(WEIGHTREALNET) AS KGPAKAI,
														DEMANDCODE
													FROM
														ELEMENTSINSPECTION
													WHERE
														ELEMENTITEMTYPECODE = 'KGF'
														AND COMPANYCODE = '100'
													GROUP BY
														DEMANDCODE
												) INSPEK ON
													INSPEK.DEMANDCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
												WHERE
													PRODUCTIONDEMAND.ITEMTYPEAFICODE = 'KGF'
													AND AD6.VALUESTRING = '0'
													AND ADSTORAGE.VALUESTRING IS NOT NULL
													AND (ITXVIEWKNTORDER.PROGRESSSTATUS IN('1', '2')
														OR PRODUCTIONDEMAND.PROGRESSSTATUS IN ('1', '2'))
												GROUP BY 
													ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE,
													ITXVIEWKNTORDER.SUBCODE01,
													ITXVIEWKNTORDER.SUBCODE02,
													ITXVIEWKNTORDER.SUBCODE03,
													ITXVIEWKNTORDER.SUBCODE04
												),
												TOTAL_MC_JALAN AS (
												SELECT
													SUM(CASE WHEN INSPEK.KGPAKAI >0 THEN 1 ELSE 0 END) AS TOTMC_JALAN,
													TRIM(ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE) AS jln_PROJECT,
													TRIM(ITXVIEWKNTORDER.SUBCODE01) AS jln_SUBCODE01,
													TRIM(ITXVIEWKNTORDER.SUBCODE02) AS jln_SUBCODE02,
													TRIM(ITXVIEWKNTORDER.SUBCODE03) AS jln_SUBCODE03,
													TRIM(ITXVIEWKNTORDER.SUBCODE04) AS jln_SUBCODE04
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
													SELECT
														COUNT(WEIGHTREALNET) AS KGPAKAI,
														DEMANDCODE
													FROM
														ELEMENTSINSPECTION
													WHERE
														ELEMENTITEMTYPECODE = 'KGF'
														AND COMPANYCODE = '100'
													GROUP BY
														DEMANDCODE
													) INSPEK ON
													INSPEK.DEMANDCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
												LEFT OUTER JOIN(
													SELECT LISTAGG(x.LONGDESCRIPTION, ', ') WITHIN GROUP (ORDER BY x.STEPNUMBER) AS STS, x.PRODUCTIONDEMANDCODE FROM DB2ADMIN.PRODUCTIONDEMANDSTEP x
													WHERE x.PROGRESSSTATUS = '2' AND x.ITEMTYPEAFICODE = 'KGF'
													GROUP BY x.PRODUCTIONDEMANDCODE
													) STSMC ON
													STSMC.PRODUCTIONDEMANDCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
												WHERE
													PRODUCTIONDEMAND.ITEMTYPEAFICODE = 'KGF'
													AND (PRODUCTIONDEMAND.PROGRESSSTATUS = '0'
														OR PRODUCTIONDEMAND.PROGRESSSTATUS = '1'
														OR PRODUCTIONDEMAND.PROGRESSSTATUS = '2'
														OR AD6.VALUESTRING = '1')
													AND ITXVIEWKNTORDER.PROGRESSSTATUS IN('2', '6')
														AND (STSMC.STS IN('Greige Inspection', 'Knitting', 'Knitting, Greige Inspection 2', 'Knitting, Greige Inspection', 'Knitting, Greige Inspection, Greige Inspection 2', 'Knitting, Greige inspection 2, Greige Inspection')
															OR INSPEK.KGPAKAI >0)
													GROUP BY
														ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE,
														ITXVIEWKNTORDER.SUBCODE01,
														ITXVIEWKNTORDER.SUBCODE02,
														ITXVIEWKNTORDER.SUBCODE03,
														ITXVIEWKNTORDER.SUBCODE04
												),
												TOTAL_MACHINE AS (
												SELECT
														p3.ORIGDLVSALORDLINESALORDERCODE,
														p3.SUBCODE01 AS tm_SUBCODE01,
														p3.SUBCODE02 AS tm_SUBCODE02,
														p3.SUBCODE03 AS tm_SUBCODE03,
														p3.SUBCODE04 AS tm_SUBCODE04,
													COUNT(p3.CODE) AS JUMLAH_MACHINE
												FROM
														PRODUCTIONDEMAND p3
												LEFT JOIN ADSTORAGE machine ON
														machine.UNIQUEID = p3.ABSUNIQUEID
													AND machine.FIELDNAME = 'MachineNoCode'
												WHERE
													p3.ITEMTYPEAFICODE = 'KGF'
												GROUP BY
														p3.ORIGDLVSALORDLINESALORDERCODE,
														p3.SUBCODE01,
														p3.SUBCODE02,
														p3.SUBCODE03,
														p3.SUBCODE04
												)
												SELECT
													LANGGANAN,
													KATEGORI,
													PROJECT,
													DATAMAIN.ORIGINAL_PDCODE,
													ARTICLE_CODE,
													UKURAN_MESIN,
													tqd.TOTAL_QTY AS TOTAL_QTY,
													MACHINE,
													TM.JUMLAH_MACHINE AS TOTAL_MACHINE,
													STD_QTY_PERMACHINE,
													TM.JUMLAH_MACHINE * STD_QTY_PERMACHINE AS CAPACITY_PERDAY,
													QPY.QTY_PRODUC_YESTERDAY,
													FLOOR(ROUND(CAST(QPY.QTY_PRODUC_YESTERDAY AS DECIMAL(10, 4)) / (TM.JUMLAH_MACHINE * STD_QTY_PERMACHINE), 2) * 100) || '%' AS PERSENTASE_TARGET,
													QPA.QTY_PRODUC_ALL AS PRODUCED,
													-- tqd.TOTAL_QTY - COALESCE(QPA.QTY_PRODUC_ALL, 0) AS BELUM_RAJUT,
													tqd.TOTAL_QTY_ALL - COALESCE(QPA.QTY_PRODUC_ALL, 0) AS BELUM_RAJUT,
													CASE
														WHEN tqd.TOTAL_QTY = 0 THEN '0'
														ELSE FLOOR(ROUND((CAST(QPA.QTY_PRODUC_ALL AS DECIMAL(10, 4)) / tqd.TOTAL_QTY * 100))) || '%'
													END AS PERSENTASE_SETELAH_PROD,													
													LISTAGG(
														DISTINCT DELIVERY_ACTUAL,
														', '
													) AS DELIVERY_ACTUAL,
													SUBCODE01,
													SUBCODE02,
													SUBCODE03,
													SUBCODE04,
													COALESCE(TP.TOTMC_PLAN, 0) AS TOTMC_PLAN,
													COALESCE(JLN.TOTMC_JALAN, 0) AS TOTMC_JALAN
												FROM
													(
													SELECT
															ip.LANGGANAN,
															CASE
																WHEN s.TEMPLATECODE = 'DOM' THEN 'Bulk'
															WHEN s.TEMPLATECODE = 'EXP' THEN 'Bulk'
															WHEN s.TEMPLATECODE = 'OPN' THEN 'Booking'
															WHEN s.TEMPLATECODE = 'MNB' THEN 'Minibulk'
															WHEN s.TEMPLATECODE = 'CMD' THEN 'Cuci Mesin'
															WHEN s.TEMPLATECODE = 'SME' THEN 'Salesment'
															WHEN s.TEMPLATECODE = 'SAM' THEN 'Salesment'
															WHEN s.TEMPLATECODE = 'BP' THEN 'Salesment'
															WHEN s.TEMPLATECODE = 'TS' THEN 'Salesment'
															ELSE 'Salesment'
														END AS KATEGORI,
															p.ORIGDLVSALORDLINESALORDERCODE AS PROJECT,
															TRIM(p.SUBCODE01) || ' ' || TRIM(p.SUBCODE02) || TRIM(p.SUBCODE03) || ' ' || TRIM(p.SUBCODE04) AS ARTICLE_CODE,
															COALESCE(FLOOR(a7.VALUEDECIMAL), '0') || ' X ' || COALESCE(FLOOR(a8.VALUEDECIMAL), '0') AS UKURAN_MESIN,
															SUBSTR(a2.VALUESTRING, 1, 2) AS MACHINE,
															ROUND(a5.VALUEDECIMAL * 24) AS STD_QTY_PERMACHINE,
															a6.VALUEDATE AS DELIVERY_ACTUAL,
															a4.VALUESTRING AS ORIGINAL_PDCODE,
															p.SUBCODE01,
															p.SUBCODE02,
															p.SUBCODE03,
															p.SUBCODE04,
															p.ABSUNIQUEID AS ABSUNIQUEID_DEMAND
													FROM
															PRODUCTIONDEMAND p
													LEFT JOIN SALESORDER s ON
															s.CODE = p.ORIGDLVSALORDLINESALORDERCODE
													LEFT JOIN ADSTORAGE a1 ON
															a1.UNIQUEID = p.ABSUNIQUEID
														AND a1.FIELDNAME = 'StatusOper'
														AND NOT a1.VALUESTRING = '2'
													LEFT JOIN ADSTORAGE a2 ON
															a2.UNIQUEID = p.ABSUNIQUEID
														AND a2.FIELDNAME = 'MachineNoCode'
													LEFT JOIN ADSTORAGE a3 ON
															a3.UNIQUEID = p.ABSUNIQUEID
														AND a3.FIELDNAME = 'ukuranMC'
													LEFT JOIN ADSTORAGE a4 ON
															a4.UNIQUEID = p.ABSUNIQUEID
														AND a4.FIELDNAME = 'OriginalPDCode'
													LEFT JOIN ADSTORAGE a6 ON
															a6.UNIQUEID = p.ABSUNIQUEID
														AND a6.FIELDNAME = 'RMPGreigeReqDateTo'
													LEFT JOIN ITXVIEW_PELANGGAN ip ON
															ip.CODE = p.ORIGDLVSALORDLINESALORDERCODE
													LEFT JOIN PRODUCT p2 ON
															p2.ITEMTYPECODE = p.ITEMTYPEAFICODE
														AND p2.SUBCODE01 = p.SUBCODE01
														AND p2.SUBCODE02 = p.SUBCODE02
														AND p2.SUBCODE03 = p.SUBCODE03
														AND p2.SUBCODE04 = p.SUBCODE04
													LEFT JOIN ADSTORAGE a5 ON
															a5.UNIQUEID = p2.ABSUNIQUEID
														AND a5.FIELDNAME = 'ProductionRate'
													LEFT JOIN ADSTORAGE a7 ON
															a7.UNIQUEID = p2.ABSUNIQUEID
														AND a7.FIELDNAME = 'Diameter'
													LEFT JOIN ADSTORAGE a8 ON
															a8.UNIQUEID = p2.ABSUNIQUEID
														AND a8.FIELDNAME = 'Gauge'
													LEFT JOIN ADSTORAGE a9 ON
														p.ABSUNIQUEID = a9.UNIQUEID
														AND a9.NAMENAME = 'StatusRMP'
													WHERE
															p.ITEMTYPEAFICODE = 'KGF'
														AND (p.PROGRESSSTATUS IN('0', '1', '2')
															OR a1.VALUESTRING = '1')
														AND NOT p.ORIGDLVSALORDLINESALORDERCODE IS NULL
														AND a4.VALUESTRING IS NULL
														AND (a9.VALUESTRING <> '3'
															)       
													ORDER BY
															p.ORIGDLVSALORDLINESALORDERCODE DESC
													) DATAMAIN
												LEFT JOIN QTY_PRODUC_YESTERDAY QPY ON
													QPY.PROJECTCODE = DATAMAIN.PROJECT
													AND QPY.DECOSUBCODE01 = DATAMAIN.SUBCODE01
													AND QPY.DECOSUBCODE02 = DATAMAIN.SUBCODE02
													AND QPY.DECOSUBCODE03 = DATAMAIN.SUBCODE03
													AND QPY.DECOSUBCODE04 = DATAMAIN.SUBCODE04
												LEFT JOIN QTY_PRODUC_ALL QPA ON
													QPA.PROJECTCODE = DATAMAIN.PROJECT
													AND QPA.DECOSUBCODE01 = DATAMAIN.SUBCODE01
													AND QPA.DECOSUBCODE02 = DATAMAIN.SUBCODE02
													AND QPA.DECOSUBCODE03 = DATAMAIN.SUBCODE03
													AND QPA.DECOSUBCODE04 = DATAMAIN.SUBCODE04
												LEFT JOIN TOTAL_QTY_DEMAND tqd ON
													tqd.ORIGDLVSALORDLINESALORDERCODE = DATAMAIN.PROJECT
													AND tqd.tq_SUBCODE01 = DATAMAIN.SUBCODE01
													AND tqd.tq_SUBCODE02 = DATAMAIN.SUBCODE02
													AND tqd.tq_SUBCODE03 = DATAMAIN.SUBCODE03
													AND tqd.tq_SUBCODE04 = DATAMAIN.SUBCODE04
												LEFT JOIN TOTAL_MACHINE TM ON
													TM.ORIGDLVSALORDLINESALORDERCODE = DATAMAIN.PROJECT
													AND TM.tm_SUBCODE01 = DATAMAIN.SUBCODE01
													AND TM.tm_SUBCODE02 = DATAMAIN.SUBCODE02
													AND TM.tm_SUBCODE03 = DATAMAIN.SUBCODE03
													AND TM.tm_SUBCODE04 = DATAMAIN.SUBCODE04
												LEFT JOIN TOTAL_PLAN TP ON
													TP.tp_PROJECT = DATAMAIN.PROJECT
													AND TP.tp_SUBCODE01 = DATAMAIN.SUBCODE01
													AND TP.tp_SUBCODE02 = DATAMAIN.SUBCODE02
													AND TP.tp_SUBCODE03 = DATAMAIN.SUBCODE03
													AND TP.tp_SUBCODE04 = DATAMAIN.SUBCODE04
												LEFT JOIN TOTAL_MC_JALAN JLN ON
													JLN.jln_PROJECT = DATAMAIN.PROJECT
													AND JLN.jln_SUBCODE01 = DATAMAIN.SUBCODE01
													AND JLN.jln_SUBCODE02 = DATAMAIN.SUBCODE02
													AND JLN.jln_SUBCODE03 = DATAMAIN.SUBCODE03
													AND JLN.jln_SUBCODE04 = DATAMAIN.SUBCODE04
												GROUP BY
													LANGGANAN,
													KATEGORI,
													PROJECT,
													DATAMAIN.ORIGINAL_PDCODE,
													ARTICLE_CODE,
													UKURAN_MESIN,
													MACHINE,
													STD_QTY_PERMACHINE,
													QPY.QTY_PRODUC_YESTERDAY,
													QPA.QTY_PRODUC_ALL,
													tqd.TOTAL_QTY,
													tqd.TOTAL_QTY_ALL,
													SUBCODE01,
													SUBCODE02,
													SUBCODE03,
													SUBCODE04,
													TM.JUMLAH_MACHINE,
													TP.TOTMC_PLAN,
													JLN.TOTMC_JALAN ";
                            $queryMain  = db2_prepare($conn1, $dataMain);
							db2_execute($queryMain);
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
                                $queryTerimaOrder = db2_prepare($conn1, $rTerimaOrder);
								db2_execute($queryTerimaOrder);
                                $rowTerimaOrder = db2_fetch_assoc($queryTerimaOrder);
                                $dataTerimaOrder = $rowTerimaOrder['TERIMAORDER'] ?? '';
                                $dataCapacity = (float)$rowMain['TOTMC_JALAN'] * (float)$rowMain['STD_QTY_PERMACHINE'];
                                if($dataCapacity>0){
                                    $dataPersentaseTarget = FLOOR(ROUND($rowMain['QTY_PRODUC_YESTERDAY'] / $dataCapacity, 2) * 100);
                                    $dataEstimated_Completed = CEIL($rowMain['TOTAL_QTY'] / $dataCapacity );
                                    // Hitung jumlah hari produksi yang dibutuhkan
                                    $daysNeeded = round(($rowMain['TOTAL_QTY'] - $rowMain['PRODUCED']) / $dataCapacity, 2);
                                    // $daysNeeded = round($kRajut / $dataCapacity, 2);
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
                                $querySeason = db2_prepare($conn1, $rSeason);
								db2_execute($querySeason);
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
                                $queryDigudang = db2_prepare($conn1, $rDigudang);
								db2_execute($queryDigudang);
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
                                $querySisaStockDigudang = db2_prepare($conn1, $rSisaStockDigudang);
								db2_execute($querySisaStockDigudang);
                                $rowSisaStockDigudang = db2_fetch_assoc($querySisaStockDigudang);
                                $dataSisaStockDigudang = $rowSisaStockDigudang['QTY'] ?? 0;

                                $rBelumKirimKnt = "SELECT
                                                        SUM(BASEPRIMARYQUANTITYUNIT) AS QTY
                                                    FROM
                                                        BALANCE
                                                    WHERE
                                                        ITEMTYPECODE = 'KGF'
                                                        AND LOGICALWAREHOUSECODE = 'M502'
                                                        AND PROJECTCODE = '$rowMain[PROJECT]'
                                                        AND DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                                        AND DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                                        AND DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                                        AND DECOSUBCODE04 = '$rowMain[SUBCODE04]'";
                                $queryBelumKirimKnt = db2_prepare($conn1, $rBelumKirimKnt);
								db2_execute($queryBelumKirimKnt);
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
                                <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? number_format($rowMain['TOTAL_QTY'],2 ) : 0; ?></td> <!-- ($qtyORD > 0) ? $qtyORD : ( empty($rowMain['ORIGINAL_PDCODE']) ? number_format($rowMain['TOTAL_QTY'], 2) : 0 );-->
                                <td><?= $rowMain['TOTAL_MACHINE']; ?></td>
                                <td><?= $rowMain['TOTMC_PLAN']; ?></td> <!-- $dataPlanMC; -->
                                <td><?= $rowMain['TOTMC_JALAN']; ?></td> <!-- $rowMain['TOTAL_MACHINE'] --> <!-- $dataTotMC; -->
                                <td><?= number_format($rowMain['STD_QTY_PERMACHINE'], 0) ?></td>
                                <td><?= number_format($dataCapacity, 0) ?></td>
                                <td><?= number_format($rowMain['QTY_PRODUC_YESTERDAY'], 2) ?></td>
                                <td><?= !empty($dataPersentaseTarget) ? number_format($dataPersentaseTarget, 0) . " %" : ''; ?></td> <!--  $rowMain['PERSENTASE_TARGET']; -->
                                <td><?= number_format($rowMain['PRODUCED'], 2) ?></td> <!-- number_format($rowdb2R['JQTY'], 2) -->
                                <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? number_format($rowMain['BELUM_RAJUT'], 2) : 0; ?></td> <!-- number_format($kRajut, 2) -->
                                <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? $rowMain['PERSENTASE_SETELAH_PROD'] : 0; ?></td> <!-- $pesentaseProd -->
                                <td><?= number_format($dataDigudang, 2); ?></td>
                                <td><?= number_format($dataSisaStockDigudang, 2); ?></td>
                                <td><?= number_format($dataBelumKirimKnt, 2); ?></td>
                                <td><?= (empty($rowMain['ORIGINAL_PDCODE']) and $rowMain['TOTMC_JALAN'] > 0 ) ? $daysNeeded : ''; //  $dataEstimated_Completed; $dataTotMC; ?></td>
                                <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? $dataEstimated_Delivery : 0; ?></td>
                                <td><?= (empty($rowMain['ORIGINAL_PDCODE']) and $rowMain['TOTMC_JALAN'] > 0 ) ? $rowMain['DELIVERY_ACTUAL'] : ''; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="plugins/toastr/toastr.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>