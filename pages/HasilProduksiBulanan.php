<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Gshift = '';
$lastDay = date("Y-m-t", strtotime($Akhir));
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
                    <input name="tgl_awal" value="<?php echo $Awal;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
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
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>	
            </div> 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Inspeksi</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th rowspan="2" valign="middle" style="text-align: center">Tanggal</th>
                    <th colspan="2" valign="middle" style="text-align: center">Produksi</th>
                    <th colspan="8" valign="middle" style="text-align: center">Grade</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">KG</th>
                    <th valign="middle" style="text-align: center">A</th>
                    <th valign="middle" style="text-align: center">%</th>
                    <th valign="middle" style="text-align: center">B</th>
                    <th valign="middle" style="text-align: center">%</th>
                    <th valign="middle" style="text-align: center">C</th>
                    <th valign="middle" style="text-align: center">%</th>
                    <th valign="middle" style="text-align: center">BS</th>
                    <th valign="middle" style="text-align: center">%</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
function produksi($Awl){
	include "koneksi.php";
	$Akr 	= date('Y-m-d', strtotime('+1 days', strtotime($Awl)));
	$wkt=" AND INSPECTIONSTARTDATETIME BETWEEN '$Awl-07:00:00' AND '$Akr-07:00:00' " ;						  
	$sqlDB21 = " SELECT 
COUNT(ELEMENTSINSPECTION.ELEMENTCODE) AS JML,SUM(WEIGHTNET) AS KGS  
FROM ELEMENTSINSPECTION
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
WHERE ELEMENTITEMTYPECODE='KGF' $wkt ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	$rowdb21 = db2_fetch_assoc($stmt1);
	$sqlDB22 = " SELECT 
COUNT(ELEMENTSINSPECTION.ELEMENTCODE) AS JML,SUM(WEIGHTNET) AS KGS  
FROM ELEMENTSINSPECTION
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
WHERE ELEMENTITEMTYPECODE='KGF' AND ELEMENTSINSPECTION.QUALITYCODE ='1' $wkt ";
	$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	$rowdb22 = db2_fetch_assoc($stmt2);
	$sqlDB23 = " SELECT 
COUNT(ELEMENTSINSPECTION.ELEMENTCODE) AS JML,SUM(WEIGHTNET) AS KGS  
FROM ELEMENTSINSPECTION
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
WHERE ELEMENTITEMTYPECODE='KGF' AND ELEMENTSINSPECTION.QUALITYCODE ='2' $wkt ";
	$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
	$rowdb23 = db2_fetch_assoc($stmt3);
	$sqlDB24 = " SELECT 
COUNT(ELEMENTSINSPECTION.ELEMENTCODE) AS JML,SUM(WEIGHTNET) AS KGS  
FROM ELEMENTSINSPECTION
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
WHERE ELEMENTITEMTYPECODE='KGF' AND ELEMENTSINSPECTION.QUALITYCODE ='3' $wkt ";
	$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
	$rowdb24 = db2_fetch_assoc($stmt4);
	$Hsl =$rowdb21['JML']."A".round($rowdb21['KGS'],2)."B".round($rowdb22['KGS'],2)."C".round($rowdb23['KGS'],2)."D".round($rowdb24['KGS'],2)."E";
	return $Hsl;
}					  
					  
					  
$no=1;   
$c=0;


if ($Akhir == $lastDay) {
    $wkt = " AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-07:00:00' AND '$Akhir-09:00:00' ";
} else {
    $wkt = " AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-07:00:00' AND '$Akhir-07:00:00' ";
}

	$sqlDB21 = " 
	SELECT 
