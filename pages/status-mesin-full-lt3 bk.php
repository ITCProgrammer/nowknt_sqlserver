<?php
session_start();
include"./../koneksi.php";
ini_set("error_reporting", 1);	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="refresh" content="180">
		<title>Status Mesin</title>
		<style>
			td{
		padding: 1px 0px;
	}
			p {
   				line-height: 4px;
				font-size: 8px;
			}
</style>
		<style>
			.blink_me {
  animation: blinker 1s linear infinite;
}
@keyframes blinker {
  50% { opacity: 0; }
}
	body{
		font-family: Calibri, "sans-serif", "Courier New";  /* "Calibri Light","serif" */
		font-style: normal;
	}
</style>

		<link rel="stylesheet" href="./../bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="./../bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="./../bower_components/Ionicons/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="./../dist/css/AdminLTE.min.css">
		<!-- toast CSS -->
		<link href="./../bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
		<!-- DataTables -->
		<link rel="stylesheet" href="./../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="./../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
		<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
		<link rel="stylesheet" href="./../dist/css/skins/skin-purple.min.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

		<!-- Google Font -->
		<!--
  <link rel="stylesheet"
        href="./../dist/css/font/font.css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	-->

		<link rel="icon" type="image/png" href="./../dist/img/index.ico">
		<style type="text/css">
			.teks-berjalan {
				background-color: #03165E;
				color: #F4F0F0;
				font-family: monospace;
				font-size: 24px;
				font-style: italic;
			}

			.border-dashed {
				border: 4px dashed #083255;
			}

			.bulat {
				border-radius: 50%;
				/*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);*/
			}

		</style>
	</head>

	<body>
		<div class="row">
			<div class="col-xs-12">
				<div class="box table-responsive">
					<div class="box-header with-border">
						<h3 class="box-title">Status Mesin Rajut Knitting ITTI Lantai 3 &amp; Lantai 2</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<a href="../index1.php?p=Status-Mesin" class="btn btn-xs" data-toggle="tooltip" data-html="true" data-placement="bottom" title="MiniScreen"><i class="fa  fa-compress"></i></a>
						</div>
					</div>
					<div class="box-body">

						<?php
