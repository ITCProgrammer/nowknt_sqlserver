<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stacked Bar Chart</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

</head>
<body>
    <canvas id="stackedChart" style="width: 100%; height: 400px;"></canvas>

    <script>
        // Ganti dengan URL API Anda
        const apiUrl = 'https://online.indotaichen.com/NOWknt/pages/api/api_mesin_top5.php';

        // Fetch data dari API
        fetch(apiUrl)
            .then(response => response.json()) // Konversi response ke JSON
            .then(data => {
                console.log("Data dari API:", data); // Debugging untuk cek data

                // Periksa apakah data memiliki format yang benar
                if (!data.labels || !data.datasets) {
                    console.error("Invalid data structure:", data);
                    return;
                }

                var ctx = document.getElementById('stackedChart').getContext('2d');

                // Buat chart menggunakan data dari API
                var stackedChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: data.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
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
                                formatter: (value) => value > 0 ? value : '' // Hanya tampilkan label jika nilai > 0
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            })
            .catch(error => console.error("Error fetching data:", error));
    </script>
</body>
</html>
