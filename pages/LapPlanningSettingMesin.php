<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Scheduled Date</h3>

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
			  
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Jenis Kegiatan</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example12" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
                  <thead>
                  <tr>
                    <th rowspan="3" align="center" valign="middle">No</th>
                    <th rowspan="3" align="center" valign="middle">Tanggal Mulai</th>
                    <th colspan="24" align="center" valign="middle">Jenis Kegiatan</th>
                    </tr>
                  <tr>
                    <th colspan="4">Ganti Slinder</th>
                    <th colspan="4">Ganti Konstruksi</th>
                    <th colspan="4">Setting PB</th>
                    <th colspan="4">Special Setting</th>
                    <th colspan="4">Ganti Jarum/Sinker</th>
                    <th colspan="4">TOTAL</th>
                    </tr>
                  <tr>
                    <th>S</th>
                    <th>T</th>
                    <th>R</th>
                    <th>D</th>
                    <th>S</th>
                    <th>T</th>
                    <th>R</th>
                    <th>D</th>
                    <th>S</th>
                    <th>T</th>
                    <th>R</th>
                    <th>D</th>
                    <th>S</th>
                    <th>T</th>
                    <th>R</th>
                    <th>D</th>
                    <th>S</th>
                    <th>T</th>
                    <th>R</th>
                    <th>D</th>
                    <th>S</th>
                    <th>T</th>
                    <th>R</th>
                    <th>D</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php	
$sqlDB2 =" 
SELECT
    a.VALUEDATE AS TGL,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'D' AND b.VALUESTRING = '3' THEN 1 ELSE 0 END) AS PB_D,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'S' AND b.VALUESTRING = '3' THEN 1 ELSE 0 END) AS PB_S,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'T' AND b.VALUESTRING = '3' THEN 1 ELSE 0 END) AS PB_T,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'R' AND b.VALUESTRING = '3' THEN 1 ELSE 0 END) AS PB_R,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'D' AND b.VALUESTRING = '4' THEN 1 ELSE 0 END) AS KONS_D,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'S' AND b.VALUESTRING = '4' THEN 1 ELSE 0 END) AS KONS_S,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'T' AND b.VALUESTRING = '4' THEN 1 ELSE 0 END) AS KONS_T,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'R' AND b.VALUESTRING = '4' THEN 1 ELSE 0 END) AS KONS_R,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'D' AND b.VALUESTRING = '5' THEN 1 ELSE 0 END) AS CYL_D,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'S' AND b.VALUESTRING = '5' THEN 1 ELSE 0 END) AS CYL_S,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'T' AND b.VALUESTRING = '5' THEN 1 ELSE 0 END) AS CYL_T,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'R' AND b.VALUESTRING = '5' THEN 1 ELSE 0 END) AS CYL_R,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'D' AND b.VALUESTRING = '7' THEN 1 ELSE 0 END) AS SPC_D,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'S' AND b.VALUESTRING = '7' THEN 1 ELSE 0 END) AS SPC_S,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'T' AND b.VALUESTRING = '7' THEN 1 ELSE 0 END) AS SPC_T,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'R' AND b.VALUESTRING = '7' THEN 1 ELSE 0 END) AS SPC_R,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'D' AND b.VALUESTRING = '8' THEN 1 ELSE 0 END) AS JRM_D,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'S' AND b.VALUESTRING = '8' THEN 1 ELSE 0 END) AS JRM_S,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'T' AND b.VALUESTRING = '8' THEN 1 ELSE 0 END) AS JRM_T,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'R' AND b.VALUESTRING = '8' THEN 1 ELSE 0 END) AS JRM_R,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'D' AND b.VALUESTRING IN ('3','4','5','7','7') THEN 1 ELSE 0 END) AS D,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'S' AND b.VALUESTRING IN ('3','4','5','7','7')  THEN 1 ELSE 0 END) AS S,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'T' AND b.VALUESTRING IN ('3','4','5','7','7')  THEN 1 ELSE 0 END) AS T,
    SUM(CASE WHEN substr(c.VALUESTRING, 1, 1) = 'R' AND b.VALUESTRING IN ('3','4','5','7','7')  THEN 1 ELSE 0 END) AS R
FROM
    PRODUCTIONDEMAND pd
LEFT OUTER JOIN 
    ADSTORAGE a ON pd.ABSUNIQUEID = a.UNIQUEID
    AND a.FIELDNAME = 'TglRencana'
LEFT OUTER JOIN    
    ADSTORAGE b ON pd.ABSUNIQUEID = b.UNIQUEID
    AND b.FIELDNAME = 'JenisSetting'
LEFT OUTER JOIN    
    ADSTORAGE c ON pd.ABSUNIQUEID = c.UNIQUEID
    AND c.FIELDNAME = 'MachineNoCode'        
WHERE
    a.VALUEDATE BETWEEN '$Awal' AND '$Akhir'
    AND b.VALUESTRING IN ('3', '4', '5', '7', '8')
GROUP BY 
    a.VALUEDATE
";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$no=1; 					  
 while ($rowdb2 = db2_fetch_assoc($stmt)) { 
	   ?>
	  <tr>
	    <td><?php echo $no; ?></td>
	    <td align="center"><?php echo $rowdb2['TGL']; ?></td>
	    <td align="center"><?php echo $rowdb2['CYL_S']; ?></td>
	    <td align="center"><?php echo $rowdb2['CYL_T']; ?></td>
	    <td align="center"><?php echo $rowdb2['CYL_R']; ?></td>
	    <td align="center"><?php echo $rowdb2['CYL_D']; ?></td>
	    <td align="center"><?php echo $rowdb2['KONS_S']; ?></td>
	    <td align="center"><?php echo $rowdb2['KONS_T']; ?></td>
	    <td align="center"><?php echo $rowdb2['KONS_R']; ?></td>
	    <td align="center"><?php echo $rowdb2['KONS_D']; ?></td>
	    <td align="center"><?php echo $rowdb2['PB_S']; ?></td>
	    <td align="center"><?php echo $rowdb2['PB_T']; ?></td>
	    <td align="center"><?php echo $rowdb2['PB_R']; ?></td>
	    <td align="center"><?php echo $rowdb2['PB_D']; ?></td>
	    <td align="center"><?php echo $rowdb2['SPC_S']; ?></td>
	    <td align="center"><?php echo $rowdb2['SPC_T']; ?></td>
	    <td align="center"><?php echo $rowdb2['SPC_R']; ?></td>
	    <td align="center"><?php echo $rowdb2['SPC_D']; ?></td>
	    <td align="center"><?php echo $rowdb2['JRM_S']; ?></td>
	    <td align="center"><?php echo $rowdb2['JRM_T']; ?></td>
	    <td align="center"><?php echo $rowdb2['JRM_R']; ?></td>
	    <td align="center"><?php echo $rowdb2['JRM_D']; ?></td>
	    <td align="center"><?php echo $rowdb2['S']; ?></td>
	    <td align="center"><?php echo $rowdb2['T']; ?></td>
	    <td align="center"><?php echo $rowdb2['R']; ?></td>
	    <td align="center"><?php echo $rowdb2['D']; ?></td>
      </tr>				  
	<?php 
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