<?php
$Project = isset($_POST['project']) ? $_POST['project'] : '';
$KdBng = isset($_POST['kdbg']) ? $_POST['kdbg'] : '';	    	 
	
$sqlDB22PRO =" SELECT ITXVIEWKK.PROJECTCODE FROM 
 PRODUCTIONORDER  
 LEFT OUTER JOIN ( SELECT ugp.LONGDESCRIPTION AS WARNA, pr.LONGDESCRIPTION AS JNSKAIN,pd.PROJECTCODE,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08  FROM PRODUCTIONDEMANDSTEP p
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
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,ugp.LONGDESCRIPTION) ITXVIEWKK ON PRODUCTIONORDER.CODE=ITXVIEWKK.PRODUCTIONORDERCODE
 WHERE ITXVIEWKK.PRODUCTIONORDERCODE='$ProdOrder' ";	
$stmt2PRO   = db2_exec($conn1,$sqlDB22PRO, array('cursor'=>DB2_SCROLLABLE));
$rowdb22PRO = db2_fetch_assoc($stmt2PRO);
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">
		<div class="row">
          <div class="col-md-12">	
		<div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Filter Identifikasi Benang Per Project</h3>

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
               <label for="project" class="col-md-1">Project</label>
               <div class="col-md-2"> 
                    <input name="project" value="<?php echo $Project;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off">
			   </div>	
              </div>
          </div>
		  <div class="card-footer">
			  <button class="btn btn-primary" type="submit" >Cari Data</button>
		  </div>	  
		  <!-- /.card-body -->          
        </div>  
		</div>
			
		</div>
		<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Pemakaian Benang</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <table id="example12" width="100%"class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">Trn No</th>
                    <th valign="middle" style="text-align: center">TGL</th>
                    <th valign="middle" style="text-align: center">Shift</th>
                    <th valign="middle" style="text-align: center">User</th>
                    <th valign="middle" style="text-align: center">KNITT</th>
                    <th valign="middle" style="text-align: center">Project</th>
                    <th valign="middle" style="text-align: center">Prod. Order</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">LOT</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Mesin</th>
                    <th valign="middle" style="text-align: center">No Mesin</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	$c=0;					  
	$sqlDB21PB = " SELECT * FROM
