<?php
$Zone	= isset($_POST['zone']) ? $_POST['zone'] : '';
$Lokasi	= isset($_POST['lokasi']) ? $_POST['lokasi'] : '';
$Lot	= isset($_POST['lot']) ? $_POST['lot'] : '';
$Knt	= isset($_POST['knt']) ? $_POST['knt'] : '';
$Code1	= isset($_POST['code1']) ? $_POST['code1'] : '';
$Code2	= isset($_POST['code2']) ? $_POST['code2'] : '';
$Code3	= isset($_POST['code3']) ? $_POST['code3'] : '';
$Code4	= isset($_POST['code4']) ? $_POST['code4'] : '';
$Code5	= isset($_POST['code5']) ? $_POST['code5'] : '';
$Code6	= isset($_POST['code6']) ? $_POST['code6'] : '';
$Code7	= isset($_POST['code7']) ? $_POST['code7'] : '';
$Code8	= isset($_POST['code8']) ? $_POST['code8'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1"> 
		<div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Filter Data Benang Stok</h3>

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
               <label for="kode" class="col-md-1">Code</label>
               <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code1; ?>" name="code1" placeholder="Count/Ply" autocomplete="off">
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code2; ?>" name="code2" placeholder="Composition" autocomplete="off">
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code3; ?>" name="code3" placeholder="Composition %" autocomplete="off">
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code4; ?>" name="code4" placeholder="Technology" autocomplete="off">
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code5; ?>" name="code5" placeholder="Twist" autocomplete="off">
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code6; ?>" name="code6" placeholder="Elastan Type" autocomplete="off">
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code7; ?>" name="code7" placeholder="Variant/Grade" autocomplete="off">
			   </div>
			   <div class="col-md-1">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $Code8; ?>" name="code8" placeholder="Color" autocomplete="off">
			   </div>	 
            </div> 
			<div class="form-group row">
               <label for="lot" class="col-md-1"> </label>
			   <div class="col-md-1">  
                 <select name="knt" class="form-control form-control-sm" id="knt">
				   <option value="">Pilih</option>
				   <option value="ALL" <?php if($Knt=="ALL"){echo "SELECTED";} ?>>ALL</option>	 
				   <option value="P501" <?php if($Knt=="P501"){echo "SELECTED";} ?>>LT1</option>
				   <option value="M904" <?php if($Knt=="M904"){echo "SELECTED";} ?>>LT2</option>
				   <option value="M011" <?php if($Knt=="M011"){echo "SELECTED";} ?>>GDB</option>	 
				   </select>
			   </div>	
               <div class="col-md-1">  
                    <input name="lot" value="<?php echo $Lot;?>" type="text" class="form-control form-control-sm" id="Lot" placeholder="Lot" autocomplete="off">
			   </div>
			   <div class="col-md-1">  
                    <input name="zone" value="<?php echo $Zone;?>" type="text" class="form-control form-control-sm" id="Zone" placeholder="Zone" autocomplete="off">
			   </div>	
			   <div class="col-md-1">  
                 <input type="lokasi" class="form-control form-control-sm" value="<?php echo $Lokasi; ?>" name="lokasi" placeholder="Lokasi" autocomplete="off">
			   </div>			   	
            </div>  		  
				 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>	
<?php if($Knt!=""){ ?>			
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Stok Benang</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table id="example12" width="100%" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">TGL</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">KNITT</th>
                    <th valign="middle" style="text-align: center">No PO</th>
                    <th valign="middle" style="text-align: center">Tipe</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Kode Supplier</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Elements</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    <th valign="middle" style="text-align: center">Tgl Prod</th>
                    <th valign="middle" style="text-align: center">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
	if($Knt=="P501"){
		$Wknt=" BALANCE.LOGICALWAREHOUSECODE='P501' AND ";
	}else if($Knt=="M904"){
		$Wknt=" BALANCE.LOGICALWAREHOUSECODE='M904' AND ";
	}else if($Knt=="M011"){
		$Wknt=" BALANCE.LOGICALWAREHOUSECODE='M011' AND ";	
	}else{
		$Wknt=" (BALANCE.LOGICALWAREHOUSECODE='P501' OR BALANCE.LOGICALWAREHOUSECODE='M904' OR BALANCE.LOGICALWAREHOUSECODE='M011') AND  ";
	}	
	if($Zone!=""){
		$WZone = " BALANCE.WHSLOCATIONWAREHOUSEZONECODE LIKE '$Zone%' AND ";
	}else{
		$WZone = "";
	}
	 if($Lokasi!=""){		 
		/*if($Lokasi=="Z00" or $Lokasi=="Z99"){
			$WLokasi = " BALANCE.WAREHOUSELOCATIONCODE = 'Z001' AND ";			
		}else{ 			
			$WLokasi = " BALANCE.WAREHOUSELOCATIONCODE LIKE '$Lokasi%' AND ";
			}*/
		$WLokasi = " BALANCE.WAREHOUSELOCATIONCODE LIKE '$Lokasi%' AND "; 
	}else{
		$WLokasi = "";
	}
	if($Code1!=""){
		$WCode1 = " BALANCE.DECOSUBCODE01 LIKE '$Code1%' AND ";
	}else{
		$WCode1 = "";
	}
	if($Code2!=""){
		$WCode2 = " BALANCE.DECOSUBCODE02 LIKE '$Code2%' AND ";
	}else{
		$WCode2 = "";
	}
	if($Code3!=""){
		$WCode3 = " BALANCE.DECOSUBCODE03 LIKE '$Code3%' AND ";
	}else{
		$WCode3 = "";
	}
	if($Code4!=""){
		$WCode4 = " BALANCE.DECOSUBCODE04 LIKE '$Code4%' AND ";
	}else{
		$WCode4 = "";
	}
	if($Code5!=""){
		$WCode5 = " BALANCE.DECOSUBCODE05 LIKE '$Code5%' AND ";
	}else{
		$WCode5 = "";
	}
	if($Code6!=""){
		$WCode6 = " BALANCE.DECOSUBCODE06 LIKE '$Code6%' AND ";
	}else{
		$WCode6 = "";
	}
	if($Code7!=""){
		$WCode7 = " BALANCE.DECOSUBCODE07 LIKE '$Code7%' AND ";
	}else{
		$WCode7 = "";
	}
	if($Code8!=""){
		$WCode8 = " BALANCE.DECOSUBCODE08 LIKE '$Code8%' AND ";
	}else{
		$WCode8 = "";
	}
	if($Lot!=""){
		$Wlot = " BALANCE.LOTCODE LIKE '$Lot%' AND ";
	}else{
		$Wlot = "";
	}
	$sqlDB21 = " 
	SELECT COUNT(BASEPRIMARYQUANTITYUNIT) AS QTY_ROL,SUM(BASEPRIMARYQUANTITYUNIT) AS QTY_KG ,
	SUM(BASESECONDARYQUANTITYUNIT) AS QTY_CONES,WHSLOCATIONWAREHOUSEZONECODE,WAREHOUSELOCATIONCODE,LOGICALWAREHOUSECODE,ELEMENTSCODE,BALANCE.ITEMTYPECODE
		FROM DB2ADMIN.BALANCE BALANCE  
		WHERE $Wknt $WZone $Wlot $WLokasi $WCode1 $WCode2 $WCode3 $WCode4 $WCode5 $WCode6 $WCode7 $WCode8 (BALANCE.ITEMTYPECODE='GYR' OR BALANCE.ITEMTYPECODE='DYR')
		GROUP BY 
		BALANCE.ELEMENTSCODE,
		BALANCE.WHSLOCATIONWAREHOUSEZONECODE,
		BALANCE.WAREHOUSELOCATIONCODE,
		BALANCE.LOGICALWAREHOUSECODE,
		BALANCE.ITEMTYPECODE,
		BALANCE.DECOSUBCODE01,
		BALANCE.DECOSUBCODE02,
		BALANCE.DECOSUBCODE03,
		BALANCE.DECOSUBCODE04,
		BALANCE.DECOSUBCODE05,
		BALANCE.DECOSUBCODE06,
		BALANCE.DECOSUBCODE07,
		BALANCE.DECOSUBCODE08,
		BALANCE.LOTCODE
		ORDER BY 
		BALANCE.ITEMTYPECODE,
		BALANCE.DECOSUBCODE01,
		BALANCE.DECOSUBCODE02,
		BALANCE.DECOSUBCODE03,
		BALANCE.DECOSUBCODE04,
		BALANCE.DECOSUBCODE05,
		BALANCE.DECOSUBCODE06,
		BALANCE.DECOSUBCODE07,
		BALANCE.DECOSUBCODE08,
		BALANCE.LOTCODE,
		BALANCE.WHSLOCATIONWAREHOUSEZONECODE,
		BALANCE.WAREHOUSELOCATIONCODE
		"; 
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}						  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=$rowdb21['INTDOCUMENTPROVISIONALCODE']."-".$rowdb21['ORDERLINE'];
		
$sqlDB22 = "SELECT 
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
STOCKTRANSACTION.LOTCODE,  
STOCKTRANSACTION.TRANSACTIONDATE,
SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL,
SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE=STOCKTRANSACTION.ORDERCODE AND 
INTERNALDOCUMENTLINE.ORDERLINE=STOCKTRANSACTION.ORDERLINE 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE (NOT INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE IS NULL) AND (STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M904' or STOCKTRANSACTION.LOGICALWAREHOUSECODE ='P501') AND
STOCKTRANSACTION.ITEMELEMENTCODE ='$rowdb21[ELEMENTSCODE]' AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
GROUP BY
STOCKTRANSACTION.LOTCODE,
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.CREATIONUSER,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION";
$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
$rowdb22 = db2_fetch_assoc($stmt2);	
$bon=$rowdb22['INTDOCUMENTPROVISIONALCODE']."-".$rowdb22['ORDERLINE'];	
$kdbenang=$rowdb22['SUBCODE01']." ".$rowdb22['SUBCODE02']." ".$rowdb22['SUBCODE03']." ".$rowdb22['SUBCODE04']." ".$rowdb22['SUBCODE05']." ".$rowdb22['SUBCODE06']." ".$rowdb22['SUBCODE07']." ".$rowdb22['SUBCODE08'];

$sqlDBPRD = " SELECT ITTSUPPLIERPACKINGLISTBEAN.MANUFACTURINGDATE 
			FROM DB2ADMIN.ITTSUPPLIERPACKINGLISTBEAN ITTSUPPLIERPACKINGLISTBEAN
			WHERE ITTSUPPLIERPACKINGLISTBEAN.ITEMELEMENTCODE = '$rowdb21[ELEMENTSCODE]'";
						
$stmt3PRD = db2_exec($conn1, $sqlDBPRD, array('cursor' => DB2_SCROLLABLE));
$rD2PRD = db2_fetch_assoc($stmt3PRD);
		
$sqlDB24 = " SELECT s.*, a.VALUESTRING AS SP,a1.VALUESTRING AS NT,a2.VALUEDATE AS TG,  
ft.SUMMARIZEDDESCRIPTION FROM STOCKTRANSACTION s 
LEFT OUTER JOIN ADSTORAGE a ON s.ABSUNIQUEID=a.UNIQUEID AND a.NAMENAME='CodeSupp'                                          
LEFT OUTER JOIN ADSTORAGE a1 ON s.ABSUNIQUEID=a1.UNIQUEID AND a1.NAMENAME='NoteGYR'                                           
LEFT OUTER JOIN ADSTORAGE a2 ON s.ABSUNIQUEID=a2.UNIQUEID AND a2.NAMENAME='ProdDate' 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER ft ON s.FULLITEMIDENTIFIER = ft.IDENTIFIER
WHERE s.ITEMELEMENTCODE ='$rowdb21[ELEMENTSCODE]' 
ORDER BY s.TRANSACTIONDATE DESC, s.TRANSACTIONDETAILNUMBER DESC";
$stmt4   = db2_exec($conn1,$sqlDB24, array('cursor'=>DB2_SCROLLABLE));
$rowdb24 = db2_fetch_assoc($stmt4);
if($rowdb22['LOTCODE']!="") {$lot=$rowdb22['LOTCODE'];}else{$lot=$rowdb24['LOTCODE'];}
$kdbenang1=trim($rowdb24['DECOSUBCODE01'])." ".trim($rowdb24['DECOSUBCODE02'])." ".trim($rowdb24['DECOSUBCODE03'])." ".trim($rowdb24['DECOSUBCODE04'])." ".trim($rowdb24['DECOSUBCODE05'])." ".trim($rowdb24['DECOSUBCODE06'])." ".trim($rowdb24['DECOSUBCODE07'])." ".trim($rowdb24['DECOSUBCODE08']);			
if($rowdb22['LOTCODE']!=""){
$sqlDB23 = " SELECT a.SUPPLIERCODE, b2.LEGALNAME1  FROM LOT a
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA b ON a.SUPPLIERCODE = b.CODE 
LEFT OUTER JOIN BUSINESSPARTNER b2 ON b2.NUMBERID = b.BUSINESSPARTNERNUMBERID 
WHERE a.CODE='".$lot."' AND a.DECOSUBCODE01 ='".$rowdb22['SUBCODE01']."' 
AND a.DECOSUBCODE02 ='".$rowdb22['SUBCODE02']."' AND a.DECOSUBCODE03 ='".$rowdb22['SUBCODE03']."'
AND a.DECOSUBCODE04 ='".$rowdb22['SUBCODE04']."' AND a.DECOSUBCODE05 ='".$rowdb22['SUBCODE05']."'
AND a.DECOSUBCODE06 ='".$rowdb22['SUBCODE06']."' AND a.DECOSUBCODE07 ='".$rowdb22['SUBCODE07']."'
AND a.DECOSUBCODE08 ='".$rowdb22['SUBCODE08']."'
AND NOT a.SUPPLIERCODE IS NULL ";
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);	}
else{
$sqlDB23 = " SELECT a.SUPPLIERCODE, b2.LEGALNAME1  FROM LOT a
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA b ON a.SUPPLIERCODE = b.CODE 
LEFT OUTER JOIN BUSINESSPARTNER b2 ON b2.NUMBERID = b.BUSINESSPARTNERNUMBERID 
WHERE a.CODE='".$lot."' AND a.DECOSUBCODE01 ='".$rowdb24['DECOSUBCODE01']."' 
AND a.DECOSUBCODE02 ='".$rowdb24['DECOSUBCODE02']."' AND a.DECOSUBCODE03 ='".$rowdb24['DECOSUBCODE03']."'
AND a.DECOSUBCODE04 ='".$rowdb24['DECOSUBCODE04']."' AND a.DECOSUBCODE05 ='".$rowdb24['DECOSUBCODE05']."'
AND a.DECOSUBCODE06 ='".$rowdb24['DECOSUBCODE06']."' AND a.DECOSUBCODE07 ='".$rowdb24['DECOSUBCODE07']."'
AND a.DECOSUBCODE08 ='".$rowdb24['DECOSUBCODE08']."'
AND NOT a.SUPPLIERCODE IS NULL ";
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);	
	
}		
		
