<div class="card border-0 shadow-sm mb-4">
    <!-- <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 fw-bold text-primary"><i class="fa fa-dashboard me-2"></i> Dashboard Project Management</h5>
        <div>
            <a href="<?= site_url('projects_management/master'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-list me-1"></i> Master Project</a>
            <a href="<?= site_url('projects_management/create'); ?>" class="btn btn-sm btn-success"><i class="fa fa-plus me-1"></i> New Project</a>
        </div>
    </div> -->
    <div class="card-body">
        <!-- KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold">Total Project</div>
                                <div class="h2 mb-0 fw-bold"><?= $kpi['total']; ?></div>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa fa-cubes"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-uppercase fw-bold">In Progress</div>
                                <div class="h2 mb-0 fw-bold"><?= $kpi['in_progress']; ?></div>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa fa-spinner"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold">Completed</div>
                                <div class="h2 mb-0 fw-bold"><?= $kpi['completed']; ?></div>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-50 small text-uppercase fw-bold">Overdue</div>
                                <div class="h2 mb-0 fw-bold"><?= $kpi['delay']; ?></div>
                            </div>
                            <div class="fs-1 opacity-50"><i class="fa fa-exclamation-triangle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project List -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light fw-bold"><i class="fa fa-list-alt me-1"></i> Daftar Project</div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="ps-3">No</th>
                            <th>Client</th>
                            <th>Project</th>
                            <th>PM</th>
                            <th>Target Selesai</th>
                            <th width="80">Modul</th>
                            <th width="80">Finish</th>
                            <th width="100">Status</th>
                            <th width="80" class="pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($projects)): $no = 1; foreach ($projects as $p): ?>
                            <tr>
                                <td class="ps-3"><?= $no++; ?></td>
                                <td><?= html_escape($p['client_name'] ? $p['client_name'] : '-'); ?></td>
                                <td><strong><?= html_escape($p['project_name']); ?></strong><br><small class="text-muted"><?= $p['project_code']; ?></small></td>
                                <td><?= html_escape($p['pm_name'] ? $p['pm_name'] : '-'); ?></td>
                                <td><small><?= date('d M Y', strtotime($p['end_date'])); ?></small></td>
                                <td class="text-center fw-bold"><?= $p['total_modules']; ?></td>
                                <td class="text-center fw-bold text-success"><?= $p['finished_modules']; ?></td>
                                <td>
                                    <?php
                                    $lbl = 'bg-secondary';
                                    if ($p['status'] == 'In Progress') $lbl = 'bg-warning text-dark';
                                    elseif ($p['status'] == 'Completed') $lbl = 'bg-success';
                                    ?>
                                    <span class="badge <?= $lbl; ?>"><?= html_escape($p['status']); ?></span>
                                </td>
                                <td class="pe-3">
                                    <a href="<?= site_url('projects_management/detail/' . $p['id']); ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="9" class="text-center p-4 text-muted">Belum ada project.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
