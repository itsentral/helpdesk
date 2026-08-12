<?php
$ENABLE_VIEW = has_permission('Ticket.View');
?>

<style>
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
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
        transition: all 0.2s cubic-bezier(.4, 2, .6, 1);
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

    .action-popover.show .action-popover-item:nth-child(1) {
        transition-delay: 0.03s;
    }

    .action-popover.show .action-popover-item:nth-child(2) {
        transition-delay: 0.06s;
    }

    .action-popover.show .action-popover-item:nth-child(3) {
        transition-delay: 0.09s;
    }

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
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

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
    <table class="table table-bordered table-striped table-hover" id="table_cancel" style="width:100%;">
        <thead class="table-light">
            <tr>
                <th style="min-width: 20px;">No</th>
                <th style="min-width: 80px;">No Tiket</th>
                <th style="min-width: 200px;">Report</th>
                <th style="min-width: 200px;">Cancel Reason</th>
                <th style="min-width: 150px;">Cancelled By</th>
                <?php if ($ENABLE_VIEW): ?>
                    <th style="min-width: 60px;" class="text-center">Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($helpdesk)): ?>
                <?php $no = 1;
                foreach ($helpdesk as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['no_ticket']) ?></strong> <br>
                            <span class="badge bg-danger">
                                <i class="fa-solid fa-ban"></i> Cancel
                            </span>
                            <?php if (!empty($row['sub_category_name'])): ?>
                                <div class="mt-1">
                                    <?php
                                    $subName = strtolower($row['sub_category_name']);
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
                                        <?= htmlspecialchars($row['sub_category_name']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($row['report']) ?>
                        </td>
                        <td>
                            <?php $cancelReason = trim((string)$row['cancel_reason']); ?>
                            <div class="text-truncate" style="max-width: 200px;"
                                title="<?= $cancelReason === '' ? '-' : htmlspecialchars($cancelReason) ?>">
                                <?= $cancelReason === '' ? '-' : htmlspecialchars($cancelReason) ?>
                            </div>
                        </td>
                        <td class="text-nowrap">
                            <div>
                                <?= trim((string)$row['update_by']) === '' ? '-' : htmlspecialchars($row['update_by']) ?>
                            </div>
                            <small>
                                <?php
                                $updateDate = $row['update_date'];
                                if (empty($updateDate) || $updateDate === '0000-00-00 00:00:00') {
                                    echo '-';
                                } else {
                                    echo '<i class="fa-solid fa-clock text-muted"></i> ' . date('d-m-Y H:i', strtotime($updateDate));
                                }
                                ?>
                            </small>
                        </td>
                        <?php if ($ENABLE_VIEW): ?>
                            <td class="text-center align-middle">
                                <?php
                                $unread_count = isset($unread_counts[$row['id']]) ? $unread_counts[$row['id']] : 0;
                                ?>

                                <div class="action-btn-wrapper position-relative">
                                    <button class="btn btn-sm btn-action-toggle" type="button" title="Aksi">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <span class="badge bg-danger toggle-unread-badge toggle-unread-badge-<?= $row['id'] ?>"
                                        style="<?= $unread_count > 0 ? '' : 'display:none;' ?>">
                                        <?= $unread_count > 99 ? '99+' : $unread_count ?>
                                    </span>
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

                                        <!-- VIEW -->
                                        <button type="button"
                                            class="action-popover-item view-ticket"
                                            data-id="<?= $row['id'] ?>"
                                            title="View">
                                            <span class="action-icon bg-info text-white"><i class="fa-solid fa-eye"></i></span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>