if (trim($rowdb21['LOGICALWAREHOUSECODE']) =="M904") { $knitt = "LT2"; }
else if(trim($rowdb21['LOGICALWAREHOUSECODE']) =="P501"){ $knitt = "LT1"; }
else if(trim($rowdb21['LOGICALWAREHOUSECODE']) =="M011"){ $knitt = "GDB"; }		
else if (trim($rowdb24['LOGICALWAREHOUSECODE']) =="M904") { $knitt = "LT2"; }
else if(trim($rowdb24['LOGICALWAREHOUSECODE']) =="P501"){ $knitt = "LT1"; }	
else if(trim($rowdb24['LOGICALWAREHOUSECODE']) =="M011"){ $knitt = "GDB"; }			

$sqlDB25 = " SELECT s.*, a.VALUESTRING AS SP,a1.VALUESTRING AS NT,a2.VALUEDATE AS TG,  
ft.SUMMARIZEDDESCRIPTION FROM STOCKTRANSACTION s 
LEFT OUTER JOIN ADSTORAGE a ON s.ABSUNIQUEID=a.UNIQUEID AND a.NAMENAME='CodeSupp'                                          
LEFT OUTER JOIN ADSTORAGE a1 ON s.ABSUNIQUEID=a1.UNIQUEID AND a1.NAMENAME='NoteGYR'                                           
LEFT OUTER JOIN ADSTORAGE a2 ON s.ABSUNIQUEID=a2.UNIQUEID AND a2.NAMENAME='ProdDate' 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER ft ON s.FULLITEMIDENTIFIER = ft.IDENTIFIER
WHERE s.ITEMELEMENTCODE ='$rowdb21[ELEMENTSCODE]' AND (NOT a.VALUESTRING IS NULL OR NOT a1.VALUESTRING IS NULL OR NOT a2.VALUESTRING IS NULL)
ORDER BY s.TRANSACTIONDATE DESC, s.TRANSACTIONDETAILNUMBER DESC";
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);

