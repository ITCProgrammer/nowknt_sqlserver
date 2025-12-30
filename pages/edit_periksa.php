<?php
if($_POST){ 
	extract($_POST);
	$id = $_POST['id'];
	$mekanik = $_POST['mekanik'];
	$mekanik2 = $_POST['mekanik2'];
	$mekanik3 = $_POST['mekanik3'];
	$ket = str_replace("'","''",$_POST['ket']);
	$sqlupdate=mysqli_query($con,"UPDATE `tbl_jadwal` SET
				`sts`='".$_POST['sts']."',
				`kategori`='".$_POST['kategori']."',
				`tgl_servis`='".$_POST['tgl_service']."',
				`mekanik`='$mekanik',
				`mekanik2`='$mekanik2',
				`mekanik3`='$mekanik3',
				`ket`='$ket'
				WHERE `id`='$id' LIMIT 1");
				echo " <script>window.location='Pemeriksaan-$_POST[no_mesin]';</script>";
				/*echo "<script>swal({
  title: 'Data Telah diUbah',   
  text: 'Klik Ok untuk melanjutkan',
  type: 'success',
  }).then((result) => {
  if (result.value) {
    window.location='Pemeriksaan-".$_POST['no_mesin']."'; 
  }
});</script>";*/
}
?>
