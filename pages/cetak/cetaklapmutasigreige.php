<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';
//--
//$act=$_POST['act'];
$tgl = date("Y-m-d");
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
                <font size="+1"><strong>LAPORAN HARIAN MUTASI KAIN GREIGE KNITTING</strong><br>
                </font>FW-19-KNT-14 / 01</div>
            </td>
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
            <td colspan="8" class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">Tanggal: <?php echo date('d F Y', strtotime($_GET['awal'])); ?></td>
          </tr>
          <tr>
            <td class="tombol">
              <center>
                <strong>No BON</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>Code</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>Project
                </strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>Jenis Kain</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>Lot</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>Qty</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>Berat / Kg</strong>
              </center>
            </td>
            <td class="tombol">
              <center>
                <strong>Mesin</strong>
              </center>
            </td>
          </tr>
        </thead>
        <tbody>
          <?php
          $no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT a.VALUESTRING AS MESIN,i.PROVISIONALCOUNTERCODE,i2.INTERNALREFERENCEDATE,i2.INTDOCUMENTPROVISIONALCODE , i2.ORDERLINE,i2.EXTERNALREFERENCE,  s.PROJECTCODE,
i.DESTINATIONWAREHOUSECODE, i2.SUBCODE01, i2.SUBCODE02, i2.SUBCODE03, i2.SUBCODE04,f.SUMMARIZEDDESCRIPTION,
SUM(s.BASEPRIMARYQUANTITY) AS KG, COUNT(s.ITEMELEMENTCODE) AS ROL, i2.RECEIVINGDATE, s.LOGICALWAREHOUSECODE,
s.WHSLOCATIONWAREHOUSEZONECODE, s.LOTCODE, s.CREATIONUSER  
FROM INTERNALDOCUMENT i 
LEFT OUTER JOIN INTERNALDOCUMENTLINE i2 ON i.PROVISIONALCODE = i2.INTDOCUMENTPROVISIONALCODE 
LEFT OUTER JOIN STOCKTRANSACTION s ON i2.INTDOCUMENTPROVISIONALCODE =s.ORDERCODE AND i2.ORDERLINE =s.ORDERLINE 
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE=s.LOTCODE
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.NAMENAME ='MachineNo'
LEFT OUTER JOIN FULLITEMKEYDECODER f ON s.FULLITEMIDENTIFIER = f.IDENTIFIER
WHERE s.PHYSICALWAREHOUSECODE ='M50' AND i2.INTERNALREFERENCEDATE BETWEEN '$_GET[awal]' AND '$_GET[akhir]'
GROUP BY 
a.VALUESTRING,i.PROVISIONALCOUNTERCODE,i2.INTERNALREFERENCEDATE,i2.INTDOCUMENTPROVISIONALCODE ,
i2.ORDERLINE, s.PROJECTCODE,
i.DESTINATIONWAREHOUSECODE, i2.SUBCODE01,
i2.SUBCODE02, i2.SUBCODE03,i2.EXTERNALREFERENCE,
i2.SUBCODE04,f.SUMMARIZEDDESCRIPTION, i2.RECEIVINGDATE,
s.LOGICALWAREHOUSECODE, s.WHSLOCATIONWAREHOUSEZONECODE, s.LOTCODE, s.CREATIONUSER  ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE']);
$itemc=trim($rowdb21['SUBCODE02'])."".trim($rowdb21['SUBCODE03'])." ".trim($rowdb21['SUBCODE04']);
$sqlKt = sqlsrv_query($con, "SELECT TOP 1 no_mesin FROM dbknitt.tbl_mesin WHERE kd_dtex = ?", [trim($rowdb21['MESIN'])]);
$rk = $sqlKt ? sqlsrv_fetch_array($sqlKt, SQLSRV_FETCH_ASSOC) : [];		
if (trim($rowdb21['PROVISIONALCOUNTERCODE']) =='I02M50') { $knitt = 'GREIGE-ITTI'; } 
$sqlDB2KPI = " SELECT
	a.VALUESTRING AS KD,
	b.VALUESTRING AS NAMA
FROM
	INTERNALDOCUMENTLINE i  
LEFT OUTER JOIN ADSTORAGE a ON
	a.UNIQUEID = i.ABSUNIQUEID 
	AND a.NAMENAME = 'KdPengiriman'
LEFT OUTER JOIN ADSTORAGE b ON
	b.UNIQUEID = i.ABSUNIQUEID 
	AND b.NAMENAME = 'NamaPetugas'	
WHERE
	i.INTDOCUMENTPROVISIONALCODE ='".$rowdb21['INTDOCUMENTPROVISIONALCODE']."'
	AND i.ORDERLINE ='".$rowdb21['ORDERLINE']."'
	AND NOT a.VALUESTRING IS NULL
	AND NOT b.VALUESTRING IS NULL ";
$stmt2KPI   = db2_exec($conn1,$sqlDB2KPI, array('cursor'=>DB2_SCROLLABLE));
$rKPI = db2_fetch_assoc($stmt2KPI);	 
$brt =number_format(round($rowdb21['KG'],2),2);		
$mesinNo = isset($rk['no_mesin']) ? $rk['no_mesin'] : '';
            echo "<tr valign='top'>
  	<td>$bon</td>
    <td>$itemc</td>
    <td>$rowdb21[PROJECTCODE]</td>
    <td>$rowdb21[SUMMARIZEDDESCRIPTION]</td>
    <td>$rowdb21[LOTCODE]</td>
    <td align =center>$rowdb21[ROL]</td>
	<td align =right>$brt</td>
	<td align =center>$mesinNo</td>
	</tr>";
            $tKg+=$rowdb21['KG'];
            $no++;
          }
          ?>
          <tr>
            <?php for ($i = $no; $i <= 35; $i++) { ?>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td align="right">&nbsp;</td>
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
          </tr>
        <tr>
          <td colspan="6" align="right" class="tombol"><strong>TOTAL :</strong></td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
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
            <div align="center">Dibuat Oleh : </div>
          </td>
          <td width="27%">
            <div align="center">Diperiksa Oleh :</div>
          </td>
          <td width="27%">
            <div align="center">Diketahui Oleh:</div>
          </td>
        </tr>
        <tr>
          <td>Nama</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Jabatan</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Tanggal</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="60" valign="top">Tanda Tangan</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
  </tbody>
</table>
