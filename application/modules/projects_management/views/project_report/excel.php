<?php
/**
 * View Excel : Laporan & Jadwal Proyek (HTML table -> .xls)
 * Variabel: $project, $modules, $others, $summary, $schedule, $report_date
 */
if (!function_exists('pr_num_x')) { function pr_num_x($n) { return number_format((float)$n, 2, '.', ''); } }
if (!function_exists('pr_date_x')) { function pr_date_x($d) { return (!empty($d) && $d !== '0000-00-00') ? date('d-m-Y', strtotime($d)) : '-'; } }

$project     = isset($project) ? $project : array();
$modules     = isset($modules) ? $modules : array();
$others      = isset($others) ? $others : array();
$summary     = isset($summary) ? $summary : array('plan_manhour' => 0, 'actual_manhour' => 0, 'selisih' => 0, 'total_modules' => 0, 'finish_modules' => 0, 'progress_pct' => 0);
$schedule    = isset($schedule) ? $schedule : array('rows' => array(), 'today_pct' => null, 'year' => date('Y'));
$report_date = isset($report_date) ? $report_date : date('d M Y');
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="4" cellspacing="0">
    <tr><td colspan="9" style="font-size:16px;font-weight:bold;background:#1f3a5f;color:#fff;">LAPORAN &amp; JADWAL PROYEK</td></tr>
    <tr><td colspan="2" style="font-weight:bold;">Kode Project</td><td colspan="7"><?= html_escape($project['project_code']); ?></td></tr>
    <tr><td colspan="2" style="font-weight:bold;">Nama Project</td><td colspan="7"><?= html_escape($project['project_name']); ?></td></tr>
    <tr><td colspan="2" style="font-weight:bold;">Client</td><td colspan="7"><?= html_escape($project['client_name'] ?: '-'); ?></td></tr>
    <tr><td colspan="2" style="font-weight:bold;">Status</td><td colspan="7"><?= html_escape($project['status']); ?></td></tr>
    <tr><td colspan="2" style="font-weight:bold;">PM</td><td colspan="7"><?= html_escape($project['pm_name'] ?: '-'); ?></td></tr>
    <tr><td colspan="2" style="font-weight:bold;">Bisnis Analis</td><td colspan="7"><?= html_escape($project['ba_names']); ?></td></tr>
    <tr><td colspan="2" style="font-weight:bold;">Programmer</td><td colspan="7"><?= html_escape($project['programmer_names']); ?></td></tr>
    <tr><td colspan="2" style="font-weight:bold;">QA</td><td colspan="7"><?= html_escape($project['qa_names']); ?></td></tr>
    <tr><td colspan="2" style="font-weight:bold;">Target Selesai</td><td colspan="7"><?= pr_date_x($project['end_date']); ?></td></tr>
    <tr><td colspan="2" style="font-weight:bold;">Tanggal Laporan</td><td colspan="7"><?= $report_date; ?></td></tr>
</table>
<br />

<table border="1" cellpadding="4" cellspacing="0">
    <tr style="background:#dde1e6;font-weight:bold;">
        <td>Plan Manhour</td><td>Aktual Manhour</td><td>Selisih</td><td>% Progress Modul</td><td>Total Modul</td><td>Modul Finish</td>
    </tr>
    <tr>
        <td><?= pr_num_x($summary['plan_manhour']); ?></td>
        <td><?= pr_num_x($summary['actual_manhour']); ?></td>
        <td><?= pr_num_x($summary['selisih']); ?></td>
        <td><?= $summary['progress_pct']; ?>%</td>
        <td><?= (int)$summary['total_modules']; ?></td>
        <td><?= (int)$summary['finish_modules']; ?></td>
    </tr>
</table>
<br />

