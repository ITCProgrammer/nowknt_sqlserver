<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$status	= isset($_POST['stts']) ? $_POST['stts'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">   
		<div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Filter Data Tgl</h3>

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
               <label for="tgl_awal" class="col-md-1">Tanggal Dari</label>
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
            </div>
			 <div class="form-group row">
               <label for="tgl_akhir" class="col-md-1">Tanggal Sampai</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>	
            </div> 
			<div class="form-group row">
               <label for="tgl_akhir" class="col-md-1">Status</label>
               <div class="col-md-2">  
                 <select class="form-control form-control-sm" name="stts">
				  	<option value="SELESAI" <?php if($status=="SELESAI"){ echo "SELECTED"; } ?>>SELESAI</option>
				  	<option value="PROGRES" <?php if($status=="PROGRES"){ echo "SELECTED"; } ?>>PROGRES</option>
					<option value="ALL" <?php if($status=="ALL"){ echo "SELECTED"; } ?>>ALL</option> 
				  </select>
			   </div>	
            </div>   
				 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  	
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
                    <th style="text-align: center">Artikel</th>
                    <th style="text-align: center">No Demand</th>
                    <th style="text-align: center">Mesin</th>
                    <th style="text-align: center">Lokasi</th>
                    <th style="text-align: center">Order</th>
                    <th style="text-align: center">Rajut</th>
                    <th style="text-align: center">Kurang Rajut</th>
                    <th style="text-align: center">M502</th>
                    <th style="text-align: center">TR11</th>
                    <th style="text-align: center">M021</th>
                    <th style="text-align: center">Tot.Kurang Project</th>
                    <th style="text-align: center">Tot.Terima Project</th>
                    <th style="text-align: center">Tgl Delivery</th>
                    <th style="text-align: center">Tgl Selesai</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">Tingkat Prioritas</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
if($status=="SELESAI"){					  
$where  = " AND VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') BETWEEN '$Awal' AND '$Akhir' ";	
}else if($status=="PROGRES"){
$where = " AND IDS IN('2 ,0','0 ,2','2 ,2') ";	
}else if($status=="ALL"){
$where = " AND (VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') BETWEEN '$Awal' AND '$Akhir' OR IDS IN('2 ,0','0 ,2','2 ,2'))";	
}else{
$where  = " AND VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') BETWEEN '$Awal' AND '$Akhir' ";		
}
$sqlDB2 ="
WITH  
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
),
CHG_PRO AS (
SELECT 
s.DECOSUBCODE01,
s.DECOSUBCODE02,
s.DECOSUBCODE03,
s.DECOSUBCODE04,
s.PROJECTCODE,
SUM(s.BASEPRIMARYQUANTITY) AS CHANGE_KG
FROM STOCKTRANSACTION s 
WHERE s.ITEMTYPECODE='KGF' AND s.TEMPLATECODE='312' AND  LOGICALWAREHOUSECODE ='M502'
GROUP BY
s.DECOSUBCODE01,
s.DECOSUBCODE02,
s.DECOSUBCODE03,
s.DECOSUBCODE04,
s.PROJECTCODE
),
STKGKG AS (
SELECT
  STOCKTRANSACTION.PROJECTCODE,
  STOCKTRANSACTION.DECOSUBCODE02,
  STOCKTRANSACTION.DECOSUBCODE03,
  STOCKTRANSACTION.DECOSUBCODE04,
  SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
  COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL
FROM
  DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
  LEFT OUTER JOIN DB2ADMIN.ITXVIEWLAPMASUKGREIGE ITXVIEWLAPMASUKGREIGE ON ITXVIEWLAPMASUKGREIGE.PROVISIONALCODE = STOCKTRANSACTION.ORDERCODE
  AND ITXVIEWLAPMASUKGREIGE.ORDERLINE = STOCKTRANSACTION.ORDERLINE
  AND ITXVIEWLAPMASUKGREIGE.PROVISIONALCOUNTERCODE = STOCKTRANSACTION.ORDERCOUNTERCODE
  AND ITXVIEWLAPMASUKGREIGE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE
  AND ITXVIEWLAPMASUKGREIGE.SUBCODE01 = STOCKTRANSACTION.DECOSUBCODE01
  AND ITXVIEWLAPMASUKGREIGE.SUBCODE02 = STOCKTRANSACTION.DECOSUBCODE02
  AND ITXVIEWLAPMASUKGREIGE.SUBCODE03 = STOCKTRANSACTION.DECOSUBCODE03
  AND ITXVIEWLAPMASUKGREIGE.SUBCODE04 = STOCKTRANSACTION.DECOSUBCODE04
WHERE
  STOCKTRANSACTION.ITEMTYPECODE = 'KGF'
  and STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M021'
  AND NOT ITXVIEWLAPMASUKGREIGE.ORDERLINE IS NULL
GROUP BY
  STOCKTRANSACTION.PROJECTCODE,
  STOCKTRANSACTION.DECOSUBCODE02,
  STOCKTRANSACTION.DECOSUBCODE03,
  STOCKTRANSACTION.DECOSUBCODE04
),
KGSISA AS (
SELECT
              COALESCE(SUM(a.BASEPRIMARYQUANTITY), 0) - COALESCE(SUM(a3.VALUEDECIMAL), 0) AS KGORDR,
              a.ITEMTYPEAFICODE,
              a.SUBCODE01,
              a.SUBCODE02,
              a.SUBCODE03,
              a.SUBCODE04,
              CASE WHEN a.PROJECTCODE ISNULL THEN a.ORIGDLVSALORDLINESALORDERCODE ELSE a.PROJECTCODE END AS PROJECTCODE
            FROM
              (
                SELECT
                  PRODUCTIONDEMAND.PROJECTCODE,
                  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
                  PRODUCTIONDEMAND.CODE,
                  PRODUCTIONDEMAND.ITEMTYPEAFICODE,
                  PRODUCTIONDEMAND.SUBCODE01,
                  PRODUCTIONDEMAND.SUBCODE02,
                  PRODUCTIONDEMAND.SUBCODE03,
                  PRODUCTIONDEMAND.SUBCODE04,
                  PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,
                  PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE,
                  PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
                  PRODUCTIONORDER.PROGRESSSTATUS
                FROM
                  PRODUCTIONDEMAND PRODUCTIONDEMAND
                  LEFT OUTER JOIN DB2ADMIN.COMPANY COMPANY ON PRODUCTIONDEMAND.COMPANYCODE = COMPANY.CODE
                  LEFT OUTER JOIN DB2ADMIN.PRODUCTIONCUSTOMIZEDOPTIONS PRODUCTIONCUSTOMIZEDOPTIONS ON PRODUCTIONDEMAND.COMPANYCODE = PRODUCTIONCUSTOMIZEDOPTIONS.COMPANYCODE
                  LEFT OUTER JOIN DB2ADMIN.ORDERPARTNER ORDERPARTNER ON PRODUCTIONDEMAND.CUSTOMERCODE = ORDERPARTNER.CUSTOMERSUPPLIERCODE
                  LEFT OUTER JOIN DB2ADMIN.BUSINESSPARTNER BUSINESSPARTNER ON ORDERPARTNER.ORDERBUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID
                  LEFT OUTER JOIN DB2ADMIN.SCHEDULESOFSTEPSPLITS SCHEDULESOFSTEPSPLITS ON PRODUCTIONDEMAND.CODE = SCHEDULESOFSTEPSPLITS.CODE
                  LEFT JOIN DB2ADMIN.PRODUCTIONDEMANDSTEP PRODUCTIONDEMANDSTEP ON PRODUCTIONDEMAND.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE
                  AND PRODUCTIONDEMAND.COMPANYCODE = PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCOMPANYCODE
                  LEFT JOIN DB2ADMIN.PRODUCTIONORDER PRODUCTIONORDER ON PRODUCTIONORDER.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE
                GROUP BY
                  PRODUCTIONDEMAND.PROJECTCODE,
                  PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE,
                  PRODUCTIONDEMAND.CODE,
                  PRODUCTIONDEMAND.ITEMTYPEAFICODE,
                  PRODUCTIONDEMAND.SUBCODE01,
                  PRODUCTIONDEMAND.SUBCODE02,
                  PRODUCTIONDEMAND.SUBCODE03,
                  PRODUCTIONDEMAND.SUBCODE04,
                  PRODUCTIONDEMAND.BASEPRIMARYQUANTITY,
                  PRODUCTIONDEMAND.FINALPLANNEDDATE,
                  PRODUCTIONDEMAND.FINALEFFECTIVEDATE,
                  BUSINESSPARTNER.LEGALNAME1,
                  PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE,
                  PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
                  PRODUCTIONORDER.PROGRESSSTATUS
              ) a
              LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE = a.PRODUCTIONDEMANDCODE
              LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID = a2.UNIQUEID
              AND a2.NAMENAME = 'StatusRMP'
              LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID = a3.UNIQUEID
              AND a3.NAMENAME = 'QtySalin'
            WHERE
              a.ITEMTYPEAFICODE = 'KGF'
              AND a.PROGRESSSTATUS IN ('1','2','6')
              AND (
                NOT a2.VALUESTRING = '3'
                OR a2.VALUESTRING IS NULL)
                GROUP BY 
                a.ITEMTYPEAFICODE,
                  a.SUBCODE01,
                  a.SUBCODE02,
                  a.SUBCODE03,
                  a.SUBCODE04,
                  a.PROJECTCODE,
                  a.ORIGDLVSALORDLINESALORDERCODE
)
SELECT *,
AD2.VALUEDATE AS RMPREQDATE,
AD3.VALUEDECIMAL AS QTYSALIN,
AD7.VALUEDATE AS RMPREQDATETO,
AD8.VALUESTRING AS LT,
AD5.VALUEDECIMAL AS QTYOPIN,
AD6.VALUEDECIMAL AS QTYOPOUT,
ADSTORAGE.VALUESTRING AS MC,
qtyg.KG_M502,
qtyg.KG_TR11,
qtyg.KG_M021,
sts.IDS,
insj.JML,
insj.INSPECTIONENDDATETIME,
chg.CHANGE_KG,
sgkg.QTY_KG AS KG_TERIMA,
ksisa.KGORDR AS KG_MINTA,
USERGENERICGROUP.SEARCHDESCRIPTION AS NOMC,
CURRENT_TIMESTAMP AS TGLS,VARCHAR_FORMAT(ITXVIEWKNTORDER.FINALEFFECTIVEDATE,'YYYY-MM-DD') AS TGLTUTUP
FROM ITXVIEWKNTORDER  
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE	
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.FIELDNAME ='RMPReqDate'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.FIELDNAME ='QtySalin'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD5 ON AD5.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD5.NAMENAME ='QtyOperIn'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD6 ON AD6.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD6.NAMENAME ='QtyOperOut'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD7 ON AD7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD7.FIELDNAME ='RMPGreigeReqDateTo'
LEFT OUTER JOIN DB2ADMIN.USERGENERICGROUP ON USERGENERICGROUP.CODE = ADSTORAGE.VALUESTRING
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD8 ON AD8.UNIQUEID = USERGENERICGROUP.ABSUNIQUEID AND AD8.NAMENAME ='LokasiMesinKNT'
LEFT OUTER JOIN QTY_GREIGE qtyg ON qtyg.LOTCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN STS_INSKNT sts ON sts.PRODUCTIONDEMANDCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN JMLINS insj ON insj.DEMANDCODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN CHG_PRO chg ON 
chg.PROJECTCODE = ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE AND 
chg.DECOSUBCODE01 = ITXVIEWKNTORDER.SUBCODE01 AND
chg.DECOSUBCODE02 = ITXVIEWKNTORDER.SUBCODE02 AND
chg.DECOSUBCODE03 = ITXVIEWKNTORDER.SUBCODE03 AND
chg.DECOSUBCODE04 = ITXVIEWKNTORDER.SUBCODE04
LEFT OUTER JOIN STKGKG sgkg ON 
sgkg.PROJECTCODE = ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE AND 
sgkg.DECOSUBCODE02 = ITXVIEWKNTORDER.SUBCODE02 AND
sgkg.DECOSUBCODE03 = ITXVIEWKNTORDER.SUBCODE03 AND
sgkg.DECOSUBCODE04 = ITXVIEWKNTORDER.SUBCODE04
LEFT OUTER JOIN KGSISA ksisa ON 
ksisa.PROJECTCODE = ITXVIEWKNTORDER.ORIGDLVSALORDLINESALORDERCODE AND 
ksisa.SUBCODE02 = ITXVIEWKNTORDER.SUBCODE02 AND
ksisa.SUBCODE03 = ITXVIEWKNTORDER.SUBCODE03 AND
ksisa.SUBCODE04 = ITXVIEWKNTORDER.SUBCODE04
WHERE ITXVIEWKNTORDER.ITEMTYPEAFICODE ='KGF' AND ITXVIEWKNTORDER.PROGRESSSTATUS IN ('2','6')
$where
 ";	
