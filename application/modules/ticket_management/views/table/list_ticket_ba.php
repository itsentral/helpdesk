<?php
$statusConfig = [
    0 => ['label' => 'Open',    'class' => 'badge bg-primary'],
    1 => ['label' => 'Process', 'class' => 'badge bg-info'],
    2 => ['label' => 'Pending', 'class' => 'badge bg-warning text-dark'],
    3 => ['label' => 'Cancel',  'class' => 'badge bg-danger'],
    4 => ['label' => 'Done',    'class' => 'badge bg-success'],
    5 => ['label' => 'Close',   'class' => 'badge bg-secondary'],
    6 => ['label' => 'Revisi',  'class' => 'badge bg-orange'],
];

$grouped = [];
foreach ($ticket as $row) {
    $grouped[$row->pic_id] = [
        'name'    => $row->pic,
        'tickets' => $row->_tickets,
    ];
}
?>

<?php if (empty($grouped)) : ?>
    <div class="text-center text-muted py-5">
        <i class="fa-solid fa-inbox fa-3x mb-3 d-block"></i>
        Tidak ada ticket untuk BA.
    </div>
<?php else : ?>

    <div class="accordion" id="accordion-ba">
        <?php foreach ($grouped as $pic_id => $data) :
            $total        = count($data['tickets']);
            $collapseId   = 'collapse-ba-' . $pic_id;
            $headingId    = 'heading-ba-'  . $pic_id;
            $sortableId   = 'sortable-ba-' . $pic_id;

            $countOpen    = 0;
            $countProcess = 0;
            $countDone    = 0;
            $countOverdue = 0;

            foreach ($data['tickets'] as $t) {
                if ($t->status == 0) $countOpen++;
                if ($t->status == 1) $countProcess++;
                if ($t->status == 4) $countDone++;
                if (
                    $t->due_date
                    && date('Y-m-d') > date('Y-m-d', strtotime($t->due_date))
                    && !in_array($t->status, [4, 5])
                ) {
                    $countOverdue++;
                }
            }
        ?>
            <div class="accordion-item border mb-3 rounded shadow-sm">

                <!-- HEADER -->
                <h2 class="accordion-header" id="<?= $headingId ?>">
                    <button class="accordion-button rounded" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#<?= $collapseId ?>">
                        <div class="d-flex align-items-center gap-3 w-100 flex-wrap">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                style="width:38px;height:38px;flex-shrink:0;">
                                <?= strtoupper(substr($data['name'], 0, 1)) ?>
                            </div>
                            <span class="fw-semibold"><?= htmlspecialchars($data['name']) ?></span>
                            <div class="ms-auto d-flex gap-2 flex-wrap pe-2">
                                <span class="badge bg-primary"><?= $countOpen ?> Open</span>
                                <span class="badge bg-info"><?= $countProcess ?> Process</span>
                                <?php if ($countDone > 0) : ?>
                                    <span class="badge bg-success"><?= $countDone ?> Done</span>
                                <?php endif; ?>
                                <?php if ($countOverdue > 0) : ?>
                                    <span class="badge bg-danger">
                                        <i class="fa-solid fa-triangle-exclamation"></i> <?= $countOverdue ?> Overdue
                                    </span>
                                <?php endif; ?>
                                <span class="badge bg-dark"><?= $total ?> Total</span>
                            </div>
                        </div>
                    </button>
                </h2>

                <!-- BODY -->
                <div id="<?= $collapseId ?>" class="accordion-collapse collapse">
                    <div class="accordion-body p-0">

                        <?php if (empty($data['tickets'])) : ?>
                            <div class="text-center text-muted py-4">
                                <span>Tidak ada ticket aktif untuk <strong><?= htmlspecialchars($data['name']) ?></strong>.</span>
                            </div>

                        <?php else : ?>
                            <div class="table-responsive">

                                <!-- Toolbar sort per PIC -->
                                <div class="d-flex align-items-center px-3 py-2 border-bottom gap-2 bg-white">
                                    <small class="text-muted me-1">
                                        <i class="fa-solid fa-arrow-up-wide-short"></i> Sort:
                                    </small>
                                    <button class="btn btn-sm btn-outline-info sort-per-pic"
                                        data-sortable-id="<?= $sortableId ?>"
                                        data-pic-id="<?= $pic_id ?>"
                                        data-type="ba"
                                        data-sort="due_date">
                                        <i class="fa-solid fa-calendar-days"></i> Due Date
                                    </button>
                                </div>

                                <!-- List header -->
                                <div class="d-flex align-items-center px-3 py-2 bg-light border-bottom text-muted small fw-semibold"
                                    style="min-width:600px;">
                                    <div style="width:40px; flex-shrink:0;">No.</div>
                                    <div style="flex:2; min-width:120px; padding-right:8px;">No Ticket</div>
                                    <div style="flex:2; min-width:120px; padding-right:8px;">Client</div>
                                    <?php if ($user['is_programmer'] == 1 || $user['is_ba'] == 1) : ?>
                                        <div style="flex:3; min-width:120px; padding-right:8px;">Report</div>
                                    <?php endif; ?>
                                    <div style="flex:3; min-width:140px; padding-right:8px;">Category</div>
                                    <div style="flex:1.5; min-width:90px; padding-right:8px;">Due Date</div>
                                    <div style="flex:1.5; min-width:90px; padding-right:8px;">Man Hour (Est)</div>
                                    <div style="flex:1; min-width:80px; padding-right:8px;">Status</div>
                                    <?php if ($user['id_user'] == $t->pic_id || $user['is_ba'] == 1 || $user['id_user'] == 7) : ?>
                                        <div style="width:30px; flex-shrink:0;"></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Ticket rows -->
                                <ul class="list-unstyled mb-0 sortable-list"
                                    id="<?= $sortableId ?>"
                                    data-pic-id="<?= $pic_id ?>"
                                    data-type="ba"
                                    style="min-width:600px;">

                                    <?php
                                    $no = 1;
                                    foreach ($data['tickets'] as $idx => $t) :
                                        $status     = $statusConfig[$t->status] ?? ['label' => 'Unknown', 'class' => 'badge bg-secondary'];
                                        $due_date   = $t->due_date ? date('d M Y', strtotime($t->due_date)) : '-';
                                        $dueDateRaw = $t->due_date ? date('Y-m-d', strtotime($t->due_date)) : '';
                                        $isOverdue  = $t->due_date
                                            && date('Y-m-d') > date('Y-m-d', strtotime($t->due_date))
                                            && !in_array($t->status, [4, 5]);
                                    ?>
                                        <li class="d-flex align-items-center px-3 py-2 border-bottom ticket-row <?= $isOverdue ? 'bg-danger bg-opacity-10' : '' ?>"
                                            data-id="<?= $t->id ?>"
                                            data-status="<?= $t->status ?>"
                                            data-due-date="<?= $dueDateRaw ?>"
                                            data-original-order="<?= $idx ?>">

                                            <!-- No urut -->
                                            <div class="priority-number fw-bold text-muted small"
                                                style="width:40px; flex-shrink:0;">
                                                <?= $no++ ?>
                                            </div>

                                            <!-- No Ticket -->
                                            <div style="flex:2; min-width:120px; padding-right:8px; overflow:hidden;">
                                                <?php if ($user['is_programmer'] == 1 || $user['is_ba'] == 1) : ?>
                                                    <a href="<?= site_url('ticket/view_ticket/' . $t->id) ?>"
                                                        class="fw-semibold text-primary small d-block text-truncate text-decoration-none"
                                                        title="<?= htmlspecialchars($t->no_ticket) ?>">
                                                        <i class="fa-solid fa-ticket me-1"></i><?= htmlspecialchars($t->no_ticket) ?>
                                                    </a>
                                                <?php else : ?>
                                                    <span class="fw-semibold text-primary small d-block text-truncate"
                                                        title="<?= htmlspecialchars($t->no_ticket) ?>">
                                                        <i class="fa-solid fa-ticket me-1"></i><?= htmlspecialchars($t->no_ticket) ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($isOverdue) : ?>
                                                    <span class="badge bg-danger mt-1">
                                                        <i class="fa-solid fa-clock me-1"></i>Overdue
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Client -->
                                            <div style="flex:2; min-width:120px; padding-right:8px; overflow:hidden;"
                                                class="small text-truncate"
                                                title="<?= htmlspecialchars($t->client_name) ?>">
                                                <?= htmlspecialchars($t->client_name) ?>
                                            </div>

                                            <?php if ($user['is_programmer'] == 1 || $user['is_ba'] == 1) : ?>
                                                <div style="flex:3; min-width:140px; padding-right:8px; overflow:hidden;"
                                                    class="small text-truncate me-3"
                                                    title="<?= htmlspecialchars($t->report) ?>">
                                                    <?= htmlspecialchars($t->report) ?>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Category -->
                                            <div style="flex:3; min-width:140px; padding-right:8px; overflow:hidden;" class="small">
                                                <div class="text-truncate" title="<?= htmlspecialchars($t->category_name) ?>">
                                                    <?= htmlspecialchars($t->category_name) ?>
                                                </div>
                                                <div class="text-muted text-truncate" style="font-size:11px;"
                                                    title="<?= htmlspecialchars($t->sub_category_name) ?>">
                                                    <?= htmlspecialchars($t->sub_category_name) ?>
                                                </div>
                                            </div>

                                            <!-- Due Date -->
                                            <div style="flex:1.5; min-width:90px; padding-right:8px;"
                                                class="small <?= $isOverdue ? 'text-danger fw-bold' : '' ?>">
                                                <?= $due_date ?>
                                            </div>

                                            <!-- Man Hour Plan -->
                                            <div style="flex:1.5; min-width:90px; padding-right:8px;" class="small">
                                                <?= (!empty($t->man_hour_plan)) ? $t->man_hour_plan . ' Jam' : '-' ?>
                                            </div>

                                            <!-- Status -->
                                            <div style="flex:1; min-width:80px; padding-right:8px;">
                                                <span class="<?= $status['class'] ?>"><?= $status['label'] ?></span>
                                            </div>

                                            <!-- Drag handle -->
                                            <?php if ($user['id_user'] == $t->pic_id || $user['is_ba'] == 1 || $user['id_user'] == 7) : ?>
                                                <div class="drag-handle text-muted"
                                                    style="cursor:grab; width:30px; flex-shrink:0; text-align:center;">
                                                    <i class="fa-solid fa-grip-vertical"></i>
                                                </div>
                                            <?php endif; ?>

                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>