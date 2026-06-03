<?php
$ENABLE_ADD    = has_permission('Productivity_report.Add');
$ENABLE_MANAGE = has_permission('Productivity_report.Manage');
$ENABLE_VIEW   = has_permission('Productivity_report.View');
$ENABLE_DELETE = has_permission('Productivity_report.Delete');
?>

<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    /* ── Skeleton ─────────────────────────────────────────────── */
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

    .skeleton-row {
        height: 48px;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    /* ── Summary Cards ────────────────────────────────────────── */
    .summary-card {
        border-radius: 10px;
        padding: 16px 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .summary-card .card-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 2.5rem;
        opacity: .2;
    }

    .summary-card .card-value {
        font-size: 1.6rem;
        font-weight: 700;
    }

    .summary-card .card-label {
        font-size: .8rem;
        opacity: .85;
    }

    .eff-excellent {
        background: #d1fae5;
        color: #065f46;
    }

    .eff-good {
        background: #dbeafe;
        color: #1e40af;
    }

    .eff-average {
        background: #fef3c7;
        color: #92400e;
    }

    .eff-poor {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ── Status Pills ─────────────────────────────────────────── */
    .status-pill {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
    }

    /* ── Tabs ─────────────────────────────────────────────────── */
    #productivityTabs .nav-link {
        font-weight: 600;
    }

    /* ── Progress Bar ─────────────────────────────────────────── */
    .mh-bar-wrap {
        background: #e9ecef;
        border-radius: 4px;
        height: 6px;
        margin-top: 4px;
    }

    .mh-bar {
        height: 6px;
        border-radius: 4px;
        transition: width .4s;
    }

    /* ── Clickable Row ────────────────────────────────────────── */
    .row-detail {
        cursor: pointer;
    }

    .row-detail:hover td {
        background: #f0f4ff !important;
    }
</style>

<!--  FILTER CARD                                                   -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="ti ti-filter me-2"></i>Filter Productivity Report</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end">

            <div class="col-md-2">
                <label class="form-label fw-semibold">Date From</label>
                <input type="text" id="date_from" class="form-control" placeholder="YYYY-MM-DD" readonly>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Date To</label>
                <input type="text" id="date_to" class="form-control" placeholder="YYYY-MM-DD" readonly>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Project</label>
                <select id="client_id" class="form-select">
                    <option value="">-- All Projects --</option>
                    <?php foreach ($clients as $c): ?>
                        <option value="<?= $c->id ?>"><?= htmlspecialchars($c->name_app) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Stage</label>
                <select id="category_id" class="form-select">
                    <option value="">-- All Stages --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->category_name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100" id="btnGenerate">
                    <i class="ti ti-search me-1"></i> Generate
                </button>
            </div>

        </div>
    </div>
</div>

<!--  MAIN RESULT (hidden until loaded)                            -->
<div id="reportResult" style="display:none;">

    <!-- Summary Cards -->
    <div class="row g-3 mb-4" id="summaryCards"></div>

    <!-- Tabs + Tables -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="productivityTabs">
                <li class="nav-item">
                    <button class="nav-link active px-4" data-bs-toggle="tab" data-bs-target="#tabProgrammer">
                        <i class="ti ti-code me-1"></i> Programmer
                        <span class="badge bg-primary ms-1" id="badgeProgrammer">0</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link px-4" data-bs-toggle="tab" data-bs-target="#tabBA">
                        <i class="ti ti-briefcase me-1"></i> Business Analyst
                        <span class="badge bg-info ms-1" id="badgeBA">0</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">

                <!-- Programmer Tab -->
                <div class="tab-pane fade show active" id="tabProgrammer">
                    <div class="table-responsive" id="tableProgrammer">
                        <p class="text-center text-muted py-4">Belum ada data</p>
                    </div>
                </div>

                <!-- BA Tab -->
                <div class="tab-pane fade" id="tabBA">
                    <div class="table-responsive" id="tableBA">
                        <p class="text-center text-muted py-4">Belum ada data</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ─── Loading Skeleton ──────────────────────────────────────────── -->
<div id="loadingSkeleton" style="display:none;">
    <div class="row g-3 mb-4">
        <?php for ($i = 0; $i < 4; $i++): ?>
            <div class="col-md-3">
                <div class="skeleton" style="height:90px;border-radius:10px;"></div>
            </div>
        <?php endfor; ?>
    </div>
    <div class="card p-3">
        <?php for ($i = 0; $i < 6; $i++): ?>
            <div class="skeleton skeleton-row"></div>
        <?php endfor; ?>
    </div>
</div>

<!--  TICKET DETAIL MODAL                                          -->
<div class="modal fade" id="modalTicketDetail" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailTitle">Ticket Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalDetailBody">
                <p class="text-center text-muted py-4">Loading...</p>
            </div>

        </div>
    </div>
</div>

<!--  SCRIPTS                                                       -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        'use strict';

        // ── URL Config ────────────────────────────────────────────────────────────
        const URL_GET_DATA = '<?= site_url("productivity_report/get_productivity_data") ?>';
        const URL_GET_PROGRAMMER = '<?= site_url("productivity_report/get_list_programmer") ?>';
        const URL_GET_BA = '<?= site_url("productivity_report/get_list_ba") ?>';
        const URL_GET_DETAIL = '<?= site_url("productivity_report/get_ticket_detail") ?>';

        // ── State ─────────────────────────────────────────────────────────────────
        let currentFilters = {};

        // ── Flatpickr ─────────────────────────────────────────────────────────────
        flatpickr('#date_from', {
            dateFormat: 'Y-m-d'
        });
        flatpickr('#date_to', {
            dateFormat: 'Y-m-d'
        });

        // ── Generate Button ───────────────────────────────────────────────────────
        $('#btnGenerate').on('click', function() {
            const date_from = $('#date_from').val();
            const date_to = $('#date_to').val();
            const client_id = $('#client_id').val();
            const category_id = $('#category_id').val();

            if (!date_from || !date_to) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Date From dan Date To wajib diisi.'
                });
            }

            if (date_from > date_to) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Date From tidak boleh lebih besar dari Date To.'
                });
            }

            currentFilters = {
                date_from,
                date_to,
                client_id,
                category_id
            };
            loadReport();
        });

        // ── Load Report (3 request paralel) ──────────────────────────────────────
        function loadReport() {
            $('#reportResult').hide();
            $('#loadingSkeleton').show();

            const reqSummary = $.post(URL_GET_DATA, currentFilters);
            const reqProgrammer = $.post(URL_GET_PROGRAMMER, currentFilters);
            const reqBA = $.post(URL_GET_BA, currentFilters);

            $.when(reqSummary, reqProgrammer, reqBA)
                .done(function(resSummary, resProgrammer, resBA) {
                    $('#loadingSkeleton').hide();

                    const data = resSummary[0]; // JSON
                    if (data.status !== 'success') {
                        return Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Gagal mengambil data.'
                        });
                    }

                    // Render summary cards dari JSON
                    renderSummaryCards(data.programmer_summary, data.ba_summary);

                    // Update badge count dari JSON
                    $('#badgeProgrammer').text(data.programmers.length);
                    $('#badgeBA').text(data.bas.length);

                    // Inject HTML partial langsung ke container div
                    $('#tableProgrammer').html(resProgrammer[0]);
                    $('#tableBA').html(resBA[0]);

                    // Bind drill-down setelah HTML di-inject
                    bindRowClick();

                    $('#reportResult').show();
                })
                .fail(function() {
                    $('#loadingSkeleton').hide();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Koneksi gagal.'
                    });
                });
        }

        // ── Render: Summary Cards ─────────────────────────────────────────────────
        function renderSummaryCards(prog, ba) {
            const c = {
                total_ticket: +prog.total_ticket + +ba.total_ticket,
                total_man_hour_plan: +prog.total_man_hour_plan + +ba.total_man_hour_plan,
                total_man_hour_actual: +prog.total_man_hour_actual + +ba.total_man_hour_actual,
                total_done: +prog.total_done + +ba.total_done,
                total_close: +prog.total_close + +ba.total_close,
            };

            const cards = [{
                    label: 'Total Ticket',
                    value: c.total_ticket,
                    icon: 'ti-ticket',
                    color: '#4361ee'
                },
                {
                    label: 'MH Plan (Total)',
                    value: c.total_man_hour_plan.toFixed(1) + ' h',
                    icon: 'ti-clock',
                    color: '#3a0ca3'
                },
                {
                    label: 'MH Actual',
                    value: c.total_man_hour_actual.toFixed(1) + ' h',
                    icon: 'ti-clock-check',
                    color: '#7209b7'
                },
                {
                    label: 'Ticket Close',
                    value: c.total_close,
                    icon: 'ti-circle-check',
                    color: '#06d6a0'
                },
            ];

            $('#summaryCards').html(
                cards.map(card => `
                <div class="col-md-3 col-6">
                    <div class="summary-card" style="background:${card.color}">
                        <div class="card-label">${card.label}</div>
                        <div class="card-value">${card.value}</div>
                        <i class="ti ${card.icon} card-icon"></i>
                    </div>
                </div>
            `).join('')
            );
        }

        // ── Bind: Drill-down click (event delegation, dipanggil ulang tiap inject) 
        function bindRowClick() {
            $('#tableProgrammer, #tableBA')
                .off('click', '.row-detail')
                .on('click', '.row-detail', function() {
                    openDetailModal(
                        $(this).data('user'),
                        $(this).data('role'),
                        $(this).data('nama')
                    );
                });
        }
        // ── Drill-Down Modal ──────────────────────────────────────────────────────
        function openDetailModal(userId, role, nama) {
            $('#modalDetailTitle').text(`Detail Ticket — ${nama}`);
            $('#modalDetailBody').html(`
            <tr>
                <td colspan="11" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm me-1"></div> Loading...
                </td>
            </tr>
        `);

            const modal = new bootstrap.Modal(document.getElementById('modalTicketDetail'));
            modal.show();

            $.post(URL_GET_DETAIL, {
                    user_id: userId,
                    role: role,
                    ...currentFilters,
                })
                .done(function(html) {
                    $('#modalDetailBody').html(html);
                })
                .fail(function() {
                    $('#modalDetailBody').html(
                        '<p class="text-center text-danger py-4">Gagal memuat data.</p>'
                    );
                });
        }

    }); // end document.ready
</script>