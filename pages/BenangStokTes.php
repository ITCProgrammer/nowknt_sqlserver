<?php
$Lot	= isset($_POST['lot']) ? $_POST['lot'] : '';
$Knt	= isset($_POST['knt']) ? $_POST['knt'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1"> 
		<div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Filter Data Benang Stok</h3>

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
               <label for="lot" class="col-md-1">Lot</label>
               <div class="col-md-2">  
                    <input name="lot" value="<?php echo $Lot;?>" type="text" class="form-control form-control-sm" id="Lot" >
			   </div>	
            </div>			  
			<div class="form-group row">
               <label for="knt" class="col-md-1">Knitting</label>
               <div class="col-md-2">  
                 <select name="knt" class="form-control form-control-sm" id="knt">
				   <option value="">Pilih</option>
				   <option value="ALL" <?php if($Knt=="ALL"){echo "SELECTED";} ?>>ALL</option>	 
				   <option value="P501" <?php if($Knt=="P501"){echo "SELECTED";} ?>>LT1</option>
				   <option value="M904" <?php if($Knt=="M904"){echo "SELECTED";} ?>>LT2</option>
				   </select>
			   </div>	
            </div>	 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>	
<?php if($Knt!=""){ ?>			
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Stok Benang</h3>				 
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
                    <th valign="middle" style="text-align: center">Tipe</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Elements</th>
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
	if($Knt=="P501"){
		$Wknt=" BALANCE.LOGICALWAREHOUSECODE='P501' AND ";
	}else if($Knt=="M904"){
		$Wknt=" BALANCE.LOGICALWAREHOUSECODE='M904' AND ";
	}else{
		$Wknt=" (BALANCE.LOGICALWAREHOUSECODE='P501' OR BALANCE.LOGICALWAREHOUSECODE='M904') AND  ";
	}	
	if($Lot!=""){
		$Wlot = " BALANCE.LOTCODE LIKE '$Lot%' AND ";
	}else{
		$Wlot = "";
	}
	$sqlDB21 = " SELECT COUNT(BASEPRIMARYQUANTITYUNIT) AS QTY_ROL, SUM(BASEPRIMARYQUANTITYUNIT) AS QTY_KG ,
	SUM(BASESECONDARYQUANTITYUNIT) AS QTY_CONES,WHSLOCATIONWAREHOUSEZONECODE,WAREHOUSELOCATIONCODE,LOGICALWAREHOUSECODE,ELEMENTSCODE,BALANCE.ITEMTYPECODE 
		FROM DB2ADMIN.BALANCE BALANCE  
		WHERE $Wknt $Wlot (BALANCE.ITEMTYPECODE='GYR' OR BALANCE.ITEMTYPECODE='DYR')
		GROUP BY ELEMENTSCODE,WHSLOCATIONWAREHOUSEZONECODE,WAREHOUSELOCATIONCODE,LOGICALWAREHOUSECODE,BALANCE.ITEMTYPECODE
		"; 
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}						  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=$rowdb21['INTDOCUMENTPROVISIONALCODE']."-".$rowdb21['ORDERLINE'];
if (trim($rowdb21['LOGICALWAREHOUSECODE']) =="M904") { $knitt = "LT2"; }
else if(trim($rowdb21['LOGICALWAREHOUSECODE']) =="P501"){ $knitt = "LT1"; }

$sqlDB22 = "SELECT 
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
WHERE (NOT INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE IS NULL) AND (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904' or STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501') AND
STOCKTRANSACTION.ITEMELEMENTCODE ='$rowdb21[ELEMENTSCODE]' AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
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
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION";
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);	
$bon=$rowdb22['INTDOCUMENTPROVISIONALCODE']."-".$rowdb22['ORDERLINE'];	
$kdbenang=$rowdb22['SUBCODE01']." ".$rowdb22['SUBCODE02']." ".$rowdb22['SUBCODE03']." ".$rowdb22['SUBCODE04']." ".$rowdb22['SUBCODE05']." ".$rowdb22['SUBCODE06']." ".$rowdb22['SUBCODE07']." ".$rowdb22['SUBCODE08'];
		
