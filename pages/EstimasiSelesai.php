<?php
$DemandKGF	= isset($_POST['demandkgf']) ? $_POST['demandkgf'] : '';
$JmlMSN		= isset($_POST['jmlmsn']) ? $_POST['jmlmsn'] : '';
$Sts		= isset($_POST['sts']) ? $_POST['sts'] : '';
$Awal		= isset($_POST['tgl_mulai']) ? $_POST['tgl_mulai'] : '';
?>    
<form role="form" method="post" enctype="multipart/form-data" name="form1">
	<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Estimasi Selesai</h3>
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
<?php 
$sqlDB2 =" 
SELECT  p.USERPRIMARYQUANTITY, X.SUBCODE02, X.SUBCODE03, TRIM(X.PROJECT) AS PROJECT, TRIM(X.LEGALNAME1) AS LEGALNAME1, TRIM(u.LONGDESCRIPTION) AS LONGDESCRIPTION, CONCAT (TRIM(X.SUBCODE02),TRIM(X.SUBCODE03)) AS HANGER, TRIM(X.SUBCODE04) AS CELUP, X.SUMMARIZEDDESCRIPTION FROM 
(SELECT LEGALNAME1, CODE, CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END  AS PROJECT, 
SUMMARIZEDDESCRIPTION, SUBCODE02,SUBCODE03,SUBCODE04, SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWHEADERKNTORDER 
WHERE ITEMTYPEAFICODE ='KGF' AND (PROGRESSSTATUS='2' OR PROGRESSSTATUS='6') 
GROUP BY CODE,LEGALNAME1, SUBCODE02,SUBCODE03,SUBCODE04,SUMMARIZEDDESCRIPTION,CURRENT_TIMESTAMP,(CASE WHEN PROJECTCODE <> '' THEN PROJECTCODE ELSE ORIGDLVSALORDLINESALORDERCODE  END)) X 
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE=X.CODE 
LEFT OUTER JOIN USERGENERICGROUP u ON p.SUBCODE05=u.CODE 
WHERE X.CODE='$DemandKGF'
";	
$stmt2   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$rowdb2  = db2_fetch_assoc($stmt2);			  
			  
$sqlDB23 ="SELECT ADSTORAGE.NAMENAME,ADSTORAGE.FIELDNAME,(ADSTORAGE.VALUEDECIMAL* 24) AS STDRAJUT 
FROM DB2ADMIN.PRODUCT PRODUCT LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON PRODUCT.ABSUNIQUEID=ADSTORAGE.UNIQUEID 
WHERE ADSTORAGE.NAMENAME='ProductionRate' AND PRODUCT.ITEMTYPECODE='KGF' AND PRODUCT.SUBCODE02='$rowdb2[SUBCODE02]' AND 
PRODUCT.SUBCODE03='$rowdb2[SUBCODE03]' AND PRODUCT.COMPANYCODE='100' 
ORDER BY ADSTORAGE.FIELDNAME";	
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);
if($DemandKGF!=""){			  
$kRajut	 = $rowdb2['USERPRIMARYQUANTITY'];			  
$kHari	 = round($kRajut/(round($rowdb23['STDRAJUT']*$JmlMSN)),0);
$tglEst	 = date('Y-m-d', strtotime($kHari." days", strtotime($Awal)));	
}
?>			  
			  
			<div class="form-group row">
               <label for="shift" class="col-md-1">Demand KGF</label>               
			   <div class="col-md-2">  
                  <input type="text" name="demandkgf" class="form-control form-control-sm" id="demandkgf" value="<?php echo $DemandKGF; ?>">
			   </div>	
            </div>	
			<div class="form-group row">
               <label for="jmlmsn" class="col-md-1">Jumlah Mesin</label>               
			   <div class="col-md-2">  
                  <input type="number" name="jmlmsn" class="form-control form-control-sm" id="jmlmsn" value="<?php echo $JmlMSN; ?>" required>
			   </div>	
            </div>
			<div class="form-group row">
                    <label for="sts" class="col-md-1">Status</label>
					<div class="col-md-2"> 
                    <select name="sts" class="form-control form-control-sm"  autocomplete="off" required>
						<option value="">Pilih</option>
						<option value="normal" <?php if($Sts=="normal"){ echo "SELECTED"; }?>>Normal</option>
						<option value="urgent" <?php if($Sts=="urgent"){ echo "SELECTED"; }?>>Urgent</option>
					</select>	
                  </div>	
            </div>
			<div class="form-group row">
               <label for="tgl_mulai" class="col-md-1">Tgl Mulai</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_mulai" value="<?php echo $Awal;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>	
            </div>  
			<div class="form-group row">
                    <label for="sts" class="col-md-1">STD Kg</label>
					<div class="col-md-2"> 
                    <input type="text" name="stdkg" class="form-control form-control-sm" id="stdkg" value="<?php echo $rowdb23['STDRAJUT']; ?>">	
                  </div>	
            </div>
			<div class="form-group row">
                    <label for="sts" class="col-md-1">Qty</label>
					<div class="col-md-2"> 
                    <input type="text" name="stdkg" class="form-control form-control-sm" id="stdkg" value="<?php echo $rowdb2['USERPRIMARYQUANTITY']; ?>">	
                  </div>	
            </div>
			<div class="form-group row">
               <label for="tgl_selesai" class="col-md-1">Tgl Selesai</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_selesai" value="<?php echo $tglEst;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off">
                 </div>
			   </div>	
            </div>  
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>
</form>	
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