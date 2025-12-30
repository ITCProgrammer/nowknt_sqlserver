<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$pos=strpos($modal_id,"#");
	$Project=substr($modal_id,0,$pos);
	$dt=substr($modal_id,$pos+1,250);
	$pos1=strpos($dt,"#");
	$Item=substr($dt,0,$pos1);
	$Code=substr($dt,$pos1+1,250);
	$kdbng=str_replace(" ","",$Code);
	
	
?>
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Internal Document Line</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"><i>
			Project: <b><?php echo $Project;?></b><br>
			Item: <b><?php echo $Item;?></b><br>	
			Code: <b><?php echo $Code;?></b>				
			</i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">BON</div></th>
								<th><div align="center">Mohon</div></th>
								<th><div align="center">Kirim</div></th>
								<th><div align="center">Terima</div></th>
								<th><div align="center">Lot Benang</div></th>
								<th><div align="center">Tgl Bon</div></th>					
							</tr>							
						</thead>
						<tbody>
							<?php
							$no=1;
							$sqlDB22 = "SELECT il.INTDOCPROVISIONALCOUNTERCODE,il.INTDOCUMENTPROVISIONALCODE,il.ORDERLINE,SUM(il.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
SUM(il.SHIPPEDBASEPRIMARYQUANTITY) AS SHIPPEDBASEPRIMARYQUANTITY,
SUM(il.RECEIVEDBASEPRIMARYQUANTITY) AS RECEIVEDBASEPRIMARYQUANTITY,a.VALUESTRING 
FROM INTERNALDOCUMENT i LEFT JOIN
INTERNALDOCUMENTLINE il ON i.PROVISIONALCODE=il.INTDOCUMENTPROVISIONALCODE AND i.PROVISIONALCOUNTERCODE=il.INTDOCPROVISIONALCOUNTERCODE 
LEFT JOIN ADSTORAGE a ON a.UNIQUEID =il.ABSUNIQUEID	AND a.NAMENAME ='Lot'
WHERE il.EXTERNALREFERENCE ='$Project' AND
il.INTERNALREFERENCE LIKE '$Item%' AND
CONCAT(trim(il.SUBCODE01),CONCAT(trim(il.SUBCODE02),CONCAT(trim(il.SUBCODE03),CONCAT(trim(il.SUBCODE04),CONCAT(trim(il.SUBCODE05),CONCAT(trim(il.SUBCODE06),CONCAT(trim(il.SUBCODE07),trim(il.SUBCODE08))))))))='$kdbng'
GROUP BY il.INTDOCPROVISIONALCOUNTERCODE,il.INTDOCUMENTPROVISIONALCODE,il.ORDERLINE,a.VALUESTRING 
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){
		if (trim($rD['LOGICALWAREHOUSECODE'])=="P501"){ $kntt= 'LT1'; }elseif (trim($rD['LOGICALWAREHOUSECODE'])=="M501" or trim($rD['LOGICALWAREHOUSECODE'])=="M904"){ $kntt= 'LT2'; }
								
		$sqlDB2T = "SELECT x.TRANSACTIONDATE FROM DB2ADMIN.STOCKTRANSACTION x
				WHERE ORDERCODE ='".$rD['INTDOCUMENTPROVISIONALCODE']."' AND ORDERLINE='".$rD['ORDERLINE']."' AND TEMPLATECODE ='204' AND  TOKENCODE ='RECEIVED'
				GROUP BY x.TRANSACTIONDATE";						
		$stmt2T   = db2_exec($conn1,$sqlDB2T, array('cursor'=>DB2_SCROLLABLE));	
		$rT=db2_fetch_assoc($stmt2T);						
	echo"<tr>
  	<td align=center>$no</td>
	<td align=left>".$rD['INTDOCUMENTPROVISIONALCODE']."-".$rD['ORDERLINE']."</td>
	<td align=right>".number_format(round($rD['BASEPRIMARYQUANTITY'],3),3)."</td>
	<td align=right>".number_format(round($rD['SHIPPEDBASEPRIMARYQUANTITY'],3),3)."</td>	
	<td align=right>".number_format(round($rD['RECEIVEDBASEPRIMARYQUANTITY'],3),3)."</td>
	<td align=center>".$rD['VALUESTRING']."</td>
	<td align=center>".$rT['TRANSACTIONDATE']."</td>
	</tr>";
				$no++;		
				$totM+=round($rD['BASEPRIMARYQUANTITY'],3);
				$totK+=round($rD['SHIPPEDBASEPRIMARYQUANTITY'],3);
				$totT+=round($rD['RECEIVEDBASEPRIMARYQUANTITY'],3);				
							}
								

     
  ?>							
						</tbody>
				<tfoot>
				<tr>
				  <td>&nbsp;</td>
							  <td align="right"><strong>Total</strong></td>
							  <td align="right"><strong><?php echo number_format($totM,3); ?></strong></td>
							  <td align="right"><strong><?php echo number_format($totK,3); ?></strong></td>
							  <td align="right"><strong><?php echo number_format($totT,3); ?></strong></td>
							  <td align="right">&nbsp;</td>
							  <td align="right">&nbsp;</td>
				  </tr>
				</tfoot>
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
<script>
  $(function () {
    $("#lookup50").DataTable({
	  "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,	
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#lookup50_wrapper .col-md-6:eq(0)');		 
  });
</script>
