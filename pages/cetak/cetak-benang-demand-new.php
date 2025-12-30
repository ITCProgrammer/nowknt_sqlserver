<?php
include "../../koneksi.php";

$Awal = @$_GET['awal'] ?: '';
$Akhir = @$_GET['akhir'] ?: '';
$Stts = @$_GET['status'] ?: '';
$no_project = @$_GET['project'] ?: '';
switch ($Stts) {
    case '6':
        $status = 'Selesai';
        break;
    case '2':
        $status = 'Sedang Jalan';
        break;
    default:
        $status = "";
        break;
}

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan loss benang PO " . $status . "" . $no_project . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!-- Main content -->
<div class="container-fluid">
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-sm table-bordered table-striped"
            style="font-size:13px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th colspan="22" style="border: 1px solid black; font-size: 2em;">
                        Laporan Loss Benang PO
                        <?= $status ?>
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;">No</th>
                    <th style="border: 1px solid black;">Tgl Selesai</th>
                    <th style="border: 1px solid black;">Ket</th>
                    <th style="border: 1px solid black;">Prod. Order</th>
                    <th style="border: 1px solid black;">Demand</th>
                    <th style="border: 1px solid black;">NoArt</th>
                    <th style="border: 1px solid black;">Jenis Benang</th>
                    <th style="border: 1px solid black;">Kode Benang</th>
                    <th style="border: 1px solid black;">No Mesin</th>
                    <th style="border: 1px solid black;">Rol</th>
                    <th style="border: 1px solid black;">Kgs</th>
                    <th style="border: 1px solid black;">BS Mekanik</th>
                    <th style="border: 1px solid black;">%</th>
                    <th style="border: 1px solid black;">BS Produksi</th>
                    <th style="border: 1px solid black;">%</th>
                    <th style="border: 1px solid black;">Lain-Lain</th>
                    <th style="border: 1px solid black;">%</th>
                    <th style="border: 1px solid black;">Sisa</th>
                    <th style="border: 1px solid black;">Pakai</th>
                    <th style="border: 1px solid black;">Loss (Kgs)</th>
                    <th style="border: 1px solid black;">Loss (%)</th>
                    <th style="border: 1px solid black;">Total Loss(%)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($Awal != "" and $Akhir != "") {
                    if ($Stts == "2") {
                        $Tgl = " AND VARCHAR_FORMAT(i.FINALPLANNEDDATE,'YYYY-MM-DD') BETWEEN '$Awal' AND '$Akhir' ";
                    } else if ($Stts == "6") {
                        $Tgl = " AND VARCHAR_FORMAT(i.FINALEFFECTIVEDATE,'YYYY-MM-DD') BETWEEN '$Awal' AND '$Akhir' ";
                    }

                } else {
                    $Tgl = " AND VARCHAR_FORMAT(i.FINALEFFECTIVEDATE,'YYYY-MM-DD') BETWEEN '2200-12-12' AND '2200-12-12' ";
                }
						
	if ($no_project !='') {
		$no_project= " AND (i.ORIGDLVSALORDLINESALORDERCODE = '$no_project' OR i.PROJECTCODE = '$no_project') ";
	} else {
		$no_project= "";
	}		
                $sqlDB2 = " SELECT i.ORIGDLVSALORDLINESALORDERCODE,i.PRODUCTIONDEMANDCODE,i.PRODUCTIONORDERCODE,count(e.WEIGHTREALNET) AS INSROL,sum(e.WEIGHTREALNET) AS INSKG,
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
                $stmt = db2_exec($conn1, $sqlDB2, array('cursor' => DB2_SCROLLABLE));
                $no = 1;
                $c = 0;
                $prsn = 0;
                $prsn1 = 0;
                $prsn2 = 0;
                while ($rowdb2 = db2_fetch_assoc($stmt)) {

                    $sqlDB21 = " SELECT sum(s.BASEPRIMARYQUANTITY) AS KGPAKAI FROM STOCKTRANSACTION s WHERE s.TEMPLATECODE='120' AND (s.ITEMTYPECODE='GYR' OR s.ITEMTYPECODE='DYR') AND s.ORDERCODE='$rowdb2[PRODUCTIONORDERCODE]' ";
                    $stmt1 = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
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
                    $stmt2 = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
                    $rowdb22 = db2_fetch_assoc($stmt2);

                    $sqlDB23 = " SELECT FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION, PRODUCTIONRESERVATION.SUBCODE01, PRODUCTIONRESERVATION.SUBCODE02, PRODUCTIONRESERVATION.SUBCODE03
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
	 
	 //$rowdb23 = db2_fetch_assoc($stmt3);
	 //$rowdb23x = db2_fetch_assoc($stmt3x);
					
                    $sqlDB24 = " SELECT
	COUNT(PRODUCTIONDEMANDCODE) AS JDMN, PROGRESSSTATUS 
FROM
	ITXVIEWKNTORDER
WHERE
	PRODUCTIONORDERCODE = '$rowdb2[PRODUCTIONORDERCODE]'
GROUP BY
	PRODUCTIONORDERCODE,PROGRESSSTATUS
ORDER BY PROGRESSSTATUS ASC
 ";
                    $stmt4 = db2_exec($conn1, $sqlDB24, array('cursor' => DB2_SCROLLABLE));
                    $rowdb24 = db2_fetch_assoc($stmt4);
                    if ($rowdb24['JDMN'] > 1 and $rowdb24['PROGRESSSTATUS'] == "2") {
                        $stsgabung = "<small class='badge badge-danger'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Gabung Order</small>";
                        $stsgabung .= "<br>(Demand Belum Selesai)";
                    } else if ($rowdb24['JDMN'] > 1 and $rowdb24['PROGRESSSTATUS'] == "6") {
                        $stsgabung = "<small class='badge badge-success'><i class='fas fa-exclamation-triangle text-warning blink_me'></i> Gabung Order</small>";
                        $stsgabung .= "<br>(Demand Selesai)";
                    } else {
                        $stsgabung = "";
                    }

                    if($stsgabung!=""){
	 $sql=mysqli_query($con," SELECT sum(berat_awal) as berat_awal  FROM tbl_inspeksi_detail_now tidn WHERE tidn.prod_oder='$rowdb2[PRODUCTIONORDERCODE]' and tidn.ket_bs ='BS Mekanik'");
	 }else{
	 $sql=mysqli_query($con," SELECT sum(berat_awal) as berat_awal  FROM tbl_inspeksi_detail_now tidn WHERE tidn.demandno='$rowdb2[PRODUCTIONDEMANDCODE]' and tidn.ket_bs ='BS Mekanik'");	 
	 }	 
	 $rowd=mysqli_fetch_array($sql);
	 if($stsgabung!=""){
	 $sql1=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn WHERE tidn.prod_oder='$rowdb2[PRODUCTIONORDERCODE]' and tidn.ket_bs ='BS Produksi'");
	 }else{
	 $sql1=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn WHERE tidn.demandno='$rowdb2[PRODUCTIONDEMANDCODE]' and tidn.ket_bs ='BS Produksi'");	 
	 }
	 $rowd1=mysqli_fetch_array($sql1);                    
                   if($stsgabung!=""){
	 $sql2=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn 
	 WHERE tidn.prod_oder='$rowdb2[PRODUCTIONORDERCODE]' and tidn.ket_bs ='BS Lain-lain'");
	  }else{
	 $sql2=mysqli_query($con," SELECT sum(berat_awal) as berat_awal FROM tbl_inspeksi_detail_now tidn 
	 WHERE tidn.demandno='$rowdb2[PRODUCTIONDEMANDCODE]' and tidn.ket_bs ='BS Lain-lain'");
	 }	 
                    $rowd2 = mysqli_fetch_array($sql2);
                    if (($rowdb21['KGPAKAI'] - ($rowdb22['KGSISA'] ?? 0)) > 0) {
                        $prsn = round(($rowd['berat_awal'] / ($rowdb21['KGPAKAI'] - ($rowdb22['KGSISA'] ?? 0))) * 100, 2);
                        $prsn1 = round(($rowd1['berat_awal'] / ($rowdb21['KGPAKAI'] - ($rowdb22['KGSISA'] ?? 0))) * 100, 2);
                        $prsn2 = round(($rowd2['berat_awal'] / ($rowdb21['KGPAKAI'] - ($rowdb22['KGSISA'] ?? 0))) * 100, 2);
                    }
                    $hslkg = $rowdb2['INSKG'] + ($rowdb22['KGSISA'] ?? 0) + $rowd['berat_awal'] + $rowd1['berat_awal'] + $rowd2['berat_awal'];
                    $losskg = round($rowdb21['KGPAKAI'] - $hslkg, 2);
                    if (($rowdb21['KGPAKAI'] - ($rowdb22['KGSISA'] ?? 0)) > 0) {
                        $prsnLoss = round(($losskg / ($rowdb21['KGPAKAI'] - ($rowdb22['KGSISA'] ?? 0))) * 100, 2);
                    } else {
                        $prsnLoss = 0;
                    }
                    $Thslkg = $losskg + $rowd['berat_awal'] + $rowd1['berat_awal'] + $rowd2['berat_awal'];
                    $Tlosskg = round($Thslkg, 2);
                    if (($rowdb21['KGPAKAI'] - ($rowdb22['KGSISA'] ?? 0)) > 0) {
                        $TprsnLoss = round(($Tlosskg / ($rowdb21['KGPAKAI'] - ($rowdb22['KGSISA'] ?? 0))) * 100, 2);
                    } else {
                        $TprsnLoss = 0;
                    }

                    ?>
                    <tr>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $no; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $rowdb2['TGLSELESAI']; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $stsgabung; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $rowdb2['PRODUCTIONORDERCODE']; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $rowdb2['PRODUCTIONDEMANDCODE']; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo trim($rowdb2['SUBCODE01']) . " " . trim($rowdb2['SUBCODE02']) . trim($rowdb2['SUBCODE03']); ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php $noBn = 1;
                            while ($rowdb23 = db2_fetch_assoc($stmt3)) {
                                echo $noBn . ". " . $rowdb23['SUMMARIZEDDESCRIPTION'] . "<br>";
                                $noBn++;
                            } ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;"><?php $noBnx=1; while ($rowdb23x = db2_fetch_assoc($stmt3x)){
		  $pemisah = '-';
		  $subcode = trim($rowdb23x['SUBCODE01']).$pemisah.trim($rowdb23x['SUBCODE02']).$pemisah.trim($rowdb23x['SUBCODE03']).$pemisah.trim($rowdb23x['SUBCODE04']).$pemisah.trim($rowdb23x['SUBCODE05']).$pemisah.trim($rowdb23x['SUBCODE06']).$pemisah.trim($rowdb23x['SUBCODE07']).$pemisah.trim($rowdb23x['SUBCODE08']);
		  echo $noBnx.". ".$subcode."<br>"; $noBnx++;}?></td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php 
                                echo $rowdb2['NO_MESIN'];
                             ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $rowdb2['INSROL']; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $rowdb2['INSKG'] ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo round($rowd['berat_awal'], 2); ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $prsn; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo round($rowd1['berat_awal'], 2); ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $prsn1; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo round($rowd2['berat_awal'], 2); ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $prsn2; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php
                            if ($rowdb22 != null) {
                                echo round(($rowdb22['KGSISA'] ?? 0), 2);
                            }
                            ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo round($rowdb21['KGPAKAI'], 2); ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $losskg; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $prsnLoss; ?>
                        </td>
                        <td style="border:1px solid black; padding:0.5em;">
                            <?php echo $TprsnLoss; ?>
                        </td>
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