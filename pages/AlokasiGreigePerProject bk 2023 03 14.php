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
WHERE ITXVIEWHEADERKNTORDER.PROJECTCODE='$Project' OR ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE='$Project'
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
}
					  ?>
	<tr>
	  <td align="center"><?php echo number_format(round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2),2)." Kgs";?></td>
	  <td align="center"><?php echo number_format(round($rowdb2R['JQTY'],2),2)." Kgs"; ?></td>
	  <td align="center"><?php echo number_format(round($kRajut,2),2)." Kgs"; ?></td>
	    <td align="center"><?php echo substr($rowdb28['INSPECTIONSTARTDATETIME'],0,10); ?></td>
	    <td align="center"><?php if($tglEst=="1970-01-01"){}else{echo $tglEst;}?></td>
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
                    <th style="text-align: center; vertical-align: middle">Delivery Kain Jadi</th>
                    <th style="text-align: center; vertical-align: middle">Demand Induk</th>
                    <th style="text-align: center; vertical-align: middle">Order</th>
                    <th style="text-align: center; vertical-align: middle">Konsumen</th>
                    <th style="text-align: center; vertical-align: middle">Note</th>
                    <th style="text-align: center; vertical-align: middle">Warna</th>
                    <th style="text-align: center; vertical-align: middle">Qty per Order</th>
                    <th style="text-align: center; vertical-align: middle">Qty Bagi</th>
                    <th style="text-align: center; vertical-align: middle">UsetID</th>
              </tr>
        </thead>
                  <tbody>
				  <?php
$sqlDB ="
SELECT 
DISTINCT p.CODE, p.ORIGDLVSALORDLINESALORDERCODE, i.LEGALNAME1,s.DELIVERYDATE, p.EXTERNALREFERENCE, p.EXTERNALREFERENCEDATE, 
p.CREATIONUSER, p.SUBCODE02, p.SUBCODE03 ,p.SUBCODE04,  
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
END AS qty5,
u.LONGDESCRIPTION,
p2.USEDBASEPRIMARYQUANTITY,
f.VALUESTRING AS NOTEDEFECT
FROM PRODUCTIONDEMAND p 
LEFT OUTER JOIN USERGENERICGROUP u ON p.SUBCODE05=u.CODE 
LEFT OUTER JOIN PRODUCTIONRESERVATION p2 ON p2.ORDERCODE = p.CODE AND p2.ITEMTYPEAFICODE ='KGF'
LEFT OUTER JOIN ITXVIEWHEADERKNTORDER i ON i.CODE = p.CODE 
LEFT OUTER JOIN 
(
SELECT s.SALESORDERLINESALESORDERCODE, SUBSTR(LISTAGG(s.DELIVERYDATE, ', ') WITHIN GROUP (ORDER BY s.DELIVERYDATE DESC),1,10)  AS DELIVERYDATE
FROM (SELECT DELIVERYDATE,SALESORDERLINESALESORDERCODE  
FROM SALESORDERDELIVERY 
GROUP BY SALESORDERLINESALESORDERCODE, DELIVERYDATE) s
GROUP BY s.SALESORDERLINESALESORDERCODE )
 s ON (p.PROJECTCODE = s.SALESORDERLINESALESORDERCODE OR p.ORIGDLVSALORDLINESALORDERCODE = s.SALESORDERLINESALESORDERCODE)
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
LEFT OUTER JOIN ADSTORAGE c ON p.ABSUNIQUEID = c.UNIQUEID AND c.NAMENAME ='SplittedFrom'
LEFT OUTER JOIN ADSTORAGE d ON p.ABSUNIQUEID = d.UNIQUEID AND d.NAMENAME ='DefectType'
LEFT OUTER JOIN ADSTORAGE e ON p.ABSUNIQUEID = e.UNIQUEID AND e.NAMENAME ='OriginalPDCode'
LEFT OUTER JOIN ADSTORAGE f ON p.ABSUNIQUEID = f.UNIQUEID AND f.NAMENAME ='DefectNote'
WHERE (a.VALUESTRING='$Project' OR a2.VALUESTRING='$Project' OR a3.VALUESTRING='$Project' OR a4.VALUESTRING='$Project' OR a5.VALUESTRING='$Project')
AND p.ITEMTYPEAFICODE='KFF' AND p.ENTRYWAREHOUSECODE='M504' AND c.VALUESTRING IS NULL
AND ((d.VALUESTRING IS NULL AND e.VALUESTRING IS NULL) OR d.VALUESTRING='058') AND NOT p.ORIGDLVSALORDLINESALORDERCODE IS NULL
AND p.SUBCODE02 = '$subC1' AND  p.SUBCODE03='$subC2' AND p.SUBCODE04='$subC3' 
ORDER BY s.DELIVERYDATE ASC
	   ";	
