<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Shift	= isset($_POST['shift']) ? $_POST['shift'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Tgl Tambah Stok</h3>

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
			<div class="form-group row">
               <label for="shift" class="col-md-1">Shift</label>
               <div class="col-md-2">  
                 <select name="shift" class="form-control form-control-sm" id="shift" required>
				   <option value="">Pilih</option>
				   <option value="ALL" <?php if($Shift=="ALL"){echo "SELECTED";} ?>>ALL</option>	 
				   <option value="1" <?php if($Shift=="1"){echo "SELECTED";} ?>>1</option>
				   <option value="2" <?php if($Shift=="2"){echo "SELECTED";} ?>>2</option>
				   <option value="3" <?php if($Shift=="3"){echo "SELECTED";} ?>>3</option>	 
				   </select>
			   </div>	
            </div>	 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Tambah Stok Benang</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Trn. No</th>
                    <th valign="middle" style="text-align: center">TGL</th>
                    <th valign="middle" style="text-align: center">Shift</th>
                    <th valign="middle" style="text-align: center">User</th>
                    <th valign="middle" style="text-align: center">KNITT</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">LOT</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
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
	STOCKTRANSACTION.PROJECTCODE,
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
	SELECT MCN.NOMC,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08  FROM PRODUCTIONDEMANDSTEP p
	LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE =p.PRODUCTIONDEMANDCODE 
	LEFT OUTER JOIN (SELECT ADSTORAGE.VALUESTRING AS NOMC,PRODUCTIONDEMAND.CODE FROM PRODUCTIONDEMAND
 	LEFT OUTER JOIN ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo') MCN ON MCN.CODE=p.PRODUCTIONDEMANDCODE
	GROUP BY p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,pd.SUBCODE03,
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,MCN.NOMC
	) KD ON KD.PRODUCTIONORDERCODE=STOCKTRANSACTION.ORDERCODE
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
	LEFT OUTER JOIN DB2ADMIN.INITIALS INITIALS ON 
	INITIALS.CODE =STOCKTRANSACTION.CREATIONUSER
WHERE STOCKTRANSACTION.ITEMTYPECODE ='GYR' and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.TEMPLATECODE ='OPN' AND STOCKTRANSACTION.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' $wkt
GROUP BY 
	STOCKTRANSACTION.TRANSACTIONNUMBER,
    STOCKTRANSACTION.PROJECTCODE,
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
		
$sqlDB22 = " SELECT TRANSACTIONNUMBER,TRANSACTIONDATE,TRANSACTIONTIME FROM STOCKTRANSACTION WHERE STOCKTRANSACTION.ITEMTYPECODE ='GYR' and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.TEMPLATECODE ='OPN' AND STOCKTRANSACTION.TRANSACTIONDATE='$rowdb21[TRANSACTIONDATE]'
AND STOCKTRANSACTION.CREATIONUSER='$rowdb21[CREATIONUSER]' ";
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
$sqlKt=sqlsrv_query($con," SELECT TOP 1 no_mesin FROM dbknitt.tbl_mesin WHERE kd_dtex='".$msin."'");
$rk=sqlsrv_fetch_array($sqlKt);	
if($rowdb21['LONGDESCRIPTION']!=""){$uid=trim($rowdb21['LONGDESCRIPTION']);}else{$uid=trim($rowdb21['CREATIONUSER']);}	


		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONNUMBER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><?php echo $shf; ?></td>
	  <td style="text-align: center"><?php echo $uid; ?></td>
      <td style="text-align: center"><?php echo $knitt; ?></td>
      <td><?php echo $kdbenang; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['QTY_DUS']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      </tr>
	 				  
	<?php 
	 $no++;
	$tRol+=$rowdb21['QTY_DUS'];
		$tCones+=$rowdb21['QTY_CONES'];
		$tKg+=$rowdb21['QTY_KG'];
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
	    <td>&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: left">Total</td>
	    <td style="text-align: right"><strong><?php echo $tRol; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tCones; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKg,2),2); ?></strong></td>
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
