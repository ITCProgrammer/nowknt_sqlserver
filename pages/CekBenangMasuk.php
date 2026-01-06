<?php
$NoBon	= isset($_POST['nobon']) ? $_POST['nobon'] : '';
$NoLine	= isset($_POST['lineno']) ? $_POST['lineno'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Benang Masuk Dari GDB</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->		  
          <div class="card-body">
             <div class="form-group row">
               <label for="nobon" class="col-md-1">No Bon</label>
               <div class="col-md-2">  
                 <input name="nobon" value="<?php echo $NoBon;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
               </div>	
            </div>
			 <div class="form-group row">
			   <label for="lineno" class="col-md-1">No Line</label>
			   <div class="col-md-1">  
                 <input name="lineno" value="<?php echo $NoLine;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
			   </div>	
            </div> 
				 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Benang Masuk Dari GDB</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th rowspan="2" valign="middle" style="text-align: center">No</th>
                    <th rowspan="2" valign="middle" style="text-align: center">TGL</th>
                    <th rowspan="2" valign="middle" style="text-align: center">No BON</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Jenis Benang</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Lot</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Elements</th>
                    <th colspan="3" valign="middle" style="text-align: center">GDB</th>
                    <th colspan="2" valign="middle" style="text-align: center">GD Transit</th>
                    <th colspan="3" valign="middle" style="text-align: center">Masuk KNT</th>
                    <th colspan="3" valign="middle" style="text-align: center">Balance</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Status</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT
STOCKTRANSACTION.ORDERCODE,
STOCKTRANSACTION.ORDERLINE,
STOCKTRANSACTION.LOTCODE,  
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.BASEPRIMARYQUANTITY AS QTY_KG,
STOCKTRANSACTION.BASESECONDARYQUANTITY AS QTY_CONES,
STOCKTRANSACTION.ITEMELEMENTCODE,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M011' AND
STOCKTRANSACTION.ORDERCODE='$NoBon' AND STOCKTRANSACTION.ORDERLINE='$NoLine'
";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}		
	$knitt="";				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=$rowdb21['ORDERCODE']."-".$rowdb21['ORDERLINE'];	
		$sqlDB22 = "SELECT STOCKTRANSACTION.LOGICALWAREHOUSECODE,
		STOCKTRANSACTION.ITEMELEMENTCODE,STOCKTRANSACTION.BASESECONDARYQUANTITY,
		STOCKTRANSACTION.BASEPRIMARYQUANTITY,STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
		STOCKTRANSACTION.WAREHOUSELOCATIONCODE,STOCKTRANSACTION.LOTCODE  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION  
		WHERE (STOCKTRANSACTION.LOGICALWAREHOUSECODE='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE='M904') AND 
		STOCKTRANSACTION.ITEMELEMENTCODE='$rowdb21[ITEMELEMENTCODE]'";
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
		$rD=db2_fetch_assoc($stmt2);
		$sqlDB23 = "SELECT BASEPRIMARYQUANTITYUNIT,BASESECONDARYQUANTITYUNIT,WHSLOCATIONWAREHOUSEZONECODE,WAREHOUSELOCATIONCODE 
		FROM DB2ADMIN.BALANCE BALANCE  
		WHERE (BALANCE.LOGICALWAREHOUSECODE='P501' OR BALANCE.LOGICALWAREHOUSECODE='M501' OR BALANCE.LOGICALWAREHOUSECODE='M904') AND 
		BALANCE.ELEMENTSCODE='$rowdb21[ITEMELEMENTCODE]'";
		$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
		$rD1=db2_fetch_assoc($stmt3);
		$sqlDB24 = "SELECT  BASEPRIMARYQUANTITYUNIT,BASESECONDARYQUANTITYUNIT,WHSLOCATIONWAREHOUSEZONECODE,WAREHOUSELOCATIONCODE 
		FROM DB2ADMIN.BALANCE BALANCE  
		WHERE BALANCE.LOGICALWAREHOUSECODE='TR11' AND 
		BALANCE.ELEMENTSCODE='$rowdb21[ITEMELEMENTCODE]'";
		$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
		$rD2=db2_fetch_assoc($stmt4);
		$stts="";
		if($rowdb21['QTY_KG']==$rD['BASEPRIMARYQUANTITY'] ){
			$stts="<small class='badge badge-success'> OK</small>";
		}elseif(($rowdb21['QTY_KG']>$rD['BASEPRIMARYQUANTITY'] and $rD1['BASEPRIMARYQUANTITYUNIT']>0) or ($rD2['BASEPRIMARYQUANTITYUNIT']>0 and $rowdb21['QTY_KG']> $rD2['BASEPRIMARYQUANTITYUNIT']) ){
			$stts="<small class='badge badge-warning'><i class='fas fa-exclamation-triangle text-white blink_me'></i> Tidak OK</small>";
		}else if((number_format(round($rowdb21['QTY_KG'],2),2)==number_format(round($rD2['BASEPRIMARYQUANTITYUNIT'],2),2)) and (round($rowdb21['QTY_CONES'],2)==round($rD2['BASESECONDARYQUANTITYUNIT'],2))) {
			$stts="<small class='badge badge-danger'><i class='far fa-clock blink_me'></i> Belum Masuk</small>";
		}else if($rowdb21['QTY_KG']>0 and $rD['BASEPRIMARYQUANTITY']>0 and $rD1['BASEPRIMARYQUANTITYUNIT']=="0" ) {
			$stts="<small class='badge badge-info'><i class='far fa-clock blink_me'></i> Sudah Pakai</small>";	
		}
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><?php echo $bon; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['ITEMELEMENTCODE']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      <td style="text-align: right"><?php echo round($rD2['BASESECONDARYQUANTITYUNIT']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rD2['BASEPRIMARYQUANTITYUNIT'],2),2); ?></td>
      <td style="text-align: right"><?php echo round($rD['BASESECONDARYQUANTITY']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rD['BASEPRIMARYQUANTITY'],2),2); ?></td>
      <td><?php echo $rD['WHSLOCATIONWAREHOUSEZONECODE']."-".$rD['WAREHOUSELOCATIONCODE']; ?></td>
      <td style="text-align: right"><?php echo round($rD1['BASESECONDARYQUANTITYUNIT']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rD1['BASEPRIMARYQUANTITYUNIT'],2),2); ?></td>
      <td><?php echo $rD1['WHSLOCATIONWAREHOUSEZONECODE']."-".$rD1['WAREHOUSELOCATIONCODE']; ?></td>
      <td><?php echo $stts;?></td>
      </tr>				  
	<?php 
	 $no++; } ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
	</form>		
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="DetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

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
<script type="text/javascript">
function checkAll(form1){
    for (var i=0;i<document.forms['form1'].elements.length;i++)
    {
        var e=document.forms['form1'].elements[i];
        if ((e.name !='allbox') && (e.type=='checkbox'))
        {
            e.checked=document.forms['form1'].allbox.checked;
			
        }
    }
}
</script>