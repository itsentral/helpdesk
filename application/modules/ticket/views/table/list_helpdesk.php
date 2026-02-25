<?php
$ENABLE_MANAGE = has_permission('Ticket.Manage');
$ENABLE_VIEW = has_permission('Ticket.View');
$ENABLE_DELETE = has_permission('Ticket.Delete');
$loginUserId = $this->auth->user_id();
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_helpdesk" style="width:100%;">
        <thead class="table-light">
            <tr>
                <th style="min-width: 20px;">No</th>
                <th style="min-width: 80px;">No Tiket</th>
                <th style="min-width: 200px;">Report</th>
                <th style="min-width: 150px;">Create By</th>
                <th style="min-width: 110px;">Due Date</th>
                <th style="min-width: 100px; max-width: 260px;" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($helpdesk)): ?>
                <?php $no = 1;
                foreach ($helpdesk as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['no_ticket']) ?></strong><br>

                            <?php
                            $statusClass = 'bg-secondary';
                            $statusIcon  = 'fa-question';
                            $statusText  = 'Unknown';

                            switch ((int)$row['status']) {
                                case 0:
                                    $statusClass = 'bg-info';
                                    $statusIcon  = 'fa-circle-dot';
                                    $statusText  = 'Open';
                                    break;
                                case 1:
                                    $statusClass = 'bg-warning';
                                    $statusIcon  = 'fa-spinner fa-spin';
                                    $statusText  = 'Process';
                                    break;
                                case 2:
                                    $statusClass = 'bg-secondary';
                                    $statusIcon  = 'fa-hourglass-half';
                                    $statusText  = 'Pending';
                                    break;
                                case 3:
                                    $statusClass = 'bg-danger';
                                    $statusIcon  = 'fa-ban';
                                    $statusText  = 'Cancel';
                                    break;
                                case 4:
                                    $statusClass = 'bg-success';
                                    $statusIcon  = 'fa-circle-check';
                                    $statusText  = 'Done';
                                    break;
                                case 5:
                                    $statusClass = 'bg-dark';
                                    $statusIcon  = 'fa-lock';
                                    $statusText  = 'Closed';
                                    break;
                                case 6:
                                    $statusClass = 'bg-primary';
                                    $statusIcon  = 'fa-rotate-left';
                                    $statusText  = 'Revisi';
                                    break;
                            }
                            ?>

                            <span class="badge <?= $statusClass ?>">
                                <i class="fa-solid <?= $statusIcon ?>"></i>
                                <?= $statusText ?>
                            </span>

                            <?php
                            $approvalLevel  = (int)($row['approval_level'] ?? 0);
                            $currentLevel   = (int)($row['current_approval_level'] ?? 0);
                            $isApprove      = $row['is_approve'] ?? null;
                            ?>

                            <?php if ((int)$row['status'] === 4): ?>
                                <div class="mt-1">
                                    <?php if ($isApprove == 1): ?>
                                        <span class="badge bg-success">
                                            <i class="fa-solid fa-check-double"></i> Approved
                                        </span>

                                    <?php elseif ($isApprove == 2): ?>
                                        <span class="badge bg-danger">
                                            <i class="fa-solid fa-xmark"></i> Rejected
                                        </span>

                                    <?php elseif ($isApprove == 0): ?>
                                        <?php if ($currentLevel < $approvalLevel): ?>
                                            <?php if ($approvalLevel > 1 && $currentLevel == ($approvalLevel - 1)): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fa-solid fa-user-check"></i>
                                                    Menunggu Konfirmasi Pembuat
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="fa-solid fa-clock"></i>
                                                    Menunggu Approval
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>


                            <?php
                            $picId = trim((string)($row['pic_id'] ?? ''));
                            if ($picId === '' && (int)$row['status'] !== 3):
                            ?>
                                <div class="mt-1">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fa-solid fa-user-slash"></i> PIC belum ditunjuk
                                    </span>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="text-truncate" style="max-width: 200px;"
                                title="<?= htmlspecialchars($row['report']) ?>">
                                <?= htmlspecialchars($row['report']) ?>
                            </div>
                        </td>
                        <td>
                            <div>
                                <i class="fa-solid fa-user text-muted"></i>
                                <?= htmlspecialchars($row['create_by']) ?>
                            </div>
                            <small class="text-muted">
                                <i class="fa-solid fa-clock"></i>
                                <?= date('d-m-Y H:i', strtotime($row['create_date'])) ?>
                            </small>
                        </td>
                        <td class="text-nowrap">
                            <!-- Due Date -->
                            <div>
                                <i class="fa-solid fa-calendar-days text-muted"></i>
                                <?php
                                $dueDate = $row['due_date'];

                                if (
                                    empty($dueDate) ||
                                    $dueDate === '0000-00-00'
                                ) {
                                    echo '-';
                                } else {
                                    echo date('d-m-Y', strtotime($dueDate));
                                }
                                ?>
                            </div>

                            <!-- Sub Category Badge -->
                            <?php if (!empty($row['sub_name'])): ?>
                                <div class="mt-1">
                                    <?php
                                    $subName = strtolower($row['sub_name']);
                                    $badgeClass = 'bg-secondary';

                                    if (strpos($subName, 'bugs konsep') !== false) {
                                        $badgeClass = 'bg-danger';
                                    } elseif (strpos($subName, 'bugs program') !== false) {
                                        $badgeClass = 'bg-warning text-dark';
                                    } elseif (strpos($subName, 'development') !== false) {
                                        $badgeClass = 'bg-primary';
                                    } elseif (strpos($subName, 'maintenance') !== false) {
                                        $badgeClass = 'bg-info';
                                    }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>">
                                        <i class="fa-solid fa-tag"></i>
                                        <?= htmlspecialchars($row['sub_name']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </td>

                        <?php
                        $status                = (int) $row['status'];
                        $approvalLevel          = (int) $row['approval_level'];
                        $currentApprovalLevel   = (int) $row['current_approval_level'];
                        $approvalById           = $row['approval_by_id'];
                        $createById             = $row['create_by_id'];
                        $unread_count           = isset($unread_counts[$row['id']]) ? $unread_counts[$row['id']] : 0;
                        ?>

                        <?php
                        $picById     = isset($row['pic_id']) ? trim((string)$row['pic_id']) : '';
                        $createById = isset($row['create_by_id']) ? trim((string)$row['create_by_id']) : '';
                        $approvalById = isset($row['approval_by_id']) ? trim((string)$row['approval_by_id']) : '';
                        $status      = isset($row['status']) ? (int)$row['status'] : 0;
                        ?>

                        <td class="text-center" style="max-width: 260px;">
                            <div class="d-inline-flex gap-1 flex-wrap justify-content-center align-items-center" style="max-width: 260px;">
                                <!-- CHAT -->
                                <button type="button"
                                    class="btn btn-primary btn-sm px-2 py-1 open-chat position-relative"
                                    style="width: 120px"
                                    data-id="<?= $row['id'] ?>"
                                    data-ticket="<?= $row['no_ticket'] ?>"
                                    title="Chat Room">
                                    <i class="fa-solid fa-comments"></i> Chat Room
                                    <span class="chat-unread-badge-<?= $row['id'] ?> position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="<?= $unread_count > 0 ? '' : 'display:none;' ?> font-size:9px;padding:2px 5px;">
                                        <?= $unread_count > 99 ? '99+' : $unread_count ?>
                                    </span>
                                </button>

                                <!-- HISTORY -->
                                <button type="button"
                                    class="btn btn-secondary btn-sm px-2 py-1 view-history"
                                    style="width: 120px"
                                    data-id="<?= $row['id'] ?>"
                                    data-ticket="<?= $row['no_ticket'] ?>"
                                    title="View History">
                                    <i class="fa-solid fa-clock-rotate-left"></i> History
                                </button>

                                <?php if ($ENABLE_MANAGE): ?>
                                    <!-- PROCESS -->
                                    <?php if ($picById == $loginUserId && in_array($status, [0, 2, 6])): ?>
                                        <button type="button"
                                            class="btn btn-primary btn-sm px-2 py-1 process-status"
                                            style="width: 120px"
                                            data-id="<?= $row['id'] ?>"
                                            data-current-status="<?= $status ?>"
                                            data-man-hour-plan="<?= $row['man_hour_plan'] ?? 0 ?>"
                                            data-causes="<?= htmlspecialchars($row['causes'] ?? '') ?>"
                                            data-action-plan="<?= htmlspecialchars($row['action_plan'] ?? '') ?>"
                                            title="Process Ticket">
                                            <i class="fa-solid fa-angles-right"></i> Process
                                        </button>
                                    <?php endif; ?>

                                    <!-- PENDING -->
                                    <?php if ($status === 1 && $picById == $loginUserId): ?>
                                        <button type="button"
                                            class="btn btn-secondary btn-sm px-2 py-1 pending-status"
                                            style="width: 120px"
                                            data-id="<?= $row['id'] ?>"
                                            title="Pending Ticket">
                                            <i class="fa-solid fa-hourglass-half"></i> Pending
                                        </button>
                                    <?php endif; ?>

                                    <!-- CANCEL -->
                                    <?php if ($status === 0 && $createById == $loginUserId): ?>
                                        <button type="button"
                                            class="btn btn-danger btn-sm px-2 py-1 cancel-status"
                                            style="width: 120px"
                                            data-id="<?= $row['id'] ?>"
                                            title="Cancel Ticket">
                                            <i class="fa-solid fa-ban"></i> Cancel
                                        </button>
                                    <?php endif; ?>

                                    <!-- DONE -->
                                    <?php if ($status === 1 && $picById == $loginUserId): ?>
                                        <button type="button"
                                            class="btn btn-success btn-sm px-2 py-1 done-status"
                                            style="width: 120px"
                                            data-id="<?= $row['id'] ?>"
                                            data-current-status="<?= $status ?>"
                                            title="Done Ticket">
                                            <i class="fa-solid fa-clipboard-check"></i> Done
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- VIEW -->
                                <?php if ($ENABLE_VIEW): ?>
                                    <button type="button"
                                        class="btn btn-info btn-sm px-2 py-1 view-ticket"
                                        style="width: 120px"
                                        data-id="<?= $row['id'] ?>"
                                        title="View Details">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
                                <?php endif; ?>

                                <!-- EDIT -->
                                <?php if ($ENABLE_MANAGE && $status === 0): ?>
                                    <button type="button"
                                        class="btn btn-warning btn-sm px-2 py-1 edit-ticket"
                                        style="width: 120px"
                                        data-id="<?= $row['id'] ?>"
                                        title="Edit Ticket">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                <?php endif; ?>

                                <!-- REJECT (SEMUA LEVEL) -->
                                <?php if (
                                    $ENABLE_MANAGE &&
                                    $status === 4 &&
                                    $currentApprovalLevel < $approvalLevel &&
                                    (
                                        ($currentApprovalLevel === 0 && $approvalById == $loginUserId) ||
                                        ($currentApprovalLevel === 1 && $createById == $loginUserId)
                                    )
                                ): ?>
                                    <button type="button"
                                        class="btn btn-danger btn-sm px-2 py-1 reject-status"
                                        style="width: 120px"
                                        data-id="<?= $row['id'] ?>"
                                        title="Reject Ticket">
                                        <i class="fa-solid fa-xmark"></i> Reject
                                    </button>
                                <?php endif; ?>

                                <!-- APPROVE LEVEL 1 -->
                                <?php if (
                                    $ENABLE_MANAGE &&
                                    $status === 4 &&
                                    $approvalLevel >= 1 &&
                                    $currentApprovalLevel === 0 &&
                                    $approvalById == $loginUserId
                                ): ?>
                                    <button type="button"
                                        class="btn btn-success btn-sm px-2 py-1 approve-status"
                                        style="width: 120px"
                                        data-id="<?= $row['id'] ?>"
                                        data-level="1"
                                        title="Approve Level 1">
                                        <i class="fa-solid fa-check"></i> Approve
                                    </button>
                                <?php endif; ?>

                                <!-- APPROVE LEVEL 2 -->
                                <?php if (
                                    $ENABLE_MANAGE &&
                                    $status === 4 &&
                                    $approvalLevel >= 2 &&
                                    $currentApprovalLevel === 1 &&
                                    $createById == $loginUserId
                                ): ?>
                                    <button type="button"
                                        class="btn btn-success btn-sm px-2 py-1 approve-status"
                                        style="width: 120px"
                                        data-id="<?= $row['id'] ?>"
                                        data-level="2"
                                        title="Approve Level 2">
                                        <i class="fa-solid fa-check-double"></i> Approve
                                    </button>
                                <?php endif; ?>

                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- <tr>
                    <td colspan="12" class="text-center">
                        <div class="py-4">
                            <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No helpdesk tickets found</p>
                        </div>
                    </td>
                </tr> -->
            <?php endif; ?>
        </tbody>
    </table>
</div>