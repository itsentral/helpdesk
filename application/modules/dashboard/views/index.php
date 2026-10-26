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

    /* === MONITORING TAHAPAN MODUL PROJECT === */
    .pm-mod-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .pm-mod-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pm-mod-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #EEEDFE;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pm-mod-title {
        font-size: 15px;
        font-weight: 600;
        margin: 0;
    }

    .pm-mod-subtitle {
        font-size: 12px;
        color: #6c757d;
        margin: 0;
    }

    .pm-mod-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        padding: 10px 14px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
    }

    .pm-mod-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }

    .pm-mod-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        color: #495057;
        background: #fff;
        border: 1px solid #dee2e6;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }

    .pm-mod-pill:hover {
        border-color: #534AB7;
        color: #534AB7;
    }

    .pm-mod-pill.active {
        background: #534AB7;
        border-color: #534AB7;
        color: #fff;
        font-weight: 600;
    }

    .pm-mod-pill.active .badge {
        background: #fff !important;
        color: #534AB7 !important;
    }

    .pm-mod-search-box {
        position: relative;
        min-width: 220px;
    }

    .pm-mod-search-input {
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 5px 12px 5px 32px;
        font-size: 12px;
        outline: none;
        background: #fff;
        transition: border-color 0.2s;
    }

    .pm-mod-search-input:focus {
        border-color: #534AB7;
    }

    .pm-mod-search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 13px;
        pointer-events: none;
    }

    .pm-mod-table-scroll {
        max-height: 480px;
        overflow-y: auto;
        overflow-x: auto;
        position: relative;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #fff;
    }

    .pm-mod-table-scroll::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .pm-mod-table-scroll::-webkit-scrollbar-track {
        background: #f1f3f5;
        border-radius: 4px;
    }

    .pm-mod-table-scroll::-webkit-scrollbar-thumb {
        background: #ced4da;
        border-radius: 4px;
    }

    .pm-mod-table-scroll::-webkit-scrollbar-thumb:hover {
        background: #adb5bd;
    }

    .pm-mod-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
        margin-bottom: 0;
    }

    .pm-mod-table thead th {
        background-color: #f8f9fa !important;
        padding: 11px 14px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
        position: relative;
        z-index: 10;
        box-shadow: inset 0 -1px 0 #dee2e6, 0 2px 4px rgba(0, 0, 0, 0.04);
        will-change: transform;
    }

    .pm-mod-table tbody tr {
        transition: background 0.15s;
    }

    .pm-mod-table tbody tr:hover {
        background: #f8f9ff;
    }

    .pm-mod-table td {
        padding: 10px 14px;
        color: #343a40;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
    }

    .pm-mod-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .pm-mod-status-overdue {
        background: #FCEBEB;
        color: #A32D2D;
        border: 1px solid #F7C5C5;
    }

    .pm-mod-status-near {
        background: #FAEEDA;
        color: #854F0B;
        border: 1px solid #F5DCB7;
    }

    .pm-mod-status-ontrack {
        background: #E9F7EF;
        color: #196F3D;
        border: 1px solid #C8ECD5;
    }

    .pm-mod-status-nodue {
        background: #f1f3f5;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }

    .pm-mod-btn-action {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 600;
        color: #534AB7;
        background: #EEEDFE;
        border: 1px solid #D5D1FC;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.15s;
        white-space: nowrap;
    }

    .pm-mod-btn-action:hover {
        background: #534AB7;
        color: #fff;
        text-decoration: none;
    }
</style>

<?php
if ($is_admin != 1):
    if (($is_programmer == 1 || $is_ba == 1) && $is_exclude != 1): ?>
        <div class="card">
            <div class="card-body">
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
            </div>
        </div>

    <?php
    else: ?>
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

<?php
endif; ?>

<?php if (($is_programmer == 1 || $is_ba == 1 || $is_admin == 1) && $is_exclude != 1): ?>

    <!-- ===== SECTION MONITORING TAHAPAN MODUL PROJECT ===== -->
    <div class="card mt-3">
        <div class="card-body">
            <div class="pm-mod-header">
                <div class="pm-mod-header-left">
                    <div class="pm-mod-icon-box">
                        <i class="ti ti-layout-kanban" style="font-size:18px; color:#534AB7;"></i>
                    </div>
                    <div>
                        <p class="pm-mod-title">Monitoring Tahapan Modul Project</p>
                        <p class="pm-mod-subtitle" id="pm_mod_subtitle">Memuat status tahapan modul...</p>
                    </div>
                </div>
                <button class="prio-toggle-btn" id="pm_mod_toggle_btn">
                    <span id="pm_mod_toggle_label">Sembunyikan</span>
                    <i class="ti ti-chevron-down prio-chevron" id="pm_mod_chevron"></i>
                </button>
            </div>

            <div id="pm_mod_body">
                <!-- Filter bar: Pills & Search -->
                <div class="pm-mod-filter-bar" id="pm_mod_filter_bar" style="display:none;">
                    <div class="pm-mod-pills">
                        <div class="pm-mod-pill active" data-filter="all">
                            <span>Semua</span>
                            <span class="badge bg-secondary" id="pm_mod_count_all">0</span>
                        </div>
                        <div class="pm-mod-pill" data-filter="overdue">
                            <span style="color:#A32D2D;">●</span> Terlambat
                            <span class="badge bg-danger" id="pm_mod_count_overdue">0</span>
                        </div>
                        <div class="pm-mod-pill" data-filter="near_due">
                            <span style="color:#854F0B;">●</span> Mendekati Due Date (H-7)
                            <span class="badge bg-warning text-dark" id="pm_mod_count_near_due">0</span>
                        </div>
                        <div class="pm-mod-pill" data-filter="on_track">
                            <span style="color:#196F3D;">●</span> On Track
                            <span class="badge bg-success" id="pm_mod_count_on_track">0</span>
                        </div>
                    </div>
                    <div class="pm-mod-search-box">
                        <i class="ti ti-search pm-mod-search-icon"></i>
                        <input type="text" id="pm_mod_search" class="pm-mod-search-input" placeholder="Cari project, modul, PIC...">
                    </div>
                </div>

                <!-- Skeleton Loading -->
                <div id="pm_mod_loading">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="pd-skeleton-line mb-2" style="width:100%;height:38px;"></div>
                    <?php endfor; ?>
                </div>

                <!-- Table Container -->
                <div id="pm_mod_table_wrap" style="display:none;" class="pm-mod-table-scroll">
                    <table class="pm-mod-table">
                        <thead>
                            <tr>
                                <th width="35" class="text-center">#</th>
                                <th>Project & Klien</th>
                                <th>Modul</th>
                                <th>Tahapan Aktif</th>
                                <th>PIC Tahapan</th>
                                <th>Due Date & Plan MH</th>
                                <th>Status / Keterlambatan</th>
                                <th width="90" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pm_mod_table_body"></tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="pm_mod_empty" style="display:none;" class="pd-empty">
                    <i class="ti ti-mood-smile text-success" style="font-size:36px; display:block; margin-bottom:8px;"></i>
                    <p id="pm_mod_empty_text">Tidak ada modul aktif yang terlambat atau mendekati deadline. Semua beres!</p>
                </div>
            </div>
        </div>
    </div>
    <!-- ===== END SECTION MONITORING TAHAPAN MODUL PROJECT ===== -->

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
                    <!-- Category -->
                    <div class="pd-filter-group" style="flex:1; min-width:200px;">
                        <span class="pd-filter-label">Category</span>
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
                <div class="col-md-12 mb-3 mb-md-0">
                    <div class="pd-chart-card" style="height:100%;">
                        <div class="pd-chart-title">Tiket per Project</div>
                        <div id="pd_chart_trend_wrap">
                            <div class="pd-skeleton-box" style="height:220px;"></div>
                        </div>
                        <canvas id="pd_chart_trend" style="display:none; max-height:220px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="pd-chart-card mb-3">
                <div class="pd-chart-title">Tiket per Category</div>
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
                                <th>Category</th>
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
        // SECTION 1.5: MONITORING TAHAPAN MODUL PROJECT
        // ===================================================
        (function() {
            var rawItems = [];
            var currentFilter = 'all';

            $('#pm_mod_toggle_btn').on('click', function() {
                var body = $('#pm_mod_body');
                var chevron = $('#pm_mod_chevron');
                var label = $('#pm_mod_toggle_label');
                if (body.is(':visible')) {
                    body.slideUp(200);
                    chevron.addClass('collapsed');
                    label.text('Tampilkan');
                } else {
                    body.slideDown(200);
                    chevron.removeClass('collapsed');
                    label.text('Sembunyikan');
                }
            });

            function renderRows() {
                var search = ($('#pm_mod_search').val() || '').toLowerCase().trim();

                var filtered = rawItems.filter(function(item) {
                    if (currentFilter !== 'all' && item.status_type !== currentFilter) {
                        return false;
                    }
                    if (search) {
                        var matchProject = (item.project_name || '').toLowerCase().indexOf(search) !== -1;
                        var matchCode = (item.project_code || '').toLowerCase().indexOf(search) !== -1;
                        var matchClient = (item.client_name || '').toLowerCase().indexOf(search) !== -1;
                        var matchModule = (item.module_name || '').toLowerCase().indexOf(search) !== -1;
                        var matchTahapan = (item.tahapan_name || '').toLowerCase().indexOf(search) !== -1;
                        var matchPic = (item.pic_name || '').toLowerCase().indexOf(search) !== -1;
                        if (!matchProject && !matchCode && !matchClient && !matchModule && !matchTahapan && !matchPic) {
                            return false;
                        }
                    }
                    return true;
                });

                if (filtered.length === 0) {
                    $('#pm_mod_table_wrap').hide();
                    $('#pm_mod_empty').show();
                    if (search) {
                        $('#pm_mod_empty_text').text('Tidak ada data yang cocok dengan pencarian "' + search + '".');
                    } else if (currentFilter === 'overdue') {
                        $('#pm_mod_empty_text').text('Hebat! Tidak ada modul yang terlambat 🎉');
                    } else if (currentFilter === 'near_due') {
                        $('#pm_mod_empty_text').text('Tidak ada modul yang mendekati due date (H-7).');
                    } else {
                        $('#pm_mod_empty_text').text('Tidak ada modul project yang sedang aktif saat ini.');
                    }
                    return;
                }

                $('#pm_mod_empty').hide();
                $('#pm_mod_table_wrap').show();

                var html = '';
                filtered.forEach(function(item, idx) {
                    var statusBadgeClass = 'pm-mod-status-nodue';
                    var statusIcon = 'ti-calendar';

                    if (item.status_type === 'overdue') {
                        statusBadgeClass = 'pm-mod-status-overdue';
                        statusIcon = 'ti-alert-circle';
                    } else if (item.status_type === 'near_due') {
                        statusBadgeClass = 'pm-mod-status-near';
                        statusIcon = 'ti-clock-hour-4';
                    } else if (item.status_type === 'on_track') {
                        statusBadgeClass = 'pm-mod-status-ontrack';
                        statusIcon = 'ti-circle-check';
                    }

                    var picInitial = (item.pic_name || '?').substring(0, 1).toUpperCase();
                    var dueText = item.plan_due_date ? item.plan_due_date : '-';
                    var planMhText = item.plan_manhour ? item.plan_manhour + ' MH' : '-';

                    html += '<tr>' +
                        '<td class="text-center pd-index">' + (idx + 1) + '</td>' +
                        '<td>' +
                            '<div style="font-weight:600; color:#212529;">' + escHtml(item.project_name) + '</div>' +
                            '<div style="margin-top:2px; font-size:11px; color:#6c757d; display:flex; align-items:center; gap:4px;">' +
                                '<i class="ti ti-building" style="font-size:12px; color:#8c98a4;"></i>' +
                                '<span>' + escHtml(item.client_name || '-') + '</span>' +
                            '</div>' +
                        '</td>' +
                        '<td>' +
                            '<div style="font-weight:600; color:#495057; display:flex; align-items:center; gap:5px;">' +
                                '<i class="ti ti-cube" style="color:#534AB7; font-size:14px;"></i>' +
                                '<span>' + escHtml(item.module_name) + '</span>' +
                            '</div>' +
                        '</td>' +
                        '<td>' +
                            '<div style="font-weight:600; color:#212529;">Step ' + item.tahapan_order + ': ' + escHtml(item.tahapan_name) + '</div>' +
                            '<span class="pd-badge" style="font-size:10px; background:#e6f6fd; color:#0ea5e9; text-transform:uppercase;">' + escHtml(item.tahapan_role || 'General') + '</span>' +
                        '</td>' +
                        '<td>' +
                            '<div style="display:flex; align-items:center; gap:6px;">' +
                                '<span style="width:24px; height:24px; border-radius:50%; background:#EEEDFE; color:#534AB7; display:inline-flex; align-items:center; justify-content:center; font-size:10px; font-weight:700;">' + picInitial + '</span>' +
                                '<span style="font-weight:500;">' + escHtml(item.pic_name) + '</span>' +
                            '</div>' +
                        '</td>' +
                        '<td>' +
                            '<div style="font-weight:600; font-size:12px;">' + dueText + '</div>' +
                            '<div class="pd-muted" style="font-size:11px;">Plan: ' + planMhText + '</div>' +
                        '</td>' +
                        '<td>' +
                            '<span class="pm-mod-status-badge ' + statusBadgeClass + '">' +
                                '<i class="ti ' + statusIcon + '" style="font-size:12px;"></i> ' +
                                escHtml(item.status_label) +
                            '</span>' +
                        '</td>' +
                        '<td class="text-center">' +
                            '<a href="' + siteurl + 'projects_management/update/' + item.project_id + '" class="pm-mod-btn-action" title="Buka Detail Project">' +
                                '<i class="ti ti-external-link" style="font-size:11px;"></i> Detail' +
                            '</a>' +
                        '</td>' +
                    '</tr>';
                });

                $('#pm_mod_table_body').html(html);

                // Reset scroll container ke atas dan posisikan sticky header
                var tableWrap = document.getElementById('pm_mod_table_wrap');
                if (tableWrap) {
                    tableWrap.scrollTop = 0;
                }
                syncStickyHeader();
            }

            function escHtml(str) {
                return $('<div>').text(str || '').html();
            }

            // Sinkronisasi posisi header tabel saat scroll (JS Sticky Header khusus template Berry)
            function syncStickyHeader() {
                var wrap = document.getElementById('pm_mod_table_wrap');
                if (!wrap) return;
                var scrollTop = wrap.scrollTop;
                var ths = wrap.querySelectorAll('.pm-mod-table thead th');
                for (var i = 0; i < ths.length; i++) {
                    ths[i].style.transform = 'translateY(' + scrollTop + 'px)';
                }
            }

            // Bind scroll event pada container tabel
            $('#pm_mod_table_wrap').on('scroll', syncStickyHeader);
            $(window).on('resize', syncStickyHeader);

            // Filter pills click
            $('.pm-mod-pill').on('click', function() {
                $('.pm-mod-pill').removeClass('active');
                $(this).addClass('active');
                currentFilter = $(this).data('filter');
                renderRows();
            });

            // Search input realtime
            $('#pm_mod_search').on('input', function() {
                renderRows();
            });

            // Load Data via AJAX
            $.ajax({
                url: siteurl + 'dashboard/get_active_project_modules',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $('#pm_mod_loading').hide();
                    $('#pm_mod_filter_bar').show();

                    rawItems = (res && res.items) ? res.items : [];
                    var summary = (res && res.summary) ? res.summary : { total: 0, overdue: 0, near_due: 0, on_track: 0 };

                    $('#pm_mod_count_all').text(summary.total);
                    $('#pm_mod_count_overdue').text(summary.overdue);
                    $('#pm_mod_count_near_due').text(summary.near_due);
                    $('#pm_mod_count_on_track').text(summary.on_track);

                    var subtitleText = summary.total + ' modul aktif sedang dikerjakan';
                    if (summary.overdue > 0) {
                        subtitleText += ' (' + summary.overdue + ' terlambat ⚠️)';
                    }
                    $('#pm_mod_subtitle').text(subtitleText);

                    renderRows();
                },
                error: function() {
                    $('#pm_mod_loading').hide();
                    $('#pm_mod_subtitle').text('Gagal memuat status tahapan modul');
                    $('#pm_mod_table_wrap').hide();
                    $('#pm_mod_empty').show();
                    $('#pm_mod_empty_text').html('<span class="text-danger"><i class="ti ti-alert-circle"></i> Gagal memuat data modul project.</span>');
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

            // ── Category Chips ─────────────────────────
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
                    $('#pd_subcat_chips').html('<span style="font-size:12px;color:#dc3545;">Gagal memuat category</span>');
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

            // ── Chart: Horizontal Bar per Category ─────
            function renderSubcatChart(subcatCounts) {
                $('#pd_chart_subcat_wrap').hide();
                var canvas = $('#pd_chart_subcat').show()[0];
                var ctx = canvas.getContext('2d');
                if (chartSubcat) {
                    chartSubcat.destroy();
                }

                if (!subcatCounts || subcatCounts.length === 0) {
                    $('#pd_chart_subcat_wrap').show().html('<div class="pd-empty" style="padding:30px 0;"><i class="ti ti-mood-smile"></i><p>Tidak ada data category.</p></div>');
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
                allClientsData = clients;

                renderProjectChart(clients);
                renderSubcatChart(subcatCounts || []);

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

            var projectModalBS = new bootstrap.Modal(document.getElementById('pd_project_modal'));

            window.pdOpenModal = function(idx) {
                var c = allClientsData[idx];
                if (!c) return;

                var dateFrom = $('#pd_date_from').val();
                var dateTo = $('#pd_date_to').val();
                var allTime = $('#pd_all_time').is(':checked') ? '1' : '0';
                var subcatNames = getSelectedSubcats();

                $('#pd_modal_body_content').html(
                    '<div style="padding:60px;text-align:center;color:#6c757d;">' +
                    '<i class="ti ti-loader-2 ti-spin" style="font-size:28px;"></i>' +
                    '<p style="margin-top:10px;font-size:13px;">Memuat data tiket...</p>' +
                    '</div>'
                );

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
                $('#pd_project_modal').hide();
                $('body').css('overflow', '');
            };

            $('#pd_project_modal').on('click', function(e) {
                if ($(e.target).is('#pd_project_modal')) pdCloseModal();
            });

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
                        text: 'Pilih minimal satu category.',
                        confirmButtonColor: '#534AB7'
                    });
                    return;
                }

                showStatSkeleton();
                $('#pd_chart_trend').hide();
                $('#pd_chart_trend_wrap').show().html('<div class="pd-skeleton-box" style="height:220px;"></div>');
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

                        renderStats(res.summary || {});
                        renderProjectSection(res.clients || [], res.subcat_counts || []);
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
                        $('#pd_chart_subcat_wrap').html('');
                        $('#pd_table_skeleton').hide();
                        $('#pd_table_wrap').show().html('<div class="pd-empty"><i class="ti ti-alert-circle"></i><p>Gagal memuat data.</p></div>');
                    }
                });
            };

        })();

    });
