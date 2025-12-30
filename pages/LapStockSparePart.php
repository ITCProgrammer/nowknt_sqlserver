<?php
$WHS	= isset($_POST['warehouse']) ? $_POST['warehouse'] : '';
$ZONE	= isset($_POST['zone']) ? $_POST['zone'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Stock Sparepart</h3>

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
               <label for="tgl_awal" class="col-md-1">Warehouse</label>
               <div class="col-md-2"> 
				   <select name="warehouse" class="form-control">
					   <option value="">Pilih</option>
					   <option value="M211" <?php if($WHS=="M211"){ echo "SELECTED"; } ?>>M211</option>
					   <option value="P211" <?php if($WHS=="P211"){ echo "SELECTED"; } ?>>P211</option>
					   <option value="P212" <?php if($WHS=="P212"){ echo "SELECTED"; } ?>>P212</option>
					   <option value="ALL" <?php if($WHS=="ALL"){ echo "SELECTED"; } ?>>ALL</option>
				   </select>
               </div>	
            </div>
			  
<!--
			 <div class="form-group row">
               <label for="tgl_akhir" class="col-md-1">Zone</label>
               <div class="col-md-2">  
                 
			   </div>	
            </div> 
-->
			  
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div> 
		  
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Stock SparePart</h3> 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th>Creation Date</th>
                    <th>Last Update</th>
                    <th>Transaction No.</th>
                    <th>Category</th>
                    <th>Part Type</th>
                    <th>Brand</th>
                    <th>Serial No</th>
                    <th>Long Description</th>
                    <th>Lot</th>
                    <th>Qlt</th>
                    <th>Elements</th>
                    <th>Warehouse</th>
                    <th>Zone</th>
                    <th>Location</th>
                    <th>Qty</th>
                    <th>GRD</th>
                    <th>Note</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php	
if($WHS=="ALL"){
	$where1= " LOGICALWAREHOUSECODE IN ('M211','P211','P212') AND ";
}else{
	$where1= " LOGICALWAREHOUSECODE = '$WHS' AND ";
}					  
$sqlDB2 =" SELECT * FROM BALANCE WHERE $where1 ITEMTYPECODE='SPR' AND DECOSUBCODE01 ='KNT'";	
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
while($rowdb2 = db2_fetch_assoc($stmt)){
	
if($WHS!="P212"){
	$where =" 
        AND s.DECOSUBCODE02 = '".TRIM($rowdb2['DECOSUBCODE02'])."'
				AND s.DECOSUBCODE03 = '".TRIM($rowdb2['DECOSUBCODE03'])."'
				AND s.DECOSUBCODE04 = '".TRIM($rowdb2['DECOSUBCODE04'])."'
				AND s.DECOSUBCODE05 = '".TRIM($rowdb2['DECOSUBCODE05'])."'
				AND s.DECOSUBCODE06 = '".TRIM($rowdb2['DECOSUBCODE06'])."' ";

  $sqlDB2ST =" SELECT
	s.CREATIONDATETIME,
	s.LASTUPDATEDATETIME AS LASTUPDATE,
	s.TRANSACTIONNUMBER,
	a1.VALUESTRING AS GRD,
	a2.VALUESTRING AS KET,
	p.LONGDESCRIPTION
FROM
	STOCKTRANSACTION s	
LEFT OUTER JOIN PRODUCT p ON
	p.ITEMTYPECODE = 'SPR'
	AND p.SUBCODE01 = 'KNT'
	AND s.DECOSUBCODE02 = p.SUBCODE02
	AND s.DECOSUBCODE03 = p.SUBCODE03
	AND s.DECOSUBCODE04 = p.SUBCODE04
	AND s.DECOSUBCODE05 = p.SUBCODE05
	AND s.DECOSUBCODE06 = p.SUBCODE06
LEFT OUTER JOIN ADSTORAGE a1 ON
	s.ABSUNIQUEID = a1.UNIQUEID
	AND a1.FIELDNAME = 'GradeSPR'
LEFT OUTER JOIN ADSTORAGE a2 ON
	s.ABSUNIQUEID = a2.UNIQUEID
	AND a2.FIELDNAME = 'Note'
WHERE
	s.TEMPLATECODE = 'OPN'
	AND s.ITEMTYPECODE = 'SPR'
	AND s.DECOSUBCODE01 = 'KNT' 
	$where
	";	      
	}else{
	$where =" 
				AND s.DECOSUBCODE02 = '".TRIM($rowdb2['DECOSUBCODE02'])."'
				AND s.DECOSUBCODE03 = '".TRIM($rowdb2['DECOSUBCODE03'])."'
				AND s.DECOSUBCODE04 = '".TRIM($rowdb2['DECOSUBCODE04'])."'
				AND s.DECOSUBCODE05 = '".TRIM($rowdb2['DECOSUBCODE05'])."'
				AND s.DECOSUBCODE06 = '".TRIM($rowdb2['DECOSUBCODE06'])."'
				AND s.ITEMELEMENTCODE = '".TRIM($rowdb2['ELEMENTSCODE'])."' ";	

 $sqlDB2ST =" SELECT
  s.CREATIONDATETIME,
  b.LASTUPDATEDATETIME AS LASTUPDATE,
  s.TRANSACTIONNUMBER,
  a1.VALUESTRING AS GRD,
  a2.VALUESTRING AS KET,
  p.LONGDESCRIPTION
FROM
  STOCKTRANSACTION s	
LEFT OUTER JOIN PRODUCT p ON
  p.ITEMTYPECODE = 'SPR'
  AND p.SUBCODE01 = 'KNT'
  AND s.DECOSUBCODE02 = p.SUBCODE02
  AND s.DECOSUBCODE03 = p.SUBCODE03
  AND s.DECOSUBCODE04 = p.SUBCODE04
  AND s.DECOSUBCODE05 = p.SUBCODE05
  AND s.DECOSUBCODE06 = p.SUBCODE06
LEFT OUTER JOIN ADSTORAGE a1 ON
  s.ABSUNIQUEID = a1.UNIQUEID
  AND a1.FIELDNAME = 'GradeSPR'
LEFT OUTER JOIN ADSTORAGE a2 ON
  s.ABSUNIQUEID = a2.UNIQUEID
  AND a2.FIELDNAME = 'Note'
LEFT OUTER JOIN ELEMENTS b ON	
  s.ITEMELEMENTCODE=b.CODE
WHERE
  s.TEMPLATECODE = 'OPN'
  AND s.ITEMTYPECODE = 'SPR'
  AND s.DECOSUBCODE01 = 'KNT' 
  $where
  ";	      
	}
 
$stmtST   = db2_exec($conn1,$sqlDB2ST, array('cursor'=>DB2_SCROLLABLE));	
$rowdb2ST = db2_fetch_assoc($stmtST);
	
if($WHS!="P212"){
	$whereU =" 
        AND s.DECOSUBCODE02 = '".TRIM($rowdb2['DECOSUBCODE02'])."'
				AND s.DECOSUBCODE03 = '".TRIM($rowdb2['DECOSUBCODE03'])."'
				AND s.DECOSUBCODE04 = '".TRIM($rowdb2['DECOSUBCODE04'])."'
				AND s.DECOSUBCODE05 = '".TRIM($rowdb2['DECOSUBCODE05'])."'
				AND s.DECOSUBCODE06 = '".TRIM($rowdb2['DECOSUBCODE06'])."' ";

  $sqlDB2STU =" SELECT
	s.CREATIONDATETIME,
	s.LASTUPDATEDATETIME AS LASTUPDATE,
	s.TRANSACTIONNUMBER,
	a1.VALUESTRING AS GRD,
	a2.VALUESTRING AS KET,
	p.LONGDESCRIPTION
FROM
	STOCKTRANSACTION s	
LEFT OUTER JOIN PRODUCT p ON
	p.ITEMTYPECODE = 'SPR'
	AND p.SUBCODE01 = 'KNT'
	AND s.DECOSUBCODE02 = p.SUBCODE02
	AND s.DECOSUBCODE03 = p.SUBCODE03
	AND s.DECOSUBCODE04 = p.SUBCODE04
	AND s.DECOSUBCODE05 = p.SUBCODE05
	AND s.DECOSUBCODE06 = p.SUBCODE06
LEFT OUTER JOIN ADSTORAGE a1 ON
	s.ABSUNIQUEID = a1.UNIQUEID
	AND a1.FIELDNAME = 'GradeSPR'
LEFT OUTER JOIN ADSTORAGE a2 ON
	s.ABSUNIQUEID = a2.UNIQUEID
	AND a2.FIELDNAME = 'Note'
WHERE
	s.TEMPLATECODE = 'OPN'
	AND s.ITEMTYPECODE = 'SPR'
	AND s.DECOSUBCODE01 = 'KNT' 
	$whereU
	";	      
	}else{
	$whereU =" 
				AND s.DECOSUBCODE02 = '".TRIM($rowdb2['DECOSUBCODE02'])."'
				AND s.DECOSUBCODE03 = '".TRIM($rowdb2['DECOSUBCODE03'])."'
				AND s.DECOSUBCODE04 = '".TRIM($rowdb2['DECOSUBCODE04'])."'
				AND s.DECOSUBCODE05 = '".TRIM($rowdb2['DECOSUBCODE05'])."'
				AND s.DECOSUBCODE06 = '".TRIM($rowdb2['DECOSUBCODE06'])."'
				AND s.ITEMELEMENTCODE = '".TRIM($rowdb2['ELEMENTSCODE'])."' ";	

 $sqlDB2STU =" SELECT *
FROM (
    SELECT
        s.TEMPLATECODE,
        s.ITEMTYPECODE,
        s.DECOSUBCODE01,
        s.CREATIONDATETIME,
        b.LASTUPDATEDATETIME AS LASTUPDATE,
        s.TRANSACTIONNUMBER,
        a1.VALUESTRING AS GRD,
        a2.VALUESTRING AS KET,
        p.LONGDESCRIPTION,
        ROW_NUMBER() OVER (ORDER BY s.CREATIONDATETIME DESC) AS RN
    FROM STOCKTRANSACTION s
    LEFT JOIN PRODUCT p ON
        p.ITEMTYPECODE = 'SPR'
        AND p.SUBCODE01 = 'KNT'
        AND s.DECOSUBCODE02 = p.SUBCODE02
        AND s.DECOSUBCODE03 = p.SUBCODE03
        AND s.DECOSUBCODE04 = p.SUBCODE04
        AND s.DECOSUBCODE05 = p.SUBCODE05
        AND s.DECOSUBCODE06 = p.SUBCODE06
    LEFT JOIN ADSTORAGE a1 
        ON s.ABSUNIQUEID = a1.UNIQUEID
       AND a1.FIELDNAME = 'GradeSPR'
    LEFT JOIN ADSTORAGE a2 
        ON s.ABSUNIQUEID = a2.UNIQUEID
       AND a2.FIELDNAME = 'Note'
    LEFT JOIN ELEMENTS b 
        ON s.ITEMELEMENTCODE = b.CODE
    WHERE s.ITEMTYPECODE = 'SPR'
      AND s.DECOSUBCODE01 = 'KNT'
	  $whereU
) tmp
WHERE RN = 1;
  ";	      
	}
 
$stmtSTU   = db2_exec($conn1,$sqlDB2STU, array('cursor'=>DB2_SCROLLABLE));	
$rowdb2STU = db2_fetch_assoc($stmtSTU);	
?>
	  <tr>
      <td>    
        <?php 
        if (!empty($rowdb2ST['CREATIONDATETIME'])) {
            echo substr($rowdb2ST['CREATIONDATETIME'], 0, 10);
        } else {
            echo substr($rowdb2['CREATIONDATETIME'], 0, 10);
        }
        ?>
      </td>
      <td>
        <?php
          //jika last update stock transaction tidak kosong maka pakai last update stock transaction
          //jika last update stock transaction kosong maka pakai last update balance
          //jika last update balance kosong maka pakai creation date balance
          $date = '';

          if (!empty($rowdb2STU['CREATIONDATETIME'])) {
              $date = $rowdb2STU['CREATIONDATETIME'];
          } elseif (!empty($rowdb2['LASTUPDATEDATETIME'])) {
              $date = $rowdb2['LASTUPDATEDATETIME'];
          } else {
              $date = $rowdb2['CREATIONDATETIME'];
          }

          echo substr($date, 0, 10);
        ?>
      <!-- <?php echo substr($rowdb2ST['LASTUPDATE'],0,10); ?> -->
      </td>
      <td><?php echo $rowdb2ST['TRANSACTIONNUMBER']; ?></td>
      <td><?php echo $rowdb2['DECOSUBCODE02']; ?></td>
      <td><?php echo $rowdb2['DECOSUBCODE04']; ?></td>
      <td><?php echo $rowdb2['DECOSUBCODE05']; ?></td>
      <td><?php echo $rowdb2['DECOSUBCODE06']; ?></td>
      <td><?php echo $rowdb2ST['LONGDESCRIPTION']; ?></td>
      <td><?php echo $rowdb2['LOTCODE']; ?></td>
      <td><?php echo $rowdb2['QUALITYLEVELCODE']; ?></td>
      <td><?php echo $rowdb2['ELEMENTSCODE']; ?></td>
      <td><?php echo $rowdb2['LOGICALWAREHOUSECODE']; ?></td>
      <td><?php echo $rowdb2['WHSLOCATIONWAREHOUSEZONECODE']; ?></td>
      <td><?php echo $rowdb2['WAREHOUSELOCATIONCODE']; ?></td>
      <td><?php echo number_format($rowdb2['BASEPRIMARYQUANTITYUNIT']); ?></td>
      <td><?php echo $rowdb2ST['GRD']; ?></td>
      <td><?php echo $rowdb2ST['KET']; ?></td>
      </tr>		
					<?php } ?>  
				  </tbody>                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>  
		  </form>	
      </div><!-- /.container-fluid -->
    <!-- /.content -->
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