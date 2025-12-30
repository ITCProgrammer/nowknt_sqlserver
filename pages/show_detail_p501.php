<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$code=str_replace(" ","",$modal_id);	
	
	
?>
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Stok Balance</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"><i>				
			Code: <b><?php echo $modal_id;?></b>				
			</i>	
			<table id="lookup10" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
							  <td>#</td>
							  <td><div align="center">LOT</div></td>
							  <td><div align="center">SUPPLIER</div></td>
							  <td><div align="center">QTY</div></td>
							  <td><div align="center">QUALITY</div></td>
						  </tr>
						</thead>
						<tbody>
							<?php
							$no=1;
							$sqlDB22 = " SELECT
	sum(a.BASEPRIMARYQUANTITYUNIT) AS TOTALSTK,
	a.LOTCODE,
	a.QUALITYLEVELCODE
    
FROM
	BALANCE a
WHERE
	a.ITEMTYPECODE = 'GYR'
	AND a.LOGICALWAREHOUSECODE = 'P501'
	AND
CONCAT(trim(a.DECOSUBCODE01), 
CONCAT(trim(a.DECOSUBCODE02), 
CONCAT(trim(a.DECOSUBCODE03), 
CONCAT(trim(a.DECOSUBCODE04), 
CONCAT(trim(a.DECOSUBCODE05), 
CONCAT(trim(a.DECOSUBCODE06), 
CONCAT(trim(a.DECOSUBCODE07), 
trim(a.DECOSUBCODE08))))))))= '$code'
GROUP BY
	a.LOTCODE,
	a.QUALITYLEVELCODE
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){ 
$Sdb23="
	SELECT b2.* FROM LOT b 	
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA c ON c.CODE = b.SUPPLIERCODE
LEFT OUTER JOIN BUSINESSPARTNER b2 ON c.BUSINESSPARTNERNUMBERID =b2.NUMBERID 
WHERE b.CODE='$rD[LOTCODE]' LIMIT 1
	";
	$st13   = db2_exec($conn1,$Sdb23, array('cursor'=>DB2_SCROLLABLE));
	$rdb23 = db2_fetch_assoc($st13);							
							
							?>
	<tr>
		<td><?php echo $no; ?></td>
		<td><?php echo $rD['LOTCODE'];?></td>
		<td><?php echo $rdb23['LEGALNAME1'];?></td>
		<td><?php echo number_format(round($rD['TOTALSTK'],3),3);?></td>
		<td><?php echo $rD['QUALITYLEVELCODE'];?></td>
	</tr>						
	<?php
				$no++;
				$totT+=round($rD['TOTALSTK'],3);				
	}
  ?>							
						</tbody>
				<tfoot>
				<tr>
				  			  <td>&nbsp;</td>
							  <td align="right">&nbsp;</td>
							  <td align="right"><strong>Total</strong></td>
							  <td align="right"><strong><?php echo number_format($totT,3); ?></strong></td>	
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
    $("#lookup10").DataTable({
	  "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,	
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#lookup10_wrapper .col-md-6:eq(0)');		 
  });
</script>
