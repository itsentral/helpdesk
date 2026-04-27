<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
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

    /* === FILTER PANEL === */
    .pd-filter-panel {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 1.25rem;
    }

    .pd-filter-title {
        font-size: 13px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pd-filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
    }

    .pd-filter-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .pd-filter-label {
        font-size: 11px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pd-date-input {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 13px;
        color: #343a40;
        background: #f8f9fa;
        min-width: 130px;
        outline: none;
        transition: border-color 0.2s;
    }

    .pd-date-input:focus {
        border-color: #534AB7;
        background: #fff;
    }

    /* Subcategory checklist */
    .pd-subcat-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }

    .pd-subcat-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1.5px solid #dee2e6;
        font-size: 12px;
        color: #495057;
        cursor: pointer;
        background: #fff;
        transition: all 0.15s;
        user-select: none;
    }

    .pd-subcat-chip input[type="checkbox"] {
        display: none;
    }

    .pd-subcat-chip.checked {
        background: #EEEDFE;
        border-color: #534AB7;
        color: #534AB7;
        font-weight: 600;
    }

    .pd-subcat-chip:hover {
        border-color: #534AB7;
        color: #534AB7;
    }

    .pd-apply-btn {
        background: #534AB7;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .pd-apply-btn:hover {
        background: #3f37a0;
    }

    /* === SECTION HEADER === */
    .pd-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .pd-section-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pd-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #EEEDFE;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pd-title {
        font-size: 15px;
        font-weight: 600;
        margin: 0;
    }

    .pd-subtitle {
        font-size: 12px;
        color: #6c757d;
        margin: 0;
    }

    /* === STAT CARDS === */
    .pd-stat-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        height: 100%;
        transition: box-shadow 0.2s;
    }

    .pd-stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    }

    .pd-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pd-stat-icon.total {
        background: #EEEDFE;
        color: #534AB7;
    }

    .pd-stat-icon.open {
        background: #FCEBEB;
        color: #A32D2D;
    }

    .pd-stat-icon.process {
        background: #E6F1FB;
        color: #185FA5;
    }

    .pd-stat-icon.done {
        background: #E9F7EF;
        color: #196F3D;
    }

    .pd-stat-icon.pending {
        background: #FAEEDA;
        color: #854F0B;
    }

    .pd-stat-number {
        font-size: 22px;
        font-weight: 700;
        color: #212529;
        line-height: 1;
        margin-bottom: 2px;
    }

    .pd-stat-label {
        font-size: 11px;
        color: #6c757d;
        margin: 0;
    }

    /* === CHART WRAPPER === */
    .pd-chart-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 1.25rem;
    }

    .pd-chart-title {
        font-size: 13px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 14px;
    }

    /* === TABLE === */
    .pd-table-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        overflow: hidden;
    }

    .pd-table-header {
        padding: 14px 18px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pd-table-title {
        font-size: 13px;
        font-weight: 600;
        color: #343a40;
        margin: 0;
    }

    .pd-table-count {
        font-size: 12px;
        color: #6c757d;
        background: #f1f3f5;
        padding: 2px 10px;
        border-radius: 20px;
    }

    .pd-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .pd-table thead th {
        background: #f8f9fa;
        padding: 10px 14px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-bottom: 1px solid #e9ecef;
        white-space: nowrap;
    }

    .pd-table tbody tr {
        transition: background 0.15s;
        border-bottom: 1px solid #f1f3f5;
    }

    .pd-table tbody tr:last-child {
        border-bottom: none;
    }

    .pd-table tbody tr:hover {
        background: #f8f9ff;
    }

    .pd-table td {
        padding: 10px 14px;
        color: #343a40;
        vertical-align: middle;
    }

    .pd-ticket-no {
        font-size: 11px;
        color: #6c757d;
    }

    .pd-report-text {
        font-weight: 500;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 260px;
    }

    .pd-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .ps-open {
        background: #FCEBEB;
        color: #A32D2D;
    }

    .ps-process {
        background: #E6F1FB;
        color: #185FA5;
    }

    .ps-pending {
        background: #FAEEDA;
        color: #854F0B;
    }

    .ps-cancel {
        background: #f1f3f5;
        color: #6c757d;
    }

    .ps-done {
        background: #E9F7EF;
        color: #196F3D;
    }

    .ps-close {
        background: #343a40;
        color: #fff;
    }

    .ps-revisi {
        background: #F4C0D1;
        color: #72243E;
    }

    .pd-subcat-tag {
        font-size: 11px;
        background: #f1f3f5;
        color: #495057;
        padding: 2px 7px;
        border-radius: 4px;
    }

    /* === SKELETON === */
    .pd-skeleton-line {
        border-radius: 6px;
        animation: pd-skeleton-anim 1s linear infinite alternate;
    }

    .pd-skeleton-box {
        border-radius: 12px;
        animation: pd-skeleton-anim 1s linear infinite alternate;
    }

    @keyframes pd-skeleton-anim {
        0% {
            background-color: hsl(200, 20%, 82%);
        }

        100% {
            background-color: hsl(200, 20%, 95%);
        }
    }

    /* Table scroll on mobile */
    .pd-table-scroll {
        overflow-x: auto;
    }

    /* Empty state */
    .pd-empty {
        text-align: center;
        padding: 2.5rem 1rem;
    }

    .pd-empty i {
        font-size: 36px;
        color: #ced4da;
        display: block;
        margin-bottom: 8px;
    }

    .pd-empty p {
        font-size: 13px;
        color: #6c757d;
        margin: 0;
    }

    .pd-welcome {
        text-align: center;
        padding: 40px;
    }

    .pd-welcome-icon {
        font-size: 32px;
        color: #534AB7;
        margin-bottom: 10px;
    }

    .pd-welcome-text {
        font-size: 13px;
        color: #6c757d;
        margin-top: 8px;
        line-height: 1.6;
    }

    .pd-project-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 14px 16px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: box-shadow 0.2s, transform 0.15s;
    }

    .pd-project-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .pd-badge {
        font-size: 10px;
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 600;
    }
