<?php
$ProdOrder = isset($_POST['prod_order']) ? $_POST['prod_order'] : '';
$KdBng = isset($_POST['kdbg']) ? $_POST['kdbg'] : '';
$selectedLocation = isset($_POST['location']) ? $_POST['location'] : '';


$sqlDB22PRO = "SELECT
                    ITXVIEWKK.ORIGDLVSALORDLINESALORDERCODE 
                  FROM
                    PRODUCTIONORDER
                    LEFT OUTER JOIN (
                    SELECT
                      ugp.LONGDESCRIPTION AS WARNA,
                      pr.LONGDESCRIPTION AS JNSKAIN,
                      pd.PROJECTCODE,
                      p.PRODUCTIONORDERCODE,
                      pd.SUBCODE01,
                      pd.SUBCODE02,
                      pd.SUBCODE03,
                      pd.SUBCODE04,
                      pd.SUBCODE05,
                      pd.SUBCODE06,
                      pd.SUBCODE07,
                      pd.SUBCODE08,
                      pd.ORIGDLVSALORDLINESALORDERCODE 
                    FROM
                      PRODUCTIONDEMANDSTEP p
                      LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE = p.PRODUCTIONDEMANDCODE
                      LEFT JOIN PRODUCT pr ON pr.ITEMTYPECODE = pd.ITEMTYPEAFICODE 
                      AND pr.SUBCODE01 = pd.SUBCODE01 
                      AND pr.SUBCODE02 = pd.SUBCODE02 
                      AND pr.SUBCODE03 = pd.SUBCODE03 
                      AND pr.SUBCODE04 = pd.SUBCODE04 
                      AND pr.SUBCODE05 = pd.SUBCODE05 
                      AND pr.SUBCODE06 = pd.SUBCODE06 
                      AND pr.SUBCODE07 = pd.SUBCODE07 
                      AND pr.SUBCODE08 = pd.SUBCODE08 
                      AND pr.SUBCODE09 = pd.SUBCODE09 
                      AND pr.SUBCODE10 = pd.SUBCODE10
                      LEFT JOIN DB2ADMIN.USERGENERICGROUP ugp ON pd.SUBCODE05 = ugp.CODE 
                    GROUP BY
                      pr.LONGDESCRIPTION,
                      p.PRODUCTIONORDERCODE,
                      pd.PROJECTCODE,
                      pd.SUBCODE01,
                      pd.SUBCODE02,
                      pd.SUBCODE03,
                      pd.SUBCODE04,
                      pd.SUBCODE05,
                      pd.SUBCODE06,
                      pd.SUBCODE07,
                      pd.SUBCODE08,
                      pd.ORIGDLVSALORDLINESALORDERCODE,
                      ugp.LONGDESCRIPTION 
                    ) ITXVIEWKK ON PRODUCTIONORDER.CODE = ITXVIEWKK.PRODUCTIONORDERCODE 
                  WHERE
                    ITXVIEWKK.PRODUCTIONORDERCODE = '$ProdOrder'";
