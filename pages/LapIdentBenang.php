<?php
$Code1	= isset($_POST['code1']) ? $_POST['code1'] : '';
$Code2	= isset($_POST['code2']) ? $_POST['code2'] : '';
$Code3	= isset($_POST['code3']) ? $_POST['code3'] : '';
$Code4	= isset($_POST['code4']) ? $_POST['code4'] : '';
$Code5	= isset($_POST['code5']) ? $_POST['code5'] : '';
$Code6	= isset($_POST['code6']) ? $_POST['code6'] : '';
$Code7	= isset($_POST['code7']) ? $_POST['code7'] : '';
$Code8	= isset($_POST['code8']) ? $_POST['code8'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Filter Identifikasi Benang</h3>

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
               <label for="kode" class="col-md-1">Code</label>
               <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code1; ?>" name="code1" placeholder="Count/Ply" required>
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code2; ?>" name="code2" placeholder="Composition" required>
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code3; ?>" name="code3" placeholder="Composition %" required>
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code4; ?>" name="code4" placeholder="Technology" required>
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code5; ?>" name="code5" placeholder="Twist" required>
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code6; ?>" name="code6" placeholder="Elastan Type" required>
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code7; ?>" name="code7" placeholder="Variant/Grade" required>
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code8; ?>" name="code8" placeholder="Color" required>
			   </div>	 
            </div>
			  
				 
			  <button class="btn btn-info" type="submit" >Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card">
              <div class="card-header">
                <h3 class="card-title">Detail Data Benang</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th colspan="10" valign="middle" style="text-align: center">Masuk</th>
                    <th colspan="4" valign="middle" style="text-align: center">Sisa</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">No PO</th>
                    <th valign="middle" style="text-align: center">SuratJalan</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">No Trn</th>
                    <th valign="middle" style="text-align: center">Pergerakan Benang</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = "SELECT MRNHEADER.MRNDATE,MRNHEADER.CHALLANNO,MRNHEADER.PURCHASEORDERCODE,
              MRNHEADER.MRNPREFIXCODE,MRNHEADER.PURCHASEORDERCOUNTERCODE,MRNDETAIL.ORDERLINE,
              STOCKTRANSACTION.DECOSUBCODE01,STOCKTRANSACTION.DECOSUBCODE02,
              STOCKTRANSACTION.DECOSUBCODE03,STOCKTRANSACTION.DECOSUBCODE04,
              STOCKTRANSACTION.DECOSUBCODE05,STOCKTRANSACTION.DECOSUBCODE06,
              STOCKTRANSACTION.DECOSUBCODE07,STOCKTRANSACTION.DECOSUBCODE08,
              STOCKTRANSACTION.DECOSUBCODE09,STOCKTRANSACTION.DECOSUBCODE10,
              STOCKTRANSACTION.ITEMDESCRIPTION,STOCKTRANSACTION.LOTCODE,
              STOCKTRANSACTION.USERPRIMARYUOMCODE,
              STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE, STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
              ITXVIEWLAPMASUKBENANG.LEGALNAME1,
              ITXVIEWLAPMASUKBENANG.SUMMARIZEDDESCRIPTION,
              SUM(BASEPRIMARYQUANTITY) AS COBA_TOTAL,
              COUNT(MRNDATE) AS COBA_JUMLAH,
              STOCKTRANSACTION.TRANSACTIONNUMBER

              FROM MRNHEADER MRNHEADER LEFT OUTER JOIN STOCKTRANSACTION STOCKTRANSACTION ON STOCKTRANSACTION.ORDERCODE = MRNHEADER.PURCHASEORDERCODE
              LEFT OUTER JOIN ITXVIEWLAPMASUKBENANG ITXVIEWLAPMASUKBENANG ON MRNHEADER.CODE=ITXVIEWLAPMASUKBENANG.MRNHEADERCODE AND MRNHEADER.PURCHASEORDERCODE=ITXVIEWLAPMASUKBENANG.PURCHASEORDERCODE
			  INNER JOIN MRNDETAIL MRNDETAIL ON MRNDETAIL.TRANSACTIONNUMBER = STOCKTRANSACTION.TRANSACTIONNUMBER
              WHERE MRNHEADER.MRNPREFIXCODE = 'YRL' AND 
              STOCKTRANSACTION.DECOSUBCODE01='$Code1' AND 
              STOCKTRANSACTION.DECOSUBCODE02='$Code2' AND
              STOCKTRANSACTION.DECOSUBCODE03='$Code3' AND 
              STOCKTRANSACTION.DECOSUBCODE04='$Code4' AND
              STOCKTRANSACTION.DECOSUBCODE05='$Code5' AND 
              STOCKTRANSACTION.DECOSUBCODE06='$Code6' AND
              STOCKTRANSACTION.DECOSUBCODE07='$Code7' AND 
              STOCKTRANSACTION.DECOSUBCODE08='$Code8'
              GROUP BY MRNHEADER.MRNDATE,MRNHEADER.CHALLANNO,MRNHEADER.PURCHASEORDERCODE,MRNDETAIL.ORDERLINE,
              MRNHEADER.MRNPREFIXCODE,MRNHEADER.PURCHASEORDERCOUNTERCODE,
              STOCKTRANSACTION.DECOSUBCODE01,STOCKTRANSACTION.DECOSUBCODE02,
              STOCKTRANSACTION.DECOSUBCODE03,STOCKTRANSACTION.DECOSUBCODE04,
              STOCKTRANSACTION.DECOSUBCODE05,STOCKTRANSACTION.DECOSUBCODE06,
              STOCKTRANSACTION.DECOSUBCODE07,STOCKTRANSACTION.DECOSUBCODE08,
              STOCKTRANSACTION.DECOSUBCODE09,STOCKTRANSACTION.DECOSUBCODE10,
              STOCKTRANSACTION.ITEMDESCRIPTION,STOCKTRANSACTION.LOTCODE,
              STOCKTRANSACTION.USERPRIMARYUOMCODE,
              STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE, STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
              ITXVIEWLAPMASUKBENANG.LEGALNAME1,
              ITXVIEWLAPMASUKBENANG.SUMMARIZEDDESCRIPTION,
              STOCKTRANSACTION.TRANSACTIONNUMBER ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=trim($rowdb21['PROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE']);
$itemc= trim($rowdb21['DECOSUBCODE01'])." ".trim($rowdb21['DECOSUBCODE02'])." "
        .trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04'])." "
        .trim($rowdb21['DECOSUBCODE05'])." ".trim($rowdb21['DECOSUBCODE06'])." "
        .trim($rowdb21['DECOSUBCODE07'])." ".trim($rowdb21['DECOSUBCODE08'])." "
        .trim($rowdb21['DECOSUBCODE09'])." ".trim($rowdb21['DECOSUBCODE10']);		
if (trim($rowdb21['PROVISIONALCOUNTERCODE']) =='I02M50') { $knitt = 'KNITTING ITTI- GREIGE'; } 
		$sqlDB22 = "SELECT COUNT(BASEPRIMARYQUANTITYUNIT) AS ROL,SUM(BASEPRIMARYQUANTITYUNIT) AS BERAT,BALANCE.LOTCODE  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
		DB2ADMIN.BALANCE  BALANCE ON BALANCE.ELEMENTSCODE =STOCKTRANSACTION.ITEMELEMENTCODE  
		WHERE STOCKTRANSACTION.LOGICALWAREHOUSECODE='M011' AND STOCKTRANSACTION.ORDERCODE='$rowdb21[PURCHASEORDERCODE]'
		AND STOCKTRANSACTION.ORDERLINE ='$rowdb21[ORDERLINE]' AND STOCKTRANSACTION.TRANSACTIONNUMBER='$rowdb21[TRANSACTIONNUMBER]' 
		AND STOCKTRANSACTION.LOTCODE='$rowdb21[LOTCODE]'
		GROUP BY BALANCE.LOTCODE ";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));	
		$rowdb22 = db2_fetch_assoc($stmt2);
		$sqlDB23 = " SELECT PRODUCT.LONGDESCRIPTION,PRODUCT.SHORTDESCRIPTION 
	   FROM DB2ADMIN.PRODUCT PRODUCT WHERE
       PRODUCT.ITEMTYPECODE='GYR' AND
	   PRODUCT.SUBCODE01='".trim($rowdb21['DECOSUBCODE01'])."' AND
       PRODUCT.SUBCODE02='".trim($rowdb21['DECOSUBCODE02'])."' AND
       PRODUCT.SUBCODE03='".trim($rowdb21['DECOSUBCODE03'])."' AND
	   PRODUCT.SUBCODE04='".trim($rowdb21['DECOSUBCODE04'])."' AND
       PRODUCT.SUBCODE05='".trim($rowdb21['DECOSUBCODE05'])."' AND
	   PRODUCT.SUBCODE06='".trim($rowdb21['DECOSUBCODE06'])."' AND
       PRODUCT.SUBCODE07='".trim($rowdb21['DECOSUBCODE07'])."' ";
	   $stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
	   $rowdb23 = db2_fetch_assoc($stmt3);
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['MRNDATE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['PURCHASEORDERCODE']."-".$rowdb21['ORDERLINE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['CHALLANNO']; ?></td>
      <td style="text-align: left"><span style="text-align: left">
        <?php 
        echo $rowdb21['LEGALNAME1']; 
        // echo "-";
        ?>
      </span></td>
      <td><?php echo $itemc;?></td> 
      <td style="text-align: left"><?php echo $rowdb23['LONGDESCRIPTION'].$rowdb23['SHORTDESCRIPTION'] ; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['COBA_JUMLAH']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['COBA_TOTAL']; ?></td>
      <td style="text-align: right"><?php if($rowdb22['ROL']!=""){echo $rowdb22['ROL'];}else{ echo"0";} ?></td>
      <td style="text-align: right"><?php if($rowdb22['BERAT']!=""){echo $rowdb22['BERAT'];}else{ echo"0.00";} ?></td>
      <td><a href="#" id="<?php echo trim($rowdb21['PURCHASEORDERCODE'])."-".trim($rowdb21['TRANSACTIONNUMBER'])."-".trim($rowdb21['MRNDATE'])."-".trim($rowdb21['ORDERLINE'])."-".trim($rowdb22['LOTCODE']); ?>" class="show_detail"><?php echo $rowdb21['TRANSACTIONNUMBER']; ?></a></td>
      <td><a href="#" class="btn btn-success btn-xs">Lihat Detail</a></td>
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