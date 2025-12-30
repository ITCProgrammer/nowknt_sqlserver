<?php
$Awal		= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir		= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Shift		= isset($_POST['shift']) ? $_POST['shift'] : '';
$QReason	= isset($_POST['q_reason']) ? $_POST['q_reason'] : '';
$DemandNo	= isset($_POST['demandno']) ? $_POST['demandno'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1"> 
		<div class="row">
		<div class="col-md-3">	
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Benang</h3>

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
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
		</div>	
			
		</div>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Benang OPR</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example12" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;" width="100%">
                  <thead>
                  <tr>
                    <th rowspan="2" valign="middle" style="text-align: center">No</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Nama</th>
                    <th colspan="2" valign="middle" style="text-align: center">KBC</th>
                    <th colspan="2" valign="middle" style="text-align: center">KBP</th>
                    <th colspan="3" valign="middle" style="text-align: center">KBPC</th>
                    <th colspan="3" valign="middle" style="text-align: center">KBPP</th>
                    <th colspan="3" valign="middle" style="text-align: center">TB</th>
                    <th colspan="2" valign="middle" style="text-align: center">TBT</th>
                    <th colspan="2" valign="middle" style="text-align: center">PBT</th>
                    <th colspan="2" valign="middle" style="text-align: center">Q2</th>
                    <th colspan="3" valign="middle" style="text-align: center">NPC</th>
                    <th colspan="3" valign="middle" style="text-align: center">NPP</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Total Waktu</th>
                    <th rowspan="2" valign="middle" style="text-align: center">Effisiensi (%)</th>
                    </tr>
                  <tr>
                    <th valign="middle" style="text-align: center">dus</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    <th valign="middle" style="text-align: center">dus</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    <th valign="middle" style="text-align: center">dus</th>
                    <th valign="middle" style="text-align: center">cones</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    <th valign="middle" style="text-align: center">dus</th>
                    <th valign="middle" style="text-align: center">cones</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    <th valign="middle" style="text-align: center">dus</th>
                    <th valign="middle" style="text-align: center">qty</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    <th valign="middle" style="text-align: center">Unit</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    <th valign="middle" style="text-align: center">Unit</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    <th valign="middle" style="text-align: center">dus</th>
                    <th valign="middle" style="text-align: center">cones</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    <th valign="middle" style="text-align: center">dus</th>
                    <th valign="middle" style="text-align: center">cones</th>
                    <th valign="middle" style="text-align: center">Target</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
function KPI($kd,$jns){
include "koneksi.php";	
$sqlDB20 = " SELECT x.* FROM DB2ADMIN.USERGENERICGROUP x
WHERE x.USERGENERICGROUPTYPECODE ='KPI' AND x.CODE='$kd' ";
$stmt0   = db2_exec($conn1,$sqlDB20, array('cursor'=>DB2_SCROLLABLE));
$rowdb20 = db2_fetch_assoc($stmt0);
if($jns=="DUS"){
	return $rowdb20['SHORTDESCRIPTION'];
}else if($jns=="CONES"){
	return $rowdb20['SEARCHDESCRIPTION'];
}	
	
}	
function UNIT($kd,$nama,$S1,$A1,$A2){	
include "koneksi.php";	
if($S1=="1"){ $w1=" AND TRANSACTIONTIME BETWEEN '07:00:00' AND '15:00:00' "; }
elseif($S1=="2"){ $w1=" AND TRANSACTIONTIME BETWEEN '15:00:00' AND '23:00:00' "; }
elseif($S1=="3"){ $w1=" AND CONCAT (TRANSACTIONDATE,CONCAT (' ',TRANSACTIONTIME)) BETWEEN '$A1 23:00:00' AND '$A2 07:00:00' "; }
//elseif($S1=="ALL"){ $w1=" AND CONCAT (TRANSACTIONDATE,CONCAT (' ',TRANSACTIONTIME)) BETWEEN '$A1 07:00:00' AND '$A2 07:00:00' "; }	
else{ $w1=""; }	
$sqlDB20 = " SELECT p.CREATIONUSER, COUNT(p.CREATIONUSER) AS MC,p.KDKPI FROM (SELECT 
	STOCKTRANSACTION.CREATIONUSER,
	MCN.NOMC,	
	KPI.KDKPI
	FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
	(
	SELECT SUBSTR(LISTAGG(DISTINCT  TRIM(i.PRODUCTIONDEMANDCODE),', '),1,8) AS PRODUCTIONDEMANDCODE,
i.PRODUCTIONORDERCODE  
FROM ITXVIEWKNTORDER i 
GROUP BY i.PRODUCTIONORDERCODE
	) ITXVIEWKNTORDER ON ITXVIEWKNTORDER.PRODUCTIONORDERCODE =STOCKTRANSACTION.ORDERCODE 
	LEFT OUTER JOIN (
 	SELECT ADSTORAGE.VALUESTRING AS NOMC,PRODUCTIONDEMAND.CODE FROM PRODUCTIONDEMAND
 	LEFT OUTER JOIN ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
 	) MCN ON MCN.CODE=ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
	LEFT OUTER JOIN (
    SELECT
	a.VALUESTRING AS KDKPI,s.TRANSACTIONNUMBER
FROM
	STOCKTRANSACTION s
LEFT OUTER JOIN ADSTORAGE a ON
	a.UNIQUEID = s.ABSUNIQUEID
	AND a.NAMENAME = 'KPIBenang'
WHERE NOT a.VALUESTRING IS NULL
GROUP BY
	a.VALUESTRING,s.TRANSACTIONNUMBER
    ) KPI ON KPI.TRANSACTIONNUMBER=STOCKTRANSACTION.TRANSACTIONNUMBER 
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
((STOCKTRANSACTION.ONHANDUPDATE >1 AND TEMPLATECODE ='120') OR STOCKTRANSACTION.RETURNTRANSACTION ='1') AND TRANSACTIONDATE BETWEEN '$A1' AND '$A2' $w1 AND NOT ORDERCODE IS NULL 
AND STOCKTRANSACTION.CREATIONUSER='$nama'
GROUP BY
	STOCKTRANSACTION.CREATIONUSER,KPI.KDKPI,MCN.NOMC) p
WHERE p.KDKPI= '$kd'	
GROUP BY p.CREATIONUSER,p.KDKPI ";
$stmt0   = db2_exec($conn1,$sqlDB20, array('cursor'=>DB2_SCROLLABLE));
$rowdb20 = db2_fetch_assoc($stmt0);
	if($rowdb20['MC']>0){
		return $rowdb20['MC'];
	}else{
		return 0;
	}
}					  
$no=1;   
$c=0;
if($Shift=="1"){ $wkt=" AND TRANSACTIONTIME BETWEEN '07:00:00' AND '15:00:00' "; }
elseif($Shift=="2"){ $wkt=" AND TRANSACTIONTIME BETWEEN '15:00:00' AND '23:00:00' "; }
elseif($Shift=="3"){ $wkt=" AND CONCAT (TRANSACTIONDATE,CONCAT (' ',TRANSACTIONTIME)) BETWEEN '$Awal 23:00:00' AND '$Akhir 07:00:00' "; }	
//elseif($Shift=="ALL"){ $wkt=" AND CONCAT (TRANSACTIONDATE,CONCAT (' ',TRANSACTIONTIME)) BETWEEN '$Awal 07:00:00' AND '$Akhir 07:00:00' "; }
else{ $wkt=""; }					  
	$sqlDB21 = "
	SELECT k.CREATIONUSER,
