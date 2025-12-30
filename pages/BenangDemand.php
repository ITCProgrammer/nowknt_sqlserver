<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Stts	= isset($_POST['stts']) ? $_POST['stts'] : '';
$no_project = isset($_POST['no_project']) ? $_POST['no_project'] : '';


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
               <label for="tgl_awal" class="col-md-1">Tgl Awal</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_awal" value="<?php echo $Awal;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>	
			   
			   
			    <label for="tgl_awal" class="col-md-1">No Project</label>
               <div class="col-md-2">  
                 <div class="input-group date"  data-target-input="nearest">
                  
                    <input name="no_project" value="<?php echo $no_project;?>" type="text" class="form-control form-control-sm" id=""  >
                 </div>
			   </div>
			   
			   
			   
            </div>
				 <div class="form-group row">
                    <label for="tgl_akhir" class="col-md-1">Tgl Akhir</label>
					<div class="col-md-2">  
                    <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm"  autocomplete="off" required>
                  </div>
					</div>	
                  </div>
			  <div class="form-group row">
                    <label for="tgl_akhir" class="col-md-1">Status</label>
				   <div class="col-md-2">
				    <select class="form-control form-control-sm" name="stts">
				  	<option value="6" <?php if($Stts=="6"){ echo "SELECTED"; } ?>>Selesai</option>
				  	<option value="2" <?php if($Stts=="2"){ echo "SELECTED"; } ?>>Sedang Jalan</option>	
				  </select>
                  </div>
			  </div>
			  <button class="btn btn-primary" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
		</form>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Benang Per Mesin</h3>
				<a href="javascript:void(0)" class="btn btn-sm btn-info float-right" onclick="cetak_cek()"> Cetak</a>  
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th>No</th>
					<th>Project Code</th>
                    <th>Tgl Selesai</th>
                    <th>Prod. Order</th>
                    <th>Demand</th>
                    <th>NoArt</th>
                    <th>Jenis Benang</th>
					 <th>Kode Benang</th>
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
	if( $Awal!="" and $Akhir!=""){	
		if ($Stts=="2"){
			$Tgl= " AND VARCHAR_FORMAT(i.FINALPLANNEDDATE,'YYYY-MM-DD') BETWEEN '$Awal' AND '$Akhir' " ;
		}else if ($Stts=="6"){
			$Tgl= " AND VARCHAR_FORMAT(i.FINALEFFECTIVEDATE,'YYYY-MM-DD') BETWEEN '$Awal' AND '$Akhir' " ;
		}	
	
	}else{
		$Tgl= " AND VARCHAR_FORMAT(i.FINALEFFECTIVEDATE,'YYYY-MM-DD') BETWEEN '2200-12-12' AND '2200-12-12' " ;
	}  
	
	if ($no_project !='') {
		$no_project= " AND (i.ORIGDLVSALORDLINESALORDERCODE = '$no_project' OR i.PROJECTCODE = '$no_project') ";
	} else {
		$no_project= "";
	}
	
