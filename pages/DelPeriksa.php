<?php
    $modal_id=$_GET['id'];
	$mesin=$_GET['mc'];
    $modal1=mysqli_query($con,"DELETE FROM tbl_jadwal WHERE id='$modal_id' ");
    if ($modal1) {
        echo "<script>window.location='Pemeriksaan-$mesin';</script>";
    } else {
        echo "<script>alert('Gagal Hapus');window.location='Pemeriksaan-$mesin';</script>";
    }
