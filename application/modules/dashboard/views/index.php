<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div class="card">
    <div class="card-body">
        <h4> <i>Selamat datang!</i></h4>
        <!-- Filter Section -->
        <!-- <div class="row mb-4">
            <?php if ($client_count > 1): ?>
                <div class="col-md-4">
                    <label class="form-label">Pilih Client</label>
                    <select class="form-select" id="client_id" required>
                        <option value="">-- Pilih Client --</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client->client_id ?>"><?= $client->name_app ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <input type="hidden" id="client_id" value="<?= $clients[0]->client_id ?>">
            <?php endif; ?>

            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="text" class="form-control" id="date_from" placeholder="Pilih tanggal">
            </div>

            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="text" class="form-control" id="date_to" placeholder="Pilih tanggal">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-primary" id="btn_filter">
                    <i class="ti ti-filter"></i> Filter Data
                </button>
            </div>
        </div> -->
    </div>
</div>

<!-- Dashboard Content -->
<div id="dashboard_content" style="display: none;">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Tickets</h5>
                    <h2 id="total_tickets">0</h2>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Done</h5>
                    <h2 id="done_tickets">0</h2>
                </div>
            </div>
        </div> -->
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Process</h5>
                    <h2 id="process_tickets">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Open</h5>
                    <h2 id="open_tickets">0</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Line Only -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Tickets Per Day</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Chart.js Data Labels Plugin -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    let dailyChart;

    // Initialize Flatpickr
    flatpickr("#date_from", {
        dateFormat: "Y-m-d",
        defaultDate: new Date(new Date().setDate(new Date().getDate() - 30))
    });

    flatpickr("#date_to", {
        dateFormat: "Y-m-d",
        defaultDate: new Date()
    });

    // Filter Button Click
    $('#btn_filter').click(function() {
        const client_id = $('#client_id').val();
        const date_from = $('#date_from').val();
        const date_to = $('#date_to').val();

        if (!client_id) {
            alert('Pilih client terlebih dahulu');
            return;
        }

        if (!date_from || !date_to) {
            alert('Pilih range tanggal terlebih dahulu');
            return;
        }
        loadDashboard(client_id, date_from, date_to);
    });

    function loadDashboard(client_id, date_from, date_to) {
        $.ajax({
            url: siteurl + 'dashboard' + '/get_dashboard_data',
            type: 'POST',
            data: {
                client_id: client_id,
                date_from: date_from,
                date_to: date_to
            },
            dataType: 'json',
            success: function(response) {
                $('#dashboard_content').show();

                // Update summary cards
                $('#total_tickets').text(response.total_tickets);

                const statusMap = {
                    0: 'open',
                    1: 'process',
                    2: 'pending',
                    3: 'cancel',
                    4: 'done',
                    5: 'close',
                    6: 'revisi'
                };
                let statusCounts = {
                    open: 0,
                    process: 0,
                    // done: 0
                };

                response.status_data.forEach(item => {
                    const statusName = statusMap[item.status];
                    if (statusName === 'open') statusCounts.open = item.total;
                    if (statusName === 'process') statusCounts.process = item.total;
                    // if (statusName === 'done') statusCounts.done = item.total;
                });

                $('#open_tickets').text(statusCounts.open);
                $('#process_tickets').text(statusCounts.process);
                // $('#done_tickets').text(statusCounts.done);

                // Render Chart Line Only
                renderDailyChart(response.daily_data);
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan saat memuat data');
                console.error(error);
            }
        });
    }

    function renderDailyChart(data) {
        const labels = data.map(item => item.date);
        const values = data.map(item => parseInt(item.total));

        if (dailyChart) dailyChart.destroy();

        const ctx = document.getElementById('dailyChart').getContext('2d');
        dailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Tickets',
                    data: values,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                return 'Tickets: ' + context.parsed.y;
                            }
                        }
                    },
                    datalabels: {
                        display: true,
                        align: 'top',
                        anchor: 'end',
                        color: '#007bff',
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: function(value) {
                            return value;
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Tickets',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
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
        });
    }
</script>