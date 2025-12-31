<?php
$stmt = sqlsrv_query($con, "INSERT INTO dbknitt.tbl_upload ([status]) VALUES ('Open')");

if ($stmt === false) {
    $error = addslashes(print_r(sqlsrv_errors(), true));
    echo "<script>alert('Gagal menambah upload: {$error}'); history.back();</script>";
    exit;
}

echo "<script type=\"text/javascript\">
            alert(\"Data Berhasil Ditambah\");
            window.location = \"DataUpload\"
            </script>";
?>
