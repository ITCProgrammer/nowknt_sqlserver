<?php
//ini_set("error_reporting", 0);
session_start();
require_once "koneksi.php";

$Awal  = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir  = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Status  = isset($_POST['sts']) ? $_POST['sts'] : '';

?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Filter Data Tgl Mulai</h3>

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
              <input name="tgl_awal" value="<?php echo $Awal; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
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
              <input name="tgl_akhir" value="<?php echo $Akhir; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
            </div>
          </div>
        </div>
		<div class="form-group row">
          <label for="sts" class="col-md-1">Status</label>
          <div class="col-md-2">
            <select name="sts" class="form-control">
				<option value="">All</option>
				<option value="In Progress" <?php if($Status=="In Progress"){ echo "SELECTED"; } ?>>In Progress</option>
				<option value="Close" <?php if($Status=="Close"){ echo "SELECTED"; } ?>>Close</option>
			</select>
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
        <h3 class="card-title">Detail Monitoring Proses Setting Mesin</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <?php //if(isset($_POST['tgl_awal'])) : ?>
        <table id="example1" class="table table-sm table-bordered table-striped nowrap" style="font-size: 13px; text-align: center;">
          <thead>
            <tr>
              <th rowspan="2" valign="middle" style="text-align: center">No</th>
              <th rowspan="2" valign="middle" style="text-align: center">Tgl Mulai</th>
              <th rowspan="2" valign="middle" style="text-align: center">Demand</th>
              <th rowspan="2" valign="middle" style="text-align: center">No MC</th>
              <th rowspan="2" valign="middle" style="text-align: center">No Hanger</th>
              <th rowspan="2" valign="middle" style="text-align: center">Jenis Setting</th>
              <th rowspan="2" valign="middle" style="text-align: center">Nama Mekanik</th>
              <th rowspan="2" valign="middle" style="text-align: center">Tgl. Pasang Benang</th>
              <th colspan="2" valign="middle" style="text-align: center">Tanggal</th>
              <th rowspan="2" valign="middle" style="text-align: center">Pencapaian</th>
              <th rowspan="2" valign="middle" style="text-align: center">Keterangan</th>
            </tr>
            <tr>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Aktual</th>
            </tr>
          </thead>
          <tbody>
            <?php             

              $no = 1;
              $c = 0;
			  if($Status=="Close"){
				  $where = " AND NOT pp2.PROGRESSLOADDATE IS NULL ";
			  }else if($Status=="In Progress"){
				  $where = " AND pp2.PROGRESSLOADDATE IS NULL ";
			  }else{
				  $where = " ";
			  }
              $sqlDB21 = " 
