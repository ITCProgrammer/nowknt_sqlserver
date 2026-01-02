<?php
$Awal  = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir  = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Filter Data Tgl Terima Transfer</h3>

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

        <button class="btn btn-info" type="submit">Cari Data</button>
      </div>
      <!-- /.card-body -->
    </div>

    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Detail Data Benang KNT</h3>
        <a href="pages/cetak/cetaklapmasukbenang.php?awal=<?php echo $Awal; ?>&akhir=<?php echo $Akhir; ?>" class="btn bg-blue float-right" target="_blank">to Print</a>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
          <thead>
            <tr>
              <th valign="middle" style="text-align: center">No</th>
              <th valign="middle" style="text-align: center">TGL</th>
              <th valign="middle" style="text-align: center">User</th>
              <th valign="middle" style="text-align: center">No BON</th>
              <th valign="middle" style="text-align: center">KNITT</th>
              <th valign="middle" style="text-align: center">No PO</th>
              <th valign="middle" style="text-align: center">Code</th>
              <th valign="middle" style="text-align: center">Jenis Benang</th>
              <th valign="middle" style="text-align: center">Lot</th>
              <th valign="middle" style="text-align: center">Qty</th>
              <th valign="middle" style="text-align: center">Cones</th>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">Block</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $c = 0;

            $sqlDB21 = " SELECT 
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
STOCKTRANSACTION.LOTCODE,  
STOCKTRANSACTION.TRANSACTIONDATE,
SUM(ROUND(STOCKTRANSACTION.BASEPRIMARYQUANTITY,2)) AS QTY_KG1,
SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL,
SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE=STOCKTRANSACTION.ORDERCODE AND 
INTERNALDOCUMENTLINE.ORDERLINE=STOCKTRANSACTION.ORDERLINE 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENT INTERNALDOCUMENT ON INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (NOT INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE IS NULL) AND (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501') AND NOT (STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE='L02' AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501') AND
INTERNALDOCUMENTLINE.WAREHOUSECODE='M011' AND
STOCKTRANSACTION.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
AND INTERNALDOCUMENT.TEMPLATECODE='I07'
GROUP BY
STOCKTRANSACTION.LOTCODE,
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.CREATIONUSER,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION ";
            $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
            //}						  
            while ($rowdb21 = db2_fetch_assoc($stmt1)) {
              $bon = $rowdb21['INTDOCUMENTPROVISIONALCODE'] . "-" . $rowdb21['ORDERLINE'];
              if (trim($rowdb21['LOGICALWAREHOUSECODE']) == 'M501' or trim($rowdb21['LOGICALWAREHOUSECODE']) == 'M904') {
                $knitt = 'LT2';
              } else if (trim($rowdb21['LOGICALWAREHOUSECODE']) == 'P501') {
                $knitt = 'LT1';
              }
              $kdbenang = $rowdb21['SUBCODE01'] . " " . $rowdb21['SUBCODE02'] . " " . $rowdb21['SUBCODE03'] . " " . $rowdb21['SUBCODE04'] . " " . $rowdb21['SUBCODE05'] . " " . $rowdb21['SUBCODE06'] . " " . $rowdb21['SUBCODE07'] . " " . $rowdb21['SUBCODE08'];
            ?>
              <tr>
                <td style="text-align: center"><?php echo $no; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['CREATIONUSER']; ?></td>
                <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb21['INTDOCUMENTPROVISIONALCODE']) . "-" . trim($rowdb21['ORDERLINE']) . "-" . trim($rowdb21['TRANSACTIONDATE']); ?>" class="show_detail"><?php echo $bon; ?></a></td>
                <td style="text-align: center"><?php echo $knitt; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
                <td><?php echo $kdbenang; ?></td>
                <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
                <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
                <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
                <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'], 2), 2); ?></td>
                <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
              </tr>

            <?php
              $no++;
              $tRol += $rowdb21['QTY_ROL'];
              $tCones += $rowdb21['QTY_CONES'];
              $tKg += $rowdb21['QTY_KG'];
            } ?>
          </tbody>
          <tfoot>
            <tr>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td style="text-align: center">Total</td>
              <td style="text-align: right"><strong><?php echo $tRol; ?></strong></td>
              <td style="text-align: right"><strong><?php echo $tCones; ?></strong></td>
              <td style="text-align: right"><strong><?php echo number_format(round($tKg, 2), 2); ?></strong></td>
              <td>&nbsp;</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">Detail Data Benang KNK</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example3" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
          <thead>
            <tr>
              <th valign="middle" style="text-align: center">No</th>
              <th valign="middle" style="text-align: center">TGL</th>
              <th valign="middle" style="text-align: center">User</th>
              <th valign="middle" style="text-align: center">No BON</th>
              <th valign="middle" style="text-align: center">KNITT</th>
              <th valign="middle" style="text-align: center">No PO</th>
              <th valign="middle" style="text-align: center">Code</th>
              <th valign="middle" style="text-align: center">Jenis Benang</th>
              <th valign="middle" style="text-align: center">Lot</th>
              <th valign="middle" style="text-align: center">Qty</th>
              <th valign="middle" style="text-align: center">Cones</th>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">Block</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $c = 0;

            $sqlDB21 = " SELECT 
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
STOCKTRANSACTION.LOTCODE,  
STOCKTRANSACTION.TRANSACTIONDATE,
SUM(ROUND(STOCKTRANSACTION.BASEPRIMARYQUANTITY,2)) AS QTY_KG,
COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL,
SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE=STOCKTRANSACTION.ORDERCODE AND 
INTERNALDOCUMENTLINE.ORDERLINE=STOCKTRANSACTION.ORDERLINE 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENT INTERNALDOCUMENT ON INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (NOT INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE IS NULL) AND (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501') AND NOT (STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE='L02' AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501') AND
INTERNALDOCUMENTLINE.WAREHOUSECODE='M011' AND
STOCKTRANSACTION.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
AND INTERNALDOCUMENT.TEMPLATECODE='I06'
GROUP BY
STOCKTRANSACTION.LOTCODE,
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.CREATIONUSER,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION ";
            $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
            //}						  
            while ($rowdb21 = db2_fetch_assoc($stmt1)) {
              $bon = $rowdb21['INTDOCUMENTPROVISIONALCODE'] . "-" . $rowdb21['ORDERLINE'];
              if (trim($rowdb21['LOGICALWAREHOUSECODE']) == 'M501' or trim($rowdb21['LOGICALWAREHOUSECODE']) == 'M904') {
                $knitt = 'LT2';
              } else if (trim($rowdb21['LOGICALWAREHOUSECODE']) == 'P501') {
                $knitt = 'LT1';
              }
              $kdbenang = $rowdb21['SUBCODE01'] . " " . $rowdb21['SUBCODE02'] . " " . $rowdb21['SUBCODE03'] . " " . $rowdb21['SUBCODE04'] . " " . $rowdb21['SUBCODE05'] . " " . $rowdb21['SUBCODE06'] . " " . $rowdb21['SUBCODE07'] . " " . $rowdb21['SUBCODE08'];
            ?>
              <tr>
                <td style="text-align: center"><?php echo $no; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['CREATIONUSER']; ?></td>
                <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb21['INTDOCUMENTPROVISIONALCODE']) . "-" . trim($rowdb21['ORDERLINE']) . "-" . trim($rowdb21['TRANSACTIONDATE']); ?>" class="show_detail"><?php echo $bon; ?></a></td>
                <td style="text-align: center"><?php echo $knitt; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
                <td><?php echo $kdbenang; ?></td>
                <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
                <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
                <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
                <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'], 2), 2); ?></td>
                <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
              </tr>

            <?php
              $no++;
              $tRol += $rowdb21['QTY_ROL'];
              $tCones += $rowdb21['QTY_CONES'];
              $tKg += $rowdb21['QTY_KG'];
            } ?>
          </tbody>
          <tfoot>
            <tr>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td>&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td style="text-align: center">Total</td>
              <td style="text-align: right"><strong><?php echo $tRol; ?></strong></td>
              <td style="text-align: right"><strong><?php echo $tCones; ?></strong></td>
              <td style="text-align: right"><strong><?php echo number_format(round($tKg, 2), 2); ?></strong></td>
              <td>&nbsp;</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>

  </form>
