<?php
$Awal		= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir		= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Shift		= isset($_POST['shift']) ? $_POST['shift'] : '';
$QReason	= isset($_POST['q_reason']) ? $_POST['q_reason'] : '';
$DemandNo	= isset($_POST['demandno']) ? $_POST['demandno'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1"> 
		<div class="row">
		<div class="col-md-3">	
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Inspeksi Greige</h3>

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
               <label for="tgl_awal" class="col-md-6">Tgl Awal</label>
               <div class="col-md-6">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_awal" value="<?php echo $Awal;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" >
                 </div>
			   </div>	
            </div>
			 <div class="form-group row">
               <label for="tgl_akhir" class="col-md-6">Tgl Akhir</label>
               <div class="col-md-6">  
                 <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" >
                 </div>
			   </div>	
            </div>
			<div class="form-group row">
               <label for="shift" class="col-md-6">Shift</label>
               <div class="col-md-6">  
                 <select name="shift" class="form-control form-control-sm" id="shift">
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
		</div>	
		<div class="col-md-9">
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Detail Defect</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example14" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">ITEMTYPECODE</th>
                    <th valign="middle" style="text-align: center">EVENTCODE</th>
                    <th valign="middle" style="text-align: center">LONGDESCRIPTION</th>
                    <th valign="middle" style="text-align: center">SHORTDESCRIPTION</th>
                    <th valign="middle" style="text-align: center">SEARCHDESCRIPTION</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
$no=1;   
$c=0;			  
	$sqlDB2df = " SELECT ITEMTYPECODE, EVENTCODE ,LONGDESCRIPTION , SHORTDESCRIPTION ,SEARCHDESCRIPTION FROM DB2ADMIN.INSPECTIONEVENTTEMPLATE
	WHERE ITEMTYPECODE ='KGF' ";
	$stmtdf   = db2_exec($conn1,$sqlDB2df, array('cursor'=>DB2_SCROLLABLE));
	
    while($rowdb2df = db2_fetch_assoc($stmtdf)){ 
	
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb2df['ITEMTYPECODE']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb2df['EVENTCODE']; ?></td>
      <td style="text-align: center"><?php  echo $rowdb2df['LONGDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php  echo $rowdb2df['SHORTDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php  echo $rowdb2df['SEARCHDESCRIPTION']; ?></td>
      </tr>				  
	<?php 
	 $no++; } ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
		</div>	
		</div>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example12" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
                  <thead>
                  <tr>
                    <th rowspan="2" valign="middle" style="text-align: center">No</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Nama</th>
                    <th rowspan="2" valign="middle" style="text-align: center">No Mesin</th>
                    <th colspan="2" valign="middle" style="text-align: center">Inspek</th>
                    <th colspan="2" valign="middle" style="text-align: center">Jumlah Roll</th>
                    <th colspan="3" valign="middle" style="text-align: center">TOTAL</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Yard</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">Not Inspek</th>
                    <th valign="middle" style="text-align: center">Reject</th>
                    <th valign="middle" style="text-align: center">Roll</th>
                    <th valign="middle" style="text-align: center">KGs</th>
                    <th valign="middle" style="text-align: center">Yard</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
$no=1;   
$c=0;
if($QReason=="ada"){ $WQreason=" AND (NOT QUALITYREASONCODE IS NULL AND NOT QUALITYREASONCODE='100') ";}
else if($QReason=="tidak"){
$WQreason=" AND (QUALITYREASONCODE IS NULL OR QUALITYREASONCODE='100') ";	
}else{
$WQreason="";	
}	
if($Awal!="" and $Akhir!=""){
$WTgl=" AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) BETWEEN '$Awal' AND '$Akhir' ";	
if($Shift=="1"){ $WTgl=" AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-07:00:00' AND '$Akhir-15:00:00' "; }
elseif($Shift=="2"){ $WTgl=" AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-15:00:00' AND '$Akhir-23:00:00' "; }
elseif($Shift=="3"){ $WTgl=" AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-23:00:00' AND '$Akhir-07:00:00' " ; }
elseif($Shift=="ALL"){ $WTgl=" AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-07:00:00' AND '$Akhir-07:00:00' " ; }
elseif($Shift==""){ $WTgl=" AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) BETWEEN '2021-01-01' AND '2021-01-01' " ; }		
}else{
$WTgl="";	
}		
					  
if($DemandNo!=""){ $WDemand=" AND DEMANDCODE LIKE '%$DemandNo%' ";}else{ $WDemand="";}	
if($WTgl=="" and $WDemand==""){
$Where = " AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) BETWEEN '$Awal' AND '$Akhir' ";	
}else{
$Where = "";	
}
					  
					  
	/*$sqlDB21 = " SELECT OPERATORCODE, INITIALS.LONGDESCRIPTION, SUBSTR(INSPECTIONSTARTDATETIME,1,10) AS TGL, SUBSTR(INSPECTIONSTARTDATETIME,12,8) AS JAM, 
	DEMANDCODE, COUNT(DEMANDCODE) AS JML,SUM(WEIGHTNET) AS KGS,ADSTORAGE.VALUESTRING AS NO_MESIN,SUBSTR(ELEMENTCODE,9,5) AS NROL,ELEMENTCODE, ELEMENTSINSPECTION.QUALITYCODE,QUALITYREASONCODE, LENGTHGROSS, TOTALPOINTS,A1.VALUESTRING AS NOTE,A2.VALUESTRING AS DISPOSISI  
	FROM ELEMENTSINSPECTION
LEFT OUTER JOIN DB2ADMIN.INITIALS ON INITIALS.CODE=ELEMENTSINSPECTION.OPERATORCODE	
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE A1 ON A1.UNIQUEID = ELEMENTSINSPECTION.ABSUNIQUEID AND A1.NAMENAME ='Note'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE A2 ON A2.UNIQUEID = ELEMENTSINSPECTION.ABSUNIQUEID AND A2.NAMENAME ='Disposisi'
	WHERE ELEMENTITEMTYPECODE='KGF' $Where $WTgl $WQreason $WDemand
GROUP BY SUBSTR(INSPECTIONSTARTDATETIME,1,10),SUBSTR(INSPECTIONSTARTDATETIME,12,8),DEMANDCODE,OPERATORCODE,
INITIALS.LONGDESCRIPTION,ADSTORAGE.VALUESTRING,ELEMENTCODE, QUALITYREASONCODE, ELEMENTSINSPECTION.QUALITYCODE, LENGTHGROSS, TOTALPOINTS,A1.VALUESTRING,A2.VALUESTRING ";*/
	$sqlDB21 = " 
	SELECT INITIALS.LONGDESCRIPTION, 
	 COUNT(INITIALS.LONGDESCRIPTION) AS JML,SUM(WEIGHTNET) AS KGS, COUNT(QUALITYREASONCODE) AS REJECT,
	 SUM(CASE WHEN INSPECTIONTIME>'00:02' THEN 1 ELSE 0 END) AS INSPK,
	 SUM(CASE WHEN INSPECTIONTIME>'00:02' THEN LENGTHGROSS ELSE 0 END) AS YD,
	 SUM(CASE WHEN INSPECTIONTIME<='00:02' THEN 1 ELSE 0 END) AS NOINSPK,
	 SUM(LENGTHGROSS) AS TOTYD 
	 
	FROM ELEMENTSINSPECTION
LEFT OUTER JOIN DB2ADMIN.INITIALS ON INITIALS.CODE=ELEMENTSINSPECTION.OPERATORCODE	
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE A1 ON A1.UNIQUEID = ELEMENTSINSPECTION.ABSUNIQUEID AND A1.NAMENAME ='Note'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE A2 ON A2.UNIQUEID = ELEMENTSINSPECTION.ABSUNIQUEID AND A2.NAMENAME ='Disposisi'
	WHERE ELEMENTITEMTYPECODE='KGF' $Where $WTgl 
	GROUP BY INITIALS.LONGDESCRIPTION
	";				  
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}	
	$McNo="";
	$grd="";
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$sqlDB23 =" SELECT *,CURRENT_TIMESTAMP AS TGLS,
CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END  AS PROJECT FROM ITXVIEWHEADERKNTORDER WHERE ITEMTYPEAFICODE ='KGF' AND CODE ='".$rowdb21['DEMANDCODE']."' ";	
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);
$kdkain=trim($rowdb23['SUBCODE02'])."".trim($rowdb23['SUBCODE03'])." ".trim($rowdb23['SUBCODE04']);	
if($rowdb23['SCHEDULEDRESOURCECODE']!=""){ $McNo=$rowdb23['SCHEDULEDRESOURCECODE']; }else { $McNo=$rowdb21['NO_MESIN']; }		
$sqlKt=mysqli_query($con," SELECT no_mesin FROM tbl_mesin WHERE kd_dtex='".$McNo."' LIMIT 1");
$rk=mysqli_fetch_array($sqlKt);	
if($rowdb21['LONGDESCRIPTION']!=""){$uid=trim($rowdb21['LONGDESCRIPTION']);}else{$uid=trim($rowdb21['CREATIONUSER']);}	
$sqlDB24 =" SELECT LISTAGG(TRIM(e.CODEEVENTCODE),', ') AS CODEEVENTCODE 
FROM ELEMENTSINSPECTIONEVENT e WHERE ELEMENTSINSPECTIONELEMENTCODE ='".$rowdb21['ELEMENTCODE']."' ";	
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['LONGDESCRIPTION']; ?></td>
	  <td>&nbsp;</td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['YD'],2),2); ?></td>
      <td style="text-align: center"><?php echo $rowdb21['INSPK']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['NOINSPK']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['REJECT']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['JML']; ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['KGS'],2),2); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['TOTYD'],2),2); ?></td>
      </tr>	  				  
	<?php 
	 $no++; 
	$TYD+=round($rowdb21['YD'],2);
	$TTOTYD+=round($rowdb21['TOTYD'],2);	
	$TINSPK+=$rowdb21['INSPK'];
	$TNOINSPK+=$rowdb21['NOINSPK'];
	$TREJECT+=$rowdb21['REJECT'];
	$TROLL+=$rowdb21['JML'];;
	$TKGS+=round($rowdb21['KGS'],2);	
	} ?>
				  </tbody>
      <tfoot>
		  <tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td><strong>Grand Total</strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($TYD,2),2); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo $TINSPK; ?></strong></td>
	    <td style="text-align: center"><strong><?php echo $TNOINSPK; ?></strong></td>
	    <td style="text-align: center"><strong><?php echo $TREJECT; ?></strong></td>
	    <td style="text-align: center"><strong><?php echo $TROLL; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($TKGS,2),2); ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($TTOTYD,2),2); ?></strong></td>
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

