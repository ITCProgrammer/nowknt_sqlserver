<?php
$Zone	= isset($_POST['zone']) ? $_POST['zone'] : '';
$Lokasi	= isset($_POST['lokasi']) ? $_POST['lokasi'] : '';
$Lot	= isset($_POST['lot']) ? $_POST['lot'] : '';
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
		<div class="card card-danger">
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
		<?php if($Lot!="" or $Zone!="" or $Lokasi!=""){ ?>
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
                    <th valign="middle" style="text-align: center">Qlt</th>
                    <th valign="middle" style="text-align: center">IT</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Desc</th>
                    <th valign="middle" style="text-align: center">Warna</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Lot Full</th>
                    <th valign="middle" style="text-align: center">SupplierCode</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cns</th>
                    <th valign="middle" style="text-align: center">Kg</th>
                    <th valign="middle" style="text-align: center">Tgl Masuk</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    <th valign="middle" style="text-align: center">Tgl Prod</th>
                    <th valign="middle" style="text-align: center">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
	if($Zone!=""){
		$WZone = " BALANCE.WHSLOCATIONWAREHOUSEZONECODE LIKE '$Zone%' AND ";
	}else{
		$WZone = "";
	}
	 if($Lokasi!=""){		 
		$WLokasi = " BALANCE.WAREHOUSELOCATIONCODE LIKE '$Lokasi%' AND "; 
	}else{
		$WLokasi = "";
	}
	if($Code1!=""){
		$WCode1 = " BALANCE.DECOSUBCODE01 LIKE '$Code1%' AND ";
		$WCode11 = " AND STK.DECOSUBCODE01 LIKE '$Code1%' ";
	}else{
		$WCode1 = "";
		$WCode11 = "";
	}
	if($Code2!=""){
		$WCode2 = " BALANCE.DECOSUBCODE02 LIKE '$Code2%' AND ";
		$WCode12 = " AND STK.DECOSUBCODE02 LIKE '$Code2%' ";
	}else{
		$WCode2 = "";
		$WCode12 = "";
	}
	if($Code3!=""){
		$WCode3 = " BALANCE.DECOSUBCODE03 LIKE '$Code3%' AND ";
		$WCode13 = " AND STK.DECOSUBCODE03 LIKE '$Code3%' ";
	}else{
		$WCode3 = "";
		$WCode13 = "";
	}
	if($Code4!=""){
		$WCode4 = " BALANCE.DECOSUBCODE04 LIKE '$Code4%' AND ";
		$WCode14 = " AND STK.DECOSUBCODE04 LIKE '$Code4%' ";
	}else{
		$WCode4 = "";
		$WCode14 = "";
	}
	if($Code5!=""){
		$WCode5 = " BALANCE.DECOSUBCODE05 LIKE '$Code5%' AND ";
		$WCode15 = " AND STK.DECOSUBCODE05 LIKE '$Code5%' ";
	}else{
		$WCode5 = "";
		$WCode15 = "";
	}
	if($Code6!=""){
		$WCode6 = " BALANCE.DECOSUBCODE06 LIKE '$Code6%' AND ";
		$WCode16 = " AND STK.DECOSUBCODE06 LIKE '$Code6%' ";
	}else{
		$WCode6 = "";
		$WCode16 = "";
	}
	if($Code7!=""){
		$WCode7 = " BALANCE.DECOSUBCODE07 LIKE '$Code7%' AND ";
		$WCode17 = " AND STK.DECOSUBCODE07 LIKE '$Code7%' ";
	}else{
		$WCode7 = "";
		$WCode17 = "";
	}
	if($Code8!=""){
		$WCode8 = " BALANCE.DECOSUBCODE08 LIKE '$Code8%' AND ";
		$WCode18 = " AND STK.DECOSUBCODE08 LIKE '$Code8%' ";
	}else{
		$WCode8 = "";
		$WCode18 = "";
	}
	if($Lot!=""){
		$Wlot 	= " BALANCE.LOTCODE LIKE '$Lot%' AND ";
		$Wlot1 	= " AND STK.LOTCODE LIKE '$Lot%'";
	}else{
		$Wlot 	= "";
		$Wlot1 	= "";
	}
	$sqlDB21 ="
	SELECT COUNT(BALANCE.BASEPRIMARYQUANTITYUNIT) AS QTY_ROL,
	SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS QTY_KG,
	SUM(BALANCE.BASESECONDARYQUANTITYUNIT) AS QTY_CONES,
	BALANCE.BASEPRIMARYQUANTITYUNIT AS KG,
	BALANCE.BASESECONDARYQUANTITYUNIT AS CONES,
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
	BALANCE.QUALITYLEVELCODE,
	F.SUMMARIZEDDESCRIPTION,
	BALANCE.LOTCODE,
	LISTAGG(DISTINCT TRIM(ISP.MANUFACTURINGDATE),', ') AS MANUFACTURINGDATE,
	LISTAGG(DISTINCT TRIM(ISP.CREATIONDATETIME),', ') AS CREATIONDATETIME,
	KET.VALUESTRING,
	CASE
		WHEN a2.VALUESTRING IS NOT NULL THEN
			CASE a2.VALUESTRING
				WHEN 1 THEN 'Merah'
				WHEN 2 THEN 'Kuning'
				WHEN 3 THEN 'Hijau'
				WHEN 4 THEN 'Ungu'
				WHEN 5 THEN 'Biru'
				WHEN 6 THEN 'Putih'
				WHEN 7 THEN 'Coklat'
				WHEN 8 THEN 'Orange'
				WHEN 9 THEN 'Cream'
				WHEN 10 THEN 'Pink'
				ELSE MAX(u.LONGDESCRIPTION)
			END
		ELSE MAX(u.LONGDESCRIPTION)
	END AS CODEWARNA
		FROM DB2ADMIN.BALANCE BALANCE  
		LEFT JOIN STOCKTRANSACTION s 
                  ON BALANCE.ELEMENTSCODE = s.ITEMELEMENTCODE 
                  AND s.TOKENCODE = 'RECEIPT' 
                  AND s.TEMPLATECODE = 'QCT'
        LEFT JOIN MRNDETAIL md 
                  ON s.TRANSACTIONNUMBER = md.TRANSACTIONNUMBER
              LEFT JOIN MRNHEADER mh 
                  ON md.MRNHEADERCODE = mh.CODE
              LEFT JOIN ADSTORAGE a2     
              	  ON a2.UNIQUEID = mh.ABSUNIQUEID AND a2.NAMENAME = 'WARNA'
		LEFT JOIN ADSTORAGE a3 ON
			a3.UNIQUEID = mh.ABSUNIQUEID
			AND a3.NAMENAME = 'WARNA1'
		LEFT JOIN USERGENERICGROUP u ON u.CODE = a3.VALUESTRING
		LEFT OUTER JOIN FULLITEMKEYDECODER F ON
		BALANCE.ITEMTYPECODE = F.ITEMTYPECODE AND
		BALANCE.DECOSUBCODE01 = F.SUBCODE01 AND
		BALANCE.DECOSUBCODE02 = F.SUBCODE02 AND
		BALANCE.DECOSUBCODE03 = F.SUBCODE03 AND
		BALANCE.DECOSUBCODE04 = F.SUBCODE04 AND
		BALANCE.DECOSUBCODE05 = F.SUBCODE05 AND
		BALANCE.DECOSUBCODE06 = F.SUBCODE06 AND
		BALANCE.DECOSUBCODE07 = F.SUBCODE07 AND
		BALANCE.DECOSUBCODE08 = F.SUBCODE08
		LEFT OUTER JOIN (SELECT ITTSUPPLIERPACKINGLISTBEAN.ITEMELEMENTCODE,ITTSUPPLIERPACKINGLISTBEAN.MANUFACTURINGDATE, 
			SUBSTR(ITTSUPPLIERPACKINGLISTBEAN.CREATIONDATETIME,1,10) AS CREATIONDATETIME  
			FROM DB2ADMIN.ITTSUPPLIERPACKINGLISTBEAN ITTSUPPLIERPACKINGLISTBEAN) ISP ON ISP.ITEMELEMENTCODE = BALANCE.ELEMENTSCODE
		LEFT OUTER JOIN (
		SELECT KET.VALUESTRING, STK.ITEMELEMENTCODE FROM (SELECT x.* FROM DB2ADMIN.STOCKTRANSACTION x
		WHERE  LOGICALWAREHOUSECODE <> 'TR11' and TEMPLATECODE ='203' and QUALITYLEVELCODE ='2') STK
			LEFT OUTER JOIN 		
			(SELECT
				i.ABSUNIQUEID, i.INTDOCPROVISIONALCOUNTERCODE,i.INTDOCUMENTPROVISIONALCODE,i.ORDERLINE, a.VALUESTRING 
			FROM
				( SELECT
				i2.*, i.ORDPRNCUSTOMERSUPPLIERCODE 
			FROM
				INTERNALDOCUMENT i
			LEFT OUTER JOIN INTERNALDOCUMENTLINE i2 ON
				i.PROVISIONALCODE = i2.INTDOCUMENTPROVISIONALCODE
				AND i.PROVISIONALCOUNTERCODE = i2.INTDOCPROVISIONALCOUNTERCODE ) i
			LEFT OUTER JOIN ADSTORAGE a ON
				a.UNIQUEID = i.ABSUNIQUEID
				AND a.NAMENAME = 'NoteYarnDefect'
			WHERE 
				i.ORDPRNCUSTOMERSUPPLIERCODE = 'M011') KET ON 
				STK.ORDERCODE=KET.INTDOCUMENTPROVISIONALCODE AND 
				STK.ORDERLINE=KET.ORDERLINE AND 
				STK.ORDERCOUNTERCODE = KET.INTDOCPROVISIONALCOUNTERCODE	
		WHERE (STK.ITEMTYPECODE='GYR' OR STK.ITEMTYPECODE='DYR') $WZone1 $Wlot1 $WLokasi1 $WCode11 $WCode12 $WCode13 $WCode14 $WCode15 $WCode16 $WCode17 $WCode18		
		) KET ON KET.ITEMELEMENTCODE = BALANCE.ELEMENTSCODE 	
		WHERE BALANCE.LOGICALWAREHOUSECODE='M011' AND $WZone $Wlot $WLokasi $WCode1 $WCode2 $WCode3 $WCode4 $WCode5 $WCode6 $WCode7 $WCode8 (BALANCE.ITEMTYPECODE='GYR' OR BALANCE.ITEMTYPECODE='DYR')
		GROUP BY 
		ISP.MANUFACTURINGDATE,
		ISP.CREATIONDATETIME,
		BALANCE.BASEPRIMARYQUANTITYUNIT,
		BALANCE.BASESECONDARYQUANTITYUNIT,
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
		BALANCE.QUALITYLEVELCODE,
		BALANCE.LOTCODE,
		F.SUMMARIZEDDESCRIPTION,
		KET.VALUESTRING,
		a2.VALUESTRING
		ORDER BY 
		BALANCE.QUALITYLEVELCODE DESC
		
	";
	/*
	$sqlDB21 = " 
	SELECT COUNT(BALANCE.BASEPRIMARYQUANTITYUNIT) AS QTY_ROL,
	SUM(BALANCE.BASEPRIMARYQUANTITYUNIT) AS QTY_KG,
	SUM(BALANCE.BASESECONDARYQUANTITYUNIT) AS QTY_CONES,
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
	BALANCE.QUALITYLEVELCODE,
	F.SUMMARIZEDDESCRIPTION,
	BALANCE.LOTCODE,
	TRIM(ISP.MANUFACTURINGDATE) AS MANUFACTURINGDATE,
	TRIM(ISP.CREATIONDATETIME) AS CREATIONDATETIME
		FROM DB2ADMIN.BALANCE BALANCE  
		LEFT OUTER JOIN FULLITEMKEYDECODER F ON
		BALANCE.ITEMTYPECODE = F.ITEMTYPECODE AND
		BALANCE.DECOSUBCODE01 = F.SUBCODE01 AND
		BALANCE.DECOSUBCODE02 = F.SUBCODE02 AND
		BALANCE.DECOSUBCODE03 = F.SUBCODE03 AND
		BALANCE.DECOSUBCODE04 = F.SUBCODE04 AND
		BALANCE.DECOSUBCODE05 = F.SUBCODE05 AND
		BALANCE.DECOSUBCODE06 = F.SUBCODE06 AND
		BALANCE.DECOSUBCODE07 = F.SUBCODE07 AND
		BALANCE.DECOSUBCODE08 = F.SUBCODE08
		LEFT OUTER JOIN (SELECT ITTSUPPLIERPACKINGLISTBEAN.ITEMELEMENTCODE,ITTSUPPLIERPACKINGLISTBEAN.MANUFACTURINGDATE, 
			SUBSTR(ITTSUPPLIERPACKINGLISTBEAN.CREATIONDATETIME,1,10) AS CREATIONDATETIME  
			FROM DB2ADMIN.ITTSUPPLIERPACKINGLISTBEAN ITTSUPPLIERPACKINGLISTBEAN) ISP ON ISP.ITEMELEMENTCODE = BALANCE.ELEMENTSCODE 
		WHERE BALANCE.LOGICALWAREHOUSECODE='M011' AND $WZone $Wlot $WLokasi $WCode1 $WCode2 $WCode3 $WCode4 $WCode5 $WCode6 $WCode7 $WCode8  (BALANCE.ITEMTYPECODE='GYR' OR BALANCE.ITEMTYPECODE='DYR')
		GROUP BY 
		ISP.MANUFACTURINGDATE,
		ISP.CREATIONDATETIME,
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
		BALANCE.QUALITYLEVELCODE,
		BALANCE.LOTCODE,
		F.SUMMARIZEDDESCRIPTION
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
		BALANCE.QUALITYLEVELCODE,
		BALANCE.LOTCODE,
		BALANCE.WHSLOCATIONWAREHOUSEZONECODE,
		BALANCE.WAREHOUSELOCATIONCODE
		"; */
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}						  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=$rowdb21['INTDOCUMENTPROVISIONALCODE']."-".$rowdb21['ORDERLINE'];

