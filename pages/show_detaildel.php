<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
$modal_id=$_GET['id'];
	
	
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
			Trn No: <b><?php echo $modal_id;?></b>				
			</i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">ELEMENTCODE</div></th>
								<th><div align="center">CONES</div></th>
								<th><div align="center">KGS</div></th>
								<th><div align="center">LOTCODE</div></th>
								<th><div align="center">KNIT</div></th>															
							</tr>
						</thead>
						<tbody>
							<?php
							$no=1;
							$sqlDB22 = "SELECT STOCKTRANSACTION.LOGICALWAREHOUSECODE,STOCKTRANSACTION.ITEMELEMENTCODE,STOCKTRANSACTION.BASESECONDARYQUANTITY,STOCKTRANSACTION.BASEPRIMARYQUANTITY,STOCKTRANSACTION.LOTCODE  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION  
		WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' or STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
		STOCKTRANSACTION.TEMPLATECODE ='098' AND STOCKTRANSACTION.TRANSACTIONNUMBER='$modal_id'
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){
		if (trim($rD['LOGICALWAREHOUSECODE'])=="P501"){ $kntt= 'LT1'; }elseif (trim($rD['LOGICALWAREHOUSECODE'])=="M501" or trim($rD['LOGICALWAREHOUSECODE'])=="M904"){ $kntt= 'LT2'; }
								
	echo"<tr>
  	<td align=center>$no</td>
	<td align=center>$rD[ITEMELEMENTCODE]</td>
	<td align=center>".round($rD['BASESECONDARYQUANTITY'])."</td>
	<td align=center>".number_format(round($rD['BASEPRIMARYQUANTITY'],2),2)."</td>	
	<td align=center>$rD[LOTCODE]</td>
	<td align=center>".$kntt."</td>
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
