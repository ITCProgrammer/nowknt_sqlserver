<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$ORDERCODE=substr($modal_id,0,13);	
	$TGL=substr($modal_id,14,200);
	$pos=strpos($TGL,"-")+1;
	$pos1=strpos($TGL,"-");	
	$jm=strpos($TGL,"-");
	$ODERLINE=substr($modal_id,14,$jm);
	$TGLMSK=substr($TGL,$pos,200);
	
	
?>
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Data Element</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"><i>
			Tgl: <b><?php echo $TGLMSK;?></b><br>
			BON: <b><?php echo $ORDERCODE."-".$ODERLINE;?></b>				
			</i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 12px;">
						<thead>
							<tr>
							  <th colspan="7" style="text-align: center">Masuk</th>
							  <th colspan="4" style="text-align: center">Keluar</th>
							  </tr>
							<tr>
								<th style="text-align: center">#</th>
								<th style="text-align: center">TRN No.</th>
								<th style="text-align: center">ELEMENTCODE</th>
								<th style="text-align: center">CONES</th>
								<th style="text-align: center">KGS</th>
								<th style="text-align: center">LOTCODE</th>
								<th style="text-align: center">KNIT</th>
								<th style="text-align: center">TRN No.</th>
								<th style="text-align: center">TGL</th>
								<th style="text-align: center">ORDERCODE</th>
								<th style="text-align: center">PROJECTCODE</th>															
							</tr>
						</thead>
						<tbody>
							<?php
							$no=1;
							$sqlDB22 = "SELECT STOCKTRANSACTION.TRANSACTIONNUMBER, STOCKTRANSACTION.LOGICALWAREHOUSECODE,STOCKTRANSACTION.ITEMELEMENTCODE,STOCKTRANSACTION.BASESECONDARYQUANTITY,STOCKTRANSACTION.BASEPRIMARYQUANTITY,STOCKTRANSACTION.LOTCODE  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION  
		WHERE (STOCKTRANSACTION.LOGICALWAREHOUSECODE='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE='M904') AND STOCKTRANSACTION.ORDERCODE='$ORDERCODE'
		AND STOCKTRANSACTION.ORDERLINE ='$ODERLINE' AND STOCKTRANSACTION.TRANSACTIONDATE='$TGLMSK'
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){
		$sqlDB23 = "SELECT TRANSACTIONNUMBER,TRANSACTIONDATE,ORDERCODE,PROJECTCODE FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 
		WHERE STOCKTRANSACTION.ITEMELEMENTCODE ='$rD[ITEMELEMENTCODE]' AND STOCKTRANSACTION.ONHANDUPDATE >1 AND 
		(STOCKTRANSACTION.LOGICALWAREHOUSECODE='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE='M904') AND STOCKTRANSACTION.TEMPLATECODE='120'";
		$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));						
		$rD1=db2_fetch_assoc($stmt3);							
		if (trim($rD['LOGICALWAREHOUSECODE'])=="P501"){ $kntt= 'LT1'; }elseif (trim($rD['LOGICALWAREHOUSECODE'])=="M904"){ $kntt= 'LT2'; }
								
	echo"<tr>
  	<td align=center>$no</td>
	<td align=center>$rD[TRANSACTIONNUMBER]</td>
	<td align=center>$rD[ITEMELEMENTCODE]</td>
	<td align=center>".round($rD['BASESECONDARYQUANTITY'])."</td>
	<td align=center>".number_format(round($rD['BASEPRIMARYQUANTITY'],2),2)."</td>	
	<td align=center>$rD[LOTCODE]</td>
	<td align=center>".$kntt."</td>
	<td align=center>$rD1[TRANSACTIONNUMBER]</td>
	<td align=center>$rD1[TRANSACTIONDATE]</td>
	<td align=center>$rD1[ORDERCODE]</td>
	<td align=center>$rD1[PROJECTCODE]</td>
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
