<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id	= $_GET['id'];
	$pos1		= strpos($modal_id,"-");
	$proj		= substr($modal_id,0,$pos1);
	$modal_id1	= substr($modal_id,$pos1+1,300);
	$pos2		= strpos($modal_id1,"-");
	$hanger		= substr($modal_id1,0,$pos2);
	$demand		= substr($modal_id1,$pos2+1,10);
	
$sqlAL=mysqli_query($con," select SUM(berat) as tot  from tbl_pembagian_greige_now where no_po ='$proj' ");
$rAL=mysqli_fetch_array($sqlAL);
	
?>
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Allokasi</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
<div class="row">				
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
SELECT 
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
PRODUCTIONRESERVATION.ORDERCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION FROM DB2ADMIN.ITXVIEWHEADERKNTORDER 
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONRESERVATION ON PRODUCTIONRESERVATION.PRODUCTIONORDERCODE = ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
PRODUCTIONRESERVATION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (PRODUCTIONRESERVATION.ITEMTYPEAFICODE='GYR' OR PRODUCTIONRESERVATION.ITEMTYPEAFICODE='DYR') AND
CONCAT(TRIM(PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE02),TRIM(PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE03))='$hanger' AND
PRODUCTIONRESERVATION.ORDERCODE='$demand' AND 
(ITXVIEWHEADERKNTORDER.PROJECTCODE='$proj' OR ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE='$proj')
GROUP BY 
PRODUCTIONRESERVATION.ITEMTYPEAFICODE,
PRODUCTIONRESERVATION.SUBCODE01,
PRODUCTIONRESERVATION.SUBCODE02,
PRODUCTIONRESERVATION.SUBCODE03,
PRODUCTIONRESERVATION.SUBCODE04,
PRODUCTIONRESERVATION.SUBCODE05,
PRODUCTIONRESERVATION.SUBCODE06,
PRODUCTIONRESERVATION.SUBCODE07,
PRODUCTIONRESERVATION.SUBCODE08,
PRODUCTIONRESERVATION.ORDERCODE,
PRODUCTIONRESERVATION.RESERVATIONLINE,
CONCAT(TRIM(PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE02),TRIM(PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE03)),
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
ORDER BY PRODUCTIONRESERVATION.RESERVATIONLINE ASC

	   ";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
					  
$no=1; 
$c=0;
 while ($rowdb22 = db2_fetch_assoc($stmt2)) {	
	 $kdbenang=trim($rowdb22['SUBCODE01'])." ".trim($rowdb22['SUBCODE02']).trim($rowdb22['SUBCODE03'])." ".trim($rowdb22['SUBCODE04'])." ".trim($rowdb22['SUBCODE05'])." ".trim($rowdb22['SUBCODE06'])." ".trim($rowdb22['SUBCODE07'])." ".trim($rowdb22['SUBCODE08']);
	 $Sdb23="
	SELECT x.* FROM DB2ADMIN.PRODUCTIONRESERVATIONCOMMENT x
WHERE PRODUCTIONRESERVATIONORDERCODE ='$rowdb22[ORDERCODE]' AND PRORESERVATIONRESERVATIONLINE ='$rowdb22[RESERVATIONLINE]'
	";
	$st13   = db2_exec($conn1,$Sdb23, array('cursor'=>DB2_SCROLLABLE));
	$rdb23 = db2_fetch_assoc($st13);
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $rowdb22['RESERVATIONLINE']; ?></td>
      <td><?php echo $rowdb22['SUMMARIZEDDESCRIPTION']; ?></td>
      <td><?php echo $rdb23['COMMENTTEXT']; ?></td>
      </tr>				  
	<?php 
	 $no++;} ?>
				  </tbody>
                  
                </table>
          </div>
              <!-- /.card-body -->
        </div>  
