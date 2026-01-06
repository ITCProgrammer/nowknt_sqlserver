<?php
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$modal=sqlsrv_query($con,"SELECT * FROM dbknitt.tbl_jadwal WHERE id='$modal_id' ");
while($r=sqlsrv_fetch_array($modal)){
?>
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="UbahPeriksa" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title">Edit Pemeriksaan Mesin</h5>
              	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="modal-body">
                  <input type="hidden" id="id" name="id" value="<?php echo $r['id'];?>">
				  <input type="hidden" id="no_mesin" name="no_mesin" value="<?php echo $r['no_mesin'];?>">
				<div class="form-group">
                  <label for="order" class="col-sm-3 control-label">Kategori</label>
                  <div class="col-sm-3">
                   <select name="kategori" class="form-control" id="kategori" required>
					   <option value="Ringan" <?php if($r['kategori']=="Ringan"){ echo "SELECTED"; } ?>>Ringan</option>
					   <option value="Over Houl" <?php if($r['kategori']=="Over Houl"){ echo "SELECTED"; } ?>>Over Houl</option>
				   </select>	   
                  </div>
                </div>  
                <div class="form-group">
                  <label for="order" class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-3">
                   <select name="sts" class="form-control" id="sts" required>
					   <option value="Berkala" <?php if($r['sts']=="Berkala"){ echo "SELECTED"; } ?>>Berkala</option>
					   <option value="Hold" <?php if($r['sts']=="Hold"){ echo "SELECTED"; } ?>>Hold</option>
					   <option value="Trouble" <?php if($r['sts']=="Trouble"){ echo "SELECTED"; } ?>>Trouble</option>
					   <option value="Ganti Konstruksi" <?php if($r['sts']=="Ganti Konstruksi"){ echo "SELECTED"; } ?>>Ganti Konstruksi</option>
				   </select>	   
                  </div>
                </div>
                <div class="form-group">
                  <label for="mekanik" class="col-sm-3 control-label">Mekanik 1</label>
                  <div class="col-sm-6">
                     <select name="mekanik" class="form-control select2" id="mekanik">
					  <option value="">Pilih</option>
						<?php $qry2=sqlsrv_query($con,"SELECT nama FROM dbknitt.tbl_operator2 WHERE jabatan='Mekanik' and status='AKTIF' "); 
						while($r2=sqlsrv_fetch_array($qry2)){
						?>
                      <option value="<?php echo $r2['nama'];?>" <?php if($r2['nama']==$r['mekanik']){echo "SELECTED";}?>><?php echo $r2['nama'];?></option>
						<?php } ?>
                    </select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="mekanik2" class="col-sm-3 control-label">Mekanik 2</label>
                  <div class="col-sm-6">
                     <select name="mekanik2" class="form-control" id="mekanik2">
					  <option value="">Pilih</option>
						<?php $qry2=sqlsrv_query($con,"SELECT nama FROM dbknitt.tbl_operator2 WHERE jabatan='Mekanik' and status='AKTIF' "); 
						while($r2=sqlsrv_fetch_array($qry2)){
						?>
                      <option value="<?php echo $r2['nama'];?>" <?php if($r2['nama']==$r['mekanik2']){echo "SELECTED";}?>><?php echo $r2['nama'];?></option>
						<?php } ?>
                    </select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="mekanik3" class="col-sm-3 control-label">Mekanik 3</label>
                  <div class="col-sm-6">
                     <select name="mekanik3" class="form-control" id="mekanik3">
					  <option value="">Pilih</option>
						<?php $qry2=sqlsrv_query($con,"SELECT nama FROM dbknitt.tbl_operator2 WHERE jabatan='Mekanik' and status='AKTIF' "); 
						while($r2=sqlsrv_fetch_array($qry2)){
						?>
                      <option value="<?php echo $r2['nama'];?>" <?php if($r2['nama']==$r['mekanik3']){echo "SELECTED";}?>><?php echo $r2['nama'];?></option>
						<?php } ?>
                    </select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="aktual" class="col-sm-3 control-label">Keterangan</label>
                  <div class="col-sm-4">  
                <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_service" type="text" class="form-control form-control-sm" id=""  autocomplete="off" value="<?php if($r['tgl_servis']!="0000-00-00"){echo $r['tgl_servis'];}?>">
                 </div> 
					</div>
				  </div>	
                <div class="form-group">
                  <label for="aktual" class="col-sm-3 control-label">Keterangan</label>
                  <div class="col-sm-8">
                    <textarea name="ket" rows="3" class="form-control" id="Ket" placeholder="Keterangan"><?php echo $r['ket']; ?></textarea>
                  </div>
                </div> 
				   			    
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" >Save</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
  </div>
          <!-- /.modal-dialog -->
          <?php } ?>
<script>
  
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
	  format: 'yyyy-mm-dd'
    }),
	//Date picker
    $('#datepicker1').datepicker({
      autoclose: true,
	  format: 'yyyy-mm-dd'
    }),
	//Date picker
    $('#datepicker2').datepicker({
      autoclose: true,
	  format: 'yyyy-mm-dd'
    }),
	//Date picker
    $('#datepicker3').datepicker({
      autoclose: true,
	  format: 'yyyy-mm-dd'
    })
</script>

