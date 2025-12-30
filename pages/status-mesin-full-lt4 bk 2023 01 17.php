<?php
include"../koneksi.php";
ini_set("error_reporting",1);

$news=mysqli_query($con,"SELECT * FROM tbl_news_line WHERE gedung='LT 2' LIMIT 1");
$rNews=mysqli_fetch_array($news);
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
			td{
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
  50% { opacity: 0; }
}
	body{
		font-family: Calibri, "sans-serif", "Courier New";  /* "Calibri Light","serif" */
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
function NoMesin($mc)
{
include"../koneksi.php";	
$sqlDB2 =" 
SELECT *
FROM (
SELECT 
CASE  
WHEN (IDS = '2 ,0' OR IDS = '0 ,2' OR IDS = '2 ,2' OR IDS = '2 ,3' OR IDS = '2 ,0 ,0' OR IDS = '2 ,2 ,3' 
OR IDS = '2 ,2 ,0' OR IDS = '0 ,0 ,2' OR IDS = '0 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,2' OR IDS = '3 ,2 ,0 ,2' 
OR IDS = '2 ,3 ,0 ,3' OR IDS = '2 ,2 ,3 ,0 ,2' ) AND STSMC.STEPNUMBER IS NULL THEN 1
WHEN STSMC.STEPNUMBER >= 2 AND STSMC.STEPNUMBER <= 7  THEN 2
WHEN STSMC.STEPNUMBER = 1 THEN 4
ELSE 3
END AS URUT,
STSMC1.IDS,
PRODUCTIONDEMAND.CODE,PRODUCTIONDEMAND.PROGRESSSTATUS,SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE,
STSMC.STEPNUMBER,STSMC.LONGDESCRIPTION,STSMC.PLANNEDOPERATIONCODE,
CASE  
WHEN AD3.VALUESTRING = 0 OR AD3.VALUESTRING IS NULL  THEN 'Normal'
WHEN AD3.VALUESTRING = 1  THEN 'Urgent'
END AS STSDEMAND 
FROM DB2ADMIN.PRODUCTIONDEMAND 
LEFT OUTER JOIN DB2ADMIN.SCHEDULESOFSTEPSPLITS SCHEDULESOFSTEPSPLITS ON PRODUCTIONDEMAND.CODE = SCHEDULESOFSTEPSPLITS.CODE
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME ='TglRencana'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME ='RMPReqDate' 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.NAMENAME ='StatusDemand'
LEFT OUTER JOIN (

SELECT STEPNUMBER,PLANNEDOPERATIONCODE,PROGRESSSTATUS,LONGDESCRIPTION,PRODUCTIONDEMANDCODE  FROM PRODUCTIONDEMANDSTEP 
WHERE PROGRESSSTATUS ='2' AND 
NOT (PLANNEDOPERATIONCODE='KNT1' OR PLANNEDOPERATIONCODE='INS1')
ORDER BY STEPNUMBER DESC

) STSMC ON STSMC.PRODUCTIONDEMANDCODE=PRODUCTIONDEMAND.CODE
LEFT OUTER JOIN (

SELECT 
trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) as IDS	,
PRODUCTIONDEMANDCODE
FROM PRODUCTIONDEMANDSTEP 
WHERE (OPERATIONCODE='INS1' OR OPERATIONCODE='KNT1')
GROUP BY PRODUCTIONDEMANDCODE

) STSMC1 ON STSMC1.PRODUCTIONDEMANDCODE=PRODUCTIONDEMAND.CODE

WHERE PRODUCTIONDEMAND.ITEMTYPEAFICODE  ='KGF' AND 
(ADSTORAGE.VALUESTRING='$mc' OR SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE='$mc' ) AND 
(PRODUCTIONDEMAND.PROGRESSSTATUS='2' OR PRODUCTIONDEMAND.PROGRESSSTATUS='3')
ORDER BY STSMC.STEPNUMBER DESC ) STSLAYAR
ORDER BY STSLAYAR.URUT ASC
 ";
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$rowdb2 = db2_fetch_assoc($stmt);

$sqlDB25 =" SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM'
GROUP BY INSPECTIONENDDATETIME ";	
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);	

	
if(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and $rowdb25['JML']>"0" ){
			$warnaD01="btn-danger";
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="HOLD" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-black"; }else{ $warnaD01="bg-black blink_me"; }
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="PBS" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-pink"; }else{ $warnaD01="bg-pink blink_me"; }
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="PBG" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-orange"; }else{ $warnaD01="bg-orange blink_me"; }
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="AMC" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-yellow"; }else{ $warnaD01="bg-yellow blink_me"; }
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="TPB" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-purple"; }else{ $warnaD01="bg-purple blink_me"; }
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="TST" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-blue"; }else{ $warnaD01="bg-blue blink_me"; }
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="TTQ" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-gray"; }else{ $warnaD01="bg-gray blink_me"; }
		}else if(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") 
		and ($rowdb2['IDS']=="2 ,0" or $rowdb2['IDS']=="0 ,2" or $rowdb2['IDS']=="2 ,2" or $rowdb2['IDS']=="2 ,3" or 
		$rowdb2['IDS']=="2 ,2 ,3" or $rowdb2['IDS']=="2 ,0 ,0" or $rowdb2['IDS']=="2 ,2 ,0" or $rowdb2['IDS']=="0 ,0 ,2" or
		$rowdb2['IDS']=="0 ,2 ,0 ,0" or $rowdb2['IDS']=="2 ,2 ,0 ,2" or $rowdb2['IDS']=="2 ,2 ,0 ,0" or $rowdb2['IDS']=="3 ,2 ,0 ,2" or 
		$rowdb2['IDS']=="2 ,3 ,0 ,3" or $rowdb2['IDS']=="2 ,2 ,3 ,0 ,2") ) {
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-green"; }else{ $warnaD01="bg-green blink_me"; }
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and $rowdb2['LONGDESCRIPTION']=="" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-orange pro"; }else{ $warnaD01="bg-orange pro blink_me"; }
		}else{
			$warnaD01="btn-default";
		}	
	return $warnaD01;
}
function Rajut($mc)
{
include"../koneksi.php";	
$sqlDB2 =" 
SELECT *
FROM (
SELECT 
CASE  
WHEN (IDS = '2 ,0' OR IDS = '0 ,2' OR IDS = '2 ,2' OR IDS = '2 ,3' OR IDS = '2 ,0 ,0' OR IDS = '2 ,2 ,3' 
OR IDS = '2 ,2 ,0' OR IDS = '0 ,0 ,2' OR IDS = '0 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,2' OR IDS = '3 ,2 ,0 ,2' 
OR IDS = '2 ,3 ,0 ,3' OR IDS = '2 ,2 ,3 ,0 ,2' ) AND STSMC.STEPNUMBER IS NULL THEN 1
WHEN STSMC.STEPNUMBER >= 2 AND STSMC.STEPNUMBER <= 7  THEN 2
WHEN STSMC.STEPNUMBER = 1 THEN 4
ELSE 3
END AS URUT,
STSMC1.IDS,
PRODUCTIONDEMAND.PROJECTCODE,PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,PRODUCTIONDEMAND.CODE,PRODUCTIONDEMAND.SUBCODE02,PRODUCTIONDEMAND.SUBCODE03,
PRODUCTIONDEMAND.SUBCODE04,PRODUCTIONDEMAND.PROGRESSSTATUS,SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE,
STSMC.STEPNUMBER,STSMC.LONGDESCRIPTION,STSMC.PLANNEDOPERATIONCODE,STDR.STDRAJUT,
CASE  
WHEN AD3.VALUESTRING = 0 OR AD3.VALUESTRING IS NULL  THEN 'Normal'
WHEN AD3.VALUESTRING = 1  THEN 'Urgent'
END AS STSDEMAND,
AD4.VALUEDECIMAL AS QTYSALIN
FROM DB2ADMIN.PRODUCTIONDEMAND 
LEFT OUTER JOIN DB2ADMIN.SCHEDULESOFSTEPSPLITS SCHEDULESOFSTEPSPLITS ON PRODUCTIONDEMAND.CODE = SCHEDULESOFSTEPSPLITS.CODE
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME ='TglRencana'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME ='RMPReqDate' 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.NAMENAME ='StatusDemand'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD4 ON AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD4.NAMENAME ='QtySalin'
LEFT OUTER JOIN (

SELECT STEPNUMBER,PLANNEDOPERATIONCODE,PROGRESSSTATUS,LONGDESCRIPTION,PRODUCTIONDEMANDCODE  FROM PRODUCTIONDEMANDSTEP 
WHERE PROGRESSSTATUS ='2' AND 
NOT (PLANNEDOPERATIONCODE='KNT1' OR PLANNEDOPERATIONCODE='INS1')
ORDER BY STEPNUMBER DESC

) STSMC ON STSMC.PRODUCTIONDEMANDCODE=PRODUCTIONDEMAND.CODE
LEFT OUTER JOIN (

SELECT 
trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) as IDS	,
PRODUCTIONDEMANDCODE
FROM PRODUCTIONDEMANDSTEP 
WHERE (OPERATIONCODE='INS1' OR OPERATIONCODE='KNT1')
GROUP BY PRODUCTIONDEMANDCODE

) STSMC1 ON STSMC1.PRODUCTIONDEMANDCODE=PRODUCTIONDEMAND.CODE
LEFT OUTER JOIN (
SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT,PRODUCT.SUBCODE02,PRODUCT.SUBCODE03,PRODUCT.SUBCODE04
FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF'        
ORDER BY ADSTORAGE.FIELDNAME
) STDR ON STDR.SUBCODE02=PRODUCTIONDEMAND.SUBCODE02 AND
STDR.SUBCODE03=PRODUCTIONDEMAND.SUBCODE03 AND
STDR.SUBCODE04=PRODUCTIONDEMAND.SUBCODE04
WHERE PRODUCTIONDEMAND.ITEMTYPEAFICODE  ='KGF' AND 
(ADSTORAGE.VALUESTRING='$mc' OR SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE='$mc' ) AND 
(PRODUCTIONDEMAND.PROGRESSSTATUS='2' OR PRODUCTIONDEMAND.PROGRESSSTATUS='3')
ORDER BY STSMC.STEPNUMBER DESC ) STSLAYAR
ORDER BY STSLAYAR.URUT ASC
 ";
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$rowdb2 = db2_fetch_assoc($stmt);
$artNO	= trim($rowdb2['SUBCODE02'])."".trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);	
$sqlDB2M =" 
SELECT x.LONGDESCRIPTION,SHORTDESCRIPTION  FROM DB2ADMIN.USERGENERICGROUP x
WHERE x.CODE='$mc'
 ";
