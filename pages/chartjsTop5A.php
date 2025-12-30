<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stacked Bar Chart from API</title>

	<script src="plugins/chart.js/chart371.js"></script>
    <script src="plugins/chart.js/chartjs-plugin-datalabels.js"></script>
</head>
<body>
    
<div class="row">
          <div class="col-md-6">
            <!-- AREA CHART -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Area Chart</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="stackedChart" style="width: 100%; height: 400px;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
		</div>
          <!-- /.col (LEFT) -->          
        </div>
    <script>
        // URL API
        const apiUrl = 'https://online.indotaichen.com/NOWknt/pages/api/api_mesin_top5A.php';

        // Fungsi untuk mengambil data dari API
        async function fetchData() {
            try {
                const response = await fetch(apiUrl);
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching data:', error);
                return [];
            }
        }

        // Fungsi untuk mengonversi data API ke format Chart.js
        function convertData(apiData) {
            // Ambil daftar tanggal dari kunci objek pertama (kecuali NO_MESIN & TOTAL)
            const dates = Object.keys(apiData[0]).filter(key => key !== 'NO_MESIN' && key !== 'TOTAL');

            // Warna untuk setiap mesin (bisa disesuaikan)
            const colors = ['#e67e22', '#3498db', '#1f497d', '#8e44ad', '#2c7b4f'];

            // Ubah data API menjadi format Chart.js
            const datasets = apiData.map((item, index) => ({
                label: item.NO_MESIN,
                data: dates.map(date => item[date]), // Ambil data untuk tiap tanggal
                backgroundColor: colors[index % colors.length]
            }));

            return { dates, datasets };
        }

        // Fungsi untuk membuat chart
        async function createChart() {
            const apiData = await fetchData();
            const { dates, datasets } = convertData(apiData);

            const ctx = document.getElementById('stackedChart').getContext('2d');
            Chart.register(ChartDataLabels);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates,  // Label sumbu X (tanggal)
                    datasets: datasets // Data sumbu Y (jumlah produksi per mesin)
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        datalabels: {
                            anchor: 'center',
                            align: 'center',
                            color: '#fff',
                            font: { weight: 'bold' },
                            formatter: (value) => value > 0 ? value : ''
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        }

        // Panggil fungsi untuk membuat chart saat halaman dimuat
        createChart();
    </script>
</body>
</html>
