<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';
//--
//$act=$_POST['act'];
$tgl = date("Y-m-d");
$Awal	= isset($_GET['awal']) ? $_GET['awal'] : '';
$Akhir	= isset($_GET['akhir']) ? $_GET['akhir'] : '';
$Shift	= isset($_GET['shift']) ? $_GET['shift'] : '';
?>
<style type="text/css">
  .tombolkanan {
    text-align: right;
  }

  input {
    text-align: center;
    border: hidden;
  }

  @media print {
    ::-webkit-input-placeholder {
      /* WebKit browsers */
      color: transparent;
    }

    :-moz-placeholder {
      /* Mozilla Firefox 4 to 18 */
      color: transparent;
    }

    ::-moz-placeholder {
      /* Mozilla Firefox 19+ */
      color: transparent;
    }

    :-ms-input-placeholder {
      /* Internet Explorer 10+ */
      color: transparent;
    }

    .pagebreak {
      page-break-before: always;
    }

    .header {
      display: block
    }

    table thead {
      display: table-header-group;
    }
  }
</style>
<link href="styles_cetak.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" style="width:9.50in;">
  <thead>
    <tr>
      <td>
        <table width="100%" border="0" class="table-list1">
          <tr>
            <td width="6%"><img src="Indo.jpg" alt="" width="50" height="50"></td>
            <td width="94%">
              <div align="center">
                <font size="+1"><strong>LAPORAN HARIAN RETUR BENANG</strong><br>
                </font>FW-19-KNT-41 / 00
              </div>            </td>
          </tr>
        </table>
      </td>
    </tr>
  </thead>
  <tr>
    <td>
      <table width="100%" border="0" class="table-list1">
        <thead>
          <tr>
            <td colspan="10" class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">TANGGAL: <?php echo date('d F Y', strtotime($_GET['awal'])); ?></td>
            <td colspan="2" class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;"><span class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">HALAMAN:  /</span></td>
          </tr>
          <tr>
            <td class="tombol">
              <center>
                <strong> NO</strong>
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
            <td class="tombol"><strong>KET</strong></td>
          </tr>
        </thead>
        <tbody>
          <?php
$no=1;   
$c=0;
$sqlDB21 = " SELECT 
INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
STOCKTRANSACTION.LOTCODE,  
STOCKTRANSACTION.TRANSACTIONDATE,
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
LEFT OUTER JOIN ( SELECT
	i2.*, i.ORDPRNCUSTOMERSUPPLIERCODE 
FROM
	INTERNALDOCUMENT i
LEFT OUTER JOIN INTERNALDOCUMENTLINE i2 ON
	i.PROVISIONALCODE = i2.INTDOCUMENTPROVISIONALCODE
	AND i.PROVISIONALCOUNTERCODE = i2.INTDOCPROVISIONALCOUNTERCODE ) INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE=STOCKTRANSACTION.ORDERCODE AND 
INTERNALDOCUMENTLINE.ORDERLINE=STOCKTRANSACTION.ORDERLINE  
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE = 'RETURAN') AND (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904' or STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' or STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501') AND
STOCKTRANSACTION.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL AND
INTERNALDOCUMENTLINE.CONDITIONRETRIEVINGDATE BETWEEN '$Awal' AND '$Akhir' AND
INTERNALDOCUMENTLINE.ORDPRNCUSTOMERSUPPLIERCODE IN ('M011','M904') 
AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE IN('GYR','DYR')
GROUP BY
STOCKTRANSACTION.LOTCODE,
INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE,
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
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}		
	$knitt="";				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=$rowdb21['INTDOCUMENTPROVISIONALCODE']."-".$rowdb21['ORDERLINE'];		
if (trim($rowdb21['DESTINATIONWAREHOUSECODE']) =='M501' or trim($rowdb21['LOGICALWAREHOUSECODE']) =='M501' or 
	trim($rowdb21['DESTINATIONWAREHOUSECODE']) =='M904' or trim($rowdb21['LOGICALWAREHOUSECODE']) =='M904') { $knitt = 'LT2'; }
else if(trim($rowdb21['DESTINATIONWAREHOUSECODE']) =='P501' or trim($rowdb21['LOGICALWAREHOUSECODE']) =='P501'){ $knitt = 'LT1'; }
					  
$sqlDB22 = " SELECT
	a.VALUESTRING 
FROM
	( SELECT
	i2.*, i.ORDPRNCUSTOMERSUPPLIERCODE 
FROM
	INTERNALDOCUMENT i
LEFT OUTER JOIN INTERNALDOCUMENTLINE i2 ON
	i.PROVISIONALCODE = i2.INTDOCUMENTPROVISIONALCODE
	AND i.PROVISIONALCOUNTERCODE = i2.INTDOCPROVISIONALCOUNTERCODE ) i
LEFT OUTER JOIN ADSTORAGE a ON
	a.UNIQUEID = i.ABSUNIQUEID
	AND a.NAMENAME = 'NoteYarnDefect'
WHERE
	i.INTDOCPROVISIONALCOUNTERCODE = '".$rowdb21['INTDOCPROVISIONALCOUNTERCODE']."' AND 
	i.INTDOCUMENTPROVISIONALCODE = '".$rowdb21['INTDOCUMENTPROVISIONALCODE']."' AND
	i.ORDERLINE = '".$rowdb21['ORDERLINE']."' AND 
	i.ORDPRNCUSTOMERSUPPLIERCODE = 'M011'";
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);	
	$cns =round($rowdb21['QTY_CONES']);	
	$brt =number_format(round($rowdb21['QTY_KG'],2),2);	
            echo "<tr valign='top'>
  	<td>$no</td>
    <td>$bon</td>
    <td>$rowdb21[EXTERNALREFERENCE]</td>
    <td>$rowdb21[ITEMDESCRIPTION]</td>
    <td>$rowdb21[SUMMARIZEDDESCRIPTION]</td>
    <td>$rowdb21[LOTCODE]</td>
	<td align =center>$cns</td>
	<td align =center>$rowdb21[QTY_ROL]</td>
	<td align =right>$brt</td>
	<td>$rowdb21[WHSLOCATIONWAREHOUSEZONECODE]-$rowdb21[WAREHOUSELOCATIONCODE]</td>
	<td></td>
	<td>$rowdb22[VALUESTRING]</td>
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
              <td class="tombol">&nbsp;</td>
          </tr>
        <?php } ?>        
        <tr>
          <td colspan="7" align="right" class="tombol"><strong>TOTAL :</strong></td>
          <td colspan="5" align="right">&nbsp;</td>
          </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" class="table-list1">
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