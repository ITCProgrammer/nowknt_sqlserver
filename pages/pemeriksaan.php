<?php

    if (isset($_POST['save'])) {
        $no_mesin=$_GET['no_mesin'];
        $ket=str_replace("'", "''", $_POST['ket']);
        $mekanik=str_replace("'", "''", $_POST['mekanik']);
        $mekanik2=str_replace("'", "''", $_POST['mekanik2']);
        $mekanik3=str_replace("'", "''", $_POST['mekanik3']);
		$tgl_service = !empty($_POST['tgl_service']) ? $_POST['tgl_service'] : NULL;
        $sts=$_POST['sts'];
        if ($sts=="Hold") {
            $produksi="0";
        } else {
            $produksi=$_POST['produksi'];
        }
       $sql = "INSERT INTO dbknitt.tbl_jadwal
				(
					no_mesin,
					kg_awal,
					ket,
					mekanik,
					mekanik2,
					mekanik3,
					kategori,
					sts,
					tgl_servis,
					tgl_buat,
					tgl_update,
					userid
				)
				VALUES
				(
					?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE(), GETDATE(), ?
				)
				";

				$params = [ $no_mesin,$produksi,$ket,
							$mekanik,$mekanik2,$mekanik3,
							$_POST['kategori'],$sts,$tgl_service,'agung'
							];

				$stmt = sqlsrv_query($con, $sql, $params);

				if ($stmt === false) {
					die(print_r(sqlsrv_errors(), true));
				}
			echo "<script>
					swal({
						title: 'Data Tersimpan',
						text: 'Klik Ok untuk melanjutkan',
						type: 'success'
					}).then((result) => {
						if (result.value) {
							window.location.href='Pemeriksaan-{$no_mesin}';
						}
					});
				</script>";
    }
