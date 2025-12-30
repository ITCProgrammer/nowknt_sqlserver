<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
			
		<div class="card card-success">
              <!--<div class="card-header">
                <h3 class="card-title">Status Mesin</h3>
              </div>-->
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example12" width="100%" class="table table-sm table-striped table-bordered table-hover" style="font-size: 13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">#</th>
                    <th style="text-align: center">Project</th>
                    <th style="text-align: center">NoArt</th>
                    <th style="text-align: center">Total MC</th>
                    <th style="text-align: center">Jml MC SJ</th>
                    <th style="text-align: center">Qty Order</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">KD BENANG</th>
                    <th style="text-align: center">Kebutuhan Benang</th>
                    <th style="text-align: center">Permohonan</th>
                    <th style="text-align: center">Total</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
$sqlDB2=" SELECT
	DMN.ORIGDLVSALORDLINESALORDERCODE,
	COUNT(DMN.ORIGDLVSALORDLINESALORDERCODE) AS JMLMC,
	DMN.SUBCODE02,
	DMN.SUBCODE03,
	DMN.SUBCODE04,
	RSV.ITEMTYPEAFICODE AS JKD1,
	RSV.SUBCODE01 AS KD1,
	RSV.SUBCODE02 AS KD2,
	RSV.SUBCODE03 AS KD3,
	RSV.SUBCODE04 AS KD4,
	RSV.SUBCODE05 AS KD5,
	RSV.SUBCODE06 AS KD6,
	RSV.SUBCODE07 AS KD7,
	RSV.SUBCODE08 AS KD8,
	RSV.QUANTITYPER
FROM
	DB2ADMIN.USERGENERICGROUP
INNER JOIN
(
	SELECT
		ADSTORAGE.VALUESTRING,
		AD1.VALUEDATE,
		AD2.VALUEDATE AS RMPREQDATE,
		AD7.VALUEDATE AS RMPREQDATETO ,
		AD3.VALUEDECIMAL AS QTYSALIN,
		AD4.VALUEDECIMAL AS QTYOPIN ,
		AD5.VALUEDECIMAL AS QTYOPOUT,
		AD6.VALUESTRING AS STSOPR,
		ITXVIEWKNTORDER.*,
		CURRENT_TIMESTAMP AS TGLS
	FROM
		ITXVIEWKNTORDER
	LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON
		PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
	LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON
		ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
		AND ADSTORAGE.NAMENAME = 'MachineNo'
	LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON
		AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
		AND AD1.NAMENAME = 'TglRencana'
	LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON
		AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
		AND AD2.NAMENAME = 'RMPReqDate'
	LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON
		AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
		AND AD3.NAMENAME = 'QtySalin'
	LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD4 ON
		AD4.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
		AND AD4.NAMENAME = 'QtyOperIn'
	LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD5 ON
		AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
		AND AD5.NAMENAME = 'QtyOperOut'
	LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON
		AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
		AND AD6.NAMENAME = 'StatusOper'
	LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD7 ON
		AD7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID
		AND AD7.NAMENAME = 'RMPGreigeReqDateTo'
	WHERE
		ITXVIEWKNTORDER.ITEMTYPEAFICODE = 'KGF'
		AND (PRODUCTIONDEMAND.PROGRESSSTATUS = '2'
			OR AD6.VALUESTRING = '1')
	ORDER BY
		AD1.VALUEDATE ASC
) DMN ON
	DMN.VALUESTRING = USERGENERICGROUP.CODE
LEFT OUTER JOIN (
	SELECT
		PRODUCTIONDEMANDCODE,
		trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) AS IDS
	FROM
		PRODUCTIONDEMANDSTEP
	WHERE
		(OPERATIONCODE = 'INS1'
			OR OPERATIONCODE = 'KNT1')
		AND ITEMTYPEAFICOMPANYCODE = '100'
	GROUP BY
		PRODUCTIONDEMANDCODE
) S1 ON
	S1.PRODUCTIONDEMANDCODE = DMN.CODE
LEFT OUTER JOIN (
SELECT
	p.ORIGDLVSALORDLINESALORDERCODE,
	p2.QUANTITYPER,
	p2.ITEMTYPEAFICODE,
	p2.SUBCODE01,
	p2.SUBCODE02,
	p2.SUBCODE03,
	p2.SUBCODE04,
	p2.SUBCODE05,
	p2.SUBCODE06,
	p2.SUBCODE07,
	p2.SUBCODE08
FROM
	PRODUCTIONDEMAND p
LEFT OUTER JOIN PRODUCTIONRESERVATION p2 ON
	p2.ORDERCODE = p.CODE
WHERE (p2.ITEMTYPEAFICODE ='GYR' OR p2.ITEMTYPEAFICODE ='DYR') 	
GROUP BY
    p.ORIGDLVSALORDLINESALORDERCODE,
	p2.ITEMTYPEAFICODE,
	p2.QUANTITYPER,
	p2.SUBCODE01,
	p2.SUBCODE02,
	p2.SUBCODE03,
	p2.SUBCODE04,
	p2.SUBCODE05,
	p2.SUBCODE06,
	p2.SUBCODE07,
	p2.SUBCODE08
) RSV ON RSV.ORIGDLVSALORDLINESALORDERCODE=DMN.ORIGDLVSALORDLINESALORDERCODE 
WHERE
	( ((DMN.STSOPR = '1'
		OR DMN.PROGRESSSTATUS = '6')
	AND (S1.IDS = '3 ,3'
		OR S1.IDS = '3 ,3 ,3'))
	OR ((DMN.STSOPR = '1'
		OR DMN.PROGRESSSTATUS = '2')
	AND (
	S1.IDS = '2 ,0'
		OR 
	S1.IDS = '0 ,2'
		OR
	S1.IDS = '2 ,2'
		OR
	S1.IDS = '2 ,3'
		OR
	S1.IDS = '3 ,2'
		OR
	S1.IDS = '2 ,0 ,0'
		OR
	S1.IDS = '2 ,2 ,0'
		OR
	S1.IDS = '2 ,2 ,3'
		OR
	S1.IDS = '0 ,0 ,2'
		OR
	S1.IDS = '0 ,2 ,0'
		OR
	S1.IDS = '0 ,2 ,2'
		OR
	S1.IDS = '0 ,2 ,0 ,0'
		OR
	S1.IDS = '2 ,2 ,0 ,2'
		OR
	S1.IDS = '2 ,2 ,0 ,0'
		OR
	S1.IDS = '3 ,2 ,0 ,2'
		OR
	S1.IDS = '2 ,3 ,0 ,3'
		OR
	S1.IDS = '0 ,2') 
	) )
