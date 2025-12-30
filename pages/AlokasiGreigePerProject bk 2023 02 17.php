<?php
$Project	= isset($_POST['projectcode']) ? $_POST['projectcode'] : '';
$HangerNO	= isset($_POST['hangerno']) ? $_POST['hangerno'] : '';
$subC1		= substr($HangerNO,0,3);
$subC2		= substr($HangerNO,3,5);
if(strlen(trim($subC2))=="4"){
$subC3		= substr($HangerNO,8,3);	
}else if(strlen(trim($subC2))=="5"){
$subC3		= substr($HangerNO,9,3); 	
}


$sqlDB2 =" SELECT SUBCODE02,SUBCODE03,SUBCODE04, SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWKNTORDER 
WHERE ITEMTYPEAFICODE ='KGF' AND (PROJECTCODE ='$Project' OR ORIGDLVSALORDLINESALORDERCODE='$Project') AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6')
GROUP BY SUBCODE02,SUBCODE03,SUBCODE04,CURRENT_TIMESTAMP  ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
?>
<!-- Main content -->
      <div class="container-fluid">
	<div class="row">
	<div class="col-md-4">	  
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data</h3>

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
               <label for="projectcode" class="col-md-1">Project</label>
               <div class="col-md-2"> 
                    <input name="projectcode" value="<?php echo $Project;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
			   </div>	
            </div>
				 <div class="form-group row">
                    <label for="hangerno" class="col-md-1">No. Hanger</label>
					<div class="col-md-2"> 
                    <select name="hangerno" class="form-control form-control-sm"  autocomplete="off">
						<option value="">Pilih</option>
						<?php while($rowdb2 = db2_fetch_assoc($stmt)){?>
						<option value="<?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?>" <?php if($HangerNO==trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04'])){ echo "SELECTED";}?>><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?></option>
						<?php } ?>
					</select>	
                  </div>	
                  </div>
			  <button class="btn btn-primary" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
		</form>	
	</div>      
	<?php
		  $sqlDB21 =" SELECT a.SUBCODE02,a.SUBCODE03,SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN,CURRENT_TIMESTAMP AS TGLS  FROM ITXVIEWKNTORDER a
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID =a2.UNIQUEID AND a2.NAMENAME ='StatusRMP'
LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID =a3.UNIQUEID AND a3.NAMENAME ='QtySalin'
WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$Project' OR a.ORIGDLVSALORDLINESALORDERCODE='$Project') AND
a.SUBCODE02='$subC1' AND a.SUBCODE03='$subC2' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6') AND (NOT a2.VALUESTRING ='3' OR a2.VALUESTRING IS NULL)
GROUP BY a.SUBCODE02,a.SUBCODE03,CURRENT_TIMESTAMP
		";	
		  $stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb21 = db2_fetch_assoc($stmt1);
		  
		  $sqlDB2R =" SELECT SUM(INSP.JQTY) AS JQTY  FROM ITXVIEWKNTORDER a
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN (
SELECT DEMANDCODE, COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
		  ELEMENTSINSPECTION WHERE ELEMENTITEMTYPECODE='KGF'
GROUP BY DEMANDCODE		  
) INSP ON INSP.DEMANDCODE=p.CODE
WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$Project' OR a.ORIGDLVSALORDLINESALORDERCODE='$Project') AND
a.SUBCODE02='$subC1' AND a.SUBCODE03='$subC2' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6')";	
		  $stmtR   = db2_exec($conn1,$sqlDB2R, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb2R = db2_fetch_assoc($stmtR);
		  $kRajut  = round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2)-round($rowdb2R['JQTY'],2);
		  ?>
<div class="col-md-4">		  
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"> Benang</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">			  	  
	<table id="example2" class="table table-sm table-bordered table-striped" style="font-size:13px;">
        <thead>
                  <tr>
                    <th style="text-align: center; vertical-align: middle">Group Line</th>
                    <th style="text-align: center; vertical-align: middle">Kode Benang</th>
                    <th style="text-align: center; vertical-align: middle">Jenis Benang</th>
          </tr>
        </thead>
                  <tbody>
				  <?php
$sqlDB22 =" 
SELECT ITXVIEWKNTORDER.PROJECTCODE,
ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE,
PRODUCTIONRESERVATION.GROUPLINE,
PRODUCTIONRESERVATION.ITEMTYPEAFICODE,
PRODUCTIONRESERVATION.SUBCODE01,
PRODUCTIONRESERVATION.SUBCODE02,
PRODUCTIONRESERVATION.SUBCODE03,
PRODUCTIONRESERVATION.SUBCODE04,
PRODUCTIONRESERVATION.SUBCODE05,
PRODUCTIONRESERVATION.SUBCODE06,
PRODUCTIONRESERVATION.SUBCODE07,
PRODUCTIONRESERVATION.SUBCODE08,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION FROM DB2ADMIN.ITXVIEWKNTORDER 
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONRESERVATION ON PRODUCTIONRESERVATION.PRODUCTIONORDERCODE = ITXVIEWKNTORDER.PRODUCTIONORDERCODE 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
PRODUCTIONRESERVATION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE ITXVIEWKNTORDER.PROJECTCODE='$Project' OR ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE='$Project'
GROUP BY ITXVIEWKNTORDER.PROJECTCODE,
ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE,
PRODUCTIONRESERVATION.ITEMTYPEAFICODE,
PRODUCTIONRESERVATION.SUBCODE01,
PRODUCTIONRESERVATION.SUBCODE02,
PRODUCTIONRESERVATION.SUBCODE03,
PRODUCTIONRESERVATION.SUBCODE04,
PRODUCTIONRESERVATION.SUBCODE05,
PRODUCTIONRESERVATION.SUBCODE06,
PRODUCTIONRESERVATION.SUBCODE07,
PRODUCTIONRESERVATION.SUBCODE08,
PRODUCTIONRESERVATION.GROUPLINE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
ORDER BY PRODUCTIONRESERVATION.GROUPLINE ASC

	   ";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
					  
$no=1; 
$c=0;
 while ($rowdb22 = db2_fetch_assoc($stmt2)) {	
	 $kdbenang=trim($rowdb22['SUBCODE01'])." ".trim($rowdb22['SUBCODE02']).trim($rowdb22['SUBCODE03'])." ".trim($rowdb22['SUBCODE04'])." ".trim($rowdb22['SUBCODE05'])." ".trim($rowdb22['SUBCODE06'])." ".trim($rowdb22['SUBCODE07'])." ".trim($rowdb22['SUBCODE08']);	  
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $rowdb22['GROUPLINE']; ?></td>
      <td><?php echo $kdbenang; ?></td>
      <td><?php echo $rowdb22['SUMMARIZEDDESCRIPTION']; ?></td>
      </tr>				  
	<?php 
	 $no++;} ?>
				  </tbody>
                  
                </table>
          </div>
              <!-- /.card-body -->
        </div>  
</div>	
		  
      </div>
<div class="row">
		  
<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Alokasi Order</h3>
              </div>
              <!-- /.card-header -->
      <div class="card-body">
			  <?php if($HangerNO!=""){ ?>	  
			  Qty Order:  <strong><?php echo round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2)." Kgs";?></strong><br>
			  Kurang Rajut: <strong><font color="<?php if($kRajut < 1){ echo "RED"; }?>"><?php echo round($kRajut,2)." Kgs"; ?></font></strong><br>
			  <?php } ?>
