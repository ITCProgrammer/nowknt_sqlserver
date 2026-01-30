<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
			
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Data Kain Greige</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example12" width="100%" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Project</th>
                    <th style="text-align: center">ProdNo</th>
                    <th style="text-align: center">DemandNo</th>
                    <th style="text-align: center">Konsumen</th>
                    <th style="text-align: center">Mesin Rajut</th>
                    <th style="text-align: center">NoArt</th>
                    <th style="text-align: center">Order</th>
                    <th style="text-align: center">Rajut</th>
                    <th style="text-align: center">M502</th>
                    <th style="text-align: center">TR11</th>
                    <th style="text-align: center">M021</th>
                    <th style="text-align: center">Ch Pro In</th>
                    <th style="text-align: center">Ch Pro Out</th>
                    <th style="text-align: center">Status PO</th>
                    <th style="text-align: center">Tgl Selesai</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
$sqlDB2 =" WITH  
QTY_GREIGE 
AS (
SELECT
    SUM(CASE WHEN b.LOGICALWAREHOUSECODE = 'M502' THEN b.BASEPRIMARYQUANTITYUNIT ELSE 0 END) AS KG_M502,
    SUM(CASE WHEN b.LOGICALWAREHOUSECODE = 'TR11' THEN b.BASEPRIMARYQUANTITYUNIT ELSE 0 END) AS KG_TR11,
    SUM(CASE WHEN b.LOGICALWAREHOUSECODE = 'M021' THEN b.BASEPRIMARYQUANTITYUNIT ELSE 0 END) AS KG_M021,
    b.LOTCODE
FROM BALANCE b
WHERE b.ITEMTYPECODE = 'KGF'
GROUP BY b.LOTCODE
),
JMLINS AS (
SELECT
        JML,
        INSPECTIONENDDATETIME,
        DEMANDCODE
    FROM (
        SELECT
            COUNT(WEIGHTREALNET) AS JML,
            INSPECTIONENDDATETIME,
            DEMANDCODE,
            ROW_NUMBER() OVER (
                PARTITION BY DEMANDCODE
                ORDER BY INSPECTIONENDDATETIME ASC
            ) AS RN
        FROM ELEMENTSINSPECTION
        WHERE ELEMENTITEMTYPECODE = 'KGF'
          AND QUALITYREASONCODE = 'PM'
        GROUP BY INSPECTIONENDDATETIME, DEMANDCODE
    ) X
    WHERE RN = 1
),
STS_INSKNT AS (
SELECT 
trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) as IDS,
PRODUCTIONDEMANDCODE
FROM PRODUCTIONDEMANDSTEP 
WHERE OPERATIONCODE IN ('INS1','KNT1') AND ITEMTYPEAFICODE = 'KGF'
GROUP BY PRODUCTIONDEMANDCODE
)
SELECT *,AD5.VALUEDECIMAL AS QTYOPIN,
AD6.VALUEDECIMAL AS QTYOPOUT,
ADSTORAGE.VALUESTRING AS MC,
qtyg.KG_M502,
qtyg.KG_TR11,
qtyg.KG_M021,
sts.IDS,
insj.JML,
insj.INSPECTIONENDDATETIME,
CURRENT_TIMESTAMP AS TGLS,VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') AS TGLTUTUP
FROM ITXVIEWKNTORDER  
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD5 ON AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD5.NAMENAME ='QtyOperIn'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD6.NAMENAME ='QtyOperOut'
LEFT OUTER JOIN QTY_GREIGE qtyg ON qtyg.LOTCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN STS_INSKNT sts ON sts.PRODUCTIONDEMANDCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN JMLINS insj ON insj.DEMANDCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
WHERE ITXVIEWKNTORDER.ITEMTYPEAFICODE ='KGF' AND ITXVIEWKNTORDER.PROGRESSSTATUS IN ('2','6')
AND ( ADD_DAYS(CASE WHEN VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') IS NULL THEN
VARCHAR_FORMAT(CURRENT_TIMESTAMP,'YYYY-MM-DD') ELSE VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') END,7) > CURRENT_TIMESTAMP 
OR qtyg.KG_TR11 > 0 OR qtyg.KG_M502 > 0 )";	
$stmt   = db2_prepare($conn1,$sqlDB2);
db2_execute($stmt);					  
$no=1;
while($rowdb2 = db2_fetch_assoc($stmt)){

$sqlDB22 =" SELECT COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF'";	
$stmt2   = db2_prepare($conn1,$sqlDB22);
db2_execute($stmt2);	
$rowdb22 = db2_fetch_assoc($stmt2);

//$sqlDB23 =" SELECT sum(b.BASEPRIMARYQUANTITYUNIT) AS KG FROM BALANCE b 
//WHERE b.LOTCODE='$rowdb2[PRODUCTIONDEMANDCODE]' AND b.LOGICALWAREHOUSECODE='M502'
//GROUP BY b.LOTCODE
//";	
//$stmt3   = db2_prepare($conn1,$sqlDB23);
//db2_execute($stmt3);	
//$rowdb23 = db2_fetch_assoc($stmt3);
//	
//$sqlDB26 =" SELECT sum(b.BASEPRIMARYQUANTITYUNIT) AS KG FROM BALANCE b 
//WHERE b.LOTCODE='$rowdb2[PRODUCTIONDEMANDCODE]' AND b.LOGICALWAREHOUSECODE='TR11'
//GROUP BY b.LOTCODE
//";	
//$stmt6   = db2_prepare($conn1,$sqlDB26);
//db2_execute($stmt6);	
//$rowdb26 = db2_fetch_assoc($stmt6);
//
//$sqlDB27 =" SELECT sum(b.BASEPRIMARYQUANTITYUNIT) AS KG FROM BALANCE b 
//WHERE b.LOTCODE='$rowdb2[PRODUCTIONDEMANDCODE]' AND b.LOGICALWAREHOUSECODE='M021'
//GROUP BY b.LOTCODE
//";	
//$stmt7   = db2_prepare($conn1,$sqlDB27);
//db2_execute($stmt7);	
//$rowdb27 = db2_fetch_assoc($stmt7);
	
//$sqlDB28 =" 
//SELECT SUM(il.SHIPPEDBASEPRIMARYQUANTITY) AS SHIPPEDBASEPRIMARYQUANTITY,
//SUM(il.RECEIVEDBASEPRIMARYQUANTITY) AS RECEIVEDBASEPRIMARYQUANTITY FROM INTERNALDOCUMENT i 
//LEFT OUTER JOIN INTERNALDOCUMENTLINE il ON i.PROVISIONALCODE=il.INTDOCUMENTPROVISIONALCODE AND i.PROVISIONALCOUNTERCODE=il.INTDOCPROVISIONALCOUNTERCODE  
//LEFT OUTER JOIN (SELECT  ORDERLINE,ORDERCODE,LOTCODE,PHYSICALWAREHOUSECODE   FROM STOCKTRANSACTION 
//GROUP BY ORDERLINE,ORDERCODE,LOTCODE,PHYSICALWAREHOUSECODE ) s ON il.INTDOCUMENTPROVISIONALCODE= s.ORDERCODE AND il.ORDERLINE =s.ORDERLINE AND s.PHYSICALWAREHOUSECODE ='M50'
//WHERE il.PROJECTCODE ='$rowdb2[PROJECTCODE]' AND
//il.ITEMTYPEAFICODE='KGF' AND
//il.EXTERNALREFERENCE='$rowdb2[PRODUCTIONORDERCODE]' AND
//il.SUBCODE01='$rowdb2[SUBCODE01]' AND
//il.SUBCODE02='$rowdb2[SUBCODE02]' AND
//il.SUBCODE03='$rowdb2[SUBCODE03]' AND
//il.SUBCODE04='$rowdb2[SUBCODE04]' AND 
//s.LOTCODE ='$rowdb2[PRODUCTIONDEMANDCODE]'
//";	
//$stmt8   = db2_prepare($conn1,$sqlDB28);
//db2_execute($stmt8);	
//$rowdb28 = db2_fetch_assoc($stmt8);
	
//$sqlDB24 =" SELECT 
//trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) as IDS	
//FROM PRODUCTIONDEMANDSTEP 
//WHERE PRODUCTIONDEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND (OPERATIONCODE='INS1' OR OPERATIONCODE='KNT1')
//GROUP BY PRODUCTIONDEMANDCODE ";	
//$stmt4   = db2_prepare($conn1,$sqlDB24);
//db2_execute($stmt4);	
//$rowdb24 = db2_fetch_assoc($stmt4);
//
//$sqlDB25 =" SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME FROM 
//ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM'
//GROUP BY INSPECTIONENDDATETIME";	
//$stmt5   = db2_prepare($conn1,$sqlDB25);
//db2_execute($stmt5);	
//$rowdb25 = db2_fetch_assoc($stmt5);
	
if($rowdb2['PROGRESSSTATUS']=="2" and $rowdb2['JML']>"0" ){
			$stts="<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Perbaikan Mesin</small>";
			$totHari=abs($hariPR);
		}
		elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb2['IDS']=="0 ,0" ){
			$stts="<small class='badge badge-warning'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
			$totHari=abs($hariPC);
		}else if($rowdb2['PROGRESSSTATUS']=="2" and ($rowdb2['IDS']=="2 ,0" or $rowdb2['IDS']=="0 ,2" or $rowdb2['IDS']=="2 ,2") ) {
			$stts="<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
			$totHari=abs($hariSJ);
		}else if($rowdb2['PROGRESSSTATUS']=="6"){
			$stts="<small class='badge badge-info'><i class='far fa-calendar-check blink_me'></i> Selesai</small>";
		}else{
			$stts="Tidak Ada PO";
		}
	if($rowdb2['PROJECTCODE']!=""){$project=$rowdb2['PROJECTCODE'];}else{$project=$rowdb2['ORIGDLVSALORDLINESALORDERCODE']; }
?>
	  <tr>
      <td style="text-align: center"><?php echo $no; ?></td>
      <td style="text-align: center"><?php echo $project;?></td>
      <td style="text-align: center"><?php echo $rowdb2['PRODUCTIONORDERCODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb2['PRODUCTIONDEMANDCODE'];?></td>
      <td><?php echo $rowdb2['LEGALNAME1'];?></td>
      <td style="text-align: center"><?php //if($rowdb2['SCHEDULEDRESOURCECODE']!=""){echo $rowdb2['SCHEDULEDRESOURCECODE'];}else{ echo $rowdb2['MC'];}
		  echo $rowdb2['MC']; ?></td>
      <td style="text-align: center"><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb2['BASEPRIMARYQUANTITY'],2),2);?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb22['JQTY'],2),2);?></td>
      <td style="text-align: center"><?php echo number_format(round($rowdb2['KG_M502'],2),2);?></td> 
      <td style="text-align: center"><?php echo number_format(round($rowdb2['KG_TR11'],2),2);?></td>
      <td style="text-align: center"><?php echo number_format(round($rowdb2['KG_M021'],2),2);?></td>
      <td style="text-align: center"><?php echo number_format(round($rowdb2['QTYOPIN'],2),2);?></td>
      <td style="text-align: center"><?php echo number_format(round($rowdb2['QTYOPOUT'],2),2);?></td>
      <td style="text-align: center"><?php echo $stts;?></td>
      <td style="text-align: center"><?php echo $rowdb2['TGLTUTUP'];?></td>
      </tr>				  
	<?php 
	 $no++;} ?>
				  </tbody>
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