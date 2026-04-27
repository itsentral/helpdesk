<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $formattedFrom = date('d M Y', strtotime($date_from));
    $formattedTo   = date('d M Y', strtotime($date_to));
    $monthYear     = date('F Y', strtotime($date_from));
    ?>
    <title>Monthly Report - <?= $client_info->name_app ?> (<?= $monthYear ?>)</title>

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
            height: 250px;
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

        .chart-box.bugs-open {
            border-color: #fd7e14;
        }

        .chart-box.issues-open {
            border-color: #20c997;
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

            .chart-row {
                page-break-inside: avoid;
                gap: 10px;
            }

            .chart-wrapper {
                height: 200px;
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
    <div class="header">
        <h1>MONTHLY REPORT - HELPDESK TICKETS</h1>
        <p><strong><?= $client_info->name_app ?></strong></p>
        <p>Periode: <?= $monthYear ?></p>
    </div>

    <!-- Summary Cards -->
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
    </div>

    <!-- Weekly Summary Table -->
    <h2 class="table-title">Summary Per Minggu</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">Minggu</th>
                <th style="width: 25%;">Periode</th>
                <th style="width: 20%;">Bugs & Error</th>
                <th style="width: 20%;">User Issues</th>
                <th style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $grand_bugs   = 0;
            $grand_issues = 0;
            $grand_total  = 0;

            foreach ($weeks as $week):
                $grand_bugs   += $week['bugs'];
                $grand_issues += $week['issues'];
                $grand_total  += $week['total'];
            ?>
                <tr>
                    <td>Week <?= $week['week_num'] ?></td>
                    <td>
                        <?= date('d M Y', strtotime($week['date_start'])) ?>
                        &nbsp;-&nbsp;
                        <?= date('d M Y', strtotime($week['date_end'])) ?>
                    </td>
                    <td><strong><?= $week['bugs'] ?></strong></td>
                    <td><strong><?= $week['issues'] ?></strong></td>
                    <td><strong><?= $week['total'] ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td><?= $grand_bugs ?></td>
                <td><?= $grand_issues ?></td>
                <td><?= $grand_total ?></td>
            </tr>
        </tfoot>
    </table>

    <!-- Man Hour Chart -->
    <h2 class="table-title">Man Hour Plan vs Actual Per Minggu</h2>
    
    <div class="chart-container">
        <div class="chart-row" style="grid-template-columns: 1fr;">
            <div class="chart-box" style="border-color: #6f42c1;">
                <h3>Man Hour Plan vs Actual per Week</h3>
                <div class="chart-wrapper" style="height: 280px;">
                    <canvas id="chartManHour"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Man Hour Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">Minggu</th>
                <th style="width: 25%;">Periode</th>
                <th style="width: 20%;">Man Hour Plan</th>
                <th style="width: 20%;">Man Hour Actual</th>
                <th style="width: 20%;">Selisih</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $grand_mh_plan   = 0;
            $grand_mh_actual = 0;

            foreach ($weeks as $week):
                $grand_mh_plan   += $week['man_hour_plan'];
                $grand_mh_actual += $week['man_hour_actual'];
                $selisih = $week['man_hour_actual'] - $week['man_hour_plan'];
                $status_color = $selisih > 0 ? 'color:#dc3545;' : ($selisih < 0 ? 'color:#28a745;' : '');
                // $status_label = $selisih > 0 ? 'Over' : ($selisih < 0 ? 'Under' : 'On Track');
                $status_label = ($week['man_hour_plan'] == 0 && $week['man_hour_actual'] == 0)
                    ? '-'
                    : ($selisih > 0 ? 'Over' : ($selisih < 0 ? 'Under' : 'On Track'));
            ?>
                <tr>
                    <td>Week <?= $week['week_num'] ?></td>
                    <td>
                        <?= date('d M Y', strtotime($week['date_start'])) ?>
                        &nbsp;-&nbsp;
                        <?= date('d M Y', strtotime($week['date_end'])) ?>
                    </td>
                    <td><strong><?= number_format($week['man_hour_plan'], 1) ?></strong></td>
                    <td><strong><?= number_format($week['man_hour_actual'], 1) ?></strong></td>
                    <td style="<?= $status_color ?>"><strong><?= ($selisih >= 0 ? '+' : '') . number_format($selisih, 1) ?></strong></td>
                    <td style="<?= $status_color ?>"><strong><?= $status_label ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        </tbody>
        <tfoot>
            <?php
            $grand_selisih = $grand_mh_actual - $grand_mh_plan;
            $week_count    = count($weeks);
            $avg_plan      = $week_count > 0 ? $grand_mh_plan   / $week_count : 0;
            $avg_actual    = $week_count > 0 ? $grand_mh_actual / $week_count : 0;
            $avg_selisih   = $avg_actual - $avg_plan;
            ?>
            <tr style="background-color: #6D8196;">
                <td colspan="2">Average</td>
                <td><?= number_format($avg_plan, 1) ?></td>
                <td><?= number_format($avg_actual, 1) ?></td>
                <td><?= ($avg_selisih >= 0 ? '+' : '') . number_format($avg_selisih, 1) ?></td>
                <td><?= $avg_selisih > 0 ? 'Over' : ($avg_selisih < 0 ? 'Under' : 'On Track') ?></td>
            </tr>
            <tr>
                <td colspan="2">Total</td>
                <td><?= number_format($grand_mh_plan, 1) ?></td>
                <td><?= number_format($grand_mh_actual, 1) ?></td>
                <td><?= ($grand_selisih >= 0 ? '+' : '') . number_format($grand_selisih, 1) ?></td>
                <td><?= ($grand_mh_plan == 0 && $grand_mh_actual == 0) ? '-' : ($grand_selisih > 0 ? 'Over' : ($grand_selisih < 0 ? 'Under' : 'On Track')) ?></td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Generated on <?= date('d F Y H:i:s') ?></p>
        <p>&copy; <?= date('Y') ?> <?= $client_info->name_app ?> - Helpdesk System</p>
    </div>

    <script>
        const dailyData = <?= json_encode($daily_data) ?>;
        const dateFrom = '<?= $date_from ?>';
        const dateTo = '<?= $date_to ?>';

        function formatDateWithMonthName(dateString) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const date = new Date(dateString);
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        }

        function generateDateRange(startDate, endDate) {
            const dates = [];
            const cur = new Date(startDate);
            const last = new Date(endDate);
            while (cur <= last) {
                const y = cur.getFullYear();
                const m = String(cur.getMonth() + 1).padStart(2, '0');
                const d = String(cur.getDate()).padStart(2, '0');
                dates.push(`${y}-${m}-${d}`);
                cur.setDate(cur.getDate() + 1);
            }
            return dates;
        }

        const bugsMap = {};
        const bugsOpenMap = {};
        const issuesMap = {};
        const issuesOpenMap = {};

        dailyData.bugs.forEach(i => {
            bugsMap[i.date] = parseInt(i.total);
        });
        dailyData.bugs_open.forEach(i => {
            bugsOpenMap[i.date] = parseInt(i.total);
        });
        dailyData.issues.forEach(i => {
            issuesMap[i.date] = parseInt(i.total);
        });
        dailyData.issues_open.forEach(i => {
            issuesOpenMap[i.date] = parseInt(i.total);
        });

        // Build weekly labels & aggregated data for chart
        const allDates = generateDateRange(dateFrom, dateTo);
        const weeks = [];
        const weekLabels = [];

        for (let i = 0; i < allDates.length; i += 7) {
            const chunk = allDates.slice(i, i + 7);
            weekLabels.push(`Week ${weeks.length + 1}\n(${formatDateWithMonthName(chunk[0])} - ${formatDateWithMonthName(chunk[chunk.length - 1])})`);
            weeks.push(chunk);
        }

        function sumWeeks(map) {
            return weeks.map(chunk => chunk.reduce((s, d) => s + (map[d] || 0), 0));
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

        new Chart(document.getElementById('chartBugs'), {
            ...chartConfig,
            data: {
                labels: weekLabels,
                datasets: [{
                    label: 'Bugs',
                    data: sumWeeks(bugsMap),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220,53,69,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            }
        });

        new Chart(document.getElementById('chartIssues'), {
            ...chartConfig,
            data: {
                labels: weekLabels,
                datasets: [{
                    label: 'Issues',
                    data: sumWeeks(issuesMap),
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255,193,7,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffc107',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            }
        });

        new Chart(document.getElementById('chartBugsOpen'), {
            ...chartConfig,
            data: {
                labels: weekLabels,
                datasets: [{
                    label: 'Open',
                    data: sumWeeks(bugsOpenMap),
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253,126,20,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#fd7e14',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            }
        });

        new Chart(document.getElementById('chartIssuesOpen'), {
            ...chartConfig,
            data: {
                labels: weekLabels,
                datasets: [{
                    label: 'Open',
                    data: sumWeeks(issuesOpenMap),
                    borderColor: '#20c997',
                    backgroundColor: 'rgba(32,201,151,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#20c997',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            }
        });

        // Man Hour data dari PHP
        const manhourData = <?= json_encode($manhour_data) ?>;

        const mhPlanMap = {};
        const mhActualMap = {};

        manhourData.plan.forEach(i => {
            mhPlanMap[i.date] = parseFloat(i.total);
        });
        manhourData.actual.forEach(i => {
            mhActualMap[i.date] = parseFloat(i.total);
        });

        function sumWeeksFloat(map) {
            return weeks.map(chunk => parseFloat(
                chunk.reduce((s, d) => s + (map[d] || 0), 0).toFixed(1)
            ));
        }

        new Chart(document.getElementById('chartManHour'), {
            type: 'bar',
            data: {
                labels: weekLabels,
                datasets: [{
                        label: 'Plan',
                        data: sumWeeksFloat(mhPlanMap),
                        backgroundColor: 'rgba(111,66,193,0.6)',
                        borderColor: '#6f42c1',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                    {
                        label: 'Actual',
                        data: sumWeeksFloat(mhActualMap),
                        backgroundColor: 'rgba(32,201,151,0.6)',
                        borderColor: '#20c997',
                        borderWidth: 2,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 2,
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
                            maxRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grace: '15%',
                        ticks: {
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        window.addEventListener('load', function() {
            setTimeout(() => window.print(), 500);
        });
    </script>
</body>

</html>