GROUP BY
	DMN.ORIGDLVSALORDLINESALORDERCODE,
	DMN.SUBCODE02,
	DMN.SUBCODE03,
	DMN.SUBCODE04,
	RSV.ITEMTYPEAFICODE,
	RSV.SUBCODE01,
	RSV.SUBCODE02,
	RSV.SUBCODE03,
	RSV.SUBCODE04,
	RSV.SUBCODE05,
	RSV.SUBCODE06,
	RSV.SUBCODE07,
	RSV.SUBCODE08,
	RSV.QUANTITYPER";
					  
$no=1;   
$c=0;
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
while($rowdb2 = db2_fetch_assoc($stmt)){		
		
$sqlDB21 =" SELECT a.SUBCODE02,a.SUBCODE03,a.SUBCODE04,SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN,CURRENT_TIMESTAMP AS TGLS  FROM ITXVIEWHEADERKNTORDER a
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID =a2.UNIQUEID AND a2.NAMENAME ='StatusRMP'
LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID =a3.UNIQUEID AND a3.NAMENAME ='QtySalin'
WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='".$rowdb2['ORIGDLVSALORDLINESALORDERCODE']."' OR a.ORIGDLVSALORDLINESALORDERCODE='".$rowdb2['ORIGDLVSALORDLINESALORDERCODE']."') AND
a.SUBCODE02='".trim($rowdb2['SUBCODE02'])."' AND a.SUBCODE03='".trim($rowdb2['SUBCODE03'])."' AND a.SUBCODE04='".trim($rowdb2['SUBCODE04'])."' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6') AND (NOT a2.VALUESTRING ='3' OR a2.VALUESTRING IS NULL)
GROUP BY a.SUBCODE02,a.SUBCODE03,a.SUBCODE04,CURRENT_TIMESTAMP
		";	
		  $stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb21 = db2_fetch_assoc($stmt1);	
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
WHERE il.EXTERNALREFERENCE ='".$rowdb2['ORIGDLVSALORDLINESALORDERCODE']."' AND
il.INTERNALREFERENCE LIKE '".trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04'])."%' AND
il.SUBCODE01='$rowdb2[KD1]' AND
il.SUBCODE02='$rowdb2[KD2]' AND
il.SUBCODE03='$rowdb2[KD3]' AND
il.SUBCODE04='$rowdb2[KD4]' AND
il.SUBCODE05='$rowdb2[KD5]' AND
il.SUBCODE06='$rowdb2[KD6]' AND
il.SUBCODE07='$rowdb2[KD7]' AND
il.SUBCODE08='$rowdb2[KD8]'
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
	
$sqlDB24 =" SELECT COUNT(CODE) AS JML FROM DB2ADMIN.PRODUCTIONDEMAND x
WHERE ORIGDLVSALORDLINESALORDERCODE ='".$rowdb2['ORIGDLVSALORDLINESALORDERCODE']."' AND ITEMTYPEAFICODE ='KGF' AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6')";	
		  $stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb24 = db2_fetch_assoc($stmt4);	
		
	   ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no; ?></td>
      <td style="text-align: center"><?php if($rowdb2['PROJECTCODE']!=""){echo $rowdb2['PROJECTCODE'];}else{ echo $rowdb2['ORIGDLVSALORDLINESALORDERCODE']; }?></td>
      <td style="text-align: center"><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?></td>
      <td style="text-align: center"><?php echo $rowdb24['JML']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['JMLMC'];?></td>
      <td style="text-align: center"><?php echo round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2);?></td>
      <td style="text-align: center"><?php echo $rowdb2['QUANTITYPER']*100;?></td>
      <td style="text-align: left"><?php echo trim($rowdb2['KD1'])."-".trim($rowdb2['KD2'])."-".trim($rowdb2['KD3'])."-".trim($rowdb2['KD4'])."-".trim($rowdb2['KD5'])."-".trim($rowdb2['KD6'])."-".trim($rowdb2['KD7'])."-".trim($rowdb2['KD8'])."-";?></td>
      <td style="text-align: right"><?php echo number_format((round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2))*($rowdb2['QUANTITYPER']),2);?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb23['BASEPRIMARYQUANTITY'],2),2); ?></td>
      <td style="text-align: right"><?php echo number_format(((round($rowdb21['BASEPRIMARYQUANTITY']-$rowdb21['QTYSALIN'],2))*($rowdb2['QUANTITYPER']))-(round($rowdb23['BASEPRIMARYQUANTITY'],2)),2);?></td>
      </tr>
					  
	<?php 
	 $no++;
	} ?>
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
      </div><!-- /.container-fluid -->
    <!-- /.content -->
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