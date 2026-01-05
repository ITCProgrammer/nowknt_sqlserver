<?php
require_once "koneksi.php";
sqlsrv_query($con_nowprd, "DELETE FROM nowprd.performpetugaspengiriman WHERE CREATEDATETIME BETWEEN DATEADD(day, -3, GETDATE()) AND DATEADD(day, -1, GETDATE())");
sqlsrv_query($con_nowprd, "DELETE FROM nowprd.performpetugaspengiriman WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 

$Awal  = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir  = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';

function KPI($con_nowprd,$kd, $jns)
{
  // ini_set("error_reporting", 1);
  // require_once "koneksi.php";
  //$con_nowprd=mysqli_connect("10.0.0.10","dit","4dm1n","nowprd"); // Nanti pindahkan tarikan datanya ke DB2 saja
  // $connectionInfo = array("Database"=>"nowprd", "UID"=>"dit", "PWD"=>"4dm1n");
  // $con_nowprd = sqlsrv_connect("10.0.0.10", $connectionInfo);
  $stmt0   = sqlsrv_query($con_nowprd, "SELECT * FROM nowprd.usergenericgroup WHERE USERGENERICGROUPTYPECODE = 'OPK' AND CODE = '$kd'");
  $rowdb20 = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC);
  if ($jns == "ROL") {
    return $rowdb20['SHORTDESCRIPTION'];
  } else if ($jns == "PALET") {
    return $rowdb20['SEARCHDESCRIPTION'];
  }
  // return $kd.' '.$jns;
}
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

      </div>
      <div class="card-footer">
        <button class="btn btn-info" type="submit">Cari Data</button>
      </div>
      <!-- /.card-body -->
    </div>
    
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Detail Performance Petugas Pengiriman Kain Greige</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <?php if(isset($_POST['tgl_awal'])) : ?>
        <table id="datagridscroll5" class="table table-sm table-bordered table-striped nowrap" style="font-size: 13px; text-align: center;">
          <thead>
            <tr>
              <th rowspan="2" valign="middle" style="text-align: center">No</th>
              <th rowspan="2" valign="middle" style="text-align: center">Nama</th>
              <th colspan="3" valign="middle" style="text-align: center">KO1</th>
              <th colspan="3" valign="middle" style="text-align: center">KO2</th>
              <th colspan="3" valign="middle" style="text-align: center">KO3</th>
              <th colspan="3" valign="middle" style="text-align: center">KO4</th>
              <th colspan="3" valign="middle" style="text-align: center">KT1</th>
              <th colspan="3" valign="middle" style="text-align: center">KT2</th>
              <th colspan="3" valign="middle" style="text-align: center">KT3</th>
              <th colspan="3" valign="middle" style="text-align: center">KT4</th>
              <th colspan="3" valign="middle" style="text-align: center">KT5</th>
              <th colspan="3" valign="middle" style="text-align: center">KS1</th>
              <th colspan="3" valign="middle" style="text-align: center">KS2</th>
              <th colspan="3" valign="middle" style="text-align: center">KS3</th>
              <th colspan="3" valign="middle" style="text-align: center">KS4</th>
              <th colspan="3" valign="middle" style="text-align: center">KS5</th>
              <th colspan="2" valign="middle" style="text-align: center">QK</th>
              <th rowspan="2" valign="middle" style="text-align: center">Total Waktu</th>
              <th rowspan="2" valign="middle" style="text-align: center">Effisiensi (%)</th>
            </tr>
            <tr>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Palet</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
              <th valign="middle" style="text-align: center">Roll</th>
              <th valign="middle" style="text-align: center">Target</th>
            </tr>
          </thead>
          <tbody>
            <?php
              ini_set("error_reporting", 0);
              session_start();
              require_once "koneksi.php";

              $no = 1;
              $c = 0;

              $sql_OPK  = "SELECT
                                *
                              FROM
                                USERGENERICGROUP 
                              WHERE
                                USERGENERICGROUPTYPECODE = 'OPK'";

              $sqlDB21 = "SELECT 
  p1.NAMA,

  SUM(CASE WHEN p1.KD = 'KO1' THEN p1.TROL ELSE 0 END) AS KO1,
  SUM(CASE WHEN p1.KD = 'KO1' THEN p1.TPALET ELSE 0 END) AS KO1P,

  SUM(CASE WHEN p1.KD = 'KO2' THEN p1.TROL ELSE 0 END) AS KO2,
  SUM(CASE WHEN p1.KD = 'KO2' THEN p1.TPALET ELSE 0 END) AS KO2P,

  SUM(CASE WHEN p1.KD = 'KO3' THEN p1.TROL ELSE 0 END) AS KO3,
  SUM(CASE WHEN p1.KD = 'KO3' THEN p1.TPALET ELSE 0 END) AS KO3P,

  SUM(CASE WHEN p1.KD = 'KO4' THEN p1.TROL ELSE 0 END) AS KO4,
  SUM(CASE WHEN p1.KD = 'KO4' THEN p1.TPALET ELSE 0 END) AS KO4P,

  SUM(CASE WHEN p1.KD = 'KS1' THEN p1.TROL ELSE 0 END) AS KS1,
  SUM(CASE WHEN p1.KD = 'KS1' THEN p1.TPALET ELSE 0 END) AS KS1P,

  SUM(CASE WHEN p1.KD = 'KS2' THEN p1.TROL ELSE 0 END) AS KS2,
  SUM(CASE WHEN p1.KD = 'KS2' THEN p1.TPALET ELSE 0 END) AS KS2P,

  SUM(CASE WHEN p1.KD = 'KS3' THEN p1.TROL ELSE 0 END) AS KS3,
  SUM(CASE WHEN p1.KD = 'KS3' THEN p1.TPALET ELSE 0 END) AS KS3P,

  SUM(CASE WHEN p1.KD = 'KS4' THEN p1.TROL ELSE 0 END) AS KS4,
  SUM(CASE WHEN p1.KD = 'KS4' THEN p1.TPALET ELSE 0 END) AS KS4P,

  SUM(CASE WHEN p1.KD = 'KS5' THEN p1.TROL ELSE 0 END) AS KS5,
  SUM(CASE WHEN p1.KD = 'KS5' THEN p1.TPALET ELSE 0 END) AS KS5P,

  SUM(CASE WHEN p1.KD = 'KT1' THEN p1.TROL ELSE 0 END) AS KT1,
  SUM(CASE WHEN p1.KD = 'KT1' THEN p1.TPALET ELSE 0 END) AS KT1P,

  SUM(CASE WHEN p1.KD = 'KT2' THEN p1.TROL ELSE 0 END) AS KT2,
  SUM(CASE WHEN p1.KD = 'KT2' THEN p1.TPALET ELSE 0 END) AS KT2P,

  SUM(CASE WHEN p1.KD = 'KT3' THEN p1.TROL ELSE 0 END) AS KT3,
  SUM(CASE WHEN p1.KD = 'KT3' THEN p1.TPALET ELSE 0 END) AS KT3P,

  SUM(CASE WHEN p1.KD = 'KT4' THEN p1.TROL ELSE 0 END) AS KT4,
  SUM(CASE WHEN p1.KD = 'KT4' THEN p1.TPALET ELSE 0 END) AS KT4P,

  SUM(CASE WHEN p1.KD = 'KT5' THEN p1.TROL ELSE 0 END) AS KT5,
  SUM(CASE WHEN p1.KD = 'KT5' THEN p1.TPALET ELSE 0 END) AS KT5P,

  SUM(CASE WHEN p1.KD = 'QK' THEN p1.TROL ELSE 0 END) AS QK