$stmt   = db2_prepare($conn1,$sqlDB2);
db2_execute($stmt);					  
$no=1;
while($rowdb2 = db2_fetch_assoc($stmt)){

$sqlDB22 =" SELECT COUNT(DEMANDCODE) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF'";	
$stmt2   = db2_prepare($conn1,$sqlDB22);
db2_execute($stmt2);	
$rowdb22 = db2_fetch_assoc($stmt2);

	
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
	$kRajut=round(($rowdb2['BASEPRIMARYQUANTITY']+$rowdb2['QTYOPOUT'])-($rowdb2['QTYSALIN']+$rowdb2['QTYOPIN']),2)- round($rowdb22['JQTY'],2);
	$prioritas = "";

if (!empty($rowdb2['RMPREQDATETO'])) {

    $tglTutup = !empty($rowdb2['TGLTUTUP'])
        ? DateTime::createFromFormat('Y-m-d', substr($rowdb2['TGLTUTUP'], 0, 10))
        : null;

    $reqDate  = DateTime::createFromFormat('Y-m-d', substr($rowdb2['RMPREQDATETO'], 0, 10));
    $today    = new DateTime(date('Y-m-d'));

    $potensiStart = (clone $reqDate)->modify('-2 days');
    $normalStart  = (clone $reqDate)->modify('-3 days');

    // Helper badge
    $badgeDelay   = "<small class='badge badge-danger'><i class='far fa-calendar-check blink_me'></i> Delay</small>";
    $badgeDD      = "<small class='badge badge-success'><i class='far fa-calendar-check blink_me'></i> Delivery Date</small>";
    $badgePotensi = "<small class='badge badge-warning'><i class='far fa-calendar-check blink_me'></i> Potensi Delay</small>";
    $badgeNormal  = "<small class='badge'><i class='far fa-calendar-check blink_me'></i> Normal</small>";
    $badgeClosed  = "<small class='badge badge-secondary'><i class='far fa-calendar-check'></i> Closed</small>";

    // =========================
    // CLOSED (SELESAI atau ALL)
    // =========================
    if (
        ($status === "SELESAI" || $status === "ALL")
        && (int)$rowdb2['KG_M502'] === 0
        && (int)$rowdb2['KG_TR11'] === 0
    ) {
        $prioritas = $badgeClosed;
    } else {

        // Tentukan mode evaluasi: SELESAI / PROGRES (khusus ALL auto-detect)
        $mode = $status;
        if ($status === "ALL") {
            $mode = ($tglTutup instanceof DateTime) ? "SELESAI" : "PROGRES";
        }

        if ($mode === "SELESAI") {

            if ($tglTutup > $reqDate) {
                $prioritas = $badgeDelay;
            } elseif ($tglTutup == $reqDate) {
                $prioritas = $badgeDD;
            } else {
                if ($today < $reqDate && $today >= $potensiStart) {
                    $prioritas = $badgePotensi;
                } elseif ($today <= $normalStart) {
                    $prioritas = $badgeNormal;
                } else {
                    $prioritas = $badgePotensi;
                }
            }

        } elseif ($mode === "PROGRES") {

            if ($today > $reqDate) {
                $prioritas = $badgeDelay;
            } elseif ($today == $reqDate) {
                $prioritas = $badgeDD;
            } else {
                if ($today < $reqDate && $today >= $potensiStart) {
                    $prioritas = $badgePotensi;
                } elseif ($today <= $normalStart) {
                    $prioritas = $badgeNormal;
                } else {
                    $prioritas = $badgePotensi;
                }
            }
        }
    }
}


	$HangerNO=trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);
	$demandNO=$rowdb2['PRODUCTIONDEMANDCODE'];

