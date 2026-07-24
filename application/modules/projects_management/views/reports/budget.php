<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 font-weight-bold text-warning"><i class="fa fa-money me-2"></i> Project Costing vs Budget Report</h5>
        <div>
            <a href="<?= site_url('projects_management/reports/export_excel?type=budget'); ?>" class="btn btn-sm btn-success me-1"><i class="fa fa-file-excel-o me-1"></i> Export Excel</a>
            <a href="<?= site_url('projects_management/reports'); ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-left me-1"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="ps-3">No</th>
                        <th>Kode Project</th>
                        <th>Nama Project</th>
                        <th>Client / App</th>
                        <th>Project Manager</th>
                        <th>Status</th>
                        <th>Budget (Rp)</th>
                        <th>Est. Man-Hours</th>
                        <th class="pe-3">Act. Man-Hours Logged</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($projects)): $no=1; foreach ($projects as $p): ?>
                        <tr>
                            <td class="ps-3"><?= $no++; ?></td>
                            <td><strong><?= html_escape($p['project_code']); ?></strong></td>
                            <td><?= html_escape($p['project_name']); ?></td>
                            <td><?= html_escape($p['client_name'] ? $p['client_name'] : '-'); ?></td>
                            <td><?= html_escape($p['pm_name'] ? $p['pm_name'] : '-'); ?></td>
                            <td><span class="badge bg-info text-white"><?= html_escape($p['status']); ?></span></td>
                            <td><strong>Rp <?= number_format($p['budget'], 0, ',', '.'); ?></strong></td>
                            <td><?= number_format($p['total_estimated_hours'], 1); ?> Jam</td>
                            <td class="pe-3"><span class="badge bg-success fs-6"><?= number_format($p['total_logged_hours'], 1); ?> Jam</span></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="9" class="text-center p-4 text-muted">Belum ada data project costing.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
