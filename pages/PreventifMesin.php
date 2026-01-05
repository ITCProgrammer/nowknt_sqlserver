<style>
  .allow_edit:hover{
    cursor: pointer;
    color: green !important:
  }
</style>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Jadwal Preventif Mesin</h3>
				<a href="pages/cetak/cetak-jadwal-html.php" class="btn bg-blue float-right" target="_blank">Cetak Jadwal</a>  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example12" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Detail</th>
                    <th valign="middle" style="text-align: center">No Mesin</th>
                    <th valign="middle" style="text-align: center">Mesin</th>
                    <th valign="middle" style="text-align: center">Batas Produksi</th>
                    <th valign="middle" style="text-align: center">Produksi</th>
                    <!-- <th valign="middle" style="text-align: center">Total Produksi</th> -->
                    <th valign="middle" style="text-align: center">Tgl Servis Terakhir</th>
                    <th valign="middle" style="text-align: center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
$no=1;   
$c=0;
$sqlDB2=" SELECT USERGENERICGROUP.CODE AS KDMC,USERGENERICGROUP.LONGDESCRIPTION, 
USERGENERICGROUP.SHORTDESCRIPTION,USERGENERICGROUP.SEARCHDESCRIPTION FROM DB2ADMIN.USERGENERICGROUP 
WHERE USERGENERICGROUP.USERGENERICGROUPTYPECODE = 'MCK' AND 
	USERGENERICGROUP.USERGENGROUPTYPECOMPANYCODE = '100' AND 
	USERGENERICGROUP.OWNINGCOMPANYCODE = '100' ";
					  
