<?php
session_start();
//include config
include"koneksi.php";
ini_set("error_reporting", 1);

//request page
$page = isset($_GET['p'])?$_GET['p']:'';
$act  = isset($_GET['act'])?$_GET['act']:'';
$id   = isset($_GET['id'])?$_GET['id']:'';
$page = strtolower($page);
?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NOWknt | <?php if ($_GET['p']!="") {
    echo ucwords($_GET['p']);
} else {
    echo "Home";
}?></title>

  <!-- Google Font: Source Sans Pro -->
  <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
   <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">		
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css">	
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">	
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">	  
  <!-- Theme style -->
  <style>
	  .blink_me {
  animation: blinker 1s linear infinite;
}
.bulat{
  border-radius: 50%;
  /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);*/
}
.border-dashed{
		border: 3px dashed #083255;
	}
.border-dashed-tujuan{
		border: 3px dashed #FF0007;
	}
@keyframes blinker {
  50% {
    opacity: 0;
  }
}
	.fixedheader
{
position:fixed;
background-color: #ecf0f5;
margin-left:230px;
left:0;
right:0; 
padding: 15px 15px 0 15px;
}  
	</style>	
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="icon" type="image/png" href="dist/img/ITTI_Logo index.ico">	
</head>
<body class="hold-transition sidebar-collapse layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-dark navbar-blue">
    <div class="container">
      <a href="Home" class="navbar-brand">
        <img src="dist/img/ITTI_Logo 2021.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-dark">NOW<strong>knt</strong></span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="Home" class="nav-link">Home</a>
          </li>
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Stock Opname</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="CheckStock" class="dropdown-item">Check Stock</a></li>
			  <li><a href="DataUpload" class="dropdown-item">Upload Data</a></li>	
			</ul>
          </li>		
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Pengiriman</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
			  <li><a href="KeluarKainGreige" class="dropdown-item">Data Mutasi Greige</a></li>	
              <li><a href="DataKainGreige" class="dropdown-item">Data Kain Greige</a></li>
			  <li><a href="DataKainGreigePerProject" class="dropdown-item">Data Kain Greige Per Project</a></li>
			  <li><a href="AlokasiGreigePerProject" class="dropdown-item">Alokasi Greige Per Project</a></li>	
			</ul>
          </li>
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Effesiensi</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="LapEffesiensi" class="dropdown-item">Effesiensi</a></li>
			  <li><a href="KainBS" class="dropdown-item">Kain BS</a></li>	
			  <li><a href="PJawabBS" class="dropdown-item">Penanggung Jawab BS</a></li>
			  <li><a href="QtyMesin" class="dropdown-item">QTY Per Mesin</a></li>
			</ul>
          </li>		
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Cams</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="StatusMesinCams" class="dropdown-item">Status Mesin Cams</a></li>
			  <li><a href="StatusMesinCamsIns" class="dropdown-item">Status Mesin Cams (Inspeksi)</a></li>	
<!--			  <li><a href="StatusMesinNOWLama" class="dropdown-item">Status Mesin Lama</a></li>	-->
			  <!-- <li><a href="StatusMesinLayarCams" class="dropdown-item">Status Mesin Layar</a></li>	 -->
			</ul>
          </li>	
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Status</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
            <li><a href="KntSchedule" class="dropdown-item">Knitting Schedule</a></li>
            <li><a href="KntSchedule_all" class="dropdown-item">Knitting Schedule (All Status)</a></li>
              <!-- <li><a href="StatusMesin" class="dropdown-item">Status Mesin</a></li> -->
<!--			  <li><a href="StatusMesinNew" class="dropdown-item">Status Mesin New</a></li>	-->
			  <li><a href="StatusMesinHarian" class="dropdown-item">Status Mesin Per Hari</a></li>	
			  <li><a href="StatusMesinLayar" class="dropdown-item">Status Mesin Layar</a></li>
			  <li><a href="StatusMesinLayarNew" class="dropdown-item">Status Mesin Layar New</a></li>	
			  <li><a href="pages/status-mesin-full.php" class="dropdown-item" target="_blank">Status Mesin Layar LT1</a></li>
			  <li><a href="pages/status-mesin-full-lt3.php" class="dropdown-item" target="_blank">Status Mesin Layar LT2+LT3</a></li>
			  <li><a href="pages/status-mesin-full-lt4.php" class="dropdown-item" target="_blank">Status Mesin Layar LT4</a></li>	
			  <li><a href="pages/status-mesin-full-lt5.php" class="dropdown-item" target="_blank">Status Mesin Layar LT5</a></li>	
			  <!-- <li><a href="LossPO" class="dropdown-item">Loss PO Selesai</a></li>	 -->
			</ul>
          </li>
