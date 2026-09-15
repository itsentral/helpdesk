<?php
/**
 * Halaman Print khusus GANTT CHART (mingguan) : Jadwal Proyek
 * Standalone HTML, otomatis window.print().
 * Variabel: $project, $schedule, $report_date
 */
if (!function_exists('pr_gdate')) { function pr_gdate($d) { return (!empty($d) && $d !== '0000-00-00') ? date('d/m/Y', strtotime($d)) : '-'; } }

$project     = isset($project) ? $project : array();
$schedule    = isset($schedule) ? $schedule : array();
$schedule   += array('rows' => array(), 'months' => array(), 'total_weeks' => 1, 'today_week' => null, 'year' => date('Y'));
$report_date = isset($report_date) ? $report_date : date('d M Y');

$WEEK_W  = 46;
$LABEL_W = 230;
$ROW_H   = 32;
$total_weeks = (int)$schedule['total_weeks'];
$grid_w  = $total_weeks * $WEEK_W;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<title>Gantt Chart - <?= html_escape(isset($project['project_code']) ? $project['project_code'] : ''); ?></title>
<style>
    * { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1a2233; margin: 20px; }
    h1 { font-size: 18px; margin: 0 0 2px; }
    .muted { color: #6b7684; font-size: 11px; }

    .toolbar { padding: 8px 0 14px; }
    .btn { display: inline-block; border: 1px solid #4f46e5; background: #4f46e5; color: #fff; padding: 8px 16px; border-radius: 4px; font-size: 13px; cursor: pointer; text-decoration: none; }
    .btn.secondary { background: #fff; color: #4f46e5; }

    .gantt-wrap { overflow-x: auto; border: 1px solid #e5e7eb; border-radius: 6px; }
    .gantt { position: relative; }
    .ghead { display: flex; }
    .glabel-head { flex: 0 0 auto; background: #4f46e5; color: #fff; font-weight: 700; font-size: 11px; display: flex; align-items: center; padding: 0 10px; }
    .gmonths { flex: 0 0 auto; display: flex; background: #4f46e5; }
    .gmonth { flex: 0 0 auto; color: #fff; font-size: 10.5px; font-weight: 600; text-align: center; line-height: 24px; height: 24px; border-left: 1px solid rgba(255,255,255,.25); overflow: hidden; white-space: nowrap; }
    .gweeks-head { flex: 0 0 auto; display: flex; background: #6366f1; }
    .gweek-h { flex: 0 0 auto; color: #e0e7ff; font-size: 8.5px; text-align: center; line-height: 16px; height: 16px; border-left: 1px solid rgba(255,255,255,.15); }
    .glabel-sub { flex: 0 0 auto; background: #6366f1; }

    .grow { display: flex; align-items: center; border-bottom: 1px solid #eef0f2; }
    .grow:nth-child(even) { background: #f8fafc; }
    .glabel { flex: 0 0 auto; display: flex; align-items: center; gap: 6px; height: 100%; padding: 0 10px; border-right: 1px solid #e5e7eb; }
    .dot { width: 8px; height: 8px; border-radius: 50%; flex: 0 0 auto; }
    .glabel-txt { font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .gtrack { flex: 0 0 auto; position: relative; height: 100%; }
    .gbar { position: absolute; top: 50%; transform: translateY(-50%); height: 16px; border-radius: 8px; overflow: hidden; display: flex; align-items: center; }
    .gbar.finish { background: #1e8e6b; }
    .gbar.progress { background: #e8590c; }
    .gbar.none { background: #9aa3af; }
    .gbar-fill { position: absolute; left: 0; top: 0; height: 100%; background: rgba(255,255,255,.28); }
    .gbar-label { position: relative; z-index: 1; color: #fff; font-size: 8.5px; font-weight: 600; padding: 0 6px; white-space: nowrap; }
    .glines { position: absolute; top: 0; bottom: 0; pointer-events: none; }
    .mline { position: absolute; top: 0; bottom: 0; width: 0; border-left: 1px solid #e5e7eb; }
    .today { position: absolute; top: 0; bottom: 0; width: 0; border-left: 2px solid #4f46e5; }

    .legend { margin-top: 12px; font-size: 11px; color: #6b7684; }
    .legend span { display: inline-block; margin-right: 16px; }
    .legend i { display: inline-block; width: 11px; height: 11px; border-radius: 2px; vertical-align: middle; margin-right: 4px; }

    @media print {
        body { margin: 0; }
        .toolbar { display: none !important; }
        .gantt-wrap { overflow: visible; border: none; }
        @page { size: A4 landscape; margin: 8mm; }
    }
</style>
</head>
<body>

<div class="toolbar">
    <button class="btn" onclick="window.print()">&#128424; Cetak / Simpan PDF</button>
    <button class="btn secondary" onclick="window.close()">Tutup</button>
</div>

<h1>Gantt Chart - <?= html_escape(isset($project['project_name']) ? $project['project_name'] : ''); ?></h1>
<div class="muted">
    <?= html_escape(isset($project['project_code']) ? $project['project_code'] : ''); ?>
    &middot; Client: <?= html_escape(!empty($project['client_name']) ? $project['client_name'] : '-'); ?>
    &middot; Tanggal Cetak: <?= $report_date; ?> &middot; Skala mingguan
</div>

<div class="gantt-wrap" style="margin-top:12px;">
    <div class="gantt" style="width:<?= $LABEL_W + $grid_w; ?>px;">
        <!-- Header bulan -->
        <div class="ghead">
            <div class="glabel-head" style="width:<?= $LABEL_W; ?>px;">TASK</div>
            <div class="gmonths" style="width:<?= $grid_w; ?>px;">
                <?php foreach ($schedule['months'] as $mo): ?>
                    <div class="gmonth" style="width:<?= $mo['weeks'] * $WEEK_W; ?>px;"><?= html_escape($mo['label']); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Header minggu -->
        <div class="ghead">
            <div class="glabel-head glabel-sub" style="width:<?= $LABEL_W; ?>px;">&nbsp;</div>
            <div class="gweeks-head" style="width:<?= $grid_w; ?>px;">
                <?php for ($w = 1; $w <= $total_weeks; $w++): ?>
                    <div class="gweek-h" style="width:<?= $WEEK_W; ?>px;">W<?= $w; ?></div>
                <?php endfor; ?>
            </div>
        </div>
        <!-- Body -->
        <div style="position:relative;">
            <div class="glines" style="left:<?= $LABEL_W; ?>px;width:<?= $grid_w; ?>px;">
                <?php $acc = 0; foreach ($schedule['months'] as $mo): $acc += $mo['weeks']; ?>
                    <div class="mline" style="left:<?= $acc * $WEEK_W; ?>px;"></div>
                <?php endforeach; ?>
                <?php if ($schedule['today_week'] !== null): ?>
                    <div class="today" style="left:<?= $schedule['today_week'] * $WEEK_W; ?>px;"></div>
                <?php endif; ?>
            </div>

            <?php if (!empty($schedule['rows'])): foreach ($schedule['rows'] as $r):
                $gcls = 'progress'; $dot = '#e8590c';
                if ($r['status'] === 'finish') { $gcls = 'finish'; $dot = '#1e8e6b'; }
                elseif ($r['status_label'] === 'Belum Mulai') { $gcls = 'none'; $dot = '#9aa3af'; }
                $bar_left  = $r['offset_weeks'] * $WEEK_W;
                $bar_width = max($WEEK_W, $r['len_weeks'] * $WEEK_W);
            ?>
                <div class="grow" style="height:<?= $ROW_H; ?>px;">
                    <div class="glabel" style="width:<?= $LABEL_W; ?>px;">
                        <span class="dot" style="background:<?= $dot; ?>"></span>
                        <span class="glabel-txt"><?= html_escape($r['module_name']); ?></span>
                    </div>
                    <div class="gtrack" style="width:<?= $grid_w; ?>px;">
                        <?php $due10_txt = !empty($r['due_date_10']) ? date('d M Y', strtotime($r['due_date_10'])) : null; ?>
                        <div class="gbar <?= $gcls; ?>" style="left:<?= $bar_left; ?>px;width:<?= $bar_width; ?>px;">
                            <div class="gbar-fill" style="width:<?= (int)$r['progress_pct']; ?>%"></div>
                            <span class="gbar-label"><?= (int)$r['progress_pct']; ?>%<?php if ($due10_txt): ?> &middot; <?= $due10_txt; ?><?php endif; ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div style="padding:20px;text-align:center;color:#6b7684;">Belum ada modul untuk dijadwalkan.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="legend">
    <span><i style="background:#1e8e6b"></i>Finish</span>
    <span><i style="background:#e8590c"></i>In Progress (isi = % MH terpakai)</span>
    <span><i style="background:#9aa3af"></i>Belum Mulai</span>
    <span><i style="background:#4f46e5;width:2px"></i>Hari ini</span>
</div>

<script>
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 300);
    });
</script>
</body>
</html>
