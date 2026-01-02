<?php
include "../koneksi.php";
ini_set("error_reporting", 1);
$schema = 'dbnow_gkj';
$tblOpnameDetail = "[$schema].[tbl_opname_detail_11]";
$sqlErrors = [];
function logSqlError($stmt, $label = '', $line = null) {
    global $sqlErrors;
    if ($stmt !== false) {
        return;
    }
    $err = sqlsrv_errors();
    if (!empty($err)) {
        $msg = $label !== '' ? $label . ': ' : '';
        if ($line !== null) {
            $msg = "[line $line] " . $msg;
        }
        $msg .= $err[0]['message'];
        $sqlErrors[] = $msg;
        echo "<script>console.error('SQLSRV error: " . addslashes($msg) . "');</script>";
    }
}
function fmtDate($val, $format = 'Y-m-d') {
    if ($val instanceof DateTime) {
        return $val->format($format);
    }
    return $val;
}
define("TANGGAL_HARI_INI", date("Y-m-d"));
$Awal = TANGGAL_HARI_INI;
$cektglSql = "SELECT
	CAST(GETDATE() AS date) as tgl,
	COUNT(tgl_tutup) as ck ,
	DATEPART(HOUR, GETDATE()) as jam,
	FORMAT(GETDATE(), 'HH:mm') as jam1,
	tgl_tutup 
FROM
	$tblOpnameDetail
WHERE
	tgl_tutup = ?";