$kdbenang=$rowdb21['DECOSUBCODE01']." ".$rowdb21['DECOSUBCODE02']." ".$rowdb21['DECOSUBCODE03']." ".$rowdb21['DECOSUBCODE04']." ".$rowdb21['DECOSUBCODE05']." ".$rowdb21['DECOSUBCODE06']." ".$rowdb21['DECOSUBCODE07']." ".$rowdb21['DECOSUBCODE08'];
$sqlDB23 = " SELECT a.SUPPLIERCODE, b2.LEGALNAME1  FROM LOT a
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA b ON a.SUPPLIERCODE = b.CODE 
LEFT OUTER JOIN BUSINESSPARTNER b2 ON b2.NUMBERID = b.BUSINESSPARTNERNUMBERID 
WHERE a.CODE='".$rowdb21['LOTCODE']."' AND NOT a.SUPPLIERCODE IS NULL ";
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);
		
$sqlDBPRD = " SELECT ITTSUPPLIERPACKINGLISTBEAN.MANUFACTURINGDATE, 
			SUBSTR(ITTSUPPLIERPACKINGLISTBEAN.CREATIONDATETIME,1,10) AS CREATIONDATETIME  
			FROM DB2ADMIN.ITTSUPPLIERPACKINGLISTBEAN ITTSUPPLIERPACKINGLISTBEAN
			WHERE ITTSUPPLIERPACKINGLISTBEAN.LOTCODE = '".$rowdb21['LOTCODE']."'
			GROUP BY ITTSUPPLIERPACKINGLISTBEAN.MANUFACTURINGDATE, 
			SUBSTR(ITTSUPPLIERPACKINGLISTBEAN.CREATIONDATETIME,1,10)
			";
						