<!--
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Full Check Legacy</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="CheckStockLegacy" class="dropdown-item">Check Stock Legacy</a></li>
			  <li><a href="DataUploadLegacy" class="dropdown-item">Upload Data Legacy</a></li>	
			</ul>
          </li>	
-->
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Produksi Rajut</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="HasilProduksiHarian" class="dropdown-item">Hasil Produksi Harian</a></li>
			  <li><a href="HasilProduksiHarianShift" class="dropdown-item">Hasil Produksi Harian Shift</a></li>	
			  <li><a href="HasilProduksiBulanan" class="dropdown-item">Hasil Produksi Bulanan</a></li>
              <li><a href="HasilProduksiHarianMesin" class="dropdown-item">Hasil Produksi Harian Per Mesin</a></li>
			  <li><a href="EstimasiSelesai" class="dropdown-item">Estimasi Selesai</a></li>	
			</ul>
          </li>	
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Benang</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
<!--              <li><a href="BenangDemand" class="dropdown-item">Benang Per Demand</a></li>-->
			  <li><a href="BenangDemandNew" class="dropdown-item">Benang Per Demand New</a></li>	
			  <li><a href="AnalisaBenang" class="dropdown-item">Analisa Kebutuhan Benang</a></li>
			  <li><a href="AnalisaBenangMesin" class="dropdown-item">Analisa Benang Status Mesin</a></li>
			  <li><a href="AnalisaBenangStok" class="dropdown-item">Analisa Benang Stok</a></li>	
			  <li class="dropdown-submenu dropdown-hover">
			    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Benang Masuk</a>
				<ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li>
                    <a tabindex="-1" href="TerimaTransfer" class="dropdown-item">Terima Transfer</a>
					<a tabindex="-1" href="BenangTurunan" class="dropdown-item">Benang Turunan</a>
					<a tabindex="-1" href="BenangAdd" class="dropdown-item">Tambah Stok</a>  
					<a tabindex="-1" href="CekBenangMasuk" class="dropdown-item">Cek Benang Masuk</a>  
                  </li>
				</ul> 	
					</li>
				<li class="dropdown-submenu dropdown-hover">
			    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Benang Keluar</a>
				<ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li>
                    <a tabindex="-1" href="BenangPakai" class="dropdown-item">Benang Pakai</a>
					<a tabindex="-1" href="BenangDel" class="dropdown-item">Hapus Stok</a>  
					<a tabindex="-1" href="BenangReturn" class="dropdown-item">Benang Return</a>  
                  </li>
				</ul> 	
					</li>
				<li><a href="BenangStokBon" class="dropdown-item">Benang Stok Per BON</a></li>
				<li><a href="StockBenangPerProduction" class="dropdown-item">Stock Benang Per Production Order</a></li>
				<!--<li><a href="BenangStokBonDetail" class="dropdown-item">Benang Stok Per BON Detail</a></li>-->
				<!--<li><a href="BenangStokBonOpname" class="dropdown-item">Benang StokOpname Per BON</a></li>-->
				<li><a href="BenangStokOpname" class="dropdown-item">Benang StokOpname</a></li>
				<li><a href="BenangStokOpnameDetail" class="dropdown-item">Benang StokOpname Detail </a></li>
				<li><a href="BenangStok" class="dropdown-item">Benang Stok</a></li>
				<li><a href="PermohonanBenangHarian" class="dropdown-item">Permohanan Benang Harian</a></li>
			  </ul>
          </li>
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Mesin</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="PreventifMesin" class="dropdown-item">Preventif Mesin</a></li>
			  <li><a href="LapPerformanceMekanik" class="dropdown-item">Laporan Performance Mekanik</a></li>	
			</ul>
          </li>		
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Laporan</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="LapOntimeDeliveryBulan" class="dropdown-item">Laporan Ontime Delivery Bulanan (PO Selesai)</a></li>
			  <li><a href="LapPlanningSettingMesin" class="dropdown-item">Laporan Planning Setting Mesin Rajut</a></li>	
			  <!-- <li><a href="LapProduksiBulan" class="dropdown-item">Laporan Bulanan Produksi</a></li> -->
			  <li><a href="LapInspeksiGreige" class="dropdown-item">Laporan Inspeksi Kain Greige</a></li>
			  <li><a href="LapSummaryInspeksiGreige" class="dropdown-item">Laporan Summary Inspeksi Kain Greige</a></li>	
			  <li><a href="LapIdentBenang" class="dropdown-item">Laporan Identifikasi Benang</a></li>
