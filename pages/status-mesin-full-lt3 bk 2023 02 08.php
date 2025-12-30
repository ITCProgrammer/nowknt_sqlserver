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
		$rowdb2['IDS']=="0 ,2 ,0 ,0" or $rowdb2['IDS']=="2 ,2 ,0 ,2" or $rowdb2['IDS']=="2 ,2 ,0 ,0" or $rowdb2['IDS']=="3 ,2 ,0 ,2" or $rowdb2['IDS']=="2 ,3 ,0 ,3" or $rowdb2['IDS']=="2 ,2 ,3 ,0 ,2") ) {
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
ORDER BY STSLAYAR.URUT ASC ";
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
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-black"; }else{ $warnaD01="bg-black blink_me"; }
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
		$rowdb2['IDS']=="0 ,2 ,0 ,0" or $rowdb2['IDS']=="2 ,2 ,0 ,2" or $rowdb2['IDS']=="2 ,2 ,0 ,0" or $rowdb2['IDS']=="3 ,2 ,0 ,2" or 
		$rowdb2['IDS']=="2 ,3 ,0 ,3" or $rowdb2['IDS']=="2 ,2 ,3 ,0 ,2") ) {
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
                <h3 class="card-title">Status Mesin Rajut Knitting ITTI Lantai 3 &amp; Lantai 2</h3>				 
			</div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
<table width="100%" border="0">
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M036"); ?>" id="SO23M036" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M036"); ?>">H36<br />
							    <p> <?php echo Kurang("SO23M036"); ?><br />
							      <br />
							      <?php waktu("SO23M036"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M037"); ?>" id="SO23M037" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M037"); ?>">H37<br />
							    <p> <?php echo Kurang("SO23M037"); ?><br />
							      <br />
							      <?php waktu("SO23M037"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M038"); ?>" id="SO23M038" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M038"); ?>">H38<br />
							    <p> <?php echo Kurang("SO23M038"); ?><br />
							      <br />
							      <?php waktu("SO23M038"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M039"); ?>" id="SO23M039" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M039"); ?>">H39<br />
							    <p> <?php echo Kurang("SO23M039"); ?><br />
							      <br />
							      <?php waktu("SO23M039"); ?>
						      </p>
							    </span></a></td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="7%" align="center" valign="middle">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="3%">&nbsp;</td>
								<td width="0%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="7%">&nbsp;</td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H101"); ?>" id="TF23H101" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H101"); ?>">E101<br />
								  <p> <?php echo Kurang("TF23H101"); ?><br />
								    <br />
								    <?php waktu("TF23H101"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H100"); ?>" id="TF23H100" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H100"); ?>">E100<br />
								  <p> <?php echo Kurang("TF23H100"); ?><br />
								    <br />
								    <?php waktu("TF23H100"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H099"); ?>" id="TF23H099" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H099"); ?>">E99<br />
								  <p> <?php echo Kurang("TF23H099"); ?><br />
								    <br />
								    <?php waktu("TF23H099"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H098"); ?>" id="TF23H098" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H098"); ?>">E98<br />
								  <p> <?php echo Kurang("TF23H098"); ?><br />
								    <br />
								    <?php waktu("TF23H098"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H097"); ?>" id="TF23H097" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H097"); ?>">E97<br />
								  <p> <?php echo Kurang("TF23H097"); ?><br />
								    <br />
								    <?php waktu("TF23H097"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H096"); ?>" id="TF23H096" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H096"); ?>">E96<br />
								  <p> <?php echo Kurang("TF23H096"); ?><br />
								    <br />
								    <?php waktu("TF23H096"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M028"); ?>" id="SO23M028" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M028"); ?>">H28<br />
							    <p> <?php echo Kurang("SO23M028"); ?><br />
							      <br />
							      <?php waktu("SO23M028"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M029"); ?>" id="SO23M029" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M029"); ?>">H29<br />
							    <p> <?php echo Kurang("SO23M029"); ?><br />
							      <br />
							      <?php waktu("SO23M029"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M030"); ?>" id="SO23M030" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M030"); ?>">H30<br />
							    <p> <?php echo Kurang("SO23M030"); ?><br />
							      <br />
							      <?php waktu("SO23M030"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M031"); ?>" id="SO23M031" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M031"); ?>">H31<br />
							    <p> <?php echo Kurang("SO23M031"); ?><br />
							      <br />
							      <?php waktu("SO23M031"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M032"); ?>" id="SO23M032" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M032"); ?>">H32<br />
							    <p> <?php echo Kurang("SO23M032"); ?><br />
							      <br />
							      <?php waktu("SO23M032"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M033"); ?>" id="SO23M033" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M033"); ?>">H33<br />
							    <p> <?php echo Kurang("SO23M033"); ?><br />
							      <br />
							      <?php waktu("SO23M033"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M034"); ?>" id="SO23M034" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M034"); ?>">H34<br />
							    <p> <?php echo Kurang("SO23M034"); ?><br />
							      <br />
							      <?php waktu("SO23M034"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M035"); ?>" id="SO23M035" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M035"); ?>">H35<br />
							    <p> <?php echo Kurang("SO23M035"); ?><br />
							      <br />
							      <?php waktu("SO23M035"); ?>
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
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H095"); ?>" id="TF23H095" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H095"); ?>">E95<br />
								  <p> <?php echo Kurang("TF23H095"); ?><br />
								    <br />
								    <?php waktu("TF23H095"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H094"); ?>" id="TF23H094" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H094"); ?>">E94<br />
								  <p> <?php echo Kurang("TF23H094"); ?><br />
								    <br />
								    <?php waktu("TF23H094"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H093"); ?>" id="TF23H093" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H093"); ?>">E93<br />
								  <p> <?php echo Kurang("TF23H093"); ?><br />
								    <br />
								    <?php waktu("TF23H093"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H092"); ?>" id="TF23H092" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H092"); ?>">E92<br />
								  <p> <?php echo Kurang("TF23H092"); ?><br />
								    <br />
								    <?php waktu("TF23H092"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H091"); ?>" id="TF23H091" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H091"); ?>">E91<br />
								  <p> <?php echo Kurang("TF23H091"); ?><br />
								    <br />
								    <?php waktu("TF23H091"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H090"); ?>" id="TF23H090" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H090"); ?>">E90<br />
								  <p> <?php echo Kurang("TF23H090"); ?><br />
								    <br />
								    <?php waktu("TF23H090"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H089"); ?>" id="TF23H089" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H089"); ?>">E89<br />
								  <p> <?php echo Kurang("TF23H089"); ?><br />
								    <br />
								    <?php waktu("TF23H089"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M020"); ?>" id="SO23M020" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M020"); ?>">H20<br />
							    <p> <?php echo Kurang("SO23M020"); ?><br />
							      <br />
							      <?php waktu("SO23M020"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M021"); ?>" id="SO23M021" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M021"); ?>">H21<br />
							    <p> <?php echo Kurang("SO23M021"); ?><br />
							      <br />
							      <?php waktu("SO23M021"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M022"); ?>" id="SO23M022" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M022"); ?>">H22<br />
							    <p> <?php echo Kurang("SO23M022"); ?><br />
							      <br />
							      <?php waktu("SO23M022"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M023"); ?>" id="SO23M023" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M023"); ?>">H23<br />
							    <p> <?php echo Kurang("SO23M023"); ?><br />
							      <br />
							      <?php waktu("SO23M023"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M024"); ?>" id="SO23M024" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M024"); ?>">H24<br />
							    <p> <?php echo Kurang("SO23M024"); ?><br />
							      <br />
							      <?php waktu("SO23M024"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M025"); ?>" id="SO23M025" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M025"); ?>">H25<br />
							    <p> <?php echo Kurang("SO23M025"); ?><br />
							      <br />
							      <?php waktu("SO23M025"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M026"); ?>" id="SO23M026" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M026"); ?>">H26<br />
							    <p> <?php echo Kurang("SO23M026"); ?><br />
							      <br />
							      <?php waktu("SO23M026"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M027"); ?>" id="SO23M027" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M027"); ?>">H27<br />
							    <p> <?php echo Kurang("SO23M027"); ?><br />
							      <br />
							      <?php waktu("SO23M027"); ?>
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
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H088"); ?>" id="TF23H088" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H088"); ?>">E88<br />
								  <p> <?php echo Kurang("TF23H088"); ?><br />
								    <br />
								    <?php waktu("TF23H088"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H087"); ?>" id="TF23H087" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H087"); ?>">E87<br />
								  <p> <?php echo Kurang("TF23H087"); ?><br />
								    <br />
								    <?php waktu("TF23H087"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H086"); ?>" id="TF23H086" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H086"); ?>">E86<br />
								  <p> <?php echo Kurang("TF23H086"); ?><br />
								    <br />
								    <?php waktu("E86"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H085"); ?>" id="TF23H085" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H085"); ?>">E85<br />
								  <p> <?php echo Kurang("TF23H085"); ?><br />
								    <br />
								    <?php waktu("TF23H085"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H084"); ?>" id="TF23H084" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H084"); ?>">E84<br />
								  <p> <?php echo Kurang("TF23H084"); ?><br />
								    <br />
								    <?php waktu("TF23H084"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H083"); ?>" id="TF23H083" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H083"); ?>">E83<br />
								  <p> <?php echo Kurang("TF23H083"); ?><br />
								    <br />
								    <?php waktu("TF23H083"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H082"); ?>" id="TF23H082" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H082"); ?>">E82<br />
								  <p> <?php echo Kurang("TF23H082"); ?><br />
								    <br />
								    <?php waktu("TF23H082"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M012"); ?>" id="SO23M012" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M012"); ?>">H12<br />
							    <p> <?php echo Kurang("SO23M012"); ?><br />
							      <br />
							      <?php waktu("SO23M012"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M013"); ?>" id="SO23M013" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M013"); ?>">H13<br />
							    <p> <?php echo Kurang("SO23M013"); ?><br />
							      <br />
							      <?php waktu("SO23M013"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M014"); ?>" id="SO23M014" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M014"); ?>">H14<br />
							    <p> <?php echo Kurang("SO23M014"); ?><br />
							      <br />
							      <?php waktu("H14"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M015"); ?>" id="SO23M015" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M015"); ?>">H15<br />
							    <p> <?php echo Kurang("SO23M015"); ?><br />
							      <br />
							      <?php waktu("SO23M015"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M016"); ?>" id="SO23M016" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M016"); ?>">H16<br />
							    <p> <?php echo Kurang("SO23M016"); ?><br />
							      <br />
							      <?php waktu("SO23M016"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M017"); ?>" id="SO23M017" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M017"); ?>">H17<br />
							    <p> <?php echo Kurang("SO23M017"); ?><br />
							      <br />
							      <?php waktu("SO23M017"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M018"); ?>" id="SO23M018" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M018"); ?>">H18<br />
							    <p> <?php echo Kurang("SO23M018"); ?><br />
							      <br />
							      <?php waktu("SO23M018"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M019"); ?>" id="SO23M019" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M019"); ?>">H19<br />
							    <p> <?php echo Kurang("SO23M019"); ?><br />
							      <br />
							      <?php waktu("SO23M019"); ?>
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
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H081"); ?>" id="TF23H081" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H081"); ?>">E81<br />
								  <p> <?php echo Kurang("TF23H081"); ?><br />
								    <br />
								    <?php waktu("TF23H081"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H080"); ?>" id="TF23H080" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H080"); ?>">E80<br />
								  <p> <?php echo Kurang("TF23H080"); ?><br />
								    <br />
								    <?php waktu("TF23H080"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H079"); ?>" id="TF23H079" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H079"); ?>">E79<br />
								  <p> <?php echo Kurang("TF23H079"); ?><br />
								    <br />
								    <?php waktu("TF23H079"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H078"); ?>" id="TF23H078" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H078"); ?>">E78<br />
								  <p> <?php echo Kurang("TF23H078"); ?><br />
								    <br />
								    <?php waktu("TF23H078"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H077"); ?>" id="TF23H077" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H077"); ?>">E77<br />
								  <p> <?php echo Kurang("TF23H077"); ?><br />
								    <br />
								    <?php waktu("TF23H077"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H076"); ?>" id="TF23H076" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H076"); ?>">E76<br />
								  <p> <?php echo Kurang("TF23H076"); ?><br />
								    <br />
								    <?php waktu("TF23H076"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H075"); ?>" id="TF23H075" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H075"); ?>">E75<br />
								  <p> <?php echo Kurang("TF23H075"); ?><br />
								    <br />
								    <?php waktu("TF23H075"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M004"); ?>" id="SO23M004" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M004"); ?>">H04<br />
							    <p> <?php echo Kurang("SO23M004"); ?><br />
							      <br />
							      <?php waktu("SO23M004"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M005"); ?>" id="SO23M005" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M005"); ?>">H05<br />
							    <p> <?php echo Kurang("SO23M005"); ?><br />
							      <br />
							      <?php waktu("SO23M005"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M006"); ?>" id="SO23M006" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M006"); ?>">H06<br />
							    <p> <?php echo Kurang("SO23M006"); ?><br />
							      <br />
							      <?php waktu("SO23M006"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M007"); ?>" id="SO23M007" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M007"); ?>">H07<br />
							    <p> <?php echo Kurang("SO23M007"); ?><br />
							      <br />
							      <?php waktu("SO23M007"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M008"); ?>" id="SO23M008" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M008"); ?>">H08<br />
							    <p> <?php echo Kurang("SO23M008"); ?><br />
							      <br />
							      <?php waktu("SO23M008"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M009"); ?>" id="SO23M009" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M009"); ?>">H09<br />
							    <p> <?php echo Kurang("SO23M009"); ?><br />
							      <br />
							      <?php waktu("SO23M009"); ?>
						      </p>
							    </span></a></td>
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
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H074"); ?>" id="TF23H074" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H074"); ?>">E74<br />
								  <p> <?php echo Kurang("TF23H074"); ?><br />
								    <br />
								    <?php waktu("TF23H074"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H073"); ?>" id="TF23H073" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H073"); ?>">E73<br />
								  <p> <?php echo Kurang("TF23H073"); ?><br />
								    <br />
								    <?php waktu("TF23H073"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H072"); ?>" id="TF23H072" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H072"); ?>">E72<br />
								  <p> <?php echo Kurang("TF23H072"); ?><br />
								    <br />
								    <?php waktu("TF23H072"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H071"); ?>" id="TF23H071" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H071"); ?>">E71<br />
								  <p> <?php echo Kurang("TF23H071"); ?><br />
								    <br />
								    <?php waktu("TF23H071"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H070"); ?>" id="TF23H070" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H070"); ?>">E70<br />
								  <p> <?php echo Kurang("TF23H070"); ?><br />
								    <br />
								    <?php waktu("TF23H070"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H069"); ?>" id="TF23H069" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H069"); ?>">E69<br />
								  <p> <?php echo Kurang("TF23H069"); ?><br />
								    <br />
								    <?php waktu("TF23H069"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H068"); ?>" id="TF23H068" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H068"); ?>">E68<br />
								  <p> <?php echo Kurang("TF23H068"); ?><br />
								    <br />
								    <?php waktu("TF23H068"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23P015"); ?>" id="SO23P015" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23P015"); ?>">M15<br />
							    <p> <?php echo Kurang("SO23P015"); ?><br />
							      <br />
							      <?php waktu("SO23P015"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23P016"); ?>" id="SO23P016" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23P016"); ?>">M16<br />
							    <p> <?php echo Kurang("SO23P016"); ?><br />
							      <br />
							      <?php waktu("SO23P016"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23P055"); ?>" id="SO23P055" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23P055"); ?>">M55<br />
							    <p> <?php echo Kurang("SO23P055"); ?><br />
							      <br />
							      <?php waktu("SO23P055"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23P056"); ?>" id="SO23P056" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23P056"); ?>">M56<br />
							    <p> <?php echo Kurang("SO23P056"); ?><br />
							      <br />
							      <?php waktu("SO23P056"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M001"); ?>" id="SO23M001" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M001"); ?>">H01<br />
							    <p> <?php echo Kurang("SO23M001"); ?><br />
							      <br />
							      <?php waktu("SO23M001"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M002"); ?>" id="SO23M002" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M002"); ?>">H02<br />
							    <p> <?php echo Kurang("SO23M002"); ?><br />
							      <br />
							      <?php waktu("SO23M002"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("SO23M003"); ?>" id="SO23M003" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("SO23M003"); ?>">H03<br />
							    <p> <?php echo Kurang("SO23M003"); ?><br />
							      <br />
							      <?php waktu("SO23M003"); ?>
						      </p>
							    </span></a></td>
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
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H067"); ?>" id="TF23H067" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H067"); ?>">E67<br />
								  <p> <?php echo Kurang("TF23H067"); ?><br />
								    <br />
								    <?php waktu("TF23H067"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H066"); ?>" id="TF23H066" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H066"); ?>">E66<br />
								  <p> <?php echo Kurang("TF23H066"); ?><br />
								    <br />
								    <?php waktu("TF23H066"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H065"); ?>" id="TF23H065" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H065"); ?>">E65<br />
								  <p> <?php echo Kurang("TF23H065"); ?><br />
								    <br />
								    <?php waktu("TF23H065"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H064"); ?>" id="TF23H064" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H064"); ?>">E64<br />
								  <p> <?php echo Kurang("TF23H064"); ?><br />
								    <br />
								    <?php waktu("TF23H064"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H063"); ?>" id="TF23H063" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H063"); ?>">E63<br />
								  <p> <?php echo Kurang("TF23H063"); ?><br />
								    <br />
								    <?php waktu("TF23H063"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("TF23H062"); ?>" id="TF23H062" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("TF23H062"); ?>">E62<br />
								  <p> <?php echo Kurang("TF23H062"); ?><br />
								    <br />
								    <?php waktu("TF23H062"); ?>
							    </p>
								  </span></a></td>
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
										<?php echo $rNews['news_line'];?>
									</marquee>
								</td>
							</tr>
							<tr>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J063"); ?>" id="DO22J063" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J063"); ?>">D63<br />
							    <p> <?php echo Kurang("DO22J063"); ?><br />
							      <br />
							      <?php waktu("DO22J063"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J064"); ?>" id="DO22J064" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J064"); ?>">D64<br />
							    <p> <?php echo Kurang("DO22J064"); ?><br />
							      <br />
							      <?php waktu("DO22J064"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J065"); ?>" id="DO22J065" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J065"); ?>">D65<br />
							    <p> <?php echo Kurang("DO22J065"); ?><br />
							      <br />
							      <?php waktu("DO22J065"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J066"); ?>" id="DO22J066" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J066"); ?>">D66<br />
							    <p> <?php echo Kurang("DO22J066"); ?><br />
							      <br />
							      <?php waktu("DO22J066"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J067"); ?>" id="DO22J067" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J067"); ?>">D67<br />
							    <p> <?php echo Kurang("DO22J067"); ?><br />
							      <br />
							      <?php waktu("DO22J067"); ?>
						      </p>
							    </span></a></td>
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
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("DO22J053"); ?>" id="DO22J053" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J053"); ?>">D53<br />
							    <p> <?php echo Kurang("DO22J053"); ?><br />
							      <br />
							      <?php waktu("DO22J053"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("DO22J054"); ?>" id="DO22J054" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J054"); ?>">D54<br />
							    <p> <?php echo Kurang("DO22J054"); ?><br />
							      <br />
							      <?php waktu("DO22J054"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("DO22J055"); ?>" id="DO22J055" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J055"); ?>">D55<br />
							    <p> <?php echo Kurang("DO22J055"); ?><br />
							      <br />
							      <?php waktu("DO22J055"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("DO22J056"); ?>" id="DO22J056" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J056"); ?>">D56<br />
							    <p> <?php echo Kurang("DO22J056"); ?><br />
							      <br />
							      <?php waktu("DO22J056"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("DO22J057"); ?>" id="DO22J057" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J057"); ?>">D57<br />
							    <p> <?php echo Kurang("DO22J057"); ?><br />
							      <br />
							      <?php waktu("DO22J057"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("DO22J058"); ?>" id="DO22J058" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J058"); ?>">D58<br />
							    <p> <?php echo Kurang("DO22J058"); ?><br />
							      <br />
							      <?php waktu("DO22J058"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J059"); ?>" id="DO22J059" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J059"); ?>">D59<br />
							    <p> <?php echo Kurang("DO22J059"); ?><br />
							      <br />
							      <?php waktu("DO22J059"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J060"); ?>" id="DO22J060" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J060"); ?>">D60<br />
							    <p> <?php echo Kurang("DO22J060"); ?><br />
							      <br />
							      <?php waktu("DO22J060"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J061"); ?>" id="DO22J061" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J061"); ?>">D61<br />
							    <p> <?php echo Kurang("DO22J061"); ?><br />
							      <br />
							      <?php waktu("DO22J061"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J062"); ?>" id="DO22J062" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J062"); ?>">D62<br />
							    <p> <?php echo Kurang("DO22J062"); ?><br />
							      <br />
							      <?php waktu("DO22J062"); ?>
						      </p>
							    </span></a></td>
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
							<tr>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J043"); ?>" id="DO22J043" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J043"); ?>">D43<br />
							    <p> <?php echo Kurang("DO22J043"); ?><br />
							      <br />
							      <?php waktu("DO22J043"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J044"); ?>" id="DO22J044" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J044"); ?>">D44<br />
							    <p> <?php echo Kurang("DO22J044"); ?><br />
							      <br />
							      <?php waktu("DO22J044"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("DO22J045"); ?>" id="DO22J045" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J045"); ?>">D45<br />
							    <p> <?php echo Kurang("DO22J045"); ?><br />
							      <br />
							      <?php waktu("DO22J045"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J046"); ?>" id="DO22J046" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J046"); ?>">D46<br />
							    <p> <?php echo Kurang("DO22J046"); ?><br />
							      <br />
							      <?php waktu("DO22J046"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J047"); ?>" id="DO22J047" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J047"); ?>">D47<br />
							    <p> <?php echo Kurang("DO22J047"); ?><br />
							      <br />
							      <?php waktu("DO22J047"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J048"); ?>" id="DO22J048" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J048"); ?>">D48<br />
							    <p> <?php echo Kurang("DO22J048"); ?><br />
							      <br />
							      <?php waktu("DO22J048"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J049"); ?>" id="DO22J049" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J049"); ?>">D49<br />
							    <p> <?php echo Kurang("DO22J049"); ?><br />
							      <br />
							      <?php waktu("DO22J049"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J050"); ?>" id="DO22J050" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J050"); ?>">D50<br />
							    <p> <?php echo Kurang("DO22J050"); ?><br />
							      <br />
							      <?php waktu("DO22J050"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J051"); ?>" id="DO22J051" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J051"); ?>">D51<br />
							    <p> <?php echo Kurang("DO22J051"); ?><br />
							      <br />
							      <?php waktu("DO22J051"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J052"); ?>" id="DO22J052" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J052"); ?>">D52<br />
							    <p> <?php echo Kurang("DO22J052"); ?><br />
							      <br />
							      <?php waktu("DO22J052"); ?>
						      </p>
							    </span></a></td>
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
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J033"); ?>" id="DO22J033" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J033"); ?>">D33<br />
							    <p> <?php echo Kurang("DO22J033"); ?><br />
							      <br />
							      <?php waktu("DO22J033"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J034"); ?>" id="DO22J034" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J034"); ?>">D34<br />
							    <p> <?php echo Kurang("DO22J034"); ?><br />
							      <br />
							      <?php waktu("DO22J034"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J035"); ?>" id="DO22J035" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("DO22J035"); ?>">D35<br />
							    <p> <?php echo Kurang("DO22J035"); ?><br />
							      <br />
							      <?php waktu("DO22J035"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J036"); ?>" id="DO22J036" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("DO22J036"); ?>">D36<br />
							    <p> <?php echo Kurang("DO22J036"); ?><br />
							      <br />
							      <?php waktu("DO22J036"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J037"); ?>" id="DO22J037" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("DO22J037"); ?>">D37<br />
							    <p> <?php echo Kurang("DO22J037"); ?><br />
							      <br />
							      <?php waktu("DO22J037"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J038"); ?>" id="DO22J038" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("DO22J038"); ?>">D38<br />
							    <p> <?php echo Kurang("DO22J038"); ?><br />
							      <br />
							      <?php waktu("DO22J038"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J039"); ?>" id="DO22J039" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("DO22J039"); ?>">D39<br />
							    <p> <?php echo Kurang("DO22J039"); ?><br />
							      <br />
							      <?php waktu("DO22J039"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J040"); ?>" id="DO22J040" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("DO22J040"); ?>">D40<br />
							    <p> <?php echo Kurang("DO22J040"); ?><br />
							      <br />
							      <?php waktu("DO22J040"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J041"); ?>" id="DO22J041" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("DO22J041"); ?>">D41<br />
							    <p> <?php echo Kurang("DO22J041"); ?><br />
							      <br />
							      <?php waktu("DO22J041"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J042"); ?>" id="DO22J042" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J042"); ?>">D42<br />
							    <p> <?php echo Kurang("DO22J042"); ?><br />
							      <br />
							      <?php waktu("DO22J042"); ?>
						      </p>
							    </span></a></td>
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
							<tr>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J023"); ?>" id="DO22J023" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J023"); ?>">D23<br />
							    <p> <?php echo Kurang("DO22J023"); ?><br />
							      <br />
							      <?php waktu("DO22J023"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J024"); ?>" id="DO22J024" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J024"); ?>">D24<br />
							    <p> <?php echo Kurang("DO22J024"); ?><br />
							      <br />
							      <?php waktu("DO22J024"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J025"); ?>" id="DO22J025" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J025"); ?>">D25<br />
							    <p> <?php echo Kurang("DO22J025"); ?><br />
							      <br />
							      <?php waktu("DO22J025"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J026"); ?>" id="DO22J026" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J026"); ?>">D26<br />
							    <p> <?php echo Kurang("DO22J026"); ?><br />
							      <br />
							      <?php waktu("DO22J026"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J027"); ?>" id="DO22J027" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J027"); ?>">D27<br />
							    <p> <?php echo Kurang("DO22J027"); ?><br />
							      <br />
							      <?php waktu("DO22J027"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J028"); ?>" id="DO22J028" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J028"); ?>">D28<br />
							    <p> <?php echo Kurang("DO22J028"); ?><br />
							      <br />
							      <?php waktu("DO22J028"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J029"); ?>" id="DO22J029" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J029"); ?>">D29<br />
							    <p> <?php echo Kurang("DO22J029"); ?><br />
							      <br />
							      <?php waktu("DO22J029"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J030"); ?>" id="DO22J030" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J030"); ?>">D30<br />
							    <p> <?php echo Kurang("DO22J030"); ?><br />
							      <br />
							      <?php waktu("DO22J030"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J031"); ?>" id="DO22J031" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J031"); ?>">D31<br />
							    <p> <?php echo Kurang("DO22J031"); ?><br />
							      <br />
							      <?php waktu("DO22J031"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("DO22J032"); ?>" id="DO22J032" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("DO22J032"); ?>">D32<br />
							    <p> <?php echo Kurang("DO22J032"); ?><br />
							      <br />
							      <?php waktu("DO22J032"); ?>
						      </p>
							    </span></a></td>
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
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G033"); ?>" id="TF22G033" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF22G033"); ?>">E33<br />
							    <p> <?php echo Kurang("TF22G033"); ?><br />
							      <br />
							      <?php waktu("TF22G033"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G034"); ?>" id="TF22G034" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G034"); ?>">E34<br />
							    <p> <?php echo Kurang("TF22G034"); ?><br />
							      <br />
							      <?php waktu("TF22G034"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G035"); ?>" id="TF22G035" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G035"); ?>">E35<br />
							    <p> <?php echo Kurang("TF22G035"); ?><br />
							      <br />
							      <?php waktu("TF22G035"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G036"); ?>" id="TF22G036" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G036"); ?>">E36<br />
							    <p> <?php echo Kurang("TF22G036"); ?><br />
							      <br />
							      <?php waktu("TF22G036"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G037"); ?>" id="TF22G037" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G037"); ?>">E37<br />
							    <p> <?php echo Kurang("TF22G037"); ?><br />
							      <br />
							      <?php waktu("TF22G037"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G028"); ?>" id="TF22G028" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("TF22G028"); ?>">E28<br />
							    <p> <?php echo Kurang("TF22G028"); ?><br />
							      <br />
							      <?php waktu("TF22G028"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G029"); ?>" id="TF22G029" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G029"); ?>">E29<br />
							    <p> <?php echo Kurang("TF22G029"); ?><br />
							      <br />
							      <?php waktu("TF22G029"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G030"); ?>" id="TF22G030" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G030"); ?>">E30<br />
							    <p> <?php echo Kurang("TF22G030"); ?><br />
							      <br />
							      <?php waktu("TF22G030"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G031"); ?>" id="TF22G031" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G031"); ?>">E31<br />
							    <p> <?php echo Kurang("TF22G031"); ?><br />
							      <br />
							      <?php waktu("TF22G031"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("TF22G032"); ?>" id="TF22G032" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G032"); ?>">E32<br />
							    <p> <?php echo Kurang("TF22G032"); ?><br />
							      <br />
							      <?php waktu("TF22G032"); ?>
						      </p>
							    </span></a></td>
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
							<tr>
								<td colspan="26" style="padding: 5px;">
									<!--<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();" >
        <?php //echo $rNews1['news_line'];?>
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
