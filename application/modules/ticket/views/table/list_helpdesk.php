<?php
$ENABLE_MANAGE = has_permission('Ticket.Manage');
$ENABLE_VIEW = has_permission('Ticket.View');
$ENABLE_DELETE = has_permission('Ticket.Delete');
$loginUserId = $this->auth->user_id();
?>

<style>
    @keyframes elegantPulse {
        0% {
            background-color: inherit;
            box-shadow: none;
        }

        50% {
            background-color: rgba(255, 193, 7, 0.15);
            box-shadow: inset 0 0 0 2px rgba(255, 193, 7, 0.5);
        }

        100% {
            background-color: inherit;
            box-shadow: none;
        }
    }

    .needs-approval {
        animation: elegantPulse 2s ease-in-out infinite;
        border-left: 4px solid #ffc107 !important;
    }

    /* Action Popover Styles */
    .action-btn-wrapper {
        display: inline-block;
    }
    .btn-action-toggle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
    }
    .btn-action-toggle:hover,
    .btn-action-toggle.active {
        background: #e3e8f0;
        transform: rotate(90deg);
    }

    .action-popover {
        position: absolute;
        top: 50%;
        right: 100%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        background: #fff;
        border-radius: 30px;
        border: 1px solid #e0e0e0;
        white-space: nowrap;
        z-index: 2000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
        margin-right: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .action-popover.show {
        opacity: 1;
        pointer-events: auto;
    }

    .action-popover-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        text-decoration: none !important;
        transition: all 0.2s cubic-bezier(.4,2,.6,1);
        opacity: 0;
        transform: scale(0.3);
        position: relative;
        cursor: pointer;
        border: none;
        padding: 0;
    }
    .action-popover.show .action-popover-item {
        opacity: 1;
        transform: scale(1);
    }
    .action-popover.show .action-popover-item:nth-child(1) { transition-delay: 0.03s; }
    .action-popover.show .action-popover-item:nth-child(2) { transition-delay: 0.06s; }
    .action-popover.show .action-popover-item:nth-child(3) { transition-delay: 0.09s; }
    .action-popover.show .action-popover-item:nth-child(4) { transition-delay: 0.12s; }
    .action-popover.show .action-popover-item:nth-child(5) { transition-delay: 0.15s; }
    .action-popover.show .action-popover-item:nth-child(6) { transition-delay: 0.18s; }
    .action-popover.show .action-popover-item:nth-child(7) { transition-delay: 0.21s; }
    .action-popover.show .action-popover-item:nth-child(8) { transition-delay: 0.24s; }
    .action-popover.show .action-popover-item:nth-child(9) { transition-delay: 0.27s; }

    .action-popover-item:hover {
        transform: scale(1.25) !important;
        z-index: 2;
    }
    .action-popover-item .action-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    /* Tooltip on hover */
    .action-popover-item::after {
        content: attr(title);
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.15s;
    }
    .action-popover-item:hover::after {
        opacity: 1;
    }

    /* Chat badge in popover */
    .action-popover-item .popover-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        font-size: 9px;
        padding: 1px 4px;
        border-radius: 10px;
        z-index: 3;
    }
