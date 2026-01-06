<?php
    $modal_id=$_GET['id'];
	$mesin=$_GET['mc'];
    $sql = "DELETE FROM dbknitt.tbl_jadwal WHERE id = ?";
    $params = [$modal_id];
    $modal1 = sqlsrv_query($con, $sql, $params);
    // $modal1=mysqli_query($con,"DELETE FROM tbl_jadwal WHERE id='$modal_id' ");
    if ($modal1) {
        echo "<script>window.location='Pemeriksaan-$mesin';</script>";
    } 
    if ($modal1 === false) {
        die(print_r(sqlsrv_errors(), true));
    }
