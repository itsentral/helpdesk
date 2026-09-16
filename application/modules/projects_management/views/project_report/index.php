<?php
/**
 * View: Dashboard Laporan & Jadwal Proyek (per-project)
 * Variabel: $projects, $project_id, $project, $modules, $others, $summary, $schedule, $report_date
 */
if (!function_exists('pr_num')) { function pr_num($n) { return number_format((float)$n, 1, ',', '.'); } }
if (!function_exists('pr_date')) { function pr_date($d) { return (!empty($d) && $d !== '0000-00-00') ? date('d-M-y', strtotime($d)) : '-'; } }

// Defensive defaults (variabel di-inject controller via template->set)
$projects    = isset($projects) ? $projects : array();
$project_id  = isset($project_id) ? $project_id : null;
$project     = isset($project) ? $project : null;
$modules     = isset($modules) ? $modules : array();
$others      = isset($others) ? $others : array();
$summary     = isset($summary) ? $summary : array('plan_manhour' => 0, 'actual_manhour' => 0, 'selisih' => 0, 'total_modules' => 0, 'finish_modules' => 0, 'progress_pct' => 0);
$schedule    = isset($schedule) ? $schedule : array();
$schedule   += array('rows' => array(), 'months' => array(), 'total_weeks' => 1, 'today_week' => null, 'year' => date('Y'));
$report_date = isset($report_date) ? $report_date : date('d M Y');
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .pr-ledger .stat { border-right: 1px solid #e5e7eb; }
    .pr-ledger .stat:last-child { border-right: none; }
    .pr-ledger .val { font-size: 1.6rem; font-weight: 700; font-family: 'Courier New', monospace; }
    .pr-mini-progress { width: 90px; height: 6px; background: #e7e9ec; border-radius: 3px; overflow: hidden; display: inline-block; vertical-align: middle; margin-right: 6px; }
    .pr-mini-progress > div { height: 100%; background: #1f3a5f; }
    .pr-mini-progress.ok > div { background: #1e8e6b; }
    .pr-mini-progress.late > div { background: #a6321d; }

    .pr-bar-track { position: relative; height: 16px; background: #e7e9ec; border-radius: 2px; }
    .pr-bar-plan { position: absolute; left: 0; top: 0; height: 16px; background: #c6cfda; border-radius: 2px; }
    .pr-bar-aktual { position: absolute; left: 0; top: 4px; height: 8px; background: #1f3a5f; border-radius: 2px; }

    /* ===== Gantt harian (scroll horizontal) ===== */
    .pr-gantt-scroll { overflow-x: auto; overflow-y: hidden; border: 1px solid #e5e7eb; border-radius: 6px; background: #fff; }
    .pr-gantt { position: relative; }

    .pr-ghead-row { display: flex; }
    .pr-ghead-label { flex: 0 0 auto; position: sticky; left: 0; z-index: 5; background: #4f46e5; color: #fff; font-weight: 700; font-size: 12px; display: flex; align-items: center; padding: 0 12px; border-right: 1px solid #3f37c9; }
    .pr-ghead-sub { background: #6366f1; }
    .pr-ghead-months { flex: 0 0 auto; display: flex; background: #4f46e5; }
    .pr-month { flex: 0 0 auto; color: #fff; font-size: 11.5px; font-weight: 600; text-align: center; line-height: 26px; height: 26px; border-left: 1px solid rgba(255,255,255,.25); overflow: hidden; white-space: nowrap; }
    .pr-ghead-days { flex: 0 0 auto; display: flex; background: #6366f1; }
    .pr-day { flex: 0 0 auto; color: #e0e7ff; font-size: 9px; text-align: center; line-height: 18px; height: 18px; border-left: 1px solid rgba(255,255,255,.12); }

    .pr-grow { display: flex; align-items: center; border-bottom: 1px solid #eef0f2; }
    .pr-grow:nth-child(even) { background: #f8fafc; }
    .pr-glabel { flex: 0 0 auto; position: sticky; left: 0; z-index: 4; background: inherit; display: flex; align-items: center; gap: 6px; height: 100%; padding: 0 10px; border-right: 1px solid #e5e7eb; }
    .pr-grow:nth-child(odd) .pr-glabel { background: #fff; }
    .pr-dot { width: 9px; height: 9px; border-radius: 50%; flex: 0 0 auto; }
    .pr-glabel-txt { font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 165px; }
    .pr-glabel-pic { font-size: 9.5px; color: #fff; background: #6366f1; border-radius: 3px; padding: 1px 5px; margin-left: auto; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 60px; }
    .pr-gtrack { flex: 0 0 auto; position: relative; height: 100%; }

    .pr-gbar { position: absolute; top: 50%; transform: translateY(-50%); height: 18px; border-radius: 9px; overflow: hidden; display: flex; align-items: center; box-shadow: 0 1px 2px rgba(0,0,0,.12); }
    .pr-gbar.finish { background: #1e8e6b; }
    .pr-gbar.progress { background: #e8590c; }
    .pr-gbar.none { background: #9aa3af; }
    .pr-gbar-fill { position: absolute; left: 0; top: 0; height: 100%; background: rgba(255,255,255,.28); }
    .pr-gbar-label { position: relative; z-index: 1; color: #fff; font-size: 9.5px; font-weight: 600; padding: 0 8px; white-space: nowrap; text-shadow: 0 1px 1px rgba(0,0,0,.25); display: flex; align-items: center; gap: 6px; }
    .pr-gbar-due { font-weight: 500; opacity: .95; }

    .pr-grid-lines { position: absolute; top: 0; bottom: 0; pointer-events: none; z-index: 1; }
    .pr-mline { position: absolute; top: 0; bottom: 0; width: 0; border-left: 1px solid #e5e7eb; }
    .pr-today { position: absolute; top: 0; bottom: 0; width: 0; border-left: 2px solid #4f46e5; z-index: 2; }
    .pr-today-badge { position: absolute; top: 2px; left: -18px; background: #4f46e5; color: #fff; font-size: 8.5px; padding: 1px 5px; border-radius: 3px; }

    .pr-seq-row { display: grid; grid-template-columns: 240px 1fr 110px; align-items: center; gap: 12px; padding: 7px 0; border-bottom: 1px solid #f1f2f4; }
    .pr-seq-name .pic { display: block; font-size: 10.5px; color: #6b7684; }
    .pr-seq-bar-track { position: relative; height: 18px; background: #e7e9ec; border-radius: 2px; }
    .pr-seq-bar-plan { position: absolute; top: 0; left: 0; height: 100%; border-radius: 2px; background: #d7dee7; }
    .pr-seq-bar-aktual { position: absolute; top: 4px; left: 0; height: 10px; border-radius: 2px; }
    .pr-seq-bar-aktual.finish { background: #1e8e6b; }
    .pr-seq-bar-aktual.active { background: #c48a1e; }
    .pr-seq-mh { font-size: 11px; color: #6b7684; text-align: right; font-family: 'Courier New', monospace; }
</style>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <!-- Filter bar -->
        <form method="get" action="<?= site_url('projects_management/project_report/index'); ?>" class="row g-2 align-items-end mb-4">
            <div class="col-12 col-md-6">
                <label class="form-label small fw-bold text-muted mb-1">Pilih Project</label>
                <select name="project_id" id="pr-project-select" class="form-select" onchange="this.form.submit()">
                    <?php if (!empty($projects)): foreach ($projects as $opt): ?>
                        <?php $opt_client = !empty($opt['client_name']) ? $opt['client_name'] : '(Tanpa Client)'; ?>
                        <option value="<?= $opt['id']; ?>" <?= ($project_id == $opt['id']) ? 'selected' : ''; ?>>
                            <?= html_escape($opt_client . ' — ' . $opt['project_name']); ?>
                        </option>
                    <?php endforeach; else: ?>
                        <option value="">Belum ada project</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-12 col-md-6 text-md-end">
                <?php if (!empty($project)): ?>
                    <a href="<?= site_url('projects_management/project_report/print_report/' . $project_id); ?>" class="btn btn-outline-danger btn-sm" target="_blank"><i class="fa fa-print me-1"></i> Print / PDF</a>
                    <a href="<?= site_url('projects_management/project_report/export_excel/' . $project_id); ?>" class="btn btn-outline-success btn-sm"><i class="fa fa-file-excel me-1"></i> Export Excel</a>
                <?php endif; ?>
            </div>
        </form>

        <?php if (empty($project)): ?>
            <div class="alert alert-info mb-0"><i class="fa fa-info-circle me-1"></i> Pilih project untuk menampilkan laporan &amp; jadwal.</div>
        <?php else: ?>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="prTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-laporan-btn" data-bs-toggle="tab" data-bs-target="#tab-laporan" type="button" role="tab">
                    <i class="fa fa-file-text-o me-1"></i> Laporan Project
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-jadwal-btn" data-bs-toggle="tab" data-bs-target="#tab-jadwal" type="button" role="tab">
                    <i class="fa fa-calendar me-1"></i> Schedule / Jadwal
                </button>
            </li>
        </ul>

        <div class="tab-content">

            <!-- ============ TAB LAPORAN ============ -->
            <div class="tab-pane fade show active" id="tab-laporan" role="tabpanel">

                <!-- Project header strip -->
                <div class="card border-0 border-start border-4 border-primary bg-light mb-3">
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="text-muted small font-monospace"><?= html_escape($project['project_code']); ?></div>
                                <div class="h5 mb-1 fw-bold">
                                    <?= html_escape($project['project_name']); ?>
                                    <?php
                                    $st = $project['status'];
                                    $stcls = 'bg-secondary';
                                    if ($st == 'In Progress') $stcls = 'bg-warning text-dark';
                                    elseif ($st == 'Completed') $stcls = 'bg-success';
                                    elseif ($st == 'On Hold') $stcls = 'bg-danger';
                                    elseif ($st == 'Planning') $stcls = 'bg-info text-white';
                                    ?>
                                    <span class="badge <?= $stcls; ?> align-middle"><?= html_escape($st); ?></span>
                                </div>
                                <div class="text-muted small">
                                    Client: <?= html_escape($project['client_name'] ?: '-'); ?>
                                    &middot; Target Selesai: <?= pr_date($project['end_date']); ?>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row row-cols-2 row-cols-lg-4 g-2">
                                    <div><div class="text-muted small text-uppercase">PM</div><div class="fw-semibold small"><?= html_escape($project['pm_name'] ?: '-'); ?></div></div>
                                    <div><div class="text-muted small text-uppercase">Bisnis Analis</div><div class="fw-semibold small"><?= html_escape($project['ba_names']); ?></div></div>
                                    <div><div class="text-muted small text-uppercase">Programmer</div><div class="fw-semibold small"><?= html_escape($project['programmer_names']); ?></div></div>
                                    <div><div class="text-muted small text-uppercase">QA</div><div class="fw-semibold small"><?= html_escape($project['qa_names']); ?></div></div>
                                    <div><div class="text-muted small text-uppercase">Total Modul</div><div class="fw-semibold small"><?= (int)$summary['total_modules']; ?></div></div>
                                    <div><div class="text-muted small text-uppercase">Modul Finish</div><div class="fw-semibold small"><?= (int)$summary['finish_modules']; ?> / <?= (int)$summary['total_modules']; ?></div></div>
                                    <div><div class="text-muted small text-uppercase">Tanggal Laporan</div><div class="fw-semibold small"><?= $report_date; ?></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ledger stats -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-0">
                        <div class="d-flex pr-ledger text-center flex-wrap">
                            <div class="stat flex-fill p-3">
                                <div class="text-muted small text-uppercase">Plan Manhour</div>
                                <div class="val"><?= pr_num($summary['plan_manhour']); ?></div>
                                <div class="text-muted small">total seluruh modul</div>
                            </div>
                            <div class="stat flex-fill p-3">
                                <div class="text-muted small text-uppercase">Aktual Manhour</div>
                                <div class="val"><?= pr_num($summary['actual_manhour']); ?></div>
                                <div class="text-muted small">terpakai s/d laporan</div>
                            </div>
                            <div class="stat flex-fill p-3">
                                <div class="text-muted small text-uppercase">Selisih</div>
                                <div class="val <?= ($summary['selisih'] > 0) ? 'text-danger' : 'text-success'; ?>"><?= ($summary['selisih'] > 0 ? '+' : '') . pr_num($summary['selisih']); ?></div>
                                <div class="text-muted small">aktual - plan</div>
                            </div>
                            <div class="stat flex-fill p-3">
                                <div class="text-muted small text-uppercase">% Progress Modul</div>
                                <div class="val"><?= pr_num($summary['progress_pct']); ?>%</div>
                                <div class="text-muted small"><?= (int)$summary['finish_modules']; ?> dari <?= (int)$summary['total_modules']; ?> modul finish</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rincian per modul -->
                <div class="d-flex justify-content-between align-items-baseline mt-3 mb-2">
                    <h6 class="fw-bold mb-0">Rincian per Modul</h6>
                    <small class="text-muted">diurutkan sesuai urutan pengerjaan</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle bg-white mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:32px">No</th>
                                <th>Modul</th>
                                <th style="width:150px">Progress Tahapan</th>
                                <th>PIC Utama</th>
                                <th style="width:110px">Due Date Modul</th>
                                <th class="text-end" style="width:90px">Plan MH</th>
                                <th class="text-end" style="width:90px">Aktual MH</th>
                                <th class="text-end" style="width:90px">Selisih</th>
                                <th style="width:100px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($modules)): $no = 1; foreach ($modules as $mod):
                                $pct = ($mod['total_tahapan'] > 0) ? round(($mod['finished_tahapan'] / $mod['total_tahapan']) * 100) : 0;
                                $barcls = 'ok';
                                if ($mod['status_label'] === 'In Progress') $barcls = '';
                                elseif ($mod['status_label'] === 'Belum Mulai') $barcls = 'late';
                                $selisih = $mod['selisih_manhour'];
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= html_escape($mod['module_name']); ?></td>
                                    <td>
                                        <span class="pr-mini-progress <?= $barcls; ?>"><div style="width:<?= $pct; ?>%"></div></span>
                                        <?= (int)$mod['finished_tahapan']; ?>/<?= (int)$mod['total_tahapan']; ?>
                                    </td>
                                    <td><?= html_escape($mod['pic_utama']); ?></td>
                                    <td class="font-monospace small"><?= pr_date($mod['due_date_modul']); ?></td>
                                    <td class="text-end"><?= pr_num($mod['plan_manhour']); ?></td>
                                    <td class="text-end"><?= pr_num($mod['actual_manhour']); ?></td>
                                    <td class="text-end <?= ($selisih > 0) ? 'text-danger' : 'text-success'; ?>"><?= ($selisih > 0 ? '+' : '') . pr_num($selisih); ?></td>
                                    <td>
                                        <?php
                                        $lbl = $mod['status_label'];
                                        $lblcls = 'bg-secondary';
                                        if ($lbl === 'Finish') $lblcls = 'bg-success';
                                        elseif ($lbl === 'In Progress') $lblcls = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge <?= $lblcls; ?>"><?= $lbl; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="9" class="text-center text-muted p-4">Belum ada modul pada project ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Perbandingan plan vs aktual -->
                <div class="d-flex justify-content-between align-items-baseline mt-4 mb-2">
                    <h6 class="fw-bold mb-0">Perbandingan Plan vs Aktual Manhour per Modul</h6>
                    <small class="text-muted">bar atas = plan, bar bawah = aktual</small>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <?php
                        $max_mh = 1;
                        foreach ($modules as $mod) {
                            $max_mh = max($max_mh, (float)$mod['plan_manhour'], (float)$mod['actual_manhour']);
                        }
                        if (!empty($modules)): foreach ($modules as $mod):
                            $plan_w   = round(((float)$mod['plan_manhour'] / $max_mh) * 100);
                            $aktual_w = round(((float)$mod['actual_manhour'] / $max_mh) * 100);
                        ?>
                            <div class="d-flex align-items-center mb-2">
                                <div style="width:220px" class="small text-truncate pe-2" title="<?= html_escape($mod['module_name']); ?>"><?= html_escape($mod['module_name']); ?></div>
                                <div class="flex-fill">
                                    <div class="pr-bar-track">
                                        <div class="pr-bar-plan" style="width:<?= $plan_w; ?>%"></div>
                                        <div class="pr-bar-aktual" style="width:<?= $aktual_w; ?>%"></div>
                                    </div>
                                </div>
                                <div style="width:150px" class="text-end small text-muted"><?= pr_num($mod['plan_manhour']); ?> / <?= pr_num($mod['actual_manhour']); ?> MH</div>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="text-muted small">Belum ada data manhour.</div>
                        <?php endif; ?>
                        <div class="d-flex gap-4 mt-3 small text-muted">
                            <span><i class="d-inline-block" style="width:10px;height:10px;background:#c6cfda"></i> Plan MH</span>
                            <span><i class="d-inline-block" style="width:10px;height:10px;background:#1f3a5f"></i> Aktual MH</span>
                        </div>
                    </div>
                </div>

                <!-- Others / meeting -->
                <div class="d-flex justify-content-between align-items-baseline mt-4 mb-2">
                    <h6 class="fw-bold mb-0">Catatan / Aktivitas Tambahan (Others)</h6>
                    <small class="text-muted">rekap seluruh modul, di luar tahapan baku</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle bg-white mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:110px">Tanggal</th>
                                <th style="width:220px">Modul</th>
                                <th>Aktivitas</th>
                                <th class="text-end" style="width:80px">MH</th>
                                <th style="width:160px">Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($others)): foreach ($others as $o): ?>
                                <tr>
                                    <td class="font-monospace small"><?= pr_date($o['task_date']); ?></td>
                                    <td class="small"><?= html_escape($o['module_name']); ?></td>
                                    <td class="small"><?= html_escape($o['task_description']); ?></td>
                                    <td class="text-end"><?= pr_num($o['manhour']); ?></td>
                                    <td class="small"><?= html_escape($o['user_name'] ?: '-'); ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="5" class="text-center text-muted p-3">Tidak ada aktivitas tambahan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- ============ TAB JADWAL ============ -->
            <div class="tab-pane fade" id="tab-jadwal" role="tabpanel">

                <div class="d-flex justify-content-between align-items-start gap-2 mb-3 flex-wrap">
                    <div class="alert alert-warning small mb-0 flex-fill"><i class="fa fa-info-circle me-1 fa-sm"></i>
                        Garis <b>Today</b> menandai minggu berjalan. Panjang bar = rentang perkiraan pengerjaan modul (skala mingguan); isian terang = <b>% MH terpakai terhadap plan</b> sebagai proksi progress.
                    </div>
                    <a href="<?= site_url('projects_management/project_report/print_gantt/' . $project_id); ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="fa fa-print me-1"></i> Print Gantt Chart
                    </a>
                </div>

                <!-- Sub-tabs jadwal -->
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item"><button class="nav-link active btn-sm" data-bs-toggle="tab" data-bs-target="#sub-modul" type="button">Ringkasan per Modul (Mingguan)</button></li>
                    <li class="nav-item"><button class="nav-link btn-sm" data-bs-toggle="tab" data-bs-target="#sub-tahapan" type="button">Detail per Tahapan</button></li>
                </ul>

                <div class="tab-content">
                    <!-- Sub 1: gantt mingguan per modul (min full-width, scroll bila panjang) -->
                    <div class="tab-pane fade show active" id="sub-modul" role="tabpanel">
                        <?php
                        $WEEK_W   = 52;                        // lebar dasar per minggu (px), bisa dilebarkan JS
                        $LABEL_W  = 260;                       // lebar kolom label modul (px)
                        $ROW_H    = 38;                        // tinggi baris
                        $total_weeks = max(1, (int)$schedule['total_weeks']);
                        // Lebar minggu & grid pakai CSS var --wk supaya JS bisa melebarkan ke full-width
                        $wk = 'var(--wk)';
                        ?>
                        <div class="pr-gantt-scroll">
                            <div class="pr-gantt" id="pr-gantt-main"
                                 data-weeks="<?= $total_weeks; ?>" data-label="<?= $LABEL_W; ?>" data-minwk="<?= $WEEK_W; ?>"
                                 style="--wk:<?= $WEEK_W; ?>px; width:calc(<?= $LABEL_W; ?>px + <?= $wk; ?> * <?= $total_weeks; ?>);">

                                <!-- Header: baris bulan -->
                                <div class="pr-ghead-row">
                                    <div class="pr-ghead-label" style="width:<?= $LABEL_W; ?>px;">TASK</div>
                                    <div class="pr-ghead-months" style="width:calc(<?= $wk; ?> * <?= $total_weeks; ?>);">
                                        <?php foreach ($schedule['months'] as $mo): ?>
                                            <div class="pr-month" style="width:calc(<?= $wk; ?> * <?= (int)$mo['weeks']; ?>);"><?= html_escape($mo['label']); ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Header: baris minggu (W1, W2, ...) -->
                                <div class="pr-ghead-row">
                                    <div class="pr-ghead-label pr-ghead-sub" style="width:<?= $LABEL_W; ?>px;">&nbsp;</div>
                                    <div class="pr-ghead-days" style="width:calc(<?= $wk; ?> * <?= $total_weeks; ?>);">
                                        <?php for ($w = 1; $w <= $total_weeks; $w++): ?>
                                            <div class="pr-day" style="width:<?= $wk; ?>;">W<?= $w; ?></div>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <!-- Body: baris modul -->
                                <div class="pr-gbody" style="position:relative;">
                                    <div class="pr-grid-lines" style="left:<?= $LABEL_W; ?>px;width:calc(<?= $wk; ?> * <?= $total_weeks; ?>);">
                                        <?php $acc = 0; foreach ($schedule['months'] as $mo): $acc += (int)$mo['weeks']; ?>
                                            <div class="pr-mline" style="left:calc(<?= $wk; ?> * <?= $acc; ?>);"></div>
                                        <?php endforeach; ?>
                                        <?php if ($schedule['today_week'] !== null): ?>
                                            <div class="pr-today" style="left:calc(<?= $wk; ?> * <?= $schedule['today_week']; ?>);">
                                                <span class="pr-today-badge">Today</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($schedule['rows'])): foreach ($schedule['rows'] as $r):
                                        $gcls = 'progress';
                                        $dot  = '#c48a1e';
                                        if ($r['status'] === 'finish') { $gcls = 'finish'; $dot = '#1e8e6b'; }
                                        elseif ($r['status_label'] === 'Belum Mulai') { $gcls = 'none'; $dot = '#9aa3af'; }
                                        $len_w = max(1, (int)$r['len_weeks']);
                                        $off_w = (int)$r['offset_weeks'];
                                    ?>
                                        <div class="pr-grow" style="height:<?= $ROW_H; ?>px;">
                                            <div class="pr-glabel" style="width:<?= $LABEL_W; ?>px;">
                                                <span class="pr-dot" style="background:<?= $dot; ?>"></span>
                                                <span class="pr-glabel-txt" title="<?= html_escape($r['module_name']); ?>"><?= html_escape($r['module_name']); ?></span>
                                                <span class="pr-glabel-pic" title="<?= html_escape($r['pic_utama']); ?>"><?= html_escape($r['pic_utama']); ?></span>
                                            </div>
                                            <div class="pr-gtrack" style="width:calc(<?= $wk; ?> * <?= $total_weeks; ?>);">
                                                <?php $due10_txt = !empty($r['due_date_10']) ? date('d M Y', strtotime($r['due_date_10'])) : null; ?>
                                                <div class="pr-gbar <?= $gcls; ?>" style="left:calc(<?= $wk; ?> * <?= $off_w; ?>);width:calc(<?= $wk; ?> * <?= $len_w; ?>);"
                                                     title="<?= html_escape($r['module_name']); ?> — Go Live: <?= $due10_txt ? $due10_txt : 'belum diset'; ?>">
                                                    <div class="pr-gbar-fill" style="width:<?= (int)$r['progress_pct']; ?>%"></div>
                                                    <span class="pr-gbar-label">
                                                        <?= (int)$r['progress_pct']; ?>%
                                                        <?php if ($due10_txt): ?><span class="pr-gbar-due">&#128197; <?= $due10_txt; ?></span><?php endif; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; else: ?>
                                        <div class="p-4 text-center text-muted">Belum ada modul untuk dijadwalkan.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-3 small text-muted flex-wrap">
                            <span><i class="d-inline-block" style="width:12px;height:12px;background:#1e8e6b;border-radius:2px"></i> Finish</span>
                            <span><i class="d-inline-block" style="width:12px;height:12px;background:#e8590c;border-radius:2px"></i> In Progress (isi = % MH terpakai)</span>
                            <span><i class="d-inline-block" style="width:12px;height:12px;background:#9aa3af;border-radius:2px"></i> Belum Mulai</span>
                            <span><i class="d-inline-block" style="width:2px;height:12px;background:#4f46e5"></i> Hari ini</span>
                        </div>
                    </div>

                    <!-- Sub 2: detail per tahapan (pilih modul) -->
                    <div class="tab-pane fade" id="sub-tahapan" role="tabpanel">
                        <div class="mb-3" style="max-width:520px">
                            <label class="form-label small fw-bold text-muted mb-1">Pilih Modul</label>
                            <select class="form-select" id="pr-modul-selector">
                                <?php foreach ($modules as $mi => $mod): ?>
                                    <option value="<?= $mi; ?>"><?= html_escape($mod['module_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if (!empty($modules)): foreach ($modules as $mi => $mod):
                            $mod_plan   = (float)$mod['plan_manhour'];
                            $mod_aktual = (float)$mod['actual_manhour'];
                            $mod_selisih = $mod_aktual - $mod_plan;
                        ?>
                            <div class="pr-modul-detail" data-idx="<?= $mi; ?>" style="<?= $mi === 0 ? '' : 'display:none;'; ?>">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <strong><?= html_escape($mod['module_name']); ?></strong>
                                            <div class="small text-muted">Plan <?= pr_num($mod_plan); ?> MH &middot; Aktual <?= pr_num($mod_aktual); ?> MH &middot; Selisih <?= ($mod_selisih > 0 ? '+' : '') . pr_num($mod_selisih); ?></div>
                                        </div>
                                        <?php
                                        $max_t = 1;
                                        foreach ($mod['tahapan'] as $t) $max_t = max($max_t, (float)$t['plan_manhour'], (float)$t['actual_manhour']);
                                        foreach ($mod['tahapan'] as $t):
                                            $plan_w   = round(((float)$t['plan_manhour'] / $max_t) * 100);
                                            $akt_w    = round(((float)$t['actual_manhour'] / $max_t) * 100);
                                            $akt_cls  = 'active';
                                            if ($t['status'] === 'finish') $akt_cls = 'finish';
                                            $locked = ($t['status'] === 'locked');
                                        ?>
                                            <div class="pr-seq-row">
                                                <div class="pr-seq-name small">
                                                    <?= (int)$t['tahapan_order']; ?>. <?= html_escape($t['tahapan_name']); ?>
                                                    <span class="pic"><?= html_escape($t['pic_name'] ?: '-'); ?><?= $locked ? ' &middot; Locked' : ''; ?></span>
                                                </div>
                                                <div class="pr-seq-bar-track">
                                                    <div class="pr-seq-bar-plan" style="width:<?= $plan_w; ?>%;<?= $locked ? 'opacity:.4;' : ''; ?>"></div>
                                                    <?php if (!$locked): ?>
                                                        <div class="pr-seq-bar-aktual <?= $akt_cls; ?>" style="width:<?= $akt_w; ?>%"></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="pr-seq-mh"><?= $locked ? '—' : pr_num($t['actual_manhour']); ?> / <?= pr_num($t['plan_manhour']); ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="d-flex gap-3 mt-3 small text-muted flex-wrap">
                                            <span><i class="d-inline-block" style="width:10px;height:10px;background:#1e8e6b"></i> Aktual (Finish)</span>
                                            <span><i class="d-inline-block" style="width:10px;height:10px;background:#c48a1e"></i> Aktual (Active)</span>
                                            <span><i class="d-inline-block" style="width:10px;height:10px;background:#d7dee7"></i> Alokasi Plan MH</span>
                                            <span><i class="d-inline-block" style="width:10px;height:10px;background:#9aa3af;opacity:.4"></i> Locked</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Select2 untuk pilih project (searchable)
    if ($.fn.select2) {
        $('#pr-project-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Cari / pilih project...'
        });
    }

    // Selector modul pada tab detail tahapan
    $('#pr-modul-selector').on('change', function() {
        var idx = $(this).val();
        $('.pr-modul-detail').hide();
        $('.pr-modul-detail[data-idx="' + idx + '"]').show();
    });

    // Gantt: minimal selebar container. Jika lebar natural grid < area tersedia,
    // lebarkan per-minggu (--wk) supaya grid mengisi penuh; jika lebih -> scroll.
    function fitGantt() {
        var g = document.getElementById('pr-gantt-main');
        if (!g) return;
        var scroll = g.closest('.pr-gantt-scroll');
        if (!scroll) return;

        var weeks  = parseInt(g.getAttribute('data-weeks'), 10) || 1;
        var labelW = parseInt(g.getAttribute('data-label'), 10) || 0;
        var minWk  = parseInt(g.getAttribute('data-minwk'), 10) || 40;

        // clientWidth = lebar dalam tanpa scrollbar vertikal
        var avail = scroll.clientWidth - labelW - 2;
        if (avail <= 0) return;

        var fitWk = Math.floor(avail / weeks);
        var wk = Math.max(minWk, fitWk);   // minimal full-width, tapi tak lebih kecil dari minimum
        g.style.setProperty('--wk', wk + 'px');
    }

    // Jalankan setelah layout siap
    function fitGanttSoon() { requestAnimationFrame(fitGantt); setTimeout(fitGantt, 120); }

    fitGanttSoon();
    $(window).on('resize', fitGantt);
    // Recompute saat tab Jadwal / sub-tab dibuka (lebar container baru terukur)
    $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function() { fitGanttSoon(); });
});
</script>
