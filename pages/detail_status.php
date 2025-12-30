<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];	
?>
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Data Mesin</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"><i>
			No Mesin: <b><?php echo $modal_id;?></b></b>				
			</i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">Project</div></th>
								<th><div align="center">Demand</div></th>
								<th><div align="center">Qty Order </div></th>
								<th><div align="center">Konsumen</div></th>
								<th><div align="center">No Art</div></th>
								<th><div align="center">ProgressStatus</div></th>															
							</tr>
						</thead>
						<tbody>
							<?php
							$no=1;
							$sqlDB22 = "SELECT *
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
IDS = '2 ,2 ,3 ,0 ,2' OR 
AD7.VALUESTRING = '1') AND STSMC.STEPNUMBER IS NULL THEN 1
WHEN STSMC.STEPNUMBER >= 2 AND STSMC.STEPNUMBER <= 7  THEN 2
WHEN STSMC.STEPNUMBER = 1 THEN 4
ELSE 3
END AS URUT,
STSMC1.IDS,
PRODUCTIONDEMAND.PROJECTCODE,PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,PRODUCTIONDEMAND.CODE,PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,PRODUCTIONDEMAND.PROGRESSSTATUS,SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE,
STSMC.STEPNUMBER,STSMC.LONGDESCRIPTION,STSMC.PLANNEDOPERATIONCODE,
CASE  
WHEN AD3.VALUESTRING = 0 OR AD3.VALUESTRING IS NULL  THEN 'Normal'
WHEN AD3.VALUESTRING = 1  THEN 'Urgent'
END AS STSDEMAND, 
AD8.VALUEDATE AS RMPREQDATETO,
AD4.VALUEDECIMAL AS QTYSALIN,
AD5.VALUEDECIMAL AS QTYOPIN,
AD6.VALUEDECIMAL AS QTYOPOUT,
AD7.VALUESTRING AS STSOPR,
PM.JML
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
LEFT OUTER JOIN (
SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME, DEMANDCODE FROM 
ELEMENTSINSPECTION WHERE ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM'
GROUP BY INSPECTIONENDDATETIME, DEMANDCODE
) PM ON PM.DEMANDCODE=PRODUCTIONDEMAND.CODE
WHERE PRODUCTIONDEMAND.ITEMTYPEAFICODE  ='KGF' AND 
(ADSTORAGE.VALUESTRING='$modal_id' OR SCHEDULESOFSTEPSPLITS.SCHEDULEDRESOURCECODE='$modal_id') AND 
(PRODUCTIONDEMAND.PROGRESSSTATUS='2' OR AD7.VALUESTRING='1')
ORDER BY STSMC.STEPNUMBER DESC ) STSLAYAR
ORDER BY STSLAYAR.URUT ASC
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){
$sqlDB24 =" SELECT 
trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) as IDS	
FROM PRODUCTIONDEMANDSTEP 
WHERE PRODUCTIONDEMANDCODE ='$rD[PRODUCTIONDEMANDCODE]' AND (OPERATIONCODE='INS1' OR OPERATIONCODE='KNT1')
GROUP BY PRODUCTIONDEMANDCODE ";	
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);
								
$sqlDB25 =" SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rD[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM'
GROUP BY INSPECTIONENDDATETIME ";	
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);	

