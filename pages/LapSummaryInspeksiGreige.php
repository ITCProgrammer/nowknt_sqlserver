<?php
$Awal		= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir		= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Shift		= isset($_POST['shift']) ? $_POST['shift'] : '';
$QReason	= isset($_POST['q_reason']) ? $_POST['q_reason'] : '';
$DemandNo	= isset($_POST['demandno']) ? $_POST['demandno'] : '';
?>
	<script src="plugins/chart.js/chart371.js"></script>
    <script src="plugins/chart.js/chartjs-plugin-datalabels.js"></script>
<!-- Main content -->

      <div class="container-fluid">
		  <div class="row">
          <div class="col-md-4">
            <!-- AREA CHART -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">5 TOP Jarum Minyak Tebal</h3>

                <div class="card-tools">
<!--
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
-->
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="stackedChart1" style="width: 100%; height: 400px;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
		</div>
          <!-- /.col (LEFT) --> 
		  <div class="col-md-4">
            <!-- AREA CHART -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">5 TOP Horizon</h3>

                <div class="card-tools">
<!--
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
-->
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="stackedChart2" style="width: 100%; height: 400px;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
		</div>	
		  <!-- /.col (LEFT) --> 
		  <div class="col-md-4">
            <!-- AREA CHART -->
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">5 TOP Vertikal</h3>

                <div class="card-tools">
<!--
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
-->
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="stackedChart3" style="width: 100%; height: 400px;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
		</div>	
        </div>
		<form role="form" method="post" enctype="multipart/form-data" name="form1"> 
		<div class="row">
		<div class="col-md-3">	
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Inspeksi Greige</h3>

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
               <label for="tgl_awal" class="col-md-6">Tgl Awal</label>
               <div class="col-md-6">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_awal" value="<?php echo $Awal;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" >
                 </div>
			   </div>	
            </div>
			 <div class="form-group row">
               <label for="tgl_akhir" class="col-md-6">Tgl Akhir</label>
               <div class="col-md-6">  
                 <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" >
                 </div>
			   </div>	
            </div>
			<div class="form-group row">
               <label for="shift" class="col-md-6">Shift</label>
               <div class="col-md-6">  
                 <select name="shift" class="form-control form-control-sm" id="shift">
				   <option value="">Pilih</option>
				   <option value="ALL" <?php if($Shift=="ALL"){echo "SELECTED";} ?>>ALL</option>	 
				   <option value="1" <?php if($Shift=="1"){echo "SELECTED";} ?>>1</option>
				   <option value="2" <?php if($Shift=="2"){echo "SELECTED";} ?>>2</option>
				   <option value="3" <?php if($Shift=="3"){echo "SELECTED";} ?>>3</option>	 
				   </select>
			   </div>	
            </div>  
			<div class="form-group row">
               <label for="demandno" class="col-md-6">DemandNo</label>
               <div class="col-md-6">  
                  <input name="demandno" value="<?php echo $DemandNo;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" >
			   </div>	
            </div>  
			<div class="form-group row">
               <label for="q_reason" class="col-md-6">Quality Reason</label>
               <div class="col-md-6">  
                 <select name="q_reason" class="form-control form-control-sm" id="q_reason" required>
				   <option value="ALL" <?php if($QReason=="ALL"){echo "SELECTED";} ?>>ALL</option>	 
				   <option value="ada" <?php if($QReason=="ada"){echo "SELECTED";} ?>>Ada</option>	 
				   <option value="tidak" <?php if($QReason=="tidak"){echo "SELECTED";} ?>>Tidak</option>
				   </select>
			   </div>	
            </div>	 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
		</div>	
		<div class="col-md-9">
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Detail Quality Reason</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example14" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">CODE</th>
                    <th valign="middle" style="text-align: center">LONGDESCRIPTION</th>
                    <th valign="middle" style="text-align: center">SHORTDESCRIPTION</th>
                    <th valign="middle" style="text-align: center">SEARCHDESCRIPTION</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