$stmt3PRD = db2_exec($conn1, $sqlDBPRD, array('cursor' => DB2_SCROLLABLE));
$rD2PRD = db2_fetch_assoc($stmt3PRD);
		
//$sqlDBPRD1 = " 
//SELECT
//	BASESECONDARYQUANTITYUNIT AS CONES,
//	BASEPRIMARYQUANTITYUNIT AS KG
//FROM
//	BALANCE 
//WHERE
//	BALANCE.ITEMTYPECODE  = '".$rowdb21['ITEMTYPECODE']."' 
//	AND BALANCE.DECOSUBCODE01 = '".$rowdb21['DECOSUBCODE01']."'
//	AND BALANCE.DECOSUBCODE02 = '".$rowdb21['DECOSUBCODE02']."'
//	AND BALANCE.DECOSUBCODE03 = '".$rowdb21['DECOSUBCODE03']."'
//	AND BALANCE.DECOSUBCODE04 = '".$rowdb21['DECOSUBCODE04']."'
//	AND BALANCE.DECOSUBCODE05 = '".$rowdb21['DECOSUBCODE05']."'
//	AND BALANCE.DECOSUBCODE06 = '".$rowdb21['DECOSUBCODE06']."'
//	AND BALANCE.DECOSUBCODE07 = '".$rowdb21['DECOSUBCODE07']."'
//	AND BALANCE.DECOSUBCODE08 = '".$rowdb21['DECOSUBCODE08']."'
//	AND BALANCE.QUALITYLEVELCODE  = '".$rowdb21['QUALITYLEVELCODE']."'
//	AND BALANCE.LOTCODE = '".$rowdb21['LOTCODE']."'
//	AND BALANCE.WHSLOCATIONWAREHOUSEZONECODE = '".$rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."'
//	AND BALANCE.WAREHOUSELOCATIONCODE = '".$rowdb21['WAREHOUSELOCATIONCODE']."'
//LIMIT 1	
//			";
//						
//$stmt3PRD1 = db2_exec($conn1, $sqlDBPRD1, array('cursor' => DB2_SCROLLABLE));
//$rD2PRD1 = db2_fetch_assoc($stmt3PRD1);
		
