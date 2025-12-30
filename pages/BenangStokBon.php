<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  		
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Stok Benang Per BON</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">TGL</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">KNITT</th>
                    <th valign="middle" style="text-align: center">No PO</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " 
	SELECT BN.INTDOCUMENTPROVISIONALCODE,
	BN.SUMMARIZEDDESCRIPTION,
	BN.TRANSACTIONDATE,
	BN.LOGICALWAREHOUSECODE AS WKNT,
	BN.ORDERLINE,
	BN.SUBCODE01,
	BN.SUBCODE02,
	BN.SUBCODE03,
	BN.SUBCODE04,
	BN.SUBCODE05,
	BN.SUBCODE06,
	BN.SUBCODE07,
	BN.SUBCODE08,
	BN.EXTERNALREFERENCE,
	BN.LOTCODE,
	COUNT(BALANCE.BASEPRIMARYQUANTITYUNIT) AS QTY_ROL,
	SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS QTY_KG,
	SUM(BALANCE.BASESECONDARYQUANTITYUNIT) AS QTY_CONES,BALANCE.WHSLOCATIONWAREHOUSEZONECODE,BALANCE.WAREHOUSELOCATIONCODE,BALANCE.LOGICALWAREHOUSECODE 
		FROM DB2ADMIN.BALANCE BALANCE  
		INNER JOIN (
		SELECT 
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
STOCKTRANSACTION.LOTCODE,  
STOCKTRANSACTION.TRANSACTIONDATE,
SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL,
SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.ITEMELEMENTCODE,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE=STOCKTRANSACTION.ORDERCODE AND 
INTERNALDOCUMENTLINE.ORDERLINE=STOCKTRANSACTION.ORDERLINE 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (NOT INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE IS NULL) AND 
(STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904' or STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501') AND
NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
GROUP BY
STOCKTRANSACTION.LOTCODE,
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.ITEMELEMENTCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION		
		) BN ON BN.ITEMELEMENTCODE=BALANCE.ELEMENTSCODE 	
WHERE (BALANCE.LOGICALWAREHOUSECODE='P501' OR BALANCE.LOGICALWAREHOUSECODE='M904')
GROUP BY BALANCE.WHSLOCATIONWAREHOUSEZONECODE,
BALANCE.WAREHOUSELOCATIONCODE,
BALANCE.LOGICALWAREHOUSECODE,
BN.INTDOCUMENTPROVISIONALCODE,
BN.SUMMARIZEDDESCRIPTION,
BN.TRANSACTIONDATE,
BN.LOGICALWAREHOUSECODE,
BN.EXTERNALREFERENCE,
BN.ORDERLINE,
BN.LOTCODE,
BN.SUBCODE01,
BN.SUBCODE02,
BN.SUBCODE03,
BN.SUBCODE04,
BN.SUBCODE05,
BN.SUBCODE06,
BN.SUBCODE07,
BN.SUBCODE08
	"; 
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}						  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
if (trim($rowdb21['WKNT']) =="M904") { $knitt = "LT2"; }
else if(trim($rowdb21['WKNT']) =="P501"){ $knitt = "LT1"; }
$kdbenang=$rowdb21['SUBCODE01']." ".$rowdb21['SUBCODE02']." ".$rowdb21['SUBCODE03']." ".$rowdb21['SUBCODE04']." ".$rowdb21['SUBCODE05']." ".$rowdb21['SUBCODE06']." ".$rowdb21['SUBCODE07']." ".$rowdb21['SUBCODE08'];		
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE'])."-".trim($rowdb21['TRANSACTIONDATE']); ?>" class="show_detailStkPBon"><?php echo trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE']); ?></a></td>
      <td style="text-align: center"><?php echo $knitt; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td><?php echo $kdbenang; ?></td> 
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
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
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td colspan="2" style="text-align: right"><span style="text-align: right"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tRol; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tCones; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKg,2),2); ?></strong></td>
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
<div id="DetailShowStkPBon" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