$stm   = db2_exec($conn1,$sqlDB, array('cursor'=>DB2_SCROLLABLE));
					  
$no=1; 
 while ($rowdb = db2_fetch_assoc($stm)) {	
	 
if($rowdb['ORIGDLVSALORDLINESALORDERCODE']!=""){$order=$rowdb['ORIGDLVSALORDLINESALORDERCODE'];}else{ $order=$rowdb['EXTERNALREFERENCE'];}	 
	 $sqlDB1 = " SELECT ip.LANGGANAN,ip.BUYER  FROM SALESORDER s 
LEFT OUTER JOIN ITXVIEW_PELANGGAN ip ON s.PROJECTCODE =ip.CODE AND s.ORDPRNCUSTOMERSUPPLIERCODE=ip.ORDPRNCUSTOMERSUPPLIERCODE  
WHERE PROJECTCODE ='$order' ";
$stm1  = db2_exec($conn1,$sqlDB1, array('cursor'=>DB2_SCROLLABLE));	 
$rowdb1 = db2_fetch_assoc($stm1);	 
	 $qtyprOrder=$rowdb['QTY1']+$rowdb['QTY2']+$rowdb['QTY3']+$rowdb['QTY4']+$rowdb['QTY5'];
	 $tOrder+=$qtyprOrder;
	 	$awalDY  = strtotime($rowdb21['TGLS']);
		$akhirDY = strtotime($rowdb['DELIVERYDATE']);
		$diffDY  = ($akhirDY - $awalDY);
		$tjamDY  =round($diffDY/(60 * 60),2);
		$hariDY  =round($tjamDY/24,2);
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $no; ?></td>
      <td style="text-align: center"><?php if($rowdb['DELIVERYDATE']!=""){echo date("d F Y", strtotime($rowdb['DELIVERYDATE']));}else{ echo date("d F Y", strtotime($rowdb['EXTERNALREFERENCEDATE']));}?></td>
      <td style="text-align: center"><?php echo $rowdb['CODE'];?></td>
      <td style="text-align: center"><?php if($rowdb['ORIGDLVSALORDLINESALORDERCODE']!=""){echo $rowdb['ORIGDLVSALORDLINESALORDERCODE'];}else{ echo $rowdb['EXTERNALREFERENCE'];}?></td>
      <td style="text-align: left"><?php if($rowdb1['LANGGANAN']!=""){echo $rowdb1['LANGGANAN'];}else{ echo $rowdb['LEGALNAME1'];}?></td>
      <td style="text-align: left"><?php echo $rowdb['NOTEDEFECT'];?></td>
      <td style="text-align: center"><?php echo $rowdb['LONGDESCRIPTION'];?></td>
      <td style="text-align: right"><?php echo number_format($qtyprOrder,2);?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb['USEDBASEPRIMARYQUANTITY'],2),2); ?></td>
      <td style="text-align: center"><?php echo $rowdb['CREATIONUSER'];?></td>
      </tr>
	  <?php 
	 $tqtyK+=$qtyK;
	 $no++;} ?>
				  </tbody>
	<tfoot>
	<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tOrder,2); ?></strong></td>
	    <td style="text-align: right">&nbsp;</td>
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