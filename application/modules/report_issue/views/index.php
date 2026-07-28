<?php
$ENABLE_ADD     = has_permission('Report_issue.Add');
$ENABLE_MANAGE  = has_permission('Report_issue.Manage');
$ENABLE_VIEW    = has_permission('Report_issue.View');
$ENABLE_DELETE  = has_permission('Report_issue.Delete');
?>


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

    .card-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        color: white !important;
    }

    .card-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%) !important;
        color: white !important;
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

    .icon-info {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .icon-success {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }

    .card-warning .stats-subtitle {
        border-top-color: rgba(0, 0, 0, 0.1) !important;
    }

    .prio-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1rem;
    }

    .prio-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #EEEDFE;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .prio-title {
        font-size: 15px;
        font-weight: 600;
        margin: 0;
    }

    .prio-subtitle {
        font-size: 12px;
        color: #6c757d;
        margin: 0;
    }

    .prio-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 14px 16px;
        position: relative;
        overflow: hidden;
        transition: box-shadow 0.2s;
        height: 100%;
    }

    .prio-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .prio-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }

    .prio-card.rank-1::before {
        background: #E24B4A;
    }

    .prio-card.rank-2::before {
        background: #EF9F27;
    }

    .prio-card.rank-3::before {
        background: #378ADD;
    }

    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .rank-1 .rank-badge {
        background: #FCEBEB;
        color: #A32D2D;
    }

    .rank-2 .rank-badge {
        background: #FAEEDA;
        color: #854F0B;
    }

    .rank-3 .rank-badge {
        background: #E6F1FB;
        color: #185FA5;
    }

    .prio-ticket-no {
        font-size: 11px;
        color: #6c757d;
        margin: 0 0 4px;
    }

    .prio-report {
        font-size: 13px;
        font-weight: 600;
        color: #212529;
        margin: 0 0 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .prio-meta-row {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .prio-meta-row i {
        font-size: 11px;
        width: 12px;
    }

    .prio-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
    }

    .prio-client-pill {
        font-size: 11px;
        color: #495057;
        background: #f1f3f5;
        padding: 2px 8px;
        border-radius: 20px;
    }

    .prio-status {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .s-open {
        background: #FCEBEB;
        color: #A32D2D;
    }

    .s-process {
        background: #E6F1FB;
        color: #185FA5;
    }

    .s-pending {
        background: #FAEEDA;
        color: #854F0B;
    }

    .s-revisi {
        background: #F4C0D1;
        color: #72243E;
    }

    .prio-empty {
        text-align: center;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px dashed #dee2e6;
    }

    .prio-empty i {
        font-size: 32px;
        color: #adb5bd;
        display: block;
        margin-bottom: 0.5rem;
    }

    .prio-empty p {
        font-size: 13px;
        color: #6c757d;
        margin: 0;
    }

    .prio-skeleton {
        height: 160px;
        border-radius: 12px;
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

    .prio-toggle-btn {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--color-text-secondary, #6c757d);
        font-size: 12px;
    }

    .prio-toggle-btn:hover {
        color: #534AB7;
    }

    .prio-chevron {
        transition: transform 0.25s ease;
        display: inline-block;
        font-size: 12px;
    }

    .prio-chevron.collapsed {
        transform: rotate(-90deg);
    }

    .prio-body {
        overflow: hidden;
        transition: max-height 0.3s ease, opacity 0.3s ease;
        max-height: 600px;
        opacity: 1;
    }

    .prio-body.collapsed {
        max-height: 0;
        opacity: 0;
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
                    <option value="yearly">Tahunan</option>
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

            <!-- Filter Tahunan -->
            <div class="col-md-3" id="yearly_filter" style="display: none;">
                <label class="form-label">Pilih Tahun</label>
                <select class="form-select" id="date_yearly">
                </select>
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
                    <button type="button" class="btn btn-info text-white" id="btn_export_yearly_pdf" style="display: none;">
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
            <div class="col-md-4" id="card_total_col">
                <div class="card stats-card card-primary">
                    <div class="card-body">
                        <div class="stats-icon icon-primary">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="stats-title" id="card_total_title">Total Tickets Bugs & User Issue</div>
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

            <!-- Kartu tambahan untuk laporan Tahunan: Request & Development -->
            <div class="col-md-4" id="card_request_col" style="display: none;">
                <div class="card stats-card card-info">
                    <div class="card-body">
                        <div class="stats-icon icon-info">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stats-title">Request</div>
                        <h2 class="stats-number" id="request_tickets">0</h2>
                        <div class="stats-subtitle">
                            <i class="fas fa-hourglass-half"></i>
                            <span id="open_request">0</span> Open Request
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" id="card_development_col" style="display: none;">
                <div class="card stats-card card-success">
                    <div class="card-body">
                        <div class="stats-icon icon-success">
                            <i class="fas fa-code"></i>
                        </div>
                        <div class="stats-title">Development</div>
                        <h2 class="stats-number" id="development_tickets">0</h2>
                        <div class="stats-subtitle">
                            <i class="fas fa-hourglass-half"></i>
                            <span id="open_development">0</span> Open Development
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
                        <h5 id="chart_title">Tickets Per Day</h5>
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



        <!-- Man Hour Chart (Monthly & Yearly Only) -->
        <div class="row mb-4" id="manhour_chart_section" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 id="manhour_chart_title">Man Hour Plan vs Actual per Minggu</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="manhourChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Man Hour Table (Monthly & Yearly Only) -->
        <div class="row mb-4" id="manhour_table_section" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 id="manhour_table_title">Man Hour Plan vs Actual Per Minggu</h5>
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
    let carryOverData = null;

    // Kategori chart per tipe report
    const CATEGORY_OPTIONS = {
        default: [
            { value: 'all', label: 'All Categories' },
            { value: 'bugs', label: 'Bugs & Error Only' },
            { value: 'issues', label: 'Issues Only' },
        ],
        yearly: [
            { value: 'all', label: 'All Categories' },
            { value: 'bugs', label: 'Bugs & Error Only' },
            { value: 'issues', label: 'Issues Only' },
            { value: 'request', label: 'Request Only' },
            { value: 'development', label: 'Development Only' },
        ]
    };

    function rebuildCategoryOptions(reportType) {
        const opts = CATEGORY_OPTIONS[reportType] || CATEGORY_OPTIONS.default;
        const $sel = $('#chart_category');
        const currentVal = $sel.val();
        $sel.empty();
        opts.forEach(o => {
            $sel.append(`<option value="${o.value}">${o.label}</option>`);
        });
        // Kalau value lama masih valid di opsi baru, pertahankan. Kalau tidak, reset ke 'all'.
        const stillValid = opts.some(o => o.value === currentVal);
        $sel.val(stillValid ? currentVal : 'all');
    }

    // Isi pilihan tahun untuk filter Tahunan (5 tahun ke belakang s/d tahun berjalan)
    (function populateYearSelect() {
        const $sel = $('#date_yearly');
        const currentYear = new Date().getFullYear();
        let optionsHtml = '';
        for (let y = currentYear; y >= currentYear - 5; y--) {
            optionsHtml += `<option value="${y}">${y}</option>`;
        }
        $sel.html(optionsHtml);
    })();

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
        $('#yearly_filter').hide();
        $('#btn_export_pdf').hide();
        $('#btn_export_monthly_pdf').hide();
        $('#btn_export_yearly_pdf').hide();

        if (monthlyPicker) monthlyPicker.clear();
        if (weeklyPicker) weeklyPicker.clear();

        if (reportType === 'monthly') {
            $('#monthly_filter').show();
        } else if (reportType === 'weekly') {
            $('#weekly_filter').show();
        } else if (reportType === 'yearly') {
            $('#yearly_filter').show();
        }

        rebuildCategoryOptions(reportType);
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
        } else if (report_type === 'yearly') {
            const selectedYear = parseInt($('#date_yearly').val());

            if (!selectedYear) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Pilih tahun terlebih dahulu',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const today = new Date();
            date_from = formatDate(new Date(selectedYear, 0, 1));

            // Kalau tahun yang dipilih = tahun berjalan, date_to = hari ini (jadi cuma Jan..bulan berjalan)
            // Kalau tahun lampau, date_to = akhir Desember tahun tsb.
            if (selectedYear === today.getFullYear()) {
                date_to = formatDate(today);
            } else {
                date_to = formatDate(new Date(selectedYear, 11, 31));
            }
        }

        currentReportType = report_type;
        loadDashboard(client_id, date_from, date_to);
    });

    // Handle click button view detail
    $(document).on('click', '.btn-view-detail', function() {
        const clientId = $(this).data('client-id');
        const date = $(this).data('date');
        const category = $(this).data('category');
        const dateFrom = $(this).data('date-from') || ''; // ← tambah ini
        const mode = $(this).data('mode') || 'day'; // 'day' (mingguan/bulanan) atau 'month' (tahunan)

        const isCarryOver = (date === 'carry_over');
        const periodLabel = (mode === 'month') ? formatMonthLabel(date) : date;
        const titleLabel = isCarryOver ?
            'Carry Over Tickets (' + category.toUpperCase() + ')' :
            'Detail Tickets - ' + periodLabel + ' (' + category.toUpperCase() + ')';

        $('#modalTicketDetail').modal('show');
        $('#modalTicketDetailLabel').text(titleLabel);
        $('#modalTicketDetailBody').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);

        $.ajax({
            url: siteurl + 'report_issue/get_ticket_detail',
            type: 'GET',
            data: {
                client_id: clientId,
                date: date,
                category: category,
                date_from: dateFrom, // ← tambah ini
                mode: mode
            },
            success: function(response) {
                $('#modalTicketDetailBody').html(response);
                if (!$.fn.DataTable.isDataTable('#ticket_table')) {
                    $('#ticket_table').DataTable({
                        pageLength: 10,
                        order: []
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

    // Format 'YYYY-MM' -> 'Mon YYYY' (dipakai untuk laporan Tahunan)
    function formatMonthLabel(monthString) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const parts = monthString.split('-');
        const year = parseInt(parts[0]);
        const monthIndex = parseInt(parts[1]) - 1;
        return `${months[monthIndex]} ${year}`;
    }

    $('#chart_category').change(function() {
        const category = $(this).val();
        updateChart(category);
        updateTable(category);
    });

    function updateTable(category) {
        if (currentReportType === 'yearly') {
            renderYearlyTable(chartData, category);
        } else if (currentReportType === 'monthly') {
            renderMonthlyTable(chartData, category);
        } else {
            const data = chartData;
            const bugsMap = {};
            const bugsOpenMap = {};
            const issuesMap = {};
            const issuesOpenMap = {};

            (data.bugs || []).forEach(i => {
                bugsMap[i.date] = parseInt(i.total);
            });
            (data.bugs_open || []).forEach(i => {
                bugsOpenMap[i.date] = parseInt(i.total);
            });
            (data.issues || []).forEach(i => {
                issuesMap[i.date] = parseInt(i.total);
            });
            (data.issues_open || []).forEach(i => {
                issuesOpenMap[i.date] = parseInt(i.total);
            });

            const allDates = new Set([
                ...(data.bugs || []).map(i => i.date),
                ...(data.bugs_open || []).map(i => i.date),
                ...(data.issues || []).map(i => i.date),
                ...(data.issues_open || []).map(i => i.date),
            ]);
            const sortedDates = Array.from(allDates).sort();

            // Update header sesuai category
            let headerHtml = '<tr><th>Tanggal</th>';
            if (category === 'all') {
                headerHtml += `
                <th class="text-center">Bugs & Error</th>
                <th class="text-center">User Issues</th>
                <th class="text-center">Total</th>
                <th class="text-center">Action</th>`;
            } else if (category === 'bugs') {
                headerHtml += `
                <th class="text-center">Total</th>
                <th class="text-center">Open</th>
                <th class="text-center">Action</th>`;
            } else if (category === 'issues') {
                headerHtml += `
                <th class="text-center">Total</th>
                <th class="text-center">Open</th>
                <th class="text-center">Action</th>`;
            }
            headerHtml += '</tr>';
            $('#summary_thead').html(headerHtml);

            const currentClientId = $('#client_id').val();
            const selectedDates = weeklyPicker.selectedDates;
            const dateFrom = selectedDates.length === 2 ? formatDate(selectedDates[0]) : null;

            // Pakai carryOverData yang sudah di-cache, tidak fetch ulang
            _buildWeeklyTableBody(
                sortedDates, bugsMap, bugsOpenMap, issuesMap, issuesOpenMap,
                category, currentClientId, dateFrom, carryOverData
            );
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
                layout: {
                    padding: {
                        right: 20
                    }
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

    // ===== Man Hour (Tahunan, per bulan) =====
    function buildYearlyMonthKeys() {
        const selectedYear = parseInt($('#date_yearly').val());
        const today = new Date();
        const lastMonthIndex = (selectedYear === today.getFullYear()) ? today.getMonth() : 11;

        const keys = [];
        const labels = [];
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for (let m = 0; m <= lastMonthIndex; m++) {
            const key = `${selectedYear}-${String(m + 1).padStart(2, '0')}`;
            keys.push(key);
            labels.push(`${monthNames[m]} ${selectedYear}`);
        }
        return {
            keys,
            labels
        };
    }

    function renderManhourChartYearly(data) {
        if (!data || !data.plan || !data.actual) return;

        const mhPlanMap = {};
        const mhActualMap = {};
        data.plan.forEach(i => {
            mhPlanMap[i.month] = parseFloat(i.total);
        });
        data.actual.forEach(i => {
            mhActualMap[i.month] = parseFloat(i.total);
        });

        const {
            keys,
            labels
        } = buildYearlyMonthKeys();

        if (manhourChart) manhourChart.destroy();

        const ctx = document.getElementById('manhourChart').getContext('2d');
        manhourChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Plan',
                        data: keys.map(k => parseFloat((mhPlanMap[k] || 0).toFixed(1))),
                        backgroundColor: 'rgba(111,66,193,0.6)',
                        borderColor: '#6f42c1',
                        borderWidth: 2,
                        borderRadius: 4,
                    },
                    {
                        label: 'Actual',
                        data: keys.map(k => parseFloat((mhActualMap[k] || 0).toFixed(1))),
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
                layout: {
                    padding: {
                        right: 20
                    }
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
                            text: 'Bulan',
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

    function renderManhourTableYearly(data) {
        if (!data || !data.plan || !data.actual) return;

        const mhPlanMap = {};
        const mhActualMap = {};
        data.plan.forEach(i => {
            mhPlanMap[i.month] = parseFloat(i.total);
        });
        data.actual.forEach(i => {
            mhActualMap[i.month] = parseFloat(i.total);
        });

        const {
            keys,
            labels
        } = buildYearlyMonthKeys();

        let tbodyHtml = '';
        let grandPlan = 0;
        let grandActual = 0;

        keys.forEach((key, index) => {
            const plan = parseFloat((mhPlanMap[key] || 0).toFixed(1));
            const actual = parseFloat((mhActualMap[key] || 0).toFixed(1));
            const selisih = parseFloat((actual - plan).toFixed(1));

            grandPlan += plan;
            grandActual += actual;

            const color = selisih > 0 ? 'color:#dc3545;' : (selisih < 0 ? 'color:#28a745;' : '');
            const label = (plan === 0 && actual === 0) ? '-' : (selisih > 0 ? 'Over' : (selisih < 0 ? 'Under' : 'On Track'));
            const sign = selisih >= 0 ? '+' : '';

            tbodyHtml += `
            <tr>
                <td>${labels[index]}</td>
                <td class="text-center"><strong>${plan.toFixed(1)}</strong></td>
                <td class="text-center"><strong>${actual.toFixed(1)}</strong></td>
                <td class="text-center" style="${color}"><strong>${sign}${selisih.toFixed(1)}</strong></td>
                <td class="text-center" style="${color}"><strong>${label}</strong></td>
            </tr>
        `;
        });

        $('#manhour_tbody').html(tbodyHtml);

        const monthCount = keys.length || 1;
        const avgPlan = parseFloat((grandPlan / monthCount).toFixed(1));
        const avgActual = parseFloat((grandActual / monthCount).toFixed(1));
        const avgSelisih = parseFloat((avgActual - avgPlan).toFixed(1));
        const grandSelisih = parseFloat((grandActual - grandPlan).toFixed(1));

        const avgColor = avgSelisih > 0 ? 'color:#dc3545;' : (avgSelisih < 0 ? 'color:#28a745;' : '');
        const totalColor = grandSelisih > 0 ? 'color:#dc3545;' : (grandSelisih < 0 ? 'color:#28a745;' : '');

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
        carryOverData = null;
        $('#dashboard_content').show();
        $('#chart_category_filter').show();
        showLoadingSkeleton();

        // Toggle kartu summary sesuai tipe report
        if (currentReportType === 'yearly') {
            $('#card_request_col').show();
            $('#card_development_col').show();
            $('#card_total_col').removeClass('col-md-4').addClass('col-md-4');
            $('#card_total_title').text('Total Tickets (Semua Kategori)');
            $('#chart_title').text('Tickets Per Bulan');
        } else {
            $('#card_request_col').hide();
            $('#card_development_col').hide();
            $('#card_total_title').text('Total Tickets Bugs & User Issue');
            $('#chart_title').text('Tickets Per Day');
        }

        $.ajax({
            url: siteurl + 'report_issue' + '/get_dashboard_data',
            type: 'POST',
            data: {
                client_id: client_id,
                date_from: date_from,
                date_to: date_to,
                report_type: currentReportType
            },
            dataType: 'json',
            success: function(response) {
                hideLoadingSkeleton();

                if (currentReportType === 'yearly') {
                    const t = response.total_tickets;
                    $('#total_tickets').text(t.total);
                    $('#bugs_tickets').text(t.bugs);
                    $('#issues_tickets').text(t.issues);
                    $('#request_tickets').text(t.request);
                    $('#development_tickets').text(t.development);
                    $('#total_open').text(t.total_open);
                    $('#open_bugs').text(t.open_bugs);
                    $('#open_issues').text(t.open_issues);
                    $('#open_request').text(t.open_request);
                    $('#open_development').text(t.open_development);

                    chartData = response.monthly_data;

                    $('#chart_category').val('all');
                    renderYearlyChart('all');
                    renderYearlyTable(response.monthly_data, 'all');

                    manhourData = response.manhour_data;
                    $('#manhour_chart_section').show();
                    $('#manhour_table_section').show();
                    $('#manhour_chart_title').text('Man Hour Plan vs Actual per Bulan');
                    $('#manhour_table_title').text('Man Hour Plan vs Actual Per Bulan');
                    renderManhourChartYearly(response.manhour_data);
                    renderManhourTableYearly(response.manhour_data);

                    $('#btn_export_pdf').hide();
                    $('#btn_export_monthly_pdf').hide();
                    $('#btn_export_yearly_pdf').show();

                    return;
                }

                $('#total_tickets').text(response.total_tickets.total);
                $('#bugs_tickets').text(response.total_tickets.bugs);
                $('#issues_tickets').text(response.total_tickets.issues);
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
                    open: 0
                };
                response.status_data.forEach(item => {
                    const statusName = statusMap[item.status];
                    if (statusName === 'open') statusCounts.open = item.total;
                });

                chartData = response.daily_data;

                $('#chart_category').val('all');
                renderDailyChart('all');

                if (currentReportType === 'monthly') {
                    renderMonthlyTable(response.daily_data, 'all');
                } else {
                    renderWeeklyTable(response.daily_data, 'all');
                }

                manhourData = response.manhour_data;

                if (currentReportType === 'monthly') {
                    $('#manhour_chart_section').show();
                    $('#manhour_table_section').show();
                    $('#manhour_chart_title').text('Man Hour Plan vs Actual per Minggu');
                    $('#manhour_table_title').text('Man Hour Plan vs Actual Per Minggu');
                    renderManhourChart(response.manhour_data);
                    renderManhourTable(response.manhour_data);
                } else {
                    $('#manhour_chart_section').hide();
                    $('#manhour_table_section').hide();
                    if (manhourChart) manhourChart.destroy();
                }

                if (currentReportType === 'weekly') {
                    $('#btn_export_pdf').show();
                    $('#btn_export_monthly_pdf').hide();
                    $('#btn_export_yearly_pdf').hide();
                } else {
                    $('#btn_export_pdf').hide();
                    $('#btn_export_monthly_pdf').show();
                    $('#btn_export_yearly_pdf').hide();
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

        const url = siteurl + 'report_issue/print_weekly_report?client_id=' + client_id +
            '&date_from=' + date_from + '&date_to=' + date_to;

        window.open(url, '_blank');
    });

    function updateChart(category) {
        if (currentReportType === 'yearly') {
            renderYearlyChart(category);
        } else {
            renderDailyChart(category);
        }
    }

    // ===== Chart Tahunan (per bulan, 4 kategori) =====
    function renderYearlyChart(category) {
        const data = chartData;
        const {
            keys,
            labels
        } = buildYearlyMonthKeys();

        function mapOf(arr) {
            const m = {};
            (arr || []).forEach(i => {
                m[i.month] = parseInt(i.total);
            });
            return m;
        }

        const maps = {
            bugs: mapOf(data.bugs),
            issues: mapOf(data.issues),
            request: mapOf(data.request),
            development: mapOf(data.development),
        };

        const seriesDefs = {
            bugs: {
                label: 'Bugs & Error',
                color: '#dc3545',
                bg: 'rgba(220, 53, 69, 0.1)'
            },
            issues: {
                label: 'User Issues',
                color: '#ffc107',
                bg: 'rgba(255, 193, 7, 0.1)'
            },
            request: {
                label: 'Request',
                color: '#0dcaf0',
                bg: 'rgba(13, 202, 240, 0.1)'
            },
            development: {
                label: 'Development',
                color: '#198754',
                bg: 'rgba(25, 135, 84, 0.1)'
            },
        };

        const activeKeys = (category === 'all') ? ['bugs', 'issues', 'request', 'development'] : [category];

        const datasets = activeKeys.map(key => {
            const def = seriesDefs[key];
            return {
                label: def.label,
                data: keys.map(k => maps[key][k] || 0),
                borderColor: def.color,
                backgroundColor: def.bg,
                tension: 0.1,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: def.color,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            };
        });

        if (dailyChart) dailyChart.destroy();

        const ctx = document.getElementById('dailyChart').getContext('2d');
        dailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                layout: {
                    padding: {
                        right: 20
                    }
                },
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
                            text: 'Bulan',
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
    }

    // Tabel summary Tahunan (per bulan, 4 kategori + Open)
    function renderYearlyTable(data, category = 'all') {
        $('#summary_title').text('Summary Per Bulan');

        const {
            keys,
            labels
        } = buildYearlyMonthKeys();

        function mapOf(arr) {
            const m = {};
            (arr || []).forEach(i => {
                m[i.month] = parseInt(i.total);
            });
            return m;
        }

        const maps = {
            bugs: mapOf(data.bugs),
            bugs_open: mapOf(data.bugs_open),
            issues: mapOf(data.issues),
            issues_open: mapOf(data.issues_open),
            request: mapOf(data.request),
            request_open: mapOf(data.request_open),
            development: mapOf(data.development),
            development_open: mapOf(data.development_open),
        };

        const catLabels = {
            bugs: 'Bugs & Error',
            issues: 'User Issues',
            request: 'Request',
            development: 'Development'
        };

        let headerHtml = '<tr><th>Bulan</th>';
        if (category === 'all') {
            headerHtml += `
            <th class="text-center">Bugs & Error</th>
            <th class="text-center">User Issues</th>
            <th class="text-center">Request</th>
            <th class="text-center">Development</th>
            <th class="text-center">Total</th>
            <th class="text-center">Action</th>`;
        } else {
            headerHtml += `
            <th class="text-center">${catLabels[category]} - Total</th>
            <th class="text-center">${catLabels[category]} - Open</th>
            <th class="text-center">Action</th>`;
        }
        headerHtml += '</tr>';
        $('#summary_thead').html(headerHtml);

        const currentClientId = $('#client_id').val();
        const selectedYear = $('#date_yearly').val();
        const dateFrom = formatDate(new Date(parseInt(selectedYear), 0, 1));

        let tableHtml = '';
        let totals = {
            bugs: 0,
            issues: 0,
            request: 0,
            development: 0,
            bugs_open: 0,
            issues_open: 0,
            request_open: 0,
            development_open: 0
        };

        keys.forEach((key, index) => {
            const bugs = maps.bugs[key] || 0;
            const issues = maps.issues[key] || 0;
            const request = maps.request[key] || 0;
            const development = maps.development[key] || 0;
            const bugsOpen = maps.bugs_open[key] || 0;
            const issuesOpen = maps.issues_open[key] || 0;
            const requestOpen = maps.request_open[key] || 0;
            const developmentOpen = maps.development_open[key] || 0;

            totals.bugs += bugs;
            totals.issues += issues;
            totals.request += request;
            totals.development += development;
            totals.bugs_open += bugsOpen;
            totals.issues_open += issuesOpen;
            totals.request_open += requestOpen;
            totals.development_open += developmentOpen;

            const total = bugs + issues + request + development;

            let rowHtml = `<tr><td>${labels[index]}</td>`;

            if (category === 'all') {
                rowHtml += `
                <td class="text-center">${bugs}</td>
                <td class="text-center">${issues}</td>
                <td class="text-center">${request}</td>
                <td class="text-center">${development}</td>
                <td class="text-center"><strong>${total}</strong></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info btn-view-detail"
                            data-client-id="${currentClientId}"
                            data-date="${key}"
                            data-category="all"
                            data-date-from="${dateFrom}"
                            data-mode="month">
                        <i class="ti ti-eye"></i> View
                    </button>
                </td>`;
            } else {
                const catTotal = maps[category][key] || 0;
                const catOpen = maps[category + '_open'][key] || 0;
                rowHtml += `
                <td class="text-center"><strong>${catTotal}</strong></td>
                <td class="text-center">${catOpen}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info btn-view-detail"
                            data-client-id="${currentClientId}"
                            data-date="${key}"
                            data-category="${category}"
                            data-date-from="${dateFrom}"
                            data-mode="month">
                        <i class="ti ti-eye"></i> View
                    </button>
                </td>`;
            }

            rowHtml += '</tr>';
            tableHtml += rowHtml;
        });

        if (keys.length === 0) {
            const colspan = category === 'all' ? 6 : 3;
            tableHtml = `<tr><td colspan="${colspan}" class="text-center">Tidak ada data</td></tr>`;
        }

        $('#summary_tbody').html(tableHtml);

        let footerHtml = '<tr><th>Total</th>';
        if (category === 'all') {
            const grandTotal = totals.bugs + totals.issues + totals.request + totals.development;
            footerHtml += `
            <th class="text-center">${totals.bugs}</th>
            <th class="text-center">${totals.issues}</th>
            <th class="text-center">${totals.request}</th>
            <th class="text-center">${totals.development}</th>
            <th class="text-center">${grandTotal}</th>
            <th></th>`;
        } else {
            footerHtml += `
            <th class="text-center">${totals[category]}</th>
            <th class="text-center">${totals[category + '_open']}</th>
            <th></th>`;
        }
        footerHtml += '</tr>';
        $('#summary_table tfoot').html(footerHtml);
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
                        orderWidth: 3,
                        tension: 0.1,
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
                        tension: 0.1,
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
                        orderWidth: 3,
                        tension: 0.1,
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
                        tension: 0.1,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#fd7e14',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        borderDash: [5, 5]
                    }
                ];
            } else if (category === 'issues') {
                weeklyDatasets = [{
                        label: 'User Issues - Total',
                        data: sumWeeks(issuesMap),
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        orderWidth: 3,
                        tension: 0.1,
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
                        tension: 0.1,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#20c997',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
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
                    layout: {
                        padding: {
                            right: 20
                        }
                    },
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
                    orderWidth: 3,
                    tension: 0.1,
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
                    tension: 0.1,
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
                    orderWidth: 3,
                    tension: 0.1,
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
                    tension: 0.1,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fd7e14',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    borderDash: [5, 5]
                }
            ];
        } else if (category === 'issues') {
            datasets = [{
                    label: 'User Issues - Total',
                    data: labels.map(date => issuesMap[date] || 0),
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    orderWidth: 3,
                    tension: 0.1,
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
                    tension: 0.1,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#20c997',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
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
                layout: {
                    padding: {
                        right: 20
                    }
                },
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

        // Update header
        let headerHtml = '<tr><th>Tanggal</th>';
        if (category === 'all') {
            headerHtml += `
            <th class="text-center">Bugs & Error</th>
            <th class="text-center">User Issues</th>
            <th class="text-center">Total</th>
            <th class="text-center">Action</th>`;
        } else if (category === 'bugs') {
            headerHtml += `
            <th class="text-center">Total</th>
            <th class="text-center">Open</th>
            <th class="text-center">Action</th>`;
        } else if (category === 'issues') {
            headerHtml += `
            <th class="text-center">Total</th>
            <th class="text-center">Open</th>
            <th class="text-center">Action</th>`;
        }
        headerHtml += '</tr>';
        $('#summary_thead').html(headerHtml);

        // Build maps
        const bugsMap = {};
        const bugsOpenMap = {};
        const issuesMap = {};
        const issuesOpenMap = {};

        data.bugs.forEach(i => {
            bugsMap[i.date] = parseInt(i.total);
        });
        data.bugs_open.forEach(i => {
            bugsOpenMap[i.date] = parseInt(i.total);
        });
        data.issues.forEach(i => {
            issuesMap[i.date] = parseInt(i.total);
        });
        data.issues_open.forEach(i => {
            issuesOpenMap[i.date] = parseInt(i.total);
        });

        const allDates = new Set([
            ...data.bugs.map(i => i.date),
            ...data.bugs_open.map(i => i.date),
            ...data.issues.map(i => i.date),
            ...data.issues_open.map(i => i.date),
        ]);
        const sortedDates = Array.from(allDates).sort();

        const currentClientId = $('#client_id').val();
        const selectedDates = weeklyPicker.selectedDates;
        const dateFrom = selectedDates.length === 2 ? formatDate(selectedDates[0]) : null;

        if (dateFrom && currentClientId) {
            $.ajax({
                url: siteurl + 'report_issue/get_carry_over_count',
                type: 'GET',
                data: {
                    client_id: currentClientId,
                    date_from: dateFrom
                },
                dataType: 'json',
                success: function(carry) {
                    carryOverData = carry;
                    _buildWeeklyTableBody(
                        sortedDates, bugsMap, bugsOpenMap, issuesMap, issuesOpenMap,
                        category, currentClientId, dateFrom, carry
                    );
                },
                error: function() {
                    carryOverData = null;
                    _buildWeeklyTableBody(
                        sortedDates, bugsMap, bugsOpenMap, issuesMap, issuesOpenMap,
                        category, currentClientId, dateFrom, null
                    );
                }
            });
        } else {
            carryOverData = null;
            _buildWeeklyTableBody(
                sortedDates, bugsMap, bugsOpenMap, issuesMap, issuesOpenMap,
                category, currentClientId, dateFrom, null
            );
        }
    }

    function _buildWeeklyTableBody(
        sortedDates, bugsMap, bugsOpenMap, issuesMap, issuesOpenMap,
        category, currentClientId, dateFrom, carry
    ) {
        let tableHtml = '';

        const carryBugs = (carry && carry.bugs) ? parseInt(carry.bugs) : 0;
        const carryIssues = (carry && carry.issues) ? parseInt(carry.issues) : 0;
        const carryTotal = carryBugs + carryIssues;

        // ── Baris Carry Over ──
        if (carry && carry.total > 0) {
            const viewCarryBtn = (cat) => `
            <button type="button" class="btn btn-sm btn-warning btn-view-detail"
                    data-client-id="${currentClientId}"
                    data-date="carry_over"
                    data-category="${cat}"
                    data-date-from="${dateFrom}"
                    data-mode="day">
                <i class="ti ti-eye"></i> View
            </button>`;

            let carryRowHtml = `<tr style="background-color:#fff0e6; font-weight:600;">
            <td>
                ⏳ <strong>Carry Over</strong>
                <div style="font-size:10px; color:#888; font-weight:normal;">
                    Open ticket dari sebelum periode ini
                </div>
            </td>`;

            if (category === 'all') {
                carryRowHtml += `
                <td class="text-center">${carryBugs}</td>
                <td class="text-center">${carryIssues}</td>
                <td class="text-center"><strong>${carryTotal}</strong></td>
                <td class="text-center">${viewCarryBtn('all')}</td>`;
            } else if (category === 'bugs') {
                carryRowHtml += `
                <td class="text-center"><strong>${carryBugs}</strong></td>
                <td class="text-center">${carryBugs}</td>
                <td class="text-center">${viewCarryBtn('bugs')}</td>`;
            } else if (category === 'issues') {
                carryRowHtml += `
                <td class="text-center"><strong>${carryIssues}</strong></td>
                <td class="text-center">${carryIssues}</td>
                <td class="text-center">${viewCarryBtn('issues')}</td>`;
            }

            carryRowHtml += '</tr>';
            tableHtml += carryRowHtml;
        }

        // ── Tidak ada data sama sekali ──
        if (sortedDates.length === 0 && carryTotal === 0) {
            let noDataColspan = category === 'all' ? 5 : 4;
            $('#summary_tbody').html(
                `<tr><td colspan="${noDataColspan}" class="text-center">Tidak ada data</td></tr>`
            );
            _renderWeeklyFooter(category, 0, 0, 0, 0, 0, 0);
            return;
        }

        // ── Baris per hari ──
        let totalBugs = 0,
            totalBugsOpen = 0,
            totalIssues = 0,
            totalIssuesOpen = 0;

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
                            data-category="${category}"
                            data-date-from="${dateFrom}"
                            data-mode="day">
                        <i class="ti ti-eye"></i> View
                    </button>
                </td>`;
            } else if (category === 'bugs') {
                rowHtml += `
                <td class="text-center"><strong>${bugs}</strong></td>
                <td class="text-center">${bugsOpen}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info btn-view-detail"
                            data-client-id="${currentClientId}"
                            data-date="${date}"
                            data-category="${category}"
                            data-date-from="${dateFrom}"
                            data-mode="day">
                        <i class="ti ti-eye"></i> View
                    </button>
                </td>`;
            } else if (category === 'issues') {
                rowHtml += `
                <td class="text-center"><strong>${issues}</strong></td>
                <td class="text-center">${issuesOpen}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info btn-view-detail"
                            data-client-id="${currentClientId}"
                            data-date="${date}"
                            data-category="${category}"
                            data-date-from="${dateFrom}"
                            data-mode="day">
                        <i class="ti ti-eye"></i> View
                    </button>
                </td>`;
            }

            rowHtml += '</tr>';
            tableHtml += rowHtml;
        });

        $('#summary_tbody').html(tableHtml);

        _renderWeeklyFooter(
            category,
            totalBugs, totalBugsOpen,
            totalIssues, totalIssuesOpen,
            carryBugs, carryIssues
        );
    }

    function _renderWeeklyFooter(
        category,
        totalBugs, totalBugsOpen,
        totalIssues, totalIssuesOpen,
        carryBugs = 0, carryIssues = 0
    ) {
        const grandBugs = totalBugs + carryBugs;
        const grandIssues = totalIssues + carryIssues;
        const grandBugsOpen = totalBugsOpen + carryBugs;
        const grandIssuesOpen = totalIssuesOpen + carryIssues;

        let footerHtml = '<tr><th>Total</th>';

        if (category === 'all') {
            footerHtml += `
            <th class="text-center">${grandBugs}</th>
            <th class="text-center">${grandIssues}</th>
            <th class="text-center">${grandBugs + grandIssues}</th>
            <th></th>`;
        } else if (category === 'bugs') {
            footerHtml += `
            <th class="text-center">${grandBugs}</th>
            <th class="text-center">${grandBugsOpen}</th>
            <th></th>`;
        } else if (category === 'issues') {
            footerHtml += `
            <th class="text-center">${grandIssues}</th>
            <th class="text-center">${grandIssuesOpen}</th>
            <th></th>`;
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

        const url = siteurl + 'report_issue/print_monthly_report?client_id=' + client_id +
            '&date_from=' + date_from + '&date_to=' + date_to;

        window.open(url, '_blank');
    });

    // Handle Export PDF Button Click (Tahunan)
    $('#btn_export_yearly_pdf').click(function() {
        const client_id = $('#client_id').val();
        const selectedYear = parseInt($('#date_yearly').val());

        if (!selectedYear) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Data belum tersedia.',
                confirmButtonText: 'OK'
            });
            return;
        }

        const today = new Date();
        const date_from = formatDate(new Date(selectedYear, 0, 1));
        const date_to = (selectedYear === today.getFullYear()) ?
            formatDate(today) :
            formatDate(new Date(selectedYear, 11, 31));

        const url = siteurl + 'report_issue/print_yearly_report?client_id=' + client_id +
            '&date_from=' + date_from + '&date_to=' + date_to;

        window.open(url, '_blank');
    });

    // ===== PRIORITY TICKETS =====
    (function() {
        var statusLabel = {
            0: ['Open', 's-open'],
            1: ['Process', 's-process'],
            2: ['Pending', 's-pending'],
            6: ['Revisi', 's-revisi']
        };

        // Toggle accordion
        $('#prio_toggle_btn').on('click', function() {
            var body = $('#prio_body');
            var chevron = $('#prio_chevron');
            var label = $('#prio_toggle_label');

            if (body.hasClass('collapsed')) {
                body.removeClass('collapsed');
                chevron.removeClass('collapsed');
                label.text('Sembunyikan');
            } else {
                body.addClass('collapsed');
                chevron.addClass('collapsed');
                label.text('Tampilkan');
            }
        });

        function renderPriorityCards(tickets) {
            var rankClass = ['rank-1', 'rank-2', 'rank-3'];
            var html = '';

            if (!tickets || tickets.length === 0) {
                // Tidak ada tiket → auto collapse
                $('#prio_body').addClass('collapsed');
                $('#prio_chevron').addClass('collapsed');
                $('#prio_toggle_label').text('Tampilkan');
                $('#prio_subtitle').text('Tidak ada tiket aktif saat ini');

                html = '<div class="col-12"><div class="prio-empty">' +
                    '<i class="ti ti-mood-happy"></i>' +
                    '<p>Tidak ada tiket saat ini. Semua beres!</p>' +
                    '</div></div>';
            } else {
                // Ada tiket → tetap expand
                $('#prio_subtitle').text(tickets.length + ' tiket aktif perlu dikerjakan');

                tickets.forEach(function(t, i) {
                    var st = statusLabel[t.status] || ['Unknown', 's-open'];
                    var rClass = rankClass[i] || '';
                    var due = t.due_date ? t.due_date.substring(0, 10) : '-';

                    html += '<div class="col-md-4 mb-3">' +
                        '<div class="prio-card ' + rClass + '">' +
                        '<div class="rank-badge">' + (i + 1) + '</div>' +
                        '<p class="prio-ticket-no">' + (t.no_ticket || '-') + '</p>' +
                        '<p class="prio-report">' + t.report + '</p>' +
                        '<div class="prio-meta-row">' +
                        '<i class="ti ti-calendar"></i>' +
                        '<span>Due: ' + due + '</span>' +
                        '</div>' +
                        '<div class="prio-meta-row">' +
                        '<i class="ti ti-tag"></i>' +
                        '<span>' + (t.sub_category_name || '-') + '</span>' +
                        '</div>' +
                        '<div class="prio-footer">' +
                        '<span class="prio-client-pill">' + (t.client_name || '-') + '</span>' +
                        '<div style="display:flex; align-items:center; gap:6px;">' +
                        '<span class="prio-status ' + st[1] + '">' + st[0] + '</span>' +
                        '<a href="' + siteurl + 'ticket/view_ticket/' + t.id + '" ' +
                        'class="btn btn-sm btn-outline-secondary" ' +
                        'style="padding:2px 8px; font-size:11px; border-radius:20px; line-height:1.6;">' +
                        '<i class="ti ti-eye" style="font-size:11px;"></i> Detail' +
                        '</a>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                });
            }

            $('#prio_cards_row').html(html);
            $('#prio_loading').hide();
            $('#prio_content').show();
        }

        $.ajax({
            url: siteurl + 'report_issue/get_my_priorities',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                renderPriorityCards(res);
            },
            error: function() {
                $('#prio_loading').hide();
                $('#prio_content').show();
                $('#prio_cards_row').html(
                    '<div class="col-12"><div class="prio-empty">' +
                    '<i class="ti ti-alert-circle"></i>' +
                    '<p>Gagal memuat data prioritas.</p>' +
                    '</div></div>'
                );
            }
        });
    })();
    // ===== END PRIORITY TICKETS =====
</script>