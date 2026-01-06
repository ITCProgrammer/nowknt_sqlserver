<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data <span style="text-align: center">Delivery date</span>  Permohonan</h3>

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
                <h3 class="card-title">Detail Data Permohonan Benang</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Create date</th>
                    <th valign="middle" style="text-align: center">User</th>
                    <th valign="middle" style="text-align: center">Status</th>
                    <th valign="middle" style="text-align: center">No Bon</th>
                    <th valign="middle" style="text-align: center">Line</th>
                    <th valign="middle" style="text-align: center">Warehouse</th>
                    <th valign="middle" style="text-align: center">Project</th>
                    <th valign="middle" style="text-align: center">No Artikel</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Kd Benang</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Quality</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Unit</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Lokasi</th>
                    <th valign="middle" style="text-align: center">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT x.*,ROUND(x.BASEPRIMARYQUANTITY,2) AS QTY_KG, a1.VALUESTRING AS supp
,a2.VALUESTRING AS LOT
,a3.VALUEDECIMAL AS QTY
,a4.VALUESTRING AS LOKASI
,a5.VALUESTRING AS KET
,a6.VALUESTRING AS KDBENANG
,a7.VALUESTRING AS SATUAN
FROM DB2ADMIN.INTERNALDOCUMENTLINE x
LEFT OUTER JOIN INTERNALDOCUMENT y ON y.PROVISIONALCODE=x.INTDOCUMENTPROVISIONALCODE AND y.PROVISIONALCOUNTERCODE=x.INTDOCPROVISIONALCOUNTERCODE
LEFT OUTER JOIN ADSTORAGE a1 ON a1.UNIQUEID =x.ABSUNIQUEID AND a1.NAMENAME ='SuppName'
LEFT OUTER JOIN ADSTORAGE a2 ON a2.UNIQUEID =x.ABSUNIQUEID AND a2.NAMENAME ='Lot'
LEFT OUTER JOIN ADSTORAGE a3 ON a3.UNIQUEID =x.ABSUNIQUEID AND a3.NAMENAME ='QtyP'
LEFT OUTER JOIN ADSTORAGE a4 ON a4.UNIQUEID =x.ABSUNIQUEID AND a4.NAMENAME ='LokasiB'
LEFT OUTER JOIN ADSTORAGE a5 ON a5.UNIQUEID =x.ABSUNIQUEID AND a5.NAMENAME ='KetB'
LEFT OUTER JOIN ADSTORAGE a6 ON a6.UNIQUEID =x.ABSUNIQUEID AND a6.NAMENAME ='KdBenang'
LEFT OUTER JOIN ADSTORAGE a7 ON a7.UNIQUEID =x.ABSUNIQUEID AND a7.NAMENAME ='Satuan'
WHERE (x.ITEMTYPEAFICODE = 'GYR' OR x.ITEMTYPEAFICODE = 'DYR') AND 
SUBSTR(x.RECEIVINGDATE,1,10) BETWEEN '$Awal' AND '$Akhir' AND 
(x.DESTINATIONWAREHOUSECODE ='M904' OR x.DESTINATIONWAREHOUSECODE ='P501')
AND (y.TEMPLATECODE<>'I06')
ORDER BY x.INTDOCUMENTPROVISIONALCODE, x.ORDERLINE "; 
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}						  
    while($rowdb21 = db2_fetch_assoc($stmt1)){
$bon=$rowdb21['INTDOCUMENTPROVISIONALCODE']."-".$rowdb21['ORDERLINE'];
if (trim($rowdb21['DESTINATIONWAREHOUSECODE']) =='M501' or trim($rowdb21['DESTINATIONWAREHOUSECODE']) =='M904') { $knitt = 'LT2'; }
else if(trim($rowdb21['DESTINATIONWAREHOUSECODE']) =='P501'){ $knitt = 'LT1'; }
$kdbenang=$rowdb21['SUBCODE01']." ".$rowdb21['SUBCODE02']." ".$rowdb21['SUBCODE03']." ".$rowdb21['SUBCODE04']." ".$rowdb21['SUBCODE05']." ".$rowdb21['SUBCODE06']." ".$rowdb21['SUBCODE07']." ".$rowdb21['SUBCODE08'];
		
$idesc 	= $rowdb21['ITEMDESCRIPTION'];
$pos 	= strpos($idesc,"*");
$supp	= substr($idesc,0,$pos);
		
$idesc1 = substr($idesc,$pos+1,300);
$pos1	= strpos($idesc1,"*");
$lot	= substr($idesc,$pos+1,$pos1);	

$Art	= $rowdb21['INTERNALREFERENCE'];
$posA	= strpos($Art," ");
$NoArt	= substr($Art,0,$posA);		
if($rowdb21['SATUAN']=="0"){
$satuan	="DUS";
}else if($rowdb21['SATUAN']=="1"){
$satuan	="KARUNG";	}
else if($rowdb21['SATUAN']=="2"){
$satuan	="CONES";	}
if($rowdb21['PROGRESSSTATUS']=="0"){	
	$stts="<small class='badge badge-info'><i class='far fa-clock blink_me'></i> Entered</small>";	
	}else if($rowdb21['PROGRESSSTATUS']=="1"){	
	$stts="<small class='badge badge-warning'><i class='far fa-clock blink_me'></i> Partially Shipped</small>";	
	}else if($rowdb21['PROGRESSSTATUS']=="2"){	
	$stts="<small class='badge badge-danger'><i class='far fa-clock blink_me'></i> Shipped</small>";	
	}		
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php //echo substr($rowdb21['RECEIVINGDATE'],0,10); 
		  echo date('Y-m-d H:i:s', strtotime($rowdb21['CREATIONDATETIME'])); ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['CREATIONUSER']; ?></td>
	  <td style="text-align: center"><?php echo $stts; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['INTDOCUMENTPROVISIONALCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['ORDERLINE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['DESTINATIONWAREHOUSECODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td style="text-align: left"><?php //if($posA=="0"){echo $Art;}else{echo $NoArt;} 
		  echo $Art; ?></td> 
      <td style="text-align: left"><?php echo $kdbenang; ?></td>
      <td style="text-align: left"><?php  echo $rowdb21['KDBENANG'];  ?></td>
      <td style="text-align: left"><?php if($supp!=""){echo $supp;}else{ echo $rowdb21['SUPP']; } ?></td>
      <td style="text-align: left"><?php if($lot!=""){echo $lot;}else{ echo $rowdb21['LOT']; } ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QUALITYCODE']; ?></td>
      <td style="text-align: right"><?php  echo round($rowdb21['QTY']);  ?></td>
      <td style="text-align: right"><?php  echo $satuan;  ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['BASESECONDARYQUANTITY']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td style="text-align: left"><?php  echo $rowdb21['LOKASI'];  ?></td>
      <td style="text-align: left"><?php  echo $rowdb21['KET'];  ?></td>
      </tr>
	  				  
	<?php 
	 	$no++; 		
		$tCones+=$rowdb21['BASESECONDARYQUANTITY'];
		$tQty+=$rowdb21['QTY'];
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
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right"><span style="text-align: center"><strong>Total</strong></span></td>
	    <td style="text-align: right"><strong><?php echo $tQty; ?></strong></td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong><?php echo $tCones; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKg,2),2); ?></strong></td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
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
