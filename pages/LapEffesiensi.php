<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Shift	= isset($_POST['shift']) ? $_POST['shift'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
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
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm"  autocomplete="off" required>
                  </div>
					</div>	
                  </div>
			      <div class="form-group row">
                  <label class="col-md-1">Shift</label>
				  <div class="col-md-2">	  
                  <select class="form-control select2bs4" name="shift">
                    <option value="">ALL</option>
                    <option value="1" <?php if($Shift=="1"){echo "Selected";}?>>1</option>
                    <option value="2" <?php if($Shift=="2"){echo "Selected";}?>>2</option>
                    <option value="3" <?php if($Shift=="3"){echo "Selected";}?>>3</option>
                  </select>
				  </div>  
                </div>
			  
          </div>
		  <div class="card-footer">
			<button class="btn btn-primary" type="submit">Cari Data</button>
		</div>
		  <!-- /.card-body -->
          
        </div> 
			</form>
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Effesiensi</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">No Mc</th>
                    <th rowspan="2">Sft</th>
                    <th rowspan="2">User</th>
                    <th rowspan="2">Operator</th>
                    <th rowspan="2">Leader</th>
                    <th rowspan="2">NoArt</th>
                    <th rowspan="2">TgtCnt (100%)</th>
                    <th rowspan="2">Rpm</th>
                    <th rowspan="2">Cnt/Roll</th>
                    <th colspan="2">Aktual</th>
                    <th colspan="3">Produksi</th>
                    <th rowspan="2">Grp</th>
                    <th rowspan="2">Tgt Grp (%)</th>
                    <th rowspan="2">Eff (%)</th>
                    <th rowspan="2">Hasil (%)</th>
                    <th colspan="2">StopPage1</th>
                    <th colspan="2">StopPage2</th>
                    <th colspan="2">StopPage3</th>
                    <th rowspan="2">Tgl Prod</th>
                    <th rowspan="2">Tgl Buat</th>
                    <th rowspan="2">Keterangan</th>
                    </tr>
                  <tr>
                    <th>Jam Kerja</th>
				    <th>Count</th>
				    <th>Count</th>
				    <th>RL</th>
				    <th>Kgs</th>
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th> 
					</tr>
                  </thead>
                  <tbody>
				  <?php
	if( $Awal!="" and $Akhir!=""){				  
	$Tgl= " a.`tgl_produksi` BETWEEN '$Awal' AND '$Akhir' " ;
	}else{
		$Tgl= " a.`tgl_produksi` BETWEEN '2200-12-12' AND '2200-12-12' " ;
	}
	if($Shift!=""){
		$Shft=" AND a.shft='$Shift' ";
	}else{
		$Shft=" ";
	}				  
		$sql=mysqli_query($con," SELECT
	DATE_FORMAT(a.tgl_produksi, '%d/%m/%y') AS tgl,
	a.id,
	a.no_mesin,
	a.nama,
	a.leader,
	a.jenis_kain,
	a.grade,
	a.count_roll,
	a.t_count,
	a.shft,
	a.tgl_produksi,
	a.grp,
	a.no_po,
	a.no_artikel,
	a.keterangan,
	a.jam_kerja_a,
	a.t_count_a,
	a.rpm_a,
	a.kdstop1,
	a.kdstop2,
	a.kdstop3,
	a.menit1,
	a.menit2,
	a.menit3,
	(
		sum(b.counter) + a.counter_akhir - a.counter_awal
	) AS counter,
	abs(sum(b.counter) - a.t_count) AS selisih_counter,
	(
		sum(b.counter) / a.count_roll
	) AS roll,
	c.total,
	(
		a.std_kg * (
			(
				sum(b.counter) + a.counter_akhir - a.counter_awal
			) / a.count_roll
		)
	) AS berat,
	(
		(a.std_kg / a.count_roll) * abs(sum(b.counter) - a.t_count)
	) AS selisih_kg,
	(
		CASE
		WHEN a.grade = 'A' THEN
			95
		WHEN a.grade = 'B' THEN
			86
		WHEN a.grade = 'C' THEN
			92
		WHEN a.grade = 'D' THEN
			86
		END
	) AS t_gr,
	(
		(
			(
				(
					(
						sum(b.counter) + a.counter_akhir - a.counter_awal
					) / a.t_count
				)
			) * 100
		) / (
			CASE
			WHEN a.grade = 'A' THEN
				95
			WHEN a.grade = 'B' THEN
				86	
			WHEN a.grade = 'C' THEN
				92
			WHEN a.grade = 'D' THEN
				86
			END
		)
	) * 100 AS capai,
	(
		(
			(
				sum(b.counter) + a.counter_akhir - a.counter_awal
			) / a.t_count
		)
	) * 100 AS eff,
	a.rpm,
	a.dept,
	a.userid,
	a.tgl_buat
FROM
	tbl_rajut_produksi_now a
LEFT JOIN tbl_rajut_produksi_detail_now b ON a.id = b.id_rajut
LEFT JOIN tbl_mesin_stop_now c ON a.id = c.id_rajut
WHERE  $Tgl $Shft 
GROUP BY
	a.id
ORDER BY
	tgl_produksi,shft ASC");
   $no=1;   
   $c=0;
    while($rowd=mysqli_fetch_array($sql)){
	if($rowd['jam_kerja_a']!="0"){
		 $eff=round(($rowd['counter']/round($rowd['jam_kerja_a']*$rowd['rpm']*60)*100),"2");
			if ($rowd['t_gr']<>""){
		 $hsl=round(($eff/$rowd['t_gr'])*100,"2");
			}
		}
	   ?>
	  <tr>
      <td><?php echo $no; ?></td>
      <td><?php echo $rowd['no_mesin']; ?></td>
      <td><?php echo $rowd['shft']; ?></td>
      <td><?php echo $rowd['userid']; ?></td>
      <td><?php echo $rowd['nama']; ?></td>
      <td><?php echo $rowd['leader']; ?></td>
      <td><?php echo $rowd['no_artikel']; ?></td>
      <td><?php echo $rowd['t_count']; ?></td>
      <td><?php echo $rowd['rpm']; ?></td>
      <td><?php echo $rowd['count_roll']; ?></td>
	  <td><?php echo $rowd['jam_kerja_a']; ?></td>
      <td><?php echo round($rowd['jam_kerja_a']*$rowd['rpm']*60); ?></td>
      <td><?php echo $rowd['counter']; ?></td>
      <td><?php echo round($rowd['roll']); ?></td>
      <td><?php echo number_format(round($rowd['berat'],'2'),'2'); ?></td>
      <td><?php echo $rowd['grade']; ?></td>
      <td><?php echo $rowd['t_gr']; ?></td>
      <td><?php // echo number_format(round($rowd[eff],'2'),'2'); ?><?php echo $eff;?></td>
      <td><?php // echo number_format(round($rowd[capai],'2'),'2'); ?><?php echo $hsl;?></td>
      <td><?php echo $rowd['kdstop1']; ?></td>
      <td><?php echo $rowd['menit1']; ?></td>
      <td><?php echo $rowd['kdstop2']; ?></td>
      <td><?php echo $rowd['menit2']; ?></td>
      <td><?php echo $rowd['kdstop3']; ?></td>
      <td><?php echo $rowd['menit3']; ?></td>
      <td><?php echo $rowd['tgl']; ?></td>
      <td><?php echo $rowd['tgl']; ?></td>
      <td><?php echo $rowd['keterangan']; ?></td>
      </tr>				  
					  <?php 
	 $totberat=$totberat+$rowd['berat'];
	 $totqty=$totqty+$rowd['qty'];
	 $totcones=$totcones+$rowd['cones'];
	 if($rowd['pack']=="DUS"){$dus=$rowd['qty'];$bdus=$rowd['berat'];}else{$dus="0";$bdus="0";}
	 if($rowd['pack']=="KARUNG"){$karung=$rowd['qty'];$bkarung=$rowd['berat'];}else{$karung="0";$bkarung="0";}
	 if($rowd['pack']=="PALET"){$palet=$rowd['qty'];$bpalet=$rowd['berat'];}else{$palet="0";$bpalet="0";}
	 $totdus=$totdus+$dus;
	 $totkarung=$totkarung+$karung;
	 $totpalet=$totpalet+$palet;
	 $totbdus=$totbdus+$bdus;
	 $totbkarung=$totbkarung+$bkarung;
	 $totbpalet=$totbpalet+$bpalet;
	 $no++;} ?>
				  </tbody>
                  <!--<tfoot>
                  <tr>
                    <th>No</th>
                    <th>No Mc</th>
                    <th>Sft</th>
                    <th>User</th>
                    <th>Operator</th>
					<th>Leader</th>
                    <th>NoArt</th>
                    <th>TgtCnt (100%)</th>
                    <th>Rpm</th>
                    <th>Cnt/Roll</th>
					<th>Jam Kerja</th>
				    <th>Count</th>
				    <th>Count</th>
				    <th>RL</th>
				    <th>Kgs</th>
				    <th>Grp</th>
      				<th>Tgt Grp (%)</th>
      				<th>Eff (%)</th>
      				<th>Hasil (%)</th>  
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th> 
					<th>Tanggal</th>
      				<th>Keterangan</th>
                  </tr>
                  </tfoot>-->
                </table>
              </div>
              <!-- /.card-body -->
            </div>  
      </div><!-- /.container-fluid -->
    <!-- /.content -->
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