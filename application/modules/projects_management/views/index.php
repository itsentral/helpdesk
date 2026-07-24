<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 font-weight-bold text-primary"><i class="fa fa-cubes me-2"></i> Manajemen Master Project</h5>
        <div>
            <a href="<?= site_url('projects_management/create'); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus me-1"></i> Tambah Project Baru</a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Bar -->
        <form method="GET" action="<?= site_url('projects_management/master'); ?>" class="row g-2 align-items-center mb-4">
            <div class="col-auto">
                <label class="col-form-label fw-bold me-1">Filter Status:</label>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="Planning" <?= ($this->input->get('status') == 'Planning') ? 'selected' : ''; ?>>Planning</option>
                    <option value="In Progress" <?= ($this->input->get('status') == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="On Hold" <?= ($this->input->get('status') == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                    <option value="Completed" <?= ($this->input->get('status') == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <div class="col-auto ms-2">
                <label class="col-form-label fw-bold me-1">Client:</label>
            </div>
            <div class="col-auto">
                <select name="client_id" class="form-select form-select-sm">
                    <option value="">-- Semua Client --</option>
                    <?php if (!empty($clients)): foreach ($clients as $c): ?>
                        <option value="<?= $c['id']; ?>" <?= ($this->input->get('client_id') == $c['id']) ? 'selected' : ''; ?>><?= html_escape($c['name_app']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="col-auto ms-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter me-1"></i> Filter</button>
                <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-secondary btn-sm ms-1"><i class="fa fa-refresh me-1"></i> Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="ps-3">No</th>
                        <th>Kode Project</th>
                        <th>Nama Project</th>
                        <th>Client</th>
                        <th>Project Manager</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th width="140">Progress Task</th>
                        <th>Status</th>
                        <th width="120" class="pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($projects)): ?>
                        <?php $no = 1; foreach ($projects as $p): ?>
                            <tr>
                                <td class="ps-3"><?= $no++; ?></td>
                                <td><strong><a href="<?= site_url('projects_management/detail/' . $p['id']); ?>" class="text-decoration-none"><?= html_escape($p['project_code']); ?></a></strong></td>
                                <td><?= html_escape($p['project_name']); ?></td>
                                <td><?= html_escape($p['client_name'] ? $p['client_name'] : '-'); ?></td>
                                <td><?= html_escape($p['pm_name'] ? $p['pm_name'] : '-'); ?></td>
                                <td><?= date('d/m/Y', strtotime($p['start_date'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($p['end_date'])); ?></td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $p['progress']; ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?= $p['progress']; ?>% (<?= $p['completed_tasks']; ?>/<?= $p['total_tasks']; ?> tasks)</small>
                                </td>
                                <td>
                                    <?php
                                    $lbl = 'bg-secondary';
                                    if ($p['status'] == 'In Progress') $lbl = 'bg-warning text-dark';
                                    else if ($p['status'] == 'Completed') $lbl = 'bg-success';
                                    else if ($p['status'] == 'On Hold') $lbl = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $lbl; ?>"><?= html_escape($p['status']); ?></span>
                                </td>
                                <td class="pe-3">
                                    <a href="<?= site_url('projects_management/detail/' . $p['id']); ?>" class="btn btn-sm btn-outline-info me-1"><i class="fa fa-eye"></i> Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center p-4 text-muted">Belum ada project terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form Project (Bootstrap 5) -->
<div class="modal fade" id="modal-project" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#btn-add-project').click(function() {
        $.get('<?= site_url("projects_management/create"); ?>', function(html) {
            $('#modal-project .modal-content').html(html);
            var myModal = new bootstrap.Modal(document.getElementById('modal-project'));
            myModal.show();
        });
    });
});
</script>