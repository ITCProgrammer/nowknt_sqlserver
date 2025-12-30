<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="180">	
<title>RMP to KNT</title>
<!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">	
</head>
<body>
<form role="form" method="post" enctype="multipart/form-data" name="form1">	
<div class="row">
            <div class="col-lg-12">
              <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list"></i> BO RMP to KNT</h3>               
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table width="100%" id="datagridscroll3" class="table table-bordered table-sm table-striped table-hover" style="font-size:13px;">
                            <thead>
                                <tr class="text-center">
                                    <th width="17" style="width: 10px">No</th>
                                    <th width="92">ProjectCode</th>
                                    <th width="173">PO</th>
                                    <th width="225">Factory Name</th>
                                    <th width="156">Buyer</th>
                                    <th width="292">Fabric Type</th>
                                    <th width="166">Sales Assistant</th>
                                    <th width="136">Internal Fabric Delivery</th>
                                    <th width="74">Status</th>
                                </tr>
                            </thead>
                            <tbody>								
<?php
$no=1;								
$sql=mysqli_query($con,"SELECT * FROM tbl_salesorder WHERE not rmp_terima='' and status_knt='' ORDER BY tgl_buat_po DESC");
while($r=mysqli_fetch_array($sql)){
?>
                                    <tr>
                                        <td align="center"><?php echo $no; ?></td>
                                        <td align="left"><a href="#" id="<?php echo $r['projectcode']; ?>" class="detail_log"><?php echo $r['projectcode'];?></a></td>
                                        <td align="left"><?php echo $r['pono']; ?></td>
                                        <td align="left"><?php echo $r['factory_name'];?></td>
                                        <td align="left"><?php echo $r['buyer'];?></td>
                                        <td align="left"><?php echo $r['jenis_kain'];?></td>
                                        <td align="center"><?php echo $r['sales_assistant'];?></td>
                                        <td align="center"><?php echo $r['tgl_delivery'];?></td>
                                        <td align="center"><a data-pk="<?php echo $r['projectcode']; ?>" data-value="<?php echo $r['status_knt']; ?>" class="status_knt" href="javascipt:void(0)"><?php echo $r['status_knt']; ?></a></td>
                                    </tr>                                                                       
                                <?php $no++; } ?>
                            </tbody>
							<tfoot>
							<tr>
                                      <td align="center">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                      <td align="left">&nbsp;</td>
                                      <td align="center">&nbsp;</td>
                                      <td align="center">&nbsp;</td>
                                      <td align="center">&nbsp;</td>
                                    </tr> 
							</tfoot>
                        </table>
                  </div>
                </div>
            </div>
        </div>
	</form>
	<div id="DetailLogPO" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
</body>
</html>
<!-- /.modal-dialog -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>	

