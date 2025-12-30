<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=terimatransfer_" . $_GET['awal'] . "_.xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");

ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';
//--
//$act=$_POST['act'];
$tgl = date("Y-m-d");
?>
<table width="100%" border="0" style="width:9.50in;">
  <thead>
    <tr>
      <td>
        <table width="100%" border="1">
          <tr>
            <td width="6%"><img src="https://online.indotaichen.com/NOWknt/pages/cetak/Indo.jpg" alt="" width="50" height="50"></td>
            <td width="94%">
              <div align="center">
                <font size="+1"><strong>LAPORAN HARIAN MASUK BENANG</strong><br>
                </font>FW-19-KNT-38 / 00
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </thead>
  <tr>
    <td>
      <table width="100%" border="1" style="width:9.50in;">
        <thead>
          <tr>
            <td colspan="2" class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">TANGGAL: <?php echo date('d F Y', strtotime($_GET['awal'])); ?></td>
            <td class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">&nbsp;</td>
            <td class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">&nbsp;</td>
            <td class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">&nbsp;</td>
            <td class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">&nbsp;</td>
            <td class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">&nbsp;</td>
            <td class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">&nbsp;</td>
            <td class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">&nbsp;</td>
            <td colspan="2" class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">HALAMAN : / </td>
          </tr>
          <tr>
            <td class="tombol">
              <center>
                <strong>NO</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>NO BON</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>PO</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>CODE</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>JENIS BENANG</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>LOT</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>CNS</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>DUS</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>QTY</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>BLOCK</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>NO. DOK</strong>
              </center>
            </td>
          </tr>
        </thead>
        <tbody>
          <?php
          $c = 0;
          $no = 1;
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
STOCKTRANSACTION.TRANSACTIONDATE BETWEEN '$_GET[awal]' AND '$_GET[akhir]' AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
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

          while ($r = db2_fetch_assoc($stmt1)) {
            $bgcolor = ($c++ & 1) ? '#33CCFF' : '#FFCC99';
			if (trim($r['LOGICALWAREHOUSECODE']) =='M501' or trim($r['LOGICALWAREHOUSECODE']) =='M904') { $knitt = 'LT2'; }
			else if(trim($r['LOGICALWAREHOUSECODE']) =='P501'){ $knitt = 'LT1'; }
			$kdbenang=$r['SUBCODE01']." ".$r['SUBCODE02']." ".$r['SUBCODE03']." ".$r['SUBCODE04']." ".$r['SUBCODE05']." ".$r['SUBCODE06']." ".$r['SUBCODE07']." ".$r['SUBCODE08'];	
			$nobon = trim($r['INTDOCUMENTPROVISIONALCODE'])."-".trim($r['ORDERLINE']); 
			$cns = round($r['QTY_CONES']); 
			$qty = number_format(round($r['QTY_KG'],2),2);  
            echo "<tr valign='top'>
  	<td>$no</td>
    <td>$nobon</td>
    <td>$r[EXTERNALREFERENCE]</td>
    <td>$kdbenang</td>
    <td>$r[SUMMARIZEDDESCRIPTION]</td>
    <td>$r[LOTCODE]</td>
	<td>$cns</td>
	<td>$r[QTY_ROL]</td>
	<td align =right>$qty</td>
	<td>$r[WHSLOCATIONWAREHOUSEZONECODE]-$r[WAREHOUSELOCATIONCODE]</td>
	<td align=right>$r[TRANSACTIONNUMBER]</td>
    </tr>";
            $tKg+=$rowdb21['QTY_KG'];
            $no++;
          }
          ?>
          <tr>
            <?php for ($i = $no; $i <= 35; $i++) { ?>
              <td class="tombol"><?php echo $i; ?></td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
          </tr>
        <?php } ?>
        <tr>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombolkanan">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol" align="right">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="6" align="right" class="tombol"><strong>TOTAL :</strong></td>
          <td colspan="5" align="right">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="6" align="right" class="tombol"><strong>GRAND TOTAL :</strong></td>
          <td colspan="5" align="right">&nbsp;</td>
          </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="1">
        <tr>
          <td width="15%">&nbsp;</td>
          <td width="31%">
            <div align="center">DIBUAT OLEH:</div>
          </td>
          <td width="27%">
            <div align="center">DIPERIKSA OLEH:</div>
          </td>
          <td width="27%">
            <div align="center">DIKETAHUI OLEH:</div>
          </td>
        </tr>
        <tr>
          <td>NAMA</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>JABATAN</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>TANGGAL</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="60" valign="top">TANDA TANGAN</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
  </tbody>
</table>