</div>
<div class="col-md-6">		  
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
$sqlDB21 =" SELECT a.SUBCODE02,a.SUBCODE03,SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN,CURRENT_TIMESTAMP AS TGLS  FROM ITXVIEWHEADERKNTORDER a
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID =a2.UNIQUEID AND a2.NAMENAME ='StatusRMP'
LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID =a3.UNIQUEID AND a3.NAMENAME ='QtySalin'
WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$proj' OR a.ORIGDLVSALORDLINESALORDERCODE='$proj') AND
CONCAT(TRIM(a.SUBCODE02),TRIM(a.SUBCODE03))='$hanger' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6') AND (NOT a2.VALUESTRING ='3' OR a2.VALUESTRING IS NULL)
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
WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$proj' OR a.ORIGDLVSALORDLINESALORDERCODE='$proj') AND
CONCAT(TRIM(a.SUBCODE02),TRIM(a.SUBCODE03))='$hanger' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6')";	
		  $stmtR   = db2_exec($conn1,$sqlDB2R, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb2R = db2_fetch_assoc($stmtR);
		  $kRajut  = round($rAL['tot'],2)-round($rowdb2R['JQTY'],2);
					  
$sqlDB23 ="SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT 
FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF' AND CONCAT(TRIM(PRODUCT.SUBCODE02),TRIM(PRODUCT.SUBCODE03))='$hanger'  AND PRODUCT.COMPANYCODE='100' 
ORDER BY ADSTORAGE.FIELDNAME";	
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);
$sqlDB24 ="SELECT COUNT(*) AS JMLMSN FROM (
SELECT ADSTORAGE.VALUESTRING  FROM PRODUCTIONDEMAND 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
WHERE ( PRODUCTIONDEMAND.PROJECTCODE = '$proj' OR PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE = '$proj')
GROUP BY ADSTORAGE.VALUESTRING) MSN";	
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);					  
$sqlDB28 =" SELECT INSPECTIONSTARTDATETIME  FROM  
ELEMENTSINSPECTION 
LEFT OUTER JOIN ITXVIEWHEADERKNTORDER  ON ITXVIEWHEADERKNTORDER.PRODUCTIONDEMANDCODE=ELEMENTSINSPECTION.DEMANDCODE  
WHERE ( ITXVIEWHEADERKNTORDER.PROJECTCODE = '$proj' OR ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE = '$proj') AND 
ELEMENTSINSPECTION.ELEMENTITEMTYPECODE='KGF' AND ELEMENTSINSPECTION.COMPANYCODE='100' 
ORDER BY ELEMENTSINSPECTION.INSPECTIONSTARTDATETIME ASC LIMIT 1 ";	
$stmt8   = db2_exec($conn1,$sqlDB28, array('cursor'=>DB2_SCROLLABLE));
$rowdb28 = db2_fetch_assoc($stmt8);
if($proj!="" and $hanger!="" and $rowdb23['STDRAJUT']!=""){					  
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
	<i>Project Code: <b><?php echo $proj;?></b> Hanger No: <b><?php echo $hanger;?></b> Demand No: <b><?php echo $demand; ?></b></i>	
			<table id="lookup1" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">NO BON</div></th>
								<th><div align="center">NO ARTIKEL</div></th>
								<th><div align="center">KGS</div></th>
								<th><div align="center">KONSUMEN</div></th>
								<th><div align="center">KET</div></th>								
								<th><div align="center">DELIVERY SELESAI</div></th>	
								<th><div align="center">DELIVERY KAIN JADI</div></th>
							</tr>
						</thead>
						<tbody>
    <?php
	$no=1;
	$sqlKBG=mysqli_query($con," select * from tbl_pembagian_greige_now where no_po ='$proj' ");
	while($rKBG=mysqli_fetch_array($sqlKBG)){
		
								
	echo"<tr>
  	<td align=center>$no</td>
	<td align=center>$rKBG[no_bon]</td>
	<td align=left>$rKBG[no_artikel]</td>
	<td align=right>".number_format(round($rKBG['berat'],2),2)."</td>	
	<td align=left>$rKBG[konsumen]</td>
	<td align=left>$rKBG[ket]</td>
	<td align=center>$rKBG[tgl_delivery_selesai]</td>
	<td align=center>$rKBG[tgl_delivery_kain_jadi]</td>	
	</tr>";
	$no++;	
	$TKG+=round($rKBG['berat'],2);			
							}
								

     
  ?>
						</tbody>
<tfoot>
			<tr>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td align="right"><strong>Total</strong></td>
			  <td align="right"><?php echo number_format($TKG,2); ?></td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>															
			</tr>
</tfoot>				
					</table>   	
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              			  	
            </div>
			</form>	
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
               
<script>
  $(function () {	 
	$('.select2sts').select2({
    placeholder: "Select a status",
    allowClear: true
});   
  });
</script>
