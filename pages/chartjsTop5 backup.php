<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stacked Bar Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
    <canvas id="stackedChart" style="width: 100%; height: 400px;"></canvas>

    <script>
        var ctx = document.getElementById('stackedChart').getContext('2d');

        var stackedChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['26/12/2024', '27/12/2024', '28/12/2024', '29/12/2024', '30/12/2024'],
                datasets: [
                    {
                        label: 'M70',
                        data: [2, 3, 3, 0, 6],
                        backgroundColor: '#1f497d'
                    },
                    {
                        label: 'D94',
                        data: [1, 1, 1, 0, 2],
                        backgroundColor: '#e67e22'
                    },
                    {
                        label: 'D125',
                        data: [1, 2, 0, 0, 2],
                        backgroundColor: '#3498db'
                    },
                    {
                        label: 'D84',
                        data: [0, 2, 2, 0, 3],
                        backgroundColor: '#2c7b4f'
                    },
                    {
                        label: 'D04',
                        data: [1, 0, 1, 0, 0],
                        backgroundColor: '#8e44ad'
                    },
                    {
                        label: 'E108',
                        data: [0, 0, 1, 0, 2],
                        backgroundColor: '#27ae60'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    datalabels: {
                        anchor: 'center',
                        align: 'center',
                        color: '#fff',
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value) => value > 0 ? value : '' // Hanya tampilkan label jika nilai > 0
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
</body>
</html>