</div><!-- /.container-fluid -->
<!-- /.content -->
<div id="DetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
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
if (isset($_POST['mutasikain']) && $_POST['mutasikain'] == "MutasiKain") {

  function mutasiurut($con)
  {
    $format = "20" . date("ymd");
    $sql = sqlsrv_query($con, "SELECT TOP 1 no_mutasi FROM dbknitt.tbl_mutasi_kain WHERE SUBSTRING(no_mutasi,1,8) like ? ORDER BY no_mutasi DESC", [$format . '%']);
    $d = 0;
    if ($sql && ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC))) {
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
  $nomid = mutasiurut($con);

  $params = [$Awal];
  $gshiftClause = "";
  if (isset($Gshift) && $Gshift != "" && $Gshift != "ALL") {
    $gshiftClause = " AND a.gshift=?";
    $params[] = $Gshift;
  }

  $sql1 = sqlsrv_query($con, "SELECT a.transid,count(b.transid) as jmlrol FROM dbknitt.tbl_mutasi_kain a 
LEFT JOIN dbknitt.tbl_prodemand b ON a.transid=b.transid 
WHERE a.no_mutasi IS NULL AND CONVERT(date,a.tgl_buat)=?{$gshiftClause} 
GROUP BY a.transid", $params);
  $n1 = 1;
  $noceklist1 = 1;
  while ($sql1 && ($r1 = sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC))) {
    if (isset($_POST['cek'][$n1]) && $_POST['cek'][$n1] != '') {
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