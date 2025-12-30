<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$pos=strpos($modal_id,"#");
	$Project=substr($modal_id,0,$pos);
	$dt=substr($modal_id,$pos+1,250);
	$pos1=strpos($dt,"#");
	$Item=substr($dt,0,$pos1);
	$Code=substr($dt,$pos1+1,250);
	$kdbng=str_replace(" ","",$Code);
	
	
?>
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Cek Project Code</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body"><i>
			Code: <b><?php echo $Code;?></b>				
			</i>	
			<table id="lookup40" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 14px;">
						<thead>
							<tr>
								<th>#</th>
								<th><div align="center">ProjectCode</div></th>
								<th><div align="center">No Hanger</div></th>
								<th><div align="center">Supplier</div></th>
								<th><div align="center">Kebutuhan</div></th>
								<th><div align="center">Permohonan</div></th>
								<th><div align="center">Kurang Benang</div></th>					
							</tr>							
						</thead>
						<tbody>
							<?php
							$no=1;
							$persen=0;
							$kbhBenang=0;							
							$sqlDB22 = "SELECT
	*
FROM
	(
	SELECT
		pr.SUBCODE01,
		pr.SUBCODE02,
		pr.SUBCODE03,
		pr.SUBCODE04,
		pr.SUBCODE05,
		pr.SUBCODE06,
		pr.SUBCODE07,
		pr.SUBCODE08,
		CASE
			WHEN NOT p.PROJECTCODE IS NULL THEN p.PROJECTCODE
			WHEN NOT p.DLVSALORDERLINESALESORDERCODE IS NULL THEN p.DLVSALORDERLINESALESORDERCODE
			WHEN NOT p.ORIGDLVSALORDLINESALORDERCODE IS NULL THEN p.ORIGDLVSALORDLINESALORDERCODE
			ELSE NULL
		END AS PROJECT,
		CONCAT (TRIM(p.SUBCODE02),CONCAT(TRIM(p.SUBCODE03),CONCAT(' ',TRIM(p.SUBCODE04)))) AS HANGERNO,
		TRIM(p.SUBCODE02) AS CODE2,
		TRIM(p.SUBCODE03) AS CODE3,
		TRIM(p.SUBCODE04) AS CODE4
	FROM
		DB2ADMIN.PRODUCTIONRESERVATION pr
	LEFT OUTER JOIN PRODUCTIONDEMAND p ON
		p.CODE = pr.ORDERCODE
	WHERE
		p.PROGRESSSTATUS = '2'
	GROUP BY
		pr.SUBCODE01,
		pr.SUBCODE02,
		pr.SUBCODE03,
		pr.SUBCODE04,
		pr.SUBCODE05,
		pr.SUBCODE06,
		pr.SUBCODE07,
		pr.SUBCODE08,
		p.PROJECTCODE,
		p.DLVSALORDERLINESALESORDERCODE,
		p.ORIGDLVSALORDLINESALORDERCODE,
		p.SUBCODE02,
		p.SUBCODE03,
		p.SUBCODE04,
		CONCAT(TRIM(p.SUBCODE02),CONCAT(TRIM(p.SUBCODE03),CONCAT(' ',TRIM(p.SUBCODE04))))) a
