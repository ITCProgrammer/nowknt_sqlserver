<?php
$Awal  = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir  = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Shift  = isset($_POST['shift']) ? $_POST['shift'] : '';
$Gshift = $Shift;
?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Filter Data Tgl Masuk Inspeksi</h3>

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
          <label for="shift" class="col-md-1">Shift</label>
          <div class="col-md-2">
            <select name="shift" class="form-control form-control-sm" id="shift" required>
              <option value="">Pilih</option>
              <option value="ALL" <?php if ($Shift == "ALL") {
                                    echo "SELECTED";
                                  } ?>>ALL</option>
              <option value="1" <?php if ($Shift == "1") {
                                  echo "SELECTED";
                                } ?>>1</option>
              <option value="2" <?php if ($Shift == "2") {
                                  echo "SELECTED";
                                } ?>>2</option>
              <option value="3" <?php if ($Shift == "3") {
                                  echo "SELECTED";
                                } ?>>3</option>
            </select>
          </div>
        </div>
        <button class="btn btn-info" type="submit">Cari Data</button>
      </div>
      <!-- /.card-body -->
    </div>
    <?php if ($Awal != "") { ?>
      <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title">Detail Data Kain Greige</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="example12" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
            <thead>
              <tr>
                <th valign="middle" style="text-align: center">No</th>
                <th valign="middle" style="text-align: center">TGL</th>
                <th valign="middle" style="text-align: center">Shift</th>
                <th valign="middle" style="text-align: center">UserId</th>
                <th valign="middle" style="text-align: center">Nama</th>
                <th valign="middle" style="text-align: center">DemandNo</th>
                <th valign="middle" style="text-align: center">No PO</th>
                <th valign="middle" style="text-align: center">Code</th>
                <th valign="middle" style="text-align: center">LOT</th>
                <th valign="middle" style="text-align: center">Jenis Kain</th>
                <th valign="middle" style="text-align: center">Qty</th>
                <th valign="middle" style="text-align: center">NoRol</th>
                <th valign="middle" style="text-align: center">Berat/Kg</th>
                <th valign="middle" style="text-align: center">Mesin</th>
                <th valign="middle" style="text-align: center">No Mesin</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $c = 0;
              if ($Shift == "1") {
                $wkt = " AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-07:00:00' AND '$Akhir-15:00:00' ";
              } elseif ($Shift == "2") {
                $wkt = " AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-15:00:00' AND '$Akhir-23:00:00' ";
              } elseif ($Shift == "3") {
                $wkt = " AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-23:00:00' AND '$Akhir-07:00:00' ";
              } elseif ($Shift == "ALL") {
                $wkt = " AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-07:00:00' AND '$Akhir-07:00:00' ";
              } elseif ($Shift == "") {
                $wkt = " AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) BETWEEN '2021-01-01' AND '2021-01-01' ";
              }
              $sqlDB21 = " SELECT OPERATORCODE, INITIALS.LONGDESCRIPTION, SUBSTR(INSPECTIONSTARTDATETIME,1,10) AS TGL, SUBSTR(INSPECTIONSTARTDATETIME,12,8) AS JAM, 
	DEMANDCODE, COUNT(DEMANDCODE) AS JML,SUM(WEIGHTNET) AS KGS,ADSTORAGE.VALUESTRING AS NO_MESIN,SUBSTR(ELEMENTCODE,9,5) AS NROL  
	FROM ELEMENTSINSPECTION
LEFT OUTER JOIN DB2ADMIN.INITIALS ON INITIALS.CODE=ELEMENTSINSPECTION.OPERATORCODE	
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'	
	WHERE ELEMENTITEMTYPECODE='KGF' $wkt 
GROUP BY SUBSTR(INSPECTIONSTARTDATETIME,1,10),SUBSTR(INSPECTIONSTARTDATETIME,12,8),DEMANDCODE,OPERATORCODE,INITIALS.LONGDESCRIPTION,ADSTORAGE.VALUESTRING,ELEMENTCODE ";
              $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
              //}	
              $McNo = "";
              while ($rowdb21 = db2_fetch_assoc($stmt1)) {
                if (trim($rowdb21['LOGICALWAREHOUSECODE']) == 'M904') {
                  $knitt = 'LT2';
                } else if (trim($rowdb21['LOGICALWAREHOUSECODE']) == 'P501') {
                  $knitt = 'LT1';
                }

                $sqlDB22 = " SELECT TRANSACTIONDATE,TRANSACTIONTIME FROM STOCKTRANSACTION WHERE STOCKTRANSACTION.ITEMTYPECODE ='GYR' and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.RETURNTRANSACTION ='1' AND STOCKTRANSACTION.TRANSACTIONDATE='$rowdb21[TRANSACTIONDATE]' 
AND STOCKTRANSACTION.ORDERCODE='$rowdb21[ORDERCODE]' AND STOCKTRANSACTION.CREATIONUSER='$rowdb21[CREATIONUSER]' ";
                $stmt2   = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
                $rowdb22 = db2_fetch_assoc($stmt2);
                if ($rowdb21['JAM'] >= "07.00.00" and $rowdb21['JAM'] <= "15.00.00") {
                  $shf = "1";
                } elseif ($rowdb21['JAM'] >= "15.00.00" and $rowdb21['JAM'] <= "23.00.00") {
                  $shf = "2";
                } else {
                  $shf = "3";
                }
                $sqlDB23 = " SELECT *,CURRENT_TIMESTAMP AS TGLS,
CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END  AS PROJECT FROM ITXVIEWHEADERKNTORDER WHERE ITEMTYPEAFICODE ='KGF' AND CODE ='" . $rowdb21['DEMANDCODE'] . "' ";
                $stmt3   = db2_exec($conn1, $sqlDB23, array('cursor' => DB2_SCROLLABLE));
                $rowdb23 = db2_fetch_assoc($stmt3);
                $kdkain = trim($rowdb23['SUBCODE02']) . "" . trim($rowdb23['SUBCODE03']) . " " . trim($rowdb23['SUBCODE04']);
                $McNo = $rowdb21['NO_MESIN'];
                $sqlKt = sqlsrv_query($con, "SELECT TOP 1 no_mesin FROM dbknitt.tbl_mesin WHERE kd_dtex=?", [$McNo]);
                $rk = $sqlKt ? sqlsrv_fetch_array($sqlKt, SQLSRV_FETCH_ASSOC) : [];
                if ($rowdb21['LONGDESCRIPTION'] != "") {
                  $uid = trim($rowdb21['LONGDESCRIPTION']);
                } else {
                  $uid = trim($rowdb21['CREATIONUSER']);
                }

              ?>
                <tr>
                  <td style="text-align: center"><?php echo $no; ?></td>
                  <td style="text-align: center"><?php echo $rowdb21['TGL']; ?></td>
                  <td style="text-align: center"><?php echo $shf; ?></td>
                  <td style="text-align: center"><?php echo $rowdb21['OPERATORCODE']; ?></td>
                  <td style="text-align: center"><?php echo $rowdb21['LONGDESCRIPTION']; ?></td>
                  <td style="text-align: center"><?php echo $rowdb21['DEMANDCODE']; ?></td>
                  <td style="text-align: center"><?php echo $rowdb23['PROJECT']; ?></td>
                  <td><?php echo $kdkain; ?></td>
                  <td style="text-align: center"><?php echo $rowdb23['PRODUCTIONORDERCODE']; ?></td>
                  <td style="text-align: left"><?php echo $rowdb23['SUMMARIZEDDESCRIPTION']; ?></td>
                  <td style="text-align: center"><?php echo $rowdb21['JML']; ?></td>
                  <td style="text-align: center"><?php echo $rowdb21['NROL']; ?></td>
                  <td style="text-align: right"><?php echo number_format(round($rowdb21['KGS'], 2), 2); ?></td>
                  <td><?php echo $McNo; ?></td>
                  <td><?php echo $rk['no_mesin']; ?></td>
                </tr>
              <?php
                $no++;
              } ?>
            </tbody>

          </table>
        </div>
        <!-- /.card-body -->
      </div>
    <?php } ?>
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

  function mutasiurut($con)
  {
    $format = "20" . date("ymd");
    $sql = sqlsrv_query(
      $con,
      "SELECT TOP 1 no_mutasi FROM dbknitt.tbl_mutasi_kain WHERE SUBSTRING(no_mutasi,1,8) LIKE ? ORDER BY no_mutasi DESC",
      [$format . '%']
    );
    $Urut = 0;
    if ($sql && ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC))) {
      $str = substr($r['no_mutasi'], 8, 2);
      $Urut = (int)$str;
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
  $nomid = mutasiurut($con);

  $params = [$Awal];
  $gshiftClause = "";
  if ($Gshift !== "" && $Gshift !== "ALL") {
    $gshiftClause = " AND a.gshift=?";
    $params[] = $Gshift;
  }
  $sql1 = sqlsrv_query(
    $con,
    "SELECT a.transid, COUNT(b.transid) AS jmlrol
     FROM dbknitt.tbl_mutasi_kain a
     LEFT JOIN dbknitt.tbl_prodemand b ON a.transid=b.transid
     WHERE a.no_mutasi IS NULL AND CONVERT(date,a.tgl_buat)=?{$gshiftClause}
     GROUP BY a.transid",
    $params
  );
  $n1 = 1;
  $noceklist1 = 1;
  while ($sql1 && ($r1 = sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC))) {
    if ($_POST['cek'][$n1] != '') {
      $transid1 = $_POST['cek'][$n1];
      sqlsrv_query(
        $con,
        "UPDATE dbknitt.tbl_mutasi_kain SET
		no_mutasi=?,
		tgl_mutasi=GETDATE()
		WHERE transid=?",
        [$nomid, $transid1]
      );
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