</style>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_helpdesk" style="width:100%;">
        <thead class="table-light">
            <tr>
                <th style="min-width: 20px;">No</th>
                <th style="min-width: 80px;">No Tiket</th>
                <th style="min-width: 200px;">Report</th>
                <th style="min-width: 150px;">Create By</th>
                <th style="min-width: 110px;">Due Date</th>
                <th style="min-width: 60px;" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($helpdesk)): ?>
                <?php $no = 1;
                foreach ($helpdesk as $row): ?>

                    <?php
                    $picById            = isset($row['pic_id']) ? trim((string)$row['pic_id']) : '';
                    $createById         = isset($row['create_by_id']) ? trim((string)$row['create_by_id']) : '';
                    $approvalById       = isset($row['approval_by_id']) ? trim((string)$row['approval_by_id']) : '';
                    $status             = isset($row['status']) ? (int)$row['status'] : 0;
                    $approvalLevel      = (int)($row['approval_level'] ?? 0);
                    $currentApprovalLevel = (int)($row['current_approval_level'] ?? 0);
                    $isApprove          = $row['is_approve'] ?? null;
                    $unread_count       = isset($unread_counts[$row['id']]) ? $unread_counts[$row['id']] : 0;

                    $needsApproval = (
                        $ENABLE_MANAGE &&
                        $status === 4 &&
                        (
                            ($approvalLevel >= 1 && $currentApprovalLevel === 0 && $approvalById == $loginUserId) ||
                            ($approvalLevel >= 2 && $currentApprovalLevel === 1 && $createById == $loginUserId)
                        )
                    );
                    ?>

                    <tr class="<?= $needsApproval ? 'needs-approval' : '' ?>">
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['no_ticket']) ?></strong><br>

                            <?php
                            $statusClass = 'bg-secondary';
                            $statusIcon  = 'fa-question';
                            $statusText  = 'Unknown';

                            switch ($status) {
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

                            <?php if ($status === 4): ?>
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
                                        <?php if ($currentApprovalLevel < $approvalLevel): ?>
                                            <?php if ($approvalLevel > 1 && $currentApprovalLevel == ($approvalLevel - 1)): ?>
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

                                <?php if ($needsApproval): ?>
                                    <div class="mt-1">
                                        <span class="badge bg-warning text-dark" style="font-size: 11px;">
                                            <i class="fa-solid fa-bell fa-shake"></i> Menunggu Persetujuan Anda
                                        </span>
                                    </div>
                                <?php endif; ?>

                            <?php endif; ?>

                            <?php
                            $picId = trim((string)($row['pic_id'] ?? ''));
                            if ($picId === '' && $status !== 3):
                            ?>
                                <div class="mt-1">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fa-solid fa-user-slash"></i> PIC belum ditunjuk
                                    </span>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['report']) ?>
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
                                if (empty($dueDate) || $dueDate === '0000-00-00') {
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

                        <td class="text-center align-middle">
                            <div class="action-btn-wrapper position-relative">
                                <button class="btn btn-sm btn-action-toggle" type="button" title="Aksi">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div class="action-popover">
                                    <!-- CHAT -->
                                    <button type="button"
                                        class="action-popover-item open-chat position-relative"
                                        data-id="<?= $row['id'] ?>"
                                        data-ticket="<?= $row['no_ticket'] ?>"
                                        title="Chat Room">
                                        <span class="action-icon bg-primary text-white"><i class="fa-solid fa-comments"></i></span>
                                        <?php if ($unread_count > 0): ?>
                                            <span class="popover-badge badge bg-danger chat-unread-badge-<?= $row['id'] ?>">
                                                <?= $unread_count > 99 ? '99+' : $unread_count ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="popover-badge badge bg-danger chat-unread-badge-<?= $row['id'] ?>" style="display:none;">0</span>
                                        <?php endif; ?>
                                    </button>

                                    <!-- HISTORY -->
                                    <button type="button"
                                        class="action-popover-item view-history"
                                        data-id="<?= $row['id'] ?>"
                                        data-ticket="<?= $row['no_ticket'] ?>"
                                        title="History">
                                        <span class="action-icon bg-secondary text-white"><i class="fa-solid fa-clock-rotate-left"></i></span>
                                    </button>

                                    <?php if ($ENABLE_MANAGE): ?>
                                        <!-- PROCESS -->
                                        <?php if ($picById == $loginUserId && in_array($status, [0, 2, 6])): ?>
                                            <button type="button"
                                                class="action-popover-item process-status"
                                                data-id="<?= $row['id'] ?>"
                                                data-current-status="<?= $status ?>"
                                                data-man-hour-plan="<?= $row['man_hour_plan'] ?? 0 ?>"
                                                data-causes="<?= htmlspecialchars($row['causes'] ?? '') ?>"
                                                data-action-plan="<?= htmlspecialchars($row['action_plan'] ?? '') ?>"
                                                title="Process">
                                                <span class="action-icon bg-primary text-white"><i class="fa-solid fa-angles-right"></i></span>
                                            </button>
                                        <?php endif; ?>

                                        <!-- PENDING -->
                                        <?php if ($status === 1 && $picById == $loginUserId): ?>
                                            <button type="button"
                                                class="action-popover-item pending-status"
                                                data-id="<?= $row['id'] ?>"
                                                title="Pending">
                                                <span class="action-icon bg-secondary text-white"><i class="fa-solid fa-hourglass-half"></i></span>
                                            </button>
                                        <?php endif; ?>

                                        <!-- CANCEL -->
                                        <?php if ($status === 0 && $createById == $loginUserId): ?>
                                            <button type="button"
                                                class="action-popover-item cancel-status"
                                                data-id="<?= $row['id'] ?>"
                                                title="Cancel">
                                                <span class="action-icon bg-danger text-white"><i class="fa-solid fa-ban"></i></span>
                                            </button>
                                        <?php endif; ?>

                                        <!-- DONE -->
                                        <?php if ($status === 1 && $picById == $loginUserId): ?>
                                            <button type="button"
                                                class="action-popover-item done-status"
                                                data-id="<?= $row['id'] ?>"
                                                data-current-status="<?= $status ?>"
                                                title="Done">
                                                <span class="action-icon bg-success text-white"><i class="fa-solid fa-clipboard-check"></i></span>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <!-- VIEW -->
                                    <?php if ($ENABLE_VIEW): ?>
                                        <button type="button"
                                            class="action-popover-item view-ticket"
                                            data-id="<?= $row['id'] ?>"
                                            title="View">
                                            <span class="action-icon bg-info text-white"><i class="fa-solid fa-eye"></i></span>
                                        </button>
                                    <?php endif; ?>

                                    <!-- EDIT -->
                                    <?php if ($ENABLE_MANAGE && in_array($status, [0, 2, 6], true)): ?>
                                        <button type="button"
                                            class="action-popover-item edit-ticket"
                                            data-id="<?= $row['id'] ?>"
                                            title="Edit">
                                            <span class="action-icon bg-warning text-dark"><i class="fa-solid fa-pen-to-square"></i></span>
                                        </button>
                                    <?php endif; ?>

                                    <!-- REJECT -->
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
                                            class="action-popover-item reject-status"
                                            data-id="<?= $row['id'] ?>"
                                            title="Reject">
                                            <span class="action-icon bg-danger text-white"><i class="fa-solid fa-xmark"></i></span>
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
                                            class="action-popover-item approve-status"
                                            data-id="<?= $row['id'] ?>"
                                            data-level="1"
                                            title="Approve">
                                            <span class="action-icon bg-success text-white"><i class="fa-solid fa-check"></i></span>
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
                                            class="action-popover-item approve-status"
                                            data-id="<?= $row['id'] ?>"
                                            data-level="2"
                                            title="Approve Lv2">
                                            <span class="action-icon bg-success text-white"><i class="fa-solid fa-check-double"></i></span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- kosong -->
            <?php endif; ?>
        </tbody>
    </table>
</div>