FROM (
  SELECT
    SUM(p.ROL) AS TROL,
    COUNT(p.BON) AS TPALET,
    p.KD,
    p.NAMA
  FROM (
    SELECT
      CONCAT(TRIM(i2.INTDOCUMENTPROVISIONALCODE), CONCAT('-', TRIM(i2.ORDERLINE))) AS BON,
      COUNT(s.ITEMELEMENTCODE) AS ROL,
      b.VALUESTRING AS KD,
      c.VALUESTRING AS NAMA
    FROM INTERNALDOCUMENT i
    LEFT OUTER JOIN INTERNALDOCUMENTLINE i2 
      ON i.PROVISIONALCODE = i2.INTDOCUMENTPROVISIONALCODE
    LEFT OUTER JOIN STOCKTRANSACTION s 
      ON i2.INTDOCUMENTPROVISIONALCODE = s.ORDERCODE 
     AND i2.ORDERLINE = s.ORDERLINE
    LEFT OUTER JOIN ADSTORAGE b 
      ON b.UNIQUEID = i2.ABSUNIQUEID 
     AND b.NAMENAME = 'KdPengiriman'
    LEFT OUTER JOIN ADSTORAGE c 
      ON c.UNIQUEID = i2.ABSUNIQUEID 
     AND c.NAMENAME = 'NamaPetugas'
    WHERE
      s.PHYSICALWAREHOUSECODE = 'M50'
      AND NOT c.VALUESTRING IS NULL
      AND i2.INTERNALREFERENCEDATE BETWEEN '$Awal' AND '$Akhir'
    GROUP BY
      b.VALUESTRING,
      c.VALUESTRING,
      i2.INTDOCUMENTPROVISIONALCODE,
      i2.ORDERLINE
  ) p
  GROUP BY
    p.NAMA,
    p.KD
) p1
GROUP BY
  p1.NAMA;
