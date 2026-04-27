<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="card">
    <div class="card-header">
        <h5>Detail Tickets - <?= $date ?></h5>
        <p class="text-muted mb-0">
            Category: <strong><?= ucfirst($category) ?></strong>
        </p>
    </div>
    <div class="card-body">
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
                                <td><?= $ticket->no_ticket ?> <br>
                                    <span class="<?= $status_class ?>"><?= $status_label ?></span>
                                </td>
                                <td>
                                    <div><?= $ticket->create_by ?></div>
                                    <div>
                                        <small><?= date('d M Y H:i', strtotime($ticket->create_date)) ?></small>
                                    </div>
                                </td>
                                <td><?= $ticket->report ?></td>
                                <td><?= $ticket->category_name ?> - <?= $ticket->sub_category_name ?></td>
                                <td><?= $ticket->causes ?></td>
                                <td><?= $ticket->action_plan ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data ticket</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#ticket_table').DataTable({
            "pageLength": 25,
            "order": [
                [7, "desc"]
            ], // Sort by created date
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>