</script>

<?php if (!empty($show_popup) && $show_popup): ?>
    <style>
        /* ── Overlay ── */
        #ba-popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 12, 41, 0.65);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: baFadeIn 0.3s ease;
        }

        @keyframes baFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* ── Card ── */
        .ba-popup-card {
            background: #fff;
            border-radius: 20px;
            width: 90%;
            max-width: 520px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.25);
            animation: baSlideUp 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
        }

        @keyframes baSlideUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ── Header banner ── */
        .ba-popup-banner {
            background: linear-gradient(135deg, #534AB7 0%, #7B6EE8 100%);
            padding: 24px 24px 20px;
            position: relative;
            overflow: hidden;
        }

        .ba-popup-banner::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .ba-popup-banner::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: -10px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .ba-popup-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-size: 24px;
        }

        .ba-popup-title {
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .ba-popup-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            margin: 0;
        }

        .ba-count-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #FF4D4D;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            box-shadow: 0 3px 12px rgba(255, 77, 77, 0.4);
            animation: baPulse 1.5s ease-in-out infinite;
        }

        @keyframes baPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 3px 12px rgba(255, 77, 77, 0.4);
            }

            50% {
                transform: scale(1.06);
                box-shadow: 0 5px 20px rgba(255, 77, 77, 0.6);
            }
        }

        /* ── Body ── */
        .ba-popup-body {
            padding: 16px 20px;
            overflow-y: auto;
            flex: 1;
        }

        /* ── Tiket item ── */
        .ba-ticket-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            color: inherit;
            background: #fafafa;
        }

        .ba-ticket-item:hover {
            background: #EEEDFE;
            border-color: #534AB7;
            transform: translateX(3px);
            text-decoration: none;
            color: inherit;
        }

        .ba-ticket-num {
            font-size: 10px;
            color: #6c757d;
            margin: 0 0 3px;
        }

        .ba-ticket-report {
            font-size: 13px;
            font-weight: 600;
            color: #212529;
            margin: 0 0 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .ba-ticket-meta {
            font-size: 11px;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ba-due-warn {
            color: #dc3545;
            font-weight: 600;
        }

        .ba-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 4px;
        }

        /* ── Footer ── */
        .ba-popup-footer {
            padding: 14px 20px;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .ba-btn-primary {
            flex: 1;
            background: #534AB7;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .ba-btn-primary:hover {
            background: #3f37a0;
        }

        .ba-btn-ghost {
            background: none;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13px;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.2s;
        }

        .ba-btn-ghost:hover {
            border-color: #534AB7;
            color: #534AB7;
        }

        /* ── Skeleton ── */
        .ba-skel {
            border-radius: 8px;
            animation: baSkel 1s linear infinite alternate;
        }

        @keyframes baSkel {
            from {
                background: hsl(200, 20%, 82%);
            }

            to {
                background: hsl(200, 20%, 94%);
            }
        }
    </style>

    <!-- Pop-up DOM -->
    <div id="ba-popup-overlay">
        <div class="ba-popup-card">
            <!-- Banner -->
            <div class="ba-popup-banner">
                <p class="ba-popup-title">Tiket Butuh Perhatianmu!</p>
                <p class="ba-popup-subtitle">Ada tiket masuk yang belum ditentukan PIC-nya dan sudah lebih dari 7 hari</p>
            </div>

            <!-- Body -->
            <div class="ba-popup-body" id="ba_popup_body">
                <!-- Skeleton sementara AJAX load -->
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="ba-ticket-item" style="pointer-events:none;">
                        <div class="ba-skel" style="width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:4px;"></div>
                        <div style="flex:1;">
                            <div class="ba-skel mb-1" style="width:80px;height:10px;"></div>
                            <div class="ba-skel mb-2" style="width:100%;height:13px;"></div>
                            <div class="ba-skel" style="width:60%;height:10px;"></div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Footer -->
            <div class="ba-popup-footer">
                <button class="ba-btn-primary" onclick="window.location.href='<?= site_url('ticket') ?>'">
                    <i class="ti ti-ticket" style="font-size:14px;"></i>
                    Lihat Semua Tiket
                </button>
                <button class="ba-btn-ghost" onclick="baClosePopup()">Nanti Dulu</button>
            </div>
        </div>
    </div>

    <script>
        (function() {


            function isOverdue(dueDateStr) {
                if (!dueDateStr) return false;
                return new Date(dueDateStr.substring(0, 10)) < new Date(new Date().toDateString());
            }

            function getAgeInfo(createDateStr) {
                if (!createDateStr) return null;

                var created = new Date(createDateStr.substring(0, 10));
                var now = new Date(new Date().toDateString());
                var days = Math.floor((now - created) / (1000 * 60 * 60 * 24));

                if (days >= 7) {
                    return {
                        days: days,
                        color: '#EF9F27',
                        bg: '#FAEEDA',
                        icon: '⚠️'
                    };
                }

                return null;
            }

            function renderTickets(tickets) {
                var body = document.getElementById('ba_popup_body');

                if (!tickets || tickets.length === 0) {
                    body.innerHTML =
                        '<div style="text-align:center;padding:30px;color:#6c757d;font-size:13px;">' +
                        '<i class="ti ti-mood-happy" style="font-size:28px;display:block;margin-bottom:8px;color:#27AE60;"></i>' +
                        'Tidak ada tiket baru (Open) 🎉</div>';
                    return;
                }

                var html = '';
                tickets.forEach(function(t) {
                    var age = getAgeInfo(t.create_date);

                    var ageHtml = '';
                    if (age) {
                        ageHtml = '<span style="background:' + age.bg + ';color:' + age.color +
                            ';padding:2px 8px;border-radius:20px;font-weight:700;font-size:10px;' +
                            'display:inline-flex;align-items:center;gap:3px;">' +
                            age.icon + ' ' + age.days + ' hari</span>';
                    }

                    var created = t.create_date ? t.create_date.substring(0, 10) : '-';

                    html += '<a href="' + siteurl + 'ticket/view_ticket/' + t.id + '" class="ba-ticket-item">' +
                        '<div class="ba-status-dot" style="background:#E24B4A;margin-top:5px;"></div>' +
                        '<div style="flex:1;">' +
                        '<p class="ba-ticket-num">' + escHtml(t.no_ticket || '-') + ' &bull; ' + escHtml(t.client_name || '') + '</p>' +
                        '<p class="ba-ticket-report">' + escHtml(t.report) + '</p>' +
                        '<div class="ba-ticket-meta" style="gap:6px;">' +
                        '<span style="background:#FCEBEB;color:#E24B4A;padding:1px 7px;border-radius:20px;font-weight:600;">Open</span>' +
                        ageHtml +
                        '<span style="color:#adb5bd;"><i class="ti ti-calendar-plus" style="font-size:10px;"></i> Dibuat: ' + created + '</span>' +
                        '</div></div></a>';
                });

                body.innerHTML = html;
            }

            function escHtml(str) {
                var d = document.createElement('div');
                d.textContent = str || '';
                return d.innerHTML;
            }

            // Load via AJAX
            $.ajax({
                url: siteurl + 'dashboard/get_unassigned_tickets',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    renderTickets(res);
                },
                error: function() {
                    document.getElementById('ba_popup_body').innerHTML =
                        '<div style="text-align:center;padding:20px;font-size:13px;color:#dc3545;">' +
                        'Gagal memuat data tiket.</div>';
                }
            });

            window.baClosePopup = function() {
                var overlay = document.getElementById('ba-popup-overlay');
                if (!overlay) return;
                overlay.style.animation = 'baFadeIn 0.2s ease reverse';
                setTimeout(function() {
                    overlay.remove();
                }, 180);
            };
        })();
    </script>
<?php endif; ?>