$no=1;   
$c=0;			  
	$sqlDB2df = " SELECT * FROM DB2ADMIN.QUALITYREASON WHERE CODE IN ('V00', 'V02', 'V03', 'H00', 'H02', 'H03', 'J00', 'J02') ";
	$stmtdf   = db2_exec($conn1,$sqlDB2df, array('cursor'=>DB2_SCROLLABLE));
	
    while($rowdb2df = db2_fetch_assoc($stmtdf)){ 
	
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb2df['CODE']; ?></td>
      <td style="text-align: center"><?php  echo $rowdb2df['LONGDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php  echo $rowdb2df['SHORTDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php  echo $rowdb2df['SEARCHDESCRIPTION']; ?></td>
      </tr>				  
	<?php 
	 $no++; } ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
		</div>	
		</div>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kain Greige</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example12" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">TGL</th>
                    <th valign="middle" style="text-align: center">Nama</th>
                    <th valign="middle" style="text-align: center">Shift</th>
                    <th valign="middle" style="text-align: center">DemandNo</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">No Mesin</th>
                    <th valign="middle" style="text-align: center">ElementCode</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Yards</th>
                    <th valign="middle" style="text-align: center">Defect</th>
                    <th valign="middle" style="text-align: center">Total Poin 100 Y</th>
                    <th valign="middle" style="text-align: center">Grade</th>
                    <th valign="middle" style="text-align: center">Q Reason</th>
                    <th valign="middle" style="text-align: center">Note</th>
                    <th valign="middle" style="text-align: center">Disposisi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
$no=1;   
$c=0;
if($QReason=="ada"){ 
//$WQreason=" AND (NOT QUALITYREASONCODE IS NULL AND NOT QUALITYREASONCODE='100') ";
$WQreason=" AND (NOT QUALITYREASONCODE IS NULL) ";
}
else if($QReason=="tidak"){
$WQreason=" AND (QUALITYREASONCODE IS NULL OR QUALITYREASONCODE='100') ";	
}else{
$WQreason="";	
}	
if($Awal!="" and $Akhir!=""){
$WTgl=" AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) BETWEEN '$Awal' AND '$Akhir' ";	
if($Shift=="1"){ $WTgl=" AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-07:00:00' AND '$Akhir-15:00:00' "; }
elseif($Shift=="2"){ $WTgl=" AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-15:00:00' AND '$Akhir-23:00:00' "; }
elseif($Shift=="3"){ $WTgl=" AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-23:00:00' AND '$Akhir-07:00:00' " ; }
elseif($Shift=="ALL"){ $WTgl=" AND INSPECTIONSTARTDATETIME BETWEEN '$Awal-07:00:00' AND '$Akhir-07:00:00' " ; }
elseif($Shift==""){ $WTgl=" AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) BETWEEN '2021-01-01' AND '2021-01-01' " ; }		
}else{
$WTgl="";
$WTgl1=" AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) BETWEEN '2021-01-01' AND '2021-01-01' " ;	
}		
					  
if($DemandNo!=""){ $WDemand=" AND DEMANDCODE LIKE '%$DemandNo%' ";}else{ $WDemand="";}	
if($WTgl=="" and $WDemand==""){
$Where = " AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) BETWEEN '$Awal' AND '$Akhir' ";	
}else{
$Where = "";	
}
					  
					  
	$sqlDB21 = " SELECT OPERATORCODE, INITIALS.LONGDESCRIPTION, SUBSTR(INSPECTIONSTARTDATETIME,1,10) AS TGL, SUBSTR(INSPECTIONSTARTDATETIME,12,8) AS JAM, 
	DEMANDCODE, COUNT(DEMANDCODE) AS JML,SUM(WEIGHTNET) AS KGS,ADSTORAGE.VALUESTRING AS NO_MESIN,SUBSTR(ELEMENTCODE,9,5) AS NROL,ELEMENTCODE, ELEMENTSINSPECTION.QUALITYCODE,QUALITYREASONCODE, LENGTHGROSS, TOTALPOINTS,A1.VALUESTRING AS NOTE,A2.VALUESTRING AS DISPOSISI  
	FROM ELEMENTSINSPECTION
LEFT OUTER JOIN DB2ADMIN.INITIALS ON INITIALS.CODE=ELEMENTSINSPECTION.OPERATORCODE	
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE A1 ON A1.UNIQUEID = ELEMENTSINSPECTION.ABSUNIQUEID AND A1.NAMENAME ='Note'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE A2 ON A2.UNIQUEID = ELEMENTSINSPECTION.ABSUNIQUEID AND A2.NAMENAME ='Disposisi'
	WHERE ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE  IN ('V00', 'V02', 'V03', 'H00', 'H02', 'H03', 'J00', 'J02') $Where $WTgl $WQreason $WDemand
GROUP BY SUBSTR(INSPECTIONSTARTDATETIME,1,10),SUBSTR(INSPECTIONSTARTDATETIME,12,8),DEMANDCODE,OPERATORCODE,
INITIALS.LONGDESCRIPTION,ADSTORAGE.VALUESTRING,ELEMENTCODE, QUALITYREASONCODE, ELEMENTSINSPECTION.QUALITYCODE, LENGTHGROSS, TOTALPOINTS,A1.VALUESTRING,A2.VALUESTRING ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}	
	$McNo="";
	$grd="";
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
if (trim($rowdb21['LOGICALWAREHOUSECODE']) =='M904') { $knitt = 'LT2'; }
else if(trim($rowdb21['LOGICALWAREHOUSECODE']) =='P501'){ $knitt = 'LT1'; }
$TP100 =	round(($rowdb21['TOTALPOINTS']/$rowdb21['LENGTHGROSS'])*100);	
if($TP100 >= 0 and $TP100 <= 15){ $grd="A"; }
elseif($TP100 > 15 and $TP100 <= 20){ $grd="B"; }	
elseif($TP100 > 20){ $grd="C"; }
else{ $grd=""; }
if($rowdb21['KGS']>0){
	//$x=(($lebar+2)*$berat)/43.05;
	//$x1=(1000/$x);
	//$yards=$x1*$rowdb21['KGS'];
}else{
	$yards=0;
}		
$sqlDB22 = " SELECT TRANSACTIONDATE,TRANSACTIONTIME FROM STOCKTRANSACTION WHERE STOCKTRANSACTION.ITEMTYPECODE ='GYR' and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
STOCKTRANSACTION.RETURNTRANSACTION ='1' AND STOCKTRANSACTION.TRANSACTIONDATE='$rowdb21[TRANSACTIONDATE]' 
AND STOCKTRANSACTION.ORDERCODE='$rowdb21[ORDERCODE]' AND STOCKTRANSACTION.CREATIONUSER='$rowdb21[CREATIONUSER]' ";
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);		
if($rowdb21['JAM']>="07.00.00" and $rowdb21['JAM']<="15.00.00"){
	$shf="1";
}elseif($rowdb21['JAM']>="15.00.00" and $rowdb21['JAM']<="23.00.00"){
	$shf="2";
}else{
	$shf="3";
}		
$sqlDB23 =" SELECT *,CURRENT_TIMESTAMP AS TGLS,
CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END  AS PROJECT FROM ITXVIEWHEADERKNTORDER WHERE ITEMTYPEAFICODE ='KGF' AND CODE ='".$rowdb21['DEMANDCODE']."' ";	
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);
$kdkain=trim($rowdb23['SUBCODE02'])."".trim($rowdb23['SUBCODE03'])." ".trim($rowdb23['SUBCODE04']);	
$McNo=$rowdb21['NO_MESIN']; 		
$sqlKt=mysqli_query($con," SELECT no_mesin FROM tbl_mesin WHERE kd_dtex='".$McNo."' LIMIT 1");
$rk=mysqli_fetch_array($sqlKt);	
if($rowdb21['LONGDESCRIPTION']!=""){$uid=trim($rowdb21['LONGDESCRIPTION']);}else{$uid=trim($rowdb21['CREATIONUSER']);}	
$sqlDB24 =" SELECT LISTAGG(TRIM(e.CODEEVENTCODE),', ') AS CODEEVENTCODE 
FROM ELEMENTSINSPECTIONEVENT e WHERE ELEMENTSINSPECTIONELEMENTCODE ='".$rowdb21['ELEMENTCODE']."' ";	
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TGL']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['LONGDESCRIPTION']; ?></td>
	  <td style="text-align: center"><?php echo $shf;?></td>
      <td style="text-align: center"><?php  echo $rowdb21['DEMANDCODE']; ?></td>
      <td><?php echo $kdkain; ?></td>
      <td><?php  echo $rk['no_mesin']; ?></td>
      <td style="text-align: center"><?php  echo $rowdb21['ELEMENTCODE']; ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['KGS'],2),2); ?></td>
      <td style="text-align: right"><?php echo $rowdb21['LENGTHGROSS']; ?></td>
      <td style="text-align: center"><?php  echo $rowdb24['CODEEVENTCODE']; ?></td>
      <td style="text-align: center"><?php echo $TP100; ?></td>
      <td style="text-align: center"><?php echo $grd; ?></td>
      <td style="text-align: center"><?php  echo $rowdb21['QUALITYREASONCODE']; ?></td>
      <td style="text-align: left"><?php  echo $rowdb21['NOTE']; ?></td> 
      <td style="text-align: left"><?php  echo $rowdb21['DISPOSISI']; ?></td>
      </tr>				  
	<?php 
	 $no++; } ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
		<div class="card card-warning" hidden>
              <div class="card-header">
                <h3 class="card-title">Summary Data Kain Greige Jarum Minyak Tebal</h3>				 
          </div>
              <!-- /.card-header -->
    <div class="card-body">
    <table id="example13" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">No Mesin</th>
                <?php
                $start_date = new DateTime($Awal);
                $end_date = new DateTime($Akhir);
                $end_date->modify('+1 day'); // Tambah 1 hari agar mencakup tanggal akhir

                // Array untuk menyimpan total per tanggal
                $totalPerTanggal = [];

                for ($date = clone $start_date; $date < $end_date; $date->modify('+1 day')) {
                    echo "<th style='text-align: center'>" . $date->format('Y-m-d') . "</th>";
                    $totalPerTanggal[$date->format('Y-m-d')] = 0; // Inisialisasi array
                }
                ?>
                <th style="text-align: center">Total</th>	
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $grdtotal = 0;

            $sqlDB21 = "SELECT ADSTORAGE.VALUESTRING AS NO_MESIN  
                FROM ELEMENTSINSPECTION
                LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE    
                LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID 
                    AND ADSTORAGE.NAMENAME = 'MachineNo'
                WHERE ELEMENTITEMTYPECODE = 'KGF' 
                    AND QUALITYREASONCODE IN ('V00', 'V02', 'V03', 'H00', 'H02', 'H03', 'J00', 'J02') 
                    $WTgl $WTgl1 $WQreason $WDemand
                GROUP BY ADSTORAGE.VALUESTRING";

            $stmt1 = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));

            while ($rowdb21 = db2_fetch_assoc($stmt1)) {
                $McNo = $rowdb21['NO_MESIN'];
                $sqlKt = mysqli_query($con, "SELECT no_mesin FROM tbl_mesin WHERE kd_dtex = '$McNo' LIMIT 1");
                $rk = mysqli_fetch_array($sqlKt);
                ?>
                <tr>
                    <td style="text-align: center"><?php echo $no; ?></td>
                    <td><?php echo $rk['no_mesin']; ?></td>
                    <?php
                    $totalMesin = 0;

                    for ($date = clone $start_date; $date < $end_date; $date->modify('+1 day')) {
                        $tanggal = $date->format('Y-m-d');

                        $sqlDB21JML = "SELECT COUNT(ELEMENTCODE) AS JML
                            FROM ELEMENTSINSPECTION	
                            LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE	
                            LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
                            WHERE ELEMENTITEMTYPECODE='KGF' 
                                AND QUALITYREASONCODE IN ('V00', 'V02', 'V03', 'H00', 'H02', 'H03', 'J00', 'J02') 
                                AND (NOT QUALITYREASONCODE IS NULL) 
                                AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) ='$tanggal' 
                                AND ADSTORAGE.VALUESTRING = '$McNo'
								$WTgl
                            GROUP BY ADSTORAGE.VALUESTRING";

                        $stmt1JML = db2_exec($conn1, $sqlDB21JML, array('cursor' => DB2_SCROLLABLE));
                        $rowdb21JML = db2_fetch_assoc($stmt1JML);
                        $jml = empty($rowdb21JML['JML']) ? 0 : $rowdb21JML['JML'];

                        echo "<td>$jml</td>";

                        // Menjumlahkan total per mesin dan per tanggal
                        $totalMesin += $jml;
                        $totalPerTanggal[$tanggal] += $jml;
                    }
                    ?>
                    <td><?php echo $totalMesin; ?></td>
                </tr>
                <?php
                $grdtotal += $totalMesin;
                $no++;
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: center">&nbsp;</th>
                <th style="text-align: center">Total</th>
                <?php
                for ($date = clone $start_date; $date < $end_date; $date->modify('+1 day')) {
                    $tanggal = $date->format('Y-m-d');
                    echo "<th style='text-align: center'>" . $totalPerTanggal[$tanggal] . "</th>";
                }
                ?>
                <th style="text-align: center"><?php echo $grdtotal; ?></th>
            </tr>
        </tfoot>
    </table>
