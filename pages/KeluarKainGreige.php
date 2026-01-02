<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Tgl Pengiriman Kain Greige</h3>

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
               <label for="tgl_awal" class="col-md-1">Tgl Awal</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_awal" value="<?php echo $Awal;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>	
            </div>
			 <div class="form-group row">
               <label for="tgl_akhir" class="col-md-1">Tgl Akhir</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>	
            </div>	 
			  
          </div>
			<div class="card-footer">
			<button class="btn btn-info" type="submit">Cari Data</button>
			</div>
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Pengiriman Kain Greige</h3>
				<a href="pages/cetak/cetaklapmutasigreige.php?awal=<?php echo $Awal;?>&akhir=<?php echo $Akhir;?>" class="btn bg-blue float-right" target="_blank">to Print</a>  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tgl Kirim</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">Tgl Terima</th>
                    <th valign="middle" style="text-align: center">Tujuan</th>
                    <th valign="middle" style="text-align: center">Prod. Order</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Project</th>
                    <th valign="middle" style="text-align: center">Jenis Kain</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Mesin</th>
                    <th valign="middle" style="text-align: center">No Mesin</th>
                    <th valign="middle" style="text-align: center">Userid</th>
                    <th valign="middle" style="text-align: center">Kode</th>
                    <th valign="middle" style="text-align: center">Petugas</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT a.VALUESTRING AS MESIN,i.PROVISIONALCOUNTERCODE,i2.INTERNALREFERENCEDATE,i2.INTDOCUMENTPROVISIONALCODE , i2.ORDERLINE,i2.EXTERNALREFERENCE,  s.PROJECTCODE,
i.DESTINATIONWAREHOUSECODE, i2.SUBCODE01, i2.SUBCODE02, i2.SUBCODE03, i2.SUBCODE04,f.SUMMARIZEDDESCRIPTION,
SUM(s.BASEPRIMARYQUANTITY) AS KG, COUNT(s.ITEMELEMENTCODE) AS ROL, i2.RECEIVINGDATE, s.LOGICALWAREHOUSECODE,
s.WHSLOCATIONWAREHOUSEZONECODE, s.LOTCODE, s.CREATIONUSER  
FROM INTERNALDOCUMENT i 
LEFT OUTER JOIN INTERNALDOCUMENTLINE i2 ON i.PROVISIONALCODE = i2.INTDOCUMENTPROVISIONALCODE 
LEFT OUTER JOIN STOCKTRANSACTION s ON i2.INTDOCUMENTPROVISIONALCODE =s.ORDERCODE AND i2.ORDERLINE =s.ORDERLINE 
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE=s.LOTCODE
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.NAMENAME ='MachineNo'
LEFT OUTER JOIN FULLITEMKEYDECODER f ON s.FULLITEMIDENTIFIER = f.IDENTIFIER
WHERE s.PHYSICALWAREHOUSECODE ='M50' AND i2.INTERNALREFERENCEDATE BETWEEN '$Awal' AND '$Akhir'
GROUP BY 
a.VALUESTRING,i.PROVISIONALCOUNTERCODE,i2.INTERNALREFERENCEDATE,i2.INTDOCUMENTPROVISIONALCODE ,
i2.ORDERLINE, s.PROJECTCODE,
i.DESTINATIONWAREHOUSECODE, i2.SUBCODE01,
i2.SUBCODE02, i2.SUBCODE03,i2.EXTERNALREFERENCE,
i2.SUBCODE04,f.SUMMARIZEDDESCRIPTION, i2.RECEIVINGDATE,
s.LOGICALWAREHOUSECODE, s.WHSLOCATIONWAREHOUSEZONECODE, s.LOTCODE, s.CREATIONUSER  ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE']);
$itemc=trim($rowdb21['SUBCODE02'])."".trim($rowdb21['SUBCODE03'])." ".trim($rowdb21['SUBCODE04']);
$sqlKt = sqlsrv_query($con, "SELECT TOP 1 no_mesin FROM dbknitt.tbl_mesin WHERE kd_dtex = ?", [trim($rowdb21['MESIN'])]);
$rk = $sqlKt ? sqlsrv_fetch_array($sqlKt, SQLSRV_FETCH_ASSOC) : [];		
if (trim($rowdb21['PROVISIONALCOUNTERCODE']) =='I02M50') { $knitt = 'GREIGE-ITTI'; } 
$sqlDB2KPI = " SELECT
	a.VALUESTRING AS KD,
	b.VALUESTRING AS NAMA
FROM
	INTERNALDOCUMENTLINE i  
LEFT OUTER JOIN ADSTORAGE a ON
	a.UNIQUEID = i.ABSUNIQUEID 
	AND a.NAMENAME = 'KdPengiriman'
LEFT OUTER JOIN ADSTORAGE b ON
	b.UNIQUEID = i.ABSUNIQUEID 
	AND b.NAMENAME = 'NamaPetugas'	
WHERE
	i.INTDOCUMENTPROVISIONALCODE ='".$rowdb21['INTDOCUMENTPROVISIONALCODE']."'
	AND i.ORDERLINE ='".$rowdb21['ORDERLINE']."'
	AND NOT a.VALUESTRING IS NULL
	AND NOT b.VALUESTRING IS NULL ";
$stmt2KPI   = db2_exec($conn1,$sqlDB2KPI, array('cursor'=>DB2_SCROLLABLE));
$rKPI = db2_fetch_assoc($stmt2KPI);					  
					  
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['INTERNALREFERENCEDATE']; ?></td>
      <td style="text-align: center"><?php echo $bon; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['RECEIVINGDATE']; ?></td>
      <td style="text-align: center"><?php echo $knitt; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td><?php echo $itemc;?></td> 
      <td style="text-align: left"><?php echo $rowdb21['PROJECTCODE']; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['ROL']; ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['KG'],2),2); ?></td>
      <td><span style="text-align: right"><?php echo $rowdb21['MESIN']; ?></span></td>
      <td><?php  echo $rk['no_mesin']; ?></td>
      <td><?php echo $rowdb21['CREATIONUSER']; ?></td>
      <td><?php echo $rKPI['KD']; ?></td>
      <td><?php echo $rKPI['NAMA']; ?></td>
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
$stmt = sqlsrv_query($con, "SELECT TOP 1 no_mutasi FROM dbknitt.tbl_mutasi_kain WHERE SUBSTRING(no_mutasi,1,8) LIKE ? ORDER BY no_mutasi DESC", ['%'.$format.'%']);
$row = $stmt ? sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) : null;
if($row){
    $str=substr($row['no_mutasi'],8,2);
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

$sql1=sqlsrv_query($con,"SELECT a.transid as kdtrans, COUNT(b.transid) as jmlrol FROM dbknitt.tbl_mutasi_kain a 
LEFT JOIN dbknitt.tbl_prodemand b ON a.transid=b.transid 
WHERE a.no_mutasi IS NULL AND CONVERT(date,a.tgl_buat)=? AND a.gshift=? 
GROUP BY a.transid", [$Awal, $Gshift]);
$n1=1;
$noceklist1=1;	
while($r1 = $sql1 ? sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC) : null){	
	if($_POST['cek'][$n1]!='') 
		{
		$transid1 = $_POST['cek'][$n1];
		sqlsrv_query($con,"UPDATE dbknitt.tbl_mutasi_kain SET
		no_mutasi=?,
		tgl_mutasi=GETDATE()
		WHERE transid=?", [$nomid, $transid1]);
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
