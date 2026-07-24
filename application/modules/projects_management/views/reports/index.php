<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title m-0 font-weight-bold text-primary"><i class="fa fa-bar-chart me-2"></i> Reports & Analytics Dashboard</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <!-- Gantt Chart Card -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm bg-info text-white h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="fs-1"><i class="fa fa-align-left"></i></div>
                            <h3 class="fw-bold mt-2 mb-1">Gantt Chart</h3>
                            <p class="text-white-50 small">Timeline & Schedule Tasks Project</p>
                        </div>
                        <a href="<?= site_url('projects_management/reports/gantt'); ?>" class="btn btn-light btn-sm fw-bold text-info mt-3">Lihat Gantt Chart <i class="fa fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>

            <!-- Resource Workload Card -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="fs-1"><i class="fa fa-users"></i></div>
                            <h3 class="fw-bold mt-2 mb-1">Workload</h3>
                            <p class="text-white-50 small">Beban Kerja & Performa Team Member</p>
                        </div>
                        <a href="<?= site_url('projects_management/reports/workload'); ?>" class="btn btn-light btn-sm fw-bold text-success mt-3">Lihat Workload Report <i class="fa fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>

            <!-- Budget vs Costing Card -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="fs-1"><i class="fa fa-money"></i></div>
                            <h3 class="fw-bold mt-2 mb-1">Costing</h3>
                            <p class="text-dark-50 small">Project Budget vs Actual Hours Logged</p>
                        </div>
                        <a href="<?= site_url('projects_management/reports/budget'); ?>" class="btn btn-dark btn-sm fw-bold mt-3">Lihat Costing & Budget <i class="fa fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light fw-bold">
                <i class="fa fa-table me-1"></i> Summary Workload Team Quick Overview
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="ps-3">No</th>
                            <th>Nama Member</th>
                            <th>Total Assigned Tasks</th>
                            <th>Completed Tasks</th>
                            <th>Total Estimated Hours</th>
                            <th class="pe-3">Total Actual Logged Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($workload)): $no = 1;
                            foreach ($workload as $wl): ?>
                                <tr>
                                    <td class="ps-3"><?= $no++; ?></td>
                                    <td><strong><?= html_escape($wl['nm_lengkap'] ? $wl['nm_lengkap'] : $wl['username']); ?></strong></td>
                                    <td><span class="badge bg-primary"><?= $wl['total_assigned_tasks']; ?> tasks</span></td>
                                    <td><span class="badge bg-success"><?= $wl['completed_tasks']; ?> done</span></td>
                                    <td><?= number_format($wl['total_estimated_hours'], 1); ?> Jam</td>
                                    <td class="pe-3"><strong><?= number_format($wl['total_actual_hours'], 1); ?> Jam</strong></td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="6" class="text-center p-4 text-muted">Belum ada data workload.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>