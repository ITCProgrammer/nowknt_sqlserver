<?php
  include '../../koneksi.php';
  ini_set("error_reporting", 1);

// $qrytgl=mysqli_query($con,"SELECT DATE_FORMAT(now(),'%d %M %Y %H:%i') as tgl"); $r2=mysqli_fetch_array($qrytgl);
function fk($mc)
{
$con=mysqli_connect("10.0.0.10","dit","4dm1n","dbknitt");
if (mysqli_connect_errno()) {
printf("Connect failed: %s\n", mysqli_connect_error());
exit();
} 
    $qry=mysqli_query($con,"SELECT @num:=@num+1 AS urut,kategori FROM
			tbl_jadwal, (SELECT @num := 0) T
		WHERE
			no_mesin = '$mc'
		ORDER BY
			id DESC
		LIMIT 3");
    $k=0;
    while ($dt=mysqli_fetch_array($qry)) {
        $urut[$k]=$dt['urut']." ".$dt['kategori'];
        $k++;
    }
    $ringan="0";
    if ($urut[0]=="1 Ringan" and $urut[1]=="2 Ringan") {
        $ringan="2";
    } elseif ($urut[0]=="1 Ringan" and $urut[1]=="2 Over Houl") {
        $ringan="1";
    } elseif ($urut[0]=="1 Over Houl") {
        $ringan="0";
    }

    return $ringan;
} 
?>

<html>
<head>
<title>:: Cetak Jadwal Preventif Mesin</title>
<link href="styles_cetak.css" rel="stylesheet" type="text/css">
<style>
html{margin:4px auto 4px;}
input{
text-align:center;
border:hidden;
}
@media print {
  ::-webkit-input-placeholder { /* WebKit browsers */
      color: transparent;
  }
  :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
      color: transparent;
  }
  ::-moz-placeholder { /* Mozilla Firefox 19+ */
      color: transparent;
  }
  :-ms-input-placeholder { /* Internet Explorer 10+ */
      color: transparent;
  }
  .pagebreak { page-break-before:always; }
  .header {display:block}
  table thead
   {
    display: table-header-group;
   }
}
</style>
</head>
<body>
<table width="100%" border="0" class="table-list1">
  <thead>
<tr valign="top">
        <td colspan="12"><table width="100%" border="0" class="table-list1">
          <thead>
            <tr>
              <td width="10%"><img src="../../dist/img/Indo.jpg" alt="" width="60" height="60"></td>
              <td width="90%"><div align="center">
                <h2>JADWAL PREVENTIF MESIN</h2>
                FW-14-KNT-25 / 01
              </div></td>
            </tr>
          <thead>
        </table></td>
    </tr>
<tr valign="top">
  <td colspan="12" style="border-left: 0px #000000 solid; border-right: 0px #000000 solid;">Tanggal : </td>
  </tr>
    <tr>
      <td width="4%" rowspan="2" align="center">No.</td>
      <td width="8%" rowspan="2" align="center">No Mesin</td>
      <td width="5%" rowspan="2" align="center">Produksi (KG)</td>
      <td width="8%" rowspan="2" align="center">Status</td>
      <td width="8%" rowspan="2" align="center">Frekuensi Servis Ringan</td>
      <td colspan="3" align="center">Preventive *)</td>
      <td width="21%" rowspan="2" align="center">Keterangan</td>
      <td width="7%" rowspan="2" align="center">Tgl Selesai Service</td>
      <td width="13%" rowspan="2" align="center">Mekanik</td>
      <td width="7%" rowspan="2" align="center">Tanda Terima Form</td>
    </tr>
    <tr>
	  <td width="7%" align="center">Over Houl</td>
      <td width="6%" align="center">Ringan</td>
      <td width="6%" align="center">Hold</td>
    </tr>
  </thead>
    <tbody>
	<?php
		$sqlDB2=" SELECT USERGENERICGROUP.CODE AS KDMC,USERGENERICGROUP.LONGDESCRIPTION, 
