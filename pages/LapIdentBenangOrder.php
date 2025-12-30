<?php
$ProdOrder = isset($_POST['prod_order']) ? $_POST['prod_order'] : '';
$KdBng = isset($_POST['kdbg']) ? $_POST['kdbg'] : '';	    	 
	
$sqlDB22PRO =" SELECT ITXVIEWKK.ORIGDLVSALORDLINESALORDERCODE FROM 
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
	pd.SUBCODE04,pd.SUBCODE05,pd.SUBCODE06,pd.SUBCODE07,pd.SUBCODE08,pd.ORIGDLVSALORDLINESALORDERCODE,ugp.LONGDESCRIPTION) ITXVIEWKK ON PRODUCTIONORDER.CODE=ITXVIEWKK.PRODUCTIONORDERCODE
 WHERE ITXVIEWKK.PRODUCTIONORDERCODE='$ProdOrder' ";	
$stmt2PRO   = db2_exec($conn1,$sqlDB22PRO, array('cursor'=>DB2_SCROLLABLE));
$rowdb22PRO = db2_fetch_assoc($stmt2PRO);
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">
		<div class="row">
          <div class="col-md-3">	
		<div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Filter Identifikasi Benang Per Order</h3>

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
               <label for="prod_order" class="col-md-4">Prod. Order</label>
               <div class="col-md-7"> 
                    <input name="prod_order" value="<?php echo $ProdOrder;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
			   </div>	
              </div>
			  <div class="form-group row">
               <label for="project" class="col-md-4">Project</label>
               <div class="col-md-7"> 
                    <input name="project" value="<?php echo $rowdb22PRO['ORIGDLVSALORDLINESALORDERCODE'];?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" readonly>
			   </div>	
              </div>
          </div>
		  <div class="card-footer">
			  <button class="btn btn-info" type="submit" >Cari Data</button>
		  </div>	  
		  <!-- /.card-body -->          
        </div>  
		</div>
		<div class="col-md-9">
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Kebutuhan Benang</h3>
              </div>
              <!-- /.card-header -->
          <div class="card-body"> 
			  
		  	  
<table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
        <thead>
                  <tr>
                    <th style="text-align: center; vertical-align: middle">No</th>
                    <th style="text-align: center; vertical-align: middle">Kode Benang</th>
                    <th style="text-align: center; vertical-align: middle">Group Line</th>
                    <th style="text-align: center; vertical-align: middle">Demand</th>
                    <th style="text-align: center; vertical-align: middle">Jenis Benang</th>
                    <th style="text-align: center; vertical-align: middle">%</th>
                    <th style="text-align: center; vertical-align: middle">Reservation</th>
                    <th style="text-align: center; vertical-align: middle">Qty Butuh</th>
                    <th style="text-align: center; vertical-align: middle">Qty Pakai</th>
                    <th style="text-align: center; vertical-align: middle">Whs</th>
                    <th style="text-align: center; vertical-align: middle">Issue Date</th>
                    <th style="text-align: center; vertical-align: middle">Loss</th>
                    <th style="text-align: center; vertical-align: middle">Loss %</th>
                </tr>
        </thead>
                  <tbody>
				  <?php					  
$sqlDB22 =" SELECT PRODUCTIONRESERVATION.*,FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION FROM DB2ADMIN.PRODUCTIONRESERVATION 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
PRODUCTIONRESERVATION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE PRODUCTIONORDERCODE ='$ProdOrder'
ORDER BY GROUPLINE ASC
	   ";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
					  
