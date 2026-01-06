<?php
include '../../koneksi.php';
//require_once('dompdf/dompdf_config.inc.php'); autoload.inc.php
require_once('dompdf/autoload.inc.php');

 ini_set("error_reporting", 1);	
//--
$idkk=$_REQUEST['idkk'];
$act=$_GET['g'];
//-
?>
<?php
$qry=sqlsrv_query($con,"SELECT *, FORMAT(GETDATE(), 'yyyy-MM-dd HH:mm:ss') as tgl FROM dbknitt.tbl_jadwal WHERE id='".$_GET['id']."'");
$r=sqlsrv_fetch_array($qry);

?>
<?php if ($r['kategori']=="Over Houl") {
    $over="checked";
} else {
    $over="";
} ?>
<?php if ($r['kategori']=="Ringan") {
    $ringan="checked";
} else {
    $ringan="";
} ?>
<?php if ($r['sts']=="Berkala") {
    $berkala="checked";
} else {
    $berkala="";
} ?>
<?php if ($r['sts']=="Trouble") {
    $trouble="checked";
} else {
    $trouble="";
} ?>
<?php if ($r['sts']=="Ganti Konstruksi") {
    $ganti="checked";
} else {
    $ganti="";
}?>
<?php
$html='
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulir Pemeriksaan Mesin Knitting</title>
<style>
body,td,th {
	/* font-family: Courier New, Courier, monospace; */
	font-family: sans-serif, Roman, serif;
	
	font-size: 12px;
}
pre {
	font-family:sans-serif, Roman, serif;
	clear:both;
	margin: 0px auto 0px;
	padding: 0px;
	white-space: pre-wrap;       /* Since CSS 2.1 */
    white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
    white-space: -pre-wrap;      /* Opera 4-6 */
    white-space: -o-pre-wrap;    /* Opera 7 */
    word-wrap: break-word; 
	
}
body{
	margin: 0px auto 0px;
	padding: 2px;
	font-size: 8px;
	color: #000;
	width: 98%;
	background-position: top;
	background-color: #fff;
}
.table-list {
	clear: both;
	text-align: left;
	border-collapse: collapse;
	margin: 0px 0px 10px 0px;
	background:#fff;	
}
.table-list td {
	color: #000000; /*#333*/
	font-size:8px;
	border-color: #fff;
	border-collapse: collapse;
	vertical-align: center;
	padding: 3px 3px;
	border-top:1px #000000 solid;
	border-bottom:1px #000000 solid;
	border-left:1px #000000 solid;
	border-right:1px #000000 solid;

	
}
.table-list1 {
	clear: both;
	text-align: left;
	border-collapse: collapse;
	margin: 0px 0px 5px 0px;
	background:#fff;	
}
.table-list1 td {
	color:#000000; /*#333*/
	font-size:12px;
	border-color: #fff;
	border-collapse: collapse;
	vertical-align: center;
	padding: 3px 3px;
	border-bottom:1px #000000 solid;
	border-top:1px #000000 solid;
	border-left:1px #000000 solid;
	border-right:1px #000000 solid;
	
	
}
.table-list2 {
	clear: both;
	text-align: left;
	border-collapse: collapse;
	margin: 10px 0px 0px 2px;
	background:#fff;	
}
.table-list2 td {
	color:#000000; /*#333*/
	font-size:10px;
	border-color: #fff;
	border-collapse: collapse;
	vertical-align: center;
	padding: 4px 2px;
	border-bottom:1px #000000 solid;
	border-top:1px #000000 solid;
	border-left:1px #000000 solid;
	border-right:1px #000000 solid;
	
	
}
#nocetak {
	display:none;
	}
</style>
<style>
html{margin:0px;}
</style>
</head>

<body>
<br>
<br>
<table width="100%" >
  <tbody>
    <tr>
      <td colspan="8" align="center"><font size="+2"><u><strong>FORMULIR PEMERIKSAAN MESIN KNITTING</strong></u></font><br><strong>FW-14-KNT-26 / 05</strong><br><br></td>
    </tr>
    <tr>
      <td width="17%">Tgl Cetak Form</td>
      <td width="19%">: '.$r['tgl'].'</td>
      <td width="17%">Tgl Mulai Service</td>
      <td width="1%">:</td>
      <td colspan="2">&nbsp;</td>
      <td width="2%">Jam</td>
      <td width="15%">:</td>
    </tr>
    <tr>
      <td>No Mesin</td>
      <td>: '.$r['no_mesin'].'</td>
      <td>Tgl Selesai Service</td>
      <td>:</td>
      <td colspan="2">&nbsp;</td>
      <td>Jam</td>
      <td>:</td>
    </tr>
    <tr>
      <td valign="top">Produksi (KG)</td>
      <td valign="top">: '.$r['kg_awal'].'</td>
      <td valign="top">Kategori</td>
      <td valign="top">:</td>
      <td width="2%" valign="top"><input type="checkbox" '.$over.'/></td>
      <td width="27%" valign="top">Over Houl</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top"><input type="checkbox" '.$ringan.' /></td>
      <td valign="top">Ringan</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">Jenis Service</td>
      <td valign="top">:</td>
      <td valign="top"><input type="checkbox" '.$berkala.'/></td>
      <td valign="top">Berkala</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top"><input type="checkbox" '.$trouble.' /></td>
      <td valign="top">Trouble</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top"><input type="checkbox" '.$ganti.' /></td>
      <td valign="top">Ganti Konstruksi</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
  </tbody>
