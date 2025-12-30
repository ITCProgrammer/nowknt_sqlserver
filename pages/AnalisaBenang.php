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


$sqlDB2 =" SELECT SUBCODE02,SUBCODE03,SUBCODE04, SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWHEADERKNTORDER 
WHERE ITEMTYPEAFICODE ='KGF' AND (PROJECTCODE ='$Project' OR ORIGDLVSALORDLINESALORDERCODE='$Project') AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6')
GROUP BY SUBCODE02,SUBCODE03,SUBCODE04,CURRENT_TIMESTAMP  ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
?>
<!-- Main content -->
      <div class="container-fluid">
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
          <?php
		  $sqlDB21 =" SELECT a.SUBCODE02,a.SUBCODE03,SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN,SUM(a4.VALUEDECIMAL) AS QTYOPIN,SUM(a5.VALUEDECIMAL) AS QTYOPOUT,CURRENT_TIMESTAMP AS TGLS  FROM ITXVIEWHEADERKNTORDER a
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID =a2.UNIQUEID AND a2.NAMENAME ='StatusRMP'
LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID =a3.UNIQUEID AND a3.NAMENAME ='QtySalin'
LEFT OUTER JOIN ADSTORAGE a4 ON p.ABSUNIQUEID =a4.UNIQUEID AND a4.NAMENAME ='QtyOperIn'
LEFT OUTER JOIN ADSTORAGE a5 ON p.ABSUNIQUEID =a5.UNIQUEID AND a5.NAMENAME ='QtyOperOut'
WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$Project' OR a.ORIGDLVSALORDLINESALORDERCODE='$Project') AND
a.SUBCODE02='$subC1' AND a.SUBCODE03='$subC2' AND a.SUBCODE04='$subC3' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6') AND (NOT a2.VALUESTRING ='3' OR a2.VALUESTRING IS NULL)
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
a.SUBCODE02='$subC1' AND a.SUBCODE03='$subC2' AND a.SUBCODE04='$subC3' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6')";	
		  $stmtR   = db2_exec($conn1,$sqlDB2R, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb2R = db2_fetch_assoc($stmtR);
		  $kRajut  = round(($rowdb21['BASEPRIMARYQUANTITY']+$rowdb21['QTYOPOUT'])-($rowdb21['QTYSALIN']+$rowdb21['QTYOPIN']),2)-round($rowdb2R['JQTY'],2);
$sqlDB21P =" 
SELECT
	i.SUBCODE02,
	i.SUBCODE03,
	i.SUBCODE04,
	SUM(i.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
	CURRENT_TIMESTAMP AS TGLS,
	p.PRODUCTIONORDERCODE
FROM
	ITXVIEWHEADERKNTORDER i
LEFT OUTER JOIN PRODUCTIONRESERVATION p ON 
i.SUBCODE02 = p.RELATEDCMPBILLOFMATSUBCODE02 AND 
i.SUBCODE03 = p.RELATEDCMPBILLOFMATSUBCODE03 AND
i.SUBCODE04 = p.RELATEDCMPBILLOFMATSUBCODE04 AND 
i.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE  
WHERE
	i.ITEMTYPEAFICODE = 'KGF'
	AND i.SUBCODE02 = '$subC1'
	AND i.SUBCODE03 = '$subC2'
	AND i.SUBCODE04 = '$subC3'
	AND (i.PROJECTCODE = '$Project'
		OR i.ORIGDLVSALORDLINESALORDERCODE = '$Project')
	AND (i.PROGRESSSTATUS = '2'
		OR i.PROGRESSSTATUS = '6')
GROUP BY
	i.SUBCODE02,
	i.SUBCODE03,
	i.SUBCODE04,
	CURRENT_TIMESTAMP,
	p.PRODUCTIONORDERCODE 
ORDER BY p.PRODUCTIONORDERCODE ASC	
LIMIT 1
		";	
		  $stmt1P   = db2_exec($conn1,$sqlDB21P, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb21P = db2_fetch_assoc($stmt1P);		  
		  
		  ?>
<?php if($Project!="" and $HangerNO!=""){ 
 $sqlDB2MC =" SELECT
	COUNT(CODE) AS JML
FROM
	DB2ADMIN.PRODUCTIONDEMAND
WHERE
	DLVSALORDERLINESALESORDERCODE = '$Project'
	AND PROGRESSSTATUS = '2'
	AND ITEMTYPEAFICODE = 'KGF'
	AND SUBCODE02 ='$subC1'
	AND SUBCODE03 ='$subC2'
	AND SUBCODE04 ='$subC3'
 ";	
		  $stmtMC   = db2_exec($conn1,$sqlDB2MC, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb2MC = db2_fetch_assoc($stmtMC);
$sqlDB23 ="SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT 
FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF' AND PRODUCT.SUBCODE02='$subC1' AND 
PRODUCT.SUBCODE03='$subC2' AND PRODUCT.COMPANYCODE='100' 
ORDER BY ADSTORAGE.FIELDNAME";	
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);	
	
		  ?>		  
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Analisa Kebutuhan Benang</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
			  <div class="float-left"> Qty Order:  <strong><?php echo round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2)." Kgs";?></strong> </div>	 <div class="float-right"> Jumlah Mesin Jalan:  <strong><?php echo $rowdb2MC['JML'];?></strong></div><br>
			  <div class="float-left">Kurang Rajut: <strong><font color="<?php if($kRajut < 1){ echo "RED"; }?>"><?php echo round($kRajut,2)." Kgs"; ?></font></strong></div>	 <div class="float-right">  Produksi/Hari: <strong><?php echo $rowdb23['STDRAJUT']*$rowdb2MC['JML']." Kgs"; ?></strong></div>
<table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
        <thead>
                  <tr>
                    <th width="17" rowspan="2" style="text-align: center; vertical-align: middle">No</th>
                    <th colspan="2" rowspan="2" style="text-align: center; vertical-align: middle">Jenis Benang</th>
                    <th width="57" rowspan="2" style="text-align: center; vertical-align: middle">Supplier Benang</th>
                    <th width="17" rowspan="2" style="text-align: center; vertical-align: middle">%</th>
                    <th width="69" rowspan="2" style="text-align: center; vertical-align: middle">Kebutuhan Benang</th>
                    <th width="69" rowspan="2" style="text-align: center; vertical-align: middle">Permohonan</th>
                    <th width="43" rowspan="2" style="text-align: center; vertical-align: middle">Shipped</th>
                    <th colspan="2" rowspan="2" style="text-align: center; vertical-align: middle">Received</th>
                    <th colspan="6" style="text-align: center; vertical-align: middle">Total Stock</th>
          </tr>
                  <tr>
                    <th colspan="2" style="text-align: center">P501</th>
                    <th colspan="2" style="text-align: center">M904</th>
                    <th colspan="2" style="text-align: center">GDB</th>
          </tr>
        </thead>
                  <tbody>
				  <?php
$sqlDB22 =" 
SELECT BNG.BOMCOMPSEQUENCE,BNG.SUBCODE01,BNG.SUBCODE02,BNG.SUBCODE03,BNG.SUBCODE04,
BNG.SUBCODE05,BNG.SUBCODE06,BNG.SUBCODE07,BNG.SUBCODE08,BNG.QUANTITYPER,BNG.ORDERCODE,BNG.RESERVATIONLINE FROM ITXVIEWHEADERKNTORDER 
LEFT OUTER JOIN (
SELECT PRODUCTIONRESERVATION.*,FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION FROM DB2ADMIN.PRODUCTIONRESERVATION 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
PRODUCTIONRESERVATION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE02='$subC1' AND 
PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE03='$subC2' AND 
PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE04='$subC3' AND
PRODUCTIONRESERVATION.PRODUCTIONORDERCODE='".$rowdb21P['PRODUCTIONORDERCODE']."'
ORDER BY BOMCOMPSEQUENCE ASC
) BNG ON BNG.PRODUCTIONORDERCODE=ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE 
WHERE ITXVIEWHEADERKNTORDER.ITEMTYPEAFICODE ='KGF' AND (ITXVIEWHEADERKNTORDER.PROJECTCODE ='$Project' OR ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE ='$Project') AND 
ITXVIEWHEADERKNTORDER.SUBCODE02='$subC1' AND ITXVIEWHEADERKNTORDER.SUBCODE03='$subC2' AND
ITXVIEWHEADERKNTORDER.SUBCODE04='$subC3' AND
ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE='".$rowdb21P['PRODUCTIONORDERCODE']."' AND
(ITXVIEWHEADERKNTORDER.PROGRESSSTATUS='2' OR ITXVIEWHEADERKNTORDER.PROGRESSSTATUS='6') 
GROUP BY BNG.BOMCOMPSEQUENCE,BNG.SUBCODE01,BNG.SUBCODE02,BNG.SUBCODE03,BNG.SUBCODE04,
BNG.SUBCODE05,BNG.SUBCODE06,BNG.SUBCODE07,BNG.SUBCODE08,BNG.QUANTITYPER,BNG.ORDERCODE,BNG.RESERVATIONLINE
ORDER BY BNG.BOMCOMPSEQUENCE ASC
	   ";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
					  
$no=1; 
$c=0;
 while ($rowdb22 = db2_fetch_assoc($stmt2)) {	
	 $kdbenang=trim($rowdb22['SUBCODE01'])." ".trim($rowdb22['SUBCODE02']).trim($rowdb22['SUBCODE03'])." ".trim($rowdb22['SUBCODE04'])." ".trim($rowdb22['SUBCODE05'])." ".trim($rowdb22['SUBCODE06'])." ".trim($rowdb22['SUBCODE07'])." ".trim($rowdb22['SUBCODE08']);
	 $persen=number_format($rowdb22['QUANTITYPER']*100,2);
	 //$kbhBenang=round((round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2)*($persen+1))/100,2);
	 $kbhBenang=round((round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2)*($persen))/100,2);
$sqlDB23 =" 
SELECT SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
SUM(SHIPPEDBASEPRIMARYQUANTITY) AS SHIPPEDBASEPRIMARYQUANTITY,
SUM(RECEIVEDBASEPRIMARYQUANTITY) AS RECEIVEDBASEPRIMARYQUANTITY,
SUM(P501) AS P501,
SUM(M904) AS M904 FROM
(
SELECT ITNDOC.*,STKP501.QTYSTK AS P501 ,STKM904.QTYSTK AS M904 FROM
(SELECT il.INTDOCPROVISIONALCOUNTERCODE,il.INTDOCUMENTPROVISIONALCODE,SUM(il.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
SUM(il.SHIPPEDBASEPRIMARYQUANTITY) AS SHIPPEDBASEPRIMARYQUANTITY,
SUM(il.RECEIVEDBASEPRIMARYQUANTITY) AS RECEIVEDBASEPRIMARYQUANTITY
FROM INTERNALDOCUMENT i LEFT JOIN
INTERNALDOCUMENTLINE il ON i.PROVISIONALCODE=il.INTDOCUMENTPROVISIONALCODE AND i.PROVISIONALCOUNTERCODE=il.INTDOCPROVISIONALCOUNTERCODE  
WHERE il.EXTERNALREFERENCE ='$Project' AND
il.INTERNALREFERENCE LIKE '$HangerNO%' AND
il.SUBCODE01='$rowdb22[SUBCODE01]' AND
il.SUBCODE02='$rowdb22[SUBCODE02]' AND
il.SUBCODE03='$rowdb22[SUBCODE03]' AND
il.SUBCODE04='$rowdb22[SUBCODE04]' AND
il.SUBCODE05='$rowdb22[SUBCODE05]' AND
il.SUBCODE06='$rowdb22[SUBCODE06]' AND
il.SUBCODE07='$rowdb22[SUBCODE07]' AND
il.SUBCODE08='$rowdb22[SUBCODE08]'
GROUP BY il.INTDOCPROVISIONALCOUNTERCODE,il.INTDOCUMENTPROVISIONALCODE) ITNDOC
LEFT OUTER JOIN (
SELECT SUM(b.BASEPRIMARYQUANTITYUNIT) AS QTYSTK,s.ORDERCODE,s.TOKENCODE  FROM STOCKTRANSACTION s 
LEFT OUTER JOIN
BALANCE b ON s.ITEMELEMENTCODE =b.ELEMENTSCODE
WHERE s.TOKENCODE ='RECEIVED' AND b.LOGICALWAREHOUSECODE='P501'
GROUP BY s.ORDERCODE,s.TOKENCODE
) STKP501 ON STKP501.ORDERCODE=ITNDOC.INTDOCUMENTPROVISIONALCODE
LEFT OUTER JOIN (
SELECT SUM(b.BASEPRIMARYQUANTITYUNIT) AS QTYSTK,s.ORDERCODE,s.TOKENCODE  FROM STOCKTRANSACTION s 
LEFT OUTER JOIN
BALANCE b ON s.ITEMELEMENTCODE =b.ELEMENTSCODE
WHERE s.TOKENCODE ='RECEIVED' AND b.LOGICALWAREHOUSECODE='M904'
GROUP BY s.ORDERCODE,s.TOKENCODE
) STKM904 ON STKM904.ORDERCODE=ITNDOC.INTDOCUMENTPROVISIONALCODE
) ITN
";	
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);		 
	 
$Sdb20="
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
	$st10   = db2_exec($conn1,$Sdb20, array('cursor'=>DB2_SCROLLABLE));
	$rdb20 = db2_fetch_assoc($st10);	 
$Sdb21="
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
	$st11   = db2_exec($conn1,$Sdb21, array('cursor'=>DB2_SCROLLABLE));
	$rdb21 = db2_fetch_assoc($st11);	 
$Sdb22="
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
	$st12   = db2_exec($conn1,$Sdb22, array('cursor'=>DB2_SCROLLABLE));
	$rdb22 = db2_fetch_assoc($st12);
$Sdb23="
	SELECT x.* FROM DB2ADMIN.PRODUCTIONRESERVATIONCOMMENT x
WHERE PRODUCTIONRESERVATIONORDERCODE ='$rowdb22[ORDERCODE]' AND PRORESERVATIONRESERVATIONLINE ='$rowdb22[RESERVATIONLINE]'
	";
	$st13   = db2_exec($conn1,$Sdb23, array('cursor'=>DB2_SCROLLABLE));
	$rdb23 = db2_fetch_assoc($st13);	 
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $no; ?></td>
      <td width="123"><?php echo $kdbenang; ?></td>
      <td width="18" align="center"><a href="#" id="<?php echo $Project;?>#<?php echo $HangerNO; ?>#<?php echo $kdbenang; ?>" class="btn btn-xs btn-info show_detail_proj" alt="Detail"><i class="far fa-eye" aria-hidden="true"></i> </a> </td>
      <td style="text-align: center"><?php echo $rdb23['COMMENTTEXT']; ?></td>
      <td style="text-align: right"><?php echo $persen; ?></td>
      <td style="text-align: right"><?php echo number_format($kbhBenang,2); ?> Kgs</td>
      <td style="text-align: right"><?php echo number_format(round($rowdb23['BASEPRIMARYQUANTITY'],2),2); ?> Kgs</td>
      <td style="text-align: right"><?php echo number_format(round($rowdb23['SHIPPEDBASEPRIMARYQUANTITY'],2),2); ?> Kgs</td>
      <td width="21" style="text-align: center"><a href="#" id="<?php echo $Project;?>#<?php echo $HangerNO; ?>#<?php echo $kdbenang; ?>" class="btn btn-xs btn-info show_detail_itn" alt="Detail"><i class="far fa-eye" aria-hidden="true"></i> </a> </td>
      <td width="42" style="text-align: right"><?php echo number_format(round($rowdb23['RECEIVEDBASEPRIMARYQUANTITY'],2),2); ?> Kgs</td>
      <td width="21" align="center" style="text-align: center"><a href="#" id="<?php echo $kdbenang; ?>" class="btn btn-xs btn-info show_detail_p501" alt="Detail"><i class="far fa-eye" aria-hidden="true"></i> </a> </td>
      <td width="42" style="text-align: right"><?php echo number_format(round($rdb22['TOTALSTK'],2),2); ?> Kgs</td>
      <td width="21" align="center"><a href="#" id="<?php echo $kdbenang; ?>" class="btn btn-xs btn-info show_detail_m904" alt="Detail"><i class="far fa-eye" aria-hidden="true"></i> </a> </td>
      <td width="42" style="text-align: right"><?php echo number_format(round($rdb21['TOTALSTK'],2),2); ?> Kgs</td>
      <td width="21" align="center"><a href="#" id="<?php echo $kdbenang; ?>" class="btn btn-xs btn-info show_detail_gdb" alt="Detail"><i class="far fa-eye" aria-hidden="true"></i> </a> </td>
      <td width="42"><?php echo number_format(round($rdb20['TOTALSTK'],2),2); ?> Kgs</td>
      </tr>	  				  
	<?php 
	 $no++;
 $tpersen+=$persen;
 $tkbhBenang+=$kbhBenang;	   
 } ?>
				  </tbody>
	<tfoot>
	<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><?php echo number_format(round($tpersen,2),2); ?></td>
	    <td style="text-align: right"><?php echo number_format(round($tkbhBenang,2),2); ?> Kgs</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    </tr>
	</tfoot>
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
<?php } ?>		  
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="DetailITNShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="DetailP501Show" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="DetailM904Show" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="DetailGDBShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="DetailPRJShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
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