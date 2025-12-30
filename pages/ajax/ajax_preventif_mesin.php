<?php
    ini_set("error_reporting", 1);
    session_start();
    //include config
    include "../../koneksi.php";
    include('Response.php');

    date_default_timezone_set('Asia/Jakarta'); 
    $response = new Response();
    $response->setHTTPStatusCode(201);
    if (isset($_POST['status'])) {
        $id = intval($_POST['id_dt']);
        if($_POST['status']=="update_batas_produksi" && $id != 0){
            $update = "UPDATE tbl_mesin 
                 SET batas_produksi =? 
                 WHERE id = ? LIMIT 1";
            $confirm=mysqli_prepare( $con, $update );
            mysqli_stmt_bind_param($confirm, "ss", $_POST['batas_produksi'],$id );
            if(mysqli_stmt_execute($confirm)){ 
                $response->setSuccess(true);
                $response->addMessage("Berhasil Update Batas Produksi");
                $response->addMessage($id);
                $response->send();
            }
            else {
                $response->setSuccess(false);
                $response->addMessage("Gagal Update Batas Produksi : ".mysqli_error($con));
                $response->send();
            }
        }
        else{
            $response->setSuccess(false);
            $response->addMessage("Error Status");
            $response->send();
        }
    }else{
        $response->setSuccess(false);
        $response->addMessage("Tidak ada Session");
        $response->send();
    }