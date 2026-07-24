<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 font-weight-bold text-primary"><i class="fa fa-align-left me-2"></i> Simplified Gantt Chart Timeline</h5>
        <div>
            <a href="<?= site_url('projects_management/reports'); ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Bar -->
        <form method="GET" action="<?= site_url('projects_management/reports/gantt'); ?>" class="row g-2 align-items-center mb-4">
            <div class="col-auto">
                <label class="col-form-label fw-bold me-1">Filter Project: </label>
            </div>
            <div class="col-auto">
                <select name="project_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Project --</option>
                    <?php if (!empty($projects)): foreach ($projects as $p): ?>
                            <option value="<?= $p['id']; ?>" <?= ($project_id == $p['id']) ? 'selected' : ''; ?>><?= html_escape($p['project_code'] . ' - ' . $p['project_name']); ?></option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="ps-3">No</th>
                        <th>Project</th>
                        <th>Task Pekerjaan</th>
                        <th>Assignee</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th width="280" class="pe-3">Timeline Bar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tasks)): $no = 1;
                        foreach ($tasks as $t): ?>
                            <tr>
                                <td class="ps-3"><?= $no++; ?></td>
                                <td><span class="badge bg-primary"><?= html_escape($t['project_name']); ?></span></td>
                                <td><strong><?= html_escape($t['text']); ?></strong></td>
                                <td><?= html_escape($t['assignee'] ? $t['assignee'] : 'Unassigned'); ?></td>
                                <td><?= date('d/m/Y', strtotime($t['start_date'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($t['due_date'])); ?></td>
                                <td><span class="badge bg-secondary"><?= html_escape($t['status']); ?></span></td>
                                <td class="pe-3">
                                    <div class="progress" style="height: 14px; background-color:#e9ecef;">
                                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= max($t['progress'], 15); ?>%;">
                                            <?= $t['progress']; ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="8" class="text-center p-4 text-muted">Belum ada task dengan tanggal start & due date.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>