$sqlDB2 =" SELECT  i.ORIGDLVSALORDLINESALORDERCODE, i.PRODUCTIONDEMANDCODE,i.PRODUCTIONORDERCODE,count(e.WEIGHTREALNET) AS INSROL,sum(e.WEIGHTREALNET) AS INSKG,
i.LEGALNAME1,i.SUBCODE01,i.SUBCODE02,i.SUBCODE03,i.SUBCODE04,i.PROJECTCODE,VARCHAR_FORMAT(i.FINALEFFECTIVEDATE,'YYYY-MM-DD') AS TGLSELESAI,
ad.VALUESTRING AS NO_MESIN, i.ORIGDLVSALORDLINESALORDERCODE
FROM ITXVIEWKNTORDER i 
LEFT OUTER JOIN STOCKTRANSACTION s ON s.PRODUCTIONORDERCODE =i.PRODUCTIONORDERCODE
LEFT OUTER JOIN ELEMENTSINSPECTION e ON e.DEMANDCODE =s.ORDERCODE AND e.ELEMENTCODE =s.ITEMELEMENTCODE
LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE = i.PRODUCTIONDEMANDCODE	
LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
WHERE  i.PROGRESSSTATUS='$Stts'  AND i.ITEMTYPEAFICODE ='KGF' $no_project $Tgl 
GROUP BY i.PRODUCTIONDEMANDCODE,i.FINALEFFECTIVEDATE,ad.VALUESTRING,  
i.LEGALNAME1,i.SUBCODE01,i.SUBCODE02,i.SUBCODE03,i.SUBCODE04,i.PROJECTCODE,i.PRODUCTIONORDERCODE, i.ORIGDLVSALORDLINESALORDERCODE ";
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
	GROUP BY STOCKTRANSACTION.ORDERCODE
	
	";
	 $stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	 $rowdb22 = db2_fetch_assoc($stmt2);
	 
	 $sqlDB23 = " SELECT FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,PRODUCTIONRESERVATION.SUBCODE01, PRODUCTIONRESERVATION.SUBCODE02, PRODUCTIONRESERVATION.SUBCODE03
	 , PRODUCTIONRESERVATION.SUBCODE04, PRODUCTIONRESERVATION.SUBCODE05, PRODUCTIONRESERVATION.SUBCODE06, PRODUCTIONRESERVATION.SUBCODE07, PRODUCTIONRESERVATION.SUBCODE08
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
	 $stmt3x   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
	 
	 $rowdb23 = db2_fetch_assoc($stmt3);
	 $rowdb23x = db2_fetch_assoc($stmt3x);
	 
	 
	 $sql=mysqli_query($con," SELECT sum(berat_awal) as berat_awal  FROM tbl_inspeksi_detail_now tidn WHERE tidn.demandno='$rowdb2[PRODUCTIONDEMANDCODE]' and tidn.ket_bs ='BS Mekanik'");
	 $rowd=mysqli_fetch_array($sql);
	 $sql1=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn WHERE tidn.demandno='$rowdb2[PRODUCTIONDEMANDCODE]' and tidn.ket_bs ='BS Produksi'");
	 $rowd1=mysqli_fetch_array($sql1);
	 $sql2=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn 
	 WHERE tidn.demandno='$rowdb2[PRODUCTIONDEMANDCODE]' and tidn.ket_bs ='BS Lain-lain'");
	 $rowd2=mysqli_fetch_array($sql2);
	 if(($rowdb21['KGPAKAI']-$rowdb22['KGSISA'])>0){
	 $prsn=round(($rowd['berat_awal']/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 $prsn1=round(($rowd1['berat_awal']/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 $prsn2=round(($rowd2['berat_awal']/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 }
	 $hslkg=$rowdb2['INSKG']+$rowdb22['KGSISA']+$rowd['berat_awal']+$rowd1['berat_awal']+$rowd2['berat_awal'];
	 $losskg=round($rowdb21['KGPAKAI']-$hslkg,2);
	 if(($rowdb21['KGPAKAI']-$rowdb22['KGSISA'])>0){
	 $prsnLoss=round(($losskg/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 }else{
	 $prsnLoss=0; 
	 }
	 $Thslkg=$losskg+$rowd['berat_awal']+$rowd1['berat_awal']+$rowd2['berat_awal'];
	 $Tlosskg=round($Thslkg,2);
	 if(($rowdb21['KGPAKAI']-$rowdb22['KGSISA'])>0){
	 $TprsnLoss=round(($Tlosskg/($rowdb21['KGPAKAI']-$rowdb22['KGSISA']))*100,2);
	 }else{
	 $TprsnLoss=0; 
	 }
	 
	   ?>
	  <tr>
      <td><?php echo $no; ?></td>
	     <td><?php if  ( $rowdb2['ORIGDLVSALORDLINESALORDERCODE']) { echo $rowdb2['ORIGDLVSALORDLINESALORDERCODE'];} else { echo  $rowdb2['PROJECTCODE'];} ?></td>
      <td><?php echo $rowdb2['TGLSELESAI']; ?></td>
      <td><?php echo $rowdb2['PRODUCTIONORDERCODE']; ?></td>
      <td><?php echo $rowdb2['PRODUCTIONDEMANDCODE']; ?></td>
      <td><?php echo trim($rowdb2['SUBCODE01'])." ".trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03']); ?></td>
      <td><?php $noBn=1; while ($rowdb23 = db2_fetch_assoc($stmt3)){echo $noBn.". ".$rowdb23['SUMMARIZEDDESCRIPTION']."<br>"; $noBn++;}?></td>
	    <td><?php $noBnx=1; while ($rowdb23x = db2_fetch_assoc($stmt3x)){
		  $pemisah = '-';
		  $subcode = $rowdb23x['SUBCODE01'].$pemisah.$rowdb23x['SUBCODE02'].$pemisah.$rowdb23x['SUBCODE03'].$pemisah.$rowdb23x['SUBCODE04'].$pemisah.$rowdb23x['SUBCODE05'].$pemisah.$rowdb23x['SUBCODE06'].$pemisah.$rowdb23x['SUBCODE07'].$pemisah.$rowdb23x['SUBCODE08'];
		  echo $noBnx.". ".$subcode."<br>"; $noBnx++;}?></td>
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
	function cetak_cek() {
    var awal = document.querySelector('input[name="tgl_awal"]').value;
    var akhir = document.querySelector('input[name="tgl_akhir"]').value;
    var status = document.querySelector('select[name="stts"]').value;

    if (awal != "" && akhir != "" && status != "") {
      let url = 'pages/cetak/cetak-benang-demand.php?awal=' + awal + '&akhir=' + akhir + '&status=' + status + '';
      // var newWindow = window.open();
      // newWindow.location.href = url;
      // newWindow.target = "_blank";

      window.open(url, '_blank').focus();
    } else {
      alert('Filter tidak boleh kosong!')
    }
  }
</script>