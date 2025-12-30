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
                            <th style="text-align: center">Estimate Complete</th>
                            <th style="text-align: center">Estimated Delivery</th>
                            <th style="text-align: center">Devliery Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require_once 'koneksi.php';
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
                            ?>
                            <tr>
                                <td><?= $dataTerimaOrder; ?></td>
                                <td><?= $rowMain['LANGGANAN']; ?></td>
                                <td><?= $rowMain['KATEGORI']; ?></td>
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
                                <td><?= (empty($rowMain['ORIGINAL_PDCODE']) and $dataTotMC > 0 ) ? $daysNeeded : ''; //$dataEstimated_Completed; ?> <?php echo $dayack; ?> <?php echo $dayack1; ?></td>
                                <td><?= empty($rowMain['ORIGINAL_PDCODE']) ? $dataEstimated_Delivery : 0; ?></td>
                                <td><?= (empty($rowMain['ORIGINAL_PDCODE']) and $dataTotMC > 0 ) ? $rowMain['DELIVERY_ACTUAL'] : ''; ?></td>
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