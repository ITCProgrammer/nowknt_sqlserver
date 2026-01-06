<?php
ini_set("error_reporting", 1);
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$modal=sqlsrv_query($con,"SELECT * FROM dbknitt.tbl_mesin WHERE no_mesin='$modal_id' ");
while($r=sqlsrv_fetch_array($modal)){
?>
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=edit_periksa" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title">Riwayat Perbaikan Mesin <?php echo $modal_id; ?></h5>
              	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="modal-body">
              <table id="tbl4" class="table table-sm table-bordered table-hover table-striped" width="100%" style="font-size: 12px;">
<thead class="bg-purple">
   <tr>
      <th width="50"><div align="center">No</div></th>
      <th width="181"><div align="center">Tanggal Perbaikan </div></th>
      <th width="966"><div align="center">Jenis Perbaikan</div></th>
      </tr>
</thead>
<tbody>
  <?php 
  $sql=sqlsrv_query($con," SELECT
	jenis_perbaikan AS JENIS_PERBAIKAN,
	FORMAT(tgl_perbaikan,'yyyy-MM-dd') AS TGL_PERBAIKAN
FROM
	dbknitt.tbl_perbaikan_mesin a 
INNER JOIN dbknitt.tbl_mesin b ON a.id_mesin=b.id
WHERE b.no_mesin='$modal_id'");
  while($r=sqlsrv_fetch_array($sql)){
	 
		$no++;
		$bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';
	  	
	?>
   <tr bgcolor="<?php echo $bgcolor; ?>">
     <td align="center"><?php echo $no; ?></td>
     <td align="center"><?php echo $r['TGL_PERBAIKAN']; ?></td>
     <td ><?php echo $r['JENIS_PERBAIKAN']; ?></td>
     </tr>
   <?php } ?>
   </tbody>
   
</table>
              </div>
             
            </form>
            </div>
            <!-- /.modal-content -->
  </div>
          <!-- /.modal-dialog -->
<script type="text/javascript">
$(function (){
                $("#tbl4").dataTable();
            });
</script>
          <?php } ?>


