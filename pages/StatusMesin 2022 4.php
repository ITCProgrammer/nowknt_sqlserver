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
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">No MC</th>
                    <th style="text-align: center">KD</th>
                    <th style="text-align: center">Ukuran</th>
                    <th style="text-align: center">Catatan</th>
                    <th style="text-align: center">Project</th>
                    <th style="text-align: center">DemandNo</th>
                    <th style="text-align: center">Prod. Order</th>
                    <th style="text-align: center">Konsumen</th>
                    <th style="text-align: center">NoArt</th>
                    <th style="text-align: center">Total Rajut</th>
                    <th style="text-align: center">BS</th>
                    <th style="text-align: center">% BS</th>
                    <th style="text-align: center">STD Qty</th>
                    <th style="text-align: center">Sisa Stiker</th>
                    <th style="text-align: center">Total Kurang Rajut</th>
                    <th style="text-align: center">Tgl Delivery</th>
                    <th style="text-align: center">ProgressStatus</th>
                    <th style="text-align: center">Total Hari</th>
                    <th style="text-align: center">Delay</th>
                    <th style="text-align: center">Kebutuhan Greige</th>
                    <th style="text-align: center">Rencana Mulai</th>
                    <th style="text-align: center">Rencana Selesai</th>
                    <th style="text-align: center">Estimasi Selesai</th>
                    <th style="text-align: center">Tgl Mulai</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
		$sql=mysqli_query($con," SELECT * FROM tbl_mesin ORDER BY no_mesin ASC ");
   $no=1;   
   $c=0;
    while($rowd=mysqli_fetch_array($sql)){
$totHari="";
		
$sqlDB2 =" 
SELECT ADSTORAGE.VALUESTRING,AD1.VALUEDATE,AD2.VALUEDATE AS RMPREQDATE , ITXVIEWKNTORDER.*,CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWKNTORDER 
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME ='TglRencana'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME ='RMPReqDate'
WHERE ITXVIEWKNTORDER.ITEMTYPEAFICODE ='KGF' AND (SCHEDULEDRESOURCECODE ='$rowd[kd_dtex]' OR ADSTORAGE.VALUESTRING='$rowd[kd_dtex]') AND ITXVIEWKNTORDER.PROGRESSSTATUS='2' 
 ORDER BY ITXVIEWKNTORDER.INITIALSCHEDULEDACTUALDATE,AD1.VALUEDATE ASC
 ";
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
while($rowdb2 = db2_fetch_assoc($stmt)){
	
$sqlDB21 =" SELECT COUNT(BASEPRIMARYQUANTITY) AS JML, SUM(BASEPRIMARYQUANTITY) AS JQTY FROM 
VIEWPRODDEMANDELEMENTS WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND PROGRESSSTATUS='0'";	
$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
$rowdb21 = db2_fetch_assoc($stmt1);
	
$sqlDB22 =" SELECT COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF'";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);

$sqlDB23 =" SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT 
FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF' AND PRODUCT.SUBCODE02='$rowdb2[SUBCODE02]' AND PRODUCT.SUBCODE03='$rowdb2[SUBCODE03]'         
ORDER BY ADSTORAGE.FIELDNAME";	
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
GROUP BY INSPECTIONENDDATETIME ";	
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);

$sqlDB26 =" SELECT INSPECTIONENDDATETIME  FROM  
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' 
ORDER BY INSPECTIONENDDATETIME ASC LIMIT 1";	
$stmt6   = db2_exec($conn1,$sqlDB26, array('cursor'=>DB2_SCROLLABLE));
$rowdb26 = db2_fetch_assoc($stmt6);
		
$sqlDB27 =" SELECT LASTUPDATEDATETIME  FROM  
PRODUCTIONDEMAND WHERE CODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ITEMTYPEAFICODE='KGF' 
ORDER BY LASTUPDATEDATETIME ASC LIMIT 1";	
$stmt7   = db2_exec($conn1,$sqlDB27, array('cursor'=>DB2_SCROLLABLE));
$rowdb27 = db2_fetch_assoc($stmt7);

$sqlDB28 =" SELECT INSPECTIONSTARTDATETIME  FROM  
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' 
ORDER BY INSPECTIONSTARTDATETIME ASC LIMIT 1";	
$stmt8   = db2_exec($conn1,$sqlDB28, array('cursor'=>DB2_SCROLLABLE));
$rowdb28 = db2_fetch_assoc($stmt8);
	
