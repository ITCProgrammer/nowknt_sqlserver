<?php
$idUp = isset($_GET['id']) ? (int) $_GET['id'] : 0;

sqlsrv_query($con, "DELETE FROM dbknitt.tbl_stokfull WHERE id_upload = ?", [$idUp]);
sqlsrv_query($con, "DELETE FROM dbknitt.tbl_stokloss WHERE id_upload = ?", [$idUp]);
sqlsrv_query($con, "DELETE FROM dbknitt.tbl_upload WHERE id = ?", [$idUp]);

echo "<script type=\"text/javascript\">
            window.location = \"DataUpload\"
      </script>";
?>
