<?php
if($_POST){ 
	extract($_POST);
	$id = $_POST['id'];
	$mekanik = $_POST['mekanik'];
	$mekanik2 = $_POST['mekanik2'];
	$mekanik3 = $_POST['mekanik3'];
	$ket = str_replace("'","''",$_POST['ket']);
	$sql = "UPDATE dbknitt.tbl_jadwal
			SET
				sts = ?,
				kategori = ?,
				tgl_servis = ?,
				mekanik = ?,
				mekanik2 = ?,
				mekanik3 = ?,
				ket = ?
			WHERE id = ?
			";

			$params = [
				$_POST['sts'],
				$_POST['kategori'],
				$_POST['tgl_service'],
				$mekanik,
				$mekanik2,
				$mekanik3,$ket,$id];

			$stmt = sqlsrv_query($con, $sql, $params);
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
