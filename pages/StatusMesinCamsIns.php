<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<script>
	setTimeout(function() {
		location.reload();
	}, 20 * 60 * 1000); // 20 menit = 1200000 ms
</script>
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
						<th style="text-align: center">Project</th>
						<th style="text-align: center">DemandNo</th>
						<th style="text-align: center">Prod. Order</th>
						<th style="text-align: center">Konsumen</th>
						<th style="text-align: center">NoArt</th>
						<th style="text-align: center">Total Rajut</th>
						<th style="text-align: center">STD Qty</th>
						<th style="text-align: center">Sisa Stiker</th>
						<th style="text-align: center">Total KR Demand</th>
						<th style="text-align: center">Total KR Project</th>
						<th style="text-align: center">Hps Kg</th>
						<th style="text-align: center">Total K GK int Project</th>
						<th style="text-align: center">Tgl Delivery</th>
						<th style="text-align: center">ProgressStatus</th>
						<th style="text-align: center">Total Hari</th>
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
							ITXVIEWKNTORDER.* FROM (SELECT
								PRODUCTIONDEMAND.PROJECTCODE,
								PRODUCTIONDEMAND.COMPANYCODE,
								PRODUCTIONDEMAND.COUNTERCODE,
								PRODUCTIONDEMAND.CODE,
								PRODUCTIONDEMAND.ITEMTYPEAFICODE,
								PRODUCTIONDEMAND.SUBCODE01,
								PRODUCTIONDEMAND.SUBCODE02,
								PRODUCTIONDEMAND.SUBCODE03,
								PRODUCTIONDEMAND.SUBCODE04,
								PRODUCTIONDEMAND.BASEPRIMARYUOMCODE,
								PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,
								PRODUCTIONDEMAND.FINALPLANNEDDATE,
								PRODUCTIONDEMAND.FINALEFFECTIVEDATE,
								PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE, 
								FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
								BUSINESSPARTNER.LEGALNAME1,
								PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE,
								PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
								PRODUCTIONORDER.PROGRESSSTATUS
							FROM
								PRODUCTIONDEMAND PRODUCTIONDEMAND
							LEFT OUTER JOIN DB2ADMIN.COMPANY COMPANY ON
								PRODUCTIONDEMAND.COMPANYCODE = COMPANY.CODE
							LEFT OUTER JOIN DB2ADMIN.PRODUCTIONCUSTOMIZEDOPTIONS PRODUCTIONCUSTOMIZEDOPTIONS ON
								PRODUCTIONDEMAND.COMPANYCODE = PRODUCTIONCUSTOMIZEDOPTIONS.COMPANYCODE
							LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
								PRODUCTIONDEMAND.COMPANYCODE = FULLITEMKEYDECODER.COMPANYCODE
								AND PRODUCTIONDEMAND.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
							LEFT OUTER JOIN DB2ADMIN.ORDERPARTNER ORDERPARTNER ON
								PRODUCTIONDEMAND.CUSTOMERCODE = ORDERPARTNER.CUSTOMERSUPPLIERCODE
							LEFT OUTER JOIN DB2ADMIN.BUSINESSPARTNER BUSINESSPARTNER ON
								ORDERPARTNER.ORDERBUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID
							LEFT JOIN DB2ADMIN.PRODUCTIONDEMANDSTEP PRODUCTIONDEMANDSTEP ON
								PRODUCTIONDEMAND.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE
								AND PRODUCTIONDEMAND.COMPANYCODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCOMPANYCODE
							LEFT JOIN DB2ADMIN.PRODUCTIONORDER PRODUCTIONORDER ON
								PRODUCTIONORDER.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE
							GROUP BY
								PRODUCTIONDEMAND.PROJECTCODE,
								PRODUCTIONDEMAND.COMPANYCODE,
								PRODUCTIONDEMAND.COUNTERCODE,
								PRODUCTIONDEMAND.CODE,
								PRODUCTIONDEMAND.ITEMTYPEAFICODE,
								PRODUCTIONDEMAND.SUBCODE01,
								PRODUCTIONDEMAND.SUBCODE02,
								PRODUCTIONDEMAND.SUBCODE03,
								PRODUCTIONDEMAND.SUBCODE04,
								PRODUCTIONDEMAND.BASEPRIMARYUOMCODE,
								PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,
								PRODUCTIONDEMAND.FINALPLANNEDDATE,
								PRODUCTIONDEMAND.FINALEFFECTIVEDATE,
								PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
								FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
								BUSINESSPARTNER.LEGALNAME1,
								PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE,
								PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
								PRODUCTIONORDER.PROGRESSSTATUS) ITXVIEWKNTORDER 
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
							) DMN ON DMN.VALUESTRING = USERGENERICGROUP.CODE 	
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
					db2_execute($stmt);
					while ($rowdb2 = db2_fetch_assoc($stmt)) {
						$totHari = "";
						if ($rowdb2['PROJECTCODE'] != "") {
							$proj = $rowdb2['PROJECTCODE'];
						} else {
							$proj = $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];
						}
						$hanger = trim($rowdb2['SUBCODE02']) . trim($rowdb2['SUBCODE03']);

						$sqlDB22 = "SELECT DEMANDCODE,COUNT(BASEPRIMARYQUANTITY) AS JML, SUM(BASEPRIMARYQUANTITY) AS JQTY FROM 
									VIEWPRODDEMANDELEMENTS WHERE PROGRESSSTATUS='0' AND COMPANYCODE='100' AND DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]'
									GROUP BY DEMANDCODE ";
						//$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
						$stmt2   = db2_prepare($conn1, $sqlDB22);
						db2_execute($stmt2);
						$rowdb22 = db2_fetch_assoc($stmt2);

						$sqlDB23 = "SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT 
									FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
									WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF' AND PRODUCT.SUBCODE02='$rowdb2[SUBCODE02]' AND 
									PRODUCT.SUBCODE03='$rowdb2[SUBCODE03]' AND PRODUCT.COMPANYCODE='100' 
									ORDER BY ADSTORAGE.FIELDNAME";
						//$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
						$stmt3   = db2_prepare($conn1, $sqlDB23);
						db2_execute($stmt3);
						$rowdb23 = db2_fetch_assoc($stmt3);

						//$sqlDB24 ="SELECT DEMANDCODE, COUNT(WEIGHTREALNET) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
						//ELEMENTSINSPECTION WHERE ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' AND DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]'
						//GROUP BY DEMANDCODE";	
						//$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
						//$rowdb24 = db2_fetch_assoc($stmt4);	

						$sqlDB25 = " SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME FROM 
										ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM' AND COMPANYCODE='100'
										GROUP BY INSPECTIONENDDATETIME ";
						//$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
						$stmt5   = db2_prepare($conn1, $sqlDB25);
						db2_execute($stmt5);
						$rowdb25 = db2_fetch_assoc($stmt5);
						$sqlDB251 = " SELECT COUNT(WEIGHTREALNET ) AS JML FROM 
										ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' ";
						//$stmt51   = db2_exec($conn1,$sqlDB251, array('cursor'=>DB2_SCROLLABLE));
						$stmt51   = db2_prepare($conn1, $sqlDB251);
						db2_execute($stmt51);
						$rowdb251 = db2_fetch_assoc($stmt51);
						$sqlDB252 = " SELECT SUM(USEDBASEPRIMARYQUANTITY) AS KGPAKAI FROM DB2ADMIN.PRODUCTIONRESERVATION 
									LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
									PRODUCTIONRESERVATION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
									WHERE PRODUCTIONORDERCODE ='$rowdb2[PRODUCTIONORDERCODE]'
									GROUP BY PRODUCTIONORDERCODE ";
						//$stmt52   = db2_exec($conn1,$sqlDB252, array('cursor'=>DB2_SCROLLABLE));
						$stmt52   = db2_prepare($conn1, $sqlDB252);
						db2_execute($stmt52);
						$rowdb252 = db2_fetch_assoc($stmt52);

						$sqlDB26 = " SELECT INSPECTIONENDDATETIME FROM  
									ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' 
									ORDER BY INSPECTIONENDDATETIME ASC";
						//$stmt6   = db2_exec($conn1,$sqlDB26, array('cursor'=>DB2_SCROLLABLE));
						$stmt6   = db2_prepare($conn1, $sqlDB26);
						db2_execute($stmt6);
						$rowdb26 = db2_fetch_assoc($stmt6);

						$sqlDB26A = " SELECT
										LASTUPDATEDATETIME
									FROM
										PRODUCTIONDEMANDSTEP
									WHERE
										PRODUCTIONDEMANDCODE = '$rowdb2[PRODUCTIONDEMANDCODE]'
										AND PRODUCTIONORDERCODE = '$rowdb2[PRODUCTIONORDERCODE]'
										AND OPERATIONCODE = 'KNT1'
										AND PROGRESSSTATUS = '2'";
						//$stmt6A   = db2_exec($conn1,$sqlDB26A, array('cursor'=>DB2_SCROLLABLE));
						$stmt6A   = db2_prepare($conn1, $sqlDB26A);
						db2_execute($stmt6A);
						$rowdb26A = db2_fetch_assoc($stmt6A);

						$sqlDB27 = " SELECT LASTUPDATEDATETIME  FROM  
									PRODUCTIONDEMAND WHERE CODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ITEMTYPEAFICODE='KGF' AND COMPANYCODE ='100' 
									ORDER BY LASTUPDATEDATETIME ASC";
						//$stmt7   = db2_exec($conn1,$sqlDB27, array('cursor'=>DB2_SCROLLABLE));
						$stmt7   = db2_prepare($conn1, $sqlDB27);
						db2_execute($stmt7);
						$rowdb27 = db2_fetch_assoc($stmt7);

						$sqlDB28 = " SELECT INSPECTIONSTARTDATETIME  FROM  
									ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' 
									ORDER BY INSPECTIONSTARTDATETIME ASC";
						//$stmt8   = db2_exec($conn1,$sqlDB28, array('cursor'=>DB2_SCROLLABLE));
						$stmt8   = db2_prepare($conn1, $sqlDB28);
						db2_execute($stmt8);
						$rowdb28 = db2_fetch_assoc($stmt8);

						$sqlDB29 = " SELECT LISTAGG(x.LONGDESCRIPTION, ', ') WITHIN GROUP (ORDER BY x.STEPNUMBER) AS STS FROM DB2ADMIN.PRODUCTIONDEMANDSTEP x
										WHERE PRODUCTIONDEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' and PRODUCTIONORDERCODE ='$rowdb2[PRODUCTIONORDERCODE]' and PROGRESSSTATUS ='2'";
						//$stmt9   = db2_exec($conn1,$sqlDB29, array('cursor'=>DB2_SCROLLABLE));
						$stmt9   = db2_prepare($conn1, $sqlDB29);
						db2_execute($stmt9);
						$rowdb29 = db2_fetch_assoc($stmt9);

						$sqlDB29A = " SELECT x.LONGDESCRIPTION FROM DB2ADMIN.PRODUCTIONDEMANDSTEP x
										WHERE PRODUCTIONDEMANDCODE = '$rowdb2[PRODUCTIONDEMANDCODE]' AND PRODUCTIONORDERCODE = '$rowdb2[PRODUCTIONORDERCODE]' AND PROGRESSSTATUS = '3' AND 
										( GROUPSTEPNUMBER = '3' OR GROUPSTEPNUMBER = '4' OR GROUPSTEPNUMBER = '5' OR GROUPSTEPNUMBER = '6' )
										ORDER BY GROUPSTEPNUMBER DESC";
						//$stmt9A   = db2_exec($conn1,$sqlDB29A, array('cursor'=>DB2_SCROLLABLE));
						$stmt9A   = db2_prepare($conn1, $sqlDB29A);
						db2_execute($stmt9A);
						$rowdb29A = db2_fetch_assoc($stmt9A);
						if (trim($rowdb29['STS']) == "HOLD") {
							$OC = "HOLD";
						} else if (trim($rowdb29['STS']) == "HABIS BENANG") {
							$OC = "PBS";
						} else if (trim($rowdb29['STS']) == "TUNGGU BENANG GUDANG") {
							$OC = "PBG";
						} else if (trim($rowdb29['STS']) == "ANTRI MESIN") {
							$OC = "AMC";
						} else if (trim($rowdb29['STS']) == "TUNGGU PASANG BENANG") {
							$OC = "TPB";
						} else if (trim($rowdb29['STS']) == "Tunggu Setting") {
							$OC = "TST";
						} else if (trim($rowdb29['STS']) == "TUNGGU TES QUALITY") {
							$OC = "TTQ";
						} else {
							$OC = "";
						}
						if ($OC != "") {
							$sqlDB210 = " SELECT x.* FROM DB2ADMIN.PRODUCTIONPROGRESS x
											WHERE PRODUCTIONORDERCODE ='$rowdb2[PRODUCTIONORDERCODE]' AND PROGRESSTEMPLATECODE ='S01' AND OPERATIONCODE ='$OC'";
							//$stmt10   = db2_exec($conn1,$sqlDB210, array('cursor'=>DB2_SCROLLABLE));
							$stmt10   = db2_prepare($conn1, $sqlDB210);
							db2_execute($stmt10);
							$rowdb210 = db2_fetch_assoc($stmt10);
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
						if ($rowdb26['INSPECTIONENDDATETIME'] != "") {
							$akhirSJ = strtotime($rowdb26['INSPECTIONENDDATETIME']);
						} else {
							$akhirSJ = strtotime($rowdb26A['LASTUPDATEDATETIME']);
						}

						$diffSJ  = ($akhirSJ - $awalSJ);
						$tjamSJ  = round($diffSJ / (60 * 60), 2);
						$hariSJ  = round($tjamSJ / 24, 2);

						$awalPC  = strtotime($rowdb2['TGLS']);
						$akhirPC = strtotime($rowdb210['PROGRESSSTARTPROCESSDATE'] . " " . $rowdb210['PROGRESSSTARTPROCESSTIME']);
						$diffPC  = ($akhirPC - $awalPC);
						$tjamPC  = round($diffPC / (60 * 60), 2);
						$hariPC = round($tjamPC / 24, 2);

						$awalPC1  = strtotime($rowdb2['TGLS']);
						$akhirPC1 = strtotime($rowdb27['LASTUPDATEDATETIME']);
						$diffPC1  = ($akhirPC1 - $awalPC1);
						$tjamPC1  = round($diffPC1 / (60 * 60), 2);
						$hariPC1 = round($tjamPC1 / 24, 2);

						if ($rowdb2['RMPREQDATE'] != "") {
							$Delay = $hariDY;
						} else {
							$Delay = "";
						}
						if ($rowdb29['STS'] != "" and $rowdb25['JML'] > "0") {
							$stts = "<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Perbaikan Mesin</small>";
							$totHari = abs($hariPR);
							$StsJ = "tidak";
						} elseif (trim($rowdb29['STS']) == "HOLD") {
							$stts = "<small class='badge bg-black'><i class='far fa-clock text-black blink_me'></i> Hold</small>";
							$totHari = abs($hariPC);
							$StsJ = "tidak";
						} elseif (trim($rowdb29['STS']) == "HABIS BENANG") {
							$stts = "<small class='badge bg-pink'><i class='far fa-clock text-white blink_me'></i> Habis Benang</small>";
							$totHari = abs($hariPC);
							$StsJ = "tidak";
						} elseif (trim($rowdb29['STS']) == "TUNGGU BENANG GUDANG") {
							$stts = "<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Benang Gudang</small>";
							$totHari = abs($hariPC);
							$StsJ = "tidak";
						} elseif (trim($rowdb29['STS']) == "ANTRI MESIN") {
							$stts = "<small class='badge bg-yellow'><i class='far fa-clock text-white blink_me'></i> Antri Mesin</small>";
							$totHari = abs($hariPC);
							$StsJ = "tidak";
						} elseif (trim($rowdb29['STS']) == "TUNGGU PASANG BENANG") {
							$stts = "<small class='badge bg-purple'><i class='far fa-clock text-black blink_me'></i> Tunggu Pasang Benang</small>";
							$totHari = abs($hariPC);
							$StsJ = "tidak";
						} elseif (trim($rowdb29['STS']) == "Tunggu Setting") {
							$stts = "<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Setting</small>";
							$totHari = abs($hariPC);
							$StsJ = "tidak";
						} elseif (trim($rowdb29['STS']) == "TUNGGU TES QUALITY") {
							$stts = "<small class='badge bg-gray'><i class='far fa-clock text-black blink_me'></i> Tunggu Tes Quality</small>";
							$totHari = abs($hariPC);
							$StsJ = "tidak";
						} else if ($rowdb2['PROGRESSSTATUS'] == "6" and $rowdb251['JML'] > "0") {
							$stts = "<small class='badge bg-pink'><i class='far fa-clock blink_me'></i> Sedang Jalan Oper PO</small>";
							$totHari = abs($hariSJ);
							$StsJ = "ya";
						} else if ((trim($rowdb29['STS']) == "Knitting, Greige inspection 2, Greige Inspection" or trim($rowdb29['STS']) == "Knitting, Greige Inspection, Greige Inspection 2" or trim($rowdb29['STS']) == "Knitting, Greige Inspection"  or trim($rowdb29['STS']) == "Knitting, Greige Inspection 2" or trim($rowdb29['STS']) == "Knitting" or trim($rowdb29['STS']) == "Greige Inspection")) {
							$stts = "<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
							if (trim($rowdb29['STS']) == "Knitting, Greige Inspection" or trim($rowdb29['STS']) == "Knitting") {
								$totHari = abs($hariSJ);
								$StsJ = "ya";
							}
						} else if ($rowdb2['PRODUCTIONORDERCODE'] == "" and $rowdb2['CODE'] != "" and $rowdb29['STS'] == "") {
							$stts = "<small class='badge bg-black'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Planned</small>";
							$StsJ = "tidak";
						} elseif ($rowdb29A['LONGDESCRIPTION'] == "TUNGGU BENANG GUDANG") {
							$stts = "<small class='badge bg-yellow'><i class='far fa-clock text-white blink_me'></i> tunggu Antrian</small>";
							$StsJ = "tidak";
						} elseif ($rowdb29A['LONGDESCRIPTION'] == "ANTRI MESIN") {
							$stts = "<small class='badge bg-purple'><i class='far fa-clock text-black blink_me'></i> Antri Pasang Benang</small>";
							$StsJ = "tidak";
						} elseif ($rowdb29A['LONGDESCRIPTION'] == "TUNGGU PASANG BENANG") {
							$stts = "<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Antri Setting</small>";
							$StsJ = "tidak";
						} elseif ($rowdb2['PROGRESSSTATUS'] == "2" and trim($rowdb29['STS']) == "" and $rowdb2['PRODUCTIONORDERCODE'] != "") {
							$stts = "<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
							$totHari = abs($hariPC1);
							$StsJ = "tidak";
						} else {
							$stts = "Tidak Ada PO";
							$StsJ = "tidak";
						}

						$sqlDBKRJP = " SELECT
										SUM(PRODUCTIONDEMAND.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
										SUM(AD3.VALUEDECIMAL) AS QTYSALIN,
										SUM(AD4.VALUEDECIMAL) AS QTYOPIN ,
										SUM(AD5.VALUEDECIMAL) AS QTYOPOUT,
										SUM(PRODUCTIONDEMAND.ENTEREDBASEPRIMARYQUANTITY) AS JQTY_J2
									--	SUM(J2.JQTY) AS JQTY_J2 
									FROM
										PRODUCTIONDEMAND
									--LEFT OUTER JOIN (
									--SELECT DEMANDCODE, COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
									--ELEMENTSINSPECTION WHERE ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100'
									--GROUP BY DEMANDCODE
									--) J2 ON J2.DEMANDCODE=PRODUCTIONDEMAND.CODE		
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON
										AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD3.FIELDNAME = 'QtySalin'
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD4 ON
										AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD4.FIELDNAME = 'QtyOperIn'
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD5 ON
										AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD5.FIELDNAME = 'QtyOperOut'
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON
										AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD6.FIELDNAME = 'StatusOper'
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD7 ON
										AD7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD7.FIELDNAME = 'StatusRMP'	
									WHERE
										(PRODUCTIONDEMAND.PROJECTCODE='" . $rowdb2['ORIGDLVSALORDLINESALORDERCODE'] . "' OR PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = '" . $rowdb2['ORIGDLVSALORDLINESALORDERCODE'] . "')
										AND PRODUCTIONDEMAND.ITEMTYPEAFICODE = 'KGF'
										AND (PRODUCTIONDEMAND.PROGRESSSTATUS = '0'
											OR PRODUCTIONDEMAND.PROGRESSSTATUS = '1'
											OR PRODUCTIONDEMAND.PROGRESSSTATUS = '2'
											OR PRODUCTIONDEMAND.PROGRESSSTATUS = '6'
											OR AD6.VALUESTRING = '1' 
									--		OR AD7.VALUESTRING = '1'
									--		OR AD7.VALUESTRING = '4'
											)
										AND (
											AD7.VALUESTRING = '1' 
									--		OR AD7.VALUESTRING = '4'
											)	
										AND PRODUCTIONDEMAND.SUBCODE02 = '" . trim($rowdb2['SUBCODE02']) . "'
										AND PRODUCTIONDEMAND.SUBCODE03 = '" . trim($rowdb2['SUBCODE03']) . "'
										AND PRODUCTIONDEMAND.SUBCODE04 = '" . trim($rowdb2['SUBCODE04']) . "'	";
															//$stmtKRJP   = db2_exec($conn1,$sqlDBKRJP, array('cursor'=>DB2_SCROLLABLE));
															$stmtKRJP   = db2_prepare($conn1, $sqlDBKRJP);
															db2_execute($stmtKRJP);
															$rowdbKRJP = db2_fetch_assoc($stmtKRJP);

															$sqlDBKRJPGK = " SELECT
										SUM(PRODUCTIONDEMAND.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
										SUM(AD3.VALUEDECIMAL) AS QTYSALIN,
										SUM(AD4.VALUEDECIMAL) AS QTYOPIN ,
										SUM(AD5.VALUEDECIMAL) AS QTYOPOUT,
										SUM(PRODUCTIONDEMAND.ENTEREDBASEPRIMARYQUANTITY) AS JQTY_J2
									--	SUM(J2.JQTY) AS JQTY_J2 
									FROM
										PRODUCTIONDEMAND
									--LEFT OUTER JOIN (
									--SELECT DEMANDCODE, COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
									--ELEMENTSINSPECTION WHERE ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100'
									--GROUP BY DEMANDCODE
									--) J2 ON J2.DEMANDCODE=PRODUCTIONDEMAND.CODE		
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON
										AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD3.FIELDNAME = 'QtySalin'
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD4 ON
										AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD4.FIELDNAME = 'QtyOperIn'
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD5 ON
										AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD5.FIELDNAME = 'QtyOperOut'
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON
										AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD6.FIELDNAME = 'StatusOper'
									LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD7 ON
										AD7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
										AND AD7.FIELDNAME = 'StatusRMP'	
									WHERE
										(PRODUCTIONDEMAND.PROJECTCODE='" . $rowdb2['ORIGDLVSALORDLINESALORDERCODE'] . "' OR PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = '" . $rowdb2['ORIGDLVSALORDLINESALORDERCODE'] . "')
										AND PRODUCTIONDEMAND.ITEMTYPEAFICODE = 'KGF'
										AND (PRODUCTIONDEMAND.PROGRESSSTATUS = '0'
											OR PRODUCTIONDEMAND.PROGRESSSTATUS = '1'
											OR PRODUCTIONDEMAND.PROGRESSSTATUS = '2'
											OR PRODUCTIONDEMAND.PROGRESSSTATUS = '6'
											OR AD6.VALUESTRING = '1' 
									--		OR AD7.VALUESTRING = '1'
									--		OR AD7.VALUESTRING = '4'
											)
										AND (
									--		AD7.VALUESTRING = '1' OR 
											AD7.VALUESTRING = '4'
											)	
										AND PRODUCTIONDEMAND.SUBCODE02 = '" . trim($rowdb2['SUBCODE02']) . "'
										AND PRODUCTIONDEMAND.SUBCODE03 = '" . trim($rowdb2['SUBCODE03']) . "'
										AND PRODUCTIONDEMAND.SUBCODE04 = '" . trim($rowdb2['SUBCODE04']) . "'	";
															//$stmtKRJPGK   = db2_exec($conn1,$sqlDBKRJPGK, array('cursor'=>DB2_SCROLLABLE));
															$stmtKRJPGK   = db2_prepare($conn1, $sqlDBKRJPGK);
															db2_execute($stmtKRJPGK);
															$rowdbKRJPGK = db2_fetch_assoc($stmtKRJPGK);

															$sqlHps = " SELECT
										SUM(s.BASEPRIMARYQUANTITY) AS HAPUS_KG 
									FROM
										STOCKTRANSACTION s
									WHERE
										s.TEMPLATECODE = '098'
										AND s.ITEMTYPECODE = 'KGF'
										AND s.DECOSUBCODE02 = '" . trim($rowdb2['SUBCODE02']) . "'
										AND s.DECOSUBCODE03 = '" . trim($rowdb2['SUBCODE03']) . "'
										AND s.DECOSUBCODE04 = '" . trim($rowdb2['SUBCODE04']) . "'
										AND s.LOGICALWAREHOUSECODE = 'M502'
										AND s.PROJECTCODE = '" . $rowdb2['ORIGDLVSALORDLINESALORDERCODE'] . "'";
						//$stmtHps   = db2_exec($conn1,$sqlHps, array('cursor'=>DB2_SCROLLABLE));
						$stmtHps   = db2_prepare($conn1, $sqlHps);
						db2_execute($stmtHps);
						$rowdbHps = db2_fetch_assoc($stmtHps);

						if ($rowdb2['BASEPRIMARYQUANTITY'] > 0) {
							$kRajut = round(($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN']), 2) - round($rowdb2['JQTY_J2'], 2);
							$kRajutP = round(($rowdbKRJP['BASEPRIMARYQUANTITY'] + $rowdbKRJP['QTYOPOUT']) - ($rowdbKRJP['QTYSALIN'] + $rowdbKRJP['QTYOPIN']), 2) - (round($rowdbKRJP['JQTY_J2'], 2) - round($rowdbHps['HAPUS_KG'], 2));
							$kRajutPGK = round(($rowdbKRJPGK['BASEPRIMARYQUANTITY'] + $rowdbKRJPGK['QTYOPOUT']) - ($rowdbKRJPGK['QTYSALIN'] + $rowdbKRJPGK['QTYOPIN']), 2) - round($rowdbKRJPGK['JQTY_J2'], 2);
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
							$kRajutP = "0";
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
							<td style="text-align: right"><?php echo round($kRajutP, 2); ?></td>
							<td style="text-align: right"><?php echo round($rowdbHps['HAPUS_KG'], 2); ?></td>
							<td style="text-align: right"><?php echo round($kRajutPGK, 2); ?></td>
							<td style="text-align: center"><?php echo $rowdb2['RMPREQDATE'] . "<br>" . $rowdb2['RMPREQDATETO']; ?></td>
							<td style="text-align: center"><?php echo $stts; ?></td>
							<td style="text-align: center"><?php echo $totHari; ?></td>
						</tr>

					<?php
						$no++;
						$stts = "";
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