<table id="example3" class="table table-sm table-bordered table-striped" style="font-size:13px;">
        <thead>
                  <tr>
                    <th height="38" style="text-align: center; vertical-align: middle">No</th>
                    <th style="text-align: center; vertical-align: middle">Tgl Delivery</th>
                    <th style="text-align: center; vertical-align: middle">Delivery Kain Jadi</th>
                    <th style="text-align: center; vertical-align: middle">Order</th>
                    <th style="text-align: center; vertical-align: middle">Konsumen</th>
                    <th style="text-align: center; vertical-align: middle">Celup</th>
                    <th style="text-align: center; vertical-align: middle">Qty per Order</th>
                    <th style="text-align: center; vertical-align: middle">Kurang Rajut</th>
                    <th style="text-align: center; vertical-align: middle">Delay</th>
          </tr>
        </thead>
                  <tbody>
				  <?php
$sqlDB ="
SELECT p.CODE, p.ORIGDLVSALORDLINESALORDERCODE,p.SUBCODE02, p.SUBCODE03 ,p.SUBCODE04,  
CASE 
	WHEN a.VALUESTRING='$Project' THEN
	b.VALUEDECIMAL
	ELSE '0'
END AS qty1,
CASE 
	WHEN a2.VALUESTRING='$Project' THEN
	b2.VALUEDECIMAL
	ELSE '0'
