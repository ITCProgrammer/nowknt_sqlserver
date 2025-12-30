<meta http-equiv="refresh" content="900">
<?php
function NoMesin($mc)
{
include"koneksi.php";	
$sqlDB2 =" 
SELECT *
FROM (
SELECT 
CASE  
WHEN (
IDS = '2 ,0' OR
IDS = '0 ,2' OR 
IDS = '2 ,2' OR
IDS = '2 ,3' OR
IDS = '3 ,2' OR
IDS = '2 ,0 ,0' OR 
IDS = '2 ,2 ,0' OR
IDS = '2 ,2 ,3' OR 
IDS = '0 ,0 ,2' OR
IDS = '0 ,2 ,0' OR
IDS = '0 ,2 ,2' OR
IDS = '0 ,2 ,0 ,0' OR
IDS = '2 ,2 ,0 ,0' OR
IDS = '2 ,2 ,0 ,2' OR 
IDS = '3 ,2 ,0 ,2' OR 
IDS = '2 ,3 ,0 ,3' OR
IDS = '2 ,2 ,3 ,0 ,2' ) AND STSMC.STEPNUMBER IS NULL THEN 1
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
END AS STSDEMAND, 
AD8.VALUEDATE AS RMPREQDATETO,
AD4.VALUEDECIMAL AS QTYSALIN,
AD5.VALUEDECIMAL AS QTYOPIN,
AD6.VALUEDECIMAL AS QTYOPOUT,
AD7.VALUESTRING AS STSOPR
FROM DB2ADMIN.PRODUCTIONDEMAND 
LEFT OUTER JOIN DB2ADMIN.SCHEDULESOFSTEPSPLITS SCHEDULESOFSTEPSPLITS ON PRODUCTIONDEMAND.CODE = SCHEDULESOFSTEPSPLITS.CODE
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME ='TglRencana'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME ='RMPReqDate' 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.NAMENAME ='StatusDemand'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD4 ON AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD4.NAMENAME ='QtySalin'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD5 ON AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD5.NAMENAME ='QtyOperIn'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD6.NAMENAME ='QtyOperOut'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD7 ON AD7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD7.NAMENAME ='StatusOper'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD8 ON AD8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD8.NAMENAME ='RMPGreigeReqDateTo'
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
		}elseif(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3" or $rowdb2['STSOPR']=="1") 
		and ($rowdb2['IDS']=="3 ,3" or $rowdb2['IDS']=="3 ,3 ,3") ){
			if($rowdb2['STSDEMAND']=="Normal"){	$warnaD01="bg-pink"; }else{ $warnaD01="bg-pink blink_me"; }
		}else if(($rowdb2['PROGRESSSTATUS']=="2" or $rowdb2['PROGRESSSTATUS']=="3")
		and (
			$rowdb2['IDS']=="2 ,0" or
			$rowdb2['IDS']=="0 ,2" or
			$rowdb2['IDS']=="2 ,2" or
			$rowdb2['IDS']=="2 ,3" or
			$rowdb2['IDS']=="3 ,2" or
			$rowdb2['IDS']=="2 ,2 ,3" or
			$rowdb2['IDS']=="2 ,0 ,0" or
			$rowdb2['IDS']=="2 ,2 ,0" or
			$rowdb2['IDS']=="0 ,0 ,2" or
			$rowdb2['IDS']=="0 ,2 ,0" or
			$rowdb2['IDS']=="0 ,2 ,2" or
			$rowdb2['IDS']=="0 ,2 ,0 ,0" or
			$rowdb2['IDS']=="2 ,2 ,0 ,2" or
			$rowdb2['IDS']=="2 ,2 ,0 ,0" or
			$rowdb2['IDS']=="3 ,2 ,0 ,2" or
			$rowdb2['IDS']=="2 ,3 ,0 ,3" or
			$rowdb2['IDS']=="2 ,2 ,3 ,0 ,2") ) {
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
include"koneksi.php";	
/*
$sqlDB2 =" 
SELECT *
FROM (
SELECT 
CASE  
WHEN (IDS = '2 ,0' OR IDS = '0 ,2' OR IDS = '2 ,2' OR IDS = '2 ,3' OR IDS = '2 ,0 ,0' OR IDS = '2 ,2 ,0'  
OR IDS = '2 ,2 ,3' OR IDS = '0 ,0 ,2' OR IDS = '2 ,2 ,0 ,0' OR IDS = '2 ,2 ,0 ,2' OR IDS = '3 ,2 ,0 ,2' 
OR IDS = '2 ,3 ,0 ,3' OR IDS = '2 ,2 ,3 ,0 ,2' )  THEN 1
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
		$kRajut=round($rowdb2['BASEPRIMARYQUANTITY'],2)- round($rowdb22['JQTY'],2);
}else{
$kRajut="0";
}		
$uk  = str_replace("'","",$rowdb2M['SHORTDESCRIPTION']);
$uk1 = str_replace("'","",$uk);
$uk2 = str_replace('"','',$uk1);
$ukuran = $uk2;	
*/
echo "<h3><u>".$mc."</u></h3>";	
/*echo "<h3><u>".$mc."</u></h3>Ukuran: ".$ukuran."<br> No PO: ".$rowdb2['PROJECTCODE']."<br>  No Art: ".$artNO."<br> Kurang Rajut: ".$kRajut."Kg<br> Catatan: ".$rowdb2M['LONGDESCRIPTION'];*/ 
}
?>
<?php
/* Total Status Mesin */
    $sqlStatus=mysqli_query($con,"SELECT kd_dtex FROM tbl_mesin where  not isnull(kd_dtex)  ORDER BY no_mesin asc");
    $TS="0"; $TBW="0";$AM="0";$TBS="0";$TQ="0";$PM="0";$SJ="0";$TAP="0";$PTD="0";$URG="0"; $RS="0"; $TPB="0";
    while ($rM=mysqli_fetch_array($sqlStatus)) {
        $sts=NoMesin($rM['kd_dtex']);
		if ($sts=="btn-default") { 
            $TAP="1";
        } else {
            $TAP="0";
        }
		if ($sts=="bg-blue" or 
			$sts=="bg-blue blink_me") {
            $TS="1";
        } else {
            $TS="0";
        }
		if ($sts=="bg-green" or 
			$sts=="bg-green blink_me") {
            $SJ="1";
        } else {
            $SJ="0";
        }
		if ($sts=="bg-yellow" or 
			$sts=="bg-yellow blink_me") {
            $AM="1";
        } else {
            $AM="0";
        }
		if ($sts=="bg-orange" or 
			$sts=="bg-orange blink_me") {
            $TBW="1";
        } else {
            $TBW="0";
        }
		if ($sts=="bg-orange pro" or 
			$sts=="bg-orange pro blink_me") {
            $POC="1";
        } else {
            $POC="0";
        }
		if ($sts=="bg-black" or 
			$sts=="bg-black blink_me") {
            $HLD="1";
        } else {
            $HLD="0";
        }
		if ($sts=="btn-danger" or 
			$sts=="btn-danger blink_me") {
            $PM="1";
        } else {
            $PM="0";
        }
		if ($sts=="bg-gray" or 
			$sts=="bg-gray blink_me") {

            $TQ="1";
        } else {
            $TQ="0";
        }
		if ($sts=="bg-purple" or 
			$sts=="bg-purple blink_me") {
            $TPB="1";
        } else {
            $TPB="0";
        }
		if ($sts=="bg-pink" or 
			$sts=="bg-pink blink_me") {
            $HB="1";
        } else {
            $HB="0";
        }
		if ($sts=="bg-gray blink_me" or	
           $sts=="bg-black blink_me" or
           $sts=="bg-green blink_me" or
           $sts=="btn-danger blink_me" or
           $sts=="bg-yellow blink_me" or
           $sts=="bg-blue blink_me" or
		   $sts=="bg-purple blink_me" or
		   $sts=="bg-pink blink_me" or
		   $sts=="bg-orange pro blink_me" or	
           $sts=="bg-orange blink_me") {
            $URG="1";
        } else {
            $URG="0";
        }
		
		$totTAP=$totTAP+$TAP;
		$totTS=$totTS+$TS;
		$totSJ=$totSJ+$SJ;
		$totAM=$totAM+$AM;
		$totTBW=$totTBW+$TBW;
		$totPOC=$totPOC+$POC;
		$totHLD=$totHLD+$HLD;
		$totPM=$totPM+$PM;
		$totTPB=$totTPB+$TPB;
		$totTQ=$totTQ+$TQ;
		$totHB=$totHB+$HB;
		$totURG=$totURG+$URG;
	}
	$totMesin=$totTAP+$totTS+$totSJ+$totAM+$totTBW+$totPOC+$totHLD+$totPM+$totTPB+$totTQ+$totHB				  
				  
?>
<!-- Main content -->
      <div class="container-fluid">
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Status Mesin Rajut Knitting ITTI Lantai 1 dan 2</h3>
				<a href="pages/status-mesin-full.php" class="btn btn-xs bg-white float-right" data-toggle="tooltip" data-html="true" data-placement="bottom" title="FullScreen" target="_blank">fullscreen</a>  
			</div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">

				  
				  
                <table width="100%" border="0">
							<tbody>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P003"); ?>" id="SO11P003" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P003"); ?>">M03</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P002"); ?>" id="SO11P002" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P002"); ?>">M02</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P001"); ?>" id="SO11P001" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P001"); ?>">M01</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P008"); ?>" id="RI11P008" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P008"); ?>">R8&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P001"); ?>" id="RI11P001" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P001"); ?>">R1&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U013"); ?>" id="TF11U013" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U013"); ?>">E13</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U016"); ?>" id="TF11U016" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U016"); ?>">E16</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P027"); ?>" id="ST11P027" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P027"); ?>">M27</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P036"); ?>" id="ST11P036" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P036"); ?>">M36</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U003"); ?>" id="TT11U003" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TT11U003"); ?>">E03</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U038"); ?>" id="TF11U038" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U038"); ?>">E38</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U044"); ?>" id="TF11U044" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U044"); ?>">E44</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P052"); ?>" id="SO11P052" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P052"); ?>">M52</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P047"); ?>" id="SO11P047" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P047"); ?>">M47</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P042"); ?>" id="SO11P042" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P042"); ?>">M42</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P037"); ?>" id="SO11P037" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P037"); ?>">M37</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P057"); ?>" id="SO11P057" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P057"); ?>">M57</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P062"); ?>" id="SO11P062" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P062"); ?>">M62</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P077"); ?>" id="SO11P077" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P077"); ?>">M77</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P069"); ?>" id="ST11P069" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P069"); ?>">M69</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P070"); ?>" id="ST11P070" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P070"); ?>">M70</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J021"); ?>" id="DO11J021" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J021"); ?>">D21</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J016"); ?>" id="DO11J016" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J016"); ?>">D16</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J011"); ?>" id="DO11J011" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J011"); ?>">D11</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J006"); ?>" id="DO11J006" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J006"); ?>">D06</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J001"); ?>" id="DO11J001" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J001"); ?>">D01</span></a></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P005"); ?>" id="SO11P005" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P005"); ?>">M05</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P009"); ?>" id="SO11P009" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P009"); ?>">M09</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P008"); ?>" id="SO11P008" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P008"); ?>">M08</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P009"); ?>" id="RI11P009" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P009"); ?>">R9&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P002"); ?>" id="RI11P002" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P002"); ?>">R2&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U015"); ?>" id="TF11U015" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U015"); ?>">E15</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U018"); ?>" id="TF11U018" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U018"); ?>">E18</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P028"); ?>" id="ST11P028" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P028"); ?>">M28</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P035"); ?>" id="ST11P035" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P035"); ?>">M35</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U005"); ?>" id="TT11U005" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TT11U005"); ?>">E05</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U039"); ?>" id="TF11U039" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U039"); ?>">E39</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U045"); ?>" id="TF11U045" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U045"); ?>">E45</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P053"); ?>" id="SO11P053" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P053"); ?>">M53</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P048"); ?>" id="SO11P048" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P048"); ?>">M48</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P043"); ?>" id="SO11P043" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P043"); ?>">M43</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P038"); ?>" id="SO11P038" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P038"); ?>">M38</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P058"); ?>" id="SO11P058" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P058"); ?>">M58</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P063"); ?>" id="SO11P063" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P063"); ?>">M63</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P078"); ?>" id="SO11P078" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P078"); ?>">M78</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P068"); ?>" id="ST11P068" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P068"); ?>">M68</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P071"); ?>" id="ST11P071" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P071"); ?>">M71</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J022"); ?>" id="DO11J022" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J022"); ?>">D22</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J017"); ?>" id="DO11J017" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J017"); ?>">D17</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J012"); ?>" id="DO11J012" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J012"); ?>">D12</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J007"); ?>" id="DO11J007" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J007"); ?>">D07</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J002"); ?>" id="DO11J002" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J002"); ?>">D02</span></a></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P006"); ?>" id="SO11P006" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P006"); ?>">M06</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P011"); ?>" id="SO11P011" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P011"); ?>">M11</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P010"); ?>" id="SO11P010" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P010"); ?>">M10</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P010"); ?>" id="RI11P010" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P010"); ?>">R10</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P003"); ?>" id="RI11P003" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P003"); ?>">R3&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U014"); ?>" id="TF11U014" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U014"); ?>">E14</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U017"); ?>" id="TF11U017" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U017"); ?>">E17</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P029"); ?>" id="ST11P029" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P029"); ?>">M29</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P034"); ?>" id="ST11P034" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P034"); ?>">M34</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U021"); ?>" id="TF11U021" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U021"); ?>">E21</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U040"); ?>" id="TF11U040" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U040"); ?>">E40</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U046"); ?>" id="TF11U046" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U046"); ?>">E46</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P054"); ?>" id="SO11P054" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P054"); ?>">M54</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P049"); ?>" id="SO11P049" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P049"); ?>">M49</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P044"); ?>" id="SO11P044" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P044"); ?>">M44</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P039"); ?>" id="SO11P039" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P039"); ?>">M39</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P059"); ?>" id="SO11P059" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P059"); ?>">M59</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P064"); ?>" id="SO11P064" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P064"); ?>">M64</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P079"); ?>" id="SO11P079" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P079"); ?>">M79</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P072"); ?>" id="ST11P072" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P072"); ?>">M72</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P073"); ?>" id="ST11P073" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P073"); ?>">M73</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U011"); ?>" id="TT11U011" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TT11U011"); ?>">E11</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J018"); ?>" id="DO11J018" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J018"); ?>">D18</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J013"); ?>" id="DO11J013" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J013"); ?>">D13</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J008"); ?>" id="DO11J008" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J008"); ?>">D08</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J003"); ?>" id="DO11J003" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J003"); ?>">D03</span></a></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P007"); ?>" id="SO11P007" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P007"); ?>">M07</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P014"); ?>" id="SO11P014" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P014"); ?>">M14</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P012"); ?>" id="SO11P012" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P012"); ?>">M12</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P011"); ?>" id="RI11P011" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P011"); ?>">R11</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P005"); ?>" id="RI11P005" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P005"); ?>">R5&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U022"); ?>" id="TF11U022" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U022"); ?>">E22</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U019"); ?>" id="TF11U019" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U019"); ?>">E19</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P030"); ?>" id="ST11P030" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P030"); ?>">M30</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P033"); ?>" id="ST11P033" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P033"); ?>">M33</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U024"); ?>" id="TF11U024" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U024"); ?>">E24</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U041"); ?>" id="TF11U041" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U041"); ?>">E41</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U047"); ?>" id="TF11U047" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U047"); ?>">E47</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U050"); ?>" id="TF11U050" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U050"); ?>">E50</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P050"); ?>" id="SO11P050" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P050"); ?>">M50</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P045"); ?>" id="SO11P045" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P045"); ?>">M45</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P040"); ?>" id="SO11P040" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P040"); ?>">M40</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P060"); ?>" id="SO11P060" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P060"); ?>">M60</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P065"); ?>" id="SO11P065" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P065"); ?>">M65</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P080"); ?>" id="SO11P080" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P080"); ?>">M80</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P074"); ?>" id="ST11P074" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P074"); ?>">M74</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P075"); ?>" id="ST11P075" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P075"); ?>">M75</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U010"); ?>" id="TT11U010" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TT11U010"); ?>">E10</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J019"); ?>" id="DO11J019" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J019"); ?>">D19</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J014"); ?>" id="DO11J014" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J014"); ?>">D14</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J009"); ?>" id="DO11J009" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J009"); ?>">D09</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J004"); ?>" id="DO11J004" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J004"); ?>">D04</span></a></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P017"); ?>" id="ST11P017" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P017"); ?>">M17</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P020"); ?>" id="ST11P020" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P020"); ?>">M20</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P023"); ?>" id="ST11P023" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P023"); ?>">M23</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P012"); ?>" id="RI11P012" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P012"); ?>">R12</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P006"); ?>" id="RI11P006" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P006"); ?>">R6&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U023"); ?>" id="TF11U023" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U023"); ?>">E23</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U020"); ?>" id="TF11U020" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U020"); ?>">E20</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P031"); ?>" id="ST11P031" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P031"); ?>">M31</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P032"); ?>" id="ST11P032" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P032"); ?>">M32</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U025"); ?>" id="TF11U025" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U025"); ?>">E25</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U042"); ?>" id="TF11U042" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U042"); ?>">E42</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U048"); ?>" id="TF11U048" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U048"); ?>">E48</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U051"); ?>" id="TF11U051" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U051"); ?>">E51</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P051"); ?>" id="SO11P051" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P051"); ?>">M51</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P046"); ?>" id="SO11P046" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P046"); ?>">M46</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P041"); ?>" id="SO11P041" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P041"); ?>">M41</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P061"); ?>" id="SO11P061" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P061"); ?>">M61</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P066"); ?>" id="SO11P066" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P066"); ?>">M66</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P081"); ?>" id="SO11P081" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P081"); ?>">M81</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P076"); ?>" id="ST11P076" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P076"); ?>">M76</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U004"); ?>" id="TT11U004" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TT11U004"); ?>">E04</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U009"); ?>" id="TT11U009" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TT11U009"); ?>">E09</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J020"); ?>" id="DO11J020" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J020"); ?>">D20</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J015"); ?>" id="DO11J015" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J015"); ?>">D15</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J010"); ?>" id="DO11J010" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J010"); ?>">D10</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J005"); ?>" id="DO11J005" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO11J005"); ?>">D05</span></a></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P007"); ?>" id="RI11P007" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11P007"); ?>">R7&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U001"); ?>" id="TT11U001" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TT11U001"); ?>">E01</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U026"); ?>" id="TF11U026" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U026"); ?>">E26</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P026"); ?>" id="ST11P026" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P026"); ?>">M26</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P067"); ?>" id="ST11P067" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P067"); ?>">M67</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U027"); ?>" id="TF11U027" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U027"); ?>">E27</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U043"); ?>" id="TF11U043" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U043"); ?>">E43</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U049"); ?>" id="TF11U049" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U049"); ?>">E49</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P082"); ?>" id="SO11P082" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO11P082"); ?>">M82</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U002"); ?>" id="TT11U002" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TT11U002"); ?>">E02</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P018"); ?>" id="ST11P018" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P018"); ?>">M18</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P021"); ?>" id="ST11P021" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P021"); ?>">M21</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P024"); ?>" id="ST11P024" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P024"); ?>">M24</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="4">Tg. Benang Warehouse <span class="badge bg-orange">
											<?php echo $totTBW;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Tg. Setting <span class="badge bg-blue">
											<?php echo $totTS;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Sedang Jalan <span class="badge bg-green">
											<?php echo $totSJ;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Tes Quality <span class="badge bg-gray">
											<?php echo $totTQ;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P019"); ?>" id="ST11P019" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P019"); ?>">M19</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P022"); ?>" id="ST11P022" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P022"); ?>">M22</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P025"); ?>" id="ST11P025" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("ST11P025"); ?>">M25</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Antri Mesin <span class="badge badge-warning">
											<?php echo $totAM;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Tidak Ada PO <span class="btn btn-xs btn-default">
											<?php echo $totTAP;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Perbaikan Mesin <span class="badge bg-red">
											<?php echo $totPM;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Tg. Benang Supp <span class="badge bg-fuchsia">
											<?php echo $totTBS."0";?></span></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11S018"); ?>" id="RI11S018" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11S018"); ?>">R18</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11S015"); ?>" id="RI11S015" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11S015"); ?>">R15</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U059"); ?>" id="TF11U059" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U059"); ?>">E59</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U055"); ?>" id="TF11U055" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U055"); ?>">E55</span></a></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Prod. Order Create <span class="badge bg-orange">
											<?php echo $totPOC;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Urgent <span class="btn btn-xs btn-default blink_me">
											<?php echo $totURG;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Rajut Sample <span class="badge btn-sm bg-abu bulat">
											<?php echo $totRS."0";?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Tg. Pasang Benang <span class="badge bg-purple"><?php echo $totTPB;?></span></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11S017"); ?>" id="RI11S017" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11S017"); ?>">R17</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11S013"); ?>" id="RI11S013" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11S013"); ?>">R13</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U058"); ?>" id="TF11U058" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U058"); ?>">E58</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U054"); ?>" id="TF11U054" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U054"); ?>">E54</span></a></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Hold <span class="badge bg-black"><?php echo $totHLD;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Siap Jalan <span class="badge bg-teal">
											<?php echo $totKOp."0";?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Total Mesin <span class="badge badge-danger">
											<?php echo $totMesin; ?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Habis Benang <span class="badge bg-pink">
											<?php echo $totHB;?></span></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11S016"); ?>" id="RI11S016" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11S016"); ?>">R16</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U061"); ?>" id="TF11U061" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U061"); ?>">E61</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U057"); ?>" id="TF11U057" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U057"); ?>">E57</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U053"); ?>" id="TF11U053" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U053"); ?>">E53</span></a></td>
								</tr>
								<tr>
									<td colspan="17">&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Sedang Jalan Oper PO <span class="badge bg-pink">
											<?php echo $totHB;?></span></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11S014"); ?>" id="RI11S014" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("RI11S014"); ?>">R14</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U060"); ?>" id="TF11U060" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U060"); ?>">E60</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U056"); ?>" id="TF11U056" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U056"); ?>">E56</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U052"); ?>" id="TF11U052" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF11U052"); ?>">E52</span></a></td>
								</tr>
								<tr>
									<td colspan="26" style="padding: 5px;">
										<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
											<?php echo $rNews['news_line'];?>
										</marquee>
									</td>
								</tr>
								<tr>
									<td colspan="26" style="padding: 5px;">&nbsp;</td>
								</tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J063"); ?>" id="DO22J063" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J063"); ?>">D63</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J064"); ?>" id="DO22J064" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J064"); ?>">D64</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J065"); ?>" id="DO22J065" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J065"); ?>">D65</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J066"); ?>" id="DO22J066" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J066"); ?>">D66</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J067"); ?>" id="DO22J067" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J067"); ?>">D67</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J053"); ?>" id="DO22J053" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J053"); ?>">D53</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J054"); ?>" id="DO22J054" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J054"); ?>">D54</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J055"); ?>" id="DO22J055" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J055"); ?>">D55</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J056"); ?>" id="DO22J056" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J056"); ?>">D56</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J057"); ?>" id="DO22J057" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J057"); ?>">D57</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J058"); ?>" id="DO22J058" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J058"); ?>">D58</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J059"); ?>" id="DO22J059" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J059"); ?>">D59</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J060"); ?>" id="DO22J060" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J060"); ?>">D60</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J061"); ?>" id="DO22J061" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J061"); ?>">D61</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J062"); ?>" id="DO22J062" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J062"); ?>">D62</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J043"); ?>" id="DO22J043" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J043"); ?>">D43</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J044"); ?>" id="DO22J044" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J044"); ?>">D44</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J045"); ?>" id="DO22J045" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J045"); ?>">D45</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J046"); ?>" id="DO22J046" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J046"); ?>">D46</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J047"); ?>" id="DO22J047" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J047"); ?>">D47</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J048"); ?>" id="DO22J048" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J048"); ?>">D48</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J049"); ?>" id="DO22J049" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J049"); ?>">D49</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J050"); ?>" id="DO22J050" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J050"); ?>">D50</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J051"); ?>" id="DO22J051" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J051"); ?>">D51</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J052"); ?>" id="DO22J052" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J052"); ?>">D52</span></a></td>
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
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J033"); ?>" id="DO22J033" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J033"); ?>">D33</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J034"); ?>" id="DO22J034" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J034"); ?>">D34</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J035"); ?>" id="DO22J035" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J035"); ?>">D35</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J036"); ?>" id="DO22J036" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J036"); ?>">D36</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J037"); ?>" id="DO22J037" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J037"); ?>">D37</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J038"); ?>" id="DO22J038" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J038"); ?>">D38</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J039"); ?>" id="DO22J039" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J039"); ?>">D39</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J040"); ?>" id="DO22J040" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J040"); ?>">D40</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J041"); ?>" id="DO22J041" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J041"); ?>">D41</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J042"); ?>" id="DO22J042" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J042"); ?>">D42</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J023"); ?>" id="DO22J023" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J023"); ?>">D23</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J024"); ?>" id="DO22J024" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J024"); ?>">D24</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J025"); ?>" id="DO22J025" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J025"); ?>">D25</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J026"); ?>" id="DO22J026" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J026"); ?>">D26</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J027"); ?>" id="DO22J027" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J027"); ?>">D27</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J028"); ?>" id="DO22J028" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J028"); ?>">D28</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J029"); ?>" id="DO22J029" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J029"); ?>">D29</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J030"); ?>" id="DO22J030" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J030"); ?>">D30</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J031"); ?>" id="DO22J031" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J031"); ?>">D31</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J032"); ?>" id="DO22J032" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO22J032"); ?>">D32</span></a></td>
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
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G033"); ?>" id="TF22G033" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G033"); ?>">E33</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G034"); ?>" id="TF22G034" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G034"); ?>">E34</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G035"); ?>" id="TF22G035" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G035"); ?>">E35</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G036"); ?>" id="TF22G036" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G036"); ?>">E36</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G037"); ?>" id="TF22G037" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G037"); ?>">E37</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G028"); ?>" id="TF22G028" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G028"); ?>">E28</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G029"); ?>" id="TF22G029" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G029"); ?>">E29</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G030"); ?>" id="TF22G030" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G030"); ?>">E30</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G031"); ?>" id="TF22G031" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G031"); ?>">E31</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G032"); ?>" id="TF22G032" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF22G032"); ?>">E32</span></a></td>
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
						</table>
              </div>
              <!-- /.card-body -->
            </div>	
		<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Status Mesin Rajut Knitting ITTI Lantai 3</h3>
				<a href="pages/status-mesin-full-lt3.php" class="btn btn-xs bg-white float-right" data-toggle="tooltip" data-html="true" data-placement="bottom" title="FullScreen" target="_blank">fullscreen</a>  
			</div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
              <table width="100%" border="0">								
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M036"); ?>" id="SO23M036" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M036"); ?>">H36</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M037"); ?>" id="SO23M037" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M037"); ?>">H37</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M038"); ?>" id="SO23M038" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M038"); ?>">H38</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M039"); ?>" id="SO23M039" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M039"); ?>">H39</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td width="26%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="9%">&nbsp;</td>
								  <td width="3%">&nbsp;</td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H101"); ?>" id="TF23H101" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H101"); ?>">E101</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H100"); ?>" id="TF23H100" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H100"); ?>">E100</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H099"); ?>" id="TF23H099" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H099"); ?>">E99</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H098"); ?>" id="TF23H098" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H098"); ?>">E98</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H097"); ?>" id="TF23H097" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H097"); ?>">E97</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H096"); ?>" id="TF23H096" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H096"); ?>">E96</span></a></td>
							  </tr>

								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M028"); ?>" id="SO23M028" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M028"); ?>">H28</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M029"); ?>" id="SO23M029" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M029"); ?>">H29</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M030"); ?>" id="SO23M030" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M030"); ?>">H30</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M031"); ?>" id="SO23M031" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M031"); ?>">H31</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M032"); ?>" id="SO23M032" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M032"); ?>">H32</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M033"); ?>" id="SO23M033" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M033"); ?>">H33</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M034"); ?>" id="SO23M034" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M034"); ?>">H34</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M035"); ?>" id="SO23M035" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M035"); ?>">H35</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H095"); ?>" id="TF23H095" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H095"); ?>">E95</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H094"); ?>" id="TF23H094" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H094"); ?>">E94</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H093"); ?>" id="TF23H093" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H093"); ?>">E93</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H092"); ?>" id="TF23H092" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H092"); ?>">E92</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H091"); ?>" id="TF23H091" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H091"); ?>">E91</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H090"); ?>" id="TF23H090" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H090"); ?>">E90</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H089"); ?>" id="TF23H089" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H089"); ?>">E89</span></a></td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M020"); ?>" id="SO23M020" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M020"); ?>">H20</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M021"); ?>" id="SO23M021" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M021"); ?>">H21</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M022"); ?>" id="SO23M022" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M022"); ?>">H22</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M023"); ?>" id="SO23M023" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M023"); ?>">H23</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M024"); ?>" id="SO23M024" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M024"); ?>">H24</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M025"); ?>" id="SO23M025" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M025"); ?>">H25</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M026"); ?>" id="SO23M026" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M026"); ?>">H26</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M027"); ?>" id="SO23M027" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M027"); ?>">H27</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H088"); ?>" id="TF23H088" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H088"); ?>">E88</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H087"); ?>" id="TF23H087" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H087"); ?>">E87</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H086"); ?>" id="TF23H086" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H086"); ?>">E86</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H085"); ?>" id="TF23H085" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H085"); ?>">E85</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H084"); ?>" id="TF23H084" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H084"); ?>">E84</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H083"); ?>" id="TF23H083" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H083"); ?>">E83</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H082"); ?>" id="TF23H082" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H082"); ?>">E82</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M012"); ?>" id="SO23M012" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M012"); ?>">H12</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M013"); ?>" id="SO23M013" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M013"); ?>">H13</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M014"); ?>" id="SO23M014" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M014"); ?>">H14</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M015"); ?>" id="SO23M015" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M015"); ?>">H15</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M016"); ?>" id="SO23M016" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M016"); ?>">H16</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M017"); ?>" id="SO23M017" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M017"); ?>">H17</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M018"); ?>" id="SO23M018" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M018"); ?>">H18</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M019"); ?>" id="SO23M019" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M019"); ?>">H19</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H081"); ?>" id="TF23H081" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H081"); ?>">E81</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H080"); ?>" id="TF23H080" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H080"); ?>">E80</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H079"); ?>" id="TF23H079" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H079"); ?>">E79</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H078"); ?>" id="TF23H078" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H078"); ?>">E78</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H077"); ?>" id="TF23H077" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H077"); ?>">E77</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H076"); ?>" id="TF23H076" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H076"); ?>">E76</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H075"); ?>" id="TF23H075" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H075"); ?>">E75</span></a></td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M004"); ?>" id="SO23M004" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M004"); ?>">H04</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M005"); ?>" id="SO23M005" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M005"); ?>">H05</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M006"); ?>" id="SO23M006" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M006"); ?>">H06</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M007"); ?>" id="SO23M007" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M007"); ?>">H07</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M008"); ?>" id="SO23M008" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M008"); ?>">H08</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M009"); ?>" id="SO23M009" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M009"); ?>">H09</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H074"); ?>" id="TF23H074" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H074"); ?>">E74</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H073"); ?>" id="TF23H073" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H073"); ?>">E73</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H072"); ?>" id="TF23H072" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H072"); ?>">E72</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H071"); ?>" id="TF23H071" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H071"); ?>">E71</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H070"); ?>" id="TF23H070" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H070"); ?>">E70</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H069"); ?>" id="TF23H069" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H069"); ?>">E69</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H068"); ?>" id="TF23H068" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H068"); ?>">E68</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23P015"); ?>" id="SO23P015" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23P015"); ?>">M15</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23P016"); ?>" id="SO23P016" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23P016"); ?>">M16</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23P055"); ?>" id="SO23P055" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23P055"); ?>">M55</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23P056"); ?>" id="SO23P056" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23P056"); ?>">M56</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M001"); ?>" id="SO23M001" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M001"); ?>">H01</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M002"); ?>" id="SO23M002" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M002"); ?>">H02</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M003"); ?>" id="SO23M003" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("SO23M003"); ?>">H03</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H067"); ?>" id="TF23H067" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H067"); ?>">E67</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H066"); ?>" id="TF23H066" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H066"); ?>">E66</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H065"); ?>" id="TF23H065" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H065"); ?>">E65</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H064"); ?>" id="TF23H064" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H064"); ?>">E64</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H063"); ?>" id="TF23H063" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H063"); ?>">E63</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H062"); ?>" id="TF23H062" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF23H062"); ?>">E62</span></a></td>
							  </tr>
								<tr>
									<td colspan="26" style="padding: 5px;">&nbsp;</td>
								</tr>
								<tr>
								  <td colspan="26" style="padding: 5px;">&nbsp;</td>
							  </tr>
							</tbody>
						</table>  

              </div>
              <!-- /.card-body -->
            </div>  
		<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Status Mesin Rajut Knitting ITTI Lantai 4</h3>
				<a href="pages/status-mesin-full-lt4.php" class="btn btn-xs bg-white float-right" data-toggle="tooltip" data-html="true" data-placement="bottom" title="FullScreen" target="_blank">fullscreen</a>  
			</div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table width="100%" border="0">	 							
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO24S108"); ?>" id="DO24S108" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO24S108"); ?>">D108</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO24S109"); ?>" id="DO24S109" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO24S109"); ?>">D109</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO24S110"); ?>" id="DO24S110" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO24S110"); ?>">D110</span></a></td>
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
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
				  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S100"); ?>" id="DO23S100" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S100"); ?>">D100</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S101"); ?>" id="DO23S101" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S101"); ?>">D101</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S102"); ?>" id="DO23S102" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S102"); ?>">D102</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S103"); ?>" id="DO23S103" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S103"); ?>">D103</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S104"); ?>" id="DO23S104" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S104"); ?>">D104</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO24S105"); ?>" id="DO24S105" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO24S105"); ?>">D105</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO24S106"); ?>" id="DO24S106" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO24S106"); ?>">D106</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO24S107"); ?>" id="DO24S107" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO24S107"); ?>">D107</span></a></td>
								  <td width="0%">&nbsp;</td>
								  <td width="0%">&nbsp;</td>
								  <td width="32%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S092"); ?>" id="DO23S092" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S092"); ?>">D92</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S093"); ?>" id="DO23S093" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S093"); ?>">D93</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S094"); ?>" id="DO23S094" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S094"); ?>">D94</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S095"); ?>" id="DO23S095" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S095"); ?>">D95</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S096"); ?>" id="DO23S096" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S096"); ?>">D96</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S097"); ?>" id="DO23S097" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S097"); ?>">D97</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S098"); ?>" id="DO23S098" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S098"); ?>">D98</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S099"); ?>" id="DO23S099" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S099"); ?>">D99</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S084"); ?>" id="DO23S084" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S084"); ?>">D84</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S085"); ?>" id="DO23S085" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S085"); ?>">D85</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S086"); ?>" id="DO23S086" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S086"); ?>">D86</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S087"); ?>" id="DO23S087" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S087"); ?>">D87</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S088"); ?>" id="DO23S088" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S088"); ?>">D88</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S089"); ?>" id="DO23S089" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S089"); ?>">D89</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S090"); ?>" id="DO23S090" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S090"); ?>">D90</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S091"); ?>" id="DO23S091" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S091"); ?>">D91</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S076"); ?>" id="DO23S076" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S076"); ?>">D76</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S077"); ?>" id="DO23S077" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S077"); ?>">D77</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S078"); ?>" id="DO23S078" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S078"); ?>">D78</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S079"); ?>" id="DO23S079" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S079"); ?>">D79</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S080"); ?>" id="DO23S080" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S080"); ?>">D80</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S081"); ?>" id="DO23S081" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S081"); ?>">D81</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S082"); ?>" id="DO23S082" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S082"); ?>">D82</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S083"); ?>" id="DO23S083" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S083"); ?>">D83</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H111"); ?>" id="TF24H111" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H111"); ?>">E111</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H110"); ?>" id="TF24H110" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H110"); ?>">E110</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H109"); ?>" id="TF24H109" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H109"); ?>">E109</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H108"); ?>" id="TF24H108" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H108"); ?>">E108</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H107"); ?>" id="TF24H107" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H107"); ?>">E107</span></a></td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S068"); ?>" id="DO23S068" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S068"); ?>">D68</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S069"); ?>" id="DO23S069" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S069"); ?>">D69</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S070"); ?>" id="DO23S070" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S070"); ?>">D70</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S071"); ?>" id="DO23S071" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S071"); ?>">D71</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S072"); ?>" id="DO23S072" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S072"); ?>">D72</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S073"); ?>" id="DO23S073" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S073"); ?>">D73</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S074"); ?>" id="DO23S074" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S074"); ?>">D74</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S075"); ?>" id="DO23S075" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("DO23S075"); ?>">D75</span></a></td>
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
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H106"); ?>" id="TF24H106" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H106"); ?>">E106</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H105"); ?>" id="TF24H105" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H105"); ?>">E105</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H104"); ?>" id="TF24H104" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H104"); ?>">E104</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H103"); ?>" id="TF24H103" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H103"); ?>">E103</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H102"); ?>" id="TF24H102" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("TF24H102"); ?>">E102</span></a></td>
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
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
							  </tr>
								<tr>
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
									<td colspan="26" style="padding: 5px;">&nbsp;</td>
								</tr>
								<tr>
								  <td colspan="26" style="padding: 5px;">&nbsp;</td>
							  </tr>
							</tbody>
						</table>
              </div>
              <!-- /.card-body -->
            </div>  
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="DetailStatus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<script>
	$(function () {
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
<!-- Tooltips -->
	<script src="dist/js/tooltips.js"></script>
	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});

	</script>