$no=1; 
$c=0;
$losskg1="";					  
 while ($rowdb22 = db2_fetch_assoc($stmt2)) {	
	 $kdbenang=trim($rowdb22['SUBCODE01'])." ".trim($rowdb22['SUBCODE02']).trim($rowdb22['SUBCODE03'])." ".trim($rowdb22['SUBCODE04'])." ".trim($rowdb22['SUBCODE05'])." ".trim($rowdb22['SUBCODE06'])." ".trim($rowdb22['SUBCODE07'])." ".trim($rowdb22['SUBCODE08']);
	 $persen=number_format($rowdb22['QUANTITYPER']*100,2);
	 $kbhBenang=round((round($rowdb21['BASEPRIMARYQUANTITY'],2)*($persen+1))/100,2);
	 $Sdb201="
	SELECT sum(BASEPRIMARYQUANTITYUNIT) AS TOTALSTK

FROM BALANCE WHERE ITEMTYPECODE ='GYR' AND LOGICALWAREHOUSECODE ='M011' AND
DECOSUBCODE01='$rowdb22[SUBCODE01]' AND
DECOSUBCODE02='$rowdb22[SUBCODE02]' AND
DECOSUBCODE03='$rowdb22[SUBCODE03]' AND
DECOSUBCODE04='$rowdb22[SUBCODE04]' AND
DECOSUBCODE05='$rowdb22[SUBCODE05]' AND
DECOSUBCODE06='$rowdb22[SUBCODE06]' AND
DECOSUBCODE07='$rowdb22[SUBCODE07]' AND
DECOSUBCODE08='$rowdb22[SUBCODE08]'

	";
	$st101   = db2_exec($conn1,$Sdb201, array('cursor'=>DB2_SCROLLABLE));
	$rdb201 = db2_fetch_assoc($st101);	 
$Sdb211="
	SELECT sum(BASEPRIMARYQUANTITYUNIT) AS TOTALSTK

FROM BALANCE WHERE ITEMTYPECODE ='GYR' AND LOGICALWAREHOUSECODE ='M904' AND
DECOSUBCODE01='$rowdb22[SUBCODE01]' AND
DECOSUBCODE02='$rowdb22[SUBCODE02]' AND
DECOSUBCODE03='$rowdb22[SUBCODE03]' AND
DECOSUBCODE04='$rowdb22[SUBCODE04]' AND
DECOSUBCODE05='$rowdb22[SUBCODE05]' AND
DECOSUBCODE06='$rowdb22[SUBCODE06]' AND
DECOSUBCODE07='$rowdb22[SUBCODE07]' AND
DECOSUBCODE08='$rowdb22[SUBCODE08]'
	";
	$st111   = db2_exec($conn1,$Sdb211, array('cursor'=>DB2_SCROLLABLE));
	$rdb211 = db2_fetch_assoc($st111);	 
$Sdb221="
	SELECT sum(BASEPRIMARYQUANTITYUNIT) AS TOTALSTK

FROM BALANCE WHERE ITEMTYPECODE ='GYR' AND LOGICALWAREHOUSECODE ='P501' AND
DECOSUBCODE01='$rowdb22[SUBCODE01]' AND
DECOSUBCODE02='$rowdb22[SUBCODE02]' AND
DECOSUBCODE03='$rowdb22[SUBCODE03]' AND
DECOSUBCODE04='$rowdb22[SUBCODE04]' AND
DECOSUBCODE05='$rowdb22[SUBCODE05]' AND
DECOSUBCODE06='$rowdb22[SUBCODE06]' AND
DECOSUBCODE07='$rowdb22[SUBCODE07]' AND
DECOSUBCODE08='$rowdb22[SUBCODE08]'
	";
	$st121   = db2_exec($conn1,$Sdb221, array('cursor'=>DB2_SCROLLABLE));
	$rdb221 = db2_fetch_assoc($st121);

$sqlDB21L = " SELECT sum(s.BASEPRIMARYQUANTITY) AS KGPAKAI FROM STOCKTRANSACTION s WHERE s.TEMPLATECODE='120' AND (s.ITEMTYPECODE='GYR' OR s.ITEMTYPECODE='DYR') AND s.ORDERCODE='$ProdOrder' ";
	 $stmt1L   = db2_exec($conn1,$sqlDB21L, array('cursor'=>DB2_SCROLLABLE));
	 $rowdb21L = db2_fetch_assoc($stmt1L);	 
	 
$sqlDB22L = " SELECT 
	STOCKTRANSACTION.ORDERCODE,	
	COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_DUS,
	SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS KGSISA,
	SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
	(
SELECT LISTAGG(DISTINCT  TRIM(ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE),', ') AS PRODUCTIONDEMANDCODE,PRODUCTIONORDERCODE
FROM DB2ADMIN.ITXVIEWKNTORDER 
GROUP BY PRODUCTIONORDERCODE
) ITXVIEWKNTORDER ON ITXVIEWKNTORDER.PRODUCTIONORDERCODE =STOCKTRANSACTION.ORDERCODE 
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
	LEFT OUTER JOIN DB2ADMIN.INITIALS INITIALS ON 
	INITIALS.CODE =STOCKTRANSACTION.CREATIONUSER
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
	STOCKTRANSACTION.RETURNTRANSACTION ='1' AND STOCKTRANSACTION.ORDERCODE='$ProdOrder' AND NOT STOCKTRANSACTION.ORDERCODE IS NULL
	GROUP BY STOCKTRANSACTION.ORDERCODE ";
	 $stmt2L   = db2_exec($conn1,$sqlDB22L, array('cursor'=>DB2_SCROLLABLE));
	 $rowdb22L = db2_fetch_assoc($stmt2L);
	 
$sqlDB2IN =" SELECT i.PRODUCTIONDEMANDCODE,i.PRODUCTIONORDERCODE,count(e.WEIGHTREALNET) AS INSROL,sum(e.WEIGHTREALNET) AS INSKG,
i.LEGALNAME1,i.SUBCODE02,i.SUBCODE03,i.SUBCODE04,i.PROJECTCODE,VARCHAR_FORMAT(i.FINALEFFECTIVEDATE,'YYYY-MM-DD') AS TGLSELESAI,
ad.VALUESTRING AS NO_MESIN
FROM ITXVIEWKNTORDER i 
LEFT OUTER JOIN STOCKTRANSACTION s ON s.PRODUCTIONORDERCODE =i.PRODUCTIONORDERCODE
LEFT OUTER JOIN ELEMENTSINSPECTION e ON e.DEMANDCODE =s.ORDERCODE AND e.ELEMENTCODE =s.ITEMELEMENTCODE
LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE = i.PRODUCTIONDEMANDCODE	
LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
WHERE i.ITEMTYPEAFICODE ='KGF'  AND pd.CODE ='$rowdb22[ORDERCODE]'
GROUP BY i.PRODUCTIONDEMANDCODE,i.FINALEFFECTIVEDATE,ad.VALUESTRING,  
i.LEGALNAME1,i.SUBCODE02,i.SUBCODE03,i.SUBCODE04,i.PROJECTCODE,i.PRODUCTIONORDERCODE  ";	
$stmtIN   = db2_exec($conn1,$sqlDB2IN, array('cursor'=>DB2_SCROLLABLE));	 
$rowdb2IN = db2_fetch_assoc($stmtIN);
	 
	 $sql=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn WHERE tidn.demandno='$rowdb22[ORDERCODE]' and tidn.ket_bs ='BS Mekanik'");
	 $rowd=mysqli_fetch_array($sql);
	 $sql1=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn WHERE tidn.demandno='$rowdb22[ORDERCODE]' and tidn.ket_bs ='BS Produksi'");
	 $rowd1=mysqli_fetch_array($sql1);
	 $sql2=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn 
	 WHERE tidn.demandno='$rowdb22[ORDERCODE]' and tidn.ket_bs ='BS Lain-lain'");
	 $rowd2=mysqli_fetch_array($sql2);
	 if($rowdb2IN['INSKG']>0){
	 $prsn=round(($rowd['berat_awal']/$rowdb2IN['INSKG'])*100,2);
	 $prsn1=round(($rowd1['berat_awal']/$rowdb2IN['INSKG'])*100,2);
	 $prsn2=round(($rowd2['berat_awal']/$rowdb2IN['INSKG'])*100,2);
	 }
	 $hslkg=$rowdb2IN['INSKG']+$rowdb22L['KGSISA']+$rowd['berat_awal']+$rowd1['berat_awal']+$rowd2['berat_awal'];
	 $kghslPro=$rowdb2IN['INSKG']+$rowd['berat_awal']+$rowd1['berat_awal']+$rowd2['berat_awal'];
	 $losskg=round($rowdb21L['KGPAKAI']-$hslkg,2);
	 $losskg1=round($rowdb22['USEDBASEPRIMARYQUANTITY'],2)-round(($rowdb22['QUANTITYPER']*$kghslPro),2);
	 $losskg12=$rowdb22['USEDBASEPRIMARYQUANTITY']-($rowdb22['QUANTITYPER']*$kghslPro);
	 if($rowdb21L['KGPAKAI']>0 and $rowdb2IN['INSKG']>0){
	 $prsnLoss=round(($losskg/($rowdb21L['KGPAKAI']-$rowdb22L['KGSISA']))*100,2);
	 $prsnLoss1=round(($losskg12/($rowdb21L['KGPAKAI']-$rowdb22L['KGSISA']))*100,2); 	 
	 }else{
	 $prsnLoss=0; 
	 $prsnLoss1=0; 	 
	 }	 
	 
	 
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $no; ?></td>
      <td><?php echo $kdbenang; ?></td>
      <td><?php echo $rowdb22['GROUPLINE']; ?></td>
      <td><?php echo $rowdb22['ORDERCODE']; ?></td>
      <td><?php echo $rowdb22['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: right"><?php echo $persen; ?></td>
      <td style="text-align: right"><?php echo round($rowdb22['USERPRIMARYQUANTITY'],2); ?></td>
      <td style="text-align: right"><?php echo number_format(round(($rowdb22['QUANTITYPER']*$kghslPro),2),2) ?> Kgs</td>
      <td style="text-align: right"><?php echo number_format(round($rowdb22['USEDBASEPRIMARYQUANTITY'],2),2); ?> Kgs</td>
      <td style="text-align: right"><?php echo $rowdb22['WAREHOUSECODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb22['ISSUEDATE']; ?></td>
      <td style="text-align: right"><?php echo $losskg1; ?></td>
      <td style="text-align: right"><?php echo $prsnLoss1; ?></td>
      </tr>	  				  
	<?php 
	 $no++;
 		$tper+=$persen;
	 	$tlkg+=$losskg1;
	 	$tprl+=$prsnLoss1;
	 	$tqp+=round($rowdb22['USEDBASEPRIMARYQUANTITY'],2);
	 	$tqb+=round(($rowdb22['QUANTITYPER']*$kghslPro),2);
 } ?>
				  </tbody>
	<tfoot>
	<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: right"><?php echo $tper; ?></td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><?php echo $tqb; ?></td>
	    <td style="text-align: right"><?php echo $tqp; ?></td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right"><?php echo round($tlkg,3); ?></td>
	    <td style="text-align: right"><?php echo round($tprl,3); ?></td>
	    </tr>
	</tfoot>
            </table>
          </div>
              <!-- /.card-body -->
          </div>	
			</div>	
		</div>
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Benang Per Mesin</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example4" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th>Tgl Selesai</th>
                    <th>Prod. Order</th>
                    <th>Demand</th>
                    <th>NoArt</th>
                    <th>Jenis Benang</th>
                    <th>No Mesin</th>
                    <th>Rol</th>
                    <th>Kgs</th>
                    <th>BS Mekanik</th>
                    <th>%</th>
                    <th>BS Produksi</th>
                    <th>%</th>
                    <th>Lain-Lain</th>
                    <th>%</th>
                    <th>Sisa</th>
                    <th>Pakai</th>
                    <th>Loss (Kgs)</th>
                    <th>Loss (%)</th>
                    <th>Total Loss(%)</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	
$sqlDB2 =" SELECT i.PRODUCTIONDEMANDCODE,i.PRODUCTIONORDERCODE,count(e.WEIGHTREALNET) AS INSROL,sum(e.WEIGHTREALNET) AS INSKG,
i.LEGALNAME1,i.SUBCODE02,i.SUBCODE03,i.SUBCODE04,i.PROJECTCODE,VARCHAR_FORMAT(i.FINALEFFECTIVEDATE,'YYYY-MM-DD') AS TGLSELESAI,
ad.VALUESTRING AS NO_MESIN
FROM ITXVIEWKNTORDER i 
LEFT OUTER JOIN STOCKTRANSACTION s ON s.PRODUCTIONORDERCODE =i.PRODUCTIONORDERCODE
LEFT OUTER JOIN ELEMENTSINSPECTION e ON e.DEMANDCODE =s.ORDERCODE AND e.ELEMENTCODE =s.ITEMELEMENTCODE
LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE = i.PRODUCTIONDEMANDCODE	
LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
WHERE  (i.PROGRESSSTATUS='2' OR i.PROGRESSSTATUS='6') AND i.ITEMTYPEAFICODE ='KGF' AND i.PRODUCTIONORDERCODE='$ProdOrder'
GROUP BY i.PRODUCTIONDEMANDCODE,i.FINALEFFECTIVEDATE,ad.VALUESTRING,  
i.LEGALNAME1,i.SUBCODE02,i.SUBCODE03,i.SUBCODE04,i.PROJECTCODE,i.PRODUCTIONORDERCODE ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$no=1;   
$c=0;
$prsn=0;
$prsn1=0;
$prsn2=0;					  
 while ($rowdb2 = db2_fetch_assoc($stmt)) { 
	
	 $sqlDB21 = " SELECT sum(s.BASEPRIMARYQUANTITY) AS KGPAKAI FROM STOCKTRANSACTION s WHERE s.TEMPLATECODE='120' AND (s.ITEMTYPECODE='GYR' OR s.ITEMTYPECODE='DYR') AND s.ORDERCODE='$rowdb2[PRODUCTIONORDERCODE]' ";
	 $stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	 $rowdb21 = db2_fetch_assoc($stmt1);
	 
	 /*$sqlDB22 = " SELECT sum(s.WEIGHTREALNET) AS KGSISA FROM STOCKTRANSACTION s WHERE s.TEMPLATECODE='125' AND s.ITEMTYPECODE='GYR' AND s.ORDERCODE='$rowdb2[PRODUCTIONORDERCODE]' ";
	 */
	 $sqlDB22 = "SELECT 
	STOCKTRANSACTION.ORDERCODE,	
	COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_DUS,
	SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS KGSISA,
	SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
	(
SELECT LISTAGG(DISTINCT  TRIM(ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE),', ') AS PRODUCTIONDEMANDCODE,PRODUCTIONORDERCODE
FROM DB2ADMIN.ITXVIEWKNTORDER 
GROUP BY PRODUCTIONORDERCODE
) ITXVIEWKNTORDER ON ITXVIEWKNTORDER.PRODUCTIONORDERCODE =STOCKTRANSACTION.ORDERCODE 
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
	LEFT OUTER JOIN DB2ADMIN.INITIALS INITIALS ON 
	INITIALS.CODE =STOCKTRANSACTION.CREATIONUSER
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
	STOCKTRANSACTION.RETURNTRANSACTION ='1' AND STOCKTRANSACTION.ORDERCODE='$rowdb2[PRODUCTIONORDERCODE]' AND NOT STOCKTRANSACTION.ORDERCODE IS NULL
	GROUP BY STOCKTRANSACTION.ORDERCODE";
	 $stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	 $rowdb22 = db2_fetch_assoc($stmt2);
	 
	 $sqlDB23 = " SELECT FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
     FROM DB2ADMIN.PRODUCTIONRESERVATION PRODUCTIONRESERVATION LEFT OUTER JOIN 
     DB2ADMIN.BOMCOMPONENT BOMCOMPONENT ON 
     PRODUCTIONRESERVATION.BOMCOMPSEQUENCE=BOMCOMPONENT.SEQUENCE AND 
     PRODUCTIONRESERVATION.BOMCOMPBILLOFMATERIALNUMBERID=BOMCOMPONENT.BILLOFMATERIALNUMBERID LEFT OUTER JOIN 
     DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
     PRODUCTIONRESERVATION.FULLITEMIDENTIFIER =FULLITEMKEYDECODER.IDENTIFIER
	 WHERE BOMCOMPONENT.BILLOFMATERIALITEMTYPECODE='KGF' 
	 AND BOMCOMPONENT.BILLOFMATERIALSUBCODE02='$rowdb2[SUBCODE02]' 
	 AND BOMCOMPONENT.BILLOFMATERIALSUBCODE03 ='$rowdb2[SUBCODE03]'
	 AND BOMCOMPONENT.BILLOFMATERIALSUBCODE04 ='$rowdb2[SUBCODE04]'
	 AND PRODUCTIONRESERVATION.ORDERCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' ";
	 $stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
	 
	 
	 $sql=mysqli_query($con," SELECT sum(berat_awal) as berat_awal  FROM tbl_inspeksi_detail_now tidn WHERE tidn.demandno='$rowdb2[PRODUCTIONDEMANDCODE]' and tidn.ket_bs ='BS Mekanik'");
	 $rowd=mysqli_fetch_array($sql);
	 $sql1=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn WHERE tidn.demandno='$rowdb2[PRODUCTIONDEMANDCODE]' and tidn.ket_bs ='BS Produksi'");
	 $rowd1=mysqli_fetch_array($sql1);
	 $sql2=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn 
	 WHERE tidn.demandno='$rowdb2[PRODUCTIONDEMANDCODE]' and tidn.ket_bs ='BS Lain-lain'");
	 $rowd2=mysqli_fetch_array($sql2);
	 if($rowdb21['KGPAKAI']>0 and $rowdb2['INSKG']>0){
	 $prsn=round(($rowd['berat_awal']/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 $prsn1=round(($rowd1['berat_awal']/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 $prsn2=round(($rowd2['berat_awal']/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 }
	 $hslkg=$rowdb2['INSKG']+$rowdb22['KGSISA']+$rowd['berat_awal']+$rowd1['berat_awal']+$rowd2['berat_awal'];
	 $losskg=round($rowdb21['KGPAKAI']-$hslkg,2);
	 if($rowdb21['KGPAKAI']>0 and $rowdb2['INSKG']>0){
	 $prsnLoss=round(($losskg/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 }else{
	 $prsnLoss=0; 
	 }
	 $Thslkg=$losskg+$rowd['berat_awal']+$rowd1['berat_awal']+$rowd2['berat_awal'];
	 $Tlosskg=round($Thslkg,2);
	 if($rowdb21['KGPAKAI']>0 and $rowdb2['INSKG']>0){
	 $TprsnLoss=round(($Tlosskg/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 }else{
	 $TprsnLoss=0; 
	 }
	   ?> 
	  <tr>
      <td><?php echo $rowdb2['TGLSELESAI']; ?></td>
      <td><?php echo $rowdb2['PRODUCTIONORDERCODE']; ?></td>
      <td><?php echo $rowdb2['PRODUCTIONDEMANDCODE']; ?></td>
      <td><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03']); ?></td>
      <td><?php $noBn=1; while ($rowdb23 = db2_fetch_assoc($stmt3)){echo $noBn.". ".$rowdb23['SUMMARIZEDDESCRIPTION']."<br>"; $noBn++;}?></td>
      <td><?php echo $rowdb2['NO_MESIN']; ?></td>
      <td><?php echo $rowdb2['INSROL']; ?></td>
      <td><?php echo $rowdb2['INSKG']; ?></td>
      <td><?php echo round($rowd['berat_awal'],2); ?></td>
      <td><?php echo $prsn; ?></td>
      <td><?php echo round($rowd1['berat_awal'],2); ?></td>
      <td><?php echo $prsn1; ?></td>
      <td><?php echo round($rowd2['berat_awal'],2); ?></td>
      <td><?php echo $prsn2; ?></td>
      <td><?php echo round($rowdb22['KGSISA'],2); ?></td>
      <td><?php echo round($rowdb21['KGPAKAI'],2); ?></td>
      <td><?php echo $losskg; ?></td>
      <td><?php echo $prsnLoss; ?></td>
      <td><?php echo $TprsnLoss; ?></td>
      </tr>				  
	<?php 
	 $no++;} ?>
				  </tbody>
                  <!--<tfoot>
                  <tr>
                    <th>No</th>
                    <th>No Mc</th>
                    <th>Sft</th>
                    <th>User</th>
                    <th>Operator</th>
					<th>Leader</th>
                    <th>NoArt</th>
                    <th>TgtCnt (100%)</th>
                    <th>Rpm</th>
                    <th>Cnt/Roll</th>
					<th>Jam Kerja</th>
				    <th>Count</th>
				    <th>Count</th>
				    <th>RL</th>
				    <th>Kgs</th>
				    <th>Grp</th>
      				<th>Tgt Grp (%)</th>
      				<th>Eff (%)</th>
      				<th>Hasil (%)</th>  
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th>
				    <th>Kd</th>
				    <th>Min</th> 
					<th>Tanggal</th>
      				<th>Keterangan</th>
                  </tr>
                  </tfoot>-->
                </table>
              </div>
              <!-- /.card-body -->
            </div>	
		<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Pemakaian Benang</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <table id="example14" width="100%"class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
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
	$sqlDB21PB = "SELECT 
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
STOCKTRANSACTION.ONHANDUPDATE >1 AND ORDERCODE='$ProdOrder' AND NOT ORDERCODE IS NULL
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
		
$sqlKtPB=mysqli_query($con," SELECT no_mesin FROM tbl_mesin WHERE kd_dtex='".$msinPB."' LIMIT 1");
$rkPB=mysqli_fetch_array($sqlKtPB);
?>
	  <tr>
	  <td style="text-align: center"><?php echo $rowdb21PB['TRANSACTIONNUMBER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21PB['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><?php echo $shfPB; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21PB['CREATIONUSER']; ?></td>
      <td style="text-align: center"><?php echo $knittPB; ?></td>
      <td style="text-align: center"><?php if($rowdb22PROPB['PROJECTCODE']!=""){echo $rowdb22PROPB['PROJECTCODE'];}else{echo $rowdb22PROPB['ORIGDLVSALORDLINESALORDERCODE'];} ?></td>
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
		<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Benang Turunan (SISA)</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example15" width="100%" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">Trn No</th>
                    <th valign="middle" style="text-align: center">TGL</th>
                    <th valign="middle" style="text-align: center">Shift</th>
                    <th valign="middle" style="text-align: center">User</th>
                    <th valign="middle" style="text-align: center">KNITT</th>
                    <th valign="middle" style="text-align: center">No PO</th>
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
	$sqlDB21 = " 
	SELECT 
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
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
	INITIALS.LONGDESCRIPTION,
	KD.NOMC
	FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN (
	SELECT MCN.NOMC,p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,LISTAGG(DISTINCT  TRIM(p.PRODUCTIONDEMANDCODE),', ') AS PRODUCTIONDEMANDCODE  FROM PRODUCTIONDEMANDSTEP p
	LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE =p.PRODUCTIONDEMANDCODE 
	LEFT OUTER JOIN (SELECT ADSTORAGE.VALUESTRING AS NOMC,PRODUCTIONDEMAND.CODE FROM PRODUCTIONDEMAND
 	LEFT OUTER JOIN ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo') MCN ON MCN.CODE=p.PRODUCTIONDEMANDCODE
	GROUP BY p.PRODUCTIONORDERCODE,pd.SUBCODE01,pd.SUBCODE02,MCN.NOMC
	) KD ON KD.PRODUCTIONORDERCODE=STOCKTRANSACTION.ORDERCODE
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
	LEFT OUTER JOIN DB2ADMIN.INITIALS INITIALS ON 
	INITIALS.CODE =STOCKTRANSACTION.CREATIONUSER
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.RETURNTRANSACTION ='1' AND STOCKTRANSACTION.ORDERCODE='$ProdOrder' AND NOT STOCKTRANSACTION.ORDERCODE IS NULL
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
		
$sqlDB22 = " SELECT TRANSACTIONDATE,TRANSACTIONTIME FROM STOCKTRANSACTION WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.RETURNTRANSACTION ='1' AND STOCKTRANSACTION.TRANSACTIONDATE='$rowdb21[TRANSACTIONDATE]' 
AND STOCKTRANSACTION.ORDERCODE='$rowdb21[ORDERCODE]' AND STOCKTRANSACTION.CREATIONUSER='$rowdb21[CREATIONUSER]' ";
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);		
if($rowdb22['TRANSACTIONTIME']>="07:00:00" and $rowdb22['TRANSACTIONTIME']<="15:00:00"){
	$shf="1";
}elseif($rowdb22['TRANSACTIONTIME']>="15:00:00" and $rowdb22['TRANSACTIONTIME']<="23:00:00"){
	$shf="2";
}else{
	$shf="3";
}		
$msin = $rowdb21['NOMC'];		
$sqlKt=mysqli_query($con," SELECT no_mesin FROM tbl_mesin WHERE kd_dtex='".$msin."' LIMIT 1");
$rk=mysqli_fetch_array($sqlKt);	
if($rowdb21['LONGDESCRIPTION']!=""){$uid=trim($rowdb21['LONGDESCRIPTION']);}else{$uid=trim($rowdb21['CREATIONUSER']);}	


		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONNUMBER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><?php echo $shf; ?></td>
	  <td style="text-align: center"><?php  echo $uid; ?></td>
      <td style="text-align: center"><?php echo $knitt; ?></td>
      <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb21['ORDERCODE'])."#".trim($rowdb21['TRANSACTIONDATE'])."#".$uid."#".trim($rowdb21['LOTCODE'])."#".trim($kdbenang); ?>" class="show_detailTurunan"><?php echo $rowdb21['ORDERCODE']; ?></a></td>
      <td><?php echo $kdbenang; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['QTY_DUS']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td><?php  echo $msin; ?></td>
      <td><?php  echo $rk['no_mesin']; ?></td>
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
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>					
					</tfoot>             
                </table>
              </div>
              <!-- /.card-body -->
          </div>	
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Detail Data Benang</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
			<div class="form-group row">
              <label for="kd_benang" class="col-md-1">Kd Benang</label>
              <div class="col-md-4"> 
                    <select name="kdbg" class="form-control form-control-sm"  autocomplete="off">
						<option value="">Pilih</option>
						<?php 
						$sqlDB2 =" SELECT * FROM DB2ADMIN.PRODUCTIONRESERVATION 
						WHERE PRODUCTIONORDERCODE ='$ProdOrder'
						ORDER BY BOMCOMPSEQUENCE ASC";	
						$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
						while($rowdb = db2_fetch_assoc($stmt)){						$kdb=trim($rowdb['SUBCODE01']).trim($rowdb['SUBCODE02']).trim($rowdb['SUBCODE03']).trim($rowdb['SUBCODE04']).trim($rowdb['SUBCODE05']).trim($rowdb['SUBCODE06']).trim($rowdb['SUBCODE07']).trim($rowdb['SUBCODE08']);
						?>
						<option value="<?php echo $kdb;?>" <?php if($KdBng==$kdb){ echo "SELECTED";}?>><?php echo $kdb;?></option>
						<?php } ?>
					</select>
			   </div>
			  <button class="btn btn-primary" type="submit" >Cari Data</button>
            </div>	  
				  
                <table id="example12" width="100%" class="table table-sm table-bordered table-striped" style="font-size: 13px;">
                  <thead>
                  <tr>
                    <th colspan="10" valign="middle" style="text-align: center">Masuk</th>
                    <th colspan="3" valign="middle" style="text-align: center">Sisa</th>
                    <th colspan="2" valign="middle" style="text-align: center">Rincian</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">Tanggal</th>
                    <th valign="middle" style="text-align: center">No Ref</th>
                    <th valign="middle" style="text-align: center">Internal Doc</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Dus</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Ket</th>
                    <th valign="middle" style="text-align: center">Lokasi</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Dus</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Sisa</th>
                    <th valign="middle" style="text-align: center">Out Doc</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					  
	$sqlDB21 = " SELECT 
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
STOCKTRANSACTION.LOTCODE,  
STOCKTRANSACTION.TRANSACTIONDATE,
SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL,
SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
BALANCE.WHSLOCATIONWAREHOUSEZONECODE AS ZONE_BALANCE,
BALANCE.WAREHOUSELOCATIONCODE AS LOKASI_BALANCE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE=STOCKTRANSACTION.ORDERCODE AND 
INTERNALDOCUMENTLINE.ORDERLINE=STOCKTRANSACTION.ORDERLINE 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
LEFT OUTER JOIN DB2ADMIN.BALANCE BALANCE ON 
BALANCE.ELEMENTSCODE =STOCKTRANSACTION.ITEMELEMENTCODE
LEFT OUTER JOIN 
(SELECT STOCKTRANSACTION.ORDERCODE, STOCKTRANSACTION.ORDERLINE, COUNT(BALANCE.BASEPRIMARYQUANTITYUNIT) AS ROL,SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS BERAT, SUM(BALANCE.BASESECONDARYQUANTITYUNIT) AS CONES, BALANCE.LOTCODE  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION RIGHT OUTER JOIN 
		DB2ADMIN.BALANCE  BALANCE ON BALANCE.ELEMENTSCODE =STOCKTRANSACTION.ITEMELEMENTCODE  
		WHERE (STOCKTRANSACTION.LOGICALWAREHOUSECODE='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE='M904') 
		GROUP BY BALANCE.LOTCODE,STOCKTRANSACTION.ORDERCODE, STOCKTRANSACTION.ORDERLINE) SISA ON 
SISA.ORDERCODE=INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE  AND SISA.ORDERLINE=INTERNALDOCUMENTLINE.ORDERLINE	
WHERE (NOT INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE IS NULL) AND (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904' or STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501') AND
CONCAT(trim(INTERNALDOCUMENTLINE.SUBCODE01),CONCAT(trim(INTERNALDOCUMENTLINE.SUBCODE02),CONCAT(trim(INTERNALDOCUMENTLINE.SUBCODE03),CONCAT(trim(INTERNALDOCUMENTLINE.SUBCODE04),CONCAT(trim(INTERNALDOCUMENTLINE.SUBCODE05),CONCAT(trim(INTERNALDOCUMENTLINE.SUBCODE06),CONCAT(trim(INTERNALDOCUMENTLINE.SUBCODE07),trim(INTERNALDOCUMENTLINE.SUBCODE08))))))))='$KdBng' AND 
NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL AND
SISA.BERAT > 0
GROUP BY
STOCKTRANSACTION.LOTCODE,
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.CREATIONUSER,
BALANCE.WHSLOCATIONWAREHOUSEZONECODE,
BALANCE.WAREHOUSELOCATIONCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE']);
$loc_awal=trim($rowdb21['WHSLOCATIONWAREHOUSEZONECODE'])."-".trim($rowdb21['WAREHOUSELOCATIONCODE']);
$loc_balance=trim($rowdb21['ZONE_BALANCE'])."-".trim($rowdb21['LOKASI_BALANCE']);		
$itemc= trim($rowdb21['DECOSUBCODE01'])." ".trim($rowdb21['DECOSUBCODE02'])." "
        .trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04'])." "
        .trim($rowdb21['DECOSUBCODE05'])." ".trim($rowdb21['DECOSUBCODE06'])." "
        .trim($rowdb21['DECOSUBCODE07'])." ".trim($rowdb21['DECOSUBCODE08'])." "
        .trim($rowdb21['DECOSUBCODE09'])." ".trim($rowdb21['DECOSUBCODE10']);		
if (trim($rowdb21['PROVISIONALCOUNTERCODE']) =='I02M50') { $knitt = 'KNITTING ITTI- GREIGE'; } 
		$sqlDB22 = "SELECT COUNT(BASEPRIMARYQUANTITYUNIT) AS ROL,SUM(BASEPRIMARYQUANTITYUNIT) AS BERAT,BALANCE.LOTCODE  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
		DB2ADMIN.BALANCE  BALANCE ON BALANCE.ELEMENTSCODE =STOCKTRANSACTION.ITEMELEMENTCODE  
		WHERE STOCKTRANSACTION.LOGICALWAREHOUSECODE='M011' AND STOCKTRANSACTION.ORDERCODE='$rowdb21[PURCHASEORDERCODE]'
		AND STOCKTRANSACTION.ORDERLINE ='$rowdb21[ORDERLINE]' AND STOCKTRANSACTION.TRANSACTIONNUMBER='$rowdb21[TRANSACTIONNUMBER]' 
		AND STOCKTRANSACTION.LOTCODE='$rowdb21[LOTCODE]'
		GROUP BY BALANCE.LOTCODE ";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));	
		$rowdb22 = db2_fetch_assoc($stmt2);
		$sqlDB23 = " SELECT PRODUCT.LONGDESCRIPTION,PRODUCT.SHORTDESCRIPTION 
	   FROM DB2ADMIN.PRODUCT PRODUCT WHERE
       PRODUCT.ITEMTYPECODE='GYR' AND
	   PRODUCT.SUBCODE01='".trim($rowdb21['DECOSUBCODE01'])."' AND
       PRODUCT.SUBCODE02='".trim($rowdb21['DECOSUBCODE02'])."' AND
       PRODUCT.SUBCODE03='".trim($rowdb21['DECOSUBCODE03'])."' AND
	   PRODUCT.SUBCODE04='".trim($rowdb21['DECOSUBCODE04'])."' AND
       PRODUCT.SUBCODE05='".trim($rowdb21['DECOSUBCODE05'])."' AND
	   PRODUCT.SUBCODE06='".trim($rowdb21['DECOSUBCODE06'])."' AND
       PRODUCT.SUBCODE07='".trim($rowdb21['DECOSUBCODE07'])."' ";
	   $stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
	   $rowdb23 = db2_fetch_assoc($stmt3);
	   $sqlDB2S = "SELECT COUNT(BALANCE.BASEPRIMARYQUANTITYUNIT) AS ROL,SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS BERAT, SUM(BALANCE.BASESECONDARYQUANTITYUNIT) AS CONES, BALANCE.LOTCODE  
		FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION RIGHT OUTER JOIN 
		DB2ADMIN.BALANCE  BALANCE ON BALANCE.ELEMENTSCODE =STOCKTRANSACTION.ITEMELEMENTCODE  
		WHERE (STOCKTRANSACTION.LOGICALWAREHOUSECODE='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE='M904') AND STOCKTRANSACTION.ORDERCODE='$rowdb21[INTDOCUMENTPROVISIONALCODE]'
		AND STOCKTRANSACTION.ORDERLINE ='$rowdb21[ORDERLINE]' 
		GROUP BY BALANCE.LOTCODE ";					  
		$stmt2S   = db2_exec($conn1,$sqlDB2S, array('cursor'=>DB2_SCROLLABLE));	
		$rowdb2S = db2_fetch_assoc($stmt2S);
		$pos1=strpos($rowdb21['ITEMDESCRIPTION'],'*');
		$supp=substr($rowdb21['ITEMDESCRIPTION'],0,$pos1);
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td style="text-align: center"><?php echo $bon; ?></td>
      <td style="text-align: left"><?php echo $supp; ?></td>
      <td><?php echo $rowdb21['LOTCODE']; ?></td> 
      <td style="text-align: center"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: center"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_KG'],2); ?></td>
      <td style="text-align: left"><?php echo $rowdb21['ITEMDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php if($loc_balance!="-"){echo $loc_balance;}else{echo $loc_awal;} ?></td>
      <td style="text-align: center"><?php if($rowdb2S['CONES']!=""){echo number_format(round($rowdb2S['CONES']));}else{ echo"0";} ?></td>
      <td style="text-align: center"><?php if($rowdb2S['ROL']!=""){echo $rowdb2S['ROL'];}else{ echo"0";} ?></td>
      <td style="text-align: right"><?php if($rowdb2S['BERAT']!=""){echo number_format(round($rowdb2S['BERAT'],2),2);}else{ echo"0.00";} ?></td>
      <td style="text-align: center"><a href="" class="btn btn-info btn-xs"> <i class="fa fa-link"></i></a></td>
      <td style="text-align: center"><a href="" class="btn btn-primary btn-xs"> <i class="fa fa-link"></i></a></td>
      </tr>	  				  
	<?php 
	 $no++; 
	$tConesD+=$rowdb21['QTY_CONES'];
	$tDusD+=$rowdb21['QTY_ROL'];
	$tKGD+=$rowdb21['QTY_KG'];
	$tsConesD+=$rowdb2S['CONES'];
	$tsDusD+=$rowdb2S['ROL'];
	$tsKGD+=$rowdb2S['BERAT'];	
	} ?>
				  </tbody>
    <tfoot>
	<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($tConesD); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($tDusD); ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tKGD,2); ?></strong></td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center"><strong><?php echo number_format($tsConesD); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($tsDusD); ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tsKGD,2); ?></strong></td>
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
$sql=mysqli_query($con,"SELECT no_mutasi FROM tbl_mutasi_kain WHERE substr(no_mutasi,1,8) like '%".$format."%' ORDER BY no_mutasi DESC LIMIT 1 ") or die (mysql_error());
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