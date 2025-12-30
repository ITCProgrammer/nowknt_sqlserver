<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=benangturunan_" . $_GET['awal'] . "_.xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");

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
<table width="100%" border="0" style="width:9.50in;">
  <thead>
    <tr>
      <td>
        <table width="100%" border="1">
          <tr>
            <td width="6%"><img src="https://online.indotaichen.com/NOWknt/pages/cetak/Indo.jpg" alt="" width="50" height="50"></td>
            <td width="94%">
              <div align="center">
                <font size="+1"><strong>LAPORAN HARIAN TURUNAN BENANG</strong><br>
                </font>FW-19-KNT-40 / 00
              </div>            </td>
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
            <td colspan="4" class="tombol" style="border-bottom:0px #000 solid;
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
            <td class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">&nbsp;</td>
            <td colspan="2" class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">HALAMAN:  /</td>
          </tr>
          <tr>
            <td class="tombol">
              <center>
                <strong> NO</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>MC</strong>
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
                <strong>SUPPLIER</strong>
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
                <strong>QTY UTUH /</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>CNS</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>QTY</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>KG / CNS</strong>
              </center>
            </td>
            <td class="tombol"><center>
              <strong>BLOCK</strong>
            </center></td>
            <td class="tombol"><center>
              <strong>NO DOC</strong>
            </center></td>
            <td class="tombol"><center>
              <strong>KET</strong>
            </center></td>
          </tr>
        </thead>
        <tbody>
          <?php
$no=1;   
$c=0;
if($Shift=="1"){ $wkt=" AND TRANSACTIONTIME BETWEEN '07:00:00' AND '15:00:00' "; }
elseif($Shift=="2"){ $wkt=" AND TRANSACTIONTIME BETWEEN '15:00:00' AND '23:00:00' "; }
elseif($Shift=="3"){ $wkt=" AND CONCAT (TRANSACTIONDATE,CONCAT (' ',TRANSACTIONTIME)) BETWEEN '$Awal 23:00:00' AND '$Akhir 07:00:00' "; }	
else{ $wkt=""; }					  
	$sqlDB21 = " 
	SELECT 
	STOCKTRANSACTION.TRANSACTIONNUMBER,
	STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
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
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
	INITIALS.LONGDESCRIPTION,
	KD.NOMC
	FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN (
	SELECT MCN.NOMC,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,LISTAGG(DISTINCT  TRIM(p.PRODUCTIONDEMANDCODE),', ') AS PRODUCTIONDEMANDCODE  FROM PRODUCTIONDEMANDSTEP p
	LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE =p.PRODUCTIONDEMANDCODE 
	LEFT OUTER JOIN (SELECT ADSTORAGE.VALUESTRING AS NOMC,PRODUCTIONDEMAND.CODE FROM PRODUCTIONDEMAND
 	LEFT OUTER JOIN ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo') MCN ON MCN.CODE=p.PRODUCTIONDEMANDCODE
	GROUP BY p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,MCN.NOMC
	) KD ON KD.PRODUCTIONORDERCODE=STOCKTRANSACTION.ORDERCODE
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
	LEFT OUTER JOIN DB2ADMIN.INITIALS INITIALS ON 
	INITIALS.CODE =STOCKTRANSACTION.CREATIONUSER
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.RETURNTRANSACTION ='1' AND STOCKTRANSACTION.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' $wkt AND NOT STOCKTRANSACTION.ORDERCODE IS NULL
GROUP BY 
	STOCKTRANSACTION.TRANSACTIONNUMBER,
    STOCKTRANSACTION.ORDERCODE,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
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
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
	INITIALS.LONGDESCRIPTION,
	KD.NOMC	
";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
if (trim($rowdb21['LOGICALWAREHOUSECODE']) =='M501' or trim($rowdb21['LOGICALWAREHOUSECODE']) =='M904') { $knitt = 'LT2'; }
else if(trim($rowdb21['LOGICALWAREHOUSECODE']) =='P501'){ $knitt = 'LT1'; }
$kdbenang=trim($rowdb21['DECOSUBCODE01'])." ".trim($rowdb21['DECOSUBCODE02'])." ".trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04'])." ".trim($rowdb21['DECOSUBCODE05'])." ".trim($rowdb21['DECOSUBCODE06'])." ".trim($rowdb21['DECOSUBCODE07'])." ".trim($rowdb21['DECOSUBCODE08']);
		
$sqlDB22 = " SELECT TRANSACTIONDATE,TRANSACTIONTIME FROM STOCKTRANSACTION WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.RETURNTRANSACTION ='1' AND STOCKTRANSACTION.TRANSACTIONDATE='$rowdb21[TRANSACTIONDATE]' 
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
$sqlKt=mysqli_query($con," SELECT no_mesin FROM tbl_mesin WHERE kd_dtex='".$msin."' LIMIT 1");
$rk=mysqli_fetch_array($sqlKt);	
if($rowdb21['LONGDESCRIPTION']!=""){$uid=trim($rowdb21['LONGDESCRIPTION']);}else{$uid=trim($rowdb21['CREATIONUSER']);}		
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
		
$sqlDB2EXPD = " SELECT
a.VALUEDATE
FROM
	STOCKTRANSACTION s
LEFT OUTER JOIN ADSTORAGE a ON
	a.UNIQUEID = s.ABSUNIQUEID
	 AND a.NAMENAME = 'ProdDate'
WHERE
	TRANSACTIONNUMBER = '".$rowdb21['TRANSACTIONNUMBER']."'
	AND NOT a.VALUEDATE  IS NULL
GROUP BY
	a.VALUEDATE ";
$stmt2EXPD   = db2_exec($conn1,$sqlDB2EXPD, array('cursor'=>DB2_SCROLLABLE));
$rEXPD = db2_fetch_assoc($stmt2EXPD);
	$cns =round($rowdb21['QTY_CONES']);	
            echo "<tr valign='top'>
  	<td>$no</td>
    <td>$rk[no_mesin]</td>
    <td>$rowdb21[ORDERCODE]</td>
    <td>$kdbenang</td>
    <td></td>
    <td>$rowdb21[SUMMARIZEDDESCRIPTION]</td>
	<td>$rowdb21[LOTCODE]</td>
	<td>$rowdb21[QTY_DUS]</td>
	<td>$cns</td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
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
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
          </tr>
        <?php } ?>        
        <tr>
          <td colspan="7" align="right" class="tombol"><strong>TOTAL :</strong></td>
          <td colspan="7" align="right">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="7" align="right" class="tombol"><strong>GRAND TOTAL :</strong></td>
          <td colspan="7" align="right">&nbsp;</td>
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