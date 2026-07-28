<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $formattedFrom = date('d M Y', strtotime($date_from));
    $formattedTo   = date('d M Y', strtotime($date_to));
    ?>

    <title>
        Weekly Report - <?= $client_info->name_app ?>
        (<?= $formattedFrom ?> - <?= $formattedTo ?>)
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
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
            max-width: 100%;
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
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #dee2e6;
        }

        table td {
            padding: 10px 8px;
            font-size: 11px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tbody tr:hover {
            background-color: #e9ecef;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
        }

        .status-open {
            background-color: #17a2b8;
            color: white;
        }

        .status-process {
            background-color: #007bff;
            color: white;
        }

        .status-pending {
            background-color: #ffc107;
            color: #333;
        }

        .status-cancel {
            background-color: #6c757d;
            color: white;
        }

        .status-done {
            background-color: #28a745;
            color: white;
        }

        .status-close {
            background-color: #343a40;
            color: white;
        }

        .status-revisi {
            background-color: #dc3545;
            color: white;
        }

        .text-center {
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

        .btn-close {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-close:hover {
            background-color: #545b62;
        }

        @media print {
            body {
                padding: 10px;
            }

            .no-print {
                display: none;
            }

            .chart-box {
                break-inside: avoid;
                page-break-inside: avoid;
                padding: 10px;
                overflow: hidden;
            }

            .chart-row {
                page-break-inside: avoid;
                gap: 10px;
            }

            .chart-wrapper {
                height: 200px;
                width: 100%;
                overflow: hidden;
            }

            .chart-wrapper canvas {
                max-width: 100% !important;
                max-height: 200px !important;
            }

            table {
                page-break-inside: auto;
            }

            table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        @page {
            size: A4 landscape;
            margin: 10mm;
        }
    </style>
</head>

<body>
    <?php
    $open_extended_js = json_encode($open_extended);
    ?>

    <!-- Print/Close Buttons -->
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Print / Save as PDF
        </button>
        <button onclick="window.close()" class="btn-close">
            ✖ Close
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>WEEKLY REPORT - HELPDESK TICKETS</h1>
        <p><strong><?= $client_info->name_app ?></strong></p>
        <p>Periode: <?= date('d F Y', strtotime($date_from)) ?> - <?= date('d F Y', strtotime($date_to)) ?></p>
    </div>

    <!-- Chart Summary -->
    <div class="chart-container">
        <!-- Row 1 -->
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

        <!-- Row 2 -->
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

    <!-- Detail Table -->
    <h2 class="table-title">Detail Tickets</h2>

    <?php
    // Tandai tiket carry over
    $carry_over_ids = [];
    foreach ($open_carry_over as $t) {
        $carry_over_ids[$t->id] = true;
    }

    // Gabungkan dan sort by create_date ASC
    $merged_tickets = array_merge((array)$open_carry_over, (array)$all_tickets);
    usort($merged_tickets, function ($a, $b) {
        return strtotime($a->create_date) - strtotime($b->create_date);
    });
    ?>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 3%;">No</th>
                <th class="text-center" style="width: 5%;">No Ticket</th>
                <th class="text-center" style="width: 9%;">Report Date</th>
                <th style="width: 10%;">Report By</th>
                <th style="width: 15%;">Report</th>
                <th style="width: 15%;">Causes</th>
                <th style="width: 13%;">Action Plan</th>
                <th style="width: 9%;">PIC</th>
                <th style="width: 9%;">Approval By</th>
                <th class="text-center" style="width: 8%;">Approval Date</th>
                <th class="text-center" style="width: 7%;">Over Due</th>
                <th class="text-center" style="width: 5%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($merged_tickets)): ?>
                <tr>
                    <td colspan="10" class="text-center">No data available</td>
                </tr>
            <?php else: ?>
                <?php
                $no = 1;
                foreach ($merged_tickets as $ticket):
                    $is_carry_over = isset($carry_over_ids[$ticket->id]);
                    $days_open = (int) floor((time() - strtotime($ticket->create_date)) / 86400);

                    // Row style
                    if ($is_carry_over) {
                        if ($days_open >= 14) {
                            $row_style = 'background-color: #ffe0e0;'; // merah - kritis
                        } elseif ($days_open >= 7) {
                            $row_style = 'background-color: #fff3cd;'; // kuning - perhatian
                        } else {
                            $row_style = 'background-color: #fff0e6;'; // oranye muda
                        }
                    } else {
                        $row_style = '';
                    }

                    $status_map = [
                        0 => ['label' => 'Open',    'class' => 'status-open'],
                        1 => ['label' => 'Process',  'class' => 'status-process'],
                        2 => ['label' => 'Pending',  'class' => 'status-pending'],
                        3 => ['label' => 'Cancel',   'class' => 'status-cancel'],
                        4 => ['label' => 'Done',     'class' => 'status-done'],
                        5 => ['label' => 'Close',    'class' => 'status-close'],
                        6 => ['label' => 'Revisi',   'class' => 'status-revisi'],
                    ];
                    $status_info = $status_map[$ticket->status] ?? ['label' => 'Unknown', 'class' => ''];

                    $subName = strtolower($ticket->sub_category_name ?? '');
                    $badgeClass = 'bg-secondary';
                    if (strpos($subName, 'bugs konsep') !== false) {
                        $badgeClass = 'bg-danger';
                    } elseif (strpos($subName, 'bugs program') !== false) {
                        $badgeClass = 'bg-warning text-dark';
                    } elseif (strpos($subName, 'user issue') !== false) {
                        $badgeClass = 'bg-info text-dark';
                    }

                    // Warna label hari open
                    if ($days_open >= 14) {
                        $days_color = '#dc3545';
                    } elseif ($days_open >= 7) {
                        $days_color = '#fd7e14';
                    } else {
                        $days_color = '#6c757d';
                    }
                ?>
                    <tr style="<?= $row_style ?>">
                        <td class="text-center"><?= $no++ ?></td>
                        <td><strong><?= $ticket->no_ticket ?></strong></td>
                        <td class="text-center">
                            <?= date('d/m/Y H:i', strtotime($ticket->create_date)) ?>
                            <div style="margin-top:4px; display:flex; flex-direction:column; gap:3px; align-items:center;">
                                <span class="badge <?= $badgeClass ?>">
                                    <?= htmlspecialchars($ticket->sub_category_name) ?>
                                </span>
                                <?php if ($is_carry_over): ?>
                                    <span class="badge bg-dark" style="font-size:9px;">⏳ Carry Over</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?= $ticket->create_by ?></td>
                        <td><?= $ticket->report ?></td>
                        <td><?= $ticket->causes ?: '-' ?></td>
                        <td><?= $ticket->action_plan ?: '-' ?></td>
                        <td><?= $ticket->pic ?: '-' ?></td>
                        <td><?=
                            $ticket->approval_level == 1
                                ? ($ticket->approval_by ?: '-')
                                : ($ticket->approval_level == 2
                                    ? ($ticket->create_by ?: '-')
                                    : '-')
                            ?></td>
                        <td class="text-center">
                            <?= (
                                isset($ticket->current_approval_level, $ticket->approval_level) &&
                                $ticket->current_approval_level == $ticket->approval_level &&
                                !empty($ticket->approval_date)
                            )
                                ? date('d/m/Y', strtotime($ticket->approval_date))
                                : '-' ?>
                        </td>
                        <td class="text-center">
                            <?php if ($is_carry_over): ?>
                                <strong style="color: <?= $days_color ?>;">
                                    <?= $days_open ?> hari
                                </strong>
                            <?php else: ?>
                                <span style="color:#ccc;">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="status-badge <?= $status_info['class'] ?>">
                                <?= $status_info['label'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Legend -->
    <div style="font-size:11px; color:#555; margin-top:-10px; margin-bottom:20px; display:flex; gap:20px;">
        <span>⏳ <strong>Carry Over</strong> = tiket open dari sebelum periode ini</span>
        <span style="color:#dc3545;">■</span> ≥ 14 hari (Kritis)
        <span style="color:#fd7e14;">■</span> 7–13 hari (Perhatian)
        <!-- <span style="color:#6c757d;">■</span> &lt; 2 hari (Normal) -->
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Generated on <?= date('d F Y H:i:s') ?></p>
        <p>&copy; <?= date('Y') ?> <?= $client_info->name_app ?> - Helpdesk System</p>
    </div>

    <script>
        // Prepare data from PHP
        const openExtended = <?= $open_extended_js ?>;
        const extendedFrom = openExtended.extended_from;
        const bugsOpenExtMap = {};
        const issuesOpenExtMap = {};

        const dailyData = <?= json_encode($daily_data) ?>;
        const dateFrom = '<?= $date_from ?>';
        const dateTo = '<?= $date_to ?>';

        openExtended.bugs_open.forEach(item => {
            bugsOpenExtMap[item.date] = parseInt(item.total);
        });
        openExtended.issues_open.forEach(item => {
            issuesOpenExtMap[item.date] = parseInt(item.total);
        });

        function buildCumulative(map, fullRange) {
            let cumulative = 0;
            const result = {};
            fullRange.forEach(date => {
                cumulative += (map[date] || 0);
                result[date] = cumulative;
            });
            return result;
        }

        // Generate full range: extended_from sampai date_to
        const fullRange = generateDateRange(extendedFrom, dateTo);

        // Helper function to format date with month name
        function formatDateWithMonthName(dateString) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const date = new Date(dateString);
            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            return `${day} ${month} ${year}`;
        }

        // Function to generate all dates between dateFrom and dateTo
        function generateDateRange(startDate, endDate) {
            const dates = [];
            const currentDate = new Date(startDate);
            const lastDate = new Date(endDate);

            while (currentDate <= lastDate) {
                // Format: YYYY-MM-DD
                const year = currentDate.getFullYear();
                const month = String(currentDate.getMonth() + 1).padStart(2, '0');
                const day = String(currentDate.getDate()).padStart(2, '0');
                dates.push(`${year}-${month}-${day}`);

                // Next day
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return dates;
        }

        // Create date maps
        const bugsMap = {};
        const bugsOpenMap = {};
        const issuesMap = {};
        const issuesOpenMap = {};
        const chartInstances = [];

        dailyData.bugs.forEach(item => {
            bugsMap[item.date] = parseInt(item.total);
        });

        dailyData.bugs_open.forEach(item => {
            bugsOpenMap[item.date] = parseInt(item.total);
        });

        dailyData.issues.forEach(item => {
            issuesMap[item.date] = parseInt(item.total);
        });

        dailyData.issues_open.forEach(item => {
            issuesOpenMap[item.date] = parseInt(item.total);
        });


        // Get all unique dates
        const allDates = new Set([
            ...dailyData.bugs.map(item => item.date),
            ...dailyData.bugs_open.map(item => item.date),
            ...dailyData.issues.map(item => item.date),
            ...dailyData.issues_open.map(item => item.date)
        ]);

        const labels = generateDateRange(dateFrom, dateTo);
        const formattedLabels = labels.map(date => formatDateWithMonthName(date));
        const bugsCumulative = buildCumulative(bugsOpenExtMap, fullRange);
        const issuesCumulative = buildCumulative(issuesOpenExtMap, fullRange);

        // Chart configuration
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
                        color: function(context) {
                            return context.dataset.borderColor;
                        },
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: function(value) {
                            return value > 0 ? value : '';
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
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
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        };

        // Chart 1: Total Bugs & Error
        const chartBugs = new Chart(document.getElementById('chartBugs'), {
            ...chartConfig,
            data: {
                labels: formattedLabels,
                datasets: [{
                    label: 'Total',
                    data: labels.map(date => bugsMap[date] || 0),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderwidth: 3,
                    tension: 0.1,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3
                }]
            }
        });
        chartInstances.push(chartBugs);

        // Chart 2: Total User Issues
        const chartIssues = new Chart(document.getElementById('chartIssues'), {
            ...chartConfig,
            data: {
                labels: formattedLabels,
                datasets: [{
                    label: 'Total',
                    data: labels.map(date => issuesMap[date] || 0),
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    borderwidth: 3,
                    tension: 0.1,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffc107',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3
                }]
            }
        });
        chartInstances.push(chartIssues);

        // Chart 3: Bugs & Error (Open)
        const chartBugsOpen = new Chart(document.getElementById('chartBugsOpen'), {
            ...chartConfig,
            data: {
                labels: formattedLabels,
                datasets: [{
                    label: 'Open',
                    data: labels.map(date => bugsCumulative[date] || 0),
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253, 126, 20, 0.1)',
                    borderwidth: 3,
                    tension: 0.1,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#fd7e14',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3
                }]
            }
        });
        chartInstances.push(chartBugsOpen);

        // Chart 4: User Issues (Open)
        const chartIssuesOpen = new Chart(document.getElementById('chartIssuesOpen'), {
            ...chartConfig,
            data: {
                labels: formattedLabels,
                datasets: [{
                    label: 'Open',
                    data: labels.map(date => issuesCumulative[date] || 0),
                    borderColor: '#20c997',
                    backgroundColor: 'rgba(32, 201, 151, 0.1)',
                    borderwidth: 3,
                    tension: 0.1,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#20c997',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3
                }]
            }
        });
        chartInstances.push(chartIssuesOpen);

        window.addEventListener('load', function() {
            setTimeout(() => window.print(), 500);
        });
    </script>
</body>

</html>