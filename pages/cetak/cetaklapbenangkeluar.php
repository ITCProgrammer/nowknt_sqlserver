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
                <font size="+1"><strong>LAPORAN HARIAN KELUAR BENANG</strong><br>
                </font>FW-19-KNT-39 / 00
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
                <strong>CONES</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>QTY</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>SATUAN</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>BERAT / KG</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>BLOCK</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>MC</strong>
              </center>
            </td>
            <td class="tombol"><strong>NO. DOK</strong></td>
          </tr>
        </thead>
        <tbody>
          <?php
$no=1;   
$c=0;
if($Shift=="1"){ $wkt=" AND TRANSACTIONTIME BETWEEN '07:00:00' AND '15:00:00' "; }
elseif($Shift=="2"){ $wkt=" AND TRANSACTIONTIME BETWEEN '15:00:00' AND '23:00:00' "; }
elseif($Shift=="3"){ $wkt=" AND CONCAT (TRANSACTIONDATE,CONCAT (' ',TRANSACTIONTIME)) BETWEEN '$Awal 23:00:00' AND '$Akhir 07:00:00' "; }
//elseif($Shift=="ALL"){ $wkt=" AND CONCAT (TRANSACTIONDATE,CONCAT (' ',TRANSACTIONTIME)) BETWEEN '$Awal 07:00:00' AND '$Akhir 07:00:00' "; }	  
else{ $wkt=""; }					  
	$sqlDB21 = "
	SELECT 
	STOCKTRANSACTION.TRANSACTIONNUMBER,
	STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
	STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
	STOCKTRANSACTION.DECOSUBCODE01,
	STOCKTRANSACTION.DECOSUBCODE02,
	STOCKTRANSACTION.DECOSUBCODE03,
	STOCKTRANSACTION.DECOSUBCODE04,
	STOCKTRANSACTION.DECOSUBCODE05,
	STOCKTRANSACTION.DECOSUBCODE06,
	STOCKTRANSACTION.DECOSUBCODE07,
	STOCKTRANSACTION.DECOSUBCODE08,
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.LOTCODE,
	STOCKTRANSACTION.CREATIONUSER,
	COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_DUS,
	SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
	SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
	ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE,
	MCN.NOMC,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
	FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
	(
	SELECT SUBSTR(LISTAGG(DISTINCT  TRIM(i.PRODUCTIONDEMANDCODE),', '),1,8) AS PRODUCTIONDEMANDCODE,
i.PRODUCTIONORDERCODE  
FROM ITXVIEWKNTORDER i 
GROUP BY i.PRODUCTIONORDERCODE
	) ITXVIEWKNTORDER ON ITXVIEWKNTORDER.PRODUCTIONORDERCODE =STOCKTRANSACTION.ORDERCODE 
	LEFT OUTER JOIN (
 	SELECT ADSTORAGE.VALUESTRING AS NOMC,PRODUCTIONDEMAND.CODE FROM PRODUCTIONDEMAND
 	LEFT OUTER JOIN ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
 	) MCN ON MCN.CODE=ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.ONHANDUPDATE >1 AND TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' $wkt  AND NOT ORDERCODE IS NULL AND TEMPLATECODE ='120'
GROUP BY
	STOCKTRANSACTION.TRANSACTIONNUMBER,
    STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
	STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
	STOCKTRANSACTION.DECOSUBCODE01,
	STOCKTRANSACTION.DECOSUBCODE02,
	STOCKTRANSACTION.DECOSUBCODE03,
	STOCKTRANSACTION.DECOSUBCODE04,
	STOCKTRANSACTION.DECOSUBCODE05,
	STOCKTRANSACTION.DECOSUBCODE06,
	STOCKTRANSACTION.DECOSUBCODE07,
	STOCKTRANSACTION.DECOSUBCODE08,
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.LOTCODE,
	STOCKTRANSACTION.CREATIONUSER,
	ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE,
	MCN.NOMC,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
if (trim($rowdb21['LOGICALWAREHOUSECODE']) =='M501' or trim($rowdb21['LOGICALWAREHOUSECODE']) =='M904') { $knitt = 'LT2'; }
else if(trim($rowdb21['LOGICALWAREHOUSECODE']) =='P501'){ $knitt = 'LT1'; }
$kdbenang=trim($rowdb21['DECOSUBCODE01'])." ".trim($rowdb21['DECOSUBCODE02'])." ".trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04'])." ".trim($rowdb21['DECOSUBCODE05'])." ".trim($rowdb21['DECOSUBCODE06'])." ".trim($rowdb21['DECOSUBCODE07'])." ".trim($rowdb21['DECOSUBCODE08']);

