<?php
session_start();
include"./../koneksi.php";

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
						<h3 class="box-title">Status Mesin Rajut Knitting ITTI Lantai 1</h3>
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
WHERE a.penyelesaian='' and b.no_po='$rD01[no_po]' and c.no_mc='$rD01[no_mesin]' ");
	$D01TPB=mysqli_query($con,"SELECT count(*) as jml FROM tbl_pergerakan_benang a 
INNER JOIN tbl_pergerakan_benang_detail b ON a.id=b.id_benang
WHERE a.no_mesin='$rD01[no_mesin]' AND a.pono='$rD01[no_po]' AND a.tujuan='MESIN' ");
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
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-navy";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="bg-navy border-dashed";
            } else {
                $warnaD01="bg-navy blink_me";
            }
        } else {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-navy bulat";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="bg-navy border-dashed bulat";
            } else {
                $warnaD01="bg-navy blink_me bulat";
            }
        }
		}
    } elseif ($rD01[posisi]=="2") {/*Sedang Jalan</font>*/
        if ($rD01[tujuan]=="PRODUKSI") {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="btn-success";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="btn-success border-dashed";
            } else {
                $warnaD01="btn-success blink_me";
            }
        } else {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="btn-success bulat";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="btn-success border-dashed bulat";
            } else {
                $warnaD01="btn-success blink_me bulat";
            }
        }
    } elseif ($rD01[posisi]=="3" and $D01rTPB[jml]<="0") {/*Tunggu Pasang Benang*/
        if ($rD01[tujuan]=="PRODUKSI") {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-purple";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="bg-purple border-dashed";
            } else {
                $warnaD01="bg-purple blink_me";
            }
        } else {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-purple bulat";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="bg-purple border-dashed bulat";
            } else {
                $warnaD01="bg-purple blink_me bulat";
            }
        }
    } elseif ($rD01[posisi]=="3" and $D01rTPB[jml] > "0") {/*Tunggu Setting*/
        if ($rD01[tujuan]=="PRODUKSI") {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="btn-primary";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="btn-primary border-dashed";
            } else {
                $warnaD01="btn-primary blink_me";
            }
        } else {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="btn-primary bulat";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="btn-primary border-dashed bulat";
            } else {
                $warnaD01="btn-primary blink_me bulat";
            }
        }
    } elseif ($rD01[posisi]=="4") {/*Tunggu Benang Supplier*/
        if ($rD01[tujuan]=="PRODUKSI") {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-fuchsia";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="bg-fuchsia border-dashed";
            } else {
                $warnaD01="bg-fuchsia blink_me";
            }
        } else {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-fuchsia bulat";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="bg-fuchsia border-dashed bulat";
            } else {
                $warnaD01="bg-fuchsia blink_me bulat";
            }
        }
    } elseif ($rD01[posisi]=="5") {/*Antri Mesin*/
        if ($rD01[tujuan]=="PRODUKSI") {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-kuning";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="bg-kuning border-dashed";
            } else {
                $warnaD01="bg-kuning blink_me";
            }
        } else {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-kuning bulat";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="bg-kuning border-dashed bulat";
            } else {
                $warnaD01="bg-kuning blink_me bulat";
            }
        }
    } elseif ($rD01[posisi]=="6") {/*Tunggu Benang Warehouse*/
        if ($rD01[tujuan]=="PRODUKSI") {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="btn-warning";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="btn-warning border-dashed";
            } else {
                $warnaD01="btn-warning blink_me";
            }
        } else {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="btn-warning bulat";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="btn-warning border-dashed bulat";
            } else {
                $warnaD01="btn-warning blink_me bulat";
            }
        }
    } elseif ($rD01[posisi]=="7") {/*Hold*/
        if ($rD01[tujuan]=="PRODUKSI") {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-teal";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
                $warnaD01="bg-teal border-dashed";
            } else {
                $warnaD01="bg-teal blink_me";
            }
        } else {
            if ($rD01[sts_order]=="Normal") {
                $warnaD01="bg-teal bulat";
            } elseif ($rD01[sts_order]=="Potensi Delay") {
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
WHERE a.no_po='$rD01[no_po]' and b.`status`<>'SELESAI' ");
    $r2=mysqli_fetch_array($sql2);
    $sql3=mysqli_query($con,"SELECT sum(berat) as KG,sum(if(berat>0,1,0)) as Roll from tbl_inspeksi a
INNER JOIN tbl_inspeksi_detail b ON a.id=b.id_inspeksi WHERE a.no_po='$rD01[no_po]'");
    $r3=mysqli_fetch_array($sql3);
    $sql4=mysqli_query($con,"SELECT diameter_mesin,gauge_mesin,catatan FROM tbl_mesin WHERE no_mesin='$mc'");
    $r4=mysqli_fetch_array($sql4);
    $totkr=number_format($r2[qty_order]-$r3[KG]);
    echo "<h3><u>".$mc."</u></h3>Ukuran: ".$r4[diameter_mesin]."''x".$r4[gauge_mesin]."G"."<br>No PO: ".$rD01[no_po]."<br> No Art: ".$rD01[no_artikel]."<br> Kurang Rajut: ".$totkr." Kg<br>".$r4[catatan];
    // echo 	"<font size=-2>".$totkr." Kg</font>";
}
function Kurang($mc)
{
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
WHERE a.no_po='$rD01[no_po]' and b.`status`<>'SELESAI' ");
    $r2=mysqli_fetch_array($sql2);
    $sql3=mysqli_query($con,"SELECT sum(berat) as KG,sum(if(berat>0,1,0)) as Roll from tbl_inspeksi a
INNER JOIN tbl_inspeksi_detail b ON a.id=b.id_inspeksi WHERE a.no_po='$rD01[no_po]'");
    $r3=mysqli_fetch_array($sql3);
    $totkr=number_format($r2[qty_order]-$r3[KG]);
    echo $totkr;
    // echo 	"<font size=-2>".$totkr." Kg</font>";
}
function waktu($mc){
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
INNER JOIN tbl_inspeksi_detail b ON a.id=b.id_inspeksi WHERE a.no_po='$rowd[no_po]' AND b.no_mc='$mc'");
$r1=mysqli_fetch_array($sql1);	
$sql5=mysqli_query($con,"SELECT c.SN,b.no_po,c.no_mc,a.tgl_update,now() as tgl FROM tbl_inspeksi b 
INNER JOIN tbl_inspeksi_detail c ON b.id=c.id_inspeksi
INNER JOIN tbl_perbaikan a ON a.SN=c.SN
WHERE a.penyelesaian='' and b.no_po='$rowd[no_po]' and c.no_mc='$mc' ");		
$r5=mysqli_fetch_array($sql5);
	
$awalP  = strtotime($r5['tgl_update']);
$akhirP = strtotime($r5['tgl']);
$diffP  = ($akhirP - $awalP);
$tjamP  =round($diffP / (60 * 60),2);
$hariP  =round($tjamP/24,2);
		
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
	if($rowd[status]=="BUKA" and $r1[KG]>0 and $rowd[sts]!="TEST QUALITY" and $rowd[sts]!="BENANG HABIS"){echo "<font color=white></font>";}else 
	if($rowd[status]=="BUKA" and ($r1[KG]=="" or $r1[KG]==0) and $rowd[sts]!="TEST QUALITY" and $rowd[sts]!="BENANG HABIS"){echo "<font color=white>$hariT</font>";}else if($rowd[status]=="BUKA" and $rowd[sts]=="TEST QUALITY"){echo "<font color=white>$hariTQ</font>";}else if($rowd[status]=="BUKA" and $rowd[sts]=="BENANG HABIS"){echo "<font color=black>$hariT</font>";}else if($rowd[status]=="ANTRI MESIN"){echo "<font color=black>$hariAM</font>";}else{echo"";}*/
	if($rowd[status]=="BUKA" and ($r1[KG]=="" or $r1[KG]==0) and $rowd[sts]!="TEST QUALITY" and $rowd[sts]!="BENANG HABIS"){echo "<font color=black>$hariT Hari</font>";}
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
        $sts=NoMesin($rM[no_mesin]);
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
								<td><a><span class="detail_status btn  <?php echo NoMesin(M03); ?>" id="M03" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut(M03); ?>">M03<br>
											<p>
												<?php echo Kurang(M03); ?><br><br><?php waktu(M03); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(M02); ?>" id="M02" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M02); ?>">M02<br>
											<p>
												<?php echo Kurang(M02); ?><br><br><?php waktu(M02); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(M01); ?>" id="M01" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M01); ?>">M01<br>
											<p>
												<?php echo Kurang(M01); ?><br><br><?php waktu(M01); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(R8); ?>" id="R8" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(R8); ?>">R8&nbsp;&nbsp;<br>
											<p>
												<?php echo Kurang(R8); ?><br><br><?php waktu(R8); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(R1); ?>" id="R1" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(R1); ?>">R1&nbsp;&nbsp;<br>
											<p>
												<?php echo Kurang(R1); ?><br><br><?php waktu(R1); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(E13); ?>" id="E13" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E13); ?>">E13<br>
											<p>
												<?php echo Kurang(E13); ?><br><br><?php waktu(E13); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(E16); ?>" id="E16" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E16); ?>">E16<br>
											<p>
												<?php echo Kurang(E16); ?><br><br><?php waktu(E16); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(M27); ?>" id="M27" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M27); ?>">M27<br>
											<p>
												<?php echo Kurang(M27); ?><br><br><?php waktu(M27); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(M36); ?>" id="M36" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M36); ?>">M36<br>
											<p>
												<?php echo Kurang(M36); ?><br><br><?php waktu(M36); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(E03); ?>" id="E03" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E03); ?>">E03<br>
											<p>
												<?php echo Kurang(E03); ?><br><br><?php waktu(E03); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(E38); ?>" id="E38" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E38); ?>">E38<br>
											<p>
												<?php echo Kurang(E38); ?><br><br><?php waktu(E38); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(E44); ?>" id="E44" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E44); ?>">E44<br>
											<p>
												<?php echo Kurang(E44); ?><br><br><?php waktu(E44); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(M52); ?>" id="M52" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M52); ?>">M52<br>
											<p>
												<?php echo Kurang(M52); ?><br><br><?php waktu(M52); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(M47); ?>" id="M47" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M47); ?>">M47<br>
											<p>
												<?php echo Kurang(M47); ?><br><br><?php waktu(M47); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn  <?php echo NoMesin(M42); ?>" id="M42" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M42); ?>">M42<br>
											<p>
												<?php echo Kurang(M42); ?><br><br><?php waktu(M42); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M37); ?>" id="M37" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M37); ?>">M37<br>
											<p>
												<?php echo Kurang(M37); ?><br><br><?php waktu(M37); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M57); ?>" id="M57" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M57); ?>">M57<br>
											<p>
												<?php echo Kurang(M57); ?><br><br><?php waktu(M57); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M62); ?>" id="M62" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M62); ?>">M62<br>
											<p>
												<?php echo Kurang(M62); ?><br><br><?php waktu(M62); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M77); ?>" id="M77" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M77); ?>">M77<br>
											<p>
												<?php echo Kurang(M77); ?><br><br><?php waktu(M77); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M69); ?>" id="M69" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M69); ?>">M69<br>
											<p>
												<?php echo Kurang(M69); ?><br><br><?php waktu(M69); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M70); ?>" id="M70" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M70); ?>">M70<br>
											<p>
												<?php echo Kurang(M70); ?><br><br><?php waktu(M70); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D21); ?>" id="D21" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(D21); ?>">D21<br>
											<p>
												<?php echo Kurang(D21); ?><br><br><?php waktu(D21); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D16); ?>" id="D16" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(D16); ?>">D16<br>
											<p>
												<?php echo Kurang(D16); ?><br><br><?php waktu(D16); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D11); ?>" id="D11" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(D11); ?>">D11<br>
											<p>
												<?php echo Kurang(D11); ?><br><br><?php waktu(D11); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D06); ?>" id="D06" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(D06); ?>">D06<br>
											<p>
												<?php echo Kurang(D06); ?><br><br><?php waktu(D06); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D01); ?>" id="D01" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut(D01); ?>">D01<br>
											<p>
												<?php echo Kurang(D01); ?><br><br><?php waktu(D01); ?>
											</p>
										</span></a></td>
							</tr>
							<tr>
								<td><a><span class="detail_status btn <?php echo NoMesin(M05); ?>" id="M05" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut(M05); ?>">M05<br>
											<p>
												<?php echo Kurang(M05); ?><br><br><?php waktu(M05); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M09); ?>" id="M09" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M09); ?>">M09<br>
											<p>
												<?php echo Kurang(M09); ?><br><br><?php waktu(M09); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M08); ?>" id="M08" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M08); ?>">M08<br>
											<p><?php echo Kurang(M08); ?><br><br><?php waktu(M08); ?></p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R9); ?>" id="R9" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(R9); ?>">R9&nbsp;&nbsp;<br>
											<p>
												<?php echo Kurang(R9); ?><br><br><?php waktu(R9); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R2); ?>" id="R2" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(R2); ?>">R2&nbsp;&nbsp;<br>
											<p>
												<?php echo Kurang(R2); ?><br><br><?php waktu(R2); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E15); ?>" id="E15" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E15); ?>">E15<br>
											<p>
												<?php echo Kurang(E15); ?><br><br><?php waktu(E15); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E18); ?>" id="E18" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E18); ?>">E18<br>
											<p>
												<?php echo Kurang(E18); ?><br><br><?php waktu(E18); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M28); ?>" id="M28" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M28); ?>">M28<br>
											<p>
												<?php echo Kurang(M28); ?><br><br><?php waktu(M28); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M35); ?>" id="M35" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M35); ?>">M35<br>
											<p>
												<?php echo Kurang(M35); ?><br><br><?php waktu(M35); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E05); ?>" id="E05" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E05); ?>">E05<br>
											<p>
												<?php echo Kurang(E05); ?><br><br><?php waktu(E05); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E39); ?>" id="E39" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E39); ?>">E39<br>
											<p>
												<?php echo Kurang(E39); ?><br><br><?php waktu(E39); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E45); ?>" id="E45" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(E45); ?>">E45<br>
											<p>
												<?php echo Kurang(E45); ?><br><br><?php waktu(E45); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M53); ?>" id="M53" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M53); ?>">M53<br>
											<p>
												<?php echo Kurang(M53); ?><br><br><?php waktu(M53); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M48); ?>" id="M48" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M48); ?>">M48<br>
											<p>
												<?php echo Kurang(M48); ?><br><br><?php waktu(M48); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M43); ?>" id="M43" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M43); ?>">M43<br>
											<p>
												<?php echo Kurang(M43); ?><br><br><?php waktu(M43); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M38); ?>" id="M38" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M38); ?>">M38<br>
											<p>
												<?php echo Kurang(M38); ?><br><br><?php waktu(M38); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M58); ?>" id="M58" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M58); ?>">M58<br>
											<p>
												<?php echo Kurang(M58); ?><br><br><?php waktu(M58); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M63); ?>" id="M63" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M63); ?>">M63<br>
											<p>
												<?php echo Kurang(M63); ?><br><br><?php waktu(M63); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M78); ?>" id="M78" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M78); ?>">M78<br>
											<p>
												<?php echo Kurang(M78); ?><br><br><?php waktu(M78); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M68); ?>" id="M68" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M68); ?>">M68<br>
											<p>
												<?php echo Kurang(M68); ?><br><br><?php waktu(M68); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M71); ?>" id="M71" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(M71); ?>">M71<br>
											<p>
												<?php echo Kurang(M71); ?><br><br><?php waktu(M71); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D22); ?>" id="D22" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(D22); ?>">D22<br>
											<p>
												<?php echo Kurang(D22); ?><br><br><?php waktu(D22); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D17); ?>" id="D17" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(D17); ?>">D17<br>
											<p>
												<?php echo Kurang(D17); ?><br><br><?php waktu(D17); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D12); ?>" id="D12" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(D12); ?>">D12<br>
											<p>
												<?php echo Kurang(D12); ?><br><br><?php waktu(D12); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D07); ?>" id="D07" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo Rajut(D07); ?>">D07<br>
											<p>
												<?php echo Kurang(D07); ?><br><br><?php waktu(D07); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D02); ?>" id="D02" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut(D02); ?>">D02<br>
											<p>
												<?php echo Kurang(D02); ?><br><br><?php waktu(D02); ?>
											</p>
										</span></a></td>
							</tr>
							<tr>
								<td><a><span class="detail_status btn <?php echo NoMesin(M06); ?>" id="M06" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut(M06); ?>">M06<br>
											<p>
												<?php echo Kurang(M06); ?><br><br><?php waktu(M06); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M11); ?>" id="M11" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M11); ?>">M11<br>
											<p>
												<?php echo Kurang(M11); ?><br><br><?php waktu(M11); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M10); ?>" id="M10" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M10); ?>">M10<br>
											<p>
												<?php echo Kurang(M10); ?><br><br><?php waktu(M10); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R10); ?>" id="R10" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R10); ?>">R10<br>
											<p>
												<?php echo Kurang(R10); ?><br><br><?php waktu(R10); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R3); ?>" id="R3" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R3); ?>">R3&nbsp;&nbsp;<br>
											<p>
												<?php echo Kurang(R3); ?><br><br><?php waktu(R3); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E14); ?>" id="E14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E14); ?>">E14<br>
											<p>
												<?php echo Kurang(E14); ?><br><br><?php waktu(E14); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E17); ?>" id="E17" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E17); ?>">E17<br>
											<p>
												<?php echo Kurang(E17); ?><br><br><?php waktu(E17); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M29); ?>" id="M29" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M29); ?>">M29<br>
											<p>
												<?php echo Kurang(M29); ?><br><br><?php waktu(M29); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M34); ?>" id="M34" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M34); ?>">M34<br>
											<p>
												<?php echo Kurang(M34); ?><br><br><?php waktu(M34); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E21); ?>" id="E21" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E21); ?>">E21<br>
											<p>
												<?php echo Kurang(E21); ?><br><br><?php waktu(E21); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E40); ?>" id="E40" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E40); ?>">E40<br>
											<p>
												<?php echo Kurang(E40); ?><br><br><?php waktu(E40); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E46); ?>" id="E46" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E46); ?>">E46<br>
											<p>
												<?php echo Kurang(E46); ?><br><br><?php waktu(E46); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M54); ?>" id="M54" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M54); ?>">M54<br>
											<p>
												<?php echo Kurang(M54); ?><br><br><?php waktu(M54); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M49); ?>" id="M49" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M49); ?>">M49<br>
											<p>
												<?php echo Kurang(M49); ?><br><br><?php waktu(M49); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M44); ?>" id="M44" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M44); ?>">M44<br>
											<p>
												<?php echo Kurang(M44); ?><br><br><?php waktu(M44); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M39); ?>" id="M39" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M39); ?>">M39<br>
											<p>
												<?php echo Kurang(M39); ?><br><br><?php waktu(M39); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M59); ?>" id="M59" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M59); ?>">M59<br>
											<p>
												<?php echo Kurang(M59); ?><br><br><?php waktu(M59); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M64); ?>" id="M64" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M64); ?>">M64<br>
											<p>
												<?php echo Kurang(M64); ?><br><br><?php waktu(M64); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M79); ?>" id="M79" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M79); ?>">M79<br>
											<p>
												<?php echo Kurang(M79); ?><br><br><?php waktu(M79); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M72); ?>" id="M72" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M72); ?>">M72<br>
											<p>
												<?php echo Kurang(M72); ?><br><br><?php waktu(M72); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M73); ?>" id="M73" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M73); ?>">M73<br>
											<p>
												<?php echo Kurang(M73); ?><br><br><?php waktu(M73); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E11); ?>" id="E11" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E11); ?>">E11<br>
											<p>
												<?php echo Kurang(E11); ?><br><br><?php waktu(E11); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D18); ?>" id="D18" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(D18); ?>">D18<br>
											<p>
												<?php echo Kurang(D18); ?><br><br><?php waktu(D18); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D13); ?>" id="D13" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(D13); ?>">D13<br>
											<p>
												<?php echo Kurang(D13); ?><br><br><?php waktu(D13); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D08); ?>" id="D08" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(D08); ?>">D08<br>
											<p>
												<?php echo Kurang(D08); ?><br><br><?php waktu(D08); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D03); ?>" id="D03" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut(D03); ?>">D03<br>
											<p><?php echo Kurang(D03); ?><br><br><?php waktu(D03); ?></p>
										</span></a></td>
							</tr>
							<tr>
								<td><a><span class="detail_status btn <?php echo NoMesin(M07); ?>" id="M07" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut(M07); ?>">M07<br>
											<p>
												<?php echo Kurang(M07); ?><br><br><?php waktu(M07); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M14); ?>" id="M14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M14); ?>">M14<br>
											<p>
												<?php echo Kurang(M14); ?><br><br><?php waktu(M14); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M12); ?>" id="M12" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M12); ?>">M12<br>
											<p>
												<?php echo Kurang(M12); ?><br><br><?php waktu(M12); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R11); ?>" id="R11" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R11); ?>">R11<br>
											<p>
												<?php echo Kurang(R11); ?><br><br><?php waktu(R11); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R5); ?>" id="R5" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R5); ?>">R5&nbsp;&nbsp;<br>
											<p>
												<?php echo Kurang(R5); ?><br><br><?php waktu(R5); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E22); ?>" id="E22" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E22); ?>">E22<br>
											<p>
												<?php echo Kurang(E22); ?><br><br><?php waktu(E22); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E19); ?>" id="E19" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E19); ?>">E19<br>
											<p>
												<?php echo Kurang(E19); ?><br><br><?php waktu(E19); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M30); ?>" id="M30" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M30); ?>">M30<br>
											<p>
												<?php echo Kurang(M30); ?><br><br><?php waktu(M30); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M33); ?>" id="M33" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M33); ?>">M33<br>
											<p>
												<?php echo Kurang(M33); ?><br><br><?php waktu(M33); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E24); ?>" id="E24" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E24); ?>">E24<br>
											<p>
												<?php echo Kurang(E24); ?><br><br><?php waktu(E24); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E41); ?>" id="E41" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E41); ?>">E41<br>
											<p>
												<?php echo Kurang(E41); ?><br><br><?php waktu(E41); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E47); ?>" id="E47" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E47); ?>">E47<br>
											<p>
												<?php echo Kurang(E47); ?><br><br><?php waktu(E47); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E50); ?>" id="E50" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E50); ?>">E50<br>
											<p>
												<?php echo Kurang(E50); ?><br><br><?php waktu(E50); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M50); ?>" id="M50" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M50); ?>">M50<br>
											<p>
												<?php echo Kurang(M50); ?><br><br><?php waktu(M50); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M45); ?>" id="M45" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M45); ?>">M45<br>
											<p>
												<?php echo Kurang(M45); ?><br><br><?php waktu(M45); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M40); ?>" id="M40" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M40); ?>">M40<br>
											<p>
												<?php echo Kurang(M40); ?><br><br><?php waktu(M40); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M60); ?>" id="M60" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M60); ?>">M60<br>
											<p>
												<?php echo Kurang(M60); ?><br><br><?php waktu(M60); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M65); ?>" id="M65" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M65); ?>">M65<br>
											<p>
												<?php echo Kurang(M65); ?><br><br><?php waktu(M65); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M80); ?>" id="M80" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M80); ?>">M80<br>
											<p>
												<?php echo Kurang(M80); ?><br><br><?php waktu(M80); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M74); ?>" id="M74" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M74); ?>">M74<br>
											<p>
												<?php echo Kurang(M74); ?><br><br><?php waktu(M74); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M75); ?>" id="M75" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M75); ?>">M75<br>
											<p>
												<?php echo Kurang(M75); ?><br><br><?php waktu(M75); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E10); ?>" id="E10" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E10); ?>">E10<br>
											<p>
												<?php echo Kurang(E10); ?><br><br><?php waktu(E10); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D19); ?>" id="D19" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(D19); ?>">D19<br>
											<p>
												<?php echo Kurang(D19); ?><br><br><?php waktu(D19); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D14); ?>" id="D14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(D14); ?>">D14<br>
											<p>
												<?php echo Kurang(D14); ?><br><br><?php waktu(D14); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D09); ?>" id="D09" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(D09); ?>">D09<br>
											<p>
												<?php echo Kurang(D09); ?><br><br><?php waktu(D09); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D04); ?>" id="D04" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut(D04); ?>">D04<br>
											<p>
												<?php echo Kurang(D04); ?><br><br><?php waktu(D04); ?>
											</p>
										</span></a></td>
							</tr>
							<tr>
								<td><a><span class="detail_status btn <?php echo NoMesin(M17); ?>" id="M17" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut(M17); ?>">M17<br>
											<p>
												<?php echo Kurang(M17); ?><br><br><?php waktu(M17); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M20); ?>" id="M20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M20); ?>">M20<br>
											<p>
												<?php echo Kurang(M20); ?><br><br><?php waktu(M20); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M23); ?>" id="M23" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M23); ?>">M23<br>
											<p>
												<?php echo Kurang(M23); ?><br><br><?php waktu(M23); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R12); ?>" id="R12" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R12); ?>">R12<br>
											<p>
												<?php echo Kurang(R12); ?><br><br><?php waktu(R12); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R6); ?>" id="R6" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R6); ?>">R6&nbsp;&nbsp;<br>
											<p>
												<?php echo Kurang(R6); ?><br><br><?php waktu(R6); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E23); ?>" id="E23" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E23); ?>">E23<br>
											<p>
												<?php echo Kurang(E23); ?><br><br><?php waktu(E23); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E20); ?>" id="E20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E20); ?>">E20<br>
											<p>
												<?php echo Kurang(E20); ?><br><br><?php waktu(E20); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M31); ?>" id="M31" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M31); ?>">M31<br>
											<p>
												<?php echo Kurang(M31); ?><br><br><?php waktu(M31); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M32); ?>" id="M32" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M32); ?>">M32<br>
											<p>
												<?php echo Kurang(M32); ?><br><br><?php waktu(M32); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E25); ?>" id="E25" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E25); ?>">E25<br>
											<p>
												<?php echo Kurang(E25); ?><br><br><?php waktu(E25); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E42); ?>" id="E42" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E42); ?>">E42<br>
											<p>
												<?php echo Kurang(E42); ?><br><br><?php waktu(E42); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E48); ?>" id="E48" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E48); ?>">E48<br>
											<p>
												<?php echo Kurang(E48); ?><br><br><?php waktu(E48); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E51); ?>" id="E51" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E51); ?>">E51<br>
											<p>
												<?php echo Kurang(E51); ?><br><br><?php waktu(E51); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M51); ?>" id="M51" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M51); ?>">M51<br>
											<p>
												<?php echo Kurang(M51); ?><br><br><?php waktu(M51); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M46); ?>" id="M46" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M46); ?>">M46<br>
											<p>
												<?php echo Kurang(M46); ?><br><br><?php waktu(M46); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M41); ?>" id="M41" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M41); ?>">M41<br>
											<p>
												<?php echo Kurang(M41); ?><br><br><?php waktu(M41); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M61); ?>" id="M61" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M61); ?>">M61<br>
											<p>
												<?php echo Kurang(M61); ?><br><br><?php waktu(M61); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M66); ?>" id="M66" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M66); ?>">M66<br>
											<p>
												<?php echo Kurang(M66); ?><br><br><?php waktu(M66); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M81); ?>" id="M81" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M81); ?>">M81<br>
											<p>
												<?php echo Kurang(M81); ?><b><br><br><?php waktu(M81); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M76); ?>" id="M76" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M76); ?>">M76<br>
											<p>
												<?php echo Kurang(M76); ?><br><br><?php waktu(M76); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E04); ?>" id="E04" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E04); ?>">E04<br>
											<p>
												<?php echo Kurang(E04); ?><br><br><?php waktu(E04); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E09); ?>" id="D20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E09); ?>">E09<br>
											<p>
												<?php echo Kurang(E09); ?><br><br><?php waktu(E09); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D20); ?>" id="D20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(D20); ?>">D20<br>
											<p>
												<?php echo Kurang(D20); ?><br><br><?php waktu(D20); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D15); ?>" id="D15" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(D15); ?>">D15<br>
											<p>
												<?php echo Kurang(D15); ?><br><br><?php waktu(D15); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D10); ?>" id="D10" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(D10); ?>">D10<br>
											<p>
												<?php echo Kurang(D10); ?><br><br><?php waktu(D10); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(D05); ?>" id="D05" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut(D05); ?>">D05<br>
											<p>
												<?php echo Kurang(D05); ?><br><br><?php waktu(D05); ?>
											</p>
										</span></a></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R7); ?>" id="R7" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R7); ?>">R7&nbsp;&nbsp;<br />
											<p>
												<?php echo Kurang(R7); ?><br><br><?php waktu(R7); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E01); ?>" id="E01" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E01); ?>">E01<br>
											<p>
												<?php echo Kurang(E01); ?><br><br><?php waktu(E01); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E26); ?>" id="E26" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E26); ?>">E26<br>
											<p>
												<?php echo Kurang(E26); ?><br><br><?php waktu(E26); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M26); ?>" id="M26" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M26); ?>">M26<br>
											<p>
												<?php echo Kurang(M26); ?><br><br><?php waktu(M26); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M67); ?>" id="M67" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M67); ?>">M67<br>
											<p>
												<?php echo Kurang(M67); ?><br><br><?php waktu(M67); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E27); ?>" id="E27" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E27); ?>">E27<br>
											<p>
												<?php echo Kurang(E27); ?><br><br><?php waktu(E27); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E43); ?>" id="E43" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E43); ?>">E43<br>
											<p>
												<?php echo Kurang(E43); ?><br><br><?php waktu(E43); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E49); ?>" id="E49" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E49); ?>">E49<br>
											<p>
												<?php echo Kurang(E49); ?><br><br><?php waktu(E49); ?>
											</p>
										</span></a></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="3">Total Mesin <span class="label label-danger">
								<?php echo $totMesin; ?></span></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M82); ?>" id="M82" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M82); ?>">M82<br>
											<p>
												<?php echo Kurang(M82); ?><br><br><?php waktu(M82); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E02); ?>" id="E02" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E02); ?>">E02<br>
											<p>
												<?php echo Kurang(E02); ?><br><br><?php waktu(E02); ?>
											</p>
										</span></a></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><a><span class="detail_status btn <?php echo NoMesin(M18); ?>" id="M18" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut(M18); ?>">M18<br>
											<p>
												<?php echo Kurang(M18); ?><br><br><?php waktu(M18); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M21); ?>" id="M21" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M21); ?>">M21<br>
											<p>
												<?php echo Kurang(M21); ?><br><br><?php waktu(M21); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M24); ?>" id="M24" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M24); ?>">M24<br>
											<p>
												<?php echo Kurang(M24); ?><br><br><?php waktu(M24); ?>
											</p>
										</span></a></td>
								<td>&nbsp;</td>
								<td><a></td>
								<td colspan="4">Tg. Benang Warehouse <span class="label label-warning">
										<?php echo $totTBW;?></span></td>
								<td>&nbsp;</td>
								<td colspan="3">Tg. Setting <span class="label label-primary">
										<?php echo $totTS;?></span></td>
								<td>&nbsp;</td>
								<td colspan="3">Sedang Jalan <span class="label label-success">
										<?php echo $totSJ;?></span></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="3">Tes Quality <span class="label bg-abu">
										<?php echo $totTQ;?></span></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R18); ?>" id="R18" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R18); ?>">R18<br />
								  <p> <?php echo Kurang(R18); ?><br />
								    <br />
								    <?php waktu(R18); ?>
							    </p>
								  </span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R15); ?>" id="R15" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R15); ?>">R15<br />
											<p>
												<?php echo Kurang(R15); ?><br><br><?php waktu(R15); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E59); ?>" id="E59" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E59); ?>">E59<br />
											<p>
												<?php echo Kurang(E59); ?><br><br><?php waktu(E59); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E55); ?>" id="E55" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut(E55); ?>">E55<br />
											<p>
												<?php echo Kurang(E55); ?><br><br><?php waktu(E55); ?>
											</p>
										</span></a></td>
							</tr>
							<tr>
								<td><a><span class="detail_status btn <?php echo NoMesin(M19); ?>" id="M19" data-toggle="tooltip" data-html="true" data-placement="right" title="<?php echo Rajut(M19); ?>">M19<br>
											<p>
												<?php echo Kurang(M19); ?><br><br><?php waktu(M19); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M22); ?>" id="M22" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M22); ?>">M22<br>
											<p>
												<?php echo Kurang(M22); ?><br><br><?php waktu(M22); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(M25); ?>" id="M25" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(M25); ?>">M25<br>
											<p>
												<?php echo Kurang(M25); ?><br><br><?php waktu(M25); ?>
											</p>
										</span></a></td>
								<td colspan="2" align="right">Urgent <span class="label bg-abu blink_me"> <?php echo $totURG;?></span></td>
								<td colspan="3" align="right">Antri Mesin <span class="label bg-kuning">
										<?php echo $totAM;?></span></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="3">Tidak Ada PO <span class="label label-default">
										<?php echo $totTAP;?></span></td>
								<td>&nbsp;</td>
								<td colspan="3">Perbaikan Mesin <span class="label label-danger">
										<?php echo $totPM;?></span></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="3">Tg. Benang Supp. <span class="label bg-fuchsia">
										<?php echo $totTBS;?></span></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R17); ?>" id="R17" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R17); ?>">R17<br />
								  <p> <?php echo Kurang(R17); ?><br />
								    <br />
								    <?php waktu(R17); ?>
							    </p>
								  </span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R13); ?>" id="R13" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R13); ?>">R13<br />
											<p>
												<?php echo Kurang(R13); ?><br><br><?php waktu(R13); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E58); ?>" id="E58" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E58); ?>">E58<br />
											<p>
												<?php echo Kurang(E58); ?><br><br><?php waktu(E58); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E54); ?>" id="E54" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut(E54); ?>">E54<br />
											<p>
												<?php echo Kurang(E54); ?><br><br><?php waktu(E54); ?>
											</p>
										</span></a></td>
							</tr>
							<tr>
								<td colspan="2">Hold <span class="label bg-teal">
								<?php echo $totHLD;?></span></td>
								<td colspan="3">Siap Jalan <span class="label bg-black">
								<?php echo $totKOp;?></span></td>
								<td colspan="3" align="right">Potensi Delay <span class="label btn-default border-dashed">
										<?php echo $totPTD;?></span></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="3">Kurang Cones <span class="label bg-navy"><?php echo $totKCo;?></span></td>
								<td>&nbsp;</td>
								<td colspan="3">Rajut Sample <span class="label bg-abu bulat">
										<?php echo $totRS;?></span></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="3">Tg. Pasang Benang <span class="label bg-purple"><?php echo $totTPB;?></span></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R16); ?>" id="R16" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R16); ?>">R16<br />
								  <p> <?php echo Kurang(R16); ?><br />
								    <br />
								    <?php waktu(R16); ?>
							    </p>
								  </span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E61); ?>" id="E61" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E61); ?>">E61<br />
											<p>
												<?php echo Kurang(E61); ?><br><br><?php waktu(E61); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E57); ?>" id="E57" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E57); ?>">E57<br />
											<p>
												<?php echo Kurang(E57); ?><br><br><?php waktu(E57); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E53); ?>" id="E53" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut(E53); ?>">E53<br />
											<p>
												<?php echo Kurang(E53); ?><br><br><?php waktu(E53); ?>
											</p>
										</span></a></td>
							</tr>

							<tr>
								<td colspan="22" style="padding: 2px;">
									<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
										<?php echo $rNews[news_line];?>
									</marquee>
								</td>
								<td><a><span class="detail_status btn <?php echo NoMesin(R14); ?>" id="R14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(R14); ?>">R14<br />
								  <p> <?php echo Kurang(R14); ?><br />
								    <br />
								    <?php waktu(R14); ?>
							    </p>
							  </span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E60); ?>" id="E60" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E60); ?>">E60<br />
											<p>
												<?php echo Kurang(E60); ?><br><br><?php waktu(E60); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E56); ?>" id="E56" data-toggle="tooltip" data-html="true" title="<?php echo Rajut(E56); ?>">E56<br />
											<p>
												<?php echo Kurang(E56); ?><br><br><?php waktu(E56); ?>
											</p>
										</span></a></td>
								<td><a><span class="detail_status btn <?php echo NoMesin(E52); ?>" id="E52" data-toggle="tooltip" data-html="true" data-placement="left" title="<?php echo Rajut(E52); ?>">E52<br />
											<p>
												<?php echo Kurang(E52); ?><br><br><?php waktu(E52); ?>
											</p>
										</span></a></td>
							</tr>
							<tr>
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
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
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
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
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
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
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
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="26" style="padding: 5px;">
									<!--<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();" >
        <?php //echo $rNews1[news_line];?>
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