";
              $stmt1   = db2_exec($conn1, $sqlDB21);
              while ($row_performpetugaspengiriman = db2_fetch_assoc($stmt1)) {
                $r_performpetugaspengiriman[]      = "('".$_POST['tgl_awal']."',"
                                                    ."'".$_POST['tgl_akhir']."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['NAMA']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KO1']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KO1P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KO2']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KO2P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KO3']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KO3P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KO4']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KO4P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS1']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS1P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS2']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS2P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS3']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS3P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS4']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS4P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS5']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KS5P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT1']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT1P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT2']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT2P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT3']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT3P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT4']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT4P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT5']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['KT5P']))."',"
                                                    ."'".TRIM(addslashes($row_performpetugaspengiriman['QK']))."',"
                                                    ."'".$_SERVER['REMOTE_ADDR']."',"
                                                    ."'".date('Y-m-d H:i:s')."')";
              }
              $value_performpetugaspengiriman        = implode(',', $r_performpetugaspengiriman);
                            // 1. Simpan query ke variabel string dulu (PENTING untuk debugging)
              $sql = "INSERT INTO nowprd.performpetugaspengiriman(DATE1,DATE2,NAMA,KO1,KO1P,KO2,KO2P,KO3,KO3P,KO4,KO4P,KS1,KS1P,KS2,KS2P,KS3,KS3P,KS4,KS4P,KS5,KS5P,KT1,KT1P,KT2,KT2P,KT3,KT3P,KT4,KT4P,KT5,KT5P,QK,IPADDRESS,CREATEDATETIME) VALUES $value_performpetugaspengiriman";

              // 2. Eksekusi Query
              $insert_performpetugaspengiriman = sqlsrv_query($con_nowprd, $sql);

              // 3. Validasi Error
              if ($insert_performpetugaspengiriman === false) {
                  // Ambil detail error dari driver SQL Server
                  $errors = sqlsrv_errors();
                  
                  echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 20px;'>";
                  echo "<h3>❌ Gagal Insert Data</h3>";
                  
                  if ($errors != null) {
                      echo "<b>Detail Error:</b><br>";
                      foreach ($errors as $error) {
                          echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
                          echo "Code: " . $error['code'] . "<br />";
                          echo "Message: " . $error['message'] . "<br /><hr>";
                      }
                  }

                  echo "<b>Cek Query SQL Anda (Copy paste ini ke SQL Server Management Studio untuk test):</b><br>";
                  echo "<textarea style='width:100%; height:100px;'>" . $sql . "</textarea>";
                  echo "</div>";
                  
                  // Hentikan script supaya error terlihat (opsional)
                  die(); 
              }

              $sqlDB2 = "SELECT DISTINCT * FROM nowprd.performpetugaspengiriman WHERE DATE1 = '$_POST[tgl_awal]' AND DATE2 = '$_POST[tgl_akhir]' AND IPADDRESS = '$_SERVER[REMOTE_ADDR]' ORDER BY NAMA ASC";
              $stmt   = sqlsrv_query($con_nowprd,$sqlDB2);
              while ($rowdb21 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            ?>
            <tr>
              <td style="text-align: center"><?php echo $no; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['NAMA']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KO1P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KO1']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KO1", "PALET") * $rowdb21['KO1P']) + (KPI($con_nowprd,"KO1", "ROL") * $rowdb21['KO1']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KO2P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KO2']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KO2", "PALET") * $rowdb21['KO2P']) + (KPI($con_nowprd,"KO2", "ROL") * $rowdb21['KO2']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KO3P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KO3']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KO3", "PALET") * $rowdb21['KO3P']) + (KPI($con_nowprd,"KO3", "ROL") * $rowdb21['KO3']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KO4P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KO4']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KO4", "PALET") * $rowdb21['KO4P']) + (KPI($con_nowprd,"KO4", "ROL") * $rowdb21['KO4']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT1P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT1']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KT1", "PALET") * $rowdb21['KT1P']) + (KPI($con_nowprd,"KT1", "ROL") * $rowdb21['KT1']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT2P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT2']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KT2", "PALET") * $rowdb21['KT2P']) + (KPI($con_nowprd,"KT2", "ROL") * $rowdb21['KT2']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT3P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT3']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KT3", "PALET") * $rowdb21['KT3P']) + (KPI($con_nowprd,"KT3", "ROL") * $rowdb21['KT3']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT4P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT4']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KT4", "PALET") * $rowdb21['KT4P']) + (KPI($con_nowprd,"KT4", "ROL") * $rowdb21['KT4']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT5P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KT5']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KT5", "PALET") * $rowdb21['KT5P']) + (KPI($con_nowprd,"KT5", "ROL") * $rowdb21['KT5']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS1P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS1']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KS1", "PALET") * $rowdb21['KS1P']) + (KPI($con_nowprd,"KS1", "ROL") * $rowdb21['KS1']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS2P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS2']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KS2", "PALET") * $rowdb21['KS2P']) + (KPI($con_nowprd,"KS2", "ROL") * $rowdb21['KS2']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS3P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS3']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KS3", "PALET") * $rowdb21['KS3P']) + (KPI($con_nowprd,"KS3", "ROL") * $rowdb21['KS3']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS4P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS4']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KS4", "PALET") * $rowdb21['KS4P']) + (KPI($con_nowprd,"KS4", "ROL") * $rowdb21['KS4']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS5P']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['KS5']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"KS5", "PALET") * $rowdb21['KS5P']) + (KPI($con_nowprd,"KS5", "ROL") * $rowdb21['KS5']); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['QK']; ?></td>
              <td style="text-align: center"><?php echo (KPI($con_nowprd,"QK", "ROL") * $rowdb21['QK']); ?></td>
              <td style="text-align: center"><?php