$sqlDB29 =" SELECT PLANNEDOPERATIONCODE,PROGRESSSTATUS,LONGDESCRIPTION  FROM PRODUCTIONDEMANDSTEP 
WHERE PRODUCTIONDEMANDCODE ='$rD[PRODUCTIONDEMANDCODE]' AND PROGRESSSTATUS ='2' AND 
NOT (PLANNEDOPERATIONCODE='KNT1' OR PLANNEDOPERATIONCODE='INS1')
ORDER BY STEPNUMBER DESC ";	
$stmt9   = db2_exec($conn1,$sqlDB29, array('cursor'=>DB2_SCROLLABLE));
$rowdb29 = db2_fetch_assoc($stmt9);									
								
		$noArt = trim($rD['SUBCODE02']).trim($rD['SUBCODE03'])." ".trim($rD['SUBCODE04']);
								
		if(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and $rD['JML']>"0" ){
			$stts="<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Perbaikan Mesin</small>";
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and trim($rD['PLANNEDOPERATIONCODE'])=="HOLD" ){
			$stts="<small class='badge bg-black'><i class='far fa-clock text-black blink_me'></i> Hold</small>";
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and trim($rD['PLANNEDOPERATIONCODE'])=="PBS" ){
			$stts="<small class='badge bg-pink'><i class='far fa-clock text-white blink_me'></i> Habis Benang</small>";
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and trim($rD['PLANNEDOPERATIONCODE'])=="PBG" ){
			$stts="<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> Tunggu Benang Gudang</small>";
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and trim($rD['PLANNEDOPERATIONCODE'])=="AMC" ){
			$stts="<small class='badge bg-yellow'><i class='far fa-clock text-white blink_me'></i> Antri Mesin</small>";
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and trim($rD['PLANNEDOPERATIONCODE'])=="TPB" ){
			$stts="<small class='badge bg-purple'><i class='far fa-clock text-black blink_me'></i> Tunggu Pasang Benang</small>";
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and trim($rD['PLANNEDOPERATIONCODE'])=="TST" ){
			$stts="<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Setting</small>";
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and trim($rD['PLANNEDOPERATIONCODE'])=="TTQ" ){
			$stts="<small class='badge bg-gray'><i class='far fa-clock text-black blink_me'></i> Tunggu Tes Quality</small>";	
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and ($rD['IDS']=="3 ,3" or $rD['IDS']=="3 ,3 ,3") ){
			$stts="<small class='badge bg-teal'><i class='far fa-clock blink_me'></i> Sedang Jalan Oper PO</small>";
		}else if(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1")
		and (
			$rD['IDS']=="2 ,0" or
			$rD['IDS']=="0 ,2" or
			$rD['IDS']=="2 ,2" or
			$rD['IDS']=="2 ,3" or
			$rD['IDS']=="3 ,2" or
			$rD['IDS']=="2 ,2 ,3" or
			$rD['IDS']=="2 ,0 ,0" or
			$rD['IDS']=="2 ,2 ,0" or
			$rD['IDS']=="0 ,0 ,2" or
			$rD['IDS']=="0 ,2 ,0" or
			$rD['IDS']=="0 ,2 ,2" or
			$rD['IDS']=="0 ,2 ,0 ,0" or
			$rD['IDS']=="2 ,2 ,0 ,2" or
			$rD['IDS']=="2 ,2 ,0 ,0" or
			$rD['IDS']=="3 ,2 ,0 ,2" or
			$rD['IDS']=="2 ,3 ,0 ,3" or
			$rD['IDS']=="2 ,2 ,3 ,0 ,2") ) { 
		$stts="<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and ($rD['IDS']=="0 ,0" or $rD['IDS']=="0 ,0 ,0" or $rD['IDS']=="0 ,0 ,0 ,0") ){
			$stts="<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
			//$totHari=abs($hariPC);	
		}elseif(($rD['PROGRESSSTATUS']=="2" or $rD['PROGRESSSTATUS']=="3" or $rD['STSOPR']=="1") and ($rD['IDS']=="3 ,3" or $rD['IDS']=="3 ,3 ,3")  ){
			$stts="<small class='badge badge-danger'><i class='far fa-clock text-white blink_me'></i> Closed</small>";
			//$totHari=abs($hariPC);		
		}else{
			$stts="Tidak Ada PO";
		}					
		if($rD['PROJECTCODE']){$PC=$rD['PROJECTCODE'];}else{$PC=$rD['ORIGDLVSALORDLINESALORDERCODE'];}						
	echo"<tr>
  	<td align=center>$no</td>
	<td align=center>$PC</td>
	<td align=center>$rD[CODE]</td>
	<td align=center>$rD[BASEPRIMARYQUANTITY]</td>	
	<td align=center>$rD[LEGALNAME1]</td>
	<td align=center>".$noArt."</td>
	<td align=center>".$stts."</td>
	</tr>";
				$no++;				
							}
								

     
  ?>
						</tbody>
					</table>   	
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              			  	
            </div>
			</form>	
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
               
<script>
  $(function () {	 
	$('.select2sts').select2({
    placeholder: "Select a status",
    allowClear: true
});   
  });
</script>