</style>


<?php if (($is_programmer == 1 || $is_ba == 1) && $is_exclude != 1): ?>
    <div class="card">
        <div class="card-body">
            <!-- ===== MY PRIORITY TICKETS ===== -->
            <div id="priority_section" style="margin-bottom: 1.5rem;">

                <div class="prio-header" style="justify-content: space-between;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div class="prio-header-icon">
                            <i class="ti ti-star" style="font-size:18px; color:#534AB7;"></i>
                        </div>
                        <div>
                            <p class="prio-title">My Top Priority Tickets</p>
                            <p class="prio-subtitle" id="prio_subtitle">
                                3 tiket teratas yang harus segera dikerjakan
                            </p>
                        </div>
                    </div>
                    <button class="prio-toggle-btn" id="prio_toggle_btn">
                        <span id="prio_toggle_label">Sembunyikan</span>
                        <i class="ti ti-chevron-down prio-chevron" id="prio_chevron"></i>
                    </button>
                </div>

                <div class="prio-body" id="prio_body">
                    <div id="prio_loading" class="row">
                        <div class="col-md-4">
                            <div class="prio-skeleton"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="prio-skeleton"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="prio-skeleton"></div>
                        </div>
                    </div>

                    <div id="prio_content" style="display:none;">
                        <div class="row" id="prio_cards_row"></div>
                    </div>
                </div>
            </div>
            <!-- ===== END ===== -->
        </div>
    </div>

<?php elseif ($is_admin == 1 || $is_exclude == 1 || ($is_programmer == 0 && $is_ba == 0)): ?>

    <div class="card">
        <div class="card-body pd-welcome">
            <div class="pd-welcome-icon">
                <i class="ti ti-ticket"></i>
            </div>
            <h4>Selamat Datang 👋</h4>
            <p class="pd-welcome-text">
                Semua solusi dimulai dari satu tiket.<br>
                Klik, laporkan, dan biarkan sistem bekerja untuk Anda.
            </p>
        </div>
    </div>