echo
    ((KPI($con_nowprd,"KO1", "PALET") * $rowdb21['KO1P']) + (KPI($con_nowprd,"KO1", "ROL") * $rowdb21['KO1'])) +
    ((KPI($con_nowprd,"KO2", "PALET") * $rowdb21['KO2P']) + (KPI($con_nowprd,"KO2", "ROL") * $rowdb21['KO2'])) +
    ((KPI($con_nowprd,"KO3", "PALET") * $rowdb21['KO3P']) + (KPI($con_nowprd,"KO3", "ROL") * $rowdb21['KO3'])) +
    ((KPI($con_nowprd,"KO4", "PALET") * $rowdb21['KO4P']) + (KPI($con_nowprd,"KO4", "ROL") * $rowdb21['KO4'])) +

    ((KPI($con_nowprd,"KT1", "PALET") * $rowdb21['KT1P']) + (KPI($con_nowprd,"KT1", "ROL") * $rowdb21['KT1'])) +
    ((KPI($con_nowprd,"KT2", "PALET") * $rowdb21['KT2P']) + (KPI($con_nowprd,"KT2", "ROL") * $rowdb21['KT2'])) +
    ((KPI($con_nowprd,"KT3", "PALET") * $rowdb21['KT3P']) + (KPI($con_nowprd,"KT3", "ROL") * $rowdb21['KT3'])) +
    ((KPI($con_nowprd,"KT4", "PALET") * $rowdb21['KT4P']) + (KPI($con_nowprd,"KT4", "ROL") * $rowdb21['KT4'])) +
    ((KPI($con_nowprd,"KT5", "PALET") * $rowdb21['KT5P']) + (KPI($con_nowprd,"KT5", "ROL") * $rowdb21['KT5'])) +

    ((KPI($con_nowprd,"KS1", "PALET") * $rowdb21['KS1P']) + (KPI($con_nowprd,"KS1", "ROL") * $rowdb21['KS1'])) +
    ((KPI($con_nowprd,"KS2", "PALET") * $rowdb21['KS2P']) + (KPI($con_nowprd,"KS2", "ROL") * $rowdb21['KS2'])) +
    ((KPI($con_nowprd,"KS3", "PALET") * $rowdb21['KS3P']) + (KPI($con_nowprd,"KS3", "ROL") * $rowdb21['KS3'])) +
    ((KPI($con_nowprd,"KS4", "PALET") * $rowdb21['KS4P']) + (KPI($con_nowprd,"KS4", "ROL") * $rowdb21['KS4'])) +
    ((KPI($con_nowprd,"KS5", "PALET") * $rowdb21['KS5P']) + (KPI($con_nowprd,"KS5", "ROL") * $rowdb21['KS5'])) +

    (KPI($con_nowprd,"QK", "ROL") * $rowdb21['QK']);
