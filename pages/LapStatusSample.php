<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$Stts	= isset($_POST['stts']) ? $_POST['stts'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Tgl Selesai</h3>

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
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
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
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data PO Selesai</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example12" class="table table-sm table-bordered table-striped" style="font-size: 11px;" width="100%">
                  <thead>
                  <tr>
								<th width="2%">#</th>
								<th width="9%"><div align="center" valign="middle">Project</div></th>
								<th width="12%"><div align="center" valign="middle">No. Demand</div></th>
								<th width="9%"><div align="center" valign="middle">Langganan</div></th>
								<th width="13%"><div align="center" valign="middle">Tgl Terima PO</div></th>
								<th width="12%"><div align="center" valign="middle">Tgl Pasang Benang</div></th>
								<th width="13%"><div align="center">Tgl Mulai Setting / Jam</div></th>
								<th width="13%"><div align="center">Tgl Selesai Setting / Jam</div></th>
								<th width="11%"><div align="center">Tgl ACC / Jam</div></th>
								<th width="14%"><div align="center">Greige Selesai</div></th>
								<th width="14%"><div align="center">Tgl Kirim Greige</div></th>
								<th width="5%"><div align="center" valign="middle">Target Delivery (Sample S)</div></th>
								<th width="5%"><div align="center" valign="middle">Target (Hari)</div></th>
								<th width="5%"><div align="center" valign="middle">Aktual (Hari)</div></th>
								<th width="5%"><div align="center" valign="middle">Keterangan</div></th>
								<th width="5%"><div align="center" valign="middle">Jenis Kain</div></th>
								<th width="5%"><div align="center" valign="middle">Mesin</div></th>
								<th width="5%"><div align="center" valign="middle">Quality Order</div></th>
								<th width="5%"><div align="center" valign="middle">Quality Produksi</div></th>
								<th width="5%"><div align="center" valign="middle">Pencapaian</div></th>
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
$sqlDB2 =" 
SELECT i.PRODUCTIONDEMANDCODE,i.PRODUCTIONORDERCODE,i.LEGALNAME1,
CASE WHEN i.PROJECTCODE <> '' THEN i.PROJECTCODE ELSE i.ORIGDLVSALORDLINESALORDERCODE  END  AS PROJECTCODE,
i.SUBCODE02,i.SUBCODE03,i.SUBCODE04,
VARCHAR_FORMAT(i.FINALEFFECTIVEDATE,'YYYY-MM-DD') AS TGLSELESAI,
ad.VALUESTRING AS NO_MESIN, SUM(pd.USERPRIMARYQUANTITY) AS QTYORDER,
SUM(pd.ENTEREDUSERPRIMARYQUANTITY) AS QTYPRO,pd.ORDERDATE,
u.SHORTDESCRIPTION,u.SEARCHDESCRIPTION,u.LONGDESCRIPTION,ad1.VALUEDATE AS RMPREQDATE, ad2.VALUEDATE AS RMPREQDATETO,f.SUMMARIZEDDESCRIPTION,ad3.VALUESTRING AS NOTESCH
,ad4.VALUEDATE AS TGLTERIMAPOSAMPLE,ad5.VALUETIMESTAMP AS TGLMULAISETELSAMPLE,ad6.VALUETIMESTAMP AS TGLSELESAISETELSAMPLE,ad7.VALUETIMESTAMP AS TGLACCSAMPLE,ad8.VALUEINT AS TARGETSAMPLE
,ad9.VALUEDATE AS TARGETDEVSAMPLE
FROM ITXVIEWHEADERKNTORDER i 
LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE = i.PRODUCTIONDEMANDCODE	
LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
LEFT OUTER JOIN ADSTORAGE ad1 ON ad1.UNIQUEID = pd.ABSUNIQUEID AND ad1.NAMENAME ='RMPReqDate'
LEFT OUTER JOIN ADSTORAGE ad2 ON ad2.UNIQUEID = pd.ABSUNIQUEID AND ad2.NAMENAME ='RMPGreigeReqDateTo'
LEFT OUTER JOIN ADSTORAGE ad3 ON ad3.UNIQUEID = pd.ABSUNIQUEID AND ad3.NAMENAME ='NoteSch'
LEFT OUTER JOIN ADSTORAGE ad4 ON ad4.UNIQUEID = pd.ABSUNIQUEID AND ad4.NAMENAME ='TglTerimaPOsample'
LEFT OUTER JOIN ADSTORAGE ad5 ON ad5.UNIQUEID = pd.ABSUNIQUEID AND ad5.NAMENAME ='TglMulaiSetelsample'
LEFT OUTER JOIN ADSTORAGE ad6 ON ad6.UNIQUEID = pd.ABSUNIQUEID AND ad6.NAMENAME ='TglSelesaiSetelsample'
LEFT OUTER JOIN ADSTORAGE ad7 ON ad7.UNIQUEID = pd.ABSUNIQUEID AND ad7.NAMENAME ='TglACCsample'
LEFT OUTER JOIN ADSTORAGE ad8 ON ad8.UNIQUEID = pd.ABSUNIQUEID AND ad8.NAMENAME ='Targetsample'
LEFT OUTER JOIN ADSTORAGE ad9 ON ad9.UNIQUEID = pd.ABSUNIQUEID AND ad9.NAMENAME ='DevSamDelivery'
LEFT OUTER JOIN USERGENERICGROUP u ON u.CODE=ad.VALUESTRING 
LEFT OUTER JOIN FULLITEMKEYDECODER f ON pd.COMPANYCODE = f.COMPANYCODE AND pd.FULLITEMIDENTIFIER = f.IDENTIFIER
WHERE  i.PROGRESSSTATUS='$Stts'  AND i.ITEMTYPEAFICODE ='KGF' $Tgl 
GROUP BY i.PRODUCTIONDEMANDCODE,i.FINALEFFECTIVEDATE,ad.VALUESTRING,  
i.LEGALNAME1,i.SUBCODE02,i.SUBCODE03,i.SUBCODE04,i.PROJECTCODE,
i.PRODUCTIONORDERCODE,pd.ORDERDATE,
u.SHORTDESCRIPTION,u.SEARCHDESCRIPTION,u.LONGDESCRIPTION,ad1.VALUEDATE,ad2.VALUEDATE,f.SUMMARIZEDDESCRIPTION,i.ORIGDLVSALORDLINESALORDERCODE,ad3.VALUESTRING
,ad4.VALUEDATE,ad5.VALUETIMESTAMP,ad6.VALUETIMESTAMP,ad7.VALUETIMESTAMP,ad8.VALUEINT,ad9.VALUEDATE
";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$no=1;   
$c=0;
$prsn=0;
$prsn1=0;
$prsn2=0;					  
 while ($rowdb2 = db2_fetch_assoc($stmt)) { 	 	
	 
$sqlDB21 =" SELECT INSPECTIONSTARTDATETIME  FROM  
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND COMPANYCODE='100' 
ORDER BY INSPECTIONSTARTDATETIME ASC LIMIT 1";	
$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
$rowdb21 = db2_fetch_assoc($stmt1);	 
	 
$sqlDB22 =" SELECT COUNT(WEIGHTREALNET ) AS JML, SUM(WEIGHTREALNET ) AS JQTY FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF'";	
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);
	 
	 	$awalDY  = strtotime($rowdb2['TGLSELESAI']);
		$akhirDY = strtotime($rowdb2['RMPREQDATE']);
		$diffDY  = ($akhirDY - $awalDY);
		$tjamDY  = round($diffDY/(60 * 60),2);
		$hariDY  = round($tjamDY/24);
	 
	 	$awalDY1  = strtotime($rowdb2['TGLSELESAI']);
		$akhirDY1 = strtotime($rowdb2['RMPREQDATETO']);
		$diffDY1  = ($akhirDY1 - $awalDY1);
		$tjamDY1  = round($diffDY1/(60 * 60),2);
		$hariDY1  = round($tjamDY1/24);	
	 
	 	if($rowdb2['RMPREQDATETO']!=""){
			$Delay=$hariDY1;
		}else if($rowdb2['RMPREQDATE']!=""){
			$Delay=$hariDY;	
		}else{
			$Delay="";
		}
		$sqlDB23 = " SELECT a.VALUESTRING AS MESIN,i.PROVISIONALCOUNTERCODE,i2.INTERNALREFERENCEDATE,i2.INTDOCUMENTPROVISIONALCODE , i2.ORDERLINE,i2.EXTERNALREFERENCE,  s.PROJECTCODE,
		i.DESTINATIONWAREHOUSECODE, i2.SUBCODE01, i2.SUBCODE02, i2.SUBCODE03, i2.SUBCODE04,f.SUMMARIZEDDESCRIPTION,
		SUM(s.BASEPRIMARYQUANTITY) AS KG, COUNT(s.ITEMELEMENTCODE) AS ROL, i2.RECEIVINGDATE, s.LOGICALWAREHOUSECODE,
		s.WHSLOCATIONWAREHOUSEZONECODE, s.LOTCODE, s.CREATIONUSER  
		FROM INTERNALDOCUMENT i 
		LEFT OUTER JOIN INTERNALDOCUMENTLINE i2 ON i.PROVISIONALCODE = i2.INTDOCUMENTPROVISIONALCODE 
		LEFT OUTER JOIN STOCKTRANSACTION s ON i2.INTDOCUMENTPROVISIONALCODE =s.ORDERCODE AND i2.ORDERLINE =s.ORDERLINE 
		LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE=s.LOTCODE
		LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.NAMENAME ='MachineNo'
		LEFT OUTER JOIN FULLITEMKEYDECODER f ON s.FULLITEMIDENTIFIER = f.IDENTIFIER
		WHERE s.PHYSICALWAREHOUSECODE ='M50' AND s.LOTCODE = '".$rowdb2['PRODUCTIONDEMANDCODE']."'
		GROUP BY 
		a.VALUESTRING,i.PROVISIONALCOUNTERCODE,i2.INTERNALREFERENCEDATE,i2.INTDOCUMENTPROVISIONALCODE ,
		i2.ORDERLINE, s.PROJECTCODE,
		i.DESTINATIONWAREHOUSECODE, i2.SUBCODE01,
		i2.SUBCODE02, i2.SUBCODE03,i2.EXTERNALREFERENCE,
		i2.SUBCODE04,f.SUMMARIZEDDESCRIPTION, i2.RECEIVINGDATE,
		s.LOGICALWAREHOUSECODE, s.WHSLOCATIONWAREHOUSEZONECODE, s.LOTCODE, s.CREATIONUSER  ";
			$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
			$rowdb23 = db2_fetch_assoc($stmt3);
		$sqlDB24 = " SELECT 
			STOCKTRANSACTION.TRANSACTIONNUMBER,
			STOCKTRANSACTION.ORDERCODE,
			STOCKTRANSACTION.LOGICALWAREHOUSECODE,
			STOCKTRANSACTION.DECOSUBCODE01,
			STOCKTRANSACTION.DECOSUBCODE02,
			STOCKTRANSACTION.DECOSUBCODE03,
			STOCKTRANSACTION.DECOSUBCODE04,
			STOCKTRANSACTION.DECOSUBCODE05,
			STOCKTRANSACTION.DECOSUBCODE06,
			STOCKTRANSACTION.DECOSUBCODE07,
			STOCKTRANSACTION.DECOSUBCODE08,
			STOCKTRANSACTION.TRANSACTIONDATE,
			STOCKTRANSACTION.LOTCODE,
			STOCKTRANSACTION.CREATIONUSER,
			COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_DUS,
			SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
			SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
			MCN.NOMC,
			FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
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
		WHERE (STOCKTRANSACTION.ITEMTYPECODE ='GYR' OR STOCKTRANSACTION.ITEMTYPECODE ='DYR') and (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M501' OR STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904') AND
		STOCKTRANSACTION.ONHANDUPDATE >1 AND ORDERCODE='".$rowdb2['PRODUCTIONORDERCODE']."' AND NOT ORDERCODE IS NULL
		GROUP BY
			STOCKTRANSACTION.TRANSACTIONNUMBER,
			STOCKTRANSACTION.ORDERCODE,
			STOCKTRANSACTION.LOGICALWAREHOUSECODE,
			STOCKTRANSACTION.DECOSUBCODE01,
			STOCKTRANSACTION.DECOSUBCODE02,
			STOCKTRANSACTION.DECOSUBCODE03,
			STOCKTRANSACTION.DECOSUBCODE04,
			STOCKTRANSACTION.DECOSUBCODE05,
			STOCKTRANSACTION.DECOSUBCODE06,
			STOCKTRANSACTION.DECOSUBCODE07,
			STOCKTRANSACTION.DECOSUBCODE08,
			STOCKTRANSACTION.TRANSACTIONDATE,
			STOCKTRANSACTION.LOTCODE,
			STOCKTRANSACTION.CREATIONUSER,
			MCN.NOMC,
			FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
		 ";
			$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
			$rowdb24 = db2_fetch_assoc($stmt4);	
	   ?>
     <tr>
							  <td><?php echo $no; ?></td>
							  <td><?php echo $rowdb2['PROJECTCODE']; ?></td>
			  				  <td><?php echo $rowdb2['PRODUCTIONDEMANDCODE']; ?></td>
			  				  <td><?php echo $rowdb2['LEGALNAME1']; ?></td>
							  <td align="center"><?php echo $rowdb2['TGLTERIMAPOSAMPLE']; ?></td>
							  <td align="center"><?php echo $rowdb24['TRANSACTIONDATE']; ?></td>
							  <td align="center"><?php echo !empty($rowdb2['TGLMULAISETELSAMPLE']) ? date('Y-m-d H:i:s', strtotime($rowdb2['TGLMULAISETELSAMPLE']))  : ''; ?></td>
							  <td align="center"><?php echo !empty($rowdb2['TGLSELESAISETELSAMPLE']) ? date('Y-m-d H:i:s', strtotime($rowdb2['TGLSELESAISETELSAMPLE']))  : ''; ?></td>
							  <td align="center"><?php echo !empty($rowdb2['TGLACCSAMPLE']) ? date('Y-m-d H:i:s', strtotime($rowdb2['TGLACCSAMPLE']))  : ''; ?></td>
							  <td align="center"><?php echo $rowdb2['TGLSELESAI']; ?></td>
							  <td align="center"><?php echo $rowdb23['INTERNALREFERENCEDATE']; ?></td>
							  <td align="center"><?php 
								if (!empty($rowdb2['TGLTERIMAPOSAMPLE']) && !empty($rowdb2['TARGETSAMPLE'])) {
									echo date('Y-m-d', strtotime($rowdb2['TGLTERIMAPOSAMPLE'] . ' + ' . $rowdb2['TARGETSAMPLE'] . ' days'));
								} else {
									echo '';
								}
								?></td>							  
							  <td align="center"><?php echo $rowdb2['TARGETSAMPLE']; ?></td>
							  <td align="center">
								<?php 
								if (!empty($rowdb23['INTERNALREFERENCEDATE']) && !empty($rowdb2['TGLTERIMAPOSAMPLE'])) {
									$date1 = strtotime($rowdb23['INTERNALREFERENCEDATE']);
									$date2 = strtotime($rowdb2['TGLTERIMAPOSAMPLE']);
									$diff = ($date1 - $date2) / (60 * 60 * 24); // Konversi dari detik ke hari
									echo $diff;
								} else {
									echo '';
								}
								?>
							  </td>
							  <td><?php  if($Delay!=""){if($Delay<0){ echo "<small class='badge badge-danger'><i class='far fa-clock text-white blink_me'></i> Delay ".abs($Delay)." Hari</small>";}} ?></td>
							  <td><?php echo $rowdb2['SUMMARIZEDDESCRIPTION']; ?></td>
							  <td><?php echo $rowdb2['SEARCHDESCRIPTION'];  ?></td>
							  <td align="right"><?php echo number_format(round($rowdb2['QTYORDER'],2),2); ?></td>
	              <td align="right"><?php echo number_format(round($rowdb22['JQTY'],2),2); ?></td>
							  <td><?php
// Ambil data tanggal dari database
$tgl_kirim_greige = $rowdb23['INTERNALREFERENCEDATE'];
$tgl_terima_po = $rowdb2['TGLTERIMAPOSAMPLE'];
$target_sample = $rowdb2['TARGETSAMPLE'];

// Hitung Tgl Terima PO + Target (Hari)
if (!empty($tgl_terima_po) && !empty($target_sample)) {
    $tgl_target = date('Y-m-d', strtotime($tgl_terima_po . ' + ' . $target_sample . ' days'));
} else {
    $tgl_target = "";
}

// Menentukan status
if(empty($tgl_target) or empty($tgl_kirim_greige)){
	$status = "";
} elseif ($tgl_kirim_greige > $tgl_target) {
    $status = "<small class='badge badge-danger'>Tidak Tercapai</small>";
} elseif ($tgl_kirim_greige <= $tgl_target) {
    $status = "<small class='badge badge-success'>Tercapai</small>";
} else {
    $status = "";
}
	 echo $status;
	 ?></td>
						  </tr>
     <!--
	  <tr>
	    <td><?php echo $no; ?></td>
	    <td align="center"><?php echo $rowdb2['ORDERDATE']; ?></td>
	    <td><?php echo $rowdb2['LEGALNAME1']; ?></td>
	    <td align="center"><?php echo $rowdb2['PROJECTCODE']; ?></td>
	    <td align="center"><?php echo $rowdb2['PRODUCTIONDEMANDCODE']; ?></td>
	    <td><?php echo $rowdb2['SUMMARIZEDDESCRIPTION']; ?></td>
	    <td><?php echo trim($rowdb2['SUBCODE02']).trim($rowdb2['SUBCODE03'])." ".trim($rowdb2['SUBCODE04']); ?></td>
	    <td align="center"><?php echo $rowdb2['RMPREQDATE']; ?></td>
	    <td align="center"><?php echo $rowdb2['RMPREQDATETO']; ?></td>
	    <td align="center"><?php echo substr($rowdb21['INSPECTIONSTARTDATETIME'],0,10); ?></td>
	    <td align="center"><?php echo $rowdb2['TGLSELESAI']; ?></td>
	    <td align="right"><?php echo number_format(round($rowdb2['QTYORDER'],2),2); ?></td>
	    <td align="right"><?php echo number_format(round($rowdb22['JQTY'],2),2); ?></td>
	    <td align="center"><?php echo $rowdb2['NO_MESIN'];  ?></td>
	    <td align="center"><?php echo $rowdb2['SEARCHDESCRIPTION'];  ?></td>
	    <td align="center"><?php echo $rowdb2['SHORTDESCRIPTION'];  ?></td>
	    <td><?php  if($Delay!=""){if($Delay<0){ echo "<small class='badge badge-danger'><i class='far fa-clock text-white blink_me'></i> Delay ".abs($Delay)." Hari</small>";}} ?></td>
	    <td><?php echo $rowdb2['NOTESCH'];  ?></td>
      </tr>	
  -->			  
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
<?php 
if($_POST['mutasikain']=="MutasiKain"){
	
function mutasiurut(){
include "koneksi.php";		
$format = "20".date("ymd");
$sql=mysqli_query($con,"SELECT no_mutasi FROM tbl_mutasi_kain WHERE substr(no_mutasi,1,8) like '%".$format."%' ORDER BY no_mutasi DESC LIMIT 1 ") or die (mysql_error());
$d=mysqli_num_rows($sql);
if($d>0){
$r=mysqli_fetch_array($sql);
$d=$r['no_mutasi'];
$str=substr($d,8,2);
$Urut = (int)$str;
}else{
$Urut = 0;
}
$Urut = $Urut + 1;
$Nol="";
$nilai=2-strlen($Urut);
for ($i=1;$i<=$nilai;$i++){
$Nol= $Nol."0";
}
$tidbr =$format.$Nol.$Urut;
return $tidbr;
}
$nomid=mutasiurut();	

$sql1=mysqli_query($con,"SELECT *,count(b.transid) as jmlrol,a.transid as kdtrans FROM tbl_mutasi_kain a 
LEFT JOIN tbl_prodemand b ON a.transid=b.transid 
WHERE isnull(a.no_mutasi) AND date_format(a.tgl_buat ,'%Y-%m-%d')='$Awal' AND a.gshift='$Gshift' 
GROUP BY a.transid");
$n1=1;
$noceklist1=1;	
while($r1=mysqli_fetch_array($sql1)){	
	if($_POST['cek'][$n1]!='') 
		{
		$transid1 = $_POST['cek'][$n1];
		mysqli_query($con,"UPDATE tbl_mutasi_kain SET
		no_mutasi='$nomid',
		tgl_mutasi=now()
		WHERE transid='$transid1'
		");
		}else{
			$noceklist1++;
	}
	$n1++;
	}
if($noceklist1==$n1){
	echo "<script>
  	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: false,
      timer: 2000
    });
	Toast.fire({
        icon: 'info',
        title: 'Data tidak ada yang di Ceklist',
		
      })
  });
  
</script>";	
}else{	
echo "<script>
	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: true,
      timer: 6000
    });
	Toast.fire({
  title: 'Data telah di Mutasi',
  text: 'klik OK untuk Cetak Bukti Mutasi',
  icon: 'success',  
}).then((result) => {
  if (result.isConfirmed) {
    	window.open('pages/cetak/cetak_mutasi_ulang.php?mutasi=$nomid', '_blank');
  }
})
  });
	</script>";
	
/*echo "<script>
	Swal.fire({
  title: 'Data telah di Mutasi',
  text: 'klik OK untuk Cetak Bukti Mutasi',
  icon: 'success',  
}).then((result) => {
  if (result.isConfirmed) {
    	window.location='Mutasi';
  }
});
	</script>";	*/
}
}
?>