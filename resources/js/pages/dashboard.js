/**
 * Dashboard - Chart Initialization
 * Depends on: Chart.js (window.Chart)
 *
 * Data dari blade dikirim via window.dashboardData
 * yang di-set di blade sebelum script ini dijalankan.
 */

document.addEventListener('DOMContentLoaded', function () {
    initGrowthChart();
    initPengeluaranChart();
});

/**
 * Bar Chart - Pertumbuhan Pendapatan
 */
function initGrowthChart() {
    const canvas = document.getElementById('growthChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const data = window.dashboardData?.growth ?? {};

    const labels = data.labels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const values = data.orders ?? [6.2, 3.7, 1.4, 3.7, 7.2, 3.7, 1.5, 5, 1.2, 3.1, 2.1, 6.2];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: 'rgba(0, 166, 105, 0.85)',
                hoverBackgroundColor: '#00a669',
                borderWidth: 0,
                barPercentage: 0.55,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            tooltips: {
                backgroundColor: '#fff',
                titleFontColor: '#333',
                bodyFontColor: '#666',
                borderColor: '#e5e5e5',
                borderWidth: 1,
                xPadding: 12,
                yPadding: 12,
                cornerRadius: 8,
                displayColors: false,
                callbacks: {
                    label: (tooltipItem) => `Pendapatan: ${tooltipItem.yLabel} M`,
                },
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        max: 7,
                        stepSize: 1,
                        fontColor: '#999',
                        fontSize: 11,
                        padding: 10,
                    },
                    gridLines: {
                        display: true,
                        color: 'rgba(0,0,0,0.04)',
                        drawBorder: false,
                        zeroLineColor: 'rgba(0,0,0,0.06)',
                    },
                }],
                xAxes: [{
                    ticks: { fontColor: '#999', fontSize: 11, padding: 10 },
                    gridLines: { display: false, drawBorder: false },
                }],
            },
        },
    });
}

/**
 * Donut Chart - Pengeluaran Bulanan
 */
function initPengeluaranChart() {
    const canvas = document.getElementById('pengeluaranChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const data = window.dashboardData?.pengeluaran ?? {};

    const labels = data.labels ?? [];
    const values = data.values ?? [];
    const colors = data.colors ?? [];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 80,
            legend: { display: false },
            tooltips: {
                backgroundColor: '#fff',
                titleFontColor: '#333',
                bodyFontColor: '#666',
                borderColor: '#e5e5e5',
                borderWidth: 1,
                xPadding: 12,
                yPadding: 12,
                cornerRadius: 8,
                displayColors: true,
                callbacks: {
                    label: (tooltipItem, chartData) => {
                        const dataset = chartData.datasets[tooltipItem.datasetIndex];
                        const nominal = dataset.data[tooltipItem.index];
                        const label = chartData.labels[tooltipItem.index];
                        const formatted = nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        return `${label}: Rp ${formatted}`;
                    },
                },
            },
        },
    });
}