<!--			  <li><a href="LapIdentBenangOrder" class="dropdown-item">Laporan Identifikasi Benang Per Order</a></li>-->
			  <li><a href="LapIdentBenangOrderNew" class="dropdown-item">Laporan Identifikasi Benang Per Order New</a></li>	
			  <li><a href="LapIdentBenangProject" class="dropdown-item">Laporan Identifikasi Benang Per Project</a></li>	
			  <!-- <li><a href="LapEffBulan" class="dropdown-item">Laporan Bulanan Efficiency</a></li>	 -->
			  <!-- <li><a href="LapStockBenangBulan" class="dropdown-item">Laporan Stock Benang untuk Stock Opname</a></li> -->
			  <li><a href="LapPerformanceInspector" class="dropdown-item">Laporan Performance Inspector</a></li>
			  <li><a href="LapPerformanceYarnOPR" class="dropdown-item">Laporan Performance Yarn OPR</a></li>
			  <li><a href="LapPerformancePetugasPengiriman" class="dropdown-item">Laporan Performance Petugas Pengiriman</a></li>
			  <li><a href="MonitoringProsesSettingMesin" class="dropdown-item">Monitoring Proses Setting Mesin</a></li>		
			  <li><a href="StatusMesinLayarNew" class="dropdown-item">Laporan Memo Penting</a></li>
			  <li><a href="LapStatusSample" class="dropdown-item">Laporan Status Sample</a></li>
			  <li><a href="LapStockSparePart" class="dropdown-item">Laporan Stock SparePart Knitting</a></li>	
			</ul>
          </li>	
        </ul>
      
      </div>
      
    </div>
  </nav>
  <!-- /.navbar -->
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
	<section class="content">  
    <div class="content">
     <?php
          if (!empty($page) and !empty($act)) {
              $files = 'pages/'.$page.'.'.$act.'.php';
          } elseif (!empty($page)) {
              $files = 'pages/'.$page.'.php';
          } else {
              $files = 'pages/home.php';
          }

          if (file_exists($files)) {
              include($files);
          } else {
              include_once("blank.php");
          }
    ?>
		
    </div>
	</section>	
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Indo Taichen Textile Industy
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?php echo date("Y");?> <a href="">DIT</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>	
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- DataTables  & Plugins -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="plugins/jszip/jszip.min.js"></script>
  <script src="plugins/pdfmake/pdfmake.min.js"></script>
  <script src="plugins/pdfmake/vfs_fonts.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <script src="plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>	
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="plugins/dropzone/min/dropzone.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>	
<script src="plugins/chart.js/chart371.js"></script>	
<script src="plugins/chart.js/chartjs-plugin-datalabels.js"></script>		
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(function () {
	//Initialize Select2 Elements
    $('.select2').select2()	
	//Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })	  
	  //Datepicker
	$('#datagridscroll5').DataTable({
	  "paging": false,
      "searching": true,
      "ordering": true,
      "info": true,
	  "scrollX": true,
      "scrollY": '350px',
	  "buttons": ["copy", "excel"],
	  "fixedColumns":   {
            left: 2,
		  	right:2,
     }	
    }).buttons().container().appendTo('#datagridscroll5_wrapper .col-md-6:eq(0)');  
    $("#example1").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    }); 
	$("#example3").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)'); 
	$('#example12').DataTable({
	  "paging": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,
	  "scrollX": true,
      "scrollY": '350px',
	  "buttons": ["copy", "excel", "pdf", "print", "colvis"]	
    }).buttons().container().appendTo('#example12_wrapper .col-md-6:eq(0)');
	$('#example10').DataTable({
	  "paging": false,
      "searching": false,
      "ordering": false,
      "info": true,
      "responsive": true,
	  "fixedHeader": true,	
    });  
	$("#example11").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "pageLength": 20,
      "buttons": ["copy", "csv", "excel", "pdf"]
    }).buttons().container().appendTo('#example11_wrapper .col-md-6:eq(0)');  
	$("#example13").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false, "pageLength": 20,
      "buttons": ["copy", "csv", "excel", "pdf"]
    }).buttons().container().appendTo('#example13_wrapper .col-md-6:eq(0)'); 
	$('#example14').DataTable({
	  "paging": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,
	  "scrollX": true,
      "scrollY": '150px',
	  "buttons": ["copy", "excel", "pdf", "print", "colvis"]	
    }).buttons().container().appendTo('#example14_wrapper .col-md-6:eq(0)');
	$('#example15').DataTable({
	  "paging": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "responsive": true,
	  "scrollX": true,
      "scrollY": '150px',
	  "buttons": ["copy", "excel", "pdf", "print", "colvis"]	
    }).buttons().container().appendTo('#example15_wrapper .col-md-6:eq(0)');  
  });
