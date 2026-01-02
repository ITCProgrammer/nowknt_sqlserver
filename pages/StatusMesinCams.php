<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
<div class="container-fluid">

	<div class="card card-success">
		<!--<div class="card-header">
                <h3 class="card-title">Status Mesin</h3>
              </div>-->
		<!-- /.card-header -->
		<div class="card-body table-responsive">
			<table id="example12" width="100%" class="table table-sm table-striped table-bordered table-hover" style="font-size: 11px;">
				<thead>
					<tr>
						<th style="text-align: center">No MC</th>
						<th style="text-align: center">KD</th>
						<th style="text-align: center">Ukuran</th>
						<th style="text-align: center">Catatan</th>
						<th style="text-align: center">Project</th>
						<th style="text-align: center">DemandNo</th>
						<th style="text-align: center">Prod. Order</th>
						<th style="text-align: center">Konsumen</th>
						<th style="text-align: center">NoArt</th>
						<th style="text-align: center">Total Rajut</th>
						<th style="text-align: center">STD Qty</th>
						<th style="text-align: center">Sisa Stiker</th>
						<th style="text-align: center">Total Kurang Rajut</th>
						<th style="text-align: center">Tgl Delivery</th>
						<th style="text-align: center">ProgressStatus</th>
						<th style="text-align: center">Total Hari</th>
						<th style="text-align: center">Delay</th>
						<th style="text-align: center">Kebutuhan Greige</th>
						<th style="text-align: center">Rencana Mulai</th>
						<th style="text-align: center">Rencana Selesai</th>
						<th style="text-align: center">Tgl Mulai</th>
						<th style="text-align: center">Estimasi Selesai</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$sqlDB2 = " SELECT
									USERGENERICGROUP.CODE AS KDMC,
									USERGENERICGROUP.LONGDESCRIPTION,
									USERGENERICGROUP.SHORTDESCRIPTION,
									USERGENERICGROUP.SEARCHDESCRIPTION,
									J1.JML AS JML_J1,J1.JQTY AS JQTY_J1,
									J2.JML AS JML_J2,J2.JQTY AS JQTY_J2,
									DMN.*
								FROM
									DB2ADMIN.USERGENERICGROUP
								LEFT OUTER JOIN
								(
								SELECT ADSTORAGE.VALUESTRING,AD1.VALUEDATE,AD2.VALUEDATE AS RMPREQDATE, AD7.VALUEDATE AS RMPREQDATETO ,AD3.VALUEDECIMAL AS QTYSALIN,
								AD4.VALUEDECIMAL AS QTYOPIN , AD5.VALUEDECIMAL AS QTYOPOUT, AD6.VALUESTRING AS STSOPR, CURRENT_TIMESTAMP AS TGLS,AD8.VALUESTRING AS STSMC, 
								ITXVIEWKNTORDER.* FROM ITXVIEWKNTORDER 
								LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
								LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
								LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.FIELDNAME ='TglRencana'
								LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.FIELDNAME ='RMPReqDate'
								LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.FIELDNAME ='QtySalin'
								LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD4 ON AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD4.FIELDNAME ='QtyOperIn'
								LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD5 ON AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD5.FIELDNAME ='QtyOperOut'
								LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD6.FIELDNAME ='StatusOper'
								LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD7 ON AD7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD7.FIELDNAME ='RMPGreigeReqDateTo'
								LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD8 ON AD8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD8.FIELDNAME ='StatusMesin'
								WHERE PRODUCTIONDEMAND.ITEMTYPEAFICODE ='KGF' AND (PRODUCTIONDEMAND.PROGRESSSTATUS='0' OR PRODUCTIONDEMAND.PROGRESSSTATUS='1' OR PRODUCTIONDEMAND.PROGRESSSTATUS='2' OR AD6.VALUESTRING='1') ORDER BY AD1.VALUEDATE ASC
								) DMN ON DMN.VALUESTRING=USERGENERICGROUP.CODE 	
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
								WHERE
									USERGENERICGROUP.USERGENERICGROUPTYPECODE = 'MCK'
									AND 
								USERGENERICGROUP.USERGENGROUPTYPECOMPANYCODE = '100'
									AND 
								USERGENERICGROUP.OWNINGCOMPANYCODE = '100' ";

					$no = 1;
					$c = 0;
					//$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
					$stmt   = db2_prepare($conn1, $sqlDB2);
					if (!$stmt || !db2_execute($stmt)) {
						echo "<div class='alert alert-danger'>Query error di " . basename(__FILE__) . ": " . db2_stmt_errormsg() . "</div>";
					}
					while ($stmt && ($rowdb2 = db2_fetch_assoc($stmt))) {
						$totHari = "";
						if ($rowdb2['PROJECTCODE'] != "") {
							$proj = $rowdb2['PROJECTCODE'];
						} else {
							$proj = $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];
						}
						$hanger = trim($rowdb2['SUBCODE02']) . trim($rowdb2['SUBCODE03']);
						$sqlDB23 = "SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT 
									FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
									WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF' AND PRODUCT.SUBCODE02='$rowdb2[SUBCODE02]' AND 
									PRODUCT.SUBCODE03='$rowdb2[SUBCODE03]' AND PRODUCT.COMPANYCODE='100' 
									ORDER BY ADSTORAGE.FIELDNAME";
						//$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
						$stmt3   = db2_prepare($conn1, $sqlDB23);
						if ($stmt3 && db2_execute($stmt3)) {
							$rowdb23 = db2_fetch_assoc($stmt3);
						} else {
							$rowdb23 = [];
						}

						$sqlDB25 = " SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME FROM 
									ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM' AND COMPANYCODE='100'
									GROUP BY INSPECTIONENDDATETIME ";
						$stmt5   = db2_prepare($conn1, $sqlDB25);
						if ($stmt5 && db2_execute($stmt5)) {
							$rowdb25 = db2_fetch_assoc($stmt5);
						} else {
							$rowdb25 = [];
						}

						$sqlDB251 = " SELECT COUNT(WEIGHTREALNET ) AS JML FROM 
										ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' ";
						//$stmt51   = db2_exec($conn1,$sqlDB251, array('cursor'=>DB2_SCROLLABLE));
						$stmt51   = db2_prepare($conn1, $sqlDB251);
						if ($stmt51 && db2_execute($stmt51)) {
							$rowdb251 = db2_fetch_assoc($stmt51);
						} else {
							$rowdb251 = [];
						}
						$sqlDB252 = " SELECT SUM(USEDBASEPRIMARYQUANTITY) AS KGPAKAI FROM DB2ADMIN.PRODUCTIONRESERVATION 
									LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
									PRODUCTIONRESERVATION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
									WHERE PRODUCTIONORDERCODE ='$rowdb2[PRODUCTIONORDERCODE]'
									GROUP BY PRODUCTIONORDERCODE ";
						//$stmt52   = db2_exec($conn1,$sqlDB252, array('cursor'=>DB2_SCROLLABLE));
						$stmt52   = db2_prepare($conn1, $sqlDB252);
						if ($stmt52 && db2_execute($stmt52)) {
							$rowdb252 = db2_fetch_assoc($stmt52);
						} else {
							$rowdb252 = [];
						}

						$sqlDB26 = " SELECT INSPECTIONENDDATETIME  FROM  
									ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' 
									ORDER BY INSPECTIONENDDATETIME ASC";
						//$stmt6   = db2_exec($conn1,$sqlDB26, array('cursor'=>DB2_SCROLLABLE));
						$stmt6   = db2_prepare($conn1, $sqlDB26);
						if ($stmt6 && db2_execute($stmt6)) {
							$rowdb26 = db2_fetch_assoc($stmt6);
						} else {
							$rowdb26 = [];
						}

						$sqlDB27 = " SELECT LASTUPDATEDATETIME  FROM  
									PRODUCTIONDEMAND WHERE CODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ITEMTYPEAFICODE='KGF' AND COMPANYCODE ='100' 
									ORDER BY LASTUPDATEDATETIME ASC";
						//$stmt7   = db2_exec($conn1,$sqlDB27, array('cursor'=>DB2_SCROLLABLE));
						$stmt7   = db2_prepare($conn1, $sqlDB27);
						if ($stmt7 && db2_execute($stmt7)) {
							$rowdb27 = db2_fetch_assoc($stmt7);
						} else {
							$rowdb27 = [];
						}

						$sqlDB28 = " SELECT INSPECTIONSTARTDATETIME  FROM  
									ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' 
									ORDER BY INSPECTIONSTARTDATETIME ASC";
						//$stmt8   = db2_exec($conn1,$sqlDB28, array('cursor'=>DB2_SCROLLABLE));
						$stmt8   = db2_prepare($conn1, $sqlDB28);
						if ($stmt8 && db2_execute($stmt8)) {
							$rowdb28 = db2_fetch_assoc($stmt8);
						} else {
							$rowdb28 = [];
						}

						$sqlDB29 = " SELECT LISTAGG(x.LONGDESCRIPTION, ', ') WITHIN GROUP (ORDER BY x.STEPNUMBER) AS STS FROM DB2ADMIN.PRODUCTIONDEMANDSTEP x
									WHERE PRODUCTIONDEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' and PRODUCTIONORDERCODE ='$rowdb2[PRODUCTIONORDERCODE]' and PROGRESSSTATUS ='2'";
						//$stmt9   = db2_exec($conn1,$sqlDB29, array('cursor'=>DB2_SCROLLABLE));
						$stmt9   = db2_prepare($conn1, $sqlDB29);
						if ($stmt9 && db2_execute($stmt9)) {
							$rowdb29 = db2_fetch_assoc($stmt9);
						} else {
							$rowdb29 = [];
						}
						$stts =	$rowdb29['STS'];
						if (substr($rowdb2['PROJECTCODE'], 0, 3) == "OPN" or substr($rowdb2['ORIGDLVSALORDLINESALORDERCODE'], 0, 3) == "OPN") {
							$sqlDB2R = " SELECT
											SUM(INSP.JQTY) AS JQTY
										FROM
											ITXVIEWHEADERKNTORDER a
										LEFT OUTER JOIN PRODUCTIONDEMAND p ON
											p.CODE = a.PRODUCTIONDEMANDCODE
										LEFT OUTER JOIN (
											SELECT
												DEMANDCODE,
												COUNT(WEIGHTREALNET) AS JML,
												SUM(WEIGHTREALNET) AS JQTY
											FROM 
												ELEMENTSINSPECTION
											WHERE
												ELEMENTITEMTYPECODE = 'KGF'
											GROUP BY
												DEMANDCODE		  
										) INSP ON
											INSP.DEMANDCODE = p.CODE
										WHERE
											a.ITEMTYPEAFICODE = 'KGF'
											AND (a.PROJECTCODE = '$proj'
												OR a.ORIGDLVSALORDLINESALORDERCODE = '$proj')
											AND
										CONCAT(TRIM(a.SUBCODE02), TRIM(a.SUBCODE03))= '$hanger'
											AND (a.PROGRESSSTATUS = '2'
												OR a.PROGRESSSTATUS = '6') ";
							//		  $stmtR   = db2_exec($conn1,$sqlDB2R, array('cursor'=>DB2_SCROLLABLE));
							$stmtR   = db2_prepare($conn1, $sqlDB2R);
							if ($stmtR && db2_execute($stmtR)) {
								$rowdb2R = db2_fetch_assoc($stmtR);
							} else {
								$rowdb2R = [];
							}
						}

						$awalDY  = strtotime($rowdb2['TGLS']);
						$akhirDY = strtotime($rowdb2['RMPREQDATE']);
						$diffDY  = ($akhirDY - $awalDY);
						$tjamDY  = round($diffDY / (60 * 60), 2);
						$hariDY  = round($tjamDY / 24);

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

						if ($rowdb2['RMPREQDATE'] != "") {
							$Delay = $hariDY;
						} else {
							$Delay = "";
						}
						if ($rowdb29['STS'] != "" and $rowdb25['JML'] > "0") {
							$stts = "<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Perbaikan Mesin</small>";
							$totHari = abs($hariPR);
							$StsJ = "tidak";
							//		}		 
							//		elseif(trim($rowdb29['STS'])=="HOLD"){
							//			$stts="<small class='badge bg-black'><i class='far fa-clock text-black blink_me'></i> Hold</small>";
							//			$totHari=abs($hariPC);
							//			$StsJ="tidak";	
							//		}			
							//			elseif(trim($rowdb29['STS'])=="HABIS BENANG"){
							//			$stts="<small class='badge bg-pink'><i class='far fa-clock text-white blink_me'></i> Habis Benang</small>";
							//			$totHari=abs($hariPC);
							//			$StsJ="tidak";	
							//		}
							//		elseif(trim($rowdb29['STS'])=="TUNGGU BENANG GUDANG"){
							//			$stts="<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Benang Gudang</small>";
							//			$totHari=abs($hariPC);
							//			$StsJ="tidak";
							//		}
							//		elseif(trim($rowdb29['STS'])=="ANTRI MESIN"){
							//			$stts="<small class='badge bg-yellow'><i class='far fa-clock text-white blink_me'></i> Antri Mesin</small>";
							//			$totHari=abs($hariPC);
							//			$StsJ="tidak";
							//		}elseif(trim($rowdb29['STS'])=="TUNGGU PASANG BENANG"){
							//			$stts="<small class='badge bg-purple'><i class='far fa-clock text-black blink_me'></i> Tunggu Pasang Benang</small>";
							//			$totHari=abs($hariPC);
							//			$StsJ="tidak";
							//		}
							//		elseif(trim($rowdb29['STS'])=="Tunggu Setting"){
							//			$stts="<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Setting</small>";
							//			$totHari=abs($hariPC);
							//			$StsJ="tidak";
							//		}
							//		elseif(trim($rowdb29['STS'])=="TUNGGU TES QUALITY"){
							//			$stts="<small class='badge bg-gray'><i class='far fa-clock text-black blink_me'></i> Tunggu Tes Quality</small>";
							//			$totHari=abs($hariPC);
							//			$StsJ="tidak";	
						} else if ($rowdb2['PROGRESSSTATUS'] == "6" and $rowdb251['JML'] > "0") {
							$stts = "<small class='badge bg-pink'><i class='far fa-clock blink_me'></i> Sedang Jalan Oper PO</small>";
							$totHari = abs($hariSJ);
							$StsJ = "ya";
							//		}else if($rowdb251['JML']>"0" and (trim($rowdb29['STS'])=="Greige Inspection" or trim($rowdb29['STS'])=="Greige inspection 2, Greige Inspection") ) {
							//			$stts="<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
							//			$totHari=abs($hariSJ);
							//			$StsJ="ya";	
						} elseif ($rowdb29['STS'] != "") {
							$stts = $rowdb29['STS'];
						} else if ($rowdb2['PRODUCTIONORDERCODE'] == "" and $rowdb2['CODE'] != "" and $rowdb29['STS'] == "") {
							$stts = "<small class='badge bg-black'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Planned</small>";
							$StsJ = "tidak";
						} elseif ($rowdb2['PROGRESSSTATUS'] == "2" and $rowdb29['STS'] == "" and $rowdb2['PRODUCTIONORDERCODE'] != "") {
							$stts = "<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
							$totHari = abs($hariPC);
							$StsJ = "tidak";
						} else {
							$stts = "Tidak Ada PO";
							$StsJ = "tidak";
						}

						//		elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['STSOPR']=="1") and trim($rowdb2['STSMC'])=="2" ){
						//			$stts="<small class='badge bg-yellow'><i class='far fa-clock text-white blink_me'></i> Antri Mesin</small>";
						//			$totHari=abs($hariPC);
						//			$StsJ="tidak";
						//		}				 
						//		elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['STSOPR']=="1") and trim($rowdb2['STSMC'])=="4" ){
						//			$stts="<small class='badge bg-pink'><i class='far fa-clock text-white blink_me'></i> Habis Benang</small>";
						//			$totHari=abs($hariPC);
						//			$StsJ="tidak";
						//		}		 
						//		elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['STSOPR']=="1") and trim($rowdb2['STSMC'])=="5" ){
						//			$stts="<small class='badge bg-gray'><i class='far fa-clock text-black blink_me'></i> Tunggu Tes Quality</small>";
						//			$totHari=abs($hariPC);
						//			$StsJ="tidak";
						//		}	
						//		elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['STSOPR']=="1") and trim($rowdb2['STSMC'])=="3" ){
						//			$stts="<small class='badge bg-black'><i class='far fa-clock text-black blink_me'></i> Hold</small>";
						//			$totHari=abs($hariPC);
						//			$StsJ="tidak";
						//		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['STSOPR']=="1") and trim($rowdb2['STSMC'])=="1" and round($rowdb252['KGPAKAI'])==0){
						//			$stts="<small class='badge bg-purple'><i class='far fa-clock text-black blink_me'></i> Tunggu Pasang Benang</small>";
						//			$totHari=abs($hariPC);
						//			$StsJ="tidak";
						//		}
						//		elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['STSOPR']=="1") and trim($rowdb2['STSMC'])=="1" and round($rowdb252['KGPAKAI'])>0 and $rowdb251['JML']=="0"){
						//			$stts="<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Setting</small>";
						//			$totHari=abs($hariPC);
						//			$StsJ="tidak";		
						//		}else if($rowdb2['PROGRESSSTATUS']=="6" and $rowdb2['STSOPR']=="1" and $rowdb251['JML']>"0" ) {
						//			$stts="<small class='badge bg-pink'><i class='far fa-clock blink_me'></i> Sedang Jalan Oper PO</small>";
						//			$totHari=abs($hariSJ);
						//			$StsJ="ya";	
						//		}else if(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['STSOPR']=="1") and (trim($rowdb2['STSMC'])=="0" or trim($rowdb2['STSMC'])=="1") and $rowdb251['JML']>"0" ) {
						//			$stts="<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
						//			$totHari=abs($hariSJ);
						//			$StsJ="ya";
						//		}else if($rowdb2['PRODUCTIONORDERCODE']=="" and $rowdb2['CODE']!=""){
						//			$stts="<small class='badge bg-black'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Planned</small>";
						//			$StsJ="tidak";
						//		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['STSOPR']=="1") and trim($rowdb2['STSMC'])=="0" and round($rowdb252['KGPAKAI'])==0){
						//			$stts="<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
						//			$totHari=abs($hariPC);
						//			$StsJ="tidak";	
						//		}


						$sqlKBG = sqlsrv_query($con, "SELECT SUM(berat) AS tot FROM dbknitt.tbl_pembagian_greige_now WHERE no_po = ?", [$proj]);
						$rKBG = $sqlKBG ? sqlsrv_fetch_array($sqlKBG, SQLSRV_FETCH_ASSOC) : ['tot' => 0];

						if ($rowdb2['BASEPRIMARYQUANTITY'] > 0) {
							$kRajut = round(($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN']), 2) - round($rowdb2['JQTY_J2'], 2);
							$kHari = round($kRajut / round($rowdb23['STDRAJUT'], 0));
							$StHari = round((($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN'])) / round($rowdb23['STDRAJUT'], 0));
							if ($kHari >= 0) {
								if ($StsJ == "ya") {
									$tglEst = date('Y-m-d', strtotime($kHari . " days", strtotime($rowdb2['TGLS'])));
								} else {
									$tglEst = "";
								}
							} else {
								$tglEst = "";
							}
							if ($rowdb2['PRODUCTIONDEMANDCODE'] != "" and $rowdb2['VALUEDATE'] != "") {
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
							<td style="text-align: right"><?php echo round($rowdb2['JQTY_J2'], 2); ?></td>
							<td style="text-align: center"><?php echo round($rowdb23['STDRAJUT'], 0); ?></td>
							<td style="text-align: center"><?php echo $rowdb2['JML_J1'] - $rowdb2['JML_J2']; ?></td>
							<td style="text-align: right"><?php echo round($kRajut, 2); ?></td>
							<td style="text-align: center"><?php echo $rowdb2['RMPREQDATE'] . "<br>" . $rowdb2['RMPREQDATETO']; ?></td>
							<td style="text-align: center"><?php echo $stts; ?></td>
							<td style="text-align: center"><?php echo $totHari; ?></td>
							<td style="text-align: center"><?php echo $Delay; ?></td>
							<td style="text-align: right"><?php if (substr(trim($rowdb2['PROJECTCODE']), 0, 3) == "OPN" or substr(trim($rowdb2['ORIGDLVSALORDLINESALORDERCODE']), 0, 3) == "OPN") {
																echo "<a href='#' class='show_detail_allokasi' id='" . $proj . "-" . $hanger . "-" . $rowdb2['PRODUCTIONDEMANDCODE'] . "'>" . round($rKBG['tot'], 2) - round($rowdb2R['JQTY'], 2) . "</a>";
															} else {
																echo "<a href='#' class='show_detail_allokasi' id='" . $proj . "-" . $hanger . "-" . $rowdb2['PRODUCTIONDEMANDCODE'] . "'>" . round((round(($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN']), 2)) - round($rowdb2['JQTY_J2'], 2), 2) . "</a>";
															} ?></td>
							<td style="text-align: center"><?php if ($rowdb2['INITIALSCHEDULEDACTUALDATE'] != "") {
																echo $rowdb2['INITIALSCHEDULEDACTUALDATE'];
															} else {
																echo $rowdb2['VALUEDATE'];
															} ?></td>
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
			</table>
		</div>
		<!-- /.card-body -->
	</div>
</div><!-- /.container-fluid -->
<!-- /.content -->
<div id="DetailAllokasiShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
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