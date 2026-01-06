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
                <table id="example12" class="table table-sm table-bordered table-striped" style="font-size: 13px;" width="100%">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>Tgl Terima</th>
                    <th>Langganan</th>
                    <th>ProjectCode</th>
                    <th>No PO</th>
                    <th>Jenis Kain</th>
                    <th>NoArt</th>
                    <th>Tgl Delivery 1</th>
                    <th>Tgl Delivery 2</th>
                    <th>Tgl Mulai Rajut</th>
                    <th>Tgl Selesai</th>
                    <th>Qty Order</th>
                    <th>Qty Produksi</th>
                    <th>NoMC</th>
                    <th>No Mesin</th>
                    <th>Mesin</th>
                    <th>Keterangan</th>
                    <th>Analisa Delay</th>
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
FROM ITXVIEWHEADERKNTORDER i 
LEFT OUTER JOIN PRODUCTIONDEMAND pd ON pd.CODE = i.PRODUCTIONDEMANDCODE	
LEFT OUTER JOIN ADSTORAGE ad ON ad.UNIQUEID = pd.ABSUNIQUEID AND ad.NAMENAME ='MachineNo'
LEFT OUTER JOIN ADSTORAGE ad1 ON ad1.UNIQUEID = pd.ABSUNIQUEID AND ad1.NAMENAME ='RMPReqDate'
LEFT OUTER JOIN ADSTORAGE ad2 ON ad2.UNIQUEID = pd.ABSUNIQUEID AND ad2.NAMENAME ='RMPGreigeReqDateTo'
LEFT OUTER JOIN ADSTORAGE ad3 ON ad3.UNIQUEID = pd.ABSUNIQUEID AND ad3.NAMENAME ='NoteSch'
LEFT OUTER JOIN USERGENERICGROUP u ON u.CODE=ad.VALUESTRING 
LEFT OUTER JOIN FULLITEMKEYDECODER f ON pd.COMPANYCODE = f.COMPANYCODE AND pd.FULLITEMIDENTIFIER = f.IDENTIFIER
WHERE  i.PROGRESSSTATUS='$Stts'  AND i.ITEMTYPEAFICODE ='KGF' $Tgl 
GROUP BY i.PRODUCTIONDEMANDCODE,i.FINALEFFECTIVEDATE,ad.VALUESTRING,  
i.LEGALNAME1,i.SUBCODE02,i.SUBCODE03,i.SUBCODE04,i.PROJECTCODE,
i.PRODUCTIONORDERCODE,pd.ORDERDATE,
u.SHORTDESCRIPTION,u.SEARCHDESCRIPTION,u.LONGDESCRIPTION,ad1.VALUEDATE,ad2.VALUEDATE,f.SUMMARIZEDDESCRIPTION,i.ORIGDLVSALORDLINESALORDERCODE,ad3.VALUESTRING
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

	   ?>
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
