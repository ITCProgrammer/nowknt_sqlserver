<?php
$Zone       = isset($_POST['zone']) ? trim($_POST['zone']) : '';
$Lokasi     = isset($_POST['lokasi']) ? trim($_POST['lokasi']) : '';
$Barcode    = isset($_POST['barcode']) ? substr($_POST['barcode'], -13) : '';
$actionCek  = isset($_POST['cek']) ? $_POST['cek'] : '';
$actionCari = isset($_POST['cari']) ? $_POST['cari'] : '';
$saveZone   = isset($_POST['simpan_zone']) ? $_POST['simpan_zone'] : '';
$saveLokasi = isset($_POST['simpan_lokasi']) ? $_POST['simpan_lokasi'] : '';

$likeLokasi = $Lokasi !== '' ? $Lokasi . '%' : '%';

// Helper ambil list master dari data stok (karena tabel master tidak ada di SQL Server)
function getDistinctOptions($con, $field, $zone = null)
{
  if ($field === 'zone') {
    $stmt = sqlsrv_query($con, "SELECT DISTINCT zone AS nama FROM dbknitt.tbl_stokfull ORDER BY zone");
  } else {
    $stmt = sqlsrv_query($con, "SELECT DISTINCT lokasi AS nama FROM dbknitt.tbl_stokfull WHERE zone=? ORDER BY lokasi", [$zone]);
  }
  $data = [];
  if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $data[] = $row['nama'];
    }
  }
  return $data;
}

function insertStokLoss($con, $data)
{
  sqlsrv_query(
    $con,
    "INSERT INTO dbknitt.tbl_stokloss (lokasi, lokasi_asli, lot, KG, zone, SN, tgl_masuk, id_upload, tgl_cek)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, GETDATE())",
    [
      $data['lokasi'],
      $data['lokasi_asli'],
      $data['lot'],
      $data['KG'],
      $data['zone'],
      $data['SN'],
      $data['tgl_masuk'],
      $data['id_upload']
    ]
  );
}

// Simpan master zone/lokasi (via modal)
// Tombol tambah dinonaktifkan sementara karena master tbl_zone/tbl_lokasi belum tersedia di SQL Server

// Data dari DB2 (lokasi real)
$cekZoneNOW   = '';
$cekLokasiNOW = '';
$tglMasukNow  = '';
$kgNowDb2     = null;
if ($Barcode !== '') {
  $sqlDB21Cek = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, CREATIONDATETIME,BASEPRIMARYQUANTITYUNIT FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' OR b.ITEMTYPECODE='DYR') AND b.LOGICALWAREHOUSECODE IN ('M904','P501') AND b.ELEMENTSCODE='$Barcode' ";
  $stmt1Cek   = db2_exec($conn1, $sqlDB21Cek, array('cursor' => DB2_SCROLLABLE));
  $rowdb21Cek = $stmt1Cek ? db2_fetch_assoc($stmt1Cek) : [];
  $cekZoneNOW = trim($rowdb21Cek['WHSLOCATIONWAREHOUSEZONECODE']);
  $cekLokasiNOW = trim($rowdb21Cek['WAREHOUSELOCATIONCODE']);
  $tglMasukNow = substr($rowdb21Cek['CREATIONDATETIME'], 0, 10);
  $kgNowDb2 = isset($rowdb21Cek['BASEPRIMARYQUANTITYUNIT']) ? round($rowdb21Cek['BASEPRIMARYQUANTITYUNIT'], 2) : null;
}

