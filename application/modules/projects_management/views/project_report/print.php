<?php
/**
 * Halaman Print (browser print / Save as PDF) : Laporan & Jadwal Proyek
 * Standalone HTML (tidak melalui template). Otomatis memanggil window.print().
 * Variabel: $project, $modules, $others, $summary, $schedule, $report_date
 */
if (!function_exists('pr_num')) { function pr_num($n) { return number_format((float)$n, 1, ',', '.'); } }
if (!function_exists('pr_date')) { function pr_date($d) { return (!empty($d) && $d !== '0000-00-00') ? date('d-M-y', strtotime($d)) : '-'; } }

$project     = isset($project) ? $project : array();
$modules     = isset($modules) ? $modules : array();
$others      = isset($others) ? $others : array();
$summary     = isset($summary) ? $summary : array('plan_manhour' => 0, 'actual_manhour' => 0, 'selisih' => 0, 'total_modules' => 0, 'finish_modules' => 0, 'progress_pct' => 0);
$schedule    = isset($schedule) ? $schedule : array('rows' => array(), 'today_pct' => null, 'year' => date('Y'));
$report_date = isset($report_date) ? $report_date : date('d M Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Laporan Proyek - <?= html_escape($project['project_code']); ?></title>
<style>
    * { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1a2233; margin: 24px; }
    h1 { font-size: 20px; margin: 0 0 2px; }
    h2 { font-size: 15px; margin: 20px 0 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px; }
    .muted { color: #6b7684; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; }
    .data th { background: #1f3a5f; color: #fff; padding: 6px 8px; font-size: 11px; text-align: left; }
    .data td { border: 1px solid #dde1e6; padding: 5px 8px; font-size: 11px; }
    .num { text-align: right; }
    .center { text-align: center; }
    .neg { color: #a6321d; }
    .pos { color: #1e8e6b; }
    .meta td { padding: 3px 8px; font-size: 12px; }
    .ledger td { border: 1px solid #dde1e6; text-align: center; padding: 10px; }
    .ledger .val { font-size: 18px; font-weight: bold; }
    .bar-out { background: #e7e9ec; height: 10px; width: 100%; border-radius: 2px; overflow: hidden; }
    .bar-in { background: #1f3a5f; height: 10px; }

    /* Toolbar (tidak ikut tercetak) */
    .toolbar { position: sticky; top: 0; background: #fff; padding: 10px 0 16px; margin-bottom: 8px; border-bottom: 1px solid #eee; }
    .btn { display: inline-block; border: 1px solid #1f3a5f; background: #1f3a5f; color: #fff; padding: 8px 16px; border-radius: 4px; font-size: 13px; cursor: pointer; text-decoration: none; }
    .btn.secondary { background: #fff; color: #1f3a5f; }

    @media print {
        body { margin: 0; }
        .toolbar { display: none !important; }
        h2 { page-break-after: avoid; }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; }
        .page-break { page-break-before: always; }
        @page { size: A4 landscape; margin: 12mm; }
    }
</style>
</head>
<body>

<div class="toolbar">
    <button class="btn" onclick="window.print()">&#128424; Cetak / Simpan PDF</button>
    <button class="btn secondary" onclick="window.close()">Tutup</button>
</div>

<h1><?= html_escape($project['project_name']); ?></h1>
<div class="muted">
    <?= html_escape($project['project_code']); ?> &middot; Client: <?= html_escape($project['client_name'] ?: '-'); ?>
    &middot; Status: <?= html_escape($project['status']); ?> &middot; Tanggal Laporan: <?= $report_date; ?>
</div>

<table class="meta" style="margin-top:10px">
    <tr>
        <td width="12%"><b>PM</b></td><td width="21%"><?= html_escape($project['pm_name'] ?: '-'); ?></td>
        <td width="12%"><b>Bisnis Analis</b></td><td width="21%"><?= html_escape($project['ba_names']); ?></td>
        <td width="12%"><b>Programmer</b></td><td width="22%"><?= html_escape($project['programmer_names']); ?></td>
    </tr>
    <tr>
        <td><b>QA</b></td><td><?= html_escape($project['qa_names']); ?></td>
        <td><b>Target Selesai</b></td><td><?= pr_date($project['end_date']); ?></td>
        <td><b>Total Modul</b></td><td><?= (int)$summary['total_modules']; ?> (Finish: <?= (int)$summary['finish_modules']; ?>)</td>
    </tr>
</table>

<!-- Ledger -->
<table class="ledger" style="margin-top:12px">
    <tr>
        <td width="25%"><div class="muted">PLAN MANHOUR</div><div class="val"><?= pr_num($summary['plan_manhour']); ?></div></td>
        <td width="25%"><div class="muted">AKTUAL MANHOUR</div><div class="val"><?= pr_num($summary['actual_manhour']); ?></div></td>
        <td width="25%"><div class="muted">SELISIH</div><div class="val <?= ($summary['selisih'] > 0) ? 'neg' : 'pos'; ?>"><?= ($summary['selisih'] > 0 ? '+' : '') . pr_num($summary['selisih']); ?></div></td>
        <td width="25%"><div class="muted">% PROGRESS MODUL</div><div class="val"><?= pr_num($summary['progress_pct']); ?>%</div></td>
    </tr>
</table>

<h2>Rincian per Modul</h2>
<table class="data">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th>Modul</th>
            <th width="9%">Tahapan</th>
            <th width="18%">PIC Utama</th>
            <th width="10%">Due Date</th>
            <th width="9%" class="num">Plan MH</th>
            <th width="9%" class="num">Aktual MH</th>
            <th width="9%" class="num">Selisih</th>
            <th width="10%">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($modules)): $no = 1; foreach ($modules as $mod):
            $selisih = $mod['selisih_manhour']; ?>
            <tr>
                <td class="center"><?= $no++; ?></td>
                <td><?= html_escape($mod['module_name']); ?></td>
                <td class="center"><?= (int)$mod['finished_tahapan']; ?>/<?= (int)$mod['total_tahapan']; ?></td>
                <td><?= html_escape($mod['pic_utama']); ?></td>
                <td><?= pr_date($mod['due_date_modul']); ?></td>
                <td class="num"><?= pr_num($mod['plan_manhour']); ?></td>
                <td class="num"><?= pr_num($mod['actual_manhour']); ?></td>
                <td class="num <?= ($selisih > 0) ? 'neg' : 'pos'; ?>"><?= ($selisih > 0 ? '+' : '') . pr_num($selisih); ?></td>
                <td><?= html_escape($mod['status_label']); ?></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="9" class="center">Belum ada modul.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Others -->
<h2>Catatan / Aktivitas Tambahan (Others)</h2>
<table class="data">
    <thead>
        <tr>
            <th width="10%">Tanggal</th>
            <th width="22%">Modul</th>
            <th>Aktivitas</th>
            <th width="8%" class="num">MH</th>
            <th width="16%">Oleh</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($others)): foreach ($others as $o): ?>
            <tr>
                <td><?= pr_date($o['task_date']); ?></td>
                <td><?= html_escape($o['module_name']); ?></td>
                <td><?= html_escape($o['task_description']); ?></td>
                <td class="num"><?= pr_num($o['manhour']); ?></td>
                <td><?= html_escape($o['user_name'] ?: '-'); ?></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="5" class="center">Tidak ada aktivitas tambahan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- ============ JADWAL ============ -->
<div class="page-break"></div>
<h1>Jadwal / Schedule Proyek</h1>
<div class="muted">Tahun <?= $schedule['year']; ?> &middot; isian bar = % manhour terpakai (proksi progress)</div>

<h2>Ringkasan Progress per Modul</h2>
<table class="data">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th>Modul</th>
            <th width="20%">PIC Utama</th>
            <th width="9%">Tahapan</th>
            <th width="12%">Status</th>
            <th width="30%">Progress (% MH)</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($schedule['rows'])): $no = 1; foreach ($schedule['rows'] as $r): ?>
            <tr>
                <td class="center"><?= $no++; ?></td>
                <td><?= html_escape($r['module_name']); ?></td>
                <td><?= html_escape($r['pic_utama']); ?></td>
                <td class="center"><?= (int)$r['finished_tahapan']; ?>/<?= (int)$r['total_tahapan']; ?></td>
                <td><?= html_escape($r['status_label']); ?></td>
                <td>
                    <table style="width:100%"><tr>
                        <td width="80%" style="border:none;padding:0 6px 0 0"><div class="bar-out"><div class="bar-in" style="width:<?= (int)$r['progress_pct']; ?>%"></div></div></td>
                        <td width="20%" class="num" style="border:none;padding:0"><?= (int)$r['progress_pct']; ?>%</td>
                    </tr></table>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="6" class="center">Belum ada modul.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h2>Detail per Tahapan</h2>
<?php if (!empty($modules)): foreach ($modules as $mod): ?>
    <p style="margin:12px 0 4px"><b><?= html_escape($mod['module_name']); ?></b>
        <span class="muted">— Plan <?= pr_num($mod['plan_manhour']); ?> / Aktual <?= pr_num($mod['actual_manhour']); ?> MH</span></p>
    <table class="data">
        <thead>
            <tr>
                <th width="6%">#</th>
                <th>Tahapan</th>
                <th width="20%">PIC</th>
                <th width="12%">Status</th>
                <th width="11%" class="num">Plan MH</th>
                <th width="11%" class="num">Aktual MH</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mod['tahapan'] as $t):
                $locked = ($t['status'] === 'locked'); ?>
                <tr>
                    <td class="center"><?= (int)$t['tahapan_order']; ?></td>
                    <td><?= html_escape($t['tahapan_name']); ?></td>
                    <td><?= html_escape($t['pic_name'] ?: '-'); ?></td>
                    <td><?= html_escape(ucfirst($t['status'])); ?></td>
                    <td class="num"><?= pr_num($t['plan_manhour']); ?></td>
                    <td class="num"><?= $locked ? '—' : pr_num($t['actual_manhour']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; endif; ?>

<script>
    // Auto-buka dialog print saat halaman siap
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 300);
    });
</script>
</body>
</html>
