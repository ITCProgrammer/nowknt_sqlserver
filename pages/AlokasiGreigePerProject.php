<?php
$Project	= isset($_POST['projectcode']) ? $_POST['projectcode'] : '';
$HangerNO	= isset($_POST['hangerno']) ? $_POST['hangerno'] : '';
$subC1		= substr($HangerNO,0,3);
$subC2		= substr($HangerNO,3,5);
if(strlen(trim($HangerNO))=="13"){
$subC2		= substr($HangerNO,3,6);
$subC3		= substr($HangerNO,10,3);	
}
else if(strlen(trim($subC2))=="4"){
$subC3		= substr($HangerNO,8,3);	
}else if(strlen(trim($subC2))=="5"){
$subC3		= substr($HangerNO,9,3); 	
}


$sqlDB2 =" SELECT SUBCODE02,SUBCODE03,SUBCODE04, SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWHEADERKNTORDER 
WHERE ITEMTYPEAFICODE ='KGF' AND (PROJECTCODE ='$Project' OR ORIGDLVSALORDLINESALORDERCODE='$Project') AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6')
GROUP BY SUBCODE02,SUBCODE03,SUBCODE04,CURRENT_TIMESTAMP  ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));

$sqlN=mysqli_query($con,"SELECT note FROM tbl_opn_rmp_now WHERE no_po='$Project' and no_artikel='$HangerNO' ORDER BY id DESC");
$rN=mysqli_fetch_array($sqlN)
?>
<!-- Main content -->
      <div class="container-fluid">
	<div class="row">
	<div class="col-md-2">	  
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
               <label for="projectcode" class="col-md-5">Project</label>
               <div class="col-md-7"> 
                    <input name="projectcode" value="<?php echo $Project;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
			   </div>	
            </div>
				 <div class="form-group row">
                    <label for="hangerno" class="col-md-5">No. Hanger</label>
					<div class="col-md-7"> 
                    <select name="hangerno" class="form-control form-control-sm"  autocomplete="off">
						<option value="">Pilih</option>
						<?php while($rowdb2 = db2_fetch_assoc($stmt)){?>
						<option value="<?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?>" <?php if($HangerNO==trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04'])){ echo "SELECTED";}?>><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?></option>
						<?php } ?>
					</select>	
                  </div>	
                  </div>
			  	  <div class="form-group row">
                    <label for="note" class="col-md-5">Note</label>
					<div class="col-md-7"> 
                   	<textarea name="note" class="form-control form-control-sm" readonly><?php $rN['note']; ?></textarea>
                  </div>	
                  </div>
			  
          </div>		  
		  <!-- /.card-body --> 
		  <div class="card-footer">
			<button class="btn btn-primary" type="submit">Cari Data</button>
		  </div>	
        </div>  
		</form>	
	</div>      
	<?php
		  $sqlDB21 =" SELECT a.SUBCODE02,a.SUBCODE03,SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN,CURRENT_TIMESTAMP AS TGLS  FROM ITXVIEWHEADERKNTORDER a
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID =a2.UNIQUEID AND a2.NAMENAME ='StatusRMP'
LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID =a3.UNIQUEID AND a3.NAMENAME ='QtySalin'
WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$Project' OR a.ORIGDLVSALORDLINESALORDERCODE='$Project') AND
a.SUBCODE02='$subC1' AND a.SUBCODE03='$subC2' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6') AND (NOT a2.VALUESTRING ='3' OR a2.VALUESTRING IS NULL)
GROUP BY a.SUBCODE02,a.SUBCODE03,CURRENT_TIMESTAMP
		";	
		  $stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb21 = db2_fetch_assoc($stmt1);
		  
		  $sqlDB2RX =" SELECT
        PROJECTCODE,
        DECOSUBCODE01,
        DECOSUBCODE02,
        DECOSUBCODE03,
        DECOSUBCODE04,
        SUM(USERPRIMARYQUANTITY) AS JQTY
    FROM
        STOCKTRANSACTION s
    WHERE
        LOGICALWAREHOUSECODE = 'M502'
        AND TEMPLATECODE = '110'
        AND PROJECTCODE= '$Project' 
		AND DECOSUBCODE02= '$subC1'
        AND DECOSUBCODE03= '$subC2'
        AND DECOSUBCODE04= '$subC3'
    GROUP BY
        PROJECTCODE,
        DECOSUBCODE01,
        DECOSUBCODE02,
        DECOSUBCODE03,
        DECOSUBCODE04 ";	
		  $stmtRX   = db2_exec($conn1,$sqlDB2RX, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb2RX = db2_fetch_assoc($stmtRX);
		  $sqlDB2R =" SELECT SUM(INSP.JQTY) AS JQTY  FROM ITXVIEWHEADERKNTORDER a
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
		  $kRajutX  = round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2)-round($rowdb2RX['JQTY'],2);
			
		  ?>
<div class="col-md-6">		  
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"> Benang</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">			  	  
	<table id="" class="table table-sm table-bordered table-striped" style="font-size:13px;">
        <thead>
                  <tr>
                    <th style="text-align: center; vertical-align: middle">Res. Line</th>
                    <th style="text-align: center; vertical-align: middle">Jenis Benang</th>
                    <th style="text-align: center; vertical-align: middle">Supplier</th>
          </tr>
        </thead>
                  <tbody>
				  <?php
$sqlDB22 =" 
SELECT ITXVIEWHEADERKNTORDER.PROJECTCODE,
ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE,
PRODUCTIONRESERVATION.RESERVATIONLINE,
PRODUCTIONRESERVATION.ITEMTYPEAFICODE,
PRODUCTIONRESERVATION.SUBCODE01,
PRODUCTIONRESERVATION.SUBCODE02,
PRODUCTIONRESERVATION.SUBCODE03,
PRODUCTIONRESERVATION.SUBCODE04,
PRODUCTIONRESERVATION.SUBCODE05,
PRODUCTIONRESERVATION.SUBCODE06,
PRODUCTIONRESERVATION.SUBCODE07,
PRODUCTIONRESERVATION.SUBCODE08,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION FROM DB2ADMIN.ITXVIEWHEADERKNTORDER 
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONRESERVATION ON PRODUCTIONRESERVATION.PRODUCTIONORDERCODE = ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
PRODUCTIONRESERVATION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (PRODUCTIONRESERVATION.ITEMTYPEAFICODE='GYR' OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE='DYR') AND
(ITXVIEWHEADERKNTORDER.PROJECTCODE='$Project' OR ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE='$Project')
GROUP BY ITXVIEWHEADERKNTORDER.PROJECTCODE,
ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE,
PRODUCTIONRESERVATION.ITEMTYPEAFICODE,
PRODUCTIONRESERVATION.SUBCODE01,
PRODUCTIONRESERVATION.SUBCODE02,
PRODUCTIONRESERVATION.SUBCODE03,
PRODUCTIONRESERVATION.SUBCODE04,
PRODUCTIONRESERVATION.SUBCODE05,
PRODUCTIONRESERVATION.SUBCODE06,
PRODUCTIONRESERVATION.SUBCODE07,
PRODUCTIONRESERVATION.SUBCODE08,
PRODUCTIONRESERVATION.RESERVATIONLINE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
ORDER BY PRODUCTIONRESERVATION.RESERVATIONLINE ASC

	   ";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
					  
$no=1; 
$c=0;
 while ($rowdb22 = db2_fetch_assoc($stmt2)) {	
	 $kdbenang=trim($rowdb22['SUBCODE01'])." ".trim($rowdb22['SUBCODE02']).trim($rowdb22['SUBCODE03'])." ".trim($rowdb22['SUBCODE04'])." ".trim($rowdb22['SUBCODE05'])." ".trim($rowdb22['SUBCODE06'])." ".trim($rowdb22['SUBCODE07'])." ".trim($rowdb22['SUBCODE08']);	  
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $rowdb22['RESERVATIONLINE']; ?></td>
      <td><?php echo $rowdb22['SUMMARIZEDDESCRIPTION']; ?></td>
      <td>&nbsp;</td>
      </tr>				  
	<?php 
	 $no++;} ?>
				  </tbody>
                  
                </table>
          </div>
              <!-- /.card-body -->
        </div>  
</div>	
<div class="col-md-4">		  
		<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title"> Estimasi</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">			  	  
	<table id="" class="table table-sm table-bordered table-striped" style="font-size:13px;">
        <thead>
                  <tr>
                    <th style="text-align: center; vertical-align: middle">Order</th>
                    <th style="text-align: center; vertical-align: middle">Hasil Rajut</th>
                    <th style="text-align: center; vertical-align: middle">Kurang Rajut</th>
                    <th style="text-align: center; vertical-align: middle">Tgl Mulai</th>
                    <th style="text-align: center; vertical-align: middle">Estimasi Selesai</th>
          </tr>
        </thead>
                  <tbody>
<?php
$sqlDB23 ="SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT 
FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF' AND PRODUCT.SUBCODE02='$subC1' AND 
PRODUCT.SUBCODE03='$subC2' AND PRODUCT.COMPANYCODE='100' 
ORDER BY ADSTORAGE.FIELDNAME";	
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);
$sqlDB24 ="SELECT COUNT(*) AS JMLMSN FROM (
SELECT ADSTORAGE.VALUESTRING  FROM PRODUCTIONDEMAND 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
WHERE ( PRODUCTIONDEMAND.PROJECTCODE = '$Project' OR PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = '$Project')
GROUP BY ADSTORAGE.VALUESTRING) MSN";	
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);					  
$sqlDB28 =" SELECT INSPECTIONSTARTDATETIME  FROM  
ELEMENTSINSPECTION 
LEFT OUTER JOIN ITXVIEWHEADERKNTORDER  ON ITXVIEWHEADERKNTORDER.PRODUCTIONDEMANDCODE=ELEMENTSINSPECTION.DEMANDCODE  
WHERE ( ITXVIEWHEADERKNTORDER.PROJECTCODE = '$Project' OR ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE = '$Project') AND 
ELEMENTSINSPECTION.ELEMENTITEMTYPECODE='KGF' AND ELEMENTSINSPECTION.COMPANYCODE='100' 
ORDER BY ELEMENTSINSPECTION.INSPECTIONSTARTDATETIME ASC LIMIT 1 ";	
$stmt8   = db2_exec($conn1,$sqlDB28, array('cursor'=>DB2_SCROLLABLE));
$rowdb28 = db2_fetch_assoc($stmt8);
if($Project!="" and $HangerNO!=""){					  
$kHari=round($kRajut/round($rowdb23['STDRAJUT']*$rowdb24['JMLMSN'],0));
$tglEst=date('Y-m-d', strtotime($kHari." days", strtotime($rowdb21['TGLS'])));
	
$kHariX=round($kRajutX/round($rowdb23['STDRAJUT']*$rowdb24['JMLMSN'],0));	
$tglEstX=date('Y-m-d', strtotime($kHariX." days", strtotime($rowdb21['TGLS'])));	
}
					  ?>
	<tr>
	  <td align="center"><?php echo number_format(round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2),2)." Kgs";?></td>
	  <td align="center"><?php echo number_format(round($rowdb2R['JQTY'],2),2)." Kgs"; ?></td> <!-- number_format(round($rowdb2R['JQTYX'],2),2) -->
	  <td align="center"><?php echo number_format(round($kRajut,2),2)." Kgs"; ?></td>  <!-- number_format(round($kRajutX,2),2) -->
	    <td align="center"><?php echo substr($rowdb28['INSPECTIONSTARTDATETIME'],0,10); ?></td>
	    <td align="center"><?php if($tglEst=="1970-01-01"){}else{echo $tglEst;} ?></td> <!-- if($tglEstX=="1970-01-01"){}else{echo $tglEstX;} -->
	    </tr>				  
				  </tbody>
                  
                </table>
          </div>
              <!-- /.card-body -->
        </div>  