?>
	  <tr>
      <td style="text-align: center"><?php echo $no; ?></td>
      <td style="text-align: center"><?php echo $project;?></td>
      <td style="text-align: center"><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']);?></td>
      <td style="text-align: center"><?php echo $rowdb2['PRODUCTIONDEMANDCODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb2['NOMC']; ?></td>
      <td style="text-align: center"><?php echo $rowdb2['LT']; ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb2['BASEPRIMARYQUANTITY'],2),2);?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb22['JQTY'],2),2);?></td>
      <td style="text-align: right"><a href="#" id="<?php echo $project;?>#<?php echo $HangerNO; ?>#<?php echo $demandNO; ?>" class="show_detail_inout" alt="Detail"><?php echo number_format($kRajut,2); ?></a></td>
      <td style="text-align: center"><?php echo number_format(round($rowdb2['KG_M502'],2),2);?></td> 
      <td style="text-align: center"><?php echo number_format(round($rowdb2['KG_TR11'],2),2);?></td>
      <td style="text-align: center"><?php echo number_format(round($rowdb2['KG_M021'],2),2);?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb2['KG_MINTA']-$rowdb2['KG_TERIMA'],2),2);?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb2['KG_TERIMA'],2),2);?></td>
      <td style="text-align: center"><?php echo $rowdb2['RMPREQDATETO'];?></td>
      <td style="text-align: center"><?php echo $rowdb2['TGLTUTUP'];?></td>
      <td style="text-align: center"><?php echo $stts;?></td>
      <td style="text-align: center"><?php echo $prioritas;?></td>
      </tr>				  
	<?php 
	 $no++;} ?>
				  </tbody>
                 </table>
              </div>
              <!-- /.card-body -->
            </div>  
		</form>	
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="DetailINOUTShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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