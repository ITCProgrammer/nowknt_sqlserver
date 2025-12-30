<?php
$Zone		= isset($_POST['zone']) ? $_POST['zone'] : '';
$Lokasi		= isset($_POST['lokasi']) ? $_POST['lokasi'] : '';
$Barcode	= substr($_POST['barcode'],-13);
?>

<?php 
$sqlDB21Cek = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, CREATIONDATETIME,BASEPRIMARYQUANTITYUNIT FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' OR b.ITEMTYPECODE='DYR') AND b.LOGICALWAREHOUSECODE IN ('M904','P501') AND b.ELEMENTSCODE='$Barcode' ";
$stmt1Cek   = db2_exec($conn1,$sqlDB21Cek, array('cursor'=>DB2_SCROLLABLE));
$rowdb21Cek = db2_fetch_assoc($stmt1Cek);
$cekZoneNOW = trim($rowdb21Cek['WHSLOCATIONWAREHOUSEZONECODE']);
$cekLokasiNOW = trim($rowdb21Cek['WAREHOUSELOCATIONCODE']);
$sqlCek0U=mysqli_query($con,"SELECT COUNT(*) as jml, id_upload FROM tbl_stokfull WHERE zone='$Zone' AND lokasi LIKE '$Lokasi%'");
$ck0U=mysqli_fetch_array($sqlCek0U);
$sqlCek0=mysqli_query($con,"SELECT * FROM tbl_stokfull WHERE zone='$Zone' AND lokasi LIKE '$Lokasi%' AND SN='$Barcode'");
$ck0=mysqli_fetch_array($sqlCek0);
$sqlCek1=mysqli_query($con,"SELECT COUNT(*) as jml FROM	tbl_stokfull WHERE status='ok' and zone='$Zone' AND lokasi LIKE '$Lokasi%'");
$ck1=mysqli_fetch_array($sqlCek1);
$sqlCek2=mysqli_query($con,"SELECT COUNT(*) as jml FROM	tbl_stokfull WHERE status='belum cek' and zone='$Zone' AND lokasi LIKE '$Lokasi%'");
$ck2=mysqli_fetch_array($sqlCek2);

