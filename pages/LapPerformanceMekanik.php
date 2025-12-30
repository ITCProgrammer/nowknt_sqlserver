<?php
$Awal		= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir		= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Shift		= isset($_POST['shift']) ? $_POST['shift'] : '';
$QReason	= isset($_POST['q_reason']) ? $_POST['q_reason'] : '';
$DemandNo	= isset($_POST['demandno']) ? $_POST['demandno'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1"> 
		<div class="row">
		<div class="col-md-3">	
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Tanggal</h3>

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
               <label for="tgl_awal" class="col-md-6">Tgl Awal</label>
               <div class="col-md-6">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_awal" value="<?php echo $Awal;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" >
                 </div>
			   </div>	
            </div>
			 <div class="form-group row">
               <label for="tgl_akhir" class="col-md-6">Tgl Akhir</label>
               <div class="col-md-6">  
                 <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" >
                 </div>
			   </div>	
            </div>
			<!--<div class="form-group row">
               <label for="shift" class="col-md-6">Demand No</label>
               <div class="col-md-6">  
                 <input name="demandno" value="<?php echo $DemandNo;?>" type="text" class="form-control form-control-sm" id="demandno">
			   </div>	
            </div>-->   
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
		</div>	
		<div class="col-md-9">
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Poin Penilaian Kerja Mekanik</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example14" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
                  <thead>
                  <tr>
                    <th rowspan="2" valign="middle" style="text-align: center">No</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Jenis Pekerjaan</th>
                    <th colspan="2" valign="middle" style="text-align: center">Single</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Kapasitas</th>
                    <th colspan="2" valign="middle" style="text-align: center">Fleece</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Kapasitas</th>
                    <th colspan="2" valign="middle" style="text-align: center">Double</th>
                    <th valign="middle" style="text-align: center">&nbsp;</th>
                  </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Waktu</th>
                    <th valign="middle" style="text-align: center">Poin</th>
                    <th valign="middle" style="text-align: center">Waktu</th>
                    <th valign="middle" style="text-align: center">Poin</th>
                    <th valign="middle" style="text-align: center">Waktu</th>
                    <th valign="middle" style="text-align: center">Poin</th>
                    <th valign="middle" style="text-align: center">Kapasitas</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
$no=1;   
$c=0;			  
	$sql=mysqli_query($con,"SELECT * FROM tbl_pekerjaan_mekanik");	
    while($row = mysqli_fetch_array($sql)){ 
	$jamS=floor($row['waktu_single']/60);
	$menitS=$row['waktu_single']%60;
	if($menitS=="0"){
		$jS=$jamS." Jam ";
	}elseif($jamS=="0"){
		$jS=$menitS." Menit";
	}else{
		$jS=$jamS." Jam ".$menitS." Menit";
	}
	$jamF=floor($row['waktu_fleece']/60);
	$menitF=$row['waktu_fleece']%60;
	if($menitF=="0"){
		$jF=$jamF." Jam ";
	}elseif($jamF=="0"){
		$jF=$menitF." Menit";
	}else{
		$jF=$jamF." Jam ".$menitF." Menit";
	}
	$jamD=floor($row['waktu_double']/60);
	$menitD=$row['waktu_double']%60;
	if($menitD=="0"){
		$jD=$jamD." Jam ";
	}elseif($jamD=="0"){
		$jD=$menitD." Menit";
	}else{
		$jD=$jamD." Jam ".$menitD." Menit";
	}	
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: left"><?php echo $row['pekerjaan']; ?></td>
	  <td style="text-align: center"><?php echo $jS; ?></td>
	  <td style="text-align: center"><?php echo $row['poin_single']; ?></td>
	  <td style="text-align: center"><?php echo $row['kapasitas_single']." Orang"; ?></td>
	  <td style="text-align: center"><?php echo $jF; ?></td>
	  <td style="text-align: center"><?php echo $row['poin_fleece']; ?></td>
	  <td style="text-align: center"><?php echo $row['kapasitas_fleece']." Orang"; ?></td>
	  <td style="text-align: center"><?php echo $jD; ?></td>
	  <td style="text-align: center"><?php echo $row['poin_double']; ?></td>
	  <td style="text-align: center"><?php echo $row['kapasitas_double']." Orang"; ?></td>
	  </tr>				  
	<?php 
	 $no++; } ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
          </div>
		</div>	
		</div>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Laporan KPI Mekanik</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example12" class="table table-sm table-bordered table-striped" style="font-size: 11px; text-align: center;" width="100%">
                  <thead>
                  <tr>
                    <th rowspan="2" valign="middle" style="text-align: center">Tanggal</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Nama</th>
                    <th rowspan="2" valign="middle" style="text-align: center">MC</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Demand</th>
                    <th rowspan="2" valign="middle" style="text-align: center">No Art</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Benang</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Sts</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Kegiatan</th>
                    <th colspan="2" valign="middle" style="text-align: center">Jam</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Total</th>
                    <th rowspan="2" valign="middle" style="text-align: center">No KK</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Total Jam</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Status Kegiatan</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Poin Tambah</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Keterangan</th>
                    <th rowspan="2" valign="middle" style="text-align: center">% Poin</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Poin</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Mulai</th>
                    <th valign="middle" style="text-align: center">Selesai</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
$no=1;
$qry= mysqli_query($con,"SELECT * FROM tbl_kpi_mekanik WHERE tgl_setting BETWEEN '$Awal' AND '$Akhir' ");					  
	while($r=mysqli_fetch_array($qry)){
$sqlA	= mysqli_query($con,"SELECT * FROM tbl_pekerjaan_mekanik WHERE pekerjaan='".$r['kegiatan']."'");	
$rowA	= mysqli_fetch_array($sqlA);
		
if($r['status_pekerjaan']=="Selesai" or $r['status_pekerjaan']=="Pindah Mesin"){
	
	if($r['sts_d']=="Sample"){
	$wkt=3;
	$pn=3;
	}else{
	$wkt=0;
	$pn=0;
	}	
	if(substr($r['jns_b'],-1)=="X" or substr($r['jns_b'],-1)=="x"){
	$pnX=2;
	}else{
	$pnX=0;
	}
	
	if(substr($r['no_mesin'],0,2)=="DO" or substr($r['no_mesin'],0,2)=="RI"){
		$Slj	= floor(($r['total_waktu_pekerjaan']-($rowA['waktu_double']+(60*$wkt)))/60);
		if($Slj > 0){
		$poin1 	= $rowA['poin_double']-$Slj;			
		}else if($Slj<=0){
		$poin1 	= $rowA['poin_double'];	
		}
		if($poin1>0){
		$poin=$poin1;
		}else{
		$poin 	= 0;	
		}		
	}else if(substr($r['no_mesin'],0,2)=="SO" or substr($r['no_mesin'],0,2)=="ST"){
		$Slj	= floor(($r['total_waktu_pekerjaan']-($rowA['waktu_single']+(60*$wkt)))/60);
		if($Slj > 0){
		$poin1 	= $rowA['poin_single']-$Slj;			
		}else if($Slj<=0){
		$poin1 	= $rowA['poin_single'];	
		}
		if($poin1>0){
		$poin=$poin1;
		}else{
		$poin 	= 0;	
		}
	}else if(substr($r['no_mesin'],0,2)=="TF" or substr($r['no_mesin'],0,2)=="TT"){
		$Slj	= floor(($r['total_waktu_pekerjaan']-($rowA['waktu_fleece']+(60*$wkt)))/60);
		if($Slj > 0){
		$poin1 	= $rowA['poin_fleece']-$Slj;			
		}else if($Slj<=0){
		$poin1 	= $rowA['poin_fleece'];	
		}
		if($poin1>0){
		$poin=$poin1;
		}else{
		$poin 	= 0;	
		}
	}		
	
}else{
	$poin = 0;
	$pnX=0;
	$wkt=0;
	$pn=0;
}		


$jTot=floor($r['total_waktu']/60);
$mTot=$r['total_waktu']%60;
	if($mTot=="0"){
		$jT=$jTot." Jam ";
	}elseif($jTot=="0"){
		$jT=$mTot." Menit";
	}else{
		$jT=$jTot." Jam ".$mTot." Menit";
	}
$tjTot=floor($r['total_waktu_pekerjaan']/60);
$tmTot=$r['total_waktu_pekerjaan']%60;
	if($tmTot=="0"){
		$tjT=$tjTot." Jam ";
	}elseif($tjTot=="0"){
		$tjT=$tmTot." Menit";
	}else{
		$tjT=$tjTot." Jam ".$tmTot." Menit";
	}

?>
	  <tr>
	  <td style="text-align: center"><?php echo $r['tgl_setting']; ?></td>
	  <td style="text-align: left"><?php echo $r['nama_mekanik']; ?></td>
	  <td style="text-align: center"><?php echo $r['no_mesin']; ?></td>
	  <td style="text-align: center"><?php echo $r['no_demand']; ?></td>
	  <td style="text-align: center"><?php echo $r['no_item']; ?></td>
	  <td style="text-align: center"><?php echo $r['jns_b']; ?></td>
	  <td style="text-align: center"><?php echo $r['sts_d']; ?></td>
	  <td style="text-align: center"><?php echo $r['kegiatan']; ?></td>
	  <td style="text-align: center"><?php echo $r['jam_mulai']; ?></td>
	  <td style="text-align: center"><?php echo $r['jam_selesai']; ?></td>
	  <td style="text-align: center"><?php echo $jT; ?></td>
	  <td style="text-align: center"><?php echo $r['no_kerja']; ?></td>
	  <td style="text-align: center"><?php echo $tjT; ?></td>
	  <td style="text-align: center"><?php echo $r['status_pekerjaan']; ?></td>
	  <td style="text-align: center"><?php echo $r['poin_tambah']; ?></td>
	  <td style="text-align: center"><?php echo $r['ket']; ?></td>
	  <td style="text-align: center"><?php echo $r['point_persen']; ?></td>
	  <td style="text-align: center"><?php echo number_format(round((($poin+$pn+$pnX+$r['poin_tambah'])*$r['point_persen'])/100,2),2); ?></td>
	  </tr>	  				  
	<?php 
	 $no++; 
	}
		 ?>
				  </tbody>
      <tfoot>
		  <tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    </tr>
	</tfoot>            
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
	</form>		
      </div><!-- /.container-fluid -->
<div id="DetailTurunanShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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