<?php endif; ?>

<?php if (($is_programmer == 1 || $is_ba == 1 || $is_admin == 1) && $is_exclude != 1): ?>
    <div class="card mt-3">
        <div class="card-body">

            <!-- Section Header -->
            <div class="pd-section-header mb-3">
                <div class="pd-section-left">
                    <div class="pd-header-icon">
                        <i class="ti ti-bug" style="font-size:18px; color:#534AB7;"></i>
                    </div>
                    <div>
                        <p class="pd-title">Project Bugs & Issues Dashboard</p>
                        <p class="pd-subtitle" id="pd_subtitle">Ringkasan bugs, error, dan user issue semua project</p>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div class="pd-filter-panel">
                <div class="pd-filter-title">
                    <i class="ti ti-filter" style="font-size:14px; color:#534AB7;"></i>
                    Filter Data
                </div>
                <div class="pd-filter-row">
                    <!-- Date From -->
                    <div class="pd-filter-group">
                        <span class="pd-filter-label">Dari Tanggal</span>
                        <input type="text" id="pd_date_from" class="pd-date-input" placeholder="YYYY-MM-DD" readonly>
                    </div>
                    <!-- Date To -->
                    <div class="pd-filter-group">
                        <span class="pd-filter-label">Sampai Tanggal</span>
                        <input type="text" id="pd_date_to" class="pd-date-input" placeholder="YYYY-MM-DD" readonly>
                    </div>

                    <div class="pd-filter-group" style="justify-content:flex-end;">
                        <span class="pd-filter-label">&nbsp;</span>
                        <label class="pd-subcat-chip" id="pd_alltime_label" style="border-radius:8px;">
                            <input type="checkbox" id="pd_all_time">
                            <i class="ti ti-infinity" style="font-size:11px;"></i> Semua Waktu
                        </label>
                    </div>
                    <!-- Sub Category -->
                    <div class="pd-filter-group" style="flex:1; min-width:200px;">
                        <span class="pd-filter-label">Sub Category</span>
                        <div class="pd-subcat-wrapper" id="pd_subcat_chips">
                            <!-- chips dirender oleh JS -->
                            <div class="pd-skeleton-line" style="width:80px;height:28px;"></div>
                            <div class="pd-skeleton-line" style="width:90px;height:28px;"></div>
                            <div class="pd-skeleton-line" style="width:70px;height:28px;"></div>
                        </div>
                    </div>
                    <!-- Apply Button -->
                    <div class="pd-filter-group">
                        <span class="pd-filter-label">&nbsp;</span>
                        <button class="pd-apply-btn" id="pd_apply_btn" onclick="pdLoadData()">
                            <i class="ti ti-search" style="font-size:13px;"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="row mb-3" id="pd_stats_row">
                <!-- Skeleton -->
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="col-6 col-md mb-2">
                        <div class="pd-stat-card">
                            <div class="pd-skeleton-box" style="width:40px;height:40px;"></div>
                            <div style="flex:1;">
                                <div class="pd-skeleton-line mb-1" style="width:50px;height:20px;"></div>
                                <div class="pd-skeleton-line" style="width:70px;height:12px;"></div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Chart Row -->
            <div class="row mb-3">
                <div class="col-md-8 mb-3 mb-md-0">
                    <div class="pd-chart-card" style="height:100%;">
                        <div class="pd-chart-title">Tiket per Project</div>
                        <!-- <div class="pd-chart-title">Tren Tiket per Hari</div> -->
                        <div id="pd_chart_trend_wrap">
                            <div class="pd-skeleton-box" style="height:220px;"></div>
                        </div>
                        <canvas id="pd_chart_trend" style="display:none; max-height:220px;"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pd-chart-card" style="height:100%;">
                        <div class="pd-chart-title">Status Distribution</div>
                        <div id="pd_chart_donut_wrap">
                            <div class="pd-skeleton-box" style="height:220px;"></div>
                        </div>
                        <canvas id="pd_chart_donut" style="display:none; max-height:220px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tambahkan ini setelah chart row -->
            <div class="pd-chart-card mb-3">
                <div class="pd-chart-title">Tiket per Sub Category</div>
                <div id="pd_chart_subcat_wrap">
                    <div class="pd-skeleton-box" style="height:180px;"></div>
                </div>
                <canvas id="pd_chart_subcat" style="display:none;"></canvas>
            </div>

            <!-- Table -->
            <div class="pd-table-card">
                <div class="pd-table-header">
                    <span class="pd-table-title">Daftar Tiket</span>
                    <span class="pd-table-count" id="pd_table_count">-</span>
                </div>
                <!-- Skeleton Table -->
                <div id="pd_table_skeleton" style="padding:12px 18px;">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="pd-skeleton-line mb-2" style="width:100%;height:36px;"></div>
                    <?php endfor; ?>
                </div>
                <!-- Real Table -->
                <div id="pd_table_wrap" style="display:none;" class="pd-table-scroll">
                    <table class="pd-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No Tiket</th>
                                <th>Laporan</th>
                                <th>Client</th>
                                <th>Sub Category</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>PIC</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="pd_table_body"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>
