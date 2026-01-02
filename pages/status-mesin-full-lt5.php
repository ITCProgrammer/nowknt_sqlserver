<?php
include "../koneksi.php";
ini_set("error_reporting", 1);

$news = sqlsrv_query($con, "SELECT TOP 1 * FROM dbknitt.tbl_news_line WHERE gedung='LT 2'");
$rNews = $news ? sqlsrv_fetch_array($news, SQLSRV_FETCH_ASSOC) : [];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="refresh" content="900">
	<title>Status Mesin</title>
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<!-- Tempusdominus Bbootstrap 4 -->
	<link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
	<style>
		td {
			padding: 1px 0px;
		}

		p {
			line-height: 6px;
			font-size: 12px;
		}
	</style>
	<style>
		.blink_me {
			animation: blinker 1s linear infinite;
		}

		@keyframes blinker {
			50% {
				opacity: 0;
			}
		}

		body {
			font-family: Calibri, "sans-serif", "Courier New";
			/* "Calibri Light","serif" */
			font-style: normal;
		}
	</style>

	<link rel="stylesheet" href="../dist/css/adminlte.min.css">
	<link rel="icon" type="image/png" href="../dist/img/ITTI_Logo index.ico">
	<style type="text/css">
		.teks-berjalan {
			background-color: #03165E;
			color: #F4F0F0;
			font-family: monospace;
			font-size: 24px;
			font-style: italic;
		}

		.border-dashed {
			border: 4px dashed #083255;
		}

		.bulat {
			border-radius: 50%;
			/*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);*/
		}
	</style>
</head>