SUM(CASE WHEN k.KDKPI='KBP' THEN k.QTY_DUS ELSE 0 END) AS KBP_DUS,
SUM(CASE WHEN k.KDKPI='KBP' THEN k.QTY_KG ELSE 0 END) AS KBP_KG,
SUM(CASE WHEN k.KDKPI='KBP' THEN k.QTY_CONES ELSE 0 END) AS KBP_CONES,
SUM(CASE WHEN k.KDKPI='KBC' THEN k.QTY_DUS ELSE 0 END) AS KBC_DUS,
SUM(CASE WHEN k.KDKPI='KBC' THEN k.QTY_KG ELSE 0 END) AS KBC_KG,
SUM(CASE WHEN k.KDKPI='KBC' THEN k.QTY_CONES ELSE 0 END) AS KBC_CONES,
SUM(CASE WHEN k.KDKPI='KBPC' THEN k.QTY_DUS ELSE 0 END) AS KBPC_DUS,
SUM(CASE WHEN k.KDKPI='KBPC' THEN k.QTY_KG ELSE 0 END) AS KBPC_KG,
SUM(CASE WHEN k.KDKPI='KBPC' THEN k.QTY_CONES ELSE 0 END) AS KBPC_CONES,
SUM(CASE WHEN k.KDKPI='KBPP' THEN k.QTY_DUS ELSE 0 END) AS KBPP_DUS,
SUM(CASE WHEN k.KDKPI='KBPP' THEN k.QTY_KG ELSE 0 END) AS KBPP_KG,
SUM(CASE WHEN k.KDKPI='KBPP' THEN k.QTY_CONES ELSE 0 END) AS KBPP_CONES,
SUM(CASE WHEN k.KDKPI='NPC' THEN k.QTY_DUS ELSE 0 END) AS NPC_DUS,
SUM(CASE WHEN k.KDKPI='NPC' THEN k.QTY_KG ELSE 0 END) AS NPC_KG,
SUM(CASE WHEN k.KDKPI='NPC' THEN k.QTY_CONES ELSE 0 END) AS NPC_CONES,
SUM(CASE WHEN k.KDKPI='NPP' THEN k.QTY_DUS ELSE 0 END) AS NPP_DUS,
SUM(CASE WHEN k.KDKPI='NPP' THEN k.QTY_KG ELSE 0 END) AS NPP_KG,
SUM(CASE WHEN k.KDKPI='NPP' THEN k.QTY_CONES ELSE 0 END) AS NPP_CONES,
SUM(CASE WHEN k.KDKPI='TB' THEN k.QTY_DUS ELSE 0 END) AS TB_DUS,
SUM(CASE WHEN k.KDKPI='TB' THEN k.QTY_KG ELSE 0 END) AS TB_KG,
SUM(CASE WHEN k.KDKPI='TB' THEN k.QTY_CONES ELSE 0 END) AS TB_CONES
FROM
(SELECT 
	STOCKTRANSACTION.CREATIONUSER,
	STOCKTRANSACTION.TRANSACTIONNUMBER,
	MCN.NOMC,
	COUNT(DISTINCT MCN.NOMC) AS MC,
	COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_DUS,
	SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
	SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
	KPI.KDKPI
	FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
	(
	SELECT SUBSTR(LISTAGG(DISTINCT  TRIM(i.PRODUCTIONDEMANDCODE),', '),1,8) AS PRODUCTIONDEMANDCODE,
i.PRODUCTIONORDERCODE  
FROM ITXVIEWKNTORDER i 
GROUP BY i.PRODUCTIONORDERCODE
	) ITXVIEWKNTORDER ON ITXVIEWKNTORDER.PRODUCTIONORDERCODE =STOCKTRANSACTION.ORDERCODE 
	LEFT OUTER JOIN (
 	SELECT ADSTORAGE.VALUESTRING AS NOMC,PRODUCTIONDEMAND.CODE FROM PRODUCTIONDEMAND
 	LEFT OUTER JOIN ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
 	) MCN ON MCN.CODE=ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
	LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
    STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
    LEFT OUTER JOIN (
    SELECT
	a.VALUESTRING AS KDKPI,s.TRANSACTIONNUMBER
FROM
	STOCKTRANSACTION s
LEFT OUTER JOIN ADSTORAGE a ON
	a.UNIQUEID = s.ABSUNIQUEID
	AND a.NAMENAME = 'KPIBenang'
WHERE NOT a.VALUESTRING IS NULL
GROUP BY
	a.VALUESTRING,s.TRANSACTIONNUMBER
    ) KPI ON KPI.TRANSACTIONNUMBER=STOCKTRANSACTION.TRANSACTIONNUMBER 
WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
((STOCKTRANSACTION.ONHANDUPDATE >1 AND TEMPLATECODE ='120') OR STOCKTRANSACTION.RETURNTRANSACTION ='1') AND TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' $wkt AND NOT ORDERCODE IS NULL 
GROUP BY
	STOCKTRANSACTION.CREATIONUSER,KPI.KDKPI,STOCKTRANSACTION.TRANSACTIONNUMBER,MCN.NOMC) k