function NoMesin($mc)
{
	include"./../koneksi.php";
    $qD01=mysqli_query($con,"SELECT
	b.id,
IF (
	c.`status` = 'BUKA'
	AND (b.`status` = 'MULAI' or b.`status` = '')
	AND (ISNULL(sum(e.berat)) or sum(e.berat) = 0),
	'3',
IF (
	c.`status` = 'BUKA'
	AND (b.`status` = 'MULAI' or b.`status` = 'SIAP JALAN')
	AND sum(e.berat) > 0,
	'2',

IF (
	b.`status` = 'BENANG HABIS',
	'4',

IF (
	b.`status` = 'TEST QUALITY',
	'1',
IF (
	b.`status` = 'SIAP JALAN',
	'1',
IF (
	b.`status` = 'KURANG CONES',
	'1',	
IF (
	c.`status` = 'ANTRI MESIN',
	'5',
IF (
	b.`status` = 'ANTRI',
	'5',	
	IF (
	c.`status` = 'TERIMA' and ISNULL(sum(e.berat)),
	'6',
	IF (
	c.`status` = 'HOLD',
	'7',
	'0'
)
)
)
)
)
)
)
)
)
)AS posisi,
  b.no_mesin,
	c.no_po,
	c.no_artikel,
	b.sts_order,
	c.tujuan,
	b.`status` as stsm,
	c.`status`	
	FROM tbl_instuksi_kerja_detail b
INNER JOIN tbl_instuksi_kerja c ON  c.id=b.id_inst
LEFT JOIN tbl_inspeksi d ON d.no_po=c.no_po
LEFT JOIN tbl_inspeksi_detail e ON e.id_inspeksi=d.id and e.no_mc=b.no_mesin
WHERE (c.`status`='BUKA' or c.`status`='TERIMA' or c.`status`='ANTRI MESIN' or c.`status`='HOLD') and b.`status`<>'SELESAI' AND b.no_mesin='$mc'
GROUP BY b.id
ORDER BY posisi,c.tgl_delivery ASC LIMIT 1");
    $rD01=mysqli_fetch_array($qD01);
    $D01sql5=mysqli_query($con,"SELECT c.SN,b.no_po,c.no_mc,a.tgl_update,now() as tgl FROM tbl_inspeksi b
INNER JOIN tbl_inspeksi_detail c ON b.id=c.id_inspeksi
INNER JOIN tbl_perbaikan a ON a.SN=c.SN
WHERE a.penyelesaian='' and b.no_po='".$rD01['no_po']."' and c.no_mc='".$rD01['no_mesin']."' ");
	$D01TPB=mysqli_query($con,"SELECT count(*) as jml FROM tbl_pergerakan_benang a 
INNER JOIN tbl_pergerakan_benang_detail b ON a.id=b.id_benang
WHERE a.no_mesin='".$rD01['no_mesin']."' AND a.pono='".$rD01['no_po']."' AND a.tujuan='MESIN' ");
	$D01rTPB=mysqli_fetch_array($D01TPB);
	
    $D01r5=mysqli_fetch_array($D01sql5);
    if ($D01r5['no_po']!="") {/*Perbaikan Mesin*/
        if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="btn-danger";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="btn-danger border-dashed";
            } else {
                $warnaD01="btn-danger blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="btn-danger bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay bulat") {
                $warnaD01="btn-danger border-dashed bulat";
            } else {
                $warnaD01="btn-danger blink_me bulat";
            }
        }
    } elseif ($rD01['posisi']=="1") {/*Test Quality*/
        if($rD01['stsm']=="TEST QUALITY"){
			
			if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-abu";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-abu border-dashed";
            } else {
                $warnaD01="bg-abu blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-abu bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-abu border-dashed bulat";
            } else {
                $warnaD01="bg-abu blink_me bulat";
            }
        }
			
		} else if($rD01['stsm']=="SIAP JALAN"){
			
			if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-black";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-black border-dashed";
            } else {
                $warnaD01="bg-black blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-black bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-black border-dashed bulat";
            } else {
                $warnaD01="bg-black blink_me bulat";
            }
        }
			
		}else if($rD01['stsm']=="KURANG CONES"){
			
			if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-navy";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-navy border-dashed";
            } else {
                $warnaD01="bg-navy blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-navy bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-navy border-dashed bulat";
            } else {
                $warnaD01="bg-navy blink_me bulat";
            }
        }
		}
    } elseif ($rD01['posisi']=="2") {/*Sedang Jalan</font>*/
        if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="btn-success";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="btn-success border-dashed";
            } else {
                $warnaD01="btn-success blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="btn-success bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="btn-success border-dashed bulat";
            } else {
                $warnaD01="btn-success blink_me bulat";
            }
        }
    } elseif ($rD01['posisi']=="3" and $D01rTPB['jml']<="0") {/*Tunggu Pasang Benang*/
        if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-purple";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-purple border-dashed";
            } else {
                $warnaD01="bg-purple blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-purple bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-purple border-dashed bulat";
            } else {
                $warnaD01="bg-purple blink_me bulat";
            }
        }
    } elseif ($rD01['posisi']=="3" and $D01rTPB['jml'] > "0") {/*Tunggu Setting*/
        if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="btn-primary";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="btn-primary border-dashed";
            } else {
                $warnaD01="btn-primary blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="btn-primary bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="btn-primary border-dashed bulat";
            } else {
                $warnaD01="btn-primary blink_me bulat";
            }
        }
    } elseif ($rD01['posisi']=="4") {/*Tunggu Benang Supplier*/
        if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-fuchsia";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-fuchsia border-dashed";
            } else {
                $warnaD01="bg-fuchsia blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-fuchsia bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-fuchsia border-dashed bulat";
            } else {
                $warnaD01="bg-fuchsia blink_me bulat";
            }
        }
    } elseif ($rD01['posisi']=="5") {/*Antri Mesin*/
        if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-kuning";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-kuning border-dashed";
            } else {
                $warnaD01="bg-kuning blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-kuning bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-kuning border-dashed bulat";
            } else {
                $warnaD01="bg-kuning blink_me bulat";
            }
        }
    } elseif ($rD01['posisi']=="6") {/*Tunggu Benang Warehouse*/
        if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="btn-warning";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="btn-warning border-dashed";
            } else {
                $warnaD01="btn-warning blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="btn-warning bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="btn-warning border-dashed bulat";
            } else {
                $warnaD01="btn-warning blink_me bulat";
            }
        }
    } elseif ($rD01['posisi']=="7") {/*Hold*/
        if ($rD01['tujuan']=="PRODUKSI") {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-teal";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-teal border-dashed";
            } else {
                $warnaD01="bg-teal blink_me";
            }
        } else {
            if ($rD01['sts_order']=="Normal") {
                $warnaD01="bg-teal bulat";
            } elseif ($rD01['sts_order']=="Potensi Delay") {
                $warnaD01="bg-teal border-dashed bulat";
            } else {
                $warnaD01="bg-teal blink_me bulat";
            }
        }
    } else {/*Tidak Ada PO*/ $warnaD01="btn-default";
    }
    return $warnaD01;
}
function Rajut($mc)
{
	include"./../koneksi.php";
    $qD01=mysqli_query($con,"SELECT
	b.id,
	IF (
	c.`status` = 'BUKA'
	AND (b.`status` = 'MULAI' or b.`status` = '')
	AND (ISNULL(sum(e.berat)) or sum(e.berat) = 0),
	'3',
IF (
	c.`status` = 'BUKA'
	AND b.`status` = 'MULAI'
	AND sum(e.berat) > 0,
	'2',

IF (
	b.`status` = 'BENANG HABIS',
	'4',

IF (
	b.`status` = 'TEST QUALITY',
	'1',
IF (
	b.`status` = 'SIAP JALAN',
	'1',
IF (
	b.`status` = 'KURANG CONES',
	'1',	
IF (
	c.`status` = 'ANTRI MESIN',
	'5',
IF (
	b.`status` = 'ANTRI',
	'5',
IF (
	c.`status` = 'TERIMA' and ISNULL(sum(e.berat)),
	'6',
	IF (
	c.`status` = 'HOLD',
	'7',
	'0'
)
)
)
)
)
)
)
)
)
) AS posisi,
  b.no_mesin,
	c.no_po,
	c.no_artikel FROM tbl_instuksi_kerja_detail b
INNER JOIN tbl_instuksi_kerja c ON  c.id=b.id_inst
LEFT JOIN tbl_inspeksi d ON d.no_po=c.no_po
LEFT JOIN tbl_inspeksi_detail e ON e.id_inspeksi=d.id and e.no_mc=b.no_mesin
WHERE (c.`status`='BUKA' or c.`status`='TERIMA' or c.`status`='ANTRI MESIN' or c.`status`='HOLD') and b.`status`<>'SELESAI' AND b.no_mesin='$mc'
GROUP BY b.id
ORDER BY posisi,c.tgl_delivery ASC LIMIT 1");
    $rD01=mysqli_fetch_array($qD01);
    $sql2=mysqli_query($con,"SELECT count(*) as jm_mc,a.qty_order from tbl_instuksi_kerja a
INNER JOIN tbl_instuksi_kerja_detail b ON a.id=b.id_inst
WHERE a.no_po='".$rD01['no_po']."' and b.`status`<>'SELESAI' ");
    $r2=mysqli_fetch_array($sql2);
    $sql3=mysqli_query($con,"SELECT sum(berat) as KG,sum(if(berat>0,1,0)) as Roll from tbl_inspeksi a
INNER JOIN tbl_inspeksi_detail b ON a.id=b.id_inspeksi WHERE a.no_po='".$rD01['no_po']."'");
    $r3=mysqli_fetch_array($sql3);
    $sql4=mysqli_query($con,"SELECT diameter_mesin,gauge_mesin,catatan FROM tbl_mesin WHERE no_mesin='$mc'");
    $r4=mysqli_fetch_array($sql4);
    $totkr=number_format($r2['qty_order']-$r3['KG']);
    echo "<h3><u>".$mc."</u></h3>Ukuran: ".$r4['diameter_mesin']."''x".$r4['gauge_mesin']."G"."<br>No PO: ".$rD01['no_po']."<br> No Art: ".$rD01['no_artikel']."<br> Kurang Rajut: ".$totkr." Kg<br>".$r4['catatan'];
    // echo 	"<font size=-2>".$totkr." Kg</font>";
}
function Kurang($mc)
{
	include"./../koneksi.php";
    $qD01=mysqli_query($con,"SELECT
	b.id,
	IF (
	c.`status` = 'BUKA'
	AND (b.`status` = 'MULAI' or b.`status` = '')
	AND (ISNULL(sum(e.berat)) or sum(e.berat) = 0),
	'3',
IF (
	c.`status` = 'BUKA'
	AND b.`status` = 'MULAI'
	AND sum(e.berat) > 0,
	'2',

IF (
	b.`status` = 'BENANG HABIS',
	'4',

IF (
	b.`status` = 'TEST QUALITY',
	'1',
IF (
	b.`status` = 'SIAP JALAN',
	'1',
IF (
	b.`status` = 'KURANG CONES',
	'1',	
IF (
	c.`status` = 'ANTRI MESIN',
	'5',
IF (
	b.`status` = 'ANTRI',
	'5',	
	IF (
	c.`status` = 'TERIMA' and ISNULL(sum(e.berat)),
	'6',
	IF (
	c.`status` = 'HOLD',
	'7',
	'0'
)
)
)
)
)
)
)
)
)
)AS posisi,
  b.no_mesin,
	c.no_po,
	c.no_artikel FROM tbl_instuksi_kerja_detail b
INNER JOIN tbl_instuksi_kerja c ON  c.id=b.id_inst
LEFT JOIN tbl_inspeksi d ON d.no_po=c.no_po
LEFT JOIN tbl_inspeksi_detail e ON e.id_inspeksi=d.id and e.no_mc=b.no_mesin
WHERE (c.`status`='BUKA' or c.`status`='TERIMA' or c.`status`='ANTRI MESIN' or c.`status`='HOLD') and b.`status`<>'SELESAI' AND b.no_mesin='$mc'
GROUP BY b.id
ORDER BY posisi,c.tgl_delivery ASC LIMIT 1");
    $rD01=mysqli_fetch_array($qD01);
    $sql2=mysqli_query($con,"SELECT count(*) as jm_mc,a.qty_order from tbl_instuksi_kerja a
INNER JOIN tbl_instuksi_kerja_detail b ON a.id=b.id_inst
WHERE a.no_po='".$rD01['no_po']."' and b.`status`<>'SELESAI' ");
    $r2=mysqli_fetch_array($sql2);
    $sql3=mysqli_query($con,"SELECT sum(berat) as KG,sum(if(berat>0,1,0)) as Roll from tbl_inspeksi a
INNER JOIN tbl_inspeksi_detail b ON a.id=b.id_inspeksi WHERE a.no_po='".$rD01['no_po']."'");
    $r3=mysqli_fetch_array($sql3);
    $totkr=number_format($r2['qty_order']-$r3['KG']);
    echo $totkr;
    // echo 	"<font size=-2>".$totkr." Kg</font>";
}
function waktu($mc){
include"./../koneksi.php";	
$qry=mysqli_query($con,"SELECT
	b.id,
IF (
	c.`status` = 'BUKA'
	AND (b.`status` = 'MULAI' or b.`status` = '')
	AND (ISNULL(sum(e.berat)) or sum(e.berat) = 0),
	'3',
IF (
	c.`status` = 'BUKA'
	AND b.`status` = 'MULAI'
	AND sum(e.berat) > 0,
	'2',

IF (
	b.`status` = 'BENANG HABIS',
	'4',

IF (
	b.`status` = 'TEST QUALITY',
	'1',
IF (
	b.`status` = 'SIAP JALAN',
	'1',
IF (
	b.`status` = 'KURANG CONES',
	'1',	
IF (
	c.`status` = 'ANTRI MESIN',
	'5',
IF (
	b.`status` = 'ANTRI',
	'5',	
	IF (
	c.`status` = 'TERIMA' and ISNULL(sum(e.berat)),
	'6',
	IF (
	c.`status` = 'HOLD',
	'7',
	'0'
)
)
)
)
)
)
)
)
)
) AS posisi,
  b.no_mesin,
	b.tgl_mulai_mesin,
	b.tgl_selesai_mesin,
	b.tgl_test_quality,
	c.no_po,
	c.no_artikel,
	c.tgl_delivery,
	c.tgl_terima,
	c.tgl_antri,
	now() as tgl,
	DATE_FORMAT(now(),'%Y-%m-%d') as tgl_skrng,
	b.sts_order,
	c.tujuan,
	b.`status` as sts,
	c.`status`
	FROM tbl_instuksi_kerja_detail b
INNER JOIN tbl_instuksi_kerja c ON  c.id=b.id_inst
LEFT JOIN tbl_inspeksi d ON d.no_po=c.no_po
LEFT JOIN tbl_inspeksi_detail e ON e.id_inspeksi=d.id and e.no_mc=b.no_mesin
WHERE (c.`status`='BUKA' or c.`status`='TERIMA' or c.`status`='ANTRI MESIN' or c.`status`='HOLD') and b.`status`<>'SELESAI' AND b.no_mesin='$mc'
GROUP BY b.id
ORDER BY posisi,c.tgl_delivery ASC LIMIT 1");
$rowd=mysqli_fetch_array($qry);
$sql1=mysqli_query($con,"SELECT sum(berat) as KG,sum(if(berat>0,1,0)) as Roll from tbl_inspeksi a
INNER JOIN tbl_inspeksi_detail b ON a.id=b.id_inspeksi WHERE a.no_po='".$rowd['no_po']."' AND b.no_mc='$mc'");
$r1=mysqli_fetch_array($sql1);	
$sql5=mysqli_query($con,"SELECT c.SN,b.no_po,c.no_mc,a.tgl_update,now() as tgl FROM tbl_inspeksi b 
INNER JOIN tbl_inspeksi_detail c ON b.id=c.id_inspeksi
INNER JOIN tbl_perbaikan a ON a.SN=c.SN
WHERE a.penyelesaian='' and b.no_po='".$rowd['no_po']."' and c.no_mc='$mc' ");		
$r5=mysqli_fetch_array($sql5);
	
$awalP  = strtotime($r5['tgl_update']);
$akhirP = strtotime($r5['tgl']);
$diffP  = ($akhirP - $awalP);
$tjamP  =round($diffP / (60 * 60),2);
$hariP  =round($tjamP/24,1);
		
$awalA  = strtotime($rowd['tgl_terima']);
$akhirA = strtotime($rowd['tgl']);
$diffA  = ($akhirA - $awalA);
$tjamA  =round($diffA / (60 * 60),2);
$hariA  =round($tjamA/24,2);

$awalT  = strtotime($rowd['tgl_mulai_mesin']);
$akhirT = strtotime($rowd['tgl']);
$diffT  = ($akhirT - $awalT);
$tjamT  =round($diffT / (60 * 60),2);
$hariT  =round($tjamT/24);
		
$awalTQ  = strtotime($rowd['tgl_test_quality']);
$akhirTQ = strtotime($rowd['tgl']);
$diffTQ  = ($akhirTQ - $awalTQ);
$tjamTQ  =round($diffTQ/(60 * 60),2);
$hariTQ  =round($tjamTQ/24,2);		

$awalAM  = strtotime($rowd['tgl_antri']);
$akhirAM = strtotime($rowd['tgl']);
$diffAM  = ($akhirAM - $awalAM);
$tjamAM  =round($diffAM/(60 * 60),2);
$hariAM  =round($tjamAM/24,2);
	
$awalDY  = strtotime($rowd['tgl_skrng']);
$akhirDY = strtotime($rowd['tgl_delivery']);
$diffDY  = ($akhirDY - $awalDY);
$tjamDY  =round($diffDY/(60 * 60),2);
$hariDY  =round($tjamDY/24,2);

	/*if($r5[no_po]!=""){echo "<font color=white>$hariP</font>";}else 
	if($rowd[status]=="TERIMA" and $r1[KG]==""){echo "<font color=white>$hariA</font>";}else 
	if($rowd[status]=="BUKA" and $r1[KG]>0 and $rowd['sts']!="TEST QUALITY" and $rowd['sts']!="BENANG HABIS"){echo "<font color=white></font>";}else 
	if($rowd[status]=="BUKA" and ($r1[KG]=="" or $r1[KG]==0) and $rowd['sts']!="TEST QUALITY" and $rowd['sts']!="BENANG HABIS"){echo "<font color=white>$hariT</font>";}else if($rowd[status]=="BUKA" and $rowd['sts']=="TEST QUALITY"){echo "<font color=white>$hariTQ</font>";}else if($rowd[status]=="BUKA" and $rowd['sts']=="BENANG HABIS"){echo "<font color=black>$hariT</font>";}else if($rowd[status]=="ANTRI MESIN"){echo "<font color=black>$hariAM</font>";}else{echo"";}*/
	if($r5['no_po']!=""){echo "<font color=black>$hariP Hari</font>";}else
	if($rowd['status']=="BUKA" and ($r1['KG']=="" or $r1['KG']==0) and $rowd['sts']!="TEST QUALITY" and $rowd['sts']!="BENANG HABIS"){echo "<font color=black>$hariT Hari</font>";}
}						
?>
						<?php
/* LT 1 */
        $news=mysqli_query($con,"SELECT * FROM tbl_news_line WHERE gedung='LT 1' LIMIT 1");
        $rNews=mysqli_fetch_array($news);
/* Total Status Mesin */
    $sqlStatus=mysqli_query($con,"SELECT no_mesin FROM tbl_mesin");
    $ST="0"; $TBW="0";$AM="0";$TBS="0";$HLD="0";$TQ="0";$PM="0";$SJ="0";$TAP="0";$PTD="0";$URG="0"; $RS="0"; $TPB="0";
    while ($rM=mysqli_fetch_array($sqlStatus)) {
        $sts=NoMesin($rM['no_mesin']);
        if ($sts=="btn-primary" or
           $sts=="btn-primary bulat" or
           $sts=="btn-primary border-dashed" or
           $sts=="btn-primary border-dashed bulat" or
           $sts=="btn-primary blink_me" or
           $sts=="btn-primary blink_me bulat") {
            $TS="1";
        } else {
            $TS="0";
        }
		if ($sts=="bg-purple" or
           $sts=="bg-purple bulat" or
           $sts=="bg-purple border-dashed" or
           $sts=="bg-purple border-dashed bulat" or
           $sts=="bg-purple blink_me" or
           $sts=="bg-purple blink_me bulat") {
            $TPB="1";
        } else {
            $TPB="0";
        }
        if ($sts=="btn-warning" or
           $sts=="btn-warning bulat" or
           $sts=="btn-warning border-dashed" or
           $sts=="btn-warning border-dashed bulat" or
           $sts=="btn-warning blink_me" or
           $sts=="btn-warning blink_me bulat") {
            $TBW="1";
        } else {
            $TBW="0";
        }
        if ($sts=="btn-danger" or
           $sts=="btn-danger bulat" or
           $sts=="btn-danger border-dashed" or
           $sts=="btn-danger border-dashed bulat" or
           $sts=="btn-danger blink_me" or
           $sts=="btn-danger blink_me bulat") {
            $PM="1";
        } else {
            $PM="0";
        }
        if ($sts=="btn-success" or
           $sts=="btn-success bulat" or
           $sts=="btn-success border-dashed" or
           $sts=="btn-success border-dashed bulat" or
           $sts=="btn-success blink_me" or
           $sts=="btn-success blink_me bulat") {
            $SJ="1";
        } else {
            $SJ="0";
        }
        if ($sts=="btn-default") {
            $TAP="1";
        } else {
            $TAP="0";
        }
        if ($sts=="bg-kuning" or
           $sts=="bg-kuning bulat" or
           $sts=="bg-kuning border-dashed" or
           $sts=="bg-kuning border-dashed bulat" or
           $sts=="bg-kuning blink_me" or
           $sts=="bg-kuning blink_me bulat") {
            $AM="1";
        } else {
            $AM="0";
        }
        if ($sts=="bg-fuchsia" or
           $sts=="bg-fuchsia bulat" or
           $sts=="bg-fuchsia border-dashed" or
           $sts=="bg-fuchsia border-dashed bulat" or
           $sts=="bg-fuchsia blink_me" or
           $sts=="bg-fuchsia blink_me bulat") {
            $TBS="1";
        } else {
            $TBS="0";
        }
		if ($sts=="bg-teal" or
           $sts=="bg-teal bulat" or
           $sts=="bg-teal border-dashed" or
           $sts=="bg-teal border-dashed bulat" or
           $sts=="bg-teal blink_me" or
           $sts=="bg-teal blink_me bulat") {
            $HLD="1";
        } else {
            $HLD="0";
        }
        if ($sts=="bg-abu" or
           $sts=="bg-abu bulat" or
           $sts=="bg-abu border-dashed" or
           $sts=="bg-abu border-dashed bulat" or
           $sts=="bg-abu blink_me" or
           $sts=="bg-abu blink_me bulat") {
            $TQ="1";
        } else {
            $TQ="0";
        }
		if ($sts=="bg-black" or
           $sts=="bg-black bulat" or
           $sts=="bg-black border-dashed" or
           $sts=="bg-black border-dashed bulat" or
           $sts=="bg-black blink_me" or
           $sts=="bg-black blink_me bulat") {
            $KOp="1";
        } else {
            $KOp="0";
        }
		if ($sts=="bg-navy" or
           $sts=="bg-navy bulat" or
           $sts=="bg-navy border-dashed" or
           $sts=="bg-navy border-dashed bulat" or
           $sts=="bg-navy blink_me" or
           $sts=="bg-navy blink_me bulat") {
            $KCo="1";
        } else {
            $KCo="0";
        }
        if ($sts=="bg-abu border-dashed" or
           $sts=="bg-abu border-dashed bulat" or
           $sts=="bg-fuchisa border-dashed" or
           $sts=="bg-fuchisa border-dashed bulat" or
		   $sts=="bg-black border-dashed" or
           $sts=="bg-black border-dashed bulat" or	
           $sts=="bg-kuning border-dashed" or
           $sts=="bg-kuning border-dashed bulat" or
           $sts=="btn-success border-dashed" or
           $sts=="btn-success border-dashed bulat" or
           $sts=="btn-danger border-dashed" or
           $sts=="btn-danger border-dashed bulat" or
           $sts=="btn-warning border-dashed" or
           $sts=="btn-warning border-dashed bulat" or
           $sts=="btn-primary border-dashed" or
           $sts=="btn-primary border-dashed bulat" or
		   $sts=="bg-purple border-dashed" or
           $sts=="bg-purple border-dashed bulat") {
            $PTD="1";
        } else {
            $PTD="0";
        }
        if ($sts=="bg-abu blink_me" or
           $sts=="bg-abu blink_me bulat" or
		   $sts=="bg-navy blink_me" or
           $sts=="bg-navy blink_me bulat" or
		   $sts=="bg-teal blink_me" or
           $sts=="bg-teal blink_me bulat" or	
           $sts=="bg-fuchsia blink_me" or
           $sts=="bg-fuchsia blink_me bulat" or
		   $sts=="bg-black blink_me" or
           $sts=="bg-black blink_me bulat" or	
           $sts=="bg-kuning blink_me" or
           $sts=="bg-kuning blink_me bulat" or
           $sts=="btn-success blink_me" or
           $sts=="btn-success blink_me bulat" or
           $sts=="btn-danger blink_me" or
           $sts=="btn-danger blink_me bulat" or
           $sts=="btn-warning blink_me" or
           $sts=="btn-warning blink_me bulat" or
           $sts=="btn-primary blink_me" or
           $sts=="btn-primary blink_me bulat" or
		   $sts=="bg-purple blink_me" or
           $sts=="bg-purple blink_me bulat") {
            $URG="1";
        } else {
            $URG="0";
        }
        if ($sts=="bg-abu bulat" or
           $sts=="bg-abu border-dashed bulat" or
           $sts=="bg-abu blink_me bulat" or
		   $sts=="bg-navy bulat" or
           $sts=="bg-navy border-dashed bulat" or
           $sts=="bg-navy blink_me bulat" or
		   $sts=="bg-teal bulat" or
           $sts=="bg-teal border-dashed bulat" or
           $sts=="bg-teal blink_me bulat" or	
		   $sts=="bg-fuchsia bulat" or
           $sts=="bg-fuchsia border-dashed bulat" or
           $sts=="bg-fuchsia blink_me bulat" or	
           $sts=="bg-black bulat" or
           $sts=="bg-black border-dashed bulat" or
           $sts=="bg-black blink_me bulat" or
           $sts=="bg-kuning bulat" or
           $sts=="bg-kuning border-dashed bulat" or
           $sts=="bg-kuning blink_me bulat" or
           $sts=="btn-success bulat" or
           $sts=="btn-success border-dashed bulat" or
           $sts=="btn-success blink_me bulat" or
           $sts=="btn-danger bulat" or
           $sts=="btn-danger border-dashed bulat" or
           $sts=="btn-danger blink_me bulat" or
           $sts=="btn-warning bulat" or
           $sts=="btn-warning border-dashed bulat" or
           $sts=="btn-warning blink_me bulat" or
           $sts=="btn-primary bulat" or
           $sts=="btn-primary border-dashed bulat" or
           $sts=="btn-primary blink_me bulat"  or
		   $sts=="bg-purple bulat" or
           $sts=="bg-purple border-dashed bulat" or
           $sts=="bg-purple blink_me bulat") {
            $RS="1";
        } else {
            $RS="0";
        }
        $totTS=$totTS+$TS;
		$totTPB=$totTPB+$TPB;
        $totTBW=$totTBW+$TBW;
        $totAM=$totAM+$AM;
        $totTBS=$totTBS+$TBS;
		$totHLD=$totHLD+$HLD;
        $totTQ=$totTQ+$TQ;
        $totPM=$totPM+$PM;
        $totSJ=$totSJ+$SJ;
        $totTAP=$totTAP+$TAP;
        $totPTD=$totPTD+$PTD;
        $totURG=$totURG+$URG;
        $totRS=$totRS+$RS;
		$totKOp=$totKOp+$KOp;
		$totKCo=$totKCo+$KCo;
    }
    $totMesin=$totTS+$totTPB+$totTBW+$totAM+$totTBS+$totHLD+$totKCo+$totKOp+$totTQ+$totPM+$totSJ+$totTAP;
        ?>
						<table width="100%" border="0">
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H36"); ?>" id="H36" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H36"); ?>">H36<br />
							    <p> <?php echo Kurang("H36"); ?><br />
							      <br />
							      <?php waktu("H36"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H37"); ?>" id="H37" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H37"); ?>">H37<br />
							    <p> <?php echo Kurang("H37"); ?><br />
							      <br />
							      <?php waktu("H37"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H38"); ?>" id="H38" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H38"); ?>">H38<br />
							    <p> <?php echo Kurang("H38"); ?><br />
							      <br />
							      <?php waktu("H38"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H39"); ?>" id="H39" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H39"); ?>">H39<br />
							    <p> <?php echo Kurang("H39"); ?><br />
							      <br />
							      <?php waktu("H39"); ?>
						      </p>
							    </span></a></td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="5%" align="center" valign="middle">&nbsp;</td>
							  <td width="7%" align="center" valign="middle">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="3%">&nbsp;</td>
								<td width="0%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="1%">&nbsp;</td>
								<td width="7%">&nbsp;</td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E101"); ?>" id="E101" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E101"); ?>">E101<br />
								  <p> <?php echo Kurang("E101"); ?><br />
								    <br />
								    <?php waktu("E101"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E100"); ?>" id="E100" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E100"); ?>">E100<br />
								  <p> <?php echo Kurang("E100"); ?><br />
								    <br />
								    <?php waktu("E100"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E99"); ?>" id="E99" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E99"); ?>">E99<br />
								  <p> <?php echo Kurang("E99"); ?><br />
								    <br />
								    <?php waktu("E99"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E98"); ?>" id="E98" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E98"); ?>">E98<br />
								  <p> <?php echo Kurang("E98"); ?><br />
								    <br />
								    <?php waktu("E98"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E97"); ?>" id="E97" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E97"); ?>">E97<br />
								  <p> <?php echo Kurang("E97"); ?><br />
								    <br />
								    <?php waktu("E97"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E96"); ?>" id="E96" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E96"); ?>">E96<br />
								  <p> <?php echo Kurang("E96"); ?><br />
								    <br />
								    <?php waktu("E96"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H28"); ?>" id="H28" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H28"); ?>">H28<br />
							    <p> <?php echo Kurang("H28"); ?><br />
							      <br />
							      <?php waktu("H28"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H29"); ?>" id="H29" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H29"); ?>">H29<br />
							    <p> <?php echo Kurang("H29"); ?><br />
							      <br />
							      <?php waktu("H29"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H30"); ?>" id="H30" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H30"); ?>">H30<br />
							    <p> <?php echo Kurang("H30"); ?><br />
							      <br />
							      <?php waktu("H30"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H31"); ?>" id="H31" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H31"); ?>">H31<br />
							    <p> <?php echo Kurang("H31"); ?><br />
							      <br />
							      <?php waktu("H31"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H32"); ?>" id="H32" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H32"); ?>">H32<br />
							    <p> <?php echo Kurang("H32"); ?><br />
							      <br />
							      <?php waktu("H32"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H33"); ?>" id="H33" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H33"); ?>">H33<br />
							    <p> <?php echo Kurang("H33"); ?><br />
							      <br />
							      <?php waktu("H33"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H34"); ?>" id="H34" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H34"); ?>">H34<br />
							    <p> <?php echo Kurang("H34"); ?><br />
							      <br />
							      <?php waktu("H34"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H35"); ?>" id="H35" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H35"); ?>">H35<br />
							    <p> <?php echo Kurang("H35"); ?><br />
							      <br />
							      <?php waktu("H35"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E95"); ?>" id="E95" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E95"); ?>">E95<br />
								  <p> <?php echo Kurang("E95"); ?><br />
								    <br />
								    <?php waktu("E95"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E94"); ?>" id="E94" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E94"); ?>">E94<br />
								  <p> <?php echo Kurang("E94"); ?><br />
								    <br />
								    <?php waktu("E94"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E93"); ?>" id="E93" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E93"); ?>">E93<br />
								  <p> <?php echo Kurang("E93"); ?><br />
								    <br />
								    <?php waktu("E93"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E92"); ?>" id="E92" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E92"); ?>">E92<br />
								  <p> <?php echo Kurang("E92"); ?><br />
								    <br />
								    <?php waktu("E92"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E91"); ?>" id="E91" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E91"); ?>">E91<br />
								  <p> <?php echo Kurang("E91"); ?><br />
								    <br />
								    <?php waktu("E91"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E90"); ?>" id="E90" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E90"); ?>">E90<br />
								  <p> <?php echo Kurang("E90"); ?><br />
								    <br />
								    <?php waktu("E90"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E89"); ?>" id="E89" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E89"); ?>">E89<br />
								  <p> <?php echo Kurang("E89"); ?><br />
								    <br />
								    <?php waktu("E89"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H20"); ?>" id="H20" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H20"); ?>">H20<br />
							    <p> <?php echo Kurang("H20"); ?><br />
							      <br />
							      <?php waktu("H20"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H21"); ?>" id="H21" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H21"); ?>">H21<br />
							    <p> <?php echo Kurang("H21"); ?><br />
							      <br />
							      <?php waktu("H21"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H22"); ?>" id="H22" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H22"); ?>">H22<br />
							    <p> <?php echo Kurang("H22"); ?><br />
							      <br />
							      <?php waktu("H22"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H23"); ?>" id="H23" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H23"); ?>">H23<br />
							    <p> <?php echo Kurang("H23"); ?><br />
							      <br />
							      <?php waktu("H23"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H24"); ?>" id="H24" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H24"); ?>">H24<br />
							    <p> <?php echo Kurang("H24"); ?><br />
							      <br />
							      <?php waktu("H24"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H25"); ?>" id="H25" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H25"); ?>">H25<br />
							    <p> <?php echo Kurang("H25"); ?><br />
							      <br />
							      <?php waktu("H25"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H26"); ?>" id="H26" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H26"); ?>">H26<br />
							    <p> <?php echo Kurang("H26"); ?><br />
							      <br />
							      <?php waktu("H26"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H27"); ?>" id="H27" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H27"); ?>">H27<br />
							    <p> <?php echo Kurang("H27"); ?><br />
							      <br />
							      <?php waktu("H27"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E88"); ?>" id="E88" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E88"); ?>">E88<br />
								  <p> <?php echo Kurang("E88"); ?><br />
								    <br />
								    <?php waktu("E88"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E87"); ?>" id="E87" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E87"); ?>">E87<br />
								  <p> <?php echo Kurang("E87"); ?><br />
								    <br />
								    <?php waktu("E87"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E86"); ?>" id="E86" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E86"); ?>">E86<br />
								  <p> <?php echo Kurang("E86"); ?><br />
								    <br />
								    <?php waktu("E86"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E85"); ?>" id="E85" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E85"); ?>">E85<br />
								  <p> <?php echo Kurang("E85"); ?><br />
								    <br />
								    <?php waktu("E85"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E84"); ?>" id="E84" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E84"); ?>">E84<br />
								  <p> <?php echo Kurang("E84"); ?><br />
								    <br />
								    <?php waktu("E84"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E83"); ?>" id="E83" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E83"); ?>">E83<br />
								  <p> <?php echo Kurang("E83"); ?><br />
								    <br />
								    <?php waktu("E83"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E82"); ?>" id="E82" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E82"); ?>">E82<br />
								  <p> <?php echo Kurang("E82"); ?><br />
								    <br />
								    <?php waktu("E82"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H12"); ?>" id="H12" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H12"); ?>">H12<br />
							    <p> <?php echo Kurang("H12"); ?><br />
							      <br />
							      <?php waktu("H12"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H13"); ?>" id="H13" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H13"); ?>">H13<br />
							    <p> <?php echo Kurang("H13"); ?><br />
							      <br />
							      <?php waktu("H13"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H14"); ?>" id="H14" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H14"); ?>">H14<br />
							    <p> <?php echo Kurang("H14"); ?><br />
							      <br />
							      <?php waktu("H14"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H15"); ?>" id="H15" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H15"); ?>">H15<br />
							    <p> <?php echo Kurang("H15"); ?><br />
							      <br />
							      <?php waktu("H15"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H16"); ?>" id="H16" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H16"); ?>">H16<br />
							    <p> <?php echo Kurang("H16"); ?><br />
							      <br />
							      <?php waktu("H16"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H17"); ?>" id="H17" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H17"); ?>">H17<br />
							    <p> <?php echo Kurang("H17"); ?><br />
							      <br />
							      <?php waktu("H17"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H18"); ?>" id="H18" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H18"); ?>">H18<br />
							    <p> <?php echo Kurang("H18"); ?><br />
							      <br />
							      <?php waktu("H18"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H19"); ?>" id="H19" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H19"); ?>">H19<br />
							    <p> <?php echo Kurang("H19"); ?><br />
							      <br />
							      <?php waktu("H19"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E81"); ?>" id="E81" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E81"); ?>">E81<br />
								  <p> <?php echo Kurang("E81"); ?><br />
								    <br />
								    <?php waktu("E81"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E80"); ?>" id="E80" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E80"); ?>">E80<br />
								  <p> <?php echo Kurang("E80"); ?><br />
								    <br />
								    <?php waktu("E80"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E79"); ?>" id="E79" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E79"); ?>">E79<br />
								  <p> <?php echo Kurang("E79"); ?><br />
								    <br />
								    <?php waktu("E79"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E78"); ?>" id="E78" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E78"); ?>">E78<br />
								  <p> <?php echo Kurang("E78"); ?><br />
								    <br />
								    <?php waktu("E78"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E77"); ?>" id="E77" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E77"); ?>">E77<br />
								  <p> <?php echo Kurang("E77"); ?><br />
								    <br />
								    <?php waktu("E77"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E76"); ?>" id="E76" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E76"); ?>">E76<br />
								  <p> <?php echo Kurang("E76"); ?><br />
								    <br />
								    <?php waktu("E76"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E75"); ?>" id="E75" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E75"); ?>">E75<br />
								  <p> <?php echo Kurang("E75"); ?><br />
								    <br />
								    <?php waktu("E75"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H04"); ?>" id="H04" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H04"); ?>">H04<br />
							    <p> <?php echo Kurang("H04"); ?><br />
							      <br />
							      <?php waktu("H04"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H05"); ?>" id="H05" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H05"); ?>">H05<br />
							    <p> <?php echo Kurang("H05"); ?><br />
							      <br />
							      <?php waktu("H05"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H06"); ?>" id="H06" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H06"); ?>">H06<br />
							    <p> <?php echo Kurang("H06"); ?><br />
							      <br />
							      <?php waktu("H06"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H07"); ?>" id="H07" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H07"); ?>">H07<br />
							    <p> <?php echo Kurang("H07"); ?><br />
							      <br />
							      <?php waktu("H07"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H08"); ?>" id="H08" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H08"); ?>">H08<br />
							    <p> <?php echo Kurang("H08"); ?><br />
							      <br />
							      <?php waktu("H08"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H09"); ?>" id="H09" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H09"); ?>">H09<br />
							    <p> <?php echo Kurang("H09"); ?><br />
							      <br />
							      <?php waktu("H09"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E74"); ?>" id="E74" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E74"); ?>">E74<br />
								  <p> <?php echo Kurang("E74"); ?><br />
								    <br />
								    <?php waktu("E74"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E73"); ?>" id="E73" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E73"); ?>">E73<br />
								  <p> <?php echo Kurang("E73"); ?><br />
								    <br />
								    <?php waktu("E73"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E72"); ?>" id="E72" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E72"); ?>">E72<br />
								  <p> <?php echo Kurang("E72"); ?><br />
								    <br />
								    <?php waktu("E72"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E71"); ?>" id="E71" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E71"); ?>">E71<br />
								  <p> <?php echo Kurang("E71"); ?><br />
								    <br />
								    <?php waktu("E71"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E70"); ?>" id="E70" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E70"); ?>">E70<br />
								  <p> <?php echo Kurang("E70"); ?><br />
								    <br />
								    <?php waktu("E70"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E69"); ?>" id="E69" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E69"); ?>">E69<br />
								  <p> <?php echo Kurang("E69"); ?><br />
								    <br />
								    <?php waktu("E69"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E68"); ?>" id="E68" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E68"); ?>">E68<br />
								  <p> <?php echo Kurang("E68"); ?><br />
								    <br />
								    <?php waktu("E68"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("M15"); ?>" id="M15" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("M15"); ?>">M15<br />
							    <p> <?php echo Kurang("M15"); ?><br />
							      <br />
							      <?php waktu("M15"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("M16"); ?>" id="M16" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("M16"); ?>">M16<br />
							    <p> <?php echo Kurang("M16"); ?><br />
							      <br />
							      <?php waktu("M16"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("M55"); ?>" id="M55" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("M55"); ?>">M55<br />
							    <p> <?php echo Kurang("M55"); ?><br />
							      <br />
							      <?php waktu("M55"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("M56"); ?>" id="M56" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("M56"); ?>">M56<br />
							    <p> <?php echo Kurang("M56"); ?><br />
							      <br />
							      <?php waktu("M56"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H01"); ?>" id="H01" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H01"); ?>">H01<br />
							    <p> <?php echo Kurang("H01"); ?><br />
							      <br />
							      <?php waktu("H01"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H02"); ?>" id="H02" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H02"); ?>">H02<br />
							    <p> <?php echo Kurang("H02"); ?><br />
							      <br />
							      <?php waktu("H02"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("H03"); ?>" id="H03" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("H03"); ?>">H03<br />
							    <p> <?php echo Kurang("H03"); ?><br />
							      <br />
							      <?php waktu("H03"); ?>
						      </p>
							    </span></a></td>
							  <td align="center" valign="middle">&nbsp;</td>
							  <td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="3" align="right">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td align="center" valign="middle">&nbsp;</td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E67"); ?>" id="E67" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E67"); ?>">E67<br />
								  <p> <?php echo Kurang("E67"); ?><br />
								    <br />
								    <?php waktu("E67"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E66"); ?>" id="E66" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E66"); ?>">E66<br />
								  <p> <?php echo Kurang("E66"); ?><br />
								    <br />
								    <?php waktu("E66"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E65"); ?>" id="E65" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E65"); ?>">E65<br />
								  <p> <?php echo Kurang("E65"); ?><br />
								    <br />
								    <?php waktu("E65"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E64"); ?>" id="E64" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E64"); ?>">E64<br />
								  <p> <?php echo Kurang("E64"); ?><br />
								    <br />
								    <?php waktu("E64"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E63"); ?>" id="E63" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E63"); ?>">E63<br />
								  <p> <?php echo Kurang("E63"); ?><br />
								    <br />
								    <?php waktu("E63"); ?>
							    </p>
								  </span></a></td>
								<td align="center" valign="middle"><a><span class="detail_status btn <?php echo NoMesin("E62"); ?>" id="E62" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut("E62"); ?>">E62<br />
								  <p> <?php echo Kurang("E62"); ?><br />
								    <br />
								    <?php waktu("E62"); ?>
							    </p>
								  </span></a></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="2" align="right">Total Mesin <span class="label label-danger">
								<?php echo $totMesin; ?></span></td>
								<td colspan="4" align="right">Tg. Benang Warehouse <span class="label label-warning">
										<?php echo $totTBW;?></span></td>
								<td colspan="4" align="right">Tg. Setting <span class="label label-primary">
								<?php echo $totTS;?></span></td>
								<td>&nbsp;</td>
								<td colspan="6" align="right">Sedang Jalan <span class="label label-success">
										<?php echo $totSJ;?></span></td>
								<td align="right">&nbsp;</td>
								<td colspan="3" align="right">Tes Quality <span class="label bg-abu">
										<?php echo $totTQ;?></span></td>
								<td align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="2" align="right">Urgent <span class="label bg-abu blink_me"> <?php echo $totURG;?></span></td>
								<td colspan="4" align="right">Antri Mesin <span class="label bg-kuning">
										<?php echo $totAM;?></span></td>
								<td colspan="4" align="right">Tidak Ada PO <span class="label label-default">
								<?php echo $totTAP;?></span></td>
								<td>&nbsp;</td>
								<td colspan="6" align="right">Perbaikan Mesin <span class="label label-danger">
										<?php echo $totPM;?></span></td>
								<td align="right">&nbsp;</td>
								<td colspan="3" align="right">Tg. Benang Supp. <span class="label bg-fuchsia">
										<?php echo $totTBS;?></span></td>
								<td align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2" align="right">Hold <span class="label bg-teal">
								<?php echo $totHLD;?></span></td>
								<td colspan="3" align="right">Siap Jalan <span class="label bg-black">
								<?php echo $totKOp;?></span></td>
								<td colspan="4" align="right">Potensi Delay <span class="label btn-default border-dashed">
										<?php echo $totPTD;?></span></td>
								<td colspan="4" align="right">Kurang Cones <span class="label bg-navy"><?php echo $totKCo;?></span></td>
								<td>&nbsp;</td>
								<td colspan="6" align="right">Rajut Sample <span class="label bg-abu bulat">
										<?php echo $totRS;?></span></td>
								<td align="right">&nbsp;</td>
								<td colspan="3" align="right">Tg. Pasang Benang <span class="label bg-purple"><?php echo $totTPB;?></span></td>
								<td align="right">&nbsp;</td>
								<td align="right">&nbsp;</td>
							</tr>

							<tr>
								<td colspan="26" style="padding: 2px;">
									<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
										<?php echo $rNews["news_line"];?>
									</marquee>
								</td>
							</tr>
							<tr>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D63"); ?>" id="D63" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D63"); ?>">D63<br />
							    <p> <?php echo Kurang("D63"); ?><br />
							      <br />
							      <?php waktu("D63"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D64"); ?>" id="D64" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D64"); ?>">D64<br />
							    <p> <?php echo Kurang("D64"); ?><br />
							      <br />
							      <?php waktu("D64"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D65"); ?>" id="D65" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D65"); ?>">D65<br />
							    <p> <?php echo Kurang("D65"); ?><br />
							      <br />
							      <?php waktu("D65"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D66"); ?>" id="D66" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D66"); ?>">D66<br />
							    <p> <?php echo Kurang("D66"); ?><br />
							      <br />
							      <?php waktu("D66"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D67"); ?>" id="D67" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D67"); ?>">D67<br />
							    <p> <?php echo Kurang("D67"); ?><br />
							      <br />
							      <?php waktu("D67"); ?>
						      </p>
							    </span></a></td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("D53"); ?>" id="D53" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D53"); ?>">D53<br />
							    <p> <?php echo Kurang("D53"); ?><br />
							      <br />
							      <?php waktu("D53"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("D54"); ?>" id="D54" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D54"); ?>">D54<br />
							    <p> <?php echo Kurang("D54"); ?><br />
							      <br />
							      <?php waktu("D54"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("D55"); ?>" id="D55" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D55"); ?>">D55<br />
							    <p> <?php echo Kurang("D55"); ?><br />
							      <br />
							      <?php waktu("D55"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("D56"); ?>" id="D56" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D56"); ?>">D56<br />
							    <p> <?php echo Kurang("D56"); ?><br />
							      <br />
							      <?php waktu("D56"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("D57"); ?>" id="D57" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D57"); ?>">D57<br />
							    <p> <?php echo Kurang("D57"); ?><br />
							      <br />
							      <?php waktu("D57"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("D58"); ?>" id="D58" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D58"); ?>">D58<br />
							    <p> <?php echo Kurang("D58"); ?><br />
							      <br />
							      <?php waktu("D58"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D59"); ?>" id="D59" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D59"); ?>">"D59"<br />
							    <p> <?php echo Kurang("D59"); ?><br />
							      <br />
							      <?php waktu("D59"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D60"); ?>" id="D60" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D60"); ?>">D60<br />
							    <p> <?php echo Kurang("D60"); ?><br />
							      <br />
							      <?php waktu("D60"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D61"); ?>" id="D61" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D61"); ?>">D61<br />
							    <p> <?php echo Kurang("D61"); ?><br />
							      <br />
							      <?php waktu("D61"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D62"); ?>" id="D62" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D62"); ?>">D62<br />
							    <p> <?php echo Kurang("D62"); ?><br />
							      <br />
							      <?php waktu("D62"); ?>
						      </p>
							    </span></a></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D43"); ?>" id="D43" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D43"); ?>">D43<br />
							    <p> <?php echo Kurang("D43"); ?><br />
							      <br />
							      <?php waktu("D43"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D44"); ?>" id="D44" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D44"); ?>">D44<br />
							    <p> <?php echo Kurang("D44"); ?><br />
							      <br />
							      <?php waktu("D44"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("D45"); ?>" id="D45" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D45"); ?>">D45<br />
							    <p> <?php echo Kurang("D45"); ?><br />
							      <br />
							      <?php waktu("D45"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D46"); ?>" id="D46" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D46"); ?>">D46<br />
							    <p> <?php echo Kurang("D46"); ?><br />
							      <br />
							      <?php waktu("D46"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D47"); ?>" id="D47" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D47"); ?>">D47<br />
							    <p> <?php echo Kurang("D47"); ?><br />
							      <br />
							      <?php waktu("D47"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D48"); ?>" id="D48" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D48"); ?>">D48<br />
							    <p> <?php echo Kurang("D48"); ?><br />
							      <br />
"							      <?php waktu("D48"); ?>
						      </p>
"							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D49"); ?>" id="D49" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D49"); ?>">D49<br />
							    <p> <?php echo Kurang("D49"); ?><br />
							      <br />
							      <?php waktu("D49"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D50"); ?>" id="D50" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D50"); ?>">D50<br />
							    <p> <?php echo Kurang("D50"); ?><br />
							      <br />
							      <?php waktu("D50"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D51"); ?>" id="D51" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D51"); ?>">D51<br />
							    <p> <?php echo Kurang("D51"); ?><br />
							      <br />
							      <?php waktu("D51"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D52"); ?>" id="D52" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D52"); ?>">D52<br />
							    <p> <?php echo Kurang("D52"); ?><br />
							      <br />
							      <?php waktu("D52"); ?>
						      </p>
							    </span></a></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr style="height: 0.1in;">
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D33"); ?>" id="D33" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D33"); ?>">D33<br />
							    <p> <?php echo Kurang("D33"); ?><br />
							      <br />
							      <?php waktu("D33"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D34"); ?>" id="D34" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D34"); ?>">D34<br />
							    <p> <?php echo Kurang("D34"); ?><br />
							      <br />
							      <?php waktu("D34"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D35"); ?>" id="D35" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("D35"); ?>">D35<br />
							    <p> <?php echo Kurang("D35"); ?><br />
							      <br />
							      <?php waktu("D35"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D36"); ?>" id="D36" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("D36"); ?>">D36<br />
							    <p> <?php echo Kurang("D36"); ?><br />
							      <br />
							      <?php waktu("D36"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D37"); ?>" id="D37" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("D37"); ?>">D37<br />
							    <p> <?php echo Kurang("D37"); ?><br />
							      <br />
							      <?php waktu("D37"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D38"); ?>" id="D38" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("D38"); ?>">D38<br />
							    <p> <?php echo Kurang("D38"); ?><br />
							      <br />
							      <?php waktu("D38"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D39"); ?>" id="D39" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("D39"); ?>">D39<br />
							    <p> <?php echo Kurang("D39"); ?><br />
							      <br />
							      <?php waktu("D39"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D40"); ?>" id="D40" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("D40"); ?>">D40<br />
							    <p> <?php echo Kurang("D40"); ?><br />
							      <br />
							      <?php waktu("D40"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D41"); ?>" id="D41" data-toggle="tooltip" data-html="true" data-="data-" title="<?php echo Rajut("D41"); ?>">D41<br />
							    <p> <?php echo Kurang("D41"); ?><br />
							      <br />
							      <?php waktu("D41"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D42"); ?>" id="D42" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D42"); ?>">D42<br />
							    <p> <?php echo Kurang("D42"); ?><br />
							      <br />
							      <?php waktu("D42"); ?>
						      </p>
							    </span></a></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D23"); ?>" id="D23" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D23"); ?>">D23<br />
							    <p> <?php echo Kurang("D23"); ?><br />
							      <br />
							      <?php waktu("D23"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D24"); ?>" id="D24" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D24"); ?>">D24<br />
							    <p> <?php echo Kurang("D24"); ?><br />
							      <br />
							      <?php waktu("D24"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D25"); ?>" id="D25" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D25"); ?>">D25<br />
							    <p> <?php echo Kurang("D25"); ?><br />
							      <br />
							      <?php waktu("D25"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D26"); ?>" id="D26" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D26"); ?>">D26<br />
							    <p> <?php echo Kurang("D26"); ?><br />
							      <br />
							      <?php waktu("D26"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D27"); ?>" id="D27" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D27"); ?>">D27<br />
							    <p> <?php echo Kurang("D27"); ?><br />
							      <br />
							      <?php waktu("D27"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D28"); ?>" id="D28" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D28"); ?>">D28<br />
							    <p> <?php echo Kurang("D28"); ?><br />
							      <br />
							      <?php waktu("D28"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D29"); ?>" id="D29" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D29"); ?>">D29<br />
							    <p> <?php echo Kurang("D29"); ?><br />
							      <br />
							      <?php waktu("D29"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D30"); ?>" id="D30" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D30"); ?>">D30<br />
							    <p> <?php echo Kurang("D30"); ?><br />
							      <br />
							      <?php waktu("D30"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D31"); ?>" id="D31" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D31"); ?>">D31<br />
							    <p> <?php echo Kurang("D31"); ?><br />
							      <br />
							      <?php waktu("D31"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn <?php echo NoMesin("D32"); ?>" id="D32" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("D32"); ?>">D32<br />
							    <p> <?php echo Kurang("D32"); ?><br />
							      <br />
							      <?php waktu("D32"); ?>
						      </p>
							    </span></a></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr style="height: 0.1in;">
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
							  <td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E33"); ?>" id="E33" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("E33"); ?>">E33<br />
							    <p> <?php echo Kurang("E33"); ?><br />
							      <br />
							      <?php waktu("E33"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E34"); ?>" id="E34" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E34"); ?>">E34<br />
							    <p> <?php echo Kurang("E34"); ?><br />
							      <br />
							      <?php waktu("E34"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E35"); ?>" id="E35" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E35"); ?>">E35<br />
							    <p> <?php echo Kurang("E35"); ?><br />
							      <br />
							      <?php waktu("E35"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E36"); ?>" id="E36" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E36"); ?>">E36<br />
							    <p> <?php echo Kurang("E36"); ?><br />
							      <br />
							      <?php waktu("E36"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E37"); ?>" id="E37" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E37"); ?>">E37<br />
							    <p> <?php echo Kurang("E37"); ?><br />
							      <br />
							      <?php waktu("E37"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E28"); ?>" id="E28" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut("E28"); ?>">E28<br />
							    <p> <?php echo Kurang("E28"); ?><br />
							      <br />
							      <?php waktu("E28"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E29"); ?>" id="E29" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E29"); ?>">E29<br />
							    <p> <?php echo Kurang("E29"); ?><br />
							      <br />
							      <?php waktu("E29"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E30"); ?>" id="E30" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E30"); ?>">E30<br />
							    <p> <?php echo Kurang("E30"); ?><br />
							      <br />
							      <?php waktu("E30"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E31"); ?>" id="E31" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E31"); ?>">E31<br />
							    <p> <?php echo Kurang("E31"); ?><br />
							      <br />
							      <?php waktu("E31"); ?>
						      </p>
							    </span></a></td>
							  <td><a><span class="detail_status btn  <?php echo NoMesin("E32"); ?>" id="E32" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E32"); ?>">E32<br />
							    <p> <?php echo Kurang("E32"); ?><br />
							      <br />
							      <?php waktu("E32"); ?>
						      </p>
							    </span></a></td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="26" style="padding: 5px;">
									<!--<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();" >
        <?php //echo $rNews1['news_line'];?>
      </marquee> -->
									&nbsp;</td>
							</tr>

						</table>

					</div>
				</div>
			</div>
			<div id="CekDetailStatus" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			</div>

	</body>

	<!-- jQuery 3 -->
	<script src="./../bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="./../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- AdminLTE App -->
	<script src="./../dist/js/adminlte.min.js"></script>

	<!-- DataTables -->
	<script src="./../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="./../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<!-- bootstrap datepicker -->
	<script src="./../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
	<script src="./../bower_components/toast-master/js/jquery.toast.js"></script>
	<!-- Tooltips -->
	<script src="./../../dist/js/tooltips.js"></script>
	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});

	</script>
	<!-- Javascript untuk popup modal Edit-->
	<script type="text/javascript">
		$(document).on('click', '.detail_status', function(e) {
			var m = $(this).attr("id");
			$.ajax({
				url: "./cek-status-mesin.php",
				type: "GET",
				data: {
					id: m,
				},
				success: function(ajaxData) {
					$("#CekDetailStatus").html(ajaxData);
					$("#CekDetailStatus").modal('show', {
						backdrop: 'true'
					});
				}
			});
		});

		//            tabel lookup KO status terima
		$(function() {
			$("#lookup").dataTable();
		});

	</script>
	<script>
		$(document).ready(function() {
			"use strict";
			// toat popup js
			$.toast({
				heading: 'Selamat Datang',
				text: 'Knitting Indo Taichen',
				position: 'bottom-right',
				loaderBg: '#ff6849',
				icon: 'success',
				hideAfter: 3500,
				stack: 6
			})


		});
		$(".tst1").on("click", function() {
			var msg = $('#message').val();
			var title = $('#title').val() || '';
			$.toast({
				heading: 'Info',
				text: msg,
				position: 'top-right',
				loaderBg: '#ff6849',
				icon: 'info',
				hideAfter: 3000,
				stack: 6
			});

		});

	</script>
</html>
<?php mysqli_close($con);?>