USERGENERICGROUP.SHORTDESCRIPTION,USERGENERICGROUP.SEARCHDESCRIPTION,KGPRO.KG FROM DB2ADMIN.USERGENERICGROUP 
LEFT OUTER JOIN 
(
SELECT SUM(WEIGHTNET) AS KG, ADSTORAGE.VALUESTRING AS MC FROM ELEMENTSINSPECTION 
 LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE 
 LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo' 
 WHERE ELEMENTITEMTYPECODE='KGF'
 GROUP BY ADSTORAGE.VALUESTRING
) KGPRO ON USERGENERICGROUP.CODE=KGPRO.MC
WHERE USERGENERICGROUP.USERGENERICGROUPTYPECODE = 'MCK' AND 
	USERGENERICGROUP.USERGENGROUPTYPECOMPANYCODE = '100' AND 
	USERGENERICGROUP.OWNINGCOMPANYCODE = '100' ";
					     
$c=0;
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
while($rowdb2 = db2_fetch_assoc($stmt)){ 
$fks=fk($rowdb2['SEARCHDESCRIPTION']);	
$sql=sqlsrv_query($con," SELECT
                            a.no_mesin, sum(b.berat_awal) as KGS
                          FROM
                            dbknitt.tbl_mesin a
                          INNER JOIN dbknitt.tbl_inspeksi_detail b ON a.no_mesin=b.no_mc
                          WHERE a.no_mesin='".$rowdb2['SEARCHDESCRIPTION']."'
                          GROUP BY
                            a.no_mesin
                          ORDER BY
                            a.no_mesin ASC ");
     $r=sqlsrv_fetch_array($sql);	
	 $sql1=sqlsrv_query($con," SELECT TOP 1  
                                a.tgl_servis,
                                b.kg_awal,
                                b.sts 
                              FROM dbknitt.tbl_jadwal a
                              LEFT JOIN 
                                (
                                  SELECT 
                                    SUM(kg_awal) as kg_awal,
                                    (
                                        SELECT TOP 1 sts
                                        FROM dbknitt.tbl_jadwal t2
                                        WHERE t2.no_mesin = t1.no_mesin
                                        ORDER BY t2.tgl_servis DESC
                                    ) AS sts,
                                    no_mesin  
                                  FROM dbknitt.tbl_jadwal  
                                  WHERE 
                                    no_mesin='".$rowdb2['SEARCHDESCRIPTION']."' 
                                  GROUP BY no_mesin
                                ) b ON b.no_mesin=a.no_mesin 
                            WHERE a.no_mesin='".$rowdb2['SEARCHDESCRIPTION']."' 
                            ORDER BY a.tgl_servis DESC ");
$r1=sqlsrv_fetch_array($sql1);	
$sqlbts=sqlsrv_query($con," SELECT
	a.no_mesin,a.batas_produksi
FROM
	dbknitt.tbl_mesin a
WHERE a.no_mesin='".$rowdb2['SEARCHDESCRIPTION']."'
ORDER BY
	a.no_mesin ASC ");
     $rBTS=sqlsrv_fetch_array($sqlbts);	
$total=round((round($r['KGS'],2)+round($rowdb2['KG'],2))-round($r1['kg_awal'],2), '2');		
if ($total > $rBTS['batas_produksi'] or $r1['sts']=="Hold") {
          $no++;
          if ($r1['sts']=="Hold") {
              $sts="Hold";
          } else {
              $sts="Berkala";
          }
		
		
		?>
      <tr valign="top">
        <td align="center"><?php echo $no; ?></td>
        <td align="center"><?php echo $rowdb2['SEARCHDESCRIPTION']; ?></td>
        <td align="right"><?php echo $total; ?></td>
        <td align="center"><?php echo $sts; ?></td>
        <td align="center"><?php echo $fks; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      	<td>&nbsp;</td>
      	<td>&nbsp;</td>
      	<td>&nbsp;</td>
      	<td>&nbsp;</td>
      </tr>
<?php 
									   
}
} ?>		
	<tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top">
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>

     </tbody>
</table>
<pre>Keterangan : *) Beri tanda tickmark (&#10004;) sesuai dengan aktual
                           Apabila preventive dihold, maka keterangan harus diisi</pre>
  <table width="100%" border="0" class="table-list1">
  <tr>
    <td width="15%">&nbsp;</td>
    <td width="31%"><div align="center">Dibuat Oleh</div></td>
    <td width="27%"><div align="center">Disetujui Oleh</div></td>
    <td width="27%"><div align="center">Diketahui Oleh</div></td>
    </tr>
  <tr>
    <td>Nama</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>Jabatan</td>
    <td><div align="center">Staff</div></td>
    <td><div align="center">Mekanik Leader</div></td>
    <td><div align="center">Supervisor</div></td>
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

</body>
</html>