</div>



              <!-- /.card-body -->
</div>
		<div class="card card-warning" hidden>
              <div class="card-header">
                <h3 class="card-title">Summary 5 TOP Data Kain Greige Jarum Minyak Tebal</h3>				 
          </div>
              <!-- /.card-header -->
    <div class="card-body">
    <table id="example14" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">No Mesin</th>
                <?php
                $start_date = new DateTime($Awal);
                $end_date = new DateTime($Akhir);
                $end_date->modify('+1 day'); // Tambah 1 hari agar mencakup tanggal akhir

                // Array untuk menyimpan total per tanggal
                $totalPerTanggal = [];

                for ($date = clone $start_date; $date < $end_date; $date->modify('+1 day')) {
                    echo "<th style='text-align: center'>" . $date->format('Y-m-d') . "</th>";
                    $totalPerTanggal[$date->format('Y-m-d')] = 0; // Inisialisasi array
                }
                ?>
                <th style="text-align: center">Total</th>	
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $grdtotal = 0;

            $sqlDB21 = "SELECT 
					COUNT(ADSTORAGE.VALUESTRING) AS JML, 
					ADSTORAGE.VALUESTRING AS NO_MESIN
				FROM 
					ELEMENTSINSPECTION
				LEFT OUTER JOIN 
					DB2ADMIN.INITIALS 
					ON INITIALS.CODE = ELEMENTSINSPECTION.OPERATORCODE
				LEFT OUTER JOIN 
					DB2ADMIN.PRODUCTIONDEMAND 
					ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE
				LEFT OUTER JOIN 
					DB2ADMIN.ADSTORAGE 
					ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID 
					AND ADSTORAGE.NAMENAME = 'MachineNo'
				LEFT OUTER JOIN 
					DB2ADMIN.ADSTORAGE A1 
					ON A1.UNIQUEID = ELEMENTSINSPECTION.ABSUNIQUEID 
					AND A1.NAMENAME = 'Note'
				LEFT OUTER JOIN 
					DB2ADMIN.ADSTORAGE A2 
					ON A2.UNIQUEID = ELEMENTSINSPECTION.ABSUNIQUEID 
					AND A2.NAMENAME = 'Disposisi'
				WHERE 
					ELEMENTITEMTYPECODE = 'KGF' 
					AND QUALITYREASONCODE IN ('V00', 'V02', 'V03', 'H00', 'H02', 'H03', 'J00', 'J02')  
                    $WTgl $WTgl1 $WQreason $WDemand
                GROUP BY 
					ADSTORAGE.VALUESTRING
				ORDER BY 
					JML DESC
				FETCH FIRST 5 ROWS ONLY";

            $stmt1 = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));

            while ($rowdb21 = db2_fetch_assoc($stmt1)) {
                $McNo = $rowdb21['NO_MESIN'];
                $sqlKt = mysqli_query($con, "SELECT no_mesin FROM tbl_mesin WHERE kd_dtex = '$McNo' LIMIT 1");
                $rk = mysqli_fetch_array($sqlKt);
                ?>
                <tr>
                    <td style="text-align: center"><?php echo $no; ?></td>
                    <td><?php echo $rk['no_mesin']; ?></td>
                    <?php
                    $totalMesin = 0;

                    for ($date = clone $start_date; $date < $end_date; $date->modify('+1 day')) {
                        $tanggal = $date->format('Y-m-d');

                        $sqlDB21JML = "SELECT COUNT(ELEMENTCODE) AS JML
                            FROM ELEMENTSINSPECTION	
                            LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE	
                            LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
                            WHERE ELEMENTITEMTYPECODE='KGF' 
                                AND QUALITYREASONCODE IN ('V00', 'V02', 'V03', 'H00', 'H02', 'H03', 'J00', 'J02') 
                                AND (NOT QUALITYREASONCODE IS NULL) 
                                AND SUBSTR(INSPECTIONSTARTDATETIME,1,10) ='$tanggal' 
                                AND ADSTORAGE.VALUESTRING = '$McNo'
								$WTgl
                            GROUP BY ADSTORAGE.VALUESTRING";

                        $stmt1JML = db2_exec($conn1, $sqlDB21JML, array('cursor' => DB2_SCROLLABLE));
                        $rowdb21JML = db2_fetch_assoc($stmt1JML);
                        $jml = empty($rowdb21JML['JML']) ? 0 : $rowdb21JML['JML'];

                        echo "<td>$jml</td>";

                        // Menjumlahkan total per mesin dan per tanggal
                        $totalMesin += $jml;
                        $totalPerTanggal[$tanggal] += $jml;
                    }
                    ?>
                    <td><?php echo $totalMesin; ?></td>
                </tr>
                <?php
                $grdtotal += $totalMesin;
                $no++;
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: center">&nbsp;</th>
                <th style="text-align: center">Total</th>
                <?php
                for ($date = clone $start_date; $date < $end_date; $date->modify('+1 day')) {
                    $tanggal = $date->format('Y-m-d');
                    echo "<th style='text-align: center'>" . $totalPerTanggal[$tanggal] . "</th>";
                }
                ?>
                <th style="text-align: center"><?php echo $grdtotal; ?></th>
            </tr>
        </tfoot>
    </table>
</div>



              <!-- /.card-body -->
</div>
			<!-- /.container-fluid -->
<div id="DetailTurunanShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
    <!-- /.content -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>		  
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>		  
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
			
<!--<script src="dist/js/demo.js"></script>		  -->

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
<script>
        // URL API
        const apiUrl1 = 'https://online.indotaichen.com/NOWknt/pages/api/api_mesin_top5AJ.php';

        // Fungsi untuk mengambil data dari API
        async function fetchData() {
            try {
                const response = await fetch(apiUrl1);
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching data:', error);
                return [];
            }
        }

        // Fungsi untuk mengonversi data API ke format Chart.js
        function convertData(apiData) {
            // Ambil daftar tanggal dari kunci objek pertama (kecuali NO_MESIN & TOTAL)
            const dates = Object.keys(apiData[0]).filter(key => key !== 'NO_MESIN' && key !== 'TOTAL');

            // Warna untuk setiap mesin (bisa disesuaikan)
            const colors = ['#e67e22', '#3498db', '#1f497d', '#8e44ad', '#2c7b4f'];

            // Ubah data API menjadi format Chart.js
            const datasets = apiData.map((item, index) => ({
                label: item.NO_MESIN,
                data: dates.map(date => item[date]), // Ambil data untuk tiap tanggal
                backgroundColor: colors[index % colors.length]
            }));

            return { dates, datasets };
        }

        // Fungsi untuk membuat chart
        async function createChart() {
            const apiData = await fetchData();
            const { dates, datasets } = convertData(apiData);

            const ctx = document.getElementById('stackedChart1').getContext('2d');
            Chart.register(ChartDataLabels);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates,  // Label sumbu X (tanggal)
                    datasets: datasets // Data sumbu Y (jumlah produksi per mesin)
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        datalabels: {
                            anchor: 'center',
                            align: 'center',
                            color: '#fff',
                            font: { weight: 'bold' },
                            formatter: (value) => value > 0 ? value : ''
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        }

        // Panggil fungsi untuk membuat chart saat halaman dimuat
        createChart();
    </script>			
	<script>
        // URL API
        const apiUrl2 = 'https://online.indotaichen.com/NOWknt/pages/api/api_mesin_top5AH.php';

        // Fungsi untuk mengambil data dari API
        async function fetchData() {
            try {
                const response = await fetch(apiUrl2);
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching data:', error);
                return [];
            }
        }

        // Fungsi untuk mengonversi data API ke format Chart.js
        function convertData(apiData) {
            // Ambil daftar tanggal dari kunci objek pertama (kecuali NO_MESIN & TOTAL)
            const dates = Object.keys(apiData[0]).filter(key => key !== 'NO_MESIN' && key !== 'TOTAL');

            // Warna untuk setiap mesin (bisa disesuaikan)
            const colors = ['#e67e22', '#3498db', '#1f497d', '#8e44ad', '#2c7b4f'];

            // Ubah data API menjadi format Chart.js
            const datasets = apiData.map((item, index) => ({
                label: item.NO_MESIN,
                data: dates.map(date => item[date]), // Ambil data untuk tiap tanggal
                backgroundColor: colors[index % colors.length]
            }));

            return { dates, datasets };
        }

        // Fungsi untuk membuat chart
        async function createChart() {
            const apiData = await fetchData();
            const { dates, datasets } = convertData(apiData);

            const ctx = document.getElementById('stackedChart2').getContext('2d');
            Chart.register(ChartDataLabels);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates,  // Label sumbu X (tanggal)
                    datasets: datasets // Data sumbu Y (jumlah produksi per mesin)
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        datalabels: {
                            anchor: 'center',
                            align: 'center',
                            color: '#fff',
                            font: { weight: 'bold' },
                            formatter: (value) => value > 0 ? value : ''
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        }

        // Panggil fungsi untuk membuat chart saat halaman dimuat
        createChart();
    </script>	  
	<script>
        // URL API
        const apiUrl3 = 'https://online.indotaichen.com/NOWknt/pages/api/api_mesin_top5AV.php';

        // Fungsi untuk mengambil data dari API
        async function fetchData() {
            try {
                const response = await fetch(apiUrl3);
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching data:', error);
                return [];
            }
        }

        // Fungsi untuk mengonversi data API ke format Chart.js
        function convertData(apiData) {
            // Ambil daftar tanggal dari kunci objek pertama (kecuali NO_MESIN & TOTAL)
            const dates = Object.keys(apiData[0]).filter(key => key !== 'NO_MESIN' && key !== 'TOTAL');

            // Warna untuk setiap mesin (bisa disesuaikan)
            const colors = ['#e67e22', '#3498db', '#1f497d', '#8e44ad', '#2c7b4f'];

            // Ubah data API menjadi format Chart.js
            const datasets = apiData.map((item, index) => ({
                label: item.NO_MESIN,
                data: dates.map(date => item[date]), // Ambil data untuk tiap tanggal
                backgroundColor: colors[index % colors.length]
            }));

            return { dates, datasets };
        }

        // Fungsi untuk membuat chart
        async function createChart() {
            const apiData = await fetchData();
            const { dates, datasets } = convertData(apiData);

            const ctx = document.getElementById('stackedChart3').getContext('2d');
            Chart.register(ChartDataLabels);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates,  // Label sumbu X (tanggal)
                    datasets: datasets // Data sumbu Y (jumlah produksi per mesin)
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        datalabels: {
                            anchor: 'center',
                            align: 'center',
                            color: '#fff',
                            font: { weight: 'bold' },
                            formatter: (value) => value > 0 ? value : ''
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        }

        // Panggil fungsi untuk membuat chart saat halaman dimuat
        createChart();
    </script>