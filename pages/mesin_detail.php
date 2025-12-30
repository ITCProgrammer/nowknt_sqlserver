<?php
ini_set("error_reporting", 1);
include("../koneksi.php");
    $modal_id=$_GET['id'];
    $modal=mysqli_query($con,"SELECT * FROM `tbl_mesin` WHERE kd_dtex='$modal_id' ");
while ($r=mysqli_fetch_array($modal)) {
    ?>
<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=edit_periksa" enctype="multipart/form-data">
      <div class="modal-header">
      <h5 class="modal-title">Detail Produksi Mesin <?php echo $modal_id; ?></h5>
              	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>  
      </div>
      <div class="modal-body">
        <table id="tbl3" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 12px;">
          <thead class="bg-red">
            <tr>
              <th width="32">
                <div align="center">No</div>
              </th>
              <th width="134">
                <div align="center">Tanggal</div>
              </th>
              <th width="253">
                <div align="center">DemandNo</div>
              </th>
              <th width="298">
                <div align="center">Jenis Kain</div>
              </th>
              <th width="179">
                <div align="center">Perhari</div>
              </th>
              <th width="132">
                <div align="center">Total</div>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php
  $sqlDB2="  SELECT SUBSTR(INSPECTIONSTARTDATETIME,1,10) AS TGL,DEMANDCODE,CONCAT(trim(PRODUCTIONDEMAND.SUBCODE02), 
 trim(PRODUCTIONDEMAND.SUBCODE03)) AS KODEITEM,SUM(WEIGHTNET) AS KG FROM ELEMENTSINSPECTION 
 LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ELEMENTSINSPECTION.DEMANDCODE 
 LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo' 
 WHERE ELEMENTITEMTYPECODE='KGF' AND ADSTORAGE.VALUESTRING='$modal_id' 
 GROUP BY SUBSTR(INSPECTIONSTARTDATETIME,1,10),DEMANDCODE,ADSTORAGE.VALUESTRING,PRODUCTIONDEMAND.SUBCODE02,PRODUCTIONDEMAND.SUBCODE03 ";
					  
$no=1;   
$c=0;
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
while($rowdb2 = db2_fetch_assoc($stmt)){
        $no++;
        $bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
        /*if($r['TGL']<$r['tgl_perbaikan']){
            $total=0;
            $warna="#EDCF65";
        }
          else{
            $warna="";
        }*/
        $total+=$rowdb2['KG'];
        ?>
            <tr bgcolor="<?php echo $bgcolor; ?>">
              <td align="center">
                <?php echo $no; ?>
              </td>
              <td align="center">
                <?php echo $rowdb2['TGL']; ?>
              </td>
              <td align="center">
                <?php echo $rowdb2['DEMANDCODE']; ?>
              </td>
              <td align="center">
                <?php echo $rowdb2['KODEITEM']; ?>
              </td>
              <td align="right">
                <?php echo $rowdb2['KG']; ?>
              </td>
              <td align="right" bgcolor="<?php echo $warna; ?>">
                <?php echo $total; ?>
              </td>
            </tr>
            <?php
    } ?>
          </tbody>

        </table>
      </div>
    
    </form>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
  $(function() {
    $("#tbl3").dataTable();
  });

</script>
<?php
} ?>
