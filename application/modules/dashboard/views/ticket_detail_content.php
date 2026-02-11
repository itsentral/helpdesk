<div class="table-responsive">
    <table class="table table-bordered table-striped" id="ticket_table">
        <thead>
            <tr>
                <th>No Ticket</th>
                <th>Report By</th>
                <th>Report</th>
                <th>Category & Sub</th>
                <th>Causes</th>
                <th>Action Plan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tickets)): ?>
                <?php foreach ($tickets as $ticket): ?>
                    <?php
                    $status_label = '';
                    $status_class = '';
                    switch ($ticket->status) {
                        case 0:
                            $status_label = 'Open';
                            $status_class = 'badge bg-info';
                            break;
                        case 1:
                            $status_label = 'Process';
                            $status_class = 'badge bg-primary';
                            break;
                        case 2:
                            $status_label = 'Pending';
                            $status_class = 'badge bg-warning';
                            break;
                        case 3:
                            $status_label = 'Cancel';
                            $status_class = 'badge bg-danger';
                            break;
                        case 4:
                            $status_label = 'Done';
                            $status_class = 'badge bg-success';
                            break;
                        case 5:
                            $status_label = 'Close';
                            $status_class = 'badge bg-secondary';
                            break;
                        case 6:
                            $status_label = 'Revisi';
                            $status_class = 'badge bg-dark';
                            break;
                    }
                    ?>
                    <tr>
                        <td>
                            <?= $ticket->no_ticket ?: '-' ?><br>
                            <span class="<?= $status_class ?>"><?= $status_label ?: '-' ?></span>
                        </td>

                        <td>
                            <div><?= $ticket->create_by ?: '-' ?></div>
                            <div>
                                <small>
                                    <?= !empty($ticket->create_date) ? date('d M Y H:i', strtotime($ticket->create_date)) : '-' ?>
                                </small>
                            </div>
                        </td>

                        <td><?= $ticket->report ?: '-' ?></td>

                        <td>
                            <?= $ticket->category_name ?: '-' ?> - <?= $ticket->sub_category_name ?: '-' ?>
                        </td>

                        <td><?= $ticket->causes ?: '-' ?></td>

                        <td><?= $ticket->action_plan ?: '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data ticket</td>
                </tr>
            <?php endif; ?>
        </tbody>

    </table>
</div>