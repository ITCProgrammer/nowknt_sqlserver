<!-- Main content -->
      <div class="container-fluid">
		<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-info"></i> Info</h5>
                  Upload Data Stock Benang Knitting<br>
			Template CSV <a href="pages/template/template-test.csv"><i class="fa fa-download blink_me"></i> </a>
		</div>  
		<form role="form" method="post" enctype="multipart/form-data" name="form1" >  
		<div class="card card-purple">
          <div class="card-header">
            <h3 class="card-title">Upload Data Benang Knitting</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
         <div class="card-body">             
			<div class="form-group">
                    <label for="file">File input CSV</label>
                    <div class="input-group">
                      <div class="custom-file">
						<input type="file" name="file" id="file" class="input-large" accept=".csv">
                        <label class="custom-file-label" for="file">Choose file</label>
                      </div>
                      <div class="input-group-append">
						<button type="submit" id="submit" name="submit" class="mb-3 btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>  
                      </div>
                    </div>
                  </div>  
			 
		  </div>
		  <div class="card-footer">
           <a href="DataUpload" class="btn btn-info">Kembali</a>
          </div>
		  <!-- /.card-body -->
          
        </div> 
	</form>
		          
</div><!-- /.container-fluid -->
    <!-- /.content -->
<?php
if (isset($_POST['submit'])) {
    require_once 'koneksi.php';

    $fileMimes = [
        'text/csv','text/plain','application/csv','text/comma-separated-values',
        'text/x-csv','application/x-csv','text/x-comma-separated-values',
        'application/vnd.ms-excel','application/octet-stream'
    ];

    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {
        $idUp = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $nameFile = $_FILES['file']['name'];
        $csv = fopen($_FILES['file']['tmp_name'], 'r');
        if (!$csv) { die("<script>alert('Gagal membuka file'); history.back();</script>"); }

        // util
        function parse_date_to_ymd($s) {
            $s = trim($s); if ($s==='') return null;
            foreach (['d/m/Y','m/d/Y','Y-m-d','d-m-Y','m-d-Y'] as $fmt) {
                $dt = DateTime::createFromFormat($fmt, $s);
                if ($dt && $dt->format($fmt) === $s) return $dt->format('Y-m-d');
            }
            $dt = date_create($s); return $dt ? $dt->format('Y-m-d') : null;
        }
        // deteksi SN notasi ilmiah (contoh: 8E+12, 1.2e5, -3e-8)
        function is_scientific_like($s) {
            $s = trim((string)$s);
            if ($s === '') return false;
            return (bool)preg_match('/^[+-]?\\d+(?:\\.\\d+)?e[+-]?\\d+$/i', $s);
        }

        // header map
        $header = fgetcsv($csv, 100000, ',');
        if (!$header) { fclose($csv); die("<script>alert('Header CSV kosong'); history.back();</script>"); }
        $map = [];
        foreach ($header as $i => $h) { $map[strtolower(trim($h))] = $i; }

        $required = ['tglbuat','sn','cones','jenis_benang','kg','zone','lokasi','project','lot'];
        foreach ($required as $col) {
            if (!array_key_exists($col, $map)) {
                fclose($csv);
                die("<script>alert('Kolom wajib \"$col\" tidak ada di header CSV'); history.back();</script>");
            }
        }

        // Validasi awal: tolak jika ada SN notasi ilmiah
        $bad = [];
        $line = 1; // header di baris 1
        while (($row = fgetcsv($csv, 200000, ',')) !== false) {
            $line++;
            if (count(array_filter($row, fn($v)=>trim((string)$v)!=='')) === 0) continue;
            $snRaw = $row[$map['sn']] ?? '';
            if (is_scientific_like($snRaw)) {
                $bad[] = ['line'=>$line, 'sn'=>$snRaw];
                if (count($bad) >= 10) break; // tampilkan contoh maks 10 baris
            }
        }
        if (!empty($bad)) {
            fclose($csv);
            // susun pesan baris bermasalah
            $detail = implode("\\n", array_map(fn($b)=>"Baris {$b['line']} -> SN: {$b['sn']}", $bad));
            echo "<script>alert('Upload DITOLAK: Ditemukan nilai SN dalam notasi ilmiah (mis. 8E+12).\\n"
               . "Perbaiki file: formatkan kolom SN sebagai NUMBER di Excel sebelum ekspor CSV.\\n\\nContoh baris bermasalah:\\n{$detail}"
               . (count($bad)===10 ? "\\n... (mungkin masih ada baris lain)":"")
               . "'); history.back();</script>";
            exit;
        }

        // Kembali ke awal untuk proses insert
        rewind($csv);
        fgetcsv($csv, 100000, ','); // skip header

        $sql = "INSERT INTO dbknitt.tbl_stokfull (tgl_buat, SN, jenis_benang, Cones, KG, zone, lokasi, project, lot, id_upload)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $insertParams = [&$tgl_buat, &$SN, &$jenis_benang, &$Cones, &$KG, &$zone, &$lokasi, &$project, &$lot, &$idUp];
        $stmt = sqlsrv_prepare($con, $sql, $insertParams);
        if ($stmt === false) {
            $err = addslashes(print_r(sqlsrv_errors(), true));
            fclose($csv);
            die("<script>alert('Gagal mempersiapkan query: {$err}'); history.back();</script>");
        }

        if (!sqlsrv_begin_transaction($con)) {
            $err = addslashes(print_r(sqlsrv_errors(), true));
            fclose($csv);
            die("<script>alert('Gagal memulai transaksi: {$err}'); history.back();</script>");
        }
        try {
            $rowCount = 0;

            while (($row = fgetcsv($csv, 200000, ',')) !== false) {
                if (count(array_filter($row, fn($v)=>trim((string)$v)!=='')) === 0) continue;

                $tgl_buat = parse_date_to_ymd($row[$map['tglbuat']]);
                if (!$tgl_buat) throw new Exception("Format tanggal tidak dikenali di baris ke-".($rowCount+2));

                $SN     = trim((string)$row[$map['sn']]);   // sudah divalidasi bukan notasi ilmiah
                $Cones  = (string)trim($row[$map['cones']]);
                $jenis_benang = trim((string)$row[$map['jenis_benang']]);
                $KG     = (string)trim($row[$map['kg']]);
                $zone   = trim((string)$row[$map['zone']]);
                $lokasi = trim((string)$row[$map['lokasi']]);
                $project= trim((string)$row[$map['project']]);
                $lot    = trim((string)$row[$map['lot']]);

                if (!sqlsrv_execute($stmt)) {
                    throw new Exception('Gagal insert baris ke-'.($rowCount+2).': '.print_r(sqlsrv_errors(), true));
                }
                $rowCount++;
            }
            fclose($csv);

            $u = sqlsrv_query($con, "UPDATE dbknitt.tbl_upload SET nama_file=?, tgl_upload=GETDATE() WHERE id=?", [$nameFile, $idUp]);
            if ($u === false) {
                throw new Exception('Gagal memperbarui header upload: '.print_r(sqlsrv_errors(), true));
            }

            sqlsrv_commit($con);
            echo "<script>alert('CSV berhasil: {$rowCount} baris diimport'); window.location='DataUpload';</script>";
        } catch (Throwable $e) {
            sqlsrv_rollback($con);
            if (is_resource($csv)) {
                fclose($csv);
            }
            $msg = addslashes($e->getMessage());
            echo "<script>alert('Gagal import: {$msg}'); history.back();</script>";
        }
    } else {
        echo "<script>alert('Harap unggah file CSV yang valid'); history.back();</script>";
    }
}
?>



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
	
});		
</script>