$stmtM   = db2_exec($conn1,$sqlDB2M, array('cursor'=>DB2_SCROLLABLE));
$rowdb2M = db2_fetch_assoc($stmtM);	

$sqlDB22 =" SELECT COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF'";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);
	
if($rowdb2['BASEPRIMARYQUANTITY']>0){
		$kRajut=round($rowdb2['BASEPRIMARYQUANTITY']-$rowdb2['QTYSALIN'],2)- round($rowdb22['JQTY'],2);
}else{
$kRajut="0";
}	
$uk  = str_replace("'","",$rowdb2M['SHORTDESCRIPTION']);
$uk1 = str_replace("'","",$uk);
$uk2 = str_replace('"','',$uk1);
$ukuran = $uk2;	
echo "<h3><u>".$mc."</u></h3>Ukuran: ".$ukuran."<br> No PO: ".$rowdb2['PROJECTCODE']."<br>  No Art: ".$artNO."<br> Kurang Rajut: ".$kRajut."Kg<br> Catatan: ".$rowdb2M['LONGDESCRIPTION'];
}
function Kurang($mc)
{
	include"../koneksi.php";
	$sqlDB2 ="
SELECT *
FROM (
SELECT 
CASE  
WHEN (IDS = '2 ,0' OR IDS = '0 ,2' OR IDS = '2 ,2' OR IDS = '2 ,3' OR IDS = '2 ,0 ,0' OR IDS = '2 ,2 ,3' 
OR IDS = '2 ,2 ,0' OR IDS = '0 ,0 ,2' OR IDS = '0 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,2' OR IDS = '3 ,2 ,0 ,2' 
OR IDS = '2 ,3 ,0 ,3' OR IDS = '2 ,2 ,3 ,0 ,2' ) AND STSMC.STEPNUMBER IS NULL THEN 1
WHEN STSMC.STEPNUMBER >= 2 AND STSMC.STEPNUMBER <= 7  THEN 2
WHEN STSMC.STEPNUMBER = 1 THEN 4
ELSE 3
END AS URUT,
STSMC1.IDS,
PRODUCTIONDEMAND.CODE,PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,PRODUCTIONDEMAND.PROGRESSSTATUS,SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE,
STSMC.STEPNUMBER,STSMC.LONGDESCRIPTION,STSMC.PLANNEDOPERATIONCODE,
CASE  
WHEN AD3.VALUESTRING = 0 OR AD3.VALUESTRING IS NULL  THEN 'Normal'
WHEN AD3.VALUESTRING = 1  THEN 'Urgent'
END AS STSDEMAND,
AD4.VALUEDECIMAL AS QTYSALIN
FROM DB2ADMIN.PRODUCTIONDEMAND 
LEFT OUTER JOIN DB2ADMIN.SCHEDULESOFSTEPSPLITS SCHEDULESOFSTEPSPLITS ON PRODUCTIONDEMAND.CODE = SCHEDULESOFSTEPSPLITS.CODE
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME ='TglRencana'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME ='RMPReqDate' 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.NAMENAME ='StatusDemand'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD4 ON AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD4.NAMENAME ='QtySalin'
LEFT OUTER JOIN (

SELECT STEPNUMBER,PLANNEDOPERATIONCODE,PROGRESSSTATUS,LONGDESCRIPTION,PRODUCTIONDEMANDCODE  FROM PRODUCTIONDEMANDSTEP 
WHERE PROGRESSSTATUS ='2' AND 
NOT (PLANNEDOPERATIONCODE='KNT1' OR PLANNEDOPERATIONCODE='INS1')
ORDER BY STEPNUMBER DESC

) STSMC ON STSMC.PRODUCTIONDEMANDCODE=PRODUCTIONDEMAND.CODE
LEFT OUTER JOIN (

SELECT 
trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) as IDS	,
PRODUCTIONDEMANDCODE
FROM PRODUCTIONDEMANDSTEP 
WHERE (OPERATIONCODE='INS1' OR OPERATIONCODE='KNT1')
GROUP BY PRODUCTIONDEMANDCODE

) STSMC1 ON STSMC1.PRODUCTIONDEMANDCODE=PRODUCTIONDEMAND.CODE

WHERE PRODUCTIONDEMAND.ITEMTYPEAFICODE  ='KGF' AND 
(ADSTORAGE.VALUESTRING='$mc' OR SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE='$mc' ) AND 
(PRODUCTIONDEMAND.PROGRESSSTATUS='2' OR PRODUCTIONDEMAND.PROGRESSSTATUS='3')
ORDER BY STSMC.STEPNUMBER DESC ) STSLAYAR
ORDER BY STSLAYAR.URUT ASC
 ";
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$rowdb2 = db2_fetch_assoc($stmt);

