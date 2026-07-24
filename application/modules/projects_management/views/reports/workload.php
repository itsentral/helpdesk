<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 font-weight-bold text-success"><i class="fa fa-users me-2"></i> Resource Workload Report</h5>
        <div>
            <a href="<?= site_url('projects_management/reports/export_excel?type=workload' . ($project_id ? '&project_id=' . $project_id : '')); ?>" class="btn btn-sm btn-success me-1"><i class="fa fa-file-excel-o me-1"></i> Export Excel</a>
            <a href="<?= site_url('projects_management/reports'); ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Bar -->
        <form method="GET" action="<?= site_url('projects_management/reports/workload'); ?>" class="row g-2 align-items-center mb-4">
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
                        <th>User Member</th>
                        <th>Username</th>
                        <th>Total Assigned Tasks</th>
                        <th>Completed Tasks (Done)</th>
                        <th width="180">Task Completion Rate</th>
                        <th>Total Estimated Hours</th>
                        <th class="pe-3">Total Actual Logged Hours</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($workload)): $no = 1;
                        foreach ($workload as $wl): ?>
                            <?php
                            $rate = ($wl['total_assigned_tasks'] > 0) ? round(($wl['completed_tasks'] / $wl['total_assigned_tasks']) * 100) : 0;
                            ?>
                            <tr>
                                <td class="ps-3"><?= $no++; ?></td>
                                <td><strong><?= html_escape($wl['nm_lengkap'] ? $wl['nm_lengkap'] : $wl['username']); ?></strong></td>
                                <td><?= html_escape($wl['username']); ?></td>
                                <td><span class="badge bg-primary"><?= $wl['total_assigned_tasks']; ?> Tasks</span></td>
                                <td><span class="badge bg-success"><?= $wl['completed_tasks']; ?> Tasks</span></td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $rate; ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?= $rate; ?>%</small>
                                </td>
                                <td><?= number_format($wl['total_estimated_hours'], 1); ?> Jam</td>
                                <td class="pe-3"><strong><?= number_format($wl['total_actual_hours'], 1); ?> Jam</strong></td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="8" class="text-center p-4 text-muted">Belum ada data workload team.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>