END AS qty2,
CASE 
	WHEN a3.VALUESTRING='$Project' THEN
	b3.VALUEDECIMAL
	ELSE '0'
END AS qty3,
CASE 
	WHEN a4.VALUESTRING='$Project' THEN
	b4.VALUEDECIMAL
	ELSE '0'
END AS qty4,
CASE 
	WHEN a5.VALUESTRING='$Project' THEN
	b5.VALUEDECIMAL
	ELSE '0'
END AS qty5
FROM PRODUCTIONDEMAND p 
LEFT OUTER JOIN ADSTORAGE a  ON p.ABSUNIQUEID = a.UNIQUEID  AND a.NAMENAME  ='ProAllow' 
LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID = a2.UNIQUEID AND a2.NAMENAME ='ProAllow2'
LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID = a3.UNIQUEID AND a3.NAMENAME ='ProAllow3'
LEFT OUTER JOIN ADSTORAGE a4 ON p.ABSUNIQUEID = a4.UNIQUEID AND a4.NAMENAME ='ProAllow4'
LEFT OUTER JOIN ADSTORAGE a5 ON p.ABSUNIQUEID = a5.UNIQUEID AND a5.NAMENAME ='ProAllow5'
LEFT OUTER JOIN ADSTORAGE b  ON p.ABSUNIQUEID = b.UNIQUEID  AND b.NAMENAME  ='ProAllowQty' 
LEFT OUTER JOIN ADSTORAGE b2 ON p.ABSUNIQUEID = b2.UNIQUEID AND b2.NAMENAME ='ProAllowQty2'
LEFT OUTER JOIN ADSTORAGE b3 ON p.ABSUNIQUEID = b3.UNIQUEID AND b3.NAMENAME ='ProAllowQty3'
LEFT OUTER JOIN ADSTORAGE b4 ON p.ABSUNIQUEID = b4.UNIQUEID AND b4.NAMENAME ='ProAllowQty4'
LEFT OUTER JOIN ADSTORAGE b5 ON p.ABSUNIQUEID = b5.UNIQUEID AND b5.NAMENAME ='ProAllowQty5'

WHERE (a.VALUESTRING='$Project' OR a2.VALUESTRING='$Project' OR a3.VALUESTRING='$Project' OR a4.VALUESTRING='$Project' OR a5.VALUESTRING='$Project')
AND p.ITEMTYPEAFICODE='KFF' AND p.ENTRYWAREHOUSECODE='M504' AND p.SUBCODE02 = '$subC1' AND  p.SUBCODE03='$subC2' AND p.SUBCODE04='$subC3'





	   ";	
$stm   = db2_exec($conn1,$sqlDB, array('cursor'=>DB2_SCROLLABLE));
					  
$no=1; 
 while ($rowdb = db2_fetch_assoc($stm)) {	
	 $qtyprOrder=$rowdb['QTY1']+$rowdb['QTY2']+$rowdb['QTY3']+$rowdb['QTY4']+$rowdb['QTY5'];
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $no; ?></td>
      <td>&nbsp;</td>
      <td style="text-align: right">&nbsp;</td>
      <td style="text-align: right"><?php echo $rowdb['ORIGDLVSALORDLINESALORDERCODE']?></td>
      <td style="text-align: right">&nbsp;</td>
      <td style="text-align: right">&nbsp;</td>
      <td style="text-align: right"><?php echo $qtyprOrder;?></td>
      <td style="text-align: right">&nbsp;</td>
      <td style="text-align: right">&nbsp;</td>
      </tr>
	  <?php 
	 $no++;} ?>
				  </tbody>
	<tfoot>
	<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    </tr>
	</tfoot>
        </table>
        </div>
              <!-- /.card-body -->
            </div>		  
</div>		  
		  <!-- /.container-fluid -->
</div> <!-- /.content -->
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