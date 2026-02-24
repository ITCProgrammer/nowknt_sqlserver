<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
?>
<!-- Main content -->
<div class="container-fluid">
	<form role="form" method="post" enctype="multipart/form-data" name="form1">
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Filter Data Per Tanggal</h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse">
						<i class="fas fa-minus"></i>
					</button>
					<button type="button" class="btn btn-tool" data-card-widget="remove">
						<i class="fas fa-times"></i>
					</button>
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body">
				<div class="form-group row">
					<label for="tgl_awal" class="col-md-1">Tanggal</label>
					<div class="col-md-2">
						<div class="input-group date" id="datepicker1" data-target-input="nearest">
							<div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
								<span class="input-group-text btn-info">
									<i class="far fa-calendar-alt"></i>
								</span>
							</div>
							<input name="tgl_awal" value="<?php echo $Awal; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
						</div>
					</div>
				</div>

				<button class="btn btn-info" type="submit">Cari Data</button>
			</div>
			<!-- /.card-body -->
		</div>

		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title">Detail Status Mesin</h3>
			</div>
			<!-- /.card-header -->
			<div class="card-body  table-responsive">
				<table id="example12" width="100%" class="table table-sm table-striped table-bordered table-hover" style="font-size: 9px;">
					<thead>
						<tr>
							<th rowspan="2" style="text-align: center">No MC</th>
							<th rowspan="2" style="text-align: center">KD</th>
							<th rowspan="2" style="text-align: center">Ukuran</th>
							<th rowspan="2" style="text-align: center">Catatan</th>
							<th rowspan="2" style="text-align: center">Project</th>
							<th rowspan="2" style="text-align: center">DemandNo</th>
							<th rowspan="2" style="text-align: center">Prod. Order</th>
							<th rowspan="2" style="text-align: center">Konsumen</th>
							<th rowspan="2" style="text-align: center">NoArt</th>
							<th colspan="2" style="text-align: center">Hasil Rajut</th>
							<th colspan="2" style="text-align: center">BS</th>
							<th rowspan="2" style="text-align: center">STD Qty</th>
							<th rowspan="2" style="text-align: center">Sisa Stiker</th>
							<th rowspan="2" style="text-align: center">Total Kurang Rajut</th>
							<th rowspan="2" style="text-align: center">Tgl Delivery</th>
							<th rowspan="2" style="text-align: center">ProgressStatus</th>
							<th colspan="2" style="text-align: center">Total Hari</th>
							<th rowspan="2" style="text-align: center">Delay</th>
							<th rowspan="2" style="text-align: center">Kebutuhan Greige</th>
							<th rowspan="2" style="text-align: center">Rencana Mulai</th>
							<th rowspan="2" style="text-align: center">Rencana Selesai</th>
							<th rowspan="2" style="text-align: center">Tgl Mulai</th>
							<th rowspan="2" style="text-align: center">Estimasi Selesai</th>
						</tr>
						<tr>
							<th style="text-align: center">Total </th>
							<th style="text-align: center"><?php echo $Awal ?></th>
							<th style="text-align: center">KG</th>
							<th style="text-align: center">% </th>
							<th style="text-align: center">Finish</th>
							<th style="text-align: center">Lama</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($Awal != "") {
							$sqlDB2 = " SELECT USERGENERICGROUP.CODE AS KDMC,USERGENERICGROUP.LONGDESCRIPTION, 
										USERGENERICGROUP.SHORTDESCRIPTION,USERGENERICGROUP.SEARCHDESCRIPTION,DMN.*,
										J1.JML AS JML_J1,J1.JQTY AS JQTY_J1,
										J2.JML AS JML_J2,J2.JQTY AS JQTY_J2,
										J3.JML AS JML_J3,J3.JQTY AS JQTY_J3,
										S1.IDS FROM DB2ADMIN.USERGENERICGROUP 
										LEFT JOIN
										(
										SELECT ADSTORAGE.VALUESTRING,AD1.VALUEDATE,AD2.VALUEDATE AS RMPREQDATE, AD7.VALUEDATE AS RMPREQDATETO ,AD3.VALUEDECIMAL AS QTYSALIN,
										AD4.VALUEDECIMAL AS QTYOPIN , AD5.VALUEDECIMAL AS QTYOPOUT, AD6.VALUESTRING AS STSOPR, ITXVIEWKNTORDER.*,CURRENT_TIMESTAMP AS TGLS,PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE FROM ITXVIEWKNTORDER 
										LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
										LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
										LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME ='TglRencana'
										LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME ='RMPReqDate'
										LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.NAMENAME ='QtySalin'
										LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD4 ON AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD4.NAMENAME ='QtyOperIn'
										LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD5 ON AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD5.NAMENAME ='QtyOperOut'
										LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD6.NAMENAME ='StatusOper'
										LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD7 ON AD7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD7.NAMENAME ='RMPGreigeReqDateTo'

										WHERE ITXVIEWKNTORDER.ITEMTYPEAFICODE ='KGF' AND (ITXVIEWKNTORDER.PROGRESSSTATUS='2' OR AD6.VALUESTRING='1') 
										ORDER BY AD1.VALUEDATE ASC
										) DMN ON (DMN.VALUESTRING=USERGENERICGROUP.CODE)
										LEFT OUTER JOIN (
										SELECT DEMANDCODE,COUNT(BASEPRIMARYQUANTITY) AS JML, SUM(BASEPRIMARYQUANTITY) AS JQTY FROM 
										VIEWPRODDEMANDELEMENTS WHERE PROGRESSSTATUS='0' AND COMPANYCODE='100'
										GROUP BY DEMANDCODE
										) J1 ON J1.DEMANDCODE=DMN.CODE
										LEFT OUTER JOIN (
										SELECT DEMANDCODE, COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
										ELEMENTSINSPECTION WHERE ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100'
										GROUP BY DEMANDCODE
										) J2 ON J2.DEMANDCODE=DMN.CODE
										LEFT OUTER JOIN (
										SELECT DEMANDCODE, COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
										ELEMENTSINSPECTION WHERE ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' AND SUBSTR(INSPECTIONSTARTDATETIME,1,10)='$Awal'
										GROUP BY DEMANDCODE
										) J3 ON J3.DEMANDCODE=DMN.CODE
										LEFT OUTER JOIN (
										SELECT PRODUCTIONDEMANDCODE,
										trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) as IDS	
										FROM PRODUCTIONDEMANDSTEP 
										WHERE (OPERATIONCODE='INS1' OR OPERATIONCODE='KNT1') AND ITEMTYPEAFICOMPANYCODE ='100'
										GROUP BY PRODUCTIONDEMANDCODE
										) S1 ON S1.PRODUCTIONDEMANDCODE=DMN.CODE

										WHERE USERGENERICGROUP.USERGENERICGROUPTYPECODE = 'MCK' AND 
											USERGENERICGROUP.USERGENGROUPTYPECOMPANYCODE = '100' AND 
											USERGENERICGROUP.OWNINGCOMPANYCODE = '100' ";
						} else {
							$sqlDB2 = " SELECT * FROM  USERGENERICGROUP WHERE USERGENERICGROUPTYPECODE='MCK1' ";
						}
						$no = 1;
						$c = 0;
						$stmt   = db2_prepare($conn1, $sqlDB2);
								db2_execute($stmt);
						while ($rowdb2 = db2_fetch_assoc($stmt)) {
							$totHari = "";
							$sqlDB23 = "SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT 
										FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
										WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF' AND PRODUCT.SUBCODE02='$rowdb2[SUBCODE02]' AND 
										PRODUCT.SUBCODE03='$rowdb2[SUBCODE03]' AND PRODUCT.COMPANYCODE='100' 
										ORDER BY ADSTORAGE.FIELDNAME";
							$stmt3   = db2_prepare($conn1, $sqlDB23);
							db2_execute($stmt3);
							$rowdb23 = db2_fetch_assoc($stmt3);

							$sqlDB25 = " SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME FROM 
										ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM' AND COMPANYCODE='100'
										GROUP BY INSPECTIONENDDATETIME ";
							$stmt5   = db2_prepare($conn1, $sqlDB25);
							db2_execute($stmt5);
							$rowdb25 = db2_fetch_assoc($stmt5);

							$sqlDB26 = " SELECT INSPECTIONENDDATETIME  FROM  
										ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' 
										ORDER BY INSPECTIONENDDATETIME ASC LIMIT 1";
							$stmt6   = db2_prepare($conn1, $sqlDB26);
							$rowdb26 = db2_fetch_assoc($stmt6);

							$sqlDB27 = " SELECT LASTUPDATEDATETIME  FROM  
										PRODUCTIONDEMAND WHERE CODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ITEMTYPEAFICODE='KGF' AND COMPANYCODE ='100' 
										ORDER BY LASTUPDATEDATETIME ASC LIMIT 1";
							$stmt7   = db2_prepare($conn1, $sqlDB27);
							db2_execute($stmt7);
							$rowdb27 = db2_fetch_assoc($stmt7);

							$sqlDB28 = " SELECT INSPECTIONSTARTDATETIME  FROM  
										ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' 
										ORDER BY INSPECTIONSTARTDATETIME ASC LIMIT 1";
							$stmt8   = db2_prepare($conn1, $sqlDB28);
							db2_execute($stmt8);
							$rowdb28 = db2_fetch_assoc($stmt8);

							$sqlDB29 = " SELECT PLANNEDOPERATIONCODE,PROGRESSSTATUS,LONGDESCRIPTION  FROM PRODUCTIONDEMANDSTEP 
										WHERE PRODUCTIONDEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND PROGRESSSTATUS ='2' AND 
										NOT (PLANNEDOPERATIONCODE='KNT1' OR PLANNEDOPERATIONCODE='INS1') AND ITEMTYPEAFICOMPANYCODE='100'
										ORDER BY STEPNUMBER DESC ";
							$stmt9   = db2_prepare($conn1, $sqlDB29);
							db2_execute($stmt9);
							$rowdb29 = db2_fetch_assoc($stmt9);

							$awalDY  = strtotime($rowdb2['TGLS']);
							$akhirDY = strtotime($rowdb2['RMPREQDATE']);
							$diffDY  = ($akhirDY - $awalDY);
							$tjamDY  = round($diffDY / (60 * 60), 2);
							$hariDY  = round($tjamDY / 24);

							$awalDY1  = strtotime($rowdb2['TGLS']);
							$akhirDY1 = strtotime($rowdb2['RMPREQDATETO']);
							$diffDY1  = ($akhirDY1 - $awalDY1);
							$tjamDY1  = round($diffDY1 / (60 * 60), 2);
							$hariDY1  = round($tjamDY1 / 24);

							$awalPR  = strtotime($rowdb2['TGLS']);
							$akhirPR = strtotime($rowdb25['INSPECTIONENDDATETIME']);
							$diffPR  = ($akhirPR - $awalPR);
							$tjamPR  = round($diffPR / (60 * 60), 2);
							$hariPR  = round($tjamPR / 24, 2);

							$awalSJ  = strtotime($rowdb2['TGLS']);
							$akhirSJ = strtotime($rowdb26['INSPECTIONENDDATETIME']);
							$diffSJ  = ($akhirSJ - $awalSJ);
							$tjamSJ  = round($diffSJ / (60 * 60), 2);
							$hariSJ  = round($tjamSJ / 24, 2);

							$awalPC  = strtotime($rowdb2['TGLS']);
							$akhirPC = strtotime($rowdb27['LASTUPDATEDATETIME']);
							$diffPC  = ($akhirPC - $awalPC);
							$tjamPC  = round($diffPC / (60 * 60), 2);
							$hariPC = round($tjamPC / 24, 2);

								if ($rowdb2['RMPREQDATETO'] != "") {
									$Delay = round($hariDY1, 2);
								} else if ($rowdb2['RMPREQDATE'] != "") {
									$Delay = round($hariDY, 2);
								} else {
									$Delay = "";
								}

							if (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and $rowdb25['JML'] > "0") {
								$stts = "<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Perbaikan Mesin</small>";
								$totHari = round(abs($hariPR), 2);
								$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "AMC") {
								$stts = "<small class='badge bg-yellow'><i class='far fa-clock text-white blink_me'></i> Antri Mesin</small>";
								$totHari = round(abs($hariPC), 2);
								$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "TST") {
								$stts = "<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Setting</small>";
								$totHari = round(abs($hariPC), 2);
								$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "PBS") {
								$stts = "<small class='badge bg-pink'><i class='far fa-clock text-white blink_me'></i> Habis Benang</small>";
								$totHari = round(abs($hariPC), 2);
								$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "PBG") {
								$stts = "<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> Tunggu Benang Gudang</small>";
								$totHari = round(abs($hariPC), 2);
								$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "TPB") {
								$stts = "<small class='badge bg-purple'><i class='far fa-clock text-black blink_me'></i> Tunggu Pasang Benang</small>";
								$totHari = round(abs($hariPC), 2);
								$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "TTQ") {
								$stts = "<small class='badge bg-gray'><i class='far fa-clock text-black blink_me'></i> Tunggu Tes Quality</small>";
								$totHari = round(abs($hariPC), 2);
								$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "HOLD") {
								$stts = "<small class='badge bg-black'><i class='far fa-clock text-black blink_me'></i> Hold</small>";
								$totHari = round(abs($hariPC), 2);
								$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and ($rowdb2['IDS'] == "0 ,0" or $rowdb2['IDS'] == "0 ,0 ,0" or $rowdb2['IDS'] == "0 ,0 ,0 ,0")) {
								$stts = "<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
								$totHari = round(abs($hariPC), 2);
								$StsJ = "tidak";
							} else if (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and ($rowdb2['IDS'] == "3 ,3" or $rowdb2['IDS'] == "3 ,3 ,3")) {
								$stts = "<small class='badge bg-pink'><i class='far fa-clock blink_me'></i> Sedang Jalan Oper PO</small>";
								$totHari = round(abs($hariSJ), 2);
								$StsJ = "ya";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "0" or $rowdb2['STSOPR'] == "2") and ($rowdb2['IDS'] == "3 ,3" or $rowdb2['IDS'] == "3 ,3 ,3")) {
								$stts = "<small class='badge badge-danger'><i class='far fa-clock text-white blink_me'></i> Closed</small>";
								$totHari = round(abs($hariPC), 2);
								$StsJ = "tidak";
							} else if (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and (
									$rowdb2['IDS'] == "2 ,0" or
								$rowdb2['IDS'] == "0 ,2" or
								$rowdb2['IDS'] == "2 ,2" or
								$rowdb2['IDS'] == "2 ,3" or
								$rowdb2['IDS'] == "3 ,2" or
								$rowdb2['IDS'] == "2 ,0 ,0" or
								$rowdb2['IDS'] == "2 ,2 ,0" or
								$rowdb2['IDS'] == "2 ,2 ,3" or
								$rowdb2['IDS'] == "0 ,0 ,2" or
								$rowdb2['IDS'] == "0 ,2 ,0" or
								$rowdb2['IDS'] == "0 ,2 ,2" or
								$rowdb2['IDS'] == "0 ,2 ,0 ,0" or
								$rowdb2['IDS'] == "2 ,2 ,0 ,2" or
									$rowdb2['IDS'] == "2 ,2 ,0 ,0" or
									$rowdb2['IDS'] == "3 ,2 ,0 ,2" or
									$rowdb2['IDS'] == "2 ,3 ,0 ,3" or
									$rowdb2['IDS'] == "2 ,2 ,3 ,0 ,2")) {
									$stts = "<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
									$totHari = round(abs($hariSJ), 2);
									$StsJ = "ya";
							} else {
								$stts = "Tidak Ada PO";
								$StsJ = "tidak";
							}
							if ($rowdb2['BASEPRIMARYQUANTITY'] > 0) {
								$kRajut = round(($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN']), 2) - round($rowdb2['JQTY_J2'], 2);
								$kHari = round($kRajut / round($rowdb23['STDRAJUT'], 0));
								$StHari = round((($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN'])) / round($rowdb23['STDRAJUT'], 0));
								if ($kHari >= 0) {
									if ($StsJ == "ya") {
										$tglEst = date('Y-m-d', strtotime($kHari . " days", strtotime($rowdb2['TGLS'])));
										$finHari = $kHari;
									} else {
										$tglEst = "";
										$finHari = "";
									}
								} else {
									$tglEst = "";
								}
								if ($rowdb2['PRODUCTIONDEMANDCODE'] != "") {
									$tglRFin = date('Y-m-d', strtotime($StHari . " days", strtotime($rowdb2['VALUEDATE'])));
								} else {
									$tglRFin = "";
								}
							} else {
								$kRajut = "0";
								$kHari = "0";
								$tglEst = "";
								$StHari = "0";
								$tglRFin = "";
							}


							$sqlBS = sqlsrv_query(
								$con,
								"SELECT SUM(berat_awal-berat) as kg_bs, a.demandno
								FROM dbknitt.tbl_inspeksi_now b
								INNER JOIN dbknitt.tbl_inspeksi_detail_now a ON b.id=a.id_inspeksi
								WHERE (CASE WHEN (berat_awal>berat) THEN 'BS' ELSE '' END)='BS' and a.demandno=?
								GROUP BY a.demandno",
								[$rowdb2['PRODUCTIONDEMANDCODE']]
							);
							if ($sqlBS === false) {
								echo "<div class='alert alert-danger'>Query error di " . basename(__FILE__) . ": " . print_r(sqlsrv_errors(), true) . "</div>";
							}
							$rBS = $sqlBS ? sqlsrv_fetch_array($sqlBS, SQLSRV_FETCH_ASSOC) : ['kg_bs' => 0];
							if ($rBS['kg_bs'] > 0 and $rowdb2['JQTY_J2'] > 0) {
								$perBS = round((float)$rBS['kg_bs'] / (float)$rowdb2['JQTY_J2'] * 100, 2);
							} else {
								$perBS = "0";
							}
						?>
							<tr>
								<td style="text-align: center"><?php echo $rowdb2['SEARCHDESCRIPTION']; ?></td>
								<td style="text-align: center"><?php echo $rowdb2['KDMC']; ?></td>
								<td style="text-align: center"><?php echo $rowdb2['SHORTDESCRIPTION']; ?></td>
								<td><?php echo $rowdb2['LONGDESCRIPTION']; ?></td>
								<td style="text-align: center"><?php if ($rowdb2['PROJECTCODE'] != "") {
																	echo $rowdb2['PROJECTCODE'];
																} else {
																	echo $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];
																} ?></td>
								<td style="text-align: center"><?php echo $rowdb2['PRODUCTIONDEMANDCODE']; ?></td>
								<td><?php echo $rowdb2['PRODUCTIONORDERCODE']; ?></td>
								<td><?php echo $rowdb2['LEGALNAME1']; ?></td>
								<td style="text-align: center"><?php echo trim($rowdb2['SUBCODE02']) . trim($rowdb2['SUBCODE03']) . " " . trim($rowdb2['SUBCODE04']); ?></td>
								<td style="text-align: right"><?php echo number_format((float)$rowdb2['JQTY_J2'], 2, '.', ''); ?></td>
								<td style="text-align: right"><?php echo number_format((float)$rowdb2['JQTY_J3'], 2, '.', ''); ?></td>
								<td style="text-align: center"><?php echo number_format((float)$rBS['kg_bs'], 2, '.', ''); ?></td>
								<td style="text-align: center"><?php echo number_format((float)$perBS, 2, '.', ''); ?></td>
								<td style="text-align: center"><?php echo round($rowdb23['STDRAJUT'], 0); ?></td>
								<td style="text-align: center"><?php echo $rowdb2['JML_J1'] - $rowdb2['JML_J2']; ?></td>
								<td style="text-align: right"><?php echo number_format((float)$kRajut, 2, '.', ''); ?></td>
								<td style="text-align: center"><?php echo $rowdb2['RMPREQDATE'] . "<br>" . $rowdb2['RMPREQDATETO']; ?></td>
								<td style="text-align: center"><?php echo $stts; ?></td>
								<td style="text-align: center"><?php echo $finHari !== "" ? number_format((float)$finHari, 2, '.', '') : ""; ?></td>
								<td style="text-align: center"><?php echo $totHari !== "" ? number_format((float)$totHari, 2, '.', '') : ""; ?></td>
								<td style="text-align: center"><?php echo $Delay !== "" ? number_format((float)$Delay, 2, '.', '') : ""; ?></td>
								<td style="text-align: right"><?php echo round(($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN']), 2); ?></td>
								<td style="text-align: center"><?php echo $rowdb2['VALUEDATE']; ?></td>
								<td style="text-align: center"><?php if ($rowdb2['FINALSCHEDULEDACTUALDATE'] != "" and $rowdb2['PRODUCTIONDEMANDCODE'] != "") {
																	echo $rowdb2['FINALSCHEDULEDACTUALDATE'];
																} else if ($tglRFin == "1970-01-01") {
																} else {
																	echo $tglRFin;
																} ?></td>
								<td style="text-align: center"><?php echo substr($rowdb28['INSPECTIONSTARTDATETIME'], 0, 10); ?></td>
								<td style="text-align: center"><?php if ($tglEst == "1970-01-01") {
																} else {
																	echo $tglEst;
																} ?></td>
							</tr>

						<?php
							$no++;
						} ?>
					</tbody>
					<!--<tfoot>
                  <tr>
                    <th>No</th>
                    <th>No Mc</th>
                    <th>Sft</th>
                    <th>User</th>
                    <th>Operator</th>
					<th>Leader</th>
                    <th>NoArt</th>
                    <th>TgtCnt (100%)</th>
                    <th>Rpm</th>
                    <th>Cnt/Roll</th>
					<th>Jam Kerja</th>
				    <th>Count</th>
				    <th>Count</th>
				    <th>RL</th>
				    <th>Kgs</th>
				    <th>Grp</th>
      				<th>Tgt Grp (%)</th>
      				<th>Eff (%)</th>
      				<th>Hasil (%)</th>  
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th> 
					<th>Tanggal</th>
      				<th>Keterangan</th>
                  </tr>
                  </tfoot>-->
				</table>
			</div>
			<!-- /.card-body -->
		</div>
	</form>
</div><!-- /.container-fluid -->
<div id="DetailTurunanShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<!-- /.content -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
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
<script type="text/javascript">
	function checkAll(form1) {
		for (var i = 0; i < document.forms['form1'].elements.length; i++) {
			var e = document.forms['form1'].elements[i];
			if ((e.name != 'allbox') && (e.type == 'checkbox')) {
				e.checked = document.forms['form1'].allbox.checked;

			}
		}
	}
</script>