$sqlDB22 = " SELECT TRANSACTIONDATE,TRANSACTIONTIME FROM STOCKTRANSACTION WHERE 
(STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.ONHANDUPDATE >1 AND STOCKTRANSACTION.TRANSACTIONDATE='$rowdb21[TRANSACTIONDATE]' 
AND STOCKTRANSACTION.ORDERCODE='$rowdb21[ORDERCODE]' AND STOCKTRANSACTION.CREATIONUSER='$rowdb21[CREATIONUSER]' ";
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);		
if($rowdb22['TRANSACTIONTIME']>="07:00:00" and $rowdb22['TRANSACTIONTIME']<="15:00:00"){
	$shf="1";
}elseif($rowdb22['TRANSACTIONTIME']>="15:00:00" and $rowdb22['TRANSACTIONTIME']<="23:00:00"){
	$shf="2";
}else{
	$shf="3";
}		
if($rowdb21['SCHEDULEDRESOURCECODE']!="") { $msin = $rowdb21['SCHEDULEDRESOURCECODE'];}else { $msin = $rowdb21['NOMC']; }	
$sqlDB22PRO =" SELECT ITXVIEWKK.SUBCODE02, ITXVIEWKK.SUBCODE03, ITXVIEWKK.SUBCODE04, ITXVIEWKK.PROJECTCODE,ITXVIEWKK.ORIGDLVSALORDLINESALORDERCODE FROM 
 PRODUCTIONORDER  
 LEFT OUTER JOIN ( SELECT ugp.LONGDESCRIPTION AS WARNA, pr.LONGDESCRIPTION AS JNSKAIN,pd.PROJECTCODE,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,pd.ORIGDLVSALORDLINESALORDERCODE  FROM PRODUCTIONDEMANDSTEP p
	LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE =p.PRODUCTIONDEMANDCODE
	LEFT JOIN PRODUCT pr ON
    pr.ITEMTYPECODE = pd.ITEMTYPEAFICODE
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
    LEFT JOIN DB2ADMIN.USERGENERICGROUP ugp ON
    pd.SUBCODE05 = ugp.CODE
	GROUP BY pr.LONGDESCRIPTION,p.PRODUCTIONORDERCODE,pd.PROJECTCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,ugp.LONGDESCRIPTION,pd.ORIGDLVSALORDLINESALORDERCODE) ITXVIEWKK ON PRODUCTIONORDER.CODE=ITXVIEWKK.PRODUCTIONORDERCODE
 WHERE ITXVIEWKK.PRODUCTIONORDERCODE='$rowdb21[ORDERCODE]' ";	
$stmt2PRO   = db2_exec($conn1,$sqlDB22PRO, array('cursor'=>DB2_SCROLLABLE));
$rowdb22PRO = db2_fetch_assoc($stmt2PRO);

$sqlDB2KPI = " SELECT
	a.VALUESTRING
FROM
	STOCKTRANSACTION s
LEFT OUTER JOIN ADSTORAGE a ON
	a.UNIQUEID = s.ABSUNIQUEID
	AND a.NAMENAME = 'KPIBenang'
WHERE
	TRANSACTIONNUMBER = '".$rowdb21['TRANSACTIONNUMBER']."'
	AND NOT a.VALUESTRING IS NULL
GROUP BY
	a.VALUESTRING ";
$stmt2KPI   = db2_exec($conn1,$sqlDB2KPI, array('cursor'=>DB2_SCROLLABLE));
$rKPI = db2_fetch_assoc($stmt2KPI);
		
$sqlKt=mysqli_query($con," SELECT no_mesin FROM tbl_mesin WHERE kd_dtex='".$msin."' LIMIT 1");
$rk=mysqli_fetch_array($sqlKt);
	$cns =round($rowdb21['QTY_CONES']);	
	$brt =number_format(round($rowdb21['QTY_KG'],2),2);	
            echo "<tr valign='top'>
  	<td>$no</td>
    <td>$bon</td>
    <td>$kdbenang</td>
    <td>$rowdb21[SUMMARIZEDDESCRIPTION]</td>
    <td>$rowdb21[LOTCODE]</td>
    <td align =center>$cns</td>
	<td align =center>$rowdb21[QTY_DUS]</td>
	<td align =center></td>
	<td align =right>$brt</td>
	<td align =center>$rowdb21[WHSLOCATIONWAREHOUSEZONECODE]-$rowdb21[WAREHOUSELOCATIONCODE]</td>
	<td align =center>$rk[no_mesin]</td>
	<td align =center>$rowdb21[TRANSACTIONNUMBER]</td>
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
          <td colspan="6" align="right" class="tombol"><strong>TOTAL :</strong></td>
          <td align="right" class="tombol">&nbsp;</td>
          <td colspan="5" align="right">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="7" align="right" class="tombol">&nbsp;</td>
          <td colspan="5" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="right" class="tombol"><strong>TOTAL :</strong></td>
          <td align="right" class="tombol">&nbsp;</td>
          <td align="left"><strong>DUS</strong></td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="right" class="tombol"><strong>TOTAL :</strong></td>
          <td align="right" class="tombol">&nbsp;</td>
          <td align="left"><strong>KARUNG</strong></td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="right" class="tombol"><strong>TOTAL :</strong></td>
          <td align="right" class="tombol">&nbsp;</td>
          <td align="left"><strong>PALET</strong></td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="right" class="tombol"><strong>TOTAL :</strong></td>
          <td align="right" class="tombol">&nbsp;</td>
          <td align="left"><strong>CONES</strong></td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="right" class="tombol"><strong>GRAND TOTAL :</strong></td>
          <td align="right" class="tombol">&nbsp;</td>
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