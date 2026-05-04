<?php
$status_map = [
    0 => ['label' => 'Open',    'cls' => 'bg-secondary'],
    1 => ['label' => 'Process', 'cls' => 'bg-primary'],
    2 => ['label' => 'Pending', 'cls' => 'bg-warning text-dark'],
    3 => ['label' => 'Cancel',  'cls' => 'bg-danger'],
    4 => ['label' => 'Done',    'cls' => 'bg-success'],
    5 => ['label' => 'Close',   'cls' => 'bg-dark'],
    6 => ['label' => 'Revisi',  'cls' => 'bg-info'],
];
?>

<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>No. Ticket</th>
                <th>Client</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Report</th>
                <th class="text-end">MH Plan</th>
                <th class="text-end">MH Actual</th>
                <th class="text-center">Status</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <tr>
                    <td colspan="11" class="text-center text-muted py-4">Tidak ada ticket.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tickets as $i => $t):
                    $st = $status_map[$t->status] ?? ['label' => $t->status, 'cls' => 'bg-light text-dark'];
                ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><strong> <?= htmlspecialchars($t->no_ticket) ?> </strong></td>
                        <td><?= htmlspecialchars($t->client_name) ?></td>
                        <td><?= htmlspecialchars($t->category_name) ?></td>
                        <td><?= htmlspecialchars($t->sub_category_name) ?></td>
                        <td style="max-width:200px;white-space:normal"><?= htmlspecialchars($t->report) ?></td>
                        <td class="text-end"><?= number_format($t->man_hour_plan   ?? 0, 1) ?></td>
                        <td class="text-end"><?= number_format($t->man_hour_actual ?? 0, 1) ?></td>
                        <td class="text-center">
                            <span class="status-pill badge <?= $st['cls'] ?>"><?= $st['label'] ?></span>
                        </td>
                        <td><?= $t->due_date ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>