?>
<div class="container-fluid">
<form role="form" method="post" enctype="multipart/form-data" name="form1">	
	<div class="card card-info">	
		<div class="card-header">
			<h3 class="card-title">Formulir Pemeriksaan Mesin Knitting</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
		</div>
		<?php		
    $sql=sqlsrv_query($con," SELECT
									a.no_mesin, a.kd_dtex, a.batas_produksi, SUM(b.berat_awal) as KGS
								FROM
									dbknitt.tbl_mesin a
								LEFT JOIN dbknitt.tbl_inspeksi_detail b ON a.no_mesin=b.no_mc
								WHERE a.no_mesin='".$_GET['no_mesin']."'
								GROUP BY
									a.no_mesin,
									a.kd_dtex,
									a.batas_produksi
								ORDER BY
									a.no_mesin ASC ");
    $r=sqlsrv_fetch_array($sql);
	$sql1=sqlsrv_query($con," SELECT TOP 1 
									a.tgl_servis,
									b.kg_awal,
									b.sts 
								FROM dbknitt.tbl_jadwal a
								LEFT JOIN 
									(
										SELECT 
											SUM(kg_awal) as kg_awal,
											(
												SELECT TOP 1 sts
												FROM dbknitt.tbl_jadwal t2
												WHERE t2.no_mesin = t1.no_mesin
												ORDER BY t2.tgl_servis ASC
											) AS sts,
											no_mesin  
										FROM dbknitt.tbl_jadwal t1 
										WHERE no_mesin='".$_GET['no_mesin']."' 
										GROUP BY no_mesin
									) b ON b.no_mesin=a.no_mesin 
								WHERE 
									a.no_mesin='".$_GET['no_mesin']."' 
								ORDER BY a.tgl_servis 
								DESC");
    $r1=sqlsrv_fetch_array($sql1);
		
	$sqlDB2="SELECT 
					SUM(WEIGHTNET) AS KG 
				FROM ELEMENTSINSPECTION 
				LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND 
					ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE 
				LEFT OUTER JOIN DB2ADMIN.ADSTORAGE 
					ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID 
					AND ADSTORAGE.NAMENAME ='MachineNo' 
				WHERE ELEMENTITEMTYPECODE='KGF' AND ADSTORAGE.VALUESTRING='$r[kd_dtex]'
";
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$rowdb2 = db2_fetch_assoc($stmt);	
	
		
	 $total=round((round($r['KGS'],2)+round($rowdb2['KG'],2))-round($r1['kg_awal'],2), '2');	
     ?>
		<div class="card-body">
			<input name="produksi1" type="hidden" class="form-control" id="produksi1" value="<?php echo $r1['kg_awal']; ?>">				
			<div class="form-group row">
				<label for="order" class="col-md-1">Produksi</label>
				<div class="col-md-2">
					<input name="produksi" type="text" class="form-control form-control-sm" id="produksi" value="<?php echo $total; ?>" placeholder="Produksi" readonly>
				</div>
				<div class="col-md-6">
					Tgl Servis Terakhir: <?php echo $r1['tgl_servis']; ?> ; Note: <?php if($r1['sts']=="Hold"){echo '<span class="label label-info">Hold</span>';}else if($total>$r['batas_produksi']){echo '<span class="label label-warning">Servis Berkala</span>';}else{echo '<span class="label label-success">OK</span>';}?>
				</div>
			</div>
			<div class="form-group row">
				<label for="order" class="col-md-1">Kategori</label>
				<div class="col-md-1">
					<select name="kategori" class="form-control form-control-sm" id="kategori" required>
						<option value="Ringan">Ringan</option>
						<option value="Over Houl">Over Houl</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="order" class="col-md-1">Status</label>
				<div class="col-md-1">
					<select name="sts" class="form-control form-control-sm" id="sts" required>
						<option value="Berkala">Berkala</option>
						<option value="Hold">Hold</option>
						<option value="Trouble">Trouble</option>
						<option value="Ganti Konstruksi">Ganti Konstruksi</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="mekanik" class="col-md-1">Mekanik 1</label>
				<div class="col-sm-2">
					<select name="mekanik" class="form-control form-control-sm  select2" id="mekanik">
						<option value="">Pilih</option>
						<?php $qry2=sqlsrv_query($con,"SELECT nama FROM dbknitt.tbl_operator2 WHERE jabatan='Mekanik' and status='AKTIF' ");
                        while ($r2=sqlsrv_fetch_array($qry2)) {
                            ?>
						<option value="<?php echo $r2['nama']; ?>">
							<?php echo $r2['nama']; ?>
						</option>
						<?php
                        } ?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="mekanik2" class="col-md-1">Mekanik 2</label>
				<div class="col-sm-2">
					<select name="mekanik2" class="form-control form-control-sm select2" id="mekanik2">
						<option value="">Pilih</option>
						<?php $qry2=sqlsrv_query($con,"SELECT nama FROM dbknitt.tbl_operator2 WHERE jabatan='Mekanik' and status='AKTIF' ");
                        while ($r2=sqlsrv_fetch_array($qry2)) {
                            ?>
						<option value="<?php echo $r2['nama']; ?>">
							<?php echo $r2['nama']; ?>
						</option>
						<?php
                        } ?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label for="mekanik3" class="col-md-1">Mekanik 3</label>
				<div class="col-sm-2">
					<select name="mekanik3" class="form-control form-control-sm select2" id="mekanik3">
						<option value="">Pilih</option>
						<?php $qry2=sqlsrv_query($con,"SELECT nama FROM dbknitt.tbl_operator2 WHERE jabatan='Mekanik' and status='AKTIF' ");
                        while ($r2=sqlsrv_fetch_array($qry2)) {
                            ?>
						<option value="<?php echo $r2['nama']; ?>">
							<?php echo $r2['nama']; ?>
						</option>
						<?php
                        } ?>
					</select>
				</div>
			</div>
			<div class="form-group row">
               <label for="tgl_awal" class="col-md-1">Tgl Service</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_service" type="text" class="form-control form-control-sm" id=""  autocomplete="off">
                 </div>
			   </div>	
            </div>			
			<div class="form-group row">
				<label for="aktual" class="col-md-1">Keterangan</label>
				<div class="col-md-8">
					<textarea name="ket" rows="4" class="form-control form-control-sm" id="Ket" placeholder="Keterangan"></textarea>
				</div>
			</div>

		</div>
		<div class="card-footer">			
			<button type="submit" class="btn btn-info" name="save">Simpan <i class="fa fa-save"></i></button>
			<a href="PreventifMesin" class="btn btn-default float-right">Kembali <i class="fas fa-arrow-circle-right"></i></a>
		</div>
		<!-- /.box-footer -->	
</div>
</form>	
	<div class="card card-warning">
			<div class="card-header">
                <h3 class="card-title">Detail Data Benang Pakai</h3>				 
          </div>
			<div class="card-body">
				<table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
					<thead class="bg-green">
						<tr>
							<th width="27">
								<div align="center">No</div>
							</th>
							<th width="86">
								<div align="center">Cetak</div>
							</th>
							<th width="91">
								<div align="center">
									<div align="center">Produksi</div>
							</th>
							<th width="169">
								<div align="center">Kategori</div>
							</th>
							<th width="169">
								<div align="center">Status</div>
							</th>
							<th width="357">
								<div align="center">Keterangan</div>
							</th>
							<th width="146">
								<div align="center">Tgl Service</div>
							</th>
							<th width="219">
								<div align="center">Mekanik</div>
							</th>
							<th width="65">
								<div align="center">Action</div>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
  $sqlcek=sqlsrv_query($con," SELECT TOP 1 * FROM dbknitt.tbl_jadwal WHERE no_mesin='".$_GET['no_mesin']."' ORDER by id DESC");
  $rcek=sqlsrv_fetch_array($sqlcek);
  $akhir=$rcek['tgl_servis'];				
  $sql=sqlsrv_query($con," SELECT * FROM dbknitt.tbl_jadwal WHERE no_mesin='".$_GET['no_mesin']."' ");			
  while ($r=sqlsrv_fetch_array($sql)) {
      $no++;
      $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite'; ?>
						<tr bgcolor="<?php echo $bgcolor; ?>">
							<td align="center">
								<?php echo $no; ?>
							</td>
							<td align="center"><a href="pages/cetak/form-periksa.php?id=<?php echo $r['id']; ?>" class="btn-sm btn-info" target="_blank"><i class="fa fa-print"></i> </a></td>
							<td align="center">
								<?php echo $r['kg_awal']; ?>
							</td>
							<td align="center">
								<?php if ($r['kategori']=="Ringan") {
          echo '<span class="label label-success">'.$r['kategori'].'</span>';
      } elseif ($r['kategori']=="Over Houl") {
          echo '<span class="label label-danger">'.$r['kategori'].'</span>';
      } ?>
							</td>
							<td align="center">
								<?php if ($r['sts']=="Berkala") {
          echo '<span class="label label-warning">'.$r['sts'].'</span>';
      } elseif ($r['sts']=="Hold") {
          echo '<span class="label label-info">'.$r['sts'].'</span>';
      } elseif ($r['sts']=="Trouble") {
          echo '<span class="label label-danger">'.$r['sts'].'</span>';
      } elseif ($r['sts']=="Ganti Konstruksi") {
          echo '<span class="label label-info">'.$r['sts'].'</span>';
      } ?>
							</td>
							<td align="center">
								<?php echo $r['ket']; ?>
							</td>
							<td align="center">
								<?php echo $r['tgl_servis']; ?>
							</td>
							<td align="left">
								<?php if ($r['mekanik']!="") {
          echo "1. ".$r['mekanik'];
      }
      if ($r['mekanik2']!="") {
          echo "<br>2. ".$r['mekanik2'];
      }
      if ($r['mekanik3']!="") {
          echo "<br>3. ".$r['mekanik3'];
      } ?>
							</td>
							<td align="center"><div class="btn-group"><a href="#" class="btn btn-sm btn-success periksa_edit" id="<?php echo $r['id'] ?>"><i class="fa fa-edit"></i></a><a href="#" class="btn btn-sm btn-danger <?php if($akhir != $r['tgl_servis'] ){echo "disabled"; } ?>" onclick="confirm_del_jdwl('DelPeriksa-<?php echo $r['id']; ?>-<?php echo $_GET['no_mesin'];?>');" ><i class="fa fa-trash"></i></a></div></td>
						</tr>
						<?php
  } ?>
					</tbody>

				</table>
				<div id="PeriksaEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

				</div>
				
			</div>
		</div>

</div>	
<!-- Modal Popup untuk delete-->
            <div class="modal fade" id="delJadwal" tabindex="-1">
			  <div class="modal-dialog">
                <div class="modal-content" style="margin-top:100px;">
                  <div class="modal-header">
					<h4 class="modal-title">INFOMATION</h4>  
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    
                  </div>
					<div class="modal-body">
						<h5 class="modal-title" style="text-align:center;">Are you sure to delete this information ?</h5>
					</div>	
                  <div class="modal-footer justify-content-between">
                    <a href="#" class="btn btn-danger" id="delJadwal_link">Delete</a>
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>			
              
            </div>
<script type="text/javascript">
              function confirm_del_jdwl(delete_url) {
                $('#delJadwal').modal('show', {
                  backdrop: 'static'
                });
                document.getElementById('delJadwal_link').setAttribute('href', delete_url);
              }

            </script>