</div>		  
      </div>
<div class="row">
<div class="col-md-12">		  
<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Alokasi Order</h3>
              </div>
              <!-- /.card-header -->
      <div class="card-body">
<table id="example12" class="table table-sm table-bordered table-striped" style="font-size:13px;" width="100%">
        <thead>
                  <tr>
                    <th height="38" style="text-align: center; vertical-align: middle">No</th>
                    <th style="text-align: center; vertical-align: middle">Delivery Mulai</th>
                    <th style="text-align: center; vertical-align: middle">Delivery Selesai</th>
                    <th style="text-align: center; vertical-align: middle">Delivery Kain Jadi</th>
                    <th style="text-align: center; vertical-align: middle">Order</th>
                    <th style="text-align: center; vertical-align: middle">Konsumen</th>
                    <th style="text-align: center; vertical-align: middle">Qty per Order</th>
                    <th style="text-align: center; vertical-align: middle">Note</th>
              </tr>
        </thead>
                  <tbody>
				  <?php
$no=1;					  
$sql=mysqli_query($con,"SELECT * FROM tbl_pembagian_greige_now WHERE no_po='$Project' ORDER BY id DESC");
  while($r=mysqli_fetch_array($sql)){
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $no; ?></td>
      <td style="text-align: center"><?php echo date("d F Y", strtotime($r['tgl_delivery_mulai']));?></td>
      <td style="text-align: center"><?php echo date("d F Y", strtotime($r['tgl_delivery_selesai']));?></td>
      <td style="text-align: center"><?php echo date("d F Y", strtotime($r['tgl_delivery_kain_jadi']));?></td>
      <td style="text-align: center"><?php echo $r['no_bon'];?></td>
      <td style="text-align: left"><?php echo $r['konsumen'];?></td>
      <td style="text-align: right"><?php echo number_format($r['berat'],2);?></td>
      <td style="text-align: left"><?php echo $r['ket'];?></td>
      </tr>
	  <?php
	  $tOrder+=$r['berat'];
	 $no++;} ?>
				  </tbody>
	<tfoot>
	<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tOrder,2); ?></strong></td>
	    <td style="text-align: right">&nbsp;</td>
	    </tr>
	</tfoot>
        </table>
        </div>
              <!-- /.card-body -->
      </div>		  
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