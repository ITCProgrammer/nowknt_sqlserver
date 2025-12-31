<?php
include("../koneksi.php");
if ($_POST) {
    $id  = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $sts = isset($_POST['sts']) ? $_POST['sts'] : '';

    if ($sts === "Closed") {
        $tglClsd = " , tgl_closed=GETDATE() ";
    } else {
        $tglClsd = " , tgl_closed=NULL ";
    }

    $sql = "UPDATE dbknitt.tbl_upload SET [status]=? {$tglClsd} WHERE id=?";
    sqlsrv_query($con, $sql, [$sts, $id]);

    echo "<script type=\"text/javascript\">
            window.location = \"DataUpload\"
      </script>";
}
?>