$no=1;   
$c=0;
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
while($rowdb2 = db2_fetch_assoc($stmt)){	
	$sql=mysqli_query($con," SELECT
  a.id,
	a.no_mesin,
	a.batas_produksi,b.tgl_servis,b.tgl_buat,b.sts 
FROM
	tbl_mesin a
	LEFT JOIN (
SELECT
no_mesin,tgl_servis,tgl_buat,sts 
FROM
	( SELECT * FROM tbl_jadwal  ORDER BY id DESC ) a 
GROUP BY
	a.no_mesin 
	) b ON a.no_mesin = b.no_mesin
	WHERE a.no_mesin='$rowdb2[SEARCHDESCRIPTION]'
	GROUP BY a.no_mesin ");
  $r=mysqli_fetch_array($sql);
 $sql1=mysqli_query($con," SELECT a.tgl_servis,b.kg_awal,b.sts FROM tbl_jadwal a
LEFT JOIN 
(
SELECT sum(kg_awal) as kg_awal,sts,no_mesin  FROM tbl_jadwal  WHERE no_mesin='".$rowdb2['SEARCHDESCRIPTION']."' GROUP BY no_mesin
) b ON b.no_mesin=a.no_mesin 
WHERE a.no_mesin='".$rowdb2['SEARCHDESCRIPTION']."' ORDER BY a.tgl_servis DESC LIMIT 1 ");
     $r1=mysqli_fetch_array($sql1);	

 // awal
 $totalP=0;
 $sqlP=mysqli_query($con," SELECT
	a.no_mesin, a.kd_dtex, a.batas_produksi, sum(b.berat_awal) as `KGS`
FROM
	tbl_mesin a
LEFT JOIN tbl_inspeksi_detail b ON a.no_mesin=b.no_mc
WHERE a.no_mesin='".$rowdb2['SEARCHDESCRIPTION']."'
GROUP BY
	a.no_mesin
ORDER BY
	a.no_mesin ASC ");
     $rP=mysqli_fetch_array($sqlP);			
$sqlDB2P=" 
SELECT SUM(WEIGHTNET) AS KG FROM ELEMENTSINSPECTION 
 LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE 
 LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo' 
 WHERE ELEMENTITEMTYPECODE='KGF' AND ADSTORAGE.VALUESTRING='".$rowdb2['KDMC']."'
";
$stmtP   = db2_exec($conn1,$sqlDB2P, array('cursor'=>DB2_SCROLLABLE));
$rowdb2P = db2_fetch_assoc($stmtP);	
	
		
	 $totalP=round((round($rP['KGS'],2)+round($rowdb2P['KG'],2))-round($r1['kg_awal'],2), '2');    
?>
	  <tr data-id_data="<?= $r['id']; ?>" data-batas_produksi="<?= $r['batas_produksi']; ?>" >
	  <td height="21" style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><a href="#" class="mesin_detail" id="<?php echo $rowdb2['KDMC']; ?>"><small class='badge bg-blue'>Lihat</small></a> <a href="#" class="log_detail" id="<?php echo $rowdb2['SEARCHDESCRIPTION']; ?>"><small class='badge bg-green'>Cek</small></a></td>
	  <td style="text-align: center"><label class='badge bg-yellow allow_edit' style="font-size: 12px;"><?php echo $rowdb2['SEARCHDESCRIPTION']; ?></label></td>
	  <td style="text-align: center"><?php echo $rowdb2['KDMC']; ?></td>
	  <td style="text-align: center" id="<?="batas_produksi".$r['id']."" ;?>"><?php echo $r['batas_produksi'];?></td>
	  <td style="text-align: center"><?php echo $totalP; ?></td>
    <!-- <td style="text-align: center"><?php echo $r1['kg_awal']; ?></td> -->
	  <td style="text-align: center"><?php echo $r1['tgl_servis']; ?></td>
      <td style="text-align: center"><a href="Pemeriksaan-<?php echo $rowdb2['SEARCHDESCRIPTION']; ?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> </a></td>
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
<div id="DetailProduksi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
      </div>
<div id="MesinLog" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
      
<div class="modal fade" id="EditDataMesin" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
        <h4 class="modal-title" style="text-align:center;">Detail Mesin</h4>
        <button type="button" class="close"  data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_data" val="0">
        <div class="row">
          <div class="form-group">
            <div class="col-sm-12">
              <label for="batas_produksi_edit">Edit Batas Produksi</label>
              <input type="text" class="form-control" id="batas_produksi_edit" autocomplete="off">
            </div>
          </div> 
				</div>
        </br>      
      <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
        <button type="button" class="btn btn-primary" id="update_detail_produksi">Update</button>
        <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>   
</div>   
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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var parent=false;
    $('.allow_edit').click(function(e) {  
        parent=$(this).parent().parent();
        $('#batas_produksi_edit').val(parent.data('batas_produksi')).trigger('change');
        $('#id_data').val(parent.data('id_data'));  
        $('#EditDataMesin').modal('show', {backdrop: 'static'});       
    });
    $('#update_detail_produksi').click(function(e) { 
        $.ajax({
            url: 'pages/ajax/ajax_preventif_mesin.php',
            type: 'POST',
            data: {
              status:"update_batas_produksi", 
              id_dt:$('#id_data').val(), 
              batas_produksi:$('#batas_produksi_edit').val(), 
            },
            success: function(response) {
                if(response.success){
                  parent.data("batas_produksi",$('#batas_produksi_edit').val()); 
                  $('#batas_produksi'+$('#id_data').val()).html($('#batas_produksi_edit').val());
                  $('#example12').DataTable().columns.adjust().draw();
                  $('#EditDataMesin').modal('hide');
                }else{
                    Swal.fire({
                      title: 'Error',
                      text: response.messages[0],
                      icon: 'error',
                      timer: 1000,
                      position : 'top-end',
                      showConfirmButton: false
                  });
                }
            },
            error: function() {
            }
      }); 
    });
  });
</script>
<?php 
if($_POST['mutasikain']=="MutasiKain"){
	
function mutasiurut(){
include "koneksi.php";		
$format = "20".date("ymd");
$sql=sqlsrv_query($con,"SELECT TOP 1 no_mutasi FROM dbknitt.tbl_mutasi_kain WHERE SUBSTRING(no_mutasi,1,8) LIKE '%".$format."%' ORDER BY no_mutasi DESC");
if($sql && $r=sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
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