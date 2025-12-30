<?php
require_once "koneksi.php";
mysqli_query($con_nowprd, "DELETE FROM statusmesin WHERE CREATEDATETIME BETWEEN NOW() - INTERVAL 3 DAY AND NOW() - INTERVAL 1 DAY");
mysqli_query($con_nowprd, "DELETE FROM statusmesin WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 
mysqli_query($con_nowprd, "DELETE FROM statusmesin_inspectionenddatetime WHERE CREATEDATETIME BETWEEN NOW() - INTERVAL 3 DAY AND NOW() - INTERVAL 1 DAY");
mysqli_query($con_nowprd, "DELETE FROM statusmesin_inspectionenddatetime WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 
mysqli_query($con_nowprd, "DELETE FROM statusmesin_tglmulai WHERE CREATEDATETIME BETWEEN NOW() - INTERVAL 3 DAY AND NOW() - INTERVAL 1 DAY");
mysqli_query($con_nowprd, "DELETE FROM statusmesin_tglmulai WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 

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
						<!--<th style="text-align: center">BS</th>
                    	<th style="text-align: center">% BS</th>-->
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
						// DB2 TO MYSQLI 1
							$sqlDB2 = "SELECT
											USERGENERICGROUP.CODE AS KDMC,
											USERGENERICGROUP.LONGDESCRIPTION,
											USERGENERICGROUP.SHORTDESCRIPTION,
											USERGENERICGROUP.SEARCHDESCRIPTION,
											DMN.*,
											J1.JML AS JML_J1,
											J1.JQTY AS JQTY_J1,
											J2.JML AS JML_J2,
											J2.JQTY AS JQTY_J2,
											S1.IDS
										FROM
											DB2ADMIN.USERGENERICGROUP
										LEFT JOIN
										(
												SELECT
													ADSTORAGE.VALUESTRING,
													AD1.VALUEDATE,
													AD2.VALUEDATE AS RMPREQDATE,
													AD7.VALUEDATE AS RMPREQDATETO,
													AD3.VALUEDECIMAL AS QTYSALIN,
													AD4.VALUEDECIMAL AS QTYOPIN ,
													AD5.VALUEDECIMAL AS QTYOPOUT,
													AD6.VALUESTRING AS STSOPR,
													ITXVIEWKNTORDER.*,
													CURRENT_TIMESTAMP AS TGLS,
													PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE
												FROM
													ITXVIEWKNTORDER
												LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
												LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID	AND ADSTORAGE.NAMENAME = 'MachineNo'
												LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME = 'TglRencana'
												LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME = 'RMPReqDate'
												LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.NAMENAME = 'QtySalin'
												LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD4 ON AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD4.NAMENAME = 'QtyOperIn'
												LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD5 ON AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD5.NAMENAME = 'QtyOperOut'
												LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD6.NAMENAME = 'StatusOper'
												LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD7 ON AD7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD7.NAMENAME = 'RMPGreigeReqDateTo'
												WHERE
													ITXVIEWKNTORDER.ITEMTYPEAFICODE = 'KGF'
													AND (PRODUCTIONDEMAND.PROGRESSSTATUS = '2' OR AD6.VALUESTRING = '1'	)
												ORDER BY
													ITXVIEWKNTORDER.INITIALSCHEDULEDACTUALDATE,
													AD1.VALUEDATE ASC ) DMN ON (DMN.VALUESTRING = USERGENERICGROUP.CODE	OR DMN.SCHEDULEDRESOURCECODE = USERGENERICGROUP.CODE)
										LEFT OUTER JOIN (
												SELECT
													DEMANDCODE,
													COUNT(BASEPRIMARYQUANTITY) AS JML,
													SUM(BASEPRIMARYQUANTITY) AS JQTY
												FROM
													VIEWPRODDEMANDELEMENTS
												WHERE
													PROGRESSSTATUS = '0'
													AND COMPANYCODE = '100'
												GROUP BY
													DEMANDCODE) J1 ON J1.DEMANDCODE = DMN.CODE
										LEFT OUTER JOIN (
												SELECT
													DEMANDCODE,
													COUNT(WEIGHTREALNET) AS JML,
													SUM(WEIGHTREALNET) AS JQTY
												FROM
													ELEMENTSINSPECTION
												WHERE
													ELEMENTITEMTYPECODE = 'KGF'
													AND COMPANYCODE = '100'
												GROUP BY
													DEMANDCODE) J2 ON J2.DEMANDCODE = DMN.CODE
										LEFT OUTER JOIN (
												SELECT
													PRODUCTIONDEMANDCODE,
													trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) AS IDS
												FROM
													PRODUCTIONDEMANDSTEP
												WHERE
													(OPERATIONCODE = 'INS1'	OR OPERATIONCODE = 'KNT1')AND ITEMTYPEAFICOMPANYCODE = '100'
												GROUP BY
													PRODUCTIONDEMANDCODE) S1 ON S1.PRODUCTIONDEMANDCODE = DMN.CODE
										WHERE
											USERGENERICGROUP.USERGENERICGROUPTYPECODE = 'MCK' AND 
											USERGENERICGROUP.USERGENGROUPTYPECOMPANYCODE = '100' AND 
											USERGENERICGROUP.OWNINGCOMPANYCODE = '100'";

							$no = 1;
							$c = 0;
							$stmt   = db2_exec($conn1, $sqlDB2);
							while ($row_statusmesin = db2_fetch_assoc($stmt)) {
								$r_statusmesin[]      = "('".TRIM(addslashes($row_statusmesin['KDMC']))."',"
														."'".TRIM(addslashes($row_statusmesin['LONGDESCRIPTION']))."',"
														."'".TRIM(addslashes($row_statusmesin['SHORTDESCRIPTION']))."',"
														."'".TRIM(addslashes($row_statusmesin['SEARCHDESCRIPTION']))."',"
														."'".TRIM(addslashes($row_statusmesin['VALUESTRING']))."',"
														."'".TRIM(addslashes($row_statusmesin['VALUEDATE']))."',"
														."'".TRIM(addslashes($row_statusmesin['RMPREQDATE']))."',"
														."'".TRIM(addslashes($row_statusmesin['RMPREQDATETO']))."',"
														."'".TRIM(addslashes($row_statusmesin['QTYSALIN']))."',"
														."'".TRIM(addslashes($row_statusmesin['QTYOPIN']))."',"
														."'".TRIM(addslashes($row_statusmesin['QTYOPOUT']))."',"
														."'".TRIM(addslashes($row_statusmesin['STSOPR']))."',"
														."'".TRIM(addslashes($row_statusmesin['PROJECTCODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['COMPANYCODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['COUNTERCODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['CODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['ITEMTYPEAFICODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['SUBCODE01']))."',"
														."'".TRIM(addslashes($row_statusmesin['SUBCODE02']))."',"
														."'".TRIM(addslashes($row_statusmesin['SUBCODE03']))."',"
														."'".TRIM(addslashes($row_statusmesin['SUBCODE04']))."',"
														."'".TRIM(addslashes($row_statusmesin['BASEPRIMARYUOMCODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['BASEPRIMARYQUANTITY']))."',"
														."'".TRIM(addslashes($row_statusmesin['FINALPLANNEDDATE']))."',"
														."'".TRIM(addslashes($row_statusmesin['FINALEFFECTIVEDATE']))."',"
														."'".TRIM(addslashes($row_statusmesin['ORIGDLVSALORDLINESALORDERCODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['SUMMARIZEDDESCRIPTION']))."',"
														."'".TRIM(addslashes($row_statusmesin['LEGALNAME1']))."',"
														."'".TRIM(addslashes($row_statusmesin['PRODUCTIONDEMANDCODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['PRODUCTIONORDERCODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['SCHEDULEDRESOURCECODE']))."',"
														."'".TRIM(addslashes($row_statusmesin['INITIALSCHEDULEDACTUALDATE']))."',"
														."'".TRIM(addslashes($row_statusmesin['FINALSCHEDULEDACTUALDATE']))."',"
														."'".TRIM(addslashes($row_statusmesin['PROGRESSSTATUS']))."',"
														."'".TRIM(addslashes($row_statusmesin['TGLS']))."',"
														."'".TRIM(addslashes($row_statusmesin['JML_J1']))."',"
														."'".TRIM(addslashes($row_statusmesin['JQTY_J1']))."',"
														."'".TRIM(addslashes($row_statusmesin['JML_J2']))."',"
														."'".TRIM(addslashes($row_statusmesin['JQTY_J2']))."',"
														."'".TRIM(addslashes($row_statusmesin['IDS']))."',"
														."'".$_SERVER['REMOTE_ADDR']."',"
														."'".date('Y-m-d H:i:s')."')";
							}
							$value_statusmesin        = implode(',', $r_statusmesin);
							$insert_statusmesin       = mysqli_query($con_nowprd, "INSERT INTO statusmesin(KDMC,LONGDESCRIPTION,SHORTDESCRIPTION,SEARCHDESCRIPTION,VALUESTRING,VALUEDATE,RMPREQDATE,RMPREQDATETO,QTYSALIN,QTYOPIN,QTYOPOUT,STSOPR,PROJECTCODE,COMPANYCODE,COUNTERCODE,CODE,ITEMTYPEAFICODE,SUBCODE01,SUBCODE02,SUBCODE03,SUBCODE04,BASEPRIMARYUOMCODE,BASEPRIMARYQUANTITY,FINALPLANNEDDATE,FINALEFFECTIVEDATE,ORIGDLVSALORDLINESALORDERCODE,SUMMARIZEDDESCRIPTION,LEGALNAME1,PRODUCTIONDEMANDCODE,PRODUCTIONORDERCODE,SCHEDULEDRESOURCECODE,INITIALSCHEDULEDACTUALDATE,FINALSCHEDULEDACTUALDATE,PROGRESSSTATUS,TGLS,JML_J1,JQTY_J1,JML_J2,JQTY_J2,IDS,IPADDRESS,CREATEDATETIME) VALUES $value_statusmesin");
						// DB2 TO MYSQLI 1

						// DB2 TO MYSQLI 2
							$sqlDB25 = "SELECT
											TRIM(DEMANDCODE) AS DEMANDCODE,
											COUNT(WEIGHTREALNET) AS JML,
											INSPECTIONENDDATETIME
										FROM 
											ELEMENTSINSPECTION
										WHERE
											ELEMENTITEMTYPECODE = 'KGF'
											AND QUALITYREASONCODE = 'PM'
											AND COMPANYCODE = '100'
										GROUP BY
											INSPECTIONENDDATETIME, DEMANDCODE";
							$stmt5_inspectionenddatetime   = db2_exec($conn1, $sqlDB25);
							while ($row_statusmesin_inspectionenddatetime = db2_fetch_assoc($stmt5_inspectionenddatetime)) {
								$r_statusmesin_inspectionenddatetime[]      = "('".TRIM(addslashes($row_statusmesin_inspectionenddatetime['DEMANDCODE']))."',"
																			."'".TRIM(addslashes($row_statusmesin_inspectionenddatetime['JML']))."',"
																			."'".TRIM(addslashes($row_statusmesin_inspectionenddatetime['INSPECTIONENDDATETIME']))."',"
																			."'".$_SERVER['REMOTE_ADDR']."',"
																			."'".date('Y-m-d H:i:s')."')";
							}
							$value_statusmesin_inspectionenddatetime        = implode(',', $r_statusmesin_inspectionenddatetime);
							$insert_statusmesin_inspectionenddatetime       = mysqli_query($con_nowprd, "INSERT INTO statusmesin_inspectionenddatetime(DEMANDCODE,JML,INSPECTIONENDDATETIME,IPADDRESS,CREATEDATETIME) VALUES $value_statusmesin_inspectionenddatetime");
						// DB2 TO MYSQLI 2
						
						// DB2 TO MYSQLI 3
							$sqlDB28 = "SELECT
											MIN(INSPECTIONSTARTDATETIME) AS INSPECTIONSTARTDATETIME,
											DEMANDCODE
										FROM  
											ELEMENTSINSPECTION
										WHERE
											ELEMENTITEMTYPECODE = 'KGF'
											AND COMPANYCODE = '100'
										GROUP BY DEMANDCODE";
							$stmt5_tglmulai   = db2_exec($conn1, $sqlDB28);
							while ($row_statusmesin_tglmulai = db2_fetch_assoc($stmt5_tglmulai)) {
								$r_statusmesin_tglmulai[]      = "('".TRIM(addslashes($row_statusmesin_tglmulai['DEMANDCODE']))."',"
																			."'".TRIM(addslashes($row_statusmesin_tglmulai['INSPECTIONSTARTDATETIME']))."',"
																			."'".$_SERVER['REMOTE_ADDR']."',"
																			."'".date('Y-m-d H:i:s')."')";
							}
							$value_statusmesin_tglmulai        = implode(',', $r_statusmesin_tglmulai);
							$insert_statusmesin_tglmulai       = mysqli_query($con_nowprd, "INSERT INTO statusmesin_tglmulai(DEMANDCODE,INSPECTIONSTARTDATETIME,IPADDRESS,CREATEDATETIME) VALUES $value_statusmesin_tglmulai");
						// DB2 TO MYSQLI 3

						$sqlDB2 = "SELECT DISTINCT *,
													CASE
														WHEN sm.RMPREQDATE = '0000-00-00' THEN ''
														ELSE sm.RMPREQDATE
													END AS RMPREQDATE_CASE,
													CASE
														WHEN sm.RMPREQDATETO = '0000-00-00' THEN ''
														ELSE sm.RMPREQDATETO
													END AS RMPREQDATETO_CASE,
													datediff(sm.TGLS, smtm.INSPECTIONSTARTDATETIME) AS HariSJ
												FROM 
													statusmesin sm
												LEFT JOIN statusmesin_tglmulai smtm ON smtm.DEMANDCODE = sm.productiondemandcode 
																				AND sm.IPADDRESS = smtm.IPADDRESS
												WHERE 
													sm.IPADDRESS = '$_SERVER[REMOTE_ADDR]'";
              			$stmt2   = mysqli_query($con_nowprd,$sqlDB2);
						while ($rowdb2 = mysqli_fetch_array($stmt2)) {
							$totHari = "";

							if ($rowdb2['PROJECTCODE'] != "") {
								$proj = $rowdb2['PROJECTCODE'];
							} else {
								$proj = $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];
							}
							$hanger = trim($rowdb2['SUBCODE02']) . trim($rowdb2['SUBCODE03']);	
							$sqlDB23 = "SELECT
											ADSTORAGE.NAMENAME,
											ADSTORAGE.FIELDNAME,
											(ADSTORAGE.VALUEDECIMAL * 24) AS STDRAJUT
										FROM
											DB2ADMIN.PRODUCT PRODUCT
										LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON
											PRODUCT.ABSUNIQUEID = ADSTORAGE.UNIQUEID
										WHERE
											ADSTORAGE.NAMENAME = 'ProductionRate'
											AND PRODUCT.ITEMTYPECODE = 'KGF'
											AND PRODUCT.SUBCODE02 = '$rowdb2[SUBCODE02]'
											AND PRODUCT.SUBCODE03 = '$rowdb2[SUBCODE03]'
											AND PRODUCT.COMPANYCODE = '100'
										ORDER BY
											ADSTORAGE.FIELDNAME";
							$stmt3   = db2_exec($conn1, $sqlDB23, array('cursor' => DB2_SCROLLABLE));
							$rowdb23 = db2_fetch_assoc($stmt3);
if (substr($rowdb2['PROJECTCODE'], 0, 3) == "OPN" or substr($rowdb2['ORIGDLVSALORDLINESALORDERCODE'], 0, 3) == "OPN") {								$sqlDB2R =" SELECT
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
		  $stmtR   = db2_exec($conn1,$sqlDB2R, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb2R = db2_fetch_assoc($stmtR);
}
							$q_statusmesin_inspectionenddatetime = "SELECT DISTINCT * 
																		FROM statusmesin_inspectionenddatetime 
																		WHERE 
																			DEMANDCODE = '$rowdb2[PRODUCTIONDEMANDCODE]' 
																			AND IPADDRESS = '$_SERVER[REMOTE_ADDR]'";
							$stmt5   = mysqli_query($con_nowprd,$q_statusmesin_inspectionenddatetime);
							$rowdb25 = mysqli_fetch_assoc($stmt5);

							// $sqlDB26 = "SELECT
							// 				INSPECTIONENDDATETIME
							// 			FROM  
							// 				ELEMENTSINSPECTION
							// 			WHERE
							// 				DEMANDCODE = '$rowdb2[PRODUCTIONDEMANDCODE]'
							// 				AND ELEMENTITEMTYPECODE = 'KGF'
							// 				AND COMPANYCODE = '100'
							// 			ORDER BY
							// 				INSPECTIONENDDATETIME ASC
							// 			LIMIT 1";
							// $stmt6   = db2_exec($conn1, $sqlDB26);
							// $rowdb26 = db2_fetch_assoc($stmt6);

							$sqlDB27 = "SELECT 
											LASTUPDATEDATETIME 
										FROM  
											PRODUCTIONDEMAND 
										WHERE 
											CODE ='$rowdb2[PRODUCTIONDEMANDCODE]' 
											AND ITEMTYPEAFICODE='KGF' 
											AND COMPANYCODE ='100' 
										ORDER BY LASTUPDATEDATETIME ASC";
							$stmt7   = db2_exec($conn1, $sqlDB27, array('cursor' => DB2_SCROLLABLE));
							$rowdb27 = db2_fetch_assoc($stmt7);

							$sqlDB28 = "SELECT 
											INSPECTIONSTARTDATETIME
										FROM  
											statusmesin_tglmulai
										WHERE
											DEMANDCODE = '$rowdb2[PRODUCTIONDEMANDCODE]'";
							$stmt8   = mysqli_query($con_nowprd, $sqlDB28);
							$rowdb28 = mysqli_fetch_assoc($stmt8);

							$sqlDB29 = "SELECT
											PLANNEDOPERATIONCODE,
											PROGRESSSTATUS,
											LONGDESCRIPTION
										FROM
											PRODUCTIONDEMANDSTEP
										WHERE
											PRODUCTIONDEMANDCODE = '$rowdb2[PRODUCTIONDEMANDCODE]'
											AND PROGRESSSTATUS = '2'
											AND NOT (PLANNEDOPERATIONCODE = 'KNT1' OR PLANNEDOPERATIONCODE = 'INS1')
											AND (PLANNEDOPERATIONCODE = 'AMC' OR PLANNEDOPERATIONCODE = 'TST' OR PLANNEDOPERATIONCODE = 'PBS' OR PLANNEDOPERATIONCODE = 'PBG'
											AND PLANNEDOPERATIONCODE = 'TPB' OR PLANNEDOPERATIONCODE = 'TTQ' OR PLANNEDOPERATIONCODE = 'HOLD')
											AND ITEMTYPEAFICOMPANYCODE = '100'
										ORDER BY
											STEPNUMBER DESC";
							$stmt9   = db2_exec($conn1, $sqlDB29, array('cursor' => DB2_SCROLLABLE));
							$rowdb29 = db2_fetch_assoc($stmt9);

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

							// $awalSJ  = strtotime($rowdb2['TGLS']);
							// $akhirSJ = strtotime($rowdb28['INSPECTIONENDDATETIME']);
							// $diffSJ  = ($akhirSJ - $awalSJ);
							// $tjamSJ  = round($diffSJ / (60 * 60), 2);
							// $hariSJ  = round($tjamSJ / 24, 2);

							$awalPC  = strtotime($rowdb2['TGLS']);
							$akhirPC = strtotime($rowdb27['LASTUPDATEDATETIME']);
							$diffPC  = ($akhirPC - $awalPC);
							$tjamPC  = round($diffPC / (60 * 60), 2);
							$hariPC = round($tjamPC / 24, 2);

							if ($rowdb2['RMPREQDATE'] != "0000-00-00") {
								$Delay = $hariDY;
							} else {
								$Delay = "";
							}
							$id_status	= $rowdb2['IDS'];

							if (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and $rowdb25['JML'] > "0") {
								$stts = "<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Perbaikan Mesin</small>";
								$totHari = abs($hariPR);
								$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "AMC") {
									$stts = "<small class='badge bg-yellow'><i class='far fa-clock text-white blink_me'></i> Antri Mesin</small>";
									$totHari = abs($hariPC);
									$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "TST") {
									$stts = "<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Setting</small>";
									$totHari = abs($hariPC);
									$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "PBS") {
									$stts = "<small class='badge bg-pink'><i class='far fa-clock text-white blink_me'></i> Habis Benang</small>";
									$totHari = abs($hariPC);
									$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "PBG") {
									$stts = "<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> Tunggu Benang Gudang</small>";
									$totHari = abs($hariPC);
									$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "TPB") {
									$stts = "<small class='badge bg-purple'><i class='far fa-clock text-black blink_me'></i> Tunggu Pasang Benang</small>";
									$totHari = abs($hariPC);
									$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "TTQ") {
									$stts = "<small class='badge bg-gray'><i class='far fa-clock text-black blink_me'></i> Tunggu Tes Quality</small>";
									$totHari = abs($hariPC);
									$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and trim($rowdb29['PLANNEDOPERATIONCODE']) == "HOLD") {
									$stts = "<small class='badge bg-black'><i class='far fa-clock text-black blink_me'></i> Hold</small>";
									$totHari = abs($hariPC);
									$StsJ = "tidak";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and ($rowdb2['IDS'] == "0 ,0" or $rowdb2['IDS'] == "0 ,0 ,0" or $rowdb2['IDS'] == "0 ,0 ,0 ,0")) {
									$stts = "<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
									$totHari = abs($hariPC);
									$StsJ = "tidak";
							} else if (($rowdb2['PROGRESSSTATUS'] == "6" or $rowdb2['STSOPR'] == "1") and ($rowdb2['IDS'] == "3 ,3" or $rowdb2['IDS'] == "3 ,3 ,3")) {
									$stts = "<small class='badge bg-pink'><i class='far fa-clock blink_me'></i> Sedang Jalan Oper PO</small>";
									// $totHari = abs($hariSJ);
									$totHari = abs($rowdb2['HariSJ']);
									$StsJ = "ya";
							} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "0" or $rowdb2['STSOPR'] == "2") and ($rowdb2['IDS'] == "3 ,3" or $rowdb2['IDS'] == "3 ,3 ,3")) {
									$stts = "<small class='badge badge-danger'><i class='far fa-clock text-white blink_me'></i> Closed</small>";
									$totHari = abs($hariPC);
									$StsJ = "tidak";
							} else if (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['STSOPR'] == "1") and ($rowdb2['IDS'] == "2 ,0" or str_contains($id_status, '2'))) {
									$stts = "<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
									// $totHari = abs($hariSJ);
									$totHari = abs($rowdb2['HariSJ']);
									$StsJ = "ya";
							} else {
									$stts = "Tidak Ada PO";
									$StsJ = "tidak";
							}
							/*
								$sqlBS=mysqli_query($con," SELECT sum((berat_awal-berat)) as kg_bs,a.demandno
									FROM tbl_inspeksi_now b
									INNER JOIN  tbl_inspeksi_detail_now a ON b.id=a.id_inspeksi
									WHERE
									IF (
										(berat_awal>berat),
										'BS',''
									)='BS' and a.demandno='$rowdb2[PRODUCTIONDEMANDCODE]'
									group by a.demandno ");
									$rBS=mysqli_fetch_array($sqlBS);	
									if($rBS['kg_bs']>0 and $rowdb2['JQTY_J2']>0){
										$perBS= round($rBS['kg_bs']/round($rowdb2['JQTY_J2'],2)*100,2);
									}else{
										$perBS="0";
									}
							*/
							
							$sqlKBG = mysqli_query($con, " select sum(berat) as tot from tbl_pembagian_greige_now where no_po ='$proj' ");
							$rKBG = mysqli_fetch_array($sqlKBG);

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
						<!--<td style="text-align: center"><?php echo $rBS['kg_bs']; ?></td>
							<td style="text-align: center"><?php echo $perBS; ?></td>-->
						<td style="text-align: center"><?php echo round($rowdb23['STDRAJUT'], 0); ?></td>
						<td style="text-align: center"><?php echo $rowdb2['JML_J1'] - $rowdb2['JML_J2']; ?></td>
						<td style="text-align: right"><?php echo round($kRajut, 2); ?></td>
						<td style="text-align: center"><?= $rowdb2['RMPREQDATE_CASE']. "<br>" . $rowdb2['RMPREQDATETO_CASE']; ?>
						</td>
						<td style="text-align: center"><?php echo $stts; ?></td>
						<td style="text-align: center"><?php echo $totHari; ?></td>
						<td style="text-align: center"><?php echo $Delay; ?></td>
						<td style="text-align: right"><?php if (substr($rowdb2['PROJECTCODE'], 0, 3) == "OPN" or substr($rowdb2['ORIGDLVSALORDLINESALORDERCODE'], 0, 3) == "OPN") {
															echo "<a href='#' class='show_detail_allokasi' id='" . $proj ."-". $hanger ."-". $rowdb2['PRODUCTIONDEMANDCODE'] . "'>" . round($rKBG['tot']) - round($rowdb2R['JQTY'], 2) . "</a>";
														} else {
															echo (round(($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN']), 2)) - round($rowdb2['JQTY_J2'], 2);
														} ?></td>
						<td style="text-align: center"><?php if ($rowdb2['INITIALSCHEDULEDACTUALDATE'] != "") {
															echo $rowdb2['INITIALSCHEDULEDACTUALDATE'];
														} else {
															echo $rowdb2['VALUEDATE'];
														} ?></td>
						<td style="text-align: center"><?php if ($rowdb2['FINALSCHEDULEDACTUALDATE'] != "0000-00-00" and $rowdb2['PRODUCTIONDEMANDCODE'] != "") {
															echo $rowdb2['FINALSCHEDULEDACTUALDATE'].'-';
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
	</div>
</div>
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