<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    /* Loading Skeleton Styles */
    .skeleton {
        animation: skeleton-loading 1s linear infinite alternate;
    }

    @keyframes skeleton-loading {
        0% {
            background-color: hsl(200, 20%, 80%);
        }

        100% {
            background-color: hsl(200, 20%, 95%);
        }
    }

    .skeleton-card {
        height: 120px;
        border-radius: 8px;
    }

    .skeleton-chart {
        height: 400px;
        border-radius: 8px;
    }

    .skeleton-table {
        height: 50px;
        margin-bottom: 10px;
        border-radius: 4px;
    }

    .stats-card {
        border-radius: 12px;
        border: none !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .stats-card .card-body {
        padding: 1.5rem;
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 1rem;
    }

    .stats-title {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        opacity: 0.8;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .stats-subtitle {
        font-size: 0.813rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        opacity: 0.9;
    }

    .card-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
    }

    .card-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        color: white !important;
    }

    .card-warning {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%) !important;
        color: #333 !important;
    }

    .icon-primary {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .icon-danger {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .icon-warning {
        background-color: rgba(0, 0, 0, 0.1) !important;
        color: #333 !important;
    }

    .card-warning .stats-subtitle {
        border-top-color: rgba(0, 0, 0, 0.1) !important;
    }
</style>

<div class="card">
    <div class="card-body">
        <!-- Filter Section -->
        <div class="row mb-4">
            <?php if ($client_count > 1): ?>
                <div class="col-md-3">
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

            <div class="col-md-3">
                <label class="form-label">Tipe Report</label>
                <select class="form-select" id="report_type" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="weekly">Mingguan</option>
                    <option value="monthly">Bulanan</option>
                </select>
            </div>

            <!-- Filter Bulanan -->
            <div class="col-md-3" id="monthly_filter" style="display: none;">
                <label class="form-label">Pilih Bulan</label>
                <input type="text" class="form-control" id="date_monthly" placeholder="Pilih bulan dan tahun">
            </div>

            <!-- Filter Mingguan -->
            <div class="col-md-3" id="weekly_filter" style="display: none;">
                <label class="form-label">Pilih Range</label>
                <input type="text" class="form-control" id="date_weekly" placeholder="Pilih tanggal mulai">
            </div>

            <!-- Filter Kategori Chart -->
            <div class="col-md-3" id="chart_category_filter" style="display: none;">
                <label class="form-label">Kategori Chart</label>
                <select class="form-select" id="chart_category">
                    <option value="all">All Categories</option>
                    <option value="bugs">Bugs & Error Only</option>
                    <option value="issues">Issues Only</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-primary" id="btn_filter">
                    <i class="ti ti-filter"></i> Filter Data
                </button>
                <?php if ($can_export == 1): ?>
                    <button type="button" class="btn btn-success" id="btn_export_pdf" style="display: none;">
                        <i class="ti ti-file-export"></i> Export PDF
                    </button>
                    <button type="button" class="btn btn-warning" id="btn_export_monthly_pdf" style="display: none;">
                        <i class="ti ti-file-export"></i> Export PDF
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div id="dashboard_content" style="display: none;">
    <!-- Loading Skeleton -->
    <div id="loading_skeleton" style="display: none;">
        <!-- Summary Cards Skeleton -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="skeleton skeleton-card"></div>
            </div>
            <div class="col-md-3">
                <div class="skeleton skeleton-card"></div>
            </div>
            <div class="col-md-3">
                <div class="skeleton skeleton-card"></div>
            </div>
            <div class="col-md-3">
                <div class="skeleton skeleton-card"></div>
            </div>
        </div>

        <!-- Category Filter Skeleton -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="skeleton skeleton-table"></div>
            </div>
        </div>

        <!-- Chart Skeleton -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="skeleton skeleton-chart"></div>
            </div>
        </div>

        <!-- Table Skeleton -->
        <div class="row">
            <div class="col-md-12">
                <div class="skeleton skeleton-table"></div>
                <div class="skeleton skeleton-table"></div>
                <div class="skeleton skeleton-table"></div>
                <div class="skeleton skeleton-table"></div>
            </div>
        </div>
    </div>

    <!-- Actual Content -->
    <div id="actual_content">
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="card stats-card card-primary">
                    <div class="card-body">
                        <div class="stats-icon icon-primary">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="stats-title">Total Tickets Bugs & User Issue</div>
                        <h2 class="stats-number" id="total_tickets">0</h2>
                        <div class="stats-subtitle">
                            <i class="fas fa-folder-open"></i>
                            <span id="total_open">0</span> Open Tickets
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stats-card card-danger">
                    <div class="card-body">
                        <div class="stats-icon icon-danger">
                            <i class="fas fa-bug"></i>
                        </div>
                        <div class="stats-title">Bugs & Error</div>
                        <h2 class="stats-number" id="bugs_tickets">0</h2>
                        <div class="stats-subtitle">
                            <i class="fas fa-exclamation-circle"></i>
                            <span id="open_bugs">0</span> Open Bugs
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stats-card card-warning">
                    <div class="card-body">
                        <div class="stats-icon icon-warning">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div class="stats-title">User Issues</div>
                        <h2 class="stats-number" id="issues_tickets">0</h2>
                        <div class="stats-subtitle">
                            <i class="fas fa-hourglass-half"></i>
                            <span id="open_issues">0</span> Open Issues
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Line -->
        <div class="row mb-4">
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

        <!-- Summary Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 id="summary_title">Summary Per Hari</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="summary_table">
                                <thead class="table" id="summary_thead">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th class="text-center">Bugs & Error</th>
                                        <th class="text-center">User Issues</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="summary_tbody">
                                </tbody>
                                <tfoot style="background-color: #C4C4C4;">
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-center" id="total_bugs_summary">0</th>
                                        <th class="text-center" id="total_issues_summary">0</th>
                                        <th class="text-center" id="total_all_summary">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Man Hour Chart (Monthly Only) -->
        <div class="row mb-4" id="manhour_chart_section" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Man Hour Plan vs Actual per Minggu</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="manhourChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Man Hour Table (Monthly Only) -->
        <div class="row mb-4" id="manhour_table_section" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Man Hour Plan vs Actual Per Minggu</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="manhour_table">
                                <thead class="table">
                                    <tr>
                                        <th>Minggu</th>
                                        <th class="text-center">Man Hour Plan</th>
                                        <th class="text-center">Man Hour Actual</th>
                                        <th class="text-center">Selisih</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="manhour_tbody"></tbody>
                                <tfoot id="manhour_tfoot"></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Tickets -->
<div class="modal fade" id="modalTicketDetail" tabindex="-1" aria-labelledby="modalTicketDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTicketDetailLabel">Detail Tickets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalTicketDetailBody" style="max-height: 80vh; overflow: auto;">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Chart.js Data Labels Plugin -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    let dailyChart;
    let chartData = {};
    let monthlyPicker;
    let weeklyPicker;
    let currentReportType = '';
    let manhourChart;
    let manhourData = {};

    // Initialize Flatpickr for Monthly (Month picker)
    monthlyPicker = flatpickr("#date_monthly", {
        dateFormat: "F Y",
        plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "F Y",
                altFormat: "F Y"
            })
        ]
    });

    // Initialize Flatpickr for Weekly (Date range with 7 days limit)
    weeklyPicker = flatpickr("#date_weekly", {
        mode: "range",
        dateFormat: "Y-m-d",
        maxDate: "today",
        locale: {
            firstDayOfWeek: 1
        },
        onClose: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                const start = selectedDates[0];
                const end = selectedDates[1];

                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays > 6) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Range maksimal adalah 7 hari. Silakan pilih ulang.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        instance.clear();
                    });
                }
            }
        }
    });

    // Report type change handler
    $('#report_type').change(function() {
        const reportType = $(this).val();
        $('#monthly_filter').hide();
        $('#weekly_filter').hide();
        $('#btn_export_pdf').hide();

        if (monthlyPicker) monthlyPicker.clear();
        if (weeklyPicker) weeklyPicker.clear();

        if (reportType === 'monthly') {
            $('#monthly_filter').show();
        } else if (reportType === 'weekly') {
            $('#weekly_filter').show();
        }
    });

    // Filter Button Click
    $('#btn_filter').click(function() {
        const client_id = $('#client_id').val();
        const report_type = $('#report_type').val();

        if (!client_id) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Pilih client terlebih dahulu',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (!report_type) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Pilih tipe report terlebih dahulu',
                confirmButtonText: 'OK'
            });
            return;
        }

        let date_from = '';
        let date_to = '';

        if (report_type === 'monthly') {
            const selectedDate = monthlyPicker.selectedDates[0];

            if (!selectedDate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Pilih bulan dan tahun terlebih dahulu',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Get first and last day of selected month
            const year = selectedDate.getFullYear();
            const month = selectedDate.getMonth();

            date_from = formatDate(new Date(year, month, 1));
            date_to = formatDate(new Date(year, month + 1, 0));

        } else if (report_type === 'weekly') {
            const selectedDates = weeklyPicker.selectedDates;

            if (selectedDates.length !== 2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Pilih range tanggal 7 hari terlebih dahulu',
                    confirmButtonText: 'OK'
                });
                return;
            }

            date_from = formatDate(selectedDates[0]);
            date_to = formatDate(selectedDates[1]);
        }

        currentReportType = report_type;
        loadDashboard(client_id, date_from, date_to);
    });

    // Handle click button view detail
    $(document).on('click', '.btn-view-detail', function() {
        const clientId = $(this).data('client-id');
        const date = $(this).data('date');
        const category = $(this).data('category');

        // Show modal
        $('#modalTicketDetail').modal('show');

        // Update modal title
        $('#modalTicketDetailLabel').text('Detail Tickets - ' + date + ' (' + category.toUpperCase() + ')');

        // Show loading
        $('#modalTicketDetailBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);

        $.ajax({
            url: siteurl + 'dashboard/get_ticket_detail',
            type: 'GET',
            data: {
                client_id: clientId,
                date: date,
                category: category
            },
            success: function(response) {
                $('#modalTicketDetailBody').html(response);
                if (!$.fn.DataTable.isDataTable('#ticket_table')) {
                    $('#ticket_table').DataTable({
                        "pageLength": 10,
                        "order": [],
                    });
                }
            },
            error: function() {
                $('#modalTicketDetailBody').html(`
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle"></i> Terjadi kesalahan saat memuat data
                </div>
            `);
            }
        });
    });

    $('#modalTicketDetail').on('hidden.bs.modal', function() {
        if ($.fn.DataTable.isDataTable('#ticket_table')) {
            $('#ticket_table').DataTable().destroy();
        }
    });

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function formatDateWithMonthName(dateString) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const date = new Date(dateString);

        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();

        return `${day} ${month} ${year}`;
    }

    $('#chart_category').change(function() {
        const category = $(this).val();
        updateChart(category);
        updateTable(category);
    });

    function updateTable(category) {
        if (currentReportType === 'monthly') {
            renderMonthlyTable(chartData, category);
        } else {
            renderWeeklyTable(chartData, category);
        }
    }

    function showLoadingSkeleton() {
        $('#loading_skeleton').show();
        $('#actual_content').hide();
    }

    function hideLoadingSkeleton() {
        $('#loading_skeleton').hide();
        $('#actual_content').show();
    }

    $('#report_type').change(function() {
        const reportType = $(this).val();
        $('#monthly_filter').hide();
        $('#weekly_filter').hide();
        $('#btn_export_pdf').hide();

        if (monthlyPicker) monthlyPicker.clear();
        if (weeklyPicker) weeklyPicker.clear();

        if (reportType === 'monthly') {
            $('#monthly_filter').show();
        } else if (reportType === 'weekly') {
            $('#weekly_filter').show();
        }
    });

    function renderManhourChart(data) {
        if (!data || !data.plan || !data.actual) return;

        const mhPlanMap = {};
        const mhActualMap = {};

        data.plan.forEach(i => {
            mhPlanMap[i.date] = parseFloat(i.total);
        });
        data.actual.forEach(i => {
            mhActualMap[i.date] = parseFloat(i.total);
        });

        // Bangun range tanggal full bulan
        const selectedDate = monthlyPicker.selectedDates[0];
        const year = selectedDate.getFullYear();
        const month = selectedDate.getMonth();
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);

        const allDates = [];
        for (let d = new Date(firstDayOfMonth); d <= lastDayOfMonth; d.setDate(d.getDate() + 1)) {
            allDates.push(formatDate(new Date(d)));
        }

        // Kelompokkan per minggu
        const weeks = [];
        const weekLabels = [];

        for (let i = 0; i < allDates.length; i += 7) {
            const chunk = allDates.slice(i, i + 7);
            const startLabel = formatDateWithMonthName(chunk[0]);
            const endLabel = formatDateWithMonthName(chunk[chunk.length - 1]);
            weekLabels.push(`Week ${weeks.length + 1} (${startLabel} - ${endLabel})`);
            weeks.push(chunk);
        }

        function sumWeeksMH(map) {
            return weeks.map(chunk =>
                parseFloat(chunk.reduce((s, d) => s + (map[d] || 0), 0).toFixed(1))
            );
        }

        if (manhourChart) manhourChart.destroy();

        const ctx = document.getElementById('manhourChart').getContext('2d');
        manhourChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: weekLabels,
                datasets: [{
                        label: 'Plan',
                        data: sumWeeksMH(mhPlanMap),
                        backgroundColor: 'rgba(111,66,193,0.6)',
                        borderColor: '#6f42c1',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                    {
                        label: 'Actual',
                        data: sumWeeksMH(mhActualMap),
                        backgroundColor: 'rgba(32,201,151,0.6)',
                        borderColor: '#20c997',
                        borderWidth: 2,
                        borderRadius: 4,
                    }
                ]
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
                        enabled: true
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 2,
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: value => value > 0 ? value : ''
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Minggu',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Man Hour',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        beginAtZero: true,
                        grace: '15%',
                        ticks: {
                            font: {
                                size: 11
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
    }

    function renderManhourTable(data) {
        if (!data || !data.plan || !data.actual) return;

        const mhPlanMap = {};
        const mhActualMap = {};

        data.plan.forEach(i => {
            mhPlanMap[i.date] = parseFloat(i.total);
        });
        data.actual.forEach(i => {
            mhActualMap[i.date] = parseFloat(i.total);
        });

        // Bangun range tanggal full bulan
        const selectedDate = monthlyPicker.selectedDates[0];
        const year = selectedDate.getFullYear();
        const month = selectedDate.getMonth();
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);

        const allDates = [];
        for (let d = new Date(firstDayOfMonth); d <= lastDayOfMonth; d.setDate(d.getDate() + 1)) {
            allDates.push(formatDate(new Date(d)));
        }

        // Kelompokkan per minggu
        const weeks = [];
        for (let i = 0; i < allDates.length; i += 7) {
            weeks.push(allDates.slice(i, i + 7));
        }

        let tbodyHtml = '';
        let grandPlan = 0;
        let grandActual = 0;

        weeks.forEach((chunk, index) => {
            const startLabel = formatDateWithMonthName(chunk[0]);
            const endLabel = formatDateWithMonthName(chunk[chunk.length - 1]);

            const plan = parseFloat(chunk.reduce((s, d) => s + (mhPlanMap[d] || 0), 0).toFixed(1));
            const actual = parseFloat(chunk.reduce((s, d) => s + (mhActualMap[d] || 0), 0).toFixed(1));
            const selisih = parseFloat((actual - plan).toFixed(1));

            grandPlan += plan;
            grandActual += actual;

            const color = selisih > 0 ? 'color:#dc3545;' : (selisih < 0 ? 'color:#28a745;' : '');
            // const label = selisih > 0 ? 'Over' : (selisih < 0 ? 'Under' : 'On Track');
            const label = (plan === 0 && actual === 0) ? '-' : (selisih > 0 ? 'Over' : (selisih < 0 ? 'Under' : 'On Track'));
            const sign = selisih >= 0 ? '+' : '';

            tbodyHtml += `
            <tr>
                <td>Week ${index + 1} (${startLabel} - ${endLabel})</td>
                <td class="text-center"><strong>${plan.toFixed(1)}</strong></td>
                <td class="text-center"><strong>${actual.toFixed(1)}</strong></td>
                <td class="text-center" style="${color}"><strong>${sign}${selisih.toFixed(1)}</strong></td>
                <td class="text-center" style="${color}"><strong>${label}</strong></td>
            </tr>
        `;
        });

        $('#manhour_tbody').html(tbodyHtml);

        // Average & Total di tfoot
        const weekCount = weeks.length;
        const avgPlan = parseFloat((grandPlan / weekCount).toFixed(1));
        const avgActual = parseFloat((grandActual / weekCount).toFixed(1));
        const avgSelisih = parseFloat((avgActual - avgPlan).toFixed(1));
        const grandSelisih = parseFloat((grandActual - grandPlan).toFixed(1));

        const avgColor = avgSelisih > 0 ? 'color:#dc3545;' : (avgSelisih < 0 ? 'color:#28a745;' : '');
        const totalColor = grandSelisih > 0 ? 'color:#dc3545;' : (grandSelisih < 0 ? 'color:#28a745;' : '');

        // const avgLabel = avgSelisih > 0 ? 'Over' : (avgSelisih < 0 ? 'Under' : 'On Track');
        // const totalLabel = grandSelisih > 0 ? 'Over' : (grandSelisih < 0 ? 'Under' : 'On Track');
        const avgLabel = (avgPlan === 0 && avgActual === 0) ? '-' : (avgSelisih > 0 ? 'Over' : (avgSelisih < 0 ? 'Under' : 'On Track'));
        const totalLabel = (grandPlan === 0 && grandActual === 0) ? '-' : (grandSelisih > 0 ? 'Over' : (grandSelisih < 0 ? 'Under' : 'On Track'));

        $('#manhour_tfoot').html(`
            <tr style="background-color: #C4C4C4;">
                <th>Average</th>
                <th class="text-center">${avgPlan.toFixed(1)}</th>
                <th class="text-center">${avgActual.toFixed(1)}</th>
                <th class="text-center" style="${avgColor}">${(avgSelisih >= 0 ? '+' : '') + avgSelisih.toFixed(1)}</th>
                <th class="text-center" style="${avgColor}">${avgLabel}</th>
            </tr>
            <tr style="background-color: #C4C4C4;">
                <th>Total</th>
                <th class="text-center">${grandPlan.toFixed(1)}</th>
                <th class="text-center">${grandActual.toFixed(1)}</th>
                <th class="text-center" style="${totalColor}">${(grandSelisih >= 0 ? '+' : '') + grandSelisih.toFixed(1)}</th>
                <th class="text-center" style="${totalColor}">${totalLabel}</th>
            </tr>
        `);
    }

    function loadDashboard(client_id, date_from, date_to) {
        $('#dashboard_content').show();
        $('#chart_category_filter').show();
        showLoadingSkeleton();

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
                hideLoadingSkeleton();

                // Update summary cards
                $('#total_tickets').text(response.total_tickets.total);
                $('#bugs_tickets').text(response.total_tickets.bugs);
                $('#issues_tickets').text(response.total_tickets.issues);

                // Update open tickets count
                $('#total_open').text(response.total_tickets.total_open);
                $('#open_bugs').text(response.total_tickets.open_bugs);
                $('#open_issues').text(response.total_tickets.open_issues);

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
                };

                response.status_data.forEach(item => {
                    const statusName = statusMap[item.status];
                    if (statusName === 'open') statusCounts.open = item.total;
                });

                // Store data and render chart
                chartData = response.daily_data;

                // Reset chart category to 'all' when loading new data
                $('#chart_category').val('all');
                renderDailyChart('all');

                // Render summary table based on report type
                if (currentReportType === 'monthly') {
                    renderMonthlyTable(response.daily_data, 'all');
                } else {
                    renderWeeklyTable(response.daily_data, 'all');
                }

                // Simpan manhour data
                manhourData = response.manhour_data;

                // Tampil/sembunyikan manhour section
                if (currentReportType === 'monthly') {
                    $('#manhour_chart_section').show();
                    $('#manhour_table_section').show();
                    renderManhourChart(response.manhour_data);
                    renderManhourTable(response.manhour_data);
                } else {
                    $('#manhour_chart_section').hide();
                    $('#manhour_table_section').hide();
                    if (manhourChart) manhourChart.destroy();
                }

                // Show export button only for weekly report
                if (currentReportType === 'weekly') {
                    $('#btn_export_pdf').show();
                    $('#btn_export_monthly_pdf').hide();
                } else {
                    $('#btn_export_pdf').hide();
                    $('#btn_export_monthly_pdf').show();
                }
            },
            error: function(xhr, status, error) {
                hideLoadingSkeleton();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memuat data',
                    confirmButtonText: 'OK'
                });
                console.error(error);
            }
        });
    }

    // Handle Export PDF Button Click
    $('#btn_export_pdf').click(function() {
        const client_id = $('#client_id').val();
        const selectedDates = weeklyPicker.selectedDates;

        if (selectedDates.length !== 2) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Data belum tersedia. Silakan filter terlebih dahulu.',
                confirmButtonText: 'OK'
            });
            return;
        }

        const date_from = formatDate(selectedDates[0]);
        const date_to = formatDate(selectedDates[1]);

        const url = siteurl + 'dashboard/print_weekly_report?client_id=' + client_id +
            '&date_from=' + date_from + '&date_to=' + date_to;

        window.open(url, '_blank');
    });

    function updateChart(category) {
        renderDailyChart(category);
    }

    function renderDailyChart(category) {
        const bugsMap = {};
        const bugsOpenMap = {};
        const issuesMap = {};
        const issuesOpenMap = {};

        chartData.bugs.forEach(item => {
            bugsMap[item.date] = parseInt(item.total);
        });

        chartData.bugs_open.forEach(item => {
            bugsOpenMap[item.date] = parseInt(item.total);
        });

        chartData.issues.forEach(item => {
            issuesMap[item.date] = parseInt(item.total);
        });

        chartData.issues_open.forEach(item => {
            issuesOpenMap[item.date] = parseInt(item.total);
        });

        function generateDateRange(startDate, endDate) {
            const dates = [];
            const currentDate = new Date(startDate);
            const lastDate = new Date(endDate);

            while (currentDate <= lastDate) {
                const year = currentDate.getFullYear();
                const month = String(currentDate.getMonth() + 1).padStart(2, '0');
                const day = String(currentDate.getDate()).padStart(2, '0');
                dates.push(`${year}-${month}-${day}`);
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return dates;
        }

        let labels;

        if (currentReportType === 'weekly' && weeklyPicker.selectedDates.length === 2) {
            const dateFrom = formatDate(weeklyPicker.selectedDates[0]);
            const dateTo = formatDate(weeklyPicker.selectedDates[1]);
            labels = generateDateRange(dateFrom, dateTo);
        } else if (currentReportType === 'monthly' && monthlyPicker.selectedDates.length > 0) {
            const selectedDate = monthlyPicker.selectedDates[0];
            const year = selectedDate.getFullYear();
            const month = selectedDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            labels = generateDateRange(formatDate(firstDay), formatDate(lastDay));
        } else {
            const allDates = new Set([
                ...chartData.bugs.map(item => item.date),
                ...chartData.bugs_open.map(item => item.date),
                ...chartData.issues.map(item => item.date),
                ...chartData.issues_open.map(item => item.date)
            ]);
            labels = Array.from(allDates).sort();
        }

        // ============================================================
        // MODE MONTHLY: Render chart per minggu
        // ============================================================
        if (currentReportType === 'monthly') {
            const weeks = [];
            const weekLabels = [];

            for (let i = 0; i < labels.length; i += 7) {
                const weekDates = labels.slice(i, i + 7);
                const startLabel = formatDateWithMonthName(weekDates[0]);
                const endLabel = formatDateWithMonthName(weekDates[weekDates.length - 1]);
                weekLabels.push(`Week ${weeks.length + 1} (${startLabel} - ${endLabel})`);
                weeks.push(weekDates);
            }

            function sumWeeks(dataMap) {
                return weeks.map(weekDates =>
                    weekDates.reduce((sum, date) => sum + (dataMap[date] || 0), 0)
                );
            }

            let weeklyDatasets = [];

            if (category === 'all') {
                weeklyDatasets = [{
                        label: 'Bugs & Error',
                        data: sumWeeks(bugsMap),
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'User Issues',
                        data: sumWeeks(issuesMap),
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#ffc107',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ];
            } else if (category === 'bugs') {
                weeklyDatasets = [{
                        label: 'Bugs & Error - Total',
                        data: sumWeeks(bugsMap),
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Bugs & Error - Open',
                        data: sumWeeks(bugsOpenMap),
                        borderColor: '#fd7e14',
                        backgroundColor: 'rgba(253, 126, 20, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#fd7e14',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        borderDash: [5, 5]
                    }
                ];
            } else if (category === 'issues') {
                weeklyDatasets = [{
                        label: 'User Issues - Total',
                        data: sumWeeks(issuesMap),
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#ffc107',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'User Issues - Open',
                        data: sumWeeks(issuesOpenMap),
                        borderColor: '#20c997',
                        backgroundColor: 'rgba(32, 201, 151, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#20c997',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        borderDash: [5, 5]
                    }
                ];
            }

            if (dailyChart) dailyChart.destroy();

            const ctx = document.getElementById('dailyChart').getContext('2d');
            dailyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weekLabels,
                    datasets: weeklyDatasets
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
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        },
                        datalabels: {
                            display: true,
                            align: 'top',
                            anchor: 'end',
                            font: {
                                weight: 'bold',
                                size: 11
                            },
                            formatter: function(value) {
                                return value > 0 ? value : '';
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Minggu',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0
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
                            grace: '20%',
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    if (Number.isInteger(value)) return value;
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

            return; // stop, tidak perlu lanjut ke render harian
        }

        // ============================================================
        // MODE WEEKLY: Render chart per hari (kode lama tidak berubah)
        // ============================================================
        const formattedLabels = labels.map(date => formatDateWithMonthName(date));
        let datasets = [];

        if (category === 'all') {
            datasets = [{
                    label: 'Bugs & Error',
                    data: labels.map(date => bugsMap[date] || 0),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'User Issues',
                    data: labels.map(date => issuesMap[date] || 0),
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#ffc107',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ];
        } else if (category === 'bugs') {
            datasets = [{
                    label: 'Bugs & Error - Total',
                    data: labels.map(date => bugsMap[date] || 0),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Bugs & Error - Open',
                    data: labels.map(date => bugsOpenMap[date] || 0),
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253, 126, 20, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fd7e14',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    borderDash: [5, 5]
                }
            ];
        } else if (category === 'issues') {
            datasets = [{
                    label: 'User Issues - Total',
                    data: labels.map(date => issuesMap[date] || 0),
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#ffc107',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'User Issues - Open',
                    data: labels.map(date => issuesOpenMap[date] || 0),
                    borderColor: '#20c997',
                    backgroundColor: 'rgba(32, 201, 151, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#20c997',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    borderDash: [5, 5]
                }
            ];
        }

        if (dailyChart) dailyChart.destroy();

        const ctx = document.getElementById('dailyChart').getContext('2d');
        dailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedLabels,
                datasets: datasets
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
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    },
                    datalabels: {
                        display: true,
                        align: 'top',
                        anchor: 'end',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value) {
                            return value > 0 ? value : '';
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
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 0
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
                        grace: '20%',
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                if (Number.isInteger(value)) return value;
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

    // Update fungsi renderWeeklyTable
    function renderWeeklyTable(data, category = 'all') {
        $('#summary_title').text('Summary Per Hari');

        if (!data.bugs) data.bugs = [];
        if (!data.bugs_open) data.bugs_open = [];
        if (!data.issues) data.issues = [];
        if (!data.issues_open) data.issues_open = [];

        // Update table header
        let headerHtml = '<tr><th>Tanggal</th>';

        if (category === 'all') {
            headerHtml += `
            <th class="text-center">Bugs & Error</th>
            <th class="text-center">User Issues</th>
            <th class="text-center">Total</th>
            <th class="text-center">Action</th>
        `;
        } else if (category === 'bugs') {
            headerHtml += `
            <th class="text-center">Total</th>
            <th class="text-center">Open</th>
            <th class="text-center">Action</th>
        `;
        } else if (category === 'issues') {
            headerHtml += `
            <th class="text-center">Total</th>
            <th class="text-center">Open</th>
            <th class="text-center">Action</th>
        `;
        }

        headerHtml += '</tr>';
        $('#summary_thead').html(headerHtml);

        // Create date map
        const bugsMap = {};
        const bugsOpenMap = {};
        const issuesMap = {};
        const issuesOpenMap = {};

        data.bugs.forEach(item => {
            bugsMap[item.date] = parseInt(item.total);
        });

        data.bugs_open.forEach(item => {
            bugsOpenMap[item.date] = parseInt(item.total);
        });

        data.issues.forEach(item => {
            issuesMap[item.date] = parseInt(item.total);
        });

        data.issues_open.forEach(item => {
            issuesOpenMap[item.date] = parseInt(item.total);
        });

        const allDates = new Set([
            ...data.bugs.map(item => item.date),
            ...data.bugs_open.map(item => item.date),
            ...data.issues.map(item => item.date),
            ...data.issues_open.map(item => item.date)
        ]);
        const sortedDates = Array.from(allDates).sort();

        if (sortedDates.length === 0) {
            let noDataColspan = category === 'all' ? 5 : 4;
            $('#summary_tbody').html(`<tr><td colspan="${noDataColspan}" class="text-center">Tidak ada data</td></tr>`);

            let footerHtml = '<tr><th>Total</th>';
            if (category === 'all') {
                footerHtml += '<th class="text-center">0</th><th class="text-center">0</th><th class="text-center">0</th><th></th>';
            } else {
                footerHtml += '<th class="text-center">0</th><th class="text-center">0</th><th></th>';
            }
            footerHtml += '</tr>';
            $('#summary_table tfoot').html(footerHtml);
            return;
        }

        let tableHtml = '';
        let totalBugs = 0;
        let totalBugsOpen = 0;
        let totalIssues = 0;
        let totalIssuesOpen = 0;

        const currentClientId = $('#client_id').val();

        sortedDates.forEach(date => {
            const bugs = bugsMap[date] || 0;
            const bugsOpen = bugsOpenMap[date] || 0;
            const issues = issuesMap[date] || 0;
            const issuesOpen = issuesOpenMap[date] || 0;
            const total = bugs + issues;

            totalBugs += bugs;
            totalBugsOpen += bugsOpen;
            totalIssues += issues;
            totalIssuesOpen += issuesOpen;

            let rowHtml = `<tr><td>${date}</td>`;

            if (category === 'all') {
                rowHtml += `
                <td class="text-center">${bugs}</td>
                <td class="text-center">${issues}</td>
                <td class="text-center"><strong>${total}</strong></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info btn-view-detail" 
                            data-client-id="${currentClientId}" 
                            data-date="${date}" 
                            data-category="${category}">
                        <i class="ti ti-eye"></i> View
                    </button>
                </td>
            `;
            } else if (category === 'bugs') {
                rowHtml += `
                <td class="text-center"><strong>${bugs}</strong></td>
                <td class="text-center">${bugsOpen}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info btn-view-detail" 
                            data-client-id="${currentClientId}" 
                            data-date="${date}" 
                            data-category="${category}">
                        <i class="ti ti-eye"></i> View
                    </button>
                </td>
            `;
            } else if (category === 'issues') {
                rowHtml += `
                <td class="text-center"><strong>${issues}</strong></td>
                <td class="text-center">${issuesOpen}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info btn-view-detail" 
                            data-client-id="${currentClientId}" 
                            data-date="${date}" 
                            data-category="${category}">
                        <i class="ti ti-eye"></i> View
                    </button>
                </td>
            `;
            }

            rowHtml += '</tr>';
            tableHtml += rowHtml;
        });

        $('#summary_tbody').html(tableHtml);

        // Update footer
        let footerHtml = '<tr><th>Total</th>';

        if (category === 'all') {
            footerHtml += `
            <th class="text-center">${totalBugs}</th>
            <th class="text-center">${totalIssues}</th>
            <th class="text-center">${totalBugs + totalIssues}</th>
            <th></th>
        `;
        } else if (category === 'bugs') {
            footerHtml += `
            <th class="text-center">${totalBugs}</th>
            <th class="text-center">${totalBugsOpen}</th>
            <th></th>
        `;
        } else if (category === 'issues') {
            footerHtml += `
            <th class="text-center">${totalIssues}</th>
            <th class="text-center">${totalIssuesOpen}</th>
            <th></th>
        `;
        }

        footerHtml += '</tr>';
        $('#summary_table tfoot').html(footerHtml);
    }

    // Render table for monthly report (per minggu)
    function renderMonthlyTable(data, category = 'all') {
        $('#summary_title').text('Summary Per Minggu');

        // Safety check - ensure data properties exist
        if (!data.bugs) data.bugs = [];
        if (!data.bugs_open) data.bugs_open = [];
        if (!data.issues) data.issues = [];
        if (!data.issues_open) data.issues_open = [];

        // Update table header based on category
        let headerHtml = '<tr><th>Periode</th>';

        if (category === 'all') {
            headerHtml += `
            <th class="text-center">Bugs & Error</th>
            <th class="text-center">User Issues</th>
            <th class="text-center">Total</th>
        `;
        } else if (category === 'bugs') {
            headerHtml += `
            <th class="text-center">Total</th>
            <th class="text-center">Open</th>
        `;
        } else if (category === 'issues') {
            headerHtml += `
            <th class="text-center">Total</th>
            <th class="text-center">Open</th>
        `;
        }

        headerHtml += '</tr>';
        $('#summary_thead').html(headerHtml);

        // Create date map
        const bugsMap = {};
        const bugsOpenMap = {};
        const issuesMap = {};
        const issuesOpenMap = {};

        data.bugs.forEach(item => {
            bugsMap[item.date] = parseInt(item.total);
        });

        data.bugs_open.forEach(item => {
            bugsOpenMap[item.date] = parseInt(item.total);
        });

        data.issues.forEach(item => {
            issuesMap[item.date] = parseInt(item.total);
        });

        data.issues_open.forEach(item => {
            issuesOpenMap[item.date] = parseInt(item.total);
        });

        // Get all unique dates
        const allDates = new Set([
            ...data.bugs.map(item => item.date),
            ...data.bugs_open.map(item => item.date),
            ...data.issues.map(item => item.date),
            ...data.issues_open.map(item => item.date)
        ]);
        const sortedDates = Array.from(allDates).sort();

        if (sortedDates.length === 0) {
            let noDataColspan = category === 'all' ? 4 : 3;
            $('#summary_tbody').html(`<tr><td colspan="${noDataColspan}" class="text-center">Tidak ada data</td></tr>`);

            let footerHtml = '<tr><th>Total</th>';
            if (category === 'all') {
                footerHtml += '<th class="text-center">0</th><th class="text-center">0</th><th class="text-center">0</th>';
            } else {
                footerHtml += '<th class="text-center">0</th><th class="text-center">0</th>';
            }
            footerHtml += '</tr>';
            $('#summary_table tfoot').html(footerHtml);
            return;
        }

        // Get first and last date to determine the full month range
        const firstDate = new Date(sortedDates[0]);
        const year = firstDate.getFullYear();
        const month = firstDate.getMonth();
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);

        // Create complete date range for the month (including dates with no data)
        const completeDates = [];
        for (let d = new Date(firstDayOfMonth); d <= lastDayOfMonth; d.setDate(d.getDate() + 1)) {
            completeDates.push(formatDate(new Date(d)));
        }

        // Group dates into weeks (starting from day 1 of the month)
        const weeks = [];
        let currentWeek = [];

        completeDates.forEach((date, index) => {
            currentWeek.push(date);

            // Start new week every 7 days or at the end of month
            if (currentWeek.length === 7 || index === completeDates.length - 1) {
                weeks.push([...currentWeek]);
                currentWeek = [];
            }
        });

        let tableHtml = '';
        let totalBugs = 0;
        let totalBugsOpen = 0;
        let totalIssues = 0;
        let totalIssuesOpen = 0;

        weeks.forEach((week, index) => {
            const startDate = week[0];
            const endDate = week[week.length - 1];

            let weekBugs = 0;
            let weekBugsOpen = 0;
            let weekIssues = 0;
            let weekIssuesOpen = 0;

            // Sum up bugs and issues for all dates in this week
            week.forEach(date => {
                weekBugs += bugsMap[date] || 0;
                weekBugsOpen += bugsOpenMap[date] || 0;
                weekIssues += issuesMap[date] || 0;
                weekIssuesOpen += issuesOpenMap[date] || 0;
            });

            const weekTotal = weekBugs + weekIssues;
            totalBugs += weekBugs;
            totalBugsOpen += weekBugsOpen;
            totalIssues += weekIssues;
            totalIssuesOpen += weekIssuesOpen;

            let rowHtml = `<tr><td>Minggu ${index + 1} (${startDate} s/d ${endDate})</td>`;

            if (category === 'all') {
                rowHtml += `
                <td class="text-center">${weekBugs}</td>
                <td class="text-center">${weekIssues}</td>
                <td class="text-center"><strong>${weekTotal}</strong></td>
            `;
            } else if (category === 'bugs') {
                rowHtml += `
                <td class="text-center"><strong>${weekBugs}</strong></td>
                <td class="text-center">${weekBugsOpen}</td>
            `;
            } else if (category === 'issues') {
                rowHtml += `
                <td class="text-center"><strong>${weekIssues}</strong></td>
                <td class="text-center">${weekIssuesOpen}</td>
            `;
            }

            rowHtml += '</tr>';
            tableHtml += rowHtml;
        });

        $('#summary_tbody').html(tableHtml);

        // Update footer based on category
        let footerHtml = '<tr><th>Total</th>';

        if (category === 'all') {
            footerHtml += `
            <th class="text-center">${totalBugs}</th>
            <th class="text-center">${totalIssues}</th>
            <th class="text-center">${totalBugs + totalIssues}</th>
        `;
        } else if (category === 'bugs') {
            footerHtml += `
            <th class="text-center">${totalBugs}</th>
            <th class="text-center">${totalBugsOpen}</th>
        `;
        } else if (category === 'issues') {
            footerHtml += `
            <th class="text-center">${totalIssues}</th>
            <th class="text-center">${totalIssuesOpen}</th>
        `;
        }

        footerHtml += '</tr>';
        $('#summary_table tfoot').html(footerHtml);
    }

    $('#btn_export_monthly_pdf').click(function() {
        const client_id = $('#client_id').val();
        const selectedDate = monthlyPicker.selectedDates[0];

        if (!selectedDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Data belum tersedia.',
                confirmButtonText: 'OK'
            });
            return;
        }

        const year = selectedDate.getFullYear();
        const month = selectedDate.getMonth();
        const date_from = formatDate(new Date(year, month, 1));
        const date_to = formatDate(new Date(year, month + 1, 0));

        const url = siteurl + 'dashboard/print_monthly_report?client_id=' + client_id +
            '&date_from=' + date_from + '&date_to=' + date_to;

        window.open(url, '_blank');
    });
</script>