/*$sqlDB26 = " SELECT s.*, a.VALUESTRING AS SP,a1.VALUESTRING AS NT,a2.VALUEDATE AS TG,  
ft.SUMMARIZEDDESCRIPTION FROM STOCKTRANSACTION s 
LEFT OUTER JOIN ADSTORAGE a ON s.ABSUNIQUEID=a.UNIQUEID AND a.NAMENAME='CodeSupp'                                          
LEFT OUTER JOIN ADSTORAGE a1 ON s.ABSUNIQUEID=a1.UNIQUEID AND a1.NAMENAME='NoteGYR'                                           
LEFT OUTER JOIN ADSTORAGE a2 ON s.ABSUNIQUEID=a2.UNIQUEID AND a2.NAMENAME='ProdDate' 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER ft ON s.FULLITEMIDENTIFIER = ft.IDENTIFIER
WHERE s.ITEMELEMENTCODE ='$rowdb21[ELEMENTSCODE]' AND (NOT a.VALUESTRING IS NULL OR NOT a1.VALUESTRING IS NULL OR NOT a2.VALUESTRING IS NULL)
ORDER BY s.TRANSACTIONDATE DESC, s.TRANSACTIONDETAILNUMBER DESC";
$stmt6   = db2_exec($conn1,$sqlDB26, array('cursor'=>DB2_SCROLLABLE));
$rowdb26 = db2_fetch_assoc($stmt6);*/		
		