// Proses scan/check
if ($actionCek === "Cek" || $actionCari === "Cari") {
    $ck = ['jml' => 0];
    if ($Barcode !== '') {
      $stmtCnt = sqlsrv_query($con, "SELECT COUNT(*) AS jml FROM dbknitt.tbl_stokfull WHERE SN=?", [$Barcode]);
      if ($stmtCnt) {
        $rowCnt = sqlsrv_fetch_array($stmtCnt, SQLSRV_FETCH_ASSOC);
        $ck['jml'] = (int)($rowCnt['jml'] ?? 0);
      }
      $stmtDet = sqlsrv_query($con, "SELECT TOP 1 zone, lokasi, cones, KG, lot, id_upload, status FROM dbknitt.tbl_stokfull WHERE SN=?", [$Barcode]);
      if ($stmtDet) {
        $detail = sqlsrv_fetch_array($stmtDet, SQLSRV_FETCH_ASSOC);
        if ($detail) {
          $ck = array_merge($ck, $detail);
          $Zone = $detail['zone'] ?? $Zone;
          $Lokasi = $detail['lokasi'] ?? $Lokasi;
        }
      }
    }

    $likeLokasi = $Lokasi !== '' ? $Lokasi . '%' : '%';
    $ck0U = ['jml' => 0, 'id_upload' => null];
    $stmtCk0U = sqlsrv_query($con, "SELECT COUNT(*) AS jml, MAX(id_upload) AS id_upload FROM dbknitt.tbl_stokfull WHERE zone=? AND lokasi LIKE ?", [$Zone, $likeLokasi]);
    if ($stmtCk0U) {
      $ck0U = sqlsrv_fetch_array($stmtCk0U, SQLSRV_FETCH_ASSOC) ?: $ck0U;
    }

    $ck0 = [];
    $stmtCk0 = sqlsrv_query($con, "SELECT TOP 1 * FROM dbknitt.tbl_stokfull WHERE zone=? AND lokasi LIKE ? AND SN=?", [$Zone, $likeLokasi, $Barcode]);
    if ($stmtCk0) {
      $ck0 = sqlsrv_fetch_array($stmtCk0, SQLSRV_FETCH_ASSOC) ?: $ck0;
    }

    if ($Zone === "" && $Lokasi === "") {
      echo "<script>alert('Zone atau Lokasi belum dipilih');</script>";
    } elseif (is_numeric(trim($Barcode)) === true && $Barcode != "" && strlen($Barcode) == 13 && (substr($Barcode, 0, 2) == "15" || substr($Barcode, 0, 2) == "16" ||
    substr($Barcode, 0, 2) == "17" || substr($Barcode, 0, 2) == "18" ||
    substr($Barcode, 0, 2) == "19" || substr($Barcode, 0, 2) == "20" ||
    substr($Barcode, 0, 2) == "21" || substr($Barcode, 0, 2) == "22" ||
    substr($Barcode, 0, 2) == "23")) {
    echo "<script>alert('SN Legacy');</script>";
  } elseif ($Barcode != "" && strlen($Barcode) == 13 && ($cekZoneNOW !== '' && ($cekZoneNOW != $Zone || $cekLokasiNOW != $Lokasi)) && !empty($ck0U['id_upload'])) {
    $LokasiAsl = ($ck['zone'] ?? '') . "-" . ($ck['lokasi'] ?? '');
    insertStokLoss($con, [
      'lokasi' => $Lokasi,
      'lokasi_asli' => $LokasiAsl,
      'lot' => $ck['lot'] ?? '',
      'KG' => $ck['kg'] ?? 0,
      'zone' => $Zone,
      'SN' => $Barcode,
      'tgl_masuk' => $tglMasukNow,
      'id_upload' => $ck0U['id_upload']
    ]);
    echo "<script>alert('Zone atau Lokasi Berbeda, NOW ($cekZoneNOW-$cekLokasiNOW)');</script>";
  } elseif ($Barcode != "" && strlen($Barcode) == 13 && (($ck['zone'] ?? '') != $Zone || ($ck['lokasi'] ?? '') != $Lokasi) && !empty($ck0U['id_upload'])) {
    $LokasiAsl = ($ck['zone'] ?? '') . "-" . ($ck['lokasi'] ?? '');
    insertStokLoss($con, [
      'lokasi' => $Lokasi,
      'lokasi_asli' => $LokasiAsl,
      'lot' => $ck['lot'] ?? '',
      'KG' => $ck['kg'] ?? 0,
      'zone' => $Zone,
      'SN' => $Barcode,
      'tgl_masuk' => $tglMasukNow,
      'id_upload' => $ck0U['id_upload']
    ]);
    echo "<script>alert('SN lokasi Tidak sama dengan yg diupload ($LokasiAsl)');</script>";
  } elseif ($Barcode != "" && strlen($Barcode) == 13 && (substr($Barcode, 0, 2) == "00" || substr($Barcode, 0, 3) == "000" ||
    substr($Barcode, 0, 2) == "70" || substr($Barcode, 0, 2) == "80" ||
    substr($Barcode, 0, 2) == "90") && ($ck['zone'] ?? '') == $Zone && ($ck['lokasi'] ?? '') == $Lokasi) {
    if (($ck0['status'] ?? '') == "ok") {
      echo "<script>alert('SN Ok dan sudah pernah discan');</script>";
    } elseif ($ck['jml'] > 0) {
      sqlsrv_query($con, "UPDATE dbknitt.tbl_stokfull SET 
		  status='ok',
		  tgl_cek=GETDATE()
		  WHERE zone=? AND lokasi LIKE ? AND SN=?", [$Zone, $likeLokasi, $Barcode]);
    } else {
      $sqlDB21 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, CREATIONDATETIME,BASEPRIMARYQUANTITYUNIT FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' OR b.ITEMTYPECODE='DYR') AND b.LOGICALWAREHOUSECODE IN ('M904','P501') AND b.ELEMENTSCODE='$Barcode' ";
      $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
      $rowdb21 = $stmt1 ? db2_fetch_assoc($stmt1) : [];
      $lokasiAsli = trim($rowdb21['WHSLOCATIONWAREHOUSEZONECODE']) . "-" . trim($rowdb21['WAREHOUSELOCATIONCODE']);
      $tglMasuk = substr($rowdb21['CREATIONDATETIME'], 0, 10);
      $KGnow = isset($rowdb21['BASEPRIMARYQUANTITYUNIT']) ? round($rowdb21['BASEPRIMARYQUANTITYUNIT'], 2) : 0;
      if ($lokasiAsli != "-") {
        echo "<script>alert('Data Dus ini dilokasi $lokasiAsli');</script>";
      } else {
        echo "<script>alert('SN tidak OK');</script>";
      }
      if (!empty($ck0U['id_upload'])) {
        insertStokLoss($con, [
          'lokasi' => $Lokasi,
          'lokasi_asli' => $lokasiAsli,
          'KG' => $KGnow,
          'lot' => '',
          'zone' => $Zone,
          'SN' => $Barcode,
          'tgl_masuk' => $tglMasuk,
          'id_upload' => $ck0U['id_upload']
        ]);
      }
      $stmtCkOpen = sqlsrv_query($con, "SELECT COUNT(*) AS jml, sf.id_upload FROM dbknitt.tbl_stokfull sf
	LEFT JOIN dbknitt.tbl_upload tu ON tu.id=sf.id_upload  
	WHERE tu.status='Open' AND SN=? GROUP BY sf.id_upload", [$Barcode]);
      $ck1 = $stmtCkOpen ? sqlsrv_fetch_array($stmtCkOpen, SQLSRV_FETCH_ASSOC) : ['jml' => 0, 'id_upload' => null];
      if (($ck1['jml'] ?? 0) > 0) {
        sqlsrv_query($con, "UPDATE dbknitt.tbl_stokfull SET 
		  status='ok',
		  zone=?,
		  lokasi=?,
		  tgl_cek=GETDATE()
		  WHERE id_upload=? AND SN=?", [$Zone, $Lokasi, $ck1['id_upload'], $Barcode]);
      }
    }
  } elseif ($Barcode === "") {
    // barcode kosong
  } else {
    echo "<script>alert('SN tidak ditemukan Atau SN Legacy');</script>";
  }
}

$stmtCk1 = sqlsrv_query($con, "SELECT COUNT(*) as jml FROM	dbknitt.tbl_stokfull WHERE status='ok' and zone=? AND lokasi LIKE ?", [$Zone, $likeLokasi]);
$ck1 = $stmtCk1 ? sqlsrv_fetch_array($stmtCk1, SQLSRV_FETCH_ASSOC) : ['jml' => 0];
$stmtCk2 = sqlsrv_query($con, "SELECT COUNT(*) as jml FROM	dbknitt.tbl_stokfull WHERE status='belum cek' and zone=? AND lokasi LIKE ?", [$Zone, $likeLokasi]);
$ck2 = $stmtCk2 ? sqlsrv_fetch_array($stmtCk2, SQLSRV_FETCH_ASSOC) : ['jml' => 0];
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
                <?php $sqlZ=sqlsrv_query($con," SELECT DISTINCT nama FROM dbknitt.tbl_zone order by nama ASC"); 
                  while($rZ=sqlsrv_fetch_array($sqlZ, SQLSRV_FETCH_ASSOC)){
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
          <label for="lokasi" class="col-md-1">Location <?=  $Lokasi  ?></label>
          <div class="input-group input-group-sm">
            <select class="form-control select2bs4" style="width: 80%;" name="lokasi">
              <option value="">Pilih</option>
              <?php $sqlL=sqlsrv_query($con," SELECT DISTINCT TRIM(nama) AS nama FROM dbknitt.tbl_lokasi WHERE zone='$Zone' order by nama ASC"); 
                while($rL=sqlsrv_fetch_array($sqlL, SQLSRV_FETCH_ASSOC)){
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
        <strong style="font-size: 11px;">Stok OK Sesuai Tempat</strong> <small class='badge badge-success'> <?php echo $ck1['jml']; ?> dus </small> <strong style="font-size: 11px;">Stok belum Cek</strong> <small class='badge badge-danger'> <?php echo $ck2['jml']; ?> dus</small>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="form-group row">
          <label for="barcode" class="col-md-1">Barcode</label>
          <input type="text" class="form-control" name="barcode" placeholder="SN / Elements" id="barcode" on autofocus>
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
      <strong style="font-size: 11px;">Stok OK Sesuai Tempat</strong> <small class='badge badge-success'> <?php echo $ck1['jml']; ?> dus </small>
      <strong style="font-size: 11px;">Stok belum Cek</strong> <small class='badge badge-danger'> <?php echo $ck2['jml']; ?> dus </small>
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
          if ($Zone != "" || $Lokasi != "") {
            $sql = sqlsrv_query($con, " SELECT * FROM dbknitt.tbl_stokfull WHERE zone=? AND lokasi LIKE ?", [$Zone, $likeLokasi]);
            while ($rowd = $sql ? sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC) : null) {
              $sqlDB22 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, LOTCODE FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' or b.ITEMTYPECODE='DYR') AND b.ELEMENTSCODE='$rowd[SN]' ";
              $stmt2   = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
              $rowdb22 = $stmt2 ? db2_fetch_assoc($stmt2) : [];
              $lokasiBalance = trim($rowdb22['WHSLOCATIONWAREHOUSEZONECODE']) . "-" . trim($rowdb22['WAREHOUSELOCATIONCODE']);
          ?>
              <tr>
                <td style="text-align: center"><?php echo $rowd['SN']; ?></td>
                <td style="text-align: right"><?php echo $rowd['cones']; ?></td>
                <td style="text-align: center"><?php echo $rowd['jenis_benang']; ?></td>
                <td style="text-align: right"><?php echo $rowd['KG']; ?></td>
                <td style="text-align: center"><small class='badge <?php if ($rowd['status'] == "ok") {
                                                                      echo "badge-success";
                                                                    } else if ($rowd['status'] == "belum cek") {
                                                                      echo "badge-danger";
                                                                    } ?>'> <?php echo $rowd['status']; ?></small></td>
                <td style="text-align: center"><?php echo $rowd['zone'] . "-" . $rowd['lokasi']; ?></td>
                <td style="text-align: center"><?php echo $lokasiBalance; ?></td>
                <td style="text-align: center"><?php echo $rowdb22['LOTCODE']; ?></td>
                <td style="text-align: center"><small class='badge <?php if ($lokasiBalance != "-") {
                                                                      echo "badge-success";
                                                                    } else if ($lokasiBalance == "-") {
                                                                      echo "badge-danger";
                                                                    } ?>'> <?php if ($lokasiBalance != "-") {
                                                                                                                                                                                echo "masih ada";
                                                                                                                                                                              } else if ($lokasiBalance == "-") {
                                                                                                                                                                                echo "sudah keluar";
                                                                                                                                                                              } ?></small></td>
              </tr>
          <?php
            }
          }
          ?>
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
          if ($Zone != "" || $Lokasi != "") {
            $sql1 = sqlsrv_query($con, " SELECT SN, MAX(KG) AS KG, MAX(zone) AS zone, MAX(lokasi) AS lokasi, MAX(lokasi_asli) AS lokasi_asli, MAX(tgl_masuk) AS tgl_masuk, MAX(status) AS status, COUNT(*) as jmlscn FROM dbknitt.tbl_stokloss WHERE zone=? AND lokasi LIKE ?  group by SN", [$Zone, $likeLokasi]);
            while ($rowd1 = $sql1 ? sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC) : null) {
              if (strlen($rowd1['SN']) != "13") {
                $ketSN = "jumlah Karakter di SN tidak Sesuai";
              } else {
                $ketSN = "";
              }
              if ($rowd1['jmlscn'] > 1) {
                $ketSCN = "Jumlah Scan " . $rowd1['jmlscn'] . " kali";
              } else {
                $ketSCN = "";
              }
              if ($rowd1['tgl_masuk'] == "0000-00-00" or $rowd1['tgl_masuk'] == "") {
                $tglmsk = "";
              } else {
                $tglmsk = $rowd1['tgl_masuk'];
              }
              $sqlDB22 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, LOTCODE FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' or b.ITEMTYPECODE='DYR') AND b.ELEMENTSCODE='$rowd1[SN]' ";
              $stmt2   = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
              $rowdb22 = $stmt2 ? db2_fetch_assoc($stmt2) : [];
              $lokasiBalance = trim($rowdb22['WHSLOCATIONWAREHOUSEZONECODE']) . "-" . trim($rowdb22['WAREHOUSELOCATIONCODE']);
          ?>
              <tr>
                <td style="text-align: center"><?php echo $rowd1['SN']; ?></td>
                <td style="text-align: center"><?php echo $rowd1['KG']; ?></td>
                <td style="text-align: center"><?php echo $rowd1['zone'] . "-" . $rowd1['lokasi']; ?></td>
                <td style="text-align: center"><?php echo $rowd1['lokasi_asli']; ?></td>
                <td style="text-align: center"><?php echo $lokasiBalance; ?></td>
                <td style="text-align: center"><?php echo $tglmsk; ?></td>
                <td style="text-align: center"><?php echo $rowdb22['LOTCODE']; ?></td>
                <td style="text-align: center"><small class='badge <?php if ($rowd1['status'] == "tidak ok") {
                                                                      echo "badge-warning";
                                                                    } ?>'><i class='fas fa-exclamation-triangle text-default blink_me'></i> <?php echo $rowd1['status']; ?></small> <?php echo $ketSN . ", " . $ketSCN; ?> </td>
              </tr>
          <?php
            }
          }
          ?>
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
          <input type="submit" value="Save changes" name="simpan_zone" class="btn btn-primary">
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
                <?php $sqlZ = sqlsrv_query($con, " SELECT * FROM dbknitt.tbl_zone order by nama ASC");
                while ($rZ = $sqlZ ? sqlsrv_fetch_array($sqlZ, SQLSRV_FETCH_ASSOC) : null) {
                ?>
                  <option value="<?php echo $rZ['nama']; ?>"><?php echo $rZ['nama']; ?></option>
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
          <input type="submit" value="Save changes" name="simpan_lokasi" class="btn btn-primary">
        </div>
      </div>
    </form>
  </div>
  <!-- /.modal-content -->
</div>
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
