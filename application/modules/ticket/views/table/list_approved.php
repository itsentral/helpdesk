<?php
$ENABLE_VIEW = has_permission('Ticket.View');
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_approved" style="width:100%;">
        <thead class="table-light">
            <tr>
                <th style="min-width: 20px;">No</th>
                <th style="min-width: 80px;">No Tiket</th>
                <th style="min-width: 200px;">Report</th>
                <th style="min-width: 150px;">Create By</th>
                <!-- <th style="min-width: 120px;">PIC</th> -->
                <th style="min-width: 150px;">Approved By</th>
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
                            <?php
                            $statusClass = 'bg-secondary';
                            $statusIcon = 'fa-question';
                            $statusText = 'Unknown';

                            switch ((int) $row['status']) {
                                case 0: // Open
                                    $statusClass = 'bg-info';
                                    $statusIcon  = 'fa-circle-dot';
                                    $statusText  = 'Open';
                                    break;

                                case 1: // Process
                                    $statusClass = 'bg-warning';
                                    $statusIcon  = 'fa-spinner fa-spin';
                                    $statusText  = 'Process';
                                    break;

                                case 2: // Pending
                                    $statusClass = 'bg-secondary';
                                    $statusIcon  = 'fa-hourglass-half';
                                    $statusText  = 'Pending';
                                    break;

                                case 4: // Done
                                    $statusClass = 'bg-success';
                                    $statusIcon  = 'fa-circle-check';
                                    $statusText  = 'Done';
                                    break;

                                case 5: // Close
                                    $statusClass = 'bg-dark';
                                    $statusIcon  = 'fa-lock';
                                    $statusText  = 'Closed';
                                    break;
                            }
                            ?>
                            <span class="badge <?= $statusClass ?>">
                                <i class="fa-solid <?= $statusIcon ?>"></i>
                                <?= $statusText ?>
                            </span>

                            <div class="mt-1">
                                <span class="badge bg-success">
                                    <i class="fa-solid fa-check-double"></i> Approved
                                </span>
                            </div>

                            <?php if (!empty($row['sub_category_name'])): ?>
                                <div class="mt-1">
                                    <?php
                                    $subName = strtolower($row['sub_category_name']);
                                    $badgeClass = 'bg-secondary'; // default

                                    // Tentukan warna badge berdasarkan sub category
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
                        <td>
                            <div>
                                <?= trim((string)$row['approval_by']) === '' ? '-' : htmlspecialchars($row['approval_by']) ?>
                            </div>
                            <small class="text-muted">
                                <?php
                                $approvalDate = $row['approval_date'];
                                if (empty($approvalDate) || $approvalDate === '0000-00-00 00:00:00') {
                                    echo '-';
                                } else {
                                    echo '<i class="fa-solid fa-clock text-muted"></i> ' . date('d-m-Y H:i', strtotime($approvalDate));
                                }
                                ?>
                            </small>
                        </td>
                        <?php if ($ENABLE_VIEW): ?>
                            <td class="text-center">
                                <?php
                                $unread_count = isset($unread_counts[$row['id']]) ? $unread_counts[$row['id']] : 0;
                                ?>

                                <button type="button"
                                    class="btn btn-primary btn-sm px-2 py-1 open-chat position-relative"
                                    data-id="<?= $row['id'] ?>"
                                    data-ticket="<?= $row['no_ticket'] ?>"
                                    title="Chat Room">
                                    <i class="fa-solid fa-comments"></i>
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
                                    title="View History">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </button>
                                <button type="button"
                                    class="btn btn-info btn-sm px-2 py-1 view-ticket"
                                    data-id="<?= $row['id'] ?>"
                                    title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- <tr>
                    <td colspan="<?= $ENABLE_VIEW ? '13' : '12' ?>" class="text-center">
                        <div class="py-4">
                            <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No approved tickets found</p>
                        </div>
                    </td>
                </tr> -->
            <?php endif; ?>
        </tbody>
    </table>
</div>