</script>
<script>
	$(function () {
		//Datepicker
    $('#datepicker').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker1').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker2').datetimepicker({
      format: 'YYYY-MM-DD'
    });
	//Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });
});		
</script>
<script>
$(document).on('click', '.show_detail', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailShow").html(ajaxData);
        $("#DetailShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
$(document).on('click', '.show_detaildel', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detaildel.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailDelShow").html(ajaxData);
        $("#DetailDelShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });  	
  $(document).on('click', '.show_detail_allokasi', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_alokasi.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailAllokasiShow").html(ajaxData);
        $("#DetailAllokasiShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
 $(document).on('click', '.show_detail_itn', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_itn.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailITNShow").html(ajaxData);
        $("#DetailITNShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
  $(document).on('click', '.show_detail_p501', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_p501.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailP501Show").html(ajaxData);
        $("#DetailP501Show").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
$(document).on('click', '.show_detail_m904', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_m904.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailM904Show").html(ajaxData);
        $("#DetailM904Show").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
$(document).on('click', '.show_detail_gdb', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_gdb.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailGDBShow").html(ajaxData);
        $("#DetailGDBShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
  $(document).on('click', '.show_detail_proj', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detail_proj.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailPRJShow").html(ajaxData);
        $("#DetailPRJShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
 $(document).on('click', '.detail_status', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/detail_status.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailStatus").html(ajaxData);
        $("#DetailStatus").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
 $(document).on('click', '.show_detailStkPBon', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detailStkPBon.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailShowStkPBon").html(ajaxData);
        $("#DetailShowStkPBon").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
 $(document).on('click', '.show_detailPakai', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detailPakai.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailPakaiShow").html(ajaxData);
        $("#DetailPakaiShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
  $(document).on('click', '.show_detailTurunan', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detailTurunan.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailTurunanShow").html(ajaxData);
        $("#DetailTurunanShow").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
  $(document).on('click', '.show_editstatus', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_editstatus.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#EditStatusUpload").html(ajaxData);
        $("#EditStatusUpload").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
  $(document).on('click', '.mesin_detail', function(e) {
        var m = $(this).attr("id");
        $.ajax({
          url: "pages/mesin_detail.php",
          type: "GET",
          data: {
            id: m,
          },
          success: function(ajaxData) {
            $("#DetailProduksi").html(ajaxData);
            $("#DetailProduksi").modal('show', {
              backdrop: 'true'
            });
          }
        });
      });
      $(document).on('click', '.log_detail', function(e) {
        var m = $(this).attr("id");
        $.ajax({
          url: "pages/mesin_log.php",
          type: "GET",
          data: {
            id: m,
          },
          success: function(ajaxData) {
            $("#MesinLog").html(ajaxData);
            $("#MesinLog").modal('show', {
              backdrop: 'true'
            });
          }
        });
      });	
	$(document).on('click', '.periksa_edit', function(e) {
        var m = $(this).attr("id");
        $.ajax({
          url: "pages/periksa_edit.php",
          type: "GET",
          data: {
            id: m,
          },
          success: function(ajaxData) {
            $("#PeriksaEdit").html(ajaxData);
            $("#PeriksaEdit").modal('show', {
              backdrop: 'true'
            });
          }
        });
      });
</script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>

<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'Digital Goods',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90]
        },
        {
          label               : 'Electronics',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    new Chart(areaChartCanvas, {
      type: 'line',
      data: areaChartData,
      options: areaChartOptions
    })

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = $.extend(true, {}, areaChartOptions)
    var lineChartData = $.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: lineChartData,
      options: lineChartOptions
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Chrome',
          'IE',
          'FireFox',
          'Safari',
          'Opera',
          'Navigator',
      ],
      datasets: [
        {
          data: [700,500,400,600,300,100],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = $.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
</script>	
</body>
</html>