$sqlDB23 = " SELECT SUPPLIERCODE  FROM LOT WHERE CODE='$rowdb22[LOTCODE]' AND NOT SUPPLIERCODE IS NULL ";
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);	
		
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb22['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb22['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb22['ORDERLINE'])."-".trim($rowdb22['TRANSACTIONDATE']); ?>" class="show_detail"><?php echo $bon; ?></a></td>
      <td style="text-align: center"><?php echo $knitt; ?></td>
      <td style="text-align: center"><?php echo $rowdb22['EXTERNALREFERENCE']; ?></td>
      <td><?php echo $rowdb21['ITEMTYPECODE']; ?></td>
      <td><?php echo $kdbenang; ?></td> 
      <td style="text-align: left"><?php echo $rowdb22['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb23['SUPPLIERCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb22['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['ELEMENTSCODE']; ?></td>
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
	    <td>&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right"><strong>Total</strong></td>
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
<?php } ?>	
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
<?php 
if($_POST['mutasikain']=="MutasiKain"){
	
function mutasiurut(){
include "koneksi.php";		
$format = "20".date("ymd");
$sql=mysqli_query($con,"SELECT no_mutasi FROM tbl_mutasi_kain WHERE substr(no_mutasi,1,8) like '%".$format."%' ORDER BY no_mutasi DESC LIMIT 1 ") or die (mysql_error());
$d=mysqli_num_rows($sql);
if($d>0){
$r=mysqli_fetch_array($sql);
$d=$r['no_mutasi'];
$str=substr($d,8,2);
$Urut = (int)$str;
}else{
$Urut = 0;
}
$Urut = $Urut + 1;
$Nol="";
$nilai=2-strlen($Urut);
for ($i=1;$i<=$nilai;$i++){
$Nol= $Nol."0";
}
$tidbr =$format.$Nol.$Urut;
return $tidbr;
}
$nomid=mutasiurut();	

$sql1=mysqli_query($con,"SELECT *,count(b.transid) as jmlrol,a.transid as kdtrans FROM tbl_mutasi_kain a 
LEFT JOIN tbl_prodemand b ON a.transid=b.transid 
WHERE isnull(a.no_mutasi) AND date_format(a.tgl_buat ,'%Y-%m-%d')='$Awal' AND a.gshift='$Gshift' 
GROUP BY a.transid");
$n1=1;
$noceklist1=1;	
while($r1=mysqli_fetch_array($sql1)){	
	if($_POST['cek'][$n1]!='') 
		{
		$transid1 = $_POST['cek'][$n1];
		mysqli_query($con,"UPDATE tbl_mutasi_kain SET
		no_mutasi='$nomid',
		tgl_mutasi=now()
		WHERE transid='$transid1'
		");
		}else{
			$noceklist1++;
	}
	$n1++;
	}
if($noceklist1==$n1){
	echo "<script>
  	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: false,
      timer: 2000
    });
	Toast.fire({
        icon: 'info',
        title: 'Data tidak ada yang di Ceklist',
		
      })
  });
  
</script>";	
}else{	
echo "<script>
	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: true,
      timer: 6000
    });
	Toast.fire({
  title: 'Data telah di Mutasi',
  text: 'klik OK untuk Cetak Bukti Mutasi',
  icon: 'success',  
}).then((result) => {
  if (result.isConfirmed) {
    	window.open('pages/cetak/cetak_mutasi_ulang.php?mutasi=$nomid', '_blank');
  }
})
  });
	</script>";
	
/*echo "<script>
	Swal.fire({
  title: 'Data telah di Mutasi',
  text: 'klik OK untuk Cetak Bukti Mutasi',
  icon: 'success',  
}).then((result) => {
  if (result.isConfirmed) {
    	window.location='Mutasi';
  }
});
	</script>";	*/
}
}
?>