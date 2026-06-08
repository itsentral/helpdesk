<?php
$ENABLE_VIEW = has_permission('Ticket.View');
?>

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
                    <th style="min-width: 120px;" class="text-center">Action</th>
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
                            <td class="text-center" style="max-width: 260px">
                                <?php
                                $unread_count = isset($unread_counts[$row['id']]) ? $unread_counts[$row['id']] : 0;
                                ?>

                                <div class="d-inline-flex gap-1 flex-wrap justify-content-center align-items-center" style="max-width: 260px;">
                                    <button type="button"
                                        class="btn btn-primary btn-sm px-2 py-1 open-chat position-relative"
                                        data-id="<?= $row['id'] ?>"
                                        data-ticket="<?= $row['no_ticket'] ?>"
                                        title="Chat Room"
                                        style="width: 120px">
                                        <i class="fa-solid fa-comments"></i> Chat Room
                                        <?php if ($unread_count > 0): ?>
                                            <span class="chat-unread-badge-<?= $row['id'] ?> position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                                style="font-size: 9px; padding: 2px 5px;">
                                                <?= $unread_count > 99 ? '99+' : $unread_count ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="chat-unread-badge-<?= $row['id'] ?> position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                                style="display: none; font-size: 9px; padding: 2px 5px;">
                                                0
                                            </span>
                                        <?php endif; ?>
                                    </button>
                                    <button type="button"
                                        class="btn btn-secondary btn-sm px-2 py-1 view-history"
                                        data-id="<?= $row['id'] ?>"
                                        data-ticket="<?= $row['no_ticket'] ?>"
                                        title="View History"
                                        style="width: 120px">
                                        <i class="fa-solid fa-clock-rotate-left"></i> History
                                    </button>
                                    <button type="button"
                                        class="btn btn-info btn-sm px-2 py-1 view-ticket"
                                        data-id="<?= $row['id'] ?>"
                                        title="View Details"
                                        style="width: 120px">
                                        <i class="fa-solid fa-eye"></i> View
                                    </button>
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