</table>
<br />
<table width="100%" border="1" class="table-list1" >
  <tbody>
    <tr align="center">
      <td width="3%" rowspan="2">No.</td>
      <td width="24%" rowspan="2">Bagian Mesin</td>
      <td width="7%" rowspan="2">Jumlah</td>
      <td colspan="2">Kondisi</td>
      <td colspan="2">Tindak Lanjut</td>
      <td width="33%" rowspan="2">Keterangan</td>
    </tr>
    <tr>
      <td width="8%" align="center">Baik</td>
      <td width="8%" align="center">Tidak</td>
      <td width="8%" align="center">Perbaikan</td>
      <td width="9%" align="center">Ganti</td>
    </tr>
    <tr>
      <td align="center">1.</td>
      <td>Jarum</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">2.</td>
      <td>Sinker</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">3.</td>
      <td>Cylinder</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">4.</td>
      <td>Fan (Kipas)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">5.</td>
      <td>Yarn Guide (Ekor Babi)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">6.</td>
      <td>Positif Feeder (MPF)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">7.</td>
      <td>Pully</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">8.</td>
      <td>Tooth Belt</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">9.</td>
      <td>Tension Tape</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">10.</td>
      <td>Feeder</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">11.</td>
      <td>Baut Cam Box</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">12.</td>
      <td>Lengkok / CAM</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">13.</td>
      <td>Lampu</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">14.</td>
      <td>Take Down Units</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">15.</td>
      <td>Sensor Pintu</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">16.</td>
      <td>Sensor Jarum</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">17.</td>
      <td>Display Monitor</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">18.</td>
      <td>Lubrication Units</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">19.</td>
      <td>Creel / Rak Benang</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">20.</td>
      <td>Motor Dinamo</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">21.</td>
      <td>Vanbelt</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">22.</td>
      <td>Air Pressure</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">23.</td>
      <td>MER</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr style="height: 1.5in">
      <td colspan="8" valign="top">Catatan: '.$r['ket'].' </td>
    </tr>
  </tbody>
</table>
<br />
<table width="100%" border="1" class="table-list1">
  <tbody>
    <tr valign="top">
      <td>Mekanik Service<br />
        1. '.$r['mekanik'].'<br />
        2. '.$r['mekanik2'].'<br />
        3. '.$r['mekanik3'].'<br />
        4.</td>
      <td>Mekanik Setting Mesin</td>
      <td>Production Leader</td>
      <td>Mekanik Leader</td>
    </tr>
    <tr>
      <td>Tgl :</td>
      <td>Tgl :</td>
      <td>Tgl : </td>
      <td>Tgl :</td>
    </tr>
    <tr valign="top">
      <td>Tanda tangan :<br /><br /><br /><br /></td>
      <td>Tanda tangan :</td>
      <td>Tanda tangan :</td>
      <td>Tanda tangan :</td>
    </tr>
  </tbody>
</table>
<!--
  <em><strong><font size="+1">*) Diisi jika keterangan Ganti Kontruksi</font></strong></em>
<table width="100%" border="1" class="table-list1">
  <tbody>
  <tr>
    <td rowspan="2" align="center" valign="middle">Posisi</td>
    <td colspan="2" align="center" valign="middle">Item Lama :</td>
    <td colspan="2" align="center" valign="middle">Item Baru :</td>
    <td rowspan="2" align="center" valign="middle">Selisih</td>
    <td rowspan="2" align="center" valign="middle">DIserahkan</td>
    <td rowspan="2" align="center" valign="middle">Diterima</td>
    <td rowspan="2" align="center" valign="middle">TTD Staff</td>
  </tr>
  <tr>
    <td align="center" valign="middle">Spare Part</td>
    <td align="center" valign="middle">Jumlah</td>
    <td align="center" valign="middle">Spare Part</td>
    <td align="center" valign="middle">Jumlah</td>
  </tr>
  <tr>
    <td rowspan="3" align="center" valign="middle">Dial</td>
    <td><em><strong>SUSUNAN</strong></em></td>
    <td>&nbsp;</td>
    <td><strong><em>SUSUNAN</em></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Jarum 1</td>
    <td>&nbsp;</td>
    <td>Jarum 1</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Jarum 2</td>
    <td>&nbsp;</td>
    <td>Jarum 2</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="5" align="center" valign="middle">Cylinder</td>
    <td><em><strong>SUSUNAN</strong></em></td>
    <td>&nbsp;</td>
    <td><em><strong>SUSUNAN</strong></em></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Jarum 1</td>
    <td>&nbsp;</td>
    <td>Jarum 1</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Jarum 2</td>
    <td>&nbsp;</td>
    <td>Jarum 2</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Jarum 3</td>
    <td>&nbsp;</td>
    <td>Jarum 3</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Jarum 4</td>
    <td>&nbsp;</td>
    <td>Jarum 4</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="9" align="left" valign="top"><strong>Keterangan :</strong><br /><br /><br /><br /></td>
  </tr>
  </tbody>
</table>
-->
	
</body>
</html>';  
use Dompdf\Dompdf;
// instantiate and use the dompdf class
$dompdf = new Dompdf();

$dompdf->loadHtml($html);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('legal', 'portrait'); //portrait, landscape
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream('FormPeriksa'.$r['no_mesin'].'.pdf', array("Attachment"=>0));

?>