$posLot=strpos($rowdb21['LOTCODE'],"+");
if($posLot>0){
	$lotP=substr($rowdb21['LOTCODE'],0,$posLot);
}else{
	$lotP=$rowdb21['LOTCODE'];
}	
$qlt="";		
if($rowdb21['QUALITYLEVELCODE']=="1"){
	$qlt="A";
}else if($rowdb21['QUALITYLEVELCODE']=="2"){
	$qlt="B";
}else if($rowdb21['QUALITYLEVELCODE']=="3"){
	$qlt="C";
}else if($rowdb21['QUALITYLEVELCODE']=="4"){
	$qlt="D";
}else if($rowdb21['QUALITYLEVELCODE']=="5"){
	$qlt="E";
}
		
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $qlt; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['ITEMTYPECODE']; ?></td>
      <td style="text-align: left"><?php echo $kdbenang; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['CODEWARNA'];?></td>
      <td style="text-align: left"><?php echo $lotP; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb23['SUPPLIERCODE']; ?></td>
      <td style="text-align: left"><?php echo $rowdb23['LEGALNAME1']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['CONES']); ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['KG'],2); ?></td>
      <td style="text-align: center"><?php if($rowdb21['CREATIONDATETIME']!=""){echo date('d-m-Y', strtotime($rowdb21['CREATIONDATETIME']));}else if($rD2PRD['CREATIONDATETIME']!="") { echo date('d-m-Y', strtotime($rD2PRD['CREATIONDATETIME'])); } ?></td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      <td><?php if($rowdb21['MANUFACTURINGDATE']!=""){ echo date('d-m-Y', strtotime($rowdb21['MANUFACTURINGDATE']));} else if($rowdb25['TG']!=""){echo date('d-m-Y', strtotime($rowdb25['TG']));}else if($rD2PRD['MANUFACTURINGDATE']!=""){ echo date('d-m-Y', strtotime($rD2PRD['MANUFACTURINGDATE'])); } ?></td>
      <td><?php echo $rowdb21['VALUESTRING']; ?></td>
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
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo $tCones; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKg,2),2); ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $tRol; ?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
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