GROUP BY k.CREATIONUSER			
";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}		
	$dTBT="";
	$dPBT="";				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
	$dTBT=UNIT("TBT",$rowdb21['CREATIONUSER'],$Shift,$Awal,$Akhir);
	$dPBT=UNIT("PBT",$rowdb21['CREATIONUSER'],$Shift,$Awal,$Akhir);
	$sqlQ2 = " SELECT
	x.CREATIONUSER,
	SUM(x.BASESECONDARYQUANTITY) AS CONES
FROM
	DB2ADMIN.STOCKTRANSACTION x
WHERE
	x.CREATIONUSER = '".$rowdb21['CREATIONUSER']."'
	AND QUALITYLEVELCODE = '2'
	AND
(ITEMTYPECODE = 'GYR'
		OR ITEMTYPECODE = 'DYR')
	AND (LOGICALWAREHOUSECODE = 'P501'
		OR LOGICALWAREHOUSECODE = 'M904')
	AND (TEMPLATECODE = '120' AND ONHANDUPDATE >1) 
	AND TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' $wkt 
	AND NOT ORDERCODE IS NULL
GROUP BY
	x.CREATIONUSER ";
$stmtQ2   = db2_exec($conn1,$sqlQ2, array('cursor'=>DB2_SCROLLABLE));
$rowdQ2 = db2_fetch_assoc($stmtQ2);	
	
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: left"><?php echo $rowdb21['CREATIONUSER']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['KBC_DUS']; ?></td>
      <td style="text-align: center"><?php echo KPI("KBC","DUS")*$rowdb21['KBC_DUS']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['KBP_DUS']; ?></td>
      <td style="text-align: center"><?php echo KPI("KBP","DUS")*$rowdb21['KBP_DUS']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['KBPC_DUS']; ?></td>
      <td style="text-align: center"><?php echo round($rowdb21['KBPC_CONES']); ?></td>
      <td style="text-align: center"><?php echo (KPI("KBPC","DUS")*$rowdb21['KBPC_DUS'])+round((KPI("KBPC","CONES")*$rowdb21['KBPC_CONES'])/60); ?></td>
      <td style="text-align: center"><?php echo $rowdb21['KBPP_DUS']; ?></td>
      <td style="text-align: center"><?php echo round($rowdb21['KBPP_CONES']); ?></td>
      <td style="text-align: center"><?php echo (KPI("KBPP","DUS")*$rowdb21['KBPP_DUS'])+round((KPI("KBPP","CONES")*$rowdb21['KBPP_CONES'])/60); ?></td>
      <td style="text-align: center"><?php echo $rowdb21['TB_DUS']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['TB_KG'],2); ?></td>
      <td style="text-align: center"><?php echo (KPI("TB","DUS")*$rowdb21['TB_DUS'])+round(($rowdb21['TB_KG'])/(KPI("TB","CONES"))); ?></td>
      <td style="text-align: center"><?php echo $dTBT; ?></td>
      <td style="text-align: center"><?php echo KPI("TBT","DUS") * $dTBT; ?></td>
      <td style="text-align: center"><?php echo $dPBT; ?></td>
      <td style="text-align: center"><?php echo KPI("PBT","DUS") * $dPBT; ?></td>
      <td style="text-align: center"><?php echo round($rowdQ2['CONES'],2); ?></td>
      <td style="text-align: center"><?php echo round((round($rowdQ2['CONES'],2)*5)/60,2); ?></td>
      <td style="text-align: center"><?php echo $rowdb21['NPC_DUS']; ?></td>
      <td style="text-align: center"><?php echo round($rowdb21['NPC_CONES']); ?></td>
      <td style="text-align: center"><?php echo (KPI("NPC","DUS")*$rowdb21['NPC_DUS'])+round((KPI("NPC","CONES")*$rowdb21['NPC_CONES'])/60); ?></td>
      <td style="text-align: center"><?php echo $rowdb21['NPP_DUS']; ?></td>
      <td style="text-align: center"><?php echo round($rowdb21['NPP_CONES']); ?></td>
      <td style="text-align: center"><?php echo (KPI("NPP","DUS")*$rowdb21['NPP_DUS'])+round((KPI("NPP","CONES")*$rowdb21['NPP_CONES'])/60); ?></td>
      <td style="text-align: center"><?php echo (KPI("KBC","DUS")*$rowdb21['KBC_DUS'])+(KPI("KBP","DUS")*$rowdb21['KBP_DUS'])+((KPI("KBPC","DUS")*$rowdb21['KBPC_DUS'])+round((KPI("KBPC","CONES")*$rowdb21['KBPC_CONES'])/60))+((KPI("KBPP","DUS")*$rowdb21['KBPP_DUS'])+round((KPI("KBPP","CONES")*$rowdb21['KBPP_CONES'])/60))+
	  ((KPI("NPC","DUS")*$rowdb21['NPC_DUS'])+round((KPI("NPC","CONES")*$rowdb21['NPC_CONES'])/60))+
	  ((KPI("NPP","DUS")*$rowdb21['NPP_DUS'])+round((KPI("NPP","CONES")*$rowdb21['NPP_CONES'])/60))+((KPI("TB","DUS")*$rowdb21['TB_DUS'])+round(($rowdb21['TB_KG'])/(KPI("TB","CONES")))+(KPI("TBT","DUS") * $dTBT)+(KPI("PBT","DUS") * $dPBT)+round((round($rowdQ2['CONES'],2)*5)/60,2)); ?></td>
      <td style="text-align: center"><?php echo round(((KPI("KBC","DUS")*$rowdb21['KBC_DUS'])+(KPI("KBP","DUS")*$rowdb21['KBP_DUS'])+((KPI("KBPC","DUS")*$rowdb21['KBPC_DUS'])+round((KPI("KBPC","CONES")*$rowdb21['KBPC_CONES'])/60))+((KPI("KBPP","DUS")*$rowdb21['KBPP_DUS'])+round((KPI("KBPP","CONES")*$rowdb21['KBPP_CONES'])/60))+
	  ((KPI("NPC","DUS")*$rowdb21['NPC_DUS'])+round((KPI("NPC","CONES")*$rowdb21['NPC_CONES'])/60))+
	  ((KPI("NPP","DUS")*$rowdb21['NPP_DUS'])+round((KPI("NPP","CONES")*$rowdb21['NPP_CONES'])/60))+
	  ((KPI("TB","DUS")*$rowdb21['TB_DUS'])+round(($rowdb21['TB_KG'])/(KPI("TB","CONES")))+(KPI("TBT","DUS") * $dTBT)+
	  (KPI("PBT","DUS") * $dPBT)+round((round($rowdQ2['CONES'],2)*5)/60,2)))/420,2)*100;?></td>
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
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    </tr>
	</tfoot>            
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
	</form>		
      </div><!-- /.container-fluid -->
<div id="DetailTurunanShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
    <!-- /.content -->
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