SELECT p.*, b.VALUEDATE, c.VALUESTRING, d.VALUESTRING AS SETTINGMC, p2.PRODUCTIONORDERCODE, pp.PROGRESSLOADDATE, pp.PROGRESSSTARTPREPROCESSTIME, p2.LASTUPDATEDATETIME AS LASTUPDATETPB,p2.PROGRESSSTATUS AS STSTPB, pp2.PROGRESSLOADDATE AS AKTUAL, (e.VALUEDECIMAL*1) AS LIBUR, f.VALUESTRING AS KETMEKANIK, g.VALUESTRING AS NAMA FROM PRODUCTIONDEMAND p 
LEFT OUTER JOIN ADSTORAGE b ON b.UNIQUEID = p.ABSUNIQUEID AND b.NAMENAME = 'TglRencana'
LEFT OUTER JOIN ADSTORAGE c ON c.UNIQUEID = p.ABSUNIQUEID AND c.NAMENAME = 'MachineNo'
LEFT OUTER JOIN ADSTORAGE d ON d.UNIQUEID = p.ABSUNIQUEID AND d.NAMENAME = 'JenisSetting'
LEFT OUTER JOIN ADSTORAGE e ON e.UNIQUEID = p.ABSUNIQUEID AND e.NAMENAME = 'TotLibur'
LEFT OUTER JOIN ADSTORAGE f ON f.UNIQUEID = p.ABSUNIQUEID AND f.NAMENAME = 'KetSetting'
LEFT OUTER JOIN ADSTORAGE g ON g.UNIQUEID = p.ABSUNIQUEID AND g.NAMENAME = 'NamaMekanik'
LEFT OUTER JOIN ADSTORAGE h ON h.UNIQUEID = p.ABSUNIQUEID AND h.NAMENAME = 'StatusMesin'
LEFT OUTER JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE AND p2.OPERATIONCODE = 'TPB'
LEFT OUTER JOIN PRODUCTIONPROGRESS pp ON pp.PRODUCTIONORDERCODE = p2.PRODUCTIONORDERCODE AND pp.PROGRESSTEMPLATECODE ='E01' AND pp.OPERATIONCODE ='TPB'
LEFT OUTER JOIN PRODUCTIONPROGRESS pp2 ON pp2.PRODUCTIONORDERCODE = p2.PRODUCTIONORDERCODE AND pp2.PROGRESSTEMPLATECODE ='S01' AND pp2.OPERATIONCODE ='KNT1'
WHERE b.VALUEDATE BETWEEN '$Awal' AND '$Akhir' AND d.VALUESTRING > 1 AND h.VALUESTRING = 1 $where ";
              $stmt1   = db2_exec($conn1, $sqlDB21);
              while ($rowdb21 = db2_fetch_assoc($stmt1)) {  
			  if($rowdb21['SETTINGMC']=="1"){
				$jnsSet="Lanjut Jalan";  
				$hr=0;  
			  }else if($rowdb21['SETTINGMC']=="2"){
				$jnsSet="Cek Gramasi";  
				$hr=0;  
			  }else if($rowdb21['SETTINGMC']=="3"){
				$jnsSet="Setting PB";  
				$hr=1;  
			  }else if($rowdb21['SETTINGMC']=="4"){
				$jnsSet="Ganti Konstruksi";  
				$hr=2;  
			  }else if($rowdb21['SETTINGMC']=="5"){
				$jnsSet="Ganti Cylinder";  
				$hr=3;  
			  }else if($rowdb21['SETTINGMC']=="6"){
				$jnsSet="Change IN";  
				$hr=0;
			  }else if($rowdb21['SETTINGMC']=="7"){
				$jnsSet="Spesial Setting";  
				$hr=2;
			  }else if($rowdb21['SETTINGMC']=="8"){
				$jnsSet="Ganti Jarum/Singker";  
				$hr=2;	  
			  }else{
				$jnsSet="";  
				$hr=0;  
			  }
			  if($rowdb21['LIBUR']!=""){
				  $libur=$rowdb21['LIBUR'];
			  }else{
				  $libur=0;
			  }
			  $torhr=$hr+$libur;	  
			  $target = date('Y-m-d', strtotime($rowdb21['VALUEDATE'] . ' +'.$torhr.' day'));
			  if($rowdb21['AKTUAL']<=$target and $rowdb21['AKTUAL']!=""){
				$capai = "<small class='badge badge-success'>OK</small>";  
			  }	else if($rowdb21['AKTUAL']!=""){
				$capai = "<small class='badge badge-danger'>Not OK</small>";  
			  } else {
				$capai = " " ; 
			  } 
			  	  
			  ?>
            <tr>
              <td style="text-align: center"><?php echo $no; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['VALUEDATE']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['CODE']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['VALUESTRING']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['SUBCODE02']."".$rowdb21['SUBCODE03']; ?></td>
              <td style="text-align: center"><?php echo $jnsSet; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['NAMA']; ?></td>
              <td style="text-align: center"><?php 
				  //echo $rowdb21['PROGRESSLOADDATE']. " " .$rowdb21['PROGRESSSTARTPREPROCESSTIME'] ;
				  if($rowdb21['STSTPB']=="3"){ echo substr($rowdb21['LASTUPDATETPB'],0,16); } ?></td>
              <td style="text-align: center"><?php echo $target; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['AKTUAL']; ?></td>
              <td style="text-align: center"><?php echo $capai; ?></td>
              <td style="text-align: left"><?php echo $rowdb21['KETMEKANIK']; ?></td>
            </tr>
            <?php $no++; } ?>
          </tbody>
        </table>
        <?php //endif; ?>
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
  $(function() {
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
  function checkAll(form1) {
    for (var i = 0; i < document.forms['form1'].elements.length; i++) {
      var e = document.forms['form1'].elements[i];
      if ((e.name != 'allbox') && (e.type == 'checkbox')) {
        e.checked = document.forms['form1'].allbox.checked;

      }
    }
  }
</script>
<?php
if ($_POST['mutasikain'] == "MutasiKain") {

  function mutasiurut()
  {
    require_once "koneksi.php";
    $format = "20" . date("ymd");
    $query = "SELECT TOP 1 
            COUNT(*) OVER() as num_row, 
            no_mutasi 
          FROM tbl_mutasi_kain 
          WHERE SUBSTRING(no_mutasi, 1, 8) LIKE '%" . $format . "%' 
          ORDER BY no_mutasi DESC";

    $sql = sqlsrv_query($con, $query);
    $d = 0; 
    $r = null;
    if (sqlsrv_has_rows($sql)) {
        $r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
        // Ambil jumlah baris DARI HASIL QUERY (num_row), bukan dari fungsi PHP
        $d = $r['num_row']; 
    }
    if ($d > 0) {
      $d = $r['no_mutasi'];
      $str = substr($d, 8, 2);
      $Urut = (int)$str;
    } else {
      $Urut = 0;
    }
    $Urut = $Urut + 1;
    $Nol = "";
    $nilai = 2 - strlen($Urut);
    for ($i = 1; $i <= $nilai; $i++) {
      $Nol = $Nol . "0";
    }
    $tidbr = $format . $Nol . $Urut;
    return $tidbr;
  }
  $nomid = mutasiurut();

  $sql1 = sqlsrv_query($con, "SELECT a.transid as kdtrans, count(b.transid) as jmlrol FROM dbknitt.tbl_mutasi_kain a 
                          LEFT JOIN dbknitt.tbl_prodemand b ON a.transid=b.transid 
                          WHERE a.no_mutasi IS NULL AND CONVERT(date, a.tgl_buat)='$Awal' AND a.gshift='$Gshift' 
                          GROUP BY a.transid");
  $n1 = 1;
  $noceklist1 = 1;
  while ($r1 = sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC)) {
    if ($_POST['cek'][$n1] != '') {
      $transid1 = $_POST['cek'][$n1];
      sqlsrv_query($con, "UPDATE dbknitt.tbl_mutasi_kain SET
		no_mutasi='$nomid',
		tgl_mutasi=GETDATE()
		WHERE transid='$transid1'
		");
    } else {
      $noceklist1++;
    }
    $n1++;
  }
  if ($noceklist1 == $n1) {
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
    } else {
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