<body>


	<?php
	include "../koneksiDB2.php";
	$allData = array();
	function searchData($mc)
	{
		global $allData, $conn1;
		$sqlDB2 = "SELECT
							*, CURRENT_TIMESTAMP AS TGLS
						FROM
							(SELECT 
								CASE
									WHEN (IDS = '2 ,0' OR IDS = '0 ,2' OR IDS = '2 ,2' OR IDS = '2 ,3' OR IDS = '3 ,2' OR IDS = '2 ,0 ,0' OR IDS = '2 ,2 ,0' OR IDS = '2 ,2 ,3' OR IDS = '0 ,0 ,2' OR
										IDS = '0 ,2 ,0' OR IDS = '0 ,2 ,2' OR IDS = '0 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,2' OR  IDS = '3 ,2 ,0 ,2' OR IDS = '2 ,3 ,0 ,3' OR IDS = '2 ,2 ,3 ,0 ,2' OR AD7.VALUESTRING = '1')
										AND STSMC.STEPNUMBER IS NULL THEN 1
									WHEN STSMC.STEPNUMBER >= 2 AND STSMC.STEPNUMBER <= 7 THEN 2
									WHEN STSMC.STEPNUMBER = 1 THEN 4
									ELSE 3
								END AS URUT,
								STSMC1.IDS,
								PRODUCTIONDEMAND.PROJECTCODE,
								PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
								PRODUCTIONDEMAND.CODE,
								PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,
								PRODUCTIONDEMAND.PROGRESSSTATUS,
								PRODUCTIONDEMAND.SUBCODE02,
								PRODUCTIONDEMAND.SUBCODE03,
								PRODUCTIONDEMAND.SUBCODE04,
								STSMC.STEPNUMBER,
								STSMC.LONGDESCRIPTION,
								STSMC.PLANNEDOPERATIONCODE,
								CASE  
									WHEN AD3.VALUESTRING = 0 OR AD3.VALUESTRING IS NULL THEN 'Normal'
									WHEN AD3.VALUESTRING = 1 THEN 'Urgent'
									WHEN AD3.VALUESTRING = 2  THEN 'Sample'
								END AS STSDEMAND, 
								AD8.VALUEDATE AS RMPREQDATETO,
								AD4.VALUEDECIMAL AS QTYSALIN,
								AD5.VALUEDECIMAL AS QTYOPIN,
								AD6.VALUEDECIMAL AS QTYOPOUT,
								AD7.VALUESTRING AS STSOPR,
								PM.JML,
								PM.INSPECTIONENDDATETIME
							FROM
								PRODUCTIONDEMAND
							LEFT JOIN SCHEDULESOFSTEPSPLITS SCHEDULESOFSTEPSPLITS ON PRODUCTIONDEMAND.CODE = SCHEDULESOFSTEPSPLITS.CODE
							LEFT JOIN ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME = 'MachineNo'
							LEFT JOIN ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME = 'TglRencana'
							LEFT JOIN ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME = 'RMPReqDate'
							LEFT JOIN ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.NAMENAME = 'StatusDemand'
							LEFT JOIN ADSTORAGE AD4 ON AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD4.NAMENAME = 'QtySalin'
							LEFT JOIN ADSTORAGE AD5 ON AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD5.NAMENAME = 'QtyOperIn'
							LEFT JOIN ADSTORAGE AD6 ON AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD6.NAMENAME = 'QtyOperOut'
							LEFT JOIN ADSTORAGE AD7 ON AD7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD7.NAMENAME = 'StatusOper'
							LEFT JOIN ADSTORAGE AD8 ON AD8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD8.NAMENAME = 'RMPGreigeReqDateTo'
							LEFT JOIN 
								(SELECT
									STEPNUMBER,
									PLANNEDOPERATIONCODE,
									PROGRESSSTATUS,
									LONGDESCRIPTION,
									PRODUCTIONDEMANDCODE
								FROM
									PRODUCTIONDEMANDSTEP
								WHERE
									PROGRESSSTATUS = '2'
									AND NOT (PLANNEDOPERATIONCODE = 'KNT1' OR PLANNEDOPERATIONCODE = 'INS1')
								ORDER BY
									STEPNUMBER DESC) STSMC ON
									STSMC.PRODUCTIONDEMANDCODE = PRODUCTIONDEMAND.CODE
							LEFT JOIN 
								(SELECT 
									trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) AS IDS ,
									PRODUCTIONDEMANDCODE
								FROM
									PRODUCTIONDEMANDSTEP
								WHERE
									( OPERATIONCODE = 'INS1' OR OPERATIONCODE = 'KNT1' )
								GROUP BY
									PRODUCTIONDEMANDCODE) STSMC1 ON STSMC1.PRODUCTIONDEMANDCODE = PRODUCTIONDEMAND.CODE
							LEFT JOIN 
								(SELECT
									COUNT(WEIGHTREALNET) AS JML,
									INSPECTIONENDDATETIME,
									DEMANDCODE
								FROM 
									ELEMENTSINSPECTION
								WHERE
									ELEMENTITEMTYPECODE = 'KGF'	AND QUALITYREASONCODE = 'PM'
								GROUP BY
									INSPECTIONENDDATETIME,
									DEMANDCODE) PM ON PM.DEMANDCODE = PRODUCTIONDEMAND.CODE
							WHERE
								PRODUCTIONDEMAND.ITEMTYPEAFICODE = 'KGF'
								AND ADSTORAGE.VALUESTRING='$mc'
								AND (PRODUCTIONDEMAND.PROGRESSSTATUS = '2' OR AD7.VALUESTRING = '1')
							ORDER BY
								STSMC.STEPNUMBER DESC) STSLAYAR
						ORDER BY
							STSLAYAR.URUT ASC";
		$stmt   = db2_exec($conn1, $sqlDB2, array('cursor' => DB2_SCROLLABLE));
		$allData[$mc] = db2_fetch_assoc($stmt);
		return $allData[$mc];
	}
	function getData($mc)
	{
		global $allData;
		if (!array_key_exists($mc, $allData)) {
			return searchData($mc);
		} else {
			return $allData[$mc];
		}
	}
	function NoMesin($mc)
	{
		global $conn1;
		$rowdb2 = getData($mc);
		if (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and $rowdb2['JML'] > "0") {
			$warnaD01 = "btn-danger";
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "HOLD") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-black";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-black bulat";
			} else {
				$warnaD01 = "bg-black blink_me";
			}
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "PBS") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-pink";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-pink bulat";
			} else {
				$warnaD01 = "bg-pink blink_me";
			}
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "PBG") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-orange";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-orange bulat";
			} else {
				$warnaD01 = "bg-orange blink_me";
			}
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "AMC") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-yellow";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-yellow bulat";
			} else {
				$warnaD01 = "bg-yellow blink_me";
			}
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "TPB") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-purple";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-purple bulat";
			} else {
				$warnaD01 = "bg-purple blink_me";
			}
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "TST") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-blue";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-blue bulat";
			} else {
				$warnaD01 = "bg-blue blink_me";
			}
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "TTQ") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-gray";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-gray bulat";
			} else {
				$warnaD01 = "bg-gray blink_me";
			}
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1")
			and ($rowdb2['IDS'] == "3 ,3" or $rowdb2['IDS'] == "3 ,3 ,3")
		) {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-teal";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-teal bulat";
			} else {
				$warnaD01 = "bg-teal blink_me";
			}
		} else if (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1")
			and ($rowdb2['IDS'] == "2 ,0" or
				$rowdb2['IDS'] == "0 ,2" or
				$rowdb2['IDS'] == "2 ,2" or
				$rowdb2['IDS'] == "2 ,3" or
				$rowdb2['IDS'] == "3 ,2" or
				$rowdb2['IDS'] == "2 ,2 ,3" or
				$rowdb2['IDS'] == "2 ,0 ,0" or
				$rowdb2['IDS'] == "2 ,2 ,0" or
				$rowdb2['IDS'] == "0 ,0 ,2" or
				$rowdb2['IDS'] == "0 ,2 ,0" or
				$rowdb2['IDS'] == "0 ,2 ,2" or
				$rowdb2['IDS'] == "0 ,2 ,0 ,0" or
				$rowdb2['IDS'] == "2 ,2 ,0 ,2" or
				$rowdb2['IDS'] == "2 ,2 ,0 ,0" or
				$rowdb2['IDS'] == "3 ,2 ,0 ,2" or
				$rowdb2['IDS'] == "2 ,3 ,0 ,3" or
				$rowdb2['IDS'] == "2 ,2 ,3 ,0 ,2")
		) {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-green";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-green bulat";
			} else {
				$warnaD01 = "bg-green blink_me";
			}
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and $rowdb2['LONGDESCRIPTION'] == "") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-orange pro";
			} else if ($rowdb2['STSDEMAND'] == "Sample") {
				$warnaD01 = "bg-orange pro bulat";
			} else {
				$warnaD01 = "bg-orange pro blink_me";
			}
		} else {
			$warnaD01 = "btn-default";
		}
		return $warnaD01;
	}
	function Rajut($mc)
	{
		global $conn1;
		$rowdb2 = getData($mc);
		$artNO	= trim($rowdb2['SUBCODE02']) . "" . trim($rowdb2['SUBCODE03']) . " " . trim($rowdb2['SUBCODE04']);
		$sqlDB2M = "SELECT x.LONGDESCRIPTION,SHORTDESCRIPTION  FROM DB2ADMIN.USERGENERICGROUP x WHERE x.CODE='$mc'";
		$stmtM   = db2_exec($conn1, $sqlDB2M, array('cursor' => DB2_SCROLLABLE));
		$rowdb2M = db2_fetch_assoc($stmtM);

		$sqlDB22 = "SELECT 
							COUNT(WEIGHTREALNET ) AS JML, 
							SUM(WEIGHTREALNET ) AS JQTY 
						FROM 
							ELEMENTSINSPECTION 
						WHERE 
							DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF'";
		$stmt2   = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
		$rowdb22 = db2_fetch_assoc($stmt2);

		if ($rowdb2['BASEPRIMARYQUANTITY'] > 0) {
			$kRajut = round(($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN']), 0) - round($rowdb22['JQTY'], 0);
		} else {
			$kRajut = "0";
		}
		$uk  = str_replace("'", "", $rowdb2M['SHORTDESCRIPTION']);
		$uk1 = str_replace("'", "", $uk);
		$uk2 = str_replace('"', '', $uk1);
		$ukuran = $uk2;
		if ($rowdb2['PROJECTCODE'] != "") {
			$prj = $rowdb2['PROJECTCODE'];
		} else {
			$prj = $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];
		}
		echo "<h3><u>" . $mc . "</u></h3>Ukuran: " . $ukuran . "<br> No PO: " . $prj . "<br>  No Art: " . $artNO . "<br> Kurang Rajut: " . $kRajut . "Kg<br> Catatan: " . $rowdb2M['LONGDESCRIPTION'];
	}
	function Kurang($mc)
	{
		global $conn1;
		$rowdb2 = getData($mc);
		$sqlDB22 = "SELECT 
							COUNT(WEIGHTREALNET ) AS JML, 
							SUM(WEIGHTREALNET ) AS JQTY FROM 
							ELEMENTSINSPECTION 
						WHERE 
							DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF'";
		$stmt2   = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
		$rowdb22 = db2_fetch_assoc($stmt2);

		if ($rowdb2['BASEPRIMARYQUANTITY'] > 0) {
			$kRajut = round(($rowdb2['BASEPRIMARYQUANTITY'] + $rowdb2['QTYOPOUT']) - ($rowdb2['QTYSALIN'] + $rowdb2['QTYOPIN']), 0) - round($rowdb22['JQTY'], 0);
		} else {
			$kRajut = "0";
		}
		return $kRajut;
	}
	function waktu($mc)
	{
		global $conn1;
		$rowdb2 = getData($mc);
		$totHari = "";
		$sqlDB22 = "SELECT 
							COUNT(WEIGHTREALNET ) AS JML, 
							SUM(WEIGHTREALNET ) AS JQTY
						FROM
							ELEMENTSINSPECTION 
						WHERE 
							DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF'";
		$stmt2   = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
		$rowdb22 = db2_fetch_assoc($stmt2);

		if ($rowdb2['BASEPRIMARYQUANTITY'] > 0) {
			$kRajut = round($rowdb2['BASEPRIMARYQUANTITY'], 0) - round($rowdb22['JQTY'], 0);
		} else {
			$kRajut = 0;
		}

		$sqlDB26 = "SELECT 
							LASTUPDATEDATETIME
						FROM 
							PRODUCTIONDEMANDSTEP x
						WHERE 
							PRODUCTIONDEMANDCODE ='$rowdb2[CODE]' AND PROGRESSSTATUS ='2' ";
		$stmt6   = db2_exec($conn1, $sqlDB26, array('cursor' => DB2_SCROLLABLE));
		$rowdb26 = db2_fetch_assoc($stmt6);

		$sqlDB27 = " SELECT 
							LASTUPDATEDATETIME
						FROM  
							PRODUCTIONDEMAND 
						WHERE 
							CODE ='$rowdb2[CODE]' AND ITEMTYPEAFICODE='KGF' AND COMPANYCODE ='100' 
						ORDER BY 
							LASTUPDATEDATETIME ASC 
						LIMIT 1";
		$stmt7   = db2_exec($conn1, $sqlDB27, array('cursor' => DB2_SCROLLABLE));
		$rowdb27 = db2_fetch_assoc($stmt7);

		$awalPR  = strtotime($rowdb2['TGLS']);
		$akhirPR = strtotime($rowdb2['INSPECTIONENDDATETIME']);
		$diffPR  = ($akhirPR - $awalPR);
		$tjamPR  = round($diffPR / (60 * 60), 2);
		$hariPR  = round($tjamPR / 24, 2);

		$awalPC  = strtotime($rowdb2['TGLS']);
		$akhirPC = strtotime($rowdb26['LASTUPDATEDATETIME']);
		$diffPC  = ($akhirPC - $awalPC);
		$tjamPC  = round($diffPC / (60 * 60), 2);
		$hariPC = round($tjamPC / 24, 1);

		$awalPC1  = strtotime($rowdb2['TGLS']);
		$akhirPC1 = strtotime($rowdb27['LASTUPDATEDATETIME']);
		$diffPC1  = ($akhirPC1 - $awalPC1);
		$tjamPC1  = round($diffPC1 / (60 * 60), 2);
		$hariPC1 = round($tjamPC1 / 24, 1);


		if (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and $rowdb2['JML'] > "0") {
			$warnaD01 = "btn-danger";
			$totHari = abs($hariPR);
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "HOLD") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-black";
			} else {
				$warnaD01 = "bg-black blink_me";
			}
			$totHari = abs($hariPC);
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "PBS") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-pink";
			} else {
				$warnaD01 = "bg-pink blink_me";
			}
			$totHari = abs($hariPC);
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "PBG") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-orange";
			} else {
				$warnaD01 = "bg-orange blink_me";
			}
			$totHari = abs($hariPC);
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "AMC") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-yellow";
			} else {
				$warnaD01 = "bg-yellow blink_me";
			}
			$totHari = abs($hariPC);
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "TPB") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-purple";
			} else {
				$warnaD01 = "bg-purple blink_me";
			}
			$totHari = abs($hariPC);
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "TST") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-blue";
			} else {
				$warnaD01 = "bg-blue blink_me";
			}
			$totHari = abs($hariPC);
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and trim($rowdb2['PLANNEDOPERATIONCODE']) == "TTQ") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-gray";
			} else {
				$warnaD01 = "bg-gray blink_me";
			}
			$totHari = abs($hariPC);
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and ($rowdb2['IDS'] == "3 ,3" or $rowdb2['IDS'] == "3 ,3 ,3")) {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-teal";
			} else {
				$warnaD01 = "bg-teal blink_me";
			}
		} else if (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1")
			and ($rowdb2['IDS'] == "2 ,0" or
				$rowdb2['IDS'] == "0 ,2" or
				$rowdb2['IDS'] == "2 ,2" or
				$rowdb2['IDS'] == "2 ,3" or
				$rowdb2['IDS'] == "3 ,2" or
				$rowdb2['IDS'] == "2 ,2 ,3" or
				$rowdb2['IDS'] == "2 ,0 ,0" or
				$rowdb2['IDS'] == "2 ,2 ,0" or
				$rowdb2['IDS'] == "0 ,0 ,2" or
				$rowdb2['IDS'] == "0 ,2 ,0" or
				$rowdb2['IDS'] == "0 ,2 ,2" or
				$rowdb2['IDS'] == "0 ,2 ,0 ,0" or
				$rowdb2['IDS'] == "2 ,2 ,0 ,2" or
				$rowdb2['IDS'] == "2 ,2 ,0 ,0" or
				$rowdb2['IDS'] == "3 ,2 ,0 ,2" or
				$rowdb2['IDS'] == "2 ,3 ,0 ,3" or
				$rowdb2['IDS'] == "2 ,2 ,3 ,0 ,2"
			)
		) {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-green";
			} else {
				$warnaD01 = "bg-green blink_me";
			}
		} elseif (($rowdb2['PROGRESSSTATUS'] == "2" or $rowdb2['PROGRESSSTATUS'] == "3" or $rowdb2['STSOPR'] == "1") and $rowdb2['LONGDESCRIPTION'] == "") {
			if ($rowdb2['STSDEMAND'] == "Normal") {
				$warnaD01 = "bg-orange pro";
			} else {
				$warnaD01 = "bg-orange pro blink_me";
			}
			$totHari = abs($hariPC1);
		} else {
			$warnaD01 = "btn-default";
		}
		if ($rowdb2['STDRAJUT'] > 0 and ($warnaD01 == "bg-green" or $warnaD01 == "bg-green blink_me")) {
			$est = round($kRajut / $rowdb2['STDRAJUT'], 1);
		} else {
			$est = "";
		}
		if ($est == "") {
			$hriT = $totHari;
		} else {
			$hriT = $est;
		}
		echo "<font color=black>$hriT</font>";
	}
	?>
	<?php
	/* Total Status Mesin */
	?>
	<style>
		.detail_status {
			width: 95%;
			height: 62px;
			padding: 0px;
		}

		.enter {
			height: 0.12in;
		}
	</style>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Status Mesin Rajut Knitting ITTI Lantai 5</h3>
			</div>
			<!-- /.card-header -->
			<div class="card-body table-responsive">
				<table width="100%" border="0">
					<tr>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U112"); ?>" id="TF25U112" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U112"); ?>">E112<br />
									<p> <?php echo Kurang("TF25U112"); ?><br />
										<br />
										<?php waktu("TF25U112"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U113"); ?>" id="TF25U113" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U113"); ?>">E113<br />
									<p> <?php echo Kurang("TF25U113"); ?><br />
										<br />
										<?php waktu("TF25U113"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U114"); ?>" id="TF25U114" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U114"); ?>">E114<br />
									<p> <?php echo Kurang("TF25U114"); ?><br />
										<br />
										<?php waktu("TF25U114"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U115"); ?>" id="TF25U115" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U115"); ?>">E115<br />
									<p> <?php echo Kurang("TF25U115"); ?><br />
										<br />
										<?php waktu("TF25U115"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U116"); ?>" id="TF25U116" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U116"); ?>">E116<br />
									<p> <?php echo Kurang("TF25U116"); ?><br />
										<br />
										<?php waktu("TF25U116"); ?>
									</p>
								</span></a></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr class="enter">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U117"); ?>" id="TF25U117" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U117"); ?>">E117<br />
									<p> <?php echo Kurang("TF25U117"); ?><br />
										<br />
										<?php waktu("TF25U117"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U118"); ?>" id="TF25U118" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U118"); ?>">E118<br />
									<p> <?php echo Kurang("TF25U118"); ?><br />
										<br />
										<?php waktu("TF25U118"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U119"); ?>" id="TF25U119" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U119"); ?>">E119<br />
									<p> <?php echo Kurang("TF25U119"); ?><br />
										<br />
										<?php waktu("TF25U119"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U120"); ?>" id="TF25U120" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U120"); ?>">E120<br />
									<p> <?php echo Kurang("TF25U120"); ?><br />
										<br />
										<?php waktu("TF25U120"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U121"); ?>" id="TF25U121" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U121"); ?>">E121<br />
									<p> <?php echo Kurang("TF25U121"); ?><br />
										<br />
										<?php waktu("TF25U121"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U122"); ?>" id="TF25U122" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U122"); ?>">E122<br />
									<p> <?php echo Kurang("TF25U122"); ?><br />
										<br />
										<?php waktu("TF25U122"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U123"); ?>" id="TF25U123" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U123"); ?>">E123<br />
									<p> <?php echo Kurang("TF25U123"); ?><br />
										<br />
										<?php waktu("TF25U123"); ?>
									</p>
								</span></a></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
					<tr class="enter">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U124"); ?>" id="TF25U124" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U124"); ?>">E124<br />
									<p> <?php echo Kurang("TF25U124"); ?><br />
										<br />
										<?php waktu("TF25U124"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U125"); ?>" id="TF25U125" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U125"); ?>">E125<br />
									<p> <?php echo Kurang("TF25U125"); ?><br />
										<br />
										<?php waktu("TF25U125"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U126"); ?>" id="TF25U126" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U126"); ?>">E126<br />
									<p> <?php echo Kurang("TF25U126"); ?><br />
										<br />
										<?php waktu("TF25U126"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U127"); ?>" id="TF25U127" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U127"); ?>">E127<br />
									<p> <?php echo Kurang("TF25U127"); ?><br />
										<br />
										<?php waktu("TF25U127"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U128"); ?>" id="TF25U128" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U128"); ?>">E128<br />
									<p> <?php echo Kurang("TF25U128"); ?><br />
										<br />
										<?php waktu("TF25U128"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U129"); ?>" id="TF25U129" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U129"); ?>">E129<br />
									<p> <?php echo Kurang("TF25U129"); ?><br />
										<br />
										<?php waktu("TF25U129"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U130"); ?>" id="TF25U130" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U130"); ?>">E130<br />
									<p> <?php echo Kurang("TF25U130"); ?><br />
										<br />
										<?php waktu("TF25U130"); ?>
									</p>
								</span></a></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
					<tr class="enter">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U131"); ?>" id="TF25U131" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U131"); ?>">E131<br />
									<p> <?php echo Kurang("TF25U131"); ?><br />
										<br />
										<?php waktu("TF25U131"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U132"); ?>" id="TF25U132" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U132"); ?>">E132<br />
									<p> <?php echo Kurang("TF25U132"); ?><br />
										<br />
										<?php waktu("TF25U132"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U133"); ?>" id="TF25U133" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U133"); ?>">E133<br />
									<p> <?php echo Kurang("TF25U133"); ?><br />
										<br />
										<?php waktu("TF25U133"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U134"); ?>" id="TF25U134" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U134"); ?>">E134<br />
									<p> <?php echo Kurang("TF25U134"); ?><br />
										<br />
										<?php waktu("TF25U134"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U135"); ?>" id="TF25U135" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U135"); ?>">E135<br />
									<p> <?php echo Kurang("TF25U135"); ?><br />
										<br />
										<?php waktu("TF25U135"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U136"); ?>" id="TF25U136" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U136"); ?>">E136<br />
									<p> <?php echo Kurang("TF25U136"); ?><br />
										<br />
										<?php waktu("TF25U136"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U137"); ?>" id="TF25U137" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U137"); ?>">E137<br />
									<p> <?php echo Kurang("TF25U137"); ?><br />
										<br />
										<?php waktu("TF25U137"); ?>
									</p>
								</span></a></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
					<tr class="enter">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U138"); ?>" id="TF25U138" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U138"); ?>">E138<br />
									<p> <?php echo Kurang("TF25U138"); ?><br />
										<br />
										<?php waktu("TF25U138"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U139"); ?>" id="TF25U139" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U139"); ?>">E139<br />
									<p> <?php echo Kurang("TF25U139"); ?><br />
										<br />
										<?php waktu("TF25U139"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U140"); ?>" id="TF25U140" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U140"); ?>">E140<br />
									<p> <?php echo Kurang("TF25U140"); ?><br />
										<br />
										<?php waktu("TF25U140"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U141"); ?>" id="TF25U141" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U141"); ?>">E141<br />
									<p> <?php echo Kurang("TF25U141"); ?><br />
										<br />
										<?php waktu("TF25U141"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U142"); ?>" id="TF25U142" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U142"); ?>">E142<br />
									<p> <?php echo Kurang("TF25U142"); ?><br />
										<br />
										<?php waktu("TF25U142"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U143"); ?>" id="TF25U143" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U143"); ?>">E143<br />
									<p> <?php echo Kurang("TF25U143"); ?><br />
										<br />
										<?php waktu("TF25U143"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U144"); ?>" id="TF25U144" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U144"); ?>">E144<br />
									<p> <?php echo Kurang("TF25U144"); ?><br />
										<br />
										<?php waktu("TF25U144"); ?>
									</p>
								</span></a></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
					<tr class="enter">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U145"); ?>" id="TF25U145" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U145"); ?>">E145<br />
									<p> <?php echo Kurang("TF25U145"); ?><br />
										<br />
										<?php waktu("TF25U145"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U146"); ?>" id="TF25U146" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U146"); ?>">E146<br />
									<p> <?php echo Kurang("TF25U146"); ?><br />
										<br />
										<?php waktu("TF25U146"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U147"); ?>" id="TF25U147" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U147"); ?>">E147<br />
									<p> <?php echo Kurang("TF25U147"); ?><br />
										<br />
										<?php waktu("TF25U147"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U148"); ?>" id="TF25U148" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U148"); ?>">E148<br />
									<p> <?php echo Kurang("TF25U148"); ?><br />
										<br />
										<?php waktu("TF25U148"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U149"); ?>" id="TF25U149" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U149"); ?>">E149<br />
									<p> <?php echo Kurang("TF25U149"); ?><br />
										<br />
										<?php waktu("TF25U149"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U150"); ?>" id="TF25U150" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U150"); ?>">E150<br />
									<p> <?php echo Kurang("TF25U150"); ?><br />
										<br />
										<?php waktu("TF25U150"); ?>
									</p>
								</span></a></td>
						<td align="center" width="100px" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF25U151"); ?>" id="TF25U151" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF25U151"); ?>">E151<br />
									<p> <?php echo Kurang("TF25U151"); ?><br />
										<br />
										<?php waktu("TF25U151"); ?>
									</p>
								</span></a></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
					<tr class="enter">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr style="height: 0.1in;">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="26" style="padding: 5px;">
							<!--<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();" >
        <?php //echo $rNews1[news_line];
		?>
      </marquee> -->
							&nbsp;
						</td>
					</tr>

				</table>

			</div>
		</div>
	</div>
	<div id="DetailStatus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	</div>

</body>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>
<!-- Javascript untuk popup modal Edit-->
<script type="text/javascript">
	$(document).on('click', '.detail_status', function(e) {
		var m = $(this).attr("id");
		$.ajax({
			url: "./detail_status.php",
			type: "GET",
			data: {
				id: m,
			},
			success: function(ajaxData) {
				$("#DetailStatus").html(ajaxData);
				$("#DetailStatus").modal('show', {
					backdrop: 'true'
				});
			}
		});
	});

	//            tabel lookup KO status terima
	$(function() {
		$("#lookup").dataTable();
	});
</script>
<script>
	$(document).ready(function() {
		"use strict";
		// toat popup js
		// $.toast({
		// 	heading: 'Selamat Datang',
		// 	text: 'Knitting Indo Taichen',
		// 	position: 'bottom-right',
		// 	loaderBg: '#ff6849',
		// 	icon: 'success',
		// 	hideAfter: 3500,
		// 	stack: 6
		// })


	});
	$(".tst1").on("click", function() {
		var msg = $('#message').val();
		var title = $('#title').val() || '';
		// $.toast({
		// 	heading: 'Info',
		// 	text: msg,
		// 	position: 'top-right',
		// 	loaderBg: '#ff6849',
		// 	icon: 'info',
		// 	hideAfter: 3000,
		// 	stack: 6
		// });

	});
</script>

</html>