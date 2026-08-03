<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $year = date('Y', strtotime($date_from));
    ?>
    <title>Yearly Report - <?= $client_info->name_app ?> (<?= $year ?>)</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .header p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }

        .chart-container {
            width: 100%;
            max-width: 100%;
            margin-bottom: 30px;
        }

        .chart-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .chart-box {
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: #f8f9fa;
            min-width: 0;
        }

        .chart-box h3 {
            font-size: 14px;
            margin-bottom: 15px;
            color: #555;
            font-weight: 600;
            text-align: center;
        }

        .chart-wrapper {
            position: relative;
            height: 220px;
            width: 100%;
        }

        .chart-wrapper canvas {
            max-width: 100% !important;
            height: auto !important;
        }

        .chart-box.bugs {
            border-color: #dc3545;
        }

        .chart-box.issues {
            border-color: #ffc107;
        }

        .chart-box.request {
            border-color: #0dcaf0;
        }

        .chart-box.development {
            border-color: #198754;
        }

        .chart-box.bugs-open {
            border-color: #fd7e14;
        }

        .chart-box.issues-open {
            border-color: #20c997;
        }

        .chart-box.request-open {
            border-color: #6610f2;
        }

        .chart-box.development-open {
            border-color: #d63384;
        }

        .table-title {
            font-size: 18px;
            font-weight: bold;
            margin: 30px 0 15px 0;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background-color: #2c3e50;
            color: white;
        }

        table th {
            padding: 12px 8px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid #dee2e6;
        }

        table td {
            padding: 10px 8px;
            font-size: 12px;
            border: 1px solid #dee2e6;
            text-align: center;
            vertical-align: middle;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tfoot {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }

        table tfoot td {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .no-print {
            margin: 20px 0;
            text-align: center;
        }

        .btn-print {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn-print:hover {
            background-color: #0056b3;
        }

        .btn-close-page {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-close-page:hover {
            background-color: #545b62;
        }

        @media print {
            .no-print {
                display: none;
            }

            .chart-box {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .print-page {
                page-break-after: always;
                break-after: page;
            }

            .print-page:last-of-type {
    page-break-after: auto;
    break-after: auto;
}

            .chart-row {
                page-break-inside: avoid;
                gap: 10px;
            }

            .chart-wrapper {
                height: 180px;
                overflow: hidden;
            }
        }

        @page {
            size: A4 landscape;
            margin: 10mm;
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Print / Save as PDF</button>
        <button onclick="window.close()" class="btn-close-page">✖ Close</button>
    </div>

    <!-- Header -->
    <!-- HALAMAN 1: Header + 4 chart total -->
    <div class="print-page">
        <div class="header">
            <h1>YEARLY REPORT - HELPDESK TICKETS</h1>
            <p><strong><?= $client_info->name_app ?></strong></p>
            <p>Periode: Januari - <?= date('F Y', strtotime($date_to)) ?> (<?= $year ?>)</p>
        </div>

        <div class="chart-container">
            <div class="chart-row">
                <div class="chart-box bugs">
                    <h3>Total Bugs & Error</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartBugs"></canvas>
                    </div>
                </div>
                <div class="chart-box issues">
                    <h3>Total User Issues</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartIssues"></canvas>
                    </div>
                </div>
            </div>
            <div class="chart-row">
                <div class="chart-box request">
                    <h3>Total Request</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartRequest"></canvas>
                    </div>
                </div>
                <div class="chart-box development">
                    <h3>Total Development</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartDevelopment"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HALAMAN 2: 4 chart Open -->
    <div class="print-page">
        <div class="chart-container">
            <div class="chart-row">
                <div class="chart-box bugs-open">
                    <h3>Bugs & Error (Open)</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartBugsOpen"></canvas>
                    </div>
                </div>
                <div class="chart-box issues-open">
                    <h3>User Issues (Open)</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartIssuesOpen"></canvas>
                    </div>
                </div>
            </div>
            <div class="chart-row">
                <div class="chart-box request-open">
                    <h3>Request (Open)</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartRequestOpen"></canvas>
                    </div>
                </div>
                <div class="chart-box development-open">
                    <h3>Development (Open)</h3>
                    <div class="chart-wrapper">
                        <canvas id="chartDevelopmentOpen"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HALAMAN 3: Table Summary -->
    <div class="print-page">
        <h2 class="table-title">Summary Per Bulan</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Bulan</th>
                    <th style="width: 14%;">Bugs & Error</th>
                    <th style="width: 14%;">User Issues</th>
                    <th style="width: 14%;">Request</th>
                    <th style="width: 14%;">Development</th>
                    <th style="width: 14%;">Total</th>
                    <th style="width: 15%;">Open (Semua Kategori)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_bugs        = 0;
                $grand_issues      = 0;
                $grand_request     = 0;
                $grand_development = 0;
                $grand_total       = 0;
                $grand_open        = 0;

                foreach ($months as $m):
                    $month_open = $m['bugs_open'] + $m['issues_open'] + $m['request_open'] + $m['development_open'];

                    $grand_bugs        += $m['bugs'];
                    $grand_issues      += $m['issues'];
                    $grand_request     += $m['request'];
                    $grand_development += $m['development'];
                    $grand_total       += $m['total'];
                    $grand_open        += $month_open;
                ?>
                    <tr>
                        <td><?= $m['month_label'] ?></td>
                        <td><strong><?= $m['bugs'] ?></strong></td>
                        <td><strong><?= $m['issues'] ?></strong></td>
                        <td><strong><?= $m['request'] ?></strong></td>
                        <td><strong><?= $m['development'] ?></strong></td>
                        <td><strong><?= $m['total'] ?></strong></td>
                        <td><?= $month_open ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td><?= $grand_bugs ?></td>
                    <td><?= $grand_issues ?></td>
                    <td><?= $grand_request ?></td>
                    <td><?= $grand_development ?></td>
                    <td><?= $grand_total ?></td>
                    <td><?= $grand_open ?></td>
                </tr>
            </tfoot>
        </table>


        <!-- Footer -->
        <div class="footer">
            <p>Generated on <?= date('d F Y H:i:s') ?></p>
            <p>&copy; <?= date('Y') ?> <?= $client_info->name_app ?> - Helpdesk System</p>
        </div>
    </div>

    <script>
        // Data bulanan sudah di-precompute di controller (per bulan, urut Jan..bulan berjalan/Des)
        const months = <?= json_encode($months) ?>;
        const monthLabels = months.map(m => m.month_label);

        function seriesOf(key) {
            return months.map(m => parseInt(m[key]) || 0);
        }

        const chartConfig = {
            type: 'line',
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                layout: {
                    padding: {
                        top: 5
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 4,
                        clip: false,
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: value => value > 0 ? value : ''
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 9
                            },
                            maxRotation: 0,
                            autoSkip: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grace: '20%',
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 10
                            },
                            callback: v => Number.isInteger(v) ? v : ''
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        };

        function makeLineChart(canvasId, label, data, color, bg) {
            new Chart(document.getElementById(canvasId), {
                ...chartConfig,
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        backgroundColor: bg,
                        borderwidth: 3,
                        tension: 0.1,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: color,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3
                    }]
                }
            });
        }

        makeLineChart('chartBugs', 'Bugs', seriesOf('bugs'), '#dc3545', 'rgba(220,53,69,0.1)');
        makeLineChart('chartIssues', 'Issues', seriesOf('issues'), '#ffc107', 'rgba(255,193,7,0.1)');
        makeLineChart('chartRequest', 'Request', seriesOf('request'), '#0dcaf0', 'rgba(13,202,240,0.1)');
        makeLineChart('chartDevelopment', 'Development', seriesOf('development'), '#198754', 'rgba(25,135,84,0.1)');
        makeLineChart('chartBugsOpen', 'Open', seriesOf('bugs_open'), '#fd7e14', 'rgba(253,126,20,0.1)');
        makeLineChart('chartIssuesOpen', 'Open', seriesOf('issues_open'), '#20c997', 'rgba(32,201,151,0.1)');
        makeLineChart('chartRequestOpen', 'Open', seriesOf('request_open'), '#6610f2', 'rgba(102,16,242,0.1)');
        makeLineChart('chartDevelopmentOpen', 'Open', seriesOf('development_open'), '#d63384', 'rgba(214,51,132,0.1)');

        window.addEventListener('load', function() {
            setTimeout(() => window.print(), 500);
        });
    </script>
</body>

</html>