WHERE NOT a.PROJECT IS NULL AND
	CONCAT(trim(a.SUBCODE01), CONCAT(trim(a.SUBCODE02), CONCAT(trim(a.SUBCODE03), CONCAT(trim(a.SUBCODE04), CONCAT(trim(a.SUBCODE05), CONCAT(trim(a.SUBCODE06), CONCAT(trim(a.SUBCODE07), trim(a.SUBCODE08))))))))= '$kdbng'
		";					  
		$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
							while($rD=db2_fetch_assoc($stmt2)){	
	$sqlDB21P =" 
SELECT
	SUBCODE02,
	SUBCODE03,
	SUBCODE04,
	SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
	CURRENT_TIMESTAMP AS TGLS,
	PRODUCTIONORDERCODE
FROM
	ITXVIEWHEADERKNTORDER
WHERE
	ITEMTYPEAFICODE = 'KGF'
	AND SUBCODE02 = '$rD[CODE2]'
	AND SUBCODE03 = '$rD[CODE3]'
	AND SUBCODE04 = '$rD[CODE4]'
	AND (PROJECTCODE = '$rD[PROJECT]'
		OR ORIGDLVSALORDLINESALORDERCODE = '$rD[PROJECT]')
	AND (PROGRESSSTATUS = '2'
		OR PROGRESSSTATUS = '6')
GROUP BY
	SUBCODE02,
	SUBCODE03,
	SUBCODE04,
	CURRENT_TIMESTAMP,
	PRODUCTIONORDERCODE
LIMIT 1
		";	
		  $stmt1P   = db2_exec($conn1,$sqlDB21P, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb21P = db2_fetch_assoc($stmt1P);	
$sqlDB21PJ =" SELECT a.SUBCODE02,a.SUBCODE03,SUM(a.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY, SUM(a3.VALUEDECIMAL) AS QTYSALIN,SUM(a4.VALUEDECIMAL) AS QTYOPIN,SUM(a5.VALUEDECIMAL) AS QTYOPOUT,CURRENT_TIMESTAMP AS TGLS  FROM ITXVIEWHEADERKNTORDER a
LEFT OUTER JOIN PRODUCTIONDEMAND p ON p.CODE =a.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN ADSTORAGE a2 ON p.ABSUNIQUEID =a2.UNIQUEID AND a2.NAMENAME ='StatusRMP'
LEFT OUTER JOIN ADSTORAGE a3 ON p.ABSUNIQUEID =a3.UNIQUEID AND a3.NAMENAME ='QtySalin'
LEFT OUTER JOIN ADSTORAGE a4 ON p.ABSUNIQUEID =a4.UNIQUEID AND a4.NAMENAME ='QtyOperIn'
LEFT OUTER JOIN ADSTORAGE a5 ON p.ABSUNIQUEID =a5.UNIQUEID AND a5.NAMENAME ='QtyOperOut'
WHERE a.ITEMTYPEAFICODE ='KGF' AND (a.PROJECTCODE ='$rD[PROJECT]' OR a.ORIGDLVSALORDLINESALORDERCODE='$rD[PROJECT]') AND
a.SUBCODE02='$rD[CODE2]' AND a.SUBCODE03='$rD[CODE3]' AND a.SUBCODE04='$rD[CODE4]' AND (a.PROGRESSSTATUS='2' OR a.PROGRESSSTATUS='6') AND (NOT a2.VALUESTRING ='3' OR a2.VALUESTRING IS NULL)
GROUP BY a.SUBCODE02,a.SUBCODE03,CURRENT_TIMESTAMP
		";	
		  $stmt1PJ   = db2_exec($conn1,$sqlDB21PJ, array('cursor'=>DB2_SCROLLABLE));
		  $rowdb21PJ = db2_fetch_assoc($stmt1PJ);								
$sqlDB22PJ =" 
SELECT BNG.BOMCOMPSEQUENCE,BNG.SUBCODE01,BNG.SUBCODE02,BNG.SUBCODE03,BNG.SUBCODE04,
BNG.SUBCODE05,BNG.SUBCODE06,BNG.SUBCODE07,BNG.SUBCODE08,BNG.QUANTITYPER,BNG.ORDERCODE,BNG.RESERVATIONLINE FROM ITXVIEWHEADERKNTORDER 
LEFT OUTER JOIN (
SELECT PRODUCTIONRESERVATION.*,FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION FROM DB2ADMIN.PRODUCTIONRESERVATION 
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
PRODUCTIONRESERVATION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
WHERE PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE02='$rD[CODE2]' AND 
PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE03='$rD[CODE3]' AND 
PRODUCTIONRESERVATION.RELATEDCMPBILLOFMATSUBCODE04='$rD[CODE4]' AND
PRODUCTIONRESERVATION.PRODUCTIONORDERCODE='".$rowdb21P['PRODUCTIONORDERCODE']."'
AND 
CONCAT(trim(PRODUCTIONRESERVATION.SUBCODE01), 
CONCAT(trim(PRODUCTIONRESERVATION.SUBCODE02), 
CONCAT(trim(PRODUCTIONRESERVATION.SUBCODE03), 
CONCAT(trim(PRODUCTIONRESERVATION.SUBCODE04), 
CONCAT(trim(PRODUCTIONRESERVATION.SUBCODE05), 
CONCAT(trim(PRODUCTIONRESERVATION.SUBCODE06), 
CONCAT(trim(PRODUCTIONRESERVATION.SUBCODE07), 
trim(PRODUCTIONRESERVATION.SUBCODE08))))))))= '$kdbng'
ORDER BY BOMCOMPSEQUENCE ASC
) BNG ON BNG.PRODUCTIONORDERCODE=ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE 
WHERE ITXVIEWHEADERKNTORDER.ITEMTYPEAFICODE ='KGF' AND (ITXVIEWHEADERKNTORDER.PROJECTCODE ='$rD[PROJECT]' OR ITXVIEWHEADERKNTORDER.ORIGDLVSALORDLINESALORDERCODE ='$rD[PROJECT]') AND 
ITXVIEWHEADERKNTORDER.SUBCODE02='$rD[CODE2]' AND ITXVIEWHEADERKNTORDER.SUBCODE03='$rD[CODE3]' AND
ITXVIEWHEADERKNTORDER.SUBCODE04='$rD[CODE4]' AND
ITXVIEWHEADERKNTORDER.PRODUCTIONORDERCODE='".$rowdb21P['PRODUCTIONORDERCODE']."' AND
(ITXVIEWHEADERKNTORDER.PROGRESSSTATUS='2' OR ITXVIEWHEADERKNTORDER.PROGRESSSTATUS='6') 
GROUP BY BNG.BOMCOMPSEQUENCE,BNG.SUBCODE01,BNG.SUBCODE02,BNG.SUBCODE03,BNG.SUBCODE04,
BNG.SUBCODE05,BNG.SUBCODE06,BNG.SUBCODE07,BNG.SUBCODE08,BNG.QUANTITYPER,BNG.ORDERCODE,BNG.RESERVATIONLINE
ORDER BY BNG.BOMCOMPSEQUENCE ASC
	   ";	
$stmt2PJ   = db2_exec($conn1,$sqlDB22PJ, array('cursor'=>DB2_SCROLLABLE));
$rowdb22PJ = db2_fetch_assoc($stmt2PJ);

								
	$persen=number_format($rowdb22PJ['QUANTITYPER']*100,2);
	$kbhBenang=round((round($rowdb21PJ['BASEPRIMARYQUANTITY']-$rowdb21PJ['QTYSALIN'],2)*($persen))/100,2);								
								
$sqlDB23 =" 
SELECT SUM(BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
SUM(SHIPPEDBASEPRIMARYQUANTITY) AS SHIPPEDBASEPRIMARYQUANTITY,
SUM(RECEIVEDBASEPRIMARYQUANTITY) AS RECEIVEDBASEPRIMARYQUANTITY,
SUM(P501) AS P501,
SUM(M904) AS M904 FROM
(
SELECT ITNDOC.*,STKP501.QTYSTK AS P501 ,STKM904.QTYSTK AS M904 FROM
(SELECT il.INTDOCPROVISIONALCOUNTERCODE,il.INTDOCUMENTPROVISIONALCODE,SUM(il.BASEPRIMARYQUANTITY) AS BASEPRIMARYQUANTITY,
SUM(il.SHIPPEDBASEPRIMARYQUANTITY) AS SHIPPEDBASEPRIMARYQUANTITY,
SUM(il.RECEIVEDBASEPRIMARYQUANTITY) AS RECEIVEDBASEPRIMARYQUANTITY
FROM INTERNALDOCUMENT i LEFT JOIN
INTERNALDOCUMENTLINE il ON i.PROVISIONALCODE=il.INTDOCUMENTPROVISIONALCODE AND i.PROVISIONALCOUNTERCODE=il.INTDOCPROVISIONALCOUNTERCODE  
WHERE il.EXTERNALREFERENCE ='$rD[PROJECT]' AND
il.INTERNALREFERENCE LIKE '$rD[HANGERNO]%' AND
il.SUBCODE01='$rowdb22PJ[SUBCODE01]' AND
il.SUBCODE02='$rowdb22PJ[SUBCODE02]' AND
il.SUBCODE03='$rowdb22PJ[SUBCODE03]' AND
il.SUBCODE04='$rowdb22PJ[SUBCODE04]' AND
il.SUBCODE05='$rowdb22PJ[SUBCODE05]' AND
il.SUBCODE06='$rowdb22PJ[SUBCODE06]' AND
il.SUBCODE07='$rowdb22PJ[SUBCODE07]' AND
il.SUBCODE08='$rowdb22PJ[SUBCODE08]'
GROUP BY il.INTDOCPROVISIONALCOUNTERCODE,il.INTDOCUMENTPROVISIONALCODE) ITNDOC
LEFT OUTER JOIN (
SELECT SUM(b.BASEPRIMARYQUANTITYUNIT) AS QTYSTK,s.ORDERCODE,s.TOKENCODE  FROM STOCKTRANSACTION s 
LEFT OUTER JOIN
BALANCE b ON s.ITEMELEMENTCODE =b.ELEMENTSCODE
WHERE s.TOKENCODE ='RECEIVED' AND b.LOGICALWAREHOUSECODE='P501'
GROUP BY s.ORDERCODE,s.TOKENCODE
) STKP501 ON STKP501.ORDERCODE=ITNDOC.INTDOCUMENTPROVISIONALCODE
LEFT OUTER JOIN (
SELECT SUM(b.BASEPRIMARYQUANTITYUNIT) AS QTYSTK,s.ORDERCODE,s.TOKENCODE  FROM STOCKTRANSACTION s 
LEFT OUTER JOIN
BALANCE b ON s.ITEMELEMENTCODE =b.ELEMENTSCODE
WHERE s.TOKENCODE ='RECEIVED' AND b.LOGICALWAREHOUSECODE='M904'
GROUP BY s.ORDERCODE,s.TOKENCODE
) STKM904 ON STKM904.ORDERCODE=ITNDOC.INTDOCUMENTPROVISIONALCODE
) ITN
";	
$stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
$rowdb23 = db2_fetch_assoc($stmt3);								
								
$Sdb23="
	SELECT x.* FROM DB2ADMIN.PRODUCTIONRESERVATIONCOMMENT x
WHERE PRODUCTIONRESERVATIONORDERCODE ='$rowdb22PJ[ORDERCODE]' AND PRORESERVATIONRESERVATIONLINE ='$rowdb22PJ[RESERVATIONLINE]'
	";
	$st13   = db2_exec($conn1,$Sdb23, array('cursor'=>DB2_SCROLLABLE));
	$rdb23 = db2_fetch_assoc($st13);								
	echo"<tr>
  	<td align=center>$no</td>
	<td align=center>".$rD['PROJECT']."</td>
	<td align=center>".$rD['HANGERNO']."</td>
	<td align=center>".$rdb23['COMMENTTEXT']."</td>	
	<td align=right>".number_format(round($kbhBenang,3),3)."</td>
	<td align=right>".number_format(round($rowdb23['BASEPRIMARYQUANTITY'],3),3)."</td>
	<td align=right>".number_format(round($kbhBenang,3)-round($rowdb23['BASEPRIMARYQUANTITY'],3),3)."</td>
	</tr>";
				$no++;		
				$totK+=round($kbhBenang,3);
				$totP+=round($rowdb23['BASEPRIMARYQUANTITY'],3);
				$totKB+=(round($kbhBenang,3)-round($rowdb23['BASEPRIMARYQUANTITY'],3));				
							}
								

     
  ?>							
						</tbody>
				<tfoot>
				<tr>
				  			  <td>&nbsp;</td>
							  <td align="right">&nbsp;</td>
							  <td align="right">&nbsp;</td>
							  <td align="right"><strong>Total</strong></td>
							  <td align="right"><strong><?php echo number_format($totK,3); ?></strong></td>
							  <td align="right"><strong><?php echo number_format($totP,3); ?></strong></td>
							  <td align="right"><strong><?php echo number_format($totKB,3); ?></strong></td>
				  </tr>
				</tfoot>
					</table>   	
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              			  	
            </div>
			</form>	
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
               
<script>
  $(function () {	 
	$('.select2sts').select2({
    placeholder: "Select a status",
    allowClear: true
});   
  });
</script>
<script>
  $(function () {
    $("#lookup40").DataTable({
	  "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,	
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#lookup40_wrapper .col-md-6:eq(0)');		 
  });
</script>
