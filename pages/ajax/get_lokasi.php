<?php
include "../../koneksi.php";

if(isset($_POST['zone'])){
    $zone = $_POST['zone'];
    
    $query = "SELECT DISTINCT TRIM(nama) AS nama FROM dbknitt.tbl_lokasi WHERE zone = ? ORDER BY nama ASC";
    $params = array($zone);
    $sqlL = sqlsrv_query($con, $query, $params);
    
    echo '<option value="">Pilih</option>';

    if($sqlL){
        while($rL = sqlsrv_fetch_array($sqlL, SQLSRV_FETCH_ASSOC)){
            echo '<option value="'.$rL['nama'].'">'.$rL['nama'].'</option>';
        }
    }
}
?>