$stmtCek = sqlsrv_query($con, $cektglSql, [$Awal], ["Scrollable" => SQLSRV_CURSOR_KEYSET]);
logSqlError($stmtCek, 'cek tgl_tutup', __LINE__);
$dcek = $stmtCek ? sqlsrv_fetch_array($stmtCek, SQLSRV_FETCH_ASSOC) : null;
if (!$dcek) {
	$dcek = ['ck' => 0, 'tgl_tutup' => null];
}
if($dcek['ck']>0){
	echo "<script>";
	echo "alert('Stok Tgl ".$dcek['tgl_tutup']." Ini Sudah Pernah ditutup')";
	echo "</script>";
}else if($_GET['note']!="" or $_GET['note']=="Berhasil"){
	echo "Tutup Transaksi Berhasil";
}else{
	$sqlDB21 = "SELECT 
					SUM(b.BASEPRIMARYQUANTITYUNIT) AS BERAT,
					SUM(b.BASESECONDARYQUANTITYUNIT) AS YD,
					COUNT(b.BASESECONDARYQUANTITYUNIT) AS ROLL,
					SUBSTR(b.CREATIONDATETIME, 1, 10) AS TGLCREATE,
					SUBSTR(b.LASTUPDATEDATETIME, 1, 10) AS TGLUPDATE,
					mk.TRANSACTIONDATE,
					b.ELEMENTSCODE,
					b.LOTCODE,
					b.PROJECTCODE,
					b.ITEMTYPECODE,
					b.DECOSUBCODE01,
					b.DECOSUBCODE02,
					b.DECOSUBCODE03,
					b.DECOSUBCODE04,
					b.DECOSUBCODE05,
					b.DECOSUBCODE06,
					b.DECOSUBCODE07,
					b.DECOSUBCODE08,
					b.BASEPRIMARYUNITCODE,
					b.BASESECONDARYUNITCODE,
					b.WHSLOCATIONWAREHOUSEZONECODE,
					b.WAREHOUSELOCATIONCODE,
					p.LONGDESCRIPTION AS JNSKAIN ,
					t.QUALITYREASONCODE AS KET,
					Q1.LONGDESCRIPTION AS STS1
				FROM 
					BALANCE b
				LEFT JOIN (
					SELECT
						*
					FROM
						(
							SELECT
								ITEMELEMENTCODE,
								QUALITYREASONCODE,
								CREATIONDATETIME,
								ROW_NUMBER() OVER (
									PARTITION BY ITEMELEMENTCODE
								ORDER BY
									CREATIONDATETIME DESC,
									TRANSACTIONDETAILNUMBER DESC
								) AS RN
							FROM
								STOCKTRANSACTION
							WHERE
								(
									ITEMTYPECODE = 'FKF'
										OR ITEMTYPECODE = 'KFF'
								)
								AND 
				LOGICALWAREHOUSECODE = 'M031'
						) T
					WHERE
						T.RN = '1'
				) t
				ON b.ELEMENTSCODE = t.ITEMELEMENTCODE
				LEFT OUTER JOIN QUALITYREASON Q1 ON	t.QUALITYREASONCODE = Q1.CODE
				LEFT OUTER JOIN (
					SELECT
						GKJ.*
					FROM
						(
							SELECT
								*
							FROM
								STOCKTRANSACTION s
							WHERE
								TRANSACTIONDETAILNUMBER = '1'
								AND TEMPLATECODE = '303'
								AND LOGICALWAREHOUSECODE = 'M033'
								AND (
									ITEMTYPECODE = 'KFF'
										OR ITEMTYPECODE = 'FKF'
								)
						) QCF
				INNER JOIN (
					SELECT
						*
					FROM
						STOCKTRANSACTION s
					WHERE
						TRANSACTIONDETAILNUMBER = '2'
						AND TEMPLATECODE = '304'
						AND LOGICALWAREHOUSECODE = 'M031'
						AND (
								ITEMTYPECODE = 'KFF'
									OR ITEMTYPECODE = 'FKF'
							)
						) GKJ ON QCF.TRANSACTIONNUMBER = GKJ.TRANSACTIONNUMBER
					) mk ON b.ELEMENTSCODE = mk.ITEMELEMENTCODE
				LEFT OUTER JOIN PRODUCT p ON
					p.ITEMTYPECODE = b.ITEMTYPECODE
					AND p.SUBCODE01 = b.DECOSUBCODE01
					AND p.SUBCODE02 = b.DECOSUBCODE02
					AND p.SUBCODE03 = b.DECOSUBCODE03
					AND p.SUBCODE04 = b.DECOSUBCODE04
					AND p.SUBCODE05 = b.DECOSUBCODE05
					AND p.SUBCODE06 = b.DECOSUBCODE06
					AND p.SUBCODE07 = b.DECOSUBCODE07
					AND p.SUBCODE08 = b.DECOSUBCODE08
				WHERE
					(b.ITEMTYPECODE = 'FKF' OR b.ITEMTYPECODE = 'KFF')
					AND b.LOGICALWAREHOUSECODE = 'M031'
					AND (TRIM(b.WHSLOCATIONWAREHOUSEZONECODE) LIKE '%W1%'
						OR TRIM(b.WHSLOCATIONWAREHOUSEZONECODE) LIKE '%W2%'
						OR TRIM(b.WHSLOCATIONWAREHOUSEZONECODE) LIKE '%W3%'
						OR TRIM(b.WHSLOCATIONWAREHOUSEZONECODE) LIKE '%X1%'
						OR TRIM(b.WHSLOCATIONWAREHOUSEZONECODE) LIKE '%X2%'
						OR TRIM(b.WHSLOCATIONWAREHOUSEZONECODE) LIKE '%X6%'
						OR TRIM(b.WHSLOCATIONWAREHOUSEZONECODE) LIKE '%X7%'
					)
				GROUP BY 
					b.ELEMENTSCODE,
					b.ITEMTYPECODE,
					b.DECOSUBCODE01,
					b.DECOSUBCODE02,
					b.DECOSUBCODE03,
					b.DECOSUBCODE04,
					b.DECOSUBCODE05,
					b.DECOSUBCODE06,
					b.DECOSUBCODE07,
					b.DECOSUBCODE08,
					b.PROJECTCODE,
					b.LOTCODE,
					b.BASEPRIMARYUNITCODE,
					b.BASESECONDARYUNITCODE,
					b.WHSLOCATIONWAREHOUSEZONECODE,
					b.WAREHOUSELOCATIONCODE,
					p.LONGDESCRIPTION,
					mk.TRANSACTIONDATE,
					t.QUALITYREASONCODE, 
					Q1.LONGDESCRIPTION,
					SUBSTR(b.LASTUPDATEDATETIME, 1, 10),
					SUBSTR(b.CREATIONDATETIME, 1, 10)";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
    $hasInsert = false;
	// siapkan prepared statement untuk insert agar lebih cepat
	$p_itm = $p_langganan = $p_buyer = $p_po = $p_orderno = $p_tipe = $p_no_item = $p_jns_kain = $p_no_warna = $p_warna = $p_rol = $p_lot = $p_weight = $p_satuan = $p_length = $p_satuan_len = $p_zone = $p_lokasi = $p_lebar = $p_gramasi = $p_sts_kain = $p_grouping1 = $p_hue1 = $p_sn = $p_tgl_delivery = $p_tgl_delivery_actual = $p_tgl_update = $p_tgl_mutasi = $p_tgl_tutup = $p_kategori = null;
	$insertSql = "INSERT INTO $tblOpnameDetail (
		itm, langganan, buyer, po, orderno, tipe, no_item, jns_kain, no_warna, warna, rol, lot, weight, satuan, length, satuan_len, zone, lokasi, lebar, gramasi, sts_kain, grouping1, hue1, sn, tgl_delivery, tgl_delivery_actual, tgl_update, tgl_mutasi, tgl_tutup, tgl_buat, kategori
	) VALUES (
		?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE(), ?
	)";
	$insertParams = [
		&$p_itm, &$p_langganan, &$p_buyer, &$p_po, &$p_orderno, &$p_tipe, &$p_no_item, &$p_jns_kain, &$p_no_warna, &$p_warna,
		&$p_rol, &$p_lot, &$p_weight, &$p_satuan, &$p_length, &$p_satuan_len, &$p_zone, &$p_lokasi, &$p_lebar, &$p_gramasi,
		&$p_sts_kain, &$p_grouping1, &$p_hue1, &$p_sn, &$p_tgl_delivery, &$p_tgl_delivery_actual, &$p_tgl_update, &$p_tgl_mutasi, &$p_tgl_tutup, &$p_kategori
	];
	$insertStmt = sqlsrv_prepare($con, $insertSql, $insertParams);
	logSqlError($insertStmt, 'prepare insert opname detail', __LINE__);
	if($insertStmt){
		sqlsrv_begin_transaction($con);
	}
    while($rowdb21 = db2_fetch_assoc($stmt1)){
	$itemNo=trim($rowdb21['DECOSUBCODE02'])."".trim($rowdb21['DECOSUBCODE03']);	
	if($rowdb21['ITEMTYPECODE']=="KFF"){$jns="KAIN";}else if($rowdb21['ITEMTYPECODE']=="FKF"){$jns="KRAH";}	

	$sqlDB22 = "SELECT
					SALESORDER.CODE,
					SALESORDER.EXTERNALREFERENCE,
					SALESORDER.ORDPRNCUSTOMERSUPPLIERCODE,
					ITXVIEWAKJ.LEGALNAME1,
					ITXVIEWAKJ.ORDERPARTNERBRANDCODE,
					ITXVIEWAKJ.LONGDESCRIPTION
				FROM
					DB2ADMIN.SALESORDER SALESORDER
				LEFT OUTER JOIN DB2ADMIN.ITXVIEWAKJ ITXVIEWAKJ ON SALESORDER.CODE = ITXVIEWAKJ.CODE
				WHERE
					SALESORDER.CODE = '$rowdb21[PROJECTCODE]'";
	$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	$rowdb22 = db2_fetch_assoc($stmt2);		
	if($rowdb22['LEGALNAME1']==""){$langganan="";}else{$langganan=$rowdb22['LEGALNAME1'];}
	if($rowdb22['ORDERPARTNERBRANDCODE']==""){$buyer="";}else{$buyer=$rowdb22['LONGDESCRIPTION'];}		

	$sqlDB23 = "SELECT 
					i.WARNA 
				FROM 
					PRODUCT p
				LEFT OUTER JOIN ITXVIEWCOLOR i ON p.ITEMTYPECODE=i.ITEMTYPECODE  AND	
												p.SUBCODE01=i.SUBCODE01  AND
												p.SUBCODE02=i.SUBCODE02 AND
												p.SUBCODE03=i.SUBCODE03 AND
												p.SUBCODE04=i.SUBCODE04 AND
												p.SUBCODE05=i.SUBCODE05 AND
												p.SUBCODE06=i.SUBCODE06 AND
												p.SUBCODE07=i.SUBCODE07 AND
												p.SUBCODE08=i.SUBCODE08
				WHERE	   
					i.ITEMTYPECODE='$rowdb21[ITEMTYPECODE]' AND	
					i.SUBCODE01='$rowdb21[DECOSUBCODE01]' AND
					i.SUBCODE02='$rowdb21[DECOSUBCODE02]' AND
					i.SUBCODE03='$rowdb21[DECOSUBCODE03]' AND
					i.SUBCODE04='$rowdb21[DECOSUBCODE04]' AND
					i.SUBCODE05='$rowdb21[DECOSUBCODE05]' AND
					i.SUBCODE06='$rowdb21[DECOSUBCODE06]' AND
					i.SUBCODE07='$rowdb21[DECOSUBCODE07]' AND
					i.SUBCODE08='$rowdb21[DECOSUBCODE08]' ";
	$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
	$rowdb23 = db2_fetch_assoc($stmt3);
		
	$sqlDB25 = "SELECT
					ORDERITEMORDERPARTNERLINK.ORDPRNCUSTOMERSUPPLIERCODE,
					ORDERITEMORDERPARTNERLINK.LONGDESCRIPTION
				FROM
					DB2ADMIN.ORDERITEMORDERPARTNERLINK ORDERITEMORDERPARTNERLINK
				WHERE
					ORDERITEMORDERPARTNERLINK.ITEMTYPEAFICODE = '$rowdb21[ITEMTYPECODE]'
					AND ORDERITEMORDERPARTNERLINK.ORDPRNCUSTOMERSUPPLIERCODE = '$rowdb22[ORDPRNCUSTOMERSUPPLIERCODE]'
					AND ORDERITEMORDERPARTNERLINK.SUBCODE01 = '$rowdb21[DECOSUBCODE01]'
					AND ORDERITEMORDERPARTNERLINK.SUBCODE02 = '$rowdb21[DECOSUBCODE02]'
					AND ORDERITEMORDERPARTNERLINK.SUBCODE03 = '$rowdb21[DECOSUBCODE03]'
					AND ORDERITEMORDERPARTNERLINK.SUBCODE04 = '$rowdb21[DECOSUBCODE04]'
					AND ORDERITEMORDERPARTNERLINK.SUBCODE05 = '$rowdb21[DECOSUBCODE05]'
					AND ORDERITEMORDERPARTNERLINK.SUBCODE06 = '$rowdb21[DECOSUBCODE06]'
					AND ORDERITEMORDERPARTNERLINK.SUBCODE07 = '$rowdb21[DECOSUBCODE07]'
					AND ORDERITEMORDERPARTNERLINK.SUBCODE08 = '$rowdb21[DECOSUBCODE08]'";
	$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
	$rowdb25 = db2_fetch_assoc($stmt5);	
	if($rowdb25['LONGDESCRIPTION']!=""){
		$item=$rowdb25['LONGDESCRIPTION'];
	}else{
		$item=trim($rowdb21['DECOSUBCODE02'])."".trim($rowdb21['DECOSUBCODE03']);
	}	
	$sqlDB26 = "SELECT SALESORDERLINE.EXTERNALREFERENCE 
				FROM DB2ADMIN.SALESORDERLINE WHERE
				SALESORDERLINE.ITEMTYPEAFICODE='$rowdb21[ITEMTYPECODE]' AND	   
				SALESORDERLINE.PROJECTCODE='$rowdb21[PROJECTCODE]' AND
				SALESORDERLINE.SUBCODE01='$rowdb21[DECOSUBCODE01]' AND
				SALESORDERLINE.SUBCODE02='$rowdb21[DECOSUBCODE02]' AND
				SALESORDERLINE.SUBCODE03='$rowdb21[DECOSUBCODE03]' AND
				SALESORDERLINE.SUBCODE04='$rowdb21[DECOSUBCODE04]' AND
				SALESORDERLINE.SUBCODE05='$rowdb21[DECOSUBCODE05]' AND
				SALESORDERLINE.SUBCODE06='$rowdb21[DECOSUBCODE06]' AND
				SALESORDERLINE.SUBCODE07='$rowdb21[DECOSUBCODE07]' AND
				SALESORDERLINE.SUBCODE08='$rowdb21[DECOSUBCODE08]' LIMIT 1";
	$stmt6   = db2_exec($conn1,$sqlDB26, array('cursor'=>DB2_SCROLLABLE));
	$rowdb26 = db2_fetch_assoc($stmt6);
	if($rowdb22['EXTERNALREFERENCE']!=""){
		$PO=$rowdb22['EXTERNALREFERENCE'];
	}else{
		$PO=$rowdb26['EXTERNALREFERENCE'];
	}
	$sqlDB27 = "SELECT 
					ADSTORAGE.VALUEDECIMAL AS GSM,
					ADSTORAGE1.VALUEDECIMAL AS LEBAR
					FROM DB2ADMIN.PRODUCT PRODUCT 
					LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE 
					ON ADSTORAGE.UNIQUEID=PRODUCT.ABSUNIQUEID AND ADSTORAGE.NAMENAME='GSM'
					LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE1 
					ON ADSTORAGE1.UNIQUEID=PRODUCT.ABSUNIQUEID AND ADSTORAGE1.NAMENAME='Width'
					WHERE
						PRODUCT.ITEMTYPECODE='$rowdb21[ITEMTYPECODE]' AND	
						PRODUCT.SUBCODE01='$rowdb21[DECOSUBCODE01]' AND
						PRODUCT.SUBCODE02='$rowdb21[DECOSUBCODE02]' AND
						PRODUCT.SUBCODE03='$rowdb21[DECOSUBCODE03]' AND
						PRODUCT.SUBCODE04='$rowdb21[DECOSUBCODE04]' AND
						PRODUCT.SUBCODE05='$rowdb21[DECOSUBCODE05]' AND
						PRODUCT.SUBCODE06='$rowdb21[DECOSUBCODE06]' AND
						PRODUCT.SUBCODE07='$rowdb21[DECOSUBCODE07]' AND
						PRODUCT.SUBCODE08='$rowdb21[DECOSUBCODE08]'  ";
	$stmt7   = db2_exec($conn1,$sqlDB27, array('cursor'=>DB2_SCROLLABLE));
	$rowdb27 = db2_fetch_assoc($stmt7);	
	$sqlDB28 = "SELECT
					QUALITYDOCLINE.VALUEGROUPCODE  AS GROUPING1 ,HUE.VALUEGROUPCODE AS HUE1
				FROM
					QUALITYDOCLINE 
				LEFT OUTER JOIN 
					(SELECT
					QUALITYDOCPRODUCTIONORDERCODE,VALUEGROUPCODE
					FROM
						QUALITYDOCLINE
					WHERE
						QUALITYDOCLINE.QUALITYDOCUMENTHEADERNUMBERID ='1005' AND
						QUALITYDOCLINE.CHARACTERISTICCODE = 'HUE' AND 
						QUALITYDOCUMENTITEMTYPEAFICODE ='KFF' AND 
						QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE='".$rowdb21['LOTCODE']."'
						) HUE ON HUE.QUALITYDOCPRODUCTIONORDERCODE=QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE
					WHERE
						QUALITYDOCLINE.QUALITYDOCUMENTHEADERNUMBERID ='1005'  AND
						QUALITYDOCLINE.CHARACTERISTICCODE = 'GROUPING' AND
						QUALITYDOCUMENTITEMTYPEAFICODE ='KFF' AND
						QUALITYDOCLINE.QUALITYDOCPRODUCTIONORDERCODE='".$rowdb21['LOTCODE']."' ";
	$stmt8   = db2_exec($conn1,$sqlDB28, array('cursor'=>DB2_SCROLLABLE));
	$rowdb28 = db2_fetch_assoc($stmt8);	

	$q_deliverydate	= db2_exec($conn1, "SELECT
									SUBSTR(LISTAGG(DELIVERYDATE, ', ') WITHIN GROUP (ORDER BY DELIVERYDATE), LENGTH(LISTAGG(DELIVERYDATE, ', ') WITHIN GROUP (ORDER BY DELIVERYDATE)) - 9) AS DELIVERYDATE
								FROM
									(
									SELECT
										DISTINCT 
										DELIVERYDATE
									FROM
										SALESORDERDELIVERY
									WHERE
										SALESORDERLINESALESORDERCODE = '$rowdb21[PROJECTCODE]'
										AND ITEMTYPEAFICODE = '$rowdb21[ITEMTYPECODE]'
										AND SUBCODE01 = '$rowdb21[DECOSUBCODE01]'
										AND SUBCODE02 = '$rowdb21[DECOSUBCODE02]'
										AND SUBCODE03 = '$rowdb21[DECOSUBCODE03]'
										AND SUBCODE04 = '$rowdb21[DECOSUBCODE04]'
										AND SUBCODE05 = '$rowdb21[DECOSUBCODE05]'
										AND SUBCODE06 = '$rowdb21[DECOSUBCODE06]'
										AND SUBCODE07 = '$rowdb21[DECOSUBCODE07]'
										AND SUBCODE08 = '$rowdb21[DECOSUBCODE08]'
										)");
	$row_deliverydate	= db2_fetch_assoc($q_deliverydate);		
		
	if ($rowdb21['KET']!="" and $rowdb21['KET']!="100"){
		$sts1=$rowdb21['STS1'];}	
	else if ((substr(trim($rowdb21['PROJECTCODE']),0,3)=="OPN" or substr(trim($rowdb21['PROJECTCODE']),0,3)=="STO") and $rowdb21['KET']=="100"){
		$sts1="Booking";}	
	else if (substr(trim($rowdb21['PROJECTCODE']),0,3)=="OPN" or substr(trim($rowdb21['PROJECTCODE']),0,3)=="STO" ){
		$sts1="Booking";}	
	else if (substr(trim($rowdb21['PROJECTCODE']),0,3)=="RPE" and $rowdb21['KET']=="100"){
		$sts1="Ganti Kain";  }	
	else if (substr(trim($rowdb21['PROJECTCODE']),0,3)=="RPE"){
		$sts1="Ganti Kain";  }
	else {
		$sts1="Tunggu Kirim"; } 
    if($rowdb21['TGLUPDATE']!=""){
		$tglupd = $rowdb21['TGLUPDATE']; }
	else{
		$tglupd = $rowdb21['TGLCREATE']; }		

	$deliveryDate   = isset($row_deliverydate['DELIVERYDATE']) ? $row_deliverydate['DELIVERYDATE'] : null;
	$deliveryActual = null;
	$tglUpdateVal   = fmtDate($tglupd, 'Y-m-d H:i:s');
	$tglMutasiVal   = fmtDate($rowdb21['TRANSACTIONDATE'], 'Y-m-d');
	
	// set nilai parameter
	$p_itm = $item;
	$p_langganan = $langganan;
	$p_buyer = $buyer;
	$p_po = $PO;
	$p_orderno = $rowdb21['PROJECTCODE'];
	$p_tipe = $jns;
	$p_no_item = $itemNo;
	$p_jns_kain = $rowdb21['JNSKAIN'];
	$p_no_warna = $rowdb21['DECOSUBCODE05'];
	$p_warna = $rowdb23['WARNA'];
	$p_rol = $rowdb21['ROLL'];
	$p_lot = $rowdb21['LOTCODE'];
	$p_weight = round($rowdb21['BERAT'],2);
	$p_satuan = $rowdb21['BASEPRIMARYUNITCODE'];
	$p_length = round($rowdb21['YD'],2);
	$p_satuan_len = $rowdb21['BASESECONDARYUNITCODE'];
	$p_zone = $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'];
	$p_lokasi = $rowdb21['WAREHOUSELOCATIONCODE'];
	$p_lebar = round($rowdb27['LEBAR']);
	$p_gramasi = round($rowdb27['GSM']);
	$p_sts_kain = $sts1;
	$p_grouping1 = $rowdb28['GROUPING1'];
	$p_hue1 = $rowdb28['HUE1'];
	$p_sn = $rowdb21['ELEMENTSCODE'];
	$p_tgl_delivery = fmtDate($deliveryDate);
	$p_tgl_delivery_actual = fmtDate($deliveryActual);
	$p_tgl_update = $tglUpdateVal;
	$p_tgl_mutasi = $tglMutasiVal;
	$p_tgl_tutup = $Awal;
	$p_kategori = isset($rowdb21['KATEGORI']) ? $rowdb21['KATEGORI'] : '';

	$simpan = $insertStmt ? sqlsrv_execute($insertStmt) : false;
	logSqlError($simpan, 'insert opname detail', __LINE__);
	if ($simpan) {
		$hasInsert = true;
	}
	
}
	if($insertStmt){
		if($hasInsert){
			sqlsrv_commit($con);
		}else{
			sqlsrv_rollback($con);
		}
	}
	if($hasInsert){		
?>
<script type="text/javascript">
    // buka berurutan laporan hasil tutup stok
    window.open("cetak/PersediaanKainJadi2022DetailExcelR11.php?tgl=<?php echo $Awal; ?>", "_blank");
    setTimeout(function() {
        window.open("cetak/PersediaanKainJadiNoOrderDetailExcelR11.php?tgl=<?php echo $Awal; ?>", "_blank");
    }, 5000);
    setTimeout(function() {
        window.open("cetak/PersediaanKainJadiFullDetailExcelR11.php?tgl=<?php echo $Awal; ?>", "_blank");
    }, 10000);
    setTimeout(function() {
        window.open("cetak/PersediaanKainJadi2023DetailExcelR11.php?tgl=<?php echo $Awal; ?>", "_blank");
    }, 15000);
    setTimeout(function() {
        window.open("cetak/PersediaanKainJadi2024DetailExcelR11.php?tgl=<?php echo $Awal; ?>", "_blank");
    }, 20000);
    setTimeout(function() {
        window.open("cetak/PersediaanKainJadi2025DetailExcelR11.php?tgl=<?php echo $Awal; ?>", "_blank");
    }, 25000);
</script>
<?php } } ?>				
