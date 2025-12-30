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
								<th><div align="center">Prod. Order</div></th>
								<th><div align="center">Konsumen</div></th>
								<th><div align="center">No Art</div></th>
								<th><div align="center">ProgressStatus</div></th>															
							</tr>
						</thead>
						<tbody>
							<?php
							$no=1;
							$sqlDB22 = "SELECT ADSTORAGE.VALUESTRING,AD1.VALUEDATE,AD2.VALUEDATE AS RMPREQDATE , ITXVIEWKNTORDER.*,CURRENT_TIMESTAMP AS TGLS,PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE FROM ITXVIEWKNTORDER 
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME ='TglRencana'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME ='RMPReqDate'
WHERE ITXVIEWKNTORDER.ITEMTYPEAFICODE ='KGF' AND (SCHEDULEDRESOURCECODE ='$modal_id' OR ADSTORAGE.VALUESTRING='$modal_id') AND ITXVIEWKNTORDER.PROGRESSSTATUS='2' 
 ORDER BY ITXVIEWKNTORDER.INITIALSCHEDULEDACTUALDATE,AD1.VALUEDATE ASC
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
								
		if($rD['PROGRESSSTATUS']=="2" and $rowdb25['JML']>"0" ){
			$stts="<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Perbaikan Mesin</small>";
			//$totHari=abs($hariPR);
		}		
		elseif($rD['PROGRESSSTATUS']=="2" and trim($rowdb29['PLANNEDOPERATIONCODE'])=="AMC" ){
			$stts="<small class='badge bg-yellow'><i class='far fa-clock text-white blink_me'></i> Antri Mesin</small>";
			//$totHari=abs($hariPC);
		}		
		elseif($rD['PROGRESSSTATUS']=="2" and trim($rowdb29['PLANNEDOPERATIONCODE'])=="TST" ){
			$stts="<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Setting</small>";
			//$totHari=abs($hariPC);
		}		 
		elseif($rD['PROGRESSSTATUS']=="2" and trim($rowdb29['PLANNEDOPERATIONCODE'])=="PBS" ){
			$stts="<small class='badge bg-pink'><i class='far fa-clock text-white blink_me'></i> Habis Benang</small>";
			//$totHari=abs($hariPC);
		}		 
		elseif($rD['PROGRESSSTATUS']=="2" and trim($rowdb29['PLANNEDOPERATIONCODE'])=="PBG" ){
			$stts="<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> Tunggu Benang Gudang</small>";
			//$totHari=abs($hariPC);
		}
		elseif($rD['PROGRESSSTATUS']=="2" and trim($rowdb29['PLANNEDOPERATIONCODE'])=="TPB" ){
			$stts="<small class='badge bg-purple'><i class='far fa-clock text-black blink_me'></i> Tunggu Pasang Benang</small>";
			//$totHari=abs($hariPC);	
		}
		elseif($rD['PROGRESSSTATUS']=="2" and trim($rowdb29['PLANNEDOPERATIONCODE'])=="TTQ" ){
			$stts="<small class='badge bg-gray'><i class='far fa-clock text-black blink_me'></i> Tunggu Tes Quality</small>";
			//$totHari=abs($hariPC);
		}	
		elseif($rD['PROGRESSSTATUS']=="2" and trim($rowdb29['PLANNEDOPERATIONCODE'])=="HOLD" ){
			$stts="<small class='badge bg-black'><i class='far fa-clock text-black blink_me'></i> Hold</small>";
			//$totHari=abs($hariPC);	
		}elseif($rD['PROGRESSSTATUS']=="2" and ($rowdb24['IDS']=="0 ,0" or $rowdb24['IDS']=="0 ,0 ,0" or $rowdb24['IDS']=="0 ,0 ,0 ,0") ){
			$stts="<small class='badge bg-orange'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
			//$totHari=abs($hariPC);	
		}elseif($rD['PROGRESSSTATUS']=="2" and $rowdb24['IDS']=="3 ,3" ){
			$stts="<small class='badge badge-danger'><i class='far fa-clock text-white blink_me'></i> Closed</small>";
			//$totHari=abs($hariPC);	
		}else if($rD['PROGRESSSTATUS']=="2" and ($rowdb24['IDS']=="2 ,0" or $rowdb24['IDS']=="0 ,2" or 
												 $rowdb24['IDS']=="2 ,2" or $rowdb24['IDS']=="2 ,3" or
												 $rowdb24['IDS']=="2 ,0 ,0" or $rowdb24['IDS']=="2 ,2 ,0" or 
												 $rowdb24['IDS']=="0 ,0 ,2" or
												 $rowdb24['IDS']=="2 ,2 ,0 ,2" or $rowdb24['IDS']=="2 ,2 ,0 ,0" or 
												 $rowdb24['IDS']=="3 ,2 ,0 ,2" or $rowdb24['IDS']=="2 ,3 ,0 ,3" or 
												 $rowdb24['IDS']=="2 ,2 ,3 ,0 ,2") ) { 
			$stts="<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
			//$totHari=abs($hariSJ);
		}else{
			$stts="Tidak Ada PO";
		}						
		if($rD['PROJECTCODE']){$PC=$rD['PROJECTCODE'];}else{$PC=$rD['ORIGDLVSALORDLINESALORDERCODE'];}						
	echo"<tr>
  	<td align=center>$no</td>
	<td align=center>$rD[PROJECTCODE]</td>
	<td align=center>$rD[PRODUCTIONDEMANDCODE]</td>
	<td align=center>$rD[PRODUCTIONORDERCODE]</td>	
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