$sqlDB22 =" SELECT COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF'";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);
	
if($rowdb2['BASEPRIMARYQUANTITY']>0){
		$kRajut=round($rowdb2['BASEPRIMARYQUANTITY']-$rowdb2['QTYSALIN'],0)- round($rowdb22['JQTY'],0);
}else{
$kRajut="0";
}	
return $kRajut;    
}
function waktu($mc){
include"../koneksi.php";
$sqlDB2 ="
SELECT *,CURRENT_TIMESTAMP AS TGLS
FROM (
SELECT 
CASE  
WHEN (IDS = '2 ,0' OR IDS = '0 ,2' OR IDS = '2 ,2' OR IDS = '2 ,3' OR IDS = '2 ,0 ,0' OR IDS = '2 ,2 ,3' 
OR IDS = '2 ,2 ,0' OR IDS = '0 ,0 ,2' OR IDS = '0 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,2' OR IDS = '3 ,2 ,0 ,2' 
OR IDS = '2 ,3 ,0 ,3' OR IDS = '2 ,2 ,3 ,0 ,2' ) AND STSMC.STEPNUMBER IS NULL THEN 1
WHEN STSMC.STEPNUMBER >= 2 AND STSMC.STEPNUMBER <= 7  THEN 2
WHEN STSMC.STEPNUMBER = 1 THEN 4
ELSE 3
END AS URUT,
STSMC1.IDS,
PRODUCTIONDEMAND.CODE,PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,PRODUCTIONDEMAND.PROGRESSSTATUS,SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE,
STSMC.STEPNUMBER,STSMC.LONGDESCRIPTION,STSMC.PLANNEDOPERATIONCODE,STDR.STDRAJUT,
CASE  
WHEN AD3.VALUESTRING = 0 OR AD3.VALUESTRING IS NULL  THEN 'Normal'
WHEN AD3.VALUESTRING = 1  THEN 'Urgent'
END AS STSDEMAND 
FROM DB2ADMIN.PRODUCTIONDEMAND 
LEFT OUTER JOIN DB2ADMIN.SCHEDULESOFSTEPSPLITS SCHEDULESOFSTEPSPLITS ON PRODUCTIONDEMAND.CODE = SCHEDULESOFSTEPSPLITS.CODE
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME ='TglRencana'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME ='RMPReqDate' 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.NAMENAME ='StatusDemand'
LEFT OUTER JOIN (

SELECT STEPNUMBER,PLANNEDOPERATIONCODE,PROGRESSSTATUS,LONGDESCRIPTION,PRODUCTIONDEMANDCODE  FROM PRODUCTIONDEMANDSTEP 
WHERE PROGRESSSTATUS ='2' AND 
NOT (PLANNEDOPERATIONCODE='KNT1' OR PLANNEDOPERATIONCODE='INS1')
ORDER BY STEPNUMBER DESC

) STSMC ON STSMC.PRODUCTIONDEMANDCODE=PRODUCTIONDEMAND.CODE
LEFT OUTER JOIN (

SELECT 
trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) as IDS	,
PRODUCTIONDEMANDCODE
FROM PRODUCTIONDEMANDSTEP 
WHERE (OPERATIONCODE='INS1' OR OPERATIONCODE='KNT1')
GROUP BY PRODUCTIONDEMANDCODE

) STSMC1 ON STSMC1.PRODUCTIONDEMANDCODE=PRODUCTIONDEMAND.CODE

LEFT OUTER JOIN (
SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT,PRODUCT.SUBCODE02,PRODUCT.SUBCODE03,PRODUCT.SUBCODE04
FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF'        
ORDER BY ADSTORAGE.FIELDNAME
) STDR ON STDR.SUBCODE02=PRODUCTIONDEMAND.SUBCODE02 AND
STDR.SUBCODE03=PRODUCTIONDEMAND.SUBCODE03 AND
STDR.SUBCODE04=PRODUCTIONDEMAND.SUBCODE04
WHERE PRODUCTIONDEMAND.ITEMTYPEAFICODE  ='KGF' AND 
(ADSTORAGE.VALUESTRING='$mc' OR SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE='$mc' ) AND 
(PRODUCTIONDEMAND.PROGRESSSTATUS='2' OR PRODUCTIONDEMAND.PROGRESSSTATUS='3')
ORDER BY STSMC.STEPNUMBER DESC ) STSLAYAR
ORDER BY STSLAYAR.URUT ASC
 ";
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$rowdb2 = db2_fetch_assoc($stmt);
$totHari="";	
$sqlDB22 =" SELECT COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF'";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);
	