if($_POST['cek']=="Cek" or $_POST['cari']=="Cari"){	
	$sqlCek=mysqli_query($con,"SELECT COUNT(*) as jml, zone, lokasi, cones, kg, lot, id_upload FROM tbl_stokfull WHERE SN='$Barcode'");
	$ck=mysqli_fetch_array($sqlCek);
	if($Zone=="" and $Lokasi==""){
		echo"<script>alert('Zone atau Lokasi belum dipilih');</script>";
	}else if(is_numeric(trim($Barcode))== true and $Barcode!="" and strlen($Barcode)==13 and (substr($Barcode,0,2)=="15" or substr($Barcode,0,2)=="16" or
														substr($Barcode,0,2)=="17" or substr($Barcode,0,2)=="18" or
														substr($Barcode,0,2)=="19" or substr($Barcode,0,2)=="20" or
														substr($Barcode,0,2)=="21" or substr($Barcode,0,2)=="22" or 
														substr($Barcode,0,2)=="23") ){
		echo"<script>alert('SN Legacy');</script>";
	}else if($Barcode!="" and strlen($Barcode)==13 and ($cekZoneNOW!=$Zone or $cekLokasiNOW!=$Lokasi) and $ck0U['id_upload']>0){
		
		$LokasiAsl=$ck['zone']."-".$ck['lokasi'];
		$sqlDataE=mysqli_query($con,"INSERT INTO tbl_stokloss SET 
		  lokasi='$Lokasi',
		  lokasi_asli='$LokasiAsl',
		  lot='$ck[lot]',
		  KG='$ck[kg]',
		  zone='$Zone',
		  SN='$Barcode',
		  tgl_masuk='$tglMasuk',
		  id_upload='$ck0U[id_upload]',
		  tgl_cek=now()");
		
		echo "<script>alert('Zone atau Lokasi Berbeda, NOW ($cekZoneNOW-$cekLokasiNOW)');</script>";
		
		
		
	}else if($Barcode!="" and strlen($Barcode)==13 and ($ck['zone']!=$Zone or $ck['lokasi']!=$Lokasi) and $ck0U['id_upload']>0 ){	
		$LokasiAsl=$ck['zone']."-".$ck['lokasi'];
		$sqlDataE=mysqli_query($con,"INSERT INTO tbl_stokloss SET 
		  lokasi='$Lokasi',
		  lokasi_asli='$LokasiAsl',
		  lot='$ck[lot]',
		  KG='$ck[kg]',
		  zone='$Zone',
		  SN='$Barcode',
		  tgl_masuk='$tglMasuk',
		  id_upload='$ck0U[id_upload]',
		  tgl_cek=now()");
		
		echo "<script>alert('SN lokasi Tidak sama dengan yg diupload ($LokasiAsl)');</script>";
		
	}else if($Barcode!="" and strlen($Barcode)==13 and (substr($Barcode,0,2)=="00" or substr($Barcode,0,3)=="000" or
														substr($Barcode,0,2)=="70" or substr($Barcode,0,2)=="80" or 
														substr($Barcode,0,2)=="90") and $ck['zone']==$Zone and $ck['lokasi']==$Lokasi ){
if($ck0['status']=="ok"){
	echo"<script>alert('SN Ok dan sudah pernah discan');</script>";
}
else if($ck['jml']>0){	
	$sqlData=mysqli_query($con,"UPDATE tbl_stokfull SET 
		  status='ok',
		  tgl_cek=now()
		  WHERE zone='$Zone' AND lokasi LIKE '$Lokasi%' AND SN='$Barcode'");
	$sqlCek1=mysqli_query($con,"SELECT COUNT(*) as jml FROM	tbl_stokfull WHERE status='ok' and zone='$Zone' AND lokasi='$Lokasi'");
	$ck1=mysqli_fetch_array($sqlCek1);
	$sqlCek2=mysqli_query($con,"SELECT COUNT(*) as jml FROM	tbl_stokfull WHERE status='belum cek' and zone='$Zone' AND lokasi='$Lokasi'");
	$ck2=mysqli_fetch_array($sqlCek2);		
}else{
	$sqlDB21 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, CREATIONDATETIME,BASEPRIMARYQUANTITYUNIT FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' OR b.ITEMTYPECODE='DYR') AND b.LOGICALWAREHOUSECODE IN ('M904','P501') AND b.ELEMENTSCODE='$Barcode' ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	$rowdb21 = db2_fetch_assoc($stmt1);
	$lokasiAsli=trim($rowdb21['WHSLOCATIONWAREHOUSEZONECODE'])."-".trim($rowdb21['WAREHOUSELOCATIONCODE']);
	$tglMasuk=substr($rowdb21['CREATIONDATETIME'],0,10);
	$KGnow=round($rowdb21['BASEPRIMARYQUANTITYUNIT'],2);	
	if($lokasiAsli!="-"){
		echo"<script>alert('Data Dus ini dilokasi $lokasiAsli');</script>";
		if( $Zone!="" and $Lokasi!=""){				  
			$Where= " AND sf.`zone`='$Zone' AND sf.`lokasi`='$Lokasi' " ; 
		}else{
			$Where= " AND sf.`zone`='$Zone' AND sf.`lokasi`='$Lokasi' " ;
		}
		$sql=mysqli_query($con," SELECT sf.* FROM tbl_stokfull sf
		LEFT JOIN tbl_upload tu ON tu.id=sf.id_upload  
		WHERE tu.status='Open' $Where ");
		$rowd=mysqli_fetch_array($sql);
		
	}else{
		echo"<script>alert('SN tidak OK');</script>";
		if( $Zone!="" and $Lokasi!=""){				  
			$Where= " AND sf.`zone`='$Zone' AND sf.`lokasi`='$Lokasi' " ; 
		}else{
			$Where= " AND sf.`zone`='$Zone' AND sf.`lokasi`='$Lokasi' " ;
		}
		$sql=mysqli_query($con," SELECT sf.* FROM tbl_stokfull sf
		LEFT JOIN tbl_upload tu ON tu.id=sf.id_upload  
		WHERE tu.status='Open' $Where ");
		$rowd=mysqli_fetch_array($sql);
		if($ck0U['id_upload']>0){
		$sqlDataE=mysqli_query($con,"INSERT INTO tbl_stokloss SET 
		  lokasi='$Lokasi',
		  lokasi_asli='$lokasiAsli',
		  KG='$KGnow',
		  zone='$Zone',
		  SN='$Barcode',
		  tgl_masuk='$tglMasuk',
		  id_upload='$ck0U[id_upload]',
		  tgl_cek=now()");
		}
	}
	$sqlCek1=mysqli_query($con,"SELECT COUNT(*) as jml, sf.id_upload FROM tbl_stokfull sf
	LEFT JOIN tbl_upload tu ON tu.id=sf.id_upload  
	WHERE tu.status='Open' AND SN='$Barcode'");
	$ck1=mysqli_fetch_array($sqlCek1);		
	if($ck1['jml']>0){	
	
	$sqlData1=mysqli_query($con,"UPDATE tbl_stokfull SET 
		  status='ok',
		  zone='$Zone',
		  lokasi='$Lokasi',
		  tgl_cek=now()
		  WHERE id_upload='$ck1[id_upload]' AND SN='$Barcode'");	
	}
}
	
	} else if($Barcode==""){
		//barcode masih kosong
	}
	else{
		echo"<script>alert('SN tidak ditemukan Atau SN Legacy');</script>";
//		$sqlDataE=mysqli_query($con,"INSERT INTO tbl_stokloss SET 
//		  lokasi='$Lokasi',
//		  lokasi_asli='$LokasiAsl',
//		  KG='$KGnow',
//		  zone='$Zone',
//		  SN='$Barcode',
//		  tgl_masuk='$tglMasuk',
//		  id_upload='$ck[id_upload]',
//		  tgl_cek=now()");
	}

}
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-default">
<!--
          <div class="card-header">
            <h3 class="card-title">Filter Data</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
-->
          <!-- /.card-header -->
          <div class="card-body">
             <div class="form-group row">
               <label for="zone" class="col-md-1">Zone</label>
				 <div class="input-group input-group-sm">
                 <select class="form-control select2bs4" style="width: 80%;" name="zone">
				   <option value="">Pilih</option>	 
					<?php $sqlZ=mysqli_query($con," SELECT * FROM tbl_zone order by nama ASC"); 
					  while($rZ=mysqli_fetch_array($sqlZ)){
					 ?>
                    <option value="<?php echo $rZ['nama'];?>" <?php if($rZ['nama']==$Zone){ echo "SELECTED"; }?>><?php echo $rZ['nama'];?></option>
                    <?php  } ?>
                  </select>	
				 <span class="input-group-append">
                   	  <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#DataZone"><i class="fa fa-plus"></i> </button>
               		  </span>
                	</div>	 
            </div>
				 <div class="form-group row">
                    <label for="lokasi" class="col-md-1">Location</label>
					<div class="input-group input-group-sm"> 
					<select class="form-control select2bs4" style="width: 80%;" name="lokasi">
                    <option value="">Pilih</option>	 
					<?php $sqlL=mysqli_query($con," SELECT * FROM tbl_lokasi WHERE zone='$Zone' order by nama ASC"); 
					  while($rL=mysqli_fetch_array($sqlL)){
					 ?>
                    <option value="<?php echo $rL['nama'];?>" <?php if($rL['nama']==$Lokasi){ echo "SELECTED"; }?>><?php echo $rL['nama'];?></option>
                    <?php  } ?>
                  </select>	
				  <span class="input-group-append">
                   	  <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#DataLokasi"><i class="fa fa-plus"></i> </button>
               		  </span>
                	</div> 		
                  </div> 
			  <button class="btn btn-info" type="submit" value="Cari" name="cari">Cari Data</button>
          </div>
		  
		  <!-- /.card-body -->
          
        </div> 
		<!--	</form>
		<form role="form" method="post" enctype="multipart/form-data" name="form2">-->  
		<div class="card card-default">
		  <div class="card-header"> 	
		   <strong style="font-size: 11px;">Stok OK Sesuai Tempat</strong> <small class='badge badge-success'> <?php echo $ck1['jml'];?> dus </small> <strong style="font-size: 11px;">Stok belum Cek</strong> <small class='badge badge-danger'> <?php echo $ck2['jml'];?> dus</small>
		  </div>	
          <!-- /.card-header -->
          <div class="card-body">
             <div class="form-group row">			   	 
               <label for="barcode" class="col-md-1">Barcode</label>               
               <input type="text" class="form-control"  name="barcode" placeholder="SN / Elements" id="barcode" on autofocus>			    
            </div>	
			  <button class="btn btn-primary" type="submit" name="cek" value="Cek">Check</button>
			  
          </div>
		  
		  <!-- /.card-body -->
          
        </div> 
			</form>
		  <div class="card">
              <div class="card-header">
                <h3 class="card-title">Stock</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
				 <strong style="font-size: 11px;">Stok OK Sesuai Tempat</strong> <small class='badge badge-success'> <?php echo $ck1['jml'];?> dus </small>
				 <strong style="font-size: 11px;">Stok belum Cek</strong> <small class='badge badge-danger'> <?php echo $ck2['jml'];?> dus </small> 
                <table id="example12" class="table table-sm table-bordered table-striped" style="font-size:13px; width: 100%">
                  <thead>
                  <tr>
                    <th style="text-align: center">SN</th>
					<th style="text-align: center">Cones</th> 
					<th style="text-align: center">Jenis Benang</th> 
                    <th style="text-align: center">Kg</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">Lokasi</th>
                    <th style="text-align: center">NOW</th>
                    <th style="text-align: center">Lot</th>
                    <th style="text-align: center">Ket</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	if( $Zone!="" and $Lokasi!=""){				  
	$Where= " Where `zone`='$Zone' AND `lokasi` LIKE '$Lokasi%' " ;
	}else{
		$Where= " Where `zone`='$Zone' AND `lokasi`LIKE '$Lokasi%' " ;
	}
	if($Shift!=""){
		$Shft=" AND a.shft='$Shift' ";
	}else{
		$Shft=" ";
	}				  
		$sql=mysqli_query($con," SELECT * FROM tbl_stokfull $Where ");
   $no=1;   
   $c=0;
    while($rowd=mysqli_fetch_array($sql)){
$sqlDB22 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, LOTCODE FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' or b.ITEMTYPECODE='DYR') AND b.ELEMENTSCODE='$rowd[SN]' ";
	$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	$rowdb22 = db2_fetch_assoc($stmt2);
	$lokasiBalance=trim($rowdb22['WHSLOCATIONWAREHOUSEZONECODE'])."-".trim($rowdb22['WAREHOUSELOCATIONCODE']);
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $rowd['SN']; ?></td>
      <td style="text-align: right"><?php echo $rowd['cones']; ?></td>
	  <td style="text-align: center"><?php echo $rowd['jenis_benang']; ?></td>		  
      <td style="text-align: right"><?php echo $rowd['KG']; ?></td>
      <td style="text-align: center"><small class='badge <?php if($rowd['status']=="ok"){ echo"badge-success";}else if($rowd['status']=="belum cek"){ echo"badge-danger";}?>'> <?php echo $rowd['status']; ?></small></td>
      <td style="text-align: center"><?php echo $rowd['zone']."-".$rowd['lokasi']; ?></td>
      <td style="text-align: center"><?php echo $lokasiBalance; ?></td>
      <td style="text-align: center"><?php echo $rowdb22['LOTCODE']; ?></td>
      <td style="text-align: center"><small class='badge <?php if($lokasiBalance!="-"){ echo"badge-success";}else if($lokasiBalance=="-"){ echo"badge-danger";}?>'> <?php if($lokasiBalance!="-"){ echo "masih ada"; }else if($lokasiBalance=="-"){ echo "sudah keluar"; } ?></small></td>
      </tr>				  
					  <?php 
	 
	 $no++;} ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
        </div>  
<div class="card">
              <div class="card-header">
                <h3 class="card-title">ReCheck Stock </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">SN</th>
                    <th style="text-align: center">KG</th>
                    <th style="text-align: center">Lokasi Scan</th>
                    <th style="text-align: center">Lokasi Upload</th>
                    <th style="text-align: center">Lokasi Balance</th>
                    <th style="text-align: center">Tgl Masuk</th>
                    <th style="text-align: center">Lot</th>
                    <th style="text-align: center">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	if( $Zone!="" and $Lokasi!=""){				  
	$Where= " Where `zone`='$Zone' AND `lokasi` LIKE '$Lokasi%' " ;
	}else{
		$Where= " Where `zone`='$Zone' AND `lokasi` LIKE '$Lokasi%' " ;
	}
					  
		$sql1=mysqli_query($con," SELECT *, count(SN) as jmlscn FROM tbl_stokloss $Where  group by SN");
   $no=1;   
   $c=0;
    while($rowd1=mysqli_fetch_array($sql1)){
	if(strlen($rowd1['SN'])!="13"){	
	$ketSN= "jumlah Karakter di SN tidak Sesuai";}else{$ketSN= "";}
	if($rowd1['jmlscn']>1){
	$ketSCN= "Jumlah Scan ".$rowd1['jmlscn']." kali";	
	}else{ $ketSCN= "";}	
	if($rowd1['tgl_masuk']=="0000-00-00" or $rowd1['tgl_masuk']==""){
			$tglmsk="";
	}else{
			$tglmsk=$rowd1['tgl_masuk']; }
	$sqlDB22 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, LOTCODE FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' or b.ITEMTYPECODE='DYR') AND b.ELEMENTSCODE='$rowd1[SN]' ";
	$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	$rowdb22 = db2_fetch_assoc($stmt2);
	$lokasiBalance=trim($rowdb22['WHSLOCATIONWAREHOUSEZONECODE'])."-".trim($rowdb22['WAREHOUSELOCATIONCODE']);	
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $rowd1['SN']; ?></td>
      <td style="text-align: center"><?php echo $rowd1['KG']; ?></td>
      <td style="text-align: center"><?php echo $rowd1['zone']."-".$rowd1['lokasi']; ?></td>
      <td style="text-align: center"><?php echo $rowd1['lokasi_asli']; ?></td>
      <td style="text-align: center"><?php echo $lokasiBalance; ?></td>
      <td style="text-align: center"><?php echo $tglmsk; ?></td>
      <td style="text-align: center"><?php echo $rowdb22['LOTCODE']; ?></td>
      <td style="text-align: center"><small class='badge <?php if($rowd1['status']=="tidak ok"){ echo"badge-warning";}?>' ><i class='fas fa-exclamation-triangle text-default blink_me'></i> <?php echo $rowd1['status']; ?></small> <?php echo $ketSN.", ".$ketSCN; ?> </td>
      </tr>				  
					  <?php 
	 
	 $no++;} ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>      
</div><!-- /.container-fluid -->
    <!-- /.content -->

<div class="modal fade" id="DataZone">
          <div class="modal-dialog">
			  <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">
            <div class="modal-content">            
              <div class="modal-header">
                <h4 class="modal-title">Input Data Zone</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <span aria-hidden="true">&times;</span>
              </div>
              <div class="modal-body">
				  
                  <input type="hidden" id="id" name="id">
                  <div class="form-group">
                  <label for="zone1" class="col-md-3 control-label">Zone</label>
                  <div class="col-md-12">
                  <input type="text" class="form-control" id="zone1" name="zone1" maxlength="3" required>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>				  
                  	  
              </div>
              <div class="modal-footer justify-content-between">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  <input type="submit" value="Save changes" name="simpan_zone" class="btn btn-primary" >
			  </div>	  
            </div>
        </form>				
            </div>
            <!-- /.modal-content -->
  </div>
<div class="modal fade" id="DataLokasi">
          <div class="modal-dialog">
			  <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">
            <div class="modal-content">            
              <div class="modal-header">
                <h4 class="modal-title">Input Data Lokasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <span aria-hidden="true">&times;</span>
              </div>
              <div class="modal-body">
				  
                  <input type="hidden" id="id" name="id">
                  <div class="form-group">
                  <label for="zone" class="col-md-3 control-label">Zone</label>
                  <div class="col-md-12">                 
			      <select class="form-control select2bs4" name="zone2" required>
				   <option value="">Pilih</option>	 
					<?php $sqlZ=mysqli_query($con," SELECT * FROM tbl_zone order by nama ASC"); 
					  while($rZ=mysqli_fetch_array($sqlZ)){
					 ?>
                    <option value="<?php echo $rZ['nama'];?>"><?php echo $rZ['nama'];?></option>
                    <?php  } ?>
                  </select>			  
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
				  <div class="form-group">
                  <label for="lokasi1" class="col-md-3 control-label">Lokasi</label>
                  <div class="col-md-12">
                  <input type="text" class="form-control" id="lokasi1" name="lokasi1" maxlength="10" required>
                  <span class="help-block with-errors"></span>
                  </div>
                  </div>
                  	  
              </div>
              <div class="modal-footer justify-content-between">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  <input type="submit" value="Save changes" name="simpan_lokasi" class="btn btn-primary" >
			  </div>	  
            </div>
        </form>				
            </div>
            <!-- /.modal-content -->
  </div>
<?php 
if($_POST['simpan_zone']=="Save changes"){
	$zone1=mysqli_real_escape_string($con,strtoupper($_POST['zone1']));
	$sqlData1=mysqli_query($con,"INSERT INTO tbl_zone SET 
		  nama='$zone1'");
	if($sqlData1){	
		echo "<script>window.location='CheckStock';</script>";
	
		}
}
if($_POST['simpan_lokasi']=="Save changes"){
	$zone2=mysqli_real_escape_string($con,strtoupper($_POST['zone2']));
	$lokasi2=mysqli_real_escape_string($con,strtoupper($_POST['lokasi1']));
	$sqlData1=mysqli_query($con,"INSERT INTO tbl_lokasi SET 
		  nama='$lokasi2',
		  zone='$zone2'");
	if($sqlData1){	
		echo "<script>window.location='CheckStock';</script>";
	
		}
}
?>
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