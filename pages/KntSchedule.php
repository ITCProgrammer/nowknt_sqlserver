<div class="container-fluid">
        <div class="card card-warning">
            <div class="card-header">
                <?php
                    require_once 'koneksi.php';
                    $cekLastUpdate          = "SELECT DISTINCT 
                                                    'Data last updated on ' ||
                                                    VARCHAR_FORMAT(MAX(CREATIONDATETIME), 'DD Mon YYYY') ||
                                                    ' at ' ||
                                                    VARCHAR_FORMAT(MAX(CREATIONDATETIME), 'HH24:MI') ||
                                                    ' (WIB)' AS LAST_UPDATED_INFO
                                                FROM
                                                    SCHEDULE_KNITING";
                    $queryMainLastUpdate    = db2_exec($conn1, $cekLastUpdate);
                    $rowMainLastUpdate = db2_fetch_assoc($queryMainLastUpdate)
                ?>
                <h3 class="card-title">Knitting Schedule | <?= $rowMainLastUpdate['LAST_UPDATED_INFO']; ?></h3>
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
                            $dataMain   = "SELECT * FROM SCHEDULE_KNITING";
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
						// total MC plan, jalan dan total QTY
						
						/*		$queryQtyOrd = "SELECT
							  SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
							  SUM(a3.VALUEDECIMAL) AS QTYSALIN
							FROM
							  (
								SELECT
								  PRODUCTIONDEMAND.PROJECTCODE,
								  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
								  PRODUCTIONDEMAND.ITEMTYPEAFICODE,
								  PRODUCTIONDEMAND.SUBCODE01,
								  PRODUCTIONDEMAND.SUBCODE02,
								  PRODUCTIONDEMAND.SUBCODE03,
								  PRODUCTIONDEMAND.SUBCODE04,
								  PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,
								  PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE,
								  PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
								  PRODUCTIONORDER.PROGRESSSTATUS
								FROM
								  PRODUCTIONDEMAND PRODUCTIONDEMAND
								  LEFT OUTER JOIN DB2ADMIN.COMPANY COMPANY ON PRODUCTIONDEMAND.COMPANYCODE = COMPANY.CODE
								  LEFT JOIN DB2ADMIN.PRODUCTIONDEMANDSTEP PRODUCTIONDEMANDSTEP ON PRODUCTIONDEMAND.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE
								  AND PRODUCTIONDEMAND.COMPANYCODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCOMPANYCODE
								  LEFT JOIN DB2ADMIN.PRODUCTIONORDER PRODUCTIONORDER ON PRODUCTIONORDER.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE
								GROUP BY
								  PRODUCTIONDEMAND.PROJECTCODE,
								  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
								  PRODUCTIONDEMAND.ITEMTYPEAFICODE,
								  PRODUCTIONDEMAND.SUBCODE01,
								  PRODUCTIONDEMAND.SUBCODE02,
								  PRODUCTIONDEMAND.SUBCODE03,
								  PRODUCTIONDEMAND.SUBCODE04,
								  PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,
								  PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE,
								  PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
								  PRODUCTIONORDER.PROGRESSSTATUS
							  ) a
							  LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE = a.PRODUCTIONDEMANDCODE
							  LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID = a2.UNIQUEID
							  AND a2.NAMENAME = 'StatusRMP'
							  LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID = a3.UNIQUEID
							  AND a3.NAMENAME = 'QtySalin'
							WHERE
							  a.ITEMTYPEAFICODE = 'KGF'
							  AND (
								a.PROJECTCODE = '$rowMain[PROJECT]'
								OR a.ORIGDLVSALORDLINESALORDERCODE = '$rowMain[PROJECT]'
							  )
							  AND a.SUBCODE02 = '$rowMain[SUBCODE02]'
							  AND a.SUBCODE03 = '$rowMain[SUBCODE03]'
							  AND a.SUBCODE04 = '$rowMain[SUBCODE04]'
							  AND (
								a.PROGRESSSTATUS = '2'
								OR a.PROGRESSSTATUS = '6'
							  )
							  AND (
								NOT a2.VALUESTRING = '3'
								OR a2.VALUESTRING IS NULL
							  )";
								$qdb2QtyOrd = db2_exec($conn1, $queryQtyOrd);
                                $rowQtyORD = db2_fetch_assoc($qdb2QtyOrd);
								$qtyORD = number_format(round($rowQtyORD['BASEPRIMARYQUANTITY'] - $rowQtyORD['QTYSALIN'], 2), 2);
								$sqlDB2R =" SELECT SUM(INSP.JQTY) AS JQTY  FROM ITXVIEWHEADERKNTORDER a
								LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
								LEFT OUTER JOIN (
								SELECT DEMANDCODE, COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
										  ELEMENTSINSPECTION WHERE ELEMENTITEMTYPECODE='KGF'
								GROUP BY DEMANDCODE		  
								) INSP ON INSP.DEMANDCODE=p.CODE
								WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$rowMain[PROJECT]' OR a.ORIGDLVSALORDLINESALORDERCODE='$rowMain[PROJECT]') AND
								a.SUBCODE02='$rowMain[SUBCODE02]' AND a.SUBCODE03='$rowMain[SUBCODE03]' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6')";	
								$stmtR   = db2_exec($conn1,$sqlDB2R, array('cursor'=>DB2_SCROLLABLE));
								$rowdb2R = db2_fetch_assoc($stmtR);
								$kRajut  = round($rowQtyORD['BASEPRIMARYQUANTITY'] - $rowQtyORD['QTYSALIN'], 2)-round($rowdb2R['JQTY'],2);
								if($qtyORD>0){
								$pesentaseProd = round((round($rowdb2R['JQTY'],2)/round($rowQtyORD['BASEPRIMARYQUANTITY'] - $rowQtyORD['QTYSALIN'], 2))*100,2)." %";
								}else{
								$pesentaseProd = "";	
								} 
								$queryPlanMC = "SELECT
												SUM(CASE WHEN INSPEK.KGPAKAI > 0 THEN 0 ELSE 1 END) AS TOTMC
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
												AND AD6.VALUESTRING = '0'
												AND ADSTORAGE.VALUESTRING IS NOT NULL
												AND (ITXVIEWKNTORDER.PROGRESSSTATUS IN('1','2') OR PRODUCTIONDEMAND.PROGRESSSTATUS IN ('1','2'))
												AND ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE = '$rowMain[PROJECT]'
												AND CONCAT(CONCAT(CONCAT(RTRIM(ITXVIEWKNTORDER.SUBCODE01), ' '), CONCAT(RTRIM(ITXVIEWKNTORDER.SUBCODE02), RTRIM(ITXVIEWKNTORDER.SUBCODE03))), CONCAT(' ', RTRIM(ITXVIEWKNTORDER.SUBCODE04))) = '$rowMain[ARTICLE_CODE]'";
                                $qPlanMC = db2_exec($conn1, $queryPlanMC);
                                $rowPlanMC = db2_fetch_assoc($qPlanMC);
								$dataPlanMC = $rowPlanMC['TOTMC'] ?? '0';
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
								*/
								//  $dataCapacity = $dataTotMC * $rowMain['STD_QTY_PERMACHINE'];
                                $dataCapacity = $rowMain['TOTMC_JALAN'] * $rowMain['STD_QTY_PERMACHINE'];
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
                                                        AND PROJECTCODE = '$rowMain[PROJECT]'
                                                        AND DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                                        AND DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                                        AND DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                                        AND DECOSUBCODE04 = '$rowMain[SUBCODE04]'";
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

<script>
    $(function() {
        //Datepicker
        $('#datepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datepicker1').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datepicker2').datetimepicker({
            format: 'YYYY-MM-DD'
        });

    });
</script>