$posLot=strpos($lot,"+");
if($posLot>0){
	$lotP=substr($lot,0,$posLot);
}else{
	$lotP=$lot;
}		
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php if($rowdb22['TRANSACTIONDATE']!=""){echo $rowdb22['TRANSACTIONDATE'];}else{ echo $rowdb24['TRANSACTIONDATE']; } ?></td>
	  <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb22['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb22['ORDERLINE'])."-".trim($rowdb22['TRANSACTIONDATE']); ?>" class="show_detail"><?php echo $bon; ?></a></td>
      <td style="text-align: center"><?php echo $knitt; ?></td>
      <td style="text-align: center"><?php if(trim($rowdb22['EXTERNALREFERENCE'])!=""){echo $rowdb22['EXTERNALREFERENCE'];}else{$rowdb24['PRODUCTIONORDERCODE'];} ?></td>
      <td><?php if($rowdb21['ITEMTYPECODE']!="") {echo $rowdb21['ITEMTYPECODE'];}else{echo $rowdb24['ITEMTYPECODE'];} ?></td>
      <td><?php if(trim($kdbenang)!=""){echo $kdbenang;}else{ echo $kdbenang1;} ?></td>
      <td style="text-align: left"><?php if($rowdb22['SUMMARIZEDDESCRIPTION']!=""){echo $rowdb22['SUMMARIZEDDESCRIPTION'];}else{echo $rowdb24['SUMMARIZEDDESCRIPTION'];} ?></td>
      <td style="text-align: center"><?php echo $rowdb23['SUPPLIERCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb23['LEGALNAME1']; ?></td>
      <td style="text-align: center"><?php echo $lotP; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['ELEMENTSCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      <td><?php if($rowdb25['TG']!=""){echo date('d-m-Y', strtotime($rowdb25['TG']));}else if($rD2PRD['MANUFACTURINGDATE']!=""){ echo date('d-m-Y', strtotime($rD2PRD['MANUFACTURINGDATE'])); } ?></td>
      <td><?php echo $rowdb25['SP']." - ".$rowdb25['NT']; ?></td>
      </tr>	  				  
	<?php 
	 $no++; 
		$tRol+=$rowdb21['QTY_ROL'];
		$tCones+=$rowdb21['QTY_CONES'];
		$tKg+=$rowdb21['QTY_KG'];
	} ?>
				  </tbody>
      <tfoot>
	  <tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo $tRol; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tCones; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKg,2),2); ?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
					</tfoot>            
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
<?php } ?>	
   </form>		
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="DetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
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