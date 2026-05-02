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
 * Area Line Chart - Pertumbuhan Pendapatan (Modern Gradient)
 */
function initGrowthChart() {
    const canvas = document.getElementById('growthChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const data = window.dashboardData?.growth ?? {};

    const labels = data.labels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const values = data.orders ?? [6.2, 3.7, 1.4, 3.7, 7.2, 3.7, 1.5, 5, 1.2, 3.1, 2.1, 6.2];

    // Build vertical gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.offsetHeight || 240);
    gradient.addColorStop(0,   'rgba(0, 166, 105, 0.30)');
    gradient.addColorStop(0.5, 'rgba(0, 166, 105, 0.10)');
    gradient.addColorStop(1,   'rgba(0, 166, 105, 0.00)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data: values,
                borderColor: '#00a669',
                borderWidth: 2.5,
                backgroundColor: gradient,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 7,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#00a669',
                pointBorderWidth: 2.5,
                pointHoverBackgroundColor: '#00a669',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2.5,
                lineTension: 0.42,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            tooltips: {
                mode: 'index',
                intersect: false,
                backgroundColor: '#111827',
                titleFontColor: '#e5e7eb',
                titleFontSize: 12,
                titleFontStyle: '600',
                bodyFontColor: '#34d399',
                bodyFontSize: 13,
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
                xPadding: 14,
                yPadding: 10,
                cornerRadius: 10,
                displayColors: false,
                callbacks: {
                    title: (items) => items[0]?.xLabel ?? '',
                    label: (item) => `  Pendapatan: ${item.yLabel} Juta`,
                },
            },
            hover: {
                mode: 'index',
                intersect: false,
                animationDuration: 150,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        padding: 12,
                        fontColor: '#9ca3af',
                        fontSize: 11,
                        fontFamily: "'Poppins', sans-serif",
                        callback: (v) => v + ' Jt',
                    },
                    gridLines: {
                        display: true,
                        color: 'rgba(0,0,0,0.04)',
                        drawBorder: false,
                        zeroLineColor: 'rgba(0,0,0,0.06)',
                        lineWidth: 1,
                    },
                }],
                xAxes: [{
                    ticks: {
                        fontColor: '#9ca3af',
                        fontSize: 11,
                        padding: 10,
                        fontFamily: "'Poppins', sans-serif",
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false,
                    },
                }],
            },
            animation: {
                duration: 900,
                easing: 'easeInOutQuart',
            },
            layout: {
                padding: { top: 8, bottom: 0, left: 0, right: 4 },
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