if($rowdb2['BASEPRIMARYQUANTITY']>0){
		$kRajut=round($rowdb2['BASEPRIMARYQUANTITY'],0)- round($rowdb22['JQTY'],0);
}else{
$kRajut=0;
}	
$sqlDB25 =" SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[CODE]' AND ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM'
GROUP BY INSPECTIONENDDATETIME ";	
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);		
	
$sqlDB26 ="  SELECT LASTUPDATEDATETIME  FROM DB2ADMIN.PRODUCTIONDEMANDSTEP x
WHERE PRODUCTIONDEMANDCODE ='$rowdb2[CODE]' AND PROGRESSSTATUS ='2' ";	
$stmt6   = db2_exec($conn1,$sqlDB26, array('cursor'=>DB2_SCROLLABLE));
$rowdb26 = db2_fetch_assoc($stmt6);	
	
$sqlDB27 =" SELECT LASTUPDATEDATETIME  FROM  
PRODUCTIONDEMAND WHERE CODE ='$rowdb2[CODE]' AND ITEMTYPEAFICODE='KGF' AND COMPANYCODE ='100' 
ORDER BY LASTUPDATEDATETIME ASC LIMIT 1";	
$stmt7   = db2_exec($conn1,$sqlDB27, array('cursor'=>DB2_SCROLLABLE));
$rowdb27 = db2_fetch_assoc($stmt7);	
	
		$awalPR  = strtotime($rowdb2['TGLS']);
		$akhirPR = strtotime($rowdb25['INSPECTIONENDDATETIME']);
		$diffPR  = ($akhirPR - $awalPR);
		$tjamPR  = round($diffPR/(60 * 60),2);
		$hariPR  = round($tjamPR/24,2);	
	
		$awalPC  = strtotime($rowdb2['TGLS']);
		$akhirPC = strtotime($rowdb26['LASTUPDATEDATETIME']);
		$diffPC  = ($akhirPC - $awalPC);
		$tjamPC  = round($diffPC/(60 * 60),2);
		$hariPC = round($tjamPC/24,1);
		
		$awalPC1  = strtotime($rowdb2['TGLS']);
		$akhirPC1 = strtotime($rowdb27['LASTUPDATEDATETIME']);
		$diffPC1  = ($akhirPC1 - $awalPC1);
		$tjamPC1  = round($diffPC1/(60 * 60),2);
		$hariPC1 = round($tjamPC1/24,1);

	
