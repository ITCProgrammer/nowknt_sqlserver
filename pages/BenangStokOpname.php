<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  		
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Stok Benang</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example11" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Kode Supplier</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Kgs</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Kgs</th>
                    <th valign="middle" style="text-align: center">Warehouse</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " 
	SELECT 
	BALANCE.DECOSUBCODE01,
	BALANCE.DECOSUBCODE02,
	BALANCE.DECOSUBCODE03,
	BALANCE.DECOSUBCODE04,
	BALANCE.DECOSUBCODE05,
	BALANCE.DECOSUBCODE06,
	BALANCE.DECOSUBCODE07,
	BALANCE.DECOSUBCODE08,	
	CASE
        WHEN LOCATE('+', BALANCE.LOTCODE) = 0 THEN
    BALANCE.LOTCODE
        ELSE
    SUBSTR(BALANCE.LOTCODE, 1, LOCATE('+', BALANCE.LOTCODE)-1)
    END
    AS LOTCODE,
	COUNT(BALANCE.BASEPRIMARYQUANTITYUNIT) AS QTY_ROL,
	SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS QTY_KG,
	SUM(BALANCE.BASESECONDARYQUANTITYUNIT) AS QTY_CONES,
	BL.BASEPRIMARYQUANTITYUNIT AS KG,
	BL.BASESECONDARYQUANTITYUNIT AS CONES,
	BALANCE.WHSLOCATIONWAREHOUSEZONECODE,
	BALANCE.WAREHOUSELOCATIONCODE,BALANCE.LOGICALWAREHOUSECODE,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION  
FROM DB2ADMIN.BALANCE BALANCE 
LEFT OUTER JOIN (
SELECT ELEMENTSCODE,BASEPRIMARYQUANTITYUNIT, BASESECONDARYQUANTITYUNIT  FROM BALANCE WHERE 
(LOGICALWAREHOUSECODE='P501' OR LOGICALWAREHOUSECODE='M904') AND (ITEMTYPECODE='GYR' OR ITEMTYPECODE='DYR')
) BL ON BL.ELEMENTSCODE=BALANCE.ELEMENTSCODE 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON FULLITEMKEYDECODER.ITEMTYPECODE = BALANCE.ITEMTYPECODE AND
FULLITEMKEYDECODER.SUBCODE01 = BALANCE.DECOSUBCODE01  AND
FULLITEMKEYDECODER.SUBCODE02 = BALANCE.DECOSUBCODE02  AND
FULLITEMKEYDECODER.SUBCODE03 = BALANCE.DECOSUBCODE03  AND
FULLITEMKEYDECODER.SUBCODE04 = BALANCE.DECOSUBCODE04  AND
FULLITEMKEYDECODER.SUBCODE05 = BALANCE.DECOSUBCODE05  AND
FULLITEMKEYDECODER.SUBCODE06 = BALANCE.DECOSUBCODE06  AND
FULLITEMKEYDECODER.SUBCODE07 = BALANCE.DECOSUBCODE07  AND
FULLITEMKEYDECODER.SUBCODE08 = BALANCE.DECOSUBCODE08
WHERE (BALANCE.LOGICALWAREHOUSECODE='P501' OR BALANCE.LOGICALWAREHOUSECODE='M904') AND (BALANCE.ITEMTYPECODE='GYR' OR BALANCE.ITEMTYPECODE='DYR')
GROUP BY BALANCE.WHSLOCATIONWAREHOUSEZONECODE,
BALANCE.WAREHOUSELOCATIONCODE,
BALANCE.LOGICALWAREHOUSECODE,
BALANCE.LOTCODE,
BALANCE.DECOSUBCODE01,
BALANCE.DECOSUBCODE02,
BALANCE.DECOSUBCODE03,
BALANCE.DECOSUBCODE04,
BALANCE.DECOSUBCODE05,
BALANCE.DECOSUBCODE06,
BALANCE.DECOSUBCODE07,
BALANCE.DECOSUBCODE08,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
BL.BASEPRIMARYQUANTITYUNIT,
BL.BASESECONDARYQUANTITYUNIT
		"; 
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}						  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=$rowdb21['INTDOCUMENTPROVISIONALCODE']."-".$rowdb21['ORDERLINE'];
if (trim($rowdb21['LOGICALWAREHOUSECODE']) =="M904") { $knitt = "LT2"; }
else if(trim($rowdb21['LOGICALWAREHOUSECODE']) =="P501"){ $knitt = "LT1"; }

$bon=$rowdb21['INTDOCUMENTPROVISIONALCODE']."-".$rowdb21['ORDERLINE'];	
$kdbenang=$rowdb21['DECOSUBCODE01']." ".$rowdb21['DECOSUBCODE02']." ".$rowdb21['DECOSUBCODE03']." ".$rowdb21['DECOSUBCODE04']." ".$rowdb21['DECOSUBCODE05']." ".$rowdb21['DECOSUBCODE06']." ".$rowdb21['DECOSUBCODE07']." ".$rowdb21['DECOSUBCODE08'];	
if($rowdb21['LOTCODE']=="-"){}else{		
$sqlDB23 = " SELECT a.SUPPLIERCODE, b2.LEGALNAME1  FROM LOT a
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA b ON a.SUPPLIERCODE = b.CODE 
LEFT OUTER JOIN BUSINESSPARTNER b2 ON b2.NUMBERID = b.BUSINESSPARTNERNUMBERID 
WHERE a.CODE='".$rowdb21['LOTCODE']."' AND a.DECOSUBCODE01 ='".$rowdb21['DECOSUBCODE01']."' 
AND a.DECOSUBCODE02 ='".$rowdb21['DECOSUBCODE02']."' AND a.DECOSUBCODE03 ='".$rowdb21['DECOSUBCODE03']."'
AND a.DECOSUBCODE04 ='".$rowdb21['DECOSUBCODE04']."' AND a.DECOSUBCODE05 ='".$rowdb21['DECOSUBCODE05']."'
AND a.DECOSUBCODE06 ='".$rowdb21['DECOSUBCODE06']."' AND a.DECOSUBCODE07 ='".$rowdb21['DECOSUBCODE07']."'
AND a.DECOSUBCODE08 ='".$rowdb21['DECOSUBCODE08']."'
AND NOT a.SUPPLIERCODE IS NULL ";
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);	
}
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: left"><?php echo $kdbenang; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb23['SUPPLIERCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb23['LEGALNAME1']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['KG'],2),2); ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOGICALWAREHOUSECODE']; ?></td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      </tr>	  				  
	<?php 
	 $no++; 
		$tRol+=$rowdb21['QTY_ROL'];
		$tCones+=$rowdb21['QTY_CONES'];
		$tKg+=$rowdb21['QTY_KG'];
	} ?>
				  </tbody>
      <tfoot>
	  <tr>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong><?php echo $tCones; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKg,2),2); ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tRol; ?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
					</tfoot>            
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