$sqlDB29 =" SELECT PLANNEDOPERATIONCODE,PROGRESSSTATUS,LONGDESCRIPTION  FROM PRODUCTIONDEMANDSTEP 
WHERE PRODUCTIONDEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND PROGRESSSTATUS ='2' AND 
NOT (PLANNEDOPERATIONCODE='KNT1' OR PLANNEDOPERATIONCODE='INS1')
ORDER BY STEPNUMBER ASC ";	
$stmt9   = db2_exec($conn1,$sqlDB29, array('cursor'=>DB2_SCROLLABLE));
$rowdb29 = db2_fetch_assoc($stmt9);	
		
		$awalDY  = strtotime($rowdb2['TGLS']);
		$akhirDY = strtotime($rowdb2['RMPREQDATE']);
		$diffDY  = ($akhirDY - $awalDY);
		$tjamDY  = round($diffDY/(60 * 60),2);
		$hariDY  = round($tjamDY/24);
		
		$awalPR  = strtotime($rowdb2['TGLS']);
		$akhirPR = strtotime($rowdb25['INSPECTIONENDDATETIME']);
		$diffPR  = ($akhirPR - $awalPR);
		$tjamPR  = round($diffPR/(60 * 60),2);
		$hariPR  = round($tjamPR/24,2);
		
		$awalSJ  = strtotime($rowdb2['TGLS']);
		$akhirSJ = strtotime($rowdb26['INSPECTIONENDDATETIME']);
		$diffSJ  = ($akhirSJ - $awalSJ);
		$tjamSJ  = round($diffSJ/(60 * 60),2);
		$hariSJ  = round($tjamSJ/24,2);
		
		$awalPC  = strtotime($rowdb2['TGLS']);
		$akhirPC = strtotime($rowdb27['LASTUPDATEDATETIME']);
		$diffPC  = ($akhirPC - $awalPC);
		$tjamPC  = round($diffPC/(60 * 60),2);
		$hariPC = round($tjamPC/24,2);
		
		if($rowdb2['RMPREQDATE']!=""){
			$Delay=$hariDY;
		}else{
			$Delay="";
		}
		
		if($rowdb2['BASEPRIMARYQUANTITY']>0){
		$kRajut=round($rowdb2['BASEPRIMARYQUANTITY'],2)- round($rowdb22['JQTY'],2);
		$kHari=round($kRajut/round($rowdb23['STDRAJUT'],0),1);
		if($kHari>0){
			$tglEst=date('Y-m-d', strtotime($kHari, strtotime($rowdb2['TGLS'])));
			$tglRFin=date('Y-m-d', strtotime($kHari, strtotime($rowdb2['VALUEDATE'])));
		} else{
			$tglEst=date('Y-m-d', strtotime($rowdb2['TGLS']));
			$tglRFin=date('Y-m-d', strtotime($rowdb2['TGLS']));
		}	
			
		}else{
		$kRajut="0";
		$kHari="0";
		$tglEst="";	
		}
		
		if($rowdb2['PROGRESSSTATUS']=="2" and $rowdb25['JML']>"0" ){
			$stts="<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Perbaikan Mesin</small>";
			$totHari=abs($hariPR);
		}		
		elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb29['LONGDESCRIPTION']=="ANTRI MESIN" ){
			$stts="<small class='badge bg-yellow'><i class='far fa-clock text-white blink_me'></i> Antri Mesin</small>";
			$totHari=abs($hariPC);
		}		
		elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb29['LONGDESCRIPTION']=="Tunggu Setting" ){
			$stts="<small class='badge bg-blue'><i class='far fa-clock text-white blink_me'></i> Tunggu Setting</small>";
			$totHari=abs($hariPC);
		}		 
		elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb29['LONGDESCRIPTION']=="HABIS BENANG" ){
			$stts="<small class='badge bg-black'><i class='far fa-clock text-white blink_me'></i> Habis Benang</small>";
			$totHari=abs($hariPC);
		}		 
		elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb29['LONGDESCRIPTION']=="TUNGGU PASANG BENANG" ){
			$stts="<small class='badge bg-maroon'><i class='far fa-clock text-white blink_me'></i> Tunggu Pasang Benang</small>";
			$totHari=abs($hariPC);
		}		 
		elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb29['LONGDESCRIPTION']=="HOLD" ){
			$stts="<small class='badge bg-white'><i class='far fa-clock text-black blink_me'></i> Hold</small>";
			$totHari=abs($hariPC);	
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb29['LONGDESCRIPTION']!="" ){
			$stts="<small class='badge badge-info'><i class='far fa-clock text-white blink_me'></i> ".$rowdb29['LONGDESCRIPTION']." </small>";
			$totHari=abs($hariPC);	
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and ($rowdb24['IDS']=="0 ,0" or $rowdb24['IDS']=="0 ,0 ,0") ){
			$stts="<small class='badge badge-warning'><i class='far fa-clock text-white blink_me'></i> ProdOrdCreate</small>";
			$totHari=abs($hariPC);	
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb24['IDS']=="3 ,3" ){
			$stts="<small class='badge badge-danger'><i class='far fa-clock text-white blink_me'></i> Closed</small>";
			$totHari=abs($hariPC);	
		}else if($rowdb2['PROGRESSSTATUS']=="2" and ($rowdb24['IDS']=="2 ,0" or $rowdb24['IDS']=="0 ,2" or $rowdb24['IDS']=="2 ,2" or $rowdb24['IDS']=="2 ,3" ) ) {
			$stts="<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Sedang Jalan</small>";
			$totHari=abs($hariSJ);
		}else{
			$stts="Tidak Ada PO";
		}
		
	$sqlBS=mysqli_query($con," SELECT sum((berat_awal-berat)) as kg_bs,a.demandno
	 FROM tbl_inspeksi_now b
	 INNER JOIN  tbl_inspeksi_detail_now a ON b.id=a.id_inspeksi
	 WHERE
	 IF (
		   (berat_awal>berat),
		   'BS',''
	 )='BS' and a.demandno='$rowdb2[PRODUCTIONDEMANDCODE]'
	 group by a.demandno ");
	$rBS=mysqli_fetch_array($sqlBS);	
	if($rBS['kg_bs']>0 and $rowdb22['JQTY']>0){
		$perBS= round($rBS['kg_bs']/round($rowdb22['JQTY'],2)*100,2);
	}else{
		$perBS="0";
	}	
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $no; ?></td>
      <td style="text-align: center"><?php echo $rowd['no_mesin']; ?></td>
      <td style="text-align: center"><?php echo $rowd['kd_dtex']; ?></td>
      <td style="text-align: center"><?php echo $rowd['diameter_mesin']."''x".$rowd['gauge_mesin']."G";?></td>
      <td><?php echo $rowd['catatan'];?></td>
      <td style="text-align: center"><?php echo $rowdb2['PROJECTCODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb2['PRODUCTIONDEMANDCODE'];?></td> 
      <td><?php echo $rowdb2['PRODUCTIONORDERCODE'];?></td>
      <td><?php echo $rowdb2['LEGALNAME1'];?></td>
      <td style="text-align: center"><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?></td>
      <td style="text-align: right"><?php echo round($rowdb22['JQTY'],2);?></td>
      <td style="text-align: center"><?php echo $rBS['kg_bs']; ?></td>
      <td style="text-align: center"><?php echo $perBS; ?></td>
      <td style="text-align: center"><?php echo round($rowdb23['STDRAJUT'],0);?></td>
      <td style="text-align: center"><?php echo $rowdb21['JML']-$rowdb22['JML'];?></td>
      <td style="text-align: right"><?php echo round($kRajut,2);?></td>
      <td style="text-align: center"><?php echo $rowdb2['RMPREQDATE'];?></td>
      <td style="text-align: center"><?php echo $stts;?></td>
      <td style="text-align: center"><?php echo $totHari; ?></td>
      <td style="text-align: center"><?php echo $Delay;?></td>
	  <td style="text-align: right"><?php echo round($kRajut,2);?></td>	  
      <td style="text-align: center"><?php if($rowdb2['INITIALSCHEDULEDACTUALDATE']!=""){echo $rowdb2['INITIALSCHEDULEDACTUALDATE'];}else{ echo $rowdb2['VALUEDATE'];}?></td>
      <td style="text-align: center"><?php if($rowdb2['FINALSCHEDULEDACTUALDATE']!=""){echo $rowdb2['FINALSCHEDULEDACTUALDATE'];}else{ if($tglRFin=="1970-01-01"){}else{echo $tglRFin;}}?></td>
      <td style="text-align: center"><?php if($tglEst=="1970-01-01"){}else{echo $tglEst;}?></td>
      <td style="text-align: center"><?php echo substr($rowdb28['INSPECTIONSTARTDATETIME'],0,10); ?></td>
      </tr>				  
	<?php 
	 $no++;
	} ?>
					  
		
<?php } ?>
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