if(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and $rowdb25['JML']>"0" ){
			$warnaD01="btn-danger";
			$totHari=abs($hariPR);
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="HOLD" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-black";}else{ $warnaD01="bg-black blink_me"; }
			$totHari=abs($hariPC);
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="PBS" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-pink"; }else{ $warnaD01="bg-pink blink_me"; }
			$totHari=abs($hariPC);	
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="PBG" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-orange"; }else{ $warnaD01="bg-orange blink_me"; }
			$totHari=abs($hariPC);
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="AMC" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-yellow"; }else{ $warnaD01="bg-yellow blink_me"; }
			$totHari=abs($hariPC);
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="TPB" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-purple"; }else{ $warnaD01="bg-purple blink_me"; }
			$totHari=abs($hariPC);
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="TST" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-blue"; }else{ $warnaD01="bg-blue blink_me"; }
			$totHari=abs($hariPC);
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and trim($rowdb2['PLANNEDOPERATIONCODE'])=="TTQ" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-gray"; }else{ $warnaD01="bg-gray blink_me"; }
			$totHari=abs($hariPC);
		}else if(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3")
		and ($rowdb2['IDS']=="2 ,0" or $rowdb2['IDS']=="0 ,2" or $rowdb2['IDS']=="2 ,2" or $rowdb2['IDS']=="2 ,3" or
		$rowdb2['IDS']=="2 ,2 ,3" or $rowdb2['IDS']=="2 ,0 ,0" or $rowdb2['IDS']=="2 ,2 ,0" or $rowdb2['IDS']=="0 ,0 ,2" or
		$rowdb2['IDS']=="0 ,2 ,0 ,0" or $rowdb2['IDS']=="2 ,2 ,0 ,2" or $rowdb2['IDS']=="2 ,2 ,0 ,0" or $rowdb2['IDS']=="3 ,2 ,0 ,2" or $rowdb2['IDS']=="2 ,3 ,0 ,3" or $rowdb2['IDS']=="2 ,2 ,3 ,0 ,2") ) {
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-green"; }else{ $warnaD01="bg-green blink_me"; }
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3") and $rowdb2['LONGDESCRIPTION']=="" ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-orange pro"; }else{ $warnaD01="bg-orange pro blink_me"; }
			$totHari=abs($hariPC1);
		}else{
			$warnaD01="btn-default";
		}	
	if($rowdb2['STDRAJUT']>0 and ($warnaD01=="bg-green" or $warnaD01=="bg-green blink_me") ){
		$est =round($kRajut/$rowdb2['STDRAJUT'],1);
	}else{
		$est="";
	}
	if($est==""){ $hriT=$totHari;}else{ $hriT=$est;}
	echo "<font color=black>$hriT</font>";
}						
?>
<?php
/* Total Status Mesin */
?>
<div class="container-fluid">
  <div class="card">
              <div class="card-header">
                <h3 class="card-title">Status Mesin Rajut Knitting ITTI Lantai 4</h3>				 
			</div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