SUBSTR(ELEMENTSINSPECTION.INSPECTIONSTARTDATETIME,1,10) AS TGL  
FROM ELEMENTSINSPECTION
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
WHERE ELEMENTITEMTYPECODE='KGF' $wkt
GROUP BY SUBSTR(ELEMENTSINSPECTION.INSPECTIONSTARTDATETIME,1,10)
 ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}	
	$McNo="";				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){	
		$prodKG 	= produksi($rowdb21['TGL']);
		$pos1 		= strpos($prodKG,'A');
		$rol1  		= substr($prodKG,0,$pos1);
		$pos2 		= strpos($prodKG,'B');
		$prodKG1 	= substr($prodKG,$pos1,300);
		$pos21 		= strpos($prodKG1,'B');
		$kg1	 	= substr($prodKG1,1,$pos21-1);
		$prodKGA 	= substr($prodKG,$pos2,300);
		$pos3 		= strpos($prodKGA,'C');
		$kgA 		= substr($prodKGA,1,$pos3-1);
		$prodKGB 	= substr($prodKGA,$pos3,300);
		$pos4 		= strpos($prodKGB,'D');
		$kgB 		= substr($prodKGB,1,$pos4-1);
		$prodKGC 	= substr($prodKGB,$pos4,300);
		$pos5 		= strpos($prodKGC,'E');
		$kgC 		= substr($prodKGC,1,$pos5-1);
		if($kgA>0){
		$pKGA		= round(($kgA/$kg1)*100,2);}
		else{ 
		$pKGA		= 0;
		}
		if($kgB>0){
		$pKGB		= round(($kgB/$kg1)*100,2);}
		else{ 
		$pKGB		= 0;
		}
		if($kgC>0){
		$pKGC		= round(($kgC/$kg1)*100,2);}
		else{ 
		$pKGC		= 0;
		}
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $rowdb21['TGL']; ?></td>
	  <td style="text-align: center"><?php echo $rol1; ?></td>
      <td style="text-align: center"><?php echo number_format($kg1,2); ?></td>
      <td style="text-align: center"><?php echo number_format($kgA,2); ?></td>
      <td style="text-align: center"><?php echo number_format($pKGA,2); ?></td>
      <td style="text-align: center"><?php echo number_format($kgB,2); ?></td> 
      <td style="text-align: center"><?php echo number_format($pKGB,2); ?></td>
      <td style="text-align: center"><?php echo number_format($kgC,2); ?></td>
      <td style="text-align: center"><?php echo number_format($pKGC,2); ?></td>
      <td style="text-align: center">&nbsp;</td>
      <td>&nbsp;</td>
      </tr>	  				  
	<?php 
	$no++; 
	$totRol+=$rol1;
	$totKG+=round($kg1,2);
	$totKGA+=round($kgA,2);
	$totKGB+=round($kgB,2);
	$totKGC+=round($kgC,2);	
	} 
	if($totKGA>0){				  
	$TPerGRA=round(($totKGA/$totKG)*100,2);}
	if($totKGB>0){				  
	$TPerGRB=round(($totKGB/$totKG)*100,2);}
	if($totKGC>0){				  
	$TPerGRC=round(($totKGC/$totKG)*100,2);}				  
	?>
				  </tbody>
     <tfoot>
	 <tr>
	    <td style="text-align: center"><strong>Total</strong></td>
	    <td style="text-align: center"><strong><?php echo $totRol; ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($totKG,2); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($totKGA,2); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($TPerGRA,2); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($totKGB,2); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($TPerGRB,2); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($totKGC,2); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($TPerGRC,2); ?></strong></td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr> 				
					</tfoot>             
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
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
	$(function () {
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
function checkAll(form1){
    for (var i=0;i<document.forms['form1'].elements.length;i++)
    {
        var e=document.forms['form1'].elements[i];
        if ((e.name !='allbox') && (e.type=='checkbox'))
        {
            e.checked=document.forms['form1'].allbox.checked;
			
        }
    }
}
</script>
<?php 
if(isset($_POST['mutasikain']) && $_POST['mutasikain']=="MutasiKain"){
	
function mutasiurut($con){
$format = "20".date("ymd");
$sql=sqlsrv_query($con,"SELECT TOP 1 no_mutasi FROM dbknitt.tbl_mutasi_kain WHERE SUBSTRING(no_mutasi,1,8) LIKE ? ORDER BY no_mutasi DESC",[$format.'%']);
$Urut = 0;
if($sql && ($r=sqlsrv_fetch_array($sql,SQLSRV_FETCH_ASSOC))){
$str=substr($r['no_mutasi'],8,2);
$Urut = (int)$str;
}
$Urut = $Urut + 1;
$Nol="";
$nilai=2-strlen($Urut);
for ($i=1;$i<=$nilai;$i++){
$Nol= $Nol."0";
}
$tidbr =$format.$Nol.$Urut;
return $tidbr;
}
$nomid=mutasiurut($con);	

$params = [$Awal];
$gshiftClause = "";
if($Gshift!="" && $Gshift!="ALL"){
  $gshiftClause = " AND a.gshift=?";
  $params[] = $Gshift;
}

$sql1=sqlsrv_query($con,"SELECT a.transid,count(b.transid) as jmlrol FROM dbknitt.tbl_mutasi_kain a 
LEFT JOIN dbknitt.tbl_prodemand b ON a.transid=b.transid 
WHERE a.no_mutasi IS NULL AND CONVERT(date,a.tgl_buat)=?{$gshiftClause} 
GROUP BY a.transid",$params);
$n1=1;
$noceklist1=1;	
while($sql1 && ($r1=sqlsrv_fetch_array($sql1,SQLSRV_FETCH_ASSOC))){	
	if(isset($_POST['cek'][$n1]) && $_POST['cek'][$n1]!='') 
		{
		$transid1 = $_POST['cek'][$n1];
		sqlsrv_query($con,"UPDATE dbknitt.tbl_mutasi_kain SET
		no_mutasi=?,
		tgl_mutasi=GETDATE()
		WHERE transid=?",
    [$nomid,$transid1]
		);
		}else{
			$noceklist1++;
	}
	$n1++;
	}
if($noceklist1==$n1){
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
}else{	
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
