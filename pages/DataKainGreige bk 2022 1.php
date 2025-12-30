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
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
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
                    <th style="text-align: center">Siap Kirim</th>
                    <th style="text-align: center">Terkirim</th>
                    <th style="text-align: center">Status PO</th>
                    <th style="text-align: center">Tgl Selesai</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
$sqlDB2 =" SELECT *,CURRENT_TIMESTAMP AS TGLS,VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') AS TGLTUTUP
FROM ITXVIEWKNTORDER 
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
WHERE ITXVIEWKNTORDER.ITEMTYPEAFICODE ='KGF' AND (ITXVIEWKNTORDER.PROGRESSSTATUS='2' OR ITXVIEWKNTORDER.PROGRESSSTATUS='6')
AND ADD_DAYS(CASE WHEN VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') IS NULL THEN
VARCHAR_FORMAT(CURRENT_TIMESTAMP,'YYYY-MM-DD') ELSE VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') END,7) > CURRENT_TIMESTAMP ";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$no=1;
while($rowdb2 = db2_fetch_assoc($stmt)){

$sqlDB22 =" SELECT COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF'";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);

$sqlDB23 =" SELECT SUM(il.SHIPPEDBASEPRIMARYQUANTITY) AS SHIPPEDBASEPRIMARYQUANTITY,
SUM(il.RECEIVEDBASEPRIMARYQUANTITY) AS RECEIVEDBASEPRIMARYQUANTITY FROM INTERNALDOCUMENT i LEFT JOIN
INTERNALDOCUMENTLINE il ON i.PROVISIONALCODE=il.INTDOCUMENTPROVISIONALCODE AND i.PROVISIONALCOUNTERCODE=il.INTDOCPROVISIONALCOUNTERCODE  
WHERE il.PROJECTCODE ='$rowdb2[PROJECTCODE]' AND
il.ITEMTYPEAFICODE='KGF' AND
il.EXTERNALREFERENCE='$rowdb2[PRODUCTIONORDERCODE]' AND
il.SUBCODE01='$rowdb2[SUBCODE01]' AND
il.SUBCODE02='$rowdb2[SUBCODE02]' AND
il.SUBCODE03='$rowdb2[SUBCODE03]' AND
il.SUBCODE04='$rowdb2[SUBCODE04]'
";	
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);
	
$sqlDB24 =" SELECT 
trim(LISTAGG (PROGRESSSTATUS , ',') WITHIN GROUP(ORDER BY PRODUCTIONDEMANDCODE ASC)) as IDS	
FROM PRODUCTIONDEMANDSTEP 
WHERE PRODUCTIONDEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND (OPERATIONCODE='INS1' OR OPERATIONCODE='KNT1')
GROUP BY PRODUCTIONDEMANDCODE ";	
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);

$sqlDB25 =" SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM'
GROUP BY INSPECTIONENDDATETIME";	
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);
	
if($rowdb2['PROGRESSSTATUS']=="2" and $rowdb25['JML']>"0" ){
			$stts="<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Perbaikan Mesin</small>";
			$totHari=abs($hariPR);
		}
		elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb24['IDS']=="0 ,0" ){
			$stts="<small class='badge badge-warning'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
			$totHari=abs($hariPC);
		}else if($rowdb2['PROGRESSSTATUS']=="2" and ($rowdb24['IDS']=="2 ,0" or $rowdb24['IDS']=="0 ,2" or $rowdb24['IDS']=="2 ,2") ) {
			$stts="<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
			$totHari=abs($hariSJ);
		}else{
			$stts="Tidak Ada PO";
		}	
?>
	  <tr>
      <td style="text-align: center"><?php echo $no; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['PROJECTCODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb2['PRODUCTIONORDERCODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb2['PRODUCTIONDEMANDCODE'];?></td>
      <td><?php echo $rowdb2['LEGALNAME1'];?></td>
      <td style="text-align: center"><?php if($rowdb2['SCHEDULEDRESOURCECODE']!=""){echo $rowdb2['SCHEDULEDRESOURCECODE'];}else{ echo $rowdb2['VALUESTRING'];}?></td>
      <td style="text-align: center"><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb2['BASEPRIMARYQUANTITY'],2),2);?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb22['JQTY'],2),2);?></td>
      <td style="text-align: center"><?php echo number_format(round($rowdb23['SHIPPEDBASEPRIMARYQUANTITY']-$rowdb23['RECEIVEDBASEPRIMARYQUANTITY'],2),2);?></td> 
      <td style="text-align: center"><?php echo number_format(round($rowdb23['RECEIVEDBASEPRIMARYQUANTITY'],2),2);?></td>
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