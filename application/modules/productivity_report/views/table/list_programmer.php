<?php

function mh_bar($plan, $actual, $max_val)
{
    $plan    = (float)($plan    ?? 0);
    $actual  = (float)($actual  ?? 0);
    $max_val = (float)($max_val ?? 1);
    $pct     = $max_val > 0 ? min(($actual / $max_val) * 100, 100) : 0;
    $color   = $actual <= $plan ? '#22c55e' : '#ef4444';
    return '
        <div>' . number_format($actual, 1) . ' <small class="text-muted">/ ' . number_format($plan, 1) . '</small></div>
        <div class="mh-bar-wrap"><div class="mh-bar" style="width:' . $pct . '%;background:' . $color . '"></div></div>
    ';
}

$max_actual = 1;
foreach ($rows as $r) {
    $val = (float)($r->total_man_hour_actual ?? 0);
    if ($val > $max_actual) $max_actual = $val;
}
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Programmer</th>
            <th class="text-center">Total Ticket</th>
            <th class="text-center">Close</th>
            <th class="text-center">Done</th>
            <th class="text-center">Open</th>
            <th class="text-center">Process</th>
            <th class="text-center">Pending</th>
            <th class="text-center">Revisi</th>
            <th class="text-end">MH Plan</th>
            <th class="text-end">MH Actual</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="12" class="text-center text-muted py-4">
                    <i class="ti ti-inbox mb-2 d-block" style="font-size:2rem;"></i>
                    Tidak ada data Programmer.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $i => $r): ?>
                <tr class="row-detail"
                    data-user="<?= $r->id_user ?>"
                    data-role="programmer"
                    data-nama="<?= htmlspecialchars($r->nama) ?>">
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($r->nama) ?></strong></td>
                    <td class="text-center"><?= $r->total_ticket ?></td>
                    <td class="text-center"><span class="badge bg-dark"><?= $r->total_close ?></span></td>
                    <td class="text-center"><span class="badge bg-success"><?= $r->total_done ?></span></td>
                    <td class="text-center"><span class="badge bg-secondary"><?= $r->total_open ?></span></td>
                    <td class="text-center"><span class="badge bg-primary"><?= $r->total_process ?></span></td>
                    <td class="text-center"><span class="badge bg-warning text-dark"><?= $r->total_pending ?></span></td>
                    <td class="text-center"><span class="badge bg-info text-dark"><?= $r->total_revisi ?></span></td>
                    <td class="text-end"><?= number_format($r->total_man_hour_plan ?? 0, 1) ?> h</td>
                    <td class="text-end" style="min-width:120px">
                        <?= mh_bar($r->total_man_hour_plan, $r->total_man_hour_actual, $max_actual) ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <tr class="table-secondary fw-bold">
                <td colspan="2">TOTAL (<?= count($rows) ?> orang)</td>
                <td class="text-center"><?= $summary['total_ticket'] ?></td>
                <td class="text-center"><?= $summary['total_close'] ?></td>
                <td class="text-center"><?= $summary['total_done'] ?></td>
                <td class="text-center"><?= $summary['total_open'] ?></td>
                <td class="text-center"><?= $summary['total_process'] ?></td>
                <td class="text-center"><?= $summary['total_pending'] ?></td>
                <td class="text-center"><?= $summary['total_revisi'] ?></td>
                <td class="text-end"><?= number_format($summary['total_man_hour_plan'], 1) ?> h</td>
                <td class="text-end"><?= number_format($summary['total_man_hour_actual'], 1) ?> h</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>