(SELECT ITXVIEWKK.PROJECTCODE,ITXVIEWKK.ORIGDLVSALORDLINESALORDERCODE,PRODUCTIONORDER.CODE FROM 
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
) ORD 
INNER JOIN 
(SELECT 
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
	MCN.NOMC,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
	FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
	DB2ADMIN.ITXVIEWKNTORDER ITXVIEWKNTORDER ON ITXVIEWKNTORDER.PRODUCTIONORDERCODE =STOCKTRANSACTION.ORDERCODE 
	LEFT OUTER JOIN (
 	SELECT ADSTORAGE.VALUESTRING AS NOMC,PRODUCTIONDEMAND.CODE FROM PRODUCTIONDEMAND
 	LEFT OUTER JOIN ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
 	) MCN ON MCN.CODE=ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.ONHANDUPDATE >1 AND NOT ORDERCODE IS NULL
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
	MCN.NOMC,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
) PAKAI ON ORD.CODE=PAKAI.ORDERCODE
WHERE ( ORD.PROJECTCODE='$Project' OR ORD.ORIGDLVSALORDLINESALORDERCODE='$Project')
";
	$stmt1PB   = db2_exec($conn1,$sqlDB21PB, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21PB = db2_fetch_assoc($stmt1PB)){ 
if (trim($rowdb21PB['LOGICALWAREHOUSECODE']) =='M501' or trim($rowdb21PB['LOGICALWAREHOUSECODE']) =='M904') { $knittPB = 'LT2'; }
else if(trim($rowdb21PB['LOGICALWAREHOUSECODE']) =='P501'){ $knittPB = 'LT1'; }
$kdbenangPB=trim($rowdb21PB['DECOSUBCODE01'])." ".trim($rowdb21PB['DECOSUBCODE02'])." ".trim($rowdb21PB['DECOSUBCODE03'])." ".trim($rowdb21PB['DECOSUBCODE04'])." ".trim($rowdb21PB['DECOSUBCODE05'])." ".trim($rowdb21PB['DECOSUBCODE06'])." ".trim($rowdb21PB['DECOSUBCODE07'])." ".trim($rowdb21PB['DECOSUBCODE08']);

$sqlDB22PB = " SELECT TRANSACTIONDATE,TRANSACTIONTIME FROM STOCKTRANSACTION WHERE 
(STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.ONHANDUPDATE >1 AND STOCKTRANSACTION.TRANSACTIONDATE='$rowdb21PB[TRANSACTIONDATE]' 
AND STOCKTRANSACTION.ORDERCODE='$rowdb21PB[ORDERCODE]' AND STOCKTRANSACTION.CREATIONUSER='$rowdb21PB[CREATIONUSER]' ";
$stmt2PB   = db2_exec($conn1,$sqlDB22PB, array('cursor'=>DB2_SCROLLABLE));
$rowdb22PB = db2_fetch_assoc($stmt2PB);		
if($rowdb22PB['TRANSACTIONTIME']>="07:00:00" and $rowdb22PB['TRANSACTIONTIME']<="15:00:00"){
	$shfPB="1";
}elseif($rowdb22PB['TRANSACTIONTIME']>="15:00:00" and $rowdb22PB['TRANSACTIONTIME']<="23:00:00"){
	$shfPB="2";
}else{
	$shfPB="3";
}		
$msinPB = $rowdb21PB['NOMC'];	
$sqlDB22PROPB =" SELECT ITXVIEWKK.PROJECTCODE,ITXVIEWKK.ORIGDLVSALORDLINESALORDERCODE FROM 
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
 WHERE ITXVIEWKK.PRODUCTIONORDERCODE='$rowdb21PB[ORDERCODE]' ";	
$stmt2PROPB   = db2_exec($conn1,$sqlDB22PROPB, array('cursor'=>DB2_SCROLLABLE));
$rowdb22PROPB = db2_fetch_assoc($stmt2PROPB);
		
$sqlKtPB=sqlsrv_query($con," SELECT TOP 1 no_mesin FROM dbknitt.tbl_mesin WHERE kd_dtex='".$msinPB."'");
$rkPB=sqlsrv_fetch_array($sqlKtPB);
?>
	  <tr>
	  <td style="text-align: center"><?php echo $rowdb21PB['TRANSACTIONNUMBER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21PB['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><?php echo $shfPB; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21PB['CREATIONUSER']; ?></td>
      <td style="text-align: center"><?php echo $knittPB; ?></td>
      <td style="text-align: center"><?php if($rowdb21PB['PROJECTCODE']!=""){echo $rowdb21PB['PROJECTCODE'];}else{echo $rowdb21PB['ORIGDLVSALORDLINESALORDERCODE'];} ?></td>
      <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb21PB['ORDERCODE'])."#".trim($rowdb21PB['TRANSACTIONDATE'])."#".trim($rowdb21PB['CREATIONUSER'])."#".trim($rowdb21PB['LOTCODE'])."#".trim($kdbenangPB); ?>" class="show_detailPakai"><?php echo $rowdb21PB['ORDERCODE']; ?></a></td>
      <td><?php echo $kdbenangPB; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21PB['LOTCODE']; ?></td>
      <td style="text-align: left"><?php echo $rowdb21PB['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21PB['QTY_DUS']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21PB['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21PB['QTY_KG'],2),2); ?></td>
      <td><?php  echo $msinPB; ?></td>
      <td><?php  echo $rkPB['no_mesin']; ?></td>
      </tr>	  				  
	<?php 
		$tRolPB+=$rowdb21PB['QTY_DUS'];
		$tConesPB+=$rowdb21PB['QTY_CONES'];
		$tKgPB+=$rowdb21PB['QTY_KG'];
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
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: left">Total</td>
	    <td style="text-align: right"><strong><?php echo $tRolPB; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tConesPB; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKgPB,2),2); ?></strong></td>
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
if($_POST['mutasikain']=="MutasiKain"){
	
function mutasiurut(){
include "koneksi.php";		
$format = "20".date("ymd");
$sql=mysqli_query($con,"SELECT no_mutasi FROM tbl_mutasi_kain WHERE substr(no_mutasi,1,8) like '%".$format."%' ORDER BY no_mutasi DESC LIMIT 1 ");
$d=mysqli_num_rows($sql);
if($d>0){
$r=mysqli_fetch_array($sql);
$d=$r['no_mutasi'];
$str=substr($d,8,2);
$Urut = (int)$str;
}else{
$Urut = 0;
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
$nomid=mutasiurut();	

$sql1=mysqli_query($con,"SELECT *,count(b.transid) as jmlrol,a.transid as kdtrans FROM tbl_mutasi_kain a 
LEFT JOIN tbl_prodemand b ON a.transid=b.transid 
WHERE isnull(a.no_mutasi) AND date_format(a.tgl_buat ,'%Y-%m-%d')='$Awal' AND a.gshift='$Gshift' 
GROUP BY a.transid");
$n1=1;
$noceklist1=1;	
while($r1=mysqli_fetch_array($sql1)){	
	if($_POST['cek'][$n1]!='') 
		{
		$transid1 = $_POST['cek'][$n1];
		mysqli_query($con,"UPDATE tbl_mutasi_kain SET
		no_mutasi='$nomid',
		tgl_mutasi=now()
		WHERE transid='$transid1'
		");
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