<table width="100%" border="0">
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO24S108"); ?>" id="DO24S108" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO24S108"); ?>">D108<br />
							    <p> <?php echo Kurang("DO24S108"); ?><br />
							      <br />
							      <?php waktu("DO24S108"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO24S109"); ?>" id="DO24S109" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO24S109"); ?>">D109<br />
							    <p> <?php echo Kurang("DO24S109"); ?><br />
							      <br />
							      <?php waktu("DO24S109"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO24S110"); ?>" id="DO24S110" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO24S110"); ?>">D110<br />
							    <p> <?php echo Kurang("DO24S110"); ?><br />
							      <br />
							      <?php waktu("DO24S110"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
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
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
						  </tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S100"); ?>" id="DO23S100" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S100"); ?>">D100<br />
							    <p> <?php echo Kurang("DO23S100"); ?><br />
							      <br />
							      <?php waktu("DO23S100"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S101"); ?>" id="DO23S101" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S101"); ?>">D101<br />
							    <p> <?php echo Kurang("DO23S101"); ?><br />
							      <br />
							      <?php waktu("DO23S101"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S102"); ?>" id="DO23S102" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S102"); ?>">D102<br />
							    <p> <?php echo Kurang("DO23S102"); ?><br />
							      <br />
							      <?php waktu("DO23S102"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S103"); ?>" id="DO23S103" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S103"); ?>">D103<br />
							    <p> <?php echo Kurang("DO23S103"); ?><br />
							      <br />
							      <?php waktu("DO23S103"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S104"); ?>" id="DO23S104" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S104"); ?>">D104<br />
							    <p> <?php echo Kurang("DO23S104"); ?><br />
							      <br />
							      <?php waktu("DO23S104"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S102"); ?>" id="DO23S102" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S102"); ?>">D105<br />
							    <p> <?php echo Kurang("DO23S102"); ?><br />
							      <br />
							      <?php waktu("DO23S102"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO24S106"); ?>" id="DO24S106" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO24S106"); ?>">D106<br />
							    <p> <?php echo Kurang("DO24S106"); ?><br />
							      <br />
							      <?php waktu("DO24S106"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO24S107"); ?>" id="DO24S107" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO24S107"); ?>">D107<br />
							    <p> <?php echo Kurang("DO24S107"); ?><br />
							      <br />
							      <?php waktu("DO24S107"); ?>
						      </p>
							    </span></a></td>
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
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S092"); ?>" id="DO23S092" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S092"); ?>">D92<br />
							    <p> <?php echo Kurang("DO23S092"); ?><br />
							      <br />
							      <?php waktu("DO23S092"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S093"); ?>" id="DO23S093" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S093"); ?>">D93<br />
							    <p> <?php echo Kurang("DO23S093"); ?><br />
							      <br />
							      <?php waktu("DO23S093"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S094"); ?>" id="DO23S094" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S094"); ?>">D94<br />
							    <p> <?php echo Kurang("DO23S094"); ?><br />
							      <br />
							      <?php waktu("DO23S094"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S095"); ?>" id="DO23S095" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S095"); ?>">D95<br />
							    <p> <?php echo Kurang("DO23S095"); ?><br />
							      <br />
							      <?php waktu("DO23S095"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S096"); ?>" id="DO23S096" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S096"); ?>">D96<br />
							    <p> <?php echo Kurang("DO23S096"); ?><br />
							      <br />
							      <?php waktu("DO23S096"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S097"); ?>" id="DO23S097" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S097"); ?>">D97<br />
							    <p> <?php echo Kurang("DO23S097"); ?><br />
							      <br />
							      <?php waktu("DO23S097"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S098"); ?>" id="DO23S098" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S098"); ?>">D98<br />
							    <p> <?php echo Kurang("DO23S098"); ?><br />
							      <br />
							      <?php waktu("DO23S098"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S099"); ?>" id="DO23S099" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("DO23S099"); ?>">D99<br />
							    <p> <?php echo Kurang("DO23S099"); ?><br />
							      <br />
							      <?php waktu("DO23S099"); ?>
						      </p>
							    </span></a></td>
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
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S084"); ?>" id="DO23S084" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S084"); ?>">D84<br />
							    <p> <?php echo Kurang("DO23S084"); ?><br />
							      <br />
							      <?php waktu("DO23S084"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S085"); ?>" id="DO23S085" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S085"); ?>">D85<br />
							    <p> <?php echo Kurang("DO23S085"); ?><br />
							      <br />
							      <?php waktu("DO23S085"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S086"); ?>" id="DO23S086" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S086"); ?>">D86<br />
							    <p> <?php echo Kurang("DO23S086"); ?><br />
							      <br />
							      <?php waktu("DO23S086"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S087"); ?>" id="DO23S087" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S087"); ?>">D87<br />
							    <p> <?php echo Kurang("DO23S087"); ?><br />
							      <br />
							      <?php waktu("DO23S087"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S088"); ?>" id="DO23S088" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S088"); ?>">D88<br />
							    <p> <?php echo Kurang("DO23S088"); ?><br />
							      <br />
							      <?php waktu("DO23S088"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S089"); ?>" id="DO23S089" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S089"); ?>">D89<br />
							    <p> <?php echo Kurang("DO23S089"); ?><br />
							      <br />
							      <?php waktu("DO23S089"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S090"); ?>" id="DO23S090" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S090"); ?>">D90<br />
							    <p> <?php echo Kurang("DO23S090"); ?><br />
							      <br />
							      <?php waktu("DO23S090"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S091"); ?>" id="DO23S091" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("DO23S091"); ?>">D91<br />
							    <p> <?php echo Kurang("DO23S091"); ?><br />
							      <br />
							      <?php waktu("DO23S091"); ?>
						      </p>
							    </span></a></td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="7%" align="center" valign="middle">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="3%">&nbsp;</td>
								<td>&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S076"); ?>" id="DO23S076" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S076"); ?>">D76<br />
							    <p> <?php echo Kurang("DO23S076"); ?><br />
							      <br />
							      <?php waktu("DO23S076"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S077"); ?>" id="DO23S077" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S077"); ?>">D77<br />
							    <p> <?php echo Kurang("DO23S077"); ?><br />
							      <br />
							      <?php waktu("DO23S077"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S078"); ?>" id="DO23S078" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S078"); ?>">D78<br />
							    <p> <?php echo Kurang("DO23S078"); ?><br />
							      <br />
							      <?php waktu("DO23S078"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S079"); ?>" id="DO23S079" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S079"); ?>">D79<br />
							    <p> <?php echo Kurang("DO23S079"); ?><br />
							      <br />
							      <?php waktu("DO23S079"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S080"); ?>" id="DO23S080" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S080"); ?>">D80<br />
							    <p> <?php echo Kurang("DO23S080"); ?><br />
							      <br />
							      <?php waktu("DO23S080"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S081"); ?>" id="DO23S081" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S081"); ?>">D81<br />
							    <p> <?php echo Kurang("DO23S081"); ?><br />
							      <br />
							      <?php waktu("DO23S081"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S082"); ?>" id="DO23S082" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S082"); ?>">D82<br />
							    <p> <?php echo Kurang("DO23S082"); ?><br />
							      <br />
							      <?php waktu("DO23S082"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S083"); ?>" id="DO23S083" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("DO23S083"); ?>">D83<br />
							    <p> <?php echo Kurang("DO23S083"); ?><br />
							      <br />
							      <?php waktu("DO23S083"); ?>
						      </p>
							    </span></a></td>
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
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H111"); ?>" id="TF24H111" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF24H111"); ?>">E111<br />
								  <p> <?php echo Kurang("TF24H111"); ?><br />
								    <br />
								    <?php waktu("TF24H111"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H110"); ?>" id="TF24H110" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF24H110"); ?>">E110<br />
								  <p> <?php echo Kurang("TF24H110"); ?><br />
								    <br />
								    <?php waktu("TF24H110"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H109"); ?>" id="TF24H109" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF24H109"); ?>">E109<br />
								  <p> <?php echo Kurang("TF24H109"); ?><br />
								    <br />
								    <?php waktu("TF24H109"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H108"); ?>" id="TF24H108" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF24H108"); ?>">E108<br />
								  <p> <?php echo Kurang("TF24H108"); ?><br />
								    <br />
								    <?php waktu("TF24H108"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H107"); ?>" id="TF24H107" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF24H107"); ?>">E107<br />
								  <p> <?php echo Kurang("TF24H107"); ?><br />
								    <br />
								    <?php waktu("TF24H107"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S068"); ?>" id="DO23S068" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S068"); ?>">D68<br />
							    <p> <?php echo Kurang("DO23S068"); ?><br />
							      <br />
							      <?php waktu("DO23S068"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S069"); ?>" id="DO23S069" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S069"); ?>">D69<br />
							    <p> <?php echo Kurang("DO23S069"); ?><br />
							      <br />
							      <?php waktu("DO23S069"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S070"); ?>" id="DO23S070" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S070"); ?>">D70<br />
							    <p> <?php echo Kurang("DO23S070"); ?><br />
							      <br />
							      <?php waktu("DO23S070"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S071"); ?>" id="DO23S071" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S071"); ?>">D71<br />
							    <p> <?php echo Kurang("DO23S071"); ?><br />
							      <br />
							      <?php waktu("DO23S071"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S072"); ?>" id="DO23S072" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("DO23S072"); ?>">D72<br />
							    <p> <?php echo Kurang("DO23S072"); ?><br />
							      <br />
							      <?php waktu("DO23S072"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S073"); ?>" id="DO23S073" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S073"); ?>">D73<br />
							    <p> <?php echo Kurang("DO23S073"); ?><br />
							      <br />
							      <?php waktu("DO23S073"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S074"); ?>" id="DO23S074" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO23S074"); ?>">D74<br />
							    <p> <?php echo Kurang("DO23S074"); ?><br />
							      <br />
							      <?php waktu("DO23S074"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("DO23S075"); ?>" id="DO23S075" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("DO23S075"); ?>">D75<br />
							    <p> <?php echo Kurang("DO23S075"); ?><br />
							      <br />
							      <?php waktu("DO23S075"); ?>
						      </p>
							    </span></a></td>
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
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H106"); ?>" id="TF24H106" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF24H106"); ?>">E106<br />
								  <p> <?php echo Kurang("TF24H106"); ?><br />
								    <br />
								    <?php waktu("TF24H106"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H105"); ?>" id="TF24H105" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF24H105"); ?>">E105<br />
								  <p> <?php echo Kurang("TF24H105"); ?><br />
								    <br />
								    <?php waktu("TF24H105"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H104"); ?>" id="TF24H104" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF24H104"); ?>">E104<br />
								  <p> <?php echo Kurang("TF24H104"); ?><br />
								    <br />
								    <?php waktu("TF24H104"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H103"); ?>" id="TF24H103" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF24H103"); ?>">E103<br />
								  <p> <?php echo Kurang("TF24H103"); ?><br />
								    <br />
								    <?php waktu("TF24H103"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF24H102"); ?>" id="TF24H102" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF24H102"); ?>">E102<br />
								  <p> <?php echo Kurang("TF24H102"); ?><br />
								    <br />
								    <?php waktu("TF24H102"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="3" align="right">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="2" align="right">&nbsp;</td>
								<td colspan="4" align="right">&nbsp;</td>
								<td colspan="4" align="right">&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="6" align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td colspan="3" align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="2" align="right">&nbsp;</td>
								<td colspan="4" align="right">&nbsp;</td>
								<td colspan="4" align="right">&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="6" align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td colspan="3" align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2" align="right">&nbsp;</td>
								<td colspan="3" align="right">&nbsp;</td>
								<td colspan="4" align="right">&nbsp;</td>
								<td colspan="4" align="right">&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="6" align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td colspan="3" align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
							</tr>

							<tr>
								<td colspan="26" style="padding: 2px;">
									<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
										<?php echo $rNews['news_line']; ?>
									</marquee>
								</td>
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
        <?php //echo $rNews1[news_line];?>
      </marquee> -->
									&nbsp;</td>
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
			$.toast({
				heading: 'Selamat Datang',
				text: 'Knitting Indo Taichen',
				position: 'bottom-right',
				loaderBg: '#ff6849',
				icon: 'success',
				hideAfter: 3500,
				stack: 6
			})


		});
		$(".tst1").on("click", function() {
			var msg = $('#message').val();
			var title = $('#title').val() || '';
			$.toast({
				heading: 'Info',
				text: msg,
				position: 'top-right',
				loaderBg: '#ff6849',
				icon: 'info',
				hideAfter: 3000,
				stack: 6
			});

		});

	</script>

</html>