<!-- ===== END PROJECT BUGS & ISSUES DASHBOARD ===== -->

<!-- Modal Detail Tiket per Project -->
<div class="modal fade" id="pd_project_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div id="pd_modal_body_content">
                <div style="padding:60px;text-align:center;color:#6c757d;">
                    <i class="ti ti-loader-2 ti-spin" style="font-size:28px;"></i>
                    <p style="margin-top:10px;font-size:13px;">Memuat data...</p>
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
    is_admin = <?= $is_admin ?>;
    is_programmer = <?= $is_programmer ?>;
    is_ba = <?= $is_ba ?>;
    is_exclude = <?= $is_exclude ?>;

    $(document).ready(function() {

        // ===================================================
        // SECTION 1: PRIORITY TICKETS
        // ===================================================
        (function() {
            var statusLabel = {
                0: ['Open', 's-open'],
                1: ['Process', 's-process'],
                2: ['Pending', 's-pending'],
                6: ['Revisi', 's-revisi']
            };

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
                    $('#prio_body').addClass('collapsed');
                    $('#prio_chevron').addClass('collapsed');
                    $('#prio_toggle_label').text('Tampilkan');
                    $('#prio_subtitle').text('Tidak ada tiket aktif saat ini');
                    html = '<div class="col-12"><div class="prio-empty">' +
                        '<i class="ti ti-mood-happy"></i>' +
                        '<p>Tidak ada tiket saat ini. Semua beres!</p>' +
                        '</div></div>';
                } else {
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
                            '<div class="prio-meta-row"><i class="ti ti-calendar"></i><span>Due: ' + due + '</span></div>' +
                            '<div class="prio-meta-row"><i class="ti ti-tag"></i><span>' + (t.sub_category_name || '-') + '</span></div>' +
                            '<div class="prio-footer">' +
                            '<span class="prio-client-pill">' + (t.client_name || '-') + '</span>' +
                            '<div style="display:flex;align-items:center;gap:6px;">' +
                            '<span class="prio-status ' + st[1] + '">' + st[0] + '</span>' +
                            '<a href="' + siteurl + 'ticket/view_ticket/' + t.id + '" class="btn btn-sm btn-outline-secondary" style="padding:2px 8px;font-size:11px;border-radius:20px;line-height:1.6;">' +
                            '<i class="ti ti-eye" style="font-size:11px;"></i> Detail</a>' +
                            '</div></div></div></div>';
                    });
                }
                $('#prio_cards_row').html(html);
                $('#prio_loading').hide();
                $('#prio_content').show();
            }

            $.ajax({
                url: siteurl + 'dashboard/get_my_priorities',
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


        // ===================================================
        // SECTION 2: PROJECT BUGS & ISSUES DASHBOARD
        // ===================================================
        (function() {

            var DEFAULT_SUBCATS = [
                'User Issue', 'Bugs Program',
                'Bugs Konsep'
            ];

            var statusMap = {
                0: ['Open', 'ps-open'],
                1: ['Process', 'ps-process'],
                2: ['Pending', 'ps-pending'],
                3: ['Cancel', 'ps-cancel'],
                4: ['Done', 'ps-done'],
                5: ['Close', 'ps-close'],
                6: ['Revisi', 'ps-revisi'],
            };

            var chartProject = null;
            var chartDonut = null;
            var chartSubcat = null;
            var allClientsData = [];

            // ── Flatpickr ──────────────────────────────────
            var today = new Date();
            var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            flatpickr('#pd_date_from', {
                dateFormat: 'Y-m-d',
                defaultDate: firstDay,
                allowInput: false
            });
            flatpickr('#pd_date_to', {
                dateFormat: 'Y-m-d',
                defaultDate: today,
                allowInput: false
            });

            $('#pd_all_time').on('change', function() {
                var isAllTime = $(this).is(':checked');
                $('#pd_alltime_label').toggleClass('checked', isAllTime);
                $('#pd_date_from').prop('disabled', isAllTime).css('opacity', isAllTime ? 0.4 : 1);
                $('#pd_date_to').prop('disabled', isAllTime).css('opacity', isAllTime ? 0.4 : 1);
            });

            // ── Helper ─────────────────────────────────────
            function escHtml(str) {
                return $('<div>').text(str || '').html();
            }

            function isDefault(name) {
                return DEFAULT_SUBCATS.some(function(d) {
                    return d.toLowerCase() === (name || '').toLowerCase();
                });
            }

            function getSelectedSubcats() {
                var names = [];
                $('#pd_subcat_chips input:checked').each(function() {
                    names.push($(this).val());
                });
                return names;
            }

            // ── Sub Category Chips ─────────────────────────
            function renderSubcatChips(subcats) {
                var wrapper = $('#pd_subcat_chips');
                wrapper.empty();
                subcats.forEach(function(sc) {
                    var checked = isDefault(sc.sub_name);
                    var chip = $('<label class="pd-subcat-chip ' + (checked ? 'checked' : '') + '">' +
                        '<input type="checkbox" value="' + escHtml(sc.sub_name) + '" ' + (checked ? 'checked' : '') + '> ' +
                        '<i class="ti ti-tag" style="font-size:11px;"></i> ' +
                        escHtml(sc.sub_name) + '</label>');

                    chip.find('input').on('change', function() {
                        $(this).closest('label').toggleClass('checked', $(this).is(':checked'));
                    });

                    wrapper.append(chip);
                });
            }

            $.ajax({
                url: siteurl + 'dashboard/get_all_subcategories',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    renderSubcatChips(res);
                    pdLoadData();
                },
                error: function() {
                    $('#pd_subcat_chips').html('<span style="font-size:12px;color:#dc3545;">Gagal memuat sub category</span>');
                }
            });

            // ── Stat Cards ─────────────────────────────────
            function showStatSkeleton() {
                var html = '';
                for (var i = 0; i < 5; i++) {
                    html += '<div class="col-6 col-md mb-2"><div class="pd-stat-card">' +
                        '<div class="pd-skeleton-box" style="width:40px;height:40px;flex-shrink:0;"></div>' +
                        '<div style="flex:1;"><div class="pd-skeleton-line mb-1" style="width:50px;height:20px;"></div>' +
                        '<div class="pd-skeleton-line" style="width:70px;height:12px;"></div></div>' +
                        '</div></div>';
                }
                $('#pd_stats_row').html(html);
            }

            function renderStats(summary) {
                var defs = [{
                        key: 'total',
                        icon: 'ti-ticket',
                        cls: 'total',
                        label: 'Total Tiket'
                    },
                    {
                        key: 'open',
                        icon: 'ti-alert-circle',
                        cls: 'open',
                        label: 'Open'
                    },
                    {
                        key: 'process',
                        icon: 'ti-settings',
                        cls: 'process',
                        label: 'Process'
                    },
                    {
                        key: 'done',
                        icon: 'ti-circle-check',
                        cls: 'done',
                        label: 'Done'
                    },
                    {
                        key: 'pending',
                        icon: 'ti-clock',
                        cls: 'pending',
                        label: 'Pending'
                    },
                ];
                var html = '';
                defs.forEach(function(d) {
                    html += '<div class="col-6 col-md mb-2"><div class="pd-stat-card">' +
                        '<div class="pd-stat-icon ' + d.cls + '"><i class="ti ' + d.icon + '" style="font-size:18px;"></i></div>' +
                        '<div><div class="pd-stat-number">' + (summary[d.key] || 0) + '</div>' +
                        '<p class="pd-stat-label">' + d.label + '</p></div>' +
                        '</div></div>';
                });
                $('#pd_stats_row').html(html);
            }

            // ── Chart: Stacked Bar per Project ─────────────
            function renderProjectChart(clients) {
                // Hanya tampilkan client yang punya tiket di chart
                var clientsWithTickets = clients.filter(function(c) {
                    return c.total > 0;
                });

                $('#pd_chart_trend_wrap').hide();
                var canvas = $('#pd_chart_trend').show()[0];
                var ctx = canvas.getContext('2d');
                if (chartProject) {
                    chartProject.destroy();
                }

                if (!clientsWithTickets || clientsWithTickets.length === 0) {
                    $('#pd_chart_trend_wrap').show().html('<div class="pd-empty" style="padding:40px 0;"><i class="ti ti-mood-smile"></i><p>Tidak ada data tiket pada periode ini.</p></div>');
                    $('#pd_chart_trend').hide();
                    return;
                }

                var barHeight = 30;
                canvas.style.maxHeight = Math.max(220, clientsWithTickets.length * barHeight + 80) + 'px';

                chartProject = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: clientsWithTickets.map(function(c) {
                            return c.client_name;
                        }),
                        datasets: [{
                                label: 'Open',
                                data: clientsWithTickets.map(function(c) {
                                    return c.open;
                                }),
                                backgroundColor: '#E24B4A',
                                borderRadius: 3
                            },
                            {
                                label: 'Process',
                                data: clientsWithTickets.map(function(c) {
                                    return c.process;
                                }),
                                backgroundColor: '#378ADD',
                                borderRadius: 3
                            },
                            {
                                label: 'Pending',
                                data: clientsWithTickets.map(function(c) {
                                    return c.pending;
                                }),
                                backgroundColor: '#EF9F27',
                                borderRadius: 3
                            },
                            {
                                label: 'Done',
                                data: clientsWithTickets.map(function(c) {
                                    return c.done;
                                }),
                                backgroundColor: '#27AE60',
                                borderRadius: 3
                            },
                            {
                                label: 'Revisi',
                                data: clientsWithTickets.map(function(c) {
                                    return c.revisi;
                                }),
                                backgroundColor: '#F4A0BB',
                                borderRadius: 3
                            },
                        ]
                    },
                    options: {
                        responsive: true,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 11
                                    },
                                    boxWidth: 12
                                }
                            },
                            datalabels: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                stacked: true,
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // ── Chart: Donut Status ────────────────────────
            function renderDonutChart(statusCounts) {
                $('#pd_chart_donut_wrap').hide();
                var canvas = $('#pd_chart_donut').show()[0];
                var ctx = canvas.getContext('2d');
                if (chartDonut) {
                    chartDonut.destroy();
                }

                if (!statusCounts || statusCounts.length === 0) {
                    $('#pd_chart_donut_wrap').show().html('<div class="pd-empty" style="padding:40px 0;"><i class="ti ti-mood-smile"></i><p>Tidak ada data.</p></div>');
                    $('#pd_chart_donut').hide();
                    return;
                }

                chartDonut = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: statusCounts.map(function(s) {
                            return s.label;
                        }),
                        datasets: [{
                            data: statusCounts.map(function(s) {
                                return s.count;
                            }),
                            backgroundColor: ['#E24B4A', '#378ADD', '#EF9F27', '#adb5bd', '#27AE60', '#343a40', '#F4A0BB'],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 11
                                    },
                                    boxWidth: 12,
                                    padding: 10
                                }
                            },
                            datalabels: {
                                display: false
                            }
                        }
                    }
                });
            }

            // ── Chart: Horizontal Bar per Sub Category ─────
            function renderSubcatChart(subcatCounts) {
                $('#pd_chart_subcat_wrap').hide();
                var canvas = $('#pd_chart_subcat').show()[0];
                var ctx = canvas.getContext('2d');
                if (chartSubcat) {
                    chartSubcat.destroy();
                }

                if (!subcatCounts || subcatCounts.length === 0) {
                    $('#pd_chart_subcat_wrap').show().html('<div class="pd-empty" style="padding:30px 0;"><i class="ti ti-mood-smile"></i><p>Tidak ada data sub category.</p></div>');
                    $('#pd_chart_subcat').hide();
                    return;
                }

                canvas.style.maxHeight = Math.max(150, subcatCounts.length * 32 + 60) + 'px';

                chartSubcat = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: subcatCounts.map(function(s) {
                            return s.name;
                        }),
                        datasets: [{
                            label: 'Jumlah Tiket',
                            data: subcatCounts.map(function(s) {
                                return s.count;
                            }),
                            backgroundColor: 'rgba(83,74,183,0.75)',
                            borderRadius: 5,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            datalabels: {
                                display: true,
                                anchor: 'end',
                                align: 'end',
                                font: {
                                    size: 11,
                                    weight: '600'
                                },
                                color: '#343a40',
                                formatter: function(val) {
                                    return val;
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }

            // ── Project Cards Grid ─────────────────────────
            function renderProjectSection(clients, subcatCounts) {
                allClientsData = clients; // tetap simpan untuk keperluan modal

                renderProjectChart(clients);
                renderSubcatChart(subcatCounts || []);

                // Render cards via server
                var dateFrom = $('#pd_date_from').val();
                var dateTo = $('#pd_date_to').val();
                var allTime = $('#pd_all_time').is(':checked') ? '1' : '0';
                var subcatNames = getSelectedSubcats();

                $('#pd_table_skeleton').show();
                $('#pd_table_wrap').hide();

                $.ajax({
                    url: siteurl + 'dashboard/render_project_cards',
                    type: 'GET',
                    data: {
                        date_from: dateFrom,
                        date_to: dateTo,
                        all_time: allTime,
                        subcat_names: subcatNames,
                    },
                    success: function(html) {
                        $('#pd_table_skeleton').hide();
                        $('#pd_table_wrap').show().html(html);
                        $('#pd_table_count').text(clients.filter(c => c.total > 0).length + ' project');
                    },
                    error: function() {
                        $('#pd_table_skeleton').hide();
                        $('#pd_table_wrap').show().html(
                            '<div class="pd-empty"><i class="ti ti-alert-circle"></i><p>Gagal memuat cards project.</p></div>'
                        );
                    }
                });
            }

            // ── Modal (AJAX → render HTML dari controller) ─────
            var projectModalBS = new bootstrap.Modal(document.getElementById('pd_project_modal'));

            window.pdOpenModal = function(idx) {
                var c = allClientsData[idx];
                if (!c) return;

                var dateFrom = $('#pd_date_from').val();
                var dateTo = $('#pd_date_to').val();
                var allTime = $('#pd_all_time').is(':checked') ? '1' : '0';
                var subcatNames = getSelectedSubcats();

                // Tampilkan loading di dalam modal sebelum AJAX selesai
                $('#pd_modal_body_content').html(
                    '<div style="padding:60px;text-align:center;color:#6c757d;">' +
                    '<i class="ti ti-loader-2 ti-spin" style="font-size:28px;"></i>' +
                    '<p style="margin-top:10px;font-size:13px;">Memuat data tiket...</p>' +
                    '</div>'
                );

                // Buka modal
                projectModalBS.show();

                $.ajax({
                    url: siteurl + 'dashboard/render_modal_project',
                    type: 'GET',
                    data: {
                        client_id: c.client_id,
                        client_name: c.client_name,
                        date_from: dateFrom,
                        date_to: dateTo,
                        all_time: allTime,
                        subcat_names: subcatNames,
                    },
                    success: function(html) {
                        $('#pd_modal_body_content').html(html);
                    },
                    error: function() {
                        $('#pd_modal_body_content').html(
                            '<div style="padding:40px;text-align:center;">' +
                            '<i class="ti ti-alert-circle" style="font-size:28px;color:#dc3545;"></i>' +
                            '<p style="margin-top:8px;font-size:13px;color:#6c757d;">Gagal memuat data.</p>' +
                            '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>' +
                            '</div>'
                        );
                    }
                });
            };

            window.pdCloseModal = function() {
                projectModalBS.hide();
            };

            window.pdCloseModal = function() {
                $('#pd_project_modal').hide();
                $('body').css('overflow', '');
            };

            $('#pd_project_modal').on('click', function(e) {
                if ($(e.target).is('#pd_project_modal')) pdCloseModal();
            });

            // ── MAIN LOAD ──────────────────────────────────
            window.pdLoadData = function() {
                if (!((is_admin == 1 || is_programmer == 1 || is_ba == 1) && is_exclude != 1)) {
                    return;
                }

                var dateFrom = $('#pd_date_from').val();
                var dateTo = $('#pd_date_to').val();
                var subcatNames = getSelectedSubcats();
                var allTime = $('#pd_all_time').is(':checked') ? '1' : '0';
                console.log('subcat_names yang dikirim:', subcatNames);

                if (allTime === '0' && (!dateFrom || !dateTo)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Harap lengkapi tanggal.',
                        confirmButtonColor: '#534AB7'
                    });
                    return;
                }
                if (subcatNames.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Pilih minimal satu sub category.',
                        confirmButtonColor: '#534AB7'
                    });
                    return;
                }

                showStatSkeleton();
                $('#pd_chart_trend').hide();
                $('#pd_chart_trend_wrap').show().html('<div class="pd-skeleton-box" style="height:220px;"></div>');
                $('#pd_chart_donut').hide();
                $('#pd_chart_donut_wrap').show().html('<div class="pd-skeleton-box" style="height:220px;"></div>');
                $('#pd_chart_subcat').hide();
                $('#pd_chart_subcat_wrap').show().html('<div class="pd-skeleton-box" style="height:180px;"></div>');
                $('#pd_table_wrap').hide();
                $('#pd_table_skeleton').show();

                $.ajax({
                    url: siteurl + 'dashboard/get_project_issues',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        date_from: dateFrom,
                        date_to: dateTo,
                        subcat_names: subcatNames,
                        all_time: allTime,
                    },
                    success: function(res) {

                        // console.log('response dari server:', res);
                        renderStats(res.summary || {});
                        renderProjectSection(res.clients || [], res.subcat_counts || []);
                        renderDonutChart(res.status_counts || []);
                        $('#pd_subtitle').text(
                            allTime === '1' ? 'Semua data sejak awal' : 'Data ' + dateFrom + ' s/d ' + dateTo
                        );
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal memuat data.',
                            confirmButtonColor: '#534AB7'
                        });
                        $('#pd_stats_row').html('');
                        $('#pd_chart_trend_wrap').html('');
                        $('#pd_chart_donut_wrap').html('');
                        $('#pd_chart_subcat_wrap').html('');
                        $('#pd_table_skeleton').hide();
                        $('#pd_table_wrap').show().html('<div class="pd-empty"><i class="ti ti-alert-circle"></i><p>Gagal memuat data.</p></div>');
                    }
                });
            };

        })();

    });
</script>