<table border="1" cellpadding="4" cellspacing="0">
    <tr><td colspan="9" style="font-weight:bold;background:#1f3a5f;color:#fff;">RINCIAN PER MODUL</td></tr>
    <tr style="background:#dde1e6;font-weight:bold;">
        <td>No</td><td>Modul</td><td>Tahapan</td><td>PIC Utama</td><td>Due Date</td>
        <td>Plan MH</td><td>Aktual MH</td><td>Selisih</td><td>Status</td>
    </tr>
    <?php if (!empty($modules)): $no = 1; foreach ($modules as $mod): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= html_escape($mod['module_name']); ?></td>
            <td><?= (int)$mod['finished_tahapan']; ?>/<?= (int)$mod['total_tahapan']; ?></td>
            <td><?= html_escape($mod['pic_utama']); ?></td>
            <td><?= pr_date_x($mod['due_date_modul']); ?></td>
            <td><?= pr_num_x($mod['plan_manhour']); ?></td>
            <td><?= pr_num_x($mod['actual_manhour']); ?></td>
            <td><?= pr_num_x($mod['selisih_manhour']); ?></td>
            <td><?= html_escape($mod['status_label']); ?></td>
        </tr>
    <?php endforeach; else: ?>
        <tr><td colspan="9">Belum ada modul.</td></tr>
    <?php endif; ?>
</table>
<br />

<table border="1" cellpadding="4" cellspacing="0">
    <tr><td colspan="5" style="font-weight:bold;background:#1f3a5f;color:#fff;">CATATAN / AKTIVITAS TAMBAHAN (OTHERS)</td></tr>
    <tr style="background:#dde1e6;font-weight:bold;">
        <td>Tanggal</td><td>Modul</td><td>Aktivitas</td><td>MH</td><td>Oleh</td>
    </tr>
    <?php if (!empty($others)): foreach ($others as $o): ?>
        <tr>
            <td><?= pr_date_x($o['task_date']); ?></td>
            <td><?= html_escape($o['module_name']); ?></td>
            <td><?= html_escape($o['task_description']); ?></td>
            <td><?= pr_num_x($o['manhour']); ?></td>
            <td><?= html_escape($o['user_name'] ?: '-'); ?></td>
        </tr>
    <?php endforeach; else: ?>
        <tr><td colspan="5">Tidak ada aktivitas tambahan.</td></tr>
    <?php endif; ?>
</table>
<br />

<table border="1" cellpadding="4" cellspacing="0">
    <tr><td colspan="6" style="font-weight:bold;background:#1f3a5f;color:#fff;">JADWAL - RINGKASAN PROGRESS PER MODUL (Tahun <?= $schedule['year']; ?>)</td></tr>
    <tr style="background:#dde1e6;font-weight:bold;">
        <td>No</td><td>Modul</td><td>PIC Utama</td><td>Tahapan</td><td>Status</td><td>Progress (% MH)</td>
    </tr>
    <?php if (!empty($schedule['rows'])): $no = 1; foreach ($schedule['rows'] as $r): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= html_escape($r['module_name']); ?></td>
            <td><?= html_escape($r['pic_utama']); ?></td>
            <td><?= (int)$r['finished_tahapan']; ?>/<?= (int)$r['total_tahapan']; ?></td>
            <td><?= html_escape($r['status_label']); ?></td>
            <td><?= (int)$r['progress_pct']; ?>%</td>
        </tr>
    <?php endforeach; else: ?>
        <tr><td colspan="6">Belum ada modul.</td></tr>
    <?php endif; ?>
</table>
<br />

<table border="1" cellpadding="4" cellspacing="0">
    <tr><td colspan="6" style="font-weight:bold;background:#1f3a5f;color:#fff;">JADWAL - DETAIL PER TAHAPAN</td></tr>
    <?php if (!empty($modules)): foreach ($modules as $mod): ?>
        <tr style="background:#eef0f2;font-weight:bold;"><td colspan="6"><?= html_escape($mod['module_name']); ?></td></tr>
        <tr style="background:#dde1e6;font-weight:bold;">
            <td>#</td><td>Tahapan</td><td>PIC</td><td>Status</td><td>Plan MH</td><td>Aktual MH</td>
        </tr>
        <?php foreach ($mod['tahapan'] as $t):
            $locked = ($t['status'] === 'locked'); ?>
            <tr>
                <td><?= (int)$t['tahapan_order']; ?></td>
                <td><?= html_escape($t['tahapan_name']); ?></td>
                <td><?= html_escape($t['pic_name'] ?: '-'); ?></td>
                <td><?= html_escape(ucfirst($t['status'])); ?></td>
                <td><?= pr_num_x($t['plan_manhour']); ?></td>
                <td><?= $locked ? '-' : pr_num_x($t['actual_manhour']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; endif; ?>
</table>