$stmt2PRO   = db2_exec($conn1, $sqlDB22PRO, array('cursor' => DB2_SCROLLABLE));
$rowdb22PRO = db2_fetch_assoc($stmt2PRO);
?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Filter Identifikasi Benang Per Order New</h3>

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
          <label for="prod_order" class="col-md-1">Prod. Order</label>
          <div class="col-md-2">
            <input name="prod_order" value="<?php echo $ProdOrder; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="project" class="col-md-1">Project</label>
          <div class="col-md-2">
            <input name="project" value="<?php echo $rowdb22PRO['ORIGDLVSALORDLINESALORDERCODE']; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" readonly>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button class="btn btn-info" type="submit">Cari Data</button>
      </div>
    </div>
    <!-- /.card-body -->



    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">Detail Data Benang</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="form-group row">
          <label for="kd_benang" class="col-md-1">Kd Benang</label>
          <div class="col-md-2">
            <select name="kdbg" class="form-control form-control-sm" autocomplete="off">
              <option value="">Pilih</option>
              <?php

              $sqlDB2 = "SELECT
                              TRIM(SUBCODE01)||TRIM(SUBCODE02)||TRIM(SUBCODE03)||TRIM(SUBCODE04)||TRIM(SUBCODE05)||
                              TRIM(SUBCODE06)||TRIM(SUBCODE07)||TRIM(SUBCODE08) AS kd_benang,
                              TRIM(SUBCODE01) AS SUBCODE01,
                              TRIM(SUBCODE02) AS SUBCODE02,
                              TRIM(SUBCODE03) AS SUBCODE03,
                              TRIM(SUBCODE04) AS SUBCODE04,
                              TRIM(SUBCODE05) AS SUBCODE05,
                              TRIM(SUBCODE06) AS SUBCODE06,
                              TRIM(SUBCODE07) AS SUBCODE07,
                              TRIM(SUBCODE08) AS SUBCODE08
                          FROM
                              DB2ADMIN.PRODUCTIONRESERVATION 
                          WHERE
                              PRODUCTIONORDERCODE = '$ProdOrder' 
                          ORDER BY
                              BOMCOMPSEQUENCE ASC";
              $stmt   = db2_exec($conn1, $sqlDB2, array('cursor' => DB2_SCROLLABLE));
              while ($rowdb = db2_fetch_assoc($stmt)) {
                $kdb = $rowdb['KD_BENANG'];
                $S1 = $rowdb['SUBCODE01'];
                $S2 = $rowdb['SUBCODE02'];
                $S3 = $rowdb['SUBCODE03'];
                $S4 = $rowdb['SUBCODE04'];
                $S5 = $rowdb['SUBCODE05'];
                $S6 = $rowdb['SUBCODE06'];
                $S7 = $rowdb['SUBCODE07'];
                $S8 = $rowdb['SUBCODE08'];
              ?>
                <option value="<?php echo $kdb; ?>" <?php if ($KdBng == $kdb) {
                                                      echo "SELECTED";
                                                    } ?>><?php echo $kdb; ?></option>
              <?php } ?>
            </select>
          </div>

        </div>
        <div class="form-group row">
          <label for="location" class="col-md-1">Lokasi</label>
          <div class="col-md-2">
            <select name="location" class="form-control form-control-sm" autocomplete="off">
              <option value="">Pilih</option>
              <option value="M011" <?php if ($_POST['location'] == 'M011') {
                                      echo "SELECTED";
                                    } ?>>M011</option>
              <option value="P501" <?php if ($_POST['location'] == 'P501') {
                                      echo "SELECTED";
                                    } ?>>P501</option>
              <option value="M904" <?php if ($_POST['location'] == 'M904') {
                                      echo "SELECTED";
                                    } ?>>M904</option>
            </select>
          </div>
          <button class="btn btn-primary" type="submit">Cari Data</button>
        </div>

        <table id="example12" width="100%" class="table table-sm table-bordered table-striped" style="font-size: 13px;">
          <thead>
            <tr>
              <th colspan="10" valign="middle" style="text-align: center">Masuk</th>
              <th colspan="3" valign="middle" style="text-align: center">Sisa</th>
              <th colspan="2" valign="middle" style="text-align: center">Rincian</th>
            </tr>
            <tr>
              <th valign="middle" style="text-align: center">Tanggal</th>
              <th valign="middle" style="text-align: center">No Ref</th>
              <th valign="middle" style="text-align: center">Internal Doc</th>
              <th valign="middle" style="text-align: center">Supplier</th>
              <th valign="middle" style="text-align: center">Lot</th>
              <th valign="middle" style="text-align: center">Cones</th>
              <th valign="middle" style="text-align: center">Dus</th>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">Ket</th>
              <th valign="middle" style="text-align: center">Lokasi</th>
              <th valign="middle" style="text-align: center">Cones</th>
              <th valign="middle" style="text-align: center">Dus</th>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">Sisa</th>
              <th valign="middle" style="text-align: center">Out Doc</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $c = 0;

            $sqlDB21 = "SELECT
                    INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
                    INTERNALDOCUMENTLINE.ORDERLINE,
                    INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
                    INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
                    STOCKTRANSACTION.LOTCODE,
                    STOCKTRANSACTION.TRANSACTIONDATE,
                    SUM( STOCKTRANSACTION.BASEPRIMARYQUANTITY ) AS QTY_KG,
                    COUNT( STOCKTRANSACTION.BASEPRIMARYQUANTITY ) AS QTY_ROL,
                    SUM( STOCKTRANSACTION.BASESECONDARYQUANTITY ) AS QTY_CONES,
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
                    BALANCE.WHSLOCATIONWAREHOUSEZONECODE AS ZONE_BALANCE,
                    BALANCE.WAREHOUSELOCATIONCODE AS LOKASI_BALANCE,
                    FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION 
                  FROM
                    DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
                    LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = STOCKTRANSACTION.ORDERCODE 
                    AND INTERNALDOCUMENTLINE.ORDERLINE = STOCKTRANSACTION.ORDERLINE
                    LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
                    LEFT OUTER JOIN DB2ADMIN.BALANCE BALANCE ON BALANCE.ELEMENTSCODE = STOCKTRANSACTION.ITEMELEMENTCODE
                    LEFT OUTER JOIN (
                    SELECT
                      STOCKTRANSACTION.ORDERCODE,
                      STOCKTRANSACTION.ORDERLINE,
                      COUNT( BALANCE.BASEPRIMARYQUANTITYUNIT ) AS ROL,
                      SUM( BALANCE.BASEPRIMARYQUANTITYUNIT ) AS BERAT,
                      SUM( BALANCE.BASESECONDARYQUANTITYUNIT ) AS CONES,
                      BALANCE.LOTCODE 
                    FROM
                      DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
                      RIGHT OUTER JOIN DB2ADMIN.BALANCE BALANCE ON BALANCE.ELEMENTSCODE = STOCKTRANSACTION.ITEMELEMENTCODE 
                    WHERE
                      ( STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M904' ) 
                    GROUP BY
                      BALANCE.LOTCODE,
                      STOCKTRANSACTION.ORDERCODE,
                      STOCKTRANSACTION.ORDERLINE 
                    ) SISA ON SISA.ORDERCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE 
                    AND SISA.ORDERLINE = INTERNALDOCUMENTLINE.ORDERLINE 
                  WHERE
                    ( NOT INTERNALDOCUMENTLINE.EXTERNALREFERENCE = 'RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE IS NULL ) 
                    AND ( STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M904' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'P501' ) 
                    AND TRIM(INTERNALDOCUMENTLINE.SUBCODE01)  = '$S1' 
                    AND TRIM( INTERNALDOCUMENTLINE.SUBCODE02 )= '$S2'
                    AND TRIM( INTERNALDOCUMENTLINE.SUBCODE03 )= '$S3'
                    AND
                    trim( INTERNALDOCUMENTLINE.SUBCODE04 )= '$S4'
                    AND
                    trim( INTERNALDOCUMENTLINE.SUBCODE05 )= '$S5'
                    AND
                    trim( INTERNALDOCUMENTLINE.SUBCODE06 )= '$S6'
                    AND
                    trim( INTERNALDOCUMENTLINE.SUBCODE07 )= '$S7'
                    AND
                    trim( INTERNALDOCUMENTLINE.SUBCODE08) = '$S8' 
                    AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='$selectedLocation'
                    AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL 
                    AND SISA.BERAT > 0 
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
                    BALANCE.WHSLOCATIONWAREHOUSEZONECODE,
                    BALANCE.WAREHOUSELOCATIONCODE,
                    FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION";
            $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
            //}				  
            while ($rowdb21 = db2_fetch_assoc($stmt1)) {
              $bon = trim($rowdb21['INTDOCUMENTPROVISIONALCODE']) . "-" . trim($rowdb21['ORDERLINE']);
              $loc_awal = trim($rowdb21['WHSLOCATIONWAREHOUSEZONECODE']) . "-" . trim($rowdb21['WAREHOUSELOCATIONCODE']);
              $loc_balance = trim($rowdb21['ZONE_BALANCE']) . "-" . trim($rowdb21['LOKASI_BALANCE']);
              $itemc = trim($rowdb21['DECOSUBCODE01']) . " " . trim($rowdb21['DECOSUBCODE02']) . " "
                . trim($rowdb21['DECOSUBCODE03']) . " " . trim($rowdb21['DECOSUBCODE04']) . " "
                . trim($rowdb21['DECOSUBCODE05']) . " " . trim($rowdb21['DECOSUBCODE06']) . " "
                . trim($rowdb21['DECOSUBCODE07']) . " " . trim($rowdb21['DECOSUBCODE08']) . " "
                . trim($rowdb21['DECOSUBCODE09']) . " " . trim($rowdb21['DECOSUBCODE10']);
              if (trim($rowdb21['PROVISIONALCOUNTERCODE']) == 'I02M50') {
                $knitt = 'KNITTING ITTI- GREIGE';
              }
              $sqlDB22 = "SELECT
                        COUNT( BASEPRIMARYQUANTITYUNIT ) AS ROL,
                        SUM( BASEPRIMARYQUANTITYUNIT ) AS BERAT,
                        BALANCE.LOTCODE 
                      FROM
                        DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
                        LEFT OUTER JOIN DB2ADMIN.BALANCE BALANCE ON BALANCE.ELEMENTSCODE = STOCKTRANSACTION.ITEMELEMENTCODE 
                      WHERE
                        STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M011' 
                        AND STOCKTRANSACTION.ORDERCODE = '$rowdb21[PURCHASEORDERCODE]' 
                        AND STOCKTRANSACTION.ORDERLINE = '$rowdb21[ORDERLINE]' 
                        AND STOCKTRANSACTION.TRANSACTIONNUMBER = '$rowdb21[TRANSACTIONNUMBER]' 
                        AND STOCKTRANSACTION.LOTCODE = '$rowdb21[LOTCODE]' 
                      GROUP BY
                        BALANCE.LOTCODE";
              $stmt2   = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
              $rowdb22 = db2_fetch_assoc($stmt2);
              $sqlDB23 = "SELECT
                            PRODUCT.LONGDESCRIPTION,
                            PRODUCT.SHORTDESCRIPTION 
                          FROM
                            DB2ADMIN.PRODUCT PRODUCT 
                          WHERE
                            PRODUCT.ITEMTYPECODE = 'GYR' 
                            AND PRODUCT.SUBCODE01 = '" . trim($rowdb21[' DECOSUBCODE01 ']) . "' 
                            AND PRODUCT.SUBCODE02 = '" . trim($rowdb21[' DECOSUBCODE02 ']) . "' 
                            AND PRODUCT.SUBCODE03 = '" . trim($rowdb21[' DECOSUBCODE03 ']) . "' 
                            AND PRODUCT.SUBCODE04 = '" . trim($rowdb21[' DECOSUBCODE04 ']) . "' 
                            AND PRODUCT.SUBCODE05 = '" . trim($rowdb21[' DECOSUBCODE05 ']) . "' 
                            AND PRODUCT.SUBCODE06 = '" . trim($rowdb21[' DECOSUBCODE06 ']) . "' 
                            AND PRODUCT.SUBCODE07 = '" . trim($rowdb21[' DECOSUBCODE07 ']) . "'";
              $stmt3   = db2_exec($conn1, $sqlDB23, array('cursor' => DB2_SCROLLABLE));
              $rowdb23 = db2_fetch_assoc($stmt3);
              $sqlDB2S = "SELECT
                            COUNT( BALANCE.BASEPRIMARYQUANTITYUNIT ) AS ROL,
                            SUM( BALANCE.BASEPRIMARYQUANTITYUNIT ) AS BERAT,
                            SUM( BALANCE.BASESECONDARYQUANTITYUNIT ) AS CONES,
                            BALANCE.LOTCODE 
                          FROM
                            DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
                            RIGHT OUTER JOIN DB2ADMIN.BALANCE BALANCE ON BALANCE.ELEMENTSCODE = STOCKTRANSACTION.ITEMELEMENTCODE 
                          WHERE
                            ( STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M904' ) 
                            AND STOCKTRANSACTION.ORDERCODE = '$rowdb21[INTDOCUMENTPROVISIONALCODE]' 
                            AND STOCKTRANSACTION.ORDERLINE = '$rowdb21[ORDERLINE]' 
                          GROUP BY
                            BALANCE.LOTCODE ";
              $stmt2S   = db2_exec($conn1, $sqlDB2S, array('cursor' => DB2_SCROLLABLE));
              $rowdb2S = db2_fetch_assoc($stmt2S);
              $pos1 = strpos($rowdb21['ITEMDESCRIPTION'], '*');

              $supplayer = db2_exec($conn1, "SELECT DISTINCT
                                                    a.VALUESTRING 
                                                  FROM
                                                    INTERNALDOCUMENT i
                                                    LEFT JOIN INTERNALDOCUMENTLINE i2 ON i2.INTDOCUMENTPROVISIONALCODE = i.PROVISIONALCODE
                                                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = i2.ABSUNIQUEID 
                                                    AND a.FIELDNAME = 'SuppName' 
                                                  WHERE
                                                    i.PROVISIONALCODE = '$rowdb21[INTDOCUMENTPROVISIONALCODE]'");
              $rowsuplayer = db2_fetch_assoc($supplayer);

              $supp = $rowsuplayer['VALUESTRING'];
            ?>
              <tr>
                <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
                <td style="text-align: center"><?php echo $bon; ?></td>
                <td style="text-align: left"><?php echo $supp; ?></td>
                <td><?php echo $rowdb21['LOTCODE']; ?></td>
                <td style="text-align: center"><?php echo round($rowdb21['QTY_CONES']); ?></td>
                <td style="text-align: center"><?php echo $rowdb21['QTY_ROL']; ?></td>
                <td style="text-align: right"><?php echo round($rowdb21['QTY_KG'], 2); ?></td>
                <td style="text-align: left"><?php echo $rowdb21['ITEMDESCRIPTION']; ?></td>
                <td style="text-align: center"><?php if ($loc_balance != "-") {
                                                  echo $loc_balance;
                                                } else {
                                                  echo $loc_awal;
                                                } ?></td>
                <td style="text-align: center"><?php if ($rowdb2S['CONES'] != "") {
                                                  echo number_format(round($rowdb2S['CONES']));
                                                } else {
                                                  echo "0";
                                                } ?></td>
                <td style="text-align: center"><?php if ($rowdb2S['ROL'] != "") {
                                                  echo $rowdb2S['ROL'];
                                                } else {
                                                  echo "0";
                                                } ?></td>
                <td style="text-align: right"><?php if ($rowdb2S['BERAT'] != "") {
                                                echo number_format(round($rowdb2S['BERAT'], 2), 2);
                                              } else {
                                                echo "0.00";
                                              } ?></td>
                <td style="text-align: center"><a href="" class="btn btn-info btn-xs"> <i class="fa fa-link"></i></a></td>
                <td style="text-align: center"><a href="" class="btn btn-primary btn-xs"> <i class="fa fa-link"></i></a></td>
              </tr>
            <?php
              $no++;
              $tConesD += $rowdb21['QTY_CONES'];
              $tDusD += $rowdb21['QTY_ROL'];
              $tKGD += $rowdb21['QTY_KG'];
              $tsConesD += $rowdb2S['CONES'];
              $tsDusD += $rowdb2S['ROL'];
              $tsKGD += $rowdb2S['BERAT'];
            } ?>
          </tbody>
          <tfoot>
            <tr>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td style="text-align: right"><strong>Total</strong></td>
              <td style="text-align: center"><strong><?php echo number_format($tConesD); ?></strong></td>
              <td style="text-align: center"><strong><?php echo number_format($tDusD); ?></strong></td>
              <td style="text-align: right"><strong><?php echo number_format($tKGD, 2); ?></strong></td>
              <td style="text-align: left">&nbsp;</td>
              <td style="text-align: right">&nbsp;</td>
              <td style="text-align: center"><strong><?php echo number_format($tsConesD); ?></strong></td>
              <td style="text-align: center"><strong><?php echo number_format($tsDusD); ?></strong></td>
              <td style="text-align: right"><strong><?php echo number_format($tsKGD, 2); ?></strong></td>
              <td>&nbsp;</td>
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
<div id="DetailPakaiShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="DetailTurunanShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
if ($_POST['mutasikain'] == "MutasiKain") {

  function mutasiurut()
  {
    include "koneksi.php";
    $format = "20" . date("ymd");
    $sql = mysqli_query($con, "SELECT no_mutasi FROM tbl_mutasi_kain WHERE substr(no_mutasi,1,8) like '%" . $format . "%' ORDER BY no_mutasi DESC LIMIT 1 ") or die(mysql_error());
    $d = mysqli_num_rows($sql);
    if ($d > 0) {
      $r = mysqli_fetch_array($sql);
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

  $sql1 = mysqli_query($con, "SELECT *,count(b.transid) as jmlrol,a.transid as kdtrans FROM tbl_mutasi_kain a 
LEFT JOIN tbl_prodemand b ON a.transid=b.transid 
WHERE isnull(a.no_mutasi) AND date_format(a.tgl_buat ,'%Y-%m-%d')='$Awal' AND a.gshift='$Gshift' 
GROUP BY a.transid");
  $n1 = 1;
  $noceklist1 = 1;
  while ($r1 = mysqli_fetch_array($sql1)) {
    if ($_POST['cek'][$n1] != '') {
      $transid1 = $_POST['cek'][$n1];
      mysqli_query($con, "UPDATE tbl_mutasi_kain SET
		no_mutasi='$nomid',
		tgl_mutasi=now()
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