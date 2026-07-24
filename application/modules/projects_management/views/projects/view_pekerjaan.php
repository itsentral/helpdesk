<div class="modal-header bg-info text-white py-2">
    <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-eye me-1"></i> Riwayat Pekerjaan</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-3" style="max-height: 75vh; overflow-y: auto;">
    <!-- Info Header -->
    <div class="card border mb-3">
        <div class="card-body p-3">
            <div class="row g-2">
                <div class="col-6 col-md-2">
                    <small class="text-muted d-block">Modul</small>
                    <strong class="text-primary"><?= html_escape($tahapan['module_name']); ?></strong>
                </div>
                <div class="col-12 col-md-4">
                    <small class="text-muted d-block">Tahapan</small>
                    <strong><?= html_escape($tahapan['tahapan_name']); ?></strong>
                </div>
                <div class="col-6 col-md-2">
                    <small class="text-muted d-block">PIC</small>
                    <strong><?= html_escape($tahapan['pic_name'] ? $tahapan['pic_name'] : '-'); ?></strong>
                </div>
                <div class="col-4 col-md-1">
                    <small class="text-muted d-block">Plan MH</small>
                    <strong><?= $tahapan['plan_manhour']; ?></strong>
                </div>
                <div class="col-4 col-md-2">
                    <small class="text-muted d-block">Due Date</small>
                    <strong><?= $tahapan['plan_due_date'] ? date('d M Y', strtotime($tahapan['plan_due_date'])) : '-'; ?></strong>
                </div>
                <div class="col-4 col-md-1">
                    <small class="text-muted d-block">Aktual MH</small>
                    <strong class="text-success"><?= $total_manhour; ?></strong>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($tasks_by_version)): ?>
        <?php $total_versions = count($tasks_by_version); ?>
        <?php foreach ($tasks_by_version as $version => $tasks): ?>
        <div class="mb-3">
            <?php if ($total_versions > 1): ?>
                <span class="badge bg-dark mb-1">Versi <?= $version; ?></span>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="40">No</th>
                            <th width="90">Tanggal</th>
                            <th>Aktivitas</th>
                            <th width="50">MH</th>
                            <th>Keterangan</th>
                            <th width="100">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($tasks as $t):
                            $file_url = !empty($t['file_name_hash']) ? base_url('uploads/projects_management/' . $t['file_name_hash']) : '';
                            $ext = !empty($t['file_name_hash']) ? strtolower(pathinfo($t['file_name_hash'], PATHINFO_EXTENSION)) : '';
                            $is_image = in_array($ext, array('jpg', 'jpeg', 'png'));
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center small"><?= date('d M Y', strtotime($t['task_date'])); ?></td>
                            <td class="small"><?= html_escape($t['task_description']); ?></td>
                            <td class="text-center fw-bold"><?= $t['manhour']; ?></td>
                            <td class="small"><?= html_escape($t['remarks'] ? $t['remarks'] : '-'); ?></td>
                            <td class="text-center">
                                <?php if (!empty($t['file_name_hash'])): ?>
                                    <?php if ($is_image): ?>
                                        <img src="<?= $file_url; ?>" class="viewer-img" style="width:28px;height:28px;object-fit:cover;cursor:pointer;border-radius:3px;" />
                                        <a href="<?= $file_url; ?>" download class="btn btn-sm btn-outline-success ms-1"><i class="fa fa-download"></i></a>
                                    <?php else: ?>
                                        <a href="<?= $file_url; ?>" download class="btn btn-sm btn-outline-success"><i class="fa fa-download"></i></a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="card bg-light p-2 text-end">
            <strong class="small">Total Manhour: <span class="text-primary"><?= $total_manhour; ?></span></strong>
        </div>
    <?php else: ?>
        <div class="text-center text-muted py-4"><i class="fa fa-info-circle me-1"></i> Belum ada riwayat pekerjaan.</div>
    <?php endif; ?>

    <!-- Rollback History -->
    <?php if (!empty($rollback_history)): ?>
    <div class="card border border-warning mt-3">
        <div class="card-header bg-warning bg-opacity-10 py-2">
            <strong class="small text-warning"><i class="fa fa-undo me-1"></i> Riwayat Rollback</strong>
        </div>
        <div class="card-body p-2">
            <?php foreach ($rollback_history as $rh): ?>
            <div class="d-flex align-items-start gap-2 mb-2 p-2 border-bottom">
                <i class="fa fa-exclamation-circle text-warning mt-1"></i>
                <div class="small">
                    <strong><?= html_escape($rh['user_name']); ?></strong> mengembalikan dari 
                    <strong>Step <?= $rh['rolled_back_from_order']; ?> (<?= html_escape($rh['from_tahapan_name']); ?>)</strong>
                    ke <strong>Step <?= $rh['rolled_back_to_order']; ?> (<?= html_escape($rh['to_tahapan_name']); ?>)</strong>
                    <br>
                    <span class="text-muted"><?= date('d M Y H:i', strtotime($rh['created_at'])); ?></span>
                    <br>
                    <span class="text-danger">Alasan: <?= html_escape($rh['reason']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal-footer py-2">
    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>

<script>
(function() {
    // Init ViewerJS on all images in this modal
    var containers = document.querySelectorAll('#modal-tahapan-content tbody');
    containers.forEach(function(c) {
        if (c && typeof Viewer !== 'undefined') {
            new Viewer(c, { toolbar: { zoomIn:true, zoomOut:true, rotateLeft:true, rotateRight:true, reset:true }, navbar:false, title:true });
        }
    });
})();
</script>