?></td>
              <td><span style="text-align: center"><?php
echo round(
    (
        (
            (
                ((KPI($con_nowprd,"KO1", "PALET") * $rowdb21['KO1P']) + (KPI($con_nowprd,"KO1", "ROL") * $rowdb21['KO1'])) +
                ((KPI($con_nowprd,"KO2", "PALET") * $rowdb21['KO2P']) + (KPI($con_nowprd,"KO2", "ROL") * $rowdb21['KO2'])) +
                ((KPI($con_nowprd,"KO3", "PALET") * $rowdb21['KO3P']) + (KPI($con_nowprd,"KO3", "ROL") * $rowdb21['KO3'])) +
                ((KPI($con_nowprd,"KO4", "PALET") * $rowdb21['KO4P']) + (KPI($con_nowprd,"KO4", "ROL") * $rowdb21['KO4'])) +

                ((KPI($con_nowprd,"KT1", "PALET") * $rowdb21['KT1P']) + (KPI($con_nowprd,"KT1", "ROL") * $rowdb21['KT1'])) +
                ((KPI($con_nowprd,"KT2", "PALET") * $rowdb21['KT2P']) + (KPI($con_nowprd,"KT2", "ROL") * $rowdb21['KT2'])) +
                ((KPI($con_nowprd,"KT3", "PALET") * $rowdb21['KT3P']) + (KPI($con_nowprd,"KT3", "ROL") * $rowdb21['KT3'])) +
                ((KPI($con_nowprd,"KT4", "PALET") * $rowdb21['KT4P']) + (KPI($con_nowprd,"KT4", "ROL") * $rowdb21['KT4'])) +
                ((KPI($con_nowprd,"KT5", "PALET") * $rowdb21['KT5P']) + (KPI($con_nowprd,"KT5", "ROL") * $rowdb21['KT5'])) +

                ((KPI($con_nowprd,"KS1", "PALET") * $rowdb21['KS1P']) + (KPI($con_nowprd,"KS1", "ROL") * $rowdb21['KS1'])) +
                ((KPI($con_nowprd,"KS2", "PALET") * $rowdb21['KS2P']) + (KPI($con_nowprd,"KS2", "ROL") * $rowdb21['KS2'])) +
                ((KPI($con_nowprd,"KS3", "PALET") * $rowdb21['KS3P']) + (KPI($con_nowprd,"KS3", "ROL") * $rowdb21['KS3'])) +
                ((KPI($con_nowprd,"KS4", "PALET") * $rowdb21['KS4P']) + (KPI($con_nowprd,"KS4", "ROL") * $rowdb21['KS4'])) +
                ((KPI($con_nowprd,"KS5", "PALET") * $rowdb21['KS5P']) + (KPI($con_nowprd,"KS5", "ROL") * $rowdb21['KS5'])) +

                (KPI($con_nowprd,"QK", "ROL") * $rowdb21['QK'])
            ) / 420
        ) * 100
    )
);
?>
</span></td>
            </tr>
            <?php $no++; } ?>
          </tbody>
        </table>
        <?php endif; ?>
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
    $sql = sqlsrv_query($con, "SELECT TOP 1 no_mutasi FROM dbknitt.tbl_mutasi_kain WHERE SUBSTRING(no_mutasi,1,8) LIKE '%" . $format . "%' ORDER BY no_mutasi DESC");
    if ($sql && $r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
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