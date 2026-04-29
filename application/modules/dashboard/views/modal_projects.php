<style>
    .pd-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .pd-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pd-icon-box {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #EEEDFE;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pd-icon {
        font-size: 16px;
        color: #534AB7;
    }

    .pd-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
    }

    .pd-subtitle {
        margin: 0;
        font-size: 11px;
        color: #6c757d;
    }

    .pd-close {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 8px;
        font-size: 18px;
        color: #6c757d;
        border-radius: 6px;
        line-height: 1;
    }

    /* Stat pills */
    .pd-stats {
        padding: 12px 20px;
        border-bottom: 1px solid #f1f3f5;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        flex-shrink: 0;
        position: sticky;
        top: 0;
        z-index: 10;
        background: #fff;
    }

    .modal-body {
        padding: 0 5px 25px 5px !important;
        margin: 0 5px 25px 5px !important;
    }

    .pd-pill {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    /* Table */
    .pd-table-wrap {
        overflow-y: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .pd-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .pd-table thead tr {
        background: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .pd-table th {
        padding: 10px 14px;
        font-size: 11px;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        border-bottom: 1px solid #e9ecef;
    }

    .pd-table td {
        padding: 9px 14px;
        border-bottom: 1px solid #f1f3f5;
    }

    .pd-muted {
        color: #6c757d;
        font-size: 11px;
    }

    .pd-index {
        color: #adb5bd;
        font-size: 11px;
    }

    .pd-report {
        font-weight: 500;
        max-width: 260px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pd-badge {
        font-size: 11px;
        background: #f1f3f5;
        color: #495057;
        padding: 2px 7px;
        border-radius: 4px;
        white-space: nowrap;
    }

    .pd-status {
        display: inline-flex;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .pd-link {
        padding: 2px 8px;
        font-size: 11px;
        border-radius: 20px;
        border: 1px solid #dee2e6;
        color: #495057;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        white-space: nowrap;
    }

    .pd-empty {
        text-align: center;
        padding: 20px;
        color: #6c757d;
    }
</style>

<?php
$status_map = [
    0 => ['Open',    'ps-open'],
    1 => ['Process', 'ps-process'],
    2 => ['Pending', 'ps-pending'],
    4 => ['Done',    'ps-done'],
    6 => ['Revisi',  'ps-revisi'],
];

$stat_defs = [
    ['key' => 'total',   'label' => 'Total',   'bg' => '#EEEDFE', 'color' => '#534AB7'],
    ['key' => 'open',    'label' => 'Open',    'bg' => '#FCEBEB', 'color' => '#A32D2D'],
    ['key' => 'process', 'label' => 'Process', 'bg' => '#E6F1FB', 'color' => '#185FA5'],
    ['key' => 'pending', 'label' => 'Pending', 'bg' => '#FAEEDA', 'color' => '#854F0B'],
    ['key' => 'done',    'label' => 'Done',    'bg' => '#E9F7EF', 'color' => '#196F3D'],
    ['key' => 'revisi',  'label' => 'Revisi',  'bg' => '#F4C0D1', 'color' => '#72243E'],
];
?>

<div class="modal-header pd-header">
    <div class="pd-header-left">
        <div class="pd-icon-box">
            <i class="ti ti-building pd-icon"></i>
        </div>
        <div>
            <h5 class="pd-title mb-0"><?= htmlspecialchars($client_name ?? '-') ?></h5>
            <p class="pd-subtitle mb-0"><?= $summary['total'] ?> tiket total</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body" style="max-height: 85vh; overflow:auto;">
    <div class="pd-stats">
        <?php foreach ($stat_defs as $s): ?>
            <?php if ($summary[$s['key']] > 0 || $s['key'] === 'total'): ?>
                <div class="pd-pill" style="background:<?= $s['bg'] ?>; color:<?= $s['color'] ?>;">
                    <b><?= $summary[$s['key']] ?></b>
                    <span><?= $s['label'] ?></span>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="table-responsive">
        <table class="pd-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No Tiket</th>
                    <th>Laporan</th>
                    <th>Sub Cat</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>PIC</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tickets)): ?>
                    <tr>
                        <td colspan="8" class="pd-empty">Tidak ada tiket ditemukan untuk filter ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tickets as $i => $t): ?>
                        <?php $st = $status_map[$t->status] ?? ['?', 'ps-open']; ?>
                        <tr>
                            <td class="pd-index"><?= $i + 1 ?></td>
                            <td class="pd-muted"><?= htmlspecialchars($t->no_ticket ?? '-') ?></td>
                            <td>
                                <div class="pd-report" title="<?= htmlspecialchars($t->report) ?>">
                                    <?= htmlspecialchars($t->report) ?>
                                </div>
                            </td>
                            <td>
                                <span class="pd-badge">
                                    <?= htmlspecialchars($t->sub_category_name ?? '-') ?>
                                </span>
                            </td>
                            <td>
                                <span class="pd-status <?= $st[1] ?>">
                                    <?= $st[0] ?>
                                </span>
                            </td>
                            <td><?= $t->due_date ? substr($t->due_date, 0, 10) : '-' ?></td>
                            <td><?= htmlspecialchars($t->pic ?? '-') ?></td>
                            <td>
                                <a href="<?= site_url('ticket/view_ticket/' . $t->id) ?>" target="_blank" class="pd-link">
                                    <i class="ti ti-external-link"></i> Buka
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>